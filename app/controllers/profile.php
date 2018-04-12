<?php

use app\Auth;
use app\Flash;

class Profile extends Controller {

	public function __construct() {
		
		$this->auth = $this->auth();
		$this->flash = $this->flash();
	}

	public function show()
	{
		$this->requireLogin();

		$this->view('profile/show', ['user' => Auth::getUser()
	]);
	}

	public function edit()
	{
		$this->requireLogin();
		
		$this->view('profile/edit', ['user' => Auth::getUser()
	]);
	}

	public function update()
	{
		$this->requireLogin();

		$user = Auth::getUser();

		if ($user->updateProfile($_POST)) {
			Flash::addMessage('Changes saved');

			$this->redirect('profile/show');
		} else {

			$this->view('profile/edit', ['user' => $user
		]);	

		}
	}
}

?>