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
	<title>Home Page for Customer</title>
</head>
<body>

<div class="header">
	<h2>Home Page for Customer</h2>
</div>
<div class="content">
  	<!-- notification message -->
  	<?php if (isset($_SESSION['success'])) : ?>
      <div class="error success" >
      	<h3>
          <?php 
          	echo $_SESSION['success'];
            // create a cart for the customer
            $db = mysqli_connect('localhost', 'root', '1246', 'estore');
            $cid = $_SESSION['username'];
            $query = "insert into cart (carttotal, customerid) values (0, '$cid')";
            $result = $db->query($query);
          	unset($_SESSION['success']);
          ?>
      	</h3>
      </div>
  	<?php endif ?>

    <!-- logged in user information -->
    <?php  if (isset($_SESSION['username'])) : ?>
    	<p>Welcome <strong><?php echo $_SESSION['username']; ?></strong></p>
      <!-- links to different pages -->
    	<p> <a href="customer.php?logout='1'">Logout</a> <a href="cart.php">View Cart</a> <a href="orders.php">View Orders</a> </p>
    <?php endif ?>
</div>

<div class="header">
  <h3>Search for Product</h3>
</div>

<!-- form for search input -->
<form action="customer.php" method="post">
  <div class="input-group">
    <label>Keyword:<br></label>
    <input type="text" name="username">
  </div>
  <div class="input-group">
    <label><br>Category:<br></label>
    <select name="category">
      <option selected disabled hidden style='display: none' value=''></option>
      <option value="smartphones">Smartphones</option>
      <option value="laptops">Laptops</option>
      <option value="accessories">Accessories</option>
      <option value="appliances">Appliances</option>
      <option value="watches">Watches</option>
      <option value="wallets">Wallets</option>
      <option value="perfumes">Perfumes</option>
      <option value="shirts">Shirts</option>
      <option value="pants">Pants</option>
      <option value="trousers">Trousers</option>
      <option value="shoes">Shoes</option>
      <option value="sweatshirts">Sweatshirts</option>
      <option value="hoodies">Hoodies</option>
      <option value="jackets">Jackets</option>
      <option value="coats">Coats</option>
      <option value="suits">Suits</option>
    </select>
  </div>
  <div class="input-group">
    <label><br>Brand:<br></label>
    <select name="brand">
      <option selected disabled hidden style='display: none' value=''></option>
      <option value="oneplus">OnePlus</option>
      <option value="haier">Haier</option>
      <option value="apple">Apple</option>
      <option value="samsung">Samsung</option>
      <option value="huawei">Huawei</option>
      <option value="oppo">Oppo</option>
      <option value="xiaomi">Xiaomi</option>
      <option value="lg">LG</option>
      <option value="dell">Dell</option>
      <option value="hp">HP</option>
      <option value="lenovo">Lenovo</option>
      <option value="infinix">Infinix</option>
      <option value="armani">Armani</option>
      <option value="cougar">Cougar</option>
      <option value="monark">Monark</option>
      <option value="charcoal">Charcoal</option>
      <option value="active men">Active Men</option>     
    </select>
  </div>
  <br>
  <div class="input-group">
    <button type="submit" class="btn" name="search">Search</button>
  </div>
</form>

<br>

<?php
  if (isset($_POST['search']))
  {
    $db = mysqli_connect('localhost', 'root', '1246', 'estore');

    $keyword = "";
    $category = "";
    $brand = "";
    
    if(!empty($_POST['keyword'])) $keyword = $_POST['keyword'];
    if(!empty($_POST['category'])) $category = $_POST['category'];
    if(!empty($_POST['brand'])) $brand = $_POST['brand'];

    // queries for all possible search combinations
    if(empty($keyword) && empty($category) && empty($brand))
      $query = "select p.productid, p.pname, p.price, p.stock, p.prating, p.picture, p.description, b.bname, c.cname
                from product p join category c on p.categoryid = c.categoryid join brand b on p.brandid = b.brandid";
    else if(!empty($keyword) && empty($category) && empty($brand))
      $query = "select p.productid, p.pname, p.price, p.stock, p.prating, p.picture, p.description, b.bname, c.cname
                from product p join category c on p.categoryid = c.categoryid join brand b on p.brandid = b.brandid
                where p.pname like '%$keyword%'";
    else if(empty($keyword) && !empty($category) && empty($brand))
      $query = "select p.productid, p.pname, p.price, p.stock, p.prating, p.picture, p.description, b.bname, c.cname
                from product p join category c on p.categoryid = c.categoryid join brand b on p.brandid = b.brandid
                where c.cname = '$category'";
    else if(empty($keyword) && empty($category) && !empty($brand))
      $query = "select p.productid, p.pname, p.price, p.stock, p.prating, p.picture, p.description, b.bname, c.cname
                from product p join category c on p.categoryid = c.categoryid join brand b on p.brandid = b.brandid
                where b.bname = '$brand'";
    else if(!empty($keyword) && !empty($category) && empty($brand))
      $query = "select p.productid, p.pname, p.price, p.stock, p.prating, p.picture, p.description, b.bname, c.cname
                from product p join category c on p.categoryid = c.categoryid join brand b on p.brandid = b.brandid
                where p.pname like '%$keyword%' and c.cname = '$category'";
    else if(!empty($keyword) && empty($category) && !empty($brand))
      $query = "select p.productid, p.pname, p.price, p.stock, p.prating, p.picture, p.description, b.bname, c.cname
                from product p join category c on p.categoryid = c.categoryid join brand b on p.brandid = b.brandid
                where p.pname like '%$keyword%' and b.bname = $brand'";
    else if(empty($keyword) && !empty($category) && !empty($brand))
      $query = "select p.productid, p.pname, p.price, p.stock, p.prating, p.picture, p.description, b.bname, c.cname
                from product p join category c on p.categoryid = c.categoryid join brand b on p.brandid = b.brandid
                where c.cname = '$category' and b.bname = '$brand'";
    else if(!empty($keyword) && !empty($category) && !empty($brand))
      $query = "select p.productid, p.pname, p.price, p.stock, p.prating, p.picture, p.description, b.bname, c.cname
                from product p join category c on p.categoryid = c.categoryid join brand b on p.brandid = b.brandid
                where p.pname like '%$keyword%' and c.cname = '$category' and b.bname = '$brand'";

    $result = $db->query($query);

    if($result)
    {
      $num_results = $result->num_rows;
      if($num_results != 0)
      {
        // printing result in tabular form
        echo "<table border = '1'>";
        echo "<tr><td> pname </td><td> price </td><td> stock </td><td> rating </td><td> picture </td><td> description </td><td> bname </td><td> cname </td><td> View Product </td></tr>";
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
        echo "</td><td>".$pname."</td><td>".$price."</td><td>".$stock."</td><td>".$rating."</td><td>".$picture."</td><td>".$description."</td><td>".$bname."</td><td>".$cname."</td><td>";
        ?>
        <a href="<?php echo "$productid.php";?>">View</a>
        <?php
        echo "</td></tr>";
        }
        echo "</table>";
      }

      else
        echo "No result found!";
    }

    else
      echo "No result found!";

    $db->close();

    unset($_POST['search']);
  }
?>
</body>
</html>