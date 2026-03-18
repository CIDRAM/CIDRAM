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
 * This file: The cache data page (last modified: 2026.03.18).
 */

namespace CIDRAM\CIDRAM;

if (!isset($this->FE['Permissions'], $this->CIDRAM['QueryVars']['cidram-page']) || $this->CIDRAM['QueryVars']['cidram-page'] !== 'cache-data' || $this->FE['Permissions'] !== 1) {
    die;
}

/** Page initial prepwork. */
$this->initialPrepwork($this->L10N->getString('link.Cache Data'), $this->L10N->getString('tip.Cache Data'));

/** All cache sources. */
$Sources = [];

/** The primary caching source. */
if ($this->Cache->Using !== '') {
    $Sources[$this->Cache->Using] = &$this->Cache;
}

/** In case a flatfile cache exists but isn't the primary caching source. */
if ($this->Cache->Using !== 'FF' && $this->CachePath !== '' && \is_writable(\preg_replace('~/[^/]+$~', '', $this->CachePath))) {
    $Sources['FF'] = new \Maikuolan\Common\Cache();
    $Sources['FF']->Prefix = $this->Configuration['supplementary_cache_options']['prefix'];
    $Sources['FF']->FFDefault = $this->CachePath;
    if (!$Sources['FF']->connect()) {
        $Sources['FF'] = false;
    }
}

/**
 * In case APCu is available but isn't the primary caching source (doing this
 * for APCu but not the others, as the others would potentially require a
 * server connection, which may or may not be desirable to the user, whereas
 * APCu data should be immediately available if the extension is available at
 * all).
 */
if ($this->Cache->Using !== 'APCu' && \extension_loaded('apcu')) {
    $Sources['APCu'] = new \Maikuolan\Common\Cache();
    $Sources['APCu']->Prefix = $this->Configuration['supplementary_cache_options']['prefix'];
    $Sources['APCu']->EnableAPCu = true;
    if (!$Sources['APCu']->connect()) {
        $Sources['APCu'] = false;
    }
}

if ($this->FE['ASYNC']) {
    if (isset($_POST['do'])) {
        /** Delete a cache entry. */
        if ($_POST['do'] === 'delete' && isset($_POST['cdi'], $_POST['csrc']) && $_POST['cdi'] !== '' && $_POST['csrc'] !== '' && isset($Sources[$_POST['csrc']])) {
            if ($_POST['cdi'] === '__') {
                /** Delete all entries ("clear all"). */
                $Sources[$_POST['csrc']]->clearCache();
            } elseif (\substr($_POST['cdi'], 0, 1) === '^') {
                /** Delete all sub-entries under a specific parent entry. */
                $Sources[$_POST['csrc']]->deleteAllEntriesWhere('~' . $_POST['cdi'] . '-~');
            } else {
                /** Delete just a specific entry (or sub-entry). */
                $Sources[$_POST['csrc']]->deleteEntry($_POST['cdi']);
            }
        }

        /** Duplicate cache entries. */
        if ($_POST['do'] === 'duplicate' && isset($_POST['csrc'], $_POST['ctrg']) && $_POST['csrc'] !== '' && $_POST['ctrg'] !== '' && isset($Sources[$_POST['csrc']], $Sources[$_POST['ctrg']])) {
            $Sources[$_POST['ctrg']]->setEntries($Sources[$_POST['csrc']]->getAllEntries());
        }
    }
} else {
    /** Append async globals. */
    $this->FE['JS'] .=
        "function cdd(d,x){window.cdi=d,window.csrc=x,window.do='delete',$('POST" .
        "','',['cidram-form-target','cdi','csrc','do'],null,function(o){'__'===d" .
        "?window.location.reload():'^'===d.substring(0,1)&&(d=d.\substr(1)),hidei" .
        "d(d+'Container'+x)})};function cdp(d,x){window.csrc=d,window.ctrg=x,win" .
        "dow.do='duplicate',$('POST','',['cidram-form-target','csrc','ctrg','do'" .
        "],null,function(o){window.location.reload()})}window['cidram-form-targe" .
        "t']='cache-data';";

    /** To be populated by the cache data. */
    $this->FE['CacheData'] = '';

    $IsFirst = true;
    $ClearAll = $this->L10N->getString('field.Clear all');
    $Action = $this->L10N->getString('confirm.Action');
    $Duplicate = $this->L10N->getString('label.Duplicate to %s');
    foreach ($Sources as $SourceKey => &$Source) {
        $CacheArray = [];
        foreach ($Source->getAllEntries() as $ThisCacheName => $ThisCacheItem) {
            if (isset($ThisCacheItem['Time']) && $ThisCacheItem['Time'] > 0 && $ThisCacheItem['Time'] < $this->Now) {
                continue;
            }
            $this->arrayify($ThisCacheItem);
            $CacheArray[$ThisCacheName] = $ThisCacheItem;
        }
        if (!$IsFirst && \count($CacheArray) === 0) {
            continue;
        }

        /** Source label. */
        $SourceLabel = $SourceKey === 'FF' ? \preg_replace('~^.*/([^/]+)$~', '\1', $Source->FFDefault) : $SourceKey;

        /** Whether inactive. */
        $Status = $IsFirst ? '' : ' (' . $this->L10N->getString('label.Inactive') . ')';

        /** Duplicability. */
        $Duplicability = '';
        foreach (\array_keys($Sources) as $Key) {
            if ($Key === $SourceKey) {
                continue;
            }
            $KeyLabel = $Key === 'FF' ? \preg_replace('~^.*/([^/]+)$~', '\1', $this->CachePath) : $Key;
            $DuplicateTo = \sprintf($Duplicate, $KeyLabel);
            $Duplicability .= ' – <span onclick="javascript:confirm(\'' . $this->escapeJsInHTML(
                \sprintf($Action, $DuplicateTo)
            ) . '\')&&cdp(\'' . $SourceKey . '\',\'' . $Key . '\')"><code><span class="auxicon export" title="' . $DuplicateTo . '"></span><span class="s auxicontxt">' . $DuplicateTo . '</span></code></span>';
        }

        /** Process all cache items. */
        $this->FE['CacheData'] .= \sprintf(
            '<div class="ng1" id="__Container%1$s"><span class="s">%2$s – (<span onclick="javascript:confirm(\'%3$s\')&&cdd(\'__\',\'%1$s\')"><code><span class="auxicon auxrd delete" title="%4$s"></span><span class="s auxicontxt">%4$s</span></code></span>%5$s)</span><br /><br /><ul class="pieul">%6$s</ul></div>',
            $SourceKey,
            $SourceLabel . $Status,
            $this->escapeJsInHTML(\sprintf($Action, $ClearAll) . ($IsFirst ? '\n' . $this->L10N->getString('warning.Proceeding will log out all users') : '')),
            $ClearAll,
            $Duplicability,
            $this->arrayToClickableList($CacheArray, 'cdd', 0, $SourceLabel, $SourceKey)
        );
        $IsFirst = false;
    }
    unset($DuplicateTo, $KeyLabel, $Key, $Duplicability, $Status, $SourceLabel, $ThisCacheName, $ThisCacheItem, $CacheArray, $Source, $SourceKey, $Duplicate, $Action, $ClearAll, $IsFirst);

    /** Cache is empty. */
    if (!$this->FE['CacheData']) {
        $this->FE['CacheData'] = '<div class="ng1"><span class="s">' . $this->L10N->getString('label.The cache is empty') . '</span></div>';
    }

    /** Parse output. */
    $this->FE['FE_Content'] = $this->parseVars($this->FE, $this->readFile($this->getAssetPath('_cache.html')), true) . $this->CIDRAM['MenuToggle'];

    /** Send output. */
    echo $this->sendOutput();
}
unset($Sources);
