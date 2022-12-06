<?php
defined('B_PROLOG_INCLUDED') || die;

use Bitrix\Main\Localization\Loc;

/** @var CMain $APPLICATION */
$getListResult = CSite::GetList('', '', ['ACTIVE' => 'Y']);
$sites = [];
while ($site = $getListResult->Fetch()) {
    $sites[] = [
        'SITE_ID' => $site['LID'],
        'NAME' => $site['NAME'],
        'DIR' => $site['DIR'],
    ];
}
?>
<form action="<?= $APPLICATION->GetCurPage() ?>" name="form1">
    <?= bitrix_sessid_post() ?>
    <input type="hidden" name="lang" value="<?= LANG ?>"/>
    <input type="hidden" name="id" value="baarlord.officemap"/>
    <input type="hidden" name="install" value="Y"/>
    <input type="hidden" name="step" value="2"/>

    <table border="1" cellpadding="3" cellspacing="0">
        <tr>
            <td></td>
            <?php foreach ($sites as $site): ?>
                <td><?= $site['NAME'] ?></td>
            <?php endforeach; ?>
        </tr>
        <tr>
            <td><?= Loc::getMessage('BO_COPY_PUBLIC_FILES') ?></td>
            <?php foreach ($sites as $site): ?>
                <td>
                    <input
                            type="checkbox"
                            name="install_public_<?= $site['SITE_ID'] ?>"
                            value="Y"
                    >
                </td>
            <?php endforeach; ?>
        </tr>
    </table>
    <input
            type="submit"
            name="inst"
            value="<?= Loc::getMessage('MOD_INSTALL') ?>"
            style="margin-top: 10px;"
    >
</form>
