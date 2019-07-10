<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Lunes extends MY_Controller {

	function __construct(){
		parent::__construct();
		$this->load->model("Usuarios_model", "user_md");
		$this->load->model("Cambios_model", "cambio_md");
		$this->load->model("Prove_model", "prove_md");
		$this->load->model("Prolunes_model", "prolu_md");
		$this->load->model("Suclunes_model", "suc_md");
		$this->load->model("Exislunes_model", "ex_lun_md");
	}

	public function index(){
		$data['links'] = [
			'/assets/css/plugins/dataTables/dataTables.bootstrap',
			'/assets/css/plugins/dataTables/dataTables.responsive',
			'/assets/css/plugins/dataTables/dataTables.tableTools.min',
			'/assets/css/plugins/dataTables/buttons.dataTables.min',
		];

		$data['scripts'] = [
			'/scripts/provel',
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
		$this->estructura("Lunes/table_proveedores", $data);
	}

	public function proveedores(){
		$data['links'] = [
			'/assets/css/plugins/dataTables/dataTables.bootstrap',
			'/assets/css/plugins/dataTables/dataTables.responsive',
			'/assets/css/plugins/dataTables/dataTables.tableTools.min',
			'/assets/css/plugins/dataTables/buttons.dataTables.min',
		];

		$data['scripts'] = [
			'/scripts/provel',
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

		$data["proveedores"] = $this->prove_md->getProveedores();
		$this->estructura("Lunes/table_proveedores", $data);
	}

	public function new_proveedor(){
		$data["title"]="REGISTRAR PROVEEDOR";
		$user = $this->session->userdata();
		$data["view"] = $this->load->view("Lunes/new_proveedor", $data, TRUE);
		$data["button"]="<button class='btn btn-success new_proveedor' type='button'>
							<span class='bold'><i class='fa fa-floppy-o'></i></span> &nbsp;Guardar
						</button>";
		$this->jsonResponse($data);
	}

	public function save_prove(){
		$proveedor = [
			"nombre"	=>	strtoupper($this->input->post('nombre')),
			"alias"	=>	strtoupper($this->input->post('apellido')),
		];
		$getUsuario = $this->prove_md->get(NULL, ['nombre'=>$proveedor['nombre']])[0];

		if(sizeof($getUsuario) == 0){
			$data ['id_proveedor'] = $this->prove_md->insert($proveedor);
			$mensaje = ["id" 	=> 'Éxito',
						"desc"	=> 'Proveedor registrado correctamente',
						"type"	=> 'success'];
			$user = $this->session->userdata();
			$cambios = [
				"id_usuario" => $user["id_usuario"],
				"fecha_cambio" => date('Y-m-d H:i:s'),
				"antes" => "Proveedor Lunes es nuevo",
				"despues" => "Nombre : ".$proveedor['nombre']." /Alias: ".$proveedor['alias']];
			$data['cambios'] = $this->cambio_md->insert($cambios);
		}else{
			$mensaje = [
				"id" 	=> 'Alerta',
				"desc"	=> 'El Proveedor ['.$proveedor['nombre'].'] está registrado en el Sistema',
				"type"	=> 'warning'
			];
		}
		$this->jsonResponse($mensaje);
	}

	public function prove_update($id){
		$data["title"]="ACTUALIZAR DATOS DEL PROVEEDOR";
		$data["proveedor"] = $this->prove_md->get(NULL, ['id_proveedor'=>$id])[0];
		$user = $this->session->userdata();
		$data["view"] =$this->load->view("Lunes/edit_proveedor", $data, TRUE);
		$data["button"]="<button class='btn btn-success update_proveedor' type='button'>
							<span class='bold'><i class='fa fa-floppy-o'></i></span> &nbsp;Guardar cambios
						</button>";
		$this->jsonResponse($data);
	}

	public function update_prove(){
		$user = $this->session->userdata();
		$antes = $this->prove_md->get(NULL, ['id_proveedor'=>$this->input->post('id_proveedor')])[0];

		$proveedor = [
			"nombre"	=>	strtoupper($this->input->post('nombre')),
			"alias"	=>	strtoupper($this->input->post('apellido')),
		];

		$data ['id_proveedor'] = $this->prove_md->update($proveedor, $this->input->post('id_proveedor'));
		$cambios = [
				"id_usuario" => $user["id_usuario"],
				"fecha_cambio" => date('Y-m-d H:i:s'),
				"antes" => "Nombre : ".$antes->nombre." /Alias: ".$antes->alias,
				"despues" => "Nombre : ".$proveedor['nombre']." /Alias: ".$proveedor['alias']];
		$data['cambios'] = $this->cambio_md->insert($cambios);
		$mensaje = ["id" 	=> 'Éxito',
					"desc"	=> 'Proveedor actualizado correctamente',
					"type"	=> 'success'];
		$this->jsonResponse($mensaje);
	}

	public function prove_delete($id){
		$data["title"]="PROVEEDOR A ELIMINAR";
		$data["proveedor"] = $this->prove_md->get(NULL, ['id_proveedor'=>$id])[0];
		$data["view"] = $this->load->view("Lunes/delete_proveedor", $data,TRUE);
		$data["button"]="<button class='btn btn-danger delete_proveedor' type='button'>
							<span class='bold'><i class='fa fa-times'></i></span> &nbsp;Estoy segura(o) de eliminar
						</button>";
		$this->jsonResponse($data);
	}

	public function delete_prove(){
		$user = $this->session->userdata();
		$antes = $this->prove_md->get(NULL, ['id_proveedor'=>$this->input->post('id_proveedor')])[0];
		$cambios = [
				"id_usuario" => $user["id_usuario"],
				"fecha_cambio" => date('Y-m-d H:i:s'),
				"antes" => "Nombre : ".$antes->nombre." /Alias: ".$antes->alias,
				"despues" => "El Proveedor fue eliminado, se puede recuperar desde la BD"];
		$data['cambios'] = $this->cambio_md->insert($cambios);
		$data ['id_usuario'] = $this->prove_md->update(["estatus" => 0], $this->input->post('id_proveedor'));
		$mensaje = ["id" 	=> 'Éxito',
					"desc"	=> 'Proveedor eliminado correctamente',
					"type"	=> 'success'];
		$this->jsonResponse($mensaje);
	}

	public function productos(){
		$data['links'] = [
			'/assets/css/plugins/dataTables/dataTables.bootstrap',
			'/assets/css/plugins/dataTables/dataTables.responsive',
			'/assets/css/plugins/dataTables/dataTables.tableTools.min',
			'/assets/css/plugins/dataTables/buttons.dataTables.min',
		];

		$data['scripts'] = [
			'/scripts/produl',
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

		$data["productos"] = $this->prolu_md->getProductos();
		$this->estructura("Lunes/table_productos", $data);
	}

	public function new_producto(){
		$data["title"]="REGISTRAR PRODUCTO";
		$user = $this->session->userdata();
		$data["proveedores"] = $this->prove_md->getProveedores();
		$data["view"] = $this->load->view("Lunes/new_producto", $data, TRUE);
		$data["button"]="<button class='btn btn-success new_producto' type='button'>
							<span class='bold'><i class='fa fa-floppy-o'></i></span> &nbsp;Guardar
						</button>";
		$this->jsonResponse($data);
	}

	public function save_prod(){
		$producto = [
			"codigo"	=>	strtoupper($this->input->post('codigo')),
			"descripcion"	=>	strtoupper($this->input->post('descripcion')),
			"precio"	=>	$this->input->post('precio'),
			"sistema"	=>	$this->input->post('sistema'),
			"id_proveedor"	=>	$this->input->post('id_proveedor'),
			"unidad"	=>	$this->input->post('unidad'),
		];
		$getProducto = $this->prolu_md->get(NULL, ['codigo'=>$producto['codigo']])[0];

		if(sizeof($getProducto) == 0){
			$data['codigo'] = $this->prolu_md->insert($producto);
			$mensaje = ["id" 	=> 'Éxito',
						"desc"	=> 'Producto registrado correctamente',
						"type"	=> 'success'];
			$user = $this->session->userdata();
			$cambios = [
				"id_usuario" => $user["id_usuario"],
				"fecha_cambio" => date('Y-m-d H:i:s'),
				"antes" => "Producto Lunes es nuevo",
				"despues" => "Código : ".$producto['codigo']." /Descripción: ".$producto['descripcion']];
			$data['cambios'] = $this->cambio_md->insert($cambios);
		}else{
			$mensaje = [
				"id" 	=> 'Alerta',
				"desc"	=> 'El Producto ['.$producto['nombre'].'] está registrado en el Sistema',
				"type"	=> 'warning'
			];
		}
		$this->jsonResponse($mensaje);
	}

	public function prod_delete($id){
		$data["title"]="PRODUCTO A ELIMINAR";
		$data["producto"] = $this->prolu_md->get(NULL, ['codigo'=>$id])[0];
		$data["proveedor"] = $this->prove_md->get(NULL, ["id_proveedor"=>$data["producto"]->id_proveedor])[0];
		$data["view"] = $this->load->view("Lunes/delete_producto", $data,TRUE);
		$data["button"]="<button class='btn btn-danger delete_proveedor' type='button'>
							<span class='bold'><i class='fa fa-times'></i></span> &nbsp;Estoy segura(o) de eliminar
						</button>";
		$this->jsonResponse($data);
	}

	public function delete_prod(){
		$user = $this->session->userdata();
		$antes = $this->prolu_md->get(NULL, ['codigo'=>$this->input->post('codigo')])[0];
		$cambios = [
				"id_usuario" => $user["id_usuario"],
				"fecha_cambio" => date('Y-m-d H:i:s'),
				"antes" => "Código : ".$antes->codigo." /Descripción: ".$antes->descripcion,
				"despues" => "El Producto fue eliminado, se puede recuperar desde la BD"];
		$data['cambios'] = $this->cambio_md->insert($cambios);
		$data ['id_usuario'] = $this->prolu_md->update(["estatus" => 0], $this->input->post('codigo'));
		$mensaje = ["id" 	=> 'Éxito',
					"desc"	=> 'Producto eliminado correctamente',
					"type"	=> 'success'];
		$this->jsonResponse($mensaje);
	}

	public function prod_update($id){
		$data["title"]="ACTUALIZAR DATOS DEL PRODUCTO";
		$data["producto"] = $this->prolu_md->get(NULL, ['codigo'=>$id])[0];
		$data["proveedores"] = $this->prove_md->getProveedores();
		$user = $this->session->userdata();
		$data["view"] =$this->load->view("Lunes/edit_producto", $data, TRUE);
		$data["button"]="<button class='btn btn-success update_producto' type='button'>
							<span class='bold'><i class='fa fa-floppy-o'></i></span> &nbsp;Guardar cambios
						</button>";
		$this->jsonResponse($data);
	}

	public function update_prod(){
		$user = $this->session->userdata();
		$antes = $this->prolu_md->get(NULL, ['codigo'=>$this->input->post('codigos')])[0];

		$producto = [
			"codigo"	=>	strtoupper($this->input->post('codigo')),
			"descripcion"	=>	strtoupper($this->input->post('descripcion')),
			"precio"	=>	$this->input->post('precio'),
			"sistema"	=>	$this->input->post('sistema'),
			"id_proveedor"	=>	$this->input->post('id_proveedor'),
			"unidad"	=>	$this->input->post('unidad'),
		];

		$data ['codigo'] = $this->prolu_md->update($producto, $this->input->post('codigos'));
		$cambios = [
				"id_usuario" => $user["id_usuario"],
				"fecha_cambio" => date('Y-m-d H:i:s'),
				"antes" => "Código : ".$antes->codigo." /Descripción: ".$antes->descripcion,
				"despues" => "Código : ".$producto['codigo']." /Descripción: ".$producto['descripcion']];
		$data['cambios'] = $this->cambio_md->insert($cambios);
		$mensaje = ["id" 	=> 'Éxito',
					"desc"	=> 'Producto actualizado correctamente',
					"type"	=> 'success'];
		$this->jsonResponse($mensaje);
	}


	/*public function upload_prods(){
		$nams = preg_replace('/\s+/', '_', "Topazo");
		$filen = "Cotizacion".$nams."".rand();
		$config['upload_path']          = './assets/uploads/cotizaciones/';
        $config['allowed_types']        = 'xlsx|xls';
        $config['max_size']             = 100;
        $config['max_width']            = 1024;
        $config['max_height']           = 768;
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
		for ($i=2; $i<=$num_rows; $i++) {
			if($sheet->getCell('A'.$i)->getValue() > 0){
				$precio=0; $sistema=0; $codigo=""; $desc=""; $unidad=0;
				$precio = str_replace("$", "", str_replace(",", "replace", $sheet->getCell('C'.$i)->getValue()));
				$sistema = str_replace("$", "", str_replace(",", "replace", $sheet->getCell('D'.$i)->getValue()));
				$codigo = htmlspecialchars($sheet->getCell('A'.$i)->getValue(), ENT_QUOTES, 'UTF-8');
				$desc = $sheet->getCell('B'.$i)->getValue();
				$unidad = $sheet->getCell('E'.$i)->getValue();
				$prove = $sheet->getCell('F'.$i)->getValue();
				$new_cotizacion=[
					"codigo"			=>	$codigo,
					"id_proveedor"		=>	$prove,//Recupera el id_usuario activo
					"precio"			=>	$precio,
					"sistema"			=>	$sistema,
					"descripcion"			=>	$desc,
					"unidad"			=>	$unidad,
					"estatus" => 1];
				$data['cotizacion']=$this->prolu_md->insert($new_cotizacion);
			}
		}
		if (!isset($new_cotizacion)) {
			$mensaje=[	"id"	=>	'Error',
						"desc"	=>	'El Archivo esta sin precios',
						"type"	=>	'error'];
		}else{
			if (sizeof($new_cotizacion) > 0) {
				
				$mensaje=[	"id"	=>	'Éxito',
							"desc"	=>	'Cotizaciones cargadas correctamente en el Sistema',
							"type"	=>	'success'];
			}else{
				$mensaje=[	"id"	=>	'Error',
							"desc"	=>	'Las Cotizaciones no se cargaron al Sistema',
							"type"	=>	'error'];
			}
		}
		$this->jsonResponse($mensaje);
	}*/

	public function exislunes(){
		$data['links'] = [
			'/assets/css/plugins/dataTables/dataTables.bootstrap',
			'/assets/css/plugins/dataTables/dataTables.responsive',
			'/assets/css/plugins/dataTables/dataTables.tableTools.min',
			'/assets/css/plugins/dataTables/buttons.dataTables.min',
		];

		$data['scripts'] = [
			'/scripts/exilu',
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
		$data["dias"] = array("DOMINGO","LUNES","MARTES","MIÉRCOLES","JUEVES","VIERNES","SÁBADO");
		$data["meses"] = array("ENERO","FEBRERO","MARZO","ABRIL","MAYO","JUNIO","JULIO","AGOSTO","SEPTIEMBRE","OCTUBRE","NOVIEMBRE","DICIEMBRE");
		$data["fecha"] =  $data["dias"][date('w')]." ".date('d')." DE ".$data["meses"][date('n')-1]. " DEL ".date('Y') ;
		$data["cuantas"] = $this->ex_lun_md->getCuantas(NULL);
		$data["noprod"] = $this->prolu_md->getCount(NULL)[0];
		$data["tiendas"] = $this->suc_md->getByOrder(NULL);
		$this->estructura("Lunes/existencias", $data);
		//$this->jsonResponse($data["noprod"]);
	}

	public function buscaProdis(){
		$busca = $this->input->post("values");
		$tiendas = $this->suc_md->getCount(NULL)[0];
		$data["prods"] = $this->prolu_md->buscaProdis(NULL,$busca,(int)$tiendas->total);
		$this->jsonResponse($data);
	}

	public function getCuantas($tienda){
		$busca = $this->input->post("values");
		$data["noprod"] = $this->prolu_md->getCount(NULL)[0];
		$data["cuantas"] = $this->ex_lun_md->getCuanto(NULL,$tienda);
		$this->jsonResponse($data);
	}

	public function upload_existencias($idesp){
		$tienda = $idesp;
		$fecha = new DateTime(date('Y-m-d H:i:s'));
		$cfile =  $this->suc_md->get(NULL, ['id_sucursal' => $idesp])[0];
		$nams = preg_replace('/\s+/', '_', $cfile->nombre);
		$filen = "Existencias".$nams."".rand();
		$config['upload_path']          = './assets/uploads/cotizaciones/';
        $config['allowed_types']        = 'xlsx|xls';
        $config['max_size']             = 100;
        $config['max_width']            = 1024;
        $config['max_height']           = 768;
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
		for ($i=2; $i<=$num_rows; $i++) {
			if(strlen($sheet->getCell('D'.$i)->getValue()) > 0){
				$productos = $this->prolu_md->get("codigo",['codigo'=> htmlspecialchars($sheet->getCell('D'.$i)->getValue(), ENT_QUOTES, 'UTF-8')])[0];
				if (sizeof($productos) > 0) {
					$caja=0; $pieza=0; $ped=0;
					$caja = $sheet->getCell('A'.$i)->getValue();
					$pieza = $sheet->getCell('B'.$i)->getValue();
					$ped = $sheet->getCell('C'.$i)->getValue();
					$codigo = htmlspecialchars($sheet->getCell('D'.$i)->getValue(), ENT_QUOTES, 'UTF-8');
					$exist =  $this->ex_lun_md->get(NULL, ['id_producto' => $codigo, 'WEEKOFYEAR(fecha_registro)' => $this->weekNumber($fecha->format('Y-m-d H:i:s')), 'id_tienda' => $idesp])[0];
					$new_existencia=[
							"id_producto"		=>	$codigo,
							"id_tienda"			=>	$idesp,
							"cajas"				=>	$caja,
							"piezas"		 	=>	$pieza,
							"pedido"			=>	$ped,
						];
					if($exist){
						$data['existencia']=$this->ex_lun_md->update($new_existencia, ['id_existencia' => $exist->id_existencia]);
					}else{
						$data['existencia']=$this->ex_lun_md->insert($new_existencia);
					}
				}
			}
		}
		if (!isset($new_existencia)) {
			$mensaje=[	"id"	=>	'Error',
						"desc"	=>	'El Archivo esta sin precios',
						"type"	=>	'error'];
		}else{
			if (sizeof($new_existencia) > 0) {
				$cambios=[
						"id_usuario"		=>	$this->session->userdata('id_usuario'),
						"fecha_cambio"		=>	date("Y-m-d H:i:s"),
						"antes"			=>	"El usuario sube archivo de existencias de ".$cfile->nombre,
						"despues"			=>	"assets/uploads/cotizaciones/".$filen.".xlsx",
						"accion"			=>	"Sube Archivo"
					];
				$data['cambios']=$this->cambio_md->insert($cambios);
				$mensaje=[	"id"	=>	'Éxito',
							"desc"	=>	'Existencias cargadas correctamente en el Sistema',
							"type"	=>	'success'];
			}else{
				$mensaje=[	"id"	=>	'Error',
							"desc"	=>	'Las Existencias no se cargaron al Sistema',
							"type"	=>	'error'];
			}
		}
		$this->jsonResponse($mensaje);
	}

	public function semapa(){
		$data['links'] = [
			'/assets/css/plugins/dataTables/dataTables.bootstrap',
			'/assets/css/plugins/dataTables/dataTables.responsive',
			'/assets/css/plugins/dataTables/dataTables.tableTools.min',
			'/assets/css/plugins/dataTables/buttons.dataTables.min',
		];

		$data['scripts'] = [
			'/scripts/semapa',
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
		$data["dias"] = array("DOMINGO","LUNES","MARTES","MIÉRCOLES","JUEVES","VIERNES","SÁBADO");
		$data["meses"] = array("ENERO","FEBRERO","MARZO","ABRIL","MAYO","JUNIO","JULIO","AGOSTO","SEPTIEMBRE","OCTUBRE","NOVIEMBRE","DICIEMBRE");
		$data["fecha"] =  $data["dias"][date('w')]." ".date('d')." DE ".$data["meses"][date('n')-1]. " DEL ".date('Y') ;
		$data["cuantas"] = $this->ex_lun_md->getCuantas(NULL);
		$data["noprod"] = $this->prolu_md->getCount(NULL)[0];
		$data["tiendas"] = $this->suc_md->getByOrder(NULL);
		$this->estructura("Lunes/semapa", $data);
		//$this->jsonResponse($data["noprod"]);
	}

	public function formlunes(){
		$data['links'] = [
			'/assets/css/plugins/dataTables/dataTables.bootstrap',
			'/assets/css/plugins/dataTables/dataTables.responsive',
			'/assets/css/plugins/dataTables/dataTables.tableTools.min',
			'/assets/css/plugins/dataTables/buttons.dataTables.min',
		];

		$data['scripts'] = [
			'/scripts/formlunes',
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
		$data["dias"] = array("DOMINGO","LUNES","MARTES","MIÉRCOLES","JUEVES","VIERNES","SÁBADO");
		$data["meses"] = array("ENERO","FEBRERO","MARZO","ABRIL","MAYO","JUNIO","JULIO","AGOSTO","SEPTIEMBRE","OCTUBRE","NOVIEMBRE","DICIEMBRE");
		$data["fecha"] =  $data["dias"][date('w')]." ".date('d')." DE ".$data["meses"][date('n')-1]. " DEL ".date('Y') ;
		$data["cuantas"] = $this->ex_lun_md->getCuantas(NULL);
		$data["noprod"] = $this->prolu_md->getCount(NULL)[0];
		$data["tiendas"] = $this->suc_md->getByOrder(NULL);
		$this->estructura("Lunes/formlunes", $data);
		//$this->jsonResponse($data["noprod"]);
	}

	public function upload_sistema(){
		$fecha = new DateTime(date('Y-m-d H:i:s'));
		$filen = "Precios Sistema";
		$config['upload_path']          = './assets/uploads/cotizaciones/';
        $config['allowed_types']        = 'xlsx|xls';
        $config['max_size']             = 100;
        $config['max_width']            = 1024;
        $config['max_height']           = 768;
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
		for ($i=1; $i<=$num_rows; $i++) {
			if(strlen($sheet->getCell('A'.$i)->getValue()) > 0){
				$productos = $this->prolu_md->get("codigo",['codigo'=> htmlspecialchars($sheet->getCell('A'.$i)->getValue(), ENT_QUOTES, 'UTF-8')])[0];
				if (sizeof($productos) > 0) {
					$sistema = $sheet->getCell('C'.$i)->getValue();
					$new_existencia=[
							"sistema"		=>	$sistema,
							"fecha_sistema"	=>	$fecha->format('Y-m-d H:i:s'),
						];
					$data['existencia']=$this->prolu_md->update($new_existencia, ['codigo' => $productos->codigo]);
				}
			}
		}
		if (!isset($new_existencia)) {
			$mensaje=[	"id"	=>	'Error',
						"desc"	=>	'El Archivo esta sin precios',
						"type"	=>	'error'];
		}else{
			if (sizeof($new_existencia) > 0) {
				$cambios=[
						"id_usuario"		=>	$this->session->userdata('id_usuario'),
						"fecha_cambio"		=>	date("Y-m-d H:i:s"),
						"antes"			=>	"El usuario sube precio sitema lunes ",
						"despues"			=>	"assets/uploads/cotizaciones/Precios Sistema.xlsx",
						"accion"			=>	"Sube Archivo"
					];
				$data['cambios']=$this->cambio_md->insert($cambios);
				$mensaje=[	"id"	=>	'Éxito',
							"desc"	=>	'Precios sistema cargadas correctamente en el Sistema',
							"type"	=>	'success'];
			}else{
				$mensaje=[	"id"	=>	'Error',
							"desc"	=>	'Precios sistema no se cargaron al Sistema',
							"type"	=>	'error'];
			}
		}
		$this->jsonResponse($mensaje);
	}


	public function upload_precios(){
		$fecha = new DateTime(date('Y-m-d H:i:s'));
		$filen = "Precios Sistema";
		$config['upload_path']          = './assets/uploads/cotizaciones/';
        $config['allowed_types']        = 'xlsx|xls';
        $config['max_size']             = 100;
        $config['max_width']            = 1024;
        $config['max_height']           = 768;
        $this->load->library('upload', $config);
        $this->upload->initialize($config);
        $this->upload->do_upload('file_cotizaciones',$filen);
		$this->load->library("excelfile");
		ini_set("memory_limit", -1);
		$file = $_FILES["file_cotizaciones"]["tmp_name"];
		$filename=$_FILES['file_cotizaciones']['name'];
		$sheet = PHPExcel_IOFactory::load($file);
		$objExcel = PHPExcel_IOFactory::load($file);
		$sheet = $objExcel->getSheet(0);
		$num_rows = $sheet->getHighestDataRow();
		for ($i=1; $i<=$num_rows; $i++) {
			if(strlen($sheet->getCell('A'.$i)->getValue()) > 0){
				$productos = $this->prolu_md->get("codigo",['codigo'=> htmlspecialchars($sheet->getCell('A'.$i)->getValue(), ENT_QUOTES, 'UTF-8')])[0];
				if (sizeof($productos) > 0) {
					$sistema = $sheet->getCell('C'.$i)->getValue();
					$new_existencia=[
							"precio" =>	$sistema
						];
					$data['existencia']=$this->prolu_md->update($new_existencia, ['codigo' => $productos->codigo]);
				}
			}
		}
		if (!isset($new_existencia)) {
			$mensaje=[	"id"	=>	'Error',
						"desc"	=>	'El Archivo esta sin precios',
						"type"	=>	'error'];
		}else{
			if (sizeof($new_existencia) > 0) {
				$cambios=[
						"id_usuario"		=>	$this->session->userdata('id_usuario'),
						"fecha_cambio"		=>	date("Y-m-d H:i:s"),
						"antes"			=>	"El usuario sube precio sitema lunes ",
						"despues"			=>	"assets/uploads/cotizaciones/Precios Sistema.xlsx",
						"accion"			=>	"Sube Archivo"
					];
				$data['cambios']=$this->cambio_md->insert($cambios);
				$mensaje=[	"id"	=>	'Éxito',
							"desc"	=>	'Precios sistema cargadas correctamente en el Sistema',
							"type"	=>	'success'];
			}else{
				$mensaje=[	"id"	=>	'Error',
							"desc"	=>	'Precios sistema no se cargaron al Sistema',
							"type"	=>	'error'];
			}
		}
		$this->jsonResponse($mensaje);
	}

	public function excel_semana(){
		ini_set("memory_limit", "-1");
		ini_set("max_execution_time", "-1");
		$this->load->library("excelfile");
		$proveedor = $this->prove_md->get(NULL);
		$last = 0;
		foreach ($proveedor as $ke => $value) {
			$last++;
			if ($ke == 0) {
				$proveedor[$ke]->estatus = $this->excelfile->setActiveSheetIndex($ke);
				$this->excelfile->setActiveSheetIndex($ke)->setTitle($value->alias);
			}else{
				$this->excelfile->createSheet();
        		$proveedor[$ke]->estatus = $this->excelfile->setActiveSheetIndex($ke)->setTitle($value->alias);
			}
		}
		$this->excelfile->createSheet();
		$anterior = $this->excelfile->setActiveSheetIndex($last)->setTitle("ANTERIOR");

		/*$this->excelfile->setActiveSheetIndex(0)->setTitle("QUIROZ");
		$this->excelfile->createSheet();
        $hoja1 = $this->excelfile->setActiveSheetIndex(1)->setTitle("AGYDSA");
        $this->excelfile->createSheet();
        $hoja2 = $this->excelfile->setActiveSheetIndex(2)->setTitle("DON VASCO");
        $this->excelfile->createSheet();
        $hoja3 = $this->excelfile->setActiveSheetIndex(3)->setTitle("TEAM");
        $this->excelfile->createSheet();
        $hoja1 = $this->excelfile->setActiveSheetIndex(4)->setTitle("FRUTIMEX");
        $this->excelfile->createSheet();
        $hoja2 = $this->excelfile->setActiveSheetIndex(5)->setTitle("PURINA");
        $this->excelfile->createSheet();
        $hoja3 = $this->excelfile->setActiveSheetIndex(6)->setTitle("JLC");
        $this->excelfile->createSheet();
        $hoja1 = $this->excelfile->setActiveSheetIndex(7)->setTitle("NAREMO");
        $this->excelfile->createSheet();
        $hoja2 = $this->excelfile->setActiveSheetIndex(8)->setTitle("SAPORIS");
        $this->excelfile->createSheet();
        $hoja3 = $this->excelfile->setActiveSheetIndex(9)->setTitle("H24");
        $this->excelfile->createSheet();
        $hoja1 = $this->excelfile->setActiveSheetIndex(10)->setTitle("DAM");
        $this->excelfile->createSheet();
        $hoja1 = $this->excelfile->setActiveSheetIndex(11)->setTitle("ANTERIOR");*/

        $flag = 1; $flag1 = 1;
		$tiendas = $this->suc_md->getCount(NULL)[0];
        
        $this->excelfile->setActiveSheetIndex(0);

		$styleArray = array(
		  'borders' => array(
		    'allborders' => array(
		      'style' => PHPExcel_Style_Border::BORDER_THIN
		    )
		  )
		);
		$styleArray2 = array(
		  'borders' => array(
		    'allborders' => array(
		      'style' => PHPExcel_Style_Border::BORDER_MEDIUM
		    )
		  )
		);
		
		$proveedor[0]->estatus = $this->excelfile->getActiveSheet();

		
		//FECHA EN FORMATO COMPLETO PARA LOS TITULOS Y TABLAS
		$dias = array("DOMINGO","LUNES","MARTES","MIÉRCOLES","JUEVES","VIERNES","SÁBADO");
		$meses = array("ENERO","FEBRERO","MARZO","ABRIL","MAYO","JUNIO","JULIO","AGOSTO","SEPTIEMBRE","OCTUBRE","NOVIEMBRE","DICIEMBRE");
		$fecha =  $dias[date('w')]." ".date('d')." DE ".$meses[date('n')-1]. " DEL ".date('Y') ;
		$day = date('w');
		$week_start = date('d', strtotime('-'.($day).' days'));
		$week_end = date('d', strtotime('+'.(6-$day).' days'));

		foreach ($proveedor as $key => $va) {
			$infos = $this->prolu_md->printProdis(NULL,$va->id_proveedor,$tiendas->total);
			if ($infos) {
				if (1 == 1) {
					$this->excelfile->setActiveSheetIndex($key);
					$proveedor[$key]->estatus = $this->excelfile->getActiveSheet();
					$flag = 1;
					$proveedor[$key]->estatus->mergeCells('A'.$flag.':BJ'.$flag);
					$this->cellStyle("A".$flag, "FFFFFF", "000000", TRUE, 12, "Franklin Gothic Book");
					$proveedor[$key]->estatus->setCellValue("A".$flag."", "CEDIS, ABARROTES,PEDREGAL, TIENDA, ULTRAMARINOS, TRINCHERAS, MERCADO, TIJERAS, Y TENENCIA AZTECA AUTOSERVICIOS SA. DE CV.");
					$this->excelfile->getActiveSheet()->getStyle('A'.$flag.':BJ'.$flag)->applyFromArray($styleArray);
					$flag++;
					$this->excelfile->getActiveSheet()->getStyle('A'.$flag.':BJ'.$flag)->applyFromArray($styleArray);
					$proveedor[$key]->estatus->getColumnDimension('A')->setWidth("25");
					$proveedor[$key]->estatus->getColumnDimension('B')->setWidth("70");
					$proveedor[$key]->estatus->getColumnDimension('F')->setWidth("15");
					$proveedor[$key]->estatus->getColumnDimension('K')->setWidth("15");
					$proveedor[$key]->estatus->getColumnDimension('P')->setWidth("15");
					$proveedor[$key]->estatus->getColumnDimension('U')->setWidth("15");
					$proveedor[$key]->estatus->getColumnDimension('Z')->setWidth("15");
					$proveedor[$key]->estatus->getColumnDimension('AE')->setWidth("15");
					$proveedor[$key]->estatus->getColumnDimension('AJ')->setWidth("15");
					$proveedor[$key]->estatus->getColumnDimension('AO')->setWidth("15");
					$proveedor[$key]->estatus->getColumnDimension('AT')->setWidth("15");
					$proveedor[$key]->estatus->getColumnDimension('AY')->setWidth("15");
					$proveedor[$key]->estatus->getColumnDimension('BD')->setWidth("15");
					$proveedor[$key]->estatus->getColumnDimension('BI')->setWidth("40");
					$proveedor[$key]->estatus->getColumnDimension('BJ')->setWidth("40");
					$proveedor[$key]->estatus->getColumnDimension('C')->setWidth("15");
					$proveedor[$key]->estatus->getColumnDimension('D')->setWidth("15");
					$proveedor[$key]->estatus->getColumnDimension('E')->setWidth("15");
					$proveedor[$key]->estatus->mergeCells('A'.$flag.':B'.$flag);
					$this->cellStyle("A".$flag, "FFFFFF", "000000", TRUE, 12, "Franklin Gothic Book");
					$proveedor[$key]->estatus->setCellValue("A".$flag."", "PEDIDO A ".$va->nombre);
					$proveedor[$key]->estatus->mergeCells('C'.$flag.':E'.$flag);
					$this->cellStyle("C".$flag, "FFFFFF", "000000", TRUE, 12, "Franklin Gothic Book");
					$proveedor[$key]->estatus->setCellValue("C".$flag."", $fecha);

					$proveedor[$key]->estatus->mergeCells('F'.$flag.':J'.$flag);
					$this->cellStyle("F".$flag, "C00000", "000000", TRUE, 12, "Franklin Gothic Book");
					$proveedor[$key]->estatus->setCellValue("F".$flag, "CEDIS/SUPER");
					$proveedor[$key]->estatus->mergeCells('K'.$flag.':O'.$flag);
					$this->cellStyle("K".$flag, "C2B90A", "000000", TRUE, 12, "Franklin Gothic Book");
					$proveedor[$key]->estatus->setCellValue("K".$flag, "SUMA CEDIS/SUPER");
					$proveedor[$key]->estatus->mergeCells('P'.$flag.':T'.$flag);
					$proveedor[$key]->estatus->setCellValue("P".$flag, "CD INDUSTRIAL");
					$this->cellStyle("P".$flag, "FF0066", "000000", TRUE, 12, "Franklin Gothic Book");
					
					$proveedor[$key]->estatus->mergeCells('U'.$flag.':Y'.$flag);
					$this->cellStyle("U".$flag, "01B0F0", "000000", TRUE, 12, "Franklin Gothic Book");
					$proveedor[$key]->estatus->setCellValue("U".$flag, "ABARROTES");
					$proveedor[$key]->estatus->mergeCells('Z'.$flag.':AD'.$flag);
					$this->cellStyle("Z".$flag, "FF0000", "000000", TRUE, 12, "Franklin Gothic Book");
					$proveedor[$key]->estatus->setCellValue("Z".$flag, "PEDREGAL");
					$proveedor[$key]->estatus->mergeCells('AE'.$flag.':AI'.$flag);
					$this->cellStyle("AE".$flag, "E26C0B", "000000", TRUE, 12, "Franklin Gothic Book");
					$proveedor[$key]->estatus->setCellValue("AE".$flag, "TIENDA");
					$proveedor[$key]->estatus->mergeCells('AJ'.$flag.':AN'.$flag);
					$this->cellStyle("AJ".$flag, "C5C5C5", "000000", TRUE, 12, "Franklin Gothic Book");
					$proveedor[$key]->estatus->setCellValue("AJ".$flag, "ULTRAMARINOS");
					$proveedor[$key]->estatus->mergeCells('AO'.$flag.':AS'.$flag);
					$this->cellStyle("AO".$flag, "92D051", "000000", TRUE, 12, "Franklin Gothic Book");
					$proveedor[$key]->estatus->setCellValue("AO".$flag, "TRINCHERAS");
					$proveedor[$key]->estatus->mergeCells('AT'.$flag.':AX'.$flag);
					$this->cellStyle("AT".$flag, "B1A0C7", "000000", TRUE, 12, "Franklin Gothic Book");
					$proveedor[$key]->estatus->setCellValue("AT".$flag, "AZT MERCADO");
					$proveedor[$key]->estatus->mergeCells('AY'.$flag.':BC'.$flag);
					$this->cellStyle("AY".$flag, "DA9694", "000000", TRUE, 12, "Franklin Gothic Book");
					$proveedor[$key]->estatus->setCellValue("AY".$flag, "TENENCIA");
					$proveedor[$key]->estatus->mergeCells('BD'.$flag.':BH'.$flag);
					$this->cellStyle("BD".$flag, "4CACC6", "000000", TRUE, 12, "Franklin Gothic Book");
					$proveedor[$key]->estatus->setCellValue("BD".$flag, "TIJERAS");
					$this->cellStyle("BI".$flag, "FFFF00", "FF0000", TRUE, 12, "Franklin Gothic Book");
					$proveedor[$key]->estatus->setCellValue("BI".$flag, "PROMOCIÓN");
					$this->cellStyle("BJ".$flag, "92D050", "FF0000", TRUE, 12, "Franklin Gothic Book");
					$proveedor[$key]->estatus->setCellValue("BJ".$flag, "NOTA");

					$flag++;
					$this->excelfile->getActiveSheet()->getStyle('A'.$flag.':BJ'.$flag)->applyFromArray($styleArray);
					$proveedor[$key]->estatus->mergeCells('A'.$flag.':E'.$flag);
					$this->cellStyle("A".$flag, "000000", "FFFFFF", TRUE, 12, "Franklin Gothic Book");
					$proveedor[$key]->estatus->setCellValue("A".$flag."", "DESCRIPCIÓN");
					$proveedor[$key]->estatus->mergeCells('F'.$flag.':J'.$flag);
					$this->cellStyle("F".$flag, "000000", "FFFFFF", TRUE, 12, "Franklin Gothic Book");
					$proveedor[$key]->estatus->setCellValue("F".$flag."", "EXISTENCIAS");
					$proveedor[$key]->estatus->mergeCells('K'.$flag.':O'.$flag);
					$this->cellStyle("K".$flag, "000000", "FFFFFF", TRUE, 12, "Franklin Gothic Book");
					$proveedor[$key]->estatus->setCellValue("K".$flag."", "EXISTENCIAS");
					$proveedor[$key]->estatus->mergeCells('P'.$flag.':T'.$flag);
					$this->cellStyle("P".$flag, "000000", "FFFFFF", TRUE, 12, "Franklin Gothic Book");
					$proveedor[$key]->estatus->setCellValue("P".$flag."", "EXISTENCIAS");
					$proveedor[$key]->estatus->mergeCells('U'.$flag.':Y'.$flag);
					$this->cellStyle("U".$flag, "000000", "FFFFFF", TRUE, 12, "Franklin Gothic Book");
					$proveedor[$key]->estatus->setCellValue("U".$flag."", "EXISTENCIAS");
					$proveedor[$key]->estatus->mergeCells('Z'.$flag.':AD'.$flag);
					$this->cellStyle("Z".$flag, "000000", "FFFFFF", TRUE, 12, "Franklin Gothic Book");
					$proveedor[$key]->estatus->setCellValue("Z".$flag."", "EXISTENCIAS");
					$proveedor[$key]->estatus->mergeCells('AE'.$flag.':AI'.$flag);
					$this->cellStyle("AE".$flag, "000000", "FFFFFF", TRUE, 12, "Franklin Gothic Book");
					$proveedor[$key]->estatus->setCellValue("AE".$flag."", "EXISTENCIAS");
					$proveedor[$key]->estatus->mergeCells('AJ'.$flag.':AN'.$flag);
					$this->cellStyle("AJ".$flag, "000000", "FFFFFF", TRUE, 12, "Franklin Gothic Book");
					$proveedor[$key]->estatus->setCellValue("AJ".$flag."", "EXISTENCIAS");
					$proveedor[$key]->estatus->mergeCells('AO'.$flag.':AS'.$flag);
					$this->cellStyle("AO".$flag, "000000", "FFFFFF", TRUE, 12, "Franklin Gothic Book");
					$proveedor[$key]->estatus->setCellValue("AO".$flag."", "EXISTENCIAS");
					$proveedor[$key]->estatus->mergeCells('AT'.$flag.':AX'.$flag);
					$this->cellStyle("AT".$flag, "000000", "FFFFFF", TRUE, 12, "Franklin Gothic Book");
					$proveedor[$key]->estatus->setCellValue("AT".$flag."", "EXISTENCIAS");
					$proveedor[$key]->estatus->mergeCells('AY'.$flag.':BC'.$flag);
					$this->cellStyle("AY".$flag, "000000", "FFFFFF", TRUE, 12, "Franklin Gothic Book");
					$proveedor[$key]->estatus->setCellValue("AY".$flag."", "EXISTENCIAS");
					$proveedor[$key]->estatus->mergeCells('BD'.$flag.':BH'.$flag);
					$this->cellStyle("BD".$flag, "000000", "FFFFFF", TRUE, 12, "Franklin Gothic Book");
					$proveedor[$key]->estatus->setCellValue("BD".$flag."", "EXISTENCIAS");
					$this->cellStyle("BI".$flag, "FFFF00", "FF0000", TRUE, 12, "Franklin Gothic Book");
					$this->cellStyle("BJ".$flag, "92D050", "FF0000", TRUE, 12, "Franklin Gothic Book");
					
					$flag++;
					$this->excelfile->getActiveSheet()->getStyle('A'.$flag.':BJ'.$flag)->applyFromArray($styleArray);
					$proveedor[$key]->estatus->mergeCells('A'.$flag.':B'.$flag);
					$this->cellStyle("A".$flag, "000000", "FFFFFF", TRUE, 12, "Franklin Gothic Book");
					$this->cellStyle("C".$flag, "000000", "FFFFFF", FALSE, 10, "Franklin Gothic Book");
					$proveedor[$key]->estatus->setCellValue("C".$flag."", "PRECIO");
					$this->cellStyle("D".$flag, "000000", "FFFFFF", FALSE, 10, "Franklin Gothic Book");
					$proveedor[$key]->estatus->setCellValue("D".$flag."", "SISTEMA");
					$this->cellStyle("E".$flag, "000000", "FFFFFF", FALSE, 10, "Franklin Gothic Book");
					$proveedor[$key]->estatus->setCellValue("E".$flag."", "UM");
					$this->cellStyle("F".$flag, "000000", "FFFFFF", FALSE, 10, "Franklin Gothic Book");
					$proveedor[$key]->estatus->setCellValue("F".$flag."", "Pedido anterior");
					$this->cellStyle("G".$flag, "000000", "FF0000", FALSE, 10, "Franklin Gothic Book");
					$proveedor[$key]->estatus->setCellValue("G".$flag."", "Sugerido");
					$this->cellStyle("H".$flag, "000000", "FFFFFF", FALSE, 10, "Franklin Gothic Book");
					$proveedor[$key]->estatus->setCellValue("H".$flag."", "Cajas");
					$this->cellStyle("I".$flag, "000000", "FFFFFF", FALSE, 10, "Franklin Gothic Book");
					$proveedor[$key]->estatus->setCellValue("I".$flag."", "Pzs");
					$this->cellStyle("J".$flag, "000000", "FFFFFF", FALSE, 10, "Franklin Gothic Book");
					$proveedor[$key]->estatus->setCellValue("J".$flag."", "Pedido");
					$this->cellStyle("K".$flag, "000000", "FFFFFF", FALSE, 10, "Franklin Gothic Book");
					$proveedor[$key]->estatus->setCellValue("K".$flag."", "Pedido anterior");
					$this->cellStyle("L".$flag, "000000", "FF0000", FALSE, 10, "Franklin Gothic Book");
					$proveedor[$key]->estatus->setCellValue("L".$flag."", "Sugerido");
					$this->cellStyle("M".$flag, "000000", "FFFFFF", FALSE, 10, "Franklin Gothic Book");
					$proveedor[$key]->estatus->setCellValue("M".$flag."", "Cajas");
					$this->cellStyle("N".$flag, "000000", "FFFFFF", FALSE, 10, "Franklin Gothic Book");
					$proveedor[$key]->estatus->setCellValue("N".$flag."", "Pzs");
					$this->cellStyle("O".$flag, "000000", "FFFFFF", FALSE, 10, "Franklin Gothic Book");
					$proveedor[$key]->estatus->setCellValue("O".$flag."", "Pedido");
					$this->cellStyle("P".$flag, "000000", "FFFFFF", FALSE, 10, "Franklin Gothic Book");
					$proveedor[$key]->estatus->setCellValue("P".$flag."", "Pedido anterior");
					$this->cellStyle("Q".$flag, "000000", "FF0000", FALSE, 10, "Franklin Gothic Book");
					$proveedor[$key]->estatus->setCellValue("Q".$flag."", "Sugerido");
					$this->cellStyle("R".$flag, "000000", "FFFFFF", FALSE, 10, "Franklin Gothic Book");
					$proveedor[$key]->estatus->setCellValue("R".$flag."", "Cajas");
					$this->cellStyle("S".$flag, "000000", "FFFFFF", FALSE, 10, "Franklin Gothic Book");
					$proveedor[$key]->estatus->setCellValue("S".$flag."", "Pzs");
					$this->cellStyle("T".$flag, "000000", "FFFFFF", FALSE, 10, "Franklin Gothic Book");
					$proveedor[$key]->estatus->setCellValue("T".$flag."", "Pedido");
					$this->cellStyle("U".$flag, "000000", "FFFFFF", FALSE, 10, "Franklin Gothic Book");
					$proveedor[$key]->estatus->setCellValue("U".$flag."", "Pedido anterior");
					$this->cellStyle("V".$flag, "000000", "FF0000", FALSE, 10, "Franklin Gothic Book");
					$proveedor[$key]->estatus->setCellValue("V".$flag."", "Sugerido");
					$this->cellStyle("W".$flag, "000000", "FFFFFF", FALSE, 10, "Franklin Gothic Book");
					$proveedor[$key]->estatus->setCellValue("W".$flag."", "Cajas");
					$this->cellStyle("X".$flag, "000000", "FFFFFF", FALSE, 10, "Franklin Gothic Book");
					$proveedor[$key]->estatus->setCellValue("X".$flag."", "Pzs");
					$this->cellStyle("Y".$flag, "000000", "FFFFFF", FALSE, 10, "Franklin Gothic Book");
					$proveedor[$key]->estatus->setCellValue("Y".$flag."", "Pedido");
					$this->cellStyle("Z".$flag, "000000", "FFFFFF", FALSE, 10, "Franklin Gothic Book");
					$proveedor[$key]->estatus->setCellValue("Z".$flag."", "Pedido anterior");
					$this->cellStyle("AA".$flag, "000000", "FF0000", FALSE, 10, "Franklin Gothic Book");
					$proveedor[$key]->estatus->setCellValue("AA".$flag."", "Sugerido");
					$this->cellStyle("AB".$flag, "000000", "FFFFFF", FALSE, 10, "Franklin Gothic Book");
					$proveedor[$key]->estatus->setCellValue("AB".$flag."", "Cajas");
					$this->cellStyle("AC".$flag, "000000", "FFFFFF", FALSE, 10, "Franklin Gothic Book");
					$proveedor[$key]->estatus->setCellValue("AC".$flag."", "Pzs");
					$this->cellStyle("AD".$flag, "000000", "FFFFFF", FALSE, 10, "Franklin Gothic Book");
					$proveedor[$key]->estatus->setCellValue("AD".$flag."", "Pedido");
					$this->cellStyle("AE".$flag, "000000", "FFFFFF", FALSE, 10, "Franklin Gothic Book");
					$proveedor[$key]->estatus->setCellValue("AE".$flag."", "Pedido anterior");
					$this->cellStyle("AF".$flag, "000000", "FF0000", FALSE, 10, "Franklin Gothic Book");
					$proveedor[$key]->estatus->setCellValue("AF".$flag."", "Sugerido");
					$this->cellStyle("AG".$flag, "000000", "FFFFFF", FALSE, 10, "Franklin Gothic Book");
					$proveedor[$key]->estatus->setCellValue("AG".$flag."", "Cajas");
					$this->cellStyle("AH".$flag, "000000", "FFFFFF", FALSE, 10, "Franklin Gothic Book");
					$proveedor[$key]->estatus->setCellValue("AH".$flag."", "Pzs");
					$this->cellStyle("AI".$flag, "000000", "FFFFFF", FALSE, 10, "Franklin Gothic Book");
					$proveedor[$key]->estatus->setCellValue("AI".$flag."", "Pedido");
					$this->cellStyle("AJ".$flag, "000000", "FFFFFF", FALSE, 10, "Franklin Gothic Book");
					$proveedor[$key]->estatus->setCellValue("AJ".$flag."", "Pedido anterior");
					$this->cellStyle("AK".$flag, "000000", "FF0000", FALSE, 10, "Franklin Gothic Book");
					$proveedor[$key]->estatus->setCellValue("AK".$flag."", "Sugerido");
					$this->cellStyle("AL".$flag, "000000", "FFFFFF", FALSE, 10, "Franklin Gothic Book");
					$proveedor[$key]->estatus->setCellValue("AL".$flag."", "Cajas");
					$this->cellStyle("AM".$flag, "000000", "FFFFFF", FALSE, 10, "Franklin Gothic Book");
					$proveedor[$key]->estatus->setCellValue("AM".$flag."", "Pzs");
					$this->cellStyle("AN".$flag, "000000", "FFFFFF", FALSE, 10, "Franklin Gothic Book");
					$proveedor[$key]->estatus->setCellValue("AN".$flag."", "Pedido");
					$this->cellStyle("AO".$flag, "000000", "FFFFFF", FALSE, 10, "Franklin Gothic Book");
					$proveedor[$key]->estatus->setCellValue("AO".$flag."", "Pedido anterior");
					$this->cellStyle("AP".$flag, "000000", "FF0000", FALSE, 10, "Franklin Gothic Book");
					$proveedor[$key]->estatus->setCellValue("AP".$flag."", "Sugerido");
					$this->cellStyle("AQ".$flag, "000000", "FFFFFF", FALSE, 10, "Franklin Gothic Book");
					$proveedor[$key]->estatus->setCellValue("AQ".$flag."", "Cajas");
					$this->cellStyle("AR".$flag, "000000", "FFFFFF", FALSE, 10, "Franklin Gothic Book");
					$proveedor[$key]->estatus->setCellValue("AR".$flag."", "Pzs");
					$this->cellStyle("AS".$flag, "000000", "FFFFFF", FALSE, 10, "Franklin Gothic Book");
					$proveedor[$key]->estatus->setCellValue("AS".$flag."", "Pedido");
					$this->cellStyle("AT".$flag, "000000", "FFFFFF", FALSE, 10, "Franklin Gothic Book");
					$proveedor[$key]->estatus->setCellValue("AT".$flag."", "Pedido anterior");
					$this->cellStyle("AU".$flag, "000000", "FF0000", FALSE, 10, "Franklin Gothic Book");
					$proveedor[$key]->estatus->setCellValue("AU".$flag."", "Sugerido");
					$this->cellStyle("AV".$flag, "000000", "FFFFFF", FALSE, 10, "Franklin Gothic Book");
					$proveedor[$key]->estatus->setCellValue("AV".$flag."", "Cajas");
					$this->cellStyle("AW".$flag, "000000", "FFFFFF", FALSE, 10, "Franklin Gothic Book");
					$proveedor[$key]->estatus->setCellValue("AW".$flag."", "Pzs");
					$this->cellStyle("AX".$flag, "000000", "FFFFFF", FALSE, 10, "Franklin Gothic Book");
					$proveedor[$key]->estatus->setCellValue("AX".$flag."", "Pedido");
					$this->cellStyle("AY".$flag, "000000", "FFFFFF", FALSE, 10, "Franklin Gothic Book");
					$proveedor[$key]->estatus->setCellValue("AY".$flag."", "Pedido anterior");
					$this->cellStyle("AZ".$flag, "000000", "FF0000", FALSE, 10, "Franklin Gothic Book");
					$proveedor[$key]->estatus->setCellValue("AZ".$flag."", "Sugerido");
					$this->cellStyle("BA".$flag, "000000", "FFFFFF", FALSE, 10, "Franklin Gothic Book");
					$proveedor[$key]->estatus->setCellValue("BA".$flag."", "Cajas");
					$this->cellStyle("BB".$flag, "000000", "FFFFFF", FALSE, 10, "Franklin Gothic Book");
					$proveedor[$key]->estatus->setCellValue("BB".$flag."", "Pzs");
					$this->cellStyle("BC".$flag, "000000", "FFFFFF", FALSE, 10, "Franklin Gothic Book");
					$proveedor[$key]->estatus->setCellValue("BC".$flag."", "Pedido");
					$this->cellStyle("BD".$flag, "000000", "FFFFFF", FALSE, 10, "Franklin Gothic Book");
					$proveedor[$key]->estatus->setCellValue("BD".$flag."", "Pedido anterior");
					$this->cellStyle("BE".$flag, "000000", "FF0000", FALSE, 10, "Franklin Gothic Book");
					$proveedor[$key]->estatus->setCellValue("BE".$flag."", "Sugerido");
					$this->cellStyle("BF".$flag, "000000", "FFFFFF", FALSE, 10, "Franklin Gothic Book");
					$proveedor[$key]->estatus->setCellValue("BF".$flag."", "Cajas");
					$this->cellStyle("BG".$flag, "000000", "FFFFFF", FALSE, 10, "Franklin Gothic Book");
					$proveedor[$key]->estatus->setCellValue("BG".$flag."", "Pzs");
					$this->cellStyle("BH".$flag, "000000", "FFFFFF", FALSE, 10, "Franklin Gothic Book");
					$proveedor[$key]->estatus->setCellValue("BH".$flag."", "Pedido");
					$this->cellStyle("BI".$flag, "FFFF00", "FF0000", FALSE, 10, "Franklin Gothic Book");
					$this->cellStyle("BJ".$flag, "92D050", "FF0000", TRUE, 12, "Franklin Gothic Book");

					$this->excelfile->getActiveSheet()->getStyle('BL'.$flag.':BV'.$flag)->applyFromArray($styleArray);

					$this->cellStyle("BL".$flag, "C00000", "000000", TRUE, 12, "Franklin Gothic Book");
					$this->cellStyle("BM".$flag, "FF0066", "000000", TRUE, 12, "Franklin Gothic Book");
					$this->cellStyle("BN".$flag, "01B0F0", "000000", TRUE, 12, "Franklin Gothic Book");
					$this->cellStyle("BO".$flag, "FF0000", "000000", TRUE, 12, "Franklin Gothic Book");
					$this->cellStyle("BP".$flag, "E26C0B", "000000", TRUE, 12, "Franklin Gothic Book");
					$this->cellStyle("BQ".$flag, "C5C5C5", "000000", TRUE, 12, "Franklin Gothic Book");
					$this->cellStyle("BR".$flag, "92D051", "000000", TRUE, 12, "Franklin Gothic Book");
					$this->cellStyle("BS".$flag, "B1A0C7", "000000", TRUE, 12, "Franklin Gothic Book");
					$this->cellStyle("BT".$flag, "DA9694", "000000", TRUE, 12, "Franklin Gothic Book");
					$this->cellStyle("BU".$flag, "4CACC6", "000000", TRUE, 12, "Franklin Gothic Book");
					$this->cellStyle("BV".$flag, "000000", "FFFFFF", TRUE, 12, "Franklin Gothic Book");
					
					$proveedor[$key]->estatus->setCellValue("AZ".$flag."", "TOTAL");
					
					$proveedor[$key]->estatus->getColumnDimension('BL')->setWidth("15");
					$proveedor[$key]->estatus->getColumnDimension('BM')->setWidth("15");
					$proveedor[$key]->estatus->getColumnDimension('BN')->setWidth("15");
					$proveedor[$key]->estatus->getColumnDimension('BO')->setWidth("15");
					$proveedor[$key]->estatus->getColumnDimension('BP')->setWidth("15");
					$proveedor[$key]->estatus->getColumnDimension('BQ')->setWidth("15");
					$proveedor[$key]->estatus->getColumnDimension('BR')->setWidth("15");
					$proveedor[$key]->estatus->getColumnDimension('BS')->setWidth("15");
					$proveedor[$key]->estatus->getColumnDimension('BT')->setWidth("15");
					$proveedor[$key]->estatus->getColumnDimension('BU')->setWidth("15");
					$proveedor[$key]->estatus->getColumnDimension('BV')->setWidth("15");
				}

				if (2 == 2) {
					$this->excelfile->setActiveSheetIndex($last);
					$anterior = $this->excelfile->getActiveSheet();
					$anterior->getColumnDimension('A')->setWidth("25");
					$anterior->getColumnDimension('B')->setWidth("70");
					$anterior->getColumnDimension('BI')->setWidth("40");
					$anterior->getColumnDimension('BJ')->setWidth("40");
					$anterior->getColumnDimension('C')->setWidth("15");
					$anterior->getColumnDimension('D')->setWidth("15");
					$anterior->getColumnDimension('E')->setWidth("15");
					$anterior->getColumnDimension('AM')->setWidth("40");
					$anterior->getColumnDimension('AN')->setWidth("40");
					$anterior->mergeCells('A'.$flag1.':AN'.$flag1);
					$this->cellStyle("A".$flag1, "FFFFFF", "000000", TRUE, 12, "Franklin Gothic Book");
					$anterior->setCellValue("A".$flag1."", "CEDIS, ABARROTES,PEDREGAL, TIENDA, ULTRAMARINOS, TRINCHERAS, MERCADO, TIJERAS, Y TENENCIA AZTECA AUTOSERVICIOS SA. DE CV.");
					$this->excelfile->getActiveSheet()->getStyle('A'.$flag1.':AN'.$flag1)->applyFromArray($styleArray);
					$flag1++;
					$this->excelfile->getActiveSheet()->getStyle('A'.$flag1.':AN'.$flag1)->applyFromArray($styleArray);
					$anterior->mergeCells('A'.$flag1.':B'.$flag1);
					$this->cellStyle("A".$flag1, "FFFFFF", "000000", TRUE, 12, "Franklin Gothic Book");
					$anterior->setCellValue("A".$flag1."", "PEDIDO A ".$va->nombre);
					$anterior->mergeCells('C'.$flag1.':E'.$flag1);
					$this->cellStyle("C".$flag1, "FFFFFF", "000000", TRUE, 10, "Franklin Gothic Book");
					$anterior->setCellValue("C".$flag1."", $fecha);

					$anterior->mergeCells('F'.$flag1.':H'.$flag1);
					$this->cellStyle("F".$flag1, "C00000", "000000", TRUE, 12, "Franklin Gothic Book");
					$anterior->setCellValue("F".$flag1, "CEDIS/SUPER");
					$anterior->mergeCells('I'.$flag1.':K'.$flag1);
					$this->cellStyle("I".$flag1, "C2B90A", "000000", TRUE, 12, "Franklin Gothic Book");
					$anterior->setCellValue("I".$flag1, "SUMA CEDIS/SUPER");
					$anterior->mergeCells('L'.$flag1.':N'.$flag1);
					$anterior->setCellValue("L".$flag1, "CD INDUSTRIAL");
					$this->cellStyle("L".$flag1, "FF0066", "000000", TRUE, 12, "Franklin Gothic Book");
					
					$anterior->mergeCells('O'.$flag1.':Q'.$flag1);
					$this->cellStyle("O".$flag1, "01B0F0", "000000", TRUE, 12, "Franklin Gothic Book");
					$anterior->setCellValue("O".$flag1, "ABARROTES");
					$anterior->mergeCells('R'.$flag1.':T'.$flag1);
					$this->cellStyle("R".$flag1, "FF0000", "000000", TRUE, 12, "Franklin Gothic Book");
					$anterior->setCellValue("R".$flag1, "PEDREGAL");
					$anterior->mergeCells('U'.$flag1.':W'.$flag1);
					$this->cellStyle("U".$flag1, "E26C0B", "000000", TRUE, 12, "Franklin Gothic Book");
					$anterior->setCellValue("U".$flag1, "TIENDA");
					$anterior->mergeCells('X'.$flag1.':Z'.$flag1);
					$this->cellStyle("X".$flag1, "C5C5C5", "000000", TRUE, 12, "Franklin Gothic Book");
					$anterior->setCellValue("X".$flag1, "ULTRAMARINOS");
					$anterior->mergeCells('AA'.$flag1.':AC'.$flag1);
					$this->cellStyle("AA".$flag1, "92D051", "000000", TRUE, 12, "Franklin Gothic Book");
					$anterior->setCellValue("AA".$flag1, "TRINCHERAS");
					$anterior->mergeCells('AD'.$flag1.':AF'.$flag1);
					$this->cellStyle("AD".$flag1, "B1A0C7", "000000", TRUE, 12, "Franklin Gothic Book");
					$anterior->setCellValue("AD".$flag1, "AZT MERCADO");
					$anterior->mergeCells('AG'.$flag1.':AI'.$flag1);
					$this->cellStyle("AG".$flag1, "DA9694", "000000", TRUE, 12, "Franklin Gothic Book");
					$anterior->setCellValue("AG".$flag1, "TENENCIA");
					$anterior->mergeCells('AJ'.$flag1.':AL'.$flag1);
					$this->cellStyle("AJ".$flag1, "4CACC6", "000000", TRUE, 12, "Franklin Gothic Book");
					$anterior->setCellValue("AJ".$flag1, "TIJERAS");
					$this->cellStyle("AM".$flag1, "FFFF00", "FF0000", TRUE, 12, "Franklin Gothic Book");
					$anterior->setCellValue("AM".$flag1, "PROMOCIÓN");
					$this->cellStyle("AN".$flag1, "92D050", "FF0000", TRUE, 12, "Franklin Gothic Book");
					$anterior->setCellValue("AN".$flag1, "NOTA");

					$flag1++;
					$this->excelfile->getActiveSheet()->getStyle('A'.$flag1.':AN'.$flag1)->applyFromArray($styleArray);
					$anterior->mergeCells('A'.$flag1.':E'.$flag1);
					$this->cellStyle("A".$flag1, "000000", "FFFFFF", TRUE, 12, "Franklin Gothic Book");
					$anterior->setCellValue("A".$flag1."", "DESCRIPCIÓN");
					$anterior->mergeCells('F'.$flag1.':H'.$flag1);
					$this->cellStyle("F".$flag1, "000000", "FFFFFF", TRUE, 12, "Franklin Gothic Book");
					$anterior->setCellValue("F".$flag1."", "EXISTENCIAS");
					$anterior->mergeCells('I'.$flag1.':K'.$flag1);
					$this->cellStyle("I".$flag1, "000000", "FFFFFF", TRUE, 12, "Franklin Gothic Book");
					$anterior->setCellValue("I".$flag1."", "EXISTENCIAS");
					$anterior->mergeCells('L'.$flag1.':N'.$flag1);
					$this->cellStyle("L".$flag1, "000000", "FFFFFF", TRUE, 12, "Franklin Gothic Book");
					$anterior->setCellValue("L".$flag1."", "EXISTENCIAS");
					$anterior->mergeCells('O'.$flag1.':Q'.$flag1);
					$this->cellStyle("O".$flag1, "000000", "FFFFFF", TRUE, 12, "Franklin Gothic Book");
					$anterior->setCellValue("O".$flag1."", "EXISTENCIAS");
					$anterior->mergeCells('R'.$flag1.':T'.$flag1);
					$this->cellStyle("R".$flag1, "000000", "FFFFFF", TRUE, 12, "Franklin Gothic Book");
					$anterior->setCellValue("R".$flag1."", "EXISTENCIAS");
					$anterior->mergeCells('U'.$flag1.':W'.$flag1);
					$this->cellStyle("U".$flag1, "000000", "FFFFFF", TRUE, 12, "Franklin Gothic Book");
					$anterior->setCellValue("U".$flag1."", "EXISTENCIAS");
					$anterior->mergeCells('X'.$flag1.':Z'.$flag1);
					$this->cellStyle("X".$flag1, "000000", "FFFFFF", TRUE, 12, "Franklin Gothic Book");
					$anterior->setCellValue("X".$flag1."", "EXISTENCIAS");
					$anterior->mergeCells('AA'.$flag1.':AC'.$flag1);
					$this->cellStyle("AA".$flag1, "000000", "FFFFFF", TRUE, 12, "Franklin Gothic Book");
					$anterior->setCellValue("AA".$flag1."", "EXISTENCIAS");
					$anterior->mergeCells('AD'.$flag1.':AF'.$flag1);
					$this->cellStyle("AD".$flag1, "000000", "FFFFFF", TRUE, 12, "Franklin Gothic Book");
					$anterior->setCellValue("AD".$flag1."", "EXISTENCIAS");
					$anterior->mergeCells('AG'.$flag1.':AI'.$flag1);
					$this->cellStyle("AG".$flag1, "000000", "FFFFFF", TRUE, 12, "Franklin Gothic Book");
					$anterior->setCellValue("AG".$flag1."", "EXISTENCIAS");
					$anterior->mergeCells('AJ'.$flag1.':AL'.$flag1);
					$this->cellStyle("AJ".$flag1, "000000", "FFFFFF", TRUE, 12, "Franklin Gothic Book");
					$anterior->setCellValue("AJ".$flag1."", "EXISTENCIAS");
					$this->cellStyle("AM".$flag1, "FFFF00", "FF0000", TRUE, 12, "Franklin Gothic Book");
					$this->cellStyle("AN".$flag1, "92D050", "FF0000", TRUE, 12, "Franklin Gothic Book");
					$flag1++;
					$this->excelfile->getActiveSheet()->getStyle('A'.$flag1.':AN'.$flag1)->applyFromArray($styleArray);
					$anterior->mergeCells('A'.$flag1.':B'.$flag1);
					$this->cellStyle("A".$flag1, "000000", "FFFFFF", TRUE, 12, "Franklin Gothic Book");
					$this->cellStyle("C".$flag1, "000000", "FFFFFF", FALSE, 10, "Franklin Gothic Book");
					$anterior->setCellValue("C".$flag1."", "PRECIO");
					$this->cellStyle("D".$flag1, "000000", "FFFFFF", FALSE, 10, "Franklin Gothic Book");
					$anterior->setCellValue("D".$flag1."", "SISTEMA");
					$this->cellStyle("E".$flag1, "000000", "FFFFFF", FALSE, 10, "Franklin Gothic Book");
					$anterior->setCellValue("E".$flag1."", "UM");
					$this->cellStyle("F".$flag1, "000000", "FFFFFF", FALSE, 10, "Franklin Gothic Book");
					$anterior->setCellValue("F".$flag1."", "Cajas");
					$this->cellStyle("G".$flag1, "000000", "FFFFFF", FALSE, 10, "Franklin Gothic Book");
					$anterior->setCellValue("G".$flag1."", "Pzs");
					$this->cellStyle("H".$flag1, "000000", "FFFFFF", FALSE, 10, "Franklin Gothic Book");
					$anterior->setCellValue("H".$flag1."", "Pedido");
					$this->cellStyle("I".$flag1, "000000", "FFFFFF", FALSE, 10, "Franklin Gothic Book");
					$anterior->setCellValue("I".$flag1."", "Cajas");
					$this->cellStyle("J".$flag1, "000000", "FFFFFF", FALSE, 10, "Franklin Gothic Book");
					$anterior->setCellValue("J".$flag1."", "Pzs");
					$this->cellStyle("K".$flag1, "000000", "FFFFFF", FALSE, 10, "Franklin Gothic Book");
					$anterior->setCellValue("K".$flag1."", "Pedido");
					$this->cellStyle("L".$flag1, "000000", "FFFFFF", FALSE, 10, "Franklin Gothic Book");
					$anterior->setCellValue("L".$flag1."", "Cajas");
					$this->cellStyle("M".$flag1, "000000", "FFFFFF", FALSE, 10, "Franklin Gothic Book");
					$anterior->setCellValue("M".$flag1."", "Pzs");
					$this->cellStyle("N".$flag1, "000000", "FFFFFF", FALSE, 10, "Franklin Gothic Book");
					$anterior->setCellValue("N".$flag1."", "Pedido");
					$this->cellStyle("O".$flag1, "000000", "FFFFFF", FALSE, 10, "Franklin Gothic Book");
					$anterior->setCellValue("O".$flag1."", "Cajas");
					$this->cellStyle("P".$flag1, "000000", "FFFFFF", FALSE, 10, "Franklin Gothic Book");
					$anterior->setCellValue("P".$flag1."", "Pzs");
					$this->cellStyle("Q".$flag1, "000000", "FFFFFF", FALSE, 10, "Franklin Gothic Book");
					$anterior->setCellValue("Q".$flag1."", "Pedido");
					$this->cellStyle("R".$flag1, "000000", "FFFFFF", FALSE, 10, "Franklin Gothic Book");
					$anterior->setCellValue("R".$flag1."", "Cajas");
					$this->cellStyle("S".$flag1, "000000", "FFFFFF", FALSE, 10, "Franklin Gothic Book");
					$anterior->setCellValue("S".$flag1."", "Pzs");
					$this->cellStyle("T".$flag1, "000000", "FFFFFF", FALSE, 10, "Franklin Gothic Book");
					$anterior->setCellValue("T".$flag1."", "Pedido");
					$this->cellStyle("U".$flag1, "000000", "FFFFFF", FALSE, 10, "Franklin Gothic Book");
					$anterior->setCellValue("U".$flag1."", "Cajas");
					$this->cellStyle("V".$flag1, "000000", "FFFFFF", FALSE, 10, "Franklin Gothic Book");
					$anterior->setCellValue("V".$flag1."", "Pzs");
					$this->cellStyle("W".$flag1, "000000", "FFFFFF", FALSE, 10, "Franklin Gothic Book");
					$anterior->setCellValue("W".$flag1."", "Pedido");
					$this->cellStyle("X".$flag1, "000000", "FFFFFF", FALSE, 10, "Franklin Gothic Book");
					$anterior->setCellValue("X".$flag1."", "Cajas");
					$this->cellStyle("Y".$flag1, "000000", "FFFFFF", FALSE, 10, "Franklin Gothic Book");
					$anterior->setCellValue("Y".$flag1."", "Pzs");
					$this->cellStyle("Z".$flag1, "000000", "FFFFFF", FALSE, 10, "Franklin Gothic Book");
					$anterior->setCellValue("Z".$flag1."", "Pedido");
					$this->cellStyle("AA".$flag1, "000000", "FFFFFF", FALSE, 10, "Franklin Gothic Book");
					$anterior->setCellValue("AA".$flag1."", "Cajas");
					$this->cellStyle("AB".$flag1, "000000", "FFFFFF", FALSE, 10, "Franklin Gothic Book");
					$anterior->setCellValue("AB".$flag1."", "Pzs");
					$this->cellStyle("AC".$flag1, "000000", "FFFFFF", FALSE, 10, "Franklin Gothic Book");
					$anterior->setCellValue("AC".$flag1."", "Pedido");
					$this->cellStyle("AD".$flag1, "000000", "FFFFFF", FALSE, 10, "Franklin Gothic Book");
					$anterior->setCellValue("AD".$flag1."", "Cajas");
					$this->cellStyle("AE".$flag1, "000000", "FFFFFF", FALSE, 10, "Franklin Gothic Book");
					$anterior->setCellValue("AE".$flag1."", "Pzs");
					$this->cellStyle("AF".$flag1, "000000", "FFFFFF", FALSE, 10, "Franklin Gothic Book");
					$anterior->setCellValue("AF".$flag1."", "Pedido");
					$this->cellStyle("AG".$flag1, "000000", "FFFFFF", FALSE, 10, "Franklin Gothic Book");
					$anterior->setCellValue("AG".$flag1."", "Cajas");
					$this->cellStyle("AH".$flag1, "000000", "FFFFFF", FALSE, 10, "Franklin Gothic Book");
					$anterior->setCellValue("AH".$flag1."", "Pzs");
					$this->cellStyle("AI".$flag1, "000000", "FFFFFF", FALSE, 10, "Franklin Gothic Book");
					$anterior->setCellValue("AI".$flag1."", "Pedido");
					$this->cellStyle("AJ".$flag1, "000000", "FFFFFF", FALSE, 10, "Franklin Gothic Book");
					$anterior->setCellValue("AJ".$flag1."", "Cajas");
					$this->cellStyle("AK".$flag1, "000000", "FFFFFF", FALSE, 10, "Franklin Gothic Book");
					$anterior->setCellValue("AK".$flag1."", "Pzs");
					$this->cellStyle("AL".$flag1, "000000", "FFFFFF", FALSE, 10, "Franklin Gothic Book");
					$anterior->setCellValue("AL".$flag1."", "Pedido");
					$this->cellStyle("AM".$flag1, "FFFF00", "FF0000", FALSE, 10, "Franklin Gothic Book");
					$this->cellStyle("AN".$flag1, "92D050", "FFFFFF", TRUE, 12, "Franklin Gothic Book");
					$this->excelfile->getActiveSheet()->getStyle('AP'.$flag1.':AZ'.$flag1)->applyFromArray($styleArray);
					$this->excelfile->getActiveSheet()->getStyle('BC'.$flag1.':BL'.$flag1)->applyFromArray($styleArray);

					$this->cellStyle("AP".$flag1, "C00000", "000000", TRUE, 12, "Franklin Gothic Book");
					$this->cellStyle("AQ".$flag1, "FF0066", "000000", TRUE, 12, "Franklin Gothic Book");
					$this->cellStyle("AR".$flag1, "01B0F0", "000000", TRUE, 12, "Franklin Gothic Book");
					$this->cellStyle("AS".$flag1, "FF0000", "000000", TRUE, 12, "Franklin Gothic Book");
					$this->cellStyle("AT".$flag1, "E26C0B", "000000", TRUE, 12, "Franklin Gothic Book");
					$this->cellStyle("AU".$flag1, "C5C5C5", "000000", TRUE, 12, "Franklin Gothic Book");
					$this->cellStyle("AV".$flag1, "92D051", "000000", TRUE, 12, "Franklin Gothic Book");
					$this->cellStyle("AW".$flag1, "B1A0C7", "000000", TRUE, 12, "Franklin Gothic Book");
					$this->cellStyle("AX".$flag1, "DA9694", "000000", TRUE, 12, "Franklin Gothic Book");
					$this->cellStyle("AY".$flag1, "4CACC6", "000000", TRUE, 12, "Franklin Gothic Book");
					$this->cellStyle("AZ".$flag1, "000000", "FFFFFF", TRUE, 12, "Franklin Gothic Book");
					
					$anterior->setCellValue("AZ".$flag1."", "TOTAL");
					
					$anterior->getColumnDimension('AO')->setWidth("15");
					$anterior->getColumnDimension('AP')->setWidth("15");
					$anterior->getColumnDimension('AQ')->setWidth("15");
					$anterior->getColumnDimension('AR')->setWidth("15");
					$anterior->getColumnDimension('AS')->setWidth("15");
					$anterior->getColumnDimension('AT')->setWidth("15");
					$anterior->getColumnDimension('AU')->setWidth("15");
					$anterior->getColumnDimension('AV')->setWidth("15");
					$anterior->getColumnDimension('AW')->setWidth("15");
					$anterior->getColumnDimension('AX')->setWidth("15");
					$anterior->getColumnDimension('AY')->setWidth("15");
					$anterior->getColumnDimension('AZ')->setWidth("15");
					$anterior->getColumnDimension('BA')->setWidth("15");
					$anterior->getColumnDimension('BB')->setWidth("15");
					$anterior->getColumnDimension('BC')->setWidth("15");
					$anterior->getColumnDimension('BD')->setWidth("15");
					$anterior->getColumnDimension('BE')->setWidth("15");
					$anterior->getColumnDimension('BF')->setWidth("15");
					$anterior->getColumnDimension('BG')->setWidth("15");
					$anterior->getColumnDimension('BH')->setWidth("15");
					$anterior->getColumnDimension('BI')->setWidth("15");
					$anterior->getColumnDimension('BJ')->setWidth("15");
					$anterior->getColumnDimension('BK')->setWidth("15");
					$anterior->getColumnDimension('BL')->setWidth("15");

					$this->cellStyle("BC".$flag1, "C00000", "000000", TRUE, 10, "Franklin Gothic Book");
					$anterior->setCellValue("BC".$flag1."", "CEDIS");
					$this->cellStyle("BD".$flag1, "FF0066", "000000", TRUE, 10, "Franklin Gothic Book");
					$anterior->setCellValue("BD".$flag1."", "INDUSTRIAL");
					$this->cellStyle("BE".$flag1, "01B0F0", "000000", TRUE, 10, "Franklin Gothic Book");
					$anterior->setCellValue("BE".$flag1."", "ABARROTES");
					$this->cellStyle("BF".$flag1, "FF0000", "000000", TRUE, 10, "Franklin Gothic Book");
					$anterior->setCellValue("BF".$flag1."", "PEDREGAL");
					$this->cellStyle("BG".$flag1, "E26C0B", "000000", TRUE, 10, "Franklin Gothic Book");
					$anterior->setCellValue("BG".$flag1."", "TIENDA");
					$this->cellStyle("BH".$flag1, "C5C5C5", "000000", TRUE, 10, "Franklin Gothic Book");
					$anterior->setCellValue("BH".$flag1."", "ULTRAMARINOS");
					$this->cellStyle("BI".$flag1, "92D051", "000000", TRUE, 10, "Franklin Gothic Book");
					$anterior->setCellValue("BI".$flag1."", "TRINCHERAS");
					$this->cellStyle("BJ".$flag1, "B1A0C7", "000000", TRUE, 10, "Franklin Gothic Book");
					$anterior->setCellValue("BJ".$flag1."", "MERCADO");
					$this->cellStyle("BK".$flag1, "DA9694", "000000", TRUE, 10, "Franklin Gothic Book");
					$anterior->setCellValue("BK".$flag1."", "TENENCIA");
					$this->cellStyle("BL".$flag1, "4CACC6", "000000", TRUE, 10, "Franklin Gothic Book");
					$anterior->setCellValue("BL".$flag1."", "TIJERAS");
				}
				
				foreach ($infos as $keys => $v) {
					$this->excelfile->setActiveSheetIndex($last);
					$anterior = $this->excelfile->getActiveSheet();
					$flag1++;
					
					$this->excelfile->getActiveSheet()->getStyle('A'.$flag1.':AN'.$flag1)->applyFromArray($styleArray);
					$anterior->setCellValue("A".$flag1."", $v["codigo"])->getStyle("A{$flag1}")->getNumberFormat()->setFormatCode('# ???/???');
					$this->cellStyle("A".$flag1, "FFFFFF", "000000", FALSE, 10, "Franklin Gothic Book");
					$anterior->setCellValue("B".$flag1."", $v["descripcion"]);
					$this->cellStyle("B".$flag1, "FFFFFF", "000000", FALSE, 10, "Franklin Gothic Book");
					$anterior->setCellValue("C{$flag1}", $v["precio"])->getStyle("C{$flag1}")->getNumberFormat()->setFormatCode('"$"#,##0.00_-');
					$this->cellStyle("C".$flag1, "FFFFFF", "000000", FALSE, 10, "Franklin Gothic Book");
					$anterior->setCellValue("D{$flag1}", $v["sistema"])->getStyle("D{$flag1}")->getNumberFormat()->setFormatCode('"$"#,##0.00_-');
					$this->cellStyle("D".$flag1, "FFFFFF", "000000", FALSE, 10, "Franklin Gothic Book");
					$anterior->setCellValue("E{$flag1}", $v["unidad"])->getStyle("E{$flag1}");
					$this->cellStyle("E".$flag1, "FFFFFF", "000000", FALSE, 10, "Franklin Gothic Book");
					$this->excelfile->getActiveSheet()->getStyle('AP'.$flag1.':AZ'.$flag1)->applyFromArray($styleArray);
					$this->excelfile->getActiveSheet()->getStyle('BC'.$flag1.':BL'.$flag1)->applyFromArray($styleArray);
					$this->cellStyle("F{$flag1}:AN{$flag1}", "FFFFFF", "000000", TRUE, 10, "Franklin Gothic Book");
					$this->cellStyle("AP".$flag1, "FFFFFF", "000000", FALSE, 10, "Franklin Gothic Book");
					$anterior->setCellValue("AP{$flag1}", "=C{$flag1}*H{$flag1}")->getStyle("AP{$flag1}")->getNumberFormat()->setFormatCode('"$"#,##0.00_-');
					$this->cellStyle("AQ".$flag1, "FFFFFF", "000000", FALSE, 10, "Franklin Gothic Book");
					$anterior->setCellValue("AQ{$flag1}", "=C{$flag1}*N{$flag1}")->getStyle("AQ{$flag1}")->getNumberFormat()->setFormatCode('"$"#,##0.00_-');
					$this->cellStyle("AR".$flag1, "FFFFFF", "000000", FALSE, 10, "Franklin Gothic Book");
					$anterior->setCellValue("AR{$flag1}", "=C{$flag1}*Q{$flag1}")->getStyle("AR{$flag1}")->getNumberFormat()->setFormatCode('"$"#,##0.00_-');
					$this->cellStyle("AS".$flag1, "FFFFFF", "000000", FALSE, 10, "Franklin Gothic Book");
					$anterior->setCellValue("AS{$flag1}", "=C{$flag1}*T{$flag1}")->getStyle("AS{$flag1}")->getNumberFormat()->setFormatCode('"$"#,##0.00_-');
					$this->cellStyle("AT".$flag1, "FFFFFF", "000000", FALSE, 10, "Franklin Gothic Book");
					$anterior->setCellValue("AT{$flag1}", "=C{$flag1}*W{$flag1}")->getStyle("AT{$flag1}")->getNumberFormat()->setFormatCode('"$"#,##0.00_-');
					$this->cellStyle("AU".$flag1, "FFFFFF", "000000", FALSE, 10, "Franklin Gothic Book");
					$anterior->setCellValue("AU{$flag1}", "=C{$flag1}*Z{$flag1}")->getStyle("AU{$flag1}")->getNumberFormat()->setFormatCode('"$"#,##0.00_-');
					$this->cellStyle("AV".$flag1, "FFFFFF", "000000", FALSE, 10, "Franklin Gothic Book");
					$anterior->setCellValue("AV{$flag1}", "=C{$flag1}*AC{$flag1}")->getStyle("AV{$flag1}")->getNumberFormat()->setFormatCode('"$"#,##0.00_-');
					$this->cellStyle("AW".$flag1, "FFFFFF", "000000", FALSE, 10, "Franklin Gothic Book");
					$anterior->setCellValue("AW{$flag1}", "=C{$flag1}*AF{$flag1}")->getStyle("AW{$flag1}")->getNumberFormat()->setFormatCode('"$"#,##0.00_-');
					$this->cellStyle("AX".$flag1, "FFFFFF", "000000", FALSE, 10, "Franklin Gothic Book");
					$anterior->setCellValue("AX{$flag1}", "=C{$flag1}*AI{$flag1}")->getStyle("AX{$flag1}")->getNumberFormat()->setFormatCode('"$"#,##0.00_-');
					$this->cellStyle("AY".$flag1, "FFFFFF", "000000", FALSE, 10, "Franklin Gothic Book");
					$anterior->setCellValue("AY{$flag1}", "=C{$flag1}*AL{$flag1}")->getStyle("AY{$flag1}")->getNumberFormat()->setFormatCode('"$"#,##0.00_-');
					$this->cellStyle("AZ".$flag1, "FFFFFF", "000000", FALSE, 10, "Franklin Gothic Book");
					$anterior->setCellValue("AZ{$flag1}", "=SUM(AP{$flag1}:AY{$flag1})")->getStyle("AZ{$flag1}")->getNumberFormat()->setFormatCode('"$"#,##0.00_-');


					$this->cellStyle("BC".$flag1, "FFFFFF", "000000", FALSE, 10, "Franklin Gothic Book");
					$anterior->setCellValue("BC{$flag1}", "=(((F{$flag1}+H{$flag1})*E{$flag1})+G{$flag1})/E{$flag1}");
					$this->cellStyle("BD".$flag1, "FFFFFF", "000000", FALSE, 10, "Franklin Gothic Book");
					$anterior->setCellValue("BD{$flag1}", "=(((L{$flag1}+N{$flag1})*E{$flag1})+M{$flag1})/E{$flag1}");
					$this->cellStyle("BE".$flag1, "FFFFFF", "000000", FALSE, 10, "Franklin Gothic Book");
					$anterior->setCellValue("BE{$flag1}", "=(((O{$flag1}+Q{$flag1})*E{$flag1})+P{$flag1})/E{$flag1}");
					$this->cellStyle("BF".$flag1, "FFFFFF", "000000", FALSE, 10, "Franklin Gothic Book");
					$anterior->setCellValue("BF{$flag1}", "=(((R{$flag1}+T{$flag1})*E{$flag1})+S{$flag1})/E{$flag1}");
					$this->cellStyle("BG".$flag1, "FFFFFF", "000000", FALSE, 10, "Franklin Gothic Book");
					$anterior->setCellValue("BG{$flag1}", "=(((U{$flag1}+W{$flag1})*E{$flag1})+V{$flag1})/E{$flag1}");
					$this->cellStyle("BH".$flag1, "FFFFFF", "000000", FALSE, 10, "Franklin Gothic Book");
					$anterior->setCellValue("BH{$flag1}", "=(((X{$flag1}+Z{$flag1})*E{$flag1})+Y{$flag1})/E{$flag1}");
					$this->cellStyle("BI".$flag1, "FFFFFF", "000000", FALSE, 10, "Franklin Gothic Book");
					$anterior->setCellValue("BI{$flag1}", "=(((AA{$flag1}+AC{$flag1})*E{$flag1})+AB{$flag1})/E{$flag1}");
					$this->cellStyle("BJ".$flag1, "FFFFFF", "000000", FALSE, 10, "Franklin Gothic Book");
					$anterior->setCellValue("BJ{$flag1}", "=(((AD{$flag1}+AF{$flag1})*E{$flag1})+AE{$flag1})/E{$flag1}");
					$this->cellStyle("BK".$flag1, "FFFFFF", "000000", FALSE, 10, "Franklin Gothic Book");
					$anterior->setCellValue("BK{$flag1}", "=(((AG{$flag1}+AI{$flag1})*E{$flag1})+AH{$flag1})/E{$flag1}");
					$this->cellStyle("BL".$flag1, "FFFFFF", "000000", FALSE, 10, "Franklin Gothic Book");
					$anterior->setCellValue("BL{$flag1}", "=(((AJ{$flag1}+AL{$flag1})*E{$flag1})+AM{$flag1})/E{$flag1}");

					$this->cellStyle("H".$flag1, "DCE6F1", "000000", TRUE, 10, "Franklin Gothic Book");
					$this->cellStyle("K".$flag1, "DCE6F1", "000000", TRUE, 10, "Franklin Gothic Book");
					$this->cellStyle("N".$flag1, "DCE6F1", "000000", TRUE, 10, "Franklin Gothic Book");
					$this->cellStyle("Q".$flag1, "DCE6F1", "000000", TRUE, 10, "Franklin Gothic Book");
					$this->cellStyle("T".$flag1, "DCE6F1", "000000", TRUE, 10, "Franklin Gothic Book");
					$this->cellStyle("W".$flag1, "DCE6F1", "000000", TRUE, 10, "Franklin Gothic Book");
					$this->cellStyle("Z".$flag1, "DCE6F1", "000000", TRUE, 10, "Franklin Gothic Book");
					$this->cellStyle("AC".$flag1, "DCE6F1", "000000", TRUE, 10, "Franklin Gothic Book");
					$this->cellStyle("AF".$flag1, "DCE6F1", "000000", TRUE, 10, "Franklin Gothic Book");
					$this->cellStyle("AI".$flag1, "DCE6F1", "000000", TRUE, 10, "Franklin Gothic Book");
					$this->cellStyle("AL".$flag1, "DCE6F1", "000000", TRUE, 10, "Franklin Gothic Book");
					

					$col = 5;
					 foreach ($v["exist"] as $k => $vl) {
						$anterior->setCellValueByColumnAndRow($col, $flag1, $vl["cja"]);
						$col++;
						$anterior->setCellValueByColumnAndRow($col, $flag1, $vl["pzs"]);
						$col++;
						$anterior->setCellValueByColumnAndRow($col, $flag1, $vl["ped"]);
						$col++;
					 }
					 


					$this->excelfile->setActiveSheetIndex($key);
					$proveedor[0]->estatus = $this->excelfile->getActiveSheet();
					$flag++;
					$this->excelfile->setActiveSheetIndex($key);
					$this->excelfile->getActiveSheet()->getStyle('A'.$flag.':BJ'.$flag)->applyFromArray($styleArray);
					$proveedor[$key]->estatus->setCellValue("A".$flag."", $v["codigo"])->getStyle("A{$flag}")->getNumberFormat()->setFormatCode('# ???/???');
					$this->cellStyle("A".$flag, "FFFFFF", "000000", FALSE, 10, "Franklin Gothic Book");
					$proveedor[$key]->estatus->setCellValue("B".$flag."", $v["descripcion"]);
					$this->cellStyle("B".$flag, "FFFFFF", "000000", FALSE, 10, "Franklin Gothic Book");
					$proveedor[$key]->estatus->setCellValue("C{$flag}", $v["precio"])->getStyle("C{$flag}")->getNumberFormat()->setFormatCode('"$"#,##0.00_-');
					$this->cellStyle("C".$flag, "FFFFFF", "000000", FALSE, 10, "Franklin Gothic Book");
					$proveedor[$key]->estatus->setCellValue("D{$flag}", $v["sistema"])->getStyle("D{$flag}")->getNumberFormat()->setFormatCode('"$"#,##0.00_-');
					$this->cellStyle("D".$flag, "FFFFFF", "000000", FALSE, 10, "Franklin Gothic Book");
					$proveedor[$key]->estatus->setCellValue("E{$flag}", $v["unidad"]);
					$this->cellStyle("E".$flag, "FFFFFF", "000000", FALSE, 10, "Franklin Gothic Book");

					$proveedor[$key]->estatus->setCellValue("F{$flag}", "=VLOOKUP(A{$flag},ANTERIOR!A:BL,55,FALSE)");
					$proveedor[$key]->estatus->setCellValue("P{$flag}", "=VLOOKUP(A{$flag},ANTERIOR!A:BL,56,FALSE)");
					$proveedor[$key]->estatus->setCellValue("U{$flag}", "=VLOOKUP(A{$flag},ANTERIOR!A:BL,57,FALSE)");
					$proveedor[$key]->estatus->setCellValue("Z{$flag}", "=VLOOKUP(A{$flag},ANTERIOR!A:BL,58,FALSE)");
					$proveedor[$key]->estatus->setCellValue("AE{$flag}", "=VLOOKUP(A{$flag},ANTERIOR!A:BL,59,FALSE)");
					$proveedor[$key]->estatus->setCellValue("AJ{$flag}", "=VLOOKUP(A{$flag},ANTERIOR!A:BL,60,FALSE)");
					$proveedor[$key]->estatus->setCellValue("AO{$flag}", "=VLOOKUP(A{$flag},ANTERIOR!A:BL,61,FALSE)");
					$proveedor[$key]->estatus->setCellValue("AT{$flag}", "=VLOOKUP(A{$flag},ANTERIOR!A:BL,62,FALSE)");
					$proveedor[$key]->estatus->setCellValue("AY{$flag}", "=VLOOKUP(A{$flag},ANTERIOR!A:BL,63,FALSE)");
					$proveedor[$key]->estatus->setCellValue("BD{$flag}", "=VLOOKUP(A{$flag},ANTERIOR!A:BL,64,FALSE)");

					$proveedor[$key]->estatus->setCellValue("G{$flag}", "=((F{$flag}*E{$flag})-(((H{$flag}*E{$flag})+I{$flag})))/E{$flag}");
					$proveedor[$key]->estatus->setCellValue("Q{$flag}", "=((P{$flag}*E{$flag})-(((R{$flag}*E{$flag})+S{$flag})))/E{$flag}");
					$proveedor[$key]->estatus->setCellValue("V{$flag}", "=((U{$flag}*E{$flag})-(((W{$flag}*E{$flag})+X{$flag})))/E{$flag}");
					$proveedor[$key]->estatus->setCellValue("AA{$flag}", "=((Z{$flag}*E{$flag})-(((AB{$flag}*E{$flag})+AC{$flag})))/E{$flag}");
					$proveedor[$key]->estatus->setCellValue("AF{$flag}", "=((AE{$flag}*E{$flag})-(((AG{$flag}*E{$flag})+AH{$flag})))/E{$flag}");
					$proveedor[$key]->estatus->setCellValue("AK{$flag}", "=((AJ{$flag}*E{$flag})-(((AL{$flag}*E{$flag})+AM{$flag})))/E{$flag}");
					$proveedor[$key]->estatus->setCellValue("AP{$flag}", "=((AO{$flag}*E{$flag})-(((AQ{$flag}*E{$flag})+AR{$flag})))/E{$flag}");
					$proveedor[$key]->estatus->setCellValue("AU{$flag}", "=((AT{$flag}*E{$flag})-(((AV{$flag}*E{$flag})+AW{$flag})))/E{$flag}");
					$proveedor[$key]->estatus->setCellValue("AZ{$flag}", "=((AY{$flag}*E{$flag})-(((BA{$flag}*E{$flag})+BB{$flag})))/E{$flag}");
					$proveedor[$key]->estatus->setCellValue("BE{$flag}", "=((BD{$flag}*E{$flag})-(((BF{$flag}*E{$flag})+BG{$flag})))/E{$flag}");
					
					

					$this->cellStyle("F{$flag}:BH{$flag}", "FFFFFF", "000000", TRUE, 10, "Franklin Gothic Book");
					$this->cellStyle("J".$flag, "DCE6F1", "000000", TRUE, 10, "Franklin Gothic Book");
					$this->cellStyle("O".$flag, "DCE6F1", "000000", TRUE, 10, "Franklin Gothic Book");
					$this->cellStyle("T".$flag, "DCE6F1", "000000", TRUE, 10, "Franklin Gothic Book");
					$this->cellStyle("Y".$flag, "DCE6F1", "000000", TRUE, 10, "Franklin Gothic Book");
					$this->cellStyle("AD".$flag, "DCE6F1", "000000", TRUE, 10, "Franklin Gothic Book");
					$this->cellStyle("AI".$flag, "DCE6F1", "000000", TRUE, 10, "Franklin Gothic Book");
					$this->cellStyle("AN".$flag, "DCE6F1", "000000", TRUE, 10, "Franklin Gothic Book");
					$this->cellStyle("AS".$flag, "DCE6F1", "000000", TRUE, 10, "Franklin Gothic Book");
					$this->cellStyle("AX".$flag, "DCE6F1", "000000", TRUE, 10, "Franklin Gothic Book");
					$this->cellStyle("BC".$flag, "DCE6F1", "000000", TRUE, 10, "Franklin Gothic Book");
					$this->cellStyle("BH".$flag, "DCE6F1", "000000", TRUE, 10, "Franklin Gothic Book");

					$this->excelfile->getActiveSheet()->getStyle('BL'.$flag.':BV'.$flag)->applyFromArray($styleArray);
					$this->cellStyle("BL".$flag, "FFFFFF", "000000", FALSE, 10, "Franklin Gothic Book");
					$proveedor[$key]->estatus->setCellValue("BL{$flag}", "=C{$flag}*J{$flag}")->getStyle("BL{$flag}")->getNumberFormat()->setFormatCode('"$"#,##0.00_-');
					$this->cellStyle("BM".$flag, "FFFFFF", "000000", FALSE, 10, "Franklin Gothic Book");
					$proveedor[$key]->estatus->setCellValue("BM{$flag}", "=C{$flag}*T{$flag}")->getStyle("BM{$flag}")->getNumberFormat()->setFormatCode('"$"#,##0.00_-');
					$this->cellStyle("BN".$flag, "FFFFFF", "000000", FALSE, 10, "Franklin Gothic Book");
					$proveedor[$key]->estatus->setCellValue("BN{$flag}", "=C{$flag}*Y{$flag}")->getStyle("BN{$flag}")->getNumberFormat()->setFormatCode('"$"#,##0.00_-');
					$this->cellStyle("BO".$flag, "FFFFFF", "000000", FALSE, 10, "Franklin Gothic Book");
					$proveedor[$key]->estatus->setCellValue("BO{$flag}", "=C{$flag}*AD{$flag}")->getStyle("BO{$flag}")->getNumberFormat()->setFormatCode('"$"#,##0.00_-');
					$this->cellStyle("BP".$flag, "FFFFFF", "000000", FALSE, 10, "Franklin Gothic Book");
					$proveedor[$key]->estatus->setCellValue("BP{$flag}", "=C{$flag}*AI{$flag}")->getStyle("BP{$flag}")->getNumberFormat()->setFormatCode('"$"#,##0.00_-');
					$this->cellStyle("BQ".$flag, "FFFFFF", "000000", FALSE, 10, "Franklin Gothic Book");
					$proveedor[$key]->estatus->setCellValue("BQ{$flag}", "=C{$flag}*AN{$flag}")->getStyle("BQ{$flag}")->getNumberFormat()->setFormatCode('"$"#,##0.00_-');
					$this->cellStyle("BR".$flag, "FFFFFF", "000000", FALSE, 10, "Franklin Gothic Book");
					$proveedor[$key]->estatus->setCellValue("BR{$flag}", "=C{$flag}*AS{$flag}")->getStyle("BR{$flag}")->getNumberFormat()->setFormatCode('"$"#,##0.00_-');
					$this->cellStyle("BS".$flag, "FFFFFF", "000000", FALSE, 10, "Franklin Gothic Book");
					$proveedor[$key]->estatus->setCellValue("BS{$flag}", "=C{$flag}*AX{$flag}")->getStyle("BS{$flag}")->getNumberFormat()->setFormatCode('"$"#,##0.00_-');
					$this->cellStyle("BT".$flag, "FFFFFF", "000000", FALSE, 10, "Franklin Gothic Book");
					$proveedor[$key]->estatus->setCellValue("BT{$flag}", "=C{$flag}*BC{$flag}")->getStyle("BT{$flag}")->getNumberFormat()->setFormatCode('"$"#,##0.00_-');
					$this->cellStyle("BU".$flag, "FFFFFF", "000000", FALSE, 10, "Franklin Gothic Book");
					$proveedor[$key]->estatus->setCellValue("BU{$flag}", "=C{$flag}*BH{$flag}")->getStyle("BU{$flag}")->getNumberFormat()->setFormatCode('"$"#,##0.00_-');
					$this->cellStyle("BV".$flag, "FFFFFF", "000000", FALSE, 10, "Franklin Gothic Book");
					$proveedor[$key]->estatus->setCellValue("BV{$flag}", "=SUM(BL{$flag}:BU{$flag})")->getStyle("BV{$flag}")->getNumberFormat()->setFormatCode('"$"#,##0.00_-');

					$col = 7;
					 foreach ($v["existencias"] as $k => $vs) {
						$proveedor[$key]->estatus->setCellValueByColumnAndRow($col, $flag, $vs["cja"]);
						$col++;
						$proveedor[$key]->estatus->setCellValueByColumnAndRow($col, $flag, $vs["pzs"]);
						$col++;
						$proveedor[$key]->estatus->setCellValueByColumnAndRow($col, $flag, $vs["ped"]);
						$col+=3;
					 }

					 $proveedor[$key]->estatus->setCellValue("K{$flag}", "=F{$flag}+P{$flag}");
					 $proveedor[$key]->estatus->setCellValue("L{$flag}", "=G{$flag}+Q{$flag}");
					 $proveedor[$key]->estatus->setCellValue("M{$flag}", "=H{$flag}+R{$flag}");
					 $proveedor[$key]->estatus->setCellValue("N{$flag}", "=I{$flag}+S{$flag}");
					 $proveedor[$key]->estatus->setCellValue("O{$flag}", "=J{$flag}+T{$flag}");
					
				}
			$flag1 += 5;
			}
		}
		$this->excelfile->setActiveSheetIndex(0);

        $dias = array("DOMINGO","LUNES","MARTES","MIÉRCOLES","JUEVES","VIERNES","SÁBADO");
		$meses = array("ENERO","FEBRERO","MARZO","ABRIL","MAYO","JUNIO","JULIO","AGOSTO","SEPTIEMBRE","OCTUBRE","NOVIEMBRE","DICIEMBRE");
		$fecha =  $dias[date('w')]." ".date('d')." DE ".$meses[date('n')-1]. " DEL ".date('Y') ;
		$file_name = "FORMATO LUNES ".$fecha.".xlsx"; //Nombre del documento con extención
		header("Content-Type: application/vnd.ms-excel; charset=utf-8");
		header("Content-Disposition: attachment;filename=".$file_name);
		header("Cache-Control: max-age=0");
		$excel_Writer = PHPExcel_IOFactory::createWriter($this->excelfile, "Excel2007");
		$excel_Writer->save("php://output");
	}
}

/* End of file Lunes.php */
/* Location: ./application/controllers/Lunes.php */
