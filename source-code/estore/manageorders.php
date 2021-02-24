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
	<title>Orders</title>
</head>
<body>

<div class="header">
	<h2>Orders</h2>
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
  <h3>List of Orders</h3>
</div>

<?php

  $db = mysqli_connect('localhost', 'root', '1246', 'estore');

  $aid = $_SESSION['username'];
  // fetching all orders
  $query = "select * from iorder";

  $result = $db->query($query);

  if($result)
  {
    $num_results = $result->num_rows;
    if($num_results != 0)
    {
      // display result in tabular form
      echo "<table border = '1'>";
      echo "<tr><td> orderid </td><td> ordertotal </td><td> idate </td><td> istatus </td><td> address </td><td> phone </td><td> company </td><td> itype </td><td> inumber </td><td> cvv </td><td> expiry </td><td> customerid </td><td> adminid </td></tr>";
      while ($row = mysqli_fetch_array($result, MYSQLI_ASSOC)) {
      $orderid = $row['orderid'];
      $ordertotal = $row['ordertotal'];
      $idate = $row['idate'];
      $istatus = $row['istatus'];
      $address = $row['address'];
      $phone = $row['phone'];
      $company = $row['company'];
      $itype = $row['itype'];
      $inumber = $row['inumber'];
      $cvv = $row['cvv'];
      $expiry = $row['expiry'];
      $customerid = $row['customerid'];
      $adminid = $row['adminid'];
      echo "<tr><td>".$orderid."</td><td>".$ordertotal."</td><td>".$idate."</td><td>".$istatus."</td><td>".$address."</td><td>".$phone."</td><td>".$company."</td><td>".$itype."</td><td>".$inumber."</td><td>".$cvv."</td><td>".$expiry."</td><td>".$customerid."</td><td>".$adminid."</td></tr>";
      }
      echo "</table>";
    }

    else
      echo "No orders found!";
  }

  else
    echo "No orders found!";

  $db->close();
?>

<div class="header">
  <h3>Modify an order:</h3>
</div>
<!-- form for modifying an order -->
<form action = "manageorders.php" method="post">
  <div class="input-group">
    <label>Order ID:<br></label>
    <input type="text" name="orderid" required min="600000" max="699999">
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
    <button type="submit" class="btn" name="modorder">Modify Order</button>
  </div>
</form>
<!-- query for modifying an order -->
<?php
  if(isset($_POST['modorder']))
  {
    $db = mysqli_connect('localhost', 'root', '1246', 'estore');

    $aid = $_SESSION['username'];
    $orderid = $_POST['orderid'];
    $attribute = $_POST['attribute'];
    $value = $_POST['value'];

    $query = "update iorder set $attribute = '$value' where orderid = $orderid";

    $result = $db->query($query);

    if($result)
      echo "Order modified successfully!";
    else
      echo "Error modifying order!";

    $db->close();
  }
?>

<div class="header">
  <h3>Delete an order:</h3>
</div>
<!-- form for deleting an order -->
<form action = "manageorders.php" method="post">
  <div class="input-group">
    <label>Order ID:<br></label>
    <input type="text" name="orderid" required min="600000" max="699999">
  </div>
  <br>
  <div class="input-group">
    <button type="submit" class="btn" name="delorder">Delete Order</button>
  </div>
</form>
<!-- query for deleting an order -->
<?php
  if(isset($_POST['delorder']))
  {
    $db = mysqli_connect('localhost', 'root', '1246', 'estore');

    $aid = $_SESSION['username'];
    $orderid = $_POST['orderid'];

    $query = "delete from iorder where orderid = $orderid";

    $result = $db->query($query);

    if($result)
      echo "Order deleted successfully!";
    else
      echo "Error deleting order!";

    $db->close();
  }
?>

</body>
</html>