<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Reportes extends MY_Controller {

	function __construct(){
		parent::__construct();
		$this->load->model("Cotizaciones_model", "ct_mdl");
		$this->load->model("Usuarios_model", "user_mdl");
		$this->load->model("Cambios_model", "cam_mdl");
		$this->load->model("Prodcaja_model", "prodc_mdl");
		$this->load->model("Cambios_model", "cambio_md");
	}

	public function precios_bajos(){
		ini_set("memory_limit", "-1");
		$data['links'] = [
			'/assets/css/plugins/dataTables/dataTables.bootstrap',
			'/assets/css/plugins/dataTables/dataTables.responsive',
			'/assets/css/plugins/dataTables/dataTables.tableTools.min',
			'/assets/css/plugins/dataTables/buttons.dataTables.min',
		];

		$data['scripts'] = [
			'/scripts/reportes',
			'/assets/js/plugins/dataTables/jquery.dataTables.min',
			'/assets/js/plugins/dataTables/jquery.dataTables',
			'/assets/js/plugins/dataTables/dataTables.buttons.min',
			'/assets/js/plugins/dataTables/buttons.flash.min',
			'/assets/js/plugins/dataTables/jszip.min',
			'/assets/js/plugins/dataTables/pdfmake.min',
			'/assets/js/plugins/dataTables/vfs_fonts',
			'/assets/js/plugins/dataTables/buttons.html5.min',
			'/assets/js/plugins/dataTables/buttons.print.min',
			'/assets/js/plugins/dataTables/dataTables.bootstrap',
			'/assets/js/plugins/dataTables/dataTables.responsive',
			'/assets/js/plugins/dataTables/dataTables.tableTools.min',
		];

		$where=["WEEKOFYEAR(cotizaciones.fecha_registro)" => $this->weekNumber()];//Semana actual
		$data["preciosBajos"] = $this->ct_mdl->preciosBajos($where);
		$this->estructura("Reportes/table_precios_bajos", $data);
	}

	public function duero(){
		ini_set("memory_limit", "-1");
		$data['links'] = [
			'/assets/css/plugins/dataTables/dataTables.bootstrap',
			'/assets/css/plugins/dataTables/dataTables.responsive',
			'/assets/css/plugins/dataTables/dataTables.tableTools.min',
			'/assets/css/plugins/dataTables/buttons.dataTables.min',
		];

		$data['scripts'] = [
			'/scripts/duero',
			'/assets/js/plugins/dataTables/jquery.dataTables.min',
			'/assets/js/plugins/dataTables/jquery.dataTables',
			'/assets/js/plugins/dataTables/dataTables.buttons.min',
			'/assets/js/plugins/dataTables/buttons.flash.min',
			'/assets/js/plugins/dataTables/jszip.min',
			'/assets/js/plugins/dataTables/pdfmake.min',
			'/assets/js/plugins/dataTables/vfs_fonts',
			'/assets/js/plugins/dataTables/buttons.html5.min',
			'/assets/js/plugins/dataTables/buttons.print.min',
			'/assets/js/plugins/dataTables/dataTables.bootstrap',
			'/assets/js/plugins/dataTables/dataTables.responsive',
			'/assets/js/plugins/dataTables/dataTables.tableTools.min',
		];

		$where=["WEEKOFYEAR(cotizaciones.fecha_registro)" => $this->weekNumber()];//Semana actual
		$data["preciosBajos"] = $this->ct_mdl->preciosBajos($where);
		$data["productos"] = $this->prodc_mdl->getProds(NULL);
		//$this->jsonResponse($data["productos"]);
		$this->estructura("Reportes/duero", $data);
	}

	public function precios_iguales(){
		$data['links'] = [
			'/assets/css/plugins/dataTables/dataTables.bootstrap',
			'/assets/css/plugins/dataTables/dataTables.responsive',
			'/assets/css/plugins/dataTables/dataTables.tableTools.min',
			'/assets/css/plugins/dataTables/buttons.dataTables.min',
		];

		$data['scripts'] = [
			'/scripts/reportes',
			'/assets/js/plugins/dataTables/jquery.dataTables.min',
			'/assets/js/plugins/dataTables/jquery.dataTables',
			'/assets/js/plugins/dataTables/dataTables.buttons.min',
			'/assets/js/plugins/dataTables/buttons.flash.min',
			'/assets/js/plugins/dataTables/jszip.min',
			'/assets/js/plugins/dataTables/pdfmake.min',
			'/assets/js/plugins/dataTables/vfs_fonts',
			'/assets/js/plugins/dataTables/buttons.html5.min',
			'/assets/js/plugins/dataTables/buttons.print.min',
			'/assets/js/plugins/dataTables/dataTables.bootstrap',
			'/assets/js/plugins/dataTables/dataTables.responsive',
			'/assets/js/plugins/dataTables/dataTables.tableTools.min',
		];

		$where=["WEEKOFYEAR(cotizaciones.fecha_registro)" => $this->weekNumber()];//Semana actual
		$data["promociones_igual"] = $this->ct_mdl->getCotizaciones($where);
		$this->estructura("Reportes/table_precios_iguales", $data);
	}

	public function actividad(){
		$data['links'] = [
			'/assets/css/plugins/dataTables/dataTables.bootstrap',
			'/assets/css/plugins/dataTables/dataTables.responsive',
			'/assets/css/plugins/dataTables/dataTables.tableTools.min',
			'/assets/css/plugins/dataTables/buttons.dataTables.min',
		];

		$data['scripts'] = [
			'/scripts/reportes',
			'/assets/js/plugins/dataTables/jquery.dataTables.min',
			'/assets/js/plugins/dataTables/jquery.dataTables',
			'/assets/js/plugins/dataTables/dataTables.buttons.min',
			'/assets/js/plugins/dataTables/buttons.flash.min',
			'/assets/js/plugins/dataTables/jszip.min',
			'/assets/js/plugins/dataTables/pdfmake.min',
			'/assets/js/plugins/dataTables/vfs_fonts',
			'/assets/js/plugins/dataTables/buttons.html5.min',
			'/assets/js/plugins/dataTables/buttons.print.min',
			'/assets/js/plugins/dataTables/dataTables.bootstrap',
			'/assets/js/plugins/dataTables/dataTables.responsive',
			'/assets/js/plugins/dataTables/dataTables.tableTools.min',
		];
		$where = ["usuarios.id_usuario <> "=> 1];
		$data["cambios"] = $this->cam_mdl->getCambios($where);
		$this->estructura("Reportes/actividad", $data);
	}

	public function comparar(){
		$data['links'] = [
			'/assets/css/plugins/dataTables/dataTables.bootstrap',
			'/assets/css/plugins/dataTables/dataTables.responsive',
			'/assets/css/plugins/dataTables/dataTables.tableTools.min',
			'/assets/css/plugins/dataTables/buttons.dataTables.min',
		];

		$data['scripts'] = [
			'/scripts/comparar',
			'/assets/js/plugins/dataTables/jquery.dataTables.min',
			'/assets/js/plugins/dataTables/jquery.dataTables',
			'/assets/js/plugins/dataTables/dataTables.buttons.min',
			'/assets/js/plugins/dataTables/buttons.flash.min',
			'/assets/js/plugins/dataTables/jszip.min',
			'/assets/js/plugins/dataTables/pdfmake.min',
			'/assets/js/plugins/dataTables/vfs_fonts',
			'/assets/js/plugins/dataTables/buttons.html5.min',
			'/assets/js/plugins/dataTables/buttons.print.min',
			'/assets/js/plugins/dataTables/dataTables.bootstrap',
			'/assets/js/plugins/dataTables/dataTables.responsive',
			'/assets/js/plugins/dataTables/dataTables.tableTools.min',
		];
		$where=["usuarios.id_grupo" => 2];
		$data["proveedores"] = $this->user_mdl->getUsuarios($where);
		$this->estructura("Reportes/comparar", $data);
	}

	public function cotizaciones(){
		$data['links'] = [
			'/assets/css/plugins/dataTables/dataTables.bootstrap',
			'/assets/css/plugins/dataTables/dataTables.responsive',
			'/assets/css/plugins/dataTables/dataTables.tableTools.min',
			'/assets/css/plugins/dataTables/buttons.dataTables.min',
		];

		$data['scripts'] = [
			'/scripts/reportes',
			'/assets/js/plugins/dataTables/jquery.dataTables.min',
			'/assets/js/plugins/dataTables/jquery.dataTables',
			'/assets/js/plugins/dataTables/dataTables.buttons.min',
			'/assets/js/plugins/dataTables/buttons.flash.min',
			'/assets/js/plugins/dataTables/jszip.min',
			'/assets/js/plugins/dataTables/pdfmake.min',
			'/assets/js/plugins/dataTables/vfs_fonts',
			'/assets/js/plugins/dataTables/buttons.html5.min',
			'/assets/js/plugins/dataTables/buttons.print.min',
			'/assets/js/plugins/dataTables/dataTables.bootstrap',
			'/assets/js/plugins/dataTables/dataTables.responsive',
			'/assets/js/plugins/dataTables/dataTables.tableTools.min',
		];

		$data["proveedores"] = $this->user_mdl->getUsuarios(['usuarios.id_grupo'=>2]);//Son proveedores;
		$this->estructura("Reportes/filter_cotizaciones", $data);
	}

	public function fill_table(){
		$dias = array("DOMINGO","LUNES","MARTES","MIÉRCOLES","JUEVES","VIERNES","SÁBADO");
		$meses = array("ENERO","FEBRERO","MARZO","ABRIL","MAYO","JUNIO","JULIO","AGOSTO","SEPTIEMBRE","OCTUBRE","NOVIEMBRE","DICIEMBRE");

		$data["fecha"]=$this->input->post('fecha_registro') == "" ? date('Y-m-d') : date('Y-m-d', strtotime($this->input->post('fecha_registro')));
		$data["semana"]=$this->weekNumber($data["fecha"]);
		$data["user"]=$this->session->userdata();
		$data["fecha"]=$this->input->post('fecha_registro') == "" ? strtotime(date('Y-m-d')) : strtotime($this->input->post('fecha_registro'));
		$fecha =  $dias[date('w', $data["fecha"])]." ".date('d', $data["fecha"])." DE ".$meses[date('n', $data["fecha"])-1]. " DEL ".date('Y', $data["fecha"]) ;
		$data["fecha"]= $fecha;
		//$this->jsonResponse($data["fecha"]);
		$this->load->view("Reportes/table_cotizaciones", $data, FALSE);
	}
	public function fill_anterior(){
		$fecha = NULL;
		if ($this->input->post('fecha') != '') {
			$fecha = date('Y-m-d', strtotime($this->input->post('fecha')));
		}else{
			$fecha = date("Y-m-d");
		}
		$data["cotizaciones"] = $this->ct_mdl->getAnteriores(NULL,$fecha);
		$this->jsonResponse($data);
	}

	public function fill_reporte(){
		if (isset($_POST['excel']))
			$this->excelCotizaciones();
		else
			$this->pdfCotizaciones();
	}

	private function pdfCotizaciones(){
		# code...
	}

	private function excelCotizaciones(){
		# code...
	}

	public function expo(){
		$data['links'] = [
			'/assets/css/plugins/dataTables/dataTables.bootstrap',
			'/assets/css/plugins/dataTables/dataTables.responsive',
			'/assets/css/plugins/dataTables/dataTables.tableTools.min',
			'/assets/css/plugins/dataTables/buttons.dataTables.min',
		];

		$data['scripts'] = [
			'/scripts/expo',
			'/assets/js/plugins/dataTables/jquery.dataTables.min',
			'/assets/js/plugins/dataTables/jquery.dataTables',
			'/assets/js/plugins/dataTables/dataTables.buttons.min',
			'/assets/js/plugins/dataTables/buttons.flash.min',
			'/assets/js/plugins/dataTables/jszip.min',
			'/assets/js/plugins/dataTables/pdfmake.min',
			'/assets/js/plugins/dataTables/vfs_fonts',
			'/assets/js/plugins/dataTables/buttons.html5.min',
			'/assets/js/plugins/dataTables/buttons.print.min',
			'/assets/js/plugins/dataTables/dataTables.bootstrap',
			'/assets/js/plugins/dataTables/dataTables.responsive',
			'/assets/js/plugins/dataTables/dataTables.tableTools.min',
		];
		$where=["usuarios.id_grupo" => 2];
		$data["proveedores"] = $this->user_mdl->getUsuarios($where);
		$this->estructura("Reportes/expo", $data);
	}

	public function edit_prods(){
		$value = json_decode($this->input->post('values'), true);
		$new_prod = [
			"codigo" => $value["codigo"],
			"descripcion" => strtoupper($value["descripcion"]),
			"clave" => $value["clave"],
			"codigo_factura" => $value["codigo_factura"],
		];
		$gets = $this->prodc_mdl->update($new_prod, $value["id_prodcaja"]);
		
		$cambios = [
			"id_usuario" => $this->session->userdata('id_usuario'),
			"fecha_cambio" => date('Y-m-d H:i:s'),
			"accion" => "Cambia código duero ".strtoupper($value["descripcion"]),
			"antes" => "El usuario edita códifo Duero",
			"despues" => "Código->".$value["codigo"]."/ ".strtoupper($value["descripcion"])."/ Clave->".$value["clave"]."/ Cod Factura ->".$value["codigo_factura"]
		];
		$data['cambios'] = $this->cambio_md->insert($cambios);
		
		$mensaje = [
			"id" 	=> 'Éxito',
			"desc"	=> 'Código editado correctamente',
			"type"	=> 'success'
		];

		$this->jsonResponse($mensaje);
	}

}

/* End of file Reportes.php */
/* Location: ./application/controllers/Reportes.php */
