<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Existencias_model extends MY_Model {

	function __construct(){
		parent::__construct();
		$this->TABLE_NAME = "existencias";
		$this->PRI_INDEX = "id_pedido";
	}

}

/* End of file Existencias_model.php */
/* Location: ./application/models/Existencias_model.php */