<?php

/**
 * @author Bilal Cinarli
 * @link http://bcinarli.com
 **/
class auth
{
	private $auth = false;
	private $_type = 'guest';
	private $_username = '';

	public $login_username;
	public $login_password;


	public function __construct()
	{
		if (isset($_GET['auth']) && $_GET['auth'] == true) {
			$_SESSION['auth']     = true;
			$_SESSION['type']     = 'user';
			$_SESSION['username'] = 'iconozan';
			$this->_username      = 'iconozan';
		}

		if (isset($_GET['auth']) && $_GET['auth'] == 'logout') {
			$_SESSION['auth']     = false;
			$_SESSION['type']     = 'guest';
			$_SESSION['username'] = '';
			$this->_username      = '';
		}

		self::set_type();
	}

	public function logged()
	{
		if (isset($_SESSION['auth']) && $_SESSION['auth'] == true) {
			return true;
		}

		return false;
	}

	private function set_type()
	{
		$this->_type = 'guest';
		if (isset($_SESSION['type']) && !empty($_SESSION['type'])) {
			$this->_type = $_SESSION['type'];
		}
	}

	public function type()
	{
		return $this->_type;
	}

	public function username()
	{
		return $_SESSION['username'];
	}

	public function login()
	{
		if (isset($_POST) && !empty($_POST['login']) && $_POST['login'] == 'login') {

			$_SESSION['auth']     = true;
			$_SESSION['type']     = $_POST['type'];
			$_SESSION['username'] = $_POST['username'];

			$this->_username = $_POST['username'];

			header("Location: http://" . router::getHost() . '/profil');
			exit;
		}
	}

	public function needLogin()
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