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
 * This file: The auxiliary rules edit mode page (last modified: 2024.09.17).
 */

namespace CIDRAM\CIDRAM;

if (!isset($this->FE['Permissions'], $this->CIDRAM['QueryVars']['cidram-page']) || $this->CIDRAM['QueryVars']['cidram-page'] !== 'aux-edit' || $this->FE['Permissions'] !== 1) {
    die;
}

/** Page initial prepwork. */
$this->initialPrepwork($this->L10N->getString('link.Auxiliary Rules'), $this->L10N->getString('tip.Auxiliary Rules'));

/** Populate methods and actions. */
$this->populateMethodsActions();

/** Avoid max_input_vars limitations. */
$this->processMinifiedFormData('minifiedFormData');

/** Update auxiliary rules. */
if (isset($_POST['rulePriority']) && is_array($_POST['rulePriority'])) {
    $NewAuxArr = [];
    foreach ($_POST['rulePriority'] as $Iterant => $Priority) {
        if (!isset($_POST['ruleName'][$Iterant]) || !strlen($_POST['ruleName'][$Iterant]) || $_POST['ruleName'][$Iterant] === ' ') {
            continue;
        }
        $RuleName = $this->desabotage($_POST['ruleName'][$Iterant]);
        $NewAuxArr[$RuleName] = ['Priority' => $Priority];
        if (!empty($_POST['mtd'][$Iterant])) {
            if ($_POST['mtd'][$Iterant] === 'mtdReg') {
                $NewAuxArr[$RuleName]['Method'] = 'RegEx';
            } elseif ($_POST['mtd'][$Iterant] === 'mtdWin') {
                $NewAuxArr[$RuleName]['Method'] = 'WinEx';
            } elseif ($_POST['mtd'][$Iterant] === 'mtdDMA') {
                $NewAuxArr[$RuleName]['Method'] = 'Auto';
            }
        }
        if (!empty($_POST['Notes'][$Iterant])) {
            $NewAuxArr[$RuleName]['Notes'] = $this->desabotage($_POST['Notes'][$Iterant]);
        }
        if (!empty($_POST['logic'][$Iterant])) {
            $NewAuxArr[$RuleName]['Logic'] = $this->desabotage($_POST['logic'][$Iterant]);
        }
        if (!empty($_POST['ruleReason'][$Iterant])) {
            $NewAuxArr[$RuleName]['Reason'] = $this->desabotage($_POST['ruleReason'][$Iterant]);
        }
        if (!empty($_POST['ruleTarget'][$Iterant])) {
            $NewAuxArr[$RuleName]['Target'] = $this->desabotage($_POST['ruleTarget'][$Iterant]);
        }
        if (!empty($_POST['ruleRun'][$Iterant])) {
            $NewAuxArr[$RuleName]['Run'] = ['File' => $this->desabotage($_POST['ruleRun'][$Iterant])];
        }
        if (!empty($_POST['from'][$Iterant])) {
            $NewAuxArr[$RuleName]['From'] = $this->desabotage($_POST['from'][$Iterant]);
        }
        if (!empty($_POST['expiry'][$Iterant])) {
            $NewAuxArr[$RuleName]['Expiry'] = $this->desabotage($_POST['expiry'][$Iterant]);
        }
        if (!empty($_POST['statusCode'][$Iterant])) {
            $NewAuxArr[$RuleName]['Status Code'] = $this->desabotage($_POST['statusCode'][$Iterant]);
        }
        $NewAuxArr[$RuleName]['Action'] = $this->desabotage($_POST['act'][$Iterant] ?? '');
        if ($NewAuxArr[$RuleName]['Action'] !== 'actRun') {
            unset($NewAuxArr[$RuleName]['Run']);
        }
        $NewAuxArr[$RuleName]['SourceType'] = $this->desabotage($_POST['conSourceType'][$Iterant] ?? '');
        $NewAuxArr[$RuleName]['IfOrNot'] = $this->desabotage($_POST['conIfOrNot'][$Iterant] ?? '');
        $NewAuxArr[$RuleName]['SourceValue'] = $this->desabotage($_POST['conSourceValue'][$Iterant] ?? '');
        foreach ($this->CIDRAM['Provide']['Auxiliary Rules']['Flags'] as $FlagSetName => $FlagSetValue) {
            $FlagSetKey = preg_replace('~[^A-Za-z]~', '', $FlagSetName);
            if (!empty($_POST[$FlagSetKey][$Iterant])) {
                foreach ($FlagSetValue as $FlagName => $FlagData) {
                    if ($_POST[$FlagSetKey][$Iterant] === $FlagName) {
                        $NewAuxArr[$RuleName][$FlagName] = true;
                    }
                }
            }
        }
    }
    unset($FlagData, $FlagName, $FlagSetKey, $FlagSetName, $FlagSetValue);
    uasort($NewAuxArr, function ($A, $B): int {
        if ($A['Priority'] === $B['Priority']) {
            return 0;
        }
        if (!strlen($A['Priority'])) {
            return strlen($B['Priority']) ? -1 : 0;
        }
        if (!strlen($B['Priority'])) {
            return strlen($A['Priority']) ? 1 : 0;
        }
        return $A['Priority'] < $B['Priority'] ? -1 : 1;
    });
    foreach ($NewAuxArr as $Iterant => &$ThisAuxData) {
        if ($ThisAuxData['Action'] === 'actWhl') {
            $ThisAuxData['Action'] = 'Whitelist';
        } elseif ($ThisAuxData['Action'] === 'actGrl') {
            $ThisAuxData['Action'] = 'Greylist';
        } elseif ($ThisAuxData['Action'] === 'actBlk') {
            $ThisAuxData['Action'] = 'Block';
        } elseif ($ThisAuxData['Action'] === 'actByp') {
            $ThisAuxData['Action'] = 'Bypass';
        } elseif ($ThisAuxData['Action'] === 'actLog') {
            $ThisAuxData['Action'] = 'Don\'t log';
        } elseif ($ThisAuxData['Action'] === 'actRdr') {
            $ThisAuxData['Action'] = 'Redirect';
        } elseif ($ThisAuxData['Action'] === 'actRun') {
            $ThisAuxData['Action'] = 'Run';
        } elseif ($ThisAuxData['Action'] === 'actPro') {
            $ThisAuxData['Action'] = 'Profile';
        }
        if (is_array($ThisAuxData['SourceType'])) {
            foreach ($ThisAuxData['SourceType'] as $IterantInner => $DataInner) {
                if (!isset(
                    $ThisAuxData['IfOrNot'][$IterantInner],
                    $ThisAuxData['SourceValue'][$IterantInner]
                ) || $ThisAuxData['SourceValue'][$IterantInner] === '') {
                    continue;
                }
                if (!isset($ThisAuxData[$ThisAuxData['Action']])) {
                    $ThisAuxData[$ThisAuxData['Action']] = [];
                }
                if ($ThisAuxData['IfOrNot'][$IterantInner] === 'If') {
                    if (!isset($ThisAuxData[$ThisAuxData['Action']]['If matches'])) {
                        $ThisAuxData[$ThisAuxData['Action']]['If matches'] = [];
                    }
                    if (!isset($ThisAuxData[$ThisAuxData['Action']]['If matches'][$DataInner])) {
                        $ThisAuxData[$ThisAuxData['Action']]['If matches'][$DataInner] = [];
                    }
                    $ThisAuxData[$ThisAuxData['Action']]['If matches'][$DataInner][] = $ThisAuxData['SourceValue'][$IterantInner];
                } elseif ($ThisAuxData['IfOrNot'][$IterantInner] === 'Not') {
                    if (!isset($ThisAuxData[$ThisAuxData['Action']]['But not if matches'])) {
                        $ThisAuxData[$ThisAuxData['Action']]['But not if matches'] = [];
                    }
                    if (!isset($ThisAuxData[$ThisAuxData['Action']]['But not if matches'][$DataInner])) {
                        $ThisAuxData[$ThisAuxData['Action']]['But not if matches'][$DataInner] = [];
                    }
                    $ThisAuxData[$ThisAuxData['Action']]['But not if matches'][$DataInner][] = $ThisAuxData['SourceValue'][$IterantInner];
                }
            }
        }
        unset($ThisAuxData['Priority'], $ThisAuxData['SourceType'], $ThisAuxData['IfOrNot'], $ThisAuxData['SourceValue'], $ThisAuxData['Action']);
    }

    /** Reconstruct and update auxiliary rules data. */
    if ($NewAuxArr = $this->YAML->reconstruct($NewAuxArr)) {
        $Success = false;
        $Handle = fopen($this->Vault . 'auxiliary.yml', 'wb');
        if (is_resource($Handle)) {
            if (fwrite($Handle, $NewAuxArr) !== false) {
                $Success = true;
            }
            fclose($Handle);
        }
        $this->FE['state_msg'] = $this->L10N->getString($Success ? 'response.Auxiliary rules successfully updated' : 'response.Failed to update auxiliary rules') . '<br />';
    }
    unset($Success, $ThisAuxData, $DataInner, $Iterant, $IterantInner, $NewAuxArr, $Priority);
}

/** Process auxiliary rules. */
$this->FE['Data'] = '      ' . $this->generateRules(true);

/** Calculate page load time (useful for debugging). */
$this->FE['ProcessTime'] = microtime(true) - $_SERVER['REQUEST_TIME_FLOAT'];
$this->FE['state_msg'] .= sprintf(
    $this->L10N->getPlural($this->FE['ProcessTime'], 'label.Page request completed in %s seconds'),
    '<span class="txtRd">' . $this->NumberFormatter->format($this->FE['ProcessTime'], 3) . '</span>'
);

/** Parse output. */
$this->FE['FE_Content'] = $this->parseVars($this->FE, $this->readFile($this->getAssetPath('_aux_edit.html')), true) . $this->CIDRAM['MenuToggle'];

/** Send output. */
echo $this->sendOutput();
