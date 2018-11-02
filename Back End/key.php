<?php
//require_once('headers.php');
$key = '1234';

if($_SERVER['REQUEST_METHOD'] == 'POST')
{
  //no key specified
  if(!isset($_POST['key']))
  {
    echo 'Key not specified: ' . json_encode($_POST);
    exit;
  }
  else {
    //keys do not match
    if($_POST['key'] != $key)
    {
      echo 'Invalid key';
      exit;
    }
  }
}
?>
