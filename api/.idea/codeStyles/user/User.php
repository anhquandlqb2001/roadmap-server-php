<?php

class User
{
    protected $email;
    protected $password;
    protected $error = "";

    public function __constructor($email, $password)
    {
        if($this->isInvalidateEmail($email) !== true)
        {
            $error = array(
                "error" => "email",
                "message" => "email khong hop le"
            );
            return $this->dataToSever(false, $error);
        }

        if($this->isValidatePassword($password) !== true)
        {
            $error = array(
                "error" => "password",
                "message" => "mat khau khong hop le"
            );
        }

        $this->email = $email;
        $this->password = $password;
    }

    private function isValidateEmail($email)
    {
        // remove html entity tag
        $email = htmlspecialchars(strip_tags($data->email));

        // checking possition of @
        return isValidateEmail($email);
    }

    private function isCorrectEmailType($email)
    {
        $trimEmail = trim($email);
        $emailAfterChange = strpos($email, "@gmail.com");

        if(strlen($emailAfterChange) != $possition)
        {
            return false;
        }

        if(strcmp($trimEmail, $emailAfterChange) == false)
        {
            return false;
        }

        // get prefix email
        $prefixEmail = substr($emailAfterChange, strpos($email, "@gmail.com"));

        // false if prefix get special value
        $prefixEmail = strtok($emailAfterChange, " @!#$^&*()+-/*");
        $prefixEmail = $prefixEmail . "@gmail.com";

        if(strcmp($prefixEmail, $emailAfterChange) !== true)
        {
            return false;
        }

        return true;
    }

    private function isValidatePassword($password)
    {
        $password = trim($password);
        $passwordTemp = strtok($password, " @!#$^&*()+-/*");

        if(strcmp($password, $passwordTemp) !== true)
        {
            return false;
        }

        return true;
    }

    private function dataToSever($success, $error) {
        return json_encode(
            array(
                "success" => $success,
                "errors" => $error
            )
        );
    }
}

?>