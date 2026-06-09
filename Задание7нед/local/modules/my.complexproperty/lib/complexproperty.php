<?php
namespace My\Complexproperty;

class ComplexProperty
{
    public static function GetUserTypeDescription(): array
    {
        return [
            'PROPERTY_TYPE' => 'S', 
            'USER_TYPE' => 'my_complex_prop', 
            'DESCRIPTION' => 'Комплексное свойство (динамическое)', 
            'GetSettingsHTML' => [__CLASS__, 'GetSettingsHTML'],
            'PrepareSettings' => [__CLASS__, 'PrepareSettings'],
            'GetEditFormHTML' => [__CLASS__, 'GetEditFormHTML'],
			'ConvertToDB' => [__CLASS__, 'ConvertToDB'],
            'ConvertFromDB' => [__CLASS__, 'ConvertFromDB'],
        ];
    }

    public static function GetSettingsHTML($arProperty, $strHTMLControlName, &$arPropertyFields): string 
    { 
        ini_set('display_errors', 1); error_reporting(E_ALL);

        $arPropertyFields = [
            "HIDE" => ["ROW_COUNT", "COL_COUNT", "MULTIPLE_CNT", "DEFAULT_VALUE", "WITH_DESCRIPTION", "SEARCHABLE", "FILTRABLE"],
            "USER_TYPE_SETTINGS_TITLE" => "Настройка подполей комплексного свойства"
        ];

        		
        $controlName = $strHTMLControlName["NAME"];
        
        $savedFields = [];
        
        if (!empty($arProperty["USER_TYPE_SETTINGS"]["FIELDS"]) && is_array($arProperty["USER_TYPE_SETTINGS"]["FIELDS"])) {
            $savedFields = $arProperty["USER_TYPE_SETTINGS"]["FIELDS"];
        } elseif (!empty($arProperty["USER_TYPE_SETTINGS"]) && is_array($arProperty["USER_TYPE_SETTINGS"]) && !empty($arProperty["USER_TYPE_SETTINGS"]["FIELDS"])) {
            $savedFields = $arProperty["USER_TYPE_SETTINGS"]["FIELDS"];
        } elseif (!empty($arProperty["FIELDS"]) && is_array($arProperty["FIELDS"])) {
            $savedFields = $arProperty["FIELDS"];
        }

        $arFieldsTable = '';

        if (!empty($savedFields)) {
            foreach ($savedFields as $fieldId => $arField) {
               
                $baseName = $controlName . '[FIELDS][' . htmlspecialcharsbx($fieldId) . ']';
                
                
                $selectedString = ($arField["TYPE"] === "string") ? "selected" : "";
                $selectedHtml = ($arField["TYPE"] === "html") ? "selected" : "";

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
        } else {
            
            $baseName = $controlName . '[FIELDS][tmp_0]';
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

        $templateRow = '
        <tr class="complex-field-row">
            <td><input type="text" name="' . $controlName . '[FIELDS][###ID###][CODE]" placeholder="Например: SUB_TITLE" style="width:90%;"></td>
            <td><input type="text" name="' . $controlName . '[FIELDS][###ID###][TITLE]" placeholder="Например: Подзаголовок" style="width:90%;"></td>
            <td>
                <select name="' . $controlName . '[FIELDS][###ID###][TYPE]" style="width:90%;">
                    <option value="string" selected>Строка (string)</option>
                    <option value="html">HTML-редактор (html)</option>
                </select>
            </td>
            <td align="center"><input type="button" value="Удалить" onclick="deleteComplexField(this)"></td>
        </tr>';
        $templateRow = str_replace(["\r", "\n"], "", $templateRow); 

        // JAVASCRIPT 
		
        $jsTemplate = '
        <table style="display:none;">
            <tbody id="my-complex-row-template">' . $templateRow . '</tbody>
        </table>
        <script>
        function addNewComplexField(controlName) {
            var table = document.getElementById("my-complex-fields-table");
            if (!table) return;
            var templateHtml = document.getElementById("my-complex-row-template").innerHTML;
            var uniqueId = "tmp_" + Date.now(); 
            var newRowHtml = templateHtml.replace(/###ID###/g, uniqueId);
            var placeholder = document.createElement("tbody");
            placeholder.innerHTML = newRowHtml;
            table.appendChild(placeholder.firstElementChild);
        }
        function deleteComplexField(button) {
            var row = button.closest(".complex-field-row");
            var table = document.getElementById("my-complex-fields-table");
            if (!table || !row) return;
            var rowCount = table.getElementsByClassName("complex-field-row").length;
            
            if (rowCount > 1) { row.remove(); } else { alert("Должно остаться хотя бы одно подполе!"); }
        }
        </script>';

        return '
        <tr>
            <td colspan="2" align="center">
                <table id="my-complex-fields-table" class="internal" style="width: 100%; border-collapse: collapse;">
                    <tr class="heading">
                        <td>Символьный код (латиница)</td>
                        <td>Название подполя</td>
                        <td>Тип поля</td>
                        <td>Действие</td>
                    </tr>
                    ' . $arFieldsTable . '
                </table>
                <div style="padding-top: 10px; text-align: left;">
                    <input type="button" value="+ Добавить подполе" onclick="addNewComplexField(\'' . $controlName . '\')">
                </div>
                ' . $jsTemplate . '
            </td>
        </tr>';
    }


    public static function PrepareSettings($arProperty): array
    {
        $fields = [];
        
        if (isset($arProperty["USER_TYPE_SETTINGS"]["FIELDS"]) && is_array($arProperty["USER_TYPE_SETTINGS"]["FIELDS"])) {
            foreach ($arProperty["USER_TYPE_SETTINGS"]["FIELDS"] as $key => $arField) {
               
                $code = strtoupper(trim($arField["CODE"]));
   
                if ($code === "") {
                    continue; 
                }

                $fields[$code] = [
                    "CODE"  => $code,
                    "TITLE" => trim($arField["TITLE"]) ?: $code, 
                    "TYPE"  => ($arField["TYPE"] === "html") ? "html" : "string", 
                ];
            }
        } 
        
        elseif (isset($arProperty["FIELDS"]) && is_array($arProperty["FIELDS"])) {
            $fields = $arProperty["FIELDS"];
        }

        return [
            "FIELDS" => $fields
        ];
    }


    public static function GetEditFormHTML($arProperty, $value, $strHTMLControlName): string
    {

        $fields = [];
        
        if (!empty($arProperty["USER_TYPE_SETTINGS"]["FIELDS"]) && is_array($arProperty["USER_TYPE_SETTINGS"]["FIELDS"])) {
            $fields = $arProperty["USER_TYPE_SETTINGS"]["FIELDS"];
        } elseif (!empty($arProperty["USER_TYPE_SETTINGS"]) && is_array($arProperty["USER_TYPE_SETTINGS"]) && !empty($arProperty["USER_TYPE_SETTINGS"]["FIELDS"])) {
            $fields = $arProperty["USER_TYPE_SETTINGS"]["FIELDS"];
        } elseif (!empty($arProperty["FIELDS"]) && is_array($arProperty["FIELDS"])) {
            $fields = $arProperty["FIELDS"];
        }

        if (empty($fields)) {
            return '<tr><td colspan="2" style="color:red; background:#ffebeb; padding:10px;">Внимание: Настройки подполей комплексного свойства не заданы!</td></tr>';
        }

        if (!\CModule::IncludeModule("fileman")) {
            return '<tr><td colspan="2" style="color:red;">Ошибка: Модуль fileman не установлен!</td></tr>';
        }

        $currentValues = is_array($value["VALUE"]) ? $value["VALUE"] : [];
        $controlName = $strHTMLControlName["VALUE"]; 

        $html = '<tr><td colspan="2" style="padding: 10px 0;"><table style="width: 100%; border-spacing: 0 8px; border-collapse: separate;">';

        foreach ($fields as $code => $arField) {
            $fieldValue = isset($currentValues[$code]) ? $currentValues[$code] : '';
            $fieldName = $controlName . '[' . htmlspecialcharsbx($code) . ']';

            $html .= '<tr>';
            $html .= '<td style="vertical-align: top; width: 160px; padding-top: 6px; font-weight: bold; color: #4b6267;">' . htmlspecialcharsbx($arField["TITLE"]) . ':</td>';
            $html .= '<td>';

            if ($arField["TYPE"] === "html") {
                ob_start();
                
                $editor = new \CHTMLEditor;
                $editor->Show([
                    'name' => $fieldName,
                    'id' => preg_replace('/[^a-zA-Z0-9_]/', '_', $fieldName) . '_' . $code, 
                    'inputName' => $fieldName,
                    'content' => $fieldValue,
                    'height' => 160,
                    'width' => '100%',
                    'minBodyWidth' => 350,
                    'normalBodyWidth' => 555,
                    'bAllowPhp' => false,
                    'limitPhpAccess' => true,
                    'showTaskbars' => false,
                    'showNodeNavi' => false,
                    'askBeforeUnloadPage' => false,
                    'bbCode' => false,
                    'siteId' => SITE_ID,
                    'autoResize' => true,
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
                    ],
                ]);
                
                $html .= ob_get_clean();
            } 
            else {
                $html .= '<input type="text" name="' . $fieldName . '" value="' . htmlspecialcharsbx($fieldValue) . '" style="width: 100%; max-width: 650px; height: 26px;">';
            }

            $html .= '</td>';
            $html .= '</tr>';
        }

        $html .= '</table></td></tr>';

        return $html;
    }

    
    public static function ConvertToDB($arProperty, $value)
    {
        if (isset($value["VALUE"]) && is_array($value["VALUE"])) {
            
            $hasData = false;
            foreach ($value["VALUE"] as $val) {
                if (trim($val) !== '') {
                    $hasData = true;
                    break;
                }
            }

            if ($hasData) {
                $value["VALUE"] = json_encode($value["VALUE"], JSON_UNESCAPED_UNICODE);
            } else {
                $value["VALUE"] = ""; 
            }
        }

        return $value;
    }


    public static function ConvertFromDB($arProperty, $value)
    {
        if (isset($value["VALUE"]) && !is_array($value["VALUE"]) && !empty($value["VALUE"])) {
            
            $decoded = json_decode($value["VALUE"], true);
            
            if (is_array($decoded)) {
                $value["VALUE"] = $decoded; 
            }
        }
        
        return $value;
    }

}
