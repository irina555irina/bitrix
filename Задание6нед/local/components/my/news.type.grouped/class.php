<?php

use Bitrix\Main\Loader;

class NewsTypeGrouped extends \CBitrixComponent
{
    public function onPrepareComponentParams($arParams) 
	{ 
		$arParams['IBLOCK_TYPE'] = trim((string)$arParams['IBLOCK_TYPE']);
		$arParams['IBLOCK_ID'] = (int)$arParams['IBLOCK_ID'];
		return $arParams;
	}

    protected function checkRequiredParams() 
	{
		if (!Loader::includeModule('iblock')) {
    		throw new \Bitrix\Main\SystemException('Модуль инфоблоков не установлен!');
		}

		if (!$this->arParams['IBLOCK_TYPE']) {
			throw new \Bitrix\Main\SystemException('Не указан тип инфоблока!');
		}	
	}

    protected function getElements() 
	{ 
		$arFilter = [
			'ACTIVE' => 'Y',
    		'IBLOCK_ACTIVE' => 'Y',
    		'IBLOCK_TYPE' => $this->arParams['IBLOCK_TYPE'],
		];

		if ($this->arParams['IBLOCK_ID'] > 0) {
    		$arFilter['IBLOCK_ID'] = $this->arParams['IBLOCK_ID'];
		}

		if (!empty($this->arParams['FILTER_NAME'])) {
    		global ${$this->arParams['FILTER_NAME']}; 
			$externalFilter = ${$this->arParams['FILTER_NAME']};
		}

        if (is_array($externalFilter)) {
            $arFilter = array_merge($arFilter, $externalFilter);
        }

		$arSelect = [
			'ID',
    		'NAME',
    		'IBLOCK_ID',
    		'DETAIL_PAGE_URL'
		];

		$dbElements = \CIBlockElement::GetList(
			[], 
			$arFilter, 
			false, 
			false, 
			$arSelect 
		);

		$arResultItems = [];

		while ($arElement = $dbElements->GetNext()) {
			$arResultItems[$arElement['IBLOCK_ID']][] = $arElement;
		}

		return $arResultItems;
	}

    public function executeComponent() 
	{  
		try{
			$this->checkRequiredParams();
			$this->arResult['ITEMS'] = $this->getElements();
			$this->includeComponentTemplate();
		} catch (\Bitrix\Main\SystemException $e) {
			ShowError($e->getMessage());
		}
	}
}