<?php
namespace My\ComplexProperty;

class ComplexUserField
{
    public static function GetUserTypeDescription(): array
    {
        return [
            "USER_TYPE_ID" => "my_complex_user_field",
            "CLASS_NAME"   => __CLASS__,
            "DESCRIPTION"  => "Комплексное свойство (динамическое)",
            "BASE_TYPE"    => "string",
        ];
    }


    public static function CheckFields($arUserField, $value): array
    {
        return [];
    }

    public static function GetDBColumnType($arUserField): string
    {
        return "text";
    }

    public static function GetSettingsHTML($arUserField, $arHtmlControl, $bVarsFromForm): string 
    { 
        ini_set('display_errors', 1); error_reporting(E_ALL);

        $controlName = $arHtmlControl["NAME"];

        $savedFields = [];
        
        if ($bVarsFromForm && isset($_POST['USER_TYPE_SETTINGS']['FIELDS'])) {
            $savedFields = $_POST['USER_TYPE_SETTINGS']['FIELDS'];
        } elseif (!empty($arUserField["SETTINGS"]["FIELDS"]) && is_array($arUserField["SETTINGS"]["FIELDS"])) {
            $savedFields = $arUserField["SETTINGS"]["FIELDS"];
        }

        $arFieldsTable = '';

        if (!empty($savedFields)) {
            foreach ($savedFields as $fieldId => $arField) {
     
                if (empty($arField["CODE"])) continue;

                $baseName = $controlName . '[FIELDS][' . htmlspecialcharsbx($fieldId) . ']';
                
                $selectedString = (($arField["TYPE"] ?? "string") === "string") ? "selected" : "";
                $selectedHtml = (($arField["TYPE"] ?? "") === "html") ? "selected" : "";

                $arFieldsTable .= '
                <tr class="complex-field-row">
                    <td><input type="text" name="' . $baseName . '[CODE]" value="' . htmlspecialcharsbx($arField["CODE"]) . '" style="width:90%;"></td>
                    <td><input type="text" name="' . $baseName . '[TITLE]" value="' . htmlspecialcharsbx($arField["TITLE"]) . '" style="width:90%;"></td>
                    <td>
                        <select name="' . $baseName . '[TYPE]" style="width:90%;">
                            <option value="string" ' . $selectedString . '>Строка (string)</option>
                            <option value="html" ' . $selectedHtml . '>HTML-редактор (html)</option>
                        </select>
                    </td>
                    <td align="center"><input type="button" value="Удалить" onclick="deleteComplexField(this)"></td>
                </tr>';
            }
        }
        
        if (empty($arFieldsTable)) {
            $baseName = $controlName . '[FIELDS][field_0]';
            $arFieldsTable = '
            <tr class="complex-field-row">
                <td><input type="text" name="' . $baseName . '[CODE]" value="" placeholder="Например: SUB_TITLE" style="width:90%;"></td>
                <td><input type="text" name="' . $baseName . '[TITLE]" value="" placeholder="Например: Подзаголовок" style="width:90%;"></td>
                <td>
                    <select name="' . $baseName . '[TYPE]" style="width:90%;">
                        <option value="string" selected>Строка (string)</option>
                        <option value="html">HTML-редактор (html)</option>
                    </select>
                </td>
                <td align="center"><input type="button" value="Удалить" onclick="deleteComplexField(this)"></td>
            </tr>';
        }

        $html = '
        <tr class="heading" id="complex_fields_heading">
            <td colspan="2">Настройка подполей комплексного свойства</td>
        </tr>
        <tr valign="top">
            <td class="adm-detail-valign-top" style="width: 40%;">Структура комплексного поля:</td>
            <td>
                <table id="complex_fields_table" style="width: 100%; border-spacing: 0 5px;">
                    <thead>
                        <tr style="text-align: left; color: #535c69; font-weight: bold;">
                            <td style="width: 30%; padding-bottom: 5px;">Код поля (латиница)</td>
                            <td style="width: 40%; padding-bottom: 5px;">Название (Placeholder)</td>
                            <td style="width: 20%; padding-bottom: 5px;">Тип поля</td>
                            <td style="width: 10%; padding-bottom: 5px; text-align: center;">Действие</td>
                        </tr>
                    </thead>
                    <tbody>
                        ' . $arFieldsTable . '
                    </tbody>
                </table>
                <input type="button" value="Добавить поле" onclick="window.addComplexFieldRow()" style="margin-top: 10px;">
                
                <script>
                
                window.addComplexFieldRow = function() {
                    var tbody = document.getElementById("complex_fields_table").getElementsByTagName("tbody")[0];
                    var currentIndex = tbody.getElementsByTagName("tr").length;
                    var nameAttr = "' . $controlName . '";
                    
                    var newRow = document.createElement("tr");
                    newRow.className = "complex-field-row";
                    newRow.innerHTML = `
                        <td><input type="text" name="${nameAttr}[FIELDS][field_${currentIndex}][CODE]" placeholder="Например: SUB_TITLE" style="width:90%;"></td>
                        <td><input type="text" name="${nameAttr}[FIELDS][field_${currentIndex}][TITLE]" placeholder="Например: Подзаголовок" style="width:90%;"></td>
                        <td>
                            <select name="${nameAttr}[FIELDS][field_${currentIndex}][TYPE]" style="width:90%;">
                                <option value="string" selected>Строка (string)</option>
                                <option value="html">HTML-редактор (html)</option>
                            </select>
                        </td>
                        <td align="center"><input type="button" value="Удалить" onclick="deleteComplexField(this)"></td>
                    `;
                    tbody.appendChild(newRow);
                };

                window.deleteComplexField = function(btn) {
                    var row = btn.parentNode.parentNode;
                    var tbody = row.parentNode;
                    if (tbody.getElementsByTagName("tr").length > 1) {
                        row.parentNode.removeChild(row);
                    } else {
                        var inputs = row.getElementsByTagName("input");
                        for(var i=0; i<inputs.length; i++) {
                            if(inputs[i].type === "text") inputs[i].value = "";
                        }
                    }
                };

                (function() {
                    var rows = document.querySelectorAll(".adm-detail-content-table tr");
                    rows.forEach(function(row) {
                        var labelCell = row.querySelector("td");
                        if (labelCell) {
                            var text = labelCell.innerText;
                            if (text.includes("Длина строки") || text.includes("Значение по умолчанию") || text.includes("Количество строк") || text.includes("Размер поля ввода")) {
                                row.style.display = "none";
                            }
                        }
                    });
                })();
                </script>
            </td>
        </tr>';

        return $html;
    }


    public static function PrepareSettings($arUserField): array
    {
        $fields = [];
        
        if (isset($arUserField["SETTINGS"]["FIELDS"]) && is_array($arUserField["SETTINGS"]["FIELDS"])) {
            foreach ($arUserField["SETTINGS"]["FIELDS"] as $arField) {
                
                $code = isset($arField["CODE"]) ? strtoupper(trim((string)$arField["CODE"])) : "";
                
                if ($code === "") {
                    continue; 
                }

                $fields[$code] = [
                    "CODE"  => $code,
                    "TITLE" => isset($arField["TITLE"]) ? trim((string)$arField["TITLE"]) : $code,
                    "TYPE"  => (isset($arField["TYPE"]) && $arField["TYPE"] === "html") ? "html" : "string",
                ];
            }
        } 
    
        elseif (isset($arUserField["FIELDS"]) && is_array($arUserField["FIELDS"])) {
            $fields = $arUserField["FIELDS"];
        }

		if (empty($fields)) {
    		return [];
		}

       return [
    		"FIELDS" => $fields
		];
		
    }

    public static function GetEditFormHTML($arUserField, $arHtmlControl): string
    {
        $fields = [];
        if (!empty($arUserField["SETTINGS"]["FIELDS"]) && is_array($arUserField["SETTINGS"]["FIELDS"])) {
            $fields = $arUserField["SETTINGS"]["FIELDS"];
        } elseif (!empty($arUserField["FIELDS"]) && is_array($arUserField["FIELDS"])) {
            $fields = $arUserField["FIELDS"];
        }

        if (empty($fields)) {
            return '<div style="color:red; background:#ffebeb; padding:10px; border-radius:4px;">Внимание: Настройки подполей комплексного свойства не заданы!</div>';
        }

        if (!\Bitrix\Main\Loader::includeModule("fileman")) {
            return '<div style="color:red;">Ошибка: Модуль fileman не установлен!</div>';
        }

        $currentValues = [];
        if (!empty($arHtmlControl["VALUE"])) {
            $currentValues = is_array($arHtmlControl["VALUE"]) 
                ? $arHtmlControl["VALUE"] 
                : json_decode($arHtmlControl["VALUE"], true);
            
            if (!is_array($currentValues)) {
                $currentValues = [];
            }
        }

        $controlName = $arHtmlControl["NAME"]; 

        $html = '<div class="complex-user-field-container" style="width: 100%; max-width: 800px; border: 1px solid #e0e4e5; padding: 15px; border-radius: 4px; background: #fcfcfc;">';

        foreach ($fields as $code => $arField) {
            $fieldValue = isset($currentValues[$code]) ? $currentValues[$code] : '';
            
            $fieldName = $controlName . '[' . htmlspecialcharsbx($code) . ']';

            $html .= '<div class="complex-field-block" style="margin-bottom: 15px; display: flex; flex-direction: column; gap: 5px;">';
            $html .= '<label style="font-weight: bold; color: #4c535c; font-size: 13px;">' . htmlspecialcharsbx($arField["TITLE"]) . ':</label>';
            $html .= '<div>';

            if (($arField["TYPE"] ?? "string") === "html") {
                ob_start();
                
                \CFileMan::AddHTMLEditorFrame(
                    $fieldName,
                    $fieldValue,
                    $fieldName . '_TYPE',
                    'html',
                    ['height' => 160, 'width' => '100%'],
                    'N', 0, '', '', false, true, false,
                    [
                        'hideTypeSelector' => 'Y',
                        'controlsMap' => [
                            ['id' => 'Bold', 'compact' => true, 'sort' => 10],
                            ['id' => 'Italic', 'compact' => true, 'sort' => 20],
                            ['id' => 'Underline', 'compact' => true, 'sort' => 30],
                            ['id' => 'Strikeout', 'compact' => true, 'sort' => 40],
                            ['id' => 'RemoveFormat', 'compact' => true, 'sort' => 50],
                            ['id' => 'Color', 'compact' => true, 'sort' => 60],
                            ['id' => 'FontSelector', 'compact' => false, 'sort' => 70],
                            ['id' => 'FontSize', 'compact' => false, 'sort' => 80],
                            ['id' => 'HeadingList', 'compact' => false, 'sort' => 90],
                            ['id' => 'OrderedList', 'compact' => true, 'sort' => 100],
                            ['id' => 'UnorderedList', 'compact' => true, 'sort' => 110],
                            ['id' => 'AlignList', 'compact' => false, 'sort' => 120],
                            ['id' => 'InsertLink', 'compact' => true, 'sort' => 130],
                            ['id' => 'InsertImage', 'compact' => true, 'sort' => 140],
                            ['id' => 'Source', 'compact' => true, 'sort' => 150], // Кнопка "Исходный код"
                        ]
                    ]
                );
                
                $html .= ob_get_clean();
            } 
            else {
                $html .= '<input type="text" name="' . $fieldName . '" value="' . htmlspecialcharsbx($fieldValue) . '" style="width: 100%; padding: 6px; border: 1px solid #c6d1d6; border-radius: 3px; box-sizing: border-box;">';
            }

            $html .= '</div>';
            $html .= '</div>';
        }

        $html .= '</div>';

        return $html;
    }


    public static function OnBeforeSave($arUserField, $value)
    {
        if (is_array($value)) {
            $hasData = false;
            foreach ($value as $val) {
                if (is_array($val)) {
                    if (!empty(trim((string)($val['TEXT'] ?? '')))) $hasData = true;
                } else {
                    if (!empty(trim((string)$val))) $hasData = true;
                }
            }
            
            if (!$hasData) {
                return '';
            }
            
            return json_encode($value, JSON_UNESCAPED_UNICODE);
        }
        return $value;
    }

    public static function OnAfterFetch($arUserField, $value)
    {
        if (is_array($value)) {
            return $value;
        }

        if (empty($value)) {
            return [];
        }

        $decoded = json_decode($value, true);
		
        return is_array($decoded) ? $decoded : [];
    }

}
