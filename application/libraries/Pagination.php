<?php

class Pagination {

    public $currentPage;
    public $prevPage;
    public $nextPage;
    public $totalPage;
    public $totalData;

    public function __construct($totalData = NULL, $totalPage, $currentPage = NULL, $prevPage = NULL, $nextPage = NULL) {
        $this->currentPage = $currentPage;
        $this->prevPage = $prevPage;
        $this->nextPage = $nextPage;
        $this->totalPage = $totalPage;
        $this->totalData = $totalData;
    }

}
