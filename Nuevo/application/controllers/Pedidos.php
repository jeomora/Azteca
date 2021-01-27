<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Pedidos extends MY_Controller {

	function __construct(){
		parent::__construct();
		$this->load->model("Usuarios_model", "usua_mdl");
		$this->load->model("Cotizaciones_model", "ct_mdl");
		$this->load->model("Cambios_model", "cambio_md");
		$this->load->model("Faltantes_model", "falt_mdl");
		$this->load->model("Familias_model", "fam_mdl");
		$this->load->model("Productos_model", "pro_md");
		$this->load->model("Finales_model", "fin_md");
		$this->load->model("Llegaron_model", "llego_md");
		$this->load->library("form_validation");
	}

	public function finales(){
		ini_set("memory_limit", "-1");
		$data['scripts'] = [
			'/scripts/Pedidos/finales',
		];
		$data["dias"] = array("DOMINGO","LUNES","MARTES","MIÉRCOLES","JUEVES","VIERNES","SÁBADO");
		$data["meses"] = array("ENERO","FEBRERO","MARZO","ABRIL","MAYO","JUNIO","JULIO","AGOSTO","SEPTIEMBRE","OCTUBRE","NOVIEMBRE","DICIEMBRE");
		$data["cotizados"] = $this->usua_mdl->getCotizados();
		$data["usuar"]  = $this->session->userdata();
		$data["proveedores"] = $this->usua_mdl->get(NULL,["estatus"=>1,"id_grupo"=>2]);
		$data["familias"] = $this->fam_mdl->get(NULL,["estatus"=>1]);
		$this->estructura("Pedidos/finales", $data);
		//$this->jsonResponse($data["cotizados"]);
	}

	public function getFinales($proveedor){
		$finales = $this->fin_md->getFinales(NULL,$proveedor);
		$this->jsonResponse($finales);
	}

	public function deletePedidos($proveedor){
		$this->db->query("delete from finales where WEEKOFYEAR(fecha_registro) = WEEKOFYEAR(CURDATE()) AND id_proveedor = ".$proveedor." ;");
		$user = $this->session->userdata();
		$cambios = [
				"id_usuario" => $user["id_usuario"],
				"fecha_cambio" => date('Y-m-d H:i:s'),
				"antes" => "El usuario elimino pedidos finales",
				"despues" => "Del proveedor ".$proveedor.". "];
		$data['cambios'] = $this->cambio_md->insert($cambios);
		$this->jsonResponse($proveedor);
	}

	public function upload_finales($provs){
		$arrays = array();
		$user = $this->session->userdata();
		$array = array();
		$this->load->library("excelfile");
		ini_set("memory_limit", -1);
		$file = $_FILES["file_final"]["tmp_name"];
		$filename=$_FILES['file_final']['name'];

		$config['upload_path']          = './assets/uploads/Pedidos/';
        $config['allowed_types']        = 'xlsx|xls';
        $config['max_size']             = 1000;
        $config['max_width']            = 10240;
        $config['max_height']           = 7680;
        $this->load->library('upload', $config);
        $nameme = 'PedidoFinal'.date('dmYHis');
        $this->upload->do_upload('file_final',$nameme);

		$sheet = PHPExcel_IOFactory::load($file);
		$objExcel = PHPExcel_IOFactory::load($file);
		$sheet = $objExcel->getSheet(0);
		$num_rows = $sheet->getHighestDataRow();
		$proveedor = $provs;
		for ($i=0; $i<=$num_rows; $i++) {
			$codigo = $this->pro_md->get(NULL,["codigo"=>htmlspecialchars($sheet->getCell('A'.$i)->getValue(), ENT_QUOTES, 'UTF-8')])[0];
			$cellC = $this->getOldVal($sheet,$i,"C");
			$cellD = $this->getOldVal($sheet,$i,"D");
			$cellE = $this->getOldVal($sheet,$i,"E");
			$cellF = $this->getOldVal($sheet,$i,"F");
			$cellG = $this->getOldVal($sheet,$i,"G");
			$cellH = $this->getOldVal($sheet,$i,"H");
			$cellI = $this->getOldVal($sheet,$i,"I");
			$cellJ = $this->getOldVal($sheet,$i,"J");
			$cellK = $this->getOldVal($sheet,$i,"K");
			$cellL = $this->getOldVal($sheet,$i,"L");
			$cellM = $this->getOldVal($sheet,$i,"M");
			
			if (sizeof($codigo) > 0) {
				$new_producto=[
					"id_producto" => $codigo->id_producto,
					"id_proveedor" => $proveedor,
					"costo" => $cellC,
					"cedis" => $cellD,
					"abarrotes" => $cellE,
					"villas" => $cellF,
					"tienda" => $cellG,
					"ultra" => $cellH,
					"trincheras" => $cellI,
					"mercado" => $cellJ,
					"tenencia" => $cellK,
					"tijeras" => $cellL,
					"promocion" => $cellM,
				];

				$codiga = $this->fin_md->get(NULL,['id_producto'=> $codigo->id_producto,"id_proveedor"=>$proveedor,"WEEKOFYEAR(fecha_registro)"=>$this->weekNumber()])[0];
				if (sizeof($codiga) > 0) {
					$data ['id_prodcaja']=$this->fin_md->update($new_producto, $codiga->id_final);
				}else{
					array_push($arrays, $new_producto);
					$data ['id_prodcaja']=$this->fin_md->insert($new_producto);
				}
			}
		}
		$cambios = [
				"id_usuario" => $user["id_usuario"],
				"fecha_cambio" => date('Y-m-d H:i:s'),
				"antes" => "".$nameme,
				"despues" => "El usuario registro pedidos finales"];
		$data['cambios'] = $this->cambio_md->insert($cambios);
		$mensaje=[	
			"id"	=>	'Éxito',
			"desc"	=>	'Productos cargados correctamente en el Sistema',
			"type"	=>	'success'];

		$this->jsonResponse(array($arrays,$array,$num_rows));
	}

	public function getOldVal($sheets,$i,$le){
		$cellB = $sheets->getCell($le.$i)->getValue();
		if(strstr($cellB,'=')==true){
		    $cellB = $sheets->getCell($le.$i)->getOldCalculatedValue();
		}
		return $cellB;
	}

	public function llegaron(){
		ini_set("memory_limit", "-1");
		$data['scripts'] = [
			'/scripts/Pedidos/llegaron',
		];
		$data["dias"] = array("DOMINGO","LUNES","MARTES","MIÉRCOLES","JUEVES","VIERNES","SÁBADO");
		$data["meses"] = array("ENERO","FEBRERO","MARZO","ABRIL","MAYO","JUNIO","JULIO","AGOSTO","SEPTIEMBRE","OCTUBRE","NOVIEMBRE","DICIEMBRE");
		$data["cotizados"] = $this->usua_mdl->getCotizados();
		$data["usuar"]  = $this->session->userdata();
		$data["proveedores"] = $this->usua_mdl->get(NULL,["estatus"=>1,"id_grupo"=>2]);
		$data["familias"] = $this->fam_mdl->get(NULL,["estatus"=>1]);
		$this->estructura("Pedidos/llegaron", $data);
		//$this->jsonResponse($data["cotizados"]);
	}

	public function getLlegaron(){
		$finales = $this->fin_md->getLlegaron(NULL);
		$this->jsonResponse($finales);
	}

	public function upload_llegaron(){
		$arrays = array();
		$user = $this->session->userdata();
		$array = array();
		$this->load->library("excelfile");
		ini_set("memory_limit", -1);
		$file = $_FILES["file_final"]["tmp_name"];
		$filename=$_FILES['file_final']['name'];

		$config['upload_path']          = './assets/uploads/Pedidos/';
        $config['allowed_types']        = 'xlsx|xls';
        $config['max_size']             = 1000;
        $config['max_width']            = 10240;
        $config['max_height']           = 7680;
        $this->load->library('upload', $config);
        $this->upload->do_upload('file_final','PedidoLlego'.date('dmYHis'));

		$sheet = PHPExcel_IOFactory::load($file);
		$objExcel = PHPExcel_IOFactory::load($file);
		$sheet = $objExcel->getSheet(0);
		$num_rows = $sheet->getHighestDataRow();
		$proveedor = 1;
		for ($i=0; $i<=$num_rows; $i++) {
			$codigo = $this->pro_md->get(NULL,["codigo"=>htmlspecialchars($sheet->getCell('A'.$i)->getValue(), ENT_QUOTES, 'UTF-8')])[0];
			$cellC = $this->getOldVal($sheet,$i,"C");
			$cellD = $this->getOldVal($sheet,$i,"D");
			$cellE = $this->getOldVal($sheet,$i,"E");
			$cellF = $this->getOldVal($sheet,$i,"F");
			$cellG = $this->getOldVal($sheet,$i,"G");
			$cellH = $this->getOldVal($sheet,$i,"H");
			$cellI = $this->getOldVal($sheet,$i,"I");
			$cellJ = $this->getOldVal($sheet,$i,"J");
			$cellK = $this->getOldVal($sheet,$i,"K");
			$cellL = $this->getOldVal($sheet,$i,"L");
			$cellM = $this->getOldVal($sheet,$i,"M");
			
			if (sizeof($codigo) > 0) {
				$new_producto=[
					"id_producto" => $codigo->id_producto,
					"id_proveedor" => $proveedor,
					"costo" => $cellC,
					"cedis" => $cellD,
					"abarrotes" => $cellE,
					"villas" => $cellF,
					"tienda" => $cellG,
					"ultra" => $cellH,
					"trincheras" => $cellI,
					"mercado" => $cellJ,
					"tenencia" => $cellK,
					"tijeras" => $cellL,
					"promocion" => $cellM,
				];

				$codiga = $this->llego_md->get(NULL,['id_producto'=> $codigo->id_producto,"id_proveedor"=>$proveedor,"WEEKOFYEAR(fecha_registro)"=>$this->weekNumber()])[0];
				if (sizeof($codiga) > 0) {
					$data ['id_prodcaja']=$this->llego_md->update($new_producto, $codiga->id_final);
				}else{
					array_push($arrays, $new_producto);
					$data ['id_prodcaja']=$this->llego_md->insert($new_producto);
				}
			}
		}
		$cambios = [
				"id_usuario" => $user["id_usuario"],
				"fecha_cambio" => date('Y-m-d H:i:s'),
				"antes" => "",
				"despues" => "El usuario registro llegaron"];
		$data['cambios'] = $this->cambio_md->insert($cambios);
		$mensaje=[	
			"id"	=>	'Éxito',
			"desc"	=>	'Pedidos cargados correctamente en el Sistema',
			"type"	=>	'success'];

		$this->jsonResponse(array($arrays,$array,$num_rows));
	}
}