<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Familias extends MY_Controller {

	function __construct(){
		parent::__construct();
		$this->load->model("Usuarios_model", "user_md");
		$this->load->model("Familias_model", "fam_md");
		$this->load->model("Familias2_model", "fam2_md");
		$this->load->model("Grupos_model", "gr_md");
		$this->load->library("form_validation");
	}

	public function perfumeria(){
		$data['scripts'] = [
			'/scripts/Familias/perfumeria',
		];
		$data['familias'] = $this->fam_md->get();
		$this->estructura("Familias/table_perfumeria", $data);
	}

	public function getFamilias(){
		$count = $this->fam_md->getCount(NULL)[0];
		$data["meta"] = ["page"=>1,"pages"=>1,"perpage"=>-1,"total"=>(int)$count->total,"sort"=>"asc","field"=>"RecordID"];
		$query = $this->fam_md->getFamilias(NULL);
		$data["data"]=[];
		foreach ($query as $key => $value) {
			$data["data"][$key] = [
				"id_familia"		=>	intval($value->id_familia),
				"nombre"	=>	$value->nombre,
				"Status"		=>	$value->estatus,
				"Actions"		=> null
			];
		}
		
		$this->jsonResponse($data);
	}

	public function getFamilia($id_familia){
		$familia = $this->fam_md->get(NULL,["id_familia"=>$id_familia])[0];
		$this->jsonResponse($familia);
	}	

	public function delete_familia(){
		$user = $this->session->userdata();
		$data ['id_usuario'] = $this->fam_md->update(["estatus" => 0], $this->input->post('id_usuario'));
		$mensaje = ["id" 	=> 'Éxito',
					"desc"	=> 'Familia eliminada correctamente',
					"type"	=> 'success'];
		$this->jsonResponse($mensaje);
	}

	public function update_familia(){
		$user = $this->session->userdata();
		$antes = $this->fam_md->get(NULL, ['id_familia'=>$this->input->post('id_usuarios')])[0];
		$usuario = [
			"nombre"	=>	strtoupper($this->input->post('nombre')),
		];

		$data ['id_usuario'] = $this->fam_md->update($usuario, $this->input->post('id_usuarios'));
		$mensaje = ["id" 	=> 'Éxito',
					"desc"	=> 'Familia actualizada correctamente',
					"type"	=> 'success'];
		$this->jsonResponse($mensaje);
	}

	public function save_familia(){
		$usuario = [
			"nombre"	=>	strtoupper($this->input->post('nombres')),
		];

		$getUsuario = $this->fam_md->get(NULL, ['nombre'=>$usuario['nombre']])[0];

		if(sizeof($getUsuario) == 0){
			$data ['id_usuario'] = $this->fam_md->insert($usuario);
			$mensaje = ["id" 	=> 'Éxito',
						"desc"	=> 'Familia registrada correctamente',
						"type"	=> 'success'];
			$user = $this->session->userdata();
		}else{
			$mensaje = [
				"id" 	=> 'Alerta',
				"desc"	=> 'La familia ['.$usuario['nombre'].'] está registrada en el Sistema',
				"type"	=> 'warning'
			];
		}
		$this->jsonResponse($mensaje);
	}

	/***************************************Farmacia***************************************/
	/******************************************************************************/

	public function Farmacia(){
		$data['scripts'] = [
			'/scripts/Familias/farmacia',
		];
		$data['familias'] = $this->fam2_md->get();
		$this->estructura("Familias/table_farmacia", $data);
	}

	public function getFamilias2(){
		$count = $this->fam2_md->getCount(NULL)[0];
		$data["meta"] = ["page"=>1,"pages"=>1,"perpage"=>-1,"total"=>(int)$count->total,"sort"=>"asc","field"=>"RecordID"];
		$query = $this->fam2_md->getFamilias(NULL);
		$data["data"]=[];
		foreach ($query as $key => $value) {
			$data["data"][$key] = [
				"id_familia"		=>	intval($value->id_familia),
				"nombre"	=>	$value->nombre,
				"Status"		=>	$value->estatus,
				"Actions"		=> null
			];
		}
		
		$this->jsonResponse($data);
	}

	public function getFamilia2($id_familia){
		$familia = $this->fam2_md->get(NULL,["id_familia"=>$id_familia])[0];
		$this->jsonResponse($familia);
	}	

	public function delete_familia2(){
		$user = $this->session->userdata();
		$data ['id_usuario'] = $this->fam2_md->update(["estatus" => 0], $this->input->post('id_usuario'));
		$mensaje = ["id" 	=> 'Éxito',
					"desc"	=> 'Familia eliminada correctamente',
					"type"	=> 'success'];
		$this->jsonResponse($mensaje);
	}

	public function update_familia2(){
		$user = $this->session->userdata();
		$antes = $this->fam2_md->get(NULL, ['id_familia'=>$this->input->post('id_usuarios')])[0];
		$usuario = [
			"nombre"	=>	strtoupper($this->input->post('nombre')),
		];

		$data ['id_usuario'] = $this->fam2_md->update($usuario, $this->input->post('id_usuarios'));
		$mensaje = ["id" 	=> 'Éxito',
					"desc"	=> 'Familia actualizada correctamente',
					"type"	=> 'success'];
		$this->jsonResponse($mensaje);
	}

	public function save_familia2(){
		$usuario = [
			"nombre"	=>	strtoupper($this->input->post('nombres')),
		];

		$getUsuario = $this->fam2_md->get(NULL, ['nombre'=>$usuario['nombre']])[0];

		if(sizeof($getUsuario) == 0){
			$data ['id_usuario'] = $this->fam2_md->insert($usuario);
			$mensaje = ["id" 	=> 'Éxito',
						"desc"	=> 'Familia registrada correctamente',
						"type"	=> 'success'];
			$user = $this->session->userdata();
		}else{
			$mensaje = [
				"id" 	=> 'Alerta',
				"desc"	=> 'La familia ['.$usuario['nombre'].'] está registrada en el Sistema',
				"type"	=> 'warning'
			];
		}
		$this->jsonResponse($mensaje);
	}
}