<?php

use Bitrix\Main\Loader;
use Bitrix\Main\EventManager;

//if (Loader::includeModule('my.logger')) {

if (file_exists($_SERVER['DOCUMENT_ROOT'] . '/local/modules/my.logger/lib/handler.php')) {
    require_once $_SERVER['DOCUMENT_ROOT'] . '/local/modules/my.logger/lib/handler.php';
}
    $eventManager = EventManager::getInstance();

    $eventManager->addEventHandler(
      'iblock',
      'OnAfterIBlockElementAdd',
      ['\My\Logger\Handler', 'logElementChange']
    );

    $eventManager->addEventHandler(
        'iblock', 
        'OnAfterIBlockElementUpdate', 
        ['\My\Logger\Handler', 'logElementChange']
    );
	//}
