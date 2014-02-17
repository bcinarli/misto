<?php

    define('ABS_PATH', dirname(__file__) . '/');
    include(ABS_PATH . 'app/settings/settings.php'); 

    function __autoload($class)
    {
        require_once (ABS_PATH . 'misto/' . strtolower($class) . '.php');
    }

    new misto;