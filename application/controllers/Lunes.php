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
		$this->load->model("Productos_model", "prod_mdl");
		$this->load->model("Pendlunes_model", "pend_mdl");
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
		$ordenes = $this->prolu_md->getMaxOrden(NULL)[0];
		$orden = ($ordenes->ordenes+1);
		$producto = [
			"codigo"	=>	strtoupper($this->input->post('codigo')),
			"descripcion"	=>	strtoupper($this->input->post('descripcion')),
			"precio"	=>	$this->input->post('precio'),
			"sistema"	=>	$this->input->post('sistema'),
			"observaciones"	=>	$this->input->post('observaciones'),
			"id_proveedor"	=>	$this->input->post('id_proveedor'),
			"unidad"	=>	$this->input->post('unidad'),
			"orden"		=> $orden
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
				"desc"	=> 'El Producto ['.$producto['descripcion'].'] está registrado en el Sistema',
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
		$data["button"]="<button class='btn btn-danger delete_producto' type='button'>
							<span class='bold'><i class='fa fa-times'></i></span> &nbsp;Estoy segura(o) de eliminar
						</button>";
		$this->jsonResponse($data);
	}

	public function delete_prod(){
		$user = $this->session->userdata();
		$antes = $this->prolu_md->get(NULL, ['codigo'=>$this->input->post('id_producto')])[0];
		$cambios = [
				"id_usuario" => $user["id_usuario"],
				"fecha_cambio" => date('Y-m-d H:i:s'),
				"antes" => "Código : ".$antes->codigo." /Descripción: ".$antes->descripcion,
				"despues" => "El Producto fue eliminado, se puede recuperar desde la BD"];
		$data['cambios'] = $this->cambio_md->insert($cambios);
		$data ['id_usuario'] = $this->prolu_md->update(["estatus" => 0], $antes->codigo);
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
			"observaciones"	=>	$this->input->post('observaciones'),
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
        $config['max_size']             = 1000;
        $config['max_width']            = 10024;
        $config['max_height']           = 7608;
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
        $config['max_size']             = 1000;
        $config['max_width']            = 10024;
        $config['max_height']           = 7068;
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
		$filen = "Precios Proveedores Lunes";
		$config['upload_path']          = './assets/uploads/cotizaciones/';
        $config['allowed_types']        = 'xlsx|xls';
        $config['max_size']             = 1000;
        $config['max_width']            = 10204;
        $config['max_height']           = 7068;
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
		$last = 1;
		$this->excelfile->createSheet();
		$totales = $this->excelfile->setActiveSheetIndex(0)->setTitle("PEDIDOS LUNES");
		/*foreach ($proveedor as $ke => $value) {
			$last++;
			$this->excelfile->createSheet();
        	$proveedor[$ke]->estatus = $this->excelfile->setActiveSheetIndex($ke+1)->setTitle($value->alias);
		}*/
		$this->excelfile->createSheet();
		$anterior = $this->excelfile->setActiveSheetIndex($last)->setTitle("ANTERIOR");

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

		$ced="=";$sup="=";$aba="=";$ped="=";$tie="=";$ult="=";$tri="=";$mer="=";$ten="=";$tij="=";

		foreach ($proveedor as $key => $va) {
			$infos = $this->prolu_md->printProdis(NULL,$va->id_proveedor,$tiendas->total);
			if ($infos) {
				if (1 == 1) {
					$key = 0;
					$this->excelfile->setActiveSheetIndex($key);
					$proveedor[$key]->estatus = $this->excelfile->getActiveSheet();
					$proveedor[$key]->estatus->mergeCells('A'.$flag.':BU'.$flag);
					$this->cellStyle("A".$flag, "FFFFFF", "000000", TRUE, 12, "Franklin Gothic Book");
					$proveedor[$key]->estatus->setCellValue("A".$flag."", "CEDIS, ABARROTES,PEDREGAL, TIENDA, ULTRAMARINOS, TRINCHERAS, MERCADO, TIJERAS, Y TENENCIA AZTECA AUTOSERVICIOS SA. DE CV.");
					$this->excelfile->getActiveSheet()->getStyle('A'.$flag.':BU'.$flag)->applyFromArray($styleArray);
					$flag++;
					$this->excelfile->getActiveSheet()->getStyle('A'.$flag.':BU'.$flag)->applyFromArray($styleArray);
					$proveedor[$key]->estatus->getColumnDimension('A')->setWidth("25");
					$proveedor[$key]->estatus->getColumnDimension('C')->setWidth("70");
					$proveedor[$key]->estatus->getColumnDimension('G')->setWidth("15");
					$proveedor[$key]->estatus->getColumnDimension('M')->setWidth("15");
					$proveedor[$key]->estatus->getColumnDimension('S')->setWidth("15");
					$proveedor[$key]->estatus->getColumnDimension('Y')->setWidth("15");
					$proveedor[$key]->estatus->getColumnDimension('AE')->setWidth("15");
					$proveedor[$key]->estatus->getColumnDimension('AK')->setWidth("15");
					$proveedor[$key]->estatus->getColumnDimension('AQ')->setWidth("15");
					$proveedor[$key]->estatus->getColumnDimension('AW')->setWidth("15");
					$proveedor[$key]->estatus->getColumnDimension('BC')->setWidth("15");
					$proveedor[$key]->estatus->getColumnDimension('BI')->setWidth("15");
					$proveedor[$key]->estatus->getColumnDimension('BO')->setWidth("15");
					$proveedor[$key]->estatus->getColumnDimension('BU')->setWidth("40");
					$proveedor[$key]->estatus->getColumnDimension('BV')->setWidth("40");
					$proveedor[$key]->estatus->getColumnDimension('D')->setWidth("15");
					$proveedor[$key]->estatus->getColumnDimension('E')->setWidth("15");
					$proveedor[$key]->estatus->getColumnDimension('F')->setWidth("15");
					$proveedor[$key]->estatus->mergeCells('A'.$flag.':C'.$flag);
					$this->cellStyle("A".$flag, "FFFFFF", "000000", TRUE, 12, "Franklin Gothic Book");
					$proveedor[$key]->estatus->setCellValue("A".$flag."", "PEDIDO A ".$va->nombre);
					$proveedor[$key]->estatus->mergeCells('D'.$flag.':F'.$flag);
					$this->cellStyle("D".$flag, "FFFFFF", "000000", TRUE, 12, "Franklin Gothic Book");
					$proveedor[$key]->estatus->setCellValue("D".$flag."", $fecha);

					$proveedor[$key]->estatus->mergeCells('G'.$flag.':L'.$flag);
					$this->cellStyle("G".$flag, "C00000", "000000", TRUE, 12, "Franklin Gothic Book");
					$proveedor[$key]->estatus->setCellValue("G".$flag, "CEDIS/SUPER");
					$proveedor[$key]->estatus->mergeCells('M'.$flag.':R'.$flag);
					$this->cellStyle("M".$flag, "C2B90A", "000000", TRUE, 12, "Franklin Gothic Book");
					$proveedor[$key]->estatus->setCellValue("M".$flag, "SUMA CEDIS/SUPER");
					$proveedor[$key]->estatus->mergeCells('S'.$flag.':X'.$flag);
					$proveedor[$key]->estatus->setCellValue("S".$flag, "CD INDUSTRIAL");
					$this->cellStyle("S".$flag, "FF0066", "000000", TRUE, 12, "Franklin Gothic Book");
					$proveedor[$key]->estatus->mergeCells('Y'.$flag.':AD'.$flag);
					$this->cellStyle("Y".$flag, "01B0F0", "000000", TRUE, 12, "Franklin Gothic Book");
					$proveedor[$key]->estatus->setCellValue("Y".$flag, "ABARROTES");
					$proveedor[$key]->estatus->mergeCells('AE'.$flag.':AJ'.$flag);
					$this->cellStyle("AE".$flag, "FF0000", "000000", TRUE, 12, "Franklin Gothic Book");
					$proveedor[$key]->estatus->setCellValue("AE".$flag, "PEDREGAL");
					$proveedor[$key]->estatus->mergeCells('AK'.$flag.':AP'.$flag);
					$this->cellStyle("AK".$flag, "E26C0B", "000000", TRUE, 12, "Franklin Gothic Book");
					$proveedor[$key]->estatus->setCellValue("AK".$flag, "TIENDA");
					$proveedor[$key]->estatus->mergeCells('AQ'.$flag.':AV'.$flag);
					$this->cellStyle("AQ".$flag, "C5C5C5", "000000", TRUE, 12, "Franklin Gothic Book");
					$proveedor[$key]->estatus->setCellValue("AQ".$flag, "ULTRAMARINOS");
					$proveedor[$key]->estatus->mergeCells('AW'.$flag.':BB'.$flag);
					$this->cellStyle("AW".$flag, "92D051", "000000", TRUE, 12, "Franklin Gothic Book");
					$proveedor[$key]->estatus->setCellValue("AW".$flag, "TRINCHERAS");
					$proveedor[$key]->estatus->mergeCells('BC'.$flag.':BH'.$flag);
					$this->cellStyle("BC".$flag, "B1A0C7", "000000", TRUE, 12, "Franklin Gothic Book");
					$proveedor[$key]->estatus->setCellValue("BC".$flag, "AZT MERCADO");
					$proveedor[$key]->estatus->mergeCells('BI'.$flag.':BN'.$flag);
					$this->cellStyle("BI".$flag, "DA9694", "000000", TRUE, 12, "Franklin Gothic Book");
					$proveedor[$key]->estatus->setCellValue("BI".$flag, "TENENCIA");
					$proveedor[$key]->estatus->mergeCells('BO'.$flag.':BT'.$flag);
					$this->cellStyle("BO".$flag, "4CACC6", "000000", TRUE, 12, "Franklin Gothic Book");
					$proveedor[$key]->estatus->setCellValue("BO".$flag, "TIJERAS");
					$this->cellStyle("BU".$flag, "FFFF00", "FF0000", TRUE, 12, "Franklin Gothic Book");
					$proveedor[$key]->estatus->setCellValue("BU".$flag, "PROMOCIÓN");
					$this->cellStyle("BV".$flag, "92D050", "FF0000", TRUE, 12, "Franklin Gothic Book");
					$proveedor[$key]->estatus->setCellValue("BV".$flag, "NOTA");

					$flag++;
					$this->excelfile->getActiveSheet()->getStyle('A'.$flag.':BV'.$flag)->applyFromArray($styleArray);
					$proveedor[$key]->estatus->mergeCells('A'.$flag.':F'.$flag);
					$this->cellStyle("A".$flag, "000000", "FFFFFF", TRUE, 12, "Franklin Gothic Book");
					$proveedor[$key]->estatus->setCellValue("A".$flag."", "DESCRIPCIÓN");
					$proveedor[$key]->estatus->mergeCells('G'.$flag.':L'.$flag);
					$this->cellStyle("G".$flag, "000000", "FFFFFF", TRUE, 12, "Franklin Gothic Book");
					$proveedor[$key]->estatus->setCellValue("G".$flag."", "EXISTENCIAS");
					$proveedor[$key]->estatus->mergeCells('M'.$flag.':R'.$flag);
					$this->cellStyle("M".$flag, "000000", "FFFFFF", TRUE, 12, "Franklin Gothic Book");
					$proveedor[$key]->estatus->setCellValue("M".$flag."", "EXISTENCIAS");
					$proveedor[$key]->estatus->mergeCells('S'.$flag.':X'.$flag);
					$this->cellStyle("S".$flag, "000000", "FFFFFF", TRUE, 12, "Franklin Gothic Book");
					$proveedor[$key]->estatus->setCellValue("S".$flag."", "EXISTENCIAS");
					$proveedor[$key]->estatus->mergeCells('Y'.$flag.':AD'.$flag);
					$this->cellStyle("Y".$flag, "000000", "FFFFFF", TRUE, 12, "Franklin Gothic Book");
					$proveedor[$key]->estatus->setCellValue("Y".$flag."", "EXISTENCIAS");
					$proveedor[$key]->estatus->mergeCells('AE'.$flag.':AJ'.$flag);
					$this->cellStyle("AE".$flag, "000000", "FFFFFF", TRUE, 12, "Franklin Gothic Book");
					$proveedor[$key]->estatus->setCellValue("AE".$flag."", "EXISTENCIAS");
					$proveedor[$key]->estatus->mergeCells('AK'.$flag.':AP'.$flag);
					$this->cellStyle("AK".$flag, "000000", "FFFFFF", TRUE, 12, "Franklin Gothic Book");
					$proveedor[$key]->estatus->setCellValue("AK".$flag."", "EXISTENCIAS");
					$proveedor[$key]->estatus->mergeCells('AQ'.$flag.':AV'.$flag);
					$this->cellStyle("AQ".$flag, "000000", "FFFFFF", TRUE, 12, "Franklin Gothic Book");
					$proveedor[$key]->estatus->setCellValue("AQ".$flag."", "EXISTENCIAS");
					$proveedor[$key]->estatus->mergeCells('AW'.$flag.':BB'.$flag);
					$this->cellStyle("AW".$flag, "000000", "FFFFFF", TRUE, 12, "Franklin Gothic Book");
					$proveedor[$key]->estatus->setCellValue("AW".$flag."", "EXISTENCIAS");
					$proveedor[$key]->estatus->mergeCells('BC'.$flag.':BH'.$flag);
					$this->cellStyle("BC".$flag, "000000", "FFFFFF", TRUE, 12, "Franklin Gothic Book");
					$proveedor[$key]->estatus->setCellValue("BC".$flag."", "EXISTENCIAS");
					$proveedor[$key]->estatus->mergeCells('BI'.$flag.':BN'.$flag);
					$this->cellStyle("BI".$flag, "000000", "FFFFFF", TRUE, 12, "Franklin Gothic Book");
					$proveedor[$key]->estatus->setCellValue("BI".$flag."", "EXISTENCIAS");
					$proveedor[$key]->estatus->mergeCells('BO'.$flag.':BT'.$flag);
					$this->cellStyle("BO".$flag, "000000", "FFFFFF", TRUE, 12, "Franklin Gothic Book");
					$proveedor[$key]->estatus->setCellValue("BO".$flag."", "EXISTENCIAS");
					$this->cellStyle("BU".$flag, "FFFF00", "FF0000", TRUE, 12, "Franklin Gothic Book");
					$this->cellStyle("BV".$flag, "92D050", "FF0000", TRUE, 12, "Franklin Gothic Book");
					
					$flag++;
					$this->excelfile->getActiveSheet()->getStyle('A'.$flag.':BV'.$flag)->applyFromArray($styleArray);
					$proveedor[$key]->estatus->mergeCells('A'.$flag.':C'.$flag);
					$this->cellStyle("A".$flag, "000000", "FFFFFF", TRUE, 12, "Franklin Gothic Book");
					$this->cellStyle("D".$flag, "000000", "FFFFFF", FALSE, 10, "Franklin Gothic Book");
					$proveedor[$key]->estatus->setCellValue("D".$flag."", "PRECIO");
					$this->cellStyle("E".$flag, "000000", "FFFFFF", FALSE, 10, "Franklin Gothic Book");
					$proveedor[$key]->estatus->setCellValue("E".$flag."", "SISTEMA");
					$this->cellStyle("F".$flag, "000000", "FFFFFF", FALSE, 10, "Franklin Gothic Book");
					$proveedor[$key]->estatus->setCellValue("F".$flag."", "UM");
					$this->cellStyle("G".$flag, "000000", "FFFFFF", FALSE, 10, "Franklin Gothic Book");
					$proveedor[$key]->estatus->setCellValue("G".$flag."", "Pedido anterior");
					$this->cellStyle("H".$flag, "000000", "FF0000", FALSE, 10, "Franklin Gothic Book");
					$proveedor[$key]->estatus->setCellValue("H".$flag."", "Sugerido");
					$this->cellStyle("I".$flag, "000000", "FFFFFF", FALSE, 10, "Franklin Gothic Book");
					$proveedor[$key]->estatus->setCellValue("I".$flag."", "Pendiente");
					$this->cellStyle("J".$flag, "000000", "FFFFFF", FALSE, 10, "Franklin Gothic Book");
					$proveedor[$key]->estatus->setCellValue("J".$flag."", "Cajas");
					$this->cellStyle("K".$flag, "000000", "FFFFFF", FALSE, 10, "Franklin Gothic Book");
					$proveedor[$key]->estatus->setCellValue("K".$flag."", "Pzs");
					$this->cellStyle("L".$flag, "000000", "FFFFFF", FALSE, 10, "Franklin Gothic Book");
					$proveedor[$key]->estatus->setCellValue("L".$flag."", "Pedido");
					$this->cellStyle("M".$flag, "000000", "FFFFFF", FALSE, 10, "Franklin Gothic Book");
					$proveedor[$key]->estatus->setCellValue("M".$flag."", "Pedido anterior");
					$this->cellStyle("N".$flag, "000000", "FF0000", FALSE, 10, "Franklin Gothic Book");
					$proveedor[$key]->estatus->setCellValue("N".$flag."", "Sugerido");
					$this->cellStyle("O".$flag, "000000", "FFFFFF", FALSE, 10, "Franklin Gothic Book");
					$proveedor[$key]->estatus->setCellValue("O".$flag."", "Pendiente");
					$this->cellStyle("P".$flag, "000000", "FFFFFF", FALSE, 10, "Franklin Gothic Book");
					$proveedor[$key]->estatus->setCellValue("P".$flag."", "Cajas");
					$this->cellStyle("Q".$flag, "000000", "FFFFFF", FALSE, 10, "Franklin Gothic Book");
					$proveedor[$key]->estatus->setCellValue("Q".$flag."", "Pzs");
					$this->cellStyle("R".$flag, "000000", "FFFFFF", FALSE, 10, "Franklin Gothic Book");
					$proveedor[$key]->estatus->setCellValue("R".$flag."", "Pedido");////a
					$this->cellStyle("S".$flag, "000000", "FFFFFF", FALSE, 10, "Franklin Gothic Book");
					$proveedor[$key]->estatus->setCellValue("S".$flag."", "Pedido anterior");
					$this->cellStyle("T".$flag, "000000", "FF0000", FALSE, 10, "Franklin Gothic Book");
					$proveedor[$key]->estatus->setCellValue("T".$flag."", "Sugerido");
					$this->cellStyle("U".$flag, "000000", "FFFFFF", FALSE, 10, "Franklin Gothic Book");
					$proveedor[$key]->estatus->setCellValue("U".$flag."", "Pendiente");
					$this->cellStyle("V".$flag, "000000", "FFFFFF", FALSE, 10, "Franklin Gothic Book");
					$proveedor[$key]->estatus->setCellValue("V".$flag."", "Cajas");
					$this->cellStyle("W".$flag, "000000", "FFFFFF", FALSE, 10, "Franklin Gothic Book");
					$proveedor[$key]->estatus->setCellValue("W".$flag."", "Pzs");
					$this->cellStyle("X".$flag, "000000", "FFFFFF", FALSE, 10, "Franklin Gothic Book");
					$proveedor[$key]->estatus->setCellValue("X".$flag."", "Pedido");
					$this->cellStyle("Y".$flag, "000000", "FFFFFF", FALSE, 10, "Franklin Gothic Book");
					$proveedor[$key]->estatus->setCellValue("Y".$flag."", "Pedido anterior");
					$this->cellStyle("Z".$flag, "000000", "FF0000", FALSE, 10, "Franklin Gothic Book");
					$proveedor[$key]->estatus->setCellValue("Z".$flag."", "Sugerido");
					$this->cellStyle("AA".$flag, "000000", "FFFFFF", FALSE, 10, "Franklin Gothic Book");
					$proveedor[$key]->estatus->setCellValue("AA".$flag."", "Pendiente");
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
					$proveedor[$key]->estatus->setCellValue("AG".$flag."", "Pendiente");
					$this->cellStyle("AH".$flag, "000000", "FFFFFF", FALSE, 10, "Franklin Gothic Book");
					$proveedor[$key]->estatus->setCellValue("AH".$flag."", "Cajas");
					$this->cellStyle("AI".$flag, "000000", "FFFFFF", FALSE, 10, "Franklin Gothic Book");
					$proveedor[$key]->estatus->setCellValue("AI".$flag."", "Pzs");
					$this->cellStyle("AJ".$flag, "000000", "FFFFFF", FALSE, 10, "Franklin Gothic Book");
					$proveedor[$key]->estatus->setCellValue("AJ".$flag."", "Pedido");
					$this->cellStyle("AK".$flag, "000000", "FFFFFF", FALSE, 10, "Franklin Gothic Book");
					$proveedor[$key]->estatus->setCellValue("AK".$flag."", "Pedido anterior");
					$this->cellStyle("AL".$flag, "000000", "FF0000", FALSE, 10, "Franklin Gothic Book");
					$proveedor[$key]->estatus->setCellValue("AL".$flag."", "Sugerido");
					$this->cellStyle("AM".$flag, "000000", "FFFFFF", FALSE, 10, "Franklin Gothic Book");
					$proveedor[$key]->estatus->setCellValue("AM".$flag."", "Pendiente");
					$this->cellStyle("AN".$flag, "000000", "FFFFFF", FALSE, 10, "Franklin Gothic Book");
					$proveedor[$key]->estatus->setCellValue("AN".$flag."", "Cajas");
					$this->cellStyle("AO".$flag, "000000", "FFFFFF", FALSE, 10, "Franklin Gothic Book");
					$proveedor[$key]->estatus->setCellValue("AO".$flag."", "Pzs");
					$this->cellStyle("AP".$flag, "000000", "FFFFFF", FALSE, 10, "Franklin Gothic Book");
					$proveedor[$key]->estatus->setCellValue("AP".$flag."", "Pedido");
					$this->cellStyle("AQ".$flag, "000000", "FFFFFF", FALSE, 10, "Franklin Gothic Book");
					$proveedor[$key]->estatus->setCellValue("AQ".$flag."", "Pedido anterior");
					$this->cellStyle("AR".$flag, "000000", "FF0000", FALSE, 10, "Franklin Gothic Book");
					$proveedor[$key]->estatus->setCellValue("AR".$flag."", "Sugerido");
					$this->cellStyle("AS".$flag, "000000", "FFFFFF", FALSE, 10, "Franklin Gothic Book");
					$proveedor[$key]->estatus->setCellValue("AS".$flag."", "Pendiente");
					$this->cellStyle("AT".$flag, "000000", "FFFFFF", FALSE, 10, "Franklin Gothic Book");
					$proveedor[$key]->estatus->setCellValue("AT".$flag."", "Cajas");
					$this->cellStyle("AU".$flag, "000000", "FFFFFF", FALSE, 10, "Franklin Gothic Book");
					$proveedor[$key]->estatus->setCellValue("AU".$flag."", "Pzs");
					$this->cellStyle("AV".$flag, "000000", "FFFFFF", FALSE, 10, "Franklin Gothic Book");
					$proveedor[$key]->estatus->setCellValue("AV".$flag."", "Pedido");
					$this->cellStyle("AW".$flag, "000000", "FFFFFF", FALSE, 10, "Franklin Gothic Book");
					$proveedor[$key]->estatus->setCellValue("AW".$flag."", "Pedido anterior");
					$this->cellStyle("AX".$flag, "000000", "FF0000", FALSE, 10, "Franklin Gothic Book");
					$proveedor[$key]->estatus->setCellValue("AX".$flag."", "Sugerido");
					$this->cellStyle("AY".$flag, "000000", "FFFFFF", FALSE, 10, "Franklin Gothic Book");
					$proveedor[$key]->estatus->setCellValue("AY".$flag."", "Pendiente");
					$this->cellStyle("AZ".$flag, "000000", "FFFFFF", FALSE, 10, "Franklin Gothic Book");
					$proveedor[$key]->estatus->setCellValue("AZ".$flag."", "Cajas");
					$this->cellStyle("BA".$flag, "000000", "FFFFFF", FALSE, 10, "Franklin Gothic Book");
					$proveedor[$key]->estatus->setCellValue("BA".$flag."", "Pzs");
					$this->cellStyle("BB".$flag, "000000", "FFFFFF", FALSE, 10, "Franklin Gothic Book");
					$proveedor[$key]->estatus->setCellValue("BB".$flag."", "Pedido");
					$this->cellStyle("BC".$flag, "000000", "FFFFFF", FALSE, 10, "Franklin Gothic Book");
					$proveedor[$key]->estatus->setCellValue("BC".$flag."", "Pedido anterior");
					$this->cellStyle("BD".$flag, "000000", "FF0000", FALSE, 10, "Franklin Gothic Book");
					$proveedor[$key]->estatus->setCellValue("BD".$flag."", "Sugerido");
					$this->cellStyle("BE".$flag, "000000", "FFFFFF", FALSE, 10, "Franklin Gothic Book");
					$proveedor[$key]->estatus->setCellValue("BE".$flag."", "Pendiente");
					$this->cellStyle("BF".$flag, "000000", "FFFFFF", FALSE, 10, "Franklin Gothic Book");
					$proveedor[$key]->estatus->setCellValue("BF".$flag."", "Cajas");
					$this->cellStyle("BG".$flag, "000000", "FFFFFF", FALSE, 10, "Franklin Gothic Book");
					$proveedor[$key]->estatus->setCellValue("BG".$flag."", "Pzs");
					$this->cellStyle("BH".$flag, "000000", "FFFFFF", FALSE, 10, "Franklin Gothic Book");
					$proveedor[$key]->estatus->setCellValue("BH".$flag."", "Pedido");
					$this->cellStyle("BI".$flag, "000000", "FFFFFF", FALSE, 10, "Franklin Gothic Book");
					$proveedor[$key]->estatus->setCellValue("BI".$flag."", "Pedido anterior");
					$this->cellStyle("BJ".$flag, "000000", "FF0000", FALSE, 10, "Franklin Gothic Book");
					$proveedor[$key]->estatus->setCellValue("BJ".$flag."", "Sugerido");
					$this->cellStyle("BK".$flag, "000000", "FFFFFF", FALSE, 10, "Franklin Gothic Book");
					$proveedor[$key]->estatus->setCellValue("BK".$flag."", "Pendiente");
					$this->cellStyle("BL".$flag, "000000", "FFFFFF", FALSE, 10, "Franklin Gothic Book");
					$proveedor[$key]->estatus->setCellValue("BL".$flag."", "Cajas");
					$this->cellStyle("BM".$flag, "000000", "FFFFFF", FALSE, 10, "Franklin Gothic Book");
					$proveedor[$key]->estatus->setCellValue("BM".$flag."", "Pzs");
					$this->cellStyle("BN".$flag, "000000", "FFFFFF", FALSE, 10, "Franklin Gothic Book");
					$proveedor[$key]->estatus->setCellValue("BN".$flag."", "Pedido");
					$this->cellStyle("BO".$flag, "000000", "FFFFFF", FALSE, 10, "Franklin Gothic Book");
					$proveedor[$key]->estatus->setCellValue("BO".$flag."", "Pedido anterior");
					$this->cellStyle("BP".$flag, "000000", "FF0000", FALSE, 10, "Franklin Gothic Book");
					$proveedor[$key]->estatus->setCellValue("BP".$flag."", "Sugerido");
					$this->cellStyle("BQ".$flag, "000000", "FFFFFF", FALSE, 10, "Franklin Gothic Book");
					$proveedor[$key]->estatus->setCellValue("BQ".$flag."", "Pendiente");
					$this->cellStyle("BR".$flag, "000000", "FFFFFF", FALSE, 10, "Franklin Gothic Book");
					$proveedor[$key]->estatus->setCellValue("BR".$flag."", "Cajas");
					$this->cellStyle("BS".$flag, "000000", "FFFFFF", FALSE, 10, "Franklin Gothic Book");
					$proveedor[$key]->estatus->setCellValue("BS".$flag."", "Pzs");
					$this->cellStyle("BT".$flag, "000000", "FFFFFF", FALSE, 10, "Franklin Gothic Book");
					$proveedor[$key]->estatus->setCellValue("BT".$flag."", "Pedido");
					$this->cellStyle("BU".$flag, "FFFF00", "FF0000", FALSE, 10, "Franklin Gothic Book");
					$this->cellStyle("BV".$flag, "92D050", "FF0000", TRUE, 12, "Franklin Gothic Book");

					$this->excelfile->getActiveSheet()->getStyle('BX'.$flag.':CH'.$flag)->applyFromArray($styleArray);

					$this->cellStyle("BX".$flag, "C00000", "000000", TRUE, 12, "Franklin Gothic Book");
					$this->cellStyle("BY".$flag, "FF0066", "000000", TRUE, 12, "Franklin Gothic Book");
					$this->cellStyle("BZ".$flag, "01B0F0", "000000", TRUE, 12, "Franklin Gothic Book");
					$this->cellStyle("CA".$flag, "FF0000", "000000", TRUE, 12, "Franklin Gothic Book");
					$this->cellStyle("CB".$flag, "E26C0B", "000000", TRUE, 12, "Franklin Gothic Book");
					$this->cellStyle("CC".$flag, "C5C5C5", "000000", TRUE, 12, "Franklin Gothic Book");
					$this->cellStyle("CD".$flag, "92D051", "000000", TRUE, 12, "Franklin Gothic Book");
					$this->cellStyle("CE".$flag, "B1A0C7", "000000", TRUE, 12, "Franklin Gothic Book");
					$this->cellStyle("CF".$flag, "DA9694", "000000", TRUE, 12, "Franklin Gothic Book");
					$this->cellStyle("CG".$flag, "4CACC6", "000000", TRUE, 12, "Franklin Gothic Book");
					$this->cellStyle("CH".$flag, "000000", "FFFFFF", TRUE, 12, "Franklin Gothic Book");
					
					$proveedor[$key]->estatus->setCellValue("CH".$flag."", "TOTAL");
					
					$proveedor[$key]->estatus->getColumnDimension('BX')->setWidth("15");
					$proveedor[$key]->estatus->getColumnDimension('BY')->setWidth("15");
					$proveedor[$key]->estatus->getColumnDimension('BZ')->setWidth("15");
					$proveedor[$key]->estatus->getColumnDimension('CA')->setWidth("15");
					$proveedor[$key]->estatus->getColumnDimension('CB')->setWidth("15");
					$proveedor[$key]->estatus->getColumnDimension('CC')->setWidth("15");
					$proveedor[$key]->estatus->getColumnDimension('CD')->setWidth("15");
					$proveedor[$key]->estatus->getColumnDimension('CE')->setWidth("15");
					$proveedor[$key]->estatus->getColumnDimension('CF')->setWidth("15");
					$proveedor[$key]->estatus->getColumnDimension('CG')->setWidth("15");
					$proveedor[$key]->estatus->getColumnDimension('CH')->setWidth("15");
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
				$flageas = $flag+1;
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
					$this->excelfile->getActiveSheet()->getStyle('A'.$flag.':BU'.$flag)->applyFromArray($styleArray);
					$proveedor[$key]->estatus->setCellValue("A".$flag."", $v["codigo"])->getStyle("A{$flag}")->getNumberFormat()->setFormatCode('# ???/???');
					$this->cellStyle("A".$flag, "FFFFFF", "000000", FALSE, 10, "Franklin Gothic Book");
					$proveedor[$key]->estatus->setCellValue("C".$flag."", $v["descripcion"]);
					$this->cellStyle("C".$flag, "FFFFFF", "000000", FALSE, 10, "Franklin Gothic Book");
					$proveedor[$key]->estatus->setCellValue("D{$flag}", $v["precio"])->getStyle("D{$flag}")->getNumberFormat()->setFormatCode('"$"#,##0.00_-');
					$this->cellStyle("D".$flag, "FFFFFF", "000000", FALSE, 10, "Franklin Gothic Book");
					$proveedor[$key]->estatus->setCellValue("E{$flag}", $v["sistema"])->getStyle("E{$flag}")->getNumberFormat()->setFormatCode('"$"#,##0.00_-');
					$this->cellStyle("E".$flag, "FFFFFF", "000000", FALSE, 10, "Franklin Gothic Book");
					$proveedor[$key]->estatus->setCellValue("F{$flag}", $v["unidad"]);

					$proveedor[$key]->estatus->setCellValue("BU{$flag}", $v["observaciones"]);					

					$this->cellStyle("F".$flag, "FFFFFF", "000000", FALSE, 10, "Franklin Gothic Book");
					$this->cellStyle("B".$flag, "FFFFFF", "000000", FALSE, 10, "Franklin Gothic Book");
					if ($v["codigo"] == "490676"){$proveedor[$key]->estatus->setCellValue("B{$flag}", "001");}
					if ($v["codigo"] == "490677"){$proveedor[$key]->estatus->setCellValue("B{$flag}", "002");}
					if ($v["codigo"] == "75010258035"){$proveedor[$key]->estatus->setCellValue("B{$flag}", "009");}
					if ($v["codigo"] == "490686"){$proveedor[$key]->estatus->setCellValue("B{$flag}", "014");}
					if ($v["codigo"] == "75010490679"){$proveedor[$key]->estatus->setCellValue("B{$flag}", "024");}
					if ($v["codigo"] == "7501021148"){$proveedor[$key]->estatus->setCellValue("B{$flag}", "063");}
					if ($v["codigo"] == "75030253007"){$proveedor[$key]->estatus->setCellValue("B{$flag}", "201");}
					if ($v["codigo"] == "750242549"){$proveedor[$key]->estatus->setCellValue("B{$flag}", "202");}
					if ($v["codigo"] == "7501021112183"){$proveedor[$key]->estatus->setCellValue("B{$flag}", "218");}
					if ($v["codigo"] == "7501021112206"){$proveedor[$key]->estatus->setCellValue("B{$flag}", "220");}
					if ($v["codigo"] == "490683"){$proveedor[$key]->estatus->setCellValue("B{$flag}", "505");}
					if ($v["codigo"] == "490684"){$proveedor[$key]->estatus->setCellValue("B{$flag}", "507");}
					if ($v["codigo"] == "490685"){$proveedor[$key]->estatus->setCellValue("B{$flag}", "508");}
					if ($v["codigo"] == "7501025803020"){$proveedor[$key]->estatus->setCellValue("B{$flag}", "516");}
					if ($v["codigo"] == "7506399016"){$proveedor[$key]->estatus->setCellValue("B{$flag}", "559");}
					if ($v["codigo"] == "7506399017"){$proveedor[$key]->estatus->setCellValue("B{$flag}", "609");}
					if ($v["codigo"] == "7506399018"){$proveedor[$key]->estatus->setCellValue("B{$flag}", "610");}
					if ($v["codigo"] == "242546"){$proveedor[$key]->estatus->setCellValue("B{$flag}", "617");}
					if ($v["codigo"] == "73501"){$proveedor[$key]->estatus->setCellValue("B{$flag}", "630");}
					if ($v["codigo"] == "490682"){$proveedor[$key]->estatus->setCellValue("B{$flag}", "631");}
					if ($v["codigo"] == "242548"){$proveedor[$key]->estatus->setCellValue("B{$flag}", "648");}
					if ($v["codigo"] == "242547"){$proveedor[$key]->estatus->setCellValue("B{$flag}", "654");}
					if ($v["codigo"] == "490680"){$proveedor[$key]->estatus->setCellValue("B{$flag}", "656");}
					if ($v["codigo"] == "490678"){$proveedor[$key]->estatus->setCellValue("B{$flag}", "713");}
					if ($v["codigo"] == "490681"){$proveedor[$key]->estatus->setCellValue("B{$flag}", "715");}
					if ($v["codigo"] == "490675"){$proveedor[$key]->estatus->setCellValue("B{$flag}", "725");}
					if ($v["codigo"] == "490679"){$proveedor[$key]->estatus->setCellValue("B{$flag}", "732");}
					if ($v["codigo"] == "7501028490679"){$proveedor[$key]->estatus->setCellValue("B{$flag}", "741");}
					
					$proveedor[$key]->estatus->setCellValue("G{$flag}", "=VLOOKUP(A{$flag},ANTERIOR!A:BL,55,FALSE)")->getStyle("G{$flag}")->getNumberFormat()->setFormatCode('#,#0_-');
					$proveedor[$key]->estatus->setCellValue("S{$flag}", "=VLOOKUP(A{$flag},ANTERIOR!A:BL,56,FALSE)")->getStyle("S{$flag}")->getNumberFormat()->setFormatCode('#,#0_-');
					$proveedor[$key]->estatus->setCellValue("Y{$flag}", "=VLOOKUP(A{$flag},ANTERIOR!A:BL,57,FALSE)")->getStyle("Y{$flag}")->getNumberFormat()->setFormatCode('#,#0_-');
					$proveedor[$key]->estatus->setCellValue("AE{$flag}", "=VLOOKUP(A{$flag},ANTERIOR!A:BL,58,FALSE)")->getStyle("AE{$flag}")->getNumberFormat()->setFormatCode('#,#0_-');
					$proveedor[$key]->estatus->setCellValue("AK{$flag}", "=VLOOKUP(A{$flag},ANTERIOR!A:BL,59,FALSE)")->getStyle("AK{$flag}")->getNumberFormat()->setFormatCode('#,#0_-');
					$proveedor[$key]->estatus->setCellValue("AQ{$flag}", "=VLOOKUP(A{$flag},ANTERIOR!A:BL,60,FALSE)")->getStyle("AQ{$flag}")->getNumberFormat()->setFormatCode('#,#0_-');
					$proveedor[$key]->estatus->setCellValue("AW{$flag}", "=VLOOKUP(A{$flag},ANTERIOR!A:BL,61,FALSE)")->getStyle("AW{$flag}")->getNumberFormat()->setFormatCode('#,#0_-');
					$proveedor[$key]->estatus->setCellValue("BC{$flag}", "=VLOOKUP(A{$flag},ANTERIOR!A:BL,62,FALSE)")->getStyle("BC{$flag}")->getNumberFormat()->setFormatCode('#,#0_-');
					$proveedor[$key]->estatus->setCellValue("BI{$flag}", "=VLOOKUP(A{$flag},ANTERIOR!A:BL,63,FALSE)")->getStyle("BI{$flag}")->getNumberFormat()->setFormatCode('#,#0_-');
					$proveedor[$key]->estatus->setCellValue("BO{$flag}", "=VLOOKUP(A{$flag},ANTERIOR!A:BL,64,FALSE)")->getStyle("BO{$flag}")->getNumberFormat()->setFormatCode('#,#0_-');

					$proveedor[$key]->estatus->setCellValue("H{$flag}", "=((G{$flag}*F{$flag})-(((J{$flag}*F{$flag})+K{$flag})))/F{$flag}")->getStyle("H{$flag}")->getNumberFormat()->setFormatCode('#,#0_-');
					$proveedor[$key]->estatus->setCellValue("T{$flag}", "=((S{$flag}*F{$flag})-(((V{$flag}*F{$flag})+W{$flag})))/F{$flag}")->getStyle("T{$flag}")->getNumberFormat()->setFormatCode('#,#0_-');
					$proveedor[$key]->estatus->setCellValue("Z{$flag}", "=((Y{$flag}*F{$flag})-(((AB{$flag}*F{$flag})+AC{$flag})))/F{$flag}")->getStyle("Z{$flag}")->getNumberFormat()->setFormatCode('#,#0_-');
					$proveedor[$key]->estatus->setCellValue("AF{$flag}", "=((AE{$flag}*F{$flag})-(((AH{$flag}*F{$flag})+AI{$flag})))/F{$flag}")->getStyle("AF{$flag}")->getNumberFormat()->setFormatCode('#,#0_-');
					$proveedor[$key]->estatus->setCellValue("AL{$flag}", "=((AK{$flag}*F{$flag})-(((AN{$flag}*F{$flag})+AO{$flag})))/F{$flag}")->getStyle("AL{$flag}")->getNumberFormat()->setFormatCode('#,#0_-');
					$proveedor[$key]->estatus->setCellValue("AR{$flag}", "=((AQ{$flag}*F{$flag})-(((AT{$flag}*F{$flag})+AU{$flag})))/F{$flag}")->getStyle("AR{$flag}")->getNumberFormat()->setFormatCode('#,#0_-');
					$proveedor[$key]->estatus->setCellValue("AX{$flag}", "=((AW{$flag}*F{$flag})-(((AZ{$flag}*F{$flag})+BA{$flag})))/F{$flag}")->getStyle("AX{$flag}")->getNumberFormat()->setFormatCode('#,#0_-');
					$proveedor[$key]->estatus->setCellValue("BD{$flag}", "=((BC{$flag}*F{$flag})-(((BF{$flag}*F{$flag})+BG{$flag})))/F{$flag}")->getStyle("BD{$flag}")->getNumberFormat()->setFormatCode('#,#0_-');
					$proveedor[$key]->estatus->setCellValue("BJ{$flag}", "=((BI{$flag}*F{$flag})-(((BL{$flag}*F{$flag})+BM{$flag})))/F{$flag}")->getStyle("BJ{$flag}")->getNumberFormat()->setFormatCode('#,#0_-');
					$proveedor[$key]->estatus->setCellValue("BP{$flag}", "=((BO{$flag}*F{$flag})-(((BR{$flag}*F{$flag})+BS{$flag})))/F{$flag}")->getStyle("BP{$flag}")->getNumberFormat()->setFormatCode('#,#0_-');
					
					

					$this->cellStyle("G{$flag}:BV{$flag}", "FFFFFF", "000000", TRUE, 10, "Franklin Gothic Book");
					$this->cellStyle("L".$flag, "DCE6F1", "000000", TRUE, 10, "Franklin Gothic Book");
					$this->cellStyle("R".$flag, "DCE6F1", "000000", TRUE, 10, "Franklin Gothic Book");
					$this->cellStyle("X".$flag, "DCE6F1", "000000", TRUE, 10, "Franklin Gothic Book");
					$this->cellStyle("AD".$flag, "DCE6F1", "000000", TRUE, 10, "Franklin Gothic Book");
					$this->cellStyle("AJ".$flag, "DCE6F1", "000000", TRUE, 10, "Franklin Gothic Book");
					$this->cellStyle("AP".$flag, "DCE6F1", "000000", TRUE, 10, "Franklin Gothic Book");
					$this->cellStyle("AV".$flag, "DCE6F1", "000000", TRUE, 10, "Franklin Gothic Book");
					$this->cellStyle("BB".$flag, "DCE6F1", "000000", TRUE, 10, "Franklin Gothic Book");
					$this->cellStyle("BH".$flag, "DCE6F1", "000000", TRUE, 10, "Franklin Gothic Book");
					$this->cellStyle("BN".$flag, "DCE6F1", "000000", TRUE, 10, "Franklin Gothic Book");
					$this->cellStyle("BT".$flag, "DCE6F1", "000000", TRUE, 10, "Franklin Gothic Book");


					$this->cellStyle("H".$flag, "FFFF00", "FF0000", TRUE, 10, "Franklin Gothic Book");
					$this->cellStyle("N".$flag, "FFFF00", "FF0000", TRUE, 10, "Franklin Gothic Book");
					$this->cellStyle("T".$flag, "FFFF00", "FF0000", TRUE, 10, "Franklin Gothic Book");
					$this->cellStyle("Z".$flag, "FFFF00", "FF0000", TRUE, 10, "Franklin Gothic Book");
					$this->cellStyle("AF".$flag, "FFFF00", "FF0000", TRUE, 10, "Franklin Gothic Book");
					$this->cellStyle("AL".$flag, "FFFF00", "FF0000", TRUE, 10, "Franklin Gothic Book");
					$this->cellStyle("AR".$flag, "FFFF00", "FF0000", TRUE, 10, "Franklin Gothic Book");
					$this->cellStyle("AX".$flag, "FFFF00", "FF0000", TRUE, 10, "Franklin Gothic Book");
					$this->cellStyle("BD".$flag, "FFFF00", "FF0000", TRUE, 10, "Franklin Gothic Book");
					$this->cellStyle("BJ".$flag, "FFFF00", "FF0000", TRUE, 10, "Franklin Gothic Book");
					$this->cellStyle("BP".$flag, "FFFF00", "FF0000", TRUE, 10, "Franklin Gothic Book");

					$this->cellStyle("I".$flag, "FFFFFF", "000000", TRUE, 12, "Franklin Gothic Book");
					$this->cellStyle("O".$flag, "FFFFFF", "000000", TRUE, 12, "Franklin Gothic Book");
					$this->cellStyle("U".$flag, "FFFFFF", "000000", TRUE, 12, "Franklin Gothic Book");
					$this->cellStyle("AA".$flag, "FFFFFF", "000000", TRUE, 12, "Franklin Gothic Book");
					$this->cellStyle("AG".$flag, "FFFFFF", "000000", TRUE, 12, "Franklin Gothic Book");
					$this->cellStyle("AM".$flag, "FFFFFF", "000000", TRUE, 12, "Franklin Gothic Book");
					$this->cellStyle("AS".$flag, "FFFFFF", "000000", TRUE, 12, "Franklin Gothic Book");
					$this->cellStyle("AY".$flag, "FFFFFF", "000000", TRUE, 12, "Franklin Gothic Book");
					$this->cellStyle("BE".$flag, "FFFFFF", "000000", TRUE, 12, "Franklin Gothic Book");
					$this->cellStyle("BK".$flag, "FFFFFF", "000000", TRUE, 12, "Franklin Gothic Book");
					$this->cellStyle("BQ".$flag, "FFFFFF", "000000", TRUE, 12, "Franklin Gothic Book");

					$this->excelfile->getActiveSheet()->getStyle('BX'.$flag.':CH'.$flag)->applyFromArray($styleArray);
					$this->cellStyle("BX".$flag, "FFFFFF", "000000", FALSE, 10, "Franklin Gothic Book");
					$proveedor[$key]->estatus->setCellValue("BX{$flag}", "=D{$flag}*L{$flag}")->getStyle("BX{$flag}")->getNumberFormat()->setFormatCode('"$"#,##0.00_-');
					$this->cellStyle("BY".$flag, "FFFFFF", "000000", FALSE, 10, "Franklin Gothic Book");
					$proveedor[$key]->estatus->setCellValue("BY{$flag}", "=D{$flag}*X{$flag}")->getStyle("BY{$flag}")->getNumberFormat()->setFormatCode('"$"#,##0.00_-');
					$this->cellStyle("BZ".$flag, "FFFFFF", "000000", FALSE, 10, "Franklin Gothic Book");
					$proveedor[$key]->estatus->setCellValue("BZ{$flag}", "=D{$flag}*AD{$flag}")->getStyle("BZ{$flag}")->getNumberFormat()->setFormatCode('"$"#,##0.00_-');
					$this->cellStyle("BZ".$flag, "FFFFFF", "000000", FALSE, 10, "Franklin Gothic Book");
					$proveedor[$key]->estatus->setCellValue("CA{$flag}", "=D{$flag}*AJ{$flag}")->getStyle("CA{$flag}")->getNumberFormat()->setFormatCode('"$"#,##0.00_-');
					$this->cellStyle("CB".$flag, "FFFFFF", "000000", FALSE, 10, "Franklin Gothic Book");
					$proveedor[$key]->estatus->setCellValue("CB{$flag}", "=D{$flag}*AP{$flag}")->getStyle("CB{$flag}")->getNumberFormat()->setFormatCode('"$"#,##0.00_-');
					$this->cellStyle("CC".$flag, "FFFFFF", "000000", FALSE, 10, "Franklin Gothic Book");
					$proveedor[$key]->estatus->setCellValue("CC{$flag}", "=D{$flag}*AV{$flag}")->getStyle("CC{$flag}")->getNumberFormat()->setFormatCode('"$"#,##0.00_-');
					$this->cellStyle("CD".$flag, "FFFFFF", "000000", FALSE, 10, "Franklin Gothic Book");
					$proveedor[$key]->estatus->setCellValue("CD{$flag}", "=D{$flag}*BB{$flag}")->getStyle("CD{$flag}")->getNumberFormat()->setFormatCode('"$"#,##0.00_-');
					$this->cellStyle("CE".$flag, "FFFFFF", "000000", FALSE, 10, "Franklin Gothic Book");
					$proveedor[$key]->estatus->setCellValue("CE{$flag}", "=D{$flag}*BH{$flag}")->getStyle("CE{$flag}")->getNumberFormat()->setFormatCode('"$"#,##0.00_-');
					$this->cellStyle("CF".$flag, "FFFFFF", "000000", FALSE, 10, "Franklin Gothic Book");
					$proveedor[$key]->estatus->setCellValue("CF{$flag}", "=D{$flag}*BN{$flag}")->getStyle("CF{$flag}")->getNumberFormat()->setFormatCode('"$"#,##0.00_-');
					$this->cellStyle("CG".$flag, "FFFFFF", "000000", FALSE, 10, "Franklin Gothic Book");
					$proveedor[$key]->estatus->setCellValue("CG{$flag}", "=D{$flag}*BT{$flag}")->getStyle("CG{$flag}")->getNumberFormat()->setFormatCode('"$"#,##0.00_-');
					$this->cellStyle("CH".$flag, "FFFFFF", "000000", FALSE, 10, "Franklin Gothic Book");
					$proveedor[$key]->estatus->setCellValue("CH{$flag}", "=SUM(BX{$flag}:CG{$flag})")->getStyle("CH{$flag}")->getNumberFormat()->setFormatCode('"$"#,##0.00_-');

					$col = 9;
					 foreach ($v["existencias"] as $k => $vs) {
						$proveedor[$key]->estatus->setCellValueByColumnAndRow($col, $flag, $vs["cja"]);
						$col++;
						$proveedor[$key]->estatus->setCellValueByColumnAndRow($col, $flag, $vs["pzs"]);
						$col++;
						$proveedor[$key]->estatus->setCellValueByColumnAndRow($col, $flag, $vs["ped"]);
						$col+=4;
					 }
					 $col = 7;
					 

					 $proveedor[$key]->estatus->setCellValue("I{$flag}", $v["pend"][1]["pend"]);
					 $proveedor[$key]->estatus->setCellValue("AA{$flag}", $v["pend"][4]["pend"]);
					 $proveedor[$key]->estatus->setCellValue("AG{$flag}", $v["pend"][5]["pend"]);
					 $proveedor[$key]->estatus->setCellValue("AM{$flag}", $v["pend"][6]["pend"]);
					 $proveedor[$key]->estatus->setCellValue("AS{$flag}", $v["pend"][7]["pend"]);
					 $proveedor[$key]->estatus->setCellValue("AY{$flag}", $v["pend"][8]["pend"]);
					 $proveedor[$key]->estatus->setCellValue("BE{$flag}", $v["pend"][9]["pend"]);
					 $proveedor[$key]->estatus->setCellValue("BK{$flag}", $v["pend"][10]["pend"]);
					 $proveedor[$key]->estatus->setCellValue("BQ{$flag}", $v["pend"][11]["pend"]);



					 $proveedor[$key]->estatus->setCellValue("M{$flag}", "=G{$flag}+S{$flag}");
					 $proveedor[$key]->estatus->setCellValue("N{$flag}", "=H{$flag}+T{$flag}");
					 $proveedor[$key]->estatus->setCellValue("O{$flag}", "=J{$flag}+V{$flag}");
					 $proveedor[$key]->estatus->setCellValue("P{$flag}", "=K{$flag}+W{$flag}");
					 $proveedor[$key]->estatus->setCellValue("Q{$flag}", "=L{$flag}+X{$flag}");
					 $proveedor[$key]->estatus->setCellValue("O{$flag}", "=I{$flag}+U{$flag}");
					
				}
				$flag++;
				$this->excelfile->getActiveSheet()->getStyle('L'.$flag)->applyFromArray($styleArray);
				$this->cellStyle("L".$flag, "FFFFFF", "000000", FALSE, 10, "Franklin Gothic Book");
				$proveedor[$key]->estatus->setCellValue("L{$flag}", "=SUM(L{$flageas}:L".($flag-1).")")->getStyle("L{$flag}")->getNumberFormat()->setFormatCode('#0_-');
				$this->excelfile->getActiveSheet()->getStyle('R'.$flag)->applyFromArray($styleArray);
				$this->cellStyle("R".$flag, "FFFFFF", "000000", FALSE, 10, "Franklin Gothic Book");
				$proveedor[$key]->estatus->setCellValue("R{$flag}", "=SUM(R{$flageas}:R".($flag-1).")")->getStyle("R{$flag}")->getNumberFormat()->setFormatCode('#0_-');
				$this->excelfile->getActiveSheet()->getStyle('X'.$flag)->applyFromArray($styleArray);
				$this->cellStyle("X".$flag, "FFFFFF", "000000", FALSE, 10, "Franklin Gothic Book");
				$proveedor[$key]->estatus->setCellValue("X{$flag}", "=SUM(X{$flageas}:X".($flag-1).")")->getStyle("X{$flag}")->getNumberFormat()->setFormatCode('#0_-');
				$this->excelfile->getActiveSheet()->getStyle('AD'.$flag)->applyFromArray($styleArray);
				$this->cellStyle("AD".$flag, "FFFFFF", "000000", FALSE, 10, "Franklin Gothic Book");
				$proveedor[$key]->estatus->setCellValue("AD{$flag}", "=SUM(AD{$flageas}:AD".($flag-1).")")->getStyle("AD{$flag}")->getNumberFormat()->setFormatCode('#0_-');
				$this->excelfile->getActiveSheet()->getStyle('AJ'.$flag)->applyFromArray($styleArray);
				$this->cellStyle("AJ".$flag, "FFFFFF", "000000", FALSE, 10, "Franklin Gothic Book");
				$proveedor[$key]->estatus->setCellValue("AJ{$flag}", "=SUM(AJ{$flageas}:AJ".($flag-1).")")->getStyle("AJ{$flag}")->getNumberFormat()->setFormatCode('#0_-');
				$this->excelfile->getActiveSheet()->getStyle('AP'.$flag)->applyFromArray($styleArray);
				$this->cellStyle("AP".$flag, "FFFFFF", "000000", FALSE, 10, "Franklin Gothic Book");
				$proveedor[$key]->estatus->setCellValue("AP{$flag}", "=SUM(AP{$flageas}:AP".($flag-1).")")->getStyle("AP{$flag}")->getNumberFormat()->setFormatCode('#0_-');
				$this->excelfile->getActiveSheet()->getStyle('AV'.$flag)->applyFromArray($styleArray);
				$this->cellStyle("AV".$flag, "FFFFFF", "000000", FALSE, 10, "Franklin Gothic Book");
				$proveedor[$key]->estatus->setCellValue("AV{$flag}", "=SUM(AV{$flageas}:AV".($flag-1).")")->getStyle("AV{$flag}")->getNumberFormat()->setFormatCode('#0_-');
				$this->excelfile->getActiveSheet()->getStyle('BB'.$flag)->applyFromArray($styleArray);
				$this->cellStyle("BB".$flag, "FFFFFF", "000000", FALSE, 10, "Franklin Gothic Book");
				$proveedor[$key]->estatus->setCellValue("BB{$flag}", "=SUM(BB{$flageas}:BB".($flag-1).")")->getStyle("BB{$flag}")->getNumberFormat()->setFormatCode('#0_-');
				$this->excelfile->getActiveSheet()->getStyle('BH'.$flag)->applyFromArray($styleArray);
				$this->cellStyle("BH".$flag, "FFFFFF", "000000", FALSE, 10, "Franklin Gothic Book");
				$proveedor[$key]->estatus->setCellValue("BH{$flag}", "=SUM(BH{$flageas}:BH".($flag-1).")")->getStyle("BH{$flag}")->getNumberFormat()->setFormatCode('#0_-');
				$this->excelfile->getActiveSheet()->getStyle('BN'.$flag)->applyFromArray($styleArray);
				$this->cellStyle("BN".$flag, "FFFFFF", "000000", FALSE, 10, "Franklin Gothic Book");
				$proveedor[$key]->estatus->setCellValue("BN{$flag}", "=SUM(BN{$flageas}:BN".($flag-1).")")->getStyle("BN{$flag}")->getNumberFormat()->setFormatCode('#0_-');
				$this->excelfile->getActiveSheet()->getStyle('BT'.$flag)->applyFromArray($styleArray);
				$this->cellStyle("BT".$flag, "FFFFFF", "000000", FALSE, 10, "Franklin Gothic Book");
				$proveedor[$key]->estatus->setCellValue("BT{$flag}", "=SUM(BT{$flageas}:BT".($flag-1).")")->getStyle("BT{$flag}")->getNumberFormat()->setFormatCode('#0_-');
				$this->excelfile->getActiveSheet()->getStyle('BX'.$flag.':CH'.$flag)->applyFromArray($styleArray);
				$this->cellStyle("BX".$flag, "FFFFFF", "000000", FALSE, 10, "Franklin Gothic Book");
				$proveedor[$key]->estatus->setCellValue("BX{$flag}", "=SUM(BW{$flageas}:BX".($flag-1).")")->getStyle("BX{$flag}")->getNumberFormat()->setFormatCode('"$"#,##0.00_-');
				$this->cellStyle("BY".$flag, "FFFFFF", "000000", FALSE, 10, "Franklin Gothic Book");
				$proveedor[$key]->estatus->setCellValue("BY{$flag}", "=SUM(BY{$flageas}:BY".($flag-1).")")->getStyle("BY{$flag}")->getNumberFormat()->setFormatCode('"$"#,##0.00_-');
				$this->cellStyle("BZ".$flag, "FFFFFF", "000000", FALSE, 10, "Franklin Gothic Book");
				$proveedor[$key]->estatus->setCellValue("BZ{$flag}", "=SUM(BZ{$flageas}:BZ".($flag-1).")")->getStyle("BZ{$flag}")->getNumberFormat()->setFormatCode('"$"#,##0.00_-');
				$this->cellStyle("CA".$flag, "FFFFFF", "000000", FALSE, 10, "Franklin Gothic Book");
				$proveedor[$key]->estatus->setCellValue("CA{$flag}", "=SUM(CA{$flageas}:CA".($flag-1).")")->getStyle("CA{$flag}")->getNumberFormat()->setFormatCode('"$"#,##0.00_-');
				$this->cellStyle("CB".$flag, "FFFFFF", "000000", FALSE, 10, "Franklin Gothic Book");
				$proveedor[$key]->estatus->setCellValue("CB{$flag}", "=SUM(CB{$flageas}:CB".($flag-1).")")->getStyle("CB{$flag}")->getNumberFormat()->setFormatCode('"$"#,##0.00_-');
				$this->cellStyle("CC".$flag, "FFFFFF", "000000", FALSE, 10, "Franklin Gothic Book");
				$proveedor[$key]->estatus->setCellValue("CC{$flag}", "=SUM(CC{$flageas}:CC".($flag-1).")")->getStyle("CC{$flag}")->getNumberFormat()->setFormatCode('"$"#,##0.00_-');
				$this->cellStyle("CD".$flag, "FFFFFF", "000000", FALSE, 10, "Franklin Gothic Book");
				$proveedor[$key]->estatus->setCellValue("CD{$flag}", "=SUM(CD{$flageas}:CD".($flag-1).")")->getStyle("CD{$flag}")->getNumberFormat()->setFormatCode('"$"#,##0.00_-');
				$this->cellStyle("CE".$flag, "FFFFFF", "000000", FALSE, 10, "Franklin Gothic Book");
				$proveedor[$key]->estatus->setCellValue("CE{$flag}", "=SUM(CE{$flageas}:CE".($flag-1).")")->getStyle("CE{$flag}")->getNumberFormat()->setFormatCode('"$"#,##0.00_-');
				$this->cellStyle("CF".$flag, "FFFFFF", "000000", FALSE, 10, "Franklin Gothic Book");
				$proveedor[$key]->estatus->setCellValue("CF{$flag}", "=SUM(CF{$flageas}:CF".($flag-1).")")->getStyle("CF{$flag}")->getNumberFormat()->setFormatCode('"$"#,##0.00_-');
				$this->cellStyle("CG".$flag, "FFFFFF", "000000", FALSE, 10, "Franklin Gothic Book");
				$proveedor[$key]->estatus->setCellValue("CG{$flag}", "=SUM(CG{$flageas}:CG".($flag-1).")")->getStyle("CG{$flag}")->getNumberFormat()->setFormatCode('"$"#,##0.00_-');
				$this->cellStyle("CH".$flag, "FFFFFF", "000000", FALSE, 10, "Franklin Gothic Book");
				$proveedor[$key]->estatus->setCellValue("CH{$flag}", "=SUM(CH{$flageas}:CH".($flag-1).")")->getStyle("CH{$flag}")->getNumberFormat()->setFormatCode('"$"#,##0.00_-');
				$totis = $flag;
				$flag+=5;


				$this->excelfile->getActiveSheet()->getStyle('C'.$flag.':D'.$flag)->applyFromArray($styleArray);
				$this->cellStyle("C".$flag, "C00000", "000000", TRUE, 12, "Franklin Gothic Book");
				$proveedor[$key]->estatus->setCellValue("C".$flag, "CEDIS/SUPER");
				$proveedor[$key]->estatus->setCellValue("D{$flag}", "=BX{$totis}")->getStyle("D{$flag}")->getNumberFormat()->setFormatCode('"$"#,##0.00_-');
				$ced=$ced."".$va->alias."!D{$flag}+";
				$flag++;
				$this->excelfile->getActiveSheet()->getStyle('C'.$flag.':D'.$flag)->applyFromArray($styleArray);
				$proveedor[$key]->estatus->setCellValue("C".$flag, "CD INDUSTRIAL");
				$this->cellStyle("C".$flag, "FF0066", "000000", TRUE, 12, "Franklin Gothic Book");
				$proveedor[$key]->estatus->setCellValue("D{$flag}", "=BY{$totis}")->getStyle("D{$flag}")->getNumberFormat()->setFormatCode('"$"#,##0.00_-');
				$sup=$sup."".$va->alias."!D{$flag}+";
				$flag++;
				$this->excelfile->getActiveSheet()->getStyle('C'.$flag.':D'.$flag)->applyFromArray($styleArray);
				$this->cellStyle("C".$flag, "01B0F0", "000000", TRUE, 12, "Franklin Gothic Book");
				$proveedor[$key]->estatus->setCellValue("C".$flag, "ABARROTES");
				$proveedor[$key]->estatus->setCellValue("D{$flag}", "=BZ{$totis}")->getStyle("D{$flag}")->getNumberFormat()->setFormatCode('"$"#,##0.00_-');
				$aba=$aba."".$va->alias."!D{$flag}+";
				$flag++;
				$this->excelfile->getActiveSheet()->getStyle('C'.$flag.':D'.$flag)->applyFromArray($styleArray);
				$this->cellStyle("C".$flag, "FF0000", "000000", TRUE, 12, "Franklin Gothic Book");
				$proveedor[$key]->estatus->setCellValue("C".$flag, "PEDREGAL");
				$proveedor[$key]->estatus->setCellValue("D{$flag}", "=CA{$totis}")->getStyle("D{$flag}")->getNumberFormat()->setFormatCode('"$"#,##0.00_-');
				$ped=$ped."".$va->alias."!D{$flag}+";
				$flag++;
				$this->excelfile->getActiveSheet()->getStyle('C'.$flag.':D'.$flag)->applyFromArray($styleArray);
				$this->cellStyle("C".$flag, "E26C0B", "000000", TRUE, 12, "Franklin Gothic Book");
				$proveedor[$key]->estatus->setCellValue("C".$flag, "TIENDA");
				$proveedor[$key]->estatus->setCellValue("D{$flag}", "=CB{$totis}")->getStyle("D{$flag}")->getNumberFormat()->setFormatCode('"$"#,##0.00_-');
				$tie=$tie."".$va->alias."!D{$flag}+";
				$flag++;
				$this->excelfile->getActiveSheet()->getStyle('C'.$flag.':D'.$flag)->applyFromArray($styleArray);
				$this->cellStyle("C".$flag, "C5C5C5", "000000", TRUE, 12, "Franklin Gothic Book");
				$proveedor[$key]->estatus->setCellValue("C".$flag, "ULTRAMARINOS");
				$proveedor[$key]->estatus->setCellValue("D{$flag}", "=CC{$totis}")->getStyle("D{$flag}")->getNumberFormat()->setFormatCode('"$"#,##0.00_-');
				$ult=$ult."".$va->alias."!D{$flag}+";
				$flag++;
				$this->excelfile->getActiveSheet()->getStyle('C'.$flag.':D'.$flag)->applyFromArray($styleArray);
				$this->cellStyle("C".$flag, "92D051", "000000", TRUE, 12, "Franklin Gothic Book");
				$proveedor[$key]->estatus->setCellValue("C".$flag, "TRINCHERAS");
				$proveedor[$key]->estatus->setCellValue("D{$flag}", "=CD{$totis}")->getStyle("D{$flag}")->getNumberFormat()->setFormatCode('"$"#,##0.00_-');
				$tri=$tri."".$va->alias."!D{$flag}+";
				$flag++;
				$this->excelfile->getActiveSheet()->getStyle('C'.$flag.':D'.$flag)->applyFromArray($styleArray);
				$this->cellStyle("C".$flag, "B1A0C7", "000000", TRUE, 12, "Franklin Gothic Book");
				$proveedor[$key]->estatus->setCellValue("C".$flag, "AZT MERCADO");
				$proveedor[$key]->estatus->setCellValue("D{$flag}", "=CE{$totis}")->getStyle("D{$flag}")->getNumberFormat()->setFormatCode('"$"#,##0.00_-');
				$mer=$mer."".$va->alias."!D{$flag}+";
				$flag++;
				$this->excelfile->getActiveSheet()->getStyle('C'.$flag.':D'.$flag)->applyFromArray($styleArray);
				$this->cellStyle("C".$flag, "DA9694", "000000", TRUE, 12, "Franklin Gothic Book");
				$proveedor[$key]->estatus->setCellValue("C".$flag, "TENENCIA");
				$proveedor[$key]->estatus->setCellValue("D{$flag}", "=CF{$totis}")->getStyle("D{$flag}")->getNumberFormat()->setFormatCode('"$"#,##0.00_-');
				$ten=$ten."".$va->alias."!D{$flag}+";
				$flag++;
				$this->excelfile->getActiveSheet()->getStyle('C'.$flag.':D'.$flag)->applyFromArray($styleArray);
				$this->cellStyle("C".$flag, "4CACC6", "000000", TRUE, 12, "Franklin Gothic Book");
				$proveedor[$key]->estatus->setCellValue("C".$flag, "TIJERAS");
				$proveedor[$key]->estatus->setCellValue("D{$flag}", "=CG{$totis}")->getStyle("D{$flag}")->getNumberFormat()->setFormatCode('"$"#,##0.00_-');
				$tij=$tij."".$va->alias."!D{$flag}+";
				$flag++;
				$this->excelfile->getActiveSheet()->getStyle('C'.$flag.':D'.$flag)->applyFromArray($styleArray);
				$this->cellStyle("C".$flag, "000000", "FFFFFF", TRUE, 12, "Franklin Gothic Book");
				$proveedor[$key]->estatus->setCellValue("C".$flag, "TOTAL");
				$proveedor[$key]->estatus->setCellValue("D{$flag}", "=SUM(D".($totis+5).":D".($flag-1).")")->getStyle("D{$flag}")->getNumberFormat()->setFormatCode('"$"#,##0.00_-');
			$flag1 += 5;

			$flag += 5;
			}
		}

		/*$this->excelfile->setActiveSheetIndex(0);

		$flag = 2;
		$totales->getColumnDimension('B')->setWidth("40");
		$totales->getColumnDimension('C')->setWidth("20");
		$this->excelfile->getActiveSheet()->getStyle('B'.$flag.':C'.$flag)->applyFromArray($styleArray);
		$this->cellStyle("B".$flag, "C00000", "000000", TRUE, 12, "Franklin Gothic Book");
		$totales->setCellValue("B".$flag, "CEDIS/SUPER");
		$totales->setCellValue("C{$flag}", substr($ced, 0, -1))->getStyle("C{$flag}")->getNumberFormat()->setFormatCode('"$"#,##0.00_-');
		
		$flag++;
		$this->excelfile->getActiveSheet()->getStyle('B'.$flag.':C'.$flag)->applyFromArray($styleArray);
		$totales->setCellValue("B".$flag, "CD INDUSTRIAL");
		$this->cellStyle("B".$flag, "FF0066", "000000", TRUE, 12, "Franklin Gothic Book");
		$sup = substr_replace($sup ,"", -1);
		$totales->setCellValue("C{$flag}", substr($sup, 0, -1))->getStyle("C{$flag}")->getNumberFormat()->setFormatCode('"$"#,##0.00_-');
		
		$flag++;
		$this->excelfile->getActiveSheet()->getStyle('B'.$flag.':C'.$flag)->applyFromArray($styleArray);
		$this->cellStyle("B".$flag, "01B0F0", "000000", TRUE, 12, "Franklin Gothic Book");
		$totales->setCellValue("B".$flag, "ABARROTES");
		$totales->setCellValue("C{$flag}", substr($aba, 0, -1))->getStyle("C{$flag}")->getNumberFormat()->setFormatCode('"$"#,##0.00_-');
		
		$flag++;
		$this->excelfile->getActiveSheet()->getStyle('B'.$flag.':C'.$flag)->applyFromArray($styleArray);
		$this->cellStyle("B".$flag, "FF0000", "000000", TRUE, 12, "Franklin Gothic Book");
		$totales->setCellValue("B".$flag, "PEDREGAL");
		$totales->setCellValue("C{$flag}", substr($ped, 0, -1))->getStyle("C{$flag}")->getNumberFormat()->setFormatCode('"$"#,##0.00_-');
		
		$flag++;
		$this->excelfile->getActiveSheet()->getStyle('B'.$flag.':C'.$flag)->applyFromArray($styleArray);
		$this->cellStyle("B".$flag, "E26C0B", "000000", TRUE, 12, "Franklin Gothic Book");
		$totales->setCellValue("B".$flag, "TIENDA");
		$totales->setCellValue("C{$flag}", substr($tie, 0, -1))->getStyle("C{$flag}")->getNumberFormat()->setFormatCode('"$"#,##0.00_-');
		
		$flag++;
		$this->excelfile->getActiveSheet()->getStyle('B'.$flag.':C'.$flag)->applyFromArray($styleArray);
		$this->cellStyle("B".$flag, "C5C5C5", "000000", TRUE, 12, "Franklin Gothic Book");
		$totales->setCellValue("B".$flag, "ULTRAMARINOS");
		$totales->setCellValue("C{$flag}", substr($ult, 0, -1))->getStyle("C{$flag}")->getNumberFormat()->setFormatCode('"$"#,##0.00_-');
		
		$flag++;
		$this->excelfile->getActiveSheet()->getStyle('B'.$flag.':C'.$flag)->applyFromArray($styleArray);
		$this->cellStyle("B".$flag, "92D051", "000000", TRUE, 12, "Franklin Gothic Book");
		$totales->setCellValue("B".$flag, "TRINCHERAS");
		$totales->setCellValue("C{$flag}", substr($tri, 0, -1))->getStyle("C{$flag}")->getNumberFormat()->setFormatCode('"$"#,##0.00_-');
		
		$flag++;
		$this->excelfile->getActiveSheet()->getStyle('B'.$flag.':C'.$flag)->applyFromArray($styleArray);
		$this->cellStyle("B".$flag, "B1A0C7", "000000", TRUE, 12, "Franklin Gothic Book");
		$totales->setCellValue("B".$flag, "AZT MERCADO");
		$totales->setCellValue("C{$flag}", substr($mer, 0, -1))->getStyle("C{$flag}")->getNumberFormat()->setFormatCode('"$"#,##0.00_-');
		
		$flag++;
		$this->excelfile->getActiveSheet()->getStyle('B'.$flag.':C'.$flag)->applyFromArray($styleArray);
		$this->cellStyle("B".$flag, "DA9694", "000000", TRUE, 12, "Franklin Gothic Book");
		$totales->setCellValue("B".$flag, "TENENCIA");
		$totales->setCellValue("C{$flag}", substr($ten, 0, -1))->getStyle("C{$flag}")->getNumberFormat()->setFormatCode('"$"#,##0.00_-');
		
		$flag++;
		$this->excelfile->getActiveSheet()->getStyle('B'.$flag.':C'.$flag)->applyFromArray($styleArray);
		$this->cellStyle("B".$flag, "4CACC6", "000000", TRUE, 12, "Franklin Gothic Book");
		$totales->setCellValue("B".$flag, "TIJERAS");
		$totales->setCellValue("C{$flag}", substr($tij, 0, -1))->getStyle("C{$flag}")->getNumberFormat()->setFormatCode('"$"#,##0.00_-');
		
		$flag++;
		$this->excelfile->getActiveSheet()->getStyle('B'.$flag.':C'.$flag)->applyFromArray($styleArray);
		$this->cellStyle("B".$flag, "000000", "FFFFFF", TRUE, 12, "Franklin Gothic Book");
		$totales->setCellValue("B".$flag, "TOTAL");
		$totales->setCellValue("C{$flag}", "=SUM(C2:C11)")->getStyle("C{$flag}")->getNumberFormat()->setFormatCode('"$"#,##0.00_-');*/
		//$this->jsonResponse($sup);

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

	public function upload_pedidos(){
		$fecha = new DateTime(date('Y-m-d H:i:s'));
		$intervalo = new DateInterval('P3D');
		$fecha->add($intervalo);
		$this->load->library("excelfile");
		ini_set("memory_limit", -1);
		$file = $_FILES["file_cotizaciones"]["tmp_name"];
		$sheet = PHPExcel_IOFactory::load($file);
		$objExcel = PHPExcel_IOFactory::load($file);
		$sheet = $objExcel->getSheet(0);
		$num_rows = $sheet->getHighestDataRow();
		$tienda = $this->session->userdata('id_usuario');
		
		$cfile =  $this->user_md->get(NULL, ['id_usuario' => $tienda])[0];
		$nams = preg_replace('/\s+/', '_', $cfile->nombre);
		$filen = "PedidosLunes".$nams."".rand();
		$config['upload_path']          = './assets/uploads/pedidos/'; 
        $config['allowed_types']        = 'xlsx|xls';
        $config['max_size']             = 1000;
        $config['max_width']            = 10024;
        $config['max_height']           = 7680;
        $this->load->library('upload', $config);
        $this->upload->initialize($config);
        $this->upload->do_upload('file_cotizaciones',$filen);
		for ($i=1; $i<=$num_rows; $i++) {
			$productos = $this->prolu_md->get("codigo",['codigo'=> htmlspecialchars($sheet->getCell('D'.$i)->getValue(), ENT_QUOTES, 'UTF-8')])[0];
			if (sizeof($productos) > 0) {
				$exis = $this->ex_lun_md->get(NULL,["WEEKOFYEAR(fecha_registro)" => $this->weekNumber($fecha->format('Y-m-d H:i:s')),"id_tienda"=>$tienda,"id_producto"=>$productos->codigo])[0];
				$column_one=0; $column_two=0; $column_three=0;
				$column_one = $sheet->getCell('A'.$i)->getValue() == "" ? 0 : $sheet->getCell('A'.$i)->getValue();
				$column_two = $sheet->getCell('B'.$i)->getValue() == "" ? 0 : $sheet->getCell('B'.$i)->getValue();
				$column_three = $sheet->getCell('C'.$i)->getValue() == "" ? 0 : $sheet->getCell('C'.$i)->getValue();
				$new_existencias[$i]=[
					"id_producto"			=>	$productos->codigo,
					"id_tienda"			=>	$tienda,
					"cajas"			=>	$column_one,
					"piezas"			=>	$column_two,
					"pedido"	=>	$column_three,
					"fecha_registro"	=>	$fecha->format('Y-m-d H:i:s')
				];
				if($exis){
					//$data['cotizacion']=$this->ex_lun_md->update($new_existencias[$i], ['id_pedido' => $exis->id_existencia]);
				}else{
					$data['cotizacion']=$this->ex_lun_md->insert($new_existencias[$i]);
				}
			}
		}
		if (isset($new_existencias)) {
			$aprov = $this->user_md->get(NULL, ['id_usuario'=>$tienda])[0];
			$cambios=[
					"id_usuario"		=>	$this->session->userdata('id_usuario'),
					"fecha_cambio"		=>	date("Y-m-d H:i:s"),
					"antes"			=>	"El usuario sube archivo de pedidos Lunes de la tienda ".$aprov->nombre,
					"despues"			=>	"assets/uploads/pedidos/".$filen.".xlsx",
					"accion"			=>	"Sube existencias y pedidos formato LUNES"
				];
			$data['cambios']=$this->cambio_md->insert($cambios);
			$mensaje=[	"id"	=>	'Éxito',
						"desc"	=>	'Existencias y pedidos cargados correctamente en el Sistema',
						"type"	=>	'success'];
		}else{
			$mensaje=[	"id"	=>	'Error',
						"desc"	=>	'Existencias y pedidos no se cargaron al Sistema',
						"type"	=>	'error'];
		}
		$this->jsonResponse($mensaje);
	}

	public function lunpedido(){
		$user = $this->session->userdata();
		$data["title"]="LISTADO EXISTENCIAS FORMATO LUNES";
		$data["cuantas"] = $this->ex_lun_md->getCuantasTienda(NULL,$user["id_usuario"])[0];
		$data["noprod"] = $this->prolu_md->getCount(NULL)[0];
		$tienda = $this->suc_md->get(NULL,["sucu"=> $user["id_usuario"]])[0];
		$data["existencias"] = $this->ex_lun_md->getLunExist(NULL,$user["id_usuario"]);
		$data["existenciasnot"] = $this->ex_lun_md->getLunExistNot(NULL,$user["id_usuario"]);
		$data["view"]=$this->load->view("Lunes/lunpedido", $data, TRUE);
		
		$this->jsonResponse($data);
	}

	public function volpedido(){
		$user = $this->session->userdata();
		$data["title"]="LISTADO EXISTENCIAS VOLÚMENES";
		$data["noprod"] = $this->prod_mdl->getVolCount(NULL)[0];
		$data["cuantas"] = $this->ex_lun_md->getVolTienda(NULL,$user["id_usuario"])[0];
		$data["existencias"] = $this->ex_lun_md->getVolExist(NULL,$user["id_usuario"]);
		$data["existenciasnot"] = $this->ex_lun_md->getVolExistNot(NULL,$user["id_usuario"]);
		$data["view"]=$this->load->view("Lunes/volpedido", $data, TRUE);
		
		$this->jsonResponse($data);
	}

	public function allpedido(){
		$user = $this->session->userdata();
		$data["title"]="LISTADO EXISTENCIAS GENERAL";
		$data["noprod"] = $this->prod_mdl->getAllCount(NULL)[0];
		$data["cuantas"] = $this->ex_lun_md->getAllTienda(NULL,$user["id_usuario"])[0];
		$data["existencias"] = $this->ex_lun_md->getAllExist(NULL,$user["id_usuario"]);
		$data["existenciasnot"] = $this->ex_lun_md->getAllExistNot(NULL,$user["id_usuario"]);
		$data["view"]=$this->load->view("Lunes/allpedido", $data, TRUE);
		
		$this->jsonResponse($data);
	}

	public function pendientes(){
		$data['links'] = [
			'/assets/css/plugins/dataTables/dataTables.bootstrap',
			'/assets/css/plugins/dataTables/dataTables.responsive',
			'/assets/css/plugins/dataTables/dataTables.tableTools.min',
			'/assets/css/plugins/dataTables/buttons.dataTables.min',
		];

		$data['scripts'] = [
			'/scripts/pendlunes',
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
		$this->estructura("Lunes/pendientes", $data);
	}

	public function upload_pendientes(){
		$fecha = new DateTime(date('Y-m-d H:i:s'));
		$intervalo = new DateInterval('P3D');
		$fecha->add($intervalo);
		$this->load->library("excelfile");
		ini_set("memory_limit", -1);
		$filen = "Pedidos Pendientes".rand();
		$config['upload_path']          = base_url('./assets/uploads/pedidos/');
        $config['allowed_types']        = 'xlsx|xls';
        $config['max_size']             = 1000;
        $config['max_width']            = 10024;
        $config['max_height']           = 7608;
        $config['max_height']           = 768;
        $this->load->library('upload', $config);
        $this->upload->initialize($config);
        $this->upload->do_upload('file_pendientes',$filen);
        $file = $_FILES["file_pendientes"]["tmp_name"];
		$sheet = PHPExcel_IOFactory::load($file);
		$objExcel = PHPExcel_IOFactory::load($file);
		$sheet = $objExcel->getSheet(0);
		$new_pendientes = [];
		$num_rows = $sheet->getHighestDataRow();
		for ($i=3; $i<=$num_rows; $i++) {
			$productos = $this->prolu_md->get("codigo",['codigo'=> htmlspecialchars($sheet->getCell('A'.$i)->getValue(), ENT_QUOTES, 'UTF-8')])[0];

			if (sizeof($productos) > 0) {
				$exis = $this->pend_mdl->get(NULL,["WEEKOFYEAR(fecha_registro)" => $this->weekNumber($fecha->format('Y-m-d H:i:s')),"id_producto"=>$productos->codigo])[0];
				$cedis = $sheet->getCell('C'.$i)->getValue() == "" ? 0 : $sheet->getCell('C'.$i)->getValue();
				$abarrotes = $sheet->getCell('D'.$i)->getValue() == "" ? 0 : $sheet->getCell('D'.$i)->getValue();
				$pedregal = $sheet->getCell('E'.$i)->getValue() == "" ? 0 : $sheet->getCell('E'.$i)->getValue();
				$tienda = $sheet->getCell('F'.$i)->getValue() == "" ? 0 : $sheet->getCell('F'.$i)->getValue();
				$ultra = $sheet->getCell('G'.$i)->getValue() == "" ? 0 : $sheet->getCell('G'.$i)->getValue();
				$trincheras = $sheet->getCell('H'.$i)->getValue() == "" ? 0 : $sheet->getCell('H'.$i)->getValue();
				$mercado = $sheet->getCell('I'.$i)->getValue() == "" ? 0 : $sheet->getCell('I'.$i)->getValue();
				$tenencia = $sheet->getCell('J'.$i)->getValue() == "" ? 0 : $sheet->getCell('J'.$i)->getValue();
				$tijeras = $sheet->getCell('K'.$i)->getValue() == "" ? 0 : $sheet->getCell('K'.$i)->getValue();
				$new_pendientes[$i]=[
					"id_producto" => $productos->codigo,
					"cedis" => $cedis,
					"abarrotes" => $abarrotes,
					"pedregal" => $pedregal,
					"tienda" => $tienda,
					"trincheras" => $trincheras,
					"ultra" => $ultra,
					"mercado" => $mercado,
					"tenencia" => $tenencia,
					"tijeras" => $tijeras,
					"fecha_registro" => $fecha->format('Y-m-d H:i:s')
				];
				if($exis){
					$data['pendientes']=$this->pend_mdl->update($new_pendientes[$i], ['id_pendiente' => $exis->id_pendiente]);
				}else{
					$data['pendientes']=$this->pend_mdl->insert($new_pendientes[$i]);
				}
			}
		}
		if (sizeof($new_pendientes) > 0) {
			$cambios=[
					"id_usuario"		=>	$this->session->userdata('id_usuario'),
					"fecha_cambio"		=>	date("Y-m-d H:i:s"),
					"antes"			=>	"El usuario sube pedidos pendientes LUNES",
					"despues"			=>	"assets/uploads/pedidos/".$filen.".xlsx",
					"accion"			=>	"Sube Pedidos Pendientes"
				];
			$data['cambios']=$this->cambio_md->insert($cambios);
			$mensaje=[	"id"	=>	'Éxito',
						"desc"	=>	'Pedidos Pendientes LUNES cargados correctamente en el Sistema',
						"type"	=>	'success'];
		}else{
			$mensaje=[	"id"	=>	'Error',
						"desc"	=>	'Los Pedidos Pendientes LUNES no se cargaron al Sistema',
						"type"	=>	'error'];
		}
		$this->jsonResponse($mensaje);
	}

	public function getPendientes(){
		$fecha = new DateTime(date('Y-m-d H:i:s'));
		$intervalo = new DateInterval('P3D');
		$fecha->add($intervalo);
		$data["pendientes"] =  $this->pend_mdl->getThem(["WEEKOFYEAR(pp.fecha_registro)" => $this->weekNumber($fecha->format('Y-m-d H:i:s'))]);
		$this->jsonResponse($data);
	}

	public function lunpedid($id_tienda){
		$user = $this->session->userdata();
		$data["title"]="LISTADO EXISTENCIAS FORMATO LUNES";
		$data["cuantas"] = $this->ex_lun_md->getCuantasTienda(NULL,$id_tienda)[0];
		$data["noprod"] = $this->prolu_md->getCount(NULL)[0];
		$tienda = $this->suc_md->get(NULL,["sucu"=> $id_tienda])[0];
		$data["existencias"] = $this->ex_lun_md->getLunExist(NULL,$id_tienda);
		$data["existenciasnot"] = $this->ex_lun_md->getLunExistNot(NULL,$id_tienda);
		$data["view"]=$this->load->view("Lunes/lunpedido", $data, TRUE);
		$this->jsonResponse($data);
	}

	public function volpedid($id_tienda){
		$user = $this->session->userdata();
		$data["title"]="LISTADO EXISTENCIAS VOLÚMENES";
		$data["noprod"] = $this->prod_mdl->getVolCount(NULL)[0];
		$data["cuantas"] = $this->ex_lun_md->getVolTienda(NULL,$id_tienda)[0];
		$data["existencias"] = $this->ex_lun_md->getVolExist(NULL,$id_tienda);
		$data["existenciasnot"] = $this->ex_lun_md->getVolExistNot(NULL,$id_tienda);
		$data["view"]=$this->load->view("Lunes/volpedido", $data, TRUE);
		
		$this->jsonResponse($data);
	}

	public function allpedid($id_tienda){
		$user = $this->session->userdata();
		$data["title"]="LISTADO EXISTENCIAS GENERAL";
		$data["noprod"] = $this->prod_mdl->getAllCount(NULL)[0];
		$data["cuantas"] = $this->ex_lun_md->getAllTienda(NULL,$id_tienda)[0];
		$data["existencias"] = $this->ex_lun_md->getAllExist(NULL,$id_tienda);
		$data["existenciasnot"] = $this->ex_lun_md->getAllExistNot(NULL,$id_tienda);
		$data["view"]=$this->load->view("Lunes/allpedido", $data, TRUE);
		
		$this->jsonResponse($data);
	}

	public function fill_plantilla(){
		ini_set("memory_limit", "-1");
		ini_set("max_execution_time", "-1");
		$this->load->library("excelfile");
		$hoja = $this->excelfile->setActiveSheetIndex(0);
		$this->excelfile->setActiveSheetIndex(0)->setTitle("EXISTENCIAS");
		$styleArray = array(
		  'borders' => array(
		    'allborders' => array(
		      'style' => PHPExcel_Style_Border::BORDER_THIN
		    )
		  )
		);

		$hoja->getColumnDimension('A')->setWidth("10");
		$hoja->getColumnDimension('B')->setWidth("10");
		$hoja->getColumnDimension('D')->setWidth("22");
		$hoja->getColumnDimension('C')->setWidth("10");
		$hoja->getColumnDimension('E')->setWidth("70");

		$productos = $this->ex_lun_md->getPlantilla(NULL);
		$alias = "";
		$flag = 1;

		
		if ($productos){
			foreach ($productos as $key => $value){
				if ($alias <> $value->alias) {
					if ($flag <> 1) {
						$flag++;
						$flag++;
					}
					$alias = $value->alias;
					$hoja->mergeCells('A'.$flag.':E'.$flag);
					$this->cellStyle("A".$flag."", "4f81bd", "FFFFFF", TRUE, 12, "Franklin Gothic Book");
					$hoja->setCellValue("A".$flag."", $value->nombre);
					$this->excelfile->getActiveSheet()->getStyle('A'.$flag.':E'.$flag.'')->applyFromArray($styleArray);
					$flag++;
					$this->excelfile->getActiveSheet()->getStyle('A'.$flag.':E'.$flag.'')->applyFromArray($styleArray);
					$this->cellStyle("A".$flag."", "1f497d", "FFFFFF", TRUE, 12, "Franklin Gothic Book");
					$this->cellStyle("B".$flag."", "1f497d", "FFFFFF", TRUE, 12, "Franklin Gothic Book");
					$this->cellStyle("C".$flag."", "1f497d", "FFFFFF", TRUE, 12, "Franklin Gothic Book");
					$this->cellStyle("D".$flag."", "1f497d", "FFFFFF", TRUE, 12, "Franklin Gothic Book");
					$this->cellStyle("E".$flag."", "1f497d", "FFFFFF", TRUE, 12, "Franklin Gothic Book");
					$hoja->setCellValue("A".$flag."", "CAJA");
					$hoja->setCellValue("B".$flag."", "PZAS");
					$hoja->setCellValue("C".$flag."", "PEDIDO");
					$hoja->setCellValue("D".$flag."", "CÓDIGO");
					$hoja->setCellValue("E".$flag."", "DESCRIPCIÓN");
					$flag++;
					$this->excelfile->getActiveSheet()->getStyle('A'.$flag.':E'.$flag.'')->applyFromArray($styleArray);
					$this->cellStyle("D".$flag."", "FFFFFF", "000000", FALSE, 10, "Franklin Gothic Book");
					$this->cellStyle("E".$flag."", "FFFFFF", "000000", FALSE, 10, "Franklin Gothic Book");
					$hoja->setCellValue("D".$flag."", $value->codigo)->getStyle("D{$flag}")->getNumberFormat()->setFormatCode('# ???/???');
					$hoja->setCellValue("E".$flag."", $value->descripcion);
					$flag++;
				}else{
					$this->excelfile->getActiveSheet()->getStyle('A'.$flag.':E'.$flag.'')->applyFromArray($styleArray);
					$this->cellStyle("D".$flag."", "FFFFFF", "000000", FALSE, 10, "Franklin Gothic Book");
					$this->cellStyle("E".$flag."", "FFFFFF", "000000", FALSE, 10, "Franklin Gothic Book");
					$hoja->setCellValue("D".$flag."", $value->codigo)->getStyle("D{$flag}")->getNumberFormat()->setFormatCode('# ???/???');
					$hoja->setCellValue("E".$flag."", $value->descripcion);
					$flag++;
				}
			}
		}

		$dias = array("DOMINGO","LUNES","MARTES","MIÉRCOLES","JUEVES","VIERNES","SÁBADO");
		$meses = array("ENERO","FEBRERO","MARZO","ABRIL","MAYO","JUNIO","JULIO","AGOSTO","SEPTIEMBRE","OCTUBRE","NOVIEMBRE","DICIEMBRE");
		$fecha =  $dias[date('w')]." ".date('d')." DE ".$meses[date('n')-1]. " DEL ".date('Y') ;
		$file_name = "Formato Existencias Lunes ".$fecha.".xlsx"; //Nombre del documento con extención
		$excel_Writer = PHPExcel_IOFactory::createWriter($this->excelfile, "Excel2007");
		header("Content-Type: application/vnd.ms-excel; charset=utf-8");
		header("Content-Disposition: attachment;filename=".$file_name);
		header("Cache-Control: max-age=0");
		$excel_Writer->save("php://output");
	}


	public function print_precios(){
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
		$hoja->setCellValue("B2", "DESCRIPCIÓN")->getColumnDimension('C')->setWidth(20);
		$hoja->setCellValue("C1", "PRECIO")->getColumnDimension('C')->setWidth(20);
		$hoja->setCellValue("C2", "SISTEMA")->getColumnDimension('C')->setWidth(20);

		$productos = $this->prolu_md->get();
		$row_print = 3;
		if ($productos){
			foreach ($productos as $key => $value){
				$hoja->setCellValue("A{$row_print}", $value->codigo);
				$hoja->setCellValue("B{$row_print}", $value->descripcion);
				$hoja->setCellValue("C{$row_print}", $value->sistema);
				$row_print +=1;
			}
		}


		$hoja->getStyle("A3:C{$row_print}")
                 ->getAlignment()
                 ->setHorizontal(\PHPExcel_Style_Alignment::HORIZONTAL_LEFT);
		$hoja->getStyle("C3:C{$row_print}")
                 ->getAlignment()
                 ->setHorizontal(\PHPExcel_Style_Alignment::HORIZONTAL_LEFT);

        $dias = array("DOMINGO","LUNES","MARTES","MIÉRCOLES","JUEVES","VIERNES","SÁBADO");
		$meses = array("ENERO","FEBRERO","MARZO","ABRIL","MAYO","JUNIO","JULIO","AGOSTO","SEPTIEMBRE","OCTUBRE","NOVIEMBRE","DICIEMBRE");

		$fecha =  $dias[date('w')]." ".date('d')." DE ".$meses[date('n')-1]. " DEL ".date('Y') ;
		$file_name = "PRECIOS SISTEMA LUNES ".$fecha.".xlsx"; //Nombre del documento con extención
		header("Content-Type: application/vnd.ms-excel; charset=utf-8");
		header("Content-Disposition: attachment;filename=".$file_name);
		header("Cache-Control: max-age=0");
		$excel_Writer = PHPExcel_IOFactory::createWriter($this->excelfile, "Excel2007");
		$excel_Writer->save("php://output");
	}

	public function excel_semana(){
		ini_set("memory_limit", "-1");
		ini_set("max_execution_time", "-1");
		$this->load->library("excelfile");
		$proveedor = $this->prove_md->get(NULL);
		$pedide = $this->excelfile->setActiveSheetIndex(0)->setTitle("EXISTENCIAS");

		$this->excelfile->createSheet();
		$totales = $this->excelfile->setActiveSheetIndex(1)->setTitle("PEDIDOS LUNES");

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

		$ced="=";$sup="=";$aba="=";$ped="=";$tie="=";$ult="=";$tri="=";$mer="=";$ten="=";$tij="=";

		foreach ($proveedor as $key => $va) {
			$infos = $this->prolu_md->printProdis(NULL,$va->id_proveedor,$tiendas->total);
			if ($infos) {
				if (1 == 1) {
					$key = 1;
					$this->excelfile->setActiveSheetIndex($key);
					$proveedor[$key]->estatus = $this->excelfile->getActiveSheet();
					$proveedor[$key]->estatus->mergeCells('A'.$flag.':BU'.$flag);
					$this->cellStyle("A".$flag, "FFFFFF", "000000", TRUE, 12, "Franklin Gothic Book");
					$proveedor[$key]->estatus->setCellValue("A".$flag."", "CEDIS, ABARROTES,PEDREGAL, TIENDA, ULTRAMARINOS, TRINCHERAS, MERCADO, TIJERAS, Y TENENCIA AZTECA AUTOSERVICIOS SA. DE CV.");
					$this->excelfile->getActiveSheet()->getStyle('A'.$flag.':BU'.$flag)->applyFromArray($styleArray);
					$flag++;
					$this->excelfile->getActiveSheet()->getStyle('A'.$flag.':BU'.$flag)->applyFromArray($styleArray);
					$proveedor[$key]->estatus->getColumnDimension('A')->setWidth("25");
					$proveedor[$key]->estatus->getColumnDimension('C')->setWidth("70");
					
					$proveedor[$key]->estatus->getColumnDimension('AY')->setWidth("40");
					$proveedor[$key]->estatus->getColumnDimension('AZ')->setWidth("40");
					$proveedor[$key]->estatus->getColumnDimension('D')->setWidth("15");
					$proveedor[$key]->estatus->getColumnDimension('E')->setWidth("15");
					$proveedor[$key]->estatus->getColumnDimension('F')->setWidth("15");
					$proveedor[$key]->estatus->mergeCells('A'.$flag.':C'.$flag);
					$this->cellStyle("A".$flag, "FFFFFF", "000000", TRUE, 12, "Franklin Gothic Book");
					$proveedor[$key]->estatus->setCellValue("A".$flag."", "PEDIDO A ".$va->nombre);
					$proveedor[$key]->estatus->mergeCells('D'.$flag.':F'.$flag);
					$this->cellStyle("D".$flag, "FFFFFF", "000000", TRUE, 12, "Franklin Gothic Book");
					$proveedor[$key]->estatus->setCellValue("D".$flag."", $fecha);
					$proveedor[$key]->estatus->mergeCells('G'.$flag.':J'.$flag);
					$this->cellStyle("G".$flag, "C00000", "000000", TRUE, 12, "Franklin Gothic Book");
					$proveedor[$key]->estatus->setCellValue("G".$flag, "CEDIS/SUPER");
					$proveedor[$key]->estatus->mergeCells('K'.$flag.':N'.$flag);
					$this->cellStyle("K".$flag, "C2B90A", "000000", TRUE, 12, "Franklin Gothic Book");
					$proveedor[$key]->estatus->setCellValue("K".$flag, "SUMA CEDIS/SUPER");
					$proveedor[$key]->estatus->mergeCells('O'.$flag.':R'.$flag);
					$proveedor[$key]->estatus->setCellValue("O".$flag, "CD INDUSTRIAL");
					$this->cellStyle("O".$flag, "FF0066", "000000", TRUE, 12, "Franklin Gothic Book");
					$proveedor[$key]->estatus->mergeCells('S'.$flag.':V'.$flag);
					$this->cellStyle("S".$flag, "01B0F0", "000000", TRUE, 12, "Franklin Gothic Book");
					$proveedor[$key]->estatus->setCellValue("S".$flag, "ABARROTES");
					$proveedor[$key]->estatus->mergeCells('W'.$flag.':Z'.$flag);
					$this->cellStyle("W".$flag, "FF0000", "000000", TRUE, 12, "Franklin Gothic Book");
					$proveedor[$key]->estatus->setCellValue("W".$flag, "PEDREGAL");
					$proveedor[$key]->estatus->mergeCells('AA'.$flag.':AD'.$flag);
					$this->cellStyle("AA".$flag, "E26C0B", "000000", TRUE, 12, "Franklin Gothic Book");
					$proveedor[$key]->estatus->setCellValue("AA".$flag, "TIENDA");
					$proveedor[$key]->estatus->mergeCells('AE'.$flag.':AH'.$flag);
					$this->cellStyle("AE".$flag, "C5C5C5", "000000", TRUE, 12, "Franklin Gothic Book");
					$proveedor[$key]->estatus->setCellValue("AE".$flag, "ULTRAMARINOS");
					$proveedor[$key]->estatus->mergeCells('AI'.$flag.':AL'.$flag);
					$this->cellStyle("AI".$flag, "92D051", "000000", TRUE, 12, "Franklin Gothic Book");
					$proveedor[$key]->estatus->setCellValue("AI".$flag, "TRINCHERAS");
					$proveedor[$key]->estatus->mergeCells('AM'.$flag.':AP'.$flag);
					$this->cellStyle("AM".$flag, "B1A0C7", "000000", TRUE, 12, "Franklin Gothic Book");
					$proveedor[$key]->estatus->setCellValue("AM".$flag, "AZT MERCADO");
					$proveedor[$key]->estatus->mergeCells('AQ'.$flag.':AT'.$flag);
					$this->cellStyle("AQ".$flag, "DA9694", "000000", TRUE, 12, "Franklin Gothic Book");
					$proveedor[$key]->estatus->setCellValue("AQ".$flag, "TENENCIA");
					$proveedor[$key]->estatus->mergeCells('AU'.$flag.':AX'.$flag);
					$this->cellStyle("AU".$flag, "4CACC6", "000000", TRUE, 12, "Franklin Gothic Book");
					$proveedor[$key]->estatus->setCellValue("AU".$flag, "TIJERAS");
					$this->cellStyle("AY".$flag, "FFFF00", "FF0000", TRUE, 12, "Franklin Gothic Book");
					$proveedor[$key]->estatus->setCellValue("AY".$flag, "PROMOCIÓN");
					$this->cellStyle("AZ".$flag, "92D050", "FF0000", TRUE, 12, "Franklin Gothic Book");
					$proveedor[$key]->estatus->setCellValue("AZ".$flag, "NOTA");

					$flag++;
					$this->excelfile->getActiveSheet()->getStyle('A'.$flag.':BV'.$flag)->applyFromArray($styleArray);
					$proveedor[$key]->estatus->mergeCells('A'.$flag.':F'.$flag);
					$this->cellStyle("A".$flag, "000000", "FFFFFF", TRUE, 12, "Franklin Gothic Book");
					$proveedor[$key]->estatus->setCellValue("A".$flag."", "DESCRIPCIÓN");

					$proveedor[$key]->estatus->mergeCells('G'.$flag.':J'.$flag);
					$this->cellStyle("G".$flag, "000000", "FFFFFF", TRUE, 12, "Franklin Gothic Book");
					$proveedor[$key]->estatus->setCellValue("G".$flag."", "EXISTENCIAS");

					$proveedor[$key]->estatus->mergeCells('K'.$flag.':N'.$flag);
					$this->cellStyle("K".$flag, "000000", "FFFFFF", TRUE, 12, "Franklin Gothic Book");
					$proveedor[$key]->estatus->setCellValue("K".$flag."", "EXISTENCIAS");

					$proveedor[$key]->estatus->mergeCells('O'.$flag.':X'.$flag);
					$this->cellStyle("O".$flag, "000000", "FFFFFF", TRUE, 12, "Franklin Gothic Book");
					$proveedor[$key]->estatus->setCellValue("O".$flag."", "EXISTENCIAS");

					$proveedor[$key]->estatus->mergeCells('S'.$flag.':AD'.$flag);
					$this->cellStyle("S".$flag, "000000", "FFFFFF", TRUE, 12, "Franklin Gothic Book");
					$proveedor[$key]->estatus->setCellValue("S".$flag."", "EXISTENCIAS");

					$proveedor[$key]->estatus->mergeCells('W'.$flag.':AJ'.$flag);
					$this->cellStyle("W".$flag, "000000", "FFFFFF", TRUE, 12, "Franklin Gothic Book");
					$proveedor[$key]->estatus->setCellValue("W".$flag."", "EXISTENCIAS");

					$proveedor[$key]->estatus->mergeCells('AA'.$flag.':AP'.$flag);
					$this->cellStyle("AA".$flag, "000000", "FFFFFF", TRUE, 12, "Franklin Gothic Book");
					$proveedor[$key]->estatus->setCellValue("AA".$flag."", "EXISTENCIAS");

					$proveedor[$key]->estatus->mergeCells('AE'.$flag.':AV'.$flag);
					$this->cellStyle("AE".$flag, "000000", "FFFFFF", TRUE, 12, "Franklin Gothic Book");
					$proveedor[$key]->estatus->setCellValue("AE".$flag."", "EXISTENCIAS");

					$proveedor[$key]->estatus->mergeCells('AI'.$flag.':BB'.$flag);
					$this->cellStyle("AI".$flag, "000000", "FFFFFF", TRUE, 12, "Franklin Gothic Book");
					$proveedor[$key]->estatus->setCellValue("AI".$flag."", "EXISTENCIAS");

					$proveedor[$key]->estatus->mergeCells('AM'.$flag.':BH'.$flag);
					$this->cellStyle("AM".$flag, "000000", "FFFFFF", TRUE, 12, "Franklin Gothic Book");
					$proveedor[$key]->estatus->setCellValue("AM".$flag."", "EXISTENCIAS");

					$proveedor[$key]->estatus->mergeCells('AQ'.$flag.':BN'.$flag);
					$this->cellStyle("AQ".$flag, "000000", "FFFFFF", TRUE, 12, "Franklin Gothic Book");
					$proveedor[$key]->estatus->setCellValue("AQ".$flag."", "EXISTENCIAS");

					$proveedor[$key]->estatus->mergeCells('AU'.$flag.':BT'.$flag);
					$this->cellStyle("AU".$flag, "000000", "FFFFFF", TRUE, 12, "Franklin Gothic Book");
					$proveedor[$key]->estatus->setCellValue("AU".$flag."", "EXISTENCIAS");

					$this->cellStyle("AY".$flag, "FFFF00", "FF0000", TRUE, 12, "Franklin Gothic Book");
					$this->cellStyle("AZ".$flag, "92D050", "FF0000", TRUE, 12, "Franklin Gothic Book");
					
					$flag++;
					$this->excelfile->getActiveSheet()->getStyle('A'.$flag.':AZ'.$flag)->applyFromArray($styleArray);
					$proveedor[$key]->estatus->mergeCells('A'.$flag.':C'.$flag);
					$this->cellStyle("A".$flag, "000000", "FFFFFF", TRUE, 12, "Franklin Gothic Book");
					$this->cellStyle("D".$flag, "000000", "FFFFFF", FALSE, 10, "Franklin Gothic Book");
					$proveedor[$key]->estatus->setCellValue("D".$flag."", "PRECIO");
					$this->cellStyle("E".$flag, "000000", "FFFFFF", FALSE, 10, "Franklin Gothic Book");
					$proveedor[$key]->estatus->setCellValue("E".$flag."", "SISTEMA");
					$this->cellStyle("F".$flag, "000000", "FFFFFF", FALSE, 10, "Franklin Gothic Book");
					$proveedor[$key]->estatus->setCellValue("F".$flag."", "UM");

					$this->cellStyle("G".$flag.':AX', "000000", "FFFFFF", FALSE, 10, "Franklin Gothic Book");
					$proveedor[$key]->estatus->setCellValue("G".$flag."", "CAJAS");
					$proveedor[$key]->estatus->setCellValue("H".$flag."", "PZAS");
					$proveedor[$key]->estatus->setCellValue("I".$flag."", "PEND");
					$proveedor[$key]->estatus->setCellValue("J".$flag."", "PEDIDO");
					$proveedor[$key]->estatus->setCellValue("K".$flag."", "CAJAS");
					$proveedor[$key]->estatus->setCellValue("L".$flag."", "PZAS");
					$proveedor[$key]->estatus->setCellValue("M".$flag."", "PEND");
					$proveedor[$key]->estatus->setCellValue("N".$flag."", "PEDIDO");
					$proveedor[$key]->estatus->setCellValue("O".$flag."", "CAJAS");
					$proveedor[$key]->estatus->setCellValue("P".$flag."", "PZAS");
					$proveedor[$key]->estatus->setCellValue("Q".$flag."", "PEND");
					$proveedor[$key]->estatus->setCellValue("R".$flag."", "PEDIDO");////a
					$proveedor[$key]->estatus->setCellValue("S".$flag."", "CAJAS");
					$proveedor[$key]->estatus->setCellValue("T".$flag."", "PZAS");
					$proveedor[$key]->estatus->setCellValue("U".$flag."", "PEND");
					$proveedor[$key]->estatus->setCellValue("V".$flag."", "PEDIDO");
					$proveedor[$key]->estatus->setCellValue("W".$flag."", "CAJAS");
					$proveedor[$key]->estatus->setCellValue("X".$flag."", "PZAS");
					$proveedor[$key]->estatus->setCellValue("Y".$flag."", "PEND");
					$proveedor[$key]->estatus->setCellValue("Z".$flag."", "PEDIDO");
					$proveedor[$key]->estatus->setCellValue("AA".$flag."", "CAJAS");
					$proveedor[$key]->estatus->setCellValue("AB".$flag."", "PZAS");
					$proveedor[$key]->estatus->setCellValue("AC".$flag."", "PEND");
					$proveedor[$key]->estatus->setCellValue("AD".$flag."", "PEDIDO");
					$proveedor[$key]->estatus->setCellValue("AE".$flag."", "CAJAS");
					$proveedor[$key]->estatus->setCellValue("AF".$flag."", "PZAS");
					$proveedor[$key]->estatus->setCellValue("AG".$flag."", "PEND");
					$proveedor[$key]->estatus->setCellValue("AH".$flag."", "PEDIDO");
					$proveedor[$key]->estatus->setCellValue("AI".$flag."", "CAJAS");
					$proveedor[$key]->estatus->setCellValue("AJ".$flag."", "PZAS");
					$proveedor[$key]->estatus->setCellValue("AK".$flag."", "PEND");
					$proveedor[$key]->estatus->setCellValue("AL".$flag."", "PEDIDO");
					$proveedor[$key]->estatus->setCellValue("AM".$flag."", "CAJAS");
					$proveedor[$key]->estatus->setCellValue("AN".$flag."", "PZAS");
					$proveedor[$key]->estatus->setCellValue("AO".$flag."", "PEND");
					$proveedor[$key]->estatus->setCellValue("AP".$flag."", "PEDIDO");
					$proveedor[$key]->estatus->setCellValue("AQ".$flag."", "CAJAS");
					$proveedor[$key]->estatus->setCellValue("AR".$flag."", "PZAS");
					$proveedor[$key]->estatus->setCellValue("AS".$flag."", "PEND");
					$proveedor[$key]->estatus->setCellValue("AT".$flag."", "PEDIDO");
					$proveedor[$key]->estatus->setCellValue("AU".$flag."", "CAJAS");
					$proveedor[$key]->estatus->setCellValue("AV".$flag."", "PZAS");
					$proveedor[$key]->estatus->setCellValue("AW".$flag."", "PEND");
					$proveedor[$key]->estatus->setCellValue("AX".$flag."", "PEDIDO");


					$this->cellStyle("AY".$flag, "FFFF00", "FF0000", FALSE, 10, "Franklin Gothic Book");
					$this->cellStyle("AZ".$flag, "92D050", "FF0000", TRUE, 12, "Franklin Gothic Book");

					$this->excelfile->getActiveSheet()->getStyle('BB'.$flag.':BL'.$flag)->applyFromArray($styleArray);

					$this->cellStyle("BB".$flag, "C00000", "000000", TRUE, 12, "Franklin Gothic Book");
					$this->cellStyle("BC".$flag, "FF0066", "000000", TRUE, 12, "Franklin Gothic Book");
					$this->cellStyle("BD".$flag, "01B0F0", "000000", TRUE, 12, "Franklin Gothic Book");
					$this->cellStyle("BE".$flag, "FF0000", "000000", TRUE, 12, "Franklin Gothic Book");
					$this->cellStyle("BF".$flag, "E26C0B", "000000", TRUE, 12, "Franklin Gothic Book");
					$this->cellStyle("BG".$flag, "C5C5C5", "000000", TRUE, 12, "Franklin Gothic Book");
					$this->cellStyle("BH".$flag, "92D051", "000000", TRUE, 12, "Franklin Gothic Book");
					$this->cellStyle("BI".$flag, "B1A0C7", "000000", TRUE, 12, "Franklin Gothic Book");
					$this->cellStyle("BJ".$flag, "DA9694", "000000", TRUE, 12, "Franklin Gothic Book");
					$this->cellStyle("BK".$flag, "4CACC6", "000000", TRUE, 12, "Franklin Gothic Book");
					$this->cellStyle("BL".$flag, "000000", "FFFFFF", TRUE, 12, "Franklin Gothic Book");
					
					$proveedor[$key]->estatus->setCellValue("BL".$flag."", "TOTAL");
					
					$proveedor[$key]->estatus->getColumnDimension('BB')->setWidth("15");
					$proveedor[$key]->estatus->getColumnDimension('BC')->setWidth("15");
					$proveedor[$key]->estatus->getColumnDimension('BD')->setWidth("15");
					$proveedor[$key]->estatus->getColumnDimension('BE')->setWidth("15");
					$proveedor[$key]->estatus->getColumnDimension('BF')->setWidth("15");
					$proveedor[$key]->estatus->getColumnDimension('BG')->setWidth("15");
					$proveedor[$key]->estatus->getColumnDimension('BH')->setWidth("15");
					$proveedor[$key]->estatus->getColumnDimension('BI')->setWidth("15");
					$proveedor[$key]->estatus->getColumnDimension('BJ')->setWidth("15");
					$proveedor[$key]->estatus->getColumnDimension('BK')->setWidth("15");
					$proveedor[$key]->estatus->getColumnDimension('BL')->setWidth("15");
				}
				$flageas = $flag+1;
				foreach ($infos as $keys => $v) {
					$this->excelfile->setActiveSheetIndex($key);
					$proveedor[0]->estatus = $this->excelfile->getActiveSheet();
					$flag++;
					
					$this->excelfile->setActiveSheetIndex($key);
					$this->excelfile->getActiveSheet()->getStyle('A'.$flag.':AZ'.$flag)->applyFromArray($styleArray);
					$proveedor[$key]->estatus->setCellValue("A".$flag."", $v["codigo"])->getStyle("A{$flag}")->getNumberFormat()->setFormatCode('# ???/???');
					$this->cellStyle("A".$flag, "FFFFFF", "000000", FALSE, 10, "Franklin Gothic Book");
					$proveedor[$key]->estatus->setCellValue("C".$flag."", $v["descripcion"]);
					$this->cellStyle("C".$flag, "FFFFFF", "000000", FALSE, 10, "Franklin Gothic Book");
					$proveedor[$key]->estatus->setCellValue("D{$flag}", $v["precio"])->getStyle("D{$flag}")->getNumberFormat()->setFormatCode('"$"#,##0.00_-');
					$this->cellStyle("D".$flag, "FFFFFF", "000000", FALSE, 10, "Franklin Gothic Book");
					$proveedor[$key]->estatus->setCellValue("E{$flag}", $v["sistema"])->getStyle("E{$flag}")->getNumberFormat()->setFormatCode('"$"#,##0.00_-');
					$this->cellStyle("E".$flag, "FFFFFF", "000000", FALSE, 10, "Franklin Gothic Book");
					$proveedor[$key]->estatus->setCellValue("F{$flag}", $v["unidad"]);

					$proveedor[$key]->estatus->setCellValue("AY{$flag}", $v["observaciones"]);					

					$this->cellStyle("F".$flag, "FFFFFF", "000000", FALSE, 10, "Franklin Gothic Book");
					$this->cellStyle("B".$flag, "FFFFFF", "000000", FALSE, 10, "Franklin Gothic Book");
					if ($v["codigo"] == "490676"){$proveedor[$key]->estatus->setCellValue("B{$flag}", "001");}
					if ($v["codigo"] == "490677"){$proveedor[$key]->estatus->setCellValue("B{$flag}", "002");}
					if ($v["codigo"] == "75010258035"){$proveedor[$key]->estatus->setCellValue("B{$flag}", "009");}
					if ($v["codigo"] == "490686"){$proveedor[$key]->estatus->setCellValue("B{$flag}", "014");}
					if ($v["codigo"] == "75010490679"){$proveedor[$key]->estatus->setCellValue("B{$flag}", "024");}
					if ($v["codigo"] == "7501021148"){$proveedor[$key]->estatus->setCellValue("B{$flag}", "063");}
					if ($v["codigo"] == "75030253007"){$proveedor[$key]->estatus->setCellValue("B{$flag}", "201");}
					if ($v["codigo"] == "750242549"){$proveedor[$key]->estatus->setCellValue("B{$flag}", "202");}
					if ($v["codigo"] == "7501021112183"){$proveedor[$key]->estatus->setCellValue("B{$flag}", "218");}
					if ($v["codigo"] == "7501021112206"){$proveedor[$key]->estatus->setCellValue("B{$flag}", "220");}
					if ($v["codigo"] == "490683"){$proveedor[$key]->estatus->setCellValue("B{$flag}", "505");}
					if ($v["codigo"] == "490684"){$proveedor[$key]->estatus->setCellValue("B{$flag}", "507");}
					if ($v["codigo"] == "490685"){$proveedor[$key]->estatus->setCellValue("B{$flag}", "508");}
					if ($v["codigo"] == "7501025803020"){$proveedor[$key]->estatus->setCellValue("B{$flag}", "516");}
					if ($v["codigo"] == "7506399016"){$proveedor[$key]->estatus->setCellValue("B{$flag}", "559");}
					if ($v["codigo"] == "7506399017"){$proveedor[$key]->estatus->setCellValue("B{$flag}", "609");}
					if ($v["codigo"] == "7506399018"){$proveedor[$key]->estatus->setCellValue("B{$flag}", "610");}
					if ($v["codigo"] == "242546"){$proveedor[$key]->estatus->setCellValue("B{$flag}", "617");}
					if ($v["codigo"] == "73501"){$proveedor[$key]->estatus->setCellValue("B{$flag}", "630");}
					if ($v["codigo"] == "490682"){$proveedor[$key]->estatus->setCellValue("B{$flag}", "631");}
					if ($v["codigo"] == "242548"){$proveedor[$key]->estatus->setCellValue("B{$flag}", "648");}
					if ($v["codigo"] == "242547"){$proveedor[$key]->estatus->setCellValue("B{$flag}", "654");}
					if ($v["codigo"] == "490680"){$proveedor[$key]->estatus->setCellValue("B{$flag}", "656");}
					if ($v["codigo"] == "490678"){$proveedor[$key]->estatus->setCellValue("B{$flag}", "713");}
					if ($v["codigo"] == "490681"){$proveedor[$key]->estatus->setCellValue("B{$flag}", "715");}
					if ($v["codigo"] == "490675"){$proveedor[$key]->estatus->setCellValue("B{$flag}", "725");}
					if ($v["codigo"] == "490679"){$proveedor[$key]->estatus->setCellValue("B{$flag}", "732");}
					if ($v["codigo"] == "7501028490679"){$proveedor[$key]->estatus->setCellValue("B{$flag}", "741");}

					
					

					$this->cellStyle("G{$flag}:AZ{$flag}", "FFFFFF", "000000", TRUE, 10, "Franklin Gothic Book");
					$this->cellStyle("J".$flag, "DCE6F1", "000000", TRUE, 10, "Franklin Gothic Book");
					$this->cellStyle("N".$flag, "DCE6F1", "000000", TRUE, 10, "Franklin Gothic Book");
					$this->cellStyle("R".$flag, "DCE6F1", "000000", TRUE, 10, "Franklin Gothic Book");
					$this->cellStyle("V".$flag, "DCE6F1", "000000", TRUE, 10, "Franklin Gothic Book");
					$this->cellStyle("Z".$flag, "DCE6F1", "000000", TRUE, 10, "Franklin Gothic Book");
					$this->cellStyle("AD".$flag, "DCE6F1", "000000", TRUE, 10, "Franklin Gothic Book");
					$this->cellStyle("AH".$flag, "DCE6F1", "000000", TRUE, 10, "Franklin Gothic Book");
					$this->cellStyle("AL".$flag, "DCE6F1", "000000", TRUE, 10, "Franklin Gothic Book");
					$this->cellStyle("AP".$flag, "DCE6F1", "000000", TRUE, 10, "Franklin Gothic Book");
					$this->cellStyle("AT".$flag, "DCE6F1", "000000", TRUE, 10, "Franklin Gothic Book");
					$this->cellStyle("AX".$flag, "DCE6F1", "000000", TRUE, 10, "Franklin Gothic Book");

					$this->excelfile->getActiveSheet()->getStyle('BB'.$flag.':BL'.$flag)->applyFromArray($styleArray);
					$this->cellStyle("BB".$flag, "FFFFFF", "000000", FALSE, 10, "Franklin Gothic Book");
					$proveedor[$key]->estatus->setCellValue("BB{$flag}", "=D{$flag}*J{$flag}")->getStyle("BB{$flag}")->getNumberFormat()->setFormatCode('"$"#,##0.00_-');
					$this->cellStyle("BC".$flag, "FFFFFF", "000000", FALSE, 10, "Franklin Gothic Book");
					$proveedor[$key]->estatus->setCellValue("BC{$flag}", "=D{$flag}*R{$flag}")->getStyle("BC{$flag}")->getNumberFormat()->setFormatCode('"$"#,##0.00_-');
					$this->cellStyle("BD".$flag, "FFFFFF", "000000", FALSE, 10, "Franklin Gothic Book");
					$proveedor[$key]->estatus->setCellValue("BD{$flag}", "=D{$flag}*V{$flag}")->getStyle("BD{$flag}")->getNumberFormat()->setFormatCode('"$"#,##0.00_-');
					$this->cellStyle("BE".$flag, "FFFFFF", "000000", FALSE, 10, "Franklin Gothic Book");
					$proveedor[$key]->estatus->setCellValue("BE{$flag}", "=D{$flag}*Z{$flag}")->getStyle("BE{$flag}")->getNumberFormat()->setFormatCode('"$"#,##0.00_-');
					$this->cellStyle("BF".$flag, "FFFFFF", "000000", FALSE, 10, "Franklin Gothic Book");
					$proveedor[$key]->estatus->setCellValue("BF{$flag}", "=D{$flag}*AD{$flag}")->getStyle("BF{$flag}")->getNumberFormat()->setFormatCode('"$"#,##0.00_-');
					$this->cellStyle("BG".$flag, "FFFFFF", "000000", FALSE, 10, "Franklin Gothic Book");
					$proveedor[$key]->estatus->setCellValue("BG{$flag}", "=D{$flag}*AH{$flag}")->getStyle("BG{$flag}")->getNumberFormat()->setFormatCode('"$"#,##0.00_-');
					$this->cellStyle("BH".$flag, "FFFFFF", "000000", FALSE, 10, "Franklin Gothic Book");
					$proveedor[$key]->estatus->setCellValue("BH{$flag}", "=D{$flag}*AL{$flag}")->getStyle("BH{$flag}")->getNumberFormat()->setFormatCode('"$"#,##0.00_-');
					$this->cellStyle("BI".$flag, "FFFFFF", "000000", FALSE, 10, "Franklin Gothic Book");
					$proveedor[$key]->estatus->setCellValue("BI{$flag}", "=D{$flag}*AP{$flag}")->getStyle("BI{$flag}")->getNumberFormat()->setFormatCode('"$"#,##0.00_-');
					$this->cellStyle("BJ".$flag, "FFFFFF", "000000", FALSE, 10, "Franklin Gothic Book");
					$proveedor[$key]->estatus->setCellValue("BJ{$flag}", "=D{$flag}*AT{$flag}")->getStyle("BJ{$flag}")->getNumberFormat()->setFormatCode('"$"#,##0.00_-');
					$this->cellStyle("BK".$flag, "FFFFFF", "000000", FALSE, 10, "Franklin Gothic Book");
					$proveedor[$key]->estatus->setCellValue("BK{$flag}", "=D{$flag}*AX{$flag}")->getStyle("BK{$flag}")->getNumberFormat()->setFormatCode('"$"#,##0.00_-');
					$this->cellStyle("BL".$flag, "FFFFFF", "000000", FALSE, 10, "Franklin Gothic Book");
					$proveedor[$key]->estatus->setCellValue("BL{$flag}", "=SUM(BB{$flag}:BK{$flag})")->getStyle("BL{$flag}")->getNumberFormat()->setFormatCode('"$"#,##0.00_-');

					$col = 7;
					 foreach ($v["existencias"] as $k => $vs) {
						$proveedor[$key]->estatus->setCellValueByColumnAndRow($col, $flag, $vs["cja"]);
						$col++;
						$proveedor[$key]->estatus->setCellValueByColumnAndRow($col, $flag, $vs["pzs"]);
						$col+=2;
						$proveedor[$key]->estatus->setCellValueByColumnAndRow($col, $flag, $vs["ped"]);
						$col++;
					 }
					 

					 $proveedor[$key]->estatus->setCellValue("I{$flag}", $v["pend"][1]["pend"]);
					 $proveedor[$key]->estatus->setCellValue("U{$flag}", $v["pend"][4]["pend"]);
					 $proveedor[$key]->estatus->setCellValue("Y{$flag}", $v["pend"][5]["pend"]);
					 $proveedor[$key]->estatus->setCellValue("AC{$flag}", $v["pend"][6]["pend"]);
					 $proveedor[$key]->estatus->setCellValue("AG{$flag}", $v["pend"][7]["pend"]);
					 $proveedor[$key]->estatus->setCellValue("AK{$flag}", $v["pend"][8]["pend"]);
					 $proveedor[$key]->estatus->setCellValue("AO{$flag}", $v["pend"][9]["pend"]);
					 $proveedor[$key]->estatus->setCellValue("AS{$flag}", $v["pend"][10]["pend"]);
					 $proveedor[$key]->estatus->setCellValue("AW{$flag}", $v["pend"][11]["pend"]);

					 $proveedor[$key]->estatus->setCellValue("K{$flag}", "=G{$flag}+O{$flag}");
					 $proveedor[$key]->estatus->setCellValue("L{$flag}", "=H{$flag}+P{$flag}");
					 $proveedor[$key]->estatus->setCellValue("M{$flag}", "=I{$flag}+Q{$flag}");
					 $proveedor[$key]->estatus->setCellValue("N{$flag}", "=J{$flag}+R{$flag}");
					
				}
				$flag++;

				$this->excelfile->getActiveSheet()->getStyle('J'.$flag)->applyFromArray($styleArray);
				$this->cellStyle("J".$flag, "FFFFFF", "000000", FALSE, 10, "Franklin Gothic Book");
				$proveedor[$key]->estatus->setCellValue("J{$flag}", "=SUM(J{$flageas}:J".($flag-1).")")->getStyle("J{$flag}")->getNumberFormat()->setFormatCode('#0_-');
				$this->excelfile->getActiveSheet()->getStyle('N'.$flag)->applyFromArray($styleArray);
				$this->cellStyle("N".$flag, "FFFFFF", "000000", FALSE, 10, "Franklin Gothic Book");
				$proveedor[$key]->estatus->setCellValue("N{$flag}", "=SUM(N{$flageas}:N".($flag-1).")")->getStyle("N{$flag}")->getNumberFormat()->setFormatCode('#0_-');
				$this->excelfile->getActiveSheet()->getStyle('R'.$flag)->applyFromArray($styleArray);
				$this->cellStyle("R".$flag, "FFFFFF", "000000", FALSE, 10, "Franklin Gothic Book");
				$proveedor[$key]->estatus->setCellValue("R{$flag}", "=SUM(R{$flageas}:R".($flag-1).")")->getStyle("R{$flag}")->getNumberFormat()->setFormatCode('#0_-');
				$this->excelfile->getActiveSheet()->getStyle('V'.$flag)->applyFromArray($styleArray);
				$this->cellStyle("V".$flag, "FFFFFF", "000000", FALSE, 10, "Franklin Gothic Book");
				$proveedor[$key]->estatus->setCellValue("V{$flag}", "=SUM(V{$flageas}:V".($flag-1).")")->getStyle("V{$flag}")->getNumberFormat()->setFormatCode('#0_-');
				$this->excelfile->getActiveSheet()->getStyle('Z'.$flag)->applyFromArray($styleArray);
				$this->cellStyle("Z".$flag, "FFFFFF", "000000", FALSE, 10, "Franklin Gothic Book");
				$proveedor[$key]->estatus->setCellValue("Z{$flag}", "=SUM(Z{$flageas}:Z".($flag-1).")")->getStyle("Z{$flag}")->getNumberFormat()->setFormatCode('#0_-');
				$this->excelfile->getActiveSheet()->getStyle('AD'.$flag)->applyFromArray($styleArray);
				$this->cellStyle("AD".$flag, "FFFFFF", "000000", FALSE, 10, "Franklin Gothic Book");
				$proveedor[$key]->estatus->setCellValue("AD{$flag}", "=SUM(AD{$flageas}:AD".($flag-1).")")->getStyle("AD{$flag}")->getNumberFormat()->setFormatCode('#0_-');
				$this->excelfile->getActiveSheet()->getStyle('AH'.$flag)->applyFromArray($styleArray);
				$this->cellStyle("AH".$flag, "FFFFFF", "000000", FALSE, 10, "Franklin Gothic Book");
				$proveedor[$key]->estatus->setCellValue("AH{$flag}", "=SUM(AH{$flageas}:AH".($flag-1).")")->getStyle("AH{$flag}")->getNumberFormat()->setFormatCode('#0_-');
				$this->excelfile->getActiveSheet()->getStyle('AL'.$flag)->applyFromArray($styleArray);
				$this->cellStyle("AL".$flag, "FFFFFF", "000000", FALSE, 10, "Franklin Gothic Book");
				$proveedor[$key]->estatus->setCellValue("AL{$flag}", "=SUM(AL{$flageas}:AL".($flag-1).")")->getStyle("AL{$flag}")->getNumberFormat()->setFormatCode('#0_-');
				$this->excelfile->getActiveSheet()->getStyle('AP'.$flag)->applyFromArray($styleArray);
				$this->cellStyle("AP".$flag, "FFFFFF", "000000", FALSE, 10, "Franklin Gothic Book");
				$proveedor[$key]->estatus->setCellValue("AP{$flag}", "=SUM(AP{$flageas}:AP".($flag-1).")")->getStyle("AP{$flag}")->getNumberFormat()->setFormatCode('#0_-');
				$this->excelfile->getActiveSheet()->getStyle('AT'.$flag)->applyFromArray($styleArray);
				$this->cellStyle("AT".$flag, "FFFFFF", "000000", FALSE, 10, "Franklin Gothic Book");
				$proveedor[$key]->estatus->setCellValue("AT{$flag}", "=SUM(AT{$flageas}:AT".($flag-1).")")->getStyle("AT{$flag}")->getNumberFormat()->setFormatCode('#0_-');
				$this->excelfile->getActiveSheet()->getStyle('AX'.$flag)->applyFromArray($styleArray);
				$this->cellStyle("AX".$flag, "FFFFFF", "000000", FALSE, 10, "Franklin Gothic Book");
				$proveedor[$key]->estatus->setCellValue("AX{$flag}", "=SUM(AX{$flageas}:AX".($flag-1).")")->getStyle("AX{$flag}")->getNumberFormat()->setFormatCode('#0_-');

				$this->excelfile->getActiveSheet()->getStyle('BB'.$flag.':BL'.$flag)->applyFromArray($styleArray);
				$this->cellStyle("BB".$flag, "FFFFFF", "000000", FALSE, 10, "Franklin Gothic Book");
				$proveedor[$key]->estatus->setCellValue("BB{$flag}", "=SUM(BB{$flageas}:BB".($flag-1).")")->getStyle("BB{$flag}")->getNumberFormat()->setFormatCode('"$"#,##0.00_-');
				$this->cellStyle("BC".$flag, "FFFFFF", "000000", FALSE, 10, "Franklin Gothic Book");
				$proveedor[$key]->estatus->setCellValue("BC{$flag}", "=SUM(BC{$flageas}:BC".($flag-1).")")->getStyle("BC{$flag}")->getNumberFormat()->setFormatCode('"$"#,##0.00_-');
				$this->cellStyle("BD".$flag, "FFFFFF", "000000", FALSE, 10, "Franklin Gothic Book");
				$proveedor[$key]->estatus->setCellValue("BD{$flag}", "=SUM(BD{$flageas}:BD".($flag-1).")")->getStyle("BD{$flag}")->getNumberFormat()->setFormatCode('"$"#,##0.00_-');
				$this->cellStyle("BE".$flag, "FFFFFF", "000000", FALSE, 10, "Franklin Gothic Book");
				$proveedor[$key]->estatus->setCellValue("BE{$flag}", "=SUM(BE{$flageas}:BE".($flag-1).")")->getStyle("BE{$flag}")->getNumberFormat()->setFormatCode('"$"#,##0.00_-');
				$this->cellStyle("BF".$flag, "FFFFFF", "000000", FALSE, 10, "Franklin Gothic Book");
				$proveedor[$key]->estatus->setCellValue("BF{$flag}", "=SUM(BF{$flageas}:BF".($flag-1).")")->getStyle("BF{$flag}")->getNumberFormat()->setFormatCode('"$"#,##0.00_-');
				$this->cellStyle("BG".$flag, "FFFFFF", "000000", FALSE, 10, "Franklin Gothic Book");
				$proveedor[$key]->estatus->setCellValue("BG{$flag}", "=SUM(BG{$flageas}:BG".($flag-1).")")->getStyle("BG{$flag}")->getNumberFormat()->setFormatCode('"$"#,##0.00_-');
				$this->cellStyle("BH".$flag, "FFFFFF", "000000", FALSE, 10, "Franklin Gothic Book");
				$proveedor[$key]->estatus->setCellValue("BH{$flag}", "=SUM(BH{$flageas}:BH".($flag-1).")")->getStyle("BH{$flag}")->getNumberFormat()->setFormatCode('"$"#,##0.00_-');
				$this->cellStyle("BI".$flag, "FFFFFF", "000000", FALSE, 10, "Franklin Gothic Book");
				$proveedor[$key]->estatus->setCellValue("BI{$flag}", "=SUM(BI{$flageas}:BI".($flag-1).")")->getStyle("BI{$flag}")->getNumberFormat()->setFormatCode('"$"#,##0.00_-');
				$this->cellStyle("BJ".$flag, "FFFFFF", "000000", FALSE, 10, "Franklin Gothic Book");
				$proveedor[$key]->estatus->setCellValue("BJ{$flag}", "=SUM(BJ{$flageas}:BJ".($flag-1).")")->getStyle("BJ{$flag}")->getNumberFormat()->setFormatCode('"$"#,##0.00_-');
				$this->cellStyle("BK".$flag, "FFFFFF", "000000", FALSE, 10, "Franklin Gothic Book");
				$proveedor[$key]->estatus->setCellValue("BK{$flag}", "=SUM(BK{$flageas}:BK".($flag-1).")")->getStyle("BK{$flag}")->getNumberFormat()->setFormatCode('"$"#,##0.00_-');
				$this->cellStyle("BL".$flag, "FFFFFF", "000000", FALSE, 10, "Franklin Gothic Book");
				$proveedor[$key]->estatus->setCellValue("BL{$flag}", "=SUM(BL{$flageas}:BL".($flag-1).")")->getStyle("BL{$flag}")->getNumberFormat()->setFormatCode('"$"#,##0.00_-');
				$totis = $flag;
				$flag+=5;


				$this->excelfile->getActiveSheet()->getStyle('C'.$flag.':D'.$flag)->applyFromArray($styleArray);
				$va->alias = "PEDIDO LUNES";
				$this->cellStyle("C".$flag, "C00000", "000000", TRUE, 12, "Franklin Gothic Book");
				$proveedor[$key]->estatus->setCellValue("C".$flag, "CEDIS/SUPER");
				$proveedor[$key]->estatus->setCellValue("D{$flag}", "=BB{$totis}")->getStyle("D{$flag}")->getNumberFormat()->setFormatCode('"$"#,##0.00_-');
				$ced=$ced."".$va->alias."!D{$flag}+";
				$flag++;
				$this->excelfile->getActiveSheet()->getStyle('C'.$flag.':D'.$flag)->applyFromArray($styleArray);
				$proveedor[$key]->estatus->setCellValue("C".$flag, "CD INDUSTRIAL");
				$this->cellStyle("C".$flag, "FF0066", "000000", TRUE, 12, "Franklin Gothic Book");
				$proveedor[$key]->estatus->setCellValue("D{$flag}", "=BC{$totis}")->getStyle("D{$flag}")->getNumberFormat()->setFormatCode('"$"#,##0.00_-');
				$sup=$sup."".$va->alias."!D{$flag}+";
				$flag++;
				$this->excelfile->getActiveSheet()->getStyle('C'.$flag.':D'.$flag)->applyFromArray($styleArray);
				$this->cellStyle("C".$flag, "01B0F0", "000000", TRUE, 12, "Franklin Gothic Book");
				$proveedor[$key]->estatus->setCellValue("C".$flag, "ABARROTES");
				$proveedor[$key]->estatus->setCellValue("D{$flag}", "=BE{$totis}")->getStyle("D{$flag}")->getNumberFormat()->setFormatCode('"$"#,##0.00_-');
				$aba=$aba."".$va->alias."!D{$flag}+";
				$flag++;
				$this->excelfile->getActiveSheet()->getStyle('C'.$flag.':D'.$flag)->applyFromArray($styleArray);
				$this->cellStyle("C".$flag, "FF0000", "000000", TRUE, 12, "Franklin Gothic Book");
				$proveedor[$key]->estatus->setCellValue("C".$flag, "PEDREGAL");
				$proveedor[$key]->estatus->setCellValue("D{$flag}", "=BF{$totis}")->getStyle("D{$flag}")->getNumberFormat()->setFormatCode('"$"#,##0.00_-');
				$ped=$ped."".$va->alias."!D{$flag}+";
				$flag++;
				$this->excelfile->getActiveSheet()->getStyle('C'.$flag.':D'.$flag)->applyFromArray($styleArray);
				$this->cellStyle("C".$flag, "E26C0B", "000000", TRUE, 12, "Franklin Gothic Book");
				$proveedor[$key]->estatus->setCellValue("C".$flag, "TIENDA");
				$proveedor[$key]->estatus->setCellValue("D{$flag}", "=BG{$totis}")->getStyle("D{$flag}")->getNumberFormat()->setFormatCode('"$"#,##0.00_-');
				$tie=$tie."".$va->alias."!D{$flag}+";
				$flag++;
				$this->excelfile->getActiveSheet()->getStyle('C'.$flag.':D'.$flag)->applyFromArray($styleArray);
				$this->cellStyle("C".$flag, "C5C5C5", "000000", TRUE, 12, "Franklin Gothic Book");
				$proveedor[$key]->estatus->setCellValue("C".$flag, "ULTRAMARINOS");
				$proveedor[$key]->estatus->setCellValue("D{$flag}", "=BH{$totis}")->getStyle("D{$flag}")->getNumberFormat()->setFormatCode('"$"#,##0.00_-');
				$ult=$ult."".$va->alias."!D{$flag}+";
				$flag++;
				$this->excelfile->getActiveSheet()->getStyle('C'.$flag.':D'.$flag)->applyFromArray($styleArray);
				$this->cellStyle("C".$flag, "92D051", "000000", TRUE, 12, "Franklin Gothic Book");
				$proveedor[$key]->estatus->setCellValue("C".$flag, "TRINCHERAS");
				$proveedor[$key]->estatus->setCellValue("D{$flag}", "=BI{$totis}")->getStyle("D{$flag}")->getNumberFormat()->setFormatCode('"$"#,##0.00_-');
				$tri=$tri."".$va->alias."!D{$flag}+";
				$flag++;
				$this->excelfile->getActiveSheet()->getStyle('C'.$flag.':D'.$flag)->applyFromArray($styleArray);
				$this->cellStyle("C".$flag, "B1A0C7", "000000", TRUE, 12, "Franklin Gothic Book");
				$proveedor[$key]->estatus->setCellValue("C".$flag, "AZT MERCADO");
				$proveedor[$key]->estatus->setCellValue("D{$flag}", "=BJ{$totis}")->getStyle("D{$flag}")->getNumberFormat()->setFormatCode('"$"#,##0.00_-');
				$mer=$mer."".$va->alias."!D{$flag}+";
				$flag++;
				$this->excelfile->getActiveSheet()->getStyle('C'.$flag.':D'.$flag)->applyFromArray($styleArray);
				$this->cellStyle("C".$flag, "DA9694", "000000", TRUE, 12, "Franklin Gothic Book");
				$proveedor[$key]->estatus->setCellValue("C".$flag, "TENENCIA");
				$proveedor[$key]->estatus->setCellValue("D{$flag}", "=BK{$totis}")->getStyle("D{$flag}")->getNumberFormat()->setFormatCode('"$"#,##0.00_-');
				$ten=$ten."".$va->alias."!D{$flag}+";
				$flag++;
				$this->excelfile->getActiveSheet()->getStyle('C'.$flag.':D'.$flag)->applyFromArray($styleArray);
				$this->cellStyle("C".$flag, "4CACC6", "000000", TRUE, 12, "Franklin Gothic Book");
				$proveedor[$key]->estatus->setCellValue("C".$flag, "TIJERAS");
				$proveedor[$key]->estatus->setCellValue("D{$flag}", "=BL{$totis}")->getStyle("D{$flag}")->getNumberFormat()->setFormatCode('"$"#,##0.00_-');
				$tij=$tij."".$va->alias."!D{$flag}+";
				$flag++;
				$this->excelfile->getActiveSheet()->getStyle('C'.$flag.':D'.$flag)->applyFromArray($styleArray);
				$this->cellStyle("C".$flag, "000000", "FFFFFF", TRUE, 12, "Franklin Gothic Book");
				$proveedor[$key]->estatus->setCellValue("C".$flag, "TOTAL");
				$proveedor[$key]->estatus->setCellValue("D{$flag}", "=SUM(D".($totis+5).":D".($flag-1).")")->getStyle("D{$flag}")->getNumberFormat()->setFormatCode('"$"#,##0.00_-');
			$flag1 += 5;

			$flag += 5;
			}
		}

		$this->excelfile->getActiveSheet()->getStyle('B'.$flag.':C'.$flag)->applyFromArray($styleArray);
		$this->cellStyle("B".$flag, "C00000", "000000", TRUE, 12, "Franklin Gothic Book");
		$proveedor[$key]->estatus->setCellValue("B".$flag, "CEDIS/SUPER");
		$proveedor[$key]->estatus->setCellValue("C{$flag}", substr($ced, 0, -1))->getStyle("C{$flag}")->getNumberFormat()->setFormatCode('"$"#,##0.00_-');
		
		$flag++;
		$this->excelfile->getActiveSheet()->getStyle('B'.$flag.':C'.$flag)->applyFromArray($styleArray);
		$proveedor[$key]->estatus->setCellValue("B".$flag, "CD INDUSTRIAL");
		$this->cellStyle("B".$flag, "FF0066", "000000", TRUE, 12, "Franklin Gothic Book");
		$sup = substr_replace($sup ,"", -1);
		$proveedor[$key]->estatus->setCellValue("C{$flag}", substr($sup, 0, -1))->getStyle("C{$flag}")->getNumberFormat()->setFormatCode('"$"#,##0.00_-');
		
		$flag++;
		$this->excelfile->getActiveSheet()->getStyle('B'.$flag.':C'.$flag)->applyFromArray($styleArray);
		$this->cellStyle("B".$flag, "01B0F0", "000000", TRUE, 12, "Franklin Gothic Book");
		$proveedor[$key]->estatus->setCellValue("B".$flag, "ABARROTES");
		$proveedor[$key]->estatus->setCellValue("C{$flag}", substr($aba, 0, -1))->getStyle("C{$flag}")->getNumberFormat()->setFormatCode('"$"#,##0.00_-');
		
		$flag++;
		$this->excelfile->getActiveSheet()->getStyle('B'.$flag.':C'.$flag)->applyFromArray($styleArray);
		$this->cellStyle("B".$flag, "FF0000", "000000", TRUE, 12, "Franklin Gothic Book");
		$proveedor[$key]->estatus->setCellValue("B".$flag, "PEDREGAL");
		$proveedor[$key]->estatus->setCellValue("C{$flag}", substr($ped, 0, -1))->getStyle("C{$flag}")->getNumberFormat()->setFormatCode('"$"#,##0.00_-');
		
		$flag++;
		$this->excelfile->getActiveSheet()->getStyle('B'.$flag.':C'.$flag)->applyFromArray($styleArray);
		$this->cellStyle("B".$flag, "E26C0B", "000000", TRUE, 12, "Franklin Gothic Book");
		$proveedor[$key]->estatus->setCellValue("B".$flag, "TIENDA");
		$proveedor[$key]->estatus->setCellValue("C{$flag}", substr($tie, 0, -1))->getStyle("C{$flag}")->getNumberFormat()->setFormatCode('"$"#,##0.00_-');
		
		$flag++;
		$this->excelfile->getActiveSheet()->getStyle('B'.$flag.':C'.$flag)->applyFromArray($styleArray);
		$this->cellStyle("B".$flag, "C5C5C5", "000000", TRUE, 12, "Franklin Gothic Book");
		$proveedor[$key]->estatus->setCellValue("B".$flag, "ULTRAMARINOS");
		$proveedor[$key]->estatus->setCellValue("C{$flag}", substr($ult, 0, -1))->getStyle("C{$flag}")->getNumberFormat()->setFormatCode('"$"#,##0.00_-');
		
		$flag++;
		$this->excelfile->getActiveSheet()->getStyle('B'.$flag.':C'.$flag)->applyFromArray($styleArray);
		$this->cellStyle("B".$flag, "92D051", "000000", TRUE, 12, "Franklin Gothic Book");
		$proveedor[$key]->estatus->setCellValue("B".$flag, "TRINCHERAS");
		$proveedor[$key]->estatus->setCellValue("C{$flag}", substr($tri, 0, -1))->getStyle("C{$flag}")->getNumberFormat()->setFormatCode('"$"#,##0.00_-');
		
		$flag++;
		$this->excelfile->getActiveSheet()->getStyle('B'.$flag.':C'.$flag)->applyFromArray($styleArray);
		$this->cellStyle("B".$flag, "B1A0C7", "000000", TRUE, 12, "Franklin Gothic Book");
		$proveedor[$key]->estatus->setCellValue("B".$flag, "AZT MERCADO");
		$proveedor[$key]->estatus->setCellValue("C{$flag}", substr($mer, 0, -1))->getStyle("C{$flag}")->getNumberFormat()->setFormatCode('"$"#,##0.00_-');
		
		$flag++;
		$this->excelfile->getActiveSheet()->getStyle('B'.$flag.':C'.$flag)->applyFromArray($styleArray);
		$this->cellStyle("B".$flag, "DA9694", "000000", TRUE, 12, "Franklin Gothic Book");
		$proveedor[$key]->estatus->setCellValue("B".$flag, "TENENCIA");
		$proveedor[$key]->estatus->setCellValue("C{$flag}", substr($ten, 0, -1))->getStyle("C{$flag}")->getNumberFormat()->setFormatCode('"$"#,##0.00_-');
		
		$flag++;
		$this->excelfile->getActiveSheet()->getStyle('B'.$flag.':C'.$flag)->applyFromArray($styleArray);
		$this->cellStyle("B".$flag, "4CACC6", "000000", TRUE, 12, "Franklin Gothic Book");
		$proveedor[$key]->estatus->setCellValue("B".$flag, "TIJERAS");
		$proveedor[$key]->estatus->setCellValue("C{$flag}", substr($tij, 0, -1))->getStyle("C{$flag}")->getNumberFormat()->setFormatCode('"$"#,##0.00_-');
		
		$flag++;
		$this->excelfile->getActiveSheet()->getStyle('B'.$flag.':C'.$flag)->applyFromArray($styleArray);
		$this->cellStyle("B".$flag, "000000", "FFFFFF", TRUE, 12, "Franklin Gothic Book");
		$proveedor[$key]->estatus->setCellValue("B".$flag, "TOTAL");
		$proveedor[$key]->estatus->setCellValue("C{$flag}", "=SUM(C2:C11)")->getStyle("C{$flag}")->getNumberFormat()->setFormatCode('"$"#,##0.00_-');

		

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
