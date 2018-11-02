<?php
session_start();
ini_set('display_errors', 1);
// ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
require_once('key.php');
require_once('headers.php');
require_once('conn.php');

//Check which function to call
if (isset($_POST['saveCompanyDetails'])) {
    saveCompanyDetails($_POST['data']);
}

if (isset($_POST['registerCompany'])) {
    registerCompany($_POST['data']);
}

if (isset($_POST['saveNewRoute'])) {
    echo saveNewRoute($_POST['data']);
}

if (isset($_POST['createBus'])) {
    echo createBus($_POST['data']);
}

function saveCompanyDetails($data)
{
    $sql = "UPDATE Company SET 
name='$data[name]', email='$data[email]', telephone='$data[telephone]', website='$data[website]', address='$data[address]',city='$data[city]' 
WHERE company_ID = (SELECT company_ID FROM Users WHERE user_ID = '1');";

    $result = $GLOBALS['conn']->query($sql);

    if ($result) {
        echo json_encode(['status' => 'success']);
    } else {
        echo json_encode(['status' => 'error', 'message' => $GLOBALS['conn']->error]);
    }

    $GLOBALS['conn']->close();
}

function registerCompany($data)
{
    $sql = "SELECT * FROM Users WHERE username='$data[username]' LIMIT 1";

    $result = $GLOBALS['conn']->query($sql);

    //If username found, return with message 'exists'
    if ($result->num_rows > 0) {
        echo json_encode(['status' => 'exists']);
        exit;
    }

    $sql = "SELECT * FROM Company WHERE name='$data[name]' LIMIT 1";

    $result = $GLOBALS['conn']->query($sql);

    //If company found, return with message 'exists'
    if ($result->num_rows > 0) {
        echo json_encode(['status' => 'exists']);
        exit;
    }


    $sql = "INSERT INTO Company (name,email,website,address,telephone) VALUES (
'$data[name]',
'$data[email]',
'$data[website]',
'$data[address]',
'$data[telephone]'
)";

    $result = $GLOBALS['conn']->query($sql);

    if ($result) {
        $sql = "INSERT INTO Users (username,password,company_ID) VALUES (
'$data[username]',
'" . md5('bus' . $data['password'] . 'alot') . "',
'".$GLOBALS['conn']->insert_id."'
)";

        $result = $GLOBALS['conn']->query($sql);

        if($result)
            echo json_encode(['status' => 'success']);
        else
            echo json_encode(['status' => 'error', 'message' => $GLOBALS['conn']->error]);
    } else {
        echo json_encode(['status' => 'error', 'message' => $GLOBALS['conn']->error]);
    }


    $GLOBALS['conn']->close();
}

function saveNewRoute($data)
{
    $sql = "INSERT INTO Routes (start_location, end_location, price, bus_ID) VALUES (
'$data[start_location]', '$data[end_location]', '$data[price]', '$data[bus_ID]'
);";

    $result = $GLOBALS['conn']->query($sql);

    if ($result) {
        $last_id = $GLOBALS['conn']->insert_id;

        $sql = "SELECT * FROM Routes INNER JOIN Bus ON Routes.bus_ID = Bus.bus_ID WHERE route_ID='$last_id'";

        $result = $GLOBALS['conn']->query($sql);

        $row = $result->fetch_assoc();

        return json_encode(['status' => 'success','data'=>$row]);
    } else {
        return json_encode(['status' => 'error', 'message' => $GLOBALS['conn']->error]);
    }

    $GLOBALS['conn']->close();
}

function createBus($data)
{
    $sql = "SELECT company_ID FROM Users WHERE user_ID = $_SESSION[loggedInUserId]";
    $result = $GLOBALS['conn']->query($sql);
    $row = $result->fetch_assoc();
    $companyID = $row['company_ID'];

    $sql = "INSERT INTO Bus (name, trailer_option, rating, size, disability, company_ID) VALUES (
'$data[name]', '$data[trailer_option]', '$data[rating]', '$data[size]', '$data[disability]','$companyID'
);";

    $result = $GLOBALS['conn']->query($sql);

    if ($result) {
        return json_encode(['status' => 'success']);
    } else {
        return json_encode(['status' => 'error', 'message' => $GLOBALS['conn']->error]);
    }

    $GLOBALS['conn']->close();
}