<?php

class Signup extends Controller {

	public function __construct() {
		
	}

	public function new()
	{
		$this->view('signup/index');
	}

	public function create()
	{
		$this->user =$this->model('User', $_POST);
		$user = $this->user;
		
		if ($user->test()) {

			$user->sendActivationEmail($_POST['email']);

			$this->redirect('signup/success');
		} else {
			$this->view('signup/index', ['user' => $user]);
		}
	}

	public function success()
	{
		$this->view('signup/success');
	}

	public function activate($token)
	{
		User::confirm($token);
		
		$this->redirect('signup/activated');
	}

	public function activated()
	{
		$this->view('signup/activated');
	}
}

?>