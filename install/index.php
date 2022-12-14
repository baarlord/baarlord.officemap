<?php

use Bitrix\Main\Application;
use Bitrix\Main\DB\SqlQueryException;
use Bitrix\Main\Error as BitrixError;
use Bitrix\Main\ErrorCollection;
use Bitrix\Main\HttpApplication;
use Bitrix\Main\HttpRequest;
use Bitrix\Main\Localization\Loc;
use Bitrix\Main\ModuleManager;
use Bitrix\Main\UrlRewriter;

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
        $step = $this->getCurrentStep();
        if ($step < 1) {
            $this->showInstallStep(1);
        } else if ($step === 1) {
            $this->InstallFiles();
        }
        ModuleManager::registerModule($this->MODULE_ID);
        $this->InstallDB();
    }

    private function getCurrentStep(): int
    {
        return $this->request->get('step') ? (int)$this->request->get('step') : 0;
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
                __DIR__ . '/sections/officemap',
                $_SERVER['DOCUMENT_ROOT'] . $site['DIR'] . 'officemap',
                true,
                true
            );
            UrlRewriter::add($site['SITE_ID'], [
                'CONDITION' => '#^/officemap/#',
                'RULE' => '',
                'ID' => 'baarlord.officemap:offices',
                'PATH' => '/officemap/index.php',
            ]);
        }
        CopyDirFiles(
            __DIR__ . '/components',
            dirname(__DIR__, 3) . '/components/baarlord.officemap',
            true,
            true
        );
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
        $this->createTable('bo_office');
        $this->addItemToLeftMenu([
            'TEXT' => Loc::getMessage('BO_OFFICE_MAP'),
            'LINK' => '/officemap/',
            'ID' => 'officemap',
            'NEW_PAGE' => 'N',
        ]);
    }

    private function createTable(string $tableName): void
    {
        $connection = HttpApplication::getConnection();
        $filePath = __DIR__ . '/db/' . $tableName . '.sql';
        if (!file_exists($filePath)) {
            $this->errors->setError(new BitrixError('Can\'t find sql script to create the table ' . $tableName));
        }
        $contents = file_get_contents($filePath);
        foreach ($connection->parseSqlBatch($contents) as $sql) {
            $connection->query($sql);
        }
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
        global $APPLICATION;
        $step = $this->getCurrentStep();
        if ($step < 1) {
            $this->showUninstallStep(1);
        } elseif ($step === 1) {
            $this->UnInstallFiles();
            $this->UnInstallDB();
        }
        ModuleManager::unRegisterModule($this->MODULE_ID);
    }

    protected function showUninstallStep(int $step)
    {
        global $APPLICATION;
        $APPLICATION->IncludeAdminFile(
            Loc::getMessage('BO_UNINSTALL_TITLE', ['#STEP#' => $step]),
            __DIR__ . '/unstep' . $step . '.php'
        );
    }

    public function UnInstallFiles()
    {
        foreach ($this->getSites() as $site) {
            DeleteDirFilesEx($site['DIR'] . 'officemap');
            UrlRewriter::delete($site['SITE_ID'], [
                'ID' => 'baarlord.officemap:offices',
            ]);
        }
        DeleteDirFilesEx('/bitrix/components/baarlord.officemap');
    }

    public function UnInstallDB()
    {
        if ($this->request->get('droptables') === 'Y') {
            $this->dropTable('bo_office');
        }
        $this->removeLeftMenuItem('officemap');
    }

    private function dropTable(string $tableName): void
    {
        Application::getConnection()->query("DROP TABLE IF EXISTS " . $tableName);
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
