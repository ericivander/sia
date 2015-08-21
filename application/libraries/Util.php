<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Util {

    public static function toMapObject($data = array(), $key) {
        $output = array();
        foreach ($data as $row) {
            $output[$row[$key]] = $row;
        }
        return $output;
    }

    public static function toMap($data = array(), $key, $val) {
        $output = array();
        foreach ($data as $row) {
            $output[$row[$key]] = $row[$val];
        }
        return $output;
    }

    public static function toList($data = array(), $val, $exclude = array()) {
        $output = array();
        foreach ($data as $row) {
            if (!in_array($row[$val], $exclude))
                $output[] = $row[$val];
        }
        return $output;
    }

    public static function mapToList($map = array(), $keys = TRUE) {
        $list = array();
        foreach ($map as $key => $val) {
            $list[] = ($keys) ? $key : $val;
        }
        return $list;
    }

    public static function toJson($data = array()) {
        return json_encode($data);
    }

    public static function byteToMb($number) {
        return $number / 1024 / 1024;
    }

    public static function jsonToObject($json) {
        return json_decode($json);
    }

    public static function arrayUnset($data = array(), $unset = array(), $key = NULL) {
        foreach ($data as $keyData => $valData) {
            if ((empty($key) && in_array($keyData, $unset)) || (!empty($key) && in_array($key, $unset) && isset($valData[$key]) && $key == $valData[$key])) {
                unset($data[$keyData]);
            }
        }
        return $data;
    }

    public static function arrayUnsetExclude($data = array(), $exclude = array()) {
        foreach ($data as $key => $val) {
            if (!in_array($key, $exclude)) {
                unset($data[$key]);
            }
        }
        return $data;
    }

    public static function arrayMerge($arr1 = array(), $arr2 = array()) {
        if (empty($arr2)) {
            $arr2 = array();
        }
        foreach ($arr2 as $key => $val) {
            $arr1[$key] = $val;
        }
        return $arr1;
    }

    public static function timeNow($dash = TRUE) {
        return ($dash) ? date('Y-m-d H:i:s') : date('YmdHis');
    }

    public static function dateNow($dash = TRUE) {
        return ($dash) ? date('Y-m-d') : date('Ymd');
    }

    public static function timeAdd($add, $time = NULL) {
        if (empty($time)) {
            $time = self::timeNow();
        }
        return date('Y-m-d H:i:s', strtotime($add, strtotime($time)));
    }

    public static function getIndoDate() {
        return Formatter::toIndoDate(self::dateNow());
    }

    public static function getIndoDateTime() {
        return Formatter::toIndoDateTime(self::timeNow());
    }

    public static function findValueArray($array = array(), $key, $value) {
        $val = FALSE;
        foreach ($array as $k => $row) {
            if (isset($row[$key]) && $row[$key] == $value) {
                $val[] = $k;
            }
        }
        return $val;
    }

    public static function camelizeToUnderscore($str) {
        preg_match_all('!([A-Z][A-Z0-9]*(?=$|[A-Z][a-z0-9])|[A-Za-z][a-z0-9]+)!', $str, $matches);
        $ret = $matches[0];
        foreach ($ret as &$match) {
            $match = $match == strtoupper($match) ? strtolower($match) : lcfirst($match);
        }
        return implode('_', $ret);
    }

}
