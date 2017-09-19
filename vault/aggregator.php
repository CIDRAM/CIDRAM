<?php
/**
 * This file is a part of the CIDRAM package.
 * Homepage: https://cidram.github.io/
 *
 * CIDRAM COPYRIGHT 2016 and beyond by Caleb Mazalevskis (Maikuolan).
 *
 * License: GNU/GPLv2
 * @see LICENSE.txt
 *
 * This file: IP aggregator (last modified: 2017.09.19).
 */

/** Prevents execution from outside of CIDRAM. */
if (!defined('CIDRAM')) {
    die('[CIDRAM] This should not be accessed directly.');
}

class Aggregator
{

    /** Input. */
    public $Input = '';

    /** Outout. */
    public $Output = '';

    /** Access parent scope data, closures, variables, etc. */
    public $CIDRAM = array();

    public function __construct(array &$CIDRAM)
    {
        $this->CIDRAM = &$CIDRAM;
    }

    /** Aggregate it! */
    public function aggregate($In)
    {
        $this->Input = $In;
        $this->Output = $In;
        $this->stripInvalidCharactersAndSort($this->Output);
        $this->stripInvalidRangesAndSubs($this->Output);
        $this->mergeRanges($this->Output);
        return $this->Output;
    }

    /** Strips invalid characters from lines and sorts entries. */
    private function stripInvalidCharactersAndSort(&$In)
    {
        $In = explode("\n", strtolower(trim(str_replace("\r", '', $In))));
        if (isset($this->CIDRAM['Results'])) {
            $this->CIDRAM['Results']['In'] = count($In);
        }
        $In = array_filter(array_unique(array_map(function ($Line) {
            $Line = preg_replace(array('~^[^0-9a-f:./]*~i', '~[ \t].*$~', '~[^0-9a-f:./]*$~i'), '', $Line);
            return (!$Line || !preg_match('~[0-9a-f:./]+~i', $Line) || preg_match('~[^0-9a-f:./]+~i', $Line)) ? '' : $Line;
        }, $In)));
        usort($In, function ($A, $B) {
            if (($Pos = strpos($A, '/')) !== false) {
                $ASize = (int)substr($A, $Pos + 1);
                $A = substr($A, 0, $Pos);
            } else {
                $ASize = 0;
            }
            $A = empty($A) || (
                !$this->CIDRAM['ExpandIPv4']($A, true) && !$this->CIDRAM['ExpandIPv6']($A, true)
            ) ? '' : inet_pton($A);
            if (($Pos = strpos($B, '/')) !== false) {
                $BSize = (int)substr($B, $Pos + 1);
                $B = substr($B, 0, $Pos);
            } else {
                $BSize = 0;
            }
            $B = empty($B) || (
                !$this->CIDRAM['ExpandIPv4']($B, true) && !$this->CIDRAM['ExpandIPv6']($B, true)
            ) ? '' : inet_pton($B);
            if ($A === false) {
                if ($B === false) {
                    return 0;
                }
                return 1;
            }
            if ($B === false) {
                return -1;
            }
            $Compare = strcmp($A, $B);
            if ($Compare === 0) {
                if ($ASize === $BSize) {
                    return 0;
                }
                return ($ASize > $BSize) ? 1 : -1;
            }
            return ($Compare < 0) ? -1 : 1;
        });
        $In = implode("\n", $In);
    }

    /** Strips invalid ranges and subordinates. */
    private function stripInvalidRangesAndSubs(&$In)
    {
        $In = $Out = "\n" . $In . "\n";
        $Offset = 0;
        while (($NewLine = strpos($In, "\n", $Offset)) !== false) {
            $Line = substr($In, $Offset, $NewLine - $Offset);
            $Offset = $NewLine + 1;
            if (!$Line) {
                continue;
            }
            if (($RangeSep = strpos($Line, '/')) !== false) {
                $Size = (int)substr($Line, $RangeSep + 1);
                $CIDR = substr($Line, 0, $RangeSep);
            } else {
                $Size = false;
                $CIDR = $Line;
            }
            if (!$CIDRs = $this->CIDRAM['ExpandIPv4']($CIDR)) {
                if (!$CIDRs = $this->CIDRAM['ExpandIPv6']($CIDR)) {
                    $Out = str_replace("\n" . $Line . "\n", "\n", $Out);
                    continue;
                }
            }
            $Ranges = count($CIDRs);
            if ($Size === false) {
                $Size = $Ranges;
                $Out = str_replace("\n" . $CIDR . "\n", "\n" . $CIDRs[$Size - 1] . "\n", $Out);
            } elseif (!isset($CIDRs[$Size - 1]) || $Line !== $CIDRs[$Size - 1]) {
                $Out = str_replace("\n" . $Line . "\n", "\n", $Out);
                continue;
            }
            for ($Range = $Size - 2; $Range >= 0; $Range--) {
                if (isset($CIDRs[$Range]) && strpos($Out, "\n" . $CIDRs[$Range] . "\n") !== false) {
                    $Out = str_replace("\n" . $Line . "\n", "\n", $Out);
                    if (isset($this->CIDRAM['Results'])) {
                        $this->CIDRAM['Results']['Merged']++;
                    }
                    break;
                }
            }
        }
        $In = trim($Out);
        if (isset($this->CIDRAM['Results'])) {
            $this->CIDRAM['Results']['Out'] = empty($In) ? 0 : substr_count($In, "\n") + 1;
            $this->CIDRAM['Results']['Rejected'] = $this->CIDRAM['Results']['In'] - $this->CIDRAM['Results']['Out'] - $this->CIDRAM['Results']['Merged'];
            $this->CIDRAM['Results']['Accepted'] = $this->CIDRAM['Results']['In'] - $this->CIDRAM['Results']['Rejected'];
        }
    }

    /** Merges ranges. */
    private function mergeRanges(&$In)
    {
        while (true) {
            $Step = $In;
            $In = $Out = "\n" . $In . "\n";
            $Size = $Offset = 0;
            $CIDR = $Line = '';
            $CIDRs = false;
            while (($NewLine = strpos($In, "\n", $Offset)) !== false) {
                $PrevLine = $Line;
                $PrevSize = $Size;
                $PrevCIDRs = $CIDRs;
                $Line = substr($In, $Offset, $NewLine - $Offset);
                $Offset = $NewLine + 1;
                $RangeSep = strpos($Line, '/');
                $Size = (int)substr($Line, $RangeSep + 1);
                $CIDR = substr($Line, 0, $RangeSep);
                if (!$CIDRs = $this->CIDRAM['ExpandIPv4']($CIDR)) {
                    $CIDRs = $this->CIDRAM['ExpandIPv6']($CIDR);
                }
                if (
                    !empty($CIDRs[$Size - 1]) &&
                    !empty($PrevCIDRs[$PrevSize - 1]) &&
                    !empty($CIDRs[$Size - 2]) &&
                    !empty($PrevCIDRs[$PrevSize - 2]) &&
                    $CIDRs[$Size - 2] === $PrevCIDRs[$PrevSize - 2]
                ) {
                    $Out = str_replace("\n" . $PrevLine . "\n" . $Line . "\n", "\n" . $CIDRs[$Size - 2] . "\n", $Out);
                    $Line = $CIDRs[$Size - 2];
                    $Size--;
                    if (isset($this->CIDRAM['Results'])) {
                        $this->CIDRAM['Results']['Merged']++;
                        $this->CIDRAM['Results']['Out']--;
                    }
                }
            }
            $In = trim($Out);
            if ($Step === $In) {
                break;
            }
        }
    }

}
