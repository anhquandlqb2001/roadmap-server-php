<?php

class DModel extends Database
{
    protected $conn = NULL;

    public function __construct()
    {
        $this->conn = new Database();
    }
}