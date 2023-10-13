<?php

use Bitrix\Main\Localization\Loc;

if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();

$arComponentDescription = [
    "NAME" => Loc::getMessage('CURRENCY_COM_NAME') ,
    "DESCRIPTION" => Loc::getMessage('CURRENCY_COM_DESCRIPTION') ,
    "ICON" => "",
    "COMPLEX" => "N",
    "SORT" => 10,
    "PATH" => [
        'ID' => 'currency',
        'NAME' => Loc::getMessage('CURRENCY_COM_NAME') ,
        "CHILD" => [
            "ID" => "_list",
            "NAME" => Loc::getMessage('CURRENCY_COM_DESCRIPTION') ,
            "SORT" => 10,
        ]
    ]
];
