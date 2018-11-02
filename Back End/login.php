<?php
  session_start();
  require_once('key.php');
  require_once('headers.php');

  // header("Access-Control-Allow-Origin: {$_SERVER['HTTP_ORIGIN']}");
  // header('Access-Control-Allow-Credentials: true');
  // header("Access-Control-Allow-Methods: GET, POST, OPTIONS");
  // header("Access-Control-Allow-Headers: {$_SERVER['HTTP_ACCESS_CONTROL_REQUEST_HEADERS']}");

  // ini_set('display_errors', 1);
  // ini_set('display_startup_errors', 1);
  // error_reporting(E_ALL);

  // if(isset($_SESSION['loggedIn']))
  // {
  //   if($_SESSION['loggedIn'] == true)
  //   {
  //     echo "logged in";
  //     exit();
  //   }
  // }

  require_once('conn.php');

  $sql = "SELECT * FROM Users WHERE username='$_POST[username]' AND password='".md5('bus'.$_POST['password'].'alot') ."' LIMIT 1";

  $result = $conn->query($sql);

  if ($result->num_rows > 0) {
      $row = $result->fetch_assoc();
    $_SESSION["loggedIn"] = true;
    $_SESSION["loggedInUserId"] = $row['user_ID'];
    echo 'success';
  } else {
    $_SESSION["loggedIn"] = false;
    echo "unauthenticated";
  }

  $conn->close();
?>
