<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Avatars_model extends MY_Model {

	function __construct(){
		parent::__construct();
		$this->TABLE_NAME = "avatars";
		$this->PRI_INDEX = "id_avatar";
	}

}

/* End of file Grupos_model.php */
/* Location: ./application/models/Grupos_model.php */