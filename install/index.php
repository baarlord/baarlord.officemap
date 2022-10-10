<?php

use Bitrix\Main\Localization\Loc;
use Bitrix\Main\ModuleManager;

Loc::loadMessages(dirname(__DIR__) . '/lang/ru/install/index.php');

class baarlord_officemap extends CModule
{
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
    }

    public function DoInstall()
    {
        ModuleManager::registerModule($this->MODULE_ID);
    }

    public function DoUninstall()
    {
        ModuleManager::unRegisterModule($this->MODULE_ID);
    }
}
