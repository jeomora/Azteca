<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Facturas extends MY_Controller {

	function __construct(){
		parent::__construct();
		$this->load->model("Productos_model", "pro_md");
		$this->load->model("Familias_model", "fam_md");
		$this->load->model("Cambios_model", "cambio_md");
		$this->load->model("Usuarios_model", "usua_mdl");
		$this->load->model("Prodcaja_model", "pcaja_md");
		$this->load->model("Finales_model", "final_md");
		$this->load->model("Facturas_model", "fact_md");
		$this->load->model("Compara_model", "comp_md");
		$this->load->model("Invoice_model", "invoice_md");
	}

	public function comparar(){
		$data['links'] = [
			'/assets/css/plugins/dataTables/dataTables.bootstrap',
			'/assets/css/plugins/dataTables/dataTables.responsive',
			'/assets/css/plugins/dataTables/dataTables.tableTools.min',
			'/assets/css/plugins/dataTables/buttons.dataTables.min',
		];

		$data['scripts'] = [
			'/scripts/facturas',
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
		$data["proveedores"] = $this->usua_mdl->get(NULL,["estatus <>"=>0,"id_grupo"=>2]);
		$data["tiendas"]	 = $this->usua_mdl->getColors(NULL);
		$this->estructura("Facturas/comparar", $data);
	}

	public function getFacturas($values){
		$facturas = $this->fact_md->getFacturas(NULL,$values);
		$this->jsonResponse($facturas);
	}

	public function updateCosto($inp){
		$busca = $this->input->post("values");
		$facturas = $this->comp_md->update(["costo"=>$busca["costo"]],$inp);
		$this->jsonResponse($inp);
	}

	public function getDetails(){
		$busca = $this->input->post("values");
		$facturas = $this->fact_md->getDetails(NULL,$busca);
		$this->jsonResponse($facturas);
	}

	public function getPedidos(){
		$busca = $this->input->post("values");
		$productos = $this->final_md->getPedidos(NULL,$busca);
		$this->jsonResponse($productos);
	}

	public function pedidos(){
		$data['links'] = [
			'/assets/css/plugins/dataTables/dataTables.bootstrap',
			'/assets/css/plugins/dataTables/dataTables.responsive',
			'/assets/css/plugins/dataTables/dataTables.tableTools.min',
			'/assets/css/plugins/dataTables/buttons.dataTables.min',
		];

		$data['scripts'] = [
			'/scripts/sped',
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
		$data["proveedores"]=$this->usua_mdl->get(NULL,["estatus <>"=>0,"id_grupo"=>2]);
		$this->estructura("Facturas/pedidos", $data);
	}

	public function getit(){
		include_once APPPATH . 'vendor/autoload.php';
		$parser = new \Smalot\PdfParser\Parser();
		$pdf = $parser->parseFile(APPPATH.'vendor\smalot\pdfparser\samples\vv.pdf');

		$text = $pdf->getText();
		$details  = $pdf->getDetails();

		/*ini_set("memory_limit", "-1");
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

		$this->cellStyle("A1:D2", "000000", "FFFFFF", TRUE, 12, "Franklin Gothic Book");
		$border_style= array('borders' => array('right' => array('style' =>
			PHPExcel_Style_Border::BORDER_THIN,'color' => array('argb' => '000000'),)));

		$this->cellStyle("F1:G2", "000000", "FFFFFF", TRUE, 12, "Franklin Gothic Book");
		$border_style= array('borders' => array('right' => array('style' =>
			PHPExcel_Style_Border::BORDER_THIN,'color' => array('argb' => '000000'),)));

		$hoja->setCellValue("B1", $text)->getColumnDimension('B')->setWidth(60);

        $dias = array("DOMINGO","LUNES","MARTES","MIÉRCOLES","JUEVES","VIERNES","SÁBADO");
		$meses = array("ENERO","FEBRERO","MARZO","ABRIL","MAYO","JUNIO","JULIO","AGOSTO","SEPTIEMBRE","OCTUBRE","NOVIEMBRE","DICIEMBRE");

		$fecha =  $dias[date('w')]." ".date('d')." DE ".$meses[date('n')-1]. " DEL ".date('Y') ;
		$file_name = "SUBIR PRODUCTOS ".$fecha.".xlsx"; //Nombre del documento con extención
		header("Content-Type: application/vnd.ms-excel; charset=utf-8");
		header("Content-Disposition: attachment;filename=".$file_name);
		header("Cache-Control: max-age=0");
		$excel_Writer = PHPExcel_IOFactory::createWriter($this->excelfile, "Excel2007");
		$excel_Writer->save("php://output");*/
		if (strpos($text, 'Unitario Con Impuesto')) {
			$ind1 = strpos($text, 'Unitario Con Impuesto');
		}else{
			$ind1 = strpos($text, 'Unitario con Impuestos');
		}
		$folio = substr($text, (strpos( $text, 'Dirección De Entrega' )+100),(200));
		$folio = substr($folio, (strpos( $folio, '<td>' )+4),((strpos( $folio, '</td>' )-strpos( $folio, '<td>' )-4)));
		$this->jsonResponse(substr($text,($ind1),strlen($text)));
	}

	public function uploadPedidos(){
		$arrays = array();
		$user = $this->session->userdata();
		$array = array();
		$this->load->library("excelfile");
		ini_set("memory_limit", -1);
		$file = $_FILES["file_codigos"]["tmp_name"];
		$sheet = PHPExcel_IOFactory::load($file);
		$objExcel = PHPExcel_IOFactory::load($file);
		$sheet = $objExcel->getSheet(0);
		$num_rows = $sheet->getHighestDataRow();
		$proveedor = $this->input->post('proveedor');
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

				$codiga = $this->final_md->get(NULL,['id_producto'=> $codigo->id_producto,"id_proveedor"=>$proveedor,"WEEKOFYEAR(fecha_registro)"=>$this->weekNumber()])[0];
				if (sizeof($codiga) > 0) {
					$data ['id_prodcaja']=$this->final_md->update($new_producto, $codiga->id_final);
				}else{
					array_push($arrays, $new_producto);
					$data ['id_prodcaja']=$this->final_md->insert($new_producto);
				}
			}
		}
		$cambios = [
				"id_usuario" => $user["id_usuario"],
				"fecha_cambio" => date('Y-m-d H:i:s'),
				"antes" => "",
				"despues" => "El usuario registro pedidos finales de Duero"];
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

	public function buscaCodigos(){
		$busca = $this->input->post("values");
		$busca2 = $this->input->post("values2");
		$productos = $this->final_md->buscaCodigos(NULL,$busca,$busca2);
		$this->jsonResponse($productos);
	}

	public function uploadFacturas($id_proveedor,$id_tienda,$tend){
		$user = $this->session->userdata();
		$this->load->library("excelfile");
		ini_set("memory_limit", -1);
		$file = $_FILES["file_factura"]["tmp_name"];
		$sheet = PHPExcel_IOFactory::load($file);
		$objExcel = PHPExcel_IOFactory::load($file);
		$sheet = $objExcel->getSheet(0);
		$num_rows = $sheet->getHighestDataRow();
		$proveedor = $id_proveedor;
		if ($sheet->getCell('A1')->getValue() === "FOLIO FACTURA") {
			$folio = htmlspecialchars($sheet->getCell('B1')->getValue(), ENT_QUOTES, 'UTF-8');	
			$fecha = $sheet->getCell('D1')->getValue();
			$no = 3;
		}else{
			$folio = htmlspecialchars($sheet->getCell('D1')->getValue(), ENT_QUOTES, 'UTF-8');
			$fecha = $sheet->getCell('E1')->getValue();
			$no = 1;
		}
		
		if ($folio === "" || strlen($folio) < 5) {
			$folio = rand(1000000000, 9000000000);
		}
		
		if ($fecha === "" || $fecha === null) {
			$fecha = new DateTime(date('Y-m-d H:i:s'));
		}

		$this->db->query("delete from facturas where folio = '".$folio."' AND id_proveedor = ".$proveedor."");
		for ($i=$no; $i<=$num_rows; $i++) {
			$codigo = htmlspecialchars($sheet->getCell('A'.$i)->getValue(), ENT_QUOTES, 'UTF-8');
			$cantidad = $this->getOldVal($sheet,$i,"B");
			$precio = $this->getOldVal($sheet,$i,"C");
			$descripcion = $this->invoice_md->get(NULL,["id_proveedor"=>$id_proveedor,"codigo"=>$codigo])[0];
			
			
			if (sizeof($codigo) > 0) {
				$new_producto=[
					"folio" => $folio,
					"id_proveedor" => $proveedor,
					"precio" => $precio,
					"codigo" => $descripcion->id_invoice,
					"descripcion" => $descripcion->descripcion,
					"fecha_registro" 	=> $fecha->format('Y-m-d H:i:s'),
					"cantidad" => $cantidad,
					"id_tienda"=> $id_tienda
				];

				$codiga = $this->fact_md->getThem(NULL,$folio,$proveedor,$id_tienda,$codigo,$precio,$cantidad);
				if ($codiga) {
				}else{
					$data['id_prodcaja']=$this->fact_md->insert($new_producto);
				}
			}
		}
		//Obtener elementos factura con pedidos para asociar
		$query = "pd.nombre,pd.codigo,fc.descripcion,fc.folio,fc.codigo as factu,fc.cantidad,fc.precio,fc.estatus,fc.fecha_registro,fc.id_tienda,fc.id_proveedor,pc.id_prodcaja,pc.id_producto, f.".$tend." as total,f.costo,f.promocion,f.id_final FROM facturas fc LEFT JOIN prodcaja pc ON fc.codigo = pc.id_invoice AND pc.id_proveedor = ".$proveedor." LEFT JOIN productos pd ON pc.id_producto = pd.id_producto LEFT JOIN finales f ON pd.id_producto = f.id_producto AND f.id_proveedor = ".$proveedor." AND WEEKOFYEAR(f.fecha_registro) = WEEKOFYEAR(CURDATE()) WHERE fc.id_proveedor = ".$proveedor." AND WEEKOFYEAR(fc.fecha_registro) = WEEKOFYEAR(CURDATE()) AND fc.folio = '".$folio."' AND fc.id_tienda = '".$id_tienda."' GROUP BY fc.id_factura ORDER BY fc.id_factura ASC";
		$factus = $this->fact_md->getFactos(NULL,json_encode($query));
		//Obtener pedidos no asociados a productos en la fatcura
		$query2 = "f2.id_final,f2.costo,f2.promocion,pd.nombre,pd.codigo,pd.id_producto,f2.".$tend." as total from finales f2 LEFT JOIN productos pd ON f2.id_producto = pd.id_producto WHERE f2.id_final NOT IN(SELECT f1.id_final from finales f1 WHERE f1.id_final IN (SELECT f.id_final FROM facturas fc LEFT JOIN prodcaja pc ON fc.codigo = pc.id_invoice AND pc.id_proveedor = ".$proveedor." LEFT JOIN productos pd2 ON pc.id_producto = pd2.id_producto LEFT JOIN finales f ON pd2.id_producto = f.id_producto AND f.id_proveedor = ".$proveedor." WHERE fc.id_proveedor = ".$proveedor." AND WEEKOFYEAR(fc.fecha_registro) = WEEKOFYEAR(CURDATE()) AND fc.folio = '".$folio."')) AND id_proveedor = ".$proveedor." AND WEEKOFYEAR(f2.fecha_registro) = WEEKOFYEAR(CURDATE()) AND f2.".$tend." > 0 ORDER BY f2.id_final ASC";
		$factus2 = $this->fact_md->getFactos(NULL,json_encode($query2));
		$cambios = [
				"id_usuario" => $user["id_usuario"],
				"fecha_cambio" => date('Y-m-d H:i:s'),
				"antes" => "El usuario registro factura",
				"despues" => "FOLIO : ".$folio];
		$data['cambios'] = $this->cambio_md->insert($cambios);
		$mensaje=[	
			"id"	=>	'Éxito',
			"desc"	=>	'Factura se cargó correctamente en el Sistema',
			"type"	=>	'success'];

		$this->jsonResponse(array($factus,$factus2,$num_rows,$folio));
	}

	public function guardaComparacion(){
		$user = $this->session->userdata();
		$value = json_decode($this->input->post('values'), true);
		$prodcod = "";
		$this->jsonResponse($value);
		$this->db->query("delete from comparacion where folio = '".$value[0]["folio"]."' AND id_proveedor = ".$value[0]["id_proveedor"]."");
		foreach ($value as $key => $v) {
			$producto = $this->pro_md->get(NULL,["codigo"=>$v["producto"]])[0];
			if ($producto) {
				$invoice = $this->invoice_md->get(NULL,["id_proveedor"=>$v["id_proveedor"],"id_invoice"=>$v["factura"]])[0];
				if ($invoice) {
					$prodcaja=$this->pcaja_md->get(NULL,["id_invoice"=>$invoice->id_invoice,"id_proveedor"=>$v["id_proveedor"],"estatus"=>1,"id_producto"=>$producto->id_producto])[0];
					if(!$prodcaja) {
						$new_prodcaja=[
							"id_producto" => $producto->id_producto,
							"id_proveedor" => $v["id_proveedor"],
							"id_invoice" => $invoice->id_invoice,
						];
						$data['prodcaja'] = $this->pcaja_md->insert($new_prodcaja);
					}
				}
			}
			$compara=$this->comp_md->get(NULL,["folio"=>$v["folio"],"id_proveedor"=>$v["id_proveedor"],"id_tienda"=>$v["id_tienda"],"factura"=>$v["factura"]])[0];
			$new_compara=[
				"folio"	=> $v["folio"],
				"factura" => $v["factura"],
				"producto" => $v["producto"],
				"id_tienda" => $v["id_tienda"],
				"id_proveedor" => $v["id_proveedor"],
				"costo" => $v["costo"],
				"devolucion" => $v["devolucion"],
				"devueltos" => $v["devueltos"],
				"gift" => $v["gift"],
				"gifted" => $v["gifted"],
				"cuantos"	=> $v["cuantos"]
			];


			$data['prodcaja'] = $this->comp_md->insert($new_compara);

			/*if ($compara) {
				if ($compara->gift <> $v["gift"] || $compara->devueltos <> $v["devueltos"]) {
					
				} else {
					$data['prodcaja'] = $this->comp_md->update($new_compara,$compara->id_comparacion);
				}			
			}else{
				$data['prodcaja'] = $this->comp_md->insert($new_compara);
			}*/
		}		
	}

	public function fill_excel($folio,$which){
		ini_set("memory_limit", "-1");
		$this->load->library("excelfile");
		$hoja = $this->excelfile->getActiveSheet();
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
		$styleArrayHL = array(
		  'borders' => array(
		    'allborders' => array(
		      'style' => PHPExcel_Style_Border::BORDER_MEDIUM
		    )
		  ),
		  'alignment' => array(
		       'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_LEFT,
		       'vertical' => PHPExcel_Style_Alignment::VERTICAL_CENTER,
		   ) 
		);
		$styleArrayHR = array(
		  'borders' => array(
		    'allborders' => array(
		      'style' => PHPExcel_Style_Border::BORDER_MEDIUM
		    )
		  ),
		  'alignment' => array(
		       'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_RIGHT,
		       'vertical' => PHPExcel_Style_Alignment::VERTICAL_CENTER,
		   ) 
		);

		$stylebottom = array(
		  'borders' => array(
		    'top' => array(
		      'style' => PHPExcel_Style_Border::BORDER_MEDIUM
		    ),
		    'left' => array(
		      'style' => PHPExcel_Style_Border::BORDER_MEDIUM
		    ),
		    'right' => array(
		      'style' => PHPExcel_Style_Border::BORDER_MEDIUM
		    ),
		    'bottom' => array(
		    	'style' => PHPExcel_Style_Border::BORDER_THIN,
		    	'color' => array('rgb' => 'cfcfcf')
		    )
		  ),
		  'alignment' => array(
		       'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
		       'vertical' => PHPExcel_Style_Alignment::VERTICAL_CENTER,
		   ) 
		);
		$styletop = array(
		  'borders' => array(
		    'bottom' => array(
		      'style' => PHPExcel_Style_Border::BORDER_MEDIUM
		    ),
		    'left' => array(
		      'style' => PHPExcel_Style_Border::BORDER_MEDIUM
		    ),
		    'right' => array(
		      'style' => PHPExcel_Style_Border::BORDER_MEDIUM
		    ),
		    'top' => array(
		    	'style' => PHPExcel_Style_Border::BORDER_THIN,
		    	'color' => array('rgb' => 'cfcfcf')
		    )
		  ),
		  'alignment' => array(
		       'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
		       'vertical' => PHPExcel_Style_Alignment::VERTICAL_CENTER,
		   ) 
		);
		$styleleft = array(
		  'borders' => array(
		    'bottom' => array(
		      'style' => PHPExcel_Style_Border::BORDER_MEDIUM
		    ),
		    'top' => array(
		      'style' => PHPExcel_Style_Border::BORDER_MEDIUM
		    ),
		    'right' => array(
		      'style' => PHPExcel_Style_Border::BORDER_MEDIUM
		    ),
		    'left' => array(
		    	'style' => PHPExcel_Style_Border::BORDER_THIN,
		    	'color' => array('rgb' => 'cfcfcf')
		    )
		  ),
		  'alignment' => array(
		       'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
		       'vertical' => PHPExcel_Style_Alignment::VERTICAL_CENTER,
		   ) 
		);
		$styleright = array(
		  'borders' => array(
		    'bottom' => array(
		      'style' => PHPExcel_Style_Border::BORDER_MEDIUM
		    ),
		    'top' => array(
		      'style' => PHPExcel_Style_Border::BORDER_MEDIUM
		    ),
		    'left' => array(
		      'style' => PHPExcel_Style_Border::BORDER_MEDIUM
		    ),
		    'right' => array(
		    	'style' => PHPExcel_Style_Border::BORDER_THIN,
		    	'color' => array('rgb' => 'cfcfcf')
		    )
		  ),
		  'alignment' => array(
		       'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
		       'vertical' => PHPExcel_Style_Alignment::VERTICAL_CENTER,
		   ) 
		);


		$woe = $this->comp_md->get(NULL,["id_comparacion"=>$folio])[0];
		$facturas = $this->fact_md->getDetails2(NULL,json_encode($woe),$which);
		$hoja->mergeCells('A1:I1');
		$this->cellStyle("A1", "".substr($facturas[0]->color,1,6), "000000", TRUE, 24, "Berlin Sans FB Demi");
		$hoja->setCellValue("A1", $facturas[0]->tienda." GRUPO AZTECA, S.A DE C.V")->getColumnDimension('A')->setWidth(60);
		$this->excelfile->getActiveSheet()->getStyle('A1:I1')->applyFromArray($styleArray);

		$hoja->mergeCells('A2:B3');
		$this->cellStyle("A2", "FFFFFF", "000000", FALSE, 18, "Arial Narrow");
		$hoja->setCellValue("A2", $facturas[0]->prove);
		$this->excelfile->getActiveSheet()->getStyle('A2:B3')->applyFromArray($styleArray);

		$hoja->mergeCells('C2:E2');
		$this->cellStyle("C2", "FFFFFF", "000000", FALSE, 14, "Arial Narrow");
		$hoja->setCellValue("C2", "Fecha de Reporte");
		$this->excelfile->getActiveSheet()->getStyle('C2:E2')->applyFromArray($styleright);
		$hoja->mergeCells('C3:E3');
		$this->cellStyle("C3", "FFFFFF", "000000", FALSE, 14, "Arial Narrow");
		$hoja->setCellValue("C3", "Fecha en Factura");
		$this->excelfile->getActiveSheet()->getStyle('C3:E3')->applyFromArray($styleright);
		$hoja->mergeCells('F2:I2');
		$this->cellStyle("F2", "FFFFFF", "000000", FALSE, 14, "Arial Narrow");
		$hoja->setCellValue('F2',$facturas[0]->fecha);  
		$this->excelfile->getActiveSheet()->getStyle('F2:I2')->applyFromArray($styleleft);
		$hoja->mergeCells('F3:I3');
		$this->cellStyle("F3", "FFFFFF", "000000", FALSE, 14, "Arial Narrow");
		$hoja->setCellValue("F3", "Fecha en Factura");
		$this->excelfile->getActiveSheet()->getStyle('F3:I3')->applyFromArray($styleleft);

		$hoja->mergeCells('A4:A5');
		$this->cellStyle("A4", "FFFFFF", "000000", FALSE, 14, "Arial Narrow");
		$hoja->setCellValue("A4", "DESCRIPCIÓN");
		$this->excelfile->getActiveSheet()->getStyle('A4:A5')->applyFromArray($styleArray);

		$hoja->mergeCells('B4:B5');
		$this->cellStyle("B4", "FFFFFF", "000000", FALSE, 9, "Arial Narrow");
		$hoja->setCellValue("B4", "PROMO")->getColumnDimension('B')->setWidth(16);
		$this->excelfile->getActiveSheet()->getStyle('B4:B5')->applyFromArray($styleArray);

		$this->cellStyle("C4", "FFFFFF", "000000", FALSE, 11, "Arial Narrow");
		$hoja->setCellValue("C4", "PRECIO EN")->getColumnDimension('C')->setWidth(16);
		$this->excelfile->getActiveSheet()->getStyle('C4')->applyFromArray($stylebottom);
		$this->cellStyle("C5", "FFFFFF", "000000", FALSE, 11, "Arial Narrow");
		$hoja->setCellValue("C5", "PEDIDO");
		$this->excelfile->getActiveSheet()->getStyle('C5')->applyFromArray($styletop);

		$this->cellStyle("D4", "FFFFFF", "000000", FALSE, 11, "Arial Narrow");
		$hoja->setCellValue("D4", "CANT")->getColumnDimension('D')->setWidth(10);
		$this->excelfile->getActiveSheet()->getStyle('D4')->applyFromArray($stylebottom);
		$this->cellStyle("D5", "FFFFFF", "000000", FALSE, 11, "Arial Narrow");
		$hoja->setCellValue("D5", "PEDIDO");
		$this->excelfile->getActiveSheet()->getStyle('D5')->applyFromArray($styletop);

		$this->cellStyle("E4", "FFFFFF", "000000", FALSE, 8, "Arial Narrow");
		$hoja->setCellValue("E4", "CANT")->getColumnDimension('E')->setWidth(10);
		$this->excelfile->getActiveSheet()->getStyle('E4')->applyFromArray($stylebottom);
		$this->cellStyle("E5", "FFFFFF", "000000", FALSE, 8, "Arial Narrow");
		$hoja->setCellValue("E5", "FACTURA");
		$this->excelfile->getActiveSheet()->getStyle('E5')->applyFromArray($styletop);

		$this->cellStyle("F4", "FFFFFF", "000000", FALSE, 10, "Arial Narrow");
		$hoja->setCellValue("F4", "PREC NETO")->getColumnDimension('F')->setWidth(12);
		$this->excelfile->getActiveSheet()->getStyle('F4')->applyFromArray($stylebottom);
		$this->cellStyle("F5", "FFFFFF", "000000", FALSE, 10, "Arial Narrow");
		$hoja->setCellValue("F5", "FACTURA");
		$this->excelfile->getActiveSheet()->getStyle('F5')->applyFromArray($styletop);

		$hoja->mergeCells('G4:G5');
		$this->cellStyle("G4", "FFFFFF", "000000", FALSE, 14, "Arial Narrow");
		$hoja->setCellValue("G4", "DIF.")->getColumnDimension('G')->setWidth(13);
		$this->excelfile->getActiveSheet()->getStyle('G4:G5')->applyFromArray($styleArray);

		$this->cellStyle("H4", "FFFFFF", "000000", FALSE, 14, "Arial Narrow");
		$hoja->setCellValue("H4", "NOTA")->getColumnDimension('H')->setWidth(16);
		$this->excelfile->getActiveSheet()->getStyle('H4')->applyFromArray($stylebottom);
		$this->cellStyle("H5", "FFFFFF", "000000", FALSE, 14, "Arial Narrow");
		$hoja->setCellValue("H5", "CREDITO");
		$this->excelfile->getActiveSheet()->getStyle('H5')->applyFromArray($styletop);

		$this->cellStyle("I4", "FFFFFF", "000000", FALSE, 14, "Arial Narrow");
		$hoja->setCellValue("I4", "TOTAL")->getColumnDimension('I')->setWidth(16);
		$this->excelfile->getActiveSheet()->getStyle('I4')->applyFromArray($stylebottom);
		$this->cellStyle("I5", "FFFFFF", "000000", FALSE, 14, "Arial Narrow");
		$hoja->setCellValue("I5", "A PAGAR");
		$this->excelfile->getActiveSheet()->getStyle('I5')->applyFromArray($styletop);
		$flag = 6;
		if ($facturas) {
			foreach ($facturas as $key => $value) {
				$this->cellStyle("A".$flag.":I".$flag, "FFFFFF", "000000", FALSE, 14, "Arial Narrow");
				$this->excelfile->getActiveSheet()->getStyle('A'.$flag)->applyFromArray($stylebottom);
				$hoja->setCellValue("A".$flag, $value->descripcion);
				if ($value->devolucion === 1 || $value->devolucion === "1") {
					$this->excelfile->getActiveSheet()->getStyle('A'.$flag)->applyFromArray($styleArray);
					if($value->pprod === "" || $value->pprod === NULL){
						$hoja->setCellValue("A".$flag, $value->descripcion);
					}else{
						$hoja->setCellValue("A".$flag, $value->pprod);	
					}
					
					$this->excelfile->getActiveSheet()->getStyle('B'.$flag)->applyFromArray($styleArray);
					$this->cellStyle("B".$flag, "FF0000", "000000", FALSE, 14, "Arial Narrow");
					$hoja->setCellValue("B".$flag, "DEVUELTO");
					$this->excelfile->getActiveSheet()->getStyle('C'.$flag)->applyFromArray($styleArray);
					$hoja->setCellValue("C".$flag, "")->getStyle("C{$flag}")->getNumberFormat()->setFormatCode('"$"#,##0.00_-');
					$this->excelfile->getActiveSheet()->getStyle('F'.$flag)->applyFromArray($styleArray);
					$hoja->setCellValue("F".$flag, $value->precio)->getStyle("F{$flag}")->getNumberFormat()->setFormatCode('"$"#,##0.00_-');
					$this->excelfile->getActiveSheet()->getStyle('E'.$flag)->applyFromArray($styleArray);
					$hoja->setCellValue("E".$flag, $value->devueltos)->getStyle("E{$flag}")->getNumberFormat()->setFormatCode('"$"#,##0.00_-');
					$this->excelfile->getActiveSheet()->getStyle('D'.$flag)->applyFromArray($styleArray);
					$hoja->setCellValue("D".$flag, "");
					$this->excelfile->getActiveSheet()->getStyle('G'.$flag)->applyFromArray($styleArray);
					$hoja->setCellValue("G".$flag, "=F{$flag}-C{$flag}")->getStyle("G{$flag}")->getNumberFormat()->setFormatCode('"$"#,##0.00_-');
					$this->excelfile->getActiveSheet()->getStyle('H'.$flag)->applyFromArray($styleArray);
					$hoja->setCellValue("H".$flag, "=G{$flag}*E{$flag}")->getStyle("H{$flag}")->getNumberFormat()->setFormatCode('"$"#,##0.00_-');
					$this->excelfile->getActiveSheet()->getStyle('I'.$flag)->applyFromArray($styleArray);
					$hoja->setCellValue("I".$flag, "=C{$flag}*E{$flag}")->getStyle("I{$flag}")->getNumberFormat()->setFormatCode('"$"#,##0.00_-');
					$flag++;
					$this->cellStyle("A".$flag.":I".$flag, "FFFFFF", "000000", FALSE, 14, "Arial Narrow");
					$this->excelfile->getActiveSheet()->getStyle('A'.$flag)->applyFromArray($styleArray);
					if($value->pprod === "" || $value->pprod === NULL){
						$hoja->setCellValue("A".$flag, $value->descripcion);
					}else{
						$hoja->setCellValue("A".$flag, $value->pprod);	
					}
					
					$this->excelfile->getActiveSheet()->getStyle('B'.$flag)->applyFromArray($styleArray);
					$hoja->setCellValue("B".$flag, "DIRECTO");
					$this->excelfile->getActiveSheet()->getStyle('C'.$flag)->applyFromArray($styleArray);
					$hoja->setCellValue("C".$flag, $value->costo)->getStyle("C{$flag}")->getNumberFormat()->setFormatCode('"$"#,##0.00_-');
					$this->excelfile->getActiveSheet()->getStyle('D'.$flag)->applyFromArray($styleArray);
					$hoja->setCellValue("D".$flag, $value->wey);
					$this->excelfile->getActiveSheet()->getStyle('E'.$flag)->applyFromArray($styleArray);
					$hoja->setCellValue("E".$flag, ($value->cuantos-$value->devueltos))->getStyle("E{$flag}")->getNumberFormat()->setFormatCode('"$"#,##0.00_-');
					$this->excelfile->getActiveSheet()->getStyle('F'.$flag)->applyFromArray($styleArray);
					$hoja->setCellValue("F".$flag, $value->precio)->getStyle("F{$flag}")->getNumberFormat()->setFormatCode('"$"#,##0.00_-');
					$this->excelfile->getActiveSheet()->getStyle('G'.$flag)->applyFromArray($styleArray);
					$hoja->setCellValue("G".$flag, "=F{$flag}-C{$flag}");
					$this->excelfile->getActiveSheet()->getStyle('H'.$flag)->applyFromArray($styleArray);
					$hoja->setCellValue("H".$flag, "=G{$flag}*E{$flag}")->getStyle("H{$flag}")->getNumberFormat()->setFormatCode('"$"#,##0.00_-');
					$this->excelfile->getActiveSheet()->getStyle('I'.$flag)->applyFromArray($styleArray);
					$hoja->setCellValue("I".$flag, "=C{$flag}*E{$flag}")->getStyle("I{$flag}")->getNumberFormat()->setFormatCode('"$"#,##0.00_-');

				} elseif ($value->gift === 1 || $value->gift === "1") {
					$this->excelfile->getActiveSheet()->getStyle('A'.$flag)->applyFromArray($styleArray);
					if($value->pprod === "" || $value->pprod === NULL){
						$hoja->setCellValue("A".$flag, $value->descripcion);
					}else{
						$hoja->setCellValue("A".$flag, $value->pprod);	
					}
					
					$this->excelfile->getActiveSheet()->getStyle('B'.$flag)->applyFromArray($styleArray);
					$this->cellStyle("B".$flag, "0000FF", "000000", FALSE, 14, "Arial Narrow");
					$hoja->setCellValue("B".$flag, "S/C");
					$this->excelfile->getActiveSheet()->getStyle('C'.$flag)->applyFromArray($styleArray);
					$hoja->setCellValue("C".$flag, "")->getStyle("C{$flag}")->getNumberFormat()->setFormatCode('"$"#,##0.00_-');
					$this->excelfile->getActiveSheet()->getStyle('D'.$flag)->applyFromArray($styleArray);
					$hoja->setCellValue("D".$flag, $value->wey);
					$this->excelfile->getActiveSheet()->getStyle('E'.$flag)->applyFromArray($styleArray);
					$hoja->setCellValue("E".$flag, $value->cuantos);
					$this->excelfile->getActiveSheet()->getStyle('F'.$flag)->applyFromArray($styleArray);
					$hoja->setCellValue("F".$flag, $value->precio)->getStyle("F{$flag}")->getNumberFormat()->setFormatCode('"$"#,##0.00_-');
					$this->excelfile->getActiveSheet()->getStyle('G'.$flag)->applyFromArray($styleArray);
					$hoja->setCellValue("G".$flag, "=F{$flag}-C{$flag}")->getStyle("G{$flag}")->getNumberFormat()->setFormatCode('"$"#,##0.00_-');
					$this->excelfile->getActiveSheet()->getStyle('H'.$flag)->applyFromArray($styleArray);
					$hoja->setCellValue("H".$flag, "=G{$flag}*E{$flag}")->getStyle("H{$flag}")->getNumberFormat()->setFormatCode('"$"#,##0.00_-');
					$this->excelfile->getActiveSheet()->getStyle('I'.$flag)->applyFromArray($styleArray);
					$hoja->setCellValue("I".$flag, "=C{$flag}*E{$flag}")->getStyle("I{$flag}")->getNumberFormat()->setFormatCode('"$"#,##0.00_-');
				} else{
					$this->excelfile->getActiveSheet()->getStyle('A'.$flag)->applyFromArray($styleArray);
					if($value->pprod === "" || $value->pprod === NULL){
						$hoja->setCellValue("A".$flag, $value->descripcion);
					}else{
						$hoja->setCellValue("A".$flag, $value->pprod);	
					}
					
					$this->excelfile->getActiveSheet()->getStyle('B'.$flag)->applyFromArray($styleArray);
					$hoja->setCellValue("B".$flag, "DIRECTO");
					$this->excelfile->getActiveSheet()->getStyle('C'.$flag)->applyFromArray($styleArray);
					$hoja->setCellValue("C".$flag, $value->costo)->getStyle("C{$flag}")->getNumberFormat()->setFormatCode('"$"#,##0.00_-');
					$this->excelfile->getActiveSheet()->getStyle('D'.$flag)->applyFromArray($styleArray);
					$hoja->setCellValue("D".$flag, $value->wey);
					$this->excelfile->getActiveSheet()->getStyle('E'.$flag)->applyFromArray($styleArray);
					$hoja->setCellValue("E".$flag, $value->cuantos);
					$this->excelfile->getActiveSheet()->getStyle('F'.$flag)->applyFromArray($styleArray);
					$hoja->setCellValue("F".$flag, $value->precio)->getStyle("F{$flag}")->getNumberFormat()->setFormatCode('"$"#,##0.00_-');
					$this->excelfile->getActiveSheet()->getStyle('G'.$flag)->applyFromArray($styleArray);
					$hoja->setCellValue("G".$flag, "=F{$flag}-C{$flag}")->getStyle("G{$flag}")->getNumberFormat()->setFormatCode('"$"#,##0.00_-');
					$this->excelfile->getActiveSheet()->getStyle('H'.$flag)->applyFromArray($styleArray);
					$hoja->setCellValue("H".$flag, "=G{$flag}*E{$flag}")->getStyle("H{$flag}")->getNumberFormat()->setFormatCode('"$"#,##0.00_-');
					$this->excelfile->getActiveSheet()->getStyle('I'.$flag)->applyFromArray($styleArray);
					$hoja->setCellValue("I".$flag, "=C{$flag}*E{$flag}")->getStyle("I{$flag}")->getNumberFormat()->setFormatCode('"$"#,##0.00_-');
				}
				$flag++;
			}
			if($facturas[0]->prove <> "SAHUAYO"){
				$this->cellStyle("A".$flag.":I".$flag, "FFFFFF", "000000", TRUE, 19, "Arial Narrow");
				$hoja->mergeCells('A'.$flag.":C".$flag);
				$this->excelfile->getActiveSheet()->getStyle('A'.$flag.":C".$flag)->applyFromArray($styleArrayHL);
				$this->excelfile->getActiveSheet()->getStyle('D'.$flag)->applyFromArray($styleArrayHL);
				$hoja->setCellValue("D".$flag, "FOLIO");
				$this->excelfile->getActiveSheet()->getStyle('E'.$flag)->applyFromArray($styleArrayHL);
				$hoja->setCellValue("E".$flag, "D");
				$hoja->mergeCells('F'.$flag.':G'.$flag);
				$this->excelfile->getActiveSheet()->getStyle('F'.$flag.':G'.$flag)->applyFromArray($styleArrayHL);
				$this->cellStyle("F".$flag, "00FFFF", "000000", TRUE, 19, "Arial Narrow");
				$hoja->mergeCells('H'.$flag.':H'.($flag+1));
				$this->excelfile->getActiveSheet()->getStyle('H'.$flag)->applyFromArray($styleArrayHL);
				$hoja->setCellValue("H".$flag, "=SUM(H6:H".($flag-1).")")->getStyle("H{$flag}")->getNumberFormat()->setFormatCode('"$"#,##0.00_-');
				$hoja->mergeCells('I'.$flag.':I'.($flag+1));
				$this->excelfile->getActiveSheet()->getStyle('I'.$flag)->applyFromArray($styleArrayHL);
				$hoja->setCellValue("I".$flag, "=SUM(I6:I".($flag-1).")")->getStyle("I{$flag}")->getNumberFormat()->setFormatCode('"$"#,##0.00_-');
				$this->cellStyle("H".$flag, "FF0000", "000000", TRUE, 16, "Arial Narrow");
				$this->cellStyle("I".$flag, "FFFF00", "000000", TRUE, 16, "Arial Narrow");
				$flag++;

				$hoja->mergeCells('A'.$flag.":C".$flag);
				$hoja->mergeCells('D'.$flag.":E".$flag);
				$hoja->mergeCells('F'.$flag.":G".$flag);
				$this->excelfile->getActiveSheet()->getStyle('I'.$flag)->applyFromArray($styleArrayHL);
				$this->excelfile->getActiveSheet()->getStyle('F'.$flag.':G'.$flag)->applyFromArray($styleArrayHL);
				$this->cellStyle("F".$flag, "00FFFF", "000000", TRUE, 19, "Arial Narrow");
				$hoja->setCellValue("F".$flag, $facturas[0]->folio);
				$this->excelfile->getActiveSheet()->getStyle('D'.$flag)->applyFromArray($styleArrayHL);
				$hoja->setCellValue("D".$flag, "CONTROL");
				$flag++;

				$hoja->mergeCells('D'.$flag.":F".$flag);
				$this->cellStyle("A".$flag.":I".$flag, "FFFFFF", "000000", TRUE, 22, "Arial Narrow");
				$this->cellStyle("A".$flag, "4f81bd", "000000", TRUE, 22, "Arial Narrow");
				$this->cellStyle("B".$flag.":C".$flag, "FF0000", "000000", TRUE, 22, "Arial Narrow");
				$this->excelfile->getActiveSheet()->getStyle('A'.$flag)->applyFromArray($styleArrayHL);
				$this->excelfile->getActiveSheet()->getStyle('B'.$flag)->applyFromArray($styleArrayHL);
				$hoja->setCellValue('B'.$flag, '=SUMIF(B6:B'.($flag-3).',"DEVUELTO",H6:H'.($flag-3).')')->getStyle("B{$flag}")->getNumberFormat()->setFormatCode('"$"#,##0.00_-');
				$this->cellStyle("D".$flag, "00b0f0", "000000", TRUE, 16, "Arial Narrow");
				$hoja->setCellValue("D".$flag, "APLICACIÓN DE CRED AC")->getStyle("D{$flag}")->getNumberFormat()->setFormatCode('"$"#,##0.00_-');
				$this->excelfile->getActiveSheet()->getStyle('C'.$flag)->applyFromArray($styleArrayHL);
				$hoja->setCellValue("C".$flag, "=H".$flag."-B".$flag)->getStyle("C{$flag}")->getNumberFormat()->setFormatCode('"$"#,##0.00_-');

				
				$hoja->setCellValue("G".$flag, 0)->getStyle("G{$flag}")->getNumberFormat()->setFormatCode('"$"#,##0.00_-');
				$hoja->setCellValue("H".$flag, "=H".($flag-2)."-G".$flag)->getStyle("H{$flag}")->getNumberFormat()->setFormatCode('"$"#,##0.00_-');
				$hoja->setCellValue("I".$flag, "=I".($flag-2))->getStyle("I{$flag}")->getNumberFormat()->setFormatCode('"$"#,##0.00_-');
				$this->excelfile->getActiveSheet()->getStyle('E'.$flag)->applyFromArray($styleArrayHL);
				$this->excelfile->getActiveSheet()->getStyle('F'.$flag)->applyFromArray($styleArrayHL);
				$this->excelfile->getActiveSheet()->getStyle('G'.$flag)->applyFromArray($styleArrayHL);
				$this->excelfile->getActiveSheet()->getStyle('H'.$flag)->applyFromArray($styleArrayHL);
				$this->excelfile->getActiveSheet()->getStyle('I'.$flag)->applyFromArray($styleArrayHL);

				$flag++;
				$this->cellStyle("A".$flag.":I".$flag, "4f81bd", "000000", TRUE, 22, "Arial Narrow");

				$this->cellStyle("B".$flag, "00FFFF", "000000", TRUE, 10, "Arial Narrow");
				$this->excelfile->getActiveSheet()->getStyle('B'.$flag)->applyFromArray($styleArrayHL);
				$this->cellStyle("C".$flag, "00FFFF", "000000", TRUE, 10, "Arial Narrow");
				$hoja->setCellValue("B".$flag, "DEVOLUCIÓN");
				$this->excelfile->getActiveSheet()->getStyle('C'.$flag)->applyFromArray($styleArrayHL);
				$hoja->setCellValue("C".$flag, "DIF EN PRECIO");
				$hoja->mergeCells('D'.$flag.":G".$flag);
				$this->excelfile->getActiveSheet()->getStyle('D'.$flag.":G".$flag)->applyFromArray($styleArrayHL);
				$this->cellStyle("D".$flag, "FFFFFF", "000000", TRUE, 22, "Arial Narrow");
				$hoja->setCellValue("D".$flag, "TOTAL DE FACTURA");
				$hoja->mergeCells('H'.$flag.":I".$flag);
				$this->cellStyle("H".$flag, "FFFFCC", "000000", TRUE, 22, "Arial Narrow");
				$this->excelfile->getActiveSheet()->getStyle('H'.$flag.":I".$flag)->applyFromArray($styleArray);
				$hoja->setCellValue("H".$flag, "=H".($flag-1)."+I".($flag-1))->getStyle("H{$flag}")->getNumberFormat()->setFormatCode('"$"#,##0.00_-');
			}else{
				$this->cellStyle("A".$flag.":I".$flag, "FFFFFF", "000000", TRUE, 19, "Arial Narrow");
				$hoja->mergeCells('A'.$flag.":C".$flag);
				$this->excelfile->getActiveSheet()->getStyle('A'.$flag.":C".$flag)->applyFromArray($styleArrayHL);
				$this->excelfile->getActiveSheet()->getStyle('D'.$flag)->applyFromArray($styleArrayHL);
				$hoja->setCellValue("D".$flag, "FOLIO");
				$this->excelfile->getActiveSheet()->getStyle('E'.$flag)->applyFromArray($styleArrayHL);
				$hoja->setCellValue("E".$flag, "R");
				$hoja->mergeCells('F'.$flag.':G'.$flag);
				$this->excelfile->getActiveSheet()->getStyle('F'.$flag.':G'.$flag)->applyFromArray($styleArrayHL);
				$this->cellStyle("F".$flag, "00FFFF", "000000", TRUE, 19, "Arial Narrow");
				$hoja->setCellValue("F".$flag, $facturas[0]->folio);
				$this->excelfile->getActiveSheet()->getStyle('H'.$flag)->applyFromArray($styleArrayHL);
				$hoja->setCellValue("H".$flag, "=SUM(H6:H".($flag-1).")")->getStyle("H{$flag}")->getNumberFormat()->setFormatCode('"$"#,##0.00_-');
				$this->excelfile->getActiveSheet()->getStyle('I'.$flag)->applyFromArray($styleArrayHL);
				$hoja->setCellValue("I".$flag, "=SUM(I6:I".($flag-1).")")->getStyle("I{$flag}")->getNumberFormat()->setFormatCode('"$"#,##0.00_-');
				$this->cellStyle("H".$flag, "FF0000", "000000", TRUE, 16, "Arial Narrow");
				$this->cellStyle("I".$flag, "FFFF00", "000000", TRUE, 16, "Arial Narrow");
				$flag++;

				$hoja->mergeCells('E'.$flag.":G".$flag);
				$hoja->mergeCells('H'.$flag.":I".$flag);
				$this->cellStyle("A".$flag.":I".$flag, "FFFFFF", "000000", TRUE, 22, "Arial Narrow");
				$this->cellStyle("A".$flag, "4f81bd", "000000", TRUE, 22, "Arial Narrow");
				$this->cellStyle("B".$flag.":C".$flag, "FF0000", "000000", TRUE, 22, "Arial Narrow");
				$this->excelfile->getActiveSheet()->getStyle('A'.$flag)->applyFromArray($styleArrayHL);
				$this->excelfile->getActiveSheet()->getStyle('B'.$flag)->applyFromArray($styleArrayHL);
				$hoja->setCellValue('B'.$flag, '=SUMIF(B6:B'.($flag-2).',"DEVUELTO",H6:H'.($flag-2).')')->getStyle("B{$flag}")->getNumberFormat()->setFormatCode('"$"#,##0.00_-');
				$this->cellStyle("E".$flag, "FFFFFF", "000000", TRUE, 16, "Arial Narrow");
				$this->excelfile->getActiveSheet()->getStyle('C'.$flag)->applyFromArray($styleArray);
				$hoja->setCellValue("E".$flag, "TOTAL DE LA FACTURA")->getStyle("D{$flag}")->getNumberFormat()->setFormatCode('"$"#,##0.00_-');
				$this->excelfile->getActiveSheet()->getStyle('C'.$flag)->applyFromArray($styleArrayHL);
				$hoja->setCellValue("C".$flag, "=H".($flag+1)."-B".$flag)->getStyle("C{$flag}")->getNumberFormat()->setFormatCode('"$"#,##0.00_-');
				$this->excelfile->getActiveSheet()->getStyle('H'.$flag)->applyFromArray($styleArray);
				$hoja->setCellValue("H".$flag, "=H".($flag-1)."+I".($flag-1))->getStyle("H{$flag}")->getNumberFormat()->setFormatCode('"$"#,##0.00_-');
				$this->excelfile->getActiveSheet()->getStyle('E'.$flag)->applyFromArray($styleArray);
				$this->excelfile->getActiveSheet()->getStyle('F'.$flag)->applyFromArray($styleArray);
				$this->excelfile->getActiveSheet()->getStyle('G'.$flag)->applyFromArray($styleArray);
				$this->excelfile->getActiveSheet()->getStyle('H'.$flag)->applyFromArray($styleArray);
				$this->excelfile->getActiveSheet()->getStyle('I'.$flag)->applyFromArray($styleArray);
				$flag++;

				$hoja->mergeCells('E'.$flag.":G".$flag);
				$hoja->mergeCells('H'.$flag.":I".$flag);
				$this->cellStyle("A".$flag.":I".$flag, "FFFFFF", "000000", TRUE, 22, "Arial Narrow");
				$this->cellStyle("A".$flag, "4f81bd", "000000", TRUE, 22, "Arial Narrow");
				$this->cellStyle("B".$flag, "00FFFF", "000000", TRUE, 10, "Arial Narrow");
				$this->cellStyle("C".$flag, "00FFFF", "000000", TRUE, 10, "Arial Narrow");
				$this->excelfile->getActiveSheet()->getStyle('A'.$flag)->applyFromArray($styleArrayHL);
				$this->cellStyle("B".$flag, "00FFFF", "000000", TRUE, 10, "Arial Narrow");
				$this->excelfile->getActiveSheet()->getStyle('B'.$flag)->applyFromArray($styleArrayHL);
				$this->cellStyle("C".$flag, "00FFFF", "000000", TRUE, 10, "Arial Narrow");
				$hoja->setCellValue("B".$flag, "DEVOLUCIÓN");
				$this->excelfile->getActiveSheet()->getStyle('C'.$flag)->applyFromArray($styleArrayHL);
				$hoja->setCellValue("C".$flag, "DIF EN PRECIO");
				$this->cellStyle("E".$flag, "FFFFFF", "000000", TRUE, 16, "Arial Narrow");
				$hoja->setCellValue("E".$flag, "PENDIENTE POR DEVOLUCIÓN")->getStyle("D{$flag}")->getNumberFormat()->setFormatCode('"$"#,##0.00_-');
				$this->excelfile->getActiveSheet()->getStyle('H'.$flag)->applyFromArray($styleArray);
				$hoja->setCellValue("H".$flag, "=H".($flag-2)."-B".($flag+1))->getStyle("H{$flag}")->getNumberFormat()->setFormatCode('"$"#,##0.00_-');
				$this->excelfile->getActiveSheet()->getStyle('E'.$flag)->applyFromArray($styleArray);
				$this->excelfile->getActiveSheet()->getStyle('F'.$flag)->applyFromArray($styleArray);
				$this->excelfile->getActiveSheet()->getStyle('G'.$flag)->applyFromArray($styleArray);
				$this->excelfile->getActiveSheet()->getStyle('H'.$flag)->applyFromArray($styleArray);
				$this->excelfile->getActiveSheet()->getStyle('I'.$flag)->applyFromArray($styleArray);
				$flag++;

				$hoja->mergeCells('E'.$flag.":G".$flag);
				$hoja->mergeCells('H'.$flag.":I".$flag);
				$this->cellStyle("A".$flag.":I".$flag, "FFFFFF", "000000", TRUE, 22, "Arial Narrow");
				$this->cellStyle("A".$flag, "FFFFFF", "000000", TRUE, 22, "Arial Narrow");
				$this->cellStyle("B".$flag, "FFFF00", "000000", TRUE, 10, "Arial Narrow");
				$this->cellStyle("C".$flag, "FFFFFF", "000000", TRUE, 10, "Arial Narrow");
				$hoja->setCellValue("B".$flag, "0")->getStyle("B{$flag}")->getNumberFormat()->setFormatCode('"$"#,##0.00_-');

				$this->excelfile->getActiveSheet()->getStyle('A'.$flag)->applyFromArray($styleArrayHL);
				$this->excelfile->getActiveSheet()->getStyle('B'.$flag)->applyFromArray($styleArrayHL);
				$this->excelfile->getActiveSheet()->getStyle('C'.$flag)->applyFromArray($styleArrayHL);

				$this->cellStyle("E".$flag, "FFFFFF", "000000", TRUE, 16, "Arial Narrow");
				$hoja->setCellValue("E".$flag, "TOTAL A PAGAR")->getStyle("D{$flag}")->getNumberFormat()->setFormatCode('"$"#,##0.00_-');
				$this->excelfile->getActiveSheet()->getStyle('H'.$flag)->applyFromArray($styleArray);
				$hoja->setCellValue("H".$flag, "=I".($flag-3))->getStyle("H{$flag}")->getNumberFormat()->setFormatCode('"$"#,##0.00_-');
				$this->excelfile->getActiveSheet()->getStyle('E'.$flag)->applyFromArray($styleArray);
				$this->excelfile->getActiveSheet()->getStyle('F'.$flag)->applyFromArray($styleArray);
				$this->excelfile->getActiveSheet()->getStyle('G'.$flag)->applyFromArray($styleArray);
				$this->excelfile->getActiveSheet()->getStyle('H'.$flag)->applyFromArray($styleArray);
				$this->excelfile->getActiveSheet()->getStyle('I'.$flag)->applyFromArray($styleArray);

			}
		}
		
		


		$file_name = "Facturas.xlsx"; //Nombre del documento con extención

		header("Content-Type: application/vnd.ms-excel; charset=utf-8");
		header("Content-Disposition: attachment;filename=".$file_name);
		header("Cache-Control: max-age=0");
		$excel_Writer = PHPExcel_IOFactory::createWriter($this->excelfile, "Excel2007");
		$excel_Writer->save("php://output");
		//$this->jsonResponse($facturas);
	}

	/*public function uploadExi(){
		$proveedor = $this->session->userdata('id_usuario');
		$cfile =  $this->usua_mdl->get(NULL, ['id_usuario' => $proveedor])[0];
		$filen = "Productos por ".$cfile->nombre."".rand();
		$config['upload_path']          = './assets/uploads/cotizaciones/';
        $config['allowed_types']        = 'xlsx|xls';
        $config['max_size']             = 100;
        $config['max_width']            = 1024;
        $config['max_height']           = 768;


        $estatus = 1;
        $this->load->library('upload', $config);
        $this->upload->initialize($config);
        $this->upload->do_upload('file_codes',$filen);
		$this->load->library("excelfile");
		ini_set("memory_limit", -1);
		$file = $_FILES["file_codes"]["tmp_name"];
		$filename=$_FILES['file_codes']['name'];
		$sheet = PHPExcel_IOFactory::load($file);
		$objExcel = PHPExcel_IOFactory::load($file);
		$sheet = $objExcel->getSheet(0);
		$num_rows = $sheet->getHighestDataRow();
		
		for ($i=1; $i<=$num_rows; $i++) {
			$invoice = $this->invoice_md->get(NULL,["codigo"=>$sheet->getCell('A'.$i)->getValue(),"id_proveedor"=>4])[0];
			$product = $this->pro_md->get(NULL,["id_producto"=>$sheet->getCell('B'.$i)->getValue()])[0];
			if ($invoice && $product) {
				$new_producto=[
					"id_invoice" 	=> $invoice->id_invoice,
					"id_producto" 	=> $product->id_producto,
					"id_proveedor" 	=> 4
				];
				$data ['id_producto'] = $this->pcaja_md->insert($new_producto);
			}
		}
		if (!isset($new_producto)) {
			$mensaje	=	[	"id"	=>	'Error',
								"desc"	=>	'El Archivo esta sin productos',
								"type"	=>	'error'];
		}else{
			if (sizeof($new_producto) > 0) {
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
	}*/
	
}

/* End of file Productos.php */
/* Location: ./application/controllers/Productos.php */