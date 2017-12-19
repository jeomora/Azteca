<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Productos extends MY_Controller {

	function __construct(){
		parent::__construct();
		$this->load->model("Productos_model", "pro_md");
		$this->load->model("Familias_model", "fam_md");
		$this->load->library("pagination");
	}

	public function productos_view(){
		$data["productos"] = $this->pro_md->getProductos();
		$this->load->view("Productos/table_productos", $data, FALSE);
	}

	// Esta función es de ejemplo para paginación
	// public function productos_view(){
	// 	$columns = "productos.id_producto,
	// 		productos.nombre AS producto,
	// 		productos.precio,
	// 		productos.codigo,
	// 		f.nombre AS familia";

	// 	$joins =  [
	// 			["table"	=>	"familias f",	"ON"	=>	"productos.id_familia = f.id_familia",	"clausula"	=>	"LEFT"]
	// 	];

	// 	$limit_per_page = 50;
	// 	$start_index = ($this->uri->segment(3)) ? $this->uri->segment(3) : 0;
	// 	$total_rows = $this->pro_md->count_all();
	// 	if($total_rows > 0){
	// 		$data["productos"] = $this->pro_md->get_pagination($columns, NULL, $joins, "", $limit_per_page, $start_index, 'id_producto');
	// 		$config =[
	// 			"base_url"			=>	site_url().'/Productos/productos_view/',
	// 			"total_rows"		=>	$total_rows,
	// 			"per_page"			=>	$limit_per_page,
	// 			"uri_segment"		=>	3,

	// 			"reuse_query_string"=> TRUE
	// 		];
	// 		$this->pagination->initialize($config);
	// 		$data["links"] = $this->pagination->create_links();
	// 	}
	// 	$this->load->view("Productos/productos_table", $data, FALSE);
	// }

	public function add_producto(){
		$data["title"]="Registrar productos";
		$this->load->view("Structure/header_modal", $data);
		$data["familias"] = $this->fam_md->get();
		$this->load->view("Productos/new_producto", $data);
		$this->load->view("Structure/footer_modal_save");
	}

	public function get_update($id){
		$data["title"]="Actualizar datos del producto";
		$this->load->view("Structure/header_modal", $data);
		$data["producto"] = $this->pro_md->get(NULL, ['id_producto'=>$id])[0];
		$data["familias"] = $this->fam_md->get();
		$this->load->view("Productos/edit_producto", $data);
		$this->load->view("Structure/footer_modal_edit");
	}

	public function get_delete($id){
		$data["title"]="Producto a eliminar";
		$this->load->view("Structure/header_modal", $data);
		$data["producto"] = $this->pro_md->get(NULL, ['id_producto'=>$id])[0];
		$this->load->view("Productos/delete_producto", $data);
		$this->load->view("Structure/footer_modal_delete");
	}

	public function accion($param){
		$producto = [
				'codigo'	=>	$this->input->post('codigo'),
				'nombre'	=>	strtoupper($this->input->post('nombre')),
				// 'precio'	=>	$this->input->post('precio'),
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
				$data ['id_producto'] = $this->pro_md->update(["estatus" => 0], $this->input->post('id_producto'));
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