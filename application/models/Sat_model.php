<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class sat_model extends MY_Model {

	function __construct(){
		parent::__construct();
		$this->TABLE_NAME = "sat";
		$this->PRI_INDEX = "id_producto";
	}

}

/* End of file Existencias_model.php */
/* Location: ./application/models/Existencias_model.php */