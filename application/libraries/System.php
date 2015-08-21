<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class System {

    public $token;
    public $errMessage;
    public $errCode;
    public $infos;
    public $validation;
    public $notification;

    public function __construct($errorCode = NULL, $errorMessage = NULL, $infos = NULL, $validation = NULL, $notification = 0) {
        $this->setData($errorCode, $errorMessage, $infos, $validation, $notification);
    }

    public function setData($errorCode = NULL, $errorMessage = NULL, $infos = NULL, $validation = NULL, $notification = 0) {
        $this->errCode = $errorCode;
        $this->errMessage = $errorMessage;
        $this->infos = $infos;
        $this->validation = $validation;
        $this->notification = $notification;
    }

}
