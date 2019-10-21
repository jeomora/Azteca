<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Exverdura_model extends MY_Model {

	function __construct(){
		parent::__construct();
		$this->TABLE_NAME = "ex_verdura";
		$this->PRI_INDEX = "id_verdura";
	}
}

/* End of file Menus_model.php */
/* Location: ./application/models/Menus_model.php */
