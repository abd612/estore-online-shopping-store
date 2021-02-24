<!-- including server file for backend purposes -->
<?php include('server.php') ?>
<!DOCTYPE html>
<html>
<head>
  <title>Login</title>
</head>
<body>
  <div class="header">
  	<h2>Login</h2>
  </div>
	<!-- form for getting user details for login -->
  <form method="post" action="login.php">
  	<?php include('errors.php'); ?>
  	<div class="input-group">
  		<label>Username:<br></label>
  		<input type="text" name="username" required minlength = "6">
  	</div>
  	<div class="input-group">
  		<label><br>Password:<br></label>
  		<input type="password" name="password" required minlength = "8">
  	</div>
    <br>
  	<div class="input-group">
  		<button type="submit" class="btn" name="log_adm">Login as Admin</button>
      <button type="submit" class="btn" name="log_cst">Login as Customer</button>
  	</div>
  	<p>
      <!-- redirecting to register page -->
  		Not a member yet? <a href="register.php">Sign up</a>
  	</p>
  </form>
</body>
</html>