<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Cotizaciones extends MY_Controller {

	function __construct(){
		parent::__construct();
		$this->load->model("Cotizaciones_model", "ct_mdl");
		$this->load->model("Cotizacionesback_model", "ctb_mdl");
		$this->load->model("Productos_model", "prod_mdl");
		$this->load->model("Usuarios_model", "usua_mdl");
		$this->load->model("Pedidos_model", "ped_mdl");
		$this->load->model("Familias_model", "fam_mdl");
		$this->load->model("Cambios_model", "cambio_md");
		$this->load->model("Usuarios_model", "user_md");
		$this->load->model("Existencias_model", "ex_mdl");
		$this->load->model("Precio_sistema_model", "pre_mdl");
		$this->load->model("Precio_sistemaback_model", "preb_mdl");
		$this->load->model("Faltantes_model", "falt_mdl");
		$this->load->model("Prodandprice_model", "prodand_mdl");
		$this->load->model("Expocotz_model", "expo_mdl");
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
			redirect("Compras/Login", "");
		}elseif($user['id_grupo'] == 2){//Solo mostrar sus Productos cotizados cuando es proveedor
			$fecha = new DateTime(date('Y-m-d H:i:s'));
			$intervalo = new DateInterval('P3D');
			$fecha->add($intervalo);
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
		//$this->jsonResponse($data["cotizados"]);
	}

	public function proveedorCots($ides){
		$fecha = new DateTime(date('Y-m-d H:i:s'));
		$intervalo = new DateInterval('P2D');
		$fecha->add($intervalo);

		$where=["cotizaciones.id_proveedor" => $ides, "cotizaciones.estatus <> " => 0 ,
				"WEEKOFYEAR(cotizaciones.fecha_registro)" => $this->weekNumber($fecha->format('Y-m-d H:i:s'))];
		$data["cotizaciones"] = $this->ct_mdl->getAllCotizaciones($where);
		$this->jsonResponse($data["cotizaciones"]);
	}

	public function agregar(){
		ini_set("memory_limit", "-1");
		$data['links'] = [
			'/assets/css/plugins/dataTables/dataTables.bootstrap',
			'/assets/css/plugins/dataTables/dataTables.responsive',
			'/assets/css/plugins/dataTables/dataTables.tableTools.min',
			'/assets/css/plugins/dataTables/buttons.dataTables.min',
		];
		$data['scripts'] = [
			'/scripts/agregar',
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
		$data["proveedores"] = $this->usua_mdl->getUsuarios($where);
		$data["usuar"]  = $this->session->userdata();
		$this->estructura("Cotizaciones/agregar", $data);
	}

	public function fastedit(){
		ini_set("memory_limit", "-1");
		$data['links'] = [
			'/assets/css/plugins/dataTables/dataTables.bootstrap',
			'/assets/css/plugins/dataTables/dataTables.responsive',
			'/assets/css/plugins/dataTables/dataTables.tableTools.min',
			'/assets/css/plugins/dataTables/buttons.dataTables.min',
		];
		$data['scripts'] = [
			'/scripts/agregar',
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
		$data["diferencias"] = $this->ct_mdl->getDiferences(NULL);
		$data["usuar"]  = $this->session->userdata();
		$this->estructura("Cotizaciones/fastedit", $data);
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

	public function directos(){
		ini_set("memory_limit", "-1");
		$data['links'] = [
			'/assets/css/plugins/dataTables/dataTables.bootstrap',
			'/assets/css/plugins/dataTables/dataTables.responsive',
			'/assets/css/plugins/dataTables/dataTables.tableTools.min',
			'/assets/css/plugins/dataTables/buttons.dataTables.min',
		];
		$data['scripts'] = [
			'/scripts/directos',
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
		$this->estructura("Cotizaciones/directos", $data);
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

	public function save($idesp){
		if($idesp == 0){
			$proveedor = $this->session->userdata('id_usuario');
		}else{
			$proveedor = $idesp;
		}
		$fecha = new DateTime(date('Y-m-d H:i:s'));
		$intervalo = new DateInterval('P2D');
		$fecha->add($intervalo);
		$antes =  $this->falt_mdl->get(NULL, ['id_producto' => $this->input->post('id_producto'), 'fecha_termino > ' => date("Y-m-d H:i:s"), 'id_proveedor' => $proveedor])[0];
		$cotiz =  $this->ct_mdl->get(NULL, ['id_producto' => $this->input->post('id_producto'), 'WEEKOFYEAR(fecha_registro)' => $this->weekNumber($fecha->format('Y-m-d H:i:s')), 'id_proveedor' => $proveedor])[0];
		$aprod = $this->prod_mdl->get(NULL, ['id_producto'=>$this->input->post('id_producto')])[0];
		$aprov = $this->usua_mdl->get(NULL, ['id_usuario'=>$proveedor])[0];
		$num_one = $this->input->post('num_one') == '' ? 0 : $this->input->post('num_one');
		$num_two = $this->input->post('num_two') == '' ? 0 : $this->input->post('num_two');
		$descuento = str_replace(',', '', $this->input->post('porcentaje')) == '' ? 0 : str_replace(',', '', $this->input->post('porcentaje'));
		if($antes){
			$cotizacion = [
				'id_producto'		=>	$this->input->post('id_producto'),
				'id_proveedor'		=>	$proveedor,
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
				$data['cotizacin']=$this->ctb_mdl->update($cotizacion, ['id_cotizacion' => $cotiz->id_cotizacion]);
			}else{
				$data['cotizacin']=$this->ct_mdl->insert($cotizacion);
				$data['cotizacin']=$this->ctb_mdl->insert($cotizacion);
			}
			$cambios = [
				"id_usuario" => $this->session->userdata('id_usuario'),
				"fecha_cambio" => date('Y-m-d H:i:s'),
				"accion" => "Cotizacion Nueva Con Faltante",
				"antes" => "Nueva cotización",
				"despues" => "Producto: ".$aprod->nombre."\n///Proveedor: ".$aprov->nombre."\n///Precio: ".str_replace(',', '', $this->input->post('precio'))."\n///Precio promoción: ".
							(($this->input->post('precio_promocion') > 0) ? str_replace(',', '', $this->input->post('precio_promocion')) : str_replace(',', '', $this->input->post('precio')))." ".
							"\n///".$this->input->post('num_one')." EN ".$this->input->post('num_two')."\n///Descuento: ".str_replace(',', '', $this->input->post('porcentaje')).
							"\n///Observaciones: ".strtoupper($this->input->post('observaciones'))
			];
			$data['cambios'] = $this->cambio_md->insert($cambios);
		}else{
			$cotizacion = [
				'id_producto'		=>	$this->input->post('id_producto'),
				'id_proveedor'		=>	$proveedor,
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
				$data['cotizacin']=$this->ctb_mdl->update($cotizacion, ['id_cotizacion' => $cotiz->id_cotizacion]);
			}else{
				$data['cotizacin']=$this->ct_mdl->insert($cotizacion);
				$data['cotizacin']=$this->ctb_mdl->insert($cotizacion);
			}
			$cambios = [
				"id_usuario" => $this->session->userdata('id_usuario'),
				"fecha_cambio" => date('Y-m-d H:i:s'),
				"accion" => "Cotizacion Nueva",
				"antes" => "Nueva cotización",
				"despues" => "Producto: ".$aprod->nombre."\n///Proveedor: ".$aprov->nombre."\n///Precio: ".str_replace(',', '', $this->input->post('precio'))."\n///Precio promoción: ".
							(($this->input->post('precio_promocion') > 0) ? str_replace(',', '', $this->input->post('precio_promocion')) : str_replace(',', '', $this->input->post('precio')))." ".
							"\n///".$this->input->post('num_one')." EN ".$this->input->post('num_two')."\n///Descuento: ".str_replace(',', '', $this->input->post('porcentaje')).
							"\n///Observaciones: ".strtoupper($this->input->post('observaciones'))
			];
			$data['cambios'] = $this->cambio_md->insert($cambios);
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
			$aprod = $this->prod_mdl->get(NULL, ['id_producto'=>$antes->id_producto])[0];
			$aprov = $this->usua_mdl->get(NULL, ['id_usuario'=>$antes->id_proveedor])[0];
			$cambios = [
				"id_usuario" => $user["id_usuario"],
				"fecha_cambio" => date('Y-m-d H:i:s'),
				"accion" => "Cotizacion actualizada",
				"antes" => "id : ".$antes->id_cotizacion." \n///Proveedor: ".$aprov->nombre." \n///Producto:".$aprod->nombre." \n///Precio: ".
							$antes->precio." \n///Precio promoción: ".$antes->precio_promocion." \n///".$antes->num_one." en ".$antes->num_two.
							" \n///% Descuento: ".$antes->descuento." \nRegistrado: ".$antes->fecha_registro." \n///Observaciones: ".$antes->observaciones,
				"despues" => "Precio: ".$precio[$i]."\n///Precio promoción: ".$precio_promocion[$i]."\n///".$num_one[$i]." en ".$num_two[$i].
							"\n///%Descuento: ".$descuento[$i]."\n///Observaciones: ".$observaciones[$i]];
			$data['cambios'] = $this->cambio_md->insert($cambios);
			$data ['id_cotizacion'] = $this->ct_mdl->update([
				"precio" => str_replace(',', '', $precio[$i]),//$precio[$i]
				"precio_promocion" => str_replace(',', '', $precio_promocion[$i]),
				"num_one" => $num_one[$i],
				"num_two" => $num_two[$i],
				"descuento" => $descuento[$i],
				"observaciones" => $observaciones[$i]
			], $cotz[$i]);
			$data ['id_cotizacion'] = $this->ctb_mdl->update([
				"precio" => str_replace(',', '', $precio[$i]),//$precio[$i]
				"precio_promocion" => str_replace(',', '', $precio_promocion[$i]),
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
			$aprod = $this->prod_mdl->get(NULL, ['id_producto'=>$antes->id_producto])[0];
			$aprov = $this->usua_mdl->get(NULL, ['id_usuario'=>$antes->id_proveedor])[0];
			$cambios = [
				"id_usuario" => $user["id_usuario"],
				"fecha_cambio" => date('Y-m-d H:i:s'),
				"antes" => "id : ".$antes->id_cotizacion." \n///Proveedor: ".$aprov->nombre." \n///Producto:".$aprod->nombre." \n///Precio: ".
							$antes->precio." \n///Precio promoción: ".$antes->precio_promocion." \n///".$antes->num_one." en ".$antes->num_two.
							" \n///% Descuento: ".$antes->descuento." \nRegistrado: ".$antes->fecha_registro." \n///Observaciones: ".$antes->observaciones,
				"accion" => "Cotizacion eliminada","despues" => "El usuario elimino la cotización"];
			$data['cambios'] = $this->cambio_md->insert($cambios);
			$data ['id_cotizacion'] = $this->ct_mdl->update(["estatus" => 0], $productos[$i]);
			$data ['id_cotizacion'] = $this->ctb_mdl->update(["estatus" => 0], $productos[$i]);
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

	public function get_update2($id,$idpros){
		$fecha = new DateTime(date('Y-m-d H:i:s'));
		$intervalo = new DateInterval('P2D');
		$fecha->add($intervalo);
		$data["cotizacion"] = $this->ct_mdl->get(NULL, ['id_cotizacion'=>$id])[0];
		$data["producto"] = $this->prod_mdl->get(NULL, ['id_producto'=>$data["cotizacion"]->id_producto])[0];
		$data["title"]="ACTUALIZAR COTIZACIÓN DE <br>".$data["producto"]->nombre;
		$user = $this->session->userdata();
		$where=["cotizaciones.id_proveedor" => $idpros];
		$data["cots"]=$this->ct_mdl->get_cots($where, $data["cotizacion"]->id_producto,$fecha->format('Y-m-d H:i:s'));
		$where=["cotizaciones.id_proveedor" => $idpros, "cotizaciones.estatus" => 0];
		$data["cotss"]=$this->ct_mdl->get_cots($where, $data["cotizacion"]->id_producto,$fecha->format('Y-m-d H:i:s'));
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

	public function get_delete2($id,$idpros){
		$fecha = new DateTime(date('Y-m-d H:i:s'));
		$intervalo = new DateInterval('P2D');
		$fecha->add($intervalo);
		$data["cotizacion"] = $this->ct_mdl->get(NULL, ['id_cotizacion'=>$id])[0];
		$data["producto"] = $this->prod_mdl->get(NULL, ['id_producto'=>$data["cotizacion"]->id_producto])[0];
		$data["title"]="Marque la casilla del producto :<br>".$data["producto"]->nombre;
		$where=["cotizaciones.id_proveedor" => $idpros];
		$data["cots"]=$this->ct_mdl->get_cots($where, $data["cotizacion"]->id_producto,$fecha->format('Y-m-d H:i:s'));
		$where=["cotizaciones.id_proveedor" => $idpros, "cotizaciones.estatus" => 0];
		$data["cotss"]=$this->ct_mdl->get_cots($where, $data["cotizacion"]->id_producto,$fecha->format('Y-m-d H:i:s'));
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

	public function getDirTable($directo){
		$fecha = new DateTime(date('Y-m-d H:i:s'));
		$intervalo = new DateInterval('P2D');
		$fecha->add($intervalo);
		$fecha = $fecha->format('Y-m-d H:i:s');
		$data["cotizados"] = $this->ct_mdl->getCotzD(NULL,$fecha,$directo);
		$this->jsonResponse($data);
	}

	public function getDirProv($directo){
		$fecha = new DateTime(date('Y-m-d H:i:s'));
		$intervalo = new DateInterval('P2D');
		$fecha->add($intervalo);
		$fecha = $fecha->format('Y-m-d H:i:s');
		$data["cotizados"] = $this->ct_mdl->getCotzP(NULL,$fecha,$directo);
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

		$this->cellStyle("A1:T2", "000000", "FFFFFF", TRUE, 12, "Franklin Gothic Book");
		$border_style= array('borders' => array('right' => array('style' =>
			PHPExcel_Style_Border::BORDER_THIN,'color' => array('argb' => '000000'),)));
		$hoja->setCellValue("A2", "CÓDIGO")->getColumnDimension('A')->setWidth(30); //Nombre y ajuste de texto a la columna
		$hoja->setCellValue("B1", "DESCRIPCIÓN")->getColumnDimension('B')->setWidth(50);
		$hoja->setCellValue("C2", "SISTEMA")->getColumnDimension('C')->setWidth(12);
		$hoja->setCellValue("D2", "PRECIO 4")->getColumnDimension('D')->setWidth(12);
		$hoja->setCellValue("E1", "DIFERENCIA 1")->getColumnDimension('E')->setWidth(12);
		$hoja->setCellValue("F1", "PRECIO MENOR")->getColumnDimension('F')->setWidth(12);
		$hoja->setCellValue("G1", "PRECIO PROMOCIÓN")->getColumnDimension('G')->setWidth(12);
		$hoja->setCellValue("H1", "PROVEEDOR")->getColumnDimension('H')->setWidth(15);
		$hoja->setCellValue("I1", "OBSERVACIÓN")->getColumnDimension('I')->setWidth(30);
		$hoja->setCellValue("J1", "PRECIO MÁXIMO")->getColumnDimension('J')->setWidth(12);
		$hoja->setCellValue("K1", "PRECIO PROMEDIO")->getColumnDimension('K')->setWidth(12);
		$hoja->setCellValue("L1", "DIFERENCIA 2")->getColumnDimension('L')->setWidth(12);
		$hoja->setCellValue("M1", "2DO PRECIO")->getColumnDimension('M')->setWidth(12);
		$hoja->setCellValue("N1", "PRECIO PROMOCIÓN")->getColumnDimension('N')->setWidth(12);
		$hoja->setCellValue("O1", "2DO PROVEEDOR")->getColumnDimension('O')->setWidth(15);
		$hoja->setCellValue("P1", "2DA OBSERVACIÓN")->getColumnDimension('P')->setWidth(30);
		$hoja->setCellValue("Q1", "3ER PRECIO")->getColumnDimension('Q')->setWidth(12);
		$hoja->setCellValue("R1", "PRECIO PROMOCIÓN")->getColumnDimension('R')->setWidth(12);
		$hoja->setCellValue("S1", "3ER PROVEEDOR")->getColumnDimension('S')->setWidth(15);
		$hoja->setCellValue("T1", "3ER OBSERVACIÓN")->getColumnDimension('T')->setWidth(30);


		$fecha = new DateTime(date('Y-m-d H:i:s'));
		$intervalo = new DateInterval('P2D');
		$fecha->add($intervalo);
		$fecha = $fecha->format('Y-m-d H:i:s');
		$cotizacionesProveedor = $this->ct_mdl->comparaCotizaciones2(NULL, $fecha,0);

		$row_print =2;
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

						$dif1 = $row["precio_sistema"] - $row["precio_first"];
						if($row['precio_first'] !== NULL){
							if ($dif1 >= ($row["precio_sistema"] * .30) || $dif1 <= (($row["precio_sistema"] * .30) * (-1))) {
								$hoja->setCellValue("E{$row_print}", $dif1)->getStyle("E{$row_print}")->getNumberFormat()->setFormatCode('"$"#,##0.00_-');
								$hoja->getStyle("E{$row_print}")->applyFromArray($border_style);
								$this->cellStyle("E{$row_print}", "FF0066", "000000", FALSE, 12, "Franklin Gothic Book");
							}else{
								$hoja->setCellValue("E{$row_print}", $dif1)->getStyle("E{$row_print}")->getNumberFormat()->setFormatCode('"$"#,##0.00_-');
								$hoja->getStyle("E{$row_print}")->applyFromArray($border_style);
								$this->cellStyle("E{$row_print}", "FFE6F0", "000000", FALSE, 12, "Franklin Gothic Book");
							}
						}else{
							$hoja->getStyle("E{$row_print}")->applyFromArray($border_style);
							$this->cellStyle("E{$row_print}", "FFE6F0", "000000", FALSE, 12, "Franklin Gothic Book");
						}


						$hoja->setCellValue("F{$row_print}", $row['precio_firsto'])->getStyle("F{$row_print}")->getNumberFormat()->setFormatCode('"$"#,##0.00_-');
						$hoja->getStyle("F{$row_print}")->applyFromArray($border_style);
						if($row['estatus'] == 2){
							$this->cellStyle("B{$row_print}", "00B0F0", "000000", FALSE, 12, "Franklin Gothic Book");
						}
						if($row['estatus'] == 3){
							$this->cellStyle("B{$row_print}", "FFF900", "000000", FALSE, 12, "Franklin Gothic Book");
						}
						if($row['estatus'] >= 4){
							$this->cellStyle("B{$row_print}", "04B486", "000000", FALSE, 12, "Franklin Gothic Book");
						}
						$hoja->getStyle("B{$row_print}")->applyFromArray($border_style);
						if($row['precio_sistema'] < $row['precio_first']){
							$hoja->setCellValue("G{$row_print}", $row['precio_first'])->getStyle("G{$row_print}")->getNumberFormat()->setFormatCode('"$"#,##0.00_-');
							$this->cellStyle("G{$row_print}", "FDB2B2", "E21111", FALSE, 12, "Franklin Gothic Book");
						}else{
							$hoja->setCellValue("G{$row_print}", $row['precio_first'])->getStyle("G{$row_print}")->getNumberFormat()->setFormatCode('"$"#,##0.00_-');
							$this->cellStyle("G{$row_print}", "96EAA8", "0C800C", FALSE, 12, "Franklin Gothic Book");
						}
						$hoja->getStyle("G{$row_print}")->applyFromArray($border_style);
						$hoja->setCellValue("H{$row_print}", $row['proveedor_first'])->getStyle("H{$row_print}");
						$hoja->getStyle("H{$row_print}")->applyFromArray($border_style);
						$hoja->setCellValue("I{$row_print}", $row['promocion_first'])->getStyle("I{$row_print}");
						$hoja->getStyle("I{$row_print}")->applyFromArray($border_style);
						$hoja->setCellValue("J{$row_print}", $row['precio_maximo'])->getStyle("J{$row_print}")->getNumberFormat()->setFormatCode('"$"#,##0.00_-');
						$hoja->getStyle("J{$row_print}")->applyFromArray($border_style);
						$hoja->setCellValue("K{$row_print}", $row['precio_promedio'])->getStyle("K{$row_print}")->getNumberFormat()->setFormatCode('"$"#,##0.00_-');
						$hoja->getStyle("K{$row_print}")->applyFromArray($border_style);

						$dif1 = $row["precio_sistema"] - $row["precio_next"];
						if($row['precio_next'] !== NULL){
							if ($dif1 >= ($row["precio_sistema"] * .30) || $dif1 <= (($row["precio_sistema"] * .30) * (-1))) {
								$hoja->setCellValue("L{$row_print}", $dif1)->getStyle("L{$row_print}")->getNumberFormat()->setFormatCode('"$"#,##0.00_-');
								$hoja->getStyle("L{$row_print}")->applyFromArray($border_style);
								$this->cellStyle("L{$row_print}", "FF0066", "000000", FALSE, 12, "Franklin Gothic Book");
							}else{
								$hoja->setCellValue("L{$row_print}", $dif1)->getStyle("L{$row_print}")->getNumberFormat()->setFormatCode('"$"#,##0.00_-');
								$hoja->getStyle("L{$row_print}")->applyFromArray($border_style);
								$this->cellStyle("L{$row_print}", "FFE6F0", "000000", FALSE, 12, "Franklin Gothic Book");
							}
						}else{
							$hoja->getStyle("L{$row_print}")->applyFromArray($border_style);
							$this->cellStyle("L{$row_print}", "FFE6F0", "000000", FALSE, 12, "Franklin Gothic Book");
						}

						$hoja->setCellValue("M{$row_print}", $row['precio_nexto'])->getStyle("M{$row_print}")->getNumberFormat()->setFormatCode('"$"#,##0.00_-');
						$hoja->getStyle("M{$row_print}")->applyFromArray($border_style);
						if($row['precio_sistema'] < $row['precio_next']){
							$hoja->setCellValue("N{$row_print}", $row['precio_next'])->getStyle("N{$row_print}")->getNumberFormat()->setFormatCode('"$"#,##0.00_-');
							$this->cellStyle("N{$row_print}", "FDB2B2", "E21111", FALSE, 12, "Franklin Gothic Book");
						}else if($row['precio_next'] !== NULL){
							$hoja->setCellValue("N{$row_print}", $row['precio_next'])->getStyle("N{$row_print}")->getNumberFormat()->setFormatCode('"$"#,##0.00_-');
							$this->cellStyle("N{$row_print}", "96EAA8", "0C800C", FALSE, 12, "Franklin Gothic Book");
						}else{
							$hoja->setCellValue("N{$row_print}", $row['precio_next'])->getStyle("N{$row_print}")->getNumberFormat()->setFormatCode('"$"#,##0.00_-');
							$this->cellStyle("N{$row_print}", "FFFFFF", "000000", FALSE, 12, "Franklin Gothic Book");
						}
						$hoja->getStyle("N{$row_print}")->applyFromArray($border_style);

						$hoja->setCellValue("O{$row_print}", $row['proveedor_next'])->getStyle("O{$row_print}");
						$hoja->getStyle("O{$row_print}")->applyFromArray($border_style);
						$hoja->setCellValue("P{$row_print}", $row['promocion_next'])->getStyle("P{$row_print}");
						$hoja->getStyle("P{$row_print}")->applyFromArray($border_style);

						$hoja->setCellValue("Q{$row_print}", $row['precio_nxtso'])->getStyle("Q{$row_print}")->getNumberFormat()->setFormatCode('"$"#,##0.00_-');
						$hoja->getStyle("Q{$row_print}")->applyFromArray($border_style);
						if($row['precio_sistema'] < $row['precio_nxts']){
							$hoja->setCellValue("R{$row_print}", $row['precio_nxts'])->getStyle("R{$row_print}")->getNumberFormat()->setFormatCode('"$"#,##0.00_-');
							$this->cellStyle("R{$row_print}", "FDB2B2", "E21111", FALSE, 12, "Franklin Gothic Book");
						}else if($row['precio_next'] !== NULL){
							$hoja->setCellValue("R{$row_print}", $row['precio_nxts'])->getStyle("R{$row_print}")->getNumberFormat()->setFormatCode('"$"#,##0.00_-');
							$this->cellStyle("R{$row_print}", "96EAA8", "0C800C", FALSE, 12, "Franklin Gothic Book");
						}else{
							$hoja->setCellValue("R{$row_print}", $row['precio_nxts'])->getStyle("R{$row_print}")->getNumberFormat()->setFormatCode('"$"#,##0.00_-');
							$this->cellStyle("R{$row_print}", "FFFFFF", "000000", FALSE, 12, "Franklin Gothic Book");
						}
						$hoja->getStyle("R{$row_print}")->applyFromArray($border_style);
						$hoja->setCellValue("S{$row_print}", $row['proveedor_nxts'])->getStyle("S{$row_print}");
						$hoja->getStyle("S{$row_print}")->applyFromArray($border_style);
						$hoja->setCellValue("T{$row_print}", $row['promocion_nxts'])->getStyle("T{$row_print}");
						$hoja->getStyle("T{$row_print}")->applyFromArray($border_style);
						$hoja->getStyle("A{$row_print}:T{$row_print}")
			                 ->getAlignment()
			                 ->setHorizontal(\PHPExcel_Style_Alignment::HORIZONTAL_LEFT);
						$row_print ++;
					}
				}
			}
		}


        $dias = array("DOMINGO","LUNES","MARTES","MIÉRCOLES","JUEVES","VIERNES","SÁBADO");
		$meses = array("ENERO","FEBRERO","MARZO","ABRIL","MAYO","JUNIO","JULIO","AGOSTO","SEPTIEMBRE","OCTUBRE","NOVIEMBRE","DICIEMBRE");

		$fecha =  $dias[date('w')]." ".date('d')." DE ".$meses[date('n')-1]. " DEL ".date('Y') ;
		$file_name = "COTIZACIÓN ".$fecha.".xlsx"; //Nombre del documento con extención
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
		$productos = $this->prod_mdl->getProdFam(NULL,$this->input->post("id_pro"));
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
						if($row['estatus'] >= 4){
							$this->cellStyle("B{$row_print}", "04B486", "000000", FALSE, 12, "Franklin Gothic Book");
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
						if($this->weekNumber($row['fecha_registro']) >= ($this->weekNumber() -1)){
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
						if($row['estatus'] >= 4){
							$this->cellStyle("B{$row_print}", "04B486", "000000", FALSE, 12, "Franklin Gothic Book");
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

	public function upload_cotizaciones($idesp){
		$fecha = new DateTime(date('Y-m-d H:i:s'));
		$intervalo = new DateInterval('P3D');
		$fecha->add($intervalo);
		if($idesp == 0){
			$proveedor = $this->session->userdata('id_usuario');
		}else{
			$proveedor = $idesp;
		}

		$cfile =  $this->usua_mdl->get(NULL, ['id_usuario' => $proveedor])[0];
		$nams = preg_replace('/\s+/', '_', $cfile->nombre);
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
					$antes =  $this->falt_mdl->get(NULL, ['id_producto' => $productos->id_producto, 'fecha_termino > ' => date("Y-m-d H:i:s"), 'id_proveedor' => $proveedor])[0];
					$cotiz =  $this->ct_mdl->get(NULL, ['id_producto' => $productos->id_producto, 'WEEKOFYEAR(fecha_registro)' => $this->weekNumber($fecha->format('Y-m-d H:i:s')), 'id_proveedor' => $proveedor])[0];
					if($antes){
						$new_cotizacion=[
							"id_producto"		=>	$productos->id_producto,
							"id_proveedor"		=>	$proveedor,//Recupera el id_usuario activo
							"precio"			=>	$precio,
							"num_one"			=>	$column_one,
							"num_two"			=>	$column_two,
							"descuento"			=>	$descuento,
							"precio_promocion"	=>	$precio_promocion,
							"fecha_registro"	=>	$fecha->format('Y-m-d H:i:s'),
							"observaciones"		=>	$sheet->getCell('D'.$i)->getValue(),
							"estatus" => 0];
						if($cotiz){
							$data['cotizacion']=$this->ct_mdl->update($new_cotizacion, ['id_cotizacion' => $cotiz->id_cotizacion]);
						}else{
							$data['cotizacion']=$this->ct_mdl->insert($new_cotizacion);
						}
					}else{
						$new_cotizacion=[
							"id_producto"		=>	$productos->id_producto,
							"id_proveedor"		=>	$proveedor,//Recupera el id_usuario activo
							"precio"			=>	$precio,
							"num_one"			=>	$column_one,
							"num_two"			=>	$column_two,
							"descuento"			=>	$descuento,
							"precio_promocion"	=>	$precio_promocion,
							"fecha_registro"	=>	$fecha->format('Y-m-d H:i:s'),
							"observaciones"		=>	$sheet->getCell('D'.$i)->getValue()
						];
						if($cotiz){
							$data['cotizacion']=$this->ct_mdl->update($new_cotizacion, ['id_cotizacion' => $cotiz->id_cotizacion]);
						}else{
							$data['cotizacion']=$this->ct_mdl->insert($new_cotizacion);
						}
					}

				}
			}
		}
		if (!isset($new_cotizacion)) {
			$mensaje=[	"id"	=>	'Error',
						"desc"	=>	'El Archivo esta sin precios',
						"type"	=>	'error'];
		}else{
			if (sizeof($new_cotizacion) > 0) {
				$aprov = $this->usua_mdl->get(NULL, ['id_usuario'=>$proveedor])[0];
				$cambios=[
						"id_usuario"		=>	$this->session->userdata('id_usuario'),
						"fecha_cambio"		=>	date("Y-m-d H:i:s"),
						"antes"			=>	"El usuario sube archivo de cotizaciones de ".$aprov->nombre,
						"despues"			=>	"assets/uploads/cotizaciones/".$filen.".xlsx",
						"accion"			=>	"Sube Archivo"
					];
				$data['cambios']=$this->cambio_md->insert($cambios);
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
	}

	public function upload_pedidos($idesp){
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

		if($idesp === "0"){
			$tienda = $this->session->userdata('id_usuario');
		}else{
			$tienda = $idesp;
		}
		$cfile =  $this->usua_mdl->get(NULL, ['id_usuario' => $tienda])[0];
		$nams = preg_replace('/\s+/', '_', $cfile->nombre);
		$filen = "Pedidos".$nams."".rand();

		$config['upload_path']          = './assets/uploads/pedidos/';
        $config['allowed_types']        = 'xlsx|xls';
        $config['max_size']             = 100;
        $config['max_width']            = 1024;
        $config['max_height']           = 768;
        $config['max_height']           = 768;


        $this->load->library('upload', $config);
        $this->upload->initialize($config);
        $this->upload->do_upload('file_cotizaciones',$filen);
		for ($i=3; $i<=$num_rows; $i++) {
			$productos = $this->prod_mdl->get("id_producto",['codigo'=> htmlspecialchars($sheet->getCell('D'.$i)->getValue(), ENT_QUOTES, 'UTF-8')])[0];
			if (sizeof($productos) > 0) {
				$exis = $this->ex_mdl->get(NULL,["WEEKOFYEAR(fecha_registro)" => $this->weekNumber($fecha->format('Y-m-d H:i:s')),"id_tienda"=>$tienda,"id_producto"=>$productos->id_producto])[0];
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
				if($exis){
					$data['cotizacion']=$this->ex_mdl->update($new_existencias[$i], ['id_pedido' => $exis->id_pedido]);
				}else{
					$data['cotizacion']=$this->ex_mdl->insert($new_existencias[$i]);
				}
			}
		}
		if (sizeof($new_existencias) > 0) {
			$aprov = $this->usua_mdl->get(NULL, ['id_usuario'=>$tienda])[0];
			$cambios=[
					"id_usuario"		=>	$this->session->userdata('id_usuario'),
					"fecha_cambio"		=>	date("Y-m-d H:i:s"),
					"antes"			=>	"El usuario sube archivo de pedidos de la tienda ".$aprov->nombre,
					"despues"			=>	"assets/uploads/pedidos/".$filen.".xlsx",
					"accion"			=>	"Sube Pedidos"
				];
			$data['cambios']=$this->cambio_md->insert($cambios);
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
					$precios = $this->pre_mdl->get("id_precio",['id_producto'=> $productos->id_producto, 'WEEKOFYEAR(fecha_registro)' => $this->weekNumber($fecha->format('Y-m-d H:i:s'))])[0];
					if(sizeof($precios) > 0 ){
						$data['cotizacion']=$this->pre_mdl->update($new_precios,
						['WEEKOFYEAR(fecha_registro)' => $this->weekNumber($fecha->format('Y-m-d H:i:s')),'id_precio'=>$precios->id_precio]);
						$data['cotizacion']=$this->preb_mdl->update($new_precios,
						['WEEKOFYEAR(fecha_registro)' => $this->weekNumber($fecha->format('Y-m-d H:i:s')),'id_precio'=>$precios->id_precio]);
					}else{
						$data['cotizacion']=$this->pre_mdl->insert($new_precios);
						$data['cotizacion']=$this->preb_mdl->insert($new_precios);
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
		$fecha = new DateTime(date('Y-m-d H:i:s'));
		$intervalo = new DateInterval('P2D');
		$fecha->add($intervalo);
		$fech = $fecha->format('Y-m-d H:i:s');
		ini_set("memory_limit", "-1");
		ini_set("max_execution_time", "-1");
			$search = ["prodandprice.codigo","prodandprice.nombre"];
			$columns = "ctz1.id_cotizacion,
			ctz1.fecha_registro,prodandprice.estatus,prodandprice.color,prodandprice.colorp,
			prodandprice.codigo, prodandprice.nombre AS producto,prodandprice.id_producto,
			UPPER(ctz1.nombre) AS proveedor_first,
			ctz1.precio AS precio_firsto,
			ctz1.precio_promocion AS precio_first,
			ctz1.observaciones AS promocion_first,
			ctz1.observaciones AS observaciones_first,
			prodandprice.precio_sistema,
			prodandprice.precio_four,
			UPPER(ctz2.nombre) AS proveedor_next,
			ctz2.fecha_registro AS fecha_next,
			ctz2.observaciones AS promocion_next,
			ctz2.precio AS precio_nexto,
			ctz2.precio_promocion AS precio_next,
			UPPER(ctz3.nombre) AS proveedor_nxts,
			ctz3.observaciones AS promocion_nxts,
			ctz3.precio AS precio_nxtso,
			ctz3.precio_promocion AS precio_nxts,
			ctz1.maxis AS precio_maximo,
			ctz1.avis AS precio_promedio,prodandprice.id_familia, prodandprice.familia AS familia";
			$joins = [
				["table"	=>	"bajos ctz1","ON"	=>	"prodandprice.id_producto = ctz1.id_producto", "clausula"	=>	"LEFT"],
				["table"	=>	"bajos ctz2","ON"	=>	"prodandprice.id_producto = ctz2.id_producto AND ctz2.id_cotizacion <> ctz1.id_cotizacion", "clausula"	=>	"LEFT"],
				["table"	=>	"bajos ctz3","ON"	=>	"prodandprice.id_producto = ctz3.id_producto AND ctz3.id_cotizacion <> ctz1.id_cotizacion AND ctz3.id_cotizacion <> ctz2.id_cotizacion","clausula"	=>	"LEFT"]
			];
		$where = NULL;
			$order="prodandprice.id_familia,prodandprice.nombre";
			$group ="prodandprice.nombre";

		$cotizacionesProveedor = $this->prodand_mdl->get_pagination($columns, $joins, $where, $search, $group, $order);

		$data =[];
		$no = $_POST["start"];
				foreach ($cotizacionesProveedor as $key => $value) {
					$no ++;
					$row = [];
					$row[] = $value->codigo;
					$row[] = $value->producto;
					$row[] = ($value->precio_sistema > 0) ? '$ '.number_format($value->precio_sistema,2,'.',',') : '';
					$row[] = ($value->precio_four > 0) ? '$ '.number_format($value->precio_four,2,'.',',') : '';
					$row[] = '$ '.number_format($value->precio_firsto,2,'.',',');
					if($value->precio_first <= $value->precio_sistema){
						$row[] = ($value->precio_first > 0) ? '<div class="preciomenos">$ '.number_format($value->precio_first,2,'.',',').'</div>' : '';
					}else{
						$row[] = ($value->precio_first > 0) ? '<div class="preciomas">$ '.number_format($value->precio_first,2,'.',',').'</div>' : '';
					}
					$row[] = $value->proveedor_first;
					$row[] = $value->promocion_first;
					$row[] = '$ '.number_format($value->precio_maximo,2,'.',',');
					$row[] = '$ '.number_format($value->precio_promedio,2,'.',',');
					$row[] = ($value->precio_nexto > 0) ? '$ '.number_format($value->precio_nexto,2,'.',',') : '';
					if($value->precio_next <= $value->precio_sistema){
						$row[] = ($value->precio_next > 0) ? '<div class="preciomenos">$ '.number_format($value->precio_next,2,'.',',').'</div>' : '';
					}else{
						$row[] = ($value->precio_next > 0) ? '<div class="preciomas">$ '.number_format($value->precio_next,2,'.',',').'</div>' : '';
					}
					$row[] = $value->proveedor_next;
					$row[] = $value->promocion_next;
					$row[] = ($value->precio_nxtso > 0) ? '$ '.number_format($value->precio_nxtso,2,'.',',') : '';
					if($value->precio_nxts <= $value->precio_sistema){
						$row[] = ($value->precio_nxts > 0) ? '<div class="preciomenos">$ '.number_format($value->precio_nxts,2,'.',',').'</div>' : '';
					}else{
						$row[] = ($value->precio_nxts > 0) ? '<div class="preciomas">$ '.number_format($value->precio_nxts,2,'.',',').'</div>' : '';
					}
					$row[] = $value->proveedor_nxts;
					$row[] = $value->promocion_nxts;
					$row[] = $this->column_buttons($value->id_cotizacion, "All");
					$data[] = $row;
				}
		$salida = [
			"query"				=>	$this->db->last_query(),
			"draw"				=>	$_POST['draw'],
			"recordsTotal"		=>	$this->prodand_mdl->count_filtered("prodandprice.id_producto", $where, $search, $joins),
			"recordsFiltered"	=>	$this->prodand_mdl->count_filtered("prodandprice.id_producto", $where, $search, $joins),
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

	public function getProveedorBajos($ides){
		$fecha = new DateTime(date('Y-m-d H:i:s'));
		$intervalo = new DateInterval('P2D');
		$fecha->add($intervalo);

		$data["cotizaciones"] =  $this->ct_mdl->getProveedorBajos(NULL,$fecha->format('Y-m-d H:i:s'),$ides);
		$this->jsonResponse($data);
	}

	public function getProveedorCot($ides){
		$data["cotizaciones"] =  $this->ct_mdl->getfalts(['fal.id_proveedor'=>$ides,'fal.fecha_termino >' => date("Y-m-d H:i:s")]);
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
		$flag =1;
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
		ini_set("max_execution_time", "-1");
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
			$hoja->getColumnDimension('AD')->setWidth("70");
			$hoja->getColumnDimension('G')->setWidth("20");
		}
		$flagBorder = 0;
		$flagBorder1 = 1;
		$flagBorder2 = 0;
		$flagBorder3 = 1;
		$flage = 5;
		$i = 0;
		$sumall = array(1 => "", 2 => "", 3 => "", 4 => "", 5 => "", 6 => "", 7 => "");
		if ($array){
			foreach ($array as $key => $value){

				$fecha = new DateTime(date('Y-m-d H:i:s'));
				$intervalo = new DateInterval('P2D');
				$fecha->add($intervalo);
				if ($value->nombre === "AMARILLOS") {
				$where=["prod.estatus" => 3];//Semana actual
			}elseif ($value->nombre === "VOLUMEN" ) {
				$where=["prod.estatus" => 2];//Semana actual
			}else{
				$where=["ctz_first.id_proveedor" => $value->id_usuario,"prod.estatus" => 1];//Semana actual
			}
			$fecha = new DateTime(date('Y-m-d H:i:s'));
			$intervalo = new DateInterval('P2D');
			$fecha->add($intervalo);
			$cotizacionesProveedor = $this->ct_mdl->getPedidosAll($where, $fecha->format('Y-m-d H:i:s'), 0);
				
				$difff = 0.01;
				$flag2 = 3;

				if ($cotizacionesProveedor){
					//HOJA EXISTENCIAS

					$this->excelfile->setActiveSheetIndex(0);
					if($i > 0){
						$flagBorder = $flag1 ;
						$this->excelfile->getActiveSheet()->getStyle('A'.$flagBorder1.':E'.$flagBorder)->applyFromArray($styleArray);
						$flagBorder1 = $flag1;
					}
					$hoja1->mergeCells('A'.$flag1.':E'.$flag1);
					$this->cellStyle("A".$flag1, "FFFFFF", "000000", TRUE, 12, "Franklin Gothic Book");
					$hoja1->setCellValue("A".$flag1."", "GRUPO ABARROTES AZTECA");
					$this->excelfile->getActiveSheet()->getStyle('A'.$flag.':E'.$flag1)->applyFromArray($styleArray);
					$flag1++;
					$hoja1->mergeCells('A'.$flag1.':E'.$flag1);
					$this->cellStyle("A".$flag1, "FFFFFF", "000000", TRUE, 12, "Franklin Gothic Book");
					$hoja1->setCellValue("A".$flag1."", "PEDIDOS A '".$value->nombre."' ".date("d-m-Y"));
					$this->excelfile->getActiveSheet()->getStyle('A'.$flag.':E'.$flag1)->applyFromArray($styleArray);
					$flag1++;
					$this->cellStyle("A".$flag1.":D".$flag1, "000000", "FFFFFF", TRUE, 12, "Franklin Gothic Book");
					$hoja1->mergeCells('A'.$flag1.':B'.$flag1);
					$hoja1->setCellValue("A".$flag1, "EXISTENCIAS");
					$hoja1->setCellValue("E".$flag1, "DESCRIPCIÓN");
					$this->cellStyle("E".$flag1, "000000", "FFFFFF", TRUE, 12, "Franklin Gothic Book");
					$this->excelfile->getActiveSheet()->getStyle('A'.$flag.':E'.$flag1)->applyFromArray($styleArray);
					$flag1++;
					$this->cellStyle("A".$flag1.":E".$flag1, "000000", "FFFFFF", TRUE, 12, "Franklin Gothic Book");
					$hoja1->setCellValue("A".$flag1, "CAJAS");
					$hoja1->setCellValue("B".$flag1, "PZAS");
					$hoja1->setCellValue("C".$flag1, "PEDIDO");
					$hoja1->setCellValue("D".$flag1, "COD");
					//$hoja1->setCellValue("E".$flag1, "DESCRIPCIÓN");
					$this->excelfile->getActiveSheet()->getStyle('A'.$flag.':E'.$flag1)->applyFromArray($styleArray);
					//$flag1++;
					$this->excelfile->setActiveSheetIndex(1);
					if($i > 0){
						$flagBorder2 = $flag ;
						$this->excelfile->getActiveSheet()->getStyle('A'.$flagBorder3.':AC'.$flagBorder2)->applyFromArray($styleArray);
						$flagBorder3 = $flag;
					}
					//HOJA PEDIDOS
					if ($id_proves === "VOLUMEN" || $id_proves === "AMARILLOS"){
						$this->cellStyle("A".$flag, "FFFFFF", "000000", TRUE, 12, "Franklin Gothic Book");
						$hoja->setCellValue("A".$flag."", "ABARROTES, TIENDA, ULTRAMARINOS, TRINCHERAS, MERCADO, TIJERAS, Y TENENCIA AZTECA AUTOSERVICIOS SA. DE CV.");
						$hoja->mergeCells('A'.$flag.':AD'.$flag);
						$this->excelfile->getActiveSheet()->getStyle('A'.$flag.':AD'.$flag)->applyFromArray($styleArray);
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
						$this->excelfile->getActiveSheet()->getStyle('A'.$flag.':AD'.$flag)->applyFromArray($styleArray);
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
						$this->excelfile->getActiveSheet()->getStyle('A'.$flag.':AA'.$flag)->applyFromArray($styleArray);
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
						$this->excelfile->getActiveSheet()->getStyle('A'.$flag.':AD'.$flag)->applyFromArray($styleArray);
					}else{
						$this->cellStyle("A".$flag, "FFFFFF", "000000", TRUE, 12, "Franklin Gothic Book");
						$hoja->setCellValue("A".$flag."", "ABARROTES, TIENDA, ULTRAMARINOS, TRINCHERAS, MERCADO, TIJERAS, Y TENENCIA AZTECA AUTOSERVICIOS SA. DE CV.");
						$hoja->mergeCells('A'.$flag.':AD'.$flag);
						$this->excelfile->getActiveSheet()->getStyle('A'.$flag.':AD'.$flag)->applyFromArray($styleArray);
						$flag++;
						$hoja->mergeCells('B'.$flag.':G'.$flag);
						$hoja->mergeCells('H'.$flag.':K'.$flag);
						$hoja->mergeCells('L'.$flag.':N'.$flag);
						$hoja->mergeCells('O'.$flag.':Q'.$flag);
						$hoja->mergeCells('R'.$flag.':T'.$flag);
						$hoja->mergeCells('U'.$flag.':W'.$flag);
						$hoja->mergeCells('X'.$flag.':Z'.$flag);
						$hoja->mergeCells('AA'.$flag.':AC'.$flag);
						$this->cellStyle("B".$flag, "FFFFFF", "000000", TRUE, 12, "Franklin Gothic Book");
						$hoja->setCellValue("B".$flag, "PEDIDOS A '".$value->nombre."' ".date("d-m-Y"));
						$this->cellStyle("H".$flag, "01B0F0", "000000", TRUE, 12, "Franklin Gothic Book");
						$hoja->setCellValue("H".$flag, "ABARROTES");
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
						$this->excelfile->getActiveSheet()->getStyle('A'.$flag.':AD'.$flag)->applyFromArray($styleArray);
						$flag++;
						$this->cellStyle("A".$flag.":AD".$flag."", "000000", "FFFFFF", TRUE, 12, "Franklin Gothic Book");
						$hoja->setCellValue("B".$flag, "DESCRIPCIÓN");
						$hoja->mergeCells('H'.$flag.':K'.$flag);
						$hoja->setCellValue("H".$flag, "EXISTENCIAS");
						$hoja->mergeCells('L'.$flag.':N'.$flag);
						$hoja->setCellValue("L".$flag, "EXISTENCIAS");
						$hoja->mergeCells('O'.$flag.':Q'.$flag);
						$hoja->setCellValue("O".$flag, "EXISTENCIAS");
						$hoja->mergeCells('R'.$flag.':T'.$flag);
						$hoja->setCellValue("R".$flag, "EXISTENCIAS");
						$hoja->mergeCells('U'.$flag.':W'.$flag);
						$hoja->setCellValue("U".$flag, "EXISTENCIAS");
						$hoja->mergeCells('X'.$flag.':Z'.$flag);
						$hoja->setCellValue("X".$flag, "EXISTENCIAS");
						$hoja->mergeCells('AA'.$flag.':AC'.$flag);
						$hoja->setCellValue("AA".$flag, "EXISTENCIAS");
						$flag++;
						$this->cellStyle("A".$flag.":AD".$flag."", "000000", "FFFFFF", TRUE, 12, "Franklin Gothic Book");
						$hoja->setCellValue("A".$flag, "CODIGO");
						$hoja->setCellValue("C".$flag, "COSTO");
						$hoja->setCellValue("D".$flag, "SISTEMA");
						$hoja->setCellValue("E".$flag, "PRECIO4");
						$hoja->setCellValue("F".$flag, "2DO");
						$hoja->setCellValue("G".$flag, "PROVEEDOR");
						$hoja->setCellValue("H".$flag, "CAJAS");
						$hoja->setCellValue("I".$flag, "PZAS");
						$hoja->setCellValue("J".$flag, "STOCK");
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
					}
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
								$hoja1->getStyle("A{$flag1}:E{$flag1}")
						                 ->getAlignment()
						                 ->setHorizontal(\PHPExcel_Style_Alignment::HORIZONTAL_LEFT);
						         $registrazo = date('Y-m-d',strtotime($row['registrazo']));
				                 if($this->weekNumber($registrazo) == ($this->weekNumber() + 1) || $this->weekNumber($registrazo) == ($this->weekNumber())){
									$this->cellStyle("A{$flag}", "FF7F71", "000000", FALSE, 12, "Franklin Gothic Book");
									$this->cellStyle("B{$flag}", "FF7F71", "000000", FALSE, 12, "Franklin Gothic Book");
									$this->cellStyle("C{$flag}", "FF7F71", "000000", FALSE, 12, "Franklin Gothic Book");
									$this->cellStyle("E{$flag}", "FF7F71", "000000", FALSE, 12, "Franklin Gothic Book");
								}
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
									$this->cellStyle("I".$flag.":AC".$flag, "FFFFFF", "000000", TRUE, 12, "Franklin Gothic Book");
									$hoja->setCellValue("H{$flag}", $row['proveedor_next']);
									$this->cellStyle("H".$flag, "FFFFFF", "000000", FALSE, 12, "Franklin Gothic Book");
									$hoja->setCellValue("I{$flag}", $row['caja0']);
									$hoja->setCellValue("J{$flag}", $row['pz0']);
									$hoja->setCellValue("K{$flag}", $row['ped0']);
									$this->cellStyle("K{$flag}", "D4EAEF", "000000", TRUE, 12, "Franklin Gothic Book");
									$hoja->setCellValue("L{$flag}", $row['caja1']);
									$hoja->setCellValue("M{$flag}", $row['pz1']);
									$hoja->setCellValue("N{$flag}", $row['ped1']);
									$this->cellStyle("N{$flag}", "D4EAEF", "000000", TRUE, 12, "Franklin Gothic Book");
									$hoja->setCellValue("O{$flag}", $row['caja2']);
									$hoja->setCellValue("P{$flag}", $row['pz2']);
									$hoja->setCellValue("Q{$flag}", $row['ped2']);
									$this->cellStyle("Q{$flag}", "D4EAEF", "000000", TRUE, 12, "Franklin Gothic Book");
									$hoja->setCellValue("R{$flag}", $row['caja3']);
									$hoja->setCellValue("S{$flag}", $row['pz3']);
									$hoja->setCellValue("T{$flag}", $row['ped3']);
									$this->cellStyle("T{$flag}", "D4EAEF", "000000", TRUE, 12, "Franklin Gothic Book");
									$hoja->setCellValue("U{$flag}", $row['caja4']);
									$hoja->setCellValue("V{$flag}", $row['pz4']);
									$hoja->setCellValue("W{$flag}", $row['ped4']);
									$this->cellStyle("W{$flag}", "D4EAEF", "000000", TRUE, 12, "Franklin Gothic Book");
									$hoja->setCellValue("X{$flag}", $row['caja5']);
									$hoja->setCellValue("Y{$flag}", $row['pz5']);
									$hoja->setCellValue("Z{$flag}", $row['ped5']);
									$this->cellStyle("Z{$flag}", "D4EAEF", "000000", TRUE, 12, "Franklin Gothic Book");
									$hoja->setCellValue("AA{$flag}", $row['caja6']);
									$hoja->setCellValue("AB{$flag}", $row['pz6']);
									$hoja->setCellValue("AC{$flag}", $row['ped6']);
									$this->cellStyle("AC{$flag}", "D4EAEF", "000000", TRUE, 12, "Franklin Gothic Book");
									$hoja->setCellValue("AD{$flag}", $row['promocion_first']);
									$hoja->setCellValue("AE{$flag}", "=D".$flag."*K".$flag)->getStyle("AE{$flag}")->getNumberFormat()->setFormatCode('"$"#,##0.00_-');
									$hoja->setCellValue("AF{$flag}", "=D".$flag."*N".$flag)->getStyle("AF{$flag}")->getNumberFormat()->setFormatCode('"$"#,##0.00_-');
									$hoja->setCellValue("AG{$flag}", "=D".$flag."*Q".$flag)->getStyle("AG{$flag}")->getNumberFormat()->setFormatCode('"$"#,##0.00_-');
									$hoja->setCellValue("AH{$flag}", "=D".$flag."*T".$flag)->getStyle("AH{$flag}")->getNumberFormat()->setFormatCode('"$"#,##0.00_-');
									$hoja->setCellValue("AI{$flag}", "=D".$flag."*W".$flag)->getStyle("AI{$flag}")->getNumberFormat()->setFormatCode('"$"#,##0.00_-');
									$hoja->setCellValue("AJ{$flag}", "=D".$flag."*Z".$flag)->getStyle("AJ{$flag}")->getNumberFormat()->setFormatCode('"$"#,##0.00_-');
									$hoja->setCellValue("AK{$flag}", "=D".$flag."*AC".$flag)->getStyle("AK{$flag}")->getNumberFormat()->setFormatCode('"$"#,##0.00_-');
								}else{
									if (number_format(($row['precio_sistema'] - $row['precio_first']),2) === "0.01" || number_format(($row['precio_sistema'] - $row['precio_first']),2) === "-0.01") {
										$hoja->setCellValue("C{$flag}", $row['precio_first'])->getStyle("C{$flag}")->getNumberFormat()->setFormatCode('"$"#,##0.00_-');
										$this->cellStyle("C{$flag}", "FFFFFF", "000000", FALSE, 12, "Franklin Gothic Book");
										$this->cellStyle("C{$flag}", "FFFFFF", "000000", FALSE, 12, "Franklin Gothic Book");
										$this->cellStyle("B{$flag}", "FFFFFF", "000000", FALSE, 12, "Franklin Gothic Book");
									}elseif($row['precio_sistema'] < $row['precio_first']){
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
									$this->cellStyle("H".$flag.":AC".$flag, "FFFFFF", "000000", TRUE, 12, "Franklin Gothic Book");
									$this->cellStyle("G".$flag, "FFFFFF", "000000", FALSE, 12, "Franklin Gothic Book");
									$hoja->setCellValue("H{$flag}", $row['caja0']);
									$hoja->setCellValue("I{$flag}", $row['pz0']);
									if ($row['stocant'] === NULL || $row['stocant'] === 0) {
										$hoja->setCellValue("J{$flag}", 0);
										$hoja->setCellValue("K{$flag}", $row['ped0']);
									}elseif($row['stocant'] < $row['caja0']){
										$hoja->setCellValue("J{$flag}", $row['stocant']);
										$hoja->setCellValue("K{$flag}", 0);
										$this->cellStyle("J{$flag}", "FF4D00", "000000", TRUE, 12, "Franklin Gothic Book");
									}elseif($row['caja0'] > ($row['stocant'] / 2)){
										$hoja->setCellValue("J{$flag}", $row['stocant']);
										$hoja->setCellValue("K{$flag}", 0);
										$this->cellStyle("J{$flag}", "AEFFFB", "000000", TRUE, 12, "Franklin Gothic Book");
									}else{
										$hoja->setCellValue("J{$flag}", $row['stocant']);
										$hoja->setCellValue("K{$flag}", ($row['stocant'] - $row['caja0']));
									}

									$this->cellStyle("K{$flag}", "D4EAEF", "000000", TRUE, 12, "Franklin Gothic Book");
									$hoja->setCellValue("L{$flag}", $row['caja1']);
									$hoja->setCellValue("M{$flag}", $row['pz1']);
									$hoja->setCellValue("N{$flag}", $row['ped1']);
									$this->cellStyle("N{$flag}", "D4EAEF", "000000", TRUE, 12, "Franklin Gothic Book");
									$hoja->setCellValue("O{$flag}", $row['caja2']);
									$hoja->setCellValue("P{$flag}", $row['pz2']);
									$hoja->setCellValue("Q{$flag}", $row['ped2']);
									$this->cellStyle("Q{$flag}", "D4EAEF", "000000", TRUE, 12, "Franklin Gothic Book");
									$hoja->setCellValue("R{$flag}", $row['caja3']);
									$hoja->setCellValue("S{$flag}", $row['pz3']);
									$hoja->setCellValue("T{$flag}", $row['ped3']);
									$this->cellStyle("T{$flag}", "D4EAEF", "000000", TRUE, 12, "Franklin Gothic Book");
									$hoja->setCellValue("U{$flag}", $row['caja4']);
									$hoja->setCellValue("V{$flag}", $row['pz4']);
									$hoja->setCellValue("W{$flag}", $row['ped4']);
									$this->cellStyle("W{$flag}", "D4EAEF", "000000", TRUE, 12, "Franklin Gothic Book");
									$hoja->setCellValue("X{$flag}", $row['caja5']);
									$hoja->setCellValue("Y{$flag}", $row['pz5']);
									$hoja->setCellValue("Z{$flag}", $row['ped5']);
									$this->cellStyle("Z{$flag}", "D4EAEF", "000000", TRUE, 12, "Franklin Gothic Book");
									$hoja->setCellValue("AA{$flag}", $row['caja6']);
									$hoja->setCellValue("AB{$flag}", $row['pz6']);
									$hoja->setCellValue("AC{$flag}", $row['ped6']);
									$this->cellStyle("AC{$flag}", "D4EAEF", "000000", TRUE, 12, "Franklin Gothic Book");
									$hoja->setCellValue("AD{$flag}", $row['promocion_first']);
									$hoja->setCellValue("AE{$flag}", "=C".$flag."*K".$flag)->getStyle("AE{$flag}")->getNumberFormat()->setFormatCode('"$"#,##0.00_-');
									$hoja->setCellValue("AF{$flag}", "=C".$flag."*N".$flag)->getStyle("AF{$flag}")->getNumberFormat()->setFormatCode('"$"#,##0.00_-');
									$hoja->setCellValue("AG{$flag}", "=C".$flag."*Q".$flag)->getStyle("AG{$flag}")->getNumberFormat()->setFormatCode('"$"#,##0.00_-');
									$hoja->setCellValue("AH{$flag}", "=C".$flag."*T".$flag)->getStyle("AH{$flag}")->getNumberFormat()->setFormatCode('"$"#,##0.00_-');
									$hoja->setCellValue("AI{$flag}", "=C".$flag."*W".$flag)->getStyle("AI{$flag}")->getNumberFormat()->setFormatCode('"$"#,##0.00_-');
									$hoja->setCellValue("AJ{$flag}", "=C".$flag."*Z".$flag)->getStyle("AJ{$flag}")->getNumberFormat()->setFormatCode('"$"#,##0.00_-');
									$hoja->setCellValue("AK{$flag}", "=C".$flag."*AC".$flag)->getStyle("AK{$flag}")->getNumberFormat()->setFormatCode('"$"#,##0.00_-');
								}
								$border_style= array('borders' => array('right' => array('style' =>
									PHPExcel_Style_Border::BORDER_THIN,'color' => array('argb' => '000000'),)));
								$this->excelfile->setActiveSheetIndex(1);
								$this->excelfile->getActiveSheet()->getStyle('A'.$flag.':AD'.$flag)->applyFromArray($styleArray);

								$this->excelfile->setActiveSheetIndex(0);
								$this->excelfile->getActiveSheet()->getStyle('A'.$flag1.':E'.$flag1)->applyFromArray($styleArray);

								$hoja->getStyle("A{$flag}:G{$flag}")
						                 ->getAlignment()
						                 ->setHorizontal(\PHPExcel_Style_Alignment::HORIZONTAL_LEFT);

						        if($this->weekNumber($registrazo) == ($this->weekNumber() + 1) || $this->weekNumber($registrazo) == ($this->weekNumber())){
									$this->cellStyle("A{$flag}", "FF7F71", "000000", FALSE, 12, "Franklin Gothic Book");
									$this->cellStyle("B{$flag}", "FF7F71", "000000", FALSE, 12, "Franklin Gothic Book");
								}
								$flag ++;
								$flag1 ++;
							}
						}
					}
					if ($id_proves === "VOLUMEN" || $id_proves === "AMARILLOS"){
						$flagf = $flag;
						$flagfs = $flag - 1;
						$hoja->setCellValue("AE{$flagf}", "=SUM(AE5:AE".$flagfs.")")->getStyle("AE{$flag}")->getNumberFormat()->setFormatCode('"$"#,##0.00_-');
						$hoja->setCellValue("AF{$flagf}", "=SUM(AF5:AF".$flagfs.")")->getStyle("AF{$flag}")->getNumberFormat()->setFormatCode('"$"#,##0.00_-');
						$hoja->setCellValue("AG{$flagf}", "=SUM(AG5:AG".$flagfs.")")->getStyle("AG{$flag}")->getNumberFormat()->setFormatCode('"$"#,##0.00_-');
						$hoja->setCellValue("AH{$flagf}", "=SUM(AH5:AH".$flagfs.")")->getStyle("AH{$flag}")->getNumberFormat()->setFormatCode('"$"#,##0.00_-');
						$hoja->setCellValue("AI{$flagf}", "=SUM(AI5:AI".$flagfs.")")->getStyle("AI{$flag}")->getNumberFormat()->setFormatCode('"$"#,##0.00_-');
						$hoja->setCellValue("AJ{$flagf}", "=SUM(AJ5:AJ".$flagfs.")")->getStyle("AJ{$flag}")->getNumberFormat()->setFormatCode('"$"#,##0.00_-');
						$hoja->setCellValue("AK{$flagf}", "=SUM(AK5:AK".$flagfs.")")->getStyle("AK{$flag}")->getNumberFormat()->setFormatCode('"$"#,##0.00_-');
						$sumall[1] .= "AE".$flagf."+";
						$sumall[2] .= "AF".$flagf."+";
						$sumall[3] .= "AG".$flagf."+";
						$sumall[4] .= "AH".$flagf."+";
						$sumall[5] .= "AI".$flagf."+";
						$sumall[6] .= "AJ".$flagf."+";
						$sumall[7] .= "AK".$flagf."+";
					}else{
						$flagf = $flag;
						$flagfs = $flag - 1;
						$hoja->setCellValue("AE{$flagf}", "=SUM(AE".$flage.":AE".$flagfs.")")->getStyle("AE{$flag}")->getNumberFormat()->setFormatCode('"$"#,##0.00_-');
						$hoja->setCellValue("AF{$flagf}", "=SUM(AF".$flage.":AF".$flagfs.")")->getStyle("AF{$flag}")->getNumberFormat()->setFormatCode('"$"#,##0.00_-');
						$hoja->setCellValue("AG{$flagf}", "=SUM(AG".$flage.":AG".$flagfs.")")->getStyle("AG{$flag}")->getNumberFormat()->setFormatCode('"$"#,##0.00_-');
						$hoja->setCellValue("AH{$flagf}", "=SUM(AH".$flage.":AH".$flagfs.")")->getStyle("AH{$flag}")->getNumberFormat()->setFormatCode('"$"#,##0.00_-');
						$hoja->setCellValue("AI{$flagf}", "=SUM(AI".$flage.":AI".$flagfs.")")->getStyle("AI{$flag}")->getNumberFormat()->setFormatCode('"$"#,##0.00_-');
						$hoja->setCellValue("AJ{$flagf}", "=SUM(AJ".$flage.":AJ".$flagfs.")")->getStyle("AJ{$flag}")->getNumberFormat()->setFormatCode('"$"#,##0.00_-');
						$hoja->setCellValue("AK{$flagf}", "=SUM(AK".$flage.":AK".$flagfs.")")->getStyle("AK{$flag}")->getNumberFormat()->setFormatCode('"$"#,##0.00_-');
						$sumall[1] .= "AE".$flagf."+";
						$sumall[2] .= "AF".$flagf."+";
						$sumall[3] .= "AG".$flagf."+";
						$sumall[4] .= "AH".$flagf."+";
						$sumall[5] .= "AI".$flagf."+";
						$sumall[6] .= "AJ".$flagf."+";
						$sumall[7] .= "AK".$flagf."+";
						$flage = $flag + 7;
					}
					$flag++;
					$flag1++;
					$flag1++;
					$flag1++;
					$flag++;
					$flag++;
				}
			}
		}

		$this->cellStyle("B".$flag, "01B0F0", "000000", TRUE, 12, "Franklin Gothic Book");
		$hoja->setCellValue("B".$flag, "ABARROTES");
		$hoja->setCellValue("C{$flag}", "=(".substr($sumall[1],0,-1).")")->getStyle("C{$flag}")->getNumberFormat()->setFormatCode('"$"#,##0.00_-');
		$flag++;
		$this->cellStyle("B".$flag, "E26C0B", "000000", TRUE, 12, "Franklin Gothic Book");
		$hoja->setCellValue("B".$flag, "TIENDA");
		$hoja->setCellValue("C{$flag}", "=(".substr($sumall[2],0,-1).")")->getStyle("C{$flag}")->getNumberFormat()->setFormatCode('"$"#,##0.00_-');
		$flag++;
		$this->cellStyle("B".$flag, "C5C5C5", "000000", TRUE, 12, "Franklin Gothic Book");
		$hoja->setCellValue("B".$flag, "ULTRAMARINOS");
		$hoja->setCellValue("C{$flag}", "=(".substr($sumall[3],0,-1).")")->getStyle("C{$flag}")->getNumberFormat()->setFormatCode('"$"#,##0.00_-');
		$flag++;
		$this->cellStyle("B".$flag, "92D051", "000000", TRUE, 12, "Franklin Gothic Book");
		$hoja->setCellValue("B".$flag, "TRINCHERAS");
		$hoja->setCellValue("C{$flag}", "=(".substr($sumall[4],0,-1).")")->getStyle("C{$flag}")->getNumberFormat()->setFormatCode('"$"#,##0.00_-');
		$flag++;
		$this->cellStyle("B".$flag, "B1A0C7", "000000", TRUE, 12, "Franklin Gothic Book");
		$hoja->setCellValue("B".$flag, "AZT MERCADO");
		$hoja->setCellValue("C{$flag}", "=(".substr($sumall[5],0,-1).")")->getStyle("C{$flag}")->getNumberFormat()->setFormatCode('"$"#,##0.00_-');
		$flag++;
		$this->cellStyle("B".$flag, "DA9694", "000000", TRUE, 12, "Franklin Gothic Book");
		$hoja->setCellValue("B".$flag, "TENENCIA");
		$hoja->setCellValue("C{$flag}", "=(".substr($sumall[6],0,-1).")")->getStyle("C{$flag}")->getNumberFormat()->setFormatCode('"$"#,##0.00_-');
		$flag++;
		$this->cellStyle("B".$flag, "4CACC6", "000000", TRUE, 12, "Franklin Gothic Book");
		$hoja->setCellValue("B".$flag, "TIJERAS");
		$hoja->setCellValue("C{$flag}", "=(".substr($sumall[7],0,-1).")")->getStyle("C{$flag}")->getNumberFormat()->setFormatCode('"$"#,##0.00_-');
		$flag++;

		
		$dias = array("DOMINGO","LUNES","MARTES","MIÉRCOLES","JUEVES","VIERNES","SÁBADO");
		$meses = array("ENERO","FEBRERO","MARZO","ABRIL","MAYO","JUNIO","JULIO","AGOSTO","SEPTIEMBRE","OCTUBRE","NOVIEMBRE","DICIEMBRE");

		$fecha =  $dias[date('w')]." ".date('d')." DE ".$meses[date('n')-1]. " DEL ".date('Y') ;
		$file_name = "FORMATO ".$filenam." ".$fecha.".xlsx"; //Nombre del documento con extención
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
						if($row['estatus'] >= 4){
							$this->cellStyle("B{$row_print}", "04B486", "000000", FALSE, 10, "Franklin Gothic Book");
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
						if($row['estatus'] >= 4){
							$this->cellStyle("B{$row_print}", "04B486", "000000", FALSE, 10, "Franklin Gothic Book");
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
					$aprod = $this->prod_mdl->get(NULL, ['id_producto'=>$antes->id_producto])[0];
					$aprov = $this->usua_mdl->get(NULL, ['id_usuario'=>$antes->id_proveedor])[0];
					$cambios = [
						"id_usuario" => $user["id_usuario"],
						"fecha_cambio" => date('Y-m-d H:i:s'),
						"accion" => "Actualizo faltantes",
						"antes" => "id_faltante: ".$antes->id_faltante." /Proveedor: ".$aprov->nombre." /Producto:".$aprod->nombre."/F Termino: ".$antes->fecha_termino.
								"/Semanas: ".$antes->no_semanas,
						"despues" => "El usuario cambio las semanas: \n/Semanas: ".$no_semanas[$i]."/Fecha Termino:".$fecha->format('Y-m-d H:i:s')];
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
					$aprod = $this->prod_mdl->get(NULL, ['id_producto'=>$cotz[$i] ] )[0];
					$aprov = $this->usua_mdl->get(NULL, ['id_usuario'=>$this->input->post('id_pro')])[0];
						$cambios = [
						"id_usuario" => $user["id_usuario"],
						"fecha_cambio" => date('Y-m-d H:i:s'),
						"accion" => "Inserto faltantes",
						"antes" => "Proveedor: ".$aprov->nombre." /Producto:".$aprod->nombre."/F Termino: ".$fecha->format('Y-m-d H:i:s').
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

	public function fill_excel_bajos(){
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

		$this->cellStyle("A1:K2", "000000", "FFFFFF", TRUE, 12, "Franklin Gothic Book");
		$border_style= array('borders' => array('right' => array('style' =>
			PHPExcel_Style_Border::BORDER_THIN,'color' => array('argb' => '000000'),)));

		$hoja->setCellValue("B1", "DESCRIPCIÓN")->getColumnDimension('B')->setWidth(70);
		$hoja->setCellValue("C1", "PRECIO")->getColumnDimension('C')->setWidth(15);
		$hoja->setCellValue("D1", "PRECIO PROMOCIÓN")->getColumnDimension('D')->setWidth(15);
		$hoja->setCellValue("E1", "OBSERVACIONES")->getColumnDimension('E')->setWidth(22);
		$hoja->setCellValue("F1", "SISTEMA")->getColumnDimension('F')->setWidth(12);
		$hoja->setCellValue("G1", "PRECIO 4")->getColumnDimension('G')->setWidth(15);
		$hoja->setCellValue("H1", "DIFERENCIA")->getColumnDimension('H')->setWidth(15);
		$hoja->setCellValue("I1", "PROVEEDOR")->getColumnDimension('I')->setWidth(22);
		$hoja->setCellValue("J1", "PRECIO")->getColumnDimension('J')->setWidth(12);
		$hoja->setCellValue("K1", "OBSERVACIONES")->getColumnDimension('K')->setWidth(22);

		$hoja->setCellValue("A2", "CÓDIGO")->getColumnDimension('A')->setWidth(30); //Nombre y ajuste de texto a la columna

		$hoja->mergeCells('B2:F2');

		$fecha = new DateTime(date('Y-m-d H:i:s'));
		$intervalo = new DateInterval('P2D');
		$fecha->add($intervalo);

		$productos =  $this->ct_mdl->getProveedorBajos(NULL,$fecha->format('Y-m-d H:i:s'),$this->input->post('id_pro'));

		$provs = $this->usua_mdl->get(NULL, ['id_usuario'=>$this->input->post('id_pro')])[0];
		$hoja->setCellValue("B2", "COMPARACIÓN DE PRECIOS ".$provs->nombre)->getColumnDimension('B')->setWidth(70);
		$row_print = 3;


		if ($productos){
			foreach ($productos as $key => $value){

			if($value->color === '#92CEE3'){
				$this->cellStyle("A{$row_print}", "92CEE3", "000000", FALSE, 10, "Franklin Gothic Book");
			}else{
				$this->cellStyle("A{$row_print}", "FFFFFF", "000000", FALSE, 10, "Franklin Gothic Book");
			}
			$hoja->setCellValue("A{$row_print}", $value->codigo)->getStyle("A{$row_print}")->getNumberFormat()->setFormatCode('# ???/???');//Formato de fraccion
			$hoja->getStyle("A{$row_print}")->applyFromArray($border_style);
			$hoja->setCellValue("B{$row_print}", $value->producto);
			if($value->estatus == 2){
				$this->cellStyle("B{$row_print}", "00B0F0", "000000", FALSE, 10, "Franklin Gothic Book");
			}
			if($value->estatus == 3){
				$this->cellStyle("B{$row_print}", "FFF900", "000000", FALSE, 10, "Franklin Gothic Book");
			}
			if($value->estatus == 4){
				$this->cellStyle("B{$row_print}", "04B486", "000000", FALSE, 10, "Franklin Gothic Book");
			}

			$hoja->getStyle("B{$row_print}")->applyFromArray($border_style);
			if($value->colorp == 1){
				$this->cellStyle("C{$row_print}", "D6DCE4", "000000", FALSE, 10, "Franklin Gothic Book");
			}else{
				$this->cellStyle("C{$row_print}", "FFFFFF", "000000", FALSE, 10, "Franklin Gothic Book");
			}
			$hoja->setCellValue("C{$row_print}", $value->proves_precio)->getStyle("C{$row_print}")->getNumberFormat()->setFormatCode('"$"#,##0.00_-');
			$hoja->getStyle("C{$row_print}")->applyFromArray($border_style);

			$hoja->setCellValue("D{$row_print}", $value->proves_promo)->getStyle("D{$row_print}")->getNumberFormat()->setFormatCode('"$"#,##0.00_-');
			$hoja->getStyle("D{$row_print}")->applyFromArray($border_style);
			$hoja->setCellValue("E{$row_print}", $value->proves_obs);
			$hoja->getStyle("E{$row_print}")->applyFromArray($border_style);
			$hoja->setCellValue("F{$row_print}", $value->precio_sistema)->getStyle("F{$row_print}")->getNumberFormat()->setFormatCode('"$"#,##0.00_-');
			$hoja->getStyle("F{$row_print}")->applyFromArray($border_style);
			$hoja->setCellValue("G{$row_print}", $value->precio_four)->getStyle("G{$row_print}")->getNumberFormat()->setFormatCode('"$"#,##0.00_-');
			$hoja->getStyle("G{$row_print}")->applyFromArray($border_style);
			$diffes = $value->proves_promo - $value->precio_first;
			$hoja->setCellValue("H{$row_print}", $diffes)->getStyle("H{$row_print}")->getNumberFormat()->setFormatCode('"$"#,##0.00_-');
			$hoja->getStyle("H{$row_print}")->applyFromArray($border_style);
			$hoja->setCellValue("I{$row_print}", $value->proveedor_first);
			$hoja->getStyle("I{$row_print}")->applyFromArray($border_style);
			$hoja->setCellValue("J{$row_print}", $value->precio_first)->getStyle("J{$row_print}")->getNumberFormat()->setFormatCode('"$"#,##0.00_-');
			$hoja->getStyle("J{$row_print}")->applyFromArray($border_style);
			$hoja->setCellValue("K{$row_print}", $value->observaciones_first);
			$hoja->getStyle("K{$row_print}")->applyFromArray($border_style);

			if($value->proves === $value->proveedor_first){
				$this->cellStyle("H{$row_print}", "FF0066", "000000", FALSE, 10, "Franklin Gothic Book");
				$hoja->setCellValue("H{$row_print}", $diffes);
				$hoja->setCellValue("I{$row_print}", $value->proveedor_next);
				$hoja->setCellValue("J{$row_print}", $value->precio_next);
				$hoja->setCellValue("K{$row_print}", $value->promocion_next);
			}elseif($value->proves_promo >= $value->precio_first){
				$this->cellStyle("H{$row_print}", "FFE6F0", "000000", FALSE, 10, "Franklin Gothic Book");
			}

			$row_print++;

			}
		}
		$hoja->getStyle("A3:K{$row_print}")
                 ->getAlignment()
                 ->setHorizontal(\PHPExcel_Style_Alignment::HORIZONTAL_LEFT);

		$file_name = "Comparación ".$provs->nombre.".xlsx"; //Nombre del documento con extención
		header("Content-Type: application/vnd.ms-excel; charset=utf-8");
		header("Content-Disposition: attachment;filename=".$file_name);
		header("Cache-Control: max-age=0");
		$excel_Writer = PHPExcel_IOFactory::createWriter($this->excelfile, "Excel2007");
		$excel_Writer->save("php://output");

	}

	public function upload_faltantes($idpro = NULL){
		$user = $this->session->userdata();
		$config['upload_path']          = './assets/uploads/faltantes/';
        $config['allowed_types']        = 'xlsx|xls';
        $config['max_size']             = 100;
        $config['max_width']            = 1024;
        $config['max_height']           = 768;
        $config['max_height']           = 768;
	    $user = $this->session->userdata();
        $this->load->library('upload', $config);
        $this->upload->do_upload('file_faltantes');
		$this->load->library("excelfile");
		ini_set("memory_limit", "-1");
		ini_set("max_execution_time", "-1");
		$file = $_FILES["file_faltantes"]["tmp_name"];
		$objExcel = PHPExcel_IOFactory::load($file);
		$sheet = $objExcel->getSheet(0);
		$num_rows = $sheet->getHighestDataRow();
		for ($i=1; $i<=$num_rows; $i++) {
			if($sheet->getCell('B'.$i)->getValue() !=''){
				$productos = $this->prod_mdl->get("id_producto",['codigo'=> htmlspecialchars($sheet->getCell('A'.$i)->getValue(), ENT_QUOTES, 'UTF-8')])[0];
				if (sizeof($productos) > 0) {
					$no_semanas = $sheet->getCell('C'.$i)->getValue() == '' ? 1 : $sheet->getCell('C'.$i)->getValue();
					$fecha = new DateTime(date('Y-m-d H:i:s'));
					$intervalo = new DateInterval('P'.$no_semanas.'W');
					$fecha->add($intervalo);
					$fecha->format('Y-m-d H:i:s');
					$antes =  $this->falt_mdl->get(NULL, ['id_producto' => $productos->id_producto, 'fecha_termino > ' => date("Y-m-d H:i:s"), 'id_proveedor' => $idpro])[0];

					if($antes){
						$aprod = $this->prod_mdl->get(NULL, ['id_producto'=>$antes->id_producto])[0];
						$aprov = $this->usua_mdl->get(NULL, ['id_usuario'=>$antes->id_proveedor])[0];
						$cambios = [
							"id_usuario" => $user["id_usuario"],
							"fecha_cambio" => date('Y-m-d H:i:s'),
							"accion" => "Actualizo faltantes",
							"antes" => "id_faltante: ".$antes->id_faltante." /Proveedor: ".$aprov->nombre." /Producto:".$aprod->nombre."/F Termino: ".$antes->fecha_termino.
									"/Semanas: ".$no_semanas." ",
							"despues" => "El usuario cambio las semanas: \n/Semanas: ".$no_semanas."/Fecha Termino:".$fecha->format('Y-m-d H:i:s')];
						$data['cambios'] = $this->cambio_md->insert($cambios);
						$data ['id_faltante'] = $this->falt_mdl->update([
							"no_semanas" => $no_semanas,
							"fecha_termino" => $fecha->format('Y-m-d H:i:s')
						], $antes->id_faltante);
						$mensaje = [
							"id" 	=> 'Éxito',
							"desc"	=> 'Faltantes actualizados correctamente',
							"type"	=> 'success'
						];
					}else{
						$aprod = $this->prod_mdl->get(NULL, ['id_producto'=>$productos->id_producto ] )[0];
						$aprov = $this->usua_mdl->get(NULL, ['id_usuario'=>$idpro])[0];
							$cambios = [
							"id_usuario" => $user["id_usuario"],
							"fecha_cambio" => date('Y-m-d H:i:s'),
							"accion" => "Inserto faltantes",
							"antes" => "Proveedor: ".$aprov->nombre." /Producto:".$aprod->nombre."/F Termino: ".$fecha->format('Y-m-d H:i:s').
									"/Semanas: ".$no_semanas."",
							"despues" => "El usuario agrego faltantes."];
						$data['cambios'] = $this->cambio_md->insert($cambios);
						$new_faltante=[
							"id_producto"		=>	$productos->id_producto,
							"fecha_termino"	=>	$fecha->format('Y-m-d H:i:s'),
							"no_semanas"		=>	$no_semanas,
							"id_proveedor" => $idpro
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
		}


		$this->jsonResponse($mensaje);
	}

	public function add_faltante($id_prove){
		$data["usuario"] = $this->user_md->get(NULL,["id_usuario" => $id_prove])[0];
		$data["title"]="AGREGAR FALTANTE A ".$data["usuario"]->nombre;
		$data["productos"] = $this->prod_mdl->get("id_producto, nombre");
		$data["view"]=$this->load->view("Cotizaciones/falta_add", $data, TRUE);
		$data["button"]="<button class='btn btn-success new_falta' type='button'>
											<span class='bold'><i class='fa fa-floppy-o'></i></span> &nbsp;Agregar Faltante
										</button>";
		$this->jsonResponse($data);
	}

	public function save_falta(){
		$user = $this->session->userdata();
		$antes =  $this->falt_mdl->get(NULL, ['id_producto' => $this->input->post('id_producto'), 'fecha_termino > ' => date("Y-m-d H:i:s"), 'id_proveedor' => $this->input->post('id_proveedor')])[0];
		$fecha = new DateTime(date('Y-m-d H:i:s'));
		$intervalo = new DateInterval('P'.$this->input->post('semanas').'W');
		$fecha->add($intervalo);
		$aprod = $this->prod_mdl->get(NULL, ['id_producto'=>$this->input->post('id_producto')])[0];
		$aprov = $this->usua_mdl->get(NULL, ['id_usuario'=>$this->input->post('id_proveedor')])[0];

		if($antes){
			$cambios = [
						"id_usuario" => $user["id_usuario"],
						"fecha_cambio" => date('Y-m-d H:i:s'),
						"accion" => "Actualizo faltantes",
						"antes" => "id_faltante: ".$antes->id_faltante." /Proveedor: ".$aprov->nombre." /Producto:".$aprod->nombre."/F Termino: ".$antes->fecha_termino.
								"/Semanas: ".$antes->no_semanas." ",
						"despues" => "El usuario cambio las semanas: \n/Semanas: ".$this->input->post('semanas')."/Fecha Termino:".$fecha->format('Y-m-d H:i:s')];
			$data ['id_faltante'] = $this->falt_mdl->update([
				"no_semanas" => $this->input->post('semanas'),
				"fecha_termino" => $fecha->format('Y-m-d H:i:s')
			], $antes->id_faltante);
			$data['cambios'] = $this->cambio_md->insert($cambios);
		}else{
			$faltante = [
				'id_producto'		=>	$this->input->post('id_producto'),
				'id_proveedor'		=>	$this->input->post('id_proveedor'),
				'fecha_termino'	=>	$fecha->format('Y-m-d H:i:s'),
				'no_semanas' => $this->input->post('semanas')
			];
				$data ['id_faltante'] = $this->falt_mdl->insert($faltante);
				$cambios = [
				"id_usuario" => $user["id_usuario"],
				"fecha_cambio" => date('Y-m-d H:i:s'),
				"accion" => "Inserto faltantes",
				"antes" => "Proveedor: ".$aprov->nombre." /Producto:".$aprod->nombre."/F Termino: ".$fecha->format('Y-m-d H:i:s').
						"/Semanas: ".$this->input->post('semanas')."",
				"despues" => "El usuario agrego faltantes."];
			$data['cambios'] = $this->cambio_md->insert($cambios);
		}

		$mensaje = [
			"id" 	=> 'Éxito',
			"desc"	=> 'Cotización registrada correctamente',
			"type"	=> 'success'
		];
		$this->jsonResponse($mensaje);
	}

	public function upload_expos($idesp){
		$fecha = new DateTime(date('Y-m-d H:i:s'));
		$intervalo = new DateInterval('P2D');
		$fecha->add($intervalo);
		if($idesp == 0){
			$proveedor = $this->session->userdata('id_usuario');
		}else{
			$proveedor = $idesp;
		}

		$cfile =  $this->usua_mdl->get(NULL, ['id_usuario' => $proveedor])[0];
		$nams = preg_replace('/\s+/', '_', $cfile->nombre);
		$filen = "Cotizacion".$nams."".rand();


		$config['upload_path']          = './assets/uploads/expo/';
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
					$cotiz =  $this->expo_mdl->get(NULL, ['id_producto' => $productos->id_producto, 'WEEKOFYEAR(fecha_registro)' => $this->weekNumber($fecha->format('Y-m-d H:i:s')), 'id_proveedor' => $proveedor])[0];
					$new_cotizacion=[
						"id_producto"		=>	$productos->id_producto,
						"id_proveedor"		=>	$proveedor,//Recupera el id_usuario activo
						"precio"			=>	$precio,
						"num_one"			=>	$column_one,
						"num_two"			=>	$column_two,
						"descuento"			=>	$descuento,
						"precio_promocion"	=>	$precio_promocion,
						"fecha_registro"	=>	$fecha->format('Y-m-d H:i:s'),
						"observaciones"		=>	$sheet->getCell('D'.$i)->getValue(),
						"estatus" => 1];
					if($cotiz){
						$data['cotizacion']=$this->expo_mdl->update($new_cotizacion, ['id_cotizacion' => $cotiz->id_cotizacion]);
					}else{
						$data['cotizacion']=$this->expo_mdl->insert($new_cotizacion);
					}

				}
			}
		}
		if (sizeof($new_cotizacion) > 0) {
			$aprov = $this->usua_mdl->get(NULL, ['id_usuario'=>$proveedor])[0];
			$cambios=[
					"id_usuario"		=>	$this->session->userdata('id_usuario'),
					"fecha_cambio"		=>	date("Y-m-d H:i:s"),
					"antes"			=>	"El usuario sube archivo de expo cotizaciones de ".$aprov->nombre,
					"despues"			=>	"assets/uploads/expo/".$filen.".xlsx",
					"accion"			=>	"Sube Archivo"
				];
			$data['cambios']=$this->cambio_md->insert($cambios);
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

	public function saveexpos($idesp){
		if($idesp == 0){
			$proveedor = $this->session->userdata('id_usuario');
		}else{
			$proveedor = $idesp;
		}
		$fecha = new DateTime(date('Y-m-d H:i:s'));
		$intervalo = new DateInterval('P2D');
		$fecha->add($intervalo);
		$cotiz =  $this->expo_mdl->get(NULL, ['id_producto' => $this->input->post('id_producto'), 'WEEKOFYEAR(fecha_registro)' => $this->weekNumber($fecha->format('Y-m-d H:i:s')), 'id_proveedor' => $proveedor])[0];
		$aprod = $this->prod_mdl->get(NULL, ['id_producto'=>$this->input->post('id_producto')])[0];
		$aprov = $this->usua_mdl->get(NULL, ['id_usuario'=>$proveedor])[0];

			$cotizacion = [
				'id_producto'		=>	$this->input->post('id_producto'),
				'id_proveedor'		=>	$proveedor,
				'num_one'			=>	$this->input->post('num_one'),
				'num_two'			=>	$this->input->post('num_two'),
				'precio'			=>	str_replace(',', '', $this->input->post('precio')),//precio base
				'precio_promocion'	=>	($this->input->post('precio_promocion') > 0) ? str_replace(',', '', $this->input->post('precio_promocion')) : str_replace(',', '', $this->input->post('precio')),//precio con promoción
				'descuento'			=>	str_replace(',', '', $this->input->post('porcentaje')),
				'fecha_registro'	=>	$fecha->format('Y-m-d H:i:s'),
				'observaciones'		=>	strtoupper($this->input->post('observaciones')),
				'estatus' => 1
			];

			if($cotiz){
				$data['cotizacin']=$this->expo_mdl->update($cotizacion, ['id_cotizacion' => $cotiz->id_cotizacion]);
			}else{
				$data['cotizacin']=$this->expo_mdl->insert($cotizacion);
			}
			$cambios = [
				"id_usuario" => $this->session->userdata('id_usuario'),
				"fecha_cambio" => date('Y-m-d H:i:s'),
				"accion" => "Cotizacion Nueva Con Faltante",
				"antes" => "Nueva cotización",
				"despues" => "Producto: ".$aprod->nombre."\n///Proveedor: ".$aprov->nombre."\n///Precio: ".str_replace(',', '', $this->input->post('precio'))."\n///Precio promoción: ".
							(($this->input->post('precio_promocion') > 0) ? str_replace(',', '', $this->input->post('precio_promocion')) : str_replace(',', '', $this->input->post('precio')))." ".
							"\n///".$this->input->post('num_one')." EN ".$this->input->post('num_two')."\n///Descuento: ".str_replace(',', '', $this->input->post('porcentaje')).
							"\n///Observaciones: ".strtoupper($this->input->post('observaciones'))
			];
			$data['cambios'] = $this->cambio_md->insert($cambios);


		$mensaje = [
			"id" 	=> 'Éxito',
			"desc"	=> 'Cotización registrada correctamente',
			"type"	=> 'success'
		];
		$this->jsonResponse($mensaje);
	}

	public function proveedorExpos($ides){
		$fecha = new DateTime(date('Y-m-d H:i:s'));
		$intervalo = new DateInterval('P2D');
		$fecha->add($intervalo);

		$where=["expocotz.id_proveedor" => $ides, "expocotz.estatus <> " => 0 ,
				"WEEKOFYEAR(expocotz.fecha_registro)" => $this->weekNumber($fecha->format('Y-m-d H:i:s'))];
		$data["cotizaciones"] = $this->expo_mdl->getAllCotizaciones($where);
		$this->jsonResponse($data["cotizaciones"]);
	}

	public function comparaExpo(){
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

		$this->cellStyle("A1:AM2", "000000", "FFFFFF", TRUE, 12, "Franklin Gothic Book");
		$border_style= array('borders' => array('right' => array('style' =>
			PHPExcel_Style_Border::BORDER_THIN,'color' => array('argb' => '000000'),)));
		$hoja->setCellValue("A2", "CÓDIGO")->getColumnDimension('A')->setWidth(30); //Nombre y ajuste de texto a la columna
		$hoja->setCellValue("B1", "DESCRIPCIÓN")->getColumnDimension('B')->setWidth(50);
		$hoja->setCellValue("C2", "SISTEMA")->getColumnDimension('C')->setWidth(12);
		$hoja->setCellValue("D2", "PRECIO 4")->getColumnDimension('D')->setWidth(12);
		$hoja->setCellValue("E1", "PRECIO")->getColumnDimension('E')->setWidth(12);
		$hoja->setCellValue("E2", "MÁXIMO")->getColumnDimension('E')->setWidth(12);
		$hoja->setCellValue("F1", "PRECIO")->getColumnDimension('F')->setWidth(12);
		$hoja->setCellValue("F2", "PROMEDIO")->getColumnDimension('F')->setWidth(12);
		$hoja->setCellValue("G1", "DIF")->getColumnDimension('G')->setWidth(12);
		$hoja->setCellValue("G2", "SISTEMA")->getColumnDimension('G')->setWidth(12);
		$hoja->setCellValue("H1", "DIF 1ER")->getColumnDimension('H')->setWidth(12);
		$hoja->setCellValue("I1", "DIF 2DO")->getColumnDimension('I')->setWidth(12);
		$hoja->setCellValue("J1", "PRECIO")->getColumnDimension('J')->setWidth(12);
		$hoja->setCellValue("K1", "CON")->getColumnDimension('K')->setWidth(12);
		$hoja->setCellValue("K1", "PROMOCIÓN")->getColumnDimension('K')->setWidth(12);
		$hoja->setCellValue("L1", "OBSERVACIÓN")->getColumnDimension('L')->setWidth(30);
		$hoja->setCellValue("M1", "PRECIO MENOR")->getColumnDimension('M')->setWidth(12);
		$hoja->setCellValue("N1", "PROVEEDOR")->getColumnDimension('N')->setWidth(15);
		$hoja->setCellValue("O1", "OBSERVACIÓN")->getColumnDimension('O')->setWidth(30);
		$hoja->setCellValue("P1", "2DO PRECIO")->getColumnDimension('P')->setWidth(12);
		$hoja->setCellValue("Q1", "2DO PROVEEDOR")->getColumnDimension('Q')->setWidth(15);
		$hoja->setCellValue("R1", "2DA OBSERVACIÓN")->getColumnDimension('R')->setWidth(30);
		$hoja->mergeCells('S1:U1');
		$this->cellStyle("S1", "01B0F0", "000000", TRUE, 12, "Franklin Gothic Book");
		$hoja->setCellValue("S1", "ABARROTES");
		$hoja->mergeCells('V1:X1');
		$this->cellStyle("V1", "E26C0B", "000000", TRUE, 12, "Franklin Gothic Book");
		$hoja->setCellValue("V1", "TIENDA");
		$hoja->mergeCells('Y1:AA1');
		$this->cellStyle("Y1", "C5C5C5", "000000", TRUE, 12, "Franklin Gothic Book");
		$hoja->setCellValue("Y1", "ULTRAMARINOS");
		$hoja->mergeCells('AB1:AD1');
		$this->cellStyle("AB1", "92D051", "000000", TRUE, 12, "Franklin Gothic Book");
		$hoja->setCellValue("AB1", "TRINCHERAS");
		$hoja->mergeCells('AE1:AG1');
		$this->cellStyle("AE1", "B1A0C7", "000000", TRUE, 12, "Franklin Gothic Book");
		$hoja->setCellValue("AE1", "AZT MERCADO");
		$hoja->mergeCells('AH1:AJ1');
		$this->cellStyle("AH1", "DA9694", "000000", TRUE, 12, "Franklin Gothic Book");
		$hoja->setCellValue("AH1", "TENENCIA");
		$hoja->mergeCells('AK1:AM1');
		$this->cellStyle("AK1", "4CACC6", "000000", TRUE, 12, "Franklin Gothic Book");
		$hoja->setCellValue("AK1", "TIJERAS");
		$hoja->setCellValue("S2", "CAJAS");
		$hoja->setCellValue("T2", "PZAS");
		$hoja->setCellValue("U2", "PEDIDO");
		$hoja->setCellValue("V2", "CAJAS");
		$hoja->setCellValue("W2", "PZAS");
		$hoja->setCellValue("X2", "PEDIDO");
		$hoja->setCellValue("Y2", "CAJAS");
		$hoja->setCellValue("Z2", "PZAS");
		$hoja->setCellValue("AA2", "PEDIDO");
		$hoja->setCellValue("AB2", "CAJAS");
		$hoja->setCellValue("AC2", "PZAS");
		$hoja->setCellValue("AD2", "PEDIDO");
		$hoja->setCellValue("AE2", "CAJAS");
		$hoja->setCellValue("AF2", "PZAS");
		$hoja->setCellValue("AG2", "PEDIDO");
		$hoja->setCellValue("AH2", "CAJAS");
		$hoja->setCellValue("AI2", "PZAS");
		$hoja->setCellValue("AJ2", "PEDIDO");
		$hoja->setCellValue("AK2", "CAJAS");
		$hoja->setCellValue("AL2", "PZAS");
		$hoja->setCellValue("AM2", "PEDIDO");
		$hoja->setCellValue("AU1", "TOTAL POR");
		$hoja->setCellValue("AU2", "PRODUCTO");
		$this->cellStyle("AU1", "000000", "FFFFFF", TRUE, 12, "Franklin Gothic Book");
		$this->cellStyle("AU2", "000000", "FFFFFF", TRUE, 12, "Franklin Gothic Book");




		$fecha = new DateTime(date('Y-m-d H:i:s'));
		$intervalo = new DateInterval('P2D');
		$fecha->sub($intervalo);
		$fecha = $fecha->format('Y-m-d H:i:s');
		$where = ["expo.id_proveedor"=>$this->input->post("id_pro")];
		$cotizacionesProveedor = $this->expo_mdl->comparaCotizaciones2($where, $fecha);

		$row_print =2;
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
						$hoja->setCellValue("E{$row_print}", $row['precio_maximo'])->getStyle("E{$row_print}")->getNumberFormat()->setFormatCode('"$"#,##0.00_-');
						$hoja->getStyle("E{$row_print}")->applyFromArray($border_style);
						$hoja->setCellValue("F{$row_print}", $row['precio_promedio'])->getStyle("F{$row_print}")->getNumberFormat()->setFormatCode('"$"#,##0.00_-');
						$hoja->getStyle("F{$row_print}")->applyFromArray($border_style);


						$dif1 = $row["precio_sistema"] - $row["xpromo"];

						if ($dif1 >= ($row["precio_sistema"] * .30) || $dif1 <= (($row["precio_sistema"] * .30) * (-1))) {
							$hoja->setCellValue("G{$row_print}", $dif1)->getStyle("G{$row_print}")->getNumberFormat()->setFormatCode('"$"#,##0.00_-');
							$hoja->getStyle("G{$row_print}")->applyFromArray($border_style);
							$this->cellStyle("G{$row_print}", "FF0066", "000000", FALSE, 12, "Franklin Gothic Book");
						}else{
							$hoja->setCellValue("G{$row_print}", $dif1)->getStyle("G{$row_print}")->getNumberFormat()->setFormatCode('"$"#,##0.00_-');
							$hoja->getStyle("G{$row_print}")->applyFromArray($border_style);
							$this->cellStyle("G{$row_print}", "FFE6F0", "000000", FALSE, 12, "Franklin Gothic Book");
						}

						$dif2 = $row["precio_first"] - $row["xpromo"];
						if($row['precio_first'] !== NULL){
							if ($dif2 >= ($row["precio_first"] * .30) || $dif2 <= (($row["precio_first"] * .30) * (-1))) {
								$hoja->setCellValue("H{$row_print}", $dif2)->getStyle("H{$row_print}")->getNumberFormat()->setFormatCode('"$"#,##0.00_-');
								$hoja->getStyle("H{$row_print}")->applyFromArray($border_style);
								$this->cellStyle("H{$row_print}", "FF0066", "000000", FALSE, 12, "Franklin Gothic Book");
							}else{
								$hoja->setCellValue("H{$row_print}", $dif2)->getStyle("H{$row_print}")->getNumberFormat()->setFormatCode('"$"#,##0.00_-');
								$hoja->getStyle("H{$row_print}")->applyFromArray($border_style);
								$this->cellStyle("H{$row_print}", "FFE6F0", "000000", FALSE, 12, "Franklin Gothic Book");
							}
						}else{
							$hoja->getStyle("H{$row_print}")->applyFromArray($border_style);
							$this->cellStyle("H{$row_print}", "FFE6F0", "000000", FALSE, 12, "Franklin Gothic Book");
						}

						$dif3 = $row["precio_next"] - $row["xpromo"];
						if($row['precio_next'] !== NULL){
							if ($dif3 >= ($row["precio_next"] * .30) || $dif3 <= (($row["precio_next"] * .30) * (-1))) {
								$hoja->setCellValue("I{$row_print}", $dif3)->getStyle("I{$row_print}")->getNumberFormat()->setFormatCode('"$"#,##0.00_-');
								$hoja->getStyle("I{$row_print}")->applyFromArray($border_style);
								$this->cellStyle("I{$row_print}", "FF0066", "000000", FALSE, 12, "Franklin Gothic Book");
							}else{
								$hoja->setCellValue("I{$row_print}", $dif3)->getStyle("I{$row_print}")->getNumberFormat()->setFormatCode('"$"#,##0.00_-');
								$hoja->getStyle("I{$row_print}")->applyFromArray($border_style);
								$this->cellStyle("I{$row_print}", "FFE6F0", "000000", FALSE, 12, "Franklin Gothic Book");
							}
						}else{
							$hoja->getStyle("I{$row_print}")->applyFromArray($border_style);
							$this->cellStyle("I{$row_print}", "FFE6F0", "000000", FALSE, 12, "Franklin Gothic Book");
						}

						$hoja->setCellValue("J{$row_print}", $row['xprecio'])->getStyle("J{$row_print}")->getNumberFormat()->setFormatCode('"$"#,##0.00_-');
						$hoja->getStyle("J{$row_print}")->applyFromArray($border_style);

						if($row['precio_sistema'] < $row['xpromo']){
							$hoja->setCellValue("K{$row_print}", $row['xpromo'])->getStyle("K{$row_print}")->getNumberFormat()->setFormatCode('"$"#,##0.00_-');
							$hoja->getStyle("K{$row_print}")->applyFromArray($border_style);
							$this->cellStyle("K{$row_print}", "FDB2B2", "E21111", FALSE, 12, "Franklin Gothic Book");
						}else{
							$hoja->setCellValue("K{$row_print}", $row['xpromo'])->getStyle("K{$row_print}")->getNumberFormat()->setFormatCode('"$"#,##0.00_-');
							$hoja->getStyle("K{$row_print}")->applyFromArray($border_style);
							$this->cellStyle("K{$row_print}", "96EAA8", "0C800C", FALSE, 12, "Franklin Gothic Book");
						}
						$hoja->setCellValue("L{$row_print}", $row['xobs'])->getStyle("L{$row_print}")->getNumberFormat()->setFormatCode('"$"#,##0.00_-');
						$hoja->getStyle("L{$row_print}")->applyFromArray($border_style);
						$hoja->setCellValue("M{$row_print}", $row['precio_first'])->getStyle("M{$row_print}")->getNumberFormat()->setFormatCode('"$"#,##0.00_-');
						$hoja->getStyle("M{$row_print}")->applyFromArray($border_style);
						$hoja->setCellValue("N{$row_print}", $row['proveedor_first'])->getStyle("N{$row_print}");
						$hoja->getStyle("N{$row_print}")->applyFromArray($border_style);
						$hoja->setCellValue("O{$row_print}", $row['promocion_first'])->getStyle("O{$row_print}");
						$hoja->getStyle("O{$row_print}")->applyFromArray($border_style);

						$hoja->setCellValue("P{$row_print}", $row['precio_next'])->getStyle("P{$row_print}")->getNumberFormat()->setFormatCode('"$"#,##0.00_-');
						$hoja->getStyle("P{$row_print}")->applyFromArray($border_style);
						$hoja->setCellValue("Q{$row_print}", $row['proveedor_next'])->getStyle("Q{$row_print}");
						$hoja->getStyle("Q{$row_print}")->applyFromArray($border_style);
						$hoja->setCellValue("R{$row_print}", $row['promocion_next'])->getStyle("R{$row_print}");
						$hoja->getStyle("R{$row_print}")->applyFromArray($border_style);

						if($row['estatus'] == 2){
							$this->cellStyle("B{$row_print}", "00B0F0", "000000", FALSE, 12, "Franklin Gothic Book");
						}
						if($row['estatus'] == 3){
							$this->cellStyle("B{$row_print}", "FFF900", "000000", FALSE, 12, "Franklin Gothic Book");
						}
						if($row['estatus'] >= 4){
							$this->cellStyle("B{$row_print}", "04B486", "000000", FALSE, 12, "Franklin Gothic Book");
						}
						$hoja->getStyle("B{$row_print}")->applyFromArray($border_style);

						$hoja->getStyle("A{$row_print}:AT{$row_print}")
			                 ->getAlignment()
			                 ->setHorizontal(\PHPExcel_Style_Alignment::HORIZONTAL_LEFT);

						$hoja->setCellValue("S{$row_print}", $row['caja0']);
 						$hoja->setCellValue("T{$row_print}", $row['pz0']);
 						$hoja->setCellValue("U{$row_print}", $row['ped0']);
 						$this->cellStyle("U{$row_print}", "D4EAEF", "000000", TRUE, 12, "Franklin Gothic Book");
 						$hoja->setCellValue("V{$row_print}", $row['caja1']);
 						$hoja->setCellValue("W{$row_print}", $row['pz1']);
 						$hoja->setCellValue("X{$row_print}", $row['ped1']);
 						$this->cellStyle("X{$row_print}", "D4EAEF", "000000", TRUE, 12, "Franklin Gothic Book");
 						$hoja->setCellValue("Y{$row_print}", $row['caja2']);
 						$hoja->setCellValue("Z{$row_print}", $row['pz2']);
 						$hoja->setCellValue("AA{$row_print}", $row['ped2']);
 						$this->cellStyle("AA{$row_print}", "D4EAEF", "000000", TRUE, 12, "Franklin Gothic Book");
 						$hoja->setCellValue("AB{$row_print}", $row['caja3']);
 						$hoja->setCellValue("AC{$row_print}", $row['pz3']);
 						$hoja->setCellValue("AD{$row_print}", $row['ped3']);
 						$this->cellStyle("AD{$row_print}", "D4EAEF", "000000", TRUE, 12, "Franklin Gothic Book");
 						$hoja->setCellValue("AE{$row_print}", $row['caja4']);
 						$hoja->setCellValue("AF{$row_print}", $row['pz4']);
 						$hoja->setCellValue("AG{$row_print}", $row['ped4']);
 						$this->cellStyle("AG{$row_print}", "D4EAEF", "000000", TRUE, 12, "Franklin Gothic Book");
 						$hoja->setCellValue("AH{$row_print}", $row['caja5']);
 						$hoja->setCellValue("AI{$row_print}", $row['pz5']);
 						$hoja->setCellValue("AJ{$row_print}", $row['ped5']);
 						$this->cellStyle("AJ{$row_print}", "D4EAEF", "000000", TRUE, 12, "Franklin Gothic Book");
						$hoja->setCellValue("AK{$row_print}", $row['caja6']);
 						$hoja->setCellValue("AL{$row_print}", $row['pz6']);
 						$hoja->setCellValue("AM{$row_print}", $row['ped6']);
 						$this->cellStyle("AM{$row_print}", "D4EAEF", "000000", TRUE, 12, "Franklin Gothic Book");
						$hoja->getStyle("S{$row_print}")->applyFromArray($border_style);
						$hoja->getStyle("T{$row_print}")->applyFromArray($border_style);
						$hoja->getStyle("U{$row_print}")->applyFromArray($border_style);
						$hoja->getStyle("V{$row_print}")->applyFromArray($border_style);
						$hoja->getStyle("W{$row_print}")->applyFromArray($border_style);
						$hoja->getStyle("X{$row_print}")->applyFromArray($border_style);
						$hoja->getStyle("Y{$row_print}")->applyFromArray($border_style);
						$hoja->getStyle("Z{$row_print}")->applyFromArray($border_style);
						$hoja->getStyle("AA{$row_print}")->applyFromArray($border_style);
						$hoja->getStyle("AB{$row_print}")->applyFromArray($border_style);
						$hoja->getStyle("AC{$row_print}")->applyFromArray($border_style);
						$hoja->getStyle("AD{$row_print}")->applyFromArray($border_style);
						$hoja->getStyle("AE{$row_print}")->applyFromArray($border_style);
						$hoja->getStyle("AF{$row_print}")->applyFromArray($border_style);
						$hoja->getStyle("AG{$row_print}")->applyFromArray($border_style);
						$hoja->getStyle("AH{$row_print}")->applyFromArray($border_style);
						$hoja->getStyle("AI{$row_print}")->applyFromArray($border_style);
						$hoja->getStyle("AJ{$row_print}")->applyFromArray($border_style);
						$hoja->getStyle("AK{$row_print}")->applyFromArray($border_style);
						$hoja->getStyle("AL{$row_print}")->applyFromArray($border_style);
						$hoja->getStyle("AM{$row_print}")->applyFromArray($border_style);

						$hoja->setCellValue("AN{$row_print}", "=(K".$row_print."*U".$row_print.")")->getStyle("AN{$row_print}")->getNumberFormat()->setFormatCode('"$"#,##0.00_-');
						$hoja->setCellValue("AO{$row_print}", "=(K".$row_print."*X".$row_print.")")->getStyle("AO{$row_print}")->getNumberFormat()->setFormatCode('"$"#,##0.00_-');
						$hoja->setCellValue("AP{$row_print}", "=(K".$row_print."*AA".$row_print.")")->getStyle("AP{$row_print}")->getNumberFormat()->setFormatCode('"$"#,##0.00_-');
						$hoja->setCellValue("AQ{$row_print}", "=(K".$row_print."*AD".$row_print.")")->getStyle("AQ{$row_print}")->getNumberFormat()->setFormatCode('"$"#,##0.00_-');
						$hoja->setCellValue("AR{$row_print}", "=(K".$row_print."*AG".$row_print.")")->getStyle("AR{$row_print}")->getNumberFormat()->setFormatCode('"$"#,##0.00_-');
						$hoja->setCellValue("AS{$row_print}", "=(K".$row_print."*AJ".$row_print.")")->getStyle("AS{$row_print}")->getNumberFormat()->setFormatCode('"$"#,##0.00_-');
						$hoja->setCellValue("AT{$row_print}", "=(K".$row_print."*AM".$row_print.")")->getStyle("AT{$row_print}")->getNumberFormat()->setFormatCode('"$"#,##0.00_-');

						$hoja->setCellValue("AU{$row_print}", "=SUM(AN{$row_print}:AT{$row_print})")->getStyle("AU{$row_print}")->getNumberFormat()->setFormatCode('"$"#,##0.00_-');
						$this->cellStyle("AU{$row_print}", "000000", "FFFFFF", FALSE, 12, "Franklin Gothic Book");

						$row_print ++;
					}
				}
			}
		}
		$flags = $row_print - 1;
		$hoja->setCellValue("AN{$row_print}", "=SUM(AN3:AN".$flags.")")->getStyle("AN{$row_print}")->getNumberFormat()->setFormatCode('"$"#,##0.00_-');
		$hoja->setCellValue("AO{$row_print}", "=SUM(AO3:AO".$flags.")")->getStyle("AO{$row_print}")->getNumberFormat()->setFormatCode('"$"#,##0.00_-');
		$hoja->setCellValue("AP{$row_print}", "=SUM(AP3:AP".$flags.")")->getStyle("AP{$row_print}")->getNumberFormat()->setFormatCode('"$"#,##0.00_-');
		$hoja->setCellValue("AQ{$row_print}", "=SUM(AQ3:AQ".$flags.")")->getStyle("AQ{$row_print}")->getNumberFormat()->setFormatCode('"$"#,##0.00_-');
		$hoja->setCellValue("AR{$row_print}", "=SUM(AR3:AR".$flags.")")->getStyle("AR{$row_print}")->getNumberFormat()->setFormatCode('"$"#,##0.00_-');
		$hoja->setCellValue("AS{$row_print}", "=SUM(AS3:AS".$flags.")")->getStyle("AS{$row_print}")->getNumberFormat()->setFormatCode('"$"#,##0.00_-');
		$hoja->setCellValue("AT{$row_print}", "=SUM(AT3:AT".$flags.")")->getStyle("AT{$row_print}")->getNumberFormat()->setFormatCode('"$"#,##0.00_-');

        $dias = array("DOMINGO","LUNES","MARTES","MIÉRCOLES","JUEVES","VIERNES","SÁBADO");
		$meses = array("ENERO","FEBRERO","MARZO","ABRIL","MAYO","JUNIO","JULIO","AGOSTO","SEPTIEMBRE","OCTUBRE","NOVIEMBRE","DICIEMBRE");

		$fecha =  $dias[date('w')]." ".date('d')." DE ".$meses[date('n')-1]. " DEL ".date('Y') ;
		$file_name = "EXPO COMPARACIÓN ".$fecha.".xlsx"; //Nombre del documento con extención
		header("Content-Type: application/vnd.ms-excel; charset=utf-8");
		header("Content-Disposition: attachment;filename=".$file_name);
		header("Cache-Control: max-age=0");
		$excel_Writer = PHPExcel_IOFactory::createWriter($this->excelfile, "Excel2007");
		$excel_Writer->save("php://output");
	}

	public function fill_existe(){
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

		$this->cellStyle("A1:W2", "000000", "FFFFFF", TRUE, 12, "Franklin Gothic Book");
		$border_style= array('borders' => array('right' => array('style' =>
			PHPExcel_Style_Border::BORDER_THIN,'color' => array('argb' => '000000'),)));
		$hoja->setCellValue("A2", "CÓDIGO")->getColumnDimension('A')->setWidth(30); //Nombre y ajuste de texto a la columna
		$hoja->setCellValue("B1", "DESCRIPCIÓN")->getColumnDimension('B')->setWidth(50);
		$hoja->mergeCells('C1:E1');
		$this->cellStyle("C1", "01B0F0", "000000", TRUE, 12, "Franklin Gothic Book");
		$hoja->setCellValue("C1", "ABARROTES");
		$hoja->mergeCells('F1:H1');
		$this->cellStyle("F1", "E26C0B", "000000", TRUE, 12, "Franklin Gothic Book");
		$hoja->setCellValue("F1", "TIENDA");
		$hoja->mergeCells('I1:K1');
		$this->cellStyle("I1", "C5C5C5", "000000", TRUE, 12, "Franklin Gothic Book");
		$hoja->setCellValue("I1", "ULTRAMARINOS");
		$hoja->mergeCells('L1:N1');
		$this->cellStyle("L1", "92D051", "000000", TRUE, 12, "Franklin Gothic Book");
		$hoja->setCellValue("L1", "TRINCHERAS");
		$hoja->mergeCells('O1:Q1');
		$this->cellStyle("O1", "B1A0C7", "000000", TRUE, 12, "Franklin Gothic Book");
		$hoja->setCellValue("O1", "AZT MERCADO");
		$hoja->mergeCells('R1:T1');
		$this->cellStyle("R1", "DA9694", "000000", TRUE, 12, "Franklin Gothic Book");
		$hoja->setCellValue("R1", "TENENCIA");
		$hoja->mergeCells('U1:W1');
		$this->cellStyle("U1", "4CACC6", "000000", TRUE, 12, "Franklin Gothic Book");
		$hoja->setCellValue("U1", "TIJERAS");
		$hoja->setCellValue("C2", "CAJAS");
		$hoja->setCellValue("D2", "PZAS");
		$hoja->setCellValue("E2", "PEDIDO");
		$hoja->setCellValue("F2", "CAJAS");
		$hoja->setCellValue("G2", "PZAS");
		$hoja->setCellValue("H2", "PEDIDO");
		$hoja->setCellValue("I2", "CAJAS");
		$hoja->setCellValue("J2", "PZAS");
		$hoja->setCellValue("K2", "PEDIDO");
		$hoja->setCellValue("L2", "CAJAS");
		$hoja->setCellValue("M2", "PZAS");
		$hoja->setCellValue("N2", "PEDIDO");
		$hoja->setCellValue("O2", "CAJAS");
		$hoja->setCellValue("P2", "PZAS");
		$hoja->setCellValue("Q2", "PEDIDO");
		$hoja->setCellValue("R2", "CAJAS");
		$hoja->setCellValue("S2", "PZAS");
		$hoja->setCellValue("T2", "PEDIDO");
		$hoja->setCellValue("U2", "CAJAS");
		$hoja->setCellValue("V2", "PZAS");
		$hoja->setCellValue("W2", "PEDIDO");

		$cotizacionesProveedor = $this->ct_mdl->fill_ex(NULL, date('Y-m-d'));

		$row_print =2;
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
						if($row['estatus'] == 2){
							$this->cellStyle("B{$row_print}", "00B0F0", "000000", FALSE, 12, "Franklin Gothic Book");
						}
						if($row['estatus'] == 3){
							$this->cellStyle("B{$row_print}", "FFF900", "000000", FALSE, 12, "Franklin Gothic Book");
						}
						if($row['estatus'] >= 4){
							$this->cellStyle("B{$row_print}", "04B486", "000000", FALSE, 12, "Franklin Gothic Book");
						}
						$hoja->getStyle("B{$row_print}")->applyFromArray($border_style);

						$hoja->getStyle("A{$row_print}:AT{$row_print}")
			                 ->getAlignment()
			                 ->setHorizontal(\PHPExcel_Style_Alignment::HORIZONTAL_LEFT);
			            $this->cellStyle("C".$row_print.":W".$row_print, "FFFFFF", "000000", TRUE, 12, "Franklin Gothic Book");
						$hoja->setCellValue("C{$row_print}", $row['caja0']);
 						$hoja->setCellValue("D{$row_print}", $row['pz0']);
 						$hoja->setCellValue("E{$row_print}", $row['ped0']);
 						$this->cellStyle("E{$row_print}", "D4EAEF", "000000", TRUE, 12, "Franklin Gothic Book");
 						$hoja->setCellValue("F{$row_print}", $row['caja1']);
 						$hoja->setCellValue("G{$row_print}", $row['pz1']);
 						$hoja->setCellValue("H{$row_print}", $row['ped1']);
 						$this->cellStyle("H{$row_print}", "D4EAEF", "000000", TRUE, 12, "Franklin Gothic Book");
 						$hoja->setCellValue("I{$row_print}", $row['caja2']);
 						$hoja->setCellValue("J{$row_print}", $row['pz2']);
 						$hoja->setCellValue("K{$row_print}", $row['ped2']);
 						$this->cellStyle("K{$row_print}", "D4EAEF", "000000", TRUE, 12, "Franklin Gothic Book");
 						$hoja->setCellValue("L{$row_print}", $row['caja3']);
 						$hoja->setCellValue("M{$row_print}", $row['pz3']);
 						$hoja->setCellValue("N{$row_print}", $row['ped3']);
 						$this->cellStyle("N{$row_print}", "D4EAEF", "000000", TRUE, 12, "Franklin Gothic Book");
 						$hoja->setCellValue("O{$row_print}", $row['caja4']);
 						$hoja->setCellValue("P{$row_print}", $row['pz4']);
 						$hoja->setCellValue("Q{$row_print}", $row['ped4']);
 						$this->cellStyle("Q{$row_print}", "D4EAEF", "000000", TRUE, 12, "Franklin Gothic Book");
 						$hoja->setCellValue("R{$row_print}", $row['caja5']);
 						$hoja->setCellValue("S{$row_print}", $row['pz5']);
 						$hoja->setCellValue("T{$row_print}", $row['ped5']);
 						$this->cellStyle("T{$row_print}", "D4EAEF", "000000", TRUE, 12, "Franklin Gothic Book");
						$hoja->setCellValue("U{$row_print}", $row['caja6']);
 						$hoja->setCellValue("V{$row_print}", $row['pz6']);
 						$hoja->setCellValue("W{$row_print}", $row['ped6']);
 						$this->cellStyle("W{$row_print}", "D4EAEF", "000000", TRUE, 12, "Franklin Gothic Book");
						$hoja->getStyle("C{$row_print}")->applyFromArray($border_style);
						$hoja->getStyle("D{$row_print}")->applyFromArray($border_style);
						$hoja->getStyle("E{$row_print}")->applyFromArray($border_style);
						$hoja->getStyle("F{$row_print}")->applyFromArray($border_style);
						$hoja->getStyle("G{$row_print}")->applyFromArray($border_style);
						$hoja->getStyle("H{$row_print}")->applyFromArray($border_style);
						$hoja->getStyle("I{$row_print}")->applyFromArray($border_style);
						$hoja->getStyle("J{$row_print}")->applyFromArray($border_style);
						$hoja->getStyle("K{$row_print}")->applyFromArray($border_style);
						$hoja->getStyle("L{$row_print}")->applyFromArray($border_style);
						$hoja->getStyle("M{$row_print}")->applyFromArray($border_style);
						$hoja->getStyle("N{$row_print}")->applyFromArray($border_style);
						$hoja->getStyle("O{$row_print}")->applyFromArray($border_style);
						$hoja->getStyle("P{$row_print}")->applyFromArray($border_style);
						$hoja->getStyle("Q{$row_print}")->applyFromArray($border_style);
						$hoja->getStyle("R{$row_print}")->applyFromArray($border_style);
						$hoja->getStyle("S{$row_print}")->applyFromArray($border_style);
						$hoja->getStyle("T{$row_print}")->applyFromArray($border_style);
						$hoja->getStyle("U{$row_print}")->applyFromArray($border_style);
						$hoja->getStyle("V{$row_print}")->applyFromArray($border_style);
						$hoja->getStyle("W{$row_print}")->applyFromArray($border_style);

						$row_print ++;
					}
				}
			}
		}
		$file_name = "EXISTENCIAS TODOS LOS PRODUCTOS.xlsx"; //Nombre del documento con extención
		header("Content-Type: application/vnd.ms-excel; charset=utf-8");
		header("Content-Disposition: attachment;filename=".$file_name);
		header("Cache-Control: max-age=0");
		$excel_Writer = PHPExcel_IOFactory::createWriter($this->excelfile, "Excel2007");
		$excel_Writer->save("php://output");
	}

	public function fill_existeNot(){
		ini_set("memory_limit", "-1");
		ini_set("max_execution_time", "-1");
		$this->load->library("excelfile");
		
		$hoja1 = $this->excelfile->setActiveSheetIndex(0);
		$this->excelfile->setActiveSheetIndex(0)->setTitle("EXISTENCIAS");

		$this->excelfile->createSheet();
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
		$styleArray = array(
		  'borders' => array(
		    'allborders' => array(
		      'style' => PHPExcel_Style_Border::BORDER_THIN
		    )
		  )
		);
		$border_style= array('borders' => array('right' => array('style' =>
			PHPExcel_Style_Border::BORDER_THIN,'color' => array('argb' => '000000'),)));
		$hoja1->setCellValue("D2", "CÓDIGO")->getColumnDimension('D')->setWidth(30); //Nombre y ajuste de texto a la columna
		$hoja1->setCellValue("E1", "DESCRIPCIÓN")->getColumnDimension('E')->setWidth(50);
        $hoja = $this->excelfile->setActiveSheetIndex(1);
        $hoja->setTitle("PEDIDO");
        $hoja1 = $this->excelfile->setActiveSheetIndex(0);
		$flag1 = 1;
		$hoja1->mergeCells('A'.$flag1.':E'.$flag1);
		$this->cellStyle("A".$flag1, "FFFFFF", "000000", TRUE, 12, "Franklin Gothic Book");
		$hoja1->setCellValue("A".$flag1."", "GRUPO ABARROTES AZTECA");
		$hoja1->getStyle('A'.$flag1)->applyFromArray($border_style);
		$flag1++;
		$hoja1->mergeCells('A'.$flag1.':E'.$flag1);
		$this->cellStyle("A".$flag1, "FFFFFF", "000000", TRUE, 12, "Franklin Gothic Book");
		$hoja1->setCellValue("A".$flag1."", "PRODUCTOS SIN COTIZAR ");
		$hoja1->getStyle('A'.$flag1)->applyFromArray($border_style);
		$flag1++;
		$this->cellStyle("A".$flag1.":D".$flag1, "000000", "FFFFFF", TRUE, 12, "Franklin Gothic Book");
		$hoja1->mergeCells('A'.$flag1.':B'.$flag1);
		$hoja1->setCellValue("A".$flag1, "EXISTENCIAS");
		$hoja1->setCellValue("E".$flag1, "DESCRIPCIÓN");
		$this->cellStyle("E".$flag1, "000000", "FFFFFF", TRUE, 12, "Franklin Gothic Book");
		$hoja1->getStyle('A'.$flag1)->applyFromArray($border_style);
		$flag1++;
		$this->cellStyle("A".$flag1.":E".$flag1, "000000", "FFFFFF", TRUE, 12, "Franklin Gothic Book");
		$hoja1->setCellValue("A".$flag1, "CAJAS");
		$hoja1->setCellValue("B".$flag1, "PZAS");
		$hoja1->setCellValue("C".$flag1, "PEDIDO");
		$hoja1->setCellValue("D".$flag1, "COD");
		//$hoja1->setCellValue("E".$flag1, "DESCRIPCIÓN");
		$hoja1->getStyle('A'.$flag1)->applyFromArray($border_style);
		//$flag1++;
		$this->excelfile->setActiveSheetIndex(1);

		$this->cellStyle("A1:W2", "000000", "FFFFFF", TRUE, 12, "Franklin Gothic Book");
		$border_style= array('borders' => array('right' => array('style' =>
			PHPExcel_Style_Border::BORDER_THIN,'color' => array('argb' => '000000'),)));
		$hoja->setCellValue("A2", "CÓDIGO")->getColumnDimension('A')->setWidth(30); //Nombre y ajuste de texto a la columna
		$hoja->setCellValue("B1", "DESCRIPCIÓN")->getColumnDimension('B')->setWidth(50);
		$hoja->mergeCells('C1:E1');
		$this->cellStyle("C1", "01B0F0", "000000", TRUE, 12, "Franklin Gothic Book");
		$hoja->setCellValue("C1", "ABARROTES");
		$hoja->mergeCells('F1:H1');
		$this->cellStyle("F1", "E26C0B", "000000", TRUE, 12, "Franklin Gothic Book");
		$hoja->setCellValue("F1", "TIENDA");
		$hoja->mergeCells('I1:K1');
		$this->cellStyle("I1", "C5C5C5", "000000", TRUE, 12, "Franklin Gothic Book");
		$hoja->setCellValue("I1", "ULTRAMARINOS");
		$hoja->mergeCells('L1:N1');
		$this->cellStyle("L1", "92D051", "000000", TRUE, 12, "Franklin Gothic Book");
		$hoja->setCellValue("L1", "TRINCHERAS");
		$hoja->mergeCells('O1:Q1');
		$this->cellStyle("O1", "B1A0C7", "000000", TRUE, 12, "Franklin Gothic Book");
		$hoja->setCellValue("O1", "AZT MERCADO");
		$hoja->mergeCells('R1:T1');
		$this->cellStyle("R1", "DA9694", "000000", TRUE, 12, "Franklin Gothic Book");
		$hoja->setCellValue("R1", "TENENCIA");
		$hoja->mergeCells('U1:W1');
		$this->cellStyle("U1", "4CACC6", "000000", TRUE, 12, "Franklin Gothic Book");
		$hoja->setCellValue("U1", "TIJERAS");
		$hoja->setCellValue("C2", "CAJAS");
		$hoja->setCellValue("D2", "PZAS");
		$hoja->setCellValue("E2", "PEDIDO");
		$hoja->setCellValue("F2", "CAJAS");
		$hoja->setCellValue("G2", "PZAS");
		$hoja->setCellValue("H2", "PEDIDO");
		$hoja->setCellValue("I2", "CAJAS");
		$hoja->setCellValue("J2", "PZAS");
		$hoja->setCellValue("K2", "PEDIDO");
		$hoja->setCellValue("L2", "CAJAS");
		$hoja->setCellValue("M2", "PZAS");
		$hoja->setCellValue("N2", "PEDIDO");
		$hoja->setCellValue("O2", "CAJAS");
		$hoja->setCellValue("P2", "PZAS");
		$hoja->setCellValue("Q2", "PEDIDO");
		$hoja->setCellValue("R2", "CAJAS");
		$hoja->setCellValue("S2", "PZAS");
		$hoja->setCellValue("T2", "PEDIDO");
		$hoja->setCellValue("U2", "CAJAS");
		$hoja->setCellValue("V2", "PZAS");
		$hoja->setCellValue("W2", "PEDIDO");

		$cotizacionesProveedor = $this->ct_mdl->fill_exNot(NULL, date('Y-m-d'));

		$row_print =2;
		if ($cotizacionesProveedor){
			foreach ($cotizacionesProveedor as $key => $value){
				$this->excelfile->setActiveSheetIndex(0);
				$this->cellStyle("E".$flag1, "000000", "FFFFFF", FALSE, 12, "Franklin Gothic Book");
				$hoja1->setCellValue("E".$flag1, $value['familia']);

				//Pedidos
				$this->excelfile->setActiveSheetIndex(1);
				$hoja->setCellValue("B{$row_print}", $value['familia']);
				$this->cellStyle("B{$row_print}", "000000", "FFFFFF", TRUE, 12, "Franklin Gothic Book");
				$row_print +=1;
				$flag1 += 1;
				if ($value['articulos']) {
					foreach ($value['articulos'] as $key => $row){
						$this->excelfile->setActiveSheetIndex(0);
						$this->cellStyle("A".$flag1.":E".$flag1, "FFFFFF", "000000", FALSE, 12, "Franklin Gothic Book");
						$hoja1->setCellValue("D{$flag1}", $row['codigo'])->getStyle("D{$flag1}")->getNumberFormat()->setFormatCode('# ???/???');//Formato de fraccion
						if($row['color'] == '#92CEE3'){
							$this->cellStyle("D{$flag1}", "92CEE3", "000000", FALSE, 12, "Franklin Gothic Book");
						}else{
							$this->cellStyle("D{$flag1}", "FFFFFF", "000000", FALSE, 12, "Franklin Gothic Book");
						}
						$hoja1->setCellValue("E{$flag1}", $row['producto']);
						$hoja1->getStyle("A{$flag1}:E{$flag1}")
				                 ->getAlignment()
				                 ->setHorizontal(\PHPExcel_Style_Alignment::HORIZONTAL_LEFT);
				        $hoja1->getStyle("A{$flag1}")->applyFromArray($border_style);
				        $hoja1->getStyle("B{$flag1}")->applyFromArray($border_style);
				        $hoja1->getStyle("C{$flag1}")->applyFromArray($border_style);
				        $hoja1->getStyle("D{$flag1}")->applyFromArray($border_style);
				        $hoja1->getStyle("E{$flag1}")->applyFromArray($border_style);
				        $flag1 += 1;
				        $this->excelfile->setActiveSheetIndex(1);
						$this->cellStyle("B{$row_print}:L{$row_print}", "FFFFFF", "000000", FALSE, 12, "Franklin Gothic Book");
						if($row['color'] == '#92CEE3'){
							$this->cellStyle("A{$row_print}", "92CEE3", "000000", TRUE, 12, "Franklin Gothic Book");
						}else{
							$this->cellStyle("A{$row_print}", "FFFFFF", "000000", TRUE, 12, "Franklin Gothic Book");
						}
						$hoja->setCellValue("A{$row_print}", $row['codigo'])->getStyle("A{$row_print}")->getNumberFormat()->setFormatCode('# ???/???');//Formato de fraccion
						$hoja->getStyle("A{$row_print}")->applyFromArray($border_style);
						$hoja->setCellValue("B{$row_print}", $row['producto']);
						if($row['estatus'] == 2){
							$this->cellStyle("B{$row_print}", "00B0F0", "000000", FALSE, 12, "Franklin Gothic Book");
						}
						if($row['estatus'] == 3){
							$this->cellStyle("B{$row_print}", "FFF900", "000000", FALSE, 12, "Franklin Gothic Book");
						}
						if($row['estatus'] >= 4){
							$this->cellStyle("B{$row_print}", "04B486", "000000", FALSE, 12, "Franklin Gothic Book");
						}

						$hoja->getStyle("B{$row_print}")->applyFromArray($border_style);

						$hoja->getStyle("A{$row_print}:AT{$row_print}")
			                 ->getAlignment()
			                 ->setHorizontal(\PHPExcel_Style_Alignment::HORIZONTAL_LEFT);
			            $this->cellStyle("C".$row_print.":W".$row_print, "FFFFFF", "000000", TRUE, 12, "Franklin Gothic Book");
						$hoja->setCellValue("C{$row_print}", $row['caja0']);
 						$hoja->setCellValue("D{$row_print}", $row['pz0']);
 						$hoja->setCellValue("E{$row_print}", $row['ped0']);
 						$this->cellStyle("E{$row_print}", "D4EAEF", "000000", TRUE, 12, "Franklin Gothic Book");
 						$hoja->setCellValue("F{$row_print}", $row['caja1']);
 						$hoja->setCellValue("G{$row_print}", $row['pz1']);
 						$hoja->setCellValue("H{$row_print}", $row['ped1']);
 						$this->cellStyle("H{$row_print}", "D4EAEF", "000000", TRUE, 12, "Franklin Gothic Book");
 						$hoja->setCellValue("I{$row_print}", $row['caja2']);
 						$hoja->setCellValue("J{$row_print}", $row['pz2']);
 						$hoja->setCellValue("K{$row_print}", $row['ped2']);
 						$this->cellStyle("K{$row_print}", "D4EAEF", "000000", TRUE, 12, "Franklin Gothic Book");
 						$hoja->setCellValue("L{$row_print}", $row['caja3']);
 						$hoja->setCellValue("M{$row_print}", $row['pz3']);
 						$hoja->setCellValue("N{$row_print}", $row['ped3']);
 						$this->cellStyle("N{$row_print}", "D4EAEF", "000000", TRUE, 12, "Franklin Gothic Book");
 						$hoja->setCellValue("O{$row_print}", $row['caja4']);
 						$hoja->setCellValue("P{$row_print}", $row['pz4']);
 						$hoja->setCellValue("Q{$row_print}", $row['ped4']);
 						$this->cellStyle("Q{$row_print}", "D4EAEF", "000000", TRUE, 12, "Franklin Gothic Book");
 						$hoja->setCellValue("R{$row_print}", $row['caja5']);
 						$hoja->setCellValue("S{$row_print}", $row['pz5']);
 						$hoja->setCellValue("T{$row_print}", $row['ped5']);
 						$this->cellStyle("T{$row_print}", "D4EAEF", "000000", TRUE, 12, "Franklin Gothic Book");
						$hoja->setCellValue("U{$row_print}", $row['caja6']);
 						$hoja->setCellValue("V{$row_print}", $row['pz6']);
 						$hoja->setCellValue("W{$row_print}", $row['ped6']);
 						$this->cellStyle("W{$row_print}", "D4EAEF", "000000", TRUE, 12, "Franklin Gothic Book");
						$hoja->getStyle("C{$row_print}")->applyFromArray($border_style);
						$hoja->getStyle("D{$row_print}")->applyFromArray($border_style);
						$hoja->getStyle("E{$row_print}")->applyFromArray($border_style);
						$hoja->getStyle("F{$row_print}")->applyFromArray($border_style);
						$hoja->getStyle("G{$row_print}")->applyFromArray($border_style);
						$hoja->getStyle("H{$row_print}")->applyFromArray($border_style);
						$hoja->getStyle("I{$row_print}")->applyFromArray($border_style);
						$hoja->getStyle("J{$row_print}")->applyFromArray($border_style);
						$hoja->getStyle("K{$row_print}")->applyFromArray($border_style);
						$hoja->getStyle("L{$row_print}")->applyFromArray($border_style);
						$hoja->getStyle("M{$row_print}")->applyFromArray($border_style);
						$hoja->getStyle("N{$row_print}")->applyFromArray($border_style);
						$hoja->getStyle("O{$row_print}")->applyFromArray($border_style);
						$hoja->getStyle("P{$row_print}")->applyFromArray($border_style);
						$hoja->getStyle("Q{$row_print}")->applyFromArray($border_style);
						$hoja->getStyle("R{$row_print}")->applyFromArray($border_style);
						$hoja->getStyle("S{$row_print}")->applyFromArray($border_style);
						$hoja->getStyle("T{$row_print}")->applyFromArray($border_style);
						$hoja->getStyle("U{$row_print}")->applyFromArray($border_style);
						$hoja->getStyle("V{$row_print}")->applyFromArray($border_style);
						$hoja->getStyle("W{$row_print}")->applyFromArray($border_style);

						$row_print ++;
					}
				}
			}
		}
		$file_name = "EXISTENCIAS PRODUCTOS NO COTIZADOS.xlsx"; //Nombre del documento con extención
		header("Content-Type: application/vnd.ms-excel; charset=utf-8");
		header("Content-Disposition: attachment;filename=".$file_name);
		header("Cache-Control: max-age=0");
		$excel_Writer = PHPExcel_IOFactory::createWriter($this->excelfile, "Excel2007");
		$excel_Writer->save("php://output");
	}

	public function upload_fullpedidos($idesp){
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

		if($idesp === "0"){
			$tienda = $this->session->userdata('id_usuario');
		}else{
			$tienda = $idesp;
		}
		$cfile =  $this->usua_mdl->get(NULL, ['id_usuario' => $tienda])[0];
		$nams = preg_replace('/\s+/', '_', $cfile->nombre);
		$filen = "Pedidos".$nams."".rand();

		$config['upload_path']          = './assets/uploads/pedidos/';
        $config['allowed_types']        = 'xlsx|xls';
        $config['max_size']             = 100;
        $config['max_width']            = 1024;
        $config['max_height']           = 768;
        $config['max_height']           = 768;


        $this->load->library('upload', $config);
        $this->upload->initialize($config);
        $this->upload->do_upload('file_cotizaciones',$filen);
		for ($i=3; $i<=$num_rows; $i++) {
			$productos = $this->prod_mdl->get("id_producto",['codigo'=> htmlspecialchars($sheet->getCell('F'.$i)->getValue(), ENT_QUOTES, 'UTF-8')])[0];
			if (sizeof($productos) > 0) {
				$exis = $this->ex_mdl->get(NULL,["WEEKOFYEAR(fecha_registro)" => $this->weekNumber($fecha->format('Y-m-d H:i:s')),"id_tienda"=>$TIENDAnda,"id_producto"=>$productos->id_producto])[0];
				$column_one=0; $column_two=0; $column_three=0;
				$column_one = $sheet->getCell('C'.$i)->getValue() == "" ? 0 : $sheet->getCell('C'.$i)->getValue();
				$column_two = $sheet->getCell('D'.$i)->getValue() == "" ? 0 : $sheet->getCell('D'.$i)->getValue();
				$column_three = $sheet->getCell('C'.$i)->getValue() == "" ? 0 : $sheet->getCell('C'.$i)->getValue();

				$new_existencias[$i]=[
					"id_producto"			=>	$productos->id_producto,
					"id_tienda"			=>	$tienda,
					"cajas"			=>	$column_one,
					"piezas"			=>	$column_two,
					"pedido"	=>	$column_three,
					"fecha_registro"	=>	$fecha->format('Y-m-d H:i:s')
				];
				if($exis){
					$data['cotizacion']=$this->ex_mdl->update($new_existencias[$i], ['id_pedido' => $exis->id_pedido]);
				}else{
					$data['cotizacion']=$this->ex_mdl->insert($new_existencias[$i]);
				}
			}
		}
		if (sizeof($new_existencias) > 0) {
			$aprov = $this->usua_mdl->get(NULL, ['id_usuario'=>$tienda])[0];
			$cambios=[
					"id_usuario"		=>	$this->session->userdata('id_usuario'),
					"fecha_cambio"		=>	date("Y-m-d H:i:s"),
					"antes"			=>	"El usuario sube archivo de pedidos de la tienda ".$aprov->nombre,
					"despues"			=>	"assets/uploads/pedidos/".$filen.".xlsx",
					"accion"			=>	"Sube Pedidos"
				];
			$data['cambios']=$this->cambio_md->insert($cambios);
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

	public function fill_directos($directos){
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

		$this->cellStyle("A1:X2", "000000", "FFFFFF", TRUE, 12, "Franklin Gothic Book");
		$border_style= array('borders' => array('right' => array('style' =>
			PHPExcel_Style_Border::BORDER_THIN,'color' => array('argb' => '000000'),)));
		$hoja->setCellValue("A2", "CÓDIGO")->getColumnDimension('A')->setWidth(30); //Nombre y ajuste de texto a la columna
		$hoja->setCellValue("B1", "DESCRIPCIÓN")->getColumnDimension('B')->setWidth(50);
		$hoja->setCellValue("C2", "SISTEMA")->getColumnDimension('C')->setWidth(12);
		$hoja->setCellValue("D2", "PRECIO 4")->getColumnDimension('D')->setWidth(12);
		$hoja->setCellValue("E1", "PRECIO")->getColumnDimension('E')->setWidth(12);
		$hoja->setCellValue("E2", "PROMEDIO")->getColumnDimension('E')->setWidth(12);
		$hoja->setCellValue("F1", "PRECIO")->getColumnDimension('F')->setWidth(12);
		$hoja->setCellValue("F2", "MAXIMO")->getColumnDimension('F')->setWidth(12);
		$col = 6;
		$rws = 3;
		$fecha = new DateTime(date('Y-m-d H:i:s'));
		$intervalo = new DateInterval('P2D');
		$fecha->add($intervalo);
		$fecha = $fecha->format('Y-m-d H:i:s');
		$cotPro = $this->ct_mdl->getCotzP(NULL, $fecha,$directos);
		if ($cotPro){
			foreach ($cotPro as $key => $value){
				$hoja->setCellValueByColumnAndRow($col, 2, $value->nombre);
                $col++;
                $col++;
			}
		}
		$col=6;
		$producto = "";
		$costo = 0;
		$maximo = 0;
		$flag = 0;
		$prueba = "";
		$cotizacionesProveedor = $this->ct_mdl->getCotzD(NULL, $fecha,$directos);
		if ($cotizacionesProveedor) {
			foreach ($cotizacionesProveedor as $key => $value) {
				foreach ($value["articulos"] as $key => $val) {
					$hoja->getStyle("A{$rws}")->applyFromArray($border_style);
					$hoja->getStyle("B{$rws}")->applyFromArray($border_style);
					$hoja->getStyle("C{$rws}")->applyFromArray($border_style);
					$hoja->getStyle("D{$rws}")->applyFromArray($border_style);
					$hoja->getStyle("E{$rws}")->applyFromArray($border_style);
					$hoja->getStyle("F{$rws}")->applyFromArray($border_style);
					$hoja->getStyle("G{$rws}")->applyFromArray($border_style);
					$hoja->getStyle("H{$rws}")->applyFromArray($border_style);
					$hoja->getStyle("I{$rws}")->applyFromArray($border_style);
					$hoja->getStyle("J{$rws}")->applyFromArray($border_style);
					$hoja->getStyle("K{$rws}")->applyFromArray($border_style);
					$hoja->getStyle("L{$rws}")->applyFromArray($border_style);
					$hoja->getStyle("M{$rws}")->applyFromArray($border_style);
					$hoja->getStyle("N{$rws}")->applyFromArray($border_style);
					$hoja->getStyle("O{$rws}")->applyFromArray($border_style);
					$hoja->getStyle("P{$rws}")->applyFromArray($border_style);
					$hoja->getStyle("Q{$rws}")->applyFromArray($border_style);
					$hoja->getStyle("R{$rws}")->applyFromArray($border_style);
					$hoja->getStyle("S{$rws}")->applyFromArray($border_style);
					$hoja->getStyle("T{$rws}")->applyFromArray($border_style);
					$hoja->getStyle("U{$rws}")->applyFromArray($border_style);
					$hoja->getStyle("W{$rws}")->applyFromArray($border_style);
					$hoja->getStyle("V{$rws}")->applyFromArray($border_style);
					$hoja->getStyle("X{$rws}")->applyFromArray($border_style);
					if ($producto <> $value["producto"]) {
						$hoja->setCellValue("B{$rws}", $value["producto"]);
						$producto = $value["producto"];
						$hoja->setCellValue("A{$rws}", $val["codigo"])->getStyle("A{$rws}")->getNumberFormat()->setFormatCode('# ???/???');
						$hoja->setCellValue("C{$rws}", $val["precio_sistema"])->getStyle("C{$rws}")->getNumberFormat()->setFormatCode('"$"#,##0.00_-');
						$hoja->setCellValue("D{$rws}", $val["precio_four"])->getStyle("D{$rws}")->getNumberFormat()->setFormatCode('"$"#,##0.00_-');
						$prueba = $val["familia"];
					}
					$maximo = $maximo < $val["precio_promocion"] ? $val["precio_promocion"] : $maximo;
					$costo =  $val["precio_promocion"] + $costo;
					for ($i=0; $i < count($cotPro); $i++) {
						if ($cotPro[$i]->nombre == $val["proveedor"]) {
							$hoja->setCellValueByColumnAndRow($col, $rws, $val["precio_promocion"])->getStyleByColumnAndRow($col, $rws)->getNumberFormat()->setFormatCode('"$"#,##0.00_-');
							$col++;
							$hoja->setCellValueByColumnAndRow($col, $rws, $val["observaciones"]);
							$col++;
						}else{
							$col++;
							$col++;
						}
					}
					$col = 6;
				}
				$costo = $costo / count($value["articulos"]);
				$hoja->setCellValue("E{$rws}", $costo)->getStyle("E{$rws}")->getNumberFormat()->setFormatCode('"$"#,##0.00_-');
				$hoja->setCellValue("F{$rws}", $maximo)->getStyle("F{$rws}")->getNumberFormat()->setFormatCode('"$"#,##0.00_-');
				
				$rws++;	
			}
		}
		//$this->jsonResponse($prueba);


        $dias = array("DOMINGO","LUNES","MARTES","MIÉRCOLES","JUEVES","VIERNES","SÁBADO");
		$meses = array("ENERO","FEBRERO","MARZO","ABRIL","MAYO","JUNIO","JULIO","AGOSTO","SEPTIEMBRE","OCTUBRE","NOVIEMBRE","DICIEMBRE");

		$fecha =  $dias[date('w')]." ".date('d')." DE ".$meses[date('n')-1]. " DEL ".date('Y') ;
		$file_name = "DIRECTOS ".$prueba." ".$fecha.".xlsx"; //Nombre del documento con extención
		header("Content-Type: application/vnd.ms-excel; charset=utf-8");
		header("Content-Disposition: attachment;filename=".$file_name);
		header("Cache-Control: max-age=0");
		$excel_Writer = PHPExcel_IOFactory::createWriter($this->excelfile, "Excel2007");
		$excel_Writer->save("php://output");
	}

}

/* End of file Cotizaciones.php */
/* Location: ./application/controllers/Cotizaciones.php */
