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
 * This file: The configuration page (last modified: 2024.04.18).
 */

namespace CIDRAM\CIDRAM;

if (!isset($this->FE['Permissions'], $this->CIDRAM['QueryVars']['cidram-page']) || $this->CIDRAM['QueryVars']['cidram-page'] !== 'config' || $this->FE['Permissions'] !== 1) {
    die;
}

/** Page initial prepwork. */
$this->initialPrepwork($this->L10N->getString('link.Configuration'), $this->L10N->getString('tip.Configuration'));

/** Append number localisation JS. */
$this->FE['JS'] .= $this->numberJs() . "\n";

/** Directive template. */
$this->FE['ConfigRow'] = $this->readFile($this->getAssetPath('_config_row.html'));

/** Flag for modified configuration. */
$this->CIDRAM['ConfigModified'] = false;

$this->FE['Indexes'] = '<ul class="pieul">';
$this->FE['ConfigFields'] = '';

/** For required extensions, classes, etc. */
$ReqsLookupCache = [];

/** Rebuilding in order to strip out orphaned data. */
if (isset($_POST['orphaned'])) {
    $NewConfig = [];
}

/** Iterate through configuration defaults. */
foreach ($this->CIDRAM['Config Defaults'] as $CatKey => $CatValue) {
    if (!is_array($CatValue)) {
        continue;
    }
    if ($CatInfo = $this->L10N->getString('config.' . $CatKey)) {
        $CatInfo = '<br /><em>' . $CatInfo . '</em>';
    }
    $this->FE['ConfigFields'] .= sprintf(
        '<table class="tablend"><tr><td class="ng2"><div id="%1$s-container" class="s">' .
        '<a id="%1$sShowLink" class="showlink" href="#%1$s-container" onclick="javascript:toggleconfig(\'%1$sRow\',\'%1$sShowLink\')">%1$s</a>' .
        '%3$s</div></td></tr></table><span id="%1$sRow" %2$s><table class="tablend">',
        $CatKey,
        'style="display:none"',
        $CatInfo
    ) . "\n";
    $CatData = '';
    foreach ($CatValue as $DirKey => $DirValue) {
        $ThisDir = ['Reset' => '', 'Preview' => '', 'Trigger' => '', 'FieldOut' => '', 'CatKey' => $CatKey];
        if (empty($DirValue['type']) || !isset($this->Configuration[$CatKey][$DirKey])) {
            continue;
        }
        $ThisDir['DirLangKey'] = 'config_' . $CatKey . '_' . $DirKey;
        $ThisDir['DirLangKeyOther'] = $ThisDir['DirLangKey'] . '_other';
        $ThisDir['DirName'] = '<span class="normalHeight">' . $this->ltrInRtf($CatKey . '➡' . $DirKey) . ':</span>';
        $ThisDir['Friendly'] = $this->L10N->getString('config.' . $CatKey . '_' . $DirKey . '_label') ?: $DirKey;
        $CatData .= sprintf(
            '<li><a onclick="javascript:toggleconfigNav(\'%1$sRow\',\'%1$sShowLink\')" href="#%2$s">%3$s</a></li>',
            $CatKey,
            $ThisDir['DirLangKey'],
            $ThisDir['Friendly']
        );
        $ThisDir['DirLang'] =
            $this->L10N->getString('config.' . $CatKey . '_' . $DirKey) ?:
            $this->L10N->getString('label.' . $DirKey) ?:
            $this->L10N->getString('config.' . $CatKey) ?:
            $this->L10N->getString('response.Error');
        if (!empty($DirValue['experimental'])) {
            $ThisDir['DirLang'] = '<code class="exp">' . $this->L10N->getString('config.experimental') . '</code> ' . $ThisDir['DirLang'];
        }
        $ThisDir['autocomplete'] = empty($DirValue['autocomplete']) ? '' : sprintf(
            ' autocomplete="%s"',
            $DirValue['autocomplete']
        );

        /** Fix for PHP automatically changing certain kinds of $_POST keys. */
        if (!isset($_POST[$ThisDir['DirLangKey']])) {
            $Try = str_replace('.', '_', $ThisDir['DirLangKey']);
            if (isset($_POST[$Try])) {
                $_POST[$ThisDir['DirLangKey']] = $_POST[$Try];
                unset($_POST[$Try]);
            }
        }

        if (isset($_POST[$ThisDir['DirLangKey']])) {
            if (in_array($DirValue['type'], ['email', 'string', 'timezone', 'url', 'float', 'int', 'duration', 'bool', 'kb'], true)) {
                $this->autoType($_POST[$ThisDir['DirLangKey']], $DirValue['type']);
            }
            if (!isset($DirValue['choices']) || isset($DirValue['choices'][$_POST[$ThisDir['DirLangKey']]])) {
                $this->CIDRAM['ConfigModified'] = true;
                $this->Configuration[$CatKey][$DirKey] = $_POST[$ThisDir['DirLangKey']];
            } elseif (
                !empty($DirValue['allow_other']) &&
                $_POST[$ThisDir['DirLangKey']] === 'Other' &&
                isset($_POST[$ThisDir['DirLangKeyOther']]) &&
                !preg_match('/[^\x20-\xFF"\']/', $_POST[$ThisDir['DirLangKeyOther']])
            ) {
                $this->CIDRAM['ConfigModified'] = true;
                $this->Configuration[$CatKey][$DirKey] = $_POST[$ThisDir['DirLangKeyOther']];
            }
        } elseif (
            $DirValue['type'] === 'checkbox' &&
            isset($DirValue['choices']) &&
            is_array($DirValue['choices'])
        ) {
            $DirValue['Posts'] = [];
            foreach ($DirValue['choices'] as $DirValue['ThisChoiceKey'] => $DirValue['ThisChoice']) {
                if (isset($DirValue['labels']) && is_array($DirValue['labels'])) {
                    foreach ($DirValue['labels'] as $DirValue['ThisLabelKey'] => $DirValue['ThisLabel']) {
                        if (!empty($_POST[$ThisDir['DirLangKey'] . '_' . $DirValue['ThisChoiceKey'] . '_' . $DirValue['ThisLabelKey']])) {
                            $DirValue['Posts'][] = $DirValue['ThisChoiceKey'] . ':' . $DirValue['ThisLabelKey'];
                        } else {
                            $Try = str_replace('.', '_', $ThisDir['DirLangKey'] . '_' . $DirValue['ThisChoiceKey'] . '_' . $DirValue['ThisLabelKey']);
                            if (!empty($_POST[$Try])) {
                                $_POST[$ThisDir['DirLangKey'] . '_' . $DirValue['ThisChoiceKey'] . '_' . $DirValue['ThisLabelKey']] = $_POST[$Try];
                                unset($_POST[$Try]);
                                $DirValue['Posts'][] = $DirValue['ThisChoiceKey'] . ':' . $DirValue['ThisLabelKey'];
                            }
                        }
                    }
                } elseif (!empty($_POST[$ThisDir['DirLangKey'] . '_' . $DirValue['ThisChoiceKey']])) {
                    $DirValue['Posts'][] = $DirValue['ThisChoiceKey'];
                } else {
                    $Try = str_replace('.', '_', $ThisDir['DirLangKey'] . '_' . $DirValue['ThisChoiceKey']);
                    if (!empty($_POST[$Try])) {
                        $_POST[$ThisDir['DirLangKey'] . '_' . $DirValue['ThisChoiceKey']] = $_POST[$Try];
                        unset($_POST[$Try]);
                        $DirValue['Posts'][] = $DirValue['ThisChoiceKey'];
                    }
                }
            }
            $DirValue['Posts'] = implode("\n", $DirValue['Posts']) ?: '';
            if (!empty($_POST['updatingConfig']) && $this->Configuration[$CatKey][$DirKey] !== $DirValue['Posts']) {
                $this->CIDRAM['ConfigModified'] = true;
                $this->Configuration[$CatKey][$DirKey] = $DirValue['Posts'];
            }
        }
        if (isset($DirValue['preview'])) {
            $ThisDir['Preview'] = ($DirValue['preview'] === 'allow_other') ? '' : sprintf(' = <span id="%s_preview"></span>', $ThisDir['DirLangKey']);
            $ThisDir['Trigger'] = ' onchange="javascript:' . $ThisDir['DirLangKey'] . '_function();" onkeyup="javascript:' . $ThisDir['DirLangKey'] . '_function();"';
            if ($DirValue['preview'] === 'seconds') {
                $ThisDir['Preview'] .= sprintf(
                    '<script type="text/javascript">function %1$s_function(){var t=%9$s?%9$s(' .
                    '\'%1$s_field\').value:%10$s&&!%9$s?%10$s.%1$s_field.value:\'\',e=isNaN(t' .
                    ')?0:0>t?t*-1:t,n=e?Math.floor(e/31536e3):0,e=e?e-31536e3*n:0,o=e?Math.fl' .
                    'oor(e/2592e3):0,e=e-2592e3*o,l=e?Math.floor(e/604800):0,e=e-604800*l,r=e' .
                    '?Math.floor(e/86400):0,e=e-86400*r,d=e?Math.floor(e/3600):0,e=e-3600*d,i' .
                    '=e?Math.floor(e/60):0,e=e-60*i,f=e?Math.floor(1*e):0,a=nft(n.toString())' .
                    '+\' %2$s – \'+nft(o.toString())+\' %3$s – \'+nft(l.toString())+\' %4$s –' .
                    ' \'+nft(r.toString())+\' %5$s – \'+nft(d.toString())+\' %6$s – \'+nft(i.' .
                    'toString())+\' %7$s – \'+nft(f.toString())+\' %8$s\';%9$s?%9$s(\'%1$s_pr' .
                    'eview\').innerHTML=a:%10$s&&!%9$s?%10$s.%1$s_preview.innerHTML=a:\'\'}' .
                    '%1$s_function();</script>',
                    $ThisDir['DirLangKey'],
                    $this->L10N->getString('previewer.Years'),
                    $this->L10N->getString('previewer.Months'),
                    $this->L10N->getString('previewer.Weeks'),
                    $this->L10N->getString('previewer.Days'),
                    $this->L10N->getString('previewer.Hours'),
                    $this->L10N->getString('previewer.Minutes'),
                    $this->L10N->getString('previewer.Seconds'),
                    'document.getElementById',
                    'document.all'
                );
            } elseif ($DirValue['preview'] === 'minutes') {
                $ThisDir['Preview'] .= sprintf(
                    '<script type="text/javascript">function %1$s_function(){var t=%9$s?%9$s(' .
                    '\'%1$s_field\').value:%10$s&&!%9$s?%10$s.%1$s_field.value:\'\',e=isNaN(t' .
                    ')?0:0>t?t*-1:t,n=e?Math.floor(e/525600):0,e=e?e-525600*n:0,o=e?Math.floo' .
                    'r(e/43200):0,e=e-43200*o,l=e?Math.floor(e/10080):0,e=e-10080*l,r=e?Math.' .
                    'floor(e/1440):0,e=e-1440*r,d=e?Math.floor(e/60):0,e=e-60*d,i=e?Math.floo' .
                    'r(e*1):0,e=e-i,f=e?Math.floor(60*e):0,a=nft(n.toString())+\' %2$s – \'+n' .
                    'ft(o.toString())+\' %3$s – \'+nft(l.toString())+\' %4$s – \'+nft(r.toStr' .
                    'ing())+\' %5$s – \'+nft(d.toString())+\' %6$s – \'+nft(i.toString())+\' ' .
                    '%7$s – \'+nft(f.toString())+\' %8$s\';%9$s?%9$s(\'%1$s_preview\').innerH' .
                    'TML=a:%10$s&&!%9$s?%10$s.%1$s_preview.innerHTML=a:\'\'}%1$s_function();<' .
                    '/script>',
                    $ThisDir['DirLangKey'],
                    $this->L10N->getString('previewer.Years'),
                    $this->L10N->getString('previewer.Months'),
                    $this->L10N->getString('previewer.Weeks'),
                    $this->L10N->getString('previewer.Days'),
                    $this->L10N->getString('previewer.Hours'),
                    $this->L10N->getString('previewer.Minutes'),
                    $this->L10N->getString('previewer.Seconds'),
                    'document.getElementById',
                    'document.all'
                );
            } elseif ($DirValue['preview'] === 'hours') {
                $ThisDir['Preview'] .= sprintf(
                    '<script type="text/javascript">function %1$s_function(){var t=%9$s?%9$s(' .
                    '\'%1$s_field\').value:%10$s&&!%9$s?%10$s.%1$s_field.value:\'\',e=isNaN(t' .
                    ')?0:0>t?t*-1:t,n=e?Math.floor(e/8760):0,e=e?e-8760*n:0,o=e?Math.floor(e/' .
                    '720):0,e=e-720*o,l=e?Math.floor(e/168):0,e=e-168*l,r=e?Math.floor(e/24):' .
                    '0,e=e-24*r,d=e?Math.floor(e*1):0,e=e-d,i=e?Math.floor(60*e):0,e=e-(i/60)' .
                    ',f=e?Math.floor(3600*e):0,a=nft(n.toString())+\' %2$s – \'+nft(o.toStrin' .
                    'g())+\' %3$s – \'+nft(l.toString())+\' %4$s – \'+nft(r.toString())+\' ' .
                    '%5$s – \'+nft(d.toString())+\' %6$s – \'+nft(i.toString())+\' %7$s – \'+' .
                    'nft(f.toString())+\' %8$s\';%9$s?%9$s(\'%1$s_preview\').innerHTML=a:' .
                    '%10$s&&!%9$s?%10$s.%1$s_preview.innerHTML=a:\'\'}%1$s_function();</script>',
                    $ThisDir['DirLangKey'],
                    $this->L10N->getString('previewer.Years'),
                    $this->L10N->getString('previewer.Months'),
                    $this->L10N->getString('previewer.Weeks'),
                    $this->L10N->getString('previewer.Days'),
                    $this->L10N->getString('previewer.Hours'),
                    $this->L10N->getString('previewer.Minutes'),
                    $this->L10N->getString('previewer.Seconds'),
                    'document.getElementById',
                    'document.all'
                );
            } elseif ($DirValue['preview'] === 'allow_other') {
                $ThisDir['Preview'] .= sprintf(
                    '<script type="text/javascript">function %1$s_function(){var e=%2$s?%2$s(' .
                    '\'%1$s_field\').value:%3$s&&!%2$s?%3$s.%1$s_field.value:\'\';e==\'Other\'' .
                    '?showid(\'%4$s_field\'):hideid(\'%4$s_field\')};%1$s_function();</script>',
                    $ThisDir['DirLangKey'],
                    'document.getElementById',
                    'document.all',
                    $ThisDir['DirLangKeyOther']
                );
            } elseif (substr($DirValue['preview'], 0, 3) === 'js:') {
                $ThisDir['Preview'] .= '<script type="text/javascript">' . sprintf(
                    substr($DirValue['preview'], 3),
                    $ThisDir['DirLangKey']
                ) . '</script>';
            }
        } elseif ($DirValue['type'] === 'duration') {
            $ThisDir['Preview'] = sprintf(' = <span id="%s_preview"></span>', $ThisDir['DirLangKey']);
            $ThisDir['Trigger'] = ' onchange="javascript:' . $ThisDir['DirLangKey'] . '_function();" onkeyup="javascript:' . $ThisDir['DirLangKey'] . '_function();"';
            $ThisDir['Preview'] .= sprintf(
                '<script type="text/javascript">function %1$s_function(){var t=%9$s?%9$s(\'%1' .
                '$s_field\').value:%10$s&&!%9$s?%10$s.%1$s_field.value:\'\',found=t.match(/^' .
                '\s*(?:(?<Weeks>\d+(?:\.\d+)?)\s*[Ww](?:[Ee]{2}[Kk][Ss]?)?)?\s*(?:(?<Days>\d+' .
                '(?:\.\d+)?)\s*[Dd](?:[Aa][Yy][Ss]?)?)?\s*(?:(?<Hours>\d+(?:\.\d+)?)\s*(?:\xB' .
                '0|°|[Hh](?:[Oo][Uu][Rr][Ss]?)?))?\s*(?:(?<Minutes>\d+(?:\.\d+)?)\s*(?:\'|′|[' .
                'Mm](?:[Ii][Nn][Uu]?[Tt]?[Ee]?[Ss]?)?))?\s*(?:(?<Seconds>\d+(?:\.\d+)?)\s*(?:' .
                '"|″|[Ss](?:[Ee][Cc][Oo]?[Nn]?[Dd]?[Ss]?)?))?\s*(?:(?<Milli>\d+(?:\.\d+)?)\s*' .
                'ms)?\s*(?:(?<Micro>\d+(?:\.\d+)?)\s*µs)?\s*(?:(?<Nano>\d+(?:\.\d+)?)\s*ns)?' .
                '\s*$|^\s*(?<Unspecified>\d+(?:\.\d+)?)?\s*$/);if (undefined===found||!found)' .
                'return;for(var limit=0,f=(void 0===found.groups.Seconds?0:Math.abs(found.gro' .
                'ups.Seconds))+(void 0===found.groups.Unspecified?0:Math.abs(found.groups.Uns' .
                'pecified))+(void 0===found.groups.Milli||Math.abs(found.groups.Milli)<1?0:Ma' .
                'th.abs(found.groups.Milli)/1e3)+(void 0===found.groups.Micro||Math.abs(found' .
                '.groups.Micro)<1?0:Math.abs(found.groups.Micro)/1e6),i=void 0===found.groups' .
                '.Minutes?0:Math.abs(found.groups.Minutes),d=void 0===found.groups.Hours?0:Ma' .
                'th.abs(found.groups.Hours),r=void 0===found.groups.Days?0:Math.abs(found.gro' .
                'ups.Days),l=void 0===found.groups.Weeks?0:Math.abs(found.groups.Weeks),fb=f,' .
                'ib=i,db=d,rb=r,lb=l,o=0,ob=o,n=0,nb=n;limit<10;limit++){var x;if(l-(x=Math.f' .
                'loor(l))>0&&(r+=7*(l-x),l=x),r-(x=Math.floor(r))>0&&(d+=24*(r-x),r=x),d-(x=M' .
                'ath.floor(d))>0&&(i+=60*(d-x),d=x),i-(x=Math.floor(i))>0&&(f+=60*(i-x),i=x),' .
                'f>=60)f-=60*(x=Math.floor(f/60)),i+=x;if(i>=60)i-=60*(x=Math.floor(i/60)),d+' .
                '=x;if(d>=24)d-=24*(x=Math.floor(d/24)),r+=x;if(r>=7)r-=7*(x=Math.floor(r/7))' .
                ',l+=x;if(l>=52)l-=52*(x=Math.floor(l/52)),n+=x;if(l>=4)l-=4*(x=Math.floor(l/' .
                '4)),o+=x;if(fb===f&&ib===i&&db===d&&rb===r&&lb===l&&ob===o&&nb===n)break;fb=' .
                'f,ib=i,db=d,rb=r,lb=l,ob=o,nb=n};f=Math.floor(f),i=Math.floor(i),d=Math.floo' .
                'r(d),r=Math.floor(r),l=Math.floor(l),o=Math.floor(o),n=Math.floor(n),a=nft(n' .
                '.toString())+\' %2$s – \'+nft(o.toString())+\' %3$s – \'+nft(l.toString())+' .
                '\' %4$s – \'+nft(r.toString())+\' %5$s – \'+nft(d.toString())+\' %6$s – \'+n' .
                'ft(i.toString())+\' %7$s – \'+nft(f.toString())+\' %8$s\';%9$s?%9$s(\'%1$s_p' .
                'review\').innerHTML=a:%10$s&&!%9$s?%10$s.%1$s_preview.innerHTML=a:\'\';}%1$s' .
                '_function();</script>',
                $ThisDir['DirLangKey'],
                $this->L10N->getString('previewer.Years'),
                $this->L10N->getString('previewer.Months'),
                $this->L10N->getString('previewer.Weeks'),
                $this->L10N->getString('previewer.Days'),
                $this->L10N->getString('previewer.Hours'),
                $this->L10N->getString('previewer.Minutes'),
                $this->L10N->getString('previewer.Seconds'),
                'document.getElementById',
                'document.all'
            );
        } elseif ($DirValue['type'] === 'kb') {
            $ThisDir['Preview'] = sprintf(' = <span id="%s_preview"></span>', $ThisDir['DirLangKey']);
            $ThisDir['Trigger'] = ' onchange="javascript:' . $ThisDir['DirLangKey'] . '_function();" onkeyup="javascript:' . $ThisDir['DirLangKey'] . '_function();"';
            $ThisDir['Preview'] .= sprintf(
                '<script type="text/javascript">function %1$s_function(){const bytesPerUnit={' .
                'B:1,K:1024,M:1048576,G:1073741824,T:1099511627776,P:1125899906842620},unitNa' .
                'mes=["%2$s","%3$s","%4$s","%5$s","%6$s","%7$s"];var e=%8$s?%8$s(\'%1$s_field' .
                '\').value:%9$s&&!%8$s?%9$s.%1$s_field.value:\'\';if((Unit=e.match(/(?<Unit>[' .
                'KkMmGgTtPpOoBb]|К|к|М|м|Г|г|Т|т|П|п|Ｋ|ｋ|Ｍ|ｍ|Ｇ|ｇ|Ｔ|ｔ|Ｐ|ｐ|Б|б|Ｂ|ｂ)(?:[OoBb]|Б|' .
                'б|Ｂ|ｂ)?$/))&&void 0!==Unit.groups.Unit)if((Unit=Unit.groups.Unit).match(/^(?' .
                ':[OoBb]|Б|б|Ｂ|ｂ)$/))var Unit=\'B\';else if(Unit.match(/^(?:[Mm]|М|м)$/))Unit' .
                '=\'M\';else if(Unit.match(/^(?:[Gg]|Г|г)$/))Unit=\'G\';else if(Unit.match(/^' .
                '(?:[Tt]|Т|т)$/))Unit=\'T\';else if(Unit.match(/^(?:[Pp]|П|п)$/))Unit=\'P\';e' .
                'lse Unit=\'K\';else Unit=\'K\';var e=parseFloat(e);if(isNaN(e))var fixed=0;e' .
                'lse{if(void 0!==bytesPerUnit[Unit])fixed=e*bytesPerUnit[Unit];else fixed=e;f' .
                'ixed=Math.floor(fixed)}for(var i=0,p=unitNames[i];fixed>=1024;){fixed=fixed/' .
                '1024;i++;p=unitNames[i];if(i>=5)break}t=nft(fixed.toFixed(i===0?0:2))+\' \'+' .
                'p;%8$s?%8$s(\'%1$s_preview\').innerHTML=t:%9$s&&!%8$s?%9$s.%1$s_preview.inne' .
                'rHTML=t:\'\';};%1$s_function();</script>',
                $ThisDir['DirLangKey'],
                $this->L10N->getPlural(0, 'field.size.bytes'),
                $this->L10N->getString('field.size.KB'),
                $this->L10N->getString('field.size.MB'),
                $this->L10N->getString('field.size.GB'),
                $this->L10N->getString('field.size.TB'),
                $this->L10N->getString('field.size.PB'),
                'document.getElementById',
                'document.all'
            );
        }
        if ($DirValue['type'] === 'timezone') {
            $DirValue['choices'] = ['SYSTEM' => $this->L10N->getString('field.Use system default timezone')];
            foreach (array_unique(\DateTimeZone::listIdentifiers()) as $DirValue['ChoiceValue']) {
                $DirValue['choices'][$DirValue['ChoiceValue']] = $DirValue['ChoiceValue'];
            }
        }
        if (isset($DirValue['choices'])) {
            if (
                $DirValue['type'] === 'checkbox' ||
                (isset($DirValue['style']) && $DirValue['style'] === 'radio')
            ) {
                if (isset($DirValue['labels']) && is_array($DirValue['labels'])) {
                    $DirValue['gridV'] = 'gridVB';
                    $ThisDir['FieldOut'] = sprintf(
                        '<div style="display:grid;margin:auto 38px;grid-template-columns:repeat(%s) auto;text-align:%s">',
                        count($DirValue['labels']) . ',minmax(0, 1fr)',
                        $this->FE['FE_Align']
                    );
                    $DirValue['HasLabels'] = true;
                    foreach ($DirValue['labels'] as $DirValue['ThisLabel']) {
                        $DirValue['gridV'] = ($DirValue['gridV']) === 'gridVB' ? 'gridVA' : 'gridVB';
                        $this->replaceLabelWithL10n($DirValue['ThisLabel']);
                        $ThisDir['FieldOut'] .= sprintf(
                            '<div class="gridboxitem configMatrixLabel %s">%s</div>',
                            $DirValue['gridV'],
                            $DirValue['ThisLabel']
                        );
                    }
                    $ThisDir['FieldOut'] .= '<div class="gridboxitem"></div>';
                } else {
                    $ThisDir['FieldOut'] = sprintf(
                        '<div style="display:grid;margin:auto 38px;grid-template-columns:19px auto;text-align:%s">',
                        $this->FE['FE_Align']
                    );
                    $DirValue['HasLabels'] = false;
                }
            } else {
                $ThisDir['FieldOut'] = sprintf(
                    '<select class="auto" style="text-transform:capitalize" name="%1$s" id="%1$s_field"%2$s>',
                    $ThisDir['DirLangKey'],
                    $ThisDir['Trigger']
                );
                if (!empty($DirValue['allow_other'])) {
                    $ThisDir['FieldOut'] = '<div class="flexrow">' . $ThisDir['FieldOut'];
                }
            }
            $DirValue['gridH'] = 'gridHB';
            foreach ($DirValue['choices'] as $ChoiceKey => $ChoiceValue) {
                if (isset($DirValue['choice_filter'])) {
                    if (!is_string($ChoiceValue) || $this->{$DirValue['choice_filter']}($ChoiceKey, $ChoiceValue) === false) {
                        continue;
                    }
                }
                $DirValue['gridV'] = 'gridVB';
                $DirValue['gridH'] = ($DirValue['gridH']) === 'gridHB' ? 'gridHA' : 'gridHB';
                $ChoiceValue = $this->timeFormat($this->Now, $ChoiceValue);
                if (strpos($ChoiceValue, '{') !== false) {
                    $ChoiceValue = $this->parseVars([], $ChoiceValue, true);
                }
                $this->replaceLabelWithL10n($ChoiceValue);
                if ($DirValue['type'] === 'checkbox') {
                    if (isset($DirValue['nonsense'])) {
                        $DirValue['ThisNonsense'] = array_flip(explode("\n", $DirValue['nonsense']));
                    }
                    if ($DirValue['HasLabels']) {
                        foreach ($DirValue['labels'] as $DirValue['ThisLabelKey'] => $DirValue['ThisLabel']) {
                            $DirValue['gridV'] = ($DirValue['gridV']) === 'gridVB' ? 'gridVA' : 'gridVB';
                            if (isset($DirValue['ThisNonsense'][$ChoiceKey . ':' . $DirValue['ThisLabelKey']])) {
                                $ThisDir['FieldOut'] .= sprintf(
                                    '<div class="gridboxcheckcell %s %s">–</div>',
                                    $DirValue['gridV'],
                                    $DirValue['gridH']
                                );
                            } else {
                                $ThisDir['FieldOut'] .= sprintf(
                                    '<div class="gridboxcheckcell %4$s %5$s"><label class="gridlabel"><input%3$s type="checkbox" class="auto" name="%1$s" id="%1$s"%2$s /></label></div>',
                                    $ThisDir['DirLangKey'] . '_' . $ChoiceKey . '_' . $DirValue['ThisLabelKey'],
                                    preg_match(
                                        '~(?:^|\n)' . preg_quote($ChoiceKey . ':' . $DirValue['ThisLabelKey']) . '(?:\n|$)~i',
                                        $this->Configuration[$CatKey][$DirKey]
                                    ) ? ' checked' : '',
                                    $ThisDir['Trigger'],
                                    $DirValue['gridV'],
                                    $DirValue['gridH']
                                );
                                $ThisDir['Reset'] .= sprintf(
                                    'document.getElementById(\'%s\').checked=%s;',
                                    $ThisDir['DirLangKey'] . '_' . $ChoiceKey . '_' . $DirValue['ThisLabelKey'],
                                    isset($this->CIDRAM['Config Defaults'][$CatKey][$DirKey]['default']) && preg_match(
                                        '~(?:^|\n)' . preg_quote($ChoiceKey . ':' . $DirValue['ThisLabelKey']) . '(?:\n|$)~i',
                                        $this->CIDRAM['Config Defaults'][$CatKey][$DirKey]['default']
                                    ) ? 'true' : 'false'
                                );
                            }
                        }
                        $ThisDir['FieldOut'] .= sprintf(
                            '<div class="gridboxitem %s %s">%s</div>',
                            $DirValue['gridH'],
                            (count($DirValue['labels']) % 2) === 0 ? 'vrte' : 'vrto',
                            $ChoiceValue
                        );
                    } else {
                        $ThisDir['FieldOut'] .= sprintf(
                            '<div class="gridboxcheckcell gridVA %5$s"><label class="gridlabel"><input%4$s type="checkbox" class="auto" name="%1$s" id="%1$s"%2$s /></label></div><div class="gridboxitem %5$s"><label for="%1$s" class="s">%3$s</label></div>',
                            $ThisDir['DirLangKey'] . '_' . $ChoiceKey,
                            preg_match(
                                '~(?:^|\n)' . preg_quote($ChoiceKey) . '(?:\n|$)~i',
                                $this->Configuration[$CatKey][$DirKey]
                            ) ? ' checked' : '',
                            $ChoiceValue,
                            $ThisDir['Trigger'],
                            $DirValue['gridH']
                        );
                        $ThisDir['Reset'] .= sprintf(
                            'document.getElementById(\'%s\').checked=%s;',
                            $ThisDir['DirLangKey'] . '_' . $ChoiceKey,
                            isset($this->CIDRAM['Config Defaults'][$CatKey][$DirKey]['default']) && preg_match(
                                '~(?:^|\n)' . preg_quote($ChoiceKey) . '(?:\n|$)~i',
                                $this->CIDRAM['Config Defaults'][$CatKey][$DirKey]['default']
                            ) ? 'true' : 'false'
                        );
                    }
                } elseif (isset($DirValue['style']) && $DirValue['style'] === 'radio') {
                    if (strpos($ChoiceValue, "\n")) {
                        $ChoiceValue = explode("\n", $ChoiceValue);
                        $ThisDir['FieldOut'] .= sprintf(
                            '<div class="gridboxstretch gridVA %5$s"><label class="gridlabel"><input%4$s type="radio" class="auto" name="%6$s" id="%1$s" value="%7$s"%2$s /></label></div><div class="gridboxstretch %5$s"><label for="%1$s"><span class="s">%3$s</span><br />%8$s</label></div>',
                            $ThisDir['DirLangKey'] . '_' . $ChoiceKey,
                            $ChoiceKey === $this->Configuration[$CatKey][$DirKey] ? ' checked' : '',
                            $ChoiceValue[0],
                            $ThisDir['Trigger'],
                            $DirValue['gridH'],
                            $ThisDir['DirLangKey'],
                            $ChoiceKey,
                            $ChoiceValue[1]
                        );
                    } else {
                        $ThisDir['FieldOut'] .= sprintf(
                            '<div class="gridboxcheckcell gridVA %5$s"><label class="gridlabel"><input%4$s type="radio" class="auto" name="%6$s" id="%1$s" value="%7$s"%2$s /></label></div><div class="gridboxitem %5$s"><label for="%1$s" class="s">%3$s</label></div>',
                            $ThisDir['DirLangKey'] . '_' . $ChoiceKey,
                            $ChoiceKey === $this->Configuration[$CatKey][$DirKey] ? ' checked' : '',
                            $ChoiceValue,
                            $ThisDir['Trigger'],
                            $DirValue['gridH'],
                            $ThisDir['DirLangKey'],
                            $ChoiceKey
                        );
                    }
                    if (
                        isset($this->CIDRAM['Config Defaults'][$CatKey][$DirKey]['default']) &&
                        $ChoiceKey === $this->CIDRAM['Config Defaults'][$CatKey][$DirKey]['default']
                    ) {
                        $ThisDir['Reset'] .= sprintf(
                            'document.getElementById(\'%s\').checked=true;',
                            $ThisDir['DirLangKey'] . '_' . $ChoiceKey
                        );
                    }
                } else {
                    $ThisDir['FieldOut'] .= sprintf(
                        '<option style="text-transform:capitalize" value="%s"%s>%s</option>',
                        $ChoiceKey,
                        $ChoiceKey === $this->Configuration[$CatKey][$DirKey] ? ' selected' : '',
                        $ChoiceValue
                    );
                    if (
                        isset($this->CIDRAM['Config Defaults'][$CatKey][$DirKey]['default']) &&
                        $ChoiceKey === $this->CIDRAM['Config Defaults'][$CatKey][$DirKey]['default']
                    ) {
                        $ThisDir['Reset'] .= sprintf(
                            'document.getElementById(\'%s_field\').value=\'%s\';',
                            $ThisDir['DirLangKey'],
                            addcslashes($ChoiceKey, "\n'\"\\")
                        );
                    }
                }
            }
            if (
                $DirValue['type'] === 'checkbox' ||
                (isset($DirValue['style']) && $DirValue['style'] === 'radio')
            ) {
                $ThisDir['FieldOut'] .= '</div>';
            } else {
                $ThisDir['SelectOther'] = !isset($DirValue['choices'][$this->Configuration[$CatKey][$DirKey]]);
                $ThisDir['FieldOut'] .= empty($DirValue['allow_other']) ? '</select>' : sprintf(
                    '<option value="Other"%1$s>%2$s</option></select><input type="text"%3$s class="flexin" name="%4$s" id="%4$s_field" value="%5$s" /></div>',
                    $ThisDir['SelectOther'] ? ' selected' : '',
                    $this->L10N->getString('label.Other'),
                    $ThisDir['SelectOther'] ? '' : ' style="display:none"',
                    $ThisDir['DirLangKeyOther'],
                    $this->Configuration[$CatKey][$DirKey]
                );
            }
        } elseif ($DirValue['type'] === 'bool') {
            $ThisDir['FieldOut'] = sprintf(
                '<select class="auto" name="%1$s" id="%1$s_field"%2$s><option value="true"%5$s>%3$s</option><option value="false"%6$s>%4$s</option></select>',
                $ThisDir['DirLangKey'],
                $ThisDir['Trigger'],
                $this->L10N->getString('field.True (True)'),
                $this->L10N->getString('field.False (False)'),
                ($this->Configuration[$CatKey][$DirKey] ? ' selected' : ''),
                ($this->Configuration[$CatKey][$DirKey] ? '' : ' selected')
            );
            $ThisDir['Reset'] .= sprintf(
                'document.getElementById(\'%s_field\').value=\'%s\';',
                $ThisDir['DirLangKey'],
                empty($this->CIDRAM['Config Defaults'][$CatKey][$DirKey]['default']) ? 'false' : 'true'
            );
        } elseif ($DirValue['type'] === 'float' || $DirValue['type'] === 'int') {
            $ThisDir['FieldAppend'] = '';
            if (isset($DirValue['step'])) {
                $ThisDir['FieldAppend'] .= ' step="' . $DirValue['step'] . '"';
            }
            $ThisDir['FieldAppend'] .= $ThisDir['Trigger'];
            if ($DirValue['type'] === 'int') {
                $ThisDir['FieldAppend'] .= ' inputmode="numeric"';
                if (isset($DirValue['pattern'])) {
                    $ThisDir['FieldAppend'] .= ' pattern="' . $DirValue['pattern'] . '"';
                } else {
                    $ThisDir['FieldAppend'] .= (!isset($DirValue['min']) || $DirValue['min'] < 0) ? ' pattern="^-?\d*$"' : ' pattern="^\d*$"';
                }
            } elseif (isset($DirValue['pattern'])) {
                $ThisDir['FieldAppend'] .= ' pattern="' . $DirValue['pattern'] . '"';
            }
            foreach (['min', 'max'] as $ThisDir['ParamTry']) {
                if (isset($DirValue[$ThisDir['ParamTry']])) {
                    $ThisDir['FieldAppend'] .= ' ' . $ThisDir['ParamTry'] . '="' . $DirValue[$ThisDir['ParamTry']] . '"';
                }
            }
            $ThisDir['FieldOut'] = sprintf(
                '<input type="number" name="%1$s" id="%1$s_field" value="%2$s"%3$s />',
                $ThisDir['DirLangKey'],
                $this->Configuration[$CatKey][$DirKey],
                $ThisDir['FieldAppend']
            );
            if (isset($this->CIDRAM['Config Defaults'][$CatKey][$DirKey]['default'])) {
                $ThisDir['Reset'] .= sprintf(
                    'document.getElementById(\'%s_field\').value=%s;',
                    $ThisDir['DirLangKey'],
                    $this->CIDRAM['Config Defaults'][$CatKey][$DirKey]['default']
                );
            }
        } elseif ($DirValue['type'] === 'url' || (
            empty($DirValue['autocomplete']) && $DirValue['type'] === 'string'
        )) {
            $ThisDir['FieldOut'] = sprintf(
                '<textarea name="%1$s" id="%1$s_field" class="half"%2$s%3$s>%4$s</textarea>',
                $ThisDir['DirLangKey'],
                $ThisDir['autocomplete'],
                $ThisDir['Trigger'],
                $this->Configuration[$CatKey][$DirKey]
            );
            if (isset($this->CIDRAM['Config Defaults'][$CatKey][$DirKey]['default'])) {
                $ThisDir['Reset'] .= sprintf(
                    'document.getElementById(\'%s_field\').value=\'%s\';',
                    $ThisDir['DirLangKey'],
                    addcslashes($this->CIDRAM['Config Defaults'][$CatKey][$DirKey]['default'], "\n'\"\\")
                );
            }
        } else {
            $ThisDir['FieldAppend'] = $ThisDir['autocomplete'] . $ThisDir['Trigger'];
            if (isset($DirValue['pattern'])) {
                $ThisDir['FieldAppend'] .= ' pattern="' . $DirValue['pattern'] . '"';
            } elseif ($DirValue['type'] === 'duration') {
                $ThisDir['FieldAppend'] .= ' pattern="^(?:(\d+(?:\.\d+)?)\s*[Ww](?:[Ee]{2}[Kk][Ss]?)?)?\s*' .
                '(?:(\d+(?:\.\d+)?)\s*[Dd](?:[Aa][Yy][Ss]?)?)?\s*(?:(\d+(?:\.\d+)?)\s*(?:\xB0|°|[Hh](?:[Oo][Uu][Rr][Ss]?)?))?\s*' .
                '(?:(\d+(?:\.\d+)?)\s*(?:\'|′|[Mm](?:[Ii][Nn][Uu]?[Tt]?[Ee]?[Ss]?)?))?\s*' .
                '(?:(\d+(?:\.\d+)?)\s*(?:\x22|″|[Ss](?:[Ee][Cc][Oo]?[Nn]?[Dd]?[Ss]?)?))?\s*' .
                '(?:(\d+(?:\.\d+)?)\s*ms)?\s*(?:(\d+(?:\.\d+)?)\s*µs)?\s*(?:(\d+(?:\.\d+)?)\s*ns)?\s*$|^(\d+(?:\.\d+)?)?$"';
            } elseif ($DirValue['type'] === 'kb') {
                $ThisDir['FieldAppend'] .= ' pattern="^\d+(\.\d+)?\s*(?:[KkMmGgTtPpOoBb]|К|к|М|м|Г|г|Т|т|П|п|Ｋ|ｋ|Ｍ|ｍ|Ｇ|ｇ|Ｔ|ｔ|Ｐ|ｐ|Б|б|Ｂ|ｂ)(?:[OoBb]|Б|б|Ｂ|ｂ)?$"';
            }
            $ThisDir['FieldOut'] = sprintf(
                '<input type="text" name="%1$s" id="%1$s_field" value="%2$s"%3$s />',
                $ThisDir['DirLangKey'],
                $this->Configuration[$CatKey][$DirKey],
                $ThisDir['FieldAppend']
            );
            if (isset($this->CIDRAM['Config Defaults'][$CatKey][$DirKey]['default'])) {
                $ThisDir['Reset'] .= sprintf(
                    'document.getElementById(\'%s_field\').value=\'%s\';',
                    $ThisDir['DirLangKey'],
                    addcslashes($this->CIDRAM['Config Defaults'][$CatKey][$DirKey]['default'], "\n'\"\\")
                );
            }
        }
        $ThisDir['FieldOut'] .= $ThisDir['Preview'];

        /** Check extension and class requirements. */
        if (!empty($DirValue['required'])) {
            $ThisDir['FieldOut'] .= '<small>';
            foreach ($DirValue['required'] as $DirValue['Requirement'] => $DirValue['Friendly']) {
                if (isset($ReqsLookupCache[$DirValue['Requirement']])) {
                    $ThisDir['FieldOut'] .= $ReqsLookupCache[$DirValue['Requirement']];
                    continue;
                }
                if (substr($DirValue['Requirement'], 0, 1) === '\\') {
                    $ReqsLookupCache[$DirValue['Requirement']] = '<br /><span class="txtGn">✔️ ' . sprintf(
                        $this->L10N->getString('label.%s is available'),
                        $DirValue['Friendly']
                    ) . '</span>';
                } elseif (extension_loaded($DirValue['Requirement'])) {
                    $DirValue['ReqVersion'] = (new \ReflectionExtension($DirValue['Requirement']))->getVersion();
                    $ReqsLookupCache[$DirValue['Requirement']] = '<br /><span class="txtGn">✔️ ' . sprintf(
                        $this->L10N->getString('label.%s is available (%s)'),
                        $DirValue['Friendly'],
                        $DirValue['ReqVersion']
                    ) . '</span>';
                } else {
                    $ReqsLookupCache[$DirValue['Requirement']] = '<br /><span class="txtRd">❌ ' . sprintf(
                        $this->L10N->getString('label.%s is not available'),
                        $DirValue['Friendly']
                    ) . '</span>';
                }
                $ThisDir['FieldOut'] .= $ReqsLookupCache[$DirValue['Requirement']];
            }
            $ThisDir['FieldOut'] .= '</small>';
        }

        /** Automatic duration hinting. */
        if ($DirValue['type'] === 'duration' && !isset($DirValue['hints'])) {
            $DirValue['hints'] = 'hints_duration';
        }

        /** Provide hints, useful for users to better understand the directive at hand. */
        if (!empty($DirValue['hints'])) {
            $ThisDir['Hints'] = $this->L10N->arrayFromL10nToArray($DirValue['hints']);
            foreach ($ThisDir['Hints'] as $ThisDir['HintKey'] => $ThisDir['HintValue']) {
                if (is_int($ThisDir['HintKey'])) {
                    $ThisDir['FieldOut'] .= sprintf("\n<br /><br />%s", $ThisDir['HintValue']);
                    continue;
                }
                $ThisDir['FieldOut'] .= sprintf(
                    "\n<br /><br /><span class=\"s\">%s</span> %s",
                    $ThisDir['HintKey'],
                    $ThisDir['HintValue']
                );
            }
        }

        /** Provide additional information, useful for users to better understand the directive at hand. */
        if (!empty($DirValue['See also']) && is_array($DirValue['See also'])) {
            $ThisDir['FieldOut'] .= sprintf("\n<br /><br />%s<ul>\n", $this->L10N->getString('label.See also'));
            foreach ($DirValue['See also'] as $DirValue['Ref key'] => $DirValue['Ref link']) {
                $ThisDir['FieldOut'] .= sprintf(
                    '<li><a dir="ltr" href="%s">%s</a></li>',
                    $DirValue['Ref link'],
                    $this->L10N->getString($DirValue['Ref key']) ?: $DirValue['Ref key']
                );
            }
            $ThisDir['FieldOut'] .= "\n</ul>";
        }

        /** Reset to defaults. */
        if ($ThisDir['Reset'] !== '') {
            if (isset($DirValue['preview'], $DirValue['default']) && $DirValue['preview'] === 'allow_other') {
                $ThisDir['Reset'] .= sprintf(
                    'hideid(\'%1$s_field\');getElementById(\'%1$s_field\').value=\'%2$s\';',
                    $ThisDir['DirLangKeyOther'],
                    $DirValue['default']
                );
            }
            $ThisDir['FieldOut'] .= sprintf(
                '<br /><br /><input type="button" class="reset" onclick="javascript:%s" value="↺ %s" />',
                $ThisDir['Reset'],
                $this->L10N->getString('field.Reset')
            );
        }

        /** Finalise configuration row. */
        $this->FE['ConfigFields'] .= $this->parseVars($ThisDir, $this->FE['ConfigRow'], true);

        /** Rebuilding in order to strip out orphaned data. */
        if (isset($NewConfig)) {
            if (!isset($NewConfig[$CatKey])) {
                $NewConfig[$CatKey] = [];
            }
            $NewConfig[$CatKey][$DirKey] = $this->Configuration[$CatKey][$DirKey];
        }
    }
    $CatKeyFriendly = $this->L10N->getString('config.' . $CatKey . '_label') ?: $CatKey;
    $this->FE['Indexes'] .= sprintf(
        '<li><span class="comCat">%s</span><ul class="comSub">%s</ul></li>',
        $CatKeyFriendly,
        $CatData
    );
    $this->FE['ConfigFields'] .= "</table></span>\n";
}

/** Cleanup. */
unset($ReqsLookupCache);

/** Update the currently active configuration file if any changes were made. */
if ($this->CIDRAM['ConfigModified'] || isset($NewConfig)) {
    if (isset($NewConfig)) {
        foreach ($this->Configuration as $CatKey => $CatValue) {
            if (substr($CatKey, 0, 5) !== 'user.') {
                continue;
            }
            $NewConfig[$CatKey] = $CatValue;
        }
        $this->Configuration = $NewConfig;
        unset($NewConfig);
    }
    if ($this->updateConfiguration()) {
        $this->FE['state_msg'] = $this->L10N->getString('response.Configuration successfully updated');
    } else {
        $this->FE['state_msg'] = $this->L10N->getString('response.Failed to update configuration');
    }
}

$this->FE['Indexes'] .= '</ul>';

/** Parse output. */
$this->FE['FE_Content'] = $this->parseVars($this->FE, $this->readFile($this->getAssetPath('_config.html')), true) . $this->CIDRAM['MenuToggle'];

/** Send output. */
echo $this->sendOutput();
