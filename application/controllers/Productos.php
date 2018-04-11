<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Productos extends MY_Controller {

	function __construct(){
		parent::__construct();
		$this->load->model("Productos_model", "pro_md");
		$this->load->model("Familias_model", "fam_md");
		$this->load->model("Cambios_model", "cambio_md");
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

		$columns = "productos.id_producto, productos.nombre AS producto, productos.codigo, fam.nombre AS familia";

		$joins = [
			["table"	=>	"familias fam",	"ON"	=>	"productos.id_familia = fam.id_familia",	"clausula"	=>	"INNER"]
		];

		$group ="productos.id_producto";
		$order="productos.id_producto";

		$where = NULL;

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
		$user = $this->session->userdata();
		$producto = ['codigo'	=>	$this->input->post('codigo'),
					'nombre'	=>	strtoupper($this->input->post('nombre')),
					// 'precio'	=>	$this->input->post('precio'),
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
				$data ['id_producto'] = $this->pro_md->update(["estatus" => 0], $this->input->post('id_producto'));
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

		$this->cellStyle("A1:N2", "000000", "FFFFFF", TRUE, 12, "Franklin Gothic Book");
		$hoja->setCellValue("A1", "CÓDIGO")->getColumnDimension('A')->setWidth(30); //Nombre y ajuste de texto a la columna
		
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

}

/* End of file Productos.php */
/* Location: ./application/controllers/Productos.php */