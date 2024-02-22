<?php
/////////////////////////////////////////////////////////
include('config1.php');

$jsonData = file_get_contents('php://input');
$data = json_decode($jsonData, true);
// Create the response object
$response = new stdClass();


$userid ="";
$sql = "SELECT id,user_name,password FROM users WHERE user_name = '". $data['email'] . "' AND password = '". $data['password'] ."' AND IsActive = 1" ;
file_put_contents('error_log.txt', $sql, FILE_APPEND);
$count=0;
foreach ($link->query($sql) as $index => $row) {
    //  $userid = $row['id'];
    $userid = intval($row['id']);
    $count=1+$count;

}
if($count>0){
    $response->status = "Ok";
    $response->code = 200;
    $response->message = "Log In Successful";
    $response->data = [$userid]; // Replace $userid with the actual value

  //  return $ret;
}else{
    $response->status = "Error";
    $response->code = 404;
    $response->message = "Log In Error";
    $response->data = [$userid]; // Replace $userid with the actual value
}





// Convert the response object to JSON
$jsonResponse = json_encode($response);

// Set the appropriate headers
header('Content-Type: application/json');

// Return the JSON response
echo $jsonResponse;