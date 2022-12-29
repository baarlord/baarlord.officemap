<?php
defined('B_PROLOG_INCLUDED') || die;

use Bitrix\Main\Localization\Loc;

/**
 * @var CMain $APPLICATION
 * @var boOfficesDetailComponent $component
 * @var array $arResult
 */
$APPLICATION->SetTitle(Loc::getMessage('DETAIL_PAGE'));
?>
<?php $APPLICATION->IncludeComponent(
        'baarlord.officemap:offices.detail',
    $arResult['TEMPLATE'] ?? '',
    [
        'ELEMENT_ID' => $arResult['VARIABLES']['ELEMENT_ID'],
        'ELEMENT_FIELDS' => ['NAME', 'ACTIVE', 'CODE', 'FLOOR', 'ADDRESS', 'SORT', 'FILE'],
    ],
    $component,
    ['HIDE_ICONS' => 'Y']
);
?>

