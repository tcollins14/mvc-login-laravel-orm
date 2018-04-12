<!DOCTYPE html>
<head>
<title> Profile </title>
</head>

<body style="margin-top: 3em;">
  <div class="container">
  <?php include "../app/views/templates/nav.php"; ?>
	<h1> Profile </h1>

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
	
	<form method="post" id="formProfile" action="/profile/update">

    	<div class="form-group">
    		<label for="inputName">Name</label>
    		<input id="inputName" name="name" placeholder="Name" value="<?php if (isset($data['user'])) { echo $user->name; }?>" required class="form-control" />
    	</div>
    	<div class="form-group">
    		<label for="inputEmail">Email address</label>
    		<input id="inputEmail" name="email" placeholder="email address" value="<?php if (isset($data['user'])) { echo $user->email; }?>" required type="email" class="form-control"/>
    	</div>
    	<div class="form-group">
			<label for="inputPassword">Password</label>
			<input type="password" id="inputPassword" name="password" placeholder="Password" aria-describedby="helpBlock" class="form-control" />
      <span id="helpBlock" class="help block">Leave blank to keep current password</span>
		</div>
		<div class="form-group">
			<label for="inputPasswordConformation">Repeat password</label>
			<input type="password" id="inputPasswordConformation" name="password_confirmation" placeholder="Repeat password" class="form-control"/>  
		</div>

    <input type="hidden" id="uid" name="userid" value="<?php echo $user->id; ?>">

		<button type="submit" class="btn btn-default">Save</button>	
    <a href="/profile/show">Cancel</a>
    </form>

    <script src="http://code.jquery.com/jquery-3.3.1.min.js"
  integrity="sha256-FgpCb/KJQlLNfOu91ta32o/NMZxltwRo8QtmkMRdAu8="
  crossorigin="anonymous"></script>
  <script src="http://ajax.aspnetcdn.com/ajax/jquery.validate/1.15.0/jquery.validate.min.js"> </script>
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

      var userId = $('#uid').val();

       $('#formProfile').validate({
            rules: {
                name: 'required',
                email: {
                    required: true,
                    email: true,
                    remote:  {
                      url: '/account/validateEmail',
                      data: {
                        ignore_id: function() {
                          return userId;
                        }
                    }
                }
            },
            password: {
                minlength: 6,
                validPassword: true
                },
             password_confirmation: {
                equalTo: '#inputPassword'
             }
           },
           messages: {
           		 email: {
           			   remote: 'email already taken'
           		}
           	}   
        });
    });
</script>
</body>
</div>
</html>