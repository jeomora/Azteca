<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Conversiones_model extends MY_Model {

	function __construct(){
		parent::__construct();
		$this->TABLE_NAME = "conversiones";
		$this->PRI_INDEX = "id_conversion";
	}

}

/* End of file Grupos_model.php */
/* Location: ./application/models/Grupos_model.php */