<?php 
  session_start(); 

  // checking if user is logged in
  if (!isset($_SESSION['username'])) {
  	$_SESSION['msg'] = "You must log in first";
  	header('location: login.php');
  }

  // logging out user
  if (isset($_GET['logout'])) {
    $db = mysqli_connect('localhost', 'root', '1246', 'estore');
    // deleting the respective cart
    $cid = $_SESSION['username'];
    $query1 = "delete from cart_products where cartid = (select cartid from cart where customerid = '$cid')";
    $result1 = $db->query($query1);
    $query2 = "delete from cart where customerid = '$cid'";
    $result2 = $db->query($query2);
  	session_destroy();
  	unset($_SESSION['username']);
  	header("location: login.php");
  }
?>
<!DOCTYPE html>
<html>
<head>
	<title>Your Cart</title>
</head>
<body>

<div class="header">
	<h2>Your Cart</h2>
</div>
<div class="content">
  	<!-- notification message -->
  	<?php if (isset($_SESSION['success'])) : ?>
      <div class="error success" >
      	<h3>
          <?php  
          	unset($_SESSION['success']);
          ?>
      	</h3>
      </div>
  	<?php endif ?>

    <!-- logged in user information -->
    <?php  if (isset($_SESSION['username'])) : ?>
    	<p>User: <strong><?php echo $_SESSION['username']; ?></strong></p>
      <!-- links to different pages -->
    	<p> <a href="customer.php?logout='1'">Logout</a> <a href="customer.php">Homepage</a> <a href="orders.php">View Orders</a> </p>
    <?php endif ?>
</div>

<br>

<?php
  // show the user cart
  $db = mysqli_connect('localhost', 'root', '1246', 'estore');
  $cid = $_SESSION['username'];
  $query = "select p.pname, p.price, cp.cquantity, c.carttotal
            from cart c join cart_products cp on c.cartid = cp.cartid join product p on cp.productid = p.productid
            where c.customerid = '$cid'";

  $result = $db->query($query);

  if($result)
  {
    $num_results = $result->num_rows;
    if($num_results != 0)
    {
      // show result in tabular form
      echo "<table border = '1'>";
      echo "<tr><td> pname </td><td> price </td><td> quantity </td><td> total </td></tr>";
      while ($row = mysqli_fetch_array($result, MYSQLI_ASSOC)) {
      $pname = $row['pname'];
      $price = $row['price'];
      $quantity = $row['cquantity'];
      $total = $row['carttotal'];
      echo "</td><td>".$pname."</td><td>".$price."</td><td>".$quantity."</td><td>".$total."</td></tr>";
      }
      echo "</table>";
    }

    else
      echo "No result found!";
  }

  else
    echo "No result found!";

  $db->close();
?>
<br><br>

<!-- button for checkout -->
<form action = "checkout.php" method="post">
  <div class="input-group">
      <button type="submit" class="btn" name="checkout">Checkout</button>
    </div>
</body>
</html>