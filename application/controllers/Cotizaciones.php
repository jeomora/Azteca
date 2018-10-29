<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Cotizaciones extends MY_Controller {
	function __construct(){
		parent::__construct();
		$this->load->model("Cotizaciones_model", "ct_mdl");
		$this->load->model("Cotizacionesback_model","ctb_mdl");
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
	public function pendientes(){
		ini_set("memory_limit", "-1");
		$data['links'] = [
			'/assets/css/plugins/dataTables/dataTables.bootstrap',
			'/assets/css/plugins/dataTables/dataTables.responsive',
			'/assets/css/plugins/dataTables/dataTables.tableTools.min',
			'/assets/css/plugins/dataTables/buttons.dataTables.min',
		];
		$data['scripts'] = [
			'/scripts/pendientes',
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
		$this->estructura("Cotizaciones/pendientes", $data);
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
	public function end_cotizacion($ides){
		$data["title"]="ELIMINAR COTIZACION DE LA SEMANA";
		$data["proveedor"] = $this->usua_mdl->get(NULL,["id_usuario" => $ides])[0];
		$data["view"]=$this->load->view("Cotizaciones/end_cotizacion", $data, TRUE);
		$data["button"]="<button class='btn btn-danger end_cotizacion' type='button'>
							<span class='bold'><i class='fa fa-trash'></i></span> &nbsp;ELIMINAR
						</button>";
		$this->jsonResponse($data);
	}
	public function endCotizacion($ides){
		$fecha = new DateTime(date('Y-m-d H:i:s'));
		$intervalo = new DateInterval('P2D');
		$fecha->add($intervalo);
		if($this->db->delete('cotizaciones', array('id_proveedor' => $ides, "WEEKOFYEAR(fecha_registro)" => $this->weekNumber($fecha->format('Y-m-d H:i:s'))))){
			$mensaje = [
				"id" 	=> 'Éxito',
				"desc"	=> 'Cotizaciones eliminadas correctamente',
				"type"	=> 'success'
			];
			$data["proveedor"] = $this->usua_mdl->get(NULL,["id_usuario" => $ides])[0];
			$cambios = [
				"id_usuario" => $this->session->userdata('id_usuario'),
				"fecha_cambio" => date('Y-m-d H:i:s'),
				"accion" => "Elimina cotizaciones de ".$data["proveedor"]->nombre,
				"antes" => "Eliminado",
				"despues" => "Eliminado"
			];
			$data['cambios'] = $this->cambio_md->insert($cambios);
		}else{
			$mensaje=[	"id"	=>	'Error',
			"desc"	=>	'El Archivo esta sin precios',
			"type"	=>	'error'];
		}
		$this->jsonResponse($mensaje);
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
				$data['cotizacion']=$this->ct_mdl->update($cotizacion, ['id_cotizacion' => $cotiz->id_cotizacion]);
				$data['cotizacin']=$this->ctb_mdl->update($cotizacion, ['id_cotizacion' => $cotiz->id_cotizacion]);
			}else{
				$data['cotizacion']=$this->ct_mdl->insert($cotizacion);
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
				$data['cotizacion']=$this->ct_mdl->update($cotizacion, ['id_cotizacion' => $cotiz->id_cotizacion]);
				$data['cotizacin']=$this->ctb_mdl->update($cotizacion, ['id_cotizacion' => $cotiz->id_cotizacion]);
			}else{
				$data['cotizacion']=$this->ct_mdl->insert($cotizacion);
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
			$data ['id_cotizacin'] = $this->ctb_mdl->update([
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
			$data ['id_cotizacin'] = $this->ctb_mdl->update(["estatus" => 0], $productos[$i]);
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
							$data['cotizacin']=$this->ctb_mdl->update($new_cotizacion, ['id_cotizacion' => $cotiz->id_cotizacion]);
						}else{
							$data['cotizacion']=$this->ct_mdl->insert($new_cotizacion);
							$data['cotizacin']=$this->ctb_mdl->insert($new_cotizacion);
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
							$data['cotizacin']=$this->ctb_mdl->update($new_cotizacion, ['id_cotizacion' => $cotiz->id_cotizacion]);
						}else{
							$data['cotizacion']=$this->ct_mdl->insert($new_cotizacion);
							$data['cotizacin']=$this->ctb_mdl->insert($new_cotizacion);
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
		$config['upload_path']          = base_url('/assets/uploads/pedidos/');
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
		if ($id_proves <> "3") {
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
				$hoja->getColumnDimension('BA' )->setWidth("70");
				$hoja->getColumnDimension('J')->setWidth("20");
			}else{
				$hoja->getColumnDimension('BC')->setWidth("70");
				$hoja->getColumnDimension('I')->setWidth("20");
				$hoja->getColumnDimension('H')->setWidth("20");
				$hoja->getColumnDimension('F')->setWidth("8");
				$hoja->getColumnDimension('D')->setWidth("8");
			}
			$flagBorder = 0;
			$flagBorder1 = 1;
			$flagBorder2 = 0;
			$flagBorder3 = 1;
			$flage = 5;
			$i = 0;
			$sumall = array(1 => "", 2 => "", 3 => "", 4 => "", 5 => "", 6 => "", 7 => "", 8 => "", 9 => "", 10 => "");
			if ($array){
				foreach ($array as $key => $value){
					$fecha = new DateTime(date('Y-m-d H:i:s'));
					$intervalo = new DateInterval('P3D');
					$fecha->add($intervalo);
					if ($value->nombre === "AMARILLOS") {
						$where=["prod.estatus" => 3];//Semana actual
					}elseif ($value->nombre === "VOLUMEN" ) {
						$where=["prod.estatus" => 2];//Semana actual
					}else{
						$where=["ctz_first.id_proveedor" => $value->id_usuario,"prod.estatus" => 1];//Semana actual
					}
				$cotizacionesProveedor = $this->ct_mdl->getPedidosAll($where, $fecha->format('Y-m-d H:i:s'), 0);
				
					$difff = 0.01;
					$flag2 = 3;
					$cargo = "";
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
							$hoja->setCellValue("A".$flag."", "CEDIS, ABARROTES,PEDREGAL, TIENDA, ULTRAMARINOS, TRINCHERAS, MERCADO, TIJERAS, Y TENENCIA AZTECA AUTOSERVICIOS SA. DE CV.");
							$hoja->mergeCells('A'.$flag.':BA'.$flag);
							$this->excelfile->getActiveSheet()->getStyle('A'.$flag.':BA'.$flag)->applyFromArray($styleArray);
							$flag++;
							$hoja->mergeCells('B'.$flag.':J'.$flag);
							$hoja->mergeCells('K'.$flag.':N'.$flag);
							$hoja->mergeCells('O'.$flag.':Q'.$flag);
							$hoja->mergeCells('R'.$flag.':U'.$flag);
							$hoja->mergeCells('V'.$flag.':Y'.$flag);
							$hoja->mergeCells('Z'.$flag.':AC'.$flag);
							$hoja->mergeCells('AD'.$flag.':AG'.$flag);
							$hoja->mergeCells('AH'.$flag.':AK'.$flag);
							$hoja->mergeCells('AL'.$flag.':AO'.$flag);
							$hoja->mergeCells('AP'.$flag.':AS'.$flag);
							$hoja->mergeCells('AT'.$flag.':AW'.$flag);
							$hoja->mergeCells('AX'.$flag.':AZ'.$flag);
							$this->cellStyle("B".$flag, "FFFFFF", "000000", TRUE, 12, "Franklin Gothic Book");
							$hoja->setCellValue("B".$flag, "PEDIDOS A '".$value->nombre."' ".date("d-m-Y"));
							$this->cellStyle("K".$flag, "C00000", "000000", TRUE, 12, "Franklin Gothic Book");
							$hoja->setCellValue("K".$flag, "CEDIS/SUPER");
							$this->cellStyle("O".$flag, "C2B90A", "000000", TRUE, 12, "Franklin Gothic Book");
							$hoja->setCellValue("O".$flag, "SUMA CEDIS/SUPER");
							$this->cellStyle("R".$flag, "01B0F0", "000000", TRUE, 12, "Franklin Gothic Book");
							$hoja->setCellValue("R".$flag, "ABARROTES");
							$this->cellStyle("V".$flag, "FF0000", "000000", TRUE, 12, "Franklin Gothic Book");
							$hoja->setCellValue("V".$flag, "PEDREGAL");
							$this->cellStyle("Z".$flag, "E26C0B", "000000", TRUE, 12, "Franklin Gothic Book");
							$hoja->setCellValue("Z".$flag, "TIENDA");
							$this->cellStyle("AD".$flag, "C5C5C5", "000000", TRUE, 12, "Franklin Gothic Book");
							$hoja->setCellValue("AD".$flag, "ULTRAMARINOS");
							$this->cellStyle("AH".$flag, "92D051", "000000", TRUE, 12, "Franklin Gothic Book");
							$hoja->setCellValue("AH".$flag, "TRINCHERAS");
							$this->cellStyle("AL".$flag, "B1A0C7", "000000", TRUE, 12, "Franklin Gothic Book");
							$hoja->setCellValue("AL".$flag, "AZT MERCADO");
							$this->cellStyle("AP".$flag, "DA9694", "000000", TRUE, 12, "Franklin Gothic Book");
							$hoja->setCellValue("AP".$flag, "TENENCIA");
							$this->cellStyle("AT".$flag, "4CACC6", "000000", TRUE, 12, "Franklin Gothic Book");
							$hoja->setCellValue("AT".$flag, "TIJERAS");
							$this->cellStyle("AX".$flag, "FF0066", "000000", TRUE, 12, "Franklin Gothic Book");
							$hoja->setCellValue("AX".$flag, "CD INDUSTRIAL");
							$this->cellStyle("A3:AO4", "000000", "FFFFFF", TRUE, 12, "Franklin Gothic Book");
							$this->excelfile->getActiveSheet()->getStyle('A'.$flag.':AO'.$flag)->applyFromArray($styleArray);
							$flag++;
							$this->cellStyle("A".$flag.":BA".$flag."", "000000", "FFFFFF", TRUE, 12, "Franklin Gothic Book");
							$hoja->mergeCells('B'.$flag.':J'.$flag);
							$hoja->mergeCells('K'.$flag.':N'.$flag);
							$hoja->mergeCells('O'.$flag.':Q'.$flag);
							$hoja->mergeCells('R'.$flag.':U'.$flag);
							$hoja->mergeCells('V'.$flag.':Y'.$flag);
							$hoja->mergeCells('Z'.$flag.':AC'.$flag);
							$hoja->mergeCells('AD'.$flag.':AG'.$flag);
							$hoja->mergeCells('AH'.$flag.':AK'.$flag);
							$hoja->mergeCells('AL'.$flag.':AO'.$flag);
							$hoja->mergeCells('AP'.$flag.':AS'.$flag);
							$hoja->mergeCells('AT'.$flag.':AW'.$flag);
							$hoja->mergeCells('AX'.$flag.':AZ'.$flag);
							$hoja->setCellValue("B".$flag, "DESCRIPCIÓN");
							$hoja->setCellValue("K".$flag, "EXISTENCIAS");
							$hoja->setCellValue("O".$flag, " SUMA EXISTENCIAS");
							$hoja->setCellValue("R".$flag, "EXISTENCIAS");
							$hoja->setCellValue("V".$flag, "EXISTENCIAS");
							$hoja->setCellValue("Z".$flag, "EXISTENCIAS");
							$hoja->setCellValue("AD".$flag, "EXISTENCIAS");
							$hoja->setCellValue("AH".$flag, "EXISTENCIAS");
							$hoja->setCellValue("AL".$flag, "EXISTENCIAS");
							$hoja->setCellValue("AP".$flag, "EXISTENCIAS");
							$hoja->setCellValue("AT".$flag, "EXISTENCIAS");
							$hoja->setCellValue("AX".$flag, "EXISTENCIAS");
							$this->excelfile->getActiveSheet()->getStyle('A'.$flag.':BA'.$flag)->applyFromArray($styleArray);
							$flag++;
							$this->cellStyle("A".$flag.":BA".$flag."", "000000", "FFFFFF", TRUE, 12, "Franklin Gothic Book");
							$hoja->setCellValue("A".$flag, "CODIGO");
							$hoja->setCellValue("C".$flag, "1ER");
							$hoja->setCellValue("D".$flag, "COSTO");
							$hoja->setCellValue("E".$flag, "DIF % 5 Y 1ER");
							$hoja->setCellValue("F".$flag, "SISTEMA");
							$hoja->setCellValue("G".$flag, "DIF % 5 Y 4");
							$hoja->setCellValue("H".$flag, "PRECIO4");
							$hoja->setCellValue("I".$flag, "2DO");
							$hoja->setCellValue("J".$flag, "PROVEEDOR");
							$hoja->setCellValue("K".$flag, "CAJAS");
							$hoja->setCellValue("L".$flag, "PZAS");
							$hoja->setCellValue("M".$flag, "PEND");
							$hoja->setCellValue("N".$flag, "PEDIDO");
							$hoja->setCellValue("O".$flag, "CAJAS");
							$hoja->setCellValue("P".$flag, "PZAS");
							$hoja->setCellValue("Q".$flag, "PEDIDO");
							$hoja->setCellValue("R".$flag, "CAJAS");
							$hoja->setCellValue("S".$flag, "PZAS");
							$hoja->setCellValue("T".$flag, "PEND");
							$hoja->setCellValue("U".$flag, "PEDIDO");
							$hoja->setCellValue("V".$flag, "CAJAS");
							$hoja->setCellValue("W".$flag, "PZAS");
							$hoja->setCellValue("X".$flag, "PEND");
							$hoja->setCellValue("Y".$flag, "PEDIDO");
							$hoja->setCellValue("Z".$flag, "CAJAS");
							$hoja->setCellValue("AA".$flag, "PZAS");
							$hoja->setCellValue("AB".$flag, "PEND");
							$hoja->setCellValue("AC".$flag, "PEDIDO");
							$hoja->setCellValue("AD".$flag, "CAJAS");
							$hoja->setCellValue("AE".$flag, "PZAS");
							$hoja->setCellValue("AF".$flag, "PEND");
							$hoja->setCellValue("AG".$flag, "PEDIDO");
							$hoja->setCellValue("AH".$flag, "CAJAS");
							$hoja->setCellValue("AI".$flag, "PZAS");
							$hoja->setCellValue("AJ".$flag, "PEND");
							$hoja->setCellValue("AK".$flag, "PEDIDO");
							$hoja->setCellValue("AL".$flag, "CAJAS");
							$hoja->setCellValue("AM".$flag, "PZAS");
							$hoja->setCellValue("AN".$flag, "PEND");
							$hoja->setCellValue("AO".$flag, "PEDIDO");
							$hoja->setCellValue("AP".$flag, "CAJAS");
							$hoja->setCellValue("AQ".$flag, "PZAS");
							$hoja->setCellValue("AR".$flag, "PEND");
							$hoja->setCellValue("AS".$flag, "PEDIDO");
							$hoja->setCellValue("AT".$flag, "CAJAS");
							$hoja->setCellValue("AU".$flag, "PZAS");
							$hoja->setCellValue("AV".$flag, "PEND");
							$hoja->setCellValue("AW".$flag, "PEDIDO");
							$hoja->setCellValue("AX".$flag, "CAJAS");
							$hoja->setCellValue("AY".$flag, "PZAS");
							$hoja->setCellValue("AZ".$flag, "PEDIDO");
							$hoja->setCellValue("BA".$flag, "PROMOCIÓN");
							$hoja->setCellValue("BK".$flag, "TOTAL");
							$hoja->setCellValue("BL".$flag, "SUMA PEDIDOS");
							$this->cellStyle("BK".$flag."", "000000", "FFFFFF", TRUE, 12, "Franklin Gothic Book");
							$this->cellStyle("BL".$flag."", "000000", "FFFFFF", TRUE, 12, "Franklin Gothic Book");
							$this->excelfile->getActiveSheet()->getStyle('BK'.$flag)->applyFromArray($styleArray);
							$this->excelfile->getActiveSheet()->getStyle('BL'.$flag)->applyFromArray($styleArray);
							$this->excelfile->getActiveSheet()->getStyle('A'.$flag.':AS'.$flag)->applyFromArray($styleArray);
						}else{
							$this->cellStyle("A".$flag, "FFFFFF", "000000", TRUE, 12, "Franklin Gothic Book");
							$hoja->setCellValue("A".$flag."", "CEDIS, ABARROTES, PEDREGAL, TIENDA, ULTRAMARINOS, TRINCHERAS, MERCADO, TIJERAS, Y TENENCIA AZTECA AUTOSERVICIOS SA. DE CV.");
							$hoja->mergeCells('A'.$flag.':BC'.$flag);
							$this->excelfile->getActiveSheet()->getStyle('A'.$flag.':BC'.$flag)->applyFromArray($styleArray);
							$flag++;
							$hoja->mergeCells('B'.$flag.':I'.$flag);
							$hoja->mergeCells('J'.$flag.':N'.$flag);
							$hoja->mergeCells('O'.$flag.':Q'.$flag);
							$hoja->mergeCells('R'.$flag.':V'.$flag);
							$hoja->mergeCells('W'.$flag.':AA'.$flag);
							$hoja->mergeCells('AB'.$flag.':AE'.$flag);
							$hoja->mergeCells('AF'.$flag.':AI'.$flag);
							$hoja->mergeCells('AJ'.$flag.':AM'.$flag);
							$hoja->mergeCells('AN'.$flag.':AQ'.$flag);
							$hoja->mergeCells('AR'.$flag.':AU'.$flag);
							$hoja->mergeCells('AV'.$flag.':AY'.$flag);
							$hoja->mergeCells('AZ'.$flag.':BB'.$flag);
							$this->cellStyle("B".$flag, "FFFFFF", "000000", TRUE, 12, "Franklin Gothic Book");
							$hoja->setCellValue("B".$flag, "PEDIDOS A '".$value->nombre."' ".date("d-m-Y"));
							$this->cellStyle("J".$flag, "C00000", "000000", TRUE, 12, "Franklin Gothic Book");
							$hoja->setCellValue("J".$flag, "CEDIS/SUPER");
							$this->cellStyle("O".$flag, "C2B90A", "000000", TRUE, 12, "Franklin Gothic Book");
							$hoja->setCellValue("O".$flag, "SUMA CEDIS/SUPER");
							$this->cellStyle("R".$flag, "01B0F0", "000000", TRUE, 12, "Franklin Gothic Book");
							$hoja->setCellValue("R".$flag, "ABARROTES");
							$this->cellStyle("W".$flag, "FF0000", "000000", TRUE, 12, "Franklin Gothic Book");
							$hoja->setCellValue("W".$flag, "PEDREGAL");
							$this->cellStyle("AB".$flag, "E26C0B", "000000", TRUE, 12, "Franklin Gothic Book");
							$hoja->setCellValue("AB".$flag, "TIENDA");
							$this->cellStyle("AF".$flag, "C5C5C5", "000000", TRUE, 12, "Franklin Gothic Book");
							$hoja->setCellValue("AF".$flag, "ULTRAMARINOS");
							$this->cellStyle("AJ".$flag, "92D051", "000000", TRUE, 12, "Franklin Gothic Book");
							$hoja->setCellValue("AJ".$flag, "TRINCHERAS");
							$this->cellStyle("AN".$flag, "B1A0C7", "000000", TRUE, 12, "Franklin Gothic Book");
							$hoja->setCellValue("AN".$flag, "AZT MERCADO");
							$this->cellStyle("AR".$flag, "DA9694", "000000", TRUE, 12, "Franklin Gothic Book");
							$hoja->setCellValue("AR".$flag, "TENENCIA");
							$this->cellStyle("AV".$flag, "4CACC6", "000000", TRUE, 12, "Franklin Gothic Book");
							$hoja->setCellValue("AV".$flag, "TIJERAS");
							$this->cellStyle("AZ".$flag, "FF0066", "000000", TRUE, 12, "Franklin Gothic Book");
							$hoja->setCellValue("AZ".$flag, "CD INDUSTRIAL");
							$this->cellStyle("A3:BC4", "000000", "FFFFFF", TRUE, 12, "Franklin Gothic Book");
							$this->excelfile->getActiveSheet()->getStyle('A'.$flag.':BC'.$flag)->applyFromArray($styleArray);
							$flag++;
							$hoja->mergeCells('B'.$flag.':I'.$flag);
							$hoja->mergeCells('J'.$flag.':N'.$flag);
							$hoja->mergeCells('O'.$flag.':Q'.$flag);
							$hoja->mergeCells('R'.$flag.':V'.$flag);
							$hoja->mergeCells('W'.$flag.':AA'.$flag);
							$hoja->mergeCells('AB'.$flag.':AE'.$flag);
							$hoja->mergeCells('AF'.$flag.':AI'.$flag);
							$hoja->mergeCells('AJ'.$flag.':AM'.$flag);
							$hoja->mergeCells('AN'.$flag.':AQ'.$flag);
							$hoja->mergeCells('AR'.$flag.':AU'.$flag);
							$hoja->mergeCells('AV'.$flag.':AY'.$flag);
							$hoja->mergeCells('AZ'.$flag.':BB'.$flag);
							$this->cellStyle("A".$flag.":BC".$flag."", "000000", "FFFFFF", TRUE, 12, "Franklin Gothic Book");
							$hoja->setCellValue("B".$flag, "DESCRIPCIÓN");
							$hoja->setCellValue("J".$flag, "EXISTENCIAS");
							$hoja->setCellValue("O".$flag, "EXISTENCIAS");
							$hoja->setCellValue("R".$flag, "EXISTENCIAS");
							$hoja->setCellValue("W".$flag, "EXISTENCIAS");
							$hoja->setCellValue("AB".$flag, "EXISTENCIAS");
							$hoja->setCellValue("AF".$flag, "EXISTENCIAS");
							$hoja->setCellValue("AJ".$flag, "EXISTENCIAS");
							$hoja->setCellValue("AN".$flag, "EXISTENCIAS");
							$hoja->setCellValue("AR".$flag, "EXISTENCIAS");
							$hoja->setCellValue("AV".$flag, "EXISTENCIAS");
							$hoja->setCellValue("AZ".$flag, "EXISTENCIAS");
							$flag++;
							$this->cellStyle("A".$flag.":BC".$flag."", "000000", "FFFFFF", TRUE, 12, "Franklin Gothic Book");
							$hoja->setCellValue("A".$flag, "CODIGO");
							$hoja->setCellValue("C".$flag, "COSTO");
							$hoja->setCellValue("E".$flag, "SISTEMA");
							$hoja->setCellValue("G".$flag, "PRECIO4");
							$hoja->setCellValue("H".$flag, "2DO");
							$hoja->setCellValue("I".$flag, "PROVEEDOR");
							$hoja->setCellValue("J".$flag, "CAJAS");
							$hoja->setCellValue("K".$flag, "PZAS");
							$hoja->setCellValue("L".$flag, "STOCK");
							$hoja->setCellValue("M".$flag, "PEND");
							$hoja->setCellValue("N".$flag, "PEDIDO");
							$hoja->setCellValue("O".$flag, "CAJAS");
							$hoja->setCellValue("P".$flag, "PZAS");
							$hoja->setCellValue("Q".$flag, "PEDIDO");
							$hoja->setCellValue("R".$flag, "CAJAS");
							$hoja->setCellValue("S".$flag, "PZAS");
							$hoja->setCellValue("T".$flag, "STOCK");
							$hoja->setCellValue("U".$flag, "PEND");
							$hoja->setCellValue("V".$flag, "PEDIDO");
							$hoja->setCellValue("W".$flag, "CAJAS");
							$hoja->setCellValue("X".$flag, "PZAS");
							$hoja->setCellValue("Y".$flag, "STOCK");
							$hoja->setCellValue("Z".$flag, "PEND");
							$hoja->setCellValue("AA".$flag, "PEDIDO");
							$hoja->setCellValue("AB".$flag, "CAJAS");
							$hoja->setCellValue("AC".$flag, "PZAS");
							$hoja->setCellValue("AD".$flag, "PEND");
							$hoja->setCellValue("AE".$flag, "PEDIDO");
							$hoja->setCellValue("AF".$flag, "CAJAS");
							$hoja->setCellValue("AG".$flag, "PZAS");
							$hoja->setCellValue("AH".$flag, "PEND");
							$hoja->setCellValue("AI".$flag, "PEDIDO");
							$hoja->setCellValue("AJ".$flag, "CAJAS");
							$hoja->setCellValue("AK".$flag, "PZAS");
							$hoja->setCellValue("AL".$flag, "PEND");
							$hoja->setCellValue("AM".$flag, "PEDIDO");
							$hoja->setCellValue("AN".$flag, "CAJAS");
							$hoja->setCellValue("AO".$flag, "PZAS");
							$hoja->setCellValue("AP".$flag, "PEND");
							$hoja->setCellValue("AQ".$flag, "PEDIDO");
							$hoja->setCellValue("AR".$flag, "CAJAS");
							$hoja->setCellValue("AS".$flag, "PZAS");
							$hoja->setCellValue("AT".$flag, "PEND");
							$hoja->setCellValue("AU".$flag, "PEDIDO");
							$hoja->setCellValue("AV".$flag, "CAJAS");
							$hoja->setCellValue("AW".$flag, "PZAS");
							$hoja->setCellValue("AX".$flag, "PEND");
							$hoja->setCellValue("AY".$flag, "PEDIDO");
							$hoja->setCellValue("AZ".$flag, "CAJAS");
							$hoja->setCellValue("BA".$flag, "PZAS");
							$hoja->setCellValue("BB".$flag, "PEDIDO");
							$hoja->setCellValue("BC".$flag, "PROMOCION");
							$this->cellStyle("BD".$flag, "C00000", "000000", TRUE, 12, "Franklin Gothic Book");
							$this->cellStyle("BE".$flag, "01B0F0", "000000", TRUE, 12, "Franklin Gothic Book");
							$this->cellStyle("BF".$flag, "FF0000", "000000", TRUE, 12, "Franklin Gothic Book");
							$this->cellStyle("BG".$flag, "E26C0B", "000000", TRUE, 12, "Franklin Gothic Book");
							$this->cellStyle("BH".$flag, "C5C5C5", "000000", TRUE, 12, "Franklin Gothic Book");
							$this->cellStyle("BI".$flag, "92D051", "000000", TRUE, 12, "Franklin Gothic Book");
							$this->cellStyle("BJ".$flag, "B1A0C7", "000000", TRUE, 12, "Franklin Gothic Book");
							$this->cellStyle("BK".$flag, "DA9694", "000000", TRUE, 12, "Franklin Gothic Book");
							$this->cellStyle("BL".$flag, "4CACC6", "000000", TRUE, 12, "Franklin Gothic Book");
							$hoja->setCellValue("BM".$flag, "TOTAL");
							$this->cellStyle("BM".$flag."", "000000", "FFFFFF", TRUE, 12, "Franklin Gothic Book");
							$hoja->setCellValue("BN".$flag, "PEDIDOS");
							$this->cellStyle("BN".$flag."", "000000", "FFFFFF", TRUE, 12, "Franklin Gothic Book");
							$this->excelfile->getActiveSheet()->getStyle('BM'.$flag)->applyFromArray($styleArray);
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
									$cargo = $row['cargo'];
									$registrazo = date('Y-m-d',strtotime($row['registrazo']));
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
							         
					                 if($this->weekNumber($registrazo) == ($this->weekNumber() -1 ) || $this->weekNumber($registrazo) == ($this->weekNumber())){
										$this->cellStyle("A{$flag1}", "FF7F71", "000000", FALSE, 12, "Franklin Gothic Book");
										$this->cellStyle("B{$flag1}", "FF7F71", "000000", FALSE, 12, "Franklin Gothic Book");
										$this->cellStyle("C{$flag1}", "FF7F71", "000000", FALSE, 12, "Franklin Gothic Book");
										$this->cellStyle("E{$flag1}", "FF7F71", "000000", FALSE, 12, "Franklin Gothic Book");

									}
									//Pedidos
									$this->excelfile->setActiveSheetIndex(1);
									$this->cellStyle("A".$flag.":AF".$flag."", "FFFFFF", "000000", FALSE, 12, "Franklin Gothic Book");
									
									$hoja->setCellValue("A{$flag}", $row['codigo'])->getStyle("A{$flag}")->getNumberFormat()->setFormatCode('# ???/???');//Formato de fraccion
									if($row['color'] == '#92CEE3'){
										$this->cellStyle("A{$flag}", "92CEE3", "000000", FALSE, 12, "Franklin Gothic Book");
									}else{
										$this->cellStyle("A{$flag}", "FFFFFF", "000000", FALSE, 12, "Franklin Gothic Book");
									}
									$hoja->setCellValue("B{$flag}", $row['producto']);
									if ($id_proves === "VOLUMEN" || $id_proves === "AMARILLOS") {
										$hoja->setCellValue("D{$flag}", $row['proveedor_first']);
										if($row['precio_sistema'] < $row['precio_first']){
											$hoja->setCellValue("C{$flag}", $row['precio_first'])->getStyle("C{$flag}")->getNumberFormat()->setFormatCode('"$"#,##0.00_-');
											$this->cellStyle("C{$flag}", "FDB2B2", "E21111", FALSE, 12, "Franklin Gothic Book");
											$this->cellStyle("B{$flag}", "E21600", "000000", FALSE, 12, "Franklin Gothic Book");
										}else{
											$hoja->setCellValue("C{$flag}", $row['precio_first'])->getStyle("C{$flag}")->getNumberFormat()->setFormatCode('"$"#,##0.00_-');
											$this->cellStyle("C{$flag}", "96EAA8", "0C800C", FALSE, 12, "Franklin Gothic Book");
											$this->cellStyle("B{$flag}", "249947", "000000", FALSE, 12, "Franklin Gothic Book");
										}
										$hoja->setCellValue("F{$flag}", $row['precio_sistema'])->getStyle("F{$flag}")->getNumberFormat()->setFormatCode('"$"#,##0.00_-');//Formto de moneda
										$this->cellStyle("F".$flag, "FFFFFF","000000",  FALSE, 12, "Franklin Gothic Book");
										if($row['colorp'] == 1){
											$this->cellStyle("F{$flag}", "D6DCE4", "000000", FALSE, 12, "Franklin Gothic Book");
										}else{
											$this->cellStyle("F{$flag}", "FFFFFF", "000000", FALSE, 12, "Franklin Gothic Book");
										}
										$hoja->setCellValue("H{$flag}", $row['precio_four'])->getStyle("H{$flag}")->getNumberFormat()->setFormatCode('"$"#,##0.00_-');
										$this->cellStyle("H{$flag}", "FFFFFF", "000000", FALSE, 12, "Franklin Gothic Book");
										if($row['precio_sistema'] < $row['precio_next']){
											$hoja->setCellValue("I{$flag}", $row['precio_next'])->getStyle("I{$flag}")->getNumberFormat()->setFormatCode('"$"#,##0.00_-');
											$this->cellStyle("I{$flag}", "FDB2B2", "E21111", FALSE, 12, "Franklin Gothic Book");
										}else if($row['precio_next'] !== NULL){
											$hoja->setCellValue("I{$flag}", $row['precio_next'])->getStyle("I{$flag}")->getNumberFormat()->setFormatCode('"$"#,##0.00_-');
											$this->cellStyle("I{$flag}", "96EAA8", "0C800C", FALSE, 12, "Franklin Gothic Book");
										}else{
											$hoja->setCellValue("I{$flag}", $row['precio_next'])->getStyle("I{$flag}")->getNumberFormat()->setFormatCode('"$"#,##0.00_-');
											$this->cellStyle("I{$flag}", "FFFFFF", "000000", FALSE, 12, "Franklin Gothic Book");
										}
										$this->cellStyle("K".$flag.":AR".$flag, "FFFFFF", "000000", TRUE, 12, "Franklin Gothic Book");
										$hoja->setCellValue("J{$flag}", $row['proveedor_next']);
										$this->cellStyle("J".$flag, "FFFFFF", "000000", FALSE, 12, "Franklin Gothic Book");

										$hoja->setCellValue("M{$flag}", $row['cedis']);
										$hoja->setCellValue("T{$flag}", $row['abarrotes']);
										$hoja->setCellValue("X{$flag}", $row['pedregal']);
										$hoja->setCellValue("AB{$flag}", $row['tienda']);
										$hoja->setCellValue("AF{$flag}", $row['ultra']);
										$hoja->setCellValue("AJ{$flag}", $row['trincheras']);
										$hoja->setCellValue("AN{$flag}", $row['mercado']);
										$hoja->setCellValue("AR{$flag}", $row['tenencia']);
										$hoja->setCellValue("AV{$flag}", $row['tijeras']);

										$hoja->setCellValue("K{$flag}", $row['caja0']);
										$hoja->setCellValue("L{$flag}", $row['pz0']);
										$hoja->setCellValue("N{$flag}", $row['ped0']);
										$this->cellStyle("N{$flag}", "D4EAEF", "000000", TRUE, 12, "Franklin Gothic Book");

										$hoja->setCellValue("O{$flag}", ($row['caja0']+$row['caja9']));
										$hoja->setCellValue("P{$flag}", ($row['pz0']+$row['pz9']));
										$hoja->setCellValue("Q{$flag}", ($row['ped0']+$row['ped9']));
										$this->cellStyle("Q{$flag}", "D4EAEF", "000000", TRUE, 12, "Franklin Gothic Book");

										$hoja->setCellValue("R{$flag}", $row['caja1']);
										$hoja->setCellValue("S{$flag}", $row['pz1']);
										$hoja->setCellValue("U{$flag}", $row['ped1']);
										$this->cellStyle("U{$flag}", "D4EAEF", "000000", TRUE, 12, "Franklin Gothic Book");

										$hoja->setCellValue("V{$flag}", $row['caja2']);
										$hoja->setCellValue("W{$flag}", $row['pz2']);
										$hoja->setCellValue("Y{$flag}", $row['ped2']);
										$this->cellStyle("Y{$flag}", "D4EAEF", "000000", TRUE, 12, "Franklin Gothic Book");

										$hoja->setCellValue("Z{$flag}", $row['caja3']);
										$hoja->setCellValue("AA{$flag}", $row['pz3']);
										$hoja->setCellValue("AC{$flag}", $row['ped3']);
										$this->cellStyle("AC{$flag}", "D4EAEF", "000000", TRUE, 12, "Franklin Gothic Book");

										$hoja->setCellValue("AD{$flag}", $row['caja4']);
										$hoja->setCellValue("AE{$flag}", $row['pz4']);
										$hoja->setCellValue("AG{$flag}", $row['ped4']);
										$this->cellStyle("AG{$flag}", "D4EAEF", "000000", TRUE, 12, "Franklin Gothic Book");

										$hoja->setCellValue("AH{$flag}", $row['caja5']);
										$hoja->setCellValue("AI{$flag}", $row['pz5']);
										$hoja->setCellValue("AK{$flag}", $row['ped5']);
										$this->cellStyle("AK{$flag}", "D4EAEF", "000000", TRUE, 12, "Franklin Gothic Book");

										$hoja->setCellValue("AL{$flag}", $row['caja6']);
										$hoja->setCellValue("AM{$flag}", $row['pz6']);
										$hoja->setCellValue("AO{$flag}", $row['ped6']);
										$this->cellStyle("AO{$flag}", "D4EAEF", "000000", TRUE, 12, "Franklin Gothic Book");

										$hoja->setCellValue("AP{$flag}", $row['caja7']);
										$hoja->setCellValue("AQ{$flag}", $row['pz7']);
										$hoja->setCellValue("AS{$flag}", $row['ped7']);
										$this->cellStyle("AS{$flag}", "D4EAEF", "000000", TRUE, 12, "Franklin Gothic Book");

										$hoja->setCellValue("AT{$flag}", $row['caja8']);
										$hoja->setCellValue("AU{$flag}", $row['pz8']);
										$hoja->setCellValue("AW{$flag}", $row['ped8']);
										$this->cellStyle("AW{$flag}", "D4EAEF", "000000", TRUE, 12, "Franklin Gothic Book");

										$hoja->setCellValue("AX{$flag}", $row['caja9']);
										$hoja->setCellValue("AY{$flag}", $row['pz9']);
										$hoja->setCellValue("AZ{$flag}", $row['ped9']);
										$this->cellStyle("AZ{$flag}", "D4EAEF", "000000", TRUE, 12, "Franklin Gothic Book");

										$this->cellStyle("BA{$flag}", "FFFFFF", "000000", TRUE, 12, "Franklin Gothic Book");
										$hoja->setCellValue("BA{$flag}", $row['promocion_first']);
										$hoja->setCellValue("BB{$flag}", "=C".$flag."*N".$flag)->getStyle("BB{$flag}")->getNumberFormat()->setFormatCode('"$"#,##0.00_-');
										$hoja->setCellValue("BC{$flag}", "=C".$flag."*U".$flag)->getStyle("BC{$flag}")->getNumberFormat()->setFormatCode('"$"#,##0.00_-');
										$hoja->setCellValue("BD{$flag}", "=C".$flag."*Y".$flag)->getStyle("BD{$flag}")->getNumberFormat()->setFormatCode('"$"#,##0.00_-');
										$hoja->setCellValue("BE{$flag}", "=C".$flag."*AC".$flag)->getStyle("BE{$flag}")->getNumberFormat()->setFormatCode('"$"#,##0.00_-');
										$hoja->setCellValue("BF{$flag}", "=C".$flag."*AG".$flag)->getStyle("BF{$flag}")->getNumberFormat()->setFormatCode('"$"#,##0.00_-');
										$hoja->setCellValue("BG{$flag}", "=C".$flag."*AK".$flag)->getStyle("BG{$flag}")->getNumberFormat()->setFormatCode('"$"#,##0.00_-');
										$hoja->setCellValue("BH{$flag}", "=C".$flag."*AO".$flag)->getStyle("BH{$flag}")->getNumberFormat()->setFormatCode('"$"#,##0.00_-');
										$hoja->setCellValue("BI{$flag}", "=C".$flag."*AS".$flag)->getStyle("BI{$flag}")->getNumberFormat()->setFormatCode('"$"#,##0.00_-');
										$hoja->setCellValue("BJ{$flag}", "=C".$flag."*AW".$flag)->getStyle("BJ{$flag}")->getNumberFormat()->setFormatCode('"$"#,##0.00_-');
										$this->cellStyle("BK{$flag}", "D4EAEF", "000000", FALSE, 12, "Franklin Gothic Book");
										$hoja->setCellValue("BK{$flag}", "=SUM(BB".$flag.":BJ".$flag.")")->getStyle("BK{$flag}")->getNumberFormat()->setFormatCode('"$"#,##0.00_-');
										$this->cellStyle("BL{$flag}", "C2B90A", "000000", FALSE, 12, "Franklin Gothic Book");
										$hoja->setCellValue("BL{$flag}", "=AZ".$flag."+AW".$flag."+AS".$flag."+AO".$flag."+AK".$flag."+AG".$flag."+AC".$flag."+Y".$flag."+U".$flag."+N".$flag."");
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
										$hoja->setCellValue("E{$flag}", $row['precio_sistema'])->getStyle("E{$flag}")->getNumberFormat()->setFormatCode('"$"#,##0.00_-');//Formto de moneda
										$this->cellStyle("E".$flag, "FFFFFF","000000",  FALSE, 12, "Franklin Gothic Book");
										if($row['colorp'] == 1){
											$this->cellStyle("E{$flag}", "D6DCE4", "000000", FALSE, 12, "Franklin Gothic Book");
										}else{
											$this->cellStyle("E{$flag}", "FFFFFF", "000000", FALSE, 12, "Franklin Gothic Book");
										}
										$hoja->setCellValue("G{$flag}", $row['precio_four'])->getStyle("G{$flag}")->getNumberFormat()->setFormatCode('"$"#,##0.00_-');
										$this->cellStyle("G{$flag}", "FFFFFF", "000000", FALSE, 12, "Franklin Gothic Book");
										if($row['precio_sistema'] < $row['precio_next']){
											$hoja->setCellValue("H{$flag}", $row['precio_next'])->getStyle("H{$flag}")->getNumberFormat()->setFormatCode('"$"#,##0.00_-');
											$this->cellStyle("H{$flag}", "FDB2B2", "E21111", FALSE, 12, "Franklin Gothic Book");
										}else if($row['precio_next'] !== NULL){
											$hoja->setCellValue("H{$flag}", $row['precio_next'])->getStyle("H{$flag}")->getNumberFormat()->setFormatCode('"$"#,##0.00_-');
											$this->cellStyle("H{$flag}", "96EAA8", "0C800C", FALSE, 12, "Franklin Gothic Book");
										}else{
											$hoja->setCellValue("H{$flag}", $row['precio_next'])->getStyle("H{$flag}")->getNumberFormat()->setFormatCode('"$"#,##0.00_-');
											$this->cellStyle("H{$flag}", "FFFFFF", "000000", FALSE, 12, "Franklin Gothic Book");
										}
										$hoja->setCellValue("I{$flag}", $row['proveedor_next']);
										$this->cellStyle("I".$flag.":AX".$flag, "FFFFFF", "000000", TRUE, 12, "Franklin Gothic Book");
										

										$hoja->setCellValue("M{$flag}", $row['cedis']);
										$hoja->setCellValue("U{$flag}", $row['abarrotes']);
										$hoja->setCellValue("Z{$flag}", $row['pedregal']);
										$hoja->setCellValue("AD{$flag}", $row['tienda']);
										$hoja->setCellValue("AH{$flag}", $row['ultra']);
										$hoja->setCellValue("AL{$flag}", $row['trincheras']);
										$hoja->setCellValue("AP{$flag}", $row['mercado']);
										$hoja->setCellValue("AT{$flag}", $row['tenencia']);
										$hoja->setCellValue("AX{$flag}", $row['tijeras']);

										$hoja->setCellValue("J{$flag}", $row['caja0']);
										$hoja->setCellValue("K{$flag}", $row['pz0']);
										$hoja->setCellValue("N{$flag}", $row['ped0']);
										$this->cellStyle("N{$flag}", "D4EAEF", "000000", TRUE, 12, "Franklin Gothic Book");

										$hoja->setCellValue("O{$flag}", ($row['caja0']+$row['caja9']));
										$hoja->setCellValue("P{$flag}", ($row['pz0']+$row['pz9']));
										$hoja->setCellValue("Q{$flag}", ($row['ped0']+$row['ped9']));
										$this->cellStyle("Q{$flag}", "D4EAEF", "000000", TRUE, 12, "Franklin Gothic Book");

										$hoja->setCellValue("R{$flag}", $row['caja1']);
										$hoja->setCellValue("S{$flag}", $row['pz1']);
										$hoja->setCellValue("T{$flag}", $row['stocant']);
										$hoja->setCellValue("V{$flag}", $row['ped1']);
										$this->cellStyle("V{$flag}", "D4EAEF", "000000", TRUE, 12, "Franklin Gothic Book");

										$hoja->setCellValue("W{$flag}", $row['caja2']);
										$hoja->setCellValue("X{$flag}", $row['pz2']);
										$hoja->setCellValue("AA{$flag}", $row['ped2']);
										$this->cellStyle("AA{$flag}", "D4EAEF", "000000", TRUE, 12, "Franklin Gothic Book");

										$hoja->setCellValue("AB{$flag}", $row['caja3']);
										$hoja->setCellValue("AC{$flag}", $row['pz3']);
										$hoja->setCellValue("AE{$flag}", $row['ped3']);
										$this->cellStyle("AE{$flag}", "D4EAEF", "000000", TRUE, 12, "Franklin Gothic Book");

										$hoja->setCellValue("AF{$flag}", $row['caja4']);
										$hoja->setCellValue("AG{$flag}", $row['pz4']);
										$hoja->setCellValue("AI{$flag}", $row['ped4']);
										$this->cellStyle("AI{$flag}", "D4EAEF", "000000", TRUE, 12, "Franklin Gothic Book");

										$hoja->setCellValue("AJ{$flag}", $row['caja5']);
										$hoja->setCellValue("AK{$flag}", $row['pz5']);
										$hoja->setCellValue("AM{$flag}", $row['ped5']);
										$this->cellStyle("AM{$flag}", "D4EAEF", "000000", TRUE, 12, "Franklin Gothic Book");

										$hoja->setCellValue("AN{$flag}", $row['caja6']);
										$hoja->setCellValue("AO{$flag}", $row['pz6']);
										$hoja->setCellValue("AQ{$flag}", $row['ped6']);
										$this->cellStyle("AQ{$flag}", "D4EAEF", "000000", TRUE, 12, "Franklin Gothic Book");

										$hoja->setCellValue("AR{$flag}", $row['caja7']);
										$hoja->setCellValue("AS{$flag}", $row['pz7']);
										$hoja->setCellValue("AU{$flag}", $row['ped7']);
										$this->cellStyle("AU{$flag}", "D4EAEF", "000000", TRUE, 12, "Franklin Gothic Book");

										$hoja->setCellValue("AV{$flag}", $row['caja8']);
										$hoja->setCellValue("AW{$flag}", $row['pz8']);
										$hoja->setCellValue("AY{$flag}", $row['ped8']);
										$this->cellStyle("AY{$flag}", "D4EAEF", "000000", TRUE, 12, "Franklin Gothic Book");

										$hoja->setCellValue("AZ{$flag}", $row['caja9']);
										$hoja->setCellValue("BA{$flag}", $row['pz9']);
										$hoja->setCellValue("BB{$flag}", $row['ped9']);
										$this->cellStyle("BB{$flag}", "D4EAEF", "000000", TRUE, 12, "Franklin Gothic Book");

										$hoja->setCellValue("BC{$flag}", $row['promocion_first']);
										$hoja->setCellValue("BD{$flag}", "=C".$flag."*N".$flag)->getStyle("BD{$flag}")->getNumberFormat()->setFormatCode('"$"#,##0.00_-');
										$hoja->setCellValue("BE{$flag}", "=C".$flag."*V".$flag)->getStyle("BE{$flag}")->getNumberFormat()->setFormatCode('"$"#,##0.00_-');
										$hoja->setCellValue("BF{$flag}", "=C".$flag."*AA".$flag)->getStyle("BF{$flag}")->getNumberFormat()->setFormatCode('"$"#,##0.00_-');
										$hoja->setCellValue("BG{$flag}", "=C".$flag."*AE".$flag)->getStyle("BG{$flag}")->getNumberFormat()->setFormatCode('"$"#,##0.00_-');
										$hoja->setCellValue("BH{$flag}", "=C".$flag."*AI".$flag)->getStyle("BH{$flag}")->getNumberFormat()->setFormatCode('"$"#,##0.00_-');
										$hoja->setCellValue("BI{$flag}", "=C".$flag."*AM".$flag)->getStyle("BI{$flag}")->getNumberFormat()->setFormatCode('"$"#,##0.00_-');
										$hoja->setCellValue("BJ{$flag}", "=C".$flag."*AQ".$flag)->getStyle("BJ{$flag}")->getNumberFormat()->setFormatCode('"$"#,##0.00_-');
										$hoja->setCellValue("BK{$flag}", "=C".$flag."*AU".$flag)->getStyle("BK{$flag}")->getNumberFormat()->setFormatCode('"$"#,##0.00_-');
										$hoja->setCellValue("BL{$flag}", "=C".$flag."*AY".$flag)->getStyle("BL{$flag}")->getNumberFormat()->setFormatCode('"$"#,##0.00_-');
										$this->cellStyle("BM{$flag}", "D4EAEF", "000000", FALSE, 12, "Franklin Gothic Book");
										$hoja->setCellValue("BM{$flag}", "=SUM(BD".$flag.":BL".$flag.")")->getStyle("BM{$flag}")->getNumberFormat()->setFormatCode('"$"#,##0.00_-');
										$this->cellStyle("BN{$flag}", "C2B90A", "000000", FALSE, 12, "Franklin Gothic Book");
										$hoja->setCellValue("BN{$flag}", "=N".$flag."+V".$flag."+AA".$flag."+AE".$flag."+AI".$flag."+AM".$flag."+AQ".$flag."+AU".$flag."+AY".$flag."+BB".$flag."");
									}
									$border_style= array('borders' => array('right' => array('style' =>
										PHPExcel_Style_Border::BORDER_THIN,'color' => array('argb' => '000000'),)));
									$this->excelfile->setActiveSheetIndex(1);
									if ($id_proves === "VOLUMEN"){
										$this->excelfile->getActiveSheet()->getStyle('A'.$flag.':BA'.$flag)->applyFromArray($styleArray);
										$this->excelfile->getActiveSheet()->getStyle('BK'.$flag.':BL'.$flag)->applyFromArray($styleArray);
										if($row['precio_sistema'] == 0){
											$row['precio_sistema'] = 1;
										}
										if($row['precio_four'] == 0){
											$row['precio_four'] = 1;
										}
											$hoja->setCellValue("E{$flag}",100 - ($row['precio_first'] * 100 / $row['precio_sistema']))->getStyle("E{$flag}")->getNumberFormat()->setFormatCode('"%"#,##0.00_-');;
											$this->cellStyle("E".$flag, "FF9999", "000000", FALSE, 10, "Franklin Gothic Book");

											$hoja->setCellValue("G{$flag}", 100 - ($row['precio_sistema'] * 100 / $row['precio_four']))->getStyle("G{$flag}")->getNumberFormat()->setFormatCode('"%"#,##0.00_-');;
											$this->cellStyle("G".$flag, "FF9999", "000000", FALSE, 10, "Franklin Gothic Book");
									}else{
										$this->excelfile->getActiveSheet()->getStyle('A'.$flag.':BC'.$flag)->applyFromArray($styleArray);
										$this->excelfile->getActiveSheet()->getStyle('BM'.$flag)->applyFromArray($styleArray);
										$this->excelfile->getActiveSheet()->getStyle('BN'.$flag)->applyFromArray($styleArray);
										if($row['precio_sistema'] == 0){
											$row['precio_sistema'] = 1;
										}
										if($row['precio_four'] == 0){
											$row['precio_four'] = 1;
										}
											$hoja->setCellValue("D{$flag}",100 - ($row['precio_first'] * 100 / $row['precio_sistema']))->getStyle("D{$flag}")->getNumberFormat()->setFormatCode('"%"#,##0.00_-');;
											$this->cellStyle("D".$flag, "FF9999", "000000", FALSE, 10, "Franklin Gothic Book");
											$hoja->setCellValue("F{$flag}", 100 - ($row['precio_sistema'] * 100 / $row['precio_four']))->getStyle("F{$flag}")->getNumberFormat()->setFormatCode('"%"#,##0.00_-');;
											$this->cellStyle("F".$flag, "FF9999", "000000", FALSE, 10, "Franklin Gothic Book");
									}
									
									$this->excelfile->setActiveSheetIndex(0);
									$this->excelfile->getActiveSheet()->getStyle('A'.$flag1.':E'.$flag1)->applyFromArray($styleArray);
									$hoja->getStyle("A{$flag}:G{$flag}")
							                 ->getAlignment()
							                 ->setHorizontal(\PHPExcel_Style_Alignment::HORIZONTAL_LEFT);

							   if($this->weekNumber($registrazo) == ($this->weekNumber() - 1) || $this->weekNumber($registrazo) == ($this->weekNumber())){
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
							$hoja->setCellValue("BB{$flagf}", "=SUM(BB5:BB".$flagfs.")")->getStyle("BB{$flag}")->getNumberFormat()->setFormatCode('"$"#,##0.00_-');
							$hoja->setCellValue("BC{$flagf}", "=SUM(BC5:BC".$flagfs.")")->getStyle("BC{$flag}")->getNumberFormat()->setFormatCode('"$"#,##0.00_-');
							$hoja->setCellValue("BD{$flagf}", "=SUM(BD5:BD".$flagfs.")")->getStyle("BD{$flag}")->getNumberFormat()->setFormatCode('"$"#,##0.00_-');
							$hoja->setCellValue("BE{$flagf}", "=SUM(BE5:BE".$flagfs.")")->getStyle("BE{$flag}")->getNumberFormat()->setFormatCode('"$"#,##0.00_-');
							$hoja->setCellValue("BF{$flagf}", "=SUM(BF5:BF".$flagfs.")")->getStyle("BF{$flag}")->getNumberFormat()->setFormatCode('"$"#,##0.00_-');
							$hoja->setCellValue("BG{$flagf}", "=SUM(BG5:BG".$flagfs.")")->getStyle("BG{$flag}")->getNumberFormat()->setFormatCode('"$"#,##0.00_-');
							$hoja->setCellValue("BH{$flagf}", "=SUM(BH5:BH".$flagfs.")")->getStyle("BH{$flag}")->getNumberFormat()->setFormatCode('"$"#,##0.00_-');
							$hoja->setCellValue("BI{$flagf}", "=SUM(BI5:BI".$flagfs.")")->getStyle("BI{$flag}")->getNumberFormat()->setFormatCode('"$"#,##0.00_-');
							$hoja->setCellValue("BJ{$flagf}", "=SUM(BJ5:BJ".$flagfs.")")->getStyle("BJ{$flag}")->getNumberFormat()->setFormatCode('"$"#,##0.00_-');
							$this->cellStyle("BK{$flagf}", "000000", "FFFFFF", FALSE, 12, "Franklin Gothic Book");
							$hoja->setCellValue("BK{$flagf}", "=SUM(BF5:BF".$flagfs.")")->getStyle("BK{$flagf}")->getNumberFormat()->setFormatCode('"$"#,##0.00_-');
							$sumall[1] .= "BB".$flagf."+";
							$sumall[2] .= "BC".$flagf."+";
							$sumall[3] .= "BD".$flagf."+";
							$sumall[4] .= "BE".$flagf."+";
							$sumall[5] .= "BF".$flagf."+";
							$sumall[6] .= "BG".$flagf."+";
							$sumall[7] .= "BH".$flagf."+";
							$sumall[8] .= "BI".$flagf."+";
							$sumall[9] .= "BJ".$flagf."+";
							$sumall[10] .= "BK".$flagf."+";
						}else{
							$flagf = $flag;
							$flagfs = $flag - 1;
							$hoja->setCellValue("BA{$flagf}", "=SUM(BA".$flage.":BA".$flagfs.")")->getStyle("BA{$flag}")->getNumberFormat()->setFormatCode('"$"#,##0.00_-');
							$hoja->setCellValue("BB{$flagf}", "=SUM(BB".$flage.":BB".$flagfs.")")->getStyle("BB{$flag}")->getNumberFormat()->setFormatCode('"$"#,##0.00_-');
							$hoja->setCellValue("BC{$flagf}", "=SUM(BC".$flage.":BC".$flagfs.")")->getStyle("BC{$flag}")->getNumberFormat()->setFormatCode('"$"#,##0.00_-');
							$hoja->setCellValue("BD{$flagf}", "=SUM(BD".$flage.":BD".$flagfs.")")->getStyle("BD{$flag}")->getNumberFormat()->setFormatCode('"$"#,##0.00_-');
							$hoja->setCellValue("BE{$flagf}", "=SUM(BE".$flage.":BE".$flagfs.")")->getStyle("BE{$flag}")->getNumberFormat()->setFormatCode('"$"#,##0.00_-');
							$hoja->setCellValue("BF{$flagf}", "=SUM(BF".$flage.":BF".$flagfs.")")->getStyle("BF{$flag}")->getNumberFormat()->setFormatCode('"$"#,##0.00_-');
							$hoja->setCellValue("BG{$flagf}", "=SUM(BG".$flage.":BG".$flagfs.")")->getStyle("BG{$flag}")->getNumberFormat()->setFormatCode('"$"#,##0.00_-');
							$hoja->setCellValue("BH{$flagf}", "=SUM(BH".$flage.":BH".$flagfs.")")->getStyle("BH{$flag}")->getNumberFormat()->setFormatCode('"$"#,##0.00_-');
							$hoja->setCellValue("BI{$flagf}", "=SUM(BI".$flage.":BI".$flagfs.")")->getStyle("BI{$flag}")->getNumberFormat()->setFormatCode('"$"#,##0.00_-');
							$hoja->setCellValue("BJ{$flagf}", "=SUM(BJ".$flage.":BJ".$flagfs.")")->getStyle("BJ{$flag}")->getNumberFormat()->setFormatCode('"$"#,##0.00_-');
							$this->cellStyle("BJ{$flagf}", "000000", "FFFFFF", FALSE, 12, "Franklin Gothic Book");
							$hoja->setCellValue("BJ{$flagf}", "=SUM(BJ".$flage.":BJ".$flagfs.")")->getStyle("BJ{$flag}")->getNumberFormat()->setFormatCode('"$"#,##0.00_-');
							$sumall[1] .= "BA".$flagf."+";
							$sumall[2] .= "BB".$flagf."+";
							$sumall[3] .= "BC".$flagf."+";
							$sumall[4] .= "BD".$flagf."+";
							$sumall[5] .= "BE".$flagf."+";
							$sumall[6] .= "BF".$flagf."+";
							$sumall[7] .= "BG".$flagf."+";
							$sumall[8] .= "BH".$flagf."+";
							$sumall[9] .= "BI".$flagf."+";
							$sumall[10] .= "BJ".$flagf."+";
							$flage = $flag + 7;
						}
						if ($id_proves <> "VOLUMEN"){
							$hoja->setCellValue("B{$flag}", "RESPONSABLE : ".$cargo);
							$this->cellStyle("B{$flag}", "FFFF00", "FF0000", TRUE, 12, "Franklin Gothic Book");
						}
						$flag++;
						$flag1++;
						$flag++;
						$flag1++;
						$flag1++;
						$flag1++;
						$flag++;
						$flag++;
					}
				}
			}
			$this->cellStyle("B".$flag, "C00000", "000000", TRUE, 12, "Franklin Gothic Book");
			$hoja->setCellValue("B".$flag, "CEDIS/SUPER");
			$hoja->setCellValue("C{$flag}", "=(".substr($sumall[1],0,-1).")")->getStyle("C{$flag}")->getNumberFormat()->setFormatCode('"$"#,##0.00_-');
			$flag++;
			$this->cellStyle("B".$flag, "01B0F0", "000000", TRUE, 12, "Franklin Gothic Book");
			$hoja->setCellValue("B".$flag, "ABARROTES");
			$hoja->setCellValue("C{$flag}", "=(".substr($sumall[2],0,-1).")")->getStyle("C{$flag}")->getNumberFormat()->setFormatCode('"$"#,##0.00_-');
			$flag++;
			$this->cellStyle("B".$flag, "FF0000", "000000", TRUE, 12, "Franklin Gothic Book");
			$hoja->setCellValue("B".$flag, "V. PEDREGAL");
			$hoja->setCellValue("C{$flag}", "=(".substr($sumall[3],0,-1).")")->getStyle("C{$flag}")->getNumberFormat()->setFormatCode('"$"#,##0.00_-');
			$flag++;
			$this->cellStyle("B".$flag, "E26C0B", "000000", TRUE, 12, "Franklin Gothic Book");
			$hoja->setCellValue("B".$flag, "TIENDA");
			$hoja->setCellValue("C{$flag}", "=(".substr($sumall[4],0,-1).")")->getStyle("C{$flag}")->getNumberFormat()->setFormatCode('"$"#,##0.00_-');
			$flag++;
			$this->cellStyle("B".$flag, "C5C5C5", "000000", TRUE, 12, "Franklin Gothic Book");
			$hoja->setCellValue("B".$flag, "ULTRAMARINOS");
			$hoja->setCellValue("C{$flag}", "=(".substr($sumall[5],0,-1).")")->getStyle("C{$flag}")->getNumberFormat()->setFormatCode('"$"#,##0.00_-');
			$flag++;
			$this->cellStyle("B".$flag, "92D051", "000000", TRUE, 12, "Franklin Gothic Book");
			$hoja->setCellValue("B".$flag, "TRINCHERAS");
			$hoja->setCellValue("C{$flag}", "=(".substr($sumall[6],0,-1).")")->getStyle("C{$flag}")->getNumberFormat()->setFormatCode('"$"#,##0.00_-');
			$flag++;
			$this->cellStyle("B".$flag, "B1A0C7", "000000", TRUE, 12, "Franklin Gothic Book");
			$hoja->setCellValue("B".$flag, "AZT MERCADO");
			$hoja->setCellValue("C{$flag}", "=(".substr($sumall[7],0,-1).")")->getStyle("C{$flag}")->getNumberFormat()->setFormatCode('"$"#,##0.00_-');
			$flag++;
			$this->cellStyle("B".$flag, "DA9694", "000000", TRUE, 12, "Franklin Gothic Book");
			$hoja->setCellValue("B".$flag, "TENENCIA");
			$hoja->setCellValue("C{$flag}", "=(".substr($sumall[8],0,-1).")")->getStyle("C{$flag}")->getNumberFormat()->setFormatCode('"$"#,##0.00_-');
			$flag++;
			$this->cellStyle("B".$flag, "4CACC6", "000000", TRUE, 12, "Franklin Gothic Book");
			$hoja->setCellValue("B".$flag, "TIJERAS");
			$hoja->setCellValue("C{$flag}", "=(".substr($sumall[9],0,-1).")")->getStyle("C{$flag}")->getNumberFormat()->setFormatCode('"$"#,##0.00_-');
			$flag++;
			$this->cellStyle("B".$flag, "000000", "FFFFFF", TRUE, 12, "Franklin Gothic Book");
			$hoja->setCellValue("B".$flag, "TOTAL");
			$hoja->setCellValue("C{$flag}", "=(".substr($sumall[10],0,-1).")")->getStyle("C{$flag}")->getNumberFormat()->setFormatCode('"$"#,##0.00_-');
			$flag++;

			$dias = array("DOMINGO","LUNES","MARTES","MIÉRCOLES","JUEVES","VIERNES","SÁBADO");
			$meses = array("ENERO","FEBRERO","MARZO","ABRIL","MAYO","JUNIO","JULIO","AGOSTO","SEPTIEMBRE","OCTUBRE","NOVIEMBRE","DICIEMBRE");
			$fecha =  $dias[date('w')]." ".date('d')." DE ".$meses[date('n')-1]. " DEL ".date('Y') ;
			$file_name = "FORMATO ".$filenam." ".$fecha.".xlsx"; //Nombre del documento con extención
			$excel_Writer = PHPExcel_IOFactory::createWriter($this->excelfile, "Excel2007");
			header("Content-Type: application/vnd.ms-excel; charset=utf-8");
			header("Content-Disposition: attachment;filename=".$file_name);
			header("Cache-Control: max-age=0");
			$excel_Writer->save("php://output");
			/*$excel_Writer = new PHPExcel_Writer_Excel2007($this->excelfile);
			$excel_Writer->setOffice2003Compatibility(true);
			$excel_Writer->save("php://output");*/
		}else{
			$this->fill_duerazo();
		}
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
		$hoja->mergeCells('X1:Z1');
		$this->cellStyle("X1", "4CACC6", "000000", TRUE, 12, "Franklin Gothic Book");
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
		$styleArray = array(
	        'alignment' => array(
	            'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_LEFT,
	        ),
	        'borders' => array('right' => array('style' =>
			PHPExcel_Style_Border::BORDER_THIN,'color' => array('argb' => '000000'),))
	    );
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
		$hoja->setCellValue("A2", "CÓDIGO")->getColumnDimension('A')->setWidth(22); //Nombre y ajuste de texto a la columna
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
		//$this->jsonResponse($cotizacionesProveedor);
		if ($cotizacionesProveedor) {
			foreach ($cotizacionesProveedor as $key => $value) {
				foreach ($value["articulos"] as $key => $val) {
					$hoja->getStyle("A{$rws}")->applyFromArray($styleArray);
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
		$file_name = "DIRECTOS".$prueba." ".$fecha.".xlsx"; //Nombre del documento con extención
		header("Content-Type: application/vnd.ms-excel; charset=utf-8");
		header("Content-Disposition: attachment;filename=".$file_name);
		header("Cache-Control: max-age=0");
		$excel_Writer = PHPExcel_IOFactory::createWriter($this->excelfile, "Excel2007");
		$excel_Writer->save("php://output");
	}
	private function fill_duerazo(){
		$flag =1;
		$array = "";
		$array2 = "";
		$filenam = "";
		ini_set("memory_limit", "-1");
		ini_set("max_execution_time", "-1");
		$this->load->library("excelfile");
		//$this->excelfile = PHPExcel_IOFactory::load("./assets/uploads/template.xlsx");
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
		$hoja->getColumnDimension('B')->setWidth("20");
		$hoja->getColumnDimension('D')->setWidth("15");
		$hoja->getColumnDimension('C')->setWidth("70");
		$hoja->getColumnDimension('E')->setWidth("8");
		$hoja->getColumnDimension('F')->setWidth("15");
		$hoja->getColumnDimension('G')->setWidth("8");
		$hoja->getColumnDimension('J')->setWidth("20");
		$hoja->getColumnDimension('BA')->setWidth("70");
		$hoja->getColumnDimension('I')->setWidth("15"); 
		
		$hoja1->getColumnDimension('A')->setWidth("6");
		$hoja1->getColumnDimension('B')->setWidth("6");
		$hoja1->getColumnDimension('C')->setWidth("6");
		$hoja1->getColumnDimension('D')->setWidth("25");
		$hoja1->getColumnDimension('E')->setWidth("47");

		$this->excelfile->setActiveSheetIndex(0);

		$hoja1->mergeCells('A1:E1');
		$this->cellStyle("A1", "FFFFFF", "000000", TRUE, 12, "Franklin Gothic Book");
		$hoja1->setCellValue("A1", "GRUPO ABARROTES AZTECA");
		$this->excelfile->getActiveSheet()->getStyle('A1:E1')->applyFromArray($styleArray);

		$hoja1->mergeCells('A2:E2');
		$this->cellStyle("A2", "FFFFFF", "000000", TRUE, 12, "Franklin Gothic Book");
		$hoja1->setCellValue("A2", "PEDIDOS A 'DUERO' ".date("d-m-Y"));
		$this->excelfile->getActiveSheet()->getStyle('A2:E2')->applyFromArray($styleArray);

		$this->cellStyle("A3:D3", "000000", "FFFFFF", TRUE, 12, "Franklin Gothic Book");
		$hoja1->mergeCells('A3:B3');
		$hoja1->setCellValue("A3", "EXISTENCIAS");
		$hoja1->setCellValue("E3", "DESCRIPCIÓN");
		$this->cellStyle("E3", "000000", "FFFFFF", TRUE, 12, "Franklin Gothic Book");
		$this->excelfile->getActiveSheet()->getStyle('A3:E3')->applyFromArray($styleArray);

		$this->cellStyle("A4:E4", "000000", "FFFFFF", TRUE, 12, "Franklin Gothic Book");
		$hoja1->setCellValue("A4", "CAJAS");
		$hoja1->setCellValue("B4", "PZAS");
		$hoja1->setCellValue("C4", "PEDIDO");
		$hoja1->setCellValue("D4", "COD");

		$this->excelfile->setActiveSheetIndex(1);
		$flag = 1;
		$this->cellStyle("A".$flag, "FFFFFF", "000000", TRUE, 12, "Franklin Gothic Book");
		$hoja->setCellValue("A".$flag, "CEDIS,CD INDUSTRIAL, ABARROTES, TIENDA, ULTRAMARINOS, TRINCHERAS, MERCADO, TIJERAS, Y TENENCIA AZTECA AUTOSERVICIOS SA. DE CV.");
		$hoja->mergeCells('A'.$flag.':BA'.$flag);
		$this->excelfile->getActiveSheet()->getStyle('A'.$flag.':BA'.$flag)->applyFromArray($styleArray);
		$flag++;
		$hoja->mergeCells('B'.$flag.':J'.$flag);
		$hoja->mergeCells('K'.$flag.':O'.$flag);
		$hoja->mergeCells('P'.$flag.':T'.$flag);
		$hoja->mergeCells('U'.$flag.':Y'.$flag);
		$hoja->mergeCells('Z'.$flag.':AC'.$flag);
		$hoja->mergeCells('AD'.$flag.':AG'.$flag);
		$hoja->mergeCells('AH'.$flag.':AK'.$flag);
		$hoja->mergeCells('AL'.$flag.':AO'.$flag);
		$hoja->mergeCells('AP'.$flag.':AS'.$flag);
		$hoja->mergeCells('AT'.$flag.':AW'.$flag);
		$hoja->mergeCells('AX'.$flag.':AZ'.$flag);
		$this->cellStyle("B".$flag, "FFFFFF", "000000", TRUE, 12, "Franklin Gothic Book");
		$hoja->setCellValue("B".$flag, "PEDIDOS A 'DUERO' ".date("d-m-Y"));
		$this->cellStyle("K".$flag, "C00000", "000000", TRUE, 12, "Franklin Gothic Book");
		$hoja->setCellValue("K".$flag, "CEDIS/SUPER");
		$this->cellStyle("P".$flag, "01B0F0", "000000", TRUE, 12, "Franklin Gothic Book");
		$hoja->setCellValue("P".$flag, "ABARROTES");
		$this->cellStyle("U".$flag, "FF0000", "000000", TRUE, 12, "Franklin Gothic Book");
		$hoja->setCellValue("U".$flag, "PEDREGAL");
		$this->cellStyle("Z".$flag, "E26C0B", "000000", TRUE, 12, "Franklin Gothic Book");
		$hoja->setCellValue("Z".$flag, "TIENDA");
		$this->cellStyle("AD".$flag, "C5C5C5", "000000", TRUE, 12, "Franklin Gothic Book");
		$hoja->setCellValue("AD".$flag, "ULTRAMARINOS");
		$this->cellStyle("AH".$flag, "92D051", "000000", TRUE, 12, "Franklin Gothic Book");
		$hoja->setCellValue("AH".$flag, "TRINCHERAS");
		$this->cellStyle("AL".$flag, "B1A0C7", "000000", TRUE, 12, "Franklin Gothic Book");
		$hoja->setCellValue("AL".$flag, "AZT MERCADO");
		$this->cellStyle("AP".$flag, "DA9694", "000000", TRUE, 12, "Franklin Gothic Book");
		$hoja->setCellValue("AP".$flag, "TENENCIA");
		$this->cellStyle("AT".$flag, "4CACC6", "000000", TRUE, 12, "Franklin Gothic Book");
		$hoja->setCellValue("AT".$flag, "TIJERAS");
		$this->cellStyle("AX".$flag, "FF0066", "000000", TRUE, 12, "Franklin Gothic Book");
		$hoja->setCellValue("AX".$flag, "SUPER INDUSTRIAL");
		$this->cellStyle("A3:AZ4", "000000", "FFFFFF", TRUE, 12, "Franklin Gothic Book");
		$this->excelfile->getActiveSheet()->getStyle('A'.$flag.':AZ'.$flag)->applyFromArray($styleArray);
		$flag++;
		$this->cellStyle("A".$flag.":BA".$flag."", "000000", "FFFFFF", TRUE, 12, "Franklin Gothic Book");
		$hoja->setCellValue("B".$flag, "DESCRIPCIÓN");
		$hoja->mergeCells('K'.$flag.':O'.$flag);
		$hoja->setCellValue("K".$flag, "EXISTENCIAS");
		$hoja->mergeCells('P'.$flag.':T'.$flag);
		$hoja->setCellValue("P".$flag, "EXISTENCIAS");
		$hoja->mergeCells('U'.$flag.':Y'.$flag);
		$hoja->setCellValue("U".$flag, "EXISTENCIAS");
		$hoja->mergeCells('Z'.$flag.':AC'.$flag);
		$hoja->setCellValue("Z".$flag, "EXISTENCIAS");
		$hoja->mergeCells('AD'.$flag.':AG'.$flag);
		$hoja->setCellValue("AD".$flag, "EXISTENCIAS");
		$hoja->mergeCells('AH'.$flag.':AK'.$flag);
		$hoja->setCellValue("AH".$flag, "EXISTENCIAS");
		$hoja->mergeCells('AL'.$flag.':AO'.$flag);
		$hoja->setCellValue("AL".$flag, "EXISTENCIAS");
		$hoja->mergeCells('AP'.$flag.':AS'.$flag);
		$hoja->setCellValue("AP".$flag, "EXISTENCIAS");
		$hoja->mergeCells('AT'.$flag.':AW'.$flag);
		$hoja->setCellValue("AT".$flag, "EXISTENCIAS");
		$hoja->mergeCells('AX'.$flag.':AZ'.$flag);
		$hoja->setCellValue("AX".$flag, "EXISTENCIAS");
		$flag++;
		$this->cellStyle("A".$flag.":BA".$flag."", "000000", "FFFFFF", TRUE, 12, "Franklin Gothic Book");
		$hoja->setCellValue("A".$flag, "CODIGO");
		$hoja->setCellValue("B".$flag, "FACTURA");
		$hoja->setCellValue("D".$flag, "COSTO");
		$hoja->setCellValue("F".$flag, "SISTEMA");
		$hoja->setCellValue("H".$flag, "PRECIO4");
		$hoja->setCellValue("I".$flag, "2DO");
		$hoja->setCellValue("J".$flag, "PROVEEDOR");
		$hoja->setCellValue("K".$flag, "CAJAS");
		$hoja->setCellValue("L".$flag, "PZAS");
		$hoja->setCellValue("M".$flag, "STOCK");
		$hoja->setCellValue("N".$flag, "PEND");
		$hoja->setCellValue("O".$flag, "PEDIDO");
		$hoja->setCellValue("P".$flag, "CAJAS");
		$hoja->setCellValue("Q".$flag, "PZAS");
		$hoja->setCellValue("R".$flag, "STOCK");
		$hoja->setCellValue("S".$flag, "PEND");
		$hoja->setCellValue("T".$flag, "PEDIDO");
		$hoja->setCellValue("U".$flag, "CAJAS");
		$hoja->setCellValue("V".$flag, "PZAS");
		$hoja->setCellValue("W".$flag, "STOCK");
		$hoja->setCellValue("X".$flag, "PEND");
		$hoja->setCellValue("Y".$flag, "PEDIDO");
		$hoja->setCellValue("Z".$flag, "CAJAS");
		$hoja->setCellValue("AA".$flag, "PZAS");
		$hoja->setCellValue("AB".$flag, "PEND");
		$hoja->setCellValue("AC".$flag, "PEDIDO");
		$hoja->setCellValue("AD".$flag, "CAJAS");
		$hoja->setCellValue("AE".$flag, "PZAS");
		$hoja->setCellValue("AF".$flag, "PEND");
		$hoja->setCellValue("AG".$flag, "PEDIDO");
		$hoja->setCellValue("AH".$flag, "CAJAS");
		$hoja->setCellValue("AI".$flag, "PZAS");
		$hoja->setCellValue("AJ".$flag, "PEND");
		$hoja->setCellValue("AK".$flag, "PEDIDO");
		$hoja->setCellValue("AL".$flag, "CAJAS");
		$hoja->setCellValue("AM".$flag, "PZAS");
		$hoja->setCellValue("AN".$flag, "PEND");
		$hoja->setCellValue("AO".$flag, "PEDIDO");
		$hoja->setCellValue("AP".$flag, "CAJAS");
		$hoja->setCellValue("AQ".$flag, "PZAS");
		$hoja->setCellValue("AR".$flag, "PEND");
		$hoja->setCellValue("AS".$flag, "PEDIDO");
		$hoja->setCellValue("AT".$flag, "CAJAS");
		$hoja->setCellValue("AU".$flag, "PZAS");
		$hoja->setCellValue("AV".$flag, "PEND");
		$hoja->setCellValue("AW".$flag, "PEDIDO");
		$hoja->setCellValue("AX".$flag, "CAJAS");
		$hoja->setCellValue("AY".$flag, "PZAS");
		$hoja->setCellValue("AZ".$flag, "PEDIDO");
		$hoja->setCellValue("BA".$flag, "PROMOCION");
		$hoja->setCellValue("BK".$flag, "TOTAL");
		$this->cellStyle("BB".$flag, "C00000", "000000", TRUE, 12, "Franklin Gothic Book");
		$this->cellStyle("BC".$flag, "01B0F0", "000000", TRUE, 12, "Franklin Gothic Book");
		$this->cellStyle("BD".$flag, "FF0000", "000000", TRUE, 12, "Franklin Gothic Book");
		$this->cellStyle("BE".$flag, "E26C0B", "000000", TRUE, 12, "Franklin Gothic Book");
		$this->cellStyle("BF".$flag, "C5C5C5", "000000", TRUE, 12, "Franklin Gothic Book");
		$this->cellStyle("BG".$flag, "92D051", "000000", TRUE, 12, "Franklin Gothic Book");
		$this->cellStyle("BH".$flag, "B1A0C7", "000000", TRUE, 12, "Franklin Gothic Book");
		$this->cellStyle("BI".$flag, "DA9694", "000000", TRUE, 12, "Franklin Gothic Book");
		$this->cellStyle("BJ".$flag, "4CACC6", "000000", TRUE, 12, "Franklin Gothic Book");
		$this->cellStyle("BK".$flag."", "000000", "FFFFFF", TRUE, 12, "Franklin Gothic Book");
		$this->excelfile->getActiveSheet()->getStyle('BA'.$flag)->applyFromArray($styleArray);
		$fecha = new DateTime(date('Y-m-d H:i:s'));
		$where=["ctz_first.id_proveedor" => 3,"prod.estatus" => 1];//Semana actual
		$intervalo = new DateInterval('P3D');
		$fecha->add($intervalo);
		$cotizacionesProveedor = $this->ct_mdl->getPedidosAll($where, $fecha->format('Y-m-d H:i:s'), 0);
		$flag1 = 5;
		if($cotizacionesProveedor){
			foreach ($cotizacionesProveedor as $key => $value) {
				$this->excelfile->setActiveSheetIndex(0);
				$this->cellStyle("E".$flag1, "000000", "FFFFFF", FALSE, 12, "Franklin Gothic Book");
				$hoja1->setCellValue("E".$flag1, $value['familia']);
				$flag1 +=1;
				//Pedidos
				$this->excelfile->setActiveSheetIndex(1);
				$this->cellStyle("C".$flag, "000000", "FFFFFF", FALSE, 12, "Franklin Gothic Book");
				$hoja->setCellValue("C{$flag}", $value['familia']);
				$flag +=1;
				if ($value['articulos']) {
					foreach ($value['articulos'] as $key => $row){
						$registrazo = date('Y-m-d',strtotime($row['registrazo']));
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
				         
		                 if($this->weekNumber($registrazo) == ($this->weekNumber() -1 ) || $this->weekNumber($registrazo) == ($this->weekNumber())){
							$this->cellStyle("A{$flag1}", "FF7F71", "000000", FALSE, 12, "Franklin Gothic Book");
							$this->cellStyle("B{$flag1}", "FF7F71", "000000", FALSE, 12, "Franklin Gothic Book");
							$this->cellStyle("C{$flag1}", "FF7F71", "000000", FALSE, 12, "Franklin Gothic Book");
							$this->cellStyle("E{$flag1}", "FF7F71", "000000", FALSE, 12, "Franklin Gothic Book");

						}
						//Pedidos
						$this->excelfile->setActiveSheetIndex(1);
						$this->cellStyle("A".$flag.":BA".$flag."", "FFFFFF", "000000", FALSE, 12, "Franklin Gothic Book");
						
						$hoja->setCellValue("A{$flag}", $row['codigo'])->getStyle("A{$flag}")->getNumberFormat()->setFormatCode('# ???/???');//Formato de fraccion
						$hoja->setCellValue("B{$flag}", $row['codigo_factura'])->getStyle("B{$flag}")->getNumberFormat()->setFormatCode('# ???/???');//Formato de fraccion
						if($row['color'] == '#92CEE3'){
							$this->cellStyle("A{$flag}", "92CEE3", "000000", FALSE, 12, "Franklin Gothic Book");
						}else{
							$this->cellStyle("A{$flag}", "FFFFFF", "000000", FALSE, 12, "Franklin Gothic Book");
						}
						$hoja->setCellValue("C{$flag}", $row['producto']);
						
						

						
						if (number_format(($row['precio_sistema'] - $row['precio_first']),2) === "0.01" || number_format(($row['precio_sistema'] - $row['precio_first']),2) === "-0.01") {
							$hoja->setCellValue("D{$flag}", $row['precio_first'])->getStyle("D{$flag}")->getNumberFormat()->setFormatCode('"$"#,##0.00_-');
							$this->cellStyle("D{$flag}", "FFFFFF", "000000", FALSE, 12, "Franklin Gothic Book");
							$this->cellStyle("D{$flag}", "FFFFFF", "000000", FALSE, 12, "Franklin Gothic Book");
							$this->cellStyle("C{$flag}", "FFFFFF", "000000", FALSE, 12, "Franklin Gothic Book");
						}elseif($row['precio_sistema'] < $row['precio_first']){
							$hoja->setCellValue("D{$flag}", $row['precio_first'])->getStyle("D{$flag}")->getNumberFormat()->setFormatCode('"$"#,##0.00_-');
							$this->cellStyle("D{$flag}", "FDB2B2", "E21111", FALSE, 12, "Franklin Gothic Book");
							$this->cellStyle("D{$flag}", "FDB2B2", "E21111", FALSE, 12, "Franklin Gothic Book");
							$this->cellStyle("C{$flag}", "E21600", "000000", FALSE, 12, "Franklin Gothic Book");
						}else{
							$hoja->setCellValue("D{$flag}", $row['precio_first'])->getStyle("D{$flag}")->getNumberFormat()->setFormatCode('"$"#,##0.00_-');
							$this->cellStyle("D{$flag}", "96EAA8", "0C800C", FALSE, 12, "Franklin Gothic Book");
							$this->cellStyle("C{$flag}", "249947", "000000", FALSE, 12, "Franklin Gothic Book");
						}

						$hoja->setCellValue("F{$flag}", $row['precio_sistema'])->getStyle("F{$flag}")->getNumberFormat()->setFormatCode('"$"#,##0.00_-');//Formto de moneda
						$this->cellStyle("F".$flag, "FFFFFF","000000",  FALSE, 12, "Franklin Gothic Book");
						if($row['colorp'] == 1){
							$this->cellStyle("F{$flag}", "D6DCE4", "000000", FALSE, 12, "Franklin Gothic Book");
						}else{
							$this->cellStyle("F{$flag}", "FFFFFF", "000000", FALSE, 12, "Franklin Gothic Book");
						}

						$hoja->setCellValue("H{$flag}", $row['precio_four'])->getStyle("H{$flag}")->getNumberFormat()->setFormatCode('"$"#,##0.00_-');
						$this->cellStyle("H{$flag}", "FFFFFF", "000000", FALSE, 12, "Franklin Gothic Book");
						if($row['precio_sistema'] < $row['precio_next']){
							$hoja->setCellValue("I{$flag}", $row['precio_next'])->getStyle("I{$flag}")->getNumberFormat()->setFormatCode('"$"#,##0.00_-');
							$this->cellStyle("I{$flag}", "FDB2B2", "E21111", FALSE, 12, "Franklin Gothic Book");
						}else if($row['precio_next'] !== NULL){
							$hoja->setCellValue("I{$flag}", $row['precio_next'])->getStyle("I{$flag}")->getNumberFormat()->setFormatCode('"$"#,##0.00_-');
							$this->cellStyle("I{$flag}", "96EAA8", "0C800C", FALSE, 12, "Franklin Gothic Book");
						}else{
							$hoja->setCellValue("I{$flag}", $row['precio_next'])->getStyle("I{$flag}")->getNumberFormat()->setFormatCode('"$"#,##0.00_-');
							$this->cellStyle("I{$flag}", "FFFFFF", "000000", FALSE, 12, "Franklin Gothic Book");
						}
						$hoja->setCellValue("J{$flag}", $row['proveedor_next']);
						$this->cellStyle("K".$flag.":BA".$flag, "FFFFFF", "000000", TRUE, 12, "Franklin Gothic Book");
						$this->cellStyle("J".$flag, "FFFFFF", "000000", FALSE, 12, "Franklin Gothic Book");

						$hoja->setCellValue("K{$flag}", $row['caja0']);
						$hoja->setCellValue("L{$flag}", $row['pz0']);
						$hoja->setCellValue("N{$flag}", $row['cedis']);

						$hoja->setCellValue("O{$flag}", $row['ped0']);
						$this->cellStyle("O{$flag}", "D4EAEF", "000000", TRUE, 12, "Franklin Gothic Book");

						$hoja->setCellValue("P{$flag}", $row['caja1']);
						$hoja->setCellValue("Q{$flag}", $row['pz1']);
						$hoja->setCellValue("R{$flag}", $row['stocant']);
						$hoja->setCellValue("S{$flag}", $row['abarrotes']);
						$hoja->setCellValue("T{$flag}", $row['ped1']);
						$this->cellStyle("T{$flag}", "D4EAEF", "000000", TRUE, 12, "Franklin Gothic Book");

						$hoja->setCellValue("U{$flag}", $row['caja2']);
						$hoja->setCellValue("V{$flag}", $row['pz2']);
						$hoja->setCellValue("X{$flag}", $row['pedregal']);

						$hoja->setCellValue("Y{$flag}", $row['ped2']);
						$this->cellStyle("Y{$flag}", "D4EAEF", "000000", TRUE, 12, "Franklin Gothic Book");

						$hoja->setCellValue("Z{$flag}", $row['caja3']);
						$hoja->setCellValue("AA{$flag}", $row['pz3']);
						$hoja->setCellValue("AB{$flag}", $row['tienda']);
						$hoja->setCellValue("AC{$flag}", $row['ped3']);
						$this->cellStyle("AC{$flag}", "D4EAEF", "000000", TRUE, 12, "Franklin Gothic Book");

						$hoja->setCellValue("AD{$flag}", $row['caja4']);
						$hoja->setCellValue("AE{$flag}", $row['pz4']);
						$hoja->setCellValue("AF{$flag}", $row['ultra']);
						$hoja->setCellValue("AG{$flag}", $row['ped4']);
						$this->cellStyle("AG{$flag}", "D4EAEF", "000000", TRUE, 12, "Franklin Gothic Book");

						$hoja->setCellValue("AH{$flag}", $row['caja5']);
						$hoja->setCellValue("AI{$flag}", $row['pz5']);
						$hoja->setCellValue("AJ{$flag}", $row['trincheras']);
						$hoja->setCellValue("AK{$flag}", $row['ped5']);
						$this->cellStyle("AK{$flag}", "D4EAEF", "000000", TRUE, 12, "Franklin Gothic Book");

						$hoja->setCellValue("AL{$flag}", $row['caja6']);
						$hoja->setCellValue("AM{$flag}", $row['pz6']);
						$hoja->setCellValue("AN{$flag}", $row['mercado']);
						$hoja->setCellValue("AO{$flag}", $row['ped6']);
						$this->cellStyle("AO{$flag}", "D4EAEF", "000000", TRUE, 12, "Franklin Gothic Book");

						$hoja->setCellValue("AP{$flag}", $row['caja7']);
						$hoja->setCellValue("AQ{$flag}", $row['pz7']);
						$hoja->setCellValue("AR{$flag}", $row['tenencia']);
						$hoja->setCellValue("AS{$flag}", $row['ped7']);
						$this->cellStyle("AS{$flag}", "D4EAEF", "000000", TRUE, 12, "Franklin Gothic Book");

						$hoja->setCellValue("AT{$flag}", $row['caja8']);
						$hoja->setCellValue("AU{$flag}", $row['pz8']);
						$hoja->setCellValue("AV{$flag}", $row['tijeras']);
						$hoja->setCellValue("AW{$flag}", $row['ped8']);
						$this->cellStyle("AW{$flag}", "D4EAEF", "000000", TRUE, 12, "Franklin Gothic Book");

						$hoja->setCellValue("AX{$flag}", $row['caja9']);
						$hoja->setCellValue("AY{$flag}", $row['pz9']);
						$hoja->setCellValue("AZ{$flag}", $row['ped9']);
						$this->cellStyle("AZ{$flag}", "D4EAEF", "000000", TRUE, 12, "Franklin Gothic Book");

						$hoja->setCellValue("BA{$flag}", $row['promocion_first']);
						$hoja->setCellValue("BB{$flag}", "=D".$flag."*O".$flag)->getStyle("BB{$flag}")->getNumberFormat()->setFormatCode('"$"#,##0.00_-');
						$hoja->setCellValue("BC{$flag}", "=D".$flag."*T".$flag)->getStyle("BC{$flag}")->getNumberFormat()->setFormatCode('"$"#,##0.00_-');
						$hoja->setCellValue("BD{$flag}", "=D".$flag."*Y".$flag)->getStyle("BD{$flag}")->getNumberFormat()->setFormatCode('"$"#,##0.00_-');
						$hoja->setCellValue("BE{$flag}", "=D".$flag."*AC".$flag)->getStyle("BE{$flag}")->getNumberFormat()->setFormatCode('"$"#,##0.00_-');
						$hoja->setCellValue("BF{$flag}", "=D".$flag."*AG".$flag)->getStyle("BF{$flag}")->getNumberFormat()->setFormatCode('"$"#,##0.00_-');
						$hoja->setCellValue("BG{$flag}", "=D".$flag."*AK".$flag)->getStyle("BG{$flag}")->getNumberFormat()->setFormatCode('"$"#,##0.00_-');
						$hoja->setCellValue("BH{$flag}", "=D".$flag."*AO".$flag)->getStyle("BH{$flag}")->getNumberFormat()->setFormatCode('"$"#,##0.00_-');
						$hoja->setCellValue("BI{$flag}", "=D".$flag."*AS".$flag)->getStyle("BI{$flag}")->getNumberFormat()->setFormatCode('"$"#,##0.00_-');
						$hoja->setCellValue("BJ{$flag}", "=D".$flag."*AW".$flag)->getStyle("BJ{$flag}")->getNumberFormat()->setFormatCode('"$"#,##0.00_-');
						$this->cellStyle("BK{$flag}", "D4EAEF", "000000", FALSE, 12, "Franklin Gothic Book");
						$hoja->setCellValue("BK{$flag}", "=SUM(BB".$flag.":BJ".$flag.")")->getStyle("BK{$flag}")->getNumberFormat()->setFormatCode('"$"#,##0.00_-');
						
						$border_style= array('borders' => array('right' => array('style' =>
							PHPExcel_Style_Border::BORDER_THIN,'color' => array('argb' => '000000'),)));
						$this->excelfile->setActiveSheetIndex(1);
						$this->excelfile->getActiveSheet()->getStyle('A'.$flag.':BA'.$flag)->applyFromArray($styleArray);
						$this->excelfile->getActiveSheet()->getStyle('BK'.$flag)->applyFromArray($styleArray);
						$this->excelfile->setActiveSheetIndex(0);
						$this->excelfile->getActiveSheet()->getStyle('A'.$flag1.':E'.$flag1)->applyFromArray($styleArray);
						$hoja->getStyle("A{$flag}:J{$flag}")
				                 ->getAlignment()
				                 ->setHorizontal(\PHPExcel_Style_Alignment::HORIZONTAL_LEFT);

				   if($this->weekNumber($registrazo) == ($this->weekNumber() - 1) || $this->weekNumber($registrazo) == ($this->weekNumber())){
							$this->cellStyle("A{$flag}", "FF7F71", "000000", FALSE, 12, "Franklin Gothic Book");
							$this->cellStyle("B{$flag}", "FF7F71", "000000", FALSE, 12, "Franklin Gothic Book");
						}
						if($row['precio_sistema'] == 0){
							$row['precio_sistema'] = 1;
						}
						if($row['precio_four'] == 0){
							$row['precio_four'] = 1;
						}

						$hoja->setCellValue("E{$flag}",100 - ($row['precio_first'] * 100 / $row['precio_sistema']))->getStyle("E{$flag}")->getNumberFormat()->setFormatCode('"%"#,##0.00_-');;
						$this->cellStyle("E".$flag, "FF9999", "000000", FALSE, 10, "Franklin Gothic Book");

						$hoja->setCellValue("G{$flag}", 100 - ($row['precio_sistema'] * 100 / $row['precio_four']))->getStyle("G{$flag}")->getNumberFormat()->setFormatCode('"%"#,##0.00_-');;
						$this->cellStyle("G".$flag, "FF9999", "000000", FALSE, 10, "Franklin Gothic Book");
						$flag ++;
						$flag1 ++;
					}
				}
			}
		}
		$flans = $flag - 1;
		$hoja->setCellValue("BB{$flag}", "=SUM(BB5:BB{$flans})")->getStyle("BB{$flag}")->getNumberFormat()->setFormatCode('"$"#,##0.00_-');
		$hoja->setCellValue("BC{$flag}", "=SUM(BC5:BC{$flans})")->getStyle("BC{$flag}")->getNumberFormat()->setFormatCode('"$"#,##0.00_-');
		$hoja->setCellValue("BD{$flag}", "=SUM(BD5:BD{$flans})")->getStyle("BD{$flag}")->getNumberFormat()->setFormatCode('"$"#,##0.00_-');
		$hoja->setCellValue("BE{$flag}", "=SUM(BE5:BE{$flans})")->getStyle("BE{$flag}")->getNumberFormat()->setFormatCode('"$"#,##0.00_-');
		$hoja->setCellValue("BF{$flag}", "=SUM(BF5:BF{$flans})")->getStyle("BF{$flag}")->getNumberFormat()->setFormatCode('"$"#,##0.00_-');
		$hoja->setCellValue("BG{$flag}", "=SUM(BG5:BG{$flans})")->getStyle("BG{$flag}")->getNumberFormat()->setFormatCode('"$"#,##0.00_-');
		$hoja->setCellValue("BH{$flag}", "=SUM(BH5:BH{$flans})")->getStyle("BH{$flag}")->getNumberFormat()->setFormatCode('"$"#,##0.00_-');
		$hoja->setCellValue("BI{$flag}", "=SUM(BI5:BI{$flans})")->getStyle("BI{$flag}")->getNumberFormat()->setFormatCode('"$"#,##0.00_-');
		$hoja->setCellValue("BJ{$flag}", "=SUM(BJ5:BJ{$flans})")->getStyle("BJ{$flag}")->getNumberFormat()->setFormatCode('"$"#,##0.00_-');
		$hoja->setCellValue("BK{$flag}", "=SUM(BK5:BK{$flans})")->getStyle("BK{$flag}")->getNumberFormat()->setFormatCode('"$"#,##0.00_-');
		$flans = $flag;
		$flag += 4;
		$this->cellStyle("B".$flag, "66FFFB", "000000", TRUE, 12, "Franklin Gothic Book");
		$hoja->setCellValue("B".$flag, "CEDIS");
		$hoja->setCellValue("C{$flag}", "=(BB{$flans})")->getStyle("C{$flag}")->getNumberFormat()->setFormatCode('"$"#,##0.00_-');
		$flag++;
		$this->cellStyle("B".$flag, "01B0F0", "000000", TRUE, 12, "Franklin Gothic Book");
		$hoja->setCellValue("B".$flag, "ABARROTES");
		$hoja->setCellValue("C{$flag}", "=(BC{$flans})")->getStyle("C{$flag}")->getNumberFormat()->setFormatCode('"$"#,##0.00_-');
		$flag++;
		$this->cellStyle("B".$flag, "FF0000", "000000", TRUE, 12, "Franklin Gothic Book");
		$hoja->setCellValue("B".$flag, "V. PEDREGAL");
		$hoja->setCellValue("C{$flag}", "=(BD{$flans})")->getStyle("C{$flag}")->getNumberFormat()->setFormatCode('"$"#,##0.00_-');
		$flag++;
		$this->cellStyle("B".$flag, "E26C0B", "000000", TRUE, 12, "Franklin Gothic Book");
		$hoja->setCellValue("B".$flag, "TIENDA");
		$hoja->setCellValue("C{$flag}", "=(BE{$flans})")->getStyle("C{$flag}")->getNumberFormat()->setFormatCode('"$"#,##0.00_-');
		$flag++;
		$this->cellStyle("B".$flag, "C5C5C5", "000000", TRUE, 12, "Franklin Gothic Book");
		$hoja->setCellValue("B".$flag, "ULTRAMARINOS");
		$hoja->setCellValue("C{$flag}", "=(BF{$flans})")->getStyle("C{$flag}")->getNumberFormat()->setFormatCode('"$"#,##0.00_-');
		$flag++;
		$this->cellStyle("B".$flag, "92D051", "000000", TRUE, 12, "Franklin Gothic Book");
		$hoja->setCellValue("B".$flag, "TRINCHERAS");
		$hoja->setCellValue("C{$flag}", "=(BG{$flans})")->getStyle("C{$flag}")->getNumberFormat()->setFormatCode('"$"#,##0.00_-');
		$flag++;
		$this->cellStyle("B".$flag, "B1A0C7", "000000", TRUE, 12, "Franklin Gothic Book");
		$hoja->setCellValue("B".$flag, "AZT MERCADO");
		$hoja->setCellValue("C{$flag}", "=(BH{$flans})")->getStyle("C{$flag}")->getNumberFormat()->setFormatCode('"$"#,##0.00_-');
		$flag++;
		$this->cellStyle("B".$flag, "DA9694", "000000", TRUE, 12, "Franklin Gothic Book");
		$hoja->setCellValue("B".$flag, "TENENCIA");
		$hoja->setCellValue("C{$flag}", "=(BI{$flans})")->getStyle("C{$flag}")->getNumberFormat()->setFormatCode('"$"#,##0.00_-');
		$flag++;
		$this->cellStyle("B".$flag, "4CACC6", "000000", TRUE, 12, "Franklin Gothic Book");
		$hoja->setCellValue("B".$flag, "TIJERAS");
		$hoja->setCellValue("C{$flag}", "=(BJ{$flans})")->getStyle("C{$flag}")->getNumberFormat()->setFormatCode('"$"#,##0.00_-');
		$flag++;
		$this->cellStyle("B".$flag, "000000", "FFFFFF", TRUE, 12, "Franklin Gothic Book");
		$hoja->setCellValue("B".$flag, "TOTAL");
		$hoja->setCellValue("C{$flag}", "=(BK{$flans})")->getStyle("C{$flag}")->getNumberFormat()->setFormatCode('"$"#,##0.00_-');
		$flag++;

		$dias = array("DOMINGO","LUNES","MARTES","MIÉRCOLES","JUEVES","VIERNES","SÁBADO");
		$meses = array("ENERO","FEBRERO","MARZO","ABRIL","MAYO","JUNIO","JULIO","AGOSTO","SEPTIEMBRE","OCTUBRE","NOVIEMBRE","DICIEMBRE");
		$fecha =  $dias[date('w')]." ".date('d')." DE ".$meses[date('n')-1]. " DEL ".date('Y') ;
		$file_name = "FORMATO DUERO ".$fecha.".xlsx"; //Nombre del documento con extención
		$excel_Writer = PHPExcel_IOFactory::createWriter($this->excelfile, "Excel2007");
		header("Content-Type: application/vnd.ms-excel; charset=utf-8");
		header("Content-Disposition: attachment;filename=".$file_name);
		header("Cache-Control: max-age=0");
		$excel_Writer->save("php://output");

	}
}
/* End of file Cotizaciones.php */
/* Location: ./application/controllers/Cotizaciones.php */
