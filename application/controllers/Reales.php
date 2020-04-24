<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Reales extends MY_Controller {

	function __construct(){
		parent::__construct();
		$this->load->model("Reales_model", "real_md");
		$this->load->model("Reales_model", "real_mdl");
		$this->load->model("Realunes_model", "realunes_md");
		$this->load->model("Cambios_model", "cambio_md");
		$this->load->model("Productos_model", "pro_md");
		$this->load->model("Prolunes_model", "prolu_md");
		$this->load->model("Usuarios_model", "usua_mdl");
	}

	public function index(){
		$data['links'] = [
			'/assets/css/plugins/dataTables/dataTables.bootstrap',
			'/assets/css/plugins/dataTables/dataTables.responsive',
			'/assets/css/plugins/dataTables/dataTables.tableTools.min',
			'/assets/css/plugins/dataTables/buttons.dataTables.min',
		];

		$data['scripts'] = [
			'/scripts/reales',
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

		$data["reales"] = $this->real_md->getReales(NULL);
		//$this->jsonResponse($data["reales"]);
		$this->estructura("Reales/agregar", $data);
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

		$this->cellStyle("A1:C2", "000000", "FFFFFF", TRUE, 12, "Franklin Gothic Book");
		$border_style= array('borders' => array('right' => array('style' =>
			PHPExcel_Style_Border::BORDER_THIN,'color' => array('argb' => '000000'),)));

		$hoja->setCellValue("B1", "DESCRIPCIÓN SISTEMA")->getColumnDimension('B')->setWidth(60);

		$hoja->setCellValue("A2", "CÓDIGO")->getColumnDimension('A')->setWidth(25); //Nombre y ajuste de texto a la columna
		$hoja->setCellValue("C1", "PRECIO")->getColumnDimension('C')->setWidth(20);
		$hoja->setCellValue("C2", "REAL")->getColumnDimension('C')->setWidth(20);

		$row_print = 3;

		$hoja->getStyle("A3:C{$row_print}")
                 ->getAlignment()
                 ->setHorizontal(\PHPExcel_Style_Alignment::HORIZONTAL_LEFT);
		$hoja->getStyle("B3:B{$row_print}")
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

	public function upload_reales(){
		$fecha = new DateTime(date('Y-m-d H:i:s'));
		$intervalo = new DateInterval('P3D');
		$fecha->add($intervalo);
		$filen = "Precios_Reales".rand();
		$config['upload_path']          = './assets/uploads/reales/';
        $config['allowed_types']        = 'xlsx|xls';
        $config['max_size']             = 1000;
        $config['max_width']            = 10024;
        $config['max_height']           = 7680;
        $this->load->library('upload', $config);
        $this->upload->initialize($config);
        $this->upload->do_upload('file_otizaciones',$filen);
		$this->load->library("excelfile");
		ini_set("memory_limit", -1);
		$file = $_FILES["file_otizaciones"]["tmp_name"];
		$filename=$_FILES['file_otizaciones']['name'];
		$sheet = PHPExcel_IOFactory::load($file);
		$objExcel = PHPExcel_IOFactory::load($file);
		$sheet = $objExcel->getSheet(0);
		$num_rows = $sheet->getHighestDataRow();
		for ($i=3; $i<=$num_rows; $i++) {
			$productos = $this->pro_md->get("id_producto",['codigo'=> htmlspecialchars($sheet->getCell('A'.$i)->getValue(), ENT_QUOTES, 'UTF-8')])[0];
			if (sizeof($productos) > 0) {
				$precio=0;
				$precio = str_replace("$", "", str_replace(",", "replace", $sheet->getCell('C'.$i)->getValue()));
				$cotiz =  $this->real_mdl->get(NULL, ['id_producto' => $productos->id_producto, 'WEEKOFYEAR(fecha_registro)' => $this->weekNumber($fecha->format('Y-m-d H:i:s'))])[0];
				$new_cotizacion=[
					"id_producto"		=>	$productos->id_producto,
					"precio"			=>	$precio,
				];
				if($cotiz){
					$data['cotizacion']=$this->real_mdl->update($new_cotizacion, ['id_real' => $cotiz->id_real]);
				}else{
					$data['cotizacion']=$this->real_mdl->insert($new_cotizacion);
				}
			
			}
		}
		if (!isset($new_cotizacion)) {
			$mensaje=[	"id"	=>	'Error',
						"desc"	=>	'El Archivo esta sin precios',
						"type"	=>	'error'];
		}else{
			if (sizeof($new_cotizacion) > 0) {
				$cambios=[
						"id_usuario"		=>	$this->session->userdata('id_usuario'),
						"fecha_cambio"		=>	date("Y-m-d H:i:s"),
						"antes"			=>	"El usuario sube archivo de Precios Reales",
						"despues"			=>	"assets/uploads/reales/".$filen.".xlsx",
						"accion"			=>	"Sube Archivo"
					];
				$data['cambios']=$this->cambio_md->insert($cambios);
				$mensaje=[	"id"	=>	'Éxito',
							"desc"	=>	'Precios Reales cargados correctamente en el Sistema',
							"type"	=>	'success'];
			}else{
				$mensaje=[	"id"	=>	'Error',
							"desc"	=>	'Los Precios Reales no se cargaron al Sistema',
							"type"	=>	'error'];
			}
		}
		$this->jsonResponse($mensaje);
	}

	public function lunes(){
		$data['links'] = [
			'/assets/css/plugins/dataTables/dataTables.bootstrap',
			'/assets/css/plugins/dataTables/dataTables.responsive',
			'/assets/css/plugins/dataTables/dataTables.tableTools.min',
			'/assets/css/plugins/dataTables/buttons.dataTables.min',
		];

		$data['scripts'] = [
			'/scripts/realunes',
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

		$data["reales"] = $this->realunes_md->getReales(NULL);
		//$this->jsonResponse($data["reales"]);
		$this->estructura("Reales/lunes", $data);
	}

	public function upload_realunes(){
		$fecha = new DateTime(date('Y-m-d H:i:s'));
		$filen = "Precios_Realunes".rand();
		$config['upload_path']          = './assets/uploads/reales/';
        $config['allowed_types']        = 'xlsx|xls';
        $config['max_size']             = 1000;
        $config['max_width']            = 10024;
        $config['max_height']           = 7680;
        $this->load->library('upload', $config);
        $this->upload->initialize($config);
        $this->upload->do_upload('file_otizaciones',$filen);
		$this->load->library("excelfile");
		ini_set("memory_limit", -1);
		$file = $_FILES["file_otizaciones"]["tmp_name"];
		$filename=$_FILES['file_otizaciones']['name'];
		$sheet = PHPExcel_IOFactory::load($file);
		$objExcel = PHPExcel_IOFactory::load($file);
		$sheet = $objExcel->getSheet(0);
		$num_rows = $sheet->getHighestDataRow();
		for ($i=3; $i<=$num_rows; $i++) {
			$productos = $this->prolu_md->get("codigo",['codigo'=> htmlspecialchars($sheet->getCell('A'.$i)->getValue(), ENT_QUOTES, 'UTF-8')])[0];
			if (sizeof($productos) > 0) {
				$precio=0;
				$precio = str_replace("$", "", str_replace(",", "replace", $sheet->getCell('C'.$i)->getValue()));
				$cotiz =  $this->realunes_md->get(NULL, ['id_producto' => $productos->codigo, 'WEEKOFYEAR(fecha_registro)' => $this->weekNumber($fecha->format('Y-m-d H:i:s'))])[0];
				$new_cotizacion=[
					"id_producto"		=>	$productos->codigo,
					"precio"			=>	$precio,
				];
				if(sizeof($cotiz) > 0){
					$data['cotizacion']=$this->realunes_md->update($new_cotizacion, ['id_real' => $cotiz->id_real]);
				}else{
					$data['cotizacion']=$this->realunes_md->insert($new_cotizacion);
				}
			
			}
		}
		if (!isset($new_cotizacion)) {
			$mensaje=[	"id"	=>	'Error',
						"desc"	=>	'El Archivo esta sin precios',
						"type"	=>	'error'];
		}else{
			if (sizeof($new_cotizacion) > 0) {
				$cambios=[
						"id_usuario"		=>	$this->session->userdata('id_usuario'),
						"fecha_cambio"		=>	date("Y-m-d H:i:s"),
						"antes"			=>	"El usuario sube archivo de Precios Reales Lunes",
						"despues"			=>	"assets/uploads/reales/".$filen.".xlsx",
						"accion"			=>	"Sube Archivo"
					];
				$data['cambios']=$this->cambio_md->insert($cambios);
				$mensaje=[	"id"	=>	'Éxito',
							"desc"	=>	'Precios Reales cargados correctamente en el Sistema',
							"type"	=>	'success'];
			}else{
				$mensaje=[	"id"	=>	'Error',
							"desc"	=>	'Los Precios Reales no se cargaron al Sistema',
							"type"	=>	'error'];
			}
		}
		$this->jsonResponse($mensaje);
	}
}