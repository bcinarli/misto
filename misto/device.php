<?php

/**
 * @author Bilal Cinarli
 * @link http://bcinarli.com
 **/

use \Mobile_Detect\Mobile_Detect;

class device extends Mobile_Detect
{
    private static $_device;
    private static $_isMobile;
    private static $_isTablet;

    public function __construct()
    {
        parent::__construct();
        
        self::$_isMobile = parent::isMobile();
        self::$_isTablet = parent::isTablet();
    }

    public static function is_Mobile()
    {
        return self::$_isMobile;
    }

    public static function is_Tablet()
    {
        return self::$_isTablet;
    }
    
    public static function is_Phone()
    {
        return self::$_isMobile && !self::$_isTablet;
    }
}
