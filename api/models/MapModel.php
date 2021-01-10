<?php

require_once(dirname(__FILE__) . '\..\..\vendor\autoload.php');

class MapModel
{
    protected $conn;
    public function __construct($db)
    {
        $this->conn = $db->getConnection();
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
        // $objUserId = new MongoDB\BSON\ObjectId($userId);

        $result = $this->conn->users->findOne(["_id" => $objMapId, "maps.mapId" => $objMapId], ["projection" => ["maps.map" => 1]]);
        var_dump($result);
        // var_export($result);
        // $map = json_decode(json_encode($result->maps), true);
        
        // return $map["map"];
        // $data = new stdClass();
        // $mapObj = json_decode(json_encode($result->maps), true);
        // // var_dump($result->maps);
        // // $data->map = $mapObj;
        // // return $data;
    }

    public function updateMap($jwt, $mapId, $name, $title, $detail, $path, $introduction)
    {
        // convert to mongo object
        $objMapId = new MongoDB\BSON\ObjectId($mapId);

        $collection = $this->conn->maps;
        
        // update map
        $result = $collection->updateOne(
            ["_id" => $objMapId],
            ['$set' => 
                [
                    "name" => $name,
                    "description.tilte" => $title,
                    "description.detail" => $detail,
                    "documentation.path" => $path,
                    "introduction" => $introduction
                ]
            ]
        );

        return $result;
    }

    public function addCommentId($mapId, $commentId)
    {
        // $objMapId = new MongoDB\BSON\ObjectId($mapId);
        $collection = $this->conn->maps;
        return $collection->updateOne(
            ["_id" => $mapId],
            ['$push' => array("comments" => $commentId)]
        );
    }
}
?>