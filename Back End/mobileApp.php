<?php
header("Access-Control-Allow-Origin: {$_SERVER['HTTP_ORIGIN']}");
header('Access-Control-Allow-Credentials: true');
header("Access-Control-Allow-Methods: GET, POST, OPTIONS");
//header("Access-Control-Allow-Headers: {$_SERVER['HTTP_ACCESS_CONTROL_REQUEST_HEADERS']}");
header("Access-Control-Allow-Headers: *");

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

$_POST = json_decode(file_get_contents('php://input'), true);

require_once('conn.php'); //connection to db

if (isset($_POST['searchRoutes']))
{
    echo searchRoutes($_POST['data']);
}

//Checks to see if there exists such a route
function searchRoutes($data){


    $sql = "SELECT * FROM Routes
INNER JOIN Bus on Routes.bus_ID = Bus.bus_ID
INNER JOIN Company on Bus.company_ID = Company.company_ID
WHERE Routes.start_location LIKE '%$data[origin]%' AND end_location LIKE '%$data[destination]%';";


//    return $sql;
    $result = $GLOBALS['conn']->query($sql);
    $data = [];
    $GLOBALS['conn']->close();

    if(mysqli_num_rows($result) > 0) {
        // output data of each row
        while ($row = $result->fetch_assoc()) {
            $data[] = $row;
        }
        return json_encode(['status' => 'found', 'data' => $data]);
    }
    else
        return json_encode(['status'=>'notFound']);



}

exit;