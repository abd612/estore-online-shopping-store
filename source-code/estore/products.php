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
	<title>Products</title>
</head>
<body>

<div class="header">
	<h2>Products</h2>
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
  <h3>List of Products</h3>
</div>

<?php

  $db = mysqli_connect('localhost', 'root', '1246', 'estore');

  $aid = $_SESSION['username'];

  // fetch all products
  $query = "select * from product";

  $result = $db->query($query);

  if($result)
  {
    $num_results = $result->num_rows;
    if($num_results != 0)
    {
      // display result in tabular form
      echo "<table border = '1'>";
      echo "<tr><td> productid </td><td> pname </td><td> price </td><td> stock </td><td> prating </td><td> picture </td><td> description </td><td> brandid </td><td> categoryid </td></tr>";
      while ($row = mysqli_fetch_array($result, MYSQLI_ASSOC)) {
      $productid = $row['productid'];
      $pname = $row['pname'];
      $price = $row['price'];
      $stock = $row['stock'];
      $prating = $row['prating'];
      $picture = $row['picture'];
      $description = $row['description'];
      $brandid = $row['brandid'];
      $categoryid = $row['categoryid'];
      echo "</td><td>".$productid."</td><td>".$pname."</td><td>".$price."</td><td>".$stock."</td><td>".$prating."</td><td>".$picture."</td><td>".$description."</td><td>".$brandid."</td><td>".$categoryid."</td></tr>";
      }
      echo "</table>";
    }

    else
      echo "No products found!";
  }

  else
    echo "No products found!";

  $db->close();
?>

<div class="header">
  <h3>Add new product:</h3>
</div>
<!-- form for adding new product -->
<form action = "products.php" method="post">
  <div class="input-group">
    <label>Name:<br></label>
    <input type="text" name="pname" required maxlength="24">
  </div>
  <div class="input-group">
    <label><br>Price:<br></label>
    <input type="number" name="price" step="any" required>
  </div>
  <div class="input-group">
    <label><br>Stock:<br></label>
    <input type="number" name="stock" required min="0">
  </div>
  <div class="input-group">
    <label><br>Rating:<br></label>
    <input type="number" name="prating" required min="1" max="5">
  </div>
  <div class="input-group">
    <label><br>Picture:<br></label>
    <input type="text" name="picture" required maxlength="32">
  </div>
  <div class="input-group">
    <label><br>Description:<br></label>
    <input type="text" name="description" required maxlength="64">
  </div>
  <div class="input-group">
    <label><br>Brand ID:<br></label>
    <input type="int" name="brandid" required min="200000" max="299999">
  </div>
  <div class="input-group">
    <label><br>Category ID:<br></label>
    <input type="int" name="categoryid" required min="300000" max="399999">
  </div>
  <br>
  <div class="input-group">
    <button type="submit" class="btn" name="addproduct">Add Product</button>
  </div>
</form>
<!-- query for adding new product -->
<?php
  if(isset($_POST['addproduct']))
  {
    $db = mysqli_connect('localhost', 'root', '1246', 'estore');

    $aid = $_SESSION['username'];
    $pname = $_POST['pname'];
    $price = $_POST['price'];
    $stock = $_POST['stock'];
    $prating = $_POST['prating'];
    $picture = $_POST['picture'];
    $description = $_POST['description'];
    $brandid = $_POST['brandid'];
    $categoryid = $_POST['categoryid'];

    $query = "insert into product (pname, price, stock, prating, picture, description, brandid, categoryid)
              values ('$pname', $price, $stock, $prating, '$picture', '$description', $brandid, $categoryid)";

    $result = $db->query($query);

    if($result)
      echo "Product added successfully!";
    else
      echo "Error adding product!";

    $db->close();
  }
?>

<div class="header">
  <h3>Modify a product:</h3>
</div>
<!-- form for modifying a product -->
<form action = "products.php" method="post">
  <div class="input-group">
    <label>Product ID:<br></label>
    <input type="text" name="productid" required min="100000" max="199999">
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
    <button type="submit" class="btn" name="modproduct">Modify Product</button>
  </div>
</form>
<!-- query for modifying a product -->
<?php
  if(isset($_POST['modproduct']))
  {
    $db = mysqli_connect('localhost', 'root', '1246', 'estore');

    $aid = $_SESSION['username'];
    $productid = $_POST['productid'];
    $attribute = $_POST['attribute'];
    $value = $_POST['value'];

    $query = "update product set $attribute = '$value' where productid = $productid";

    $result = $db->query($query);

    if($result)
      echo "Product modified successfully!";
    else
      echo "Error modifying product!";

    $db->close();
  }
?>

<div class="header">
  <h3>Delete a product:</h3>
</div>
<!-- form for deleting a product -->
<form action = "products.php" method="post">
  <div class="input-group">
    <label>Product ID:<br></label>
    <input type="text" name="productid" required min="100000" max="199999">
  </div>
  <br>
  <div class="input-group">
    <button type="submit" class="btn" name="delproduct">Delete Product</button>
  </div>
</form>
<!-- query for deleting a product -->
<?php
  if(isset($_POST['delproduct']))
  {
    $db = mysqli_connect('localhost', 'root', '1246', 'estore');

    $aid = $_SESSION['username'];
    $productid = $_POST['productid'];

    $query = "delete from product where productid = $productid";

    $result = $db->query($query);

    if($result)
      echo "Product deleted successfully!";
    else
      echo "Error deleting product!";

    $db->close();
  }
?>

</body>
</html>