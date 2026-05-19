<?php

class my_logger extends CModule
{
    public $MODULE_ID = "my.logger"; 
    public $MODULE_NAME = "Модуль логирования";
    public $MODULE_DESCRIPTION = "Логирует изменения элементов инфоблоков в специальный лог.";

   
    function __construct()
    {
        $this->MODULE_ID = "my.logger";
        $this->MODULE_NAME = "Модуль логирования";
        $this->MODULE_DESCRIPTION = "Логирует изменения элементов инфоблоков";
    }

    
    function DoInstall()
    {
        RegisterModule($this->MODULE_ID);
    }

  
    function DoUninstall()
    {
        UnRegisterModule($this->MODULE_ID);
    }
}
