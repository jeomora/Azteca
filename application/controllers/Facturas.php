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
		$data["provis"] = $this->fact_md->getFactProv(NULL);
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

		$config['upload_path']          = './assets/uploads/facturas/';
        $config['allowed_types']        = 'xlsx|xls';
        $config['max_size']             = 1000;
        $config['max_width']            = 10240;
        $config['max_height']           = 7680;
        $this->load->library('upload', $config);
        $this->upload->do_upload('file_factura','PedidoFinal'.$this->input->post('proveedor').''.date('dmYHis'));

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

	public function deletePedidos(){
		$proveedor = $this->input->post('proveedor');
		$this->db->query("delete from finales where WEEKOFYEAR(fecha_registro) = WEEKOFYEAR(CURDATE()) AND id_proveedor = ".$proveedor." ;");
		$user = $this->session->userdata();
		$cambios = [
				"id_usuario" => $user["id_usuario"],
				"fecha_cambio" => date('Y-m-d H:i:s'),
				"antes" => "El usuario elimino pedidos finales",
				"despues" => "Del proveedor ".$proveedor.". "];
		$this->jsonResponse($proveedor);
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
		$nombreFolio = $_FILES["file_factura"]['name'];
		$file = $_FILES["file_factura"]["tmp_name"];
		$sheet = PHPExcel_IOFactory::load($file);
		$objExcel = PHPExcel_IOFactory::load($file);
		$sheet = $objExcel->getSheet(0);
		$num_rows = $sheet->getHighestDataRow();
		$proveedor = $id_proveedor;
		$folio = substr($nombreFolio,0,strlen($nombreFolio)-5);
		$config['upload_path']          = './assets/uploads/facturas/';
        $config['allowed_types']        = 'xlsx|xls';
        $config['max_size']             = 1000;
        $config['max_width']            = 10240;
        $config['max_height']           = 7680;
        $this->load->library('upload', $config);
        $this->upload->do_upload('file_factura',substr($nombreFolio,0,strlen($nombreFolio)-5).''.date('dmYHis'));
		$this->load->library("excelfile");
		if ($sheet->getCell('A1')->getValue() === "FOLIO FACTURA") {
			$folio = htmlspecialchars($sheet->getCell('B1')->getValue(), ENT_QUOTES, 'UTF-8');	
			$fecha = $sheet->getCell('D1')->getValue();
			$no = 3;
		}else{
			$folio = htmlspecialchars($sheet->getCell('E1')->getValue(), ENT_QUOTES, 'UTF-8');
			$fecha = $sheet->getCell('F1')->getValue();
			$no = 1;
		}
		
		if ($folio === "" || strlen($folio) < 5) {
			$folio = rand(1000000000, 9000000000);
		}
		
		if ($fecha === "" || $fecha === null) {
			$fecha = date("d/m/Y h:i:sa");
		}
		$folio = substr($nombreFolio,0,strlen($nombreFolio)-5);
		$this->db->query("delete from facturas where folio = '".$folio."' AND id_proveedor = ".$proveedor."");
		for ($i=$no; $i<=$num_rows; $i++) {
			$codigo = $sheet->getCell('A'.$i)->getValue();
			$cantidad = $this->getOldVal($sheet,$i,"C");
			$precio = $this->getOldVal($sheet,$i,"D");
			$desc = $this->getOldVal($sheet,$i,"B");
			$descripcion = $this->invoice_md->get(NULL,["id_proveedor"=>$id_proveedor,"codigo"=>$codigo])[0];
			
			
			if ($codigo <> NULL || $codigo <> "") {
				if (sizeof($descripcion) > 0) {
					$new_producto=[
						"folio" => $folio,
						"id_proveedor" => $proveedor,
						"precio" => $precio,
						"codigo" => $descripcion->id_invoice,
						"descripcion" => $descripcion->descripcion,
						"fecha_factura" => $fecha,
						"cantidad" => $cantidad,
						"id_tienda"=> $id_tienda
					];

					$codiga = $this->fact_md->getThem(NULL,$folio,$proveedor,$id_tienda,$codigo,$precio,$cantidad);
					if ($codiga) {
					}else{
						$data['id_prodcaja']=$this->fact_md->insert($new_producto);
					}
				}else{
					$new_invoice=[
						"codigo" => $codigo,
						"id_proveedor" => $id_proveedor,
						"descripcion" => $desc,
						"unidad" => "CJ"
					];
					$data['id_invoice']=$this->invoice_md->insert($new_invoice);
					$descripcion = $this->invoice_md->get(NULL,["id_proveedor"=>$id_proveedor,"codigo"=>$codigo])[0];
					$new_producto=[
						"folio" => $folio,
						"id_proveedor" => $proveedor,
						"precio" => $precio,
						"codigo" => $descripcion->id_invoice,
						"descripcion" => $descripcion->descripcion,
						"fecha_factura"	=> $fecha,
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
		}
		//Obtener elementos factura con pedidos para asociar
		$query = "pd.nombre,pd.codigo,fc.descripcion,fc.folio,fc.codigo as factu,fc.cantidad,fc.precio,fc.estatus,fc.fecha_registro,fc.id_tienda,fc.id_proveedor,pc.id_prodcaja,pc.id_producto, f.".$tend." as total,f.costo,f.promocion,f.id_final FROM facturas fc LEFT JOIN (select * from prodcaja order by id_prodcaja DESC) pc ON fc.codigo = pc.id_invoice AND pc.id_proveedor = ".$proveedor." LEFT JOIN productos pd ON pc.id_producto = pd.id_producto LEFT JOIN finales f ON pd.id_producto = f.id_producto AND f.id_proveedor = ".$proveedor." AND WEEKOFYEAR(f.fecha_registro) = WEEKOFYEAR(CURDATE()) WHERE fc.id_proveedor = ".$proveedor." AND WEEKOFYEAR(fc.fecha_registro) = WEEKOFYEAR(CURDATE()) AND fc.folio = '".$folio."' AND fc.id_tienda = '".$id_tienda."' GROUP BY fc.id_factura ORDER BY fc.id_factura ASC";
		$factus = $this->fact_md->getFactos(NULL,json_encode($query));
		//Obtener pedidos no asociados a productos en la fatcura
		$query2 = "f2.id_final,f2.costo,f2.promocion,pd.nombre,pd.codigo,pd.id_producto,f2.".$tend." as total from finales f2 LEFT JOIN productos pd ON f2.id_producto = pd.id_producto WHERE f2.id_final AND id_proveedor = ".$proveedor." AND WEEKOFYEAR(f2.fecha_registro) = WEEKOFYEAR(CURDATE()) ORDER BY f2.id_final ASC";
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

	public function eliminaFactura(){
		$user = $this->session->userdata();
		$value = json_decode($this->input->post('values'), true);
		$flag = 0;
		$this->db->query("delete from comparacion where folio = '".$value["folio"]."' AND id_proveedor = ".$value["proveedor"]."");
		$this->db->query("delete from facturas where folio = '".$value["folio"]."' AND id_proveedor = ".$value["proveedor"]."");
		$cambios = [
				"id_usuario" => $user["id_usuario"],
				"fecha_cambio" => date('Y-m-d H:i:s'),
				"antes" => "El usuario elimino factura",
				"despues" => "FOLIO : ".$value['folio']." del proveedor ".$value['proveedor'].". "];
		$this->jsonResponse("Se elimino la factura");
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
		$hoja->setCellValue("F3", $facturas[0]->fecha_factura);
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

	public function imprimeR($proveedor){
		ini_set("memory_limit", "-1");
		ini_set("max_execution_time", "-1");
		$this->load->library("excelfile");
		$hoja = $this->excelfile->getActiveSheet();
		$styleArray9 = array(
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
		$provfact = $this->fact_md->profactu(NULL,$proveedor);
		$provnombre = $this->usua_mdl->get("nombre",["id_usuario"=>$proveedor])[0];
		$this->excelfile->setActiveSheetIndex(0)->setTitle("CEDIS");

		$this->excelfile->createSheet();
        $hoja1 = $this->excelfile->setActiveSheetIndex(1)->setTitle("ABARROTES");

        $this->excelfile->createSheet();
        $hoja2 = $this->excelfile->setActiveSheetIndex(2)->setTitle("TIENDA");

        $this->excelfile->createSheet();
        $hoja3 = $this->excelfile->setActiveSheetIndex(3)->setTitle("ULTRA");

        $this->excelfile->createSheet();
        $hoja4 = $this->excelfile->setActiveSheetIndex(4)->setTitle("TRINCHERAS");

		$this->excelfile->createSheet();
        $hoja5 = $this->excelfile->setActiveSheetIndex(5)->setTitle("MERCADO");

        $this->excelfile->createSheet();
        $hoja6 = $this->excelfile->setActiveSheetIndex(6)->setTitle("TENENCIA");

        $this->excelfile->createSheet();
        $hoja7 = $this->excelfile->setActiveSheetIndex(7)->setTitle("TIJERAS");

        $this->excelfile->createSheet();
        $hoja8 = $this->excelfile->setActiveSheetIndex(8)->setTitle("VILLAS");

        $this->excelfile->createSheet();
        $hoja9 = $this->excelfile->setActiveSheetIndex(9)->setTitle("FINALES");


		//$this->jsonResponse($provfact);
		$flag = 1;
		$fced = 1;$faba = 1;$ftie = 1;$fult = 1;$ftri = 1;$fmer = 1; $ften = 1;$ftij = 1;$fvil = 1;
		if ($provfact) {
			foreach ($provfact as $key => $valorce) {
				$folio = $valorce->folio;
				$which = "cedis";
				switch ($valorce->tienda) {
					case "87":
						$which = "cedis";
						$pestania = 0;
						$this->excelfile->setActiveSheetIndex(0);
						$hoja = $this->excelfile->getActiveSheet();
						$flag = $fced;
						break;
					case "57":
						$which = "abarrotes";
						$pestania = 1;
						$this->excelfile->setActiveSheetIndex(1);
						$hoja = $this->excelfile->getActiveSheet();
						$flag = $faba;
						break;
					case "58":
						$which = "tienda";
						$pestania = 2;
						$this->excelfile->setActiveSheetIndex(2);
						$hoja = $this->excelfile->getActiveSheet();
						$flag = $ftie;
						break;
					case "59":
						$which = "ultra";
						$pestania = 3;
						$this->excelfile->setActiveSheetIndex(3);
						$hoja = $this->excelfile->getActiveSheet();
						$flag = $fult;
						break;
					case "60":
						$which = "trincheras";
						$pestania = 4;
						$this->excelfile->setActiveSheetIndex(4);
						$hoja = $this->excelfile->getActiveSheet();
						$flag = $ftri;
						break;
					case "61":
						$which = "mercado";
						$pestania = 5;
						$this->excelfile->setActiveSheetIndex(5);
						$hoja = $this->excelfile->getActiveSheet();
						$flag = $fmer;
						break;
					case "62":
						$which = "tenencia";
						$pestania = 6;
						$this->excelfile->setActiveSheetIndex(6);
						$hoja = $this->excelfile->getActiveSheet();
						$flag = $ften;
						break;
					case "63":
						$which = "tijeras";
						$pestania = 7;
						$this->excelfile->setActiveSheetIndex(7);
						$hoja = $this->excelfile->getActiveSheet();
						$flag = $ftij;
						break;
					case "90":
						$which = "villas";
						$pestania = 8;
						$this->excelfile->setActiveSheetIndex(8);
						$hoja = $this->excelfile->getActiveSheet();
						$flag = $fvil;
						break;
					default:
						# code...
						break;
				}
				$woe = $this->comp_md->get(NULL,["folio"=>$folio])[0];
				if ($woe) {
					$facturas = $this->fact_md->getDetails2(NULL,json_encode($woe),$which);
					$hoja->mergeCells('A'.$flag.':I'.$flag.'');
					$this->cellStyle("A".$flag, "".substr($facturas[0]->color,1,6), "000000", TRUE, 24, "Berlin Sans FB Demi");
					$hoja->setCellValue("A".$flag, $facturas[0]->tienda." GRUPO AZTECA, S.A DE C.V")->getColumnDimension('A')->setWidth(60);
					$this->excelfile->getActiveSheet()->getStyle('A'.$flag.':I'.$flag.'')->applyFromArray($styleArray);
					$flag++;

					$hoja->mergeCells('A'.$flag.':B'.($flag+1));
					$this->cellStyle("A".$flag, "FFFFFF", "000000", FALSE, 18, "Arial Narrow");
					$hoja->setCellValue("A".$flag, $facturas[0]->prove);
					$this->excelfile->getActiveSheet()->getStyle('A'.$flag.':B'.($flag+1))->applyFromArray($styleArray);
					$hoja->mergeCells('C'.$flag.':E'.$flag.'');
					$this->cellStyle("C".$flag, "FFFFFF", "000000", FALSE, 14, "Arial Narrow");
					$hoja->setCellValue("C".$flag, "Fecha de Reporte");
					$this->excelfile->getActiveSheet()->getStyle('C'.$flag.':E'.$flag)->applyFromArray($styleright);
					$hoja->mergeCells('F'.$flag.':I'.$flag.'');
					$this->cellStyle("F".$flag, "FFFFFF", "000000", FALSE, 14, "Arial Narrow");
					$hoja->setCellValue('F'.$flag,$facturas[0]->fecha);  
					$this->excelfile->getActiveSheet()->getStyle('F'.$flag.':I'.$flag.'')->applyFromArray($styleleft);
					$flag++;

					$hoja->mergeCells('C'.$flag.':E'.$flag.'');
					$this->cellStyle("C".$flag, "FFFFFF", "000000", FALSE, 14, "Arial Narrow");
					$hoja->setCellValue("C".$flag, "Fecha en Factura");
					$this->excelfile->getActiveSheet()->getStyle('C'.$flag.':E'.$flag.'')->applyFromArray($styleright);
					$hoja->mergeCells('F'.$flag.':I'.$flag.'');
					$this->cellStyle("F".$flag, "FFFFFF", "000000", FALSE, 14, "Arial Narrow");
					$hoja->setCellValue("F".$flag, $facturas[0]->fecha_factura);
					$this->excelfile->getActiveSheet()->getStyle('F'.$flag.':I'.$flag.'')->applyFromArray($styleleft);
					$flag++;

					$hoja->mergeCells('A'.$flag.':A'.($flag+1));
					$this->cellStyle("A".$flag, "FFFFFF", "000000", FALSE, 14, "Arial Narrow");
					$hoja->setCellValue("A".$flag, "DESCRIPCIÓN");
					$this->excelfile->getActiveSheet()->getStyle('A'.$flag.':A'.($flag+1))->applyFromArray($styleArray);
					$hoja->mergeCells('B'.$flag.':B'.($flag+1));
					$this->cellStyle("B".$flag, "FFFFFF", "000000", FALSE, 9, "Arial Narrow");
					$hoja->setCellValue("B".$flag, "PROMO")->getColumnDimension('B')->setWidth(16);
					$this->excelfile->getActiveSheet()->getStyle('B'.$flag.':B'.($flag+1))->applyFromArray($styleArray);
					$this->cellStyle("D".$flag, "FFFFFF", "000000", FALSE, 11, "Arial Narrow");
					$hoja->setCellValue("D".$flag, "CANT")->getColumnDimension('D')->setWidth(10);
					$this->excelfile->getActiveSheet()->getStyle('D'.$flag)->applyFromArray($stylebottom);
					$this->cellStyle("F".$flag, "FFFFFF", "000000", FALSE, 10, "Arial Narrow");
					$hoja->setCellValue("F".$flag, "PREC NETO")->getColumnDimension('F')->setWidth(12);
					$this->excelfile->getActiveSheet()->getStyle('F'.$flag)->applyFromArray($stylebottom);
					$this->cellStyle("C".$flag, "FFFFFF", "000000", FALSE, 11, "Arial Narrow");
					$hoja->setCellValue("C".$flag, "PRECIO EN")->getColumnDimension('C')->setWidth(16);
					$this->excelfile->getActiveSheet()->getStyle('C'.$flag)->applyFromArray($stylebottom);
					$hoja->mergeCells('G'.$flag.':G'.($flag+1));
					$this->cellStyle("G".$flag, "FFFFFF", "000000", FALSE, 14, "Arial Narrow");
					$hoja->setCellValue("G".$flag, "DIF.")->getColumnDimension('G')->setWidth(13);
					$this->excelfile->getActiveSheet()->getStyle('G'.$flag.':G'.($flag+1))->applyFromArray($styleArray);
					$this->cellStyle("H".$flag, "FFFFFF", "000000", FALSE, 14, "Arial Narrow");
					$hoja->setCellValue("H".$flag, "NOTA")->getColumnDimension('H')->setWidth(16);
					$this->excelfile->getActiveSheet()->getStyle('H'.$flag)->applyFromArray($stylebottom);
					$this->cellStyle("I".$flag, "FFFFFF", "000000", FALSE, 14, "Arial Narrow");
					$hoja->setCellValue("I".$flag, "TOTAL")->getColumnDimension('I')->setWidth(16);
					$this->excelfile->getActiveSheet()->getStyle('I'.$flag)->applyFromArray($stylebottom);
					$this->cellStyle("E".$flag, "FFFFFF", "000000", FALSE, 8, "Arial Narrow");
					$hoja->setCellValue("E".$flag, "CANT")->getColumnDimension('E')->setWidth(10);
					$this->excelfile->getActiveSheet()->getStyle('E'.$flag)->applyFromArray($stylebottom);
					$flag++;
					
					$this->cellStyle("D".$flag, "FFFFFF", "000000", FALSE, 11, "Arial Narrow");
					$hoja->setCellValue("D".$flag, "PEDIDO");
					$this->excelfile->getActiveSheet()->getStyle('D'.$flag)->applyFromArray($styletop);
					$this->cellStyle("C".$flag, "FFFFFF", "000000", FALSE, 11, "Arial Narrow");
					$hoja->setCellValue("C".$flag, "PEDIDO");
					$this->excelfile->getActiveSheet()->getStyle('C'.$flag)->applyFromArray($styletop);
					$this->cellStyle("E".$flag, "FFFFFF", "000000", FALSE, 8, "Arial Narrow");
					$hoja->setCellValue("E".$flag, "FACTURA");
					$this->excelfile->getActiveSheet()->getStyle('E'.$flag)->applyFromArray($styletop);
					$this->cellStyle("F".$flag, "FFFFFF", "000000", FALSE, 10, "Arial Narrow");
					$hoja->setCellValue("F".$flag, "FACTURA");
					$this->excelfile->getActiveSheet()->getStyle('F'.$flag)->applyFromArray($styletop);
					$this->cellStyle("H".$flag, "FFFFFF", "000000", FALSE, 14, "Arial Narrow");
					$hoja->setCellValue("H".$flag, "CREDITO");
					$this->excelfile->getActiveSheet()->getStyle('H'.$flag)->applyFromArray($styletop);
					$this->cellStyle("I".$flag, "FFFFFF", "000000", FALSE, 14, "Arial Narrow");
					$hoja->setCellValue("I".$flag, "A PAGAR");
					$this->excelfile->getActiveSheet()->getStyle('I'.$flag)->applyFromArray($styletop);
					$flag++;
					$flag2 = $flag;
					if ($facturas) {
						foreach ($facturas as $key => $value) {
							$this->cellStyle("A".$flag.":J".$flag, "FFFFFF", "000000", FALSE, 14, "Arial Narrow");
							$this->excelfile->getActiveSheet()->getStyle('A'.$flag)->applyFromArray($stylebottom);
							$hoja->setCellValue("A".$flag, $value->descripcion);
							if ($value->devolucion === 1 || $value->devolucion === "1") {
								$this->excelfile->getActiveSheet()->getStyle('A'.$flag)->applyFromArray($styleArray);
								if($value->pprod === "" || $value->pprod === NULL){
									$hoja->setCellValue("A".$flag, $value->descripcion);
								}else{
									$hoja->setCellValue("A".$flag, $value->pprod);	
								}
								

								$hoja->setCellValue("J".$flag, $value->comproducto);	
								if($value->pprod === "FACTURA"){
									$hoja->setCellValue("J".$flag, "SIN ASOCIAR");
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
								$hoja->setCellValue("J".$flag, $value->comproducto);	
								if($value->pprod === "FACTURA"){
									$hoja->setCellValue("J".$flag, "SIN ASOCIAR");
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
								$hoja->setCellValue("J".$flag, $value->comproducto);	
								if($value->pprod === "FACTURA"){
									$hoja->setCellValue("J".$flag, "SIN ASOCIAR");
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
							$hoja->setCellValue("H".$flag, "=SUM(H".$flag2.":H".($flag-1).")")->getStyle("H{$flag}")->getNumberFormat()->setFormatCode('"$"#,##0.00_-');
							$hoja->mergeCells('I'.$flag.':I'.($flag+1));
							$this->excelfile->getActiveSheet()->getStyle('I'.$flag)->applyFromArray($styleArrayHL);
							$hoja->setCellValue("I".$flag, "=SUM(I".$flag2.":I".($flag-1).")")->getStyle("I{$flag}")->getNumberFormat()->setFormatCode('"$"#,##0.00_-');
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
							$hoja->setCellValue('B'.$flag, '=SUMIF(B'.$flag2.':B'.($flag-3).',"DEVUELTO",H'.$flag2.':H'.($flag-3).')')->getStyle("B{$flag}")->getNumberFormat()->setFormatCode('"$"#,##0.00_-');
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
							$hoja->setCellValue("H".$flag, "=SUM(H".$flag2.":H".($flag-1).")")->getStyle("H{$flag}")->getNumberFormat()->setFormatCode('"$"#,##0.00_-');
							$this->excelfile->getActiveSheet()->getStyle('I'.$flag)->applyFromArray($styleArrayHL);
							$hoja->setCellValue("I".$flag, "=SUM(I".$flag2.":I".($flag-1).")")->getStyle("I{$flag}")->getNumberFormat()->setFormatCode('"$"#,##0.00_-');
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
							$hoja->setCellValue('B'.$flag, '=SUMIF(B'.$flag2.':B'.($flag-2).',"DEVUELTO",H'.$flag2.':H'.($flag-2).')')->getStyle("B{$flag}")->getNumberFormat()->setFormatCode('"$"#,##0.00_-');
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
					$flag = $flag + 5;
					switch ($valorce->tienda) {
						case "87":
							$fced = $flag;
							break;
						case "57":
							$faba = $flag;
							break;
						case "58":
							$ftie = $flag;
							break;
						case "59":
							$fult = $flag;
							break;
						case "60":
							$ftri = $flag;
							break;
						case "61":
							$fmer = $flag;
							break;
						case "62":
							$ften = $flag;
							break;
						case "63":
							$ftij = $flag;
							break;
						case "90":
							$fvil = $flag;
							break;
						default:
							# code...
							break;
					}
				}
		
			}
			$flag9 = 1;
			$this->excelfile->setActiveSheetIndex(9);
			$hoja = $this->excelfile->getActiveSheet();
			$hoja->mergeCells('A'.$flag9.':B'.$flag9.'');
			$this->cellStyle("A".$flag9, "FFFFFF", "000000", TRUE, 12, "Franklin Gothic Book");
			$hoja->setCellValue("A".$flag9, "COMPARACIÓN PEDIDOS '".$provnombre->nombre."'");
			$this->excelfile->getActiveSheet()->getStyle('A'.$flag9.':AF'.$flag9.'')->applyFromArray($styleArray9);
			$hoja->mergeCells('C'.$flag9.':E'.$flag9.'');
			$this->cellStyle("C".$flag9, "C00000", "000000", TRUE, 12, "Franklin Gothic Book");
			$hoja->setCellValue("C".$flag9, "CEDIS");
			$hoja->mergeCells('F'.$flag9.':H'.$flag9.'');
			$this->cellStyle("F".$flag9, "01B0F0", "000000", TRUE, 12, "Franklin Gothic Book");
			$hoja->setCellValue("F".$flag9, "ABARROTES");
			$hoja->mergeCells('I'.$flag9.':K'.$flag9.'');
			$this->cellStyle("I".$flag9, "FF0000", "000000", TRUE, 12, "Franklin Gothic Book");
			$hoja->setCellValue("I".$flag9, "VILLAS");
			$hoja->mergeCells('L'.$flag9.':N'.$flag9.'');
			$this->cellStyle("L".$flag9, "FF6D0B", "000000", TRUE, 12, "Franklin Gothic Book");
			$hoja->setCellValue("L".$flag9, "TIENDA");
			$hoja->mergeCells('O'.$flag9.':Q'.$flag9.'');
			$this->cellStyle("O".$flag9, "C5C5C5", "000000", TRUE, 12, "Franklin Gothic Book");
			$hoja->setCellValue("O".$flag9, "ULTRAMARINOS");
			$hoja->mergeCells('R'.$flag9.':T'.$flag9.'');
			$this->cellStyle("R".$flag9, "93D051", "000000", TRUE, 12, "Franklin Gothic Book");
			$hoja->setCellValue("R".$flag9, "TRINCHERAS");
			$hoja->mergeCells('U'.$flag9.':W'.$flag9.'');
			$this->cellStyle("U".$flag9, "B1A0C7", "000000", TRUE, 12, "Franklin Gothic Book");
			$hoja->setCellValue("U".$flag9, "AZT MERCADO");
			$hoja->mergeCells('X'.$flag9.':Z'.$flag9.'');
			$this->cellStyle("X".$flag9, "DA9694", "000000", TRUE, 12, "Franklin Gothic Book");
			$hoja->setCellValue("X".$flag9, "TENENCIA");
			$hoja->mergeCells('AA'.$flag9.':AC'.$flag9.'');
			$this->cellStyle("AA".$flag9, "4CACC6", "000000", TRUE, 12, "Franklin Gothic Book");
			$hoja->setCellValue("AA".$flag9, "TIJERAS");
			$hoja->mergeCells('AD'.$flag9.':AF'.$flag9.'');
			$this->cellStyle("AD".$flag9.":AF".$flag9, "000000", "FFFFFF", TRUE, 12, "Franklin Gothic Book");
			$flag9++;
			$this->excelfile->getActiveSheet()->getStyle('A'.$flag9.':AF'.$flag9.'')->applyFromArray($styleArray9);
			$hoja->setCellValue("A".$flag9, "CÓDIGO")->getColumnDimension('A')->setWidth(20);
			$hoja->setCellValue("B".$flag9, "DESCRIPCIÓN")->getColumnDimension('B')->setWidth(60);
			$this->cellStyle("A".$flag9.":AF".$flag9, "000000", "FFFFFF", TRUE, 12, "Franklin Gothic Book");
			$hoja->setCellValue("C".$flag9, "PEDIDO");
			$hoja->setCellValue("D".$flag9, "FACTS");
			$hoja->setCellValue("E".$flag9, "DIFS");
			$hoja->setCellValue("F".$flag9, "PEDIDO");
			$hoja->setCellValue("G".$flag9, "FACTS");
			$hoja->setCellValue("H".$flag9, "DIFS");
			$hoja->setCellValue("I".$flag9, "PEDIDO");
			$hoja->setCellValue("J".$flag9, "FACTS");
			$hoja->setCellValue("K".$flag9, "DIFS");
			$hoja->setCellValue("L".$flag9, "PEDIDO");
			$hoja->setCellValue("M".$flag9, "FACTS");
			$hoja->setCellValue("N".$flag9, "DIFS");
			$hoja->setCellValue("O".$flag9, "PEDIDO");
			$hoja->setCellValue("P".$flag9, "FACTS");
			$hoja->setCellValue("Q".$flag9, "DIFS");
			$hoja->setCellValue("R".$flag9, "PEDIDO");
			$hoja->setCellValue("S".$flag9, "FACTS");
			$hoja->setCellValue("T".$flag9, "DIFS");
			$hoja->setCellValue("U".$flag9, "PEDIDO");
			$hoja->setCellValue("V".$flag9, "FACTS");
			$hoja->setCellValue("W".$flag9, "DIFS");
			$hoja->setCellValue("X".$flag9, "PEDIDO");
			$hoja->setCellValue("Y".$flag9, "FACTS");
			$hoja->setCellValue("Z".$flag9, "DIFS");
			$hoja->setCellValue("AA".$flag9, "PEDIDO");
			$hoja->setCellValue("AB".$flag9, "FACTS");
			$hoja->setCellValue("AC".$flag9, "DIFS");
			$hoja->setCellValue("AD".$flag9, "SUM PEDS")->getColumnDimension('AD')->setWidth(16);
			$hoja->setCellValue("AE".$flag9, "SUM FACTS")->getColumnDimension('AE')->setWidth(16);
			$hoja->setCellValue("AF".$flag9, "SUM DIFS")->getColumnDimension('AF')->setWidth(16);
			
			$finals = $this->fact_md->getfinals(NULL,$proveedor);
			$facts = $this->fact_md->getfacts(NULL,$proveedor);
			$fams = "";
			if ($finals) {
				foreach ($finals as $key => $val) {
					if ($val->familia <> $fams) {
						$fams = $val->familia;
						$hoja->setCellValue("B".$flag9, $val->familia);
						$this->cellStyle("B".$flag9, "000000", "FFFFFF", FALSE, 12, "Franklin Gothic Book");
						$flag9++;
					}
					$this->cellStyle('A'.$flag9.':B'.$flag9.'', "FFFFFF", "000000", FALSE, 12, "Franklin Gothic Book");
					$this->cellStyle('C'.$flag9.':AF'.$flag9.'', "FFFFFF", "000000", TRUE, 12, "Franklin Gothic Book");
					$this->cellStyle('C'.$flag9, "D4EAEF", "000000", TRUE, 12, "Franklin Gothic Book");
					$this->cellStyle('F'.$flag9, "D4EAEF", "000000", TRUE, 12, "Franklin Gothic Book");
					$this->cellStyle('I'.$flag9, "D4EAEF", "000000", TRUE, 12, "Franklin Gothic Book");
					$this->cellStyle('L'.$flag9, "D4EAEF", "000000", TRUE, 12, "Franklin Gothic Book");
					$this->cellStyle('O'.$flag9, "D4EAEF", "000000", TRUE, 12, "Franklin Gothic Book");
					$this->cellStyle('R'.$flag9, "D4EAEF", "000000", TRUE, 12, "Franklin Gothic Book");
					$this->cellStyle('U'.$flag9, "D4EAEF", "000000", TRUE, 12, "Franklin Gothic Book");
					$this->cellStyle('X'.$flag9, "D4EAEF", "000000", TRUE, 12, "Franklin Gothic Book");
					$this->cellStyle('AA'.$flag9, "D4EAEF", "000000", TRUE, 12, "Franklin Gothic Book");

					$this->excelfile->getActiveSheet()->getStyle('A'.$flag9.':AF'.$flag9.'')->applyFromArray($styleArray9);
					$hoja->setCellValue("A".$flag9, $val->codigo)->getStyle("A{$flag9}")->getNumberFormat()->setFormatCode('# ???/???');
					$hoja->setCellValue("B".$flag9, $val->nombre);
					$hoja->setCellValue("C".$flag9, $val->cedis);
					$hoja->setCellValue("E".$flag9, "=(C".$flag9."-D".$flag9.")");
					$hoja->setCellValue("F".$flag9, $val->abarrotes);
					$hoja->setCellValue("H".$flag9, "=(F".$flag9."-G".$flag9.")");
					$hoja->setCellValue("I".$flag9, $val->villas);
					$hoja->setCellValue("K".$flag9, "=(I".$flag9."-J".$flag9.")");
					$hoja->setCellValue("L".$flag9, $val->tienda);
					$hoja->setCellValue("N".$flag9, "=(L".$flag9."-M".$flag9.")");
					$hoja->setCellValue("O".$flag9, $val->ultra);
					$hoja->setCellValue("Q".$flag9, "=(O".$flag9."-P".$flag9.")");
					$hoja->setCellValue("R".$flag9, $val->trincheras);
					$hoja->setCellValue("T".$flag9, "=(R".$flag9."-S".$flag9.")");
					$hoja->setCellValue("U".$flag9, $val->mercado);
					$hoja->setCellValue("W".$flag9, "=(U".$flag9."-V".$flag9.")");
					$hoja->setCellValue("X".$flag9, $val->tenencia);
					$hoja->setCellValue("Z".$flag9, "=(X".$flag9."-Y".$flag9.")");
					$hoja->setCellValue("AA".$flag9, $val->tijeras);
					$hoja->setCellValue("AC".$flag9, "=(AA".$flag9."-AB".$flag9.")");
					$hoja->setCellValue("D".$flag9, 0);
					$hoja->setCellValue("G".$flag9, 0);
					$hoja->setCellValue("J".$flag9, 0);
					$hoja->setCellValue("M".$flag9, 0);
					$hoja->setCellValue("O".$flag9, 0);
					$hoja->setCellValue("S".$flag9, 0);
					$hoja->setCellValue("V".$flag9, 0);
					$hoja->setCellValue("Y".$flag9, 0);
					$hoja->setCellValue("AB".$flag9, 0);

					$this->cellStyle('D'.$flag9, "FFFFFF", "000000", FALSE, 12, "Franklin Gothic Book");
					$this->cellStyle('G'.$flag9, "FFFFFF", "000000", FALSE, 12, "Franklin Gothic Book");
					$this->cellStyle('J'.$flag9, "FFFFFF", "000000", FALSE, 12, "Franklin Gothic Book");
					$this->cellStyle('M'.$flag9, "FFFFFF", "000000", FALSE, 12, "Franklin Gothic Book");
					$this->cellStyle('P'.$flag9, "FFFFFF", "000000", FALSE, 12, "Franklin Gothic Book");
					$this->cellStyle('S'.$flag9, "FFFFFF", "000000", FALSE, 12, "Franklin Gothic Book");
					$this->cellStyle('V'.$flag9, "FFFFFF", "000000", FALSE, 12, "Franklin Gothic Book");
					$this->cellStyle('Y'.$flag9, "FFFFFF", "000000", FALSE, 12, "Franklin Gothic Book");
					$this->cellStyle('AB'.$flag9, "FFFFFF", "000000", FALSE, 12, "Franklin Gothic Book");

					if (isset($facts[$val->codigo])) {
						$hoja->setCellValue("D".$flag9, $facts[$val->codigo][87]);
						$hoja->setCellValue("G".$flag9, $facts[$val->codigo][57]);
						$hoja->setCellValue("J".$flag9, $facts[$val->codigo][90]);
						$hoja->setCellValue("M".$flag9, $facts[$val->codigo][58]);
						$hoja->setCellValue("P".$flag9, $facts[$val->codigo][59]);
						$hoja->setCellValue("S".$flag9, $facts[$val->codigo][60]);
						$hoja->setCellValue("V".$flag9, $facts[$val->codigo][61]);
						$hoja->setCellValue("Y".$flag9, $facts[$val->codigo][62]);
						$hoja->setCellValue("AB".$flag9, $facts[$val->codigo][63]);
					}

					$this->cellStyle('AD'.$flag9, "C2B90A", "000000", TRUE, 12, "Franklin Gothic Book");
					$this->cellStyle('AE'.$flag9, "CCC0DA", "000000", TRUE, 12, "Franklin Gothic Book");
					$this->cellStyle('AF'.$flag9, "FDE9D9", "000000", TRUE, 12, "Franklin Gothic Book");

					$hoja->setCellValue("AD".$flag9, "=C".$flag9."+F".$flag9."+I".$flag9."+L".$flag9."+O".$flag9."+R".$flag9."+U".$flag9."+X".$flag9."+AA".$flag9."");
					$hoja->setCellValue("AE".$flag9, "=D".$flag9."+G".$flag9."+J".$flag9."+M".$flag9."+P".$flag9."+S".$flag9."+V".$flag9."+Y".$flag9."+AB".$flag9."");
					$hoja->setCellValue("AF".$flag9, "=AD".$flag9."-AE".$flag9."");

					$arras = array(1=>"E",2=>"H",3=>"K",4=>"N",5=>"Q",6=>"T",7=>"W",8=>"Z",9=>"AC");
					for ($i=1; $i <=9 ; $i++){
						$condRed = new PHPExcel_Style_Conditional();
						$condRed->setConditionType(PHPExcel_Style_Conditional::CONDITION_CELLIS)
				                ->setOperatorType(PHPExcel_Style_Conditional::OPERATOR_NOTEQUAL)
				                ->addCondition(0)
				                ->getStyle()
				                ->applyFromArray(
				                	array(
									  'font'=>array(
									   'color'=>array('argb'=>'FF9C0006')
									  ),
									  'fill'=>array(
										  'type' =>PHPExcel_Style_Fill::FILL_SOLID,
										  'startcolor' =>array('argb' => 'FFFFC7CE'),
										  'endcolor' =>array('argb' => 'FFFFC7CE')
										)
									)
								);
						$conditionalStyles = $this->excelfile->getActiveSheet()->getStyle($arras[$i].''.$flag9)->getConditionalStyles();
						array_push($conditionalStyles,$condRed);
						$this->excelfile->getActiveSheet()->getStyle($arras[$i].''.$flag9)->setConditionalStyles($conditionalStyles);
					}
					$arras2 = array(1=>"D",2=>"G",3=>"J",4=>"M",5=>"P",6=>"S",7=>"V",8=>"Y",9=>"AB");
					for ($i=1; $i <=9 ; $i++){
						$condRed = new PHPExcel_Style_Conditional();
						$condRed->setConditionType(PHPExcel_Style_Conditional::CONDITION_CELLIS)
				                ->setOperatorType(PHPExcel_Style_Conditional::OPERATOR_NOTEQUAL)
				                ->addCondition(0)
				                ->getStyle()
				                ->applyFromArray(
				                	array(
									  'font'=>array(
									   'color'=>array('argb'=>'FF000000')
									  ),
									  'fill'=>array(
										  'type' =>PHPExcel_Style_Fill::FILL_SOLID,
										  'startcolor' =>array('argb' => 'FFFF00'),
										  'endcolor' =>array('argb' => 'FFFF00')
										)
									)
								);
						$conditionalStyles = $this->excelfile->getActiveSheet()->getStyle($arras2[$i].''.$flag9)->getConditionalStyles();
						array_push($conditionalStyles,$condRed);
						$this->excelfile->getActiveSheet()->getStyle($arras2[$i].''.$flag9)->setConditionalStyles($conditionalStyles);
					}
					$flag9++;
				}
				$flag9 = $flag9 + 4;
				$hoja->mergeCells('A'.$flag9.':B'.$flag9.'');
				$this->cellStyle("A".$flag9, "FFFFFF", "000000", TRUE, 12, "Franklin Gothic Book");
				$hoja->setCellValue("A".$flag9, "SIN ASOCIAR A PEDIDOS FINALES '".$provnombre->nombre."'");
				$this->excelfile->getActiveSheet()->getStyle('A'.$flag9.':L'.$flag9.'')->applyFromArray($styleArray9);
				$this->cellStyle("C".$flag9, "C00000", "000000", TRUE, 12, "Franklin Gothic Book");
				$hoja->setCellValue("C".$flag9, "CEDIS");
				$this->cellStyle("D".$flag9, "01B0F0", "000000", TRUE, 12, "Franklin Gothic Book");
				$hoja->setCellValue("D".$flag9, "ABARROTES");
				$this->cellStyle("E".$flag9, "FF0000", "000000", TRUE, 12, "Franklin Gothic Book");
				$hoja->setCellValue("E".$flag9, "VILLAS");
				$this->cellStyle("F".$flag9, "FF6D0B", "000000", TRUE, 12, "Franklin Gothic Book");
				$hoja->setCellValue("F".$flag9, "TIENDA");
				$this->cellStyle("G".$flag9, "C5C5C5", "000000", TRUE, 12, "Franklin Gothic Book");
				$hoja->setCellValue("G".$flag9, "ULTRAMARINOS");
				$this->cellStyle("H".$flag9, "93D051", "000000", TRUE, 12, "Franklin Gothic Book");
				$hoja->setCellValue("H".$flag9, "TRINCHERAS");
				$this->cellStyle("I".$flag9, "B1A0C7", "000000", TRUE, 12, "Franklin Gothic Book");
				$hoja->setCellValue("I".$flag9, "AZT MERCADO");
				$this->cellStyle("J".$flag9, "DA9694", "000000", TRUE, 12, "Franklin Gothic Book");
				$hoja->setCellValue("J".$flag9, "TENENCIA");
				$this->cellStyle("K".$flag9, "4CACC6", "000000", TRUE, 12, "Franklin Gothic Book");
				$hoja->setCellValue("K".$flag9, "TIJERAS");
				$flag9++;
				$this->cellStyle("A".$flag9.":L".$flag9, "000000", "FFFFFF", TRUE, 12, "Franklin Gothic Book");
				$hoja->setCellValue("A".$flag9, "CÓDIGO");
				$hoja->setCellValue("B".$flag9, "DESCRIPCIÓN");
				$hoja->setCellValue("C".$flag9, "FACTS");
				$hoja->setCellValue("D".$flag9, "FACTS");
				$hoja->setCellValue("E".$flag9, "FACTS");
				$hoja->setCellValue("F".$flag9, "FACTS");
				$hoja->setCellValue("G".$flag9, "FACTS");
				$hoja->setCellValue("H".$flag9, "FACTS");
				$hoja->setCellValue("I".$flag9, "FACTS");
				$hoja->setCellValue("J".$flag9, "FACTS");
				$hoja->setCellValue("K".$flag9, "FACTS");
				$hoja->setCellValue("K".$flag9, "TOTALES");
				$flag9++;
				$facts2 = $this->fact_md->getfacts2(NULL,$proveedor);
				if ($facts2) {
					foreach ($facts2 as $key => $varray){
						$this->cellStyle('C'.$flag9, "D4EAEF", "000000", TRUE, 12, "Franklin Gothic Book");
						$this->cellStyle('D'.$flag9, "D4EAEF", "000000", TRUE, 12, "Franklin Gothic Book");
						$this->cellStyle('F'.$flag9, "D4EAEF", "000000", TRUE, 12, "Franklin Gothic Book");
						$this->cellStyle('G'.$flag9, "D4EAEF", "000000", TRUE, 12, "Franklin Gothic Book");
						$this->cellStyle('H'.$flag9, "D4EAEF", "000000", TRUE, 12, "Franklin Gothic Book");
						$this->cellStyle('I'.$flag9, "D4EAEF", "000000", TRUE, 12, "Franklin Gothic Book");
						$this->cellStyle('J'.$flag9, "D4EAEF", "000000", TRUE, 12, "Franklin Gothic Book");
						$this->cellStyle('K'.$flag9, "D4EAEF", "000000", TRUE, 12, "Franklin Gothic Book");
						$this->cellStyle('E'.$flag9, "D4EAEF", "000000", TRUE, 12, "Franklin Gothic Book");
						$hoja->setCellValue("A".$flag9, $varray["codigo"])->getStyle("A{$flag9}")->getNumberFormat()->setFormatCode('# ???/???');
						$hoja->setCellValue("B".$flag9, $varray["descripcion"]);
						$hoja->setCellValue("C".$flag9, $varray[87]);
						$hoja->setCellValue("D".$flag9, $varray[57]);
						$hoja->setCellValue("E".$flag9, $varray[90]);
						$hoja->setCellValue("F".$flag9, $varray[58]);
						$hoja->setCellValue("G".$flag9, $varray[59]);
						$hoja->setCellValue("H".$flag9, $varray[60]);
						$hoja->setCellValue("I".$flag9, $varray[61]);
						$hoja->setCellValue("J".$flag9, $varray[62]);
						$hoja->setCellValue("K".$flag9, $varray[63]);
						$hoja->setCellValue("L".$flag9, "=SUM(C".$flag9.":K".$flag9.")");
						$this->excelfile->getActiveSheet()->getStyle('A'.$flag9.':L'.$flag9.'')->applyFromArray($styleArray9);
						$flag9++;
					}
				}

			}



			$file_name = "Facturas ".$provnombre->nombre.".xlsx"; //Nombre del documento con extención


			header("Content-Type: application/vnd.ms-excel; charset=utf-8");
			header("Content-Disposition: attachment;filename=".$file_name);
			header("Cache-Control: max-age=0");
			$excel_Writer = PHPExcel_IOFactory::createWriter($this->excelfile, "Excel2007");
			$excel_Writer->save("php://output");
		}else{
			$this->jsonResponse("No se pudo completar el reporte, por favor inténtalo nuevamente");
		}
		//$this->jsonResponse();
	}

	public function codigos(){
		$data['links'] = [
			'/assets/css/plugins/dataTables/dataTables.bootstrap',
			'/assets/css/plugins/dataTables/dataTables.responsive',
			'/assets/css/plugins/dataTables/dataTables.tableTools.min',
			'/assets/css/plugins/dataTables/buttons.dataTables.min',
		];

		$data['scripts'] = [
			'/scripts/codigosProv',
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
		$data["provis"] = $this->fact_md->getFactProv(NULL);
		$this->estructura("Facturas/codigos", $data);
	}

	public function getnumeros(){
		$data["title"]="Facturas Registradas";
		$data["numeros"] = $this->fact_md->getnumeros(NULL);
		$data["view"] =$this->load->view("Facturas/getnumeros", $data, TRUE);
		$this->jsonResponse($data);
	}

	public function setGifted($id_comparacion,$estats,$gifted){
		if ($estats) {
			$facturas = $this->comp_md->update(["gift"=>1,"gifted"=>$gifted],$id_comparacion);
		}else{
			$facturas = $this->comp_md->update(["gift"=>0,"gifted"=>0],$id_comparacion);
		}
		$this->jsonResponse($facturas);
	}
	

	public function setDevolution($id_comparacion,$estats,$gifted){
		if ($estats) {
			$facturas = $this->comp_md->update(["devolucion"=>1,"devueltos"=>$gifted],$id_comparacion);
		}else{
			$facturas = $this->comp_md->update(["devolucion"=>0,"devueltos"=>0],$id_comparacion);
		}
		$this->jsonResponse($facturas);
	}

}

/* End of file Productos.php */
/* Location: ./application/controllers/Productos.php */