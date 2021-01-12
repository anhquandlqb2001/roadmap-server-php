<?php
require_once(dirname(__FILE__) . '/../../vendor/autoload.php');
require_once(dirname(__FILE__) . '/../libs/findOneAndUpdate.php');


class UserModel
{
    protected $conn;
    public function __construct($db)
    {
        $this->conn = $db->getConnection();
    }

    public function login($email, $jwt)
    {
        $userCollection = $this->conn->users;

        $userCollection->updateOne(array(
            "email" => $email
        ), ['$set' => ["jwt" => $jwt]]);

        return $jwt;
    }

    public function logout($id)
    {
        $userCollection = $this->conn->users;
        $objUserId = new MongoDB\BSON\ObjectId($id);
        // var_dump($objUserId);
        
        return $userCollection->updateOne(array(
            "_id" => $objUserId
        ), ['$set' => ["jwt" => ""]]);

    }

    public function getUserList()
    {
        $result = $this->conn->users->find([], ["projection" => ["_id" => 1, "email" => 1, "password" => 1, "provider" => 1, "jwt" => 1]]);

        return $result;
    }

    public function getUserMap($userId)
    {
        $userCollection = $this->conn->users;
        $objUserId = new MongoDB\BSON\ObjectId($userId);

        $result = $userCollection->findOne(["_id" => $objUserId], ["projection" => ["maps" => 1]]);
        return $result;
    }


    public function updateUserProgress($mapID, $userId, $fieldChange, $valueChange)
    {
        $collection = $this->conn->users;


        // get object id
        $objMapId = new MongoDB\BSON\ObjectId($mapID);
        $objUserId = new MongoDB\BSON\ObjectId($userId);
// 
        $result = $collection->findOne(
            ["_id" => $objUserId]
        );

        $userMap = NULL;
        
        foreach($result->maps as $map => $data)
        {
            if($data->mapId == $objMapId)
            {
                $userMaps = $data->map;
            break;
            }
        }

        // var_export($result->maps[0]);

        $map = json_decode($userMaps, true);
        // convert mongo object to array 
        // $result = json_decode(json_encode($result), true);
        // echo gettype($result);
        // var_dump($map);

        // update
        findFieldAndUpdate($map, $fieldChange, $valueChange);
        // var_dump($map);

        $updateResult = $collection->updateOne(
            ['_id' => $objUserId, "maps.mapId" => $objMapId],
            ['$set' => ['maps.$.map' => json_encode($map)]]
        );
        return true;
    }

    public function startMap($mapId, $userId){
        $mapCollection = $this->conn->maps;
        $userCollection = $this->conn->users;
        $objUserId = new MongoDB\BSON\ObjectId($userId);
        $objMapId = new MongoDB\BSON\ObjectId($mapId);

        $user = $userCollection->findOne(["_id"=>$objUserId]);
        $exist = false;

        // echo gettype($user["maps"]);

        foreach($user["maps"] as $key)
        {
            // var_export($key);
            if($key->mapId == $objMapId)
            {
                $exist = true;
                return;
            }
        }

        if($exist == true)
        {
            return false;
        }

        $map = $mapCollection->findOne(["_id"=>$objMapId],["projection" => ["map" => 1]]);
        $newMaps = array();

        foreach($user->maps as $key)
        {
            $newMaps[] = $key;
        }

        $importedMap = array(
            "_id" => new MongoDB\BSON\ObjectId(),
            "mapId" => $map->_id,
            "map" => $map->map
        );

        $newMaps[] = $importedMap;

        // $newMaps[] = $user->maps;
        $userCollection->updateOne(["_id"=>$objUserId],
        [
            '$set'=> ["maps" => $newMaps]
        ]);

        return true;
    }

    public function register($email, $password)
    {
        $userCollection = $this->conn->users;
        
        return $userCollection->insertOne(
            [
                "email" => $email,
                "password" => $password,
                "provider" => "LOCAL",
                "maps" => array() 
            ]
        );
    }

    public function findUserByEmail($email)
    {
        $userCollection = $this->conn->users;
        
        return $userCollection->findOne(["email" => $email]);
    }

    // public function getUserMap($jwt)
    // {

    // }
}

