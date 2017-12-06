<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Productos_control extends MY_Controller {

	function __construct(){
		parent::__construct();
		$this->load->model("Productos_model", "pro_md");
		$this->load->model("Familias_model", "fam_md");
	}

	public function productos_view(){
		$data["productos"] = $this->pro_md->getProductos();
		$this->estructura("Productos/table_productos", $data);
	}

	public function add_producto(){
		$data["title"]="Registrar productos";
		$this->load->view("Structure/header_modal", $data);
		$data["familias"] = $this->fam_md->get();
		$this->load->view("Productos/new_producto", $data);
		$this->load->view("Structure/footer_modal_save");
	}

	public function update_producto($id){
		$data["title"]="Actualizar datos del producto";
		$this->load->view("Structure/header_modal", $data);
		$data["producto"] = $this->pro_md->get(['id_producto'=>$id])[0];
		$data["familias"] = $this->fam_md->get();
		$this->load->view("Productos/edit_producto", $data);
		$this->load->view("Structure/footer_modal_edit");
	}

	public function delete_producto($id){
		$data["title"]="Producto a eliminar";
		$this->load->view("Structure/header_modal", $data);
		$data["producto"] = $this->pro_md->get(['id_producto'=>$id])[0];
		$this->load->view("Productos/delete_producto", $data);
		$this->load->view("Structure/footer_modal_delete");
	}

	public function accion($param){
		$producto = array(
				'nombre'		=>	strtoupper($this->input->post('nombre')),
				'precio'		=>	$this->input->post('precio'),
				'descripcion'	=>	$this->input->post('descripcion'),
				'longitud'		=>	$this->input->post('longitud'),
				'latitud'		=>	$this->input->post('latitud'),
				'id_categoria'	=>	($this->input->post('id_categoria') !="-1") ? $this->input->post('id_categoria') : NULL
			);
		switch ($param) {
			case (substr($param, 0, 1) === 'I'):
				$data ['id_producto']=$this->pr_md->insert($producto);
				$mensaje = array(
					"id" 	=> 'Éxito',
					"desc"	=> 'Producto registrado correctamente',
					"type"	=> 'success'
				);
				break;

			case (substr($param, 0, 1) === 'U'):
				$data ['id_producto'] = $this->pr_md->update($producto, $this->input->post('id_producto'));
				$mensaje = array(
					"id" 	=> 'Éxito',
					"desc"	=> 'Producto actualizado correctamente',
					"type"	=> 'success'
				);
				break;

			default:
				$data ['id_producto'] = $this->pr_md->update(["estatus" => 1], $this->input->post('id_producto'));
				$mensaje = array(
					"id" 	=> 'Éxito',
					"desc"	=> 'Producto eliminado correctamente',
					"type"	=> 'success'
				);
				break;
		}
		$this->jsonResponse($mensaje);
	}

}

/* End of file Productos_control.php */
/* Location: ./application/controllers/Productos_control.php */