<?php

include_once '../../../vendor/autoload.php';

// 'user' object
class User {

    // database connection and table name
    private $conn;
    private $collection_name = "users";

    // object properties
    public $firstName;
    public $lastName;
    public $email;
    public $password;

    // constructor
    public function __constructor($db) {
        $this->conn = $db;
    }

    // check email is exist
    public function emailExist() {
        $collection = $this->conn->WebCuoiKy-users;

        // get number of documents
//        $result = $collection->find(array('email' => $this->email), array('limit' => 1));
        $result = $collection->find(array('email' => $this->email))->count();

        // if email exists, assign values to object properties for easy access and use for php sessions
        if ($result > 0) {

            // get existed account
            $result = $collection->find(array('email' => $this->email));

            // assign that values to object properties
            $this->firstName = $result['firstName'];
            $this->lastName = $result['lastName'];
            $this->email = $result['email'];
            $this->password = $result['password'];

            return true;
        }

        return false;
    }


    // update method()

    public function create() {
        // insert query
//        $query = "INSERT INTO ".$this->table_name."
//        SET
//            firstName = :firstName,
//            lastName = :lastName,
//            email = :email,
//            password = :password";

        // prepare the query
//        $stmt = $this->conn->prepare($query);

        // sanitize
        // strip_tags() function -> remove html tags, htmlspecialchars() ->
        $this->firstName = htmlspecialchars(strip_tags($this->firstName));
        $this->lastName = htmlspecialchars(strip_tags($this->lastName));
        $this->email = htmlspecialchars(strip_tags($this->email));
        $this->password = htmlspecialchars(strip_tags($this->password));

        $this->firstName = str_replace("'", '"', $this->firstName);
        $this->lastName = str_replace("'", '"', $this->lastName);
        $this->email = str_replace("'", '"', $this->email);
        $this->password = str_replace("'", '"', $this->password);

//        bind the values
//        $stmt->bindParam(':firstName', $this->firstName);
//        $stmt->bindParam(':lastName', $this->lastName);
//        $stmt->bindParam(':email', $this->email);
//        $stmt->bindParam(':password', $this->password);
        $connection = new Database();
        $collection = $connection->getConnection()->users;

        $collection->insertOne([
            'firstName' => $this->firstName,
            'lastName' => $this->lastName,
            'email' => $this->email,
            'password' => $this->password
        ]);

        echo json_encode(array(
           "success" => true,
           "message" => "had input"
        ));

        return true;
    }
}

?>