<?php

class BenchmarkManager {

    public static function query() {
        $CI = & get_instance();
        $times = $CI->db->query_times;
        $output = array();
        foreach ($CI->db->queries as $key => $query) {
            $output[] = array('sql' => str_replace('"', '', preg_replace('/\s+/', ' ', str_replace(PHP_EOL, ' ', $query))), 'execution_time' => $times[$key]);
        }
        return $output;
    }

    public static function totalExecutionQuery() {
        $CI = & get_instance();
        $times = $CI->db->query_times;
        return array_sum($times);
    }

    public static function render() {
        $CI = & get_instance();
        return $CI->benchmark->elapsed_time('total_execution_time_start', 'total_execution_time_end', 7);
    }

    public static function memory() {
        $memory = Formatter::toIndoNumber(Util::byteToMb(memory_get_usage()), 4) . ' MB';
        return $memory;
    }

    public static function realMemory() {
        $memory = Formatter::toIndoNumber(Util::byteToMb(memory_get_usage(TRUE)), 4) . ' MB';
        return $memory;
    }

    public static function httpHeader() {
        $CI = & get_instance();
        return $CI->input->request_headers();
    }

    public static function controllerInfo() {
        $CI = & get_instance();
        $router = $CI->router;
        return array('class' => $router->class, 'method' => $router->method, 'directory' => $router->directory);
    }

    public static function post() {
        $CI = & get_instance();
        return $CI->input->post();
    }

    public static function get() {
        $CI = & get_instance();
        return $CI->input->get();
    }
    
    public static function files(){
        return $_FILES;
    }

    public static function session() {
        return $_SESSION;
    }

    public static function getMemoryInfo() {
        $info = array();
        foreach (explode(PHP_EOL, shell_exec('cat /proc/meminfo')) as $val) {
            $exp = explode(':', preg_replace('/\s+/', '', $val));
            if (!empty($exp[0]) && !empty($exp[1]))
                $info[$exp[0]] = $exp[1];
        }
        return $info;
    }

    public static function getCpuInfo() {
        $info = array();
        foreach (explode(PHP_EOL, shell_exec('cat /proc/cpuinfo')) as $val) {
            $exp = explode(':', preg_replace('/\s+/', '', $val));
            if (!empty($exp[0]) && !empty($exp[1]))
                $info[$exp[0]] = $exp[1];
        }
        return $info;
    }

    public static function getServerInfo() {
        return array('Operating System' => php_uname(), 'PHP' => phpversion());
    }

}
