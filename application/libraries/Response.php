<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Response {

    public $system;
    public $data;

    public function setData($system = array(), $data = array()) {
        if (empty($system))
            $system = new System(ResponseStatus::ERROR, 'Bad Request');
        if (empty($data))
            $data = NULL;

        $this->system = $system;
        $this->data = $data;
    }

    public function render($debug = TRUE) {
        header("Access-Control-Allow-Orgin: *");
        header("Access-Control-Allow-Methods: *");
        header("Content-Type: application/json");
        $system = (Array) $this->system;
        foreach ($system as $key => $value) {
            if (is_null($value)) {
                unset($system[$key]);
            }
        }
        $output = array('system' => $system, 'data' => $this->data);
        if ($debug) {
            switch (ENVIRONMENT) {
                case 'testing':
                case 'production':
                    break;
                case 'development';
                    $output['benchmark'] = array(
                        'query' => BenchmarkManager::query(),
                        'elapsed_time' => BenchmarkManager::render(),
                        'memory' => BenchmarkManager::memory(),
                        'parameter' => array(
                            'GET' => BenchmarkManager::get(),
                            'POST' => BenchmarkManager::post(),
                            'FILES' => BenchmarkManager::files()
                        )
                    );
                    break;
            }
        }
        echo Util::toJson($output);
        die;
    }

}
