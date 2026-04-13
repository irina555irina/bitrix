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


<div class="contact-form">
    <div class="contact-form__head">
        <div class="contact-form__head-title">Связаться</div>
        <div class="contact-form__head-text">
            Наши сотрудники помогут выполнить подбор услуги и&nbsp;расчет цены с&nbsp;учетом ваших требований
        </div>
    </div>

	<div class="contact-form">
    <?=$arResult["FORM_HEADER"]?>
        <div class="contact-form__form-inputs">
            <?// Поле: Ваше имя ?>
            <div class="input contact-form__input">
                <label class="input__label" for="medicine_name">
                    <div class="input__label-text">Ваше имя*</div>
                    <input class="input__input" type="text" id="medicine_name" required
                           name="form_<?=$arResult["QUESTIONS"]["NAME"]["STRUCTURE"][0]["FIELD_TYPE"]?>_<?=$arResult["QUESTIONS"]["NAME"]["STRUCTURE"][0]["ID"]?>"  
                           value="<?=htmlspecialcharsbx($arResult["arrVALUES"]["form_text_" . $arResult["QUESTIONS"]["NAME"]["STRUCTURE"][0]["ID"]])?>">
                    <div class="input__notification">Поле должно содержать не менее 3-х символов</div>
                </label>
            </div>

            <?// Поле: Компания ?>
            <div class="input contact-form__input">
                <label class="input__label" for="medicine_company">
                    <div class="input__label-text">Компания/Должность*</div>
                    <input class="input__input" type="text" id="medicine_company" required
                           name="form_<?=$arResult["QUESTIONS"]["COMPANY"]["STRUCTURE"][0]["FIELD_TYPE"]?>_<?=$arResult["QUESTIONS"]["COMPANY"]["STRUCTURE"][0]["ID"]?>" 
                           value="<?=htmlspecialcharsbx($arResult["arrVALUES"]["form_text_" . $arResult["QUESTIONS"]["COMPANY"]["STRUCTURE"][0]["ID"]])?>">
                    <div class="input__notification">Поле должно содержать не менее 3-х символов</div>
                </label>
            </div>

            <?// Поле: Email ?>
            <div class="input contact-form__input">
                <label class="input__label" for="medicine_email">
                    <div class="input__label-text">Email*</div>
                    <input class="input__input" type="email" id="medicine_email" required
                           name="form_<?=$arResult["QUESTIONS"]["EMAIL"]["STRUCTURE"][0]["FIELD_TYPE"]?>_<?=$arResult["QUESTIONS"]["EMAIL"]["STRUCTURE"][0]["ID"]?>" 
                           value="<?=htmlspecialcharsbx($arResult["arrVALUES"]["form_email_" . $arResult["QUESTIONS"]["EMAIL"]["STRUCTURE"][0]["ID"]])?>">
                    <div class="input__notification">Неверный формат почты</div>
                </label>
            </div>

            <?// Поле: Номер телефона ?>
            <div class="input contact-form__input">
                <label class="input__label" for="medicine_phone">
                    <div class="input__label-text">Номер телефона*</div>
                    <input class="input__input" type="tel" id="medicine_phone" required
                           data-inputmask="'mask': '+79999999999', 'clearIncomplete': 'true'" maxlength="12"
                           name="form_<?=$arResult["QUESTIONS"]["PHONE"]["STRUCTURE"][0]["FIELD_TYPE"]?>_<?=$arResult["QUESTIONS"]["PHONE"]["STRUCTURE"][0]["ID"]?>" 
                           value="<?=htmlspecialcharsbx($arResult["arrVALUES"]["form_text_" . $arResult["QUESTIONS"]["PHONE"]["STRUCTURE"][0]["ID"]])?>">
                </label>
            </div>
        </div>

        <?// Поле: Сообщение ?>
        <div class="contact-form__form-message">
            <div class="input">
                <label class="input__label" for="medicine_message">
                    <div class="input__label-text">Сообщение</div>
                    <textarea class="input__input" id="medicine_message" 
                              name="form_<?=$arResult["QUESTIONS"]["MESSAGE"]["STRUCTURE"][0]["FIELD_TYPE"]?>_<?=$arResult["QUESTIONS"]["MESSAGE"]["STRUCTURE"][0]["ID"]?>"
                    ><?=htmlspecialcharsbx($arResult["arrVALUES"]["form_textarea_" . $arResult["QUESTIONS"]["MESSAGE"]["STRUCTURE"][0]["ID"]])?></textarea>
                </label>
            </div>
        </div>

        <div class="contact-form__bottom">
            <div class="contact-form__bottom-policy">
                Нажимая &laquo;Отправить&raquo;, Вы&nbsp;подтверждаете, что ознакомлены, полностью согласны и&nbsp;принимаете условия &laquo;Согласия на&nbsp;обработку персональных данных&raquo;.
            </div>
            <input type="submit" name="web_form_submit" value="Отправить">
        </div>
    <?=$arResult["FORM_FOOTER"]?>
</div>

