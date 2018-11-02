<?php
session_start();
ini_set('display_errors', 1);
// ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
require_once('key.php');
require_once('headers.php');
require_once('conn.php');

if (isset($_POST['deleteRoute'])) {
    deleteRoute($_POST['data']);
}

function deleteRoute($data)
{
    $sql = "DELETE FROM Routes WHERE route_ID = '$data[route_ID]'";

    $result = $GLOBALS['conn']->query($sql);

    if ($result) {
        echo json_encode(['status' => 'success']);
    } else {
        echo json_encode(['status' => 'error', 'message' => $GLOBALS['conn']->error]);
    }

    $GLOBALS['conn']->close();
}