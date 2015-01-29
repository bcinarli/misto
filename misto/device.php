<?php

/**
 * @author Bilal Cinarli
 * @link http://bcinarli.com
 **/

use \Mobile_Detect\Mobile_Detect;

class device
{
    private static $_device;

    public function __construct()
    {
        self::$_device = new Mobile_Detect;
    }

    public static function is($key)
    {
        self::$_device->is($key);
    }

    public static function isMobile()
    {
        self::$_device->isMobile();
    }

    public static function isTablet()
    {
        self::$_device->isTablet();
    }
}