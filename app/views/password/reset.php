<!DOCTYPE html>
<head>
<!-- <link rel="stylesheet" type="text/css" href="/css/styles.css"> -->
<title> Reset Password </title>
</head>

<body style="margin-top: 3em;">
  <div class="container">
  <?php include "../app/views/templates/nav.php"; ?>
	<h1> Reset Password </h1>

	<?php 
	
	if (isset($data['user'])) {
	$user = $data['user'];

	if($user->errors != null) {
		?> <p> Errors: </p>
		   <ul> 
		   	<?php
		   	  foreach ($user->errors as $errors) {
		   	  	?> <li> <?php echo "$errors <br>" ?> </li> <?php
		   	}
		   	?> </ul>
		   	<?php }
		   	}
		   	?>
	
	<form method="post" id="formPassword" action="/password/resetPassword">
    <input type="hidden" name="token" value="<?=$data['token'];?>" />
    
    <div>
			<label for="inputPassword">Password</label>
			<input type="password" id="inputPassword" name="password" placeholder="Password" required class="form-control" />
		</div>
		<div class="form-group">
			<label for="inputPasswordConformation">Repeat password</label>
			<input type="password" id="inputPasswordConformation" name="password_confirmation" placeholder="Repeat password" required class="form-control" />  
		</div>  

		<button type="submit" class="btn btn-default">Reset password</button>	
    </form>
    <script src="http://code.jquery.com/jquery-3.3.1.min.js"
  integrity="sha256-FgpCb/KJQlLNfOu91ta32o/NMZxltwRo8QtmkMRdAu8="
  crossorigin="anonymous"></script>
  <script src="http://ajax.aspnetcdn.com/ajax/jquery.validate/1.15.0/jquery.validate.min.js"></script>
  <!-- <script src="/js/app.js"></script> -->

  <script>
      $.validator.addMethod('validPassword',
            function(value, element, param) {
                if (value != '') {
                    if (value.match(/.*[a-z]+.*/i) == null) {
                        return false;
                    }
                    if (value.match(/.*\d+.*/) == null) 
                    {
                        return false;
                    }
                }

                return true;
            },
            'Must contain at least one letter and one number'
        );

	    $(document).ready(function() {

      $('#formPassword').validate({
            rules: {
              password: {
                required:true,
                minlength: 6,
                validPassword: true
              },
             password_confirmation: {
                equalTo: '#inputPassword'
             }
           } 
        });
    });
</script>
</body>
</div>
</html>