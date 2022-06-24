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
 * This file: Bad hosts blocker module (last modified: 2022.06.24).
 *
 * False positive risk (an approximate, rough estimate only): « [ ]Low [x]Medium [ ]High »
 */

/** Safety. */
if (!isset($this->CIDRAM['ModuleResCache'])) {
    $this->CIDRAM['ModuleResCache'] = [];
}

/** Defining as closure for later recall (no params; no return value). */
$this->CIDRAM['ModuleResCache'][$Module] = function () {
    /** Guard. */
    if (empty($this->BlockInfo['IPAddr'])) {
        return;
    }

    /** The number of signatures triggered by this point in time. */
    $Before = $this->BlockInfo['SignaturesCount'] ?? 0;

    /** Don't continue if compatibility indicators exist. */
    if (strpos($this->BlockInfo['Signatures'], 'bunnycdn.php') !== false) {
        return;
    }

    /** Fetch hostname. */
    if (empty($this->CIDRAM['Hostname'])) {
        $this->CIDRAM['Hostname'] = $this->dnsReverse($this->BlockInfo['IPAddr']);
    }

    /** Safety mechanism against false positives caused by failed lookups. */
    if (
        !$this->CIDRAM['Hostname'] ||
        $this->CIDRAM['Hostname'] === $this->BlockInfo['IPAddr'] ||
        preg_match('~^b\.in-addr-servers\.nstld~', $this->CIDRAM['Hostname'])
    ) {
        return;
    }

    /** Signatures start here. */
    $HN = preg_replace('/\s/', '', str_replace("\\", '/', strtolower(urldecode($this->CIDRAM['Hostname']))));
    $UA = str_replace("\\", '/', strtolower(urldecode($this->BlockInfo['UA'])));
    $UANoSpace = preg_replace('/\s/', '', $UA);

    $this->trigger(preg_match(
        '/\$(?:globals|_(cookie|env|files|get|post|request|se(rver|ssion)))/',
        $HN
    ), 'Banned hostname'); // 2017.01.21

    $this->trigger(preg_match(
        '/(?:<(\?|body|i?frame|object|script)|(body|i?frame|object|script)>)/',
        $HN
    ), 'Hostname script injection'); // 2017.01.21

    $this->trigger(preg_match('~captch|dbcapi\.me~', $HN), 'CAPTCHA cracker host'); // 2017.01.21

    $this->trigger(preg_match(
        '~prking\.com\.au$|(?:qvt|telsp)\.net\.br$|(?:\.(?:giga-dns|oodle|po' .
        'intandchange|solidseo(?:dedicated|vps)?|topsy|vadino)|23gb|35up|acc' .
        'elovation|barefruit|bestprice|colo\.iinet|detangled|kimsufi|lightsp' .
        'eedsystems|lipperhey|mantraonline|netcomber|onlinehome-server\.myfo' .
        'rexvps|page-store|setooz|technicolor)\.com$|poneytelecom\.eu$|(?:4u' .
        '|netadvert|onlinehome-server)\.info$|mobilemarketingaid\.info|(?:3f' .
        'n|buyurl|dragonara|isnet|mfnx|onlinehome-server)\.net$|seomoz\.org$' .
        '|(?:dimargroup|itrack|mail|rulinki|vipmailoffer)\.ru$|(?:2kom|solom' .
        'ono)\.ru|\.v4\.ngi\.it|awcheck|b(?:oardreader|reakingtopics|uysells' .
        'ales)|c(?:eptro|heapseovps|yber-uslugi)|drugstore|liwio\.|luxuryhan' .
        'dbag|s(?:emalt|mileweb\.com\.ua|quider|tartdedicated\.)|(?:exa|full' .
        'textro|we)bot~',
        $HN
    ), 'SEO/Bothost/Scraper/Spamhost'); // 2020.11.15 mod 2021.06.28

    $this->trigger(preg_match('~cjh-law\.com$~', $HN), 'Phisher / Phishing Host'); // 2017.02.14

    $this->trigger(preg_match('~exatt\.net$|unpef\.org$~', $HN), 'Pornobot/Pornhost'); // 2017.02.16

    $this->trigger(preg_match(
        '~^(?:damage|moon|test)\.|anahaqq|core\.youtu\.me|hosted-(?:by|in)|n' .
        'o-(?:data|(?:reverse-)?r?dns)|qeas|spletnahisa|therewill\.be|unassi' .
        'gned|work\.from|yhost\.name~',
        $HN
    ), 'Questionable Host'); // 2017.01.30 mod 2020.11.09

    $this->trigger(preg_match(
        '~\.(?:as13448|websense)\.|(?:bibbly|pulsepoint|zvelo)\.com|(?:\.fil' .
        'espot|cloudsystemnetworks)\.com$|westdc\.net|propagation\.net$|maje' .
        'stic|meanpath|tag-trek~',
        $HN
    ), 'Unauthorised'); // 2018.09.15

    // Caught attempting to brute-force WordPress logins.
    $this->trigger(preg_match('~\.domainserver\.ne\.jp$~', $HN), 'Cloud/Webhosting'); // 2020.11.09

    $this->trigger(preg_match('~anchorfree|hotspotsheild|esonicspider\.com$~', $HN), 'Hostile/esonicspider'); // 2018.09.15

    $this->trigger(preg_match(
        '~megacom\.biz$|ideastack\.com$|dotnetdotcom\.org$|controlyourself\.online|seeweb\.it~',
        $HN
    ), 'Hostile/Unauthorised'); // 2017.02.14 mod 2021.06.28

    $this->trigger(preg_match('~brandaffinity~', $HN), 'Hostile/SLAPP'); // 2018.09.15

    $this->trigger(preg_match(
        '~i(?:g|nsite)\.com\.br$|terra\.cl$|(?:\.(?:appian|cloud|ctera|dyn|emc|fo' .
        'rce|fsfreeware|gnip|gridlayer|hosting|icims|panorama|parallels|quest|the' .
        'gridlayer|voda|vultr|webzilla|workday)|10gen|12designer|3leafsystems|3te' .
        'ra|a(?:ccentrainc|conex|dvologix|gathon|ltornetworks|mericanforeclosures' .
        '|mitive|pp(?:irio|istry|jet|nexus|renda|spot|zero)|ptana|riasystems|rjun' .
        'a|rtofdefence|sterdata|syanka|uthenticnetworks|zati)|b(?:alticservers|ea' .
        'm4d|hivesoft|irtondemand|linklogic|lue(?:host|lock|wolf)|oomi|ucketexplo' .
        'rer|ungeeconnect)|c(?:a(?:dinor|msolutionsinc|spio|ssatt|stiron)|l(?:ari' .
        'oanalytics|ickability|oud(?:42|9analytics|computingchina|control|era|fou' .
        'ndry|kick|scale|status|switch|works)|usterseven)|o(?:ghead|hesiveft|ldli' .
        'ghtsolutions|ncur|ntroltier)|tinets)|d(?:ata(?:line|sisar|synaps)|edicat' .
        'edpanel|irectlaw|ns-safe|oclanding|ropbox|ynamsoft)|e(?:last(?:ichosts|r' .
        'a)|n(?:gineyard|omalism|stratus)|telos|ucalyptus|vapt|vionet)|fathomdb|f' .
        'lexiscale|followmeoffice|g(?:emstone|enerositycool|igaspaces|ogrid|roupc' .
        'ross)|h(?:eroku|exagrid|olhost|ost(?:acy|cats|ing24)|ubspan|yperic)|i(?:' .
        'cloud|modrive|nfo(?:bright|rmatica)|tricityhosting)|j(?:oyent|umpbox|ung' .
        'lebox|usthost)|k(?:2analytics|aavo|eynote|nowledgetree)|l(?:ayeredtech|i' .
        'nkneo|iveops|oadstorm|ogixml|ongjump|tdomains)|m(?:o(?:derro|jsite|rphex' .
        'change|sso|zy)|idphase|turk|ulesoft)|n(?:asstar|e(?:ointeractiva|t(?:app' .
        '|documents|suite|topia)|wrelic|wservers)|ionex|irvanix|ovatium|scaled)|o' .
        '(?:co-inc|nelogin|npathtech|penqrm|psource)|p(?:ara(?:scal|tur)e|hatserv' .
        'ers|iemontetv|inqidentity|ivotlink|luraprocessing)|q(?:layer|rimp|uanti(' .
        '?:vo|x-uk))|r(?:ackspace(?:cloud)?|e(?:di2|ductivelabs|lia(?:blehosting|' .
        'cloud)|sponsys)|ight(?:now|scale)|ollbase|omania-webhosting|path)|s(?:al' .
        'esforce|avvis|ertifi|huilinchi|kytap|martservercontrol|naplogic|oasta|pr' .
        'ingcm|tax|treetsmarts|tretchoid|uccessmetrics|wifttrim|ymplified|yncplic' .
        'ity)|t(?:aleo|err[ae]mark|h(?:eprocessfactory|inkgos|oughtexpress)|rusts' .
        'aas)|utilitystatus|v(?:aultscape|ertica|mware|ordel)|web(?:hosting\.uk|s' .
        'calesolutions)|xactlycorp|xlhost|xythos|z(?:embly|imory|manda|oho|uora))' .
        '\.com$|(?:alxagency|capellahealthcare|host(?:gator|ingprod)|instantdedic' .
        'ated|khavarzamin|link88\.seo|securityspace|serve(?:path|rbuddies))\.com|' .
        '(?:(?:\.|kunden)server|clanmoi|fastwebserver|optimal|server4you)\.de$|yo' .
        'ur-server\.de|eucalyptus\.cs\.uscb\.edu$|candycloud\.eu$|adsinmedia\.co\.in$' .
        '|server\.lu$|starnet\.md$|(?:\.(?:bhsrv|box|propagation|voxel)|1978th|co' .
        'llab|enkiconsulting|incrediserve|recyber|reliablesite|techajans)\.net$|h' .
        'itech-hosting\.nl|(?:\.terracotta|beowulf|iboss|opennebula|xen)\.org$|mo' .
        'r\.ph$|(?:ogicom|vampire)\.pl$|(?:cyber-host|slaskdatacenter)\.pl|(?:ser' .
        'verhub|rivreg)\.ru$|(?:tkvprok|vympelstroy)\.ru|g\.ho\.st$|(?:webfusion|' .
        'xcalibre)\.co\.uk$|bergdorf-group|cloudsigma|dreamhost|ipxserver|linode|' .
        'money(?:mattersnow|tech\.mg)|psychz|requestedoffers|scopehosts|s(?:p?lic' .
        'e|teep)host~',
        $HN
    ), 'Server farm'); // 2022.06.24

    $this->trigger(preg_match(
        '~\.google(?:domains|usercontent)\.com$~',
        $HN
    ), 'Google user content not permitted here'); // 2022.06.22

    $this->trigger(preg_match(
        '/(?:\.above|shared-server|jkserv)\.net$|akpackaging\.net|(?:academi' .
        'cedge|cyber-freaks|dailyrazor|gothamdating|ibuzytravel|server306|we' .
        'bfaction|\.siteprotect)\.com$|(?:aramenet|dinaserver|phishmongers|w' .
        'eb(?:hostinghub|sitewelcome))\.com|server4u\.cz$|acetrophies\.co\.u' .
        'k$|\.pomserve2\.co\.uk|webhostserver\.biz$|\.haremo\.de$|webcreator' .
        's\.nl|vpsnow\.ru$/',
        $HN
    ), 'Probe/Scanner'); // 2019.03.04

    if ($this->trigger(preg_match(
        '/(?:\.oroxy|anonine)\.com$|thefreevpn|vpn(?:999\.com|gate)|public-net/',
        $HN
    ), 'Risky/Proxy/VPN Host')) {
        $this->addProfileEntry('Tor endpoints here');
    } // 2021.03.18

    $this->trigger(preg_match(
        '~(?:(?:criminalip|dimenoc|dumpyourbitch|hostenko|internetserviceteam|ipr' .
        'edator|krypt|webandnetworksolutions|xcelmg)\.com|mbox\.kz|doctore\.sk|ho' .
        'stnoc\.net|\.(?:host|spheral)\.ru)$|45ru\.net\.au|dedipower|p(?:rohibitivestuff|wn)~',
        $HN
    ), 'Dangerous Host'); // 2022.06.24

    $this->trigger(preg_match(
        '~(?:iweb|privatedns)\.com$|iweb\.ca$|^(?:www\.)?iweb~',
        $HN
    ), 'Domain Snipers'); // 2017.02.15 mod 2021.06.28

    $this->trigger(preg_match('~(?<!ssg-corp\.)zetta\.net$|(?<!\.user\.)veloxzone\.com\.br$~', $HN), 'Server farm'); // 2022.06.24

    $this->trigger(empty($this->CIDRAM['Ignore']['SoftLayer']) && preg_match('/softlayer\.com$/', $HN) && (
        !substr_count($this->BlockInfo['UALC'], 'disqus') &&
        !substr_count($this->BlockInfo['UA'], 'Superfeedr bot/2.0') &&
        !substr_count($this->BlockInfo['UA'], 'Feedbot')
    ), 'SoftLayer'); // 2017.01.21 (ASN 36351) modified 2020.01.11

    $this->trigger(preg_match(
        '~(?:starlogic|temka)\.biz$|ethymos\.com\.br$|(?:amplilogic|astranig' .
        'ht|borderfreehosting|creatoor|dl-hosting|hosting-ie|idknet|ipilum|k' .
        'uzbass|prommorpg|uxxicom|vdswin|x-svr)\.com$|(?:ahost01|efdns|em-zw' .
        'o|haebdler-treff|key(account|mars64)|mail\.adc|rootbash|securewebse' .
        'rver|tagdance|traders-briefing|vilitas|w-4)\.de$|(?:hostrov|kemhost' .
        '|netorn|power-web34|profithost|volia)\.net$|cssgroup\.lv|(?:nasza-k' .
        'lasa|softel\.com)\.pl$|(?:corbina|cpms|datapoint|elsv-v|hc|itns|lim' .
        't|majordomo|mtu-net|netorn|nigma|relan|spb|totalstat)\.ru|(?:(?:cos' .
        'monova|sovam|utel)\.net|odessa|poltava|rbn\.com|volia)\.ua$|aceleo|' .
        'dedibox|filmefashion|infobox|key(?:machine|server|web)|kyklo|laycat' .
        '|oliro~',
        $HN
    ), 'RBN'); // 2017.02.06 mod 2021.06.28

    $this->trigger(preg_match('~amazonaws\.com$~', $HN) && (
        !preg_match(
            '~alexa|postrank|twitt(?:urly|erfeed)|bitlybot|unwindfetchor|met' .
            'auri|pinterest|slack|silk-accelerated=true$~',
            $UANoSpace
        ) &&
        substr($this->BlockInfo['UA'], -32) !== 'Feedspot http://www.feedspot.com'
    ), 'Amazon Web Services'); // 2021.06.30

    $this->trigger(preg_match('/\.local$/', $HN), 'Spoofed/Fake Hostname'); // 2017.02.06

    // See: https://zb-block.net/zbf/showthread.php?t=25
    $this->trigger(preg_match('/shodan\.io|(?:serverprofi24|aspadmin|project25499)\./', $HN), 'AutoSploit Host'); // 2018.02.02 mod 2021.02.07

    /** These signatures can set extended tracking options. */
    if (
        $this->trigger(substr($HN, 0, 2) === '()', 'Banned hostname (Bash/Shellshock)') || // 2017.01.21
        $this->trigger(preg_match(
            '/(?:0wn[3e]d|:(?:\{\w:|[\w\d][;:]\})|h[4a]ck(?:e[dr]|ing|[7t](?:[3e' .
            '][4a]m|[0o]{2}l))|%(?:0[0-8bcef]|1)|[`\'"]|^[-.:]|[-.:]$|[.:][\w\d-' .
            ']{64,}[.:])/i',
            $HN
        ), 'Banned hostname') || // 2018.06.24
        $this->trigger((
            strpos($HN, 'rm ' . '-rf') !== false ||
            strpos($HN, 'sh' . 'el' . 'l_' . 'ex' . 'ec') !== false ||
            strpos($HN, '$_' . '[$' . '__') !== false ||
            strpos($HN, '@$' . '_[' . ']=' . '@!' . '+_') !== false
        ), 'Banned hostname') || // 2017.01.21
        $this->trigger(preg_match('~rumer|pymep|румер~', $HN), 'Spamhost') || // 2017.01.21
        $this->trigger(preg_match('/^localhost$/', $HN) && (
            !preg_match('/^(?:1(?:27|92\.168)(?:\.1?\d{1,2}|\.2[0-4]\d|\.25[0-5]){2,3}|\:\:1)$/', $this->BlockInfo['IPAddr'])
        ), 'Spoofed/Fake Hostname') || // 2018.06.24
        $this->trigger($HN === '.', 'DNS error') // 2017.02.25
    ) {
        $this->CIDRAM['Tracking options override'] = 'extended';
    }

    /**
     * Only to be triggered if other signatures haven't already been triggered
     * and if CIDRAM has been configured to block proxies.
     */
    if (
        !$this->BlockInfo['SignatureCount'] &&
        $this->Configuration['signatures']['block_proxies'] &&

        // Prevents matching against Facebook requests (updated 2020.02.07).
        !preg_match('~^fwdproxy-.*\.fbsv\.net$~i', $HN) &&

        /**
         * Prevents matching against (updated 2020.04.05):
         * - Google Translate
         * - Google Webmasters
         * - AdSense (Mediapartners)
         */
        !preg_match('~^(?:google|rate-limited)-proxy-.*\.google\.com$~i', $HN)
    ) {
        if ($this->trigger(preg_match('~(?<!\w)tor(?!\w)|anonym|makesecure\.nl$|proxy~i', $HN), 'Proxy host')) {
            $this->addProfileEntry('Tor endpoints here');
        } // 2021.03.18
    }

    /** WordPress cronjob bypass. */
    $this->bypass(
        (($this->BlockInfo['SignatureCount'] - $Before) > 0) &&
        preg_match('~^/wp-cron\.php\?doing_wp_cron=\d+\.\d+$~', $this->BlockInfo['rURI']) &&
        defined('DOING_CRON'),
        'WordPress cronjob bypass'
    ); // 2018.06.24

    /** Conjunctive reporting. */
    if (preg_match('~Spoofed/Fake Hostname|Dangerous Host|Questionable Host|DNS error~i', $this->BlockInfo['WhyReason'])) {
        $this->Reporter->report([20], [], $this->BlockInfo['IPAddr']);
    }
    if (preg_match('~(?:VPN|Proxy) Host~i', $this->BlockInfo['WhyReason'])) {
        $this->Reporter->report([9, 13], [], $this->BlockInfo['IPAddr']);
    }

    /** Reporting. */
    if (strpos($this->BlockInfo['WhyReason'], 'Banned hostname') !== false) {
        $this->Reporter->report([15], ['Hack attempt via hostname detected at this address.'], $this->BlockInfo['IPAddr']);
    } elseif (strpos($this->BlockInfo['WhyReason'], 'Hostname script injection') !== false) {
        $this->Reporter->report([15], ['Script injection via hostname detected at this address.'], $this->BlockInfo['IPAddr']);
    } elseif (strpos($this->BlockInfo['WhyReason'], 'CAPTCHA cracker host') !== false) {
        $this->Reporter->report([15], ['CAPTCHA cracker detected at this address.'], $this->BlockInfo['IPAddr']);
    } elseif (strpos($this->BlockInfo['WhyReason'], 'esonicspider') !== false) {
        $this->Reporter->report([21], ['esonicspider detected at this address.'], $this->BlockInfo['IPAddr']);
    }
};

/** Execute closure. */
$this->CIDRAM['ModuleResCache'][$Module]();
