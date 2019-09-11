<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Productos extends MY_Controller {

	function __construct(){
		parent::__construct();
		$this->load->model("Productos_model", "pro_md");
		$this->load->model("Familias_model", "fam_md");
		$this->load->model("Cambios_model", "cambio_md");
		$this->load->model("Usuarios_model", "usua_mdl");
		$this->load->model("Prodcaja_model", "pcaja_md");
	}

	public function index(){
		$data['links'] = [
			'/assets/css/plugins/dataTables/dataTables.bootstrap',
			'/assets/css/plugins/dataTables/dataTables.responsive',
			'/assets/css/plugins/dataTables/dataTables.tableTools.min',
			'/assets/css/plugins/dataTables/buttons.dataTables.min',
		];

		$data['scripts'] = [
			'/scripts/productos',
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
		// $data["productos"]=$this->pro_md->getProductos();
		$this->estructura("Productos/table_productos", $data);
	}

	public function productos_dataTable(){
		$search = ["productos.codigo", "productos.nombre", "fam.nombre"];

		$columns = "productos.id_producto, productos.nombre AS producto, productos.codigo, fam.nombre AS familia,productos.color";

		$joins = [
			["table"	=>	"familias fam",	"ON"	=>	"productos.id_familia = fam.id_familia",	"clausula"	=>	"INNER"]
		];

		$group ="productos.id_producto";
		$order="productos.id_producto";

		$where = [
				["clausula"	=>	"productos.estatus <>",	"valor"	=>	0]
		];

		$productos = $this->pro_md->get_pagination($columns, $joins, $where, $search, $group, $order);

		$data =[];
		$no = $_POST["start"];
		if ($productos) {
			foreach ($productos as $key => $value) {
				$no ++;
				$row = [];
				$row[] = '<b>'.$value->id_producto.'</b>';
				$row[] = $value->codigo;
				$row[] = $value->producto;
				$row[] = $value->familia;
				$row[] = $this->column_buttons($value->id_producto);
				$data[] = $row;
			}
		}
		$salida = [
			"draw"				=>	$_POST['draw'],
			"recordsTotal"		=>	$this->pro_md->count_filtered("productos.id_producto", $where, $search, $joins),
			"recordsFiltered"	=>	$this->pro_md->count_filtered("productos.id_producto", $where, $search, $joins),
			"data" => $data];
		$this->jsonResponse($salida);
	}

	private function column_buttons($id_producto){
		$botones = "";
		$botones.='<button id="update_producto" class="btn btn-info" data-toggle="tooltip" title="Editar" data-id-producto="'.$id_producto.'">
						<i class="fa fa-pencil"></i>
					</button>';
		$botones.='&nbsp;<button id="delete_producto" class="btn btn-warning" data-toggle="tooltip" title="Eliminar" data-id-producto="'.$id_producto.'">
							<i class="fa fa-trash"></i>
						</button>';
		return $botones;
	}

	public function add_producto(){
		$data["title"]="REGISTRAR PRODUCTOS";
		$data["familias"] = $this->fam_md->get();
		$data["view"] =$this->load->view("Productos/new_producto", $data, TRUE);
		$data["button"]="<button class='btn btn-success new_producto' type='button'>
							<span class='bold'><i class='fa fa-floppy-o'></i></span> &nbsp;Guardar
						</button>";
		$this->jsonResponse($data);
	}

	

	public function get_update($id){
		$data["title"]="ACTUALIZAR DATOS DEL PRODUCTO";
		$data["producto"] = $this->pro_md->get(NULL, ['id_producto'=>$id])[0];
		$data["familias"] = $this->fam_md->get();
		$data["view"] =$this->load->view("Productos/edit_producto", $data, TRUE);
		$data["button"]="<button class='btn btn-success update_producto' type='button'>
							<span class='bold'><i class='fa fa-floppy-o'></i></span> &nbsp;Guardar cambios
						</button>";
		$this->jsonResponse($data);
	}

	public function get_delete($id){
		$data["title"]="PRODUCTO A ELIMINAR";
		$data["producto"] = $this->pro_md->get(NULL, ['id_producto'=>$id])[0];
		$data["view"] = $this->load->view("Productos/delete_producto", $data,TRUE);
		$data["button"]="<button class='btn btn-danger delete_producto' type='button'>
							<span class='bold'><i class='fa fa-times'></i></span> &nbsp;Aceptar
						</button>";
		$this->jsonResponse($data);
	}

	public function accion($param){
		$estats = $this->input->post('estatus');
		if($this->input->post('id_familia') =="75"){
			$estats = 6;
		}elseif ($this->input->post('id_familia') =="74") {
			$estats = 5;
		}elseif ($this->input->post('id_familia') =="73") {
			$estats = 4;
		}
		$user = $this->session->userdata();
		$producto = ['codigo'	=>	$this->input->post('codigo'),
					'nombre'	=>	strtoupper($this->input->post('nombre')),
					'estatus'	=>	$estats,
					'colorp'	=>	$this->input->post('colorp'),
					'id_familia'=>	($this->input->post('id_familia') !="-1") ? $this->input->post('id_familia') : NULL
		];
		$getProducto = $this->pro_md->get(NULL, ['codigo'=>$producto['codigo']])[0];
		switch ($param) {
			case (substr($param, 0, 1) === 'I'):
				if (sizeof($getProducto) == 0) {
					$cambios = [
							"id_usuario" => $user["id_usuario"],
							"fecha_cambio" => date('Y-m-d H:i:s'),
							"antes" => "Registro de nuevo producto",
							"despues" => "Código: ".$producto['codigo']." /Nombre: ".$producto['nombre']." /Familia: ".$producto['id_familia']];
					$data['cambios'] = $this->cambio_md->insert($cambios);
					$data ['id_producto']=$this->pro_md->insert($producto);
					$mensaje = ["id" 	=> 'Éxito',
								"desc"	=> 'Producto registrado correctamente',
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

		$this->cellStyle("A1:D2", "000000", "FFFFFF", TRUE, 12, "Franklin Gothic Book");
		$border_style= array('borders' => array('right' => array('style' =>
			PHPExcel_Style_Border::BORDER_THIN,'color' => array('argb' => '000000'),)));

		$this->cellStyle("F1:G2", "000000", "FFFFFF", TRUE, 12, "Franklin Gothic Book");
		$border_style= array('borders' => array('right' => array('style' =>
			PHPExcel_Style_Border::BORDER_THIN,'color' => array('argb' => '000000'),)));

		$hoja->setCellValue("B1", "DESCRIPCIÓN SISTEMA")->getColumnDimension('B')->setWidth(60);

		$hoja->setCellValue("A2", "CÓDIGO")->getColumnDimension('A')->setWidth(25); //Nombre y ajuste de texto a la columna
		$hoja->setCellValue("C1", "NÚMERO")->getColumnDimension('C')->setWidth(20);
		$hoja->setCellValue("C2", "FAMILIA")->getColumnDimension('C')->setWidth(20);
		$hoja->setCellValue("D1", "CONVERSIÓN")->getColumnDimension('D')->setWidth(20);
		$hoja->setCellValue("D2", "SI / NO")->getColumnDimension('D')->setWidth(20);

		$hoja->setCellValue("F1", "FAMILIA")->getColumnDimension('F')->setWidth(35);
		$hoja->setCellValue("G1", "NÚMERO")->getColumnDimension('G')->setWidth(18);
		$hoja->setCellValue("G2", "FAMILIA")->getColumnDimension('G')->setWidth(18);
		$productos = $this->pro_md->getProdFam(NULL,0);
		$row_print = 3;
		if ($productos){
			foreach ($productos as $key => $value){
				$hoja->setCellValue("F{$row_print}", $value['familia']);
				$hoja->setCellValue("G{$row_print}", $value['id_familia']);
				$row_print +=1;
			}
		}
		$hoja->getStyle("A3:H{$row_print}")
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

	public function upload_productos(){
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
        $this->upload->do_upload('file_productos',$filen);
		$this->load->library("excelfile");
		ini_set("memory_limit", -1);
		$file = $_FILES["file_productos"]["tmp_name"];
		$filename=$_FILES['file_productos']['name'];
		$sheet = PHPExcel_IOFactory::load($file);
		$objExcel = PHPExcel_IOFactory::load($file);
		$sheet = $objExcel->getSheet(0);
		$num_rows = $sheet->getHighestDataRow();
		if ($sheet->getCell('E'.$i)->getValue() === "") {
			$estatus = 1
		}else{
			$estatus = $sheet->getCell('E'.$i)->getValue();
		}
		
		for ($i=3; $i<=$num_rows; $i++) {
			$productos = $this->pro_md->get("id_producto",['codigo'=> htmlspecialchars($sheet->getCell('A'.$i)->getValue(), ENT_QUOTES, 'UTF-8')])[0];
			$conversion = $sheet->getCell('D'.$i)->getValue() == "SI" ? 1 : 0; 
			if (sizeof($productos) > 0) {
				$new_producto=[
						"id_familia" => $sheet->getCell('C'.$i)->getValue(),//Recupera el id_usuario activo
						"nombre" => $sheet->getCell('B'.$i)->getValue(),
						"codigo" => $sheet->getCell('A'.$i)->getValue(),
						"colorp" => $conversion,
						"estatus" => $sheet->getCell('E'.$i)->getValue()];
				$data ['id_producto'] = $this->pro_md->update($new_producto, $productos->id_producto);
			}else{
				$new_producto=[
						"id_familia" => $sheet->getCell('C'.$i)->getValue(),//Recupera el id_usuario activo
						"nombre" => $sheet->getCell('B'.$i)->getValue(),
						"codigo" => $sheet->getCell('A'.$i)->getValue(),
						"colorp" => $conversion,
						"estatus" => $sheet->getCell('E'.$i)->getValue()];
				$data ['id_producto']=$this->pro_md->insert($new_producto);
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
						"antes"			=>	"El usuario sube productos",
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


	public function upload_productos2(){
		$this->load->library("excelfile");
		ini_set("memory_limit", -1);
		$file = $_FILES["file_p"]["tmp_name"];
		$sheet = PHPExcel_IOFactory::load($file);
		$objExcel = PHPExcel_IOFactory::load($file);
		$sheet = $objExcel->getSheet(0);
		$num_rows = $sheet->getHighestDataRow();
		
		for ($i=2; $i<=$num_rows; $i++) {
			$codigo = $this->pro_md->get("id_producto",['codigo'=> htmlspecialchars($sheet->getCell('A'.$i)->getValue(), ENT_QUOTES, 'UTF-8')])[0];
			if (sizeof($codigo) > 0) {
				$new_producto=[
					"id_prodfactura" => $codigo->id_producto,
					"id_proveedor" => 3,
					"codigo" => $sheet->getCell('C'.$i)->getValue(),
					"clave" => $sheet->getCell('E'.$i)->getValue(),
					"descripcion" => $sheet->getCell('G'.$i)->getValue(),
					"codigo_factura" => $sheet->getCell('D'.$i)->getValue()];
					$codiga = $this->pcaja_md->get("id_prodcaja",['codigo'=> htmlspecialchars($sheet->getCell('A'.$i)->getValue(), ENT_QUOTES, 'UTF-8')])[0];
				if (sizeof($codiga) > 0) {
					$data ['id_prodcaja']=$this->pcaja_md->update($new_producto, $codiga->id_prodcaja);
				}else{
					$data ['id_prodcaja']=$this->pcaja_md->insert($new_producto);
				}
			}
		}
		$mensaje=[	"id"	=>	'Éxito',
							"desc"	=>	'Productos cargados correctamente en el Sistema',
							"type"	=>	'success'];
		$this->jsonResponse($mensaje);
	}

	public function codigos(){
		$data['links'] = [
			'/assets/css/plugins/dataTables/dataTables.bootstrap',
			'/assets/css/plugins/dataTables/dataTables.responsive',
			'/assets/css/plugins/dataTables/dataTables.tableTools.min',
			'/assets/css/plugins/dataTables/buttons.dataTables.min',
		];

		$data['scripts'] = [
			'/scripts/codigos',
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
		$this->estructura("Productos/codigos", $data);
	}

	public function buscaCodigos(){
		$busca = $this->input->post("values");
		$busca2 = $this->input->post("values2");
		$productos = $this->pro_md->buscaCodigos(NULL,$busca,$busca2);
		$this->jsonResponse($productos);
	}

	public function upload_codigos(){
		$arrays = array();
		$array = array();
		$this->load->library("excelfile");
		ini_set("memory_limit", -1);
		$file = $_FILES["file_codigos"]["tmp_name"];
		$sheet = PHPExcel_IOFactory::load($file);
		$objExcel = PHPExcel_IOFactory::load($file);
		$sheet = $objExcel->getSheet(0);
		$num_rows = $sheet->getHighestDataRow();
		$proveedor = $this->input->post('proveedor');
		for ($i=3; $i<=$num_rows; $i++) {
			$codigo = $this->pro_md->get(NULL,["codigo"=>htmlspecialchars($sheet->getCell('A'.$i)->getValue(), ENT_QUOTES, 'UTF-8')])[0];
			$cellB = $this->getOldVal($sheet,$i,"B");
			$cellC = $this->getOldVal($sheet,$i,"C");
			if (sizeof($codigo) > 0 && $cellB <> 0) {
				$new_producto=[
					"id_prodfactura" => $codigo->id_producto,
					"id_proveedor" => $proveedor,
					"codigo_factura" => $cellB,
					"descripcion" => $cellC
				];

				$codiga = $this->pcaja_md->get(NULL,['id_prodfactura'=> $codigo->id_producto,"id_proveedor"=>$proveedor,"codigo_factura"=>$cellB])[0];
				if (sizeof($codiga) > 0) {
					$new_codis=[
						"nombre"=>$codigo->nombre,
						"id_producto"=>$codigo->id_producto,
						"id_prodfactura"=>$cellB,
						"id_proveedor"=>$proveedor,
						"codigo"=>$codigo->codigo
					];
					array_push($arrays, $new_codis);
				}else{
					array_push($arrays, $new_producto);
					$data ['id_prodcaja']=$this->pcaja_md->insert($new_producto);
				}
			}
		}
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

	

}

/* End of file Productos.php */
/* Location: ./application/controllers/Productos.php */