<?php
defined('B_PROLOG_INCLUDED') || die;

use Bitrix\Main\Loader;
use Bitrix\Main\Localization\Loc;

class boOfficesComponent extends CBitrixComponent
{
    private CComponentEngine $engine;

    public function __construct($component = null)
    {
        parent::__construct($component);
        $this->engine = new CComponentEngine($this);
    }

    public function onPrepareComponentParams($arParams)
    {
        $arParams = parent::onPrepareComponentParams($arParams);
        $arParams['SEF_FOLDER'] = $arParams['SEF_FOLDER'] ?? '';
        $arParams['VARIABLE_ALIASES'] = $arParams['VARIABLE_ALIASES'] ?? [];
        $arParams['SEF_URL_TEMPLATES'] = $arParams['SEF_URL_TEMPLATES'] ?? [];
        return $arParams;
    }

    public function executeComponent()
    {
        global $APPLICATION;

        if (
            !Loader::includeModule('baarlord.officemap')
        ) {
            ShowError(Loc::getMessage('BO_CANT_LOAD_REQUIRED_MODULES'));
            return;
        }

        $variables = [];
        $defaultUrlTemplates404 = [
            'offices' => '',
            'section' => '#SECTION_CODE#/',
            'detail' => '#SECTION_CODE#/#ELEMENT_CODE#/',
        ];
        $componentVariableAliases = [
            'SECTION_CODE',
            'ELEMENT_CODE',
        ];

        $urlTemplates = $this->engine::makeComponentUrlTemplates(
            $defaultUrlTemplates404,
            $this->arParams['SEF_URL_TEMPLATES']
        );
        $variableAliases = $this->engine::makeComponentVariableAliases(
            [],
            $this->arParams['VARIABLE_ALIASES']
        );
        $componentPage = $this->engine->guessComponentPath(
            $this->arParams['SEF_FOLDER'],
            $urlTemplates,
            $variables
        );

        $is404 = false;
        if (!$componentPage) {
            $componentPage = 'offices';
            $is404 = true;
        }
        if ($componentPage === 'section') {
            $is404 = !isset($variables['SECTION_CODE']);
        }
        if ($componentPage === 'detail') {
            $is404 = (!isset($variables['SECTION_CODE']) || !isset($variables['ELEMENT_CODE']));
        }
        if ($is404) {
            $folder404 = str_replace("\\", "/", $this->arParams['SEF_FOLDER']);
            if ($folder404 != '/')
                $folder404 = '/' . trim($folder404, "/ \t\n\r\0\x0B") . "/";
            if (mb_substr($folder404, -1) == '/')
                $folder404 .= 'index.php';

            if ($folder404 != $APPLICATION->GetCurPage(true)) {
                ShowError(Loc::getMessage('BO_UNKNOWN_PAGE'));
                @define('ERROR_404', 'Y');
                CHTTP::SetStatus('404 Not Found');
                return;
            }
        }

        $this->engine::initComponentVariables($componentPage, $componentVariableAliases, $variableAliases, $variables);

        $this->arResult = [
            'FOLDER' => '',
            'URL_TEMPLATES' => $urlTemplates,
            'VARIABLES' => $variables,
            'ALIASES' => $variableAliases,
        ];
        $this->includeComponentTemplate($componentPage);
    }
}
