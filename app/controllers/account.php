<?php

class Account extends Controller {

	public function __construct() {
		
	}

	public function validateEmail() 
		{

			$is_valid = !User::emailExists($_GET['email'], $_GET['ignore_id'] ?? null);
			header('Content-Type: application/json');
			echo json_encode($is_valid);
		}
	}