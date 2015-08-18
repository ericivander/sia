<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Referensi_pekerjaan extends MY_Model {
	protected $has_many = array('Riwayat_pekerjaan');
}