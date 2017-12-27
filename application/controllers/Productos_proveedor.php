<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Productos_proveedor extends MY_Controller {

	function __construct(){
		parent::__construct();
		$this->load->model("Productos_model", "prod_mdl");
		$this->load->model("Productos_proveedor_model", "prod_prov_mdl");
	}

	public function productos_proveedor_view(){
		$user = $this->ion_auth->user()->row();//Obtenemos el usuario logeado 
		$where = [];
		
		if(! $this->ion_auth->is_admin()){//Solo mostrar sus Productos cuando es proveedor
			$where = ["productos_proveedor.id_proveedor" => $user->id];
		}
		$data["productosProveedor"] = $this->prod_prov_mdl->getProductos_proveedor($where);
		$this->load->view("Productos_proveedor/table_productos_proveedor", $data, FALSE);
	}

	public function add_asignacion(){
		$data["title"]="Registrar cotizaciones";
		$this->load->view("Structure/header_modal", $data);
		$data["productos"] = $this->prod_mdl->get('id_producto, nombre');
		$this->load->view("Productos_proveedor/new_asignacion", $data);
		$this->load->view("Structure/footer_modal_save");
	}

	public function save_asignados(){
		$size = sizeof($this->input->post('id_producto[]'));
		$productos = $this->input->post('id_producto[]');
		for($i = 0; $i < $size; $i++){
			$new_asignados[]= array(
				'id_proveedor'		=>	$this->input->post('id_proveedor'),
				'id_producto'		=>	$productos[$i],
				'fecha_registro'	=>	date('Y-m-d H:i:s'),
				'precio'			=>	str_replace(",", "", $this->input->post('precio[]')[$i]),
				'descuento'			=>	($this->input->post('descuento[]')[$i] > 0) ? $this->input->post('descuento[]')[$i] : NULL,
				'total_descuento'	=>	($this->input->post('descuento[]')[$i] > 0) ? str_replace(',', '', $this->input->post('total')[$i]) : str_replace(",", "", $this->input->post('precio[]')[$i])
			);
		}
		if($this->prod_prov_mdl->insertm($new_asignados) > 0){
			$mensaje = [
				"id" 	=> 'Éxito',
				"desc"	=> 'Productos registrados correctamente',
				"type"	=> 'success'
			];
		}else{
			$mensaje = [
				"id" 	=> 'Error',
				"desc"	=> 'No se agregaron los productos cotizados',
				"type"	=> 'error'
			];
		}
		$this->jsonResponse($mensaje);
	}

	public function get_update($id){
		$data["title"]="Actualizar cotización";
		$this->load->view("Structure/header_modal", $data);
		$data["prod_prov"] = $this->prod_prov_mdl->get(NULL, ['id_producto_proveedor'=>$id])[0];
		$data["producto"] = $this->prod_mdl->get(NULL, ['id_producto'=>$data["prod_prov"]->id_producto])[0];
		$this->load->view("Productos_proveedor/edit_asignacion", $data);
		$this->load->view("Structure/footer_modal_edit");
	}

	public function get_delete($id){
		$data["title"]="Cotización a eliminar";
		$this->load->view("Structure/header_modal", $data);
		$data["prod_prov"] = $this->prod_prov_mdl->get(NULL, ['id_producto_proveedor'=>$id])[0];
		$data["producto"] = $this->prod_mdl->get(NULL, ['id_producto'=>$data["prod_prov"]->id_producto])[0];
		$this->load->view("Productos_proveedor/delete_asignacion", $data);
		$this->load->view("Structure/footer_modal_delete");
	}

	public function accion($param){
		$prod_prov = [
			'fecha_modificado'	=> date('Y-m-d H:i:s'),
			'precio'			=>	str_replace(',', '', $this->input->post('precio'))
			];

		switch ($param) {
			case (substr($param, 0, 1) === 'U'):
				$data ['id_prod_proveedor'] = $this->prod_prov_mdl->update($prod_prov, $this->input->post('id_producto_proveedor'));
				$mensaje = [
					"id" 	=> 'Éxito',
					"desc"	=> 'Cotización actualizada correctamente',
					"type"	=> 'success'
				];
				break;

			default:
				$data ['id_prod_proveedor'] = $this->prod_prov_mdl->update(["estatus" => 0], $this->input->post('id_producto_proveedor'));
				$mensaje = [
					"id" 	=> 'Éxito',
					"desc"	=> 'Cotización eliminada correctamente',
					"type"	=> 'success'
				];
				break;
		}
		$this->jsonResponse($mensaje);
	}


}

/* End of file Productos_proveedor.php */
/* Location: ./application/controllers/Productos_proveedor.php */