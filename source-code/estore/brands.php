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
	<title>Brands</title>
</head>
<body>

<div class="header">
	<h2>Brands</h2>
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
    	<p> <a href="admin.php?logout='1'">Logout</a> <a href="admin.php">Homepage</a> <a href="products.php">View Products</a> <a href="brands.php">View Brands</a> <a href="categories.php">View Categories</a> <a href="manageorders.php">View Orders</a> </p>
    <?php endif ?>
</div>

<div class="header">
  <h3>List of Brands</h3>
</div>

<?php

  $db = mysqli_connect('localhost', 'root', '1246', 'estore');

  $aid = $_SESSION['username'];
  // fetching all brands
  $query = "select * from brand";

  $result = $db->query($query);

  if($result)
  {
    $num_results = $result->num_rows;
    if($num_results != 0)
    {
      // display result in tabular form
      echo "<table border = '1'>";
      echo "<tr><td> brandid </td><td> bname </td></tr>";
      while ($row = mysqli_fetch_array($result, MYSQLI_ASSOC)) {
      $brandid = $row['brandid'];
      $bname = $row['bname'];
      echo "</td><td>".$brandid."</td><td>".$bname."</td></tr>";
      }
      echo "</table>";
    }

    else
      echo "No brands found!";
  }

  else
    echo "No brands found!";

  $db->close();
?>

<div class="header">
  <h3>Add new brand:</h3>
</div>
<!-- form for adding a brand -->
<form action = "brands.php" method="post">
  <div class="input-group">
    <label>Name:<br></label>
    <input type="text" name="bname" required maxlength="24">
  </div>
  <br>
  <div class="input-group">
    <button type="submit" class="btn" name="addbrand">Add Brand</button>
  </div>
</form>
<!-- query for adding a brand -->
<?php
  if(isset($_POST['addbrand']))
  {
    $db = mysqli_connect('localhost', 'root', '1246', 'estore');

    $aid = $_SESSION['username'];
    $bname = $_POST['bname'];

    $query = "insert into brand (bname)
              values ('$bname')";

    $result = $db->query($query);

    if($result)
      echo "Brand added successfully!";
    else
      echo "Error adding brand!";

    $db->close();
  }
?>
<!-- form for modifying a brand -->
<div class="header">
  <h3>Modify a brand:</h3>
</div>

<form action = "brands.php" method="post">
  <div class="input-group">
    <label>Brand ID:<br></label>
    <input type="text" name="brandid" required min="200000" max="299999">
  </div>
  <div class="input-group">
    <label><br>Attribute:<br></label>
    <input type="text" name="attribute" required>
  </div>
  <div class="input-group">
    <label><br>Value:<br></label>
    <input type="text" name="value" required>
  </div>
  <br>
  <div class="input-group">
    <button type="submit" class="btn" name="modbrand">Modify Brand</button>
  </div>
</form>
<!-- query for modifying a brand -->
<?php
  if(isset($_POST['modbrand']))
  {
    $db = mysqli_connect('localhost', 'root', '1246', 'estore');

    $aid = $_SESSION['username'];
    $brandid = $_POST['brandid'];
    $attribute = $_POST['attribute'];
    $value = $_POST['value'];

    $query = "update brand set $attribute = '$value' where brandid = $brandid";

    $result = $db->query($query);

    if($result)
      echo "Brand modified successfully!";
    else
      echo "Error modifying brand!";

    $db->close();
  }
?>

<div class="header">
  <h3>Delete a brand:</h3>
</div>
<!-- form for deleting a brand -->
<form action = "brands.php" method="post">
  <div class="input-group">
    <label>Brand ID:<br></label>
    <input type="text" name="brandid" required min="200000" max="299999">
  </div>
  <br>
  <div class="input-group">
    <button type="submit" class="btn" name="delbrand">Delete Brand</button>
  </div>
</form>
<!-- query for deleting a brand -->
<?php
  if(isset($_POST['delbrand']))
  {
    $db = mysqli_connect('localhost', 'root', '1246', 'estore');

    $aid = $_SESSION['username'];
    $brandid = $_POST['brandid'];

    $query = "delete from brand where brandid = $brandid";

    $result = $db->query($query);

    if($result)
      echo "Brand deleted successfully!";
    else
      echo "Error deleting brand!";

    $db->close();
  }
?>

</body>
</html>