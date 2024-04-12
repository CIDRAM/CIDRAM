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
 * This file: The auxiliary rules view mode page (last modified: 2024.04.11).
 */

namespace CIDRAM\CIDRAM;

if (!isset($this->FE['Permissions'], $this->CIDRAM['QueryVars']['cidram-page']) || $this->CIDRAM['QueryVars']['cidram-page'] !== 'aux-view' || $this->FE['Permissions'] !== 1) {
    die;
}

/** Attempt to parse the auxiliary rules file. */
if (!isset($this->CIDRAM['AuxData'])) {
    $this->CIDRAM['AuxData'] = [];
    $this->YAML->process($this->readFile($this->Vault . 'auxiliary.yml'), $this->CIDRAM['AuxData']);
}

/** Create new auxiliary rule. */
if (isset($_POST['ruleName'], $_POST['conSourceType'], $_POST['conIfOrNot'], $_POST['conSourceValue'], $_POST['act'], $_POST['mtd'], $_POST['logic']) && $_POST['ruleName']) {
    /** Construct new rule array. */
    $this->CIDRAM['AuxData'][$_POST['ruleName']] = [];

    /** Construct new rule method. */
    if ($_POST['mtd'] === 'mtdReg') {
        $this->CIDRAM['AuxData'][$_POST['ruleName']]['Method'] = 'RegEx';
    } elseif ($_POST['mtd'] === 'mtdWin') {
        $this->CIDRAM['AuxData'][$_POST['ruleName']]['Method'] = 'WinEx';
    } elseif ($_POST['mtd'] === 'mtdDMA') {
        $this->CIDRAM['AuxData'][$_POST['ruleName']]['Method'] = 'Auto';
    }

    /** Construct new rule notes. */
    if (isset($_POST['Notes']) && strlen($_POST['Notes'])) {
        $this->CIDRAM['AuxData'][$_POST['ruleName']]['Notes'] = $_POST['Notes'];
    }

    /** Construct other basic rule fields (e.g., match logic, block reason, etc). */
    foreach ([
        ['Logic', 'logic'],
        ['Reason', 'ruleReason'],
        ['Target', 'ruleTarget'],
        ['From', 'from'],
        ['Expiry', 'expiry'],
        ['Status Code', 'statusCode'],
        ['Webhooks', 'webhooks']
    ] as $AuxTmp) {
        if (!empty($_POST[$AuxTmp[1]])) {
            $this->CIDRAM['AuxData'][$_POST['ruleName']][$AuxTmp[0]] = $_POST[$AuxTmp[1]];
        }
    }
    unset($AuxTmp);

    /** Process webhooks. */
    if (!empty($this->CIDRAM['AuxData'][$_POST['ruleName']]['Webhooks'])) {
        $this->arrayify($this->CIDRAM['AuxData'][$_POST['ruleName']]['Webhooks']);
        $this->CIDRAM['AuxData'][$_POST['ruleName']]['Webhooks'] = array_unique(
            array_filter($this->CIDRAM['AuxData'][$_POST['ruleName']]['Webhooks'])
        );
        if (!count($this->CIDRAM['AuxData'][$_POST['ruleName']]['Webhooks'])) {
            unset($this->CIDRAM['AuxData'][$_POST['ruleName']]['Webhooks']);
        }
    }

    /** Process other options and special flags. */
    foreach ($this->CIDRAM['Provide']['Auxiliary Rules']['Flags'] as $FlagSetName => $FlagSetValue) {
        $FlagSetKey = preg_replace('~[^A-Za-z]~', '', $FlagSetName);
        if (!isset($_POST[$FlagSetKey])) {
            continue;
        }
        foreach ($FlagSetValue as $FlagName => $FlagData) {
            if ($_POST[$FlagSetKey] === $FlagName) {
                $this->CIDRAM['AuxData'][$_POST['ruleName']][$FlagName] = true;
            }
        }
    }
    unset($FlagData, $FlagName, $FlagSetKey, $FlagSetValue, $FlagSetName);

    /** Possible actions (other than block). */
    $Actions = [
        'actWhl' => 'Whitelist',
        'actGrl' => 'Greylist',
        'actByp' => 'Bypass',
        'actLog' => 'Don\'t log',
        'actRdr' => 'Redirect',
        'actRun' => 'Run',
        'actPro' => 'Profile'
    ];

    /** Determine appropriate action for new rule. */
    $Action = $Actions[$_POST['act']] ?? 'Block';

    /** Construct new rule action array. */
    if ($Action === 'Run' && isset($_POST['ruleRun'])) {
        $this->CIDRAM['AuxData'][$_POST['ruleName']][$Action] = ['File' => $_POST['ruleRun'], 'If matches' => [], 'But not if matches' => []];
    } else {
        $this->CIDRAM['AuxData'][$_POST['ruleName']][$Action] = ['If matches' => [], 'But not if matches' => []];
    }

    /** Determine number of new rule conditions to construct. */
    $AuxConditions = count($_POST['conSourceType']);

    /** Construct new rule conditions. */
    for ($Iteration = 0; $Iteration < $AuxConditions; $Iteration++) {
        /** Skip if something went wrong during form submission, or if the fields are empty. */
        if (
            empty($_POST['conSourceType'][$Iteration]) ||
            empty($_POST['conIfOrNot'][$Iteration]) ||
            empty($_POST['conSourceValue'][$Iteration])
        ) {
            continue;
        }

        /** Where to construct into. */
        $ConstructInto = ($_POST['conIfOrNot'][$Iteration] === 'If') ? 'If matches' : 'But not if matches';

        /** Set source sub in rule if it doesn't already exist. */
        if (!isset($this->CIDRAM['AuxData'][$_POST['ruleName']][$Action][$ConstructInto][
            $_POST['conSourceType'][$Iteration]
        ])) {
            $this->CIDRAM['AuxData'][$_POST['ruleName']][$Action][$ConstructInto][
                $_POST['conSourceType'][$Iteration]
            ] = [];
        }

        /** Construct expected condition values. */
        $this->CIDRAM['AuxData'][$_POST['ruleName']][$Action][$ConstructInto][
            $_POST['conSourceType'][$Iteration]
        ][] = $_POST['conSourceValue'][$Iteration];
    }

    /** Remove possible empty array. */
    if (empty($this->CIDRAM['AuxData'][$_POST['ruleName']][$Action]['If matches'])) {
        unset($this->CIDRAM['AuxData'][$_POST['ruleName']][$Action]['If matches']);
    }

    /** Remove possible empty array. */
    if (empty($this->CIDRAM['AuxData'][$_POST['ruleName']][$Action]['But not if matches'])) {
        unset($this->CIDRAM['AuxData'][$_POST['ruleName']][$Action]['But not if matches']);
    }

    $Success = false;

    /** Reconstruct and update auxiliary rules data. */
    if ($NewAuxData = $this->YAML->reconstruct($this->CIDRAM['AuxData'])) {
        $Handle = fopen($this->Vault . 'auxiliary.yml', 'wb');
        if (is_resource($Handle)) {
            if (fwrite($Handle, $NewAuxData) !== false) {
                $Success = true;
            }
            fclose($Handle);
        }
    }

    /** Update state message. */
    $this->FE['state_msg'] = $Success ? sprintf(
        $this->L10N->getString('response.New auxiliary rule, %s, created successfully'),
        $_POST['ruleName']
    ) . '<br />' : $this->L10N->getString('response.Failed to update auxiliary rules') . '<br />';

    /** Cleanup. */
    unset($NewAuxData, $Success, $ConstructInto, $Iteration, $AuxConditions, $Action);
}

/** Prepare data for display. */
if (!$this->FE['ASYNC']) {
    /** Page initial prepwork. */
    $this->initialPrepwork($this->L10N->getString('link.Auxiliary Rules'), $this->L10N->getString('tip.Auxiliary Rules'));

    /** Populate methods and actions. */
    $this->populateMethodsActions();

    /** Process auxiliary rules. */
    $this->FE['Data'] = '      ' . (
        file_exists($this->Vault . 'auxiliary.yml') ?
        $this->generateRules() :
        '<span class="s">' . $this->L10N->getString('response.There aren_t currently any auxiliary rules') . '<br /><br /></span>'
    );

    /** Priority information about auxiliary rules. */
    $this->FE['Priority_Aux'] = sprintf(
        '%2$s%1$s%8$s%1$s(%9$s🔄%3$s🔄%4$s🔄%5$s🔄%6$s)%1$s%7$s',
        $this->L10N->Directionality !== 'rtl' ? '➡' : '⬅',
        $this->L10N->getString('label.aux.whitelist the request'),
        $this->L10N->getString('label.aux.greylist the request'),
        $this->L10N->getString('label.aux.block the request'),
        $this->L10N->getString('label.aux.bypass the request'),
        $this->L10N->getString('label.aux.don_t log the request'),
        $this->L10N->getString('label.aux.redirect the request (without blocking it)'),
        $this->L10N->getString('label.aux.run a file to handle the request'),
        $this->L10N->getString('label.aux.profile the request')
    );

    /** Priority information about status codes. */
    $this->FE['Priority_Status_Codes'] = sprintf(
        '%2$s%1$s%3$s%1$s%4$s%1$s%5$s%1$s%6$s%1$s%7$s%1$s%8$s%1$s%9$s',
        $this->L10N->Directionality !== 'rtl' ? '➡' : '⬅',
        '<code dir="ltr">silent_mode(30x)</code>',
        '<code dir="ltr">ban_override(4xx🔄5xx)</code>',
        '<code dir="ltr">rate_limiting(429)</code>',
        $this->L10N->getString('link.Auxiliary Rules') . '<code dir="ltr">(4xx🔄5xx)</code>',
        '<code dir="ltr">http_response_header_code(4xx🔄5xx)</code>',
        $this->L10N->getString('link.Auxiliary Rules') . '<code dir="ltr">(30x)</code>',
        '<code dir="ltr">nonblocked_status_code(4xx)</code>',
        $this->L10N->getString('label.Other')
    );

    /** Provides the "other options and special flags" to the default view mode new rule creation. */
    $this->FE['AuxFlagsProvides'] = '';
    foreach ($this->CIDRAM['Provide']['Auxiliary Rules']['Flags'] as $FlagSetName => $FlagSetValue) {
        $FlagKey = preg_replace('~[^A-Za-z]~', '', $FlagSetName);
        $Options = sprintf('<select name="%s" class="auto"><option value="Default State" selected>%s</option>', $FlagKey, $this->L10N->getString('label.aux.Leave it as is (don_t set anything)'));
        if (isset($FlagSetValue['Label'])) {
            $FlagSetName = $this->L10N->getString($FlagSetValue['Label']) ?: $FlagSetName;
            unset($FlagSetValue['Label']);
        }
        foreach ($FlagSetValue as $FlagName => $FlagData) {
            $Options .= sprintf('<option value="%s">%s</option>', $FlagName, isset($FlagData['Label']) ? ($this->L10N->getString($FlagData['Label']) ?: $FlagName) : $FlagName);
        }
        $Options .= '</select>';
        $this->FE['AuxFlagsProvides'] .= sprintf(
            "\n          <li>\n            <div class=\"iCntr\">\n              <div class=\"iLabl s\"><label for=\"%s\">%s</label></div><div class=\"iCntn\">%s</div>\n            </div>\n          </li>",
            $FlagKey,
            trim($FlagSetName . $this->L10N->getString('pair_separator')),
            $Options
        );
    }
    unset($FlagData, $FlagName, $Options, $FlagKey, $FlagSetValue, $FlagSetName);

    /** Calculate page load time (useful for debugging). */
    $this->FE['ProcessTime'] = microtime(true) - $_SERVER['REQUEST_TIME_FLOAT'];
    $this->FE['state_msg'] .= sprintf(
        $this->L10N->getPlural($this->FE['ProcessTime'], 'label.Page request completed in %s seconds'),
        '<span class="txtRd">' . $this->NumberFormatter->format($this->FE['ProcessTime'], 3) . '</span>'
    );

    /** Parse output. */
    $this->FE['FE_Content'] = $this->parseVars($this->FE, $this->readFile($this->getAssetPath('_aux_view.html')), true) . $this->CIDRAM['MenuToggle'];

    /** Send output. */
    echo $this->sendOutput();
} elseif (isset($_POST['auxD'], $this->CIDRAM['AuxData'][$_POST['auxD']])) {
    /** Delete an auxiliary rule. */
    unset($this->CIDRAM['AuxData'][$_POST['auxD']]);

    /** Reconstruct and update auxiliary rules data. */
    if (!$this->updateAuxData() && file_exists($this->Vault . 'auxiliary.yml')) {
        /** If auxiliary rules data reconstruction fails, or if it's empty, delete the file. */
        unlink($this->Vault . 'auxiliary.yml');
    }

    /** Confirm successful deletion. */
    echo sprintf($this->L10N->getString('response.Auxiliary rule, %s, deleted successfully'), $_POST['auxD']);
} elseif (isset($_POST['auxT'])) {
    /** Move an auxiliary rule to the top of the list. */
    $this->CIDRAM['AuxData'] = $this->swapAssociativeArrayElements($this->CIDRAM['AuxData'], $_POST['auxT'], false);
    $this->updateAuxData();
} elseif (isset($_POST['auxB'])) {
    /** Move an auxiliary rule to the bottom of the list. */
    $this->CIDRAM['AuxData'] = $this->swapAssociativeArrayElements($this->CIDRAM['AuxData'], $_POST['auxB'], true);
    $this->updateAuxData();
} elseif (isset($_POST['auxMU'])) {
    /** Move an auxiliary rule up one position. */
    $this->CIDRAM['AuxData'] = $this->swapAssociativeArrayElementsByOne($this->CIDRAM['AuxData'], $_POST['auxMU'], false);
    $this->updateAuxData();
} elseif (isset($_POST['auxMD'])) {
    /** Move an auxiliary rule down one position. */
    $this->CIDRAM['AuxData'] = $this->swapAssociativeArrayElementsByOne($this->CIDRAM['AuxData'], $_POST['auxMD'], true);
    $this->updateAuxData();
} elseif (isset($_POST['auxDR'], $this->CIDRAM['AuxData'][$_POST['auxDR']])) {
    /** Disable an auxiliary rule. */
    $this->CIDRAM['AuxData'][$_POST['auxDR']]['Disable this rule'] = true;
    $this->updateAuxData();
} elseif (isset($_POST['auxER'], $this->CIDRAM['AuxData'][$_POST['auxER']])) {
    /** Enable an auxiliary rule. */
    unset($this->CIDRAM['AuxData'][$_POST['auxER']]['Disable this rule']);
    $this->updateAuxData();
}
