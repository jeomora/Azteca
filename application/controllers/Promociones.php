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
		$data["promociones"] = $this->prom_mdl->getPromociones($where);
		$this->load->view("Promociones/table_promociones", $data, FALSE);
	}

	public function add_promocion(){
		$data["title"]="Registrar promociones";
		$this->load->view("Structure/header_modal", $data);
		$data["productos"] = $this->prod_mdl->get("id_producto, nombre");
		$this->load->view("Promociones/new_promocion", $data);
		$this->load->view("Structure/footer_modal_save");
	}

	public function accion($param){
		$promocion = [
			'id_producto'		=>	$this->input->post('id_producto'),
			'id_proveedor'		=>	$this->ion_auth->user()->row()->id,
			'precio_inicio'		=>	str_replace(',', '', $this->input->post('precio_desde')),
			'precio_fin'		=>	str_replace(',', '', $this->input->post('precio_hasta')),
			'precio_fijo'		=>	str_replace(',', '', $this->input->post('precio_producto')),
			'descuento'			=>	str_replace(',', '', $this->input->post('porcentaje')),
			'precio_descuento'	=>	str_replace(',', '', $this->input->post('precio_descuento')),
			'fecha_registro'	=>	date('Y-m-d h:i:s', strtotime($this->input->post('fecha'))),
			'fecha_caduca'		=>	date('Y-m-d', strtotime($this->input->post('fecha_vence'))),
			'existencias'		=>	$this->input->post('existencias'),
			'observaciones'		=>	$this->input->post('observaciones')
		];
		switch ($param) {
			case (substr($param, 0, 1) === 'I'):
				$data ['id_promocion']=$this->prom_mdl->insert($promocion);
				$mensaje = [
					"id" 	=> 'Éxito',
					"desc"	=> 'Promoción registrada correctamente',
					"type"	=> 'success'
				];
				break;

			case (substr($param, 0, 1) === 'U'):
				$data ['id_promocion'] = $this->prom_mdl->update($promocion, $this->input->post('id_promocion'));
				$mensaje = [
					"id" 	=> 'Éxito',
					"desc"	=> 'Promoción actualizada correctamente',
					"type"	=> 'success'
				];
				break;

			default:
				$data ['id_promocion'] = $this->prom_mdl->update(["estatus" => 0], $this->input->post('id_promocion'));
				$mensaje = [
					"id" 	=> 'Éxito',
					"desc"	=> 'Promoción eliminada correctamente',
					"type"	=> 'success'
				];
				break;
		}
		$this->jsonResponse($mensaje);
	}

	public function update_promocion($id){
		$data["title"]="Actualizar promoción";
		$this->load->view("Structure/header_modal", $data);
		$data["promocion"] = $this->prom_mdl->get(NULL, ['id_promocion'=>$id])[0];
		$data["productos"] = $this->prod_mdl->get("id_producto, nombre");
		$this->load->view("Promociones/edit_promocion", $data);
		$this->load->view("Structure/footer_modal_edit");
	}

	public function delete_promocion($id){
		$data["title"]="Promoción a eliminar";
		$this->load->view("Structure/header_modal", $data);
		$data["promocion"] = $this->prom_mdl->get(NULL, ['id_promocion'=>$id])[0];
		$data["producto"] = $this->prod_mdl->get(NULL, ['id_producto'=>$data["promocion"]->id_producto])[0];
		$this->load->view("Promociones/delete_promocion", $data);
		$this->load->view("Structure/footer_modal_delete");
	}


}

/* End of file Promociones.php */
/* Location: ./application/controllers/Promociones.php */