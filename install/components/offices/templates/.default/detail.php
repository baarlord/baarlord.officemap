<?php
defined('B_PROLOG_INCLUDED') || die;

/**
 * @var CMain $APPLICATION
 * @var boOfficesDetailComponent $component
 * @var array $arResult
 */
?>
<?php $APPLICATION->IncludeComponent(
        'baarlord.officemap:offices.detail',
    $arResult['TEMPLATE'] ?? '',
    [
        'ELEMENT_ID' => $arResult['ELEMENT_ID'],
    ],
    $component,
    ['HIDE_ICONS' => 'Y']
);
?>

