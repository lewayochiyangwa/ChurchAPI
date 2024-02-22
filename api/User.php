<?php
/**
 * Created by PhpStorm.
 * User: Pheps
 * Date: 23/11/2015
 * Time: 4:17 AM
 */

class User implements JsonSerializable{
    private $id;
    private $name;
    private $Email;
    private $active;
    private $created;
    private $accessToken;

    public function getUser ($id) {

        $query = DB::query("
          SELECT usr.ID,
                 IFNULL(usr.Name, '') AS Name,
                 IFNULL(usr.Email, '') AS Email,
                 DATE_FORMAT(usr.Created, %s) AS Created,
                 usr.AccessToken,
                 usr.Active
          FROM `user` usr
          WHERE usr.ID = %i",'%d %b %Y','%d %b %Y %l:%i:%s%p', $id);

        if (sizeof($query) == 1) {
            $this->id = $query[0]["ID"];
            $this->name = $query[0]["Name"];
            $this->Email = $query[0]["Email"];
            $this->created = $query[0]["Created"];
            $this->accessToken = $query[0]["AccessToken"];
            $this->active = $query[0]["Active"];

            return true;
        } else {
            return false;
        }
    }

    public function __construct($ID) {
        $this->getUser($ID);
    }

    public static function create(&$data) {

        /*if (isset($data["Source"]) && $data["Source"] == 'App') {
            $password = User::generateRandomString(6);
            $userName = ($data["Email"] != "" ? $data["Email"] : $data["Phone"]);
        } else {
            $password = $data["Password"];
            $userName = $data["UserName"];
        }*/
        $accessToken = $token = hash('sha512', uniqid(openssl_random_pseudo_bytes(16), TRUE));;

        $query = DB::query("SELECT ID FROM `user` WHERE Email = %s",$data["Email"]);
        if (sizeof($query) > 0) {
            $data["message"] = "This email is already in use.";
            return 0;
        };

        $query = DB::insertUpdate(
            'user', array(
                'Password' => hash("sha512", $data["Password"]),
                'Name' => $data["Name"],
                'Email' => $data["Email"],
                'Created' => date("Y-m-d H:i:s"),
                'AccessToken' => $accessToken,
                'Active' => 1
            )
        );

        if (DB::affectedRows() != 0) {
            $id = DB::insertId();

            $data["message"] = "User created";
            User::logIn($data);
            return $id;
            /*$query = DB::query("SELECT Created FROM user WHERE ID = %i", $id);
            if (sizeof($query) == 1) {
                $access_key = hash("sha512", $data["user_name"] . $data["password"] . $query[0]["Created"]);
                $query = DB::query("UPDATE user SET AccessKey = %s WHERE ID = %i", $access_key, $id);
                return $id;
            }*/
        } else {
            $data["message"] = "Could not create user.";
            return 0;
        }
    }

    public static function updateUser($data, $id) {

        //var_dump($data);
        $query = DB::update(
            'user', array(
                'Name' => $data["Name"],
                'Active' => $data["Active"]
            ), "ID = %i", $data["ID"]
        );
        if (DB::affectedRows() != 0) {

            return $id;
            /*$query = DB::query("SELECT Created FROM user WHERE ID = %i", $id);
            if (sizeof($query) == 1) {
                $access_key = hash("sha512", $data["user_name"] . $data["password"] . $query[0]["Created"]);
                $query = DB::query("UPDATE user SET AccessKey = %s WHERE ID = %i", $access_key, $id);
                return $id;
            }*/
        } else {
            return 0;
        }
    }

    public static function logIn ($data) {

        $result = [];
        //$token = hash('sha512', uniqid(openssl_random_pseudo_bytes(16), TRUE));

        $query = DB::query("
            SELECT ID, `Name`, Email, AccessToken, Active, Created
            FROM `user`
            WHERE Email = %s AND Password = %s
            ", $data["Email"], hash("sha512", $data["Password"]));

        if (sizeof($query) == 1) {
            if ($query[0]["Active"] == 0) {
                $result["Message"] = "User is inactive";
                $result["Status"] = "Error";
                $result["Data"] = -1;
               // var_dump($query);
                return $result;
            } else {

              //  var_dump($query);
                $_SESSION["ID"] = $query[0]["ID"];
                $_SESSION["Name"] = $query[0]["Name"];
                $_SESSION["Email"] = $query[0]["Email"];
                $_SESSION["AccessToken"] = $query[0]["AccessToken"];
                $_SESSION["Active"] = $query[0]["Active"];
                $_SESSION["Created"] = $query[0]["Created"];
                $_SESSION["IsAdmin"] = 0;

                $result["Message"] = "Login succeded";
                $result["Status"] = "OK";
                $result["Data"] = $query;
                return $result;
            }
        } else {
            $result["Message"] = "Invalid email or password";
            $result["Status"] = "Error";
            $result["Data"] = 0;
            return $result;
        }
    }

    public static function logInOauth ($data) {

        User::create($data);
        $ret = User::logIn($data);
        return $ret;
    }

    public static function changePwd ($data, $userID) {

        if (isset($data["Token"])) {
            $query = DB::query("SELECT ID, ResetExpiry FROM tbluser WHERE RestToken = %s", $data["Token"]);
        } else {
            $query = DB::query("SELECT ID FROM tbluser WHERE ID = %i AND Password = %s", $userID, hash("sha512", $data["Old"]));
        }

        if (sizeof($query) == 1) {
            if (isset($query[0]["ResetExpiry"])){
                if($query[0]["ResetExpiry"] < date("Y-m-d h:i")){
                    return "Resest Password token has expired. Get a new one by clicking 'Forgot Password' from the login page.";
                }
            }
            DB::update(
                'tbluser', array(
                    'Password' => hash("sha512", $data["Password"])
                ), 'ID=%i', $userID
            );
            return $query[0]["ID"];
        } else {
            return "Could not change password.";
        }
    }

    public static function userList($data) {
        $query = DB::query("
          SELECT usr.ID,
                 IFNULL(usr.Name, '') AS Name,
                 IFNULL(usr.Email, '') AS Email,
                 DATE_FORMAT(usr.Created, %s) AS Created,
                 usr.Active
          FROM user usr
          WHERE (usr.Name LIKE (%ss)
          OR usr.Email LIKE (%ss))
          ORDER BY usr.Name
          LIMIT 200", '%d %b %Y %l:%i:%s %p','%d %b %Y %l:%i:%s %p', $data["keywords"], $data["keywords"]);
        return $query;
    }

    static function generateRandomString($length = 10) {
        $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $charactersLength = strlen($characters);
        $randomString = '';
        for ($i = 0; $i < $length; $i++) {
            $randomString .= $characters[rand(0, $charactersLength - 1)];
        }
        return $randomString;
    }

    public function jsonSerialize() {
        return get_object_vars($this);
    }
}