<?php
require_once(dirname(__FILE__) . '\..\..\vendor\autoload.php');

class CommontModel
{
    public $conn;
    public function __construct($db)
    {
        $this->conn = $db->getConnection();
        return $this->conn;
    }

    public function updateContent($heading, $detail)
    {
        $collection = $this->conn->commons;

        $result = $collection->updateOne(
            ["_id" => new MongoDB\BSON\ObjectId("5ff5cde46c25817df333a09d")],
            ['$set' => array("heading" => $heading, "detail" => $detail)]
        );

        return $result;
    }

    public function getHomePageContent()
    {
        $collection = $this->conn->commons;

        $result = $collection->findONe(
            ["_id" => new MongoDB\BSON\ObjectId("5ff5cde46c25817df333a09d")]
        );

        return $result;
    }
}
?>