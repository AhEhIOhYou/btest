<?php

if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();

use Currency\Common\Api\CurrencyService\CurrencyService;
use Currency\Common\Hlb\HlbCurrency\HlbCurrency;
use Bitrix\Main\Loader;

try {
    Loader::includeModule('currency.common');
} catch (\Bitrix\Main\LoaderException $e) {
    highlight_string("<?php\n\$data =\n" . var_export($e, true) . ";\n?>");
}

function UpdateCurrenciesAgent() {

    if (!CModule::IncludeModule('currency.common'))
        return "UpdateCurrenciesAgent();";

    $CService = new CurrencyService();

    $courses = $CService->parseCurrencies($CService->getCursOnDate(Date('Y-m-d')));
    $hlb = new HlbCurrency();

    foreach ($courses as $course) {
        $hlb->add($course);
    }

    AddMessage2Log("Периодический BX_CRONTAB:" . BX_CRONTAB . " BX_CRONTAB_SUPPORT:" . BX_CRONTAB_SUPPORT);

    return "UpdateCurrenciesAgent();";
}