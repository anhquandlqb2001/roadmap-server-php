<?php
require_once(dirname(__FILE__) . '/../../vendor/autoload.php');
// require_once "vendor/autoload.php";

class Database {
    private $host = 'mongodb://localhost:27017';
    private $db_name = 'WebCuoiKy';
    private $conn;

    // get the database connection
    public function getConnection() {
        $this->conn = null;

        try {
            $this->conn = (new MongoDB\Client($this->host))->WebCuoiKy;
        } catch(MongoConnectionException $e) {
            echo $e->getMessage();
        }

        return $this->conn;
    }
}

?>