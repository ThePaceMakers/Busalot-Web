<?php
session_start();

//import important php files
require_once('key.php'); //Validate accsess key
require_once('headers.php'); //CORS headers
require_once('conn.php'); //connection to db

//Call the relevant method from the ajax call
if (isset($_POST['getCompanyDetails']))
{
    echo getCompanyDetails();
}
if (isset($_POST['searchRoutes']))
{
    echo searchRoutes($_POST['data']);
}
if (isset($_POST['getAllCompanyRoutes']))
{
    echo getAllCompanyRoutes();
}
if (isset($_POST['getCompanyBusses']))
{
    echo getCompanyBusses();
}

//Returns the details of the company where the user is logged in
function getCompanyDetails(){
    $sql = "SELECT * FROM Users  INNER JOIN Company ON Users.company_ID = Company.company_ID WHERE user_ID = '$_SESSION[loggedInUserId]';";

    $result = $GLOBALS['conn']->query($sql);

    $GLOBALS['conn']->close();

    $row = $result->fetch_assoc();

    return json_encode(['data'=>$row]);
}

//Get the busses that the company owns
function getCompanyBusses(){

    $sql = "SELECT company_ID FROM Users WHERE user_ID = $_SESSION[loggedInUserId]";
    $result = $GLOBALS['conn']->query($sql);
    $row = $result->fetch_assoc();
    $companyID = $row['company_ID'];

    $sql = "SELECT * FROM Bus WHERE company_ID=$companyID;";
    $result = $GLOBALS['conn']->query($sql);
    $data = [];

    if(mysqli_num_rows($result) > 0)
    {
        // output data of each row
        while($row = $result->fetch_assoc()) {
            $data[] = $row;
        }
        return json_encode(['status'=>'found', 'data'=>$data]);
    }
    else
        return json_encode(['status'=>'notFound']);