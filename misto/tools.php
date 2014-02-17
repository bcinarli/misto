<?php
/**
 * @author Bilal Cinarli
 * @link http://bcinarli.com
 **/

class tools {
	public function __construct(){

	}

	public static function inc($file, $dir = 'pages', $type = 'include'){
		$include = $file;
		$ext = self::extension($file);

		if(empty($ext)){
			$include .= '.php';
		}

		$path = ABS_PATH . 'app/' . $dir . '/' . $include;

		switch($type){
			default:
			case 'include':
				return include $path;
				break;
			case 'include_once':
				return include_once $path;
				break;
			case 'require':
				return require $path;
				break;
			case 'require_once':
				return require_once $path;
				break;
		}
	}

	public static function inc_setting($file){
		return self::inc($file, 'settings');
	}

	public static function extension($file)
	{
		$extension = '';
		$hasDot = strrpos($file, '.');

		if($hasDot > 0){
			$extension = substr($file, $hasDot + 1);
		}

		return $extension;
	}

	public static function params($defaults, $params)
	{
		$return = $defaults;

		if (!empty($params))
		{
			if(!is_array($params)){
				$options = array();
				$_params = explode('&', $params);
				foreach($_params as $value){
					$tempKey = explode("=", $value);
					$options[$tempKey[0]] = $tempKey[1];
				}

				$params = $options;
			}

			$return =  array_merge($defaults, $params);
		}

		return $return;
	}

	public static function is_serial($data)
	{
		$serial = false;

		if(isset($data)){
			$data = @unserialize($data);
			if($data == true){
				$serial = true;
			}
		}

		return $serial;
	}

	public static function slug($slug, $dir = true){
		$keysToRemove = array('"', "'", "‘", "’", "“", "”", "〃", '\\', '<', '>', '¡', '¢', '£', '¤', '¥', '¦', '§', '¨', '©', 'ª', '%', '«', '¬', '®', '™', '~', '¯', '°', '±', '²', '³', '´', '`', 'µ', '¶', '·', '¸', '¹', 'º', '»', '¼', '½', '¾', '¿', '×', '÷', '#', '…', '•', '†', '‡');
		$keysToSpace = array(",", "?", "!", "&", "(", ")", "{", "}", "[", "]", "=", ";", ":", "_", ".", "+", "*", "@", "^", "-", "–","—", "―");

		if($dir == true) {
			$keysToSpace[] = "/";
		}

		$slug = html_entity_decode($slug, ENT_QUOTES, 'UTF-8');
		$slug = str_replace($keysToRemove, "", $slug);
		$slug = str_replace($keysToSpace, " ", $slug);
		$slug = preg_replace('/\s+/', ' ', $slug);
		$slug = trim($slug);
		$slug = str_replace(array("&nbsp;", " "), "-", $slug);
		$slug = self::to_latin($slug);
		$slug = strtolower($slug);

		return $slug;
	}

	public static function to_latin($string)
	{
		$charTable = array('Â' => 'A', 'â' => 'a', 'À' => 'A', 'à' => 'a', 'Á' => 'A', 'á' => 'a', 'Ä' => 'A', 'ä' => 'a', 'Ã' => 'A', 'ã' => 'a', 'Æ' => 'A', 'æ' => 'a', 'Å' => 'A', 'å' => 'a', 'Þ' => 'B', 'þ' => 'b', 'ß' => 'Ss', 'Ç' => 'C', 'ç' => 'c', 'Č' => 'C', 'č' => 'c', 'Ć' => 'C', 'ć' => 'c', 'Œ' => 'CE', 'œ' => 'ce', 'Đ' => 'Dj', 'đ' => 'dj', 'È' => 'E', 'è' => 'e', 'É' => 'E', 'é' => 'e', 'Ê' => 'E', 'ê' => 'e', 'Ë' => 'E', 'ë' => 'e', 'Ğ' => 'G', 'ğ' => 'g', 'İ' => 'I', 'ı' => 'i', 'Ì' => 'I', 'ì' => 'i', 'Í' => 'I', 'í' => 'i', 'Î' => 'I', 'î' => 'i', 'Ï' => 'I', 'ï' => 'i', 'Ñ' => 'N', 'ñ' => 'n', 'Ò' => 'O', 'ò' => 'o', 'Ó' => 'O', 'ó' => 'o', 'Ô' => 'O', 'ô' => 'o', 'Õ' => 'O', 'õ' => 'o', 'Ö' => 'O', 'ö' => 'o', 'Ø' => 'O', 'ø' => 'o', 'ð' => 'o', 'Ŕ' => 'R', 'ŕ' => 'r', 'Š' => 'S', 'š' => 's', 'Ş' => 'S', 'ş' => 's', 'Ù' => 'U', 'ù' => 'u', 'Ú' => 'U', 'ú' => 'u', 'Û' => 'U', 'û' => 'u', 'Ü' => 'U', 'ü' => 'u', 'Ý' => 'Y', 'ý' => 'y', 'ÿ' => 'y', 'Ž' => 'Z', 'ž' => 'z');
		return strtr($string, $charTable);
	}
}