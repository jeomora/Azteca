<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Prodcaja_model extends MY_Model {

	function __construct(){
		parent::__construct();
		$this->TABLE_NAME = "prodcaja";
		$this->PRI_INDEX = "id_prodcaja";
	}

}

/* End of file Existencias_model.php */
/* Location: ./application/models/Existencias_model.php */