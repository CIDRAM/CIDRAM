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
 * This file: Bot user agents module (last modified: 2026.07.30).
 *
 * False positive risk (an approximate, rough estimate only): « [ ]Low [x]Medium [ ]High »
 */

/** Safety. */
if (!isset($this->CIDRAM['ModuleResCache'])) {
    $this->CIDRAM['ModuleResCache'] = [];
}

/** Defining as closure for later recall (no params; no return value). */
$this->CIDRAM['ModuleResCache'][$Module] = function () {
    /**
     * UA-based signatures start from here (UA = User Agent).
     * Please report all false positives to https://github.com/CIDRAM/CIDRAM/issues
     */
    if (!$this->BlockInfo['UA'] || $this->trigger(\strlen($this->BlockInfo['UA']) > 4096, 'Bad UA', 'User agent string is too long!')) {
        return;
    }

    /** Unmarks for use with reCAPTCHA and hCaptcha. */
    $UnmarkCaptcha = ['recaptcha' => ['usemode' => 0, 'forcibly_disabled' => true], 'hcaptcha' => ['usemode' => 0, 'forcibly_disabled' => true]];

    $UA = \str_replace('\\', '/', \strtolower(\urldecode($this->BlockInfo['UA'])));
    $UANoSpace = \preg_replace('/\s/', '', $UA);

    $this->trigger(\preg_match('/\\((?:["\']{2})?\\)/', $UANoSpace), 'UA command injection'); // 2017.01.02

    $this->trigger(\preg_match(
        '/(?:_once|(?<!st)able|asc|assert|c(?:hr|ode|ontents)|e(?:cho|regi|scape|' .
        'val)|ex(?:ec|ists)?|f(?:ile|late|unction)|get(?:c|csv|ss?)?|if|include|l' .
        'en(?:gth)?|open|p(?:ress|rint(?:f|_r)?|lace|lode|uts)|re(?:ad|place|quir' .
        'e|store)|rot13|start|system|w(?:hil|rit)e)["\':(\[{<$]/',
        $UANoSpace
    ), 'UA command injection'); // 2017.01.20 mod 2025.08.02

    $this->trigger(\preg_match(
        '/\$(?:globals|_(cookie|env|files|get|post|request|se(rver|ssion)))/',
        $UANoSpace
    ), 'UA command injection'); // 2017.01.13

    $this->trigger(\preg_match('/http_(?:cmd|sum)/', $UANoSpace), 'UA command injection'); // 2017.01.02
    $this->trigger(\preg_match('/pa(?:rse_ini_file|ssthru)/', $UANoSpace), 'UA command injection'); // 2017.01.02
    $this->trigger(\preg_match('/rewrite(?:cond|rule)/', $UANoSpace), 'UA command injection'); // 2017.01.02
    $this->trigger(\preg_match('/u(?:nserialize|ploadedfile)/', $UANoSpace), 'UA command injection'); // 2017.01.02
    $this->trigger(\strpos($UANoSpace, 'dotnet_load') !== false, 'UA command injection'); // 2017.01.02
    $this->trigger(\strpos($UANoSpace, 'execcgi') !== false, 'UA command injection'); // 2017.01.02
    $this->trigger(\strpos($UANoSpace, 'move_uploaded_file') !== false, 'UA command injection'); // 2017.01.02
    $this->trigger(\strpos($UANoSpace, 'symlink') !== false, 'UA command injection'); // 2017.01.02
    $this->trigger(\strpos($UANoSpace, 'tmp_name') !== false, 'UA command injection'); // 2017.01.02
    $this->trigger(\strpos($UANoSpace, '_contents') !== false, 'UA command injection'); // 2017.01.02

    $this->trigger(\preg_match('/%(?:0[0-8bcef]|1)/i', $this->BlockInfo['UA']), 'Non-printable characters in UA'); // 2017.01.02

    $this->trigger(\preg_match(
        '/(?:<(\?|body|i?frame|object|script)|(body|i?frame|object|script)>)/',
        $UANoSpace
    ), 'UA script injection'); // 2017.01.08

    if ($this->trigger(\preg_match(
        '/(?:globals|_(cookie|env|files|get|post|request|se(rver|ssion)))\[/',
        $UANoSpace
    ), 'UA global variable hack')) {
        $this->Reporter->report([15], ['Globvar hack detected in user agent.'], $this->BlockInfo['IPAddr']);
    } // 2017.01.13

    $this->trigger(\preg_match('/Y[EI]$/', $this->BlockInfo['UA']), 'Possible/Suspected hack UA'); // 2017.01.06

    $this->trigger(\strpos($UA, 'select ') !== false, 'UASQLi'); // 2017.02.25

    if ($this->trigger(\strpos($UANoSpace, 'captch') !== false, 'CAPTCHA cracker UA', '', $UnmarkCaptcha)) {
        $this->Reporter->report([19], ['CAPTCHA cracker detected.'], $this->BlockInfo['IPAddr']);
    } // 2017.01.08 mod 2021.04.29

    $this->trigger(\preg_match(
        '~(?:^b55|-agent-|auto_?http|bigbrother|cybeye|d(?:(?:iavol|ragoste)a|own' .
        'loaddemon)|e(?:ak01ag9|catch)|i(?:ndylibrary|ntelium)|k(?:angen|mccrew)|' .
        'libwww-pavuk|m(?:o(?:get|zillaxyz)|sie6\.0.*deepnet)|n(?:et(?:ants|combe' .
        'r)|s8/0\.9\.6)|p(?:atchone|aros|entru|lanetwork|robe)|riddler|s(?:asqia|' .
        'ledink|noopy|tingbot)|toata|updown_tester|w(?:hitehataviator|orio)|xirio' .
        '|zmeu)~',
        $UANoSpace
    ), 'Probe UA'); // 2019.03.04
    $this->trigger(\preg_match('/(?: obot|ie 5\.5 compatible browser)/', $UA), 'Probe UA'); // 2017.02.02

    $this->trigger(\preg_match('/[<\[](?:a|link|url)[ =>\]]/', $UA) || \strpos($UANoSpace, 'ruru)') !== false || \preg_match(
        '~^(?:\.?=|bot|java|msie|windows-live-social-object-extractor)|\\((?:java|\w:\d{2,})|/how-|>click|' .
        'a(?:btasty|llsubmitter|velox)|' .
        'b(?:ad-neighborhood|dsm|ea?stiality|iloba|ork-edition|uyessay)|' .
        'c(?:asino|ialis|igar|heap|oursework)|' .
        'deltasone|dissertation|drugs|' .
        'eroti[ck]|' .
        'forex|funbot|' .
        'g(?:abapentin|erifort|inkg?o|uestbook)|' .
        'hentai|honeybee|hrbot|' .
        'in(?:cest|come|vestment)|' .
        'jailbreak|' .
        'kamagra|keylog|' .
        'l(?:axative|esbian|evitra|exap|i(?:ker\.profile|nkback|pitor)|olita|uxury|ycosa\.se)|' .
        'm(?:ail\.ru|e(?:laleuca|nthol)|ixrank|rie8pack)|' .
        'n(?:erdybot|etzcheckbot|eurontin|olvadex)|' .
        'orgasm|outlet|' .
        'p(?:axil|harma|illz|lavix|orn|r0n|ropecia|rosti)|' .
        'reviewsx|rogaine|' .
        's(?:ex[xy]|hemale|ickseo|limy|putnik|tart\.exe|terapred|ynthroid)|' .
        't(?:entacle|[0o]p(?:hack|less|sites))|' .
        'u(?:01-2|nlock)|' .
        'v(?:aluationbot|oilabot|arifort|[1i](?:agra|olation|tol))|' .
        'warifort|' .
        'xanax|' .
        'zdorov~',
        $UANoSpace
    ) || \preg_match('~^go +\d|movable type|msie ?(?:\d{3,}|[2-9]\d|[0-8]\.)| (audit|href|mra |quibids )|\\(build 5339\\)~i', $UA), 'Spam UA'); // 2022.07.09 mod 2025.11.06

    $this->trigger(\preg_match('/[\'"`]\+[\'"`]/', $UANoSpace), 'XSS attack'); // 2017.01.03
    $this->trigger(\strpos($UANoSpace, '`') !== false, 'Execution attempt'); // 2017.01.13

    $this->trigger(\preg_match(
        '/(?:digger|e(?:mail)?collector|email(?:ex|search|spider|siphon)|extract(' .
        '?:ion|or)|iscsystems|microsofturl|oozbot|psycheclone)/',
        $UANoSpace
    ), 'Email harvester'); // 2018.04.23 mod 2022.05.08 (typo)

    $this->trigger(\strpos($UANoSpace, 'email') !== false, 'Possible/Suspected email harvester'); // 2017.01.06 mod 2022.05.08 (typo)

    $this->trigger(\preg_match('/%(?:[01][\da-f]|2[257]|3[ce]|[57][bd]|[7f]f)/', $UANoSpace), 'Bad UA'); // 2017.01.06

    $this->trigger((
        \preg_match('/^[\'"].*[\'"]$/', $UANoSpace) &&
        \strpos($UANoSpace, 'duckduckbot') === false
    ), 'Banned UA'); // 2017.02.02 mod 2021.06.20

    $this->trigger(\preg_match(
        '~^(?:wp-iphone$|\'?test|-|default|foo)|_sitemapper|3mir|' .
        'a(?:boundex|dmantx|dnormcrawler|dvbot|lphaserver|thens|ttache)|' .
        'blekko|blogsnowbot|' .
        'cmscrawler|co(?:ccoc|llect|modo-webinspector-crawler|mpspy)|crawler\.feedback|' .
        'd(?:atacha|igout4uagent|ioscout|kimrepbot|sarobot)|' .
        'easou|exabot|' .
        'f(?:astenterprisecrawler|astlwspider|ind?bot|indlinks|loodgate|r[_-]?crawler)|' .
        'geedo|' .
        'hrcrawler|hubspot|' .
        'i(?:mrbot|ntegromedb|p-?web-?crawler|rcsearch|rgrabber)|' .
        'jadynavebot|komodiabot|linguee|linkpad|' .
        'm(?:ajestic12|agnet|auibot|eanpath|entormate|fibot|ignify|j12)|' .
        'nutch|omgilibot|' .
        'p(?:ackrat|cbrowser|lukkie|surf)|reaper|rsync|' .
        's(?:aidwot|alad|cspider|ees\.co|hai|hellbot|hopproductfinder|[iy]phon|truct\.it|upport\.wordpress\.com|ystemscrawler)|' .
        't(?:est\'?$|akeout|asapspider|weetmeme)|' .
        'user-agent|visaduhoc|vonchimpenfurlr|webtarantula|wolf|' .
        'y(?:acy|isouspider|[ry]spider|unrang|unyun)|zoominfobot~',
        $UANoSpace
    ) || \strpos($UA, '   ') !== false, 'Banned UA'); // 2021.07.08 mod 2026.03.11

    if (!$this->trigger((
        \preg_match('~^python-requests/2\.27~', $UANoSpace) &&
        \preg_match('~admin|config\.php~', $this->BlockInfo['rURI'])
    ), 'Hack attempt')) { // 2022.05.08
        $this->trigger(\preg_match(
            '~c(?:copyright|enturyb|9hilkat|olly)|fetch/|flipboard|googlealerts|grub|' .
            'indeedbot|quick-crawler|scrapinghub|ttd-content|^(?:abot|python-requests' .
            '/|spider)~',
            $UANoSpace
        ), 'Scraper UA'); // 2022.05.11 mod 2025.07.24
    }

    $this->trigger(\preg_match('~^mozila/~', $UANoSpace), 'Hack attempt'); // 2022.05.31

    $this->trigger(\preg_match(
        '~007ac9|200please|360spider|3d-ftp|' .
        'a(?:6-indexer|ccelo|ffinity|ghaven|href|ipbot|naly(?:ticsseo|zer(?!ai))|pp3lewebkit|rtviper|wcheck)|' .
        'b(?:abbar\.tech|acklink|arkrowler|azqux|ender|inlar|itvo|ixo|lex|nf.fr|ogahn|oitho|pimagewalker)|' .
        'c(?:ent(?:iverse|ric)|ityreview|msworldmap|omment|overscout|r4nk|rawl(?:erbotalpha|fire)|razywebcrawler|uriousgeorge|ydral)|' .
        'd(?:ata(?:for|provider)|aylife|ebate|igext|(?:cp|isco|ot|ouban|ownload)bot|otcomdotnet|otnetdotcom|owjones|tsagent)|' .
        'e(?:(?:na|uro|xperi)bot|nvolk|stimatewebstats|vaal|zoom)|' .
        'f(?:dm|etch(?:er.0|or)|ibgen)|' .
        'g(?:alaxydownloads|et(?:download\.ws|ty|url11)|slfbot|umgum|urujibot)|' .
        'i(?:mage(?:.fetcher|walker)|linkscrawler|nagist|ndocom|nfluencebot|track)|jakarta|jike|' .
        'k(?:eywenbot|eywordsearchtool|imengi|kman)|' .
        'l(?:abjs\.pro|arbin|ink(?:dex|walker)|iperhey|(?:t|ush)bot)|' .
        'm(?:ahiti|ahonie|attters|egaindex|iabot|lbot|oreover|ormor|ot-v980|oz\.com|rchrome|ulticrawler)|' .
        'n(?:eofonie|ewsbot|extgensearchbot|ineconnections)|' .
        'o(?:afcrawl|fflinenavigator|odlebot|ptimizer)|' .
        'p(?:age(?:fetch|gett|_verifi)er|agesinventory|ath2|ic(?:grabber|s|tsnapshot|turefinder)|i(?:pl|xmatch|xray)|oe-component-client-|owermarks|rofiler|(?:s|ure)bot|urity)|qqdownload|' .
        'r(?:6_|adian6|ankivabot|ebi-shoveler|everseget|ganalytics|ocketcrawler|ogerbot|sscrawl|ulinki)|' .
        's(?:afeassign|bider|bl[.-]bot|creamingfrog|earchmetricsbot|emrush|eo(?:bulls|eng|hunt|kicks|mon|profiler|stat|tool)|erpstat|istrix|ite(?:bot|intel)|n[iy]per|olomono|pbot|search|webot)|' .
        't(?:-h-u-n|agsdir|ineye|opseo|raumacadx|urnitinbot)|' .
        'u(?:12bot|p(?:downer|ictobot))|' .
        'v(?:agabondo|bseo|isbot|oyager)|' .
        'w(?:arebay|auuu|bsearchbot|eb(?:alta|capture|download|mastercoffee|meup|ripper)|ikio|indows(?:3|seven)|ise-guys|khtmlto|orldbot|otbox)|' .
        'yoofind~',
        $UANoSpace
    ), 'Backlink/SEO/Scraper UA'); // 2022.09.19 mod 2026.06.22

    $this->trigger(\preg_match('~zombiebot~', $UANoSpace), 'Backlink/SEO'); // 2025.07.26

    $this->trigger(\strpos($UANoSpace, 'catch') !== false, 'Risky UA'); // 2017.01.13

    if (isset($this->Shorthand['Proxy:Block'])) {
        $this->trigger(\preg_match('~anonymous(?!ai)|vpngate~', $UANoSpace), 'Proxy UA'); // 2017.01.13 mod 2026.06.22
    }

    $this->trigger(\preg_match(
        '/(?:360se|cncdialer|desktopsmiley|ds_juicyaccess|foxy.1|genieo|hotbar|ic' .
        'afe|magicbrowser|mutant|myway|ootkit|ossproxy|qqpinyinsetup|sicent|simba' .
        'r|tencenttraveler|theworld|wsr-agent|zeus)/',
        $UANoSpace
    ), 'Malware UA'); // 2017.04.23

    $this->trigger(\preg_match(
        '~\.buzz|(?<!amazona)dbot/|(?:\W|^)(?:curl|libwww|perl)(?:\W|$)|#boss#|' .
        '^(?:[aim]$|(?!linkedinbot).*http-?(?:agent|client))|-xpanse|' .
        'a(?:bonti|ccserver|cme.spider|dreview/\d|jbaxy|nthill$|nyevent-http|ppengine)|' .
        'b(?:igbozz|itsight|lackbird|logsearch|logbot|salsa)|' .
        'c(?:astlebot|atexplorador|cleaner|eramic|k=\{\}|lickagy|liqzbot|ms-?checker|ontextad|orporama|ortex/\d|rowsnest|yberpatrol)|' .
        'd(?:eepfield|le_spider|nbcrawler|omainappender|ummyconnection|umprendertree)|' .
        'expanse|' .
        'f(?:lightdeckreportsbot|luid/|orms\.gle)|' .
        'g(?:atheranalyzeprovide|enomecrawler|dnplus|imme60|lobalipv[46]space|ooglebenjojo|tbdfffgtb.?$)|' .
        'i(?:nfrawatch|nternaldummy|nternet(?:census|measurement)|ps-agent|sitwp)|' .
        'k2spider|kemvi|' .
        'l(?:9scan|eak(?:\.info|ix)|exxebot|ivelapbot|wp)|' .
        'm(?:acinroyprivacyauditors|etaintelligence|ultipletimes)|' .
        'n(?:etcraft|ettrapport|icebot|mapscriptingengine|rsbot)|' .
        'ontheinternet|' .
        'p(?:4bot|4load|acrawler|ageglimpse|aloalto(?:company|network)|andalytics|arsijoo|egasusmonitoring|hantomjs|hpcrawl|ingdom|rlog|ython-httpx)|' .
        'r(?:arelyused|obo(?:cop|spider)|yze)|' .
        's(?:/got|can\.lol|can(?:ner|info)|creener|eekport|itedomain|mut|nap(?:preview)?bot|oapclient|ocial(?:ayer|searcher)|oso|pyglass|quider|tormintelcrawler|treetbot|ynapse)|' .
        't(?:omba|weezler|ryghost)|' .
        'urlappendbot|urltest|' .
        'vicibox|' .
        'w(?:asalive|atchmouse|eb(?:-monitoring|bot|masteraid|money|pros|site-info\.net|thumbnail)|hatweb|ikiapiary|ininet|maid\.com|pbot/1\.|sr-agent|wwtype)|' .
        'xenu|xovi|' .
        'zibber|zurichfinancialservices~',
        $UANoSpace
    ) || \preg_match(
        '~^Mozilla/5\.0(?: [a-z]{2,5}/0\..| \(Macintosh; Intel Mac OS X \d+_\d+_\d+\) AppleWebKit/\d+\.\d+\.\d+ \(KHTML, like Gecko\))?$~i',
        $this->BlockInfo['UA']
    ), 'Unauthorised'); // 2023.09.15 mod 2026.06.20

    if ($this->trigger(\preg_match('~ivre-|masscan~', $UANoSpace), 'Port scanner and synflood tool detected')) {
        $this->Reporter->report([14, 15, 19], ['MASSCAN port scanner and synflood tool detected.'], $this->BlockInfo['IPAddr']);
    } // 2024.07.28

    $this->trigger(\preg_match('/(?:internet explorer)/', $UA), 'Hostile / Fake IE'); // 2017.02.03

    $this->trigger(\preg_match('~opera/[0-8]\.~', $UA), 'Bot UA'); // 2017.02.25
    $this->trigger(\strpos($UA, 'http://www.mozilla/') !== false, 'Abusive UA'); // 2017.02.25
    $this->trigger(\strpos($UA, 'movabletype/3.3') !== false, 'Bot UA'); // 2017.02.25
    $this->trigger(\strpos($UA, 'mozilla 4.0') !== false, 'Bot UA'); // 2017.02.25
    $this->trigger(\strpos($UA, 'mozilla/0.') !== false, 'Bot UA'); // 2017.02.25
    $this->trigger(\strpos($UA, 'mozilla/1.') !== false, 'Bot UA'); // 2017.02.25
    $this->trigger(\strpos($UA, 'mozilla/2.0 (compatible; ask/teoma)') !== false, 'Bot UA'); // 2017.02.25
    $this->trigger(\strpos($UA, 'mozilla/3.0 (compatible;)') !== false, 'Bot UA'); // 2017.02.25
    $this->trigger(\strpos($UA, 'mozilla/4.0 (compatible; ics 1.2.105)') !== false, 'Bot UA'); // 2017.02.25
    $this->trigger(\strpos($UA, 'mozilla/4.0 (compatible; msie 6.0; windows xp)') !== false, 'Bad UA'); // 2017.02.25
    $this->trigger(\strpos($UA, 'mozilla/4.0+(compatible;+') !== false, 'Bot UA'); // 2017.02.25
    $this->trigger(\strpos($UA, 'mozilla/4.76 [ru] (x11; U; sunos 5.7 sun4u)') !== false, 'Bot UA'); // 2017.02.25
    $this->trigger(\strpos($UA, 'php /') !== false, 'Bot UA'); // 2017.02.25
    $this->trigger($UANoSpace === 'chorme', 'Bot UA'); // 2021.04.16
    $this->trigger(\strpos($UA, '\(windows nt 10.0\; win64\; x64\)') !== false || \strpos($UA, '\(khtml, like gecko\)') !== false, 'Bot UA'); // 2023.09.08
    $this->trigger(\substr($this->BlockInfo['UA'], 0, 2) === '\x', 'Bot UA'); // 2023.10.15
    $this->trigger(\strpos($UA, ';;') !== false, 'Bot UA'); // 2024.06.11

    $this->trigger(\preg_match(
        '/(?:drop ?table|(_table|assert|co(de|ntents)|dotnet_load|e(cho|regi' .
        '|scape|val|x(ec(utable)?|ists)?)|f(ile|unction)|g(et(c(sv)?|ss?)|zi' .
        'nflate)|if|[ints]able|nt|open|p(lace|uts)|re(ad|store)|s(chema|tart' .
        '|ystem)|thru|un(ction|serialize)|w(hil|rit)e)\\(|database\\(\\))/',
        $UA
    ), 'UAEX'); // 2017.02.02

    $this->trigger(\preg_match('~(?:[./]seo|seo/)~', $UANoSpace), 'SEO UA'); // 2018.07.10

    if ($this->trigger(\strpos($UA, 'bittorrent') !== false, 'Bad context (not a bittorrent hub)')) {
        $this->Reporter->report([4, 19], ['BitTorrent user agent seen at HTTP server endpoint (possible flood/DDoS attempt).'], $this->BlockInfo['IPAddr']);
    } // 2017.02.25

    if ($this->trigger(\preg_match(
        '~authorizedsecurity|foregenix|modat|nuclei|isscyberrisk|projectdiscovery|securityscanner|sslyze|threatview~',
        $UANoSpace
    ), 'Unauthorised vulnerability scanner detected')) {
        $this->Reporter->report([15, 19, 21], ['Unauthorised vulnerability scanner detected.'], $this->BlockInfo['IPAddr']);
        $this->CIDRAM['Tracking options override'] = 'extended';
    } // 2023.06.16 mod 2025.07.27

    $this->trigger(\preg_match('~^python/|aiohttp/|\.post0~', $UANoSpace), 'Bad context (Python/AIO clients not permitted here)'); // 2021.05.18

    /**
     * @link https://gist.github.com/paralax/6de9968e989c292781b2df167a1fb4ce
     */
    if ($this->trigger(\strpos($UANoSpace, 'gbrmss/') !== false, 'Gebriano webshell detected')) {
        $this->Reporter->report([15, 19, 20, 21], ['Gebriano webshell detected here.'], $this->BlockInfo['IPAddr']);
    } // 2022.02.23

    /**
     * @link https://isc.sans.edu/forums/diary/MGLNDD+Scans/28458/
     */
    if ($this->trigger(\preg_match('~^MGLNDD_~i', $UANoSpace), 'Attempting to expose honeypots')) {
        $this->Reporter->report([21], ['Caught attempting to expose honeypot via reporting mechanism.'], $this->BlockInfo['IPAddr']);
    } // 2022.05.08

    if ($this->trigger(\preg_match(
        '~^(?:curlmozilla|http_get)|\(gort\)|[-.]ai|2bone|80legs|' .
        'a(?:dbar|gent(?:3|api|ic|ql)|i.?(?:2|agent|article|assistant|bot|chat|content|detection|dungeon|hitbot|journalist|legion|matrix|rag|research|search|seocrawler|training|web|writer)|liyun|lphaai|nalyzerai|ndibot|nonymous-?(?:ai|coward)|riaai|skai|uto(?:nomous)?rag|wario|xios)|' .
        'b(?:anana-?bot|asicrag|edrockbot|ot-?test|rands-?bot|rightbot|rings_?you|ytespider)|' .
        'c(?:arynai|asperbot|cbot|harstar|hinaclaw|lark-?crawler|ognitive|ohere-|ommoncrawl|ontentsamurai|onversionai|opyai|orrectiverag|rawl[4q]ai|rawler4j|rewai|rushonai)|' .
        'd(?:atenbank|eep-?(?:ai|crawl|index|l|mind|(?:re)?search|seek)|iffbot|oubaoai)|' .
        'echobo[tx]|' .
        'f(?:idget-?spinner-?bot|irecrawl|lyriver|raseai|riendly-?(?:crawler|spider))|' .
        'genai|' .
        'h(?:arvest|eritrix|tt(?:pfetcher|punit|rack)|ybrid(?:search)?rag|ypotenuse)|' .
        'i(?:askspider|magesift|mg2dataset|p_address)|' .
        'j(?:addjabot|anitorai|enniai|uliusai)|' .
        'k(?:afkai|angaroobot|eys-?so-?bot|eyworddensity)|' .
        'l(?:9explore|anguageai|ightrag|ink(?:check|fluence)|ocalrag)|' .
        'm(?:amac(?:asper|yber)|bzuai|etaai|i[sx]tral|odel[_-]?training|ozilla/0|ycentralai)|' .
        'n(?:etestate|injaai|ovaact)|' .
        'o(?:mgili|pen(?:agi|bot|interpreter|pi|router|textai)|rbbot)|' .
        'p(?:angubot|anscient|erflexity|erplexity|hindbot|hxbot|lease_?block|oseidon|roximic|ublicwebcrawler|ythonai)|' .
        'q(?:opywriter|ualifiedbot|uillbot)|' .
        'r(?:ag(?:[-_]|agent|azure|chat|data|is|pipe|search|with)|esearch.?crawler)|' .
        's(?:aplingai|bintuition|crap[ey]|idetrade|implifiedai|p(?:hi|y)der|tablediffusion|tealth|torm-?crawler|ummalybot|urferai)|' .
        't(?:erracotta|est[-_]?(?:bot|phase)|heknowledgeai|hesis-?research-?bot|hink(?:bot|chaos)|impi|iny-?(?:bot|test)|rafilatura)|' .
        'v(?:elenpublic|enuschub|idnami|isionrag)|' .
        'w(?:ardbot|ebsite[-_]?scraper|ebzio|hatstuffwherebot|inhttp|ordai)|' .
        'x(?:ai|tractorpro)|' .
        'yak/|' .
        'z(?:ephuli-?bot|grab|huqueai)~',
        $UANoSpace
    ), 'Scraper UA')) {
        $this->CIDRAM['Tracking options override'] = 'extended';
    } // 2023.11.17 mod 2026.07.30

    /**
     * @link https://github.com/CIDRAM/CIDRAM/issues/651
     */
    $this->trigger(\preg_match(
        '~(?=.*chrome\/\d)(?=.*safari\/\d)(?=.*edg\/\d).*gls\/[\d.]+$|' .
        '(?=.*chrome\/\d)(?=.*safari\/\d).*(?:unique|trailer|agency|viewer)\/[\d.]+$|' .
        '(?=.*gecko\/\d)(?=.*firefox\/\d).*(?:openwave|config)\/[\d.]+$~',
        $UANoSpace
    ), 'Scraper UA'); // 2025.12.02

    /**
     * Requests with this UA are most likely stealth attempts by Perplexity.
     * Also a legitimate UA for Chrome v124 running on MacOS v10 (Catalina),
     * but as Chrome v124 was released April 2024 (now more than two years
     * ago), and Chrome auto-updates, the chances of encountering a
     * legitimate instance of it is fairly small.
     *
     * @link https://blog.cloudflare.com/perplexity-is-using-stealth-undeclared-crawlers-to-evade-website-no-crawl-directives/
     */
    $this->trigger(\preg_match('~^Mozilla/5\.0 \(Macintosh; Intel Mac OS X 10_15_7\) AppleWebKit/537\.36 \(KHTML, like Gecko\) Chrome/124(?:\.0){3} Safari/537\.36$~i', $this->BlockInfo['UA']), 'Scraper UA'); // 2026.05.18

    $this->trigger(\preg_match('~ct‑git‑scanner/~i', $this->BlockInfo['UA']), 'Unauthorised Git scanner'); // 2025.07.05
    $this->trigger(\preg_match('~4\.066686748~', $UANoSpace), 'Hack UA (pretending to be Netscape)'); // 2025.11.13
    $this->trigger(\preg_match('~httpxdiscovery~', $UANoSpace), 'Hack UA'); // 2026.02.22

    /** These signatures can set extended tracking options. */
    if (
        $this->trigger(\strpos($UANoSpace, '$_' . '[$' . '__') !== false, 'UA shell upload attempt') || // 2017.01.02
        $this->trigger(\strpos($UANoSpace, '@$' . '_[' . ']=' . '@!' . '+_') !== false, 'UA shell upload attempt') || // 2017.01.02
        $this->trigger(\preg_match('/h[4a]c' . 'k(?:e[dr]|ing|t([3e][4a]m|[0o]{2}l))/', $UANoSpace), 'Hack UA') || // 2017.01.06
        $this->trigger(\strpos($UANoSpace, 'alittleclient') !== false, 'Hack UA') || // 2023.04.20
        $this->trigger((
            \strpos($UA, 'rm ' . '-rf') !== false ||
            \strpos($UA, 'wordpress ha') !== false ||
            \strpos($UANoSpace, '\0\0\0') !== false ||
            \strpos($UANoSpace, 'cha0s') !== false ||
            \strpos($UANoSpace, 'fhscan') !== false ||
            \strpos($UANoSpace, 'havij') !== false ||
            \strpos($UANoSpace, 'if(') !== false ||
            \strpos($UANoSpace, 'jdatabasedrivermysqli') !== false ||
            \strpos($UANoSpace, 'morfeus') !== false ||
            \strpos($UANoSpace, 'r0' . '0t') !== false ||
            \strpos($UANoSpace, 'sh' . 'el' . 'l_' . 'ex' . 'ec') !== false ||
            \strpos($UANoSpace, 'urldumper') !== false ||
            \strpos($UANoSpace, 'whcc/') !== false ||
            \strpos($UANoSpace, 'xmlset_roodkcable') !== false ||
            \strpos($UANoSpace, 'zollard') !== false ||
            \strpos($UANoSpace, '}__') !== false ||
            \preg_match('~0wn[3e]d|dkemdif.\d|f' . 'uck|:(?:\{[\w]:|[\w\d][;:]\})~', $UANoSpace)
        ), 'Hack UA') || // 2021.06.28
        $this->trigger(\strpos($UANoSpace, 'wopbot') !== false, 'Bash/Shellshock UA') || // 2017.01.06
        $this->trigger(\preg_match('/(?:x(rumer|pymep)|хрумер)/', $UANoSpace), 'Spam UA') || // 2017.01.02
        $this->trigger(\preg_match('~loadimpact|re-?animator|root|webster~', $UANoSpace), 'Banned UA') || // 2021.02.10 mod 2025.07.24
        $this->trigger(\strpos($UANoSpace, '(somename)') !== false, 'Banned UA') || // 2017.02.02
        $this->trigger(\preg_match('~brandwatch|magpie~', $UANoSpace), 'Snoop UA') || // 2017.01.13 mod 2021.06.28
        $this->trigger(\strpos($this->BlockInfo['UA'], 'MSIECrawler') !== false, 'Hostile / Fake IE') // 2017.02.25 mod 2021.06.28
    ) {
        $this->CIDRAM['Tracking options override'] = 'extended';
    }

    /** Reporting. */
    if (!empty($this->BlockInfo['IPAddr'])) {
        if (\strpos($this->BlockInfo['WhyReason'], 'Bot UA') !== false) {
            $this->Reporter->report([19], ['Bad web bot detected.'], $this->BlockInfo['IPAddr']);
        }

        if (\strpos($this->BlockInfo['WhyReason'], 'Spam UA') !== false) {
            $this->Reporter->report([12, 19], ['Spambot detected.'], $this->BlockInfo['IPAddr']);
        } elseif (\strpos($this->BlockInfo['WhyReason'], 'Malware UA') !== false) {
            $this->Reporter->report([19, 20], ['User agent cited by malware detected.'], $this->BlockInfo['IPAddr']);
        } elseif (\strpos($this->BlockInfo['WhyReason'], 'UAEX') !== false) {
            $this->Reporter->report([15, 19], ['Detected command execution via user agent header.'], $this->BlockInfo['IPAddr']);
        } elseif (\strpos($this->BlockInfo['WhyReason'], 'UA command injection') !== false) {
            $this->Reporter->report([15], ['Command injection detected in user agent.'], $this->BlockInfo['IPAddr']);
        } elseif (\strpos($this->BlockInfo['WhyReason'], 'UA script injection') !== false) {
            $this->Reporter->report([15], ['Script injection detected in user agent.'], $this->BlockInfo['IPAddr']);
        } elseif (\strpos($this->BlockInfo['WhyReason'], 'UA shell upload attempt') !== false) {
            $this->Reporter->report([15], ['Shell upload attempt detected in user agent.'], $this->BlockInfo['IPAddr']);
        } elseif (\strpos($this->BlockInfo['WhyReason'], 'Hack UA') !== false) {
            $this->Reporter->report([15, 19, 21], ['Hack identifier detected in user agent.'], $this->BlockInfo['IPAddr']);
        } elseif (\strpos($this->BlockInfo['WhyReason'], 'UASQLi') !== false) {
            $this->Reporter->report([16], ['SQLi attempt detected in user agent.'], $this->BlockInfo['IPAddr']);
        } elseif (\strpos($this->BlockInfo['WhyReason'], 'Probe UA') !== false) {
            $this->Reporter->report([19], ['Probe detected.'], $this->BlockInfo['IPAddr']);
        } elseif (\strpos($this->BlockInfo['WhyReason'], 'Bash/Shellshock UA') !== false) {
            $this->Reporter->report([15], ['Bash/Shellshock attempt detected via user agent.'], $this->BlockInfo['IPAddr']);
        } elseif (\strpos($this->BlockInfo['WhyReason'], 'Email harvester') !== false) {
            $this->Reporter->report([19], ['Email harvester detected.'], $this->BlockInfo['IPAddr']);
        } elseif (\strpos($this->BlockInfo['WhyReason'], 'Execution attempt') !== false) {
            $this->Reporter->report([15], ['Attempted to push shell commands via user agent header.'], $this->BlockInfo['IPAddr']);
        } elseif (\strpos($this->BlockInfo['WhyReason'], 'XSS attack') !== false) {
            $this->Reporter->report([15], ['Attempted to push XSS via user agent header.'], $this->BlockInfo['IPAddr']);
        } elseif (\strpos($this->BlockInfo['WhyReason'], 'Banned UA') !== false) {
            $this->Reporter->report([19], ['Misbehaving bot detected.'], $this->BlockInfo['IPAddr']);
        } elseif (\strpos($this->BlockInfo['WhyReason'], 'Scraper UA') !== false) {
            $this->Reporter->report([19], ['Scraper detected.'], $this->BlockInfo['IPAddr']);
        } elseif (\strpos($this->BlockInfo['WhyReason'], 'Hack attempt') !== false) {
            $this->Reporter->report([15, 19, 21], ['Hack attempt detected.'], $this->BlockInfo['IPAddr']);
        }
    }

    /**
     * @link https://github.com/CIDRAM/CIDRAM/issues/493
     * @link https://github.com/CIDRAM/CIDRAM/issues/557
     * @link https://github.com/CIDRAM/CIDRAM/issues/588
     * @link https://trunc.org/learning/the-mozlila-user-agent-bot
     */
    if (
        $this->trigger(\strpos($UANoSpace, 'mozlila') !== false || \strpos($UANoSpace, 'moblie') !== false || $UANoSpace === 'mozila/5.0', 'Attack UA') // 2023.08.10 mod 2024.05.07
    ) {
        $this->Reporter->report([15, 19, 20, 21], ['User agent cited by various attack tools, rootkits, backdoors, webshells, and malware detected.'], $this->BlockInfo['IPAddr']);
        $this->CIDRAM['Tracking options override'] = 'extended';
    }

    /**
     * @link https://github.com/CIDRAM/CIDRAM/issues/494
     * @link https://www.reddit.com/r/singularity/comments/1cdm97j/anthropics_claudebot_is_aggressively_scraping_the/
     * @link https://www.linode.com/community/questions/24842/ddos-from-anthropic-ai
     */
    if ($this->trigger(\preg_match('~anthropic|claude-?(?:bot|searchbot|user|web)~', $UANoSpace), 'Unauthorised AI scanner')) {
        $this->Reporter->report([4, 19], ['AI scanner notorious for flooding and DDoS attacks detected.'], $this->BlockInfo['IPAddr']);
        $this->CIDRAM['Tracking options override'] = 'extended';
    } // 2023.08.10 mod 2025.07.24

    /**
     * @link https://github.com/CIDRAM/CIDRAM/issues/606
     * @link https://nsfocusglobal.com/ai-supply-chain-security-hugging-face-malicious-ml-models/
     * @link https://www.darkreading.com/application-security/hugging-face-ai-platform-100-malicious-code-execution-models
     * @link https://vulcan.io/blog/understanding-the-hugging-face-backdoor-threat/
     */
    if ($this->trigger(\preg_match('~datasets/|hugging.*face|_hub.*(?:pyarrow|torch)~', $UANoSpace), 'Potential supply chain attack')) {
        $this->Reporter->report([4, 15, 19, 20], ['Huggingface detected (potential ML-based supply chain attack vector; caught flooding, scraping, and performing DDoS attacks).'], $this->BlockInfo['IPAddr']);
        $this->CIDRAM['Tracking options override'] = 'extended';
    } // 2024.06.27

    if ($this->trigger(\strpos($UANoSpace, 'getodin.com') !== false, 'Unauthorised')) {
        $this->Reporter->report([15, 19, 23], ['Strange bot caught probing for vulnerable routers and webservices detected.'], $this->BlockInfo['IPAddr']);
    } // 2024.07.07
};

/** Execute closure. */
$this->CIDRAM['ModuleResCache'][$Module]();
