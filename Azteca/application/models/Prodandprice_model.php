<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Prodandprice_model extends MY_Model {

	function __construct(){
		parent::__construct();
		$this->TABLE_NAME = "prodandprice";
		$this->PRI_INDEX = "id_cotizacion";
	}

}

/* End of file Grupos_model.php */
/* Location: ./application/models/Grupos_model.php */