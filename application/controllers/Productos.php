<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Productos extends MY_Controller {

	function __construct(){
		parent::__construct();
		$this->load->model("Productos_model", "pro_md");
		$this->load->model("Familias_model", "fam_md");
	}

	public function productos_view(){
		$data["productos"] = $this->pro_md->getProductos();
		$data["scripts"] = ["/assets/scripts/productos"];
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
		$producto = [
				'codigo'	=>	$this->input->post('codigo'),
				'nombre'	=>	strtoupper($this->input->post('nombre')),
				'precio'	=>	$this->input->post('precio'),
				'id_familia'=>	($this->input->post('id_familia') !="-1") ? $this->input->post('id_familia') : NULL
			];
		switch ($param) {
			case (substr($param, 0, 1) === 'I'):
				$data ['id_producto']=$this->pro_md->insert($producto);
				$mensaje = [
					"id" 	=> 'Éxito',
					"desc"	=> 'Producto registrado correctamente',
					"type"	=> 'success'
				];
				break;

			case (substr($param, 0, 1) === 'U'):
				$data ['id_producto'] = $this->pro_md->update($producto, $this->input->post('id_producto'));
				$mensaje = [
					"id" 	=> 'Éxito',
					"desc"	=> 'Producto actualizado correctamente',
					"type"	=> 'success'
				];
				break;

			default:
				$data ['id_producto'] = $this->pro_md->update(["estatus" => 1], $this->input->post('id_producto'));
				$mensaje = [
					"id" 	=> 'Éxito',
					"desc"	=> 'Producto eliminado correctamente',
					"type"	=> 'success'
				];
				break;
		}
		$this->jsonResponse($mensaje);
	}

}

/* End of file Productos.php */
/* Location: ./application/controllers/Productos.php */