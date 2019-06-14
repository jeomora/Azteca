<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Sucursales_model extends MY_Model {

	function __construct(){
		parent::__construct();
		$this->TABLE_NAME = "sucursales";
		$this->PRI_INDEX = "id_sucursal";
	}

}

/* End of file Sucursales_model.php */
/* Location: ./application/models/Sucursales_model.php */