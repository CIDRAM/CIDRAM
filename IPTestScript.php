<?php
/**
 * Description to go here.
 *
 * This file: File description here.
 *
 * @package Maikuolan/IPTestScript
 *
 * @todo EVERYTHING! This is still an ALPHA.
 */

/**
 * Determines the location of the "vault" directory of IPTestScript and saves this information to the $vault variable,
 * required by IPTestScript in order to call, read, write, delete, etc, its files (signatures, includes, logs, etc).
 * (There's this, and a few other parts of this script, borrowed/adapted from phpMussel).
 */
$vault=@(__DIR__==='__DIR__')?dirname(__FILE__).'/vault/':__DIR__.'/vault/';

/**
 * Set all temporary vars to this, so that it's easier to unset it all later.
 */
$IPTestScript=array();
$IPTestScript['Version']='IPTestScript v0.0.1';

/**
 * Kills the script if $vault isn't defined or if it isn't a valid directory.
 */
if(!is_dir($vault))die('[IPTestScript] Vault directory not correctly set: Can\'t continue. Refer to documentation if this is a first-time run, and if problems persist, seek assistance.');

if(!defined('IPTestScript'))
    {
    define('IPTestScript',true);
    $display_errors=error_reporting(1);
    $IPTestScript['Config']=@(!file_exists($vault.'config.ini'))?false:parse_ini_file($vault.'config.ini',true);
    if(!is_array($IPTestScript['Config']))die('[IPTestScript] Could not read config.ini: Can\'t continue. Refer to documentation if this is a first-time run, and if problems persist, seek assistance.');
    if(!isset($IPTestScript['Config']['general']))$IPTestScript['Config']['general']=array();
    if(!isset($IPTestScript['Config']['general']['ipaddr']))$IPTestScript['Config']['general']['ipaddr']='REMOTE_ADDR';
    if(!isset($_SERVER))$_SERVER=array();
    if(!isset($_SERVER[$IPTestScript['Config']['general']['ipaddr']]))$_SERVER[$IPTestScript['Config']['general']['ipaddr']]='';
    if(!file_exists($vault.'lang.inc'))die('[IPTestScript] Language data file missing! Please reinstall IPTestScript.');
    require $vault.'lang.inc';
    if(!isset($IPTestScript['Config']['signatures']))$IPTestScript['Config']['signatures']=array();
    if(!isset($IPTestScript['Config']['signatures']['block_cloud']))$IPTestScript['Config']['signatures']['block_cloud']=true;
    if(!isset($IPTestScript['Config']['signatures']['block_bogons']))$IPTestScript['Config']['signatures']['block_bogons']=true;
    if(!isset($IPTestScript['Config']['signatures']['block_spam']))$IPTestScript['Config']['signatures']['block_spam']=true;
    }

if(!function_exists('ParseVars'))
    {
    /**
     * This is a specialised search-and-replace function, designed to replace encapsulated substrings within a given
     * input string based upon the elements of a given input array. The function accepts two input parameters: The
     * first, the input array, and the second, the input string. The function searches for any instances of each array
     * key, encapsulated by curly brackets, as substrings within the input string, and replaces any instances found
     * with the array element content corresponding to the array key associated with each instance found.
     *
     * This function is used extensively throughout IPTestScript, to parse its language data and to parse any messages
     * related to any detections found during the scan process and any other related processes.
     *
     * @param array $v The input array.
     * @param string $b The input string.
     * @return string The results of the function are returned directly to the calling scope as a string.
     */
    function ParseVars($v,$b)
        {
        if(!is_array($v)||empty($b))return '';
        $c=count($v);
        reset($v);
        for($i=0;$i<$c;$i++)
            {
            $k=key($v);
            $b=str_replace('{'.$k.'}',$v[$k],$b);
            next($v);
            }
        return $b;
        }
    }

/**
 * Tests if $input is an IPv4 address, and if so, reconstructs the appropriate CIDR ranges from which the specified IP
 * address should belong to, and then checks those reconstructed CIDRs against the CIDR signatures file, and if any
 * matches are found, increments $IPTestScript['BlockInfo']['SignatureCount'] and appends to
 * $IPTestScript['BlockInfo']['ReasonMessage']. If $input is NOT an IPv4 address, or if the test fails, false will be
 * returned. If the test succeeds (regardless of whether there are any matches), true will be returned. CIDR ranges are
 * reconstructed as a numeric array containing 32 elements, representing the 32 possible block sizes of IPv4.
 *
 * If an optional secondary parameter is set to true, instead of checking reconstructed CIDRs against the CIDR
 * signatures file, the function will return an array of the reconstructed CIDRs (this can be used for debugging and
 * development purposes, but isn't intended for use by end-users).
 *
 * @param string $Addr The input to test.
 * @param bool $Dump An optional secondary parameter that can be used for debugging.
 * @return bool|array A boolean indicating whether the test succeeded (or an array of the CIDRs, when $Dump is true).
 */
function IPv4Test($Addr,$Dump=false)
    {
    if(!preg_match('/^([01]?[0-9]{1,2}|2[0-4][0-9]|25[0-5])\.([01]?[0-9]{1,2}|2[0-4][0-9]|25[0-5])\.([01]?[0-9]{1,2}|2[0-4][0-9]|25[0-5])\.([01]?[0-9]{1,2}|2[0-4][0-9]|25[0-5])$/i',$Addr,$octets))return false;
    if(!isset($GLOBALS['IPTestScript']['BlockInfo']['ReasonMessage'])||!isset($GLOBALS['IPTestScript']['BlockInfo']['SignatureCount']))return false;
    $cidr=array();
    $cidr[0]=($octets[1]<128)?'0.0.0.0/1':'128.0.0.0/1';
    $cidr[1]=(floor($octets[1]/64)*64).'.0.0.0/2';
    $cidr[2]=(floor($octets[1]/32)*32).'.0.0.0/3';
    $cidr[3]=(floor($octets[1]/16)*16).'.0.0.0/4';
    $cidr[4]=(floor($octets[1]/8)*8).'.0.0.0/5';
    $cidr[5]=(floor($octets[1]/4)*4).'.0.0.0/6';
    $cidr[6]=(floor($octets[1]/2)*2).'.0.0.0/7';
    $cidr[7]=$octets[1].'.0.0.0/8';
    $cidr[8]=$octets[1].'.'.(($octets[2]<128)?'0':'128').'.0.0/9';
    $cidr[9]=$octets[1].'.'.(floor($octets[2]/64)*64).'.0.0/10';
    $cidr[10]=$octets[1].'.'.(floor($octets[2]/32)*32).'.0.0/11';
    $cidr[11]=$octets[1].'.'.(floor($octets[2]/16)*16).'.0.0/12';
    $cidr[12]=$octets[1].'.'.(floor($octets[2]/8)*8).'.0.0/13';
    $cidr[13]=$octets[1].'.'.(floor($octets[2]/4)*4).'.0.0/14';
    $cidr[14]=$octets[1].'.'.(floor($octets[2]/2)*2).'.0.0/15';
    $cidr[15]=$octets[1].'.'.$octets[2].'.0.0/16';
    $cidr[16]=$octets[1].'.'.$octets[2].'.'.(($octets[3]<128)?'0':'128').'.0/17';
    $cidr[17]=$octets[1].'.'.$octets[2].'.'.(floor($octets[3]/64)*64).'.0/18';
    $cidr[18]=$octets[1].'.'.$octets[2].'.'.(floor($octets[3]/32)*32).'.0/19';
    $cidr[19]=$octets[1].'.'.$octets[2].'.'.(floor($octets[3]/16)*16).'.0/20';
    $cidr[20]=$octets[1].'.'.$octets[2].'.'.(floor($octets[3]/8)*8).'.0/21';
    $cidr[21]=$octets[1].'.'.$octets[2].'.'.(floor($octets[3]/4)*4).'.0/22';
    $cidr[22]=$octets[1].'.'.$octets[2].'.'.(floor($octets[3]/2)*2).'.0/23';
    $cidr[23]=$octets[1].'.'.$octets[2].'.'.$octets[3].'.0/24';
    $cidr[24]=$octets[1].'.'.$octets[2].'.'.$octets[3].'.'.(($octets[4]<128)?'0':'128').'/25';
    $cidr[25]=$octets[1].'.'.$octets[2].'.'.$octets[3].'.'.(floor($octets[4]/64)*64).'/26';
    $cidr[26]=$octets[1].'.'.$octets[2].'.'.$octets[3].'.'.(floor($octets[4]/32)*32).'/27';
    $cidr[27]=$octets[1].'.'.$octets[2].'.'.$octets[3].'.'.(floor($octets[4]/16)*16).'/28';
    $cidr[28]=$octets[1].'.'.$octets[2].'.'.$octets[3].'.'.(floor($octets[4]/8)*8).'/29';
    $cidr[29]=$octets[1].'.'.$octets[2].'.'.$octets[3].'.'.(floor($octets[4]/4)*4).'/30';
    $cidr[30]=$octets[1].'.'.$octets[2].'.'.$octets[3].'.'.(floor($octets[4]/2)*2).'/31';
    $cidr[31]=$octets[1].'.'.$octets[2].'.'.$octets[3].'.'.$octets[4].'/32';
    if($Dump)return $cidr;
    $IPv4Sigs=IPTestScriptReadFile($GLOBALS['vault'].'ipv4.dat');
    for($i=0;$i<32;$i++)
        {
        $PosA=strpos($IPv4Sigs,"\n".$cidr[$i].' ');
        if($PosA===false)continue;
        $PosA+=strlen($cidr[$i])+2;
        if(!$PosB=strpos($IPv4Sigs,"\n",$PosA))continue;
        $Sig=substr($IPv4Sigs,$PosA,($PosB-$PosA));
        $Cat=substr($Sig,0,strpos($Sig,' '));
        $Sig=substr($Sig,strpos($Sig,' ')+1);
        if($Cat==='Run')
            {
            if(file_exists($GLOBALS['vault'].$Sig))require_once $GLOBALS['vault'].$Sig;
            // AAA else give me some error message
            continue;
            }
        if($Cat==='Deny')
            {
            // AAA Need to sort out categories for blocking.
            if($Sig==='Bogon'&&$GLOBALS['IPTestScript']['Config']['signatures']['block_bogons'])
                {
                $GLOBALS['IPTestScript']['BlockInfo']['ReasonMessage'].=$GLOBALS['IPTestScript']['lang']['ReasonMessage_Bogon'];
                if(!empty($GLOBALS['IPTestScript']['BlockInfo']['Signatures']))$GLOBALS['IPTestScript']['BlockInfo']['Signatures'].=', ';
                $GLOBALS['IPTestScript']['BlockInfo']['Signatures'].=$cidr[$i];
                $GLOBALS['IPTestScript']['BlockInfo']['SignatureCount']++;
                continue;
                }
            if($Sig==='Cloud'&&$GLOBALS['IPTestScript']['Config']['signatures']['block_cloud'])
                {
                $GLOBALS['IPTestScript']['BlockInfo']['ReasonMessage'].=$GLOBALS['IPTestScript']['lang']['ReasonMessage_Cloud'];
                if(!empty($GLOBALS['IPTestScript']['BlockInfo']['Signatures']))$GLOBALS['IPTestScript']['BlockInfo']['Signatures'].=', ';
                $GLOBALS['IPTestScript']['BlockInfo']['Signatures'].=$cidr[$i];
                $GLOBALS['IPTestScript']['BlockInfo']['SignatureCount']++;
                continue;
                }
            if($Sig==='Generic'&&$GLOBALS['IPTestScript']['Config']['signatures']['block_generic'])
                {
                $GLOBALS['IPTestScript']['BlockInfo']['ReasonMessage'].=$GLOBALS['IPTestScript']['lang']['ReasonMessage_Generic'];
                if(!empty($GLOBALS['IPTestScript']['BlockInfo']['Signatures']))$GLOBALS['IPTestScript']['BlockInfo']['Signatures'].=', ';
                $GLOBALS['IPTestScript']['BlockInfo']['Signatures'].=$cidr[$i];
                $GLOBALS['IPTestScript']['BlockInfo']['SignatureCount']++;
                continue;
                }
            if($Sig==='Spam'&&$GLOBALS['IPTestScript']['Config']['signatures']['block_spam'])
                {
                $GLOBALS['IPTestScript']['BlockInfo']['ReasonMessage'].=$GLOBALS['IPTestScript']['lang']['ReasonMessage_Spam'];
                if(!empty($GLOBALS['IPTestScript']['BlockInfo']['Signatures']))$GLOBALS['IPTestScript']['BlockInfo']['Signatures'].=', ';
                $GLOBALS['IPTestScript']['BlockInfo']['Signatures'].=$cidr[$i];
                $GLOBALS['IPTestScript']['BlockInfo']['SignatureCount']++;
                }
            continue;
            }
        if($Cat==='DenyCustom')
            {
            $GLOBALS['IPTestScript']['BlockInfo']['ReasonMessage'].=$Sig;
            if(!empty($GLOBALS['IPTestScript']['BlockInfo']['Signatures']))$GLOBALS['IPTestScript']['BlockInfo']['Signatures'].=', ';
            $GLOBALS['IPTestScript']['BlockInfo']['Signatures'].=$cidr[$i];
            $GLOBALS['IPTestScript']['BlockInfo']['SignatureCount']++;
            }
        }
    return true;
    }

/**
 * Tests if $input is an IPv6 address, and if so, reconstructs the appropriate CIDR ranges from which the specified IP
 * address should belong to, and then checks those reconstructed CIDRs against the CIDR signatures file, and if any
 * matches are found, increments $IPTestScript['BlockInfo']['SignatureCount'] and appends to
 * $IPTestScript['BlockInfo']['ReasonMessage']. If $input is NOT an IPv6 address, or if the test fails, false will be
 * returned. If the test succeeds (regardless of whether there are any matches), true will be returned. CIDR ranges are
 * reconstructed as a numeric array containing 128 elements, representing the 128 possible block sizes of IPv6.
 *
 * If an optional secondary parameter is set to true, instead of checking reconstructed CIDRs against the CIDR
 * signatures file, the function will return an array of the reconstructed CIDRs (this can be used for debugging and
 * development purposes, but isn't intended for use by end-users).
 *
 * @param string $Addr The input to test.
 * @param bool $Dump An optional secondary parameter that can be used for debugging.
 * @return bool|array A boolean indicating whether the test succeeded (or an array of the CIDRs, when $Dump is true).
 *
 * @todo Further testing may be required to ensure that this function behaves as is expected, with particular attention paid to the way in which the CIDRs are reconstructed (take note, especially, as to which octets are shortened and where).
 */
function IPv6Test($Addr,$Dump=false)
    {
    /**
     * The REGEX pattern used by this `preg_match` call was adapted from the IPv6 REGEX pattern that can be found at
     * http://sroze.io/2008/10/09/regex-ipv4-et-ipv6/
     *
     * Although I wrote the IPv4 REGEX for this script myself and have tested it enough to be personally satisfied that
     * it should always return the expected results, I've neither written this IPv6 REGEX myself nor yet done enough
     * thorough testing of it to ensure that it'll always return the expected results. I -believe- it should be
     * satisfactory, but it -may- require modifying or replacing in the future, pending further testing.
     */
    if(!preg_match('/^(([0-9a-f]{1,4}\:){7}[0-9a-f]{1,4})|(([0-9a-f]{1,4}\:){6}\:[0-9a-f]{1,4})|(([0-9a-f]{1,4}\:){5}\:([0-9a-f]{1,4}\:)?[0-9a-f]{1,4})|(([0-9a-f]{1,4}\:){4}\:([0-9a-f]{1,4}\:){0,2}[0-9a-f]{1,4})|(([0-9a-f]{1,4}\:){3}\:([0-9a-f]{1,4}\:){0,3}[0-9a-f]{1,4})|(([0-9a-f]{1,4}\:){2}\:([0-9a-f]{1,4}\:){0,4}[0-9a-f]{1,4})|(([0-9a-f]{1,4}\:){6}((\b((25[0-5])|(1\d{2})|(2[0-4]\d)|(\d{1,2}))\b).){3}(\b((25[0-5])|(1\d{2})|(2[0-4]\d)|(\d{1,2}))\b))|(([0-9a-f]{1,4}\:){0,5}\:((\b((25[0-5])|(1\d{2})|(2[0-4]\d)|(\d{1,2}))\b).){3}(\b((25[0-5])|(1\d{2})|(2[0-4]\d)|(\d{1,2}))\b))|(\:\:([0-9a-f]{1,4}\:){0,5}((\b((25[0-5])|(1\d{2})|(2[0-4]\d)|(\d{1,2}))\b).){3}(\b((25[0-5])|(1\d{2})|(2[0-4]\d)|(\d{1,2}))\b))|([0-9a-f]{1,4}\:\:([0-9a-f]{1,4}\:){0,5}[0-9a-f]{1,4})|(\:\:([0-9a-f]{1,4}\:){0,6}[0-9a-f]{1,4})|(([0-9a-f]{1,4}\:){1,7}\:)$/i',$Addr))return false;
    $NAddr=$Addr;
    if(preg_match('/^\:\:/i',$NAddr))$NAddr='0'.$NAddr;
    if(preg_match('/\:\:$/i',$NAddr))$NAddr.='0';
    if(substr_count($NAddr,'::'))
        {
        $c=7-substr_count($Addr,':');
        $arr=array(':0:',':0:0:',':0:0:0:',':0:0:0:0:',':0:0:0:0:0:',':0:0:0:0:0:0:');
        $NAddr=str_replace('::',$arr[$c],$Addr);
        unset($arr);
        }
    $NAddr=explode(':',$NAddr);
    if(count($NAddr)!==8)return false;
    $NAddr[0]=hexdec($NAddr[0]);
    $NAddr[1]=hexdec($NAddr[1]);
    $NAddr[2]=hexdec($NAddr[2]);
    $NAddr[3]=hexdec($NAddr[3]);
    $NAddr[4]=hexdec($NAddr[4]);
    $NAddr[5]=hexdec($NAddr[5]);
    $NAddr[6]=hexdec($NAddr[6]);
    $NAddr[7]=hexdec($NAddr[7]);
    $cidr=array();
    $cidr[0]=($NAddr[0]<32768)?'0::/1':'8000::/1';
    $cidr[1]=dechex(floor($NAddr[0]/16384)*16384).'::/2';
    $cidr[2]=dechex(floor($NAddr[0]/8192)*8192).'::/3';
    $cidr[3]=dechex(floor($NAddr[0]/4096)*4096).'::/4';
    $cidr[4]=dechex(floor($NAddr[0]/2048)*2048).'::/5';
    $cidr[5]=dechex(floor($NAddr[0]/1024)*1024).'::/6';
    $cidr[6]=dechex(floor($NAddr[0]/512)*512).'::/7';
    $cidr[7]=dechex(floor($NAddr[0]/256)*256).'::/8';
    $cidr[8]=dechex(floor($NAddr[0]/128)*128).'::/9';
    $cidr[9]=dechex(floor($NAddr[0]/64)*64).'::/10';
    $cidr[10]=dechex(floor($NAddr[0]/32)*32).'::/11';
    $cidr[11]=dechex(floor($NAddr[0]/16)*16).'::/12';
    $cidr[12]=dechex(floor($NAddr[0]/8)*8).'::/13';
    $cidr[13]=dechex(floor($NAddr[0]/4)*4).'::/14';
    $cidr[14]=dechex(floor($NAddr[0]/2)*2).'::/15';
    $NAddr[0]=dechex($NAddr[0]);
    $cidr[15]=$NAddr[0].'::/16';
    $cidr[16]=($NAddr[1]<32768)?$NAddr[0].'::/17':$NAddr[0].':8000::/17';
    $cidr[17]=$NAddr[0].':'.dechex(floor($NAddr[1]/16384)*16384).'::/18';
    $cidr[18]=$NAddr[0].':'.dechex(floor($NAddr[1]/8192)*8192).'::/19';
    $cidr[19]=$NAddr[0].':'.dechex(floor($NAddr[1]/4096)*4096).'::/20';
    $cidr[20]=$NAddr[0].':'.dechex(floor($NAddr[1]/2048)*2048).'::/21';
    $cidr[21]=$NAddr[0].':'.dechex(floor($NAddr[1]/1024)*1024).'::/22';
    $cidr[22]=$NAddr[0].':'.dechex(floor($NAddr[1]/512)*512).'::/23';
    $cidr[23]=$NAddr[0].':'.dechex(floor($NAddr[1]/256)*256).'::/24';
    $cidr[24]=$NAddr[0].':'.dechex(floor($NAddr[1]/128)*128).'::/25';
    $cidr[25]=$NAddr[0].':'.dechex(floor($NAddr[1]/64)*64).'::/26';
    $cidr[26]=$NAddr[0].':'.dechex(floor($NAddr[1]/32)*32).'::/27';
    $cidr[27]=$NAddr[0].':'.dechex(floor($NAddr[1]/16)*16).'::/28';
    $cidr[28]=$NAddr[0].':'.dechex(floor($NAddr[1]/8)*8).'::/29';
    $cidr[29]=$NAddr[0].':'.dechex(floor($NAddr[1]/4)*4).'::/30';
    $cidr[30]=$NAddr[0].':'.dechex(floor($NAddr[1]/2)*2).'::/31';
    $NAddr[1]=dechex($NAddr[1]);
    $cidr[31]=$NAddr[0].':'.$NAddr[1].'::/32';
    $cidr[32]=($NAddr[2]<32768)?$NAddr[0].':'.$NAddr[1].'::/33':$NAddr[0].':'.$NAddr[1].':8000::/33';
    $cidr[33]=$NAddr[0].':'.$NAddr[1].':'.dechex(floor($NAddr[2]/16384)*16384).'::/34';
    $cidr[34]=$NAddr[0].':'.$NAddr[1].':'.dechex(floor($NAddr[2]/8192)*8192).'::/35';
    $cidr[35]=$NAddr[0].':'.$NAddr[1].':'.dechex(floor($NAddr[2]/4096)*4096).'::/36';
    $cidr[36]=$NAddr[0].':'.$NAddr[1].':'.dechex(floor($NAddr[2]/2048)*2048).'::/37';
    $cidr[37]=$NAddr[0].':'.$NAddr[1].':'.dechex(floor($NAddr[2]/1024)*1024).'::/38';
    $cidr[38]=$NAddr[0].':'.$NAddr[1].':'.dechex(floor($NAddr[2]/512)*512).'::/39';
    $cidr[39]=$NAddr[0].':'.$NAddr[1].':'.dechex(floor($NAddr[2]/256)*256).'::/40';
    $cidr[40]=$NAddr[0].':'.$NAddr[1].':'.dechex(floor($NAddr[2]/128)*128).'::/41';
    $cidr[41]=$NAddr[0].':'.$NAddr[1].':'.dechex(floor($NAddr[2]/64)*64).'::/42';
    $cidr[42]=$NAddr[0].':'.$NAddr[1].':'.dechex(floor($NAddr[2]/32)*32).'::/43';
    $cidr[43]=$NAddr[0].':'.$NAddr[1].':'.dechex(floor($NAddr[2]/16)*16).'::/44';
    $cidr[44]=$NAddr[0].':'.$NAddr[1].':'.dechex(floor($NAddr[2]/8)*8).'::/45';
    $cidr[45]=$NAddr[0].':'.$NAddr[1].':'.dechex(floor($NAddr[2]/4)*4).'::/46';
    $cidr[46]=$NAddr[0].':'.$NAddr[1].':'.dechex(floor($NAddr[2]/2)*2).'::/47';
    $NAddr[2]=dechex($NAddr[2]);
    $cidr[47]=$NAddr[0].':'.$NAddr[1].':'.$NAddr[2].'::/48';
    $cidr[48]=($NAddr[3]<32768)?$NAddr[0].':'.$NAddr[1].':'.$NAddr[2].'::/49':$NAddr[0].':'.$NAddr[1].':'.$NAddr[2].':8000::/49';
    $cidr[49]=$NAddr[0].':'.$NAddr[1].':'.$NAddr[2].':'.dechex(floor($NAddr[3]/16384)*16384).'::/50';
    $cidr[50]=$NAddr[0].':'.$NAddr[1].':'.$NAddr[2].':'.dechex(floor($NAddr[3]/8192)*8192).'::/51';
    $cidr[51]=$NAddr[0].':'.$NAddr[1].':'.$NAddr[2].':'.dechex(floor($NAddr[3]/4096)*4096).'::/52';
    $cidr[52]=$NAddr[0].':'.$NAddr[1].':'.$NAddr[2].':'.dechex(floor($NAddr[3]/2048)*2048).'::/53';
    $cidr[53]=$NAddr[0].':'.$NAddr[1].':'.$NAddr[2].':'.dechex(floor($NAddr[3]/1024)*1024).'::/54';
    $cidr[54]=$NAddr[0].':'.$NAddr[1].':'.$NAddr[2].':'.dechex(floor($NAddr[3]/512)*512).'::/55';
    $cidr[55]=$NAddr[0].':'.$NAddr[1].':'.$NAddr[2].':'.dechex(floor($NAddr[3]/256)*256).'::/56';
    $cidr[56]=$NAddr[0].':'.$NAddr[1].':'.$NAddr[2].':'.dechex(floor($NAddr[3]/128)*128).'::/57';
    $cidr[57]=$NAddr[0].':'.$NAddr[1].':'.$NAddr[2].':'.dechex(floor($NAddr[3]/64)*64).'::/58';
    $cidr[58]=$NAddr[0].':'.$NAddr[1].':'.$NAddr[2].':'.dechex(floor($NAddr[3]/32)*32).'::/59';
    $cidr[59]=$NAddr[0].':'.$NAddr[1].':'.$NAddr[2].':'.dechex(floor($NAddr[3]/16)*16).'::/60';
    $cidr[60]=$NAddr[0].':'.$NAddr[1].':'.$NAddr[2].':'.dechex(floor($NAddr[3]/8)*8).'::/61';
    $cidr[61]=$NAddr[0].':'.$NAddr[1].':'.$NAddr[2].':'.dechex(floor($NAddr[3]/4)*4).'::/62';
    $cidr[62]=$NAddr[0].':'.$NAddr[1].':'.$NAddr[2].':'.dechex(floor($NAddr[3]/2)*2).'::/63';
    $NAddr[3]=dechex($NAddr[3]);
    $cidr[63]=$NAddr[0].':'.$NAddr[1].':'.$NAddr[2].':'.$NAddr[3].'::/64';
    $cidr[64]=($NAddr[4]<32768)?$NAddr[0].':'.$NAddr[1].':'.$NAddr[2].':'.$NAddr[3].'::/65':$NAddr[0].':'.$NAddr[1].':'.$NAddr[2].':'.$NAddr[3].':8000::/65';
    $cidr[65]=$NAddr[0].':'.$NAddr[1].':'.$NAddr[2].':'.$NAddr[3].':'.dechex(floor($NAddr[4]/16384)*16384).'::/66';
    $cidr[66]=$NAddr[0].':'.$NAddr[1].':'.$NAddr[2].':'.$NAddr[3].':'.dechex(floor($NAddr[4]/8192)*8192).'::/67';
    $cidr[67]=$NAddr[0].':'.$NAddr[1].':'.$NAddr[2].':'.$NAddr[3].':'.dechex(floor($NAddr[4]/4096)*4096).'::/68';
    $cidr[68]=$NAddr[0].':'.$NAddr[1].':'.$NAddr[2].':'.$NAddr[3].':'.dechex(floor($NAddr[4]/2048)*2048).'::/69';
    $cidr[69]=$NAddr[0].':'.$NAddr[1].':'.$NAddr[2].':'.$NAddr[3].':'.dechex(floor($NAddr[4]/1024)*1024).'::/70';
    $cidr[70]=$NAddr[0].':'.$NAddr[1].':'.$NAddr[2].':'.$NAddr[3].':'.dechex(floor($NAddr[4]/512)*512).'::/71';
    $cidr[71]=$NAddr[0].':'.$NAddr[1].':'.$NAddr[2].':'.$NAddr[3].':'.dechex(floor($NAddr[4]/256)*256).'::/72';
    $cidr[72]=$NAddr[0].':'.$NAddr[1].':'.$NAddr[2].':'.$NAddr[3].':'.dechex(floor($NAddr[4]/128)*128).'::/73';
    $cidr[73]=$NAddr[0].':'.$NAddr[1].':'.$NAddr[2].':'.$NAddr[3].':'.dechex(floor($NAddr[4]/64)*64).'::/74';
    $cidr[74]=$NAddr[0].':'.$NAddr[1].':'.$NAddr[2].':'.$NAddr[3].':'.dechex(floor($NAddr[4]/32)*32).'::/75';
    $cidr[75]=$NAddr[0].':'.$NAddr[1].':'.$NAddr[2].':'.$NAddr[3].':'.dechex(floor($NAddr[4]/16)*16).'::/76';
    $cidr[76]=$NAddr[0].':'.$NAddr[1].':'.$NAddr[2].':'.$NAddr[3].':'.dechex(floor($NAddr[4]/8)*8).'::/77';
    $cidr[77]=$NAddr[0].':'.$NAddr[1].':'.$NAddr[2].':'.$NAddr[3].':'.dechex(floor($NAddr[4]/4)*4).'::/78';
    $cidr[78]=$NAddr[0].':'.$NAddr[1].':'.$NAddr[2].':'.$NAddr[3].':'.dechex(floor($NAddr[4]/2)*2).'::/79';
    $NAddr[4]=dechex($NAddr[4]);
    $cidr[79]=$NAddr[0].':'.$NAddr[1].':'.$NAddr[2].':'.$NAddr[3].':'.$NAddr[4].'::/80';
    $cidr[80]=($NAddr[5]<32768)?$NAddr[0].':'.$NAddr[1].':'.$NAddr[2].':'.$NAddr[3].':'.$NAddr[4].'::/81':$NAddr[0].':'.$NAddr[1].':'.$NAddr[2].':'.$NAddr[3].':'.$NAddr[4].':8000::/81';
    $cidr[81]=$NAddr[0].':'.$NAddr[1].':'.$NAddr[2].':'.$NAddr[3].':'.$NAddr[4].':'.dechex(floor($NAddr[5]/16384)*16384).'::/82';
    $cidr[82]=$NAddr[0].':'.$NAddr[1].':'.$NAddr[2].':'.$NAddr[3].':'.$NAddr[4].':'.dechex(floor($NAddr[5]/8192)*8192).'::/83';
    $cidr[83]=$NAddr[0].':'.$NAddr[1].':'.$NAddr[2].':'.$NAddr[3].':'.$NAddr[4].':'.dechex(floor($NAddr[5]/4096)*4096).'::/84';
    $cidr[84]=$NAddr[0].':'.$NAddr[1].':'.$NAddr[2].':'.$NAddr[3].':'.$NAddr[4].':'.dechex(floor($NAddr[5]/2048)*2048).'::/85';
    $cidr[85]=$NAddr[0].':'.$NAddr[1].':'.$NAddr[2].':'.$NAddr[3].':'.$NAddr[4].':'.dechex(floor($NAddr[5]/1024)*1024).'::/86';
    $cidr[86]=$NAddr[0].':'.$NAddr[1].':'.$NAddr[2].':'.$NAddr[3].':'.$NAddr[4].':'.dechex(floor($NAddr[5]/512)*512).'::/87';
    $cidr[87]=$NAddr[0].':'.$NAddr[1].':'.$NAddr[2].':'.$NAddr[3].':'.$NAddr[4].':'.dechex(floor($NAddr[5]/256)*256).'::/88';
    $cidr[88]=$NAddr[0].':'.$NAddr[1].':'.$NAddr[2].':'.$NAddr[3].':'.$NAddr[4].':'.dechex(floor($NAddr[5]/128)*128).'::/89';
    $cidr[89]=$NAddr[0].':'.$NAddr[1].':'.$NAddr[2].':'.$NAddr[3].':'.$NAddr[4].':'.dechex(floor($NAddr[5]/64)*64).'::/90';
    $cidr[90]=$NAddr[0].':'.$NAddr[1].':'.$NAddr[2].':'.$NAddr[3].':'.$NAddr[4].':'.dechex(floor($NAddr[5]/32)*32).'::/91';
    $cidr[91]=$NAddr[0].':'.$NAddr[1].':'.$NAddr[2].':'.$NAddr[3].':'.$NAddr[4].':'.dechex(floor($NAddr[5]/16)*16).'::/92';
    $cidr[92]=$NAddr[0].':'.$NAddr[1].':'.$NAddr[2].':'.$NAddr[3].':'.$NAddr[4].':'.dechex(floor($NAddr[5]/8)*8).'::/93';
    $cidr[93]=$NAddr[0].':'.$NAddr[1].':'.$NAddr[2].':'.$NAddr[3].':'.$NAddr[4].':'.dechex(floor($NAddr[5]/4)*4).'::/94';
    $cidr[94]=$NAddr[0].':'.$NAddr[1].':'.$NAddr[2].':'.$NAddr[3].':'.$NAddr[4].':'.dechex(floor($NAddr[5]/2)*2).'::/95';
    $NAddr[5]=dechex($NAddr[5]);
    $cidr[95]=$NAddr[0].':'.$NAddr[1].':'.$NAddr[2].':'.$NAddr[3].':'.$NAddr[4].':'.$NAddr[5].'::/96';
    $cidr[96]=($NAddr[6]<32768)?$NAddr[0].':'.$NAddr[1].':'.$NAddr[2].':'.$NAddr[3].':'.$NAddr[4].':'.$NAddr[5].'::/97':$NAddr[0].':'.$NAddr[1].':'.$NAddr[2].':'.$NAddr[3].':'.$NAddr[4].':'.$NAddr[5].':8000:0/97';
    $cidr[97]=$NAddr[0].':'.$NAddr[1].':'.$NAddr[2].':'.$NAddr[3].':'.$NAddr[4].':'.$NAddr[5].':'.dechex(floor($NAddr[6]/16384)*16384).':0/98';
    $cidr[98]=$NAddr[0].':'.$NAddr[1].':'.$NAddr[2].':'.$NAddr[3].':'.$NAddr[4].':'.$NAddr[5].':'.dechex(floor($NAddr[6]/8192)*8192).':0/99';
    $cidr[99]=$NAddr[0].':'.$NAddr[1].':'.$NAddr[2].':'.$NAddr[3].':'.$NAddr[4].':'.$NAddr[5].':'.dechex(floor($NAddr[6]/4096)*4096).':0/100';
    $cidr[100]=$NAddr[0].':'.$NAddr[1].':'.$NAddr[2].':'.$NAddr[3].':'.$NAddr[4].':'.$NAddr[5].':'.dechex(floor($NAddr[6]/2048)*2048).':0/101';
    $cidr[101]=$NAddr[0].':'.$NAddr[1].':'.$NAddr[2].':'.$NAddr[3].':'.$NAddr[4].':'.$NAddr[5].':'.dechex(floor($NAddr[6]/1024)*1024).':0/102';
    $cidr[102]=$NAddr[0].':'.$NAddr[1].':'.$NAddr[2].':'.$NAddr[3].':'.$NAddr[4].':'.$NAddr[5].':'.dechex(floor($NAddr[6]/512)*512).':0/103';
    $cidr[103]=$NAddr[0].':'.$NAddr[1].':'.$NAddr[2].':'.$NAddr[3].':'.$NAddr[4].':'.$NAddr[5].':'.dechex(floor($NAddr[6]/256)*256).':0/104';
    $cidr[104]=$NAddr[0].':'.$NAddr[1].':'.$NAddr[2].':'.$NAddr[3].':'.$NAddr[4].':'.$NAddr[5].':'.dechex(floor($NAddr[6]/128)*128).':0/105';
    $cidr[105]=$NAddr[0].':'.$NAddr[1].':'.$NAddr[2].':'.$NAddr[3].':'.$NAddr[4].':'.$NAddr[5].':'.dechex(floor($NAddr[6]/64)*64).':0/106';
    $cidr[106]=$NAddr[0].':'.$NAddr[1].':'.$NAddr[2].':'.$NAddr[3].':'.$NAddr[4].':'.$NAddr[5].':'.dechex(floor($NAddr[6]/32)*32).':0/107';
    $cidr[107]=$NAddr[0].':'.$NAddr[1].':'.$NAddr[2].':'.$NAddr[3].':'.$NAddr[4].':'.$NAddr[5].':'.dechex(floor($NAddr[6]/16)*16).':0/108';
    $cidr[108]=$NAddr[0].':'.$NAddr[1].':'.$NAddr[2].':'.$NAddr[3].':'.$NAddr[4].':'.$NAddr[5].':'.dechex(floor($NAddr[6]/8)*8).':0/109';
    $cidr[109]=$NAddr[0].':'.$NAddr[1].':'.$NAddr[2].':'.$NAddr[3].':'.$NAddr[4].':'.$NAddr[5].':'.dechex(floor($NAddr[6]/4)*4).':0/110';
    $cidr[110]=$NAddr[0].':'.$NAddr[1].':'.$NAddr[2].':'.$NAddr[3].':'.$NAddr[4].':'.$NAddr[5].':'.dechex(floor($NAddr[6]/2)*2).':0/111';
    $NAddr[6]=dechex($NAddr[6]);
    $cidr[111]=$NAddr[0].':'.$NAddr[1].':'.$NAddr[2].':'.$NAddr[3].':'.$NAddr[4].':'.$NAddr[5].':'.$NAddr[6].':0/112';
    $cidr[112]=($NAddr[7]<32768)?$NAddr[0].':'.$NAddr[1].':'.$NAddr[2].':'.$NAddr[3].':'.$NAddr[4].':'.$NAddr[5].':'.$NAddr[6].':0/113':$NAddr[0].':'.$NAddr[1].':'.$NAddr[2].':'.$NAddr[3].':'.$NAddr[4].':'.$NAddr[5].':'.$NAddr[6].':8000/113';
    $cidr[113]=$NAddr[0].':'.$NAddr[1].':'.$NAddr[2].':'.$NAddr[3].':'.$NAddr[4].':'.$NAddr[5].':'.$NAddr[6].':'.dechex(floor($NAddr[7]/16384)*16384).'/114';
    $cidr[114]=$NAddr[0].':'.$NAddr[1].':'.$NAddr[2].':'.$NAddr[3].':'.$NAddr[4].':'.$NAddr[5].':'.$NAddr[6].':'.dechex(floor($NAddr[7]/8192)*8192).'/115';
    $cidr[115]=$NAddr[0].':'.$NAddr[1].':'.$NAddr[2].':'.$NAddr[3].':'.$NAddr[4].':'.$NAddr[5].':'.$NAddr[6].':'.dechex(floor($NAddr[7]/4096)*4096).'/116';
    $cidr[116]=$NAddr[0].':'.$NAddr[1].':'.$NAddr[2].':'.$NAddr[3].':'.$NAddr[4].':'.$NAddr[5].':'.$NAddr[6].':'.dechex(floor($NAddr[7]/2048)*2048).'/117';
    $cidr[117]=$NAddr[0].':'.$NAddr[1].':'.$NAddr[2].':'.$NAddr[3].':'.$NAddr[4].':'.$NAddr[5].':'.$NAddr[6].':'.dechex(floor($NAddr[7]/1024)*1024).'/118';
    $cidr[118]=$NAddr[0].':'.$NAddr[1].':'.$NAddr[2].':'.$NAddr[3].':'.$NAddr[4].':'.$NAddr[5].':'.$NAddr[6].':'.dechex(floor($NAddr[7]/512)*512).'/119';
    $cidr[119]=$NAddr[0].':'.$NAddr[1].':'.$NAddr[2].':'.$NAddr[3].':'.$NAddr[4].':'.$NAddr[5].':'.$NAddr[6].':'.dechex(floor($NAddr[7]/256)*256).'/120';
    $cidr[120]=$NAddr[0].':'.$NAddr[1].':'.$NAddr[2].':'.$NAddr[3].':'.$NAddr[4].':'.$NAddr[5].':'.$NAddr[6].':'.dechex(floor($NAddr[7]/128)*128).'/121';
    $cidr[121]=$NAddr[0].':'.$NAddr[1].':'.$NAddr[2].':'.$NAddr[3].':'.$NAddr[4].':'.$NAddr[5].':'.$NAddr[6].':'.dechex(floor($NAddr[7]/64)*64).'/122';
    $cidr[122]=$NAddr[0].':'.$NAddr[1].':'.$NAddr[2].':'.$NAddr[3].':'.$NAddr[4].':'.$NAddr[5].':'.$NAddr[6].':'.dechex(floor($NAddr[7]/32)*32).'/123';
    $cidr[123]=$NAddr[0].':'.$NAddr[1].':'.$NAddr[2].':'.$NAddr[3].':'.$NAddr[4].':'.$NAddr[5].':'.$NAddr[6].':'.dechex(floor($NAddr[7]/16)*16).'/124';
    $cidr[124]=$NAddr[0].':'.$NAddr[1].':'.$NAddr[2].':'.$NAddr[3].':'.$NAddr[4].':'.$NAddr[5].':'.$NAddr[6].':'.dechex(floor($NAddr[7]/8)*8).'/125';
    $cidr[125]=$NAddr[0].':'.$NAddr[1].':'.$NAddr[2].':'.$NAddr[3].':'.$NAddr[4].':'.$NAddr[5].':'.$NAddr[6].':'.dechex(floor($NAddr[7]/4)*4).'/126';
    $cidr[126]=$NAddr[0].':'.$NAddr[1].':'.$NAddr[2].':'.$NAddr[3].':'.$NAddr[4].':'.$NAddr[5].':'.$NAddr[6].':'.dechex(floor($NAddr[7]/2)*2).'/127';
    $NAddr[7]=dechex($NAddr[7]);
    $cidr[127]=$NAddr[0].':'.$NAddr[1].':'.$NAddr[2].':'.$NAddr[3].':'.$NAddr[4].':'.$NAddr[5].':'.$NAddr[6].':'.$NAddr[7].'/128';
    for($i=0;$i<128;$i++)
        {
        if(substr_count($cidr[$i],'::'))
            {
            $cidr[$i]=preg_replace('/(\:0)*\:\:(0\:)*/i','::',$cidr[$i],1);
            $cidr[$i]=str_replace('::0/','::/',$cidr[$i]);
            continue;
            }
        if(substr_count($cidr[$i],':0:0/'))
            {
            $cidr[$i]=preg_replace('/(\:0){2,}\//i','::/',$cidr[$i],1);
            continue;
            }
        if(substr_count($cidr[$i],':0:0:'))
            {
            $cidr[$i]=preg_replace('/(\:0)+\:(0\:)+/i','::',$cidr[$i],1);
            $cidr[$i]=str_replace('::0/','::/',$cidr[$i]);
            continue;
            }
        }
    if($Dump)return $cidr;
    $IPv6Sigs=IPTestScriptReadFile($GLOBALS['vault'].'ipv6.dat');
    for($i=0;$i<128;$i++)
        {
        $PosA=strpos($IPv6Sigs,"\n".$cidr[$i].' ');
        if($PosA===false)continue;
        $PosA+=strlen($cidr[$i])+2;
        if(!$PosB=strpos($IPv6Sigs,"\n",$PosA))continue;
        $Sig=substr($IPv6Sigs,$PosA,($PosB-$PosA));
        $Cat=substr($Sig,0,strpos($Sig,' '));
        $Sig=substr($Sig,strpos($Sig,' ')+1);
        if($Cat==='Run')
            {
            if(file_exists($GLOBALS['vault'].$Sig))require_once $GLOBALS['vault'].$Sig;
            // AAA else give me some error message
            continue;
            }
        if($Cat==='Deny')
            {
            // AAA Need to sort out categories for blocking.
            // AAA Need to sort out categories for blocking.
            if($Sig==='Bogon'&&$GLOBALS['IPTestScript']['Config']['signatures']['block_bogons'])
                {
                $GLOBALS['IPTestScript']['BlockInfo']['ReasonMessage'].=$GLOBALS['IPTestScript']['lang']['ReasonMessage_Bogon'];
                if(!empty($GLOBALS['IPTestScript']['BlockInfo']['Signatures']))$GLOBALS['IPTestScript']['BlockInfo']['Signatures'].=', ';
                $GLOBALS['IPTestScript']['BlockInfo']['Signatures'].=$cidr[$i];
                $GLOBALS['IPTestScript']['BlockInfo']['SignatureCount']++;
                continue;
                }
            if($Sig==='Cloud'&&$GLOBALS['IPTestScript']['Config']['signatures']['block_cloud'])
                {
                $GLOBALS['IPTestScript']['BlockInfo']['ReasonMessage'].=$GLOBALS['IPTestScript']['lang']['ReasonMessage_Cloud'];
                if(!empty($GLOBALS['IPTestScript']['BlockInfo']['Signatures']))$GLOBALS['IPTestScript']['BlockInfo']['Signatures'].=', ';
                $GLOBALS['IPTestScript']['BlockInfo']['Signatures'].=$cidr[$i];
                $GLOBALS['IPTestScript']['BlockInfo']['SignatureCount']++;
                continue;
                }
            if($Sig==='Generic'&&$GLOBALS['IPTestScript']['Config']['signatures']['block_generic'])
                {
                $GLOBALS['IPTestScript']['BlockInfo']['ReasonMessage'].=$GLOBALS['IPTestScript']['lang']['ReasonMessage_Generic'];
                if(!empty($GLOBALS['IPTestScript']['BlockInfo']['Signatures']))$GLOBALS['IPTestScript']['BlockInfo']['Signatures'].=', ';
                $GLOBALS['IPTestScript']['BlockInfo']['Signatures'].=$cidr[$i];
                $GLOBALS['IPTestScript']['BlockInfo']['SignatureCount']++;
                continue;
                }
            if($Sig==='Spam'&&$GLOBALS['IPTestScript']['Config']['signatures']['block_spam'])
                {
                $GLOBALS['IPTestScript']['BlockInfo']['ReasonMessage'].=$GLOBALS['IPTestScript']['lang']['ReasonMessage_Spam'];
                if(!empty($GLOBALS['IPTestScript']['BlockInfo']['Signatures']))$GLOBALS['IPTestScript']['BlockInfo']['Signatures'].=', ';
                $GLOBALS['IPTestScript']['BlockInfo']['Signatures'].=$cidr[$i];
                $GLOBALS['IPTestScript']['BlockInfo']['SignatureCount']++;
                }
            continue;
            }
        if($Cat==='DenyCustom')
            {
            $GLOBALS['IPTestScript']['BlockInfo']['ReasonMessage'].=$Sig;
            if(!empty($GLOBALS['IPTestScript']['BlockInfo']['Signatures']))$GLOBALS['IPTestScript']['BlockInfo']['Signatures'].=', ';
            $GLOBALS['IPTestScript']['BlockInfo']['Signatures'].=$cidr[$i];
            $GLOBALS['IPTestScript']['BlockInfo']['SignatureCount']++;
            }
        }
    return true;
    }

if(!function_exists('IPTestScriptReadFile'))
    {
    /**
     * This function reads files and returns the contents of those files.
     *
     * @param string $f Path and filename of the file to read.
     * @return string|bool Content of the file returned by the function (or false on failure).
     */
    function IPTestScriptReadFile($f)
        {
        if(!is_file($f))return false;
        $s=@ceil(filesize($f)/49152);
        $d='';
        if($s>0)
            {
            $fh=fopen($f,'rb');
            $r=0;
            while($r<$s)
                {
                $d.=fread($fh,49152);
                $r++;
                }
            fclose($fh);
            }
        return (!empty($d))?$d:false;
        }
    }

$IPTestScript['BlockInfo']=array();
$IPTestScript['BlockInfo']['DateTime']=date('r');
$IPTestScript['BlockInfo']['IPAddr']=$_SERVER[$IPTestScript['Config']['general']['ipaddr']];
$IPTestScript['BlockInfo']['IPTestScriptVersion']=$IPTestScript['Version'];
$IPTestScript['BlockInfo']['Path']=(!empty($_SERVER['PATH_INFO']))?$_SERVER['PATH_INFO']:'';
$IPTestScript['BlockInfo']['Query']=(!empty($_SERVER['QUERY_STRING']))?$_SERVER['QUERY_STRING']:'';
$IPTestScript['BlockInfo']['ReasonMessage']='';
$IPTestScript['BlockInfo']['SignatureCount']=0;
$IPTestScript['BlockInfo']['Signatures']='';
$IPTestScript['BlockInfo']['UA']=(!empty($_SERVER['HTTP_USER_AGENT']))?$_SERVER['HTTP_USER_AGENT']:'';
$IPTestScript['BlockInfo']['xmlLang']=$IPTestScript['Config']['general']['lang'];

/**
 * Run the IPv4 test.
 */
$IPTestScript['TestIPv4']=IPv4Test($_SERVER[$IPTestScript['Config']['general']['ipaddr']]);

/**
 * Run the IPv6 test.
 */
$IPTestScript['TestIPv6']=IPv6Test($_SERVER[$IPTestScript['Config']['general']['ipaddr']]);

/**
 * If both fail...
 */
if(!$IPTestScript['TestIPv4']&&!$IPTestScript['TestIPv6'])
    {
    $IPTestScript['BlockInfo']['ReasonMessage'].=$IPTestScript['lang']['ReasonMessage_BadIP'];
    if(!empty($IPTestScript['BlockInfo']['Signatures']))$IPTestScript['BlockInfo']['Signatures'].=', ';
    $IPTestScript['BlockInfo']['Signatures'].=$IPTestScript['lang']['Short_BadIP'];
    $IPTestScript['BlockInfo']['SignatureCount']++;
    }

/**
 * If any signatures were triggered, log the event and generate page output.
 */
if($IPTestScript['BlockInfo']['SignatureCount'])
    {
    $IPTestScript['template_file']='template.html';
    if($IPTestScript['Config']['general']['logfile'])
        {
        $IPTestScript['logfileData']=array();
        $IPTestScript['logfileData']['d']=(!file_exists($vault.$IPTestScript['Config']['general']['logfile']))?"\x3c\x3fphp die; \x3f\x3e\n\n":'';
        $IPTestScript['logfileData']['d'].=ParseVars($IPTestScript['lang'],ParseVars($IPTestScript['BlockInfo'],"{field_id}XXX\n{field_datetime}{DateTime}\n{field_ipaddr}{IPAddr}\n{field_path}{Path}\n{field_query}{Query}\n{field_sigcount}{SignatureCount}\n{field_sigref}{Signatures}\n{field_ua}{UA}\n\n"));
        $IPTestScript['logfileData']['f']=fopen($vault.$IPTestScript['Config']['general']['logfile'],'a');
        fwrite($IPTestScript['logfileData']['f'],$IPTestScript['logfileData']['d']);
        fclose($IPTestScript['logfileData']['f']);
        unset($IPTestScript['logfileData']);
        }
    if(!file_exists($vault.$IPTestScript['template_file']))plaintext_echo_die('[IPTestScript] '.$IPTestScript['lang']['denied']);
    if($IPTestScript['Config']['general']['forbid_on_block'])
        {
        header('HTTP/1.0 403 Forbidden');
        header('HTTP/1.1 403 Forbidden');
        header('Status: 403 Forbidden');
        }
    $IPTestScript['html']=ParseVars($IPTestScript['lang'],ParseVars($IPTestScript['BlockInfo'],IPTestScriptReadFile($vault.$IPTestScript['template_file'])));
    die($IPTestScript['html']);
    }

/**
 * For testing IPv4 in CLI:
 * var_dump(IPv4Test($argv[1],true));
 *
 * For testing IPv6 in CLI:
 * var_dump(IPv6Test($argv[1],true));
 *
 * CLI usage:
 * php.exe /path/to/script.php %IPAddr%
 */

/**
 * We unset the script variables and exit cleanly.
 */
unset($IPTestScript,$vault);
