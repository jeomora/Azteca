<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Cotizaciones_model extends MY_Model {

	function __construct(){
		parent::__construct();
		$this->TABLE_NAME = "cotizaciones";
		$this->PRI_INDEX = "id_cotizacion";
	}

}

/* End of file Cotizaciones_model.php */
/* Location: ./application/models/Cotizaciones_model.php */