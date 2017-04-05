<?php
/**
 * This file is a part of the CIDRAM package, and can be downloaded for free
 * from {@link https://github.com/Maikuolan/CIDRAM/ GitHub}.
 *
 * CIDRAM COPYRIGHT 2016 and beyond by Caleb Mazalevskis (Maikuolan).
 *
 * License: GNU/GPLv2
 * @see LICENSE.txt
 *
 * This file: The simple installer (last modified: 2017.04.05) by Naveen {@link https://github.com/naveen17797} */
require 'vault/fe_assets/_installer.html';

if ($_GET['mode'] == "install") {
/*
* Make rename function
*/
rename("vault/config.ini.RenameMe", "vault/config.ini");


/*
* chmod vault to 755
*/
chmod("vault", 755);


/*
* self destruct index.php file
*/
unlink("index.php");


}



?>

