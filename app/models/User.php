<?php

use Illuminate\Database\Eloquent\Model as Eloquent;
use Illuminate\Database\Capsule\Manager as DB;

use app\Token;
use app\Mail;

class User extends Eloquent
{
	
  protected $fillable = ['name', 'email', 'password', 'password_confirmation'];

	public $errors = [];

	public function __construct($data =[])

	{
 		foreach ($data as $key => $value) {
      		$this->$key = $value;
    	};

    }

  public function setName($name){
        $this->name = $name;
    }

  public function setEmail($email){
        $this->email = $email;
    }

  public function test() 
	{

	$this->validate();

	if (empty($this->errors)) {

	$password_hash = password_hash($this->password, PASSWORD_DEFAULT);

  $token = new Token();
  $hashed_token = $token->getHash();
  $this->activation_token = $token->getValue();

    DB::table('users')->insert(
	['name' => $this->name, 'email' => $this->email,
	'password_hash' => $password_hash, 'activation_hash' => $hashed_token]
	);

	return true;
	}
	return false;
}

	public function validate()
    {
       // Name
       if ($this->name == '') {
           $this->errors[] = 'Name is required';
       }

       // email address
       if (filter_var($this->email, FILTER_VALIDATE_EMAIL) === false) {
           $this->errors[] = 'Invalid email';
       }

       if (static::emailExists($this->email, $this->id ?? null)) {
          $this->errors[] = 'email already taken';
       }

       // Password

       if (isset($this->password)) {

       if ($this->password != $this->password_confirmation) {
           $this->errors[] = 'Password must match confirmation';
       }

       if (strlen($this->password) < 6) {
           $this->errors[] = 'Please enter at least 6 characters for the password';
       }

       if (preg_match('/.*[a-z]+.*/i', $this->password) == 0) {
           $this->errors[] = 'Password needs at least one letter';
       }

       if (preg_match('/.*\d+.*/i', $this->password) == 0) {
           $this->errors[] = 'Password needs at least one number';
       }
     }

       $this->setName($this->name);
       $this->setEmail($this->email);

    }

     public static function emailExists($email, $ignore_id = null)
    {
      $user = static::findByEmail($email);
      if ($user) {
        if ($user->id != $ignore_id) {
          return true;
        }
      }
      return false;
    }

    public static function findByEmail($email)
    {
      $emailExists = User::where('email', '=', $email)->first();

      if ( is_null($emailExists) ) {
       return false;
      } else {
      return $emailExists;
      }
    }

    public static function authenticate($email, $password)
    {
      $user = static::findByEmail($email);

      if ($user && $user->is_active) {
          if (password_verify($password, $user->password_hash)) {
            return $user;
          }
      }
      return false;
    }

    public static function findByID($id)
    {
      $user = User::find($id);

      return $user;
    }

    public function rememberLogin()
    {

      $token = new Token();
      $hashed_token = $token->getHash();
      $this->remember_token = $token->getValue();

      $this->expiry_timestamp = time() + 60 * 60 * 24 * 30;

      DB::table('remembered_logins')->insert(
        ['token_hash' => $hashed_token, 'user_id' => $this->id, 'expires_at' => date('Y-m-d H:i:s', $this->expiry_timestamp)]);

         return true;
    }

    public static function sendPasswordReset($email) {
      
      $user = static::findByEmail($email);

      if ($user) {

        if ($user->startPasswordReset()){
            $user->sendPasswordResetEmail($email);
        }
      }
    }

    protected function startPasswordReset()
    {
      $token = new Token();
      $hashed_token = $token->getHash();
      $this->password_reset_token = $token->getValue();
      
      $expiry_timestamp = time() + 60 * 60 * 2;

      $password_reset = DB::table('users')
      ->where('id', $this->id)
      ->update(['password_reset_hash' => $hashed_token, 'password_reset_expires_at' => date('Y-m-d H:i:s', $expiry_timestamp)]);

      return $password_reset;
    }

    protected function sendPasswordResetEmail($email)
    {
      $url = 'http://' . $_SERVER['HTTP_HOST'] . '/password/reset/' . $this->password_reset_token;

      $text = "Please click on the following URL to reset your password: $url";
      $html = "Please click <a href=\"$url\">here</a> to reset your password.";
      Mail::send($email, 'Password reset', $text, $html);
    }

    public static function findByPasswordReset($token)
    {
      $token = new Token($token);
      $hashed_token = $token->getHash();

      $user = User::where('password_reset_hash', $hashed_token)->get();

      foreach($user as $value) {
         $u = $value;
      }
      
      if (isset($u)) {
        if (strtotime($u->password_reset_expires_at) > time()) {
            return $u;
        }
      }
    }

    public function passwordReset($password, $password_confirmation)
    {
      $this->password = $password;

      $this->password_confirmation = $password_confirmation;

      $this->validate();

      if (empty($this->errors)) {

        $password_hash = password_hash($this->password, PASSWORD_DEFAULT);

      $password_reset = DB::table('users')
      ->where('id', $this->id)
      ->update(['password_hash' => $password_hash, 'password_reset_expires_at' => NULL, 'password_reset_hash' => NULL]);

      return $password_reset;

      }
      return false;
    }

    public function sendActivationEmail($email)
    {
      $url = 'http://' . $_SERVER['HTTP_HOST'] . '/signup/activate/' . $this->activation_token;

      $text = "Thank you for signing up. Please click the following link to activate your account: $url";
      $html = "Thank you for signing up. Please click <a href=\"$url\">here</a> to activate your account.";
      Mail::send($email, 'Account activation', $text, $html);
    }

    public static function confirm($value) {

      $token = new Token($value);
      $hashed_token = $token->getHash();

      $activate = DB::table('users')
      ->where('activation_hash', $hashed_token)
      ->update(['is_active' => 1, 'activation_hash' => NULL]);

      return $activate;

    }

    public function updateProfile($data) {
    $this->name = $data['name'];
    $this->email = $data['email'];

    if ($data['password'] != '') {
    $this->password = $data['password'];
    $this->password_confirmation = $data['password_confirmation'];
    }

    $this->validate();

    if (empty($this->errors)) {

    if (isset($this->password)) {

    $password_hash = password_hash($this->password, PASSWORD_DEFAULT);
    }

    $query = array("name" => $this->name, "email" => $this->email);

    if (isset($this->password)) {
      $query['password_hash'] = $password_hash;
    }

    $update = DB::table('users')->where('id', $this->id)->update($query);
     
    return $update;
    }
    return false;
    } 
}