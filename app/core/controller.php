<?php

use app\Auth;
use app\Flash;

class Controller
{
	public function __construct() {

		$this->auth = $this->auth();
		}

	public function model($model, $data = [])
	{
		require_once '../app/models/' . $model . '.php';
		return new $model($data);
	}

	public function auth() {
		require_once '../app/Auth.php';
	}

	public function flash() {
		require_once '../app/Flash.php';
	}

	public function mail() {
		require_once '../app/Mail.php';
	}

	public function view($view, $data = [])
	{
		 require_once '../app/views/' . $view . '.php';
	}

	public function redirect($url)
	{
		header('Location: http://' . $_SERVER['HTTP_HOST'] . '/' . $url, true, 303);
		exit;
	}

	public function requireLogin()
	{
		session_start();
		if(! Auth::getUser()) {
			$this->flash = $this->flash();
			if (isset($_SESSION['flash_notifications'])) {
			Flash::unsetMessages();
			}
			Flash::addMessage('Please login to access that page', Flash::INFO);
			Auth::rememberRequestedPage();
			$this->redirect('login/new');
		}
	}
}

?>