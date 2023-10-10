<?php

class dev_common extends CModule
{
    var $MODULE_ID = "dev.common";
    var $MODULE_VERSION;
    var $MODULE_VERSION_DATE;
    var $MODULE_NAME;
    var $MODULE_DESCRIPTION;
    var $MODULE_CSS;

    function __construct()
    {
        $arModuleVersion = array();
        $path = str_replace("\\", "/", __FILE__);
        $path = substr($path, 0, strlen($path) - strlen("/index.php"));
        include($path . "/version.php");
        if (is_array($arModuleVersion) && array_key_exists("VERSION", $arModuleVersion)) {
            $this->MODULE_VERSION = $arModuleVersion["VERSION"];
            $this->MODULE_VERSION_DATE = $arModuleVersion["VERSION_DATE"];
        }
        $this->MODULE_NAME = "dev.common – модуль с компонентом";
        $this->MODULE_DESCRIPTION = "После установки вы сможете пользоваться компонентом dev.common:currency.list";
    }

    function InstallFiles(): true
    {
        CopyDirFiles($_SERVER["DOCUMENT_ROOT"] . "/local/modules/dev.common/install/components",
            $_SERVER["DOCUMENT_ROOT"] . "/bitrix/components", true, true);
        return true;
    }

    function UnInstallFiles(): true
    {
        DeleteDirFilesEx("/local/components/dev.common");
        return true;
    }

    function DoInstall(): void
    {
        global $DOCUMENT_ROOT, $APPLICATION;
        $this->InstallFiles();
        RegisterModule("dev.common");
        $APPLICATION->IncludeAdminFile("Установка модуля dev.common", $DOCUMENT_ROOT . "/local/modules/dev.common/install/step.php");
    }

    function DoUninstall(): void
    {
        global $DOCUMENT_ROOT, $APPLICATION;
        $this->UnInstallFiles();
        UnRegisterModule("dev.common");
        $APPLICATION->IncludeAdminFile("Деинсталляция модуля dev.common", $DOCUMENT_ROOT . "/local/modules/dev.common/install/unstep.php");
    }
}
