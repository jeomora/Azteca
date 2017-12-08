<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Familias extends MY_Controller {

	function __construct(){
		parent::__construct();
		$this->load->model("Familias_model", "fam_md");
	}

	public function familias_view(){
		$data["familias"] = $this->fam_md->get();
		$data["scripts"] = ["/assets/scripts/familias"];
		$this->estructura("familias/table_familias", $data);
	}

	public function add_familia(){
		$data["title"]="Registrar familias";
		$this->load->view("Structure/header_modal", $data);
		$this->load->view("Familias/new_familia", $data);
		$this->load->view("Structure/footer_modal_save");
	}

	public function update_familia($id){
		$data["title"]="Actualizar datos de la familia";
		$this->load->view("Structure/header_modal", $data);
		$data["familia"] = $this->fam_md->get(NULL, ['id_familia'=>$id])[0];
		$this->load->view("Familias/edit_familia", $data);
		$this->load->view("Structure/footer_modal_edit");
	}

	public function delete_familia($id){
		$data["title"]="Familia a eliminar";
		$this->load->view("Structure/header_modal", $data);
		$data["familia"] = $this->fam_md->get(NULL, ['id_familia'=>$id])[0];
		$this->load->view("Familias/delete_familia", $data);
		$this->load->view("Structure/footer_modal_delete");
	}

	public function accion($param){
		$familia = [
			'nombre'	=>	strtoupper($this->input->post('nombre'))
			];

		switch ($param) {
			case (substr($param, 0, 1) === 'I'):
				$data ['id_familia']=$this->fam_md->insert($familia);
				$mensaje = [
					"id" 	=> 'Éxito',
					"desc"	=> 'Familia registrada correctamente',
					"type"	=> 'success'
				];
				break;

			case (substr($param, 0, 1) === 'U'):
				$data ['id_familia'] = $this->fam_md->update($familia, $this->input->post('id_familia'));
				$mensaje = [
					"id" 	=> 'Éxito',
					"desc"	=> 'Familia actualizada correctamente',
					"type"	=> 'success'
				];
				break;

			default:
				$data ['id_familia'] = $this->fam_md->update(["estatus" => 0], $this->input->post('id_familia'));
				$mensaje = [
					"id" 	=> 'Éxito',
					"desc"	=> 'Familia eliminada correctamente',
					"type"	=> 'success'
				];
				break;
		}
		$this->jsonResponse($mensaje);
		// redirect("Familias/familias_view", 'refresh');

	}

}

/* End of file Familias.php */
/* Location: ./application/controllers/Familias.php */