<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Cotizaciones extends MY_Controller {

	function __construct(){
		parent::__construct();
		$this->load->model("Cotizaciones_model", "ct_mdl");
		$this->load->model("Productos_model", "prod_mdl");
		$this->load->model("Usuarios_model", "usua_mdl");
		$this->load->model("Pedidos_model", "ped_mdl");
		$this->load->model("Familias_model", "fam_mdl");
	}

	public function index(){
		ini_set("memory_limit", "-1");
		$data['links'] = [
			'/assets/css/plugins/dataTables/dataTables.bootstrap',
			'/assets/css/plugins/dataTables/dataTables.responsive',
			'/assets/css/plugins/dataTables/dataTables.tableTools.min',
			'/assets/css/plugins/dataTables/buttons.dataTables.min',
		];
		$data['scripts'] = [
			'/scripts/cotizaciones',
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

		$user = $this->session->userdata();//Trae los datos del usuario;

		$where = [];
		$this->data["message"] =NULL;
		if(!$this->session->userdata("username")){
			redirect("Welcome/Login", "");
		}elseif($user['id_grupo'] == 2){//Solo mostrar sus Productos cotizados cuando es proveedor
			$where=["cotizaciones.id_proveedor" => $user['id_usuario'],
					"WEEKOFYEAR(cotizaciones.fecha_registro)" => $this->weekNumber()];
			$data["cotizaciones"] = $this->ct_mdl->getCotizaciones($where);
			$this->estructura("Cotizaciones/table_cotizaciones", $data, FALSE);
		}else{
			$data["cotizaciones"] = $this->ct_mdl->getCotz(NULL);
			$data["proveedores"] = $this->usua_mdl->getUsuarios();
			$this->estructura("Cotizaciones/cotizaciones_view", $data, FALSE);
		}
	}

	public function add_cotizacion(){
		$data["title"]="REGISTRAR COTIZACIONES";
		$data["productos"] = $this->prod_mdl->get("id_producto, nombre");
		$data["view"]=$this->load->view("Cotizaciones/new_cotizacion", $data, TRUE);
		$data["button"]="<button class='btn btn-success new_cotizacion' type='button'>
							<span class='bold'><i class='fa fa-floppy-o'></i></span> &nbsp;Guardar
						</button>";
		$this->jsonResponse($data);
	}

	public function save(){
		$cotizacion = [
			'nombre'			=>	strtoupper($this->input->post('nombre')),
			'id_producto'		=>	$this->input->post('id_producto'),
			'id_proveedor'		=>	$this->session->userdata('id_usuario'),
			'num_one'			=>	$this->input->post('num_one'),
			'num_two'			=>	$this->input->post('num_two'),
			'precio'			=>	str_replace(',', '', $this->input->post('precio')),//precio base
			'precio_promocion'	=>	($this->input->post('precio_promocion') > 0) ? str_replace(',', '', $this->input->post('precio_promocion')) : str_replace(',', '', $this->input->post('precio')),//precio con promoción
			'descuento'			=>	str_replace(',', '', $this->input->post('porcentaje')),
			'fecha_registro'	=>	date('Y-m-d H:i:s'),
			'fecha_caduca'		=>	($this->input->post('fecha_caducidad') !='') ? date('Y-m-d', strtotime($this->input->post('fecha_caducidad'))) : NULL,
			'existencias'		=>	str_replace(',', '',$this->input->post('existencias')),
			'observaciones'		=>	strtoupper($this->input->post('observaciones'))
		];
		$data ['id_cotizacion']=$this->ct_mdl->insert($cotizacion);
		$mensaje = [
			"id" 	=> 'Éxito',
			"desc"	=> 'Cotización registrada correctamente',
			"type"	=> 'success'
		];
		$this->jsonResponse($mensaje);
	}

	public function update(){
		$size = sizeof($this->input->post('id_cotz[]'));
		$cotz = $this->input->post('id_cotz[]');
		$precio = $this->input->post('precio[]');
		$precio_promocion = $this->input->post('precio_promocion[]');
		$num_one = $this->input->post('num_one[]');
		$num_two = $this->input->post('num_two[]');
		$descuento = $this->input->post('descuento[]');
		$observaciones = $this->input->post('observaciones[]');
		for($i = 0; $i < $size; $i++){
			$data ['id_cotizacion'] = $this->ct_mdl->update([
				"precio" => $precio[$i],
				"precio_promocion" => $precio_promocion[$i],
				"num_one" => $num_one[$i],
				"num_two" => $num_two[$i],
				"descuento" => $descuento[$i],
				"observaciones" => $observaciones[$i]
			], $cotz[$i]);
		}
		$mensaje = [
			"id" 	=> 'Éxito',
			"desc"	=> 'Cotización actualizada correctamente',
			"type"	=> 'success'
		];
		$this->jsonResponse($mensaje);
	}

	public function delete(){
		$size = sizeof($this->input->post('id_producto[]'));
		$productos = $this->input->post('id_producto[]');
		for($i = 0; $i < $size; $i++){
			$data ['id_cotizacion'] = $this->ct_mdl->update(["estatus" => 0], $productos[$i]);
		}
		$mensaje = [
			"id" 	=> 'Éxito',
			"desc"	=> 'Cotización eliminada correctamente',
			"type"	=> 'success'
		];
		$this->jsonResponse($mensaje);
	}

	public function hacer_pedido($value=''){
		$size = sizeof($this->input->post('id_producto[]'));
		$productos = $this->input->post('id_producto[]');
		$importe = $this->input->post('importe[]');
		for($i = 0; $i < $size; $i++){
			$pedid = $this->ped_mdl->getWeekPedidos(NULL,$productos[$i],$this->weekNumber());
			if($pedid){
				$id_pedido = $this->ped_mdl->update(["total" => 0], $pedid->total+$importe[$i]);
			}else{
				$pedido = [
					"id_sucursal"		=>	1,
					"id_proveedor"		=>	$producto[$i],
					"id_user_registra"	=>	$this->ion_auth->user()->row()->id, 
					"fecha_registro"	=>	date("Y-m-d H:i:s"),
					"total"				=>	str_replace(",", "", $importe[$i])
				];
				$id_pedido = $this->ped_mdl->insert($pedido);
			}
			$detalle_pedido = [
				'id_pedido'		=>	$id_pedido,
				'id_producto'	=>	$this->input->post('id_prod'),
				'cantidad'		=>	str_replace(",", "", $this->input->post('cantidad[]')[$i]),
				'precio'		=>	str_replace(",", "", $this->input->post('precio[]')[$i]),
				'importe'		=>	str_replace(",", "", $this->input->post('importe[]')[$i])
			];
		}
	}

	public function get_update($id){
		$data["cotizacion"] = $this->ct_mdl->get(NULL, ['id_cotizacion'=>$id])[0];
		$data["producto"] = $this->prod_mdl->get(NULL, ['id_producto'=>$data["cotizacion"]->id_producto])[0];
		$data["title"]="ACTUALIZAR COTIZACIÓN DE <br>".$data["producto"]->nombre;
		$user = $this->session->userdata();
		if($user['id_grupo'] ==2){//Proveedor
			$where=["cotizaciones.id_proveedor" => $user['id_usuario']];
			$data["cots"]=$this->ct_mdl->get_cots($where, $data["cotizacion"]->id_producto);
		}else{
			$data["cots"]=$this->ct_mdl->get_cots(NULL, $data["cotizacion"]->id_producto);
		}
		$data["view"]=$this->load->view("Cotizaciones/edit_cotizacion", $data, TRUE);
		$data["button"]="<button class='btn btn-success update_cotizacion' type='button'>
							<span class='bold'><i class='fa fa-floppy-o'></i></span> &nbsp;Guardar cambios
						</button>";
		$this->jsonResponse($data);
	}

	public function get_delete($id){
		$data["cotizacion"] = $this->ct_mdl->get(NULL, ['id_cotizacion'=>$id])[0];
		$data["producto"] = $this->prod_mdl->get(NULL, ['id_producto'=>$data["cotizacion"]->id_producto])[0];
		$data["title"]="Seleccione una opción para eliminar la Cotización del producto:<br>".$data["producto"]->nombre;
		$user = $this->session->userdata();
		if($user['id_grupo'] ==2){//Proveedor
			$where=["cotizaciones.id_proveedor" => $user['id_usuario']];
			$data["cots"]=$this->ct_mdl->get_cots($where, $data["cotizacion"]->id_producto);
		}else{
			$data["cots"]=$this->ct_mdl->get_cots(NULL, $data["cotizacion"]->id_producto);
		}
		$data["view"]=$this->load->view("Cotizaciones/delete_cotizacion", $data, TRUE);
		$data["button"]="<button class='btn btn-danger delete_cotizacion' type='button'>
							<span class='bold'><i class='fa fa-trash'></i></span> &nbsp;Eliminar
						</button>";
		$this->jsonResponse($data);
	}

	public function set_pedido($id){
		$data["cotizacion"] = $this->ct_mdl->get(NULL, ['id_cotizacion'=>$id])[0];
		$data["producto"] = $this->prod_mdl->get(NULL, ['id_producto'=>$data["cotizacion"]->id_producto])[0];
		$data["proveedor"] = $this->ct_mdl->pedido(NULL,$data["producto"]->id_producto);
		
		$data["title"]="HACER PEDIDO ".$data['producto']->nombre;
		$data["view"]=$this->load->view("Cotizaciones/new_pedido", $data, TRUE);
		$data["button"]="<button class='btn btn-success new_pedido' type='button'>
							<span class='bold'><i class='fa fa-shopping-cart'></i></span> &nbsp;Hacer Pedido
						</button>";
		$this->jsonResponse($data);
	}

	public function fill_formato(){
		$id_proves = $this->input->post('id_proves');
		$proves = $this->input->post('id_proves2');
		if($proves != "nope"){
			$this->fill_formato1($proves, $id_proves);
		}else{
			$this->fill_formatoAll($proves, $id_proves);
		}
	}





	public function fill_excel(){
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

		$this->cellStyle("A1:N2", "000000", "FFFFFF", TRUE, 12, "Franklin Gothic Book");
		$hoja->setCellValue("A2", "CÓDIGO")->getColumnDimension('A')->setWidth(30); //Nombre y ajuste de texto a la columna
		$hoja->setCellValue("B1", "DESCRIPCIÓN")->getColumnDimension('B')->setWidth(50);
		$hoja->setCellValue("C2", "SISTEMA")->getColumnDimension('C')->setWidth(12);
		$hoja->setCellValue("D2", "PRECIO 4")->getColumnDimension('D')->setWidth(12);
		$hoja->setCellValue("E1", "PRECIO MENOR")->getColumnDimension('E')->setWidth(12);
		$hoja->setCellValue("F1", "PRECIO PROMOCIÓN")->getColumnDimension('F')->setWidth(12);
		$hoja->setCellValue("G1", "PROVEEDOR")->getColumnDimension('G')->setWidth(15);
		$hoja->setCellValue("H1", "OBSERVACIÓN")->getColumnDimension('H')->setWidth(30);
		$hoja->setCellValue("I1", "PRECIO MÁXIMO")->getColumnDimension('I')->setWidth(12);
		$hoja->setCellValue("J1", "PRECIO PROMEDIO")->getColumnDimension('J')->setWidth(12);
		$hoja->setCellValue("K1", "2DO PRECIO")->getColumnDimension('K')->setWidth(12);
		$hoja->setCellValue("L1", "PRECIO PROMOCIÓN")->getColumnDimension('L')->setWidth(12);
		$hoja->setCellValue("M1", "2DO PROVEEDOR")->getColumnDimension('M')->setWidth(15);
		$hoja->setCellValue("N1", "2DA OBSERVACIÓN")->getColumnDimension('N')->setWidth(30);
		$where=["WEEKOFYEAR(cotizaciones.fecha_registro)" => $this->weekNumber()];//Semana actual
		$fecha = date('Y-m-d');
		$cotizacionesProveedor = $this->ct_mdl->comparaCotizaciones($where, $fecha);

		$row_print =3;
		if ($cotizacionesProveedor){
			foreach ($cotizacionesProveedor as $key => $value){
				$hoja->setCellValue("B{$row_print}", $value['familia'])->getStyle("B{$row_print}")->getAlignment()->setWrapText(true);
				$this->cellStyle("B{$row_print}", "000000", "FFFFFF", TRUE, 12, "Franklin Gothic Book");
				$row_print +=1;
				if ($value['articulos']) {
					foreach ($value['articulos'] as $key => $row){
						$this->cellStyle("A{$row_print}", "FFFFFF", "000000", TRUE, 12, "Franklin Gothic Book");
						$this->cellStyle("B{$row_print}:L{$row_print}", "FFFFFF", "000000", FALSE, 12, "Franklin Gothic Book");
						$hoja->setCellValue("A{$row_print}", $row['codigo'])->getStyle("A{$row_print}")->getNumberFormat()->setFormatCode('# ???/???');//Formato de fraccion
						$hoja->setCellValue("B{$row_print}", $row['producto'])->getStyle("B{$row_print}");
						$hoja->setCellValue("C{$row_print}", $row['precio_sistema'])->getStyle("C{$row_print}")->getNumberFormat()->setFormatCode('"$"#,##0.00_-');//Formto de moneda
						$hoja->setCellValue("D{$row_print}", $row['precio_four'])->getStyle("D{$row_print}")->getNumberFormat()->setFormatCode('"$"#,##0.00_-');
						$hoja->setCellValue("E{$row_print}", $row['precio_firsto'])->getStyle("E{$row_print}")->getNumberFormat()->setFormatCode('"$"#,##0.00_-');
						if($row['precio_sistema'] < $row['precio_first']){
							$hoja->setCellValue("F{$row_print}", $row['precio_first'])->getStyle("F{$row_print}")->getNumberFormat()->setFormatCode('"$"#,##0.00_-');
							$this->cellStyle("F{$row_print}", "FDB2B2", "E21111", FALSE, 12, "Franklin Gothic Book");
						}else{
							$hoja->setCellValue("F{$row_print}", $row['precio_first'])->getStyle("F{$row_print}")->getNumberFormat()->setFormatCode('"$"#,##0.00_-');
							$this->cellStyle("F{$row_print}", "96EAA8", "0C800C", FALSE, 12, "Franklin Gothic Book");
						}
						$hoja->setCellValue("G{$row_print}", $row['proveedor_first'])->getStyle("G{$row_print}");
						$hoja->setCellValue("H{$row_print}", $row['promocion_first'])->getStyle("H{$row_print}");
						$hoja->setCellValue("I{$row_print}", $row['precio_maximo'])->getStyle("I{$row_print}")->getNumberFormat()->setFormatCode('"$"#,##0.00_-');
						$hoja->setCellValue("J{$row_print}", $row['precio_promedio'])->getStyle("J{$row_print}")->getNumberFormat()->setFormatCode('"$"#,##0.00_-');
						$hoja->setCellValue("K{$row_print}", $row['precio_nexto'])->getStyle("K{$row_print}")->getNumberFormat()->setFormatCode('"$"#,##0.00_-');
						if($row['precio_sistema'] < $row['precio_next']){
							$hoja->setCellValue("L{$row_print}", $row['precio_next'])->getStyle("L{$row_print}")->getNumberFormat()->setFormatCode('"$"#,##0.00_-');
							$this->cellStyle("L{$row_print}", "FDB2B2", "E21111", FALSE, 12, "Franklin Gothic Book");
						}else if($row['precio_next'] !== NULL){
							$hoja->setCellValue("L{$row_print}", $row['precio_next'])->getStyle("L{$row_print}")->getNumberFormat()->setFormatCode('"$"#,##0.00_-');
							$this->cellStyle("L{$row_print}", "96EAA8", "0C800C", FALSE, 12, "Franklin Gothic Book");
						}else{
							$hoja->setCellValue("L{$row_print}", $row['precio_next'])->getStyle("L{$row_print}")->getNumberFormat()->setFormatCode('"$"#,##0.00_-');
							$this->cellStyle("L{$row_print}", "FFFFFF", "000000", FALSE, 12, "Franklin Gothic Book");
						}
						$hoja->setCellValue("M{$row_print}", $row['proveedor_next'])->getStyle("M{$row_print}");						
						$hoja->setCellValue("N{$row_print}", $row['promocion_next'])->getStyle("N{$row_print}");
						$row_print ++;
					}
				}
			}
		}

		$file_name = "Cotizaciones.xls"; //Nombre del documento con extención
		header("Content-Type: application/vnd.ms-excel; charset=utf-8");
		header("Content-Disposition: attachment;filename=".$file_name);
		header("Cache-Control: max-age=0");
		$excel_Writer = PHPExcel_IOFactory::createWriter($this->excelfile, "Excel5");
		$excel_Writer->save("php://output");
	}

	public function upload_cotizaciones(){
		$this->load->library("excelfile");
		ini_set("memory_limit", "-1");
		$file = $_FILES["file_cotizaciones"]["tmp_name"];
		$sheet = PHPExcel_IOFactory::load($file);
		$objExcel = PHPExcel_IOFactory::load($file);
		$sheet = $objExcel->getSheet(0); 
		$num_rows = $sheet->getHighestDataRow();
		$proveedor = $this->session->userdata('id_usuario');
		for ($i=3; $i<=$num_rows; $i++) { 
			if($sheet->getCell('B'.$i)->getValue() > 0){
				$productos = $this->prod_mdl->get("id_producto",['nombre'=> htmlspecialchars($sheet->getCell('A'.$i)->getValue(), ENT_QUOTES, 'UTF-8')])[0];
				if (sizeof($productos) > 0) {
					$precio=0; $column_one=0; $column_two=0; $descuento=0; $precio_promocion=0;
					$precio = str_replace("$", "", str_replace(",", "replace", $sheet->getCell('B'.$i)->getValue()));
					$column_one = $sheet->getCell('D'.$i)->getValue();
					$column_two = $sheet->getCell('E'.$i)->getValue();
					$descuento = $sheet->getCell('F'.$i)->getValue();

					if ($column_one ==1 && $column_two ==1) {
						$precio_promocion = (($precio * $column_two)/($column_one+$column_two));
					}elseif ($column_one >=1 && $column_two >1) {
						$precio_promocion = (($precio * $column_two)/($column_one+$column_two));
					}elseif ($descuento >0) {
						$precio_promocion = ($precio - ($precio * ($descuento/100)));
					}else{
						$precio_promocion = $precio;
					}
					$new_cotizacion[$i]=[
						"id_producto"		=>	$productos->id_producto,
						"id_proveedor"		=>	$proveedor,//Recupera el id_usuario activo
						"precio"			=>	$precio,
						"num_one"			=>	$column_one,
						"num_two"			=>	$column_two,
						"descuento"			=>	$descuento,
						"precio_promocion"	=>	$precio_promocion,
						"fecha_registro"	=>	date('Y-m-d H:i:s'),
						"observaciones"		=>	$sheet->getCell('C'.$i)->getValue()
					];
				}
			}
		}
		if (sizeof($new_cotizacion) > 0) {
			$data['cotizacion']=$this->ct_mdl->insert_batch($new_cotizacion);
			$mensaje=[	"id"	=>	'Éxito',
						"desc"	=>	'Cotizaciones cargadas correctamente en el Sistema',
						"type"	=>	'success'];
		}else{
			$mensaje=[	"id"	=>	'Error',
						"desc"	=>	'Las Cotizaciones no se cargaron al Sistema',
						"type"	=>	'error'];
		}
		$this->jsonResponse($mensaje);
	}

	public function upload_allcotizaciones(){
		$this->load->library("excelfile");
		ini_set("memory_limit", "-1");
		$file = $_FILES["file_cotizaciones"]["tmp_name"];
		$sheet = PHPExcel_IOFactory::load($file);
		$objExcel = PHPExcel_IOFactory::load($file);
		$sheet = $objExcel->getSheet(0); 
		$num_rows = $sheet->getHighestDataRow();
		for ($i=3; $i<=$num_rows; $i++) { 
			if($sheet->getCell('B'.$i)->getValue() > 0){
				$productos = $this->prod_mdl->get("id_producto",['nombre'=> htmlspecialchars($sheet->getCell('A'.$i)->getValue(), ENT_QUOTES, 'UTF-8')])[0];
				$proveedor = $this->usua_mdl->get("id_usuario",['nombre'=> $sheet->getCell('G'.$i)->getValue()])[0];
				if (sizeof($productos) > 0) {
					$precio=0; $column_one=0; $column_two=0; $descuento=0; $precio_promocion=0;
					$precio = str_replace("$", "", str_replace(",", "replace", $sheet->getCell('B'.$i)->getValue()));
					$column_one = $sheet->getCell('D'.$i)->getValue();
					$column_two = $sheet->getCell('E'.$i)->getValue();
					$descuento = $sheet->getCell('F'.$i)->getValue();

					if ($column_one ==1 && $column_two ==1) {
						$precio_promocion = (($precio * $column_two)/($column_one+$column_two));
					}elseif ($column_one >=1 && $column_two >1) {
						$precio_promocion = (($precio * $column_two)/($column_one+$column_two));
					}elseif ($descuento >0) {
						$precio_promocion = ($precio - ($precio * ($descuento/100)));
					}else{
						$precio_promocion = $precio;
					}
					$new_cotizacion[$i]=[
						"id_producto"		=>	$productos->id_producto,
						"id_proveedor"		=>	$proveedor->id_usuario,//Recupera el id_usuario activo
						"precio"			=>	$precio,
						"num_one"			=>	$column_one,
						"num_two"			=>	$column_two,
						"descuento"			=>	$descuento,
						"precio_promocion"	=>	$precio_promocion,
						"fecha_registro"	=>	date('Y-m-d H:i:s'),
						"observaciones"		=>	$sheet->getCell('C'.$i)->getValue()
					];
				}
			}
		}
		if (sizeof($new_cotizacion) > 0) {
			$data['cotizacion']=$this->ct_mdl->insert_batch($new_cotizacion);
			$mensaje=[	"id"	=>	'Éxito',
						"desc"	=>	'Cotizaciones cargadas correctamente en el Sistema',
						"type"	=>	'success'];
		}else{
			$mensaje=[	"id"	=>	'Error',
						"desc"	=>	'Las Cotizaciones no se cargaron al Sistema',
						"type"	=>	'error'];
		}
		$this->jsonResponse($mensaje);
	}


	public function getProducto(){
		$where = ["productos.estatus" => 1];
		$productosProveedor = $this->prod_mdl->getProducto($where);
		$this->jsonResponse($productosProveedor);
	}
	public function getFamilia(){
		$where = ["familias.estatus" => 1];
		$productosProveedor = $this->fam_mdl->getFamilia($where);
		$this->jsonResponse($productosProveedor);
	}
	public function getProveedor(){
		$where = ["usuarios.id_grupo" => 2];
		$productosProveedor = $this->usua_mdl->getUsuario($where);
		$this->jsonResponse($productosProveedor);
	}

	public function upload_precios(){
		$this->load->library("excelfile");
		ini_set("memory_limit", "-1");
		$file = $_FILES["file_precios"]["tmp_name"];
		$objExcel = PHPExcel_IOFactory::load($file);
		$sheet = $objExcel->getSheet(0); 
		$num_rows = $sheet->getHighestDataRow();
		for ($i=3; $i<=$num_rows; $i++) {
			if($sheet->getCell('B'.$i)->getValue() !=''){
				$productos = $this->prod_mdl->get("id_producto",['codigo'=> htmlspecialchars($sheet->getCell('A'.$i)->getValue(), ENT_QUOTES, 'UTF-8')])[0];
				if (sizeof($productos) > 0) {
					$new_precios=[
						"id_producto"		=>	$productos->id_producto,
						"precio_sistema"	=>	str_replace("$", "", str_replace(",", "replace", $sheet->getCell('C'.$i)->getValue())),
						"precio_four"		=>	str_replace("$", "", str_replace(",", "replace", $sheet->getCell('D'.$i)->getValue())),
						"fecha_cambio"		=>	date('Y-m-d H:i:s')
					];
					$data['cotizacion']=$this->ct_mdl->update($new_precios,
						['WEEKOFYEAR(fecha_registro)' => $this->weekNumber(),'id_producto'=>$productos->id_producto]);
				}
			}
		}
		$mensaje=[	"id"	=>	'Éxito',
					"desc"	=>	'Precios cargados correctamente en el Sistema',
					"type"	=>	'success'];
		$this->jsonResponse($mensaje);
	}

	public function set_pedido_prov($id){
		$data["proveedor"] = $this->usua_mdl->getHim(NULL,$id);
		$where = ["cotizaciones.id_proveedor" => $id];
		$data["productos"] = $this->ct_mdl->productos_proveedor($where);
		$data["title"]="PEDIDOS A  ".$data["proveedor"]->names;
		$data["view"]=$this->load->view("Cotizaciones/pedidos_proveedor", $data, TRUE);
		$data["button"]="<button class='btn btn-success new_pedido' type='button'>
							<span class='bold'><i class='fa fa-shopping-cart'></i></span> &nbsp;Hacer Pedido
						</button>";
		$this->jsonResponse($data);
	}

	public function set_pedido_provs($id){

		ini_set("memory_limit", "-1");
		$search = ["productos.nombre", "cotizaciones.precio", "cotizaciones.observaciones"];
		$columns = "usuarios.id_usuario, productos.id_producto, productos.nombre AS producto, cotizaciones.precio AS precio, cotizaciones.observaciones";
		$joins = [
			["table"	=>	"usuarios",			"ON"	=>	"cotizaciones.id_proveedor = usuarios.id_usuario",	"clausula"	=>	"INNER"],
			["table"	=>	"productos",		"ON"	=>	"cotizaciones.id_producto = productos.id_producto",	"clausula"	=>	"INNER"]
		];
		$where = [
				["clausula"	=>	"WEEKOFYEAR(cotizaciones.fecha_registro)",	"valor"	=>	$this->weekNumber()],
				["clausula"	=>	"cotizaciones.id_proveedor",	"valor"	=>	$id],
				["clausula"	=>	"cotizaciones.estatus",	"valor"	=>	1]
		];
		$order="productos.nombre";
		$group ="productos.id_producto";
		$cotizacionesProveedor = $this->ct_mdl->get_pagination($columns, $joins, $where, $search, $group, $order);

		$data =[];
		$no = $_POST["start"];
		if ($cotizacionesProveedor){
			foreach ($cotizacionesProveedor as $key => $value) {
				$no ++;
				$row = [];
				$row[] = $value->producto;
				$row[] = "<div class='input-group m-b'>
						 <span class='input-group-addon'><i class='fa fa-dollar'></i></span>
						 <input type='text' value='$ ".number_format($value->precio,2,'.',',')."' class='form-control precio numeric' readonly=''>
						 </div>";
				$row[] = $value->observaciones;
				$row[] = "<div class='input-group m-b'>
						 <span class='input-group-addon'><i class='fa fa-slack'></i></span>
						 <input type='number' min='0' value='' class='form-control cantidad'> 
						 </div>";
				$row[] = '<td><button type="button" id="add_me" class="btn btn-info" data-toggle="tooltip" title="Agregar" data-id-cotizacion="'.$value->id_producto.'">
						<i class="fa fa-plus"></i></button></td>';
				$data[] = $row;
			}
		}	
		$salida = [
			"query"				=>	$this->db->last_query(),
			"draw"				=>	$_POST['draw'],
			"recordsTotal"		=>	$this->ct_mdl->count_filtered("cotizaciones.id_producto", $where, $search, $joins),
			"recordsFiltered"	=>	$this->ct_mdl->count_filtered("cotizaciones.id_producto", $where, $search, $joins),
			"data" => $data];
		$this->jsonResponse($salida);
	}

	public function cotizaciones_dataTable(){
		ini_set("memory_limit", "-1");
			$search = ["fam.nombre", "prod.codigo", "prod.nombre", "ctz_first.nombre", "ctz_first.observaciones", "proveedor_first.nombre", "proveedor_first.apellido",
				"proveedor_next.nombre", "proveedor_next.apellido","ctz_first.precio","ctz_next.precio"];
			$columns = "ctz_first.estatus,cotizaciones.id_cotizacion, cotizaciones.fecha_registro, ctz_first.precio_sistema, ctz_first.precio_four,
			fam.id_familia, fam.nombre AS familia,
			prod.codigo, prod.nombre AS producto,
			UPPER(CONCAT(proveedor_first.nombre,' ',proveedor_first.apellido)) AS proveedor_first,
			ctz_first.precio AS precio_firsto,
			IF((ctz_first.precio_promocion >0), ctz_first.precio_promocion, ctz_first.precio) AS precio_first,
			ctz_first.nombre AS promocion_first,
			ctz_first.observaciones AS observaciones_first,
			UPPER(CONCAT(proveedor_next.nombre,' ',proveedor_next.apellido)) AS proveedor_next,
			ctz_next.precio AS precio_nexto,
			IF((ctz_next.precio_promocion >0), ctz_next.precio_promocion, ctz_next.precio) AS precio_next,
			ctz_maxima.precio AS precio_maximo,
			ctz_next.observaciones AS observaciones_next,
			AVG(cotizaciones.precio) AS precio_promedio";
			$joins = [
				["table"	=>	"productos prod",			"ON"	=>	"cotizaciones.id_producto = prod.id_producto",	"clausula"	=>	"LEFT"],
				["table"	=>	"familias fam",				"ON"	=>	"prod.id_familia = fam.id_familia",				"clausula"	=>	"INNER"],
				["table"	=>	"cotizaciones ctz_first",	"ON"	=>	"ctz_first.id_cotizacion = (SELECT  ctz_min.id_cotizacion FROM cotizaciones ctz_min WHERE cotizaciones.id_producto = ctz_min.id_producto 
					 AND WEEKOFYEAR(ctz_min.fecha_registro) = ".$this->weekNumber()." AND ctz_min.precio_promocion = (SELECT MIN(ctz_min_precio.precio_promocion) FROM cotizaciones ctz_min_precio WHERE ctz_min_precio.id_producto = ctz_min.id_producto AND ctz_min_precio.estatus = 1 AND WEEKOFYEAR(ctz_min_precio.fecha_registro) = ".$this->weekNumber().") LIMIT 1)",	"clausula"				=>	"LEFT"],
				["table"	=>	"cotizaciones ctz_maxima",	"ON"	=>	"ctz_maxima.id_cotizacion = (SELECT ctz_max.id_cotizacion FROM cotizaciones ctz_max WHERE cotizaciones.id_producto = ctz_max.id_producto
					 AND ctz_max.precio = (SELECT MAX(ctz_max_precio.precio) FROM cotizaciones ctz_max_precio WHERE ctz_max_precio.id_producto = ctz_max.id_producto AND WEEKOFYEAR(ctz_max_precio.fecha_registro) = ".$this->weekNumber().") LIMIT 1)",	"clausula"			=>	"INNER"],
				["table"	=>	"cotizaciones ctz_next",	"ON"	=>	"ctz_next.id_cotizacion = (SELECT cotizaciones.id_cotizacion FROM cotizaciones WHERE cotizaciones.id_producto = ctz_first.id_producto
					AND cotizaciones.estatus = 1 AND cotizaciones.precio_promocion >= ctz_first.precio_promocion AND WEEKOFYEAR(cotizaciones.fecha_registro) = ".$this->weekNumber()." AND cotizaciones.id_proveedor <> ctz_first.id_proveedor ORDER BY cotizaciones.precio_promocion ASC LIMIT 1)",	"clausula"						=>	"LEFT"],
				["table"	=>	"usuarios proveedor_first",	"ON"	=>	"ctz_first.id_proveedor = proveedor_first.id_usuario",	"clausula"	=>	"INNER"],
				["table"	=>	"usuarios proveedor_next",	"ON"	=>	"ctz_next.id_proveedor = proveedor_next.id_usuario",	"clausula"	=>	"LEFT"],
			];
		$where = [
				["clausula"	=>	"ctz_first.estatus",	"valor"	=>	1]
			];
			$order="prod.id_producto";
			$group ="cotizaciones.id_producto";

		$cotizacionesProveedor = $this->ct_mdl->get_pagination($columns, $joins, $where, $search, $group, $order);

		$data =[];
		$no = $_POST["start"];
				foreach ($cotizacionesProveedor as $key => $value) {
					$no ++;
					$row = [];
					$row[] = $value->familia;
					$row[] = $value->codigo;
					$row[] = $value->producto;
					$row[] = ($value->precio_sistema > 0) ? '$ '.number_format($value->precio_sistema,2,'.',',') : '';
					$row[] = ($value->precio_four > 0) ? '$ '.number_format($value->precio_four,2,'.',',') : '';
					$row[] = '$ '.number_format($value->precio_firsto,2,'.',',');
					if($value->precio_first <= $value->precio_four){
						$row[] = ($value->precio_first > 0) ? '<div class="preciomenos">$ '.number_format($value->precio_first,2,'.',',').'</div>' : '';
					}else{
						$row[] = ($value->precio_first > 0) ? '<div class="preciomas">$ '.number_format($value->precio_first,2,'.',',').'</div>' : '';
					}
					$row[] = $value->proveedor_first;
					$row[] = $value->observaciones_first;
					$row[] = '$ '.number_format($value->precio_maximo,2,'.',',');
					$row[] = '$ '.number_format($value->precio_promedio,2,'.',',');
					$row[] = ($value->precio_nexto > 0) ? '$ '.number_format($value->precio_nexto,2,'.',',') : '';
					if($value->precio_next <= $value->precio_four){
						$row[] = ($value->precio_next > 0) ? '<div class="preciomenos">$ '.number_format($value->precio_next,2,'.',',').'</div>' : '';
					}else{
						$row[] = ($value->precio_next > 0) ? '<div class="preciomas">$ '.number_format($value->precio_next,2,'.',',').'</div>' : '';
					}
					$row[] = $value->proveedor_next;
					$row[] = $value->observaciones_next;
					$row[] = $this->column_buttons($value->id_cotizacion, "All");
					$data[] = $row;
				}
		$salida = [
			"query"				=>	$this->db->last_query(),
			"draw"				=>	$_POST['draw'],
			"recordsTotal"		=>	$this->ct_mdl->count_filtered("cotizaciones.id_producto", $where, $search, $joins),
			"recordsFiltered"	=>	$this->ct_mdl->count_filtered("cotizaciones.id_producto", $where, $search, $joins),
			"data" => $data];
		$this->jsonResponse($salida);
	}

	private function column_buttons($id_cotizacion,$param1){
		$botones = "";
		$botones.='<button id="update_cotizacion" class="btn btn-info" data-toggle="tooltip" title="Editar" data-id-cotizacion="'.$id_cotizacion.'">
						<i class="fa fa-pencil"></i>
					</button>';
		$botones.='&nbsp;<button id="delete_cotizacion" class="btn btn-warning" data-toggle="tooltip" title="Eliminar" data-id-cotizacion="'.$id_cotizacion.'">
							<i class="fa fa-trash"></i>
						</button>';
		
		
		return $botones;
	}

	public function fill_formato1($proves, $id_proves){
		ini_set("memory_limit", "-1");
		$this->load->library("excelfile");
		
		$default_border = array(
		    'style' => PHPExcel_Style_Border::BORDER_THIN,
		    'color' => array('rgb'=>'000000')
		);
		$style_header = array(
		    'fill' => array(
		        'type' => PHPExcel_Style_Fill::FILL_SOLID,
		        'color' => array('rgb'=>'000000'),
		    ),
		    'font' => array(
		        'bold' => true,
		        'color' => array('rgb' => 'FFFFFF')
		    ),
		    'borders' => array(
		        'bottom' => $default_border,
		        'left' => $default_border,
		        'top' => $default_border,
		        'right' => $default_border,
		    ),
		);

		$style_menos = array(
		    'fill' => array(
		        'type' => PHPExcel_Style_Fill::FILL_SOLID,
		        'color' => array('rgb'=>'FDB2B2'),
		    ),
		    'font' => array(
		        'bold' => true,
		        'color' => array('rgb' => 'E21111')
		    ),
		    'borders' => array(
		        'bottom' => $default_border,
		        'left' => $default_border,
		        'top' => $default_border,
		        'right' => $default_border,
		    ),
		);

		$style_mas = array(
		    'fill' => array(
		        'type' => PHPExcel_Style_Fill::FILL_SOLID,
		        'color' => array('rgb'=>'96EAA8'),
		    ),
		    'font' => array(
		        'bold' => true,
		        'color' => array('rgb' => '0C800C')
		    ),
		    'borders' => array(
		        'bottom' => $default_border,
		        'left' => $default_border,
		        'top' => $default_border,
		        'right' => $default_border,
		    ),
		);

		$style_blue = array(
		    'fill' => array(
		        'type' => PHPExcel_Style_Fill::FILL_SOLID,
		        'color' => array('rgb'=>'80b9f5'),
		    ),
		    'font' => array(
		        'bold' => true,
		        'color' => array('rgb' => '000000')
		    ),
		    'borders' => array(
		        'bottom' => $default_border,
		        'left' => $default_border,
		        'top' => $default_border,
		        'right' => $default_border,
		    ),
		);
		$style_them = array(
		    'borders' => array(
		        'bottom' => $default_border,
		        'left' => $default_border,
		        'top' => $default_border,
		        'right' => $default_border,
		    ),
		);
		

		
		$inputFileName = 'assets/uploads/formproveedor.xlsx';
		$inputFileType = PHPExcel_IOFactory::identify($inputFileName);
		$objReader = PHPExcel_IOFactory::createReader($inputFileType);
		$objPHPExcel = $objReader->load($inputFileName);

		$objPHPExcel->setActiveSheetIndex(0);
		$hoja = $objPHPExcel->getActiveSheet();

		$hoja->setCellValue("A2", 'PEDIDO A "'.$proves.'" '.date("d-m-Y"));
		$where=["ctz_first.id_proveedor" => $id_proves];
		$fecha = date('Y-m-d');
		$row_print =4;
		$cotizacionesProveedor = $this->ct_mdl->comparaCotizaciones($where, $fecha);
		if ($cotizacionesProveedor){
			foreach ($cotizacionesProveedor as $key => $value){
				$hoja->getStyle("A{$row_print}:E{$row_print}")->applyFromArray( $style_header );
				$hoja->setCellValue("E{$row_print}", $value['familia'])->getStyle("E{$row_print}")->getAlignment()->setWrapText(true);
				$row_print +=1;
				if ($value['articulos']) {
					foreach ($value['articulos'] as $key => $row){
						$this->cellStyle("D{$row_print}", "FFFFFF", "000000", TRUE, 12, "Franklin Gothic Book");
						$this->cellStyle("D{$row_print}:E{$row_print}", "FFFFFF", "000000", FALSE, 12, "Franklin Gothic Book");
						$hoja->setCellValue("D{$row_print}", $row['codigo'])->getStyle("D{$row_print}")->getNumberFormat()->setFormatCode('# ???/???');
						$hoja->setCellValue("E{$row_print}", $row['producto'])->getStyle("E{$row_print}");
						$row_print ++;
					}
				}
			}
		}
		for($i = 5; $i < $row_print; $i++){
			$hoja->getStyle("A{$i}:A{$i}")->applyFromArray( $style_them );
			$hoja->getStyle("B{$i}:B{$i}")->applyFromArray( $style_them );
			$hoja->getStyle("C{$i}:C{$i}")->applyFromArray( $style_them );
			$hoja->getStyle("D{$i}:D{$i}")->applyFromArray( $style_them );
			$hoja->getStyle("E{$i}:E{$i}")->applyFromArray( $style_them );
		}
		$hoja->getStyle("A5:E{$row_print}")->applyFromArray( $style_them );


		$objPHPExcel->setActiveSheetIndex(1);

		$hoja = $objPHPExcel->getActiveSheet();
		$hoja->setCellValue("B2", 'PEDIDO A "'.$proves.'" '.date("d-m-Y"));
		$row_print =4;
		
		if ($cotizacionesProveedor){
			foreach ($cotizacionesProveedor as $key => $value){
				$hoja->getStyle("B{$row_print}:B{$row_print}")->applyFromArray( $style_header );
				$hoja->setCellValue("B{$row_print}", $value['familia'])->getStyle("B{$row_print}")->getAlignment()->setWrapText(true);
				$row_print +=1;
				if ($value['articulos']) {
					foreach ($value['articulos'] as $key => $row){
						$this->cellStyle("D{$row_print}", "FFFFFF", "000000", TRUE, 12, "Franklin Gothic Book");
						$this->cellStyle("D{$row_print}:E{$row_print}", "FFFFFF", "000000", FALSE, 12, "Franklin Gothic Book");
						$hoja->setCellValue("A{$row_print}", $row['codigo'])->getStyle("A{$row_print}")->getNumberFormat()->setFormatCode('# ???/???');
						$hoja->setCellValue("B{$row_print}", $row['producto'])->getStyle("B{$row_print}");
						if($row['precio_sistema'] < $row['precio_first']){
							$hoja->getStyle("C{$row_print}:C{$row_print}")->applyFromArray( $style_menos );
							$hoja->setCellValue("C{$row_print}", $row['precio_first'])->getStyle("C{$row_print}")->getNumberFormat()->setFormatCode('"$"#,##0.00_-');
						}else{
							$hoja->getStyle("C{$row_print}:C{$row_print}")->applyFromArray( $style_mas );
							$hoja->setCellValue("C{$row_print}", $row['precio_first'])->getStyle("C{$row_print}")->getNumberFormat()->setFormatCode('"$"#,##0.00_-');
						}
						$hoja->setCellValue("D{$row_print}", $row['precio_sistema'])->getStyle("D{$row_print}")->getNumberFormat()->setFormatCode('"$"#,##0.00_-');//Formto de moneda
						$hoja->setCellValue("E{$row_print}", $row['precio_four'])->getStyle("E{$row_print}")->getNumberFormat()->setFormatCode('"$"#,##0.00_-');
						if($row['precio_sistema'] < $row['precio_next']){
							$hoja->getStyle("F{$row_print}:F{$row_print}")->applyFromArray( $style_menos );
							$hoja->setCellValue("F{$row_print}", $row['precio_next'])->getStyle("F{$row_print}")->getNumberFormat()->setFormatCode('"$"#,##0.00_-');
						}else if($row['precio_next'] !== NULL){
							$hoja->getStyle("F{$row_print}:F{$row_print}")->applyFromArray( $style_mas );
							$hoja->setCellValue("F{$row_print}", $row['precio_next'])->getStyle("F{$row_print}")->getNumberFormat()->setFormatCode('"$"#,##0.00_-');
						}else{
							$hoja->setCellValue("F{$row_print}", $row['precio_next'])->getStyle("F{$row_print}")->getNumberFormat()->setFormatCode('"$"#,##0.00_-');
							$this->cellStyle("F{$row_print}", "FFFFFF", "000000", FALSE, 12, "Franklin Gothic Book");
						}
						$hoja->setCellValue("G{$row_print}", $row['proveedor_next'])->getStyle("G{$row_print}");
						$hoja->setCellValue("AC{$row_print}", $row['promocion_first'])->getStyle("AC{$row_print}");
						$hoja->getStyle("J{$row_print}:J{$row_print}")->applyFromArray( $style_blue );
						$hoja->getStyle("M{$row_print}:M{$row_print}")->applyFromArray( $style_blue );
						$hoja->getStyle("P{$row_print}:P{$row_print}")->applyFromArray( $style_blue );
						$hoja->getStyle("S{$row_print}:S{$row_print}")->applyFromArray( $style_blue );
						$hoja->getStyle("V{$row_print}:V{$row_print}")->applyFromArray( $style_blue );
						$hoja->getStyle("Y{$row_print}:Y{$row_print}")->applyFromArray( $style_blue );

												
						$hoja->setCellValue("AD{$row_print}", "=J{$row_print}*C{$row_print}")->getStyle("AD{$row_print}")->getNumberFormat()->setFormatCode('"$"#,##0.00_-');						
						$hoja->setCellValue("AE{$row_print}", "=M{$row_print}*C{$row_print}")->getStyle("AE{$row_print}")->getNumberFormat()->setFormatCode('"$"#,##0.00_-');
						$hoja->setCellValue("AF{$row_print}", "=P{$row_print}*C{$row_print}")->getStyle("AF{$row_print}")->getNumberFormat()->setFormatCode('"$"#,##0.00_-');
						$hoja->setCellValue("AG{$row_print}", "=S{$row_print}*C{$row_print}")->getStyle("AG{$row_print}")->getNumberFormat()->setFormatCode('"$"#,##0.00_-');
						$hoja->setCellValue("AH{$row_print}", "=V{$row_print}*C{$row_print}")->getStyle("AH{$row_print}")->getNumberFormat()->setFormatCode('"$"#,##0.00_-');
						$hoja->setCellValue("AI{$row_print}", "=Y{$row_print}*C{$row_print}")->getStyle("AI{$row_print}")->getNumberFormat()->setFormatCode('"$"#,##0.00_-');
						$hoja->setCellValue("AJ{$row_print}", "=C{$row_print}*AB{$row_print}")->getStyle("AJ{$row_print}")->getNumberFormat()->setFormatCode('"$"#,##0.00_-');


						$hoja->getStyle("AB{$row_print}:AB{$row_print}")->applyFromArray( $style_blue );

						$row_print ++;
					}
					
				}
				

			}
			$row_print4 =  $row_print - 1;
			$hoja->setCellValue("AD{$row_print}", "=SUM(AE5:AE{$row_print4})")->getStyle("AD{$row_print}")->getNumberFormat()->setFormatCode('"$"#,##0.00_-');
			$hoja->setCellValue("AE{$row_print}", "=SUM(AE5:AE{$row_print4})")->getStyle("AE{$row_print}")->getNumberFormat()->setFormatCode('"$"#,##0.00_-');
			$hoja->setCellValue("AF{$row_print}", "=SUM(AF5:AF{$row_print4})")->getStyle("AF{$row_print}")->getNumberFormat()->setFormatCode('"$"#,##0.00_-');
			$hoja->setCellValue("AG{$row_print}", "=SUM(AG5:AG{$row_print4})")->getStyle("AG{$row_print}")->getNumberFormat()->setFormatCode('"$"#,##0.00_-');
			$hoja->setCellValue("AH{$row_print}", "=SUM(AH5:AH{$row_print4})")->getStyle("AH{$row_print}")->getNumberFormat()->setFormatCode('"$"#,##0.00_-');
			$hoja->setCellValue("AI{$row_print}", "=SUM(AI5:AI{$row_print4})")->getStyle("AI{$row_print}")->getNumberFormat()->setFormatCode('"$"#,##0.00_-');
			$hoja->setCellValue("AJ{$row_print}", "=SUM(AJ5:AJ{$row_print4})")->getStyle("AJ{$row_print}")->getNumberFormat()->setFormatCode('"$"#,##0.00_-');

			$row_print2 = $row_print + 8;

			$hoja->setCellValue("B{$row_print2}", "ABARROTES")->getStyle("B{$row_print2}");
			$hoja->setCellValue("C{$row_print2}", "=AD{$row_print}")->getStyle("C{$row_print2}")->getNumberFormat()->setFormatCode('"$"#,##0.00_-');
			$row_print2 += 1;
			$hoja->setCellValue("B{$row_print2}", "TIENDA")->getStyle("B{$row_print2}");
			$hoja->setCellValue("C{$row_print2}", "=AE{$row_print}")->getStyle("C{$row_print2}")->getNumberFormat()->setFormatCode('"$"#,##0.00_-');
			$row_print2 += 1;
			$hoja->setCellValue("B{$row_print2}", "ULTRAMARINOS")->getStyle("B{$row_print2}");
			$hoja->setCellValue("C{$row_print2}", "=AF{$row_print}")->getStyle("C{$row_print2}")->getNumberFormat()->setFormatCode('"$"#,##0.00_-');
			$row_print2 += 1;
			$hoja->setCellValue("B{$row_print2}", "TRINCHERAS")->getStyle("B{$row_print2}");
			$hoja->setCellValue("C{$row_print2}", "=AG{$row_print}")->getStyle("C{$row_print2}")->getNumberFormat()->setFormatCode('"$"#,##0.00_-');
			$row_print2 += 1;
			$hoja->setCellValue("B{$row_print2}", "MERCADO")->getStyle("B{$row_print2}");
			$hoja->setCellValue("C{$row_print2}", "=AH{$row_print}")->getStyle("C{$row_print2}")->getNumberFormat()->setFormatCode('"$"#,##0.00_-');
			$row_print2 += 1;
			$hoja->setCellValue("B{$row_print2}", "TENENCIA")->getStyle("B{$row_print2}");
			$hoja->setCellValue("C{$row_print2}", "=AI{$row_print}")->getStyle("C{$row_print2}")->getNumberFormat()->setFormatCode('"$"#,##0.00_-');
			$row_print2 += 1;
			$hoja->setCellValue("B{$row_print2}", "TIJERAS")->getStyle("B{$row_print2}");
			$hoja->setCellValue("C{$row_print2}", "=AJ{$row_print}")->getStyle("C{$row_print2}")->getNumberFormat()->setFormatCode('"$"#,##0.00_-');

		}
		
		for($i = 5; $i < $row_print; $i++){
			$hoja->getStyle("A{$i}:A{$i}")->applyFromArray( $style_them );
			$hoja->getStyle("B{$i}:B{$i}")->applyFromArray( $style_them );
			$hoja->getStyle("C{$i}:C{$i}")->applyFromArray( $style_them );
			$hoja->getStyle("D{$i}:D{$i}")->applyFromArray( $style_them );
			$hoja->getStyle("E{$i}:E{$i}")->applyFromArray( $style_them );
			$hoja->getStyle("F{$i}:F{$i}")->applyFromArray( $style_them );
			$hoja->getStyle("G{$i}:G{$i}")->applyFromArray( $style_them );
			$hoja->getStyle("H{$i}:H{$i}")->applyFromArray( $style_them );
			$hoja->getStyle("I{$i}:I{$i}")->applyFromArray( $style_them );
			$hoja->getStyle("J{$i}:J{$i}")->applyFromArray( $style_them );
			$hoja->getStyle("K{$i}:K{$i}")->applyFromArray( $style_them );
			$hoja->getStyle("L{$i}:L{$i}")->applyFromArray( $style_them );
			$hoja->getStyle("M{$i}:M{$i}")->applyFromArray( $style_them );
			$hoja->getStyle("N{$i}:N{$i}")->applyFromArray( $style_them );
			$hoja->getStyle("O{$i}:O{$i}")->applyFromArray( $style_them );
			$hoja->getStyle("P{$i}:P{$i}")->applyFromArray( $style_them );
			$hoja->getStyle("Q{$i}:Q{$i}")->applyFromArray( $style_them );
			$hoja->getStyle("R{$i}:R{$i}")->applyFromArray( $style_them );
			$hoja->getStyle("S{$i}:S{$i}")->applyFromArray( $style_them );
			$hoja->getStyle("T{$i}:T{$i}")->applyFromArray( $style_them );
			$hoja->getStyle("U{$i}:U{$i}")->applyFromArray( $style_them );
			$hoja->getStyle("V{$i}:V{$i}")->applyFromArray( $style_them );
			$hoja->getStyle("W{$i}:W{$i}")->applyFromArray( $style_them );
			$hoja->getStyle("X{$i}:X{$i}")->applyFromArray( $style_them );
			$hoja->getStyle("Y{$i}:Y{$i}")->applyFromArray( $style_them );
			$hoja->getStyle("Z{$i}:Z{$i}")->applyFromArray( $style_them );
			$hoja->getStyle("AA{$i}:AA{$i}")->applyFromArray( $style_them );
			$hoja->getStyle("AB{$i}:AB{$i}")->applyFromArray( $style_them );
			$hoja->getStyle("AC{$i}:AC{$i}")->applyFromArray( $style_them );
			$hoja->getStyle("AD{$i}:AD{$i}")->applyFromArray( $style_them );
			$hoja->getStyle("AE{$i}:AE{$i}")->applyFromArray( $style_them );
			$hoja->getStyle("AF{$i}:AF{$i}")->applyFromArray( $style_them );
			$hoja->getStyle("AG{$i}:AG{$i}")->applyFromArray( $style_them );
			$hoja->getStyle("AH{$i}:AH{$i}")->applyFromArray( $style_them );
			$hoja->getStyle("AI{$i}:AI{$i}")->applyFromArray( $style_them );
			$hoja->getStyle("AJ{$i}:AJ{$i}")->applyFromArray( $style_them );
		}
		$hoja->getStyle("A1:AJ{$row_print}")->applyFromArray( $style_them );
		$file_name = "FORMS ".$proves.".xls"; //Nombre del documento con extención
		header("Content-Type: application/vnd.ms-excel; charset=utf-8");
		header("Content-Disposition: attachment;filename=".$file_name);
		header("Cache-Control: max-age=0");
		$excel_Writer = PHPExcel_IOFactory::createWriter($objPHPExcel, "Excel5");
		$excel_Writer->save("php://output");
		
	}

}

/* End of file Cotizaciones.php */
/* Location: ./application/controllers/Cotizaciones.php */