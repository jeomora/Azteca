<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Pedidos extends MY_Controller {

	function __construct(){
		parent::__construct();
		$this->load->model("Pedidos_model", "ped_mdl");
		$this->load->model("Detalles_pedidos_model", "det_ped_mdl");
		$this->load->model("Sucursales_model", "suc_mdl");
		$this->load->model("Usuarios_model", "user_mdl");
		$this->load->model("Cotizaciones_model", "ct_mdl");
		$this->load->model("Existencias_model", "ex_mdl");
	}

	public function index(){
		$user = $this->session->userdata();//Trae los datos del usuario

		$where = [];

		if($user['id_grupo'] ==2){//El grupo 2 es proveedor
			$where = ["promociones.id_proveedor" => $user['id_usuario']];
		}
		$data['links'] = [
			'/assets/css/plugins/dataTables/dataTables.bootstrap',
			'/assets/css/plugins/dataTables/dataTables.responsive',
			'/assets/css/plugins/dataTables/dataTables.tableTools.min',
			'/assets/css/plugins/dataTables/buttons.dataTables.min',
		];

		$data['scripts'] = [
			'/scripts/pedidos',
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
		$data["pedidos"] = $this->ped_mdl->getPedidos($where);
		$data["proveedores"] = $this->user_mdl->getUsuarios();
		if($user['id_grupo'] ==3){
			$this->estructura("Pedidos/pedido_tienda", $data, FALSE);
		}else{
			$this->estructura("Pedidos/table_pedidos", $data, FALSE);
		}
		
	}

	public function add_pedido(){
		$data["title"]="REGISTRAR PEDIDOS";
		$data["proveedores"] = $this->user_mdl->getUsuarios();
		$data["sucursales"] = $this->suc_mdl->get('id_sucursal, nombre');
		$data["view"]=$this->load->view("Pedidos/new_pedido", $data, TRUE);
		$data["button"]="<button class='btn btn-success new_pedido' type='button'>
							<span class='bold'><i class='fa fa-floppy-o'></i></span> &nbsp;Guardar
						</button>";
		$this->jsonResponse($data);
	}

	public function get_update($id){
		$data["title"]="ACTUALIZAR DATOS DEL PEDIDO";
		$data["pedido"] = $this->ped_mdl->get(NULL, ['id_pedido'=>$id])[0];
		$data["sucursales"] = $this->suc_mdl->get('id_sucursal, nombre');
		$data["proveedores"] = $this->user_mdl->getUsuarios();
		$data["detallePedido"] = $this->det_ped_mdl->getDetallePedido(["detalles_pedidos.id_pedido"=>$data["pedido"]->id_pedido]);
		$data["view"]=$this->load->view("Pedidos/edit_pedido", $data, TRUE);
		$data["button"]="<button class='btn btn-success update_pedido' type='button'>
							<span class='bold'><i class='fa fa-floppy-o'></i></span> &nbsp;Guardar cambios
						</button>";
		$this->jsonResponse($data);
	}

	public function get_delete($id){
		$data["title"]="PEDIDO A ELIMINAR";
		$data["pedido"] = $this->ped_mdl->get(NULL, ['id_pedido'=>$id])[0];
		$data["proveedor"] = $this->user_mdl->getUsuarios(['users.id' => $data['pedido']->id_proveedor])[0];
		$data["view"]=$this->load->view("Pedidos/delete_pedido", $data, TRUE);
		$data["button"]="<button class='btn btn-danger delete_pedido' type='button'>
							<span class='bold'><i class='fa fa-times'></i></span> &nbsp;Aceptar
						</button>";
		$this->jsonResponse($data);
	}

	public function update(){
	
		$mensaje = [
			"id" 	=> 'Éxito',
			"desc"	=> 'Pedido actualizado correctamente',
			"type"	=> 'success'
		];
		$this->jsonResponse($mensaje);
	}

	public function delete(){
		$data ['id_pedido'] = $this->ped_mdl->update(["estatus" => 0], $this->input->post('id_pedido'));
		$mensaje = [
			"id" 	=> 'Éxito',
			"desc"	=> 'Pedido eliminado correctamente',
			"type"	=> 'success'
		];
		$this->jsonResponse($mensaje);
	}
 
	public function get_productos(){
		$id_proveedor = $this->input->post('id_proveedor');
		$where = ["cotizaciones.id_proveedor" => $id_proveedor];
		$productosProveedor = $this->ct_mdl->productos_proveedor($where);
		$this->jsonResponse($productosProveedor);
	}

	public function get_pedidos(){
		$id_proveedor = $this->input->post('id_proveedor');
		$user = $this->session->userdata();
		$where = ["ctz_first.id_proveedor" => $id_proveedor];
		$fecha = date('Y-m-d');
		$productosProveedor = $this->ct_mdl->comparaCotizaciones($where,$fecha,$user["id_usuario"]);
		$this->jsonResponse($productosProveedor);
	}

	public function get_cotizaciones(){
		$where=["ctz_first.id_proveedor" => $this->input->post('id_proves')];
		$fecha = date('Y-m-d');
		$productosProveedor = $this->ct_mdl->comparaCotizaciones($where, $fecha,0);
		$this->jsonResponse($productosProveedor);
	}

	public function upload_pedidos(){
		$this->load->library("excelfile");
		ini_set("memory_limit", "-1");
		$file = $_FILES["file_cotizaciones"]["tmp_name"];
		$sheet = PHPExcel_IOFactory::load($file);
		$objExcel = PHPExcel_IOFactory::load($file);
		$sheet = $objExcel->getSheet(0); 
		$num_rows = $sheet->getHighestDataRow();
		
		for ($i=3; $i<=$num_rows; $i++) { 
			if($sheet->getCell('B'.$i)->getValue() > 0){
				$productos = $this->prod_mdl->get("id_producto",['nombre'=> htmlspecialchars($sheet->getCell('A'.$i)->getValue(), ENT_QUOTES, 'UTF-8')])[0];
				if (sizeof($productos) > 0) {
					$precio=0; $column_one=0; $column_two=0; $descuento=0; $precio_promocion=0;
					$precio = str_replace("$", "", str_replace(",", "replace", $sheet->getCell('B'.$i)->getValue()));
					$column_one = $sheet->getCell('D'.$i)->getValue();
					$column_two = $sheet->getCell('E'.$i)->getValue();
					$descuento = $sheet->getCell('F'.$i)->getValue();

					if ($column_one ==1 && $column_two ==1) {
						$precio_promocion = (($precio * $column_two)/($column_one+$column_two));
					}elseif ($column_one >=1 && $column_two >1) {
						$precio_promocion = (($precio * $column_two)/($column_one+$column_two));
					}elseif ($descuento >0) {
						$precio_promocion = ($precio - ($precio * ($descuento/100)));
					}else{
						$precio_promocion = $precio;
					}
					$new_cotizacion[$i]=[
						"id_producto"		=>	$productos->id_producto,
						"id_proveedor"		=>	$proveedor,//Recupera el id_usuario activo
						"precio"			=>	$precio,
						"num_one"			=>	$column_one,
						"num_two"			=>	$column_two,
						"descuento"			=>	$descuento,
						"precio_promocion"	=>	$precio_promocion,
						"fecha_registro"	=>	date('Y-m-d H:i:s'),
						"observaciones"		=>	$sheet->getCell('C'.$i)->getValue()
					];
				}
			}
		}
		if (sizeof($new_cotizacion) > 0) {
			$data['cotizacion']=$this->ct_mdl->insert_batch($new_cotizacion);
			$mensaje=[	"id"	=>	'Éxito',
						"desc"	=>	'Cotizaciones cargadas correctamente en el Sistema',
						"type"	=>	'success'];
		}else{
			$mensaje=[	"id"	=>	'Error',
						"desc"	=>	'Las Cotizaciones no se cargaron al Sistema',
						"type"	=>	'error'];
		}
		$this->jsonResponse($mensaje);
	}

	public function guardaPedido(){
		$user = $this->session->userdata();
		$values = json_decode($this->input->post('values'), true);

		$pedido = [
				"id_producto"=>	$values["id_producto"],
				"id_tienda"=>	$user['id_usuario'], 
				"cajas"=>	$values["cajas"],
				"piezas"=>	$values["piezas"],
				"pedido"=>$values["pedido"],
				"fecha_registro"=>date("Y-m-d H:i:s")
			];
		$ides = $this->ex_mdl->get('id_pedido', ['id_producto'=>$values["id_producto"],'WEEKOFYEAR(fecha_registro)'=>$this->weekNumber(), 'id_tienda'=>$user['id_usuario']])[0];
		if($ides == NULL){
			$respuesta = $this->ex_mdl->insert($pedido);
		}else{
			$respuesta = $this->ex_mdl->update($pedido,["id_pedido" => $ides->{'id_pedido'}]);
		}
		if($respuesta){
			$mensaje = [
				"id" 	=> 'Éxito',
				"desc"	=> 'Pedido registrado correctamente',
				"type"	=> 'success'
			];
		}else{
			$mensaje = [
				"id" 	=> 'Error',
				"desc"	=> 'No se registro el Pedido',
				"type"	=> $respuesta
			];
		}
		$this->jsonResponse($mensaje);
	}

	public function save_pedido(){
		$pedido = [
			"id_sucursal"		=>	$this->input->post('id_sucursal'),
			"id_proveedor"		=>	$this->input->post('id_proveedor'),
			"id_user_registra"	=>	$this->ion_auth->user()->row()->id, 
			"fecha_registro"	=>	date("Y-m-d H:i:s"),
			"total"				=>	str_replace(",", "", $this->input->post('total'))
		];
		
		$id_pedido = $this->ped_mdl->insert($pedido);
		
		$size = sizeof($this->input->post('id_producto[]'));
		$productos = $this->input->post('id_producto[]');
		for($i = 0; $i < $size; $i++){
			$detalle_pedido[] = array(
				'id_pedido'		=>	$id_pedido,
				'id_producto'	=>	$productos[$i],
				'cantidad'		=>	str_replace(",", "", $this->input->post('cantidad[]')[$i]),
				'precio'		=>	str_replace(",", "", $this->input->post('precio[]')[$i]),
				'importe'		=>	str_replace(",", "", $this->input->post('importe[]')[$i])
			);
		}
		if($this->det_ped_mdl->insert_batch($detalle_pedido) > 0){
			$mensaje = [
				"id" 	=> 'Éxito',
				"desc"	=> 'Pedido registrado correctamente',
				"type"	=> 'success'
			];
		}else{
			$mensaje = [
				"id" 	=> 'Error',
				"desc"	=> 'No se registro el Pedido',
				"type"	=> 'error'
			];
		}
		$this->jsonResponse($mensaje);
	}

	public function get_detalle($id){
		$data["title"]="DETALLE DEL PEDIDO";
		$data["pedido"] = $this->ped_mdl->get(NULL, ['id_pedido'=>$id])[0];
		$data["detallePedido"] = $this->det_ped_mdl->getDetallePedido(["detalles_pedidos.id_pedido"=>$data["pedido"]->id_pedido]);
		$data["view"]=$this->load->view("Pedidos/detalle_pedido", $data, TRUE);
		$data["button"]="";
		$this->jsonResponse($data);
	}

}

/* End of file Pedidos.php */
/* Location: ./application/controllers/Pedidos.php */