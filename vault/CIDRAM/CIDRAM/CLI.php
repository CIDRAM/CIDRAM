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
 * This file: CIDRAM CLI mode (last modified: 2022.06.01).
 */

namespace CIDRAM\CIDRAM;

trait CLI
{
    /**
     * CIDRAM CLI mode.
     *
     * @return never
     */
    public function cli(): never
    {
        /** Guard against access from the wrong endpoint. */
        if (!empty($_SERVER['REQUEST_METHOD']) || substr(php_sapi_name(), 0, 3) !== 'cli' || !empty($_SERVER['HTTP_USER_AGENT'])) {
            header('Content-Type: text/plain');
            die('[CIDRAM CLI] Webserver access not permitted.');
        }

        $ML = false;
        $Chain = '';

        /** Load CIDRAM front-end L10N data. */
        $this->loadL10N($this->Vault . 'l10n' . DIRECTORY_SEPARATOR . 'frontend' . DIRECTORY_SEPARATOR);

        /** Show basic information. */
        echo "\rCIDRAM CLI mode.

To test whether an IP address is blocked by CIDRAM:
>> test xxx.xxx.xxx.xxx

To calculate CIDRs from an IP address:
>> cidrs xxx.xxx.xxx.xxx

IPv4/IPv6 are both supported. Multiline input is possible for *all* CLI mode
operations (e.g., to test multiple IP addresses in a single operation) by using
quotes, like so:
>> test \"xxx.xxx.xxx.xxx
>> yyy.yyy.yyy.yyy
>> 2002::1
>> zzz.zzz.zzz.zzz\"

You can also use commas instead if you want (this is treated the same as using
multilines, but you won't need to use quotes, and don't mix both together):
>> test xxx.xxx.xxx.xxx,yyy.yyy.yyy.yyy,2002::1,zzz.zzz.zzz.zzz

By default, IPs are tested against all signature files, all modules, all
auxiliary rules, *and* against social media and search engine verification.
However, you can optionally disable, on a per-IP basis, checking against
modules with --no-mod, checking against auxiliary rules with --no-aux, and
checking against social media and search engine verification with --no-ssv,
like so:
>> test \"aaa.aaa.aaa.aaa --no-mod
>> bbb.bbb.bbb.bbb --no-aux
>> ccc.ccc.ccc.ccc --no-ssv
>> ddd.ddd.ddd.ddd --no-mod --no-aux --no-ssv\"

You can also read from files like so:
>> fread \"file1.dat
>> file2.dat
>> file3.dat\"

You can also write to files like so:
>> fwrite=file.dat

You can also aggregate IPs and CIDRs like so:
>> aggregate \"1.2.3.4/32
>> 1.2.3.5/32
>> 1.2.3.6/32
>> 1.2.3.7/32\"

Or, to aggregate them as netmasks:
>> aggregate=netmasks \"1.2.3.4/32
>> 1.2.3.5/32\"

You can also chain commands together like so (this example reads from some
files, aggregates their content, then writes the aggregated data to output.dat):
>> fread>aggregate>fwrite=output.dat \"input1.dat
>> input2.dat
>> input3.dat\"

Or, depending on whether you'd prefer to chain first to last, or last to first:
>> fwrite=output.dat<aggregate<fread \"input1.dat
>> input2.dat
>> input3.dat\"

Or, using commas instead of multilines (does exactly the same thing):
>> fread>aggregate>fwrite=output.dat input1.dat,input2.dat,input3.dat

You can print data to the screen like so (this can sometimes be useful when
chaining commands):
>> print Hello World

You can also utilise CIDRAM's signature fixer facility via CLI:
>> fread>fix>fwrite=fixed.dat broken.dat

To quit, type \"q\", \"quit\", or \"exit\" and press enter:
>> q\n\n";

        $this->initialiseCache();
        $this->resetBypassFlags();
        $StdInHandle = fopen('php://stdin', 'rb');

        while (true) {
            /** Set CLI process title. */
            if (function_exists('cli_set_process_title')) {
                cli_set_process_title($this->ScriptIdent);
            }

            /** Echo the CLI-mode prompt. */
            if (!$Chain) {
                echo '>> ';
            }

            /** Wait for user input or assume it from chaining. */
            $StdInClean = $Chain ?: trim(fgets($StdInHandle));

            /** Check whether expected input is multiline. */
            if ($ML) {
                /** Multiline detection. */
                if (substr($StdInClean, -1, 1) !== '"') {
                    $Data[] = $StdInClean;
                    continue;
                } else {
                    $Data[] = substr($StdInClean, 0, -1);
                    $ML = false;
                    echo "\n";
                }
            } else {
                /** Fetch the command. */
                $Cmd = (($SPos = strpos($StdInClean, ' ')) === false) ? $StdInClean : substr($StdInClean, 0, $SPos);

                /** Chain detection. */
                if ($Chain) {
                    $Data = explode("\n", substr($StdInClean, strlen($Cmd) + 1));
                } else {

                    /** Multiline detection. */
                    if ($ML = (
                        substr($StdInClean, strlen($Cmd) + 1, 1) === '"' &&
                        substr($StdInClean, -1, 1) !== '"'
                    )) {
                        $Data = [substr($StdInClean, strlen($Cmd) + 2)];
                        continue;
                    } else {
                        $Data = substr($StdInClean, strlen($Cmd) + 1);
                        if (substr($Data, 0, 1) === '"' && substr($Data, -1, 1) === '"') {
                            $Data = substr($Data, 1, -1);
                        }
                        $Data = (strpos($Data, ',') !== false) ? explode(',', $Data) : [$Data];
                        echo "\n";
                    }
                }
            }

            /** Chain processing. */
            if (($ChainPoa = strpos($Cmd, '>')) !== false && strpos($Cmd, '<') === false) {
                $Chain = substr($Cmd, $ChainPoa + 1) . ' ';
                $Cmd = substr($Cmd, 0, $ChainPoa);
            } elseif (strpos($Cmd, '>') === false && ($ChainPoa = strrpos($Cmd, '<')) !== false) {
                $Chain = substr($Cmd, 0, $ChainPoa) . ' ';
                $Cmd = substr($Cmd, $ChainPoa + 1);
            } else {
                $Chain = '';
            }

            /** Set CLI process title with "working" notice. */
            if (function_exists('cli_set_process_title')) {
                cli_set_process_title($this->ScriptIdent . ' - ' . $this->L10N->getString('state_loading'));
            }

            /** Don't execute any commands when receiving multiline input. */
            if ($ML) {
                continue;
            }

            /** Exit CLI-mode. */
            if ($Cmd === 'quit' || $Cmd === 'q' || $Cmd === 'exit') {
                break;
            }

            /** Print data to the screen. */
            if ($Cmd === 'print') {
                if (empty($Data) || (count($Data) === 1 && empty($Data[0]))) {
                    echo "There's nothing to print, sorry.\n\n";
                    continue;
                }
                if (!$Chain) {
                    foreach ($Data as $ThisItem) {
                        echo $ThisItem . "\n";
                    }
                    echo "\n";
                    continue;
                }
                $Chain = '';
                echo "The print command can't be chained in that way, sorry.\n\n";
                continue;
            }

            /** Write data to a file. */
            if (substr($Cmd, 0, 7) === 'fwrite=') {
                if (empty($Data) || (count($Data) === 1 && empty($Data[0]))) {
                    echo "There's nothing to write, sorry.\n\n";
                    continue;
                }
                if ($Chain) {
                    echo "The fwrite command can't be chained in that way, sorry.\n\n";
                    continue;
                }
                $WriteTo = substr($Cmd, 7);
                if (is_dir($this->Vault . $WriteTo) || !is_writable($this->Vault)) {
                    echo "I can't write to " . $WriteTo . ", sorry.\n\n";
                    continue;
                }
                $Handle = fopen($this->Vault . $WriteTo, 'wb');
                $BlocksToDo = count($Data);
                $ThisBlock = 0;
                $FileSize = 0;
                foreach ($Data as $ThisItem) {
                    $ThisBlock++;
                    $FileSize += strlen($ThisItem);
                    if ($ThisBlock !== $BlocksToDo) {
                        $ThisItem .= "\n";
                        $FileSize++;
                    }
                    fwrite($Handle, $ThisItem);
                }
                fclose($Handle);
                $MemoryUsage = memory_get_usage();
                $this->formatFileSize($MemoryUsage);
                $this->formatFileSize($FileSize);
                echo 'Finished writing to ' . $WriteTo . '. <' . $this->L10N->getString('field_file') . ': ' . $FileSize . '> <RAM: ' . $MemoryUsage . ">\n\n";
                unset($WriteTo, $Handle, $BlocksToDo, $ThisBlock, $MemoryUsage, $FileSize);
                continue;
            }

            /** Read data from files. */
            if ($Cmd === 'fread') {
                if (!$Chain) {
                    echo "I'm not sure what to do with the file's data after reading it.\nPlease chain fread to something else so that I'll know what to do. Thanks.\n\n";
                    continue;
                }
                foreach ($Data as $ThisItem) {
                    $ThisItemTry = $this->Vault . $ThisItem;
                    if (!is_file($ThisItemTry) || !is_readable($ThisItemTry)) {
                        $ThisItemTry = $ThisItem;
                        if (!is_file($ThisItemTry) || !is_readable($ThisItemTry)) {
                            echo "Failed to read " . $ThisItem . "!\n";
                            continue;
                        }
                    }
                    $ThisItemSize = filesize($ThisItemTry);
                    $FileSize = $ThisItemSize;
                    $this->formatFileSize($FileSize);
                    $ThisItemSize = $ThisItemSize ? ceil($ThisItemSize / 131072) : 0;
                    if ($ThisItemSize <= 0) {
                        echo "Failed to read " . $ThisItem . "!\n";
                        continue;
                    }
                    $ThisItemTry = fopen($ThisItemTry, 'rb');
                    $ThisItemCycle = 0;
                    while ($ThisItemCycle < $ThisItemSize) {
                        $Chain .= fread($ThisItemTry, 131072);
                        $ThisItemCycle++;
                    }
                    $MemoryUsage = memory_get_usage();
                    $this->formatFileSize($MemoryUsage);
                    echo 'Finished reading from ' . $ThisItem . '. <' . $this->L10N->getString('field_file') . ': ' . $FileSize . '> <RAM: ' . $MemoryUsage . ">\n";
                    fclose($ThisItemTry);
                }
                unset($ThisItemTry, $ThisItemSize, $FileSize, $ThisItemCycle, $MemoryUsage);
                echo "\n";
                continue;
            }

            /** Perform IP test. */
            if ($Cmd === 'test') {
                if (!$Chain) {
                    echo $this->L10N->getString('field_ipaddr') . ' – ' . $this->L10N->getString('field_blocked') . "\n===\n";
                }
                foreach ($Data as $ThisItem) {
                    $Results = ['Mod' => true, 'Aux' => true, 'SSV' => true];
                    if (preg_match('~( --no-(?:mod|aux|ssv))+$~', $ThisItem)) {
                        if (strpos($ThisItem, ' --no-mod') !== false) {
                            $Results['Mod'] = false;
                        }
                        if (strpos($ThisItem, ' --no-aux') !== false) {
                            $Results['Aux'] = false;
                        }
                        if (strpos($ThisItem, ' --no-ssv') !== false) {
                            $Results['SSV'] = false;
                        }
                        $ThisItem = preg_replace('~( --no-(?:mod|aux|ssv))+$~', '', $ThisItem);
                    }
                    $this->simulateBlockEvent($ThisItem, $Results['Mod'], $Results['Aux'], $Results['SSV']);
                    if (
                        $this->CIDRAM['Caught'] ||
                        empty($this->CIDRAM['LastTestIP']) ||
                        empty($this->CIDRAM['TestResults']) ||
                        !empty($this->CIDRAM['ModuleErrors']) ||
                        !empty($this->CIDRAM['AuxErrors'])
                    ) {
                        $Results['YesNo'] = $this->L10N->getString('response_error');
                        if (!empty($this->CIDRAM['AuxErrors'])) {
                            $Results['YesNo'] .= sprintf(
                                ' – auxiliary.yaml (%s)',
                                $this->NumberFormatter->format(count($this->CIDRAM['AuxErrors']))
                            );
                        }
                        if (!empty($this->CIDRAM['ModuleErrors'])) {
                            $ModuleErrorCounts = [];
                            foreach ($this->CIDRAM['ModuleErrors'] as $ModuleError) {
                                if (isset($ModuleErrorCounts[$ModuleError[2]])) {
                                    $ModuleErrorCounts[$ModuleError[2]]++;
                                } else {
                                    $ModuleErrorCounts[$ModuleError[2]] = 1;
                                }
                            }
                            arsort($ModuleErrorCounts);
                            foreach ($ModuleErrorCounts as $ModuleName => $ModuleError) {
                                $Results['YesNo'] .= sprintf(
                                    ' – %s (%s)',
                                    $ModuleName,
                                    $this->NumberFormatter->format($ModuleError)
                                );
                            }
                            unset($ModuleName, $ModuleError, $ModuleErrorCounts);
                        }
                    } elseif ($this->BlockInfo['SignatureCount']) {
                        $Results['YesNo'] = $this->L10N->getString('response_yes') . ' – ' . $this->BlockInfo['WhyReason'];
                    } else {
                        $Results['YesNo'] = $this->L10N->getString('response_no');
                    }
                    $Results['NegateFlags'] = '';
                    if ($this->CIDRAM['Flag Don\'t Log']) {
                        $Results['NegateFlags'] .= '📓';
                    }
                    if ($Results['NegateFlags']) {
                        $Results['YesNo'] .= ' – 🚫' . $Results['NegateFlags'];
                    }
                    if ($Chain) {
                        $Chain .= $ThisItem . ' – ' . $Results['YesNo'] . ".\n";
                    } else {
                        echo $ThisItem . ' – ' . $Results['YesNo'] . ".\n";
                    }
                }
                unset($Results);
                if (!$Chain) {
                    echo "\n";
                }
                continue;
            }

            /** Calculate CIDRs. */
            if ($Cmd === 'cidrs') {
                if (!$Chain) {
                    echo $this->L10N->getString('field_range') . "\n===\n";
                }
                foreach ($Data as $ThisItem) {
                    if ($ThisItem = preg_replace('~[^\da-f:./]~i', '', $ThisItem)) {
                        if (!$this->CIDRAM['CIDRs'] = $this->CIDRAM['ExpandIPv4']($ThisItem)) {
                            $this->CIDRAM['CIDRs'] = $this->CIDRAM['ExpandIPv6']($ThisItem);
                        }
                    }

                    /** Process CIDRs. */
                    if (!empty($this->CIDRAM['CIDRs'])) {
                        $this->CIDRAM['Factors'] = count($this->CIDRAM['CIDRs']);
                        array_walk($this->CIDRAM['CIDRs'], function ($CIDR, $Key) {
                            if ($Chain) {
                                $Chain .= $CIDR . "\n";
                            } else {
                                $First = substr($CIDR, 0, strlen($CIDR) - strlen($Key + 1) - 1);
                                if ($this->CIDRAM['Factors'] === 32) {
                                    $Last = $this->ipv4GetLast($First, $Key + 1);
                                } elseif ($this->CIDRAM['Factors'] === 128) {
                                    $Last = $this->ipv6GetLast($First, $Key + 1);
                                } else {
                                    $Last = $this->L10N->getString('response_error');
                                }
                                echo $CIDR . ' (' . $First . ' – ' . $Last . ")\n";
                            }
                        });
                    }
                }
                unset($this->CIDRAM['CIDRs']);
                if (!$Chain) {
                    echo "\n";
                }
                continue;
            }

            /** Aggregate IPs/CIDRs. */
            if ($Cmd === 'aggregate' || substr($Cmd, 0, 10) === 'aggregate=') {
                echo $this->L10N->getString('link_ip_aggregator') . "\n===\n";
                $this->CIDRAM['Aggregator'] = new \CIDRAM\CIDRAM\Aggregator(substr($Cmd, 10) === 'netmasks' ? 1 : 0);
                $this->CIDRAM['Aggregator']->Results = true;
                $Data = implode("\n", $Data);
                $Data = str_replace("\r", '', trim($Data));
                $Results = ['Timer' => 0, 'Parse' => 0, 'Tick' => 0, 'Measure' => 0];
                $this->CIDRAM['Aggregator']->callbacks['newParse'] = function ($Measure) use (&$Results) {
                    if ($Results['Parse'] !== 0) {
                        $Memory = memory_get_usage();
                        $this->formatFileSize($Memory);
                        echo "\rParse " . $this->NumberFormatter->format($Results['Parse']) . ' ... ' . $this->NumberFormatter->format(100, 2) . '% (' . $this->timeFormat(time(), $this->Configuration['general']['time_format']) . ') <RAM: ' . $Memory . '>';
                    }
                    echo "\n";
                    $Results['Parse']++;
                    $Results['Tick'] = 0;
                    $Results['Timer'] = 0;
                    $Results['Measure'] = $Measure ?: $this->CIDRAM['Aggregator']->NumberEntered;
                };
                $this->CIDRAM['Aggregator']->callbacks['newTick'] = function () use (&$Results) {
                    $Results['Tick']++;
                    $Results['Timer']++;
                    if ($Results['Tick'] >= $Results['Measure']) {
                        $Results['Measure']++;
                    }
                    if ($Results['Timer'] > 25) {
                        $Results['Timer'] = 0;
                        $Percent = $this->NumberFormatter->format(($Results['Tick'] / $Results['Measure']) * 100, 2);
                        echo "\rParse " . $this->NumberFormatter->format($Results['Parse']) . ' ... ' . $Percent . '%';
                    }
                };
                $Data = $this->CIDRAM['Aggregator']->aggregate($Data);
                $Results['Memory'] = memory_get_usage();
                $this->formatFileSize($Results['Memory']);
                echo "\rParse " . $this->NumberFormatter->format($Results['Parse']) . ' ... ' . $this->NumberFormatter->format(100, 2) . '% (' . $this->timeFormat(time(), $this->Configuration['general']['time_format']) . ') <RAM: ' . $Results['Memory'] . ">\n\n";
                echo sprintf(
                    $this->L10N->getString('label_results'),
                    $this->NumberFormatter->format($this->CIDRAM['Aggregator']->NumberEntered),
                    $this->NumberFormatter->format($this->CIDRAM['Aggregator']->NumberRejected),
                    $this->NumberFormatter->format($this->CIDRAM['Aggregator']->NumberAccepted),
                    $this->NumberFormatter->format($this->CIDRAM['Aggregator']->NumberMerged),
                    $this->NumberFormatter->format($this->CIDRAM['Aggregator']->NumberReturned)
                ) . "\n\n";
                if ($Chain) {
                    $Chain .= $Data;
                } else {
                    echo $Data . "\n\n";
                }
                unset($Results, $this->CIDRAM['Aggregator']);
                continue;
            }

            /** Create analysis matrix. */
            if (class_exists('\Maikuolan\Common\Matrix') && function_exists('imagecreatetruecolor') && substr($Cmd, 0, 7) === 'matrix=') {
                if (empty($Data) || (count($Data) === 1 && empty($Data[0]))) {
                    echo "There's nothing to analyse, sorry.\n\n";
                    continue;
                }
                if ($Chain) {
                    echo "The matrix command can't be chained in that way, sorry.\n\n";
                    continue;
                }
                $WriteTo = substr($Cmd, 7);
                if (is_dir($this->Vault . $WriteTo) || !is_writable($this->Vault)) {
                    echo "I can't write to " . $WriteTo . ", sorry.\n\n";
                    continue;
                }
                $Data = implode("\n", $Data);
                $this->matrixCreate($Data, $WriteTo, true);
                unset($WriteTo);
                continue;
            }

            /** Signature file fixer. */
            if ($Cmd === 'fix') {
                echo $this->L10N->getString('link_fixer') . "\n===\n";
                $Data = implode("\n", $Data);
                $Fixer = [
                    'Aggregator' => new \CIDRAM\CIDRAM\Aggregator(),
                    'Before' => hash('sha256', $Data) . ':' . strlen($Data),
                    'Timer' => 0,
                    'Parse' => 0,
                    'Tick' => 0,
                    'Measure' => 0,
                ];
                $Fixer['Aggregator']->callbacks['newParse'] = function ($Measure) use (&$Fixer) {
                    if ($Fixer['Parse'] !== 0) {
                        $Memory = memory_get_usage();
                        $this->formatFileSize($Memory);
                        echo "\rParse " . $this->NumberFormatter->format($Fixer['Parse']) . ' ... ' . $this->NumberFormatter->format(100, 2) . '% (' . $this->timeFormat(time(), $this->Configuration['general']['time_format']) . ') <RAM: ' . $Memory . '>';
                    }
                    echo "\n";
                    $Fixer['Parse']++;
                    $Fixer['Tick'] = 0;
                    $Fixer['Timer'] = 0;
                    $Fixer['Measure'] = $Measure;
                };
                $Fixer['Aggregator']->callbacks['newTick'] = function () use (&$Fixer) {
                    $Fixer['Tick']++;
                    $Fixer['Timer']++;
                    if ($Fixer['Tick'] >= $Fixer['Measure']) {
                        $Fixer['Measure']++;
                    }
                    if ($Fixer['Timer'] > 25) {
                        $Fixer['Timer'] = 0;
                        $Percent = $this->NumberFormatter->format(($Fixer['Tick'] / $Fixer['Measure']) * 100, 2);
                        echo "\rParse " . $this->NumberFormatter->format($Fixer['Parse']) . ' ... ' . $Percent . '%';
                    }
                };
                if (strpos($Data, "\r") !== false) {
                    $Data = str_replace("\r", '', $Data);
                }
                $Fixer['StrObject'] = new \Maikuolan\Common\ComplexStringHandler(
                    "\n" . $Data . "\n",
                    '~(?<=\n)(?:\n|Expires\: \d{4}\.\d\d\.\d\d|Origin\: [A-Z]{2}|(?:\#|Tag\: |Profile\: |Defers to\: )[^\n]+| *\/\*\*(?:\n *\*[^\n]*)*\/| *\/\*\*? [^\n*]+\*\/|---\n(?:[^\n:]+\:(?:\n +[^\n:]+\: [^\n]+)+)+)+\n~',
                    function ($Data) {
                        if (!$Data = trim($Data)) {
                            return '';
                        }
                        $Output = '';
                        $EoLPos = $NEoLPos = 0;
                        while ($NEoLPos !== false) {
                            $Set = $Previous = '';
                            while (true) {
                                if (($NEoLPos = strpos($Data, "\n", $EoLPos)) === false) {
                                    $Line = trim(substr($Data, $EoLPos));
                                } else {
                                    $Line = trim(substr($Data, $EoLPos, $NEoLPos - $EoLPos));
                                    $NEoLPos++;
                                }
                                $Param = (($Pos = strpos($Line, ' ')) !== false) ? substr($Line, $Pos + 1) : 'Deny Generic';
                                if (!$Previous) {
                                    $Previous = $Param;
                                }
                                if ($Param !== $Previous) {
                                    $NEoLPos = 0;
                                    break;
                                }
                                if ($Line) {
                                    $Set .= $Line . "\n";
                                }
                                if ($NEoLPos === false) {
                                    break;
                                }
                                $EoLPos = $NEoLPos;
                            }
                            if ($Set = $Fixer['Aggregator']->aggregate(trim($Set))) {
                                $Set = preg_replace('~$~m', ' ' . $Previous, $Set);
                                $Output .= $Set . "\n";
                            }
                        }
                        return trim($Output);
                    }
                );
                $Fixer['StrObject']->iterateClosure(function ($Data) {
                    if (($Pos = strpos($Data, "---\n")) !== false && substr($Data, $Pos - 1, 1) === "\n") {
                        $YAML = substr($Data, $Pos + 4);
                        if (($HPos = strpos($YAML, "\n#")) !== false) {
                            $After = substr($YAML, $HPos);
                            $YAML = substr($YAML, 0, $HPos + 1);
                        } else {
                            $After = '';
                        }
                        $BeforeCount = substr_count($YAML, "\n");
                        $Arr = [];
                        $this->CIDRAM['YAML']->process($YAML, $Arr);
                        $NewData = substr($Data, 0, $Pos + 4) . $this->CIDRAM['YAML']->reconstruct($Arr);
                        if (($Add = $BeforeCount - substr_count($NewData, "\n") + 1) > 0) {
                            $NewData .= str_repeat("\n", $Add);
                        }
                        $NewData .= $After;
                        if ($Data !== $NewData) {
                            $Data = $NewData;
                        }
                    }
                    return "\n" . $Data;
                }, true);
                $Fixer['Memory'] = memory_get_usage();
                $this->formatFileSize($Fixer['Memory']);
                echo "\rParse " . $this->NumberFormatter->format($Fixer['Parse']) . ' ... ' . $this->NumberFormatter->format(100, 2) . '% (' . $this->timeFormat(time(), $this->Configuration['general']['time_format']) . ') <RAM: ' . $Fixer['Memory'] . ">\n\n";
                $Data = trim($Fixer['StrObject']->recompile()) . "\n";
                $Fixer['After'] = hash('sha256', $Data) . ':' . strlen($Data);
                echo 'Checksum before: ' . $Fixer['Before'] . "\nChecksum after: " . $Fixer['After'] . "\n\n";
                unset($Fixer);
                if ($Chain) {
                    $Chain .= $Data;
                } else {
                    echo $Data . "\n\n";
                }
                continue;
            }

            /** Reset the chain if the current command isn't valid. */
            $Chain = '';

            /** Let the user know that the current command isn't valid. */
            echo "I don't understand that command, sorry.\n\n";
        }
        die;
    }
}
