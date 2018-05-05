<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Pedidos extends MY_Controller {

	function __construct(){
		parent::__construct();
		$this->load->model("Pedidos_model", "ped_mdl");
		$this->load->model("Detalles_pedidos_model", "det_ped_mdl");
		$this->load->model("Sucursales_model", "suc_mdl");
		$this->load->model("Usuarios_model", "user_mdl");
		$this->load->model("Cotizaciones_model", "ct_mdl");
		$this->load->model("Existencias_model", "ex_mdl");
		$this->load->model("Precio_sistema_model", "pre_mdl");
	}

	public function index(){
		$user = $this->session->userdata();//Trae los datos del usuario
		
		$where = [];

		if($user['id_grupo'] ==2){//El grupo 2 es proveedor
			$where = ["promociones.id_proveedor" => $user['id_usuario']];
		}
		$data['links'] = [
			'/assets/css/plugins/dataTables/dataTables.bootstrap',
			'/assets/css/plugins/dataTables/dataTables.responsive',
			'/assets/css/plugins/dataTables/dataTables.tableTools.min',
			'/assets/css/plugins/dataTables/buttons.dataTables.min',
		];

		$data['scripts'] = [
			'/scripts/pedidos',
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
		$data["pedidos"] = $this->ped_mdl->getPedidos($where);
		$data["proveedores"] = $this->user_mdl->getUsuarios();
		$data["conjuntos"] = $this->user_mdl->get(NULL, ["conjunto" => "INDIVIDUAL"]);

		if($user['id_grupo'] == 3){
			$this->estructura("Pedidos/pedido_tienda", $data, FALSE);
		}else{
			$this->estructura("Pedidos/table_pedidos", $data, FALSE);
		}
		
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
		$where=["usuarios.id_grupo" => 3];
		$data["tiendas"] = $this->user_mdl->getUsuarios($where);
		$data["usuar"]  = $this->session->userdata();
		$this->estructura("Pedidos/agregar", $data);
	}

	public function add_pedido(){
		$data["title"]="REGISTRAR PEDIDOS";
		$data["proveedores"] = $this->user_mdl->getUsuarios();
		$data["sucursales"] = $this->suc_mdl->get('id_sucursal, nombre');
		$data["view"]=$this->load->view("Pedidos/new_pedido", $data, TRUE);
		$data["button"]="<button class='btn btn-success new_pedido' type='button'>
							<span class='bold'><i class='fa fa-floppy-o'></i></span> &nbsp;Guardar
						</button>";
		$this->jsonResponse($data);
	}

	public function get_update($id){
		$data["title"]="ACTUALIZAR DATOS DEL PEDIDO";
		$data["pedido"] = $this->ped_mdl->get(NULL, ['id_pedido'=>$id])[0];
		$data["sucursales"] = $this->suc_mdl->get('id_sucursal, nombre');
		$data["proveedores"] = $this->user_mdl->getUsuarios();
		$data["detallePedido"] = $this->det_ped_mdl->getDetallePedido(["detalles_pedidos.id_pedido"=>$data["pedido"]->id_pedido]);
		$data["view"]=$this->load->view("Pedidos/edit_pedido", $data, TRUE);
		$data["button"]="<button class='btn btn-success update_pedido' type='button'>
							<span class='bold'><i class='fa fa-floppy-o'></i></span> &nbsp;Guardar cambios
						</button>";
		$this->jsonResponse($data);
	}

	public function get_delete($id){
		$data["title"]="PEDIDO A ELIMINAR";
		$data["pedido"] = $this->ped_mdl->get(NULL, ['id_pedido'=>$id])[0];
		$data["proveedor"] = $this->user_mdl->getUsuarios(['users.id' => $data['pedido']->id_proveedor])[0];
		$data["view"]=$this->load->view("Pedidos/delete_pedido", $data, TRUE);
		$data["button"]="<button class='btn btn-danger delete_pedido' type='button'>
							<span class='bold'><i class='fa fa-times'></i></span> &nbsp;Aceptar
						</button>";
		$this->jsonResponse($data);
	}

	public function update(){
	
		$mensaje = [
			"id" 	=> 'Éxito',
			"desc"	=> 'Pedido actualizado correctamente',
			"type"	=> 'success'
		];
		$this->jsonResponse($mensaje);
	}

	public function delete(){
		$data ['id_pedido'] = $this->ped_mdl->update(["estatus" => 0], $this->input->post('id_pedido'));
		$mensaje = [
			"id" 	=> 'Éxito',
			"desc"	=> 'Pedido eliminado correctamente',
			"type"	=> 'success'
		];
		$this->jsonResponse($mensaje);
	}
 
	public function get_productos(){
		$id_proveedor = $this->input->post('id_proveedor');
		$where = ["cotizaciones.id_proveedor" => $id_proveedor];
		$productosProveedor = $this->ct_mdl->productos_proveedor($where);
		$this->jsonResponse($productosProveedor);
	}

	public function get_pedidos(){
		$fecha = new DateTime(date('Y-m-d H:i:s'));
		$intervalo = new DateInterval('P2D');
		$fecha->add($intervalo);
		$id_proveedor = $this->input->post('id_proveedor');
		$user = $this->session->userdata();
		if ($id_proveedor == "VOLUMEN") {
			$where = ["prod.estatus" => 2];
		}elseif ($id_proveedor == "AMARILLOS") {
			$where = ["prod.estatus" => 3];
		}else{
			$where = ["ctz_first.id_proveedor" => $id_proveedor,"prod.estatus" => 1];
		}
		
		$fecha = $fecha->format('Y-m-d H:i:s');
		$productosProveedor = $this->ct_mdl->comparaCotizaciones($where,$fecha,$user["id_usuario"]);
		$this->jsonResponse($productosProveedor);
	}

	public function get_allpedidos(){
		$fecha = new DateTime(date('Y-m-d H:i:s'));
		$intervalo = new DateInterval('P2D');
		$fecha->add($intervalo);
		$id_proveedor = $this->input->post('id_proveedor');
		$user = $this->session->userdata();
		if ($id_proveedor == "VOLUMEN") {
			$where = ["prod.estatus" => 2];
		}elseif ($id_proveedor == "AMARILLOS") {
			$where = ["prod.estatus" => 3];
		}else{
			$where = ["ctz_first.id_proveedor" => $id_proveedor,"prod.estatus" => 1];
		}
		
		$fecha = $fecha->format('Y-m-d H:i:s');
		$productosProveedor = $this->ct_mdl->getPedidosAll($where,$fecha,$user["id_usuario"]);
		$this->jsonResponse($productosProveedor);
	}

	public function getConjs(){
		$productosProveedor = $this->user_mdl->get(NULL, ["conjunto" => $this->input->post('id_proveedor')]);
		$this->jsonResponse($productosProveedor);
	}

	public function get_pedidosingle(){
		$fecha = new DateTime(date('Y-m-d H:i:s'));
		$intervalo = new DateInterval('P2D');
		$fecha->add($intervalo);
		$id_proveedor = $this->input->post('id_proveedor');
		$user = $this->session->userdata();
		if ($id_proveedor == "VOLUMEN") {
			$where = ["prod.estatus" => 2];
		}elseif ($id_proveedor == "AMARILLOS") {
			$where = ["prod.estatus" => 3];
		}else{
			$where = ["ctz_first.id_proveedor" => $id_proveedor,"prod.estatus" => 1];
		}
		
		$fecha = $fecha->format('Y-m-d H:i:s');
		$productosProveedor = $this->ct_mdl->getPedidosSingle($where,$fecha,$user["id_usuario"]);
		$this->jsonResponse($productosProveedor);
	}

	public function get_cotizaciones(){
		$fecha = new DateTime(date('Y-m-d H:i:s'));
		$intervalo = new DateInterval('P2D');
		$fecha->add($intervalo);
		$id_proveedor = $this->input->post('id_proveedor');
		$where=["ctz_first.id_proveedor" => $this->input->post('id_proves')];
		$fecha = $fecha->format('Y-m-d H:i:s');
		$productosProveedor = $this->ct_mdl->comparaCotizaciones($where, $fecha,0);
		$this->jsonResponse($productosProveedor);
	}

	public function guardaSistema(){
		$fecha = new DateTime(date('Y-m-d H:i:s'));
		$intervalo = new DateInterval('P2D');
		$fecha->add($intervalo);
		$user = $this->session->userdata();
		$values = json_decode($this->input->post('values'), true);
		$this->jsonResponse($values);
		$ides = $this->ct_mdl->get('id_producto', ['id_cotizacion'=>$values["id_cotizacion"]])[0];
		$sist = [
				"id_producto"=>	$ides->{"id_producto"},
				"precio_sistema"=>	$values["sistema"], 
				"precio_four"=>	$values["cuatro"],
				"fecha_registro"=>$fecha->format('Y-m-d H:i:s')
			];
		$press = $this->pre_mdl->get('id_precio', ['id_producto'=>$ides->{"id_producto"},'WEEKOFYEAR(fecha_registro)'=>$this->weekNumber($fecha->format('Y-m-d H:i:s'))])[0];
		if($press == NULL){
			$respuesta = $this->pre_mdl->insert($sist);
		}else{
			$respuesta = $this->pre_mdl->update($sist,["id_precio" => $press->{'id_precio'}]);
		}
		if($respuesta){
			$mensaje = [
				"id" 	=> 'Éxito',
				"desc"	=> 'Pedido registrado correctamente',
				"type"	=> 'success'
			];
		}else{
			$mensaje = [
				"id" 	=> 'Error',
				"desc"	=> 'No se registro el Pedido',
				"type"	=> $respuesta
			];
		}
		$this->jsonResponse($mensaje);
	}

	public function guardaPedidos(){
		$fecha = new DateTime(date('Y-m-d H:i:s'));
		$intervalo = new DateInterval('P2D');
		$fecha->add($intervalo);
		$user = $this->session->userdata();
		$values = json_decode($this->input->post('values'), true);

		$pedido = [
				"id_producto"=>	$values["id_producto"],
				"id_tienda"=>	$user['id_usuario'], 
				"cajas"=>	$values["cajas"],
				"piezas"=>	$values["piezas"],
				"pedido"=>$values["pedido"],
				"fecha_registro"=>$fecha->format('Y-m-d H:i:s')
			];
		$ides = $this->ex_mdl->get('id_pedido', ['id_producto'=>$values["id_producto"],'WEEKOFYEAR(fecha_registro)'=>$this->weekNumber($fecha->format('Y-m-d H:i:s')), 'id_tienda'=>$user['id_usuario']])[0];
		if($ides == NULL){
			$respuesta = $this->ex_mdl->insert($pedido);
		}else{
			$respuesta = $this->ex_mdl->update($pedido,["id_pedido" => $ides->{'id_pedido'}]);
		}
		if($respuesta){
			$mensaje = [
				"id" 	=> 'Éxito',
				"desc"	=> 'Pedido registrado correctamente',
				"type"	=> 'success'
			];
		}else{
			$mensaje = [
				"id" 	=> 'Error',
				"desc"	=> 'No se registro el Pedido',
				"type"	=> $respuesta
			];
		}
		$this->jsonResponse($mensaje);
	}

	public function save_pedido(){
		$fecha = new DateTime(date('Y-m-d H:i:s'));
		$intervalo = new DateInterval('P2D');
		$fecha->add($intervalo);
		$pedido = [
			"id_sucursal"		=>	$this->input->post('id_sucursal'),
			"id_proveedor"		=>	$this->input->post('id_proveedor'),
			"id_user_registra"	=>	$this->ion_auth->user()->row()->id, 
			"fecha_registro"	=>	$fecha->format('Y-m-d H:i:s'),
			"total"				=>	str_replace(",", "", $this->input->post('total'))
		];
		
		$id_pedido = $this->ped_mdl->insert($pedido);
		
		$size = sizeof($this->input->post('id_producto[]'));
		$productos = $this->input->post('id_producto[]');
		for($i = 0; $i < $size; $i++){
			$detalle_pedido[] = array(
				'id_pedido'		=>	$id_pedido,
				'id_producto'	=>	$productos[$i],
				'cantidad'		=>	str_replace(",", "", $this->input->post('cantidad[]')[$i]),
				'precio'		=>	str_replace(",", "", $this->input->post('precio[]')[$i]),
				'importe'		=>	str_replace(",", "", $this->input->post('importe[]')[$i])
			);
		}
		if($this->det_ped_mdl->insert_batch($detalle_pedido) > 0){
			$mensaje = [
				"id" 	=> 'Éxito',
				"desc"	=> 'Pedido registrado correctamente',
				"type"	=> 'success'
			];
		}else{
			$mensaje = [
				"id" 	=> 'Error',
				"desc"	=> 'No se registro el Pedido',
				"type"	=> 'error'
			];
		}
		$this->jsonResponse($mensaje);
	}

	public function get_detalle($id){
		$data["title"]="DETALLE DEL PEDIDO";
		$data["pedido"] = $this->ped_mdl->get(NULL, ['id_pedido'=>$id])[0];
		$data["detallePedido"] = $this->det_ped_mdl->getDetallePedido(["detalles_pedidos.id_pedido"=>$data["pedido"]->id_pedido]);
		$data["view"]=$this->load->view("Pedidos/detalle_pedido", $data, TRUE);
		$data["button"]="";
		$this->jsonResponse($data);
	}

}

/* End of file Pedidos.php */
/* Location: ./application/controllers/Pedidos.php */