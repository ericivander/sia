<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Riwayat_pelatihan extends MY_Model {
	protected $belongs_to = array(
		'Mahasiswa'=> array( 'primary_key' => 'nrp' )
		);
}