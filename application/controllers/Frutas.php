<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Frutas extends MY_Controller {

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
		$this->load->model("Frutas_model", "ver_mdl");
		$this->load->model("Exfruta_model", "exver_mdl");
	}

	public function index(){
		$data['links'] = [
			'/assets/css/plugins/dataTables/dataTables.bootstrap',
			'/assets/css/plugins/dataTables/dataTables.responsive',
			'/assets/css/plugins/dataTables/dataTables.tableTools.min',
			'/assets/css/plugins/dataTables/buttons.dataTables.min',
		];

		$data['scripts'] = [
			'/scripts/frutas',
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
		$this->estructura("Frutas/existencias", $data);
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
	        $this->upload->do_upload('file_precios',$filen);
			$this->load->library("excelfile");
			ini_set("memory_limit", -1);
			$file = $_FILES["file_precios"]["tmp_name"];
			$filename=$_FILES['file_precios']['name'];
			$sheet = PHPExcel_IOFactory::load($file);
			$objExcel = PHPExcel_IOFactory::load($file);
			$sheet = $objExcel->getSheet(0);
			$num_rows = $sheet->getHighestDataRow();

		
			for ($i=1; $i<=$num_rows; $i++) {
				$productos = $this->ver_mdl->get("id_fruta",['codigo'=> $sheet->getCell('A'.$i)->getValue()])[0];
				if (sizeof($productos) <= 0) {
					$new_producto=[
							"id_familia" => 1,//Recupera el id_usuario activo
							"codigo" => $sheet->getCell('A'.$i)->getValue(),
							"descripcion" => $sheet->getCell('B'.$i)->getValue(),
							"precio" => $sheet->getCell('C'.$i)->getValue()];
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
							"antes"				=>	"El usuario sube productos Frutas",
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

		$hoja->setCellValue("A1", "CÓDIGO")->getColumnDimension('A')->setWidth(25); //Nombre y ajuste de texto a la columna
		$hoja->setCellValue("B1", "DESCRIPCIÓN")->getColumnDimension('B')->setWidth(40);		
		$hoja->setCellValue("C1", "CANTIDAD")->getColumnDimension('C')->setWidth(20);

		$productos = $this->ver_mdl->get(NULL,0);
		$row_print = 2;
		if ($productos){
			foreach ($productos as $key => $value){
				$hoja->setCellValue("A{$row_print}", $value->codigo);
				$hoja->setCellValue("B{$row_print}", $value->descripcion);
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
		$file = $_FILES["file_frutas"]["tmp_name"];
		$sheet = PHPExcel_IOFactory::load($file);
		$objExcel = PHPExcel_IOFactory::load($file);
		$sheet = $objExcel->getSheet(0);
		$num_rows = $sheet->getHighestDataRow();
		$tienda = $this->session->userdata('id_usuario');
		
		$cfile =  $this->user_md->get(NULL, ['id_usuario' => $tienda])[0];
		$nams = preg_replace('/\s+/', '_', $cfile->nombre);
		$filen = "PedidosFrutas".$nams."".rand();
		$config['upload_path']          = './assets/uploads/pedidos/'; 
        $config['allowed_types']        = 'xlsx|xls';
        $config['max_size']             = 1000;
        $config['max_width']            = 10024;
        $config['max_height']           = 7680;
        $this->load->library('upload', $config);
        $this->upload->initialize($config);
        $this->upload->do_upload('file_frutas',$filen);
		for ($i=1; $i<=$num_rows; $i++) {
			$productos = $this->ver_mdl->get("id_fruta",['codigo'=>$sheet->getCell('A'.$i)->getValue()])[0];
			if (sizeof($productos) > 0) {
				$exis = $this->exver_mdl->get(NULL,["WEEKOFYEAR(fecha_registro)" => $this->weekNumber($fecha->format('Y-m-d H:i:s')),"id_tienda"=>$tienda,"id_fruta"=>$productos->id_fruta])[0];
				$column_two = $sheet->getCell('C'.$i)->getValue() == "" ? 0 : $sheet->getCell('C'.$i)->getValue();
				$new_existencias[$i]=[
					"id_fruta"	=>	$productos->id_fruta,
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
					"antes"				=>	"El usuario sube existencias frutas de la tienda ".$aprov->nombre,
					"despues"			=>	"assets/uploads/pedidos/".$filen.".xlsx",
					"accion"			=>	"Sube existencias frutas"
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
				"id_fruta" => $busca["id_fruta"],//Recupera el id_usuario activo
				"precio" => $busca["precio"]];
		$data ['id_producto']=$this->ver_mdl->update($new_producto,$busca["id_fruta"]);
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

		$hoja->mergeCells('A1:E1');
		$hoja->mergeCells('F1:O1');
		$this->excelfile->getActiveSheet()->getStyle('A1:O1')->applyFromArray($styleArray);
		$this->excelfile->getActiveSheet()->getStyle('A3:O3')->applyFromArray($styleArray);
		$this->excelfile->getActiveSheet()->getStyle('A2:O2')->applyFromArray($styleArray);
		$this->excelfile->getActiveSheet()->getStyle('A4:O4')->applyFromArray($styleArray);

		$this->cellStyle("A1", "000000", "FFFFFF", FALSE, 22, "Arial Rounded MT Bold");
		$this->cellStyle("B1", "000000", "FFFFFF", FALSE, 22, "Arial Rounded MT Bold");
		$this->cellStyle("F1", "b7dee8", "000000", TRUE, 18, "Arial Rounded MT Bold");
		$hoja->setCellValue("A1", "CONTROL DE MERMA")->getColumnDimension('B')->setWidth(50);
		$hoja->setCellValue("F1", "FRUTAS")->getColumnDimension('F')->setWidth(13);
		$hoja->mergeCells('A2:F2');
		$hoja->mergeCells('G2:O2');
		$this->cellStyle("A2", "99ffcc", "000000", FALSE, 16, "Arial Rounded MT Bold");
		$this->cellStyle("F2", "FFFFFF", "000000", FALSE, 16, "Arial Rounded MT Bold");
		$hoja->setCellValue("A2", "GRUPO ABARROTES AZTECA");
		$hoja->setCellValue("F2", "AL: ");
		$hoja->mergeCells('A3:F3');
		$hoja->mergeCells('G3:O3');
		$this->cellStyle("G3", "FFFFFF", "000000", FALSE, 18, "Arial Rounded MT Bold");
		$hoja->setCellValue("G3", $fecha);

		$this->cellStyle("A4", "FFFFFF", "000000", FALSE, 14, "Franklin Gothic Book");
		$hoja->setCellValue("A4", "Código");
		$this->cellStyle("B4", "FFFFFF", "000000", FALSE, 14, "Franklin Gothic Book");
		$hoja->setCellValue("B4", "Descripción");

		$this->cellStyle("C4", "948a54", "000000",TRUE, 12, "Franklin Gothic Book");
		$hoja->setCellValue("C4", "CEDIS");
		$this->cellStyle("D4", "92d050", "000000",TRUE, 12, "Franklin Gothic Book");
		$hoja->setCellValue("D4", "SUPER");
		$this->cellStyle("E4", "BFBFBF", "000000",TRUE, 12, "Franklin Gothic Book");
		$hoja->setCellValue("E4", "VILLAS");
		$this->cellStyle("F4", "FFFF00", "000000",TRUE, 9, "Franklin Gothic Book");
		$hoja->setCellValue("F4", "SOLIDARIDAD");
		$this->cellStyle("G4", "00b050", "000000",TRUE, 12, "Franklin Gothic Book");
		$hoja->setCellValue("G4", "TIENDA");
		$this->cellStyle("H4", "00b0f0", "000000",TRUE, 8, "Franklin Gothic Book");
		$hoja->setCellValue("H4", "ULTRAMARINOS");
		$this->cellStyle("I4", "e26b0a", "000000",TRUE, 9, "Franklin Gothic Book");
		$hoja->setCellValue("I4", "TRINCHERAS");
		$this->cellStyle("J4", "ff999b", "000000",TRUE, 9, "Franklin Gothic Book");
		$hoja->setCellValue("J4", "MERCADO");
		$this->cellStyle("K4", "b1a0c7", "000000",TRUE, 12, "Franklin Gothic Book");
		$hoja->setCellValue("K4", "TENENCIA");
		$this->cellStyle("L4", "FF3737", "000000",TRUE, 12, "Franklin Gothic Book");
		$hoja->setCellValue("L4", "TIJERAS");

		$this->cellStyle("M4", "FFFFFF", "000000",TRUE, 11, "Franklin Gothic Book");
		$hoja->setCellValue("M4", "TOTAL KGS");
		$this->cellStyle("N4", "FFFFFF", "000000",TRUE, 12, "Franklin Gothic Book");
		$hoja->setCellValue("N4", "PRECIO");
		$this->cellStyle("O4", "FFFFFF", "000000",TRUE, 10, "Franklin Gothic Book");
		$hoja->setCellValue("O4", "TOTAL GRAL")->getColumnDimension('O')->setWidth(15);

		$ced="=";$sup="=";$vil="=";$sol="=";$tie="=";$ult="=";$tri="=";$mer="=";$ten="=";$tij="=";

		$productos = $this->ver_mdl->getExistencias(NULL);
		$row_print = 5;
		if ($productos){
			foreach ($productos as $key => $value){
				$this->excelfile->getActiveSheet()->getStyle('A'.$row_print.':N'.$row_print)->applyFromArray($styleArray2);
				$this->excelfile->getActiveSheet()->getStyle('O'.$row_print)->applyFromArray($styleArray);
				$this->cellStyle("A{$row_print}", "FFFFFF", "000000",FALSE, 12, "Euphemia");
				$this->cellStyle("B{$row_print}", "FFFFFF", "000000",FALSE, 12, "Euphemia");
				$this->cellStyle("C{$row_print}:O{$row_print}", "FFFFFF", "000000",FALSE, 14, "Franklin Gothic Book");
				$this->cellStyle("M{$row_print}", "FFFFFF", "000000",TRUE, 14, "Franklin Gothic Book");
				$hoja->setCellValue("A{$row_print}", $value->codigo)->getColumnDimension('A')->setWidth(13);
				$hoja->setCellValue("B{$row_print}", $value->descripcion);
				$hoja->setCellValue("C{$row_print}", $value->cedis)->getColumnDimension('C')->setWidth(13);
				$hoja->setCellValue("D{$row_print}", $value->super)->getColumnDimension('D')->setWidth(13);
				$hoja->setCellValue("E{$row_print}", $value->villas)->getColumnDimension('E')->setWidth(13);
				$hoja->setCellValue("F{$row_print}", $value->soli)->getColumnDimension('F')->setWidth(13);
				$hoja->setCellValue("G{$row_print}", $value->tienda)->getColumnDimension('G')->setWidth(13);
				$hoja->setCellValue("H{$row_print}", $value->ultra)->getColumnDimension('H')->setWidth(13);
				$hoja->setCellValue("I{$row_print}", $value->trinch)->getColumnDimension('I')->setWidth(13);
				$hoja->setCellValue("J{$row_print}", $value->merca)->getColumnDimension('J')->setWidth(13);
				$hoja->setCellValue("K{$row_print}", $value->tenen)->getColumnDimension('K')->setWidth(13);
				$hoja->setCellValue("L{$row_print}", $value->tije)->getColumnDimension('L')->setWidth(13);
				$hoja->setCellValue("M{$row_print}", "=SUM(C{$row_print}:L{$row_print})")->getColumnDimension('M')->setWidth(13);
				$hoja->setCellValue("N{$row_print}", $value->precio)->getColumnDimension('N')->setWidth(13);
				$hoja->setCellValue("O{$row_print}", "=N{$row_print}*M{$row_print}")->getStyle("O{$row_print}")->getNumberFormat()->setFormatCode('"$"#,##0.00_-');
				$ced=$ced."(C".$row_print."*N".$row_print.")+";
				$sup=$sup."(D".$row_print."*N".$row_print.")+";
				$vil=$vil."(E".$row_print."*N".$row_print.")+";
				$sol=$sol."(F".$row_print."*N".$row_print.")+";
				$tie=$tie."(G".$row_print."*N".$row_print.")+";
				$ult=$ult."(H".$row_print."*N".$row_print.")+";
				$tri=$tri."(I".$row_print."*N".$row_print.")+";
				$mer=$mer."(J".$row_print."*N".$row_print.")+";
				$ten=$ten."(K".$row_print."*N".$row_print.")+";
				$tij=$tij."(L".$row_print."*N".$row_print.")+";
				$row_print +=1;
			}
		}

		$this->excelfile->getActiveSheet()->getStyle('O'.$row_print)->applyFromArray($styleArray);
		$hoja->mergeCells('M'.$row_print.':N'.$row_print);
		$this->cellStyle("M{$row_print}:O{$row_print}", "FFFFFF", "000000",FALSE, 12, "Arial Rounded MT Bold");
		$hoja->setCellValue("M{$row_print}", "TOTAL FINAL");
		$hoja->setCellValue("O{$row_print}", "=SUM(O5:O".($row_print-1).")")->getStyle("O{$row_print}")->getNumberFormat()->setFormatCode('"$"#,##0.00_-');
		$this->cellStyle("C{$row_print}", "948a54", "000000",TRUE, 12, "Franklin Gothic Book");
		$hoja->setCellValue("C{$row_print}", substr($ced,0,-1))->getStyle("C{$row_print}")->getNumberFormat()->setFormatCode('"$"#,##0.00_-');
		$this->cellStyle("D{$row_print}", "92d050", "000000",TRUE, 12, "Franklin Gothic Book");
		$hoja->setCellValue("D{$row_print}", substr($sup,0,-1))->getStyle("D{$row_print}")->getNumberFormat()->setFormatCode('"$"#,##0.00_-');
		$this->cellStyle("E{$row_print}", "BFBFBF", "000000",TRUE, 12, "Franklin Gothic Book");
		$hoja->setCellValue("E{$row_print}", substr($vil,0,-1))->getStyle("E{$row_print}")->getNumberFormat()->setFormatCode('"$"#,##0.00_-');
		$this->cellStyle("F{$row_print}", "FFFF00", "000000",TRUE, 12, "Franklin Gothic Book");
		$hoja->setCellValue("F{$row_print}", substr($sol,0,-1))->getStyle("F{$row_print}")->getNumberFormat()->setFormatCode('"$"#,##0.00_-');
		$this->cellStyle("G{$row_print}", "00b050", "000000",TRUE, 12, "Franklin Gothic Book");
		$hoja->setCellValue("G{$row_print}", substr($tie,0,-1))->getStyle("G{$row_print}")->getNumberFormat()->setFormatCode('"$"#,##0.00_-');
		$this->cellStyle("H{$row_print}", "00b0f0", "000000",TRUE, 12, "Franklin Gothic Book");
		$hoja->setCellValue("H{$row_print}", substr($ult,0,-1))->getStyle("H{$row_print}")->getNumberFormat()->setFormatCode('"$"#,##0.00_-');
		$this->cellStyle("I{$row_print}", "e26b0a", "000000",TRUE, 12, "Franklin Gothic Book");
		$hoja->setCellValue("I{$row_print}", substr($tri,0,-1))->getStyle("I{$row_print}")->getNumberFormat()->setFormatCode('"$"#,##0.00_-');
		$this->cellStyle("J{$row_print}", "ff999b", "000000",TRUE, 12, "Franklin Gothic Book");
		$hoja->setCellValue("J{$row_print}", substr($mer,0,-1))->getStyle("J{$row_print}")->getNumberFormat()->setFormatCode('"$"#,##0.00_-');
		$this->cellStyle("K{$row_print}", "b1a0c7", "000000",TRUE, 12, "Franklin Gothic Book");
		$hoja->setCellValue("K{$row_print}", substr($ten,0,-1))->getStyle("K{$row_print}")->getNumberFormat()->setFormatCode('"$"#,##0.00_-');
		$this->cellStyle("L{$row_print}", "FF3737", "000000",TRUE, 12, "Franklin Gothic Book");
		$hoja->setCellValue("L{$row_print}", substr($tij,0,-1))->getStyle("L{$row_print}")->getNumberFormat()->setFormatCode('"$"#,##0.00_-');

		$row_print++;
		$row_print++;
		$row_print++;

		$productos = $this->ver_mdl->getExistenciasJ(NULL);
		if ($productos){
			foreach ($productos as $key => $value){
				$this->excelfile->getActiveSheet()->getStyle('A'.$row_print.':N'.$row_print.'')->applyFromArray($styleArray2);
				$this->excelfile->getActiveSheet()->getStyle('O'.$row_print)->applyFromArray($styleArray);
				$this->cellStyle("A{$row_print}", "FF0000", "000000",FALSE, 12, "Euphemia");
				$this->cellStyle("B{$row_print}", "FF0000", "000000",FALSE, 12, "Euphemia");
				$this->cellStyle("C{$row_print}:O{$row_print}", "FFFFFF", "000000",FALSE, 14, "Franklin Gothic Book");
				$this->cellStyle("M{$row_print}", "FFFFFF", "000000",TRUE, 14, "Franklin Gothic Book");

				$hoja->setCellValue("A{$row_print}", $value->codigo);
				$hoja->setCellValue("B{$row_print}", $value->descripcion);
				$hoja->setCellValue("C{$row_print}", $value->cedis);
				$hoja->setCellValue("D{$row_print}", $value->super);
				$hoja->setCellValue("E{$row_print}", $value->villas);
				$hoja->setCellValue("F{$row_print}", $value->soli);
				$hoja->setCellValue("G{$row_print}", $value->tienda);
				$hoja->setCellValue("H{$row_print}", $value->ultra);
				$hoja->setCellValue("I{$row_print}", $value->trinch);
				$hoja->setCellValue("J{$row_print}", $value->merca);
				$hoja->setCellValue("K{$row_print}", $value->tenen);
				$hoja->setCellValue("L{$row_print}", $value->tije);
				$hoja->setCellValue("M{$row_print}", "=SUM(C{$row_print}:L{$row_print})");
				$hoja->setCellValue("N{$row_print}", $value->precio);
				$hoja->setCellValue("O{$row_print}", "=N{$row_print}*M{$row_print}")->getStyle("O{$row_print}")->getNumberFormat()->setFormatCode('"$"#,##0.00_-');
				$row_print +=1;
			}
		}

        $dias = array("DOMINGO","LUNES","MARTES","MIÉRCOLES","JUEVES","VIERNES","SÁBADO");
		$meses = array("ENERO","FEBRERO","MARZO","ABRIL","MAYO","JUNIO","JULIO","AGOSTO","SEPTIEMBRE","OCTUBRE","NOVIEMBRE","DICIEMBRE");

		$fecha =  $dias[date('w')]." ".date('d')." DE ".$meses[date('n')-1]. " DEL ".date('Y') ;
		$file_name = "FORMATO FRUTAS (MERMA) ".$fecha.".xlsx"; //Nombre del documento con extención
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
				$hoja->setCellValue("A{$row_print}", $value->id_fruta);
				$hoja->setCellValue("B{$row_print}", $value->descripcion);
				$hoja->setCellValue("C{$row_print}", $value->precio)->getStyle("C{$row_print}")->getNumberFormat()->setFormatCode('"$"#,##0.00_-');
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
		$file_name = "FORMATO PRECIOS FRUTAS ".$fecha.".xlsx"; //Nombre del documento con extención
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
	
		$filen = "PreciosFrutas".$fecha->format('Y-m-d H:i:s')."".rand();
		$config['upload_path']          = './assets/uploads/pedidos/'; 
        $config['allowed_types']        = 'xlsx|xls';
        $config['max_size']             = 1000;
        $config['max_width']            = 10024;
        $config['max_height']           = 7680;
        $this->load->library('upload', $config);
        $this->upload->initialize($config);
        $this->upload->do_upload('file_precios',$filen);
		for ($i=1; $i<=$num_rows; $i++) {
			$productos = $this->ver_mdl->get("id_fruta",['id_fruta'=>$sheet->getCell('A'.$i)->getValue()])[0];
			if (sizeof($productos) > 0) {
				$column_two = $sheet->getCell('C'.$i)->getValue() == "" ? 0 : $sheet->getCell('C'.$i)->getValue();
				$new_existencias[$i]=[
					"precio"			=>	$column_two
				];
				$data['cotizacion']=$this->ver_mdl->update($new_existencias[$i], ['id_fruta' => $productos->id_fruta]);
			}
		}
		if (isset($new_existencias)) {
			$cambios=[
					"id_usuario"		=>	$this->session->userdata('id_usuario'),
					"fecha_cambio"		=>	date("Y-m-d H:i:s"),
					"antes"				=>	"El usuario sube precios frutas",
					"despues"			=>	"assets/uploads/pedidos/".$filen.".xlsx",
					"accion"			=>	"Sube precios frutas"
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

	public function getExTns(){
		$extns = $this->ver_mdl->getExTns(NULL);
		$this->jsonResponse($extns);
	}


	public function add_producto(){
		$data["title"]="REGISTRAR FRUTA";
		$data["view"] =$this->load->view("Frutas/new_fruta", $data, TRUE);
		$data["button"]="<button class='btn btn-success new_producto' type='button'>
							<span class='bold'><i class='fa fa-floppy-o'></i></span> &nbsp;Guardar
						</button>";
		$this->jsonResponse($data);
	}

	public function accion($param){
		$estats = $this->input->post('estatus');

		$user = $this->session->userdata();
		$producto = ['codigo'	=>	$this->input->post('codigo'),
					'descripcion'	=>	strtoupper($this->input->post('nombre')),
					'precio'	=>	$this->input->post('precio'),
					'id_familia'=>	1
		];
		$getProducto = $this->ver_mdl->get(NULL, ['codigo'=>$producto['codigo']])[0];
		switch ($param) {
			case (substr($param, 0, 1) === 'I'):
				if (sizeof($getProducto) == 0) {
					$cambios = [
							"id_usuario" => $user["id_usuario"],
							"fecha_cambio" => date('Y-m-d H:i:s'),
							"antes" => "Registro de nueva fruta",
							"despues" => "Código: ".$producto['codigo']." /Nombre: ".$producto['descripcion']." /Precio: ".$producto['precio']];
					$data['cambios'] = $this->cambio_md->insert($cambios);
					$data ['id_producto']=$this->ver_mdl->insert($producto);
					$mensaje = ["id" 	=> 'Éxito',
								"desc"	=> 'Fruta registrada correctamente',
								"type"	=> 'success'];
				}else{
					$mensaje = ["id" 	=> 'Alerta',
								"desc"	=> 'El código ya esta registrada en el Sistema',
								"type"	=> 'warning'];
				}
				break;

			case (substr($param, 0, 1) === 'U'):
				$antes = $this->pro_md->get(NULL, ['id_producto'=>$this->input->post('id_producto')])[0];
				$data ['id_producto'] = $this->pro_md->update($producto, $this->input->post('id_producto'));
				$cambios = [
						"id_usuario" => $user["id_usuario"],
						"fecha_cambio" => date('Y-m-d H:i:s'),
						"antes" => "id: ".$antes->id_producto." /Código: ".$antes->codigo." /Nombre: ".$antes->nombre." /Familia: ".$antes->id_familia,
						"despues" => "Nuevos datos -> Código: ".$producto['codigo']." /Nombre: ".$producto['nombre']." /Familia: ".$producto['id_familia']];
				$data['cambios'] = $this->cambio_md->insert($cambios);
				$mensaje = [
					"id" 	=> 'Éxito',
					"desc"	=> 'Producto actualizado correctamente',
					"type"	=> 'success'
				];
				break;

			default:
				$antes = $this->pro_md->get(NULL, ['id_producto'=>$this->input->post('id_producto')])[0];
				$data ['id_producto'] = $this->pro_md->update(["estatus" => 0,"codigo"=>"Eliminado"], $this->input->post('id_producto'));
				$cambios = [
						"id_usuario" => $user["id_usuario"],
						"fecha_cambio" => date('Y-m-d H:i:s'),
						"antes" => "id: ".$antes->id_producto." /Código: ".$antes->codigo." /Nombre: ".$antes->nombre." /Familia: ".$antes->id_familia,
						"despues" => "Producto eliminado"];
				$data['cambios'] = $this->cambio_md->insert($cambios);
				$mensaje = [
					"id" 	=> 'Éxito',
					"desc"	=> 'Producto eliminado correctamente',
					"type"	=> 'success'
				];
				break;
		}
		$this->jsonResponse($mensaje);
	}



}

/* End of file Lunes.php */
/* Location: ./application/controllers/Lunes.php */
