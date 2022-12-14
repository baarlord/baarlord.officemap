<?php
defined('B_PROLOG_INCLUDED') || die;

use Bitrix\Main\Loader;
use Bitrix\Main\LoaderException;

class boOfficesListComponent extends CBitrixComponent
{
    public function executeComponent()
    {
        try {
            $this->loadModules();
/*            $filter = $this->getFilter();
            $this->arResult['ITEMS'] = $this->getItems();*/
            $this->includeComponentTemplate();
        } catch (LoaderException $e) {
            ShowError($e->getMessage());
            return;
        }
    }

    /**
     * @throws LoaderException
     */
    private function loadModules(): void
    {
        if (
            !Loader::includeModule('baarlord.officemap')
        ) {
            throw new LoaderException('Can\'t load required modules');
        }
    }

    private function getFilter(): array
    {
        return [];
    }

    private function getItems(): array
    {
        return [];
    }
}
