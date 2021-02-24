<?php 
  session_start(); 

  // checking if user is logged in
  if (!isset($_SESSION['username'])) {
  	$_SESSION['msg'] = "You must log in first";
  	header('location: login.php');
  }

  // logging out user
  if (isset($_GET['logout'])) {
  	session_destroy();
  	unset($_SESSION['username']);
  	header("location: login.php");
  }
?>
<!DOCTYPE html>
<html>
<head>
	<title>Home Page for Admin</title>
</head>
<body>

<div class="header">
	<h2>Home Page for Admin</h2>
</div>
<div class="content">
  	<!-- notification message -->
  	<?php if (isset($_SESSION['success'])) : ?>
      <div class="error success" >
      	<h3>
          <?php 
          	echo $_SESSION['success']; 
          	unset($_SESSION['success']);
          ?>
      	</h3>
      </div>
  	<?php endif ?>

    <!-- logged in user information -->
    <?php  if (isset($_SESSION['username'])) : ?>
    	<p>Welcome <strong><?php echo $_SESSION['username']; ?></strong></p>
      <!-- links to different pages -->
    	<p> <a href="admin.php?logout='1'">Logout</a> </p>
      <p> <a href="products.php">View Products</a> </p>
      <p> <a href="brands.php">View Brands</a> </p>
      <p> <a href="categories.php">View Categories</a> </p>
      <p> <a href="manageorders.php">View Orders</a> </p>
    <?php endif ?>
</div>

</body>
</html>