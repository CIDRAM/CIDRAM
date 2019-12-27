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
 * This file: IP aggregator (last modified: 2019.12.26).
 */

namespace CIDRAM\Aggregator;

class Aggregator
{
    /** Input. */
    public $Input = '';

    /** Outout. */
    public $Output = '';

    /** Access parent scope data, closures, variables, etc. */
    public $CIDRAM = [];

    /** Conversion tables for netmasks to IPv4. */
    private $TableNetmaskIPv4 = [];

    /** Conversion tables for netmasks to IPv6. */
    private $TableNetmaskIPv6 = [];

    /** Conversion tables for IPv4 to netmasks. */
    private $TableIPv4Netmask = [];

    /** Conversion tables for IPv6 to netmasks. */
    private $TableIPv6Netmask = [];

    /** Specifies the format to use for Aggregator output. 0 = CIDR notation [default]. 1 = Netmask notation. */
    private $Mode = 0;

    /** Optional callback. */
    public $callbacks = [];

    public function __construct(array &$CIDRAM, $Mode = 0)
    {
        $this->constructTables();
        $this->CIDRAM = &$CIDRAM;
        $this->Mode = $Mode;
    }

    /** Construct netmask<->CIDR conversion tables. */
    private function constructTables()
    {
        $CIDR = 32;
        for ($Octet = 4; $Octet > 0; $Octet--) {
            $Base = str_repeat('255.', $Octet - 1);
            $End = str_repeat('.0', 4 - $Octet);
            for ($Addresses = 1, $Iterate = 0; $Iterate < 8; $Iterate++, $Addresses *= 2, $CIDR--) {
                $Netmask = $Base . (256 - $Addresses) . $End;
                $this->TableNetmaskIPv4[$CIDR] = $Netmask;
                $this->TableIPv4Netmask[$Netmask] = $CIDR;
            }
        }
        $CIDR = 128;
        for ($Octet = 8; $Octet > 0; $Octet--) {
            $Base = str_repeat('ffff:', $Octet - 1);
            $End = ($Octet === 8) ? ':0' : '::';
            for ($Addresses = 1, $Iterate = 0; $Iterate < 16; $Iterate++, $Addresses *= 2, $CIDR--) {
                $Netmask = $Base . (dechex(65536 - $Addresses)) . $End;
                $this->TableNetmaskIPv6[$CIDR] = $Netmask;
                $this->TableIPv6Netmask[$Netmask] = $CIDR;
            }
        }
    }

    /** Aggregate it! */
    public function aggregate($In)
    {
        $this->Input = $In;
        $this->Output = $In;
        $this->stripInvalidCharactersAndSort($this->Output);
        $this->stripInvalidRangesAndSubs($this->Output);
        $this->mergeRanges($this->Output);
        if ($this->Mode === 1) {
            $this->convertToNetmasks($this->Output);
        }
        return $this->Output;
    }

    /** Strips invalid characters from lines and sorts entries. */
    private function stripInvalidCharactersAndSort(&$In)
    {
        $In = explode("\n", strtolower(trim(str_replace("\r", '', $In))));
        $InCount = count($In);
        if (isset($this->callbacks['newParse']) && is_callable($this->callbacks['newParse'])) {
            $this->callbacks['newParse']($InCount);
        }
        if (isset($this->CIDRAM['Results'])) {
            $this->CIDRAM['Results']['In'] = $InCount;
        }
        unset($InCount);
        $In = array_filter(array_unique(array_map(function ($Line) {
            $Line = preg_replace(['~^(?:#| \*|/\*).*~', '~^[^\da-f:./]*~i', '~[ \t].*$~', '~[^\da-f:./]*$~i'], '', $Line);
            if (isset($this->callbacks['newTick']) && is_callable($this->callbacks['newTick'])) {
                $this->callbacks['newTick']();
            }
            return (!$Line || !preg_match('~[\da-f:./]+~i', $Line) || preg_match('~[^\da-f:./]+~i', $Line)) ? '' : $Line;
        }, $In)));
        usort($In, function ($A, $B) {
            if (($Pos = strpos($A, '/')) !== false) {
                $ASize = substr($A, $Pos + 1);
                $A = substr($A, 0, $Pos);
            } else {
                $ASize = 0;
            }
            $AType = 0;
            if ($this->CIDRAM['ExpandIPv4']($A, true)) {
                $AType = 4;
            } elseif ($this->CIDRAM['ExpandIPv6']($A, true)) {
                $AType = 6;
            }
            $A = $AType ? inet_pton($A) : false;
            if ($AType === 4 && isset($this->TableIPv4Netmask[$ASize])) {
                $ASize = $this->TableIPv4Netmask[$ASize];
            } elseif ($AType === 6 && isset($this->TableIPv6Netmask[$ASize])) {
                $ASize = $this->TableIPv6Netmask[$ASize];
            } else {
                $ASize = (int)$ASize;
            }
            if ($ASize === 0) {
                $ASize = ($AType === 4) ? 32 : 128;
            }
            if (($Pos = strpos($B, '/')) !== false) {
                $BSize = substr($B, $Pos + 1);
                $B = substr($B, 0, $Pos);
            } else {
                $BSize = 0;
            }
            $BType = 0;
            if ($this->CIDRAM['ExpandIPv4']($B, true)) {
                $BType = 4;
            } elseif ($this->CIDRAM['ExpandIPv6']($B, true)) {
                $BType = 6;
            }
            $B = $BType ? inet_pton($B) : false;
            if ($BType === 4 && isset($this->TableIPv4Netmask[$BSize])) {
                $BSize = $this->TableIPv4Netmask[$BSize];
            } elseif ($BType === 6 && isset($this->TableIPv6Netmask[$BSize])) {
                $BSize = $this->TableIPv6Netmask[$BSize];
            } else {
                $BSize = (int)$BSize;
            }
            if ($BSize === 0) {
                $BSize = ($BType === 4) ? 32 : 128;
            }
            if ($A === false) {
                return $B === false ? 0 : 1;
            }
            if ($B === false) {
                return -1;
            }
            if ($AType !== $BType) {
                return $AType < $BType ? -1 : 1;
            }
            $Compare = strcmp($A, $B);
            if ($Compare === 0) {
                if ($ASize === $BSize) {
                    return 0;
                }
                return $ASize > $BSize ? 1 : -1;
            }
            return $Compare < 0 ? -1 : 1;
        });
        $In = implode("\n", $In);
    }

    /** Strips invalid ranges and subordinates. */
    private function stripInvalidRangesAndSubs(&$In)
    {
        if (isset($this->callbacks['newParse']) && is_callable($this->callbacks['newParse'])) {
            $this->callbacks['newParse'](substr_count($In, "\n"));
        }
        $In = $Out = "\n" . $In . "\n";
        $Offset = 0;
        while (($NewLine = strpos($In, "\n", $Offset)) !== false) {
            if (isset($this->callbacks['newTick']) && is_callable($this->callbacks['newTick'])) {
                $this->callbacks['newTick']();
            }
            $Line = substr($In, $Offset, $NewLine - $Offset);
            $Offset = $NewLine + 1;
            if (!$Line) {
                continue;
            }
            if (($RangeSep = strpos($Line, '/')) !== false) {
                $Size = substr($Line, $RangeSep + 1);
                $CIDR = substr($Line, 0, $RangeSep);
            } else {
                $Size = 0;
                $CIDR = $Line;
            }
            if ($CIDRs = $this->CIDRAM['ExpandIPv4']($CIDR)) {
                $Type = 4;
                $Ranges = 32;
            } elseif ($CIDRs = $this->CIDRAM['ExpandIPv6']($CIDR)) {
                $Type = 6;
                $Ranges = 128;
            } else {
                $Type = 0;
                $Ranges = 0;
                $Out = str_replace("\n" . $Line . "\n", "\n", $Out);
                continue;
            }
            if ($Type === 4 && isset($this->TableIPv4Netmask[$Size])) {
                $Size = $this->TableIPv4Netmask[$Size];
            } elseif ($Type === 6 && isset($this->TableIPv6Netmask[$Size])) {
                $Size = $this->TableIPv6Netmask[$Size];
            } else {
                $Size = (int)$Size;
            }
            if ($Size === 0) {
                $Size = $Ranges;
            }
            if (!isset($CIDRs[$Size - 1]) || $CIDRs[$Size - 1] !== $CIDR . '/' . $Size) {
                $Out = str_replace("\n" . $Line . "\n", "\n", $Out);
                continue;
            }
            $Out = str_replace("\n" . $Line . "\n", "\n" . $CIDRs[$Size - 1] . "\n", $Out);
            for ($Range = $Size - 2; $Range >= 0; $Range--) {
                if (isset($CIDRs[$Range]) && strpos($Out, "\n" . $CIDRs[$Range] . "\n") !== false) {
                    $Out = str_replace("\n" . $CIDRs[$Size - 1] . "\n", "\n", $Out);
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
            if (isset($this->callbacks['newParse']) && is_callable($this->callbacks['newParse'])) {
                $this->callbacks['newParse'](substr_count($Step, "\n"));
            }
            $In = $Out = "\n" . $In . "\n";
            $Size = $Offset = 0;
            $CIDR = $Line = '';
            $CIDRs = false;
            while (($NewLine = strpos($In, "\n", $Offset)) !== false) {
                if (isset($this->callbacks['newTick']) && is_callable($this->callbacks['newTick'])) {
                    $this->callbacks['newTick']();
                }
                $PrevLine = $Line;
                $PrevSize = $Size;
                $PrevCIDRs = $CIDRs;
                $Line = substr($In, $Offset, $NewLine - $Offset);
                $Offset = $NewLine + 1;
                $RangeSep = strpos($Line, '/');
                $Size = (int)substr($Line, $RangeSep + 1);
                $CIDR = substr($Line, 0, $RangeSep);
                if (!$CIDRs = $this->CIDRAM['ExpandIPv4']($CIDR, false, $Size - 1)) {
                    $CIDRs = $this->CIDRAM['ExpandIPv6']($CIDR, false, $Size - 1);
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

    /** Optionally converts output to netmask notation. */
    private function convertToNetmasks(&$In)
    {
        if (isset($this->callbacks['newParse']) && is_callable($this->callbacks['newParse'])) {
            $this->callbacks['newParse'](substr_count($In, "\n"));
        }
        $In = $Out = "\n" . $In . "\n";
        $Offset = 0;
        while (($NewLine = strpos($In, "\n", $Offset)) !== false) {
            if (isset($this->callbacks['newTick']) && is_callable($this->callbacks['newTick'])) {
                $this->callbacks['newTick']();
            }
            $Line = substr($In, $Offset, $NewLine - $Offset);
            $Offset = $NewLine + 1;
            if (!$Line || ($RangeSep = strpos($Line, '/')) === false) {
                continue;
            }
            $Size = substr($Line, $RangeSep + 1);
            $CIDR = substr($Line, 0, $RangeSep);
            $Type = ($this->CIDRAM['ExpandIPv4']($CIDR, true)) ? 4 : 6;
            if ($Type === 4 && isset($this->TableNetmaskIPv4[$Size])) {
                $Size = $this->TableNetmaskIPv4[$Size];
            } elseif ($Type === 6 && isset($this->TableNetmaskIPv6[$Size])) {
                $Size = $this->TableNetmaskIPv6[$Size];
            }
            $Out = str_replace("\n" . $Line . "\n", "\n" . $CIDR . '/' . $Size . "\n", $Out);
        }
        $In = trim($Out);
    }
}
