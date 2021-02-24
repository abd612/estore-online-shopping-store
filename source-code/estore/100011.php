<?php
  $pid = 100011;
  session_start(); 

  // checking if user is logged in
  if (!isset($_SESSION['username'])) {
    $_SESSION['msg'] = "You must log in first";
    header('location: login.php');
  }

  // logging out user
  if (isset($_GET['logout'])) {
    $db = mysqli_connect('localhost', 'root', '1246', 'estore');
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
	<title>Monark Grey Shirt</title>
</head>
<body>

<div class="header">
	<h2>Monark Grey Shirt</h2>
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
      <p> <a href="customer.php?logout='1'">Logout</a> <a href="customer.php">Homepage</a> <a href="cart.php">View Cart</a> <a href="orders.php">View Orders</a> </p>
    <?php endif ?>
</div>

<br>
<!-- query for fetching and displaying product details -->
<?php
  $db = mysqli_connect('localhost', 'root', '1246', 'estore');
  $cid = $_SESSION['username'];
  $query = "select p.productid, p.pname, p.price, p.stock, p.prating, p.picture, p.description, b.bname, c.cname
            from product p join category c on p.categoryid = c.categoryid join brand b on p.brandid = b.brandid
            where p.productid = $pid";

  $result = $db->query($query);

  if($result)
  {
    $num_results = $result->num_rows;
    if($num_results != 0)
    {
      while ($row = mysqli_fetch_array($result, MYSQLI_ASSOC)) {
      $productid = $row['productid'];
      $pname = $row['pname'];
      $price = $row['price'];
      $stock = $row['stock'];
      $rating = $row['prating'];
      $picture = $row['picture'];
      $description = $row['description'];
      $bname = $row['bname'];
      $cname = $row['cname'];

      ?>
      <!-- displaying product image -->
      <img src="<?php echo "images/$picture";?>" alt="<?php echo "$pname";?>" width="300" height="300">
      <br>
      <?php
      echo "<strong> $pname</strong> <br>";
      echo "$description <br>";

      echo "<br><strong> Product Details </strong><br><br>";
      echo "<table border = '1'>";
      echo "</td><td> Price </td><td> Rs. $price </td></td></tr>";
      echo "</td><td> Stock </td><td> $stock </td></td></tr>";
      echo "</td><td> Rating </td><td> $rating </td></td></tr>";
      echo "</td><td> Brand </td><td> $bname </td></td></tr>";
      echo "</td><td> Category </td><td> $cname </td></td></tr>";
      echo "</table>";
      }
    }

    else
      echo "No result found!";
  }

  else
    echo "No result found!";

  echo "<br><strong> Order </strong> <br><br>";
  $db->close();
?>

<!-- form for adding to cart -->
<form action = "<?php echo "$pid.php";?>" method="post">
  <div class="input-group">
    <label>Quantity:</label>
    <input type="number" name="quantity" required min="1" max="10">
  </div>
  <br>
  <div class="input-group">
    <button type="submit" class="btn" name="addtocart">Add to Cart</button>
  </div>
</form>
<!-- query for adding to cart -->
<?php
  if(isset($_POST['addtocart']))
  {
    $db = mysqli_connect('localhost', 'root', '1246', 'estore');
    $cid = $_SESSION['username'];
    $quantity = $_POST['quantity'];

    $query0 = "select cartid from cart where customerid = '$cid'";
    $result0 = $db->query($query0);
    $row = mysqli_fetch_array($result0, MYSQLI_ASSOC);
    $cartid = $row['cartid'];

    $query1 = "insert into cart_products values ($cartid, $pid, $quantity)";
    $result1 = $db->query($query1);

    $query2 = "update cart set carttotal = carttotal + ($price*$quantity) where cartid = $cartid";
    $result2 = $db->query($query2);

    $query3 = "update product set stock = (stock - $quantity) where productid = $pid";
    $result3 = $db->query($query3);

    if($result && $result2 && $result3)
      echo "Product added to cart!";
    else
      echo "Error adding product to cart!";
  }
?>
<!-- query for fetching and displaying reviews -->
<?php
  echo "<br><strong> Reviews </strong> <br><br>";

  $db = mysqli_connect('localhost', 'root', '1246', 'estore');

  $query = "select r.customerid, r.rating, r.icomment
            from review r join product p on r.productid = p.productid
            where p.productid = $pid;";

  $result = $db->query($query);

  if($result)
  {
    $num_results = $result->num_rows;
    if($num_results != 0)
    {
      echo "<table border = '1'>";
      echo "<tr><td> customerid </td><td> rating </td><td> icomment </td></tr>";
      while ($row = mysqli_fetch_array($result, MYSQLI_ASSOC)) {
      $customerid = $row['customerid'];
      $rating = $row['rating'];
      $comment = $row['icomment'];
      echo "</td><td>".$customerid."</td><td>".$rating."</td><td>".$comment."</td></tr>";
      }
      echo "</table>";
    }

    else
      echo "No reviews yet!";
  }

  else
    echo "No reviews yet!";
?>

<br><br>
<strong>Add your own review</strong>
<br><br>
<!-- form for adding review -->
<form action = "<?php echo "$pid.php";?>" method="post">
  <div class="input-group">
    <label>Rating:</label>
    <input type="number" name="rating" required min="1" max="5">
  </div>
  <br>
  <div class="input-group">
    <label>Comment:</label><br>
    <input type="text" name="comment" required maxlength="64">
  </div>
  <br>
  <div class="input-group">
    <button type="submit" class="btn" name="addreview">Add Review</button>
  </div>
</form>
<!-- query for adding review -->
<?php
  if(isset($_POST['addreview']))
  {
    $db = mysqli_connect('localhost', 'root', '1246', 'estore');
    $cid = $_SESSION['username'];
    $rating = $_POST['rating'];
    $comment = $_POST['comment'];

    $query1 = "insert into review (rating, icomment, customerid, productid)
              values ($rating, '$comment', '$cid', $pid)";

    $result1 = $db->query($query1);

    $query2 = "update product set prating = (prating + $rating)/2 where productid = $pid;";
    $result2 = $db->query($query2);

    if($result1 && $result2)
      echo "Review added!";
    else
      echo "Error adding review!";
  }
?>

</body>
</html>