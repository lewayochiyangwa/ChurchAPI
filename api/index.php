<?php
/*
spl_autoload_register('apiAutoload');
function apiAutoload($classname)
{
    if (preg_match('/[a-zA-Z]+Controller$/', $classname)) {
        include __DIR__ . '/controllers/' . $classname . '.php';
        return true;
    } elseif (preg_match('/[a-zA-Z]+Model$/', $classname)) {
        include __DIR__ . '/models/' . $classname . '.php';
        return true;
    } elseif (preg_match('/[a-zA-Z]+View$/', $classname)) {
        include __DIR__ . '/views/' . $classname . '.php';
        return true;
    }
}*/

ini_set('session.cookie_domain', '.datvest.com' );

session_start();


include "Request.php";
include "User.php";
include "Client.php";
include "Report.php";

//include_once(__DIR__."/lib/adodb/adodb.inc.php"); // database library

$request = new Request();

//var_dump($request);
//include_once(__DIR__."/lib/adodb/adodb-exceptions.inc.php"); // database library
/*
$driver = 'mssqlnative';
$serverName = '.';
$user = 'debugger';
$password = 'bug1';
$database = 'GSAMUP';







$driver = 'mssqlnative';
//$ADODB_FETCH_MODE = ADODB_FETCH_ASSOC;
$db = adoNewConnection($driver); # eg. 'mysqli' or 'oci8'
$db->debug = false;
if(!$db->connect($serverName, $user, $password, $database)) {
    print $db->ErrorMsg();
    die;
};

*/

define('DB_SERVER', 'localhost');
define('DB_USERNAME', 'root');
define('DB_PASSWORD', '');
define('DB_NAME', 'church');

/* Attempt to connect to MySQL database */
$link = mysqli_connect(DB_SERVER, DB_USERNAME, DB_PASSWORD, DB_NAME);

if(1==9){}
else if ($request->url_elements[3] == 'user') {

    switch ($request->url_elements[4]) {
        case 'login':
            $results = User::logIn($request->parameters);
            echo json_encode($results);
            break;
        default:
            logUsage($request->url_elements, $request->parameters);
    }

}
else if ($request->url_elements[3] == 'client') {
    switch ($request->url_elements[4]) {
        case 'post_event':
            $results = Client::postEventtest($request->parameters, $link);
            echo json_encode($results);
            break;
        case 'paynow':
            $results = Client::paynow($request->parameters, $link);
            echo json_encode($results);
            break;
        case 'post_sermons':
            $results = Client::postSermons($request->parameters, $link);
            echo json_encode($results);
            break;
        case 'sermons':
            $results = Client::sermons($request->parameters, $link);
            // print(sizeof($request->parameters));
            echo json_encode($results);
            break;
        case 'give':
            $results = Client::postGive($request->parameters, $link);
            // print(sizeof($request->parameters));
            echo json_encode($results);
            break;
        case 'params':
            $results = Client::params($request->parameters, $link);
           // print(sizeof($request->parameters));
            echo json_encode($results);
            break;
        case 'events':
            $results = Client::events($request->parameters, $link);
            // print(sizeof($request->parameters));
            echo json_encode($results);
            break;
        case 'paramsj':
            print('mambure');
            define('DB_SERVER', 'localhost');
            define('DB_USERNAME', 'root');
            define('DB_PASSWORD', '');
            define('DB_NAME', 'church');

            /* Attempt to connect to MySQL database */
            $linkw = mysqli_connect('localhost', 'root', '', 'church');
            $results = Client::paramsj($request->parameters, $linkw);
            // print(sizeof($request->parameters));
            echo json_encode($results);
            break;
        case 'recent_posts':
            $ret = Client::recentPosts($request->parameters, $link);
           // var_dump($ret);
            echo json_encode(utf8ize($ret));
            break;
        case 'login':
            $ret = Client::login($request->parameters, $link);
            // var_dump($ret);
            echo json_encode(utf8ize($ret));
            break;
        case 'sent_apply':
            $ret = Client::sent_apply($request->parameters, $link);
            // var_dump($ret);
            echo json_encode(utf8ize($ret));
            break;

        case 'jobs':
            $ret = Client::jobs($request->parameters, $link);
            // var_dump($ret);
            echo json_encode(utf8ize($ret));
            break;

        default:
            logUsage($request->url_elements, $request->parameters);
    }
}

else {
    logUsage($request->url_elements, $request->parameters);
}

function logUsage($req, $param) {
    return false;
}

function getRealIpAddr()
{
    if (!empty($_SERVER['HTTP_CLIENT_IP']))   //check ip from share internet
    {
        $ip=$_SERVER['HTTP_CLIENT_IP'];
    }
    elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR']))   //to check ip is pass from proxy
    {
        $ip=$_SERVER['HTTP_X_FORWARDED_FOR'];
    }
    else
    {
        $ip=$_SERVER['REMOTE_ADDR'];
    }
    return $ip;
}

function utf8ize($d) {
    if (is_array($d)) {
        foreach ($d as $k => $v) {
            $d[$k] = utf8ize($v);
        }
    } else if (is_string ($d)) {
        return utf8_encode($d);
    }
    return $d;
}

/*$controller_name = ucfirst($request->url_elements[3]) . 'Controller';
$model_name = ucfirst($request->url_elements[4]) . 'Model';
if (class_exists($controller_name)) {
    if (isset($request->parameters["AccessKey"])) {
        $model_name = new $model_name($request->parameters["AccessKey"]);
        $controller = new $controller_name($request->parameters["AccessKey"]);
        $action_name = strtolower($request->verb) . 'Action';
        if ($controller->valid) {
            $result = $controller->$action_name($request);
            //print_r($result);
        } else {
            $result["error"] = "Invalid access key";
        }
    } else {
        $result["params"] = $request->parameters;
        $result["error"] = "Invalid access key Top";
    }
    $view_name = ucfirst($request->format) . 'View';
    if(class_exists($view_name)) {
        $view = new $view_name();
        $view->render($result);
    }
}*/

