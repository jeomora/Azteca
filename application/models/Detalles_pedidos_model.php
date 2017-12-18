<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Detalles_pedidos_model extends MY_Model {

	function __construct(){
		parent::__construct();
		$this->TABLE_NAME = "detalles_pedidos";
		$this->PRI_INDEX = "id_detalle_pedido";
	}

}

/* End of file Detalles_pedidos_model.php */
/* Location: ./application/models/Detalles_pedidos_model.php */