<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Mayoreo_model extends MY_Model {

	function __construct(){
		parent::__construct();
		$this->TABLE_NAME = "mayoreo";
		$this->PRI_INDEX = "id_mayoreo";
	}

}

/* End of file Existencias_model.php */
/* Location: ./application/models/Existencias_model.php */