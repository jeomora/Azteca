<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class MY_Controller extends CI_Controller {

	protected $header = ""; //Archivo header
	protected $top_menu = ""; //Menú arriba
	protected $folder = ""; //Contenedor de las vistas del menú
	protected $footer = ""; //Archivo footer
	protected $main = ""; //Archivo principal
	protected $ASSETS;
	protected $UPLOADS;

	function __construct() {
		parent::__construct();
		$this->load->model("Menus_model", "m_md");
		$this->load->model("Cotizaciones_model", "ct_mdl");
		$data["main_menu"] = $this->m_md->getMenus();
		$data["usuario"] = $this->ion_auth->user()->row();
		//Asignamos el valor a las variables"!
		$this->ASSETS = "./assets/";
		$this->UPLOADS = "uploads/";

		$user = $this->ion_auth->user()->row();//Obtenemos el usuario logeado 
		$where = [];
		
		if(! $this->ion_auth->is_admin()){//Solo mostrar sus Productos cuando es proveedor
			@$where = ["cotizaciones.id_proveedor" => $data['usuario']->id];
		}
		$data["cotizaciones"] = $this->ct_mdl->getCotizaciones($where);
		
		$this->load->vars($data);
		$this->header = "Structure/header";
		$this->top_menu  = "Structure/top_menu";
		$this->footer = "Structure/footer";
		$this->main = "Structure/main";
		$this->folder = "Structure";
	}

	public function estructura($view, $data = NULL) {
		$this->load->view($this->header, $data);
		$this->load->view($this->top_menu, $data);
		$this->load->view($view, $data);
		$this->load->view($this->footer, $data);
		$this->load->view($this->main, $data);
	}

	public function jsonResponse( $response ) {
		header( "content-type: application/json; charset=utf8" );
		echo json_encode( $response );
	}

	public function createFolder($folder){
		$base = $this->ASSETS.$this->UPLOADS;
		$ruta = $this->ASSETS.$this->UPLOADS.$folder."/";
		if(!is_dir($ruta)){
			mkdir($this->ASSETS, 0777);
			mkdir($base, 0777);
			mkdir($ruta, 0777);
		}
		return $ruta;
	}

	public function weekNumber($date=NULL){
		if (empty($date)) {
			$date = date("Y-m-d");
		}
		$day	=	substr(date($date),8,2);//Día actual
		$month	=	substr(date($date),5,2);//Mes actual
		$year	=	substr(date($date),0,4);//Año actul
		return date("W", mktime(0,0,0,$month,$day,$year));//El número de la semana
	}

}

/* End of file MY_Controller.php */
/* Location: ./application/controllers/MY_Controller.php */