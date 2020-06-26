<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Inicio extends MY_Controller {

	function __construct(){
		parent::__construct();
		$this->load->library("form_validation");
	}

	public function index(){
		redirect("Catalogo/admin", $data);
	}

	public function allpedido(){
		$user = $this->session->userdata();
		$data["noprod"] = $this->pr_md->getAllCount(NULL)[0];
		$data["cuantas"] = $this->ex_md->getAllTienda(NULL,$user["id_usuario"])[0];
		$data["existencias"] = $this->ex_md->getAllExist(NULL,$user["id_usuario"]);
		$data["existencias2"] = $this->ex2_md->getAllExist(NULL,$user["id_usuario"]);
		$data["existenciasnot"] = $this->ex_md->getAllExistNot(NULL,$user["id_usuario"]);
		$data["existenciasnot2"] = $this->ex2_md->getAllExistNot(NULL,$user["id_usuario"]);
		$this->jsonResponse($data);
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

	public function getMesesProv(){
		$meses = $this->pr_md->getMesesProv(NULL);
		$this->jsonResponse($meses);
	}

	public function getCambios(){
		$meses = $this->cambio_md->getCambios2(NULL);
		$this->jsonResponse($meses);
	}

	public function getMeses(){
		$meses = $this->pr_md->getMeses(NULL);
		$this->jsonResponse($meses);
	}

	public function new_usuario(){
		$data["title"]="REGISTRAR USUARIOS";
		$user = $this->session->userdata();
		$data["sucursal"] = $this->suc_md->get();
		$data["grupo"] = $this->grupo_md->get(NULL,["id_grupo <>"=>6]);
		$data["view"] = $this->load->view("Admin/new_usuario", $data, TRUE);
		$data["button"]="<button class='btn btn-success new_usuario' type='button'>
							<span class='bold'><i class='fa fa-floppy-o'></i></span> &nbsp;Guardar
						</button>";
		$this->jsonResponse($data);
	}

	public function get_update($id){
		$data["title"]="ACTUALIZAR DATOS DEL USUARIO";
		$data["usuario"] = $this->user_md->get(NULL, ['id_usuario'=>$id])[0];
		$data["password"]= $this->showPassword($data["usuario"]->password);//Para mostrar la contraseña
		$data["sucursal"] = $this->suc_md->get();
		$data["grupo"] = $this->grupo_md->get(NULL,["id_grupo <>"=>6]);
		$user = $this->session->userdata();
		$data["view"] =$this->load->view("Admin/edit_usuario", $data, TRUE);
		$data["button"]="<button class='btn btn-success update_usuario' type='button'>
							<span class='bold'><i class='fa fa-floppy-o'></i></span> &nbsp;Guardar cambios
						</button>";
		$this->jsonResponse($data);
	}

	public function get_delete($id){
		$data["title"]="USUARIO A ELIMINAR";
		$data["usuario"] = $this->user_md->get(NULL, ['id_usuario'=>$id])[0];
		$data["view"] = $this->load->view("Admin/delete_usuario", $data,TRUE);
		$data["button"]="<button class='btn btn-danger delete_usuario' type='button'>
							<span class='bold'><i class='fa fa-times'></i></span> &nbsp;Aceptar
						</button>";
		$this->jsonResponse($data);
	}

	public function save_user(){
		if($this->input->post('estatus') == 3){
			$conj = $this->input->post('conjunto');
		}else{
			$conj = 0;
		}
		$usuario = [
			"nombre"	=>	strtoupper($this->input->post('nombre')),
			"apellido"	=>	strtoupper($this->input->post('apellido')),
			"telefono"	=>	$this->input->post('telefono'),
			"email"		=>	$this->input->post('correo'),
			"password"	=>	$this->encryptPassword($this->input->post('password')),
			"estatus"	=>	$this->input->post('estatus'),
			"conjunto"	=>	$conj
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

	public function update_user(){
		$usuario = [
			"nombre"	=>	strtoupper($this->input->post('nombre')),
			"apellido"	=>	strtoupper($this->input->post('apellido')),
			"telefono"	=>	$this->input->post('telefono'),
			"email"		=>	$this->input->post('correo'),
			"password"	=>	$this->encryptPassword($this->input->post('password')),
			"estatus"	=>	$this->input->post('estatus')];

		$mensaje = ["id" 	=> 'Éxito',
					"desc"	=> 'Usuario actualizado correctamente',
					"type"	=> 'success'];
		$this->jsonResponse($mensaje);
	}

	public function delete_user(){
		$this->db->query("Delete FROM usuarios WHERE id_usuario = ".$this->input->post('id_usuario'));
		$mensaje = ["id" 	=> 'Éxito',
					"desc"	=> 'Usuario eliminado correctamente',
					"type"	=> 'success'];
		$this->jsonResponse($mensaje);
	}

	public function get_usuario($id){
		$data["title"]="INFORMACIÓN DEL USUARIO";
		$data["usuario"] = $this->user_md->showUser(NULL, $id);
		$data["password"]= $this->showPassword($data["usuario"]->password);//Para mostrar la contraseña
		$data["view"] =$this->load->view("Admin/show_usuario", $data, TRUE);
		$this->jsonResponse($data);
	}

	public function print_usuarios(){
		ini_set("memory_limit", "-1");
		$this->load->library("excelfile");
		$hoja = $this->excelfile->getActiveSheet();
		$hoja->getDefaultStyle()
		    ->getBorders()
		    ->getTop()
		        ->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
		$hoja->getDefaultStyle()
		    ->getBorders()
		    ->getBottom()
		        ->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
		$hoja->getDefaultStyle()
		    ->getBorders()
		    ->getLeft()
		        ->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
		$hoja->getDefaultStyle()
		    ->getBorders()
		    ->getRight()
		        ->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);

		$this->cellStyle("A1:D1", "000000", "FFFFFF", TRUE, 12, "Franklin Gothic Book");
		$border_style= array('borders' => array('right' => array('style' =>
			PHPExcel_Style_Border::BORDER_THIN,'color' => array('argb' => '000000'),)));

		$hoja->setCellValue("A1", "EMPRESA")->getColumnDimension('B')->setWidth(40);
		$hoja->setCellValue("B1", "NOMBRE")->getColumnDimension('C')->setWidth(40);
		$hoja->setCellValue("C1", "EMAIL")->getColumnDimension('D')->setWidth(40);
		$hoja->setCellValue("D1", "CONTRASEÑA")->getColumnDimension('E')->setWidth(30);
		$cotizacionesProveedor = $this->user_md->getUsuarios();
		$row_print =2;
		if ($cotizacionesProveedor){
			foreach ($cotizacionesProveedor as $key => $row){

				$hoja->setCellValue("A{$row_print}", $row->nombre);
				$hoja->setCellValue("B{$row_print}", $row->apellido);//Formto de moneda
				$hoja->setCellValue("C{$row_print}", $row->email);
				if ($row->grupo <> 'AZTECA') {
					$hoja->setCellValue("D{$row_print}", $this->showPassword($row->password));
				}
				$hoja->getStyle("A{$row_print}")->applyFromArray($border_style);
				$hoja->getStyle("B{$row_print}")->applyFromArray($border_style);
				$hoja->getStyle("C{$row_print}")->applyFromArray($border_style);
				$hoja->getStyle("D{$row_print}")->applyFromArray($border_style);
				$row_print ++;
			}
		}

		$file_name = "Proveedores.xlsx"; //Nombre del documento con extención
		header("Content-Type: application/vnd.ms-excel; charset=utf-8");
		header("Content-Disposition: attachment;filename=".$file_name);
		header("Cache-Control: max-age=0");
		$excel_Writer = PHPExcel_IOFactory::createWriter($this->excelfile, "Excel2007");
		$excel_Writer->save("php://output");
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
		if (isset($_POST['email']) && isset($_POST['password'])) {

			$where=["email"	=>	$this->input->post('email'),
					"password"	=>	$this->encryptPassword($this->input->post('password'))];
			$validar = $this->user_md->login($where)[0];
			if(sizeof($validar) > 0){
				$values=[	"id_usuario"=>	$validar->id_usuario,
							"id_grupo"	=>	$validar->id_grupo,
							"nombre"	=>	$validar->nombre,
							"nombres"	=>	explode(' ', $validar->nombre, 3),
							"password"	=>	$validar->password,
							"email"		=>	$validar->email,
							"estatus"	=>	$validar->estatus ];
				$this->session->set_userdata("username", $values['nombre']);
				$this->session->set_userdata($values);
				$user = $this->session->userdata();
				echo true;
			}else{
				echo false;
			}
		}
	}

	public function allpedid(){
		$user = $this->session->userdata();
		$data["title"]="LISTADO EXISTENCIAS GENERAL";
		$data["noprod"] = $this->pr_md->getAllCount(NULL)[0];
		$data["cuantas"] = $this->ex_lun_md->getAllTienda(NULL,$user["id_usuario"])[0];
		$data["existencias"] = $this->ex_lun_md->getAllExist(NULL,$user["id_usuario"]);
		$data["existenciasnot"] = $this->ex_lun_md->getAllExistNot(NULL,$user["id_usuario"]);
		
		$this->jsonResponse($data);
	}
}
