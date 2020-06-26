<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Usuarios extends MY_Controller {

	function __construct(){
		parent::__construct();
		$this->load->model("Usuarios_model", "user_md");
		$this->load->model("Familias_model", "fam_md");
		$this->load->model("Grupos_model", "gr_md");
		$this->load->library("form_validation");
	}

	public function index(){
		$data['scripts'] = [
			'/scripts/Usuarios/list-datatable',
		];
		$data["grupos"] = $this->gr_md->get();
		$data['familias'] = $this->fam_md->get();
		$data["usuarios"] = $this->user_md->getUsuarios();
		$this->estructura("Usuarios/table_usuarios", $data);
	}

	public function getUsuarios(){
		$count = $this->user_md->getCount(NULL)[0];
		$data["meta"] = ["page"=>1,"pages"=>1,"perpage"=>-1,"total"=>(int)$count->total,"sort"=>"asc","field"=>"RecordID"];
		$query = $this->user_md->getUsuarios(NULL);
		$data["data"]=[];
		foreach ($query as $key => $value) {
			$data["data"][$key] = [
				"id_usuario"		=>	intval($value->id_usuario),
				"Type"		=>	$value->id_usuario,
				"nombre"	=>	$value->CompanyAgent,
				"password"		=>	$value->password,
				"email"			=>	$value->email,
				"Country"			=>	$value->cargo,
				"ShipName"		=>	$value->ShipName,
				"Status"		=>	$value->Status,
				"id_grupo"		=>	$value->id_grupo,
				"Actions"		=> null
			];
		}
		
		$this->jsonResponse($data);
	}
	public function getUser($id_usuario){
		$usuario = $this->user_md->get(NULL,["id_usuario"=>$id_usuario])[0];
		$this->jsonResponse($usuario);
	}	
	public function delete_user(){
		$user = $this->session->userdata();
		$data ['id_usuario'] = $this->user_md->update(["estatus" => 0], $this->input->post('id_usuario'));
		$mensaje = ["id" 	=> 'Éxito',
					"desc"	=> 'Usuario eliminado correctamente',
					"type"	=> 'success'];
		$this->jsonResponse($mensaje);
	}


	public function getUserIP(){
	    if(!empty($_SERVER['HTTP_CLIENT_IP'])){
	        //ip from share internet
	        $ip = $_SERVER['HTTP_CLIENT_IP'];
	    }elseif(!empty($_SERVER['HTTP_X_FORWARDED_FOR'])){
	        //ip pass from proxy
	        $ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
	    }else{
	        $ip = $_SERVER['REMOTE_ADDR'];
	    }

	    return $this->jsonResponse($ip);
	}

	public function update_user(){
		$user = $this->session->userdata();
		$antes = $this->user_md->get(NULL, ['id_usuario'=>$this->input->post('id_usuarios')])[0];
		$gr = $this->input->post('id_grupo');
		if ($gr <> 2) {
			$conjunto = "";
			$cargos = "";
		}else{
			$conjunto = $this->input->post('conjunto');
			$cargos = $this->input->post('cargo');
		}
		$usuario = [
			"nombre"	=>	strtoupper($this->input->post('nombre')),
			"apellido"	=>	strtoupper($this->input->post('apellido')),
			"telefono"	=>	$this->input->post('telefono'),
			"email"		=>	$this->input->post('correo'),
			"password"	=>	$this->encryptPassword($this->input->post('password')),
			"id_grupo"	=>	$this->input->post('id_grupo'),
			"conjunto"	=>	$conjunto,
			"cargo"	=>	$cargos
		];

		$data ['id_usuario'] = $this->user_md->update($usuario, $this->input->post('id_usuarios'));
		$mensaje = ["id" 	=> 'Éxito',
					"desc"	=> 'Usuario actualizado correctamente',
					"type"	=> 'success'];
		$this->jsonResponse($mensaje);
	}

	public function save_user(){
		$gr = $this->input->post('id_grupos');
		if ($gr <> 2) {
			$conjunto = "";
			$cargos = "";
		}else{
			$conjunto = $this->input->post('conjunto');
			$cargos = $this->input->post('cargo');
		}
		$usuario = [
			"nombre"	=>	strtoupper($this->input->post('nombres')),
			"apellido"	=>	strtoupper($this->input->post('apellidos')),
			"telefono"	=>	$this->input->post('telefonos'),
			"email"		=>	$this->input->post('correos'),
			"password"	=>	$this->encryptPassword($this->input->post('passwords')),
			"id_grupo"	=>	$this->input->post('id_grupos'),
			"conjunto"	=>	$conjunto,
			"cargo"	=>	$cargos
		];

		$getUsuario = $this->user_md->get(NULL, ['email'=>$usuario['email']])[0];

		if(sizeof($getUsuario) == 0){
			$data ['id_usuario'] = $this->user_md->insert($usuario);
			$mensaje = ["id" 	=> 'Éxito',
						"desc"	=> 'Usuario registrado correctamente',
						"type"	=> 'success'];
			$user = $this->session->userdata();
		}else{
			$mensaje = [
				"id" 	=> 'Alerta',
				"desc"	=> 'El correo ['.$usuario['email'].'] está registrado en el Sistema',
				"type"	=> 'warning'
			];
		}
		$this->jsonResponse($mensaje);
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
				"despues" => "Password: ".$this->input->post('password')];
		$data['cambios'] = $this->cambio_md->insert($cambios);
		$mensaje = ["id" 	=> 'Éxito',
					"desc"	=> 'contraseña actualizada correctamente',
					"type"	=> 'success'];
		$this->jsonResponse($mensaje);
	}

}
