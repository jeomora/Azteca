<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Llegaron_model extends MY_Model {

	function __construct(){
		parent::__construct();
		$this->TABLE_NAME = "llegaron";
		$this->PRI_INDEX = "id_final";
	}
}