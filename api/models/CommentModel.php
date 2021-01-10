<?php
require_once(dirname(__FILE__) . '\..\..\vendor\autoload.php');

class CommentModel
{
    protected $conn;
    public function __construct($db)
    {
        $this->conn = $db->getConnection();
    }

    public function getComments($mapId, $package)
    {

    }

    public function addComment($userId, $userEmail, $mapId, $text)
    {
        $objUserId = new MongoDB\BSON\ObjectId($userId);
        $objMapId = new MongoDB\BSON\ObjectId($mapId);
        $commentId = new MongoDB\BSON\ObjectId();
        // var_dump($commentId->__toString());
        $collection = $this->conn->comments;
        // $timestamp =  MongoDB\BSON\Timestamp->getTimestamp(); 

        //         $utcdatetime = new MongoDB\BSON\UTCDateTime();
        // $datetime = $utcdatetime->toDateTime();

        $tz = new DateTimeZone('Asia/Ho_Chi_Minh'); //Change your timezone
        $date = date("Y-m-d h:i:sa"); //Current Date
        $a = new MongoDB\BSON\UTCDateTime(strtotime($date)*1000);
        $datetime = $a->toDateTime();
        $datetime->setTimezone($tz); //Set timezone
        $time=$datetime->format(DATE_ATOM);

        $result = $collection->insertOne(
            [
                "_id" => $commentId,
                "userId" => $objUserId,
                "userEmail" => $userEmail,
                "mapId" => $objMapId,
                "text" => $text,
                "createdAt" => $time
            ]
        );

        $mapModel = new MapModel(new Database());
        if(!$mapModel->addCommentId($objMapId, $commentId))
        {
            return false; 
        }

        return array("commentId" => $commentId->__toString(), "createdAt" => $time);
        

        
    }
}
?>