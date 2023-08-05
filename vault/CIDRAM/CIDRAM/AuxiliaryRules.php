<?php
/**
 * This file is an optional extension of the CIDRAM package.
 * Homepage: https://cidram.github.io/
 *
 * CIDRAM COPYRIGHT 2016 and beyond by Caleb Mazalevskis (Maikuolan).
 *
 * License: GNU/GPLv2
 * @see LICENSE.txt
 *
 * This file: Methods used for auxiliary rules (last modified: 2023.08.05).
 */

namespace CIDRAM\CIDRAM;

trait AuxiliaryRules
{
    /**
     * Generates the rules data displayed on the auxiliary rules page.
     *
     * @param bool $Mode Whether view mode (false) or edit mode (true).
     * @return string The generated auxiliary rules data.
     */
    private function generateRules(bool $Mode = false): string
    {
        /** Populate output here. */
        $Output = '';

        /** JavaScript stuff to append to output after everything else. */
        $JSAppend = '';

        /** Potential sources. */
        $Sources = $this->generateLabels($this->CIDRAM['Provide']['Auxiliary Rules']['Sources']);

        /** Attempt to parse the auxiliary rules file. */
        if (!isset($this->CIDRAM['AuxData'])) {
            $this->CIDRAM['AuxData'] = [];
            $this->YAML->process($this->readFile($this->Vault . 'auxiliary.yml'), $this->CIDRAM['AuxData']);
        }

        /** Count entries (needed for offering first and last move options). */
        $Count = count($this->CIDRAM['AuxData']);

        /** Make entries safe for display at the front-end. */
        $this->recursiveReplace($this->CIDRAM['AuxData'], ['<', '>', '"'], ['&lt;', '&gt;', '&#34;']);

        if ($Mode) {
            /** Append empty rule if editing. */
            $this->CIDRAM['AuxData'][' '] = [];
            $Count++;
            $Current = 0;
        } else {
            /** Useful to know whether we're at the first or last rule (due to the "move to the ..." options. */
            $Current = 1;
        }

        /** Style class. */
        $StyleClass = 'ng1';

        /** Update button before. */
        if ($Mode) {
            $Output .= sprintf(
                '<div class="%s"><center><input type="submit" value="%s" class="auto" /></center></div>',
                $StyleClass,
                $this->L10N->getString('field_update_all')
            );
        };

        /** Iterate through the auxiliary rules. */
        foreach ($this->CIDRAM['AuxData'] as $Name => $Data) {
            /** Rule row ID. */
            $RuleClass = preg_replace('~^0+~', '', bin2hex($Name));

            /** Edit mode. */
            if ($Mode) {
                /** Update cell style. */
                $StyleClass = $StyleClass === 'ng1' ? 'ng2' : 'ng1';

                /** Rule begin and sticky. */
                $Output .= sprintf(
                    '%s<div class="%s"><div style="float:%s;position:sticky;top:0px;overflow:hidden;z-index;-1"><span class="s">%s</span></div>',
                    "\n      ",
                    $StyleClass,
                    $this->FE['FE_Align_Reverse'],
                    ($Name === ' ' && count($Data) === 0) ? '' : sprintf($this->L10N->getString('label_current_data_for'), $Name)
                );

                /** Rule name. */
                $Output .= sprintf(
                    '%1$s<div class="iCntr">%1$s  <div class="iLabl s">%3$s</div><div class="iCntn"><input type="text" name="ruleName[%4$s]" class="f400" value="%2$s" /></div></div>',
                    "\n      ",
                    $Name === ' ' ? '' : $Name,
                    $this->L10N->getString('field_new_name'),
                    $Current
                );

                /** Set rule priority (rearranges the rules). */
                $Output .= sprintf(
                    '%1$s<div class="iCntr"><div class="iLabl s">%3$s</div>%1$s  <div class="iCntn"><input type="text" name="rulePriority[%2$s]" class="f400" value="%2$s" /></div></div>',
                    "\n      ",
                    $Current,
                    $this->L10N->getString('field_execution_order')
                );

                /** Rule reason. */
                $Output .= sprintf(
                    '<div class="iCntr"><div class="iLabl s" id="%4$sruleReasonDt">%2$s</div><div class="iCntn" id="%4$sruleReasonDd"><input type="text" name="ruleReason[%3$s]" class="f400" value="%1$s" /></div></div>',
                    $Data['Reason'] ?? '',
                    $this->L10N->getString('label_aux_reason'),
                    $Current,
                    $RuleClass
                );

                /** Redirect target. */
                $Output .= sprintf(
                    '<div class="iCntr"><div class="iLabl s" id="%4$sruleTargetDt">%2$s</div><div class="iCntn" id="%4$sruleTargetDd"><input type="text" name="ruleTarget[%3$s]" class="f400" value="%1$s" /></div></div>',
                    $Data['Target'] ?? '',
                    $this->L10N->getString('label_aux_target'),
                    $Current,
                    $RuleClass
                );

                /** Run target. */
                $Output .= sprintf(
                    '<div class="iCntr"><div class="iLabl s" id="%4$sruleRunDt">%2$s</div><div class="iCntn" id="%4$sruleRunDd"><input type="text" name="ruleRun[%3$s]" class="f400" value="%1$s" /></div></div>',
                    $Data['Run']['File'] ?? '',
                    $this->L10N->getString('label_aux_run'),
                    $Current,
                    $RuleClass
                );

                /** From. */
                $Output .= sprintf(
                    '<div class="iCntr"><div class="iLabl s" id="%4$sfromDt">%2$s</div><div class="iCntn" id="%4$sfromDd"><input type="date" name="from[%3$s]" class="f400" value="%1$s" min="%5$s" /></div></div>',
                    isset($Data['From']) ? str_replace('.', '-', $Data['From']) : '',
                    $this->L10N->getString('label_aux_from'),
                    $Current,
                    $RuleClass,
                    $this->FE['Y-m-d']
                );

                /** Expiry. */
                $Output .= sprintf(
                    '<div class="iCntr"><div class="iLabl s" id="%4$sexpiryDt">%2$s</div><div class="iCntn" id="%4$sexpiryDd"><input type="date" name="expiry[%3$s]" class="f400" value="%1$s" min="%5$s" /></div></div>',
                    isset($Data['Expiry']) ? str_replace('.', '-', $Data['Expiry']) : '',
                    $this->L10N->getString('label_aux_expiry'),
                    $Current,
                    $RuleClass,
                    $this->FE['Y-m-d']
                );

                /** Status code override. */
                $Output .= sprintf('<div class="iCntr"><div class="iLabl s">%1$s</div><div class="iCntn">', $this->L10N->getString('label_aux_http_status_code_override'));
                $Output .= sprintf(
                    '<span id="%1$sstatGroupX" class="statGroup"><input type="radio" class="auto" id="%1$sstatusCodeX" name="statusCode[%3$s]" value="0" %2$s/><label for="%1$sstatusCodeX">🗙</label></span>',
                    $RuleClass,
                    empty($Data['Status Code']) ? 'checked="true" ' : '',
                    $Current
                );
                foreach ([['3', ['301', '302', '307', '308']], ['45', ['403', '410', '418', '451', '503']]] as $StatGroup) {
                    $Output .= sprintf('<span id="%1$sstatGroup%2$s" class="statGroup">', $RuleClass, $StatGroup[0]);
                    foreach ($StatGroup[1] as $StatusCode) {
                        $Output .= sprintf(
                            '<input type="radio" class="auto" id="%1$sstatusCode%2$s" name="statusCode[%4$s]" value="%2$s" %3$s/><label for="%1$sstatusCode%2$s">%2$s</label>',
                            $RuleClass,
                            $StatusCode,
                            isset($Data['Status Code']) && $Data['Status Code'] === $StatusCode ? ' checked="true"' : '',
                            $Current
                        );
                    }
                    $Output .= '</span>';
                }
                $Output .= '</div></div>';

                /** Where to get conditions from. */
                $ConditionsFrom = '';

                /** Action menu. */
                $Output .= sprintf('<div class="iCntr"><div class="iLabl"><select id="act%1$s" name="act[%1$s]" class="auto" onchange="javascript:onAuxActionChange(this.value,\'%2$s\',\'%1$s\')">', $Current, $RuleClass);
                foreach ([
                    ['actWhl', 'optActWhl', 'Whitelist'],
                    ['actGrl', 'optActGrl', 'Greylist'],
                    ['actBlk', 'optActBlk', 'Block'],
                    ['actByp', 'optActByp', 'Bypass'],
                    ['actLog', 'optActLog', 'Don\'t log'],
                    ['actRdr', 'optActRdr', 'Redirect'],
                    ['actRun', 'optActRun', 'Run'],
                    ['actPro', 'optActPro', 'Profile']
                ] as $MenuOption) {
                    $Output .= sprintf(
                        '<option value="%1$s"%2$s>%3$s</option>',
                        $MenuOption[0],
                        empty($Data[$MenuOption[2]]) ? '' : ' selected',
                        $this->FE[$MenuOption[1]]
                    );
                    if (!empty($Data[$MenuOption[2]])) {
                        $ConditionsFrom = $MenuOption[2];
                        $JSAppend .= sprintf('onAuxActionChange(\'%s\',\'%s\',\'%s\');', $MenuOption[0], $RuleClass, $Current);
                    }
                }
                if ($ConditionsFrom === '') {
                    $JSAppend .= sprintf('onAuxActionChange(\'actWhl\',\'%s\',\'%s\');', $RuleClass, $Current);
                }
                $Output .= sprintf(
                    '</select><input type="button" onclick="javascript:addCondition(\'%s\')" value="%s" class="auto" /></div>',
                    $Current,
                    $this->L10N->getString('field_add_more_conditions')
                );
                $Output .= sprintf('<div class="iCntn" id="%1$sconditions">', $Current);

                /** Populate conditions. */
                if ($ConditionsFrom && is_array($Data[$ConditionsFrom])) {
                    $Iteration = 0;
                    $ConditionFormTemplate =
                        "\n<div>" .
                        '<select name="conSourceType[%1$s][%2$s]" class="auto">%3$s</select>' .
                        '<select name="conIfOrNot[%1$s][%2$s]" class="auto"><option value="If"%6$s>=</option><option value="Not"%7$s>≠</option></select>' .
                        '<input type="text" name="conSourceValue[%1$s][%2$s]" placeholder="%4$s" class="f400" value="%5$s" /></div>';
                    foreach ([['If matches', ' selected', ''], ['But not if matches', '', ' selected']] as $ModeSet) {
                        if (isset($Data[$ConditionsFrom][$ModeSet[0]]) && is_array($Data[$ConditionsFrom][$ModeSet[0]])) {
                            foreach ($Data[$ConditionsFrom][$ModeSet[0]] as $Key => $Values) {
                                $ThisSources = str_replace('value="' . $Key . '">', 'value="' . $Key . '" selected>', $this->FE['conSources']);
                                foreach ($Values as $Condition) {
                                    $Output .= sprintf(
                                        $ConditionFormTemplate,
                                        $Current,
                                        $Iteration,
                                        $ThisSources,
                                        $this->L10N->getString('tip_condition_placeholder'),
                                        $Condition,
                                        $ModeSet[1],
                                        $ModeSet[2]
                                    );
                                    $Iteration++;
                                }
                            }
                        }
                    }
                }
                $Output .= '</div></div>';

                /** Webhook button. */
                $Output .= sprintf(
                    '<div class="iCntr"><div class="iLabl"><input type="button" onclick="javascript:addWebhook(\'%1$s\')" value="%2$s" class="auto" /></div><div class="iCntn" id="%1$swebhooks">',
                    $Current,
                    $this->L10N->getString('field_add_webhook')
                );

                /** Populate webhooks. */
                if (isset($Data['Webhooks']) && is_array($Data['Webhooks'])) {
                    $Iteration = 0;
                    foreach ($Data['Webhooks'] as $Webhook) {
                        $Output .= sprintf(
                            '<input type="text" name="webhooks[%1$s][%2$s]" placeholder="%3$s" class="f400" value="%4$s" />',
                            $Current,
                            $Iteration,
                            $this->L10N->getString('tip_condition_placeholder'),
                            $Webhook
                        );
                        $Iteration++;
                    }
                }
                $Output .= '</div></div>';

                /** Match method. */
                if (empty($Data['Method'])) {
                    $MethodData = [' selected', '', '', ''];
                } elseif ($Data['Method'] === 'RegEx') {
                    $MethodData = ['', ' selected', '', ''];
                } elseif ($Data['Method'] === 'WinEx') {
                    $MethodData = ['', '', ' selected', ''];
                } elseif ($Data['Method'] === 'Auto') {
                    $MethodData = ['', '', '', ' selected'];
                } else {
                    $MethodData = ['', '', '', ''];
                }
                $Output .= sprintf(
                    '<div class="iCntr"><div class="iLabl"><select name="mtd[%s]" class="auto"><option value="mtdStr"%s>%s</option><option value="mtdReg"%s>%s</option><option value="mtdWin"%s>%s</option><option value="mtdDMA"%s>%s</option></select></div></div>',
                    $Current,
                    $MethodData[0],
                    $this->FE['optMtdStr'],
                    $MethodData[1],
                    $this->FE['optMtdReg'],
                    $MethodData[2],
                    $this->FE['optMtdWin'],
                    $MethodData[3],
                    $this->FE['optMtdDMA']
                );

                /** Match logic. */
                if (empty($Data['Logic']) || $Data['Logic'] === 'Any') {
                    $LogicData = [' selected', ''];
                } elseif ($Data['Logic'] === 'All') {
                    $LogicData = ['', ' selected'];
                } else {
                    $LogicData = ['', ''];
                }
                $Output .= sprintf(
                    '<div class="iCntr"><div class="iLabl"><select id="logic[%1$s]" name="logic[%1$s]" class="flong"><option value="Any"%4$s>%2$s</option><option value="All"%5$s>%3$s</option></select></div></div>',
                    $Current,
                    $this->L10N->getString('label_aux_logic_any'),
                    $this->L10N->getString('label_aux_logic_all'),
                    $LogicData[0],
                    $LogicData[1]
                );

                /** Other options and special flags. */
                foreach ($this->CIDRAM['Provide']['Auxiliary Rules']['Flags'] as $FlagSetName => $FlagSet) {
                    $FlagKey = preg_replace('~[^A-Za-z]~', '', $FlagSetName);
                    $UseDefaultState = true;
                    $Options = '';
                    if (isset($FlagSet['Label'])) {
                        $FlagSetName = $this->L10N->getString($FlagSet['Label']) ?: $FlagSetName;
                        unset($FlagSet['Label']);
                    }
                    foreach ($FlagSet as $FlagName => $FlagData) {
                        if (empty($Data[$FlagName])) {
                            $Selected = '';
                        } else {
                            $UseDefaultState = false;
                            $Selected = ' selected';
                        }
                        $Options .= sprintf(
                            '<option value="%s"%s>%s</option>',
                            $FlagName,
                            $Selected,
                            isset($FlagData['Label']) ? ($this->L10N->getString($FlagData['Label']) ?: $FlagName) : $FlagName
                        );
                    }
                    $Options = sprintf(
                        '<select name="%s[%s]" class="auto"><option value="Default State"%s>%s</option>',
                        $FlagKey,
                        $Current,
                        $UseDefaultState ? ' selected' : '',
                        $this->L10N->getString('label_aux_special_default_state')
                    ) . $Options . '</select><br /><br />';
                    $Output .= sprintf(
                        '<div class="iLabl s"><label for="%s[%s]">%s</label></div><div class="iCntn">%s</div>',
                        $FlagKey,
                        $Current,
                        trim($FlagSetName . $this->L10N->getString('pair_separator')),
                        $Options
                    );
                }

                /** Rule notes. */
                $Output .= sprintf(
                    '<div class="iCntr"><div class="iLabl s">%1$s</div><div class="iCntn"><textarea id="Notes[%2$s]" name="Notes[%2$s]" class="half">%3$s</textarea></div></div>',
                    $this->L10N->getString('label_aux_notes'),
                    $Current,
                    $Data['Notes'] ?? ''
                );

                /** Finish writing new rule. */
                $Output .= '</div>';
                $Current++;
                continue;
            }

            /** Figure out which options are available for the rule (view mode). */
            $Options = ['(<span onclick="javascript:%s(\'' . $this->escapeJsInHTML($Name) . '\',\'' . $RuleClass . '\')"><code class="s">%s</code></span>)'];
            if (empty($Data['Disable this rule'])) {
                $Options['disableRule'] = sprintf($Options[0], 'disableRule', '<span style="position:relative;top:-2px" class="txtRd">⏸</span>' . $this->L10N->getString('label_aux_special_disable'));
            } else {
                $Options['enableRule'] = sprintf($Options[0], 'enableRule', '<span style="position:relative;top:-3px" class="txtGn">▶</span>' . $this->L10N->getString('label_aux_special_enable'));
            }
            if ($Count > 1) {
                if ($Current !== 1) {
                    if ($Current !== 2) {
                        $Options['moveUp'] = sprintf($Options[0], 'moveUp', '<span class="txtBl">↑</span>' . $this->L10N->getString('label_aux_move_up'));
                    }
                    $Options['moveToTop'] = sprintf($Options[0], 'moveToTop', '<span class="txtBl">↑↑</span>' . $this->L10N->getString('label_aux_move_top'));
                }
                if ($Current !== $Count) {
                    if ($Current !== ($Count - 1)) {
                        $Options['moveDown'] = sprintf($Options[0], 'moveDown', '<span class="txtBl">↓</span>' . $this->L10N->getString('label_aux_move_down'));
                    }
                    $Options['moveToBottom'] = sprintf($Options[0], 'moveToBottom', '<span class="txtBl">↓↓</span>' . $this->L10N->getString('label_aux_move_bottom'));
                }
            }
            unset($Options[0]);
            $Options['exportRule'] = sprintf(
                '(<span onclick="javascript:{document.getElementById(\'xprtName\').value=\'%s\';document.getElementById(\'xprtForm\').submit()}"><code class="s">%s</code></span>)',
                $this->escapeJsInHTML($Name),
                $this->L10N->getString('label_export')
            );
            $Options['delRule'] = sprintf(
                '(<span onclick="javascript:confirm(\'%s\')&&delRule(\'' . $this->escapeJsInHTML($Name) . '\',\'' . $RuleClass . '\')"><code class="s"><span class="txtRd">⌧</span>%s</code></span>)',
                $this->escapeJsInHTML(sprintf($this->L10N->getString('confirm_delete'), $Name)),
                $this->L10N->getString('field_delete')
            );
            $Options = implode(' ', $Options);
            if (substr($Options, 0, 1) === '(' && substr($Options, -1) === ')') {
                $Options = sprintf(
                    '<span style="display:inline-block">(<span id="heaven%1$s" class="scaleXToOne" onclick="javascript:heavenToggle(\'%1$s\')"><code style="s">☰</code></span><span id="hidden%1$s" class="scaleXToZero">%2$s</span>)</span>',
                    $RuleClass,
                    substr($Options, 1, -1)
                );
            }
            $Options = ' – ' . $Options;

            /** Begin generating rule output. */
            $Output .= sprintf(
                '%1$s<li class="%2$s"><span class="comCat s">%3$s</span>%4$s%5$s%1$s  <ul class="comSub">',
                "\n      ",
                $RuleClass . (empty($Data['Disable this rule']) ? '' : ' hB fBlur"'),
                $Name,
                $Options,
                isset($Data['Notes']) ? '<div class="iCntn"><em>' . str_replace(['<', '>', "\n"], ['&lt;', '&gt;', "<br />\n"], $Data['Notes']) . '</em></div>' : ''
            );

            /** Additional details about the rule to print to the page (e.g., detailed block reason). */
            foreach ([
                ['Reason', 'label_aux_reason'],
                ['Target', 'label_aux_target'],
                ['Webhooks', 'label_aux_webhooks']
            ] as $Details) {
                if (!empty($Data[$Details[0]]) && $Label = $this->L10N->getString($Details[1])) {
                    if (is_array($Data[$Details[0]])) {
                        $Data[$Details[0]] = implode('</div><div class="iCntn">', $Data[$Details[0]]);
                    }
                    $Output .= "\n          <li><div class=\"iCntr\"><div class=\"iLabl s\">" . $Label . '</div><div class="iCntn">' . $Data[$Details[0]] . '</div></div></li>';
                }
            }

            /** Populate from and expiry. */
            foreach ([
                ['From', 'label_aux_from'],
                ['Expiry', 'label_aux_expiry']
            ] as $Details) {
                if (!empty($Data[$Details[0]]) && $Label = $this->L10N->getString($Details[1])) {
                    if (preg_match('~^(\d{4})[.-](\d\d)[.-](\d\d)$~', $Data[$Details[0]], $Details[2])) {
                        $Data[$Details[0]] .= ' (' . $this->relativeTime(
                            mktime(0, 0, 0, (int)$Details[2][2], (int)$Details[2][3], (int)$Details[2][1])
                        ) . ')';
                    }
                    $Output .= "\n          <li><div class=\"iCntr\"><div class=\"iLabl s\">" . $Label . '</div><div class="iCntn">' . $Data[$Details[0]] . '</div></div></li>';
                }
            }

            /** Display the status code to be applied. */
            if (!empty($Data['Status Code']) && $Data['Status Code'] > 200 && $StatusCode = $this->getStatusHTTP($Data['Status Code'])) {
                $Output .= "\n          <li><div class=\"iCntr\"><div class=\"iLabl s\">" . $this->L10N->getString('label_aux_http_status_code_override') . '</div><div class="iCntn">' . $Data['Status Code'] . ' ' . $StatusCode . '</div></div></li>';
            }

            /** Iterate through actions. */
            foreach ([
                ['Whitelist', 'optActWhl'],
                ['Greylist', 'optActGrl'],
                ['Block', 'optActBlk'],
                ['Bypass', 'optActByp'],
                ['Don\'t log', 'optActLog'],
                ['Redirect', 'optActRdr'],
                ['Run', 'optActRun'],
                ['Profile', 'optActPro']
            ] as $Action) {
                /** Skip action if the current rule doesn't use this action. */
                if (empty($Data[$Action[0]])) {
                    continue;
                }

                /** Show the appropriate label for this action. */
                $Output .= sprintf(
                    '%1$s<li>%1$s  <div class="iCntr">%1$s    <div class="iLabl s">%2$s</div>',
                    "\n          ",
                    $this->FE[$Action[1]]
                );

                /** List all "not equals" conditions . */
                if (!empty($Data[$Action[0]]['But not if matches'])) {
                    /** Iterate through sources. */
                    foreach ($Data[$Action[0]]['But not if matches'] as $Source => $Values) {
                        $ThisSource = $Sources[$Source] ?? $Source;
                        if (!is_array($Values)) {
                            $Values = [$Values];
                        }
                        foreach ($Values as $Value) {
                            if ($Value === '') {
                                $Value = '&nbsp;';
                            }
                            if (!isset($Data['Method'])) {
                                $Operator = $this->operatorFromAuxValue($Value, true);
                            } elseif ($Data['Method'] === 'RegEx') {
                                $Operator = '≇';
                            } elseif ($Data['Method'] === 'WinEx') {
                                $Operator = strpos($Value, '*') === false ? '≠' : '≉';
                            } elseif ($Data['Method'] === 'Auto') {
                                $Boundary = substr($Value, 0, 1);
                                if (
                                    !preg_match('~^[\0-\x20\dA-Za-z\xC0-\xFF]$~', $Boundary) &&
                                    preg_match($Boundary === '~' ? '/^' . $Boundary . '.+' . $Boundary . 'i?m?s?x?A?D?S?U?u?n?$/' : '~^' . $Boundary . '.*' . $Boundary . 'i?m?s?x?A?D?S?U?u?n?$~', $Value)
                                ) {
                                    $Operator = '≇';
                                } else {
                                    $Operator = strpos($Value, '*') === false ? '≠' : '≉';
                                }
                            } else {
                                $Operator = $this->operatorFromAuxValue($Value, true);
                            }
                            $Output .= "\n              <div class=\"iCntn\"><span>" . $ThisSource . '</span> ' . $Operator . ' <code>' . $Value . '</code></div>';
                        }
                    }
                }

                /** List all "equals" conditions . */
                if (!empty($Data[$Action[0]]['If matches'])) {
                    /** Iterate through sources. */
                    foreach ($Data[$Action[0]]['If matches'] as $Source => $Values) {
                        $ThisSource = isset($Sources[$Source]) ? $Sources[$Source] : $Source;
                        if (!is_array($Values)) {
                            $Values = [$Values];
                        }
                        foreach ($Values as $Value) {
                            if ($Value === '') {
                                $Value = '&nbsp;';
                            }
                            if (!isset($Data['Method'])) {
                                $Operator = $this->operatorFromAuxValue($Value);
                            } elseif ($Data['Method'] === 'RegEx') {
                                $Operator = '≅';
                            } elseif ($Data['Method'] === 'WinEx') {
                                $Operator = strpos($Value, '*') === false ? '=' : '≈';
                            } elseif ($Data['Method'] === 'Auto') {
                                $Boundary = substr($Value, 0, 1);
                                if (
                                    !preg_match('~^[\0-\x20\dA-Za-z\xC0-\xFF]$~', $Boundary) &&
                                    preg_match($Boundary === '~' ? '/^' . $Boundary . '.+' . $Boundary . 'i?m?s?x?A?D?S?U?u?n?$/' : '~^' . $Boundary . '.*' . $Boundary . 'i?m?s?x?A?D?S?U?u?n?$~', $Value)
                                ) {
                                    $Operator = '≅';
                                } else {
                                    $Operator = strpos($Value, '*') === false ? '=' : '≈';
                                }
                            } else {
                                $Operator = $this->operatorFromAuxValue($Value);
                            }
                            $Output .= "\n              <div class=\"iCntn\"><span>" . $ThisSource . '</span> ' . $Operator . ' <code>' . $Value . '</code></div>';
                        }
                    }
                }

                /** Finish writing conditions list. */
                $Output .= "\n            </div>\n          </li>";
            }

            /** Cite the file to run. */
            if (!empty($Data['Run']['File']) && $Label = $this->L10N->getString('label_aux_run')) {
                $Output .= "\n            <li><div class=\"iCntr\"><div class=\"iLabl s\">" . $Label . '</div><div class="iCntn">' . $Data['Run']['File'] . '</div></div></li>';
            }

            /** Display other options and special flags. */
            $Flags = [];
            foreach ($this->CIDRAM['Provide']['Auxiliary Rules']['Flags'] as $FlagSetName => $FlagSet) {
                foreach ($FlagSet as $FlagName => $FlagData) {
                    if (!is_array($FlagData) || empty($FlagData['Label'])) {
                        continue;
                    }
                    $Label = isset($FlagData['Label']) ? ($this->L10N->getString($FlagData['Label']) ?: $FlagData['Label']) : $FlagData['Label'];
                    if (!empty($Data[$FlagName])) {
                        $Flags[] = $Label;
                    }
                }
            }
            if (count($Flags)) {
                $Output .= "\n          <li><div class=\"iCntr\"><div class=\"iLabl s\">" . $this->L10N->getString('label_aux_special') . '</div><div class="iCntn">' . implode('<br />', $Flags) . '</div></div></li>';
            }

            /** Show the method to be used. */
            $Output .= "\n          <li><div class=\"iCntr\"><div class=\"iLabl\"><em>";
            if (!isset($Data['Method'])) {
                $Output .= $this->FE['optMtdStr'];
            } elseif ($Data['Method'] === 'RegEx') {
                $Output .= $this->FE['optMtdReg'];
            } elseif ($Data['Method'] === 'WinEx') {
                $Output .= $this->FE['optMtdWin'];
            } elseif ($Data['Method'] === 'Auto') {
                $Output .= $this->FE['optMtdDMA'];
            } else {
                $Output .= $this->FE['optMtdStr'];
            }
            $Output .= '</em></div></div></li>';

            /** Describe matching logic used. */
            $Output .= "\n          <li><div class=\"iCntr\"><div class=\"iLabl\"><em>" . $this->L10N->getString(
                (!empty($Data['Logic']) && $Data['Logic'] !== 'Any') ? 'label_aux_logic_all' : 'label_aux_logic_any'
            ) . '</em></div></div></li>';

            /** Finish writing new rule. */
            $Output .= "\n        </ul>\n      </li>";
            $Current++;
        }

        /** Update button after. */
        if ($Mode) {
            $StyleClass = $StyleClass === 'ng1' ? 'ng2' : 'ng1';
            $Output .= sprintf(
                '<div class="%s"><center><input type="submit" value="%s" class="auto" /></center></div>',
                $StyleClass,
                $this->L10N->getString('field_update_all')
            );
        };

        /** Exit with generated output. */
        return $Output . '<script type="text/javascript">' . $JSAppend . '</script>';
    }

    /**
     * Generate select options from an associative array.
     *
     * @param array $Options An associative array of the options to generate.
     * @param bool $JS Whether generating for JavaScript.
     * @return string The generated options.
     */
    private function generateOptions(array $Options, bool $JS = false): string
    {
        $Output = '';
        foreach ($Options as $Value => $Label) {
            if (is_array($Label)) {
                $Output .= $this->generateOptions($Label, $JS);
                continue;
            }
            $Label = $this->L10N->getString($Label) ?: $Label;
            if ($JS) {
                $Output .= "\n  x = document.createElement('option'),\n  x.setAttribute('value', '" . $Value . "'),\n  x.innerHTML = '" . $Label . "',\n  t.appendChild(x),";
            } else {
                $Output .= '<option value="' . $Value . '">' . $Label . '</option>';
            }
        }
        return $Output;
    }

    /**
     * Generate labels from an associative array.
     *
     * @param array $Options An associative array of the labels to generate.
     * @return array The generated labels.
     */
    private function generateLabels(array $Options): array
    {
        $Output = [];
        foreach ($Options as $Value => $Label) {
            if (is_array($Label)) {
                $Output = array_merge($Output, $this->generateLabels($Label));
                continue;
            }
            $Label = $this->L10N->getString($Label) ?: $Label;
            $Output[$Value] = $Label;
        }
        return $Output;
    }

    /**
     * Procedure to populate methods, actions, and sources used by the
     * auxiliary rules page.
     *
     * @return void
     */
    private function populateMethodsActions(): void
    {
        /** Append JavaScript specific to the auxiliary rules page. */
        $this->FE['JS'] .= $this->parseVars(
            ['tip_condition_placeholder' => $this->L10N->getString('tip_condition_placeholder')],
            $this->readFile($this->getAssetPath('auxiliary.js'))
        );

        /** Populate methods. */
        $this->FE['optMtdStr'] = sprintf($this->L10N->getString('label_aux_menu_method'), $this->L10N->getString('label_aux_mtdStr'));
        $this->FE['optMtdReg'] = sprintf($this->L10N->getString('label_aux_menu_method'), $this->L10N->getString('label_aux_mtdReg'));
        $this->FE['optMtdWin'] = sprintf($this->L10N->getString('label_aux_menu_method'), $this->L10N->getString('label_aux_mtdWin'));
        $this->FE['optMtdDMA'] = $this->L10N->getString('label_aux_mtdDMA');

        /** Populate actions. */
        $this->FE['optActWhl'] = sprintf($this->L10N->getString('label_aux_menu_action'), $this->L10N->getString('label_aux_actWhl'));
        $this->FE['optActGrl'] = sprintf($this->L10N->getString('label_aux_menu_action'), $this->L10N->getString('label_aux_actGrl'));
        $this->FE['optActBlk'] = sprintf($this->L10N->getString('label_aux_menu_action'), $this->L10N->getString('label_aux_actBlk'));
        $this->FE['optActByp'] = sprintf($this->L10N->getString('label_aux_menu_action'), $this->L10N->getString('label_aux_actByp'));
        $this->FE['optActLog'] = sprintf($this->L10N->getString('label_aux_menu_action'), $this->L10N->getString('label_aux_actLog'));
        $this->FE['optActRdr'] = sprintf($this->L10N->getString('label_aux_menu_action'), $this->L10N->getString('label_aux_actRdr'));
        $this->FE['optActRun'] = sprintf($this->L10N->getString('label_aux_menu_action'), $this->L10N->getString('label_aux_actRun'));
        $this->FE['optActPro'] = sprintf($this->L10N->getString('label_aux_menu_action'), $this->L10N->getString('label_aux_actPro'));

        /** Populate sources. */
        $this->FE['conSources'] = $this->generateOptions($this->CIDRAM['Provide']['Auxiliary Rules']['Sources']);

        /** Populate sources for JavaScript. */
        $this->FE['conSourcesJS'] = $this->generateOptions($this->CIDRAM['Provide']['Auxiliary Rules']['Sources'], true);
    }

    /**
     * Reconstructs and updates the auxiliary rules data.
     *
     * @return bool True when succeeded.
     */
    private function updateAuxData(): bool
    {
        if (($NewAuxArr = $this->YAML->reconstruct($this->CIDRAM['AuxData'])) && strlen($NewAuxArr) > 2) {
            $Handle = fopen($this->Vault . 'auxiliary.yml', 'wb');
            if ($Handle !== false) {
                fwrite($Handle, $NewAuxArr);
                fclose($Handle);
                return true;
            }
        }
        return false;
    }

    /**
     * Recursively replace strings by reference.
     *
     * @param string|array $In The data to be worked with.
     * @param string|array $What What to replace.
     * @param string|array $With What to replace it with.
     * @return void
     */
    private function recursiveReplace(&$In, $What, $With): void
    {
        if (is_string($In)) {
            $In = str_replace($What, $With, $In);
        }
        if (is_array($In)) {
            foreach ($In as &$Item) {
                $this->recursiveReplace($Item, $What, $With);
            }
        }
    }

    /**
     * Swaps an element in an associative array to the top or the bottom.
     *
     * @param array $Arr The array to be worked.
     * @param string $Target The key of the element to be swapped.
     * @param bool $Direction False for top, true for bottom.
     * @return array The worked array.
     */
    private function swapAssociativeArrayElements(array $Arr, string $Target, bool $Direction): array
    {
        if (!isset($Arr[$Target])) {
            return $Arr;
        }
        $Split = [$Target => $Arr[$Target]];
        unset($Arr[$Target]);
        $Arr = $Direction ? array_merge($Arr, $Split) : array_merge($Split, $Arr);
        return $Arr;
    }

    /**
     * Swaps the position of an element in an associative array up or down by one.
     *
     * @param array $Arr The associative array to be worked.
     * @param string $Target The key of the element to be swapped.
     * @param bool $Direction False for up, true for down.
     * @return array The worked array.
     */
    private function swapAssociativeArrayElementsByOne(array $Arr, string $Target, bool $Direction): array
    {
        if (!isset($Arr[$Target])) {
            return $Arr;
        }
        $Keys = [];
        $Values = [];
        $Index = 0;
        foreach ($Arr as $Key => $Value) {
            $Keys[$Index] = $Key;
            $Values[$Index] = $Value;
            if ($Key === $Target) {
                $TargetIndex = $Index;
            }
            $Index++;
        }
        if (!isset($TargetIndex, $Keys[$TargetIndex], $Values[$TargetIndex])) {
            return $Arr;
        }
        if ($Direction) {
            if (!isset($Keys[$TargetIndex + 1], $Values[$TargetIndex + 1])) {
                return $Arr;
            }
            [$Keys[$TargetIndex], $Keys[$TargetIndex + 1]] = [$Keys[$TargetIndex + 1], $Keys[$TargetIndex]];
            [$Values[$TargetIndex], $Values[$TargetIndex + 1]] = [$Values[$TargetIndex + 1], $Values[$TargetIndex]];
        } else {
            if (!isset($Keys[$TargetIndex - 1], $Values[$TargetIndex - 1])) {
                return $Arr;
            }
            [$Keys[$TargetIndex], $Keys[$TargetIndex - 1]] = [$Keys[$TargetIndex - 1], $Keys[$TargetIndex]];
            [$Values[$TargetIndex], $Values[$TargetIndex - 1]] = [$Values[$TargetIndex - 1], $Values[$TargetIndex]];
        }
        return array_combine($Keys, $Values);
    }
}
