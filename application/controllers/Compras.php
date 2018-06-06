<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Compras extends MY_Controller {

	function __construct(){
		parent::__construct();
		$this->load->model("Usuarios_model", "user_md");
		$this->load->model("Cambios_model", "cambio_md");
		$this->load->model("Grupos_model", "gr_md");
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

		$data["usuarios"] = $this->user_md->getUsuarios();
		$this->estructura("Admin/table_usuarios", $data);
	}

	public function login(){
		if($this->session->userdata("username")){
			$user = $this->session->userdata();
			if($user['id_grupo'] ==2){
				redirect("cotizaciones/", $data);
			}else{
				redirect("Main/", $data);
			}

		}
		$this->data["message"] =NULL;
		if (isset($_POST['email']) && isset($_POST['password'])) {
			$where=["email"		=>	$this->input->post('email'),
					"password"	=>	$this->encryptPassword($this->input->post('password'))];
			$validar = $this->user_md->login($where)[0];
			if(sizeof($validar) > 0){
				$values=[	"id_usuario"=>	$validar->id_usuario,
							"id_grupo"	=>	$validar->id_grupo,
							"nombre"	=>	$validar->nombre,
							"apellido"	=>	$validar->apellido,
							"telefono"	=>	$validar->telefono,
							"email"		=>	$validar->email,
							"password"	=>	$validar->password,
							"estatus"	=>	$validar->estatus ];
				$this->session->set_userdata("username", $values['nombre']);
				$this->session->set_userdata($values);
				$user = $this->session->userdata();
				if($user['id_grupo'] ==2){
					redirect("cotizaciones/", $data);
				}else{
					redirect("Main/", $data);
				}
			}else{
				$this->data['message']='Usuario y/o contraseña incorrectos';
			}
		}
		$this->estructura_login("Admin/login", $this->data, FALSE);
	}

	public function logout(){
		$this->session->sess_destroy();
		redirect("Compras/login/", "refresh");
	}

	public function new_usuario(){
		$data["title"]="REGISTRAR USUARIOS";
		$data["grupos"] = $this->gr_md->get();
		$user = $this->session->userdata();
		$data["grupo"] = $user['id_grupo'];
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
		$data["grupos"] = $this->gr_md->get();
		$user = $this->session->userdata();
		$data["grupo"] = $user['id_grupo'];
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
		$gr = $this->input->post('id_grupo');
		if ($gr <> 2) {
			$conjunto = "";
		}else{
			$conjunto = $this->input->post('conjunto');
		}
		$usuario = [
			"nombre"	=>	strtoupper($this->input->post('nombre')),
			"apellido"	=>	strtoupper($this->input->post('apellido')),
			"telefono"	=>	$this->input->post('telefono'),
			"email"		=>	$this->input->post('correo'),
			"password"	=>	$this->encryptPassword($this->input->post('password')),
			"id_grupo"	=>	$this->input->post('id_grupo'),
			"conjunto"	=>	$conjunto];

		$getUsuario = $this->user_md->get(NULL, ['email'=>$usuario['email']])[0];

		if(sizeof($getUsuario) == 0){
			$data ['id_usuario'] = $this->user_md->insert($usuario);
			$mensaje = ["id" 	=> 'Éxito',
						"desc"	=> 'Usuario registrado correctamente',
						"type"	=> 'success'];
			$user = $this->session->userdata();
			$cambios = [
				"id_usuario" => $user["id_usuario"],
				"fecha_cambio" => date('Y-m-d H:i:s'),
				"antes" => "Usuario es nuevo",
				"despues" => "Nombre : ".$usuario['nombre']." /Apellido: ".$usuario['apellido']." /Teléfono:".$usuario['telefono']." /Email: ".$usuario['email']." /Password: ".$this->input->post('password')." /Grupo: ".$usuario['id_grupo']];
			$data['cambios'] = $this->cambio_md->insert($cambios);
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
		$user = $this->session->userdata();
		$antes = $this->user_md->get(NULL, ['id_usuario'=>$this->input->post('id_usuario')])[0];
		$gr = $this->input->post('id_grupo');
		if ($gr <> 2) {
			$conjunto = "";
		}else{
			$conjunto = $this->input->post('conjunto');
		}
		$usuario = [
			"nombre"	=>	strtoupper($this->input->post('nombre')),
			"apellido"	=>	strtoupper($this->input->post('apellido')),
			"telefono"	=>	$this->input->post('telefono'),
			"email"		=>	$this->input->post('correo'),
			"password"	=>	$this->encryptPassword($this->input->post('password')),
			"id_grupo"	=>	$this->input->post('id_grupo'),
			"conjunto"	=>	$conjunto];

		$data ['id_usuario'] = $this->user_md->update($usuario, $this->input->post('id_usuario'));
		$cambios = [
				"id_usuario" => $user["id_usuario"],
				"fecha_cambio" => date('Y-m-d H:i:s'),
				"antes" => "Nombre : ".$antes->nombre." /Apellido: ".$antes->apellido." /Teléfono:".$antes->telefono." /Email: ".$antes->email." /Password: ".$antes->password." /Grupo: ".$antes->id_grupo,
				"despues" => "Nombre : ".$usuario['nombre']." /Apellido: ".$usuario['apellido']." /Teléfono:".$usuario['telefono']." /Email: ".$usuario['email']." /Password: ".$this->input->post('password')." /Grupo: ".$usuario['id_grupo']];
		$data['cambios'] = $this->cambio_md->insert($cambios);
		$mensaje = ["id" 	=> 'Éxito',
					"desc"	=> 'Usuario actualizado correctamente',
					"type"	=> 'success'];
		$this->jsonResponse($mensaje);
	}

	public function delete_user(){
		$user = $this->session->userdata();
		$antes = $this->user_md->get(NULL, ['id_usuario'=>$this->input->post('id_usuario')])[0];
		$cambios = [
				"id_usuario" => $user["id_usuario"],
				"fecha_cambio" => date('Y-m-d H:i:s'),
				"antes" => "Nombre : ".$antes->nombre." /Apellido: ".$antes->apellido." /Teléfono:".$antes->telefono." /Email: ".$antes->email." /Password: ".$antes->password." /Grupo: ".$antes->id_grupo,
				"despues" => "El usuario fue eliminado, se puede recuperar desde la BD"];
		$data['cambios'] = $this->cambio_md->insert($cambios);
		$data ['id_usuario'] = $this->user_md->update(["estatus" => 0], $this->input->post('id_usuario'));
		$mensaje = ["id" 	=> 'Éxito',
					"desc"	=> 'Usuario eliminado correctamente',
					"type"	=> 'success'];
		$this->jsonResponse($mensaje);
	}

	public function get_usuario($id){
		$data["title"]="INFORMACIÓN DEL USUARIO";
		$data["usuario"] = $this->user_md->get(NULL, ['id_usuario'=>$id])[0];
		$data["password"]= $this->showPassword($data["usuario"]->password);//Para mostrar la contraseña
		$data["grupo"] = $this->gr_md->get(NULL,['id_grupo'=>$data["usuario"]->id_grupo])[0];
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

}
