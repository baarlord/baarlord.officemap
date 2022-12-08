<?php
defined('B_PROLOG_INCLUDED') || die;

/**
 * @var CBitrixComponentTemplate $this
 * @var CMain $APPLICATION
 * @var array $arParams
 * @var array $arResult
 * @var boOfficesComponent $component
 * @var string $componentPath
 */
?>
<?php
$APPLICATION->IncludeComponent(
        'baarlord.officemap:offices.list',
    '',
    [],
    $component,
    ['HIDE_ICONS' => 'Y']
);
?>
