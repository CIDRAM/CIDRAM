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
 * This file: Glossary for CIDRAM (last modified: 2026.04.02).
 */

namespace CIDRAM\CIDRAM;

if (!isset($this->FE['Permissions'], $this->CIDRAM['QueryVars']['cidram-page']) || $this->CIDRAM['QueryVars']['cidram-page'] !== 'glossary' || $this->FE['Permissions'] <= 0) {
    die;
}

/** Page initial prepwork. */
$this->initialPrepwork($this->L10N->getString('link.Glossary'), $this->L10N->getString('tip.Glossary'));

/** Populate indexes. */
$this->FE['Indexes'] = [];
$Indexes = $this->L10N->Data['Glossary']['Indexes'] ?? (\is_array($this->L10N->Fallback) && isset($this->L10N->Fallback['Glossary']['Indexes']) ? $this->L10N->Fallback['Glossary']['Indexes'] : []);
foreach ($Indexes as $Index => $Anchor) {
    $this->FE['Indexes'][] = \sprintf('<a href="#%s">%s</a>', $Anchor, $Index);
}
\sort($this->FE['Indexes']);
$this->FE['Indexes'] = \implode("<br />\n      ", $this->FE['Indexes']);

/** Populate entries. */
$this->FE['Entries'] = [];
$Entries = $this->L10N->Data['Glossary']['Entries'] ?? (\is_array($this->L10N->Fallback) && isset($this->L10N->Fallback['Glossary']['Entries']) ? $this->L10N->Fallback['Glossary']['Entries'] : []);
$Refs = $this->L10N->Data['Glossary']['Refs'] ?? (\is_array($this->L10N->Fallback) && isset($this->L10N->Fallback['Glossary']['Refs']) ? $this->L10N->Fallback['Glossary']['Refs'] : []);
$SeeAlso = $this->L10N->getString('label.See also');
foreach ($Entries as $Index => $Entry) {
    if (\is_array($Entry)) {
        $Entry = $this->L10N->getString('Glossary.Entries.' . $Index);
    }
    if (empty($Entry)) {
        continue;
    }
    $Entry = \preg_replace('~(?<!\|)\n(?!\|)~', '<br /><br />', $Entry);

    /** Support for markdown-like tables. */
    if (\strpos($Entry, "\n| ") !== false) {
        $Entry = \preg_split('~\|\n\||(?<=\n)\||\|(?=\n)~', $Entry);
        $First = true;
        $RowOdd = true;
        foreach ($Entry as &$EntryPart) {
            if (($Count = \substr_count($EntryPart, '|')) === 0) {
                continue;
            }
            $Prepend = $First ? '        <div style="display:grid;margin:auto;grid-template-columns:' . \str_repeat('1fr ', $Count) . '1fr;text-align:center">' : '';
            $EntryPart = \explode('|', $EntryPart);
            $CellOdd = false;
            foreach ($EntryPart as &$Cell) {
                if ($Cell === '') {
                    continue;
                }
                $Style = 'gridboxitem s ' . ($CellOdd ? 'gridVB ' : 'gridVA ') . ($First ? 'configMatrixLabel' : ($RowOdd ? 'gridHB' : 'gridHA'));
                $Cell = '<div class="' . $Style . '">' . \trim($Cell, ' ') . '</div>';
                $CellOdd = !$CellOdd;
            }
            $EntryPart = $Prepend . \implode('', $EntryPart);
            $First = false;
            $RowOdd = !$RowOdd;
        }
        $Entry = \str_replace(["</div>\n", "\n"], ["</div></div>\n      ", "<br /><br />\n"], \implode('', $Entry));
    }

    /** Citations and references. */
    $Anchor = isset($Indexes[$Index]) ? ' id="' . $Indexes[$Index] . '"' : '';
    if (isset($Refs[$Index])) {
        $Entry .= '<br /><br />' . $SeeAlso . '<ul>';
        foreach ($Refs[$Index] as $RefName => $Ref) {
            $Entry .= \sprintf('<li><cite><a href="%s" dir="ltr" rel="noopener noreferrer external"><span class="navicon link"></span>%s</a></cite></li>', $Ref, $RefName);
        }
        $Entry .= '</ul>';
    }

    $this->FE['Entries'][] = \sprintf('<div class="ng1"><dl><dt%s>%s</dt><dd>%s</dd></dl></div>', $Anchor, $Index, $Entry);
}
\sort($this->FE['Entries']);
$this->FE['Entries'] = \implode("\n      ", $this->FE['Entries']);
unset($Style, $Cell, $CellOdd, $Prepend, $Count, $EntryPart, $RowOdd, $First, $Ref, $RefName, $Entry, $SeeAlso, $Refs, $Entries, $Anchor, $Index, $Indexes);

/** Parse output. */
$this->FE['FE_Content'] = $this->parseVars($this->FE, $this->readFile($this->getAssetPath('_glossary.html')), true);

/** Send output. */
echo $this->sendOutput();
