<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Main extends MY_Controller {

	function __construct(){
		parent::__construct();
		$this->load->model("Familias_model", "fam_md");
		$this->load->model("Productos_model", "pr_md");
		$this->load->model("Cotizaciones_model", "cot_md");
		$this->load->model("Usuarios_model", "user_md");
		$this->load->model("Cambios_model", "cambio_md");
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

	public function getCotzUsuario($ides){
		$semana = $this->weekNumber() -1;
		$data["prueba"] = $semana;
		$data["title"]="PRODUCTOS COTIZADOS EN LA ANTERIOR SEMANA";
		$data["cotizaciones"] =  $this->cot_md->getAnterior(['id_proveedor'=>$ides,'WEEKOFYEAR(fecha_registro)' => $semana]);
		$data["view"] = $this->load->view("Cotizaciones/get_cotz", $data,TRUE);
		$data["button"]="<button class='btn btn-success repeat_cotizacion' id='repeat_cot' type='button' data-id-cot='".$ides."'>
							<span class='bold'><i class='fa fa-floppy-o'></i></span> &nbsp;Repetir precios
						</button>";
		$this->jsonResponse($data);
	}

	public function repeat_cotizacion(){
		$user = $this->session->userdata();
		$semana = $this->weekNumber() -1;
		$cotizaciones =  $this->cot_md->getAnterior(['id_proveedor'=>$this->input->post('id_proveedor'),'WEEKOFYEAR(fecha_registro)' => $semana]);
		$i = 0;
		$new_cotizacion = null;
		if ($cotizaciones){
			foreach ($cotizaciones as $key => $value){
				$cambios = [
				"id_usuario" => $user["id_usuario"],
				"fecha_cambio" => date('Y-m-d H:i:s'),
				"antes" => "Repite cotizacion",
				"despues" => "Del proveedor ".$this->input->post('id_proveedor')];
				$data['cambios'] = $this->cambio_md->insert($cambios);
				$new_cotizacion[$i]=[
					"id_producto"		=>	$value->id_producto,
					"id_proveedor"		=>	$this->input->post('id_proveedor'),
					"precio"			=>	$value->precio,
					"num_one"			=>	$value->num_one,
					"num_two"			=>	$value->num_two,
					"descuento"			=>	$value->descuento,
					"precio_promocion"	=>	$value->precio_promocion,
					"fecha_registro"	=>	date('Y-m-d H:i:s'),
					"observaciones"		=>	$value->observaciones,
				];
				$i++;
			}
		}
		if (sizeof($new_cotizacion) > 0) {
			$data['cotizacion']=$this->cot_md->insert_batch($new_cotizacion);
			$mensaje=[	"id"	=>	'Éxito',
						"desc"	=>	'Cotizaciones cargadas correctamente en el Sistema',
						"type"	=>	'success'];
		}else{
			$mensaje=[	"id"	=>	'Error',
						"desc"	=>	'No hay cotizaciones de la semana pasada',
						"type"	=>	'error'];
		}
		$this->jsonResponse($mensaje);
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
