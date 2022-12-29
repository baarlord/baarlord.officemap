<?php
defined('B_PROLOG_INCLUDED') || die;

use Bitrix\Main\Localization\Loc;
use Bitrix\Main\UI\Extension;

/**
 * @var CMain $APPLICATION
 * @var array $arParams
 * @var array $arResult
 * @var boOfficesDetailComponent $component
 */
Extension::load([
    'ui.forms',
    'ui.bootstrap4'
]);
?>
<?php
$fieldNames = [];
foreach ($arParams['ELEMENT_FIELDS'] as $fieldName) {
    $fieldNames[] = ['name' => $fieldName];
}
?>
<div class="container">
    <div class="row">
        <div class="col-lg-4">
            <?php
            $APPLICATION->IncludeComponent(
                'bitrix:ui.form',
                '',
                [
                    'INITIAL_MODE' => 'edit',
                    'CONFIG_ID' => 'baarlord_officemap_office_detail_' . $arParams['ELEMENT_ID'],
                    'ENABLE_SECTION_EDIT' => false,
                    'ENABLE_SECTION_CREATION' => false,
                    'ENABLE_USER_FIELD_CREATION' => false,
                    'ENABLE_SETTINGS_FOR_ALL' => false,
                    'ENABLE_BOTTOM_PANEL' => false,
                    'ENABLE_TOOL_PANEL' => true,
                    'ENABLE_MODE_TOGGLE' => false,
                    'CAN_UPDATE_COMMON_CONFIGURATION' => false,
                    'CAN_UPDATE_PERSONAL_CONFIGURATION' => false,
                    'ENABLE_CONFIG_SCOPE_TOGGLE' => false,
                    'ENABLE_FIELD_DRAG_DROP' => false,
                    'ENABLE_FIELDS_CONTEXT_MENU' => false,
                    'SHOW_EMPTY_FIELDS' => true,
                    'ENTITY_CONFIG' => [
                        [
                            'name' => 'main',
                            'title' => Loc::getMessage('MAIN'),
                            'type' => 'section',
                            'transferable' => false,
                            'enableToggling' => false,
                            'data' => [
                                'showButtonPanel' => false,
                                'isRemovable' => false,
                                'enableTitle' => false,
                            ],
                            'elements' => $fieldNames,
                        ],
                    ],
                    'ENTITY_DATA' => $arResult['ENTITY_DATA'],
                    'ENTITY_FIELDS' => [
                        [
                            'name' => 'NAME',
                            'title' => Loc::getMessage('FIELD_NAME'),
                            'type' => 'text',
                            'required' => true,
                            'editable' => true,
                            'data' => [
                                'enableEditInView' => false,
                                'enableSingleEdit' => false,
                            ],
                        ],
                        [
                            'name' => 'ACTIVE',
                            'title' => Loc::getMessage('FIELD_ACTIVE'),
                            'type' => 'boolean',
                            'required' => true,
                            'editable' => true,
                            'data' => [
                                'enableEditInView' => false,
                                'enableSingleEdit' => false,
                            ],
                        ],
                        [
                            'name' => 'CODE',
                            'title' => Loc::getMessage('FIELD_CODE'),
                            'type' => 'text',
                            'required' => true,
                            'editable' => true,
                            'data' => [
                                'enableEditInView' => false,
                                'enableSingleEdit' => false,
                            ],
                        ],
                        [
                            'name' => 'FLOOR',
                            'title' => Loc::getMessage('FIELD_FLOOR'),
                            'type' => 'text',
                            'required' => true,
                            'editable' => true,
                            'data' => [
                                'enableEditInView' => false,
                                'enableSingleEdit' => false,
                            ],
                        ],
                        [
                            'name' => 'FILE',
                            'title' => Loc::getMessage('FIELD_FILE'),
                            'type' => 'file',
                            'required' => true,
                            'editable' => true,
                            'data' => [
                                'enableEditInView' => false,
                                'enableSingleEdit' => false,
                            ],
                        ],
                        [
                            'name' => 'ADDRESS',
                            'title' => Loc::getMessage('FIELD_ADDRESS'),
                            'type' => 'text',
                            'required' => true,
                            'editable' => true,
                            'data' => [
                                'enableEditInView' => false,
                                'enableSingleEdit' => false,
                            ],
                        ],
                        [
                            'name' => 'SORT',
                            'title' => Loc::getMessage('FIELD_SORT'),
                            'type' => 'text',
                            'required' => true,
                            'editable' => true,
                            'data' => [
                                'enableEditInView' => false,
                                'enableSingleEdit' => false,
                            ],
                        ],
                    ],
                    'GUID' => 'baarlord_officemap_detail_edit',
                    'ENTITY_ID' => $arParams['ELEMENT_ID'],
                    'SERVICE_URL' => $arResult['SERVICE_URL'],
                ],
                $component,
                ['HIDE_ICONS' => 'Y']
            );
            ?>
        </div>
        <div class="col-md-auto">
        </div>
        <div class="col-xl-8">
            <div class="file-preview"></div>
            <script>
            </script>
        </div>
    </div>
</div>
