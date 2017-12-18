<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Pedidos extends MY_Controller {

	function __construct(){
		parent::__construct();
		$this->load->model("Pedidos_model", "ped_mdl");
		$this->load->model("Pedidos_model", "ped_mdl");
		$this->load->model("Proveedores_model", "pro_mdl");
	}

	public function pedidos_view(){
		$user = $this->ion_auth->user()->row();//Obtenemos el usuario logeado 
		$where = [];

		if(! $this->ion_auth->is_admin()){//Solo mostrar sus Productos cuando es proveedor
			$where = ["promociones.id_proveedor" => $user->id];
		}
		$data["pedidos"] = $this->ped_mdl->getPedidos($where);
		$this->load->view("Pedidos/table_pedidos", $data, FALSE);
	}

	public function add_pedido(){
		$data["title"]="Registrar pedidos";
		$this->load->view("Structure/header_modal", $data);
		$data["proveedores"] = $this->pro_mdl->getProveedores();
		$this->load->view("Pedidos/new_pedido", $data);
		$this->load->view("Structure/footer_modal_save");
	}

	public function update_pedido($id){
		$data["title"]="Actualizar datos del pedido";
		$this->load->view("Structure/header_modal", $data);
		$data["pedido"] = $this->ped_mdl->get(NULL, ['id_pedido'=>$id])[0];
		$this->load->view("Pedidos/edit_pedido", $data);
		$this->load->view("Structure/footer_modal_edit");
	}

	public function delete_pedido($id){
		$data["title"]="Pedido a eliminar";
		$this->load->view("Structure/header_modal", $data);
		$data["pedido"] = $this->ped_mdl->get(NULL, ['id_pedido'=>$id])[0];
		$this->load->view("Pedidos/delete_pedido", $data);
		$this->load->view("Structure/footer_modal_delete");
	}

	public function accion($param){
		$pedido = [];

		switch ($param) {
			case (substr($param, 0, 1) === 'I'):
				// $data ['id_pedido']=$this->ped_mdl->insert($pedido);
				$mensaje = [
					"id" 	=> 'Éxito',
					"desc"	=> 'Pedido registrado correctamente',
					"type"	=> 'success'
				];
				break;

			case (substr($param, 0, 1) === 'U'):
				// $data ['id_pedido'] = $this->ped_mdl->update($pedido, $this->input->post('id_pedido'));
				$mensaje = [
					"id" 	=> 'Éxito',
					"desc"	=> 'Pedido actualizado correctamente',
					"type"	=> 'success'
				];
				break;

			default:
				// $data ['id_pedido'] = $this->ped_mdl->update(["estatus" => 0], $this->input->post('id_pedido'));
				$mensaje = [
					"id" 	=> 'Éxito',
					"desc"	=> 'Pedido eliminado correctamente',
					"type"	=> 'success'
				];
				break;
		}
		$this->jsonResponse($mensaje);
	}

}

/* End of file Pedidos.php */
/* Location: ./application/controllers/Pedidos.php */