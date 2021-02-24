<?php
session_start();

// initializing variables
$username = "";
$email    = "";
$errors = array(); 

// connect to the database
$db = mysqli_connect('localhost', 'root', '1246', 'estore');

// register for admin
if (isset($_POST['reg_adm'])) {
  // receive all input values from the form
  $fname = mysqli_real_escape_string($db, $_POST['fname']);
  $lname = mysqli_real_escape_string($db, $_POST['lname']);
  $username = mysqli_real_escape_string($db, $_POST['username']);
  $email = mysqli_real_escape_string($db, $_POST['email']);
  $password_1 = mysqli_real_escape_string($db, $_POST['password_1']);
  $password_2 = mysqli_real_escape_string($db, $_POST['password_2']);

  // ensure that the form is correctly filled by adding (array_push()) corresponding error unto $errors array
  if (empty($fname)) { array_push($errors, "First name is required"); }
  if (empty($lname)) { array_push($errors, "Last name is required"); }
  if (empty($username)) { array_push($errors, "Username is required"); }
  if (empty($email)) { array_push($errors, "Email is required"); }
  if (empty($password_1)) { array_push($errors, "Password is required"); }
  if ($password_1 != $password_2) {
  array_push($errors, "The two passwords do not match");
  }

  // first check the database to make sure a user does not already exist with the same username and/or email
  $user_check_query = "select * from admin where username='$username' or email='$email' limit 1";
  $result = mysqli_query($db, $user_check_query);
  $user = mysqli_fetch_assoc($result);
  
  if ($user) { // if user exists
    if ($user['username'] === $username) {
      array_push($errors, "Username already exists");
    }

    if ($user['email'] === $email) {
      array_push($errors, "Email already exists");
    }
  }

  // Finally, register user if there are no errors in the form
  if (count($errors) == 0) {
    $password = $password_1;

    $query = "insert into admin (username, email, ipassword, fname, lname) 
          values('$username', '$email', '$password', '$fname', '$lname')";
    mysqli_query($db, $query);
    $_SESSION['username'] = $username;
    $_SESSION['success'] = "You are now logged in";
    header('location: admin.php');
  }
}

// register for customer
if (isset($_POST['reg_cst'])) {
  // receive all input values from the form
  $fname = mysqli_real_escape_string($db, $_POST['fname']);
  $lname = mysqli_real_escape_string($db, $_POST['lname']);
  $username = mysqli_real_escape_string($db, $_POST['username']);
  $email = mysqli_real_escape_string($db, $_POST['email']);
  $password_1 = mysqli_real_escape_string($db, $_POST['password_1']);
  $password_2 = mysqli_real_escape_string($db, $_POST['password_2']);
  $birthday = "";
  $age = "";
  $gender = "";

  if(!empty($_POST['gender']))
  {
    $gender = mysqli_real_escape_string($db, $_POST['gender']);
  }

  if(!empty($_POST['birthday']))
  {
    $birthday = mysqli_real_escape_string($db, $_POST['birthday']);
    // calculate age from birthday
    $bday = new Datetime($birthday);
    $today = new Datetime(date('m.d.y'));
    $diff = $today->diff($bday);
    $age = $diff->y;
  }

  // ensure that the form is correctly filled by adding (array_push()) corresponding error unto $errors array
  if (empty($fname)) { array_push($errors, "First name is required"); }
  if (empty($lname)) { array_push($errors, "Last name is required"); }
  if (empty($username)) { array_push($errors, "Username is required"); }
  if (empty($email)) { array_push($errors, "Email is required"); }
  if (empty($password_1)) { array_push($errors, "Password is required"); }
  if (empty($birthday)) { array_push($errors, "Birthday is required"); }
  if (empty($gender)) { array_push($errors, "Gender is required"); }
  if ($password_1 != $password_2) {
  array_push($errors, "The two passwords do not match");
  }

  // first check the database to make sure a user does not already exist with the same username and/or email
  $user_check_query = "select * from customer where username='$username' or email='$email' limit 1";
  $result = mysqli_query($db, $user_check_query);
  $user = mysqli_fetch_assoc($result);
  
  if ($user) { // if user exists
    if ($user['username'] === $username) {
      array_push($errors, "Username already exists");
    }

    if ($user['email'] === $email) {
      array_push($errors, "Email already exists");
    }
  }

  // Finally, register user if there are no errors in the form
  if (count($errors) == 0) {
    $password = $password_1;

    $query = "insert into customer (username, email, ipassword, fname, lname, birthday, age, gender) 
          VALUES('$username', '$email', '$password', '$fname', '$lname', '$birthday', '$age', '$gender')";
    mysqli_query($db, $query);
    $_SESSION['username'] = $username;
    $_SESSION['success'] = "You are now logged in";
    header('location: customer.php');
  }
}

// login for admin
if (isset($_POST['log_adm'])) {
  $username = mysqli_real_escape_string($db, $_POST['username']);
  $password = mysqli_real_escape_string($db, $_POST['password']);

  if (empty($username)) {
    array_push($errors, "Username is required");
  }
  if (empty($password)) {
    array_push($errors, "Password is required");
  }

  // if no error in user input
  if (count($errors) == 0) {
    $query = "select * from admin where username='$username' and ipassword='$password'";
    $results = mysqli_query($db, $query);
    if (mysqli_num_rows($results) == 1) { //if no duplicate exists
      $_SESSION['username'] = $username;
      $_SESSION['success'] = "You are now logged in";
      header('location: admin.php');
    }else {
      array_push($errors, "Wrong username/password combination");
    }
  }
}

// login for customer
if (isset($_POST['log_cst'])) {
  $username = mysqli_real_escape_string($db, $_POST['username']);
  $password = mysqli_real_escape_string($db, $_POST['password']);

  if (empty($username)) {
    array_push($errors, "Username is required");
  }
  if (empty($password)) {
    array_push($errors, "Password is required");
  }

  // if no error in user input
  if (count($errors) == 0) {
    $query = "select * from customer where username='$username' and ipassword='$password'";
    $results = mysqli_query($db, $query);

    if (mysqli_num_rows($results) == 1) { //if no duplicate exists
      $_SESSION['username'] = $username;
      $_SESSION['success'] = "You are now logged in";
      header('location: customer.php');
    }else {
      array_push($errors, "Wrong username/password combination");
    }
  }
}

?>