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
 * This file: Methods used for auxiliary rules (last modified: 2026.03.22).
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
        $Count = \count($this->CIDRAM['AuxData']);

        /** Make entries safe for display at the front-end. */
        $this->recursiveReplace($this->CIDRAM['AuxData'], ['<', '>', '"'], ['&lt;', '&gt;', '&#34;']);

        /** Style class. */
        $StyleClass = 'ng1';

        if ($Mode) {
            /** Append empty rule if editing. */
            $this->CIDRAM['AuxData'][' '] = [];
            $Count++;
            $Current = 0;

            /** Update button before. */
            $Output .= \sprintf(
                '<div class="%s center flexstretch"><input type="submit" value="%s" class="auto" /></div>',
                $StyleClass,
                $this->L10N->getString('field.Update all')
            );
        } else {
            /** Useful to know whether we're at the first or last rule (due to the "move to the ..." options. */
            $Current = 1;

            /** Head pseudo-position. */
            $Output .= \sprintf(
                '%s<div class="rulePseudoPos" name="_pseudo0">%s</div>',
                "\n        ",
                $this->L10N->getString('label.aux.Drop the rule here to move it to this position, or onto another rule to swap positions')
            );
        }

        /** Iterate through the auxiliary rules. */
        foreach ($this->CIDRAM['AuxData'] as $Name => $Data) {
            /** Rule row ID. */
            $RuleClass = \preg_replace('~^0+~', '', \bin2hex($Name));

            /** Edit mode. */
            if ($Mode) {
                /** Update cell style. */
                $StyleClass = $StyleClass === 'ng1' ? 'ng2' : 'ng1';

                /** Rule begin and sticky. */
                $Output .= \sprintf(
                    '%s<div class="%s flexstretch"><div style="float:%s;position:sticky;top:0px;overflow:hidden;z-index;-1"><span class="s">%s</span></div>',
                    "\n        ",
                    $StyleClass,
                    $this->FE['FE_Align_Reverse'],
                    ($Name === ' ' && \count($Data) === 0) ? '' : \sprintf($this->L10N->getString('label.Current data for %s'), $Name)
                );

                /** Rule name. */
                $Output .= \sprintf(
                    '%1$s<label><div class="iCntr"><div class="iLabl s">%3$s</div><div class="iCntn"><input type="text" name="ruleName[%4$s]" class="f400" value="%2$s" /></div></div></label>',
                    "\n        ",
                    $Name === ' ' ? '' : $Name,
                    $this->L10N->getString('field.New name'),
                    $Current
                );

                /** Set rule priority (rearranges the rules). */
                $Output .= \sprintf(
                    '%1$s<label><div class="iCntr"><div class="iLabl s">%2$s</div><div class="iCntn"><input type="number" name="rulePriority[%3$s]" class="f400" value="%3$s" /></div></div></label>',
                    "\n        ",
                    $this->L10N->getString('field.Execution order'),
                    $Current
                );

                /** Rule reason. */
                $Output .= \sprintf(
                    '%1$s<label><div class="iCntr"><div class="iLabl s" id="%5$sruleReasonDt">%3$s</div><div class="iCntn" id="%5$sruleReasonDd"><input type="text" name="ruleReason[%4$s]" class="f400" value="%2$s" /></div></div></label>',
                    "\n        ",
                    $Data['Reason'] ?? '',
                    $this->L10N->getString('label.aux.The reason given to the user when blocked'),
                    $Current,
                    $RuleClass
                );

                /** Redirect target. */
                $Output .= \sprintf(
                    '%1$s<label><div class="iCntr"><div class="iLabl s" id="%5$sruleTargetDt">%3$s</div><div class="iCntn" id="%5$sruleTargetDd"><input type="text" name="ruleTarget[%4$s]" class="f400" value="%2$s" /></div></div></label>',
                    "\n        ",
                    $Data['Target'] ?? '',
                    $this->L10N->getString('label.aux.Where to redirect the request'),
                    $Current,
                    $RuleClass
                );

                /** Run target. */
                $Output .= \sprintf(
                    '%1$s<label><div class="iCntr"><div class="iLabl s" id="%5$sruleRunDt">%3$s</div><div class="iCntn" id="%5$sruleRunDd"><input type="text" name="ruleRun[%4$s]" class="f400" value="%2$s" /></div></div></label>',
                    "\n        ",
                    $Data['Run']['File'] ?? '',
                    $this->L10N->getString('label.aux.The name of the file to run'),
                    $Current,
                    $RuleClass
                );

                /** From. */
                $Output .= \sprintf(
                    '%1$s<label><div class="iCntr"><div class="iLabl s" id="%5$sfromDt">%3$s</div><div class="iCntn" id="%5$sfromDd"><input type="date" name="from[%4$s]" class="f400" value="%2$s" /></div></div></label>',
                    "\n        ",
                    isset($Data['From']) ? \str_replace('.', '-', $Data['From']) : '',
                    $this->L10N->getString('label.aux.When the rule should begin (optional)'),
                    $Current,
                    $RuleClass
                );

                /** Expiry. */
                $Output .= \sprintf(
                    '%1$s<label><div class="iCntr"><div class="iLabl s" id="%5$sexpiryDt">%3$s</div><div class="iCntn" id="%5$sexpiryDd"><input type="date" name="expiry[%4$s]" class="f400" value="%2$s" /></div></div></label>',
                    "\n        ",
                    isset($Data['Expiry']) ? \str_replace('.', '-', $Data['Expiry']) : '',
                    $this->L10N->getString('label.aux.When the rule should expire (optional)'),
                    $Current,
                    $RuleClass
                );

                /** Status code override. */
                $Output .= \sprintf('<div class="iCntr"><div class="iLabl s">%1$s</div><div class="iCntn">', $this->L10N->getString('label.aux.HTTP status code override')) . \sprintf(
                    '<span id="%1$sstatGroupX" class="statGroup"><input type="radio" class="auto" id="%1$sstatusCodeX" name="statusCode[%3$s]" value="0" %2$s/><label for="%1$sstatusCodeX">🗙</label></span>',
                    $RuleClass,
                    empty($Data['Status Code']) ? 'checked="true" ' : '',
                    $Current
                );
                foreach ([['3', ['301', '302', '307', '308']], ['45', ['403', '404', '410', '418', '451', '503']]] as $StatGroup) {
                    $Output .= \sprintf('<span id="%1$sstatGroup%2$s" class="statGroup">', $RuleClass, $StatGroup[0]);
                    foreach ($StatGroup[1] as $StatusCode) {
                        $Output .= \sprintf(
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
                $Output .= \sprintf('<div class="iCntr"><div class="iLabl"><select id="act%1$s" name="act[%1$s]" title="%3$s" class="auto" onchange="javascript:onAuxActionChange(this.value,\'%2$s\',\'%1$s\')">', $Current, $RuleClass, $this->L10N->getString('label.Action'));
                foreach ([
                    ['actWhl', 'optActWhl', 'Whitelist'],
                    ['actGrl', 'optActGrl', 'Greylist'],
                    ['actBlk', 'optActBlk', 'Block'],
                    ['actByp', 'optActByp', 'Bypass'],
                    ['actRdr', 'optActRdr', 'Redirect'],
                    ['actRun', 'optActRun', 'Run'],
                    ['actPro', 'optActPro', 'Profile']
                ] as $MenuOption) {
                    $Output .= \sprintf(
                        '<option value="%1$s"%2$s>%3$s</option>',
                        $MenuOption[0],
                        empty($Data[$MenuOption[2]]) ? '' : ' selected',
                        $this->FE[$MenuOption[1]]
                    );
                    if (!empty($Data[$MenuOption[2]])) {
                        $ConditionsFrom = $MenuOption[2];
                        $JSAppend .= \sprintf('onAuxActionChange(\'%s\',\'%s\',\'%s\');', $MenuOption[0], $RuleClass, $Current);
                    }
                }
                if ($ConditionsFrom === '') {
                    $JSAppend .= \sprintf('onAuxActionChange(\'actWhl\',\'%s\',\'%s\');', $RuleClass, $Current);
                }
                $Output .= \sprintf(
                    '</select><input type="button" onclick="javascript:addCondition(\'%s\', this.parentElement.parentElement.nextSibling.nextSibling.firstChild.firstChild.value)" value="%s" class="auto" /><br /><span class="suggestsActive"><small>%s</small></span></div>',
                    $Current,
                    $this->L10N->getString('field.Add more conditions'),
                    $this->L10N->getString('tip.Actions menu')
                );
                $Output .= \sprintf('<div class="iCntn" id="%1$sconditions">', $Current);

                /** Populate conditions. */
                if ($ConditionsFrom && \is_array($Data[$ConditionsFrom])) {
                    if (empty($Data['Method'])) {
                        $PosSymbol = '=';
                        $NegSymbol = '≠';
                    } elseif ($Data['Method'] === 'RegEx') {
                        $PosSymbol = '≅';
                        $NegSymbol = '≇';
                    } elseif ($Data['Method'] === 'WinEx') {
                        $PosSymbol = '≈';
                        $NegSymbol = '≉';
                    } else {
                        $PosSymbol = $Data['Method'] === 'Auto' ? '≟' : '=';
                        $NegSymbol = '≠';
                    }
                    $Iteration = 0;
                    $ConditionFormTemplate = "\n        " .
                        '<div class="flexrow"><select name="conSourceType[%1$s][%2$s]" title="%10$s" class="auto" onchange="javascript:getInputSuggestions(this)">%3$s</select>' .
                        '<select name="conIfOrNot[%1$s][%2$s]" title="{label.Operator}" class="auto"><option value="If" class="ifOrNot"%6$s>%8$s</option><option value="Not" class="ifOrNot"%7$s>%9$s</option></select>' .
                        '<input type="text" name="conSourceValue[%1$s][%2$s]" title="%11$s" placeholder="%4$s" class="flexin" value="%5$s" onfocus="javascript:getInputSuggestions(this.previousElementSibling.previousElementSibling)" /></div><div class="suggestsInactive s"></div>';
                    foreach ([['If matches', ' selected', ''], ['But not if matches', '', ' selected']] as $ModeSet) {
                        if (isset($Data[$ConditionsFrom][$ModeSet[0]]) && \is_array($Data[$ConditionsFrom][$ModeSet[0]])) {
                            foreach ($Data[$ConditionsFrom][$ModeSet[0]] as $Key => $Values) {
                                $ThisSources = \str_replace('value="' . $Key . '">', 'value="' . $Key . '" selected>', $this->FE['conSources']);
                                foreach ($Values as $Condition) {
                                    $Output .= \sprintf(
                                        $ConditionFormTemplate,
                                        $Current,
                                        $Iteration,
                                        $ThisSources,
                                        $this->L10N->getString('tip.Specify a value, or leave blank to disregard'),
                                        $Condition,
                                        $ModeSet[1],
                                        $ModeSet[2],
                                        $PosSymbol,
                                        $NegSymbol,
                                        $this->L10N->getString('label.Data source'),
                                        $this->L10N->getString('label.Data value')
                                    );
                                    $Iteration++;
                                }
                            }
                        }
                    }
                }
                $Output .= '</div></div>';

                /** Webhook button. */
                $Output .= \sprintf(
                    '<div class="iCntr"><div class="iLabl"><input type="button" onclick="javascript:addWebhook(\'%1$s\')" value="%2$s" class="auto" /><br /><span class="suggestsActive"><small>%3$s</small></span></div><div class="iCntn" id="%1$swebhooks">',
                    $Current,
                    $this->L10N->getString('field.Add webhook'),
                    $this->L10N->getString('tip.Auxiliary Rules Webhooks')
                );

                /** Populate webhooks. */
                if (isset($Data['Webhooks']) && \is_array($Data['Webhooks'])) {
                    $Iteration = 0;
                    foreach ($Data['Webhooks'] as $Webhook) {
                        $Output .= \sprintf(
                            '<input type="text" name="webhooks[%1$s][%2$s]" placeholder="%3$s" class="txtf" value="%4$s" />',
                            $Current,
                            $Iteration,
                            $this->L10N->getString('tip.Specify a URL, or leave blank to disregard'),
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
                $Output .= \sprintf(
                    '<div class="iCntr"><div class="iLabl"><select name="mtd[%s]" title="%s" class="auto" onchange="javascript:changeIfOrNotEditMode(this)"><option value="mtdStr"%s>%s</option><option value="mtdReg"%s>%s</option><option value="mtdWin"%s>%s</option><option value="mtdDMA"%s>%s</option></select><br /><span class="suggestsActive"><small>%s</small></span></div></div>',
                    $Current,
                    $this->L10N->getString('label.Method'),
                    $MethodData[0],
                    $this->FE['optMtdStr'],
                    $MethodData[1],
                    $this->FE['optMtdReg'],
                    $MethodData[2],
                    $this->FE['optMtdWin'],
                    $MethodData[3],
                    $this->FE['optMtdDMA'],
                    $this->L10N->getString('tip.Numeric comparison')
                );

                /** Match logic. */
                if (empty($Data['Logic']) || $Data['Logic'] === 'Any') {
                    $LogicData = [' selected', ''];
                } elseif ($Data['Logic'] === 'All') {
                    $LogicData = ['', ' selected'];
                } else {
                    $LogicData = ['', ''];
                }
                $Output .= \sprintf(
                    '<div class="iCntr"><div class="iLabl"><select id="logic[%1$s]" name="logic[%1$s]" title="%2$s" class="flong"><option value="Any"%3$s>%4$s</option><option value="All"%5$s>%6$s</option></select></div></div>',
                    $Current,
                    $this->L10N->getString('label.Logic'),
                    $LogicData[0],
                    $this->L10N->getString('label.aux.logic_any'),
                    $LogicData[1],
                    $this->L10N->getString('label.aux.logic_all')
                );

                /** Display flags (edit mode). */
                foreach ($this->CIDRAM['Provide']['Auxiliary Rules']['Flags'] as $FlagSetName => $FlagSet) {
                    if (isset($FlagSet['Label']) && \is_string($FlagSet['Label'])) {
                        $FlagSetName = $this->L10N->getString($FlagSet['Label']) ?: $FlagSetName;
                    }
                    $Output .= \sprintf('%s<div class="iLabl s"><fieldset><legend>%s</legend>', "\n        ", $FlagSetName);
                    foreach ($FlagSet as $FlagName => $FlagData) {
                        if (!isset($FlagData['Label'])) {
                            continue;
                        }
                        $Output .= \sprintf(
                            '<label><input type="checkbox" class="auto" id="%1$s" name="%1$s"%2$s /> %3$s%4$s</label><br />',
                            \preg_replace('~[^A-Za-z]~', '_', $FlagName) . '_' . $Current,
                            empty($Data[$FlagName]) ? '' : ' checked',
                            isset($FlagData['Icon']) ? $FlagData['Icon'] . ' ' : '',
                            $this->L10N->getString($FlagData['Label']) ?: $FlagName
                        );
                    }
                    if (($Hint = isset($FlagSet['Hint']) ? $this->parseVars([], $FlagSet['Hint'], true) : '') !== '') {
                        $Output .= '<br /><span class="suggestsActive s"><small>' . $Hint . '</small></span><br />';
                    }
                    $Output .= '</fieldset></div>';
                }

                /** Additional instructions. */
                $Output .= \sprintf(
                    '<label><div class="iCntr"><div class="iLabl s">%1$s</div><div class="iCntn"><textarea id="AdditionalInstructions[%2$s]" name="AdditionalInstructions[%2$s]" class="half">%3$s</textarea></div></div></label>',
                    $this->L10N->getString('label.aux.Additional instructions'),
                    $Current,
                    $Data['Additional instructions'] ?? ''
                );

                /** Rule notes. */
                $Output .= \sprintf(
                    '<label><div class="iCntr"><div class="iLabl s">%1$s</div><div class="iCntn"><textarea id="Notes[%2$s]" name="Notes[%2$s]" class="half">%3$s</textarea></div></div></label>',
                    $this->L10N->getString('label.aux.Notes'),
                    $Current,
                    $Data['Notes'] ?? ''
                );

                /** Finish writing new rule. */
                $Output .= '</div>';
                $Current++;
                continue;
            }

            /** Figure out which options are available for the rule (view mode). */
            $Options = ['<span onclick="javascript:%s(\'' . $this->escapeJsInHTML($Name) . '\',\'' . $RuleClass . '\')" class="auxopt" tabindex="0" role="button"><code><span class="auxicon %s" title="%s"></span><span class="s auxicontxt">%s</span></code></span>'];
            if (empty($Data['Disable this rule'])) {
                $Options['disableRule'] = \sprintf($Options[0], 'disableRule', 'auxbl pause', '⏸', $this->L10N->getString('label.aux.Disable this rule'));
            } else {
                $Options['enableRule'] = \sprintf($Options[0], 'enableRule', 'auxgn play', '▶', $this->L10N->getString('label.aux.Enable this rule'));
            }
            $Options['exportRule'] = \sprintf(
                '<span onclick="javascript:{document.getElementById(\'xprtName\').value=\'%s\';document.getElementById(\'xprtForm\').submit()}" class="auxopt" tabindex="0" role="button"><code><span class="auxicon auxbl export"></span><span class="s auxicontxt">%s</span></code></span>',
                $this->escapeJsInHTML($Name),
                $this->L10N->getString('label.Export')
            );
            if ($Count > 1) {
                if ($Current !== 1) {
                    if ($Current !== 2) {
                        $Options['moveUp'] = \sprintf($Options[0], 'moveUp', 'auxbl up1', '↑', $this->L10N->getString('label.aux.Move up'));
                    }
                    $Options['moveToTop'] = \sprintf($Options[0], 'moveToTop', 'auxbl up2', '↑↑', $this->L10N->getString('label.aux.Move to the top'));
                }
                if ($Current !== $Count) {
                    if ($Current !== ($Count - 1)) {
                        $Options['moveDown'] = \sprintf($Options[0], 'moveDown', 'auxbl down1', '↓', $this->L10N->getString('label.aux.Move down'));
                    }
                    $Options['moveToBottom'] = \sprintf($Options[0], 'moveToBottom', 'auxbl down2', '↓↓', $this->L10N->getString('label.aux.Move to the bottom'));
                }
            }
            unset($Options[0]);
            $Options['delRule'] = \sprintf(
                '<span onclick="javascript:confirm(\'%s\')&&delRule(\'' . $this->escapeJsInHTML($Name) . '\',\'' . $RuleClass . '\')" class="auxopt"><code><span class="auxicon auxrd delete" title="⌧" tabindex="0" role="button"></span><span class="s auxicontxt">%s</span></code></span>',
                $this->escapeJsInHTML(\sprintf($this->L10N->getString('confirm.Delete'), $Name)),
                $this->L10N->getString('field.Delete')
            );
            $Options = \sprintf(
                ' <span class="inlineBlock">– <span id="heaven%1$s" class="heavenInitPos navicon heaven hoverglow" onclick="javascript:heavenToggle(\'%1$s\')" title="☰" aria-haspopup="menu" tabindex="0" role="button"></span><span id="hidden%1$s" class="hiddenInitPos">&nbsp;– %2$s</span></span>',
                $RuleClass,
                \implode(' – ', $Options)
            );

            $FromAndExpiry = '';
            $Expired = false;

            /** Determine from and expiry information. */
            foreach ([
                ['From', 'label.aux.When the rule should begin (optional)'],
                ['Expiry', 'label.aux.When the rule should expire (optional)', 'Expired']
            ] as $Details) {
                if (!empty($Data[$Details[0]]) && $Label = $this->L10N->getString($Details[1])) {
                    if (\preg_match('~^(\d{4})[.-](\d\d)[.-](\d\d)$~', $Data[$Details[0]], $Time)) {
                        $Time = \mktime(0, 0, 0, (int)$Time[2], (int)$Time[3], (int)$Time[1]);
                        if (isset($Details[2])) {
                            ${$Details[2]} = $Time < $this->Now;
                        }
                        $Data[$Details[0]] .= ' (' . $this->relativeTime($Time) . ')';
                    }
                    $FromAndExpiry .= "\n          <li><div class=\"iCntr\"><div class=\"iLabl s\">" . $Label . '</div><div class="iCntn">' . $Data[$Details[0]] . '</div></div></li>';
                }
            }

            /** Begin generating rule output. */
            $Output .= \sprintf(
                '%1$s<li class="%2$s" name="%6$s" draggable="true"><span class="comCat s">%3$s</span><span class="auxAlignFix">%4$s</span>%5$s%1$s  <ul class="comSub">',
                "\n        ",
                $RuleClass . (empty($Data['Disable this rule']) ? '' : ' hB fBlur"'),
                $Expired ? '<em class="txtRd">' . $Name . ' (' . $this->L10N->getString('field.Expired') . ')</em>' : $Name,
                $Options,
                isset($Data['Notes']) ? '<div class="iCntn"><em>' . \str_replace(['<', '>', "\n"], ['&lt;', '&gt;', "<br />\n"], $Data['Notes']) . '</em></div>' : '',
                $Name
            );

            /** Additional details about the rule to print to the page (e.g., detailed block reason). */
            foreach ([
                ['Reason', 'label.aux.The reason given to the user when blocked'],
                ['Target', 'label.aux.Where to redirect the request'],
                ['Webhooks', 'label.aux.Webhooks to apply when the rule requirements are met']
            ] as $Details) {
                if (!empty($Data[$Details[0]]) && $Label = $this->L10N->getString($Details[1])) {
                    if (\is_array($Data[$Details[0]])) {
                        $Data[$Details[0]] = \implode('</div><div class="iCntn">', $Data[$Details[0]]);
                    }
                    $Output .= "\n          <li><div class=\"iCntr\"><div class=\"iLabl s\">" . $Label . '</div><div class="iCntn">' . $Data[$Details[0]] . '</div></div></li>';
                }
            }

            /** Append from and expiry information. */
            $Output .= $FromAndExpiry;

            /** Display the status code to be applied. */
            if (!empty($Data['Status Code']) && $Data['Status Code'] > 200 && $StatusCode = $this->getStatusHTTP($Data['Status Code'])) {
                $Output .= "\n          <li><div class=\"iCntr\"><div class=\"iLabl s\">" . $this->L10N->getString('label.aux.HTTP status code override') . '</div><div class="iCntn">' . $Data['Status Code'] . ' ' . $StatusCode . '</div></div></li>';
            }

            /** Iterate through actions. */
            foreach ([
                ['Whitelist', 'optActWhl'],
                ['Greylist', 'optActGrl'],
                ['Block', 'optActBlk'],
                ['Bypass', 'optActByp'],
                ['Redirect', 'optActRdr'],
                ['Run', 'optActRun'],
                ['Profile', 'optActPro']
            ] as $Action) {
                /** Skip action if the current rule doesn't use this action. */
                if (empty($Data[$Action[0]])) {
                    continue;
                }

                /** Show the appropriate label for this action. */
                $Output .= \sprintf(
                    '%1$s<li>%1$s  <div class="iCntr">%1$s    <div class="iLabl s">%2$s</div>',
                    "\n          ",
                    $this->FE[$Action[1]]
                );

                /** List all "not equals" conditions . */
                if (!empty($Data[$Action[0]]['But not if matches'])) {
                    /** Iterate through sources. */
                    foreach ($Data[$Action[0]]['But not if matches'] as $Source => $Values) {
                        $ThisSource = $Sources[$Source] ?? $Source;
                        if (!\is_array($Values)) {
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
                                $Operator = \strpos($Value, '*') === false ? '≠' : '≉';
                            } elseif ($Data['Method'] === 'Auto') {
                                $Boundary = \preg_quote(\substr($Value, 0, 1));
                                if (
                                    \preg_match('~^(?!\\\\)(?![\0-\x20\dA-Za-z\xC0-\xFF]).$~', $Boundary) &&
                                    \preg_match($Boundary === '~' ? '/^' . $Boundary . '.+' . $Boundary . 'i?m?s?x?A?D?S?U?u?n?$/' : '~^' . $Boundary . '.+' . $Boundary . 'i?m?s?x?A?D?S?U?u?n?$~', $Value)
                                ) {
                                    $Operator = '≇';
                                } else {
                                    $Operator = \strpos($Value, '*') === false ? $this->operatorFromAuxValue($Value, true) : '≉';
                                }
                            } else {
                                $Operator = $this->operatorFromAuxValue($Value, true);
                            }
                            $Operator = '<span class="toolTip" title="' . $this->L10N->getString('operator_' . $Operator) . '">' . $Operator . '</span>';
                            $Output .= "\n              <div class=\"iCntn\"><span>" . $ThisSource . '</span> ' . $Operator . ' <code>' . $Value . '</code></div>';
                        }
                    }
                }

                /** List all "equals" conditions . */
                if (!empty($Data[$Action[0]]['If matches'])) {
                    /** Iterate through sources. */
                    foreach ($Data[$Action[0]]['If matches'] as $Source => $Values) {
                        $ThisSource = isset($Sources[$Source]) ? $Sources[$Source] : $Source;
                        if (!\is_array($Values)) {
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
                                $Operator = \strpos($Value, '*') === false ? '=' : '≈';
                            } elseif ($Data['Method'] === 'Auto') {
                                $Boundary = \preg_quote(\substr($Value, 0, 1));
                                if (
                                    \preg_match('~^(?!\\\\)(?![\0-\x20\dA-Za-z\xC0-\xFF]).$~', $Boundary) &&
                                    \preg_match($Boundary === '~' ? '/^' . $Boundary . '.+' . $Boundary . 'i?m?s?x?A?D?S?U?u?n?$/' : '~^' . $Boundary . '.+' . $Boundary . 'i?m?s?x?A?D?S?U?u?n?$~', $Value)
                                ) {
                                    $Operator = '≅';
                                } else {
                                    $Operator = \strpos($Value, '*') === false ? $this->operatorFromAuxValue($Value) : '≈';
                                }
                            } else {
                                $Operator = $this->operatorFromAuxValue($Value);
                            }
                            $Operator = '<span class="toolTip" title="' . $this->L10N->getString('operator_' . $Operator) . '">' . $Operator . '</span>';
                            $Output .= "\n              <div class=\"iCntn\"><span>" . $ThisSource . '</span> ' . $Operator . ' <code>' . $Value . '</code></div>';
                        }
                    }
                }

                /** Finish writing conditions list. */
                $Output .= "\n            </div>\n          </li>";
            }

            /** Cite the file to run. */
            if (!empty($Data['Run']['File']) && $Label = $this->L10N->getString('label.aux.The name of the file to run')) {
                $Output .= "\n          <li><div class=\"iCntr\"><div class=\"iLabl s\">" . $Label . '</div><div class="iCntn">' . $Data['Run']['File'] . '</div></div></li>';
            }

            /** Display flags (view mode). */
            $Flags = [];
            foreach ($this->CIDRAM['Provide']['Auxiliary Rules']['Flags'] as $FlagSetName => $FlagSet) {
                foreach ($FlagSet as $FlagName => $FlagData) {
                    if (!isset($FlagData['Label'])) {
                        continue;
                    }
                    if (!empty($Data[$FlagName])) {
                        $Flags[] = $this->L10N->getString($FlagData['Label']) ?: $FlagName;
                    }
                }
            }
            if (\count($Flags)) {
                $Output .= "\n          <li><div class=\"iCntr\"><div class=\"iLabl s\">" . $this->L10N->getString('label.aux.Other options and special flags') . '</div><div class="iCntn">' . \implode('<br />', $Flags) . '</div></div></li>';
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
                (!empty($Data['Logic']) && $Data['Logic'] !== 'Any') ? 'label.aux.logic_all' : 'label.aux.logic_any'
            ) . '</em></div></div></li>';

            /** Describe any additional instructions. */
            if (!empty($Data['Additional instructions']) && $Label = $this->L10N->getString('label.aux.Additional instructions')) {
                $Output .= "\n          <li><div class=\"iCntr\"><div class=\"iLabl s\">" . $Label . '</div><div class="iCntn">' . \str_replace(['<', '>', "\n"], ['&lt;', '&gt;', '<br />'], $Data['Additional instructions']) . '</div></div></li>';
            }

            /** Finish writing new rule. */
            $Output .= \sprintf(
                '%1$s  </ul>%1$s</li>%1$s<div class="rulePseudoPos" name="_pseudo%2$d">%3$s</div>',
                "\n        ",
                $Current,
                $this->L10N->getString('label.aux.Drop the rule here to move it to this position, or onto another rule to swap positions')
            );
            $Current++;
        }

        /** Update button after. */
        if ($Mode) {
            $StyleClass = $StyleClass === 'ng1' ? 'ng2' : 'ng1';
            $Output .= \sprintf(
                '<div class="%s center flexstretch"><input type="submit" value="%s" class="auto" /></div>',
                $StyleClass,
                $this->L10N->getString('field.Update all')
            );
        }

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
            if (\is_array($Label)) {
                $Output .= $this->generateOptions($Label, $JS);
                continue;
            }
            $Label = $this->parseVars([], $this->L10N->getString($Label) ?: $Label, true);
            if ($JS) {
                $Output .= "\n  x = document.createElement('option'),\n  x.setAttribute('value', '" . $Value . "'),\n  x.textContent = '" . $this->escapeJsInHTML($Label) . "',\n  t.appendChild(x),";
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
            if (\is_array($Label)) {
                $Output = \array_merge($Output, $this->generateLabels($Label));
                continue;
            }
            $Label = $this->L10N->getString($Label) ?: $Label;
            $Output[$Value] = $this->parseVars([], $Label, true);
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
        /** Fetch ignore.dat data. */
        if (!isset($this->CIDRAM['Ignore'])) {
            $this->CIDRAM['Ignore'] = $this->fetchIgnores();
        }

        /** Prepare ignore suggestions. */
        $IgnoreSuggestions = (
            \is_array($this->CIDRAM['Ignore']) &&
            \count($this->CIDRAM['Ignore']) > 0
        ) ? '[\'' . \implode('\',\'', \str_replace('\'', '\\\'', \array_keys($this->CIDRAM['Ignore']))) . '\'].map((e)=>\'<span class="auxSuggestLink" onclick="javascript:this.parentElement.parentElement.previousElementSibling.lastChild.value=\\\'\'+e+\'\\\'">\'+e+\'</span>\').join(\', \')' : '\'\'';

        /** Append JavaScript specific to the auxiliary rules page. */
        $this->FE['JS'] .= $this->parseVars([
            'hints_asnlookup' => \str_replace('\'', '\\\'', $this->L10N->getString('hints_asnlookup')),
            'hints_cclookup' => \str_replace('\'', '\\\'', $this->L10N->getString('tip.When available, the value of the country code lookup') . '<br />' . $this->L10N->getString('hints_cclookup')),
            'hints_client_hints' => \str_replace('\'', '\\\'', $this->L10N->getString('hints_client_hints')),
            'label.Suggestions' => \str_replace('\'', '\\\'', $this->L10N->getString('label.Suggestions')),
            'tip.An accepted value is any CIDR with a range that covers the IP address of the request' => \str_replace('\'', '\\\'', $this->L10N->getString('tip.An accepted value is any CIDR with a range that covers the IP address of the request')),
            'tip.If a request connects from an IPv6 address belonging to a range used by an IPv6 transition mechanism' => \str_replace('\'', '\\\'', $this->L10N->getString('tip.If a request connects from an IPv6 address belonging to a range used by an IPv6 transition mechanism')),
            'tip.Note that the exact value of this statistic will be the same as that shown at the statistics page at the time the rule is processed' => \str_replace('\'', '\\\'', $this->L10N->getString('tip.Note that the exact value of this statistic will be the same as that shown at the statistics page at the time the rule is processed')),
            'tip.Specify a URL, or leave blank to disregard' => \str_replace('\'', '\\\'', $this->L10N->getString('tip.Specify a URL, or leave blank to disregard')),
            'tip.Specify a value, or leave blank to disregard' => \str_replace('\'', '\\\'', $this->L10N->getString('tip.Specify a value, or leave blank to disregard')),
            'pair_separator' => $this->L10N->getString('pair_separator'),
            'ignoreSuggestions' => $IgnoreSuggestions
        ], $this->readFile($this->getAssetPath('auxiliary.js')));

        /** Populate methods. */
        $this->FE['optMtdStr'] = $this->L10N->getString('label.aux.Use direct string comparison to test the conditions');
        $this->FE['optMtdReg'] = $this->L10N->getString('label.aux.Use regular expressions to test the conditions');
        $this->FE['optMtdWin'] = $this->L10N->getString('label.aux.Use Windows-style wildcards to test the conditions');
        $this->FE['optMtdDMA'] = $this->L10N->getString('label.aux.Detect the method for testing the conditions automatically');

        /** Populate actions. */
        $this->FE['optActWhl'] = \sprintf($this->L10N->getString('label.aux.If the following conditions are met, %s'), $this->L10N->getString('label.aux.whitelist the request'));
        $this->FE['optActGrl'] = \sprintf($this->L10N->getString('label.aux.If the following conditions are met, %s'), $this->L10N->getString('label.aux.greylist the request'));
        $this->FE['optActBlk'] = \sprintf($this->L10N->getString('label.aux.If the following conditions are met, %s'), $this->L10N->getString('label.aux.block the request'));
        $this->FE['optActByp'] = \sprintf($this->L10N->getString('label.aux.If the following conditions are met, %s'), $this->L10N->getString('label.aux.bypass the request'));
        $this->FE['optActRdr'] = \sprintf($this->L10N->getString('label.aux.If the following conditions are met, %s'), $this->L10N->getString('label.aux.redirect the request (without blocking it)'));
        $this->FE['optActRun'] = \sprintf($this->L10N->getString('label.aux.If the following conditions are met, %s'), $this->L10N->getString('label.aux.run a file to handle the request'));
        $this->FE['optActPro'] = \sprintf($this->L10N->getString('label.aux.If the following conditions are met, %s'), $this->L10N->getString('label.aux.profile the request'));

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
        if (($NewAuxArr = $this->YAML->reconstruct($this->CIDRAM['AuxData'])) && \strlen($NewAuxArr) > 2) {
            $Handle = \fopen($this->Vault . 'auxiliary.yml', 'wb');
            if ($Handle !== false) {
                \fwrite($Handle, $NewAuxArr);
                \fclose($Handle);
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
        if (\is_string($In)) {
            $In = \str_replace($What, $With, $In);
        }
        if (\is_array($In)) {
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
        $Arr = $Direction ? \array_merge($Arr, $Split) : \array_merge($Split, $Arr);
        return $Arr;
    }

    /**
     * Swaps the positions of elements in an associative array by distance.
     *
     * @param array $Arr The associative array to be worked.
     * @param string $Target The key of the element to be swapped.
     * @param int $Distance The distance between the elements to be swapped.
     * @return array The worked array.
     */
    private function swapAssociativeArrayElementsByDistance(array $Arr, string $Target, int $Distance): array
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
        if (!isset($TargetIndex, $Keys[$TargetIndex], $Values[$TargetIndex], $Keys[$TargetIndex + $Distance], $Values[$TargetIndex + $Distance])) {
            return $Arr;
        }
        [$Keys[$TargetIndex], $Keys[$TargetIndex + $Distance]] = [$Keys[$TargetIndex + $Distance], $Keys[$TargetIndex]];
        [$Values[$TargetIndex], $Values[$TargetIndex + $Distance]] = [$Values[$TargetIndex + $Distance], $Values[$TargetIndex]];
        return \array_combine($Keys, $Values);
    }
}
