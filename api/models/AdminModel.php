<?php
require_once(dirname(__FILE__) . '\..\..\vendor\autoload.php');

class AdminModel
{
    public $conn;
    public function __construct($db)
    {
        $this->conn = $db->getConnection();
        return $this->conn;
    }

    public function login($email, $password)
    {
        $collection = $this->conn->admins;

        $admin = $collection->findOne([
            "email" => $email,
            "password" => md5($password)
        ]);

        // var_dump($admin);

        if (!$admin) {
            return false;
        }

        $header = ["alg" => "HS256", "typ" => "JWT"];
        $payload = array("id" => $admin->_id, "email" => $email);

        // $data = base64_encode($header) + "." + base64_decode($payload);
        // echo $data;
        $jwt = \Firebase\JWT\JWT::encode($payload, "WebCuoiKy", "HS256", NULL, $header);

        $admin = $collection->updateOne(
            ["email" => $admin->email],
            ['$set' => array("jwt" => $jwt)]
        );

        return $jwt;
    }

    public function updateMap($jwt, $mapId, $name, $title, $detail, $path, $introduction)
    {
        // convert to mongo object
        $objMapId = new MongoDB\BSON\ObjectId($mapId);

        $collection = $this->conn->maps;
        
        // update map
        $result = $collection->updateOne(
            ["mapId" => $mapId],
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
}
