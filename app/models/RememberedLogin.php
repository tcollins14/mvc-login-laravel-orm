<?php

use Illuminate\Database\Eloquent\Model as Eloquent;
use Illuminate\Database\Capsule\Manager as DB;

use app\Token;

class RememberedLogin extends Eloquent
{
	public function __construct() {
		
	}

	public static function findByToken($token)
	{
		$token = new Token($token);
		$token_hash = $token->getHash();

		$test = RememberedLogin::where('token_hash', $token_hash)->get();

		return $test;
	}

	public function getUser()
	{
		return User::findByID($this->user_id);
	}

	public function hasExpired()
	{
		return strtotime($this->expires_at) < time();
	}

	public function delete()
	{
		$delete = RememberedLogin::where('token_hash', $this->token_hash)->delete();

		return $delete;
	}
}