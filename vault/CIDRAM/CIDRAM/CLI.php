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
 * This file: CIDRAM CLI mode (last modified: 2023.05.07).
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

        $this->FE = ['DateTime' => $this->timeFormat($this->Now, $this->Configuration['general']['time_format'])];
        if ($this->Stages === []) {
            $this->Stages = array_flip(explode("\n", $this->Configuration['general']['stages']));
        }
        if ($this->Shorthand === []) {
            $this->Shorthand = array_flip(explode("\n", $this->Configuration['signatures']['shorthand']));
        }

        /** Show basic information. */
        echo sprintf(
            "\r\033[0;41m%s\033[0m\n\n\033[0;33m%s\n\033[0;32m>>\033[0m test xxx.xxx.xxx.xxx\n\n" .
            "\033[0;33m%s\n\033[0;32m>>\033[0m cidrs xxx.xxx.xxx.xxx\n\n" .
            "\033[0;33m%s\n\033[0;32m>>\033[0m test \"xxx.xxx.xxx.xxx\n\033[0;32m>>\033[0m yyy.yyy.yyy.yyy\n" .
            "\033[0;32m>>\033[0m 2002::1\n\033[0;32m>>\033[0m zzz.zzz.zzz.zzz\"\n\n" .
            "\033[0;33m%s\n\033[0;32m>>\033[0m test xxx.xxx.xxx.xxx,yyy.yyy.yyy.yyy,2002::1,zzz.zzz.zzz.zzz\n\n" .
            "\033[0;33m%s\n\033[0;32m>>\033[0m test \"aaa.aaa.aaa.aaa --no-mod\n\033[0;32m>>\033[0m bbb.bbb.bbb.bbb --no-aux\n\033[0;32m>>\033[0m ccc.ccc.ccc.ccc --no-sev --no-smv --no-ov\n" .
            "\033[0;32m>>\033[0m ddd.ddd.ddd.ddd --no-mod --no-aux --no-sev --no-smv --no-ov\"\n\n" .
            "\033[0;33m%s\n\033[0;32m>>\033[0m fread \"file1.dat\n\033[0;32m>>\033[0m file2.dat\n\033[0;32m>>\033[0m file3.dat\"\n\n" .
            "\033[0;33m%s\n\033[0;32m>>\033[0m fwrite=file.dat\n\n" .
            "\033[0;33m%s\n\033[0;32m>>\033[0m aggregate \"1.2.3.4/32\n\033[0;32m>>\033[0m 1.2.3.5/32\n\033[0;32m>>\033[0m 1.2.3.6/32\n\033[0;32m>>\033[0m 1.2.3.7/32\"\n\n" .
            "\033[0;33m%s\n\033[0;32m>>\033[0m aggregate=netmasks \"1.2.3.4/32\n\033[0;32m>>\033[0m 1.2.3.5/32\"\n\n" .
            "\033[0;33m%s\n\033[0;32m>>\033[0m fread>aggregate>fwrite=output.dat \"input1.dat\n\033[0;32m>>\033[0m input2.dat\n\033[0;32m>>\033[0m input3.dat\"\n\n" .
            "\033[0;33m%s\n\033[0;32m>>\033[0m fwrite=output.dat<aggregate<fread \"input1.dat\n\033[0;32m>>\033[0m input2.dat\n\033[0;32m>>\033[0m input3.dat\"\n\n" .
            "\033[0;33m%s\n\033[0;32m>>\033[0m fread>aggregate>fwrite=output.dat input1.dat,input2.dat,input3.dat\n\n" .
            "\033[0;33m%s\n\033[0;32m>>\033[0m print Hello World\n\n\033[0;33m%s\n\033[0;32m>>\033[0m fread>fix>fwrite=fixed.dat broken.dat\n\n\033[0;33m%s\n\n",
            $this->L10N->getString('info_cli_cidram_cli_mod'),
            $this->L10N->getString('info_cli_to_test_whethe'),
            $this->L10N->getString('info_cli_to_calculate_c'),
            $this->L10N->getString('info_cli_ipv4_ipv6_are_'),
            $this->L10N->getString('info_cli_you_can_also_u'),
            $this->L10N->getString('info_cli_by_default_ips'),
            $this->L10N->getString('info_cli_you_can_also_r'),
            $this->L10N->getString('info_cli_you_can_also_w'),
            $this->L10N->getString('info_cli_you_can_also_a'),
            $this->L10N->getString('info_cli_or_to_aggregat'),
            $this->L10N->getString('info_cli_you_can_also_c'),
            $this->L10N->getString('info_cli_or_depending_o'),
            $this->L10N->getString('info_cli_or_using_comma'),
            $this->L10N->getString('info_cli_you_can_print_'),
            $this->L10N->getString('info_cli_the_signature_'),
            $this->L10N->getString('info_cli_to_quit_type_q')
        );

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
                echo "\033[0;32m>> \033[0m";
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
            if (preg_match('~^(?:(?:[Qq]|ԛ)(?:[Uu][Ii][Tt])?|[Ee][Xx][Ii][Tt])$~', $Cmd)) {
                break;
            }

            /** Print data to the screen. */
            if ($Cmd === 'print') {
                echo "\033[0;33m";
                if (empty($Data) || (count($Data) === 1 && empty($Data[0]))) {
                    echo $this->L10N->getString('response_cli_no_print') . "\n\n";
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
                echo sprintf($this->L10N->getString('response_cli_bad_chain'), 'print') . "\n\n";
                continue;
            }

            /** Write data to a file. */
            if (substr($Cmd, 0, 7) === 'fwrite=') {
                echo "\033[0;33m";
                if (empty($Data) || (count($Data) === 1 && empty($Data[0]))) {
                    echo $this->L10N->getString('response_cli_no_write') . "\n\n";
                    continue;
                }
                if ($Chain !== '') {
                    echo sprintf($this->L10N->getString('response_cli_bad_chain'), 'fwrite') . "\n\n";
                    continue;
                }
                $WriteTo = substr($Cmd, 7);
                if (is_dir($this->Vault . $WriteTo) || !is_writable($this->Vault)) {
                    echo sprintf($this->L10N->getString('response_cli_cant_write'), $WriteTo) . "\n\n";
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
                echo sprintf($this->L10N->getString('response_cli_finished_writing'), $WriteTo) . '. <' . $this->L10N->getString('field_file') . ': ' . $FileSize . '> <RAM: ' . $MemoryUsage . ">\n\n";
                unset($WriteTo, $Handle, $BlocksToDo, $ThisBlock, $MemoryUsage, $FileSize);
                continue;
            }

            /** Read data from files. */
            if ($Cmd === 'fread') {
                echo "\033[0;33m";
                if ($Chain === '') {
                    echo "I'm not sure what to do with the file's data after reading it.\nPlease chain fread to something else so that I'll know what to do. Thanks.\n\n";
                    continue;
                }
                foreach ($Data as $ThisItem) {
                    $ThisItemTry = $this->Vault . $ThisItem;
                    if (!is_file($ThisItemTry) || !is_readable($ThisItemTry)) {
                        $ThisItemTry = $ThisItem;
                        if (!is_file($ThisItemTry) || !is_readable($ThisItemTry)) {
                            echo sprintf($this->L10N->getString('response_failed_to_access'), $ThisItem) . "\n";
                            continue;
                        }
                    }
                    $ThisItemSize = filesize($ThisItemTry);
                    $FileSize = $ThisItemSize;
                    $this->formatFileSize($FileSize);
                    if ($ThisItemSize <= 0) {
                        echo sprintf($this->L10N->getString('response_failed_to_access'), $ThisItem) . "\n";
                        continue;
                    }
                    $Chain .= $this->readFile($ThisItemTry);
                    $MemoryUsage = memory_get_usage();
                    $this->formatFileSize($MemoryUsage);
                    echo sprintf(
                        $this->L10N->getString('response_finished_reading_from') . " <%s: %s> <RAM: %s>\n",
                        $ThisItem,
                        $this->L10N->getString('field_file'),
                        $FileSize,
                        $MemoryUsage
                    );
                }
                unset($ThisItemTry, $ThisItemSize, $FileSize, $MemoryUsage);
                echo "\n";
                continue;
            }

            /** Perform IP test. */
            if ($Cmd === 'test') {
                echo "\033[0;33m";
                if (!$Chain) {
                    echo $this->L10N->getString('field_ipaddr') . ' – ' . $this->L10N->getString('field_blocked') . "\n===\n";
                }
                foreach ($Data as $ThisItem) {
                    $this->CIDRAM['Caught'] = false;
                    $this->CIDRAM['LastTestIP'] = 0;
                    $this->CIDRAM['TestResults'] = false;
                    $this->CIDRAM['ModuleErrors'] = '';
                    $this->CIDRAM['AuxErrors'] = '';
                    $Results = [
                        'Tests' => (strpos($ThisItem, ' --no-sig') === false),
                        'Modules' => (strpos($ThisItem, ' --no-mod') === false),
                        'SEV' => (strpos($ThisItem, ' --no-sev') === false),
                        'SMV' => (strpos($ThisItem, ' --no-smv') === false),
                        'OV' => (strpos($ThisItem, ' --no-ov') === false),
                        'Aux' => (strpos($ThisItem, ' --no-aux') === false)
                    ];
                    $ThisItem = preg_replace('~( --no-(?:sig|mod|s[em]v|ov|aux))+$~', '', $ThisItem);
                    $this->simulateBlockEvent($ThisItem, ...$Results);
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
                    if (!empty($this->CIDRAM['Suppress logging'])) {
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
                echo "\033[0;33m";
                if (!$Chain) {
                    echo $this->L10N->getString('field_range') . "\n===\n";
                }
                foreach ($Data as $ThisItem) {
                    if (($ThisItem = preg_replace('~[^\da-f:./]~i', '', $ThisItem)) !== '') {
                        if (!$CIDRs = $this->expandIpv4($ThisItem)) {
                            $CIDRs = $this->expandIpv6($ThisItem);
                        }
                    }

                    /** Process CIDRs. */
                    if (!empty($CIDRs)) {
                        $Factors = count($CIDRs);
                        foreach ($CIDRs as $Key => $CIDR) {
                            if ($Chain !== '') {
                                $Chain .= $CIDR . "\n";
                            } else {
                                $First = substr($CIDR, 0, strlen($CIDR) - strlen($Key + 1) - 1);
                                if ($Factors === 32) {
                                    $Last = $this->ipv4GetLast($First, $Key + 1);
                                } elseif ($Factors === 128) {
                                    $Last = $this->ipv6GetLast($First, $Key + 1);
                                } else {
                                    $Last = $this->L10N->getString('response_error');
                                }
                                echo $CIDR . ' (' . $First . ' – ' . $Last . ")\n";
                            }
                        }
                    }
                }
                unset($CIDRs);
                if (!$Chain) {
                    echo "\n";
                }
                continue;
            }

            /** Aggregate IPs/CIDRs. */
            if ($Cmd === 'aggregate' || substr($Cmd, 0, 10) === 'aggregate=') {
                echo "\033[0;33m" . $this->L10N->getString('link_ip_aggregator') . "\n===\n";
                $this->CIDRAM['Aggregator'] = new Aggregator(substr($Cmd, 10) === 'netmasks' ? 1 : 0);
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
                echo "\033[0;33m";
                if (empty($Data) || (count($Data) === 1 && empty($Data[0]))) {
                    echo $this->L10N->getString('response_cli_no_analyse') . "\n\n";
                    continue;
                }
                if ($Chain) {
                    echo sprintf($this->L10N->getString('response_cli_bad_chain'), 'matrix') . "\n\n";
                    continue;
                }
                $WriteTo = substr($Cmd, 7);
                if (is_dir($this->Vault . $WriteTo) || !is_writable($this->Vault)) {
                    echo sprintf($this->L10N->getString('response_cli_cant_write'), $WriteTo) . "\n\n";
                    continue;
                }
                $Data = implode("\n", $Data);
                $this->matrixCreate($Data, $WriteTo, true);
                unset($WriteTo);
                continue;
            }

            /** Signature file fixer. */
            if ($Cmd === 'fix') {
                echo "\033[0;33m" . $this->L10N->getString('link_fixer') . "\n===\n";
                $Data = implode("\n", $Data);
                $Fixer = [
                    'Aggregator' => new Aggregator(),
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
                $Fixer['StrObject'] = new \Maikuolan\Common\ComplexStringHandler("\n" . $Data . "\n", self::REGEX_TAGS, function (string $Data) use (&$Fixer): string {
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
                            $Param = preg_replace(['~^\s+|\s+$~', '~(\S+)\s+(\S+)~'], ['', '\1 \2'], $Param);
                            if ($Previous === '') {
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
                });
                $Fixer['StrObject']->iterateClosure(function (string $Data) {
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
                echo sprintf(
                    "%1\$s%3\$s%4\$s\n%2\$s%3\$s%5\$s\n\n",
                    $this->L10N->getString('label_checksum_before'),
                    $this->L10N->getString('label_checksum_after'),
                    $this->L10N->getString('pair_separator'),
                    $Fixer['Before'],
                    $Fixer['After']
                );
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
            echo "\033[0;33m" . $this->L10N->getString('response_cli_bad_command') . "\n\n";
        }
        die;
    }
}
