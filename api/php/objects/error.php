<?php

class ErrorField {
  public string $name;
  public string $error;
}

class ResponseToServer {
  public bool $success;
  public ErrorField $errors;

  // public function __construct($success, $error) {
  //   $this->success = $success;
  // }
}

$res = new ResponseToServer();


