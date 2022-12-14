<?php
defined('B_PROLOG_INCLUDED') || die;

use Bitrix\Main\Localization\Loc;

/** @var CMain $APPLICATION */
?>
<form action="<?= $APPLICATION->GetCurPage(); ?>">
    <?= bitrix_sessid_post() ?>
    <input type="hidden" name="lang" value="<?= LANG; ?>">
    <input type="hidden" name="id" value="baarlord.officemap">
    <input type="hidden" name="uninstall" value="Y">
    <input type="hidden" name="step" value="1">

    <table border="1" cellpadding="3" cellspacing="0">
        <tr>
            <td><?= Loc::getMessage('BO_DROP_TABLES') ?></td>
            <td><input type="checkbox" name="droptables" id="droptables" value="Y"></td>
        </tr>
    </table>
    <input
            type="submit"
            name="inst"
            value="<?= Loc::getMessage('MOD_UNINSTALL') ?>"
            style="margin-top: 10px;"
    >
</form>

