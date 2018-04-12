<?php

class Password extends Controller {

	public function forgot()
	{
		$this->view('password/forgot');
	}

	public function requestReset()
	{
		User::sendPasswordReset($_POST['email']);

		$this->view('password/reset_requested');
	}

	public function reset($token)
	{
		if (ctype_xdigit($token)) { 
			$user = $this->getUserOrExit($token);

			$this->view('password/reset', ['token' => $token]);
			}	else {
				$this->view('password/token_expired');
			}
		}

		public function resetPassword()
		{
			$token = $_POST['token'];

			$user = $this->getUserOrExit($token);

			if ($user->passwordReset($_POST['password'],$_POST['password_confirmation'])) {

				$this->view('password/reset_success');
			} else {

				$this->view('password/reset', ['token' => $token, 'user' => $user]);
			}
		}

		protected function getUserOrExit($token)
		{
			$user = User::findByPasswordReset($token);

			if($user) {

				return $user;

			} else {
				$this->view('password/token_expired');
				exit;
			}
		}
	}