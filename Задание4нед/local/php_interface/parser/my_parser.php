<?php

require_once($_SERVER['DOCUMENT_ROOT'] . "/bitrix/modules/main/include/prolog_before.php");

if (!$USER->IsAdmin()) die("Доступ запрещён");

\Bitrix\Main\Loader::includeModule('iblock');

$IBLOCK_ID = 4;
$file = $_SERVER['DOCUMENT_ROOT'] . "/local/php_interface/parser/vacancy.csv";
$CIBlockElementObject = new CIBlockElement;
$propertyList = [];
$row = 1;

$propertyEnumResult = CIBlockPropertyEnum::GetList(
    ["SORT" => "ASC", "VALUE" => "ASC"],
    ['IBLOCK_ID' => $IBLOCK_ID]
);

while ($propertyFields = $propertyEnumResult->Fetch()) {
    $key = trim($propertyFields['VALUE']);
    $propertyList[$propertyFields['PROPERTY_CODE']][$key] = $propertyFields['ID'];
}

if (($handle = fopen($file, "r")) !== false) {
    while (($data = fgetcsv($handle, 1000, ",")) !== false) {
        if ($row == 1) {
            $row++;
            continue;
        }
        $row++;

        $getListId = function($propCode, $value) use ($propertyList) {
            $val = trim($value);
            return  $propertyList[$propCode][$val] ?? false;
        };

        $PROP = [];
        $PROP['FIELD']       = $getListId('FIELD',       $data[11]);
        $PROP['ACTIVITY']    = $getListId('ACTIVITY',    $data[9]);
        $PROP['OFFICE']      = $getListId('OFFICE',      $data[1]);
        $PROP['LOCATION']    = $getListId('LOCATION',    $data[2]);
        $PROP['TYPE']        = $getListId('TYPE',        $data[8]);
        $PROP['SALARY_TYPE'] = $getListId('SALARY_TYPE', $data[7]);
        $PROP['SCHEDULE']    = $getListId('SCHEDULE',    $data[10]);

        $PROP['REQUIRE']      = $data[4];
        $PROP['DUTY']         = $data[5];
        $PROP['CONDITIONS']   = $data[6];
        $PROP['EMAIL']        = $data[12];
        $PROP['SALARY_VALUE'] = $data[7];
        $PROP['DATE']         = date('d.m.Y');

        foreach ($PROP as $key => &$value) {
            $value = trim($value);
            $value = str_replace('\n', '', $value);
            
            if (stripos($value, '•') !== false) {
                $value = explode('•', $value);
                array_splice($value, 0, 1);
                foreach ($value as &$str) {
                    $str = trim($str);
                }
            }
        }

	    $newVacancy = [
            "MODIFIED_BY" => $USER->GetID(),
            "IBLOCK_SECTION_ID" => false,
            "IBLOCK_ID" => $IBLOCK_ID,
            "PROPERTY_VALUES" => $PROP,
            "NAME" => $data[3],
            "ACTIVE" => end($data) ? 'Y' : 'N',
        ];

        if ($PRODUCT_ID = $CIBlockElementObject->Add($newVacancy)) {
            echo "Добавлен элемент с ID : " . $PRODUCT_ID . "<br>";
        } else {
            echo "Error: " . $CIBlockElementObject->LAST_ERROR . '<br>';
        }

	}

    fclose($handle);
}
