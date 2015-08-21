<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class ResponseStatus {

    const SUCCESS = 200; //Request success
    const ERROR = 400; //Error rerquest
    const UNAUTHORIZED = 401; //Invalid token
    const NOT_FOUND = 404; //URL Not Found
    const ERROR_VALIDATION = 402;
   
    //BELUM DIGUNAKAN
    const CREATED = 201;
    const ACCEPTED = 202;
    const PARTIAL_INFORMATION = 203;
    const NO_RESPONSE = 204;
    const FORBIDDEN = 403;
    const INTERNAL_ERROR = 500;
    const NOT_IMPLEMENTED = 501;
    const GATEWAY_TIMEOUT = 503;

}
