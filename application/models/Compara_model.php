<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Compara_model extends MY_Model {

	function __construct(){
		parent::__construct();
		$this->TABLE_NAME = "comparacion";
		$this->PRI_INDEX = "id_comparacion";
	}

}

