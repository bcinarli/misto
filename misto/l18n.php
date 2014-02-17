<?php
/**
 * @author Bilal Cinarli
 * @link http://bcinarli.com
 **/

class l18n {
	private static $_lang;

	public function __construct(){
		tools::inc_setting('lang');

		self::$_lang = $lang;
	}

	public static function lang(){
		return self::$_lang;
	}

	public static function translate($text)
	{
		if (isset(self::$_lang[$text]))
		{
			return self::$_lang[$text];
		}

		return $text;
	}
}