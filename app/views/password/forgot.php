<head>
</head>
<body style="margin-top: 3em;">
<div class="container">
<?php include "../app/views/templates/nav.php"; ?>
<h1>Request password reset</h1>

<form method="post" action="/password/requestReset">
	<div class="form-group"> 
		<label for="inputEmail">Email address</label>
		<input type="email" id="inputEmail" name="email" placeholder="Email address" autofocus required class="form-control"/>
	</div>

	<button type="submit" class="btn btn-default">Send password reset</button>
</form>
</body>
</div>