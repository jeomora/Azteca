<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Usuarios extends MY_Controller {

	function __construct(){
		parent::__construct();
		$this->load->model("Usuarios_model", "user_md");
		$this->load->model("Cambios_model", "cambio_md");
		$this->load->library("form_validation");
	}

	public function index(){
		$data['links'] = [
			'/assets/css/plugins/dataTables/dataTables.bootstrap',
			'/assets/css/plugins/dataTables/dataTables.responsive',
			'/assets/css/plugins/dataTables/dataTables.tableTools.min',
			'/assets/css/plugins/dataTables/buttons.dataTables.min',
		];

		$data['scripts'] = [
			'/scripts/usuarios',
			'/assets/js/plugins/dataTables/jquery.dataTables.min',
			'/assets/js/plugins/dataTables/jquery.dataTables',
			'/assets/js/plugins/dataTables/dataTables.buttons.min',
			'/assets/js/plugins/dataTables/dataTables.bootstrap',
			'/assets/js/plugins/dataTables/dataTables.responsive',
			'/assets/js/plugins/dataTables/dataTables.tableTools.min',
		];
		$data["usuarios"] = $this->user_md->showUsers(NULL);
		$this->estructura("Admin/login", $data);
	}

	public function update_pass(){
		$user = $this->session->userdata();
		$antes = $this->user_md->get(NULL, ['id_usuario'=>$user["id_usuario"]])[0];
		$usuario = [
			"password"	=>	$this->encryptPassword($this->input->post('password')),
			];

		$data ['id_usuario'] = $this->user_md->update($usuario, $user["id_usuario"]);
		$cambios = [
				"id_usuario" => $user["id_usuario"],
				"fecha_cambio" => date('Y-m-d H:i:s'),
				"antes" => "Usuario cambia su contraseña ",
				"accion" => date('Y-m-d H:i:s'),
				"despues" => ""];
		$data['cambios'] = $this->cambio_md->insert($cambios);
		$mensaje = ["id" 	=> 'Éxito',
					"desc"	=> 'contraseña actualizada correctamente',
					"type"	=> 'success'];
		$this->jsonResponse($mensaje);
	}

	public function update_user33($marcoed){
		$user = $this->session->userdata();
		$usuario = [
			"imagen"	=>	$marcoed,
		];
		$avas = $this->ava_md->get(NULL,["id_avatar"=>$marcoed])[0];
		$this->session->set_userdata("imagen", $avas->nombre);
		$data ['id_usuario'] = $this->user_md->update($usuario, $user["id_usuario"]);
		$mensaje = ["id" 	=> 'Éxito',
					"desc"	=> 'Usuario actualizado correctamente',
					"type"	=> 'success'];
		$this->jsonResponse($mensaje);
	}
}
