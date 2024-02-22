<?php
require_once '../vendor/autoload.php';
error_reporting(0);
class ApiReturn {
    public $status;
    public $message;
    public $data;
}

class Client implements JsonSerializable{

//INSERT INTO `tblusers` (`id`, `email`, `password`, `isActive`, `tblpremium_id`) VALUES (NULL, 'leroy@gmai.com', '1234', b'1', '1');



    public static function postEvent($data, $db) {
        $ret = new APIReturn();
        $message="";
        try{

            if (!$db) {
                die("Connection failed: " . mysqli_connect_error());
            }else {
                $title = trim($data['title']);
                $date= trim($data['date']);
                $from= trim($data['from']);
                $to= trim($data['to']);
                $description= trim($data['description']);//mysql_real_escape_string($input_data);
                $location= trim($data['location']);

               // $description2 = mysqli_real_escape_string($description);

                $sql ="INSERT INTO events(title,timeFrom,timeTo,description,location,event_date) 
                       VALUES ('$title','$from','$to','$description','$location','$date');";


                file_put_contents('logger.txt',  $sql."\n", FILE_APPEND);

                if($title!="") {
                    $stmt = $db->prepare($sql);
                    if($stmt->execute()){
                        //  echo "Application  Success";
                        $ret= "Event Saved  Successfully";
                    }
                }

            }

        }catch (Exception $e) {
            $ret->status = "Error";
            $ret->message = $e->getMessage();
            $ret->data = [];
            file_put_contents('error_log.txt', $e->getMessage(), FILE_APPEND);
            return $ret;
        }
        return $ret;
    }
    public static function postEventtest($data, $db) {
        $ret = new APIReturn();
        $message="";
        try{

            if (!$db) {
                die("Connection failed: " . mysqli_connect_error());
            }else {

                // Prepare the SQL statement
                $sql = "CALL InsertEventw(?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";

// Prepare the statement
                $stmt = $db->prepare($sql);

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
                file_put_contents('error_log.txt', "tipei date baba", FILE_APPEND);
                file_put_contents('error_log.txt', $event_date, FILE_APPEND);
                $stmt->bind_param("issssssiii", $id, $title, $timeFrom, $timeTo, $description, $location, $event_date, $update, $insert, $delete);

                if ($stmt->execute()) {
                    $ret = "Event Saved Successfully";
                }else{

                    file_put_contents('error_log.txt', "zvaramba", FILE_APPEND);
                }

                $stmt->close();




            }

        }catch (Exception $e) {
            $ret->status = "Error";
            $ret->message = $e->getMessage();
            $ret->data = [];
            file_put_contents('error_log.txt', $e->getMessage(), FILE_APPEND);
            return $ret;
        }
        return $ret;
    }
    public static function postGive($data, $db) {
        $ret = new APIReturn();
        $message="";
        try{

            if (!$db) {
                die("Connection failed: " . mysqli_connect_error());
            }else {
                $type = trim($data['type']);
                $amount= trim($data['amount']);
                $date= trim($data['valueDate']);
                $device= trim($data['device']);
                $callback= trim($data['callback']);



                $sql ="INSERT INTO onations(type,amount,datez,device,callback_url) 
                       VALUES ('$type','$amount',now(),'$device','$callback');";

                file_put_contents('logger.txt',  $sql."\n", FILE_APPEND);

                $stmt = $db->prepare($sql);
                if($stmt->execute()){
                    //  echo "Application  Success";
                    $ret= "Event Saved  Successfully";
                }

            }

        }catch (Exception $e) {
            $ret->status = "Error";
            $ret->message = $e->getMessage();
            $ret->data = [];
            file_put_contents('error_log.txt', $e->getMessage(), FILE_APPEND);
            return $ret;
        }
        return $ret;
    }

    public static function postSermons($data, $db) {
        $ret = new APIReturn();
        $message="";
        try{

            if (!$db) {
                die("Connection failed: " . mysqli_connect_error());
            }else {

                // Prepare the SQL statement
                $sql = "CALL InsertSermons(?, ?, ?, ?, ?, ?, ?)";
                $stmt = $db->prepare($sql);

                $id = trim($data['id']);
                $verse = trim($data['verse']);
                $sermon_title = trim($data['sermon_title']);
                $description = trim($data['description']);
                $update =  trim($data['update']);
                $insert =  trim($data['insert']);
                $delete =  trim($data['delete']);

                file_put_contents('error_log.txt', "post sermons", FILE_APPEND);

                $stmt->bind_param("isssiii", $id, $verse, $sermon_title, $description, $update, $insert, $delete);

                if ($stmt->execute()) {
                    $ret = "Sermons Saved Successfully";
                }else{

                    file_put_contents('error_log.txt', "zvaramba", FILE_APPEND);
                }

                $stmt->close();




            }

            return $ret;
        }catch (Exception $e) {
            $ret->status = "Error";
            $ret->message = $e->getMessage();
            $ret->data = [];
            file_put_contents('error_log.txt', $e->getMessage(), FILE_APPEND);
            return $ret;
        }
        return $ret;
    }
    public static function params($data, $db) {
        //echo 'ooo';
        $ret = new APIReturn();
        try{

        //   print($data['name']);

            $sql = "SELECT * FROM give_type";
            $location_result = mysqli_query($db, $sql);

            $sql2 = "SELECT * FROM events";
            $events_result = mysqli_query($db, $sql2);



            $data = array();

            $location_array = array();
            $events_array = array();


// Step 3: Loop through the query results and add them to the array
            if (mysqli_num_rows($location_result) > 0) {
                while($row = mysqli_fetch_assoc($location_result)) {
                    $location_array[] = $row;
                }
            }
            if (mysqli_num_rows($events_result) > 0) {
                while($row = mysqli_fetch_assoc($events_result)) {
                    $events_array[] = $row;
                }
            }

            $response = array(
                "status" => "OK",
                "message" => "xxxss Query executed successfully",
                "data"  => array(
                "give" => $location_array,
                "events" => $events_array

            )
            );
            //echo $json;
            return $response;

        } catch (Exception $e) {
            $ret->status = "Error";
            $ret->message = $e->getMessage();
            $ret->data = [];
            return $ret;
        }

    }

    public static function sermons($data, $db) {

        $ret = new APIReturn();
        try{

            $sql = "SELECT * from sermons where deleted <> 1";

            $location_result = mysqli_query($db, $sql);
            $data = array();

            $location_array = array();

            if (mysqli_num_rows($location_result) > 0) {
                while($row = mysqli_fetch_assoc($location_result)) {
                    $location_array[] = $row;
                }
            }


            $response= $location_array;

            return  $response;

        } catch (Exception $e) {
            $ret->status = "Error";
            $ret->message = $e->getMessage();
            $ret->data = [];
            return $ret;
        }

    }

    public static function login($data, $db){
        $ret = new APIReturn();
        define('DB_SERVER', 'localhost');
        define('DB_USERNAME', 'root');
        define('DB_PASSWORD', '');
        define('DB_NAME', 'basamaoko');


        $link = mysqli_connect(DB_SERVER, DB_USERNAME, DB_PASSWORD, DB_NAME);

            $sql = "SELECT id,user_name,password FROM users WHERE user_name = '". $data['email'] . "' AND password = '". $data['password'] ."' AND IsActive = 1" ;
        file_put_contents('error_log.txt', $sql, FILE_APPEND);
    $count=0;
        foreach ($link->query($sql) as $index => $row) {
          //  $userid = $row['id'];
            $userid = intval($row['id']);
            $count=1+$count;

        }
        if($count>0){
            $ret->status = "Ok";
            $ret->code = 200;
            $ret->message ="Log In Successfull";
          $ret->data =[$userid];

            return $ret;
        }else{
            $ret->status = "Error";
            $ret->code = 404;
            $ret->message ="Credentials Wrong";
            $ret->data = [];
            return $ret;
        }


    }

    public static function events($data, $db) {

        $ret = new APIReturn();
        try{

            $sql = "SELECT id as ID,title,timeFrom,timeTo,description,location,event_date from events where deleted<>1";

            $location_result = mysqli_query($db, $sql);
            $data = [];//array();

            $location_array = [];//array();

            if (mysqli_num_rows($location_result) > 0) {
                while($row = mysqli_fetch_assoc($location_result)) {
                    $location_array[] = $row;
                }
            }


           $response= $location_array;


            return  $response;

        } catch (Exception $e) {
            $ret->status = "Error";
            $ret->message = $e->getMessage();
            $ret->data = [];
            return $ret;
        }

    }
    public static function paramsj($data, $db) {
        //echo 'ooo';
        $ret = new APIReturn();
        try{

            //   print($data['name']);

            $sql = "SELECT * FROM give_type";
            $location_result = mysqli_query($db, $sql);

            /*$sql1 = "SELECT * FROM tbljobtypea";
            $jobtypea_result = mysqli_query($db, $sql1);

            $sql2 = "SELECT * FROM tbljobtypeb";
            $jobtypeb_result = mysqli_query($db, $sql2);

            $sql2 = "SELECT * FROM tbljobtypeb";
            $jobtypeb_result = mysqli_query($db, $sql2);

            $sql3 = "SELECT * FROM tbljobtitle";
            $jobtitle_result = mysqli_query($db, $sql3);*/

            $data = array();

            $location_array = array();
            /*$jobtypea_array = array();
            $jobtypeb_array = array();
            $jobtitle_array = array();*/

// Step 3: Loop through the query results and add them to the array
            if (mysqli_num_rows($location_result) > 0) {
                while($row = mysqli_fetch_assoc($location_result)) {
                    $location_array[] = $row;
                }
            }

           /* if (mysqli_num_rows($jobtypea_result) > 0) {
                while($row = mysqli_fetch_assoc($jobtypea_result)) {
                    $jobtypea_array[] = $row;
                }
            }

            if (mysqli_num_rows($jobtypeb_result) > 0) {
                while($row = mysqli_fetch_assoc($jobtypeb_result)) {
                    $jobtypeb_array[] = $row;
                }
            }

            if (mysqli_num_rows($jobtitle_result) > 0) {
                while($row = mysqli_fetch_assoc($jobtitle_result)) {
                    $jobtitle_array[] = $row;
                }
            }*/

            $response = array(
                "status" => "OK",
                "message" => "xxxss Query executed successfully",
                "data"  => array(
                    "locations" => $location_array
                )
            );
            //echo $json;
            return $response;

        } catch (Exception $e) {
            $ret->status = "Error";
            $ret->message = $e->getMessage();
            $ret->data = [];
            return $ret;
        }

    }


    public static function  paynow($data, $db){

        $ret = new APIReturn();
        $message="";
        try {

            if (!$db) {
                die("Connection failed: " . mysqli_connect_error());
            } else {

                $amount = trim($data['amount']);
                $phone = trim($data['phone']);
                $description = trim($data['description']);



                try{
                    file_put_contents('error_log.txt', "try begin", FILE_APPEND);
                    $paynow = new Paynow\Payments\Paynow(
                    ///$paynow = new Paynow(
                        '16938',
                        'cd69ebc4-3386-4ea5-bf38-0ac17bfdefbb',
                        'http://example.com/gateways/paynow/update',

                        // The return url can be set at later stages. You might want to do this if you want to pass data to the return url (like the reference of the transaction)
                        'http://example.com/return?gateway=paynow'
                    );
                    file_put_contents('error_log.txt', "after paynow instance", FILE_APPEND);
                    $payment = $paynow->createPayment($description, 'leroy.chiyangwa1994@gmail.com');

                    $payment->add('loan Repayment', $amount);

                    $response = $paynow->sendMobile($payment, $phone, 'ecocash');
                    $responseString = json_encode($response);
                    file_put_contents('error_log.txt', "within paynow trial", FILE_APPEND);
                    file_put_contents('error_log.txt', "=============Waiting Response==============", FILE_APPEND);
                    file_put_contents('error_log.txt', $responseString, FILE_APPEND);
                  //  appendToFile('logger.txt',$responseString);


                    if($response->success()) {

                        $pollUrl = $response->pollUrl();
                       /* $response = array(
                            "url" => $pollUrl,
                        );*/
                        $ret = $pollUrl;


                    }
                }catch(Exception $ex){
                    
                    file_put_contents('error_log.txt', $ex, FILE_APPEND);
                  //  appendToFile('poll_urls.txt',$query);
                }

            }
        }catch(Exception $ex){
            file_put_contents('error_log.txt', $ex, FILE_APPEND);
           
        }
        return $ret;
    }
    public function jsonSerialize() {
        return get_object_vars($this);
    }
}