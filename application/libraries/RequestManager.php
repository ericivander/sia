<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class RequestManager {

    const METHOD_HEAD = 'HEAD';
    const METHOD_GET = 'GET';
    const METHOD_POST = 'POST';
    const METHOD_PUT = 'PUT';
    const METHOD_PATCH = 'PATCH';
    const METHOD_DELETE = 'DELETE';
    const METHOD_OPTIONS = 'OPTIONS';
    const METHOD_OVERRIDE = '_METHOD';

    /**
     * Get method request
     * @return string
     * @throws Exception
     */
    public static function getMethod() {
        if ($_SERVER['REQUEST_METHOD'] == self::METHOD_POST && array_key_exists('HTTP_X_HTTP_METHOD', $_SERVER)) {
            if ($_SERVER['HTTP_X_HTTP_METHOD'] == self::METHOD_DELETE) {
                return self::METHOD_DELETE;
            } else if ($_SERVER['HTTP_X_HTTP_METHOD'] == self::METHOD_PUT) {
                return self::METHOD_PUT;
            } else {
                throw new Exception("Unexpected Header");
            }
        }
        return $_SERVER['REQUEST_METHOD'];
    }

    /**
     * Validate GET Request
     * @return boolean
     */
    public static function isGet() {
        return self::getMethod() === self::METHOD_GET;
    }

    /**
     * Validate POST Request
     * @return boolean
     */
    public static function isPost() {
        return self::getMethod() === self::METHOD_POST;
    }

    /**
     * Validate PUT Request
     * @return boolean
     */
    public static function isPut() {
        return self::getMethod() === self::METHOD_PUT;
    }

    /**
     * Validate PATH Request
     * @return boolean
     */
    public static function isPatch() {
        return self::getMethod() === self::METHOD_PATCH;
    }

    /**
     * Validate DELETE Request
     * @return boolean
     */
    public static function isDelete() {
        return self::getMethod() === self::METHOD_DELETE;
    }

    /**
     * Validate HEAD Request
     * @return boolean
     */
    public static function isHead() {
        return self::getMethod() === self::METHOD_HEAD;
    }

    /**
     * Validate OPTION Request
     * @return boolean
     */
    public static function isOptions() {
        return self::getMethod() === self::METHOD_OPTIONS;
    }

    /**
     * Validate AJAX Request
     * @return boolean
     */
    public static function isAjax() {
        if (isset($_SERVER['HTTP_X_REQUESTED_WITH']) && $_SERVER['HTTP_X_REQUESTED_WITH'] === 'XMLHttpRequest') {
            return true;
        }
        return false;
    }

    /**
     * Get IP Adress
     * @return string
     */
    public static function getIpAddress() {
        $ipaddress = '';
        if (getenv('HTTP_CLIENT_IP'))
            $ipaddress = getenv('HTTP_CLIENT_IP');
        else if (getenv('HTTP_X_FORWARDED_FOR'))
            $ipaddress = getenv('HTTP_X_FORWARDED_FOR');
        else if (getenv('HTTP_X_FORWARDED'))
            $ipaddress = getenv('HTTP_X_FORWARDED');
        else if (getenv('HTTP_FORWARDED_FOR'))
            $ipaddress = getenv('HTTP_FORWARDED_FOR');
        else if (getenv('HTTP_FORWARDED'))
            $ipaddress = getenv('HTTP_FORWARDED');
        else if (getenv('REMOTE_ADDR'))
            $ipaddress = getenv('REMOTE_ADDR');
        else
            $ipaddress = 'UNKNOWN';
        return $ipaddress;
    }

    /**
     * Get User Agent
     * @return string
     */
    public static function getUserAgent() {
        return $_SERVER['HTTP_USER_AGENT'];
    }

    public static function equalPost($vars = array(), $postKeys = array()) {
        if (empty($postKeys)) {
            $postKeys = array_keys($_POST);
        }
        if (count($postKeys) == count($vars)) {
            $count = 0;
            foreach ($vars as $var) {
                if (in_array($var, $postKeys)) {
                    $count++;
                }
            }
            if ($count == count($vars)) {
                return TRUE;
            }
        }
        return FALSE;
    }

}
