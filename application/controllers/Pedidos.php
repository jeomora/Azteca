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
		$this->load->model("Existenciasback_model", "exb_mdl");
		$this->load->model("Precio_sistema_model", "pre_mdl");
		$this->load->model("Stocks_model", "sto_mdl");
		$this->load->model("Pendientes_model", "pend_mdl");
		$this->load->model("Productos_model","prod_mdl");
		$this->load->model("Usuarios_model","usua_mdl");
		$this->load->model("Usuarios_model","usua_mdl");
		$this->load->model("Cambios_model", "cambio_md");
		$this->load->model("Prolunes_model", "prolu_md");
		$this->load->model("Exislunes_model", "ex_lun_md");
		$this->load->model("Verduras_model", "ver_md");
		$this->load->model("Frutas_model", "fru_md");
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

		$data["pedidos"] = $this->ped_mdl->getPedidos($where);
		$data["proveedores"] = $this->user_mdl->getUsuarios();
		$data["conjuntos"] = $this->user_mdl->get(NULL, ["conjunto" => "INDIVIDUAL"]);

		if($user['id_grupo'] == 3){
			$data['scripts'] = [
			'/scripts/pedtienda',
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
			$data["cuantas"] = $this->ex_lun_md->getCuantasTienda(NULL,$user["id_usuario"])[0];
			$data["noprod"] = $this->prolu_md->getCount(NULL)[0];
			$data["novol"] = $this->prod_mdl->getVolCount(NULL)[0];
			$data["noall"] = $this->prod_mdl->getAllCount(NULL)[0];
			$data["volcuantas"] = $this->ex_lun_md->getVolTienda(NULL,$user["id_usuario"])[0];
			$data["allcuantas"] = $this->ex_lun_md->getAllTienda(NULL,$user["id_usuario"])[0];
			$data["verduras"] = $this->ver_md->getExisTienda(NULL,$user["id_usuario"]);
			$data["frutas"] = $this->fru_md->getExisTienda(NULL,$user["id_usuario"]);
			//$this->jsonResponse($data["verduras"]);
			$this->estructura("Pedidos/pedido_tienda", $data, FALSE);
		}else{
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
			$this->estructura("Pedidos/table_pedidos", $data, FALSE);
		}

	}
	public function agregar(){
		ini_set("memory_limit", "-1");
		$data['links'] = [
			'/assets/css/plugins/dataTables/dataTables.bootstrap',
			'/assets/css/plugins/dataTables/dataTables.responsive',
			'/assets/css/plugins/dataTables/dataTables.tableTools.min',
			'/assets/css/plugins/dataTables/buttons.dataTables.min',
		];
		$data['scripts'] = [
			'/scripts/agregar',
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
		$where=["usuarios.id_grupo" => 3];
		$data["tiendas"] = $this->user_mdl->getUsuarios($where);
		$data["usuar"]  = $this->session->userdata();
		$this->estructura("Pedidos/agregar", $data);
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
		$fecha = new DateTime(date('Y-m-d H:i:s'));
		$intervalo = new DateInterval('P2D');
		$fecha->add($intervalo);
		$id_proveedor = $this->input->post('id_proveedor');
		$user = $this->session->userdata();
		if ($id_proveedor == "VOLUMEN") {
			$where = ["prod.estatus" => 2];
		}elseif ($id_proveedor == "AMARILLOS") {
			$where = ["prod.estatus" => 3];
		}else{
			$where = ["ctz_first.id_proveedor" => $id_proveedor,"prod.estatus" => 1];
		}

		$fecha = $fecha->format('Y-m-d H:i:s');
		$productosProveedor = $this->ct_mdl->comparaCotizaciones($where,$fecha,$user["id_usuario"]);
		$this->jsonResponse($productosProveedor);
	}

	public function get_allpedidos(){
		$fecha = new DateTime(date('Y-m-d H:i:s'));
		$intervalo = new DateInterval('P2D');
		$fecha->add($intervalo);
		$id_proveedor = $this->input->post('id_proveedor');
		$user = $this->session->userdata();
		if ($id_proveedor == "VOLUMEN") {
			$where = ["prod.estatus" => 2];
		}elseif ($id_proveedor == "AMARILLOS") {
			$where = ["prod.estatus" => 3];
		}else{
			//$where = ["ctz1.id_proveedor" => $id_proveedor,"prod.estatus" => 1];
			$where = ["ctz_first.id_proveedor" => $id_proveedor,"prod.estatus" => 1];
		}

		$fecha = $fecha->format('Y-m-d H:i:s');
		//$productosProveedor = $this->ct_mdl->getPedidosAll($where,$fecha,$user["id_usuario"]);
		$productosProveedor = $this->ct_mdl->comparaCotizaciones($where,$fecha,$user["id_usuario"]);
		$this->jsonResponse($productosProveedor);
	}

	public function getConjs(){
		$productosProveedor = $this->user_mdl->get(NULL, ["conjunto" => $this->input->post('id_proveedor')]);
		$this->jsonResponse($productosProveedor);
	}

	public function get_pedidosingle(){
		$fecha = new DateTime(date('Y-m-d H:i:s'));
		$intervalo = new DateInterval('P2D');
		$fecha->add($intervalo);
		$id_proveedor = $this->input->post('id_proveedor');
		$user = $this->session->userdata();
		if ($id_proveedor == "VOLUMEN") {
			$where = ["prod.estatus" => 2];
		}elseif ($id_proveedor == "AMARILLOS") {
			$where = ["prod.estatus" => 3];
		}else{
			$where = ["ctz_first.id_proveedor" => $id_proveedor,"prod.estatus" => 1];
		}

		$fecha = $fecha->format('Y-m-d H:i:s');
		$productosProveedor = $this->ct_mdl->getPedidosSingle($where,$fecha,$user["id_usuario"]);
		$this->jsonResponse($productosProveedor);
	}

	public function get_cotizaciones(){
		$fecha = new DateTime(date('Y-m-d H:i:s'));
		$intervalo = new DateInterval('P2D');
		$fecha->add($intervalo);
		$id_proveedor = $this->input->post('id_proveedor');
		$where=["ctz_first.id_proveedor" => $this->input->post('id_proves')];
		$fecha = $fecha->format('Y-m-d H:i:s');
		$productosProveedor = $this->ct_mdl->comparaCotizaciones($where, $fecha,0);
		$this->jsonResponse($productosProveedor);
	}

	public function guardaSistema(){
		$fecha = new DateTime(date('Y-m-d H:i:s'));
		$intervalo = new DateInterval('P2D');
		$fecha->add($intervalo);
		$user = $this->session->userdata();
		$values = json_decode($this->input->post('values'), true);
		$this->jsonResponse($values);
		$ides = $this->ct_mdl->get('id_producto', ['id_cotizacion'=>$values["id_cotizacion"]])[0];
		$sist = [
				"id_producto"=>	$ides->{"id_producto"},
				"precio_sistema"=>	$values["sistema"],
				"precio_four"=>	$values["cuatro"],
				"fecha_registro"=>$fecha->format('Y-m-d H:i:s')
			];
		$press = $this->pre_mdl->get('id_precio', ['id_producto'=>$ides->{"id_producto"},'WEEKOFYEAR(fecha_registro)'=>$this->weekNumber($fecha->format('Y-m-d H:i:s'))])[0];
		if($press == NULL){
			$respuesta = $this->pre_mdl->insert($sist);
		}else{
			$respuesta = $this->pre_mdl->update($sist,["id_precio" => $press->{'id_precio'}]);
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

	public function guardaPedidos(){
		$fecha = new DateTime(date('Y-m-d H:i:s'));
		$intervalo = new DateInterval('P2D');
		$fecha->add($intervalo);
		$user = $this->session->userdata();
		$values = json_decode($this->input->post('values'), true);

		$pedido = [
				"id_producto"=>	$values["id_producto"],
				"id_tienda"=>	$user['id_usuario'],
				"cajas"=>	$values["cajas"],
				"piezas"=>	$values["piezas"],
				"pedido"=>$values["pedido"],
				"fecha_registro"=>$fecha->format('Y-m-d H:i:s')
			];
		$ides = $this->ex_mdl->get('id_pedido', ['id_producto'=>$values["id_producto"],'WEEKOFYEAR(fecha_registro)'=>$this->weekNumber($fecha->format('Y-m-d H:i:s')), 'id_tienda'=>$user['id_usuario']])[0];
		if($ides == NULL){
			$respuesta = $this->ex_mdl->insert($pedido);
			$respuesta = $this->exb_mdl->insert($pedido);
		}else{
			$respuesta = $this->ex_mdl->update($pedido,["id_pedido" => $ides->{'id_pedido'}]);
			$respuesta = $this->exb_mdl->update($pedido,["id_pedido" => $ides->{'id_pedido'}]);
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
		$fecha = new DateTime(date('Y-m-d H:i:s'));
		$intervalo = new DateInterval('P2D');
		$fecha->add($intervalo);
		$pedido = [
			"id_sucursal"		=>	$this->input->post('id_sucursal'),
			"id_proveedor"		=>	$this->input->post('id_proveedor'),
			"id_user_registra"	=>	$this->ion_auth->user()->row()->id,
			"fecha_registro"	=>	$fecha->format('Y-m-d H:i:s'),
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

	public function upload_pendientes(){
		$fecha = new DateTime(date('Y-m-d H:i:s'));
		$intervalo = new DateInterval('P3D');
		$fecha->add($intervalo);
		$this->load->library("excelfile");
		ini_set("memory_limit", -1);
		$filen = "Pedidos Pendientes".rand();
		$config['upload_path']          = base_url('./assets/uploads/pedidos/');
        $config['allowed_types']        = 'xlsx|xls';
        $config['max_size']             = 100;
        $config['max_width']            = 1024;
        $config['max_height']           = 768;
        $config['max_height']           = 768;
        $this->load->library('upload', $config);
        $this->upload->initialize($config);
        $this->upload->do_upload('file_pendientes',$filen);
        $file = $_FILES["file_pendientes"]["tmp_name"];
		$sheet = PHPExcel_IOFactory::load($file);
		$objExcel = PHPExcel_IOFactory::load($file);
		$sheet = $objExcel->getSheet(0);
		$new_pendientes = [];
		$num_rows = $sheet->getHighestDataRow();
		for ($i=3; $i<=$num_rows; $i++) {
			$productos = $this->prod_mdl->get("id_producto",['codigo'=> htmlspecialchars($sheet->getCell('A'.$i)->getValue(), ENT_QUOTES, 'UTF-8')])[0];

			if (sizeof($productos) > 0) {
				$exis = $this->pend_mdl->get(NULL,["WEEKOFYEAR(fecha_registro)" => $this->weekNumber($fecha->format('Y-m-d H:i:s')),"id_producto"=>$productos->id_producto])[0];
				$cedis = $sheet->getCell('C'.$i)->getValue() == "" ? 0 : $sheet->getCell('C'.$i)->getValue();
				$abarrotes = $sheet->getCell('D'.$i)->getValue() == "" ? 0 : $sheet->getCell('D'.$i)->getValue();
				$pedregal = $sheet->getCell('E'.$i)->getValue() == "" ? 0 : $sheet->getCell('E'.$i)->getValue();
				$tienda = $sheet->getCell('F'.$i)->getValue() == "" ? 0 : $sheet->getCell('F'.$i)->getValue();
				$ultra = $sheet->getCell('G'.$i)->getValue() == "" ? 0 : $sheet->getCell('G'.$i)->getValue();
				$trincheras = $sheet->getCell('H'.$i)->getValue() == "" ? 0 : $sheet->getCell('H'.$i)->getValue();
				$mercado = $sheet->getCell('I'.$i)->getValue() == "" ? 0 : $sheet->getCell('I'.$i)->getValue();
				$tenencia = $sheet->getCell('J'.$i)->getValue() == "" ? 0 : $sheet->getCell('J'.$i)->getValue();
				$tijeras = $sheet->getCell('K'.$i)->getValue() == "" ? 0 : $sheet->getCell('K'.$i)->getValue();
				$new_pendientes[$i]=[
					"id_producto" => $productos->id_producto,
					"cedis" => $cedis,
					"abarrotes" => $abarrotes,
					"pedregal" => $pedregal,
					"tienda" => $tienda,
					"trincheras" => $trincheras,
					"ultra" => $ultra,
					"mercado" => $mercado,
					"tenencia" => $tenencia,
					"tijeras" => $tijeras,
					"fecha_registro" => $fecha->format('Y-m-d H:i:s')
				];
				if($exis){
					$data['pendientes']=$this->pend_mdl->update($new_pendientes[$i], ['id_pendiente' => $exis->id_pendiente]);
				}else{
					$data['pendientes']=$this->pend_mdl->insert($new_pendientes[$i]);
				}
			}
		}
		if (sizeof($new_pendientes) > 0) {
			$aprov = $this->usua_mdl->get(NULL, ['id_usuario'=>$tienda])[0];
			$cambios=[
					"id_usuario"		=>	$this->session->userdata('id_usuario'),
					"fecha_cambio"		=>	date("Y-m-d H:i:s"),
					"antes"			=>	"El usuario sube pedidos pendientes ",
					"despues"			=>	"assets/uploads/pedidos/".$filen.".xlsx",
					"accion"			=>	"Sube Pedidos Pendientes"
				];
			$data['cambios']=$this->cambio_md->insert($cambios);
			$mensaje=[	"id"	=>	'Éxito',
						"desc"	=>	'Pedidos Pendientes cargados correctamente en el Sistema',
						"type"	=>	'success'];
		}else{
			$mensaje=[	"id"	=>	'Error',
						"desc"	=>	'Los Pedidos Pendientes no se cargaron al Sistema',
						"type"	=>	'error'];
		}
		$this->jsonResponse($mensaje);
	}

	public function getPendientes(){
		$fecha = new DateTime(date('Y-m-d H:i:s'));
		$intervalo = new DateInterval('P3D');
		$fecha->add($intervalo);
		$data["pendientes"] =  $this->pend_mdl->getThem(["WEEKOFYEAR(pp.fecha_registro)" => $this->weekNumber($fecha->format('Y-m-d H:i:s'))]);
		$this->jsonResponse($data);
	}

	public function getPeds($id_tienda){
		$data["cuantas"] = $this->ex_lun_md->getCuantasTienda(NULL,$id_tienda)[0];
		$data["noprod"] = $this->prolu_md->getCount(NULL)[0];
		$data["novol"] = $this->prod_mdl->getVolCount(NULL)[0];
		$data["noall"] = $this->prod_mdl->getAllCount(NULL)[0];
		$data["volcuantas"] = $this->ex_lun_md->getVolTienda(NULL,$id_tienda)[0];
		$data["allcuantas"] = $this->ex_lun_md->getAllTienda(NULL,$id_tienda)[0];
		$this->jsonResponse($data);
	}
	

}

/* End of file Pedidos.php */
/* Location: ./application/controllers/Pedidos.php */
