<?php

use Bitrix\Main\Loader;
use Currency\Common\Hlb\HlbCurrency\HlbCurrency;

class currency_common extends CModule
{
    var $MODULE_ID = "currency.common";
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
        $this->MODULE_NAME = "Currency – модуль с компонентом";
        $this->MODULE_DESCRIPTION = "После установки вы сможете пользоваться компонентом Currency:currency.list";
    }

    function InstallFiles(): true
    {
        CopyDirFiles($_SERVER["DOCUMENT_ROOT"] . "/local/modules/currency.common/install/components",
            $_SERVER["DOCUMENT_ROOT"] . "/bitrix/components", true, true);
        return true;
    }

    function UnInstallFiles(): true
    {
        DeleteDirFilesEx("/local/components/currency.common");
        return true;
    }

    function DoInstall(): void
    {
        $this->InstallFiles();
        RegisterModule("currency.common");

        try {
            Loader::includeModule('currency.common');
        } catch (\Bitrix\Main\LoaderException $e) {
            highlight_string("<?php\n\$data =\n" . var_export($e, true) . ";\n?>");
        }

        try {
            HlbCurrency::createHlb();
        } catch (Exception $e) {
            highlight_string("<?php\n\$data =\n" . var_export($e, true) . ";\n?>");
        }

        CAgent::AddAgent(
            "UpdateCurrenciesAgent();", // имя функции
            "currency.common", // идентификатор модуля
            "N",  // агент не критичен к кол-ву запусков
            60, // интервал запуска - 24 часа
            "", // дата первой проверки на запуск
            "Y", // агент активен
            "", // дата первого запуска
            30
        );

    }

    function DoUninstall(): void
    {
        try {
            HlbCurrency::deleteHlb();
        } catch (Exception $e) {
            highlight_string("<?php\n\$data =\n" . var_export($e, true) . ";\n?>");
        }

        CAgent::RemoveAgent("UpdateCurrenciesAgent();", "currency.common");

        $this->UnInstallFiles();
        UnRegisterModule("currency.common");
    }
}
