<?php
if(isset($_SESSION['loggedIn']))
{
  if($_SESSION['loggedIn'] != true)
  {
    echo "<p>You are not logged in! Click <a href='company.html'>here</a> to log in.</p>";
    exit();
  }
}
else {
  echo "<p>You are not logged in! Click <a href='company.html'>here</a> to log in.</p>";
  exit();
}
 ?>
