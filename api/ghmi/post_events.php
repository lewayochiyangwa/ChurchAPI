<?php
/////////////////////////////////////////////////////////
include('config1.php');

$jsonData = file_get_contents('php://input');
$data = json_decode($jsonData, true);

try {
    $sql = "CALL InsertEventw(?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";

    $stmt = $link->prepare($sql);

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

    $stmt->bind_param("issssssiii", $id, $title, $timeFrom, $timeTo, $description, $location, $event_date, $update, $insert, $delete);

    if ($stmt->execute()) {
        $ret = "Event Saved Successfully";
    } else {
        file_put_contents('error_log.txt', "zvaramba", FILE_APPEND);
    }

    $stmt->close();

    header('Content-Type: application/text');
    echo $ret; //$jsonResponse;
} catch (Exception $e) {
    // Handle any exceptions that occur
    file_put_contents('error_log.txt', $e->getMessage(), FILE_APPEND);
}
?>