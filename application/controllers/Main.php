<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Main extends MY_Controller {

	function __construct(){
		parent::__construct();
		$this->load->model("Familias_model", "fam_md");
		$this->load->model("Productos_model", "pr_md");
		$this->load->model("Cotizaciones_model", "cot_md");
		$this->load->model("Cotizacionesback_model", "cotb_md");
		$this->load->model("Usuarios_model", "user_md");
		$this->load->model("Cambios_model", "cambio_md");
		$this->load->model("Faltantes_model", "falt_mdl");
	}

	//Primera función que carga el dashboard
	public function index(){
		$user = $this->session->userdata();//Trae los datos del usuario

		$data["proveedores"]=$this->user_md->getUsuarios(['usuarios.id_grupo'=>2]);//Son proveedores
		$data["productos"]=$this->pr_md->get(NULL,["estatus <>"=>0]);
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
		$data["dias"] = array("DOMINGO","LUNES","MARTES","MIÉRCOLES","JUEVES","VIERNES","SÁBADO");
		$data["meses"] = array("ENERO","FEBRERO","MARZO","ABRIL","MAYO","JUNIO","JULIO","AGOSTO","SEPTIEMBRE","OCTUBRE","NOVIEMBRE","DICIEMBRE");
		$data["cotizados"] = $this->pr_md->getCotizadillos();
		$data["view"] = $this->load->view("Cotizaciones/no_cotizados", $data,TRUE);
		$this->jsonResponse($data);
	}

	public function getNotCotizo(){
		$data["title"]="PROVEEDORES SIN COTIZAR";
		$data["dias"] = array("DOMINGO","LUNES","MARTES","MIÉRCOLES","JUEVES","VIERNES","SÁBADO");
		$data["meses"] = array("ENERO","FEBRERO","MARZO","ABRIL","MAYO","JUNIO","JULIO","AGOSTO","SEPTIEMBRE","OCTUBRE","NOVIEMBRE","DICIEMBRE");
		$data["cotizados"] = $this->user_md->getCotizadillos();
		$data["view"] = $this->load->view("Cotizaciones/no_cotizo", $data,TRUE);
		$this->jsonResponse($data);
	}

	public function cambioContra(){
		$data["title"]="CAMBIO CONTRASEÑA";
		$data["usuario"] = $this->user_md->get(NULL, ["id_usuario"=>$this->session->userdata('id_usuario')])[0];
		$data["view"] = $this->load->view("Structure/contrasena", $data,TRUE);
		$data["button"]="<button class='btn btn-success update_usuario' type='button'>
							<span class='bold'><i class='fa fa-floppy-o'></i></span> &nbsp;Guardar cambios
						</button>";
		$this->jsonResponse($data);
	}
	public function update_user(){
		$user = $this->session->userdata();
		$antes = $this->user_md->get(NULL, ['id_usuario'=>$this->input->post('id_usuario')])[0];
		$usuario = [
			"password"	=>	$this->encryptPassword($this->input->post('password')),
			];

		$data ['id_usuario'] = $this->user_md->update($usuario, $this->input->post('id_usuario'));
		$cambios = [
				"id_usuario" => $user["id_usuario"],
				"fecha_cambio" => date('Y-m-d H:i:s'),
				"antes" => "Usuario cambia su contraseña ",
				"despues" => "Password: ".$this->input->post('password')];
		$data['cambios'] = $this->cambio_md->insert($cambios);
		$mensaje = ["id" 	=> 'Éxito',
					"desc"	=> 'contraseña actualizada correctamente',
					"type"	=> 'success'];
		$this->jsonResponse($mensaje);
	}

	public function getCotzUsuario($ides){
		$fecha = new DateTime(date('Y-m-d H:i:s'));
		$intervalo = new DateInterval('P2D');
		$fecha->add($intervalo);
		$semana = $this->weekNumber($fecha->format('Y-m-d H:i:s')) -1;
		$data["prueba"] = $semana;
		$data["title"]="PRODUCTOS COTIZADOS EN LA ANTERIOR SEMANA";
		$data["cotizaciones"] =  $this->cot_md->getAnterior(['cotizaciones.id_proveedor'=>$ides,'WEEKOFYEAR(cotizaciones.fecha_registro)' => 52]);
		$data["view"] = $this->load->view("Cotizaciones/get_cotz", $data,TRUE);
		$data["button"]="<button class='btn btn-success repeat_cotizacion' id='repeat_cot' type='button' data-id-cot='".$ides."'>
							<span class='bold'><i class='fa fa-floppy-o'></i></span> &nbsp;Repetir precios
						</button>";
		$this->jsonResponse($data);
	}

	public function repeat_cotizacion(){
		$fecha = new DateTime(date('Y-m-d H:i:s'));
		$intervalo = new DateInterval('P3D');
		$fecha->add($intervalo);
		$semana = $this->weekNumber($fecha->format('Y-m-d H:i:s')) -1;
		$user = $this->session->userdata();

		$cotizaciones =  $this->cot_md->getAnterior(['cotizaciones.id_proveedor'=>$this->input->post('id_proveedor'),'WEEKOFYEAR(cotizaciones.fecha_registro)' => 52]);
		$i = 0;
		$new_cotizacion = null;
		if ($cotizaciones){
			foreach ($cotizaciones as $key => $value){
				$antes =  $this->falt_mdl->get(NULL, ['id_producto' => $value->id_producto, 'fecha_termino > ' => date("Y-m-d H:i:s"), 'id_proveedor' => $this->input->post('id_proveedor')])[0];
				$fecha = new DateTime(date('Y-m-d H:i:s'));
				$intervalo = new DateInterval('P3D');
				$num_one = $value->num_one == '' ? 0 : $value->num_one;
				$num_two = $value->num_two == '' ? 0 : $value->num_two;
				$descuento = $value->descuento == '' ? 0 : $value->descuento;
				$fecha->add($intervalo);
				if($antes){
					$new_cotizacion[$i] = [
						"id_proveedor"		=>	$this->input->post('id_proveedor'),
						"id_producto"		=>	$value->id_producto,
						"precio"			=>	$value->precio,
						"num_one"			=>	$value->num_one,
						"num_two"			=>	$value->num_two,
						"descuento"			=>	$value->descuento,
						"precio_promocion"	=>	$value->precio_promocion,
						"fecha_registro"	=>	$fecha->format('Y-m-d H:i:s'),
						"observaciones"		=>	strtoupper($value->observaciones),
						'estatus' => 0
					];
				}else{
					$new_cotizacion[$i] = [
						"id_producto"		=>	$value->id_producto,
						"id_proveedor"		=>	$this->input->post('id_proveedor'),
						"precio"			=>	$value->precio,
						"num_one"			=>	$value->num_one,
						"num_two"			=>	$value->num_two,
						"descuento"			=>	$value->descuento,
						"precio_promocion"	=>	$value->precio_promocion,
						"fecha_registro"	=>	$fecha->format('Y-m-d H:i:s'),
						"observaciones"		=>	strtoupper($value->observaciones),
						'estatus' => 1
					];
				}

				$i++;
			}
		}
		if (sizeof($new_cotizacion) > 0) {
			$data['cotizacion']=$this->cot_md->insert_batch($new_cotizacion);
			$data['cotizacin']=$this->cotb_md->insert_batch($new_cotizacion);
			$aprov = $this->user_md->get(NULL, ['id_usuario'=>$this->input->post('id_proveedor')])[0];
			$cambios = [
				"id_usuario" => $user["id_usuario"],
				"fecha_cambio" => date('Y-m-d H:i:s'),
				"antes" => "Repite cotizacion",
				"despues" => "Del proveedor ".$aprov->nombre];
			$data['cambios'] = $this->cambio_md->insert($cambios);
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
