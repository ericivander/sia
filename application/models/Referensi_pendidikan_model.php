<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Referensi_pendidikan extends MY_Model {
	public $primary_key = 'id pendidikan';
	protected $has_many = array(
		'Riwayat_pendidikan'=> array( 'primary_key' => 'nrp' ),
		);
}