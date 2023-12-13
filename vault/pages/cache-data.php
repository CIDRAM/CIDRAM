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
 * This file: The cache data page (last modified: 2023.12.13).
 */

namespace CIDRAM\CIDRAM;

if (!isset($this->FE['Permissions'], $this->CIDRAM['QueryVars']['cidram-page']) || $this->CIDRAM['QueryVars']['cidram-page'] !== 'cache-data' || $this->FE['Permissions'] !== 1) {
    die;
}

/** Page initial prepwork. */
$this->initialPrepwork($this->L10N->getString('link.Cache Data'), $this->L10N->getString('tip.Cache Data'));

if ($this->FE['ASYNC']) {
    /** Delete a cache entry. */
    if (isset($_POST['do']) && $_POST['do'] === 'delete' && !empty($_POST['cdi'])) {
        if ($_POST['cdi'] === '__') {
            $this->Cache->clearCache();
        } elseif (substr($_POST['cdi'], 0, 1) === '^') {
            $this->Cache->deleteAllEntriesWhere('~' . $_POST['cdi'] . '-~');
        } else {
            $this->Cache->deleteEntry($_POST['cdi']);
        }
    }
} else {
    /** Append async globals. */
    $this->FE['JS'] .=
        "function cdd(d){window.cdi=d,window.do='delete',$('POST','',['cidram-for" .
        "m-target','cdi','do'],null,function(o){'__'===d?window.location=window.l" .
        "ocation.href.split('?')[0]:'^'===d.substring(0,1)&&(d=d.substr(1)),hidei" .
        "d(d+'Container')})}window['cidram-form-target']='cache-data';";

    /** To be populated by the cache data. */
    $this->FE['CacheData'] = '';

    /** To be populated by the cache data. */
    $PreferredSource = ($this->Cache->Using && $this->Cache->Using !== 'FF') ? $this->Cache->Using : 'cache.dat';

    /** Array of all cache items. */
    $CacheArray = [];

    /** Get cache index data. */
    foreach ($this->Cache->getAllEntries() as $ThisCacheName => $ThisCacheItem) {
        if (isset($ThisCacheItem['Time']) && $ThisCacheItem['Time'] > 0 && $ThisCacheItem['Time'] < $this->Now) {
            continue;
        }
        $this->arrayify($ThisCacheItem);
        $CacheArray[$ThisCacheName] = $ThisCacheItem;
    }
    unset($ThisCacheName, $ThisCacheItem);

    /** Process all cache items. */
    $this->FE['CacheData'] .= sprintf(
        '<div class="ng1" id="__Container"><span class="s">%s – (<span onclick="javascript:confirm(\'%s\')&&cdd(\'__\')"><code class="s">%s</code></span>)</span><br /><br /><ul class="pieul">%s</ul></div>',
        $PreferredSource,
        $this->escapeJsInHTML(sprintf(
            $this->L10N->getString('confirm.Action'),
            $this->L10N->getString('field.Clear all')
        ) . '\n' . $this->L10N->getString('warning.Proceeding will log out all users')),
        $this->L10N->getString('field.Clear all'),
        $this->arrayToClickableList($CacheArray, 'cdd', 0, $PreferredSource)
    );
    unset($PreferredSource, $CacheArray);

    /** Cache is empty. */
    if (!$this->FE['CacheData']) {
        $this->FE['CacheData'] = '<div class="ng1"><span class="s">' . $this->L10N->getString('label.The cache is empty') . '</span></div>';
    }

    /** Parse output. */
    $this->FE['FE_Content'] = $this->parseVars($this->FE, $this->readFile($this->getAssetPath('_cache.html')), true) . $this->CIDRAM['MenuToggle'];

    /** Send output. */
    echo $this->sendOutput();
}
