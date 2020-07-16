<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Inicio extends MY_Controller {

	function __construct(){
		parent::__construct();
		$this->load->model("Usuarios_model", "user_md");
		$this->load->model("Grupos_model", "grupo_md");
		$this->load->model("Avatars_model", "ava_md");
		$this->load->model("Productos_model","prod_mdl");
		$this->load->model("Prolunes_model", "prolu_md");
		$this->load->model("Exislunes_model", "ex_lun_md");
		$this->load->model("Suclunes_model", "suc_md");
		$this->load->library("form_validation");
	}

	public function index(){
		if($this->session->userdata("username")){
			$user = $this->session->userdata();//Trae los datos del usuario
			$data["dias"] = array("DOMINGO","LUNES","MARTES","MIÉRCOLES","JUEVES","VIERNES","SÁBADO");
			$data["meses"] = array("ENERO","FEBRERO","MARZO","ABRIL","MAYO","JUNIO","JULIO","AGOSTO","SEPTIEMBRE","OCTUBRE","NOVIEMBRE","DICIEMBRE");
			$data["fecha"] =  $data["dias"][date('w')]." ".date('d')." DE ".$data["meses"][date('n')-1]. " DEL ".date('Y') ;
			
			if ($user["id_grupo"] === "1" || $user["id_grupo"] === 1) { // Compras
				redirect("Cotizaciones/anteriores", $data);
			}elseif ($user["id_grupo"] <> "2" || $user["id_grupo"] <> 2) { //Sucursales Existencias y Pedidos
				$data['scripts'] = ['/scripts/Sucursales/index'];
				$data["cuantas"] = $this->ex_lun_md->getCuantasTienda(NULL,$user["id_usuario"])[0];
				$data["noprod"] = $this->prolu_md->getCount(NULL)[0];
				$data["novol"] = $this->prod_mdl->getVolCount(NULL)[0];
				$data["noall"] = $this->prod_mdl->getAllCount(NULL)[0];
				$data["volcuantas"] = $this->ex_lun_md->getVolTienda(NULL,$user["id_usuario"])[0];
				$data["allcuantas"] = $this->ex_lun_md->getAllTienda(NULL,$user["id_usuario"])[0];
				

				$this->estructura("Dashboards/sucursal", $data);
			}elseif ($user["id_grupo"] === "2" || $user["id_grupo"] === 2) { // Proveedores
				$data["user"] = $user;
				redirect("Facturas", $data);
			}
		}else{
			$this->data["message"] =NULL;
			$this->estructura_login("Admin/login", $this->data, FALSE);
		}
	}

	public function getLunesSin(){
		$user = $this->session->userdata();//Trae los datos del usuario
		$data["existenciasnot"] = $this->ex_lun_md->getLunExistNot(NULL,$user["id_usuario"]);
		$this->jsonResponse($data["existenciasnot"]);
	}

	public function getLunesCon(){
		$user = $this->session->userdata();//Trae los datos del usuario
		$data["existencias"] = $this->ex_lun_md->getLunExist(NULL,$user["id_usuario"]);
		$this->jsonResponse($data["existencias"]);
	}

	public function getGeneralSin(){
		$user = $this->session->userdata();//Trae los datos del usuario
		$data["existenciasnot"] = $this->ex_lun_md->getAllExistNot(NULL,$user["id_usuario"]);
		$this->jsonResponse($data["existenciasnot"]);
	}

	public function getGeneralCon(){
		$user = $this->session->userdata();//Trae los datos del usuario
		$data["existencias"] = $this->ex_lun_md->getAllExist(NULL,$user["id_usuario"]);
		$this->jsonResponse($data["existencias"]);
	}

	public function getVolumenSin(){
		$user = $this->session->userdata();//Trae los datos del usuario
		$data["existenciasnot"] = $this->ex_lun_md->getVolExistNot(NULL,$user["id_usuario"]);
		$this->jsonResponse($data["existenciasnot"]);
	}

	public function getVolumenCon(){
		$user = $this->session->userdata();//Trae los datos del usuario
		$data["existencias"] = $this->ex_lun_md->getVolExist(NULL,$user["id_usuario"]);
		$this->jsonResponse($data["existencias"]);
	}

	public function login(){
		if($this->session->userdata("username")){
			$user = $this->session->userdata();
			redirect("Inicio", $data);
		}
		$this->data["message"] =NULL;
		$this->estructura_login("Admin/login", $this->data, FALSE);
	}

	public function logout(){
		$this->session->sess_destroy();
		redirect("/", "refresh");
	}

	private function estructura_login($view, $data=array()){
		$this->_render_page($view, $data);
	}

	private function _render_page($view, $data=null, $returnhtml=false){//I think this makes more sense
		$this->viewdata = (empty($data)) ? $this->data: $data;
		$view_html = $this->load->view($view, $this->viewdata, $returnhtml);
		if ($returnhtml) return $view_html;//This will return html on 3rd argument being true
	}
	
	public function validamesta(){
		$this->data["message"] =NULL;
		$values = json_decode($this->input->post("values"));
		if (isset($values->email) && isset($values->password)) {

			$where=["email"	=>	$values->email,
					"password"	=>	$this->encryptPassword($values->password)];
			$validar = $this->user_md->login($where)[0];
			if(sizeof($validar) > 0){
				$avas = $this->ava_md->get(NULL,["id_avatar"=>$validar->imagen])[0];
				$grupo = $this->grupo_md->get(NULL,["id_grupo"=>$validar->id_grupo])[0];
				$values=[	"id_usuario"=>	$validar->id_usuario,
							"id_grupo"	=>	$validar->id_grupo,
							"nombre"	=>	$validar->nombre,
							"nombres"	=>	explode(' ', $validar->nombre, 3),
							"password"	=>	$validar->password,
							"email"		=>	$validar->email,
							"imagen"	=>	$avas->nombre,
							"grupo"		=>	$grupo->nombre,
							"estatus"	=>	$validar->estatus ];
				$this->session->set_userdata("username", $values['nombre']);
				$this->session->set_userdata($values);
				$user = $this->session->userdata();
				$this->jsonResponse("true");
			}else{
				$this->jsonResponse("false");
			}
		}else{
			$this->jsonResponse("false");
		}
	}

}
