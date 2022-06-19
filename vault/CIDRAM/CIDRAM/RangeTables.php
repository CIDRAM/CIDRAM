<?php
/**
 * This file is an optional extension of the CIDRAM package.
 * Homepage: https://cidram.github.io/
 *
 * CIDRAM COPYRIGHT 2016 and beyond by Caleb Mazalevskis (Maikuolan).
 *
 * License: GNU/GPLv2
 * @see LICENSE.txt
 *
 * This file: Methods used by the range tables page (last modified: 2022.06.19).
 */

namespace CIDRAM\CIDRAM;

trait RangeTables
{
    /**
     * Tally IPv6 count.
     *
     * @param array $Arr
     * @param int $Range (1-128)
     * @return void
     */
    private function rangeTablesTallyIpv6(array &$Arr, int $Range)
    {
        $Order = ceil($Range / 16) - 1;
        $Arr[$Order] += 2 ** ((128 - $Range) % 16);
    }

    /**
     * Finalise IPv6 count.
     *
     * @param array $Arr Values of the IPv6 octets.
     * @return array A base value (first parameter), and the power it would need to
     *      be raised by (second parameter) to accurately reflect the total amount.
     */
    private function rangeTablesFinaliseIpv6(array $Arr): array
    {
        for ($Iter = 7; $Iter > 0; $Iter--) {
            if (!empty($Arr[$Iter + 1])) {
                $Arr[$Iter] += (floor($Arr[$Iter + 1] / 655.36) / 100);
            }
            while ($Arr[$Iter] >= self::MAX_BLOCKSIZE) {
                $Arr[$Iter] -= self::MAX_BLOCKSIZE;
                $Arr[$Iter - 1] += 1;
            }
        }
        $Power = 0;
        foreach ($Arr as $Order => $Value) {
            if ($Value) {
                $Power = (7 - $Order) * 16;
                break;
            }
        }
        return [$Value, $Power];
    }

    /**
     * Fetch some data about CIDRs.
     *
     * @param string $Data The signature file data.
     * @param int $Offset The current offset position.
     * @param string $Needle A needle to identify the parts of the data we're looking for.
     * @param bool $HasOrigin Whether the data has an origin tag.
     * @return array|bool The CIDR's parameter and origin (when available), or false on failure.
     */
    private function rangeTablesFetchLine(string &$Data, int &$Offset, string &$Needle, bool &$HasOrigin)
    {
        $Check = strpos($Data, $Needle, $Offset);
        if ($Check !== false) {
            $NeedleLen = strlen($Needle);
            $LFPos = strpos($Data, "\n", $Check + $NeedleLen);
            if ($LFPos !== false) {
                if ($Deduct = $LFPos - $Check - $NeedleLen) {
                    $Param = trim(substr($Data, $Check + $NeedleLen + 1, $Deduct - 1));
                    $Offset = $Check + $NeedleLen + strlen($Param) + 1;
                } else {
                    $Param = '';
                    $Offset = $Check + $NeedleLen;
                }
            } else {
                $Param = trim(substr($Data, $Check + $NeedleLen));
                $Offset = false;
            }
            $From = $Check > 128 ? $Check - 128 : 0;
            $CPos = strrpos(substr($Data, $From, $Check - $From), "\n");
            if (substr($Data, $CPos + 1, 1) === '#') {
                return false;
            }
            $Origin = '??';
            $Tag = '';
            $CPos = strpos($Data, "\n\n", $Offset);
            if ($Offset !== false) {
                if ($HasOrigin) {
                    $OPos = strpos($Data, "\nOrigin: ", $Offset);
                    if ($OPos !== false && ($CPos === false || $CPos > $OPos)) {
                        $Origin = substr($Data, $OPos + 9, 2);
                    }
                }
                $TPos = strpos($Data, "\nTag: ", $Offset);
                if ($TPos !== false && ($CPos === false || $CPos > $TPos)) {
                    $TEPos = strpos($Data, "\n", $TPos + 1);
                    $Tag = substr($Data, $TPos + 6, $TEPos - $TPos - 6);
                }
            }
            $Param = preg_replace([
                '~ until (\d{4})[.-](\d\d)[.-](\d\d)$~i',
                '~ from (\d{4})[.-](\d\d)[.-](\d\d)$~i'
            ], '', trim($Param));
            return ['Param' => $Param, 'Origin' => $Origin, 'Tag' => $Tag];
        }
        $Offset = false;
        return false;
    }

    /**
     * Iterate range tables files.
     *
     * @param array $Arr Where we're populating it all.
     * @param array $Files The currently active (IPv4 or IPv6) signature files.
     * @param array $SigTypes The various types of signatures supported.
     * @param int $MaxRange (32 or 128).
     * @param string $IPType (IPv4 or IPv6).
     * @return void
     */
    private function rangeTablesIterateFiles(array &$Arr, array $Files, array $SigTypes, int $MaxRange, string $IPType): void
    {
        if (!isset($this->CIDRAM['Ignore'])) {
            $this->CIDRAM['Ignore'] = $this->fetchIgnores();
        }
        foreach ($Files as $File) {
            $File = (strpos($File, ':') === false) ? $File : substr($File, strpos($File, ':') + 1);
            $Data = $this->readFile($this->SignaturesPath . $File);
            if (strlen($Data) === 0) {
                continue;
            }
            if (isset($this->FE['Matrix-Data']) && class_exists('\Maikuolan\Common\Matrix') && function_exists('imagecreatetruecolor')) {
                $this->FE['Matrix-Data'] .= $Data;
            }
            $this->normaliseLinebreaks($Data);
            $HasOrigin = (strpos($Data, "\nOrigin: ") !== false);
            foreach ($SigTypes as $SigType) {
                for ($Range = 1; $Range <= $MaxRange; $Range++) {
                    if ($MaxRange === 32) {
                        $Order = 2 ** ($MaxRange - $Range);
                    }
                    $Offset = 0;
                    $Needle = '/' . $Range . ' ' . $SigType;
                    while ($Offset !== false) {
                        if (!$Entry = $this->rangeTablesFetchLine($Data, $Offset, $Needle, $HasOrigin)) {
                            break;
                        }
                        $Into = (!empty($Entry['Tag']) && !empty($this->CIDRAM['Ignore'][$Entry['Tag']])) ? $IPType . '-Ignored' : $IPType;
                        foreach ([$Into, $IPType . '-Total'] as $ThisInto) {
                            if (empty($Arr[$ThisInto][$SigType][$Range][$Entry['Param']])) {
                                $Arr[$ThisInto][$SigType][$Range][$Entry['Param']] = 0;
                            }
                            $Arr[$ThisInto][$SigType][$Range][$Entry['Param']]++;
                            if ($MaxRange === 32) {
                                if (empty($Arr[$ThisInto][$SigType]['Total'][$Entry['Param']])) {
                                    $Arr[$ThisInto][$SigType]['Total'][$Entry['Param']] = 0;
                                }
                                $Arr[$ThisInto][$SigType]['Total'][$Entry['Param']] += $Order;
                            } elseif ($MaxRange === 128) {
                                if (empty($Arr[$ThisInto][$SigType]['Total'][$Entry['Param']])) {
                                    $Arr[$ThisInto][$SigType]['Total'][$Entry['Param']] = [0, 0, 0, 0, 0, 0, 0, 0];
                                }
                                $this->rangeTablesTallyIpv6($Arr[$ThisInto][$SigType]['Total'][$Entry['Param']], $Range);
                            }
                            if (!$Entry['Origin']) {
                                continue;
                            }
                            if (empty($Arr[$ThisInto . '-Origin'][$SigType][$Range][$Entry['Origin']])) {
                                $Arr[$ThisInto . '-Origin'][$SigType][$Range][$Entry['Origin']] = 0;
                            }
                            $Arr[$ThisInto . '-Origin'][$SigType][$Range][$Entry['Origin']]++;
                            if ($MaxRange === 32) {
                                if (empty($Arr[$ThisInto . '-Origin'][$SigType]['Total'][$Entry['Origin']])) {
                                    $Arr[$ThisInto . '-Origin'][$SigType]['Total'][$Entry['Origin']] = 0;
                                }
                                $Arr[$ThisInto . '-Origin'][$SigType]['Total'][$Entry['Origin']] += $Order;
                            } elseif ($MaxRange === 128) {
                                if (empty($Arr[$ThisInto . '-Origin'][$SigType]['Total'][$Entry['Origin']])) {
                                    $Arr[$ThisInto . '-Origin'][$SigType]['Total'][$Entry['Origin']] = [0, 0, 0, 0, 0, 0, 0, 0];
                                }
                                $this->rangeTablesTallyIpv6($Arr[$ThisInto . '-Origin'][$SigType]['Total'][$Entry['Origin']], $Range);
                            }
                        }
                    }
                }
            }
        }
    }

    /**
     * Iterate range tables data.
     *
     * @param array $Arr
     * @param array $Out
     * @param string $JS
     * @param string $SigType
     * @param int $MaxRange (32 or 128).
     * @param string $IPType (IPv4 or IPv6).
     * @param string $ZeroPlus (txtGn, txtRd or txtOe, depending on the type of signature we're working with).
     * @param string $Class The table entry class that our JavaScript will need to work with.
     */
    private function rangeTablesIterateData(array &$Arr, array &$Out, string &$JS, string $SigType, int $MaxRange, string $IPType, string $ZeroPlus, string $Class)
    {
        for ($Range = 1; $Range <= $MaxRange; $Range++) {
            $Size = '*Math.pow(2,' . ($MaxRange - $Range) . ')';
            foreach ([$IPType, $IPType . '-Ignored', $IPType . '-Total'] as $IgnoreState) {
                if (count($Arr[$IgnoreState][$SigType][$Range])) {
                    $StatClass = $ZeroPlus;
                    arsort($Arr[$IgnoreState][$SigType][$Range]);
                    foreach ($Arr[$IgnoreState][$SigType][$Range] as $Param => &$Count) {
                        if ($IPType === 'IPv4') {
                            $ThisID = preg_replace('~[^\da-z]~i', '_', $IgnoreState . $SigType . $Range . $Param);
                            $Total = '<span id="' . $ThisID . '"></span>';
                            $JS .= 'w(\'' . $ThisID . '\',nft((' . $Count . $Size . ').toString()));';
                            $Count = $this->NumberFormatter->format($Count) . ' (' . $Total . ')';
                        } else {
                            $Count = $this->NumberFormatter->format($Count);
                        }
                        if ($Param) {
                            $Count = $Param . ' – ' . $Count;
                        }
                    }
                    $Arr[$IgnoreState][$SigType][$Range] = implode('<br />', $Arr[$IgnoreState][$SigType][$Range]);
                    if (count($Arr[$IgnoreState . '-Origin'][$SigType][$Range])) {
                        arsort($Arr[$IgnoreState . '-Origin'][$SigType][$Range]);
                        foreach ($Arr[$IgnoreState . '-Origin'][$SigType][$Range] as $Origin => &$Count) {
                            if ($IPType === 'IPv4') {
                                $ThisID = preg_replace('~[^\da-z]~i', '_', $IgnoreState . $SigType . $Range . $Origin);
                                $Total = '<span id="' . $ThisID . '"></span>';
                                $JS .= 'w(\'' . $ThisID . '\',nft((' . $Count . $Size . ').toString()));';
                                $Count = $this->NumberFormatter->format($Count) . ' (' . $Total . ')';
                            } else {
                                $Count = $this->NumberFormatter->format($Count);
                            }
                            $Count = '<code class="hB">' . $Origin . '</code> – ' . (
                                $this->FE['Flags'] && $Origin !== '??' ? '<span class="flag ' . $Origin . '"></span> – ' : ''
                            ) . $Count;
                        }
                        $Arr[$IgnoreState . '-Origin'][$SigType][$Range] = implode('<br /><br />', $Arr[$IgnoreState . '-Origin'][$SigType][$Range]);
                        $Arr[$IgnoreState][$SigType][$Range] .= '<hr />' . $Arr[$IgnoreState . '-Origin'][$SigType][$Range];
                    }
                } else {
                    $StatClass = 's';
                    $Arr[$IgnoreState][$SigType][$Range] = '';
                }
                if ($Arr[$IgnoreState][$SigType][$Range]) {
                    if (!isset($Out[$IgnoreState . '/' . $Range])) {
                        $Out[$IgnoreState . '/' . $Range] = '';
                    }
                    $Out[$IgnoreState . '/' . $Range] .= '<span style="display:none" class="' . $Class . ' ' . $StatClass . '">' . $Arr[$IgnoreState][$SigType][$Range] . '</span>';
                }
            }
        }
        foreach ([$IPType, $IPType . '-Ignored', $IPType . '-Total'] as $IgnoreState) {
            if (count($Arr[$IgnoreState][$SigType]['Total'])) {
                $StatClass = $ZeroPlus;
                if ($MaxRange === 32) {
                    arsort($Arr[$IgnoreState][$SigType]['Total']);
                } elseif ($MaxRange === 128) {
                    uasort($Arr[$IgnoreState][$SigType]['Total'], function ($A, $B): int {
                        for ($i = 0; $i < 8; $i++) {
                            if ($A[$i] !== $B[$i]) {
                                return $A[$i] > $B[$i] ? -1 : 1;
                            }
                        }
                        return 0;
                    });
                }
                foreach ($Arr[$IgnoreState][$SigType]['Total'] as $Param => &$Count) {
                    if ($MaxRange === 32) {
                        $ThisID = preg_replace('~[^\da-z]~i', '_', $IgnoreState . $SigType . 'Total' . $Param);
                        $JS .= 'w(\'' . $ThisID . '\',nft((' . $Count . ').toString()));';
                    } elseif ($MaxRange === 128) {
                        $Count = $this->rangeTablesFinaliseIpv6($Count);
                        $Count[1] = $Count[1] ? '+\' × \'+nft((2).toString())+\'<sup>^\'+nft((' . $Count[1] . ').toString())+\'</sup>\'' : '';
                        $ThisID = preg_replace('~[^\da-z]~i', '_', $IgnoreState . $SigType . 'Total' . $Param);
                        $JS .= 'w(\'' . $ThisID . '\',' . ($Count[1] ? '\'~\'+' : '') . 'nft((' . $Count[0] . ').toString())' . $Count[1] . ');';
                    }
                    $Count = '<span id="' . $ThisID . '"></span>';
                    if ($Param) {
                        $Count = $Param . ' – ' . $Count;
                    }
                }
                $Arr[$IgnoreState][$SigType]['Total'] = implode('<br />', $Arr[$IgnoreState][$SigType]['Total']);
                if (count($Arr[$IgnoreState . '-Origin'][$SigType]['Total'])) {
                    arsort($Arr[$IgnoreState . '-Origin'][$SigType]['Total']);
                    foreach ($Arr[$IgnoreState . '-Origin'][$SigType]['Total'] as $Origin => &$Count) {
                        if ($MaxRange === 32) {
                            $ThisID = preg_replace('~[^\da-z]~i', '_', $IgnoreState . $SigType . 'Total' . $Origin);
                            $JS .= 'w(\'' . $ThisID . '\',nft((' . $Count . ').toString()));';
                        } elseif ($MaxRange === 128) {
                            $Count = $this->rangeTablesFinaliseIpv6($Count);
                            $Count[1] = $Count[1] ? '+\' × \'+nft((2).toString())+\'<sup>^\'+nft((' . $Count[1] . ').toString())+\'</sup>\'' : '';
                            $ThisID = preg_replace('~[^\da-z]~i', '_', $IgnoreState . $SigType . 'Total' . $Origin);
                            $JS .= 'w(\'' . $ThisID . '\',' . ($Count[1] ? '\'~\'+' : '') . 'nft((' . $Count[0] . ').toString())' . $Count[1] . ');';
                        }
                        $Count = '<code class="hB">' . $Origin . '</code> – ' . (
                            $this->FE['Flags'] && $Origin !== '??' ? '<span class="flag ' . $Origin . '"></span> – ' : ''
                        ) . '<span id="' . $ThisID . '"></span>';
                    }
                    $Arr[$IgnoreState . '-Origin'][$SigType]['Total'] = implode('<br /><br />', $Arr[$IgnoreState . '-Origin'][$SigType]['Total']);
                    $Arr[$IgnoreState][$SigType]['Total'] .= '<hr />' . $Arr[$IgnoreState . '-Origin'][$SigType]['Total'];
                }
            } else {
                $StatClass = 's';
                $Arr[$IgnoreState][$SigType]['Total'] = '';
            }
            if ($Arr[$IgnoreState][$SigType]['Total']) {
                if (!isset($Out[$IgnoreState . '/Total'])) {
                    $Out[$IgnoreState . '/Total'] = '';
                }
                $Out[$IgnoreState . '/Total'] .= '<span style="display:none" class="' . $Class . ' ' . $StatClass . '">' . $Arr[$IgnoreState][$SigType]['Total'] . '</span>';
            }
        }
    }

    /**
     * Range tables handler.
     *
     * @param array $IPv4 The currently active IPv4 signature files.
     * @param array $IPv6 The currently active IPv6 signature files.
     * @return string Some JavaScript generated to populate the range tables data.
     */
    private function rangeTablesHandler(array $IPv4, array $IPv6): string
    {
        $Arr = ['IPv4' => [], 'IPv4-Origin' => [], 'IPv6' => [], 'IPv6-Origin' => []];
        $SigTypes = ['Run', 'Whitelist', 'Greylist', 'Deny'];
        foreach ($SigTypes as $SigType) {
            $Arr['IPv4'][$SigType] = ['Total' => []];
            $Arr['IPv4-Origin'][$SigType] = ['Total' => []];
            $Arr['IPv6'][$SigType] = ['Total' => []];
            $Arr['IPv6-Origin'][$SigType] = ['Total' => []];
            $Arr['IPv4-Ignored'][$SigType] = ['Total' => []];
            $Arr['IPv4-Ignored-Origin'][$SigType] = ['Total' => []];
            $Arr['IPv6-Ignored'][$SigType] = ['Total' => []];
            $Arr['IPv6-Ignored-Origin'][$SigType] = ['Total' => []];
            $Arr['IPv4-Total'][$SigType] = ['Total' => []];
            $Arr['IPv4-Total-Origin'][$SigType] = ['Total' => []];
            $Arr['IPv6-Total'][$SigType] = ['Total' => []];
            $Arr['IPv6-Total-Origin'][$SigType] = ['Total' => []];
            for ($Range = 1; $Range <= 32; $Range++) {
                $Arr['IPv4'][$SigType][$Range] = [];
                $Arr['IPv4-Origin'][$SigType][$Range] = [];
                $Arr['IPv4-Ignored'][$SigType][$Range] = [];
                $Arr['IPv4-Ignored-Origin'][$SigType][$Range] = [];
                $Arr['IPv4-Total'][$SigType][$Range] = [];
                $Arr['IPv4-Total-Origin'][$SigType][$Range] = [];
            }
            for ($Range = 1; $Range <= 128; $Range++) {
                $Arr['IPv6'][$SigType][$Range] = [];
                $Arr['IPv6-Origin'][$SigType][$Range] = [];
                $Arr['IPv6-Ignored'][$SigType][$Range] = [];
                $Arr['IPv6-Ignored-Origin'][$SigType][$Range] = [];
                $Arr['IPv6-Total'][$SigType][$Range] = [];
                $Arr['IPv6-Total-Origin'][$SigType][$Range] = [];
            }
        }
        $Out = [];
        $this->rangeTablesIterateFiles($Arr, $IPv4, $SigTypes, 32, 'IPv4');
        $this->rangeTablesIterateFiles($Arr, $IPv6, $SigTypes, 128, 'IPv6');
        $this->FE['Labels'] = '';
        $JS = '';
        $RangeCatOptions = [];
        foreach ($SigTypes as $SigType) {
            $Class = 'sigtype_' . strtolower($SigType);
            $RangeCatOptions[] = '<option value="' . $Class . '">' . $SigType . '</option>';
            $this->FE['Labels'] .= '<span style="display:none" class="s ' . $Class . '">' . $this->L10N->getString('label_signature_type') . ' ' . $SigType . '</span>';
            if ($SigType === 'Run') {
                $ZeroPlus = 'txtOe';
            } else {
                $ZeroPlus = ($SigType === 'Whitelist' || $SigType === 'Greylist') ? 'txtGn' : 'txtRd';
            }
            $this->rangeTablesIterateData($Arr, $Out, $JS, $SigType, 32, 'IPv4', $ZeroPlus, $Class);
            $this->rangeTablesIterateData($Arr, $Out, $JS, $SigType, 128, 'IPv6', $ZeroPlus, $Class);
        }
        $this->FE['rangeCatOptions'] = implode("\n            ", $RangeCatOptions);
        $this->FE['RangeRows'] = '';
        foreach ([['IPv4', 32], ['IPv6', 128]] as $Build) {
            for ($Range = 1; $Range <= $Build[1]; $Range++) {
                foreach ([
                    [$Build[0] . '/' . $Range, $Build[0] . '/' . $Range],
                    [$Build[0] . '-Ignored/' . $Range, $Build[0] . '/' . $Range . ' (' . $this->L10N->getString('state_ignored') . ')'],
                    [$Build[0] . '-Total/' . $Range, $Build[0] . '/' . $Range . ' (' . $this->L10N->getString('label_total') . ')']
                ] as $Label) {
                    if (!empty($Out[$Label[0]])) {
                        foreach ($SigTypes as $SigType) {
                            $Class = 'sigtype_' . strtolower($SigType);
                            if (strpos($Out[$Label[0]], $Class) === false) {
                                $Out[$Label[0]] .= '<span style="display:none" class="' . $Class . ' s">-</span>';
                            }
                        }
                        $ThisArr = ['RangeType' => $Label[1], 'NumOfCIDRs' => $Out[$Label[0]], 'state_loading' => $this->L10N->getString('state_loading')];
                        $this->FE['RangeRows'] .= $this->parseVars($ThisArr, $this->FE['RangeRow']);
                    }
                }
            }
        }
        $Loading = $this->L10N->getString('state_loading');
        foreach ([
            ['', $this->L10N->getString('label_total')],
            ['-Ignored', $this->L10N->getString('label_total') . ' (' . $this->L10N->getString('state_ignored') . ')'],
            ['-Total', $this->L10N->getString('label_total') . ' (' . $this->L10N->getString('label_total') . ')']
        ] as $Label) {
            $ThisRight = '<table><tr><td>';
            $InternalIPv4 = 'IPv4' . $Label[0] . '/Total';
            $InternalIPv6 = 'IPv6' . $Label[0] . '/Total';
            if (isset($Out[$InternalIPv4]) && strlen($Out[$InternalIPv4])) {
                foreach ($SigTypes as $SigType) {
                    $Class = 'sigtype_' . strtolower($SigType);
                    if (strpos($Out[$InternalIPv4], $Class) === false) {
                        $Out[$InternalIPv4] .= '<span style="display:none" class="' . $Class . ' s">-</span>';
                    }
                }
                $ThisRight .= $Out[$InternalIPv4];
            }
            $ThisRight .= '</td><td>';
            if (isset($Out[$InternalIPv6]) && strlen($Out[$InternalIPv6])) {
                foreach ($SigTypes as $SigType) {
                    $Class = 'sigtype_' . strtolower($SigType);
                    if (strpos($Out[$InternalIPv6], $Class) === false) {
                        $Out[$InternalIPv6] .= '<span style="display:none" class="' . $Class . ' s">-</span>';
                    }
                }
                $ThisRight .= $Out[$InternalIPv6];
            }
            $ThisRight .= '</td></tr></table>';
            $this->FE['RangeRows'] .= $this->parseVars([
                'RangeType' => $Label[1],
                'NumOfCIDRs' => $ThisRight,
                'state_loading' => $Loading
            ], $this->FE['RangeRow']);
        }
        return $JS;
    }

    /**
     * Returns a callback closure used by the matrix handler to increment coordinates.
     *
     * @return \Closure
     */
    private function matrixIncrement(): \Closure
    {
        /**
         * @param string $Current The value of the current coordinate.
         * @param string $Key The key of the current coordinate (expected, but not used by this callback).
         * @param string $Previous The value of the previous coordinate (expected, but not used by this callback).
         * @param string $KeyPrevious The key of the previous coordinate (expected, but not used by this callback).
         * @param string $Next The value of the next coordinate (expected, but not used by this callback).
         * @param string $KeyNext The key of the next coordinate (expected, but not used by this callback).
         * @param string $Step Can be used to manipulate the vector trajectory (expected, but not used by this callback).
         * @param string $Amount Contains information such as the type and amount of value to be added to the coordinate.
         */
        return function (&$Current, $Key, &$Previous, $KeyPrevious, &$Next, $KeyNext, &$Step, $Amount) {
            if (
                !is_array($Current) ||
                !isset($Current['R'], $Current['G'], $Current['B'], $Amount[0], $Amount[1]) ||
                ($Amount[0] !== 'R' && $Amount[0] !== 'G' && $Amount[0] !== 'B')
            ) {
                return;
            }
            $Current[$Amount[0]] += $Amount[1];
        };
    }

    /**
     * Returns a callback closure used by the matrix handler to limit a coordinate's RGB values.
     *
     * @return \Closure
     */
    private function matrixLimit(): \Closure
    {
        /**
         * @param string $Current The value of the current coordinate.
         */
        return function (&$Current) {
            if (!is_array($Current) || !isset($Current['R'], $Current['G'], $Current['B'])) {
                return;
            }
            foreach ($Current as &$RGB) {
                if (!is_int($RGB) || $RGB < 0) {
                    $RGB = 0;
                }
                $RGB = ceil($RGB);
                if ($RGB > 255) {
                    $RGB = 255;
                }
            }
        };
    }

    /**
     * Returns a callback closure used by the matrix handler to draw an image from a matrix.
     *
     * @return \Closure
     */
    private function matrixDraw(): \Closure
    {
        /**
         * @param string $Current The value of the current coordinate.
         * @param string $Key The key of the current coordinate.
         * @param string $Previous The value of the previous coordinate (expected, but not used by this callback).
         * @param string $KeyPrevious The key of the previous coordinate (expected, but not used by this callback).
         * @param string $Next The value of the next coordinate (expected, but not used by this callback).
         * @param string $KeyNext The key of the next coordinate (expected, but not used by this callback).
         * @param string $Step Can be used to manipulate the vector trajectory (expected, but not used by this callback).
         * @param string $Offsets Contains offsets between the matrix coordinates to the image XY coordinates.
         * @return void
         */
        return function (&$Current, $Key, &$Previous, $KeyPrevious, &$Next, $KeyNext, &$Step, $Offsets) {
            if (!is_array($Current) || !is_array($Offsets) || !isset($Current['R'], $Current['G'], $Current['B'], $this->CIDRAM['Matrix-Image'])) {
                return;
            }
            $Colour = ($Current['R'] * 65536) + ($Current['G'] * 256) + $Current['B'];
            $XY = explode(',', $Key);
            $X = $XY[0] ?? 0;
            $Y = $XY[1] ?? 0;
            if (is_array($Offsets) && isset($Offsets[0], $Offsets[1]) && is_int($Offsets[0]) && is_int($Offsets[1])) {
                $X += $Offsets[0];
                $Y += $Offsets[1];
            }
            imagesetpixel($this->CIDRAM['Matrix-Image'], $X, $Y, $Colour);
        };
    }

    /**
     * Yields the ranges of the signatures in the currently active signature
     * files as values to be used as coordinates in the matrices generated by
     * the matrix handler for use at the front-end range tables page.
     *
     * @param string $Source The contents of the currently active signature files.
     */
    private function matrixCreateGenerator(string &$Source): \Generator
    {
        $SPos = 0;
        while (($FPos = strpos($Source, "\n", $SPos)) !== false) {
            $Mark = [];
            if (isset($this->CIDRAM['Matrix-%Print'])) {
                $this->CIDRAM['Matrix-%Print']();
            }
            $Line = substr($Source, $SPos, $FPos - $SPos);
            $SPos = $FPos + 1;
            if ($Line === '' || substr($Line, 0, 1) === '#') {
                continue;
            }
            $Matches = [];
            if (preg_match('~^(\d+).(\d+).(\d+).(\d+)/(\d+) (Deny|Whitelist|Greylist|Run)~', $Line, $Matches)) {
                $Mark['6or4'] = 4;
                if ($Matches[6] === 'Deny') {
                    $Mark['Colour'] = 'R';
                } elseif ($Matches[6] === 'Run') {
                    $Mark['Colour'] = 'B';
                } else {
                    $Mark['Colour'] = 'G';
                }
                if ($Matches[5] <= 8) {
                    for ($Iterant = $Matches[5], $To = 256; $Iterant > 0; $Iterant--) {
                        $To /= 2;
                    }
                    $To += $Matches[1] - 1;
                    $Mark['Range'] = $Matches[1] . '-' . $To . ',0-255';
                    $Mark['Amount'] = 255;
                } elseif ($Matches[5] <= 16) {
                    for ($Iterant = $Matches[5] - 8, $To = 256; $Iterant > 0; $Iterant--) {
                        $To /= 2;
                    }
                    $To += $Matches[2] - 1;
                    $Mark['Range'] = $Matches[1] . ',' . $Matches[2] . '-' . $To;
                    $Mark['Amount'] = 255;
                } elseif ($Matches[5] <= 24) {
                    for ($Iterant = $Matches[5] - 16, $To = 256; $Iterant > 0; $Iterant--) {
                        $To /= 2;
                    }
                    $To += $Matches[3] - 1;
                    $Mark['Range'] = $Matches[1] . ',' . $Matches[2];
                    $Mark['Amount'] = $To;
                } else {
                    for ($Iterant = $Matches[5] - 24, $To = 256; $Iterant > 0; $Iterant--) {
                        $To /= 2;
                    }
                    $To += $Matches[4] - 1;
                    $Mark['Range'] = $Matches[1] . ',' . $Matches[2];
                    $Mark['Amount'] = $To / 256;
                }
                yield $Mark;
            } elseif (
                preg_match('~^()([\da-f]{1,2})()()\:\:/(\d+) (Deny|Whitelist|Greylist|Run)~', $Line, $Matches) ||
                preg_match('~^([\da-f]{1,2})([\da-f]{2})()()\:\:/(\d+) (Deny|Whitelist|Greylist|Run)~', $Line, $Matches) ||
                preg_match('~^([\da-f]{1,2})([\da-f]{2})\:()([\da-f]{1,2})\:\:/(\d+) (Deny|Whitelist|Greylist|Run)~', $Line, $Matches) ||
                preg_match('~^([\da-f]{1,2})([\da-f]{2})\:([\da-f]{1,2})([\da-f]{2})\:\:/(\d+) (Deny|Whitelist|Greylist|Run)~', $Line, $Matches)
            ) {
                $Mark['6or4'] = 6;
                if ($Matches[6] === 'Deny') {
                    $Mark['Colour'] = 'R';
                } elseif ($Matches[6] === 'Run') {
                    $Mark['Colour'] = 'B';
                } else {
                    $Mark['Colour'] = 'G';
                }
                if ($Matches[5] <= 8) {
                    for ($Iterant = $Matches[5], $To = 256; $Iterant > 0; $Iterant--) {
                        $To /= 2;
                    }
                    $To += hexdec($Matches[1]) - 1;
                    $Mark['Range'] = hexdec($Matches[1]) . '-' . $To . ',0-255';
                    $Mark['Amount'] = 255;
                } elseif ($Matches[5] <= 16) {
                    for ($Iterant = $Matches[5] - 8, $To = 256; $Iterant > 0; $Iterant--) {
                        $To /= 2;
                    }
                    $To += hexdec($Matches[2]) - 1;
                    $Mark['Range'] = hexdec($Matches[1]) . ',' . hexdec($Matches[2]) . '-' . $To;
                    $Mark['Amount'] = 255;
                } elseif ($Matches[5] <= 24) {
                    for ($Iterant = $Matches[5] - 16, $To = 256; $Iterant > 0; $Iterant--) {
                        $To /= 2;
                    }
                    $To += hexdec($Matches[3]) - 1;
                    $Mark['Range'] = hexdec($Matches[1]) . ',' . hexdec($Matches[2]);
                    $Mark['Amount'] = $To;
                } else {
                    for ($Iterant = $Matches[5] - 24, $To = 256; $Iterant > 0; $Iterant--) {
                        $To /= 2;
                    }
                    $To += hexdec($Matches[4]) - 1;
                    $Mark['Range'] = hexdec($Matches[1]) . ',' . hexdec($Matches[2]);
                    $Mark['Amount'] = $To / 256;
                }
                yield $Mark;
            }
        }
    }

    /**
     * Uses the matrix handler to create an image from the ranges of the signatures
     * in the currently active signature files (requires GD functionality).
     *
     * @param string $Source The contents of the currently active signature files.
     * @param string $Destination Where to save the image file after rendering it
    *       with the CLI tool (has no effect when CLI is false and can be omitted).
     * @param bool $CLI Should be true when called via the CLI tool and should
     *      otherwise always be false.
     * @return string Raw PNG data (or an empty string if CLI is true).
     */
    private function matrixCreate(string &$Source, string $Destination = '', bool $CLI = false): string
    {
        if ($CLI) {
            $Splits = ['Percentage' => 0, 'Skip' => 0];
            $Splits['Max'] = substr_count($Source, "\n");
            $this->CIDRAM['Matrix-%Print'] = function () use (&$Splits) {
                $Splits['Percentage']++;
                $Splits['Skip']++;
                if ($Splits['Percentage'] >= $Splits['Max']) {
                    $Splits['Max'] = $Splits['Percentage'] + 1;
                }
                $Current = $Splits['Percentage'] / $Splits['Max'];
                if ($Splits['Skip'] > 24) {
                    $Splits['Skip'] = 0;
                    $Memory = memory_get_usage();
                    $this->formatFileSize($Memory);
                    echo "\rWorking ... " . $this->NumberFormatter->format($Current, 2) . '% (' . $this->timeFormat(time(), $this->Configuration['general']['time_format']) . ') <RAM: ' . $Memory . '>';
                }
            };
            echo "\rWorking ...";
        } elseif (isset($this->CIDRAM['Matrix-%Print'])) {
            unset($this->CIDRAM['Matrix-%Print']);
        }

        $IPv4 = new \Maikuolan\Common\Matrix();
        $IPv4->createMatrix(2, 256, ['R' => 0, 'G' => 0, 'B' => 0]);

        $IPv6 = new \Maikuolan\Common\Matrix();
        $IPv6->createMatrix(2, 256, ['R' => 0, 'G' => 0, 'B' => 0]);

        foreach ($this->matrixCreateGenerator($Source) as $Mark) {
            if ($Mark['6or4'] === 4) {
                $IPv4->iterateCallback($Mark['Range'], $this->matrixIncrement(), $Mark['Colour'], $Mark['Amount']);
            } elseif ($Mark['6or4'] === 6) {
                $IPv6->iterateCallback($Mark['Range'], $this->matrixIncrement(), $Mark['Colour'], $Mark['Amount']);
            }
        }

        $IPv4->iterateCallback('0-255,0-255', $this->matrixLimit());
        $IPv6->iterateCallback('0-255,0-255', $this->matrixLimit());

        $Wheel = new \Maikuolan\Common\Matrix();
        $Wheel->createMatrix(2, 24, ['R' => 0, 'G' => 0, 'B' => 0]);
        $Wheel->iterateCallback('0-8,12-15', $this->matrixIncrement(), 'R', 32);
        $Wheel->iterateCallback('0-8,8-15', $this->matrixIncrement(), 'R', 32);
        $Wheel->iterateCallback('0-8,4-15', $this->matrixIncrement(), 'R', 32);
        $Wheel->iterateCallback('0-8,0-15', $this->matrixIncrement(), 'R', 32);
        $Wheel->iterateCallback('5-13,16-19', $this->matrixIncrement(), 'B', 32);
        $Wheel->iterateCallback('5-13,12-19', $this->matrixIncrement(), 'B', 32);
        $Wheel->iterateCallback('5-13,8-19', $this->matrixIncrement(), 'B', 32);
        $Wheel->iterateCallback('5-13,4-19', $this->matrixIncrement(), 'B', 32);
        $Wheel->iterateCallback('10-18,20-23', $this->matrixIncrement(), 'G', 32);
        $Wheel->iterateCallback('10-18,16-23', $this->matrixIncrement(), 'G', 32);
        $Wheel->iterateCallback('10-18,12-23', $this->matrixIncrement(), 'G', 32);
        $Wheel->iterateCallback('10-18,8-23', $this->matrixIncrement(), 'G', 32);

        $this->CIDRAM['Matrix-Image'] = imagecreatetruecolor(544, 308);

        /** Roofs. */
        imageline($this->CIDRAM['Matrix-Image'], 10, 48, 269, 48, 16777215);
        imageline($this->CIDRAM['Matrix-Image'], 284, 48, 543, 48, 16777215);

        /** Walls. */
        imageline($this->CIDRAM['Matrix-Image'], 10, 48, 10, 307, 16777215);
        imageline($this->CIDRAM['Matrix-Image'], 269, 48, 269, 307, 16777215);
        imageline($this->CIDRAM['Matrix-Image'], 284, 48, 284, 307, 16777215);
        imageline($this->CIDRAM['Matrix-Image'], 543, 48, 543, 307, 16777215);

        /** Floors. */
        imageline($this->CIDRAM['Matrix-Image'], 10, 307, 269, 307, 16777215);
        imageline($this->CIDRAM['Matrix-Image'], 284, 307, 543, 307, 16777215);

        imagestring($this->CIDRAM['Matrix-Image'], 2, 12, 2, 'CIDRAM signature file analysis (image generated ' . date('Y.m.d', time()) . ').', 16777215);
        imagefilledrectangle($this->CIDRAM['Matrix-Image'], 12, 14, 22, 24, 16711680);
        imagestring($this->CIDRAM['Matrix-Image'], 2, 24, 12, '"Deny" signatures', 16711680);
        imagefilledrectangle($this->CIDRAM['Matrix-Image'], 130, 14, 140, 24, 65280);
        imagestring($this->CIDRAM['Matrix-Image'], 2, 142, 12, '"Whitelist" + "Greylist" signatures', 65280);
        imagefilledrectangle($this->CIDRAM['Matrix-Image'], 356, 14, 366, 24, 255);
        imagestring($this->CIDRAM['Matrix-Image'], 2, 368, 12, '"Run" signatures', 255);

        imagestring($this->CIDRAM['Matrix-Image'], 2, 14, 36, '< 0.0.0.0/8        IPv4      255.0.0.0/8 >', 16777215);
        imagestring($this->CIDRAM['Matrix-Image'], 2, 288, 36, '< 00xx::/8         IPv6         ffxx::/8 >', 16777215);
        imagestringup($this->CIDRAM['Matrix-Image'], 2, -2, 304, '< x.255.0.0/16                x.0.0.0/16 >', 16777215);
        imagestringup($this->CIDRAM['Matrix-Image'], 2, 272, 304, '< xxff::/16                    xx00::/16 >', 16777215);

        $IPv4->iterateCallback('0-255,0-255', $this->matrixDraw(), 12, 50);
        $IPv6->iterateCallback('0-255,0-255', $this->matrixDraw(), 286, 50);
        $Wheel->iterateCallback('0-24,0-24', $this->matrixDraw(), 519, 2);

        if ($CLI) {
            if (!$Destination) {
                $Destination = 'export.png';
            }
            imagepng($this->CIDRAM['Matrix-Image'], $this->Vault . $Destination);
            $Memory = memory_get_usage();
            $this->formatFileSize($Memory);
            echo "\rWorking ... " . $this->NumberFormatter->format(100, 2) . '% (' . $this->timeFormat(time(), $this->Configuration['general']['time_format']) . ') <RAM: ' . $Memory . '>' . "\n\n";
        } else {
            ob_start();
            imagepng($this->CIDRAM['Matrix-Image']);
            $Out = ob_get_contents();
            ob_end_clean();
            return $Out;
        }
        imagedestroy($this->CIDRAM['Matrix-Image']);
        return '';
    }
}
