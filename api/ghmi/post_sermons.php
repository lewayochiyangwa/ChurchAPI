<?php
/////////////////////////////////////////////////////////
include('config1.php');

$jsonData = file_get_contents('php://input');
$data = json_decode($jsonData, true);

try {
    $sql = "CALL InsertSermons(?, ?, ?, ?, ?, ?, ?)";
    $stmt = $link->prepare($sql);

    $id = trim($data['id']);
    $verse = trim($data['verse']);
    $sermon_title = trim($data['sermon_title']);
    $description = trim($data['description']);
    $update = trim($data['update']);
    $insert = trim($data['insert']);
    $delete = trim($data['delete']);

    file_put_contents('error_log.txt', "post sermons", FILE_APPEND);

    $stmt->bind_param("isssiii", $id, $verse, $sermon_title, $description, $update, $insert, $delete);

    if ($stmt->execute()) {
        $ret = "Sermons Saved Successfully";
    } else {

        file_put_contents('error_log.txt', "zvaramba", FILE_APPEND);
    }

    $stmt->close();

}catch (Exception $d ){
    file_put_contents('error_log.txt', "zvaramba", FILE_APPEND);
}
header('Content-Type: application/text');

echo $ret; 