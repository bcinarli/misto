<?php
    define('ABS_PATH', dirname(__file__) . '/');
    include(ABS_PATH . 'settings.php');

    function __autoload($class)
    {
	    $classname = strtolower($class);
	    $namespaced = ABS_PATH . str_replace('\\', '/', $class) . '.php';
	    $vendornamespaced = ABS_PATH . 'vendors/' . str_replace('\\', '/', $class) . '.php';
	    $customClass = ABS_PATH . APP_PATH . 'classes/' . $classname . '.php';
	    $mistoClass = ABS_PATH . 'misto/' . $classname . '.php';

	    // namespaced classes
	    if(file_exists($namespaced)){
		    require_once $namespaced;
	    }

	    // vendor namespaced clasess
	    if(file_exists($vendornamespaced)){
		    require_once $vendornamespaced;
	    }

	    // classic classes
	    else if(file_exists($customClass)){
		    require_once $customClass;
	    }

	    // misto classes
	    else if(file_exists($mistoClass)){
		    require_once $mistoClass;
	    }
    }

    new misto;