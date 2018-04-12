<?php

use app\Auth;
use app\Flash;
use app\Mail;

class Homepage extends Controller {

	protected $user;

	public function __construct() {
		$this->user = $this->model('User');
		$this->auth = $this->auth();
		$this->flash = $this->flash();
		$this->mail = $this->mail();
	}

	public function index() {

		session_start();

		if (isset($_SESSION['flash_notifications'])) {
			$this->view('home/index', ['user' => Auth::getUser(), 'messages' => Flash::getMessages()]);
			Flash::unsetMessages();
		} else {
			$this->view('home/index', ['user' => Auth::getUser()]);
		}
	}
}

?>