<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Familias_control extends MY_Controller {

	function __construct(){
		parent::__construct();
		$this->load->model("Familias_model", "fam_md");
	}

	public function familias_view(){
		$data["familias"] = $this->fam_md->get();
		$data['scripts'] = [
				'scripts/familias'];
		
		$this->estructura("familias/table_familias", $data);
	}

	public function add_familia(){
		$data["title"]="Registrar familias";
		$this->load->view("Structure/header_modal", $data);
		$this->load->view("Familias/new_familia", $data);
		$this->load->view("Structure/footer_modal_save");
	}

	

}

/* End of file Familias_control.php */
/* Location: ./application/controllers/Familias_control.php */