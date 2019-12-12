<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Invoice_model extends MY_Model {

	function __construct(){
		parent::__construct();
		$this->TABLE_NAME = "invoice_codes";
		$this->PRI_INDEX = "id_invoice";
	}

}
