<?php

/**
 * @author Bilal Cinarli
 * @link http://bcinarli.com
 **/
class router
{
	private static $_routes;
	private static $_routeMatch = false;
	private static $_page;
	private static $_matches;
	private static $_role = '404';
	public static $is_404 = true;

	public function __construct()
	{
		require_once(ABS_PATH . 'app/settings/routes.php');

		self::$_page = $routes['404']['page'];
		$this->setRoutes($routes);
	}

	public function setRoutes(array $routes)
	{
		if (is_array($routes)) {
			self::$_routes = $routes;
			$this->analyseRoutes();
		}
	}

	public function analyseRoutes()
	{
		foreach (self::$_routes as $route) {
			$match = 'plain';
			if (isset($route['match'])) {
				$match = $route['match'];
			}

			switch ($match) {
				case 'plain':
				default:
					$this->checkPlainRoute($route['url']);
					break;
				case 'regex':
					$this->checkRegexRoute($route['url']);
					break;
			}

			if (self::$_routeMatch === true) {
				self::$is_404 = false;
				$this->setRole($route);
				$this->setPage($route);
				break;
			}
		}
	}

	private function setPage($route)
	{
		$page = $route['page'];

		if (isset($route['match']) && $route['match'] == 'regex') {
			$matches = self::getMatches();

			// loop matches and replace $1, $2 like placeholders with matched vars
			foreach ($matches as $key => $value) {
				$page = str_replace('$' . $key, $value, $page);
			}

			// if url matched, and page if is not exists, set page to 404
			if (!file_exists(ABS_PATH . 'app' . $page)) {
				$page         = $this->_routes[404]['page'];
				self::$is_404 = true;
			}
		}

		return self::$_page = $page;
	}

	private function setRole($route)
	{
		if (!isset($route['role'])) {
			self::$_role = 'role';
		} else {
			self::$_role = $route['role'];
		}
	}

	private function checkPlainRoute($url)
	{
		self::$_routeMatch = false;
		if ($url == url::getUrl()) {
			self::$_routeMatch = true;
		}
	}

	private function checkRegexRoute($url)
	{
		self::$_routeMatch = false;

		if (preg_match('#' . $url . '#', url::getUrl(), $matches)) {
			self::$_matches    = $matches;
			self::$_routeMatch = true;
		}
	}

	public static function setRoute($route)
	{
		if (array_key_exists('page', self::$_routes[$route])) {
			return self::$_page = self::$_routes[$route]['page'];
		}
	}

	public static function getRoute()
	{
		return self::$_page;
	}

	public static function getRole()
	{
		return self::$_role;
	}

	public static function getMatches($matched = null)
	{
		if ($matched == null) {
			return self::$_matches;
		}

		return self::$_matches[$matched];
	}
}
