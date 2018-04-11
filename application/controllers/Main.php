<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Main extends MY_Controller {

	function __construct(){
		parent::__construct();
		$this->load->model("Familias_model", "fam_md");
		$this->load->model("Productos_model", "pr_md");
		$this->load->model("Cotizaciones_model", "cot_md");
		$this->load->model("Usuarios_model", "user_md");
	}

	//Primera función que carga el dashboard
	public function index(){
		$user = $this->session->userdata();//Trae los datos del usuario
		$data["proveedores"]=$this->user_md->getUsuarios(['usuarios.id_grupo'=>2]);//Son proveedores
		$data["productos"]=$this->pr_md->get();
		$data["familias"]=$this->fam_md->get();
		$where = [];
		if($user['id_grupo'] ==2){//El grupo 2 es proveedor
			$where = [	"cotizaciones.id_proveedor"					=>	$user['id_usuario'],
						"WEEKOFYEAR(cotizaciones.fecha_registro)"	=>	$this->weekNumber()
					];
		}else{
			$where = ["WEEKOFYEAR(cotizaciones.fecha_registro)" => $this->weekNumber()];
		}
		$data["cotizaciones"] = $this->cot_md->getCotizaciones($where);
		$data["ides"] = $user['id_grupo'];
		
		$this->estructura("Admin/welcome", $data);
	}

	public function getNotCotizados(){
		$data["title"]="PRODUCTOS NO COTIZADOS";
		$data["cotizados"] = $this->pr_md->getCotizados();
		$data["view"] = $this->load->view("Cotizaciones/no_cotizados", $data,TRUE);
		$this->jsonResponse($data);
	}

	public function getNotCotizo(){
		$data["title"]="PROVEEDORES SIN COTIZAR";
		$data["cotizados"] = $this->user_md->getCotizados();
		$data["view"] = $this->load->view("Cotizaciones/no_cotizo", $data,TRUE);

		$this->jsonResponse($data);
	}


	public function uploadFoto(){
		$this->load->library("upload");
   		$this->load->helper("path");
   		$id_producto = $this->input->post('id_producto');
   		$id_categoria = $this->input->post('id_categoria');
   		$categoria = $this->input->post('categoria');

		$ruta_folder = $this->createFolder($categoria); //Se crea el folder si no existe
		
		$explode = explode(".", $_FILES['file']['name']);
		$extension = array_pop($explode);
		
		$imagen = [
			"file_name"		=>	$id_producto.'_'.$explode[0],
			"upload_path"	=>	FCPATH.$ruta_folder,
			"allowed_types"	=>	'jpg|jpeg|gif|png|',
			"overwrite"		=>	TRUE,
			"remove_spaces"	=>	TRUE
		];

		$this->upload->initialize($imagen);
		
		if (! $this->upload->do_upload('file')){
			$data = ["id"	=>	"Error",
        			"desc"	=>	$this->upload->display_errors(),
        			"type"	=>	"error"];
        }else{
            $data["resultado"] = $this->upload->data();
            $data =["id" => "Éxito",
            		"desc"	=>	"Foto cargada correctamente",
            		"type"	=>	"success"];
            $update = [
            	"ruta_imagen" => $ruta_folder,
                "nombre_imagen" => $imagen["file_name"].".".$extension];

            $this->pr_md->update($update, $id_producto);
        }
        $this->jsonResponse($data);
	}

}

/* End of file Main.php */
/* Location: ./application/controllers/Main.php */
