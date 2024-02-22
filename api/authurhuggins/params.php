<?php
include('config1.php');

class Response {
    public $status;
    public $message;
    public $data;

    public function __construct($status, $message, $data) {
        $this->status = $status;
        $this->message = $message;
        $this->data = $data;
    }
}

try {
    $sql = "SELECT * FROM give_type";
    $location_result = mysqli_query($link, $sql);

    $sql2 = "SELECT * FROM events";
    $events_result = mysqli_query($link, $sql2);

    $location_array = [];
    $events_array = [];

    if (mysqli_num_rows($location_result) > 0) {
        while ($row = mysqli_fetch_assoc($location_result)) {
            $location_array[] = $row;
        }
    }
    if (mysqli_num_rows($events_result) > 0) {
        while ($row = mysqli_fetch_assoc($events_result)) {
            $events_array[] = $row;
        }
    }

    $response = new Response(
        "OK",
        "Query executed successfully",
        [
            "give" => $location_array,
            "events" => $events_array
        ]
    );

    header('Content-Type: application/json');
    echo json_encode($response);
} catch (Exception $e) {
    $response = new Response(
        "Error",
        $e->getMessage(),
        []
    );

    header('Content-Type: application/json');
    echo json_encode($response);
}
?>