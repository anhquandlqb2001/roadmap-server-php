<?php
require_once(dirname(__FILE__) . '/../../vendor/autoload.php');

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
        $a = new MongoDB\BSON\UTCDateTime(strtotime($date) * 1000);
        $datetime = $a->toDateTime();
        $datetime->setTimezone($tz); //Set timezone
        // var_dump($datetime);
        $time = $datetime->format(DATE_ATOM);

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
        if (!$mapModel->addCommentId($objMapId, $commentId)) {
            return false;
        }

        return array("commentId" => $commentId->__toString(), "createdAt" => $time);
    }

    public function replyComment($mapId, $commentId, $userId, $userEmail, $text)
    {
        $commentsCollection = $this->conn->comments;
        $mapsCollection = $this->conn->maps;

        $objUserId = new MongoDB\BSON\ObjectId($userId);
        $objCommentId = new MongoDB\BSON\ObjectId($commentId);
        $objMapId = new MongoDB\BSON\ObjectId($mapId);

        $replyCommentId = new MongoDB\BSON\ObjectId();

        $tz = new DateTimeZone('Asia/Ho_Chi_Minh'); //Change your timezone
        $date = date("Y-m-d h:i:sa"); //Current Date
        $a = new MongoDB\BSON\UTCDateTime(strtotime($date) * 1000);
        $datetime = $a->toDateTime();
        $datetime->setTimezone($tz); //Set timezone
        $time = $datetime->format(DATE_ATOM);

        $result = $commentsCollection->insertOne(
            [
                "_id" => $replyCommentId,
                "mapId" => $objUserId,
                "userEmail" => $userEmail,
                "mapId" => $objMapId,
                "text" => $text,
                "createdAt" => strtotime($time)
            ]
        );

        return true;
    }

    public function addReply($mapId, $commentId, $userId, $userEmail, $text)
    {
        $commentsCollection = $this->conn->comments;
        $mapsCollection = $this->conn->maps;

        // init mongo id
        $objMapId = new MongoDB\BSON\ObjectId($mapId);
        $objCommentId = new MongoDB\BSON\ObjectId($commentId);
        $objUserId = new MongoDB\BSON\ObjectId($userId);

        // declare information of reply comment
        $objReplyId = new MongoDB\BSON\ObjectId();

        $tz = new DateTimeZone('Asia/Ho_Chi_Minh'); //Change your timezone
        $date = date("Y-m-d h:i:sa"); //Current Date
        $a = new MongoDB\BSON\UTCDateTime(strtotime($date) * 1000);
        $datetime = $a->toDateTime();
        $datetime->setTimezone($tz); //Set timezone
        $time = $datetime->format(DATE_ATOM);

        $replyInsert = [
            "_id" => $objReplyId,
            "commentId" => $objCommentId,
            "userId" => $objUserId,
            "userEmail" => $userEmail,
            "text" => $text,
            "createdAt" => ($time)
        ];


        $replyUpdate = $commentsCollection->updateOne(
            ["_id" => $objCommentId, "mapId" => $objMapId],
            ['$push' => ["replys" => $replyInsert]]
        );
        // var_dump($replyUpdate);



        // if not exist comment
        if (!$replyUpdate) {
            return false;
        }

        return array(
            "replyId" => $objReplyId->__toString(),
            "createdAt" => $time
        );
    }

    public function getReplys($commentId, $page)
    {
        $limit = 5;
        $skip = $page < 0 ? 0 : $limit * $page;
        $objCommentId = new MongoDB\BSON\ObjectId($commentId);
        $collection = $this->conn->comments;
        // $result = $collection->find(["mapId" => $objCommentId])->sort(array(strtotime("createdAt") => -1))->limit($limit)->skip($skip);
        $result = $collection->findOne(["_id" => $objCommentId], ['projection' => ['replys' => ['$slice' => [$skip, 6]]]]);
        // , ['sort' => ['replys.createdAt' => -1], 'replys' => ['$slice' => [$skip, ($skip + $limit + 1)]]]
        // var_dump($result->replys);
        // foreach ($result as $key => $value) {
        //     $replys[] = $value->replys;
        // }

        $hasMore = false;
        // var_dump(count($result->replys));
        if (count($result->replys) > $limit) {
            $hasMore = true;
        }

        $replys = json_decode(json_encode($result->replys));

        if ($hasMore == true) {
            array_pop($replys);
        }

        return array("replys" => $replys, "hasMore" => $hasMore);
    }


    public function getListComment($mapId, $page)
    {
        $limit = 10;
        $skip = $page < 0 ? 0 : $limit * $page;
        $objMapId = new MongoDB\BSON\ObjectId($mapId);

        $collection = $this->conn->comments;
        // $result = $collection->find(["mapId" => $objMapId])->sort(array(strtotime("createdAt") => -1))->limit($limit)->skip($skip);
        $result = $collection->find(["mapId" => $objMapId], ['sort' => ['createdAt' => -1], 'limit' => $limit + 1, 'skip' => $skip]);
        $comments = [];
        foreach ($result as $key => $value) {
            $arr = json_decode(json_encode($value), true);
            if (count($value->replys) > 0) {
                $arr["hasReply"] = true;
            } else {
                $arr["hasReply"] = false;
            }
            $comments[] = $arr;
        }

        $hasMore = false;
        if (count($comments) > $limit) {
            $hasMore = true;
        }

        if ($hasMore == true) {
            array_pop($comments);
        }

        return array("comments" => $comments, "hasMore" => $hasMore);
        // return $comments;
    }
}
