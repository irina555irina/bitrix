<?
if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();?>

<?
$APPLICATION->IncludeComponent(
    "bitrix:catalog.section.list",
    "",
    array(
        "IBLOCK_ID" => $arParams["IBLOCK_ID"],
        "SECTION_URL" => $arResult["FOLDER"].$arResult["URL_TEMPLATES"]["section"],
        "TOP_DEPTH" => "1",
        "COUNT_ELEMENTS" => "Y",
    ),
    $component
);
?>