<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u" crossorigin="anonymous">

<nav class="navbar navbar-default">
<div class="container-fluid">
<ul class="nav navbar-nav">
		<li><a href="/homepage/index">Home</a></li>
	</ul>
	<ul class="nav navbar-nav navbar-right">
      	<?php if(isset($_SESSION['user_id'])) { ?>
        <li><a href="/profile/show">Profile</a></li>
        <li><a href="/login/destroy">Log out</a></li> <?php
        }
        ?>

 	</ul>
 </div>
</nav>