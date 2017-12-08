<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Promociones_model extends MY_Model {

	function __construct(){
		parent::__construct();
		$this->TABLE_NAME = "promociones";
		$this->PRI_INDEX = "id_promocion";
	}

}

/* End of file Promociones_model.php */
/* Location: ./application/models/Promociones_model.php */