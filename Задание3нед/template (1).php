<?
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();

$APPLICATION->SetTitle("Новости на шаблоне");

$this->addExternalCss("/bitrix/templates/eshop_bootstrap_v4/components/bitrix/news.list/my_news_form/css/common.css");

$this->addExternalCss("/bitrix/templates/eshop_bootstrap_v4/components/bitrix/news.list/my_news_form/fonts/Gilroy-Bold.ttf");
$this->addExternalCss("/bitrix/templates/eshop_bootstrap_v4/components/bitrix/news.list/my_news_form/fonts/Gilroy-Bold.woff");
$this->addExternalCss("/bitrix/templates/eshop_bootstrap_v4/components/bitrix/news.list/my_news_form/fonts/Gilroy-Bold.woff2");
$this->addExternalCss("/bitrix/templates/eshop_bootstrap_v4/components/bitrix/news.list/my_news_form/fonts/Gilroy-Extrabold.ttf");
$this->addExternalCss("/bitrix/templates/eshop_bootstrap_v4/components/bitrix/news.list/my_news_form/fonts/Gilroy-Extrabold.woff");
$this->addExternalCss("/bitrix/templates/eshop_bootstrap_v4/components/bitrix/news.list/my_news_form/fonts/Gilroy-Extrabold.woff2");
$this->addExternalCss("/bitrix/templates/eshop_bootstrap_v4/components/bitrix/news.list/my_news_form/fonts/Gilroy-Light.ttf");
$this->addExternalCss("/bitrix/templates/eshop_bootstrap_v4/components/bitrix/news.list/my_news_form/fonts/Gilroy-Light.woff");
$this->addExternalCss("/bitrix/templates/eshop_bootstrap_v4/components/bitrix/news.list/my_news_form/fonts/Gilroy-Light.woff2");
$this->addExternalCss("/bitrix/templates/eshop_bootstrap_v4/components/bitrix/news.list/my_news_form/fonts/Gilroy-Medium.ttf");
$this->addExternalCss("/bitrix/templates/eshop_bootstrap_v4/components/bitrix/news.list/my_news_form/fonts/Gilroy-Medium.woff");
$this->addExternalCss("/bitrix/templates/eshop_bootstrap_v4/components/bitrix/news.list/my_news_form/fonts/Gilroy-Medium.woff2");
$this->addExternalCss("/bitrix/templates/eshop_bootstrap_v4/components/bitrix/news.list/my_news_form/fonts/Gilroy-Regular.ttf");
$this->addExternalCss("/bitrix/templates/eshop_bootstrap_v4/components/bitrix/news.list/my_news_form/fonts/Gilroy-Regular.woff");
$this->addExternalCss("/bitrix/templates/eshop_bootstrap_v4/components/bitrix/news.list/my_news_form/fonts/Gilroy-Regular.woff2");
$this->addExternalCss("/bitrix/templates/eshop_bootstrap_v4/components/bitrix/news.list/my_news_form/fonts/GothamPro.ttf");
$this->addExternalCss("/bitrix/templates/eshop_bootstrap_v4/components/bitrix/news.list/my_news_form/fonts/GothamPro.woff");
$this->addExternalCss("/bitrix/templates/eshop_bootstrap_v4/components/bitrix/news.list/my_news_form/fonts/GothamPro.woff2");
?>

<div class="article-card">
    <div class="article-card__title"><?= $arResult["PROPERTIES"]["title"]["VALUE"]; ?></div>
    <div class="article-card__date">15 авг 2019</div>
    <div class="article-card__content">
        <div class="article-card__image sticky"><img 
			src="<?=$arResult["DISPLAY_PROPERTIES"]["picture"]["FILE_VALUE"]["SRC"]?>" alt="" data-object-fit="cover"/>
        </div>
        <div class="article-card__text">
            <div class="block-content" data-anim="anim-3"><p>
				<?= $arResult["PROPERTIES"]["text"]["VALUE"] ?></p>
            </div>
		</div>
    </div>
</div>
