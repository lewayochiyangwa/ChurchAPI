<?php
/////////////////////////////////////////////////////////
include('config1.php');

$jsonData = file_get_contents('php://input');
$data = json_decode($jsonData, true);
//$church_link = mysqli_connect('localhost', 'root', '', 'church');
  // Prepare the SQL statement
                $sql = "CALL InsertEventw(?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";

// Prepare the statement
                $stmt = $link->prepare($sql);

// Bind the parameter values
                $id = trim($data['id']);
                $title = trim($data['title']);
                $timeFrom = trim($data['from']);
                $timeTo = trim($data['to']);
                $description = trim($data['description']);
                $location = trim($data['location']);
                $event_date = trim($data['event_date']);
                $update =  trim($data['update']);
                $insert =  trim($data['insert']);
                $delete =  trim($data['delete']);
              //  file_put_contents('error_log.txt', "tipei date baba", FILE_APPEND);
             //   file_put_contents('error_log.txt', $event_date, FILE_APPEND);
                $stmt->bind_param("issssssiii", $id, $title, $timeFrom, $timeTo, $description, $location, $event_date, $update, $insert, $delete);

                if ($stmt->execute()) {
                    $ret = "Event Saved Successfully";
                }else{

                    file_put_contents('error_log.txt', "zvaramba", FILE_APPEND);
                }

                $stmt->close();

/*


// Create the response object
$response = new stdClass();
$response->status = "Ok";
$response->code = 200;
$response->message = "Events Posted Successfully";
$response->data = [0]; // Replace $userid with the actual value

// Convert the response object to JSON
$jsonResponse = json_encode($response);
*/
// Set the appropriate headers
header('Content-Type: application/text');

// Return the JSON response
echo $ret; //$jsonResponse;