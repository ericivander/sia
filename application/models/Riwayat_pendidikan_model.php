<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Riwayat_pendidikan extends MY_Model {
	protected $belongs_to = array(
		'Mahasiswa'=> array( 'primary_key' => 'nrp' ),
		'Referensi_pendidikan' => array( 'primary_key' => 'id pendidikan' )
		);
}