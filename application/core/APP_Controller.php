<?php

(defined('BASEPATH')) OR exit('No direct script access allowed');

class APP_Controller extends CI_Controller {

    protected $defaultModel;
    protected $class;
    protected $method;
    protected $postData = array();
    protected $model = FALSE;
    protected $data;

    public function __construct() {
        parent::__construct();

        //GET CLASS AND METHOD
        $this->class = $this->router->class;
        $this->method = ($this->router->class == $this->router->method) ? 'index' : $this->router->method;

        $this->postData = $this->input->post();

        //LOAD DEFAULT MODEL
        $this->loadDefaultModel();
    }

    private function loadDefaultModel() {
        if (empty($this->defaultModel)) {
            $defaultModel = ucfirst($this->class) . '_model';
        } else {
            $defaultModel = $this->defaultModel;
        }
        if (file_exists(APPPATH . 'models/' . $defaultModel . EXT)) {
            $this->load->model($defaultModel);
            $this->model = new $defaultModel();
        }
    }

}
