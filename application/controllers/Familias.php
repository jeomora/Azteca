<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Familias extends MY_Controller {

	function __construct(){
		parent::__construct();
		$this->load->model("Familias_model", "fam_md");
	}

	public function familias_view(){
		$data['links'] = [
			'/assets/css/plugins/dataTables/dataTables.bootstrap',
			'/assets/css/plugins/dataTables/dataTables.responsive',
			'/assets/css/plugins/dataTables/dataTables.tableTools.min',
			'/assets/css/plugins/dataTables/buttons.dataTables.min',
		];

		$data['scripts'] = [
			'/scripts/familias',
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
		$data["familias"] = $this->fam_md->get();
		$this->estructura("Familias/table_familias", $data);
		// $this->load->view("Familias/table_familias", $data, FALSE);
	}

	public function table_familias(){
		$data["familias"] = $this->fam_md->get();
		$this->load->view("Familias/table_familias", $data, FALSE);
	}

	public function add_familia(){
		$data["title"]="REGISTRAR FAMILIAS";
		$data["class"] = "new_familia";
		$data["view"] = $this->load->view("Familias/new_familia", NULL, TRUE);
		$this->jsonResponse($data);
	}

	public function get_update($id){
		$data["title"]="ACTUALIZAR DATOS DE LA FAMILIA";
		$data["class"] = "update_familia";
		$data["familia"] = $this->fam_md->get(NULL, ['id_familia'=>$id])[0];
		$data["view"] = $this->load->view("Familias/edit_familia", $data, TRUE);
		$this->jsonResponse($data);
	}

	public function get_delete($id){
		$data["title"]="FAMILIA A ELIMINAR";
		$data["class"] = "delete_familia";
		$data["familia"] = $this->fam_md->get(NULL, ['id_familia'=>$id])[0];
		$data["view"] = $this->load->view("Familias/delete_familia", $data, TRUE);
		$this->jsonResponse($data);
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
	}

}

/* End of file Familias.php */
/* Location: ./application/controllers/Familias.php */