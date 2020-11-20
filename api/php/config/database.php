<?php

include_once  '../../../vendor/autoload.php';


class Database {
    private $host = 'mongodb://localhost';
    private $conn;

    // get the database connection
    public function getConnection() {
        $this->conn = null;

        try {
            $this->conn = (new MongoDB\Client($this->host))->roadmapphpdev;
            // new MongoDB\Driver\Manager("mongodb://localhost:27017")
        } catch(MongoConnectionException $e) {
            echo $e->getMessage();
        }

        return $this->conn;
    }
}

?>