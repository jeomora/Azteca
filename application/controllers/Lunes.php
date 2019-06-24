<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Lunes extends MY_Controller {

	function __construct(){
		parent::__construct();
		$this->load->model("Usuarios_model", "user_md");
		$this->load->model("Cambios_model", "cambio_md");
		$this->load->model("Prove_model", "prove_md");
		$this->load->model("Prolunes_model", "prolu_md");
	}

	public function index(){
		$data['links'] = [
			'/assets/css/plugins/dataTables/dataTables.bootstrap',
			'/assets/css/plugins/dataTables/dataTables.responsive',
			'/assets/css/plugins/dataTables/dataTables.tableTools.min',
			'/assets/css/plugins/dataTables/buttons.dataTables.min',
		];

		$data['scripts'] = [
			'/scripts/provel',
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
		$this->estructura("Lunes/table_proveedores", $data);
	}

	public function proveedores(){
		$data['links'] = [
			'/assets/css/plugins/dataTables/dataTables.bootstrap',
			'/assets/css/plugins/dataTables/dataTables.responsive',
			'/assets/css/plugins/dataTables/dataTables.tableTools.min',
			'/assets/css/plugins/dataTables/buttons.dataTables.min',
		];

		$data['scripts'] = [
			'/scripts/provel',
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

		$data["proveedores"] = $this->prove_md->getProveedores();
		$this->estructura("Lunes/table_proveedores", $data);
	}

	public function new_proveedor(){
		$data["title"]="REGISTRAR PROVEEDOR";
		$user = $this->session->userdata();
		$data["view"] = $this->load->view("Lunes/new_proveedor", $data, TRUE);
		$data["button"]="<button class='btn btn-success new_proveedor' type='button'>
							<span class='bold'><i class='fa fa-floppy-o'></i></span> &nbsp;Guardar
						</button>";
		$this->jsonResponse($data);
	}

	public function save_prove(){
		$proveedor = [
			"nombre"	=>	strtoupper($this->input->post('nombre')),
			"alias"	=>	strtoupper($this->input->post('apellido')),
		];
		$getUsuario = $this->prove_md->get(NULL, ['nombre'=>$proveedor['nombre']])[0];

		if(sizeof($getUsuario) == 0){
			$data ['id_proveedor'] = $this->prove_md->insert($proveedor);
			$mensaje = ["id" 	=> 'Éxito',
						"desc"	=> 'Proveedor registrado correctamente',
						"type"	=> 'success'];
			$user = $this->session->userdata();
			$cambios = [
				"id_usuario" => $user["id_usuario"],
				"fecha_cambio" => date('Y-m-d H:i:s'),
				"antes" => "Proveedor Lunes es nuevo",
				"despues" => "Nombre : ".$proveedor['nombre']." /Alias: ".$proveedor['alias']];
			$data['cambios'] = $this->cambio_md->insert($cambios);
		}else{
			$mensaje = [
				"id" 	=> 'Alerta',
				"desc"	=> 'El Proveedor ['.$proveedor['nombre'].'] está registrado en el Sistema',
				"type"	=> 'warning'
			];
		}
		$this->jsonResponse($mensaje);
	}

	public function prove_update($id){
		$data["title"]="ACTUALIZAR DATOS DEL PROVEEDOR";
		$data["proveedor"] = $this->prove_md->get(NULL, ['id_proveedor'=>$id])[0];
		$user = $this->session->userdata();
		$data["view"] =$this->load->view("Lunes/edit_proveedor", $data, TRUE);
		$data["button"]="<button class='btn btn-success update_proveedor' type='button'>
							<span class='bold'><i class='fa fa-floppy-o'></i></span> &nbsp;Guardar cambios
						</button>";
		$this->jsonResponse($data);
	}

	public function update_prove(){
		$user = $this->session->userdata();
		$antes = $this->prove_md->get(NULL, ['id_proveedor'=>$this->input->post('id_proveedor')])[0];

		$proveedor = [
			"nombre"	=>	strtoupper($this->input->post('nombre')),
			"alias"	=>	strtoupper($this->input->post('apellido')),
		];

		$data ['id_proveedor'] = $this->prove_md->update($proveedor, $this->input->post('id_proveedor'));
		$cambios = [
				"id_usuario" => $user["id_usuario"],
				"fecha_cambio" => date('Y-m-d H:i:s'),
				"antes" => "Nombre : ".$antes->nombre." /Alias: ".$antes->alias,
				"despues" => "Nombre : ".$proveedor['nombre']." /Alias: ".$proveedor['alias']];
		$data['cambios'] = $this->cambio_md->insert($cambios);
		$mensaje = ["id" 	=> 'Éxito',
					"desc"	=> 'Proveedor actualizado correctamente',
					"type"	=> 'success'];
		$this->jsonResponse($mensaje);
	}

	public function prove_delete($id){
		$data["title"]="PROVEEDOR A ELIMINAR";
		$data["proveedor"] = $this->prove_md->get(NULL, ['id_proveedor'=>$id])[0];
		$data["view"] = $this->load->view("Lunes/delete_proveedor", $data,TRUE);
		$data["button"]="<button class='btn btn-danger delete_proveedor' type='button'>
							<span class='bold'><i class='fa fa-times'></i></span> &nbsp;Estoy segura(o) de eliminar
						</button>";
		$this->jsonResponse($data);
	}

	public function delete_prove(){
		$user = $this->session->userdata();
		$antes = $this->prove_md->get(NULL, ['id_proveedor'=>$this->input->post('id_proveedor')])[0];
		$cambios = [
				"id_usuario" => $user["id_usuario"],
				"fecha_cambio" => date('Y-m-d H:i:s'),
				"antes" => "Nombre : ".$antes->nombre." /Alias: ".$antes->alias,
				"despues" => "El Proveedor fue eliminado, se puede recuperar desde la BD"];
		$data['cambios'] = $this->cambio_md->insert($cambios);
		$data ['id_usuario'] = $this->prove_md->update(["estatus" => 0], $this->input->post('id_proveedor'));
		$mensaje = ["id" 	=> 'Éxito',
					"desc"	=> 'Proveedor eliminado correctamente',
					"type"	=> 'success'];
		$this->jsonResponse($mensaje);
	}

	public function productos(){
		$data['links'] = [
			'/assets/css/plugins/dataTables/dataTables.bootstrap',
			'/assets/css/plugins/dataTables/dataTables.responsive',
			'/assets/css/plugins/dataTables/dataTables.tableTools.min',
			'/assets/css/plugins/dataTables/buttons.dataTables.min',
		];

		$data['scripts'] = [
			'/scripts/produl',
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

		$data["productos"] = $this->prolu_md->getProductos();
		$this->estructura("Lunes/table_productos", $data);
	}

	public function new_producto(){
		$data["title"]="REGISTRAR PRODUCTO";
		$user = $this->session->userdata();
		$data["proveedores"] = $this->prove_md->getProveedores();
		$data["view"] = $this->load->view("Lunes/new_producto", $data, TRUE);
		$data["button"]="<button class='btn btn-success new_producto' type='button'>
							<span class='bold'><i class='fa fa-floppy-o'></i></span> &nbsp;Guardar
						</button>";
		$this->jsonResponse($data);
	}

	public function save_prod(){
		$producto = [
			"codigo"	=>	strtoupper($this->input->post('codigo')),
			"descripcion"	=>	strtoupper($this->input->post('descripcion')),
			"precio"	=>	$this->input->post('precio'),
			"sistema"	=>	$this->input->post('sistema'),
			"id_proveedor"	=>	$this->input->post('id_proveedor'),
			"unidad"	=>	$this->input->post('unidad'),
		];
		$getProducto = $this->prolu_md->get(NULL, ['codigo'=>$producto['codigo']])[0];

		if(sizeof($getProducto) == 0){
			$data['codigo'] = $this->prolu_md->insert($producto);
			$mensaje = ["id" 	=> 'Éxito',
						"desc"	=> 'Producto registrado correctamente',
						"type"	=> 'success'];
			$user = $this->session->userdata();
			$cambios = [
				"id_usuario" => $user["id_usuario"],
				"fecha_cambio" => date('Y-m-d H:i:s'),
				"antes" => "Producto Lunes es nuevo",
				"despues" => "Código : ".$producto['codigo']." /Descripción: ".$producto['descripcion']];
			$data['cambios'] = $this->cambio_md->insert($cambios);
		}else{
			$mensaje = [
				"id" 	=> 'Alerta',
				"desc"	=> 'El Producto ['.$producto['nombre'].'] está registrado en el Sistema',
				"type"	=> 'warning'
			];
		}
		$this->jsonResponse($mensaje);
	}

	public function prod_delete($id){
		$data["title"]="PRODUCTO A ELIMINAR";
		$data["producto"] = $this->prolu_md->get(NULL, ['codigo'=>$id])[0];
		$data["proveedor"] = $this->prove_md->get(NULL, ["id_proveedor"=>$data["producto"]->id_proveedor])[0];
		$data["view"] = $this->load->view("Lunes/delete_producto", $data,TRUE);
		$data["button"]="<button class='btn btn-danger delete_proveedor' type='button'>
							<span class='bold'><i class='fa fa-times'></i></span> &nbsp;Estoy segura(o) de eliminar
						</button>";
		$this->jsonResponse($data);
	}

	public function delete_prod(){
		$user = $this->session->userdata();
		$antes = $this->prolu_md->get(NULL, ['codigo'=>$this->input->post('codigo')])[0];
		$cambios = [
				"id_usuario" => $user["id_usuario"],
				"fecha_cambio" => date('Y-m-d H:i:s'),
				"antes" => "Código : ".$antes->codigo." /Descripción: ".$antes->descripcion,
				"despues" => "El Producto fue eliminado, se puede recuperar desde la BD"];
		$data['cambios'] = $this->cambio_md->insert($cambios);
		$data ['id_usuario'] = $this->prolu_md->update(["estatus" => 0], $this->input->post('codigo'));
		$mensaje = ["id" 	=> 'Éxito',
					"desc"	=> 'Producto eliminado correctamente',
					"type"	=> 'success'];
		$this->jsonResponse($mensaje);
	}

	public function prod_update($id){
		$data["title"]="ACTUALIZAR DATOS DEL PRODUCTO";
		$data["producto"] = $this->prolu_md->get(NULL, ['codigo'=>$id])[0];
		$data["proveedores"] = $this->prove_md->getProveedores();
		$user = $this->session->userdata();
		$data["view"] =$this->load->view("Lunes/edit_producto", $data, TRUE);
		$data["button"]="<button class='btn btn-success update_producto' type='button'>
							<span class='bold'><i class='fa fa-floppy-o'></i></span> &nbsp;Guardar cambios
						</button>";
		$this->jsonResponse($data);
	}

	public function update_prod(){
		$user = $this->session->userdata();
		$antes = $this->prolu_md->get(NULL, ['codigo'=>$this->input->post('codigos')])[0];

		$producto = [
			"codigo"	=>	strtoupper($this->input->post('codigo')),
			"descripcion"	=>	strtoupper($this->input->post('descripcion')),
			"precio"	=>	$this->input->post('precio'),
			"sistema"	=>	$this->input->post('sistema'),
			"id_proveedor"	=>	$this->input->post('id_proveedor'),
			"unidad"	=>	$this->input->post('unidad'),
		];

		$data ['codigo'] = $this->prolu_md->update($producto, $this->input->post('codigos'));
		$cambios = [
				"id_usuario" => $user["id_usuario"],
				"fecha_cambio" => date('Y-m-d H:i:s'),
				"antes" => "Código : ".$antes->codigo." /Descripción: ".$antes->descripcion,
				"despues" => "Código : ".$producto['codigo']." /Descripción: ".$producto['descripcion']];
		$data['cambios'] = $this->cambio_md->insert($cambios);
		$mensaje = ["id" 	=> 'Éxito',
					"desc"	=> 'Producto actualizado correctamente',
					"type"	=> 'success'];
		$this->jsonResponse($mensaje);
	}


}

/* End of file Familias.php */
/* Location: ./application/controllers/Familias.php */
