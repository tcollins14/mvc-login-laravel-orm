<head>
<title> Log in </title>
 </head>
<body style="margin-top: 3em;">
<div class="container">
<?php 
    include "../app/views/templates/nav.php";
    
    if (isset($data['messages'])) {
            foreach($data['messages'] as $message) 
            { ?> 
            <div class="alert alert-<?php echo $message['type'] ?>">
                <p> <?php echo $message['body'];
                ?> </p> </div>
                <?php
                }
             } 
        ?>

    <h1> Log in </h1>
	<form action="/login/create" method="post">
		<div>
			<div class="form-group">
    		<label for="inputEmail">Email address</label>
    		<input type="email" id="inputEmail" name="email" placeholder="Email address" autofocus value="<?php if (isset($data['email'])) {
                echo $data['email'];
            } ?>" class="form-control"/>
            
    	</div>
    	<div class="form-group">
    		<label for="inputPassword">Password</label>
    		<input type="password" id="inputPassword" name="password" placeholder="Password" class="form-control"/>
    	</div>
        <div class="checkbox">
            <label>
               <input type="checkbox" name="remember me" <?php if (isset($data['remember_me'])) {
                if ($data['remember_me']) {
                ?> checked="checked" <?php
               } 
           } ?>/> Remember me 
           </label>
        </div>
             

    	<button type="submit" class="btn btn-default"> Log in </button>
        <a href="/password/forgot">Forgot password?</a>
	</form>
</body>
</div>
