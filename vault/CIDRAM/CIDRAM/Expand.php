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
 * This file: Methods used to expand CIDRs (last modified: 2023.10.21).
 */

namespace CIDRAM\CIDRAM;

trait Expand
{
    /**
     * Tests whether $Addr is an IPv4 address, and if it is, expands its potential
     * factors (i.e., constructs an array containing the CIDRs that contain $Addr).
     * Returns false if $Addr is *not* an IPv4 address, and otherwise, returns the
     * contructed array.
     *
     * @param string $Addr Refer to the description above.
     * @param bool $ValidateOnly If true, just checks if the IP is valid only.
     * @param int $FactorLimit Maximum number of CIDRs to return (default: 32).
     * @return bool|array Refer to the description above.
     */
    public function expandIpv4(string $Addr, bool $ValidateOnly = false, int $FactorLimit = 32)
    {
        if (!preg_match(
            '/^([01]?\d{1,2}|2[0-4]\d|25[0-5])\.([01]?\d{1,2}|2[0-4]\d|25[0-5])\.([01]?\d{1,2}|2[0-4]\d|25[0-5])\.([01]?\d{1,2}|2[0-4]\d|25[0-5])$/',
            $Addr,
            $Octets
        )) {
            return false;
        }
        if ($ValidateOnly) {
            return true;
        }
        $CIDRs = [];
        $Base = [0, 0, 0, 0];
        for ($Cycle = 0; $Cycle < 4; $Cycle++) {
            for ($Size = 128, $Step = 0; $Step < 8; $Step++, $Size /= 2) {
                $CIDR = $Step + ($Cycle * 8);
                $Base[$Cycle] = floor($Octets[$Cycle + 1] / $Size) * $Size;
                $CIDRs[$CIDR] = $Base[0] . '.' . $Base[1] . '.' . $Base[2] . '.' . $Base[3] . '/' . ($CIDR + 1);
                if ($CIDR >= $FactorLimit) {
                    break 2;
                }
            }
        }
        return $CIDRs;
    }

    /**
     * Tests whether $Addr is an IPv6 address, and if it is, expands its potential
     * factors (i.e., constructs an array containing the CIDRs that contain $Addr).
     * Returns false if $Addr is *not* an IPv6 address, and otherwise, returns the
     * contructed array.
     *
     * @param string $Addr Refer to the description above.
     * @param bool $ValidateOnly If true, just checks if the IP is valid only.
     * @param int $FactorLimit Maximum number of CIDRs to return (default: 128).
     * @return bool|array Refer to the description above.
     */
    public function expandIpv6(string $Addr, bool $ValidateOnly = false, int $FactorLimit = 128)
    {
        /**
         * The pattern used by this `preg_match` call was adapted from the IPv6
         * pattern that can be found at
         * @link https://sroze.io/regex-ip-v4-et-ipv6-6cc005cabe8c
         */
        if (!preg_match(
            '/^((([\da-f]{1,4}:){7}[\da-f]{1,4})|(([\da-f]{1,4}:){6}:[\da-f]{1,4})' .
            '|(([\da-f]{1,4}:){5}:([\da-f]{1,4}:)?[\da-f]{1,4})|(([\da-f]{1,4}:){4' .
            '}:([\da-f]{1,4}:){0,2}[\da-f]{1,4})|(([\da-f]{1,4}:){3}:([\da-f]{1,4}' .
            ':){0,3}[\da-f]{1,4})|(([\da-f]{1,4}:){2}:([\da-f]{1,4}:){0,4}[\da-f]{' .
            '1,4})|(([\da-f]{1,4}:){6}((\b((25[0-5])|(1\d{2})|(2[0-4]\d)|(\d{1,2})' .
            ')\b).){3}(\b((25[0-5])|(1\d{2})|(2[0-4]\d)|(\d{1,2}))\b))|(([\da-f]{1' .
            ',4}:){0,5}:((\b((25[0-5])|(1\d{2})|(2[0-4]\d)|(\d{1,2}))\b).){3}(\b((' .
            '25[0-5])|(1\d{2})|(2[0-4]\d)|(\d{1,2}))\b))|(::([\da-f]{1,4}:){0,5}((' .
            '\b((25[0-5])|(1\d{2})|(2[0-4]\d)|(\d{1,2}))\b).){3}(\b((25[0-5])|(1\d' .
            '{2})|(2[0-4]\d)|(\d{1,2}))\b))|([\da-f]{1,4}::([\da-f]{1,4}:){0,5}[\d' .
            'a-f]{1,4})|(::([\da-f]{1,4}:){0,6}[\da-f]{1,4})|(([\da-f]{1,4}:){1,7}' .
            ':))$/i',
            $Addr
        )) {
            return false;
        }

        if ($ValidateOnly) {
            return true;
        }
        $NAddr = $Addr;
        if (substr($NAddr, 0, 2) === '::') {
            $NAddr = '0' . $NAddr;
        }
        if (substr($NAddr, -2) === '::') {
            $NAddr .= '0';
        }
        if (strpos($NAddr, '::') !== false) {
            $Key = 7 - substr_count($Addr, ':');
            $Arr = [':0:', ':0:0:', ':0:0:0:', ':0:0:0:0:', ':0:0:0:0:0:', ':0:0:0:0:0:0:'];
            if (!isset($Arr[$Key])) {
                return false;
            }
            $NAddr = str_replace('::', $Arr[$Key], $Addr);
            unset($Arr, $Key);
        }
        $NAddr = explode(':', $NAddr);
        if (count($NAddr) !== 8) {
            return false;
        }
        for ($i = 0; $i < 8; $i++) {
            $NAddr[$i] = hexdec($NAddr[$i]);
        }
        $CIDRs = [];
        $Base = [0, 0, 0, 0, 0, 0, 0, 0];
        for ($Cycle = 0; $Cycle < 8; $Cycle++) {
            for ($Size = 32768, $Step = 0; $Step < 16; $Step++, $Size /= 2) {
                $CIDR = $Step + ($Cycle * 16);
                $Base[$Cycle] = dechex(floor($NAddr[$Cycle] / $Size) * $Size);
                $CIDRs[$CIDR] = $Base[0] . ':' . $Base[1] . ':' . $Base[2] . ':' . $Base[3] . ':' . $Base[4] . ':' . $Base[5] . ':' . $Base[6] . ':' . $Base[7] . '/' . ($CIDR + 1);
                if ($CIDR >= $FactorLimit) {
                    break 2;
                }
            }
        }
        foreach ($CIDRs as &$CIDR) {
            if (strpos($CIDR, '::') !== false) {
                $CIDR = preg_replace('~(?::0)*::(?:0:)*~i', '::', $CIDR, 1);
                $CIDR = str_replace('::0/', '::/', $CIDR);
                continue;
            }
            if (strpos($CIDR, ':0:0/') !== false) {
                $CIDR = preg_replace('~(:0){2,}\/~i', '::/', $CIDR, 1);
                continue;
            }
            if (strpos($CIDR, ':0:0:') !== false) {
                $CIDR = preg_replace('~(:0)+:(0:)+~i', '::', $CIDR, 1);
                $CIDR = str_replace('::0/', '::/', $CIDR);
                continue;
            }
        }
        return $CIDRs;
    }
}
