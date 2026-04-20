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
 * This file: General methods used by the front-end (last modified: 2026.04.20).
 */

namespace CIDRAM\CIDRAM;

trait FrontEndMethods
{
    /**
     * Format filesize information.
     *
     * @param int $Filesize
     * @param int $Markers Whether to include positive/negative markers.
     *      1 = Negative only. 2 = Positive only. 3 = Both.
     * @return void
     */
    private function formatFileSize(int &$Filesize, int $Markers = 0): void
    {
        if ($Filesize < 0) {
            $Filesize *= -1;
            $Marker = $Markers === 1 || $Markers === 3 ? '-' : '';
        } else {
            $Marker = $Markers === 2 || $Markers === 3 ? '+' : '';
        }
        $Scale = ['field.size.bytes', 'field.size.KB', 'field.size.MB', 'field.size.GB', 'field.size.TB', 'field.size.PB'];
        $Iterate = 0;
        while ($Filesize > 1024) {
            $Filesize /= 1024;
            $Iterate++;
            if ($Iterate > 4) {
                break;
            }
        }
        $Filesize = $Marker . $this->NumberFormatter->format($Filesize, ($Iterate === 0) ? 0 : 2) . ' ' . $this->L10N->getPlural($Filesize, $Scale[$Iterate]);
    }

    /**
     * Used by the file manager to generate a list of the files contained in a
     * working directory (normally, the vault).
     *
     * @param string $Base The path to the working directory.
     * @param bool $Recursive Whether to iterate recursively.
     * @return array A list of the files contained in the working directory.
     */
    private function fileManagerRecursiveList(string $Base, bool $Recursive = true): array
    {
        $this->FE['CanShowRecursive'] = $Recursive ? -1 : 0;
        if (!\file_exists($Base) || !\is_dir($Base) || !\is_readable($Base)) {
            if ($this->FE['state_msg'] !== '') {
                $this->FE['state_msg'] .= '<br />';
            }
            $this->FE['state_msg'] .= \sprintf($this->L10N->getString('response.Failed to access %s'), $Base);
            return [];
        }
        $Arr = [[
            'Filename' => '..',
            'CanEdit' => false,
            'Icon' => 'icon=folder',
            'Readable' => false,
            'Writable' => false,
            'Deletable' => false,
            'Directory' => true,
            'Filesize' => '',
            'Component' => $this->L10N->getString('field.Directory'),
            'Ord0' => 0,
            'Ord1' => 1,
            'Ord2' => 2,
            'Ord3' => 3,
            'FS' => -2,
            'mtime' => -2,
            'dynClass' => ''
        ]];
        $Ord = 4;
        $Dirs = [];
        $Key = 0;
        $StartTime = \time();
        $Offset = \strlen($Base);
        $VLen = \strlen($this->Vault);
        if ($Recursive) {
            $List = new \LimitIterator(new \RecursiveIteratorIterator(new \RecursiveDirectoryIterator(
                $Base,
                \RecursiveDirectoryIterator::FOLLOW_SYMLINKS | \RecursiveDirectoryIterator::SKIP_DOTS | \RecursiveDirectoryIterator::UNIX_PATHS
            ), \RecursiveIteratorIterator::SELF_FIRST), 0, 1000);
            if (\iterator_count($List) >= 1000) {
                unset($List);
                return $this->fileManagerRecursiveList($Base, false);
            }
        } else {
            $List = new \DirectoryIterator($Base);
        }
        if (isset($this->FE) && !isset($this->FE['basepathList'])) {
            $this->FE['basepathList'] = '';
        }
        $FailToNonRecursive = false;
        foreach ($List as $Item => $List) {
            /** Guard against timeouts due to huge directories. */
            if ((\time() - $StartTime) > 3) {
                if ($Recursive) {
                    $FailToNonRecursive = true;
                    break;
                }
                return $Arr;
            }

            if (!$Recursive) {
                $ThisName = $List->getFilename();
                $Item = $Base . $ThisName;
            } else {
                $ThisName = \substr($Item, $Offset);
            }
            $Key++;
            if (\preg_match('~^(?:/\.\.|./\.|\.{3})$~', \str_replace('\\', '/', \substr($Item, -3)))) {
                continue;
            }
            $Arr[$Key] = [
                'Filename' => $this->canonical($ThisName),
                'CanEdit' => false,
                'Icon' => 'icon=othernoedit',
                'Readable' => false,
                'Writable' => false,
                'Deletable' => false,
                'Ord0' => $Ord,
                'Ord1' => $Ord + 1,
                'Ord2' => $Ord + 2,
                'Ord3' => $Ord + 3,
                'FS' => -1,
                'mtime' => -2
            ];
            if (\strpos($Arr[$Key]['Filename'], '/') === false) {
                $Arr[$Key]['dynClass'] = '';
            } else {
                $this->FE['CanShowRecursive'] = 1;
                $Arr[$Key]['dynClass'] = ' isSub';
            }
            $Ord += 4;
            $Item = $this->canonical($Item);
            $ThisDir = \dirname($Item);
            if (!isset($Dirs[$ThisDir])) {
                $Dirs[$ThisDir] = \is_writable($ThisDir);
            }
            $Arr[$Key]['Deletable'] = $Dirs[$ThisDir];
            if (\is_dir($Item) && !\is_file($Item)) {
                $Arr[$Key]['Directory'] = true;
                $Arr[$Key]['Filesize'] = '';
                $Arr[$Key]['Component'] = $this->L10N->getString('field.Directory');
                $Arr[$Key]['Icon'] = 'icon=folder';
                if (!\preg_match('~["<>\r\n]~', $Item)) {
                    $this->FE['basepathList'] .= "\n            <option value=\"" . $Item . '">' . $Item . '</option>';
                }
                continue;
            }
            $Arr[$Key]['Directory'] = false;
            if (!\is_file($Item)) {
                $Arr[$Key]['Filesize'] = $Arr[$Key]['Component'] = $this->L10N->getString('field.Unknown');
                continue;
            }
            $Arr[$Key]['Readable'] = \is_readable($Item);
            $Arr[$Key]['Writable'] = \is_writable($Item);
            $Arr[$Key]['Filesize'] = $Arr[$Key]['FS'] = \filesize($Item);
            $Arr[$Key]['mtime'] = \filemtime($Item);
            $Component = '';
            $NoEdit = false;
            $LockIcon = false;
            $CheckAs = \substr($Item, 0, $VLen) === $this->Vault ? \substr($Item, $VLen) : '';
            if ($CheckAs !== '' && isset($this->Components['Files'])) {
                if (isset($this->Components['Files'][$CheckAs])) {
                    $Component = $this->Components['Files'][$CheckAs];
                } elseif (\preg_match('~^\.ht|\.safety$|^salt\.dat$~i', $CheckAs)) {
                    $Component = $this->L10N->getString('label.Safety mechanisms');
                    $NoEdit = true;
                } elseif (\preg_match('~(?:^|\.)config\.yml$~i', $CheckAs)) {
                    $Component = $this->L10N->getString('link.Configuration');
                    $Arr[$Key]['Icon'] = 'icon=configuration';
                    $LockIcon = true;
                } elseif ($this->isLogFile($CheckAs)) {
                    $Component = $this->L10N->getString('link.Logs');
                    $Arr[$Key]['Icon'] = 'icon=logs';
                    $LockIcon = true;
                } elseif ($CheckAs === 'auxiliary.yml') {
                    $Component = $this->L10N->getString('link.Auxiliary Rules');
                    $Arr[$Key]['Icon'] = 'icon=auxiliary';
                    $LockIcon = true;
                } elseif (\preg_match('~(?:(?:^|/)ignore\.dat|_custom\.dat|\.sig|\.inc)$|(?:^|/)signatures/~i', $CheckAs)) {
                    $Component = $this->L10N->getString('label.Other rules, signature files, etc');
                } elseif ($CheckAs === 'cache.dat') {
                    $Component = $this->L10N->getString('label.Cache data and temporary files');
                    $Arr[$Key]['Icon'] = 'icon=cache';
                    $LockIcon = true;
                } elseif (\preg_match('~(?:\.tmp|\.rollback|^(?:hashes|ipbypass|rl)\.dat)$~i', $CheckAs)) {
                    $Component = $this->L10N->getString('label.Cache data and temporary files');
                } elseif ($CheckAs === 'installed.yml') {
                    $Component = $this->L10N->getString('label.Component updates metadata');
                    $Arr[$Key]['Icon'] = 'icon=updates';
                    $LockIcon = true;
                }
            }
            if ($Component !== '') {
                $Component .= ' – ';
            }
            $this->formatFileSize($Arr[$Key]['Filesize']);
            $Arr[$Key]['Filesize'] = $Arr[$Key]['Filesize'] . ' – ' . $this->timeFormat($Arr[$Key]['mtime'], $this->Configuration['general']['time_format']);
            if (($ExtDel = \strrpos($Item, '.')) === false || ($Ext = \strtoupper(\substr($Item, $ExtDel + 1))) === '') {
                $Arr[$Key]['Component'] = $Component === '' ? $this->L10N->getString('field.Unknown') : $Component . $this->L10N->getString('field.Unknown');
                continue;
            }
            $Arr[$Key]['Component'] = $Component ?: $this->L10N->getString('field.Unknown');
            if (!$NoEdit && \preg_match('/^(?:[BD]AT|SVG|TEX|URL)$/', $Ext) && (!isset($this->FE['MemoryLimit']) || $Arr[$Key]['FS'] < $this->FE['MemoryLimit'])) {
                $Arr[$Key]['CanEdit'] = true;
            }
            if ($Base === $this->Vault && $Ext === 'ICO') {
                if (!$LockIcon) {
                    $Arr[$Key]['Icon'] = 'file=' . \urlencode($Arr[$Key]['Filename']);
                }
                $Arr[$Key]['Component'] = $Component . $this->L10N->getString('purpose.Graphics file');
                continue;
            }
            if ($Ext === 'EML') {
                if (!$LockIcon) {
                    $Arr[$Key]['Icon'] = 'icon=email';
                }
                $Arr[$Key]['Component'] = $Component . $this->L10N->getString('purpose.Email file');
            } elseif ($Ext === 'TORRENT') {
                if (!$LockIcon) {
                    $Arr[$Key]['Icon'] = 'icon=torrent';
                }
                $Arr[$Key]['Component'] = $Component . $this->L10N->getString('purpose.Torrent file');
            } elseif (\preg_match('/^(?:CML|DX|G3K|JDX|MML|MOL|ODF|SDF?|SMI|SXM|TEX)$/', $Ext)) {
                if (!$LockIcon) {
                    $Arr[$Key]['Icon'] = 'icon=formulas';
                }
                $Arr[$Key]['Component'] = $Component . $this->L10N->getString('purpose.Formulas file');
            } elseif (\preg_match('/^(?:A(?:BF|FM)|B(?:[DM]F|RFNT)|F(?:NT|ON[DT]?)|MGF|OTF|PF[ABM]|S(?:FD|NF)|T(?:DF|FM|T[CF])|UFO|WOFF)$/', $Ext)) {
                if (!$LockIcon) {
                    $Arr[$Key]['Icon'] = 'icon=font';
                }
                $Arr[$Key]['Component'] = $Component . $this->L10N->getString('purpose.Font file');
            } elseif (\preg_match('/^(?:CA?RD|VC(?:ARD|F))$/', $Ext)) {
                if (!$LockIcon) {
                    $Arr[$Key]['Icon'] = 'icon=card';
                }
                $Arr[$Key]['Component'] = $Component . $this->L10N->getString('purpose.Card file');
            } elseif (\preg_match('/^(?:DPS|FODP|GSLIDES|KEYNOTE|NBP?|O[DT]P|P(?:EZ|OT[MX]?|P[ST]X?|RDX|RZ)|S(?:DD|H[FW]|HOW|LP|SPSS|TI|XI)|THMX|WATCH|XDP)$/', $Ext)) {
                if (!$LockIcon) {
                    $Arr[$Key]['Icon'] = 'icon=presentation';
                }
                $Arr[$Key]['Component'] = $Component . $this->L10N->getString('purpose.Graphs or presentation file');
            } elseif (\preg_match('/^(?:0XE|INFECTED|QFU|QUARANTINED|ZL9)$/', $Ext)) {
                if (!$LockIcon) {
                    $Arr[$Key]['Icon'] = 'icon=quarantine';
                }
                $Arr[$Key]['Component'] = $Component . $this->L10N->getString('purpose.Quarantined file');
            } elseif (\preg_match(
                '/^(?:.*DB|4(?:D[CDRZ]|DIND[XY])|' .
                'A(?:CCDE|D[PT]|PR)|BOX|CHML|D(?:A[FT]|BF|TA)|' .
                'E(?:AP|GT|SS)|F(?:P[357]?|RM)|GTABLE|KEXI[CS]?|' .
                'L(?:DF|IRS)|M(?:D[AEF]|Y[DI])|N(?:[CDST]F|V2)|O(?:DBC|RA)|P(?:CONTACT|D[IX]|RC)|' .
                'R(?:E[CL]|IN)|S(?:DF|IG|KD|QL(?:ITE)?)|' .
                'UDL|W(?:ADATA|AINDX|AJOURNAL|AMODEL))$/',
                $Ext
            )) {
                if (!$LockIcon) {
                    $Arr[$Key]['Icon'] = 'icon=database';
                }
                $Arr[$Key]['Component'] = $Component . $this->L10N->getString('purpose.Database file');
            } elseif (\preg_match(
                '/^(?:[123]86|73K|89K|A(?:6P|C[CT].*|PP|SH.*)|' .
                'B(?:AT|IN|PL|TM)|C(?:CC|MD|OM.*|PL|SH)|D(?:LL|RV)|E(?:LF|X[E_])|G(?:AD.*|EO)|' .
                'I(?:N[SX]|PA|SU)|J(?:OB|SE)|K(?:O|SH)|LIB|' .
                'MS[CIPT].*|N(?:ET|LM)|O(?:[CS]X|UT)|P(?:[AI]F|RG|S1)|' .
                'R(?:EG|GS|LL|UN)|S(?:CR.*|CT|H[BS]|YS)|TLB|' .
                'U3P|V(?:AP|B[EX])|W(?:OR.*|S[FH]?)|X(?:AP|BE|EX|PI))$/',
                $Ext
            )) {
                if (!$LockIcon) {
                    $Arr[$Key]['Icon'] = 'icon=executable';
                }
                $Arr[$Key]['Component'] = $Component . $this->L10N->getString('purpose.Executable file');
            } elseif (\preg_match(
                '/^(?:16SVX|3GA|8SVX|' .
                'A(?:A[CX]?|BC|C[3DT]|IF[CF]?|IMPPL|LA?C|L[PS]|MR|PE|S[FTX]|TMOS|UD?|UDIO|UP3?|WB?)|' .
                'B(?:AND|CWAV|RSTM|WF)|' .
                'C(?:AU|DD?A|EL|PR|UST|WAV|WP)|' .
                'D(?:ARMS|FF|MKIT|RM|S[FS]|TS(?:HD|MA)?|VF|W[DP])|' .
                'E(?:NS|TF)|F(?:4A|LAC?|L[MP])|G(?:P|RIR|[SY]M)|I(?:KLAX|VS)|JAM|KERN|L(?:[AY]|OGIC)|' .
                'M(?:3U|EI|ETADATA|IDI?|KA|M[FPR]|NG|OGG|OVPKG|P[123AC]|P?4[ABP]|SC[XZ]|SF|USX?|X6HS|XL)|' .
                'N(?:IFF|MF|PR)|O(?:F[FRS]|G[AG]|MFI?|PUS|TS)|P(?:AC|CM|LS|SF|T[BFSX]|VD)|Q(?:AU[0A]?|UEYEAUDIO)|' .
                'R(?:A[MW]?|EAPEAKS|F64|IN|KA|M[AJX]?|PP(?:-BAK)?)|' .
                'S(?:ES|F[234KL]|HN|I[BD]|LN|MDL|MP|N[DG]|P[CX]|TF|WA|YN)|' .
                'T(?:AK|HD|TA|XM)|USTX?|V(?:CLS|GM|O[BCX]|PR|QF|SQX?)|' .
                'W(?:AVE?|MA|V)|X(?:PL|SPF)|YM|ZPL)$/',
                $Ext
            )) {
                if (!$LockIcon) {
                    $Arr[$Key]['Icon'] = 'icon=audio';
                }
                $Arr[$Key]['Component'] = $Component . $this->L10N->getString('purpose.Audio file');
            } elseif (\preg_match(
                '/^(?:12[3M]|A(?:B[23]|ST|WS)|BCSV|C(?:ELL|HIP|LF|SV|TS)|' .
                'D(?:EX|FG|I[FS])|E(?:DXZ?|FU|SS)|F(?:CS|ODS|P)|' .
                'G(?:NM|NUMERIC|S(?:HEET)?)|IMP|LCW|N(?:CSS|UMBERS)|' .
                'O(?:[DT]S|GWU?)|P(?:MDX?|MVX?|RESTO)|QPW|RDF|' .
                'S(?:[DTX]C|LK|XT)|T(?:AB|MVT?|SV)|UOS|VC|' .
                'W(?:K[1234IQSU]|LS|Q[12]|R1)|_?X(?:L(?:[KRST][BMXT]?|W)?))$/',
                $Ext
            )) {
                if (!$LockIcon) {
                    $Arr[$Key]['Icon'] = 'icon=spreadsheet';
                }
                $Arr[$Key]['Component'] = $Component . $this->L10N->getString('purpose.Spreadsheet file or tabular data');
            } elseif (\preg_match('/^(?:AXX|BPW|C(?:ERT?|RT)|DER|EEA|G(?:PG|XK)|HTPASSWD|JKS|K(?:DBX?|EY|ODE)|NSIGNE?|OMF|P(?:12|7[BC]|ASS(?:WORD)?|EM|FX|GP|PK|UB|WD)|SSH|TC)$/', $Ext)) {
                if (!$LockIcon) {
                    $Arr[$Key]['Icon'] = 'icon=encrypted';
                }
                $Arr[$Key]['Component'] = $Component . $this->L10N->getString('purpose.Encrypted or sensitive file');
            } elseif (\preg_match(
                '/^(?:.._|.[QZ].|7Z|' .
                'A(?:AR|CE|FA|LZ|PK|PPX?|PPXBUNDLE|R[CJK]?)?|' .
                'B(?:16Z|[AHRZ]|IN|Z2)|' .
                'C(?:A[BR]|PT|DX|FS|PIO|PT|RX)|' .
                'D(?:AR|D|EB|GC|MG)|' .
                'E(?:AR|CAB|CC|CSBX|G[GT]|MSIX(?:BUNDLE)?|S[DS]|ZIP)|' .
                'F(?:LIPCHART)?|G(?:3FC|CA|ENOZIP|Z2?)|H(?:A|KI)|I(?:MA|SO)|JAR|KGB|' .
                'L(?:AWRENCE|BR|HA|PAQ|Z[4HOX]?|ZMA)|' .
                'M(?:AR|BW|CADDON|OU|PKG|SIX?|SIXBUNDLE)|OAR|' .
                'P(?:AC?K|AF|AQ.?|AR2?|ARTIMG|EA|HAR|I[MT]|KG?|KZIP|YK)|QDA|' .
                'R(?:A[RX]|EV|[KZ]|PM|UN)|' .
                'S(?:7Z|BX|DA|E[AN]|FARK|[FQ]X|HAR|HK|ITX?|WM|Z)|' .
                'T(?:AR|[BGLX]Z2?)|' .
                'U(?:C[02AN]?|[ER]2|HA)|' .
                'W(?:A[RX]|IM)|X(?:AR|[FZ]|P3)|YZ1|Z(?:IPX?|OO|PAQ|ST|Z)?)$/',
                $Ext
            )) {
                if (!$LockIcon) {
                    $Arr[$Key]['Icon'] = 'icon=archive';
                }
                $Arr[$Key]['Component'] = $Component . $this->L10N->getString('purpose.Compressed file or archive');
            } elseif (\preg_match('/^(?:3(?:DM[FL]?|DS|DXML|MF)|A(?:BC|MF|ND)|BLEND\d*|CAD|D(?:AE|WG)|FBX|G(?:CODE|ITF|LB|LTF)|IGES|J?MESH|MTL|OBJ|P(?:BR|LY|RC)|S(?:KP|TEP|TL)|USDZ|VRML|X(?:3D|VL))$/', $Ext)) {
                if (!$LockIcon) {
                    $Arr[$Key]['Icon'] = 'icon=models';
                }
                $Arr[$Key]['Component'] = $Component . $this->L10N->getString('purpose.3D graphics file');
            } elseif (\preg_match(
                '/^(?:3(?:DMLW|DV|G2|GP(?:P2)?)|' .
                'A(?:AF|CT?|EP|I|M[CFV]|N8|NIM?|OI|RT|S[EFMS]|T3|VCHD|VIF?|WG)|' .
                'B(?:3D|DL4|FRES|IK|LOCK|LP|M[2P]|MD3|PG|RAW|RRES|TI|W)|' .
                'C(?:4D?|AL3D|ALS|AM|CP4|D[5R]|FL|GM|IT|LIP|MX|OB|OLLAB|ORE3D|PT|R2|TM)|' .
                'D(?:DS|EEP|FF|IB|IVX|JVU|NG?|PM?|RAWIO|R[CPW]|TS|V5?|VR(?:-MS)?|WF|XF)|' .
                'E(?:2D|CW|G[GT]|MF|PS?|XIF)|' .
                'F(?:[4L][BCVP]|ACT|CP|ITS|L[ARV]|LASH|LIF|MV|S)|' .
                'G(?:BR|IFV?|L[BM]|LTF|MV|PL|RF)|' .
                'H(?:DR|EI[CF]|LV)|' .
                'H?264|' .
                'H(?:EC|EI[CF])|' .
                'I(?:C[BCMO]|CNS|FF|MAGE|MG|LBM|MOVIE(?:MOBILE|PROJ)?|NT|OB?)|' .
                'J(?:AS|BIG?|F?IF?|NG|P[2GS]|PE?G?2?|X[LR])|' .
                'K(?:DENLIVE|RA)|' .
                'L(?:BM|DR|W[OS]|X[FO])|' .
                'M(?:2TS|[24KO]V|3D|4[PV]|AX?|B|D[235PX]|IFF|IMODEL|IOBJECT|IPARTICLE|KV|M3D|NG|OTN|OV(?:IE)?|P[24DEGOV]|PE?G[4V]?|RC|SP|SWMM|TS|VR|XF)|' .
                'N(?:EF|IT?F|OA|RRD|SV|W[CDF])|' .
                'O(?:DG|FF|GEX|G[MV]|T[BG])|' .
                'P(?:AL|[AB]M|C[123FTX]|D[DNS]|G[FM]|I[C123X]|ICT|LD|N[GJMS]|OV|P[JM]|RT|ROCREATE|RPROJ|S[BDP]|X[MRZ]?)|' .
                'Q(?:FX|MG|OI|T)|' .
                'R(?:3D|APHS|AW|ENDERMAN|GB|LE|MV?B?|OQ|WX)|' .
                'S(?:[AG]I|CT|I[ABD]|KIP|LDASM|LDPRT|M[DIK]|OL|RT|SA|TR|UF|V[AGI]|WF|XD)|' .
                'T(?:ARGA|GAX?|HP|IFF?|RES)|' .
                'U(?:3D|SD[AC]?)|' .
                'V(?:2D|D[AX]|DOC|EG(?:-BAK)?|IDEO|ICAR|I[MV]|ND|OB|PJ|PROJ|RML97|SD[MX]?|STX|TF|UE|WX)|' .
                'W(?:3D|BMP?|EB[MP]|FP|INGS|LMP|M[FPV]3?|RAP|RL|TV|VE)|' .
                'X(?:AR|BM|CF|BMP|PM|ISF|VID|WMV)?|' .
                'YUV|' .
                'Z(?:3D|BMX|IF))$/',
                $Ext
            )) {
                if (!$LockIcon) {
                    $Arr[$Key]['Icon'] = 'icon=graphics';
                }
                $Arr[$Key]['Component'] = $Component . $this->L10N->getString('purpose.Graphics file');
            } elseif (\preg_match(
                '/^(?:0|1ST|60.|A(?:BW|FP|MI|NS|WW)|BBEB|C(?:CF|WK)|D(?:BK|O[CT][MTX]?)|' .
                'E(?:PUB|VTX)|F(?:B[23]|DX|ODT|T[MX])|G(?:DOC|UIDE)|HWP(?:ML)?|KPUB|' .
                'L(?:RF|WP)|M(?:AN|BP|CW|WD)|O(?:D[MT]|DOC|MM|SHEET|T[HT]|XPS)|' .
                'P(?:AGES|AP|DAX|DFX?|[DE]R|PTX?|ROTONDOC|SW)|QUOX|' .
                'R(?:PT|TF)|S(?:[DT]W||X[CW])|T(?:MAC|MDX|ROFF)|U(?:O[FPST]|OML)|' .
                'VIA|W(?:B[12]|N|P[DST]|Q[12]|R[DFI])|X(?:LSX|PS)|ZABW)$/',
                $Ext
            )) {
                if (!$LockIcon) {
                    $Arr[$Key]['Icon'] = 'icon=presentation';
                }
                $Arr[$Key]['Component'] = $Component . $this->L10N->getString('purpose.Document');
            } elseif (\preg_match(
                '/^(?:[SDMPX]?HT[AM]L?X?|A(?:D[ABS]|HK|PPLESCRIPT|SC?|SC(?:II(?:DOC)?)?|SM|TOM|U3|WK)?|' .
                'B(?:AS|B|MX)?|C(?:A?ML|B[LP]|C|FG|SV|IA|JS|LASS|LJS?|LS|NF|OB|OFFEE|ONF(?:IG)?|PP|SS?|SPROJ|XX)?|' .
                'D(?:ART|BA|BPRO123|IFF|ITA)?|E(?:BUILD|FS|L|NV|RB)?|' .
                'F(?:77|90|OR|REEBASIC|RX|T[HN])?|G(?:[DO]|ED|M[6DKL])?|' .
                'H(?:ACK|[CHSX]|PP|TACCESS|XML|XX)?|I(?:BI|CI|JS|N[CFIO]|NFO|PYNB|TCL)?|' .
                'J(?:AVA|SX?|SFL|SON(?:LD)?)?|K(?:PRX|T)|' .
                'L(?:GT|ISP|OG|UA)?|M(?:[4DEL]|AP|ARKDOWN|ET(?:ALINK)?|OBI|JS|SQR)?|' .
                'N(?:EIS|EON|FO|[QT]|QP|U[CDT])?|O|' .
                'P(?:AS|DE|HP[34578SX]?|IV|L[1I]?|[MPSY]|OL|RO|S1XML|S[CDM]1|Y[CO])?|' .
                'R(?:[BY]|C2?|DP|EDS?|ESOURCES|ESX|EX[GPX]?|KTL?|SS?|XS)?|' .
                'S(?:A?ML|B[23]?|CALA|C[EIM]|CPTD?|CSS?|D[7L]|[EH]|IG|K.|LK|PIN|PRITE3|PWN|TK|VELTE|WG|YJS|YPY)?|' .
                'T(?:CL|EXT|INI|NS|SCN|SX?|XT)?|U(?:P|TF)?|V(?:B[GP]?|[BCD]PROJ|BS|IP)?|' .
                'W(?:ASM|AT)|X(?:A?ML|HT|HTML?|Q|SL)|Y(?:A?ML|NI)?|ZIM)$/',
                $Ext
            )) {
                if (!isset($this->FE['MemoryLimit']) || $Arr[$Key]['FS'] < $this->FE['MemoryLimit']) {
                    $Arr[$Key]['CanEdit'] = true;
                }
                if (!$LockIcon) {
                    $Arr[$Key]['Icon'] = 'icon=documentation';
                }
                $Arr[$Key]['Component'] = $Component . $this->L10N->getString('purpose.Plain-text file');
            } elseif (\preg_match('/^(?:DESKTOP|DIRECTORY|LI?NK|PLIST|SHORTCUT|URL|WEBLOC)$/', $Ext)) {
                if (!$LockIcon) {
                    $Arr[$Key]['Icon'] = 'icon=link';
                }
                $Arr[$Key]['Component'] = $Component . $this->L10N->getString('purpose.Shortcut file');
            } elseif (\preg_match('/^(?:CACHE|FOO|OLD|TE?MP)(-\d+)?$/', $Ext) || \substr($Arr[$Key]['Filename'], 0, 2) === '~$') {
                if (!$LockIcon) {
                    $Arr[$Key]['Icon'] = 'icon=cache';
                }
                $Arr[$Key]['Component'] = $Component . $this->L10N->getString('label.Cache data and temporary files');
            } else {
                $Arr[$Key]['Component'] = $Component . $this->L10N->getString('field.Unknown');
            }
        }
        if ($FailToNonRecursive) {
            unset($Item, $List);
            return $this->fileManagerRecursiveList($Base, false);
        }
        foreach ($Arr as $Key => &$Value) {
            $Ord -= 4;
            $Value['OrdRev0'] = $Ord;
            $Value['OrdRev1'] = $Ord + 1;
            $Value['OrdRev2'] = $Ord + 2;
            $Value['OrdRev3'] = $Ord + 3;
        }
        \uasort($Arr, function ($A, $B): int {
            if ($A['FS'] === $B['FS']) {
                return 0;
            }
            return $A['FS'] > $B['FS'] ? 1 : -1;
        });
        $Ord = 0;
        foreach ($Arr as $Key => &$Value) {
            $Value['OrdFS0'] = $Ord;
            $Value['OrdFS1'] = $Ord + 1;
            $Value['OrdFS2'] = $Ord + 2;
            $Value['OrdFS3'] = $Ord + 3;
            $Ord += 4;
        }
        foreach ($Arr as $Key => &$Value) {
            $Ord -= 4;
            $Value['OrdFSRev0'] = $Ord;
            $Value['OrdFSRev1'] = $Ord + 1;
            $Value['OrdFSRev2'] = $Ord + 2;
            $Value['OrdFSRev3'] = $Ord + 3;
        }
        \uasort($Arr, function ($A, $B): int {
            if ($A['mtime'] === $B['mtime']) {
                return 0;
            }
            return $A['mtime'] > $B['mtime'] ? 1 : -1;
        });
        $Ord = 0;
        foreach ($Arr as $Key => &$Value) {
            $Value['OrdMT0'] = $Ord;
            $Value['OrdMT1'] = $Ord + 1;
            $Value['OrdMT2'] = $Ord + 2;
            $Value['OrdMT3'] = $Ord + 3;
            $Ord += 4;
        }
        foreach ($Arr as $Key => &$Value) {
            $Ord -= 4;
            $Value['OrdMTRev0'] = $Ord;
            $Value['OrdMTRev1'] = $Ord + 1;
            $Value['OrdMTRev2'] = $Ord + 2;
            $Value['OrdMTRev3'] = $Ord + 3;
        }
        \ksort($Arr);
        return $Arr;
    }

    /**
     * Generates a list of the files in a working directory as array keys.
     *
     * @param string $Base The path to the working directory.
     * @param bool $Rescursive Whether to search the directory recursively.
     * @return array A list of the files in the working directory as array keys.
     */
    private function filesAsKeys(string $Base, bool $Rescursive = true): array
    {
        $Arr = [];
        $Offset = \strlen($Base);
        if ($Rescursive) {
            $List = new \LimitIterator(new \RecursiveIteratorIterator(new \RecursiveDirectoryIterator(
                $Base,
                \RecursiveDirectoryIterator::FOLLOW_SYMLINKS | \RecursiveDirectoryIterator::SKIP_DOTS | \RecursiveDirectoryIterator::UNIX_PATHS
            ), \RecursiveIteratorIterator::SELF_FIRST), 0, 1000);
            foreach ($List as $Item => $List) {
                $Arr[\substr($Item, $Offset)] = true;
            }
        } else {
            $List = new \DirectoryIterator($Base);
            foreach ($List as $Item) {
                $Item = $Item->getPathname();
                if (\is_dir($Item) || !\is_file($Item) || \preg_match('~^(?:/\.\.|./\.|\.{3})$~', \str_replace('\\', '/', \substr($Item, -3)))) {
                    continue;
                }
                $Arr[\substr($Item, $Offset)] = true;
            }
        }
        return $Arr;
    }

    /**
     * Checks paths for directory traversal and ensures that they only contain
     * expected characters.
     *
     * @param string $Path The path to check.
     * @return bool False when directory traversals and/or unexpected characters
     *      are detected, and true otherwise.
     */
    private function pathSecurityCheck(string $Path): bool
    {
        $Path = \str_replace('\\', '/', $Path);
        if (\preg_match('~(?://|[^!\d\w\._-]$)~i', $Path) || !$this->freeFromTraversal($Path)) {
            return false;
        }
        $Path = \preg_split('@/@', $Path, -1, \PREG_SPLIT_NO_EMPTY);
        $Valid = true;
        \array_walk($Path, function ($Segment) use (&$Valid): void {
            if (empty($Segment) || \preg_match('/(?:[\x00-\x1F\x7F]+|^\.+$)/i', $Segment)) {
                $Valid = false;
            }
        });
        return $Valid;
    }

    /**
     * Determine the final IP address covered by an IPv4 CIDR. This method is
     * used by the range calculator.
     *
     * @param string $First The first IP address.
     * @param int $Factor The range number (or CIDR factor number).
     * @return string The final IP address.
     */
    private function ipv4GetLast(string $First, int $Factor): string
    {
        $Octets = \explode('.', $First);
        $Split = $Bracket = $Factor / 8;
        $Split -= \floor($Split);
        $Split = (int)(8 - ($Split * 8));
        $Octet = \floor($Bracket);
        if ($Octet < 4) {
            $Octets[$Octet] += (2 ** $Split) - 1;
        }
        while ($Octet < 3) {
            $Octets[$Octet + 1] = 255;
            $Octet++;
        }
        return \implode('.', $Octets);
    }

    /**
     * Determine the final IP address covered by an IPv6 CIDR. This method is
     * used by the range calculator.
     *
     * @param string $First The first IP address.
     * @param int $Factor The range number (or CIDR factor number).
     * @return string The final IP address.
     */
    private function ipv6GetLast(string $First, int $Factor): string
    {
        if (\strpos($First, '::') !== false) {
            $Abr = 7 - \substr_count($First, ':');
            $Arr = [':0:', ':0:0:', ':0:0:0:', ':0:0:0:0:', ':0:0:0:0:0:', ':0:0:0:0:0:0:'];
            $First = \str_replace('::', $Arr[$Abr], $First);
        }
        $Octets = \explode(':', $First);
        $Octet = 8;
        while ($Octet > 0) {
            $Octet--;
            $Octets[$Octet] = \hexdec($Octets[$Octet]);
        }
        $Split = $Bracket = $Factor / 16;
        $Split -= \floor($Split);
        $Split = (int)(16 - ($Split * 16));
        $Octet = \floor($Bracket);
        if ($Octet < 8) {
            $Octets[$Octet] += (2 ** $Split) - 1;
        }
        while ($Octet < 7) {
            $Octets[$Octet + 1] = 65535;
            $Octet++;
        }
        $Octet = 8;
        while ($Octet > 0) {
            $Octet--;
            $Octets[$Octet] = \dechex($Octets[$Octet]);
        }
        $Last = \implode(':', $Octets);
        if (\strpos($Last . '/', ':0:0/') !== false) {
            $Last = \preg_replace('/(:0){2,}$/i', '::', $Last, 1);
        } elseif (\strpos($Last, ':0:0:') !== false) {
            $Last = \preg_replace('/(?:(:0)+:(0:)+|::0$)/i', '::', $Last, 1);
        }
        return $Last;
    }

    /**
     * Prepares component name (used by both the updater and the file manager).
     *
     * @param array $Arr Metadata of the component to be prepared.
     * @param string $Key A key to use to help find L10N data for the component name.
     * @return void
     */
    private function prepareName(array &$Arr, string $Key = ''): void
    {
        if (($Try = $this->L10N->getString('Name.' . $Key)) !== '') {
            $Arr['Name'] = $Try;
        } elseif (!isset($Arr['Name'])) {
            $Arr['Name'] = '';
        }
    }

    /**
     * Get the appropriate path for a specified asset as per the defined theme.
     *
     * @param string $Asset The asset filename.
     * @param bool $CanFail Is failure acceptable? (Default: False)
     * @throws Exception if the asset can't be found.
     * @return string The asset path.
     */
    private function getAssetPath(string $Asset, bool $CanFail = false): string
    {
        /** Guard against unsafe paths and traversal attacks. */
        if (\preg_match('~[^\da-z._]~i', $Asset) || !$this->freeFromTraversal($Asset)) {
            return '';
        }

        /** Non-default theme assets. */
        if (\file_exists($this->AssetsPath . 'frontend' . \DIRECTORY_SEPARATOR . $this->Configuration['frontend']['theme'] . \DIRECTORY_SEPARATOR . $Asset)) {
            return $this->AssetsPath . 'frontend' . \DIRECTORY_SEPARATOR . $this->Configuration['frontend']['theme'] . \DIRECTORY_SEPARATOR . $Asset;
        }

        /** Default theme assets. */
        if (\file_exists($this->AssetsPath . 'frontend' . \DIRECTORY_SEPARATOR . 'default' . \DIRECTORY_SEPARATOR . $Asset)) {
            return $this->AssetsPath . 'frontend' . \DIRECTORY_SEPARATOR . 'default' . \DIRECTORY_SEPARATOR . $Asset;
        }

        /** Front-end assets base directory assets. */
        if (\file_exists($this->AssetsPath . 'frontend' . \DIRECTORY_SEPARATOR . $Asset)) {
            return $this->AssetsPath . 'frontend' . \DIRECTORY_SEPARATOR . $Asset;
        }

        /** Failure. */
        if ($CanFail) {
            return '';
        }
        throw new \Exception('Asset not found');
    }

    /**
     * JavaScript code for localising numbers locally.
     *
     * @return string The JavaScript code.
     */
    private function numberJs(): string
    {
        return \sprintf(
            $this->readFile($this->getAssetPath('numberJs.js')),
            $this->NumberFormatter->getSetJSON($this->NumberFormatter->ConversionSet),
            $this->NumberFormatter->GroupSeparator,
            $this->NumberFormatter->GroupSize,
            $this->NumberFormatter->GroupOffset,
            $this->NumberFormatter->DecimalSeparator,
            $this->NumberFormatter->Base
        );
    }

    /**
     * Switch control for front-end page filters (currently used only by the IP tracking page).
     *
     * @param array $Switches Names of available switches.
     * @param string $Selector Switch selector variable.
     * @param bool $StateModified Determines whether the filter state has been modified.
     * @param string $Redirect Reconstructed path to redirect to when the state changes.
     * @param string $Options Reconstructed filter controls.
     * @return void
     */
    private function filterSwitch(array $Switches, string $Selector, bool &$StateModified, string &$Redirect, string &$Options): void
    {
        foreach ($Switches as $Switch) {
            $State = (!empty($Selector) && $Selector === $Switch);
            $this->FE[$Switch] = empty($this->CIDRAM['QueryVars'][$Switch]) ? false : (
                ($this->CIDRAM['QueryVars'][$Switch] === 'true' && !$State) ||
                ($this->CIDRAM['QueryVars'][$Switch] !== 'true' && $State)
            );
            if ($State) {
                $StateModified = true;
            }
            if ($this->FE[$Switch]) {
                $Redirect .= '&' . $Switch . '=true';
                $LangItem = 'switch-' . $Switch . '-set-false';
            } else {
                $Redirect .= '&' . $Switch . '=false';
                $LangItem = 'switch-' . $Switch . '-set-true';
            }
            $Label = $this->L10N->getString($LangItem) ?: $LangItem;
            $Options .= '<option value="' . $Switch . '">' . $Label . '</option>';
        }
    }

    /**
     * Normalise linebreaks.
     *
     * @param string $Data The data to normalise.
     * @return void
     */
    private function normaliseLinebreaks(string &$Data)
    {
        if (\strpos($Data, "\r")) {
            $Data = (\strpos($Data, "\r\n") !== false) ? \str_replace("\r", '', $Data) : \str_replace("\r", "\n", $Data);
        }
    }

    /**
     * Signature files handler for sections list.
     *
     * @param array $Files The signature files to process.
     * @return string Generated sections list data.
     */
    private function sectionsHandler(array $Files): string
    {
        if (!isset($this->CIDRAM['Ignore'])) {
            $this->CIDRAM['Ignore'] = $this->fetchIgnores();
        }
        $this->FE['SL_Signatures'] = 0;
        $this->FE['SL_Sections'] = 0;
        $this->FE['SL_Files'] = \count($Files);
        $this->FE['SL_Unique'] = 0;
        $Out = '';
        $SectionsForIgnore = [];
        $SignaturesCount = [];
        $FilesCount = [];
        $SectionMeta = [];
        $ThisSectionMeta = [];
        foreach ($Files as $File) {
            if ($File === '' || $this->isReserved($File)) {
                continue;
            }
            $Data = $this->readFile($this->SignaturesPath . $File);
            if (\strlen($Data) === 0) {
                continue;
            }
            $this->normaliseLinebreaks($Data);
            $Data = "\n" . $Data . "\n";
            $PosB = -1;
            $ThisCount = 0;
            $OriginCount = 0;
            while (true) {
                $PosA = \strpos($Data, "\n", $PosB + 1);
                if ($PosA === false) {
                    break;
                }
                $PosA++;
                if (!$PosB = \strpos($Data, "\n", $PosA)) {
                    break;
                }
                $Line = \substr($Data, $PosA, $PosB - $PosA);
                $PosB--;
                if (\substr($Line, -1) === "\n") {
                    $Line = \substr($Line, 0, -1);
                }
                if (\substr($Line, 0, 5) === 'Tag: ') {
                    $Tag = \substr($Line, 5);
                    $this->FE['SL_Sections']++;
                    if (!isset($SectionsForIgnore[$Tag])) {
                        $SectionsForIgnore[$Tag] = empty($this->CIDRAM['Ignore'][$Tag]);
                    }
                    foreach ($ThisSectionMeta as $ThisOrigin => $ThisQuantity) {
                        if (!isset($SectionsForIgnore[$Tag . ':' . $ThisOrigin])) {
                            $SectionsForIgnore[$Tag . ':' . $ThisOrigin] = empty($this->CIDRAM['Ignore'][$Tag . ':' . $ThisOrigin]);
                        }
                    }
                    if (!isset($SignaturesCount[$Tag])) {
                        $SignaturesCount[$Tag] = 0;
                    }
                    $SignaturesCount[$Tag] += $ThisCount;
                    $ThisCount = 0;
                    if (!isset($FilesCount[$Tag])) {
                        $FilesCount[$Tag] = [];
                    }
                    if (!isset($FilesCount[$Tag][$File])) {
                        $FilesCount[$Tag][$File] = true;
                    }
                    if (!isset($SectionMeta[$Tag])) {
                        $SectionMeta[$Tag] = [];
                    }
                    foreach ($ThisSectionMeta as $ThisOrigin => $ThisQuantity) {
                        if (!isset($SectionMeta[$Tag][$ThisOrigin])) {
                            $SectionMeta[$Tag][$ThisOrigin] = 0;
                        }
                        $SectionMeta[$Tag][$ThisOrigin] += $ThisQuantity;
                    }
                    $ThisSectionMeta = [];
                    continue;
                }
                if (\substr($Line, 0, 8) === 'Origin: ') {
                    $Origin = \substr($Line, 8);
                    if (!isset($ThisSectionMeta[$Origin])) {
                        $ThisSectionMeta[$Origin] = 0;
                    }
                    $ThisSectionMeta[$Origin] += $OriginCount;
                    $OriginCount = 0;
                    continue;
                }
                if (!$Line || \preg_match('~^([\n#]|Expires|Defers to)~', $Line) || \strpos($Line, '/') === false) {
                    continue;
                }
                $ThisCount++;
                $OriginCount++;
                $this->FE['SL_Signatures']++;
            }
        }
        $Class = 'ng2';
        \ksort($SectionMeta);
        $this->FE['SL_Unique'] = \count($SectionMeta);
        foreach ($SectionMeta as $Section => $Counts) {
            $ThisCount = $SignaturesCount[$Section] ?? 0;
            $ThisFiles = isset($FilesCount[$Section]) ? \count($FilesCount[$Section]) : 0;
            $ThisCount = \sprintf(
                $this->L10N->getPlural($ThisFiles, 'label.%s across %s file'),
                \sprintf(
                    $this->L10N->getPlural($ThisCount, 'label.%s signature'),
                    '<span class="txtRd">' . $this->NumberFormatter->format($ThisCount) . '</span>'
                ),
                '<span class="txtRd">' . $this->NumberFormatter->format($ThisFiles) . '</span>'
            );
            $Class = (isset($Class) && $Class === 'ng2') ? 'ng1' : 'ng2';
            $SectionSafe = \preg_replace('~[^\da-z]~i', '', $Section);
            $SectionLabel = $Section . ' (' . $ThisCount . ')';
            $OriginOut = '';
            \arsort($Counts);
            foreach ($Counts as $Origin => $Quantity) {
                $State = !empty($SectionsForIgnore[$Section . ':' . $Origin]);
                $OriginSafe = $SectionSafe . \preg_replace('~[^A-Z]~', '', $Origin);
                $Quantity = \sprintf(
                    $this->L10N->getPlural($Quantity, 'label.%s signature'),
                    $this->NumberFormatter->format($Quantity)
                );
                $OriginDisplay = '<code>' . $Origin . '</code>' . ($this->FE['Flags'] ? ' – <span class="flag ' . $Origin . '"></span>' : '');
                $OriginOut .= "\n" . \sprintf(
                    '<div class="sectionControlNotIgnored%s">%s – %s – %s<a href="javascript:void()" onclick="javascript:slx(\'%s:%s\',\'%s</a></div>',
                    $OriginSafe . '" style="transform:skew(-18deg)' . ($State ? '' : ';display:none'),
                    $OriginDisplay,
                    $Quantity,
                    '',
                    $Section,
                    $Origin,
                    'ignore\',\'sectionControlNotIgnored' . $OriginSafe . '\',\'sectionControlIgnored' . $OriginSafe . '\')">' . $this->L10N->getString('label.Ignore this')
                ) . \sprintf(
                    '<div class="sectionControlIgnored%s">%s – %s – %s<a href="javascript:void()" onclick="javascript:slx(\'%s:%s\',\'%s</a></div>',
                    $OriginSafe . '" style="transform:skew(-18deg);filter:grayscale(75%) contrast(50%)' . ($State ? ';display:none' : ''),
                    $OriginDisplay,
                    $Quantity,
                    $this->L10N->getString('field.Ignored') . ' – ',
                    $Section,
                    $Origin,
                    'unignore\',\'sectionControlIgnored' . $OriginSafe . '\',\'sectionControlNotIgnored' . $OriginSafe . '\')">' . $this->L10N->getString('label.Unignore this')
                );
            }
            $State = !empty($SectionsForIgnore[$Section]);
            $Out .= "\n" . \sprintf(
                '<div class="%s sectionControlNotIgnored%s"><strong>%s%s</strong><br />%s</div>',
                $Class,
                $State ? $SectionSafe : $SectionSafe . '" style="display:none',
                $SectionLabel,
                ' – <a href="javascript:void()" onclick="javascript:slx(\'' . $Section . '\',\'ignore\',\'sectionControlNotIgnored' . $SectionSafe . '\',\'sectionControlIgnored' . $SectionSafe . '\')">' . $this->L10N->getString('label.Ignore this') . '</a>',
                $OriginOut
            ) . \sprintf(
                '<div class="%s sectionControlIgnored%s"><strong>%s%s</strong><br />%s</div>',
                $Class,
                $SectionSafe . '" style="filter:grayscale(50%) contrast(50%)' . ($State ? ';display:none' : ''),
                $SectionLabel . ' – ' . $this->L10N->getString('field.Ignored'),
                ' – <a href="javascript:void()" onclick="javascript:slx(\'' . $Section . '\',\'unignore\',\'sectionControlIgnored' . $SectionSafe . '\',\'sectionControlNotIgnored' . $SectionSafe . '\')">' . $this->L10N->getString('label.Unignore this') . '</a>',
                $OriginOut
            );
        }
        return $Out;
    }

    /**
     * Assign some basic variables (initial prepwork for most front-end pages).
     *
     * @param string $Title The page title.
     * @param string $Tips The page "tip" to include ("Hello username! Here you can...").
     * @param bool $JS Whether to include the standard front-end JavaScript boilerplate.
     * @return void
     */
    private function initialPrepwork(string $Title = '', string $Tips = '', bool $JS = true): void
    {
        /** Set page title. */
        $this->FE['FE_Title'] = 'CIDRAM – ' . $Title;

        /** Fetch and prepare username. */
        if ($Username = (empty($this->FE['User']) ? '' : $this->FE['User'])) {
            $Username = \preg_replace('~^([^<>]+)<[^<>]+>$~', '\1', $Username);
            if (($AtChar = \strpos($Username, '@')) !== false) {
                $Username = \substr($Username, 0, $AtChar);
            }
        }

        /** Prepare page greeting. */
        $this->FE['Greeting'] = \sprintf($this->L10N->getString('tip.Greeting'), $Username);

        /** Prepare page tooltip/description. */
        $this->FE['FE_Tip'] = $this->parseVars([], $Tips);

        /** Load main front-end JavaScript data. */
        $this->FE['JS'] = $JS ? "\n" . $this->parseVars([], $this->readFile($this->getAssetPath('scripts.js')), true) : '';
    }

    /**
     * Send page output for front-end pages (plus some other final prepwork).
     *
     * @return string Page output.
     */
    private function sendOutput(): string
    {
        if ($this->FE['JS']) {
            $this->FE['JS'] = "\n<script type=\"text/javascript\">" . $this->FE['JS'] . '</script>';
        }
        $Template = $this->FE['Template'];
        $Labels = [];
        $Segments = [];
        if (isset($this->FE['UserState']) && ($this->FE['UserState'] === 1 || $this->FE['UserState'] === 2)) {
            $Labels[] = 'Logged In';
            $Segments[] = 'Logged Out';
        } else {
            $Labels[] = 'Logged Out';
            $Segments[] = 'Logged In';
        }
        foreach ($Labels as $Label) {
            $Template = \str_replace(['<!-- ' . $Label . ' Begin -->', '<!-- ' . $Label . ' End -->'], '', $Template);
        }
        foreach ($Segments as $Segment) {
            while ($Before = $Template) {
                $BPos = \strpos($Template, '<!-- ' . $Segment . ' Begin -->');
                $EPos = $BPos === false ? false : \strpos($Template, '<!-- ' . $Segment . ' End -->', $BPos);
                if ($BPos !== false && $EPos !== false) {
                    $Template = \substr($Template, 0, $BPos) . \substr($Template, $EPos + \strlen($Segment) + 13);
                }
                if ($Template === $Before) {
                    break;
                }
            }
        }
        return $this->embedAssets($this->parseVars($this->FE, $Template, true));
    }

    /**
     * Confirm whether a file is a log file (used by the file manager and the
     * logs page).
     *
     * @param string $File The path/name of the file to be confirmed.
     * @param string $Normalised A normalised name for the log, useful for better sorting.
     * @return bool True if it's a log file; False if it isn't.
     */
    private function isLogFile(string $File, string &$Normalised = ''): bool
    {
        $FileLC = \strtolower($File);
        $Normalised = '';
        if ($this->Events->assigned('isLogFile')) {
            $this->Events->fireEvent('isLogFile');
            $this->Events->destroyEvent('isLogFile');
        }
        if (!isset($this->CIDRAM['LogPatterns']) || !\is_array($this->CIDRAM['LogPatterns'])) {
            return false;
        }
        foreach ($this->CIDRAM['LogPatterns'] as $LogPattern) {
            if (\preg_match($LogPattern, $FileLC, $Matches)) {
                if (isset($Matches['yyyy'])) {
                    $Normalised .= $Matches['yyyy'] . '.';
                } elseif (isset($Matches['yy'])) {
                    $Normalised .= '20' . $Matches['yy'] . '.';
                }
                if (isset($Matches['Mon']) && !isset($Matches['mm']) && !isset($Matches['m'])) {
                    static $Months = ['Jan' => '01', 'Feb' => '02', 'Mar' => '03', 'Apr' => '04', 'May' => '05', 'Jun' => '06', 'Jul' => '07', 'Aug' => '08', 'Sep' => '09', 'Oct' => '10', 'Nov' => '11', 'Dec' => '12'];
                    if (isset($Months[$Matches['Mon']])) {
                        $Matches['mm'] = $Months[$Matches['Mon']];
                    }
                }
                foreach (['m', 'd', 'h', 'i', 's'] as $Unit) {
                    $Double = $Unit . $Unit;
                    if (isset($Matches[$Double])) {
                        $Normalised .= $Matches[$Double] . '.';
                    } elseif (isset($Matches[$Unit])) {
                        $Normalised .= (\strlen($Matches[$Unit]) < 2 ? '0' : '') . $Matches[$Unit] . '.';
                    }
                }
                $Normalised .= "\xFF" . $File;
                return true;
            }
        }
        if (\preg_match('~\.log(?:\.gz)?$~', \strtolower($FileLC))) {
            $Normalised = "\xFF" . $File;
            return true;
        }
        return false;
    }

    /**
     * Generates JavaScript prompts for confirmation front-end actions (used by
     * the IP tracking and statistics pages).
     *
     * @param string $Action The action being taken to be confirmed.
     * @param string $Form The ID of the form to be submitted when the action is confirmed.
     * @return string The JavaScript snippet.
     */
    private function generateConfirmation(string $Action, string $Form): string
    {
        return 'javascript:confirm(\'' . $this->escapeJsInHTML(\sprintf($this->L10N->getString('confirm.Action'), $Action)) . '\')&&document.getElementById(\'' . $Form . '\').submit()';
    }

    /**
     * A quicker way to add entries to the front-end logs file.
     *
     * @param string $IPAddr The IP address triggering the log event.
     * @param string $User The user triggering the log event.
     * @param string $Message The message to be logged.
     * @return void
     */
    private function frontendLogger(string $IPAddr, string $User, string $Message): void
    {
        /** Guard. */
        if (
            empty($this->FE['DateTime']) ||
            $this->Configuration['frontend']['frontend_log'] === '' ||
            ($File = $this->buildPath($this->Vault . $this->Configuration['frontend']['frontend_log'])) === ''
        ) {
            return;
        }

        $Data = $this->Configuration['legal']['pseudonymise_ip_addresses'] ? $this->pseudonymiseIp($IPAddr) : $IPAddr;
        $Data .= ' - ' . $this->FE['DateTime'] . ' - "' . $User . '" - ' . $Message . "\n";

        $Truncate = $this->readBytes($this->Configuration['logging']['truncate']);
        $WriteMode = (!\file_exists($File) || $Truncate > 0 && \filesize($File) >= $Truncate) ? 'wb' : 'ab';
        $Handle = \fopen($File, $WriteMode);
        \fwrite($Handle, $Data);
        \fclose($Handle);
        if ($WriteMode === 'wb') {
            $this->logRotation($this->Configuration['frontend']['frontend_log']);
        }
    }

    /**
     * Generates very simple 8-digit numbers used for 2FA.
     *
     * @return int An 8-digit number.
     */
    private function twoFactorNumber(): int
    {
        try {
            $Key = \random_int(self::TWO_FACTOR_MIN_INT, self::TWO_FACTOR_MAX_INT);
        } catch (\Exception $e) {
            $Key = \rand(self::TWO_FACTOR_MIN_INT, self::TWO_FACTOR_MAX_INT);
        }
        return $Key;
    }

    /**
     * Generate a clickable list from an array (used by the cache data page).
     *
     * @param array $Arr The array to convert from.
     * @param string $DeleteKey The key to use for async calls to delete a cache entry.
     * @param int $Depth Current cache entry list depth.
     * @param string $ParentKey An optional key of the parent data source.
     * @param string $ListSection An optional HTML ID for the parent data source.
     * @return string The generated clickable list.
     */
    private function arrayToClickableList(array $Arr = [], string $DeleteKey = '', int $Depth = 0, string $ParentKey = '', string $ListSection = ''): string
    {
        if ($Depth === 0) {
            $this->CIDRAM['ListGroups'] = [];
            $NewArr = [];
            foreach ($Arr as $Key => $Value) {
                $Matches = [];
                if (\preg_match('~^([^-]+)-(.+)$~', $Key, $Matches) && !isset($Arr[$Matches[1]])) {
                    if (!isset($NewArr[$Matches[1]])) {
                        $NewArr[$Matches[1]] = [];
                        $this->CIDRAM['ListGroups'][$Matches[1]] = true;
                    }
                    $NewArr[$Matches[1]][$Matches[2]] = $Value;
                    continue;
                }
                $NewArr[$Key] = $Value;
            }
            $Arr = $NewArr;
            unset($NewArr);
        }
        $Output = '';
        $Count = \count($Arr);
        foreach ($Arr as $Key => $Value) {
            if ((\is_string($Value) && !$this->Demojibakefier->checkConformity($Value)) || \is_null($Value)) {
                continue;
            }
            if ($Depth === 1 && isset($this->CIDRAM['ListGroups'][$ParentKey])) {
                $Delete = \sprintf(
                    ' – (<span onclick="javascript:%1$s(\'%2$s\'%3$s)"><code><span class="auxicon auxrd delete" title="%4$s"></span><span class="s auxicontxt">%4$s</span></code></span>)',
                    $DeleteKey,
                    $this->escapeJsInHTML($ParentKey . '-' . $Key),
                    $ListSection === '' ? '' : ',\'' . $ListSection . '\'',
                    $this->L10N->getString('field.Delete')
                );
                $Output .= '<span id="' . $this->escapeJsInHTML($ParentKey . '-' . $Key) . 'Container' . $ListSection . '">';
            } elseif ($Depth === 0) {
                $Delete = \sprintf(
                    ' – (<span onclick="javascript:%1$s(\'%2$s\'%3$s)"><code><span class="auxicon auxrd delete" title="%4$s"></span><span class="s auxicontxt">%4$s</span></code></span>)',
                    $DeleteKey,
                    (isset($this->CIDRAM['ListGroups'][$Key]) ? '^' : '') . $this->escapeJsInHTML($Key),
                    $ListSection === '' ? '' : ',\'' . $ListSection . '\'',
                    $this->L10N->getString('field.Delete')
                );
                $Output .= '<span id="' . $this->escapeJsInHTML($Key) . 'Container' . $ListSection . '">';
            } else {
                $Delete = '';
            }
            $Output .= '<li>';
            if (\is_string($Value)) {
                if (\substr($Value, 0, 2) === '{"' && \substr($Value, -2) === '"}') {
                    $Try = \json_decode($Value, true);
                    if ($Try !== null) {
                        $Value = $Try;
                    }
                } elseif (
                    \preg_match('~\.ya?ml$~i', $Key) ||
                    (\preg_match('~^(?:Data|\d+)$~', $Key) && \preg_match('~\.ya?ml$~i', $ParentKey))
                ) {
                    $Try = [];
                    if ($this->YAML->process($Value, $Try) && !empty($Try)) {
                        $Value = $Try;
                    }
                } elseif (\substr($Value, 0, 2) === '["' && \substr($Value, -2) === '"]' && \strpos($Value, '","') !== false) {
                    $Value = \explode('","', \substr($Value, 2, -2));
                }
            }
            if (\is_array($Value)) {
                if ($Depth === 0 || ($Depth === 1 && isset($this->CIDRAM['ListGroups'][$ParentKey]))) {
                    $SizeField = $this->L10N->getString('field.size.Total size') ?: 'Size';
                    $Size = isset($Value['Data']) && \is_string($Value['Data']) ? \strlen($Value['Data']) : (
                        isset($Value[0]) && \is_string($Value[0]) ? \strlen($Value[0]) : false
                    );
                    if ($Size !== false) {
                        $this->formatFileSize($Size);
                        $Value[$SizeField] = $Size;
                    }
                }
                $Output .= '<span class="comCat"><code class="s">' . \str_replace(['<', '>'], ['&lt;', '&gt;'], $Key) . '</code></span><span class="fixComCatBtnVert">' . $Delete . '</span><ul class="comSub">';
                $Output .= $this->arrayToClickableList($Value, $DeleteKey, $Depth + 1, $Key, $ListSection) . '</ul>';
            } elseif (\is_scalar($Value)) {
                if ($Key === 'Time' && \preg_match('~^\d+$~', $Value)) {
                    $Key = $this->L10N->getString('label.Expires');
                    $Value = $this->timeFormat($Value, $this->Configuration['general']['time_format']);
                }
                $Class = ($Key === $this->L10N->getString('field.size.Total size') || $Key === $this->L10N->getString('label.Expires')) ? 'txtRd' : 's';
                $Text = ($Count === 1 && $Key === 0) ? $Value : $Key . ($Class === 's' ? ' => ' : ' ') . $Value;
                $Output .= '<code class="' . $Class . ' canBreak">' . $this->ltrInRtf(
                    \str_replace(['<', '>'], ['&lt;', '&gt;'], $Text)
                ) . '</code>' . $Delete;
            }
            $Output .= '</li>';
            if ($Depth === 1 && isset($this->CIDRAM['ListGroups'][$ParentKey])) {
                $Output .= '</span>';
            } elseif ($Depth === 0) {
                $Output .= '<br /></span>';
            }
        }
        if ($Depth === 0) {
            unset($this->CIDRAM['ListGroups']);
        }
        return $Output;
    }

    /**
     * Supplied string is used to generate arbitrary values used as RGB
     * information for CSS styling (used by the file manager).
     *
     * @param string $String The supplied string to use.
     * @param int $Mode Whether to return the values as an array of integers,
     *      a hash-like string, or both.
     * @return string|array an array of integers, a hash-like string, or both.
     */
    private function rgb(string $String = '', int $Mode = 0)
    {
        $Diff = [247, 127, 31];
        if (\is_string($String) && !empty($String)) {
            $String = \str_split($String);
            foreach ($String as $Char) {
                $Char = \ord($Char);
                $Diff[0] = ($Diff[0] >> 1) + (($Diff[2] & 1) === 1 ? 128 : 0);
                $Diff[1] = ($Diff[1] >> 1) + (($Diff[0] & 1) === 1 ? 128 : 0);
                $Diff[2] = ($Diff[2] >> 1) + (($Diff[1] & 1) === 1 ? 128 : 0);
                $Diff[0] ^= $Char;
            }
        }
        if ($Mode === 1) {
            return $Diff;
        }
        for ($Hash = '', $Index = 0; $Index < 3; $Index++) {
            $Hash .= \str_pad(\bin2hex(\chr($Diff[$Index])), 2, '0', \STR_PAD_LEFT);
        }
        if ($Mode === 2) {
            return $Hash;
        }
        return ['Values' => $Diff, 'Hash' => $Hash];
    }

    /**
     * Provides stronger support for LTR inside RTL text.
     *
     * @param string $String The string to work with.
     * @return string The string, modified if necessary.
     */
    private function ltrInRtf(string $String = ''): string
    {
        /** If the page isn't RTL, the string should be returned verbatim. */
        if ($this->L10N->Directionality !== 'rtl') {
            return $String;
        }

        /** Modify the string to better suit RTL directionality and return it. */
        while (true) {
            $NewString = \preg_replace(
                ['~^(.+)( +)-&gt;( +)(.+)$~i', '~^(.+)-&gt;(.+)$~i', '~^(.+)( +)➡( +)(.+)$~i', '~^(.+)➡(.+)$~i', '~^(.+)( +)=&gt;( +)(.+)$~i', '~^(.+)=&gt;(.+)$~i'],
                ['\4\2&lt;-\3\1', '\2&lt;-\1', '\4\2⬅\3\1', '\2⬅\1', '\4\2&lt;=\3\1', '\2&lt;=\1'],
                $String
            );
            if ($NewString === $String) {
                return $NewString;
            }
            $String = $NewString;
        }
    }

    /**
     * Splits a CIDR into two smaller CIDRs of the same total value.
     *
     * @param string $CIDR The CIDR to split.
     * @return array An array containing two elements (the smaller CIDRs), or an
     *      empty array on failure (e.g., supplied data isn't a valid CIDR).
     */
    private function splitCidr(string $CIDR): array
    {
        if (($Pos = \strpos($CIDR, '/')) === false) {
            return [];
        }
        $Base = \substr($CIDR, 0, $Pos);
        $Factor = \substr($CIDR, $Pos + 1);
        if ($CIDRs = $this->expandIpv4($Base, false, $Factor + 1)) {
            $Is = 4;
        } elseif ($CIDRs = $this->expandIpv6($Base, false, $Factor + 1)) {
            $Is = 6;
        } else {
            return [];
        }
        if ($Factor < 1 || ($Is === 4 && $Factor >= 32) || ($Is === 6 && $Factor >= 128)) {
            return [];
        }
        $Split = [$CIDRs[$Factor]];
        $Last = ($Is === 4) ? $this->ipv4GetLast($Base, $Factor) : $this->ipv6GetLast($Base, $Factor);
        $CIDRs = ($Is === 4) ? $this->expandIpv4($Last, false, $Factor + 1) : $this->expandIpv6($Last, false, $Factor + 1);
        if (isset($CIDRs[$Factor])) {
            $Split[] = $CIDRs[$Factor];
            return $Split;
        }
        return [];
    }

    /**
     * Returns the intersect of two sets of CIDRs.
     *
     * @param string $A Set A.
     * @param string $B Set B.
     * @param int $Format The format to return the results as.
     *      1 = Netmasks. 0 = CIDRs.
     * @return string The intersect.
     */
    private function intersectCidr(string $A = '', string $B = '', int $Format = 0): string
    {
        $StrObject = new \Maikuolan\Common\ComplexStringHandler("\n" . $A . "\n", self::REGEX_TAGS, function (string $Data) use ($B, $Format): string {
            $Data = "\n" . $this->CIDRAM['Aggregator']->aggregate($Data) . "\n";
            $Intersect = '';
            foreach ([['B', 'Data'], ['Data', 'B']] as $Points) {
                $LPos = 0;
                while (($NPos = \strpos(${$Points[0]}, "\n", $LPos)) !== false) {
                    $Line = \substr(${$Points[0]}, $LPos, $NPos - $LPos);
                    $LPos = $NPos + 1;
                    if (($DPos = \strpos($Line, '/')) !== false) {
                        $Range = \substr($Line, $DPos + 1);
                        $Base = \substr($Line, 0, $DPos);
                    } else {
                        continue;
                    }
                    if (!$CIDRs = $this->expandIpv4($Base)) {
                        if (!$CIDRs = $this->expandIpv6($Base)) {
                            continue;
                        }
                    }
                    foreach ($CIDRs as $Key => $Actual) {
                        if (\strpos(${$Points[1]}, "\n" . $Actual . "\n") === false) {
                            continue;
                        }
                        if (($Key + 1) > (int)$Range) {
                            $Intersect .= $Actual . "\n";
                        } else {
                            $Intersect .= $Line . "\n";
                        }
                        break;
                    }
                }
            }
            $Aggregator = new Aggregator($Format);
            return \trim($Aggregator->aggregate($Intersect));
        });
        $StrObject->iterateClosure(function (string $Data): string {
            return "\n" . $Data;
        }, true);
        return \trim($StrObject->recompile());
    }

    /**
     * Subtracts subtrahend CIDRs from minuend CIDRs and returns the difference.
     *
     * @param string $Minuend The minuend (assumes no erroneous data).
     * @param string $Subtrahend The subtrahend (assumes no erroneous data).
     * @param int $Format The format to return the results as.
     *      1 = Netmasks. 0 = CIDRs.
     * @return string The difference.
     */
    private function subtractCidr(string $Minuend = '', string $Subtrahend = '', int $Format = 0): string
    {
        $StrObject = new \Maikuolan\Common\ComplexStringHandler("\n" . $Minuend . "\n", self::REGEX_TAGS, function (string $Minuend) use ($Subtrahend, $Format): string {
            $Minuend = "\n" . $this->CIDRAM['Aggregator']->aggregate($Minuend . "\n" . $Subtrahend) . "\n";
            $LPos = 0;
            while (($NPos = \strpos($Subtrahend, "\n", $LPos)) !== false) {
                $Line = \substr($Subtrahend, $LPos, $NPos - $LPos);
                $LPos = $NPos + 1;
                if (($DPos = \strpos($Line, '/')) !== false) {
                    $Range = \substr($Line, $DPos + 1);
                    $Base = \substr($Line, 0, $DPos);
                } else {
                    continue;
                }
                if (!$CIDRs = $this->expandIpv4($Base, false, $Range)) {
                    if (!$CIDRs = $this->expandIpv6($Base, false, $Range)) {
                        continue;
                    }
                }
                foreach ($CIDRs as $Key => $Actual) {
                    if (\strpos($Minuend, "\n" . $Actual . "\n") === false) {
                        continue;
                    }
                    if ($Range > ($Key + 1) && $Split = $this->splitCidr($Actual)) {
                        $Minuend .= \implode("\n", $Split) . "\n";
                    }
                    $Minuend = \str_replace("\n" . $Actual . "\n", "\n", $Minuend);
                }
            }
            $Aggregator = new Aggregator($Format);
            return \trim($Aggregator->aggregate($Minuend));
        });
        $StrObject->iterateClosure(function (string $Data): string {
            return "\n" . $Data;
        }, true);
        return \trim($StrObject->recompile());
    }

    /**
     * Generates a message describing the relative difference between the
     * specified time and the current time (used by the IP tracking and
     * auxiliary rules pages).
     *
     * @param int $Time The specified time (unix time).
     * @return string The message.
     */
    private function relativeTime(int $Time): string
    {
        $Time -= $this->Now;
        if ($Time < -31536000) {
            $Time = (int)($Time / -31536000);
            return \sprintf(
                $this->L10N->getPlural($Time, 'time_years_ago'),
                $this->NumberFormatter->format($Time)
            );
        }
        if ($Time < -2629800) {
            $Time = (int)($Time / -2629800);
            return \sprintf(
                $this->L10N->getPlural($Time, 'time_months_ago'),
                $this->NumberFormatter->format($Time)
            );
        }
        if ($Time < -86400) {
            $Time = (int)($Time / -86400);
            return \sprintf(
                $this->L10N->getPlural($Time, 'time_days_ago'),
                $this->NumberFormatter->format($Time)
            );
        }
        if ($Time < -3600) {
            $Time = (int)($Time / -3600);
            return \sprintf(
                $this->L10N->getPlural($Time, 'time_hours_ago'),
                $this->NumberFormatter->format($Time)
            );
        }
        if ($Time < -60) {
            $Time = (int)($Time / -60);
            return \sprintf(
                $this->L10N->getPlural($Time, 'time_minutes_ago'),
                $this->NumberFormatter->format($Time)
            );
        }
        if ($Time < 0) {
            $Time = (int)($Time * -1);
            return \sprintf(
                $this->L10N->getPlural($Time, 'time_seconds_ago'),
                $this->NumberFormatter->format($Time)
            );
        }
        if ($Time > 31536000) {
            $Time = (int)($Time / 31536000);
            return \sprintf(
                $this->L10N->getPlural($Time, 'time_years_from_now'),
                $this->NumberFormatter->format($Time)
            );
        }
        if ($Time > 2629800) {
            $Time = (int)($Time / 2629800);
            return \sprintf(
                $this->L10N->getPlural($Time, 'time_months_from_now'),
                $this->NumberFormatter->format($Time)
            );
        }
        if ($Time > 86400) {
            $Time = (int)($Time / 86400);
            return \sprintf(
                $this->L10N->getPlural($Time, 'time_days_from_now'),
                $this->NumberFormatter->format($Time)
            );
        }
        if ($Time > 3600) {
            $Time = (int)($Time / 3600);
            return \sprintf(
                $this->L10N->getPlural($Time, 'time_hours_from_now'),
                $this->NumberFormatter->format($Time)
            );
        }
        if ($Time > 60) {
            $Time = (int)($Time / 60);
            return \sprintf(
                $this->L10N->getPlural($Time, 'time_minutes_from_now'),
                $this->NumberFormatter->format($Time)
            );
        }
        $Time = (int)$Time;
        return \sprintf(
            $this->L10N->getPlural($Time, 'time_seconds_from_now'),
            $this->NumberFormatter->format($Time)
        );
    }

    /**
     * Update the configuration.
     *
     * @param int|null $BytesRemoved The number of bytes removed (only used when invoked by executor).
     * @param int|null $BytesAdded The number of bytes added (only used when invoked by executor).
     * @return bool Whether succeeded or failed.
     */
    private function updateConfiguration(?int &$BytesRemoved = null, ?int &$BytesAdded = null): bool
    {
        if (!\is_file($this->FE['ActiveConfigFile']) || !\is_writable($this->FE['ActiveConfigFile'])) {
            return false;
        }
        $Reconstructed = $this->YAML->reconstruct($this->Configuration);
        if ($BytesRemoved !== null) {
            $Size = \strlen($Reconstructed) - \filesize($this->FE['ActiveConfigFile']);
        }
        $Handle = \fopen($this->FE['ActiveConfigFile'], 'wb');
        if (!\is_resource($Handle)) {
            return false;
        }
        $Err = \fwrite($Handle, $Reconstructed);
        \fclose($Handle);
        if ($Err !== false && $BytesRemoved !== null) {
            if ($Size < 0) {
                $BytesRemoved += $Size;
            } elseif ($BytesAdded === null) {
                $BytesRemoved -= $Size;
            } else {
                $BytesAdded += $Size;
            }
        }
        return $Err !== false;
    }

    /**
     * Get path from component type.
     *
     * @param string $Type "ipv4", "ipv6", "modules", "imports", or "events".
     * @return string The path.
     */
    private function pathFromComponentType(string $Type): string
    {
        if ($Type === 'ipv4' || $Type === 'ipv6') {
            return $this->SignaturesPath;
        }
        if ($Type === 'modules') {
            return $this->ModulesPath;
        }
        if ($Type === 'imports') {
            return $this->ImportsPath;
        }
        if ($Type === 'events') {
            return $this->EventsPath;
        }
        return $this->Vault;
    }

    /**
     * Process all current request and bandwidth usage for this period.
     *
     * @param string $Data The data to be processed.
     * @return array The processed data.
     */
    private function processRLUsage(string $Data): array
    {
        $Pos = 0;
        $EoS = \strlen($Data);
        $Out = [];
        while ($Pos < $EoS) {
            $Time = \substr($Data, $Pos, 4);
            if (\strlen($Time) !== 4) {
                break;
            }
            $Time = \unpack('l*', $Time);
            $Pos += 4;
            $Bandwidth = \substr($Data, $Pos, 4);
            if (\strlen($Bandwidth) !== 4) {
                break;
            }
            $Bandwidth = \unpack('l*', $Bandwidth);
            $Pos += 4;
            $BlockSize = \substr($Data, $Pos, 4);
            if (\strlen($BlockSize) !== 4) {
                break;
            }
            $BlockSize = \unpack('l*', $BlockSize);
            $Pos += 4;
            $Block = \substr($Data, $Pos, $BlockSize[1]);
            $Pos += $BlockSize[1];
            if (isset($Out[$Block])) {
                $Out[$Block]['Bandwidth'] += $Bandwidth[1];
                $Out[$Block]['Requests']++;
                $Out[$Block]['Newest'] = $Time[1];
            } else {
                $Out[$Block] = ['Bandwidth' => $Bandwidth[1], 'Requests' => 1, 'Oldest' => $Time[1], 'Newest' => $Time[1]];
            }
        }
        return $Out;
    }

    /**
     * Process minified form data.
     *
     * @param string $MinifiedKey The key for the minified form data.
     * @return void
     */
    private function processMinifiedFormData(string $MinifiedKey): void
    {
        if (!isset($_POST[$MinifiedKey]) || !\is_string($_POST[$MinifiedKey]) || \substr($_POST[$MinifiedKey], 0, 1) !== '{' || \substr($_POST[$MinifiedKey], -1) !== '}') {
            return;
        }
        $this->initialiseErrorHandler();
        $MinifiedFormData = \json_decode($this->desabotage($_POST[$MinifiedKey]), true);
        $this->restoreErrorHandler();
        if (!\is_array($MinifiedFormData)) {
            return;
        }
        $ToMerge = [];
        $ToBase = [];
        foreach ($MinifiedFormData as $Key => $Value) {
            if (\preg_match('~^(.+)\[(\d+)\]\[(?:New)?\d*\]$|^"(.+)\[(\d+)\]\[(?:New)?\d*\]"$~', $Key, $Index)) {
                if (!isset($ToMerge[$Index[1]])) {
                    $ToMerge[$Index[1]] = [];
                }
                if (!isset($ToMerge[$Index[1]][$Index[2]])) {
                    $ToMerge[$Index[1]][$Index[2]] = [];
                }
                $ToMerge[$Index[1]][$Index[2]][] = $Value;
                continue;
            }
            if (\preg_match('~^(.+)\[(?:New)?\d*\]$|^"(.+)\[(?:New)?\d*\]"$~', $Key, $Index)) {
                if (!isset($ToMerge[$Index[1]])) {
                    $ToMerge[$Index[1]] = [];
                }
                $ToMerge[$Index[1]][] = $Value;
                continue;
            }
            $ToBase[$Key] = $Value;
        }
        $MinifiedFormData = \array_merge($ToBase, $ToMerge);
        $_POST = \array_replace($_POST, $MinifiedFormData);
        unset($_POST[$MinifiedKey]);
    }

    /**
     * Perform callback against an array where a callback matches.
     *
     * @param array $Arr The array to work upon.
     * @param callable $Perform The callable to perform.
     * @param int $Depth The current depth.
     * @return void
     */
    private function callableRecursive(array &$Arr, callable $Perform, int $Depth = 0): void
    {
        foreach ($Arr as $Key => &$Value) {
            if (!$Perform($Value, $Depth)) {
                break;
            }
            if (\is_array($Value)) {
                $this->callableRecursive($Value, $Perform, $Depth + 1);
            }
        }
    }

    /**
     * Fetch an etaggable asset as requested by the client.
     *
     * @param string $Asset The path to the asset.
     * @param callable|null $Callback An optional callback.
     * @return never
     */
    private function eTaggable(string $Asset, ?callable $Callback = null): void
    {
        $this->Events->fireEvent('final');
        header_remove('Cache-Control');
        if ($this->pathSecurityCheck($Asset) && !\preg_match('~[^\da-z._]~i', $Asset)) {
            $ThisAsset = $this->getAssetPath($Asset, true);
            if (\strlen($ThisAsset) && \is_readable($ThisAsset) && ($ThisAssetDel = \strrpos($ThisAsset, '.')) !== false) {
                $Success = false;
                $NoSniff = false;
                $Type = \strtolower(\substr($ThisAsset, $ThisAssetDel + 1));
                if ($Type === 'jpeg') {
                    $Type = 'jpg';
                }
                if (\preg_match('/^(?:gif|jpg|png|webp)$/', $Type)) {
                    $MimeType = 'Content-Type: image/' . $Type;
                    $Success = true;
                } elseif ($Type === 'svg') {
                    $MimeType = 'Content-Type: image/svg+xml';
                    $Success = true;
                } elseif ($Type === 'ico') {
                    $MimeType = 'Content-Type: image/x-icon';
                    $Success = true;
                } elseif ($Type === 'js') {
                    $MimeType = 'Content-Type: text/javascript';
                    $Success = true;
                    $NoSniff = true;
                } elseif ($Type === 'css') {
                    $MimeType = 'Content-Type: text/css';
                    $Success = true;
                    $NoSniff = true;
                }
                if ($Success) {
                    $AssetData = $this->readFile($ThisAsset);
                    if (\is_callable($Callback)) {
                        $AssetData = $Callback($AssetData);
                    }
                    $OldETag = $_SERVER['HTTP_IF_NONE_MATCH'] ?? '';
                    $NewETag = \hash('sha256', $AssetData) . '-' . \strlen($AssetData);
                    header('Last-Modified: ' . \gmdate('D, d M Y H:i:s T', \filemtime($ThisAsset)));
                    header('ETag: "' . $NewETag . '"');
                    header('Expires: ' . \gmdate('D, d M Y H:i:s T', $this->Now + 15552000));
                    if (\preg_match('~(?:^|, )(?:"' . $NewETag . '"|' . $NewETag . ')(?:$|, )~', $OldETag)) {
                        header('HTTP/1.0 304 Not Modified');
                        header('HTTP/1.1 304 Not Modified');
                        header('Status: 304 Not Modified');
                        die;
                    }
                    header($MimeType);
                    if ($NoSniff) {
                        header('X-Content-Type-Options: nosniff');
                    }
                    echo $AssetData;
                    die;
                }
            }
            header('HTTP/1.0 404 Not Found');
            header('HTTP/1.1 404 Not Found');
            header('Status: 404 Not Found');
            die;
        }
        header('HTTP/1.0 403 Forbidden');
        header('HTTP/1.1 403 Forbidden');
        header('Status: 403 Forbidden');
        die;
    }

    /**
     * Embed assets inside a string.
     *
     * @param string $In The string to embed assets in.
     * @return string
     */
    private function embedAssets(string $In): string
    {
        if (\preg_match_all('~\{Asset:([^{}]+)\}~', $In, $Matches)) {
            $Matches = (isset($Matches[1]) && \is_array($Matches[1])) ? \array_unique($Matches[1]) : [];
            foreach ($Matches as $AssetName) {
                if (($AssetPath = $this->getAssetPath($AssetName, true)) !== '') {
                    if (($Value = $this->readFile($AssetPath)) !== '') {
                        $In = \str_replace('{Asset:' . $AssetName . '}', $Value, $In);
                    }
                }
            }
        }
        if (\preg_match_all('~\{Base64Encode\}(.+?)\{/Base64Encode\}~s', $In, $Matches)) {
            $Matches = (isset($Matches[1]) && \is_array($Matches[1])) ? \array_unique($Matches[1]) : [];
            foreach ($Matches as $Data) {
                $In = \str_replace('{Base64Encode}' . $Data . '{/Base64Encode}', \base64_encode($Data), $In);
            }
        }
        return $In;
    }

    /**
     * Executes a list of methods or commands when specific conditions are met.
     *
     * @param string|array $Methods The list of methods or commands to execute.
     * @param bool $Queue Whether to queue the operation or perform immediately.
     * @param int|null $BytesRemoved The number of bytes removed (optional).
     * @param int|null $BytesAdded The number of bytes added (optional).
     * @return void
     */
    private function executor($Methods = '', bool $Queue = false, ?int &$BytesRemoved = null, ?int &$BytesAdded = null): void
    {
        if ($Queue && $Methods !== '') {
            /** Guard. */
            if (!isset($this->CIDRAM['ExecutorQueue']) || !\is_array($this->CIDRAM['ExecutorQueue'])) {
                $this->CIDRAM['ExecutorQueue'] = [];
            }

            /** Add to the executor queue. */
            if (\is_array($Methods)) {
                $this->CIDRAM['ExecutorQueue'] = \array_merge($this->CIDRAM['ExecutorQueue'], $Methods);
            } else {
                $this->CIDRAM['ExecutorQueue'][] = $Methods;
            }
            return;
        }

        if ($Methods === '') {
            if (isset($this->CIDRAM['ExecutorQueue']) && \is_array($this->CIDRAM['ExecutorQueue'])) {
                /** We'll iterate an array from the local scope to guard against infinite loops. */
                $Items = $this->CIDRAM['ExecutorQueue'];

                /** Purge the queue before iterating. */
                $this->CIDRAM['ExecutorQueue'] = [];

                /** Iterate through the executor queue. */
                $this->executor($Items, false, $BytesRemoved, $BytesAdded);
            }
            return;
        }

        /** Guard. */
        $this->arrayify($Methods);

        /** Recursively execute all methods in the current queue item. */
        foreach ($Methods as $Method) {
            /** Guard. */
            if (\is_array($Method)) {
                foreach ($Method as $Item) {
                    $this->executor($Item, false, $BytesRemoved, $BytesAdded);
                }
                continue;
            }

            /** Foreach looping. */
            if (\preg_match('~^foreach \{(.+?)\} as ([^ ]+?) => ([^ ]+?) (.*)$~i', $Method, $Tokens)) {
                $Iterable = $this->OperationHandler->dataTraverse($this, $Tokens[1], true, true);
                if (!\is_iterable($Iterable)) {
                    continue;
                }
                $Arr = [];
                foreach ($Iterable as $Key => $Value) {
                    $Arr[] = \str_replace(['{' . $Tokens[2] . '}', '{' . $Tokens[3] . '}'], [$Key, $Value], $Tokens[4]);
                }
                $this->executor($Arr, false, $BytesRemoved, $BytesAdded);
                continue;
            }

            /** All logic, data traversal, dot notation, etc handled here. */
            $Method = $this->OperationHandler->ifCompare($this, $Method, true);

            foreach (\preg_split('~(?<!\\\\);~', $Method) as $Method) {
                if ($Method === '') {
                    continue;
                }
                if (\method_exists($this, $Method)) {
                    $this->{$Method}($BytesRemoved, $BytesAdded);
                } elseif (($Pos = \strpos($Method, ' ')) !== false) {
                    $Method = \preg_replace('~(?<!\\\\)\\\\;~', ';', $Method);
                    $Params = \substr($Method, $Pos + 1);
                    $Method = \substr($Method, 0, $Pos);
                    if ($Method === 'set') {
                        $this->OperationHandler->set($this, $Params, true);
                    } elseif (\method_exists($this, $Method)) {
                        $Params = $this->OperationHandler->ifCompare($this, $Params, true);
                        $this->{$Method}($Params, $BytesRemoved, $BytesAdded);
                    }
                }
            }
        }
    }

    /**
     * Discern a message.
     *
     * @param string $Message What to discern.
     * @return string The discerned message.
     */
    private function discern(string $Message): string
    {
        if (($Try = $this->L10N->getString($Message)) !== '') {
            $Message = $Try;
        } elseif (($SPos = \strpos($Message, ' ')) !== false) {
            if (($Try = $this->L10N->getString(\substr($Message, 0, $SPos))) !== '') {
                $Params = \substr($Message, $SPos + 1);
                $FC = \substr_count($Try, '%s');
                if ($FC === 1) {
                    $Try = \sprintf($Try, $Params);
                } elseif ($FC > 1) {
                    $SC = \substr_count($Params, ' ');
                    if ($SC + 1 === $FC) {
                        $Try = \sprintf($Try, ...\explode(' ', $Params));
                    } elseif ($SC >= $FC) {
                        $Try = \sprintf($Try, ...\explode(' ', $Params, $FC));
                    }
                }
                $Message = $Try;
            }
        }
        return $Message;
    }

    /**
     * Append to the current warnings.
     *
     * @param string $Message What to append.
     * @return void
     */
    private function warn(string $Message): void
    {
        if (!isset($this->CIDRAM['Warnings'])) {
            return;
        }
        $this->CIDRAM['Warnings'][] = $this->discern($Message);
    }

    /**
     * Better escaping for JavaScript inside HTML.
     *
     * @param string $In What to escape.
     * @return string Escaped string.
     */
    private function escapeJsInHTML(string $In): string
    {
        return \str_replace(['"', '<', '>', '\\\\n'], ['&#34;', '&lt;', '&gt;', '\\n'], \addslashes($In));
    }

    /**
     * Attempts to compensate for cases where the environment might be messing
     * around with POST data and such, negatively affecting CIDRAM functionality.
     *
     * @param mixed $Data The data potentially being messed around with.
     * @return mixed
     */
    private function desabotage($Data)
    {
        if (\is_array($Data)) {
            foreach ($Data as &$Entry) {
                $Entry = $this->desabotage($Entry);
            }
            return $Data;
        }

        /**
         * Errant slashes produced by WordPress throughout all POST data breaks
         * any changes made to auxiliary rules via the auxiliary rules page, to
         * files via the file manager, and possibly elsewhere.
         *
         * @link https://core.trac.wordpress.org/ticket/18322
         * @link https://developer.wordpress.org/reference/functions/wp_unslash/
         */
        if (\is_string($Data) && $Data !== '' && \function_exists('wp_unslash')) {
            return wp_unslash($Data);
        }

        return $Data;
    }

    /**
     * If, of two numbers, one is negative while the other is positive and
     * non-zero, attempt to rebalance them out so that neither is negative.
     *
     * @param int $A The first number.
     * @param int $B The second number.
     * @return void
     */
    private function rebalanceNumbers(int &$A, int &$B): void
    {
        if (($A >= 0 && $B >= 0) || ($A < 0 && $B < 0)) {
            return;
        }
        if ($A < 0) {
            $B -= $A;
            $A -= $A;
        }
        if ($B < 0) {
            $A -= $B;
            $B -= $B;
        }
    }

    /**
     * Determine the name to use for a "copied" entity.
     *
     * @param string $Origin The original name to be worked from.
     * @param callable $AlreadyUsed A callable to check whether an option has already been used.
     * @param int $MaxLimit The maximum number of iterations for name checks allowed.
     * @return string The name to use.
     */
    private function copyIterableName(string $Origin, callable $AlreadyUsed, int $MaxLimit = 1024): string
    {
        $CopyFirst = $this->L10N->getString('label.%s (Copy)') ?: '%s (Copy)';
        $CopyFirstBlank = \sprintf($CopyFirst, '');
        $CopyFirstBlankLen = \strlen($CopyFirstBlank);
        if (\substr($Origin, $CopyFirstBlankLen * -1) === $CopyFirstBlank) {
            $Origin = \substr($Origin, 0, $CopyFirstBlankLen * -1);
        }
        $CopyExtra = $this->L10N->getString('label.%s (Copy %s)') ?: '%s (Copy %s)';
        $CopyExtraBlank = \sprintf($CopyExtra, '', '');
        $CopyExtraBlankLen = \strlen($CopyExtraBlank);
        $OriginNoDigits = \preg_replace('~\d~', '', $Origin);
        if (\substr($OriginNoDigits, $CopyExtraBlankLen * -1) === $CopyExtraBlank) {
            $Origin = \preg_replace('~' . \sprintf(\preg_quote($CopyExtra), '', '\\d+') . '$~', '', $Origin);
        }
        $Try = \sprintf($CopyFirst, $Origin);
        if (!$AlreadyUsed($Try)) {
            return $Try;
        }
        for ($Current = 1; $Current < $MaxLimit; $Current++) {
            $Try = \sprintf($CopyExtra, $Origin, $Current);
            if (!$AlreadyUsed($Try)) {
                return $Try;
            }
        }
        try {
            $Current = \random_int(1025, 99999);
        } catch (\Exception $e) {
            $Current = \rand(1025, 99999);
        }
        $Try = \sprintf($CopyExtra, $Origin, $Current);
        if (!$AlreadyUsed($Try)) {
            return $Try;
        }
        return '';
    }

    /**
     * Attempt to copy a file.
     *
     * @param string $Origin The file to copy.
     * @param string $Target Where to copy the file.
     * @return string A human-readable message for whether it succeeded or failed.
     */
    private function copyFile(string $Origin, string $Target): string
    {
        if (\filesize($Origin) >= ($this->readBytes(\ini_get('memory_limit')) - \memory_get_peak_usage(true))) {
            return $this->L10N->getString('response.The targeted file_s size exceeds PHP_s memory limit') . ' ' . $this->L10N->getString('response.Failed to duplicate');
        }
        if (!\function_exists('copy') || \preg_match('~(^|,)copy(,|$)~i', \ini_get('disable_functions'))) {
            $Data = $this->readFile($Origin);
            $Handle = \fopen($Target, 'wb');
            if (!\is_resource($Handle)) {
                return $this->L10N->getString('response.Failed to duplicate');
            }
            $Err = \fwrite($Handle, $Data);
            \fclose($Handle);
            return $Err === false ? $this->L10N->getString('response.File successfully duplicated') : $this->L10N->getString('response.Failed to duplicate');
        }
        return \copy($Origin, $Target) ? $this->L10N->getString('response.File successfully duplicated') : $this->L10N->getString('response.Failed to duplicate');
    }
}
