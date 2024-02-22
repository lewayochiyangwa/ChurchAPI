<?php
/////////////////////////////////////////////////////////
//include('config1.php');
class ApiReturn {
    public $status;
    public $message;
    public $data;
}

define('DB_SERVER', 'localhost');
define('DB_USERNAME', 'root');
define('DB_PASSWORD', '');
define('DB_NAME', 'church');
$church_link = mysqli_connect('localhost', 'root', '', 'church');
  function params($data, $db) {
    print 'hmmm';
    $ret = new APIReturn();
    try{
        print('hhhh');

        //   print($data['name']);

        $sql = "SELECT * FROM give_type";
        $location_result = mysqli_query($db, $sql);



        $data = array();

        $location_array = array();


// Step 3: Loop through the query results and add them to the array
        if (mysqli_num_rows($location_result) > 0) {
            print("greater");
            while($row = mysqli_fetch_assoc($location_result)) {
                $location_array[] = $row;

            }
        }



        $response = array(
            "status" => "OK",
            "message" => "xxxss Query executed successfully",
            "data"  => array(
                "locations" => $location_array
                /*"jobTypesA" => $jobtypea_array,
                    "jobTypesB"=>$jobtypeb_array,
                    "jobTitle"=>$jobtitle_array,*/
            )
        );
       // echo $response;
        return $response;

    } catch (Exception $e) {
        $ret->status = "Error";
        $ret->message = $e->getMessage();
        $ret->data = [];
        return $ret;
    }

}
if($church_link)
{
    params("h",$church_link);
    echo "Connection established.\n";
}
else
{
    //echo "Connection could not be established.\n";
    die("Failed");
}

