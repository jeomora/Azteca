<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Pedidos extends MY_Controller {

	function __construct(){
		parent::__construct();
		$this->load->model("Pedidos_model", "ped_mdl");
		$this->load->model("Detalles_pedidos_model", "det_ped_mdl");
		$this->load->model("Sucursales_model", "suc_mdl");
		$this->load->model("Proveedores_model", "pro_mdl");
		$this->load->model("Productos_proveedor_model", "pro_prov_mdl");
	}

	public function index(){
		$user = $this->ion_auth->user()->row();//Obtenemos el usuario logeado 
		$where = [];

		if(! $this->ion_auth->is_admin()){//Solo mostrar sus Productos cuando es proveedor
			$where = ["promociones.id_proveedor" => $user->id];
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
		$this->estructura("Pedidos/table_pedidos", $data, FALSE);
	}

	public function add_pedido(){
		$data["title"]="REGISTRAR PEDIDOS";
		$data["proveedores"] = $this->pro_mdl->getProveedores();
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
		$data["proveedores"] = $this->pro_mdl->getProveedores();
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
		$data["proveedor"] = $this->pro_mdl->getProveedores(['users.id' => $data['pedido']->id_proveedor])[0];
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
		$where = ["productos_proveedor.id_proveedor" => $id_proveedor];
		$productosProveedor = $this->pro_prov_mdl->productos_proveedor($where);
		$this->jsonResponse($productosProveedor);
	}

	public function save_pedido(){
		$pedido = [
			"id_sucursal"		=>	$this->input->post('id_sucursal'),
			"id_proveedor"		=>	$this->input->post('id_proveedor'),
			"id_user_registra"	=>	$this->ion_auth->user()->row()->id, 
			"fecha_registro"	=>	date("Y-m-d H:i:s"),
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
		if($this->det_ped_mdl->insertm($detalle_pedido) > 0){
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