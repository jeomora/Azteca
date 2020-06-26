<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Inicio extends MY_Controller {

	function __construct(){
		parent::__construct();
		$this->load->library("form_validation");
	}

	public function index(){
		$this->data["message"] =NULL;
		$this->estructura_login("Catalogo/admin", $this->data, FALSE);
	}

	
}
