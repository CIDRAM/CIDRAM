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
 * This file: Constants (last modified: 2021.03.12).
 */

namespace CIDRAM\Core;

class Constants
{
    /**
     * @var int Default file blocksize (128KB).
     */
    public const FILE_BLOCKSIZE = 131072;

    public const GENERATE_SALT_MIN_LEN = 32;
    public const GENERATE_SALT_MAX_LEN = 72;
    public const GENERATE_SALT_MIN_CHR = 1;
    public const GENERATE_SALT_MAX_CHR = 255;

    /**
     * @var int Used for some logs page and range tables calculations.
     */
    public const MAX_BLOCKSIZE = 65536;

    /**
     * @var string Default file blocksize (128KB).
     */
    public const PAD_FOR_DNS = '0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz-._~';

    public const TWO_FACTOR_MIN_INT = 10000000;
    public const TWO_FACTOR_MAX_INT = 99999999;
}
