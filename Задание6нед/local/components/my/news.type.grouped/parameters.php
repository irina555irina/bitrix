<?if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();

$arComponentParameters = [
    "PARAMETERS" => [
        "IBLOCK_TYPE" => [
            "PARENT" => "BASE",
            "NAME" => "Тип инфоблока (строка)",
            "TYPE" => "STRING",
            "DEFAULT" => "news",
        ],
        "IBLOCK_ID" => [
            "PARENT" => "BASE",
            "NAME" => "ID инфоблока (0 - все)",
            "TYPE" => "STRING",
            "DEFAULT" => "0",
        ],
        "FILTER_NAME" => [
            "PARENT" => "DATA_SOURCE",
            "NAME" => "Имя глобального фильтра",
            "TYPE" => "STRING",
            "DEFAULT" => "arrFilter",
        ],
    ],
];