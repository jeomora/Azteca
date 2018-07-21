<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Contadores extends MY_Controller {

	function __construct(){
		parent::__construct();
		$this->load->model("Usuarios_model", "user_md");
		$this->load->model("Cambios_model", "cambio_md");
		$this->load->model("Grupos_model", "gr_md");
		$this->load->model("Sat_model", "sat_md");
		$this->load->model("Prodfactura_model", "pfact_md");
		$this->load->library("form_validation");
	}

	public function index(){
		$data['links'] = [
			'/assets/css/plugins/dataTables/dataTables.bootstrap',
			'/assets/css/plugins/dataTables/dataTables.responsive',
			'/assets/css/plugins/dataTables/dataTables.tableTools.min',
			'/assets/css/plugins/dataTables/buttons.dataTables.min',
		];

		$data['scripts'] = [
			'/scripts/contadores',
			'/assets/js/plugins/dataTables/jquery.dataTables.min',
			'/assets/js/plugins/dataTables/jquery.dataTables',
			'/assets/js/plugins/dataTables/dataTables.buttons.min',
			'/assets/js/plugins/dataTables/dataTables.bootstrap',
			'/assets/js/plugins/dataTables/dataTables.responsive',
			'/assets/js/plugins/dataTables/dataTables.tableTools.min',
		];
		$this->estructura("Admin/contadores", $data);
	}

	
	private function estructura_login($view, $data=array()){
		$this->_render_page($view, $data);
	}

	private function _render_page($view, $data=null, $returnhtml=false){//I think this makes more sense
		$this->viewdata = (empty($data)) ? $this->data: $data;
		$view_html = $this->load->view($view, $this->viewdata, $returnhtml);
		if ($returnhtml) return $view_html;//This will return html on 3rd argument being true
	}

	public function subirxml(){
		ini_set("memory_limit", -1);
		$dom = file_get_contents($_FILES["file_xml"]["tmp_name"]); 
		$dom = str_replace("cfdi:", "", $dom);
		$xml = simplexml_load_string($dom);
		$json = json_encode($xml);
		$array = json_decode($json,TRUE);


		




		$this->jsonResponse($array);
	}

	public function upload_productos(){
		$this->load->library("excelfile");
		ini_set("memory_limit", -1);
		$file = $_FILES["file_p"]["tmp_name"];
		$sheet = PHPExcel_IOFactory::load($file);
		$objExcel = PHPExcel_IOFactory::load($file);
		$sheet = $objExcel->getSheet(0);
		$num_rows = $sheet->getHighestDataRow();
		
		for ($i=5; $i<=$num_rows; $i++) {
			$new_producto=[
					"codigo" => $sheet->getCell('A'.$i)->getValue(),//Recupera el id_usuario activo
					"descripcion" => $sheet->getCell('B'.$i)->getValue(),
					"claves" => $sheet->getCell('C'.$i)->getValue()];
			$data ['id_producto']=$this->sat_md->insert($new_producto);
		}
		$mensaje=[	"id"	=>	'Éxito',
							"desc"	=>	'Productos cargados correctamente en el Sistema',
							"type"	=>	'success'];
		$this->jsonResponse($mensaje);
	}

	public function upload_productos2(){
		$this->load->library("excelfile");
		ini_set("memory_limit", -1);
		$file = $_FILES["file_p"]["tmp_name"];
		$sheet = PHPExcel_IOFactory::load($file);
		$objExcel = PHPExcel_IOFactory::load($file);
		$sheet = $objExcel->getSheet(0);
		$num_rows = $sheet->getHighestDataRow();
		
		for ($i=2; $i<=$num_rows; $i++) {
			$new_producto=[
					"codigo" => $sheet->getCell('A'.$i)->getValue(),//Recupera el id_usuario activo
					"codigo_pz" => $sheet->getCell('B'.$i)->getValue(),
					"descripcion" => $sheet->getCell('C'.$i)->getValue()];
			$data ['id_producto']=$this->pfact_md->insert($new_producto);
		}
		$mensaje=[	"id"	=>	'Éxito',
							"desc"	=>	'Productos cargados correctamente en el Sistema',
							"type"	=>	'success'];
		$this->jsonResponse($mensaje);
	}

}
