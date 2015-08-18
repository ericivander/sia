<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Mahasiswa extends MY_Model {
	public $primary_key = 'nrp';
	protected $has_many = array(
			'Pasangan' => array( 'primary_key' => 'nrp' ),
			'Anak'=> array( 'primary_key' => 'nrp' ),
			'Riwayat_kepanitiaan'=> array( 'primary_key' => 'nrp' ),
			'Riwayat_organisasi'=> array( 'primary_key' => 'nrp' ),
			'Riwayat_pekerjaan'=> array( 'primary_key' => 'nrp' ),
			'Riwayat_pelatihan'=> array( 'primary_key' => 'nrp' ),
			'Riwayat_pendidikan'=> array( 'primary_key' => 'nrp' )
		);
}