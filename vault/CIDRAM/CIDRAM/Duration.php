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
 * This file: Duration value type (last modified: 2023.10.21).
 */

namespace CIDRAM\CIDRAM;

class Duration
{
    /**
     * @var int How many days.
     */
    public $Days = 0;

    /**
     * @var int How many hours.
     */
    public $Hours = 0;

    /**
     * @var int How many minutes.
     */
    public $Minutes = 0;

    /**
     * @var int How many seconds.
     */
    public $Seconds = 0;

    /**
     * @var int How many milliseconds.
     */
    public $Milli = 0;

    /**
     * @var int|float How many microseconds.
     */
    public $Micro = 0;

    /**
     * @var string Which convention to use. Supported:
     *  - "SI": "International System of Units".
     *  - Any other value: arcs/angular notation.
     * @link https://usma.org/commonly-used-metric-system-units-symbols-and-prefixes
     */
    public $Convention = '';

    /**
     * Instantiate.
     *
     * @param string $Raw The raw duration value.
     * @param string $Convention The convention to use.
     * @return void
     */
    public function __construct(string $Raw = '', string $Convention = '')
    {
        if (!preg_match(
            '~^(?:(?<Weeks>\d+(?:\.\d+)?)[Ww](?:[Ee]{2}[Kk][Ss]?)?)?' .
            '(?:(?<Days>\d+(?:\.\d+)?)[Dd](?:[Aa][Yy][Ss]?)?)?' .
            '(?:(?<Hours>\d+(?:\.\d+)?)(?:\xB0|°|[Hh](?:[Oo][Uu][Rr][Ss]?)?))?' .
            '(?:(?<Minutes>\d+(?:\.\d+)?)(?:\'|′|[Mm](?:[Ii][Nn][Uu]?[Tt]?[Ee]?[Ss]?)?))?' .
            '(?:(?<Seconds>\d+(?:\.\d+)?)(?:"|″|[Ss](?:[Ee][Cc][Oo]?[Nn]?[Dd]?[Ss]?)?))?' .
            '(?:(?<Milli>\d+(?:\.\d+)?)ms)?' .
            '(?:(?<Micro>\d+(?:\.\d+)?)µs)?' .
            '(?:(?<Nano>\d+(?:\.\d+)?)ns)?' .
            '$|^(?<Unspecified>\d+(?:\.\d+)?)?$~',
            preg_replace('~[\s,]~', '', $Raw),
            $Parts
        )) {
            return;
        }
        $this->Convention = $Convention;
        foreach (['Weeks', 'Days', 'Hours', 'Minutes', 'Seconds', 'Milli', 'Micro', 'Nano', 'Unspecified'] as $Unit) {
            $$Unit = $Parts[$Unit] ?? 0;
            if ($$Unit === '') {
                $$Unit = 0;
            } elseif (strpos($$Unit, '.') !== false) {
                $$Unit = (float)$$Unit;
            } else {
                $$Unit = (int)$$Unit;
            }
        }
        $DaysBefore = $Days += $Weeks * 7;
        $HoursBefore = $Hours;
        $MinutesBefore = $Minutes;
        $SecondsBefore = $Seconds += $Unspecified;
        $MilliBefore = $Milli;
        $MicroBefore = $Micro += ($Nano > 0 ? $Nano / 1000 : 0);
        while (true) {
            if ($Micro > 1000) {
                $Add = floor($Micro / 1000);
                $Micro -= $Add * 1000;
                $Milli += $Add;
            }
            if ($Milli > 1000) {
                $Add = floor($Milli / 1000);
                $Milli -= $Add * 1000;
                $Seconds += $Add;
            }
            if ($Seconds > 60) {
                $Add = floor($Seconds / 60);
                $Seconds -= $Add * 60;
                $Minutes += $Add;
            }
            if ($Minutes > 60) {
                $Add = floor($Minutes / 60);
                $Minutes -= $Add * 60;
                $Hours += $Add;
            }
            if ($Hours > 24) {
                $Add = floor($Hours / 24);
                $Hours -= $Add * 24;
                $Days += $Add;
            }
            $Whole = floor($Days);
            $Add = $Days - $Whole;
            if ($Add > 0) {
                $Hours += $Add * 24;
                $Days = $Whole;
            }
            foreach ([['Hours', 'Minutes'], ['Minutes', 'Seconds']] as $Pair) {
                $Whole = floor(${$Pair[0]});
                $Add = ${$Pair[0]} - $Whole;
                if ($Add > 0) {
                    ${$Pair[1]} += $Add * 60;
                    ${$Pair[0]} = $Whole;
                }
            }
            foreach ([['Seconds', 'Milli'], ['Milli', 'Micro']] as $Pair) {
                $Whole = floor(${$Pair[0]});
                $Add = ${$Pair[0]} - $Whole;
                if ($Add > 0) {
                    ${$Pair[1]} += $Add * 1000;
                    ${$Pair[0]} = $Whole;
                }
            }
            if (
                $DaysBefore === $Days &&
                $HoursBefore === $Hours &&
                $MinutesBefore === $Minutes &&
                $SecondsBefore === $Seconds &&
                $MilliBefore === $Milli &&
                $MicroBefore === $Micro
            ) {
                break;
            }
            $DaysBefore = $Days;
            $HoursBefore = $Hours;
            $MinutesBefore = $Minutes;
            $SecondsBefore = $Seconds;
            $MilliBefore = $Milli;
            $MicroBefore = $Micro;
        }
        $this->Days = (int)$Days;
        $this->Hours = (int)$Hours;
        $this->Minutes = (int)$Minutes;
        $this->Seconds = (int)$Seconds;
        $this->Milli = (int)$Milli;
        $this->Micro = $Micro;
    }

    /**
     * PHP's magic "__toString" method to act as an alias for "format".
     *
     * @return string
     */
    public function __toString(): string
    {
        return $this->format();
    }

    /**
     * Reconstruct the duration.
     *
     * @return string The reconstructed duration.
     */
    public function format(): string
    {
        $Output = '';
        if ($this->Convention === 'SI') {
            if ($this->Days > 0) {
                $Output .= $this->Days . 'd';
            }
            if ($this->Hours > 0) {
                $Output .= $this->Hours . 'h';
            }
            if ($this->Minutes > 0) {
                $Output .= $this->Minutes . 'min';
            }
            if ($this->Seconds > 0) {
                $Output .= $this->Seconds . 's';
            }
            if ($this->Milli > 0) {
                $Output .= $this->Milli . 'ms';
            }
            if ($this->Micro > 0) {
                $Whole = floor($this->Micro);
                $Nano = floor(($this->Micro - $Whole) * 1000);
                if ($Whole > 0) {
                    $Output .= $Whole . 'μs';
                }
                if ($Nano > 0) {
                    $Output .= $Nano . 'ns';
                }
            }
            return $Output === '' ? '0' : $Output;
        }
        if ($this->Days > 0) {
            $Output .= $this->Days . 'd';
        }
        $Output .= $this->Hours . '°';
        $Output .= $this->Minutes . '′';
        $ToOut = $this->Seconds;
        if ($this->Milli > 0) {
            $ToOut += $this->Milli / 1000;
        }
        if ($this->Micro > 0) {
            $ToOut += $this->Micro / 1000000;
        }
        $Output .= $ToOut . '″';
        return $Output;
    }

    /**
     * Get the duration as weeks.
     *
     * @return int|float The duration as weeks.
     */
    public function getAsWeeks()
    {
        $Output = 0;
        if ($this->Days > 0) {
            $Output += $this->Days / 7;
        }
        if ($this->Hours > 0) {
            $Output += $this->Hours / 168;
        }
        if ($this->Minutes > 0) {
            $Output += $this->Minutes / 10080;
        }
        if ($this->Seconds > 0) {
            $Output += $this->Seconds / 604800;
        }
        if ($this->Milli > 0) {
            $Output += $this->Milli / 604800000;
        }
        if ($this->Micro > 0) {
            $Output += $this->Micro / 604800000000;
        }
        return $Output;
    }

    /**
     * Get the duration as days.
     *
     * @return int|float The duration as days.
     */
    public function getAsDays()
    {
        $Output = $this->Days;
        if ($this->Hours > 0) {
            $Output += $this->Hours / 24;
        }
        if ($this->Minutes > 0) {
            $Output += $this->Minutes / 1440;
        }
        if ($this->Seconds > 0) {
            $Output += $this->Seconds / 86400;
        }
        if ($this->Milli > 0) {
            $Output += $this->Milli / 86400000;
        }
        if ($this->Micro > 0) {
            $Output += $this->Micro / 86400000000;
        }
        return $Output;
    }

    /**
     * Get the duration as hours.
     *
     * @return int|float The duration as hours.
     */
    public function getAsHours()
    {
        $Output = $this->Hours;
        if ($this->Days > 0) {
            $Output += $this->Days * 24;
        }
        if ($this->Minutes > 0) {
            $Output += $this->Minutes / 60;
        }
        if ($this->Seconds > 0) {
            $Output += $this->Seconds / 3600;
        }
        if ($this->Milli > 0) {
            $Output += $this->Milli / 3600000;
        }
        if ($this->Micro > 0) {
            $Output += $this->Micro / 3600000000;
        }
        return $Output;
    }

    /**
     * Get the duration as minutes.
     *
     * @return int|float The duration as minutes.
     */
    public function getAsMinutes()
    {
        $Output = $this->Minutes;
        if ($this->Days > 0) {
            $Output += $this->Days * 1440;
        }
        if ($this->Hours > 0) {
            $Output += $this->Hours * 60;
        }
        if ($this->Seconds > 0) {
            $Output += $this->Seconds / 60;
        }
        if ($this->Milli > 0) {
            $Output += $this->Milli / 60000;
        }
        if ($this->Micro > 0) {
            $Output += $this->Micro / 60000000;
        }
        return $Output;
    }

    /**
     * Get the duration as seconds.
     *
     * @return int|float The duration as seconds.
     */
    public function getAsSeconds()
    {
        $Output = $this->Seconds;
        if ($this->Days > 0) {
            $Output += $this->Days * 86400;
        }
        if ($this->Hours > 0) {
            $Output += $this->Hours * 3600;
        }
        if ($this->Minutes > 0) {
            $Output += $this->Minutes * 60;
        }
        if ($this->Milli > 0) {
            $Output += $this->Milli / 1000;
        }
        if ($this->Micro > 0) {
            $Output += $this->Micro / 1000000;
        }
        return $Output;
    }

    /**
     * Get the duration as milliseconds.
     *
     * @return int|float The duration as milliseconds.
     */
    public function getAsMilliseconds()
    {
        $Output = $this->Milliseconds;
        if ($this->Days > 0) {
            $Output += $this->Days * 86400000;
        }
        if ($this->Hours > 0) {
            $Output += $this->Hours * 3600000;
        }
        if ($this->Minutes > 0) {
            $Output += $this->Minutes * 60000;
        }
        if ($this->Seconds > 0) {
            $Output += $this->Seconds * 1000;
        }
        if ($this->Micro > 0) {
            $Output += $this->Micro / 1000;
        }
        return $Output;
    }

    /**
     * Get the duration as microseconds.
     *
     * @return int|float The duration as microseconds.
     */
    public function getAsMicroseconds()
    {
        $Output = $this->Microseconds;
        if ($this->Days > 0) {
            $Output += $this->Days * 86400000000;
        }
        if ($this->Hours > 0) {
            $Output += $this->Hours * 3600000000;
        }
        if ($this->Minutes > 0) {
            $Output += $this->Minutes * 60000000;
        }
        if ($this->Seconds > 0) {
            $Output += $this->Seconds * 1000000;
        }
        if ($this->Milli > 0) {
            $Output += $this->Milli * 1000;
        }
        return $Output;
    }
}
