<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Promociones extends MY_Controller {

	function __construct(){
		parent::__construct();
		$this->load->model("Productos_model", "prod_mdl");
		$this->load->model("Promociones_model", "prom_mdl");
	}

	public function promociones_view(){
		$user = $this->ion_auth->user()->row();//Obtenemos el usuario logeado 
		$where = [];

		if(! $this->ion_auth->is_admin()){//Solo mostrar sus Productos cuando es proveedor
			$where = ["promociones.id_proveedor" => $user->id];
		}
		$data['links'] = [
			'/assets/css/plugins/dataTables/dataTables.bootstrap',
			'/assets/css/plugins/dataTables/dataTables.responsive',
			'/assets/css/plugins/dataTables/dataTables.tableTools.min',
			'/assets/css/plugins/dataTables/buttons.dataTables.min',
		];

		$data['scripts'] = [
			'/scripts/promociones',
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
		$data["promociones"] = $this->prom_mdl->getPromociones($where);
		$this->estructura("Promociones/table_promociones", $data);
	}

	public function add_promocion(){
		$data["title"]="REGISTRAR PROMOCIONES";
		$data["class"]="new_promocion";
		$data["productos"] = $this->prod_mdl->get("id_producto, nombre");
		$data["view"]=$this->load->view("Promociones/new_promocion", $data, TRUE);
		$this->jsonResponse($data);
	}

	public function save(){
		$promocion = [
			'nombre'			=>	strtoupper($this->input->post('nombre')),
			'id_producto'		=>	$this->input->post('id_producto'),
			'id_proveedor'		=>	$this->ion_auth->user()->row()->id,
			'precio_inicio'		=>	str_replace(',', '', $this->input->post('precio_desde')),
			'precio_fin'		=>	str_replace(',', '', $this->input->post('precio_hasta')),
			'precio_fijo'		=>	str_replace(',', '', $this->input->post('precio_producto')),
			'descuento'			=>	str_replace(',', '', $this->input->post('porcentaje')),
			'precio_descuento'	=>	str_replace(',', '', $this->input->post('precio_descuento')),
			'fecha_registro'	=>	date('Y-m-d H:i:s', strtotime($this->input->post('fecha'))),
			'fecha_caduca'		=>	date('Y-m-d', strtotime($this->input->post('fecha_vence'))),
			'existencias'		=>	$this->input->post('existencias'),
			'observaciones'		=>	strtoupper($this->input->post('observaciones'))
		];
		$data ['id_promocion']=$this->prom_mdl->insert($promocion);
		$mensaje = [
			"id" 	=> 'Éxito',
			"desc"	=> 'Promoción registrada correctamente',
			"type"	=> 'success'
		];
		$this->jsonResponse($mensaje);
	}

	public function update(){
		$promocion = [
			'nombre'			=>	strtoupper($this->input->post('nombre')),
			'id_producto'		=>	$this->input->post('id_producto'),
			'precio_inicio'		=>	str_replace(',', '', $this->input->post('precio_desde')),
			'precio_fin'		=>	str_replace(',', '', $this->input->post('precio_hasta')),
			'precio_fijo'		=>	str_replace(',', '', $this->input->post('precio_producto')),
			'descuento'			=>	str_replace(',', '', $this->input->post('porcentaje')),
			'precio_descuento'	=>	str_replace(',', '', $this->input->post('precio_descuento')),
			'fecha_caduca'		=>	date('Y-m-d', strtotime($this->input->post('fecha_vence'))),
			'existencias'		=>	$this->input->post('existencias'),
			'observaciones'		=>	strtoupper($this->input->post('observaciones'))
		];
		$data ['id_promocion'] = $this->prom_mdl->update($promocion, $this->input->post('id_promocion'));
		$mensaje = [
			"id" 	=> 'Éxito',
			"desc"	=> 'Promoción actualizada correctamente',
			"type"	=> 'success'
		];
		$this->jsonResponse($mensaje);
	}

	public function delete(){
		$data ['id_promocion'] = $this->prom_mdl->update(["estatus" => 0], $this->input->post('id_promocion'));
		$mensaje = [
			"id" 	=> 'Éxito',
			"desc"	=> 'Promoción eliminada correctamente',
			"type"	=> 'success'
		];
		$this->jsonResponse($mensaje);
	}

	public function get_update($id){
		$data["title"]="ACTUALIZAR PROMOCIÓN";
		$data["class"]="update_promocion";
		$data["promocion"] = $this->prom_mdl->get(NULL, ['id_promocion'=>$id])[0];
		$data["productos"] = $this->prod_mdl->get("id_producto, nombre");
		$data["view"]=$this->load->view("Promociones/edit_promocion", $data, TRUE);
		$this->jsonResponse($data);
	}

	public function get_delete($id){
		$data["title"]="PROMOCIÓN A ELIMINAR";
		$data["class"]="delete_promocion";
		$data["promocion"] = $this->prom_mdl->get(NULL, ['id_promocion'=>$id])[0];
		$data["producto"] = $this->prod_mdl->get(NULL, ['id_producto'=>$data["promocion"]->id_producto])[0];
		$data["view"]=$this->load->view("Promociones/delete_promocion", $data, TRUE);
		$this->jsonResponse($data);
	}


}

/* End of file Promociones.php */
/* Location: ./application/controllers/Promociones.php */