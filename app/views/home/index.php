<head>
</head>
<body style="margin-top: 3em;">
	<div class="container">
 <?php
 include "../app/views/templates/nav.php";
 $user = $data['user']; 
 ?>
 <h1>Welcome</h1>
 <?php if(isset($_SESSION['user_id'])) { 
    echo 'Hello ' . $user['name'];
  } else { ?>
   <a href="/signup/new">Sign up</a>
   <a href="/login/new">Login</a> <?php
   } ?>
 </body>
</div>


