<?php

 include_once('../gb_header.php'); ?>

	<div class="container">

		<img class="login_img" src="images/logo.jpg" alt="FICM Festival Internacional de Cine de Morelia">

		<form class="form-signin" role="form">
			
			<h2 class="form-signin-heading">Please sign in</h2>
			<input type="username" class="form-control" placeholder="Username" required="" autofocus="">
			<input type="password" class="form-control" placeholder="Password" required="">
			<label class="checkbox">
				<input type="checkbox" value="remember-me">Remember me
			</label>
			<button class="btn btn-lg btn-primary btn-block" type="submit">Sign in</button>
		</form>

	</div>

<?php include_once ('../footer.php'); ?>