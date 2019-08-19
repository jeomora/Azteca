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
			'/scripts/dragscroll',
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
		$this->estructura("Facturas/Comparar", $data);
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
		$pdf = $parser->parseFile(APPPATH.'vendor\smalot\pdfparser\samples\adela.pdf');

		$text = $pdf->getText();
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
		$excel_Writer->save("php://output");
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
		$folio = htmlspecialchars($sheet->getCell('B1')->getValue(), ENT_QUOTES, 'UTF-8');
		for ($i=3; $i<=$num_rows; $i++) {
			$codigo = $this->getOldVal($sheet,$i,"A");
			$cellB = $this->getOldVal($sheet,$i,"B");
			$cellC = $this->getOldVal($sheet,$i,"C");
			$cellD = $this->getOldVal($sheet,$i,"D");
			
			if (sizeof($codigo) > 0) {
				$new_producto=[
					"folio" => $folio,
					"id_proveedor" => $proveedor,
					"precio" => $cellD,
					"codigo" => $codigo,
					"descripcion" => $cellB,
					"cantidad" => $cellC,
					"id_tienda"=> $id_tienda
				];

				$codiga = $this->fact_md->getThem(NULL,$folio,$proveedor,$id_tienda,$codigo,$cellD,$cellC);
				//$this->jsonResponse($codiga);
				if ($codiga) {
				}else{
					$data['id_prodcaja']=$this->fact_md->insert($new_producto);
				}
			}
		}
		$query = "pd.nombre,pd.codigo,fc.descripcion,fc.folio,fc.codigo as factu,fc.cantidad,fc.precio,fc.estatus,fc.fecha_registro,fc.id_tienda,fc.id_proveedor,pc.id_prodcaja,pc.id_prodfactura, f.".$tend." as total,f.costo,f.promocion,f.id_final FROM facturas fc LEFT JOIN prodcaja pc ON fc.codigo = pc.codigo_factura AND fc.id_proveedor = pc.id_proveedor LEFT JOIN finales f ON pc.id_prodfactura = f.id_producto AND fc.id_proveedor = f.id_proveedor LEFT JOIN productos pd ON pc.id_prodfactura = pd.id_producto WHERE fc.id_proveedor = ".$proveedor." AND WEEKOFYEAR(fc.fecha_registro) = WEEKOFYEAR(CURDATE()) AND fc.folio = '".$folio."' ORDER BY fc.id_factura ASC";
		$factus = $this->fact_md->getFactos(NULL,json_encode($query));
		$query2 = "f2.id_final,f2.costo,f2.promocion,pd.nombre,pd.codigo,pd.id_producto,f2.".$tend." as total from finales f2 LEFT JOIN productos pd ON f2.id_producto = pd.id_producto WHERE f2.id_final NOT IN(SELECT f1.id_final from finales f1 WHERE f1.id_final IN (SELECT f.id_final FROM facturas fc LEFT JOIN prodcaja pc ON fc.codigo = pc.codigo_factura AND fc.id_proveedor = pc.id_proveedor LEFT JOIN finales f ON pc.id_prodfactura = f.id_producto AND fc.id_proveedor = f.id_proveedor WHERE fc.id_proveedor = ".$proveedor." AND WEEKOFYEAR(fc.fecha_registro) = WEEKOFYEAR(CURDATE()) AND fc.folio = '".$folio."')) AND id_proveedor = ".$proveedor." AND WEEKOFYEAR(f2.fecha_registro) = WEEKOFYEAR(CURDATE()) AND f2.".$tend." > 0 ORDER BY f2.id_final ASC";
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
		foreach ($value as $key => $v) {
			$producto = $this->pro_md->get(NULL,["codigo"=>$v["producto"]])[0];
			if ($producto) {
				$prodcaja=$this->pcaja_md->get(NULL,["codigo_factura"=>$v["factura"],"id_proveedor"=>$v["id_proveedor"],"estatus"=>1,"id_prodfactura"=>$producto->id_producto])[0];
				if(!$prodcaja) {
					$new_prodcaja=[
						"id_prodfactura" => $producto->id_producto,
						"id_proveedor" => $v["id_proveedor"],
						"codigo_factura" => $v["factura"],
						"descripcion"	=> $v["descripcion"]
					];
					$data['prodcaja'] = $this->pcaja_md->insert($new_prodcaja);
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
				"devueltos" => $v["devueltos"]
			];
			if ($compara) {
				$data['prodcaja'] = $this->comp_md->update($new_compara,$compara->id_comparacion);
			}else{
				$data['prodcaja'] = $this->comp_md->insert($new_compara);
			}
		}
		
	}
}

/* End of file Productos.php */
/* Location: ./application/controllers/Productos.php */