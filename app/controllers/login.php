<?php

Use app\Auth;
use app\Flash;

class Login extends Controller {

	public function __construct() {
		
		$this->auth = $this->auth();
		$this->flash = $this->flash();
	}

	public function new()
	{
		session_start();

		if (isset($_SESSION['flash_notifications'])) { 
		$this->view('login/index', ['messages' => Flash::getMessages()]);
		} else {
		$this->view('login/index');
		}
	}

	public function create()
	{
		session_start();

		$user = User::authenticate($_POST['email'], $_POST['password']);

		$remember_me = isset($_POST['remember_me']);

		if ($user) {

			Auth::login($user, $remember_me);

			if (isset($_SESSION['flash_notifications'])) {
			Flash::unsetMessages();
			}

			Flash::addMessage('Login successful');

			$this->redirect(Auth::getReturnToPage());

		} else {

			if (isset($_SESSION['flash_notifications'])) {
			Flash::unsetMessages();
			}

			Flash::addMessage('Login unsuccessful, please try again', Flash::WARNING);

			$this->view('login/index', ['email' => $_POST['email'], 'messages' => Flash::getMessages(), 'remember_me' => $remember_me
			]);
		}
	}

	public function destroy()
	{
		Auth::logout();

		$this->redirect('homepage/index');
	} 
}