<?php
namespace My\Logger; 

use Bitrix\Main\Loader;
use Bitrix\Iblock\IblockTable; 
use Bitrix\Iblock\SectionTable; 
use Bitrix\Iblock\ElementTable; 
use Bitrix\Main\Type\DateTime; 

class Handler
{
    public static function logElementChange(&$arFields)
    {
		if(!Loader::includeModule('iblock')) {
			return;
		}

		$currentIblock = IblockTable::getList([
            'filter' => ['ID' => $arFields["IBLOCK_ID"]],
            'select' => ['NAME', 'CODE']
        ])->fetch();

		if (!$currentIblock) {
            return;
        }

		if ($currentIblock['CODE'] === 'LOG') {
			return;
		}

        $logIblock = IblockTable::getList([
            'filter' => ['CODE' => 'LOG'],
            'select' => ['ID']
        ])->fetch();

		$sectionId = self::getOrCreateLogSection(
            $logIblock['ID'], 
            $currentIblock['NAME'], 
            $currentIblock['CODE']
        );

		$chain = [$currentIblock['NAME']];

		if (!empty($arFields['IBLOCK_SECTION_ID'])) {
			$itemSectionId = is_array($arFields['IBLOCK_SECTION_ID']) ? current($arFields['IBLOCK_SECTION_ID']) : $arFields['IBLOCK_SECTION_ID'];
			$sectionsChain = self::getSectionPathChain((int)$itemSectionId);
			$chain = array_merge($chain, $sectionsChain);
		}

		$chain[] = $arFields['NAME']; 
		$previewText = implode(' -> ', $chain); 

		$element = new \CIBlockElement;
		$element->Add([
			"IBLOCK_SECTION_ID" => $sectionId,    
			"IBLOCK_ID"         => $logIblock['ID'], 
			"NAME"              => $arFields['ID'],  
			"ACTIVE_FROM"       => new DateTime(), 
			"PREVIEW_TEXT"      => $previewText,  
			"ACTIVE"            => "Y",
		]);
    }

    private static function getOrCreateLogSection($logIblockId, $name, $code): int
	{
		
		if (!$logIblockId || (int)$logIblockId <= 0) {
			return 0;
		}

        $sectionCode = !empty($code) ? $code : 'IB_CODE_' . $logIblockId;

        $section = SectionTable::getList([
            'filter' => [
                'IBLOCK_ID' => $logIblockId,
                'CODE' => $sectionCode
            ],
            'select' => ['ID']
        ])->fetch();

        if ($section) {
            return (int)$section['ID'];
        }

        $bs = new \CIBlockSection;
        $arFields = [
            "ACTIVE" => "Y",
            "IBLOCK_ID" => $logIblockId,
            "NAME" => $name,
            "CODE" => $sectionCode,
        ];

        $newSectionId = $bs->Add($arFields);

		if ($newSectionId) {
    		return (int)$newSectionId;
		} 
		//else {
		//$bs->LAST_ERROR
		//}
	}

	private static function getSectionPathChain(int $sectionId): array
	{
		if ($sectionId <= 0) {
			return [];
		}

		$section = SectionTable::getList([
			'filter' => ['ID' => $sectionId],
			'select' => ['NAME', 'IBLOCK_SECTION_ID']
		])->fetch();

		if (!$section) {
				return [];
		}

		$parentChain = self::getSectionPathChain((int)$section['IBLOCK_SECTION_ID']);
		$parentChain[] = $section['NAME'];

		return $parentChain;
	}


	public static function clearOldLogsAgent()
    {
        if (!\Bitrix\Main\Loader::includeModule('iblock')) {
            return __METHOD__ . '();'; 
        }

        $logIblock = IblockTable::getList([
            'select' => ['ID'],
            'filter' => ['=CODE' => 'LOG'] 
        ])->fetch();

        if (!$logIblock) {
            return __METHOD__ . '();'; 
        }

        $logIblockId = (int)$logIblock['ID'];

        $elements = ElementTable::getList([
            'select' => ['ID'], 
            'filter' => [
                '=IBLOCK_ID' => $logIblockId, 
            ],
            'order'  => ['ID' => 'DESC'], 
            'limit'  => 10,               
        ]);

        $keepIds = []; 
        while ($ar = $elements->fetch()) { 
            $keepIds[] = (int)$ar['ID'];
        }

        if (count($keepIds) < 10) {
            return __METHOD__ . '();';
        }

        $elementsToDelete = ElementTable::getList([
            'select' => ['ID'],
            'filter' => [
                '=IBLOCK_ID' => $logIblockId,
                '!@ID' => $keepIds, 
            ],
            'order' => ['ID' => 'ASC'] 
        ]);

        $deleteIds = []; 
        while ($ar = $elementsToDelete->fetch()) {
            $deleteIds[] = (int)$ar['ID'];
        }

        if (!empty($deleteIds)) {
            foreach ($deleteIds as $id) {
                \CIBlockElement::Delete($id); 
            }
        }

        return __METHOD__ . '();';
    }

}
