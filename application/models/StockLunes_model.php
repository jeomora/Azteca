<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class StockLunes_model extends MY_Model {

	function __construct(){
		parent::__construct();
		$this->TABLE_NAME = "stocklunes";
		$this->PRI_INDEX = "id_stock";
	}

}

