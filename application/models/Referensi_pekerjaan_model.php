<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Referensi_pekerjaan extends MY_Model {
	public $primary_key = 'id perusahaan';
	protected $has_many = array(
		'Riwayat_pekerjaan'=> array( 'primary_key' => 'nrp' )
		);
}