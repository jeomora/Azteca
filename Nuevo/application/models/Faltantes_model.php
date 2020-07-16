<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Faltantes_model extends MY_Model {

	function __construct(){
		parent::__construct();
		$this->TABLE_NAME = "faltantes";
		$this->PRI_INDEX = "id_faltante";
	}
}