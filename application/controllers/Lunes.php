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
		$this->load->model("Promo_model", "promo_mdl");
		$this->load->model("Catalogos_model", "cata_mdl");
		$this->load->model("Finalunes_model", "fin_mdl");
		$this->load->model("Facturalunes_model", "fac_mdl");
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
		$data["productos"] = $this->prolu_md->get(NULL,["estatus <>"=>0]);
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
		$promos =  [
			"codigo"	=>	$this->input->post('codigo'),
			"promo"		=>	$this->input->post('promo'),
			"descuento"	=>	0,
			"prod"		=>	0,
			"cuantos1"	=>	0,
			"cuantos2"	=>	0,
			"mins"		=>	0,
			"ieps"		=>	0,
			"estatus"	=>	0
		];
		$cata =  [
			"id_catalogo"	=>	$this->input->post('id_catalogo'),
			"id_producto"	=>	$this->input->post('codigo'),
			"descripcion"	=>	$this->input->post('descripcion'),
			"estatus"		=>	1
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
			$categos = $this->cata_mdl->get(NULL,["id_catalogo"=>$this->input->post("id_catalogo")])[0];
			if ($categos) {
				$mensaje = ["id" 	=> 'Error',
						"desc"	=> 'Código de PROVEEDOR ya esta registrado',
						"type"	=> 'error'];
			}else{
				$data['catego'] = $this->cata_mdl->insert($cata);

				if($this->input->post('promo') === "1" || $this->input->post('promo') === 1){//# EN #
						$promos["cuantos1"] = $this->input->post('cuantos1');
						$promos["cuantos2"] = $this->input->post('cuantos2');
						$promos["mins"] = $this->input->post('mins');
						$promos["ieps"] = $this->input->post('ieps');
						$promos["estatus"] = 1;
					}elseif($this->input->post( 'promo') === "2" || $this->input->post('promo') === 2){//PORCENTAJE DE DESCUENTO
						$promos["descuento"] = $this->input->post('descuento');
						$promos["mins"] = $this->input->post('mins2');
						$promos["ieps"] = $this->input->post('ieps2');
						$promos["estatus"] = 1;
					}elseif($this->input->post('promo') === "3" || $this->input->post('promo') === 3){//PRODUCTO ADICIONAL
						$promos["cuantos1"] = $this->input->post('cuanto1');
						$promos["cuantos2"] = $this->input->post('cuanto2');
						$promos["prod"] = $this->input->post('prod');
						$promos["mins"] = $this->input->post('mins3');
						$promos["ieps"] = $this->input->post('ieps3');
						$promos["estatus"] = 1;
					}

					$data['promo'] = $this->promo_mdl->insert($promos);
			}
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
		$data["productos"] = $this->prolu_md->get(NULL,["estatus <>"=>0]);
		$data["promo"] = $this->promo_mdl->get(NULL,["codigo"=>$data["producto"]->codigo])[0];
		$data["cata"] = $this->cata_mdl->get(NULL,["id_producto"=>$data["producto"]->codigo])[0];
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
		$lastpromo = $this->promo_mdl->get(NULL,["codigo"=>$this->input->post('codigo')]);
		$producto = [
			"codigo"	=>	strtoupper($this->input->post('codigo')),
			"descripcion"	=>	strtoupper($this->input->post('descripcion')),
			"precio"	=>	$this->input->post('precio'),
			"sistema"	=>	$this->input->post('sistema'),
			"observaciones"	=>	$this->input->post('observaciones'),
			"id_proveedor"	=>	$this->input->post('id_proveedor'),
			"unidad"	=>	$this->input->post('unidad'),
		];
		$promos =  [
			"codigo"	=>	$this->input->post('codigo'),
			"promo"		=>	$this->input->post('promo'),
			"descuento"	=>	0,
			"prod"		=>	0,
			"cuantos1"	=>	0,
			"cuantos2"	=>	0,
			"mins"		=>	0,
			"ieps"		=>	0,
			"estatus"	=>	0
		];
		$cata =  [
			"id_catalogo"	=>	$this->input->post('id_catalogo'),
			"id_producto"	=>	$this->input->post('codigo'),
			"descripcion"	=>	$this->input->post('descripcion'),
			"estatus"		=>	1
		];
		$dude = null;


		if ($this->input->post("codigo") <> $this->input->post("codigo2")){
			$dude = $this->prolu_md->get(NULL,["codigo"=>$this->input->post("codigo")])[0];
		}
		
		if($dude){
			$mensaje = ["id" 	=> 'Error',
						"desc"	=> 'Código de producto ya esta registrado',
						"type"	=> 'error'];
		}else{
			if ($this->input->post("id_catalogo") <> $this->input->post("id_catalogo2")){
				$categos = $this->cata_mdl->get(NULL,["id_catalogo"=>$this->input->post("id_catalogo")])[0];
			}else{
				$categos = null;
			}

			if ($categos) {
				$mensaje = ["id" 	=> 'Error',
						"desc"	=> 'Código de PROVEEDOR ya esta registrado',
						"type"	=> 'error'];
			}else{
				$categos = $this->cata_mdl->get(NULL,["id_producto"=>$this->input->post("codigo")])[0];
				
				if ($this->input->post('id_catalogo') <> "" && $this->input->post('id_catalogo') <> NULL) {
					if ($categos) {
						$data['catego'] = $this->cata_mdl->update($cata, $this->input->post('id_catalogo2'));
					}else{
						$data['catego'] = $this->cata_mdl->insert($cata);
					}
				}

				if($this->input->post('promo') === "1" || $this->input->post('promo') === 1){//# EN #
					$promos["cuantos1"] = $this->input->post('cuantos1');
					$promos["cuantos2"] = $this->input->post('cuantos2');
					$promos["mins"] = $this->input->post('mins');
					$promos["ieps"] = $this->input->post('ieps');
					$promos["estatus"] = 1;
				}elseif($this->input->post( 'promo') === "2" || $this->input->post('promo') === 2){//PORCENTAJE DE DESCUENTO
					$promos["descuento"] = $this->input->post('descuento');
					$promos["mins"] = $this->input->post('mins2');
					$promos["ieps"] = $this->input->post('ieps2');
					$promos["estatus"] = 1;
				}elseif($this->input->post('promo') === "3" || $this->input->post('promo') === 3){//PRODUCTO ADICIONAL
					$promos["cuantos1"] = $this->input->post('cuanto1');
					$promos["cuantos2"] = $this->input->post('cuanto2');
					$promos["prod"] = $this->input->post('prod');
					$promos["ieps"] = $this->input->post('ieps3');
					$promos["mins"] = $this->input->post('mins3');
					$promos["estatus"] = 1;
				}

				if($lastpromo){
					$data['promo'] = $this->promo_mdl->update($promos, $this->input->post('codigo'));
				}else{
					$data['promo'] = $this->promo_mdl->insert($promos);
				}

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
			}
		}
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
					$caja = $this->getOldVal($sheet,$i,'A');
					$pieza =$this->getOldVal($sheet,$i,'B');
					$ped = $this->getOldVal($sheet,$i,'C');
					$codigo = htmlspecialchars($this->getOldVal($sheet,$i,'D'), ENT_QUOTES, 'UTF-8');
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
		$config['upload_path']          = './assets/uploads/lunes/';
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
		for ($i=2; $i<=$num_rows; $i++) {
			if(strlen($sheet->getCell('A'.$i)->getValue()) > 0){
				$productos = $this->prolu_md->get("codigo",['codigo'=> htmlspecialchars($sheet->getCell('A'.$i)->getValue(), ENT_QUOTES, 'UTF-8')])[0];
				if (sizeof($productos) > 0) {
					$sistema = $sheet->getCell('C'.$i)->getValue();
					$observaciones = $sheet->getCell('D'.$i)->getValue();
					$new_existencia=[
							"precio" =>	$sistema,
							"observaciones" =>	$observaciones,
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
						"despues"			=>	"assets/uploads/lunes/Precios Provs.xlsx",
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
					$anterior->setCellValue("C{$flag1}", $v["precio"])->getStyle("C{$flag1}")->getNumberFormat()->setFormatCode("_(\"$\"* #,##0.00_);_(\"$\"* \(#,##0.00\);_(\"$\"* \"-\"??_);_(@_)");
					$this->cellStyle("C".$flag1, "FFFFFF", "000000", FALSE, 10, "Franklin Gothic Book");
					$anterior->setCellValue("D{$flag1}", $v["sistema"])->getStyle("D{$flag1}")->getNumberFormat()->setFormatCode("_(\"$\"* #,##0.00_);_(\"$\"* \(#,##0.00\);_(\"$\"* \"-\"??_);_(@_)");
					$this->cellStyle("D".$flag1, "FFFFFF", "000000", FALSE, 10, "Franklin Gothic Book");
					$anterior->setCellValue("E{$flag1}", $v["unidad"])->getStyle("E{$flag1}");
					$this->cellStyle("E".$flag1, "FFFFFF", "000000", FALSE, 10, "Franklin Gothic Book");
					$this->excelfile->getActiveSheet()->getStyle('AP'.$flag1.':AZ'.$flag1)->applyFromArray($styleArray);
					$this->excelfile->getActiveSheet()->getStyle('BC'.$flag1.':BL'.$flag1)->applyFromArray($styleArray);
					$this->cellStyle("F{$flag1}:AN{$flag1}", "FFFFFF", "000000", TRUE, 10, "Franklin Gothic Book");
					$this->cellStyle("AP".$flag1, "FFFFFF", "000000", FALSE, 10, "Franklin Gothic Book");
					$anterior->setCellValue("AP{$flag1}", "=C{$flag1}*H{$flag1}")->getStyle("AP{$flag1}")->getNumberFormat()->setFormatCode("_(\"$\"* #,##0.00_);_(\"$\"* \(#,##0.00\);_(\"$\"* \"-\"??_);_(@_)");
					$this->cellStyle("AQ".$flag1, "FFFFFF", "000000", FALSE, 10, "Franklin Gothic Book");
					$anterior->setCellValue("AQ{$flag1}", "=C{$flag1}*N{$flag1}")->getStyle("AQ{$flag1}")->getNumberFormat()->setFormatCode("_(\"$\"* #,##0.00_);_(\"$\"* \(#,##0.00\);_(\"$\"* \"-\"??_);_(@_)");
					$this->cellStyle("AR".$flag1, "FFFFFF", "000000", FALSE, 10, "Franklin Gothic Book");
					$anterior->setCellValue("AR{$flag1}", "=C{$flag1}*Q{$flag1}")->getStyle("AR{$flag1}")->getNumberFormat()->setFormatCode("_(\"$\"* #,##0.00_);_(\"$\"* \(#,##0.00\);_(\"$\"* \"-\"??_);_(@_)");
					$this->cellStyle("AS".$flag1, "FFFFFF", "000000", FALSE, 10, "Franklin Gothic Book");
					$anterior->setCellValue("AS{$flag1}", "=C{$flag1}*T{$flag1}")->getStyle("AS{$flag1}")->getNumberFormat()->setFormatCode("_(\"$\"* #,##0.00_);_(\"$\"* \(#,##0.00\);_(\"$\"* \"-\"??_);_(@_)");
					$this->cellStyle("AT".$flag1, "FFFFFF", "000000", FALSE, 10, "Franklin Gothic Book");
					$anterior->setCellValue("AT{$flag1}", "=C{$flag1}*W{$flag1}")->getStyle("AT{$flag1}")->getNumberFormat()->setFormatCode("_(\"$\"* #,##0.00_);_(\"$\"* \(#,##0.00\);_(\"$\"* \"-\"??_);_(@_)");
					$this->cellStyle("AU".$flag1, "FFFFFF", "000000", FALSE, 10, "Franklin Gothic Book");
					$anterior->setCellValue("AU{$flag1}", "=C{$flag1}*Z{$flag1}")->getStyle("AU{$flag1}")->getNumberFormat()->setFormatCode("_(\"$\"* #,##0.00_);_(\"$\"* \(#,##0.00\);_(\"$\"* \"-\"??_);_(@_)");
					$this->cellStyle("AV".$flag1, "FFFFFF", "000000", FALSE, 10, "Franklin Gothic Book");
					$anterior->setCellValue("AV{$flag1}", "=C{$flag1}*AC{$flag1}")->getStyle("AV{$flag1}")->getNumberFormat()->setFormatCode("_(\"$\"* #,##0.00_);_(\"$\"* \(#,##0.00\);_(\"$\"* \"-\"??_);_(@_)");
					$this->cellStyle("AW".$flag1, "FFFFFF", "000000", FALSE, 10, "Franklin Gothic Book");
					$anterior->setCellValue("AW{$flag1}", "=C{$flag1}*AF{$flag1}")->getStyle("AW{$flag1}")->getNumberFormat()->setFormatCode("_(\"$\"* #,##0.00_);_(\"$\"* \(#,##0.00\);_(\"$\"* \"-\"??_);_(@_)");
					$this->cellStyle("AX".$flag1, "FFFFFF", "000000", FALSE, 10, "Franklin Gothic Book");
					$anterior->setCellValue("AX{$flag1}", "=C{$flag1}*AI{$flag1}")->getStyle("AX{$flag1}")->getNumberFormat()->setFormatCode("_(\"$\"* #,##0.00_);_(\"$\"* \(#,##0.00\);_(\"$\"* \"-\"??_);_(@_)");
					$this->cellStyle("AY".$flag1, "FFFFFF", "000000", FALSE, 10, "Franklin Gothic Book");
					$anterior->setCellValue("AY{$flag1}", "=C{$flag1}*AL{$flag1}")->getStyle("AY{$flag1}")->getNumberFormat()->setFormatCode("_(\"$\"* #,##0.00_);_(\"$\"* \(#,##0.00\);_(\"$\"* \"-\"??_);_(@_)");
					$this->cellStyle("AZ".$flag1, "FFFFFF", "000000", FALSE, 10, "Franklin Gothic Book");
					$anterior->setCellValue("AZ{$flag1}", "=SUM(AP{$flag1}:AY{$flag1})")->getStyle("AZ{$flag1}")->getNumberFormat()->setFormatCode("_(\"$\"* #,##0.00_);_(\"$\"* \(#,##0.00\);_(\"$\"* \"-\"??_);_(@_)");


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
					$proveedor[$key]->estatus->setCellValue("D{$flag}", $v["precio"])->getStyle("D{$flag}")->getNumberFormat()->setFormatCode("_(\"$\"* #,##0.00_);_(\"$\"* \(#,##0.00\);_(\"$\"* \"-\"??_);_(@_)");
					$this->cellStyle("D".$flag, "FFFFFF", "000000", FALSE, 10, "Franklin Gothic Book");
					$proveedor[$key]->estatus->setCellValue("E{$flag}", $v["sistema"])->getStyle("E{$flag}")->getNumberFormat()->setFormatCode("_(\"$\"* #,##0.00_);_(\"$\"* \(#,##0.00\);_(\"$\"* \"-\"??_);_(@_)");
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
					$proveedor[$key]->estatus->setCellValue("BX{$flag}", "=D{$flag}*L{$flag}")->getStyle("BX{$flag}")->getNumberFormat()->setFormatCode("_(\"$\"* #,##0.00_);_(\"$\"* \(#,##0.00\);_(\"$\"* \"-\"??_);_(@_)");
					$this->cellStyle("BY".$flag, "FFFFFF", "000000", FALSE, 10, "Franklin Gothic Book");
					$proveedor[$key]->estatus->setCellValue("BY{$flag}", "=D{$flag}*X{$flag}")->getStyle("BY{$flag}")->getNumberFormat()->setFormatCode("_(\"$\"* #,##0.00_);_(\"$\"* \(#,##0.00\);_(\"$\"* \"-\"??_);_(@_)");
					$this->cellStyle("BZ".$flag, "FFFFFF", "000000", FALSE, 10, "Franklin Gothic Book");
					$proveedor[$key]->estatus->setCellValue("BZ{$flag}", "=D{$flag}*AD{$flag}")->getStyle("BZ{$flag}")->getNumberFormat()->setFormatCode("_(\"$\"* #,##0.00_);_(\"$\"* \(#,##0.00\);_(\"$\"* \"-\"??_);_(@_)");
					$this->cellStyle("BZ".$flag, "FFFFFF", "000000", FALSE, 10, "Franklin Gothic Book");
					$proveedor[$key]->estatus->setCellValue("CA{$flag}", "=D{$flag}*AJ{$flag}")->getStyle("CA{$flag}")->getNumberFormat()->setFormatCode("_(\"$\"* #,##0.00_);_(\"$\"* \(#,##0.00\);_(\"$\"* \"-\"??_);_(@_)");
					$this->cellStyle("CB".$flag, "FFFFFF", "000000", FALSE, 10, "Franklin Gothic Book");
					$proveedor[$key]->estatus->setCellValue("CB{$flag}", "=D{$flag}*AP{$flag}")->getStyle("CB{$flag}")->getNumberFormat()->setFormatCode("_(\"$\"* #,##0.00_);_(\"$\"* \(#,##0.00\);_(\"$\"* \"-\"??_);_(@_)");
					$this->cellStyle("CC".$flag, "FFFFFF", "000000", FALSE, 10, "Franklin Gothic Book");
					$proveedor[$key]->estatus->setCellValue("CC{$flag}", "=D{$flag}*AV{$flag}")->getStyle("CC{$flag}")->getNumberFormat()->setFormatCode("_(\"$\"* #,##0.00_);_(\"$\"* \(#,##0.00\);_(\"$\"* \"-\"??_);_(@_)");
					$this->cellStyle("CD".$flag, "FFFFFF", "000000", FALSE, 10, "Franklin Gothic Book");
					$proveedor[$key]->estatus->setCellValue("CD{$flag}", "=D{$flag}*BB{$flag}")->getStyle("CD{$flag}")->getNumberFormat()->setFormatCode("_(\"$\"* #,##0.00_);_(\"$\"* \(#,##0.00\);_(\"$\"* \"-\"??_);_(@_)");
					$this->cellStyle("CE".$flag, "FFFFFF", "000000", FALSE, 10, "Franklin Gothic Book");
					$proveedor[$key]->estatus->setCellValue("CE{$flag}", "=D{$flag}*BH{$flag}")->getStyle("CE{$flag}")->getNumberFormat()->setFormatCode("_(\"$\"* #,##0.00_);_(\"$\"* \(#,##0.00\);_(\"$\"* \"-\"??_);_(@_)");
					$this->cellStyle("CF".$flag, "FFFFFF", "000000", FALSE, 10, "Franklin Gothic Book");
					$proveedor[$key]->estatus->setCellValue("CF{$flag}", "=D{$flag}*BN{$flag}")->getStyle("CF{$flag}")->getNumberFormat()->setFormatCode("_(\"$\"* #,##0.00_);_(\"$\"* \(#,##0.00\);_(\"$\"* \"-\"??_);_(@_)");
					$this->cellStyle("CG".$flag, "FFFFFF", "000000", FALSE, 10, "Franklin Gothic Book");
					$proveedor[$key]->estatus->setCellValue("CG{$flag}", "=D{$flag}*BT{$flag}")->getStyle("CG{$flag}")->getNumberFormat()->setFormatCode("_(\"$\"* #,##0.00_);_(\"$\"* \(#,##0.00\);_(\"$\"* \"-\"??_);_(@_)");
					$this->cellStyle("CH".$flag, "FFFFFF", "000000", FALSE, 10, "Franklin Gothic Book");
					$proveedor[$key]->estatus->setCellValue("CH{$flag}", "=SUM(BX{$flag}:CG{$flag})")->getStyle("CH{$flag}")->getNumberFormat()->setFormatCode("_(\"$\"* #,##0.00_);_(\"$\"* \(#,##0.00\);_(\"$\"* \"-\"??_);_(@_)");

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
				$proveedor[$key]->estatus->setCellValue("BX{$flag}", "=SUM(BW{$flageas}:BX".($flag-1).")")->getStyle("BX{$flag}")->getNumberFormat()->setFormatCode("_(\"$\"* #,##0.00_);_(\"$\"* \(#,##0.00\);_(\"$\"* \"-\"??_);_(@_)");
				$this->cellStyle("BY".$flag, "FFFFFF", "000000", FALSE, 10, "Franklin Gothic Book");
				$proveedor[$key]->estatus->setCellValue("BY{$flag}", "=SUM(BY{$flageas}:BY".($flag-1).")")->getStyle("BY{$flag}")->getNumberFormat()->setFormatCode("_(\"$\"* #,##0.00_);_(\"$\"* \(#,##0.00\);_(\"$\"* \"-\"??_);_(@_)");
				$this->cellStyle("BZ".$flag, "FFFFFF", "000000", FALSE, 10, "Franklin Gothic Book");
				$proveedor[$key]->estatus->setCellValue("BZ{$flag}", "=SUM(BZ{$flageas}:BZ".($flag-1).")")->getStyle("BZ{$flag}")->getNumberFormat()->setFormatCode("_(\"$\"* #,##0.00_);_(\"$\"* \(#,##0.00\);_(\"$\"* \"-\"??_);_(@_)");
				$this->cellStyle("CA".$flag, "FFFFFF", "000000", FALSE, 10, "Franklin Gothic Book");
				$proveedor[$key]->estatus->setCellValue("CA{$flag}", "=SUM(CA{$flageas}:CA".($flag-1).")")->getStyle("CA{$flag}")->getNumberFormat()->setFormatCode("_(\"$\"* #,##0.00_);_(\"$\"* \(#,##0.00\);_(\"$\"* \"-\"??_);_(@_)");
				$this->cellStyle("CB".$flag, "FFFFFF", "000000", FALSE, 10, "Franklin Gothic Book");
				$proveedor[$key]->estatus->setCellValue("CB{$flag}", "=SUM(CB{$flageas}:CB".($flag-1).")")->getStyle("CB{$flag}")->getNumberFormat()->setFormatCode("_(\"$\"* #,##0.00_);_(\"$\"* \(#,##0.00\);_(\"$\"* \"-\"??_);_(@_)");
				$this->cellStyle("CC".$flag, "FFFFFF", "000000", FALSE, 10, "Franklin Gothic Book");
				$proveedor[$key]->estatus->setCellValue("CC{$flag}", "=SUM(CC{$flageas}:CC".($flag-1).")")->getStyle("CC{$flag}")->getNumberFormat()->setFormatCode("_(\"$\"* #,##0.00_);_(\"$\"* \(#,##0.00\);_(\"$\"* \"-\"??_);_(@_)");
				$this->cellStyle("CD".$flag, "FFFFFF", "000000", FALSE, 10, "Franklin Gothic Book");
				$proveedor[$key]->estatus->setCellValue("CD{$flag}", "=SUM(CD{$flageas}:CD".($flag-1).")")->getStyle("CD{$flag}")->getNumberFormat()->setFormatCode("_(\"$\"* #,##0.00_);_(\"$\"* \(#,##0.00\);_(\"$\"* \"-\"??_);_(@_)");
				$this->cellStyle("CE".$flag, "FFFFFF", "000000", FALSE, 10, "Franklin Gothic Book");
				$proveedor[$key]->estatus->setCellValue("CE{$flag}", "=SUM(CE{$flageas}:CE".($flag-1).")")->getStyle("CE{$flag}")->getNumberFormat()->setFormatCode("_(\"$\"* #,##0.00_);_(\"$\"* \(#,##0.00\);_(\"$\"* \"-\"??_);_(@_)");
				$this->cellStyle("CF".$flag, "FFFFFF", "000000", FALSE, 10, "Franklin Gothic Book");
				$proveedor[$key]->estatus->setCellValue("CF{$flag}", "=SUM(CF{$flageas}:CF".($flag-1).")")->getStyle("CF{$flag}")->getNumberFormat()->setFormatCode("_(\"$\"* #,##0.00_);_(\"$\"* \(#,##0.00\);_(\"$\"* \"-\"??_);_(@_)");
				$this->cellStyle("CG".$flag, "FFFFFF", "000000", FALSE, 10, "Franklin Gothic Book");
				$proveedor[$key]->estatus->setCellValue("CG{$flag}", "=SUM(CG{$flageas}:CG".($flag-1).")")->getStyle("CG{$flag}")->getNumberFormat()->setFormatCode("_(\"$\"* #,##0.00_);_(\"$\"* \(#,##0.00\);_(\"$\"* \"-\"??_);_(@_)");
				$this->cellStyle("CH".$flag, "FFFFFF", "000000", FALSE, 10, "Franklin Gothic Book");
				$proveedor[$key]->estatus->setCellValue("CH{$flag}", "=SUM(CH{$flageas}:CH".($flag-1).")")->getStyle("CH{$flag}")->getNumberFormat()->setFormatCode("_(\"$\"* #,##0.00_);_(\"$\"* \(#,##0.00\);_(\"$\"* \"-\"??_);_(@_)");
				$totis = $flag;
				$flag+=5;


				$this->excelfile->getActiveSheet()->getStyle('C'.$flag.':D'.$flag)->applyFromArray($styleArray);
				$this->cellStyle("C".$flag, "C00000", "000000", TRUE, 12, "Franklin Gothic Book");
				$proveedor[$key]->estatus->setCellValue("C".$flag, "CEDIS/SUPER");
				$proveedor[$key]->estatus->setCellValue("D{$flag}", "=BX{$totis}")->getStyle("D{$flag}")->getNumberFormat()->setFormatCode("_(\"$\"* #,##0.00_);_(\"$\"* \(#,##0.00\);_(\"$\"* \"-\"??_);_(@_)");
				$ced=$ced."".$va->alias."!D{$flag}+";
				$flag++;
				$this->excelfile->getActiveSheet()->getStyle('C'.$flag.':D'.$flag)->applyFromArray($styleArray);
				$proveedor[$key]->estatus->setCellValue("C".$flag, "CD INDUSTRIAL");
				$this->cellStyle("C".$flag, "FF0066", "000000", TRUE, 12, "Franklin Gothic Book");
				$proveedor[$key]->estatus->setCellValue("D{$flag}", "=BY{$totis}")->getStyle("D{$flag}")->getNumberFormat()->setFormatCode("_(\"$\"* #,##0.00_);_(\"$\"* \(#,##0.00\);_(\"$\"* \"-\"??_);_(@_)");
				$sup=$sup."".$va->alias."!D{$flag}+";
				$flag++;
				$this->excelfile->getActiveSheet()->getStyle('C'.$flag.':D'.$flag)->applyFromArray($styleArray);
				$this->cellStyle("C".$flag, "01B0F0", "000000", TRUE, 12, "Franklin Gothic Book");
				$proveedor[$key]->estatus->setCellValue("C".$flag, "ABARROTES");
				$proveedor[$key]->estatus->setCellValue("D{$flag}", "=BZ{$totis}")->getStyle("D{$flag}")->getNumberFormat()->setFormatCode("_(\"$\"* #,##0.00_);_(\"$\"* \(#,##0.00\);_(\"$\"* \"-\"??_);_(@_)");
				$aba=$aba."".$va->alias."!D{$flag}+";
				$flag++;
				$this->excelfile->getActiveSheet()->getStyle('C'.$flag.':D'.$flag)->applyFromArray($styleArray);
				$this->cellStyle("C".$flag, "FF0000", "000000", TRUE, 12, "Franklin Gothic Book");
				$proveedor[$key]->estatus->setCellValue("C".$flag, "PEDREGAL");
				$proveedor[$key]->estatus->setCellValue("D{$flag}", "=CA{$totis}")->getStyle("D{$flag}")->getNumberFormat()->setFormatCode("_(\"$\"* #,##0.00_);_(\"$\"* \(#,##0.00\);_(\"$\"* \"-\"??_);_(@_)");
				$ped=$ped."".$va->alias."!D{$flag}+";
				$flag++;
				$this->excelfile->getActiveSheet()->getStyle('C'.$flag.':D'.$flag)->applyFromArray($styleArray);
				$this->cellStyle("C".$flag, "E26C0B", "000000", TRUE, 12, "Franklin Gothic Book");
				$proveedor[$key]->estatus->setCellValue("C".$flag, "TIENDA");
				$proveedor[$key]->estatus->setCellValue("D{$flag}", "=CB{$totis}")->getStyle("D{$flag}")->getNumberFormat()->setFormatCode("_(\"$\"* #,##0.00_);_(\"$\"* \(#,##0.00\);_(\"$\"* \"-\"??_);_(@_)");
				$tie=$tie."".$va->alias."!D{$flag}+";
				$flag++;
				$this->excelfile->getActiveSheet()->getStyle('C'.$flag.':D'.$flag)->applyFromArray($styleArray);
				$this->cellStyle("C".$flag, "C5C5C5", "000000", TRUE, 12, "Franklin Gothic Book");
				$proveedor[$key]->estatus->setCellValue("C".$flag, "ULTRAMARINOS");
				$proveedor[$key]->estatus->setCellValue("D{$flag}", "=CC{$totis}")->getStyle("D{$flag}")->getNumberFormat()->setFormatCode("_(\"$\"* #,##0.00_);_(\"$\"* \(#,##0.00\);_(\"$\"* \"-\"??_);_(@_)");
				$ult=$ult."".$va->alias."!D{$flag}+";
				$flag++;
				$this->excelfile->getActiveSheet()->getStyle('C'.$flag.':D'.$flag)->applyFromArray($styleArray);
				$this->cellStyle("C".$flag, "92D051", "000000", TRUE, 12, "Franklin Gothic Book");
				$proveedor[$key]->estatus->setCellValue("C".$flag, "TRINCHERAS");
				$proveedor[$key]->estatus->setCellValue("D{$flag}", "=CD{$totis}")->getStyle("D{$flag}")->getNumberFormat()->setFormatCode("_(\"$\"* #,##0.00_);_(\"$\"* \(#,##0.00\);_(\"$\"* \"-\"??_);_(@_)");
				$tri=$tri."".$va->alias."!D{$flag}+";
				$flag++;
				$this->excelfile->getActiveSheet()->getStyle('C'.$flag.':D'.$flag)->applyFromArray($styleArray);
				$this->cellStyle("C".$flag, "B1A0C7", "000000", TRUE, 12, "Franklin Gothic Book");
				$proveedor[$key]->estatus->setCellValue("C".$flag, "AZT MERCADO");
				$proveedor[$key]->estatus->setCellValue("D{$flag}", "=CE{$totis}")->getStyle("D{$flag}")->getNumberFormat()->setFormatCode("_(\"$\"* #,##0.00_);_(\"$\"* \(#,##0.00\);_(\"$\"* \"-\"??_);_(@_)");
				$mer=$mer."".$va->alias."!D{$flag}+";
				$flag++;
				$this->excelfile->getActiveSheet()->getStyle('C'.$flag.':D'.$flag)->applyFromArray($styleArray);
				$this->cellStyle("C".$flag, "DA9694", "000000", TRUE, 12, "Franklin Gothic Book");
				$proveedor[$key]->estatus->setCellValue("C".$flag, "TENENCIA");
				$proveedor[$key]->estatus->setCellValue("D{$flag}", "=CF{$totis}")->getStyle("D{$flag}")->getNumberFormat()->setFormatCode("_(\"$\"* #,##0.00_);_(\"$\"* \(#,##0.00\);_(\"$\"* \"-\"??_);_(@_)");
				$ten=$ten."".$va->alias."!D{$flag}+";
				$flag++;
				$this->excelfile->getActiveSheet()->getStyle('C'.$flag.':D'.$flag)->applyFromArray($styleArray);
				$this->cellStyle("C".$flag, "4CACC6", "000000", TRUE, 12, "Franklin Gothic Book");
				$proveedor[$key]->estatus->setCellValue("C".$flag, "TIJERAS");
				$proveedor[$key]->estatus->setCellValue("D{$flag}", "=CG{$totis}")->getStyle("D{$flag}")->getNumberFormat()->setFormatCode("_(\"$\"* #,##0.00_);_(\"$\"* \(#,##0.00\);_(\"$\"* \"-\"??_);_(@_)");
				$tij=$tij."".$va->alias."!D{$flag}+";
				$flag++;
				$this->excelfile->getActiveSheet()->getStyle('C'.$flag.':D'.$flag)->applyFromArray($styleArray);
				$this->cellStyle("C".$flag, "000000", "FFFFFF", TRUE, 12, "Franklin Gothic Book");
				$proveedor[$key]->estatus->setCellValue("C".$flag, "TOTAL");
				$proveedor[$key]->estatus->setCellValue("D{$flag}", "=SUM(D".($totis+5).":D".($flag-1).")")->getStyle("D{$flag}")->getNumberFormat()->setFormatCode("_(\"$\"* #,##0.00_);_(\"$\"* \(#,##0.00\);_(\"$\"* \"-\"??_);_(@_)");
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
		$totales->setCellValue("C{$flag}", substr($ced, 0, -1))->getStyle("C{$flag}")->getNumberFormat()->setFormatCode("_(\"$\"* #,##0.00_);_(\"$\"* \(#,##0.00\);_(\"$\"* \"-\"??_);_(@_)");
		
		$flag++;
		$this->excelfile->getActiveSheet()->getStyle('B'.$flag.':C'.$flag)->applyFromArray($styleArray);
		$totales->setCellValue("B".$flag, "CD INDUSTRIAL");
		$this->cellStyle("B".$flag, "FF0066", "000000", TRUE, 12, "Franklin Gothic Book");
		$sup = substr_replace($sup ,"", -1);
		$totales->setCellValue("C{$flag}", substr($sup, 0, -1))->getStyle("C{$flag}")->getNumberFormat()->setFormatCode("_(\"$\"* #,##0.00_);_(\"$\"* \(#,##0.00\);_(\"$\"* \"-\"??_);_(@_)");
		
		$flag++;
		$this->excelfile->getActiveSheet()->getStyle('B'.$flag.':C'.$flag)->applyFromArray($styleArray);
		$this->cellStyle("B".$flag, "01B0F0", "000000", TRUE, 12, "Franklin Gothic Book");
		$totales->setCellValue("B".$flag, "ABARROTES");
		$totales->setCellValue("C{$flag}", substr($aba, 0, -1))->getStyle("C{$flag}")->getNumberFormat()->setFormatCode("_(\"$\"* #,##0.00_);_(\"$\"* \(#,##0.00\);_(\"$\"* \"-\"??_);_(@_)");
		
		$flag++;
		$this->excelfile->getActiveSheet()->getStyle('B'.$flag.':C'.$flag)->applyFromArray($styleArray);
		$this->cellStyle("B".$flag, "FF0000", "000000", TRUE, 12, "Franklin Gothic Book");
		$totales->setCellValue("B".$flag, "PEDREGAL");
		$totales->setCellValue("C{$flag}", substr($ped, 0, -1))->getStyle("C{$flag}")->getNumberFormat()->setFormatCode("_(\"$\"* #,##0.00_);_(\"$\"* \(#,##0.00\);_(\"$\"* \"-\"??_);_(@_)");
		
		$flag++;
		$this->excelfile->getActiveSheet()->getStyle('B'.$flag.':C'.$flag)->applyFromArray($styleArray);
		$this->cellStyle("B".$flag, "E26C0B", "000000", TRUE, 12, "Franklin Gothic Book");
		$totales->setCellValue("B".$flag, "TIENDA");
		$totales->setCellValue("C{$flag}", substr($tie, 0, -1))->getStyle("C{$flag}")->getNumberFormat()->setFormatCode("_(\"$\"* #,##0.00_);_(\"$\"* \(#,##0.00\);_(\"$\"* \"-\"??_);_(@_)");
		
		$flag++;
		$this->excelfile->getActiveSheet()->getStyle('B'.$flag.':C'.$flag)->applyFromArray($styleArray);
		$this->cellStyle("B".$flag, "C5C5C5", "000000", TRUE, 12, "Franklin Gothic Book");
		$totales->setCellValue("B".$flag, "ULTRAMARINOS");
		$totales->setCellValue("C{$flag}", substr($ult, 0, -1))->getStyle("C{$flag}")->getNumberFormat()->setFormatCode("_(\"$\"* #,##0.00_);_(\"$\"* \(#,##0.00\);_(\"$\"* \"-\"??_);_(@_)");
		
		$flag++;
		$this->excelfile->getActiveSheet()->getStyle('B'.$flag.':C'.$flag)->applyFromArray($styleArray);
		$this->cellStyle("B".$flag, "92D051", "000000", TRUE, 12, "Franklin Gothic Book");
		$totales->setCellValue("B".$flag, "TRINCHERAS");
		$totales->setCellValue("C{$flag}", substr($tri, 0, -1))->getStyle("C{$flag}")->getNumberFormat()->setFormatCode("_(\"$\"* #,##0.00_);_(\"$\"* \(#,##0.00\);_(\"$\"* \"-\"??_);_(@_)");
		
		$flag++;
		$this->excelfile->getActiveSheet()->getStyle('B'.$flag.':C'.$flag)->applyFromArray($styleArray);
		$this->cellStyle("B".$flag, "B1A0C7", "000000", TRUE, 12, "Franklin Gothic Book");
		$totales->setCellValue("B".$flag, "AZT MERCADO");
		$totales->setCellValue("C{$flag}", substr($mer, 0, -1))->getStyle("C{$flag}")->getNumberFormat()->setFormatCode("_(\"$\"* #,##0.00_);_(\"$\"* \(#,##0.00\);_(\"$\"* \"-\"??_);_(@_)");
		
		$flag++;
		$this->excelfile->getActiveSheet()->getStyle('B'.$flag.':C'.$flag)->applyFromArray($styleArray);
		$this->cellStyle("B".$flag, "DA9694", "000000", TRUE, 12, "Franklin Gothic Book");
		$totales->setCellValue("B".$flag, "TENENCIA");
		$totales->setCellValue("C{$flag}", substr($ten, 0, -1))->getStyle("C{$flag}")->getNumberFormat()->setFormatCode("_(\"$\"* #,##0.00_);_(\"$\"* \(#,##0.00\);_(\"$\"* \"-\"??_);_(@_)");
		
		$flag++;
		$this->excelfile->getActiveSheet()->getStyle('B'.$flag.':C'.$flag)->applyFromArray($styleArray);
		$this->cellStyle("B".$flag, "4CACC6", "000000", TRUE, 12, "Franklin Gothic Book");
		$totales->setCellValue("B".$flag, "TIJERAS");
		$totales->setCellValue("C{$flag}", substr($tij, 0, -1))->getStyle("C{$flag}")->getNumberFormat()->setFormatCode("_(\"$\"* #,##0.00_);_(\"$\"* \(#,##0.00\);_(\"$\"* \"-\"??_);_(@_)");
		
		$flag++;
		$this->excelfile->getActiveSheet()->getStyle('B'.$flag.':C'.$flag)->applyFromArray($styleArray);
		$this->cellStyle("B".$flag, "000000", "FFFFFF", TRUE, 12, "Franklin Gothic Book");
		$totales->setCellValue("B".$flag, "TOTAL");
		$totales->setCellValue("C{$flag}", "=SUM(C2:C11)")->getStyle("C{$flag}")->getNumberFormat()->setFormatCode("_(\"$\"* #,##0.00_);_(\"$\"* \(#,##0.00\);_(\"$\"* \"-\"??_);_(@_)");*/
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
		$hoja->getColumnDimension('F')->setWidth("50");

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
					$hoja->mergeCells('A'.$flag.':F'.$flag);
					$this->cellStyle("A".$flag."", "4f81bd", "FFFFFF", TRUE, 12, "Franklin Gothic Book");
					$hoja->setCellValue("A".$flag."", $value->nombre);
					$this->excelfile->getActiveSheet()->getStyle('A'.$flag.':F'.$flag.'')->applyFromArray($styleArray);
					$this->cellStyle("G".$flag."", "000000", "FFFFFF", TRUE, 12, "Franklin Gothic Book");
					$hoja->setCellValue("G".$flag."", "PENDIENT");
					$this->cellStyle("H".$flag."", "000000", "FFFFFF", TRUE, 12, "Franklin Gothic Book");
					$hoja->setCellValue("H".$flag."", "PENDIENT");
					$this->cellStyle("I".$flag."", "000000", "FFFFFF", TRUE, 12, "Franklin Gothic Book");
					$hoja->setCellValue("I".$flag."", "PENDIENT");
					$this->cellStyle("J".$flag."", "000000", "FFFFFF", TRUE, 12, "Franklin Gothic Book");
					$hoja->setCellValue("J".$flag."", "PENDIENT");
					$this->cellStyle("K".$flag."", "000000", "FFFFFF", TRUE, 12, "Franklin Gothic Book");
					$hoja->setCellValue("K".$flag."", "PENDIENT");
					$this->cellStyle("L".$flag."", "000000", "FFFFFF", TRUE, 12, "Franklin Gothic Book");
					$hoja->setCellValue("L".$flag."", "PENDIENT");
					$this->cellStyle("M".$flag."", "000000", "FFFFFF", TRUE, 12, "Franklin Gothic Book");
					$hoja->setCellValue("M".$flag."", "PENDIENT");
					$this->cellStyle("N".$flag."", "000000", "FFFFFF", TRUE, 12, "Franklin Gothic Book");
					$hoja->setCellValue("N".$flag."", "PENDIENT");
					$this->cellStyle("O".$flag."", "000000", "FFFFFF", TRUE, 12, "Franklin Gothic Book");
					$hoja->setCellValue("O".$flag."", "PENDIENT");
					$flag++;
					$this->excelfile->getActiveSheet()->getStyle('A'.$flag.':F'.$flag.'')->applyFromArray($styleArray);
					$this->cellStyle("A".$flag."", "1f497d", "FFFFFF", TRUE, 12, "Franklin Gothic Book");
					$this->cellStyle("B".$flag."", "1f497d", "FFFFFF", TRUE, 12, "Franklin Gothic Book");
					$this->cellStyle("C".$flag."", "1f497d", "FFFFFF", TRUE, 12, "Franklin Gothic Book");
					$this->cellStyle("D".$flag."", "1f497d", "FFFFFF", TRUE, 12, "Franklin Gothic Book");
					$this->cellStyle("E".$flag."", "1f497d", "FFFFFF", TRUE, 12, "Franklin Gothic Book");
					$this->cellStyle("F".$flag."", "1f497d", "FFFFFF", TRUE, 12, "Franklin Gothic Book");
					$hoja->setCellValue("A".$flag."", "CAJA");
					$hoja->setCellValue("B".$flag."", "PZAS");
					$hoja->setCellValue("C".$flag."", "PEDIDO");
					$hoja->setCellValue("D".$flag."", "CÓDIGO");
					$hoja->setCellValue("E".$flag."", "DESCRIPCIÓN");
					$hoja->setCellValue("F".$flag."", "PROMOCIÓN");
					$hoja->setCellValue("A".$flag."", "CAJAS");
					$hoja->setCellValue("B".$flag."", "PZAS");
					$hoja->setCellValue("C".$flag."", "PEDIDO");
					$hoja->setCellValue("D".$flag."", "CÓDIGO");
					$hoja->setCellValue("F".$flag."", "PROMOCIÓN");
					$this->cellStyle("G".$flag, "C00000", "000000", TRUE, 10, "Franklin Gothic Book");
					$this->cellStyle("H".$flag, "01B0F0", "000000", TRUE, 10, "Franklin Gothic Book");
					$this->cellStyle("I".$flag, "FF0000", "000000", TRUE, 10, "Franklin Gothic Book");
					$this->cellStyle("J".$flag, "E26C0B", "000000", TRUE, 10, "Franklin Gothic Book");
					$this->cellStyle("K".$flag, "C5C5C5", "000000", TRUE, 10, "Franklin Gothic Book");
					$this->cellStyle("L".$flag, "92D051", "000000", TRUE, 10, "Franklin Gothic Book");
					$this->cellStyle("M".$flag, "B1A0C7", "000000", TRUE, 10, "Franklin Gothic Book");
					$this->cellStyle("N".$flag, "DA9694", "000000", TRUE, 10, "Franklin Gothic Book");
					$this->cellStyle("O".$flag, "4CACC6", "000000", TRUE, 10, "Franklin Gothic Book");
					$hoja->setCellValue("G".$flag."", "CEDIS");
					$hoja->setCellValue("H".$flag."", "ABARROTES");
					$hoja->setCellValue("I".$flag."", "VILLAS");
					$hoja->setCellValue("J".$flag."", "TIENDA");
					$hoja->setCellValue("K".$flag."", "ULTRA");
					$hoja->setCellValue("L".$flag."", "TRINCHERAS");
					$hoja->setCellValue("M".$flag."", "MERCADO");
					$hoja->setCellValue("N".$flag."", "TENENCIA");
					$hoja->setCellValue("O".$flag."", "TIJERAS");
					$flag++;
					$this->excelfile->getActiveSheet()->getStyle('A'.$flag.':F'.$flag.'')->applyFromArray($styleArray);
					$this->cellStyle("D".$flag."", "FFFFFF", "000000", FALSE, 10, "Franklin Gothic Book");
					$this->cellStyle("E".$flag."", "FFFFFF", "000000", FALSE, 10, "Franklin Gothic Book");
					$hoja->setCellValue("D".$flag."", $value->codigo)->getStyle("D{$flag}")->getNumberFormat()->setFormatCode('# ???/???');
					$hoja->setCellValue("E".$flag."", $value->descripcion);
					$hoja->setCellValue("F".$flag."", $value->observaciones);
					$hoja->setCellValue("G".$flag."", $value->cedis);
					$hoja->setCellValue("H".$flag."", $value->abarrotes);
					$hoja->setCellValue("I".$flag."", $value->pedregal);
					$hoja->setCellValue("J".$flag."", $value->tienda);
					$hoja->setCellValue("K".$flag."", $value->ultra);
					$hoja->setCellValue("L".$flag."", $value->trincheras);
					$hoja->setCellValue("M".$flag."", $value->mercado);
					$hoja->setCellValue("N".$flag."", $value->tenencia);
					$hoja->setCellValue("O".$flag."", $value->tijeras);
					if ($value->promo === 1 || $value->promo === "1") {
						$condRed = new PHPExcel_Style_Conditional();
						$condRed->setConditionType(PHPExcel_Style_Conditional::CONDITION_EXPRESSION)
				                ->addCondition("=MOD(C".$flag.",".$value->cuantos1.")>0")
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
						$conditionalStyles = $this->excelfile->getActiveSheet()->getStyle('C'.$flag)->getConditionalStyles();
						array_push($conditionalStyles,$condRed);
						$this->excelfile->getActiveSheet()->getStyle('C'.$flag)->setConditionalStyles($conditionalStyles);
					}elseif ($value->promo === 3 || $value->promo === "3") {
						$condRed = new PHPExcel_Style_Conditional();
						$condRed->setConditionType(PHPExcel_Style_Conditional::CONDITION_EXPRESSION)
				                ->addCondition("=MOD(C".$flag.",".$value->cuantos1.")>0")
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
						$conditionalStyles = $this->excelfile->getActiveSheet()->getStyle('C'.$flag)->getConditionalStyles();
						array_push($conditionalStyles,$condRed);
						$this->excelfile->getActiveSheet()->getStyle('C'.$flag)->setConditionalStyles($conditionalStyles);
					}
					$flag++;
				}else{
					$this->excelfile->getActiveSheet()->getStyle('A'.$flag.':F'.$flag.'')->applyFromArray($styleArray);
					$this->cellStyle("D".$flag."", "FFFFFF", "000000", FALSE, 10, "Franklin Gothic Book");
					$this->cellStyle("E".$flag."", "FFFFFF", "000000", FALSE, 10, "Franklin Gothic Book");
					$hoja->setCellValue("D".$flag."", $value->codigo)->getStyle("D{$flag}")->getNumberFormat()->setFormatCode('# ???/???');
					$hoja->setCellValue("E".$flag."", $value->descripcion);
					$hoja->setCellValue("F".$flag."", $value->observaciones);
					$hoja->setCellValue("G".$flag."", $value->cedis);
					$hoja->setCellValue("H".$flag."", $value->abarrotes);
					$hoja->setCellValue("I".$flag."", $value->pedregal);
					$hoja->setCellValue("J".$flag."", $value->tienda);
					$hoja->setCellValue("K".$flag."", $value->ultra);
					$hoja->setCellValue("L".$flag."", $value->trincheras);
					$hoja->setCellValue("M".$flag."", $value->mercado);
					$hoja->setCellValue("N".$flag."", $value->tenencia);
					$hoja->setCellValue("O".$flag."", $value->tijeras);
					if ($value->promo === 1 || $value->promo === "1") {
						$condRed = new PHPExcel_Style_Conditional();
						$condRed->setConditionType(PHPExcel_Style_Conditional::CONDITION_EXPRESSION)
				                ->addCondition("=MOD(C".$flag.",".$value->cuantos1.")>0")
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
						$conditionalStyles = $this->excelfile->getActiveSheet()->getStyle('C'.$flag)->getConditionalStyles();
						array_push($conditionalStyles,$condRed);
						$this->excelfile->getActiveSheet()->getStyle('C'.$flag)->setConditionalStyles($conditionalStyles);
					}elseif ($value->promo === 3 || $value->promo === "3") {
						$condRed = new PHPExcel_Style_Conditional();
						$condRed->setConditionType(PHPExcel_Style_Conditional::CONDITION_EXPRESSION)
				                ->addCondition("=MOD(C".$flag.",".$value->cuantos1.")>0")
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
						$conditionalStyles = $this->excelfile->getActiveSheet()->getStyle('C'.$flag)->getConditionalStyles();
						array_push($conditionalStyles,$condRed);
						$this->excelfile->getActiveSheet()->getStyle('C'.$flag)->setConditionalStyles($conditionalStyles);
					}
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

	public function excel_semanon(){
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
		$bande=1;
		foreach ($proveedor as $key => $va) {
			$infos = $this->prolu_md->printProdis(NULL,$va->id_proveedor,$tiendas->total);
			if ($infos) {
				if (1 == 1) {
					$key = 1;

					/******************BEGIN HOJA EXISTENCIAS******************/
					$this->excelfile->setActiveSheetIndex(0);
					$pedide = $this->excelfile->getActiveSheet();
					$pedide->getColumnDimension('A')->setWidth("6");
					$pedide->getColumnDimension('B')->setWidth("6");
					$pedide->getColumnDimension('C')->setWidth("6");
					$pedide->getColumnDimension('D')->setWidth("25");
					$pedide->getColumnDimension('E')->setWidth("58");
					$pedide->getColumnDimension('F')->setWidth("50");
					$pedide->mergeCells('A'.$bande.':F'.$bande);
					$this->cellStyle("A".$bande."", "FFFFFF", "000000", TRUE, 12, "Franklin Gothic Book");
					$pedide->setCellValue("A".$bande."", "GRUPO ABARROTES AZTECA");
					$this->excelfile->getActiveSheet()->getStyle('A'.$bande.':F'.$bande.'')->applyFromArray($styleArray);
					$bande++;
					$pedide->mergeCells('A'.$bande.':F'.$bande.'');
					$this->cellStyle("A".$bande."", "FFFFFF", "000000", TRUE, 12, "Franklin Gothic Book");
					$pedide->setCellValue("A".$bande."", "PEDIDOS A '".$va->nombre."' ".date("d-m-Y"));
					$this->excelfile->getActiveSheet()->getStyle('A'.$bande.':F'.$bande.'')->applyFromArray($styleArray);

					$bande++;
					$this->cellStyle("A".$bande.":F".$bande."", "000000", "FFFFFF", TRUE, 12, "Franklin Gothic Book");
					$pedide->mergeCells('A'.$bande.':C'.$bande.'');

					$pedide->setCellValue("A".$bande."", "EXISTENCIAS");
					$pedide->setCellValue("E".$bande."", "DESCRIPCIÓN");
					$pedide->setCellValue("D".$bande."", "CÓD");
					$pedide->setCellValue("F".$bande."", "PROMO");
					$this->cellStyle("D".$bande."", "000000", "FFFFFF", TRUE, 12, "Franklin Gothic Book");
					$this->cellStyle("E".$bande."", "000000", "FFFFFF", TRUE, 12, "Franklin Gothic Book");
					$this->cellStyle("F".$bande."", "000000", "FFFFFF", TRUE, 12, "Franklin Gothic Book");
					$this->excelfile->getActiveSheet()->getStyle('A'.$bande.':F'.$bande.'')->applyFromArray($styleArray);
					$this->cellStyle("G".$bande."", "000000", "FFFFFF", TRUE, 12, "Franklin Gothic Book");
					$pedide->setCellValue("G".$bande."", "PENDIENT");
					$this->cellStyle("H".$bande."", "000000", "FFFFFF", TRUE, 12, "Franklin Gothic Book");
					$pedide->setCellValue("H".$bande."", "PENDIENT");
					$this->cellStyle("I".$bande."", "000000", "FFFFFF", TRUE, 12, "Franklin Gothic Book");
					$pedide->setCellValue("I".$bande."", "PENDIENT");
					$this->cellStyle("J".$bande."", "000000", "FFFFFF", TRUE, 12, "Franklin Gothic Book");
					$pedide->setCellValue("J".$bande."", "PENDIENT");
					$this->cellStyle("K".$bande."", "000000", "FFFFFF", TRUE, 12, "Franklin Gothic Book");
					$pedide->setCellValue("K".$bande."", "PENDIENT");
					$this->cellStyle("L".$bande."", "000000", "FFFFFF", TRUE, 12, "Franklin Gothic Book");
					$pedide->setCellValue("L".$bande."", "PENDIENT");
					$this->cellStyle("M".$bande."", "000000", "FFFFFF", TRUE, 12, "Franklin Gothic Book");
					$pedide->setCellValue("M".$bande."", "PENDIENT");
					$this->cellStyle("N".$bande."", "000000", "FFFFFF", TRUE, 12, "Franklin Gothic Book");
					$pedide->setCellValue("N".$bande."", "PENDIENT");
					$this->cellStyle("O".$bande."", "000000", "FFFFFF", TRUE, 12, "Franklin Gothic Book");
					$pedide->setCellValue("O".$bande."", "PENDIENT");
					$bande++;
					$this->cellStyle("A".$bande.":F".$bande."", "000000", "FFFFFF", TRUE, 12, "Franklin Gothic Book");
					$pedide->setCellValue("A".$bande."", "CAJAS");
					$pedide->setCellValue("B".$bande."", "PZAS");
					$pedide->setCellValue("C".$bande."", "PEDIDO");
					$pedide->setCellValue("D".$bande."", "CÓDIGO");
					$pedide->setCellValue("F".$bande."", "PROMOCIÓN");
					$this->cellStyle("G".$bande, "C00000", "000000", TRUE, 10, "Franklin Gothic Book");
					$this->cellStyle("H".$bande, "01B0F0", "000000", TRUE, 10, "Franklin Gothic Book");
					$this->cellStyle("I".$bande, "FF0000", "000000", TRUE, 10, "Franklin Gothic Book");
					$this->cellStyle("J".$bande, "E26C0B", "000000", TRUE, 10, "Franklin Gothic Book");
					$this->cellStyle("K".$bande, "C5C5C5", "000000", TRUE, 10, "Franklin Gothic Book");
					$this->cellStyle("L".$bande, "92D051", "000000", TRUE, 10, "Franklin Gothic Book");
					$this->cellStyle("M".$bande, "B1A0C7", "000000", TRUE, 10, "Franklin Gothic Book");
					$this->cellStyle("N".$bande, "DA9694", "000000", TRUE, 10, "Franklin Gothic Book");
					$this->cellStyle("O".$bande, "4CACC6", "000000", TRUE, 10, "Franklin Gothic Book");
					$pedide->setCellValue("G".$bande."", "CEDIS");
					$pedide->setCellValue("H".$bande."", "ABARROTES");
					$pedide->setCellValue("I".$bande."", "VILLAS");
					$pedide->setCellValue("J".$bande."", "TIENDA");
					$pedide->setCellValue("K".$bande."", "ULTRA");
					$pedide->setCellValue("L".$bande."", "TRINCHERAS");
					$pedide->setCellValue("M".$bande."", "MERCADO");
					$pedide->setCellValue("N".$bande."", "TENENCIA");
					$pedide->setCellValue("O".$bande."", "TIJERAS");
					$this->excelfile->getActiveSheet()->getStyle('A'.$bande.':O'.$bande.'')->applyFromArray($styleArray);
					$bande++;
					/******************END HOJA EXISTENCIAS******************/

					$this->excelfile->setActiveSheetIndex($key);
					$proveedor[$key]->estatus = $this->excelfile->getActiveSheet();
					$proveedor[$key]->estatus->mergeCells('A'.$flag.':BA'.$flag);
					$this->cellStyle("A".$flag, "FFFFFF", "000000", TRUE, 12, "Franklin Gothic Book");
					$proveedor[$key]->estatus->setCellValue("A".$flag."", "CEDIS, ABARROTES,PEDREGAL, TIENDA, ULTRAMARINOS, TRINCHERAS, MERCADO, TIJERAS, Y TENENCIA AZTECA AUTOSERVICIOS SA. DE CV.");
					$this->excelfile->getActiveSheet()->getStyle('A'.$flag.':BA'.$flag)->applyFromArray($styleArray);
					$flag++;
					$this->excelfile->getActiveSheet()->getStyle('A'.$flag.':BA'.$flag)->applyFromArray($styleArray);
					$proveedor[$key]->estatus->getColumnDimension('A')->setWidth("25");
					$proveedor[$key]->estatus->getColumnDimension('C')->setWidth("70");
					
					$proveedor[$key]->estatus->getColumnDimension('AZ')->setWidth("40");
					$proveedor[$key]->estatus->getColumnDimension('BA')->setWidth("40");
					$proveedor[$key]->estatus->getColumnDimension('D')->setWidth("15");
					$proveedor[$key]->estatus->getColumnDimension('E')->setWidth("15");
					$proveedor[$key]->estatus->getColumnDimension('F')->setWidth("15");
					$proveedor[$key]->estatus->mergeCells('A'.$flag.':C'.$flag);
					$this->cellStyle("A".$flag, "FFFFFF", "000000", TRUE, 12, "Franklin Gothic Book");
					$proveedor[$key]->estatus->setCellValue("A".$flag."", "PEDIDO A ".$va->nombre);

					$proveedor[$key]->estatus->mergeCells('D'.$flag.':G'.$flag);
					$this->cellStyle("D".$flag, "FFFFFF", "000000", TRUE, 12, "Franklin Gothic Book");
					$proveedor[$key]->estatus->setCellValue("D".$flag."", $fecha);

					$proveedor[$key]->estatus->mergeCells('H'.$flag.':K'.$flag);
					$this->cellStyle("H".$flag, "C00000", "000000", TRUE, 12, "Franklin Gothic Book");
					$proveedor[$key]->estatus->setCellValue("H".$flag, "CEDIS/SUPER");

					$proveedor[$key]->estatus->mergeCells('L'.$flag.':O'.$flag);
					$this->cellStyle("L".$flag, "C2B90A", "000000", TRUE, 12, "Franklin Gothic Book");
					$proveedor[$key]->estatus->setCellValue("L".$flag, "SUMA CEDIS/SUPER");
					$proveedor[$key]->estatus->mergeCells('P'.$flag.':S'.$flag);
					$proveedor[$key]->estatus->setCellValue("P".$flag, "CD INDUSTRIAL");
					$this->cellStyle("P".$flag, "FF0066", "000000", TRUE, 12, "Franklin Gothic Book");
					$proveedor[$key]->estatus->mergeCells('T'.$flag.':W'.$flag);
					$this->cellStyle("T".$flag, "01B0F0", "000000", TRUE, 12, "Franklin Gothic Book");
					$proveedor[$key]->estatus->setCellValue("T".$flag, "ABARROTES");
					$proveedor[$key]->estatus->mergeCells('X'.$flag.':AA'.$flag);
					$this->cellStyle("X".$flag, "FF0000", "000000", TRUE, 12, "Franklin Gothic Book");
					$proveedor[$key]->estatus->setCellValue("X".$flag, "PEDREGAL");
					$proveedor[$key]->estatus->mergeCells('AB'.$flag.':AE'.$flag);
					$this->cellStyle("AB".$flag, "E26C0B", "000000", TRUE, 12, "Franklin Gothic Book");
					$proveedor[$key]->estatus->setCellValue("AB".$flag, "TIENDA");
					$proveedor[$key]->estatus->mergeCells('AF'.$flag.':AI'.$flag);
					$this->cellStyle("AF".$flag, "C5C5C5", "000000", TRUE, 12, "Franklin Gothic Book");
					$proveedor[$key]->estatus->setCellValue("AF".$flag, "ULTRAMARINOS");
					$proveedor[$key]->estatus->mergeCells('AJ'.$flag.':AM'.$flag);
					$this->cellStyle("AJ".$flag, "92D051", "000000", TRUE, 12, "Franklin Gothic Book");
					$proveedor[$key]->estatus->setCellValue("AJ".$flag, "TRINCHERAS");
					$proveedor[$key]->estatus->mergeCells('AN'.$flag.':AQ'.$flag);
					$this->cellStyle("AN".$flag, "B1A0C7", "000000", TRUE, 12, "Franklin Gothic Book");
					$proveedor[$key]->estatus->setCellValue("AN".$flag, "AZT MERCADO");
					$proveedor[$key]->estatus->mergeCells('AR'.$flag.':AU'.$flag);
					$this->cellStyle("AR".$flag, "DA9694", "000000", TRUE, 12, "Franklin Gothic Book");
					$proveedor[$key]->estatus->setCellValue("AR".$flag, "TENENCIA");
					$proveedor[$key]->estatus->mergeCells('AV'.$flag.':AY'.$flag);
					$this->cellStyle("AV".$flag, "4CACC6", "000000", TRUE, 12, "Franklin Gothic Book");
					$proveedor[$key]->estatus->setCellValue("AV".$flag, "TIJERAS");
					$this->cellStyle("AZ".$flag, "FFFF00", "FF0000", TRUE, 12, "Franklin Gothic Book");
					$proveedor[$key]->estatus->setCellValue("AZ".$flag, "PROMOCIÓN");
					$this->cellStyle("BA".$flag, "92D050", "FF0000", TRUE, 12, "Franklin Gothic Book");
					$proveedor[$key]->estatus->setCellValue("BA".$flag, "NOTA");

					$flag++;
					$this->excelfile->getActiveSheet()->getStyle('A'.$flag.':AZ'.$flag)->applyFromArray($styleArray);
					$proveedor[$key]->estatus->mergeCells('A'.$flag.':G'.$flag);
					$this->cellStyle("A".$flag, "000000", "FFFFFF", TRUE, 12, "Franklin Gothic Book");
					$proveedor[$key]->estatus->setCellValue("A".$flag."", "DESCRIPCIÓN");

					$proveedor[$key]->estatus->mergeCells('H'.$flag.':K'.$flag);
					$this->cellStyle("H".$flag, "000000", "FFFFFF", TRUE, 12, "Franklin Gothic Book");
					$proveedor[$key]->estatus->setCellValue("H".$flag."", "EXISTENCIAS");

					$proveedor[$key]->estatus->mergeCells('L'.$flag.':O'.$flag);
					$this->cellStyle("L".$flag, "000000", "FFFFFF", TRUE, 12, "Franklin Gothic Book");
					$proveedor[$key]->estatus->setCellValue("L".$flag."", "EXISTENCIAS");

					$proveedor[$key]->estatus->mergeCells('P'.$flag.':S'.$flag);
					$this->cellStyle("P".$flag, "000000", "FFFFFF", TRUE, 12, "Franklin Gothic Book");
					$proveedor[$key]->estatus->setCellValue("P".$flag."", "EXISTENCIAS");

					$proveedor[$key]->estatus->mergeCells('T'.$flag.':W'.$flag);
					$this->cellStyle("T".$flag, "000000", "FFFFFF", TRUE, 12, "Franklin Gothic Book");
					$proveedor[$key]->estatus->setCellValue("T".$flag."", "EXISTENCIAS");

					$proveedor[$key]->estatus->mergeCells('X'.$flag.':AA'.$flag);
					$this->cellStyle("X".$flag, "000000", "FFFFFF", TRUE, 12, "Franklin Gothic Book");
					$proveedor[$key]->estatus->setCellValue("X".$flag."", "EXISTENCIAS");

					$proveedor[$key]->estatus->mergeCells('AB'.$flag.':AE'.$flag);
					$this->cellStyle("AB".$flag, "000000", "FFFFFF", TRUE, 12, "Franklin Gothic Book");
					$proveedor[$key]->estatus->setCellValue("AB".$flag."", "EXISTENCIAS");

					$proveedor[$key]->estatus->mergeCells('AF'.$flag.':AI'.$flag);
					$this->cellStyle("AF".$flag, "000000", "FFFFFF", TRUE, 12, "Franklin Gothic Book");
					$proveedor[$key]->estatus->setCellValue("AF".$flag."", "EXISTENCIAS");

					$proveedor[$key]->estatus->mergeCells('AJ'.$flag.':AM'.$flag);
					$this->cellStyle("AJ".$flag, "000000", "FFFFFF", TRUE, 12, "Franklin Gothic Book");
					$proveedor[$key]->estatus->setCellValue("AJ".$flag."", "EXISTENCIAS");

					$proveedor[$key]->estatus->mergeCells('AN'.$flag.':AQ'.$flag);
					$this->cellStyle("AN".$flag, "000000", "FFFFFF", TRUE, 12, "Franklin Gothic Book");
					$proveedor[$key]->estatus->setCellValue("AN".$flag."", "EXISTENCIAS");

					$proveedor[$key]->estatus->mergeCells('AR'.$flag.':AU'.$flag);
					$this->cellStyle("AR".$flag, "000000", "FFFFFF", TRUE, 12, "Franklin Gothic Book");
					$proveedor[$key]->estatus->setCellValue("AR".$flag."", "EXISTENCIAS");

					$proveedor[$key]->estatus->mergeCells('AV'.$flag.':AY'.$flag);
					$this->cellStyle("AV".$flag, "000000", "FFFFFF", TRUE, 12, "Franklin Gothic Book");
					$proveedor[$key]->estatus->setCellValue("AV".$flag."", "EXISTENCIAS");

					$this->cellStyle("AZ".$flag, "FFFF00", "FF0000", TRUE, 12, "Franklin Gothic Book");
					$this->cellStyle("BA".$flag, "92D050", "FF0000", TRUE, 12, "Franklin Gothic Book");
					$this->cellStyle("BN".$flag, "000000", "FFFFFF", TRUE, 12, "Franklin Gothic Book");
					$proveedor[$key]->estatus->setCellValue("BN".$flag."", "TOTAL");
					
					$flag++;
					$this->excelfile->getActiveSheet()->getStyle('A'.$flag.':BA'.$flag)->applyFromArray($styleArray);
					$proveedor[$key]->estatus->mergeCells('A'.$flag.':C'.$flag);
					$this->cellStyle("A".$flag, "000000", "FFFFFF", TRUE, 12, "Franklin Gothic Book");
					$this->cellStyle("D".$flag, "000000", "FFFFFF", FALSE, 10, "Franklin Gothic Book");
					$proveedor[$key]->estatus->setCellValue("D".$flag."", "PRECIO");
					$this->cellStyle("E".$flag, "000000", "FFFFFF", FALSE, 10, "Franklin Gothic Book");
					$proveedor[$key]->estatus->setCellValue("E".$flag."", "SISTEMA");
					$this->cellStyle("F".$flag, "000000", "FFFFFF", FALSE, 10, "Franklin Gothic Book");
					$proveedor[$key]->estatus->setCellValue("F".$flag."", "UM");
					$this->cellStyle("G".$flag, "000000", "FFFFFF", FALSE, 10, "Franklin Gothic Book");
					$proveedor[$key]->estatus->setCellValue("G".$flag."", "REAL");

					$this->cellStyle("H".$flag.':AY'.$flag, "000000", "FFFFFF", FALSE, 10, "Franklin Gothic Book");
					$proveedor[$key]->estatus->setCellValue("H".$flag."", "CAJAS");
					$proveedor[$key]->estatus->setCellValue("I".$flag."", "PZAS");
					$proveedor[$key]->estatus->setCellValue("J".$flag."", "PEND");
					$proveedor[$key]->estatus->setCellValue("K".$flag."", "PEDIDO");
					$proveedor[$key]->estatus->setCellValue("L".$flag."", "CAJAS");
					$proveedor[$key]->estatus->setCellValue("M".$flag."", "PZAS");
					$proveedor[$key]->estatus->setCellValue("N".$flag."", "PEND");
					$proveedor[$key]->estatus->setCellValue("O".$flag."", "PEDIDO");
					$proveedor[$key]->estatus->setCellValue("P".$flag."", "CAJAS");
					$proveedor[$key]->estatus->setCellValue("Q".$flag."", "PZAS");
					$proveedor[$key]->estatus->setCellValue("R".$flag."", "PEND");
					$proveedor[$key]->estatus->setCellValue("S".$flag."", "PEDIDO");////a
					$proveedor[$key]->estatus->setCellValue("T".$flag."", "CAJAS");
					$proveedor[$key]->estatus->setCellValue("U".$flag."", "PZAS");
					$proveedor[$key]->estatus->setCellValue("V".$flag."", "PEND");
					$proveedor[$key]->estatus->setCellValue("W".$flag."", "PEDIDO");
					$proveedor[$key]->estatus->setCellValue("X".$flag."", "CAJAS");
					$proveedor[$key]->estatus->setCellValue("Y".$flag."", "PZAS");
					$proveedor[$key]->estatus->setCellValue("Z".$flag."", "PEND");
					$proveedor[$key]->estatus->setCellValue("AA".$flag."", "PEDIDO");
					$proveedor[$key]->estatus->setCellValue("AB".$flag."", "CAJAS");
					$proveedor[$key]->estatus->setCellValue("AC".$flag."", "PZAS");
					$proveedor[$key]->estatus->setCellValue("AD".$flag."", "PEND");
					$proveedor[$key]->estatus->setCellValue("AE".$flag."", "PEDIDO");
					$proveedor[$key]->estatus->setCellValue("AF".$flag."", "CAJAS");
					$proveedor[$key]->estatus->setCellValue("AG".$flag."", "PZAS");
					$proveedor[$key]->estatus->setCellValue("AH".$flag."", "PEND");
					$proveedor[$key]->estatus->setCellValue("AI".$flag."", "PEDIDO");
					$proveedor[$key]->estatus->setCellValue("AJ".$flag."", "CAJAS");
					$proveedor[$key]->estatus->setCellValue("AK".$flag."", "PZAS");
					$proveedor[$key]->estatus->setCellValue("AL".$flag."", "PEND");
					$proveedor[$key]->estatus->setCellValue("AM".$flag."", "PEDIDO");
					$proveedor[$key]->estatus->setCellValue("AN".$flag."", "CAJAS");
					$proveedor[$key]->estatus->setCellValue("AO".$flag."", "PZAS");
					$proveedor[$key]->estatus->setCellValue("AP".$flag."", "PEND");
					$proveedor[$key]->estatus->setCellValue("AQ".$flag."", "PEDIDO");
					$proveedor[$key]->estatus->setCellValue("AR".$flag."", "CAJAS");
					$proveedor[$key]->estatus->setCellValue("AS".$flag."", "PZAS");
					$proveedor[$key]->estatus->setCellValue("AT".$flag."", "PEND");
					$proveedor[$key]->estatus->setCellValue("AU".$flag."", "PEDIDO");
					$proveedor[$key]->estatus->setCellValue("AV".$flag."", "CAJAS");
					$proveedor[$key]->estatus->setCellValue("AW".$flag."", "PZAS");
					$proveedor[$key]->estatus->setCellValue("AX".$flag."", "PEND");
					$proveedor[$key]->estatus->setCellValue("AY".$flag."", "PEDIDO");


					$this->cellStyle("AZ".$flag, "FFFF00", "FF0000", FALSE, 10, "Franklin Gothic Book");
					$this->cellStyle("BA".$flag, "92D050", "FF0000", TRUE, 12, "Franklin Gothic Book");

					$this->excelfile->getActiveSheet()->getStyle('BC'.$flag.':BM'.$flag)->applyFromArray($styleArray);

					$this->cellStyle("BC".$flag, "C00000", "000000", TRUE, 12, "Franklin Gothic Book");
					$this->cellStyle("BD".$flag, "FF0066", "000000", TRUE, 12, "Franklin Gothic Book");
					$this->cellStyle("BE".$flag, "01B0F0", "000000", TRUE, 12, "Franklin Gothic Book");
					$this->cellStyle("BF".$flag, "FF0000", "000000", TRUE, 12, "Franklin Gothic Book");
					$this->cellStyle("BG".$flag, "E26C0B", "000000", TRUE, 12, "Franklin Gothic Book");
					$this->cellStyle("BH".$flag, "C5C5C5", "000000", TRUE, 12, "Franklin Gothic Book");
					$this->cellStyle("BI".$flag, "92D051", "000000", TRUE, 12, "Franklin Gothic Book");
					$this->cellStyle("BJ".$flag, "B1A0C7", "000000", TRUE, 12, "Franklin Gothic Book");
					$this->cellStyle("BK".$flag, "DA9694", "000000", TRUE, 12, "Franklin Gothic Book");
					$this->cellStyle("BL".$flag, "4CACC6", "000000", TRUE, 12, "Franklin Gothic Book");
					$this->cellStyle("BM".$flag, "000000", "FFFFFF", TRUE, 12, "Franklin Gothic Book");
					$this->cellStyle("BN".$flag, "000000", "FFFFFF", TRUE, 12, "Franklin Gothic Book");
					
					$proveedor[$key]->estatus->setCellValue("BM".$flag."", "TOTAL");
					$proveedor[$key]->estatus->setCellValue("BN".$flag."", "PEDIDOS");
					
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
					$proveedor[$key]->estatus->getColumnDimension('BM')->setWidth("15");
					$proveedor[$key]->estatus->getColumnDimension('BN')->setWidth("15");
				}
				$flageas = $flag+1;
				foreach ($infos as $keys => $v) {
					/******************BEGIN HOJA EXISTENCIAS******************/
					$this->excelfile->setActiveSheetIndex(0);
					$this->cellStyle("A".$bande.":O".$bande, "FFFFFF", "000000", FALSE, 12, "Franklin Gothic Book");
					
					$pedide->setCellValue("D{$bande}", $v['codigo'])->getStyle("D{$bande}")->getNumberFormat()->setFormatCode('#');//Formato de fraccion
					
					$pedide->setCellValue("E{$bande}", $v['descripcion']);
					$pedide->setCellValue("F{$bande}", $v['observaciones']);
					$pedide->setCellValue("G{$bande}", $v["pend"][1]["pend"]);
					$pedide->setCellValue("H{$bande}", $v["pend"][4]["pend"]);
					$pedide->setCellValue("I{$bande}", $v["pend"][5]["pend"]);
					$pedide->setCellValue("J{$bande}", $v["pend"][6]["pend"]);
					$pedide->setCellValue("K{$bande}", $v["pend"][7]["pend"]);
					$pedide->setCellValue("L{$bande}", $v["pend"][8]["pend"]);
					$pedide->setCellValue("M{$bande}", $v["pend"][9]["pend"]);
					$pedide->setCellValue("N{$bande}", $v["pend"][10]["pend"]);
					$pedide->setCellValue("O{$bande}", $v["pend"][11]["pend"]);
					$this->excelfile->getActiveSheet()->getStyle('A'.$bande.':O'.$bande.'')->applyFromArray($styleArray);

					if ($v["promo"] === 1 || $v["promo"] === "1") {
						$condRed = new PHPExcel_Style_Conditional();
						$condRed->setConditionType(PHPExcel_Style_Conditional::CONDITION_EXPRESSION)
				                ->addCondition("=MOD(C".$bande.",".$v['cuantos1'].")>0")
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
						$conditionalStyles = $this->excelfile->getActiveSheet()->getStyle('C'.$bande)->getConditionalStyles();
						array_push($conditionalStyles,$condRed);
						$this->excelfile->getActiveSheet()->getStyle('C'.$bande)->setConditionalStyles($conditionalStyles);
					}elseif ($v["promo"] === 3 || $v["promo"] === "3") {
						$condRed = new PHPExcel_Style_Conditional();
						$condRed->setConditionType(PHPExcel_Style_Conditional::CONDITION_EXPRESSION)
				                ->addCondition("=MOD(C".$bande.",".$v['cuantos1'].")>0")
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
						$conditionalStyles = $this->excelfile->getActiveSheet()->getStyle('C'.$bande)->getConditionalStyles();
						array_push($conditionalStyles,$condRed);
						$this->excelfile->getActiveSheet()->getStyle('C'.$bande)->setConditionalStyles($conditionalStyles);
					}

					$bande++;
					/******************END HOJA EXISTENCIAS******************/


					$this->excelfile->setActiveSheetIndex($key);
					$proveedor[0]->estatus = $this->excelfile->getActiveSheet();
					$flag++;
					
					$this->excelfile->setActiveSheetIndex($key);
					$this->excelfile->getActiveSheet()->getStyle('A'.$flag.':BA'.$flag)->applyFromArray($styleArray);
					$proveedor[$key]->estatus->setCellValue("A".$flag."", $v["codigo"])->getStyle("A{$flag}")->getNumberFormat()->setFormatCode('# ???/???');
					$this->cellStyle("A".$flag, "FFFFFF", "000000", FALSE, 10, "Franklin Gothic Book");
					$proveedor[$key]->estatus->setCellValue("C".$flag."", $v["descripcion"]);
					$proveedor[$key]->estatus->setCellValue("G".$flag."", $v["real"]);
					$this->cellStyle("C".$flag, "FFFFFF", "000000", FALSE, 10, "Franklin Gothic Book");
					$proveedor[$key]->estatus->setCellValue("D{$flag}", $v["precio"])->getStyle("D{$flag}")->getNumberFormat()->setFormatCode("_(\"$\"* #,##0.00_);_(\"$\"* \(#,##0.00\);_(\"$\"* \"-\"??_);_(@_)");
					$this->cellStyle("D".$flag, "FFFFFF", "000000", FALSE, 10, "Franklin Gothic Book");
					$proveedor[$key]->estatus->setCellValue("E{$flag}", $v["sistema"])->getStyle("E{$flag}")->getNumberFormat()->setFormatCode("_(\"$\"* #,##0.00_);_(\"$\"* \(#,##0.00\);_(\"$\"* \"-\"??_);_(@_)");
					$this->cellStyle("E".$flag, "FFFFFF", "000000", FALSE, 10, "Franklin Gothic Book");
					$proveedor[$key]->estatus->setCellValue("F{$flag}", $v["unidad"]);


					

					$proveedor[$key]->estatus->setCellValue("AZ{$flag}", $v["observaciones"]);					

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

					
					

					$this->cellStyle("H{$flag}:BA{$flag}", "FFFFFF", "000000", TRUE, 10, "Franklin Gothic Book");
					$this->cellStyle("K".$flag, "DCE6F1", "000000", TRUE, 10, "Franklin Gothic Book");
					$this->cellStyle("O".$flag, "DCE6F1", "000000", TRUE, 10, "Franklin Gothic Book");
					$this->cellStyle("S".$flag, "DCE6F1", "000000", TRUE, 10, "Franklin Gothic Book");
					$this->cellStyle("W".$flag, "DCE6F1", "000000", TRUE, 10, "Franklin Gothic Book");
					$this->cellStyle("AA".$flag, "DCE6F1", "000000", TRUE, 10, "Franklin Gothic Book");
					$this->cellStyle("AE".$flag, "DCE6F1", "000000", TRUE, 10, "Franklin Gothic Book");
					$this->cellStyle("AI".$flag, "DCE6F1", "000000", TRUE, 10, "Franklin Gothic Book");
					$this->cellStyle("AM".$flag, "DCE6F1", "000000", TRUE, 10, "Franklin Gothic Book");
					$this->cellStyle("AQ".$flag, "DCE6F1", "000000", TRUE, 10, "Franklin Gothic Book");
					$this->cellStyle("AU".$flag, "DCE6F1", "000000", TRUE, 10, "Franklin Gothic Book");
					$this->cellStyle("AY".$flag, "DCE6F1", "000000", TRUE, 10, "Franklin Gothic Book");

					$this->excelfile->getActiveSheet()->getStyle('BC'.$flag.':BN'.$flag)->applyFromArray($styleArray);
					$this->cellStyle("BC".$flag, "FFFFFF", "000000", FALSE, 10, "Franklin Gothic Book");
					$proveedor[$key]->estatus->setCellValue("BC{$flag}", "=D{$flag}*K{$flag}")->getStyle("BC{$flag}")->getNumberFormat()->setFormatCode("_(\"$\"* #,##0.00_);_(\"$\"* \(#,##0.00\);_(\"$\"* \"-\"??_);_(@_)");
					$this->cellStyle("BD".$flag, "FFFFFF", "000000", FALSE, 10, "Franklin Gothic Book");
					$proveedor[$key]->estatus->setCellValue("BD{$flag}", "=D{$flag}*S{$flag}")->getStyle("BD{$flag}")->getNumberFormat()->setFormatCode("_(\"$\"* #,##0.00_);_(\"$\"* \(#,##0.00\);_(\"$\"* \"-\"??_);_(@_)");
					$this->cellStyle("BE".$flag, "FFFFFF", "000000", FALSE, 10, "Franklin Gothic Book");
					$proveedor[$key]->estatus->setCellValue("BE{$flag}", "=D{$flag}*W{$flag}")->getStyle("BE{$flag}")->getNumberFormat()->setFormatCode("_(\"$\"* #,##0.00_);_(\"$\"* \(#,##0.00\);_(\"$\"* \"-\"??_);_(@_)");
					$this->cellStyle("BF".$flag, "FFFFFF", "000000", FALSE, 10, "Franklin Gothic Book");
					$proveedor[$key]->estatus->setCellValue("BF{$flag}", "=D{$flag}*AA{$flag}")->getStyle("BF{$flag}")->getNumberFormat()->setFormatCode("_(\"$\"* #,##0.00_);_(\"$\"* \(#,##0.00\);_(\"$\"* \"-\"??_);_(@_)");
					$this->cellStyle("BG".$flag, "FFFFFF", "000000", FALSE, 10, "Franklin Gothic Book");
					$proveedor[$key]->estatus->setCellValue("BG{$flag}", "=D{$flag}*AE{$flag}")->getStyle("BG{$flag}")->getNumberFormat()->setFormatCode("_(\"$\"* #,##0.00_);_(\"$\"* \(#,##0.00\);_(\"$\"* \"-\"??_);_(@_)");
					$this->cellStyle("BH".$flag, "FFFFFF", "000000", FALSE, 10, "Franklin Gothic Book");
					$proveedor[$key]->estatus->setCellValue("BH{$flag}", "=D{$flag}*AI{$flag}")->getStyle("BH{$flag}")->getNumberFormat()->setFormatCode("_(\"$\"* #,##0.00_);_(\"$\"* \(#,##0.00\);_(\"$\"* \"-\"??_);_(@_)");
					$this->cellStyle("BI".$flag, "FFFFFF", "000000", FALSE, 10, "Franklin Gothic Book");
					$proveedor[$key]->estatus->setCellValue("BI{$flag}", "=D{$flag}*AM{$flag}")->getStyle("BI{$flag}")->getNumberFormat()->setFormatCode("_(\"$\"* #,##0.00_);_(\"$\"* \(#,##0.00\);_(\"$\"* \"-\"??_);_(@_)");
					$this->cellStyle("BJ".$flag, "FFFFFF", "000000", FALSE, 10, "Franklin Gothic Book");
					$proveedor[$key]->estatus->setCellValue("BJ{$flag}", "=D{$flag}*AQ{$flag}")->getStyle("BJ{$flag}")->getNumberFormat()->setFormatCode("_(\"$\"* #,##0.00_);_(\"$\"* \(#,##0.00\);_(\"$\"* \"-\"??_);_(@_)");
					$this->cellStyle("BK".$flag, "FFFFFF", "000000", FALSE, 10, "Franklin Gothic Book");
					$proveedor[$key]->estatus->setCellValue("BK{$flag}", "=D{$flag}*AU{$flag}")->getStyle("BK{$flag}")->getNumberFormat()->setFormatCode("_(\"$\"* #,##0.00_);_(\"$\"* \(#,##0.00\);_(\"$\"* \"-\"??_);_(@_)");
					$this->cellStyle("BL".$flag, "FFFFFF", "000000", FALSE, 10, "Franklin Gothic Book");
					$proveedor[$key]->estatus->setCellValue("BL{$flag}", "=D{$flag}*AY{$flag}")->getStyle("BL{$flag}")->getNumberFormat()->setFormatCode("_(\"$\"* #,##0.00_);_(\"$\"* \(#,##0.00\);_(\"$\"* \"-\"??_);_(@_)");
					$this->cellStyle("BM".$flag, "FFFFFF", "000000", FALSE, 10, "Franklin Gothic Book");
					$proveedor[$key]->estatus->setCellValue("BM{$flag}", "=SUM(BC{$flag}:BL{$flag})")->getStyle("BM{$flag}")->getNumberFormat()->setFormatCode("_(\"$\"* #,##0.00_);_(\"$\"* \(#,##0.00\);_(\"$\"* \"-\"??_);_(@_)");
					$this->cellStyle("BN".$flag, "FFFFFF", "000000", FALSE, 10, "Franklin Gothic Book");
					$proveedor[$key]->estatus->setCellValue("BN{$flag}", "=K{$flag}+O{$flag}+S{$flag}+W{$flag}+AA{$flag}+AE{$flag}+AI{$flag}+AM{$flag}+AQ{$flag}+AU{$flag}+AY{$flag}")->getStyle("BN{$flag}")->getNumberFormat()->setFormatCode('#,##0.00_-');
					$arras = array(1=>"K",2=>"O",3=>"S",4=>"W",5=>"AA",6=>"AE",7=>"AI",8=>"AM",9=>"AQ",10=>"AU",11=>"AY",12=>"BN");
					for ($i=1; $i <=12 ; $i++){
						if ($v["promo"] === 1 || $v["promo"] === "1") {
							$condRed = new PHPExcel_Style_Conditional();
							$condRed->setConditionType(PHPExcel_Style_Conditional::CONDITION_EXPRESSION)
					                ->addCondition("=MOD(".$arras[$i].$flag.",".$v['cuantos1'].")>0")
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
							$conditionalStyles = $this->excelfile->getActiveSheet()->getStyle(''.$arras[$i].$flag)->getConditionalStyles();
							array_push($conditionalStyles,$condRed);
							$this->excelfile->getActiveSheet()->getStyle(''.$arras[$i].$flag)->setConditionalStyles($conditionalStyles);
						}elseif ($v["promo"] === 3 || $v["promo"] === "3") {
							$condRed = new PHPExcel_Style_Conditional();
							$condRed->setConditionType(PHPExcel_Style_Conditional::CONDITION_EXPRESSION)
					                ->addCondition("=MOD(".$arras[$i].$flag.",".$v['cuantos1'].")>0")
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
							$conditionalStyles = $this->excelfile->getActiveSheet()->getStyle(''.$arras[$i].$flag)->getConditionalStyles();
							array_push($conditionalStyles,$condRed);
							$this->excelfile->getActiveSheet()->getStyle(''.$arras[$i].$flag)->setConditionalStyles($conditionalStyles);
						}	
					}

					$col = 6;
					 foreach ($v["existencias"] as $k => $vs) {
						$proveedor[$key]->estatus->setCellValueByColumnAndRow($col, $flag, $vs["cja"]);
						$col++;
						$proveedor[$key]->estatus->setCellValueByColumnAndRow($col, $flag, $vs["pzs"]);
						$col+=2;
						$proveedor[$key]->estatus->setCellValueByColumnAndRow($col, $flag, $vs["ped"]);
						$col++;
					 }

					if ($v["mins"] <> "" && $v["mins"] > 0 && $v["mins"] <> NULL) {
						$condRed = new PHPExcel_Style_Conditional();
						$condRed->setConditionType(PHPExcel_Style_Conditional::CONDITION_CELLIS)
			                ->setOperatorType(PHPExcel_Style_Conditional::OPERATOR_LESSTHAN)
			                ->addCondition($v["mins"])
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
						$conditionalStyles = $this->excelfile->getActiveSheet()->getStyle('BN'.$flag)->getConditionalStyles();
						array_push($conditionalStyles,$condRed);
						$this->excelfile->getActiveSheet()->getStyle('BN'.$flag)->setConditionalStyles($conditionalStyles);
					}
					 

					 $proveedor[$key]->estatus->setCellValue("J{$flag}", $v["pend"][1]["pend"]);
					 $proveedor[$key]->estatus->setCellValue("V{$flag}", $v["pend"][4]["pend"]);
					 $proveedor[$key]->estatus->setCellValue("Z{$flag}", $v["pend"][5]["pend"]);
					 $proveedor[$key]->estatus->setCellValue("AD{$flag}", $v["pend"][6]["pend"]);
					 $proveedor[$key]->estatus->setCellValue("AH{$flag}", $v["pend"][7]["pend"]);
					 $proveedor[$key]->estatus->setCellValue("AL{$flag}", $v["pend"][8]["pend"]);
					 $proveedor[$key]->estatus->setCellValue("AP{$flag}", $v["pend"][9]["pend"]);
					 $proveedor[$key]->estatus->setCellValue("AT{$flag}", $v["pend"][10]["pend"]);
					 $proveedor[$key]->estatus->setCellValue("AX{$flag}", $v["pend"][11]["pend"]);

					 $proveedor[$key]->estatus->setCellValue("L{$flag}", "=H{$flag}+P{$flag}");
					 $proveedor[$key]->estatus->setCellValue("M{$flag}", "=I{$flag}+Q{$flag}");
					 $proveedor[$key]->estatus->setCellValue("N{$flag}", "=J{$flag}+R{$flag}");
					 $proveedor[$key]->estatus->setCellValue("O{$flag}", "=K{$flag}+S{$flag}");
					
				}
				$flag++;

				$this->excelfile->getActiveSheet()->getStyle('K'.$flag)->applyFromArray($styleArray);
				$this->cellStyle("K".$flag, "FFFFFF", "000000", FALSE, 10, "Franklin Gothic Book");
				$proveedor[$key]->estatus->setCellValue("K{$flag}", "=SUM(K{$flageas}:K".($flag-1).")")->getStyle("K{$flag}")->getNumberFormat()->setFormatCode('#0_-');
				$this->excelfile->getActiveSheet()->getStyle('O'.$flag)->applyFromArray($styleArray);
				$this->cellStyle("O".$flag, "FFFFFF", "000000", FALSE, 10, "Franklin Gothic Book");
				$proveedor[$key]->estatus->setCellValue("O{$flag}", "=SUM(O{$flageas}:O".($flag-1).")")->getStyle("O{$flag}")->getNumberFormat()->setFormatCode('#0_-');
				$this->excelfile->getActiveSheet()->getStyle('S'.$flag)->applyFromArray($styleArray);
				$this->cellStyle("S".$flag, "FFFFFF", "000000", FALSE, 10, "Franklin Gothic Book");
				$proveedor[$key]->estatus->setCellValue("S{$flag}", "=SUM(S{$flageas}:S".($flag-1).")")->getStyle("S{$flag}")->getNumberFormat()->setFormatCode('#0_-');
				$this->excelfile->getActiveSheet()->getStyle('W'.$flag)->applyFromArray($styleArray);
				$this->cellStyle("W".$flag, "FFFFFF", "000000", FALSE, 10, "Franklin Gothic Book");
				$proveedor[$key]->estatus->setCellValue("W{$flag}", "=SUM(W{$flageas}:W".($flag-1).")")->getStyle("W{$flag}")->getNumberFormat()->setFormatCode('#0_-');
				$this->excelfile->getActiveSheet()->getStyle('AA'.$flag)->applyFromArray($styleArray);
				$this->cellStyle("AA".$flag, "FFFFFF", "000000", FALSE, 10, "Franklin Gothic Book");
				$proveedor[$key]->estatus->setCellValue("AA{$flag}", "=SUM(AA{$flageas}:AA".($flag-1).")")->getStyle("AA{$flag}")->getNumberFormat()->setFormatCode('#0_-');
				$this->excelfile->getActiveSheet()->getStyle('AE'.$flag)->applyFromArray($styleArray);
				$this->cellStyle("AE".$flag, "FFFFFF", "000000", FALSE, 10, "Franklin Gothic Book");
				$proveedor[$key]->estatus->setCellValue("AE{$flag}", "=SUM(AE{$flageas}:AE".($flag-1).")")->getStyle("AE{$flag}")->getNumberFormat()->setFormatCode('#0_-');
				$this->excelfile->getActiveSheet()->getStyle('AI'.$flag)->applyFromArray($styleArray);
				$this->cellStyle("AI".$flag, "FFFFFF", "000000", FALSE, 10, "Franklin Gothic Book");
				$proveedor[$key]->estatus->setCellValue("AI{$flag}", "=SUM(AI{$flageas}:AI".($flag-1).")")->getStyle("AI{$flag}")->getNumberFormat()->setFormatCode('#0_-');
				$this->excelfile->getActiveSheet()->getStyle('AM'.$flag)->applyFromArray($styleArray);
				$this->cellStyle("AM".$flag, "FFFFFF", "000000", FALSE, 10, "Franklin Gothic Book");
				$proveedor[$key]->estatus->setCellValue("AM{$flag}", "=SUM(AM{$flageas}:AM".($flag-1).")")->getStyle("AM{$flag}")->getNumberFormat()->setFormatCode('#0_-');
				$this->excelfile->getActiveSheet()->getStyle('AQ'.$flag)->applyFromArray($styleArray);
				$this->cellStyle("AQ".$flag, "FFFFFF", "000000", FALSE, 10, "Franklin Gothic Book");
				$proveedor[$key]->estatus->setCellValue("AQ{$flag}", "=SUM(AQ{$flageas}:AQ".($flag-1).")")->getStyle("AQ{$flag}")->getNumberFormat()->setFormatCode('#0_-');
				$this->excelfile->getActiveSheet()->getStyle('AU'.$flag)->applyFromArray($styleArray);
				$this->cellStyle("AU".$flag, "FFFFFF", "000000", FALSE, 10, "Franklin Gothic Book");
				$proveedor[$key]->estatus->setCellValue("AU{$flag}", "=SUM(AU{$flageas}:AU".($flag-1).")")->getStyle("AU{$flag}")->getNumberFormat()->setFormatCode('#0_-');
				$this->excelfile->getActiveSheet()->getStyle('AY'.$flag)->applyFromArray($styleArray);
				$this->cellStyle("AY".$flag, "FFFFFF", "000000", FALSE, 10, "Franklin Gothic Book");
				$proveedor[$key]->estatus->setCellValue("AY{$flag}", "=SUM(AY{$flageas}:AY".($flag-1).")")->getStyle("AY{$flag}")->getNumberFormat()->setFormatCode('#0_-');

				$this->excelfile->getActiveSheet()->getStyle('BC'.$flag.':BM'.$flag)->applyFromArray($styleArray);
				$this->cellStyle("BC".$flag, "FFFFFF", "000000", FALSE, 10, "Franklin Gothic Book");
				$proveedor[$key]->estatus->setCellValue("BC{$flag}", "=SUM(BC{$flageas}:BC".($flag-1).")")->getStyle("BC{$flag}")->getNumberFormat()->setFormatCode("_(\"$\"* #,##0.00_);_(\"$\"* \(#,##0.00\);_(\"$\"* \"-\"??_);_(@_)");
				$this->cellStyle("BD".$flag, "FFFFFF", "000000", FALSE, 10, "Franklin Gothic Book");
				$proveedor[$key]->estatus->setCellValue("BD{$flag}", "=SUM(BD{$flageas}:BD".($flag-1).")")->getStyle("BD{$flag}")->getNumberFormat()->setFormatCode("_(\"$\"* #,##0.00_);_(\"$\"* \(#,##0.00\);_(\"$\"* \"-\"??_);_(@_)");
				$this->cellStyle("BE".$flag, "FFFFFF", "000000", FALSE, 10, "Franklin Gothic Book");
				$proveedor[$key]->estatus->setCellValue("BE{$flag}", "=SUM(BE{$flageas}:BE".($flag-1).")")->getStyle("BE{$flag}")->getNumberFormat()->setFormatCode("_(\"$\"* #,##0.00_);_(\"$\"* \(#,##0.00\);_(\"$\"* \"-\"??_);_(@_)");
				$this->cellStyle("BF".$flag, "FFFFFF", "000000", FALSE, 10, "Franklin Gothic Book");
				$proveedor[$key]->estatus->setCellValue("BF{$flag}", "=SUM(BF{$flageas}:BF".($flag-1).")")->getStyle("BF{$flag}")->getNumberFormat()->setFormatCode("_(\"$\"* #,##0.00_);_(\"$\"* \(#,##0.00\);_(\"$\"* \"-\"??_);_(@_)");
				$this->cellStyle("BG".$flag, "FFFFFF", "000000", FALSE, 10, "Franklin Gothic Book");
				$proveedor[$key]->estatus->setCellValue("BG{$flag}", "=SUM(BG{$flageas}:BG".($flag-1).")")->getStyle("BG{$flag}")->getNumberFormat()->setFormatCode("_(\"$\"* #,##0.00_);_(\"$\"* \(#,##0.00\);_(\"$\"* \"-\"??_);_(@_)");
				$this->cellStyle("BH".$flag, "FFFFFF", "000000", FALSE, 10, "Franklin Gothic Book");
				$proveedor[$key]->estatus->setCellValue("BH{$flag}", "=SUM(BH{$flageas}:BH".($flag-1).")")->getStyle("BH{$flag}")->getNumberFormat()->setFormatCode("_(\"$\"* #,##0.00_);_(\"$\"* \(#,##0.00\);_(\"$\"* \"-\"??_);_(@_)");
				$this->cellStyle("BI".$flag, "FFFFFF", "000000", FALSE, 10, "Franklin Gothic Book");
				$proveedor[$key]->estatus->setCellValue("BI{$flag}", "=SUM(BI{$flageas}:BI".($flag-1).")")->getStyle("BI{$flag}")->getNumberFormat()->setFormatCode("_(\"$\"* #,##0.00_);_(\"$\"* \(#,##0.00\);_(\"$\"* \"-\"??_);_(@_)");
				$this->cellStyle("BJ".$flag, "FFFFFF", "000000", FALSE, 10, "Franklin Gothic Book");
				$proveedor[$key]->estatus->setCellValue("BJ{$flag}", "=SUM(BJ{$flageas}:BJ".($flag-1).")")->getStyle("BJ{$flag}")->getNumberFormat()->setFormatCode("_(\"$\"* #,##0.00_);_(\"$\"* \(#,##0.00\);_(\"$\"* \"-\"??_);_(@_)");
				$this->cellStyle("BK".$flag, "FFFFFF", "000000", FALSE, 10, "Franklin Gothic Book");
				$proveedor[$key]->estatus->setCellValue("BK{$flag}", "=SUM(BK{$flageas}:BK".($flag-1).")")->getStyle("BK{$flag}")->getNumberFormat()->setFormatCode("_(\"$\"* #,##0.00_);_(\"$\"* \(#,##0.00\);_(\"$\"* \"-\"??_);_(@_)");
				$this->cellStyle("BL".$flag, "FFFFFF", "000000", FALSE, 10, "Franklin Gothic Book");
				$proveedor[$key]->estatus->setCellValue("BL{$flag}", "=SUM(BL{$flageas}:BL".($flag-1).")")->getStyle("BL{$flag}")->getNumberFormat()->setFormatCode("_(\"$\"* #,##0.00_);_(\"$\"* \(#,##0.00\);_(\"$\"* \"-\"??_);_(@_)");
				$this->cellStyle("BM".$flag, "FFFFFF", "000000", FALSE, 10, "Franklin Gothic Book");
				$proveedor[$key]->estatus->setCellValue("BM{$flag}", "=SUM(BM{$flageas}:BM".($flag-1).")")->getStyle("BM{$flag}")->getNumberFormat()->setFormatCode("_(\"$\"* #,##0.00_);_(\"$\"* \(#,##0.00\);_(\"$\"* \"-\"??_);_(@_)");
				$totis = $flag;
				$flag+=5;


				$this->excelfile->getActiveSheet()->getStyle('C'.$flag.':D'.$flag)->applyFromArray($styleArray);
				$va->alias = "PEDIDO LUNES";
				$this->cellStyle("C".$flag, "C00000", "000000", TRUE, 12, "Franklin Gothic Book");
				$proveedor[$key]->estatus->setCellValue("C".$flag, "CEDIS/SUPER");
				$proveedor[$key]->estatus->setCellValue("D{$flag}", "=BC{$totis}")->getStyle("D{$flag}")->getNumberFormat()->setFormatCode("_(\"$\"* #,##0.00_);_(\"$\"* \(#,##0.00\);_(\"$\"* \"-\"??_);_(@_)");
				$ced=$ced."D{$flag}+";
				$flag++;
				$this->excelfile->getActiveSheet()->getStyle('C'.$flag.':D'.$flag)->applyFromArray($styleArray);
				$proveedor[$key]->estatus->setCellValue("C".$flag, "CD INDUSTRIAL");
				$this->cellStyle("C".$flag, "FF0066", "000000", TRUE, 12, "Franklin Gothic Book");
				$proveedor[$key]->estatus->setCellValue("D{$flag}", "=BD{$totis}")->getStyle("D{$flag}")->getNumberFormat()->setFormatCode("_(\"$\"* #,##0.00_);_(\"$\"* \(#,##0.00\);_(\"$\"* \"-\"??_);_(@_)");
				$sup=$sup."D{$flag}+";
				$flag++;
				$this->excelfile->getActiveSheet()->getStyle('C'.$flag.':D'.$flag)->applyFromArray($styleArray);
				$this->cellStyle("C".$flag, "01B0F0", "000000", TRUE, 12, "Franklin Gothic Book");
				$proveedor[$key]->estatus->setCellValue("C".$flag, "ABARROTES");
				$proveedor[$key]->estatus->setCellValue("D{$flag}", "=BE{$totis}")->getStyle("D{$flag}")->getNumberFormat()->setFormatCode("_(\"$\"* #,##0.00_);_(\"$\"* \(#,##0.00\);_(\"$\"* \"-\"??_);_(@_)");
				$aba=$aba."D{$flag}+";
				$flag++;
				$this->excelfile->getActiveSheet()->getStyle('C'.$flag.':D'.$flag)->applyFromArray($styleArray);
				$this->cellStyle("C".$flag, "FF0000", "000000", TRUE, 12, "Franklin Gothic Book");
				$proveedor[$key]->estatus->setCellValue("C".$flag, "PEDREGAL");
				$proveedor[$key]->estatus->setCellValue("D{$flag}", "=BF{$totis}")->getStyle("D{$flag}")->getNumberFormat()->setFormatCode("_(\"$\"* #,##0.00_);_(\"$\"* \(#,##0.00\);_(\"$\"* \"-\"??_);_(@_)");
				$ped=$ped."D{$flag}+";
				$flag++;
				$this->excelfile->getActiveSheet()->getStyle('C'.$flag.':D'.$flag)->applyFromArray($styleArray);
				$this->cellStyle("C".$flag, "E26C0B", "000000", TRUE, 12, "Franklin Gothic Book");
				$proveedor[$key]->estatus->setCellValue("C".$flag, "TIENDA");
				$proveedor[$key]->estatus->setCellValue("D{$flag}", "=BG{$totis}")->getStyle("D{$flag}")->getNumberFormat()->setFormatCode("_(\"$\"* #,##0.00_);_(\"$\"* \(#,##0.00\);_(\"$\"* \"-\"??_);_(@_)");
				$tie=$tie."D{$flag}+";
				$flag++;
				$this->excelfile->getActiveSheet()->getStyle('C'.$flag.':D'.$flag)->applyFromArray($styleArray);
				$this->cellStyle("C".$flag, "C5C5C5", "000000", TRUE, 12, "Franklin Gothic Book");
				$proveedor[$key]->estatus->setCellValue("C".$flag, "ULTRAMARINOS");
				$proveedor[$key]->estatus->setCellValue("D{$flag}", "=BH{$totis}")->getStyle("D{$flag}")->getNumberFormat()->setFormatCode("_(\"$\"* #,##0.00_);_(\"$\"* \(#,##0.00\);_(\"$\"* \"-\"??_);_(@_)");
				$ult=$ult."D{$flag}+";
				$flag++;
				$this->excelfile->getActiveSheet()->getStyle('C'.$flag.':D'.$flag)->applyFromArray($styleArray);
				$this->cellStyle("C".$flag, "92D051", "000000", TRUE, 12, "Franklin Gothic Book");
				$proveedor[$key]->estatus->setCellValue("C".$flag, "TRINCHERAS");
				$proveedor[$key]->estatus->setCellValue("D{$flag}", "=BI{$totis}")->getStyle("D{$flag}")->getNumberFormat()->setFormatCode("_(\"$\"* #,##0.00_);_(\"$\"* \(#,##0.00\);_(\"$\"* \"-\"??_);_(@_)");
				$tri=$tri."D{$flag}+";
				$flag++;
				$this->excelfile->getActiveSheet()->getStyle('C'.$flag.':D'.$flag)->applyFromArray($styleArray);
				$this->cellStyle("C".$flag, "B1A0C7", "000000", TRUE, 12, "Franklin Gothic Book");
				$proveedor[$key]->estatus->setCellValue("C".$flag, "AZT MERCADO");
				$proveedor[$key]->estatus->setCellValue("D{$flag}", "=BJ{$totis}")->getStyle("D{$flag}")->getNumberFormat()->setFormatCode("_(\"$\"* #,##0.00_);_(\"$\"* \(#,##0.00\);_(\"$\"* \"-\"??_);_(@_)");
				$mer=$mer."D{$flag}+";
				$flag++;
				$this->excelfile->getActiveSheet()->getStyle('C'.$flag.':D'.$flag)->applyFromArray($styleArray);
				$this->cellStyle("C".$flag, "DA9694", "000000", TRUE, 12, "Franklin Gothic Book");
				$proveedor[$key]->estatus->setCellValue("C".$flag, "TENENCIA");
				$proveedor[$key]->estatus->setCellValue("D{$flag}", "=BK{$totis}")->getStyle("D{$flag}")->getNumberFormat()->setFormatCode("_(\"$\"* #,##0.00_);_(\"$\"* \(#,##0.00\);_(\"$\"* \"-\"??_);_(@_)");
				$ten=$ten."D{$flag}+";
				$flag++;
				$this->excelfile->getActiveSheet()->getStyle('C'.$flag.':D'.$flag)->applyFromArray($styleArray);
				$this->cellStyle("C".$flag, "4CACC6", "000000", TRUE, 12, "Franklin Gothic Book");
				$proveedor[$key]->estatus->setCellValue("C".$flag, "TIJERAS");
				$proveedor[$key]->estatus->setCellValue("D{$flag}", "=BL{$totis}")->getStyle("D{$flag}")->getNumberFormat()->setFormatCode("_(\"$\"* #,##0.00_);_(\"$\"* \(#,##0.00\);_(\"$\"* \"-\"??_);_(@_)");
				$tij=$tij."D{$flag}+";
				$flag++;
				$this->excelfile->getActiveSheet()->getStyle('C'.$flag.':D'.$flag)->applyFromArray($styleArray);
				$this->cellStyle("C".$flag, "000000", "FFFFFF", TRUE, 12, "Franklin Gothic Book");
				$proveedor[$key]->estatus->setCellValue("C".$flag, "TOTAL");
				$proveedor[$key]->estatus->setCellValue("D{$flag}", "=SUM(D".($totis+5).":D".($flag-1).")")->getStyle("D{$flag}")->getNumberFormat()->setFormatCode("_(\"$\"* #,##0.00_);_(\"$\"* \(#,##0.00\);_(\"$\"* \"-\"??_);_(@_)");
			$flag1 += 5;

			$flag += 5;
			$bande+=5;
			}
		}

		

		

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

	public function asociar_codigos(){
		$fecha = new DateTime(date('Y-m-d H:i:s'));
		$filen = "Codigos Proveedores Lunes";
		$config['upload_path']          = './assets/uploads/cotizaciones/';
        $config['allowed_types']        = 'xlsx|xls';
        $config['max_size']             = 1000;
        $config['max_width']            = 10204;
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
					$id_catalogo = $sheet->getCell('C'.$i)->getValue();
					$descripcion = $sheet->getCell('B'.$i)->getValue();
					$new_codigo=[
							"id_catalogo" => $id_catalogo,
							"descripcion" => $descripcion,
							"id_producto" => $productos->codigo,
							"estatus"	  => 1
						];
					$catal = $this->cata_mdl->get(NULL,["id_producto"=>$productos->codigo])[0];
					if (sizeof($catal)>0) {
						$data['existencia']=$this->cata_mdl->update($new_codigo, ['id_producto' => $productos->codigo]);
					}else{
						$data['existencia']=$this->cata_mdl->insert($new_codigo);
					}
				}
			}
		}
		if (!isset($new_codigo)) {
			$mensaje=[	"id"	=>	'Error',
						"desc"	=>	'El Archivo esta sin codigos',
						"type"	=>	'error'];
		}else{
			if (sizeof($new_codigo) > 0) {
				$cambios=[
						"id_usuario"		=>	$this->session->userdata('id_usuario'),
						"fecha_cambio"		=>	date("Y-m-d H:i:s"),
						"antes"			=>	"El usuario sube Codigos Proveedor lunes ",
						"despues"			=>	"assets/uploads/cotizaciones/Codigos Proveedor Lunes.xlsx",
						"accion"			=>	"Sube Archivo"
					];
				$data['cambios']=$this->cambio_md->insert($cambios);
				$mensaje=[	"id"	=>	'Éxito',
							"desc"	=>	'Codigos proveedor cargadas correctamente en el Sistema',
							"type"	=>	'success'];
			}else{
				$mensaje=[	"id"	=>	'Error',
							"desc"	=>	'Codigos proveedor no se cargaron al Sistema',
							"type"	=>	'error'];
			}
		}
		$this->jsonResponse($mensaje);
	}

	public function facturas(){
		$data['links'] = [
			'/assets/css/plugins/dataTables/dataTables.bootstrap',
			'/assets/css/plugins/dataTables/dataTables.responsive',
			'/assets/css/plugins/dataTables/dataTables.tableTools.min',
			'/assets/css/plugins/dataTables/buttons.dataTables.min',
		];

		$data['scripts'] = [
			'/scripts/pedidolunes',
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
		$data["sucursales"] = $this->suc_md->get(NULL,["estatus<>"=>0]);
		$data["facturas"] = $this->fac_mdl->get(NULL);
		$data["factura"] = $this->fac_mdl->getFactos(NULL,"566874805");
		//$this->jsonResponse($data["facturas"]);
		$this->estructura("Lunes/facturas", $data);
	}
	public function verpedido($id_proveedor){
		$data["title"]="PEDIDOS A PROVEEDOR";
		$user = $this->session->userdata();
		$data["finales"] = $this->fin_mdl->getPedidos(NULL,$id_proveedor);
		$data["view"] = $this->load->view("Lunes/verpedido", $data, TRUE);
		$data["button"]="";
		$this->jsonResponse($data);
	}


	public function sube_pedido(){
		$fecha = new DateTime(date('Y-m-d H:i:s'));
		$filen = "Pedido".date("dmyHis");
		$config['upload_path']          = './assets/uploads/lunes/';
        $config['allowed_types']        = 'xlsx|xls';
        $config['max_size']             = 1000;
        $config['max_width']            = 10204;
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
		$mensaje=[	"id"	=>	'Error',
					"desc"	=>	'No se pudo cargar el archivo excel',
					"type"	=>	'error'];

		for ($i=1; $i<=$num_rows; $i++) {
			if(strlen($sheet->getCell('A'.$i)->getValue()) > 0){
				$productos = $this->prolu_md->get("codigo",['codigo'=> htmlspecialchars($sheet->getCell('A'.$i)->getValue(), ENT_QUOTES, 'UTF-8')])[0];
				if (sizeof($productos) > 0) {
					$new_pedido=[
							"costo" 		=> $this->getOldVal($sheet,$i,'C'),
							"cedis" 		=> $this->getOldVal($sheet,$i,'D'),
							"abarrotes"		=> $this->getOldVal($sheet,$i,'E'),
							"villas"		=> $this->getOldVal($sheet,$i,'F'),
							"tienda"		=> $this->getOldVal($sheet,$i,'G'),
							"ultra"			=> $this->getOldVal($sheet,$i,'H'),
							"trincheras"	=> $this->getOldVal($sheet,$i,'I'),
							"mercado"		=> $this->getOldVal($sheet,$i,'J'),
							"tenencia"		=> $this->getOldVal($sheet,$i,'K'),
							"tijeras"		=> $this->getOldVal($sheet,$i,'L'),
							"id_producto" 	=> $productos->codigo,
						];
					$codiga = $this->fin_mdl->get(NULL,['id_producto'=> $productos->codigo,"WEEKOFYEAR(fecha_registro)"=>$this->weekNumber()])[0];
					if (sizeof($codiga)>0) {
						$data['existencia']=$this->fin_mdl->update($new_pedido, $codiga->id_final);
					}else{
						$data['existencia']=$this->fin_mdl->insert($new_pedido);
					}
				}
			}
		}
		if (!isset($new_pedido)) {
			$mensaje=[	"id"	=>	'Error',
						"desc"	=>	'El Archivo esta sin datos',
						"type"	=>	'error'];
		}else{
			if (sizeof($new_pedido) > 0) {
				$cambios=[
						"id_usuario"		=>	$this->session->userdata('id_usuario'),
						"fecha_cambio"		=>	date("Y-m-d H:i:s"),
						"antes"				=>	"El usuario sube Pedidos Finales lunes ",
						"despues"			=>	"assets/uploads/lunes/".$filen.".xlsx",
						"accion"			=>	"Sube Archivo"
					];
				$data['cambios']=$this->cambio_md->insert($cambios);
				$mensaje=[	"id"	=>	'Éxito',
							"desc"	=>	'Pedidos Finales cargadas correctamente en el Sistema',
							"type"	=>	'success'];
			}
		}
		$this->jsonResponse($mensaje);
	}

	public function getOldVal($sheets,$i,$le){
		$cellB = $sheets->getCell($le.$i)->getValue();
		if(strstr($cellB,'=')==true){
		    $cellB = $sheets->getCell($le.$i)->getOldCalculatedValue();
		}
		return $cellB;
	}

	public function endPedidos($ides){
		$this->db->query('DELETE FROM finalunes WHERE id_producto IN(select p.codigo FROM pro_lunes p WHERE p.id_proveedor = '.$ides.') AND WEEKOFYEAR(fecha_registro) = WEEKOFYEAR(CURDATE())');
		
		$mensaje = [
				"id" 	=> 'Éxito',
				"desc"	=> 'Pedidos eliminadas correctamente',
				"type"	=> 'success'
			];
			$data["proveedor"] = $this->prove_md->get(NULL,["id_proveedor" => $ides])[0];
			$cambios = [
				"id_usuario" => $this->session->userdata('id_usuario'),
				"fecha_cambio" => date('Y-m-d H:i:s'),
				"accion" => "Elimina pedidos lunes de ".$data["proveedor"]->nombre,
				"antes" => "Eliminado",
				"despues" => "Eliminado"
			];
			$data['cambios'] = $this->cambio_md->insert($cambios);
		$this->jsonResponse($mensaje);
	}

	public function sube_factura($tienda,$proveedor){
		$fecha = new DateTime(date('Y-m-d H:i:s'));
		$filen = "FacturaLunes".date("dmyHis");
		$config['upload_path']          = './assets/uploads/lunes/';
        $config['allowed_types']        = 'xlsx|xls';
        $config['max_size']             = 1000;
        $config['max_width']            = 10204;
        $config['max_height']           = 7068;
        $this->load->library('upload', $config);
        $this->upload->initialize($config);
		$this->load->library("excelfile");
		ini_set("memory_limit", -1);
		$file = $_FILES["file_otizaciones2"]["tmp_name"];
		$filename=$_FILES['file_otizaciones2']['name'];
		$sheet = PHPExcel_IOFactory::load($file);
		$objExcel = PHPExcel_IOFactory::load($file);
		$sheet = $objExcel->getSheet(0);
		$num_rows = $sheet->getHighestDataRow();
		$mensaje=[	"id"	=>	'Error',
					"desc"	=>	'No se pudo cargar el archivo excel',
					"type"	=>	'error'];
		$nombreFolio = $_FILES["file_otizaciones2"]['name'];
		$this->upload->do_upload('file_otizaciones2',substr($nombreFolio,0,strlen($nombreFolio)-5).''.date('dmYHis'));
		$this->load->library("excelfile");
		if ($sheet->getCell('E1')->getValue() === "FOLIO") {
			$folio = htmlspecialchars($sheet->getCell('E2')->getValue(), ENT_QUOTES, 'UTF-8');	
			$fecha = $sheet->getCell('F2')->getValue();
			$no = 2;
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
		$this->db->query("delete from factura_lunes where folio = '".$folio."' AND id_proveedor = ".$proveedor." AND id_tienda = ".$tienda."");
		for ($i=$no; $i<=$num_rows; $i++) {
			$codigo = $sheet->getCell('A'.$i)->getValue();
			$cantidad = $this->getOldVal($sheet,$i,"C");
			$precio = $this->getOldVal($sheet,$i,"D");
			$desc = $this->getOldVal($sheet,$i,"B");
			$descripcion = $this->cata_mdl->get(NULL,["id_catalogo"=>$codigo])[0];
			
			
			if ($codigo <> NULL || $codigo <> "") {
				if (sizeof($descripcion) > 0) {
					$new_producto=[
						"folio" => $folio,
						"id_proveedor" => $proveedor,
						"precio" => $precio,
						"codigo" => $descripcion->id_catalogo,
						"descripcion" => $descripcion->descripcion,
						"fecha_factura" => $fecha,
						"cantidad" => $cantidad,
						"id_tienda"=> $tienda,
						"id_producto" => $descripcion->id_producto
					];

					$data['id_prodcaja']=$this->fac_mdl->insert($new_producto);
				}else{
					$new_invoice=[
						"id_catalogo" => $codigo,
						"descripcion" => $desc,
					];
					$data['id_invoice']=$this->cata_mdl->insert($new_invoice);
					$new_producto=[
						"folio" => $folio,
						"id_proveedor" => $proveedor,
						"precio" => $precio,
						"codigo" => $codigo,
						"descripcion" => $desc,
						"fecha_factura" => $fecha,
						"cantidad" => $cantidad,
						"id_tienda"=> $tienda
					];
					$data['id_prodcaja']=$this->fac_mdl->insert($new_producto);
				}
			}
		}
		if (!isset($new_producto)) {
			$mensaje=[	"id"	=>	'Error',
						"desc"	=>	'El Archivo esta sin datos',
						"type"	=>	'error'];
		}else{
			if (sizeof($new_producto) > 0) {
				$cambios=[
						"id_usuario"		=>	$this->session->userdata('id_usuario'),
						"fecha_cambio"		=>	date("Y-m-d H:i:s"),
						"antes"				=>	"El usuario sube Factura lunes ",
						"despues"			=>	"assets/uploads/lunes/".$filen.".xlsx",
						"accion"			=>	"Sube Archivo"
					];
				$data['cambios']=$this->cambio_md->insert($cambios);
				$mensaje=[	"id"	=>	'Éxito',
							"desc"	=>	'Factura cargada correctamente en el Sistema',
							"type"	=>	'success'];
			}
		}
		$this->jsonResponse($mensaje);
	}

	public function getSemFacts($prove){
		$facts = $this->fac_mdl->getSemFacts(NULL,$prove);
		$this->jsonResponse($facts);
	}

	public function getFactClic(){
		$busca = $this->input->post("values");
		$values = $busca;
		$value = json_decode($values); 
		switch($value->cual){
				  case 57: $day = "abarrotes";break;
				  case 87: $day = "cedis";break;
				  case 90: $day = "villas";break;
				  case 58: $day = "tienda";break;
				  case 59: $day = "ultra";break;
				  case 60: $day = "trincheras";break;
				  case 61: $day = "mercado";break;
				  case 62: $day = "tenencia";break;
				  case 63: $day = "tijeras";break;
				}
		$facts = $this->fac_mdl->getFactClic(NULL,$busca,$day);
		$this->jsonResponse($facts);	
	}

	public function catalogoAsocia($id_catalogo){
		$data["title"]="Asociar Productos Proveedor";
		$data["catalogo"] = $this->cata_mdl->get(NULL,["id_catalogo"=>$id_catalogo])[0];
		$data["productos"] = $this->prolu_md->get(NULL,["estatus <>"=>"0"]);
		$data["button"]="<button class='btn btn-success new_catalogo' type='button'>
							<span class='bold'><i class='fa fa-floppy-o'></i></span> &nbsp;Asociar Producto
						</button>";
		$data["view"] = $this->load->view("Lunes/asociando", $data,TRUE);
		$this->jsonResponse($data);
	}

	public function asociamesta(){
		$prod = $this->input->post('id_producto');
		$id_catalogo = $this->input->post('idca2');
		$this->cata_mdl->update(["id_producto"=>$prod],["id_catalogo"=>$id_catalogo]);
		$this->fac_mdl->update(["id_producto"=>$prod],["codigo"=>$id_catalogo]);
		$mensaje = [
			"id" 	=> 'Éxito',
			"desc"	=> 'Producto asociado correctamente',
			"type"	=> 'success'
		];
		$this->jsonResponse($mensaje);
	}

	public function delFactura(){
		$busca = $this->input->post("values");
		$values = json_decode($busca);
		$this->db->query("delete from factura_lunes where folio = '".$values->folio."' AND id_proveedor = ".$values->proveedor." AND id_tienda = ".$values->tienda."");
		$mensaje = [
			"id" 	=> 'Éxito',
			"desc"	=> 'Factura Elimianda',
			"type"	=> 'success'
		];
		$this->jsonResponse($mensaje);
	}

	public function excelFacturas($proveedor){
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
		$provfact = $this->fac_mdl->profactu(NULL,$proveedor);
		$provnombre = $this->prove_md->get("alias",["id_proveedor"=>$proveedor])[0];
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
				switch ($valorce->id_tienda) {
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
						break;
				}

				$facturas = $this->fac_mdl->getDetails(NULL,json_encode($valorce),$which);
				$hoja->mergeCells('A'.$flag.':L'.$flag.'');
				$this->cellStyle("A".$flag, "".substr($facturas[0]->color,1,6), "000000", TRUE, 24, "Berlin Sans FB Demi");
				$hoja->setCellValue("A".$flag, $facturas[0]->tienda." GRUPO AZTECA, S.A DE C.V")->getColumnDimension('A')->setWidth(60);
				$this->excelfile->getActiveSheet()->getStyle('A'.$flag.':L'.$flag.'')->applyFromArray($styleArray);
				$flag++;

				$hoja->mergeCells('A'.$flag.':B'.($flag+1));
				$this->cellStyle("A".$flag, "FFFFFF", "000000", FALSE, 18, "Arial Narrow");
				$hoja->setCellValue("A".$flag, $facturas[0]->prove);
				$this->excelfile->getActiveSheet()->getStyle('A'.$flag.':B'.($flag+1))->applyFromArray($styleArray);
				$hoja->mergeCells('C'.$flag.':E'.$flag.'');
				$this->cellStyle("C".$flag, "FFFFFF", "000000", FALSE, 14, "Arial Narrow");
				$hoja->setCellValue("C".$flag, "Fecha de Reporte");
				$this->excelfile->getActiveSheet()->getStyle('C'.$flag.':E'.$flag)->applyFromArray($styleright);
				$hoja->mergeCells('F'.$flag.':L'.$flag.'');
				$this->cellStyle("F".$flag, "FFFFFF", "000000", FALSE, 14, "Arial Narrow");
				$hoja->setCellValue('F'.$flag,$facturas[0]->fecha_registro);  
				$this->excelfile->getActiveSheet()->getStyle('F'.$flag.':L'.$flag.'')->applyFromArray($styleleft);
				$flag++;

				$hoja->mergeCells('C'.$flag.':E'.$flag.'');
				$this->cellStyle("C".$flag, "FFFFFF", "000000", FALSE, 14, "Arial Narrow");
				$hoja->setCellValue("C".$flag, "Fecha en Factura");
				$this->excelfile->getActiveSheet()->getStyle('C'.$flag.':E'.$flag.'')->applyFromArray($styleright);
				$hoja->mergeCells('F'.$flag.':L'.$flag.'');
				$this->cellStyle("F".$flag, "FFFFFF", "000000", FALSE, 14, "Arial Narrow");
				$hoja->setCellValue("F".$flag, $facturas[0]->fecha_factura);
				$this->excelfile->getActiveSheet()->getStyle('F'.$flag.':L'.$flag.'')->applyFromArray($styleleft);
				$flag++;

				$hoja->mergeCells('A'.$flag.':A'.($flag+1));
				$this->cellStyle("A".$flag, "FFFFFF", "000000", FALSE, 14, "Arial Narrow");
				$hoja->setCellValue("A".$flag, "DESCRIPCIÓN");
				$this->excelfile->getActiveSheet()->getStyle('A'.$flag.':A'.($flag+1))->applyFromArray($styleArray);
				$hoja->mergeCells('B'.$flag.':B'.($flag+1));
				$this->cellStyle("B".$flag, "FFFFFF", "000000", FALSE, 9, "Arial Narrow");
				$hoja->setCellValue("B".$flag, "PROMO")->getColumnDimension('B')->setWidth(16);
				$this->excelfile->getActiveSheet()->getStyle('B'.$flag.':B'.($flag+1))->applyFromArray($styleArray);
				$this->cellStyle("C".$flag, "FFFFFF", "000000", FALSE, 11, "Arial Narrow");
				$hoja->setCellValue("C".$flag, "PRECIO EN")->getColumnDimension('C')->setWidth(16);
				$this->excelfile->getActiveSheet()->getStyle('C'.$flag)->applyFromArray($stylebottom);
				$this->cellStyle("D".$flag, "FFFFFF", "000000", FALSE, 11, "Arial Narrow");
				$hoja->setCellValue("D".$flag, "CANT")->getColumnDimension('D')->setWidth(10);
				$this->excelfile->getActiveSheet()->getStyle('D'.$flag)->applyFromArray($stylebottom);
				$this->cellStyle("E".$flag, "FFFFFF", "000000", FALSE, 8, "Arial Narrow");
				$hoja->setCellValue("E".$flag, "CANT")->getColumnDimension('E')->setWidth(10);
				$this->excelfile->getActiveSheet()->getStyle('E'.$flag)->applyFromArray($stylebottom);

				$this->cellStyle("F".$flag, "FFFFFF", "000000", FALSE, 8, "Arial Narrow");
				$hoja->setCellValue("F".$flag, "SUBTOTAL")->getColumnDimension('F')->setWidth(10);
				$this->excelfile->getActiveSheet()->getStyle('F'.$flag)->applyFromArray($stylebottom);
				$hoja->mergeCells('G'.$flag.':G'.($flag+1));
				$this->cellStyle("G".$flag, "FFFFFF", "000000", FALSE, 14, "Arial Narrow");
				$hoja->setCellValue("G".$flag, "IVA")->getColumnDimension('G')->setWidth(13);
				$this->excelfile->getActiveSheet()->getStyle('G'.$flag.':G'.($flag+1))->applyFromArray($styleArray);
				$hoja->mergeCells('H'.$flag.':H'.($flag+1));
				$this->cellStyle("H".$flag, "FFFFFF", "000000", FALSE, 14, "Arial Narrow");
				$hoja->setCellValue("H".$flag, "IEPS")->getColumnDimension('H')->setWidth(13);
				$this->excelfile->getActiveSheet()->getStyle('H'.$flag.':H'.($flag+1))->applyFromArray($styleArray);

				$this->cellStyle("I".$flag, "FFFFFF", "000000", FALSE, 10, "Arial Narrow");
				$hoja->setCellValue("I".$flag, "PREC NETO")->getColumnDimension('I')->setWidth(12);
				$this->excelfile->getActiveSheet()->getStyle('I'.$flag)->applyFromArray($stylebottom);
				
				$hoja->mergeCells('J'.$flag.':J'.($flag+1));
				$this->cellStyle("J".$flag, "FFFFFF", "000000", FALSE, 14, "Arial Narrow");
				$hoja->setCellValue("J".$flag, "DIF.")->getColumnDimension('J')->setWidth(13);
				$this->excelfile->getActiveSheet()->getStyle('J'.$flag.':J'.($flag+1))->applyFromArray($styleArray);
				$this->cellStyle("K".$flag, "FFFFFF", "000000", FALSE, 14, "Arial Narrow");
				$hoja->setCellValue("K".$flag, "NOTA")->getColumnDimension('K')->setWidth(16);
				$this->excelfile->getActiveSheet()->getStyle('L'.$flag)->applyFromArray($stylebottom);
				$this->cellStyle("L".$flag, "FFFFFF", "000000", FALSE, 14, "Arial Narrow");
				$hoja->setCellValue("L".$flag, "TOTAL")->getColumnDimension('L')->setWidth(16);
				$this->excelfile->getActiveSheet()->getStyle('L'.$flag)->applyFromArray($stylebottom);
				
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
				$this->cellStyle("I".$flag, "FFFFFF", "000000", FALSE, 10, "Arial Narrow");
				$hoja->setCellValue("I".$flag, "FACTURA");
				$this->excelfile->getActiveSheet()->getStyle('I'.$flag)->applyFromArray($styletop);
				$this->cellStyle("K".$flag, "FFFFFF", "000000", FALSE, 14, "Arial Narrow");
				$hoja->setCellValue("K".$flag, "CREDITO");
				$this->excelfile->getActiveSheet()->getStyle('K'.$flag)->applyFromArray($styletop);
				$this->cellStyle("L".$flag, "FFFFFF", "000000", FALSE, 14, "Arial Narrow");
				$hoja->setCellValue("L".$flag, "A PAGAR");
				$this->excelfile->getActiveSheet()->getStyle('L'.$flag)->applyFromArray($styletop);
				$this->cellStyle("F".$flag, "FFFFFF", "000000", FALSE, 8, "Arial Narrow");
				$hoja->setCellValue("F".$flag, "FACTURA");
				$this->excelfile->getActiveSheet()->getStyle('F'.$flag)->applyFromArray($styletop);
				$flag++;
				$flag2 = $flag;

				if ($facturas) {
					foreach ($facturas as $key => $value) {
						$this->cellStyle("A".$flag.":L".$flag, "FFFFFF", "000000", FALSE, 14, "Arial Narrow");
						$this->excelfile->getActiveSheet()->getStyle('A'.$flag)->applyFromArray($stylebottom);
						$hoja->setCellValue("A".$flag, $value->descripcion);


						$this->excelfile->getActiveSheet()->getStyle('A'.$flag)->applyFromArray($styleArray);
						if($value->pprod === "" || $value->pprod === NULL){
							$hoja->setCellValue("A".$flag, $value->descripcion);
						}else{
							$hoja->setCellValue("A".$flag, $value->pprod);	
						}
						
						$this->excelfile->getActiveSheet()->getStyle('B'.$flag)->applyFromArray($styleArray);
						$hoja->setCellValue("B".$flag, "DIRECTO");
						$this->excelfile->getActiveSheet()->getStyle('C'.$flag)->applyFromArray($styleArray);
						$hoja->setCellValue("C".$flag, $value->costo)->getStyle("C{$flag}")->getNumberFormat()->setFormatCode("_(\"$\"* #,##0.00_);_(\"$\"* \(#,##0.00\);_(\"$\"* \"-\"??_);_(@_)");
						$this->excelfile->getActiveSheet()->getStyle('D'.$flag)->applyFromArray($styleArray);
						$hoja->setCellValue("D".$flag, $value->wey);
						$this->excelfile->getActiveSheet()->getStyle('E'.$flag)->applyFromArray($styleArray);
						$hoja->setCellValue("E".$flag, $value->cantidad);
						$this->excelfile->getActiveSheet()->getStyle('F'.$flag)->applyFromArray($styleArray);
						$hoja->setCellValue("F".$flag, $value->precio)->getStyle("F{$flag}")->getNumberFormat()->setFormatCode("_(\"$\"* #,##0.00_);_(\"$\"* \(#,##0.00\);_(\"$\"* \"-\"??_);_(@_)");
						$this->excelfile->getActiveSheet()->getStyle('G'.$flag)->applyFromArray($styleArray);
						$hoja->setCellValue("G".$flag, "=F{$flag}*0.16")->getStyle("G{$flag}")->getNumberFormat()->setFormatCode("_(\"$\"* #,##0.00_);_(\"$\"* \(#,##0.00\);_(\"$\"* \"-\"??_);_(@_)");
						$this->excelfile->getActiveSheet()->getStyle('H'.$flag)->applyFromArray($styleArray);
						if ($value->ieps === "0.00" || $value->ieps === 0 || $value->ieps === null) {
							$value->ieps = 0;
						}
						$hoja->setCellValue("H".$flag, "=F{$flag}*".$value->ieps."")->getStyle("H{$flag}")->getNumberFormat()->setFormatCode("_(\"$\"* #,##0.00_);_(\"$\"* \(#,##0.00\);_(\"$\"* \"-\"??_);_(@_)");
						$this->excelfile->getActiveSheet()->getStyle('I'.$flag)->applyFromArray($styleArray);
						$hoja->setCellValue("I".$flag, "=SUM(F{$flag}:H{$flag})")->getStyle("I{$flag}")->getNumberFormat()->setFormatCode("_(\"$\"* #,##0.00_);_(\"$\"* \(#,##0.00\);_(\"$\"* \"-\"??_);_(@_)");

						$this->excelfile->getActiveSheet()->getStyle('J'.$flag)->applyFromArray($styleArray);
						$hoja->setCellValue("J".$flag, "=I{$flag}-C{$flag}")->getStyle("J{$flag}")->getNumberFormat()->setFormatCode("_(\"$\"* #,##0.00_);_(\"$\"* \(#,##0.00\);_(\"$\"* \"-\"??_);_(@_)");
						$this->excelfile->getActiveSheet()->getStyle('K'.$flag)->applyFromArray($styleArray);
						$hoja->setCellValue("K".$flag, "=J{$flag}*E{$flag}")->getStyle("K{$flag}")->getNumberFormat()->setFormatCode("_(\"$\"* #,##0.00_);_(\"$\"* \(#,##0.00\);_(\"$\"* \"-\"??_);_(@_)");
						$this->excelfile->getActiveSheet()->getStyle('L'.$flag)->applyFromArray($styleArray);
						$hoja->setCellValue("L".$flag, "=C{$flag}*E{$flag}")->getStyle("L{$flag}")->getNumberFormat()->setFormatCode("_(\"$\"* #,##0.00_);_(\"$\"* \(#,##0.00\);_(\"$\"* \"-\"??_);_(@_)");
						$flag++;
					}
					$flag++;

					$this->cellStyle("A".$flag.":I".$flag, "FFFFFF", "000000", TRUE, 19, "Arial Narrow");
					$hoja->mergeCells('A'.$flag.":C".$flag);
					$this->excelfile->getActiveSheet()->getStyle('A'.$flag.":C".$flag)->applyFromArray($styleArrayHL);
					$hoja->mergeCells('D'.$flag.":H".$flag);
					$this->excelfile->getActiveSheet()->getStyle('D'.$flag.":H".$flag)->applyFromArray($styleArrayHL);
					$hoja->setCellValue("D".$flag, "FOLIO");

					$hoja->mergeCells('I'.$flag.':J'.$flag);
					$this->excelfile->getActiveSheet()->getStyle('I'.$flag.':J'.$flag)->applyFromArray($styleArrayHL);
					$this->cellStyle("I".$flag, "00FFFF", "000000", TRUE, 19, "Arial Narrow");
					$hoja->setCellValue("I".$flag, $facturas[0]->folio);
					$this->excelfile->getActiveSheet()->getStyle('K'.$flag)->applyFromArray($styleArrayHL);
					$hoja->setCellValue("K".$flag, "=SUM(K".$flag2.":K".($flag-1).")")->getStyle("K{$flag}")->getNumberFormat()->setFormatCode("_(\"$\"* #,##0.00_);_(\"$\"* \(#,##0.00\);_(\"$\"* \"-\"??_);_(@_)");
					$this->excelfile->getActiveSheet()->getStyle('L'.$flag)->applyFromArray($styleArrayHL);
					$hoja->setCellValue("L".$flag, "=SUM(L".$flag2.":L".($flag-1).")")->getStyle("L{$flag}")->getNumberFormat()->setFormatCode("_(\"$\"* #,##0.00_);_(\"$\"* \(#,##0.00\);_(\"$\"* \"-\"??_);_(@_)");
					$this->cellStyle("K".$flag, "FF0000", "000000", TRUE, 16, "Arial Narrow");
					$this->cellStyle("L".$flag, "FFFF00", "000000", TRUE, 16, "Arial Narrow");
					$flag++;

					$hoja->mergeCells('D'.$flag.":J".$flag);
					$hoja->mergeCells('K'.$flag.":L".$flag);
					$this->cellStyle("A".$flag.":L".$flag, "FFFFFF", "000000", TRUE, 22, "Arial Narrow");
					$this->cellStyle("A".$flag, "4f81bd", "000000", TRUE, 22, "Arial Narrow");
					$this->cellStyle("B".$flag.":C".$flag, "FF0000", "000000", TRUE, 22, "Arial Narrow");
					$this->excelfile->getActiveSheet()->getStyle('A'.$flag)->applyFromArray($styleArrayHL);
					$this->excelfile->getActiveSheet()->getStyle('B'.$flag)->applyFromArray($styleArrayHL);
					$hoja->setCellValue('B'.$flag, '=SUMIF(B'.$flag2.':B'.($flag-2).',"DEVUELTO",K'.$flag2.':K'.($flag-2).')')->getStyle("B{$flag}")->getNumberFormat()->setFormatCode("_(\"$\"* #,##0.00_);_(\"$\"* \(#,##0.00\);_(\"$\"* \"-\"??_);_(@_)");
					$this->cellStyle("D".$flag, "FFFFFF", "000000", TRUE, 16, "Arial Narrow");
					$this->excelfile->getActiveSheet()->getStyle('C'.$flag)->applyFromArray($styleArray);
					$hoja->setCellValue("D".$flag, "TOTAL DE LA FACTURA")->getStyle("D{$flag}")->getNumberFormat()->setFormatCode("_(\"$\"* #,##0.00_);_(\"$\"* \(#,##0.00\);_(\"$\"* \"-\"??_);_(@_)");
					$this->excelfile->getActiveSheet()->getStyle('C'.$flag)->applyFromArray($styleArrayHL);
					$hoja->setCellValue("C".$flag, "=K".($flag+1)."-B".$flag)->getStyle("C{$flag}")->getNumberFormat()->setFormatCode("_(\"$\"* #,##0.00_);_(\"$\"* \(#,##0.00\);_(\"$\"* \"-\"??_);_(@_)");
					$this->excelfile->getActiveSheet()->getStyle('K'.$flag)->applyFromArray($styleArray);
					$hoja->setCellValue("K".$flag, "=K".($flag-1)."+L".($flag-1))->getStyle("K{$flag}")->getNumberFormat()->setFormatCode("_(\"$\"* #,##0.00_);_(\"$\"* \(#,##0.00\);_(\"$\"* \"-\"??_);_(@_)");
					$this->excelfile->getActiveSheet()->getStyle('D'.$flag)->applyFromArray($styleArray);
					$this->excelfile->getActiveSheet()->getStyle('E'.$flag)->applyFromArray($styleArray);
					$this->excelfile->getActiveSheet()->getStyle('F'.$flag)->applyFromArray($styleArray);
					$this->excelfile->getActiveSheet()->getStyle('G'.$flag)->applyFromArray($styleArray);
					$this->excelfile->getActiveSheet()->getStyle('H'.$flag)->applyFromArray($styleArray);
					$this->excelfile->getActiveSheet()->getStyle('I'.$flag)->applyFromArray($styleArray);
					$this->excelfile->getActiveSheet()->getStyle('L'.$flag)->applyFromArray($styleArray);
					$this->excelfile->getActiveSheet()->getStyle('J'.$flag)->applyFromArray($styleArray);
					$flag++;

					$hoja->mergeCells('D'.$flag.":J".$flag);
					$hoja->mergeCells('K'.$flag.":L".$flag);
					$this->cellStyle("A".$flag.":L".$flag, "FFFFFF", "000000", TRUE, 22, "Arial Narrow");
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
					$this->cellStyle("D".$flag, "FFFFFF", "000000", TRUE, 16, "Arial Narrow");
					$hoja->setCellValue("D".$flag, "PENDIENTE POR DEVOLUCIÓN")->getStyle("D{$flag}")->getNumberFormat()->setFormatCode("_(\"$\"* #,##0.00_);_(\"$\"* \(#,##0.00\);_(\"$\"* \"-\"??_);_(@_)");
					$this->excelfile->getActiveSheet()->getStyle('K'.$flag)->applyFromArray($styleArray);
					$hoja->setCellValue("K".$flag, "=K".($flag-2)."-B".($flag+1))->getStyle("K{$flag}")->getNumberFormat()->setFormatCode("_(\"$\"* #,##0.00_);_(\"$\"* \(#,##0.00\);_(\"$\"* \"-\"??_);_(@_)");
					$this->excelfile->getActiveSheet()->getStyle('E'.$flag)->applyFromArray($styleArray);
					$this->excelfile->getActiveSheet()->getStyle('F'.$flag)->applyFromArray($styleArray);
					$this->excelfile->getActiveSheet()->getStyle('G'.$flag)->applyFromArray($styleArray);
					$this->excelfile->getActiveSheet()->getStyle('H'.$flag)->applyFromArray($styleArray);
					$this->excelfile->getActiveSheet()->getStyle('I'.$flag)->applyFromArray($styleArray);
					$this->excelfile->getActiveSheet()->getStyle('L'.$flag)->applyFromArray($styleArray);
					$this->excelfile->getActiveSheet()->getStyle('J'.$flag)->applyFromArray($styleArray);
					$flag++;

					$hoja->mergeCells('D'.$flag.":J".$flag);
					$hoja->mergeCells('K'.$flag.":L".$flag);
					$this->cellStyle("A".$flag.":L".$flag, "FFFFFF", "000000", TRUE, 22, "Arial Narrow");
					$this->cellStyle("A".$flag, "FFFFFF", "000000", TRUE, 22, "Arial Narrow");
					$this->cellStyle("B".$flag, "FFFF00", "000000", TRUE, 10, "Arial Narrow");
					$this->cellStyle("C".$flag, "FFFFFF", "000000", TRUE, 10, "Arial Narrow");
					$hoja->setCellValue("B".$flag, "0")->getStyle("B{$flag}")->getNumberFormat()->setFormatCode("_(\"$\"* #,##0.00_);_(\"$\"* \(#,##0.00\);_(\"$\"* \"-\"??_);_(@_)");

					$this->excelfile->getActiveSheet()->getStyle('A'.$flag)->applyFromArray($styleArrayHL);
					$this->excelfile->getActiveSheet()->getStyle('B'.$flag)->applyFromArray($styleArrayHL);
					$this->excelfile->getActiveSheet()->getStyle('C'.$flag)->applyFromArray($styleArrayHL);

					$this->cellStyle("D".$flag, "FFFFFF", "000000", TRUE, 16, "Arial Narrow");
					$hoja->setCellValue("D".$flag, "TOTAL A PAGAR")->getStyle("D{$flag}")->getNumberFormat()->setFormatCode("_(\"$\"* #,##0.00_);_(\"$\"* \(#,##0.00\);_(\"$\"* \"-\"??_);_(@_)");
					$this->excelfile->getActiveSheet()->getStyle('K'.$flag)->applyFromArray($styleArray);
					$hoja->setCellValue("K".$flag, "=L".($flag-3))->getStyle("K{$flag}")->getNumberFormat()->setFormatCode("_(\"$\"* #,##0.00_);_(\"$\"* \(#,##0.00\);_(\"$\"* \"-\"??_);_(@_)");
					$this->excelfile->getActiveSheet()->getStyle('E'.$flag)->applyFromArray($styleArray);
					$this->excelfile->getActiveSheet()->getStyle('D'.$flag)->applyFromArray($styleArray);
					$this->excelfile->getActiveSheet()->getStyle('F'.$flag)->applyFromArray($styleArray);
					$this->excelfile->getActiveSheet()->getStyle('G'.$flag)->applyFromArray($styleArray);
					$this->excelfile->getActiveSheet()->getStyle('H'.$flag)->applyFromArray($styleArray);
					$this->excelfile->getActiveSheet()->getStyle('I'.$flag)->applyFromArray($styleArray);
					$this->excelfile->getActiveSheet()->getStyle('L'.$flag)->applyFromArray($styleArray);
					$this->excelfile->getActiveSheet()->getStyle('J'.$flag)->applyFromArray($styleArray);
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
			$flag9 = 1;
			$this->excelfile->setActiveSheetIndex(9);
			$hoja = $this->excelfile->getActiveSheet();
			$hoja->mergeCells('A'.$flag9.':B'.$flag9.'');
			$this->cellStyle("A".$flag9, "FFFFFF", "000000", TRUE, 12, "Franklin Gothic Book");
			$hoja->setCellValue("A".$flag9, "COMPARACIÓN PEDIDOS '".$provnombre->alias."'");
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
			
			$finals = $this->fac_mdl->getfinals(NULL,$proveedor);
			$facts = $this->fac_mdl->getfacts(NULL,$proveedor);
			$fams = "";
			if ($finals) {
				foreach ($finals as $key => $val) {
					if ($val->proveedor <> $fams) {
						$fams = $val->proveedor;
						$hoja->setCellValue("B".$flag9, $val->proveedor);
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
					$hoja->setCellValue("B".$flag9, $val->descripcion);
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
				$hoja->setCellValue("A".$flag9, "SIN ASOCIAR A PEDIDOS FINALES '".$provnombre->alias."'");
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
			}



			$file_name = "Facturas ".$provnombre->alias.".xlsx"; //Nombre del documento con extención


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

}

/* End of file Lunes.php */
/* Location: ./application/controllers/Lunes.php */
