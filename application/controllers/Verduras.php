<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Verduras extends MY_Controller {

	function __construct(){
		parent::__construct();
		$this->load->model("Usuarios_model", "user_md");
		$this->load->model("Cambios_model", "cambio_md");
		$this->load->model("Prove_model", "prove_md");
		$this->load->model("Prolunes_model", "prolu_md");
		$this->load->model("Suclunes_model", "suc_md");
		$this->load->model("Exislunes_model", "ex_lun_md");
		$this->load->model("Productos_model", "prod_mdl");
		$this->load->model("Pendlunes_model", "pend_mdl");
		$this->load->model("Verduras_model", "ver_mdl");
		$this->load->model("Exverdura_model", "exver_mdl");
	}

	public function index(){
		$data['links'] = [
			'/assets/css/plugins/dataTables/dataTables.bootstrap',
			'/assets/css/plugins/dataTables/dataTables.responsive',
			'/assets/css/plugins/dataTables/dataTables.tableTools.min',
			'/assets/css/plugins/dataTables/buttons.dataTables.min',
		];

		$data['scripts'] = [
			'/scripts/verduras',
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

		$data["usuarios"] = $this->user_md->getUsuarios();
		$data["dias"] = array("DOMINGO","LUNES","MARTES","MIÉRCOLES","JUEVES","VIERNES","SÁBADO");
		$data["meses"] = array("ENERO","FEBRERO","MARZO","ABRIL","MAYO","JUNIO","JULIO","AGOSTO","SEPTIEMBRE","OCTUBRE","NOVIEMBRE","DICIEMBRE");
		$day = date('w');
		$data["verduras"] = $this->ver_mdl->getExistenciasJ(NULL);
		$data["verdurasEx"] = $this->ver_mdl->getExistencias(NULL);
		$data["fecha"] =  $data["dias"][date('w')]." ".date('d')." DE ".$data["meses"][date('n')-1]. " DEL ".date('Y') ;
		$this->estructura("Verduras/existencias", $data);
	}

	public function upload_productos(){
			$proveedor = $this->session->userdata('id_usuario');
			$cfile =  $this->user_md->get(NULL, ['id_usuario' => $proveedor])[0];
			$filen = "Productos por ".$cfile->nombre."".rand();
			$config['upload_path']          = './assets/uploads/cotizaciones/';
	        $config['allowed_types']        = 'xlsx|xls';
	        $config['max_size']             = 100;
	        $config['max_width']            = 1024;
	        $config['max_height']           = 768;


	        $estatus = 1;
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

		
			for ($i=3; $i<=$num_rows; $i++) {
				$productos = $this->ver_mdl->get("id_verdura",['descripcion'=> $sheet->getCell('A'.$i)->getValue()])[0];
				if (sizeof($productos) <= 0) {
					$new_producto=[
							"id_familia" => $sheet->getCell('C'.$i)->getValue(),//Recupera el id_usuario activo
							"descripcion" => $sheet->getCell('A'.$i)->getValue(),
							"precio" => $sheet->getCell('B'.$i)->getValue()];
					$data ['id_producto']=$this->ver_mdl->insert($new_producto);
				}
			}
			if (!isset($new_producto)) {
				$mensaje=[	"id"	=>	'Error',
							"desc"	=>	'El Archivo esta sin productos',
							"type"	=>	'error'];
			}else{
				if (sizeof($new_producto) > 0) {
					$cambios=[
							"id_usuario"		=>	$this->session->userdata('id_usuario'),
							"fecha_cambio"		=>	date("Y-m-d H:i:s"),
							"antes"				=>	"El usuario sube productos Verduras",
							"despues"			=>	"assets/uploads/cotizaciones/".$filen.".xlsx",
							"accion"			=>	"Sube Archivo"
						];
					$data['cambios']=$this->cambio_md->insert($cambios);
					$mensaje=[	"id"	=>	'Éxito',
								"desc"	=>	'Productos cargados correctamente en el Sistema',
								"type"	=>	'success'];
				}else{
					$mensaje=[	"id"	=>	'Error',
								"desc"	=>	'Los Productos no se cargaron al Sistema',
								"type"	=>	'error'];
				}
			}
			$this->jsonResponse($mensaje);
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

		$this->cellStyle("A1:C1", "000000", "FFFFFF", TRUE, 12, "Franklin Gothic Book");
		$border_style= array('borders' => array('right' => array('style' =>
			PHPExcel_Style_Border::BORDER_THIN,'color' => array('argb' => '000000'),)));

		$hoja->setCellValue("A1", "ID")->getColumnDimension('A')->setWidth(7); //Nombre y ajuste de texto a la columna
		$hoja->setCellValue("B1", "DESCRIPCIÓN")->getColumnDimension('B')->setWidth(40);		
		$hoja->setCellValue("C1", "CANTIDAD")->getColumnDimension('C')->setWidth(20);

		$productos = $this->ver_mdl->get(NULL,0);
		$row_print = 2;
		if ($productos){
			foreach ($productos as $key => $value){
				$hoja->setCellValue("A{$row_print}", $value->id_verdura);
				$hoja->setCellValue("B{$row_print}", $value->descripcion);
				if ($value->id_verdura === 103 || $value->id_verdura === "103") {
					$this->cellStyle("B{$row_print}", "FF0000", "000000", TRUE, 12, "Franklin Gothic Book");
				}
				$row_print +=1;
			}
		}

		$hoja->getStyle("A2:C{$row_print}")
                 ->getAlignment()
                 ->setHorizontal(\PHPExcel_Style_Alignment::HORIZONTAL_LEFT);
		$hoja->getStyle("B1:B{$row_print}")
                 ->getAlignment()
                 ->setHorizontal(\PHPExcel_Style_Alignment::HORIZONTAL_LEFT);

        $dias = array("DOMINGO","LUNES","MARTES","MIÉRCOLES","JUEVES","VIERNES","SÁBADO");
		$meses = array("ENERO","FEBRERO","MARZO","ABRIL","MAYO","JUNIO","JULIO","AGOSTO","SEPTIEMBRE","OCTUBRE","NOVIEMBRE","DICIEMBRE");

		$fecha =  $dias[date('w')]." ".date('d')." DE ".$meses[date('n')-1]. " DEL ".date('Y') ;
		$file_name = "FORMATO EXISTENCIAS VERDURA ".$fecha.".xlsx"; //Nombre del documento con extención
		header("Content-Type: application/vnd.ms-excel; charset=utf-8");
		header("Content-Disposition: attachment;filename=".$file_name);
		header("Cache-Control: max-age=0");
		$excel_Writer = PHPExcel_IOFactory::createWriter($this->excelfile, "Excel2007");
		$excel_Writer->save("php://output");

	}


	public function upload_pedidos(){
		$fecha = new DateTime(date('Y-m-d H:i:s'));
		$this->load->library("excelfile");
		ini_set("memory_limit", -1);
		$file = $_FILES["file_verduras"]["tmp_name"];
		$sheet = PHPExcel_IOFactory::load($file);
		$objExcel = PHPExcel_IOFactory::load($file);
		$sheet = $objExcel->getSheet(0);
		$num_rows = $sheet->getHighestDataRow();
		$tienda = $this->session->userdata('id_usuario');
		
		$cfile =  $this->user_md->get(NULL, ['id_usuario' => $tienda])[0];
		$nams = preg_replace('/\s+/', '_', $cfile->nombre);
		$filen = "PedidosVerduras".$nams."".rand();
		$config['upload_path']          = base_url('./assets/uploads/pedidos/'); 
        $config['allowed_types']        = 'xlsx|xls';
        $config['max_size']             = 1000;
        $config['max_width']            = 10024;
        $config['max_height']           = 7680;
        $this->load->library('upload', $config);
        $this->upload->initialize($config);
        $this->upload->do_upload('file_verduras',$filen);
		for ($i=1; $i<=$num_rows; $i++) {
			$productos = $this->ver_mdl->get("id_verdura",['id_verdura'=>$sheet->getCell('A'.$i)->getValue()])[0];
			if (sizeof($productos) > 0) {
				$exis = $this->exver_mdl->get(NULL,["WEEKOFYEAR(fecha_registro)" => $this->weekNumber($fecha->format('Y-m-d H:i:s')),"id_tienda"=>$tienda,"id_verdura"=>$productos->id_verdura])[0];
				$column_two = $sheet->getCell('C'.$i)->getValue() == "" ? 0 : $sheet->getCell('C'.$i)->getValue();
				$new_existencias[$i]=[
					"id_verdura"	=>	$productos->id_verdura,
					"id_tienda"		=>	$tienda,
					"total"			=>	$column_two,
					"fecha_registro"	=>	$fecha->format('Y-m-d H:i:s')
				];
				if($exis){
					$data['cotizacion']=$this->exver_mdl->update($new_existencias[$i], ['id_existencia' => $exis->id_existencia]);
				}else{
					$data['cotizacion']=$this->exver_mdl->insert($new_existencias[$i]);
				}
			}
		}
		if (isset($new_existencias)) {
			$aprov = $this->user_md->get(NULL, ['id_usuario'=>$tienda])[0];
			$cambios=[
					"id_usuario"		=>	$this->session->userdata('id_usuario'),
					"fecha_cambio"		=>	date("Y-m-d H:i:s"),
					"antes"				=>	"El usuario sube existencias verduras de la tienda ".$aprov->nombre,
					"despues"			=>	"assets/uploads/pedidos/".$filen.".xlsx",
					"accion"			=>	"Sube existencias verduras"
				];
			$data['cambios']=$this->cambio_md->insert($cambios);
			$mensaje=[	"id"	=>	'Éxito',
						"desc"	=>	'Existencias cargados correctamente en el Sistema',
						"type"	=>	'success'];
		}else{
			$mensaje=[	"id"	=>	'Error',
						"desc"	=>	'Existencias no se cargaron al Sistema',
						"type"	=>	'error'];
		}
		$this->jsonResponse($mensaje);
	}

	public function changeprice(){
		$busca = $this->input->post("values");
		$new_producto=[
				"id_verdura" => $busca["id_verdura"],//Recupera el id_usuario activo
				"precio" => $busca["precio"]];
		$data ['id_producto']=$this->ver_mdl->update($new_producto,$busca["id_verdura"]);
		$this->jsonResponse($busca);
	}

	public function print_formato(){
		ini_set("memory_limit", "-1");
		$this->load->library("excelfile");
		$styleArray = array(
		  'borders' => array(
		    'allborders' => array(
		      'style' => PHPExcel_Style_Border::BORDER_MEDIUM
		    )
		  ),
		  'alignment' => array(
		       'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
		       'vertical' => PHPExcel_Style_Alignment::VERTICAL_CENTER,
		   ) 
		);
		$styleArray2 = array(
		  'borders' => array(
		    'allborders' => array(
		      'style' => PHPExcel_Style_Border::BORDER_THIN
		    )
		  ),
		  'alignment' => array(
		       'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
		       'vertical' => PHPExcel_Style_Alignment::VERTICAL_CENTER,
		   ) 
		);
		$hoja = $this->excelfile->getActiveSheet();


		$data["dias"] = array("DOMINGO","LUNES","MARTES","MIÉRCOLES","JUEVES","VIERNES","SÁBADO");
		$data["meses"] = array("ENERO","FEBRERO","MARZO","ABRIL","MAYO","JUNIO","JULIO","AGOSTO","SEPTIEMBRE","OCTUBRE","NOVIEMBRE","DICIEMBRE");
		$day = date('w');
		$fecha =  $data["dias"][date('w')]." ".date('d')." DE ".$data["meses"][date('n')-1]. " DEL ".date('Y') ;

		$hoja->mergeCells('A1:D1');
		$hoja->mergeCells('E1:N1');
		$this->excelfile->getActiveSheet()->getStyle('A1:N1')->applyFromArray($styleArray);
		$this->excelfile->getActiveSheet()->getStyle('A3:N3')->applyFromArray($styleArray);
		$this->excelfile->getActiveSheet()->getStyle('A2:N2')->applyFromArray($styleArray);
		$this->excelfile->getActiveSheet()->getStyle('A4:N4')->applyFromArray($styleArray);

		$this->cellStyle("A1", "000000", "000000", FALSE, 22, "Arial Rounded MT Bold");
		$this->cellStyle("E1", "fcd6b4", "000000", TRUE, 18, "Arial Rounded MT Bold");
		$hoja->setCellValue("A1", "CONTROL DE MERMA")->getColumnDimension('A')->setWidth(50);
		$hoja->setCellValue("E1", "VERDURAS")->getColumnDimension('E')->setWidth(13);
		$hoja->mergeCells('A2:E2');
		$hoja->mergeCells('F2:N2');
		$this->cellStyle("A2", "99ffcc", "000000", FALSE, 16, "Arial Rounded MT Bold");
		$this->cellStyle("F2", "FFFFFF", "000000", FALSE, 16, "Arial Rounded MT Bold");
		$hoja->setCellValue("A2", "GRUPO ABARROTES AZTECA");
		$hoja->setCellValue("E2", "AL: ");
		$hoja->mergeCells('A3:E3');
		$hoja->mergeCells('F3:N3');
		$this->cellStyle("F3", "FFFFFF", "000000", FALSE, 18, "Arial Rounded MT Bold");
		$hoja->setCellValue("F3", $fecha);

		$this->cellStyle("A4", "FFFFFF", "000000", FALSE, 14, "Franklin Gothic Book");
		$hoja->setCellValue("A4", "Descripción");

		$this->cellStyle("B4", "948a54", "000000",TRUE, 12, "Franklin Gothic Book");
		$hoja->setCellValue("B4", "CEDIS");
		$this->cellStyle("C4", "92d050", "000000",TRUE, 12, "Franklin Gothic Book");
		$hoja->setCellValue("C4", "SUPER");
		$this->cellStyle("D4", "BFBFBF", "000000",TRUE, 12, "Franklin Gothic Book");
		$hoja->setCellValue("D4", "VILLAS");
		$this->cellStyle("E4", "FFFF00", "000000",TRUE, 9, "Franklin Gothic Book");
		$hoja->setCellValue("E4", "SOLIDARIDAD");
		$this->cellStyle("F4", "00b050", "000000",TRUE, 12, "Franklin Gothic Book");
		$hoja->setCellValue("F4", "TIENDA");
		$this->cellStyle("G4", "00b0f0", "000000",TRUE, 8, "Franklin Gothic Book");
		$hoja->setCellValue("G4", "ULTRAMARINOS");
		$this->cellStyle("H4", "e26b0a", "000000",TRUE, 9, "Franklin Gothic Book");
		$hoja->setCellValue("H4", "TRINCHERAS");
		$this->cellStyle("I4", "ff999b", "000000",TRUE, 9, "Franklin Gothic Book");
		$hoja->setCellValue("I4", "MERCADO");
		$this->cellStyle("J4", "b1a0c7", "000000",TRUE, 12, "Franklin Gothic Book");
		$hoja->setCellValue("J4", "TENENCIA");
		$this->cellStyle("K4", "FF3737", "000000",TRUE, 12, "Franklin Gothic Book");
		$hoja->setCellValue("K4", "TIJERAS");

		$this->cellStyle("L4", "FFFFFF", "000000",TRUE, 11, "Franklin Gothic Book");
		$hoja->setCellValue("L4", "TOTAL KGS");
		$this->cellStyle("M4", "FFFFFF", "000000",TRUE, 12, "Franklin Gothic Book");
		$hoja->setCellValue("M4", "PRECIO");
		$this->cellStyle("N4", "FFFFFF", "000000",TRUE, 10, "Franklin Gothic Book");
		$hoja->setCellValue("N4", "TOTAL GRAL")->getColumnDimension('N')->setWidth(15);

		$ced="=";$sup="=";$vil="=";$sol="=";$tie="=";$ult="=";$tri="=";$mer="=";$ten="=";$tij="=";

		$productos = $this->ver_mdl->getExistencias(NULL);
		$row_print = 5;
		if ($productos){
			foreach ($productos as $key => $value){
				$this->excelfile->getActiveSheet()->getStyle('A'.$row_print.':M'.$row_print)->applyFromArray($styleArray2);
				$this->excelfile->getActiveSheet()->getStyle('N'.$row_print)->applyFromArray($styleArray);
				$this->cellStyle("A{$row_print}", "FFFFFF", "000000",FALSE, 12, "Euphemia");
				$this->cellStyle("B{$row_print}:N{$row_print}", "FFFFFF", "000000",FALSE, 14, "Franklin Gothic Book");
				$this->cellStyle("L{$row_print}", "FFFFFF", "000000",TRUE, 14, "Franklin Gothic Book");
				$hoja->setCellValue("A{$row_print}", $value->descripcion);
				$hoja->setCellValue("B{$row_print}", $value->cedis)->getColumnDimension('B')->setWidth(13);
				$hoja->setCellValue("C{$row_print}", $value->super)->getColumnDimension('C')->setWidth(13);
				$hoja->setCellValue("D{$row_print}", $value->villas)->getColumnDimension('D')->setWidth(13);
				$hoja->setCellValue("E{$row_print}", $value->soli)->getColumnDimension('E')->setWidth(13);
				$hoja->setCellValue("F{$row_print}", $value->tienda)->getColumnDimension('F')->setWidth(13);
				$hoja->setCellValue("G{$row_print}", $value->ultra)->getColumnDimension('G')->setWidth(13);
				$hoja->setCellValue("H{$row_print}", $value->trinch)->getColumnDimension('H')->setWidth(13);
				$hoja->setCellValue("I{$row_print}", $value->merca)->getColumnDimension('I')->setWidth(13);
				$hoja->setCellValue("J{$row_print}", $value->tenen)->getColumnDimension('J')->setWidth(13);
				$hoja->setCellValue("K{$row_print}", $value->tije)->getColumnDimension('K')->setWidth(13);
				$hoja->setCellValue("L{$row_print}", "=SUM(B{$row_print}:K{$row_print})")->getColumnDimension('L')->setWidth(13);
				$hoja->setCellValue("M{$row_print}", $value->precio)->getColumnDimension('M')->setWidth(13);
				$hoja->setCellValue("N{$row_print}", "=M{$row_print}*L{$row_print}")->getStyle("N{$row_print}")->getNumberFormat()->setFormatCode('"$"#,##0.00_-');
				$ced=$ced."(B".$row_print."*M".$row_print.")+";
				$sup=$sup."(C".$row_print."*M".$row_print.")+";
				$vil=$vil."(D".$row_print."*M".$row_print.")+";
				$sol=$sol."(E".$row_print."*M".$row_print.")+";
				$tie=$tie."(F".$row_print."*M".$row_print.")+";
				$ult=$ult."(G".$row_print."*M".$row_print.")+";
				$tri=$tri."(H".$row_print."*M".$row_print.")+";
				$mer=$mer."(I".$row_print."*M".$row_print.")+";
				$ten=$ten."(J".$row_print."*M".$row_print.")+";
				$tij=$tij."(K".$row_print."*M".$row_print.")+";
				$row_print +=1;
			}
		}

		$this->excelfile->getActiveSheet()->getStyle('N'.$row_print)->applyFromArray($styleArray);
		$hoja->mergeCells('L'.$row_print.':M'.$row_print);
		$this->cellStyle("L{$row_print}:N{$row_print}", "FFFFFF", "000000",FALSE, 12, "Arial Rounded MT Bold");
		$hoja->setCellValue("M{$row_print}", "TOTAL FINAL");
		$hoja->setCellValue("N{$row_print}", "=SUM(N5:N".($row_print-1).")")->getStyle("N{$row_print}")->getNumberFormat()->setFormatCode('"$"#,##0.00_-');
		$this->cellStyle("B{$row_print}", "948a54", "000000",TRUE, 12, "Franklin Gothic Book");
		$hoja->setCellValue("B{$row_print}", substr($ced,0,-1))->getStyle("B{$row_print}")->getNumberFormat()->setFormatCode('"$"#,##0.00_-');
		$this->cellStyle("C{$row_print}", "92d050", "000000",TRUE, 12, "Franklin Gothic Book");
		$hoja->setCellValue("C{$row_print}", substr($sup,0,-1))->getStyle("C{$row_print}")->getNumberFormat()->setFormatCode('"$"#,##0.00_-');
		$this->cellStyle("D{$row_print}", "BFBFBF", "000000",TRUE, 12, "Franklin Gothic Book");
		$hoja->setCellValue("D{$row_print}", substr($vil,0,-1))->getStyle("D{$row_print}")->getNumberFormat()->setFormatCode('"$"#,##0.00_-');
		$this->cellStyle("E{$row_print}", "FFFF00", "000000",TRUE, 9, "Franklin Gothic Book");
		$hoja->setCellValue("E{$row_print}", substr($sol,0,-1))->getStyle("E{$row_print}")->getNumberFormat()->setFormatCode('"$"#,##0.00_-');
		$this->cellStyle("F{$row_print}", "00b050", "000000",TRUE, 12, "Franklin Gothic Book");
		$hoja->setCellValue("F{$row_print}", substr($tie,0,-1))->getStyle("F{$row_print}")->getNumberFormat()->setFormatCode('"$"#,##0.00_-');
		$this->cellStyle("G{$row_print}", "00b0f0", "000000",TRUE, 8, "Franklin Gothic Book");
		$hoja->setCellValue("G{$row_print}", substr($ult,0,-1))->getStyle("G{$row_print}")->getNumberFormat()->setFormatCode('"$"#,##0.00_-');
		$this->cellStyle("H{$row_print}", "e26b0a", "000000",TRUE, 9, "Franklin Gothic Book");
		$hoja->setCellValue("H{$row_print}", substr($tri,0,-1))->getStyle("H{$row_print}")->getNumberFormat()->setFormatCode('"$"#,##0.00_-');
		$this->cellStyle("I{$row_print}", "ff999b", "000000",TRUE, 9, "Franklin Gothic Book");
		$hoja->setCellValue("I{$row_print}", substr($mer,0,-1))->getStyle("I{$row_print}")->getNumberFormat()->setFormatCode('"$"#,##0.00_-');
		$this->cellStyle("J{$row_print}", "b1a0c7", "000000",TRUE, 12, "Franklin Gothic Book");
		$hoja->setCellValue("J{$row_print}", substr($ten,0,-1))->getStyle("J{$row_print}")->getNumberFormat()->setFormatCode('"$"#,##0.00_-');
		$this->cellStyle("K{$row_print}", "FF3737", "000000",TRUE, 12, "Franklin Gothic Book");
		$hoja->setCellValue("K{$row_print}", substr($tij,0,-1))->getStyle("K{$row_print}")->getNumberFormat()->setFormatCode('"$"#,##0.00_-');

		$row_print++;
		$row_print++;
		$row_print++;


		$productos = $this->ver_mdl->getExistenciasJ(NULL);
		if ($productos){
			foreach ($productos as $key => $value){
				$this->excelfile->getActiveSheet()->getStyle('A'.$row_print.':M'.$row_print.'')->applyFromArray($styleArray2);
				$this->excelfile->getActiveSheet()->getStyle('N'.$row_print)->applyFromArray($styleArray);
				$this->cellStyle("A{$row_print}", "FF0000", "000000",FALSE, 12, "Euphemia");
				$this->cellStyle("B{$row_print}:N{$row_print}", "FFFFFF", "000000",FALSE, 14, "Franklin Gothic Book");
				$this->cellStyle("L{$row_print}", "FFFFFF", "000000",TRUE, 14, "Franklin Gothic Book");

				$hoja->setCellValue("A{$row_print}", $value->descripcion);
				$hoja->setCellValue("B{$row_print}", $value->cedis);
				$hoja->setCellValue("C{$row_print}", $value->super);
				$hoja->setCellValue("D{$row_print}", $value->villas);
				$hoja->setCellValue("E{$row_print}", $value->soli);
				$hoja->setCellValue("F{$row_print}", $value->tienda);
				$hoja->setCellValue("G{$row_print}", $value->ultra);
				$hoja->setCellValue("H{$row_print}", $value->trinch);
				$hoja->setCellValue("I{$row_print}", $value->merca);
				$hoja->setCellValue("J{$row_print}", $value->tenen);
				$hoja->setCellValue("K{$row_print}", $value->tije);
				$hoja->setCellValue("L{$row_print}", "=SUM(B{$row_print}:K{$row_print})");
				$hoja->setCellValue("M{$row_print}", $value->precio);
				$hoja->setCellValue("N{$row_print}", "=M{$row_print}*L{$row_print}")->getStyle("N{$row_print}")->getNumberFormat()->setFormatCode('"$"#,##0.00_-');
				$row_print +=1;
			}
		}


        $dias = array("DOMINGO","LUNES","MARTES","MIÉRCOLES","JUEVES","VIERNES","SÁBADO");
		$meses = array("ENERO","FEBRERO","MARZO","ABRIL","MAYO","JUNIO","JULIO","AGOSTO","SEPTIEMBRE","OCTUBRE","NOVIEMBRE","DICIEMBRE");

		$fecha =  $dias[date('w')]." ".date('d')." DE ".$meses[date('n')-1]. " DEL ".date('Y') ;
		$file_name = "FORMATO VERDURAS (MERMA) ".$fecha.".xlsx"; //Nombre del documento con extención
		header("Content-Type: application/vnd.ms-excel; charset=utf-8");
		header("Content-Disposition: attachment;filename=".$file_name);
		header("Cache-Control: max-age=0");
		$excel_Writer = PHPExcel_IOFactory::createWriter($this->excelfile, "Excel2007");
		$excel_Writer->save("php://output");

	}
	
	public function print_productos2(){
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

		$this->cellStyle("A1:C1", "000000", "FFFFFF", TRUE, 12, "Franklin Gothic Book");
		$border_style= array('borders' => array('right' => array('style' =>
			PHPExcel_Style_Border::BORDER_THIN,'color' => array('argb' => '000000'),)));

		$hoja->setCellValue("A1", "ID")->getColumnDimension('A')->setWidth(7); //Nombre y ajuste de texto a la columna
		$hoja->setCellValue("B1", "DESCRIPCIÓN")->getColumnDimension('B')->setWidth(40);		
		$hoja->setCellValue("C1", "PRECIO")->getColumnDimension('C')->setWidth(20);

		$productos = $this->ver_mdl->get(NULL,0);
		$row_print = 2;
		if ($productos){
			foreach ($productos as $key => $value){
				$hoja->setCellValue("A{$row_print}", $value->id_verdura);
				$hoja->setCellValue("B{$row_print}", $value->descripcion);
				if ($value->id_verdura === 103 || $value->id_verdura === "103") {
					$this->cellStyle("B{$row_print}", "FF0000", "000000", TRUE, 12, "Franklin Gothic Book");
				}
				$row_print +=1;
			}
		}

		$hoja->getStyle("A2:C{$row_print}")
                 ->getAlignment()
                 ->setHorizontal(\PHPExcel_Style_Alignment::HORIZONTAL_LEFT);
		$hoja->getStyle("B1:B{$row_print}")
                 ->getAlignment()
                 ->setHorizontal(\PHPExcel_Style_Alignment::HORIZONTAL_LEFT);

        $dias = array("DOMINGO","LUNES","MARTES","MIÉRCOLES","JUEVES","VIERNES","SÁBADO");
		$meses = array("ENERO","FEBRERO","MARZO","ABRIL","MAYO","JUNIO","JULIO","AGOSTO","SEPTIEMBRE","OCTUBRE","NOVIEMBRE","DICIEMBRE");

		$fecha =  $dias[date('w')]." ".date('d')." DE ".$meses[date('n')-1]. " DEL ".date('Y') ;
		$file_name = "FORMATO PRECIOS VERDURA ".$fecha.".xlsx"; //Nombre del documento con extención
		header("Content-Type: application/vnd.ms-excel; charset=utf-8");
		header("Content-Disposition: attachment;filename=".$file_name);
		header("Cache-Control: max-age=0");
		$excel_Writer = PHPExcel_IOFactory::createWriter($this->excelfile, "Excel2007");
		$excel_Writer->save("php://output");

	}

	public function upload_precios(){
		$fecha = new DateTime(date('Y-m-d H:i:s'));
		$this->load->library("excelfile");
		ini_set("memory_limit", -1);
		$file = $_FILES["file_precios"]["tmp_name"];
		$sheet = PHPExcel_IOFactory::load($file);
		$objExcel = PHPExcel_IOFactory::load($file);
		$sheet = $objExcel->getSheet(0);
		$num_rows = $sheet->getHighestDataRow();
	
		$filen = "PreciosVerduras".$fecha->format('Y-m-d H:i:s')."".rand();
		$config['upload_path']          = base_url('./assets/uploads/pedidos/'); 
        $config['allowed_types']        = 'xlsx|xls';
        $config['max_size']             = 1000;
        $config['max_width']            = 10024;
        $config['max_height']           = 7680;
        $this->load->library('upload', $config);
        $this->upload->initialize($config);
        $this->upload->do_upload('file_precios',$filen);
		for ($i=1; $i<=$num_rows; $i++) {
			$productos = $this->ver_mdl->get("id_verdura",['id_verdura'=>$sheet->getCell('A'.$i)->getValue()])[0];
			if (sizeof($productos) > 0) {
				$column_two = $sheet->getCell('C'.$i)->getValue() == "" ? 0 : $sheet->getCell('C'.$i)->getValue();
				$new_existencias[$i]=[
					"precio"			=>	$column_two
				];
				$data['cotizacion']=$this->ver_mdl->update($new_existencias[$i], ['id_verdura' => $productos->id_verdura]);
			}
		}
		if (isset($new_existencias)) {
			$cambios=[
					"id_usuario"		=>	$this->session->userdata('id_usuario'),
					"fecha_cambio"		=>	date("Y-m-d H:i:s"),
					"antes"				=>	"El usuario sube precios verduras",
					"despues"			=>	"assets/uploads/pedidos/".$filen.".xlsx",
					"accion"			=>	"Sube precios verduras"
				];
			$data['cambios']=$this->cambio_md->insert($cambios);
			$mensaje=[	"id"	=>	'Éxito',
						"desc"	=>	'Precios cargados correctamente en el Sistema',
						"type"	=>	'success'];
		}else{
			$mensaje=[	"id"	=>	'Error',
						"desc"	=>	'Precios no se cargaron al Sistema',
						"type"	=>	'error'];
		}
		$this->jsonResponse($mensaje);
	}



}

/* End of file Lunes.php */
/* Location: ./application/controllers/Lunes.php */
