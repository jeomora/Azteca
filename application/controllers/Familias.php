<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Familias extends MY_Controller {

	function __construct(){
		parent::__construct();
		$this->load->model("Familias_model", "fam_md");
		$this->load->library("pagination");
	}

	public function familias_view(){
		$data["familias"] = $this->fam_md->get();
		$this->load->view("Familias/table_familias", $data, FALSE);
	}

/*	public function familias_view(){
		$limit_per_page = 10;
		$start_index = ($this->uri->segment(3)) ? $this->uri->segment(3) : 0;
		$total_rows = $this->fam_md->count_all();
		if($total_rows > 0){
			$data["familias"] = $this->fam_md->get_pagination('', NULL, NULL, 10, $limit_per_page, $start_index, 'id_familia');
			$config =[
				"base_url"			=>	site_url().'/Familias/familias_view/',
				"total_rows"		=>	$total_rows,
				"per_page"			=>	$limit_per_page,
				"uri_segment"		=>	3,

				"reuse_query_string"=> TRUE,

				"full_tag_open"		=>	'<div class="pagination">',
				"full_tag_close"	=>	'</div>',

				"first_link"		=>	'Primera',
				"last_link"			=>	'Última',
				"next_link"			=>	'&nbsp; Siguiente &nbsp;',
				"prev_link"			=>	'&nbsp; Anterior &nbsp;',

				"first_tag_open"	=>	'<span class="firstlink">',
				"first_tag_close"	=>	'</span>',

				"last_tag_open"		=>	'<span class="lastlink">',
				"last_tag_close"	=>	'</span>',

				"next_tag_open"		=>	'<span class="nextlink">',
				"next_tag_close"	=>	'</span>',

				"prev_tag_open"		=>	'<span class="prevlink">',
				"prev_tag_close"	=>	'</span>',

				"cur_tag_open"		=>	'<span class="curlink">',
				"cur_tag_close"		=>	'</span>',

				"num_tag_open"		=>	'<span class="numlink">',
				"num_tag_close"		=>	'</span>',
			];
			$this->pagination->initialize($config);
			$data["links"] = $this->pagination->create_links();
		}
		$this->load->view("Familias/family_table", $data, FALSE);
	}*/

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
	}

}

/* End of file Familias.php */
/* Location: ./application/controllers/Familias.php */