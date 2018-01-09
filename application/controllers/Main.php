<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Main extends MY_Controller {

	function __construct(){
		parent::__construct();
		$this->load->model("Familias_model", "fam_md");
		$this->load->model("Productos_model", "pr_md");
		$this->load->model("Cotizaciones_model", "cot_md");
		$this->load->model("Proveedores_model", "prov_md");
	}

	//Primera función que carga el dashboard
	public function index(){
		$user = $this->ion_auth->user()->row();//Obtenemos el usuario logeado 
		$data["proveedores"]=$this->prov_md->getProveedores();
		$data["productos"]=$this->pr_md->get();
		$data["familias"]=$this->fam_md->get();
		$where = [];
		if(! $this->ion_auth->is_admin()){//Solo mostrar sus Productos cotizados cuando es proveedor
			$where = ["cotizaciones.id_proveedor" => $user->id];
		}
		$data["cotizaciones"] = $this->cot_md->getCotizaciones($where);
		$this->estructura("Admin/welcome", $data);
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
