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
 * This file: Protocol blocker module (last modified: 2024.06.11).
 *
 * False positive risk (an approximate, rough estimate only): « [x]Low [ ]Medium [ ]High »
 */

/** Safety. */
if (!isset($this->CIDRAM['ModuleResCache'])) {
    $this->CIDRAM['ModuleResCache'] = [];
}

/** Protocol blocker module blocked protocols. */
$this->CIDRAM['ProtocolBlocker'] = ['blocked' => array_flip(explode("\n", $this->Configuration['protocol']['blocked']))];

/** Defining as closure for later recall (no params; no return value). */
$this->CIDRAM['ModuleResCache'][$Module] = function () {
    /** Guard. */
    if (!isset($_SERVER['SERVER_PROTOCOL'])) {
        return;
    }

    if (($Split = strpos($_SERVER['SERVER_PROTOCOL'], '/')) !== false) {
        $Protocol = strtoupper(preg_replace('~[^A-Za-z]~', '', substr($_SERVER['SERVER_PROTOCOL'], 0, $Split)));
        $Version = explode('.', preg_replace('~[^\d.]~', '', substr($_SERVER['SERVER_PROTOCOL'], $Split + 1)), 2);
    } else {
        $Protocol = strtoupper(preg_replace('~[^A-Za-z]~', '', $_SERVER['SERVER_PROTOCOL']));
        $Version = explode('.', preg_replace('~[^\d.]~', '', $_SERVER['SERVER_PROTOCOL']), 2);
    }
    $Major = (int)$Version[0];
    $Minor = isset($Version[1]) ? (int)$Version[1] : 0;
    $Rebuilt = $Protocol . '/' . $Major . '.' . $Minor;
    $Hit = false;

    /** Account for different protocols used through proxying. */
    if ($Protocol === 'HTTP' && $Major === 1 && isset($_SERVER['X_SPDY']) && substr($_SERVER['X_SPDY'], 0, 4) === 'HTTP') {
        $Version = explode('.', preg_replace('~[^\d.]~', '', substr($_SERVER['X_SPDY'], 4)), 2);
        $Major = (int)$Version[0];
        $Minor = isset($Version[1]) ? (int)$Version[1] : 0;
    }

    $Short = $this->L10N->getString('protocol_denied_short') ?: 'Protocol denied';
    $Long = $this->L10N->getString($this->Configuration['protocol']['reason_message']) ?: $this->Configuration['protocol']['reason_message'] ?: $this->L10N->getString('denied');

    if ($Protocol === 'HTTP') {
        /** See: https://developer.mozilla.org/en-US/docs/Web/HTTP/Basics_of_HTTP/Evolution_of_HTTP */
        if ($this->trigger((
            (isset($this->CIDRAM['ProtocolBlocker']['blocked']['HTTP/0.9']) && $Major === 0 && $Minor === 9) ||
            (isset($this->CIDRAM['ProtocolBlocker']['blocked']['HTTP/0.x']) && $Major === 0 && $Minor !== 9) ||
            (isset($this->CIDRAM['ProtocolBlocker']['blocked']['HTTP/1.0']) && $Major === 1 && $Minor === 0) ||
            (isset($this->CIDRAM['ProtocolBlocker']['blocked']['HTTP/1.1']) && $Major === 1 && $Minor === 1) ||
            (isset($this->CIDRAM['ProtocolBlocker']['blocked']['HTTP/1.2']) && $Major === 1 && $Minor === 2) ||
            (isset($this->CIDRAM['ProtocolBlocker']['blocked']['HTTP/1.3']) && $Major === 1 && $Minor === 3) ||
            (isset($this->CIDRAM['ProtocolBlocker']['blocked']['HTTP/1.x']) && $Major === 1 && $Minor !== 0 && $Minor !== 1 && $Minor !== 2 && $Minor !== 3) ||
            (isset($this->CIDRAM['ProtocolBlocker']['blocked']['HTTP/2.0']) && $Major === 2 && $Minor === 0) ||
            (isset($this->CIDRAM['ProtocolBlocker']['blocked']['HTTP/2.x']) && $Major === 2 && $Minor !== 0) ||
            (isset($this->CIDRAM['ProtocolBlocker']['blocked']['HTTP/3.0']) && $Major === 3 && $Minor === 0) ||
            (isset($this->CIDRAM['ProtocolBlocker']['blocked']['HTTP/3.x']) && $Major === 3 && $Minor !== 0) ||
            (isset($this->CIDRAM['ProtocolBlocker']['blocked']['HTTP/x.x']) && $Major !== 0 && $Major !== 1 && $Major !== 2 && $Major !== 3)
        ), $Short . ' (' . $Rebuilt . ')', $Long)) {
            $Hit = true;
        }
    } elseif ($Protocol === 'SHTTP') {
        if ($this->trigger((
            (isset($this->CIDRAM['ProtocolBlocker']['blocked']['SHTTP/1.3']) && $Major === 1 && $Minor === 3) ||
            (isset($this->CIDRAM['ProtocolBlocker']['blocked']['SHTTP/x.x']) && !($Major === 1 && $Minor === 3))
        ), $Short . ' (' . $Rebuilt . ')', $Long)) {
            $Hit = true;
        }
    } elseif ($Protocol === 'SPDY') {
        if ($this->trigger((
            (isset($this->CIDRAM['ProtocolBlocker']['blocked']['SPDY/3.0']) && $Major === 3 && $Minor === 0) ||
            (isset($this->CIDRAM['ProtocolBlocker']['blocked']['SPDY/3.1']) && $Major === 3 && $Minor === 1) ||
            (isset($this->CIDRAM['ProtocolBlocker']['blocked']['SPDY/x.x']) && ($Major !== 3 || ($Minor !== 0 && $Minor !== 1)))
        ), $Short . ' (' . $Rebuilt . ')', $Long)) {
            $Hit = true;
        }
    } elseif ($Protocol === 'IRC') {
        /** See: https://tools.ietf.org/html/rfc7230 */
        if ($this->trigger((
            (isset($this->CIDRAM['ProtocolBlocker']['blocked']['IRC/6.9']) && $Major === 6 && $Minor === 9) ||
            (isset($this->CIDRAM['ProtocolBlocker']['blocked']['IRC/x.x']) && !($Major === 6 && $Minor === 9))
        ), $Short . ' (' . $Rebuilt . ')', $Long)) {
            $Hit = true;
        }
    } elseif ($Protocol === 'RTA') {
        if ($this->trigger((
            isset($this->CIDRAM['ProtocolBlocker']['blocked']['RTA/x11']) && $_SERVER['SERVER_PROTOCOL'] === 'RTA/x11'
        ), $Short . ' (' . $Rebuilt . ')', $Long)) {
            $Hit = true;
        }
    } else {
        if ($this->trigger(isset($this->CIDRAM['ProtocolBlocker']['blocked']['Other']), $Short . ' (' . $Rebuilt . ')', $Long)) {
            $Hit = true;
        }
    }

    /** Disable CAPTCHAs. */
    if ($Hit) {
        $this->enactOptions('', ['ForciblyDisableReCAPTCHA' => true, 'ForciblyDisableHCAPTCHA' => true]);
    }
};

/** Execute closure. */
$this->CIDRAM['ModuleResCache'][$Module]();
