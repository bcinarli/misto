<?php

/**
 * @author Bilal Cinarli
 * @link http://bcinarli.com
 **/
class misto
{
	public function __construct()
	{
		new url();
		new router();

		ini_set('session.cookie_domain', '.' . url::getPlainHost());
		session_start();

		if (Authentication === true) {
			$this->force_directory_authentication();
		}

		if (role::is_404() === true) {
			header("HTTP/1.0 404 Not Found");
		}

		tools::inc(router::getRoute(), '', 'require_once');
	}

	public function force_directory_authentication()
	{
		if (empty($_SESSION['auth_user']) || $_SESSION['auth_user'] !== true) {
			if (empty($_SERVER['PHP_AUTH_USER'])) {
				header('WWW-Authenticate: Basic realm="' . Realm . '"');
				header('HTTP/1.0 401 Unauthorized');

				die('Website access is password protected!');
			}

			if (sha1($_SERVER['PHP_AUTH_PW']) != Authentication_Pass && $_SERVER['PHP_AUTH_USER'] != Authentication_User) {
				die('Wrong Credentials!');
			} else {
				$_SESSION['auth_user'] = true;
			}
		}
	}
}