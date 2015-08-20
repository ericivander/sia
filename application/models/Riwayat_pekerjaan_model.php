<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Referensi_pekerjaan extends MY_Model {
	protected $belogs_to = array(
			'Referensi_pekerjaan' => array( 'primary_key' => 'id perusahaan' ),
			'Mahasiswa' => array( 'primary_key' => 'nrp' )
		);
}