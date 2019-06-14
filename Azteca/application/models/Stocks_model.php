<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Stocks_model extends MY_Model {

	function __construct(){
		parent::__construct();
		$this->TABLE_NAME = "stocks";
		$this->PRI_INDEX = "id_stock";
	}

}

