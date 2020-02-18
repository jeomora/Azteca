<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Catalogos_model extends MY_Model {

	function __construct(){
		parent::__construct();
		$this->TABLE_NAME = "catalogos";
		$this->PRI_INDEX = "id_catalogo";
	}

}