<?php
session_start();

// initializing variables
$userID = "";
$email    = "";
$errors = array(); 

// connect to the database
$db = mysqli_connect('localhost', 'root', '', 'test') or die("Connect failed: %s\n". $db -> error);

// REGISTER USER
if (isset($_POST['reg_user'])) {
  // receive all input values from the form
  $userID = mysqli_real_escape_string($db, $_POST['userID']);
  $email = mysqli_real_escape_string($db, $_POST['email']);
  $password_1 = mysqli_real_escape_string($db, $_POST['password_1']);
  $password_2 = mysqli_real_escape_string($db, $_POST['password_2']);

  // form validation: ensure that the form is correctly filled ...
  // by adding (array_push()) corresponding error unto $errors array
  if (empty($userID)) { array_push($errors, "Username is required"); }
  if (empty($email)) { array_push($errors, "Email is required"); }
  if (empty($password_1)) { array_push($errors, "Password is required"); }
  if ($password_1 != $password_2) {
	  array_push($errors, "The two passwords do not match");
  }

  // first check the database to make sure 
  // a user does not already exist with the same username and/or email
  $user_check_query = "SELECT * FROM `login` WHERE `ID`='$userID' OR email='$email' LIMIT 1";
  $result = mysqli_query($db, $user_check_query);
  $user = mysqli_fetch_assoc($result);
  
  if ($user) { // if user exists
    if ($user['ID'] === $userID) {
      array_push($errors, "Username already exists");
    }

    if ($user['email'] === $email) {
      array_push($errors, "email already exists");
    }
  }
    /*echo '<script type="text/javascript">',
    'console.log("in server, count errors:'.count($errors).'")',
    '</script>'
    ;
    */

  // Finally, register user if there are no errors in the form
  if (count($errors) == 0) {
  	$password = md5($password_1);//encrypt the password before saving in the database
  	$query = "INSERT INTO `login`(`ID`, `email`, `password`) VALUES('$userID', '$email', '$password')";
  	mysqli_query($db, $query);
  	$_SESSION['userID'] = $userID;
  	$_SESSION['msg'] = "You are now logged in, ".$userID;
  	header('location: ../index.php');
  }
}


// LOGIN USER
if (isset($_POST['login_user'])) {
    $userID = mysqli_real_escape_string($db, $_POST['userID']);
    $password = mysqli_real_escape_string($db, $_POST['password']);
  
    if (empty($userID)) {
        array_push($errors, "Username is required");
    }
    if (empty($password)) {
        array_push($errors, "Password is required");
    }
  
    if (count($errors) == 0) {
        $password = md5($password);
        $query = "SELECT * FROM `login` WHERE `ID`='$userID' AND `password`='$password'";
        $results = mysqli_query($db, $query);
        if (mysqli_num_rows($results) == 1) {
          $_SESSION['userID'] = $userID;
          $_SESSION['msg'] = "You are now logged in, ".$userID;
          header('location: ../index.php');
        }else {
            array_push($errors, "Wrong username/password combination");
        }
    }
  }
?>