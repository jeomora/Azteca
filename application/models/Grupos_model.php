<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Grupos_model extends MY_Model {

	function __construct(){
		parent::__construct();
		$this->TABLE_NAME = "grupos";
		$this->PRI_INDEX = "id_grupo";
	}

}

/* End of file Grupos_model.php */
/* Location: ./application/models/Grupos_model.php */