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
 * This file: The accounts page (last modified: 2026.08.30).
 */

namespace CIDRAM\CIDRAM;

if (!isset($this->FE['Permissions'], $this->CIDRAM['QueryVars']['cidram-page']) || $this->CIDRAM['QueryVars']['cidram-page'] !== 'accounts' || $this->FE['Permissions'] !== 1) {
    die;
}

/** $_POST overrides for mobile display. */
if (!empty($_POST['username']) && !empty($_POST['do_mob']) && (!empty($_POST['password_mob']) || $_POST['do_mob'] === 'delete-account')) {
    $_POST['do'] = $_POST['do_mob'];
}
if (empty($_POST['username']) && !empty($_POST['username_mob'])) {
    $_POST['username'] = $_POST['username_mob'];
}
if (empty($_POST['permissions']) && !empty($_POST['permissions_mob'])) {
    $_POST['permissions'] = $_POST['permissions_mob'];
}
if (empty($_POST['password']) && !empty($_POST['password_mob'])) {
    $_POST['password'] = $_POST['password_mob'];
}

/** A form has been submitted. */
if ($this->FE['FormTarget'] === 'accounts' && !empty($_POST['do'])) {
    /** Create a new account. */
    if ($_POST['do'] === 'create-account' && !empty($_POST['username']) && !empty($_POST['password']) && !empty($_POST['permissions'])) {
        $Accounts = [
            'TryPath' => 'user.' . $this->desabotage($_POST['username']),
            'TryPass' => \password_hash($_POST['password'], $this->DefaultAlgo),
            'TryPermissions' => (int)$_POST['permissions']
        ];
        if (isset($this->Configuration[$Accounts['TryPath']])) {
            $this->FE['state_msg'] = $this->L10N->getString('response.An account with that username already exists');
        } else {
            $this->Configuration[$Accounts['TryPath']] = ['password' => $Accounts['TryPass'], 'permissions' => $Accounts['TryPermissions']];
            if ($this->updateConfiguration()) {
                $this->FE['state_msg'] = $this->L10N->getString('response.Account successfully created');
            } else {
                $this->FE['state_msg'] = $this->L10N->getString('response.Failed to create');
            }
        }
        unset($Accounts);
    }

    /** Delete an account. */
    if ($_POST['do'] === 'delete-account' && !empty($_POST['username'])) {
        $Accounts = 'user.' . $this->desabotage($_POST['username']);
        if (!isset($this->Configuration[$Accounts])) {
            $this->FE['state_msg'] = $this->L10N->getString('response.That account doesn_t exist');
        } else {
            unset($this->Configuration[$Accounts]);
            if ($this->updateConfiguration()) {
                $this->FE['state_msg'] = $this->L10N->getString('response.Account successfully deleted');
            } else {
                $this->FE['state_msg'] = $this->L10N->getString('response.Failed to delete');
            }
        }
        unset($Accounts);
    }

    /** Update an account password. */
    if ($_POST['do'] === 'update-password' && !empty($_POST['username']) && !empty($_POST['password'])) {
        $Accounts = [
            'TryPath' => 'user.' . $this->desabotage($_POST['username']),
            'TryPass' => \password_hash($_POST['password'], $this->DefaultAlgo)
        ];
        if (!isset($this->Configuration[$Accounts['TryPath']])) {
            $this->FE['state_msg'] = $this->L10N->getString('response.That account doesn_t exist');
        } else {
            $this->Configuration[$Accounts['TryPath']]['password'] = $Accounts['TryPass'];
            if ($this->updateConfiguration()) {
                $this->FE['state_msg'] = $this->L10N->getString('response.Password successfully updated');
            } else {
                $this->FE['state_msg'] = $this->L10N->getString('response.Failed to update');
            }
        }
        unset($Accounts);
    }
}

if (!$this->FE['ASYNC']) {
    /** Page initial prepwork. */
    $this->initialPrepwork($this->L10N->getString('link.Accounts'), $this->L10N->getString('tip.Accounts'));

    /** Append JavaScript specific to the accounts page. */
    $this->FE['JS'] .= $this->parseVars(['Loading' => $this->L10N->getString('label.Loading_')], $this->readFile($this->getAssetPath('accounts.js')));

    $this->FE['AccountsRow'] = $this->readFile($this->getAssetPath('_accounts_row.html'));
    $this->FE['Accounts'] = '';
    $this->FE['PassInOnListWarn'] = \str_replace('\'', '\\\'', $this->L10N->getString('warning.Extremely common passwords should be avoided'));

    $LI = ['Possible' => []];
    foreach ($this->Cache->getAllEntries() as $LI['KeyName'] => $LI['KeyData']) {
        if (isset($LI['KeyData']['Time']) && $LI['KeyData']['Time'] > 0 && $LI['KeyData']['Time'] < $this->Now) {
            continue;
        }
        if (\strlen($LI['KeyName']) > 64) {
            $LI['Try'] = \substr($LI['KeyName'], 0, -64);
            if (isset($this->Configuration['user.' . $LI['Try']])) {
                $LI['Possible'][$LI['Try']] = true;
            }
        }
    }
    $LI = $LI['Possible'];

    foreach ($this->Configuration as $CatKey => $CatValues) {
        if (\substr($CatKey, 0, 5) !== 'user.' || !\is_array($CatValues)) {
            continue;
        }
        $RowInfo = [
            'AccUsername' => \substr($CatKey, 5),
            'AccPassword' => $CatValues['password'] ?? '',
            'AccPermissions' => $CatValues['permissions'] ?? 0,
            'AccWarnings' => ''
        ];
        $RowInfo['AccPasswordLen'] = \strlen($RowInfo['AccPassword']);
        if ($RowInfo['AccPermissions'] === 1) {
            $RowInfo['AccPermissions'] = $this->L10N->getString('label.Complete access');
        } elseif ($RowInfo['AccPermissions'] === 2) {
            $RowInfo['AccPermissions'] = $this->L10N->getString('label.Logs access only');
        } elseif ($RowInfo['AccPermissions'] === 3) {
            $RowInfo['AccPermissions'] = 'Cronable';
        } else {
            $RowInfo['AccPermissions'] = $this->L10N->getString('response.Error');
        }

        /** Account password warnings. */
        if ($RowInfo['AccPassword'] === $this->FE['DefaultPassword']) {
            $RowInfo['AccWarnings'] .= '<br /><div class="txtRd">' . $this->L10N->getString('warning.Using the default password') . '</div>';
        } elseif (
            ($RowInfo['AccPasswordLen'] !== 60 && $RowInfo['AccPasswordLen'] !== 96 && $RowInfo['AccPasswordLen'] !== 97) ||
            ($RowInfo['AccPasswordLen'] === 60 && !\preg_match('/^\$2.\$\d\d\$/', $RowInfo['AccPassword'])) ||
            ($RowInfo['AccPasswordLen'] === 96 && !\preg_match('/^\$argon2i\$/', $RowInfo['AccPassword'])) ||
            ($RowInfo['AccPasswordLen'] === 97 && !\preg_match('/^\$argon2id\$/', $RowInfo['AccPassword']))
        ) {
            $RowInfo['AccWarnings'] .= '<br /><div class="txtRd">' . $this->L10N->getString('warning.This account is not using a valid password') . '</div>';
        }

        /** Logged in notice. */
        if (isset($LI[$RowInfo['AccUsername']])) {
            $RowInfo['AccWarnings'] .= '<br /><div class="txtGn">' . $this->L10N->getString('label.Logged in') . '</div>';
        }

        $RowInfo['AccID'] = \bin2hex($RowInfo['AccUsername']);
        $RowInfo['AccUsername'] = \htmlentities($RowInfo['AccUsername']);
        $this->FE['Accounts'] .= $this->parseVars($RowInfo, $this->FE['AccountsRow'], true);
    }
    unset($RowInfo, $CatValues, $CatKey, $LI);
}

if ($this->FE['ASYNC']) {
    /** Send output (async). */
    echo $this->FE['state_msg'];
} else {
    /** Parse output. */
    $this->FE['FE_Content'] = $this->parseVars($this->FE, $this->readFile($this->getAssetPath('_accounts.html')), true);

    /** Send output. */
    echo $this->sendOutput();
}
