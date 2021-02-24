<!-- including server file for backend purposes -->
<?php include('server.php') ?>
<!DOCTYPE html>
<html>
<head>
  <title>Register</title>
</head>
<body>
  <div class="header">
    <h2>Register</h2>
  </div>
  
  <!-- form for getting user details for registration -->
  <form method="post" action="register.php">
    <?php include('errors.php'); ?>
    <div class="input-group">
      <label>First name:<br></label>
      <input type="text" name="fname" required>
    </div>
    <div class="input-group">
      <label><br>Last name:<br></label>
      <input type="text" name="lname" required>
    </div>
    <div class="input-group">
      <label><br>Username:<br></label>
      <input type="text" name="username" required minlength = "6">
    </div>
    <div class="input-group">
      <label><br>Email:<br></label>
      <input type="email" name="email" required>
    </div>
    <div class="input-group">
      <label><br>Password:<br></label>
      <input type="password" name="password_1" required minlength = "8">
    </div>
    <div class="input-group">
      <label><br>Confirm password:<br></label>
      <input type="password" name="password_2" required minlength = "8">
    </div>
    <div class="input-group">
      <label><br>Birthday:<br></label>
      <input type="date" name="birthday">
    </div>
    <div class="input-group">
      <label><br>Gender:<br></label>
      <input type="radio" name="gender" value="male"> Male
      <input type="radio" name="gender" value="female"> Female
    </div>
    <br>
    <div class="input-group">
      <button type="submit" class="btn" name="reg_adm">Register as Admin</button>
      <button type="submit" class="btn" name="reg_cst">Register as Customer</button>
    </div>
    <p>
      <!-- redirecting to login page -->
      Already a member? <a href="login.php">Sign in</a>
    </p>
  </form>
</body>
</html>