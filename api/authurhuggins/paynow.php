<?php
/////////////////////////////////////////////////////////
require_once '../../vendor/autoload.php';
include('config1.php');

$jsonData = file_get_contents('php://input');
$data = json_decode($jsonData, true);

try {

    $amount = trim($data['amount']);
    $phone = trim($data['phone']);
    $description = trim($data['description']);




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
header('Content-Type: application/text');

echo $ret; 