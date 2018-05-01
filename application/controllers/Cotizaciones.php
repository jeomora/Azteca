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
		$this->load->model("Cambios_model", "cambio_md");
		$this->load->model("Usuarios_model", "user_md");
		$this->load->model("Existencias_model", "ex_mdl");
		$this->load->model("Precio_sistema_model", "pre_mdl");
		$this->load->model("Faltantes_model", "falt_mdl");
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
			$data["cotizaciones"] = $this->ct_mdl->getAllCotizaciones($where);
			$data["usuarios"] = $this->user_md->getUsuarios();	
			$this->estructura("Cotizaciones/table_cotizaciones", $data, FALSE);
		}else{
			$data["proveedores"] = $this->usua_mdl->getUsuarios();
			$data["usuarios"] = $this->user_md->getUsuarios();
			$this->estructura("Cotizaciones/cotizaciones_view", $data, FALSE);
		}
	}

	public function anteriores(){
		ini_set("memory_limit", "-1");
		$data['links'] = [
			'/assets/css/plugins/dataTables/dataTables.bootstrap',
			'/assets/css/plugins/dataTables/dataTables.responsive',
			'/assets/css/plugins/dataTables/dataTables.tableTools.min',
			'/assets/css/plugins/dataTables/buttons.dataTables.min',
		];
		$data['scripts'] = [
			'/scripts/admin',
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
		
		$data["cotizados"] = $this->usua_mdl->getCotizados();
		$data["usuar"]  = $this->session->userdata();
		$this->estructura("Cotizaciones/anteriores", $data);
	}

	public function volumenes(){
		ini_set("memory_limit", "-1");
		$data['links'] = [
			'/assets/css/plugins/dataTables/dataTables.bootstrap',
			'/assets/css/plugins/dataTables/dataTables.responsive',
			'/assets/css/plugins/dataTables/dataTables.tableTools.min',
			'/assets/css/plugins/dataTables/buttons.dataTables.min',
		];
		$data['scripts'] = [
			'/scripts/volumenes',
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
		$data["usuar"]  = $this->session->userdata();
		$this->estructura("Cotizaciones/volumenes", $data);
	}
	
	public function proveedor(){
		ini_set("memory_limit", "-1");
		$data['links'] = [
			'/assets/css/plugins/dataTables/dataTables.bootstrap',
			'/assets/css/plugins/dataTables/dataTables.responsive',
			'/assets/css/plugins/dataTables/dataTables.tableTools.min',
			'/assets/css/plugins/dataTables/buttons.dataTables.min',
		];
		$data['scripts'] = [
			'/scripts/volumenes',
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
		$where=["usuarios.id_grupo" => 2];
		$data["title"] = "Filtrar por proveedor";
		$data["proveedores"] = $this->usua_mdl->getUsuarios($where);
		$data["usuar"]  = $this->session->userdata();
		$this->estructura("Cotizaciones/proveedor", $data);
	}

	public function faltantes(){
		ini_set("memory_limit", "-1");
		$data['links'] = [
			'/assets/css/plugins/dataTables/dataTables.bootstrap',
			'/assets/css/plugins/dataTables/dataTables.responsive',
			'/assets/css/plugins/dataTables/dataTables.tableTools.min',
			'/assets/css/plugins/dataTables/buttons.dataTables.min',
		];
		$data['scripts'] = [
			'/scripts/faltantes',
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
		$where=["usuarios.id_grupo" => 2, "usuarios.estatus" => 1];
		$data["title"] = "Filtrar por proveedor";
		$data["proveedores"] = $this->usua_mdl->getUsuarios($where);
		$data["usuar"]  = $this->session->userdata();
		$this->estructura("Cotizaciones/faltantes", $data);
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
		$fecha = new DateTime(date('Y-m-d H:i:s'));
		$intervalo = new DateInterval('P2D');
		$fecha->add($intervalo);
		$antes =  $this->falt_mdl->get(NULL, ['id_producto' => $this->input->post('id_producto'), 'fecha_termino > ' => date("Y-m-d H:i:s"), 'id_proveedor' => $this->session->userdata('id_usuario')])[0];
		$cotiz =  $this->ct_mdl->get(NULL, ['id_producto' => $this->input->post('id_producto'), 'WEEKOFYEAR(fecha_registro)' => $this->weekNumber($fecha->format('Y-m-d H:i:s')), 'id_proveedor' => $this->session->userdata('id_usuario')])[0];
		if($antes){
			$cotizacion = [
				'id_producto'		=>	$this->input->post('id_producto'),
				'id_proveedor'		=>	$this->session->userdata('id_usuario'),
				'num_one'			=>	$this->input->post('num_one'),
				'num_two'			=>	$this->input->post('num_two'),
				'precio'			=>	str_replace(',', '', $this->input->post('precio')),//precio base
				'precio_promocion'	=>	($this->input->post('precio_promocion') > 0) ? str_replace(',', '', $this->input->post('precio_promocion')) : str_replace(',', '', $this->input->post('precio')),//precio con promoción
				'descuento'			=>	str_replace(',', '', $this->input->post('porcentaje')),
				'fecha_registro'	=>	$fecha->format('Y-m-d H:i:s'),
				'observaciones'		=>	strtoupper($this->input->post('observaciones')),
				'estatus' => 0
			];
			if($cotiz){
				$data['cotizacin']=$this->ct_mdl->update($cotizacion, ['id_cotizacion' => $cotiz->id_cotizacion]);
			}else{
				$data['cotizacin']=$this->ct_mdl->insert($cotizacion);
			}
		}else{
			$cotizacion = [
				'id_producto'		=>	$this->input->post('id_producto'),
				'id_proveedor'		=>	$this->session->userdata('id_usuario'),
				'num_one'			=>	$this->input->post('num_one'),
				'num_two'			=>	$this->input->post('num_two'),
				'precio'			=>	str_replace(',', '', $this->input->post('precio')),//precio base
				'precio_promocion'	=>	($this->input->post('precio_promocion') > 0) ? str_replace(',', '', $this->input->post('precio_promocion')) : str_replace(',', '', $this->input->post('precio')),//precio con promoción
				'descuento'			=>	str_replace(',', '', $this->input->post('porcentaje')),
				'fecha_registro'	=>	$fecha->format('Y-m-d H:i:s'),
				'observaciones'		=>	strtoupper($this->input->post('observaciones'))
			];
			if($cotiz){
				$data['cotizacin']=$this->ct_mdl->update($cotizacion, ['id_cotizacion' => $cotiz->id_cotizacion]);
			}else{
				$data['cotizacin']=$this->ct_mdl->insert($cotizacion);
			}
		}
		
		$mensaje = [
			"id" 	=> 'Éxito',
			"desc"	=> 'Cotización registrada correctamente',
			"type"	=> 'success'
		];
		$this->jsonResponse($mensaje);
	}

	public function update(){
		$user = $this->session->userdata();
		$size = sizeof($this->input->post('id_cotz[]'));
		$cotz = $this->input->post('id_cotz[]');
		$precio = $this->input->post('precio[]');
		$precio_promocion = $this->input->post('precio_promocion[]');
		$num_one = $this->input->post('num_one[]');
		$num_two = $this->input->post('num_two[]');
		$descuento = $this->input->post('descuento[]');
		$observaciones = $this->input->post('observaciones[]');
		for($i = 0; $i < $size; $i++){
			$antes =  $this->ct_mdl->get(NULL, ['id_cotizacion'=>$cotz[$i]])[0];
			$cambios = [
				"id_usuario" => $user["id_usuario"],
				"fecha_cambio" => date('Y-m-d H:i:s'),
				"antes" => "id : ".$antes->id_cotizacion." /Proveedor: ".$antes->id_proveedor." /Producto:".$antes->id_producto." /Precio: ".
							$antes->precio." /Precio promoción: ".$antes->precio_promocion." /".$antes->num_one." en ".$antes->num_two.
							" /%Descuento: ".$antes->descuento." /Registrado: ".$antes->fecha_registro." /Observaciones: ".$antes->observaciones,
				"despues" => "id : ".$cotz[$i]." /Proveedor: ".$antes->id_proveedor." /Producto:".$antes->id_producto." /Precio: ".
							$precio[$i]." /Precio promoción: ".$precio_promocion[$i]." /".$num_one[$i]." en ".$num_two[$i].
							" /%Descuento: ".$descuento[$i]." /Observaciones: ".$observaciones[$i]];
			$data['cambios'] = $this->cambio_md->insert($cambios);
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
		$user = $this->session->userdata();
		$productos = $this->input->post('id_producto[]');
		for($i = 0; $i < $size; $i++){
			$antes =  $this->ct_mdl->get(NULL, ['id_cotizacion'=>$productos[$i]])[0];
			$cambios = [
				"id_usuario" => $user["id_usuario"],
				"fecha_cambio" => date('Y-m-d H:i:s'),
				"antes" => "id : ".$antes->id_cotizacion." /Proveedor: ".$antes->id_proveedor." /Producto:".$antes->id_producto." /Precio: ".
							$antes->precio." /Precio promoción: ".$antes->precio_promocion." /".$antes->num_one." en ".$antes->num_two.
							" /%Descuento: ".$antes->descuento." /Registrado: ".$antes->fecha_registro." /Observaciones: ".$antes->observaciones,
				"despues" => "Cotización eliminada"];
			$data['cambios'] = $this->cambio_md->insert($cambios);
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
		$fecha = new DateTime(date('Y-m-d H:i:s'));
		$intervalo = new DateInterval('P2D');
		$fecha->add($intervalo);
		$size = sizeof($this->input->post('id_producto[]'));
		$productos = $this->input->post('id_producto[]');
		$importe = $this->input->post('importe[]');
		for($i = 0; $i < $size; $i++){
			$pedid = $this->ped_mdl->getWeekPedidos(NULL,$productos[$i],$this->weekNumber($fecha->format('Y-m-d H:i:s')));
			if($pedid){
				$id_pedido = $this->ped_mdl->update(["total" => 0], $pedid->total+$importe[$i]);
			}else{
				$pedido = [
					"id_sucursal"		=>	1,
					"id_proveedor"		=>	$producto[$i],
					"id_user_registra"	=>	$this->ion_auth->user()->row()->id, 
					"fecha_registro"	=>	$fecha->format('Y-m-d H:i:s'),
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
		$fecha = new DateTime(date('Y-m-d H:i:s'));
		$intervalo = new DateInterval('P2D');
		$fecha->add($intervalo);
		$data["cotizacion"] = $this->ct_mdl->get(NULL, ['id_cotizacion'=>$id])[0];
		$data["producto"] = $this->prod_mdl->get(NULL, ['id_producto'=>$data["cotizacion"]->id_producto])[0];
		$data["title"]="ACTUALIZAR COTIZACIÓN DE <br>".$data["producto"]->nombre;
		$user = $this->session->userdata();
		if($user['id_grupo'] ==2){//Proveedor
			$where=["cotizaciones.id_proveedor" => $user['id_usuario']];
			$data["cots"]=$this->ct_mdl->get_cots($where, $data["cotizacion"]->id_producto,$fecha->format('Y-m-d H:i:s'));
			$where=["cotizaciones.id_proveedor" => $user['id_usuario'], "cotizaciones.estatus" => 0];
			$data["cotss"]=$this->ct_mdl->get_cots($where, $data["cotizacion"]->id_producto,$fecha->format('Y-m-d H:i:s'));
		}else{
			$data["cots"]=$this->ct_mdl->get_cots(NULL, $data["cotizacion"]->id_producto,$fecha->format('Y-m-d H:i:s'));
			$where=["cotizaciones.estatus" => 0];
			$data["cotss"]=$this->ct_mdl->get_cots($where, $data["cotizacion"]->id_producto,$fecha->format('Y-m-d H:i:s'));
		}
		$data["view"]=$this->load->view("Cotizaciones/edit_cotizacion", $data, TRUE);
		$data["button"]="<button class='btn btn-success update_cotizacion' type='button'>
							<span class='bold'><i class='fa fa-floppy-o'></i></span> &nbsp;Guardar cambios
						</button>";
		$this->jsonResponse($data);
	}

	public function get_delete($id){
		$fecha = new DateTime(date('Y-m-d H:i:s'));
		$intervalo = new DateInterval('P2D');
		$fecha->add($intervalo);
		$data["cotizacion"] = $this->ct_mdl->get(NULL, ['id_cotizacion'=>$id])[0];
		$data["producto"] = $this->prod_mdl->get(NULL, ['id_producto'=>$data["cotizacion"]->id_producto])[0];
		$data["title"]="Seleccione una opción para eliminar la Cotización del producto:<br>".$data["producto"]->nombre;
		$user = $this->session->userdata();
		if($user['id_grupo'] ==2){//Proveedor
			$where=["cotizaciones.id_proveedor" => $user['id_usuario']];
			$data["cots"]=$this->ct_mdl->get_cots($where, $data["cotizacion"]->id_producto,$fecha->format('Y-m-d H:i:s'));
			$where=["cotizaciones.id_proveedor" => $user['id_usuario'], "cotizaciones.estatus" => 0];
			$data["cotss"]=$this->ct_mdl->get_cots($where, $data["cotizacion"]->id_producto,$fecha->format('Y-m-d H:i:s'));
		}else{
			$data["cots"]=$this->ct_mdl->get_cots(NULL, $data["cotizacion"]->id_producto,$fecha->format('Y-m-d H:i:s'));
			$where=["cotizaciones.estatus" => 0];
			$data["cotss"]=$this->ct_mdl->get_cots($where, $data["cotizacion"]->id_producto,$fecha->format('Y-m-d H:i:s'));
		}
		$data["view"]=$this->load->view("Cotizaciones/delete_cotizacion", $data, TRUE);
		$data["button"]="<button class='btn btn-danger delete_cotizacion' type='button'>
							<span class='bold'><i class='fa fa-trash'></i></span> &nbsp;Eliminar
						</button>";
		$this->jsonResponse($data);
	}
	public function detallazos($id){
		$fecha = new DateTime(date('Y-m-d H:i:s'));
		$intervalo = new DateInterval('P2D');
		$fecha->add($intervalo);
		$data["cotizacion"] = $this->ct_mdl->get(NULL, ['id_cotizacion'=>$id])[0];
		$data["producto"] = $this->prod_mdl->get(NULL, ['id_producto'=>$data["cotizacion"]->id_producto])[0];
		$data["title"]="Cotizaciones Eliminadas y faltantes de :<br>".$data["producto"]->nombre;
		$user = $this->session->userdata();
		$data["faltas"] = $this->falt_mdl->getfaltas(NULL,$data["cotizacion"]->id_producto, date("Y-m-d H:i:s"));
		$data["cotss"]=$this->ct_mdl->get_cotdel(NULL, $data["cotizacion"]->id_producto,$fecha->format('Y-m-d H:i:s'));
		$fecha = new DateTime(date('Y-m-d H:i:s'));
		$intervalo = new DateInterval('P7D');
		$fecha->sub($intervalo);
		$data["lastweek"]=$this->ct_mdl->getLastWeek(NULL, $data["cotizacion"]->id_producto,$this->weekNumber($fecha->format('Y-m-d H:i:s')));
		$data["view"]=$this->load->view("Cotizaciones/detallazos", $data, TRUE);
		$this->jsonResponse($data);
	}

	public function ver_cotizacion($id,$fech){
		$data["cotizacion"] = $this->ct_mdl->get(NULL, ['id_cotizacion'=>$id])[0];
		$data["producto"] = $this->prod_mdl->get(NULL, ['id_producto'=>$data["cotizacion"]->id_producto])[0];
		$data["title"]="Cotizaciones del producto :<br>".$data["producto"]->nombre;
		$user = $this->session->userdata();
		if($user['id_grupo'] ==2){//Proveedor
			$where=["cotizaciones.id_proveedor" => $user['id_usuario']];
			$data["cots"]=$this->ct_mdl->get_cots($where, $data["cotizacion"]->id_producto,$fech);
			$where=["cotizaciones.id_proveedor" => $user['id_usuario'], "cotizaciones.estatus" => 0];
			$data["cotss"]=$this->ct_mdl->get_cots($where, $data["cotizacion"]->id_producto,$fech);
		}else{
			$data["cots"]=$this->ct_mdl->get_cots(NULL, $data["cotizacion"]->id_producto,$fech);
			$where=["cotizaciones.estatus" => 0];
			$data["cotss"]=$this->ct_mdl->get_cots($where, $data["cotizacion"]->id_producto,$fech);
		}
		$data["view"]=$this->load->view("Cotizaciones/ver_cotizacion", $data, TRUE);
		$data["button"]="";
		$this->jsonResponse($data);
	}

	public function getAdminTable(){
		$fecha = new DateTime(date('Y-m-d H:i:s'));
		$intervalo = new DateInterval('P2D');
		$fecha->add($intervalo);
		$fecha = $fecha->format('Y-m-d H:i:s');
		$data["cotizaciones"] = $this->ct_mdl->getCotz(NULL,$fecha);
		$this->jsonResponse($data);
	}
	public function getVolTable(){
		$fecha = new DateTime(date('Y-m-d H:i:s'));
		$intervalo = new DateInterval('P2D');
		$fecha->add($intervalo);
		$fecha = $fecha->format('Y-m-d H:i:s');
		$data["cotizados"] = $this->ct_mdl->getCotzV(NULL,$fecha);
		$this->jsonResponse($data);
	}

	public function getGrupo(){
		$user = $this->session->userdata();
		$data["ides"] = $user['id_grupo'];
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

	
	public function fill_excel(){
		ini_set("memory_limit", "-1");
		ini_set("max_execution_time", "-1");
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

		$this->cellStyle("A1:R2", "000000", "FFFFFF", TRUE, 12, "Franklin Gothic Book");
		$border_style= array('borders' => array('right' => array('style' => 
			PHPExcel_Style_Border::BORDER_THIN,'color' => array('argb' => '000000'),)));
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
		$hoja->setCellValue("O1", "3ER PRECIO")->getColumnDimension('O')->setWidth(12);
		$hoja->setCellValue("P1", "PRECIO PROMOCIÓN")->getColumnDimension('P')->setWidth(12);
		$hoja->setCellValue("Q1", "3ER PROVEEDOR")->getColumnDimension('Q')->setWidth(15);
		$hoja->setCellValue("R1", "3ER OBSERVACIÓN")->getColumnDimension('R')->setWidth(30);

		$where=["prod.estatus <> "=>0];//Semana actual
		$fecha = new DateTime(date('Y-m-d H:i:s'));
		$intervalo = new DateInterval('P2D');
		$fecha->add($intervalo);
		$fecha = $fecha->format('Y-m-d H:i:s');
		$cotizacionesProveedor = $this->ct_mdl->comparaCotizaciones2($where, $fecha,0);

		$row_print =3;
		if ($cotizacionesProveedor){
			foreach ($cotizacionesProveedor as $key => $value){
				$hoja->setCellValue("B{$row_print}", $value['familia']);
				$this->cellStyle("B{$row_print}", "000000", "FFFFFF", TRUE, 12, "Franklin Gothic Book");
				$row_print +=1;
				if ($value['articulos']) {
					foreach ($value['articulos'] as $key => $row){
						$this->cellStyle("B{$row_print}:L{$row_print}", "FFFFFF", "000000", FALSE, 12, "Franklin Gothic Book");
						if($row['color'] == '#92CEE3'){
							$this->cellStyle("A{$row_print}", "92CEE3", "000000", TRUE, 12, "Franklin Gothic Book");
						}else{
							$this->cellStyle("A{$row_print}", "FFFFFF", "000000", TRUE, 12, "Franklin Gothic Book");
						}
						$hoja->setCellValue("A{$row_print}", $row['codigo'])->getStyle("A{$row_print}")->getNumberFormat()->setFormatCode('# ???/???');//Formato de fraccion
						$hoja->getStyle("A{$row_print}")->applyFromArray($border_style);
						$hoja->setCellValue("B{$row_print}", $row['producto']);
						if($row['colorp'] == 1){
							$this->cellStyle("C{$row_print}", "D6DCE4", "000000", FALSE, 12, "Franklin Gothic Book");
						}else{
							$this->cellStyle("C{$row_print}", "FFFFFF", "000000", FALSE, 12, "Franklin Gothic Book");
						}
						$hoja->setCellValue("C{$row_print}", $row['precio_sistema'])->getStyle("C{$row_print}")->getNumberFormat()->setFormatCode('"$"#,##0.00_-');//Formto de moneda
						$hoja->getStyle("C{$row_print}")->applyFromArray($border_style);
						$hoja->setCellValue("D{$row_print}", $row['precio_four'])->getStyle("D{$row_print}")->getNumberFormat()->setFormatCode('"$"#,##0.00_-');
						$hoja->getStyle("D{$row_print}")->applyFromArray($border_style);
						$hoja->setCellValue("E{$row_print}", $row['precio_firsto'])->getStyle("E{$row_print}")->getNumberFormat()->setFormatCode('"$"#,##0.00_-');
						$hoja->getStyle("E{$row_print}")->applyFromArray($border_style);
						if($row['estatus'] == 2){
							$this->cellStyle("B{$row_print}", "00B0F0", "000000", FALSE, 12, "Franklin Gothic Book");
						}
						if($row['estatus'] == 3){
							$this->cellStyle("B{$row_print}", "FFF900", "000000", FALSE, 12, "Franklin Gothic Book");
						}
						$hoja->getStyle("B{$row_print}")->applyFromArray($border_style);
						if($row['precio_sistema'] < $row['precio_first']){
							$hoja->setCellValue("F{$row_print}", $row['precio_first'])->getStyle("F{$row_print}")->getNumberFormat()->setFormatCode('"$"#,##0.00_-');
							$this->cellStyle("F{$row_print}", "FDB2B2", "E21111", FALSE, 12, "Franklin Gothic Book");
						}else{
							$hoja->setCellValue("F{$row_print}", $row['precio_first'])->getStyle("F{$row_print}")->getNumberFormat()->setFormatCode('"$"#,##0.00_-');
							$this->cellStyle("F{$row_print}", "96EAA8", "0C800C", FALSE, 12, "Franklin Gothic Book");
						}
						$hoja->getStyle("F{$row_print}")->applyFromArray($border_style);
						$hoja->setCellValue("G{$row_print}", $row['proveedor_first'])->getStyle("G{$row_print}");
						$hoja->getStyle("G{$row_print}")->applyFromArray($border_style);
						$hoja->setCellValue("H{$row_print}", $row['promocion_first'])->getStyle("H{$row_print}");
						$hoja->getStyle("H{$row_print}")->applyFromArray($border_style);
						$hoja->setCellValue("I{$row_print}", $row['precio_maximo'])->getStyle("I{$row_print}")->getNumberFormat()->setFormatCode('"$"#,##0.00_-');
						$hoja->getStyle("I{$row_print}")->applyFromArray($border_style);
						$hoja->setCellValue("J{$row_print}", $row['precio_promedio'])->getStyle("J{$row_print}")->getNumberFormat()->setFormatCode('"$"#,##0.00_-');
						$hoja->getStyle("J{$row_print}")->applyFromArray($border_style);
						$hoja->setCellValue("K{$row_print}", $row['precio_nexto'])->getStyle("K{$row_print}")->getNumberFormat()->setFormatCode('"$"#,##0.00_-');
						$hoja->getStyle("K{$row_print}")->applyFromArray($border_style);
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
						$hoja->getStyle("L{$row_print}")->applyFromArray($border_style);
						$hoja->setCellValue("M{$row_print}", $row['proveedor_next'])->getStyle("M{$row_print}");
						$hoja->getStyle("M{$row_print}")->applyFromArray($border_style);						
						$hoja->setCellValue("N{$row_print}", $row['promocion_next'])->getStyle("N{$row_print}");
						$hoja->getStyle("N{$row_print}")->applyFromArray($border_style);

						$hoja->setCellValue("O{$row_print}", $row['precio_nxtso'])->getStyle("O{$row_print}")->getNumberFormat()->setFormatCode('"$"#,##0.00_-');
						$hoja->getStyle("O{$row_print}")->applyFromArray($border_style);
						if($row['precio_sistema'] < $row['precio_nxts']){
							$hoja->setCellValue("P{$row_print}", $row['precio_nxts'])->getStyle("P{$row_print}")->getNumberFormat()->setFormatCode('"$"#,##0.00_-');
							$this->cellStyle("P{$row_print}", "FDB2B2", "E21111", FALSE, 12, "Franklin Gothic Book");
						}else if($row['precio_next'] !== NULL){
							$hoja->setCellValue("P{$row_print}", $row['precio_nxts'])->getStyle("P{$row_print}")->getNumberFormat()->setFormatCode('"$"#,##0.00_-');
							$this->cellStyle("P{$row_print}", "96EAA8", "0C800C", FALSE, 12, "Franklin Gothic Book");
						}else{
							$hoja->setCellValue("P{$row_print}", $row['precio_nxts'])->getStyle("P{$row_print}")->getNumberFormat()->setFormatCode('"$"#,##0.00_-');
							$this->cellStyle("P{$row_print}", "FFFFFF", "000000", FALSE, 12, "Franklin Gothic Book");
						}
						$hoja->getStyle("P{$row_print}")->applyFromArray($border_style);
						$hoja->setCellValue("Q{$row_print}", $row['proveedor_nxts'])->getStyle("Q{$row_print}");
						$hoja->getStyle("Q{$row_print}")->applyFromArray($border_style);						
						$hoja->setCellValue("R{$row_print}", $row['promocion_nxts'])->getStyle("R{$row_print}");
						$hoja->getStyle("R{$row_print}")->applyFromArray($border_style);
						$row_print ++;
					}
				}
			}
		}
		$hoja->getStyle("A3:N{$row_print}")
                 ->getAlignment()
                 ->setHorizontal(\PHPExcel_Style_Alignment::HORIZONTAL_LEFT);

		$file_name = "Cotizaciones ".$fecha.".xlsx"; //Nombre del documento con extención
		header("Content-Type: application/vnd.ms-excel; charset=utf-8");
		header("Content-Disposition: attachment;filename=".$file_name);
		header("Cache-Control: max-age=0");
		$excel_Writer = PHPExcel_IOFactory::createWriter($this->excelfile, "Excel2007");
		$excel_Writer->save("php://output");
	}

	public function fill_excel_pro(){
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

		$this->cellStyle("A1:G2", "000000", "FFFFFF", TRUE, 12, "Franklin Gothic Book");
		$border_style= array('borders' => array('right' => array('style' => 
			PHPExcel_Style_Border::BORDER_THIN,'color' => array('argb' => '000000'),)));
		
		$hoja->setCellValue("B1", "DESCRIPCIÓN SISTEMA")->getColumnDimension('B')->setWidth(70);
		$hoja->setCellValue("C1", "PRECIO")->getColumnDimension('C')->setWidth(15);
		$hoja->setCellValue("D1", "PROMOCIÓN")->getColumnDimension('D')->setWidth(50);
		$hoja->setCellValue("E1", "# EN #")->getColumnDimension('E')->setWidth(12);
		$hoja->setCellValue("F1", "# EN #")->getColumnDimension('F')->setWidth(12);
		$hoja->setCellValue("G1", "% DESCUENTO")->getColumnDimension('G')->setWidth(15);

		$hoja->setCellValue("A2", "CÓDIGO")->getColumnDimension('A')->setWidth(30); //Nombre y ajuste de texto a la columna
		$hoja->mergeCells('E1:F1');

		$productos = $this->prod_mdl->getProdFam(NULL,$this->input->post('id_pro'));
		$provs = $this->usua_mdl->get(NULL, ['id_usuario'=>$this->input->post('id_pro')])[0];
		$row_print = 2;
		if ($productos){
			foreach ($productos as $key => $value){
				$hoja->setCellValue("B{$row_print}", $value['familia']);
				$this->cellStyle("B{$row_print}", "000000", "FFFFFF", TRUE, 12, "Franklin Gothic Book");
				$hoja->setCellValue("B{$row_print}", $value['familia']);
				$hoja->setCellValue("C{$row_print}", $provs->nombre.' '.$provs->apellido);
				$hoja->getStyle("C{$row_print}")->applyFromArray($border_style);
				$row_print +=1;
				if ($value['articulos']) {
					foreach ($value['articulos'] as $key => $row){
						if($row['color'] == '#92CEE3'){
							$this->cellStyle("A{$row_print}", "92CEE3", "000000", FALSE, 10, "Franklin Gothic Book");
						}else{
							$this->cellStyle("A{$row_print}", "FFFFFF", "000000", FALSE, 10, "Franklin Gothic Book");
						}
						$hoja->setCellValue("A{$row_print}", $row['codigo'])->getStyle("A{$row_print}")->getNumberFormat()->setFormatCode('# ???/???');//Formato de fraccion
						$hoja->getStyle("A{$row_print}")->applyFromArray($border_style);
						$hoja->setCellValue("B{$row_print}", $row['producto']);
						if($row['estatus'] == 2){
							$this->cellStyle("B{$row_print}", "00B0F0", "000000", FALSE, 10, "Franklin Gothic Book");
						}
						if($row['estatus'] == 3){
							$this->cellStyle("B{$row_print}", "FFF900", "000000", FALSE, 10, "Franklin Gothic Book");
						}
						
						$hoja->getStyle("B{$row_print}")->applyFromArray($border_style);
						if($row['colorp'] == 1){
							$this->cellStyle("C{$row_print}", "D6DCE4", "000000", FALSE, 10, "Franklin Gothic Book");
						}else{
							$this->cellStyle("C{$row_print}", "FFFFFF", "000000", FALSE, 10, "Franklin Gothic Book");
						}
						$hoja->setCellValue("C{$row_print}", $row['precio'])->getStyle("C{$row_print}")->getNumberFormat()->setFormatCode('"$"#,##0.00_-');
						$hoja->getStyle("C{$row_print}")->applyFromArray($border_style);

						$hoja->setCellValue("D{$row_print}", $row['observaciones']);
						$hoja->getStyle("D{$row_print}")->applyFromArray($border_style);
						$hoja->setCellValue("E{$row_print}", $row['num_one']);
						$hoja->getStyle("E{$row_print}")->applyFromArray($border_style);
						$hoja->setCellValue("F{$row_print}", $row['num_two']);
						$hoja->getStyle("F{$row_print}")->applyFromArray($border_style);
						$hoja->setCellValue("G{$row_print}", $row['descuento']);
						$hoja->getStyle("G{$row_print}")->applyFromArray($border_style);
						if($this->weekNumber($row['fecha_registro']) >= ($this->weekNumber())){
							$this->cellStyle("A{$row_print}", "FF7F71", "000000", FALSE, 10, "Franklin Gothic Book");
							$this->cellStyle("B{$row_print}", "FF7F71", "000000", FALSE, 10, "Franklin Gothic Book");
							$this->cellStyle("C{$row_print}", "FF7F71", "000000", TRUE, 10, "Franklin Gothic Book");
							$this->cellStyle("D{$row_print}", "FF7F71", "000000", FALSE, 10, "Franklin Gothic Book");
							$this->cellStyle("E{$row_print}", "FF7F71", "000000", FALSE, 10, "Franklin Gothic Book");
							$this->cellStyle("F{$row_print}", "FF7F71", "000000", FALSE, 10, "Franklin Gothic Book");
							$this->cellStyle("G{$row_print}", "FF7F71", "000000", FALSE, 10, "Franklin Gothic Book");
							$hoja->setCellValue("H{$row_print}", "NUEVO");
						}
						$row_print++;
					}
				}
			}
		}
		$hoja->getStyle("A3:H{$row_print}")
                 ->getAlignment()
                 ->setHorizontal(\PHPExcel_Style_Alignment::HORIZONTAL_LEFT);
		$hoja->getStyle("B3:B{$row_print}")
                 ->getAlignment()
                 ->setHorizontal(\PHPExcel_Style_Alignment::HORIZONTAL_LEFT);


		$file_name = "Cotización ".$provs->nombre.".xlsx"; //Nombre del documento con extención
		header("Content-Type: application/vnd.ms-excel; charset=utf-8");
		header("Content-Disposition: attachment;filename=".$file_name);
		header("Cache-Control: max-age=0");
		$excel_Writer = PHPExcel_IOFactory::createWriter($this->excelfile, "Excel2007");
		$excel_Writer->save("php://output");

	}

	public function fill_excelV(){
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
		$hoja->setCellValue("G1", "PROVEEDOR")->getColumnDimension('G')->setWidth(20);
		$hoja->setCellValue("H1", "OBSERVACIÓN")->getColumnDimension('H')->setWidth(30);
		$hoja->setCellValue("I1", "PRECIO MÁXIMO")->getColumnDimension('I')->setWidth(12);
		$hoja->setCellValue("J1", "PRECIO PROMEDIO")->getColumnDimension('J')->setWidth(12);
		$hoja->setCellValue("K1", "2DO PRECIO")->getColumnDimension('K')->setWidth(12);
		$hoja->setCellValue("L1", "PRECIO PROMOCIÓN")->getColumnDimension('L')->setWidth(12);
		$hoja->setCellValue("M1", "2DO PROVEEDOR")->getColumnDimension('M')->setWidth(20);
		$hoja->setCellValue("N1", "2DA OBSERVACIÓN")->getColumnDimension('N')->setWidth(30);
		$where=["prod.estatus"=>2];//Semana actual
		$fecha = new DateTime(date('Y-m-d H:i:s'));
		$intervalo = new DateInterval('P2D');
		$fecha->add($intervalo);
		$fecha = $fecha->format('Y-m-d H:i:s');
		$cotizacionesProveedor = $this->ct_mdl->comparaCotizaciones2($where, $fecha,0);

		$border_style= array('borders' => array('right' => array('style' => 
			PHPExcel_Style_Border::BORDER_THIN,'color' => array('argb' => '000000'),)));

		$row_print =3;
		if ($cotizacionesProveedor){
			foreach ($cotizacionesProveedor as $key => $value){
				if ($value['articulos']) {
					foreach ($value['articulos'] as $key => $row){
						$this->cellStyle("A{$row_print}", "FFFFFF", "000000", TRUE, 12, "Franklin Gothic Book");
						$this->cellStyle("B{$row_print}:L{$row_print}", "FFFFFF", "000000", FALSE, 12, "Franklin Gothic Book");
						$hoja->setCellValue("A{$row_print}", $row['codigo'])->getStyle("A{$row_print}")->getNumberFormat()->setFormatCode('# ???/???');//Formato de fraccion
						$hoja->getStyle("A{$row_print}")->applyFromArray($border_style);
						$hoja->setCellValue("B{$row_print}", $row['producto']);
						$hoja->getStyle("B{$row_print}")->applyFromArray($border_style);
						$hoja->setCellValue("C{$row_print}", $row['precio_sistema'])->getStyle("C{$row_print}")->getNumberFormat()->setFormatCode('"$"#,##0.00_-');//Formto de moneda
						$hoja->getStyle("C{$row_print}")->applyFromArray($border_style);
						$hoja->setCellValue("D{$row_print}", $row['precio_four'])->getStyle("D{$row_print}")->getNumberFormat()->setFormatCode('"$"#,##0.00_-');
						$hoja->getStyle("D{$row_print}")->applyFromArray($border_style);
						$hoja->setCellValue("E{$row_print}", $row['precio_firsto'])->getStyle("E{$row_print}")->getNumberFormat()->setFormatCode('"$"#,##0.00_-');
						$hoja->getStyle("E{$row_print}")->applyFromArray($border_style);
						if($row['estatus'] == 2){
							$this->cellStyle("B{$row_print}", "00B0F0", "000000", FALSE, 12, "Franklin Gothic Book");
						}
						if($row['estatus'] == 3){
							$this->cellStyle("B{$row_print}", "FFF900", "000000", FALSE, 12, "Franklin Gothic Book");
						}
						if($row['precio_sistema'] < $row['precio_first']){
							$hoja->setCellValue("F{$row_print}", $row['precio_first'])->getStyle("F{$row_print}")->getNumberFormat()->setFormatCode('"$"#,##0.00_-');
							$this->cellStyle("F{$row_print}", "FDB2B2", "E21111", FALSE, 12, "Franklin Gothic Book");
						}else{
							$hoja->setCellValue("F{$row_print}", $row['precio_first'])->getStyle("F{$row_print}")->getNumberFormat()->setFormatCode('"$"#,##0.00_-');
							$this->cellStyle("F{$row_print}", "96EAA8", "0C800C", FALSE, 12, "Franklin Gothic Book");
						}
						$hoja->getStyle("F{$row_print}")->applyFromArray($border_style);
						$hoja->setCellValue("G{$row_print}", $row['proveedor_first'])->getStyle("G{$row_print}");
						$hoja->getStyle("G{$row_print}")->applyFromArray($border_style);
						$hoja->setCellValue("H{$row_print}", $row['promocion_first'])->getStyle("H{$row_print}");
						$hoja->getStyle("H{$row_print}")->applyFromArray($border_style);
						$hoja->setCellValue("I{$row_print}", $row['precio_maximo'])->getStyle("I{$row_print}")->getNumberFormat()->setFormatCode('"$"#,##0.00_-');
						$hoja->getStyle("I{$row_print}")->applyFromArray($border_style);
						$hoja->setCellValue("J{$row_print}", $row['precio_promedio'])->getStyle("J{$row_print}")->getNumberFormat()->setFormatCode('"$"#,##0.00_-');
						$hoja->getStyle("J{$row_print}")->applyFromArray($border_style);
						$hoja->setCellValue("K{$row_print}", $row['precio_nexto'])->getStyle("K{$row_print}")->getNumberFormat()->setFormatCode('"$"#,##0.00_-');
						$hoja->getStyle("K{$row_print}")->applyFromArray($border_style);
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
						$hoja->getStyle("L{$row_print}")->applyFromArray($border_style);
						$hoja->setCellValue("M{$row_print}", $row['proveedor_next'])->getStyle("M{$row_print}");	
						$hoja->getStyle("M{$row_print}")->applyFromArray($border_style);					
						$hoja->setCellValue("N{$row_print}", $row['promocion_next'])->getStyle("N{$row_print}");
						$hoja->getStyle("N{$row_print}")->applyFromArray($border_style);

						$row_print ++;
					}
				}
			}
		}
		$hoja->getStyle("A3:N{$row_print}")
                 ->getAlignment()
                 ->setHorizontal(\PHPExcel_Style_Alignment::HORIZONTAL_LEFT);
	
		$file_name = "Cotizaciones Volúmenes.xlsx"; //Nombre del documento con extención
		header("Content-Type: application/vnd.ms-excel; charset=utf-8");
		header("Content-Disposition: attachment;filename=".$file_name);
		header("Cache-Control: max-age=0");
		$excel_Writer = PHPExcel_IOFactory::createWriter($this->excelfile, "Excel2007");
		$excel_Writer->save("php://output");
	}

	public function upload_cotizaciones(){
		$fecha = new DateTime(date('Y-m-d H:i:s'));
		$intervalo = new DateInterval('P2D');
		$fecha->add($intervalo);
		
		

		$config['upload_path']          = './assets/uploads/cotizaciones/';
        $config['allowed_types']        = 'xlsx|xls';
        $config['max_size']             = 100;
        $config['max_width']            = 1024;
        $config['max_height']           = 768;
        $config['max_height']           = 768;
        

        $this->load->library('upload', $config);
        $this->upload->do_upload('file_cotizaciones');

		$this->load->library("excelfile");
		ini_set("memory_limit", -1);
		$file = $_FILES["file_cotizaciones"]["tmp_name"];
		$sheet = PHPExcel_IOFactory::load($file);
		$objExcel = PHPExcel_IOFactory::load($file);
		$sheet = $objExcel->getSheet(0); 
		$num_rows = $sheet->getHighestDataRow();
		$proveedor = $this->session->userdata('id_usuario');
		for ($i=3; $i<=$num_rows; $i++) { 
			if($sheet->getCell('C'.$i)->getValue() > 0){
				$productos = $this->prod_mdl->get("id_producto",['codigo'=> htmlspecialchars($sheet->getCell('A'.$i)->getValue(), ENT_QUOTES, 'UTF-8')])[0];
				if (sizeof($productos) > 0) {
					$precio=0; $column_one=0; $column_two=0; $descuento=0; $precio_promocion=0;
					$precio = str_replace("$", "", str_replace(",", "replace", $sheet->getCell('C'.$i)->getValue()));
					$column_one = $sheet->getCell('E'.$i)->getValue();
					$column_two = $sheet->getCell('F'.$i)->getValue();
					$descuento = $sheet->getCell('G'.$i)->getValue();

					if ($column_one ==1 && $column_two ==1) {
						$precio_promocion = (($precio * $column_two)/($column_one+$column_two));
					}elseif ($column_one >=1 && $column_two >1) {
						$precio_promocion = (($precio * $column_two)/($column_one+$column_two));
					}elseif ($descuento >0) {
						$precio_promocion = ($precio - ($precio * ($descuento/100)));
					}else{
						$precio_promocion = $precio;
					}
					$antes =  $this->falt_mdl->get(NULL, ['id_producto' => $productos->id_producto, 'fecha_termino > ' => date("Y-m-d H:i:s"), 'id_proveedor' => $this->session->userdata('id_usuario')])[0];
					if($antes){
						$new_cotizacion[$i]=[
							"id_producto"		=>	$productos->id_producto,
							"id_proveedor"		=>	$proveedor,//Recupera el id_usuario activo
							"precio"			=>	$precio,
							"num_one"			=>	$column_one,
							"num_two"			=>	$column_two,
							"descuento"			=>	$descuento,
							"precio_promocion"	=>	$precio_promocion,
							"fecha_registro"	=>	$fecha->format('Y-m-d H:i:s'),
							"observaciones"		=>	$sheet->getCell('D'.$i)->getValue(),
							"estatus" => 0
						];
					}else{
						$new_cotizacion[$i]=[
							"id_producto"		=>	$productos->id_producto,
							"id_proveedor"		=>	$proveedor,//Recupera el id_usuario activo
							"precio"			=>	$precio,
							"num_one"			=>	$column_one,
							"num_two"			=>	$column_two,
							"descuento"			=>	$descuento,
							"precio_promocion"	=>	$precio_promocion,
							"fecha_registro"	=>	$fecha->format('Y-m-d H:i:s'),
							"observaciones"		=>	$sheet->getCell('D'.$i)->getValue(),
							"estatus" => 1
						];
					}
					
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

	public function upload_pedidos(){
		$fecha = new DateTime(date('Y-m-d H:i:s'));
		$intervalo = new DateInterval('P2D');
		$fecha->add($intervalo);
		$this->load->library("excelfile");
		ini_set("memory_limit", -1);
		$file = $_FILES["file_cotizaciones"]["tmp_name"];
		$sheet = PHPExcel_IOFactory::load($file);
		$objExcel = PHPExcel_IOFactory::load($file);
		$sheet = $objExcel->getSheet(0); 
		$num_rows = $sheet->getHighestDataRow();
		$tienda = $this->session->userdata('id_usuario');

		$config['upload_path']          = './assets/uploads/precios/';
        $config['allowed_types']        = 'xlsx|xls';
        $config['max_size']             = 100;
        $config['max_width']            = 1024;
        $config['max_height']           = 768;
        $config['max_height']           = 768;
        

        $this->load->library('upload', $config);
        $this->upload->do_upload('file_cotizaciones');
		for ($i=3; $i<=$num_rows; $i++) { 
			$productos = $this->prod_mdl->get("id_producto",['codigo'=> htmlspecialchars($sheet->getCell('D'.$i)->getValue(), ENT_QUOTES, 'UTF-8')])[0];
			if (sizeof($productos) > 0) {
				$this->prod_mdl->delete("EXISTENCIAS","WEEKOFYEAR(fecha_registro)".$this->weekNumber($fecha->format('Y-m-d H:i:s'))."AND id_tienda = ".$tienda." AND id_producto = ".$productos->id_producto);
				$column_one=0; $column_two=0; $column_three=0;
				$column_one = $sheet->getCell('A'.$i)->getValue() == "" ? 0 : $sheet->getCell('A'.$i)->getValue();	
				$column_two = $sheet->getCell('B'.$i)->getValue() == "" ? 0 : $sheet->getCell('B'.$i)->getValue();
				$column_three = $sheet->getCell('C'.$i)->getValue() == "" ? 0 : $sheet->getCell('C'.$i)->getValue();

				$new_existencias[$i]=[
					"id_producto"			=>	$productos->id_producto,
					"id_tienda"			=>	$tienda,
					"cajas"			=>	$column_one,
					"piezas"			=>	$column_two,
					"pedido"	=>	$column_three,
					"fecha_registro"	=>	$fecha->format('Y-m-d H:i:s')
				];
			}
		}
		if (sizeof($new_existencias) > 0) {
			$data['cotizacion']=$this->ex_mdl->insert_batch($new_existencias);
			$mensaje=[	"id"	=>	'Éxito',
						"desc"	=>	'Pedidos cargados correctamente en el Sistema',
						"type"	=>	'success'];
		}else{
			$mensaje=[	"id"	=>	'Error',
						"desc"	=>	'Los Pedidos no se cargaron al Sistema',
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
		$fecha = new DateTime(date('Y-m-d H:i:s'));
		$intervalo = new DateInterval('P2D');
		$fecha->add($intervalo);
		$user = $this->session->userdata();
		$config['upload_path']          = './assets/uploads/precios/';
        $config['allowed_types']        = 'xlsx|xls';
        $config['max_size']             = 100;
        $config['max_width']            = 1024;
        $config['max_height']           = 768;
        $config['max_height']           = 768;
        

        $this->load->library('upload', $config);
        $this->upload->do_upload('file_precios');
		$this->load->library("excelfile");
		ini_set("memory_limit", "-1");
		ini_set("max_execution_time", "-1");
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
						"fecha_registro"		=>	$fecha->format('Y-m-d H:i:s')
					];
					$precios = $this->pre_mdl->get("id_precio",['id_producto'=> $productos->id_producto, 'WEEKOFYEAR(fecha_registro)' => $this->weekNumber()])[0];
					if(sizeof($precios) > 0 ){
						$data['cotizacion']=$this->pre_mdl->update($new_precios,
						['WEEKOFYEAR(fecha_registro)' => $this->weekNumber($fecha->format('Y-m-d H:i:s')),'id_precio'=>$precios->id_precio]);
					}else{
						$data['cotizacion']=$this->pre_mdl->insert($new_precios);
					}
					
					
				}
			}
		}
		$cambios = [
				"id_usuario" => $user["id_usuario"],
				"fecha_cambio" => date('Y-m-d H:i:s'),
				"antes" => getHostByName(getHostName()),
				"despues" => "El usuario subio precios de sistema y precio 4"];
		$data['cambios'] = $this->cambio_md->insert($cambios);
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
		$fecha = new DateTime(date('Y-m-d H:i:s'));
		$intervalo = new DateInterval('P2D');
		$fecha->add($intervalo);
		ini_set("memory_limit", "-1");
		$search = ["productos.nombre", "cotizaciones.precio", "cotizaciones.observaciones"];
		$columns = "usuarios.id_usuario, productos.id_producto, productos.nombre AS producto, cotizaciones.precio AS precio, cotizaciones.observaciones";
		$joins = [
			["table"	=>	"usuarios",			"ON"	=>	"cotizaciones.id_proveedor = usuarios.id_usuario",	"clausula"	=>	"INNER"],
			["table"	=>	"productos",		"ON"	=>	"cotizaciones.id_producto = productos.id_producto",	"clausula"	=>	"INNER"]
		];
		$where = [
				["clausula"	=>	"WEEKOFYEAR(cotizaciones.fecha_registro)",	"valor"	=>	$this->weekNumber($fecha->format('Y-m-d H:i:s'))],
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
			UPPER(proveedor_first.nombre) AS proveedor_first,
			ctz_first.precio AS precio_firsto,
			IF((ctz_first.precio_promocion >0), ctz_first.precio_promocion, ctz_first.precio) AS precio_first,
			ctz_first.nombre AS promocion_first,
			ctz_first.observaciones AS observaciones_first,
			UPPER(proveedor_next.nombre) AS proveedor_next,
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

	

	public function getProveedorCot($ides){
		$data["cotizaciones"] =  $this->ct_mdl->getAnterior(['cotizaciones.id_proveedor'=>$ides,'WEEKOFYEAR(cotizaciones.fecha_registro)' => ($this->weekNumber()-1)]);
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

	public function fill_formato1(){
		$flag = 1;
		$flag1 = 1;

		$array = "";
		$array2 = "";
		$filenam = "";
		$id_proves = $this->input->post('id_proves4');
		$proves = $this->input->post('id_proves2');
		$prs = substr($id_proves,0,6);
		if ($prs === "VARIOS") {
			$array = $this->usua_mdl->get(NULL, ["conjunto" => $id_proves]);
			$filenam = $id_proves;
		}elseif ($id_proves === "VOLUMEN" || $id_proves === "AMARILLOS") {
			$filenam = $id_proves;
			$array = (object)['0'=>(object)['nombre' => $id_proves]];
		}else{
			$array = $this->user_md->get(NULL, ["id_usuario" => $id_proves]);
			$filenam = $array[0]->nombre;
		}

		
		
		ini_set("memory_limit", "-1");
		$this->load->library("excelfile");
		$hoja1 = $this->excelfile->setActiveSheetIndex(0);

		$this->excelfile->setActiveSheetIndex(0)->setTitle("EXISTENCIAS");
		
		$this->excelfile->createSheet();
        $hoja = $this->excelfile->setActiveSheetIndex(1);
        $hoja->setTitle("PEDIDO");

		$styleArray = array(
		  'borders' => array(
		    'allborders' => array(
		      'style' => PHPExcel_Style_Border::BORDER_THIN
		    )
		  )
		);

		$hoja->getColumnDimension('A')->setWidth("20");
		$hoja->getColumnDimension('B')->setWidth("70");
		$hoja->getColumnDimension('C')->setWidth("15");
		$hoja->getColumnDimension('D')->setWidth("15");
		$hoja->getColumnDimension('E')->setWidth("15");
		$hoja->getColumnDimension('F')->setWidth("15");
		
		$hoja1->getColumnDimension('A')->setWidth("6");
		$hoja1->getColumnDimension('B')->setWidth("6");
		$hoja1->getColumnDimension('C')->setWidth("6");
		$hoja1->getColumnDimension('D')->setWidth("25");
		$hoja1->getColumnDimension('E')->setWidth("47");
		if ($id_proves === "VOLUMEN" || $id_proves === "AMARILLOS"){
			$hoja->getColumnDimension('AD')->setWidth("70");
			$hoja->getColumnDimension('H')->setWidth("20");
		}else{
			$hoja->getColumnDimension('AC')->setWidth("70");
			$hoja->getColumnDimension('G')->setWidth("20");
		}
		$flagBorder = 0;
		$flagBorder1 = 1;
		$flagBorder2 = 0;
		$flagBorder3 = 1;
		
		$i = 0;
		if ($array){
			foreach ($array as $key => $value){
			//HOJA EXISTENCIAS
			$i++;
			
			$this->excelfile->setActiveSheetIndex(0);
			if($i > 1){
				$flagBorder = $flag1 ;
				$this->excelfile->getActiveSheet()->getStyle('A'.$flagBorder1.':E'.$flagBorder)->applyFromArray($styleArray);
				$flagBorder1 = $flag1;
			}
			$hoja1->mergeCells('A'.$flag1.':E'.$flag1);
			$this->cellStyle("A".$flag1, "FFFFFF", "000000", TRUE, 12, "Franklin Gothic Book");
			$hoja1->setCellValue("A".$flag1."", "GRUPO ABARROTES AZTECA");
			$flag1++;
			$this->cellStyle("A".$flag1.":D".$flag1, "000000", "FFFFFF", TRUE, 12, "Franklin Gothic Book");
			$hoja1->mergeCells('A'.$flag1.':B'.$flag1);
			$hoja1->setCellValue("A".$flag1, "EXISTENCIAS");
			$hoja1->setCellValue("E".$flag1, "PEDIDOS A '".$value->nombre."' ".date("d-m-Y"));
			$this->cellStyle("E".$flag1, "FFFFFF", "000000", TRUE, 12, "Franklin Gothic Book");
			$flag1++;
			$this->cellStyle("A".$flag1.":E".$flag1, "000000", "FFFFFF", TRUE, 12, "Franklin Gothic Book");
			$hoja1->setCellValue("A".$flag1, "CAJAS");
			$hoja1->setCellValue("B".$flag1, "PZAS");
			$hoja1->setCellValue("C".$flag1, "PEDIDO");
			$hoja1->setCellValue("D".$flag1, "COD");
			$hoja1->setCellValue("E".$flag1, "DESCRIPCIÓN");
			$flag1++;

			$this->excelfile->setActiveSheetIndex(1);
			if($i > 0){
				$flagBorder2 = $flag ;
				$this->excelfile->getActiveSheet()->getStyle('A'.$flagBorder3.':AC'.$flagBorder2)->applyFromArray($styleArray);
				$flagBorder3 = $flag;
			}
			//HOJA PEDIDOS
			if ($id_proves === "VOLUMEN" || $id_proves === "AMARILLOS"){
				$this->cellStyle("A".$flag, "FFFFFF", "000000", TRUE, 12, "Franklin Gothic Book");
				$hoja->mergeCells('A'.$flag.':AD'.$flag);
				$flag++;
				$hoja->mergeCells('B'.$flag.':H'.$flag);
				$hoja->mergeCells('I'.$flag.':K'.$flag);
				$hoja->mergeCells('L'.$flag.':N'.$flag);
				$hoja->mergeCells('O'.$flag.':Q'.$flag);
				$hoja->mergeCells('R'.$flag.':T'.$flag);
				$hoja->mergeCells('U'.$flag.':W'.$flag);
				$hoja->mergeCells('X'.$flag.':Z'.$flag);
				$hoja->mergeCells('AA'.$flag.':AC'.$flag);
				$this->cellStyle("B".$flag, "FFFFFF", "000000", TRUE, 12, "Franklin Gothic Book");
				$hoja->setCellValue("B".$flag, "PEDIDOS A '".$value->nombre."' ".date("d-m-Y"));
				$this->cellStyle("I".$flag, "01B0F0", "000000", TRUE, 12, "Franklin Gothic Book");
				$hoja->setCellValue("I".$flag, "ABARROTES");
				$this->cellStyle("L".$flag, "E26C0B", "000000", TRUE, 12, "Franklin Gothic Book");
				$hoja->setCellValue("L".$flag, "TIENDA");
				$this->cellStyle("O".$flag, "C5C5C5", "000000", TRUE, 12, "Franklin Gothic Book");
				$hoja->setCellValue("O".$flag, "ULTRAMARINOS");
				$this->cellStyle("R".$flag, "92D051", "000000", TRUE, 12, "Franklin Gothic Book");
				$hoja->setCellValue("R".$flag, "TRINCHERAS");
				$this->cellStyle("U".$flag, "B1A0C7", "000000", TRUE, 12, "Franklin Gothic Book");
				$hoja->setCellValue("U".$flag, "AZT MERCADO");
				$this->cellStyle("X".$flag, "DA9694", "000000", TRUE, 12, "Franklin Gothic Book");
				$hoja->setCellValue("X".$flag, "TENENCIA");
				$this->cellStyle("AA".$flag, "4CACC6", "000000", TRUE, 12, "Franklin Gothic Book");
				$hoja->setCellValue("AA".$flag, "TIJERAS");
				$this->cellStyle("A3:AD4", "000000", "FFFFFF", TRUE, 12, "Franklin Gothic Book");
				$flag++;
				$this->cellStyle("A".$flag.":AD".$flag."", "000000", "FFFFFF", TRUE, 12, "Franklin Gothic Book");
				$hoja->mergeCells('B'.$flag.':H'.$flag);
				$hoja->mergeCells('I'.$flag.':K'.$flag);
				$hoja->mergeCells('L'.$flag.':N'.$flag);
				$hoja->mergeCells('O'.$flag.':Q'.$flag);
				$hoja->mergeCells('R'.$flag.':T'.$flag);
				$hoja->mergeCells('U'.$flag.':W'.$flag);
				$hoja->mergeCells('X'.$flag.':Z'.$flag);
				$hoja->mergeCells('AA'.$flag.':AC'.$flag);
				$hoja->setCellValue("B".$flag, "DESCRIPCIÓN");
				$hoja->setCellValue("I".$flag, "EXISTENCIAS");
				$hoja->setCellValue("L".$flag, "EXISTENCIAS");
				$hoja->setCellValue("O".$flag, "EXISTENCIAS");
				$hoja->setCellValue("R".$flag, "EXISTENCIAS");
				$hoja->setCellValue("U".$flag, "EXISTENCIAS");
				$hoja->setCellValue("X".$flag, "EXISTENCIAS");
				$hoja->setCellValue("AA".$flag, "EXISTENCIAS");
				$flag++;
				$this->cellStyle("A".$flag.":AD".$flag."", "000000", "FFFFFF", TRUE, 12, "Franklin Gothic Book");
				$hoja->setCellValue("A".$flag, "CODIGO");
				$hoja->setCellValue("C".$flag, "1ER");
				$hoja->setCellValue("D".$flag, "COSTO");
				$hoja->setCellValue("E".$flag, "SISTEMA");
				$hoja->setCellValue("F".$flag, "PRECIO4");
				$hoja->setCellValue("G".$flag, "2DO");
				$hoja->setCellValue("H".$flag, "PROVEEDOR");
				$hoja->setCellValue("I".$flag, "CAJAS");
				$hoja->setCellValue("J".$flag, "PZAS");
				$hoja->setCellValue("K".$flag, "PEDIDO");
				$hoja->setCellValue("L".$flag, "CAJAS");
				$hoja->setCellValue("M".$flag, "PZAS");
				$hoja->setCellValue("N".$flag, "PEDIDO");
				$hoja->setCellValue("O".$flag, "CAJAS");
				$hoja->setCellValue("P".$flag, "PZAS");
				$hoja->setCellValue("Q".$flag, "PEDIDO");
				$hoja->setCellValue("R".$flag, "CAJAS");
				$hoja->setCellValue("S".$flag, "PZAS");
				$hoja->setCellValue("T".$flag, "PEDIDO");
				$hoja->setCellValue("U".$flag, "CAJAS");
				$hoja->setCellValue("V".$flag, "PZAS");
				$hoja->setCellValue("W".$flag, "PEDIDO");
				$hoja->setCellValue("X".$flag, "CAJAS");
				$hoja->setCellValue("Y".$flag, "PZAS");
				$hoja->setCellValue("Z".$flag, "PEDIDO");
				$hoja->setCellValue("AA".$flag, "CAJAS");
				$hoja->setCellValue("AB".$flag, "PZAS");
				$hoja->setCellValue("AC".$flag, "PEDIDO");
				$hoja->setCellValue("AD".$flag, "PROMOCION");
			}else{
				$hoja->setCellValue("A".$flag."", "ABARROTES, TIENDA, ULTRAMARINOS, TRINCHERAS, MERCADO, TIJERAS, Y TENENCIA AZTECA AUTOSERVICIOS SA. DE CV.");
				$hoja->mergeCells('A'.$flag.':AC'.$flag);
				$flag++;
				$hoja->mergeCells('B'.$flag.':G'.$flag);
				$hoja->mergeCells('H'.$flag.':J'.$flag);
				$hoja->mergeCells('K'.$flag.':M'.$flag);
				$hoja->mergeCells('N'.$flag.':P'.$flag);
				$hoja->mergeCells('Q'.$flag.':S'.$flag);
				$hoja->mergeCells('T'.$flag.':V'.$flag);
				$hoja->mergeCells('W'.$flag.':Y'.$flag);
				$hoja->mergeCells('Z'.$flag.':AB'.$flag);
				$this->cellStyle("B".$flag, "FFFFFF", "000000", TRUE, 12, "Franklin Gothic Book");
				$hoja->setCellValue("B".$flag, "PEDIDOS A '".$value->nombre."' ".date("d-m-Y"));
				$this->cellStyle("H".$flag, "01B0F0", "000000", TRUE, 12, "Franklin Gothic Book");
				$hoja->setCellValue("H".$flag, "ABARROTES");
				$this->cellStyle("K".$flag, "E26C0B", "000000", TRUE, 12, "Franklin Gothic Book");
				$hoja->setCellValue("K".$flag, "TIENDA");
				$this->cellStyle("N".$flag, "C5C5C5", "000000", TRUE, 12, "Franklin Gothic Book");
				$hoja->setCellValue("N".$flag, "ULTRAMARINOS");
				$this->cellStyle("Q".$flag, "92D051", "000000", TRUE, 12, "Franklin Gothic Book");
				$hoja->setCellValue("Q".$flag, "TRINCHERAS");
				$this->cellStyle("T".$flag, "B1A0C7", "000000", TRUE, 12, "Franklin Gothic Book");
				$hoja->setCellValue("T".$flag, "AZT MERCADO");
				$this->cellStyle("W".$flag, "DA9694", "000000", TRUE, 12, "Franklin Gothic Book");
				$hoja->setCellValue("W".$flag, "TENENCIA");
				$this->cellStyle("Z".$flag, "4CACC6", "000000", TRUE, 12, "Franklin Gothic Book");
				$hoja->setCellValue("Z".$flag, "TIJERAS");
				$this->cellStyle("A3:AC4", "000000", "FFFFFF", TRUE, 12, "Franklin Gothic Book");
				$flag++;
				$this->cellStyle("A".$flag.":AC".$flag."", "000000", "FFFFFF", TRUE, 12, "Franklin Gothic Book");
				$hoja->setCellValue("B".$flag, "DESCRIPCIÓN");
				$hoja->mergeCells('H'.$flag.':J'.$flag);
				$hoja->setCellValue("H".$flag, "EXISTENCIAS");
				$hoja->mergeCells('K'.$flag.':M'.$flag);
				$hoja->setCellValue("K".$flag, "EXISTENCIAS");
				$hoja->mergeCells('N'.$flag.':P'.$flag);
				$hoja->setCellValue("N".$flag, "EXISTENCIAS");
				$hoja->mergeCells('Q'.$flag.':S'.$flag);
				$hoja->setCellValue("Q".$flag, "EXISTENCIAS");
				$hoja->mergeCells('T'.$flag.':V'.$flag);
				$hoja->setCellValue("T".$flag, "EXISTENCIAS");
				$hoja->mergeCells('W'.$flag.':Y'.$flag);
				$hoja->setCellValue("W".$flag, "EXISTENCIAS");
				$hoja->mergeCells('W'.$flag.':Y'.$flag);
				$hoja->setCellValue("W".$flag, "EXISTENCIAS");
				$flag++;
				$this->cellStyle("A".$flag.":AC".$flag."", "000000", "FFFFFF", TRUE, 12, "Franklin Gothic Book");
				$hoja->setCellValue("A".$flag, "CODIGO");
				$hoja->setCellValue("C".$flag, "COSTO");
				$hoja->setCellValue("D".$flag, "SISTEMA");
				$hoja->setCellValue("E".$flag, "PRECIO4");
				$hoja->setCellValue("F".$flag, "2DO");
				$hoja->setCellValue("G".$flag, "PROVEEDOR");
				$hoja->setCellValue("H".$flag, "CAJAS");
				$hoja->setCellValue("I".$flag, "PZAS");
				$hoja->setCellValue("J".$flag, "PEDIDO");
				$hoja->setCellValue("K".$flag, "CAJAS");
				$hoja->setCellValue("L".$flag, "PZAS");
				$hoja->setCellValue("M".$flag, "PEDIDO");
				$hoja->setCellValue("N".$flag, "CAJAS");
				$hoja->setCellValue("O".$flag, "PZAS");
				$hoja->setCellValue("P".$flag, "PEDIDO");
				$hoja->setCellValue("Q".$flag, "CAJAS");
				$hoja->setCellValue("R".$flag, "PZAS");
				$hoja->setCellValue("S".$flag, "PEDIDO");
				$hoja->setCellValue("T".$flag, "CAJAS");
				$hoja->setCellValue("U".$flag, "PZAS");
				$hoja->setCellValue("V".$flag, "PEDIDO");
				$hoja->setCellValue("W".$flag, "CAJAS");
				$hoja->setCellValue("X".$flag, "PZAS");
				$hoja->setCellValue("Y".$flag, "PEDIDO");
				$hoja->setCellValue("Z".$flag, "CAJAS");
				$hoja->setCellValue("AA".$flag, "PZAS");
				$hoja->setCellValue("AB".$flag, "PEDIDO");
				$hoja->setCellValue("AC".$flag, "PROMOCION");
			}
			$fecha = new DateTime(date('Y-m-d H:i:s'));
			$intervalo = new DateInterval('P2D');
			$fecha->add($intervalo);

			if ($value->nombre === "AMARILLOS") {
				$where=["WEEKOFYEAR(cotizaciones.fecha_registro)" => $this->weekNumber( $fecha->format('Y-m-d H:i:s')),"prod.estatus" => 3];//Semana actual
			}elseif ($value->nombre === "VOLUMEN" ) {
				$where=["WEEKOFYEAR(cotizaciones.fecha_registro)" => $this->weekNumber( $fecha->format('Y-m-d H:i:s')),"prod.estatus" => 2];//Semana actual
			}else{
				$where=["WEEKOFYEAR(cotizaciones.fecha_registro)" => $this->weekNumber( $fecha->format('Y-m-d H:i:s')),"ctz_first.id_proveedor" => $value->id_usuario,"prod.estatus" => 1];//Semana actual
			}
			$fecha = new DateTime(date('Y-m-d H:i:s'));
			$intervalo = new DateInterval('P2D');
			$fecha->add($intervalo);
			$cotizacionesProveedor = $this->ct_mdl->getPedidosAll($where, $fecha->format('Y-m-d H:i:s'), 0);
			$difff = 0.01;
			$flag2 = 3;
			$flage = 5;

			if ($cotizacionesProveedor){
				foreach ($cotizacionesProveedor as $key => $value){
					//Existencias

					$this->excelfile->setActiveSheetIndex(0);
					$this->cellStyle("E".$flag1, "000000", "FFFFFF", FALSE, 12, "Franklin Gothic Book");
					$hoja1->setCellValue("E".$flag1, $value['familia']);
					$flag1 +=1;
					
					//Pedidos
					$this->excelfile->setActiveSheetIndex(1);
					$this->cellStyle("B".$flag, "000000", "FFFFFF", FALSE, 12, "Franklin Gothic Book");
					$hoja->setCellValue("B{$flag}", $value['familia']);
					$flag +=1;
					if ($value['articulos']) {
						foreach ($value['articulos'] as $key => $row){
							//Existencias
							$this->excelfile->setActiveSheetIndex(0);
							$this->cellStyle("A".$flag1.":E".$flag1, "FFFFFF", "000000", FALSE, 12, "Franklin Gothic Book");
							$hoja1->setCellValue("D{$flag}", $row['codigo'])->getStyle("D{$flag}")->getNumberFormat()->setFormatCode('# ???/???');//Formato de fraccion
							if($row['color'] == '#92CEE3'){
								$this->cellStyle("D{$flag}", "92CEE3", "000000", FALSE, 12, "Franklin Gothic Book");
							}else{
								$this->cellStyle("D{$flag}", "FFFFFF", "000000", FALSE, 12, "Franklin Gothic Book");
							}
							$hoja1->setCellValue("E{$flag}", $row['producto']);
							//Pedidos
							$this->excelfile->setActiveSheetIndex(1);
							$this->cellStyle("A".$flag.":AC".$flag."", "FFFFFF", "000000", FALSE, 12, "Franklin Gothic Book");
							$hoja->setCellValue("A{$flag}", $row['codigo'])->getStyle("A{$flag}")->getNumberFormat()->setFormatCode('# ???/???');//Formato de fraccion
							if($row['color'] == '#92CEE3'){
								$this->cellStyle("A{$flag}", "92CEE3", "000000", FALSE, 12, "Franklin Gothic Book");
							}else{
								$this->cellStyle("A{$flag}", "FFFFFF", "000000", FALSE, 12, "Franklin Gothic Book");
							}
							$hoja->setCellValue("B{$flag}", $row['producto']);
							if ($id_proves === "VOLUMEN" || $id_proves === "AMARILLOS") {
								$hoja->setCellValue("C{$flag}", $row['proveedor_first']);
								if($row['precio_sistema'] < $row['precio_first']){
									$hoja->setCellValue("D{$flag}", $row['precio_first'])->getStyle("D{$flag}")->getNumberFormat()->setFormatCode('"$"#,##0.00_-');
									$this->cellStyle("D{$flag}", "FDB2B2", "E21111", FALSE, 12, "Franklin Gothic Book");
									$this->cellStyle("D{$flag}", "FDB2B2", "E21111", FALSE, 12, "Franklin Gothic Book");
									$this->cellStyle("B{$flag}", "E21600", "000000", FALSE, 12, "Franklin Gothic Book");
								}else{
									$hoja->setCellValue("D{$flag}", $row['precio_first'])->getStyle("D{$flag}")->getNumberFormat()->setFormatCode('"$"#,##0.00_-');
									$this->cellStyle("D{$flag}", "96EAA8", "0C800C", FALSE, 12, "Franklin Gothic Book");
									$this->cellStyle("B{$flag}", "249947", "000000", FALSE, 12, "Franklin Gothic Book");
								}
								
								$hoja->setCellValue("E{$flag}", $row['precio_sistema'])->getStyle("E{$flag}")->getNumberFormat()->setFormatCode('"$"#,##0.00_-');//Formto de moneda
								$this->cellStyle("E".$flag, "FFFFFF","000000",  FALSE, 12, "Franklin Gothic Book");
								if($row['colorp'] == 1){
									$this->cellStyle("E{$flag}", "D6DCE4", "000000", FALSE, 12, "Franklin Gothic Book");
								}else{
									$this->cellStyle("E{$flag}", "FFFFFF", "000000", FALSE, 12, "Franklin Gothic Book");
								}
								$hoja->setCellValue("F{$flag}", $row['precio_four'])->getStyle("F{$flag}")->getNumberFormat()->setFormatCode('"$"#,##0.00_-');
								$this->cellStyle("F{$flag}", "FFFFFF", "000000", FALSE, 12, "Franklin Gothic Book");
								if($row['precio_sistema'] < $row['precio_next']){
									$hoja->setCellValue("G{$flag}", $row['precio_next'])->getStyle("G{$flag}")->getNumberFormat()->setFormatCode('"$"#,##0.00_-');
									$this->cellStyle("G{$flag}", "FDB2B2", "E21111", FALSE, 12, "Franklin Gothic Book");
								}else if($row['precio_next'] !== NULL){
									$hoja->setCellValue("G{$flag}", $row['precio_next'])->getStyle("G{$flag}")->getNumberFormat()->setFormatCode('"$"#,##0.00_-');
									$this->cellStyle("G{$flag}", "96EAA8", "0C800C", FALSE, 12, "Franklin Gothic Book");
								}else{
									$hoja->setCellValue("G{$flag}", $row['precio_next'])->getStyle("G{$flag}")->getNumberFormat()->setFormatCode('"$"#,##0.00_-');
									$this->cellStyle("G{$flag}", "FFFFFF", "000000", FALSE, 12, "Franklin Gothic Book");
								}
								
								$hoja->setCellValue("H{$flag}", $row['proveedor_next']);
								$this->cellStyle("H".$flag, "FFFFFF", "000000", FALSE, 12, "Franklin Gothic Book");
								$hoja->setCellValue("I{$flag}", $row['caja0']);
								$hoja->setCellValue("J{$flag}", $row['pz0']);
								$hoja->setCellValue("K{$flag}", $row['ped0']);
								$this->cellStyle("K{$flag}", "D4EAEF", "000000", FALSE, 12, "Franklin Gothic Book");
								$hoja->setCellValue("L{$flag}", $row['caja1']);
								$hoja->setCellValue("M{$flag}", $row['pz1']);
								$hoja->setCellValue("N{$flag}", $row['ped1']);
								$this->cellStyle("N{$flag}", "D4EAEF", "000000", FALSE, 12, "Franklin Gothic Book");
								$hoja->setCellValue("O{$flag}", $row['caja2']);
								$hoja->setCellValue("P{$flag}", $row['pz2']);
								$hoja->setCellValue("Q{$flag}", $row['ped2']);
								$this->cellStyle("Q{$flag}", "D4EAEF", "000000", FALSE, 12, "Franklin Gothic Book");
								$hoja->setCellValue("R{$flag}", $row['caja3']);
								$hoja->setCellValue("S{$flag}", $row['pz3']);
								$hoja->setCellValue("T{$flag}", $row['ped3']);
								$this->cellStyle("T{$flag}", "D4EAEF", "000000", FALSE, 12, "Franklin Gothic Book");
								$hoja->setCellValue("U{$flag}", $row['caja4']);
								$hoja->setCellValue("V{$flag}", $row['pz4']);
								$hoja->setCellValue("W{$flag}", $row['ped4']);
								$this->cellStyle("W{$flag}", "D4EAEF", "000000", FALSE, 12, "Franklin Gothic Book");
								$hoja->setCellValue("X{$flag}", $row['caja5']);
								$hoja->setCellValue("Y{$flag}", $row['pz5']);
								$hoja->setCellValue("Z{$flag}", $row['ped5']);
								$this->cellStyle("Z{$flag}", "D4EAEF", "000000", FALSE, 12, "Franklin Gothic Book");
								$hoja->setCellValue("AA{$flag}", $row['caja6']);
								$hoja->setCellValue("AB{$flag}", $row['pz6']);
								$hoja->setCellValue("AC{$flag}", $row['ped6']);
								$this->cellStyle("AC{$flag}", "D4EAEF", "000000", FALSE, 12, "Franklin Gothic Book");
								$hoja->setCellValue("AD{$flag}", $row['promocion_first']);
								$hoja->setCellValue("AE{$flag}", "=D".$flag."*K".$flag)->getStyle("AE{$flag}")->getNumberFormat()->setFormatCode('"$"#,##0.00_-');
								$hoja->setCellValue("AF{$flag}", "=D".$flag."*N".$flag)->getStyle("AF{$flag}")->getNumberFormat()->setFormatCode('"$"#,##0.00_-');
								$hoja->setCellValue("AG{$flag}", "=D".$flag."*Q".$flag)->getStyle("AG{$flag}")->getNumberFormat()->setFormatCode('"$"#,##0.00_-');
								$hoja->setCellValue("AH{$flag}", "=D".$flag."*T".$flag)->getStyle("AH{$flag}")->getNumberFormat()->setFormatCode('"$"#,##0.00_-');
								$hoja->setCellValue("AI{$flag}", "=D".$flag."*W".$flag)->getStyle("AI{$flag}")->getNumberFormat()->setFormatCode('"$"#,##0.00_-');
								$hoja->setCellValue("AJ{$flag}", "=D".$flag."*Z".$flag)->getStyle("AJ{$flag}")->getNumberFormat()->setFormatCode('"$"#,##0.00_-');
								$hoja->setCellValue("AK{$flag}", "=D".$flag."*AC".$flag)->getStyle("AK{$flag}")->getNumberFormat()->setFormatCode('"$"#,##0.00_-');
							}else{
								if($row['precio_sistema'] < $row['precio_first']){
									$hoja->setCellValue("C{$flag}", $row['precio_first'])->getStyle("C{$flag}")->getNumberFormat()->setFormatCode('"$"#,##0.00_-');
									$this->cellStyle("C{$flag}", "FDB2B2", "E21111", FALSE, 12, "Franklin Gothic Book");
									$this->cellStyle("C{$flag}", "FDB2B2", "E21111", FALSE, 12, "Franklin Gothic Book");
									$this->cellStyle("B{$flag}", "E21600", "000000", FALSE, 12, "Franklin Gothic Book");
								}else{
									$hoja->setCellValue("C{$flag}", $row['precio_first'])->getStyle("C{$flag}")->getNumberFormat()->setFormatCode('"$"#,##0.00_-');
									$this->cellStyle("C{$flag}", "96EAA8", "0C800C", FALSE, 12, "Franklin Gothic Book");
									$this->cellStyle("B{$flag}", "249947", "000000", FALSE, 12, "Franklin Gothic Book");
								}
								$hoja->setCellValue("D{$flag}", $row['precio_sistema'])->getStyle("D{$flag}")->getNumberFormat()->setFormatCode('"$"#,##0.00_-');//Formto de moneda
								$this->cellStyle("D".$flag, "FFFFFF","000000",  FALSE, 12, "Franklin Gothic Book");
								if($row['colorp'] == 1){
									$this->cellStyle("D{$flag}", "D6DCE4", "000000", FALSE, 12, "Franklin Gothic Book");
								}else{
									$this->cellStyle("D{$flag}", "FFFFFF", "000000", FALSE, 12, "Franklin Gothic Book");
								}
								$hoja->setCellValue("E{$flag}", $row['precio_four'])->getStyle("E{$flag}")->getNumberFormat()->setFormatCode('"$"#,##0.00_-');
								$this->cellStyle("E{$flag}", "FFFFFF", "000000", FALSE, 12, "Franklin Gothic Book");
								if($row['precio_sistema'] < $row['precio_next']){
									$hoja->setCellValue("F{$flag}", $row['precio_next'])->getStyle("F{$flag}")->getNumberFormat()->setFormatCode('"$"#,##0.00_-');
									$this->cellStyle("F{$flag}", "FDB2B2", "E21111", FALSE, 12, "Franklin Gothic Book");
								}else if($row['precio_next'] !== NULL){
									$hoja->setCellValue("F{$flag}", $row['precio_next'])->getStyle("F{$flag}")->getNumberFormat()->setFormatCode('"$"#,##0.00_-');
									$this->cellStyle("F{$flag}", "96EAA8", "0C800C", FALSE, 12, "Franklin Gothic Book");
								}else{
									$hoja->setCellValue("F{$flag}", $row['precio_next'])->getStyle("F{$flag}")->getNumberFormat()->setFormatCode('"$"#,##0.00_-');
									$this->cellStyle("F{$flag}", "FFFFFF", "000000", FALSE, 12, "Franklin Gothic Book");
								}
								
								$hoja->setCellValue("G{$flag}", $row['proveedor_next']);
								$this->cellStyle("G".$flag, "FFFFFF", "000000", FALSE, 12, "Franklin Gothic Book");
								$hoja->setCellValue("H{$flag}", $row['caja0']);
								$hoja->setCellValue("I{$flag}", $row['pz0']);
								$hoja->setCellValue("J{$flag}", $row['ped0']);
								$this->cellStyle("J{$flag}", "D4EAEF", "000000", FALSE, 12, "Franklin Gothic Book");
								$hoja->setCellValue("K{$flag}", $row['caja1']);
								$hoja->setCellValue("L{$flag}", $row['pz1']);
								$hoja->setCellValue("M{$flag}", $row['ped1']);
								$this->cellStyle("M{$flag}", "D4EAEF", "000000", FALSE, 12, "Franklin Gothic Book");
								$hoja->setCellValue("N{$flag}", $row['caja2']);
								$hoja->setCellValue("O{$flag}", $row['pz2']);
								$hoja->setCellValue("P{$flag}", $row['ped2']);
								$this->cellStyle("P{$flag}", "D4EAEF", "000000", FALSE, 12, "Franklin Gothic Book");
								$hoja->setCellValue("Q{$flag}", $row['caja3']);
								$hoja->setCellValue("R{$flag}", $row['pz3']);
								$hoja->setCellValue("S{$flag}", $row['ped3']);
								$this->cellStyle("S{$flag}", "D4EAEF", "000000", FALSE, 12, "Franklin Gothic Book");
								$hoja->setCellValue("T{$flag}", $row['caja4']);
								$hoja->setCellValue("U{$flag}", $row['pz4']);
								$hoja->setCellValue("V{$flag}", $row['ped4']);
								$this->cellStyle("V{$flag}", "D4EAEF", "000000", FALSE, 12, "Franklin Gothic Book");
								$hoja->setCellValue("W{$flag}", $row['caja5']);
								$hoja->setCellValue("X{$flag}", $row['pz5']);
								$hoja->setCellValue("Y{$flag}", $row['ped5']);
								$this->cellStyle("Y{$flag}", "D4EAEF", "000000", FALSE, 12, "Franklin Gothic Book");
								$hoja->setCellValue("Z{$flag}", $row['caja6']);
								$hoja->setCellValue("AA{$flag}", $row['pz6']);
								$hoja->setCellValue("AB{$flag}", $row['ped6']);
								$this->cellStyle("AB{$flag}", "D4EAEF", "000000", FALSE, 12, "Franklin Gothic Book");
								$hoja->setCellValue("AC{$flag}", $row['promocion_first']);
								$hoja->setCellValue("AD{$flag}", "=C".$flag."*J".$flag)->getStyle("AD{$flag}")->getNumberFormat()->setFormatCode('"$"#,##0.00_-');
								$hoja->setCellValue("AE{$flag}", "=C".$flag."*M".$flag)->getStyle("AE{$flag}")->getNumberFormat()->setFormatCode('"$"#,##0.00_-');
								$hoja->setCellValue("AF{$flag}", "=C".$flag."*P".$flag)->getStyle("AF{$flag}")->getNumberFormat()->setFormatCode('"$"#,##0.00_-');
								$hoja->setCellValue("AG{$flag}", "=C".$flag."*S".$flag)->getStyle("AG{$flag}")->getNumberFormat()->setFormatCode('"$"#,##0.00_-');
								$hoja->setCellValue("AH{$flag}", "=C".$flag."*V".$flag)->getStyle("AH{$flag}")->getNumberFormat()->setFormatCode('"$"#,##0.00_-');
								$hoja->setCellValue("AI{$flag}", "=C".$flag."*Y".$flag)->getStyle("AI{$flag}")->getNumberFormat()->setFormatCode('"$"#,##0.00_-');
								$hoja->setCellValue("AJ{$flag}", "=C".$flag."*AB".$flag)->getStyle("AJ{$flag}")->getNumberFormat()->setFormatCode('"$"#,##0.00_-');
							}
							$border_style= array('borders' => array('right' => array('style' => 
								PHPExcel_Style_Border::BORDER_THIN,'color' => array('argb' => '000000'),)));
							$hoja->getStyle("A{$flag}:G{$flag}")
					                 ->getAlignment()
					                 ->setHorizontal(\PHPExcel_Style_Alignment::HORIZONTAL_LEFT);
							$flag ++;
							$flag1 ++;
						}
					}
				}
			}
			$flag++;
			$flag1++;
			$flag1++;
			$flag1++;
			$flag++;
			$flag++;
			if ($id_proves === "VOLUMEN" || $id_proves === "AMARILLOS"){
				$flagf = $flag - 3;
				$flagfs = $flag - 4;
				$hoja->setCellValue("AE{$flagf}", "=SUMA(AE5:AE".$flagfs.")")->getStyle("AE{$flag}")->getNumberFormat()->setFormatCode('"$"#,##0.00_-');
				$hoja->setCellValue("AF{$flagf}", "=SUMA(AF5:AF".$flagfs.")")->getStyle("AF{$flag}")->getNumberFormat()->setFormatCode('"$"#,##0.00_-');
				$hoja->setCellValue("AG{$flagf}", "=SUMA(AG5:AG".$flagfs.")")->getStyle("AG{$flag}")->getNumberFormat()->setFormatCode('"$"#,##0.00_-');
				$hoja->setCellValue("AH{$flagf}", "=SUMA(AH5:AH".$flagfs.")")->getStyle("AH{$flag}")->getNumberFormat()->setFormatCode('"$"#,##0.00_-');
				$hoja->setCellValue("AI{$flagf}", "=SUMA(AI5:AI".$flagfs.")")->getStyle("AI{$flag}")->getNumberFormat()->setFormatCode('"$"#,##0.00_-');
				$hoja->setCellValue("AJ{$flagf}", "=SUMA(AJ5:AJ".$flagfs.")")->getStyle("AJ{$flag}")->getNumberFormat()->setFormatCode('"$"#,##0.00_-');
				$hoja->setCellValue("AK{$flagf}", "=SUMA(AK5:AK".$flagfs.")")->getStyle("AK{$flag}")->getNumberFormat()->setFormatCode('"$"#,##0.00_-');
			}else{
				$flagf = $flag - 3;
				$flagfs = $flag - 4;
				$hoja->setCellValue("AD{$flagf}", "=SUMA(AD5:AD".$flagfs.")")->getStyle("AD{$flag}")->getNumberFormat()->setFormatCode('"$"#,##0.00_-');
				$hoja->setCellValue("AE{$flagf}", "=SUMA(AE5:AE".$flagfs.")")->getStyle("AE{$flag}")->getNumberFormat()->setFormatCode('"$"#,##0.00_-');
				$hoja->setCellValue("AF{$flagf}", "=SUMA(AF5:AF".$flagfs.")")->getStyle("AF{$flag}")->getNumberFormat()->setFormatCode('"$"#,##0.00_-');
				$hoja->setCellValue("AG{$flagf}", "=SUMA(AG5:AG".$flagfs.")")->getStyle("AG{$flag}")->getNumberFormat()->setFormatCode('"$"#,##0.00_-');
				$hoja->setCellValue("AH{$flagf}", "=SUMA(AH5:AH".$flagfs.")")->getStyle("AH{$flag}")->getNumberFormat()->setFormatCode('"$"#,##0.00_-');
				$hoja->setCellValue("AI{$flagf}", "=SUMA(AI5:AI".$flagfs.")")->getStyle("AI{$flag}")->getNumberFormat()->setFormatCode('"$"#,##0.00_-');
				$hoja->setCellValue("AJ{$flagf}", "=SUMA(AJ5:AJ".$flagfs.")")->getStyle("AJ{$flag}")->getNumberFormat()->setFormatCode('"$"#,##0.00_-');
				$flage = $flag + 5;
			}
		
			}
		}
		$this->excelfile->setActiveSheetIndex(1);
			if($flagBorder2 == 0){
				$flagBorder2 = $flag ;
				$this->excelfile->getActiveSheet()->getStyle('A1:AC'.$flagBorder2)->applyFromArray($styleArray);
				$this->excelfile->getActiveSheet()->getStyle('A'.$flagBorder3.':AC'.$flagBorder2)->applyFromArray($styleArray);
			}
		$this->excelfile->setActiveSheetIndex(0);
			if($flagBorder == 0){
				$flagBorder = $flag1 ;
				$this->excelfile->getActiveSheet()->getStyle('A1:E'.$flagBorder)->applyFromArray($styleArray);
				$this->excelfile->getActiveSheet()->getStyle('A'.$flagBorder1.':E'.$flagBorder)->applyFromArray($styleArray);
			}
		

		$file_name = "PEDIDO ".$filenam." ".date('d-m-Y').".xlsx"; //Nombre del documento con extención
		header("Content-Type: application/vnd.ms-excel; charset=utf-8");
		header("Content-Disposition: attachment;filename=".$file_name);
		header("Cache-Control: max-age=0");
		$excel_Writer = PHPExcel_IOFactory::createWriter($this->excelfile, "Excel2007");

		$excel_Writer->save("php://output");
		/*$excel_Writer = new PHPExcel_Writer_Excel2007($this->excelfile);
		$excel_Writer->setOffice2003Compatibility(true);
		$excel_Writer->save("php://output");*/
	}

	public function archivo_precios(){
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
		
		$hoja->setCellValue("B1", "DESCRIPCIÓN")->getColumnDimension('B')->setWidth(70);
		$hoja->setCellValue("C1", "SISTEMA")->getColumnDimension('C')->setWidth(15);
		$hoja->setCellValue("D1", "PRECIO 4")->getColumnDimension('D')->setWidth(50);

		$hoja->setCellValue("A2", "CÓDIGO")->getColumnDimension('A')->setWidth(30); //Nombre y ajuste de texto a la columna

		$productos = $this->prod_mdl->getProdFamS(NULL);
		$row_print = 2;
		if ($productos){
			foreach ($productos as $key => $value){
				$hoja->setCellValue("B{$row_print}", $value['familia']);
				$this->cellStyle("B{$row_print}", "000000", "FFFFFF", TRUE, 12, "Franklin Gothic Book");
				$hoja->setCellValue("B{$row_print}", $value['familia']);
				$row_print +=1;
				if ($value['articulos']) {
					foreach ($value['articulos'] as $key => $row){
						if($row['color'] == '#92CEE3'){
							$this->cellStyle("A{$row_print}", "92CEE3", "000000", FALSE, 10, "Franklin Gothic Book");
						}else{
							$this->cellStyle("A{$row_print}", "FFFFFF", "000000", FALSE, 10, "Franklin Gothic Book");
						}
						$hoja->setCellValue("A{$row_print}", $row['codigo'])->getStyle("A{$row_print}")->getNumberFormat()->setFormatCode('# ???/???');//Formato de fraccion
						$hoja->getStyle("A{$row_print}")->applyFromArray($border_style);
						$hoja->setCellValue("B{$row_print}", $row['producto']);
						if($row['estatus'] == 2){
							$this->cellStyle("B{$row_print}", "00B0F0", "000000", FALSE, 10, "Franklin Gothic Book");
						}
						if($row['estatus'] == 3){
							$this->cellStyle("B{$row_print}", "FFF900", "000000", FALSE, 10, "Franklin Gothic Book");
						}
						
						$hoja->getStyle("B{$row_print}")->applyFromArray($border_style);
						if($row['colorp'] == 1){
							$this->cellStyle("C{$row_print}", "D6DCE4", "000000", FALSE, 10, "Franklin Gothic Book");
						}else{
							$this->cellStyle("C{$row_print}", "FFFFFF", "000000", FALSE, 10, "Franklin Gothic Book");
						}
						$hoja->getStyle("C{$row_print}")->applyFromArray($border_style);
						$hoja->getStyle("D{$row_print}")->applyFromArray($border_style);
						if($this->weekNumber($row['fecha_registro']) >= ($this->weekNumber() - 1)){
							$this->cellStyle("A{$row_print}", "FF7F71", "000000", FALSE, 10, "Franklin Gothic Book");
							$this->cellStyle("B{$row_print}", "FF7F71", "000000", FALSE, 10, "Franklin Gothic Book");
							$this->cellStyle("C{$row_print}", "FF7F71", "000000", TRUE, 10, "Franklin Gothic Book");
							$this->cellStyle("D{$row_print}", "FF7F71", "000000", FALSE, 10, "Franklin Gothic Book");
							$hoja->setCellValue("E{$row_print}", "NUEVO");
						}
						$row_print++;
					}
				}
			}
		}
		$hoja->getStyle("A3:G{$row_print}")
                 ->getAlignment()
                 ->setHorizontal(\PHPExcel_Style_Alignment::HORIZONTAL_LEFT);
		$hoja->getStyle("B3:B{$row_print}")
                 ->getAlignment()
                 ->setHorizontal(\PHPExcel_Style_Alignment::HORIZONTAL_LEFT);

		$file_name = "Formato Precios.xlsx"; //Nombre del documento con extención
		header("Content-Type: application/vnd.ms-excel; charset=utf-8");
		header("Content-Disposition: attachment;filename=".$file_name);
		header("Cache-Control: max-age=0");
		$excel_Writer = PHPExcel_IOFactory::createWriter($this->excelfile, "Excel2007");
		$excel_Writer->save("php://output");
	}

	public function archivo_cotizacion(){
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

		$this->cellStyle("A1:G2", "000000", "FFFFFF", TRUE, 12, "Franklin Gothic Book");
		$border_style= array('borders' => array('right' => array('style' => 
			PHPExcel_Style_Border::BORDER_THIN,'color' => array('argb' => '000000'),)));
		
		$hoja->setCellValue("B1", "DESCRIPCIÓN SISTEMA")->getColumnDimension('B')->setWidth(70);
		$hoja->setCellValue("C1", "PRECIO")->getColumnDimension('C')->setWidth(15);
		$hoja->setCellValue("D1", "PROMOCIÓN")->getColumnDimension('D')->setWidth(50);
		$hoja->setCellValue("E1", "# EN #")->getColumnDimension('E')->setWidth(12);
		$hoja->setCellValue("F1", "# EN #")->getColumnDimension('F')->setWidth(12);
		$hoja->setCellValue("G1", "% DESCUENTO")->getColumnDimension('G')->setWidth(15);

		$hoja->setCellValue("A2", "CÓDIGO")->getColumnDimension('A')->setWidth(30); //Nombre y ajuste de texto a la columna
		$hoja->mergeCells('E1:F1');

		$productos = $this->prod_mdl->getProdFamS(NULL);
		$row_print = 2;
		if ($productos){
			foreach ($productos as $key => $value){
				$hoja->setCellValue("B{$row_print}", $value['familia']);
				$this->cellStyle("B{$row_print}", "000000", "FFFFFF", TRUE, 12, "Franklin Gothic Book");
				$hoja->setCellValue("B{$row_print}", $value['familia']);
				$row_print +=1;
				if ($value['articulos']) {
					foreach ($value['articulos'] as $key => $row){
						if($row['color'] == '#92CEE3'){
							$this->cellStyle("A{$row_print}", "92CEE3", "000000", FALSE, 10, "Franklin Gothic Book");
						}else{
							$this->cellStyle("A{$row_print}", "FFFFFF", "000000", FALSE, 10, "Franklin Gothic Book");
						}
						$hoja->setCellValue("A{$row_print}", $row['codigo'])->getStyle("A{$row_print}")->getNumberFormat()->setFormatCode('# ???/???');//Formato de fraccion
						$hoja->getStyle("A{$row_print}")->applyFromArray($border_style);
						$hoja->setCellValue("B{$row_print}", $row['producto']);
						if($row['estatus'] == 2){
							$this->cellStyle("B{$row_print}", "00B0F0", "000000", FALSE, 10, "Franklin Gothic Book");
						}
						if($row['estatus'] == 3){
							$this->cellStyle("B{$row_print}", "FFF900", "000000", FALSE, 10, "Franklin Gothic Book");
						}
						
						$hoja->getStyle("B{$row_print}")->applyFromArray($border_style);
						if($row['colorp'] == 1){
							$this->cellStyle("C{$row_print}", "D6DCE4", "000000", FALSE, 10, "Franklin Gothic Book");
						}else{
							$this->cellStyle("C{$row_print}", "FFFFFF", "000000", FALSE, 10, "Franklin Gothic Book");
						}
						$hoja->getStyle("C{$row_print}")->applyFromArray($border_style);
						$hoja->getStyle("D{$row_print}")->applyFromArray($border_style);
						$hoja->getStyle("E{$row_print}")->applyFromArray($border_style);
						$hoja->getStyle("F{$row_print}")->applyFromArray($border_style);
						$hoja->getStyle("G{$row_print}")->applyFromArray($border_style);
						if($this->weekNumber($row['fecha_registro']) >= ($this->weekNumber() - 1)){
							$this->cellStyle("A{$row_print}", "FF7F71", "000000", FALSE, 10, "Franklin Gothic Book");
							$this->cellStyle("B{$row_print}", "FF7F71", "000000", FALSE, 10, "Franklin Gothic Book");
							$this->cellStyle("C{$row_print}", "FF7F71", "000000", TRUE, 10, "Franklin Gothic Book");
							$this->cellStyle("D{$row_print}", "FF7F71", "000000", FALSE, 10, "Franklin Gothic Book");
							$this->cellStyle("E{$row_print}", "FF7F71", "000000", FALSE, 10, "Franklin Gothic Book");
							$this->cellStyle("G{$row_print}", "FF7F71", "000000", TRUE, 10, "Franklin Gothic Book");
							$this->cellStyle("F{$row_print}", "FF7F71", "000000", FALSE, 10, "Franklin Gothic Book");
							$hoja->setCellValue("H{$row_print}", "NUEVO");
						}
						$row_print++;
					}
				}
			}
		}
		$hoja->getStyle("A3:H{$row_print}")
                 ->getAlignment()
                 ->setHorizontal(\PHPExcel_Style_Alignment::HORIZONTAL_LEFT);
		$hoja->getStyle("B3:B{$row_print}")
                 ->getAlignment()
                 ->setHorizontal(\PHPExcel_Style_Alignment::HORIZONTAL_LEFT);

        $user = $this->session->userdata();
        $provs = $this->usua_mdl->get(NULL, ['id_usuario'=>$user['id_usuario']])[0];
		$file_name = "Formato Cotizaciones ".$provs->nombre.".xlsx"; //Nombre del documento con extención
		header("Content-Type: application/vnd.ms-excel; charset=utf-8");
		header("Content-Disposition: attachment;filename=".$file_name);
		header("Cache-Control: max-age=0");
		$excel_Writer = PHPExcel_IOFactory::createWriter($this->excelfile, "Excel2007");
		$excel_Writer->save("php://output");
	}

	public function registro_fltnts(){
		$user = $this->session->userdata();
		$size = sizeof($this->input->post('id_producto[]'));
		$cotz = $this->input->post('id_producto[]');
		$no_semanas = $this->input->post('no_semanas[]');
		for($i = 0; $i < $size; $i++){
			if($no_semanas[$i] <> "" && $no_semanas[$i] <> NULL){
				$fecha = new DateTime(date('Y-m-d H:i:s'));
				$intervalo = new DateInterval('P'.$no_semanas[$i].'W');
				$fecha->add($intervalo);
				$fecha->format('Y-m-d H:i:s');
				$antes =  $this->falt_mdl->get(NULL, ['id_producto' => $cotz[$i], 'fecha_termino > ' => date("Y-m-d H:i:s"), 'id_proveedor' => $this->input->post('id_pro')])[0];
				if($antes){
					$cambios = [
						"id_usuario" => $user["id_usuario"],
						"fecha_cambio" => date('Y-m-d H:i:s'),
						"antes" => "id_faltante: ".$antes->id_faltante." /Proveedor: ".$antes->id_proveedor." /Producto:".$antes->id_producto."/F Termino: ".$antes->fecha_termino.
								"/Semanas: ".$antes->no_semanas,
						"despues" => "El usuario cambio las semanas..../Semanas: ".$no_semanas[$i]."/Fecha Termino:".$fecha->format('Y-m-d H:i:s')];
					$data['cambios'] = $this->cambio_md->insert($cambios);
					$data ['id_faltante'] = $this->falt_mdl->update([
						"no_semanas" => $no_semanas[$i],
						"fecha_termino" => $fecha->format('Y-m-d H:i:s')
					], $antes->id_faltante);
					$mensaje = [
						"id" 	=> 'Éxito',
						"desc"	=> 'Faltantes actualizados correctamente',
						"type"	=> 'success'
					];
				}else{
					$cambios = [
						"id_usuario" => $user["id_usuario"],
						"fecha_cambio" => date('Y-m-d H:i:s'),
						"antes" => "Proveedor: ".$this->input->post('id_pro')." /Producto:".$cotz[$i]."/F Termino: ".$fecha->format('Y-m-d H:i:s').
								"/Semanas: ".$no_semanas[$i],
						"despues" => "El usuario agrego faltantes."];
					$data['cambios'] = $this->cambio_md->insert($cambios);
					$new_faltante=[
						"id_producto"		=>	$cotz[$i],
						"fecha_termino"	=>	$fecha->format('Y-m-d H:i:s'),
						"no_semanas"		=>	$no_semanas[$i],
						"id_proveedor" => $this->input->post('id_pro')
					];
					$data ['id_faltante'] = $this->falt_mdl->insert($new_faltante);
					$mensaje = [
						"id" 	=> 'Éxito',
						"desc"	=> 'Faltantes insertados correctamente',
						"type"	=> 'success'
					];
				}
			}
			
		}

		
		
		$this->jsonResponse($mensaje);
	}

}

/* End of file Cotizaciones.php */
/* Location: ./application/controllers/Cotizaciones.php */