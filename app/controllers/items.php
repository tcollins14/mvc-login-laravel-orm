<?php

use app\Auth;

class Items extends Controller {

	public function __construct() {

		$this->auth = $this->auth();
	}

	public function index()
	{
		$this->requireLogin();

		$this->view('items/index');
	}

	public function new()
	{
		$this->requireLogin();

		echo "new action";
	}

	public function show() 
	{
		$this->requireLogin();

		echo "show action";
	}
}


?>