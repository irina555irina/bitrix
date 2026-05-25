<?
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");
$APPLICATION->SetTitle("News_Type_Grouped");
?><?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();?>

<?
foreach ($arResult['ITEMS'] as $iblockId => $arItems) {
    echo "<h2>Инфоблок № " . $iblockId . "</h2>";
    
    foreach ($arItems as $arItem) {
        echo '<p><a href="' . $arItem['DETAIL_PAGE_URL'] . '">' . $arItem['ID'] . ' - ' . $arItem['NAME'] . '</a></p>';
    }
}
?>

<?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");?>