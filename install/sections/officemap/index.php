<?php
require($_SERVER['DOCUMENT_ROOT'] . '/bitrix/header.php');
/** @var CMain $APPLICATION */
$APPLICATION->SetTitle('Карта офиса');
?>
<?php $APPLICATION->IncludeComponent(
    'baarlord.officemap:offices',
    '',
    [
        'SEF_FOLDER' => SITE_DIR . 'officemap/',
        'SEF_URL_TEMPLATES' => [
            'offices' => '',
            'section' => '#SECTION_CODE#/',
            'detail' => '#SECTION_CODE#/#ELEMENT_CODE#/',
        ],
    ],
    false,
    ['HIDE_ICONS' => 'Y']
);
?>
<?php
require($_SERVER['DOCUMENT_ROOT'] . '/bitrix/footer.php');
