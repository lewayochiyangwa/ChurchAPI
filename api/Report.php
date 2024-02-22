<?php
/**
 * Created by PhpStorm.
 * User: Pheps
 * Date: 23/11/2015
 * Time: 4:17 AM
 */

class Report implements JsonSerializable{





    public static function updatePop($data, $db) {

        $sql = "EXEC spTransactionRequestUpdatePop "
            . $data["ID"] . ", '"
            . $data["FileName"] . "'";
        //var_dump($sql);
        $result = $db->getAll($sql);
        //var_dump($result);
        $ret["Status"] = "OK";
        $ret["Message"] = "";
        $ret["Data"] = $result;
        return $ret;
    }

    public static function logoupdatePop($data, $db) {

        $sql = "EXEC logoRequestUpdatePop "
            . $data["ID"] . ", '"
            . $data["FileName"] . "'";
        //var_dump($sql);
        $result = $db->getAll($sql);
        //var_dump($result);
        $ret["Status"] = "OK";
        $ret["Message"] = "";
        $ret["Data"] = $result;
        return $ret;
    }

    public function jsonSerialize() {
        return get_object_vars($this);
    }
}
