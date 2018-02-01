<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Productos extends MY_Controller {

	function __construct(){
		parent::__construct();
		$this->load->model("Productos_model", "pro_md");
		$this->load->model("Familias_model", "fam_md");
	}

	public function index(){
		$data['links'] = [
			'/assets/css/plugins/dataTables/dataTables.bootstrap',
			'/assets/css/plugins/dataTables/dataTables.responsive',
			'/assets/css/plugins/dataTables/dataTables.tableTools.min',
			'/assets/css/plugins/dataTables/buttons.dataTables.min',
		];

		$data['scripts'] = [
			'/scripts/productos',
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
		// $data["productos"]=$this->pro_md->getProductos();
		$this->estructura("Productos/table_productos", $data);
	}

	public function productos_dataTable(){
		$search = ["productos.codigo", "productos.nombre", "fam.nombre"];

		$columns = "productos.id_producto, productos.nombre AS producto, productos.codigo, fam.nombre AS familia";

		$joins = [
			["table"	=>	"familias fam",	"ON"	=>	"productos.id_familia = fam.id_familia",	"clausula"	=>	"INNER"]
		];

		$group ="productos.id_producto";
		$order="productos.id_producto";

		$where = [["clausula"	=>	"productos.estatus",		"valor"	=>	1]];

		$productos = $this->pro_md->get_pagination($columns, $joins, $where, $search, $group, $order);

		$data =[];
		$no = $_POST["start"];
		if ($productos) {
			foreach ($productos as $key => $value) {
				$no ++;
				$row = [];
				$row[] = '<b>'.$value->id_producto.'</b>';
				$row[] = $value->codigo;
				$row[] = $value->producto;
				$row[] = $value->familia;
				$row[] = $this->column_buttons($value->id_producto);
				$data[] = $row;
			}
		}
		$salida = [
			"draw"				=>	$_POST['draw'],
			"recordsTotal"		=>	$this->pro_md->count_filtered("productos.id_producto", $where, $search, $joins),
			"recordsFiltered"	=>	$this->pro_md->count_filtered("productos.id_producto", $where, $search, $joins),
			"data" => $data];
		$this->jsonResponse($salida);
	}

	private function column_buttons($id_producto){
		$botones = "";
		$botones.='<button id="update_producto" class="btn btn-info" data-toggle="tooltip" title="Editar" data-id-producto="'.$id_producto.'">
						<i class="fa fa-pencil"></i>
					</button>';
		$botones.='&nbsp;<button id="delete_producto" class="btn btn-warning" data-toggle="tooltip" title="Eliminar" data-id-producto="'.$id_producto.'">
							<i class="fa fa-trash"></i>
						</button>';
		return $botones;
	}

	public function add_producto(){
		$data["title"]="REGISTRAR PRODUCTOS";
		$data["familias"] = $this->fam_md->get();
		$data["view"] =$this->load->view("Productos/new_producto", $data, TRUE);
		$data["button"]="<button class='btn btn-success new_producto' type='button'>
							<span class='bold'><i class='fa fa-floppy-o'></i></span> &nbsp;Guardar
						</button>";
		$this->jsonResponse($data);
	}

	public function get_update($id){
		$data["title"]="ACTUALIZAR DATOS DEL PRODUCTO";
		$data["producto"] = $this->pro_md->get(NULL, ['id_producto'=>$id])[0];
		$data["familias"] = $this->fam_md->get();
		$data["view"] =$this->load->view("Productos/edit_producto", $data, TRUE);
		$data["button"]="<button class='btn btn-success update_producto' type='button'>
							<span class='bold'><i class='fa fa-floppy-o'></i></span> &nbsp;Guardar cambios
						</button>";
		$this->jsonResponse($data);
	}

	public function get_delete($id){
		$data["title"]="PRODUCTO A ELIMINAR";
		$data["producto"] = $this->pro_md->get(NULL, ['id_producto'=>$id])[0];
		$data["view"] = $this->load->view("Productos/delete_producto", $data,TRUE);
		$data["button"]="<button class='btn btn-danger delete_producto' type='button'>
							<span class='bold'><i class='fa fa-times'></i></span> &nbsp;Aceptar
						</button>";
		$this->jsonResponse($data);
	}

	public function accion($param){
		$producto = ['codigo'	=>	$this->input->post('codigo'),
					'nombre'	=>	strtoupper($this->input->post('nombre')),
					// 'precio'	=>	$this->input->post('precio'),
					'id_familia'=>	($this->input->post('id_familia') !="-1") ? $this->input->post('id_familia') : NULL
		];
		$getProducto = $this->pro_md->get(NULL, ['codigo'=>$producto['codigo']])[0];
		switch ($param) {
			case (substr($param, 0, 1) === 'I'):
				if (sizeof($getProducto) == 0) {
					$data ['id_producto']=$this->pro_md->insert($producto);
					$mensaje = ["id" 	=> 'Éxito',
								"desc"	=> 'Producto registrado correctamente',
								"type"	=> 'success'];
				}else{
					$mensaje = ["id" 	=> 'Alerta',
								"desc"	=> 'El código ya esta registrada en el Sistema',
								"type"	=> 'warning'];
				}
				break;

			case (substr($param, 0, 1) === 'U'):
				$data ['id_producto'] = $this->pro_md->update($producto, $this->input->post('id_producto'));
				$mensaje = [
					"id" 	=> 'Éxito',
					"desc"	=> 'Producto actualizado correctamente',
					"type"	=> 'success'
				];
				break;

			default:
				$data ['id_producto'] = $this->pro_md->update(["estatus" => 0], $this->input->post('id_producto'));
				$mensaje = [
					"id" 	=> 'Éxito',
					"desc"	=> 'Producto eliminado correctamente',
					"type"	=> 'success'
				];
				break;
		}
		$this->jsonResponse($mensaje);
	}

}

/* End of file Productos.php */
/* Location: ./application/controllers/Productos.php */