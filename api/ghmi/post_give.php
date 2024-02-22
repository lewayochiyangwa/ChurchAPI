<?php
/////////////////////////////////////////////////////////
include('config1.php');

$jsonData = file_get_contents('php://input');
$data = json_decode($jsonData, true);

try {
    $type = trim($data['type']);
    $amount= trim($data['amount']);
    $date= trim($data['valueDate']);
    $device= trim($data['device']);
    $callback= trim($data['callback']);



    $sql ="INSERT INTO onations(type,amount,datez,device,callback_url) 
                       VALUES ('$type','$amount',now(),'$device','$callback');";

    file_put_contents('logger.txt',  $sql."\n", FILE_APPEND);

    $stmt = $link->prepare($sql);
    if($stmt->execute()){
        //  echo "Application  Success";
        $ret= "Donation Saved  Successfully";
    }



}catch (Exception $d ){
    file_put_contents('error_log.txt', "zvaramba", FILE_APPEND);
}
header('Content-Type: application/text');

echo $ret; 