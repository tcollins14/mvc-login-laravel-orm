<html>
<head>
<title> Profile </title>
</head>
<body style="margin-top: 3em;">
	<div class="container">
	<?php 
	include "../app/views/templates/nav.php"; $user = $data['user'];?>
	<h1>Profile</h1>

	<dl class="dl-horizontal">
		<dt>Name</dt>
		<dd> <?php echo $user['name']; ?> </dd>
		<dt>email</dt>
		<dd> <?php echo $user['email']; ?> </dd>
	</dl>

	<a href="/profile/edit">Edit</a>
	
</body>
</div>
</html>