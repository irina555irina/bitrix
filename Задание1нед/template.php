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

<div id="barba-wrapper">
	<div class="article-list">
		<?php foreach($arResult["ITEMS"] as $arItem) : ?>
			<a class="article-item article-list__item" href=""<?=$arItem["DETAIL_PAGE_URL"]?>"
                                 data-anim="anim-3">
				<div class="article-item__background">
					<img src="<?= $arItem["PREVIEW_PICTURE"]["SRC"] ;?>"
                                                   data-src="xxxHTMLLINKxxx0.39186223192351520.41491856731872767xxx"
                                                   alt=""/>
				</div>
				<div class="article-item__wrapper">
					<div class="article-item__title"> <?= $arItem["PROPERTIES"]["title"]["VALUE"]; ?> </div>
					<div class="article-item__content"><?= $arItem["PROPERTIES"]["text"]["VALUE"]; ?> </div>
				</div>
			</a>
		<?php endforeach; ?> 
	</div>
</div>



