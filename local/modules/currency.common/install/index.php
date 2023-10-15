<?php

use Bitrix\Main\Loader;
use Bitrix\Main\Localization\Loc;
use Currency\Common\Hlb\HlbCurrency\HlbCurrency;
use Bitrix\Main\ModuleManager;
use Bitrix\Main\Config\Option;
use Bitrix\Main\EventManager;
use Bitrix\Main\Application;
use Bitrix\Main\IO\Directory;

Loc::loadMessages(__FILE__);

class currency_common extends CModule
{
    private const MODULE_ID = "currency.common";

    public function __construct()
    {
        if (is_file(__DIR__ . '/version.php')) {
            include_once(__DIR__ . '/version.php');
            $this->MODULE_ID = self::MODULE_ID;
            $this->MODULE_VERSION = $arModuleVersion['VERSION'];
            $this->MODULE_VERSION_DATE = $arModuleVersion['VERSION_DATE'];
            $this->MODULE_NAME = Loc::getMessage('CURRENCY_MOD_NAME');
            $this->MODULE_DESCRIPTION = Loc::getMessage('CURRENCY_MOD_DESCRIPTION');
        } else {
            CAdminMessage::ShowMessage(
                Loc::getMessage('CURRENCY_MOD_FILE_NOT_FOUND') . ' version.php'
            );
        }
    }

    public function InstallFiles(): void
    {
        CopyDirFiles(
            __DIR__ . '/components',
            Application::getDocumentRoot() . '/local/components/' . self::MODULE_ID . '/',
            true,
            true
        );
    }

    public function InstallDB(): void
    {
        try {
            Loader::includeModule(self::MODULE_ID);
        } catch (\Bitrix\Main\LoaderException $e) {
            highlight_string("<?php\n\$data =\n" . var_export($e, true) . ";\n?>");
        }

        try {
            HlbCurrency::createHlb();
        } catch (Exception $e) {
            CAdminMessage::ShowMessage(
                Loc::getMessage('CURRENCY_MOD_INSTALL_FAILED')
            );
        }
    }

    public function InstallEvents(): void
    {
        return;
    }

    public function UnInstallFiles(): void
    {
        Directory::deleteDirectory(
            Application::getDocumentRoot() . '/local/components/' . self::MODULE_ID
        );
        Option::delete(self::MODULE_ID);
    }

    public function UnInstallDB(): void
    {
        try {
            HlbCurrency::deleteHlb();
        } catch (Exception $e) {
            CAdminMessage::ShowMessage(
                Loc::getMessage('CURRENCY_MOD_UNINSTALL_FAILED')
            );
        }
    }

    public function UnInstallEvents(): void
    {
        return;
    }

    public function DoInstall(): void
    {
        ModuleManager::registerModule(self::MODULE_ID);
        $this->InstallFiles();
        $this->InstallDB();
        $this->InstallEvents();

        CAgent::AddAgent(
            "UpdateCurrenciesAgent();",
            self::MODULE_ID,
            "N",
            86400,
            date("d.m.Y H:i:s", time() + 10),
            "Y",
            date("d.m.Y H:i:s", time() + 10),
            30
        );

    }

    public function DoUninstall(): void
    {
        CAgent::RemoveAgent("UpdateCurrenciesAgent();", self::MODULE_ID);

        $this->UnInstallFiles();
        $this->UnInstallDB();
        $this->UnInstallEvents();

        ModuleManager::unRegisterModule(self::MODULE_ID);
    }
}
