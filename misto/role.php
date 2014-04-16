<?php

/**
 * @author Bilal Cinarli
 * @link http://bcinarli.com
 **/
class role
{
	/**
	 * if $role is null, returns the current role
	 * otherwise checks the role and returns if its true or false
	 * @param null $role
	 * @return bool|string
	 */
	public static function is($role = null)
	{
		if ($role == null) {
			return router::getRole();
		}

		if (router::getRole() == $role) {
			return true;
		}

		return false;
	}

	public static function is_homepage()
	{
		return self::is('homepage');
	}

	public static function is_page()
	{
		return self::is('page');
	}

	public static function is_404()
	{
		return self::is('404');
	}
} 