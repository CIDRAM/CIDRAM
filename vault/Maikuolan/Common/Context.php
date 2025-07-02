<?php
/**
 * Context attribute (last modified: 2025.07.02).
 *
 * This file is a part of the "common classes package", utilised by a number of
 * packages and projects, including CIDRAM and phpMussel.
 * @link https://github.com/Maikuolan/Common
 *
 * License: GNU/GPLv2
 * @see LICENSE.txt
 *
 * "COMMON CLASSES PACKAGE" COPYRIGHT 2019 and beyond by Caleb Mazalevskis.
 * *This particular class*, COPYRIGHT 2025 and beyond by Caleb Mazalevskis.
 */

namespace Maikuolan\Common;

#[\Attribute, \AllowDynamicProperties]
class Context
{
    public function __construct(...$Arguments)
    {
        foreach ($Arguments as $Key => $Value) {
            $this->{$Key} = $Value;
        }
    }
}
