<?php
// require("C:\\xampp\\htdocs\\roadmap-server-php-master\\vendor\\autoload.php");
// require("C:\\xampp\\htdocs\\roadmap-server-php-master\\api\\libs\\findOneAndUpdate.php");

// include_once "/vendor/autoload.php";
require_once(dirname(__FILE__) . '\..\..\vendor\autoload.php');
require_once(dirname(__FILE__) . '.\findOneAndUpdate.php');

class Database
{
    private $dbName = "WebCuoiKy";
    private $host = "mongodb://localhost:27017";
    private $conn = NULL;

    public function __construct()
    {
        // $result = NULL;
        // $conn =  new MongoDB\Client();
        // return $conn->selectDatabase($this->dbName);
        $this->conn = (new MongoDB\Client($this->host))->WebCuoiKy;
        return $this->conn;
    }

    public function findListMap()
    {
        $result = $this->conn->maps->find([], ["projection" => ["_id" => 1, "name" => 1, "introduction" => 1]]);
        // echo gettype($result);
        $mapList = array();
        foreach ($result as $map => $val) {
            // echo $val->_id["oid"];
            // echo json_decode(json_encode($val->_id), true)["\$oid"];
            $id = json_decode(json_encode($val->_id), true)["\$oid"];
            // echo $id."<br/>";

            // var_dump($val->_id["oid"]);
            $newMap = new stdClass();
            $newMap->_id = $id;
            $newMap->name = $val->name;
            $newMap->introduction = $val->introduction;


            $mapList[] = $newMap;
        }
        // var_dump($mapList);
        return $mapList;
    }

    public function getMapInfo($mapId)
    {
        // echo $mapId;
        // get object id
        $objMapId = new MongoDB\BSON\ObjectId($mapId);

        $result = $this->conn->maps->findOne(["_id" => $objMapId], ["projection" => ["name" => 1, "description" => 1, "documentation" => 1, "introduction" => 1]]);
        // var_dump($result);

        return $result;
    }

    public function getMapContent($mapId)
    {
        $objMapId = new MongoDB\BSON\ObjectId($mapId);

        $result = $this->conn->users->findOne(["_id" => $objMapId], ["projection" => ["map" => 1]]);
        // var_dump($result);
    }

    public static function update()
    {
    }

    public function findAndUpdate($mapID, $fieldChange, $valueChange)
    {
        $collection = $this->conn->maps;


        // get object id
        $objMapId = new MongoDB\BSON\ObjectId($mapID);

        $result = $collection->findOne(
            ["_id" => $objMapId]
        );

        $map = json_decode($result["map"], true);
        // convert mongo object to array 
        // $result = json_decode(json_encode($result), true);
        // echo gettype($result);
        // var_dump($result);

        // update
        findFieldAndUpdate($map, $fieldChange, $valueChange);
        // var_dump($map);

        $updateResult = $collection->updateOne(
            ['_id' => $objMapId],
            ['$set' => ['map' => json_encode($map)]]
        );

        return true;
    }

    public static function delete()
    {
    }

    public static function insertMapToUser()
    {
    }
}
