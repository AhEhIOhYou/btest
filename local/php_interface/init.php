<?php

if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();

use Currency\Common\Api\CurrencyService\CurrencyService;
use Currency\Common\Hlb\HlbCurrency\HlbCurrency;
use Bitrix\Main\Loader;

use Bitrix\Main\Page\Asset;

$asset = Asset::getInstance();
$asset->addJs('https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/js/bootstrap.bundle.min.js');
$asset->addCss('https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/css/bootstrap.min.css');

// Фикс с подключением js ядра битрикса для неавторизованных
CUtil::InitJSCore(['ajax']);

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

    return "UpdateCurrenciesAgent();";
}