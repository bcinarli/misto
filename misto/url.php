<?php

/**
 * @author Bilal Cinarli
 * @link http://bcinarli.com
 **/
class url
{
	public static $path = '/';
	private static $_isSSL = false;
	private static $_isLocal = false;
	private static $_host;
	private static $path_dir = '';
	private static $_subdomain = null;
	private static $_url;
	public static $plainhost;

	public function __construct()
	{
		if (!empty($_SERVER['HTTPS'])) {
			if ($_SERVER['HTTPS'] == 'on') {
				$this->_isSSL = true;
			}
		}

		self::$_host = $_SERVER['HTTP_HOST'];

		$this->findPath();
		$this->plainHost();
		$this->findSubdomain();
		$this->getUrl();
	}

	private function findPath()
	{
		$script_name = $_SERVER['SCRIPT_NAME'];

		$script_path = str_replace('/index.php', '', $script_name);

		if ($script_path != self::$path) {
			self::$path = $script_path;
		}
	}

	private function plainHost()
	{
		if (in_array(self::$_host, array('127.0.0.1', 'localhost'))) {
			self::$plainhost = self::$_host;
			self::$_isLocal  = true;

			return;
		}

		$host = explode('.', self::$_host);

		if (count($host) == 2) {
			self::$plainhost = self::$_host;
		} else {
			self::$plainhost = str_replace(self::$_subdomain . '.', '', self::$_host);
		}
	}

	public static function getPlainHost()
	{
		return self::$plainhost;
	}

	public static function isLocal()
	{
		return self::$_isLocal;
	}

	private function findSubdomain()
	{
		if (self::$_isLocal == false) {
			$host = explode('.', self::$_host);

			if (count($host) == 2) {
				self::$_subdomain = 'www';
			} else {
				self::$_subdomain = $host[0];
			}
		}
	}

	public static function getUrl()
	{
		$http = 'http';
		if (self::$_isSSL == true) {
			$http .= 's';
		}

		$h = $http . '://' . self::$_host;

		if (self::$_isLocal == true || self::$path != '/') {
			$h = self::$path;
		}

		self::$_url = str_replace($h, '', trim($_SERVER['REQUEST_URI']));
		self::$_url = str_replace('?' . $_SERVER['QUERY_STRING'], '', self::$_url);

		return self::$_url;
	}
	
	public static function getPath(){
		return self::$path;
	}

	public static function getHost()
	{
		if (self::$_isLocal == true) {
			return self::$_host . self::$path;
		}

		return self::$_host;
	}

	public static function getSubdomain()
	{
		return self::$_subdomain;
	}

	public static function base_url($dir = '')
	{
		$base = str_replace('//', '/', self::$path . '/');

		$file  = false;
		$check = explode('.', $dir);
		if (count($check) >= 2) {
			$file = true;
		}

		if ($dir != '') {
			$base .= $dir;
		}

		if ($file === false) {
			$base .= '/';
		}

		$base = str_replace('//', '/', $base);

		return $base;
	}

	public static function app_url($dir = '')
	{
		return self::base_url(APP_PATH . $dir);
	}

	public static function assets_url($dir = '')
	{
		return self::base_url('assets/' . $dir);
	}

	public static function homepage()
	{
		return str_replace('//', '/', self::$path);
	}

	public static function styles($file = '')
	{
		return self::interface_url('/styles/' . $file);
	}

	public static function interface_url($dir = '')
	{
		return self::app_url('assets' . $dir);
	}

	public static function images($file = '')
	{
		return self::interface_url('/images/' . $file);
	}

	public static function scripts($file = '')
	{
		return self::interface_url('/scripts/' . $file);
	}

	public static function placeholders($file = '')
	{
		return self::app_url('placeholders/' . $file);
	}

	public static function content($file = '')
	{
		return self::app_url('content/' . $file);
	}

	public static function make_url($path)
	{
		return self::homepage() . '/' . tools::slug($path, false);
	}

	public static function make_abs_url($path)
	{
		$http = 'http';
		if (self::$_isSSL == true) {
			$http .= 's';
		}

		return $http . '://' . url::getHost() . url::make_url($path);
	}
}
