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
	<title>Your Orders</title>
</head>
<body>

<div class="header">
	<h2>Your Orders</h2>
</div>
<div class="content">
  	<!-- notification message -->
  	<?php if (isset($_SESSION['success'])) : ?>
      <div class="error success" >
      	<h3>
          <?php 
          	//echo $_SESSION['success']; 
          	unset($_SESSION['success']);
          ?>
      	</h3>
      </div>
  	<?php endif ?>

    <!-- logged in user information -->
    <?php  if (isset($_SESSION['username'])) : ?>
    	<p>User: <strong><?php echo $_SESSION['username']; ?></strong></p>
      <!-- links to different pages -->
    	<p> <a href="customer.php?logout='1'">Logout</a> <a href="customer.php">Homepage</a> <a href="cart.php">View Cart</a> </p>
    <?php endif ?>
</div>

<br>

<?php
  if(isset($_POST['order']))
  {
    // fetch the user cart
    $db = mysqli_connect('localhost', 'root', '1246', 'estore');
    $cid = $_SESSION['username'];
    $query1 = "select p.pname, p.price, cp.cquantity, c.cartid, c.carttotal
              from cart c join cart_products cp on c.cartid = cp.cartid join product p on cp.productid = p.productid
              where c.customerid = '$cid'";

    $result1 = $db->query($query1);

    $row = mysqli_fetch_array($result1, MYSQLI_ASSOC);
    $cartid = $row['cartid'];
    $orderid = 100000 + $row['cartid'];
    $ordertotal = $row['carttotal'];

    // initiate order
    $query2 = "insert into iorder (orderid, ordertotal, customerid) values ($orderid, $ordertotal, '$cid')";
    $result2 = $db->query($query2);

    // fetch cart products
    $query3 = "select * from cart_products where cartid = (select cartid from cart where customerid = '$cid');";
    $result3 = $db->query($query3);

    if($result3)
    {
      $num_results = $result3->num_rows;
      if($num_results != 0)
      {
        while ($row = mysqli_fetch_array($result3, MYSQLI_ASSOC)) {
        $productid = $row['productid'];
        $oquantity = $row['cquantity'];
        // copy cart products to order products
        $query = "insert into order_products (orderid, productid, oquantity) values ($orderid, $productid, $oquantity)";
        $result = $db->query($query);
        }
      }

      else
        echo "Cart empty!";
    }

    else
      echo "Cart empty!";

    $date = date('y-m-d');
    $status = "Order Placed";
    $address = $_POST['address'];
    $phone = $_POST['phone'];
    $company = $_POST['company'];
    $type = $_POST['type'];
    $number = $_POST['number'];
    $cvv = $_POST['cvv'];
    $expiry = $_POST['expiry'];
    
    // add remaining details to order
    $query = "update iorder set idate = curdate(), istatus = '$status', address = '$address', phone = '$phone', company = '$company', itype = '$type', inumber = '$number', cvv = $cvv, expiry = '$expiry' where orderid = $orderid";

    $result = $db->query($query);

    if($result)
    {
      echo "Order Placed Successfully!";

      // empty the user cart
      $query = "delete from cart_products where cartid = $cartid";

      $result = $db->query($query);

      $query = "update cart set carttotal = 0 where cartid = $cartid";

      $result = $db->query($query);      

    }
  }
?>

<?php
  // get a list of all orders for the customer
  $db = mysqli_connect('localhost', 'root', '1246', 'estore');
  $cid = $_SESSION['username'];
  $query = "select ordertotal, idate, istatus
            from iorder
            where customerid = '$cid'";

  $result = $db->query($query);

  if($result)
  {
    $num_results = $result->num_rows;
    if($num_results != 0)
    {
      // display the result in tabular form
      echo "<table border = '1'>";
      echo "<tr><td> ordertotal </td><td> idate </td><td> istatus </td></tr>";
      while ($row = mysqli_fetch_array($result, MYSQLI_ASSOC)) {
      $total = $row['ordertotal'];
      $date = $row['idate'];
      $status = $row['istatus'];
      echo "</td><td>".$total."</td><td>".$date."</td><td>".$status."</td></tr>";
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

</body>
</html>