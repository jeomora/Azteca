<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Productos extends MY_Controller {

	function __construct(){
		parent::__construct();
		$this->load->model("Usuarios_model", "usua_mdl");
		$this->load->model("Cotizaciones_model", "ct_mdl");
		$this->load->model("Cambios_model", "cambio_md");
		$this->load->model("Faltantes_model", "falt_mdl");
		$this->load->model("Familias_model", "fam_mdl");
		$this->load->model("Productos_model", "pro_md");
		$this->load->library("form_validation");
	}

	public function index(){
		ini_set("memory_limit", "-1");
		$data['scripts'] = [
			'/scripts/Productos/index',
		];
		$data["dias"] = array("DOMINGO","LUNES","MARTES","MIÉRCOLES","JUEVES","VIERNES","SÁBADO");
		$data["meses"] = array("ENERO","FEBRERO","MARZO","ABRIL","MAYO","JUNIO","JULIO","AGOSTO","SEPTIEMBRE","OCTUBRE","NOVIEMBRE","DICIEMBRE");
		$data["cotizados"] = $this->usua_mdl->getCotizados();
		$data["usuar"]  = $this->session->userdata();
		$data["familias"] = $this->fam_mdl->get(NULL,["estatus"=>1]);
		$data["cuantos"] = $this->pro_md->getCuantos(NULL)[0];
		$this->estructura("Productos/index", $data);
		//$this->jsonResponse($data["cotizados"]);
	}

	public function getOldVal($sheets,$i,$le){
		$cellB = $sheets->getCell($le.$i)->getValue();
		if(strstr($cellB,'=')==true){
		    $cellB = $sheets->getCell($le.$i)->getOldCalculatedValue();
		}
		return $cellB;
	}

	public function print_productos(){
		ini_set("memory_limit", "-1");
		$this->load->library("excelfile");
		$hoja = $this->excelfile->getActiveSheet();
		$hoja->getDefaultStyle()
		    ->getBorders()
		    ->getTop()
		        ->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
		$hoja->getDefaultStyle()
		    ->getBorders()
		    ->getBottom()
		        ->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
		$hoja->getDefaultStyle()
		    ->getBorders()
		    ->getLeft()
		        ->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
		$hoja->getDefaultStyle()
		    ->getBorders()
		    ->getRight()
		        ->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);

		$this->cellStyle("A1:G2", "000000", "FFFFFF", TRUE, 12, "Franklin Gothic Book");
		$border_style= array('borders' => array('right' => array('style' =>
			PHPExcel_Style_Border::BORDER_THIN,'color' => array('argb' => '000000'),)));

		$this->cellStyle("I1:J2", "000000", "FFFFFF", TRUE, 12, "Franklin Gothic Book");
		$border_style= array('borders' => array('right' => array('style' =>
			PHPExcel_Style_Border::BORDER_THIN,'color' => array('argb' => '000000'),)));

		$this->cellStyle("L1:M2", "000000", "FFFFFF", TRUE, 12, "Franklin Gothic Book");
		$border_style= array('borders' => array('right' => array('style' =>
			PHPExcel_Style_Border::BORDER_THIN,'color' => array('argb' => '000000'),)));

		$hoja->setCellValue("B1", "DESCRIPCIÓN SISTEMA")->getColumnDimension('B')->setWidth(60);
		$hoja->setCellValue("A2", "CÓDIGO")->getColumnDimension('A')->setWidth(25); //Nombre y ajuste de texto a la columna
		$hoja->setCellValue("C1", "NÚM");
		$hoja->setCellValue("C2", "FAMILIA")->getColumnDimension('C')->setWidth(15);
		$hoja->setCellValue("D1", "CONVERSIÓN")->getColumnDimension('D')->setWidth(20);
		$hoja->setCellValue("D2", "SI / NO")->getColumnDimension('D')->setWidth(20);
		$hoja->setCellValue("E1", "TIPO")->getColumnDimension('E')->setWidth(14);
		$hoja->setCellValue("F2", "UM")->getColumnDimension('F')->setWidth(8);
		$hoja->setCellValue("G1", "COD")->getColumnDimension('G')->setWidth(25);
		$hoja->setCellValue("G2", "PZA");

		$hoja->setCellValue("I1", "TIPO")->getColumnDimension('I')->setWidth(18);
		$hoja->setCellValue("J2", "")->getColumnDimension('J')->setWidth(8);
		$hoja->mergeCells('I1:J2');

		$hoja->setCellValue("L2", "FAMILIA")->getColumnDimension('L')->setWidth(30);
		$hoja->setCellValue("M2", "#")->getColumnDimension('M')->setWidth(8);
		$productos = $this->pro_md->getProdFam(NULL,0);
		$row_print = 3;
		if ($productos){
			foreach ($productos as $key => $value){
				$hoja->setCellValue("L{$row_print}", $value['familia']);
				$hoja->setCellValue("M{$row_print}", $value['id_familia']);
				$row_print +=1;
			}
		}

		$hoja->setCellValue("I3", "NORMAL");
		$hoja->setCellValue("J3", "1");
		$row_print++;
		$hoja->setCellValue("I4", "VOLÚMEN");
		$hoja->setCellValue("J4", "2");
		$row_print++;
		$hoja->setCellValue("I5", "AMARILLO");
		$hoja->setCellValue("J5", "3");
		$row_print++;
		$hoja->setCellValue("I6", "MODERNA");
		$hoja->setCellValue("J6", "4");
		$row_print++;
		$hoja->setCellValue("I7", "COSTEÑA");
		$hoja->setCellValue("J7", "5");
		$row_print++;
		$hoja->setCellValue("I8", "CUETARA");
		$hoja->setCellValue("J8", "6");
		$row_print++;

		$hoja->getStyle("A3:M{$row_print}")
                 ->getAlignment()
                 ->setHorizontal(\PHPExcel_Style_Alignment::HORIZONTAL_LEFT);

        $dias = array("DOMINGO","LUNES","MARTES","MIÉRCOLES","JUEVES","VIERNES","SÁBADO");
		$meses = array("ENERO","FEBRERO","MARZO","ABRIL","MAYO","JUNIO","JULIO","AGOSTO","SEPTIEMBRE","OCTUBRE","NOVIEMBRE","DICIEMBRE");

		$fecha =  $dias[date('w')]." ".date('d')." DE ".$meses[date('n')-1]. " DEL ".date('Y') ;
		$file_name = "SUBIR PRODUCTOS ".$fecha.".xlsx"; //Nombre del documento con extención
		header("Content-Type: application/vnd.ms-excel; charset=utf-8");
		header("Content-Disposition: attachment;filename=".$file_name);
		header("Cache-Control: max-age=0");
		$excel_Writer = PHPExcel_IOFactory::createWriter($this->excelfile, "Excel2007");
		$excel_Writer->save("php://output");
	}

	public function getProducto($id){
		$producto = $this->pro_md->get(NULL, ['id_producto'=>$id])[0];
		$this->jsonResponse($producto);
	}

	public function getProductos(){
		$prods = $this->pro_md->getProductos(NULL);
		$this->jsonResponse($prods);
	}

	public function delete_producto(){
		$user = $this->session->userdata();
		//$this->jsonResponse($this->input->post('id_producto'));
		$antes = $this->pro_md->get(NULL, ['id_producto'=>$this->input->post('id_producto')])[0];
		$data ['id_usuario'] = $this->pro_md->update(["estatus" => 0,"codigo"=>"Eliminado"], $this->input->post('id_producto'));
		$cambios = [
				"id_usuario" => $user["id_usuario"],
				"fecha_cambio" => date('Y-m-d H:i:s'),
				"antes" => "id: ".$antes->id_producto." /Código: ".$antes->codigo." /Nombre: ".$antes->nombre." /Familia: ".$antes->id_familia,
				"despues" => "Producto eliminado"];
		$data['cambios'] = $this->cambio_md->insert($cambios);

		$mensaje = ["id" 	=> 'Éxito',
					"desc"	=> 'Producto eliminado correctamente',
					"type"	=> 'success'];
		$this->jsonResponse($mensaje);
	}

	public function getProds($id_producto){
		$producto = $this->pro_md->get(NULL,["id_producto"=>$id_producto])[0];
		$this->jsonResponse($producto);
	}

	public function update_producto(){
		$user = $this->session->userdata();
		$flag = 1;
		if ($this->input->post("codigo") <> $this->input->post("codigo2")){
			$antes = $this->pro_md->get(NULL, ['codigo'=>$this->input->post('codigo')])[0];	
			if ($antes) {
				$flag = 0;
				$mensaje = [
					"desc"	=> 'El Código ya esta registrado en otro producto',
					"type"	=> 'error'
				];
			}
		}

		if ($flag) {
			$producto = [
				"nombre"		=>	strtoupper($this->input->post('nombre')),
				"codigo"		=>	$this->input->post('codigo'),
				"pieza"			=>	$this->input->post('pieza'),
				"unidad"		=>	$this->input->post('unidad'),
				"id_familia"	=>	$this->input->post('id_familia'),
				"estatus"		=>	$this->input->post('estatus'),
				"colorp"		=>	$this->input->post('colorp'),
				"casa"			=>	strtoupper($this->input->post('casa')),
			];

			$data ['id_producto'] = $this->pro_md->update($producto, $this->input->post('id_productos'));
			$mensaje = [
				"desc"	=> 'Producto actualizado correctamente',
				"type"	=> 'success'
			];
		}
		$this->jsonResponse($mensaje);	
	}

	public function agregar_producto(){
		$user = $this->session->userdata();
		$antes = $this->pro_md->get(NULL, ['codigo'=>$this->input->post('codigoA')])[0];	
		if ($antes) {
			$mensaje = [
				"desc"	=> 'El Código ya esta registrado en otro producto',
				"type"	=> 'error'
			];
		}else{
			$producto = [
				"nombre"		=>	strtoupper($this->input->post('nombreA')),
				"codigo"		=>	$this->input->post('codigoA'),
				"pieza"			=>	$this->input->post('piezaA'),
				"unidad"		=>	$this->input->post('unidadA'),
				"id_familia"	=>	$this->input->post('id_familiaA'),
				"estatus"		=>	$this->input->post('estatusA'),
				"colorp"		=>	$this->input->post('colorpA'),
				"casa"			=>	strtoupper($this->input->post('casaA')),
			];

			$data['id_producto'] = $this->pro_md->insert($producto);
			$mensaje = [
				"desc"	=> 'Producto actualizado correctamente',
				"type"	=> 'success'
			];
		}
		$this->jsonResponse($mensaje);	
	}

	public function upload_productos(){
		$proveedor = $this->session->userdata('id_usuario');
		$cfile =  $this->usua_mdl->get(NULL, ['id_usuario' => $proveedor])[0];
		$filen = "Productos por ".$cfile->nombre."".rand();
		$config['upload_path']          = './assets/uploads/productos/';
        $config['allowed_types']        = 'xlsx|xls';
        $config['max_size']             = 1000;
        $config['max_width']            = 10024;
        $config['max_height']           = 7608;

        $this->load->library('upload', $config);
        $this->upload->initialize($config);
        $this->upload->do_upload('file_productos',$filen);
		$this->load->library("excelfile");
		ini_set("memory_limit", -1);
		$file = $_FILES["file_productos"]["tmp_name"];
		$filename=$_FILES['file_productos']['name'];
		$sheet = PHPExcel_IOFactory::load($file);
		$objExcel = PHPExcel_IOFactory::load($file);
		$sheet = $objExcel->getSheet(0);
		$num_rows = $sheet->getHighestDataRow();
		$flag = 1;
		for ($i=3; $i<=$num_rows; $i++) {
			if ($this->getOldVal($sheet,$i,'A') <> NULL || $this->getOldVal($sheet,$i,'A') <> "") {
				$productos = $this->pro_md->get("id_producto",['codigo'=> $this->getOldVal($sheet,$i,'A')])[0];
				if(!$productos){
					if($this->getOldVal($sheet,$i,'D') === "1" || $this->getOldVal($sheet,$i,'D') === "SI" || $this->getOldVal($sheet,$i,'D') === "Si" || $this->getOldVal($sheet,$i,'D') === "si" || $this->getOldVal($sheet,$i,'D') === 1) {
						$estatus = 1;
					}else{
						$estatus = 0;
					}
					$new_producto=[
							"id_familia" 	=> $this->getOldVal($sheet,$i,'C'),//Recupera el id_usuario activo
							"nombre" 		=> $this->getOldVal($sheet,$i,'B'),
							"codigo"		=> $this->getOldVal($sheet,$i,'A'),
							"pieza"			=> $this->getOldVal($sheet,$i,'G'),
							"unidad" 		=> $this->getOldVal($sheet,$i,'F'),
							"colorp" 		=> $estatus,
							"estatus"		=> $this->getOldVal($sheet,$i,'E')
						];
					//$data ['id_producto'] = $this->pro_md->insert($new_producto);
				}else{
					if($this->getOldVal($sheet,$i,'D') === "1" || $this->getOldVal($sheet,$i,'D') === "SI" || $this->getOldVal($sheet,$i,'D') === "Si" || $this->getOldVal($sheet,$i,'D') === "si" || $this->getOldVal($sheet,$i,'D') === 1) {
						$estatus = 1;
					}else{
						$estatus = 0;
					}
					$new_producto=[
							"casa" 			=> $this->getOldVal($sheet,$i,'D'),//Recupera el id_usuario activo
							"unidad"		=> $this->getOldVal($sheet,$i,'C')
						];
					$data ['id_producto'] = $this->pro_md->update($new_producto,$productos->id_producto);
					$flag = 0;
				}
			}
		}
		if ($flag === 0) {
			$mensaje=[	"desc"	=>	'Algunos códigos ya estaban registrados',
						"type"	=>	'warning'];
		}else{
			if (isset($new_producto)) {
				$mensaje=[	"desc"	=>	'Productos cargados correctamente en el Sistema',
							"type"	=>	'success'];
			}else{
				$mensaje=[	"desc"	=>	'Los Productos no se cargaron al Sistema',
							"type"	=>	'error'];
			}
		}
		$this->jsonResponse($mensaje);
	}

	public function upload_productosum(){
		$proveedor = $this->session->userdata('id_usuario');
		$cfile =  $this->usua_mdl->get(NULL, ['id_usuario' => $proveedor])[0];
		$filen = "Productos por ".$cfile->nombre."".rand();
		$config['upload_path']          = './assets/uploads/productos/';
        $config['allowed_types']        = 'xlsx|xls';
        $config['max_size']             = 1000;
        $config['max_width']            = 10024;
        $config['max_height']           = 7608;

        $this->load->library('upload', $config);
        $this->upload->initialize($config);
        $this->upload->do_upload('file_productos',$filen);
		$this->load->library("excelfile");
		ini_set("memory_limit", -1);
		$file = $_FILES["file_productos"]["tmp_name"];
		$filename=$_FILES['file_productos']['name'];
		$sheet = PHPExcel_IOFactory::load($file);
		$objExcel = PHPExcel_IOFactory::load($file);
		$sheet = $objExcel->getSheet(0);
		$num_rows = $sheet->getHighestDataRow();
		$flag = 1;
		for ($i=3; $i<=$num_rows; $i++) {
			if ($this->getOldVal($sheet,$i,'A') <> NULL || $this->getOldVal($sheet,$i,'A') <> "") {
				$productos = $this->pro_md->get("id_producto",['codigo'=> $this->getOldVal($sheet,$i,'A')])[0];
				if(!$productos){

				}else{
					if($this->getOldVal($sheet,$i,'D') === "1" || $this->getOldVal($sheet,$i,'D') === "SI" || $this->getOldVal($sheet,$i,'D') === "Si" || $this->getOldVal($sheet,$i,'D') === "si" || $this->getOldVal($sheet,$i,'D') === 1) {
						$estatus = 1;
					}else{
						$estatus = 0;
					}
					$new_producto=[
							"obis" 			=> $this->getOldVal($sheet,$i,'K'),//Recupera el id_usuario activo
							"unidad"		=> $this->getOldVal($sheet,$i,'J')
						];
					$data ['id_producto'] = $this->pro_md->update($new_producto,$productos->id_producto);
					$flag = 0;
				}
			}
		}
		if ($flag === 0) {
			$mensaje=[	"desc"	=>	'Algunos códigos ya estaban registrados',
						"type"	=>	'warning'];
		}else{
			if (isset($new_producto)) {
				$mensaje=[	"desc"	=>	'Productos cargados correctamente en el Sistema',
							"type"	=>	'success'];
			}else{
				$mensaje=[	"desc"	=>	'Los Productos no se cargaron al Sistema',
							"type"	=>	'error'];
			}
		}
		$this->jsonResponse($mensaje);
	}
}