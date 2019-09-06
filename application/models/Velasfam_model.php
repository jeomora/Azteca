<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Velasfam_model extends MY_Model {

	function __construct(){
		parent::__construct();
		$this->TABLE_NAME = "velasfam";
		$this->PRI_INDEX = "id_familia";
	}
}

/* End of file Proveedores_model.php */
/* Location: ./application/models/Proveedores_model.php */
