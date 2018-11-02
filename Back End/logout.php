<?php
session_start();
  require_once('key.php');
  $_SESSION['isLoggedIn']=false;
  session_destroy();
 ?>
