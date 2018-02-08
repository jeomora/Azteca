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
	protected $KEY;

	function __construct() {
		parent::__construct();
		$this->load->model("Menus_model", "m_md");
		$data["main_menu"] = $this->m_md->getMenus();
		$data["usuario"] = $this->session->userdata();//Trae los datos del usuario;
		//Asignamos el valor a las variables"!
		$this->ASSETS = "./assets/";
		$this->UPLOADS = "uploads/";
		$this->KEY='APGoyQGOKAR5iXQ1wiO6i4jNczeMV7Sg';//Para encriptar las contraseñas

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
		$year	=	substr(date($date),0,4);//Año actual
		return date("W", mktime(0,0,0,$month,$day,$year));//El número de la semana
	}

	public function cellStyle($cells=NULL, $background=NULL, $font_color=NULL, $bold=FALSE, $font_size=NULL, $font_family=NULL){
		$this->load->library("excelfile");
		$this->excelfile->getActiveSheet()->getStyle($cells)->applyFromArray(
			array(	"fill"	=>	array(	"type"	=>	PHPExcel_Style_Fill::FILL_SOLID,
										"color"	=>	array("rgb"	=>	$background)),
					"alignment"	=>	array(	"horizontal"	=>	PHPExcel_Style_Alignment::HORIZONTAL_CENTER,),
					"font"		=>	array(	"bold"			=>	$bold,
											"color"			=>	array(	"rgb"	=>	$font_color),
											"size"			=>	$font_size,
											"name"			=>	$font_family))
		);
	}

	public function encryptPassword($password=NULL){
		$encrypted = base64_encode(mcrypt_encrypt(MCRYPT_RIJNDAEL_256, md5($this->KEY), $password, MCRYPT_MODE_CBC, md5(md5($this->KEY))));
		return $encrypted;
	}

	public function showPassword($password=NULL){
		$decrypted = rtrim(mcrypt_decrypt(MCRYPT_RIJNDAEL_256, md5($this->KEY), base64_decode($password), MCRYPT_MODE_CBC, md5(md5($this->KEY))), "\0");
		return $decrypted;
	}


}

/* End of file MY_Controller.php */
/* Location: ./application/controllers/MY_Controller.php */