<?php

use Bitrix\Main\Application;
use Bitrix\Main\ErrorCollection;
use Bitrix\Main\HttpRequest;
use Bitrix\Main\Localization\Loc;
use Bitrix\Main\ModuleManager;

Loc::loadMessages(__FILE__);

class baarlord_officemap extends CModule
{
    private ErrorCollection $errors;
    private HttpRequest $request;

    public function __construct()
    {
        $moduleVersion = [];
        include(__DIR__ . '/version.php');
        $this->MODULE_VERSION = $moduleVersion['VERSION'];
        $this->MODULE_VERSION_DATE = $moduleVersion['VERSION_DATE'];
        $this->MODULE_ID = 'baarlord.officemap';
        $this->MODULE_NAME = Loc::getMessage('BO_MODULE_NAME');
        $this->MODULE_DESCRIPTION = Loc::getMessage('BO_MODULE_DESCRIPTION');
        $this->PARTNER_NAME = Loc::getMessage('BO_PARTNER_NAME');
        $this->PARTNER_URI = Loc::getMessage('BO_PARTNER_URI');
        $this->errors = new ErrorCollection();
        $this->request = Application::getInstance()->getContext()->getRequest();
    }

    public function DoInstall()
    {
        $step = $this->request->get('step') ? (int)$this->request->get('step') : 0;
        if ($step < 2) {
            $this->showInstallStep(1);
        } else if ($step === 2) {
            $this->InstallFiles();
        }
        ModuleManager::registerModule($this->MODULE_ID);
        $this->InstallDB();
    }

    protected function showInstallStep(int $step)
    {
        global $APPLICATION;
        $APPLICATION->IncludeAdminFile(
            Loc::getMessage('BO_INSTALL_TITLE', ['#STEP#' => $step]),
            __DIR__ . '/step' . $step . '.php'
        );
    }

    public function InstallFiles()
    {
        foreach ($this->getSites() as $site) {
            if (empty($this->request->get('install_public_' . $site['SITE_ID']))) {
                continue;
            }
            CopyDirFiles(
                dirname(__DIR__) . '/install/officemap',
                $_SERVER['DOCUMENT_ROOT'] . $site['DIR'] . 'officemap'
            );
        }
    }

    private function getSites(): array
    {
        $getListResult = CSite::GetList('', '', ['ACTIVE' => 'Y']);
        $sites = [];
        while ($site = $getListResult->Fetch()) {
            $sites[] = [
                'SITE_ID' => $site['LID'],
                'NAME' => $site['NAME'],
                'DIR' => $site['DIR'],
            ];
        }
        return $sites;
    }

    public function InstallDB()
    {
        $this->addItemToLeftMenu([
            'TEXT' => Loc::getMessage('BO_OFFICE_MAP'),
            'LINK' => '/officemap/',
            'ID' => 'officemap',
            'NEW_PAGE' => 'N',
        ]);
    }

    private function addItemToLeftMenu(array $newItem): void
    {
        foreach ($this->getSites() as $site) {
            if (empty($this->request->get('install_public_' . $site['SITE_ID']))) {
                continue;
            }
            $selfItems = CUserOptions::GetOption('intranet', 'left_menu_self_items_' . $site['SITE_ID']);
            if (!is_array($selfItems) || empty($selfItems)) {
                $selfItems = [$newItem];
            } else {
                $selfItems[] = $newItem;
            }
            CUserOptions::SetOption('intranet', 'left_menu_self_items_' . $site['SITE_ID'], $selfItems);
        }
    }

    public function DoUninstall()
    {
        $this->UnInstallFiles();
        $this->UnInstallDB();
        ModuleManager::unRegisterModule($this->MODULE_ID);
    }

    public function UnInstallFiles()
    {
        foreach ($this->getSites() as $site) {
            DeleteDirFilesEx($site['DIR'] . 'officemap');
        }
    }

    public function UnInstallDB()
    {
        $this->removeLeftMenuItem('officemap');
    }

    private function removeLeftMenuItem(string $linkId): void
    {
        foreach ($this->getSites() as $site) {
            $selfItems = CUserOptions::GetOption('intranet', 'left_menu_self_items_' . $site['SITE_ID']);
            if (!is_array($selfItems) || empty($selfItems)) {
                continue;
            }
            foreach ($selfItems as $index => $item) {
                if ($item['ID'] !== $linkId) {
                    continue;
                }
                unset($selfItems[$index]);
                break;
            }
            CUserOptions::SetOption('intranet', 'left_menu_self_items_' . $site['SITE_ID'], $selfItems);
        }
    }
}
