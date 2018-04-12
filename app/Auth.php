<?php

namespace app;

use User;

use RememberedLogin;

class Auth
{

	public function __construct() {

	}

	public static function login ($user, $remember_me)
	{
		
		session_regenerate_id(true);

		$_SESSION['user_id'] = $user->id;

		if ($remember_me) {

			if ($user->rememberLogin()) {
				setcookie('remember_me', $user->remember_token, $user->expiry_timestamp, '/');
			}
		}
	}

	public static function logout()
	{
		session_start();

		$_SESSION = [];

		if (ini_get("session.use_cookies")) {
			$params = session_get_cookie_params();
    		setcookie(session_name(), '', time() - 42000, $params["path"], $params["domain"], $params["secure"], $params["httponly"]);
		}
		session_destroy();

		static::forgetLogin();
	}

	public static function isLoggedIn()
	{	session_start();
		return isset($_SESSION['user_id']);
	}

	public static function rememberRequestedPage()
	{
		session_start();
		$_SESSION['return_to'] = '../..' . $_SERVER['REQUEST_URI'];
	}

	public static function getReturnToPage()
	{
		session_start();
		return $_SESSION['return_to'] ?? 'homepage/index';
	}

	public static function getUser()
	{
		if (isset($_SESSION['user_id'])) {
			return User::findByID($_SESSION['user_id']);
		} else {

			return static::loginFromRememberCookie();
		}
	}

	protected static function loginFromRememberCookie()
	{
		$cookie = $_COOKIE['remember_me'] ?? false;

		if ($cookie) {
			$remembered_login = RememberedLogin::findByToken($cookie);

			foreach($remembered_login as $value) {
					$rl = $value;
				}
			if (isset($rl)) {
			if ($rl && ! $rl->hasExpired()) {
				$user = $rl->getUser();
				static::login($user, false);
				return $user;
				}
			}
		}
	}

	protected static function forgetLogin()
	{
		$cookie = $_COOKIE['remember_me'] ?? false;

		if ($cookie) {
			$remembered_login = RememberedLogin::findByToken($cookie);

			foreach($remembered_login as $value) {
					$rl = $value;
				}
			if ($rl) {
				if($rl->delete()) {
					setcookie('remember_me', '', time() - 3600, '/');
				}
			}
		}
	}
}