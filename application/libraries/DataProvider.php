<?php

class DataProvider {

    public $filter;
    public $sort;
    public $pagination;

    public function __construct($filter = NULL, $sort = NULL, $pagination = NULL) {
        $this->filter = $filter;
        $this->sort = $sort;
        $this->pagination = $pagination;
    }
    
    

}
