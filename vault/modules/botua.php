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
 * This file: Bot user agents module (last modified: 2024.05.03).
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
    if (!$this->BlockInfo['UA'] || $this->trigger(strlen($this->BlockInfo['UA']) > 4096, 'Bad UA', 'User agent string is too long!')) {
        return;
    }

    /** Unmarks for use with reCAPTCHA and hCAPTCHA. */
    $UnmarkCaptcha = ['recaptcha' => ['enabled' => false], 'hcaptcha' => ['enabled' => false]];

    $UA = str_replace('\\', '/', strtolower(urldecode($this->BlockInfo['UA'])));
    $UANoSpace = preg_replace('/\s/', '', $UA);

    $this->trigger(preg_match('/\\((?:["\']{2})?\\)/', $UANoSpace), 'UA command injection'); // 2017.01.02

    $this->trigger(preg_match(
        '/(?:_once|able|as(c|hes|sert)|c(hr|ode|ontents)|e(cho|regi|scape|val)|ex' .
        '(ec|ists)?|f(ile|late|unction)|get(c|csv|ss?)?|i(f|nclude)|len(gth)?|ope' .
        'n|p(ress|lace|lode|uts)|print(f|_r)?|re(ad|place|quire|store)|rot13|s(ta' .
        'rt|ystem)|w(hil|rit)e)["\':(\[{<$]/',
        $UANoSpace
    ), 'UA command injection'); // 2017.01.20

    $this->trigger(preg_match(
        '/\$(?:globals|_(cookie|env|files|get|post|request|se(rver|ssion)))/',
        $UANoSpace
    ), 'UA command injection'); // 2017.01.13

    $this->trigger(preg_match('/http_(?:cmd|sum)/', $UANoSpace), 'UA command injection'); // 2017.01.02
    $this->trigger(preg_match('/pa(?:rse_ini_file|ssthru)/', $UANoSpace), 'UA command injection'); // 2017.01.02
    $this->trigger(preg_match('/rewrite(?:cond|rule)/', $UANoSpace), 'UA command injection'); // 2017.01.02
    $this->trigger(preg_match('/u(?:nserialize|ploadedfile)/', $UANoSpace), 'UA command injection'); // 2017.01.02
    $this->trigger(strpos($UANoSpace, 'dotnet_load') !== false, 'UA command injection'); // 2017.01.02
    $this->trigger(strpos($UANoSpace, 'execcgi') !== false, 'UA command injection'); // 2017.01.02
    $this->trigger(strpos($UANoSpace, 'move_uploaded_file') !== false, 'UA command injection'); // 2017.01.02
    $this->trigger(strpos($UANoSpace, 'symlink') !== false, 'UA command injection'); // 2017.01.02
    $this->trigger(strpos($UANoSpace, 'tmp_name') !== false, 'UA command injection'); // 2017.01.02
    $this->trigger(strpos($UANoSpace, '_contents') !== false, 'UA command injection'); // 2017.01.02

    $this->trigger(preg_match('/%(?:0[0-8bcef]|1)/i', $this->BlockInfo['UA']), 'Non-printable characters in UA'); // 2017.01.02

    $this->trigger(preg_match(
        '/(?:<(\?|body|i?frame|object|script)|(body|i?frame|object|script)>)/',
        $UANoSpace
    ), 'UA script injection'); // 2017.01.08

    $this->trigger(preg_match(
        '/(?:globals|_(cookie|env|files|get|post|request|se(rver|ssion)))\[/',
        $UANoSpace
    ), 'UA global variable hack'); // 2017.01.13

    $this->trigger(preg_match('/Y[EI]$/', $this->BlockInfo['UA']), 'Possible/Suspected hack UA'); // 2017.01.06

    $this->trigger(strpos($UA, 'select ') !== false, 'UASQLi'); // 2017.02.25

    $this->trigger(strpos($UANoSpace, 'captch') !== false, 'CAPTCHA cracker UA', '', $UnmarkCaptcha); // 2017.01.08 mod 2021.04.29


    $this->trigger(preg_match(
        '~(?:^b55|-agent-|auto_?http|bigbrother|cybeye|d(?:(?:iavol|ragoste)a|own' .
        'loaddemon)|e(?:ak01ag9|catch)|i(?:ndylibrary|ntelium)|k(?:angen|mccrew)|' .
        'libwww-pavuk|m(?:o(?:get|zillaxyz)|sie6\.0.*deepnet)|n(?:et(?:ants|combe' .
        'r)|s8/0\.9\.6)|p(?:atchone|aros|entru|lanetwork|robe)|riddler|s(?:asqia|' .
        'ledink|noopy|tingbot)|toata|updown_tester|w(?:hitehataviator|orio)|xirio' .
        '|zmeu)~',
        $UANoSpace
    ), 'Probe UA'); // 2019.03.04
    $this->trigger(preg_match('/(?: obot|ie 5\.5 compatible browser)/', $UA), 'Probe UA'); // 2017.02.02

    $this->trigger(preg_match('/[<\[](?:a|link|url)[ =>\]]/', $UA), 'Spam UA'); // 2017.01.02
    $this->trigger(preg_match('/^\.?=/', $UANoSpace), 'Spam UA'); // 2017.01.07
    $this->trigger(strpos($UANoSpace, '/how-') !== false, 'Spam UA'); // 2017.01.04
    $this->trigger(strpos($UANoSpace, '>click') !== false, 'Spam UA'); // 2017.01.04
    $this->trigger(strpos($UANoSpace, 'ruru)') !== false, 'Spam UA'); // 2017.01.07

    $this->trigger(preg_match(
        '~a(?:btasty|llsubmitter|velox)|' .
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
        'l(?:axative|esbian|evitra|exap|i(?:ker\.profile|nk(?:ba|che)ck|pitor)|olita|uxury|ycosa\.se)|' .
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
    ), 'Spam UA'); // 2022.07.09

    $this->trigger(preg_match(
        '/(?: (audit|href|mra |quibids )|\\(build 5339\\))/',
        $UA
    ), 'Spam UA'); // 2017.02.02

    $this->trigger(preg_match('/[\'"`]\+[\'"`]/', $UANoSpace), 'XSS attack'); // 2017.01.03
    $this->trigger(strpos($UANoSpace, '`') !== false, 'Execution attempt'); // 2017.01.13

    $this->trigger(preg_match(
        '/(?:digger|e(?:mail)?collector|email(?:ex|search|spider|siphon)|extract(' .
        '?:ion|or)|iscsystems|microsofturl|oozbot|psycheclone)/',
        $UANoSpace
    ), 'Email harvester'); // 2018.04.23 mod 2022.05.08 (typo)

    $this->trigger(strpos($UANoSpace, 'email') !== false, 'Possible/Suspected email harvester'); // 2017.01.06 mod 2022.05.08 (typo)

    $this->trigger(preg_match('/%(?:[01][\da-f]|2[257]|3[ce]|[57][bd]|[7f]f)/', $UANoSpace), 'Bad UA'); // 2017.01.06

    $this->trigger(preg_match('/test\'?$/', $UANoSpace), 'Banned UA'); // 2017.02.02
    $this->trigger(preg_match('/^(?:\'?test|-|default|foo)/', $UANoSpace), 'Banned UA'); // 2017.02.02
    $this->trigger(strpos($UA, '   ') !== false, 'Banned UA'); // 2017.02.02

    $this->trigger((
        preg_match('/^[\'"].*[\'"]$/', $UANoSpace) &&
        strpos($UANoSpace, 'duckduckbot') === false
    ), 'Banned UA'); // 2017.02.02 mod 2021.06.20

    $this->trigger(preg_match(
        '~_sitemapper|3mir|a(?:boundex|dmantx|dnormcrawler|dvbot|lphaserver|thens' .
        '|ttache)|blekko|blogsnowbot|bytespider|cmscrawler|co(?:ccoc|llect|modo-w' .
        'ebinspector-crawler|mpspy)|crawler(?:4j|\.feedback)|d(?:atacha|igout4uag' .
        'ent|ioscout|kimrepbot|sarobot)|easou|exabot|f(?:astenterprisecrawler|ast' .
        'lwspider|ind?bot|indlinks|loodgate|r[_-]?crawler)|hrcrawler|hubspot|i(?:' .
        'mrbot|ntegromedb|p-?web-?crawler|rcsearch|rgrabber)|jadynavebot|komodiab' .
        'ot|linguee|linkpad|m(?:ajestic12|agnet|auibot|eanpath|entormate|fibot|ig' .
        'nify|j12)|nutch|omgilibot|p(?:ackrat|cbrowser|lukkie|surf)|reaper|rsync|' .
        's(?:aidwot|alad|cspider|ees\.co|hai|[iy]phon|truct\.it|upport\.wordpress' .
        '\.com|ystemscrawler)|takeout|tasapspider|tweetmeme|user-agent|visaduhoc|' .
        'vonchimpenfurlr|webtarantula|wolf|y(?:acy|isouspider|[ry]spider|unrang|u' .
        'nyun)|zoominfobot~',
        $UANoSpace
    ), 'Banned UA'); // 2021.07.08 mod 2023.11.10

    $this->trigger(preg_match(
        '/^wp-iphone$/',
        $UANoSpace
    ), 'Banned UA'); // 2017.12.14

    if (!$this->trigger((
        preg_match('~^python-requests/2\.27~', $UANoSpace) &&
        preg_match('~admin|config\.php~', $this->BlockInfo['rURI'])
    ), 'Hack attempt')) { // 2022.05.08
        $this->trigger(preg_match(
            '~c(?:copyright|enturyb|9hilkat|olly)|fetch/|flipboard|googlealerts|grub|' .
            'indeedbot|quick-crawler|scrapinghub|ttd-content|zgrab|^(?:abot|python-re' .
            'quests/|spider)~',
            $UANoSpace
        ), 'Scraper UA'); // 2022.05.11
    }

    $this->trigger(preg_match('~^mozila/~', $UANoSpace), 'Hack attempt'); // 2022.05.31

    $this->trigger(preg_match(
        '~007ac9|200please|360spider|3d-ftp|' .
        'a(?:6-indexer|ccelo|ffinity|ghaven|href|ipbot|naly(?:ticsseo|zer)|pp3lewebkit|rtviper|wcheck)|' .
        'b(?:acklink|azqux|ender|inlar|itvo|ixo|lex|nf.fr|ogahn|oitho|pimagewalker)|' .
        'c(?:cbot|ent(?:iverse|ric)|ityreview|msworldmap|omment|ommoncrawl|overscout|r4nk|rawl(?:erbotalpha|fire)|razywebcrawler|uriousgeorge|ydral)|' .
        'd(?:ataprovider|atenbank|aylife|ebate|igext|(?:cp|isco|ot|ouban|ownload)bot|otcomdotnet|otnetdotcom|owjones|tsagent)|' .
        'e(?:(?:na|uro|xperi)bot|nvolk|stimatewebstats|vaal|zoom)|' .
        'f(?:dm|etch(?:er.0|or)|ibgen)|' .
        'g(?:alaxydownloads|et(?:download\.ws|ty|url11)|slfbot|umgum|urujibot)|' .
        'h(?:arvest|eritrix|olmes|ttp(?:fetcher|unit)|ttrack)|' .
        'i(?:mage(?:.fetcher|walker)|linkscrawler|nagist|ndocom|nfluencebot|track)|jakarta|jike|' .
        'k(?:eywenbot|eywordsearchtool|imengi|kman)|' .
        'l(?:abjs\.pro|arbin|ink(?:dex|walker)|iperhey|(?:t|ush)bot)|' .
        'm(?:ahiti|ahonie|attters|egaindex|iabot|lbot|oreover|ormor|ot-v980|oz\.com|rchrome|ulticrawler)|' .
        'n(?:eofonie|etestate|ewsbot|extgensearchbot|ineconnections)|' .
        'o(?:afcrawl|fflinenavigator|odlebot|ptimizer)|' .
        'p(?:age(?:fetch|gett|_verifi)er|agesinventory|anscient|ath2|ic(?:grabber|s|tsnapshot|turefinder)|i(?:pl|xmatch|xray)|oe-component-client-|owermarks|rofiler|roximic|(?:s|ure)bot|urity)|qqdownload|' .
        'r(?:6_|adian6|ankivabot|ebi-shoveler|everseget|ganalytics|ocketcrawler|ogerbot|sscrawl|ulinki)|' .
        's(?:afeassign|bider|bl[.-]bot|crap[ey]|creamingfrog|earchmetricsbot|emrush|eo(?:bulls|eng|hunt|kicks|mon|profiler|stat|tool)|erpstat|istrix|ite(?:bot|intel)|n[iy]per|olomono|pbot|p(?:hi|y)der|search|webot)|' .
        't(?:-h-u-n|agsdir|ineye|opseo|raumacadx|urnitinbot)|' .
        'u(?:12bot|p(?:downer|ictobot))|' .
        'v(?:agabondo|bseo|isbot|oyager)|' .
        'w(?:arebay|auuu|bsearchbot|eb(?:alta|capture|download|mastercoffee|meup|ripper)|ikio|indows(?:3|seven)|inhttp|ise-guys|khtmlto|orldbot|otbox)|' .
        'xtractorpro|' .
        'yoofind~',
        $UANoSpace
    ), 'Backlink/SEO/Scraper UA'); // 2022.09.19

    $this->trigger(strpos($UANoSpace, 'catch') !== false, 'Risky UA'); // 2017.01.13

    if (isset($this->Shorthand['Proxy:Block'])) {
        $this->trigger((strpos($UANoSpace, 'anonymous') !== false || strpos($UANoSpace, 'vpngate') !== false), 'Proxy UA'); // 2017.01.13 mod 2021.05.18
    }

    $this->trigger(preg_match(
        '/(?:360se|cncdialer|desktopsmiley|ds_juicyaccess|foxy.1|genieo|hotbar|ic' .
        'afe|magicbrowser|mutant|myway|ootkit|ossproxy|qqpinyinsetup|sicent|simba' .
        'r|tencenttraveler|theworld|wsr-agent|zeus)/',
        $UANoSpace
    ), 'Malware UA'); // 2017.04.23

    $this->trigger(preg_match(
        '~\.buzz|(?<!amazona)dbot/|^m$|(?:\W|^)(?:cu|pe)rl(?:\W|$)|^(?!linkedinbot).*http-?(?:agent|client)|#boss#|' .
        'a(?:bonti|ccserver|cme.spider|dreview/\d|jbaxy|nyevent-http|ppengine)|' .
        'b(?:abbar\.tech|igbozz|lackbird|logsearch|logbot|salsa)|' .
        'c(?:astlebot|atexplorador|lickagy|liqzbot|ontextad|orporama|ortex/\d|rowsnest|yberpatrol)|' .
        'd(?:eepfield|le_spider|nbcrawler|omainappender|umprendertree)|' .
        'expanse|' .
        'f(?:lightdeckreportsbot|luid/|orms\.gle)|' .
        'g(?:atheranalyzeprovide|enomecrawler|dnplus|imme60|lobalipv[46]space|ooglebenjojo|tbdfffgtb.?$)|' .
        'infrawatch|internetcensus|ips-agent|isitwp|' .
        'k2spider|kemvi|' .
        'leak\.info|lexxebot|livelapbot|lwp|' .
        'm(?:acinroyprivacyauditors|asscan|etaintelligence|ultipletimes)|' .
        'n(?:etcraft|ettrapport|icebot|mapscriptingengine|rsbot)|' .
        'ontheinternet|' .
        'p(?:4bot|4load|acrawler|ageglimpse|aloalto(?:company|network)|arsijoo|egasusmonitoring|hantomjs|hpcrawl|ingdom|rlog)|' .
        'r(?:arelyused|obo(?:cop|spider)|yze)|' .
        's(?:can\.lol|caninfo|creener|eekport|itedomain|mut|nap(?:preview)?bot|oapclient|ocial(?:ayer|searcher)|oso|pyglass|quider|treetbot|ynapse)|' .
        't(?:impi|omba|weezler)|' .
        'urlappendbot|urltest|' .
        'w(?:asalive|atchmouse|eb(?:-monitoring|bot|masteraid|money|pros|thumbnail)|hatweb|ikiapiary|in(?:http|inet)|maid\.com|pbot/1\.|sr-agent|wwtype)|' .
        'xenu|xovi|' .
        'zibber|zurichfinancialservices~',
        $UANoSpace
    ) || preg_match(
        '~^Mozilla/5\.0( [A-Za-z]{2,5}/0\..)?$~',
        $this->BlockInfo['UA']
    ), 'Unauthorised'); // 2023.09.15 mod 2024.05.03

    $this->trigger(preg_match(
        '~^(?:bot|java|msie|windows-live-social-object-extractor)|\\((?:java|\w:\d{2,})~',
        $UANoSpace
    ), 'Fake UA'); // 2019.06.30

    $this->trigger(preg_match(
        '~^go +\d|movable type|msie ?(?:\d{3,}|[2-9]\d|[0-8]\.)~i',
        $UA
    ), 'Fake UA'); // 2019.06.30

    $this->trigger(preg_match('/(?:internet explorer)/', $UA), 'Hostile / Fake IE'); // 2017.02.03

    $this->trigger(preg_match('~opera/[0-8]\.~', $UA), 'Bot UA'); // 2017.02.25
    $this->trigger(strpos($UA, 'http://www.mozilla/') !== false, 'Abusive UA'); // 2017.02.25
    $this->trigger(strpos($UA, 'movabletype/3.3') !== false, 'Bot UA'); // 2017.02.25
    $this->trigger(strpos($UA, 'mozilla 4.0') !== false, 'Bot UA'); // 2017.02.25
    $this->trigger(strpos($UA, 'mozilla/0.') !== false, 'Bot UA'); // 2017.02.25
    $this->trigger(strpos($UA, 'mozilla/1.') !== false, 'Bot UA'); // 2017.02.25
    $this->trigger(strpos($UA, 'mozilla/2.0 (compatible; ask/teoma)') !== false, 'Bot UA'); // 2017.02.25
    $this->trigger(strpos($UA, 'mozilla/3.0 (compatible;)') !== false, 'Bot UA'); // 2017.02.25
    $this->trigger(strpos($UA, 'mozilla/4.0 (compatible; ics 1.2.105)') !== false, 'Bot UA'); // 2017.02.25
    $this->trigger(strpos($UA, 'mozilla/4.0 (compatible; msie 6.0; windows xp)') !== false, 'Bad UA'); // 2017.02.25
    $this->trigger(strpos($UA, 'mozilla/4.0+(compatible;+') !== false, 'Bot UA'); // 2017.02.25
    $this->trigger(strpos($UA, 'mozilla/4.76 [ru] (x11; U; sunos 5.7 sun4u)') !== false, 'Bot UA'); // 2017.02.25
    $this->trigger(strpos($UA, 'php /') !== false, 'Bot UA'); // 2017.02.25
    $this->trigger($UANoSpace === 'chorme', 'Bot UA'); // 2021.04.16
    $this->trigger(strpos($UA, '\(windows nt 10.0\; win64\; x64\)') !== false || strpos($UA, '\(khtml, like gecko\)') !== false, 'Bot UA'); // 2023.09.08
    $this->trigger(substr($this->BlockInfo['UA'], 0, 2) === '\x', 'Bot UA'); // 2023.10.15

    $this->trigger(preg_match(
        '/(?:drop ?table|(_table|assert|co(de|ntents)|dotnet_load|e(cho|regi' .
        '|scape|val|x(ec(utable)?|ists)?)|f(ile|unction)|g(et(c(sv)?|ss?)|zi' .
        'nflate)|if|[ints]able|nt|open|p(lace|uts)|re(ad|store)|s(chema|tart' .
        '|ystem)|thru|un(ction|serialize)|w(hil|rit)e)\\(|database\\(\\))/',
        $UA
    ), 'UAEX'); // 2017.02.02

    $this->trigger(preg_match('~(?:[./]seo|seo/)~', $UANoSpace), 'SEO UA'); // 2018.07.10

    $this->trigger(strpos($UA, 'bittorrent') !== false, 'Bad context (not a bittorrent hub)'); // 2017.02.25

    $this->trigger(preg_match(
        '~foregenix|nuclei|projectdiscovery|threatview~',
        $UA
    ), 'Vulnerability scanner detected; Unauthorised'); // 2023.06.16

    $this->trigger(preg_match('~^python/|aiohttp/|\.post0~', $UANoSpace), 'Bad context (Python/AIO clients not permitted here)'); // 2021.05.18

    /**
     * @link https://gist.github.com/paralax/6de9968e989c292781b2df167a1fb4ce
     */
    $this->trigger(strpos($UANoSpace, 'gbrmss/') !== false, 'Gebriano webshell detected'); // 2022.02.23

    /**
     * @link https://isc.sans.edu/forums/diary/MGLNDD+Scans/28458/
     */
    $this->trigger(preg_match('~^MGLNDD_~i', $UANoSpace), 'Attempting to expose honeypots'); // 2022.05.08

    $this->trigger(preg_match(
        '~adbar|anonymous-?coward|banana-?bot|bot-?test|brands-?bot|' .
        'clark-?crawler|fidget-?spinner-?bot|friendly-?spider|' .
        'jaddjabot|keys-?so-?bot|orbbot|phxbot|storm-?crawler|' .
        'test-?bot|thesis-?research-?bot|thinkchaos|tiny-?bot|tiny-?test|trafilatura|' .
        'whatstuffwherebot|zephuli-?bot~',
        $UANoSpace
    ), 'Scraper UA'); // 2023.11.17 mod 2024.04.11

    /** These signatures can set extended tracking options. */
    if (
        $this->trigger(strpos($UANoSpace, '$_' . '[$' . '__') !== false, 'UA shell upload attempt') || // 2017.01.02
        $this->trigger(strpos($UANoSpace, '@$' . '_[' . ']=' . '@!' . '+_') !== false, 'UA shell upload attempt') || // 2017.01.02
        $this->trigger(preg_match('/h[4a]c' . 'k(?:e[dr]|ing|t([3e][4a]m|[0o]{2}l))/', $UANoSpace), 'Hack UA') || // 2017.01.06
        $this->trigger(strpos($UANoSpace, 'alittleclient') !== false, 'Hack UA') || // 2023.04.20
        $this->trigger((
            strpos($UA, 'rm ' . '-rf') !== false ||
            strpos($UA, 'wordpress ha') !== false ||
            strpos($UANoSpace, '\0\0\0') !== false ||
            strpos($UANoSpace, 'cha0s') !== false ||
            strpos($UANoSpace, 'fhscan') !== false ||
            strpos($UANoSpace, 'havij') !== false ||
            strpos($UANoSpace, 'if(') !== false ||
            strpos($UANoSpace, 'jdatabasedrivermysqli') !== false ||
            strpos($UANoSpace, 'morfeus') !== false ||
            strpos($UANoSpace, 'r0' . '0t') !== false ||
            strpos($UANoSpace, 'sh' . 'el' . 'l_' . 'ex' . 'ec') !== false ||
            strpos($UANoSpace, 'urldumper') !== false ||
            strpos($UANoSpace, 'whcc/') !== false ||
            strpos($UANoSpace, 'xmlset_roodkcable') !== false ||
            strpos($UANoSpace, 'zollard') !== false ||
            strpos($UANoSpace, '}__') !== false ||
            preg_match('~0wn[3e]d|dkemdif.\d|f' . 'uck|:(?:\{[\w]:|[\w\d][;:]\})~', $UANoSpace)
        ), 'Hack UA') || // 2021.06.28
        $this->trigger(preg_match('~(?:(aihit|casper)bot|mamac(asper|yber)|mozilla/0)~', $UANoSpace), 'Probe UA') || // 2017.02.25
        $this->trigger(strpos($UANoSpace, 'wopbot') !== false, 'Bash/Shellshock UA') || // 2017.01.06
        $this->trigger(preg_match('/(?:x(rumer|pymep)|хрумер)/', $UANoSpace), 'Spam UA') || // 2017.01.02
        $this->trigger(preg_match('~loadimpact|re-?animator|root|theknowledgeai|webster~', $UANoSpace), 'Banned UA') || // 2021.02.10
        $this->trigger(strpos($UANoSpace, '(somename)') !== false, 'Banned UA') || // 2017.02.02
        $this->trigger(preg_match('~80legs|chinaclaw~', $UANoSpace), 'Scraper UA') || // 2017.01.08 mod 2021.06.28
        $this->trigger(preg_match('~brandwatch|magpie~', $UANoSpace), 'Snoop UA') || // 2017.01.13 mod 2021.06.28
        $this->trigger(strpos($this->BlockInfo['UA'], 'MSIECrawler') !== false, 'Hostile / Fake IE') // 2017.02.25 mod 2021.06.28
    ) {
        $this->CIDRAM['Tracking options override'] = 'extended';
    }

    /** Reporting. */
    if (!empty($this->BlockInfo['IPAddr'])) {
        if (strpos($this->BlockInfo['WhyReason'], 'Spam UA') !== false) {
            $this->Reporter->report([12, 19], ['Spambot detected.'], $this->BlockInfo['IPAddr']);
        } elseif (strpos($this->BlockInfo['WhyReason'], 'Malware UA') !== false) {
            $this->Reporter->report([19, 20], ['User agent cited by malware detected.'], $this->BlockInfo['IPAddr']);
        } elseif (strpos($this->BlockInfo['WhyReason'], 'UAEX') !== false) {
            $this->Reporter->report([15, 19], ['Detected command execution via user agent header.'], $this->BlockInfo['IPAddr']);
        } elseif (strpos($this->BlockInfo['WhyReason'], 'bittorrent') !== false) {
            $this->Reporter->report([4, 19], ['BitTorrent user agent seen at HTTP server endpoint (possible flood/DDoS attempt).'], $this->BlockInfo['IPAddr']);
        } elseif (strpos($this->BlockInfo['WhyReason'], 'Gebriano') !== false) {
            $this->Reporter->report([15, 19, 20, 21], ['Gebriano webshell detected here.'], $this->BlockInfo['IPAddr']);
        } elseif (strpos($this->BlockInfo['WhyReason'], 'UA command injection') !== false) {
            $this->Reporter->report([15], ['Command injection detected in user agent.'], $this->BlockInfo['IPAddr']);
        } elseif (strpos($this->BlockInfo['WhyReason'], 'UA script injection') !== false) {
            $this->Reporter->report([15], ['Script injection detected in user agent.'], $this->BlockInfo['IPAddr']);
        } elseif (strpos($this->BlockInfo['WhyReason'], 'UA global variable hack') !== false) {
            $this->Reporter->report([15], ['Globvar hack detected in user agent.'], $this->BlockInfo['IPAddr']);
        } elseif (strpos($this->BlockInfo['WhyReason'], 'UA shell upload attempt') !== false) {
            $this->Reporter->report([15], ['Shell upload attempt detected in user agent.'], $this->BlockInfo['IPAddr']);
        } elseif (strpos($this->BlockInfo['WhyReason'], 'Hack UA') !== false) {
            $this->Reporter->report([15, 19, 21], ['Hack identifier detected in user agent.'], $this->BlockInfo['IPAddr']);
        } elseif (strpos($this->BlockInfo['WhyReason'], 'Vulner') !== false) {
            $this->Reporter->report([15, 19, 21], ['Caught looking for vulnerabilities.'], $this->BlockInfo['IPAddr']);
        } elseif (strpos($this->BlockInfo['WhyReason'], 'UASQLi') !== false) {
            $this->Reporter->report([16], ['SQLi attempt detected in user agent.'], $this->BlockInfo['IPAddr']);
        } elseif (strpos($this->BlockInfo['WhyReason'], 'CAPTCHA cracker UA') !== false) {
            $this->Reporter->report([19], ['CAPTCHA cracker detected.'], $this->BlockInfo['IPAddr']);
        } elseif (strpos($this->BlockInfo['WhyReason'], 'Probe UA') !== false) {
            $this->Reporter->report([19], ['Probe detected.'], $this->BlockInfo['IPAddr']);
        } elseif (strpos($this->BlockInfo['WhyReason'], 'Bash/Shellshock UA') !== false) {
            $this->Reporter->report([15], ['Bash/Shellshock attempt detected via user agent.'], $this->BlockInfo['IPAddr']);
        } elseif (strpos($this->BlockInfo['WhyReason'], 'Email harvester') !== false) {
            $this->Reporter->report([19], ['Email harvester detected.'], $this->BlockInfo['IPAddr']);
        } elseif (strpos($this->BlockInfo['WhyReason'], 'Execution attempt') !== false) {
            $this->Reporter->report([15], ['Attempted to push shell commands via user agent header.'], $this->BlockInfo['IPAddr']);
        } elseif (strpos($this->BlockInfo['WhyReason'], 'XSS attack') !== false) {
            $this->Reporter->report([15], ['Attempted to push XSS via user agent header.'], $this->BlockInfo['IPAddr']);
        } elseif (strpos($this->BlockInfo['WhyReason'], 'Banned UA') !== false) {
            $this->Reporter->report([19], ['Misbehaving bot detected.'], $this->BlockInfo['IPAddr']);
        } elseif (strpos($this->BlockInfo['WhyReason'], 'Scraper UA') !== false) {
            $this->Reporter->report([19], ['Scraper detected.'], $this->BlockInfo['IPAddr']);
        } elseif (strpos($this->BlockInfo['WhyReason'], 'Fake UA') !== false) {
            $this->Reporter->report([19], ['Faked user agent detected.'], $this->BlockInfo['IPAddr']);
        } elseif (strpos($this->BlockInfo['WhyReason'], 'Attempting to expose honeypots') !== false) {
            $this->Reporter->report([21], ['Caught attempting to expose honeypot via reporting mechanism.'], $this->BlockInfo['IPAddr']);
        } elseif (strpos($this->BlockInfo['WhyReason'], 'Hack attempt') !== false) {
            $this->Reporter->report([15, 19, 21], ['Hack attempt detected.'], $this->BlockInfo['IPAddr']);
        }
    }

    /**
     * @link https://github.com/CIDRAM/CIDRAM/issues/493
     * @link https://github.com/CIDRAM/CIDRAM/issues/557
     * @link https://trunc.org/learning/the-mozlila-user-agent-bot
     */
    if (
        $this->trigger(strpos($UANoSpace, 'mozlila') !== false || strpos($UANoSpace, 'moblie') !== false, 'Attack UA') // 2023.08.10 mod 2024.01.18
    ) {
        $this->Reporter->report([15, 19, 20, 21], ['User agent cited by various attack tools, rootkits, backdoors, webshells, and malware detected.'], $this->BlockInfo['IPAddr']);
        $this->CIDRAM['Tracking options override'] = 'extended';
    }

    /**
     * @link https://github.com/CIDRAM/CIDRAM/issues/494
     * @link https://www.reddit.com/r/singularity/comments/1cdm97j/anthropics_claudebot_is_aggressively_scraping_the/
     * @link https://www.linode.com/community/questions/24842/ddos-from-anthropic-ai
     */
    if ($this->trigger((strpos($UANoSpace, 'anthropic') !== false || strpos($UANoSpace, 'claudebot') !== false), 'Unauthorised AI scanner')) {
        $this->Reporter->report([4, 19], ['AI scanner notorious for flooding and DDoS attacks detected.'], $this->BlockInfo['IPAddr']);
        $this->CIDRAM['Tracking options override'] = 'extended';
    } // 2023.08.10 mod 2024.04.27
};

/** Execute closure. */
$this->CIDRAM['ModuleResCache'][$Module]();
