<?php
include('config1.php');

try {
    $sql = "SELECT * from sermons where deleted <> 1";

    $location_result = mysqli_query($link, $sql);
    $data = [];

    if (mysqli_num_rows($location_result) > 0) {
        while ($row = mysqli_fetch_assoc($location_result)) {
            $data[] = $row;
        }
    }

 //   $response = json_encode($data);
$response = json_encode(array_map('utf8_encode', $data));
    header('Content-Type: application/json');

    echo $response;
} catch (Exception $e) {
    $ret = new stdClass();
    $ret->status = "Error";
    $ret->message = $e->getMessage();
    $ret->data = [];

    $response = json_encode($ret);

    header('Content-Type: application/json');
    echo $response;
}
?>