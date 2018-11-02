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

//To display a table for the admin user with all the bus routes including the bus
function getAllCompanyRoutes()
{
    $sql = "SELECT company_ID FROM Users WHERE user_ID = $_SESSION[loggedInUserId]";
    $result = $GLOBALS['conn']->query($sql);
    $row = $result->fetch_assoc();
    $companyID = $row['company_ID'];

    $sql = "SELECT * FROM Routes INNER JOIN Bus ON Routes.bus_ID = Bus.bus_ID WHERE company_ID=$companyID;";
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

}



?>