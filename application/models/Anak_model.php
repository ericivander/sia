<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Anak extends MY_Model {
	public $primary_key = 'id perusahaan';
	protected $belongs_to = array(
		'Mahasiswa'=> array( 'primary_key' => 'nrp' )
		);
}