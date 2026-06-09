<?php
use Bitrix\Main\Localization\Loc;
use Bitrix\Main\ModuleManager;
use Bitrix\Main\EventManager;

Loc::loadMessages(__FILE__);

class my_complexproperty extends CModule
{
    public $MODULE_ID = 'my.complexproperty'; 
    public $MODULE_VERSION = '1.0.0';
    public $MODULE_VERSION_DATE = '2026-06-06';
    public $MODULE_NAME = 'Комплексное свойство и Пользовательское поле';
    public $MODULE_DESCRIPTION = 'Добавляет комплексные свойства для инфоблоков и UF-полей с HTML-редактором';
    public $PARTNER_NAME = 'Irishk1c';
    public $PARTNER_URI = 'https://beget.tech';

    public function DoInstall()
    {
		ModuleManager::registerModule($this->MODULE_ID);
        
        EventManager::getInstance()->registerEventHandler(
            'iblock',
            'OnIBlockPropertyBuildList',
            $this->MODULE_ID,
            '\My\Complexproperty\ComplexProperty',
            'GetUserTypeDescription'
        );

        EventManager::getInstance()->registerEventHandler(
            'main',
            'OnUserTypeBuildList',
            $this->MODULE_ID,
            '\My\Complexproperty\ComplexUserField',
            'GetUserTypeDescription'
        );
    }

    public function DoUninstall()
    {
        EventManager::getInstance()->unRegisterEventHandler(
            'iblock',
            'OnIBlockPropertyBuildList',
            $this->MODULE_ID,
            '\My\Complexproperty\ComplexProperty',
            'GetUserTypeDescription'
        );

        EventManager::getInstance()->unRegisterEventHandler(
            'main',
            'OnUserTypeBuildList',
            $this->MODULE_ID,
            '\My\Complexproperty\ComplexUserField',
            'GetUserTypeDescription'
        );

        ModuleManager::unRegisterModule($this->MODULE_ID);
    }
}
