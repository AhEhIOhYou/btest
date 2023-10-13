<?php

require($_SERVER["DOCUMENT_ROOT"] . "/bitrix/header.php");

UpdateCurrenciesAgent();

global $APPLICATION;

$APPLICATION->IncludeComponent(
    "currency.common:currency.list",
    ".default",
);

require($_SERVER["DOCUMENT_ROOT"] . "/bitrix/footer.php");