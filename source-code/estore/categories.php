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
	<title>Categories</title>
</head>
<body>

<div class="header">
	<h2>Categories</h2>
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
  <h3>List of Categories</h3>
</div>

<?php

  $db = mysqli_connect('localhost', 'root', '1246', 'estore');

  $aid = $_SESSION['username'];
  // fetching all categories
  $query = "select * from category";

  $result = $db->query($query);

  if($result)
  {
    $num_results = $result->num_rows;
    if($num_results != 0)
    {
      // display result in tabular form
      echo "<table border = '1'>";
      echo "<tr><td> categoryid </td><td> cname </td></tr>";
      while ($row = mysqli_fetch_array($result, MYSQLI_ASSOC)) {
      $categoryid = $row['categoryid'];
      $cname = $row['cname'];
      echo "</td><td>".$categoryid."</td><td>".$cname."</td></tr>";
      }
      echo "</table>";
    }

    else
      echo "No categories found!";
  }

  else
    echo "No categories found!";

  $db->close();
?>

<div class="header">
  <h3>Add new category:</h3>
</div>
<!-- form for adding a category -->
<form action = "categories.php" method="post">
  <div class="input-group">
    <label>Name:<br></label>
    <input type="text" name="cname" required maxlength="24">
  </div>
  <br>
  <div class="input-group">
    <button type="submit" class="btn" name="addcategory">Add Category</button>
  </div>
</form>
<!-- query for adding a category -->
<?php
  if(isset($_POST['addcategory']))
  {
    $db = mysqli_connect('localhost', 'root', '1246', 'estore');

    $aid = $_SESSION['username'];
    $cname = $_POST['cname'];

    $query = "insert into category (cname)
              values ('$cname')";

    $result = $db->query($query);

    if($result)
      echo "Category added successfully!";
    else
      echo "Error adding category!";

    $db->close();
  }
?>

<div class="header">
  <h3>Modify a category:</h3>
</div>
<!-- form for modifying a category -->
<form action = "categories.php" method="post">
  <div class="input-group">
    <label>Category ID:<br></label>
    <input type="text" name="categoryid" required min="300000" max="399999">
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
    <button type="submit" class="btn" name="modcategory">Modify Category</button>
  </div>
</form>
<!-- query for modifying a category -->
<?php
  if(isset($_POST['modcategory']))
  {
    $db = mysqli_connect('localhost', 'root', '1246', 'estore');

    $aid = $_SESSION['username'];
    $categoryid = $_POST['categoryid'];
    $attribute = $_POST['attribute'];
    $value = $_POST['value'];

    $query = "update category set $attribute = '$value' where categoryid = $categoryid";

    $result = $db->query($query);

    if($result)
      echo "Category modified successfully!";
    else
      echo "Error modifying category!";

    $db->close();
  }
?>

<div class="header">
  <h3>Delete a category:</h3>
</div>
<!-- form for deleting a category -->
<form action = "categories.php" method="post">
  <div class="input-group">
    <label>Category ID:<br></label>
    <input type="text" name="categoryid" required min="300000" max="399999">
  </div>
  <br>
  <div class="input-group">
    <button type="submit" class="btn" name="delcategory">Delete Category</button>
  </div>
</form>
<!-- query for deleting a category -->
<?php
  if(isset($_POST['delcategory']))
  {
    $db = mysqli_connect('localhost', 'root', '1246', 'estore');

    $aid = $_SESSION['username'];
    $categoryid = $_POST['categoryid'];

    $query = "delete from category where categoryid = $categoryid";

    $result = $db->query($query);

    if($result)
      echo "Category deleted successfully!";
    else
      echo "Error deleting category!";

    $db->close();
  }
?>

</body>
</html>