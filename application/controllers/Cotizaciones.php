<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Cotizaciones extends MY_Controller {

	function __construct(){
		parent::__construct();
		$this->load->model("Cotizaciones_model", "ct_mdl");
		$this->load->model("Productos_model", "prod_mdl");
	}

	public function index(){
		$data['links'] = [
			'/assets/css/plugins/dataTables/dataTables.bootstrap',
			'/assets/css/plugins/dataTables/dataTables.responsive',
			'/assets/css/plugins/dataTables/dataTables.tableTools.min',
			'/assets/css/plugins/dataTables/buttons.dataTables.min',
		];

		$data['scripts'] = [
			'/scripts/cotizaciones',
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
		$user = $this->ion_auth->user()->row();//Obtenemos el usuario logeado 
		$where = [];
		
		if(! $this->ion_auth->is_admin()){//Solo mostrar sus Productos cuando es proveedor
			$where = ["cotizaciones.id_proveedor" => $user->id];
		}
		$data["cotizaciones"] = $this->ct_mdl->getCotizaciones($where);
		$this->estructura("Cotizaciones/table_cotizaciones", $data, FALSE);
	}

	public function add_cotizacion(){
		$data["title"]="REGISTRAR COTIZACIONES";
		$data["productos"] = $this->prod_mdl->get("id_producto, nombre");
		$data["view"]=$this->load->view("Cotizaciones/new_cotizacion", $data, TRUE);
		$data["button"]="<button class='btn btn-success new_cotizacion' type='button'>
							<span class='bold'><i class='fa fa-floppy-o'></i></span> &nbsp;Guardar
						</button>";
		$this->jsonResponse($data);
	}

	public function save(){
		$cotizacion = [
			'nombre'			=>	strtoupper($this->input->post('nombre')),
			'id_producto'		=>	$this->input->post('id_producto'),
			'id_proveedor'		=>	$this->ion_auth->user()->row()->id,
			'num_one'			=>	str_replace(',', '', $this->input->post('num_one')),
			'num_two'			=>	str_replace(',', '', $this->input->post('num_two')),
			'precio'			=>	str_replace(',', '', $this->input->post('precio')),//precio base
			'precio_promocion'	=>	($this->input->post('precio_promocion') > 0) ? str_replace(',', '', $this->input->post('precio_promocion')) : str_replace(',', '', $this->input->post('precio')),//precio con promoción
			'descuento'			=>	str_replace(',', '', $this->input->post('porcentaje')),
			'fecha_registro'	=>	date('Y-m-d H:i:s'),
			'fecha_caduca'		=>	($this->input->post('fecha_caducidad') !='') ? date('Y-m-d', strtotime($this->input->post('fecha_caducidad'))) : NULL,
			'existencias'		=>	str_replace(',', '',$this->input->post('existencias')),
			'observaciones'		=>	strtoupper($this->input->post('observaciones'))
		];
		$data ['id_cotizacion']=$this->ct_mdl->insert($cotizacion);
		$mensaje = [
			"id" 	=> 'Éxito',
			"desc"	=> 'Cotización registrada correctamente',
			"type"	=> 'success'
		];
		$this->jsonResponse($mensaje);
	}

	public function update(){
		$cotizacion = [
			'nombre'			=>	strtoupper($this->input->post('nombre')),
			'num_one'			=>	str_replace(',', '', $this->input->post('num_one')),
			'num_two'			=>	str_replace(',', '', $this->input->post('num_two')),
			'precio_nuevo'		=>	str_replace(',', '', $this->input->post('precio')),
			'precio_promocion'	=>	($this->input->post('precio_promocion') > 0) ? str_replace(',', '', $this->input->post('precio_promocion')) : str_replace(',', '', $this->input->post('precio')),
			'descuento'			=>	str_replace(',', '', $this->input->post('porcentaje')),
			'fecha_actualiza'	=>	date('Y-m-d H:i:s'),
			'fecha_caduca'		=>	date('Y-m-d', strtotime($this->input->post('fecha_caducidad'))),
			'existencias'		=>	str_replace(',', '',$this->input->post('existencias')),
			'observaciones'		=>	strtoupper($this->input->post('observaciones'))
		];
		$data ['id_cotizacion'] = $this->ct_mdl->update($cotizacion, $this->input->post('id_cotizacion'));
		$mensaje = [
			"id" 	=> 'Éxito',
			"desc"	=> 'Cotización actualizada correctamente',
			"type"	=> 'success'
		];
		$this->jsonResponse($mensaje);
	}

	public function delete(){
		$data ['id_cotizacion'] = $this->ct_mdl->update(["estatus" => 0], $this->input->post('id_cotizacion'));
		$mensaje = [
			"id" 	=> 'Éxito',
			"desc"	=> 'Cotización eliminada correctamente',
			"type"	=> 'success'
		];
		$this->jsonResponse($mensaje);
	}

	public function get_update($id){
		$data["title"]="ACTUALIZAR COTIZACIÓN";
		$data["cotizacion"] = $this->ct_mdl->getCotizaciones(['id_cotizacion'=>$id])[0];
		$data["productos"] = $this->prod_mdl->get("id_producto, nombre");
		$data["view"]=$this->load->view("Cotizaciones/edit_cotizacion", $data, TRUE);
		$data["button"]="<button class='btn btn-success update_cotizacion' type='button'>
							<span class='bold'><i class='fa fa-floppy-o'></i></span> &nbsp;Guardar cambios
						</button>";
		$this->jsonResponse($data);
	}

	public function get_delete($id){
		$data["title"]="COTIZACIÓN A ELIMINAR";
		$data["cotizacion"] = $this->ct_mdl->get(NULL, ['id_cotizacion'=>$id])[0];
		$data["producto"] = $this->prod_mdl->get(NULL, ['id_producto'=>$data["cotizacion"]->id_producto])[0];
		$data["view"]=$this->load->view("Cotizaciones/delete_cotizacion", $data, TRUE);
		$data["button"]="<button class='btn btn-danger delete_cotizacion' type='button'>
							<span class='bold'><i class='fa fa-times'></i></span> &nbsp;Aceptar
						</button>";
		$this->jsonResponse($data);
	}

	public function change_prices(){
		set_time_limit(300);
		ini_get("memory_limit");
		ini_set("memory_limit","512M");
		ini_get("memory_limit");
		$this->load->helper("path");
		$this->load->library("upload");

		$config=["upload_path"	=>	'./assets/uploads/',
				"file_name"		=>	$_FILES['file_csv']['name'],
				"allowed_types"	=>	'csv',
				"remove_spaces"	=>	TRUE,
				"overwrite"		=> TRUE
		];
		
		$this->upload->initialize($config);

		if (! $this->upload->do_upload('file_csv')){
			$mensaje= [	"id" 	=>	'Error',
						"desc"	=>	$this->upload->display_errors(),
						"type"	=>	 'error'];
		}else{
			$file_name = $this->upload->data('full_path');
			$cont =0;
			$open_file = fopen($file_name,'r') or die("No se puede abrir el archivo");
			while(($file_csv = fgetcsv($open_file, 0,",","\n")) !== FALSE){
				if($cont === 0){//Son los encabezados del archivo
				
				}else{
					$change_precios[]=[
						"nombre"	=>	$file_csv[0],
						"precio"	=>	$file_csv[1],
						"promocion"	=>	$file_csv[2]
					];
				}
				$cont++;
			}
			$mensaje=[	"id"	=>	'Éxito',
						"desc"	=>	'Precios actualizados correctamente',
						"type"	=>	'success'];
			echo "<pre>";
			print_r ($change_precios);
			echo "</pre>";
		}
		$this->jsonResponse($mensaje);

	}

}

/* End of file Cotizaciones.php */
/* Location: ./application/controllers/Cotizaciones.php */