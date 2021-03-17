<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Cotizaciones extends MY_Controller {
	function __construct(){
		parent::__construct();
		$this->load->model("Cotizaciones_model", "ct_mdl");
		$this->load->model("Cotizprovs_model", "ctpr_mdl");
		$this->load->model("Cotizacionesback_model","ctb_mdl");
		$this->load->model("Productos_model", "prod_mdl");
		$this->load->model("Usuarios_model", "usua_mdl");
		$this->load->model("Pedidos_model", "ped_mdl");
		$this->load->model("Familias_model", "fam_mdl");
		$this->load->model("Cambios_model", "cambio_md");
		$this->load->model("Usuarios_model", "user_md");
		$this->load->model("Existencias_model", "ex_mdl");
		$this->load->model("Precio_sistema_model", "pre_mdl");
		$this->load->model("Precio_sistemaback_model", "preb_mdl");
		$this->load->model("Faltantes_model", "falt_mdl");
		$this->load->model("Prodandprice_model", "prodand_mdl");
		$this->load->model("Images_model", "img_md");
		$this->load->model("Expocotz_model", "expo_mdl");
		$this->load->model("Conversiones_model", "conve_mdl");
		$this->load->model("Reales_model","real_mdl");
	}
	public function index(){
		ini_set("memory_limit", "-1");
		$data['links'] = [
			'/assets/css/plugins/dataTables/dataTables.bootstrap',
			'/assets/css/plugins/dataTables/dataTables.responsive',
			'/assets/css/plugins/dataTables/dataTables.tableTools.min',
			'/assets/css/plugins/dataTables/buttons.dataTables.min',
		];
		$data['scripts'] = [
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
		$user = $this->session->userdata();//Trae los datos del usuario;
		$where = [];
		$this->data["message"] =NULL;
		if(!$this->session->userdata("username")){
			redirect("Compras/Login", "");
		}elseif($user['id_grupo'] == 2){//Solo mostrar sus Productos cotizados cuando es proveedor
			array_push($data['scripts'], '/scripts/cotizacionesP');
			$fecha = new DateTime(date('Y-m-d H:i:s'));
			$intervalo = new DateInterval('P3D');
			$fecha->add($intervalo);
			$where=["ctr.id_proveedor" => $user['id_usuario'],
					"WEEKOFYEAR(ctr.fecha_registro)" => $this->weekNumber()];
			$data["cotizaciones"] = $this->ctpr_mdl->getAllCotizaciones($where);
			$data["usuarios"] = $this->user_md->getUsuarios();
			$this->estructura("Cotizaciones/table_cotizaciones", $data, FALSE);
		}else{
			array_push($data['scripts'], '/scripts/cotizaciones');
			$data["proveedores"] = $this->usua_mdl->getUsuarios();
			$data["usuarios"] = $this->user_md->getUsuarios();
			$this->estructura("Cotizaciones/cotizaciones_view", $data, FALSE);
		}
	}
	public function anteriores(){
		ini_set("memory_limit", "-1");
		$data['links'] = [
			'/assets/css/plugins/dataTables/dataTables.bootstrap',
			'/assets/css/plugins/dataTables/dataTables.responsive',
			'/assets/css/plugins/dataTables/dataTables.tableTools.min',
			'/assets/css/plugins/dataTables/buttons.dataTables.min',
		];
		$data['scripts'] = [
			'/scripts/admin',
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
		$data["cotizados"] = $this->usua_mdl->getCotizados();
		$data["usuar"]  = $this->session->userdata();
		$this->estructura("Cotizaciones/anteriores", $data);
	}
	public function autorizar(){
		ini_set("memory_limit", "-1");
		$data['links'] = [
			'/assets/css/plugins/dataTables/dataTables.bootstrap',
			'/assets/css/plugins/dataTables/dataTables.responsive',
			'/assets/css/plugins/dataTables/dataTables.tableTools.min',
			'/assets/css/plugins/dataTables/buttons.dataTables.min',
		];
		$data['scripts'] = [
			'/scripts/autorizar',
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
		$where=["usuarios.id_grupo" => 2];
		$data["proveedores"] = $this->usua_mdl->getUsuarios($where);
		$this->estructura("Cotizaciones/autorizar", $data);
	}
	public function get_cotzprov($id_proveedor){
		$where=["ctr.id_proveedor" => $id_proveedor,
				"ctr.estatus <> "=>0,
				"WEEKOFYEAR(ctr.fecha_registro)" => $this->weekNumber()];
		$dat = $this->ctpr_mdl->getAllCotizaciones($where);
		$this->jsonResponse($dat);
	}
	public function deleteRenglon($id_cot){
		$data['cotizacion']=$this->ctpr_mdl->update(["estatus"=>0], ['id_cotizacion' => $id_cot]);
		$this->jsonResponse($data['cotizacion']);
	}
	public function agregaRenglon($idprov){
		$fecha = new DateTime(date('Y-m-d H:i:s'));
		$intervalo = new DateInterval('P3D');
		$fecha->add($intervalo);
		$user = $this->session->userdata();

		$cotizaciones =  $this->ctpr_mdl->getAllCotizaciones(['ctr.id_proveedor'=>$idprov,'ctr.estatus <> '=>0,'WEEKOFYEAR(ctr.fecha_registro)' => $this->weekNumber($fecha->format('Y-m-d H:i:s'))]);
		$i = 0;
		$new_cotizacion = null;
		if ($cotizaciones){
			foreach ($cotizaciones as $key => $value){
				$antes =  $this->falt_mdl->get(NULL, ['id_producto' => $value->id_producto, 'fecha_termino > ' => date("Y-m-d H:i:s"), 'id_proveedor' => $idprov])[0];
				$fecha = new DateTime(date('Y-m-d H:i:s'));
				$intervalo = new DateInterval('P3D');
				$num_one = $value->num_one == '' ? 0 : $value->num_one;
				$num_two = $value->num_two == '' ? 0 : $value->num_two;
				$descuento = $value->descuento == '' ? 0 : $value->descuento;
				$fecha->add($intervalo);
				$cotiz =  $this->ct_mdl->get(NULL, ['id_producto' => $value->id_producto, 'WEEKOFYEAR(fecha_registro)' => $this->weekNumber($fecha->format('Y-m-d H:i:s')), 'id_proveedor' => $idprov])[0];
				if($antes){
					$new_cotizacion[$i] = [
						"id_proveedor"		=>	$idprov,
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
						"id_proveedor"		=>	$idprov,
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
				if($cotiz){
					$data['cotizacion']=$this->ct_mdl->update($new_cotizacion[$i], ['id_cotizacion' => $cotiz->id_cotizacion]);
				}else{
					$data['cotizacion']=$this->ct_mdl->insert($new_cotizacion[$i]);
				}

				$i++;
			}
		}else{
			$mensaje = [
				"id" 	=> 'Error',
				"desc"	=> 'No se pudieron cargar las cotizaciones',
				"type"	=> 'error'
			];
		}

		if (!isset($new_cotizacion)) {
			$mensaje=[	"id"	=>	'Error',
						"desc"	=>	'No se pudo completar la operación',
						"type"	=>	'error'];
		}else{
			if (sizeof($new_cotizacion) > 0) {
				$aprov = $this->usua_mdl->get(NULL, ['id_usuario'=>$idprov])[0];
				$cambios=[
						"id_usuario"		=>	$this->session->userdata('id_usuario'),
						"fecha_cambio"		=>	date("Y-m-d H:i:s"),
						"antes"			=>	"Se autorizó la cotización de ".$aprov->nombre,
						"despues"			=>	"Cotizaciones ya en sistema",
						"accion"			=>	"----"
					];
				$data['cambios']=$this->cambio_md->insert($cambios);
				$mensaje=[	"id"	=>	'Éxito',
							"desc"	=>	'Cotizaciones cargadas correctamente en el Sistema',
							"type"	=>	'success'];
			}else{
				$mensaje=[	"id"	=>	'Error',
							"desc"	=>	'Las Cotizaciones no se cargaron al Sistema',
							"type"	=>	'error'];
			}
		}
		
		$this->jsonResponse($mensaje);
	}
	public function proveedorCots($ides){
		$fecha = new DateTime(date('Y-m-d H:i:s'));
		$intervalo = new DateInterval('P3D');
		$fecha->add($intervalo);
		$where=["cotizaciones.id_proveedor" => $ides, "cotizaciones.estatus <> " => 0 ,
				"WEEKOFYEAR(cotizaciones.fecha_registro)" => $this->weekNumber($fecha->format('Y-m-d H:i:s'))];
		$data["cotizaciones"] = $this->ct_mdl->getAllCotizaciones($where);
		$this->jsonResponse($data["cotizaciones"]);
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
		$where=["usuarios.id_grupo" => 2];
		$data["proveedores"] = $this->usua_mdl->getUsuarios($where);
		$data["usuar"]  = $this->session->userdata();
		$this->estructura("Cotizaciones/agregar", $data);
	}
	public function fastedit(){
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
		$where=["usuarios.id_grupo" => 2];
		$data["diferencias"] = $this->ct_mdl->getDiferences(NULL);
		$data["usuar"]  = $this->session->userdata();
		$this->estructura("Cotizaciones/fastedit", $data);
	}
	public function volumenes(){
		ini_set("memory_limit", "-1");
		$data['links'] = [
			'/assets/css/plugins/dataTables/dataTables.bootstrap',
			'/assets/css/plugins/dataTables/dataTables.responsive',
			'/assets/css/plugins/dataTables/dataTables.tableTools.min',
			'/assets/css/plugins/dataTables/buttons.dataTables.min',
		];
		$data['scripts'] = [
			'/scripts/volumenes',
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
		$data["usuar"]  = $this->session->userdata();
		$this->estructura("Cotizaciones/volumenes", $data);
	}
	public function directos(){
		ini_set("memory_limit", "-1");
		$data['links'] = [
			'/assets/css/plugins/dataTables/dataTables.bootstrap',
			'/assets/css/plugins/dataTables/dataTables.responsive',
			'/assets/css/plugins/dataTables/dataTables.tableTools.min',
			'/assets/css/plugins/dataTables/buttons.dataTables.min',
		];
		$data['scripts'] = [
			'/scripts/directos',
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
		$data["usuar"]  = $this->session->userdata();
		$this->estructura("Cotizaciones/directos", $data);
	}
	public function pendientes(){
		ini_set("memory_limit", "-1");
		$data['links'] = [
			'/assets/css/plugins/dataTables/dataTables.bootstrap',
			'/assets/css/plugins/dataTables/dataTables.responsive',
			'/assets/css/plugins/dataTables/dataTables.tableTools.min',
			'/assets/css/plugins/dataTables/buttons.dataTables.min',
		];
		$data['scripts'] = [
			'/scripts/pendientes',
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
		$data["usuar"]  = $this->session->userdata();
		$this->estructura("Cotizaciones/pendientes", $data);
	}
	public function proveedor(){
		ini_set("memory_limit", "-1");
		$data['links'] = [
			'/assets/css/plugins/dataTables/dataTables.bootstrap',
			'/assets/css/plugins/dataTables/dataTables.responsive',
			'/assets/css/plugins/dataTables/dataTables.tableTools.min',
			'/assets/css/plugins/dataTables/buttons.dataTables.min',
		];
		$data['scripts'] = [
			'/scripts/volumenes',
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
		$where=["usuarios.id_grupo" => 2];
		$data["title"] = "Filtrar por proveedor";
		$data["proveedores"] = $this->usua_mdl->getUsuarios($where);
		$data["usuar"]  = $this->session->userdata();
		$this->estructura("Cotizaciones/proveedor", $data);
	}
	public function faltantes(){
		ini_set("memory_limit", "-1");
		$data['links'] = [
			'/assets/css/plugins/dataTables/dataTables.bootstrap',
			'/assets/css/plugins/dataTables/dataTables.responsive',
			'/assets/css/plugins/dataTables/dataTables.tableTools.min',
			'/assets/css/plugins/dataTables/buttons.dataTables.min',
		];
		$data['scripts'] = [
			'/scripts/faltantes',
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
		$where=["usuarios.id_grupo" => 2, "usuarios.estatus" => 1];
		$data["title"] = "Filtrar por proveedor";
		$data["proveedores"] = $this->usua_mdl->getUsuarios($where);
		$data["usuar"]  = $this->session->userdata();
		$this->estructura("Cotizaciones/faltantes", $data);
	}
	public function add_cotizacion(){
		$data["title"]="REGISTRAR COTIZACIONES";
		$data["productos"] = $this->prod_mdl->get("id_producto, nombre",["estatus <>"=>0]);
		$data["view"]=$this->load->view("Cotizaciones/new_cotizacion", $data, TRUE);
		$data["button"]="<button class='btn btn-success new_cotizacion' type='button'>
							<span class='bold'><i class='fa fa-floppy-o'></i></span> &nbsp;Guardar
						</button>";
		$this->jsonResponse($data);
	}
	public function end_cotizacion($ides){
		$data["title"]="ELIMINAR COTIZACION DE LA SEMANA";
		$data["proveedor"] = $this->usua_mdl->get(NULL,["id_usuario" => $ides])[0];
		$data["view"]=$this->load->view("Cotizaciones/end_cotizacion", $data, TRUE);
		$data["button"]="<button class='btn btn-danger end_cotizacion' type='button'>
							<span class='bold'><i class='fa fa-trash'></i></span> &nbsp;ELIMINAR
						</button>";
		$this->jsonResponse($data);
	}
	public function endCotizacion($ides){
		$fecha = new DateTime(date('Y-m-d H:i:s'));
		$intervalo = new DateInterval('P3D');
		$fecha->add($intervalo);
		if($this->db->delete('cotizaciones', array('id_proveedor' => $ides, "WEEKOFYEAR(fecha_registro)" => $this->weekNumber($fecha->format('Y-m-d H:i:s'))))){
			$mensaje = [
				"id" 	=> 'Éxito',
				"desc"	=> 'Cotizaciones eliminadas correctamente',
				"type"	=> 'success'
			];
			$data["proveedor"] = $this->usua_mdl->get(NULL,["id_usuario" => $ides])[0];
			$cambios = [
				"id_usuario" => $this->session->userdata('id_usuario'),
				"fecha_cambio" => date('Y-m-d H:i:s'),
				"accion" => "Elimina cotizaciones de ".$data["proveedor"]->nombre,
				"antes" => "Eliminado",
				"despues" => "Eliminado"
			];
			$data['cambios'] = $this->cambio_md->insert($cambios);
		}else{
			$mensaje=[	"id"	=>	'Error',
			"desc"	=>	'El Archivo esta sin precios',
			"type"	=>	'error'];
		}
		$this->jsonResponse($mensaje);
	}
	public function save($idesp){
		if($idesp == 0){
			$proveedor = $this->session->userdata('id_usuario');
		}else{
			$proveedor = $idesp;
		}
		$fecha = new DateTime(date('Y-m-d H:i:s'));
		$intervalo = new DateInterval('P2D');
		$fecha->add($intervalo);
		$antes =  $this->falt_mdl->get(NULL, ['id_producto' => $this->input->post('id_producto'), 'fecha_termino > ' => date("Y-m-d H:i:s"), 'id_proveedor' => $proveedor])[0];
		$cotiz =  $this->ct_mdl->get(NULL, ['id_producto' => $this->input->post('id_producto'), 'WEEKOFYEAR(fecha_registro)' => $this->weekNumber($fecha->format('Y-m-d H:i:s')), 'id_proveedor' => $proveedor])[0];
		$aprod = $this->prod_mdl->get(NULL, ['id_producto'=>$this->input->post('id_producto')])[0];
		$aprov = $this->usua_mdl->get(NULL, ['id_usuario'=>$proveedor])[0];
		$num_one = $this->input->post('num_one') == '' ? 0 : $this->input->post('num_one');
		$num_two = $this->input->post('num_two') == '' ? 0 : $this->input->post('num_two');
		$descuento = str_replace(',', '', $this->input->post('porcentaje')) == '' ? 0 : str_replace(',', '', $this->input->post('porcentaje'));
		if($antes){
			$cotizacion = [
				'id_producto'		=>	$this->input->post('id_producto'),
				'id_proveedor'		=>	$proveedor,
				'num_one'			=>	$this->input->post('num_one'),
				'num_two'			=>	$this->input->post('num_two'),
				'precio'			=>	str_replace(',', '', $this->input->post('precio')),//precio base
				'precio_promocion'	=>	($this->input->post('precio_promocion') > 0) ? str_replace(',', '', $this->input->post('precio_promocion')) : str_replace(',', '', $this->input->post('precio')),//precio con promoción
				'descuento'			=>	str_replace(',', '', $this->input->post('porcentaje')),
				'fecha_registro'	=>	$fecha->format('Y-m-d H:i:s'),
				'observaciones'		=>	strtoupper($this->input->post('observaciones')),
				'estatus' => 0
			];
			if($cotiz){
				$data['cotizacion']=$this->ct_mdl->update($cotizacion, ['id_cotizacion' => $cotiz->id_cotizacion]);
				$data['cotizacin']=$this->ctb_mdl->update($cotizacion, ['id_cotizacion' => $cotiz->id_cotizacion]);
			}else{
				$data['cotizacion']=$this->ct_mdl->insert($cotizacion);
				$data['cotizacin']=$this->ctb_mdl->insert($cotizacion);
			}
			$cambios = [
				"id_usuario" => $this->session->userdata('id_usuario'),
				"fecha_cambio" => date('Y-m-d H:i:s'),
				"accion" => "Cotizacion Nueva Con Faltante",
				"antes" => "Nueva cotización",
				"despues" => "Producto: ".$aprod->nombre."\n///Proveedor: ".$aprov->nombre."\n///Precio: ".str_replace(',', '', $this->input->post('precio'))."\n///Precio promoción: ".
							(($this->input->post('precio_promocion') > 0) ? str_replace(',', '', $this->input->post('precio_promocion')) : str_replace(',', '', $this->input->post('precio')))." ".
							"\n///".$this->input->post('num_one')." EN ".$this->input->post('num_two')."\n///Descuento: ".str_replace(',', '', $this->input->post('porcentaje')).
							"\n///Observaciones: ".strtoupper($this->input->post('observaciones'))
			];
			$data['cambios'] = $this->cambio_md->insert($cambios);
		}else{
			$cotizacion = [
				'id_producto'		=>	$this->input->post('id_producto'),
				'id_proveedor'		=>	$proveedor,
				'num_one'			=>	$this->input->post('num_one'),
				'num_two'			=>	$this->input->post('num_two'),
				'precio'			=>	str_replace(',', '', $this->input->post('precio')),//precio base
				'precio_promocion'	=>	($this->input->post('precio_promocion') > 0) ? str_replace(',', '', $this->input->post('precio_promocion')) : str_replace(',', '', $this->input->post('precio')),//precio con promoción
				'descuento'			=>	str_replace(',', '', $this->input->post('porcentaje')),
				'fecha_registro'	=>	$fecha->format('Y-m-d H:i:s'),
				'observaciones'		=>	strtoupper($this->input->post('observaciones'))
			];
			if($cotiz){
				$data['cotizacion']=$this->ct_mdl->update($cotizacion, ['id_cotizacion' => $cotiz->id_cotizacion]);
				$data['cotizacin']=$this->ctb_mdl->update($cotizacion, ['id_cotizacion' => $cotiz->id_cotizacion]);
			}else{
				$data['cotizacion']=$this->ct_mdl->insert($cotizacion);
				$data['cotizacin']=$this->ctb_mdl->insert($cotizacion);
			}
			$cambios = [
				"id_usuario" => $this->session->userdata('id_usuario'),
				"fecha_cambio" => date('Y-m-d H:i:s'),
				"accion" => "Cotizacion Nueva",
				"antes" => "Nueva cotización",
				"despues" => "Producto: ".$aprod->nombre."\n///Proveedor: ".$aprov->nombre."\n///Precio: ".str_replace(',', '', $this->input->post('precio'))."\n///Precio promoción: ".
							(($this->input->post('precio_promocion') > 0) ? str_replace(',', '', $this->input->post('precio_promocion')) : str_replace(',', '', $this->input->post('precio')))." ".
							"\n///".$this->input->post('num_one')." EN ".$this->input->post('num_two')."\n///Descuento: ".str_replace(',', '', $this->input->post('porcentaje')).
							"\n///Observaciones: ".strtoupper($this->input->post('observaciones'))
			];
			$data['cambios'] = $this->cambio_md->insert($cambios);
		}
		$mensaje = [
			"id" 	=> 'Éxito',
			"desc"	=> 'Cotización registrada correctamente',
			"type"	=> 'success'
		];
		$this->jsonResponse($mensaje);
	}
	public function saveP($idesp){
		if($idesp == 0){
			$proveedor = $this->session->userdata('id_usuario');
		}else{
			$proveedor = $idesp;
		}
		$fecha = new DateTime(date('Y-m-d H:i:s'));
		$intervalo = new DateInterval('P2D');
		$fecha->add($intervalo);
		$antes =  $this->falt_mdl->get(NULL, ['id_producto' => $this->input->post('id_producto'), 'fecha_termino > ' => date("Y-m-d H:i:s"), 'id_proveedor' => $proveedor])[0];
		$cotiz =  $this->ctpr_mdl->get(NULL, ['id_producto' => $this->input->post('id_producto'), 'WEEKOFYEAR(fecha_registro)' => $this->weekNumber($fecha->format('Y-m-d H:i:s')), 'id_proveedor' => $proveedor])[0];
		$aprod = $this->prod_mdl->get(NULL, ['id_producto'=>$this->input->post('id_producto')])[0];
		$aprov = $this->usua_mdl->get(NULL, ['id_usuario'=>$proveedor])[0];
		$num_one = $this->input->post('num_one') == '' ? 0 : $this->input->post('num_one');
		$num_two = $this->input->post('num_two') == '' ? 0 : $this->input->post('num_two');
		$descuento = str_replace(',', '', $this->input->post('porcentaje')) == '' ? 0 : str_replace(',', '', $this->input->post('porcentaje'));
		if($antes){
			$cotizacion = [
				'id_producto'		=>	$this->input->post('id_producto'),
				'id_proveedor'		=>	$proveedor,
				'num_one'			=>	$this->input->post('num_one'),
				'num_two'			=>	$this->input->post('num_two'),
				'precio'			=>	str_replace(',', '', $this->input->post('precio')),//precio base
				'precio_promocion'	=>	($this->input->post('precio_promocion') > 0) ? str_replace(',', '', $this->input->post('precio_promocion')) : str_replace(',', '', $this->input->post('precio')),//precio con promoción
				'descuento'			=>	str_replace(',', '', $this->input->post('porcentaje')),
				'fecha_registro'	=>	$fecha->format('Y-m-d H:i:s'),
				'observaciones'		=>	strtoupper($this->input->post('observaciones')),
				'estatus' => 0
			];
			if($cotiz){
				$data['cotizacion']=$this->ctpr_mdl->update($cotizacion, ['id_cotizacion' => $cotiz->id_cotizacion]);
			}else{
				$data['cotizacion']=$this->ctpr_mdl->insert($cotizacion);
			}
			$cambios = [
				"id_usuario" => $this->session->userdata('id_usuario'),
				"fecha_cambio" => date('Y-m-d H:i:s'),
				"accion" => "Cotizacion Nueva Con Faltante",
				"antes" => "Nueva cotización",
				"despues" => "Producto: ".$aprod->nombre."\n///Proveedor: ".$aprov->nombre."\n///Precio: ".str_replace(',', '', $this->input->post('precio'))."\n///Precio promoción: ".
							(($this->input->post('precio_promocion') > 0) ? str_replace(',', '', $this->input->post('precio_promocion')) : str_replace(',', '', $this->input->post('precio')))." ".
							"\n///".$this->input->post('num_one')." EN ".$this->input->post('num_two')."\n///Descuento: ".str_replace(',', '', $this->input->post('porcentaje')).
							"\n///Observaciones: ".strtoupper($this->input->post('observaciones'))
			];
			$data['cambios'] = $this->cambio_md->insert($cambios);
		}else{
			$cotizacion = [
				'id_producto'		=>	$this->input->post('id_producto'),
				'id_proveedor'		=>	$proveedor,
				'num_one'			=>	$this->input->post('num_one'),
				'num_two'			=>	$this->input->post('num_two'),
				'precio'			=>	str_replace(',', '', $this->input->post('precio')),//precio base
				'precio_promocion'	=>	($this->input->post('precio_promocion') > 0) ? str_replace(',', '', $this->input->post('precio_promocion')) : str_replace(',', '', $this->input->post('precio')),//precio con promoción
				'descuento'			=>	str_replace(',', '', $this->input->post('porcentaje')),
				'fecha_registro'	=>	$fecha->format('Y-m-d H:i:s'),
				'observaciones'		=>	strtoupper($this->input->post('observaciones'))
			];
			if($cotiz){
				$data['cotizacion']=$this->ctpr_mdl->update($cotizacion, ['id_cotizacion' => $cotiz->id_cotizacion]);
			}else{
				$data['cotizacion']=$this->ctpr_mdl->insert($cotizacion);
			}
			$cambios = [
				"id_usuario" => $this->session->userdata('id_usuario'),
				"fecha_cambio" => date('Y-m-d H:i:s'),
				"accion" => "Cotizacion Nueva",
				"antes" => "Nueva cotización",
				"despues" => "Producto: ".$aprod->nombre."\n///Proveedor: ".$aprov->nombre."\n///Precio: ".str_replace(',', '', $this->input->post('precio'))."\n///Precio promoción: ".
							(($this->input->post('precio_promocion') > 0) ? str_replace(',', '', $this->input->post('precio_promocion')) : str_replace(',', '', $this->input->post('precio')))." ".
							"\n///".$this->input->post('num_one')." EN ".$this->input->post('num_two')."\n///Descuento: ".str_replace(',', '', $this->input->post('porcentaje')).
							"\n///Observaciones: ".strtoupper($this->input->post('observaciones'))
			];
			$data['cambios'] = $this->cambio_md->insert($cambios);
		}
		$mensaje = [
			"id" 	=> 'Éxito',
			"desc"	=> 'Cotización registrada correctamente',
			"type"	=> 'success'
		];
		$this->jsonResponse($mensaje);
	}
	public function update(){
		$user = $this->session->userdata();
		$size = sizeof($this->input->post('id_cotz[]'));
		$cotz = $this->input->post('id_cotz[]');
		$precio = $this->input->post('precio[]');
		$precio_promocion = $this->input->post('precio_promocion[]');
		$num_one = $this->input->post('num_one[]');
		$num_two = $this->input->post('num_two[]');
		$descuento = $this->input->post('descuento[]');
		$observaciones = $this->input->post('observaciones[]');
		for($i = 0; $i < $size; $i++){
			$antes =  $this->ct_mdl->get(NULL, ['id_cotizacion'=>$cotz[$i]])[0];
			$aprod = $this->prod_mdl->get(NULL, ['id_producto'=>$antes->id_producto])[0];
			$aprov = $this->usua_mdl->get(NULL, ['id_usuario'=>$antes->id_proveedor])[0];
			$cambios = [
				"id_usuario" => $user["id_usuario"],
				"fecha_cambio" => date('Y-m-d H:i:s'),
				"accion" => "Cotizacion actualizada",
				"antes" => "id : ".$antes->id_cotizacion." \n///Proveedor: ".$aprov->nombre." \n///Producto:".$aprod->nombre." \n///Precio: ".
							$antes->precio." \n///Precio promoción: ".$antes->precio_promocion." \n///".$antes->num_one." en ".$antes->num_two.
							" \n///% Descuento: ".$antes->descuento." \nRegistrado: ".$antes->fecha_registro." \n///Observaciones: ".$antes->observaciones,
				"despues" => "Precio: ".$precio[$i]."\n///Precio promoción: ".$precio_promocion[$i]."\n///".$num_one[$i]." en ".$num_two[$i].
							"\n///%Descuento: ".$descuento[$i]."\n///Observaciones: ".$observaciones[$i]];
			$data['cambios'] = $this->cambio_md->insert($cambios);
			$data ['id_cotizacion'] = $this->ct_mdl->update([
				"precio" => str_replace(',', '', $precio[$i]),//$precio[$i]
				"precio_promocion" => str_replace(',', '', $precio_promocion[$i]),
				"num_one" => $num_one[$i],
				"num_two" => $num_two[$i],
				"descuento" => $descuento[$i],
				"observaciones" => $observaciones[$i]
			], $cotz[$i]);
			$data ['id_cotizacin'] = $this->ctb_mdl->update([
				"precio" => str_replace(',', '', $precio[$i]),//$precio[$i]
				"precio_promocion" => str_replace(',', '', $precio_promocion[$i]),
				"num_one" => $num_one[$i],
				"num_two" => $num_two[$i],
				"descuento" => $descuento[$i],
				"observaciones" => $observaciones[$i]
			], $cotz[$i]);
		}
		$mensaje = [
			"id" 	=> 'Éxito',
			"desc"	=> 'Cotización actualizada correctamente',
			"type"	=> 'success'
		];
		$this->jsonResponse($mensaje);
	}
	public function delete(){
		$size = sizeof($this->input->post('id_producto[]'));
		$user = $this->session->userdata();
		$productos = $this->input->post('id_producto[]');
		for($i = 0; $i < $size; $i++){
			$antes =  $this->ct_mdl->get(NULL, ['id_cotizacion'=>$productos[$i]])[0];
			$aprod = $this->prod_mdl->get(NULL, ['id_producto'=>$antes->id_producto])[0];
			$aprov = $this->usua_mdl->get(NULL, ['id_usuario'=>$antes->id_proveedor])[0];
			$cambios = [
				"id_usuario" => $user["id_usuario"],
				"fecha_cambio" => date('Y-m-d H:i:s'),
				"antes" => "id : ".$antes->id_cotizacion." \n///Proveedor: ".$aprov->nombre." \n///Producto:".$aprod->nombre." \n///Precio: ".
							$antes->precio." \n///Precio promoción: ".$antes->precio_promocion." \n///".$antes->num_one." en ".$antes->num_two.
							" \n///% Descuento: ".$antes->descuento." \nRegistrado: ".$antes->fecha_registro." \n///Observaciones: ".$antes->observaciones,
				"accion" => "Cotizacion eliminada","despues" => "El usuario elimino la cotización"];
			$data['cambios'] = $this->cambio_md->insert($cambios);
			$data ['id_cotizacion'] = $this->ct_mdl->update(["estatus" => 0], $productos[$i]);
			$data ['id_cotizacin'] = $this->ctb_mdl->update(["estatus" => 0], $productos[$i]);
		}
		$mensaje = [
			"id" 	=> 'Éxito',
			"desc"	=> 'Cotización eliminada correctamente',
			"type"	=> 'success'
		];
		$this->jsonResponse($mensaje);
	}
	public function hacer_pedido($value=''){
		$fecha = new DateTime(date('Y-m-d H:i:s'));
		$intervalo = new DateInterval('P2D');
		$fecha->add($intervalo);
		$size = sizeof($this->input->post('id_producto[]'));
		$productos = $this->input->post('id_producto[]');
		$importe = $this->input->post('importe[]');
		for($i = 0; $i < $size; $i++){
			$pedid = $this->ped_mdl->getWeekPedidos(NULL,$productos[$i],$this->weekNumber($fecha->format('Y-m-d H:i:s')));
			if($pedid){
				$id_pedido = $this->ped_mdl->update(["total" => 0], $pedid->total+$importe[$i]);
			}else{
				$pedido = [
					"id_sucursal"		=>	1,
					"id_proveedor"		=>	$producto[$i],
					"id_user_registra"	=>	$this->ion_auth->user()->row()->id,
					"fecha_registro"	=>	$fecha->format('Y-m-d H:i:s'),
					"total"				=>	str_replace(",", "", $importe[$i])
				];
				$id_pedido = $this->ped_mdl->insert($pedido);
			}
			$detalle_pedido = [
				'id_pedido'		=>	$id_pedido,
				'id_producto'	=>	$this->input->post('id_prod'),
				'cantidad'		=>	str_replace(",", "", $this->input->post('cantidad[]')[$i]),
				'precio'		=>	str_replace(",", "", $this->input->post('precio[]')[$i]),
				'importe'		=>	str_replace(",", "", $this->input->post('importe[]')[$i])
			];
		}
	}
	public function get_update($id){
		$fecha = new DateTime(date('Y-m-d H:i:s'));
		$intervalo = new DateInterval('P3D');
		$fecha->add($intervalo);
		$data["cotizacion"] = $this->ct_mdl->get(NULL, ['id_cotizacion'=>$id])[0];
		$data["producto"] = $this->prod_mdl->get(NULL, ['id_producto'=>$data["cotizacion"]->id_producto])[0];
		$data["title"]="ACTUALIZAR COTIZACIÓN DE <br>".$data["producto"]->nombre;
		$user = $this->session->userdata();
		if($user['id_grupo'] ==2){//Proveedor
			$where=["cotizaciones.id_proveedor" => $user['id_usuario']];
			$data["cots"]=$this->ct_mdl->get_cots($where, $data["cotizacion"]->id_producto,$fecha->format('Y-m-d H:i:s'));
			$where=["cotizaciones.id_proveedor" => $user['id_usuario'], "cotizaciones.estatus" => 0];
			$data["cotss"]=$this->ct_mdl->get_cots($where, $data["cotizacion"]->id_producto,$fecha->format('Y-m-d H:i:s'));
		}else{
			$data["cots"]=$this->ct_mdl->get_cots(NULL, $data["cotizacion"]->id_producto,$fecha->format('Y-m-d H:i:s'));
			$where=["cotizaciones.estatus" => 0];
			$data["cotss"]=$this->ct_mdl->get_cots($where, $data["cotizacion"]->id_producto,$fecha->format('Y-m-d H:i:s'));
		}
		$data["view"]=$this->load->view("Cotizaciones/edit_cotizacion", $data, TRUE);
		$data["button"]="<button class='btn btn-success update_cotizacion' type='button'>
							<span class='bold'><i class='fa fa-floppy-o'></i></span> &nbsp;Guardar cambios
						</button>";
		$this->jsonResponse($data);
	}
	public function get_update2($id,$idpros){
		$fecha = new DateTime(date('Y-m-d H:i:s'));
		$intervalo = new DateInterval('P2D');
		$fecha->add($intervalo);
		$data["cotizacion"] = $this->ct_mdl->get(NULL, ['id_cotizacion'=>$id])[0];
		$data["producto"] = $this->prod_mdl->get(NULL, ['id_producto'=>$data["cotizacion"]->id_producto])[0];
		$data["title"]="ACTUALIZAR COTIZACIÓN DE <br>".$data["producto"]->nombre;
		$user = $this->session->userdata();
		$where=["cotizaciones.id_proveedor" => $idpros];
		$data["cots"]=$this->ct_mdl->get_cots($where, $data["cotizacion"]->id_producto,$fecha->format('Y-m-d H:i:s'));
		$where=["cotizaciones.id_proveedor" => $idpros, "cotizaciones.estatus" => 0];
		$data["cotss"]=$this->ct_mdl->get_cots($where, $data["cotizacion"]->id_producto,$fecha->format('Y-m-d H:i:s'));
		$data["view"]=$this->load->view("Cotizaciones/edit_cotizacion", $data, TRUE);
		$data["button"]="<button class='btn btn-success update_cotizacion' type='button'>
							<span class='bold'><i class='fa fa-floppy-o'></i></span> &nbsp;Guardar cambios
						</button>";
		$this->jsonResponse($data);
	}
	public function get_delete($id){
		$fecha = new DateTime(date('Y-m-d H:i:s'));
		$intervalo = new DateInterval('P3D');
		$fecha->add($intervalo);
		$data["cotizacion"] = $this->ct_mdl->get(NULL, ['id_cotizacion'=>$id])[0];
		$data["producto"] = $this->prod_mdl->get(NULL, ['id_producto'=>$data["cotizacion"]->id_producto])[0];
		$data["title"]="Seleccione una opción para eliminar la Cotización del producto:<br>".$data["producto"]->nombre;
		$user = $this->session->userdata();
		if($user['id_grupo'] ==2){//Proveedor
			$where=["cotizaciones.id_proveedor" => $user['id_usuario']];
			$data["cots"]=$this->ct_mdl->get_cots($where, $data["cotizacion"]->id_producto,$fecha->format('Y-m-d H:i:s'));
			$where=["cotizaciones.id_proveedor" => $user['id_usuario'], "cotizaciones.estatus" => 0];
			$data["cotss"]=$this->ct_mdl->get_cots($where, $data["cotizacion"]->id_producto,$fecha->format('Y-m-d H:i:s'));
		}else{
			$data["cots"]=$this->ct_mdl->get_cots(NULL, $data["cotizacion"]->id_producto,$fecha->format('Y-m-d H:i:s'));
			$where=["cotizaciones.estatus" => 0];
			$data["cotss"]=$this->ct_mdl->get_cots($where, $data["cotizacion"]->id_producto,$fecha->format('Y-m-d H:i:s'));
		}
		$data["view"]=$this->load->view("Cotizaciones/delete_cotizacion", $data, TRUE);
		$data["button"]="<button class='btn btn-danger delete_cotizacion' type='button'>
							<span class='bold'><i class='fa fa-trash'></i></span> &nbsp;Eliminar
						</button>";
		$this->jsonResponse($data);
	}
	public function get_delete2($id,$idpros){
		$fecha = new DateTime(date('Y-m-d H:i:s'));
		$intervalo = new DateInterval('P2D');
		$fecha->add($intervalo);
		$data["cotizacion"] = $this->ct_mdl->get(NULL, ['id_cotizacion'=>$id])[0];
		$data["producto"] = $this->prod_mdl->get(NULL, ['id_producto'=>$data["cotizacion"]->id_producto])[0];
		$data["title"]="Marque la casilla del producto :<br>".$data["producto"]->nombre;
		$where=["cotizaciones.id_proveedor" => $idpros];
		$data["cots"]=$this->ct_mdl->get_cots($where, $data["cotizacion"]->id_producto,$fecha->format('Y-m-d H:i:s'));
		$where=["cotizaciones.id_proveedor" => $idpros, "cotizaciones.estatus" => 0];
		$data["cotss"]=$this->ct_mdl->get_cots($where, $data["cotizacion"]->id_producto,$fecha->format('Y-m-d H:i:s'));
		$data["view"]=$this->load->view("Cotizaciones/delete_cotizacion", $data, TRUE);
		$data["button"]="<button class='btn btn-danger delete_cotizacion' type='button'>
							<span class='bold'><i class='fa fa-trash'></i></span> &nbsp;Eliminar
						</button>";
		$this->jsonResponse($data);
	}
	public function detallazos($id){
		$fecha = new DateTime(date('Y-m-d H:i:s'));
		$intervalo = new DateInterval('P3D');
		$fecha->add($intervalo);
		$data["cotizacion"] = $this->ct_mdl->get(NULL, ['id_cotizacion'=>$id])[0];
		$data["producto"] = $this->prod_mdl->get(NULL, ['id_producto'=>$data["cotizacion"]->id_producto])[0];
		$data["title"]="Cotizaciones Eliminadas y faltantes de :<br>".$data["producto"]->nombre;
		$user = $this->session->userdata();
		$data["faltas"] = $this->falt_mdl->getfaltas(NULL,$data["cotizacion"]->id_producto, date("Y-m-d H:i:s"));
		$data["cotss"]=$this->ct_mdl->get_cotdel(NULL, $data["cotizacion"]->id_producto,$fecha->format('Y-m-d H:i:s'));
		$fecha = new DateTime(date('Y-m-d H:i:s'));
		$intervalo = new DateInterval('P7D');
		$fecha->sub($intervalo);
		$data["lastweek"]=$this->ct_mdl->getLastWeek(NULL, $data["cotizacion"]->id_producto,$this->weekNumber($fecha->format('Y-m-d H:i:s')));
		$data["view"]=$this->load->view("Cotizaciones/detallazos", $data, TRUE);
		$this->jsonResponse($data);
	}
	public function ver_cotizacion($id,$fech){
		$data["cotizacion"] = $this->ct_mdl->get(NULL, ['id_cotizacion'=>$id])[0];
		$data["producto"] = $this->prod_mdl->get(NULL, ['id_producto'=>$data["cotizacion"]->id_producto])[0];
		$data["title"]="Cotizaciones del producto :<br>".$data["producto"]->nombre;
		$user = $this->session->userdata();
		if($user['id_grupo'] ==2){//Proveedor
			$where=["cotizaciones.id_proveedor" => $user['id_usuario']];
			$data["cots"]=$this->ct_mdl->get_cots($where, $data["cotizacion"]->id_producto,$fech);
			$where=["cotizaciones.id_proveedor" => $user['id_usuario'], "cotizaciones.estatus" => 0];
			$data["cotss"]=$this->ct_mdl->get_cots($where, $data["cotizacion"]->id_producto,$fech);
		}else{
			$data["cots"]=$this->ct_mdl->get_cots(NULL, $data["cotizacion"]->id_producto,$fech);
			$where=["cotizaciones.estatus" => 0];
			$data["cotss"]=$this->ct_mdl->get_cots($where, $data["cotizacion"]->id_producto,$fech);
		}
		$data["view"]=$this->load->view("Cotizaciones/ver_cotizacion", $data, TRUE);
		$data["button"]="";
		$this->jsonResponse($data);
	}
	public function getAdminTable(){
		$fecha = new DateTime(date('Y-m-d H:i:s'));
		$intervalo = new DateInterval('P2D');
		$fecha->add($intervalo);
		$fecha = $fecha->format('Y-m-d H:i:s');
		$data["cotizaciones"] = $this->ct_mdl->getCotz(NULL,$fecha);
		$this->jsonResponse($data);
	}
	public function buscaProdis(){
		$busca = $this->input->post("values");
		$fecha = new DateTime(date('Y-m-d H:i:s'));
		$intervalo = new DateInterval('P3D');
		$fecha->add($intervalo);
		$fecha = $fecha->format('Y-m-d H:i:s');
		$data["cotizaciones"] = $this->ct_mdl->getCotiz(NULL,$fecha,$busca);
		$this->jsonResponse($data);
	}
	public function getVolTable(){
		$fecha = new DateTime(date('Y-m-d H:i:s'));
		$intervalo = new DateInterval('P2D');
		$fecha->add($intervalo);
		$fecha = $fecha->format('Y-m-d H:i:s');
		$data["cotizados"] = $this->ct_mdl->getCotzV(NULL,$fecha);
		$this->jsonResponse($data);
	}
	public function getDirTable($directo){
		$fecha = new DateTime(date('Y-m-d H:i:s'));
		$intervalo = new DateInterval('P2D');
		$fecha->add($intervalo);
		$fecha = $fecha->format('Y-m-d H:i:s');
		$data["cotizados"] = $this->ct_mdl->getCotzD(NULL,$fecha,$directo);
		$this->jsonResponse($data);
	}
	public function getDirProv($directo){
		$fecha = new DateTime(date('Y-m-d H:i:s'));
		$intervalo = new DateInterval('P2D');
		$fecha->add($intervalo);
		$fecha = $fecha->format('Y-m-d H:i:s');
		$data["cotizados"] = $this->ct_mdl->getCotzP(NULL,$fecha,$directo);
		$this->jsonResponse($data);
	}
	public function getGrupo(){
		$user = $this->session->userdata();
		$data["ides"] = $user['id_grupo'];
		$this->jsonResponse($data);
	}
	public function set_pedido($id){
		$data["cotizacion"] = $this->ct_mdl->get(NULL, ['id_cotizacion'=>$id])[0];
		$data["producto"] = $this->prod_mdl->get(NULL, ['id_producto'=>$data["cotizacion"]->id_producto])[0];
		$data["proveedor"] = $this->ct_mdl->pedido(NULL,$data["producto"]->id_producto);
		$data["title"]="HACER PEDIDO ".$data['producto']->nombre;
		$data["view"]=$this->load->view("Cotizaciones/new_pedido", $data, TRUE);
		$data["button"]="<button class='btn btn-success new_pedido' type='button'>
							<span class='bold'><i class='fa fa-shopping-cart'></i></span> &nbsp;Hacer Pedido
						</button>";
		$this->jsonResponse($data);
	}
	public function fill_excel(){
		ini_set("memory_limit", "-1");
		ini_set("max_execution_time", "-1");
		$this->load->library("excelfile");
		$objReader = PHPExcel_IOFactory::createReader("Excel2007");
		$hoja = $objReader->load("./assets/uploads/cotiz.xlsx");
		$hoja->setActiveSheetIndex(0);

		
		$fecha = new DateTime(date('Y-m-d H:i:s'));
		$intervalo = new DateInterval('P3D');
		$fecha->add($intervalo);
		$fecha = $fecha->format('Y-m-d H:i:s');
		$cotizacionesProveedor = $this->ct_mdl->comparaCotizaciones2(NULL, $fecha,0);
		$row_print =2;
		if ($cotizacionesProveedor){
			foreach ($cotizacionesProveedor as $key => $value){
				$hoja->getActiveSheet()->setCellValue('B'.$row_print, $value['familia']);
				$this->cellStyle("B{$row_print}", "000000", "FFFFFF", TRUE, 12, "Franklin Gothic Book");
				$hoja->getActiveSheet()->getStyle("B{$row_print}")->applyFromArray(
					array(
						'font' => array('size' => 12,'bold' => true,'color' => array('rgb' => 'FFFFFF')),
						'fill' => array('type' => PHPExcel_Style_Fill::FILL_SOLID,'color' => array('rgb' => '000000'))
					)
				);
				$row_print +=1;
				if ($value['articulos']) {
					foreach ($value['articulos'] as $key => $row){
						$hoja->getActiveSheet()->getStyle("B{$row_print}")->applyFromArray(
								array(
									'font' => array('size' => 12,'bold' => false,'color' => array('rgb' => '000000')),
									'fill' => array('type' => PHPExcel_Style_Fill::FILL_SOLID,'color' => array('rgb' => 'FFFFFF'))
								)
							);
						$arrayData = array(
							array($row['codigo'],$row['producto'],$row['precio_sistema'],$row['precio_four'],$row["precio_sistema"] - $row["precio_first"],$row['precio_firsto'],$row['precio_first'],$row['proveedor_first'],$row['promocion_first'],$row['precio_maximo'],$row['precio_promedio'],$row["precio_sistema"] - $row["precio_next"],$row['precio_nexto'],$row['precio_next'],$row['proveedor_next'],$row['promocion_next'],$row['precio_nxtso'],$row['precio_nxts'],$row['proveedor_nxts'],$row['promocion_nxts'])
						);
						$hoja->getActiveSheet()->fromArray(
						    $arrayData,
						    NULL,
						    'A'.$row_print
						);

						if($row['color'] == '#92CEE3'){
							$hoja->getActiveSheet()->getStyle("A{$row_print}")->applyFromArray(
								array(
									'font' => array('size' => 12,'bold' => true,'color' => array('rgb' => '000000')),
									'fill' => array('type' => PHPExcel_Style_Fill::FILL_SOLID,'color' => array('rgb' => '92CEE3'))
								)
							);
						}else{
							$hoja->getActiveSheet()->getStyle("A{$row_print}")->applyFromArray(
								array(
									'font' => array('size' => 12,'bold' => true,'color' => array('rgb' => '000000')),
									'fill' => array('type' => PHPExcel_Style_Fill::FILL_SOLID,'color' => array('rgb' => 'FFFFFF'))
								)
							);
						}

						if($row['colorp'] == 1){
							$hoja->getActiveSheet()->getStyle("C{$row_print}")->applyFromArray(
								array(
									'font' => array('size' => 12,'bold' => false,'color' => array('rgb' => '000000')),
									'fill' => array('type' => PHPExcel_Style_Fill::FILL_SOLID,'color' => array('rgb' => 'D6DCE4'))
								)
							);
						}else{
							$hoja->getActiveSheet()->getStyle("C{$row_print}")->applyFromArray(
								array(
									'font' => array('size' => 12,'bold' => false,'color' => array('rgb' => '000000')),
									'fill' => array('type' => PHPExcel_Style_Fill::FILL_SOLID,'color' => array('rgb' => 'FFFFFF'))
								)
							);
						}
						$dif1 = $row["precio_sistema"] - $row["precio_first"];
						if($row['precio_first'] !== NULL){
							if ($dif1 >= ($row["precio_sistema"] * .30) || $dif1 <= (($row["precio_sistema"] * .30) * (-1))) {
								$this->cellStyle("E{$row_print}", "FF0066", "000000", FALSE, 12, "Franklin Gothic Book");
								$hoja->getActiveSheet()->getStyle("E{$row_print}")->applyFromArray(
									array(
										'font' => array('size' => 12,'bold' => false,'color' => array('rgb' => '000000')),
										'fill' => array('type' => PHPExcel_Style_Fill::FILL_SOLID,'color' => array('rgb' => 'FF0066'))
									)
								);
							}else{
								$hoja->getActiveSheet()->getStyle("E{$row_print}")->applyFromArray(
									array(
										'font' => array('size' => 12,'bold' => false,'color' => array('rgb' => '000000')),
										'fill' => array('type' => PHPExcel_Style_Fill::FILL_SOLID,'color' => array('rgb' => 'FFE6F0'))
									)
								);
							}
						}else{
							$hoja->getActiveSheet()->getStyle("E{$row_print}")->applyFromArray(
								array(
									'font' => array('size' => 12,'bold' => false,'color' => array('rgb' => '000000')),
									'fill' => array('type' => PHPExcel_Style_Fill::FILL_SOLID,'color' => array('rgb' => 'FFE6F0'))
								)
							);
						}
						if($row['estatus'] == 2){
							$hoja->getActiveSheet()->getStyle("B{$row_print}")->applyFromArray(
								array(
									'font' => array('size' => 12,'bold' => false,'color' => array('rgb' => '000000')),
									'fill' => array('type' => PHPExcel_Style_Fill::FILL_SOLID,'color' => array('rgb' => '00B0F0'))
								)
							);
						}
						if($row['estatus'] == 3){
							$hoja->getActiveSheet()->getStyle("B{$row_print}")->applyFromArray(
								array(
									'font' => array('size' => 12,'bold' => false,'color' => array('rgb' => '000000')),
									'fill' => array('type' => PHPExcel_Style_Fill::FILL_SOLID,'color' => array('rgb' => 'FFF900'))
								)
							);
						}
						if($row['estatus'] >= 4){
							$hoja->getActiveSheet()->getStyle("B{$row_print}")->applyFromArray(
								array(
									'font' => array('size' => 12,'bold' => false,'color' => array('rgb' => '000000')),
									'fill' => array('type' => PHPExcel_Style_Fill::FILL_SOLID,'color' => array('rgb' => '04B486'))
								)
							);
						}
						if($row['precio_sistema'] < $row['precio_first']){
							$hoja->getActiveSheet()->getStyle("G{$row_print}")->applyFromArray(
								array(
									'font' => array('size' => 12,'bold' => false,'color' => array('rgb' => 'E21111')),
									'fill' => array('type' => PHPExcel_Style_Fill::FILL_SOLID,'color' => array('rgb' => 'FDB2B2'))
								)
							);
						}else{
							$hoja->getActiveSheet()->getStyle("G{$row_print}")->applyFromArray(
								array(
									'font' => array('size' => 12,'bold' => false,'color' => array('rgb' => '0C800C')),
									'fill' => array('type' => PHPExcel_Style_Fill::FILL_SOLID,'color' => array('rgb' => '96EAA8'))
								)
							);
						}
						$dif1 = $row["precio_sistema"] - $row["precio_next"];
						if($row['precio_next'] !== NULL){
							if ($dif1 >= ($row["precio_sistema"] * .30) || $dif1 <= (($row["precio_sistema"] * .30) * (-1))) {
								$hoja->getActiveSheet()->getStyle("L{$row_print}")->applyFromArray(
									array(
										'font' => array('size' => 12,'bold' => false,'color' => array('rgb' => '000000')),
										'fill' => array('type' => PHPExcel_Style_Fill::FILL_SOLID,'color' => array('rgb' => 'FF0066'))
									)
								);
							}else{
								$hoja->getActiveSheet()->getStyle("L{$row_print}")->applyFromArray(
									array(
										'font' => array('size' => 12,'bold' => false,'color' => array('rgb' => '000000')),
										'fill' => array('type' => PHPExcel_Style_Fill::FILL_SOLID,'color' => array('rgb' => 'FFE6F0'))
									)
								);
							}
						}else{
							$hoja->getActiveSheet()->getStyle("L{$row_print}")->applyFromArray(
									array(
										'font' => array('size' => 12,'bold' => false,'color' => array('rgb' => '000000')),
										'fill' => array('type' => PHPExcel_Style_Fill::FILL_SOLID,'color' => array('rgb' => 'FFE6F0'))
									)
								);
						}
						
						if($row['precio_sistema'] < $row['precio_next']){
							$hoja->getActiveSheet()->getStyle("N{$row_print}")->applyFromArray(
									array(
										'font' => array('size' => 12,'bold' => false,'color' => array('rgb' => 'E21111')),
										'fill' => array('type' => PHPExcel_Style_Fill::FILL_SOLID,'color' => array('rgb' => 'FDB2B2'))
									)
								);
						}else if($row['precio_next'] !== NULL){
							$hoja->getActiveSheet()->getStyle("N{$row_print}")->applyFromArray(
									array(
										'font' => array('size' => 12,'bold' => false,'color' => array('rgb' => '0C800C')),
										'fill' => array('type' => PHPExcel_Style_Fill::FILL_SOLID,'color' => array('rgb' => '96EAA8'))
									)
								);
						}else{
							$hoja->getActiveSheet()->getStyle("N{$row_print}")->applyFromArray(
									array(
										'font' => array('size' => 12,'bold' => false,'color' => array('rgb' => '000000')),
										'fill' => array('type' => PHPExcel_Style_Fill::FILL_SOLID,'color' => array('rgb' => 'FFFFFF'))
									)
								);
						}
						
						if($row['precio_sistema'] < $row['precio_nxts']){
							$hoja->getActiveSheet()->getStyle("R{$row_print}")->applyFromArray(
									array(
										'font' => array('size' => 12,'bold' => false,'color' => array('rgb' => 'E21111')),
										'fill' => array('type' => PHPExcel_Style_Fill::FILL_SOLID,'color' => array('rgb' => 'FDB2B2'))
									)
								);
						}else if($row['precio_next'] !== NULL){
							$hoja->getActiveSheet()->getStyle("R{$row_print}")->applyFromArray(
									array(
										'font' => array('size' => 12,'bold' => false,'color' => array('rgb' => '0C800C')),
										'fill' => array('type' => PHPExcel_Style_Fill::FILL_SOLID,'color' => array('rgb' => '96EAA8'))
									)
								);
						}else{
							$hoja->getActiveSheet()->getStyle("R{$row_print}")->applyFromArray(
									array(
										'font' => array('size' => 12,'bold' => false,'color' => array('rgb' => '000000')),
										'fill' => array('type' => PHPExcel_Style_Fill::FILL_SOLID,'color' => array('rgb' => 'FFFFFF'))
									)
								);
						}
						$row_print ++;
					}
				}
			}
		}
        $dias = array("DOMINGO","LUNES","MARTES","MIÉRCOLES","JUEVES","VIERNES","SÁBADO");
		$meses = array("ENERO","FEBRERO","MARZO","ABRIL","MAYO","JUNIO","JULIO","AGOSTO","SEPTIEMBRE","OCTUBRE","NOVIEMBRE","DICIEMBRE");
		$fecha =  $dias[date('w')]." ".date('d')." DE ".$meses[date('n')-1]. " DEL ".date('Y') ;
		$file_name = "COTIZACIÓN ".$fecha.".xlsx"; //Nombre del documento con extención
		header("Content-Type: application/vnd.ms-excel; charset=utf-8");
		header("Content-Disposition: attachment;filename=".$file_name);
		header("Cache-Control: max-age=0");
		$excel_Writer = PHPExcel_IOFactory::createWriter($hoja, "Excel2007");
		$excel_Writer->save("php://output");
	}
	public function fill_excel_pro(){
		if ($this->input->post('id_pro') == 3 || $this->input->post('id_pro') == "3") {
			$this->fill_excel_duero();
		}else{
			ini_set("memory_limit", "-1");
			$this->load->library("excelfile");
			$objReader = PHPExcel_IOFactory::createReader("Excel2007");
			$hoja = $objReader->load("./assets/uploads/cotiz2.xlsx");
			$hoja->setActiveSheetIndex(0);

			$hoja->getActiveSheet()->getStyle("C2")->applyFromArray(
				array(
					'font' => array('size' => 12,'bold' => true,'color' => array('rgb' => 'FFFFFF')),
					'fill' => array('type' => PHPExcel_Style_Fill::FILL_SOLID,'color' => array('rgb' => '000000'))
				)
			);
			$productos = $this->prod_mdl->getProdFam(NULL,$this->input->post("id_pro"));
			$provs = $this->usua_mdl->get(NULL, ['id_usuario'=>$this->input->post('id_pro')])[0];
			$row_print = 2;
			if ($productos){
				foreach ($productos as $key => $value){
					$arrayData = array(
						array($value['familia'],$provs->nombre.' '.$provs->apellido,)
					);
					$hoja->getActiveSheet()->fromArray(
					    $arrayData,
					    NULL,
					    'B'.$row_print
					);
					$hoja->getActiveSheet()->getStyle("B{$row_print}")->applyFromArray(
						array(
							'font' => array('size' => 12,'bold' => true,'color' => array('rgb' => 'FFFFFF')),
							'fill' => array('type' => PHPExcel_Style_Fill::FILL_SOLID,'color' => array('rgb' => '000000'))
						)
					);

					$row_print +=1;
					if ($value['articulos']) {
						foreach ($value['articulos'] as $key => $row){
							$conversion = str_replace("CWEY", "C".$row_print, $row['conversion']);
							$conversion = str_replace("HWEY", "H".$row_print, $conversion);
							$conversion = str_replace("DWEY", "D".$row_print, $conversion);

							$arrayData = array(
								array($row['codigo'],$row['producto'],$row['precio'],$row['observaciones'],$row['num_one'],$row['num_two'],$row['descuento'],"",$conversion,$row['precio2'])
							);
							$hoja->getActiveSheet()->fromArray(
							    $arrayData,
							    NULL,
							    'A'.$row_print
							);
							$hoja->getActiveSheet()->getStyle("B{$row_print}:H{$row_print}")->applyFromArray(
								array(
									'font' => array('size' => 12,'bold' => false,'color' => array('rgb' => '000000')),
									'fill' => array('type' => PHPExcel_Style_Fill::FILL_SOLID,'color' => array('rgb' => 'FFFFFF'))
								)
							);
							if($row['color'] == '#92CEE3'){
								$hoja->getActiveSheet()->getStyle("A{$row_print}")->applyFromArray(
									array(
										'font' => array('size' => 10,'bold' => false,'color' => array('rgb' => '000000')),
										'fill' => array('type' => PHPExcel_Style_Fill::FILL_SOLID,'color' => array('rgb' => '92CEE3'))
									)
								);
							}else{
								$hoja->getActiveSheet()->getStyle("A{$row_print}")->applyFromArray(
									array(
										'font' => array('size' => 10,'bold' => false,'color' => array('rgb' => '000000')),
										'fill' => array('type' => PHPExcel_Style_Fill::FILL_SOLID,'color' => array('rgb' => 'FFFFFF'))
									)
								);
							}
							
							if($row['estatus'] == 2){
								$hoja->getActiveSheet()->getStyle("B{$row_print}")->applyFromArray(
									array(
										'font' => array('size' => 10,'bold' => false,'color' => array('rgb' => '000000')),
										'fill' => array('type' => PHPExcel_Style_Fill::FILL_SOLID,'color' => array('rgb' => '00B0F0'))
									)
								);
							}
							if($row['estatus'] == 3){
								$hoja->getActiveSheet()->getStyle("B{$row_print}")->applyFromArray(
									array(
										'font' => array('size' => 10,'bold' => false,'color' => array('rgb' => '000000')),
										'fill' => array('type' => PHPExcel_Style_Fill::FILL_SOLID,'color' => array('rgb' => 'FFF900'))
									)
								);
							}
							if($row['estatus'] >= 4){
								$hoja->getActiveSheet()->getStyle("B{$row_print}:C{$row_print}")->applyFromArray(
									array(
										'font' => array('size' => 10,'bold' => false,'color' => array('rgb' => '000000')),
										'fill' => array('type' => PHPExcel_Style_Fill::FILL_SOLID,'color' => array('rgb' => '04B486'))
									)
								);
							}
							if($row['colorp'] == 1){
								$hoja->getActiveSheet()->getStyle("C{$row_print}")->applyFromArray(
									array(
										'font' => array('size' => 10,'bold' => false,'color' => array('rgb' => '000000')),
										'fill' => array('type' => PHPExcel_Style_Fill::FILL_SOLID,'color' => array('rgb' => 'D6DCE4'))
									)
								);
							}else{
								$this->cellStyle("C{$row_print}", "FFFFFF", "000000", FALSE, 10, "Franklin Gothic Book");
								$hoja->getActiveSheet()->getStyle("C{$row_print}")->applyFromArray(
									array(
										'font' => array('size' => 10,'bold' => false,'color' => array('rgb' => '000000')),
										'fill' => array('type' => PHPExcel_Style_Fill::FILL_SOLID,'color' => array('rgb' => 'FFFFFF'))
									)
								);
							}
							if ($row['sem2'] <> NULL) {
								$hoja->getActiveSheet()->getStyle("C{$row_print}")->applyFromArray(
									array(
										'font' => array('size' => 10,'bold' => false,'color' => array('rgb' => '000000')),
										'fill' => array('type' => PHPExcel_Style_Fill::FILL_SOLID,'color' => array('rgb' => 'F79646'))
									)
								);
							}elseif ($row['sem1'] <> NULL) {
								$hoja->getActiveSheet()->getStyle("C{$row_print}")->applyFromArray(
									array(
										'font' => array('size' => 10,'bold' => false,'color' => array('rgb' => '000000')),
										'fill' => array('type' => PHPExcel_Style_Fill::FILL_SOLID,'color' => array('rgb' => 'F79646'))
									)
								);
							}

							if(($this->weekNumber($row['fecha_registro']) >= ($this->weekNumber() -1)) && date('Y', strtotime($row['fecha_registro'])) === '2021'){
								$arrayData = array(
									array("NUEVO")
								);
								$hoja->getActiveSheet()->fromArray(
								    $arrayData,
								    NULL,
								    'H'.$row_print
								);
								$hoja->getActiveSheet()->getStyle("A{$row_print}:G{$row_print}")->applyFromArray(
									array(
										'font' => array('size' => 10,'bold' => false,'color' => array('rgb' => '000000')),
										'fill' => array('type' => PHPExcel_Style_Fill::FILL_SOLID,'color' => array('rgb' => 'FF7F71'))
									)
								);
								$hoja->getActiveSheet()->getStyle("C{$row_print}")->applyFromArray(
									array(
										'font' => array('size' => 10,'bold' => true,'color' => array('rgb' => '000000')),
										'fill' => array('type' => PHPExcel_Style_Fill::FILL_SOLID,'color' => array('rgb' => 'FF7F71'))
									)
								);
							}
							$row_print++;
						}
					}
				}
			}

			$file_name = "Cotización ".$provs->nombre.".xlsx"; //Nombre del documento con extención

			header("Content-Type: application/vnd.ms-excel; charset=utf-8");
			header("Content-Disposition: attachment;filename=".$file_name);
			header("Cache-Control: max-age=0");
			$excel_Writer = PHPExcel_IOFactory::createWriter($hoja, "Excel2007");
			$excel_Writer->save("php://output");
		}
	}
	public function fill_excelV(){
		ini_set("memory_limit", "-1");
		$this->load->library("excelfile");
		$hoja = $this->excelfile->getActiveSheet();
		$hoja->getDefaultStyle()
		    ->getBorders()
		    ->getTop()
		        ->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
		$hoja->getDefaultStyle()
		    ->getBorders()
		    ->getBottom()
		        ->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
		$hoja->getDefaultStyle()
		    ->getBorders()
		    ->getLeft()
		        ->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
		$hoja->getDefaultStyle()
		    ->getBorders()
		    ->getRight()
		        ->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
		$this->cellStyle("A1:N2", "000000", "FFFFFF", TRUE, 12, "Franklin Gothic Book");
		$hoja->setCellValue("A2", "CÓDIGO")->getColumnDimension('A')->setWidth(30); //Nombre y ajuste de texto a la columna
		$hoja->setCellValue("B1", "DESCRIPCIÓN")->getColumnDimension('B')->setWidth(50);
		$hoja->setCellValue("C2", "SISTEMA")->getColumnDimension('C')->setWidth(12);
		$hoja->setCellValue("D2", "PRECIO 4")->getColumnDimension('D')->setWidth(12);
		$hoja->setCellValue("E1", "PRECIO MENOR")->getColumnDimension('E')->setWidth(12);
		$hoja->setCellValue("F1", "PRECIO PROMOCIÓN")->getColumnDimension('F')->setWidth(12);
		$hoja->setCellValue("G1", "PROVEEDOR")->getColumnDimension('G')->setWidth(20);
		$hoja->setCellValue("H1", "OBSERVACIÓN")->getColumnDimension('H')->setWidth(30);
		$hoja->setCellValue("I1", "PRECIO MÁXIMO")->getColumnDimension('I')->setWidth(12);
		$hoja->setCellValue("J1", "PRECIO PROMEDIO")->getColumnDimension('J')->setWidth(12);
		$hoja->setCellValue("K1", "2DO PRECIO")->getColumnDimension('K')->setWidth(12);
		$hoja->setCellValue("L1", "PRECIO PROMOCIÓN")->getColumnDimension('L')->setWidth(12);
		$hoja->setCellValue("M1", "2DO PROVEEDOR")->getColumnDimension('M')->setWidth(20);
		$hoja->setCellValue("N1", "2DA OBSERVACIÓN")->getColumnDimension('N')->setWidth(30);
		$where=["prod.estatus"=>2];//Semana actual
		$fecha = new DateTime(date('Y-m-d H:i:s'));
		$intervalo = new DateInterval('P2D');
		$fecha->add($intervalo);
		$fecha = $fecha->format('Y-m-d H:i:s');
		$cotizacionesProveedor = $this->ct_mdl->comparaCotizaciones2($where, $fecha,0);
		$border_style= array('borders' => array('right' => array('style' =>
			PHPExcel_Style_Border::BORDER_THIN,'color' => array('argb' => '000000'),)));
		$row_print =3;
		if ($cotizacionesProveedor){
			foreach ($cotizacionesProveedor as $key => $value){
				if ($value['articulos']) {
					foreach ($value['articulos'] as $key => $row){
						$this->cellStyle("A{$row_print}", "FFFFFF", "000000", TRUE, 12, "Franklin Gothic Book");
						$this->cellStyle("B{$row_print}:L{$row_print}", "FFFFFF", "000000", FALSE, 12, "Franklin Gothic Book");
						$hoja->setCellValue("A{$row_print}", $row['codigo'])->getStyle("A{$row_print}")->getNumberFormat()->setFormatCode('# ???/???');//Formato de fraccion
						$hoja->getStyle("A{$row_print}")->applyFromArray($border_style);
						$hoja->setCellValue("B{$row_print}", $row['producto']);
						$hoja->getStyle("B{$row_print}")->applyFromArray($border_style);
						$hoja->setCellValue("C{$row_print}", $row['precio_sistema'])->getStyle("C{$row_print}")->getNumberFormat()->setFormatCode('"$"#,##0.00_-');//Formto de moneda
						$hoja->getStyle("C{$row_print}")->applyFromArray($border_style);
						$hoja->setCellValue("D{$row_print}", $row['precio_four'])->getStyle("D{$row_print}")->getNumberFormat()->setFormatCode('"$"#,##0.00_-');
						$hoja->getStyle("D{$row_print}")->applyFromArray($border_style);
						$hoja->setCellValue("E{$row_print}", $row['precio_firsto'])->getStyle("E{$row_print}")->getNumberFormat()->setFormatCode('"$"#,##0.00_-');
						$hoja->getStyle("E{$row_print}")->applyFromArray($border_style);
						if($row['estatus'] == 2){
							$this->cellStyle("B{$row_print}", "00B0F0", "000000", FALSE, 12, "Franklin Gothic Book");
						}
						if($row['estatus'] == 3){
							$this->cellStyle("B{$row_print}", "FFF900", "000000", FALSE, 12, "Franklin Gothic Book");
						}
						if($row['estatus'] >= 4){
							$this->cellStyle("B{$row_print}", "04B486", "000000", FALSE, 12, "Franklin Gothic Book");
						}
						if($row['precio_sistema'] < $row['precio_first']){
							$hoja->setCellValue("F{$row_print}", $row['precio_first'])->getStyle("F{$row_print}")->getNumberFormat()->setFormatCode('"$"#,##0.00_-');
							$this->cellStyle("F{$row_print}", "FDB2B2", "E21111", FALSE, 12, "Franklin Gothic Book");
						}else{
							$hoja->setCellValue("F{$row_print}", $row['precio_first'])->getStyle("F{$row_print}")->getNumberFormat()->setFormatCode('"$"#,##0.00_-');
							$this->cellStyle("F{$row_print}", "96EAA8", "0C800C", FALSE, 12, "Franklin Gothic Book");
						}
						$hoja->getStyle("F{$row_print}")->applyFromArray($border_style);
						$hoja->setCellValue("G{$row_print}", $row['proveedor_first'])->getStyle("G{$row_print}");
						$hoja->getStyle("G{$row_print}")->applyFromArray($border_style);
						$hoja->setCellValue("H{$row_print}", $row['promocion_first'])->getStyle("H{$row_print}");
						$hoja->getStyle("H{$row_print}")->applyFromArray($border_style);
						$hoja->setCellValue("I{$row_print}", $row['precio_maximo'])->getStyle("I{$row_print}")->getNumberFormat()->setFormatCode('"$"#,##0.00_-');
						$hoja->getStyle("I{$row_print}")->applyFromArray($border_style);
						$hoja->setCellValue("J{$row_print}", $row['precio_promedio'])->getStyle("J{$row_print}")->getNumberFormat()->setFormatCode('"$"#,##0.00_-');
						$hoja->getStyle("J{$row_print}")->applyFromArray($border_style);
						$hoja->setCellValue("K{$row_print}", $row['precio_nexto'])->getStyle("K{$row_print}")->getNumberFormat()->setFormatCode('"$"#,##0.00_-');
						$hoja->getStyle("K{$row_print}")->applyFromArray($border_style);
						if($row['precio_sistema'] < $row['precio_next']){
							$hoja->setCellValue("L{$row_print}", $row['precio_next'])->getStyle("L{$row_print}")->getNumberFormat()->setFormatCode('"$"#,##0.00_-');
							$this->cellStyle("L{$row_print}", "FDB2B2", "E21111", FALSE, 12, "Franklin Gothic Book");
						}else if($row['precio_next'] !== NULL){
							$hoja->setCellValue("L{$row_print}", $row['precio_next'])->getStyle("L{$row_print}")->getNumberFormat()->setFormatCode('"$"#,##0.00_-');
							$this->cellStyle("L{$row_print}", "96EAA8", "0C800C", FALSE, 12, "Franklin Gothic Book");
						}else{
							$hoja->setCellValue("L{$row_print}", $row['precio_next'])->getStyle("L{$row_print}")->getNumberFormat()->setFormatCode('"$"#,##0.00_-');
							$this->cellStyle("L{$row_print}", "FFFFFF", "000000", FALSE, 12, "Franklin Gothic Book");
						}
						$hoja->getStyle("L{$row_print}")->applyFromArray($border_style);
						$hoja->setCellValue("M{$row_print}", $row['proveedor_next'])->getStyle("M{$row_print}");
						$hoja->getStyle("M{$row_print}")->applyFromArray($border_style);
						$hoja->setCellValue("N{$row_print}", $row['promocion_next'])->getStyle("N{$row_print}");
						$hoja->getStyle("N{$row_print}")->applyFromArray($border_style);
						$row_print ++;
					}
				}
			}
		}
		$hoja->getStyle("A3:N{$row_print}")
                 ->getAlignment()
                 ->setHorizontal(\PHPExcel_Style_Alignment::HORIZONTAL_LEFT);
		$file_name = "Cotizaciones Volúmenes.xlsx"; //Nombre del documento con extención
		header("Content-Type: application/vnd.ms-excel; charset=utf-8");
		header("Content-Disposition: attachment;filename=".$file_name);
		header("Cache-Control: max-age=0");
		$excel_Writer = PHPExcel_IOFactory::createWriter($this->excelfile, "Excel2007");
		$excel_Writer->save("php://output");
	}
	public function upload_cotizaciones($idesp){
		$fecha = new DateTime(date('Y-m-d H:i:s'));
		$intervalo = new DateInterval('P3D');
		$fecha->add($intervalo);
		if($idesp == 0){
			$proveedor = $this->session->userdata('id_usuario');
		}else{
			$proveedor = $idesp;
		}
		$cfile =  $this->usua_mdl->get(NULL, ['id_usuario' => $proveedor])[0];
		$nams = "user".date("dmyHis");
		$filen = "cotizacion".$nams."".rand();
		$config['upload_path']          = './assets/uploads/cotizaciones/';
        $config['allowed_types']        = 'xlsx|xls';
        $config['max_size']             = 10000;
        $config['max_width']            = 10024;
        $config['max_height']           = 7680;
        $this->load->library('upload', $config);
        $this->upload->initialize($config);
        $this->upload->do_upload('file_otizaciones',$filen);
		$this->load->library("excelfile");
		ini_set("memory_limit", -1);
		$file = $_FILES["file_otizaciones"]["tmp_name"];
		$filename=$_FILES['file_otizaciones']['name'];
		$sheet = PHPExcel_IOFactory::load($file);
		$objExcel = PHPExcel_IOFactory::load($file);
		$sheet = $objExcel->getSheet(0);
		$num_rows = $sheet->getHighestDataRow();
		for ($i=3; $i<=$num_rows; $i++) {
			if($sheet->getCell('C'.$i)->getValue() > 0){
				$productos = $this->prod_mdl->get("id_producto",['codigo'=> htmlspecialchars($this->getOldVal($sheet,$i,"A"), ENT_QUOTES, 'UTF-8')])[0];
				if (sizeof($productos) > 0) {
					$precio=0; $column_one=0; $column_two=0; $descuento=0; $precio_promocion=0;
					$precio = str_replace("$", "", str_replace(",", "replace",$this->getOldVal($sheet,$i,"C")));
					$column_one =$this->getOldVal($sheet,$i,"E");
					$column_two = $this->getOldVal($sheet,$i,"F");
					$descuento = $this->getOldVal($sheet,$i,"G");
					if ($column_one ==1 && $column_two ==1) {
						$precio_promocion = (($precio * $column_two)/($column_one+$column_two));
					}elseif ($column_one >=1 && $column_two >1) {
						$precio_promocion = (($precio * $column_two)/($column_one+$column_two));
					}elseif ($descuento >0) {
						$precio_promocion = ($precio - ($precio * ($descuento/100)));
					}else{
						$precio_promocion = $precio;
					}
					$antes =  $this->falt_mdl->get(NULL, ['id_producto' => $productos->id_producto, 'fecha_termino > ' => date("Y-m-d H:i:s"), 'id_proveedor' => $proveedor])[0];
					$cotiz =  $this->ct_mdl->get(NULL, ['id_producto' => $productos->id_producto, 'WEEKOFYEAR(fecha_registro)' => $this->weekNumber($fecha->format('Y-m-d H:i:s')), 'id_proveedor' => $proveedor])[0];
					if($antes){
						$new_cotizacion=[
							"id_producto"		=>	$productos->id_producto,
							"id_proveedor"		=>	$proveedor,//Recupera el id_usuario activo
							"precio"			=>	$precio,
							"num_one"			=>	$column_one,
							"num_two"			=>	$column_two,
							"descuento"			=>	$descuento,
							"precio_promocion"	=>	$precio_promocion,
							"fecha_registro"	=>	$fecha->format('Y-m-d H:i:s'),
							"observaciones"		=>	$this->getOldVal($sheet,$i,"D"),
							"estatus" => 0];

							$conversion = str_replace("C".$i, "CWEY",$sheet->getCell('I'.$i)->getValue());
							$conversion = str_replace("H".$i, "HWEY",$conversion);
							$conversion = str_replace("D".$i, "DWEY",$conversion);

							$conv = $this->conve_mdl->get(NULL,["id_producto"=>$productos->id_producto,"id_proveedor"=>$proveedor])[0];
							$new_conversion = [
								"id_producto"	=>	$productos->id_producto,
								"id_proveedor"	=>	$proveedor,
								"conversion"	=>	$conversion
							];
							if($conv){
								$data['cotizacion']=$this->conve_mdl->update($new_conversion, ['id_conversion' => $conv->id_conversion]);
							}else{
								$data['cotizacion']=$this->conve_mdl->insert($new_conversion);	
							}
						if($cotiz){
							$data['cotizacion']=$this->ct_mdl->update($new_cotizacion, ['id_cotizacion' => $cotiz->id_cotizacion]);
							//$data['cotizacin']=$this->ctb_mdl->update($new_cotizacion, ['id_cotizacion' => $cotiz->id_cotizacion]);
						}else{
							$data['cotizacion']=$this->ct_mdl->insert($new_cotizacion);
							//$data['cotizacin']=$this->ctb_mdl->insert($new_cotizacion);
						}
					}else{
						$new_cotizacion=[
							"id_producto"		=>	$productos->id_producto,
							"id_proveedor"		=>	$proveedor,//Recupera el id_usuario activo
							"precio"			=>	$precio,
							"num_one"			=>	$column_one,
							"num_two"			=>	$column_two,
							"descuento"			=>	$descuento,
							"precio_promocion"	=>	$precio_promocion,
							"fecha_registro"	=>	$fecha->format('Y-m-d H:i:s'),
							"observaciones"		=>	$this->getOldVal($sheet,$i,"D"),
							"estatus"			=> 1
						];
						$conversion = str_replace("C".$i, "CWEY",$sheet->getCell('I'.$i)->getValue());
						$conversion = str_replace("H".$i, "HWEY",$conversion);
						$conversion = str_replace("D".$i, "DWEY",$conversion);

						$conv = $this->conve_mdl->get(NULL,["id_producto"=>$productos->id_producto,"id_proveedor"=>$proveedor])[0];
						$new_conversion = [
							"id_producto"	=>	$productos->id_producto,
							"id_proveedor"	=>	$proveedor,
							"conversion"	=>	$conversion
						];
						if($conv){
							$data['cotizacion']=$this->conve_mdl->update($new_conversion, ['id_conversion' => $conv->id_conversion]);
						}else{
							$data['cotizacion']=$this->conve_mdl->insert($new_conversion);	
						}


						if($cotiz){
							$data['cotizacion']=$this->ct_mdl->update($new_cotizacion, ['id_cotizacion' => $cotiz->id_cotizacion]);
						}else{
							$data['cotizacion']=$this->ct_mdl->insert($new_cotizacion);
						}
					}
				}
			}
		}
		if (!isset($new_cotizacion)) {
			$mensaje=[	"id"	=>	'Error',
						"desc"	=>	'El Archivo esta sin precios',
						"type"	=>	'error'];
		}else{
			if (sizeof($new_cotizacion) > 0) {
				$aprov = $this->usua_mdl->get(NULL, ['id_usuario'=>$proveedor])[0];
				$cambios=[
						"id_usuario"		=>	$this->session->userdata('id_usuario'),
						"fecha_cambio"		=>	date("Y-m-d H:i:s"),
						"antes"			=>	"El usuario sube archivo de cotizaciones de ".$aprov->nombre,
						"despues"			=>	"assets/uploads/cotizaciones/".$filen.".xlsx",
						"accion"			=>	"Sube Archivo"
					];
				$data['cambios']=$this->cambio_md->insert($cambios);
				$mensaje=[	"id"	=>	'Éxito',
							"desc"	=>	'Cotizaciones cargadas correctamente en el Sistema',
							"type"	=>	'success'];
			}else{
				$mensaje=[	"id"	=>	'Error',
							"desc"	=>	'Las Cotizaciones no se cargaron al Sistema',
							"type"	=>	'error'];
			}
		}
		$this->jsonResponse($mensaje);
	}
	public function upload_cotizacionesP($idesp){
		$fecha = new DateTime(date('Y-m-d H:i:s'));
		$intervalo = new DateInterval('P3D');
		$fecha->add($intervalo);
		if($idesp == 0){
			$proveedor = $this->session->userdata('id_usuario');
		}else{
			$proveedor = $idesp;
		}

		$cfile =  $this->usua_mdl->get(NULL, ['id_usuario' => $proveedor])[0];
		$nams = "prov".date("dmyHis");
		$filen = "cotizacion".$nams."".rand();
		$config['upload_path']          = './assets/uploads/cotizaciones/';
        $config['allowed_types']        = 'xlsx|xls';
        $config['max_size']             = 10000;
        $config['max_width']            = 10024;
        $config['max_height']           = 7680;
        $this->load->library('upload', $config);
        $this->upload->initialize($config);
        $this->upload->do_upload('file_otizaciones',$filen);
		$this->load->library("excelfile");
		ini_set("memory_limit", -1);
		$file = $_FILES["file_otizaciones"]["tmp_name"];
		$filename=$_FILES['file_otizaciones']['name'];
		$sheet = PHPExcel_IOFactory::load($file);
		$objExcel = PHPExcel_IOFactory::load($file);
		$sheet = $objExcel->getSheet(0);
		$num_rows = $sheet->getHighestDataRow();
		for ($i=3; $i<=$num_rows; $i++) {
			if($sheet->getCell('C'.$i)->getValue() > 0){
				$productos = $this->prod_mdl->get("id_producto",['codigo'=> htmlspecialchars($this->getOldVal($sheet,$i,"A"), ENT_QUOTES, 'UTF-8')])[0];
				if (sizeof($productos) > 0) {
					$precio=0; $column_one=0; $column_two=0; $descuento=0; $precio_promocion=0;
					$precio = str_replace("$", "", str_replace(",", "replace",$this->getOldVal($sheet,$i,"C")));
					$column_one =$this->getOldVal($sheet,$i,"E");
					$column_two = $this->getOldVal($sheet,$i,"F");
					$descuento = $this->getOldVal($sheet,$i,"G");
					if ($column_one ==1 && $column_two ==1) {
						$precio_promocion = (($precio * $column_two)/($column_one+$column_two));
					}elseif ($column_one >=1 && $column_two >1) {
						$precio_promocion = (($precio * $column_two)/($column_one+$column_two));
					}elseif ($descuento >0) {
						$precio_promocion = ($precio - ($precio * ($descuento/100)));
					}else{
						$precio_promocion = $precio;
					}
					$antes =  $this->falt_mdl->get(NULL, ['id_producto' => $productos->id_producto, 'fecha_termino > ' => date("Y-m-d H:i:s"), 'id_proveedor' => $proveedor])[0];
					$cotiz =  $this->ctpr_mdl->get(NULL, ['id_producto' => $productos->id_producto, 'WEEKOFYEAR(fecha_registro)' => $this->weekNumber($fecha->format('Y-m-d H:i:s')), 'id_proveedor' => $proveedor])[0];
					if($antes){
						$new_cotizacion=[
							"id_producto"		=>	$productos->id_producto,
							"id_proveedor"		=>	$proveedor,//Recupera el id_usuario activo
							"precio"			=>	$precio,
							"num_one"			=>	$column_one,
							"num_two"			=>	$column_two,
							"descuento"			=>	$descuento,
							"precio_promocion"	=>	$precio_promocion,
							"fecha_registro"	=>	$fecha->format('Y-m-d H:i:s'),
							"observaciones"		=>	$this->getOldVal($sheet,$i,"D"),
							"estatus" => 0];
						if($cotiz){
							$data['cotizacion']=$this->ctpr_mdl->update($new_cotizacion, ['id_cotizacion' => $cotiz->id_cotizacion]);
							//$data['cotizacin']=$this->ctb_mdl->update($new_cotizacion, ['id_cotizacion' => $cotiz->id_cotizacion]);
						}else{
							$data['cotizacion']=$this->ctpr_mdl->insert($new_cotizacion);
							//$data['cotizacin']=$this->ctb_mdl->insert($new_cotizacion);
						}
					}else{
						$new_cotizacion=[
							"id_producto"		=>	$productos->id_producto,
							"id_proveedor"		=>	$proveedor,//Recupera el id_usuario activo
							"precio"			=>	$precio,
							"num_one"			=>	$column_one,
							"num_two"			=>	$column_two,
							"descuento"			=>	$descuento,
							"precio_promocion"	=>	$precio_promocion,
							"fecha_registro"	=>	$fecha->format('Y-m-d H:i:s'),
							"observaciones"		=>	$this->getOldVal($sheet,$i,"D"),
							"estatus"			=> 1
						];
						if($cotiz){
							$data['cotizacion']=$this->ctpr_mdl->update($new_cotizacion, ['id_cotizacion' => $cotiz->id_cotizacion]);
						}else{
							$data['cotizacion']=$this->ctpr_mdl->insert($new_cotizacion);
						}
					}
				}
			}
		}
		if (!isset($new_cotizacion)) {
			$mensaje=[	"id"	=>	'Error',
						"desc"	=>	'El Archivo esta sin precios',
						"type"	=>	'error'];
		}else{
			if (sizeof($new_cotizacion) > 0) {
				$aprov = $this->usua_mdl->get(NULL, ['id_usuario'=>$proveedor])[0];
				$cambios=[
						"id_usuario"		=>	$this->session->userdata('id_usuario'),
						"fecha_cambio"		=>	date("Y-m-d H:i:s"),
						"antes"			=>	"El proveedor sube archivo de cotizaciones de ".$aprov->nombre,
						"despues"			=>	"assets/uploads/cotizaciones/".$filen.".xlsx",
						"accion"			=>	"Sube Archivo"
					];
				$data['cambios']=$this->cambio_md->insert($cambios);
				$mensaje=[	"id"	=>	'Éxito',
							"desc"	=>	'Cotizaciones cargadas correctamente en el Sistema',
							"type"	=>	'success'];
			}else{
				$mensaje=[	"id"	=>	'Error',
							"desc"	=>	'Las Cotizaciones no se cargaron al Sistema',
							"type"	=>	'error'];
			}
		}
		$this->jsonResponse($mensaje);
	}
	public function cotzOneByOne($idesp){
		$fecha = new DateTime(date('Y-m-d H:i:s'));
		$intervalo = new DateInterval('P3D');
		$fecha->add($intervalo);
		$proveedor = $idesp;

		$cfile =  $this->usua_mdl->get(NULL, ['id_usuario' => $proveedor])[0];
		$nams = preg_replace('/\s+/', '_', $cfile->nombre);
		$filen = "Cotizacion".$nams."".rand();
		$config['upload_path']          = './assets/uploads/cotizaciones/';
        $config['allowed_types']        = 'xlsx|xls';
        $config['max_size']             = 10000;
        $config['max_width']            = 1024;
        $config['max_height']           = 768;
        $this->load->library('upload', $config);
        $this->upload->initialize($config);
        $this->upload->do_upload('file_otizaciones',$filen);
		$this->load->library("excelfile");
		ini_set("memory_limit", -1);
		$file = $_FILES["file_otizaciones"]["tmp_name"];
		$filename=$_FILES['file_otizaciones']['name'];
		$sheet = PHPExcel_IOFactory::load($file);
		$objExcel = PHPExcel_IOFactory::load($file);
		$sheet = $objExcel->getSheet(0);
		$num_rows = $sheet->getHighestDataRow();
		$new_cotizacion = [];
		$flag = 0;$faltis= 0;$cotizados = 0;

		for ($i=3; $i<=$num_rows; $i++) {
			if($sheet->getCell('C'.$i)->getValue() > 0){
				$productos = $this->prod_mdl->get("id_producto",['codigo'=> htmlspecialchars($sheet->getCell('A'.$i)->getValue(), ENT_QUOTES, 'UTF-8')])[0];
				if (sizeof($productos) > 0) {
					$precio=0; $column_one=0; $column_two=0; $descuento=0; $precio_promocion=0;
					$precio = str_replace("$", "", str_replace(",", "replace", $sheet->getCell('C'.$i)->getValue()));
					$column_one = $sheet->getCell('E'.$i)->getValue();
					$column_two = $sheet->getCell('F'.$i)->getValue();
					$descuento = $sheet->getCell('G'.$i)->getValue();
					if ($column_one ==1 && $column_two ==1) {
						$precio_promocion = (($precio * $column_two)/($column_one+$column_two));
					}elseif ($column_one >=1 && $column_two >1) {
						$precio_promocion = (($precio * $column_two)/($column_one+$column_two));
					}elseif ($descuento >0) {
						$precio_promocion = ($precio - ($precio * ($descuento/100)));
					}else{
						$precio_promocion = $precio;
					}
					$antes =  $this->falt_mdl->get(NULL, ['id_producto' => $productos->id_producto, 'fecha_termino > ' => date("Y-m-d H:i:s"), 'id_proveedor' => $proveedor])[0];
					$cotiz =  $this->ct_mdl->get(NULL, ['id_producto' => $productos->id_producto, 'WEEKOFYEAR(fecha_registro)' => $this->weekNumber($fecha->format('Y-m-d H:i:s')), 'id_proveedor' => $proveedor])[0];
					$accion = "";
					if($antes){
						if($cotiz){
							$accion="update";
							$faltis+=1;
							$id_cotiz=$cotiz->id_cotizacion;
						}else{
							$accion="insert";
							$cotizados+=1;
							$id_cotiz=0;
						}
						$new_cotizacion[$flag]=[
							"id_producto"		=>	$productos->id_producto,
							"id_proveedor"		=>	$proveedor,
							"precio"			=>	$precio,
							"num_one"			=>	$column_one,
							"num_two"			=>	$column_two,
							"descuento"			=>	$descuento,
							"precio_promocion"	=>	$precio_promocion,
							"fecha_registro"	=>	$fecha->format('Y-m-d H:i:s'),
							"observaciones"		=>	$sheet->getCell('D'.$i)->getValue(),
							"estatus" 			=> 0,
							"accion"			=> $accion,
							"id_cotiz"			=> $id_cotiz
						];
					}else{
						if($cotiz){
							$accion="update";
							$faltis+=1;
							$id_cotiz=$cotiz->id_cotizacion;
						}else{
							$accion="insert";
							$cotizados+=1;
							$id_cotiz=0;
						}
						$new_cotizacion[$flag]=[
							"id_producto"		=>	$productos->id_producto,
							"id_proveedor"		=>	$proveedor,
							"precio"			=>	$precio,
							"num_one"			=>	$column_one,
							"num_two"			=>	$column_two,
							"descuento"			=>	$descuento,
							"precio_promocion"	=>	$precio_promocion,
							"fecha_registro"	=>	$fecha->format('Y-m-d H:i:s'),
							"observaciones"		=>	$sheet->getCell('D'.$i)->getValue(),
							"estatus" 			=> 0,
							"accion"			=> $accion,
							"id_cotiz"			=> $id_cotiz
						];
					}
					$flag+=1;
				}
			}
		}
		$this->jsonResponse($new_cotizacion);
	}
	public function upload_pedidos($idesp){
		$fecha = new DateTime(date('Y-m-d H:i:s'));
		$intervalo = new DateInterval('P3D');
		$fecha->add($intervalo);
		$this->load->library("excelfile");
		ini_set("memory_limit", -1);
		$file = $_FILES["file_cotizaciones"]["tmp_name"];
		$sheet = PHPExcel_IOFactory::load($file);
		$objExcel = PHPExcel_IOFactory::load($file);
		$sheet = $objExcel->getSheet(0);
		$num_rows = $sheet->getHighestDataRow();
		if($idesp === "0"){
			$tienda = $this->session->userdata('id_usuario');
		}else{
			$tienda = $idesp;
		}
		$cfile =  $this->usua_mdl->get(NULL, ['id_usuario' => $tienda])[0];
		$nams = preg_replace('/\s+/', '_', $cfile->nombre);
		$filen = "Pedidos".$nams."".rand();
		$config['upload_path']          = './assets/uploads/pedidos/';
        $config['allowed_types']        = 'xlsx|xls';
        $config['max_size']             = 10000;
        $config['max_width']            = 10024;
        $config['max_height']           = 7608;
        $this->load->library('upload', $config);
        $this->upload->initialize($config);
        $new_existencias = FALSE;
        $this->upload->do_upload('file_cotizaciones',$filen);
		for ($i=3; $i<=$num_rows; $i++) {
			$productos = $this->prod_mdl->get("id_producto",['codigo'=> htmlspecialchars($this->getOldVal($sheet,$i,"D"), ENT_QUOTES, 'UTF-8')])[0];
			if (sizeof($productos) > 0) {
				$exis = $this->ex_mdl->get(NULL,["WEEKOFYEAR(fecha_registro)" => $this->weekNumber($fecha->format('Y-m-d H:i:s')),"id_tienda"=>$tienda,"id_producto"=>$productos->id_producto])[0];
				$column_one=0; $column_two=0; $column_three=0;
				$column_one = $this->getOldVal($sheet,$i,"A") == "" ? 0 : $this->getOldVal($sheet,$i,"A");
				$column_two = $this->getOldVal($sheet,$i,"B") == "" ? 0 : $this->getOldVal($sheet,$i,"B");
				$column_three = $this->getOldVal($sheet,$i,"C") == "" ? 0 : $this->getOldVal($sheet,$i,"C");
				$new_existencias[$i]=[
					"id_producto"			=>	$productos->id_producto,
					"id_tienda"			=>	$tienda,
					"cajas"			=>	$column_one,
					"piezas"			=>	$column_two,
					"pedido"	=>	$column_three,
					"fecha_registro"	=>	$fecha->format('Y-m-d H:i:s')
				];
				if($exis){
					$data['cotizacion']=$this->ex_mdl->update($new_existencias[$i], ['id_pedido' => $exis->id_pedido]);
				}else{
					$data['cotizacion']=$this->ex_mdl->insert($new_existencias[$i]);
				}
			}
		}
		if (sizeof($new_existencias) > 0) {
			$aprov = $this->usua_mdl->get(NULL, ['id_usuario'=>$tienda])[0];
			$cambios=[
					"id_usuario"		=>	$this->session->userdata('id_usuario'),
					"fecha_cambio"		=>	date("Y-m-d H:i:s"),
					"antes"			=>	"El usuario sube archivo de pedidos de la tienda ".$aprov->nombre,
					"despues"			=>	"assets/uploads/pedidos/".$filen.".xlsx",
					"accion"			=>	"Sube Pedidos"
				];
			$data['cambios']=$this->cambio_md->insert($cambios);
			$mensaje=[	"id"	=>	'Éxito',
						"desc"	=>	'Pedidos cargados correctamente en el Sistema',
						"type"	=>	'success'];
		}else{
			$mensaje=[	"id"	=>	'Error',
						"desc"	=>	'Los Pedidos no se cargaron al Sistema',
						"type"	=>	'error'];
		}
		$this->jsonResponse($mensaje);
	}
	public function upload_allcotizaciones(){
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
				$proveedor = $this->usua_mdl->get("id_usuario",['nombre'=> $sheet->getCell('G'.$i)->getValue()])[0];
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
						"id_proveedor"		=>	$proveedor->id_usuario,//Recupera el id_usuario activo
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
	public function getProducto(){
		$where = ["productos.estatus" => 1];
		$productosProveedor = $this->prod_mdl->getProducto($where);
		$this->jsonResponse($productosProveedor);
	}
	public function getFamilia(){
		$where = ["familias.estatus" => 1];
		$productosProveedor = $this->fam_mdl->getFamilia($where);
		$this->jsonResponse($productosProveedor);
	}
	public function getProveedor(){
		$where = ["usuarios.id_grupo" => 2];
		$productosProveedor = $this->usua_mdl->getUsuario($where);
		$this->jsonResponse($productosProveedor);
	}
	public function upload_precios(){
		$fecha = new DateTime(date('Y-m-d H:i:s'));
		$intervalo = new DateInterval('P3D');
		$fecha->add($intervalo);
		$user = $this->session->userdata();
		$config['upload_path']          = './assets/uploads/precios/';
        $config['allowed_types']        = 'xlsx|xls';
        $config['max_size']             = 10000;
        $config['max_width']            = 10240;
        $config['max_height']           = 7680;
        $this->load->library('upload', $config);
        $this->upload->do_upload('file_precios');
		$this->load->library("excelfile");
		ini_set("memory_limit", "-1");
		ini_set("max_execution_time", "-1");
		$file = $_FILES["file_precios"]["tmp_name"];
		$objExcel = PHPExcel_IOFactory::load($file);
		$sheet = $objExcel->getSheet(0);
		$num_rows = $sheet->getHighestDataRow();
		for ($i=3; $i<=$num_rows; $i++) {
			if( $this->getOldVal($sheet,$i,"A") !=''){
				$productos = $this->prod_mdl->get("id_producto",['codigo'=> htmlspecialchars( $this->getOldVal($sheet,$i,"A") , ENT_QUOTES, 'UTF-8')])[0];
				if ($productos){
					$new_precios=[
						"id_producto"		=>	$productos->id_producto,
						"precio_sistema"	=>	str_replace("$", "", str_replace(",", "replace", $this->getOldVal($sheet,$i,"C") )),
						"precio_four"		=>	str_replace("$", "", str_replace(",", "replace", $this->getOldVal($sheet,$i,"D") )),
						"fecha_registro"		=>	$fecha->format('Y-m-d H:i:s')
					];
					$new_unidad = [
						"umsistema" => $this->getOldVal($sheet,$i,"E")
					];

					$data['unidad']=$this->prod_mdl->update($new_unidad,["id_producto"=>$productos->id_producto]);

					$precios = $this->pre_mdl->get("id_precio",['id_producto'=> $productos->id_producto, 'WEEKOFYEAR(fecha_registro)' => $this->weekNumber($fecha->format('Y-m-d H:i:s'))])[0];
					if($precios){
						$data['cotizacion']=$this->pre_mdl->update($new_precios,
						['WEEKOFYEAR(fecha_registro)' => $this->weekNumber($fecha->format('Y-m-d H:i:s')),'id_precio'=>$precios->id_precio]);
						$data['cotizacion']=$this->preb_mdl->update($new_precios,
						['WEEKOFYEAR(fecha_registro)' => $this->weekNumber($fecha->format('Y-m-d H:i:s')),'id_precio'=>$precios->id_precio]);
					}else{
						$data['cotizacion']=$this->pre_mdl->insert($new_precios);
						$data['cotizacion']=$this->preb_mdl->insert($new_precios);
					}
				}
			}
		}
		$cambios = [
				"id_usuario" => $user["id_usuario"],
				"fecha_cambio" => date('Y-m-d H:i:s'),
				"antes" => $this->getUserIP(),
				"despues" => "El usuario subio precios de sistema y precio 4"];
		$data['cambios'] = $this->cambio_md->insert($cambios);
		$mensaje=[	"id"	=>	'Éxito',
					"desc"	=>	'Precios cargados correctamente en el Sistema',
					"type"	=>	'success'];
		$this->jsonResponse($mensaje);
	}
	public function set_pedido_prov($id){
		$data["proveedor"] = $this->usua_mdl->getHim(NULL,$id);
		$where = ["cotizaciones.id_proveedor" => $id];
		$data["productos"] = $this->ct_mdl->productos_proveedor($where);
		$data["title"]="PEDIDOS A  ".$data["proveedor"]->names;
		$data["view"]=$this->load->view("Cotizaciones/pedidos_proveedor", $data, TRUE);
		$data["button"]="<button class='btn btn-success new_pedido' type='button'>
							<span class='bold'><i class='fa fa-shopping-cart'></i></span> &nbsp;Hacer Pedido
						</button>";
		$this->jsonResponse($data);
	}
	public function set_pedido_provs($id){
		$fecha = new DateTime(date('Y-m-d H:i:s'));
		$intervalo = new DateInterval('P2D');
		$fecha->add($intervalo);
		ini_set("memory_limit", "-1");
		$search = ["productos.nombre", "cotizaciones.precio", "cotizaciones.observaciones"];
		$columns = "usuarios.id_usuario, productos.id_producto, productos.nombre AS producto, cotizaciones.precio AS precio, cotizaciones.observaciones";
		$joins = [
			["table"	=>	"usuarios",			"ON"	=>	"cotizaciones.id_proveedor = usuarios.id_usuario",	"clausula"	=>	"INNER"],
			["table"	=>	"productos",		"ON"	=>	"cotizaciones.id_producto = productos.id_producto",	"clausula"	=>	"INNER"]
		];
		$where = [
				["clausula"	=>	"WEEKOFYEAR(cotizaciones.fecha_registro)",	"valor"	=>	$this->weekNumber($fecha->format('Y-m-d H:i:s'))],
				["clausula"	=>	"cotizaciones.id_proveedor",	"valor"	=>	$id],
				["clausula"	=>	"cotizaciones.estatus",	"valor"	=>	1]
		];
		$order="productos.nombre";
		$group ="productos.id_producto";
		$cotizacionesProveedor = $this->ct_mdl->get_pagination($columns, $joins, $where, $search, $group, $order);
		$data =[];
		$no = $_POST["start"];
		if ($cotizacionesProveedor){
			foreach ($cotizacionesProveedor as $key => $value) {
				$no ++;
				$row = [];
				$row[] = $value->producto;
				$row[] = "<div class='input-group m-b'>
						 <span class='input-group-addon'><i class='fa fa-dollar'></i></span>
						 <input type='text' value='$ ".number_format($value->precio,2,'.',',')."' class='form-control precio numeric' readonly=''>
						 </div>";
				$row[] = $value->observaciones;
				$row[] = "<div class='input-group m-b'>
						 <span class='input-group-addon'><i class='fa fa-slack'></i></span>
						 <input type='number' min='0' value='' class='form-control cantidad'>
						 </div>";
				$row[] = '<td><button type="button" id="add_me" class="btn btn-info" data-toggle="tooltip" title="Agregar" data-id-cotizacion="'.$value->id_producto.'">
						<i class="fa fa-plus"></i></button></td>';
				$data[] = $row;
			}
		}
		$salida = [
			"query"				=>	$this->db->last_query(),
			"draw"				=>	$_POST['draw'],
			"recordsTotal"		=>	$this->ct_mdl->count_filtered("cotizaciones.id_producto", $where, $search, $joins),
			"recordsFiltered"	=>	$this->ct_mdl->count_filtered("cotizaciones.id_producto", $where, $search, $joins),
			"data" => $data];
		$this->jsonResponse($salida);
	}
	public function cotizaciones_dataTable(){
		$fecha = new DateTime(date('Y-m-d H:i:s'));
		$intervalo = new DateInterval('P2D');
		$fecha->add($intervalo);
		$fech = $fecha->format('Y-m-d H:i:s');
		ini_set("memory_limit", "-1");
		ini_set("max_execution_time", "-1");
			$search = ["prodandprice.codigo","prodandprice.nombre"];
			$columns = "ctz1.id_cotizacion,
			ctz1.fecha_registro,prodandprice.estatus,prodandprice.color,prodandprice.colorp,
			prodandprice.codigo, prodandprice.nombre AS producto,prodandprice.id_producto,
			UPPER(ctz1.nombre) AS proveedor_first,
			ctz1.precio AS precio_firsto,
			ctz1.precio_promocion AS precio_first,
			ctz1.observaciones AS promocion_first,
			ctz1.observaciones AS observaciones_first,
			prodandprice.precio_sistema,
			prodandprice.precio_four,
			UPPER(ctz2.nombre) AS proveedor_next,
			ctz2.fecha_registro AS fecha_next,
			ctz2.observaciones AS promocion_next,
			ctz2.precio AS precio_nexto,
			ctz2.precio_promocion AS precio_next,
			UPPER(ctz3.nombre) AS proveedor_nxts,
			ctz3.observaciones AS promocion_nxts,
			ctz3.precio AS precio_nxtso,
			ctz3.precio_promocion AS precio_nxts,
			ctz1.maxis AS precio_maximo,
			ctz1.avis AS precio_promedio,prodandprice.id_familia, prodandprice.familia AS familia";
			$joins = [
				["table"	=>	"bajos ctz1","ON"	=>	"prodandprice.id_producto = ctz1.id_producto", "clausula"	=>	"LEFT"],
				["table"	=>	"bajos ctz2","ON"	=>	"prodandprice.id_producto = ctz2.id_producto AND ctz2.id_cotizacion <> ctz1.id_cotizacion", "clausula"	=>	"LEFT"],
				["table"	=>	"bajos ctz3","ON"	=>	"prodandprice.id_producto = ctz3.id_producto AND ctz3.id_cotizacion <> ctz1.id_cotizacion AND ctz3.id_cotizacion <> ctz2.id_cotizacion","clausula"	=>	"LEFT"]
			];
		$where = NULL;
			$order="prodandprice.id_familia,prodandprice.nombre";
			$group ="prodandprice.nombre";
		$cotizacionesProveedor = $this->prodand_mdl->get_pagination($columns, $joins, $where, $search, $group, $order);
		$data =[];
		$no = $_POST["start"];
				foreach ($cotizacionesProveedor as $key => $value) {
					$no ++;
					$row = [];
					$row[] = $value->codigo;
					$row[] = $value->producto;
					$row[] = ($value->precio_sistema > 0) ? '$ '.number_format($value->precio_sistema,2,'.',',') : '';
					$row[] = ($value->precio_four > 0) ? '$ '.number_format($value->precio_four,2,'.',',') : '';
					$row[] = '$ '.number_format($value->precio_firsto,2,'.',',');
					if($value->precio_first <= $value->precio_sistema){
						$row[] = ($value->precio_first > 0) ? '<div class="preciomenos">$ '.number_format($value->precio_first,2,'.',',').'</div>' : '';
					}else{
						$row[] = ($value->precio_first > 0) ? '<div class="preciomas">$ '.number_format($value->precio_first,2,'.',',').'</div>' : '';
					}
					$row[] = $value->proveedor_first;
					$row[] = $value->promocion_first;
					$row[] = '$ '.number_format($value->precio_maximo,2,'.',',');
					$row[] = '$ '.number_format($value->precio_promedio,2,'.',',');
					$row[] = ($value->precio_nexto > 0) ? '$ '.number_format($value->precio_nexto,2,'.',',') : '';
					if($value->precio_next <= $value->precio_sistema){
						$row[] = ($value->precio_next > 0) ? '<div class="preciomenos">$ '.number_format($value->precio_next,2,'.',',').'</div>' : '';
					}else{
						$row[] = ($value->precio_next > 0) ? '<div class="preciomas">$ '.number_format($value->precio_next,2,'.',',').'</div>' : '';
					}
					$row[] = $value->proveedor_next;
					$row[] = $value->promocion_next;
					$row[] = ($value->precio_nxtso > 0) ? '$ '.number_format($value->precio_nxtso,2,'.',',') : '';
					if($value->precio_nxts <= $value->precio_sistema){
						$row[] = ($value->precio_nxts > 0) ? '<div class="preciomenos">$ '.number_format($value->precio_nxts,2,'.',',').'</div>' : '';
					}else{
						$row[] = ($value->precio_nxts > 0) ? '<div class="preciomas">$ '.number_format($value->precio_nxts,2,'.',',').'</div>' : '';
					}
					$row[] = $value->proveedor_nxts;
					$row[] = $value->promocion_nxts;
					$row[] = $this->column_buttons($value->id_cotizacion, "All");
					$data[] = $row;
				}
		$salida = [
			"query"				=>	$this->db->last_query(),
			"draw"				=>	$_POST['draw'],
			"recordsTotal"		=>	$this->prodand_mdl->count_filtered("prodandprice.id_producto", $where, $search, $joins),
			"recordsFiltered"	=>	$this->prodand_mdl->count_filtered("prodandprice.id_producto", $where, $search, $joins),
			"data" => $data];
		$this->jsonResponse($salida);
	}
	private function column_buttons($id_cotizacion,$param1){
		$botones = "";
		$botones.='<button id="update_cotizacion" class="btn btn-info" data-toggle="tooltip" title="Editar" data-id-cotizacion="'.$id_cotizacion.'">
						<i class="fa fa-pencil"></i>
					</button>';
		$botones.='&nbsp;<button id="delete_cotizacion" class="btn btn-warning" data-toggle="tooltip" title="Eliminar" data-id-cotizacion="'.$id_cotizacion.'">
							<i class="fa fa-trash"></i>
						</button>';
		return $botones;
	}
	public function getProveedorBajos($ides){
		$fecha = new DateTime(date('Y-m-d H:i:s'));
		$intervalo = new DateInterval('P2D');
		$fecha->add($intervalo);
		$data["cotizaciones"] =  $this->ct_mdl->getProveedorBajos(NULL,$fecha->format('Y-m-d H:i:s'),$ides);
		$this->jsonResponse($data);
	}
	public function getProveedorCot($ides){
		$data["cotizaciones"] =  $this->ct_mdl->getfalts(['fal.id_proveedor'=>$ides,'fal.fecha_termino >' => date("Y-m-d H:i:s")]);
		$this->jsonResponse($data);
	}
	public function fill_formato(){
		$id_proves = $this->input->post('id_proves4');
		$proves = $this->input->post('id_proves2');
		$prs = substr($id_proves,0,6);
		if ($prs === "VARIOS") {
			$array = $this->usua_mdl->get(NULL, ["conjunto" => $id_proves]);
			$filenam = $id_proves;
			$this->fill_varios();
		}elseif($proves != "nope"){
			$this->fill_formato1($proves, $id_proves);
		}else{
			$this->fill_formatoAll($proves, $id_proves);
		}
	}
	public function fill_formato1(){
		$user = $this->session->userdata();
		$flag =1;
		$flag1 = 1;
		$array = "";
		$array2 = "";
		$filenam = "";
		$id_proves = $this->input->post('id_proves4');
		$proves = $this->input->post('id_proves2');
		$prs = substr($id_proves,0,6);
		if (($id_proves <> "3" || $id_proves <> 3) && ($id_proves <> "6" || $id_proves <> 6) && ($id_proves <> "4" || $id_proves <> 4)) {
			if ($prs === "VARIOS") {
				$cambios = [
					"id_usuario" => $user["id_usuario"],
					"fecha_cambio" => date('Y-m-d H:i:s'),
					"antes" => "Descarga formato",
					"despues" => "VARIOS ",
					"estatus" => "3",
				];
				$data['cambios'] = $this->cambio_md->insert($cambios);
				$array = $this->usua_mdl->get(NULL, ["conjunto" => $id_proves]);
				$filenam = $id_proves;
				$this->fill_varios($id_proves,$proves,$prs);
			}elseif ($id_proves === "VOLUMEN" || $id_proves === "AMARILLOS") {
				$cambios = [
					"id_usuario" => $user["id_usuario"],
					"fecha_cambio" => date('Y-m-d H:i:s'),
					"antes" => "Descarga formato",
					"despues" => "Volúmen",
					"estatus" => "3",
				];
				$data['cambios'] = $this->cambio_md->insert($cambios);
				$filenam = $id_proves;
				$array = (object)['0'=>(object)['nombre' => $id_proves]];
				$this->fill_volumen($id_proves,$proves,$prs);
			}elseif ($id_proves === "MODERNA") {
				$cambios = [
					"id_usuario" => $user["id_usuario"],
					"fecha_cambio" => date('Y-m-d H:i:s'),
					"antes" => "Descarga formato",
					"despues" => "MODERNA",
					"estatus" => "3",
				];
				$data['cambios'] = $this->cambio_md->insert($cambios);
				$filenam = $id_proves;
				$array = (object)['0'=>(object)['nombre' => $id_proves]];
				$this->fill_moderna($id_proves,$proves,$prs);
			}elseif ($id_proves === "CUETARA") {
				$cambios = [
					"id_usuario" => $user["id_usuario"],
					"fecha_cambio" => date('Y-m-d H:i:s'),
					"antes" => "Descarga formato",
					"despues" => "CUETARA",
					"estatus" => "3",
				];
				$data['cambios'] = $this->cambio_md->insert($cambios);
				$filenam = $id_proves;
				$array = (object)['0'=>(object)['nombre' => $id_proves]];
				$this->fill_cuetara($id_proves,$proves,$prs);
			}elseif ($id_proves === "COSTENA") {
				$cambios = [
					"id_usuario" => $user["id_usuario"],
					"fecha_cambio" => date('Y-m-d H:i:s'),
					"antes" => "Descarga formato",
					"despues" => "COSTENA",
					"estatus" => "3",
				];
				$data['cambios'] = $this->cambio_md->insert($cambios);
				$filenam = $id_proves;
				$array = (object)['0'=>(object)['nombre' => $id_proves]];
				$this->fill_costena($id_proves,$proves,$prs);
			}elseif ($id_proves === "MEXICANO") {
				$cambios = [
					"id_usuario" => $user["id_usuario"],
					"fecha_cambio" => date('Y-m-d H:i:s'),
					"antes" => "Descarga formato",
					"despues" => "MEXICANO",
					"estatus" => "3",
				];
				$data['cambios'] = $this->cambio_md->insert($cambios);
				$filenam = $id_proves;
				$array = (object)['0'=>(object)['nombre' => $id_proves]];
				$this->fill_mexicano($id_proves,$proves,$prs);
			}else{
				$array = $this->user_md->get(NULL, ["id_usuario" => $id_proves]);
				$filenam = $array[0]->nombre;
			}
			//$this->jsonResponse($array);
			ini_set("memory_limit", "-1");
			ini_set("max_execution_time", "-1");
			$this->load->library("excelfile");
			$hoja1 = $this->excelfile->setActiveSheetIndex(0);
			$this->excelfile->setActiveSheetIndex(0)->setTitle("EXISTENCIAS");
			$this->excelfile->createSheet();
	        $hoja = $this->excelfile->setActiveSheetIndex(1);
	        $hoja->setTitle("PEDIDO");
			$styleArray = array(
			  'borders' => array(
			    'allborders' => array(
			      'style' => PHPExcel_Style_Border::BORDER_THIN
			    )
			  )
			);
			$hoja->getColumnDimension('A')->setWidth("20");
			$hoja->getColumnDimension('B')->setWidth("70");
			$hoja->getColumnDimension('C')->setWidth("15");
			$hoja->getColumnDimension('D')->setWidth("15");
			$hoja->getColumnDimension('E')->setWidth("15");
			$hoja->getColumnDimension('F')->setWidth("15");
			$hoja->getColumnDimension('G')->setWidth("15");

			$hoja1->getColumnDimension('A')->setWidth("6");
			$hoja1->getColumnDimension('B')->setWidth("6");
			$hoja1->getColumnDimension('C')->setWidth("6");
			$hoja1->getColumnDimension('D')->setWidth("25");
			$hoja1->getColumnDimension('E')->setWidth("47");
			$hoja1->getColumnDimension('G')->setWidth("50");

			if ($id_proves === "VOLUMEN" || $id_proves === "AMARILLOS"){
				$hoja->getColumnDimension('BB' )->setWidth("70");
				$hoja->getColumnDimension('K')->setWidth("20");
			}else{
				$hoja->getColumnDimension('BD')->setWidth("70");
				$hoja->getColumnDimension('J')->setWidth("20");
				$hoja->getColumnDimension('I')->setWidth("20");
				$hoja->getColumnDimension('G')->setWidth("8");
				$hoja->getColumnDimension('E')->setWidth("8");
			}
			$flagBorder = 0;
			$flagBorder1 = 1;
			$flagBorder2 = 0;
			$flagBorder3 = 1;
			$flage = 5;
			$i = 0;
			$sumall = array(1 => "", 2 => "", 3 => "", 4 => "", 5 => "", 6 => "", 7 => "", 8 => "", 9 => "", 10 => "");
			$provname = "";
			if ($array){
				foreach ($array as $key => $value){
					$fecha = new DateTime(date('Y-m-d H:i:s'));
					$intervalo = new DateInterval('P3D');
					$fecha->add($intervalo);
					if ($value->nombre === "AMARILLOS") {
						$where=["prod.estatus" => 3];//Semana actual
					}elseif ($value->nombre === "VOLUMEN" ) {
						$where=["prod.estatus" => 2];//Semana actual
					}else{
						$where=["ctz_first.id_proveedor" => $value->id_usuario,"prod.estatus" => 1];//Semana actual
					}
				$cotizacionesProveedor = $this->ct_mdl->getPedidosAll($where, $fecha->format('Y-m-d H:i:s'), 0);
				
					$difff = 0.01;
					$flag2 = 3;
					$cargo = "";
					if ($cotizacionesProveedor){
						//HOJA EXISTENCIAS
						$this->excelfile->setActiveSheetIndex(0);
						if($i > 0){
							$flagBorder = $flag1 ;
							$this->excelfile->getActiveSheet()->getStyle('A'.$flagBorder1.':G'.$flagBorder)->applyFromArray($styleArray);
							$flagBorder1 = $flag1;
						}
						$hoja1->mergeCells('A'.$flag1.':G'.$flag1);
						$this->cellStyle("A".$flag1, "FFFFFF", "000000", TRUE, 12, "Franklin Gothic Book");
						$hoja1->setCellValue("A".$flag1."", "GRUPO ABARROTES AZTECA");
						$this->excelfile->getActiveSheet()->getStyle('A'.$flag1.':G'.$flag1)->applyFromArray($styleArray);
						$flag1++;
						$hoja1->mergeCells('A'.$flag1.':G'.$flag1);
						$this->cellStyle("A".$flag1, "FFFFFF", "000000", TRUE, 12, "Franklin Gothic Book");
						$hoja1->setCellValue("A".$flag1."", "PEDIDOS A '".$value->nombre."' ".date("d-m-Y"));
						$provname = $value->nombre;
						$this->excelfile->getActiveSheet()->getStyle('A'.$flag1.':G'.$flag1)->applyFromArray($styleArray);
						$flag1++;
						$this->cellStyle("A".$flag1.":D".$flag1, "000000", "FFFFFF", TRUE, 12, "Franklin Gothic Book");
						$hoja1->mergeCells('A'.$flag1.':B'.$flag1);
						$hoja1->setCellValue("A".$flag1, "EXISTENCIAS");
						$hoja1->setCellValue("E".$flag1, "DESCRIPCIÓN");
						$this->cellStyle("E".$flag1, "000000", "FFFFFF", TRUE, 12, "Franklin Gothic Book");

						$this->cellStyle("H".$flag1."", "000000", "FFFFFF", TRUE, 12, "Franklin Gothic Book");
						$hoja1->setCellValue("H".$flag1."", "PENDIENT");
						$this->cellStyle("I".$flag1."", "000000", "FFFFFF", TRUE, 12, "Franklin Gothic Book");
						$hoja1->setCellValue("I".$flag1."", "PENDIENT");
						$this->cellStyle("J".$flag1."", "000000", "FFFFFF", TRUE, 12, "Franklin Gothic Book");
						$hoja1->setCellValue("J".$flag1."", "PENDIENT");
						$this->cellStyle("K".$flag1."", "000000", "FFFFFF", TRUE, 12, "Franklin Gothic Book");
						$hoja1->setCellValue("K".$flag1."", "PENDIENT");
						$this->cellStyle("L".$flag1."", "000000", "FFFFFF", TRUE, 12, "Franklin Gothic Book");
						$hoja1->setCellValue("L".$flag1."", "PENDIENT");
						$this->cellStyle("M".$flag1."", "000000", "FFFFFF", TRUE, 12, "Franklin Gothic Book");
						$hoja1->setCellValue("M".$flag1."", "PENDIENT");
						$this->cellStyle("N".$flag1."", "000000", "FFFFFF", TRUE, 12, "Franklin Gothic Book");
						$hoja1->setCellValue("N".$flag1."", "PENDIENT");
						$this->cellStyle("O".$flag1."", "000000", "FFFFFF", TRUE, 12, "Franklin Gothic Book");
						$hoja1->setCellValue("O".$flag1."", "PENDIENT");
						$this->cellStyle("P".$flag1."", "000000", "FFFFFF", TRUE, 12, "Franklin Gothic Book");
						$hoja1->setCellValue("P".$flag1."", "PENDIENT");
						$this->excelfile->getActiveSheet()->getStyle('A'.$flag1.':P'.$flag1)->applyFromArray($styleArray);
						$flag1++;
						$this->cellStyle("A".$flag1.":G".$flag1, "000000", "FFFFFF", TRUE, 12, "Franklin Gothic Book");
						$hoja1->setCellValue("A".$flag1, "CAJAS");
						$hoja1->setCellValue("B".$flag1, "PZAS");
						$hoja1->setCellValue("C".$flag1, "PEDIDO");
						$hoja1->setCellValue("D".$flag1, "CÓDIGO");
						$hoja1->setCellValue("G".$flag1, "PROMOCIÓN");
						$hoja1->setCellValue("F".$flag1, "IMAGEN")->getColumnDimension('F')->setWidth(18);
						
						$this->cellStyle("H".$flag1, "C00000", "000000", TRUE, 10, "Franklin Gothic Book");
						$this->cellStyle("I".$flag1, "01B0F0", "000000", TRUE, 10, "Franklin Gothic Book");
						$this->cellStyle("J".$flag1, "FF0000", "000000", TRUE, 10, "Franklin Gothic Book");
						$this->cellStyle("K".$flag1, "E26C0B", "000000", TRUE, 10, "Franklin Gothic Book");
						$this->cellStyle("L".$flag1, "C5C5C5", "000000", TRUE, 10, "Franklin Gothic Book");
						$this->cellStyle("M".$flag1, "92D051", "000000", TRUE, 10, "Franklin Gothic Book");
						$this->cellStyle("N".$flag1, "B1A0C7", "000000", TRUE, 10, "Franklin Gothic Book");
						$this->cellStyle("O".$flag1, "DA9694", "000000", TRUE, 10, "Franklin Gothic Book");
						$this->cellStyle("P".$flag1, "4CACC6", "000000", TRUE, 10, "Franklin Gothic Book");
						$hoja1->setCellValue("H".$flag1."", "CEDIS");
						$hoja1->setCellValue("I".$flag1."", "ABARROTES");
						$hoja1->setCellValue("J".$flag1."", "VILLAS");
						$hoja1->setCellValue("K".$flag1."", "TIENDA");
						$hoja1->setCellValue("L".$flag1."", "ULTRA");
						$hoja1->setCellValue("M".$flag1."", "TRINCHERAS");
						$hoja1->setCellValue("N".$flag1."", "MERCADO");
						$hoja1->setCellValue("O".$flag1."", "TENENCIA");
						$hoja1->setCellValue("P".$flag1."", "TIJERAS");
						$this->excelfile->getActiveSheet()->getStyle('A'.$flag1.':P'.$flag1)->applyFromArray($styleArray);
						//$flag1++;
						$this->excelfile->setActiveSheetIndex(1);
						if($i > 0){
							$flagBorder2 = $flag ;
							$this->excelfile->getActiveSheet()->getStyle('A'.$flagBorder3.':AD'.$flagBorder2)->applyFromArray($styleArray);
							$flagBorder3 = $flag;
						}
						//HOJA PEDIDOS
						if ($id_proves === "VOLUMEN" || $id_proves === "AMARILLOS"){
							$this->cellStyle("A".$flag, "FFFFFF", "000000", TRUE, 12, "Franklin Gothic Book");
							$hoja->setCellValue("A".$flag."", "CEDIS, ABARROTES,PEDREGAL, TIENDA, ULTRAMARINOS, TRINCHERAS, MERCADO, TIJERAS, Y TENENCIA AZTECA AUTOSERVICIOS SA. DE CV.");
							$hoja->mergeCells('A'.$flag.':BB'.$flag);
							$this->excelfile->getActiveSheet()->getStyle('A'.$flag.':BB'.$flag)->applyFromArray($styleArray);
							$flag++;
							$hoja->mergeCells('B'.$flag.':K'.$flag);
							$hoja->mergeCells('L'.$flag.':O'.$flag);
							$hoja->mergeCells('P'.$flag.':R'.$flag);
							$hoja->mergeCells('S'.$flag.':U'.$flag);
							$hoja->mergeCells('V'.$flag.':Y'.$flag);
							$hoja->mergeCells('Z'.$flag.':AC'.$flag);
							$hoja->mergeCells('AD'.$flag.':AG'.$flag);
							$hoja->mergeCells('AH'.$flag.':AK'.$flag);
							$hoja->mergeCells('AL'.$flag.':AO'.$flag);
							$hoja->mergeCells('AP'.$flag.':AS'.$flag);
							$hoja->mergeCells('AT'.$flag.':AW'.$flag);
							$hoja->mergeCells('AX'.$flag.':BA'.$flag);

							$this->cellStyle("B".$flag, "FFFFFF", "000000", TRUE, 12, "Franklin Gothic Book");
							$hoja->setCellValue("B".$flag, "PEDIDOS A '".$value->nombre."' ".date("d-m-Y"));
							$this->cellStyle("L".$flag, "C00000", "000000", TRUE, 12, "Franklin Gothic Book");
							$hoja->setCellValue("L".$flag, "CEDIS");
							$hoja->setCellValue("P".$flag, "CD INDUSTRIAL");
							$this->cellStyle("P".$flag, "FF0066", "000000", TRUE, 12, "Franklin Gothic Book");
							$this->cellStyle("S".$flag, "C2B90A", "000000", TRUE, 12, "Franklin Gothic Book");
							$hoja->setCellValue("S".$flag, "SUMA CEDIS");
							$this->cellStyle("V".$flag, "01B0F0", "000000", TRUE, 12, "Franklin Gothic Book");
							$hoja->setCellValue("V".$flag, "ABARROTES");
							$this->cellStyle("Z".$flag, "FF0000", "000000", TRUE, 12, "Franklin Gothic Book");
							$hoja->setCellValue("Z".$flag, "VILLAS");
							$this->cellStyle("AD".$flag, "E26C0B", "000000", TRUE, 12, "Franklin Gothic Book");
							$hoja->setCellValue("AD".$flag, "TIENDA");
							$this->cellStyle("AH".$flag, "C5C5C5", "000000", TRUE, 12, "Franklin Gothic Book");
							$hoja->setCellValue("AH".$flag, "ULTRAMARINOS");
							$this->cellStyle("AL".$flag, "92D051", "000000", TRUE, 12, "Franklin Gothic Book");
							$hoja->setCellValue("AL".$flag, "TRINCHERAS");
							$this->cellStyle("AP".$flag, "B1A0C7", "000000", TRUE, 12, "Franklin Gothic Book");
							$hoja->setCellValue("AP".$flag, "AZT MERCADO");
							$this->cellStyle("AT".$flag, "DA9694", "000000", TRUE, 12, "Franklin Gothic Book");
							$hoja->setCellValue("AT".$flag, "TENENCIA");
							$this->cellStyle("AX".$flag, "4CACC6", "000000", TRUE, 12, "Franklin Gothic Book");
							$hoja->setCellValue("AX".$flag, "TIJERAS");
							
							$this->cellStyle("A3:AP4", "000000", "FFFFFF", TRUE, 12, "Franklin Gothic Book");
							
							$this->excelfile->getActiveSheet()->getStyle('A'.$flag.':AP'.$flag)->applyFromArray($styleArray);
							$flag++;
							$this->cellStyle("A".$flag.":BB".$flag."", "000000", "FFFFFF", TRUE, 12, "Franklin Gothic Book");
							$hoja->mergeCells('B'.$flag.':K'.$flag);
							$hoja->mergeCells('L'.$flag.':O'.$flag);
							$hoja->mergeCells('P'.$flag.':R'.$flag);
							$hoja->mergeCells('S'.$flag.':U'.$flag);
							$hoja->mergeCells('V'.$flag.':Y'.$flag);
							$hoja->mergeCells('Z'.$flag.':AC'.$flag);
							$hoja->mergeCells('AD'.$flag.':AG'.$flag);
							$hoja->mergeCells('AH'.$flag.':AK'.$flag);
							$hoja->mergeCells('AL'.$flag.':AO'.$flag);
							$hoja->mergeCells('AP'.$flag.':AS'.$flag);
							$hoja->mergeCells('AT'.$flag.':AW'.$flag);
							$hoja->mergeCells('AX'.$flag.':BA'.$flag);
							$hoja->setCellValue("B".$flag, "DESCRIPCIÓN");
							$hoja->setCellValue("L".$flag, "EXISTENCIAS");
							$hoja->setCellValue("P".$flag, " SUMA EXISTENCIAS");
							$hoja->setCellValue("S".$flag, "EXISTENCIAS");
							$hoja->setCellValue("V".$flag, "EXISTENCIAS");
							$hoja->setCellValue("Z".$flag, "EXISTENCIAS");
							$hoja->setCellValue("AD".$flag, "EXISTENCIAS");
							$hoja->setCellValue("AH".$flag, "EXISTENCIAS");
							$hoja->setCellValue("AL".$flag, "EXISTENCIAS");
							$hoja->setCellValue("AP".$flag, "EXISTENCIAS");
							$hoja->setCellValue("AT".$flag, "EXISTENCIAS");
							$hoja->setCellValue("AX".$flag, "EXISTENCIAS");
							$this->excelfile->getActiveSheet()->getStyle('A'.$flag.':BB'.$flag)->applyFromArray($styleArray);
							//Begin: TOTALES PEDIDOS PENDIENTES
							$hoja->mergeCells('BS'.$flag.':CC'.$flag);
							$this->cellStyle("BS".$flag.":CC".$flag."", "000000", "FFFFFF", TRUE, 12, "Franklin Gothic Book");
							$hoja->setCellValue("BS".$flag, "TOTAL POR PEDIDOS PENDIENTES");
							//End: TOTALES PEDIDOS PENDIENTES
							$flag++;
							$this->cellStyle("A".$flag.":BB".$flag."", "000000", "FFFFFF", TRUE, 12, "Franklin Gothic Book");
							$hoja->setCellValue("A".$flag, "CODIGO");
							$hoja->setCellValue("C".$flag, "REAL");
							$hoja->setCellValue("D".$flag, "1ER");
							$hoja->setCellValue("E".$flag, "COSTO");
							$hoja->setCellValue("F".$flag, "DIF % 5 Y 1ER");
							$hoja->setCellValue("G".$flag, "SISTEMA");
							$hoja->setCellValue("H".$flag, "DIF % 5 Y 4");
							$hoja->setCellValue("I".$flag, "PRECIO4");
							$hoja->setCellValue("J".$flag, "2DO");
							$hoja->setCellValue("K".$flag, "PROVEEDOR");
							$hoja->setCellValue("L".$flag, "CAJAS");
							$hoja->setCellValue("M".$flag, "PZAS");
							$hoja->setCellValue("N".$flag, "PEND");
							$hoja->setCellValue("O".$flag, "PEDIDO");
							$hoja->setCellValue("P".$flag, "CAJAS");
							$hoja->setCellValue("Q".$flag, "PZAS");
							$hoja->setCellValue("R".$flag, "PEDIDO");
							$hoja->setCellValue("S".$flag, "CAJAS");
							$hoja->setCellValue("T".$flag, "PZAS");
							$hoja->setCellValue("U".$flag, "PEDIDO");
							$hoja->setCellValue("V".$flag, "CAJAS");
							$hoja->setCellValue("W".$flag, "PZAS");
							$hoja->setCellValue("X".$flag, "PEND");
							$hoja->setCellValue("Y".$flag, "PEDIDO");
							$hoja->setCellValue("Z".$flag, "CAJAS");
							$hoja->setCellValue("AA".$flag, "PZAS");
							$hoja->setCellValue("AB".$flag, "PEND");
							$hoja->setCellValue("AC".$flag, "PEDIDO");
							$hoja->setCellValue("AD".$flag, "CAJAS");
							$hoja->setCellValue("AE".$flag, "PZAS");
							$hoja->setCellValue("AF".$flag, "PEND");
							$hoja->setCellValue("AG".$flag, "PEDIDO");
							$hoja->setCellValue("AH".$flag, "CAJAS");
							$hoja->setCellValue("AI".$flag, "PZAS");
							$hoja->setCellValue("AJ".$flag, "PEND");
							$hoja->setCellValue("AK".$flag, "PEDIDO");
							$hoja->setCellValue("AL".$flag, "CAJAS");
							$hoja->setCellValue("AM".$flag, "PZAS");
							$hoja->setCellValue("AN".$flag, "PEND");
							$hoja->setCellValue("AO".$flag, "PEDIDO");
							$hoja->setCellValue("AP".$flag, "CAJAS");
							$hoja->setCellValue("AQ".$flag, "PZAS");
							$hoja->setCellValue("AR".$flag, "PEND");
							$hoja->setCellValue("AS".$flag, "PEDIDO");
							$hoja->setCellValue("AT".$flag, "CAJAS");
							$hoja->setCellValue("AU".$flag, "PZAS");
							$hoja->setCellValue("AV".$flag, "PEND");
							$hoja->setCellValue("AW".$flag, "PEDIDO");
							$hoja->setCellValue("AX".$flag, "CAJAS");
							$hoja->setCellValue("AY".$flag, "PZAS");
							$hoja->setCellValue("AZ".$flag, "PEND");
							$hoja->setCellValue("BA".$flag, "PEDIDO");
							
							$this->cellStyle("BC".$flag1, "C00000", "000000", TRUE, 10, "Franklin Gothic Book");
							$this->cellStyle("BD".$flag1, "01B0F0", "000000", TRUE, 10, "Franklin Gothic Book");
							$this->cellStyle("BE".$flag1, "FF0000", "000000", TRUE, 10, "Franklin Gothic Book");
							$this->cellStyle("BF".$flag1, "E26C0B", "000000", TRUE, 10, "Franklin Gothic Book");
							$this->cellStyle("BG".$flag1, "C5C5C5", "000000", TRUE, 10, "Franklin Gothic Book");
							$this->cellStyle("BH".$flag1, "92D051", "000000", TRUE, 10, "Franklin Gothic Book");
							$this->cellStyle("BI".$flag1, "B1A0C7", "000000", TRUE, 10, "Franklin Gothic Book");
							$this->cellStyle("BJ".$flag1, "DA9694", "000000", TRUE, 10, "Franklin Gothic Book");
							$this->cellStyle("BK".$flag1, "4CACC6", "000000", TRUE, 10, "Franklin Gothic Book");

							$hoja->setCellValue("BB".$flag, "PROMOCIÓN");
							$hoja->setCellValue("BL".$flag, "TOTAL");
							$hoja->setCellValue("BM".$flag, "PEDIDOS");
							$this->cellStyle("BL".$flag."", "000000", "FFFFFF", TRUE, 12, "Franklin Gothic Book");
							$this->cellStyle("BM".$flag."", "000000", "FFFFFF", TRUE, 12, "Franklin Gothic Book");
							$this->excelfile->getActiveSheet()->getStyle('BL'.$flag)->applyFromArray($styleArray);
							$this->excelfile->getActiveSheet()->getStyle('BM'.$flag)->applyFromArray($styleArray);
							$this->excelfile->getActiveSheet()->getStyle('A'.$flag.':BB'.$flag)->applyFromArray($styleArray);

							//Begin: TOTALES PEDIDOS PENDIENTES
							$this->cellStyle("BS".$flag, "C00000", "000000", TRUE, 12, "Franklin Gothic Book");
							$this->cellStyle("BT".$flag, "01B0F0", "000000", TRUE, 12, "Franklin Gothic Book");
							$this->cellStyle("BU".$flag, "FF0000", "000000", TRUE, 12, "Franklin Gothic Book");
							$this->cellStyle("BV".$flag, "E26C0B", "000000", TRUE, 12, "Franklin Gothic Book");
							$this->cellStyle("BW".$flag, "C5C5C5", "000000", TRUE, 12, "Franklin Gothic Book");
							$this->cellStyle("BX".$flag, "92D051", "000000", TRUE, 12, "Franklin Gothic Book");
							$this->cellStyle("BY".$flag, "B1A0C7", "000000", TRUE, 12, "Franklin Gothic Book");
							$this->cellStyle("BZ".$flag, "DA9694", "000000", TRUE, 12, "Franklin Gothic Book");
							$this->cellStyle("CA".$flag, "4CACC6", "000000", TRUE, 12, "Franklin Gothic Book");
							$this->cellStyle("CB".$flag."", "000000", "FFFFFF", TRUE, 12, "Franklin Gothic Book");
							$this->cellStyle("CC".$flag."", "000000", "FFFFFF", TRUE, 12, "Franklin Gothic Book");
							$hoja->setCellValue("CB".$flag, "TOTAL");
							$hoja->setCellValue("CC".$flag, "PEDIDOS");
							//End: TOTALES PEDIDOS PENDIENTES
						}else{
							$this->cellStyle("A".$flag, "FFFFFF", "000000", TRUE, 12, "Franklin Gothic Book");
							$hoja->setCellValue("A".$flag."", "CEDIS, ABARROTES, VILLAS, TIENDA, ULTRAMARINOS, TRINCHERAS, MERCADO, TIJERAS, Y TENENCIA AZTECA AUTOSERVICIOS SA. DE CV.");
							$hoja->mergeCells('A'.$flag.':BD'.$flag);
							$this->excelfile->getActiveSheet()->getStyle('A'.$flag.':BD'.$flag)->applyFromArray($styleArray);
							$flag++;
							$hoja->mergeCells('B'.$flag.':J'.$flag);
							$hoja->mergeCells('K'.$flag.':O'.$flag);
							$hoja->mergeCells('P'.$flag.':R'.$flag);
							$hoja->mergeCells('S'.$flag.':U'.$flag);
							$hoja->mergeCells('V'.$flag.':Z'.$flag);
							$hoja->mergeCells('AA'.$flag.':AE'.$flag);
							$hoja->mergeCells('AF'.$flag.':AI'.$flag);
							$hoja->mergeCells('AJ'.$flag.':AM'.$flag);
							$hoja->mergeCells('AN'.$flag.':AQ'.$flag);
							$hoja->mergeCells('AR'.$flag.':AU'.$flag);
							$hoja->mergeCells('AV'.$flag.':AY'.$flag);
							$hoja->mergeCells('AZ'.$flag.':BC'.$flag);
							$this->cellStyle("B".$flag, "FFFFFF", "000000", TRUE, 12, "Franklin Gothic Book");
							$hoja->setCellValue("B".$flag, "PEDIDOS A '".$value->nombre."' ".date("d-m-Y"));
							$this->cellStyle("K".$flag, "C00000", "000000", TRUE, 12, "Franklin Gothic Book");
							$hoja->setCellValue("K".$flag, "CEDIS");
							$this->cellStyle("P".$flag, "FF0066", "000000", TRUE, 12, "Franklin Gothic Book");
							$hoja->setCellValue("P".$flag, "CD INDUSTRIAL");
							$this->cellStyle("S".$flag, "C2B90A", "000000", TRUE, 12, "Franklin Gothic Book");
							$hoja->setCellValue("S".$flag, "SUMA CEDIS");
							$this->cellStyle("V".$flag, "01B0F0", "000000", TRUE, 12, "Franklin Gothic Book");
							$hoja->setCellValue("V".$flag, "ABARROTES");
							$this->cellStyle("AA".$flag, "FF0000", "000000", TRUE, 12, "Franklin Gothic Book");
							$hoja->setCellValue("AA".$flag, "VILLAS");
							$this->cellStyle("AF".$flag, "E26C0B", "000000", TRUE, 12, "Franklin Gothic Book");
							$hoja->setCellValue("AF".$flag, "TIENDA");
							$this->cellStyle("AJ".$flag, "C5C5C5", "000000", TRUE, 12, "Franklin Gothic Book");
							$hoja->setCellValue("AJ".$flag, "ULTRAMARINOS");
							$this->cellStyle("AN".$flag, "92D051", "000000", TRUE, 12, "Franklin Gothic Book");
							$hoja->setCellValue("AN".$flag, "TRINCHERAS");
							$this->cellStyle("AR".$flag, "B1A0C7", "000000", TRUE, 12, "Franklin Gothic Book");
							$hoja->setCellValue("AR".$flag, "AZT MERCADO");
							$this->cellStyle("AV".$flag, "DA9694", "000000", TRUE, 12, "Franklin Gothic Book");
							$hoja->setCellValue("AV".$flag, "TENENCIA");
							$this->cellStyle("AZ".$flag, "4CACC6", "000000", TRUE, 12, "Franklin Gothic Book");
							$hoja->setCellValue("AZ".$flag, "TIJERAS");
							
							$this->cellStyle("A3:BD4", "000000", "FFFFFF", TRUE, 12, "Franklin Gothic Book");
							$this->excelfile->getActiveSheet()->getStyle('A'.$flag.':BD'.$flag)->applyFromArray($styleArray);
							$flag++;
							$hoja->mergeCells('B'.$flag.':J'.$flag);
							$hoja->mergeCells('K'.$flag.':O'.$flag);
							$hoja->mergeCells('P'.$flag.':R'.$flag);
							$hoja->mergeCells('S'.$flag.':U'.$flag);
							$hoja->mergeCells('V'.$flag.':Z'.$flag);
							$hoja->mergeCells('AA'.$flag.':AE'.$flag);
							$hoja->mergeCells('AF'.$flag.':AI'.$flag);
							$hoja->mergeCells('AJ'.$flag.':AM'.$flag);
							$hoja->mergeCells('AN'.$flag.':AQ'.$flag);
							$hoja->mergeCells('AR'.$flag.':AU'.$flag);
							$hoja->mergeCells('AV'.$flag.':AY'.$flag);
							$hoja->mergeCells('AZ'.$flag.':BC'.$flag);
							$this->cellStyle("A".$flag.":BD".$flag."", "000000", "FFFFFF", TRUE, 12, "Franklin Gothic Book");
							$hoja->setCellValue("B".$flag, "DESCRIPCIÓN");
							$hoja->setCellValue("K".$flag, "EXISTENCIAS");
							$hoja->setCellValue("P".$flag, "EXISTENCIAS");
							$hoja->setCellValue("S".$flag, "EXISTENCIAS");
							$hoja->setCellValue("V".$flag, "EXISTENCIAS");
							$hoja->setCellValue("AA".$flag, "EXISTENCIAS");
							$hoja->setCellValue("AF".$flag, "EXISTENCIAS");
							$hoja->setCellValue("AJ".$flag, "EXISTENCIAS");
							$hoja->setCellValue("AN".$flag, "EXISTENCIAS");
							$hoja->setCellValue("AR".$flag, "EXISTENCIAS");
							$hoja->setCellValue("AV".$flag, "EXISTENCIAS");
							$hoja->setCellValue("AZ".$flag, "EXISTENCIAS");
							//Begin: TOTALES PEDIDOS PENDIENTES
							$hoja->mergeCells('BS'.$flag.':CC'.$flag);
							$this->cellStyle("BS".$flag.":CC".$flag."", "000000", "FFFFFF", TRUE, 12, "Franklin Gothic Book");
							$hoja->setCellValue("BS".$flag, "TOTAL POR PEDIDOS PENDIENTES");
							//End: TOTALES PEDIDOS PENDIENTES
							$flag++;
							$this->cellStyle("A".$flag.":BD".$flag."", "000000", "FFFFFF", TRUE, 12, "Franklin Gothic Book");
							$hoja->setCellValue("A".$flag, "CODIGO");
							$hoja->setCellValue("C".$flag, "REALES");
							$hoja->setCellValue("D".$flag, "COSTO");
							$hoja->setCellValue("F".$flag, "SISTEMA");
							$hoja->setCellValue("H".$flag, "PRECIO4");
							$hoja->setCellValue("I".$flag, "2DO");
							$hoja->setCellValue("J".$flag, "PROVEEDOR");
							$hoja->setCellValue("K".$flag, "CAJAS");
							$hoja->setCellValue("L".$flag, "PZAS");
							$hoja->setCellValue("M".$flag, "STOCK");
							$hoja->setCellValue("N".$flag, "PEND");
							$hoja->setCellValue("O".$flag, "PEDIDO");
							$hoja->setCellValue("P".$flag, "CAJAS");
							$hoja->setCellValue("Q".$flag, "PZAS");
							$hoja->setCellValue("R".$flag, "PEDIDO");
							$hoja->setCellValue("S".$flag, "CAJAS");
							$hoja->setCellValue("T".$flag, "PZAS");
							$hoja->setCellValue("U".$flag, "PEDIDO");
							$hoja->setCellValue("V".$flag, "CAJAS");
							$hoja->setCellValue("W".$flag, "PZAS");
							$hoja->setCellValue("X".$flag, "STOCK");
							$hoja->setCellValue("Y".$flag, "PEND");
							$hoja->setCellValue("Z".$flag, "PEDIDO");
							$hoja->setCellValue("AA".$flag, "CAJAS");
							$hoja->setCellValue("AB".$flag, "PZAS");
							$hoja->setCellValue("AC".$flag, "STOCK");
							$hoja->setCellValue("AD".$flag, "PEND");
							$hoja->setCellValue("AE".$flag, "PEDIDO");
							$hoja->setCellValue("AF".$flag, "CAJAS");
							$hoja->setCellValue("AG".$flag, "PZAS");
							$hoja->setCellValue("AH".$flag, "PEND");
							$hoja->setCellValue("AI".$flag, "PEDIDO");
							$hoja->setCellValue("AJ".$flag, "CAJAS");
							$hoja->setCellValue("AK".$flag, "PZAS");
							$hoja->setCellValue("AL".$flag, "PEND");
							$hoja->setCellValue("AM".$flag, "PEDIDO");
							$hoja->setCellValue("AN".$flag, "CAJAS");
							$hoja->setCellValue("AO".$flag, "PZAS");
							$hoja->setCellValue("AP".$flag, "PEND");
							$hoja->setCellValue("AQ".$flag, "PEDIDO");
							$hoja->setCellValue("AR".$flag, "CAJAS");
							$hoja->setCellValue("AS".$flag, "PZAS");
							$hoja->setCellValue("AT".$flag, "PEND");
							$hoja->setCellValue("AU".$flag, "PEDIDO");
							$hoja->setCellValue("AV".$flag, "CAJAS");
							$hoja->setCellValue("AW".$flag, "PZAS");
							$hoja->setCellValue("AX".$flag, "PEND");
							$hoja->setCellValue("AY".$flag, "PEDIDO");
							$hoja->setCellValue("AZ".$flag, "CAJAS");
							$hoja->setCellValue("BA".$flag, "PZAS");
							$hoja->setCellValue("BB".$flag, "PEND");
							$hoja->setCellValue("BC".$flag, "PEDIDO");
							$hoja->setCellValue("BD".$flag, "PROMOCION");
							$this->cellStyle("BE".$flag, "C00000", "000000", TRUE, 12, "Franklin Gothic Book");
							$this->cellStyle("BF".$flag, "01B0F0", "000000", TRUE, 12, "Franklin Gothic Book");
							$this->cellStyle("BG".$flag, "FF0000", "000000", TRUE, 12, "Franklin Gothic Book");
							$this->cellStyle("BH".$flag, "E26C0B", "000000", TRUE, 12, "Franklin Gothic Book");
							$this->cellStyle("BI".$flag, "C5C5C5", "000000", TRUE, 12, "Franklin Gothic Book");
							$this->cellStyle("BJ".$flag, "92D051", "000000", TRUE, 12, "Franklin Gothic Book");
							$this->cellStyle("BK".$flag, "B1A0C7", "000000", TRUE, 12, "Franklin Gothic Book");
							$this->cellStyle("BL".$flag, "DA9694", "000000", TRUE, 12, "Franklin Gothic Book");
							$this->cellStyle("BM".$flag, "4CACC6", "000000", TRUE, 12, "Franklin Gothic Book");
							$hoja->setCellValue("BN".$flag, "TOTAL");
							$this->cellStyle("BN".$flag."", "000000", "FFFFFF", TRUE, 12, "Franklin Gothic Book");
							$hoja->setCellValue("BO".$flag, "PEDIDOS");
							$this->cellStyle("BO".$flag."", "000000", "FFFFFF", TRUE, 12, "Franklin Gothic Book");
							$this->excelfile->getActiveSheet()->getStyle('BN'.$flag)->applyFromArray($styleArray);

							//Begin: TOTALES PEDIDOS PENDIENTES
							$this->cellStyle("BS".$flag, "C00000", "000000", TRUE, 12, "Franklin Gothic Book");
							$this->cellStyle("BT".$flag, "01B0F0", "000000", TRUE, 12, "Franklin Gothic Book");
							$this->cellStyle("BU".$flag, "FF0000", "000000", TRUE, 12, "Franklin Gothic Book");
							$this->cellStyle("BV".$flag, "E26C0B", "000000", TRUE, 12, "Franklin Gothic Book");
							$this->cellStyle("BW".$flag, "C5C5C5", "000000", TRUE, 12, "Franklin Gothic Book");
							$this->cellStyle("BX".$flag, "92D051", "000000", TRUE, 12, "Franklin Gothic Book");
							$this->cellStyle("BY".$flag, "B1A0C7", "000000", TRUE, 12, "Franklin Gothic Book");
							$this->cellStyle("BZ".$flag, "DA9694", "000000", TRUE, 12, "Franklin Gothic Book");
							$this->cellStyle("CA".$flag, "4CACC6", "000000", TRUE, 12, "Franklin Gothic Book");
							$this->cellStyle("CB".$flag."", "000000", "FFFFFF", TRUE, 12, "Franklin Gothic Book");
							$this->cellStyle("CC".$flag."", "000000", "FFFFFF", TRUE, 12, "Franklin Gothic Book");
							$hoja->setCellValue("CB".$flag, "TOTAL");
							$hoja->setCellValue("CC".$flag, "PEDIDOS");
							//End: TOTALES PEDIDOS PENDIENTES
						}
						foreach ($cotizacionesProveedor as $key => $value){
							//Existencias
							$this->excelfile->setActiveSheetIndex(0);
							$this->cellStyle("E".$flag1, "000000", "FFFFFF", FALSE, 12, "Franklin Gothic Book");
							$hoja1->setCellValue("E".$flag1, $value['familia']);
							$flag1 +=1;
							//Pedidos
							$this->excelfile->setActiveSheetIndex(1);
							$this->cellStyle("B".$flag, "000000", "FFFFFF", FALSE, 12, "Franklin Gothic Book");
							$hoja->setCellValue("B{$flag}", $value['familia']);
							$flag +=1;
							if ($value['articulos']) {
								foreach ($value['articulos'] as $key => $row){
									//Existencias
									$cargo = $row['cargo'];
									$registrazo = date('Y-m-d',strtotime($row['registrazo']));
									$this->excelfile->setActiveSheetIndex(0);
									$this->cellStyle("A".$flag1.":P".$flag1, "FFFFFF", "000000", FALSE, 12, "Franklin Gothic Book");
									
									$hoja1->setCellValue("D{$flag1}", $row['codigo'])->getStyle("D{$flag1}")->getNumberFormat()->setFormatCode('# ???/???');//Formato de fraccion
									if($row['color'] == '#92CEE3'){
										$this->cellStyle("D{$flag1}", "92CEE3", "000000", FALSE, 12, "Franklin Gothic Book");
									}else{
										$this->cellStyle("D{$flag1}", "FFFFFF", "000000", FALSE, 12, "Franklin Gothic Book");
									}
									$hoja1->setCellValue("E{$flag1}", $row['producto']);
									$hoja1->setCellValue("G{$flag1}", $row['promocion_first']);
									$hoja1->setCellValue("H{$flag1}", $row['cedis']);
									$hoja1->setCellValue("I{$flag1}", $row['abarrotes']);
									$hoja1->setCellValue("J{$flag1}", $row['pedregal']);
									$hoja1->setCellValue("K{$flag1}", $row['tienda']);
									$hoja1->setCellValue("L{$flag1}", $row['ultra']);
									$hoja1->setCellValue("M{$flag1}", $row['trincheras']);
									$hoja1->setCellValue("N{$flag1}", $row['mercado']);
									$hoja1->setCellValue("O{$flag1}", $row['tenencia']);
									$hoja1->setCellValue("P{$flag1}", $row['tijeras']);

									if ($row["imagen"] <> "" && !is_null($row["imagen"]) ) {
										$objDrawing = new PHPExcel_Worksheet_Drawing();
										$objDrawing->setName('COD'.$row['producto']);
										$objDrawing->setDescription('DESC'.$row['codigo']);
										$objDrawing->setPath("./Abarrotes/assets/img/productos/".$row["imagen"]."");
										$objDrawing->setWidth(50);
										$objDrawing->setHeight(50);
										$objDrawing->setCoordinates('F'.$flag1);
										$objDrawing->setOffsetX(5); 
										$objDrawing->setOffsetY(5);
										//$objDrawing->setUrl('http://abarrotesazteca.com/Abarrotes/assets/img/productos/'.$row["imagen"]);
										$objDrawing->setWorksheet($this->excelfile->getActiveSheet());
										$this->excelfile->getActiveSheet()->getRowDimension($flag1)->setRowHeight(60);
										$this->excelfile->getActiveSheet()->getStyleByColumnAndRow(10, $flag1)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_DATE_XLSX14);
										$this->excelfile->getActiveSheet()->getCell('F'.$flag1)->getHyperlink()->setUrl('http://abarrotesazteca.com/Abarrotes/assets/img/productos/'.str_replace("_thumb.",".",$row["imagen"]));
									}

									$hoja1->getStyle("A{$flag1}:P{$flag1}")
							                 ->getAlignment()
							                 ->setHorizontal(\PHPExcel_Style_Alignment::HORIZONTAL_LEFT);
							         
					                 
									//Pedidos
									$this->excelfile->setActiveSheetIndex(1);
									$this->cellStyle("A".$flag.":BK".$flag."", "FFFFFF", "000000", FALSE, 12, "Franklin Gothic Book");
									
									$hoja->setCellValue("A{$flag}", $row['codigo'])->getStyle("A{$flag}")->getNumberFormat()->setFormatCode('# ???/???');//Formato de fraccion
									if($row['color'] == '#92CEE3'){
										$this->cellStyle("A{$flag}", "92CEE3", "000000", FALSE, 12, "Franklin Gothic Book");
									}else{
										$this->cellStyle("A{$flag}", "FFFFFF", "000000", FALSE, 12, "Franklin Gothic Book");
									}
									$hoja->setCellValue("B{$flag}", $row['producto']);
									if ($id_proves === "VOLUMEN" || $id_proves === "AMARILLOS") {
										$hoja->setCellValue("E{$flag}", $row['proveedor_first']);
										$hoja->setCellValue("C{$flag}", $row['reales'])->getStyle("C{$flag}")->getNumberFormat()->setFormatCode('"$"#,##0.00_-');

										if(number_format(($row['precio_first'] - $row['reales']),2) === "0.01" || number_format(($row['precio_first'] - $row['reales']),2) === "-0.01"){
											$this->cellStyle("C{$flag}", "FFFFFF", "000000", FALSE, 12, "Franklin Gothic Book");
										}elseif($row['precio_first'] < $row['reales']){
											$this->cellStyle("C{$flag}", "FFFFFF", "E21600", FALSE, 12, "Franklin Gothic Book");
										}else{
											$this->cellStyle("C{$flag}", "FFFFFF", "249947", FALSE, 12, "Franklin Gothic Book");
										}

										if($row['precio_sistema'] < $row['precio_first']){
											$hoja->setCellValue("D{$flag}", $row['precio_first'])->getStyle("D{$flag}")->getNumberFormat()->setFormatCode('"$"#,##0.00_-');
											$this->cellStyle("D{$flag}", "FDB2B2", "E21111", FALSE, 12, "Franklin Gothic Book");
											$this->cellStyle("B{$flag}", "E21600", "000000", FALSE, 12, "Franklin Gothic Book");
										}else{
											$hoja->setCellValue("D{$flag}", $row['precio_first'])->getStyle("D{$flag}")->getNumberFormat()->setFormatCode('"$"#,##0.00_-');
											$this->cellStyle("D{$flag}", "96EAA8", "0C800C", FALSE, 12, "Franklin Gothic Book");
											$this->cellStyle("B{$flag}", "249947", "000000", FALSE, 12, "Franklin Gothic Book");
										}
										$hoja->setCellValue("G{$flag}", $row['precio_sistema'])->getStyle("G{$flag}")->getNumberFormat()->setFormatCode('"$"#,##0.00_-');//Formto de moneda
										$this->cellStyle("G".$flag, "FFFFFF","000000",  FALSE, 12, "Franklin Gothic Book");
										if($row['colorp'] == 1){
											$this->cellStyle("G{$flag}", "D6DCE4", "000000", FALSE, 12, "Franklin Gothic Book");
										}else{
											$this->cellStyle("G{$flag}", "FFFFFF", "000000", FALSE, 12, "Franklin Gothic Book");
										}
										$hoja->setCellValue("I{$flag}", $row['precio_four'])->getStyle("I{$flag}")->getNumberFormat()->setFormatCode('"$"#,##0.00_-');
										$this->cellStyle("I{$flag}", "FFFFFF", "000000", FALSE, 12, "Franklin Gothic Book");
										if($row['precio_sistema'] < $row['precio_next']){
											$hoja->setCellValue("J{$flag}", $row['precio_next'])->getStyle("J{$flag}")->getNumberFormat()->setFormatCode('"$"#,##0.00_-');
											$this->cellStyle("J{$flag}", "FDB2B2", "E21111", FALSE, 12, "Franklin Gothic Book");
										}else if($row['precio_next'] !== NULL){
											$hoja->setCellValue("J{$flag}", $row['precio_next'])->getStyle("J{$flag}")->getNumberFormat()->setFormatCode('"$"#,##0.00_-');
											$this->cellStyle("J{$flag}", "96EAA8", "0C800C", FALSE, 12, "Franklin Gothic Book");
										}else{
											$hoja->setCellValue("J{$flag}", $row['precio_next'])->getStyle("J{$flag}")->getNumberFormat()->setFormatCode('"$"#,##0.00_-');
											$this->cellStyle("J{$flag}", "FFFFFF", "000000", FALSE, 12, "Franklin Gothic Book");
										}
										$this->cellStyle("L".$flag.":AS".$flag, "FFFFFF", "000000", TRUE, 12, "Franklin Gothic Book");
										$hoja->setCellValue("K{$flag}", $row['proveedor_next']);
										$this->cellStyle("K".$flag, "FFFFFF", "000000", FALSE, 12, "Franklin Gothic Book");

										
										
										$hoja->setCellValue("N{$flag}", $row['cedis']);
										$hoja->setCellValue("X{$flag}", $row['abarrotes']);
										$hoja->setCellValue("AB{$flag}", $row['pedregal']);
										$hoja->setCellValue("AF{$flag}", $row['tienda']);
										$hoja->setCellValue("AJ{$flag}", $row['ultra']);
										$hoja->setCellValue("AN{$flag}", $row['trincheras']);
										$hoja->setCellValue("AR{$flag}", $row['mercado']);
										$hoja->setCellValue("AV{$flag}", $row['tenencia']);
										$hoja->setCellValue("AZ{$flag}", $row['tijeras']);

										$hoja->setCellValue("L{$flag}", $row['caja0']);
										$hoja->setCellValue("M{$flag}", $row['pz0']);
										$hoja->setCellValue("O{$flag}", $row['ped0']);
										$this->cellStyle("O{$flag}", "D4EAEF", "000000", TRUE, 12, "Franklin Gothic Book");

										$hoja->setCellValue("P{$flag}", $row['caja9']);
										$hoja->setCellValue("Q{$flag}", $row['pz9']);
										$hoja->setCellValue("R{$flag}", $row['ped9']);
										$this->cellStyle("R{$flag}", "D4EAEF", "000000", TRUE, 12, "Franklin Gothic Book");

										$hoja->setCellValue("S{$flag}", "=L".$flag."+P".$flag);
										$hoja->setCellValue("S{$flag}", "=M".$flag."+Q".$flag);
										$hoja->setCellValue("U{$flag}", "=O".$flag."+R".$flag);
										$this->cellStyle("U{$flag}", "D4EAEF", "000000", TRUE, 12, "Franklin Gothic Book");

										$hoja->setCellValue("V{$flag}", $row['caja1']);
										$hoja->setCellValue("W{$flag}", $row['pz1']);
										$hoja->setCellValue("Y{$flag}", $row['ped1']);
										$this->cellStyle("Y{$flag}", "D4EAEF", "000000", TRUE, 12, "Franklin Gothic Book");

										$hoja->setCellValue("Z{$flag}", $row['caja2']);
										$hoja->setCellValue("AA{$flag}", $row['pz2']);
										$hoja->setCellValue("AC{$flag}", $row['ped2']);
										$this->cellStyle("AC{$flag}", "D4EAEF", "000000", TRUE, 12, "Franklin Gothic Book");

										$hoja->setCellValue("AD{$flag}", $row['caja3']);
										$hoja->setCellValue("AE{$flag}", $row['pz3']);
										$hoja->setCellValue("AG{$flag}", $row['ped3']);
										$this->cellStyle("AG{$flag}", "D4EAEF", "000000", TRUE, 12, "Franklin Gothic Book");

										$hoja->setCellValue("AH{$flag}", $row['caja4']);
										$hoja->setCellValue("AI{$flag}", $row['pz4']);
										$hoja->setCellValue("AK{$flag}", $row['ped4']);
										$this->cellStyle("AK{$flag}", "D4EAEF", "000000", TRUE, 12, "Franklin Gothic Book");

										$hoja->setCellValue("AL{$flag}", $row['caja5']);
										$hoja->setCellValue("AM{$flag}", $row['pz5']);
										$hoja->setCellValue("AO{$flag}", $row['ped5']);
										$this->cellStyle("AO{$flag}", "D4EAEF", "000000", TRUE, 12, "Franklin Gothic Book");

										$hoja->setCellValue("AP{$flag}", $row['caja6']);
										$hoja->setCellValue("AQ{$flag}", $row['pz6']);
										$hoja->setCellValue("AS{$flag}", $row['ped6']);
										$this->cellStyle("AS{$flag}", "D4EAEF", "000000", TRUE, 12, "Franklin Gothic Book");

										$hoja->setCellValue("AT{$flag}", $row['caja7']);
										$hoja->setCellValue("AU{$flag}", $row['pz7']);
										$hoja->setCellValue("AW{$flag}", $row['ped7']);
										$this->cellStyle("AW{$flag}", "D4EAEF", "000000", TRUE, 12, "Franklin Gothic Book");

										$hoja->setCellValue("AX{$flag}", $row['caja8']);
										$hoja->setCellValue("AY{$flag}", $row['pz8']);
										$hoja->setCellValue("BA{$flag}", $row['ped8']);
										$this->cellStyle("BA{$flag}", "D4EAEF", "000000", TRUE, 12, "Franklin Gothic Book");

										

										$this->cellStyle("BB{$flag}", "FFFFFF", "000000", TRUE, 12, "Franklin Gothic Book");
										$hoja->setCellValue("BB{$flag}", $row['promocion_first']);
										$hoja->setCellValue("BC{$flag}", "=D".$flag."*O".$flag)->getStyle("BC{$flag}")->getNumberFormat()->setFormatCode('"$"#,##0.00_-');
										$hoja->setCellValue("BD{$flag}", "=D".$flag."*Y".$flag)->getStyle("BD{$flag}")->getNumberFormat()->setFormatCode('"$"#,##0.00_-');
										$hoja->setCellValue("BE{$flag}", "=D".$flag."*AC".$flag)->getStyle("BE{$flag}")->getNumberFormat()->setFormatCode('"$"#,##0.00_-');
										$hoja->setCellValue("BF{$flag}", "=D".$flag."*AG".$flag)->getStyle("BF{$flag}")->getNumberFormat()->setFormatCode('"$"#,##0.00_-');
										$hoja->setCellValue("BG{$flag}", "=D".$flag."*AK".$flag)->getStyle("BG{$flag}")->getNumberFormat()->setFormatCode('"$"#,##0.00_-');
										$hoja->setCellValue("BH{$flag}", "=D".$flag."*AO".$flag)->getStyle("BH{$flag}")->getNumberFormat()->setFormatCode('"$"#,##0.00_-');
										$hoja->setCellValue("BI{$flag}", "=D".$flag."*AS".$flag)->getStyle("BI{$flag}")->getNumberFormat()->setFormatCode('"$"#,##0.00_-');
										$hoja->setCellValue("BJ{$flag}", "=D".$flag."*AW".$flag)->getStyle("BJ{$flag}")->getNumberFormat()->setFormatCode('"$"#,##0.00_-');
										$hoja->setCellValue("BK{$flag}", "=D".$flag."*BA".$flag)->getStyle("BK{$flag}")->getNumberFormat()->setFormatCode('"$"#,##0.00_-');
										$this->cellStyle("BL{$flag}", "D4EAEF", "000000", FALSE, 12, "Franklin Gothic Book");
										$hoja->setCellValue("BL{$flag}", "=SUM(BC".$flag.":BK".$flag.")")->getStyle("BL{$flag}")->getNumberFormat()->setFormatCode('"$"#,##0.00_-');
										$this->cellStyle("BM{$flag}", "C2B90A", "000000", FALSE, 12, "Franklin Gothic Book");
										$hoja->setCellValue("BM{$flag}", "=O".$flag."+Y".$flag."+AC".$flag."+AG".$flag."+AK".$flag."+AO".$flag."+AS".$flag."+AW".$flag."+BA".$flag."");

										//Begin: TOTALES PEDIDOS PENDIENTES
										$hoja->setCellValue("BS{$flag}", "=D".$flag."*N".$flag)->getStyle("BS{$flag}")->getNumberFormat()->setFormatCode('"$"#,##0.00_-');
										$hoja->setCellValue("BT{$flag}", "=D".$flag."*X".$flag)->getStyle("BT{$flag}")->getNumberFormat()->setFormatCode('"$"#,##0.00_-');
										$hoja->setCellValue("BU{$flag}", "=D".$flag."*AB".$flag)->getStyle("BU{$flag}")->getNumberFormat()->setFormatCode('"$"#,##0.00_-');
										$hoja->setCellValue("BV{$flag}", "=D".$flag."*AF".$flag)->getStyle("BV{$flag}")->getNumberFormat()->setFormatCode('"$"#,##0.00_-');
										$hoja->setCellValue("BW{$flag}", "=D".$flag."*AJ".$flag)->getStyle("BW{$flag}")->getNumberFormat()->setFormatCode('"$"#,##0.00_-');
										$hoja->setCellValue("BX{$flag}", "=D".$flag."*AN".$flag)->getStyle("BX{$flag}")->getNumberFormat()->setFormatCode('"$"#,##0.00_-');
										$hoja->setCellValue("BY{$flag}", "=D".$flag."*AR".$flag)->getStyle("BY{$flag}")->getNumberFormat()->setFormatCode('"$"#,##0.00_-');
										$hoja->setCellValue("BZ{$flag}", "=D".$flag."*AV".$flag)->getStyle("BZ{$flag}")->getNumberFormat()->setFormatCode('"$"#,##0.00_-');
										$hoja->setCellValue("CA{$flag}", "=D".$flag."*AZ".$flag)->getStyle("CA{$flag}")->getNumberFormat()->setFormatCode('"$"#,##0.00_-');
										$this->cellStyle("CB{$flag}", "000000", "FFFFFF", FALSE, 12, "Franklin Gothic Book");
										$hoja->setCellValue("CB{$flag}", "=SUM(BS".$flag.":CA".$flag.")")->getStyle("CB{$flag}")->getNumberFormat()->setFormatCode('"$"#,##0.00_-');
										$this->cellStyle("CC{$flag}", "000000", "FFFFFF", FALSE, 12, "Franklin Gothic Book");
										$hoja->setCellValue("CC{$flag}", "=N".$flag."+X".$flag."+AB".$flag."+AF".$flag."+AJ".$flag."+AN".$flag."+AR".$flag."+AV".$flag."+AZ".$flag."");
										//End: TOTALES PEDIDOS PENDIENTES
									}else{
										$hoja->setCellValue("C{$flag}", $row['reales'])->getStyle("C{$flag}")->getNumberFormat()->setFormatCode('"$"#,##0.00_-');
										$this->cellStyle("C{$flag}", "FFFFFF", "000000", FALSE, 12, "Franklin Gothic Book");
										
										if(number_format(($row['precio_first'] - $row['reales']),2) === "0.01" || number_format(($row['precio_first'] - $row['reales']),2) === "-0.01"){
											$this->cellStyle("C{$flag}", "FFFFFF", "000000", FALSE, 12, "Franklin Gothic Book");
										}elseif($row['precio_first'] < $row['reales']){
											$this->cellStyle("C{$flag}", "FFFFFF", "E21600", FALSE, 12, "Franklin Gothic Book");
										}else{
											$this->cellStyle("C{$flag}", "FFFFFF", "249947", FALSE, 12, "Franklin Gothic Book");
										}

										
										if (number_format(($row['precio_sistema'] - $row['precio_first']),2) === "0.01" || number_format(($row['precio_sistema'] - $row['precio_first']),2) === "-0.01") {
											$hoja->setCellValue("D{$flag}", $row['precio_first'])->getStyle("D{$flag}")->getNumberFormat()->setFormatCode('"$"#,##0.00_-');
											$this->cellStyle("D{$flag}", "FFFFFF", "000000", FALSE, 12, "Franklin Gothic Book");
											$this->cellStyle("D{$flag}", "FFFFFF", "000000", FALSE, 12, "Franklin Gothic Book");
											$this->cellStyle("B{$flag}", "FFFFFF", "000000", FALSE, 12, "Franklin Gothic Book");
										}elseif($row['precio_sistema'] < $row['precio_first']){
											$hoja->setCellValue("D{$flag}", $row['precio_first'])->getStyle("D{$flag}")->getNumberFormat()->setFormatCode('"$"#,##0.00_-');
											$this->cellStyle("D{$flag}", "FDB2B2", "E21111", FALSE, 12, "Franklin Gothic Book");
											$this->cellStyle("D{$flag}", "FDB2B2", "E21111", FALSE, 12, "Franklin Gothic Book");
											$this->cellStyle("B{$flag}", "E21600", "000000", FALSE, 12, "Franklin Gothic Book");
										}else{
											$hoja->setCellValue("D{$flag}", $row['precio_first'])->getStyle("D{$flag}")->getNumberFormat()->setFormatCode('"$"#,##0.00_-');
											$this->cellStyle("D{$flag}", "96EAA8", "0C800C", FALSE, 12, "Franklin Gothic Book");
											$this->cellStyle("B{$flag}", "249947", "000000", FALSE, 12, "Franklin Gothic Book");
										}
										$hoja->setCellValue("F{$flag}", $row['precio_sistema'])->getStyle("F{$flag}")->getNumberFormat()->setFormatCode('"$"#,##0.00_-');//Formto de moneda
										$this->cellStyle("F".$flag, "FFFFFF","000000",  FALSE, 12, "Franklin Gothic Book");
										if($row['colorp'] == 1){
											$this->cellStyle("F{$flag}", "D6DCE4", "000000", FALSE, 12, "Franklin Gothic Book");
										}else{
											$this->cellStyle("F{$flag}", "FFFFFF", "000000", FALSE, 12, "Franklin Gothic Book");
										}
										$hoja->setCellValue("H{$flag}", $row['precio_four'])->getStyle("H{$flag}")->getNumberFormat()->setFormatCode('"$"#,##0.00_-');
										$this->cellStyle("H{$flag}", "FFFFFF", "000000", FALSE, 12, "Franklin Gothic Book");
										if($row['precio_sistema'] < $row['precio_next']){
											$hoja->setCellValue("I{$flag}", $row['precio_next'])->getStyle("I{$flag}")->getNumberFormat()->setFormatCode('"$"#,##0.00_-');
											$this->cellStyle("I{$flag}", "FDB2B2", "E21111", FALSE, 12, "Franklin Gothic Book");
										}else if($row['precio_next'] !== NULL){
											$hoja->setCellValue("I{$flag}", $row['precio_next'])->getStyle("I{$flag}")->getNumberFormat()->setFormatCode('"$"#,##0.00_-');
											$this->cellStyle("I{$flag}", "96EAA8", "0C800C", FALSE, 12, "Franklin Gothic Book");
										}else{
											$hoja->setCellValue("I{$flag}", $row['precio_next'])->getStyle("I{$flag}")->getNumberFormat()->setFormatCode('"$"#,##0.00_-');
											$this->cellStyle("I{$flag}", "FFFFFF", "000000", FALSE, 12, "Franklin Gothic Book");
										}
										$hoja->setCellValue("J{$flag}", $row['proveedor_next']);
										$this->cellStyle("J".$flag.":AY".$flag, "FFFFFF", "000000", TRUE, 12, "Franklin Gothic Book");

										$hoja->setCellValue("N{$flag}", $row['cedis']);
										$hoja->setCellValue("Y{$flag}", $row['abarrotes']);
										$hoja->setCellValue("AD{$flag}", $row['pedregal']);
										$hoja->setCellValue("AH{$flag}", $row['tienda']);
										$hoja->setCellValue("AL{$flag}", $row['ultra']);
										$hoja->setCellValue("AP{$flag}", $row['trincheras']);
										$hoja->setCellValue("AT{$flag}", $row['mercado']);
										$hoja->setCellValue("AX{$flag}", $row['tenencia']);
										$hoja->setCellValue("BB{$flag}", $row['tijeras']);

										$hoja->setCellValue("K{$flag}", $row['caja0']);
										$hoja->setCellValue("L{$flag}", $row['pz0']);
										$hoja->setCellValue("O{$flag}", $row['ped0']);
										$this->cellStyle("O{$flag}", "D4EAEF", "000000", TRUE, 12, "Franklin Gothic Book");

										$hoja->setCellValue("P{$flag}", $row['caja9']);
										$hoja->setCellValue("Q{$flag}", $row['pz9']);
										$hoja->setCellValue("R{$flag}", $row['ped9']);
										$this->cellStyle("R{$flag}", "D4EAEF", "000000", TRUE, 12, "Franklin Gothic Book");

										$hoja->setCellValue("S{$flag}", "=K".$flag."+P".$flag);
										$hoja->setCellValue("T{$flag}", "=L".$flag."+Q".$flag);
										$hoja->setCellValue("U{$flag}", "=O".$flag."+R".$flag);
										$this->cellStyle("U{$flag}", "D4EAEF", "000000", TRUE, 12, "Franklin Gothic Book");

										$hoja->setCellValue("V{$flag}", $row['caja1']);
										$hoja->setCellValue("W{$flag}", $row['pz1']);
										$hoja->setCellValue("X{$flag}", $row['stocant']);
										$hoja->setCellValue("Z{$flag}", $row['ped1']);
										$this->cellStyle("Z{$flag}", "D4EAEF", "000000", TRUE, 12, "Franklin Gothic Book");

										$hoja->setCellValue("AA{$flag}", $row['caja2']);
										$hoja->setCellValue("AB{$flag}", $row['pz2']);
										$hoja->setCellValue("AE{$flag}", $row['ped2']);
										$this->cellStyle("AE{$flag}", "D4EAEF", "000000", TRUE, 12, "Franklin Gothic Book");

										$hoja->setCellValue("AF{$flag}", $row['caja3']);
										$hoja->setCellValue("AG{$flag}", $row['pz3']);
										$hoja->setCellValue("AI{$flag}", $row['ped3']);
										$this->cellStyle("AI{$flag}", "D4EAEF", "000000", TRUE, 12, "Franklin Gothic Book");

										$hoja->setCellValue("AJ{$flag}", $row['caja4']);
										$hoja->setCellValue("AK{$flag}", $row['pz4']);
										$hoja->setCellValue("AM{$flag}", $row['ped4']);
										$this->cellStyle("AM{$flag}", "D4EAEF", "000000", TRUE, 12, "Franklin Gothic Book");

										$hoja->setCellValue("AN{$flag}", $row['caja5']);
										$hoja->setCellValue("AO{$flag}", $row['pz5']);
										$hoja->setCellValue("AQ{$flag}", $row['ped5']);
										$this->cellStyle("AQ{$flag}", "D4EAEF", "000000", TRUE, 12, "Franklin Gothic Book");

										$hoja->setCellValue("AR{$flag}", $row['caja6']);
										$hoja->setCellValue("AS{$flag}", $row['pz6']);
										$hoja->setCellValue("AU{$flag}", $row['ped6']);
										$this->cellStyle("AU{$flag}", "D4EAEF", "000000", TRUE, 12, "Franklin Gothic Book");

										$hoja->setCellValue("AV{$flag}", $row['caja7']);
										$hoja->setCellValue("AW{$flag}", $row['pz7']);
										$hoja->setCellValue("AY{$flag}", $row['ped7']);
										$this->cellStyle("AY{$flag}", "D4EAEF", "000000", TRUE, 12, "Franklin Gothic Book");

										$hoja->setCellValue("AZ{$flag}", $row['caja8']);
										$hoja->setCellValue("BA{$flag}", $row['pz8']);
										$hoja->setCellValue("BC{$flag}", $row['ped8']);
										$this->cellStyle("BC{$flag}", "D4EAEF", "000000", TRUE, 12, "Franklin Gothic Book");

										

										$hoja->setCellValue("BD{$flag}", $row['promocion_first']);
										$hoja->setCellValue("BE{$flag}", "=D".$flag."*O".$flag)->getStyle("BE{$flag}")->getNumberFormat()->setFormatCode('"$"#,##0.00_-');
										$hoja->setCellValue("BF{$flag}", "=D".$flag."*Z".$flag)->getStyle("BF{$flag}")->getNumberFormat()->setFormatCode('"$"#,##0.00_-');
										$hoja->setCellValue("BG{$flag}", "=D".$flag."*AE".$flag)->getStyle("BG{$flag}")->getNumberFormat()->setFormatCode('"$"#,##0.00_-');
										$hoja->setCellValue("BH{$flag}", "=D".$flag."*AI".$flag)->getStyle("BH{$flag}")->getNumberFormat()->setFormatCode('"$"#,##0.00_-');
										$hoja->setCellValue("BI{$flag}", "=D".$flag."*AM".$flag)->getStyle("BI{$flag}")->getNumberFormat()->setFormatCode('"$"#,##0.00_-');
										$hoja->setCellValue("BJ{$flag}", "=D".$flag."*AQ".$flag)->getStyle("BJ{$flag}")->getNumberFormat()->setFormatCode('"$"#,##0.00_-');
										$hoja->setCellValue("BK{$flag}", "=D".$flag."*AU".$flag)->getStyle("BK{$flag}")->getNumberFormat()->setFormatCode('"$"#,##0.00_-');
										$hoja->setCellValue("BL{$flag}", "=D".$flag."*AY".$flag)->getStyle("BL{$flag}")->getNumberFormat()->setFormatCode('"$"#,##0.00_-');
										$hoja->setCellValue("BM{$flag}", "=D".$flag."*BC".$flag)->getStyle("BM{$flag}")->getNumberFormat()->setFormatCode('"$"#,##0.00_-');
										$this->cellStyle("BN{$flag}", "D4EAEF", "000000", FALSE, 12, "Franklin Gothic Book");
										$hoja->setCellValue("BN{$flag}", "=SUM(BE".$flag.":BM".$flag.")")->getStyle("BN{$flag}")->getNumberFormat()->setFormatCode('"$"#,##0.00_-');
										$this->cellStyle("BO{$flag}", "C2B90A", "000000", FALSE, 12, "Franklin Gothic Book");
										$hoja->setCellValue("BO{$flag}", "=O".$flag."+Z".$flag."+AE".$flag."+AI".$flag."+AM".$flag."+AQ".$flag."+AU".$flag."+AY".$flag."+BC".$flag."");

										//Begin: TOTALES PEDIDOS PENDIENTES
										$hoja->setCellValue("BS{$flag}", "=D".$flag."*N".$flag)->getStyle("BS{$flag}")->getNumberFormat()->setFormatCode('"$"#,##0.00_-');
										$hoja->setCellValue("BT{$flag}", "=D".$flag."*Y".$flag)->getStyle("BT{$flag}")->getNumberFormat()->setFormatCode('"$"#,##0.00_-');
										$hoja->setCellValue("BU{$flag}", "=D".$flag."*AD".$flag)->getStyle("BU{$flag}")->getNumberFormat()->setFormatCode('"$"#,##0.00_-');
										$hoja->setCellValue("BV{$flag}", "=D".$flag."*AH".$flag)->getStyle("BV{$flag}")->getNumberFormat()->setFormatCode('"$"#,##0.00_-');
										$hoja->setCellValue("BW{$flag}", "=D".$flag."*AL".$flag)->getStyle("BW{$flag}")->getNumberFormat()->setFormatCode('"$"#,##0.00_-');
										$hoja->setCellValue("BX{$flag}", "=D".$flag."*AP".$flag)->getStyle("BX{$flag}")->getNumberFormat()->setFormatCode('"$"#,##0.00_-');
										$hoja->setCellValue("BY{$flag}", "=D".$flag."*AT".$flag)->getStyle("BY{$flag}")->getNumberFormat()->setFormatCode('"$"#,##0.00_-');
										$hoja->setCellValue("BZ{$flag}", "=D".$flag."*AX".$flag)->getStyle("BZ{$flag}")->getNumberFormat()->setFormatCode('"$"#,##0.00_-');
										$hoja->setCellValue("CA{$flag}", "=D".$flag."*BB".$flag)->getStyle("CA{$flag}")->getNumberFormat()->setFormatCode('"$"#,##0.00_-');
										$this->cellStyle("CB{$flag}", "000000", "FFFFFF", FALSE, 12, "Franklin Gothic Book");
										$hoja->setCellValue("CB{$flag}", "=SUM(BS".$flag.":CA".$flag.")")->getStyle("CB{$flag}")->getNumberFormat()->setFormatCode('"$"#,##0.00_-');
										$this->cellStyle("CC{$flag}", "000000", "FFFFFF", FALSE, 12, "Franklin Gothic Book");
										$hoja->setCellValue("CC{$flag}", "=N".$flag."+Y".$flag."+AD".$flag."+AH".$flag."+AL".$flag."+AP".$flag."+AT".$flag."+AX".$flag."+BB".$flag."");
										//End: TOTALES PEDIDOS PENDIENTES
									}
									$border_style= array('borders' => array('right' => array('style' =>
										PHPExcel_Style_Border::BORDER_THIN,'color' => array('argb' => '000000'),)));
									$this->excelfile->setActiveSheetIndex(1);
									if ($id_proves === "VOLUMEN"){
										$this->excelfile->getActiveSheet()->getStyle('A'.$flag.':BK'.$flag)->applyFromArray($styleArray);
										$this->excelfile->getActiveSheet()->getStyle('BL'.$flag.':BM'.$flag)->applyFromArray($styleArray);
										if($row['precio_sistema'] == 0){
											$row['precio_sistema'] = 1;
										}
										if($row['precio_four'] == 0){
											$row['precio_four'] = 1;
										}
											$hoja->setCellValue("F{$flag}",100 - ($row['precio_first'] * 100 / $row['precio_sistema']))->getStyle("F{$flag}")->getNumberFormat()->setFormatCode('"%"#,##0.00_-');;
											$this->cellStyle("F".$flag, "FF9999", "000000", FALSE, 10, "Franklin Gothic Book");

											$hoja->setCellValue("H{$flag}", 100 - ($row['precio_sistema'] * 100 / $row['precio_four']))->getStyle("H{$flag}")->getNumberFormat()->setFormatCode('"%"#,##0.00_-');;
											$this->cellStyle("H".$flag, "FF9999", "000000", FALSE, 10, "Franklin Gothic Book");
									}else{
										$this->excelfile->getActiveSheet()->getStyle('A'.$flag.':BM'.$flag)->applyFromArray($styleArray);
										$this->excelfile->getActiveSheet()->getStyle('BN'.$flag)->applyFromArray($styleArray);
										$this->excelfile->getActiveSheet()->getStyle('BO'.$flag)->applyFromArray($styleArray);
										if($row['precio_sistema'] == 0){
											$row['precio_sistema'] = 1;
										}
										if($row['precio_four'] == 0){
											$row['precio_four'] = 1;
										}
											$hoja->setCellValue("E{$flag}",100 - ($row['precio_first'] * 100 / $row['precio_sistema']))->getStyle("E{$flag}")->getNumberFormat()->setFormatCode('"%"#,##0.00_-');;
											$this->cellStyle("E".$flag, "FF9999", "000000", FALSE, 10, "Franklin Gothic Book");
											$hoja->setCellValue("G{$flag}", 100 - ($row['precio_sistema'] * 100 / $row['precio_four']))->getStyle("G{$flag}")->getNumberFormat()->setFormatCode('"%"#,##0.00_-');;
											$this->cellStyle("G".$flag, "FF9999", "000000", FALSE, 10, "Franklin Gothic Book");
									}
									
									$this->excelfile->setActiveSheetIndex(0);
									$this->excelfile->getActiveSheet()->getStyle('A'.$flag1.':P'.$flag1)->applyFromArray($styleArray);
									$hoja->getStyle("A{$flag}:I{$flag}")
							                 ->getAlignment()
							                 ->setHorizontal(\PHPExcel_Style_Alignment::HORIZONTAL_LEFT);

							   
									$flag ++;
									$flag1 ++;
								}
							}
						}
						if ($id_proves === "VOLUMEN" || $id_proves === "AMARILLOS"){
							$flagf = $flag;
							$flagfs = $flag - 1;
							$hoja->setCellValue("BC{$flagf}", "=SUM(BC5:BC".$flagfs.")")->getStyle("BC{$flag}")->getNumberFormat()->setFormatCode('"$"#,##0.00_-');
							$hoja->setCellValue("BD{$flagf}", "=SUM(BD5:BD".$flagfs.")")->getStyle("BD{$flag}")->getNumberFormat()->setFormatCode('"$"#,##0.00_-');
							$hoja->setCellValue("BE{$flagf}", "=SUM(BE5:BE".$flagfs.")")->getStyle("BE{$flag}")->getNumberFormat()->setFormatCode('"$"#,##0.00_-');
							$hoja->setCellValue("BF{$flagf}", "=SUM(BF5:BF".$flagfs.")")->getStyle("BF{$flag}")->getNumberFormat()->setFormatCode('"$"#,##0.00_-');
							$hoja->setCellValue("BG{$flagf}", "=SUM(BG5:BG".$flagfs.")")->getStyle("BG{$flag}")->getNumberFormat()->setFormatCode('"$"#,##0.00_-');
							$hoja->setCellValue("BH{$flagf}", "=SUM(BH5:BH".$flagfs.")")->getStyle("BH{$flag}")->getNumberFormat()->setFormatCode('"$"#,##0.00_-');
							$hoja->setCellValue("BI{$flagf}", "=SUM(BI5:BI".$flagfs.")")->getStyle("BI{$flag}")->getNumberFormat()->setFormatCode('"$"#,##0.00_-');
							$hoja->setCellValue("BJ{$flagf}", "=SUM(BJ5:BJ".$flagfs.")")->getStyle("BJ{$flag}")->getNumberFormat()->setFormatCode('"$"#,##0.00_-');
							$hoja->setCellValue("BK{$flagf}", "=SUM(BK5:BK".$flagfs.")")->getStyle("BK{$flag}")->getNumberFormat()->setFormatCode('"$"#,##0.00_-');
							$this->cellStyle("BL{$flagf}", "000000", "FFFFFF", FALSE, 12, "Franklin Gothic Book");
							$hoja->setCellValue("BL{$flagf}", "=SUM(BL5:BL".$flagfs.")")->getStyle("BL{$flagf}")->getNumberFormat()->setFormatCode('"$"#,##0.00_-');
							$sumall[1] .= "BC".$flagf."+";
							$sumall[2] .= "BD".$flagf."+";
							$sumall[3] .= "BE".$flagf."+";
							$sumall[4] .= "BF".$flagf."+";
							$sumall[5] .= "BG".$flagf."+";
							$sumall[6] .= "BH".$flagf."+";
							$sumall[7] .= "BI".$flagf."+";
							$sumall[8] .= "BJ".$flagf."+";
							$sumall[9] .= "BK".$flagf."+";
							$sumall[10] .= "BL".$flagf."+";
							//Begin: TOTALES PEDIDOS PENDIENTES
							$hoja->setCellValue("BS{$flag}", "=SUM(BS".$flage.":BS".$flagfs.")")->getStyle("BS{$flag}")->getNumberFormat()->setFormatCode('"$"#,##0.00_-');
							$hoja->setCellValue("BT{$flag}", "=SUM(BT".$flage.":BT".$flagfs.")")->getStyle("BT{$flag}")->getNumberFormat()->setFormatCode('"$"#,##0.00_-');
							$hoja->setCellValue("BU{$flag}", "=SUM(BU".$flage.":BU".$flagfs.")")->getStyle("BU{$flag}")->getNumberFormat()->setFormatCode('"$"#,##0.00_-');
							$hoja->setCellValue("BV{$flag}", "=SUM(BV".$flage.":BV".$flagfs.")")->getStyle("BV{$flag}")->getNumberFormat()->setFormatCode('"$"#,##0.00_-');
							$hoja->setCellValue("BW{$flag}", "=SUM(BW".$flage.":BW".$flagfs.")")->getStyle("BW{$flag}")->getNumberFormat()->setFormatCode('"$"#,##0.00_-');
							$hoja->setCellValue("BX{$flag}", "=SUM(BX".$flage.":BX".$flagfs.")")->getStyle("BX{$flag}")->getNumberFormat()->setFormatCode('"$"#,##0.00_-');
							$hoja->setCellValue("BY{$flag}", "=SUM(BY".$flage.":BY".$flagfs.")")->getStyle("BY{$flag}")->getNumberFormat()->setFormatCode('"$"#,##0.00_-');
							$hoja->setCellValue("BZ{$flag}", "=SUM(BZ".$flage.":BZ".$flagfs.")")->getStyle("BZ{$flag}")->getNumberFormat()->setFormatCode('"$"#,##0.00_-');
							$hoja->setCellValue("CA{$flag}", "=SUM(CA".$flage.":CA".$flagfs.")")->getStyle("CA{$flag}")->getNumberFormat()->setFormatCode('"$"#,##0.00_-');
							$hoja->setCellValue("CB{$flag}", "=SUM(CB".$flage.":CB".$flagfs.")")->getStyle("CB{$flag}")->getNumberFormat()->setFormatCode('"$"#,##0.00_-');
							//End: TOTALES PEDIDOS PENDIENTES
						}else{
							$flagf = $flag;
							$flagfs = $flag - 1;
							$hoja->setCellValue("BE{$flagf}", "=SUM(BE".$flage.":BE".$flagfs.")")->getStyle("BE{$flag}")->getNumberFormat()->setFormatCode('"$"#,##0.00_-');
							$hoja->setCellValue("BF{$flagf}", "=SUM(BF".$flage.":BF".$flagfs.")")->getStyle("BF{$flag}")->getNumberFormat()->setFormatCode('"$"#,##0.00_-');
							$hoja->setCellValue("BG{$flagf}", "=SUM(BG".$flage.":BG".$flagfs.")")->getStyle("BG{$flag}")->getNumberFormat()->setFormatCode('"$"#,##0.00_-');
							$hoja->setCellValue("BH{$flagf}", "=SUM(BH".$flage.":BH".$flagfs.")")->getStyle("BH{$flag}")->getNumberFormat()->setFormatCode('"$"#,##0.00_-');
							$hoja->setCellValue("BI{$flagf}", "=SUM(BI".$flage.":BI".$flagfs.")")->getStyle("BI{$flag}")->getNumberFormat()->setFormatCode('"$"#,##0.00_-');
							$hoja->setCellValue("BJ{$flagf}", "=SUM(BJ".$flage.":BJ".$flagfs.")")->getStyle("BJ{$flag}")->getNumberFormat()->setFormatCode('"$"#,##0.00_-');
							$hoja->setCellValue("BK{$flagf}", "=SUM(BK".$flage.":BK".$flagfs.")")->getStyle("BK{$flag}")->getNumberFormat()->setFormatCode('"$"#,##0.00_-');
							$hoja->setCellValue("BL{$flagf}", "=SUM(BL".$flage.":BL".$flagfs.")")->getStyle("BL{$flag}")->getNumberFormat()->setFormatCode('"$"#,##0.00_-');
							$hoja->setCellValue("BM{$flagf}", "=SUM(BM".$flage.":BM".$flagfs.")")->getStyle("BM{$flag}")->getNumberFormat()->setFormatCode('"$"#,##0.00_-');
							$hoja->setCellValue("BN{$flagf}", "=SUM(BN".$flage.":BN".$flagfs.")")->getStyle("BN{$flag}")->getNumberFormat()->setFormatCode('"$"#,##0.00_-');
							$this->cellStyle("BN{$flagf}", "000000", "FFFFFF", FALSE, 12, "Franklin Gothic Book");
							$hoja->setCellValue("BN{$flagf}", "=SUM(BN".$flage.":BN".$flagfs.")")->getStyle("BN{$flag}")->getNumberFormat()->setFormatCode('"$"#,##0.00_-');
							$sumall[1] .= "BE".$flagf."+";
							$sumall[2] .= "BF".$flagf."+";
							$sumall[3] .= "BG".$flagf."+";
							$sumall[4] .= "BH".$flagf."+";
							$sumall[5] .= "BI".$flagf."+";
							$sumall[6] .= "BJ".$flagf."+";
							$sumall[7] .= "BK".$flagf."+";
							$sumall[8] .= "BL".$flagf."+";
							$sumall[9] .= "BM".$flagf."+";
							$sumall[10] .= "BN".$flagf."+";
							//Begin: TOTALES PEDIDOS PENDIENTES
							$hoja->setCellValue("BS{$flag}", "=SUM(BS".$flage.":BS".$flagfs.")")->getStyle("BS{$flag}")->getNumberFormat()->setFormatCode('"$"#,##0.00_-');
							$hoja->setCellValue("BT{$flag}", "=SUM(BT".$flage.":BT".$flagfs.")")->getStyle("BT{$flag}")->getNumberFormat()->setFormatCode('"$"#,##0.00_-');
							$hoja->setCellValue("BU{$flag}", "=SUM(BU".$flage.":BU".$flagfs.")")->getStyle("BU{$flag}")->getNumberFormat()->setFormatCode('"$"#,##0.00_-');
							$hoja->setCellValue("BV{$flag}", "=SUM(BV".$flage.":BV".$flagfs.")")->getStyle("BV{$flag}")->getNumberFormat()->setFormatCode('"$"#,##0.00_-');
							$hoja->setCellValue("BW{$flag}", "=SUM(BW".$flage.":BW".$flagfs.")")->getStyle("BW{$flag}")->getNumberFormat()->setFormatCode('"$"#,##0.00_-');
							$hoja->setCellValue("BX{$flag}", "=SUM(BX".$flage.":BX".$flagfs.")")->getStyle("BX{$flag}")->getNumberFormat()->setFormatCode('"$"#,##0.00_-');
							$hoja->setCellValue("BY{$flag}", "=SUM(BY".$flage.":BY".$flagfs.")")->getStyle("BY{$flag}")->getNumberFormat()->setFormatCode('"$"#,##0.00_-');
							$hoja->setCellValue("BZ{$flag}", "=SUM(BZ".$flage.":BZ".$flagfs.")")->getStyle("BZ{$flag}")->getNumberFormat()->setFormatCode('"$"#,##0.00_-');
							$hoja->setCellValue("CA{$flag}", "=SUM(CA".$flage.":CA".$flagfs.")")->getStyle("CA{$flag}")->getNumberFormat()->setFormatCode('"$"#,##0.00_-');
							$hoja->setCellValue("CB{$flag}", "=SUM(CB".$flage.":CB".$flagfs.")")->getStyle("CB{$flag}")->getNumberFormat()->setFormatCode('"$"#,##0.00_-');
							//End: TOTALES PEDIDOS PENDIENTES
							if ($id_proves <> "VOLUMEN"){
								$hoja->setCellValue("B{$flag}", "RESPONSABLE : ".$cargo);
								$this->cellStyle("B{$flag}", "FFFF00", "FF0000", TRUE, 12, "Franklin Gothic Book");
							}
							$flage = $flag + 21;

							$flag = $flag + 3;
							$hoja->mergeCells('B'.$flag.':C'.$flag);
							$this->cellStyle("B".$flag, "000000", "FFFFFF", TRUE, 12, "Franklin Gothic Book");
							$hoja->setCellValue("B".$flag, "TOTALES POR '".$provname."'");

							$hoja->mergeCells('G'.$flag.':I'.$flag);
							$this->cellStyle("G".$flag, "000000", "FFFFFF", TRUE, 12, "Franklin Gothic Book");
							$hoja->setCellValue("G".$flag, "TOTALES PENDIENTES '".$provname."'");
							$flag++;

							$this->cellStyle("B".$flag, "C00000", "000000", TRUE, 12, "Franklin Gothic Book");
							$hoja->setCellValue("B".$flag, "CEDIS");
							$hoja->setCellValue("C{$flag}", "=BE{$flagf}")->getStyle("C{$flag}")->getNumberFormat()->setFormatCode('"$"#,##0.00_-');
							$hoja->mergeCells('G'.$flag.':H'.$flag);
							$this->cellStyle("G".$flag, "000000", "FFFFFF", TRUE, 12, "Franklin Gothic Book");
							$hoja->setCellValue("G".$flag, "CEDIS");
							$hoja->setCellValue("I{$flag}", "=BS{$flagf}")->getStyle("I{$flag}")->getNumberFormat()->setFormatCode('"$"#,##0.00_-');
							$flag++;
							$this->cellStyle("B".$flag, "01B0F0", "000000", TRUE, 12, "Franklin Gothic Book");
							$hoja->setCellValue("B".$flag, "ABARROTES");
							$hoja->setCellValue("C{$flag}", "=BF{$flagf}")->getStyle("C{$flag}")->getNumberFormat()->setFormatCode('"$"#,##0.00_-');
							$hoja->mergeCells('G'.$flag.':H'.$flag);
							$this->cellStyle("G".$flag, "000000", "FFFFFF", TRUE, 12, "Franklin Gothic Book");
							$hoja->setCellValue("G".$flag, "ABARROTES");
							$hoja->setCellValue("I{$flag}", "=BT{$flagf}")->getStyle("I{$flag}")->getNumberFormat()->setFormatCode('"$"#,##0.00_-');
							$flag++;
							$this->cellStyle("B".$flag, "FF0000", "000000", TRUE, 12, "Franklin Gothic Book");
							$hoja->setCellValue("B".$flag, "VILLAS");
							$hoja->setCellValue("C{$flag}", "=BG{$flagf}")->getStyle("C{$flag}")->getNumberFormat()->setFormatCode('"$"#,##0.00_-');
							$hoja->mergeCells('G'.$flag.':H'.$flag);
							$this->cellStyle("G".$flag, "000000", "FFFFFF", TRUE, 12, "Franklin Gothic Book");
							$hoja->setCellValue("G".$flag, "VILLAS");
							$hoja->setCellValue("I{$flag}", "=BU{$flagf}")->getStyle("I{$flag}")->getNumberFormat()->setFormatCode('"$"#,##0.00_-');
							$flag++;
							$this->cellStyle("B".$flag, "E26C0B", "000000", TRUE, 12, "Franklin Gothic Book");
							$hoja->setCellValue("B".$flag, "TIENDA");
							$hoja->setCellValue("C{$flag}", "=BH{$flagf}")->getStyle("C{$flag}")->getNumberFormat()->setFormatCode('"$"#,##0.00_-');
							$hoja->mergeCells('G'.$flag.':H'.$flag);
							$this->cellStyle("G".$flag, "000000", "FFFFFF", TRUE, 12, "Franklin Gothic Book");
							$hoja->setCellValue("G".$flag, "TIENDA");
							$hoja->setCellValue("I{$flag}", "=BV{$flagf}")->getStyle("I{$flag}")->getNumberFormat()->setFormatCode('"$"#,##0.00_-');
							$flag++;
							$this->cellStyle("B".$flag, "C5C5C5", "000000", TRUE, 12, "Franklin Gothic Book");
							$hoja->setCellValue("B".$flag, "ULTRAMARINOS");
							$hoja->setCellValue("C{$flag}", "=BI{$flagf}")->getStyle("C{$flag}")->getNumberFormat()->setFormatCode('"$"#,##0.00_-');
							$hoja->mergeCells('G'.$flag.':H'.$flag);
							$this->cellStyle("G".$flag, "000000", "FFFFFF", TRUE, 12, "Franklin Gothic Book");
							$hoja->setCellValue("G".$flag, "ULTRAMARINOS");
							$hoja->setCellValue("I{$flag}", "=BW{$flagf}")->getStyle("I{$flag}")->getNumberFormat()->setFormatCode('"$"#,##0.00_-');
							$flag++;
							$this->cellStyle("B".$flag, "92D051", "000000", TRUE, 12, "Franklin Gothic Book");
							$hoja->setCellValue("B".$flag, "TRINCHERAS");
							$hoja->setCellValue("C{$flag}", "=BJ{$flagf}")->getStyle("C{$flag}")->getNumberFormat()->setFormatCode('"$"#,##0.00_-');
							$hoja->mergeCells('G'.$flag.':H'.$flag);
							$this->cellStyle("G".$flag, "000000", "FFFFFF", TRUE, 12, "Franklin Gothic Book");
							$hoja->setCellValue("G".$flag, "TRINCHERAS");
							$hoja->setCellValue("I{$flag}", "=BX{$flagf}")->getStyle("I{$flag}")->getNumberFormat()->setFormatCode('"$"#,##0.00_-');
							$flag++;
							$this->cellStyle("B".$flag, "B1A0C7", "000000", TRUE, 12, "Franklin Gothic Book");
							$hoja->setCellValue("B".$flag, "AZT MERCADO");
							$hoja->setCellValue("C{$flag}", "=BK{$flagf}")->getStyle("C{$flag}")->getNumberFormat()->setFormatCode('"$"#,##0.00_-');
							$hoja->mergeCells('G'.$flag.':H'.$flag);
							$this->cellStyle("G".$flag, "000000", "FFFFFF", TRUE, 12, "Franklin Gothic Book");
							$hoja->setCellValue("G".$flag, "AZT MERCADO");
							$hoja->setCellValue("I{$flag}", "=BY{$flagf}")->getStyle("I{$flag}")->getNumberFormat()->setFormatCode('"$"#,##0.00_-');
							$flag++;
							$this->cellStyle("B".$flag, "DA9694", "000000", TRUE, 12, "Franklin Gothic Book");
							$hoja->setCellValue("B".$flag, "TENENCIA");
							$hoja->setCellValue("C{$flag}", "=BL{$flagf}")->getStyle("C{$flag}")->getNumberFormat()->setFormatCode('"$"#,##0.00_-');
							$hoja->mergeCells('G'.$flag.':H'.$flag);
							$this->cellStyle("G".$flag, "000000", "FFFFFF", TRUE, 12, "Franklin Gothic Book");
							$hoja->setCellValue("G".$flag, "TENENCIA");
							$hoja->setCellValue("I{$flag}", "=BZ{$flagf}")->getStyle("I{$flag}")->getNumberFormat()->setFormatCode('"$"#,##0.00_-');
							$flag++;
							$this->cellStyle("B".$flag, "4CACC6", "000000", TRUE, 12, "Franklin Gothic Book");
							$hoja->setCellValue("B".$flag, "TIJERAS");
							$hoja->setCellValue("C{$flag}", "=BM{$flagf}")->getStyle("C{$flag}")->getNumberFormat()->setFormatCode('"$"#,##0.00_-');
							$hoja->mergeCells('G'.$flag.':H'.$flag);
							$this->cellStyle("G".$flag, "000000", "FFFFFF", TRUE, 12, "Franklin Gothic Book");
							$hoja->setCellValue("G".$flag, "TIJERAS");
							$hoja->setCellValue("I{$flag}", "=CA{$flagf}")->getStyle("I{$flag}")->getNumberFormat()->setFormatCode('"$"#,##0.00_-');
							$flag++;
							$this->cellStyle("B".$flag, "000000", "FFFFFF", TRUE, 12, "Franklin Gothic Book");
							$hoja->setCellValue("B".$flag, "TOTAL");
							$hoja->setCellValue("C{$flag}", "=BN{$flagf}")->getStyle("C{$flag}")->getNumberFormat()->setFormatCode('"$"#,##0.00_-');
							$hoja->mergeCells('G'.$flag.':H'.$flag);
							$this->cellStyle("G".$flag, "000000", "FFFFFF", TRUE, 12, "Franklin Gothic Book");
							$hoja->setCellValue("G".$flag, "TOTAL");
							$hoja->setCellValue("I{$flag}", "=CB{$flagf}")->getStyle("I{$flag}")->getNumberFormat()->setFormatCode('"$"#,##0.00_-');
						}
						
						$flag++;
						$flag1++;
						$flag++;
						$flag1++;
						$flag1++;
						$flag1++;
						$flag++;
						$flag++;
					}
				}
			}
			$flag++;
			$bandera = $flag;
			if ($id_proves === "VOLUMEN" || $id_proves === "AMARILLOS"){
				$hoja->mergeCells('G'.$flag.':J'.$flag);
				$this->cellStyle("G".$flag, "000000", "FFFFFF", TRUE, 12, "Franklin Gothic Book");
				$hoja->setCellValue("G".$flag, "TOTALES PENDIENTES '".$provname."'");
				$flag++;
				$hoja->mergeCells('G'.$flag.':H'.$flag);
				$hoja->mergeCells('I'.$flag.':J'.$flag);
				$this->cellStyle("G".$flag, "000000", "FFFFFF", TRUE, 12, "Franklin Gothic Book");
				$hoja->setCellValue("G".$flag, "CEDIS");
				$hoja->setCellValue("I{$flag}", "=BS{$flagf}")->getStyle("I{$flag}")->getNumberFormat()->setFormatCode('"$"#,##0.00_-');
				$flag++;
				$hoja->mergeCells('G'.$flag.':H'.$flag);
				$hoja->mergeCells('I'.$flag.':J'.$flag);
				$this->cellStyle("G".$flag, "000000", "FFFFFF", TRUE, 12, "Franklin Gothic Book");
				$hoja->setCellValue("G".$flag, "ABARROTES");
				$hoja->setCellValue("I{$flag}", "=BT{$flagf}")->getStyle("I{$flag}")->getNumberFormat()->setFormatCode('"$"#,##0.00_-');
				$flag++;
				$hoja->mergeCells('G'.$flag.':H'.$flag);
				$hoja->mergeCells('I'.$flag.':J'.$flag);
				$this->cellStyle("G".$flag, "000000", "FFFFFF", TRUE, 12, "Franklin Gothic Book");
				$hoja->setCellValue("G".$flag, "VILLAS");
				$hoja->setCellValue("I{$flag}", "=BU{$flagf}")->getStyle("I{$flag}")->getNumberFormat()->setFormatCode('"$"#,##0.00_-');
				$flag++;
				$hoja->mergeCells('G'.$flag.':H'.$flag);
				$hoja->mergeCells('I'.$flag.':J'.$flag);
				$this->cellStyle("G".$flag, "000000", "FFFFFF", TRUE, 12, "Franklin Gothic Book");
				$hoja->setCellValue("G".$flag, "TIENDA");
				$hoja->setCellValue("I{$flag}", "=BV{$flagf}")->getStyle("I{$flag}")->getNumberFormat()->setFormatCode('"$"#,##0.00_-');
				$flag++;
				$hoja->mergeCells('G'.$flag.':H'.$flag);
				$hoja->mergeCells('I'.$flag.':J'.$flag);
				$this->cellStyle("G".$flag, "000000", "FFFFFF", TRUE, 12, "Franklin Gothic Book");
				$hoja->setCellValue("G".$flag, "ULTRAMARINOS");
				$hoja->setCellValue("I{$flag}", "=BW{$flagf}")->getStyle("I{$flag}")->getNumberFormat()->setFormatCode('"$"#,##0.00_-');
				$flag++;
				$hoja->mergeCells('G'.$flag.':H'.$flag);
				$hoja->mergeCells('I'.$flag.':J'.$flag);
				$this->cellStyle("G".$flag, "000000", "FFFFFF", TRUE, 12, "Franklin Gothic Book");
				$hoja->setCellValue("G".$flag, "TRINCHERAS");
				$hoja->setCellValue("I{$flag}", "=BX{$flagf}")->getStyle("I{$flag}")->getNumberFormat()->setFormatCode('"$"#,##0.00_-');
				$flag++;
				$hoja->mergeCells('G'.$flag.':H'.$flag);
				$hoja->mergeCells('I'.$flag.':J'.$flag);
				$this->cellStyle("G".$flag, "000000", "FFFFFF", TRUE, 12, "Franklin Gothic Book");
				$hoja->setCellValue("G".$flag, "AZT MERCADO");
				$hoja->setCellValue("I{$flag}", "=BY{$flagf}")->getStyle("I{$flag}")->getNumberFormat()->setFormatCode('"$"#,##0.00_-');
				$flag++;
				$hoja->mergeCells('G'.$flag.':H'.$flag);
				$hoja->mergeCells('I'.$flag.':J'.$flag);
				$this->cellStyle("G".$flag, "000000", "FFFFFF", TRUE, 12, "Franklin Gothic Book");
				$hoja->setCellValue("G".$flag, "TENENCIA");
				$hoja->setCellValue("I{$flag}", "=BZ{$flagf}")->getStyle("I{$flag}")->getNumberFormat()->setFormatCode('"$"#,##0.00_-');
				$flag++;
				$hoja->mergeCells('G'.$flag.':H'.$flag);
				$hoja->mergeCells('I'.$flag.':J'.$flag);
				$this->cellStyle("G".$flag, "000000", "FFFFFF", TRUE, 12, "Franklin Gothic Book");
				$hoja->setCellValue("G".$flag, "TIJERAS");
				$hoja->setCellValue("I{$flag}", "=CA{$flagf}")->getStyle("I{$flag}")->getNumberFormat()->setFormatCode('"$"#,##0.00_-');
				$flag++;
				$hoja->mergeCells('G'.$flag.':H'.$flag);
				$hoja->mergeCells('I'.$flag.':J'.$flag);
				$this->cellStyle("G".$flag, "000000", "FFFFFF", TRUE, 12, "Franklin Gothic Book");
				$hoja->setCellValue("G".$flag, "TOTAL");
				$hoja->setCellValue("I{$flag}", "=CB{$flagf}")->getStyle("I{$flag}")->getNumberFormat()->setFormatCode('"$"#,##0.00_-');
			}
			$flag = $bandera;

			$hoja->mergeCells('B'.$flag.':C'.$flag);
			$this->cellStyle("B".$flag, "000000", "FFFFFF", TRUE, 12, "Franklin Gothic Book");
			$hoja->setCellValue("B".$flag, "TOTALES EN GENERAL");
			$flag++;
			$this->cellStyle("B".$flag, "C00000", "000000", TRUE, 12, "Franklin Gothic Book");
			$hoja->setCellValue("B".$flag, "CEDIS");
			$hoja->setCellValue("C{$flag}", "=(".substr($sumall[1],0,-1).")")->getStyle("C{$flag}")->getNumberFormat()->setFormatCode('"$"#,##0.00_-');
			$flag++;
			$this->cellStyle("B".$flag, "01B0F0", "000000", TRUE, 12, "Franklin Gothic Book");
			$hoja->setCellValue("B".$flag, "ABARROTES");
			$hoja->setCellValue("C{$flag}", "=(".substr($sumall[2],0,-1).")")->getStyle("C{$flag}")->getNumberFormat()->setFormatCode('"$"#,##0.00_-');
			$flag++;
			$this->cellStyle("B".$flag, "FF0000", "000000", TRUE, 12, "Franklin Gothic Book");
			$hoja->setCellValue("B".$flag, "VILLAS");
			$hoja->setCellValue("C{$flag}", "=(".substr($sumall[3],0,-1).")")->getStyle("C{$flag}")->getNumberFormat()->setFormatCode('"$"#,##0.00_-');
			$flag++;
			$this->cellStyle("B".$flag, "E26C0B", "000000", TRUE, 12, "Franklin Gothic Book");
			$hoja->setCellValue("B".$flag, "TIENDA");
			$hoja->setCellValue("C{$flag}", "=(".substr($sumall[4],0,-1).")")->getStyle("C{$flag}")->getNumberFormat()->setFormatCode('"$"#,##0.00_-');
			$flag++;
			$this->cellStyle("B".$flag, "C5C5C5", "000000", TRUE, 12, "Franklin Gothic Book");
			$hoja->setCellValue("B".$flag, "ULTRAMARINOS");
			$hoja->setCellValue("C{$flag}", "=(".substr($sumall[5],0,-1).")")->getStyle("C{$flag}")->getNumberFormat()->setFormatCode('"$"#,##0.00_-');
			$flag++;
			$this->cellStyle("B".$flag, "92D051", "000000", TRUE, 12, "Franklin Gothic Book");
			$hoja->setCellValue("B".$flag, "TRINCHERAS");
			$hoja->setCellValue("C{$flag}", "=(".substr($sumall[6],0,-1).")")->getStyle("C{$flag}")->getNumberFormat()->setFormatCode('"$"#,##0.00_-');
			$flag++;
			$this->cellStyle("B".$flag, "B1A0C7", "000000", TRUE, 12, "Franklin Gothic Book");
			$hoja->setCellValue("B".$flag, "AZT MERCADO");
			$hoja->setCellValue("C{$flag}", "=(".substr($sumall[7],0,-1).")")->getStyle("C{$flag}")->getNumberFormat()->setFormatCode('"$"#,##0.00_-');
			$flag++;
			$this->cellStyle("B".$flag, "DA9694", "000000", TRUE, 12, "Franklin Gothic Book");
			$hoja->setCellValue("B".$flag, "TENENCIA");
			$hoja->setCellValue("C{$flag}", "=(".substr($sumall[8],0,-1).")")->getStyle("C{$flag}")->getNumberFormat()->setFormatCode('"$"#,##0.00_-');
			$flag++;
			$this->cellStyle("B".$flag, "4CACC6", "000000", TRUE, 12, "Franklin Gothic Book");
			$hoja->setCellValue("B".$flag, "TIJERAS");
			$hoja->setCellValue("C{$flag}", "=(".substr($sumall[9],0,-1).")")->getStyle("C{$flag}")->getNumberFormat()->setFormatCode('"$"#,##0.00_-');
			$flag++;
			$this->cellStyle("B".$flag, "000000", "FFFFFF", TRUE, 12, "Franklin Gothic Book");
			$hoja->setCellValue("B".$flag, "TOTAL");
			$hoja->setCellValue("C{$flag}", "=(".substr($sumall[10],0,-1).")")->getStyle("C{$flag}")->getNumberFormat()->setFormatCode('"$"#,##0.00_-');
			$flag++;

			$dias = array("DOMINGO","LUNES","MARTES","MIÉRCOLES","JUEVES","VIERNES","SÁBADO");
			$meses = array("ENERO","FEBRERO","MARZO","ABRIL","MAYO","JUNIO","JULIO","AGOSTO","SEPTIEMBRE","OCTUBRE","NOVIEMBRE","DICIEMBRE");
			$fecha =  $dias[date('w')]." ".date('d')." DE ".$meses[date('n')-1]. " DEL ".date('Y') ;
			$file_name = "FORMATO ".$filenam." ".$fecha.".xlsx"; //Nombre del documento con extención
			$excel_Writer = PHPExcel_IOFactory::createWriter($this->excelfile, "Excel2007");
			header("Content-Type: application/vnd.ms-excel; charset=utf-8");
			header("Content-Disposition: attachment;filename=".$file_name);
			header("Cache-Control: max-age=0");
			$excel_Writer->save("php://output");
			/*$excel_Writer = new PHPExcel_Writer_Excel2007($this->excelfile);
			$excel_Writer->setOffice2003Compatibility(true);
			$excel_Writer->save("php://output");*/
		}elseif ($id_proves === "6" || $id_proves === 6) {
			$cambios = [
				"id_usuario" => $user["id_usuario"],
				"fecha_cambio" => date('Y-m-d H:i:s'),
				"antes" => "Descarga formato",
				"despues" => "19 hermanos ",
				"estatus" => "3",
			];
			$data['cambios'] = $this->cambio_md->insert($cambios);
			$this->fill_hermanos();
		}elseif ($id_proves === "4" || $id_proves === 4) {
			$cambios = [
				"id_usuario" => $user["id_usuario"],
				"fecha_cambio" => date('Y-m-d H:i:s'),
				"antes" => "Descarga formato",
				"despues" => "SAHUAYO",
				"estatus" => "3",
			];
			$data['cambios'] = $this->cambio_md->insert($cambios);
			$this->fill_duerazo("SAHUAYO");
		}else{
			$cambios = [
				"id_usuario" => $user["id_usuario"],
				"fecha_cambio" => date('Y-m-d H:i:s'),
				"antes" => "Descarga formato",
				"despues" => "DUERO",
				"estatus" => "3",
			];
			$data['cambios'] = $this->cambio_md->insert($cambios);
			$this->fill_duerazo("DUERO");
		}
	}
	public function archivo_precios(){
		ini_set("memory_limit", "-1");
		$this->load->library("excelfile");
		$hoja = $this->excelfile->getActiveSheet(0);

		
		$this->excelfile->setActiveSheetIndex(0)->setTitle("PRECIOS");
		$this->excelfile->createSheet();
		$hoja2 = $this->excelfile->setActiveSheetIndex(1);
		$hoja2->setTitle("TXT");

		$this->excelfile->setActiveSheetIndex(0);

		$hoja->getDefaultStyle()
		    ->getBorders()
		    ->getTop()
		        ->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
		$hoja->getDefaultStyle()
		    ->getBorders()
		    ->getBottom()
		        ->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
		$hoja->getDefaultStyle()
		    ->getBorders()
		    ->getLeft()
		        ->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
		$hoja->getDefaultStyle()
		    ->getBorders()
		    ->getRight()
		        ->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
		$this->cellStyle("A1:G2", "000000", "FFFFFF", TRUE, 12, "Franklin Gothic Book");
		$border_style= array('borders' => array('right' => array('style' =>
			PHPExcel_Style_Border::BORDER_THIN,'color' => array('argb' => '000000'),)));
		$hoja->setCellValue("B1", "DESCRIPCIÓN")->getColumnDimension('B')->setWidth(70);
		$hoja->setCellValue("C1", "SISTEMA")->getColumnDimension('C')->setWidth(15);
		$hoja->setCellValue("D1", "PRECIO 4")->getColumnDimension('D')->setWidth(15);
		$hoja->setCellValue("A2", "CÓDIGO")->getColumnDimension('A')->setWidth(30); //Nombre y ajuste de texto a la columna

		$hoja->setCellValue("E1", "UNIDAD")->getColumnDimension('E')->setWidth(15);
		$hoja->setCellValue("E2", "MEDIDA");
		$hoja->setCellValue("F1", "SISTEMA")->getColumnDimension('F')->setWidth(15);
		$hoja->setCellValue("F2", "ANTERIOR");
		$hoja->setCellValue("G1", "PRECIO 4")->getColumnDimension('G')->setWidth(15);
		$hoja->setCellValue("G2", "ANTERIOR");



		$productos = $this->prod_mdl->getProdFamS(NULL);
		$row_print = 2;
		if ($productos){
			foreach ($productos as $key => $value){
				$hoja->setCellValue("B{$row_print}", $value['familia']);
				$this->cellStyle("B{$row_print}", "000000", "FFFFFF", TRUE, 12, "Franklin Gothic Book");
				$hoja->setCellValue("B{$row_print}", $value['familia']);
				$row_print +=1;
				if ($value['articulos']) {
					foreach ($value['articulos'] as $key => $row){
						if($row['color'] == '#92CEE3'){
							$this->cellStyle("A{$row_print}", "92CEE3", "000000", FALSE, 10, "Franklin Gothic Book");
						}else{
							$this->cellStyle("A{$row_print}", "FFFFFF", "000000", FALSE, 10, "Franklin Gothic Book");
						}
						$hoja->setCellValue("A{$row_print}", $row['codigo'])->getStyle("A{$row_print}")->getNumberFormat()->setFormatCode('# ???/???');//Formato de fraccion
						$hoja->getStyle("A{$row_print}")->applyFromArray($border_style);
						$hoja->setCellValue("B{$row_print}", $row['producto']);

						$hoja->setCellValue("E{$row_print}", $row['unidad']);
						$hoja->setCellValue("F{$row_print}", $row['precio_sistema']);
						$hoja->setCellValue("G{$row_print}", $row['precio_four']);


						$hoja->setCellValue("C{$row_print}", '=IFERROR(VLOOKUP(A'.$row_print.',TXT!A:K,9,FALSE),0)*E'.$row_print);
						$hoja->setCellValue("D{$row_print}", '=IFERROR(VLOOKUP(A'.$row_print.',TXT!A:K,8,FALSE),0)*E'.$row_print);

						if($row['estatus'] == 2){
							$this->cellStyle("B{$row_print}", "00B0F0", "000000", FALSE, 10, "Franklin Gothic Book");
						}
						if($row['estatus'] == 3){
							$this->cellStyle("B{$row_print}", "FFF900", "000000", FALSE, 10, "Franklin Gothic Book");
						}
						if($row['estatus'] >= 4){
							$this->cellStyle("B{$row_print}", "04B486", "000000", FALSE, 10, "Franklin Gothic Book");
						}
						$hoja->getStyle("B{$row_print}")->applyFromArray($border_style);
						if($row['colorp'] == 1){
							$this->cellStyle("C{$row_print}", "D6DCE4", "000000", FALSE, 10, "Franklin Gothic Book");
						}else{
							$this->cellStyle("C{$row_print}", "FFFFFF", "000000", FALSE, 10, "Franklin Gothic Book");
						}
						$hoja->getStyle("C{$row_print}")->applyFromArray($border_style);
						$hoja->getStyle("D{$row_print}")->applyFromArray($border_style);
						$hoja->getStyle("E{$row_print}")->applyFromArray($border_style);
						$hoja->getStyle("F{$row_print}")->applyFromArray($border_style);
						$hoja->getStyle("G{$row_print}")->applyFromArray($border_style);
						if($this->weekNumber($row['fecha_registro']) >= ($this->weekNumber() - 1)  && date("Y") == substr($row['fecha_registro'],4)){
							$this->cellStyle("A{$row_print}", "FF7F71", "000000", FALSE, 10, "Franklin Gothic Book");
							$this->cellStyle("B{$row_print}", "FF7F71", "000000", FALSE, 10, "Franklin Gothic Book");
							$this->cellStyle("C{$row_print}", "FF7F71", "000000", TRUE, 10, "Franklin Gothic Book");
							$this->cellStyle("D{$row_print}", "FF7F71", "000000", FALSE, 10, "Franklin Gothic Book");
							$hoja->setCellValue("E{$row_print}", "NUEVO");
						}
						$row_print++;
					}
				}
			}
		}
		$hoja->getStyle("A3:G{$row_print}")
                 ->getAlignment()
                 ->setHorizontal(\PHPExcel_Style_Alignment::HORIZONTAL_LEFT);
		$hoja->getStyle("B3:B{$row_print}")
                 ->getAlignment()
                 ->setHorizontal(\PHPExcel_Style_Alignment::HORIZONTAL_LEFT);
		$file_name = "Formato Precios.xlsx"; //Nombre del documento con extención
		header("Content-Type: application/vnd.ms-excel; charset=utf-8");
		header("Content-Disposition: attachment;filename=".$file_name);
		header("Cache-Control: max-age=0");
		$excel_Writer = PHPExcel_IOFactory::createWriter($this->excelfile, "Excel2007");
		$excel_Writer->save("php://output");
	}
	public function archivo_cotizacion(){
		ini_set("memory_limit", "-1");
		$this->load->library("excelfile");
		$hoja = $this->excelfile->getActiveSheet();
		$hoja->getDefaultStyle()
		    ->getBorders()
		    ->getTop()
		        ->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
		$hoja->getDefaultStyle()
		    ->getBorders()
		    ->getBottom()
		        ->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
		$hoja->getDefaultStyle()
		    ->getBorders()
		    ->getLeft()
		        ->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
		$hoja->getDefaultStyle()
		    ->getBorders()
		    ->getRight()
		        ->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
		$this->cellStyle("A1:G2", "000000", "FFFFFF", TRUE, 12, "Franklin Gothic Book");
		$border_style= array('borders' => array('right' => array('style' =>
			PHPExcel_Style_Border::BORDER_THIN,'color' => array('argb' => '000000'),)));
		$hoja->setCellValue("B1", "DESCRIPCIÓN SISTEMA")->getColumnDimension('B')->setWidth(70);
		$hoja->setCellValue("C1", "PRECIO")->getColumnDimension('C')->setWidth(15);
		$hoja->setCellValue("D1", "PROMOCIÓN")->getColumnDimension('D')->setWidth(50);
		$hoja->setCellValue("E1", "# EN #")->getColumnDimension('E')->setWidth(12);
		$hoja->setCellValue("F1", "# EN #")->getColumnDimension('F')->setWidth(12);
		$hoja->setCellValue("G1", "% DESCUENTO")->getColumnDimension('G')->setWidth(15);
		$hoja->setCellValue("A2", "CÓDIGO")->getColumnDimension('A')->setWidth(30); //Nombre y ajuste de texto a la columna
		$hoja->mergeCells('E1:F1');
		$productos = $this->prod_mdl->getProdFamS(NULL);
		$row_print = 2;
		if ($productos){
			foreach ($productos as $key => $value){
				$hoja->setCellValue("B{$row_print}", $value['familia']);
				$this->cellStyle("B{$row_print}", "000000", "FFFFFF", TRUE, 12, "Franklin Gothic Book");
				$hoja->setCellValue("B{$row_print}", $value['familia']);
				$row_print +=1;
				if ($value['articulos']) {
					foreach ($value['articulos'] as $key => $row){
						if($row['color'] == '#92CEE3'){
							$this->cellStyle("A{$row_print}", "92CEE3", "000000", FALSE, 10, "Franklin Gothic Book");
						}else{
							$this->cellStyle("A{$row_print}", "FFFFFF", "000000", FALSE, 10, "Franklin Gothic Book");
						}
						$hoja->setCellValue("A{$row_print}", $row['codigo'])->getStyle("A{$row_print}")->getNumberFormat()->setFormatCode('# ???/???');//Formato de fraccion
						$hoja->getStyle("A{$row_print}")->applyFromArray($border_style);
						$hoja->setCellValue("B{$row_print}", $row['producto']);
						if($row['estatus'] == 2){
							$this->cellStyle("B{$row_print}", "00B0F0", "000000", FALSE, 10, "Franklin Gothic Book");
						}
						if($row['estatus'] == 3){
							$this->cellStyle("B{$row_print}", "FFF900", "000000", FALSE, 10, "Franklin Gothic Book");
						}
						if($row['estatus'] >= 4){
							$this->cellStyle("B{$row_print}", "04B486", "000000", FALSE, 10, "Franklin Gothic Book");
						}
						$hoja->getStyle("B{$row_print}")->applyFromArray($border_style);
						if($row['colorp'] == 1){
							$this->cellStyle("C{$row_print}", "D6DCE4", "000000", FALSE, 10, "Franklin Gothic Book");
						}else{
							$this->cellStyle("C{$row_print}", "FFFFFF", "000000", FALSE, 10, "Franklin Gothic Book");
						}
						$hoja->getStyle("C{$row_print}")->applyFromArray($border_style);
						$hoja->getStyle("D{$row_print}")->applyFromArray($border_style);
						$hoja->getStyle("E{$row_print}")->applyFromArray($border_style);
						$hoja->getStyle("F{$row_print}")->applyFromArray($border_style);
						$hoja->getStyle("G{$row_print}")->applyFromArray($border_style);
						if($this->weekNumber($row['fecha_registro']) >= ($this->weekNumber() - 1)  && date("Y") == substr($row['fecha_registro'],4)){
							$this->cellStyle("A{$row_print}", "FF7F71", "000000", FALSE, 10, "Franklin Gothic Book");
							$this->cellStyle("B{$row_print}", "FF7F71", "000000", FALSE, 10, "Franklin Gothic Book");
							$this->cellStyle("C{$row_print}", "FF7F71", "000000", TRUE, 10, "Franklin Gothic Book");
							$this->cellStyle("D{$row_print}", "FF7F71", "000000", FALSE, 10, "Franklin Gothic Book");
							$this->cellStyle("E{$row_print}", "FF7F71", "000000", FALSE, 10, "Franklin Gothic Book");
							$this->cellStyle("G{$row_print}", "FF7F71", "000000", TRUE, 10, "Franklin Gothic Book");
							$this->cellStyle("F{$row_print}", "FF7F71", "000000", FALSE, 10, "Franklin Gothic Book");
							$hoja->setCellValue("H{$row_print}", "NUEVO");
						}
						$row_print++;
					}
				}
			}
		}
		$hoja->getStyle("A3:H{$row_print}")
                 ->getAlignment()
                 ->setHorizontal(\PHPExcel_Style_Alignment::HORIZONTAL_LEFT);
		$hoja->getStyle("B3:B{$row_print}")
                 ->getAlignment()
                 ->setHorizontal(\PHPExcel_Style_Alignment::HORIZONTAL_LEFT);
        $user = $this->session->userdata();
        $provs = $this->usua_mdl->get(NULL, ['id_usuario'=>$user['id_usuario']])[0];
		$file_name = "Formato Cotizaciones ".$provs->nombre.".xlsx"; //Nombre del documento con extención
		header("Content-Type: application/vnd.ms-excel; charset=utf-8");
		header("Content-Disposition: attachment;filename=".$file_name);
		header("Cache-Control: max-age=0");
		$excel_Writer = PHPExcel_IOFactory::createWriter($this->excelfile, "Excel2007");
		$excel_Writer->save("php://output");
	}
	public function registro_fltnts(){
		$user = $this->session->userdata();
		$size = sizeof($this->input->post('id_producto[]'));
		$cotz = $this->input->post('id_producto[]');
		$no_semanas = $this->input->post('no_semanas[]');
		for($i = 0; $i < $size; $i++){
			if($no_semanas[$i] <> "" && $no_semanas[$i] <> NULL){
				$fecha = new DateTime(date('Y-m-d H:i:s'));
				$intervalo = new DateInterval('P'.$no_semanas[$i].'W');
				$fecha->add($intervalo);
				$fecha->format('Y-m-d H:i:s');
				$antes =  $this->falt_mdl->get(NULL, ['id_producto' => $cotz[$i], 'fecha_termino > ' => date("Y-m-d H:i:s"), 'id_proveedor' => $this->input->post('id_pro')])[0];
				if($antes){
					$aprod = $this->prod_mdl->get(NULL, ['id_producto'=>$antes->id_producto])[0];
					$aprov = $this->usua_mdl->get(NULL, ['id_usuario'=>$antes->id_proveedor])[0];
					$cambios = [
						"id_usuario" => $user["id_usuario"],
						"fecha_cambio" => date('Y-m-d H:i:s'),
						"accion" => "Actualizo faltantes",
						"antes" => "id_faltante: ".$antes->id_faltante." /Proveedor: ".$aprov->nombre." /Producto:".$aprod->nombre."/F Termino: ".$antes->fecha_termino.
								"/Semanas: ".$antes->no_semanas,
						"despues" => "El usuario cambio las semanas: \n/Semanas: ".$no_semanas[$i]."/Fecha Termino:".$fecha->format('Y-m-d H:i:s')];
					$data['cambios'] = $this->cambio_md->insert($cambios);
					$data ['id_faltante'] = $this->falt_mdl->update([
						"no_semanas" => $no_semanas[$i],
						"fecha_termino" => $fecha->format('Y-m-d H:i:s')
					], $antes->id_faltante);
					$mensaje = [
						"id" 	=> 'Éxito',
						"desc"	=> 'Faltantes actualizados correctamente',
						"type"	=> 'success'
					];
				}else{
					$aprod = $this->prod_mdl->get(NULL, ['id_producto'=>$cotz[$i] ] )[0];
					$aprov = $this->usua_mdl->get(NULL, ['id_usuario'=>$this->input->post('id_pro')])[0];
						$cambios = [
						"id_usuario" => $user["id_usuario"],
						"fecha_cambio" => date('Y-m-d H:i:s'),
						"accion" => "Inserto faltantes",
						"antes" => "Proveedor: ".$aprov->nombre." /Producto:".$aprod->nombre."/F Termino: ".$fecha->format('Y-m-d H:i:s').
								"/Semanas: ".$no_semanas[$i],
						"despues" => "El usuario agrego faltantes."];
					$data['cambios'] = $this->cambio_md->insert($cambios);
					$new_faltante=[
						"id_producto"		=>	$cotz[$i],
						"fecha_termino"	=>	$fecha->format('Y-m-d H:i:s'),
						"no_semanas"		=>	$no_semanas[$i],
						"id_proveedor" => $this->input->post('id_pro')
					];
					$data ['id_faltante'] = $this->falt_mdl->insert($new_faltante);
					$mensaje = [
						"id" 	=> 'Éxito',
						"desc"	=> 'Faltantes insertados correctamente',
						"type"	=> 'success'
					];
				}
			}
		}
		$this->jsonResponse($mensaje);
	}
	public function fill_excel_bajos(){
		ini_set("memory_limit", "-1");
		$this->load->library("excelfile");
		$hoja = $this->excelfile->getActiveSheet();
		$hoja->getDefaultStyle()
		    ->getBorders()
		    ->getTop()
		        ->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
		$hoja->getDefaultStyle()
		    ->getBorders()
		    ->getBottom()
		        ->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
		$hoja->getDefaultStyle()
		    ->getBorders()
		    ->getLeft()
		        ->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
		$hoja->getDefaultStyle()
		    ->getBorders()
		    ->getRight()
		        ->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
		$this->cellStyle("A1:K2", "000000", "FFFFFF", TRUE, 12, "Franklin Gothic Book");
		$border_style= array('borders' => array('right' => array('style' =>
			PHPExcel_Style_Border::BORDER_THIN,'color' => array('argb' => '000000'),)));
		$hoja->setCellValue("B1", "DESCRIPCIÓN")->getColumnDimension('B')->setWidth(70);
		$hoja->setCellValue("C1", "PRECIO")->getColumnDimension('C')->setWidth(15);
		$hoja->setCellValue("D1", "PRECIO PROMOCIÓN")->getColumnDimension('D')->setWidth(15);
		$hoja->setCellValue("E1", "OBSERVACIONES")->getColumnDimension('E')->setWidth(22);
		$hoja->setCellValue("F1", "SISTEMA")->getColumnDimension('F')->setWidth(12);
		$hoja->setCellValue("G1", "PRECIO 4")->getColumnDimension('G')->setWidth(15);
		$hoja->setCellValue("H1", "DIFERENCIA")->getColumnDimension('H')->setWidth(15);
		$hoja->setCellValue("I1", "PROVEEDOR")->getColumnDimension('I')->setWidth(22);
		$hoja->setCellValue("J1", "PRECIO")->getColumnDimension('J')->setWidth(12);
		$hoja->setCellValue("K1", "OBSERVACIONES")->getColumnDimension('K')->setWidth(22);
		$hoja->setCellValue("A2", "CÓDIGO")->getColumnDimension('A')->setWidth(30); //Nombre y ajuste de texto a la columna
		$hoja->mergeCells('B2:F2');
		$fecha = new DateTime(date('Y-m-d H:i:s'));
		$intervalo = new DateInterval('P2D');
		$fecha->add($intervalo);
		$productos =  $this->ct_mdl->getProveedorBajos(NULL,$fecha->format('Y-m-d H:i:s'),$this->input->post('id_pro'));
		$provs = $this->usua_mdl->get(NULL, ['id_usuario'=>$this->input->post('id_pro')])[0];
		$hoja->setCellValue("B2", "COMPARACIÓN DE PRECIOS ".$provs->nombre)->getColumnDimension('B')->setWidth(70);
		$row_print = 3;
		if ($productos){
			foreach ($productos as $key => $value){
			if($value->color === '#92CEE3'){
				$this->cellStyle("A{$row_print}", "92CEE3", "000000", FALSE, 10, "Franklin Gothic Book");
			}else{
				$this->cellStyle("A{$row_print}", "FFFFFF", "000000", FALSE, 10, "Franklin Gothic Book");
			}
			$hoja->setCellValue("A{$row_print}", $value->codigo)->getStyle("A{$row_print}")->getNumberFormat()->setFormatCode('# ???/???');//Formato de fraccion
			$hoja->getStyle("A{$row_print}")->applyFromArray($border_style);
			$hoja->setCellValue("B{$row_print}", $value->producto);
			if($value->estatus == 2){
				$this->cellStyle("B{$row_print}", "00B0F0", "000000", FALSE, 10, "Franklin Gothic Book");
			}
			if($value->estatus == 3){
				$this->cellStyle("B{$row_print}", "FFF900", "000000", FALSE, 10, "Franklin Gothic Book");
			}
			if($value->estatus == 4){
				$this->cellStyle("B{$row_print}", "04B486", "000000", FALSE, 10, "Franklin Gothic Book");
			}
			$hoja->getStyle("B{$row_print}")->applyFromArray($border_style);
			if($value->colorp == 1){
				$this->cellStyle("C{$row_print}", "D6DCE4", "000000", FALSE, 10, "Franklin Gothic Book");
			}else{
				$this->cellStyle("C{$row_print}", "FFFFFF", "000000", FALSE, 10, "Franklin Gothic Book");
			}
			$hoja->setCellValue("C{$row_print}", $value->proves_precio)->getStyle("C{$row_print}")->getNumberFormat()->setFormatCode('"$"#,##0.00_-');
			$hoja->getStyle("C{$row_print}")->applyFromArray($border_style);
			$hoja->setCellValue("D{$row_print}", $value->proves_promo)->getStyle("D{$row_print}")->getNumberFormat()->setFormatCode('"$"#,##0.00_-');
			$hoja->getStyle("D{$row_print}")->applyFromArray($border_style);
			$hoja->setCellValue("E{$row_print}", $value->proves_obs);
			$hoja->getStyle("E{$row_print}")->applyFromArray($border_style);
			$hoja->setCellValue("F{$row_print}", $value->precio_sistema)->getStyle("F{$row_print}")->getNumberFormat()->setFormatCode('"$"#,##0.00_-');
			$hoja->getStyle("F{$row_print}")->applyFromArray($border_style);
			$hoja->setCellValue("G{$row_print}", $value->precio_four)->getStyle("G{$row_print}")->getNumberFormat()->setFormatCode('"$"#,##0.00_-');
			$hoja->getStyle("G{$row_print}")->applyFromArray($border_style);
			$diffes = $value->proves_promo - $value->precio_first;
			$hoja->setCellValue("H{$row_print}", $diffes)->getStyle("H{$row_print}")->getNumberFormat()->setFormatCode('"$"#,##0.00_-');
			$hoja->getStyle("H{$row_print}")->applyFromArray($border_style);
			$hoja->setCellValue("I{$row_print}", $value->proveedor_first);
			$hoja->getStyle("I{$row_print}")->applyFromArray($border_style);
			$hoja->setCellValue("J{$row_print}", $value->precio_first)->getStyle("J{$row_print}")->getNumberFormat()->setFormatCode('"$"#,##0.00_-');
			$hoja->getStyle("J{$row_print}")->applyFromArray($border_style);
			$hoja->setCellValue("K{$row_print}", $value->observaciones_first);
			$hoja->getStyle("K{$row_print}")->applyFromArray($border_style);
			if($value->proves === $value->proveedor_first){
				$this->cellStyle("H{$row_print}", "FF0066", "000000", FALSE, 10, "Franklin Gothic Book");
				$hoja->setCellValue("H{$row_print}", $diffes);
				$hoja->setCellValue("I{$row_print}", $value->proveedor_next);
				$hoja->setCellValue("J{$row_print}", $value->precio_next);
				$hoja->setCellValue("K{$row_print}", $value->promocion_next);
			}elseif($value->proves_promo >= $value->precio_first){
				$this->cellStyle("H{$row_print}", "FFE6F0", "000000", FALSE, 10, "Franklin Gothic Book");
			}
			$row_print++;
			}
		}
		$hoja->getStyle("A3:K{$row_print}")
                 ->getAlignment()
                 ->setHorizontal(\PHPExcel_Style_Alignment::HORIZONTAL_LEFT);
		$file_name = "Comparación ".$provs->nombre.".xlsx"; //Nombre del documento con extención
		header("Content-Type: application/vnd.ms-excel; charset=utf-8");
		header("Content-Disposition: attachment;filename=".$file_name);
		header("Cache-Control: max-age=0");
		$excel_Writer = PHPExcel_IOFactory::createWriter($this->excelfile, "Excel2007");
		$excel_Writer->save("php://output");
	}
	public function upload_faltantes($idpro = NULL){
		$user = $this->session->userdata();
		$config['upload_path']          = './assets/uploads/faltantes/';
        $config['allowed_types']        = 'xlsx|xls';
        $config['max_size']             = 10000;
        $config['max_width']            = 10024;
        $config['max_height']           = 7068;
	    $user = $this->session->userdata();
        $this->load->library('upload', $config);
        $this->upload->do_upload('file_faltantes');
		$this->load->library("excelfile");
		ini_set("memory_limit", "-1");
		ini_set("max_execution_time", "-1");
		$file = $_FILES["file_faltantes"]["tmp_name"];
		$objExcel = PHPExcel_IOFactory::load($file);
		$sheet = $objExcel->getSheet(0);
		$num_rows = $sheet->getHighestDataRow();
		$mensaje = [
			"id" 	=> 'Error',
			"desc"	=> 'No se pudo subir el archivo',
			"type"	=> 'error'
		];
		for ($i=1; $i<=$num_rows; $i++) {
			if($sheet->getCell('B'.$i)->getValue() !=''){
				$productos = $this->prod_mdl->get("id_producto",['codigo'=> htmlspecialchars($sheet->getCell('A'.$i)->getValue(), ENT_QUOTES, 'UTF-8')])[0];
				if (sizeof($productos) > 0) {
					$no_semanas = $sheet->getCell('C'.$i)->getValue() == '' ? 1 : $sheet->getCell('C'.$i)->getValue();
					$fecha = new DateTime(date('Y-m-d H:i:s'));
					$intervalo = new DateInterval('P'.$no_semanas.'W');
					$fecha->add($intervalo);
					$fecha->format('Y-m-d H:i:s');
					$antes =  $this->falt_mdl->get(NULL, ['id_producto' => $productos->id_producto, 'fecha_termino > ' => date("Y-m-d H:i:s"), 'id_proveedor' => $idpro])[0];
					if($antes){
						$aprod = $this->prod_mdl->get(NULL, ['id_producto'=>$antes->id_producto])[0];
						$aprov = $this->usua_mdl->get(NULL, ['id_usuario'=>$antes->id_proveedor])[0];
						$cambios = [
							"id_usuario" => $user["id_usuario"],
							"fecha_cambio" => date('Y-m-d H:i:s'),
							"accion" => "Actualizo faltantes",
							"antes" => "id_faltante: ".$antes->id_faltante." /Proveedor: ".$aprov->nombre." /Producto:".$aprod->nombre."/F Termino: ".$antes->fecha_termino.
									"/Semanas: ".$no_semanas." ",
							"despues" => "El usuario cambio las semanas: \n/Semanas: ".$no_semanas."/Fecha Termino:".$fecha->format('Y-m-d H:i:s')];
						$data['cambios'] = $this->cambio_md->insert($cambios);
						$data ['id_faltante'] = $this->falt_mdl->update([
							"no_semanas" => $no_semanas,
							"fecha_termino" => $fecha->format('Y-m-d H:i:s')
						], $antes->id_faltante);
						$mensaje = [
							"id" 	=> 'Éxito',
							"desc"	=> 'Faltantes actualizados correctamente',
							"type"	=> 'success'
						];
					}else{
						$aprod = $this->prod_mdl->get(NULL, ['id_producto'=>$productos->id_producto ] )[0];
						$aprov = $this->usua_mdl->get(NULL, ['id_usuario'=>$idpro])[0];
							$cambios = [
							"id_usuario" => $user["id_usuario"],
							"fecha_cambio" => date('Y-m-d H:i:s'),
							"accion" => "Inserto faltantes",
							"antes" => "Proveedor: ".$aprov->nombre." /Producto:".$aprod->nombre."/F Termino: ".$fecha->format('Y-m-d H:i:s').
									"/Semanas: ".$no_semanas."",
							"despues" => "El usuario agrego faltantes."];
						$data['cambios'] = $this->cambio_md->insert($cambios);
						$new_faltante=[
							"id_producto"		=>	$productos->id_producto,
							"fecha_termino"	=>	$fecha->format('Y-m-d H:i:s'),
							"no_semanas"		=>	$no_semanas,
							"id_proveedor" => $idpro
						];
						$data ['id_faltante'] = $this->falt_mdl->insert($new_faltante);
						$mensaje = [
							"id" 	=> 'Éxito',
							"desc"	=> 'Faltantes insertados correctamente',
							"type"	=> 'success'
						];
					}
				}
			}
		}
		$this->jsonResponse($mensaje);
	}
	public function add_faltante($id_prove){
		$data["usuario"] = $this->user_md->get(NULL,["id_usuario" => $id_prove])[0];
		$data["title"]="AGREGAR FALTANTE A ".$data["usuario"]->nombre;
		$data["productos"] = $this->prod_mdl->get("id_producto, nombre");
		$data["view"]=$this->load->view("Cotizaciones/falta_add", $data, TRUE);
		$data["button"]="<button class='btn btn-success new_falta' type='button'>
											<span class='bold'><i class='fa fa-floppy-o'></i></span> &nbsp;Agregar Faltante
										</button>";
		$this->jsonResponse($data);
	}
	public function save_falta(){
		$user = $this->session->userdata();
		$antes =  $this->falt_mdl->get(NULL, ['id_producto' => $this->input->post('id_producto'), 'fecha_termino > ' => date("Y-m-d H:i:s"), 'id_proveedor' => $this->input->post('id_proveedor')])[0];
		$fecha = new DateTime(date('Y-m-d H:i:s'));
		$intervalo = new DateInterval('P'.$this->input->post('semanas').'W');
		$fecha->add($intervalo);
		$aprod = $this->prod_mdl->get(NULL, ['id_producto'=>$this->input->post('id_producto')])[0];
		$aprov = $this->usua_mdl->get(NULL, ['id_usuario'=>$this->input->post('id_proveedor')])[0];
		if($antes){
			$cambios = [
						"id_usuario" => $user["id_usuario"],
						"fecha_cambio" => date('Y-m-d H:i:s'),
						"accion" => "Actualizo faltantes",
						"antes" => "id_faltante: ".$antes->id_faltante." /Proveedor: ".$aprov->nombre." /Producto:".$aprod->nombre."/F Termino: ".$antes->fecha_termino.
								"/Semanas: ".$antes->no_semanas." ",
						"despues" => "El usuario cambio las semanas: \n/Semanas: ".$this->input->post('semanas')."/Fecha Termino:".$fecha->format('Y-m-d H:i:s')];
			$data ['id_faltante'] = $this->falt_mdl->update([
				"no_semanas" => $this->input->post('semanas'),
				"fecha_termino" => $fecha->format('Y-m-d H:i:s')
			], $antes->id_faltante);
			$data['cambios'] = $this->cambio_md->insert($cambios);
		}else{
			$faltante = [
				'id_producto'		=>	$this->input->post('id_producto'),
				'id_proveedor'		=>	$this->input->post('id_proveedor'),
				'fecha_termino'	=>	$fecha->format('Y-m-d H:i:s'),
				'no_semanas' => $this->input->post('semanas')
			];
				$data ['id_faltante'] = $this->falt_mdl->insert($faltante);
				$cambios = [
				"id_usuario" => $user["id_usuario"],
				"fecha_cambio" => date('Y-m-d H:i:s'),
				"accion" => "Inserto faltantes",
				"antes" => "Proveedor: ".$aprov->nombre." /Producto:".$aprod->nombre."/F Termino: ".$fecha->format('Y-m-d H:i:s').
						"/Semanas: ".$this->input->post('semanas')."",
				"despues" => "El usuario agrego faltantes."];
			$data['cambios'] = $this->cambio_md->insert($cambios);
		}
		$mensaje = [
			"id" 	=> 'Éxito',
			"desc"	=> 'Cotización registrada correctamente',
			"type"	=> 'success'
		];
		$this->jsonResponse($mensaje);
	}
	public function delete_falta(){
		$user = $this->session->userdata();
		$val = json_decode($this->input->post('values'), true);
		$antes =  $this->falt_mdl->get(NULL, ['fecha_termino > ' => date("Y-m-d H:i:s"), 'id_proveedor' => $val['id_proveedor']]);
		$fecha = new DateTime(date('Y-m-d H:i:s'));
		$intervalo = new DateInterval('P2D');
		$fecha->sub($intervalo);
		$aprov = $this->usua_mdl->get(NULL, ['id_usuario'=> $val['id_proveedor']])[0];
		if($antes){
			foreach ($antes as $key => $value) {
				$data ['id_faltante'] = $this->falt_mdl->update(["no_semanas" => 0,"fecha_termino" => $fecha->format('Y-m-d H:i:s')], $value->id_faltante);
			}
		}
		$mensaje = [
			"id" 	=> 'Éxito',
			"desc"	=> 'Faltantes eliminados correctamente',
			"type"	=> 'success'
		];
		$cambios = [
				"id_usuario" => $user["id_usuario"],
				"fecha_cambio" => date('Y-m-d H:i:s'),
				"accion" => "Elimina faltantes",
				"antes" => "El usuario elimina los faltantes ",
				"despues" => ""];
		$data['cambios'] = $this->cambio_md->insert($cambios);
		$this->jsonResponse($mensaje);
	}
	public function upload_expos($idesp){
		$fecha = new DateTime(date('Y-m-d H:i:s'));
		$intervalo = new DateInterval('P2D');
		$fecha->add($intervalo);
		if($idesp == 0){
			$proveedor = $this->session->userdata('id_usuario');
		}else{
			$proveedor = $idesp;
		}
		$cfile =  $this->usua_mdl->get(NULL, ['id_usuario' => $proveedor])[0];
		$nams = preg_replace('/\s+/', '_', $cfile->nombre);
		$filen = "Cotizacion".$nams."".rand();
		$config['upload_path']          = './assets/uploads/expo/';
        $config['allowed_types']        = 'xlsx|xls';
        $config['max_size']             = 10000;
        $config['max_width']            = 10204;
        $config['max_height']           = 7608;
        $this->load->library('upload', $config);
        $this->upload->initialize($config);
        $this->upload->do_upload('file_otizaciones',$filen);
		$this->load->library("excelfile");
		ini_set("memory_limit", -1);
		$file = $_FILES["file_otizaciones"]["tmp_name"];
		$filename=$_FILES['file_otizaciones']['name'];
		$sheet = PHPExcel_IOFactory::load($file);
		$objExcel = PHPExcel_IOFactory::load($file);
		$sheet = $objExcel->getSheet(0);
		$num_rows = $sheet->getHighestDataRow();
		for ($i=3; $i<=$num_rows; $i++) {
			if($sheet->getCell('C'.$i)->getValue() > 0){
				$productos = $this->prod_mdl->get("id_producto",['codigo'=> htmlspecialchars($sheet->getCell('A'.$i)->getValue(), ENT_QUOTES, 'UTF-8')])[0];
				if (sizeof($productos) > 0) {
					$precio=0; $column_one=0; $column_two=0; $descuento=0; $precio_promocion=0;
					$precio = str_replace("$", "", str_replace(",", "replace", $sheet->getCell('C'.$i)->getValue()));
					$column_one = $sheet->getCell('E'.$i)->getValue();
					$column_two = $sheet->getCell('F'.$i)->getValue();
					$descuento = $sheet->getCell('G'.$i)->getValue();
					if ($column_one ==1 && $column_two ==1) {
						$precio_promocion = (($precio * $column_two)/($column_one+$column_two));
					}elseif ($column_one >=1 && $column_two >1) {
						$precio_promocion = (($precio * $column_two)/($column_one+$column_two));
					}elseif ($descuento >0) {
						$precio_promocion = ($precio - ($precio * ($descuento/100)));
					}else{
						$precio_promocion = $precio;
					}
					$cotiz =  $this->expo_mdl->get(NULL, ['id_producto' => $productos->id_producto, 'WEEKOFYEAR(fecha_registro)' => $this->weekNumber($fecha->format('Y-m-d H:i:s')), 'id_proveedor' => $proveedor])[0];
					$new_cotizacion=[
						"id_producto"		=>	$productos->id_producto,
						"id_proveedor"		=>	$proveedor,//Recupera el id_usuario activo
						"precio"			=>	$precio,
						"num_one"			=>	$column_one,
						"num_two"			=>	$column_two,
						"descuento"			=>	$descuento,
						"precio_promocion"	=>	$precio_promocion,
						"fecha_registro"	=>	$fecha->format('Y-m-d H:i:s'),
						"observaciones"		=>	$sheet->getCell('D'.$i)->getValue(),
						"estatus" => 1];
					if($cotiz){
						$data['cotizacion']=$this->expo_mdl->update($new_cotizacion, ['id_cotizacion' => $cotiz->id_cotizacion]);
					}else{
						$data['cotizacion']=$this->expo_mdl->insert($new_cotizacion);
					}
				}
			}
		}
		if (sizeof($new_cotizacion) > 0) {
			$aprov = $this->usua_mdl->get(NULL, ['id_usuario'=>$proveedor])[0];
			$cambios=[
					"id_usuario"		=>	$this->session->userdata('id_usuario'),
					"fecha_cambio"		=>	date("Y-m-d H:i:s"),
					"antes"			=>	"El usuario sube archivo de expo cotizaciones de ".$aprov->nombre,
					"despues"			=>	"assets/uploads/expo/".$filen.".xlsx",
					"accion"			=>	"Sube Archivo"
				];
			$data['cambios']=$this->cambio_md->insert($cambios);
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
	public function saveexpos($idesp){
		if($idesp == 0){
			$proveedor = $this->session->userdata('id_usuario');
		}else{
			$proveedor = $idesp;
		}
		$fecha = new DateTime(date('Y-m-d H:i:s'));
		$intervalo = new DateInterval('P2D');
		$fecha->add($intervalo);
		$cotiz =  $this->expo_mdl->get(NULL, ['id_producto' => $this->input->post('id_producto'), 'WEEKOFYEAR(fecha_registro)' => $this->weekNumber($fecha->format('Y-m-d H:i:s')), 'id_proveedor' => $proveedor])[0];
		$aprod = $this->prod_mdl->get(NULL, ['id_producto'=>$this->input->post('id_producto')])[0];
		$aprov = $this->usua_mdl->get(NULL, ['id_usuario'=>$proveedor])[0];
			$cotizacion = [
				'id_producto'		=>	$this->input->post('id_producto'),
				'id_proveedor'		=>	$proveedor,
				'num_one'			=>	$this->input->post('num_one'),
				'num_two'			=>	$this->input->post('num_two'),
				'precio'			=>	str_replace(',', '', $this->input->post('precio')),//precio base
				'precio_promocion'	=>	($this->input->post('precio_promocion') > 0) ? str_replace(',', '', $this->input->post('precio_promocion')) : str_replace(',', '', $this->input->post('precio')),//precio con promoción
				'descuento'			=>	str_replace(',', '', $this->input->post('porcentaje')),
				'fecha_registro'	=>	$fecha->format('Y-m-d H:i:s'),
				'observaciones'		=>	strtoupper($this->input->post('observaciones')),
				'estatus' => 1
			];
			if($cotiz){
				$data['cotizacin']=$this->expo_mdl->update($cotizacion, ['id_cotizacion' => $cotiz->id_cotizacion]);
			}else{
				$data['cotizacin']=$this->expo_mdl->insert($cotizacion);
			}
			$cambios = [
				"id_usuario" => $this->session->userdata('id_usuario'),
				"fecha_cambio" => date('Y-m-d H:i:s'),
				"accion" => "Cotizacion Nueva Con Faltante",
				"antes" => "Nueva cotización",
				"despues" => "Producto: ".$aprod->nombre."\n///Proveedor: ".$aprov->nombre."\n///Precio: ".str_replace(',', '', $this->input->post('precio'))."\n///Precio promoción: ".
							(($this->input->post('precio_promocion') > 0) ? str_replace(',', '', $this->input->post('precio_promocion')) : str_replace(',', '', $this->input->post('precio')))." ".
							"\n///".$this->input->post('num_one')." EN ".$this->input->post('num_two')."\n///Descuento: ".str_replace(',', '', $this->input->post('porcentaje')).
							"\n///Observaciones: ".strtoupper($this->input->post('observaciones'))
			];
			$data['cambios'] = $this->cambio_md->insert($cambios);
		$mensaje = [
			"id" 	=> 'Éxito',
			"desc"	=> 'Cotización registrada correctamente',
			"type"	=> 'success'
		];
		$this->jsonResponse($mensaje);
	}
	public function proveedorExpos($ides){
		$fecha = new DateTime(date('Y-m-d H:i:s'));
		$intervalo = new DateInterval('P2D');
		$fecha->add($intervalo);
		$where=["expocotz.id_proveedor" => $ides, "expocotz.estatus <> " => 0 ,
				"WEEKOFYEAR(expocotz.fecha_registro)" => $this->weekNumber($fecha->format('Y-m-d H:i:s'))];
		$data["cotizaciones"] = $this->expo_mdl->getAllCotizaciones($where);
		$this->jsonResponse($data["cotizaciones"]);
	}
	public function comparaExpo(){
		ini_set("memory_limit", "-1");
		ini_set("max_execution_time", "-1");
		$this->load->library("excelfile");
		$hoja = $this->excelfile->getActiveSheet();
				$hoja->getDefaultStyle()
		    ->getBorders()
		    ->getTop()
		        ->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
		$hoja->getDefaultStyle()
		    ->getBorders()
		    ->getBottom()
		        ->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
		$hoja->getDefaultStyle()
		    ->getBorders()
		    ->getLeft()
		        ->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
		$hoja->getDefaultStyle()
		    ->getBorders()
		    ->getRight()
		        ->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
		$this->cellStyle("A1:AY2", "000000", "FFFFFF", TRUE, 12, "Franklin Gothic Book");
		$border_style= array('borders' => array('right' => array('style' =>
			PHPExcel_Style_Border::BORDER_THIN,'color' => array('argb' => '000000'),)));
		$hoja->setCellValue("A2", "CÓDIGO")->getColumnDimension('A')->setWidth(30); //Nombre y ajuste de texto a la columna
		$hoja->setCellValue("B1", "DESCRIPCIÓN")->getColumnDimension('B')->setWidth(60);
		$hoja->setCellValue("C2", "SISTEMA")->getColumnDimension('C')->setWidth(12);
		$hoja->setCellValue("D2", "PRECIO 4")->getColumnDimension('D')->setWidth(12);
		$hoja->setCellValue("E1", "PRECIO")->getColumnDimension('E')->setWidth(12);
		$hoja->setCellValue("E2", "MÁXIMO")->getColumnDimension('E')->setWidth(12);
		$hoja->setCellValue("F1", "PRECIO")->getColumnDimension('F')->setWidth(12);
		$hoja->setCellValue("F2", "PROMEDIO")->getColumnDimension('F')->setWidth(12);
		$hoja->setCellValue("G1", "DIF")->getColumnDimension('G')->setWidth(12);
		$hoja->setCellValue("G2", "SISTEMA")->getColumnDimension('G')->setWidth(12);
		$hoja->setCellValue("H1", "DIF 1ER")->getColumnDimension('H')->setWidth(12);
		$hoja->setCellValue("I1", "DIF 2DO")->getColumnDimension('I')->setWidth(12);
		$hoja->setCellValue("J1", "PRECIO")->getColumnDimension('J')->setWidth(12);
		$hoja->setCellValue("K1", "CON")->getColumnDimension('K')->setWidth(12);
		$hoja->setCellValue("K1", "PROMOCIÓN")->getColumnDimension('K')->setWidth(12);
		$hoja->setCellValue("L1", "OBSERVACIÓN")->getColumnDimension('L')->setWidth(30);
		$hoja->setCellValue("M1", "PRECIO MENOR")->getColumnDimension('M')->setWidth(12);
		$hoja->setCellValue("N1", "PROVEEDOR")->getColumnDimension('N')->setWidth(15);
		$hoja->setCellValue("O1", "OBSERVACIÓN")->getColumnDimension('O')->setWidth(30);
		$hoja->setCellValue("P1", "2DO PRECIO")->getColumnDimension('P')->setWidth(12);
		$hoja->setCellValue("Q1", "2DO PROVEEDOR")->getColumnDimension('Q')->setWidth(15);
		$hoja->setCellValue("R1", "2DA OBSERVACIÓN")->getColumnDimension('R')->setWidth(30);


		$hoja->mergeCells('S1:U1');
		$this->cellStyle("S1", "C00000", "000000", TRUE, 12, "Franklin Gothic Book");
		$hoja->setCellValue("S1", "CEDIS");
		$hoja->mergeCells('V1:X1');
		$hoja->setCellValue("V1", "CD INDUSTRIAL");
		$this->cellStyle("V1", "FF0066", "000000", TRUE, 12, "Franklin Gothic Book");
		$hoja->mergeCells('Y1:AA1');
		$this->cellStyle("Y1", "C2B90A", "000000", TRUE, 12, "Franklin Gothic Book");
		$hoja->setCellValue("Y1", "SUMA CEDIS");
		$hoja->mergeCells('AB1:AD1');
		$this->cellStyle("AB1", "01B0F0", "000000", TRUE, 12, "Franklin Gothic Book");
		$hoja->setCellValue("AB1", "ABARROTES");
		$hoja->mergeCells('AE1:AG1');
		$this->cellStyle("AE1", "FF0000", "000000", TRUE, 12, "Franklin Gothic Book");
		$hoja->setCellValue("AE1", "VILLAS");
		$hoja->mergeCells('AH1:AJ1');
		$this->cellStyle("AH1", "E26C0B", "000000", TRUE, 12, "Franklin Gothic Book");
		$hoja->setCellValue("AH1", "TIENDA");
		$hoja->mergeCells('AK1:AM1');
		$this->cellStyle("AK1", "C5C5C5", "000000", TRUE, 12, "Franklin Gothic Book");
		$hoja->setCellValue("AK1", "ULTRAMARINOS");
		$hoja->mergeCells('AN1:AP1');
		$this->cellStyle("AN1", "92D051", "000000", TRUE, 12, "Franklin Gothic Book");
		$hoja->setCellValue("AN1", "TRINCHERAS");
		$hoja->mergeCells('AQ1:AS1');
		$this->cellStyle("AQ1", "B1A0C7", "000000", TRUE, 12, "Franklin Gothic Book");
		$hoja->setCellValue("AQ1", "AZT MERCADO");
		$hoja->mergeCells('AT1:AV1');
		$this->cellStyle("AT1", "DA9694", "000000", TRUE, 12, "Franklin Gothic Book");
		$hoja->setCellValue("AT1", "TENENCIA");
		$hoja->mergeCells('AW1:AY1');
		$this->cellStyle("AW1", "4CACC6", "000000", TRUE, 12, "Franklin Gothic Book");
		$hoja->setCellValue("AW1", "TIJERAS");

		$this->cellStyle("AZ2", "C00000", "000000", TRUE, 12, "Franklin Gothic Book");
		$this->cellStyle("BA2", "FF0066", "000000", TRUE, 12, "Franklin Gothic Book");
		$this->cellStyle("BB2", "C2B90A", "000000", TRUE, 12, "Franklin Gothic Book");
		$this->cellStyle("BC2", "01B0F0", "000000", TRUE, 12, "Franklin Gothic Book");
		$this->cellStyle("BD2", "FF0000", "000000", TRUE, 12, "Franklin Gothic Book");
		$this->cellStyle("BE2", "E26C0B", "000000", TRUE, 12, "Franklin Gothic Book");
		$this->cellStyle("BF2", "C5C5C5", "000000", TRUE, 12, "Franklin Gothic Book");
		$this->cellStyle("BG2", "92D051", "000000", TRUE, 12, "Franklin Gothic Book");
		$this->cellStyle("BH2", "B1A0C7", "000000", TRUE, 12, "Franklin Gothic Book");
		$this->cellStyle("BI2", "DA9694", "000000", TRUE, 12, "Franklin Gothic Book");
		$this->cellStyle("BJ2", "4CACC6", "000000", TRUE, 12, "Franklin Gothic Book");


		
		$hoja->setCellValue("S2", "CAJAS");
		$hoja->setCellValue("T2", "PZAS");
		$hoja->setCellValue("U2", "PEDIDO");
		$hoja->setCellValue("V2", "CAJAS");
		$hoja->setCellValue("W2", "PZAS");
		$hoja->setCellValue("X2", "PEDIDO");
		$hoja->setCellValue("Y2", "CAJAS");
		$hoja->setCellValue("Z2", "PZAS");
		$hoja->setCellValue("AA2", "PEDIDO");
		$hoja->setCellValue("AB2", "CAJAS");
		$hoja->setCellValue("AC2", "PZAS");
		$hoja->setCellValue("AD2", "PEDIDO");
		$hoja->setCellValue("AE2", "CAJAS");
		$hoja->setCellValue("AF2", "PZAS");
		$hoja->setCellValue("AG2", "PEDIDO");
		$hoja->setCellValue("AH2", "CAJAS");
		$hoja->setCellValue("AI2", "PZAS");
		$hoja->setCellValue("AJ2", "PEDIDO");
		$hoja->setCellValue("AK2", "CAJAS");
		$hoja->setCellValue("AL2", "PZAS");
		$hoja->setCellValue("AM2", "PEDIDO");
		$hoja->setCellValue("AN2", "CAJAS");
		$hoja->setCellValue("AO2", "PZAS");
		$hoja->setCellValue("AP2", "PEDIDO");
		$hoja->setCellValue("AQ2", "CAJAS");
		$hoja->setCellValue("AR2", "PZAS");
		$hoja->setCellValue("AS2", "PEDIDO");
		$hoja->setCellValue("AT2", "CAJAS");
		$hoja->setCellValue("AU2", "PZAS");
		$hoja->setCellValue("AV2", "PEDIDO");
		$hoja->setCellValue("AW2", "CAJAS");
		$hoja->setCellValue("AX2", "PZAS");
		$hoja->setCellValue("AY2", "PEDIDO"); 
		$hoja->mergeCells('AZ1:BJ1');
		
		$hoja->setCellValue("AZ1", "TOTAL POR PRODUCTO");
		$this->cellStyle("AZ1", "000000", "FFFFFF", TRUE, 12, "Franklin Gothic Book");
		$this->cellStyle("AZ2", "000000", "FFFFFF", TRUE, 12, "Franklin Gothic Book");
		$fecha = new DateTime(date('Y-m-d H:i:s'));
		$intervalo = new DateInterval('P2D');
		$fecha->add($intervalo);
		$fecha = $fecha->format('Y-m-d H:i:s');
		//$where = ["expo.id_proveedor"=>$this->input->post("id_pro")];
		$cotizacionesProveedor = $this->expo_mdl->comparaCotizaciones2(NULL, $fecha,$this->input->post("id_pro"));
		$row_print =2;
		if ($cotizacionesProveedor){
			foreach ($cotizacionesProveedor as $key => $value){
				$hoja->setCellValue("B{$row_print}", $value['familia']);
				$this->cellStyle("B{$row_print}", "000000", "FFFFFF", TRUE, 12, "Franklin Gothic Book");
				$row_print +=1;
				if ($value['articulos']) {
					foreach ($value['articulos'] as $key => $row){
						$this->cellStyle("B{$row_print}:L{$row_print}", "FFFFFF", "000000", FALSE, 12, "Franklin Gothic Book");
						if($row['color'] == '#92CEE3'){
							$this->cellStyle("A{$row_print}", "92CEE3", "000000", TRUE, 12, "Franklin Gothic Book");
						}else{
							$this->cellStyle("A{$row_print}", "FFFFFF", "000000", TRUE, 12, "Franklin Gothic Book");
						}
						$hoja->setCellValue("A{$row_print}", $row['codigo'])->getStyle("A{$row_print}")->getNumberFormat()->setFormatCode('# ???/???');//Formato de fraccion
						$hoja->getStyle("A{$row_print}")->applyFromArray($border_style);
						$hoja->setCellValue("B{$row_print}", $row['producto']);
						if($row['colorp'] == 1){
							$this->cellStyle("C{$row_print}", "D6DCE4", "000000", FALSE, 12, "Franklin Gothic Book");
						}else{
							$this->cellStyle("C{$row_print}", "FFFFFF", "000000", FALSE, 12, "Franklin Gothic Book");
						}
						$hoja->setCellValue("C{$row_print}", $row['precio_sistema'])->getStyle("C{$row_print}")->getNumberFormat()->setFormatCode('"$"#,##0.00_-');//Formto de moneda
						$hoja->getStyle("C{$row_print}")->applyFromArray($border_style);
						$hoja->setCellValue("D{$row_print}", $row['precio_four'])->getStyle("D{$row_print}")->getNumberFormat()->setFormatCode('"$"#,##0.00_-');
						$hoja->getStyle("D{$row_print}")->applyFromArray($border_style);
						$hoja->setCellValue("E{$row_print}", $row['precio_maximo'])->getStyle("E{$row_print}")->getNumberFormat()->setFormatCode('"$"#,##0.00_-');
						$hoja->getStyle("E{$row_print}")->applyFromArray($border_style);
						$hoja->setCellValue("F{$row_print}", $row['precio_promedio'])->getStyle("F{$row_print}")->getNumberFormat()->setFormatCode('"$"#,##0.00_-');
						$hoja->getStyle("F{$row_print}")->applyFromArray($border_style);
						$dif1 = $row["precio_sistema"] - $row["xpromo"];
						if ($dif1 >= ($row["precio_sistema"] * .30) || $dif1 <= (($row["precio_sistema"] * .30) * (-1))) {
							$hoja->setCellValue("G{$row_print}", $dif1)->getStyle("G{$row_print}")->getNumberFormat()->setFormatCode('"$"#,##0.00_-');
							$hoja->getStyle("G{$row_print}")->applyFromArray($border_style);
							$this->cellStyle("G{$row_print}", "FF0066", "000000", FALSE, 12, "Franklin Gothic Book");
						}else{
							$hoja->setCellValue("G{$row_print}", $dif1)->getStyle("G{$row_print}")->getNumberFormat()->setFormatCode('"$"#,##0.00_-');
							$hoja->getStyle("G{$row_print}")->applyFromArray($border_style);
							$this->cellStyle("G{$row_print}", "FFE6F0", "000000", FALSE, 12, "Franklin Gothic Book");
						}
						$dif2 = $row["precio_first"] - $row["xpromo"];
						if($row['precio_first'] !== NULL){
							if ($dif2 >= ($row["precio_first"] * .30) || $dif2 <= (($row["precio_first"] * .30) * (-1))) {
								$hoja->setCellValue("H{$row_print}", $dif2)->getStyle("H{$row_print}")->getNumberFormat()->setFormatCode('"$"#,##0.00_-');
								$hoja->getStyle("H{$row_print}")->applyFromArray($border_style);
								$this->cellStyle("H{$row_print}", "FF0066", "000000", FALSE, 12, "Franklin Gothic Book");
							}else{
								$hoja->setCellValue("H{$row_print}", $dif2)->getStyle("H{$row_print}")->getNumberFormat()->setFormatCode('"$"#,##0.00_-');
								$hoja->getStyle("H{$row_print}")->applyFromArray($border_style);
								$this->cellStyle("H{$row_print}", "FFE6F0", "000000", FALSE, 12, "Franklin Gothic Book");
							}
						}else{
							$hoja->getStyle("H{$row_print}")->applyFromArray($border_style);
							$this->cellStyle("H{$row_print}", "FFE6F0", "000000", FALSE, 12, "Franklin Gothic Book");
						}
						$dif3 = $row["precio_next"] - $row["xpromo"];
						if($row['precio_next'] !== NULL){
							if ($dif3 >= ($row["precio_next"] * .30) || $dif3 <= (($row["precio_next"] * .30) * (-1))) {
								$hoja->setCellValue("I{$row_print}", $dif3)->getStyle("I{$row_print}")->getNumberFormat()->setFormatCode('"$"#,##0.00_-');
								$hoja->getStyle("I{$row_print}")->applyFromArray($border_style);
								$this->cellStyle("I{$row_print}", "FF0066", "000000", FALSE, 12, "Franklin Gothic Book");
							}else{
								$hoja->setCellValue("I{$row_print}", $dif3)->getStyle("I{$row_print}")->getNumberFormat()->setFormatCode('"$"#,##0.00_-');
								$hoja->getStyle("I{$row_print}")->applyFromArray($border_style);
								$this->cellStyle("I{$row_print}", "FFE6F0", "000000", FALSE, 12, "Franklin Gothic Book");
							}
						}else{
							$hoja->getStyle("I{$row_print}")->applyFromArray($border_style);
							$this->cellStyle("I{$row_print}", "FFE6F0", "000000", FALSE, 12, "Franklin Gothic Book");
						}
						$hoja->setCellValue("J{$row_print}", $row['xprecio'])->getStyle("J{$row_print}")->getNumberFormat()->setFormatCode('"$"#,##0.00_-');
						$hoja->getStyle("J{$row_print}")->applyFromArray($border_style);
						if($row['precio_sistema'] < $row['xpromo']){
							$hoja->setCellValue("K{$row_print}", $row['xpromo'])->getStyle("K{$row_print}")->getNumberFormat()->setFormatCode('"$"#,##0.00_-');
							$hoja->getStyle("K{$row_print}")->applyFromArray($border_style);
							$this->cellStyle("K{$row_print}", "FDB2B2", "E21111", FALSE, 12, "Franklin Gothic Book");
						}else{
							$hoja->setCellValue("K{$row_print}", $row['xpromo'])->getStyle("K{$row_print}")->getNumberFormat()->setFormatCode('"$"#,##0.00_-');
							$hoja->getStyle("K{$row_print}")->applyFromArray($border_style);
							$this->cellStyle("K{$row_print}", "96EAA8", "0C800C", FALSE, 12, "Franklin Gothic Book");
						}
						$hoja->setCellValue("L{$row_print}", $row['xobs'])->getStyle("L{$row_print}")->getNumberFormat()->setFormatCode('"$"#,##0.00_-');
						$hoja->getStyle("L{$row_print}")->applyFromArray($border_style);
						$hoja->setCellValue("M{$row_print}", $row['precio_first'])->getStyle("M{$row_print}")->getNumberFormat()->setFormatCode('"$"#,##0.00_-');
						$hoja->getStyle("M{$row_print}")->applyFromArray($border_style);
						$hoja->setCellValue("N{$row_print}", $row['proveedor_first'])->getStyle("N{$row_print}");
						$hoja->getStyle("N{$row_print}")->applyFromArray($border_style);
						$hoja->setCellValue("O{$row_print}", $row['promocion_first'])->getStyle("O{$row_print}");
						$hoja->getStyle("O{$row_print}")->applyFromArray($border_style);
						$hoja->setCellValue("P{$row_print}", $row['precio_next'])->getStyle("P{$row_print}")->getNumberFormat()->setFormatCode('"$"#,##0.00_-');
						$hoja->getStyle("P{$row_print}")->applyFromArray($border_style);
						$hoja->setCellValue("Q{$row_print}", $row['proveedor_next'])->getStyle("Q{$row_print}");
						$hoja->getStyle("Q{$row_print}")->applyFromArray($border_style);
						$hoja->setCellValue("R{$row_print}", $row['promocion_next'])->getStyle("R{$row_print}");
						$hoja->getStyle("R{$row_print}")->applyFromArray($border_style);
						if($row['estatus'] == 2){
							$this->cellStyle("B{$row_print}", "00B0F0", "000000", FALSE, 12, "Franklin Gothic Book");
						}
						if($row['estatus'] == 3){
							$this->cellStyle("B{$row_print}", "FFF900", "000000", FALSE, 12, "Franklin Gothic Book");
						}
						if($row['estatus'] >= 4){
							$this->cellStyle("B{$row_print}", "04B486", "000000", FALSE, 12, "Franklin Gothic Book");
						}
						$hoja->getStyle("B{$row_print}:BA{$row_print}")->applyFromArray($border_style);
						$hoja->getStyle("A{$row_print}:BA{$row_print}")
			                 ->getAlignment()
			                 ->setHorizontal(\PHPExcel_Style_Alignment::HORIZONTAL_LEFT);

						$hoja->setCellValue("S{$row_print}", $row['caja0']);
						$hoja->setCellValue("T{$row_print}", $row['pz0']);
						$hoja->setCellValue("U{$row_print}", $row['ped0']);
						$this->cellStyle("U{$row_print}", "D4EAEF", "000000", TRUE, 12, "Franklin Gothic Book");

						$hoja->setCellValue("V{$row_print}", $row['caja9']);
						$hoja->setCellValue("W{$row_print}", $row['pz9']);
						$hoja->setCellValue("X{$row_print}", $row['ped9']);
						$this->cellStyle("X{$row_print}", "D4EAEF", "000000", TRUE, 12, "Franklin Gothic Book");

						$hoja->setCellValue("Y{$row_print}", "=S".$row_print."+Y".$row_print);
						$hoja->setCellValue("Z{$row_print}", "=T".$row_print."+W".$row_print);
						$hoja->setCellValue("AA{$row_print}", "=U".$row_print."+X".$row_print);
						$this->cellStyle("AA{$row_print}", "D4EAEF", "000000", TRUE, 12, "Franklin Gothic Book");

						$hoja->setCellValue("AB{$row_print}", $row['caja1']);
						$hoja->setCellValue("AC{$row_print}", $row['pz1']);
						$hoja->setCellValue("AD{$row_print}", $row['ped1']);
						$this->cellStyle("AD{$row_print}", "D4EAEF", "000000", TRUE, 12, "Franklin Gothic Book");

						$hoja->setCellValue("AE{$row_print}", $row['caja2']);
						$hoja->setCellValue("AF{$row_print}", $row['pz2']);
						$hoja->setCellValue("AG{$row_print}", $row['ped2']);
						$this->cellStyle("AG{$row_print}", "D4EAEF", "000000", TRUE, 12, "Franklin Gothic Book");

						$hoja->setCellValue("AH{$row_print}", $row['caja3']);
						$hoja->setCellValue("AI{$row_print}", $row['pz3']);
						$hoja->setCellValue("AJ{$row_print}", $row['ped3']);
						$this->cellStyle("AJ{$row_print}", "D4EAEF", "000000", TRUE, 12, "Franklin Gothic Book");

						$hoja->setCellValue("AK{$row_print}", $row['caja4']);
						$hoja->setCellValue("AL{$row_print}", $row['pz4']);
						$hoja->setCellValue("AM{$row_print}", $row['ped4']);
						$this->cellStyle("AM{$row_print}", "D4EAEF", "000000", TRUE, 12, "Franklin Gothic Book");

						$hoja->setCellValue("AN{$row_print}", $row['caja5']);
						$hoja->setCellValue("AO{$row_print}", $row['pz5']);
						$hoja->setCellValue("AP{$row_print}", $row['ped5']);
						$this->cellStyle("AP{$row_print}", "D4EAEF", "000000", TRUE, 12, "Franklin Gothic Book");

						$hoja->setCellValue("AQ{$row_print}", $row['caja6']);
						$hoja->setCellValue("AR{$row_print}", $row['pz6']);
						$hoja->setCellValue("AS{$row_print}", $row['ped6']);
						$this->cellStyle("AS{$row_print}", "D4EAEF", "000000", TRUE, 12, "Franklin Gothic Book");

						$hoja->setCellValue("AT{$row_print}", $row['caja7']);
						$hoja->setCellValue("AU{$row_print}", $row['pz7']);
						$hoja->setCellValue("AV{$row_print}", $row['ped7']);
						$this->cellStyle("AV{$row_print}", "D4EAEF", "000000", TRUE, 12, "Franklin Gothic Book");

						$hoja->setCellValue("AW{$row_print}", $row['caja8']);
						$hoja->setCellValue("AX{$row_print}", $row['pz8']);
						$hoja->setCellValue("AY{$row_print}", $row['ped8']);
						$this->cellStyle("AY{$row_print}", "D4EAEF", "000000", TRUE, 12, "Franklin Gothic Book");

						$hoja->getStyle("S{$row_print}")->applyFromArray($border_style);
						$hoja->getStyle("T{$row_print}")->applyFromArray($border_style);
						$hoja->getStyle("U{$row_print}")->applyFromArray($border_style);
						$hoja->getStyle("V{$row_print}")->applyFromArray($border_style);
						$hoja->getStyle("W{$row_print}")->applyFromArray($border_style);
						$hoja->getStyle("X{$row_print}")->applyFromArray($border_style);
						$hoja->getStyle("Y{$row_print}")->applyFromArray($border_style);
						$hoja->getStyle("Z{$row_print}")->applyFromArray($border_style);
						$hoja->getStyle("AA{$row_print}")->applyFromArray($border_style);
						$hoja->getStyle("AB{$row_print}")->applyFromArray($border_style);
						$hoja->getStyle("AC{$row_print}")->applyFromArray($border_style);
						$hoja->getStyle("AD{$row_print}")->applyFromArray($border_style);
						$hoja->getStyle("AE{$row_print}")->applyFromArray($border_style);
						$hoja->getStyle("AF{$row_print}")->applyFromArray($border_style);
						$hoja->getStyle("AG{$row_print}")->applyFromArray($border_style);
						$hoja->getStyle("AH{$row_print}")->applyFromArray($border_style);
						$hoja->getStyle("AI{$row_print}")->applyFromArray($border_style);
						$hoja->getStyle("AJ{$row_print}")->applyFromArray($border_style);
						$hoja->getStyle("AK{$row_print}")->applyFromArray($border_style);
						$hoja->getStyle("AL{$row_print}")->applyFromArray($border_style);
						$hoja->getStyle("AM{$row_print}")->applyFromArray($border_style);
						$hoja->getStyle("AN{$row_print}")->applyFromArray($border_style);
						$hoja->getStyle("AO{$row_print}")->applyFromArray($border_style);
						$hoja->getStyle("AP{$row_print}")->applyFromArray($border_style);
						$hoja->getStyle("AQ{$row_print}")->applyFromArray($border_style);
						$hoja->getStyle("AR{$row_print}")->applyFromArray($border_style);
						$hoja->getStyle("AS{$row_print}")->applyFromArray($border_style);
						$hoja->getStyle("AT{$row_print}")->applyFromArray($border_style);
						$hoja->getStyle("AU{$row_print}")->applyFromArray($border_style);
						$hoja->getStyle("AV{$row_print}")->applyFromArray($border_style);
						$hoja->getStyle("AW{$row_print}")->applyFromArray($border_style);
						$hoja->getStyle("AX{$row_print}")->applyFromArray($border_style);
						$hoja->getStyle("AY{$row_print}")->applyFromArray($border_style);
						$hoja->getStyle("AZ{$row_print}")->applyFromArray($border_style);
						$hoja->getStyle("BA{$row_print}")->applyFromArray($border_style);
						$hoja->getStyle("BB{$row_print}")->applyFromArray($border_style);
						$hoja->getStyle("BC{$row_print}")->applyFromArray($border_style);
						$hoja->getStyle("BD{$row_print}")->applyFromArray($border_style);
						$hoja->getStyle("BE{$row_print}")->applyFromArray($border_style);
						$hoja->getStyle("BF{$row_print}")->applyFromArray($border_style);
						$hoja->getStyle("BG{$row_print}")->applyFromArray($border_style);
						$hoja->getStyle("BH{$row_print}")->applyFromArray($border_style);
						$hoja->getStyle("BI{$row_print}")->applyFromArray($border_style);
						$hoja->getStyle("BJ{$row_print}")->applyFromArray($border_style);
						$hoja->getStyle("B{$row_print}")->applyFromArray($border_style);



						$hoja->getStyle("S{$row_print}:AW{$row_print}")->applyFromArray($border_style);

						$hoja->setCellValue("AZ{$row_print}", "=(K".$row_print."*U".$row_print.")")->getStyle("AZ{$row_print}")->getNumberFormat()->setFormatCode('"$"#,##0.00_-');
						$hoja->setCellValue("BA{$row_print}", "=(K".$row_print."*X".$row_print.")")->getStyle("BA{$row_print}")->getNumberFormat()->setFormatCode('"$"#,##0.00_-');
						$hoja->setCellValue("BB{$row_print}", "=(K".$row_print."*AA".$row_print.")")->getStyle("BB{$row_print}")->getNumberFormat()->setFormatCode('"$"#,##0.00_-');
						$hoja->setCellValue("BC{$row_print}", "=(K".$row_print."*AD".$row_print.")")->getStyle("BC{$row_print}")->getNumberFormat()->setFormatCode('"$"#,##0.00_-');
						$hoja->setCellValue("BD{$row_print}", "=(K".$row_print."*AG".$row_print.")")->getStyle("BD{$row_print}")->getNumberFormat()->setFormatCode('"$"#,##0.00_-');
						$hoja->setCellValue("BE{$row_print}", "=(K".$row_print."*AJ".$row_print.")")->getStyle("BE{$row_print}")->getNumberFormat()->setFormatCode('"$"#,##0.00_-');
						$hoja->setCellValue("BF{$row_print}", "=(K".$row_print."*AM".$row_print.")")->getStyle("BF{$row_print}")->getNumberFormat()->setFormatCode('"$"#,##0.00_-');
						$hoja->setCellValue("BG{$row_print}", "=(K".$row_print."*AP".$row_print.")")->getStyle("BG{$row_print}")->getNumberFormat()->setFormatCode('"$"#,##0.00_-');
						$hoja->setCellValue("BH{$row_print}", "=(K".$row_print."*AS".$row_print.")")->getStyle("BH{$row_print}")->getNumberFormat()->setFormatCode('"$"#,##0.00_-');
						$hoja->setCellValue("BI{$row_print}", "=(K".$row_print."*AV".$row_print.")")->getStyle("BI{$row_print}")->getNumberFormat()->setFormatCode('"$"#,##0.00_-');
						$hoja->setCellValue("BJ{$row_print}", "=(K".$row_print."*AY".$row_print.")")->getStyle("BJ{$row_print}")->getNumberFormat()->setFormatCode('"$"#,##0.00_-');
						$hoja->setCellValue("BK{$row_print}", "=SUM(AZ{$row_print}:BJ{$row_print})")->getStyle("AK{$row_print}")->getNumberFormat()->setFormatCode('"$"#,##0.00_-');
						$this->cellStyle("BK{$row_print}", "000000", "FFFFFF", FALSE, 12, "Franklin Gothic Book");
						$row_print ++;
					}
				}
			}
		}
		$flags = $row_print - 1;
		$hoja->setCellValue("AZ{$row_print}", "=SUM(AZ3:AZ".$flags.")")->getStyle("AZ{$row_print}")->getNumberFormat()->setFormatCode('"$"#,##0.00_-');
		$hoja->setCellValue("BA{$row_print}", "=SUM(BA3:BA".$flags.")")->getStyle("BA{$row_print}")->getNumberFormat()->setFormatCode('"$"#,##0.00_-');
		$hoja->setCellValue("BB{$row_print}", "=SUM(BB3:BB".$flags.")")->getStyle("BB{$row_print}")->getNumberFormat()->setFormatCode('"$"#,##0.00_-');
		$hoja->setCellValue("BC{$row_print}", "=SUM(BC3:BC".$flags.")")->getStyle("BC{$row_print}")->getNumberFormat()->setFormatCode('"$"#,##0.00_-');
		$hoja->setCellValue("BD{$row_print}", "=SUM(BD3:BD".$flags.")")->getStyle("BD{$row_print}")->getNumberFormat()->setFormatCode('"$"#,##0.00_-');
		$hoja->setCellValue("BE{$row_print}", "=SUM(BE3:BE".$flags.")")->getStyle("BE{$row_print}")->getNumberFormat()->setFormatCode('"$"#,##0.00_-');
		$hoja->setCellValue("BF{$row_print}", "=SUM(BF3:BF".$flags.")")->getStyle("BF{$row_print}")->getNumberFormat()->setFormatCode('"$"#,##0.00_-');
		$hoja->setCellValue("BG{$row_print}", "=SUM(BG3:BG".$flags.")")->getStyle("BG{$row_print}")->getNumberFormat()->setFormatCode('"$"#,##0.00_-');
		$hoja->setCellValue("BH{$row_print}", "=SUM(BH3:BH".$flags.")")->getStyle("BH{$row_print}")->getNumberFormat()->setFormatCode('"$"#,##0.00_-');
		$hoja->setCellValue("BI{$row_print}", "=SUM(BI3:BI".$flags.")")->getStyle("BI{$row_print}")->getNumberFormat()->setFormatCode('"$"#,##0.00_-');
		$hoja->setCellValue("BJ{$row_print}", "=SUM(BJ3:BJ".$flags.")")->getStyle("BJ{$row_print}")->getNumberFormat()->setFormatCode('"$"#,##0.00_-');
		$hoja->setCellValue("BK{$row_print}", "=SUM(BK3:BK".$flags.")")->getStyle("BK{$row_print}")->getNumberFormat()->setFormatCode('"$"#,##0.00_-');
        $dias = array("DOMINGO","LUNES","MARTES","MIÉRCOLES","JUEVES","VIERNES","SÁBADO");
		$meses = array("ENERO","FEBRERO","MARZO","ABRIL","MAYO","JUNIO","JULIO","AGOSTO","SEPTIEMBRE","OCTUBRE","NOVIEMBRE","DICIEMBRE");
		$fecha =  $dias[date('w')]." ".date('d')." DE ".$meses[date('n')-1]. " DEL ".date('Y') ;
		$file_name = "EXPO COMPARACIÓN ".$fecha.".xlsx"; //Nombre del documento con extención
		header("Content-Type: application/vnd.ms-excel; charset=utf-8");
		header("Content-Disposition: attachment;filename=".$file_name);
		header("Cache-Control: max-age=0");
		$excel_Writer = PHPExcel_IOFactory::createWriter($this->excelfile, "Excel2007");
		$excel_Writer->save("php://output");
	}
	public function fill_existe(){
		ini_set("memory_limit", "-1");
		ini_set("max_execution_time", "-1");
		$this->load->library("excelfile");
		$hoja = $this->excelfile->getActiveSheet();
				$hoja->getDefaultStyle()
		    ->getBorders()
		    ->getTop()
		        ->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
		$hoja->getDefaultStyle()
		    ->getBorders()
		    ->getBottom()
		        ->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
		$hoja->getDefaultStyle()
		    ->getBorders()
		    ->getLeft()
		        ->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
		$hoja->getDefaultStyle()
		    ->getBorders()
		    ->getRight()
		        ->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
		$this->cellStyle("A1:AC2", "000000", "FFFFFF", TRUE, 12, "Franklin Gothic Book");
		$border_style= array('borders' => array('right' => array('style' =>
			PHPExcel_Style_Border::BORDER_THIN,'color' => array('argb' => '000000'),)));
		$hoja->setCellValue("A2", "CÓDIGO")->getColumnDimension('A')->setWidth(30); //Nombre y ajuste de texto a la columna
		$hoja->setCellValue("B1", "DESCRIPCIÓN")->getColumnDimension('B')->setWidth(50);
		$hoja->mergeCells('C1:E1');
		$hoja->mergeCells('F1:H1');
		$hoja->mergeCells('I1:K1');
		$hoja->mergeCells('L1:N1');
		$hoja->mergeCells('O1:Q1');
		$hoja->mergeCells('R1:T1');
		$hoja->mergeCells('U1:W1');
		$hoja->mergeCells('X1:Z1');
		$hoja->mergeCells('AA1:AC1');
		$hoja->mergeCells('AD1:AF1');
		$this->cellStyle("C1", "C00000", "000000", TRUE, 12, "Franklin Gothic Book");
		$hoja->setCellValue("C1", "CEDIS");
		$this->cellStyle("F1", "01B0F0", "000000", TRUE, 12, "Franklin Gothic Book");
		$hoja->setCellValue("F1", "ABARROTES");
		$this->cellStyle("I1", "E26C0B", "000000", TRUE, 12, "Franklin Gothic Book");
		$hoja->setCellValue("I1", "TIENDA");
		$this->cellStyle("L1", "C5C5C5", "000000", TRUE, 12, "Franklin Gothic Book");
		$hoja->setCellValue("L1", "ULTRAMARINOS");
		$this->cellStyle("O1", "92D051", "000000", TRUE, 12, "Franklin Gothic Book");
		$hoja->setCellValue("O1", "TRINCHERAS");
		$this->cellStyle("R1", "B1A0C7", "000000", TRUE, 12, "Franklin Gothic Book");
		$hoja->setCellValue("R1", "AZT MERCADO");
		$this->cellStyle("U1", "DA9694", "000000", TRUE, 12, "Franklin Gothic Book");
		$hoja->setCellValue("U1", "TENENCIA");
		$this->cellStyle("X1", "4CACC6", "000000", TRUE, 12, "Franklin Gothic Book");
		$hoja->setCellValue("X1", "TIJERAS");
		$this->cellStyle("AA1", "FF0066", "000000", TRUE, 12, "Franklin Gothic Book");
		$hoja->setCellValue("AA1", "SUPER INDUSTRIAL");
		$this->cellStyle("AD1", "FF0066", "000000", TRUE, 12, "Franklin Gothic Book");
		$hoja->setCellValue("AD1", "VILLAS");

		$hoja->setCellValue("C2", "CAJAS");
		$hoja->setCellValue("D2", "PZAS");
		$hoja->setCellValue("E2", "PEDIDO");
		$hoja->setCellValue("F2", "CAJAS");
		$hoja->setCellValue("G2", "PZAS");
		$hoja->setCellValue("H2", "PEDIDO");
		$hoja->setCellValue("I2", "CAJAS");
		$hoja->setCellValue("J2", "PZAS");
		$hoja->setCellValue("K2", "PEDIDO");
		$hoja->setCellValue("L2", "CAJAS");
		$hoja->setCellValue("M2", "PZAS");
		$hoja->setCellValue("N2", "PEDIDO");
		$hoja->setCellValue("O2", "CAJAS");
		$hoja->setCellValue("P2", "PZAS");
		$hoja->setCellValue("Q2", "PEDIDO");
		$hoja->setCellValue("R2", "CAJAS");
		$hoja->setCellValue("S2", "PZAS");
		$hoja->setCellValue("T2", "PEDIDO");
		$hoja->setCellValue("U2", "CAJAS");
		$hoja->setCellValue("V2", "PZAS");
		$hoja->setCellValue("W2", "PEDIDO");
		$hoja->setCellValue("X2", "CAJAS");
		$hoja->setCellValue("Y2", "PZAS");
		$hoja->setCellValue("Z2", "PEDIDO");
		$hoja->setCellValue("AA2", "CAJAS");
		$hoja->setCellValue("AB2", "PZAS");
		$hoja->setCellValue("AC2", "PEDIDO");
		$hoja->setCellValue("AD2", "CAJAS");
		$hoja->setCellValue("AE2", "PZAS");
		$hoja->setCellValue("AF2", "PEDIDO");
		$cotizacionesProveedor = $this->ct_mdl->fill_ex(NULL, date('Y-m-d'));
		$row_print =2;
		if ($cotizacionesProveedor){
			foreach ($cotizacionesProveedor as $key => $value){
				$hoja->setCellValue("B{$row_print}", $value['familia']);
				$this->cellStyle("B{$row_print}", "000000", "FFFFFF", TRUE, 12, "Franklin Gothic Book");
				$row_print +=1;
				if ($value['articulos']) {
					foreach ($value['articulos'] as $key => $row){
						$this->cellStyle("B{$row_print}:L{$row_print}", "FFFFFF", "000000", FALSE, 12, "Franklin Gothic Book");
						if($row['color'] == '#92CEE3'){
							$this->cellStyle("A{$row_print}", "92CEE3", "000000", TRUE, 12, "Franklin Gothic Book");
						}else{
							$this->cellStyle("A{$row_print}", "FFFFFF", "000000", TRUE, 12, "Franklin Gothic Book");
						}
						$hoja->setCellValue("A{$row_print}", $row['codigo'])->getStyle("A{$row_print}")->getNumberFormat()->setFormatCode('# ???/???');//Formato de fraccion
						$hoja->getStyle("A{$row_print}")->applyFromArray($border_style);
						$hoja->setCellValue("B{$row_print}", $row['producto']);
						if($row['estatus'] == 2){
							$this->cellStyle("B{$row_print}", "00B0F0", "000000", FALSE, 12, "Franklin Gothic Book");
						}
						if($row['estatus'] == 3){
							$this->cellStyle("B{$row_print}", "FFF900", "000000", FALSE, 12, "Franklin Gothic Book");
						}
						if($row['estatus'] >= 4){
							$this->cellStyle("B{$row_print}", "04B486", "000000", FALSE, 12, "Franklin Gothic Book");
						}
						$hoja->getStyle("B{$row_print}")->applyFromArray($border_style);
						$hoja->getStyle("A{$row_print}:AT{$row_print}")
			                 ->getAlignment()
			                 ->setHorizontal(\PHPExcel_Style_Alignment::HORIZONTAL_LEFT);
			            $this->cellStyle("C".$row_print.":W".$row_print, "FFFFFF", "000000", TRUE, 12, "Franklin Gothic Book");
						$hoja->setCellValue("C{$row_print}", $row['caja0']);
 						$hoja->setCellValue("D{$row_print}", $row['pz0']);
 						$hoja->setCellValue("E{$row_print}", $row['ped0']);
 						$this->cellStyle("E{$row_print}", "D4EAEF", "000000", TRUE, 12, "Franklin Gothic Book");
 						$hoja->setCellValue("F{$row_print}", $row['caja1']);
 						$hoja->setCellValue("G{$row_print}", $row['pz1']);
 						$hoja->setCellValue("H{$row_print}", $row['ped1']);
 						$this->cellStyle("H{$row_print}", "D4EAEF", "000000", TRUE, 12, "Franklin Gothic Book");
 						$hoja->setCellValue("I{$row_print}", $row['caja2']);
 						$hoja->setCellValue("J{$row_print}", $row['pz2']);
 						$hoja->setCellValue("K{$row_print}", $row['ped2']);
 						$this->cellStyle("K{$row_print}", "D4EAEF", "000000", TRUE, 12, "Franklin Gothic Book");
 						$hoja->setCellValue("L{$row_print}", $row['caja3']);
 						$hoja->setCellValue("M{$row_print}", $row['pz3']);
 						$hoja->setCellValue("N{$row_print}", $row['ped3']);
 						$this->cellStyle("N{$row_print}", "D4EAEF", "000000", TRUE, 12, "Franklin Gothic Book");
 						$hoja->setCellValue("O{$row_print}", $row['caja4']);
 						$hoja->setCellValue("P{$row_print}", $row['pz4']);
 						$hoja->setCellValue("Q{$row_print}", $row['ped4']);
 						$this->cellStyle("Q{$row_print}", "D4EAEF", "000000", TRUE, 12, "Franklin Gothic Book");
 						$hoja->setCellValue("R{$row_print}", $row['caja5']);
 						$hoja->setCellValue("S{$row_print}", $row['pz5']);
 						$hoja->setCellValue("T{$row_print}", $row['ped5']);
 						$this->cellStyle("T{$row_print}", "D4EAEF", "000000", TRUE, 12, "Franklin Gothic Book");
						$hoja->setCellValue("U{$row_print}", $row['caja6']);
 						$hoja->setCellValue("V{$row_print}", $row['pz6']);
 						$hoja->setCellValue("W{$row_print}", $row['ped6']);
 						$this->cellStyle("W{$row_print}", "D4EAEF", "000000", TRUE, 12, "Franklin Gothic Book");
 						$hoja->setCellValue("X{$row_print}", $row['caja7']);
 						$hoja->setCellValue("Y{$row_print}", $row['pz7']);
 						$hoja->setCellValue("Z{$row_print}", $row['ped7']);
 						$this->cellStyle("Z{$row_print}", "D4EAEF", "000000", TRUE, 12, "Franklin Gothic Book");
						$hoja->setCellValue("AA{$row_print}", $row['caja8']);
 						$hoja->setCellValue("AB{$row_print}", $row['pz8']);
 						$hoja->setCellValue("AC{$row_print}", $row['ped8']);
 						$this->cellStyle("AC{$row_print}", "D4EAEF", "000000", TRUE, 12, "Franklin Gothic Book");
 						$hoja->setCellValue("AD{$row_print}", $row['caja9']);
 						$hoja->setCellValue("AE{$row_print}", $row['pz9']);
 						$hoja->setCellValue("AF{$row_print}", $row['ped9']);
 						$this->cellStyle("AF{$row_print}", "D4EAEF", "000000", TRUE, 12, "Franklin Gothic Book");
						$hoja->getStyle("C{$row_print}")->applyFromArray($border_style);
						$hoja->getStyle("D{$row_print}")->applyFromArray($border_style);
						$hoja->getStyle("E{$row_print}")->applyFromArray($border_style);
						$hoja->getStyle("F{$row_print}")->applyFromArray($border_style);
						$hoja->getStyle("G{$row_print}")->applyFromArray($border_style);
						$hoja->getStyle("H{$row_print}")->applyFromArray($border_style);
						$hoja->getStyle("I{$row_print}")->applyFromArray($border_style);
						$hoja->getStyle("J{$row_print}")->applyFromArray($border_style);
						$hoja->getStyle("K{$row_print}")->applyFromArray($border_style);
						$hoja->getStyle("L{$row_print}")->applyFromArray($border_style);
						$hoja->getStyle("M{$row_print}")->applyFromArray($border_style);
						$hoja->getStyle("N{$row_print}")->applyFromArray($border_style);
						$hoja->getStyle("O{$row_print}")->applyFromArray($border_style);
						$hoja->getStyle("P{$row_print}")->applyFromArray($border_style);
						$hoja->getStyle("Q{$row_print}")->applyFromArray($border_style);
						$hoja->getStyle("R{$row_print}")->applyFromArray($border_style);
						$hoja->getStyle("S{$row_print}")->applyFromArray($border_style);
						$hoja->getStyle("T{$row_print}")->applyFromArray($border_style);
						$hoja->getStyle("U{$row_print}")->applyFromArray($border_style);
						$hoja->getStyle("V{$row_print}")->applyFromArray($border_style);
						$hoja->getStyle("W{$row_print}")->applyFromArray($border_style);
						$hoja->getStyle("X{$row_print}")->applyFromArray($border_style);
						$hoja->getStyle("Y{$row_print}")->applyFromArray($border_style);
						$hoja->getStyle("Z{$row_print}")->applyFromArray($border_style);
						$hoja->getStyle("AA{$row_print}")->applyFromArray($border_style);
						$hoja->getStyle("AB{$row_print}")->applyFromArray($border_style);
						$hoja->getStyle("AC{$row_print}")->applyFromArray($border_style);
						$hoja->getStyle("AD{$row_print}")->applyFromArray($border_style);
						$hoja->getStyle("AE{$row_print}")->applyFromArray($border_style);
						$hoja->getStyle("AF{$row_print}")->applyFromArray($border_style);
						$row_print ++;
					}
				}
			}
		}
		$file_name = "EXISTENCIAS TODOS LOS PRODUCTOS.xlsx"; //Nombre del documento con extención
		header("Content-Type: application/vnd.ms-excel; charset=utf-8");
		header("Content-Disposition: attachment;filename=".$file_name);
		header("Cache-Control: max-age=0");
		$excel_Writer = PHPExcel_IOFactory::createWriter($this->excelfile, "Excel2007");
		$excel_Writer->save("php://output");
	}
	public function fill_existeNot(){
		ini_set("memory_limit", "-1");
		ini_set("max_execution_time", "-1");
		$this->load->library("excelfile");
		
		$hoja1 = $this->excelfile->setActiveSheetIndex(0);
		$this->excelfile->setActiveSheetIndex(0)->setTitle("EXISTENCIAS");
		$this->excelfile->createSheet();
		$hoja = $this->excelfile->getActiveSheet();
				$hoja->getDefaultStyle()
		    ->getBorders()
		    ->getTop()
		        ->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
		$hoja->getDefaultStyle()
		    ->getBorders()
		    ->getBottom()
		        ->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
		$hoja->getDefaultStyle()
		    ->getBorders()
		    ->getLeft()
		        ->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
		$hoja->getDefaultStyle()
		    ->getBorders()
		    ->getRight()
		        ->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
		$styleArray = array(
		  'borders' => array(
		    'allborders' => array(
		      'style' => PHPExcel_Style_Border::BORDER_THIN
		    )
		  )
		);
		$border_style= array('borders' => array('right' => array('style' =>
			PHPExcel_Style_Border::BORDER_THIN,'color' => array('argb' => '000000'),)));
		$hoja1->setCellValue("D2", "CÓDIGO")->getColumnDimension('D')->setWidth(30); //Nombre y ajuste de texto a la columna
		$hoja1->setCellValue("E1", "DESCRIPCIÓN")->getColumnDimension('E')->setWidth(50);
        $hoja = $this->excelfile->setActiveSheetIndex(1);
        $hoja->setTitle("PEDIDO");
        $hoja1 = $this->excelfile->setActiveSheetIndex(0);
		$flag1 = 1;
		$hoja1->mergeCells('A'.$flag1.':E'.$flag1);
		$this->cellStyle("A".$flag1, "FFFFFF", "000000", TRUE, 12, "Franklin Gothic Book");
		$hoja1->setCellValue("A".$flag1."", "GRUPO ABARROTES AZTECA");
		$hoja1->getStyle('A'.$flag1)->applyFromArray($border_style);
		$flag1++;
		$hoja1->mergeCells('A'.$flag1.':E'.$flag1);
		$this->cellStyle("A".$flag1, "FFFFFF", "000000", TRUE, 12, "Franklin Gothic Book");
		$hoja1->setCellValue("A".$flag1."", "PRODUCTOS SIN COTIZAR ");
		$hoja1->getStyle('A'.$flag1)->applyFromArray($border_style);
		$flag1++;
		$this->cellStyle("A".$flag1.":D".$flag1, "000000", "FFFFFF", TRUE, 12, "Franklin Gothic Book");
		$hoja1->mergeCells('A'.$flag1.':B'.$flag1);
		$hoja1->setCellValue("A".$flag1, "EXISTENCIAS");
		$hoja1->setCellValue("E".$flag1, "DESCRIPCIÓN");
		$this->cellStyle("E".$flag1, "000000", "FFFFFF", TRUE, 12, "Franklin Gothic Book");
		$hoja1->getStyle('A'.$flag1)->applyFromArray($border_style);
		$flag1++;
		$this->cellStyle("A".$flag1.":E".$flag1, "000000", "FFFFFF", TRUE, 12, "Franklin Gothic Book");
		$hoja1->setCellValue("A".$flag1, "CAJAS");
		$hoja1->setCellValue("B".$flag1, "PZAS");
		$hoja1->setCellValue("C".$flag1, "PEDIDO");
		$hoja1->setCellValue("D".$flag1, "COD");
		//$hoja1->setCellValue("E".$flag1, "DESCRIPCIÓN");
		$hoja1->getStyle('A'.$flag1)->applyFromArray($border_style);
		//$flag1++;
		$this->excelfile->setActiveSheetIndex(1);
		$this->cellStyle("A1:AF2", "000000", "FFFFFF", TRUE, 12, "Franklin Gothic Book");
		$border_style= array('borders' => array('right' => array('style' =>
			PHPExcel_Style_Border::BORDER_THIN,'color' => array('argb' => '000000'),)));
		$hoja->setCellValue("A2", "CÓDIGO")->getColumnDimension('A')->setWidth(30); //Nombre y ajuste de texto a la columna
		$hoja->setCellValue("B1", "DESCRIPCIÓN")->getColumnDimension('B')->setWidth(50);
		$hoja->mergeCells('C1:E1');
		$this->cellStyle("C1", "01B0F0", "000000", TRUE, 12, "Franklin Gothic Book");
		$hoja->setCellValue("C1", "ABARROTES");
		$hoja->mergeCells('F1:H1');
		$this->cellStyle("F1", "E26C0B", "000000", TRUE, 12, "Franklin Gothic Book");
		$hoja->setCellValue("F1", "TIENDA");
		$hoja->mergeCells('I1:K1');
		$this->cellStyle("I1", "C5C5C5", "000000", TRUE, 12, "Franklin Gothic Book");
		$hoja->setCellValue("I1", "ULTRAMARINOS");
		$hoja->mergeCells('L1:N1');
		$this->cellStyle("L1", "92D051", "000000", TRUE, 12, "Franklin Gothic Book");
		$hoja->setCellValue("L1", "TRINCHERAS");
		$hoja->mergeCells('O1:Q1');
		$this->cellStyle("O1", "B1A0C7", "000000", TRUE, 12, "Franklin Gothic Book");
		$hoja->setCellValue("O1", "AZT MERCADO");
		$hoja->mergeCells('R1:T1');
		$this->cellStyle("R1", "DA9694", "000000", TRUE, 12, "Franklin Gothic Book");
		$hoja->setCellValue("R1", "TENENCIA");
		$hoja->mergeCells('U1:W1');
		$this->cellStyle("U1", "4CACC6", "000000", TRUE, 12, "Franklin Gothic Book");
		$hoja->setCellValue("U1", "TIJERAS");
		$hoja->mergeCells('X1:Z1');
		$this->cellStyle("X1", "C00000", "000000", TRUE, 12, "Franklin Gothic Book");
		$hoja->setCellValue("X1", "CEDIS");
		$hoja->mergeCells('AA1:AC1');
		$hoja->setCellValue("AA1", "CD INDUSTRIAL");
		$this->cellStyle("AA1", "FF0066", "000000", TRUE, 12, "Franklin Gothic Book");
		$hoja->mergeCells('AD1:AF1');
		$this->cellStyle("AD1", "FF0000", "000000", TRUE, 12, "Franklin Gothic Book");
		$hoja->setCellValue("AD1", "VILLAS");

		$hoja->setCellValue("C2", "CAJAS");
		$hoja->setCellValue("D2", "PZAS");
		$hoja->setCellValue("E2", "PEDIDO");
		$hoja->setCellValue("F2", "CAJAS");
		$hoja->setCellValue("G2", "PZAS");
		$hoja->setCellValue("H2", "PEDIDO");
		$hoja->setCellValue("I2", "CAJAS");
		$hoja->setCellValue("J2", "PZAS");
		$hoja->setCellValue("K2", "PEDIDO");
		$hoja->setCellValue("L2", "CAJAS");
		$hoja->setCellValue("M2", "PZAS");
		$hoja->setCellValue("N2", "PEDIDO");
		$hoja->setCellValue("O2", "CAJAS");
		$hoja->setCellValue("P2", "PZAS");
		$hoja->setCellValue("Q2", "PEDIDO");
		$hoja->setCellValue("R2", "CAJAS");
		$hoja->setCellValue("S2", "PZAS");
		$hoja->setCellValue("T2", "PEDIDO");
		$hoja->setCellValue("U2", "CAJAS");
		$hoja->setCellValue("V2", "PZAS");
		$hoja->setCellValue("W2", "PEDIDO");
		$hoja->setCellValue("X2", "CAJAS");
		$hoja->setCellValue("Y2", "PZAS");
		$hoja->setCellValue("Z2", "PEDIDO");
		$hoja->setCellValue("AA2", "CAJAS");
		$hoja->setCellValue("AB2", "PZAS");
		$hoja->setCellValue("AC2", "PEDIDO");
		$hoja->setCellValue("AD2", "CAJAS");
		$hoja->setCellValue("AE2", "PZAS");
		$hoja->setCellValue("AF2", "PEDIDO");
		$cotizacionesProveedor = $this->ct_mdl->fill_exNot(NULL, date('Y-m-d'));
		$row_print =2;
		if ($cotizacionesProveedor){
			foreach ($cotizacionesProveedor as $key => $value){
				$this->excelfile->setActiveSheetIndex(0);
				$this->cellStyle("E".$flag1, "000000", "FFFFFF", FALSE, 12, "Franklin Gothic Book");
				$hoja1->setCellValue("E".$flag1, $value['familia']);
				//Pedidos
				$this->excelfile->setActiveSheetIndex(1);
				$hoja->setCellValue("B{$row_print}", $value['familia']);
				$this->cellStyle("B{$row_print}", "000000", "FFFFFF", TRUE, 12, "Franklin Gothic Book");
				$row_print +=1;
				$flag1 += 1;
				if ($value['articulos']) {
					foreach ($value['articulos'] as $key => $row){
						$this->excelfile->setActiveSheetIndex(0);
						$this->cellStyle("A".$flag1.":AF".$flag1, "FFFFFF", "000000", FALSE, 12, "Franklin Gothic Book");
						$hoja1->setCellValue("D{$flag1}", $row['codigo'])->getStyle("D{$flag1}")->getNumberFormat()->setFormatCode('# ???/???');//Formato de fraccion
						if($row['color'] == '#92CEE3'){
							$this->cellStyle("D{$flag1}", "92CEE3", "000000", FALSE, 12, "Franklin Gothic Book");
						}else{
							$this->cellStyle("D{$flag1}", "FFFFFF", "000000", FALSE, 12, "Franklin Gothic Book");
						}
						$hoja1->setCellValue("E{$flag1}", $row['producto']);
						$hoja1->getStyle("A{$flag1}:AF{$flag1}")
				                 ->getAlignment()
				                 ->setHorizontal(\PHPExcel_Style_Alignment::HORIZONTAL_LEFT);
				        $hoja1->getStyle("A{$flag1}")->applyFromArray($border_style);
				        $hoja1->getStyle("B{$flag1}")->applyFromArray($border_style);
				        $hoja1->getStyle("C{$flag1}")->applyFromArray($border_style);
				        $hoja1->getStyle("D{$flag1}")->applyFromArray($border_style);
				        $hoja1->getStyle("E{$flag1}")->applyFromArray($border_style);
				        $flag1 += 1;
				        $this->excelfile->setActiveSheetIndex(1);
						$this->cellStyle("B{$row_print}:L{$row_print}", "FFFFFF", "000000", FALSE, 12, "Franklin Gothic Book");
						if($row['color'] == '#92CEE3'){
							$this->cellStyle("A{$row_print}", "92CEE3", "000000", TRUE, 12, "Franklin Gothic Book");
						}else{
							$this->cellStyle("A{$row_print}", "FFFFFF", "000000", TRUE, 12, "Franklin Gothic Book");
						}
						$hoja->setCellValue("A{$row_print}", $row['codigo'])->getStyle("A{$row_print}")->getNumberFormat()->setFormatCode('# ???/???');//Formato de fraccion
						$hoja->getStyle("A{$row_print}")->applyFromArray($border_style);
						$hoja->setCellValue("B{$row_print}", $row['producto']);
						if($row['estatus'] == 2){
							$this->cellStyle("B{$row_print}", "00B0F0", "000000", FALSE, 12, "Franklin Gothic Book");
						}
						if($row['estatus'] == 3){
							$this->cellStyle("B{$row_print}", "FFF900", "000000", FALSE, 12, "Franklin Gothic Book");
						}
						if($row['estatus'] >= 4){
							$this->cellStyle("B{$row_print}", "04B486", "000000", FALSE, 12, "Franklin Gothic Book");
						}
						$hoja->getStyle("B{$row_print}")->applyFromArray($border_style);
						$hoja->getStyle("A{$row_print}:AT{$row_print}")
			                 ->getAlignment()
			                 ->setHorizontal(\PHPExcel_Style_Alignment::HORIZONTAL_LEFT);
			            $this->cellStyle("C".$row_print.":AF".$row_print, "FFFFFF", "000000", TRUE, 12, "Franklin Gothic Book");
						$hoja->setCellValue("C{$row_print}", $row['caja0']);
 						$hoja->setCellValue("D{$row_print}", $row['pz0']);
 						$hoja->setCellValue("E{$row_print}", $row['ped0']);
 						$this->cellStyle("E{$row_print}", "D4EAEF", "000000", TRUE, 12, "Franklin Gothic Book");
 						$hoja->setCellValue("F{$row_print}", $row['caja1']);
 						$hoja->setCellValue("G{$row_print}", $row['pz1']);
 						$hoja->setCellValue("H{$row_print}", $row['ped1']);
 						$this->cellStyle("H{$row_print}", "D4EAEF", "000000", TRUE, 12, "Franklin Gothic Book");
 						$hoja->setCellValue("I{$row_print}", $row['caja2']);
 						$hoja->setCellValue("J{$row_print}", $row['pz2']);
 						$hoja->setCellValue("K{$row_print}", $row['ped2']);
 						$this->cellStyle("K{$row_print}", "D4EAEF", "000000", TRUE, 12, "Franklin Gothic Book");
 						$hoja->setCellValue("L{$row_print}", $row['caja3']);
 						$hoja->setCellValue("M{$row_print}", $row['pz3']);
 						$hoja->setCellValue("N{$row_print}", $row['ped3']);
 						$this->cellStyle("N{$row_print}", "D4EAEF", "000000", TRUE, 12, "Franklin Gothic Book");
 						$hoja->setCellValue("O{$row_print}", $row['caja4']);
 						$hoja->setCellValue("P{$row_print}", $row['pz4']);
 						$hoja->setCellValue("Q{$row_print}", $row['ped4']);
 						$this->cellStyle("Q{$row_print}", "D4EAEF", "000000", TRUE, 12, "Franklin Gothic Book");
 						$hoja->setCellValue("R{$row_print}", $row['caja5']);
 						$hoja->setCellValue("S{$row_print}", $row['pz5']);
 						$hoja->setCellValue("T{$row_print}", $row['ped5']);
 						$this->cellStyle("T{$row_print}", "D4EAEF", "000000", TRUE, 12, "Franklin Gothic Book");
						$hoja->setCellValue("U{$row_print}", $row['caja6']);
 						$hoja->setCellValue("V{$row_print}", $row['pz6']);
 						$hoja->setCellValue("W{$row_print}", $row['ped6']);
 						$this->cellStyle("W{$row_print}", "D4EAEF", "000000", TRUE, 12, "Franklin Gothic Book");

 						$hoja->setCellValue("X{$row_print}", $row['caja7']);
 						$hoja->setCellValue("Y{$row_print}", $row['pz7']);
 						$hoja->setCellValue("Z{$row_print}", $row['ped7']);
 						$this->cellStyle("Z{$row_print}", "D4EAEF", "000000", TRUE, 12, "Franklin Gothic Book");
 						$hoja->setCellValue("AA{$row_print}", $row['caja8']);
 						$hoja->setCellValue("AB{$row_print}", $row['pz8']);
 						$hoja->setCellValue("AC{$row_print}", $row['ped8']);
 						$this->cellStyle("AC{$row_print}", "D4EAEF", "000000", TRUE, 12, "Franklin Gothic Book");
						$hoja->setCellValue("AD{$row_print}", $row['caja9']);
 						$hoja->setCellValue("AE{$row_print}", $row['pz9']);
 						$hoja->setCellValue("AF{$row_print}", $row['ped9']);
 						$this->cellStyle("AF{$row_print}", "D4EAEF", "000000", TRUE, 12, "Franklin Gothic Book");


						$hoja->getStyle("C{$row_print}")->applyFromArray($border_style);
						$hoja->getStyle("D{$row_print}")->applyFromArray($border_style);
						$hoja->getStyle("E{$row_print}")->applyFromArray($border_style);
						$hoja->getStyle("F{$row_print}")->applyFromArray($border_style);
						$hoja->getStyle("G{$row_print}")->applyFromArray($border_style);
						$hoja->getStyle("H{$row_print}")->applyFromArray($border_style);
						$hoja->getStyle("I{$row_print}")->applyFromArray($border_style);
						$hoja->getStyle("J{$row_print}")->applyFromArray($border_style);
						$hoja->getStyle("K{$row_print}")->applyFromArray($border_style);
						$hoja->getStyle("L{$row_print}")->applyFromArray($border_style);
						$hoja->getStyle("M{$row_print}")->applyFromArray($border_style);
						$hoja->getStyle("N{$row_print}")->applyFromArray($border_style);
						$hoja->getStyle("O{$row_print}")->applyFromArray($border_style);
						$hoja->getStyle("P{$row_print}")->applyFromArray($border_style);
						$hoja->getStyle("Q{$row_print}")->applyFromArray($border_style);
						$hoja->getStyle("R{$row_print}")->applyFromArray($border_style);
						$hoja->getStyle("S{$row_print}")->applyFromArray($border_style);
						$hoja->getStyle("T{$row_print}")->applyFromArray($border_style);
						$hoja->getStyle("U{$row_print}")->applyFromArray($border_style);
						$hoja->getStyle("V{$row_print}")->applyFromArray($border_style);
						$hoja->getStyle("W{$row_print}")->applyFromArray($border_style);

						$hoja->getStyle("X{$row_print}")->applyFromArray($border_style);
						$hoja->getStyle("Y{$row_print}")->applyFromArray($border_style);
						$hoja->getStyle("Z{$row_print}")->applyFromArray($border_style);
						$hoja->getStyle("AA{$row_print}")->applyFromArray($border_style);
						$hoja->getStyle("AB{$row_print}")->applyFromArray($border_style);
						$hoja->getStyle("AC{$row_print}")->applyFromArray($border_style);
						$hoja->getStyle("AD{$row_print}")->applyFromArray($border_style);
						$hoja->getStyle("AE{$row_print}")->applyFromArray($border_style);
						$hoja->getStyle("AF{$row_print}")->applyFromArray($border_style);
						$row_print ++;
					}
				}
			}
		}
		$file_name = "EXISTENCIAS PRODUCTOS NO COTIZADOS.xlsx"; //Nombre del documento con extención
		header("Content-Type: application/vnd.ms-excel; charset=utf-8");
		header("Content-Disposition: attachment;filename=".$file_name);
		header("Cache-Control: max-age=0");
		$excel_Writer = PHPExcel_IOFactory::createWriter($this->excelfile, "Excel2007");
		$excel_Writer->save("php://output");
	}
	public function upload_fullpedidos($idesp){
		$fecha = new DateTime(date('Y-m-d H:i:s'));
		$intervalo = new DateInterval('P2D');
		$fecha->add($intervalo);
		$this->load->library("excelfile");
		ini_set("memory_limit", -1);
		$file = $_FILES["file_cotizaciones"]["tmp_name"];
		$sheet = PHPExcel_IOFactory::load($file);
		$objExcel = PHPExcel_IOFactory::load($file);
		$sheet = $objExcel->getSheet(0);
		$num_rows = $sheet->getHighestDataRow();
		if($idesp === "0"){
			$tienda = $this->session->userdata('id_usuario');
		}else{
			$tienda = $idesp;
		}
		$cfile =  $this->usua_mdl->get(NULL, ['id_usuario' => $tienda])[0];
		$nams = preg_replace('/\s+/', '_', $cfile->nombre);
		$filen = "Pedidos".$nams."".rand();
		$config['upload_path']          = './assets/uploads/pedidos/';
        $config['allowed_types']        = 'xlsx|xls';
        $config['max_size']             = 10000;
        $config['max_width']            = 10024;
        $config['max_height']           = 7608;
        $config['max_height']           = 7068;
        $this->load->library('upload', $config);
        $this->upload->initialize($config);
        $this->upload->do_upload('file_cotizaciones',$filen);
		for ($i=3; $i<=$num_rows; $i++) {
			$productos = $this->prod_mdl->get("id_producto",['codigo'=> htmlspecialchars($sheet->getCell('F'.$i)->getValue(), ENT_QUOTES, 'UTF-8')])[0];
			if (sizeof($productos) > 0) {
				$exis = $this->ex_mdl->get(NULL,["WEEKOFYEAR(fecha_registro)" => $this->weekNumber($fecha->format('Y-m-d H:i:s')),"id_tienda"=>$TIENDAnda,"id_producto"=>$productos->id_producto])[0];
				$column_one=0; $column_two=0; $column_three=0;
				$column_one = $sheet->getCell('C'.$i)->getValue() == "" ? 0 : $sheet->getCell('C'.$i)->getValue();
				$column_two = $sheet->getCell('D'.$i)->getValue() == "" ? 0 : $sheet->getCell('D'.$i)->getValue();
				$column_three = $sheet->getCell('C'.$i)->getValue() == "" ? 0 : $sheet->getCell('C'.$i)->getValue();
				$new_existencias[$i]=[
					"id_producto"			=>	$productos->id_producto,
					"id_tienda"			=>	$tienda,
					"cajas"			=>	$column_one,
					"piezas"			=>	$column_two,
					"pedido"	=>	$column_three,
					"fecha_registro"	=>	$fecha->format('Y-m-d H:i:s')
				];
				if($exis){
					$data['cotizacion']=$this->ex_mdl->update($new_existencias[$i], ['id_pedido' => $exis->id_pedido]);
				}else{
					$data['cotizacion']=$this->ex_mdl->insert($new_existencias[$i]);
				}
			}
		}
		if (sizeof($new_existencias) > 0) {
			$aprov = $this->usua_mdl->get(NULL, ['id_usuario'=>$tienda])[0];
			$cambios=[
					"id_usuario"		=>	$this->session->userdata('id_usuario'),
					"fecha_cambio"		=>	date("Y-m-d H:i:s"),
					"antes"			=>	"El usuario sube archivo de pedidos de la tienda ".$aprov->nombre,
					"despues"			=>	"assets/uploads/pedidos/".$filen.".xlsx",
					"accion"			=>	"Sube Pedidos"
				];
			$data['cambios']=$this->cambio_md->insert($cambios);
			$mensaje=[	"id"	=>	'Éxito',
						"desc"	=>	'Pedidos cargados correctamente en el Sistema',
						"type"	=>	'success'];
		}else{
			$mensaje=[	"id"	=>	'Error',
						"desc"	=>	'Los Pedidos no se cargaron al Sistema',
						"type"	=>	'error'];
		}
		$this->jsonResponse($mensaje);
	}
	public function fill_directos($directos){
		ini_set("memory_limit", "-1");
		ini_set("max_execution_time", "-1");
		$this->load->library("excelfile");
		$styleArray = array(
	        'alignment' => array(
	            'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_LEFT,
	        ),
	        'borders' => array('right' => array('style' =>
			PHPExcel_Style_Border::BORDER_THIN,'color' => array('argb' => '000000'),))
	    );
		$hoja = $this->excelfile->getActiveSheet();
				$hoja->getDefaultStyle()
		    ->getBorders()
		    ->getTop()
		        ->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
		$hoja->getDefaultStyle()
		    ->getBorders()
		    ->getBottom()
		        ->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
		$hoja->getDefaultStyle()
		    ->getBorders()
		    ->getLeft()
		        ->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
		$hoja->getDefaultStyle()
		    ->getBorders()
		    ->getRight()
		        ->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
		$this->cellStyle("A1:X2", "000000", "FFFFFF", TRUE, 12, "Franklin Gothic Book");
		$border_style= array('borders' => array('right' => array('style' =>
			PHPExcel_Style_Border::BORDER_THIN,'color' => array('argb' => '000000'),)));
		$hoja->setCellValue("A2", "CÓDIGO")->getColumnDimension('A')->setWidth(22); //Nombre y ajuste de texto a la columna
		$hoja->setCellValue("B1", "DESCRIPCIÓN")->getColumnDimension('B')->setWidth(50);
		$hoja->setCellValue("C2", "SISTEMA")->getColumnDimension('C')->setWidth(12);
		$hoja->setCellValue("D2", "PRECIO 4")->getColumnDimension('D')->setWidth(12);
		$hoja->setCellValue("E1", "PRECIO")->getColumnDimension('E')->setWidth(12);
		$hoja->setCellValue("E2", "PROMEDIO")->getColumnDimension('E')->setWidth(12);
		$hoja->setCellValue("F1", "PRECIO")->getColumnDimension('F')->setWidth(12);
		$hoja->setCellValue("F2", "MAXIMO")->getColumnDimension('F')->setWidth(12);
		$col = 6;
		$rws = 3;
		$fecha = new DateTime(date('Y-m-d H:i:s'));
		$intervalo = new DateInterval('P2D');
		$fecha->add($intervalo);
		$fecha = $fecha->format('Y-m-d H:i:s');
		$cotPro = $this->ct_mdl->getCotzP(NULL, $fecha,$directos);
		if ($cotPro){
			foreach ($cotPro as $key => $value){
				$hoja->setCellValueByColumnAndRow($col, 2, $value->nombre);
                $col++;
                $col++;
			}
		}
		$col=6;
		$producto = "";
		$costo = 0;
		$maximo = 0;
		$flag = 0;
		$prueba = "";
		$cotizacionesProveedor = $this->ct_mdl->getCotzD(NULL, $fecha,$directos);
		//$this->jsonResponse($cotizacionesProveedor);
		if ($cotizacionesProveedor) {
			foreach ($cotizacionesProveedor as $key => $value) {
				foreach ($value["articulos"] as $key => $val) {
					$hoja->getStyle("A{$rws}")->applyFromArray($styleArray);
					$hoja->getStyle("B{$rws}")->applyFromArray($border_style);
					$hoja->getStyle("C{$rws}")->applyFromArray($border_style);
					$hoja->getStyle("D{$rws}")->applyFromArray($border_style);
					$hoja->getStyle("E{$rws}")->applyFromArray($border_style);
					$hoja->getStyle("F{$rws}")->applyFromArray($border_style);
					$hoja->getStyle("G{$rws}")->applyFromArray($border_style);
					$hoja->getStyle("H{$rws}")->applyFromArray($border_style);
					$hoja->getStyle("I{$rws}")->applyFromArray($border_style);
					$hoja->getStyle("J{$rws}")->applyFromArray($border_style);
					$hoja->getStyle("K{$rws}")->applyFromArray($border_style);
					$hoja->getStyle("L{$rws}")->applyFromArray($border_style);
					$hoja->getStyle("M{$rws}")->applyFromArray($border_style);
					$hoja->getStyle("N{$rws}")->applyFromArray($border_style);
					$hoja->getStyle("O{$rws}")->applyFromArray($border_style);
					$hoja->getStyle("P{$rws}")->applyFromArray($border_style);
					$hoja->getStyle("Q{$rws}")->applyFromArray($border_style);
					$hoja->getStyle("R{$rws}")->applyFromArray($border_style);
					$hoja->getStyle("S{$rws}")->applyFromArray($border_style);
					$hoja->getStyle("T{$rws}")->applyFromArray($border_style);
					$hoja->getStyle("U{$rws}")->applyFromArray($border_style);
					$hoja->getStyle("W{$rws}")->applyFromArray($border_style);
					$hoja->getStyle("V{$rws}")->applyFromArray($border_style);
					$hoja->getStyle("X{$rws}")->applyFromArray($border_style);
					if ($producto <> $value["producto"]) {
						$hoja->setCellValue("B{$rws}", $value["producto"]);
						$producto = $value["producto"];
						$hoja->setCellValue("A{$rws}", $val["codigo"])->getStyle("A{$rws}")->getNumberFormat()->setFormatCode('# ???/???');
						$hoja->setCellValue("C{$rws}", $val["precio_sistema"])->getStyle("C{$rws}")->getNumberFormat()->setFormatCode('"$"#,##0.00_-');
						$hoja->setCellValue("D{$rws}", $val["precio_four"])->getStyle("D{$rws}")->getNumberFormat()->setFormatCode('"$"#,##0.00_-');
						$prueba = $val["familia"];
					}
					$maximo = $maximo < $val["precio_promocion"] ? $val["precio_promocion"] : $maximo;
					$costo =  $val["precio_promocion"] + $costo;
					for ($i=0; $i < count($cotPro); $i++) {
						if ($cotPro[$i]->nombre == $val["proveedor"]) {
							$hoja->setCellValueByColumnAndRow($col, $rws, $val["precio_promocion"])->getStyleByColumnAndRow($col, $rws)->getNumberFormat()->setFormatCode('"$"#,##0.00_-');
							$col++;
							$hoja->setCellValueByColumnAndRow($col, $rws, $val["observaciones"]);
							$col++;
						}else{
							$col++;
							$col++;
						}
					}
					$col = 6;
				}
				$costo = $costo / count($value["articulos"]);
				$hoja->setCellValue("E{$rws}", $costo)->getStyle("E{$rws}")->getNumberFormat()->setFormatCode('"$"#,##0.00_-');
				$hoja->setCellValue("F{$rws}", $maximo)->getStyle("F{$rws}")->getNumberFormat()->setFormatCode('"$"#,##0.00_-');
				
				$rws++;	
			}
		}
		//$this->jsonResponse($prueba);
        $dias = array("DOMINGO","LUNES","MARTES","MIÉRCOLES","JUEVES","VIERNES","SÁBADO");
		$meses = array("ENERO","FEBRERO","MARZO","ABRIL","MAYO","JUNIO","JULIO","AGOSTO","SEPTIEMBRE","OCTUBRE","NOVIEMBRE","DICIEMBRE");
		$fecha =  $dias[date('w')]." ".date('d')." DE ".$meses[date('n')-1]. " DEL ".date('Y') ;
		$file_name = "DIRECTOS".$prueba." ".$fecha.".xlsx"; //Nombre del documento con extención
		header("Content-Type: application/vnd.ms-excel; charset=utf-8");
		header("Content-Disposition: attachment;filename=".$file_name);
		header("Cache-Control: max-age=0");
		$excel_Writer = PHPExcel_IOFactory::createWriter($this->excelfile, "Excel2007");
		$excel_Writer->save("php://output");
	}
	private function fill_duerazo($cualess){
		$flag =1;
		$flag2=1;
		$array = "";
		$array2 = "";
		$filenam = "";
		$flag1 = 5;
		$excname = "";
		if ($cualess === "SAHUAYO") {
			$array = $this->usua_mdl->getSAH(NULL);	
			$excname = "SAHUAYO";
		}else{
			$array = $this->usua_mdl->getD(NULL);
			$excname = "DUERO - PROCTER";
		}
		ini_set("memory_limit", "-1");
		ini_set("max_execution_time", "-1");
		$this->load->library("excelfile");
		//$this->excelfile = PHPExcel_IOFactory::load("./assets/uploads/template.xlsx");
		$hoja1 = $this->excelfile->setActiveSheetIndex(0);
		$this->excelfile->setActiveSheetIndex(0)->setTitle("EXISTENCIAS");
		$this->excelfile->createSheet();
        $hoja = $this->excelfile->setActiveSheetIndex(1);
        $hoja->setTitle("PEDIDO");
		$styleArray = array(
		  'borders' => array(
		    'allborders' => array(
		      'style' => PHPExcel_Style_Border::BORDER_THIN
		    )
		  )
		);
		$hoja->getColumnDimension('A')->setWidth("20");
		$hoja->getColumnDimension('B')->setWidth("20");
		$hoja->getColumnDimension('C')->setWidth("70");
		$hoja->getColumnDimension('D')->setWidth("7");
		$hoja->getColumnDimension('E')->setWidth("15");
		$hoja->getColumnDimension('F')->setWidth("15");
		$hoja->getColumnDimension('G')->setWidth("8");
		$hoja->getColumnDimension('H')->setWidth("15");
		$hoja->getColumnDimension('I')->setWidth("8");
		$hoja->getColumnDimension('L')->setWidth("20");
		$hoja->getColumnDimension('J')->setWidth("20");
		$hoja->getColumnDimension('K')->setWidth("15");
		$hoja->getColumnDimension('BM')->setWidth("70");
		
		$hoja1->getColumnDimension('A')->setWidth("6");
		$hoja1->getColumnDimension('B')->setWidth("6");
		$hoja1->getColumnDimension('C')->setWidth("6");
		$hoja1->getColumnDimension('D')->setWidth("25");
		$hoja1->getColumnDimension('E')->setWidth("47");
		$hoja1->getColumnDimension('G')->setWidth("50");
		$hoja1->getColumnDimension('F')->setWidth("15");

		foreach ($array as $key => $v3) {
			$this->excelfile->setActiveSheetIndex(0);
			if ($flag > 15) {
				$flag2 = $flag+2;	
			}else{
				$flag2 = $flag;
			}
			
			$hoja1->mergeCells('A'.$flag2.':G'.$flag2);
			$this->cellStyle("A".$flag2."", "FFFFFF", "000000", TRUE, 12, "Franklin Gothic Book");
			$hoja1->setCellValue("A".$flag2."", "GRUPO ABARROTES AZTECA");
			$this->excelfile->getActiveSheet()->getStyle('A'.$flag2.':G'.$flag2.'')->applyFromArray($styleArray);
			$flag2++;
			$hoja1->mergeCells('A'.$flag2.':G'.$flag2.'');
			$this->cellStyle("A".$flag2."", "FFFFFF", "000000", TRUE, 12, "Franklin Gothic Book");
			$hoja1->setCellValue("A".$flag2."", "PEDIDOS A '".$v3->nombre."' ".date("d-m-Y"));
			$this->excelfile->getActiveSheet()->getStyle('A'.$flag2.':G'.$flag2.'')->applyFromArray($styleArray);
			$flag2++;
			$this->cellStyle("A".$flag2.":D".$flag2."", "000000", "FFFFFF", TRUE, 12, "Franklin Gothic Book");
			$hoja1->mergeCells('A'.$flag2.':B'.$flag2.'');
			$hoja1->setCellValue("A".$flag2."", "EXISTENCIAS");
			$hoja1->setCellValue("E".$flag2."", "DESCRIPCIÓN");
			$this->cellStyle("E".$flag2."", "000000", "FFFFFF", TRUE, 12, "Franklin Gothic Book");
			$this->excelfile->getActiveSheet()->getStyle('A'.$flag2.':G'.$flag2.'')->applyFromArray($styleArray);

			$this->cellStyle("H".$flag2."", "000000", "FFFFFF", TRUE, 12, "Franklin Gothic Book");
			$hoja1->setCellValue("H".$flag2."", "PENDIENT");
			$this->cellStyle("I".$flag2."", "000000", "FFFFFF", TRUE, 12, "Franklin Gothic Book");
			$hoja1->setCellValue("I".$flag2."", "PENDIENT");
			$this->cellStyle("J".$flag2."", "000000", "FFFFFF", TRUE, 12, "Franklin Gothic Book");
			$hoja1->setCellValue("J".$flag2."", "PENDIENT");
			$this->cellStyle("K".$flag2."", "000000", "FFFFFF", TRUE, 12, "Franklin Gothic Book");
			$hoja1->setCellValue("K".$flag2."", "PENDIENT");
			$this->cellStyle("L".$flag2."", "000000", "FFFFFF", TRUE, 12, "Franklin Gothic Book");
			$hoja1->setCellValue("L".$flag2."", "PENDIENT");
			$this->cellStyle("M".$flag2."", "000000", "FFFFFF", TRUE, 12, "Franklin Gothic Book");
			$hoja1->setCellValue("M".$flag2."", "PENDIENT");
			$this->cellStyle("N".$flag2."", "000000", "FFFFFF", TRUE, 12, "Franklin Gothic Book");
			$hoja1->setCellValue("N".$flag2."", "PENDIENT");
			$this->cellStyle("O".$flag2."", "000000", "FFFFFF", TRUE, 12, "Franklin Gothic Book");
			$hoja1->setCellValue("O".$flag2."", "PENDIENT");
			$this->cellStyle("P".$flag2."", "000000", "FFFFFF", TRUE, 12, "Franklin Gothic Book");
			$hoja1->setCellValue("P".$flag2."", "PENDIENT");
			

			$flag2++;
			$this->cellStyle("A".$flag2.":G".$flag2."", "000000", "FFFFFF", TRUE, 12, "Franklin Gothic Book");
			$hoja1->setCellValue("A".$flag2."", "CAJAS");
			$hoja1->setCellValue("B".$flag2."", "PZAS");
			$hoja1->setCellValue("C".$flag2."", "PEDIDO");
			$hoja1->setCellValue("D".$flag2."", "CÓDIGO");
			$hoja1->setCellValue("G".$flag2."", "PROMOCIÓN");

			$this->cellStyle("H".$flag2, "C00000", "000000", TRUE, 10, "Franklin Gothic Book");
			$this->cellStyle("I".$flag2, "01B0F0", "000000", TRUE, 10, "Franklin Gothic Book");
			$this->cellStyle("J".$flag2, "FF0000", "000000", TRUE, 10, "Franklin Gothic Book");
			$this->cellStyle("K".$flag2, "E26C0B", "000000", TRUE, 10, "Franklin Gothic Book");
			$this->cellStyle("L".$flag2, "C5C5C5", "000000", TRUE, 10, "Franklin Gothic Book");
			$this->cellStyle("M".$flag2, "92D051", "000000", TRUE, 10, "Franklin Gothic Book");
			$this->cellStyle("N".$flag2, "B1A0C7", "000000", TRUE, 10, "Franklin Gothic Book");
			$this->cellStyle("O".$flag2, "DA9694", "000000", TRUE, 10, "Franklin Gothic Book");
			$this->cellStyle("P".$flag2, "4CACC6", "000000", TRUE, 10, "Franklin Gothic Book");
			$hoja1->setCellValue("H".$flag2."", "CEDIS");
			$hoja1->setCellValue("I".$flag2."", "ABARROTES");
			$hoja1->setCellValue("J".$flag2."", "VILLAS");
			$hoja1->setCellValue("K".$flag2."", "TIENDA");
			$hoja1->setCellValue("L".$flag2."", "ULTRA");
			$hoja1->setCellValue("M".$flag2."", "TRINCHERAS");
			$hoja1->setCellValue("N".$flag2."", "MERCADO");
			$hoja1->setCellValue("O".$flag2."", "TENENCIA");
			$hoja1->setCellValue("P".$flag2."", "TIJERAS");

			$this->excelfile->setActiveSheetIndex(1);
			
			$this->cellStyle("A".$flag, "FFFFFF", "000000", TRUE, 12, "Franklin Gothic Book");
			$hoja->setCellValue("A".$flag, "CEDIS,CD INDUSTRIAL, ABARROTES, TIENDA, ULTRAMARINOS, TRINCHERAS, MERCADO, TIJERAS, Y TENENCIA AZTECA AUTOSERVICIOS SA. DE CV.");
			$hoja->mergeCells('A'.$flag.':BM'.$flag);
			$this->excelfile->getActiveSheet()->getStyle('A'.$flag.':BM'.$flag)->applyFromArray($styleArray);
			$flag++;
			$hoja->mergeCells('A'.$flag.':L'.$flag);
			$hoja->mergeCells('M'.$flag.':Q'.$flag);
			$hoja->mergeCells('R'.$flag.':U'.$flag);
			$hoja->mergeCells('V'.$flag.':X'.$flag);
			$hoja->mergeCells('Y'.$flag.':AC'.$flag);
			$hoja->mergeCells('AD'.$flag.':AH'.$flag);
			$hoja->mergeCells('AI'.$flag.':AM'.$flag);
			$hoja->mergeCells('AN'.$flag.':AR'.$flag);
			$hoja->mergeCells('AS'.$flag.':AW'.$flag);
			$hoja->mergeCells('AX'.$flag.':BB'.$flag);
			$hoja->mergeCells('BC'.$flag.':BG'.$flag);
			$hoja->mergeCells('BH'.$flag.':BL'.$flag);
			$this->cellStyle("A".$flag, "FFFFFF", "000000", TRUE, 12, "Franklin Gothic Book");
			$hoja->setCellValue("A".$flag, "PEDIDOS A '".$v3->nombre."' ".date("d-m-Y"));
			$this->cellStyle("M".$flag, "C00000", "000000", TRUE, 12, "Franklin Gothic Book");
			$hoja->setCellValue("M".$flag, "CEDIS");
			$this->cellStyle("R".$flag, "FF0066", "000000", TRUE, 12, "Franklin Gothic Book");
			$hoja->setCellValue("R".$flag, "SUPER INDUSTRIAL");
			$this->cellStyle("V".$flag, "C2B90A", "000000", TRUE, 12, "Franklin Gothic Book");
			$hoja->setCellValue("V".$flag, "SUMA CEDIS");
			$this->cellStyle("Y".$flag, "01B0F0", "000000", TRUE, 12, "Franklin Gothic Book");
			$hoja->setCellValue("Y".$flag, "ABARROTES");
			$this->cellStyle("AD".$flag, "FF0000", "000000", TRUE, 12, "Franklin Gothic Book");
			$hoja->setCellValue("AD".$flag, "VILLAS");
			$this->cellStyle("AI".$flag, "E26C0B", "000000", TRUE, 12, "Franklin Gothic Book");
			$hoja->setCellValue("AI".$flag, "TIENDA");
			$this->cellStyle("AN".$flag, "C5C5C5", "000000", TRUE, 12, "Franklin Gothic Book");
			$hoja->setCellValue("AN".$flag, "ULTRAMARINOS");
			$this->cellStyle("AS".$flag, "92D051", "000000", TRUE, 12, "Franklin Gothic Book");
			$hoja->setCellValue("AS".$flag, "TRINCHERAS");
			$this->cellStyle("AX".$flag, "B1A0C7", "000000", TRUE, 12, "Franklin Gothic Book");
			$hoja->setCellValue("AX".$flag, "AZT MERCADO");
			$this->cellStyle("BC".$flag, "DA9694", "000000", TRUE, 12, "Franklin Gothic Book");
			$hoja->setCellValue("BC".$flag, "TENENCIA");
			$this->cellStyle("BH".$flag, "4CACC6", "000000", TRUE, 12, "Franklin Gothic Book");
			$hoja->setCellValue("BH".$flag, "TIJERAS");
			
			$this->cellStyle("A3:BM4", "000000", "FFFFFF", TRUE, 12, "Franklin Gothic Book");
			$this->excelfile->getActiveSheet()->getStyle('A'.$flag.':BM'.$flag)->applyFromArray($styleArray);
			$flag++;
			$hoja->mergeCells('A'.$flag.':L'.$flag);
			$hoja->mergeCells('M'.$flag.':Q'.$flag);
			$hoja->mergeCells('R'.$flag.':U'.$flag);
			$hoja->mergeCells('V'.$flag.':X'.$flag);
			$hoja->mergeCells('Y'.$flag.':AC'.$flag);
			$hoja->mergeCells('AD'.$flag.':AH'.$flag);
			$hoja->mergeCells('AI'.$flag.':AM'.$flag);
			$hoja->mergeCells('AN'.$flag.':AR'.$flag);
			$hoja->mergeCells('AS'.$flag.':AW'.$flag);
			$hoja->mergeCells('AX'.$flag.':BB'.$flag);
			$hoja->mergeCells('BC'.$flag.':BG'.$flag);
			$hoja->mergeCells('BH'.$flag.':BL'.$flag);
			$this->cellStyle("A".$flag.":BM".$flag."", "000000", "FFFFFF", TRUE, 12, "Franklin Gothic Book");
			$hoja->setCellValue("A".$flag, "DESCRIPCIÓN");
			$hoja->setCellValue("M".$flag, "EXISTENCIAS");
			$hoja->setCellValue("R".$flag, "EXISTENCIAS");
			$hoja->setCellValue("V".$flag, "EXISTENCIAS");
			$hoja->setCellValue("Y".$flag, "EXISTENCIAS");
			$hoja->setCellValue("AD".$flag, "EXISTENCIAS");
			$hoja->setCellValue("AI".$flag, "EXISTENCIAS");
			$hoja->setCellValue("AN".$flag, "EXISTENCIAS");
			$hoja->setCellValue("AS".$flag, "EXISTENCIAS");
			$hoja->setCellValue("AX".$flag, "EXISTENCIAS");
			$hoja->setCellValue("BC".$flag, "EXISTENCIAS");
			$hoja->setCellValue("BH".$flag, "EXISTENCIAS");

			//Begin: TOTALES PEDIDOS PENDIENTES
			$hoja->mergeCells('CA'.$flag.':CK'.$flag);
			$this->cellStyle("CA".$flag.":CK".$flag."", "000000", "FFFFFF", TRUE, 12, "Franklin Gothic Book");
			$hoja->setCellValue("CA".$flag, "TOTAL POR PEDIDOS PENDIENTES");
			//End: TOTALES PEDIDOS PENDIENTES

			$flag++;
			$this->cellStyle("A".$flag.":BM".$flag."", "000000", "FFFFFF", TRUE, 12, "Franklin Gothic Book");
			$hoja->setCellValue("A".$flag, "CÓDIGO");
			$hoja->setCellValue("B".$flag, "FACTURA");
			$hoja->setCellValue("D".$flag, "UM");
			$hoja->setCellValue("E".$flag, "REALES");
			$hoja->setCellValue("F".$flag, "COSTO");
			$hoja->setCellValue("H".$flag, "SISTEMA");
			$hoja->setCellValue("J".$flag, "PRECIO4");
			$hoja->setCellValue("K".$flag, "2DO");
			$hoja->setCellValue("L".$flag, "PROVEEDOR");
			$hoja->setCellValue("M".$flag, "CAJAS");
			$hoja->setCellValue("N".$flag, "PZAS");
			$hoja->setCellValue("O".$flag, "PEND");
			$hoja->setCellValue("P".$flag, "SUGS");
			$hoja->setCellValue("Q".$flag, "PEDIDO");
			$hoja->setCellValue("R".$flag, "CAJAS");
			$hoja->setCellValue("S".$flag, "PZAS");
			$hoja->setCellValue("T".$flag, "SUGS");
			$hoja->setCellValue("U".$flag, "PEDIDO");
			$hoja->setCellValue("V".$flag, "CAJAS");
			$hoja->setCellValue("W".$flag, "PZAS");
			$hoja->setCellValue("X".$flag, "PEDIDO");
			$hoja->setCellValue("Y".$flag, "CAJAS");
			$hoja->setCellValue("Z".$flag, "PZAS");
			$hoja->setCellValue("AA".$flag, "PEND");
			$hoja->setCellValue("AB".$flag, "SUGS");
			$hoja->setCellValue("AC".$flag, "PEDIDO");

			$hoja->setCellValue("AD".$flag, "CAJAS");
			$hoja->setCellValue("AE".$flag, "PZAS");
			$hoja->setCellValue("AF".$flag, "PEND");
			$hoja->setCellValue("AG".$flag, "SUGS");
			$hoja->setCellValue("AH".$flag, "PEDIDO");
			$hoja->setCellValue("AI".$flag, "CAJAS");
			$hoja->setCellValue("AJ".$flag, "PZAS");
			$hoja->setCellValue("AK".$flag, "PEND");
			$hoja->setCellValue("AL".$flag, "SUGS");
			$hoja->setCellValue("AM".$flag, "PEDIDO");
			$hoja->setCellValue("AD".$flag, "CAJAS");
			$hoja->setCellValue("AE".$flag, "PZAS");
			$hoja->setCellValue("AF".$flag, "PEND");
			$hoja->setCellValue("AG".$flag, "SUGS");
			$hoja->setCellValue("AH".$flag, "PEDIDO");
			$hoja->setCellValue("AI".$flag, "CAJAS");
			$hoja->setCellValue("AJ".$flag, "PZAS");
			$hoja->setCellValue("AK".$flag, "PEND");
			$hoja->setCellValue("AL".$flag, "SUGS");
			$hoja->setCellValue("AM".$flag, "PEDIDO");
			$hoja->setCellValue("AN".$flag, "CAJAS");
			$hoja->setCellValue("AO".$flag, "PZAS");
			$hoja->setCellValue("AP".$flag, "PEND");
			$hoja->setCellValue("AQ".$flag, "SUGS");
			$hoja->setCellValue("AR".$flag, "PEDIDO");
			$hoja->setCellValue("AS".$flag, "CAJAS");
			$hoja->setCellValue("AT".$flag, "PZAS");
			$hoja->setCellValue("AU".$flag, "PEND");
			$hoja->setCellValue("AV".$flag, "SUGS");
			$hoja->setCellValue("AW".$flag, "PEDIDO");
			$hoja->setCellValue("AX".$flag, "CAJAS");
			$hoja->setCellValue("AY".$flag, "PZAS");
			$hoja->setCellValue("AZ".$flag, "PEND");
			$hoja->setCellValue("BA".$flag, "SUGS");
			$hoja->setCellValue("BB".$flag, "PEDIDO");
			$hoja->setCellValue("BC".$flag, "CAJAS");
			$hoja->setCellValue("BD".$flag, "PZAS");
			$hoja->setCellValue("BE".$flag, "PEND");
			$hoja->setCellValue("BF".$flag, "SUGS");
			$hoja->setCellValue("BG".$flag, "PEDIDO");
			$hoja->setCellValue("BH".$flag, "CAJAS");
			$hoja->setCellValue("BI".$flag, "PZAS");
			$hoja->setCellValue("BJ".$flag, "PEND");
			$hoja->setCellValue("BK".$flag, "SUGS");
			$hoja->setCellValue("BL".$flag, "PEDIDO");
			
			$hoja->setCellValue("BM".$flag, "PROMOCION");
			$hoja->setCellValue("BW".$flag, "TOTAL");
			$hoja->setCellValue("BX".$flag, "PEDIDOS");
			$this->cellStyle("BN".$flag, "C00000", "000000", TRUE, 12, "Franklin Gothic Book");
			$this->cellStyle("BO".$flag, "01B0F0", "000000", TRUE, 12, "Franklin Gothic Book");
			$this->cellStyle("BP".$flag, "FF0000", "000000", TRUE, 12, "Franklin Gothic Book");
			$this->cellStyle("BQ".$flag, "E26C0B", "000000", TRUE, 12, "Franklin Gothic Book");
			$this->cellStyle("BR".$flag, "C5C5C5", "000000", TRUE, 12, "Franklin Gothic Book");
			$this->cellStyle("BS".$flag, "92D051", "000000", TRUE, 12, "Franklin Gothic Book");
			$this->cellStyle("BT".$flag, "B1A0C7", "000000", TRUE, 12, "Franklin Gothic Book");
			$this->cellStyle("BU".$flag, "DA9694", "000000", TRUE, 12, "Franklin Gothic Book");
			$this->cellStyle("BV".$flag, "4CACC6", "000000", TRUE, 12, "Franklin Gothic Book");
			$this->cellStyle("BW".$flag."", "000000", "FFFFFF", TRUE, 12, "Franklin Gothic Book");
			$this->cellStyle("BX".$flag."", "000000", "FFFFFF", TRUE, 12, "Franklin Gothic Book");

			//Begin: TOTALES PEDIDOS PENDIENTES
			$this->cellStyle("CA".$flag, "C00000", "000000", TRUE, 12, "Franklin Gothic Book");
			$this->cellStyle("CB".$flag, "01B0F0", "000000", TRUE, 12, "Franklin Gothic Book");
			$this->cellStyle("CC".$flag, "FF0000", "000000", TRUE, 12, "Franklin Gothic Book");
			$this->cellStyle("CD".$flag, "E26C0B", "000000", TRUE, 12, "Franklin Gothic Book");
			$this->cellStyle("CE".$flag, "C5C5C5", "000000", TRUE, 12, "Franklin Gothic Book");
			$this->cellStyle("CF".$flag, "92D051", "000000", TRUE, 12, "Franklin Gothic Book");
			$this->cellStyle("CG".$flag, "B1A0C7", "000000", TRUE, 12, "Franklin Gothic Book");
			$this->cellStyle("CH".$flag, "DA9694", "000000", TRUE, 12, "Franklin Gothic Book");
			$this->cellStyle("CI".$flag, "4CACC6", "000000", TRUE, 12, "Franklin Gothic Book");
			$this->cellStyle("CJ".$flag."", "000000", "FFFFFF", TRUE, 12, "Franklin Gothic Book");
			$this->cellStyle("CK".$flag."", "000000", "FFFFFF", TRUE, 12, "Franklin Gothic Book");
			$hoja->setCellValue("CJ".$flag, "TOTAL");
			$hoja->setCellValue("CK".$flag, "PEDIDOS");
			//End: TOTALES PEDIDOS PENDIENTES

			$this->excelfile->getActiveSheet()->getStyle('BM'.$flag)->applyFromArray($styleArray);
			$fecha = new DateTime(date('Y-m-d H:i:s'));
			$where=["ctz_first.id_proveedor" => $v3->id_usuario,"prod.estatus" => 1];//Semana actual
			$intervalo = new DateInterval('P2D');
			$fecha->add($intervalo);
			$cotizacionesProveedor = $this->ct_mdl->getPedidosAll($where, $fecha->format('Y-m-d H:i:s'), 0);
			$bandera = $flag;
			if($cotizacionesProveedor){
				foreach ($cotizacionesProveedor as $key => $value) {
					$this->excelfile->setActiveSheetIndex(0);
					$this->cellStyle("E".$flag1, "000000", "FFFFFF", FALSE, 12, "Franklin Gothic Book");
					$hoja1->setCellValue("E".$flag1, $value['familia']);
					$flag1 +=1;
					//Pedidos
					$this->excelfile->setActiveSheetIndex(1);
					$this->cellStyle("C".$flag, "000000", "FFFFFF", FALSE, 12, "Franklin Gothic Book");
					$hoja->setCellValue("C{$flag}", $value['familia']);
					$flag +=1;
					if ($value['articulos']) {
						foreach ($value['articulos'] as $key => $row){
							if($row["unidad"] === null || $row["unidad"] === ""){
								$row["unidad"] = 1;
							}
							$registrazo = date('Y-m-d',strtotime($row['registrazo']));
							$this->excelfile->setActiveSheetIndex(0);
							$this->cellStyle("A".$flag1.":P".$flag1, "FFFFFF", "000000", FALSE, 12, "Franklin Gothic Book");
							
							$hoja1->setCellValue("D{$flag1}", $row['codigo'])->getStyle("D{$flag1}")->getNumberFormat()->setFormatCode('# ???/???');//Formato de fraccion
							if($row['color'] == '#92CEE3'){
								$this->cellStyle("D{$flag1}", "92CEE3", "000000", FALSE, 12, "Franklin Gothic Book");
							}else{
								$this->cellStyle("D{$flag1}", "FFFFFF", "000000", FALSE, 12, "Franklin Gothic Book");
							}
							$hoja1->setCellValue("E{$flag1}", $row['producto']);
							$hoja1->setCellValue("G{$flag1}", $row['promocion_first']);
							$hoja1->setCellValue("H{$flag1}", $row['cedis']);
							$hoja1->setCellValue("I{$flag1}", $row['abarrotes']);
							$hoja1->setCellValue("J{$flag1}", $row['pedregal']);
							$hoja1->setCellValue("K{$flag1}", $row['tienda']);
							$hoja1->setCellValue("L{$flag1}", $row['ultra']);
							$hoja1->setCellValue("M{$flag1}", $row['trincheras']);
							$hoja1->setCellValue("N{$flag1}", $row['mercado']);
							$hoja1->setCellValue("O{$flag1}", $row['tenencia']);
							$hoja1->setCellValue("P{$flag1}", $row['tijeras']);

							if ($row["imagen"] <> "" && !is_null($row["imagen"]) ) {
								$objDrawing = new PHPExcel_Worksheet_Drawing();
								$objDrawing->setName('COD'.$row['producto']);
								$objDrawing->setDescription('DESC'.$row['codigo']);
								$objDrawing->setPath("./Abarrotes/assets/img/productos/".$row["imagen"]."");
								$objDrawing->setWidth(50);
								$objDrawing->setHeight(50);
								$objDrawing->setCoordinates('F'.$flag1);
								$objDrawing->setOffsetX(5); 
								$objDrawing->setOffsetY(5);
								//$objDrawing->setUrl('http://abarrotesazteca.com/Abarrotes/assets/img/productos/'.$row["imagen"]);
								$objDrawing->setWorksheet($this->excelfile->getActiveSheet());
								$this->excelfile->getActiveSheet()->getRowDimension($flag1)->setRowHeight(60);
								$this->excelfile->getActiveSheet()->getStyleByColumnAndRow(10, $flag1)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_DATE_XLSX14);
								$this->excelfile->getActiveSheet()->getCell('F'.$flag1)->getHyperlink()->setUrl('http://abarrotesazteca.com/Abarrotes/assets/img/productos/'.str_replace("_thumb.",".",$row["imagen"]));
							}

							$hoja1->getStyle("A{$flag1}:P{$flag1}")
					                 ->getAlignment()
					                 ->setHorizontal(\PHPExcel_Style_Alignment::HORIZONTAL_LEFT);
					         
			                
							//Pedidos
							$this->excelfile->setActiveSheetIndex(1);
							$this->cellStyle("A".$flag.":BL".$flag."", "FFFFFF", "000000", FALSE, 12, "Franklin Gothic Book");
							
							$hoja->setCellValue("A{$flag}", $row['codigo'])->getStyle("A{$flag}")->getNumberFormat()->setFormatCode('# ???/???');//Formato de fraccion
							$hoja->setCellValue("B{$flag}", $row['codigo_factura'])->getStyle("B{$flag}")->getNumberFormat()->setFormatCode('# ???/???');//Formato de fraccion
							if($row['color'] == '#92CEE3'){
								$this->cellStyle("A{$flag}", "92CEE3", "000000", FALSE, 12, "Franklin Gothic Book");
							}else{
								$this->cellStyle("A{$flag}", "FFFFFF", "000000", FALSE, 12, "Franklin Gothic Book");
							}
							$hoja->setCellValue("C{$flag}", $row['producto']);
							$hoja->setCellValue("D{$flag}", $row['unidad']);
							
							

							$this->cellStyle("E{$flag}", "FFFFFF", "000000", FALSE, 12, "Franklin Gothic Book");
							$hoja->setCellValue("E{$flag}", $row['reales'])->getStyle("E{$flag}")->getNumberFormat()->setFormatCode('"$"#,##0.00_-');
							if(number_format(($row['precio_first'] - $row['reales']),2) === "0.01" || number_format(($row['precio_first'] - $row['reales']),2) === "-0.01"){
								$this->cellStyle("E{$flag}", "FFFFFF", "000000", FALSE, 12, "Franklin Gothic Book");
							}elseif($row['precio_first'] < $row['reales']){
								$this->cellStyle("E{$flag}", "FFFFFF", "E21600", FALSE, 12, "Franklin Gothic Book");
							}else{
								$this->cellStyle("E{$flag}", "FFFFFF", "249947", FALSE, 12, "Franklin Gothic Book");
							}

							if (number_format(($row['precio_sistema'] - $row['precio_first']),2) === "0.01" || number_format(($row['precio_sistema'] - $row['precio_first']),2) === "-0.01") {
								$hoja->setCellValue("F{$flag}", $row['precio_first'])->getStyle("F{$flag}")->getNumberFormat()->setFormatCode('"$"#,##0.00_-');
								$this->cellStyle("F{$flag}", "FFFFFF", "000000", FALSE, 12, "Franklin Gothic Book");
								$this->cellStyle("F{$flag}", "FFFFFF", "000000", FALSE, 12, "Franklin Gothic Book");
								$this->cellStyle("C{$flag}", "FFFFFF", "000000", FALSE, 12, "Franklin Gothic Book");
							}elseif($row['precio_sistema'] < $row['precio_first']){
								$hoja->setCellValue("F{$flag}", $row['precio_first'])->getStyle("F{$flag}")->getNumberFormat()->setFormatCode('"$"#,##0.00_-');
								$this->cellStyle("F{$flag}", "FDB2B2", "E21111", FALSE, 12, "Franklin Gothic Book");
								$this->cellStyle("F{$flag}", "FDB2B2", "E21111", FALSE, 12, "Franklin Gothic Book");
								$this->cellStyle("C{$flag}", "E21600", "000000", FALSE, 12, "Franklin Gothic Book");
							}else{
								$hoja->setCellValue("F{$flag}", $row['precio_first'])->getStyle("F{$flag}")->getNumberFormat()->setFormatCode('"$"#,##0.00_-');
								$this->cellStyle("F{$flag}", "96EAA8", "0C800C", FALSE, 12, "Franklin Gothic Book");
								$this->cellStyle("C{$flag}", "249947", "000000", FALSE, 12, "Franklin Gothic Book");
							}

							$hoja->setCellValue("H{$flag}", $row['precio_sistema'])->getStyle("H{$flag}")->getNumberFormat()->setFormatCode('"$"#,##0.00_-');//Formto de moneda
							
							$this->cellStyle("H".$flag, "FFFFFF","000000",  FALSE, 12, "Franklin Gothic Book");
							if($row['colorp'] == 1){
								$this->cellStyle("H{$flag}", "D6DCE4", "000000", FALSE, 12, "Franklin Gothic Book");
							}else{
								$this->cellStyle("H{$flag}", "FFFFFF", "000000", FALSE, 12, "Franklin Gothic Book");
							}

							$hoja->setCellValue("J{$flag}", $row['precio_four'])->getStyle("J{$flag}")->getNumberFormat()->setFormatCode('"$"#,##0.00_-');
							$this->cellStyle("J{$flag}", "FFFFFF", "000000", FALSE, 12, "Franklin Gothic Book");
							if($row['precio_sistema'] < $row['precio_next']){
								$hoja->setCellValue("K{$flag}", $row['precio_next'])->getStyle("K{$flag}")->getNumberFormat()->setFormatCode('"$"#,##0.00_-');
								$this->cellStyle("K{$flag}", "FDB2B2", "E21111", FALSE, 12, "Franklin Gothic Book");
							}else if($row['precio_next'] !== NULL){
								$hoja->setCellValue("K{$flag}", $row['precio_next'])->getStyle("K{$flag}")->getNumberFormat()->setFormatCode('"$"#,##0.00_-');
								$this->cellStyle("K{$flag}", "96EAA8", "0C800C", FALSE, 12, "Franklin Gothic Book");
							}else{
								$hoja->setCellValue("K{$flag}", $row['precio_next'])->getStyle("K{$flag}")->getNumberFormat()->setFormatCode('"$"#,##0.00_-');
								$this->cellStyle("K{$flag}", "FFFFFF", "000000", FALSE, 12, "Franklin Gothic Book");
							}
							$hoja->setCellValue("L{$flag}", $row['proveedor_next']);
							$this->cellStyle("M".$flag.":BM".$flag, "FFFFFF", "000000", TRUE, 12, "Franklin Gothic Book");
							$this->cellStyle("L".$flag, "FFFFFF", "000000", FALSE, 12, "Franklin Gothic Book");

							$row[87] = $row[87] === NULL ? 0 :  $row[87];
							$antis = ((floatval($row[87]) * floatval($row["unidad"])) + ($row["unidad"] * $row["past"]["caja0"]) + $row["past"]["pz0"])/$row["unidad"];							
							$hoja->setCellValue("M{$flag}", $row['caja0']);
							$hoja->setCellValue("N{$flag}", $row['pz0']);
							$hoja->setCellValue("O{$flag}", $row['cedis']);
							$hoja->setCellValue("P{$flag}", "=".$antis."-(((M{$flag}*D{$flag})+N{$flag})/D{$flag})")->getStyle("P{$flag}")->getNumberFormat()->setFormatCode('#,##0_-');
							$hoja->setCellValue("Q{$flag}", $row['ped0']);
							$this->cellStyle("Q{$flag}", "D4EAEF", "000000", TRUE, 12, "Franklin Gothic Book");

							

							$row[89] = $row[89] === NULL ? 0 :  $row[89];
							$antis = ((floatval($row[89]) * floatval($row["unidad"])) + ($row["unidad"] * $row["past"]["caja9"]) + $row["past"]["pz9"])/$row["unidad"];							
							$hoja->setCellValue("R{$flag}", $row['caja9']);
							$hoja->setCellValue("S{$flag}", $row['pz9']);
							$hoja->setCellValue("T{$flag}", "=".$antis."-(((R{$flag}*D{$flag})+S{$flag})/D{$flag})")->getStyle("T{$flag}")->getNumberFormat()->setFormatCode('#,##0_-');
							$hoja->setCellValue("U{$flag}", $row['ped9']);
							$this->cellStyle("U{$flag}", "D4EAEF", "000000", TRUE, 12, "Franklin Gothic Book");

							

							$hoja->setCellValue("V{$flag}", "=M".$flag."+R".$flag);
							$hoja->setCellValue("W{$flag}", "=N".$flag."+S".$flag);
							$hoja->setCellValue("X{$flag}", "=Q".$flag."+U".$flag);
							$this->cellStyle("X{$flag}", "D4EAEF", "000000", TRUE, 12, "Franklin Gothic Book");

							$row[57] = $row[57] === NULL ? 0 :  $row[57];
							$antis = ((floatval($row[57]) * floatval($row["unidad"])) + ($row["unidad"] * $row["past"]["caja1"]) + $row["past"]["pz1"])/$row["unidad"];							
							$hoja->setCellValue("Y{$flag}", $row['caja1']);
							$hoja->setCellValue("Z{$flag}", $row['pz1']);
							$hoja->setCellValue("AA{$flag}", $row['abarrotes']);
							$hoja->setCellValue("AB{$flag}", "=".$antis."-(((Y{$flag}*D{$flag})+Z{$flag})/D{$flag})")->getStyle("AB{$flag}")->getNumberFormat()->setFormatCode('#,##0_-');
							$hoja->setCellValue("AC{$flag}", $row['ped1']);
							$this->cellStyle("AC{$flag}", "D4EAEF", "000000", TRUE, 12, "Franklin Gothic Book");

							

							$row[90] = $row[90] === NULL ? 0 :  $row[90];
							$antis = ((floatval($row[90]) * floatval($row["unidad"])) + ($row["unidad"] * $row["past"]["caja2"]) + $row["past"]["pz2"])/$row["unidad"];
							$hoja->setCellValue("AD{$flag}", $row['caja2']);
							$hoja->setCellValue("AE{$flag}", $row['pz2']);
							$hoja->setCellValue("AF{$flag}", $row['pedregal']);
							$hoja->setCellValue("AG{$flag}", "=".$antis."-(((AD{$flag}*D{$flag})+AE{$flag})/D{$flag})")->getStyle("AG{$flag}")->getNumberFormat()->setFormatCode('#,##0_-');
							$hoja->setCellValue("AH{$flag}", $row['ped2']);
							$this->cellStyle("AH{$flag}", "D4EAEF", "000000", TRUE, 12, "Franklin Gothic Book");

							

							$row[58] = $row[58] === NULL ? 0 :  $row[58];
							$antis = ((floatval($row[58]) * floatval($row["unidad"])) + ($row["unidad"] * $row["past"]["caja3"]) + $row["past"]["pz3"])/$row["unidad"];
							$hoja->setCellValue("AI{$flag}", $row['caja3']);
							$hoja->setCellValue("AJ{$flag}", $row['pz3']);
							$hoja->setCellValue("AK{$flag}", $row['tienda']);
							$hoja->setCellValue("AL{$flag}", "=".$antis."-(((AI{$flag}*D{$flag})+AJ{$flag})/D{$flag})")->getStyle("AL{$flag}")->getNumberFormat()->setFormatCode('#,##0_-');
							$hoja->setCellValue("AM{$flag}", $row['ped3']);
							$this->cellStyle("AM{$flag}", "D4EAEF", "000000", TRUE, 12, "Franklin Gothic Book");

							

							$row[59] = $row[59] === NULL ? 0 :  $row[59];
							$antis = ((floatval($row[59]) * floatval($row["unidad"])) + ($row["unidad"] * $row["past"]["caja4"]) + $row["past"]["pz4"])/$row["unidad"];
							$hoja->setCellValue("AN{$flag}", $row['caja4']);
							$hoja->setCellValue("AO{$flag}", $row['pz4']);
							$hoja->setCellValue("AP{$flag}", $row['ultra']);
							$hoja->setCellValue("AQ{$flag}", "=".$antis."-(((AN{$flag}*D{$flag})+AO{$flag})/D{$flag})")->getStyle("AQ{$flag}")->getNumberFormat()->setFormatCode('#,##0_-');
							$hoja->setCellValue("AR{$flag}", $row['ped4']);
							$this->cellStyle("AR{$flag}", "D4EAEF", "000000", TRUE, 12, "Franklin Gothic Book");

							

							$row[60] = $row[60] === NULL ? 0 :  $row[60];
							$antis = ((floatval($row[60]) * floatval($row["unidad"])) + ($row["unidad"] * $row["past"]["caja5"]) + $row["past"]["pz5"])/$row["unidad"];
							$hoja->setCellValue("AS{$flag}", $row['caja5']);
							$hoja->setCellValue("AT{$flag}", $row['pz5']);
							$hoja->setCellValue("AU{$flag}", $row['trincheras']);
							$hoja->setCellValue("AV{$flag}", "=".$antis."-(((AS{$flag}*D{$flag})+AT{$flag})/D{$flag})")->getStyle("AV{$flag}")->getNumberFormat()->setFormatCode('#,##0_-');
							$hoja->setCellValue("AW{$flag}", $row['ped5']);
							$this->cellStyle("AW{$flag}", "D4EAEF", "000000", TRUE, 12, "Franklin Gothic Book");

				

							$row[61] = $row[61] === NULL ? 0 :  $row[61];
							$antis = ((floatval($row[61]) * floatval($row["unidad"])) + ($row["unidad"] * $row["past"]["caja6"]) + $row["past"]["pz6"])/$row["unidad"];
							$hoja->setCellValue("AX{$flag}", $row['caja6']);
							$hoja->setCellValue("AY{$flag}", $row['pz6']);
							$hoja->setCellValue("AZ{$flag}", $row['mercado']);
							$hoja->setCellValue("BA{$flag}", "=".$antis."-(((AX{$flag}*D{$flag})+AY{$flag})/D{$flag})")->getStyle("BA{$flag}")->getNumberFormat()->setFormatCode('#,##0_-');
							$hoja->setCellValue("BB{$flag}", $row['ped6']);
							$this->cellStyle("BB{$flag}", "D4EAEF", "000000", TRUE, 12, "Franklin Gothic Book");

							

							$row[62] = $row[62] === NULL ? 0 :  $row[62];
							$antis = ((floatval($row[62]) * floatval($row["unidad"])) + ($row["unidad"] * $row["past"]["caja7"]) + $row["past"]["pz7"])/$row["unidad"];
							$hoja->setCellValue("BC{$flag}", $row['caja7']);
							$hoja->setCellValue("BD{$flag}", $row['pz7']);
							$hoja->setCellValue("BE{$flag}", $row['tenencia']);
							$hoja->setCellValue("BF{$flag}", "=".$antis."-(((BC{$flag}*D{$flag})+BD{$flag})/D{$flag})")->getStyle("BF{$flag}")->getNumberFormat()->setFormatCode('#,##0_-');
							$hoja->setCellValue("BG{$flag}", $row['ped7']);
							$this->cellStyle("BG{$flag}", "D4EAEF", "000000", TRUE, 12, "Franklin Gothic Book");

							

							$row[63] = $row[63] === NULL ? 0 :  $row[63];
							$antis = ((floatval($row[63]) * floatval($row["unidad"])) + ($row["unidad"] * $row["past"]["caja8"]) + $row["past"]["pz8"])/$row["unidad"];
							$hoja->setCellValue("BH{$flag}", $row['caja8']);
							$hoja->setCellValue("BI{$flag}", $row['pz8']);
							$hoja->setCellValue("BJ{$flag}", $row['tijeras']);
							$hoja->setCellValue("BK{$flag}", "=".$antis."-(((BH{$flag}*D{$flag})+BI{$flag})/D{$flag})")->getStyle("BK{$flag}")->getNumberFormat()->setFormatCode('#,##0_-');
							$hoja->setCellValue("BL{$flag}", $row['ped8']);
							$this->cellStyle("BL{$flag}", "D4EAEF", "000000", TRUE, 12, "Franklin Gothic Book");

							

							

							$hoja->setCellValue("BM{$flag}", $row['promocion_first']);
							$hoja->setCellValue("BN{$flag}", "=F".$flag."*Q".$flag)->getStyle("BN{$flag}")->getNumberFormat()->setFormatCode('"$"#,##0.00_-');
							$hoja->setCellValue("BO{$flag}", "=F".$flag."*AC".$flag)->getStyle("BO{$flag}")->getNumberFormat()->setFormatCode('"$"#,##0.00_-');
							$hoja->setCellValue("BP{$flag}", "=F".$flag."*AH".$flag)->getStyle("BP{$flag}")->getNumberFormat()->setFormatCode('"$"#,##0.00_-');
							$hoja->setCellValue("BQ{$flag}", "=F".$flag."*AM".$flag)->getStyle("BQ{$flag}")->getNumberFormat()->setFormatCode('"$"#,##0.00_-');
							$hoja->setCellValue("BR{$flag}", "=F".$flag."*AR".$flag)->getStyle("BR{$flag}")->getNumberFormat()->setFormatCode('"$"#,##0.00_-');
							$hoja->setCellValue("BS{$flag}", "=F".$flag."*AW".$flag)->getStyle("BS{$flag}")->getNumberFormat()->setFormatCode('"$"#,##0.00_-');
							$hoja->setCellValue("BT{$flag}", "=F".$flag."*BB".$flag)->getStyle("BT{$flag}")->getNumberFormat()->setFormatCode('"$"#,##0.00_-');
							$hoja->setCellValue("BU{$flag}", "=F".$flag."*BG".$flag)->getStyle("BU{$flag}")->getNumberFormat()->setFormatCode('"$"#,##0.00_-');
							$hoja->setCellValue("BV{$flag}", "=F".$flag."*BL".$flag)->getStyle("BV{$flag}")->getNumberFormat()->setFormatCode('"$"#,##0.00_-');
							$this->cellStyle("BW{$flag}", "D4EAEF", "000000", FALSE, 12, "Franklin Gothic Book");
							$hoja->setCellValue("BW{$flag}", "=SUM(BN".$flag.":BV".$flag.")")->getStyle("BW{$flag}")->getNumberFormat()->setFormatCode('"$"#,##0.00_-');
							$this->cellStyle("BX{$flag}", "C2B90A", "000000", FALSE, 12, "Franklin Gothic Book");
							$hoja->setCellValue("BX{$flag}", "=Q".$flag."+AC".$flag."+AH".$flag."+AM".$flag."+AR".$flag."+AW".$flag."+BB".$flag."+BG".$flag."+BL".$flag."");

							//Begin: TOTALES PEDIDOS PENDIENTES
							$hoja->setCellValue("CA{$flag}", "=F".$flag."*O".$flag)->getStyle("CA{$flag}")->getNumberFormat()->setFormatCode('"$"#,##0.00_-');
							$hoja->setCellValue("CB{$flag}", "=F".$flag."*AA".$flag)->getStyle("CB{$flag}")->getNumberFormat()->setFormatCode('"$"#,##0.00_-');
							$hoja->setCellValue("CC{$flag}", "=F".$flag."*AF".$flag)->getStyle("CC{$flag}")->getNumberFormat()->setFormatCode('"$"#,##0.00_-');
							$hoja->setCellValue("CD{$flag}", "=F".$flag."*AK".$flag)->getStyle("CD{$flag}")->getNumberFormat()->setFormatCode('"$"#,##0.00_-');
							$hoja->setCellValue("CE{$flag}", "=F".$flag."*AP".$flag)->getStyle("CE{$flag}")->getNumberFormat()->setFormatCode('"$"#,##0.00_-');
							$hoja->setCellValue("CF{$flag}", "=F".$flag."*AU".$flag)->getStyle("CF{$flag}")->getNumberFormat()->setFormatCode('"$"#,##0.00_-');
							$hoja->setCellValue("CG{$flag}", "=F".$flag."*AZ".$flag)->getStyle("CG{$flag}")->getNumberFormat()->setFormatCode('"$"#,##0.00_-');
							$hoja->setCellValue("CH{$flag}", "=F".$flag."*BE".$flag)->getStyle("CH{$flag}")->getNumberFormat()->setFormatCode('"$"#,##0.00_-');
							$hoja->setCellValue("CI{$flag}", "=F".$flag."*BJ".$flag)->getStyle("CI{$flag}")->getNumberFormat()->setFormatCode('"$"#,##0.00_-');
							$this->cellStyle("CJ{$flag}", "000000", "FFFFFF", FALSE, 12, "Franklin Gothic Book");
							$hoja->setCellValue("CJ{$flag}", "=SUM(CA".$flag.":CI".$flag.")")->getStyle("CJ{$flag}")->getNumberFormat()->setFormatCode('"$"#,##0.00_-');
							$this->cellStyle("CK{$flag}", "000000", "FFFFFF", FALSE, 12, "Franklin Gothic Book");
							$hoja->setCellValue("CK{$flag}", "=O".$flag."+AA".$flag."+AF".$flag."+AK".$flag."+AP".$flag."+AU".$flag."+AZ".$flag."+BE".$flag."+BJ".$flag."");
							//End: TOTALES PEDIDOS PENDIENTES
							
							$border_style= array('borders' => array('right' => array('style' =>
								PHPExcel_Style_Border::BORDER_THIN,'color' => array('argb' => '000000'),)));
							$this->excelfile->setActiveSheetIndex(1);
							$this->excelfile->getActiveSheet()->getStyle('A'.$flag.':BM'.$flag)->applyFromArray($styleArray);
							$this->excelfile->getActiveSheet()->getStyle('BW'.$flag)->applyFromArray($styleArray);
							$this->excelfile->getActiveSheet()->getStyle('BX'.$flag)->applyFromArray($styleArray);
							$this->excelfile->setActiveSheetIndex(0);
							$this->excelfile->getActiveSheet()->getStyle('A'.$flag1.':P'.$flag1)->applyFromArray($styleArray);
							$hoja->getStyle("A{$flag}:L{$flag}")
					                 ->getAlignment()
					                 ->setHorizontal(\PHPExcel_Style_Alignment::HORIZONTAL_LEFT);

						    if($this->weekNumber($registrazo) == ($this->weekNumber() - 1) || $this->weekNumber($registrazo) == ($this->weekNumber())){
								$this->cellStyle("A{$flag}", "FF7F71", "000000", FALSE, 12, "Franklin Gothic Book");
								$this->cellStyle("B{$flag}", "FF7F71", "000000", FALSE, 12, "Franklin Gothic Book");
							}
							if($row['precio_sistema'] == 0){
								$row['precio_sistema'] = 1;
							}
							if($row['precio_four'] == 0){
								$row['precio_four'] = 1;
							}

							$hoja->setCellValue("G{$flag}",100 - ($row['precio_first'] * 100 / $row['precio_sistema']))->getStyle("G{$flag}")->getNumberFormat()->setFormatCode('"%"#,##0.00_-');;
							$this->cellStyle("G".$flag, "FF9999", "000000", FALSE, 10, "Franklin Gothic Book");

							$hoja->setCellValue("I{$flag}", 100 - ($row['precio_sistema'] * 100 / $row['precio_four']))->getStyle("I{$flag}")->getNumberFormat()->setFormatCode('"%"#,##0.00_-');;
							$this->cellStyle("I".$flag, "FF9999", "000000", FALSE, 10, "Franklin Gothic Book");

							


							$flag ++;
							$flag1 ++;
						}
					}
				}
			}
			$flans = $flag - 1;
			$hoja->setCellValue("BN{$flag}", "=SUM(BN{$bandera}:BN{$flans})")->getStyle("BN{$flag}")->getNumberFormat()->setFormatCode('"$"#,##0.00_-');
			$hoja->setCellValue("BO{$flag}", "=SUM(BO{$bandera}:BO{$flans})")->getStyle("BO{$flag}")->getNumberFormat()->setFormatCode('"$"#,##0.00_-');
			$hoja->setCellValue("BP{$flag}", "=SUM(BP{$bandera}:BP{$flans})")->getStyle("BP{$flag}")->getNumberFormat()->setFormatCode('"$"#,##0.00_-');
			$hoja->setCellValue("BQ{$flag}", "=SUM(BQ{$bandera}:BQ{$flans})")->getStyle("BQ{$flag}")->getNumberFormat()->setFormatCode('"$"#,##0.00_-');
			$hoja->setCellValue("BR{$flag}", "=SUM(BR{$bandera}:BR{$flans})")->getStyle("BR{$flag}")->getNumberFormat()->setFormatCode('"$"#,##0.00_-');
			$hoja->setCellValue("BS{$flag}", "=SUM(BS{$bandera}:BS{$flans})")->getStyle("BS{$flag}")->getNumberFormat()->setFormatCode('"$"#,##0.00_-');
			$hoja->setCellValue("BT{$flag}", "=SUM(BT{$bandera}:BT{$flans})")->getStyle("BT{$flag}")->getNumberFormat()->setFormatCode('"$"#,##0.00_-');
			$hoja->setCellValue("BU{$flag}", "=SUM(BU{$bandera}:BU{$flans})")->getStyle("BU{$flag}")->getNumberFormat()->setFormatCode('"$"#,##0.00_-');
			$hoja->setCellValue("BV{$flag}", "=SUM(BV{$bandera}:BV{$flans})")->getStyle("BV{$flag}")->getNumberFormat()->setFormatCode('"$"#,##0.00_-');
			$hoja->setCellValue("BW{$flag}", "=SUM(BW{$bandera}:BW{$flans})")->getStyle("BW{$flag}")->getNumberFormat()->setFormatCode('"$"#,##0.00_-');

			//Begin: TOTALES PEDIDOS PENDIENTES
			$hoja->setCellValue("CA{$flag}", "=SUM(CA{$bandera}:CA{$flans})")->getStyle("CA{$flag}")->getNumberFormat()->setFormatCode('"$"#,##0.00_-');
			$hoja->setCellValue("CB{$flag}", "=SUM(CB{$bandera}:CB{$flans})")->getStyle("CB{$flag}")->getNumberFormat()->setFormatCode('"$"#,##0.00_-');
			$hoja->setCellValue("CC{$flag}", "=SUM(CC{$bandera}:CC{$flans})")->getStyle("CC{$flag}")->getNumberFormat()->setFormatCode('"$"#,##0.00_-');
			$hoja->setCellValue("CD{$flag}", "=SUM(CD{$bandera}:CD{$flans})")->getStyle("CD{$flag}")->getNumberFormat()->setFormatCode('"$"#,##0.00_-');
			$hoja->setCellValue("CE{$flag}", "=SUM(CE{$bandera}:CE{$flans})")->getStyle("CE{$flag}")->getNumberFormat()->setFormatCode('"$"#,##0.00_-');
			$hoja->setCellValue("CF{$flag}", "=SUM(CF{$bandera}:CF{$flans})")->getStyle("CF{$flag}")->getNumberFormat()->setFormatCode('"$"#,##0.00_-');
			$hoja->setCellValue("CG{$flag}", "=SUM(CG{$bandera}:CG{$flans})")->getStyle("CG{$flag}")->getNumberFormat()->setFormatCode('"$"#,##0.00_-');
			$hoja->setCellValue("CH{$flag}", "=SUM(CH{$bandera}:CH{$flans})")->getStyle("CH{$flag}")->getNumberFormat()->setFormatCode('"$"#,##0.00_-');
			$hoja->setCellValue("CI{$flag}", "=SUM(CI{$bandera}:CI{$flans})")->getStyle("CI{$flag}")->getNumberFormat()->setFormatCode('"$"#,##0.00_-');
			$hoja->setCellValue("CJ{$flag}", "=SUM(CJ{$bandera}:CJ{$flans})")->getStyle("CJ{$flag}")->getNumberFormat()->setFormatCode('"$"#,##0.00_-');
			//End: TOTALES PEDIDOS PENDIENTES

			$flans = $flag;
			$flag += 4;
			//Begin: TOTALES PEDIDOS PENDIENTES
			$hoja->mergeCells('F'.($flag-1).':I'.($flag-1));
			$this->cellStyle('F'.($flag-1).':I'.($flag-1), "000000", "FFFFFF", TRUE, 12, "Franklin Gothic Book");
			$hoja->setCellValue("F".($flag-1), "TOTALES POR PENDIENTES");
			//End: TOTALES PEDIDOS PENDIENTES
			$this->cellStyle("C".$flag, "66FFFB", "000000", TRUE, 12, "Franklin Gothic Book");
			$hoja->setCellValue("C".$flag, "CEDIS");
			$hoja->setCellValue("D{$flag}", "=(BN{$flans})")->getStyle("D{$flag}")->getNumberFormat()->setFormatCode('"$"#,##0.00_-');
			//Begin: TOTALES PEDIDOS PENDIENTES
			$hoja->mergeCells('F'.$flag.':G'.$flag);
			$hoja->mergeCells('H'.$flag.':I'.$flag);
			$this->cellStyle('F'.$flag.':G'.$flag, "000000", "FFFFFF", TRUE, 12, "Franklin Gothic Book");
			$hoja->setCellValue("F".$flag, "CEDIS");
			$hoja->setCellValue("H{$flag}", "=(CA{$flans})")->getStyle("H{$flag}")->getNumberFormat()->setFormatCode('"$"#,##0.00_-');
			//End: TOTALES PEDIDOS PENDIENTES
			$flag++;
			$this->cellStyle("C".$flag, "01B0F0", "000000", TRUE, 12, "Franklin Gothic Book");
			$hoja->setCellValue("C".$flag, "ABARROTES");
			$hoja->setCellValue("D{$flag}", "=(BO{$flans})")->getStyle("D{$flag}")->getNumberFormat()->setFormatCode('"$"#,##0.00_-');
			//Begin: TOTALES PEDIDOS PENDIENTES
			$hoja->mergeCells('F'.$flag.':G'.$flag);
			$hoja->mergeCells('H'.$flag.':I'.$flag);
			$this->cellStyle('F'.$flag.':G'.$flag, "000000", "FFFFFF", TRUE, 12, "Franklin Gothic Book");
			$hoja->setCellValue("F".$flag, "ABARROTES");
			$hoja->setCellValue("H{$flag}", "=(CB{$flans})")->getStyle("H{$flag}")->getNumberFormat()->setFormatCode('"$"#,##0.00_-');
			//End: TOTALES PEDIDOS PENDIENTES
			$flag++;
			$this->cellStyle("C".$flag, "FF0000", "000000", TRUE, 12, "Franklin Gothic Book");
			$hoja->setCellValue("C".$flag, "VILLAS");
			$hoja->setCellValue("D{$flag}", "=(BP{$flans})")->getStyle("D{$flag}")->getNumberFormat()->setFormatCode('"$"#,##0.00_-');
			//Begin: TOTALES PEDIDOS PENDIENTES
			$hoja->mergeCells('F'.$flag.':G'.$flag);
			$hoja->mergeCells('H'.$flag.':I'.$flag);
			$this->cellStyle('F'.$flag.':G'.$flag, "000000", "FFFFFF", TRUE, 12, "Franklin Gothic Book");
			$hoja->setCellValue("F".$flag, "VILLAS");
			$hoja->setCellValue("H{$flag}", "=(CC{$flans})")->getStyle("H{$flag}")->getNumberFormat()->setFormatCode('"$"#,##0.00_-');
			//End: TOTALES PEDIDOS PENDIENTES
			$flag++;
			$this->cellStyle("C".$flag, "E26C0B", "000000", TRUE, 12, "Franklin Gothic Book");
			$hoja->setCellValue("C".$flag, "TIENDA");
			$hoja->setCellValue("D{$flag}", "=(BQ{$flans})")->getStyle("D{$flag}")->getNumberFormat()->setFormatCode('"$"#,##0.00_-');
			//Begin: TOTALES PEDIDOS PENDIENTES
			$hoja->mergeCells('F'.$flag.':G'.$flag);
			$hoja->mergeCells('H'.$flag.':I'.$flag);
			$this->cellStyle('F'.$flag.':G'.$flag, "000000", "FFFFFF", TRUE, 12, "Franklin Gothic Book");
			$hoja->setCellValue("F".$flag, "TIENDA");
			$hoja->setCellValue("H{$flag}", "=(CD{$flans})")->getStyle("H{$flag}")->getNumberFormat()->setFormatCode('"$"#,##0.00_-');
			//End: TOTALES PEDIDOS PENDIENTES
			$flag++;
			$this->cellStyle("C".$flag, "C5C5C5", "000000", TRUE, 12, "Franklin Gothic Book");
			$hoja->setCellValue("C".$flag, "ULTRAMARINOS");
			$hoja->setCellValue("D{$flag}", "=(BR{$flans})")->getStyle("D{$flag}")->getNumberFormat()->setFormatCode('"$"#,##0.00_-');
			//Begin: TOTALES PEDIDOS PENDIENTES
			$hoja->mergeCells('F'.$flag.':G'.$flag);
			$hoja->mergeCells('H'.$flag.':I'.$flag);
			$this->cellStyle('F'.$flag.':G'.$flag, "000000", "FFFFFF", TRUE, 12, "Franklin Gothic Book");
			$hoja->setCellValue("F".$flag, "ULTRAMARINOS");
			$hoja->setCellValue("H{$flag}", "=(CE{$flans})")->getStyle("H{$flag}")->getNumberFormat()->setFormatCode('"$"#,##0.00_-');
			//End: TOTALES PEDIDOS PENDIENTES
			$flag++;
			$this->cellStyle("C".$flag, "92D051", "000000", TRUE, 12, "Franklin Gothic Book");
			$hoja->setCellValue("C".$flag, "TRINCHERAS");
			$hoja->setCellValue("D{$flag}", "=(BS{$flans})")->getStyle("D{$flag}")->getNumberFormat()->setFormatCode('"$"#,##0.00_-');
			//Begin: TOTALES PEDIDOS PENDIENTES
			$hoja->mergeCells('F'.$flag.':G'.$flag);
			$hoja->mergeCells('H'.$flag.':I'.$flag);
			$this->cellStyle('F'.$flag.':G'.$flag, "000000", "FFFFFF", TRUE, 12, "Franklin Gothic Book");
			$hoja->setCellValue("F".$flag, "TRINCHERAS");
			$hoja->setCellValue("H{$flag}", "=(CF{$flans})")->getStyle("H{$flag}")->getNumberFormat()->setFormatCode('"$"#,##0.00_-');
			//End: TOTALES PEDIDOS PENDIENTES
			$flag++;
			$this->cellStyle("C".$flag, "B1A0C7", "000000", TRUE, 12, "Franklin Gothic Book");
			$hoja->setCellValue("C".$flag, "AZT MERCADO");
			$hoja->setCellValue("D{$flag}", "=(BT{$flans})")->getStyle("D{$flag}")->getNumberFormat()->setFormatCode('"$"#,##0.00_-');
			//Begin: TOTALES PEDIDOS PENDIENTES
			$hoja->mergeCells('F'.$flag.':G'.$flag);
			$hoja->mergeCells('H'.$flag.':I'.$flag);
			$this->cellStyle('F'.$flag.':G'.$flag, "000000", "FFFFFF", TRUE, 12, "Franklin Gothic Book");
			$hoja->setCellValue("F".$flag, "AZT MERCADO");
			$hoja->setCellValue("H{$flag}", "=(CG{$flans})")->getStyle("H{$flag}")->getNumberFormat()->setFormatCode('"$"#,##0.00_-');
			//End: TOTALES PEDIDOS PENDIENTES
			$flag++;
			$this->cellStyle("C".$flag, "DA9694", "000000", TRUE, 12, "Franklin Gothic Book");
			$hoja->setCellValue("C".$flag, "TENENCIA");
			$hoja->setCellValue("D{$flag}", "=(BU{$flans})")->getStyle("D{$flag}")->getNumberFormat()->setFormatCode('"$"#,##0.00_-');
			//Begin: TOTALES PEDIDOS PENDIENTES
			$hoja->mergeCells('F'.$flag.':G'.$flag);
			$hoja->mergeCells('H'.$flag.':I'.$flag);
			$this->cellStyle('F'.$flag.':G'.$flag, "000000", "FFFFFF", TRUE, 12, "Franklin Gothic Book");
			$hoja->setCellValue("F".$flag, "TENENCIA");
			$hoja->setCellValue("H{$flag}", "=(CH{$flans})")->getStyle("H{$flag}")->getNumberFormat()->setFormatCode('"$"#,##0.00_-');
			//End: TOTALES PEDIDOS PENDIENTES
			$flag++;
			$this->cellStyle("C".$flag, "4CACC6", "000000", TRUE, 12, "Franklin Gothic Book");
			$hoja->setCellValue("C".$flag, "TIJERAS");
			$hoja->setCellValue("D{$flag}", "=(BV{$flans})")->getStyle("D{$flag}")->getNumberFormat()->setFormatCode('"$"#,##0.00_-');
			//Begin: TOTALES PEDIDOS PENDIENTES
			$hoja->mergeCells('F'.$flag.':G'.$flag);
			$hoja->mergeCells('H'.$flag.':I'.$flag);
			$this->cellStyle('F'.$flag.':G'.$flag, "000000", "FFFFFF", TRUE, 12, "Franklin Gothic Book");
			$hoja->setCellValue("F".$flag, "TIJERAS");
			$hoja->setCellValue("H{$flag}", "=(CI{$flans})")->getStyle("H{$flag}")->getNumberFormat()->setFormatCode('"$"#,##0.00_-');
			//End: TOTALES PEDIDOS PENDIENTES
			$flag++;
			$this->cellStyle("C".$flag, "000000", "FFFFFF", TRUE, 12, "Franklin Gothic Book");
			$hoja->setCellValue("C".$flag, "TOTAL");
			$hoja->setCellValue("D{$flag}", "=(BW{$flans})")->getStyle("D{$flag}")->getNumberFormat()->setFormatCode('"$"#,##0.00_-');
			//Begin: TOTALES PEDIDOS PENDIENTES
			$hoja->mergeCells('F'.$flag.':G'.$flag);
			$hoja->mergeCells('H'.$flag.':I'.$flag);
			$this->cellStyle('F'.$flag.':G'.$flag, "000000", "FFFFFF", TRUE, 12, "Franklin Gothic Book");
			$hoja->setCellValue("F".$flag, "TOTAL");
			$hoja->setCellValue("H{$flag}", "=(CJ{$flans})")->getStyle("H{$flag}")->getNumberFormat()->setFormatCode('"$"#,##0.00_-');
			//End: TOTALES PEDIDOS PENDIENTES
			$flag++;


			$flag = $flag+5;
			$flag1 = $flag1+5;
		}

		$dias = array("DOMINGO","LUNES","MARTES","MIÉRCOLES","JUEVES","VIERNES","SÁBADO");
		$meses = array("ENERO","FEBRERO","MARZO","ABRIL","MAYO","JUNIO","JULIO","AGOSTO","SEPTIEMBRE","OCTUBRE","NOVIEMBRE","DICIEMBRE");
		$fecha =  $dias[date('w')]." ".date('d')." DE ".$meses[date('n')-1]. " DEL ".date('Y') ;
		$file_name = "FORMATO ".$excname." ".$fecha.".xlsx"; //Nombre del documento con extención
		$excel_Writer = PHPExcel_IOFactory::createWriter($this->excelfile, "Excel2007");
		header("Content-Type: application/vnd.ms-excel; charset=utf-8");
		header("Content-Disposition: attachment;filename=".$file_name);
		header("Cache-Control: max-age=0");
		$excel_Writer->save("php://output");
	}
	public function fill_excel_duero(){
		ini_set("memory_limit", "-1");
		$provee = $this->input->post('id_pro');
		$this->load->library("excelfile");
		$hoja = $this->excelfile->getActiveSheet();
		$hoja->getDefaultStyle()
		    ->getBorders()
		    ->getTop()
		        ->setBorderStyle(PHPExcel_Style_Border::BO$RDER_THIN);
		$hoja->getDefaultStyle()
		    ->getBorders()
		    ->getBottom()
		        ->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
		$hoja->getDefaultStyle()
		    ->getBorders()
		    ->getLeft()
		        ->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
		$hoja->getDefaultStyle()
		    ->getBorders()
		    ->getRight()
		        ->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
		$this->cellStyle("A1:H2", "000000", "FFFFFF", TRUE, 12, "Franklin Gothic Book");
		$border_style= array('borders' => array('right' => array('style' =>
			PHPExcel_Style_Border::BORDER_THIN,'color' => array('argb' => '000000'),)));
		$hoja->setCellValue("C1", "DESCRIPCIÓN SISTEMA")->getColumnDimension('C')->setWidth(70);
		$hoja->setCellValue("D1", "PRECIO")->getColumnDimension('D')->setWidth(15);
		$hoja->setCellValue("E1", "PROMOCIÓN")->getColumnDimension('E')->setWidth(50);
		$hoja->setCellValue("F1", "# EN #")->getColumnDimension('F')->setWidth(12);
		$hoja->setCellValue("G1", "# EN #")->getColumnDimension('G')->setWidth(12);
		$hoja->setCellValue("H1", "% DESCUENTO")->getColumnDimension('H')->setWidth(15);
		$hoja->setCellValue("A2", "CÓDIGO")->getColumnDimension('A')->setWidth(30); //Nombre y ajuste de texto a la columna
		$hoja->setCellValue("B2", "CÓDIGO DUERO")->getColumnDimension('B')->setWidth(30); //Nombre y ajuste de texto a la columna
		$hoja->mergeCells('E1:F1');
		
		$productos = $this->prod_mdl->getProdFamDuero(NULL,$provee);
		$provs = $this->usua_mdl->get(NULL, ['id_usuario'=>$provee])[0];
		$row_print = 2;
		if ($productos){
			foreach ($productos as $key => $value){
				$hoja->setCellValue("C{$row_print}", $value['familia']);
				$this->cellStyle("C{$row_print}", "000000", "FFFFFF", TRUE, 12, "Franklin Gothic Book");
				$hoja->setCellValue("C{$row_print}", $value['familia']);
				$hoja->setCellValue("D{$row_print}", $provs->nombre.' '.$provs->apellido);
				$hoja->getStyle("D{$row_print}")->applyFromArray($border_style);
				$row_print +=1;
				if ($value['articulos']) {
					foreach ($value['articulos'] as $key => $row){
						if($row['color'] == '#92CEE3'){
							$this->cellStyle("A{$row_print}", "92CEE3", "000000", FALSE, 10, "Franklin Gothic Book");
						}else{
							$this->cellStyle("A{$row_print}", "FFFFFF", "000000", FALSE, 10, "Franklin Gothic Book");
						}
						$hoja->setCellValue("A{$row_print}", $row['codigo'])->getStyle("A{$row_print}")->getNumberFormat()->setFormatCode('# ???/???');//Formato de fraccion
						$hoja->getStyle("A{$row_print}")->applyFromArray($border_style);
						$hoja->setCellValue("B{$row_print}", $row['prodcaja'])->getStyle("B{$row_print}")->getNumberFormat()->setFormatCode('# ???/???');//Formato de fraccion
						$hoja->getStyle("B{$row_print}")->applyFromArray($border_style);
						$hoja->setCellValue("C{$row_print}", $row['producto']);
						if($row['estatus'] == 2){
							$this->cellStyle("C{$row_print}", "00B0F0", "000000", FALSE, 10, "Franklin Gothic Book");
						}
						if($row['estatus'] == 3){
							$this->cellStyle("C{$row_print}", "FFF900", "000000", FALSE, 10, "Franklin Gothic Book");
						}
						if($row['estatus'] >= 4){
							$this->cellStyle("C{$row_print}", "04B486", "000000", FALSE, 12, "Franklin Gothic Book");
						}
						$hoja->getStyle("C{$row_print}")->applyFromArray($border_style);
						if($row['colorp'] == 1){
							$this->cellStyle("D{$row_print}", "D6DCE4", "000000", FALSE, 10, "Franklin Gothic Book");
						}else{
							$this->cellStyle("D{$row_print}", "FFFFFF", "000000", FALSE, 10, "Franklin Gothic Book");
						}
						$hoja->setCellValue("D{$row_print}", $row['precio'])->getStyle("D{$row_print}")->getNumberFormat()->setFormatCode('"$"#,##0.00_-');
						$hoja->getStyle("D{$row_print}")->applyFromArray($border_style);
						$hoja->setCellValue("E{$row_print}", $row['observaciones']);
						$hoja->getStyle("E{$row_print}")->applyFromArray($border_style);
						$hoja->setCellValue("F{$row_print}", $row['num_one']);
						$hoja->getStyle("F{$row_print}")->applyFromArray($border_style);
						$hoja->setCellValue("G{$row_print}", $row['num_two']);
						$hoja->getStyle("G{$row_print}")->applyFromArray($border_style);
						$hoja->setCellValue("H{$row_print}", $row['descuento']);
						$hoja->getStyle("H{$row_print}")->applyFromArray($border_style);
						if($row['sem4'] <> NULL && (($row['sem2'] <> NULL || $row['sem1'] == NULL) || ($row['sem2'] == NULL || $row['sem1'] <> NULL))){
								$this->cellStyle("D{$row_print}", "8064A2", "000000", FALSE, 10, "Franklin Gothic Book");
							}elseif ($row['sem3'] <> NULL && (($row['sem2'] <> NULL || $row['sem1'] == NULL) || ($row['sem2'] == NULL || $row['sem1'] <> NULL))){
								$this->cellStyle("D{$row_print}", "8064A2", "000000", FALSE, 10, "Franklin Gothic Book");
							}elseif ($row['sem1'] <> NULL) {
								$this->cellStyle("D{$row_print}", "F79646", "000000", FALSE, 10, "Franklin Gothic Book");
							}elseif ($row['sem2'] <> NULL) {
								$this->cellStyle("D{$row_print}", "F79646", "000000", FALSE, 10, "Franklin Gothic Book");
							}
						if(($this->weekNumber($row['fecha_registro']) >= ($this->weekNumber() -1))  && date('Y', strtotime($row['fecha_registro'])) == '2020'){
							$this->cellStyle("A{$row_print}", "FF7F71", "000000", FALSE, 10, "Franklin Gothic Book");
							$this->cellStyle("B{$row_print}", "FF7F71", "000000", FALSE, 10, "Franklin Gothic Book");
							$this->cellStyle("C{$row_print}", "FF7F71", "000000", FALSE, 10, "Franklin Gothic Book");
							$this->cellStyle("D{$row_print}", "FF7F71", "000000", TRUE, 10, "Franklin Gothic Book");
							$this->cellStyle("E{$row_print}", "FF7F71", "000000", FALSE, 10, "Franklin Gothic Book");
							$this->cellStyle("F{$row_print}", "FF7F71", "000000", FALSE, 10, "Franklin Gothic Book");
							$this->cellStyle("G{$row_print}", "FF7F71", "000000", FALSE, 10, "Franklin Gothic Book");
							$this->cellStyle("H{$row_print}", "FF7F71", "000000", FALSE, 10, "Franklin Gothic Book");
							$hoja->setCellValue("I{$row_print}", "NUEVO");
						}
						$row_print++;
					}
				}
			}
		}
		$hoja->getStyle("A3:I{$row_print}")
                 ->getAlignment()
                 ->setHorizontal(\PHPExcel_Style_Alignment::HORIZONTAL_LEFT);
		$hoja->getStyle("C3:C{$row_print}")
                 ->getAlignment()
                 ->setHorizontal(\PHPExcel_Style_Alignment::HORIZONTAL_LEFT);



		$file_name = "Cotización ".$provs->nombre.".xlsx"; //Nombre del documento con extención

		header("Content-Type: application/vnd.ms-excel; charset=utf-8");
		header("Content-Disposition: attachment;filename=".$file_name);
		header("Cache-Control: max-age=0");
		$excel_Writer = PHPExcel_IOFactory::createWriter($this->excelfile, "Excel2007");
		$excel_Writer->save("php://output");
	}
	public function upload_pedid($idesp){
		$fecha = new DateTime(date('Y-m-d H:i:s'));
		$intervalo = new DateInterval('P3D');
		$fecha->add($intervalo);
		$this->load->library("excelfile");
		ini_set("memory_limit", -1);
		$cuafile = $_FILES["file_cotizaciones"]['name'];
		$file = $_FILES["file_cotizaciones"]["tmp_name"];
		$sheet = PHPExcel_IOFactory::load($file);
		$objExcel = PHPExcel_IOFactory::load($file);
		$sheet = $objExcel->getSheet(0);
		$num_rows = $sheet->getHighestDataRow();

		if($idesp === "0"){
			$tienda = $this->session->userdata('id_usuario');
		}else{
			$tienda = $idesp;
		}
		$cfile =  $this->usua_mdl->get(NULL, ['id_usuario' => $tienda])[0];
		$nams = preg_replace('/\s+/', '_', $cfile->nombre);
		$filen = "Pedidos".$nams."".rand();
		$config['upload_path']          = './assets/uploads/pedidos/';
        $config['allowed_types']        = 'xlsx|xls';
        $config['max_size']             = 10000;
        $config['max_width']            = 10024;
        $config['max_height']           = 7680;
        $this->load->library('upload', $config);
        $this->upload->initialize($config);
        $this->upload->do_upload('file_cotizaciones',$filen);
		for ($i=3; $i<=$num_rows; $i++) {
			$productos = $this->prod_mdl->get("id_producto",['codigo'=> htmlspecialchars($sheet->getCell('D'.$i)->getValue(), ENT_QUOTES, 'UTF-8')])[0];
			if (sizeof($productos) > 0) {
				$exis = $this->ex_mdl->get(NULL,["WEEKOFYEAR(fecha_registro)" => $this->weekNumber($fecha->format('Y-m-d H:i:s')),"id_tienda"=>$tienda,"id_producto"=>$productos->id_producto])[0];
				$column_one=0; $column_two=0; $column_three=0;
				$column_one = $sheet->getCell('A'.$i)->getValue() == "" ? 0 : $sheet->getCell('A'.$i)->getValue();
				$column_two = $sheet->getCell('B'.$i)->getValue() == "" ? 0 : $sheet->getCell('B'.$i)->getValue();
				$column_three = $sheet->getCell('C'.$i)->getValue() == "" ? 0 : $sheet->getCell('C'.$i)->getValue();
				$new_existencias[$i]=[
					"id_producto"			=>	$productos->id_producto,
					"id_tienda"			=>	$tienda,
					"cajas"			=>	$column_one,
					"piezas"			=>	$column_two,
					"pedido"	=>	$column_three,
					"fecha_registro"	=>	$fecha->format('Y-m-d H:i:s')
				];
				if($exis){
					//$data['cotizacion']=$this->ex_mdl->update($new_existencias[$i], ['id_pedido' => $exis->id_pedido]);
				}else{
					$data['cotizacion']=$this->ex_mdl->insert($new_existencias[$i]);
				}
			}
		}
		if (isset($new_existencias)) {
			$aprov = $this->usua_mdl->get(NULL, ['id_usuario'=>$tienda])[0];
			$cambios=[
					"id_usuario"		=>	$this->session->userdata('id_usuario'),
					"fecha_cambio"		=>	date("Y-m-d H:i:s"),
					"antes"			=>	"El usuario sube archivo de pedidos de la tienda ".$aprov->nombre,
					"despues"			=>	"assets/uploads/pedidos/".$filen.".xlsx",
					"accion"			=>	"Pedidos ( ".$cuafile." )"
				];
			$data['cambios']=$this->cambio_md->insert($cambios);
			$mensaje=[	"id"	=>	'Éxito',
						"desc"	=>	'Pedidos cargados correctamente en el Sistema',
						"type"	=>	'success'];
		}else{
			$mensaje=[	"id"	=>	'Error',
						"desc"	=>	'Los Pedidos no se cargaron al Sistema',
						"type"	=>	'error'];
		}
		$this->jsonResponse($mensaje);
	}
	private function fill_hermanos(){
		$flag =1;
		$flag2=1;
		$array = "";
		$array2 = "";
		$filenam = "";
		$flag1 = 5;
		$array = $this->usua_mdl->getH(NULL);
		ini_set("memory_limit", "-1");
		ini_set("max_execution_time", "-1");
		$this->load->library("excelfile");
		//$this->excelfile = PHPExcel_IOFactory::load("./assets/uploads/template.xlsx");
		$hoja1 = $this->excelfile->setActiveSheetIndex(0);
		$this->excelfile->setActiveSheetIndex(0)->setTitle("EXISTENCIAS");
		$this->excelfile->createSheet();
        $hoja = $this->excelfile->setActiveSheetIndex(1);
        $hoja->setTitle("PEDIDO");
		$styleArray = array(
		  'borders' => array(
		    'allborders' => array(
		      'style' => PHPExcel_Style_Border::BORDER_THIN
		    )
		  )
		);
		$hoja->getColumnDimension('A')->setWidth("20");
		$hoja->getColumnDimension('B')->setWidth("20");
		$hoja->getColumnDimension('C')->setWidth("70");
		$hoja->getColumnDimension('D')->setWidth("7");
		$hoja->getColumnDimension('E')->setWidth("15");
		$hoja->getColumnDimension('F')->setWidth("15");
		$hoja->getColumnDimension('G')->setWidth("8");
		$hoja->getColumnDimension('H')->setWidth("15");
		$hoja->getColumnDimension('I')->setWidth("8");
		$hoja->getColumnDimension('L')->setWidth("20");
		$hoja->getColumnDimension('J')->setWidth("20");
		$hoja->getColumnDimension('K')->setWidth("15");
		$hoja->getColumnDimension('BM')->setWidth("70");
		
		$hoja1->getColumnDimension('A')->setWidth("6");
		$hoja1->getColumnDimension('B')->setWidth("6");
		$hoja1->getColumnDimension('C')->setWidth("6");
		$hoja1->getColumnDimension('D')->setWidth("25");
		$hoja1->getColumnDimension('E')->setWidth("47");
		$hoja1->getColumnDimension('G')->setWidth("50");
		$hoja1->getColumnDimension('F')->setWidth("15");

		foreach ($array as $key => $v3) {
			$this->excelfile->setActiveSheetIndex(0);
			if ($flag > 15) {
				$flag2 = $flag+2;	
			}else{
				$flag2 = $flag;
			}
			
			$hoja1->mergeCells('A'.$flag2.':G'.$flag2);
			$this->cellStyle("A".$flag2."", "FFFFFF", "000000", TRUE, 12, "Franklin Gothic Book");
			$hoja1->setCellValue("A".$flag2."", "GRUPO ABARROTES AZTECA");
			$this->excelfile->getActiveSheet()->getStyle('A'.$flag2.':G'.$flag2.'')->applyFromArray($styleArray);
			$flag2++;
			$hoja1->mergeCells('A'.$flag2.':G'.$flag2.'');
			$this->cellStyle("A".$flag2."", "FFFFFF", "000000", TRUE, 12, "Franklin Gothic Book");
			$hoja1->setCellValue("A".$flag2."", "PEDIDOS A '".$v3->nombre."' ".date("d-m-Y"));
			$this->excelfile->getActiveSheet()->getStyle('A'.$flag2.':G'.$flag2.'')->applyFromArray($styleArray);
			$flag2++;
			$this->cellStyle("A".$flag2.":D".$flag2."", "000000", "FFFFFF", TRUE, 12, "Franklin Gothic Book");
			$hoja1->mergeCells('A'.$flag2.':B'.$flag2.'');
			$hoja1->setCellValue("A".$flag2."", "EXISTENCIAS");
			$hoja1->setCellValue("E".$flag2."", "DESCRIPCIÓN");
			$this->cellStyle("E".$flag2."", "000000", "FFFFFF", TRUE, 12, "Franklin Gothic Book");
			$this->excelfile->getActiveSheet()->getStyle('A'.$flag2.':G'.$flag2.'')->applyFromArray($styleArray);

			$this->cellStyle("H".$flag2."", "000000", "FFFFFF", TRUE, 12, "Franklin Gothic Book");
			$hoja1->setCellValue("H".$flag2."", "PENDIENT");
			$this->cellStyle("I".$flag2."", "000000", "FFFFFF", TRUE, 12, "Franklin Gothic Book");
			$hoja1->setCellValue("I".$flag2."", "PENDIENT");
			$this->cellStyle("J".$flag2."", "000000", "FFFFFF", TRUE, 12, "Franklin Gothic Book");
			$hoja1->setCellValue("J".$flag2."", "PENDIENT");
			$this->cellStyle("K".$flag2."", "000000", "FFFFFF", TRUE, 12, "Franklin Gothic Book");
			$hoja1->setCellValue("K".$flag2."", "PENDIENT");
			$this->cellStyle("L".$flag2."", "000000", "FFFFFF", TRUE, 12, "Franklin Gothic Book");
			$hoja1->setCellValue("L".$flag2."", "PENDIENT");
			$this->cellStyle("M".$flag2."", "000000", "FFFFFF", TRUE, 12, "Franklin Gothic Book");
			$hoja1->setCellValue("M".$flag2."", "PENDIENT");
			$this->cellStyle("N".$flag2."", "000000", "FFFFFF", TRUE, 12, "Franklin Gothic Book");
			$hoja1->setCellValue("N".$flag2."", "PENDIENT");
			$this->cellStyle("O".$flag2."", "000000", "FFFFFF", TRUE, 12, "Franklin Gothic Book");
			$hoja1->setCellValue("O".$flag2."", "PENDIENT");
			$this->cellStyle("P".$flag2."", "000000", "FFFFFF", TRUE, 12, "Franklin Gothic Book");
			$hoja1->setCellValue("P".$flag2."", "PENDIENT");
			

			$flag2++;
			$this->cellStyle("A".$flag2.":G".$flag2."", "000000", "FFFFFF", TRUE, 12, "Franklin Gothic Book");
			$hoja1->setCellValue("A".$flag2."", "CAJAS");
			$hoja1->setCellValue("B".$flag2."", "PZAS");
			$hoja1->setCellValue("C".$flag2."", "PEDIDO");
			$hoja1->setCellValue("D".$flag2."", "CÓDIGO");
			$hoja1->setCellValue("G".$flag2."", "PROMOCIÓN");

			$this->cellStyle("H".$flag2, "C00000", "000000", TRUE, 10, "Franklin Gothic Book");
			$this->cellStyle("I".$flag2, "01B0F0", "000000", TRUE, 10, "Franklin Gothic Book");
			$this->cellStyle("J".$flag2, "FF0000", "000000", TRUE, 10, "Franklin Gothic Book");
			$this->cellStyle("K".$flag2, "E26C0B", "000000", TRUE, 10, "Franklin Gothic Book");
			$this->cellStyle("L".$flag2, "C5C5C5", "000000", TRUE, 10, "Franklin Gothic Book");
			$this->cellStyle("M".$flag2, "92D051", "000000", TRUE, 10, "Franklin Gothic Book");
			$this->cellStyle("N".$flag2, "B1A0C7", "000000", TRUE, 10, "Franklin Gothic Book");
			$this->cellStyle("O".$flag2, "DA9694", "000000", TRUE, 10, "Franklin Gothic Book");
			$this->cellStyle("P".$flag2, "4CACC6", "000000", TRUE, 10, "Franklin Gothic Book");
			$hoja1->setCellValue("H".$flag2."", "CEDIS");
			$hoja1->setCellValue("I".$flag2."", "ABARROTES");
			$hoja1->setCellValue("J".$flag2."", "VILLAS");
			$hoja1->setCellValue("K".$flag2."", "TIENDA");
			$hoja1->setCellValue("L".$flag2."", "ULTRA");
			$hoja1->setCellValue("M".$flag2."", "TRINCHERAS");
			$hoja1->setCellValue("N".$flag2."", "MERCADO");
			$hoja1->setCellValue("O".$flag2."", "TENENCIA");
			$hoja1->setCellValue("P".$flag2."", "TIJERAS");

			$this->excelfile->setActiveSheetIndex(1);
			
			$this->cellStyle("A".$flag, "FFFFFF", "000000", TRUE, 12, "Franklin Gothic Book");
			$hoja->setCellValue("A".$flag, "CEDIS,CD INDUSTRIAL, ABARROTES, TIENDA, ULTRAMARINOS, TRINCHERAS, MERCADO, TIJERAS, Y TENENCIA AZTECA AUTOSERVICIOS SA. DE CV.");
			$hoja->mergeCells('A'.$flag.':BM'.$flag);
			$this->excelfile->getActiveSheet()->getStyle('A'.$flag.':BM'.$flag)->applyFromArray($styleArray);
			$flag++;
			$hoja->mergeCells('A'.$flag.':L'.$flag);
			$hoja->mergeCells('M'.$flag.':Q'.$flag);
			$hoja->mergeCells('R'.$flag.':U'.$flag);
			$hoja->mergeCells('V'.$flag.':X'.$flag);
			$hoja->mergeCells('Y'.$flag.':AC'.$flag);
			$hoja->mergeCells('AD'.$flag.':AH'.$flag);
			$hoja->mergeCells('AI'.$flag.':AM'.$flag);
			$hoja->mergeCells('AN'.$flag.':AR'.$flag);
			$hoja->mergeCells('AS'.$flag.':AW'.$flag);
			$hoja->mergeCells('AX'.$flag.':BB'.$flag);
			$hoja->mergeCells('BC'.$flag.':BG'.$flag);
			$hoja->mergeCells('BH'.$flag.':BL'.$flag);
			$this->cellStyle("A".$flag, "FFFFFF", "000000", TRUE, 12, "Franklin Gothic Book");
			$hoja->setCellValue("A".$flag, "PEDIDOS A '".$v3->nombre."' ".date("d-m-Y"));
			$this->cellStyle("M".$flag, "C00000", "000000", TRUE, 12, "Franklin Gothic Book");
			$hoja->setCellValue("M".$flag, "CEDIS");
			$this->cellStyle("R".$flag, "FF0066", "000000", TRUE, 12, "Franklin Gothic Book");
			$hoja->setCellValue("R".$flag, "SUPER INDUSTRIAL");
			$this->cellStyle("V".$flag, "C2B90A", "000000", TRUE, 12, "Franklin Gothic Book");
			$hoja->setCellValue("V".$flag, "SUMA CEDIS");
			$this->cellStyle("Y".$flag, "01B0F0", "000000", TRUE, 12, "Franklin Gothic Book");
			$hoja->setCellValue("Y".$flag, "ABARROTES");
			$this->cellStyle("AD".$flag, "FF0000", "000000", TRUE, 12, "Franklin Gothic Book");
			$hoja->setCellValue("AD".$flag, "VILLAS");
			$this->cellStyle("AI".$flag, "E26C0B", "000000", TRUE, 12, "Franklin Gothic Book");
			$hoja->setCellValue("AI".$flag, "TIENDA");
			$this->cellStyle("AN".$flag, "C5C5C5", "000000", TRUE, 12, "Franklin Gothic Book");
			$hoja->setCellValue("AN".$flag, "ULTRAMARINOS");
			$this->cellStyle("AS".$flag, "92D051", "000000", TRUE, 12, "Franklin Gothic Book");
			$hoja->setCellValue("AS".$flag, "TRINCHERAS");
			$this->cellStyle("AX".$flag, "B1A0C7", "000000", TRUE, 12, "Franklin Gothic Book");
			$hoja->setCellValue("AX".$flag, "AZT MERCADO");
			$this->cellStyle("BC".$flag, "DA9694", "000000", TRUE, 12, "Franklin Gothic Book");
			$hoja->setCellValue("BC".$flag, "TENENCIA");
			$this->cellStyle("BH".$flag, "4CACC6", "000000", TRUE, 12, "Franklin Gothic Book");
			$hoja->setCellValue("BH".$flag, "TIJERAS");
			
			$this->cellStyle("A3:BM4", "000000", "FFFFFF", TRUE, 12, "Franklin Gothic Book");
			$this->excelfile->getActiveSheet()->getStyle('A'.$flag.':BM'.$flag)->applyFromArray($styleArray);
			$flag++;
			$hoja->mergeCells('A'.$flag.':L'.$flag);
			$hoja->mergeCells('M'.$flag.':Q'.$flag);
			$hoja->mergeCells('R'.$flag.':U'.$flag);
			$hoja->mergeCells('V'.$flag.':X'.$flag);
			$hoja->mergeCells('Y'.$flag.':AC'.$flag);
			$hoja->mergeCells('AD'.$flag.':AH'.$flag);
			$hoja->mergeCells('AI'.$flag.':AM'.$flag);
			$hoja->mergeCells('AN'.$flag.':AR'.$flag);
			$hoja->mergeCells('AS'.$flag.':AW'.$flag);
			$hoja->mergeCells('AX'.$flag.':BB'.$flag);
			$hoja->mergeCells('BC'.$flag.':BG'.$flag);
			$hoja->mergeCells('BH'.$flag.':BL'.$flag);
			$this->cellStyle("A".$flag.":BM".$flag."", "000000", "FFFFFF", TRUE, 12, "Franklin Gothic Book");
			$hoja->setCellValue("A".$flag, "DESCRIPCIÓN");
			$hoja->setCellValue("M".$flag, "EXISTENCIAS");
			$hoja->setCellValue("R".$flag, "EXISTENCIAS");
			$hoja->setCellValue("V".$flag, "EXISTENCIAS");
			$hoja->setCellValue("Y".$flag, "EXISTENCIAS");
			$hoja->setCellValue("AD".$flag, "EXISTENCIAS");
			$hoja->setCellValue("AI".$flag, "EXISTENCIAS");
			$hoja->setCellValue("AN".$flag, "EXISTENCIAS");
			$hoja->setCellValue("AS".$flag, "EXISTENCIAS");
			$hoja->setCellValue("AX".$flag, "EXISTENCIAS");
			$hoja->setCellValue("BC".$flag, "EXISTENCIAS");
			$hoja->setCellValue("BH".$flag, "EXISTENCIAS");

			//Begin: TOTALES PEDIDOS PENDIENTES
			$hoja->mergeCells('CA'.$flag.':CK'.$flag);
			$this->cellStyle("CA".$flag.":CK".$flag."", "000000", "FFFFFF", TRUE, 12, "Franklin Gothic Book");
			$hoja->setCellValue("CA".$flag, "TOTAL POR PEDIDOS PENDIENTES");
			//End: TOTALES PEDIDOS PENDIENTES

			$flag++;
			$this->cellStyle("A".$flag.":BM".$flag."", "000000", "FFFFFF", TRUE, 12, "Franklin Gothic Book");
			$hoja->setCellValue("A".$flag, "CÓDIGO");
			$hoja->setCellValue("B".$flag, "FACTURA");
			$hoja->setCellValue("D".$flag, "UM");
			$hoja->setCellValue("E".$flag, "REALES");
			$hoja->setCellValue("F".$flag, "COSTO");
			$hoja->setCellValue("H".$flag, "SISTEMA");
			$hoja->setCellValue("J".$flag, "PRECIO4");
			$hoja->setCellValue("K".$flag, "2DO");
			$hoja->setCellValue("L".$flag, "PROVEEDOR");
			$hoja->setCellValue("M".$flag, "CAJAS");
			$hoja->setCellValue("N".$flag, "PZAS");
			$hoja->setCellValue("O".$flag, "PEND");
			$hoja->setCellValue("P".$flag, "SUGS");
			$hoja->setCellValue("Q".$flag, "PEDIDO");
			$hoja->setCellValue("R".$flag, "CAJAS");
			$hoja->setCellValue("S".$flag, "PZAS");
			$hoja->setCellValue("T".$flag, "SUGS");
			$hoja->setCellValue("U".$flag, "PEDIDO");
			$hoja->setCellValue("V".$flag, "CAJAS");
			$hoja->setCellValue("W".$flag, "PZAS");
			$hoja->setCellValue("X".$flag, "PEDIDO");
			$hoja->setCellValue("Y".$flag, "CAJAS");
			$hoja->setCellValue("Z".$flag, "PZAS");
			$hoja->setCellValue("AA".$flag, "PEND");
			$hoja->setCellValue("AB".$flag, "SUGS");
			$hoja->setCellValue("AC".$flag, "PEDIDO");

			$hoja->setCellValue("AD".$flag, "CAJAS");
			$hoja->setCellValue("AE".$flag, "PZAS");
			$hoja->setCellValue("AF".$flag, "PEND");
			$hoja->setCellValue("AG".$flag, "SUGS");
			$hoja->setCellValue("AH".$flag, "PEDIDO");
			$hoja->setCellValue("AI".$flag, "CAJAS");
			$hoja->setCellValue("AJ".$flag, "PZAS");
			$hoja->setCellValue("AK".$flag, "PEND");
			$hoja->setCellValue("AL".$flag, "SUGS");
			$hoja->setCellValue("AM".$flag, "PEDIDO");
			$hoja->setCellValue("AD".$flag, "CAJAS");
			$hoja->setCellValue("AE".$flag, "PZAS");
			$hoja->setCellValue("AF".$flag, "PEND");
			$hoja->setCellValue("AG".$flag, "SUGS");
			$hoja->setCellValue("AH".$flag, "PEDIDO");
			$hoja->setCellValue("AI".$flag, "CAJAS");
			$hoja->setCellValue("AJ".$flag, "PZAS");
			$hoja->setCellValue("AK".$flag, "PEND");
			$hoja->setCellValue("AL".$flag, "SUGS");
			$hoja->setCellValue("AM".$flag, "PEDIDO");
			$hoja->setCellValue("AN".$flag, "CAJAS");
			$hoja->setCellValue("AO".$flag, "PZAS");
			$hoja->setCellValue("AP".$flag, "PEND");
			$hoja->setCellValue("AQ".$flag, "SUGS");
			$hoja->setCellValue("AR".$flag, "PEDIDO");
			$hoja->setCellValue("AS".$flag, "CAJAS");
			$hoja->setCellValue("AT".$flag, "PZAS");
			$hoja->setCellValue("AU".$flag, "PEND");
			$hoja->setCellValue("AV".$flag, "SUGS");
			$hoja->setCellValue("AW".$flag, "PEDIDO");
			$hoja->setCellValue("AX".$flag, "CAJAS");
			$hoja->setCellValue("AY".$flag, "PZAS");
			$hoja->setCellValue("AZ".$flag, "PEND");
			$hoja->setCellValue("BA".$flag, "SUGS");
			$hoja->setCellValue("BB".$flag, "PEDIDO");
			$hoja->setCellValue("BC".$flag, "CAJAS");
			$hoja->setCellValue("BD".$flag, "PZAS");
			$hoja->setCellValue("BE".$flag, "PEND");
			$hoja->setCellValue("BF".$flag, "SUGS");
			$hoja->setCellValue("BG".$flag, "PEDIDO");
			$hoja->setCellValue("BH".$flag, "CAJAS");
			$hoja->setCellValue("BI".$flag, "PZAS");
			$hoja->setCellValue("BJ".$flag, "PEND");
			$hoja->setCellValue("BK".$flag, "SUGS");
			$hoja->setCellValue("BL".$flag, "PEDIDO");
			
			$hoja->setCellValue("BM".$flag, "PROMOCION");
			$hoja->setCellValue("BW".$flag, "TOTAL");
			$hoja->setCellValue("BX".$flag, "PEDIDOS");
			$this->cellStyle("BN".$flag, "C00000", "000000", TRUE, 12, "Franklin Gothic Book");
			$this->cellStyle("BO".$flag, "01B0F0", "000000", TRUE, 12, "Franklin Gothic Book");
			$this->cellStyle("BP".$flag, "FF0000", "000000", TRUE, 12, "Franklin Gothic Book");
			$this->cellStyle("BQ".$flag, "E26C0B", "000000", TRUE, 12, "Franklin Gothic Book");
			$this->cellStyle("BR".$flag, "C5C5C5", "000000", TRUE, 12, "Franklin Gothic Book");
			$this->cellStyle("BS".$flag, "92D051", "000000", TRUE, 12, "Franklin Gothic Book");
			$this->cellStyle("BT".$flag, "B1A0C7", "000000", TRUE, 12, "Franklin Gothic Book");
			$this->cellStyle("BU".$flag, "DA9694", "000000", TRUE, 12, "Franklin Gothic Book");
			$this->cellStyle("BV".$flag, "4CACC6", "000000", TRUE, 12, "Franklin Gothic Book");
			$this->cellStyle("BW".$flag."", "000000", "FFFFFF", TRUE, 12, "Franklin Gothic Book");
			$this->cellStyle("BX".$flag."", "000000", "FFFFFF", TRUE, 12, "Franklin Gothic Book");

			//Begin: TOTALES PEDIDOS PENDIENTES
			$this->cellStyle("CA".$flag, "C00000", "000000", TRUE, 12, "Franklin Gothic Book");
			$this->cellStyle("CB".$flag, "01B0F0", "000000", TRUE, 12, "Franklin Gothic Book");
			$this->cellStyle("CC".$flag, "FF0000", "000000", TRUE, 12, "Franklin Gothic Book");
			$this->cellStyle("CD".$flag, "E26C0B", "000000", TRUE, 12, "Franklin Gothic Book");
			$this->cellStyle("CE".$flag, "C5C5C5", "000000", TRUE, 12, "Franklin Gothic Book");
			$this->cellStyle("CF".$flag, "92D051", "000000", TRUE, 12, "Franklin Gothic Book");
			$this->cellStyle("CG".$flag, "B1A0C7", "000000", TRUE, 12, "Franklin Gothic Book");
			$this->cellStyle("CH".$flag, "DA9694", "000000", TRUE, 12, "Franklin Gothic Book");
			$this->cellStyle("CI".$flag, "4CACC6", "000000", TRUE, 12, "Franklin Gothic Book");
			$this->cellStyle("CJ".$flag."", "000000", "FFFFFF", TRUE, 12, "Franklin Gothic Book");
			$this->cellStyle("CK".$flag."", "000000", "FFFFFF", TRUE, 12, "Franklin Gothic Book");
			$hoja->setCellValue("CJ".$flag, "TOTAL");
			$hoja->setCellValue("CK".$flag, "PEDIDOS");
			//End: TOTALES PEDIDOS PENDIENTES

			$this->excelfile->getActiveSheet()->getStyle('BM'.$flag)->applyFromArray($styleArray);
			$fecha = new DateTime(date('Y-m-d H:i:s'));
			$where=["ctz_first.id_proveedor" => $v3->id_usuario,"prod.estatus" => 1];//Semana actual
			$intervalo = new DateInterval('P2D');
			$fecha->add($intervalo);
			$cotizacionesProveedor = $this->ct_mdl->getPedidosAll($where, $fecha->format('Y-m-d H:i:s'), 0);
			$bandera = $flag;
			if($cotizacionesProveedor){
				foreach ($cotizacionesProveedor as $key => $value) {
					$this->excelfile->setActiveSheetIndex(0);
					$this->cellStyle("E".$flag1, "000000", "FFFFFF", FALSE, 12, "Franklin Gothic Book");
					$hoja1->setCellValue("E".$flag1, $value['familia']);
					$flag1 +=1;
					//Pedidos
					$this->excelfile->setActiveSheetIndex(1);
					$this->cellStyle("C".$flag, "000000", "FFFFFF", FALSE, 12, "Franklin Gothic Book");
					$hoja->setCellValue("C{$flag}", $value['familia']);
					$flag +=1;
					if ($value['articulos']) {
						foreach ($value['articulos'] as $key => $row){
							if($row["unidad"] === null || $row["unidad"] === ""){
								$row["unidad"] = 1;
							}
							$registrazo = date('Y-m-d',strtotime($row['registrazo']));
							$this->excelfile->setActiveSheetIndex(0);
							$this->cellStyle("A".$flag1.":P".$flag1, "FFFFFF", "000000", FALSE, 12, "Franklin Gothic Book");
							
							$hoja1->setCellValue("D{$flag1}", $row['codigo'])->getStyle("D{$flag1}")->getNumberFormat()->setFormatCode('# ???/???');//Formato de fraccion
							if($row['color'] == '#92CEE3'){
								$this->cellStyle("D{$flag1}", "92CEE3", "000000", FALSE, 12, "Franklin Gothic Book");
							}else{
								$this->cellStyle("D{$flag1}", "FFFFFF", "000000", FALSE, 12, "Franklin Gothic Book");
							}
							$hoja1->setCellValue("E{$flag1}", $row['producto']);
							$hoja1->setCellValue("G{$flag1}", $row['promocion_first']);
							$hoja1->setCellValue("H{$flag1}", $row['cedis']);
							$hoja1->setCellValue("I{$flag1}", $row['abarrotes']);
							$hoja1->setCellValue("J{$flag1}", $row['pedregal']);
							$hoja1->setCellValue("K{$flag1}", $row['tienda']);
							$hoja1->setCellValue("L{$flag1}", $row['ultra']);
							$hoja1->setCellValue("M{$flag1}", $row['trincheras']);
							$hoja1->setCellValue("N{$flag1}", $row['mercado']);
							$hoja1->setCellValue("O{$flag1}", $row['tenencia']);
							$hoja1->setCellValue("P{$flag1}", $row['tijeras']);

							if ($row["imagen"] <> "" && !is_null($row["imagen"]) ) {
								$objDrawing = new PHPExcel_Worksheet_Drawing();
								$objDrawing->setName('COD'.$row['producto']);
								$objDrawing->setDescription('DESC'.$row['codigo']);
								$objDrawing->setPath("./Abarrotes/assets/img/productos/".$row["imagen"]."");
								$objDrawing->setWidth(50);
								$objDrawing->setHeight(50);
								$objDrawing->setCoordinates('F'.$flag1);
								$objDrawing->setOffsetX(5); 
								$objDrawing->setOffsetY(5);
								//$objDrawing->setUrl('http://abarrotesazteca.com/Abarrotes/assets/img/productos/'.$row["imagen"]);
								$objDrawing->setWorksheet($this->excelfile->getActiveSheet());
								$this->excelfile->getActiveSheet()->getRowDimension($flag1)->setRowHeight(60);
								$this->excelfile->getActiveSheet()->getStyleByColumnAndRow(10, $flag1)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_DATE_XLSX14);
								$this->excelfile->getActiveSheet()->getCell('F'.$flag1)->getHyperlink()->setUrl('http://abarrotesazteca.com/Abarrotes/assets/img/productos/'.str_replace("_thumb.",".",$row["imagen"]));
							}

							$hoja1->getStyle("A{$flag1}:P{$flag1}")
					                 ->getAlignment()
					                 ->setHorizontal(\PHPExcel_Style_Alignment::HORIZONTAL_LEFT);
					         
			                
							//Pedidos
							$this->excelfile->setActiveSheetIndex(1);
							$this->cellStyle("A".$flag.":BL".$flag."", "FFFFFF", "000000", FALSE, 12, "Franklin Gothic Book");
							
							$hoja->setCellValue("A{$flag}", $row['codigo'])->getStyle("A{$flag}")->getNumberFormat()->setFormatCode('# ???/???');//Formato de fraccion
							$hoja->setCellValue("B{$flag}", $row['codigo_factura'])->getStyle("B{$flag}")->getNumberFormat()->setFormatCode('# ???/???');//Formato de fraccion
							if($row['color'] == '#92CEE3'){
								$this->cellStyle("A{$flag}", "92CEE3", "000000", FALSE, 12, "Franklin Gothic Book");
							}else{
								$this->cellStyle("A{$flag}", "FFFFFF", "000000", FALSE, 12, "Franklin Gothic Book");
							}
							$hoja->setCellValue("C{$flag}", $row['producto']);
							$hoja->setCellValue("D{$flag}", $row['unidad']);
							
							

							$this->cellStyle("E{$flag}", "FFFFFF", "000000", FALSE, 12, "Franklin Gothic Book");
							$hoja->setCellValue("E{$flag}", $row['reales'])->getStyle("E{$flag}")->getNumberFormat()->setFormatCode('"$"#,##0.00_-');
							if(number_format(($row['precio_first'] - $row['reales']),2) === "0.01" || number_format(($row['precio_first'] - $row['reales']),2) === "-0.01"){
								$this->cellStyle("E{$flag}", "FFFFFF", "000000", FALSE, 12, "Franklin Gothic Book");
							}elseif($row['precio_first'] < $row['reales']){
								$this->cellStyle("E{$flag}", "FFFFFF", "E21600", FALSE, 12, "Franklin Gothic Book");
							}else{
								$this->cellStyle("E{$flag}", "FFFFFF", "249947", FALSE, 12, "Franklin Gothic Book");
							}

							if (number_format(($row['precio_sistema'] - $row['precio_first']),2) === "0.01" || number_format(($row['precio_sistema'] - $row['precio_first']),2) === "-0.01") {
								$hoja->setCellValue("F{$flag}", $row['precio_first'])->getStyle("F{$flag}")->getNumberFormat()->setFormatCode('"$"#,##0.00_-');
								$this->cellStyle("F{$flag}", "FFFFFF", "000000", FALSE, 12, "Franklin Gothic Book");
								$this->cellStyle("F{$flag}", "FFFFFF", "000000", FALSE, 12, "Franklin Gothic Book");
								$this->cellStyle("C{$flag}", "FFFFFF", "000000", FALSE, 12, "Franklin Gothic Book");
							}elseif($row['precio_sistema'] < $row['precio_first']){
								$hoja->setCellValue("F{$flag}", $row['precio_first'])->getStyle("F{$flag}")->getNumberFormat()->setFormatCode('"$"#,##0.00_-');
								$this->cellStyle("F{$flag}", "FDB2B2", "E21111", FALSE, 12, "Franklin Gothic Book");
								$this->cellStyle("F{$flag}", "FDB2B2", "E21111", FALSE, 12, "Franklin Gothic Book");
								$this->cellStyle("C{$flag}", "E21600", "000000", FALSE, 12, "Franklin Gothic Book");
							}else{
								$hoja->setCellValue("F{$flag}", $row['precio_first'])->getStyle("F{$flag}")->getNumberFormat()->setFormatCode('"$"#,##0.00_-');
								$this->cellStyle("F{$flag}", "96EAA8", "0C800C", FALSE, 12, "Franklin Gothic Book");
								$this->cellStyle("C{$flag}", "249947", "000000", FALSE, 12, "Franklin Gothic Book");
							}

							$hoja->setCellValue("H{$flag}", $row['precio_sistema'])->getStyle("H{$flag}")->getNumberFormat()->setFormatCode('"$"#,##0.00_-');//Formto de moneda
						
							$this->cellStyle("H".$flag, "FFFFFF","000000",  FALSE, 12, "Franklin Gothic Book");
							if($row['colorp'] == 1){
								$this->cellStyle("H{$flag}", "D6DCE4", "000000", FALSE, 12, "Franklin Gothic Book");
							}else{
								$this->cellStyle("H{$flag}", "FFFFFF", "000000", FALSE, 12, "Franklin Gothic Book");
							}

							$hoja->setCellValue("J{$flag}", $row['precio_four'])->getStyle("J{$flag}")->getNumberFormat()->setFormatCode('"$"#,##0.00_-');
							$this->cellStyle("J{$flag}", "FFFFFF", "000000", FALSE, 12, "Franklin Gothic Book");
							if($row['precio_sistema'] < $row['precio_next']){
								$hoja->setCellValue("K{$flag}", $row['precio_next'])->getStyle("K{$flag}")->getNumberFormat()->setFormatCode('"$"#,##0.00_-');
								$this->cellStyle("K{$flag}", "FDB2B2", "E21111", FALSE, 12, "Franklin Gothic Book");
							}else if($row['precio_next'] !== NULL){
								$hoja->setCellValue("K{$flag}", $row['precio_next'])->getStyle("K{$flag}")->getNumberFormat()->setFormatCode('"$"#,##0.00_-');
								$this->cellStyle("K{$flag}", "96EAA8", "0C800C", FALSE, 12, "Franklin Gothic Book");
							}else{
								$hoja->setCellValue("K{$flag}", $row['precio_next'])->getStyle("K{$flag}")->getNumberFormat()->setFormatCode('"$"#,##0.00_-');
								$this->cellStyle("K{$flag}", "FFFFFF", "000000", FALSE, 12, "Franklin Gothic Book");
							}
							$hoja->setCellValue("L{$flag}", $row['proveedor_next']);
							$this->cellStyle("M".$flag.":BM".$flag, "FFFFFF", "000000", TRUE, 12, "Franklin Gothic Book");
							$this->cellStyle("L".$flag, "FFFFFF", "000000", FALSE, 12, "Franklin Gothic Book");

							$row[87] = $row[87] === NULL ? 0 :  $row[87];
							$antis = ((floatval($row[87]) * floatval($row["unidad"])) + ($row["unidad"] * $row["past"]["caja0"]) + $row["past"]["pz0"])/$row["unidad"];							
							$hoja->setCellValue("M{$flag}", $row['caja0']);
							$hoja->setCellValue("N{$flag}", $row['pz0']);
							$hoja->setCellValue("O{$flag}", $row['cedis']);
							$hoja->setCellValue("P{$flag}", "=".$antis."-(((M{$flag}*D{$flag})+N{$flag})/D{$flag})")->getStyle("P{$flag}")->getNumberFormat()->setFormatCode('#,##0_-');
							$hoja->setCellValue("Q{$flag}", $row['ped0']);
							$this->cellStyle("Q{$flag}", "D4EAEF", "000000", TRUE, 12, "Franklin Gothic Book");

							

							$row[89] = $row[89] === NULL ? 0 :  $row[89];
							$antis = ((floatval($row[89]) * floatval($row["unidad"])) + ($row["unidad"] * $row["past"]["caja9"]) + $row["past"]["pz9"])/$row["unidad"];							
							$hoja->setCellValue("R{$flag}", $row['caja9']);
							$hoja->setCellValue("S{$flag}", $row['pz9']);
							$hoja->setCellValue("T{$flag}", "=".$antis."-(((R{$flag}*D{$flag})+S{$flag})/D{$flag})")->getStyle("T{$flag}")->getNumberFormat()->setFormatCode('#,##0_-');
							$hoja->setCellValue("U{$flag}", $row['ped9']);
							$this->cellStyle("U{$flag}", "D4EAEF", "000000", TRUE, 12, "Franklin Gothic Book");

							


							$hoja->setCellValue("V{$flag}", "=M".$flag."+R".$flag);
							$hoja->setCellValue("W{$flag}", "=N".$flag."+S".$flag);
							$hoja->setCellValue("X{$flag}", "=Q".$flag."+U".$flag);
							$this->cellStyle("X{$flag}", "D4EAEF", "000000", TRUE, 12, "Franklin Gothic Book");

							$row[57] = $row[57] === NULL ? 0 :  $row[57];
							$antis = ((floatval($row[57]) * floatval($row["unidad"])) + ($row["unidad"] * $row["past"]["caja1"]) + $row["past"]["pz1"])/$row["unidad"];							
							$hoja->setCellValue("Y{$flag}", $row['caja1']);
							$hoja->setCellValue("Z{$flag}", $row['pz1']);
							$hoja->setCellValue("AA{$flag}", $row['abarrotes']);
							$hoja->setCellValue("AB{$flag}", "=".$antis."-(((Y{$flag}*D{$flag})+Z{$flag})/D{$flag})")->getStyle("AB{$flag}")->getNumberFormat()->setFormatCode('#,##0_-');
							$hoja->setCellValue("AC{$flag}", $row['ped1']);
							$this->cellStyle("AC{$flag}", "D4EAEF", "000000", TRUE, 12, "Franklin Gothic Book");

							

							$row[90] = $row[90] === NULL ? 0 :  $row[90];
							$antis = ((floatval($row[90]) * floatval($row["unidad"])) + ($row["unidad"] * $row["past"]["caja2"]) + $row["past"]["pz2"])/$row["unidad"];
							$hoja->setCellValue("AD{$flag}", $row['caja2']);
							$hoja->setCellValue("AE{$flag}", $row['pz2']);
							$hoja->setCellValue("AF{$flag}", $row['pedregal']);
							$hoja->setCellValue("AG{$flag}", "=".$antis."-(((AD{$flag}*D{$flag})+AE{$flag})/D{$flag})")->getStyle("AG{$flag}")->getNumberFormat()->setFormatCode('#,##0_-');
							$hoja->setCellValue("AH{$flag}", $row['ped2']);
							$this->cellStyle("AH{$flag}", "D4EAEF", "000000", TRUE, 12, "Franklin Gothic Book");

							

							$row[58] = $row[58] === NULL ? 0 :  $row[58];
							$antis = ((floatval($row[58]) * floatval($row["unidad"])) + ($row["unidad"] * $row["past"]["caja3"]) + $row["past"]["pz3"])/$row["unidad"];
							$hoja->setCellValue("AI{$flag}", $row['caja3']);
							$hoja->setCellValue("AJ{$flag}", $row['pz3']);
							$hoja->setCellValue("AK{$flag}", $row['tienda']);
							$hoja->setCellValue("AL{$flag}", "=".$antis."-(((AI{$flag}*D{$flag})+AJ{$flag})/D{$flag})")->getStyle("AL{$flag}")->getNumberFormat()->setFormatCode('#,##0_-');
							$hoja->setCellValue("AM{$flag}", $row['ped3']);
							$this->cellStyle("AM{$flag}", "D4EAEF", "000000", TRUE, 12, "Franklin Gothic Book");

							

							$row[59] = $row[59] === NULL ? 0 :  $row[59];
							$antis = ((floatval($row[59]) * floatval($row["unidad"])) + ($row["unidad"] * $row["past"]["caja4"]) + $row["past"]["pz4"])/$row["unidad"];
							$hoja->setCellValue("AN{$flag}", $row['caja4']);
							$hoja->setCellValue("AO{$flag}", $row['pz4']);
							$hoja->setCellValue("AP{$flag}", $row['ultra']);
							$hoja->setCellValue("AQ{$flag}", "=".$antis."-(((AN{$flag}*D{$flag})+AO{$flag})/D{$flag})")->getStyle("AQ{$flag}")->getNumberFormat()->setFormatCode('#,##0_-');
							$hoja->setCellValue("AR{$flag}", $row['ped4']);
							$this->cellStyle("AR{$flag}", "D4EAEF", "000000", TRUE, 12, "Franklin Gothic Book");

							

							$row[60] = $row[60] === NULL ? 0 :  $row[60];
							$antis = ((floatval($row[60]) * floatval($row["unidad"])) + ($row["unidad"] * $row["past"]["caja5"]) + $row["past"]["pz5"])/$row["unidad"];
							$hoja->setCellValue("AS{$flag}", $row['caja5']);
							$hoja->setCellValue("AT{$flag}", $row['pz5']);
							$hoja->setCellValue("AU{$flag}", $row['trincheras']);
							$hoja->setCellValue("AV{$flag}", "=".$antis."-(((AS{$flag}*D{$flag})+AT{$flag})/D{$flag})")->getStyle("AV{$flag}")->getNumberFormat()->setFormatCode('#,##0_-');
							$hoja->setCellValue("AW{$flag}", $row['ped5']);
							$this->cellStyle("AW{$flag}", "D4EAEF", "000000", TRUE, 12, "Franklin Gothic Book");

							

							$row[61] = $row[61] === NULL ? 0 :  $row[61];
							$antis = ((floatval($row[61]) * floatval($row["unidad"])) + ($row["unidad"] * $row["past"]["caja6"]) + $row["past"]["pz6"])/$row["unidad"];
							$hoja->setCellValue("AX{$flag}", $row['caja6']);
							$hoja->setCellValue("AY{$flag}", $row['pz6']);
							$hoja->setCellValue("AZ{$flag}", $row['mercado']);
							$hoja->setCellValue("BA{$flag}", "=".$antis."-(((AX{$flag}*D{$flag})+AY{$flag})/D{$flag})")->getStyle("BA{$flag}")->getNumberFormat()->setFormatCode('#,##0_-');
							$hoja->setCellValue("BB{$flag}", $row['ped6']);
							$this->cellStyle("BB{$flag}", "D4EAEF", "000000", TRUE, 12, "Franklin Gothic Book");

							

							$row[62] = $row[62] === NULL ? 0 :  $row[62];
							$antis = ((floatval($row[62]) * floatval($row["unidad"])) + ($row["unidad"] * $row["past"]["caja7"]) + $row["past"]["pz7"])/$row["unidad"];
							$hoja->setCellValue("BC{$flag}", $row['caja7']);
							$hoja->setCellValue("BD{$flag}", $row['pz7']);
							$hoja->setCellValue("BE{$flag}", $row['tenencia']);
							$hoja->setCellValue("BF{$flag}", "=".$antis."-(((BC{$flag}*D{$flag})+BD{$flag})/D{$flag})")->getStyle("BF{$flag}")->getNumberFormat()->setFormatCode('#,##0_-');
							$hoja->setCellValue("BG{$flag}", $row['ped7']);
							$this->cellStyle("BG{$flag}", "D4EAEF", "000000", TRUE, 12, "Franklin Gothic Book");

							

							$row[63] = $row[63] === NULL ? 0 :  $row[63];
							$antis = ((floatval($row[63]) * floatval($row["unidad"])) + ($row["unidad"] * $row["past"]["caja8"]) + $row["past"]["pz8"])/$row["unidad"];
							$hoja->setCellValue("BH{$flag}", $row['caja8']);
							$hoja->setCellValue("BI{$flag}", $row['pz8']);
							$hoja->setCellValue("BJ{$flag}", $row['tijeras']);
							$hoja->setCellValue("BK{$flag}", "=".$antis."-(((BH{$flag}*D{$flag})+BI{$flag})/D{$flag})")->getStyle("BK{$flag}")->getNumberFormat()->setFormatCode('#,##0_-');
							$hoja->setCellValue("BL{$flag}", $row['ped8']);
							$this->cellStyle("BL{$flag}", "D4EAEF", "000000", TRUE, 12, "Franklin Gothic Book");

							

							

							$hoja->setCellValue("BM{$flag}", $row['promocion_first']);
							$hoja->setCellValue("BN{$flag}", "=F".$flag."*Q".$flag)->getStyle("BN{$flag}")->getNumberFormat()->setFormatCode('"$"#,##0.00_-');
							$hoja->setCellValue("BO{$flag}", "=F".$flag."*AC".$flag)->getStyle("BO{$flag}")->getNumberFormat()->setFormatCode('"$"#,##0.00_-');
							$hoja->setCellValue("BP{$flag}", "=F".$flag."*AH".$flag)->getStyle("BP{$flag}")->getNumberFormat()->setFormatCode('"$"#,##0.00_-');
							$hoja->setCellValue("BQ{$flag}", "=F".$flag."*AM".$flag)->getStyle("BQ{$flag}")->getNumberFormat()->setFormatCode('"$"#,##0.00_-');
							$hoja->setCellValue("BR{$flag}", "=F".$flag."*AR".$flag)->getStyle("BR{$flag}")->getNumberFormat()->setFormatCode('"$"#,##0.00_-');
							$hoja->setCellValue("BS{$flag}", "=F".$flag."*AW".$flag)->getStyle("BS{$flag}")->getNumberFormat()->setFormatCode('"$"#,##0.00_-');
							$hoja->setCellValue("BT{$flag}", "=F".$flag."*BB".$flag)->getStyle("BT{$flag}")->getNumberFormat()->setFormatCode('"$"#,##0.00_-');
							$hoja->setCellValue("BU{$flag}", "=F".$flag."*BG".$flag)->getStyle("BU{$flag}")->getNumberFormat()->setFormatCode('"$"#,##0.00_-');
							$hoja->setCellValue("BV{$flag}", "=F".$flag."*BL".$flag)->getStyle("BV{$flag}")->getNumberFormat()->setFormatCode('"$"#,##0.00_-');
							$this->cellStyle("BW{$flag}", "D4EAEF", "000000", FALSE, 12, "Franklin Gothic Book");
							$hoja->setCellValue("BW{$flag}", "=SUM(BN".$flag.":BV".$flag.")")->getStyle("BW{$flag}")->getNumberFormat()->setFormatCode('"$"#,##0.00_-');
							$this->cellStyle("BX{$flag}", "C2B90A", "000000", FALSE, 12, "Franklin Gothic Book");
							$hoja->setCellValue("BX{$flag}", "=Q".$flag."+AC".$flag."+AH".$flag."+AM".$flag."+AR".$flag."+AW".$flag."+BB".$flag."+BG".$flag."+BL".$flag."");

							//Begin: TOTALES PEDIDOS PENDIENTES
							$hoja->setCellValue("CA{$flag}", "=F".$flag."*O".$flag)->getStyle("CA{$flag}")->getNumberFormat()->setFormatCode('"$"#,##0.00_-');
							$hoja->setCellValue("CB{$flag}", "=F".$flag."*AA".$flag)->getStyle("CB{$flag}")->getNumberFormat()->setFormatCode('"$"#,##0.00_-');
							$hoja->setCellValue("CC{$flag}", "=F".$flag."*AF".$flag)->getStyle("CC{$flag}")->getNumberFormat()->setFormatCode('"$"#,##0.00_-');
							$hoja->setCellValue("CD{$flag}", "=F".$flag."*AK".$flag)->getStyle("CD{$flag}")->getNumberFormat()->setFormatCode('"$"#,##0.00_-');
							$hoja->setCellValue("CE{$flag}", "=F".$flag."*AP".$flag)->getStyle("CE{$flag}")->getNumberFormat()->setFormatCode('"$"#,##0.00_-');
							$hoja->setCellValue("CF{$flag}", "=F".$flag."*AU".$flag)->getStyle("CF{$flag}")->getNumberFormat()->setFormatCode('"$"#,##0.00_-');
							$hoja->setCellValue("CG{$flag}", "=F".$flag."*AZ".$flag)->getStyle("CG{$flag}")->getNumberFormat()->setFormatCode('"$"#,##0.00_-');
							$hoja->setCellValue("CH{$flag}", "=F".$flag."*BE".$flag)->getStyle("CH{$flag}")->getNumberFormat()->setFormatCode('"$"#,##0.00_-');
							$hoja->setCellValue("CI{$flag}", "=F".$flag."*BJ".$flag)->getStyle("CI{$flag}")->getNumberFormat()->setFormatCode('"$"#,##0.00_-');
							$this->cellStyle("CJ{$flag}", "000000", "FFFFFF", FALSE, 12, "Franklin Gothic Book");
							$hoja->setCellValue("CJ{$flag}", "=SUM(CA".$flag.":CI".$flag.")")->getStyle("CJ{$flag}")->getNumberFormat()->setFormatCode('"$"#,##0.00_-');
							$this->cellStyle("CK{$flag}", "000000", "FFFFFF", FALSE, 12, "Franklin Gothic Book");
							$hoja->setCellValue("CK{$flag}", "=O".$flag."+AA".$flag."+AF".$flag."+AK".$flag."+AP".$flag."+AU".$flag."+AZ".$flag."+BE".$flag."+BJ".$flag."");
							//End: TOTALES PEDIDOS PENDIENTES
							
							$border_style= array('borders' => array('right' => array('style' =>
								PHPExcel_Style_Border::BORDER_THIN,'color' => array('argb' => '000000'),)));
							$this->excelfile->setActiveSheetIndex(1);
							$this->excelfile->getActiveSheet()->getStyle('A'.$flag.':BM'.$flag)->applyFromArray($styleArray);
							$this->excelfile->getActiveSheet()->getStyle('BW'.$flag)->applyFromArray($styleArray);
							$this->excelfile->getActiveSheet()->getStyle('BX'.$flag)->applyFromArray($styleArray);
							$this->excelfile->setActiveSheetIndex(0);
							$this->excelfile->getActiveSheet()->getStyle('A'.$flag1.':P'.$flag1)->applyFromArray($styleArray);
							$hoja->getStyle("A{$flag}:L{$flag}")
					                 ->getAlignment()
					                 ->setHorizontal(\PHPExcel_Style_Alignment::HORIZONTAL_LEFT);

						    if($this->weekNumber($registrazo) == ($this->weekNumber() - 1) || $this->weekNumber($registrazo) == ($this->weekNumber())){
								$this->cellStyle("A{$flag}", "FF7F71", "000000", FALSE, 12, "Franklin Gothic Book");
								$this->cellStyle("B{$flag}", "FF7F71", "000000", FALSE, 12, "Franklin Gothic Book");
							}
							if($row['precio_sistema'] == 0){
								$row['precio_sistema'] = 1;
							}
							if($row['precio_four'] == 0){
								$row['precio_four'] = 1;
							}

							$hoja->setCellValue("G{$flag}",100 - ($row['precio_first'] * 100 / $row['precio_sistema']))->getStyle("G{$flag}")->getNumberFormat()->setFormatCode('"%"#,##0.00_-');;
							$this->cellStyle("G".$flag, "FF9999", "000000", FALSE, 10, "Franklin Gothic Book");

							$hoja->setCellValue("I{$flag}", 100 - ($row['precio_sistema'] * 100 / $row['precio_four']))->getStyle("I{$flag}")->getNumberFormat()->setFormatCode('"%"#,##0.00_-');;
							$this->cellStyle("I".$flag, "FF9999", "000000", FALSE, 10, "Franklin Gothic Book");

							


							$flag ++;
							$flag1 ++;
						}
					}
				}
			}
			$flans = $flag - 1;
			$hoja->setCellValue("BN{$flag}", "=SUM(BN{$bandera}:BN{$flans})")->getStyle("BN{$flag}")->getNumberFormat()->setFormatCode('"$"#,##0.00_-');
			$hoja->setCellValue("BO{$flag}", "=SUM(BO{$bandera}:BO{$flans})")->getStyle("BO{$flag}")->getNumberFormat()->setFormatCode('"$"#,##0.00_-');
			$hoja->setCellValue("BP{$flag}", "=SUM(BP{$bandera}:BP{$flans})")->getStyle("BP{$flag}")->getNumberFormat()->setFormatCode('"$"#,##0.00_-');
			$hoja->setCellValue("BQ{$flag}", "=SUM(BQ{$bandera}:BQ{$flans})")->getStyle("BQ{$flag}")->getNumberFormat()->setFormatCode('"$"#,##0.00_-');
			$hoja->setCellValue("BR{$flag}", "=SUM(BR{$bandera}:BR{$flans})")->getStyle("BR{$flag}")->getNumberFormat()->setFormatCode('"$"#,##0.00_-');
			$hoja->setCellValue("BS{$flag}", "=SUM(BS{$bandera}:BS{$flans})")->getStyle("BS{$flag}")->getNumberFormat()->setFormatCode('"$"#,##0.00_-');
			$hoja->setCellValue("BT{$flag}", "=SUM(BT{$bandera}:BT{$flans})")->getStyle("BT{$flag}")->getNumberFormat()->setFormatCode('"$"#,##0.00_-');
			$hoja->setCellValue("BU{$flag}", "=SUM(BU{$bandera}:BU{$flans})")->getStyle("BU{$flag}")->getNumberFormat()->setFormatCode('"$"#,##0.00_-');
			$hoja->setCellValue("BV{$flag}", "=SUM(BV{$bandera}:BV{$flans})")->getStyle("BV{$flag}")->getNumberFormat()->setFormatCode('"$"#,##0.00_-');
			$hoja->setCellValue("BW{$flag}", "=SUM(BW{$bandera}:BW{$flans})")->getStyle("BW{$flag}")->getNumberFormat()->setFormatCode('"$"#,##0.00_-');

			//Begin: TOTALES PEDIDOS PENDIENTES
			$hoja->setCellValue("CA{$flag}", "=SUM(CA{$bandera}:CA{$flans})")->getStyle("CA{$flag}")->getNumberFormat()->setFormatCode('"$"#,##0.00_-');
			$hoja->setCellValue("CB{$flag}", "=SUM(CB{$bandera}:CB{$flans})")->getStyle("CB{$flag}")->getNumberFormat()->setFormatCode('"$"#,##0.00_-');
			$hoja->setCellValue("CC{$flag}", "=SUM(CC{$bandera}:CC{$flans})")->getStyle("CC{$flag}")->getNumberFormat()->setFormatCode('"$"#,##0.00_-');
			$hoja->setCellValue("CD{$flag}", "=SUM(CD{$bandera}:CD{$flans})")->getStyle("CD{$flag}")->getNumberFormat()->setFormatCode('"$"#,##0.00_-');
			$hoja->setCellValue("CE{$flag}", "=SUM(CE{$bandera}:CE{$flans})")->getStyle("CE{$flag}")->getNumberFormat()->setFormatCode('"$"#,##0.00_-');
			$hoja->setCellValue("CF{$flag}", "=SUM(CF{$bandera}:CF{$flans})")->getStyle("CF{$flag}")->getNumberFormat()->setFormatCode('"$"#,##0.00_-');
			$hoja->setCellValue("CG{$flag}", "=SUM(CG{$bandera}:CG{$flans})")->getStyle("CG{$flag}")->getNumberFormat()->setFormatCode('"$"#,##0.00_-');
			$hoja->setCellValue("CH{$flag}", "=SUM(CH{$bandera}:CH{$flans})")->getStyle("CH{$flag}")->getNumberFormat()->setFormatCode('"$"#,##0.00_-');
			$hoja->setCellValue("CI{$flag}", "=SUM(CI{$bandera}:CI{$flans})")->getStyle("CI{$flag}")->getNumberFormat()->setFormatCode('"$"#,##0.00_-');
			$hoja->setCellValue("CJ{$flag}", "=SUM(CJ{$bandera}:CJ{$flans})")->getStyle("CJ{$flag}")->getNumberFormat()->setFormatCode('"$"#,##0.00_-');
			//End: TOTALES PEDIDOS PENDIENTES

			$flans = $flag;
			$flag += 4;
			//Begin: TOTALES PEDIDOS PENDIENTES
			$hoja->mergeCells('F'.($flag-1).':I'.($flag-1));
			$this->cellStyle('F'.($flag-1).':I'.($flag-1), "000000", "FFFFFF", TRUE, 12, "Franklin Gothic Book");
			$hoja->setCellValue("F".($flag-1), "TOTALES POR PENDIENTES");
			//End: TOTALES PEDIDOS PENDIENTES
			$this->cellStyle("C".$flag, "66FFFB", "000000", TRUE, 12, "Franklin Gothic Book");
			$hoja->setCellValue("C".$flag, "CEDIS");
			$hoja->setCellValue("D{$flag}", "=(BN{$flans})")->getStyle("D{$flag}")->getNumberFormat()->setFormatCode('"$"#,##0.00_-');
			//Begin: TOTALES PEDIDOS PENDIENTES
			$hoja->mergeCells('F'.$flag.':G'.$flag);
			$hoja->mergeCells('H'.$flag.':I'.$flag);
			$this->cellStyle('F'.$flag.':G'.$flag, "000000", "FFFFFF", TRUE, 12, "Franklin Gothic Book");
			$hoja->setCellValue("F".$flag, "CEDIS");
			$hoja->setCellValue("H{$flag}", "=(CA{$flans})")->getStyle("H{$flag}")->getNumberFormat()->setFormatCode('"$"#,##0.00_-');
			//End: TOTALES PEDIDOS PENDIENTES
			$flag++;
			$this->cellStyle("C".$flag, "01B0F0", "000000", TRUE, 12, "Franklin Gothic Book");
			$hoja->setCellValue("C".$flag, "ABARROTES");
			$hoja->setCellValue("D{$flag}", "=(BO{$flans})")->getStyle("D{$flag}")->getNumberFormat()->setFormatCode('"$"#,##0.00_-');
			//Begin: TOTALES PEDIDOS PENDIENTES
			$hoja->mergeCells('F'.$flag.':G'.$flag);
			$hoja->mergeCells('H'.$flag.':I'.$flag);
			$this->cellStyle('F'.$flag.':G'.$flag, "000000", "FFFFFF", TRUE, 12, "Franklin Gothic Book");
			$hoja->setCellValue("F".$flag, "ABARROTES");
			$hoja->setCellValue("H{$flag}", "=(CB{$flans})")->getStyle("H{$flag}")->getNumberFormat()->setFormatCode('"$"#,##0.00_-');
			//End: TOTALES PEDIDOS PENDIENTES
			$flag++;
			$this->cellStyle("C".$flag, "FF0000", "000000", TRUE, 12, "Franklin Gothic Book");
			$hoja->setCellValue("C".$flag, "VILLAS");
			$hoja->setCellValue("D{$flag}", "=(BP{$flans})")->getStyle("D{$flag}")->getNumberFormat()->setFormatCode('"$"#,##0.00_-');
			//Begin: TOTALES PEDIDOS PENDIENTES
			$hoja->mergeCells('F'.$flag.':G'.$flag);
			$hoja->mergeCells('H'.$flag.':I'.$flag);
			$this->cellStyle('F'.$flag.':G'.$flag, "000000", "FFFFFF", TRUE, 12, "Franklin Gothic Book");
			$hoja->setCellValue("F".$flag, "VILLAS");
			$hoja->setCellValue("H{$flag}", "=(CC{$flans})")->getStyle("H{$flag}")->getNumberFormat()->setFormatCode('"$"#,##0.00_-');
			//End: TOTALES PEDIDOS PENDIENTES
			$flag++;
			$this->cellStyle("C".$flag, "E26C0B", "000000", TRUE, 12, "Franklin Gothic Book");
			$hoja->setCellValue("C".$flag, "TIENDA");
			$hoja->setCellValue("D{$flag}", "=(BQ{$flans})")->getStyle("D{$flag}")->getNumberFormat()->setFormatCode('"$"#,##0.00_-');
			//Begin: TOTALES PEDIDOS PENDIENTES
			$hoja->mergeCells('F'.$flag.':G'.$flag);
			$hoja->mergeCells('H'.$flag.':I'.$flag);
			$this->cellStyle('F'.$flag.':G'.$flag, "000000", "FFFFFF", TRUE, 12, "Franklin Gothic Book");
			$hoja->setCellValue("F".$flag, "TIENDA");
			$hoja->setCellValue("H{$flag}", "=(CD{$flans})")->getStyle("H{$flag}")->getNumberFormat()->setFormatCode('"$"#,##0.00_-');
			//End: TOTALES PEDIDOS PENDIENTES
			$flag++;
			$this->cellStyle("C".$flag, "C5C5C5", "000000", TRUE, 12, "Franklin Gothic Book");
			$hoja->setCellValue("C".$flag, "ULTRAMARINOS");
			$hoja->setCellValue("D{$flag}", "=(BR{$flans})")->getStyle("D{$flag}")->getNumberFormat()->setFormatCode('"$"#,##0.00_-');
			//Begin: TOTALES PEDIDOS PENDIENTES
			$hoja->mergeCells('F'.$flag.':G'.$flag);
			$hoja->mergeCells('H'.$flag.':I'.$flag);
			$this->cellStyle('F'.$flag.':G'.$flag, "000000", "FFFFFF", TRUE, 12, "Franklin Gothic Book");
			$hoja->setCellValue("F".$flag, "ULTRAMARINOS");
			$hoja->setCellValue("H{$flag}", "=(CE{$flans})")->getStyle("H{$flag}")->getNumberFormat()->setFormatCode('"$"#,##0.00_-');
			//End: TOTALES PEDIDOS PENDIENTES
			$flag++;
			$this->cellStyle("C".$flag, "92D051", "000000", TRUE, 12, "Franklin Gothic Book");
			$hoja->setCellValue("C".$flag, "TRINCHERAS");
			$hoja->setCellValue("D{$flag}", "=(BS{$flans})")->getStyle("D{$flag}")->getNumberFormat()->setFormatCode('"$"#,##0.00_-');
			//Begin: TOTALES PEDIDOS PENDIENTES
			$hoja->mergeCells('F'.$flag.':G'.$flag);
			$hoja->mergeCells('H'.$flag.':I'.$flag);
			$this->cellStyle('F'.$flag.':G'.$flag, "000000", "FFFFFF", TRUE, 12, "Franklin Gothic Book");
			$hoja->setCellValue("F".$flag, "TRINCHERAS");
			$hoja->setCellValue("H{$flag}", "=(CF{$flans})")->getStyle("H{$flag}")->getNumberFormat()->setFormatCode('"$"#,##0.00_-');
			//End: TOTALES PEDIDOS PENDIENTES
			$flag++;
			$this->cellStyle("C".$flag, "B1A0C7", "000000", TRUE, 12, "Franklin Gothic Book");
			$hoja->setCellValue("C".$flag, "AZT MERCADO");
			$hoja->setCellValue("D{$flag}", "=(BT{$flans})")->getStyle("D{$flag}")->getNumberFormat()->setFormatCode('"$"#,##0.00_-');
			//Begin: TOTALES PEDIDOS PENDIENTES
			$hoja->mergeCells('F'.$flag.':G'.$flag);
			$hoja->mergeCells('H'.$flag.':I'.$flag);
			$this->cellStyle('F'.$flag.':G'.$flag, "000000", "FFFFFF", TRUE, 12, "Franklin Gothic Book");
			$hoja->setCellValue("F".$flag, "AZT MERCADO");
			$hoja->setCellValue("H{$flag}", "=(CG{$flans})")->getStyle("H{$flag}")->getNumberFormat()->setFormatCode('"$"#,##0.00_-');
			//End: TOTALES PEDIDOS PENDIENTES
			$flag++;
			$this->cellStyle("C".$flag, "DA9694", "000000", TRUE, 12, "Franklin Gothic Book");
			$hoja->setCellValue("C".$flag, "TENENCIA");
			$hoja->setCellValue("D{$flag}", "=(BU{$flans})")->getStyle("D{$flag}")->getNumberFormat()->setFormatCode('"$"#,##0.00_-');
			//Begin: TOTALES PEDIDOS PENDIENTES
			$hoja->mergeCells('F'.$flag.':G'.$flag);
			$hoja->mergeCells('H'.$flag.':I'.$flag);
			$this->cellStyle('F'.$flag.':G'.$flag, "000000", "FFFFFF", TRUE, 12, "Franklin Gothic Book");
			$hoja->setCellValue("F".$flag, "TENENCIA");
			$hoja->setCellValue("H{$flag}", "=(CH{$flans})")->getStyle("H{$flag}")->getNumberFormat()->setFormatCode('"$"#,##0.00_-');
			//End: TOTALES PEDIDOS PENDIENTES
			$flag++;
			$this->cellStyle("C".$flag, "4CACC6", "000000", TRUE, 12, "Franklin Gothic Book");
			$hoja->setCellValue("C".$flag, "TIJERAS");
			$hoja->setCellValue("D{$flag}", "=(BV{$flans})")->getStyle("D{$flag}")->getNumberFormat()->setFormatCode('"$"#,##0.00_-');
			//Begin: TOTALES PEDIDOS PENDIENTES
			$hoja->mergeCells('F'.$flag.':G'.$flag);
			$hoja->mergeCells('H'.$flag.':I'.$flag);
			$this->cellStyle('F'.$flag.':G'.$flag, "000000", "FFFFFF", TRUE, 12, "Franklin Gothic Book");
			$hoja->setCellValue("F".$flag, "TIJERAS");
			$hoja->setCellValue("H{$flag}", "=(CI{$flans})")->getStyle("H{$flag}")->getNumberFormat()->setFormatCode('"$"#,##0.00_-');
			//End: TOTALES PEDIDOS PENDIENTES
			$flag++;
			$this->cellStyle("C".$flag, "000000", "FFFFFF", TRUE, 12, "Franklin Gothic Book");
			$hoja->setCellValue("C".$flag, "TOTAL");
			$hoja->setCellValue("D{$flag}", "=(BW{$flans})")->getStyle("D{$flag}")->getNumberFormat()->setFormatCode('"$"#,##0.00_-');
			//Begin: TOTALES PEDIDOS PENDIENTES
			$hoja->mergeCells('F'.$flag.':G'.$flag);
			$hoja->mergeCells('H'.$flag.':I'.$flag);
			$this->cellStyle('F'.$flag.':G'.$flag, "000000", "FFFFFF", TRUE, 12, "Franklin Gothic Book");
			$hoja->setCellValue("F".$flag, "TOTAL");
			$hoja->setCellValue("H{$flag}", "=(CJ{$flans})")->getStyle("H{$flag}")->getNumberFormat()->setFormatCode('"$"#,##0.00_-');
			//End: TOTALES PEDIDOS PENDIENTES
			$flag++;


			$flag = $flag+5;
			$flag1 = $flag1+5;
		}

		$dias = array("DOMINGO","LUNES","MARTES","MIÉRCOLES","JUEVES","VIERNES","SÁBADO");
		$meses = array("ENERO","FEBRERO","MARZO","ABRIL","MAYO","JUNIO","JULIO","AGOSTO","SEPTIEMBRE","OCTUBRE","NOVIEMBRE","DICIEMBRE");
		$fecha =  $dias[date('w')]." ".date('d')." DE ".$meses[date('n')-1]. " DEL ".date('Y') ;
		$file_name = "FORMATO 19 HNOS - CORONA ".$fecha.".xlsx"; //Nombre del documento con extención
		$excel_Writer = PHPExcel_IOFactory::createWriter($this->excelfile, "Excel2007");
		header("Content-Type: application/vnd.ms-excel; charset=utf-8");
		header("Content-Disposition: attachment;filename=".$file_name);
		header("Cache-Control: max-age=0");
		$excel_Writer->save("php://output");
	}
	public function getUserIP(){
	    if(!empty($_SERVER['HTTP_CLIENT_IP'])){
	        //ip from share internet
	        $ip = $_SERVER['HTTP_CLIENT_IP'];
	    }elseif(!empty($_SERVER['HTTP_X_FORWARDED_FOR'])){
	        //ip pass from proxy
	        $ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
	    }else{
	        $ip = $_SERVER['REMOTE_ADDR'];
	    }
		return $ip;
	}
	public function fill_cotiz33(){
		ini_set("memory_limit", "-1");
		ini_set("max_execution_time", "-1");
		$this->load->library("excelfile");
		
		$fecha = new DateTime(date('Y-m-d H:i:s'));
		$intervalo = new DateInterval('P2D');
		$fecha->add($intervalo);
		$fecha = $fecha->format('Y-m-d H:i:s');
		$probes = $this->usua_mdl->getDudes(NULL);
		$hojs = 1;

		$this->load->library("excelfile");
		$hoja1 = $this->excelfile->setActiveSheetIndex(0);
		$this->excelfile->setActiveSheetIndex(0)->setTitle("COTIZACIÓN");
		$styleArray = array(
			  'borders' => array(
			    'allborders' => array(
			      'style' => PHPExcel_Style_Border::BORDER_THIN
			    )
			  )
			);


		if ($probes){
			foreach ($probes as $key => $probns){
				$this->excelfile->createSheet();
				$hoja = $this->excelfile->setActiveSheetIndex($hojs);
				$hojs++;
				if(strlen($probns->nombre) < 25){
					$hoja->setTitle(" ".$probns->nombre);
				}else{
					$hoja->setTitle(" ".substr($probns->nombre,0,25));
				}
				

				$hoja = $this->excelfile->getActiveSheet();

				$this->cellStyle("A1:D2", "000000", "FFFFFF", TRUE, 12, "Franklin Gothic Book");
				$this->cellStyle("A1:D2", "000000", "FFFFFF", TRUE, 12, "Franklin Gothic Book");
				$border_style= array('borders' => array('right' => array('style' =>
					PHPExcel_Style_Border::BORDER_THIN,'color' => array('argb' => '000000'),)));
				$hoja->setCellValue("B1", "DESCRIPCIÓN")->getColumnDimension('B')->setWidth(70);
				$hoja->setCellValue("C1", "PRECIO")->getColumnDimension('C')->setWidth(15);
				$hoja->setCellValue("D1", "PROMOCIÓN")->getColumnDimension('D')->setWidth(50);
				$hoja->setCellValue("A2", "CÓDIGO")->getColumnDimension('A')->setWidth(30);

				$productos = $this->prod_mdl->getProdFam22(NULL,$probns->id_usuario);
				$provs = $this->usua_mdl->get(NULL, ['id_usuario'=>$probns->id_usuario])[0];
				$row_print = 2;
				if ($productos){
					foreach ($productos as $key => $value){
						$hoja->setCellValue("B{$row_print}", $value['familia']);
						$hoja->setCellValue("C{$row_print}", $provs->nombre.' '.$provs->apellido);
						$this->cellStyle("B{$row_print}", "000000", "FFFFFF", TRUE, 12, "Franklin Gothic Book");
						$row_print +=1;
						if ($value['articulos']) {
							foreach ($value['articulos'] as $key => $row){
								$hoja->setCellValue("A{$row_print}", $row['codigo']);
								$hoja->setCellValue("B{$row_print}", $row['producto']);
								$hoja->setCellValue("C{$row_print}", $row['precio_promocion'])->getStyle("C{$row_print}")->getNumberFormat()->setFormatCode('"$"#,##0.00_-');
								$hoja->setCellValue("D{$row_print}", $row['observaciones']);
								if($row['color'] == '#92CEE3'){
									$this->cellStyle("A{$row_print}", "92CEE3", "000000", FALSE, 10, "Franklin Gothic Book");
								}else{
									$this->cellStyle("A{$row_print}", "FFFFFF", "000000", FALSE, 10, "Franklin Gothic Book");
								}
								if($row['estatus'] == 2){
									$this->cellStyle("B{$row_print}", "00B0F0", "000000", FALSE, 10, "Franklin Gothic Book");
								}
								if($row['estatus'] == 3){
									$this->cellStyle("B{$row_print}", "FFF900", "000000", FALSE, 10, "Franklin Gothic Book");
								}
								if($row['estatus'] >= 4){
									$this->cellStyle("B{$row_print}", "04B486", "000000", FALSE, 10, "Franklin Gothic Book");
								}
								$this->excelfile->getActiveSheet()->getStyle('A'.$row_print.':D'.$row_print)->applyFromArray($styleArray);
								$row_print++;
							}
						}
					}
				}
				
			}
		}

		
        $dias = array("DOMINGO","LUNES","MARTES","MIÉRCOLES","JUEVES","VIERNES","SÁBADO");
		$meses = array("ENERO","FEBRERO","MARZO","ABRIL","MAYO","JUNIO","JULIO","AGOSTO","SEPTIEMBRE","OCTUBRE","NOVIEMBRE","DICIEMBRE");
		$fecha =  $dias[date('w')]." ".date('d')." DE ".$meses[date('n')-1]. " DEL ".date('Y') ;
		$file_name = "COTIZACIONES ".$fecha.".xlsx"; //Nombre del documento con extención
		header("Content-Type: application/vnd.ms-excel; charset=utf-8");
		header("Content-Disposition: attachment;filename=".$file_name."");
		header("Cache-Control: max-age=0");
		$excel_Writer = PHPExcel_IOFactory::createWriter($this->excelfile, "Excel2007");
		$excel_Writer->save("php://output");
	}
	public function fill_cotiz(){
		ini_set("memory_limit", "-1");
		ini_set("max_execution_time", "-1");
		$this->load->library("excelfile");
		//FECHA EN FORMATO COMPLETO PARA LOS TITULOS Y TABLAS
		$dias = array("DOMINGO","LUNES","MARTES","MIÉRCOLES","JUEVES","VIERNES","SÁBADO");
		$meses = array("ENERO","FEBRERO","MARZO","ABRIL","MAYO","JUNIO","JULIO","AGOSTO","SEPTIEMBRE","OCTUBRE","NOVIEMBRE","DICIEMBRE");
		$fecha =  $dias[date('w')]." ".date('d')." DE ".$meses[date('n')-1]. " DEL ".date('Y') ;
		$day = date('w');

		$hoja = $this->excelfile->setActiveSheetIndex(0);
		$this->excelfile->setActiveSheetIndex(0)->setTitle($dias[date('w')]." ".date('d'));
        $this->excelfile->setActiveSheetIndex(0);

		$styleArray = array(
		  'borders' => array(
		    'allborders' => array(
		      'style' => PHPExcel_Style_Border::BORDER_THIN
		    )
		  )
		);
		$styleArray2 = array(
		  'borders' => array(
		    'allborders' => array(
		      'style' => PHPExcel_Style_Border::BORDER_MEDIUM
		    )
		  )
		);
		$hoja = $this->excelfile->getActiveSheet();

		$rws = 1;

		$columnas = $this->ct_mdl->columnas(NULL);
		$fecha = new DateTime(date('Y-m-d H:i:s'));
		$intervalo = new DateInterval('P3D');
		$fecha->add($intervalo);
		$fecha = $fecha->format('Y-m-d H:i:s');
		$cotizacionesProveedor = $this->ct_mdl->comparaCotizaciones3(NULL,$fecha,0);
		$this->excelfile->getActiveSheet()->getRowDimension('1')->setRowHeight(40);

		$this->cellStyle("A".$rws, "000000", "FFFFFF", TRUE, 12, "Franklin Gothic Book");
		$hoja->setCellValue("A".$rws, "CÓDIGO")->getColumnDimension('A')->setWidth(20);
		$this->cellStyle("B".$rws, "000000", "FFFFFF", TRUE, 12, "Franklin Gothic Book");
		$hoja->setCellValue("B".$rws, "DESCRIPCIÓN")->getColumnDimension('B')->setWidth(50);
		$this->cellStyle("C".$rws, "000000", "FFFFFF", TRUE, 12, "Franklin Gothic Book");
		$hoja->setCellValue("C".$rws, "PRECIO SISTEMA")->getColumnDimension('C')->setWidth(20);
		$this->cellStyle("D".$rws, "000000", "FFFFFF", TRUE, 12, "Franklin Gothic Book");
		$hoja->setCellValue("D".$rws, "PRECIO 4")->getColumnDimension('D')->setWidth(20);
		$this->cellStyle("E".$rws, "000000", "FFFFFF", TRUE, 12, "Franklin Gothic Book");
		$hoja->setCellValue("E".$rws, "PRECIO REAL")->getColumnDimension('E')->setWidth(20);
		$this->cellStyle("F".$rws, "000000", "FFFFFF", TRUE, 12, "Franklin Gothic Book");
		$hoja->setCellValue("F".$rws, "1ER PRECIO")->getColumnDimension('F')->setWidth(20);
		$this->cellStyle("G".$rws, "000000", "FFFFFF", TRUE, 12, "Franklin Gothic Book");
		$hoja->setCellValue("G".$rws, "PROVEEDOR")->getColumnDimension('G')->setWidth(30);
		$this->excelfile->getActiveSheet()->getStyle('A'.$rws.":G".$rws)->applyFromArray($styleArray2);
		$colFlag = 7;
		$rws = 1;
		foreach ($columnas as $key => $val) {
			if ($val["cuantos"] > 10) {
				$this->cellStyle($this->getColumna($colFlag).''.$rws, "000000", "FFFFFF", TRUE, 12, "Franklin Gothic Book");
				$hoja->setCellValue($this->getColumna($colFlag).''.$rws, $val["nombre"])->getColumnDimension($this->getColumna($colFlag).'')->setWidth(20);
				$this->excelfile->getActiveSheet()->getStyle($this->getColumna($colFlag).''.$rws)->applyFromArray($styleArray2);
				$colFlag++;
			}elseif ($val["cuantos"] === -1) {
				$this->cellStyle($this->getColumna($colFlag).''.$rws, "000000", "FFFFFF", TRUE, 12, "Franklin Gothic Book");
				$hoja->setCellValue($this->getColumna($colFlag).''.$rws, "P. D.")->getColumnDimension($this->getColumna($colFlag).'')->setWidth(20);
				$this->excelfile->getActiveSheet()->getStyle($this->getColumna($colFlag).''.$rws)->applyFromArray($styleArray2);
				$colFlag++;
			}
		}
		
		if ($cotizacionesProveedor) {
			foreach ($cotizacionesProveedor as $key => $value) {
				$this->cellStyle("B".$rws, "000000", "FFFFFF", TRUE, 12, "Franklin Gothic Book");
				$hoja->setCellValue("B".$rws, $value["familia"]);
				$this->excelfile->getActiveSheet()->getStyle('B'.$rws)->applyFromArray($styleArray);
				$rws++;
				foreach ($value["articulos"] as $key => $val) {
					$this->cellStyle("A".$rws, "FFFFFF", "000000", FALSE, 10, "Franklin Gothic Book");
					$hoja->setCellValue("A".$rws, $val["codigo"]);
					$this->excelfile->getActiveSheet()->getStyle('A'.$rws)->applyFromArray($styleArray);
					$this->cellStyle("B".$rws, "FFFFFF", "000000", FALSE, 10, "Franklin Gothic Book");
					$hoja->setCellValue("B".$rws, $val["producto"]);
					$this->excelfile->getActiveSheet()->getStyle('B'.$rws)->applyFromArray($styleArray);
					$this->cellStyle("C".$rws, "FFFFFF", "000000", FALSE, 10, "Franklin Gothic Book");
					$hoja->setCellValue("C".$rws, $val["precio_sistema"])->getStyle("C{$rws}")->getNumberFormat()->setFormatCode("_(\"$\"* #,##0.00_);_(\"$\"* \(#,##0.00\);_(\"$\"* \"-\"??_);_(@_)");
					$this->excelfile->getActiveSheet()->getStyle('C'.$rws)->applyFromArray($styleArray);
					$this->cellStyle("D".$rws, "FFFFFF", "000000", FALSE, 10, "Franklin Gothic Book");
					$hoja->setCellValue("D".$rws, $val["precio_four"])->getStyle("D{$rws}")->getNumberFormat()->setFormatCode("_(\"$\"* #,##0.00_);_(\"$\"* \(#,##0.00\);_(\"$\"* \"-\"??_);_(@_)");
					$this->excelfile->getActiveSheet()->getStyle('D'.$rws)->applyFromArray($styleArray);
					$this->cellStyle("E".$rws, "FFFFFF", "000000", FALSE, 10, "Franklin Gothic Book");
					$hoja->setCellValue("E".$rws, $val["reales"])->getStyle("E{$rws}")->getNumberFormat()->setFormatCode("_(\"$\"* #,##0.00_);_(\"$\"* \(#,##0.00\);_(\"$\"* \"-\"??_);_(@_)");
					$this->excelfile->getActiveSheet()->getStyle('E'.$rws)->applyFromArray($styleArray);

					$this->cellStyle("F".$rws, "FFFFFF", "000000", FALSE, 10, "Franklin Gothic Book");
					$hoja->setCellValue("F".$rws, $val["precio"])->getStyle("F{$rws}")->getNumberFormat()->setFormatCode("_(\"$\"* #,##0.00_);_(\"$\"* \(#,##0.00\);_(\"$\"* \"-\"??_);_(@_)");
					$this->excelfile->getActiveSheet()->getStyle('F'.$rws)->applyFromArray($styleArray);
					$this->cellStyle("G".$rws, "FFFFFF", "000000", FALSE, 10, "Franklin Gothic Book");
					$hoja->setCellValue("G".$rws, $val["provefirst"]);
					$this->excelfile->getActiveSheet()->getStyle('G'.$rws)->applyFromArray($styleArray);

					if (isset($columnas[ $val["primero"] ])) {
						$this->cellStyle($this->getColumna($columnas[$val["primero"]]["columna"]).''.$rws, "FFFFFF", "000000", FALSE, 10, "Franklin Gothic Book");
						$hoja->setCellValue($this->getColumna($columnas[$val["primero"]]["columna"]).''.$rws, $val["precio"])->getStyle($this->getColumna($columnas[$val["primero"]]["columna"]).''.$rws)->getNumberFormat()->setFormatCode("_(\"$\"* #,##0.00_);_(\"$\"* \(#,##0.00\);_(\"$\"* \"-\"??_);_(@_)");
						$this->excelfile->getActiveSheet()->getStyle($this->getColumna($columnas[$val["primero"]]["columna"]).''.$rws)->applyFromArray($styleArray);
					}else{
						$this->cellStyle($this->getColumna($columnas["none"]["columna"]).''.$rws, "FFFFFF", "000000", FALSE, 10, "Franklin Gothic Book");
						$hoja->setCellValue($this->getColumna($columnas["none"]["columna"]).''.$rws, $val["precio"])->getStyle($this->getColumna($columnas["none"]["columna"]).''.$rws)->getNumberFormat()->setFormatCode("_(\"$\"* #,##0.00_);_(\"$\"* \(#,##0.00\);_(\"$\"* \"-\"??_);_(@_)");
						$this->excelfile->getActiveSheet()->getStyle($this->getColumna($columnas["none"]["columna"]).''.$rws)->applyFromArray($styleArray);
					}
					if (isset($val["otros"])) {
						foreach ($val["otros"] as $key => $v) {
							if (isset($columnas[ $v["proveedor"] ])) {
								$this->cellStyle($this->getColumna($columnas[$v["proveedor"]]["columna"]).''.$rws, "FFFFFF", "000000", FALSE, 10, "Franklin Gothic Book");
								$hoja->setCellValue($this->getColumna($columnas[$v["proveedor"]]["columna"]).''.$rws, $v["precio"])->getStyle($this->getColumna($columnas[$v["proveedor"]]["columna"]).''.$rws)->getNumberFormat()->setFormatCode("_(\"$\"* #,##0.00_);_(\"$\"* \(#,##0.00\);_(\"$\"* \"-\"??_);_(@_)");
								$this->excelfile->getActiveSheet()->getStyle($this->getColumna($columnas[$v["proveedor"]]["columna"]).''.$rws)->applyFromArray($styleArray);
							}
						}
					}
					
					$condRed = new PHPExcel_Style_Conditional();
					$condRed->setConditionType(PHPExcel_Style_Conditional::CONDITION_CELLIS)
			                ->setOperatorType(PHPExcel_Style_Conditional::OPERATOR_EQUAL)
			                ->addCondition('=MIN(H'.$rws.':'.$this->getColumna($colFlag).''.$rws.')')
			                ->getStyle()
			                ->applyFromArray(
			                	array(
								  'font'=>array(
								   'color'=>array('argb'=>'FF9C0006')
								  ),
								  'fill'=>array(
									  'type' =>PHPExcel_Style_Fill::FILL_SOLID,
									  'startcolor' =>array('argb' => 'FFFFC7CE'),
									  'endcolor' =>array('argb' => 'FFFFC7CE')
									)
								)
							);
			        $bandera = 7;
					
					$this->excelfile->getActiveSheet()->getStyle('H'.$rws.':'.$this->getColumna($colFlag).''.$rws)->applyFromArray($styleArray);
					$this->excelfile->getActiveSheet()->freezePane('H2');

					$rws++;
				}

			}
		}




		$this->excelfile->getActiveSheet()->getStyle('A1:BZ1')->getAlignment()->setWrapText(true);
        $dias = array("DOMINGO","LUNES","MARTES","MIÉRCOLES","JUEVES","VIERNES","SÁBADO");
		$meses = array("ENERO","FEBRERO","MARZO","ABRIL","MAYO","JUNIO","JULIO","AGOSTO","SEPTIEMBRE","OCTUBRE","NOVIEMBRE","DICIEMBRE");
		$fecha =  $dias[date('w')]." ".date('d')." DE ".$meses[date('n')-1]. " DEL ".date('Y') ;
		$file_name = "COTIZACIÓN ".$fecha.".xlsx"; //Nombre del documento con extención
		header("Content-Type: application/vnd.ms-excel; charset=utf-8");
		header("Content-Disposition: attachment;filename=".$file_name."");
		header("Cache-Control: max-age=0");
		$excel_Writer = PHPExcel_IOFactory::createWriter($this->excelfile, "Excel2007");
		$excel_Writer->save("php://output");
	}
	public function getColumna($num) {
	    $numeric = $num % 26;
	    $letter = chr(65 + $numeric);
	    $num2 = intval($num / 26);
	    if ($num2 > 0) {
	        return $this->getColumna($num2 - 1) . $letter;
	    } else {
	        return $letter;
	    }
	}
	public function getOldVal($sheets,$i,$le){
		$cellB = $sheets->getCell($le.$i)->getValue();
		if(strstr($cellB,'=')==true){
		    $cellB = $sheets->getCell($le.$i)->getOldCalculatedValue();
		}
		return $cellB;
	}
	public function fill_cotize(){
		ini_set("memory_limit", "-1");
		ini_set("max_execution_time", "-1");
		$this->load->library("excelfile");
		//FECHA EN FORMATO COMPLETO PARA LOS TITULOS Y TABLAS
		$dias = array("DOMINGO","LUNES","MARTES","MIÉRCOLES","JUEVES","VIERNES","SÁBADO");
		$meses = array("ENERO","FEBRERO","MARZO","ABRIL","MAYO","JUNIO","JULIO","AGOSTO","SEPTIEMBRE","OCTUBRE","NOVIEMBRE","DICIEMBRE");
		$fecha =  $dias[date('w')]." ".date('d')." DE ".$meses[date('n')-1]. " DEL ".date('Y') ;
		$day = date('w');

		$hoja = $this->excelfile->setActiveSheetIndex(0);
		$this->excelfile->setActiveSheetIndex(0)->setTitle($dias[date('w')]." ".date('d'));
        $this->excelfile->setActiveSheetIndex(0);

		$styleArray = array(
		  'borders' => array(
		    'allborders' => array(
		      'style' => PHPExcel_Style_Border::BORDER_THIN
		    )
		  )
		);
		$styleArray2 = array(
		  'borders' => array(
		    'allborders' => array(
		      'style' => PHPExcel_Style_Border::BORDER_MEDIUM
		    )
		  )
		);
		$hoja = $this->excelfile->getActiveSheet();
		$this->excelfile->setActiveSheetIndex(0)->setTitle("COTIZACIÓN");

		$rws = 1;

		$hoja->setCellValue("A2","CÓDIGO")->getColumnDimension('A')->setWidth(20);
		$hoja->setCellValue("B1","DESCRIPCIÓN")->getColumnDimension('B')->setWidth(63);
		$hoja->setCellValue("C2","SISTEMA")->getColumnDimension('C')->setWidth(16);
		$hoja->setCellValue("D2","PRECIO 4")->getColumnDimension('D')->setWidth(16);
		$hoja->setCellValue("E1","PRIMER")->getColumnDimension('E')->setWidth(16);
		$hoja->setCellValue("E2","PRECIO");
		$hoja->setCellValue("F1","PROVEEDOR")->getColumnDimension('F')->setWidth(25);
		$hoja->setCellValue("G1","OBSERVACIONES")->getColumnDimension('G')->setWidth(40);
		$hoja->setCellValue("H1","PRECIO")->getColumnDimension('H')->setWidth(16);
		$hoja->setCellValue("H2","MÁXIMO");
		$hoja->setCellValue("I1","PRECIO")->getColumnDimension('I')->setWidth(16);
		$hoja->setCellValue("I2","PROMEDIO");
		$hoja->setCellValue("J1","SEGUNDO")->getColumnDimension('J')->setWidth(16);
		$hoja->setCellValue("J2","PRECIO");
		$hoja->setCellValue("K1","SEGUNDO")->getColumnDimension('K')->setWidth(20);
		$hoja->setCellValue("K2","PROVEEDOR");
		$hoja->setCellValue("L1","OBSERVACIÓN")->getColumnDimension('L')->setWidth(40);

		$hoja->setCellValue("Q2","")->getColumnDimension('Q')->setWidth(30);
		$hoja->setCellValue("R2","")->getColumnDimension('R')->setWidth(30);
		$hoja->setCellValue("S2","")->getColumnDimension('S')->setWidth(30);
		$hoja->setCellValue("T2","")->getColumnDimension('T')->setWidth(30);
		$hoja->setCellValue("P2","")->getColumnDimension('P')->setWidth(30);
		$hoja->setCellValue("U2","")->getColumnDimension('U')->setWidth(30);
		$hoja->setCellValue("V2","")->getColumnDimension('V')->setWidth(30);
		$hoja->setCellValue("W2","")->getColumnDimension('W')->setWidth(30);
		$hoja->setCellValue("X2","")->getColumnDimension('X')->setWidth(30);
		$hoja->setCellValue("Y2","")->getColumnDimension('Y')->setWidth(30);
		$hoja->setCellValue("Z2","")->getColumnDimension('Z')->setWidth(30);
		$hoja->setCellValue("AA2","")->getColumnDimension('AA')->setWidth(30);
		$hoja->setCellValue("AB2","")->getColumnDimension('AB')->setWidth(30);
		$hoja->setCellValue("AC2","")->getColumnDimension('AC')->setWidth(30);
		$hoja->setCellValue("AD2","")->getColumnDimension('AD')->setWidth(30);
		$hoja->setCellValue("AE2","")->getColumnDimension('AE')->setWidth(30);
		
		
		

		$this->cellStyle("A1:DA2", "000000", "FFFFFF", TRUE, 12, "Franklin Gothic Bold");
		$headers = 1;
		$cotizacionesProveedor = $this->prod_mdl->sinpestanias(NULL, $fecha);
		$cotzi = $this->prod_mdl->byProveedor(NULL);
		$row_print =2;

		foreach($cotzi["proveedores"] as $key => $vv){
			$hoja->mergeCells($this->getColumna($vv["columna"])."1:".$this->getColumna($vv["columna"]+1)."1");
			$hoja->mergeCells($this->getColumna($vv["columna"])."2:".$this->getColumna($vv["columna"]+1)."2");
			$hoja->setCellValue($this->getColumna($vv["columna"])."1","PRECIO/OBSERVACIÓN")->getColumnDimension($this->getColumna($vv["columna"]))->setWidth(16);
			$hoja->setCellValue($this->getColumna($vv["columna"])."2", $vv["nombre"])->getColumnDimension($this->getColumna($vv["columna"] + 1))->setWidth(22);
		}
		if ($cotizacionesProveedor){
			foreach ($cotizacionesProveedor as $key => $value){
				$hoja->setCellValue('B'.$row_print, $value['familia']);
				$this->cellStyle("B{$row_print}", "000000", "FFFFFF", TRUE, 12, "Franklin Gothic Bold");				
				$row_print +=1;
				if ($value['articulos']) {
					foreach ($value['articulos'] as $key => $row){
						$condRed = new PHPExcel_Style_Conditional();
						$condGreen = new PHPExcel_Style_Conditional();
						$hoja->setCellValue("A{$row_print}",$row['codigo']);
						$hoja->setCellValue("B{$row_print}",$row['producto']);
						$hoja->setCellValue("C{$row_print}",$row['precio_sistema'])->getStyle("C{$row_print}")->getNumberFormat()->setFormatCode("_(\"$\"* #,##0.00_);_(\"$\"* \(#,##0.00\);_(\"$\"* \"-\"??_);_(@_)");
						$hoja->setCellValue("D{$row_print}",$row['precio_four'])->getStyle("D{$row_print}")->getNumberFormat()->setFormatCode("_(\"$\"* #,##0.00_);_(\"$\"* \(#,##0.00\);_(\"$\"* \"-\"??_);_(@_)");
						$hoja->setCellValue("H{$row_print}",$row['precio_maximo'])->getStyle("H{$row_print}")->getNumberFormat()->setFormatCode("_(\"$\"* #,##0.00_);_(\"$\"* \(#,##0.00\);_(\"$\"* \"-\"??_);_(@_)");
						$hoja->setCellValue("I{$row_print}",$row['precio_promedio'])->getStyle("I{$row_print}")->getNumberFormat()->setFormatCode("_(\"$\"* #,##0.00_);_(\"$\"* \(#,##0.00\);_(\"$\"* \"-\"??_);_(@_)");
						
						$condRed->setConditionType(PHPExcel_Style_Conditional::CONDITION_CELLIS)
				                ->setOperatorType(PHPExcel_Style_Conditional::OPERATOR_GREATERTHAN)
				                ->addCondition("C".$row_print)
				                ->getStyle()
				                ->applyFromArray(
				                	array(
									  'font'=>array(
									   'color'=>array('argb'=>'FF9C0006')
									  ),
									  'fill'=>array(
										  'type' =>PHPExcel_Style_Fill::FILL_SOLID,
										  'startcolor' =>array('argb' => 'FFFFC7CE'),
										  'endcolor' =>array('argb' => 'FFFFC7CE')
										)
									)
								);
						$condGreen->setConditionType(PHPExcel_Style_Conditional::CONDITION_CELLIS)
				                ->setOperatorType(PHPExcel_Style_Conditional::OPERATOR_LESSTHAN)
				                ->addCondition("C".$row_print)
				                ->getStyle()
				                ->applyFromArray(
				                	array(
									  'font'=>array(
									   'color'=>array('argb'=>'FF006100')
									  ),
									  'fill'=>array(
										  'type' =>PHPExcel_Style_Fill::FILL_SOLID,
										  'startcolor' =>array('argb' => 'FFC6EFCE'),
										  'endcolor' =>array('argb' => 'FFC6EFCE')
										)
									)
								);

				        $conditionalStyles = $this->excelfile->getActiveSheet()->getStyle('E'.$row_print)->getConditionalStyles();
						array_push($conditionalStyles,$condRed);
						array_push($conditionalStyles,$condGreen);
						$this->excelfile->getActiveSheet()->getStyle('E'.$row_print)->setConditionalStyles($conditionalStyles);

						$flag = 1;
						$celda = 9;
						if($row["cotizaciones"]){
							foreach ($row["cotizaciones"] as $key => $vcotz) {
								if($flag === 1){
									$hoja->setCellValue("E{$row_print}",$vcotz['precio'])->getStyle("E{$row_print}")->getNumberFormat()->setFormatCode("_(\"$\"* #,##0.00_);_(\"$\"* \(#,##0.00\);_(\"$\"* \"-\"??_);_(@_)");
									$hoja->setCellValue("F{$row_print}",$vcotz['proveedor']);
									$hoja->setCellValue("G{$row_print}",$vcotz['promocion']);
									$flag++;
								}elseif($flag === 2){
									$hoja->setCellValue($this->getColumna($celda)."{$row_print}",$vcotz['precio'])->getStyle($this->getColumna($celda)."{$row_print}")->getNumberFormat()->setFormatCode("_(\"$\"* #,##0.00_);_(\"$\"* \(#,##0.00\);_(\"$\"* \"-\"??_);_(@_)");
									$conditionalStyles = $this->excelfile->getActiveSheet()->getStyle($this->getColumna($celda)."{$row_print}")->getConditionalStyles();
									array_push($conditionalStyles,$condRed);
									array_push($conditionalStyles,$condGreen);
									$this->excelfile->getActiveSheet()->getStyle($this->getColumna($celda)."{$row_print}")->setConditionalStyles($conditionalStyles);
									$celda++;
									$hoja->setCellValue($this->getColumna($celda)."{$row_print}",$vcotz['proveedor']);
									$celda++;
									$hoja->setCellValue($this->getColumna($celda)."{$row_print}",$vcotz['promocion']);
									$celda++;
									$flag++;
								}
							}
						}

						if(isset($cotzi[$row['id_producto']]["cotiza"])){
							foreach ($cotzi[$row["id_producto"]]["cotiza"] as $key => $vpr) {
								$hoja->setCellValue($this->getColumna($vpr["columna"]-2)."{$row_print}",$vpr['precio'])->getStyle($this->getColumna($vpr["columna"]-2)."{$row_print}")->getNumberFormat()->setFormatCode("_(\"$\"* #,##0.00_);_(\"$\"* \(#,##0.00\);_(\"$\"* \"-\"??_);_(@_)");
								$conditionalStyles = $this->excelfile->getActiveSheet()->getStyle($this->getColumna($vpr["columna"]-2)."{$row_print}")->getConditionalStyles();
								array_push($conditionalStyles,$condRed);
								array_push($conditionalStyles,$condGreen);
								$this->excelfile->getActiveSheet()->getStyle($this->getColumna($vpr["columna"]-2)."{$row_print}")->setConditionalStyles($conditionalStyles);
								$hoja->setCellValue($this->getColumna($vpr["columna"]-1)."{$row_print}",$vpr['observaciones']);
							}
						}
						$this->cellStyle("A{$row_print}", "FFFFFF", "000000", TRUE, 12, "Franklin Gothic Bold");
						$this->cellStyle("B{$row_print}:M{$row_print}", "FFFFFF", "000000", FALSE, 12, "Franklin Gothic Bold");
						$this->cellStyle("M{$row_print}:DA{$row_print}", "FFFFFF", "000000", FALSE, 10, "Franklin Gothic Bold");
						$this->excelfile->getActiveSheet()->getStyle("A{$row_print}:DA{$row_print}")->applyFromArray($styleArray);
						if($row['estatus'] == 2){
							$this->cellStyle("B{$row_print}", "00B0F0", "000000", FALSE, 12, "Franklin Gothic Book");
						}
						if($row['estatus'] == 3){
							$this->cellStyle("B{$row_print}", "FFF900", "000000", FALSE, 12, "Franklin Gothic Book");
						}
						if($row['estatus'] >= 4){
							$this->cellStyle("B{$row_print}", "04B486", "000000", FALSE, 12, "Franklin Gothic Book");
						}

						
						$row_print +=1;
					}
				}
			}
		}

		$user = $this->session->userdata();
		$cambios = [
			"id_usuario" => $user["id_usuario"],
			"fecha_cambio" => date('Y-m-d H:i:s'),
			"antes" => "Descarga formato",
			"despues" => "19 hermanos ",
			"estatus" => "3",
		];
		$data['cambios'] = $this->cambio_md->insert($cambios);



		$dias = array("DOMINGO","LUNES","MARTES","MIÉRCOLES","JUEVES","VIERNES","SÁBADO");
		$meses = array("ENERO","FEBRERO","MARZO","ABRIL","MAYO","JUNIO","JULIO","AGOSTO","SEPTIEMBRE","OCTUBRE","NOVIEMBRE","DICIEMBRE");
		$fecha =  $dias[date('w')]." ".date('d')." DE ".$meses[date('n')-1]. " DEL ".date('Y') ;
		$file_name = "COTIZACIÓN ".$fecha.".xlsx"; //Nombre del documento con extención
		header("Content-Type: application/vnd.ms-excel; charset=utf-8");
		header("Content-Disposition: attachment;filename=".$file_name."");
		header("Cache-Control: max-age=0");
		$excel_Writer = PHPExcel_IOFactory::createWriter($this->excelfile, "Excel2007");
		$excel_Writer->save("php://output");
	}
	public function resizeImage($filename,$filename2){
		$source_path = $_SERVER['DOCUMENT_ROOT'] . '/Aztecas/Abarrotes/assets/img/productos/' . $filename;
	    $target_path = $_SERVER['DOCUMENT_ROOT'] . '/Aztecas/Abarrotes/aswwsets/img/ppp/'.$filename2;
	    list($width, $height, $type, $attr) = getimagesize($source_path);
	    if ($width > $height) {
	    	$w = $width * .50;
	      	$config_manip = array(
		          'image_library' => 'gd2',
		          'source_image' => $source_path,
		          'new_image' => $target_path,
		          'create_thumb' => TRUE,
		          'maintain_ratio' => TRUE,
		          'width' => intval($w),
		          'wm_name'	=>	$filename
		      );
	      }else{
	      	$w = $height * .50;
	      	$config_manip = array(
		          'image_library' => 'gd2',
		          'source_image' => $source_path,
		          'new_image' => $target_path,
		          'create_thumb' => TRUE,
		          'maintain_ratio' => TRUE,
		          'height' => intval($w),
		          'wm_name'	=>	$filename
		      );
	      }
	      
	   
	      $this->image_lib->initialize($config_manip);
	      if (!$this->image_lib->resize()) {
	          echo $this->image_lib->display_errors();
	      }
	      var_dump($this->image_lib);
	      echo $source_path;
    }
    public function didi(){
   		$this->load->library('image_lib');
   		for ($i=1; $i < 1394; $i++) { 
			$flags = $i;
			$longs = $this->couns($flags);
			if ($longs === 1){
				$filename = "image00".$flags.".png";
				$filename2 = "image00".$flags.".png";
			}elseif($longs === 2){
				$filename = "image0".$flags.".png";
				$filename2 = "image0".$flags.".png";
			}else{
				$filename = "image".$flags.".png";
				$filename2 = "image".$flags.".png";
			}
			$this->resizeImage($filename,$filename2);
			$this->image_lib->clear();

		}
    }
    public function couns($number){
   		return strlen((string)$number);
   	}
   	public function subimg(){
   		ini_set("memory_limit", -1);
   		$this->load->library("excelfile");
		$file = $_FILES["file_productos"]["tmp_name"];
		$filename=$_FILES['file_productos']['name'];
		$sheet = PHPExcel_IOFactory::load($file);
		$objExcel = PHPExcel_IOFactory::load($file);
		$sheet = $objExcel->getSheet(0);
		$num_rows = $sheet->getHighestDataRow();

		for ($i=1; $i<=$num_rows; $i++) {
			if($this->getOldVal($sheet,$i,'A') > 0){
				$productos = $this->prod_mdl->get("id_producto",['codigo'=> htmlspecialchars($this->getOldVal($sheet,$i,'A'), ENT_QUOTES, 'UTF-8')])[0];
				if(sizeof($productos) > 0) {
					$longs = strlen((string)$i);
					if ($longs === 1){
						$ima = "image00".$i.".png";
					}elseif($longs === 2){
						$ima = "image0".$i.".png";
					}else{
						$ima = "image".$i.".png";
					}					
					$new_cotizacion=[
						"id_producto"	=>	$productos->id_producto,
						"imagen"		=>	$ima,
					];
					$data['cotizacion']=$this->img_md->insert($new_cotizacion);
				}
			}
		}
   	}

   	private function fill_varios($id_proves,$proves,$prs){
		$flag =1;
		$flag2=1;
		$array = "";
		$array2 = "";
		$filenam = "";
		$flag1 = 5;
		$filenam = $id_proves;
		$array = $this->usua_mdl->get(NULL, ["conjunto" => $id_proves,"estatus"=>1]);
		ini_set("memory_limit", "-1");
		ini_set("max_execution_time", "-1");
		$this->load->library("excelfile");
		
		$hoja1 = $this->excelfile->setActiveSheetIndex(0);
		$this->excelfile->setActiveSheetIndex(0)->setTitle("EXISTENCIAS");
		$this->excelfile->createSheet();
        $hoja = $this->excelfile->setActiveSheetIndex(1);
        $hoja->setTitle("PEDIDO");
		$styleArray = array(
		  'borders' => array(
		    'allborders' => array(
		      'style' => PHPExcel_Style_Border::BORDER_THIN
		    )
		  )
		);
		$hoja->getColumnDimension('A')->setWidth("20");
		$hoja->getColumnDimension('B')->setWidth("20");
		$hoja->getColumnDimension('C')->setWidth("70");
		$hoja->getColumnDimension('D')->setWidth("7");
		$hoja->getColumnDimension('E')->setWidth("15");
		$hoja->getColumnDimension('F')->setWidth("15");
		$hoja->getColumnDimension('G')->setWidth("8");
		$hoja->getColumnDimension('H')->setWidth("15");
		$hoja->getColumnDimension('I')->setWidth("8");
		$hoja->getColumnDimension('L')->setWidth("20");
		$hoja->getColumnDimension('J')->setWidth("20");
		$hoja->getColumnDimension('K')->setWidth("15");
		$hoja->getColumnDimension('BM')->setWidth("70");
		
		$hoja1->getColumnDimension('A')->setWidth("6");
		$hoja1->getColumnDimension('B')->setWidth("6");
		$hoja1->getColumnDimension('C')->setWidth("6");
		$hoja1->getColumnDimension('D')->setWidth("25");
		$hoja1->getColumnDimension('E')->setWidth("47");
		$hoja1->getColumnDimension('G')->setWidth("50");
		$hoja1->getColumnDimension('F')->setWidth("15");

		$flagBorder = 0;
		$flagBorder1 = 1;
		$flagBorder2 = 0;
		$flagBorder3 = 1;
		$flage = 5;
		$i = 0;
		$sumall = array(1 => "", 2 => "", 3 => "", 4 => "", 5 => "", 6 => "", 7 => "", 8 => "", 9 => "", 10 => "");
		$provname = "";

		foreach ($array as $key => $v3) {
			$this->excelfile->setActiveSheetIndex(0);
			if ($flag > 15) {
				$flag2 = $flag+2;	
			}else{
				$flag2 = $flag;
			}
			$fecha = new DateTime(date('Y-m-d H:i:s'));
			$where=["ctz_first.id_proveedor" => $v3->id_usuario,"prod.estatus" => 1];//Semana actual
			$intervalo = new DateInterval('P2D');
			$fecha->add($intervalo);
			$where=["ctz_first.id_proveedor" => $v3->id_usuario,"prod.estatus" => 1];//Semana actual
			$cotizacionesProveedor = $this->ct_mdl->getPedidosAll($where, $fecha->format('Y-m-d H:i:s'), 0);
			
			if ($cotizacionesProveedor) {
				$hoja1->mergeCells('A'.$flag2.':G'.$flag2);
				$this->cellStyle("A".$flag2."", "FFFFFF", "000000", TRUE, 12, "Franklin Gothic Book");
				$hoja1->setCellValue("A".$flag2."", "GRUPO ABARROTES AZTECA");
				$this->excelfile->getActiveSheet()->getStyle('A'.$flag2.':G'.$flag2.'')->applyFromArray($styleArray);
				$flag2++;
				$hoja1->mergeCells('A'.$flag2.':G'.$flag2.'');
				$this->cellStyle("A".$flag2."", "FFFFFF", "000000", TRUE, 12, "Franklin Gothic Book");
				$hoja1->setCellValue("A".$flag2."", "PEDIDOS A '".$v3->nombre."' ".date("d-m-Y"));
				$this->excelfile->getActiveSheet()->getStyle('A'.$flag2.':G'.$flag2.'')->applyFromArray($styleArray);
				$flag2++;
				$this->cellStyle("A".$flag2.":D".$flag2."", "000000", "FFFFFF", TRUE, 12, "Franklin Gothic Book");
				$hoja1->mergeCells('A'.$flag2.':B'.$flag2.'');
				$hoja1->setCellValue("A".$flag2."", "EXISTENCIAS");
				$hoja1->setCellValue("E".$flag2."", "DESCRIPCIÓN");
				$this->cellStyle("E".$flag2."", "000000", "FFFFFF", TRUE, 12, "Franklin Gothic Book");
				$this->excelfile->getActiveSheet()->getStyle('A'.$flag2.':G'.$flag2.'')->applyFromArray($styleArray);

				$this->cellStyle("H".$flag2."", "000000", "FFFFFF", TRUE, 12, "Franklin Gothic Book");
				$hoja1->setCellValue("H".$flag2."", "PENDIENT");
				$this->cellStyle("I".$flag2."", "000000", "FFFFFF", TRUE, 12, "Franklin Gothic Book");
				$hoja1->setCellValue("I".$flag2."", "PENDIENT");
				$this->cellStyle("J".$flag2."", "000000", "FFFFFF", TRUE, 12, "Franklin Gothic Book");
				$hoja1->setCellValue("J".$flag2."", "PENDIENT");
				$this->cellStyle("K".$flag2."", "000000", "FFFFFF", TRUE, 12, "Franklin Gothic Book");
				$hoja1->setCellValue("K".$flag2."", "PENDIENT");
				$this->cellStyle("L".$flag2."", "000000", "FFFFFF", TRUE, 12, "Franklin Gothic Book");
				$hoja1->setCellValue("L".$flag2."", "PENDIENT");
				$this->cellStyle("M".$flag2."", "000000", "FFFFFF", TRUE, 12, "Franklin Gothic Book");
				$hoja1->setCellValue("M".$flag2."", "PENDIENT");
				$this->cellStyle("N".$flag2."", "000000", "FFFFFF", TRUE, 12, "Franklin Gothic Book");
				$hoja1->setCellValue("N".$flag2."", "PENDIENT");
				$this->cellStyle("O".$flag2."", "000000", "FFFFFF", TRUE, 12, "Franklin Gothic Book");
				$hoja1->setCellValue("O".$flag2."", "PENDIENT");
				$this->cellStyle("P".$flag2."", "000000", "FFFFFF", TRUE, 12, "Franklin Gothic Book");
				$hoja1->setCellValue("P".$flag2."", "PENDIENT");
				

				$flag2++;
				$this->cellStyle("A".$flag2.":G".$flag2."", "000000", "FFFFFF", TRUE, 12, "Franklin Gothic Book");
				$hoja1->setCellValue("A".$flag2."", "CAJAS");
				$hoja1->setCellValue("B".$flag2."", "PZAS");
				$hoja1->setCellValue("C".$flag2."", "PEDIDO");
				$hoja1->setCellValue("D".$flag2."", "CÓDIGO");
				$hoja1->setCellValue("G".$flag2."", "PROMOCIÓN");

				$this->cellStyle("H".$flag2, "C00000", "000000", TRUE, 10, "Franklin Gothic Book");
				$this->cellStyle("I".$flag2, "01B0F0", "000000", TRUE, 10, "Franklin Gothic Book");
				$this->cellStyle("J".$flag2, "FF0000", "000000", TRUE, 10, "Franklin Gothic Book");
				$this->cellStyle("K".$flag2, "E26C0B", "000000", TRUE, 10, "Franklin Gothic Book");
				$this->cellStyle("L".$flag2, "C5C5C5", "000000", TRUE, 10, "Franklin Gothic Book");
				$this->cellStyle("M".$flag2, "92D051", "000000", TRUE, 10, "Franklin Gothic Book");
				$this->cellStyle("N".$flag2, "B1A0C7", "000000", TRUE, 10, "Franklin Gothic Book");
				$this->cellStyle("O".$flag2, "DA9694", "000000", TRUE, 10, "Franklin Gothic Book");
				$this->cellStyle("P".$flag2, "4CACC6", "000000", TRUE, 10, "Franklin Gothic Book");
				$hoja1->setCellValue("H".$flag2."", "CEDIS");
				$hoja1->setCellValue("I".$flag2."", "ABARROTES");
				$hoja1->setCellValue("J".$flag2."", "VILLAS");
				$hoja1->setCellValue("K".$flag2."", "TIENDA");
				$hoja1->setCellValue("L".$flag2."", "ULTRA");
				$hoja1->setCellValue("M".$flag2."", "TRINCHERAS");
				$hoja1->setCellValue("N".$flag2."", "MERCADO");
				$hoja1->setCellValue("O".$flag2."", "TENENCIA");
				$hoja1->setCellValue("P".$flag2."", "TIJERAS");

				$this->excelfile->setActiveSheetIndex(1);
				
				$this->cellStyle("A".$flag, "FFFFFF", "000000", TRUE, 12, "Franklin Gothic Book");
				$hoja->setCellValue("A".$flag, "CEDIS,CD INDUSTRIAL, ABARROTES, TIENDA, ULTRAMARINOS, TRINCHERAS, MERCADO, TIJERAS, Y TENENCIA AZTECA AUTOSERVICIOS SA. DE CV.");
				$hoja->mergeCells('A'.$flag.':BM'.$flag);
				$this->excelfile->getActiveSheet()->getStyle('A'.$flag.':BM'.$flag)->applyFromArray($styleArray);
				$flag++;
				$hoja->mergeCells('A'.$flag.':L'.$flag);
				$hoja->mergeCells('M'.$flag.':Q'.$flag);
				$hoja->mergeCells('R'.$flag.':U'.$flag);
				$hoja->mergeCells('V'.$flag.':X'.$flag);
				$hoja->mergeCells('Y'.$flag.':AC'.$flag);
				$hoja->mergeCells('AD'.$flag.':AH'.$flag);
				$hoja->mergeCells('AI'.$flag.':AM'.$flag);
				$hoja->mergeCells('AN'.$flag.':AR'.$flag);
				$hoja->mergeCells('AS'.$flag.':AW'.$flag);
				$hoja->mergeCells('AX'.$flag.':BB'.$flag);
				$hoja->mergeCells('BC'.$flag.':BG'.$flag);
				$hoja->mergeCells('BH'.$flag.':BL'.$flag);
				$this->cellStyle("A".$flag, "FFFFFF", "000000", TRUE, 12, "Franklin Gothic Book");
				$hoja->setCellValue("A".$flag, "PEDIDOS A '".$v3->nombre."' ".date("d-m-Y"));
				$this->cellStyle("M".$flag, "C00000", "000000", TRUE, 12, "Franklin Gothic Book");
				$hoja->setCellValue("M".$flag, "CEDIS");
				$this->cellStyle("R".$flag, "FF0066", "000000", TRUE, 12, "Franklin Gothic Book");
				$hoja->setCellValue("R".$flag, "SUPER INDUSTRIAL");
				$this->cellStyle("V".$flag, "C2B90A", "000000", TRUE, 12, "Franklin Gothic Book");
				$hoja->setCellValue("V".$flag, "SUMA CEDIS");
				$this->cellStyle("Y".$flag, "01B0F0", "000000", TRUE, 12, "Franklin Gothic Book");
				$hoja->setCellValue("Y".$flag, "ABARROTES");
				$this->cellStyle("AD".$flag, "FF0000", "000000", TRUE, 12, "Franklin Gothic Book");
				$hoja->setCellValue("AD".$flag, "VILLAS");
				$this->cellStyle("AI".$flag, "E26C0B", "000000", TRUE, 12, "Franklin Gothic Book");
				$hoja->setCellValue("AI".$flag, "TIENDA");
				$this->cellStyle("AN".$flag, "C5C5C5", "000000", TRUE, 12, "Franklin Gothic Book");
				$hoja->setCellValue("AN".$flag, "ULTRAMARINOS");
				$this->cellStyle("AS".$flag, "92D051", "000000", TRUE, 12, "Franklin Gothic Book");
				$hoja->setCellValue("AS".$flag, "TRINCHERAS");
				$this->cellStyle("AX".$flag, "B1A0C7", "000000", TRUE, 12, "Franklin Gothic Book");
				$hoja->setCellValue("AX".$flag, "AZT MERCADO");
				$this->cellStyle("BC".$flag, "DA9694", "000000", TRUE, 12, "Franklin Gothic Book");
				$hoja->setCellValue("BC".$flag, "TENENCIA");
				$this->cellStyle("BH".$flag, "4CACC6", "000000", TRUE, 12, "Franklin Gothic Book");
				$hoja->setCellValue("BH".$flag, "TIJERAS");
				
				$this->cellStyle("A3:BM4", "000000", "FFFFFF", TRUE, 12, "Franklin Gothic Book");
				$this->excelfile->getActiveSheet()->getStyle('A'.$flag.':BM'.$flag)->applyFromArray($styleArray);
				$flag++;
				$hoja->mergeCells('A'.$flag.':L'.$flag);
				$hoja->mergeCells('M'.$flag.':Q'.$flag);
				$hoja->mergeCells('R'.$flag.':U'.$flag);
				$hoja->mergeCells('V'.$flag.':X'.$flag);
				$hoja->mergeCells('Y'.$flag.':AC'.$flag);
				$hoja->mergeCells('AD'.$flag.':AH'.$flag);
				$hoja->mergeCells('AI'.$flag.':AM'.$flag);
				$hoja->mergeCells('AN'.$flag.':AR'.$flag);
				$hoja->mergeCells('AS'.$flag.':AW'.$flag);
				$hoja->mergeCells('AX'.$flag.':BB'.$flag);
				$hoja->mergeCells('BC'.$flag.':BG'.$flag);
				$hoja->mergeCells('BH'.$flag.':BL'.$flag);
				$this->cellStyle("A".$flag.":BM".$flag."", "000000", "FFFFFF", TRUE, 12, "Franklin Gothic Book");
				$hoja->setCellValue("A".$flag, "DESCRIPCIÓN");
				$hoja->setCellValue("M".$flag, "EXISTENCIAS");
				$hoja->setCellValue("R".$flag, "EXISTENCIAS");
				$hoja->setCellValue("V".$flag, "EXISTENCIAS");
				$hoja->setCellValue("Y".$flag, "EXISTENCIAS");
				$hoja->setCellValue("AD".$flag, "EXISTENCIAS");
				$hoja->setCellValue("AI".$flag, "EXISTENCIAS");
				$hoja->setCellValue("AN".$flag, "EXISTENCIAS");
				$hoja->setCellValue("AS".$flag, "EXISTENCIAS");
				$hoja->setCellValue("AX".$flag, "EXISTENCIAS");
				$hoja->setCellValue("BC".$flag, "EXISTENCIAS");
				$hoja->setCellValue("BH".$flag, "EXISTENCIAS");

				//Begin: TOTALES PEDIDOS PENDIENTES
				$hoja->mergeCells('CA'.$flag.':CK'.$flag);
				$this->cellStyle("CA".$flag.":CK".$flag."", "000000", "FFFFFF", TRUE, 12, "Franklin Gothic Book");
				$hoja->setCellValue("CA".$flag, "TOTAL POR PEDIDOS PENDIENTES");
				//End: TOTALES PEDIDOS PENDIENTES

				$flag++;
				$this->cellStyle("A".$flag.":BM".$flag."", "000000", "FFFFFF", TRUE, 12, "Franklin Gothic Book");
				$hoja->setCellValue("A".$flag, "CÓDIGO");
				$hoja->setCellValue("B".$flag, "FACTURA");
				$hoja->setCellValue("D".$flag, "UM");
				$hoja->setCellValue("E".$flag, "REALES");
				$hoja->setCellValue("F".$flag, "COSTO");
				$hoja->setCellValue("H".$flag, "SISTEMA");
				$hoja->setCellValue("J".$flag, "PRECIO4");
				$hoja->setCellValue("K".$flag, "2DO");
				$hoja->setCellValue("L".$flag, "PROVEEDOR");
				$hoja->setCellValue("M".$flag, "CAJAS");
				$hoja->setCellValue("N".$flag, "PZAS");
				$hoja->setCellValue("O".$flag, "PEND");
				$hoja->setCellValue("P".$flag, "STOCKS");
				$hoja->setCellValue("Q".$flag, "PEDIDO");
				$hoja->setCellValue("R".$flag, "CAJAS");
				$hoja->setCellValue("S".$flag, "PZAS");
				$hoja->setCellValue("T".$flag, "STOCKS");
				$hoja->setCellValue("U".$flag, "PEDIDO");
				$hoja->setCellValue("V".$flag, "CAJAS");
				$hoja->setCellValue("W".$flag, "PZAS");
				$hoja->setCellValue("X".$flag, "PEDIDO");
				$hoja->setCellValue("Y".$flag, "CAJAS");
				$hoja->setCellValue("Z".$flag, "PZAS");
				$hoja->setCellValue("AA".$flag, "PEND");
				$hoja->setCellValue("AB".$flag, "STOCKS");
				$hoja->setCellValue("AC".$flag, "PEDIDO");

				$hoja->setCellValue("AD".$flag, "CAJAS");
				$hoja->setCellValue("AE".$flag, "PZAS");
				$hoja->setCellValue("AF".$flag, "PEND");
				$hoja->setCellValue("AG".$flag, "STOCKS");
				$hoja->setCellValue("AH".$flag, "PEDIDO");
				$hoja->setCellValue("AI".$flag, "CAJAS");
				$hoja->setCellValue("AJ".$flag, "PZAS");
				$hoja->setCellValue("AK".$flag, "PEND");
				$hoja->setCellValue("AL".$flag, "STOCKS");
				$hoja->setCellValue("AM".$flag, "PEDIDO");
				$hoja->setCellValue("AD".$flag, "CAJAS");
				$hoja->setCellValue("AE".$flag, "PZAS");
				$hoja->setCellValue("AF".$flag, "PEND");
				$hoja->setCellValue("AG".$flag, "STOCKS");
				$hoja->setCellValue("AH".$flag, "PEDIDO");
				$hoja->setCellValue("AI".$flag, "CAJAS");
				$hoja->setCellValue("AJ".$flag, "PZAS");
				$hoja->setCellValue("AK".$flag, "PEND");
				$hoja->setCellValue("AL".$flag, "STOCKS");
				$hoja->setCellValue("AM".$flag, "PEDIDO");
				$hoja->setCellValue("AN".$flag, "CAJAS");
				$hoja->setCellValue("AO".$flag, "PZAS");
				$hoja->setCellValue("AP".$flag, "PEND");
				$hoja->setCellValue("AQ".$flag, "STOCKS");
				$hoja->setCellValue("AR".$flag, "PEDIDO");
				$hoja->setCellValue("AS".$flag, "CAJAS");
				$hoja->setCellValue("AT".$flag, "PZAS");
				$hoja->setCellValue("AU".$flag, "PEND");
				$hoja->setCellValue("AV".$flag, "STOCKS");
				$hoja->setCellValue("AW".$flag, "PEDIDO");
				$hoja->setCellValue("AX".$flag, "CAJAS");
				$hoja->setCellValue("AY".$flag, "PZAS");
				$hoja->setCellValue("AZ".$flag, "PEND");
				$hoja->setCellValue("BA".$flag, "STOCKS");
				$hoja->setCellValue("BB".$flag, "PEDIDO");
				$hoja->setCellValue("BC".$flag, "CAJAS");
				$hoja->setCellValue("BD".$flag, "PZAS");
				$hoja->setCellValue("BE".$flag, "PEND");
				$hoja->setCellValue("BF".$flag, "STOCKS");
				$hoja->setCellValue("BG".$flag, "PEDIDO");
				$hoja->setCellValue("BH".$flag, "CAJAS");
				$hoja->setCellValue("BI".$flag, "PZAS");
				$hoja->setCellValue("BJ".$flag, "PEND");
				$hoja->setCellValue("BK".$flag, "STOCKS");
				$hoja->setCellValue("BL".$flag, "PEDIDO");
				
				$hoja->setCellValue("BM".$flag, "PROMOCION");
				$hoja->setCellValue("BW".$flag, "TOTAL");
				$hoja->setCellValue("BX".$flag, "PEDIDOS");
				$this->cellStyle("BN".$flag, "C00000", "000000", TRUE, 12, "Franklin Gothic Book");
				$this->cellStyle("BO".$flag, "01B0F0", "000000", TRUE, 12, "Franklin Gothic Book");
				$this->cellStyle("BP".$flag, "FF0000", "000000", TRUE, 12, "Franklin Gothic Book");
				$this->cellStyle("BQ".$flag, "E26C0B", "000000", TRUE, 12, "Franklin Gothic Book");
				$this->cellStyle("BR".$flag, "C5C5C5", "000000", TRUE, 12, "Franklin Gothic Book");
				$this->cellStyle("BS".$flag, "92D051", "000000", TRUE, 12, "Franklin Gothic Book");
				$this->cellStyle("BT".$flag, "B1A0C7", "000000", TRUE, 12, "Franklin Gothic Book");
				$this->cellStyle("BU".$flag, "DA9694", "000000", TRUE, 12, "Franklin Gothic Book");
				$this->cellStyle("BV".$flag, "4CACC6", "000000", TRUE, 12, "Franklin Gothic Book");
				$this->cellStyle("BW".$flag."", "000000", "FFFFFF", TRUE, 12, "Franklin Gothic Book");
				$this->cellStyle("BX".$flag."", "000000", "FFFFFF", TRUE, 12, "Franklin Gothic Book");

				//Begin: TOTALES PEDIDOS PENDIENTES
				$this->cellStyle("CA".$flag, "C00000", "000000", TRUE, 12, "Franklin Gothic Book");
				$this->cellStyle("CB".$flag, "01B0F0", "000000", TRUE, 12, "Franklin Gothic Book");
				$this->cellStyle("CC".$flag, "FF0000", "000000", TRUE, 12, "Franklin Gothic Book");
				$this->cellStyle("CD".$flag, "E26C0B", "000000", TRUE, 12, "Franklin Gothic Book");
				$this->cellStyle("CE".$flag, "C5C5C5", "000000", TRUE, 12, "Franklin Gothic Book");
				$this->cellStyle("CF".$flag, "92D051", "000000", TRUE, 12, "Franklin Gothic Book");
				$this->cellStyle("CG".$flag, "B1A0C7", "000000", TRUE, 12, "Franklin Gothic Book");
				$this->cellStyle("CH".$flag, "DA9694", "000000", TRUE, 12, "Franklin Gothic Book");
				$this->cellStyle("CI".$flag, "4CACC6", "000000", TRUE, 12, "Franklin Gothic Book");
				$this->cellStyle("CJ".$flag."", "000000", "FFFFFF", TRUE, 12, "Franklin Gothic Book");
				$this->cellStyle("CK".$flag."", "000000", "FFFFFF", TRUE, 12, "Franklin Gothic Book");
				$hoja->setCellValue("CJ".$flag, "TOTAL");
				$hoja->setCellValue("CK".$flag, "PEDIDOS");
				//End: TOTALES PEDIDOS PENDIENTES

				$this->excelfile->getActiveSheet()->getStyle('BM'.$flag)->applyFromArray($styleArray);
				
				$bandera = $flag+1;

			

				foreach ($cotizacionesProveedor as $key => $value) {
					$this->excelfile->setActiveSheetIndex(0);
					$this->cellStyle("E".$flag1, "000000", "FFFFFF", FALSE, 12, "Franklin Gothic Book");
					$hoja1->setCellValue("E".$flag1, $value['familia']);
					$flag1 +=1;
					//Pedidos
					$this->excelfile->setActiveSheetIndex(1);
					$this->cellStyle("C".$flag, "000000", "FFFFFF", FALSE, 12, "Franklin Gothic Book");
					$hoja->setCellValue("C{$flag}", $value['familia']);
					$flag +=1;
					if ($value['articulos']) {
						foreach ($value['articulos'] as $key => $row){
							if($row["unidad"] === null || $row["unidad"] === ""){
								$row["unidad"] = 1;
							}
							$registrazo = date('Y-m-d',strtotime($row['registrazo']));
							$this->excelfile->setActiveSheetIndex(0);
							$this->cellStyle("A".$flag1.":P".$flag1, "FFFFFF", "000000", FALSE, 12, "Franklin Gothic Book");
							
							$hoja1->setCellValue("D{$flag1}", $row['codigo'])->getStyle("D{$flag1}")->getNumberFormat()->setFormatCode('# ???/???');//Formato de fraccion
							if($row['color'] == '#92CEE3'){
								$this->cellStyle("D{$flag1}", "92CEE3", "000000", FALSE, 12, "Franklin Gothic Book");
							}else{
								$this->cellStyle("D{$flag1}", "FFFFFF", "000000", FALSE, 12, "Franklin Gothic Book");
							}
							$hoja1->setCellValue("E{$flag1}", $row['producto']);
							$hoja1->setCellValue("G{$flag1}", $row['promocion_first']);
							$hoja1->setCellValue("H{$flag1}", $row['cedis']);
							$hoja1->setCellValue("I{$flag1}", $row['abarrotes']);
							$hoja1->setCellValue("J{$flag1}", $row['pedregal']);
							$hoja1->setCellValue("K{$flag1}", $row['tienda']);
							$hoja1->setCellValue("L{$flag1}", $row['ultra']);
							$hoja1->setCellValue("M{$flag1}", $row['trincheras']);
							$hoja1->setCellValue("N{$flag1}", $row['mercado']);
							$hoja1->setCellValue("O{$flag1}", $row['tenencia']);
							$hoja1->setCellValue("P{$flag1}", $row['tijeras']);

							if ($row["imagen"] <> "" && !is_null($row["imagen"]) ) {
								$objDrawing = new PHPExcel_Worksheet_Drawing();
								$objDrawing->setName('COD'.$row['producto']);
								$objDrawing->setDescription('DESC'.$row['codigo']);
								$objDrawing->setPath("./Abarrotes/assets/img/productos/".$row["imagen"]."");
								$objDrawing->setWidth(50);
								$objDrawing->setHeight(50);
								$objDrawing->setCoordinates('F'.$flag1);
								$objDrawing->setOffsetX(5); 
								$objDrawing->setOffsetY(5);
								//$objDrawing->setUrl('http://abarrotesazteca.com/Abarrotes/assets/img/productos/'.$row["imagen"]);
								$objDrawing->setWorksheet($this->excelfile->getActiveSheet());
								$this->excelfile->getActiveSheet()->getRowDimension($flag1)->setRowHeight(60);
								$this->excelfile->getActiveSheet()->getStyleByColumnAndRow(10, $flag1)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_DATE_XLSX14);
								$this->excelfile->getActiveSheet()->getCell('F'.$flag1)->getHyperlink()->setUrl('http://abarrotesazteca.com/Abarrotes/assets/img/productos/'.str_replace("_thumb.",".",$row["imagen"]));
							}

							$hoja1->getStyle("A{$flag1}:P{$flag1}")
					                 ->getAlignment()
					                 ->setHorizontal(\PHPExcel_Style_Alignment::HORIZONTAL_LEFT);
					         
			                
							//Pedidos
							$this->excelfile->setActiveSheetIndex(1);
							$this->cellStyle("A".$flag.":BL".$flag."", "FFFFFF", "000000", FALSE, 12, "Franklin Gothic Book");
							
							$hoja->setCellValue("A{$flag}", $row['codigo'])->getStyle("A{$flag}")->getNumberFormat()->setFormatCode('# ???/???');//Formato de fraccion
							$hoja->setCellValue("B{$flag}", $row['codigo_factura'])->getStyle("B{$flag}")->getNumberFormat()->setFormatCode('# ???/???');//Formato de fraccion
							if($row['color'] == '#92CEE3'){
								$this->cellStyle("A{$flag}", "92CEE3", "000000", FALSE, 12, "Franklin Gothic Book");
							}else{
								$this->cellStyle("A{$flag}", "FFFFFF", "000000", FALSE, 12, "Franklin Gothic Book");
							}
							$hoja->setCellValue("C{$flag}", $row['producto']);
							$hoja->setCellValue("D{$flag}", $row['unidad']);
							
							

							$this->cellStyle("E{$flag}", "FFFFFF", "000000", FALSE, 12, "Franklin Gothic Book");
							$hoja->setCellValue("E{$flag}", $row['reales'])->getStyle("E{$flag}")->getNumberFormat()->setFormatCode('"$"#,##0.00_-');
							if(number_format(($row['precio_first'] - $row['reales']),2) === "0.01" || number_format(($row['precio_first'] - $row['reales']),2) === "-0.01"){
								$this->cellStyle("E{$flag}", "FFFFFF", "000000", FALSE, 12, "Franklin Gothic Book");
							}elseif($row['precio_first'] < $row['reales']){
								$this->cellStyle("E{$flag}", "FFFFFF", "E21600", FALSE, 12, "Franklin Gothic Book");
							}else{
								$this->cellStyle("E{$flag}", "FFFFFF", "249947", FALSE, 12, "Franklin Gothic Book");
							}

							if (number_format(($row['precio_sistema'] - $row['precio_first']),2) === "0.01" || number_format(($row['precio_sistema'] - $row['precio_first']),2) === "-0.01") {
								$hoja->setCellValue("F{$flag}", $row['precio_first'])->getStyle("F{$flag}")->getNumberFormat()->setFormatCode('"$"#,##0.00_-');
								$this->cellStyle("F{$flag}", "FFFFFF", "000000", FALSE, 12, "Franklin Gothic Book");
								$this->cellStyle("F{$flag}", "FFFFFF", "000000", FALSE, 12, "Franklin Gothic Book");
								$this->cellStyle("C{$flag}", "FFFFFF", "000000", FALSE, 12, "Franklin Gothic Book");
							}elseif($row['precio_sistema'] < $row['precio_first']){
								$hoja->setCellValue("F{$flag}", $row['precio_first'])->getStyle("F{$flag}")->getNumberFormat()->setFormatCode('"$"#,##0.00_-');
								$this->cellStyle("F{$flag}", "FDB2B2", "E21111", FALSE, 12, "Franklin Gothic Book");
								$this->cellStyle("F{$flag}", "FDB2B2", "E21111", FALSE, 12, "Franklin Gothic Book");
								$this->cellStyle("C{$flag}", "E21600", "000000", FALSE, 12, "Franklin Gothic Book");
							}else{
								$hoja->setCellValue("F{$flag}", $row['precio_first'])->getStyle("F{$flag}")->getNumberFormat()->setFormatCode('"$"#,##0.00_-');
								$this->cellStyle("F{$flag}", "96EAA8", "0C800C", FALSE, 12, "Franklin Gothic Book");
								$this->cellStyle("C{$flag}", "249947", "000000", FALSE, 12, "Franklin Gothic Book");
							}

							$hoja->setCellValue("H{$flag}", $row['precio_sistema'])->getStyle("H{$flag}")->getNumberFormat()->setFormatCode('"$"#,##0.00_-');//Formto de moneda
							
							$this->cellStyle("H".$flag, "FFFFFF","000000",  FALSE, 12, "Franklin Gothic Book");
							if($row['colorp'] == 1){
								$this->cellStyle("H{$flag}", "D6DCE4", "000000", FALSE, 12, "Franklin Gothic Book");
							}else{
								$this->cellStyle("H{$flag}", "FFFFFF", "000000", FALSE, 12, "Franklin Gothic Book");
							}

							$hoja->setCellValue("J{$flag}", $row['precio_four'])->getStyle("J{$flag}")->getNumberFormat()->setFormatCode('"$"#,##0.00_-');
							$this->cellStyle("J{$flag}", "FFFFFF", "000000", FALSE, 12, "Franklin Gothic Book");
							if($row['precio_sistema'] < $row['precio_next']){
								$hoja->setCellValue("K{$flag}", $row['precio_next'])->getStyle("K{$flag}")->getNumberFormat()->setFormatCode('"$"#,##0.00_-');
								$this->cellStyle("K{$flag}", "FDB2B2", "E21111", FALSE, 12, "Franklin Gothic Book");
							}else if($row['precio_next'] !== NULL){
								$hoja->setCellValue("K{$flag}", $row['precio_next'])->getStyle("K{$flag}")->getNumberFormat()->setFormatCode('"$"#,##0.00_-');
								$this->cellStyle("K{$flag}", "96EAA8", "0C800C", FALSE, 12, "Franklin Gothic Book");
							}else{
								$hoja->setCellValue("K{$flag}", $row['precio_next'])->getStyle("K{$flag}")->getNumberFormat()->setFormatCode('"$"#,##0.00_-');
								$this->cellStyle("K{$flag}", "FFFFFF", "000000", FALSE, 12, "Franklin Gothic Book");
							}
							$hoja->setCellValue("L{$flag}", $row['proveedor_next']);
							$this->cellStyle("M".$flag.":BM".$flag, "FFFFFF", "000000", TRUE, 12, "Franklin Gothic Book");
							$this->cellStyle("L".$flag, "FFFFFF", "000000", FALSE, 12, "Franklin Gothic Book");



							$hoja->setCellValue("M{$flag}", $row['caja0']);
							$hoja->setCellValue("N{$flag}", $row['pz0']);
							$hoja->setCellValue("O{$flag}", $row['cedis']);
							$hoja->setCellValue("P{$flag}", $row["past"]["caja0"])->getStyle("P{$flag}")->getNumberFormat()->setFormatCode('#,##0-');
							$hoja->setCellValue("Q{$flag}", $row['ped0']);
							$this->cellStyle("Q{$flag}", "D4EAEF", "000000", TRUE, 12, "Franklin Gothic Book");

							
							$hoja->setCellValue("R{$flag}", $row['caja9']);
							$hoja->setCellValue("S{$flag}", $row['pz9']);
							$hoja->setCellValue("T{$flag}", $row["past"]["caja9"])->getStyle("T{$flag}")->getNumberFormat()->setFormatCode('#,##0-');
							$hoja->setCellValue("U{$flag}", $row['ped9']);
							$this->cellStyle("U{$flag}", "D4EAEF", "000000", TRUE, 12, "Franklin Gothic Book");


							$hoja->setCellValue("V{$flag}", "=M".$flag."+R".$flag);
							$hoja->setCellValue("W{$flag}", "=N".$flag."+S".$flag);
							$hoja->setCellValue("X{$flag}", "=Q".$flag."+U".$flag);
							$this->cellStyle("X{$flag}", "D4EAEF", "000000", TRUE, 12, "Franklin Gothic Book");

							$hoja->setCellValue("Y{$flag}", $row['caja1']);
							$hoja->setCellValue("Z{$flag}", $row['pz1']);
							$hoja->setCellValue("AA{$flag}", $row['abarrotes']);
							$hoja->setCellValue("AB{$flag}", $row["past"]["caja1"])->getStyle("AB{$flag}")->getNumberFormat()->setFormatCode('#,##0_-');
							$hoja->setCellValue("AC{$flag}", $row['ped1']);
							$this->cellStyle("AC{$flag}", "D4EAEF", "000000", TRUE, 12, "Franklin Gothic Book");

							
							$hoja->setCellValue("AD{$flag}", $row['caja2']);
							$hoja->setCellValue("AE{$flag}", $row['pz2']);
							$hoja->setCellValue("AF{$flag}", $row['pedregal']);
							$hoja->setCellValue("AG{$flag}", $row["past"]["caja2"])->getStyle("AG{$flag}")->getNumberFormat()->setFormatCode('#,##0_-');
							$hoja->setCellValue("AH{$flag}", $row['ped2']);
							$this->cellStyle("AH{$flag}", "D4EAEF", "000000", TRUE, 12, "Franklin Gothic Book");

							
							$hoja->setCellValue("AI{$flag}", $row['caja3']);
							$hoja->setCellValue("AJ{$flag}", $row['pz3']);
							$hoja->setCellValue("AK{$flag}", $row['tienda']);
							$hoja->setCellValue("AL{$flag}", $row["past"]["caja3"])->getStyle("AL{$flag}")->getNumberFormat()->setFormatCode('#,##0_-');
							$hoja->setCellValue("AM{$flag}", $row['ped3']);
							$this->cellStyle("AM{$flag}", "D4EAEF", "000000", TRUE, 12, "Franklin Gothic Book");

							
							$hoja->setCellValue("AN{$flag}", $row['caja4']);
							$hoja->setCellValue("AO{$flag}", $row['pz4']);
							$hoja->setCellValue("AP{$flag}", $row['ultra']);
							$hoja->setCellValue("AQ{$flag}", $row["past"]["caja4"])->getStyle("AQ{$flag}")->getNumberFormat()->setFormatCode('#,##0_-');
							$hoja->setCellValue("AR{$flag}", $row['ped4']);
							$this->cellStyle("AR{$flag}", "D4EAEF", "000000", TRUE, 12, "Franklin Gothic Book");

							
							$hoja->setCellValue("AS{$flag}", $row['caja5']);
							$hoja->setCellValue("AT{$flag}", $row['pz5']);
							$hoja->setCellValue("AU{$flag}", $row['trincheras']);
							$hoja->setCellValue("AV{$flag}", $row["past"]["caja5"])->getStyle("AV{$flag}")->getNumberFormat()->setFormatCode('#,##0_-');
							$hoja->setCellValue("AW{$flag}", $row['ped5']);
							$this->cellStyle("AW{$flag}", "D4EAEF", "000000", TRUE, 12, "Franklin Gothic Book");

							
							$hoja->setCellValue("AX{$flag}", $row['caja6']);
							$hoja->setCellValue("AY{$flag}", $row['pz6']);
							$hoja->setCellValue("AZ{$flag}", $row['mercado']);
							$hoja->setCellValue("BA{$flag}", $row["past"]["caja6"])->getStyle("BA{$flag}")->getNumberFormat()->setFormatCode('#,##0_-');
							$hoja->setCellValue("BB{$flag}", $row['ped6']);
							$this->cellStyle("BB{$flag}", "D4EAEF", "000000", TRUE, 12, "Franklin Gothic Book");

							
							$hoja->setCellValue("BC{$flag}", $row['caja7']);
							$hoja->setCellValue("BD{$flag}", $row['pz7']);
							$hoja->setCellValue("BE{$flag}", $row['tenencia']);
							$hoja->setCellValue("BF{$flag}", $row["past"]["caja7"])->getStyle("BF{$flag}")->getNumberFormat()->setFormatCode('#,##0_-');
							$hoja->setCellValue("BG{$flag}", $row['ped7']);
							$this->cellStyle("BG{$flag}", "D4EAEF", "000000", TRUE, 12, "Franklin Gothic Book");

							
							$hoja->setCellValue("BH{$flag}", $row['caja8']);
							$hoja->setCellValue("BI{$flag}", $row['pz8']);
							$hoja->setCellValue("BJ{$flag}", $row['tijeras']);
							$hoja->setCellValue("BK{$flag}", $row["past"]["caja8"])->getStyle("BK{$flag}")->getNumberFormat()->setFormatCode('#,##0_-');
							$hoja->setCellValue("BL{$flag}", $row['ped8']);
							$this->cellStyle("BL{$flag}", "D4EAEF", "000000", TRUE, 12, "Franklin Gothic Book");

							

							

							$hoja->setCellValue("BM{$flag}", $row['promocion_first']);
							$hoja->setCellValue("BN{$flag}", "=F".$flag."*Q".$flag)->getStyle("BN{$flag}")->getNumberFormat()->setFormatCode('"$"#,##0.00_-');
							$hoja->setCellValue("BO{$flag}", "=F".$flag."*AC".$flag)->getStyle("BO{$flag}")->getNumberFormat()->setFormatCode('"$"#,##0.00_-');
							$hoja->setCellValue("BP{$flag}", "=F".$flag."*AH".$flag)->getStyle("BP{$flag}")->getNumberFormat()->setFormatCode('"$"#,##0.00_-');
							$hoja->setCellValue("BQ{$flag}", "=F".$flag."*AM".$flag)->getStyle("BQ{$flag}")->getNumberFormat()->setFormatCode('"$"#,##0.00_-');
							$hoja->setCellValue("BR{$flag}", "=F".$flag."*AR".$flag)->getStyle("BR{$flag}")->getNumberFormat()->setFormatCode('"$"#,##0.00_-');
							$hoja->setCellValue("BS{$flag}", "=F".$flag."*AW".$flag)->getStyle("BS{$flag}")->getNumberFormat()->setFormatCode('"$"#,##0.00_-');
							$hoja->setCellValue("BT{$flag}", "=F".$flag."*BB".$flag)->getStyle("BT{$flag}")->getNumberFormat()->setFormatCode('"$"#,##0.00_-');
							$hoja->setCellValue("BU{$flag}", "=F".$flag."*BG".$flag)->getStyle("BU{$flag}")->getNumberFormat()->setFormatCode('"$"#,##0.00_-');
							$hoja->setCellValue("BV{$flag}", "=F".$flag."*BL".$flag)->getStyle("BV{$flag}")->getNumberFormat()->setFormatCode('"$"#,##0.00_-');
							$this->cellStyle("BW{$flag}", "D4EAEF", "000000", FALSE, 12, "Franklin Gothic Book");
							$hoja->setCellValue("BW{$flag}", "=SUM(BN".$flag.":BV".$flag.")")->getStyle("BW{$flag}")->getNumberFormat()->setFormatCode('"$"#,##0.00_-');
							$this->cellStyle("BX{$flag}", "C2B90A", "000000", FALSE, 12, "Franklin Gothic Book");
							$hoja->setCellValue("BX{$flag}", "=Q".$flag."+AC".$flag."+AH".$flag."+AM".$flag."+AR".$flag."+AW".$flag."+BB".$flag."+BG".$flag."+BL".$flag."");

							//Begin: TOTALES PEDIDOS PENDIENTES
							$hoja->setCellValue("CA{$flag}", "=F".$flag."*O".$flag)->getStyle("CA{$flag}")->getNumberFormat()->setFormatCode('"$"#,##0.00_-');
							$hoja->setCellValue("CB{$flag}", "=F".$flag."*AA".$flag)->getStyle("CB{$flag}")->getNumberFormat()->setFormatCode('"$"#,##0.00_-');
							$hoja->setCellValue("CC{$flag}", "=F".$flag."*AF".$flag)->getStyle("CC{$flag}")->getNumberFormat()->setFormatCode('"$"#,##0.00_-');
							$hoja->setCellValue("CD{$flag}", "=F".$flag."*AK".$flag)->getStyle("CD{$flag}")->getNumberFormat()->setFormatCode('"$"#,##0.00_-');
							$hoja->setCellValue("CE{$flag}", "=F".$flag."*AP".$flag)->getStyle("CE{$flag}")->getNumberFormat()->setFormatCode('"$"#,##0.00_-');
							$hoja->setCellValue("CF{$flag}", "=F".$flag."*AU".$flag)->getStyle("CF{$flag}")->getNumberFormat()->setFormatCode('"$"#,##0.00_-');
							$hoja->setCellValue("CG{$flag}", "=F".$flag."*AZ".$flag)->getStyle("CG{$flag}")->getNumberFormat()->setFormatCode('"$"#,##0.00_-');
							$hoja->setCellValue("CH{$flag}", "=F".$flag."*BE".$flag)->getStyle("CH{$flag}")->getNumberFormat()->setFormatCode('"$"#,##0.00_-');
							$hoja->setCellValue("CI{$flag}", "=F".$flag."*BJ".$flag)->getStyle("CI{$flag}")->getNumberFormat()->setFormatCode('"$"#,##0.00_-');
							$this->cellStyle("CJ{$flag}", "000000", "FFFFFF", FALSE, 12, "Franklin Gothic Book");
							$hoja->setCellValue("CJ{$flag}", "=SUM(CA".$flag.":CI".$flag.")")->getStyle("CJ{$flag}")->getNumberFormat()->setFormatCode('"$"#,##0.00_-');
							$this->cellStyle("CK{$flag}", "000000", "FFFFFF", FALSE, 12, "Franklin Gothic Book");
							$hoja->setCellValue("CK{$flag}", "=O".$flag."+AA".$flag."+AF".$flag."+AK".$flag."+AP".$flag."+AU".$flag."+AZ".$flag."+BE".$flag."+BJ".$flag."");
							//End: TOTALES PEDIDOS PENDIENTES
							
							$border_style= array('borders' => array('right' => array('style' =>
								PHPExcel_Style_Border::BORDER_THIN,'color' => array('argb' => '000000'),)));
							$this->excelfile->setActiveSheetIndex(1);
							$this->excelfile->getActiveSheet()->getStyle('A'.$flag.':BM'.$flag)->applyFromArray($styleArray);
							$this->excelfile->getActiveSheet()->getStyle('BW'.$flag)->applyFromArray($styleArray);
							$this->excelfile->getActiveSheet()->getStyle('BX'.$flag)->applyFromArray($styleArray);
							$this->excelfile->setActiveSheetIndex(0);
							$this->excelfile->getActiveSheet()->getStyle('A'.$flag1.':P'.$flag1)->applyFromArray($styleArray);
							$hoja->getStyle("A{$flag}:L{$flag}")
					                 ->getAlignment()
					                 ->setHorizontal(\PHPExcel_Style_Alignment::HORIZONTAL_LEFT);

						    if($this->weekNumber($registrazo) == ($this->weekNumber() - 1) || $this->weekNumber($registrazo) == ($this->weekNumber())){
								$this->cellStyle("A{$flag}", "FF7F71", "000000", FALSE, 12, "Franklin Gothic Book");
								$this->cellStyle("B{$flag}", "FF7F71", "000000", FALSE, 12, "Franklin Gothic Book");
							}
							if($row['precio_sistema'] == 0){
								$row['precio_sistema'] = 1;
							}
							if($row['precio_four'] == 0){
								$row['precio_four'] = 1;
							}

							$hoja->setCellValue("G{$flag}",100 - ($row['precio_first'] * 100 / $row['precio_sistema']))->getStyle("G{$flag}")->getNumberFormat()->setFormatCode('"%"#,##0.00_-');;
							$this->cellStyle("G".$flag, "FF9999", "000000", FALSE, 10, "Franklin Gothic Book");

							$hoja->setCellValue("I{$flag}", 100 - ($row['precio_sistema'] * 100 / $row['precio_four']))->getStyle("I{$flag}")->getNumberFormat()->setFormatCode('"%"#,##0.00_-');;
							$this->cellStyle("I".$flag, "FF9999", "000000", FALSE, 10, "Franklin Gothic Book");


							$flag ++;
							$flag1 ++;
						}
					}
				}	

				$flans = $flag - 1;
				$hoja->setCellValue("BN{$flag}", "=SUM(BN{$bandera}:BN{$flans})")->getStyle("BN{$flag}")->getNumberFormat()->setFormatCode('"$"#,##0.00_-');
				$hoja->setCellValue("BO{$flag}", "=SUM(BO{$bandera}:BO{$flans})")->getStyle("BO{$flag}")->getNumberFormat()->setFormatCode('"$"#,##0.00_-');
				$hoja->setCellValue("BP{$flag}", "=SUM(BP{$bandera}:BP{$flans})")->getStyle("BP{$flag}")->getNumberFormat()->setFormatCode('"$"#,##0.00_-');
				$hoja->setCellValue("BQ{$flag}", "=SUM(BQ{$bandera}:BQ{$flans})")->getStyle("BQ{$flag}")->getNumberFormat()->setFormatCode('"$"#,##0.00_-');
				$hoja->setCellValue("BR{$flag}", "=SUM(BR{$bandera}:BR{$flans})")->getStyle("BR{$flag}")->getNumberFormat()->setFormatCode('"$"#,##0.00_-');
				$hoja->setCellValue("BS{$flag}", "=SUM(BS{$bandera}:BS{$flans})")->getStyle("BS{$flag}")->getNumberFormat()->setFormatCode('"$"#,##0.00_-');
				$hoja->setCellValue("BT{$flag}", "=SUM(BT{$bandera}:BT{$flans})")->getStyle("BT{$flag}")->getNumberFormat()->setFormatCode('"$"#,##0.00_-');
				$hoja->setCellValue("BU{$flag}", "=SUM(BU{$bandera}:BU{$flans})")->getStyle("BU{$flag}")->getNumberFormat()->setFormatCode('"$"#,##0.00_-');
				$hoja->setCellValue("BV{$flag}", "=SUM(BV{$bandera}:BV{$flans})")->getStyle("BV{$flag}")->getNumberFormat()->setFormatCode('"$"#,##0.00_-');
				$hoja->setCellValue("BW{$flag}", "=SUM(BW{$bandera}:BW{$flans})")->getStyle("BW{$flag}")->getNumberFormat()->setFormatCode('"$"#,##0.00_-');

				//Begin: TOTALES PEDIDOS PENDIENTES
				$hoja->setCellValue("CA{$flag}", "=SUM(CA{$bandera}:CA{$flans})")->getStyle("CA{$flag}")->getNumberFormat()->setFormatCode('"$"#,##0.00_-');
				$hoja->setCellValue("CB{$flag}", "=SUM(CB{$bandera}:CB{$flans})")->getStyle("CB{$flag}")->getNumberFormat()->setFormatCode('"$"#,##0.00_-');
				$hoja->setCellValue("CC{$flag}", "=SUM(CC{$bandera}:CC{$flans})")->getStyle("CC{$flag}")->getNumberFormat()->setFormatCode('"$"#,##0.00_-');
				$hoja->setCellValue("CD{$flag}", "=SUM(CD{$bandera}:CD{$flans})")->getStyle("CD{$flag}")->getNumberFormat()->setFormatCode('"$"#,##0.00_-');
				$hoja->setCellValue("CE{$flag}", "=SUM(CE{$bandera}:CE{$flans})")->getStyle("CE{$flag}")->getNumberFormat()->setFormatCode('"$"#,##0.00_-');
				$hoja->setCellValue("CF{$flag}", "=SUM(CF{$bandera}:CF{$flans})")->getStyle("CF{$flag}")->getNumberFormat()->setFormatCode('"$"#,##0.00_-');
				$hoja->setCellValue("CG{$flag}", "=SUM(CG{$bandera}:CG{$flans})")->getStyle("CG{$flag}")->getNumberFormat()->setFormatCode('"$"#,##0.00_-');
				$hoja->setCellValue("CH{$flag}", "=SUM(CH{$bandera}:CH{$flans})")->getStyle("CH{$flag}")->getNumberFormat()->setFormatCode('"$"#,##0.00_-');
				$hoja->setCellValue("CI{$flag}", "=SUM(CI{$bandera}:CI{$flans})")->getStyle("CI{$flag}")->getNumberFormat()->setFormatCode('"$"#,##0.00_-');
				$hoja->setCellValue("CJ{$flag}", "=SUM(CJ{$bandera}:CJ{$flans})")->getStyle("CJ{$flag}")->getNumberFormat()->setFormatCode('"$"#,##0.00_-');
				//End: TOTALES PEDIDOS PENDIENTES

				$flans = $flag;
				$flag += 4;
				//Begin: TOTALES PEDIDOS PENDIENTES
				$hoja->mergeCells('F'.($flag-1).':I'.($flag-1));
				$this->cellStyle('F'.($flag-1).':I'.($flag-1), "000000", "FFFFFF", TRUE, 12, "Franklin Gothic Book");
				$hoja->setCellValue("F".($flag-1), "TOTALES POR PENDIENTES");
				//End: TOTALES PEDIDOS PENDIENTES
				$this->cellStyle("C".$flag, "66FFFB", "000000", TRUE, 12, "Franklin Gothic Book");
				$hoja->setCellValue("C".$flag, "CEDIS");
				$hoja->setCellValue("D{$flag}", "=(BN{$flans})")->getStyle("D{$flag}")->getNumberFormat()->setFormatCode('"$"#,##0.00_-');
				//Begin: TOTALES PEDIDOS PENDIENTES
				$hoja->mergeCells('F'.$flag.':G'.$flag);
				$hoja->mergeCells('H'.$flag.':I'.$flag);
				$this->cellStyle('F'.$flag.':G'.$flag, "000000", "FFFFFF", TRUE, 12, "Franklin Gothic Book");
				$hoja->setCellValue("F".$flag, "CEDIS");
				$hoja->setCellValue("H{$flag}", "=(CA{$flans})")->getStyle("H{$flag}")->getNumberFormat()->setFormatCode('"$"#,##0.00_-');
				//End: TOTALES PEDIDOS PENDIENTES
				$flag++;
				$this->cellStyle("C".$flag, "01B0F0", "000000", TRUE, 12, "Franklin Gothic Book");
				$hoja->setCellValue("C".$flag, "ABARROTES");
				$hoja->setCellValue("D{$flag}", "=(BO{$flans})")->getStyle("D{$flag}")->getNumberFormat()->setFormatCode('"$"#,##0.00_-');
				//Begin: TOTALES PEDIDOS PENDIENTES
				$hoja->mergeCells('F'.$flag.':G'.$flag);
				$hoja->mergeCells('H'.$flag.':I'.$flag);
				$this->cellStyle('F'.$flag.':G'.$flag, "000000", "FFFFFF", TRUE, 12, "Franklin Gothic Book");
				$hoja->setCellValue("F".$flag, "ABARROTES");
				$hoja->setCellValue("H{$flag}", "=(CB{$flans})")->getStyle("H{$flag}")->getNumberFormat()->setFormatCode('"$"#,##0.00_-');
				//End: TOTALES PEDIDOS PENDIENTES
				$flag++;
				$this->cellStyle("C".$flag, "FF0000", "000000", TRUE, 12, "Franklin Gothic Book");
				$hoja->setCellValue("C".$flag, "VILLAS");
				$hoja->setCellValue("D{$flag}", "=(BP{$flans})")->getStyle("D{$flag}")->getNumberFormat()->setFormatCode('"$"#,##0.00_-');
				//Begin: TOTALES PEDIDOS PENDIENTES
				$hoja->mergeCells('F'.$flag.':G'.$flag);
				$hoja->mergeCells('H'.$flag.':I'.$flag);
				$this->cellStyle('F'.$flag.':G'.$flag, "000000", "FFFFFF", TRUE, 12, "Franklin Gothic Book");
				$hoja->setCellValue("F".$flag, "VILLAS");
				$hoja->setCellValue("H{$flag}", "=(CC{$flans})")->getStyle("H{$flag}")->getNumberFormat()->setFormatCode('"$"#,##0.00_-');
				//End: TOTALES PEDIDOS PENDIENTES
				$flag++;
				$this->cellStyle("C".$flag, "E26C0B", "000000", TRUE, 12, "Franklin Gothic Book");
				$hoja->setCellValue("C".$flag, "TIENDA");
				$hoja->setCellValue("D{$flag}", "=(BQ{$flans})")->getStyle("D{$flag}")->getNumberFormat()->setFormatCode('"$"#,##0.00_-');
				//Begin: TOTALES PEDIDOS PENDIENTES
				$hoja->mergeCells('F'.$flag.':G'.$flag);
				$hoja->mergeCells('H'.$flag.':I'.$flag);
				$this->cellStyle('F'.$flag.':G'.$flag, "000000", "FFFFFF", TRUE, 12, "Franklin Gothic Book");
				$hoja->setCellValue("F".$flag, "TIENDA");
				$hoja->setCellValue("H{$flag}", "=(CD{$flans})")->getStyle("H{$flag}")->getNumberFormat()->setFormatCode('"$"#,##0.00_-');
				//End: TOTALES PEDIDOS PENDIENTES
				$flag++;
				$this->cellStyle("C".$flag, "C5C5C5", "000000", TRUE, 12, "Franklin Gothic Book");
				$hoja->setCellValue("C".$flag, "ULTRAMARINOS");
				$hoja->setCellValue("D{$flag}", "=(BR{$flans})")->getStyle("D{$flag}")->getNumberFormat()->setFormatCode('"$"#,##0.00_-');
				//Begin: TOTALES PEDIDOS PENDIENTES
				$hoja->mergeCells('F'.$flag.':G'.$flag);
				$hoja->mergeCells('H'.$flag.':I'.$flag);
				$this->cellStyle('F'.$flag.':G'.$flag, "000000", "FFFFFF", TRUE, 12, "Franklin Gothic Book");
				$hoja->setCellValue("F".$flag, "ULTRAMARINOS");
				$hoja->setCellValue("H{$flag}", "=(CE{$flans})")->getStyle("H{$flag}")->getNumberFormat()->setFormatCode('"$"#,##0.00_-');
				//End: TOTALES PEDIDOS PENDIENTES
				$flag++;
				$this->cellStyle("C".$flag, "92D051", "000000", TRUE, 12, "Franklin Gothic Book");
				$hoja->setCellValue("C".$flag, "TRINCHERAS");
				$hoja->setCellValue("D{$flag}", "=(BS{$flans})")->getStyle("D{$flag}")->getNumberFormat()->setFormatCode('"$"#,##0.00_-');
				//Begin: TOTALES PEDIDOS PENDIENTES
				$hoja->mergeCells('F'.$flag.':G'.$flag);
				$hoja->mergeCells('H'.$flag.':I'.$flag);
				$this->cellStyle('F'.$flag.':G'.$flag, "000000", "FFFFFF", TRUE, 12, "Franklin Gothic Book");
				$hoja->setCellValue("F".$flag, "TRINCHERAS");
				$hoja->setCellValue("H{$flag}", "=(CF{$flans})")->getStyle("H{$flag}")->getNumberFormat()->setFormatCode('"$"#,##0.00_-');
				//End: TOTALES PEDIDOS PENDIENTES
				$flag++;
				$this->cellStyle("C".$flag, "B1A0C7", "000000", TRUE, 12, "Franklin Gothic Book");
				$hoja->setCellValue("C".$flag, "AZT MERCADO");
				$hoja->setCellValue("D{$flag}", "=(BT{$flans})")->getStyle("D{$flag}")->getNumberFormat()->setFormatCode('"$"#,##0.00_-');
				//Begin: TOTALES PEDIDOS PENDIENTES
				$hoja->mergeCells('F'.$flag.':G'.$flag);
				$hoja->mergeCells('H'.$flag.':I'.$flag);
				$this->cellStyle('F'.$flag.':G'.$flag, "000000", "FFFFFF", TRUE, 12, "Franklin Gothic Book");
				$hoja->setCellValue("F".$flag, "AZT MERCADO");
				$hoja->setCellValue("H{$flag}", "=(CG{$flans})")->getStyle("H{$flag}")->getNumberFormat()->setFormatCode('"$"#,##0.00_-');
				//End: TOTALES PEDIDOS PENDIENTES
				$flag++;
				$this->cellStyle("C".$flag, "DA9694", "000000", TRUE, 12, "Franklin Gothic Book");
				$hoja->setCellValue("C".$flag, "TENENCIA");
				$hoja->setCellValue("D{$flag}", "=(BU{$flans})")->getStyle("D{$flag}")->getNumberFormat()->setFormatCode('"$"#,##0.00_-');
				//Begin: TOTALES PEDIDOS PENDIENTES
				$hoja->mergeCells('F'.$flag.':G'.$flag);
				$hoja->mergeCells('H'.$flag.':I'.$flag);
				$this->cellStyle('F'.$flag.':G'.$flag, "000000", "FFFFFF", TRUE, 12, "Franklin Gothic Book");
				$hoja->setCellValue("F".$flag, "TENENCIA");
				$hoja->setCellValue("H{$flag}", "=(CH{$flans})")->getStyle("H{$flag}")->getNumberFormat()->setFormatCode('"$"#,##0.00_-');
				//End: TOTALES PEDIDOS PENDIENTES
				$flag++;
				$this->cellStyle("C".$flag, "4CACC6", "000000", TRUE, 12, "Franklin Gothic Book");
				$hoja->setCellValue("C".$flag, "TIJERAS");
				$hoja->setCellValue("D{$flag}", "=(BV{$flans})")->getStyle("D{$flag}")->getNumberFormat()->setFormatCode('"$"#,##0.00_-');
				//Begin: TOTALES PEDIDOS PENDIENTES
				$hoja->mergeCells('F'.$flag.':G'.$flag);
				$hoja->mergeCells('H'.$flag.':I'.$flag);
				$this->cellStyle('F'.$flag.':G'.$flag, "000000", "FFFFFF", TRUE, 12, "Franklin Gothic Book");
				$hoja->setCellValue("F".$flag, "TIJERAS");
				$hoja->setCellValue("H{$flag}", "=(CI{$flans})")->getStyle("H{$flag}")->getNumberFormat()->setFormatCode('"$"#,##0.00_-');
				//End: TOTALES PEDIDOS PENDIENTES
				$flag++;
				$this->cellStyle("C".$flag, "000000", "FFFFFF", TRUE, 12, "Franklin Gothic Book");
				$hoja->setCellValue("C".$flag, "TOTAL");
				$hoja->setCellValue("D{$flag}", "=(BW{$flans})")->getStyle("D{$flag}")->getNumberFormat()->setFormatCode('"$"#,##0.00_-');
				//Begin: TOTALES PEDIDOS PENDIENTES
				$hoja->mergeCells('F'.$flag.':G'.$flag);
				$hoja->mergeCells('H'.$flag.':I'.$flag);
				$this->cellStyle('F'.$flag.':G'.$flag, "000000", "FFFFFF", TRUE, 12, "Franklin Gothic Book");
				$hoja->setCellValue("F".$flag, "TOTAL");
				$hoja->setCellValue("H{$flag}", "=(CJ{$flans})")->getStyle("H{$flag}")->getNumberFormat()->setFormatCode('"$"#,##0.00_-');
				//End: TOTALES PEDIDOS PENDIENTES
				$flag++;


				$flag = $flag+5;
				$flag1 = $flag+5;
			}
		}

		$dias = array("DOMINGO","LUNES","MARTES","MIÉRCOLES","JUEVES","VIERNES","SÁBADO");
		$meses = array("ENERO","FEBRERO","MARZO","ABRIL","MAYO","JUNIO","JULIO","AGOSTO","SEPTIEMBRE","OCTUBRE","NOVIEMBRE","DICIEMBRE");
		$fecha =  $dias[date('w')]." ".date('d')." DE ".$meses[date('n')-1]. " DEL ".date('Y') ;
		$file_name = "FORMATO ".$filenam." ".$fecha.".xlsx"; //Nombre del documento con extención
		$excel_Writer = PHPExcel_IOFactory::createWriter($this->excelfile, "Excel2007");
		header("Content-Type: application/vnd.ms-excel; charset=utf-8");
		header("Content-Disposition: attachment;filename=".$file_name);
		header("Cache-Control: max-age=0");
		$excel_Writer->save("php://output");
	}

	private function fill_volumen($id_proves,$proves,$prs){
		$flag =1;
		$flag1 = 1;
		$array = "";
		$array2 = "";
		$filenam = "";
		$id_proves = $this->input->post('id_proves4');
		$proves = $this->input->post('id_proves2');
		$prs = substr($id_proves,0,6);
		$filenam = $id_proves;
		$array = (object)['0'=>(object)['nombre' => $id_proves]];
		//$this->jsonResponse($array);
		ini_set("memory_limit", "-1");
		ini_set("max_execution_time", "-1");
		$this->load->library("excelfile");
		$hoja1 = $this->excelfile->setActiveSheetIndex(0);
		$this->excelfile->setActiveSheetIndex(0)->setTitle("EXISTENCIAS");
		$this->excelfile->createSheet();
        $hoja = $this->excelfile->setActiveSheetIndex(1);
        $hoja->setTitle("PEDIDO");
		$styleArray = array(
		  'borders' => array(
		    'allborders' => array(
		      'style' => PHPExcel_Style_Border::BORDER_THIN
		    )
		  )
		);
		$hoja->getColumnDimension('A')->setWidth("20");
		$hoja->getColumnDimension('B')->setWidth("70");
		$hoja->getColumnDimension('C')->setWidth("15");
		$hoja->getColumnDimension('D')->setWidth("8");

		$hoja->getColumnDimension('E')->setWidth("15");
		$hoja->getColumnDimension('G')->setWidth("8");
		$hoja->getColumnDimension('I')->setWidth("8");
		$hoja->getColumnDimension('F')->setWidth("20");
		$hoja->getColumnDimension('H')->setWidth("15");

		$hoja1->getColumnDimension('A')->setWidth("6");
		$hoja1->getColumnDimension('B')->setWidth("6");
		$hoja1->getColumnDimension('C')->setWidth("6");
		$hoja1->getColumnDimension('D')->setWidth("25");
		$hoja1->getColumnDimension('E')->setWidth("47");
		$hoja1->getColumnDimension('G')->setWidth("50");

		$hoja->getColumnDimension('BM' )->setWidth("70");
		$hoja->getColumnDimension('L')->setWidth("20");

		$flagBorder = 0;
		$flagBorder1 = 1;
		$flagBorder2 = 0;
		$flagBorder3 = 1;
		$flage = 5;
		$i = 0;
		$sumall = array(1 => "", 2 => "", 3 => "", 4 => "", 5 => "", 6 => "", 7 => "", 8 => "", 9 => "", 10 => "");
		$provname = "";
		if ($array){
			foreach ($array as $key => $value){
				$fecha = new DateTime(date('Y-m-d H:i:s'));
				$intervalo = new DateInterval('P2D');
				$fecha->add($intervalo);
				$cotizacionesProveedor = $this->ct_mdl->getPedidosAllVolumen(NULL, $fecha->format('Y-m-d H:i:s'), 0);
			
				$difff = 0.01;
				$flag2 = 3;
				$cargo = "";
				if ($cotizacionesProveedor){
					//HOJA EXISTENCIAS
					$this->excelfile->setActiveSheetIndex(0);
					if($i > 0){
						$flagBorder = $flag1 ;
						$this->excelfile->getActiveSheet()->getStyle('A'.$flagBorder1.':G'.$flagBorder)->applyFromArray($styleArray);
						$flagBorder1 = $flag1;
					}
					$hoja1->mergeCells('A'.$flag1.':G'.$flag1);
					$this->cellStyle("A".$flag1, "FFFFFF", "000000", TRUE, 12, "Franklin Gothic Book");
					$hoja1->setCellValue("A".$flag1."", "GRUPO ABARROTES AZTECA");
					$this->excelfile->getActiveSheet()->getStyle('A'.$flag1.':G'.$flag1)->applyFromArray($styleArray);
					$flag1++;
					$hoja1->mergeCells('A'.$flag1.':G'.$flag1);
					$this->cellStyle("A".$flag1, "FFFFFF", "000000", TRUE, 12, "Franklin Gothic Book");
					$hoja1->setCellValue("A".$flag1."", "PEDIDOS A '".$value->nombre."' ".date("d-m-Y"));
					$provname = $value->nombre;
					$this->excelfile->getActiveSheet()->getStyle('A'.$flag1.':G'.$flag1)->applyFromArray($styleArray);
					$flag1++;
					$this->cellStyle("A".$flag1.":D".$flag1, "000000", "FFFFFF", TRUE, 12, "Franklin Gothic Book");
					$hoja1->mergeCells('A'.$flag1.':B'.$flag1);
					$hoja1->setCellValue("A".$flag1, "EXISTENCIAS");
					$hoja1->setCellValue("E".$flag1, "DESCRIPCIÓN");
					$this->cellStyle("E".$flag1, "000000", "FFFFFF", TRUE, 12, "Franklin Gothic Book");

					$this->cellStyle("H".$flag1."", "000000", "FFFFFF", TRUE, 12, "Franklin Gothic Book");
					$hoja1->setCellValue("H".$flag1."", "PENDIENT");
					$this->cellStyle("I".$flag1."", "000000", "FFFFFF", TRUE, 12, "Franklin Gothic Book");
					$hoja1->setCellValue("I".$flag1."", "PENDIENT");
					$this->cellStyle("J".$flag1."", "000000", "FFFFFF", TRUE, 12, "Franklin Gothic Book");
					$hoja1->setCellValue("J".$flag1."", "PENDIENT");
					$this->cellStyle("K".$flag1."", "000000", "FFFFFF", TRUE, 12, "Franklin Gothic Book");
					$hoja1->setCellValue("K".$flag1."", "PENDIENT");
					$this->cellStyle("L".$flag1."", "000000", "FFFFFF", TRUE, 12, "Franklin Gothic Book");
					$hoja1->setCellValue("L".$flag1."", "PENDIENT");
					$this->cellStyle("M".$flag1."", "000000", "FFFFFF", TRUE, 12, "Franklin Gothic Book");
					$hoja1->setCellValue("M".$flag1."", "PENDIENT");
					$this->cellStyle("N".$flag1."", "000000", "FFFFFF", TRUE, 12, "Franklin Gothic Book");
					$hoja1->setCellValue("N".$flag1."", "PENDIENT");
					$this->cellStyle("O".$flag1."", "000000", "FFFFFF", TRUE, 12, "Franklin Gothic Book");
					$hoja1->setCellValue("O".$flag1."", "PENDIENT");
					$this->cellStyle("P".$flag1."", "000000", "FFFFFF", TRUE, 12, "Franklin Gothic Book");
					$hoja1->setCellValue("P".$flag1."", "PENDIENT");
					$this->excelfile->getActiveSheet()->getStyle('A'.$flag1.':P'.$flag1)->applyFromArray($styleArray);
					$flag1++;
					$this->cellStyle("A".$flag1.":G".$flag1, "000000", "FFFFFF", TRUE, 12, "Franklin Gothic Book");
					$hoja1->setCellValue("A".$flag1, "CAJAS");
					$hoja1->setCellValue("B".$flag1, "PZAS");
					$hoja1->setCellValue("C".$flag1, "PEDIDO");
					$hoja1->setCellValue("D".$flag1, "CÓDIGO");
					$hoja1->setCellValue("G".$flag1, "PROMOCIÓN");
					$hoja1->setCellValue("F".$flag1, "IMAGEN")->getColumnDimension('F')->setWidth(18);
					
					$this->cellStyle("H".$flag1, "C00000", "000000", TRUE, 10, "Franklin Gothic Book");
					$this->cellStyle("I".$flag1, "01B0F0", "000000", TRUE, 10, "Franklin Gothic Book");
					$this->cellStyle("J".$flag1, "FF0000", "000000", TRUE, 10, "Franklin Gothic Book");
					$this->cellStyle("K".$flag1, "E26C0B", "000000", TRUE, 10, "Franklin Gothic Book");
					$this->cellStyle("L".$flag1, "C5C5C5", "000000", TRUE, 10, "Franklin Gothic Book");
					$this->cellStyle("M".$flag1, "92D051", "000000", TRUE, 10, "Franklin Gothic Book");
					$this->cellStyle("N".$flag1, "B1A0C7", "000000", TRUE, 10, "Franklin Gothic Book");
					$this->cellStyle("O".$flag1, "DA9694", "000000", TRUE, 10, "Franklin Gothic Book");
					$this->cellStyle("P".$flag1, "4CACC6", "000000", TRUE, 10, "Franklin Gothic Book");
					$hoja1->setCellValue("H".$flag1."", "CEDIS");
					$hoja1->setCellValue("I".$flag1."", "ABARROTES");
					$hoja1->setCellValue("J".$flag1."", "VILLAS");
					$hoja1->setCellValue("K".$flag1."", "TIENDA");
					$hoja1->setCellValue("L".$flag1."", "ULTRA");
					$hoja1->setCellValue("M".$flag1."", "TRINCHERAS");
					$hoja1->setCellValue("N".$flag1."", "MERCADO");
					$hoja1->setCellValue("O".$flag1."", "TENENCIA");
					$hoja1->setCellValue("P".$flag1."", "TIJERAS");
					$this->excelfile->getActiveSheet()->getStyle('A'.$flag1.':P'.$flag1)->applyFromArray($styleArray);
					//$flag1++;
					$this->excelfile->setActiveSheetIndex(1);
					if($i > 0){
						$flagBorder2 = $flag ;
						$this->excelfile->getActiveSheet()->getStyle('A'.$flagBorder3.':AD'.$flagBorder2)->applyFromArray($styleArray);
						$flagBorder3 = $flag;
					}


					//HOJA PEDIDOS
					$this->cellStyle("A".$flag, "FFFFFF", "000000", TRUE, 12, "Franklin Gothic Book");
					$hoja->setCellValue("A".$flag."", "CEDIS, ABARROTES,PEDREGAL, TIENDA, ULTRAMARINOS, TRINCHERAS, MERCADO, TIJERAS, Y TENENCIA AZTECA AUTOSERVICIOS SA. DE CV.");
					$hoja->mergeCells('A'.$flag.':BM'.$flag);
					$this->excelfile->getActiveSheet()->getStyle('A'.$flag.':BM'.$flag)->applyFromArray($styleArray);
					$flag++;
					$hoja->mergeCells('A'.$flag.':L'.$flag);
					$hoja->mergeCells('M'.$flag.':Q'.$flag);
					$hoja->mergeCells('R'.$flag.':U'.$flag);
					$hoja->mergeCells('V'.$flag.':X'.$flag);
					$hoja->mergeCells('Y'.$flag.':AC'.$flag);
					$hoja->mergeCells('AD'.$flag.':AH'.$flag);
					$hoja->mergeCells('AI'.$flag.':AM'.$flag);
					$hoja->mergeCells('AN'.$flag.':AR'.$flag);
					$hoja->mergeCells('AS'.$flag.':AW'.$flag);
					$hoja->mergeCells('AX'.$flag.':BB'.$flag);
					$hoja->mergeCells('BC'.$flag.':BG'.$flag);
					$hoja->mergeCells('BH'.$flag.':BL'.$flag);

					$this->cellStyle("B".$flag, "FFFFFF", "000000", TRUE, 12, "Franklin Gothic Book");
					$hoja->setCellValue("B".$flag, "PEDIDOS A '".$value->nombre."' ".date("d-m-Y"));
					$this->cellStyle("M".$flag, "C00000", "000000", TRUE, 12, "Franklin Gothic Book");
					$hoja->setCellValue("M".$flag, "CEDIS");
					$hoja->setCellValue("R".$flag, "CD INDUSTRIAL");
					$this->cellStyle("R".$flag, "FF0066", "000000", TRUE, 12, "Franklin Gothic Book");
					$this->cellStyle("V".$flag, "C2B90A", "000000", TRUE, 12, "Franklin Gothic Book");
					$hoja->setCellValue("V".$flag, "SUMA CEDIS");
					$this->cellStyle("Y".$flag, "01B0F0", "000000", TRUE, 12, "Franklin Gothic Book");
					$hoja->setCellValue("Y".$flag, "ABARROTES");
					$this->cellStyle("AD".$flag, "FF0000", "000000", TRUE, 12, "Franklin Gothic Book");
					$hoja->setCellValue("AD".$flag, "VILLAS");
					$this->cellStyle("AI".$flag, "E26C0B", "000000", TRUE, 12, "Franklin Gothic Book");
					$hoja->setCellValue("AI".$flag, "TIENDA");
					$this->cellStyle("AN".$flag, "C5C5C5", "000000", TRUE, 12, "Franklin Gothic Book");
					$hoja->setCellValue("AN".$flag, "ULTRAMARINOS");
					$this->cellStyle("AS".$flag, "92D051", "000000", TRUE, 12, "Franklin Gothic Book");
					$hoja->setCellValue("AS".$flag, "TRINCHERAS");
					$this->cellStyle("AX".$flag, "B1A0C7", "000000", TRUE, 12, "Franklin Gothic Book");
					$hoja->setCellValue("AX".$flag, "AZT MERCADO");
					$this->cellStyle("BC".$flag, "DA9694", "000000", TRUE, 12, "Franklin Gothic Book");
					$hoja->setCellValue("BC".$flag, "TENENCIA");
					$this->cellStyle("BH".$flag, "4CACC6", "000000", TRUE, 12, "Franklin Gothic Book");
					$hoja->setCellValue("BH".$flag, "TIJERAS");
					
					$this->cellStyle("A3:BM4", "000000", "FFFFFF", TRUE, 12, "Franklin Gothic Book");
					
					$this->excelfile->getActiveSheet()->getStyle('A'.$flag.':BM'.$flag)->applyFromArray($styleArray);
					$flag++;
					$this->cellStyle("A".$flag.":BM".$flag."", "000000", "FFFFFF", TRUE, 12, "Franklin Gothic Book");
					$hoja->mergeCells('A'.$flag.':L'.$flag);
					$hoja->mergeCells('M'.$flag.':Q'.$flag);
					$hoja->mergeCells('R'.$flag.':U'.$flag);
					$hoja->mergeCells('V'.$flag.':X'.$flag);
					$hoja->mergeCells('Y'.$flag.':AC'.$flag);
					$hoja->mergeCells('AD'.$flag.':AH'.$flag);
					$hoja->mergeCells('AI'.$flag.':AM'.$flag);
					$hoja->mergeCells('AN'.$flag.':AR'.$flag);
					$hoja->mergeCells('AS'.$flag.':AW'.$flag);
					$hoja->mergeCells('AX'.$flag.':BB'.$flag);
					$hoja->mergeCells('BC'.$flag.':BG'.$flag);
					$hoja->mergeCells('BH'.$flag.':BL'.$flag);
					$hoja->setCellValue("A".$flag, "DESCRIPCIÓN");
					$hoja->setCellValue("M".$flag, "EXISTENCIAS");
					$hoja->setCellValue("R".$flag, " SUMA EXISTENCIAS");
					$hoja->setCellValue("V".$flag, "EXISTENCIAS");
					$hoja->setCellValue("Y".$flag, "EXISTENCIAS");
					$hoja->setCellValue("AD".$flag, "EXISTENCIAS");
					$hoja->setCellValue("AI".$flag, "EXISTENCIAS");
					$hoja->setCellValue("AN".$flag, "EXISTENCIAS");
					$hoja->setCellValue("AS".$flag, "EXISTENCIAS");
					$hoja->setCellValue("AX".$flag, "EXISTENCIAS");
					$hoja->setCellValue("BC".$flag, "EXISTENCIAS");
					$hoja->setCellValue("BH".$flag, "EXISTENCIAS");
					$this->excelfile->getActiveSheet()->getStyle('A'.$flag.':BM'.$flag)->applyFromArray($styleArray);
					//Begin: TOTALES PEDIDOS PENDIENTES
					$hoja->mergeCells('CD'.$flag.':CN'.$flag);
					$this->cellStyle("CD".$flag.":CN".$flag."", "000000", "FFFFFF", TRUE, 12, "Franklin Gothic Book");
					$hoja->setCellValue("CD".$flag, "TOTAL POR PEDIDOS PENDIENTES");
					//End: TOTALES PEDIDOS PENDIENTES
					$flag++;
					$this->cellStyle("A".$flag.":BM".$flag."", "000000", "FFFFFF", TRUE, 12, "Franklin Gothic Book");
					$hoja->setCellValue("A".$flag, "CODIGO");
					$hoja->setCellValue("C".$flag, "REAL")
					;
					$hoja->setCellValue("D".$flag, "UM");
					$hoja->setCellValue("E".$flag, "1ER");
					$hoja->setCellValue("F".$flag, "COSTO");
					$hoja->setCellValue("G".$flag, "DIF % 5 Y 1ER");
					$hoja->setCellValue("H".$flag, "SISTEMA");
					$hoja->setCellValue("I".$flag, "DIF % 5 Y 4");
					$hoja->setCellValue("J".$flag, "PRECIO4");
					$hoja->setCellValue("K".$flag, "2DO");
					$hoja->setCellValue("L".$flag, "PROVEEDOR");

					$hoja->setCellValue("M".$flag, "CAJAS");
					$hoja->setCellValue("N".$flag, "PZAS");
					$hoja->setCellValue("O".$flag, "PEND");
					$hoja->setCellValue("P".$flag, "STOCK");
					$hoja->setCellValue("Q".$flag, "PEDIDO");
					$hoja->setCellValue("R".$flag, "CAJAS");
					$hoja->setCellValue("S".$flag, "PZAS");
					$hoja->setCellValue("T".$flag, "STOCK");
					$hoja->setCellValue("U".$flag, "PEDIDO");
					$hoja->setCellValue("V".$flag, "CAJAS");
					$hoja->setCellValue("W".$flag, "PZAS");
					$hoja->setCellValue("X".$flag, "PEDIDO");
					$hoja->setCellValue("Y".$flag, "CAJAS");
					$hoja->setCellValue("Z".$flag, "PZAS");
					$hoja->setCellValue("AA".$flag, "PEND");
					$hoja->setCellValue("AB".$flag, "STOCK");
					$hoja->setCellValue("AC".$flag, "PEDIDO");
					$hoja->setCellValue("AD".$flag, "CAJAS");
					$hoja->setCellValue("AE".$flag, "PZAS");
					$hoja->setCellValue("AF".$flag, "PEND");
					$hoja->setCellValue("AG".$flag, "STOCK");
					$hoja->setCellValue("AH".$flag, "PEDIDO");
					$hoja->setCellValue("AI".$flag, "CAJAS");
					$hoja->setCellValue("AJ".$flag, "PZAS");
					$hoja->setCellValue("AK".$flag, "PEND");
					$hoja->setCellValue("AL".$flag, "STOCK");
					$hoja->setCellValue("AM".$flag, "PEDIDO");
					$hoja->setCellValue("AN".$flag, "CAJAS");
					$hoja->setCellValue("AO".$flag, "PZAS");
					$hoja->setCellValue("AP".$flag, "PEND");
					$hoja->setCellValue("AQ".$flag, "STOCK");
					$hoja->setCellValue("AR".$flag, "PEDIDO");
					$hoja->setCellValue("AS".$flag, "CAJAS");
					$hoja->setCellValue("AT".$flag, "PZAS");
					$hoja->setCellValue("AU".$flag, "PEND");
					$hoja->setCellValue("AV".$flag, "STOCK");
					$hoja->setCellValue("AW".$flag, "PEDIDO");
					$hoja->setCellValue("AX".$flag, "CAJAS");
					$hoja->setCellValue("AY".$flag, "PZAS");
					$hoja->setCellValue("AZ".$flag, "PEND");
					$hoja->setCellValue("BA".$flag, "STOCK");
					$hoja->setCellValue("BB".$flag, "PEDIDO");
					$hoja->setCellValue("BC".$flag, "CAJAS");
					$hoja->setCellValue("BD".$flag, "PZAS");
					$hoja->setCellValue("BE".$flag, "PEND");
					$hoja->setCellValue("BF".$flag, "STOCK");
					$hoja->setCellValue("BG".$flag, "PEDIDO");
					$hoja->setCellValue("BH".$flag, "CAJAS");
					$hoja->setCellValue("BI".$flag, "PZAS");
					$hoja->setCellValue("BJ".$flag, "PEND");
					$hoja->setCellValue("BK".$flag, "STOCK");
					$hoja->setCellValue("BL".$flag, "PEDIDO");
					$hoja->setCellValue("BM".$flag, "PROMOCIÓN");					
					
					
					$this->cellStyle("BN".$flag1, "C00000", "000000", TRUE, 10, "Franklin Gothic Book");
					$this->cellStyle("BO".$flag1, "01B0F0", "000000", TRUE, 10, "Franklin Gothic Book");
					$this->cellStyle("BP".$flag1, "FF0000", "000000", TRUE, 10, "Franklin Gothic Book");
					$this->cellStyle("BQ".$flag1, "E26C0B", "000000", TRUE, 10, "Franklin Gothic Book");
					$this->cellStyle("BR".$flag1, "C5C5C5", "000000", TRUE, 10, "Franklin Gothic Book");
					$this->cellStyle("BS".$flag1, "92D051", "000000", TRUE, 10, "Franklin Gothic Book");
					$this->cellStyle("BT".$flag1, "B1A0C7", "000000", TRUE, 10, "Franklin Gothic Book");
					$this->cellStyle("BU".$flag1, "DA9694", "000000", TRUE, 10, "Franklin Gothic Book");
					$this->cellStyle("BV".$flag1, "4CACC6", "000000", TRUE, 10, "Franklin Gothic Book");

					$hoja->setCellValue("BW".$flag, "TOTAL");
					$hoja->setCellValue("BX".$flag, "PEDIDOS");
					$this->cellStyle("BW".$flag."", "000000", "FFFFFF", TRUE, 12, "Franklin Gothic Book");
					$this->cellStyle("BX".$flag."", "000000", "FFFFFF", TRUE, 12, "Franklin Gothic Book");
					$this->excelfile->getActiveSheet()->getStyle('BW'.$flag)->applyFromArray($styleArray);
					$this->excelfile->getActiveSheet()->getStyle('BX'.$flag)->applyFromArray($styleArray);
					$this->excelfile->getActiveSheet()->getStyle('A'.$flag.':BM'.$flag)->applyFromArray($styleArray);

					//Begin: TOTALES PEDIDOS PENDIENTES
					$this->cellStyle("CD".$flag, "C00000", "000000", TRUE, 12, "Franklin Gothic Book");
					$this->cellStyle("CE".$flag, "01B0F0", "000000", TRUE, 12, "Franklin Gothic Book");
					$this->cellStyle("CF".$flag, "FF0000", "000000", TRUE, 12, "Franklin Gothic Book");
					$this->cellStyle("CG".$flag, "E26C0B", "000000", TRUE, 12, "Franklin Gothic Book");
					$this->cellStyle("CH".$flag, "C5C5C5", "000000", TRUE, 12, "Franklin Gothic Book");
					$this->cellStyle("CI".$flag, "92D051", "000000", TRUE, 12, "Franklin Gothic Book");
					$this->cellStyle("CJ".$flag, "B1A0C7", "000000", TRUE, 12, "Franklin Gothic Book");
					$this->cellStyle("CK".$flag, "DA9694", "000000", TRUE, 12, "Franklin Gothic Book");
					$this->cellStyle("CL".$flag, "4CACC6", "000000", TRUE, 12, "Franklin Gothic Book");
					$this->cellStyle("CM".$flag."", "000000", "FFFFFF", TRUE, 12, "Franklin Gothic Book");
					$this->cellStyle("CN".$flag."", "000000", "FFFFFF", TRUE, 12, "Franklin Gothic Book");
					$hoja->setCellValue("CM".$flag, "TOTAL");
					$hoja->setCellValue("CN".$flag, "PEDIDOS");
					//End: TOTALES PEDIDOS PENDIENTES
					foreach ($cotizacionesProveedor as $key => $value){
						//Existencias
						$this->excelfile->setActiveSheetIndex(0);
						$this->cellStyle("E".$flag1, "000000", "FFFFFF", FALSE, 12, "Franklin Gothic Book");
						$hoja1->setCellValue("E".$flag1, $value['familia']);
						$flag1 +=1;
						//Pedidos
						$this->excelfile->setActiveSheetIndex(1);
						$this->cellStyle("B".$flag, "000000", "FFFFFF", FALSE, 12, "Franklin Gothic Book");
						$hoja->setCellValue("B{$flag}", $value['familia']);
						$flag +=1;
						if ($value['articulos']) {
							foreach ($value['articulos'] as $key => $row){
								//Existencias
								$cargo = $row['cargo'];
								$registrazo = date('Y-m-d',strtotime($row['registrazo']));
								$this->excelfile->setActiveSheetIndex(0);
								$this->cellStyle("A".$flag1.":P".$flag1, "FFFFFF", "000000", FALSE, 12, "Franklin Gothic Book");
								
								$hoja1->setCellValue("D{$flag1}", $row['codigo'])->getStyle("D{$flag1}")->getNumberFormat()->setFormatCode('# ???/???');//Formato de fraccion
								if($row['color'] == '#92CEE3'){
									$this->cellStyle("D{$flag1}", "92CEE3", "000000", FALSE, 12, "Franklin Gothic Book");
								}else{
									$this->cellStyle("D{$flag1}", "FFFFFF", "000000", FALSE, 12, "Franklin Gothic Book");
								}
								$hoja1->setCellValue("E{$flag1}", $row['producto']);
								$hoja1->setCellValue("G{$flag1}", $row['promocion_first']);
								$hoja1->setCellValue("H{$flag1}", $row['cedis']);
								$hoja1->setCellValue("I{$flag1}", $row['abarrotes']);
								$hoja1->setCellValue("J{$flag1}", $row['pedregal']);
								$hoja1->setCellValue("K{$flag1}", $row['tienda']);
								$hoja1->setCellValue("L{$flag1}", $row['ultra']);
								$hoja1->setCellValue("M{$flag1}", $row['trincheras']);
								$hoja1->setCellValue("N{$flag1}", $row['mercado']);
								$hoja1->setCellValue("O{$flag1}", $row['tenencia']);
								$hoja1->setCellValue("P{$flag1}", $row['tijeras']);

								if ($row["imagen"] <> "" && !is_null($row["imagen"]) ) {
									$objDrawing = new PHPExcel_Worksheet_Drawing();
									$objDrawing->setName('COD'.$row['producto']);
									$objDrawing->setDescription('DESC'.$row['codigo']);
									$objDrawing->setPath("./Abarrotes/assets/img/productos/".$row["imagen"]."");
									$objDrawing->setWidth(50);
									$objDrawing->setHeight(50);
									$objDrawing->setCoordinates('F'.$flag1);
									$objDrawing->setOffsetX(5); 
									$objDrawing->setOffsetY(5);
									//$objDrawing->setUrl('http://abarrotesazteca.com/Abarrotes/assets/img/productos/'.$row["imagen"]);
									$objDrawing->setWorksheet($this->excelfile->getActiveSheet());
									$this->excelfile->getActiveSheet()->getRowDimension($flag1)->setRowHeight(60);
									$this->excelfile->getActiveSheet()->getStyleByColumnAndRow(10, $flag1)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_DATE_XLSX14);
									$this->excelfile->getActiveSheet()->getCell('F'.$flag1)->getHyperlink()->setUrl('http://abarrotesazteca.com/Abarrotes/assets/img/productos/'.str_replace("_thumb.",".",$row["imagen"]));
								}

								$hoja1->getStyle("A{$flag1}:P{$flag1}")
						                 ->getAlignment()
						                 ->setHorizontal(\PHPExcel_Style_Alignment::HORIZONTAL_LEFT);
						         
				                 
								//Pedidos
								$this->excelfile->setActiveSheetIndex(1);
								$this->cellStyle("A".$flag.":BX".$flag."", "FFFFFF", "000000", FALSE, 12, "Franklin Gothic Book");
								
								$hoja->setCellValue("A{$flag}", $row['codigo'])->getStyle("A{$flag}")->getNumberFormat()->setFormatCode('# ???/???');//Formato de fraccion
								if($row['color'] == '#92CEE3'){
									$this->cellStyle("A{$flag}", "92CEE3", "000000", FALSE, 12, "Franklin Gothic Book");
								}else{
									$this->cellStyle("A{$flag}", "FFFFFF", "000000", FALSE, 12, "Franklin Gothic Book");
								}
								$hoja->setCellValue("B{$flag}", $row['producto']);
								$hoja->setCellValue("F{$flag}", $row['proveedor_first']);
								$hoja->setCellValue("C{$flag}", $row['reales'])->getStyle("C{$flag}")->getNumberFormat()->setFormatCode('"$"#,##0.00_-');

								if(number_format(($row['precio_first'] - $row['reales']),2) === "0.01" || number_format(($row['precio_first'] - $row['reales']),2) === "-0.01"){
									$this->cellStyle("C{$flag}", "FFFFFF", "000000", FALSE, 12, "Franklin Gothic Book");
								}elseif($row['precio_first'] < $row['reales']){
									$this->cellStyle("C{$flag}", "FFFFFF", "E21600", FALSE, 12, "Franklin Gothic Book");
								}else{
									$this->cellStyle("C{$flag}", "FFFFFF", "249947", FALSE, 12, "Franklin Gothic Book");
								}

								if($row['precio_sistema'] < $row['precio_first']){
									$hoja->setCellValue("E{$flag}", $row['precio_first'])->getStyle("E{$flag}")->getNumberFormat()->setFormatCode('"$"#,##0.00_-');
									$this->cellStyle("E{$flag}", "FDB2B2", "E21111", FALSE, 12, "Franklin Gothic Book");
									$this->cellStyle("B{$flag}", "E21600", "000000", FALSE, 12, "Franklin Gothic Book");
								}else{
									$hoja->setCellValue("E{$flag}", $row['precio_first'])->getStyle("E{$flag}")->getNumberFormat()->setFormatCode('"$"#,##0.00_-');
									$this->cellStyle("E{$flag}", "96EAA8", "0C800C", FALSE, 12, "Franklin Gothic Book");
									$this->cellStyle("B{$flag}", "249947", "000000", FALSE, 12, "Franklin Gothic Book");
								}
								$hoja->setCellValue("H{$flag}", $row['precio_sistema'])->getStyle("H{$flag}")->getNumberFormat()->setFormatCode('"$"#,##0.00_-');//Formto de moneda
								$this->cellStyle("H".$flag, "FFFFFF","000000",  FALSE, 12, "Franklin Gothic Book");
								if($row['colorp'] == 1){
									$this->cellStyle("H{$flag}", "D6DCE4", "000000", FALSE, 12, "Franklin Gothic Book");
								}else{
									$this->cellStyle("H{$flag}", "FFFFFF", "000000", FALSE, 12, "Franklin Gothic Book");
								}
								$hoja->setCellValue("J{$flag}", $row['precio_four'])->getStyle("J{$flag}")->getNumberFormat()->setFormatCode('"$"#,##0.00_-');
								$this->cellStyle("J{$flag}", "FFFFFF", "000000", FALSE, 12, "Franklin Gothic Book");
								if($row['precio_sistema'] < $row['precio_next']){
									$hoja->setCellValue("K{$flag}", $row['precio_next'])->getStyle("K{$flag}")->getNumberFormat()->setFormatCode('"$"#,##0.00_-');
									$this->cellStyle("K{$flag}", "FDB2B2", "E21111", FALSE, 12, "Franklin Gothic Book");
								}else if($row['precio_next'] !== NULL){
									$hoja->setCellValue("K{$flag}", $row['precio_next'])->getStyle("K{$flag}")->getNumberFormat()->setFormatCode('"$"#,##0.00_-');
									$this->cellStyle("K{$flag}", "96EAA8", "0C800C", FALSE, 12, "Franklin Gothic Book");
								}else{
									$hoja->setCellValue("K{$flag}", $row['precio_next'])->getStyle("K{$flag}")->getNumberFormat()->setFormatCode('"$"#,##0.00_-');
									$this->cellStyle("K{$flag}", "FFFFFF", "000000", FALSE, 12, "Franklin Gothic Book");
								}
								$this->cellStyle("M".$flag.":BB".$flag, "FFFFFF", "000000", TRUE, 12, "Franklin Gothic Book");
								$hoja->setCellValue("L{$flag}", $row['proveedor_next']);
								$this->cellStyle("L".$flag, "FFFFFF", "000000", FALSE, 12, "Franklin Gothic Book");

								
								
								$hoja->setCellValue("O{$flag}", $row['cedis']);
								$hoja->setCellValue("AA{$flag}", $row['abarrotes']);
								$hoja->setCellValue("AF{$flag}", $row['pedregal']);
								$hoja->setCellValue("AK{$flag}", $row['tienda']);
								$hoja->setCellValue("AP{$flag}", $row['ultra']);
								$hoja->setCellValue("AU{$flag}", $row['trincheras']);
								$hoja->setCellValue("AZ{$flag}", $row['mercado']);
								$hoja->setCellValue("BE{$flag}", $row['tenencia']);
								$hoja->setCellValue("BJ{$flag}", $row['tijeras']);

								if ($row["lastfecha"] === NULL || $row["lastfecha"] === "") {
									$row["lastfecha"] = "NO PEDIDOS";
								}
								if ($row["unidad"] === NULL || $row["unidad"] === "") {
									$row["unidad"] = 1;
								}
								$hoja->setCellValue("D{$flag}",$row["unidad"]);
								$hoja->setCellValue("M{$flag}", $row['caja0']);
								$hoja->setCellValue("N{$flag}", $row['pz0']);
								$hoja->setCellValue("P{$flag}", $row["past"]["caja0"]);
								$hoja->setCellValue("Q{$flag}", $row['ped0']);
								$this->cellStyle("Q{$flag}", "D4EAEF", "000000", TRUE, 12, "Franklin Gothic Book");

								

								$hoja->setCellValue("R{$flag}", $row['caja9']);
								$hoja->setCellValue("S{$flag}", $row['pz9']);
								$hoja->setCellValue("T{$flag}", $row["past"]["caja9"]);
								$hoja->setCellValue("U{$flag}", $row['ped9']);
								$this->cellStyle("U{$flag}", "D4EAEF", "000000", TRUE, 12, "Franklin Gothic Book");

								$hoja->setCellValue("V{$flag}", "=M".$flag."+R".$flag);
								$hoja->setCellValue("W{$flag}", "=N".$flag."+S".$flag);
								$hoja->setCellValue("X{$flag}", "=Q".$flag."+U".$flag);
								$this->cellStyle("X{$flag}", "D4EAEF", "000000", TRUE, 12, "Franklin Gothic Book");

								$hoja->setCellValue("Y{$flag}", $row['caja1']);
								$hoja->setCellValue("Z{$flag}", $row['pz1']);
								$hoja->setCellValue("AB{$flag}", $row["past"]["caja1"]);
								$hoja->setCellValue("AC{$flag}", $row['ped1']);
								$this->cellStyle("AC{$flag}", "D4EAEF", "000000", TRUE, 12, "Franklin Gothic Book");

								
								$hoja->setCellValue("AD{$flag}", $row['caja2']);
								$hoja->setCellValue("AE{$flag}", $row['pz2']);
								$hoja->setCellValue("AG{$flag}", $row["past"]["caja2"]);
								$hoja->setCellValue("AH{$flag}", $row['ped2']);
								$this->cellStyle("AH{$flag}", "D4EAEF", "000000", TRUE, 12, "Franklin Gothic Book");

								
								$hoja->setCellValue("AI{$flag}", $row['caja3']);
								$hoja->setCellValue("AJ{$flag}", $row['pz3']);
								$hoja->setCellValue("AL{$flag}", $row["past"]["caja3"]);
								$hoja->setCellValue("AM{$flag}", $row['ped3']);
								$this->cellStyle("AM{$flag}", "D4EAEF", "000000", TRUE, 12, "Franklin Gothic Book");

								
								$hoja->setCellValue("AN{$flag}", $row['caja4']);
								$hoja->setCellValue("AO{$flag}", $row['pz4']);
								$hoja->setCellValue("AQ{$flag}", $row["past"]["caja4"]);
								$hoja->setCellValue("AR{$flag}", $row['ped4']);
								$this->cellStyle("AR{$flag}", "D4EAEF", "000000", TRUE, 12, "Franklin Gothic Book");

								$hoja->setCellValue("AS{$flag}", $row['caja5']);
								$hoja->setCellValue("AT{$flag}", $row['pz5']);
								$hoja->setCellValue("AV{$flag}", $row["past"]["caja5"]);
								$hoja->setCellValue("AW{$flag}", $row['ped5']);
								$this->cellStyle("AW{$flag}", "D4EAEF", "000000", TRUE, 12, "Franklin Gothic Book");

								
								$hoja->setCellValue("AX{$flag}", $row['caja6']);
								$hoja->setCellValue("AY{$flag}", $row['pz6']);
								$hoja->setCellValue("BA{$flag}", $row["past"]["caja6"]);
								$hoja->setCellValue("BB{$flag}", $row['ped6']);
								$this->cellStyle("BB{$flag}", "D4EAEF", "000000", TRUE, 12, "Franklin Gothic Book");

								
								$hoja->setCellValue("BC{$flag}", $row['caja7']);
								$hoja->setCellValue("BD{$flag}", $row['pz7']);
								$hoja->setCellValue("BF{$flag}", $row["past"]["caja7"]);
								$hoja->setCellValue("BG{$flag}", $row['ped7']);
								$this->cellStyle("BG{$flag}", "D4EAEF", "000000", TRUE, 12, "Franklin Gothic Book");

								
								$hoja->setCellValue("BH{$flag}", $row['caja8']);
								$hoja->setCellValue("BI{$flag}", $row['pz8']);
								$hoja->setCellValue("BK{$flag}", $row["past"]["caja8"]);
								$hoja->setCellValue("BL{$flag}", $row['ped8']);
								$this->cellStyle("BL{$flag}", "D4EAEF", "000000", TRUE, 12, "Franklin Gothic Book");

								$this->cellStyle("BM{$flag}", "FFFFFF", "000000", TRUE, 12, "Franklin Gothic Book");
								$hoja->setCellValue("BM{$flag}", $row['promocion_first']);
								$hoja->setCellValue("BN{$flag}", "=E".$flag."*Q".$flag)->getStyle("BN{$flag}")->getNumberFormat()->setFormatCode('"$"#,##0.00_-');
								$hoja->setCellValue("BO{$flag}", "=E".$flag."*AC".$flag)->getStyle("BO{$flag}")->getNumberFormat()->setFormatCode('"$"#,##0.00_-');
								$hoja->setCellValue("BP{$flag}", "=E".$flag."*AH".$flag)->getStyle("BP{$flag}")->getNumberFormat()->setFormatCode('"$"#,##0.00_-');
								$hoja->setCellValue("BQ{$flag}", "=E".$flag."*AM".$flag)->getStyle("BQ{$flag}")->getNumberFormat()->setFormatCode('"$"#,##0.00_-');
								$hoja->setCellValue("BR{$flag}", "=E".$flag."*AR".$flag)->getStyle("BR{$flag}")->getNumberFormat()->setFormatCode('"$"#,##0.00_-');
								$hoja->setCellValue("BS{$flag}", "=E".$flag."*AW".$flag)->getStyle("BS{$flag}")->getNumberFormat()->setFormatCode('"$"#,##0.00_-');
								$hoja->setCellValue("BT{$flag}", "=E".$flag."*BB".$flag)->getStyle("BT{$flag}")->getNumberFormat()->setFormatCode('"$"#,##0.00_-');
								$hoja->setCellValue("BU{$flag}", "=E".$flag."*BG".$flag)->getStyle("BU{$flag}")->getNumberFormat()->setFormatCode('"$"#,##0.00_-');
								$hoja->setCellValue("BV{$flag}", "=E".$flag."*BL".$flag)->getStyle("BV{$flag}")->getNumberFormat()->setFormatCode('"$"#,##0.00_-');
								$this->cellStyle("BW{$flag}", "D4EAEF", "000000", FALSE, 12, "Franklin Gothic Book");
								$hoja->setCellValue("BW{$flag}", "=SUM(BM".$flag.":BV".$flag.")")->getStyle("BW{$flag}")->getNumberFormat()->setFormatCode('"$"#,##0.00_-');
								$this->cellStyle("BX{$flag}", "C2B90A", "000000", FALSE, 12, "Franklin Gothic Book");
								$hoja->setCellValue("BX{$flag}", "=O".$flag."+AC".$flag."+AH".$flag."+AM".$flag."+AR".$flag."+AW".$flag."+BB".$flag."+BG".$flag."+BL".$flag."");

								//Begin: TOTALES PEDIDOS PENDIENTES
								$hoja->setCellValue("CD{$flag}", "=E".$flag."*O".$flag)->getStyle("CD{$flag}")->getNumberFormat()->setFormatCode('"$"#,##0.00_-');
								$hoja->setCellValue("CE{$flag}", "=E".$flag."*AA".$flag)->getStyle("CE{$flag}")->getNumberFormat()->setFormatCode('"$"#,##0.00_-');
								$hoja->setCellValue("CF{$flag}", "=E".$flag."*AF".$flag)->getStyle("CF{$flag}")->getNumberFormat()->setFormatCode('"$"#,##0.00_-');
								$hoja->setCellValue("CG{$flag}", "=E".$flag."*AK".$flag)->getStyle("CG{$flag}")->getNumberFormat()->setFormatCode('"$"#,##0.00_-');
								$hoja->setCellValue("CH{$flag}", "=E".$flag."*AP".$flag)->getStyle("CH{$flag}")->getNumberFormat()->setFormatCode('"$"#,##0.00_-');
								$hoja->setCellValue("CI{$flag}", "=E".$flag."*AU".$flag)->getStyle("CI{$flag}")->getNumberFormat()->setFormatCode('"$"#,##0.00_-');
								$hoja->setCellValue("CJ{$flag}", "=E".$flag."*AZ".$flag)->getStyle("CJ{$flag}")->getNumberFormat()->setFormatCode('"$"#,##0.00_-');
								$hoja->setCellValue("CK{$flag}", "=E".$flag."*BE".$flag)->getStyle("CK{$flag}")->getNumberFormat()->setFormatCode('"$"#,##0.00_-');
								$hoja->setCellValue("CL{$flag}", "=E".$flag."*BJ".$flag)->getStyle("CL{$flag}")->getNumberFormat()->setFormatCode('"$"#,##0.00_-');
								$this->cellStyle("CM{$flag}", "000000", "FFFFFF", FALSE, 12, "Franklin Gothic Book");
								$hoja->setCellValue("CM{$flag}", "=SUM(CD".$flag.":CL".$flag.")")->getStyle("CM{$flag}")->getNumberFormat()->setFormatCode('"$"#,##0.00_-');
								$this->cellStyle("CN{$flag}", "000000", "FFFFFF", FALSE, 12, "Franklin Gothic Book");
								$hoja->setCellValue("CN{$flag}", "=O".$flag."+AA".$flag."+AF".$flag."+AK".$flag."+AP".$flag."+AU".$flag."+AZ".$flag."+BE".$flag."+BJ".$flag."");
								//End: TOTALES PEDIDOS PENDIENTES
								$border_style= array('borders' => array('right' => array('style' =>
									PHPExcel_Style_Border::BORDER_THIN,'color' => array('argb' => '000000'),)));
								$this->excelfile->setActiveSheetIndex(1);
								
								$this->excelfile->getActiveSheet()->getStyle('A'.$flag.':BX'.$flag)->applyFromArray($styleArray);
								$this->excelfile->getActiveSheet()->getStyle('BW'.$flag.':BX'.$flag)->applyFromArray($styleArray);
								if($row['precio_sistema'] == 0){
									$row['precio_sistema'] = 1;
								}
								if($row['precio_four'] == 0){
									$row['precio_four'] = 1;
								}
									$hoja->setCellValue("G{$flag}",100 - ($row['precio_first'] * 100 / $row['precio_sistema']))->getStyle("G{$flag}")->getNumberFormat()->setFormatCode('"%"#,##0.00_-');;
									$this->cellStyle("G".$flag, "FF9999", "000000", FALSE, 10, "Franklin Gothic Book");

									$hoja->setCellValue("I{$flag}", 100 - ($row['precio_sistema'] * 100 / $row['precio_four']))->getStyle("I{$flag}")->getNumberFormat()->setFormatCode('"%"#,##0.00_-');;
									$this->cellStyle("I".$flag, "FF9999", "000000", FALSE, 10, "Franklin Gothic Book");
								
								$this->excelfile->setActiveSheetIndex(0);
								$this->excelfile->getActiveSheet()->getStyle('A'.$flag1.':P'.$flag1)->applyFromArray($styleArray);
								$hoja->getStyle("A{$flag}:I{$flag}")
						                 ->getAlignment()
						                 ->setHorizontal(\PHPExcel_Style_Alignment::HORIZONTAL_LEFT);

						   
								$flag ++;
								$flag1 ++;
							}
						}
					}
					$flagf = $flag;
					$flagfs = $flag - 1;
					$hoja->setCellValue("BN{$flagf}", "=SUM(BN5:BN".$flagfs.")")->getStyle("BN{$flag}")->getNumberFormat()->setFormatCode('"$"#,##0.00_-');
					$hoja->setCellValue("BO{$flagf}", "=SUM(BO5:BO".$flagfs.")")->getStyle("BO{$flag}")->getNumberFormat()->setFormatCode('"$"#,##0.00_-');
					$hoja->setCellValue("BP{$flagf}", "=SUM(BP5:BP".$flagfs.")")->getStyle("BP{$flag}")->getNumberFormat()->setFormatCode('"$"#,##0.00_-');
					$hoja->setCellValue("BQ{$flagf}", "=SUM(BQ5:BQ".$flagfs.")")->getStyle("BQ{$flag}")->getNumberFormat()->setFormatCode('"$"#,##0.00_-');
					$hoja->setCellValue("BR{$flagf}", "=SUM(BR5:BR".$flagfs.")")->getStyle("BR{$flag}")->getNumberFormat()->setFormatCode('"$"#,##0.00_-');
					$hoja->setCellValue("BS{$flagf}", "=SUM(BS5:BS".$flagfs.")")->getStyle("BS{$flag}")->getNumberFormat()->setFormatCode('"$"#,##0.00_-');
					$hoja->setCellValue("BT{$flagf}", "=SUM(BT5:BT".$flagfs.")")->getStyle("BT{$flag}")->getNumberFormat()->setFormatCode('"$"#,##0.00_-');
					$hoja->setCellValue("BU{$flagf}", "=SUM(BU5:BU".$flagfs.")")->getStyle("BU{$flag}")->getNumberFormat()->setFormatCode('"$"#,##0.00_-');
					$hoja->setCellValue("BV{$flagf}", "=SUM(BV5:BV".$flagfs.")")->getStyle("BV{$flag}")->getNumberFormat()->setFormatCode('"$"#,##0.00_-');
					$this->cellStyle("BW{$flagf}", "000000", "FFFFFF", FALSE, 12, "Franklin Gothic Book");
					$hoja->setCellValue("BW{$flagf}", "=SUM(BW5:BW".$flagfs.")")->getStyle("BW{$flagf}")->getNumberFormat()->setFormatCode('"$"#,##0.00_-');
					$sumall[1] .= "BN".$flagf."+";
					$sumall[2] .= "BO".$flagf."+";
					$sumall[3] .= "BP".$flagf."+";
					$sumall[4] .= "BQ".$flagf."+";
					$sumall[5] .= "BR".$flagf."+";
					$sumall[6] .= "BS".$flagf."+";
					$sumall[7] .= "BT".$flagf."+";
					$sumall[8] .= "BU".$flagf."+";
					$sumall[9] .= "BV".$flagf."+";
					$sumall[10] .= "BW".$flagf."+";
					//Begin: TOTALES PEDIDOS PENDIENTES
					$hoja->setCellValue("CD{$flag}", "=SUM(CD".$flage.":CD".$flagfs.")")->getStyle("CD{$flag}")->getNumberFormat()->setFormatCode('"$"#,##0.00_-');
					$hoja->setCellValue("CE{$flag}", "=SUM(CE".$flage.":CE".$flagfs.")")->getStyle("CE{$flag}")->getNumberFormat()->setFormatCode('"$"#,##0.00_-');
					$hoja->setCellValue("CF{$flag}", "=SUM(CF".$flage.":CF".$flagfs.")")->getStyle("CF{$flag}")->getNumberFormat()->setFormatCode('"$"#,##0.00_-');
					$hoja->setCellValue("CG{$flag}", "=SUM(CG".$flage.":CG".$flagfs.")")->getStyle("CG{$flag}")->getNumberFormat()->setFormatCode('"$"#,##0.00_-');
					$hoja->setCellValue("CH{$flag}", "=SUM(CH".$flage.":CH".$flagfs.")")->getStyle("CH{$flag}")->getNumberFormat()->setFormatCode('"$"#,##0.00_-');
					$hoja->setCellValue("CI{$flag}", "=SUM(CI".$flage.":CI".$flagfs.")")->getStyle("CI{$flag}")->getNumberFormat()->setFormatCode('"$"#,##0.00_-');
					$hoja->setCellValue("CJ{$flag}", "=SUM(CJ".$flage.":CJ".$flagfs.")")->getStyle("CJ{$flag}")->getNumberFormat()->setFormatCode('"$"#,##0.00_-');
					$hoja->setCellValue("CK{$flag}", "=SUM(CK".$flage.":CK".$flagfs.")")->getStyle("CK{$flag}")->getNumberFormat()->setFormatCode('"$"#,##0.00_-');
					$hoja->setCellValue("CL{$flag}", "=SUM(CL".$flage.":CL".$flagfs.")")->getStyle("CL{$flag}")->getNumberFormat()->setFormatCode('"$"#,##0.00_-');
					$hoja->setCellValue("CM{$flag}", "=SUM(CM".$flage.":CM".$flagfs.")")->getStyle("CM{$flag}")->getNumberFormat()->setFormatCode('"$"#,##0.00_-');
					//End: TOTALES PEDIDOS PENDIENTES
					
					$flag++;
					$flag1++;
					$flag++;
					$flag1++;
					$flag1++;
					$flag1++;
					$flag++;
					$flag++;
				}
			}
		}
		$flag++;
		$bandera = $flag;
		
		$hoja->mergeCells('G'.$flag.':J'.$flag);
		$this->cellStyle("G".$flag, "000000", "FFFFFF", TRUE, 12, "Franklin Gothic Book");
		$hoja->setCellValue("G".$flag, "TOTALES PENDIENTES '".$provname."'");
		$flag++;
		$hoja->mergeCells('G'.$flag.':H'.$flag);
		$hoja->mergeCells('I'.$flag.':J'.$flag);
		$this->cellStyle("G".$flag, "000000", "FFFFFF", TRUE, 12, "Franklin Gothic Book");
		$hoja->setCellValue("G".$flag, "CEDIS");
		$hoja->setCellValue("I{$flag}", "=CD{$flagf}")->getStyle("I{$flag}")->getNumberFormat()->setFormatCode('"$"#,##0.00_-');
		$flag++;
		$hoja->mergeCells('G'.$flag.':H'.$flag);
		$hoja->mergeCells('I'.$flag.':J'.$flag);
		$this->cellStyle("G".$flag, "000000", "FFFFFF", TRUE, 12, "Franklin Gothic Book");
		$hoja->setCellValue("G".$flag, "ABARROTES");
		$hoja->setCellValue("I{$flag}", "=CE{$flagf}")->getStyle("I{$flag}")->getNumberFormat()->setFormatCode('"$"#,##0.00_-');
		$flag++;
		$hoja->mergeCells('G'.$flag.':H'.$flag);
		$hoja->mergeCells('I'.$flag.':J'.$flag);
		$this->cellStyle("G".$flag, "000000", "FFFFFF", TRUE, 12, "Franklin Gothic Book");
		$hoja->setCellValue("G".$flag, "VILLAS");
		$hoja->setCellValue("I{$flag}", "=CF{$flagf}")->getStyle("I{$flag}")->getNumberFormat()->setFormatCode('"$"#,##0.00_-');
		$flag++;
		$hoja->mergeCells('G'.$flag.':H'.$flag);
		$hoja->mergeCells('I'.$flag.':J'.$flag);
		$this->cellStyle("G".$flag, "000000", "FFFFFF", TRUE, 12, "Franklin Gothic Book");
		$hoja->setCellValue("G".$flag, "TIENDA");
		$hoja->setCellValue("I{$flag}", "=CG{$flagf}")->getStyle("I{$flag}")->getNumberFormat()->setFormatCode('"$"#,##0.00_-');
		$flag++;
		$hoja->mergeCells('G'.$flag.':H'.$flag);
		$hoja->mergeCells('I'.$flag.':J'.$flag);
		$this->cellStyle("G".$flag, "000000", "FFFFFF", TRUE, 12, "Franklin Gothic Book");
		$hoja->setCellValue("G".$flag, "ULTRAMARINOS");
		$hoja->setCellValue("I{$flag}", "=CH{$flagf}")->getStyle("I{$flag}")->getNumberFormat()->setFormatCode('"$"#,##0.00_-');
		$flag++;
		$hoja->mergeCells('G'.$flag.':H'.$flag);
		$hoja->mergeCells('I'.$flag.':J'.$flag);
		$this->cellStyle("G".$flag, "000000", "FFFFFF", TRUE, 12, "Franklin Gothic Book");
		$hoja->setCellValue("G".$flag, "TRINCHERAS");
		$hoja->setCellValue("I{$flag}", "=CI{$flagf}")->getStyle("I{$flag}")->getNumberFormat()->setFormatCode('"$"#,##0.00_-');
		$flag++;
		$hoja->mergeCells('G'.$flag.':H'.$flag);
		$hoja->mergeCells('I'.$flag.':J'.$flag);
		$this->cellStyle("G".$flag, "000000", "FFFFFF", TRUE, 12, "Franklin Gothic Book");
		$hoja->setCellValue("G".$flag, "AZT MERCADO");
		$hoja->setCellValue("I{$flag}", "=CJ{$flagf}")->getStyle("I{$flag}")->getNumberFormat()->setFormatCode('"$"#,##0.00_-');
		$flag++;
		$hoja->mergeCells('G'.$flag.':H'.$flag);
		$hoja->mergeCells('I'.$flag.':J'.$flag);
		$this->cellStyle("G".$flag, "000000", "FFFFFF", TRUE, 12, "Franklin Gothic Book");
		$hoja->setCellValue("G".$flag, "TENENCIA");
		$hoja->setCellValue("I{$flag}", "=CK{$flagf}")->getStyle("I{$flag}")->getNumberFormat()->setFormatCode('"$"#,##0.00_-');
		$flag++;
		$hoja->mergeCells('G'.$flag.':H'.$flag);
		$hoja->mergeCells('I'.$flag.':J'.$flag);
		$this->cellStyle("G".$flag, "000000", "FFFFFF", TRUE, 12, "Franklin Gothic Book");
		$hoja->setCellValue("G".$flag, "TIJERAS");
		$hoja->setCellValue("I{$flag}", "=CL{$flagf}")->getStyle("I{$flag}")->getNumberFormat()->setFormatCode('"$"#,##0.00_-');
		$flag++;
		$hoja->mergeCells('G'.$flag.':H'.$flag);
		$hoja->mergeCells('I'.$flag.':J'.$flag);
		$this->cellStyle("G".$flag, "000000", "FFFFFF", TRUE, 12, "Franklin Gothic Book");
		$hoja->setCellValue("G".$flag, "TOTAL");
		$hoja->setCellValue("I{$flag}", "=CM{$flagf}")->getStyle("I{$flag}")->getNumberFormat()->setFormatCode('"$"#,##0.00_-');
		$flag = $bandera;

		$hoja->mergeCells('B'.$flag.':C'.$flag);
		$this->cellStyle("B".$flag, "000000", "FFFFFF", TRUE, 12, "Franklin Gothic Book");
		$hoja->setCellValue("B".$flag, "TOTALES EN GENERAL");
		$flag++;
		$this->cellStyle("B".$flag, "C00000", "000000", TRUE, 12, "Franklin Gothic Book");
		$hoja->setCellValue("B".$flag, "CEDIS");
		$hoja->setCellValue("C{$flag}", "=(".substr($sumall[1],0,-1).")")->getStyle("C{$flag}")->getNumberFormat()->setFormatCode('"$"#,##0.00_-');
		$flag++;
		$this->cellStyle("B".$flag, "01B0F0", "000000", TRUE, 12, "Franklin Gothic Book");
		$hoja->setCellValue("B".$flag, "ABARROTES");
		$hoja->setCellValue("C{$flag}", "=(".substr($sumall[2],0,-1).")")->getStyle("C{$flag}")->getNumberFormat()->setFormatCode('"$"#,##0.00_-');
		$flag++;
		$this->cellStyle("B".$flag, "FF0000", "000000", TRUE, 12, "Franklin Gothic Book");
		$hoja->setCellValue("B".$flag, "VILLAS");
		$hoja->setCellValue("C{$flag}", "=(".substr($sumall[3],0,-1).")")->getStyle("C{$flag}")->getNumberFormat()->setFormatCode('"$"#,##0.00_-');
		$flag++;
		$this->cellStyle("B".$flag, "E26C0B", "000000", TRUE, 12, "Franklin Gothic Book");
		$hoja->setCellValue("B".$flag, "TIENDA");
		$hoja->setCellValue("C{$flag}", "=(".substr($sumall[4],0,-1).")")->getStyle("C{$flag}")->getNumberFormat()->setFormatCode('"$"#,##0.00_-');
		$flag++;
		$this->cellStyle("B".$flag, "C5C5C5", "000000", TRUE, 12, "Franklin Gothic Book");
		$hoja->setCellValue("B".$flag, "ULTRAMARINOS");
		$hoja->setCellValue("C{$flag}", "=(".substr($sumall[5],0,-1).")")->getStyle("C{$flag}")->getNumberFormat()->setFormatCode('"$"#,##0.00_-');
		$flag++;
		$this->cellStyle("B".$flag, "92D051", "000000", TRUE, 12, "Franklin Gothic Book");
		$hoja->setCellValue("B".$flag, "TRINCHERAS");
		$hoja->setCellValue("C{$flag}", "=(".substr($sumall[6],0,-1).")")->getStyle("C{$flag}")->getNumberFormat()->setFormatCode('"$"#,##0.00_-');
		$flag++;
		$this->cellStyle("B".$flag, "B1A0C7", "000000", TRUE, 12, "Franklin Gothic Book");
		$hoja->setCellValue("B".$flag, "AZT MERCADO");
		$hoja->setCellValue("C{$flag}", "=(".substr($sumall[7],0,-1).")")->getStyle("C{$flag}")->getNumberFormat()->setFormatCode('"$"#,##0.00_-');
		$flag++;
		$this->cellStyle("B".$flag, "DA9694", "000000", TRUE, 12, "Franklin Gothic Book");
		$hoja->setCellValue("B".$flag, "TENENCIA");
		$hoja->setCellValue("C{$flag}", "=(".substr($sumall[8],0,-1).")")->getStyle("C{$flag}")->getNumberFormat()->setFormatCode('"$"#,##0.00_-');
		$flag++;
		$this->cellStyle("B".$flag, "4CACC6", "000000", TRUE, 12, "Franklin Gothic Book");
		$hoja->setCellValue("B".$flag, "TIJERAS");
		$hoja->setCellValue("C{$flag}", "=(".substr($sumall[9],0,-1).")")->getStyle("C{$flag}")->getNumberFormat()->setFormatCode('"$"#,##0.00_-');
		$flag++;
		$this->cellStyle("B".$flag, "000000", "FFFFFF", TRUE, 12, "Franklin Gothic Book");
		$hoja->setCellValue("B".$flag, "TOTAL");
		$hoja->setCellValue("C{$flag}", "=(".substr($sumall[10],0,-1).")")->getStyle("C{$flag}")->getNumberFormat()->setFormatCode('"$"#,##0.00_-');
		$flag++;

		$dias = array("DOMINGO","LUNES","MARTES","MIÉRCOLES","JUEVES","VIERNES","SÁBADO");
		$meses = array("ENERO","FEBRERO","MARZO","ABRIL","MAYO","JUNIO","JULIO","AGOSTO","SEPTIEMBRE","OCTUBRE","NOVIEMBRE","DICIEMBRE");
		$fecha =  $dias[date('w')]." ".date('d')." DE ".$meses[date('n')-1]. " DEL ".date('Y') ;
		$file_name = "FORMATO ".$filenam." ".$fecha.".xlsx"; //Nombre del documento con extención
		$excel_Writer = PHPExcel_IOFactory::createWriter($this->excelfile, "Excel2007");
		header("Content-Type: application/vnd.ms-excel; charset=utf-8");
		header("Content-Disposition: attachment;filename=".$file_name);
		header("Cache-Control: max-age=0");
		$excel_Writer->save("php://output");
		/*$excel_Writer = new PHPExcel_Writer_Excel2007($this->excelfile);
		$excel_Writer->setOffice2003Compatibility(true);
		$excel_Writer->save("php://output");*/
	}

	private function fill_moderna($id_proves,$proves,$prs){
		$flag =1;
		$flag1 = 1;
		$array = "";
		$array2 = "";
		$filenam = "";
		$id_proves = $this->input->post('id_proves4');
		$proves = $this->input->post('id_proves2');
		$prs = substr($id_proves,0,6);
		$filenam = $id_proves;
		$array = (object)['0'=>(object)['nombre' => $id_proves]];
		//$this->jsonResponse($array);
		ini_set("memory_limit", "-1");
		ini_set("max_execution_time", "-1");
		$this->load->library("excelfile");
		$hoja1 = $this->excelfile->setActiveSheetIndex(0);
		$this->excelfile->setActiveSheetIndex(0)->setTitle("EXISTENCIAS");
		$this->excelfile->createSheet();
        $hoja = $this->excelfile->setActiveSheetIndex(1);
        $hoja->setTitle("PEDIDO");
		$styleArray = array(
		  'borders' => array(
		    'allborders' => array(
		      'style' => PHPExcel_Style_Border::BORDER_THIN
		    )
		  )
		);
		$hoja->getColumnDimension('A')->setWidth("20");
		$hoja->getColumnDimension('B')->setWidth("70");
		$hoja->getColumnDimension('C')->setWidth("15");
		$hoja->getColumnDimension('D')->setWidth("8");

		$hoja->getColumnDimension('E')->setWidth("15");
		$hoja->getColumnDimension('G')->setWidth("8");
		$hoja->getColumnDimension('I')->setWidth("8");
		$hoja->getColumnDimension('F')->setWidth("20");
		$hoja->getColumnDimension('H')->setWidth("15");

		$hoja1->getColumnDimension('A')->setWidth("6");
		$hoja1->getColumnDimension('B')->setWidth("6");
		$hoja1->getColumnDimension('C')->setWidth("6");
		$hoja1->getColumnDimension('D')->setWidth("25");
		$hoja1->getColumnDimension('E')->setWidth("47");
		$hoja1->getColumnDimension('G')->setWidth("50");

		$hoja->getColumnDimension('BM' )->setWidth("70");
		$hoja->getColumnDimension('L')->setWidth("20");

		$flagBorder = 0;
		$flagBorder1 = 1;
		$flagBorder2 = 0;
		$flagBorder3 = 1;
		$flage = 5;
		$i = 0;
		$sumall = array(1 => "", 2 => "", 3 => "", 4 => "", 5 => "", 6 => "", 7 => "", 8 => "", 9 => "", 10 => "");
		$provname = "";
		if ($array){
			foreach ($array as $key => $value){
				$fecha = new DateTime(date('Y-m-d H:i:s'));
				$intervalo = new DateInterval('P2D');
				$fecha->add($intervalo);
				$cotizacionesProveedor = $this->ct_mdl->getPedidosAllModerna(NULL, $fecha->format('Y-m-d H:i:s'), 0);
			
				$difff = 0.01;
				$flag2 = 3;
				$cargo = "";
				if ($cotizacionesProveedor){
					//HOJA EXISTENCIAS
					$this->excelfile->setActiveSheetIndex(0);
					if($i > 0){
						$flagBorder = $flag1 ;
						$this->excelfile->getActiveSheet()->getStyle('A'.$flagBorder1.':G'.$flagBorder)->applyFromArray($styleArray);
						$flagBorder1 = $flag1;
					}
					$hoja1->mergeCells('A'.$flag1.':G'.$flag1);
					$this->cellStyle("A".$flag1, "FFFFFF", "000000", TRUE, 12, "Franklin Gothic Book");
					$hoja1->setCellValue("A".$flag1."", "GRUPO ABARROTES AZTECA");
					$this->excelfile->getActiveSheet()->getStyle('A'.$flag1.':G'.$flag1)->applyFromArray($styleArray);
					$flag1++;
					$hoja1->mergeCells('A'.$flag1.':G'.$flag1);
					$this->cellStyle("A".$flag1, "FFFFFF", "000000", TRUE, 12, "Franklin Gothic Book");
					$hoja1->setCellValue("A".$flag1."", "PEDIDOS A '".$value->nombre."' ".date("d-m-Y"));
					$provname = $value->nombre;
					$this->excelfile->getActiveSheet()->getStyle('A'.$flag1.':G'.$flag1)->applyFromArray($styleArray);
					$flag1++;
					$this->cellStyle("A".$flag1.":D".$flag1, "000000", "FFFFFF", TRUE, 12, "Franklin Gothic Book");
					$hoja1->mergeCells('A'.$flag1.':B'.$flag1);
					$hoja1->setCellValue("A".$flag1, "EXISTENCIAS");
					$hoja1->setCellValue("E".$flag1, "DESCRIPCIÓN");
					$this->cellStyle("E".$flag1, "000000", "FFFFFF", TRUE, 12, "Franklin Gothic Book");

					$this->cellStyle("H".$flag1."", "000000", "FFFFFF", TRUE, 12, "Franklin Gothic Book");
					$hoja1->setCellValue("H".$flag1."", "PENDIENT");
					$this->cellStyle("I".$flag1."", "000000", "FFFFFF", TRUE, 12, "Franklin Gothic Book");
					$hoja1->setCellValue("I".$flag1."", "PENDIENT");
					$this->cellStyle("J".$flag1."", "000000", "FFFFFF", TRUE, 12, "Franklin Gothic Book");
					$hoja1->setCellValue("J".$flag1."", "PENDIENT");
					$this->cellStyle("K".$flag1."", "000000", "FFFFFF", TRUE, 12, "Franklin Gothic Book");
					$hoja1->setCellValue("K".$flag1."", "PENDIENT");
					$this->cellStyle("L".$flag1."", "000000", "FFFFFF", TRUE, 12, "Franklin Gothic Book");
					$hoja1->setCellValue("L".$flag1."", "PENDIENT");
					$this->cellStyle("M".$flag1."", "000000", "FFFFFF", TRUE, 12, "Franklin Gothic Book");
					$hoja1->setCellValue("M".$flag1."", "PENDIENT");
					$this->cellStyle("N".$flag1."", "000000", "FFFFFF", TRUE, 12, "Franklin Gothic Book");
					$hoja1->setCellValue("N".$flag1."", "PENDIENT");
					$this->cellStyle("O".$flag1."", "000000", "FFFFFF", TRUE, 12, "Franklin Gothic Book");
					$hoja1->setCellValue("O".$flag1."", "PENDIENT");
					$this->cellStyle("P".$flag1."", "000000", "FFFFFF", TRUE, 12, "Franklin Gothic Book");
					$hoja1->setCellValue("P".$flag1."", "PENDIENT");
					$this->excelfile->getActiveSheet()->getStyle('A'.$flag1.':P'.$flag1)->applyFromArray($styleArray);
					$flag1++;
					$this->cellStyle("A".$flag1.":G".$flag1, "000000", "FFFFFF", TRUE, 12, "Franklin Gothic Book");
					$hoja1->setCellValue("A".$flag1, "CAJAS");
					$hoja1->setCellValue("B".$flag1, "PZAS");
					$hoja1->setCellValue("C".$flag1, "PEDIDO");
					$hoja1->setCellValue("D".$flag1, "CÓDIGO");
					$hoja1->setCellValue("G".$flag1, "PROMOCIÓN");
					$hoja1->setCellValue("F".$flag1, "IMAGEN")->getColumnDimension('F')->setWidth(18);
					
					$this->cellStyle("H".$flag1, "C00000", "000000", TRUE, 10, "Franklin Gothic Book");
					$this->cellStyle("I".$flag1, "01B0F0", "000000", TRUE, 10, "Franklin Gothic Book");
					$this->cellStyle("J".$flag1, "FF0000", "000000", TRUE, 10, "Franklin Gothic Book");
					$this->cellStyle("K".$flag1, "E26C0B", "000000", TRUE, 10, "Franklin Gothic Book");
					$this->cellStyle("L".$flag1, "C5C5C5", "000000", TRUE, 10, "Franklin Gothic Book");
					$this->cellStyle("M".$flag1, "92D051", "000000", TRUE, 10, "Franklin Gothic Book");
					$this->cellStyle("N".$flag1, "B1A0C7", "000000", TRUE, 10, "Franklin Gothic Book");
					$this->cellStyle("O".$flag1, "DA9694", "000000", TRUE, 10, "Franklin Gothic Book");
					$this->cellStyle("P".$flag1, "4CACC6", "000000", TRUE, 10, "Franklin Gothic Book");
					$hoja1->setCellValue("H".$flag1."", "CEDIS");
					$hoja1->setCellValue("I".$flag1."", "ABARROTES");
					$hoja1->setCellValue("J".$flag1."", "VILLAS");
					$hoja1->setCellValue("K".$flag1."", "TIENDA");
					$hoja1->setCellValue("L".$flag1."", "ULTRA");
					$hoja1->setCellValue("M".$flag1."", "TRINCHERAS");
					$hoja1->setCellValue("N".$flag1."", "MERCADO");
					$hoja1->setCellValue("O".$flag1."", "TENENCIA");
					$hoja1->setCellValue("P".$flag1."", "TIJERAS");
					$this->excelfile->getActiveSheet()->getStyle('A'.$flag1.':P'.$flag1)->applyFromArray($styleArray);
					//$flag1++;
					$this->excelfile->setActiveSheetIndex(1);
					if($i > 0){
						$flagBorder2 = $flag ;
						$this->excelfile->getActiveSheet()->getStyle('A'.$flagBorder3.':AD'.$flagBorder2)->applyFromArray($styleArray);
						$flagBorder3 = $flag;
					}


					//HOJA PEDIDOS
					$this->cellStyle("A".$flag, "FFFFFF", "000000", TRUE, 12, "Franklin Gothic Book");
					$hoja->setCellValue("A".$flag."", "CEDIS, ABARROTES,PEDREGAL, TIENDA, ULTRAMARINOS, TRINCHERAS, MERCADO, TIJERAS, Y TENENCIA AZTECA AUTOSERVICIOS SA. DE CV.");
					$hoja->mergeCells('A'.$flag.':BM'.$flag);
					$this->excelfile->getActiveSheet()->getStyle('A'.$flag.':BM'.$flag)->applyFromArray($styleArray);
					$flag++;
					$hoja->mergeCells('A'.$flag.':L'.$flag);
					$hoja->mergeCells('M'.$flag.':Q'.$flag);
					$hoja->mergeCells('R'.$flag.':U'.$flag);
					$hoja->mergeCells('V'.$flag.':X'.$flag);
					$hoja->mergeCells('Y'.$flag.':AC'.$flag);
					$hoja->mergeCells('AD'.$flag.':AH'.$flag);
					$hoja->mergeCells('AI'.$flag.':AM'.$flag);
					$hoja->mergeCells('AN'.$flag.':AR'.$flag);
					$hoja->mergeCells('AS'.$flag.':AW'.$flag);
					$hoja->mergeCells('AX'.$flag.':BB'.$flag);
					$hoja->mergeCells('BC'.$flag.':BG'.$flag);
					$hoja->mergeCells('BH'.$flag.':BL'.$flag);

					$this->cellStyle("B".$flag, "FFFFFF", "000000", TRUE, 12, "Franklin Gothic Book");
					$hoja->setCellValue("B".$flag, "PEDIDOS A '".$value->nombre."' ".date("d-m-Y"));
					$this->cellStyle("M".$flag, "C00000", "000000", TRUE, 12, "Franklin Gothic Book");
					$hoja->setCellValue("M".$flag, "CEDIS");
					$hoja->setCellValue("R".$flag, "CD INDUSTRIAL");
					$this->cellStyle("R".$flag, "FF0066", "000000", TRUE, 12, "Franklin Gothic Book");
					$this->cellStyle("V".$flag, "C2B90A", "000000", TRUE, 12, "Franklin Gothic Book");
					$hoja->setCellValue("V".$flag, "SUMA CEDIS");
					$this->cellStyle("Y".$flag, "01B0F0", "000000", TRUE, 12, "Franklin Gothic Book");
					$hoja->setCellValue("Y".$flag, "ABARROTES");
					$this->cellStyle("AD".$flag, "FF0000", "000000", TRUE, 12, "Franklin Gothic Book");
					$hoja->setCellValue("AD".$flag, "VILLAS");
					$this->cellStyle("AI".$flag, "E26C0B", "000000", TRUE, 12, "Franklin Gothic Book");
					$hoja->setCellValue("AI".$flag, "TIENDA");
					$this->cellStyle("AN".$flag, "C5C5C5", "000000", TRUE, 12, "Franklin Gothic Book");
					$hoja->setCellValue("AN".$flag, "ULTRAMARINOS");
					$this->cellStyle("AS".$flag, "92D051", "000000", TRUE, 12, "Franklin Gothic Book");
					$hoja->setCellValue("AS".$flag, "TRINCHERAS");
					$this->cellStyle("AX".$flag, "B1A0C7", "000000", TRUE, 12, "Franklin Gothic Book");
					$hoja->setCellValue("AX".$flag, "AZT MERCADO");
					$this->cellStyle("BC".$flag, "DA9694", "000000", TRUE, 12, "Franklin Gothic Book");
					$hoja->setCellValue("BC".$flag, "TENENCIA");
					$this->cellStyle("BH".$flag, "4CACC6", "000000", TRUE, 12, "Franklin Gothic Book");
					$hoja->setCellValue("BH".$flag, "TIJERAS");
					
					$this->cellStyle("A3:BM4", "000000", "FFFFFF", TRUE, 12, "Franklin Gothic Book");
					
					$this->excelfile->getActiveSheet()->getStyle('A'.$flag.':BM'.$flag)->applyFromArray($styleArray);
					$flag++;
					$this->cellStyle("A".$flag.":BM".$flag."", "000000", "FFFFFF", TRUE, 12, "Franklin Gothic Book");
					$hoja->mergeCells('A'.$flag.':L'.$flag);
					$hoja->mergeCells('M'.$flag.':Q'.$flag);
					$hoja->mergeCells('R'.$flag.':U'.$flag);
					$hoja->mergeCells('V'.$flag.':X'.$flag);
					$hoja->mergeCells('Y'.$flag.':AC'.$flag);
					$hoja->mergeCells('AD'.$flag.':AH'.$flag);
					$hoja->mergeCells('AI'.$flag.':AM'.$flag);
					$hoja->mergeCells('AN'.$flag.':AR'.$flag);
					$hoja->mergeCells('AS'.$flag.':AW'.$flag);
					$hoja->mergeCells('AX'.$flag.':BB'.$flag);
					$hoja->mergeCells('BC'.$flag.':BG'.$flag);
					$hoja->mergeCells('BH'.$flag.':BL'.$flag);
					$hoja->setCellValue("A".$flag, "DESCRIPCIÓN");
					$hoja->setCellValue("M".$flag, "EXISTENCIAS");
					$hoja->setCellValue("R".$flag, " SUMA EXISTENCIAS");
					$hoja->setCellValue("V".$flag, "EXISTENCIAS");
					$hoja->setCellValue("Y".$flag, "EXISTENCIAS");
					$hoja->setCellValue("AD".$flag, "EXISTENCIAS");
					$hoja->setCellValue("AI".$flag, "EXISTENCIAS");
					$hoja->setCellValue("AN".$flag, "EXISTENCIAS");
					$hoja->setCellValue("AS".$flag, "EXISTENCIAS");
					$hoja->setCellValue("AX".$flag, "EXISTENCIAS");
					$hoja->setCellValue("BC".$flag, "EXISTENCIAS");
					$hoja->setCellValue("BH".$flag, "EXISTENCIAS");
					$this->excelfile->getActiveSheet()->getStyle('A'.$flag.':BM'.$flag)->applyFromArray($styleArray);
					//Begin: TOTALES PEDIDOS PENDIENTES
					$hoja->mergeCells('CD'.$flag.':CN'.$flag);
					$this->cellStyle("CD".$flag.":CN".$flag."", "000000", "FFFFFF", TRUE, 12, "Franklin Gothic Book");
					$hoja->setCellValue("CD".$flag, "TOTAL POR PEDIDOS PENDIENTES");
					//End: TOTALES PEDIDOS PENDIENTES
					$flag++;
					$this->cellStyle("A".$flag.":BM".$flag."", "000000", "FFFFFF", TRUE, 12, "Franklin Gothic Book");
					$hoja->setCellValue("A".$flag, "CODIGO");
					$hoja->setCellValue("C".$flag, "COD PROV")
					;
					$hoja->setCellValue("D".$flag, "UM");
					$hoja->setCellValue("E".$flag, "1ER");
					$hoja->setCellValue("F".$flag, "COSTO");
					$hoja->setCellValue("G".$flag, "DIF % 5 Y 1ER");
					$hoja->setCellValue("H".$flag, "SISTEMA");
					$hoja->setCellValue("I".$flag, "DIF % 5 Y 4");
					$hoja->setCellValue("J".$flag, "PRECIO4");
					$hoja->setCellValue("K".$flag, "2DO");
					$hoja->setCellValue("L".$flag, "PROVEEDOR");

					$hoja->setCellValue("M".$flag, "CAJAS");
					$hoja->setCellValue("N".$flag, "PZAS");
					$hoja->setCellValue("O".$flag, "PEND");
					$hoja->setCellValue("P".$flag, "STOCK");
					$hoja->setCellValue("Q".$flag, "PEDIDO");
					$hoja->setCellValue("R".$flag, "CAJAS");
					$hoja->setCellValue("S".$flag, "PZAS");
					$hoja->setCellValue("T".$flag, "STOCK");
					$hoja->setCellValue("U".$flag, "PEDIDO");
					$hoja->setCellValue("V".$flag, "CAJAS");
					$hoja->setCellValue("W".$flag, "PZAS");
					$hoja->setCellValue("X".$flag, "PEDIDO");
					$hoja->setCellValue("Y".$flag, "CAJAS");
					$hoja->setCellValue("Z".$flag, "PZAS");
					$hoja->setCellValue("AA".$flag, "PEND");
					$hoja->setCellValue("AB".$flag, "STOCK");
					$hoja->setCellValue("AC".$flag, "PEDIDO");
					$hoja->setCellValue("AD".$flag, "CAJAS");
					$hoja->setCellValue("AE".$flag, "PZAS");
					$hoja->setCellValue("AF".$flag, "PEND");
					$hoja->setCellValue("AG".$flag, "STOCK");
					$hoja->setCellValue("AH".$flag, "PEDIDO");
					$hoja->setCellValue("AI".$flag, "CAJAS");
					$hoja->setCellValue("AJ".$flag, "PZAS");
					$hoja->setCellValue("AK".$flag, "PEND");
					$hoja->setCellValue("AL".$flag, "STOCK");
					$hoja->setCellValue("AM".$flag, "PEDIDO");
					$hoja->setCellValue("AN".$flag, "CAJAS");
					$hoja->setCellValue("AO".$flag, "PZAS");
					$hoja->setCellValue("AP".$flag, "PEND");
					$hoja->setCellValue("AQ".$flag, "STOCK");
					$hoja->setCellValue("AR".$flag, "PEDIDO");
					$hoja->setCellValue("AS".$flag, "CAJAS");
					$hoja->setCellValue("AT".$flag, "PZAS");
					$hoja->setCellValue("AU".$flag, "PEND");
					$hoja->setCellValue("AV".$flag, "STOCK");
					$hoja->setCellValue("AW".$flag, "PEDIDO");
					$hoja->setCellValue("AX".$flag, "CAJAS");
					$hoja->setCellValue("AY".$flag, "PZAS");
					$hoja->setCellValue("AZ".$flag, "PEND");
					$hoja->setCellValue("BA".$flag, "STOCK");
					$hoja->setCellValue("BB".$flag, "PEDIDO");
					$hoja->setCellValue("BC".$flag, "CAJAS");
					$hoja->setCellValue("BD".$flag, "PZAS");
					$hoja->setCellValue("BE".$flag, "PEND");
					$hoja->setCellValue("BF".$flag, "STOCK");
					$hoja->setCellValue("BG".$flag, "PEDIDO");
					$hoja->setCellValue("BH".$flag, "CAJAS");
					$hoja->setCellValue("BI".$flag, "PZAS");
					$hoja->setCellValue("BJ".$flag, "PEND");
					$hoja->setCellValue("BK".$flag, "STOCK");
					$hoja->setCellValue("BL".$flag, "PEDIDO");
					$hoja->setCellValue("BM".$flag, "PROMOCIÓN");					
					
					
					$this->cellStyle("BN".$flag1, "C00000", "000000", TRUE, 10, "Franklin Gothic Book");
					$this->cellStyle("BO".$flag1, "01B0F0", "000000", TRUE, 10, "Franklin Gothic Book");
					$this->cellStyle("BP".$flag1, "FF0000", "000000", TRUE, 10, "Franklin Gothic Book");
					$this->cellStyle("BQ".$flag1, "E26C0B", "000000", TRUE, 10, "Franklin Gothic Book");
					$this->cellStyle("BR".$flag1, "C5C5C5", "000000", TRUE, 10, "Franklin Gothic Book");
					$this->cellStyle("BS".$flag1, "92D051", "000000", TRUE, 10, "Franklin Gothic Book");
					$this->cellStyle("BT".$flag1, "B1A0C7", "000000", TRUE, 10, "Franklin Gothic Book");
					$this->cellStyle("BU".$flag1, "DA9694", "000000", TRUE, 10, "Franklin Gothic Book");
					$this->cellStyle("BV".$flag1, "4CACC6", "000000", TRUE, 10, "Franklin Gothic Book");

					$hoja->setCellValue("BW".$flag, "TOTAL");
					$hoja->setCellValue("BX".$flag, "PEDIDOS");
					$this->cellStyle("BW".$flag."", "000000", "FFFFFF", TRUE, 12, "Franklin Gothic Book");
					$this->cellStyle("BX".$flag."", "000000", "FFFFFF", TRUE, 12, "Franklin Gothic Book");
					$this->excelfile->getActiveSheet()->getStyle('BW'.$flag)->applyFromArray($styleArray);
					$this->excelfile->getActiveSheet()->getStyle('BX'.$flag)->applyFromArray($styleArray);
					$this->excelfile->getActiveSheet()->getStyle('A'.$flag.':BM'.$flag)->applyFromArray($styleArray);

					//Begin: TOTALES PEDIDOS PENDIENTES
					$this->cellStyle("CD".$flag, "C00000", "000000", TRUE, 12, "Franklin Gothic Book");
					$this->cellStyle("CE".$flag, "01B0F0", "000000", TRUE, 12, "Franklin Gothic Book");
					$this->cellStyle("CF".$flag, "FF0000", "000000", TRUE, 12, "Franklin Gothic Book");
					$this->cellStyle("CG".$flag, "E26C0B", "000000", TRUE, 12, "Franklin Gothic Book");
					$this->cellStyle("CH".$flag, "C5C5C5", "000000", TRUE, 12, "Franklin Gothic Book");
					$this->cellStyle("CI".$flag, "92D051", "000000", TRUE, 12, "Franklin Gothic Book");
					$this->cellStyle("CJ".$flag, "B1A0C7", "000000", TRUE, 12, "Franklin Gothic Book");
					$this->cellStyle("CK".$flag, "DA9694", "000000", TRUE, 12, "Franklin Gothic Book");
					$this->cellStyle("CL".$flag, "4CACC6", "000000", TRUE, 12, "Franklin Gothic Book");
					$this->cellStyle("CM".$flag."", "000000", "FFFFFF", TRUE, 12, "Franklin Gothic Book");
					$this->cellStyle("CN".$flag."", "000000", "FFFFFF", TRUE, 12, "Franklin Gothic Book");
					$hoja->setCellValue("CM".$flag, "TOTAL");
					$hoja->setCellValue("CN".$flag, "PEDIDOS");
					//End: TOTALES PEDIDOS PENDIENTES
					foreach ($cotizacionesProveedor as $key => $value){
						//Existencias
						$this->excelfile->setActiveSheetIndex(0);
						$this->cellStyle("E".$flag1, "000000", "FFFFFF", FALSE, 12, "Franklin Gothic Book");
						$hoja1->setCellValue("E".$flag1, $value['familia']);
						$flag1 +=1;
						//Pedidos
						$this->excelfile->setActiveSheetIndex(1);
						$this->cellStyle("B".$flag, "000000", "FFFFFF", FALSE, 12, "Franklin Gothic Book");
						$hoja->setCellValue("B{$flag}", $value['familia']);
						$flag +=1;
						if ($value['articulos']) {
							foreach ($value['articulos'] as $key => $row){
								//Existencias
								$cargo = $row['cargo'];
								$registrazo = date('Y-m-d',strtotime($row['registrazo']));
								$this->excelfile->setActiveSheetIndex(0);
								$this->cellStyle("A".$flag1.":P".$flag1, "FFFFFF", "000000", FALSE, 12, "Franklin Gothic Book");
								
								$hoja1->setCellValue("D{$flag1}", $row['codigo'])->getStyle("D{$flag1}")->getNumberFormat()->setFormatCode('# ???/???');//Formato de fraccion
								if($row['color'] == '#92CEE3'){
									$this->cellStyle("D{$flag1}", "92CEE3", "000000", FALSE, 12, "Franklin Gothic Book");
								}else{
									$this->cellStyle("D{$flag1}", "FFFFFF", "000000", FALSE, 12, "Franklin Gothic Book");
								}
								$hoja1->setCellValue("E{$flag1}", $row['producto']);
								$hoja1->setCellValue("G{$flag1}", $row['promocion_first']);
								$hoja1->setCellValue("H{$flag1}", $row['cedis']);
								$hoja1->setCellValue("I{$flag1}", $row['abarrotes']);
								$hoja1->setCellValue("J{$flag1}", $row['pedregal']);
								$hoja1->setCellValue("K{$flag1}", $row['tienda']);
								$hoja1->setCellValue("L{$flag1}", $row['ultra']);
								$hoja1->setCellValue("M{$flag1}", $row['trincheras']);
								$hoja1->setCellValue("N{$flag1}", $row['mercado']);
								$hoja1->setCellValue("O{$flag1}", $row['tenencia']);
								$hoja1->setCellValue("P{$flag1}", $row['tijeras']);

								if ($row["imagen"] <> "" && !is_null($row["imagen"]) ) {
									$objDrawing = new PHPExcel_Worksheet_Drawing();
									$objDrawing->setName('COD'.$row['producto']);
									$objDrawing->setDescription('DESC'.$row['codigo']);
									$objDrawing->setPath("./Abarrotes/assets/img/productos/".$row["imagen"]."");
									$objDrawing->setWidth(50);
									$objDrawing->setHeight(50);
									$objDrawing->setCoordinates('F'.$flag1);
									$objDrawing->setOffsetX(5); 
									$objDrawing->setOffsetY(5);
									//$objDrawing->setUrl('http://abarrotesazteca.com/Abarrotes/assets/img/productos/'.$row["imagen"]);
									$objDrawing->setWorksheet($this->excelfile->getActiveSheet());
									$this->excelfile->getActiveSheet()->getRowDimension($flag1)->setRowHeight(60);
									$this->excelfile->getActiveSheet()->getStyleByColumnAndRow(10, $flag1)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_DATE_XLSX14);
									$this->excelfile->getActiveSheet()->getCell('F'.$flag1)->getHyperlink()->setUrl('http://abarrotesazteca.com/Abarrotes/assets/img/productos/'.str_replace("_thumb.",".",$row["imagen"]));
								}

								$hoja1->getStyle("A{$flag1}:P{$flag1}")
						                 ->getAlignment()
						                 ->setHorizontal(\PHPExcel_Style_Alignment::HORIZONTAL_LEFT);
						         
				                 
								//Pedidos
								$this->excelfile->setActiveSheetIndex(1);
								$this->cellStyle("A".$flag.":BX".$flag."", "FFFFFF", "000000", FALSE, 12, "Franklin Gothic Book");
								
								$hoja->setCellValue("A{$flag}", $row['codigo'])->getStyle("A{$flag}")->getNumberFormat()->setFormatCode('# ???/???');//Formato de fraccion
								if($row['color'] == '#92CEE3'){
									$this->cellStyle("A{$flag}", "92CEE3", "000000", FALSE, 12, "Franklin Gothic Book");
								}else{
									$this->cellStyle("A{$flag}", "FFFFFF", "000000", FALSE, 12, "Franklin Gothic Book");
								}
								$hoja->setCellValue("B{$flag}", $row['producto']);
								$hoja->setCellValue("F{$flag}", $row['proveedor_first']);
								$hoja->setCellValue("C{$flag}", $row['codigo_factura'])->getStyle("C{$flag}")->getNumberFormat()->setFormatCode('# ???/???');

								

								if($row['precio_sistema'] < $row['precio_first']){
									$hoja->setCellValue("E{$flag}", $row['precio_first'])->getStyle("E{$flag}")->getNumberFormat()->setFormatCode('"$"#,##0.00_-');
									$this->cellStyle("E{$flag}", "FDB2B2", "E21111", FALSE, 12, "Franklin Gothic Book");
									$this->cellStyle("B{$flag}", "E21600", "000000", FALSE, 12, "Franklin Gothic Book");
								}else{
									$hoja->setCellValue("E{$flag}", $row['precio_first'])->getStyle("E{$flag}")->getNumberFormat()->setFormatCode('"$"#,##0.00_-');
									$this->cellStyle("E{$flag}", "96EAA8", "0C800C", FALSE, 12, "Franklin Gothic Book");
									$this->cellStyle("B{$flag}", "249947", "000000", FALSE, 12, "Franklin Gothic Book");
								}
								$hoja->setCellValue("H{$flag}", $row['precio_sistema'])->getStyle("H{$flag}")->getNumberFormat()->setFormatCode('"$"#,##0.00_-');//Formto de moneda
								$this->cellStyle("H".$flag, "FFFFFF","000000",  FALSE, 12, "Franklin Gothic Book");
								if($row['colorp'] == 1){
									$this->cellStyle("H{$flag}", "D6DCE4", "000000", FALSE, 12, "Franklin Gothic Book");
								}else{
									$this->cellStyle("H{$flag}", "FFFFFF", "000000", FALSE, 12, "Franklin Gothic Book");
								}
								$hoja->setCellValue("J{$flag}", $row['precio_four'])->getStyle("J{$flag}")->getNumberFormat()->setFormatCode('"$"#,##0.00_-');
								$this->cellStyle("J{$flag}", "FFFFFF", "000000", FALSE, 12, "Franklin Gothic Book");
								if($row['precio_sistema'] < $row['precio_next']){
									$hoja->setCellValue("K{$flag}", $row['precio_next'])->getStyle("K{$flag}")->getNumberFormat()->setFormatCode('"$"#,##0.00_-');
									$this->cellStyle("K{$flag}", "FDB2B2", "E21111", FALSE, 12, "Franklin Gothic Book");
								}else if($row['precio_next'] !== NULL){
									$hoja->setCellValue("K{$flag}", $row['precio_next'])->getStyle("K{$flag}")->getNumberFormat()->setFormatCode('"$"#,##0.00_-');
									$this->cellStyle("K{$flag}", "96EAA8", "0C800C", FALSE, 12, "Franklin Gothic Book");
								}else{
									$hoja->setCellValue("K{$flag}", $row['precio_next'])->getStyle("K{$flag}")->getNumberFormat()->setFormatCode('"$"#,##0.00_-');
									$this->cellStyle("K{$flag}", "FFFFFF", "000000", FALSE, 12, "Franklin Gothic Book");
								}
								$this->cellStyle("M".$flag.":BB".$flag, "FFFFFF", "000000", TRUE, 12, "Franklin Gothic Book");
								$hoja->setCellValue("L{$flag}", $row['proveedor_next']);
								$this->cellStyle("L".$flag, "FFFFFF", "000000", FALSE, 12, "Franklin Gothic Book");

								
								
								$hoja->setCellValue("O{$flag}", $row['cedis']);
								$hoja->setCellValue("AA{$flag}", $row['abarrotes']);
								$hoja->setCellValue("AF{$flag}", $row['pedregal']);
								$hoja->setCellValue("AK{$flag}", $row['tienda']);
								$hoja->setCellValue("AP{$flag}", $row['ultra']);
								$hoja->setCellValue("AU{$flag}", $row['trincheras']);
								$hoja->setCellValue("AZ{$flag}", $row['mercado']);
								$hoja->setCellValue("BE{$flag}", $row['tenencia']);
								$hoja->setCellValue("BJ{$flag}", $row['tijeras']);

								if ($row["lastfecha"] === NULL || $row["lastfecha"] === "") {
									$row["lastfecha"] = "NO PEDIDOS";
								}
								if ($row["unidad"] === NULL || $row["unidad"] === "") {
									$row["unidad"] = 1;
								}
								$hoja->setCellValue("D{$flag}",$row["unidad"]);
								
								$hoja->setCellValue("M{$flag}", $row['caja0']);
								$hoja->setCellValue("N{$flag}", $row['pz0']);
								$hoja->setCellValue("P{$flag}", $row["past"]["caja0"]);
								$hoja->setCellValue("Q{$flag}", $row['ped0']);
								$this->cellStyle("Q{$flag}", "D4EAEF", "000000", TRUE, 12, "Franklin Gothic Book");

								
								$hoja->setCellValue("R{$flag}", $row['caja9']);
								$hoja->setCellValue("S{$flag}", $row['pz9']);
								$hoja->setCellValue("T{$flag}", $row["past"]["caja9"]);
								$hoja->setCellValue("U{$flag}", $row['ped9']);
								$this->cellStyle("U{$flag}", "D4EAEF", "000000", TRUE, 12, "Franklin Gothic Book");

								

								$hoja->setCellValue("V{$flag}", "=M".$flag."+R".$flag);
								$hoja->setCellValue("W{$flag}", "=N".$flag."+S".$flag);
								$hoja->setCellValue("X{$flag}", "=Q".$flag."+U".$flag);
								$this->cellStyle("X{$flag}", "D4EAEF", "000000", TRUE, 12, "Franklin Gothic Book");

								$hoja->setCellValue("Y{$flag}", $row['caja1']);
								$hoja->setCellValue("Z{$flag}", $row['pz1']);
								$hoja->setCellValue("AB{$flag}", $row["past"]["caja1"]);
								$hoja->setCellValue("AC{$flag}", $row['ped1']);
								$this->cellStyle("AC{$flag}", "D4EAEF", "000000", TRUE, 12, "Franklin Gothic Book");

								$hoja->setCellValue("AD{$flag}", $row['caja2']);
								$hoja->setCellValue("AE{$flag}", $row['pz2']);
								$hoja->setCellValue("AG{$flag}", $row["past"]["caja2"]);
								$hoja->setCellValue("AH{$flag}", $row['ped2']);
								$this->cellStyle("AH{$flag}", "D4EAEF", "000000", TRUE, 12, "Franklin Gothic Book");

								$hoja->setCellValue("AI{$flag}", $row['caja3']);
								$hoja->setCellValue("AJ{$flag}", $row['pz3']);
								$hoja->setCellValue("AL{$flag}", $row["past"]["caja3"]);
								$hoja->setCellValue("AM{$flag}", $row['ped3']);
								$this->cellStyle("AM{$flag}", "D4EAEF", "000000", TRUE, 12, "Franklin Gothic Book");

								$hoja->setCellValue("AN{$flag}", $row['caja4']);
								$hoja->setCellValue("AO{$flag}", $row['pz4']);
								$hoja->setCellValue("AQ{$flag}", $row["past"]["caja4"]);
								$hoja->setCellValue("AR{$flag}", $row['ped4']);
								$this->cellStyle("AR{$flag}", "D4EAEF", "000000", TRUE, 12, "Franklin Gothic Book");

							
								$hoja->setCellValue("AS{$flag}", $row['caja5']);
								$hoja->setCellValue("AT{$flag}", $row['pz5']);
								$hoja->setCellValue("AV{$flag}", $row["past"]["caja5"]);
								$hoja->setCellValue("AW{$flag}", $row['ped5']);
								$this->cellStyle("AW{$flag}", "D4EAEF", "000000", TRUE, 12, "Franklin Gothic Book");

								$hoja->setCellValue("AX{$flag}", $row['caja6']);
								$hoja->setCellValue("AY{$flag}", $row['pz6']);
								$hoja->setCellValue("BA{$flag}", $row["past"]["caja6"]);
								$hoja->setCellValue("BB{$flag}", $row['ped6']);
								$this->cellStyle("BB{$flag}", "D4EAEF", "000000", TRUE, 12, "Franklin Gothic Book");

								
								$hoja->setCellValue("BC{$flag}", $row['caja7']);
								$hoja->setCellValue("BD{$flag}", $row['pz7']);
								$hoja->setCellValue("BF{$flag}", $row["past"]["caja7"]);
								$hoja->setCellValue("BG{$flag}", $row['ped7']);
								$this->cellStyle("BG{$flag}", "D4EAEF", "000000", TRUE, 12, "Franklin Gothic Book");

								$hoja->setCellValue("BH{$flag}", $row['caja8']);
								$hoja->setCellValue("BI{$flag}", $row['pz8']);
								$hoja->setCellValue("BK{$flag}", $row["past"]["caja8"]);
								$hoja->setCellValue("BL{$flag}", $row['ped8']);
								$this->cellStyle("BL{$flag}", "D4EAEF", "000000", TRUE, 12, "Franklin Gothic Book");

								
								$this->cellStyle("BM{$flag}", "FFFFFF", "000000", TRUE, 12, "Franklin Gothic Book");
								$hoja->setCellValue("BM{$flag}", $row['promocion_first']);
								$hoja->setCellValue("BN{$flag}", "=E".$flag."*Q".$flag)->getStyle("BN{$flag}")->getNumberFormat()->setFormatCode('"$"#,##0.00_-');
								$hoja->setCellValue("BO{$flag}", "=E".$flag."*AC".$flag)->getStyle("BO{$flag}")->getNumberFormat()->setFormatCode('"$"#,##0.00_-');
								$hoja->setCellValue("BP{$flag}", "=E".$flag."*AH".$flag)->getStyle("BP{$flag}")->getNumberFormat()->setFormatCode('"$"#,##0.00_-');
								$hoja->setCellValue("BQ{$flag}", "=E".$flag."*AM".$flag)->getStyle("BQ{$flag}")->getNumberFormat()->setFormatCode('"$"#,##0.00_-');
								$hoja->setCellValue("BR{$flag}", "=E".$flag."*AR".$flag)->getStyle("BR{$flag}")->getNumberFormat()->setFormatCode('"$"#,##0.00_-');
								$hoja->setCellValue("BS{$flag}", "=E".$flag."*AW".$flag)->getStyle("BS{$flag}")->getNumberFormat()->setFormatCode('"$"#,##0.00_-');
								$hoja->setCellValue("BT{$flag}", "=E".$flag."*BB".$flag)->getStyle("BT{$flag}")->getNumberFormat()->setFormatCode('"$"#,##0.00_-');
								$hoja->setCellValue("BU{$flag}", "=E".$flag."*BG".$flag)->getStyle("BU{$flag}")->getNumberFormat()->setFormatCode('"$"#,##0.00_-');
								$hoja->setCellValue("BV{$flag}", "=E".$flag."*BL".$flag)->getStyle("BV{$flag}")->getNumberFormat()->setFormatCode('"$"#,##0.00_-');
								$this->cellStyle("BW{$flag}", "D4EAEF", "000000", FALSE, 12, "Franklin Gothic Book");
								$hoja->setCellValue("BW{$flag}", "=SUM(BN".$flag.":BV".$flag.")")->getStyle("BW{$flag}")->getNumberFormat()->setFormatCode('"$"#,##0.00_-');
								$this->cellStyle("BX{$flag}", "C2B90A", "000000", FALSE, 12, "Franklin Gothic Book");
								$hoja->setCellValue("BX{$flag}", "=Q".$flag."+AC".$flag."+AH".$flag."+AM".$flag."+AR".$flag."+AW".$flag."+BB".$flag."+BG".$flag."+BL".$flag."");

								//Begin: TOTALES PEDIDOS PENDIENTES
								$hoja->setCellValue("CD{$flag}", "=E".$flag."*O".$flag)->getStyle("CD{$flag}")->getNumberFormat()->setFormatCode('"$"#,##0.00_-');
								$hoja->setCellValue("CE{$flag}", "=E".$flag."*AA".$flag)->getStyle("CE{$flag}")->getNumberFormat()->setFormatCode('"$"#,##0.00_-');
								$hoja->setCellValue("CF{$flag}", "=E".$flag."*AF".$flag)->getStyle("CF{$flag}")->getNumberFormat()->setFormatCode('"$"#,##0.00_-');
								$hoja->setCellValue("CG{$flag}", "=E".$flag."*AK".$flag)->getStyle("CG{$flag}")->getNumberFormat()->setFormatCode('"$"#,##0.00_-');
								$hoja->setCellValue("CH{$flag}", "=E".$flag."*AP".$flag)->getStyle("CH{$flag}")->getNumberFormat()->setFormatCode('"$"#,##0.00_-');
								$hoja->setCellValue("CI{$flag}", "=E".$flag."*AU".$flag)->getStyle("CI{$flag}")->getNumberFormat()->setFormatCode('"$"#,##0.00_-');
								$hoja->setCellValue("CJ{$flag}", "=E".$flag."*AZ".$flag)->getStyle("CJ{$flag}")->getNumberFormat()->setFormatCode('"$"#,##0.00_-');
								$hoja->setCellValue("CK{$flag}", "=E".$flag."*BE".$flag)->getStyle("CK{$flag}")->getNumberFormat()->setFormatCode('"$"#,##0.00_-');
								$hoja->setCellValue("CL{$flag}", "=E".$flag."*BJ".$flag)->getStyle("CL{$flag}")->getNumberFormat()->setFormatCode('"$"#,##0.00_-');
								$this->cellStyle("CM{$flag}", "000000", "FFFFFF", FALSE, 12, "Franklin Gothic Book");
								$hoja->setCellValue("CM{$flag}", "=SUM(CD".$flag.":CL".$flag.")")->getStyle("CM{$flag}")->getNumberFormat()->setFormatCode('"$"#,##0.00_-');
								$this->cellStyle("CN{$flag}", "000000", "FFFFFF", FALSE, 12, "Franklin Gothic Book");
								$hoja->setCellValue("CN{$flag}", "=O".$flag."+AA".$flag."+AF".$flag."+AK".$flag."+AP".$flag."+AU".$flag."+AZ".$flag."+BE".$flag."+BJ".$flag."");
								//End: TOTALES PEDIDOS PENDIENTES
								$border_style= array('borders' => array('right' => array('style' =>
									PHPExcel_Style_Border::BORDER_THIN,'color' => array('argb' => '000000'),)));
								$this->excelfile->setActiveSheetIndex(1);
								
								$this->excelfile->getActiveSheet()->getStyle('A'.$flag.':BX'.$flag)->applyFromArray($styleArray);
								$this->excelfile->getActiveSheet()->getStyle('BW'.$flag.':BX'.$flag)->applyFromArray($styleArray);
								if($row['precio_sistema'] == 0){
									$row['precio_sistema'] = 1;
								}
								if($row['precio_four'] == 0){
									$row['precio_four'] = 1;
								}
									$hoja->setCellValue("G{$flag}",100 - ($row['precio_first'] * 100 / $row['precio_sistema']))->getStyle("G{$flag}")->getNumberFormat()->setFormatCode('"%"#,##0.00_-');;
									$this->cellStyle("G".$flag, "FF9999", "000000", FALSE, 10, "Franklin Gothic Book");

									$hoja->setCellValue("I{$flag}", 100 - ($row['precio_sistema'] * 100 / $row['precio_four']))->getStyle("I{$flag}")->getNumberFormat()->setFormatCode('"%"#,##0.00_-');;
									$this->cellStyle("I".$flag, "FF9999", "000000", FALSE, 10, "Franklin Gothic Book");
								
								$this->excelfile->setActiveSheetIndex(0);
								$this->excelfile->getActiveSheet()->getStyle('A'.$flag1.':P'.$flag1)->applyFromArray($styleArray);
								$hoja->getStyle("A{$flag}:I{$flag}")
						                 ->getAlignment()
						                 ->setHorizontal(\PHPExcel_Style_Alignment::HORIZONTAL_LEFT);

						   
								$flag ++;
								$flag1 ++;
							}
						}
					}
					$flagf = $flag;
					$flagfs = $flag - 1;
					$hoja->setCellValue("BN{$flagf}", "=SUM(BN5:BN".$flagfs.")")->getStyle("BN{$flag}")->getNumberFormat()->setFormatCode('"$"#,##0.00_-');
					$hoja->setCellValue("BO{$flagf}", "=SUM(BO5:BO".$flagfs.")")->getStyle("BO{$flag}")->getNumberFormat()->setFormatCode('"$"#,##0.00_-');
					$hoja->setCellValue("BP{$flagf}", "=SUM(BP5:BP".$flagfs.")")->getStyle("BP{$flag}")->getNumberFormat()->setFormatCode('"$"#,##0.00_-');
					$hoja->setCellValue("BQ{$flagf}", "=SUM(BQ5:BQ".$flagfs.")")->getStyle("BQ{$flag}")->getNumberFormat()->setFormatCode('"$"#,##0.00_-');
					$hoja->setCellValue("BR{$flagf}", "=SUM(BR5:BR".$flagfs.")")->getStyle("BR{$flag}")->getNumberFormat()->setFormatCode('"$"#,##0.00_-');
					$hoja->setCellValue("BS{$flagf}", "=SUM(BS5:BS".$flagfs.")")->getStyle("BS{$flag}")->getNumberFormat()->setFormatCode('"$"#,##0.00_-');
					$hoja->setCellValue("BT{$flagf}", "=SUM(BT5:BT".$flagfs.")")->getStyle("BT{$flag}")->getNumberFormat()->setFormatCode('"$"#,##0.00_-');
					$hoja->setCellValue("BU{$flagf}", "=SUM(BU5:BU".$flagfs.")")->getStyle("BU{$flag}")->getNumberFormat()->setFormatCode('"$"#,##0.00_-');
					$hoja->setCellValue("BV{$flagf}", "=SUM(BV5:BV".$flagfs.")")->getStyle("BV{$flag}")->getNumberFormat()->setFormatCode('"$"#,##0.00_-');
					$this->cellStyle("BW{$flagf}", "000000", "FFFFFF", FALSE, 12, "Franklin Gothic Book");
					$hoja->setCellValue("BW{$flagf}", "=SUM(BW5:BW".$flagfs.")")->getStyle("BW{$flagf}")->getNumberFormat()->setFormatCode('"$"#,##0.00_-');
					$sumall[1] .= "BN".$flagf."+";
					$sumall[2] .= "BO".$flagf."+";
					$sumall[3] .= "BP".$flagf."+";
					$sumall[4] .= "BQ".$flagf."+";
					$sumall[5] .= "BR".$flagf."+";
					$sumall[6] .= "BS".$flagf."+";
					$sumall[7] .= "BT".$flagf."+";
					$sumall[8] .= "BU".$flagf."+";
					$sumall[9] .= "BV".$flagf."+";
					$sumall[10] .= "BW".$flagf."+";
					//Begin: TOTALES PEDIDOS PENDIENTES
					$hoja->setCellValue("CD{$flag}", "=SUM(CD".$flage.":CD".$flagfs.")")->getStyle("CD{$flag}")->getNumberFormat()->setFormatCode('"$"#,##0.00_-');
					$hoja->setCellValue("CE{$flag}", "=SUM(CE".$flage.":CE".$flagfs.")")->getStyle("CE{$flag}")->getNumberFormat()->setFormatCode('"$"#,##0.00_-');
					$hoja->setCellValue("CF{$flag}", "=SUM(CF".$flage.":CF".$flagfs.")")->getStyle("CF{$flag}")->getNumberFormat()->setFormatCode('"$"#,##0.00_-');
					$hoja->setCellValue("CG{$flag}", "=SUM(CG".$flage.":CG".$flagfs.")")->getStyle("CG{$flag}")->getNumberFormat()->setFormatCode('"$"#,##0.00_-');
					$hoja->setCellValue("CH{$flag}", "=SUM(CH".$flage.":CH".$flagfs.")")->getStyle("CH{$flag}")->getNumberFormat()->setFormatCode('"$"#,##0.00_-');
					$hoja->setCellValue("CI{$flag}", "=SUM(CI".$flage.":CI".$flagfs.")")->getStyle("CI{$flag}")->getNumberFormat()->setFormatCode('"$"#,##0.00_-');
					$hoja->setCellValue("CJ{$flag}", "=SUM(CJ".$flage.":CJ".$flagfs.")")->getStyle("CJ{$flag}")->getNumberFormat()->setFormatCode('"$"#,##0.00_-');
					$hoja->setCellValue("CK{$flag}", "=SUM(CK".$flage.":CK".$flagfs.")")->getStyle("CK{$flag}")->getNumberFormat()->setFormatCode('"$"#,##0.00_-');
					$hoja->setCellValue("CL{$flag}", "=SUM(CL".$flage.":CL".$flagfs.")")->getStyle("CL{$flag}")->getNumberFormat()->setFormatCode('"$"#,##0.00_-');
					$hoja->setCellValue("CM{$flag}", "=SUM(CM".$flage.":CM".$flagfs.")")->getStyle("CM{$flag}")->getNumberFormat()->setFormatCode('"$"#,##0.00_-');
					//End: TOTALES PEDIDOS PENDIENTES
					
					$flag++;
					$flag1++;
					$flag++;
					$flag1++;
					$flag1++;
					$flag1++;
					$flag++;
					$flag++;
				}
			}
		}
		$flag++;
		$bandera = $flag;
		
		$hoja->mergeCells('G'.$flag.':J'.$flag);
		$this->cellStyle("G".$flag, "000000", "FFFFFF", TRUE, 12, "Franklin Gothic Book");
		$hoja->setCellValue("G".$flag, "TOTALES PENDIENTES '".$provname."'");
		$flag++;
		$hoja->mergeCells('G'.$flag.':H'.$flag);
		$hoja->mergeCells('I'.$flag.':J'.$flag);
		$this->cellStyle("G".$flag, "000000", "FFFFFF", TRUE, 12, "Franklin Gothic Book");
		$hoja->setCellValue("G".$flag, "CEDIS");
		$hoja->setCellValue("I{$flag}", "=CD{$flagf}")->getStyle("I{$flag}")->getNumberFormat()->setFormatCode('"$"#,##0.00_-');
		$flag++;
		$hoja->mergeCells('G'.$flag.':H'.$flag);
		$hoja->mergeCells('I'.$flag.':J'.$flag);
		$this->cellStyle("G".$flag, "000000", "FFFFFF", TRUE, 12, "Franklin Gothic Book");
		$hoja->setCellValue("G".$flag, "ABARROTES");
		$hoja->setCellValue("I{$flag}", "=CE{$flagf}")->getStyle("I{$flag}")->getNumberFormat()->setFormatCode('"$"#,##0.00_-');
		$flag++;
		$hoja->mergeCells('G'.$flag.':H'.$flag);
		$hoja->mergeCells('I'.$flag.':J'.$flag);
		$this->cellStyle("G".$flag, "000000", "FFFFFF", TRUE, 12, "Franklin Gothic Book");
		$hoja->setCellValue("G".$flag, "VILLAS");
		$hoja->setCellValue("I{$flag}", "=CF{$flagf}")->getStyle("I{$flag}")->getNumberFormat()->setFormatCode('"$"#,##0.00_-');
		$flag++;
		$hoja->mergeCells('G'.$flag.':H'.$flag);
		$hoja->mergeCells('I'.$flag.':J'.$flag);
		$this->cellStyle("G".$flag, "000000", "FFFFFF", TRUE, 12, "Franklin Gothic Book");
		$hoja->setCellValue("G".$flag, "TIENDA");
		$hoja->setCellValue("I{$flag}", "=CG{$flagf}")->getStyle("I{$flag}")->getNumberFormat()->setFormatCode('"$"#,##0.00_-');
		$flag++;
		$hoja->mergeCells('G'.$flag.':H'.$flag);
		$hoja->mergeCells('I'.$flag.':J'.$flag);
		$this->cellStyle("G".$flag, "000000", "FFFFFF", TRUE, 12, "Franklin Gothic Book");
		$hoja->setCellValue("G".$flag, "ULTRAMARINOS");
		$hoja->setCellValue("I{$flag}", "=CH{$flagf}")->getStyle("I{$flag}")->getNumberFormat()->setFormatCode('"$"#,##0.00_-');
		$flag++;
		$hoja->mergeCells('G'.$flag.':H'.$flag);
		$hoja->mergeCells('I'.$flag.':J'.$flag);
		$this->cellStyle("G".$flag, "000000", "FFFFFF", TRUE, 12, "Franklin Gothic Book");
		$hoja->setCellValue("G".$flag, "TRINCHERAS");
		$hoja->setCellValue("I{$flag}", "=CI{$flagf}")->getStyle("I{$flag}")->getNumberFormat()->setFormatCode('"$"#,##0.00_-');
		$flag++;
		$hoja->mergeCells('G'.$flag.':H'.$flag);
		$hoja->mergeCells('I'.$flag.':J'.$flag);
		$this->cellStyle("G".$flag, "000000", "FFFFFF", TRUE, 12, "Franklin Gothic Book");
		$hoja->setCellValue("G".$flag, "AZT MERCADO");
		$hoja->setCellValue("I{$flag}", "=CJ{$flagf}")->getStyle("I{$flag}")->getNumberFormat()->setFormatCode('"$"#,##0.00_-');
		$flag++;
		$hoja->mergeCells('G'.$flag.':H'.$flag);
		$hoja->mergeCells('I'.$flag.':J'.$flag);
		$this->cellStyle("G".$flag, "000000", "FFFFFF", TRUE, 12, "Franklin Gothic Book");
		$hoja->setCellValue("G".$flag, "TENENCIA");
		$hoja->setCellValue("I{$flag}", "=CK{$flagf}")->getStyle("I{$flag}")->getNumberFormat()->setFormatCode('"$"#,##0.00_-');
		$flag++;
		$hoja->mergeCells('G'.$flag.':H'.$flag);
		$hoja->mergeCells('I'.$flag.':J'.$flag);
		$this->cellStyle("G".$flag, "000000", "FFFFFF", TRUE, 12, "Franklin Gothic Book");
		$hoja->setCellValue("G".$flag, "TIJERAS");
		$hoja->setCellValue("I{$flag}", "=CL{$flagf}")->getStyle("I{$flag}")->getNumberFormat()->setFormatCode('"$"#,##0.00_-');
		$flag++;
		$hoja->mergeCells('G'.$flag.':H'.$flag);
		$hoja->mergeCells('I'.$flag.':J'.$flag);
		$this->cellStyle("G".$flag, "000000", "FFFFFF", TRUE, 12, "Franklin Gothic Book");
		$hoja->setCellValue("G".$flag, "TOTAL");
		$hoja->setCellValue("I{$flag}", "=CM{$flagf}")->getStyle("I{$flag}")->getNumberFormat()->setFormatCode('"$"#,##0.00_-');
		$flag = $bandera;

		$hoja->mergeCells('B'.$flag.':C'.$flag);
		$this->cellStyle("B".$flag, "000000", "FFFFFF", TRUE, 12, "Franklin Gothic Book");
		$hoja->setCellValue("B".$flag, "TOTALES EN GENERAL");
		$flag++;
		$this->cellStyle("B".$flag, "C00000", "000000", TRUE, 12, "Franklin Gothic Book");
		$hoja->setCellValue("B".$flag, "CEDIS");
		$hoja->setCellValue("C{$flag}", "=(".substr($sumall[1],0,-1).")")->getStyle("C{$flag}")->getNumberFormat()->setFormatCode('"$"#,##0.00_-');
		$flag++;
		$this->cellStyle("B".$flag, "01B0F0", "000000", TRUE, 12, "Franklin Gothic Book");
		$hoja->setCellValue("B".$flag, "ABARROTES");
		$hoja->setCellValue("C{$flag}", "=(".substr($sumall[2],0,-1).")")->getStyle("C{$flag}")->getNumberFormat()->setFormatCode('"$"#,##0.00_-');
		$flag++;
		$this->cellStyle("B".$flag, "FF0000", "000000", TRUE, 12, "Franklin Gothic Book");
		$hoja->setCellValue("B".$flag, "VILLAS");
		$hoja->setCellValue("C{$flag}", "=(".substr($sumall[3],0,-1).")")->getStyle("C{$flag}")->getNumberFormat()->setFormatCode('"$"#,##0.00_-');
		$flag++;
		$this->cellStyle("B".$flag, "E26C0B", "000000", TRUE, 12, "Franklin Gothic Book");
		$hoja->setCellValue("B".$flag, "TIENDA");
		$hoja->setCellValue("C{$flag}", "=(".substr($sumall[4],0,-1).")")->getStyle("C{$flag}")->getNumberFormat()->setFormatCode('"$"#,##0.00_-');
		$flag++;
		$this->cellStyle("B".$flag, "C5C5C5", "000000", TRUE, 12, "Franklin Gothic Book");
		$hoja->setCellValue("B".$flag, "ULTRAMARINOS");
		$hoja->setCellValue("C{$flag}", "=(".substr($sumall[5],0,-1).")")->getStyle("C{$flag}")->getNumberFormat()->setFormatCode('"$"#,##0.00_-');
		$flag++;
		$this->cellStyle("B".$flag, "92D051", "000000", TRUE, 12, "Franklin Gothic Book");
		$hoja->setCellValue("B".$flag, "TRINCHERAS");
		$hoja->setCellValue("C{$flag}", "=(".substr($sumall[6],0,-1).")")->getStyle("C{$flag}")->getNumberFormat()->setFormatCode('"$"#,##0.00_-');
		$flag++;
		$this->cellStyle("B".$flag, "B1A0C7", "000000", TRUE, 12, "Franklin Gothic Book");
		$hoja->setCellValue("B".$flag, "AZT MERCADO");
		$hoja->setCellValue("C{$flag}", "=(".substr($sumall[7],0,-1).")")->getStyle("C{$flag}")->getNumberFormat()->setFormatCode('"$"#,##0.00_-');
		$flag++;
		$this->cellStyle("B".$flag, "DA9694", "000000", TRUE, 12, "Franklin Gothic Book");
		$hoja->setCellValue("B".$flag, "TENENCIA");
		$hoja->setCellValue("C{$flag}", "=(".substr($sumall[8],0,-1).")")->getStyle("C{$flag}")->getNumberFormat()->setFormatCode('"$"#,##0.00_-');
		$flag++;
		$this->cellStyle("B".$flag, "4CACC6", "000000", TRUE, 12, "Franklin Gothic Book");
		$hoja->setCellValue("B".$flag, "TIJERAS");
		$hoja->setCellValue("C{$flag}", "=(".substr($sumall[9],0,-1).")")->getStyle("C{$flag}")->getNumberFormat()->setFormatCode('"$"#,##0.00_-');
		$flag++;
		$this->cellStyle("B".$flag, "000000", "FFFFFF", TRUE, 12, "Franklin Gothic Book");
		$hoja->setCellValue("B".$flag, "TOTAL");
		$hoja->setCellValue("C{$flag}", "=(".substr($sumall[10],0,-1).")")->getStyle("C{$flag}")->getNumberFormat()->setFormatCode('"$"#,##0.00_-');
		$flag++;

		$dias = array("DOMINGO","LUNES","MARTES","MIÉRCOLES","JUEVES","VIERNES","SÁBADO");
		$meses = array("ENERO","FEBRERO","MARZO","ABRIL","MAYO","JUNIO","JULIO","AGOSTO","SEPTIEMBRE","OCTUBRE","NOVIEMBRE","DICIEMBRE");
		$fecha =  $dias[date('w')]." ".date('d')." DE ".$meses[date('n')-1]. " DEL ".date('Y') ;
		$file_name = "FORMATO MODERNA ".$fecha.".xlsx"; //Nombre del documento con extención
		header("Content-Type: application/vnd.ms-excel; charset=utf-8");
		header("Content-Disposition: attachment;filename=".$file_name);
		header("Cache-Control: max-age=0");
		$excel_Writer = PHPExcel_IOFactory::createWriter($this->excelfile, "Excel2007");
		$excel_Writer->save("php://output");
		/*$excel_Writer = new PHPExcel_Writer_Excel2007($this->excelfile);
		$excel_Writer->setOffice2003Compatibility(true);
		$excel_Writer->save("php://output");*/
	}

	private function fill_costena($id_proves,$proves,$prs){
		$flag =1;
		$flag1 = 1;
		$array = "";
		$array2 = "";
		$filenam = "";
		$id_proves = $this->input->post('id_proves4');
		$proves = $this->input->post('id_proves2');
		$prs = substr($id_proves,0,6);
		$filenam = $id_proves;
		$array = (object)['0'=>(object)['nombre' => $id_proves]];
		//$this->jsonResponse($array);
		ini_set("memory_limit", "-1");
		ini_set("max_execution_time", "-1");
		$this->load->library("excelfile");
		$hoja1 = $this->excelfile->setActiveSheetIndex(0);
		$this->excelfile->setActiveSheetIndex(0)->setTitle("EXISTENCIAS");
		$this->excelfile->createSheet();
        $hoja = $this->excelfile->setActiveSheetIndex(1);
        $hoja->setTitle("PEDIDO");
		$styleArray = array(
		  'borders' => array(
		    'allborders' => array(
		      'style' => PHPExcel_Style_Border::BORDER_THIN
		    )
		  )
		);
		$hoja->getColumnDimension('A')->setWidth("20");
		$hoja->getColumnDimension('B')->setWidth("70");
		$hoja->getColumnDimension('C')->setWidth("15");
		$hoja->getColumnDimension('D')->setWidth("8");

		$hoja->getColumnDimension('E')->setWidth("15");
		$hoja->getColumnDimension('G')->setWidth("8");
		$hoja->getColumnDimension('I')->setWidth("8");
		$hoja->getColumnDimension('F')->setWidth("20");
		$hoja->getColumnDimension('H')->setWidth("15");

		$hoja1->getColumnDimension('A')->setWidth("6");
		$hoja1->getColumnDimension('B')->setWidth("6");
		$hoja1->getColumnDimension('C')->setWidth("6");
		$hoja1->getColumnDimension('D')->setWidth("25");
		$hoja1->getColumnDimension('E')->setWidth("47");
		$hoja1->getColumnDimension('G')->setWidth("50");

		$hoja->getColumnDimension('BM' )->setWidth("70");
		$hoja->getColumnDimension('L')->setWidth("20");

		$flagBorder = 0;
		$flagBorder1 = 1;
		$flagBorder2 = 0;
		$flagBorder3 = 1;
		$flage = 5;
		$i = 0;
		$sumall = array(1 => "", 2 => "", 3 => "", 4 => "", 5 => "", 6 => "", 7 => "", 8 => "", 9 => "", 10 => "");
		$provname = "";
		if ($array){
			foreach ($array as $key => $value){
				$fecha = new DateTime(date('Y-m-d H:i:s'));
				$intervalo = new DateInterval('P2D');
				$fecha->add($intervalo);
				$cotizacionesProveedor = $this->ct_mdl->getPedidosAllCostena(NULL, $fecha->format('Y-m-d H:i:s'), 0);
			
				$difff = 0.01;
				$flag2 = 3;
				$cargo = "";
				if ($cotizacionesProveedor){
					//HOJA EXISTENCIAS
					$this->excelfile->setActiveSheetIndex(0);
					if($i > 0){
						$flagBorder = $flag1 ;
						$this->excelfile->getActiveSheet()->getStyle('A'.$flagBorder1.':G'.$flagBorder)->applyFromArray($styleArray);
						$flagBorder1 = $flag1;
					}
					$hoja1->mergeCells('A'.$flag1.':G'.$flag1);
					$this->cellStyle("A".$flag1, "FFFFFF", "000000", TRUE, 12, "Franklin Gothic Book");
					$hoja1->setCellValue("A".$flag1."", "GRUPO ABARROTES AZTECA");
					$this->excelfile->getActiveSheet()->getStyle('A'.$flag1.':G'.$flag1)->applyFromArray($styleArray);
					$flag1++;
					$hoja1->mergeCells('A'.$flag1.':G'.$flag1);
					$this->cellStyle("A".$flag1, "FFFFFF", "000000", TRUE, 12, "Franklin Gothic Book");
					$hoja1->setCellValue("A".$flag1."", "PEDIDOS A '".$value->nombre."' ".date("d-m-Y"));
					$provname = $value->nombre;
					$this->excelfile->getActiveSheet()->getStyle('A'.$flag1.':G'.$flag1)->applyFromArray($styleArray);
					$flag1++;
					$this->cellStyle("A".$flag1.":D".$flag1, "000000", "FFFFFF", TRUE, 12, "Franklin Gothic Book");
					$hoja1->mergeCells('A'.$flag1.':B'.$flag1);
					$hoja1->setCellValue("A".$flag1, "EXISTENCIAS");
					$hoja1->setCellValue("E".$flag1, "DESCRIPCIÓN");
					$this->cellStyle("E".$flag1, "000000", "FFFFFF", TRUE, 12, "Franklin Gothic Book");

					$this->cellStyle("H".$flag1."", "000000", "FFFFFF", TRUE, 12, "Franklin Gothic Book");
					$hoja1->setCellValue("H".$flag1."", "PENDIENT");
					$this->cellStyle("I".$flag1."", "000000", "FFFFFF", TRUE, 12, "Franklin Gothic Book");
					$hoja1->setCellValue("I".$flag1."", "PENDIENT");
					$this->cellStyle("J".$flag1."", "000000", "FFFFFF", TRUE, 12, "Franklin Gothic Book");
					$hoja1->setCellValue("J".$flag1."", "PENDIENT");
					$this->cellStyle("K".$flag1."", "000000", "FFFFFF", TRUE, 12, "Franklin Gothic Book");
					$hoja1->setCellValue("K".$flag1."", "PENDIENT");
					$this->cellStyle("L".$flag1."", "000000", "FFFFFF", TRUE, 12, "Franklin Gothic Book");
					$hoja1->setCellValue("L".$flag1."", "PENDIENT");
					$this->cellStyle("M".$flag1."", "000000", "FFFFFF", TRUE, 12, "Franklin Gothic Book");
					$hoja1->setCellValue("M".$flag1."", "PENDIENT");
					$this->cellStyle("N".$flag1."", "000000", "FFFFFF", TRUE, 12, "Franklin Gothic Book");
					$hoja1->setCellValue("N".$flag1."", "PENDIENT");
					$this->cellStyle("O".$flag1."", "000000", "FFFFFF", TRUE, 12, "Franklin Gothic Book");
					$hoja1->setCellValue("O".$flag1."", "PENDIENT");
					$this->cellStyle("P".$flag1."", "000000", "FFFFFF", TRUE, 12, "Franklin Gothic Book");
					$hoja1->setCellValue("P".$flag1."", "PENDIENT");
					$this->excelfile->getActiveSheet()->getStyle('A'.$flag1.':P'.$flag1)->applyFromArray($styleArray);
					$flag1++;
					$this->cellStyle("A".$flag1.":G".$flag1, "000000", "FFFFFF", TRUE, 12, "Franklin Gothic Book");
					$hoja1->setCellValue("A".$flag1, "CAJAS");
					$hoja1->setCellValue("B".$flag1, "PZAS");
					$hoja1->setCellValue("C".$flag1, "PEDIDO");
					$hoja1->setCellValue("D".$flag1, "CÓDIGO");
					$hoja1->setCellValue("G".$flag1, "PROMOCIÓN");
					$hoja1->setCellValue("F".$flag1, "IMAGEN")->getColumnDimension('F')->setWidth(18);
					
					$this->cellStyle("H".$flag1, "C00000", "000000", TRUE, 10, "Franklin Gothic Book");
					$this->cellStyle("I".$flag1, "01B0F0", "000000", TRUE, 10, "Franklin Gothic Book");
					$this->cellStyle("J".$flag1, "FF0000", "000000", TRUE, 10, "Franklin Gothic Book");
					$this->cellStyle("K".$flag1, "E26C0B", "000000", TRUE, 10, "Franklin Gothic Book");
					$this->cellStyle("L".$flag1, "C5C5C5", "000000", TRUE, 10, "Franklin Gothic Book");
					$this->cellStyle("M".$flag1, "92D051", "000000", TRUE, 10, "Franklin Gothic Book");
					$this->cellStyle("N".$flag1, "B1A0C7", "000000", TRUE, 10, "Franklin Gothic Book");
					$this->cellStyle("O".$flag1, "DA9694", "000000", TRUE, 10, "Franklin Gothic Book");
					$this->cellStyle("P".$flag1, "4CACC6", "000000", TRUE, 10, "Franklin Gothic Book");
					$hoja1->setCellValue("H".$flag1."", "CEDIS");
					$hoja1->setCellValue("I".$flag1."", "ABARROTES");
					$hoja1->setCellValue("J".$flag1."", "VILLAS");
					$hoja1->setCellValue("K".$flag1."", "TIENDA");
					$hoja1->setCellValue("L".$flag1."", "ULTRA");
					$hoja1->setCellValue("M".$flag1."", "TRINCHERAS");
					$hoja1->setCellValue("N".$flag1."", "MERCADO");
					$hoja1->setCellValue("O".$flag1."", "TENENCIA");
					$hoja1->setCellValue("P".$flag1."", "TIJERAS");
					$this->excelfile->getActiveSheet()->getStyle('A'.$flag1.':P'.$flag1)->applyFromArray($styleArray);
					//$flag1++;
					$this->excelfile->setActiveSheetIndex(1);
					if($i > 0){
						$flagBorder2 = $flag ;
						$this->excelfile->getActiveSheet()->getStyle('A'.$flagBorder3.':AD'.$flagBorder2)->applyFromArray($styleArray);
						$flagBorder3 = $flag;
					}


					//HOJA PEDIDOS
					$this->cellStyle("A".$flag, "FFFFFF", "000000", TRUE, 12, "Franklin Gothic Book");
					$hoja->setCellValue("A".$flag."", "CEDIS, ABARROTES,PEDREGAL, TIENDA, ULTRAMARINOS, TRINCHERAS, MERCADO, TIJERAS, Y TENENCIA AZTECA AUTOSERVICIOS SA. DE CV.");
					$hoja->mergeCells('A'.$flag.':BM'.$flag);
					$this->excelfile->getActiveSheet()->getStyle('A'.$flag.':BM'.$flag)->applyFromArray($styleArray);
					$flag++;
					$hoja->mergeCells('A'.$flag.':L'.$flag);
					$hoja->mergeCells('M'.$flag.':Q'.$flag);
					$hoja->mergeCells('R'.$flag.':U'.$flag);
					$hoja->mergeCells('V'.$flag.':X'.$flag);
					$hoja->mergeCells('Y'.$flag.':AC'.$flag);
					$hoja->mergeCells('AD'.$flag.':AH'.$flag);
					$hoja->mergeCells('AI'.$flag.':AM'.$flag);
					$hoja->mergeCells('AN'.$flag.':AR'.$flag);
					$hoja->mergeCells('AS'.$flag.':AW'.$flag);
					$hoja->mergeCells('AX'.$flag.':BB'.$flag);
					$hoja->mergeCells('BC'.$flag.':BG'.$flag);
					$hoja->mergeCells('BH'.$flag.':BL'.$flag);

					$this->cellStyle("B".$flag, "FFFFFF", "000000", TRUE, 12, "Franklin Gothic Book");
					$hoja->setCellValue("B".$flag, "PEDIDOS A '".$value->nombre."' ".date("d-m-Y"));
					$this->cellStyle("M".$flag, "C00000", "000000", TRUE, 12, "Franklin Gothic Book");
					$hoja->setCellValue("M".$flag, "CEDIS");
					$hoja->setCellValue("R".$flag, "CD INDUSTRIAL");
					$this->cellStyle("R".$flag, "FF0066", "000000", TRUE, 12, "Franklin Gothic Book");
					$this->cellStyle("V".$flag, "C2B90A", "000000", TRUE, 12, "Franklin Gothic Book");
					$hoja->setCellValue("V".$flag, "SUMA CEDIS");
					$this->cellStyle("Y".$flag, "01B0F0", "000000", TRUE, 12, "Franklin Gothic Book");
					$hoja->setCellValue("Y".$flag, "ABARROTES");
					$this->cellStyle("AD".$flag, "FF0000", "000000", TRUE, 12, "Franklin Gothic Book");
					$hoja->setCellValue("AD".$flag, "VILLAS");
					$this->cellStyle("AI".$flag, "E26C0B", "000000", TRUE, 12, "Franklin Gothic Book");
					$hoja->setCellValue("AI".$flag, "TIENDA");
					$this->cellStyle("AN".$flag, "C5C5C5", "000000", TRUE, 12, "Franklin Gothic Book");
					$hoja->setCellValue("AN".$flag, "ULTRAMARINOS");
					$this->cellStyle("AS".$flag, "92D051", "000000", TRUE, 12, "Franklin Gothic Book");
					$hoja->setCellValue("AS".$flag, "TRINCHERAS");
					$this->cellStyle("AX".$flag, "B1A0C7", "000000", TRUE, 12, "Franklin Gothic Book");
					$hoja->setCellValue("AX".$flag, "AZT MERCADO");
					$this->cellStyle("BC".$flag, "DA9694", "000000", TRUE, 12, "Franklin Gothic Book");
					$hoja->setCellValue("BC".$flag, "TENENCIA");
					$this->cellStyle("BH".$flag, "4CACC6", "000000", TRUE, 12, "Franklin Gothic Book");
					$hoja->setCellValue("BH".$flag, "TIJERAS");
					
					$this->cellStyle("A3:BM4", "000000", "FFFFFF", TRUE, 12, "Franklin Gothic Book");
					
					$this->excelfile->getActiveSheet()->getStyle('A'.$flag.':BM'.$flag)->applyFromArray($styleArray);
					$flag++;
					$this->cellStyle("A".$flag.":BM".$flag."", "000000", "FFFFFF", TRUE, 12, "Franklin Gothic Book");
					$hoja->mergeCells('A'.$flag.':L'.$flag);
					$hoja->mergeCells('M'.$flag.':Q'.$flag);
					$hoja->mergeCells('R'.$flag.':U'.$flag);
					$hoja->mergeCells('V'.$flag.':X'.$flag);
					$hoja->mergeCells('Y'.$flag.':AC'.$flag);
					$hoja->mergeCells('AD'.$flag.':AH'.$flag);
					$hoja->mergeCells('AI'.$flag.':AM'.$flag);
					$hoja->mergeCells('AN'.$flag.':AR'.$flag);
					$hoja->mergeCells('AS'.$flag.':AW'.$flag);
					$hoja->mergeCells('AX'.$flag.':BB'.$flag);
					$hoja->mergeCells('BC'.$flag.':BG'.$flag);
					$hoja->mergeCells('BH'.$flag.':BL'.$flag);
					$hoja->setCellValue("A".$flag, "DESCRIPCIÓN");
					$hoja->setCellValue("M".$flag, "EXISTENCIAS");
					$hoja->setCellValue("R".$flag, " SUMA EXISTENCIAS");
					$hoja->setCellValue("V".$flag, "EXISTENCIAS");
					$hoja->setCellValue("Y".$flag, "EXISTENCIAS");
					$hoja->setCellValue("AD".$flag, "EXISTENCIAS");
					$hoja->setCellValue("AI".$flag, "EXISTENCIAS");
					$hoja->setCellValue("AN".$flag, "EXISTENCIAS");
					$hoja->setCellValue("AS".$flag, "EXISTENCIAS");
					$hoja->setCellValue("AX".$flag, "EXISTENCIAS");
					$hoja->setCellValue("BC".$flag, "EXISTENCIAS");
					$hoja->setCellValue("BH".$flag, "EXISTENCIAS");
					$this->excelfile->getActiveSheet()->getStyle('A'.$flag.':BM'.$flag)->applyFromArray($styleArray);
					//Begin: TOTALES PEDIDOS PENDIENTES
					$hoja->mergeCells('CD'.$flag.':CN'.$flag);
					$this->cellStyle("CD".$flag.":CN".$flag."", "000000", "FFFFFF", TRUE, 12, "Franklin Gothic Book");
					$hoja->setCellValue("CD".$flag, "TOTAL POR PEDIDOS PENDIENTES");
					//End: TOTALES PEDIDOS PENDIENTES
					$flag++;
					$this->cellStyle("A".$flag.":BM".$flag."", "000000", "FFFFFF", TRUE, 12, "Franklin Gothic Book");
					$hoja->setCellValue("A".$flag, "CODIGO");
					$hoja->setCellValue("C".$flag, "COD PROV")
					;
					$hoja->setCellValue("D".$flag, "UM");
					$hoja->setCellValue("E".$flag, "1ER");
					$hoja->setCellValue("F".$flag, "COSTO");
					$hoja->setCellValue("G".$flag, "DIF % 5 Y 1ER");
					$hoja->setCellValue("H".$flag, "SISTEMA");
					$hoja->setCellValue("I".$flag, "DIF % 5 Y 4");
					$hoja->setCellValue("J".$flag, "PRECIO4");
					$hoja->setCellValue("K".$flag, "2DO");
					$hoja->setCellValue("L".$flag, "PROVEEDOR");

					$hoja->setCellValue("M".$flag, "CAJAS");
					$hoja->setCellValue("N".$flag, "PZAS");
					$hoja->setCellValue("O".$flag, "PEND");
					$hoja->setCellValue("P".$flag, "STOCK");
					$hoja->setCellValue("Q".$flag, "PEDIDO");
					$hoja->setCellValue("R".$flag, "CAJAS");
					$hoja->setCellValue("S".$flag, "PZAS");
					$hoja->setCellValue("T".$flag, "STOCK");
					$hoja->setCellValue("U".$flag, "PEDIDO");
					$hoja->setCellValue("V".$flag, "CAJAS");
					$hoja->setCellValue("W".$flag, "PZAS");
					$hoja->setCellValue("X".$flag, "PEDIDO");
					$hoja->setCellValue("Y".$flag, "CAJAS");
					$hoja->setCellValue("Z".$flag, "PZAS");
					$hoja->setCellValue("AA".$flag, "PEND");
					$hoja->setCellValue("AB".$flag, "STOCK");
					$hoja->setCellValue("AC".$flag, "PEDIDO");
					$hoja->setCellValue("AD".$flag, "CAJAS");
					$hoja->setCellValue("AE".$flag, "PZAS");
					$hoja->setCellValue("AF".$flag, "PEND");
					$hoja->setCellValue("AG".$flag, "STOCK");
					$hoja->setCellValue("AH".$flag, "PEDIDO");
					$hoja->setCellValue("AI".$flag, "CAJAS");
					$hoja->setCellValue("AJ".$flag, "PZAS");
					$hoja->setCellValue("AK".$flag, "PEND");
					$hoja->setCellValue("AL".$flag, "STOCK");
					$hoja->setCellValue("AM".$flag, "PEDIDO");
					$hoja->setCellValue("AN".$flag, "CAJAS");
					$hoja->setCellValue("AO".$flag, "PZAS");
					$hoja->setCellValue("AP".$flag, "PEND");
					$hoja->setCellValue("AQ".$flag, "STOCK");
					$hoja->setCellValue("AR".$flag, "PEDIDO");
					$hoja->setCellValue("AS".$flag, "CAJAS");
					$hoja->setCellValue("AT".$flag, "PZAS");
					$hoja->setCellValue("AU".$flag, "PEND");
					$hoja->setCellValue("AV".$flag, "STOCK");
					$hoja->setCellValue("AW".$flag, "PEDIDO");
					$hoja->setCellValue("AX".$flag, "CAJAS");
					$hoja->setCellValue("AY".$flag, "PZAS");
					$hoja->setCellValue("AZ".$flag, "PEND");
					$hoja->setCellValue("BA".$flag, "STOCK");
					$hoja->setCellValue("BB".$flag, "PEDIDO");
					$hoja->setCellValue("BC".$flag, "CAJAS");
					$hoja->setCellValue("BD".$flag, "PZAS");
					$hoja->setCellValue("BE".$flag, "PEND");
					$hoja->setCellValue("BF".$flag, "STOCK");
					$hoja->setCellValue("BG".$flag, "PEDIDO");
					$hoja->setCellValue("BH".$flag, "CAJAS");
					$hoja->setCellValue("BI".$flag, "PZAS");
					$hoja->setCellValue("BJ".$flag, "PEND");
					$hoja->setCellValue("BK".$flag, "STOCK");
					$hoja->setCellValue("BL".$flag, "PEDIDO");
					$hoja->setCellValue("BM".$flag, "PROMOCIÓN");					
					
					
					$this->cellStyle("BN".$flag1, "C00000", "000000", TRUE, 10, "Franklin Gothic Book");
					$this->cellStyle("BO".$flag1, "01B0F0", "000000", TRUE, 10, "Franklin Gothic Book");
					$this->cellStyle("BP".$flag1, "FF0000", "000000", TRUE, 10, "Franklin Gothic Book");
					$this->cellStyle("BQ".$flag1, "E26C0B", "000000", TRUE, 10, "Franklin Gothic Book");
					$this->cellStyle("BR".$flag1, "C5C5C5", "000000", TRUE, 10, "Franklin Gothic Book");
					$this->cellStyle("BS".$flag1, "92D051", "000000", TRUE, 10, "Franklin Gothic Book");
					$this->cellStyle("BT".$flag1, "B1A0C7", "000000", TRUE, 10, "Franklin Gothic Book");
					$this->cellStyle("BU".$flag1, "DA9694", "000000", TRUE, 10, "Franklin Gothic Book");
					$this->cellStyle("BV".$flag1, "4CACC6", "000000", TRUE, 10, "Franklin Gothic Book");

					$hoja->setCellValue("BW".$flag, "TOTAL");
					$hoja->setCellValue("BX".$flag, "PEDIDOS");
					$this->cellStyle("BW".$flag."", "000000", "FFFFFF", TRUE, 12, "Franklin Gothic Book");
					$this->cellStyle("BX".$flag."", "000000", "FFFFFF", TRUE, 12, "Franklin Gothic Book");
					$this->excelfile->getActiveSheet()->getStyle('BW'.$flag)->applyFromArray($styleArray);
					$this->excelfile->getActiveSheet()->getStyle('BX'.$flag)->applyFromArray($styleArray);
					$this->excelfile->getActiveSheet()->getStyle('A'.$flag.':BM'.$flag)->applyFromArray($styleArray);

					//Begin: TOTALES PEDIDOS PENDIENTES
					$this->cellStyle("CD".$flag, "C00000", "000000", TRUE, 12, "Franklin Gothic Book");
					$this->cellStyle("CE".$flag, "01B0F0", "000000", TRUE, 12, "Franklin Gothic Book");
					$this->cellStyle("CF".$flag, "FF0000", "000000", TRUE, 12, "Franklin Gothic Book");
					$this->cellStyle("CG".$flag, "E26C0B", "000000", TRUE, 12, "Franklin Gothic Book");
					$this->cellStyle("CH".$flag, "C5C5C5", "000000", TRUE, 12, "Franklin Gothic Book");
					$this->cellStyle("CI".$flag, "92D051", "000000", TRUE, 12, "Franklin Gothic Book");
					$this->cellStyle("CJ".$flag, "B1A0C7", "000000", TRUE, 12, "Franklin Gothic Book");
					$this->cellStyle("CK".$flag, "DA9694", "000000", TRUE, 12, "Franklin Gothic Book");
					$this->cellStyle("CL".$flag, "4CACC6", "000000", TRUE, 12, "Franklin Gothic Book");
					$this->cellStyle("CM".$flag."", "000000", "FFFFFF", TRUE, 12, "Franklin Gothic Book");
					$this->cellStyle("CN".$flag."", "000000", "FFFFFF", TRUE, 12, "Franklin Gothic Book");
					$hoja->setCellValue("CM".$flag, "TOTAL");
					$hoja->setCellValue("CN".$flag, "PEDIDOS");
					//End: TOTALES PEDIDOS PENDIENTES
					foreach ($cotizacionesProveedor as $key => $value){
						//Existencias
						$this->excelfile->setActiveSheetIndex(0);
						$this->cellStyle("E".$flag1, "000000", "FFFFFF", FALSE, 12, "Franklin Gothic Book");
						$hoja1->setCellValue("E".$flag1, $value['familia']);
						$flag1 +=1;
						//Pedidos
						$this->excelfile->setActiveSheetIndex(1);
						$this->cellStyle("B".$flag, "000000", "FFFFFF", FALSE, 12, "Franklin Gothic Book");
						$hoja->setCellValue("B{$flag}", $value['familia']);
						$flag +=1;
						if ($value['articulos']) {
							foreach ($value['articulos'] as $key => $row){
								//Existencias
								$cargo = $row['cargo'];
								$registrazo = date('Y-m-d',strtotime($row['registrazo']));
								$this->excelfile->setActiveSheetIndex(0);
								$this->cellStyle("A".$flag1.":P".$flag1, "FFFFFF", "000000", FALSE, 12, "Franklin Gothic Book");
								
								$hoja1->setCellValue("D{$flag1}", $row['codigo'])->getStyle("D{$flag1}")->getNumberFormat()->setFormatCode('# ???/???');//Formato de fraccion
								if($row['color'] == '#92CEE3'){
									$this->cellStyle("D{$flag1}", "92CEE3", "000000", FALSE, 12, "Franklin Gothic Book");
								}else{
									$this->cellStyle("D{$flag1}", "FFFFFF", "000000", FALSE, 12, "Franklin Gothic Book");
								}
								$hoja1->setCellValue("E{$flag1}", $row['producto']);
								$hoja1->setCellValue("G{$flag1}", $row['promocion_first']);
								$hoja1->setCellValue("H{$flag1}", $row['cedis']);
								$hoja1->setCellValue("I{$flag1}", $row['abarrotes']);
								$hoja1->setCellValue("J{$flag1}", $row['pedregal']);
								$hoja1->setCellValue("K{$flag1}", $row['tienda']);
								$hoja1->setCellValue("L{$flag1}", $row['ultra']);
								$hoja1->setCellValue("M{$flag1}", $row['trincheras']);
								$hoja1->setCellValue("N{$flag1}", $row['mercado']);
								$hoja1->setCellValue("O{$flag1}", $row['tenencia']);
								$hoja1->setCellValue("P{$flag1}", $row['tijeras']);

								if ($row["imagen"] <> "" && !is_null($row["imagen"]) ) {
									$objDrawing = new PHPExcel_Worksheet_Drawing();
									$objDrawing->setName('COD'.$row['producto']);
									$objDrawing->setDescription('DESC'.$row['codigo']);
									$objDrawing->setPath("./Abarrotes/assets/img/productos/".$row["imagen"]."");
									$objDrawing->setWidth(50);
									$objDrawing->setHeight(50);
									$objDrawing->setCoordinates('F'.$flag1);
									$objDrawing->setOffsetX(5); 
									$objDrawing->setOffsetY(5);
									//$objDrawing->setUrl('http://abarrotesazteca.com/Abarrotes/assets/img/productos/'.$row["imagen"]);
									$objDrawing->setWorksheet($this->excelfile->getActiveSheet());
									$this->excelfile->getActiveSheet()->getRowDimension($flag1)->setRowHeight(60);
									$this->excelfile->getActiveSheet()->getStyleByColumnAndRow(10, $flag1)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_DATE_XLSX14);
									$this->excelfile->getActiveSheet()->getCell('F'.$flag1)->getHyperlink()->setUrl('http://abarrotesazteca.com/Abarrotes/assets/img/productos/'.str_replace("_thumb.",".",$row["imagen"]));
								}

								$hoja1->getStyle("A{$flag1}:P{$flag1}")
						                 ->getAlignment()
						                 ->setHorizontal(\PHPExcel_Style_Alignment::HORIZONTAL_LEFT);
						         
				                 
								//Pedidos
								$this->excelfile->setActiveSheetIndex(1);
								$this->cellStyle("A".$flag.":BX".$flag."", "FFFFFF", "000000", FALSE, 12, "Franklin Gothic Book");
								
								$hoja->setCellValue("A{$flag}", $row['codigo'])->getStyle("A{$flag}")->getNumberFormat()->setFormatCode('# ???/???');//Formato de fraccion
								if($row['color'] == '#92CEE3'){
									$this->cellStyle("A{$flag}", "92CEE3", "000000", FALSE, 12, "Franklin Gothic Book");
								}else{
									$this->cellStyle("A{$flag}", "FFFFFF", "000000", FALSE, 12, "Franklin Gothic Book");
								}
								$hoja->setCellValue("B{$flag}", $row['producto']);
								$hoja->setCellValue("F{$flag}", $row['proveedor_first']);
								$hoja->setCellValue("C{$flag}", $row['codigo_factura'])->getStyle("C{$flag}")->getNumberFormat()->setFormatCode('# ???/???');


								if($row['precio_sistema'] < $row['precio_first']){
									$hoja->setCellValue("E{$flag}", $row['precio_first'])->getStyle("E{$flag}")->getNumberFormat()->setFormatCode('"$"#,##0.00_-');
									$this->cellStyle("E{$flag}", "FDB2B2", "E21111", FALSE, 12, "Franklin Gothic Book");
									$this->cellStyle("B{$flag}", "E21600", "000000", FALSE, 12, "Franklin Gothic Book");
								}else{
									$hoja->setCellValue("E{$flag}", $row['precio_first'])->getStyle("E{$flag}")->getNumberFormat()->setFormatCode('"$"#,##0.00_-');
									$this->cellStyle("E{$flag}", "96EAA8", "0C800C", FALSE, 12, "Franklin Gothic Book");
									$this->cellStyle("B{$flag}", "249947", "000000", FALSE, 12, "Franklin Gothic Book");
								}
								$hoja->setCellValue("H{$flag}", $row['precio_sistema'])->getStyle("H{$flag}")->getNumberFormat()->setFormatCode('"$"#,##0.00_-');//Formto de moneda
								$this->cellStyle("H".$flag, "FFFFFF","000000",  FALSE, 12, "Franklin Gothic Book");
								if($row['colorp'] == 1){
									$this->cellStyle("H{$flag}", "D6DCE4", "000000", FALSE, 12, "Franklin Gothic Book");
								}else{
									$this->cellStyle("H{$flag}", "FFFFFF", "000000", FALSE, 12, "Franklin Gothic Book");
								}
								$hoja->setCellValue("J{$flag}", $row['precio_four'])->getStyle("J{$flag}")->getNumberFormat()->setFormatCode('"$"#,##0.00_-');
								$this->cellStyle("J{$flag}", "FFFFFF", "000000", FALSE, 12, "Franklin Gothic Book");
								if($row['precio_sistema'] < $row['precio_next']){
									$hoja->setCellValue("K{$flag}", $row['precio_next'])->getStyle("K{$flag}")->getNumberFormat()->setFormatCode('"$"#,##0.00_-');
									$this->cellStyle("K{$flag}", "FDB2B2", "E21111", FALSE, 12, "Franklin Gothic Book");
								}else if($row['precio_next'] !== NULL){
									$hoja->setCellValue("K{$flag}", $row['precio_next'])->getStyle("K{$flag}")->getNumberFormat()->setFormatCode('"$"#,##0.00_-');
									$this->cellStyle("K{$flag}", "96EAA8", "0C800C", FALSE, 12, "Franklin Gothic Book");
								}else{
									$hoja->setCellValue("K{$flag}", $row['precio_next'])->getStyle("K{$flag}")->getNumberFormat()->setFormatCode('"$"#,##0.00_-');
									$this->cellStyle("K{$flag}", "FFFFFF", "000000", FALSE, 12, "Franklin Gothic Book");
								}
								$this->cellStyle("M".$flag.":BB".$flag, "FFFFFF", "000000", TRUE, 12, "Franklin Gothic Book");
								$hoja->setCellValue("L{$flag}", $row['proveedor_next']);
								$this->cellStyle("L".$flag, "FFFFFF", "000000", FALSE, 12, "Franklin Gothic Book");

								
								
								$hoja->setCellValue("O{$flag}", $row['cedis']);
								$hoja->setCellValue("AA{$flag}", $row['abarrotes']);
								$hoja->setCellValue("AF{$flag}", $row['pedregal']);
								$hoja->setCellValue("AK{$flag}", $row['tienda']);
								$hoja->setCellValue("AP{$flag}", $row['ultra']);
								$hoja->setCellValue("AU{$flag}", $row['trincheras']);
								$hoja->setCellValue("AZ{$flag}", $row['mercado']);
								$hoja->setCellValue("BE{$flag}", $row['tenencia']);
								$hoja->setCellValue("BJ{$flag}", $row['tijeras']);

								if ($row["lastfecha"] === NULL || $row["lastfecha"] === "") {
									$row["lastfecha"] = "NO PEDIDOS";
								}
								if ($row["unidad"] === NULL || $row["unidad"] === "") {
									$row["unidad"] = 1;
								}
								$hoja->setCellValue("D{$flag}",$row["unidad"]);

								$hoja->setCellValue("M{$flag}", $row['caja0']);
								$hoja->setCellValue("N{$flag}", $row['pz0']);
								$hoja->setCellValue("P{$flag}", $row["past"]["caja0"]);
								$hoja->setCellValue("Q{$flag}", $row['ped0']);
								$this->cellStyle("Q{$flag}", "D4EAEF", "000000", TRUE, 12, "Franklin Gothic Book");

								
								$hoja->setCellValue("R{$flag}", $row['caja9']);
								$hoja->setCellValue("S{$flag}", $row['pz9']);
								$hoja->setCellValue("T{$flag}", $row["past"]["caja9"]);
								$hoja->setCellValue("U{$flag}", $row['ped9']);
								$this->cellStyle("U{$flag}", "D4EAEF", "000000", TRUE, 12, "Franklin Gothic Book");

								

								$hoja->setCellValue("V{$flag}", "=M".$flag."+R".$flag);
								$hoja->setCellValue("W{$flag}", "=N".$flag."+S".$flag);
								$hoja->setCellValue("X{$flag}", "=Q".$flag."+U".$flag);
								$this->cellStyle("X{$flag}", "D4EAEF", "000000", TRUE, 12, "Franklin Gothic Book");


								$hoja->setCellValue("Y{$flag}", $row['caja1']);
								$hoja->setCellValue("Z{$flag}", $row['pz1']);
								$hoja->setCellValue("AB{$flag}", $row["past"]["caja1"]);
								$hoja->setCellValue("AC{$flag}", $row['ped1']);
								$this->cellStyle("AC{$flag}", "D4EAEF", "000000", TRUE, 12, "Franklin Gothic Book");

								$hoja->setCellValue("AD{$flag}", $row['caja2']);
								$hoja->setCellValue("AE{$flag}", $row['pz2']);
								$hoja->setCellValue("AG{$flag}", $row["past"]["caja2"]);
								$hoja->setCellValue("AH{$flag}", $row['ped2']);
								$this->cellStyle("AH{$flag}", "D4EAEF", "000000", TRUE, 12, "Franklin Gothic Book");

								
								$hoja->setCellValue("AI{$flag}", $row['caja3']);
								$hoja->setCellValue("AJ{$flag}", $row['pz3']);
								$hoja->setCellValue("AL{$flag}", $row["past"]["caja3"]);
								$hoja->setCellValue("AM{$flag}", $row['ped3']);
								$this->cellStyle("AM{$flag}", "D4EAEF", "000000", TRUE, 12, "Franklin Gothic Book");

								
								$hoja->setCellValue("AN{$flag}", $row['caja4']);
								$hoja->setCellValue("AO{$flag}", $row['pz4']);
								$hoja->setCellValue("AQ{$flag}", $row["past"]["caja4"]);
								$hoja->setCellValue("AR{$flag}", $row['ped4']);
								$this->cellStyle("AR{$flag}", "D4EAEF", "000000", TRUE, 12, "Franklin Gothic Book");

								
								$hoja->setCellValue("AS{$flag}", $row['caja5']);
								$hoja->setCellValue("AT{$flag}", $row['pz5']);
								$hoja->setCellValue("AV{$flag}", $row["past"]["caja5"]);
								$hoja->setCellValue("AW{$flag}", $row['ped5']);
								$this->cellStyle("AW{$flag}", "D4EAEF", "000000", TRUE, 12, "Franklin Gothic Book");

								
								$hoja->setCellValue("AX{$flag}", $row['caja6']);
								$hoja->setCellValue("AY{$flag}", $row['pz6']);
								$hoja->setCellValue("BA{$flag}", $row["past"]["caja6"]);
								$hoja->setCellValue("BB{$flag}", $row['ped6']);
								$this->cellStyle("BB{$flag}", "D4EAEF", "000000", TRUE, 12, "Franklin Gothic Book");

								$hoja->setCellValue("BC{$flag}", $row['caja7']);
								$hoja->setCellValue("BD{$flag}", $row['pz7']);
								$hoja->setCellValue("BF{$flag}", $row["past"]["caja7"]);
								$hoja->setCellValue("BG{$flag}", $row['ped7']);
								$this->cellStyle("BG{$flag}", "D4EAEF", "000000", TRUE, 12, "Franklin Gothic Book");

								
								$hoja->setCellValue("BH{$flag}", $row['caja8']);
								$hoja->setCellValue("BI{$flag}", $row['pz8']);
								$hoja->setCellValue("BK{$flag}", $row["past"]["caja8"]);
								$hoja->setCellValue("BL{$flag}", $row['ped8']);
								$this->cellStyle("BL{$flag}", "D4EAEF", "000000", TRUE, 12, "Franklin Gothic Book");

								$this->cellStyle("BM{$flag}", "FFFFFF", "000000", TRUE, 12, "Franklin Gothic Book");
								$hoja->setCellValue("BM{$flag}", $row['promocion_first']);
								$hoja->setCellValue("BN{$flag}", "=E".$flag."*Q".$flag)->getStyle("BN{$flag}")->getNumberFormat()->setFormatCode('"$"#,##0.00_-');
								$hoja->setCellValue("BO{$flag}", "=E".$flag."*AC".$flag)->getStyle("BO{$flag}")->getNumberFormat()->setFormatCode('"$"#,##0.00_-');
								$hoja->setCellValue("BP{$flag}", "=E".$flag."*AH".$flag)->getStyle("BP{$flag}")->getNumberFormat()->setFormatCode('"$"#,##0.00_-');
								$hoja->setCellValue("BQ{$flag}", "=E".$flag."*AM".$flag)->getStyle("BQ{$flag}")->getNumberFormat()->setFormatCode('"$"#,##0.00_-');
								$hoja->setCellValue("BR{$flag}", "=E".$flag."*AR".$flag)->getStyle("BR{$flag}")->getNumberFormat()->setFormatCode('"$"#,##0.00_-');
								$hoja->setCellValue("BS{$flag}", "=E".$flag."*AW".$flag)->getStyle("BS{$flag}")->getNumberFormat()->setFormatCode('"$"#,##0.00_-');
								$hoja->setCellValue("BT{$flag}", "=E".$flag."*BB".$flag)->getStyle("BT{$flag}")->getNumberFormat()->setFormatCode('"$"#,##0.00_-');
								$hoja->setCellValue("BU{$flag}", "=E".$flag."*BG".$flag)->getStyle("BU{$flag}")->getNumberFormat()->setFormatCode('"$"#,##0.00_-');
								$hoja->setCellValue("BV{$flag}", "=E".$flag."*BL".$flag)->getStyle("BV{$flag}")->getNumberFormat()->setFormatCode('"$"#,##0.00_-');
								$this->cellStyle("BW{$flag}", "D4EAEF", "000000", FALSE, 12, "Franklin Gothic Book");
								$hoja->setCellValue("BW{$flag}", "=SUM(BN".$flag.":BV".$flag.")")->getStyle("BW{$flag}")->getNumberFormat()->setFormatCode('"$"#,##0.00_-');
								$this->cellStyle("BX{$flag}", "C2B90A", "000000", FALSE, 12, "Franklin Gothic Book");
								$hoja->setCellValue("BX{$flag}", "=Q".$flag."+AC".$flag."+AH".$flag."+AM".$flag."+AR".$flag."+AW".$flag."+BB".$flag."+BG".$flag."+BL".$flag."");



								//Begin: TOTALES PEDIDOS PENDIENTES
								$hoja->setCellValue("CD{$flag}", "=E".$flag."*O".$flag)->getStyle("CD{$flag}")->getNumberFormat()->setFormatCode('"$"#,##0.00_-');
								$hoja->setCellValue("CE{$flag}", "=E".$flag."*AA".$flag)->getStyle("CE{$flag}")->getNumberFormat()->setFormatCode('"$"#,##0.00_-');
								$hoja->setCellValue("CF{$flag}", "=E".$flag."*AF".$flag)->getStyle("CF{$flag}")->getNumberFormat()->setFormatCode('"$"#,##0.00_-');
								$hoja->setCellValue("CG{$flag}", "=E".$flag."*AK".$flag)->getStyle("CG{$flag}")->getNumberFormat()->setFormatCode('"$"#,##0.00_-');
								$hoja->setCellValue("CH{$flag}", "=E".$flag."*AP".$flag)->getStyle("CH{$flag}")->getNumberFormat()->setFormatCode('"$"#,##0.00_-');
								$hoja->setCellValue("CI{$flag}", "=E".$flag."*AU".$flag)->getStyle("CI{$flag}")->getNumberFormat()->setFormatCode('"$"#,##0.00_-');
								$hoja->setCellValue("CJ{$flag}", "=E".$flag."*AZ".$flag)->getStyle("CJ{$flag}")->getNumberFormat()->setFormatCode('"$"#,##0.00_-');
								$hoja->setCellValue("CK{$flag}", "=E".$flag."*BE".$flag)->getStyle("CK{$flag}")->getNumberFormat()->setFormatCode('"$"#,##0.00_-');
								$hoja->setCellValue("CL{$flag}", "=E".$flag."*BJ".$flag)->getStyle("CL{$flag}")->getNumberFormat()->setFormatCode('"$"#,##0.00_-');
								$this->cellStyle("CM{$flag}", "000000", "FFFFFF", FALSE, 12, "Franklin Gothic Book");
								$hoja->setCellValue("CM{$flag}", "=SUM(CD".$flag.":CL".$flag.")")->getStyle("CM{$flag}")->getNumberFormat()->setFormatCode('"$"#,##0.00_-');
								$this->cellStyle("CN{$flag}", "000000", "FFFFFF", FALSE, 12, "Franklin Gothic Book");
								$hoja->setCellValue("CN{$flag}", "=O".$flag."+AA".$flag."+AF".$flag."+AK".$flag."+AP".$flag."+AU".$flag."+AZ".$flag."+BE".$flag."+BJ".$flag."");
								//End: TOTALES PEDIDOS PENDIENTES
								$border_style= array('borders' => array('right' => array('style' =>
									PHPExcel_Style_Border::BORDER_THIN,'color' => array('argb' => '000000'),)));
								$this->excelfile->setActiveSheetIndex(1);
								
								$this->excelfile->getActiveSheet()->getStyle('A'.$flag.':BX'.$flag)->applyFromArray($styleArray);
								$this->excelfile->getActiveSheet()->getStyle('BW'.$flag.':BX'.$flag)->applyFromArray($styleArray);
								if($row['precio_sistema'] == 0){
									$row['precio_sistema'] = 1;
								}
								if($row['precio_four'] == 0){
									$row['precio_four'] = 1;
								}
									$hoja->setCellValue("G{$flag}",100 - ($row['precio_first'] * 100 / $row['precio_sistema']))->getStyle("G{$flag}")->getNumberFormat()->setFormatCode('"%"#,##0.00_-');;
									$this->cellStyle("G".$flag, "FF9999", "000000", FALSE, 10, "Franklin Gothic Book");

									$hoja->setCellValue("I{$flag}", 100 - ($row['precio_sistema'] * 100 / $row['precio_four']))->getStyle("I{$flag}")->getNumberFormat()->setFormatCode('"%"#,##0.00_-');;
									$this->cellStyle("I".$flag, "FF9999", "000000", FALSE, 10, "Franklin Gothic Book");
								
								$this->excelfile->setActiveSheetIndex(0);
								$this->excelfile->getActiveSheet()->getStyle('A'.$flag1.':P'.$flag1)->applyFromArray($styleArray);
								$hoja->getStyle("A{$flag}:I{$flag}")
						                 ->getAlignment()
						                 ->setHorizontal(\PHPExcel_Style_Alignment::HORIZONTAL_LEFT);

						   
								$flag ++;
								$flag1 ++;
							}
						}
					}
					$flagf = $flag;
					$flagfs = $flag - 1;
					$hoja->setCellValue("BN{$flagf}", "=SUM(BN5:BN".$flagfs.")")->getStyle("BN{$flag}")->getNumberFormat()->setFormatCode('"$"#,##0.00_-');
					$hoja->setCellValue("BO{$flagf}", "=SUM(BO5:BO".$flagfs.")")->getStyle("BO{$flag}")->getNumberFormat()->setFormatCode('"$"#,##0.00_-');
					$hoja->setCellValue("BP{$flagf}", "=SUM(BP5:BP".$flagfs.")")->getStyle("BP{$flag}")->getNumberFormat()->setFormatCode('"$"#,##0.00_-');
					$hoja->setCellValue("BQ{$flagf}", "=SUM(BQ5:BQ".$flagfs.")")->getStyle("BQ{$flag}")->getNumberFormat()->setFormatCode('"$"#,##0.00_-');
					$hoja->setCellValue("BR{$flagf}", "=SUM(BR5:BR".$flagfs.")")->getStyle("BR{$flag}")->getNumberFormat()->setFormatCode('"$"#,##0.00_-');
					$hoja->setCellValue("BS{$flagf}", "=SUM(BS5:BS".$flagfs.")")->getStyle("BS{$flag}")->getNumberFormat()->setFormatCode('"$"#,##0.00_-');
					$hoja->setCellValue("BT{$flagf}", "=SUM(BT5:BT".$flagfs.")")->getStyle("BT{$flag}")->getNumberFormat()->setFormatCode('"$"#,##0.00_-');
					$hoja->setCellValue("BU{$flagf}", "=SUM(BU5:BU".$flagfs.")")->getStyle("BU{$flag}")->getNumberFormat()->setFormatCode('"$"#,##0.00_-');
					$hoja->setCellValue("BV{$flagf}", "=SUM(BV5:BV".$flagfs.")")->getStyle("BV{$flag}")->getNumberFormat()->setFormatCode('"$"#,##0.00_-');
					$this->cellStyle("BW{$flagf}", "000000", "FFFFFF", FALSE, 12, "Franklin Gothic Book");
					$hoja->setCellValue("BW{$flagf}", "=SUM(BW5:BW".$flagfs.")")->getStyle("BW{$flagf}")->getNumberFormat()->setFormatCode('"$"#,##0.00_-');

					$sumall[1] .= "BN".$flagf."+";
					$sumall[2] .= "BO".$flagf."+";
					$sumall[3] .= "BP".$flagf."+";
					$sumall[4] .= "BQ".$flagf."+";
					$sumall[5] .= "BR".$flagf."+";
					$sumall[6] .= "BS".$flagf."+";
					$sumall[7] .= "BT".$flagf."+";
					$sumall[8] .= "BU".$flagf."+";
					$sumall[9] .= "BV".$flagf."+";
					$sumall[10] .= "BW".$flagf."+";
					//Begin: TOTALES PEDIDOS PENDIENTES
					$hoja->setCellValue("CD{$flag}", "=SUM(CD".$flage.":CD".$flagfs.")")->getStyle("CD{$flag}")->getNumberFormat()->setFormatCode('"$"#,##0.00_-');
					$hoja->setCellValue("CE{$flag}", "=SUM(CE".$flage.":CE".$flagfs.")")->getStyle("CE{$flag}")->getNumberFormat()->setFormatCode('"$"#,##0.00_-');
					$hoja->setCellValue("CF{$flag}", "=SUM(CF".$flage.":CF".$flagfs.")")->getStyle("CF{$flag}")->getNumberFormat()->setFormatCode('"$"#,##0.00_-');
					$hoja->setCellValue("CG{$flag}", "=SUM(CG".$flage.":CG".$flagfs.")")->getStyle("CG{$flag}")->getNumberFormat()->setFormatCode('"$"#,##0.00_-');
					$hoja->setCellValue("CH{$flag}", "=SUM(CH".$flage.":CH".$flagfs.")")->getStyle("CH{$flag}")->getNumberFormat()->setFormatCode('"$"#,##0.00_-');
					$hoja->setCellValue("CI{$flag}", "=SUM(CI".$flage.":CI".$flagfs.")")->getStyle("CI{$flag}")->getNumberFormat()->setFormatCode('"$"#,##0.00_-');
					$hoja->setCellValue("CJ{$flag}", "=SUM(CJ".$flage.":CJ".$flagfs.")")->getStyle("CJ{$flag}")->getNumberFormat()->setFormatCode('"$"#,##0.00_-');
					$hoja->setCellValue("CK{$flag}", "=SUM(CK".$flage.":CK".$flagfs.")")->getStyle("CK{$flag}")->getNumberFormat()->setFormatCode('"$"#,##0.00_-');
					$hoja->setCellValue("CL{$flag}", "=SUM(CL".$flage.":CL".$flagfs.")")->getStyle("CL{$flag}")->getNumberFormat()->setFormatCode('"$"#,##0.00_-');
					$hoja->setCellValue("CM{$flag}", "=SUM(CM".$flage.":CM".$flagfs.")")->getStyle("CM{$flag}")->getNumberFormat()->setFormatCode('"$"#,##0.00_-');
					//End: TOTALES PEDIDOS PENDIENTES
					
					$flag++;
					$flag1++;
					$flag++;
					$flag1++;
					$flag1++;
					$flag1++;
					$flag++;
					$flag++;
				}
			}
		}
		$flag++;
		$bandera = $flag;
		
		$hoja->mergeCells('G'.$flag.':J'.$flag);
		$this->cellStyle("G".$flag, "000000", "FFFFFF", TRUE, 12, "Franklin Gothic Book");
		$hoja->setCellValue("G".$flag, "TOTALES PENDIENTES '".$provname."'");
		$flag++;
		$hoja->mergeCells('G'.$flag.':H'.$flag);
		$hoja->mergeCells('I'.$flag.':J'.$flag);
		$this->cellStyle("G".$flag, "000000", "FFFFFF", TRUE, 12, "Franklin Gothic Book");
		$hoja->setCellValue("G".$flag, "CEDIS");
		$hoja->setCellValue("I{$flag}", "=CD{$flagf}")->getStyle("I{$flag}")->getNumberFormat()->setFormatCode('"$"#,##0.00_-');
		$flag++;
		$hoja->mergeCells('G'.$flag.':H'.$flag);
		$hoja->mergeCells('I'.$flag.':J'.$flag);
		$this->cellStyle("G".$flag, "000000", "FFFFFF", TRUE, 12, "Franklin Gothic Book");
		$hoja->setCellValue("G".$flag, "ABARROTES");
		$hoja->setCellValue("I{$flag}", "=CE{$flagf}")->getStyle("I{$flag}")->getNumberFormat()->setFormatCode('"$"#,##0.00_-');
		$flag++;
		$hoja->mergeCells('G'.$flag.':H'.$flag);
		$hoja->mergeCells('I'.$flag.':J'.$flag);
		$this->cellStyle("G".$flag, "000000", "FFFFFF", TRUE, 12, "Franklin Gothic Book");
		$hoja->setCellValue("G".$flag, "VILLAS");
		$hoja->setCellValue("I{$flag}", "=CF{$flagf}")->getStyle("I{$flag}")->getNumberFormat()->setFormatCode('"$"#,##0.00_-');
		$flag++;
		$hoja->mergeCells('G'.$flag.':H'.$flag);
		$hoja->mergeCells('I'.$flag.':J'.$flag);
		$this->cellStyle("G".$flag, "000000", "FFFFFF", TRUE, 12, "Franklin Gothic Book");
		$hoja->setCellValue("G".$flag, "TIENDA");
		$hoja->setCellValue("I{$flag}", "=CG{$flagf}")->getStyle("I{$flag}")->getNumberFormat()->setFormatCode('"$"#,##0.00_-');
		$flag++;
		$hoja->mergeCells('G'.$flag.':H'.$flag);
		$hoja->mergeCells('I'.$flag.':J'.$flag);
		$this->cellStyle("G".$flag, "000000", "FFFFFF", TRUE, 12, "Franklin Gothic Book");
		$hoja->setCellValue("G".$flag, "ULTRAMARINOS");
		$hoja->setCellValue("I{$flag}", "=CH{$flagf}")->getStyle("I{$flag}")->getNumberFormat()->setFormatCode('"$"#,##0.00_-');
		$flag++;
		$hoja->mergeCells('G'.$flag.':H'.$flag);
		$hoja->mergeCells('I'.$flag.':J'.$flag);
		$this->cellStyle("G".$flag, "000000", "FFFFFF", TRUE, 12, "Franklin Gothic Book");
		$hoja->setCellValue("G".$flag, "TRINCHERAS");
		$hoja->setCellValue("I{$flag}", "=CI{$flagf}")->getStyle("I{$flag}")->getNumberFormat()->setFormatCode('"$"#,##0.00_-');
		$flag++;
		$hoja->mergeCells('G'.$flag.':H'.$flag);
		$hoja->mergeCells('I'.$flag.':J'.$flag);
		$this->cellStyle("G".$flag, "000000", "FFFFFF", TRUE, 12, "Franklin Gothic Book");
		$hoja->setCellValue("G".$flag, "AZT MERCADO");
		$hoja->setCellValue("I{$flag}", "=CJ{$flagf}")->getStyle("I{$flag}")->getNumberFormat()->setFormatCode('"$"#,##0.00_-');
		$flag++;
		$hoja->mergeCells('G'.$flag.':H'.$flag);
		$hoja->mergeCells('I'.$flag.':J'.$flag);
		$this->cellStyle("G".$flag, "000000", "FFFFFF", TRUE, 12, "Franklin Gothic Book");
		$hoja->setCellValue("G".$flag, "TENENCIA");
		$hoja->setCellValue("I{$flag}", "=CK{$flagf}")->getStyle("I{$flag}")->getNumberFormat()->setFormatCode('"$"#,##0.00_-');
		$flag++;
		$hoja->mergeCells('G'.$flag.':H'.$flag);
		$hoja->mergeCells('I'.$flag.':J'.$flag);
		$this->cellStyle("G".$flag, "000000", "FFFFFF", TRUE, 12, "Franklin Gothic Book");
		$hoja->setCellValue("G".$flag, "TIJERAS");
		$hoja->setCellValue("I{$flag}", "=CL{$flagf}")->getStyle("I{$flag}")->getNumberFormat()->setFormatCode('"$"#,##0.00_-');
		$flag++;
		$hoja->mergeCells('G'.$flag.':H'.$flag);
		$hoja->mergeCells('I'.$flag.':J'.$flag);
		$this->cellStyle("G".$flag, "000000", "FFFFFF", TRUE, 12, "Franklin Gothic Book");
		$hoja->setCellValue("G".$flag, "TOTAL");
		$hoja->setCellValue("I{$flag}", "=CM{$flagf}")->getStyle("I{$flag}")->getNumberFormat()->setFormatCode('"$"#,##0.00_-');
		$flag = $bandera;

		$hoja->mergeCells('B'.$flag.':C'.$flag);
		$this->cellStyle("B".$flag, "000000", "FFFFFF", TRUE, 12, "Franklin Gothic Book");
		$hoja->setCellValue("B".$flag, "TOTALES EN GENERAL");
		$flag++;
		$this->cellStyle("B".$flag, "C00000", "000000", TRUE, 12, "Franklin Gothic Book");
		$hoja->setCellValue("B".$flag, "CEDIS");
		$hoja->setCellValue("C{$flag}", "=(".substr($sumall[1],0,-1).")")->getStyle("C{$flag}")->getNumberFormat()->setFormatCode('"$"#,##0.00_-');
		$flag++;
		$this->cellStyle("B".$flag, "01B0F0", "000000", TRUE, 12, "Franklin Gothic Book");
		$hoja->setCellValue("B".$flag, "ABARROTES");
		$hoja->setCellValue("C{$flag}", "=(".substr($sumall[2],0,-1).")")->getStyle("C{$flag}")->getNumberFormat()->setFormatCode('"$"#,##0.00_-');
		$flag++;
		$this->cellStyle("B".$flag, "FF0000", "000000", TRUE, 12, "Franklin Gothic Book");
		$hoja->setCellValue("B".$flag, "VILLAS");
		$hoja->setCellValue("C{$flag}", "=(".substr($sumall[3],0,-1).")")->getStyle("C{$flag}")->getNumberFormat()->setFormatCode('"$"#,##0.00_-');
		$flag++;
		$this->cellStyle("B".$flag, "E26C0B", "000000", TRUE, 12, "Franklin Gothic Book");
		$hoja->setCellValue("B".$flag, "TIENDA");
		$hoja->setCellValue("C{$flag}", "=(".substr($sumall[4],0,-1).")")->getStyle("C{$flag}")->getNumberFormat()->setFormatCode('"$"#,##0.00_-');
		$flag++;
		$this->cellStyle("B".$flag, "C5C5C5", "000000", TRUE, 12, "Franklin Gothic Book");
		$hoja->setCellValue("B".$flag, "ULTRAMARINOS");
		$hoja->setCellValue("C{$flag}", "=(".substr($sumall[5],0,-1).")")->getStyle("C{$flag}")->getNumberFormat()->setFormatCode('"$"#,##0.00_-');
		$flag++;
		$this->cellStyle("B".$flag, "92D051", "000000", TRUE, 12, "Franklin Gothic Book");
		$hoja->setCellValue("B".$flag, "TRINCHERAS");
		$hoja->setCellValue("C{$flag}", "=(".substr($sumall[6],0,-1).")")->getStyle("C{$flag}")->getNumberFormat()->setFormatCode('"$"#,##0.00_-');
		$flag++;
		$this->cellStyle("B".$flag, "B1A0C7", "000000", TRUE, 12, "Franklin Gothic Book");
		$hoja->setCellValue("B".$flag, "AZT MERCADO");
		$hoja->setCellValue("C{$flag}", "=(".substr($sumall[7],0,-1).")")->getStyle("C{$flag}")->getNumberFormat()->setFormatCode('"$"#,##0.00_-');
		$flag++;
		$this->cellStyle("B".$flag, "DA9694", "000000", TRUE, 12, "Franklin Gothic Book");
		$hoja->setCellValue("B".$flag, "TENENCIA");
		$hoja->setCellValue("C{$flag}", "=(".substr($sumall[8],0,-1).")")->getStyle("C{$flag}")->getNumberFormat()->setFormatCode('"$"#,##0.00_-');
		$flag++;
		$this->cellStyle("B".$flag, "4CACC6", "000000", TRUE, 12, "Franklin Gothic Book");
		$hoja->setCellValue("B".$flag, "TIJERAS");
		$hoja->setCellValue("C{$flag}", "=(".substr($sumall[9],0,-1).")")->getStyle("C{$flag}")->getNumberFormat()->setFormatCode('"$"#,##0.00_-');
		$flag++;
		$this->cellStyle("B".$flag, "000000", "FFFFFF", TRUE, 12, "Franklin Gothic Book");
		$hoja->setCellValue("B".$flag, "TOTAL");
		$hoja->setCellValue("C{$flag}", "=(".substr($sumall[10],0,-1).")")->getStyle("C{$flag}")->getNumberFormat()->setFormatCode('"$"#,##0.00_-');
		$flag++;

		$dias = array("DOMINGO","LUNES","MARTES","MIÉRCOLES","JUEVES","VIERNES","SÁBADO");
		$meses = array("ENERO","FEBRERO","MARZO","ABRIL","MAYO","JUNIO","JULIO","AGOSTO","SEPTIEMBRE","OCTUBRE","NOVIEMBRE","DICIEMBRE");
		$fecha =  $dias[date('w')]." ".date('d')." DE ".$meses[date('n')-1]. " DEL ".date('Y') ;
		$file_name = "FORMATO COSTEÑA ".$fecha.".xlsx"; //Nombre del documento con extención
		$excel_Writer = PHPExcel_IOFactory::createWriter($this->excelfile, "Excel2007");
		header("Content-Type: application/vnd.ms-excel; charset=utf-8");
		header("Content-Disposition: attachment;filename=".$file_name);
		header("Cache-Control: max-age=0");
		$excel_Writer->save("php://output");
		/*$excel_Writer = new PHPExcel_Writer_Excel2007($this->excelfile);
		$excel_Writer->setOffice2003Compatibility(true);
		$excel_Writer->save("php://output");*/
	}

	private function fill_cuetara($id_proves,$proves,$prs){
		$flag =1;
		$flag1 = 1;
		$array = "";
		$array2 = "";
		$filenam = "";
		$id_proves = $this->input->post('id_proves4');
		$proves = $this->input->post('id_proves2');
		$prs = substr($id_proves,0,6);
		$filenam = $id_proves;
		$array = (object)['0'=>(object)['nombre' => $id_proves]];
		//$this->jsonResponse($array);
		ini_set("memory_limit", "-1");
		ini_set("max_execution_time", "-1");
		$this->load->library("excelfile");
		$hoja1 = $this->excelfile->setActiveSheetIndex(0);
		$this->excelfile->setActiveSheetIndex(0)->setTitle("EXISTENCIAS");
		$this->excelfile->createSheet();
        $hoja = $this->excelfile->setActiveSheetIndex(1);
        $hoja->setTitle("PEDIDO");
		$styleArray = array(
		  'borders' => array(
		    'allborders' => array(
		      'style' => PHPExcel_Style_Border::BORDER_THIN
		    )
		  )
		);
		$hoja->getColumnDimension('A')->setWidth("20");
		$hoja->getColumnDimension('B')->setWidth("70");
		$hoja->getColumnDimension('C')->setWidth("15");
		$hoja->getColumnDimension('D')->setWidth("8");

		$hoja->getColumnDimension('E')->setWidth("15");
		$hoja->getColumnDimension('G')->setWidth("8");
		$hoja->getColumnDimension('I')->setWidth("8");
		$hoja->getColumnDimension('F')->setWidth("20");
		$hoja->getColumnDimension('H')->setWidth("15");

		$hoja1->getColumnDimension('A')->setWidth("6");
		$hoja1->getColumnDimension('B')->setWidth("6");
		$hoja1->getColumnDimension('C')->setWidth("6");
		$hoja1->getColumnDimension('D')->setWidth("25");
		$hoja1->getColumnDimension('E')->setWidth("47");
		$hoja1->getColumnDimension('G')->setWidth("50");

		$hoja->getColumnDimension('BM' )->setWidth("70");
		$hoja->getColumnDimension('L')->setWidth("20");

		$flagBorder = 0;
		$flagBorder1 = 1;
		$flagBorder2 = 0;
		$flagBorder3 = 1;
		$flage = 5;
		$i = 0;
		$sumall = array(1 => "", 2 => "", 3 => "", 4 => "", 5 => "", 6 => "", 7 => "", 8 => "", 9 => "", 10 => "");
		$provname = "";
		if ($array){
			foreach ($array as $key => $value){
				$fecha = new DateTime(date('Y-m-d H:i:s'));
				$intervalo = new DateInterval('P2D');
				$fecha->add($intervalo);
				$cotizacionesProveedor = $this->ct_mdl->getPedidosAllCuetara(NULL, $fecha->format('Y-m-d H:i:s'), 0);
			
				$difff = 0.01;
				$flag2 = 3;
				$cargo = "";
				if ($cotizacionesProveedor){
					//HOJA EXISTENCIAS
					$this->excelfile->setActiveSheetIndex(0);
					if($i > 0){
						$flagBorder = $flag1 ;
						$this->excelfile->getActiveSheet()->getStyle('A'.$flagBorder1.':G'.$flagBorder)->applyFromArray($styleArray);
						$flagBorder1 = $flag1;
					}
					$hoja1->mergeCells('A'.$flag1.':G'.$flag1);
					$this->cellStyle("A".$flag1, "FFFFFF", "000000", TRUE, 12, "Franklin Gothic Book");
					$hoja1->setCellValue("A".$flag1."", "GRUPO ABARROTES AZTECA");
					$this->excelfile->getActiveSheet()->getStyle('A'.$flag1.':G'.$flag1)->applyFromArray($styleArray);
					$flag1++;
					$hoja1->mergeCells('A'.$flag1.':G'.$flag1);
					$this->cellStyle("A".$flag1, "FFFFFF", "000000", TRUE, 12, "Franklin Gothic Book");
					$hoja1->setCellValue("A".$flag1."", "PEDIDOS A '".$value->nombre."' ".date("d-m-Y"));
					$provname = $value->nombre;
					$this->excelfile->getActiveSheet()->getStyle('A'.$flag1.':G'.$flag1)->applyFromArray($styleArray);
					$flag1++;
					$this->cellStyle("A".$flag1.":D".$flag1, "000000", "FFFFFF", TRUE, 12, "Franklin Gothic Book");
					$hoja1->mergeCells('A'.$flag1.':B'.$flag1);
					$hoja1->setCellValue("A".$flag1, "EXISTENCIAS");
					$hoja1->setCellValue("E".$flag1, "DESCRIPCIÓN");
					$this->cellStyle("E".$flag1, "000000", "FFFFFF", TRUE, 12, "Franklin Gothic Book");

					$this->cellStyle("H".$flag1."", "000000", "FFFFFF", TRUE, 12, "Franklin Gothic Book");
					$hoja1->setCellValue("H".$flag1."", "PENDIENT");
					$this->cellStyle("I".$flag1."", "000000", "FFFFFF", TRUE, 12, "Franklin Gothic Book");
					$hoja1->setCellValue("I".$flag1."", "PENDIENT");
					$this->cellStyle("J".$flag1."", "000000", "FFFFFF", TRUE, 12, "Franklin Gothic Book");
					$hoja1->setCellValue("J".$flag1."", "PENDIENT");
					$this->cellStyle("K".$flag1."", "000000", "FFFFFF", TRUE, 12, "Franklin Gothic Book");
					$hoja1->setCellValue("K".$flag1."", "PENDIENT");
					$this->cellStyle("L".$flag1."", "000000", "FFFFFF", TRUE, 12, "Franklin Gothic Book");
					$hoja1->setCellValue("L".$flag1."", "PENDIENT");
					$this->cellStyle("M".$flag1."", "000000", "FFFFFF", TRUE, 12, "Franklin Gothic Book");
					$hoja1->setCellValue("M".$flag1."", "PENDIENT");
					$this->cellStyle("N".$flag1."", "000000", "FFFFFF", TRUE, 12, "Franklin Gothic Book");
					$hoja1->setCellValue("N".$flag1."", "PENDIENT");
					$this->cellStyle("O".$flag1."", "000000", "FFFFFF", TRUE, 12, "Franklin Gothic Book");
					$hoja1->setCellValue("O".$flag1."", "PENDIENT");
					$this->cellStyle("P".$flag1."", "000000", "FFFFFF", TRUE, 12, "Franklin Gothic Book");
					$hoja1->setCellValue("P".$flag1."", "PENDIENT");
					$this->excelfile->getActiveSheet()->getStyle('A'.$flag1.':P'.$flag1)->applyFromArray($styleArray);
					$flag1++;
					$this->cellStyle("A".$flag1.":G".$flag1, "000000", "FFFFFF", TRUE, 12, "Franklin Gothic Book");
					$hoja1->setCellValue("A".$flag1, "CAJAS");
					$hoja1->setCellValue("B".$flag1, "PZAS");
					$hoja1->setCellValue("C".$flag1, "PEDIDO");
					$hoja1->setCellValue("D".$flag1, "CÓDIGO");
					$hoja1->setCellValue("G".$flag1, "PROMOCIÓN");
					$hoja1->setCellValue("F".$flag1, "IMAGEN")->getColumnDimension('F')->setWidth(18);
					
					$this->cellStyle("H".$flag1, "C00000", "000000", TRUE, 10, "Franklin Gothic Book");
					$this->cellStyle("I".$flag1, "01B0F0", "000000", TRUE, 10, "Franklin Gothic Book");
					$this->cellStyle("J".$flag1, "FF0000", "000000", TRUE, 10, "Franklin Gothic Book");
					$this->cellStyle("K".$flag1, "E26C0B", "000000", TRUE, 10, "Franklin Gothic Book");
					$this->cellStyle("L".$flag1, "C5C5C5", "000000", TRUE, 10, "Franklin Gothic Book");
					$this->cellStyle("M".$flag1, "92D051", "000000", TRUE, 10, "Franklin Gothic Book");
					$this->cellStyle("N".$flag1, "B1A0C7", "000000", TRUE, 10, "Franklin Gothic Book");
					$this->cellStyle("O".$flag1, "DA9694", "000000", TRUE, 10, "Franklin Gothic Book");
					$this->cellStyle("P".$flag1, "4CACC6", "000000", TRUE, 10, "Franklin Gothic Book");
					$hoja1->setCellValue("H".$flag1."", "CEDIS");
					$hoja1->setCellValue("I".$flag1."", "ABARROTES");
					$hoja1->setCellValue("J".$flag1."", "VILLAS");
					$hoja1->setCellValue("K".$flag1."", "TIENDA");
					$hoja1->setCellValue("L".$flag1."", "ULTRA");
					$hoja1->setCellValue("M".$flag1."", "TRINCHERAS");
					$hoja1->setCellValue("N".$flag1."", "MERCADO");
					$hoja1->setCellValue("O".$flag1."", "TENENCIA");
					$hoja1->setCellValue("P".$flag1."", "TIJERAS");
					$this->excelfile->getActiveSheet()->getStyle('A'.$flag1.':P'.$flag1)->applyFromArray($styleArray);
					//$flag1++;
					$this->excelfile->setActiveSheetIndex(1);
					if($i > 0){
						$flagBorder2 = $flag ;
						$this->excelfile->getActiveSheet()->getStyle('A'.$flagBorder3.':AD'.$flagBorder2)->applyFromArray($styleArray);
						$flagBorder3 = $flag;
					}


					//HOJA PEDIDOS
					$this->cellStyle("A".$flag, "FFFFFF", "000000", TRUE, 12, "Franklin Gothic Book");
					$hoja->setCellValue("A".$flag."", "CEDIS, ABARROTES,PEDREGAL, TIENDA, ULTRAMARINOS, TRINCHERAS, MERCADO, TIJERAS, Y TENENCIA AZTECA AUTOSERVICIOS SA. DE CV.");
					$hoja->mergeCells('A'.$flag.':BM'.$flag);
					$this->excelfile->getActiveSheet()->getStyle('A'.$flag.':BM'.$flag)->applyFromArray($styleArray);
					$flag++;
					$hoja->mergeCells('A'.$flag.':L'.$flag);
					$hoja->mergeCells('M'.$flag.':Q'.$flag);
					$hoja->mergeCells('R'.$flag.':U'.$flag);
					$hoja->mergeCells('V'.$flag.':X'.$flag);
					$hoja->mergeCells('Y'.$flag.':AC'.$flag);
					$hoja->mergeCells('AD'.$flag.':AH'.$flag);
					$hoja->mergeCells('AI'.$flag.':AM'.$flag);
					$hoja->mergeCells('AN'.$flag.':AR'.$flag);
					$hoja->mergeCells('AS'.$flag.':AW'.$flag);
					$hoja->mergeCells('AX'.$flag.':BB'.$flag);
					$hoja->mergeCells('BC'.$flag.':BG'.$flag);
					$hoja->mergeCells('BH'.$flag.':BL'.$flag);

					$this->cellStyle("B".$flag, "FFFFFF", "000000", TRUE, 12, "Franklin Gothic Book");
					$hoja->setCellValue("B".$flag, "PEDIDOS A '".$value->nombre."' ".date("d-m-Y"));
					$this->cellStyle("M".$flag, "C00000", "000000", TRUE, 12, "Franklin Gothic Book");
					$hoja->setCellValue("M".$flag, "CEDIS");
					$hoja->setCellValue("R".$flag, "CD INDUSTRIAL");
					$this->cellStyle("R".$flag, "FF0066", "000000", TRUE, 12, "Franklin Gothic Book");
					$this->cellStyle("V".$flag, "C2B90A", "000000", TRUE, 12, "Franklin Gothic Book");
					$hoja->setCellValue("V".$flag, "SUMA CEDIS");
					$this->cellStyle("Y".$flag, "01B0F0", "000000", TRUE, 12, "Franklin Gothic Book");
					$hoja->setCellValue("Y".$flag, "ABARROTES");
					$this->cellStyle("AD".$flag, "FF0000", "000000", TRUE, 12, "Franklin Gothic Book");
					$hoja->setCellValue("AD".$flag, "VILLAS");
					$this->cellStyle("AI".$flag, "E26C0B", "000000", TRUE, 12, "Franklin Gothic Book");
					$hoja->setCellValue("AI".$flag, "TIENDA");
					$this->cellStyle("AN".$flag, "C5C5C5", "000000", TRUE, 12, "Franklin Gothic Book");
					$hoja->setCellValue("AN".$flag, "ULTRAMARINOS");
					$this->cellStyle("AS".$flag, "92D051", "000000", TRUE, 12, "Franklin Gothic Book");
					$hoja->setCellValue("AS".$flag, "TRINCHERAS");
					$this->cellStyle("AX".$flag, "B1A0C7", "000000", TRUE, 12, "Franklin Gothic Book");
					$hoja->setCellValue("AX".$flag, "AZT MERCADO");
					$this->cellStyle("BC".$flag, "DA9694", "000000", TRUE, 12, "Franklin Gothic Book");
					$hoja->setCellValue("BC".$flag, "TENENCIA");
					$this->cellStyle("BH".$flag, "4CACC6", "000000", TRUE, 12, "Franklin Gothic Book");
					$hoja->setCellValue("BH".$flag, "TIJERAS");
					
					$this->cellStyle("A3:BM4", "000000", "FFFFFF", TRUE, 12, "Franklin Gothic Book");
					
					$this->excelfile->getActiveSheet()->getStyle('A'.$flag.':BM'.$flag)->applyFromArray($styleArray);
					$flag++;
					$this->cellStyle("A".$flag.":BM".$flag."", "000000", "FFFFFF", TRUE, 12, "Franklin Gothic Book");
					$hoja->mergeCells('A'.$flag.':L'.$flag);
					$hoja->mergeCells('M'.$flag.':Q'.$flag);
					$hoja->mergeCells('R'.$flag.':U'.$flag);
					$hoja->mergeCells('V'.$flag.':X'.$flag);
					$hoja->mergeCells('Y'.$flag.':AC'.$flag);
					$hoja->mergeCells('AD'.$flag.':AH'.$flag);
					$hoja->mergeCells('AI'.$flag.':AM'.$flag);
					$hoja->mergeCells('AN'.$flag.':AR'.$flag);
					$hoja->mergeCells('AS'.$flag.':AW'.$flag);
					$hoja->mergeCells('AX'.$flag.':BB'.$flag);
					$hoja->mergeCells('BC'.$flag.':BG'.$flag);
					$hoja->mergeCells('BH'.$flag.':BL'.$flag);
					$hoja->setCellValue("A".$flag, "DESCRIPCIÓN");
					$hoja->setCellValue("M".$flag, "EXISTENCIAS");
					$hoja->setCellValue("R".$flag, " SUMA EXISTENCIAS");
					$hoja->setCellValue("V".$flag, "EXISTENCIAS");
					$hoja->setCellValue("Y".$flag, "EXISTENCIAS");
					$hoja->setCellValue("AD".$flag, "EXISTENCIAS");
					$hoja->setCellValue("AI".$flag, "EXISTENCIAS");
					$hoja->setCellValue("AN".$flag, "EXISTENCIAS");
					$hoja->setCellValue("AS".$flag, "EXISTENCIAS");
					$hoja->setCellValue("AX".$flag, "EXISTENCIAS");
					$hoja->setCellValue("BC".$flag, "EXISTENCIAS");
					$hoja->setCellValue("BH".$flag, "EXISTENCIAS");
					$this->excelfile->getActiveSheet()->getStyle('A'.$flag.':BM'.$flag)->applyFromArray($styleArray);
					//Begin: TOTALES PEDIDOS PENDIENTES
					$hoja->mergeCells('CD'.$flag.':CN'.$flag);
					$this->cellStyle("CD".$flag.":CN".$flag."", "000000", "FFFFFF", TRUE, 12, "Franklin Gothic Book");
					$hoja->setCellValue("CD".$flag, "TOTAL POR PEDIDOS PENDIENTES");
					//End: TOTALES PEDIDOS PENDIENTES
					$flag++;
					$this->cellStyle("A".$flag.":BM".$flag."", "000000", "FFFFFF", TRUE, 12, "Franklin Gothic Book");
					$hoja->setCellValue("A".$flag, "CODIGO");
					$hoja->setCellValue("C".$flag, "REAL")
					;
					$hoja->setCellValue("D".$flag, "UM");
					$hoja->setCellValue("E".$flag, "1ER");
					$hoja->setCellValue("F".$flag, "COSTO");
					$hoja->setCellValue("G".$flag, "DIF % 5 Y 1ER");
					$hoja->setCellValue("H".$flag, "SISTEMA");
					$hoja->setCellValue("I".$flag, "DIF % 5 Y 4");
					$hoja->setCellValue("J".$flag, "PRECIO4");
					$hoja->setCellValue("K".$flag, "2DO");
					$hoja->setCellValue("L".$flag, "PROVEEDOR");

					$hoja->setCellValue("M".$flag, "CAJAS");
					$hoja->setCellValue("N".$flag, "PZAS");
					$hoja->setCellValue("O".$flag, "PEND");
					$hoja->setCellValue("P".$flag, "STOCK");
					$hoja->setCellValue("Q".$flag, "PEDIDO");
					$hoja->setCellValue("R".$flag, "CAJAS");
					$hoja->setCellValue("S".$flag, "PZAS");
					$hoja->setCellValue("T".$flag, "STOCK");
					$hoja->setCellValue("U".$flag, "PEDIDO");
					$hoja->setCellValue("V".$flag, "CAJAS");
					$hoja->setCellValue("W".$flag, "PZAS");
					$hoja->setCellValue("X".$flag, "PEDIDO");
					$hoja->setCellValue("Y".$flag, "CAJAS");
					$hoja->setCellValue("Z".$flag, "PZAS");
					$hoja->setCellValue("AA".$flag, "PEND");
					$hoja->setCellValue("AB".$flag, "STOCK");
					$hoja->setCellValue("AC".$flag, "PEDIDO");
					$hoja->setCellValue("AD".$flag, "CAJAS");
					$hoja->setCellValue("AE".$flag, "PZAS");
					$hoja->setCellValue("AF".$flag, "PEND");
					$hoja->setCellValue("AG".$flag, "STOCK");
					$hoja->setCellValue("AH".$flag, "PEDIDO");
					$hoja->setCellValue("AI".$flag, "CAJAS");
					$hoja->setCellValue("AJ".$flag, "PZAS");
					$hoja->setCellValue("AK".$flag, "PEND");
					$hoja->setCellValue("AL".$flag, "STOCK");
					$hoja->setCellValue("AM".$flag, "PEDIDO");
					$hoja->setCellValue("AN".$flag, "CAJAS");
					$hoja->setCellValue("AO".$flag, "PZAS");
					$hoja->setCellValue("AP".$flag, "PEND");
					$hoja->setCellValue("AQ".$flag, "STOCK");
					$hoja->setCellValue("AR".$flag, "PEDIDO");
					$hoja->setCellValue("AS".$flag, "CAJAS");
					$hoja->setCellValue("AT".$flag, "PZAS");
					$hoja->setCellValue("AU".$flag, "PEND");
					$hoja->setCellValue("AV".$flag, "STOCK");
					$hoja->setCellValue("AW".$flag, "PEDIDO");
					$hoja->setCellValue("AX".$flag, "CAJAS");
					$hoja->setCellValue("AY".$flag, "PZAS");
					$hoja->setCellValue("AZ".$flag, "PEND");
					$hoja->setCellValue("BA".$flag, "STOCK");
					$hoja->setCellValue("BB".$flag, "PEDIDO");
					$hoja->setCellValue("BC".$flag, "CAJAS");
					$hoja->setCellValue("BD".$flag, "PZAS");
					$hoja->setCellValue("BE".$flag, "PEND");
					$hoja->setCellValue("BF".$flag, "STOCK");
					$hoja->setCellValue("BG".$flag, "PEDIDO");
					$hoja->setCellValue("BH".$flag, "CAJAS");
					$hoja->setCellValue("BI".$flag, "PZAS");
					$hoja->setCellValue("BJ".$flag, "PEND");
					$hoja->setCellValue("BK".$flag, "STOCK");
					$hoja->setCellValue("BL".$flag, "PEDIDO");
					$hoja->setCellValue("BM".$flag, "PROMOCIÓN");					
					
					
					$this->cellStyle("BN".$flag1, "C00000", "000000", TRUE, 10, "Franklin Gothic Book");
					$this->cellStyle("BO".$flag1, "01B0F0", "000000", TRUE, 10, "Franklin Gothic Book");
					$this->cellStyle("BP".$flag1, "FF0000", "000000", TRUE, 10, "Franklin Gothic Book");
					$this->cellStyle("BQ".$flag1, "E26C0B", "000000", TRUE, 10, "Franklin Gothic Book");
					$this->cellStyle("BR".$flag1, "C5C5C5", "000000", TRUE, 10, "Franklin Gothic Book");
					$this->cellStyle("BS".$flag1, "92D051", "000000", TRUE, 10, "Franklin Gothic Book");
					$this->cellStyle("BT".$flag1, "B1A0C7", "000000", TRUE, 10, "Franklin Gothic Book");
					$this->cellStyle("BU".$flag1, "DA9694", "000000", TRUE, 10, "Franklin Gothic Book");
					$this->cellStyle("BV".$flag1, "4CACC6", "000000", TRUE, 10, "Franklin Gothic Book");

					$hoja->setCellValue("BW".$flag, "TOTAL");
					$hoja->setCellValue("BX".$flag, "PEDIDOS");
					$this->cellStyle("BW".$flag."", "000000", "FFFFFF", TRUE, 12, "Franklin Gothic Book");
					$this->cellStyle("BX".$flag."", "000000", "FFFFFF", TRUE, 12, "Franklin Gothic Book");
					$this->excelfile->getActiveSheet()->getStyle('BW'.$flag)->applyFromArray($styleArray);
					$this->excelfile->getActiveSheet()->getStyle('BX'.$flag)->applyFromArray($styleArray);
					$this->excelfile->getActiveSheet()->getStyle('A'.$flag.':BM'.$flag)->applyFromArray($styleArray);

					//Begin: TOTALES PEDIDOS PENDIENTES
					$this->cellStyle("CD".$flag, "C00000", "000000", TRUE, 12, "Franklin Gothic Book");
					$this->cellStyle("CE".$flag, "01B0F0", "000000", TRUE, 12, "Franklin Gothic Book");
					$this->cellStyle("CF".$flag, "FF0000", "000000", TRUE, 12, "Franklin Gothic Book");
					$this->cellStyle("CG".$flag, "E26C0B", "000000", TRUE, 12, "Franklin Gothic Book");
					$this->cellStyle("CH".$flag, "C5C5C5", "000000", TRUE, 12, "Franklin Gothic Book");
					$this->cellStyle("CI".$flag, "92D051", "000000", TRUE, 12, "Franklin Gothic Book");
					$this->cellStyle("CJ".$flag, "B1A0C7", "000000", TRUE, 12, "Franklin Gothic Book");
					$this->cellStyle("CK".$flag, "DA9694", "000000", TRUE, 12, "Franklin Gothic Book");
					$this->cellStyle("CL".$flag, "4CACC6", "000000", TRUE, 12, "Franklin Gothic Book");
					$this->cellStyle("CM".$flag."", "000000", "FFFFFF", TRUE, 12, "Franklin Gothic Book");
					$this->cellStyle("CN".$flag."", "000000", "FFFFFF", TRUE, 12, "Franklin Gothic Book");
					$hoja->setCellValue("CM".$flag, "TOTAL");
					$hoja->setCellValue("CN".$flag, "PEDIDOS");
					//End: TOTALES PEDIDOS PENDIENTES
					foreach ($cotizacionesProveedor as $key => $value){
						//Existencias
						$this->excelfile->setActiveSheetIndex(0);
						$this->cellStyle("E".$flag1, "000000", "FFFFFF", FALSE, 12, "Franklin Gothic Book");
						$hoja1->setCellValue("E".$flag1, $value['familia']);
						$flag1 +=1;
						//Pedidos
						$this->excelfile->setActiveSheetIndex(1);
						$this->cellStyle("B".$flag, "000000", "FFFFFF", FALSE, 12, "Franklin Gothic Book");
						$hoja->setCellValue("B{$flag}", $value['familia']);
						$flag +=1;
						if ($value['articulos']) {
							foreach ($value['articulos'] as $key => $row){
								//Existencias
								$cargo = $row['cargo'];
								$registrazo = date('Y-m-d',strtotime($row['registrazo']));
								$this->excelfile->setActiveSheetIndex(0);
								$this->cellStyle("A".$flag1.":P".$flag1, "FFFFFF", "000000", FALSE, 12, "Franklin Gothic Book");
								
								$hoja1->setCellValue("D{$flag1}", $row['codigo'])->getStyle("D{$flag1}")->getNumberFormat()->setFormatCode('# ???/???');//Formato de fraccion
								if($row['color'] == '#92CEE3'){
									$this->cellStyle("D{$flag1}", "92CEE3", "000000", FALSE, 12, "Franklin Gothic Book");
								}else{
									$this->cellStyle("D{$flag1}", "FFFFFF", "000000", FALSE, 12, "Franklin Gothic Book");
								}
								$hoja1->setCellValue("E{$flag1}", $row['producto']);
								$hoja1->setCellValue("G{$flag1}", $row['promocion_first']);
								$hoja1->setCellValue("H{$flag1}", $row['cedis']);
								$hoja1->setCellValue("I{$flag1}", $row['abarrotes']);
								$hoja1->setCellValue("J{$flag1}", $row['pedregal']);
								$hoja1->setCellValue("K{$flag1}", $row['tienda']);
								$hoja1->setCellValue("L{$flag1}", $row['ultra']);
								$hoja1->setCellValue("M{$flag1}", $row['trincheras']);
								$hoja1->setCellValue("N{$flag1}", $row['mercado']);
								$hoja1->setCellValue("O{$flag1}", $row['tenencia']);
								$hoja1->setCellValue("P{$flag1}", $row['tijeras']);

								if ($row["imagen"] <> "" && !is_null($row["imagen"]) ) {
									$objDrawing = new PHPExcel_Worksheet_Drawing();
									$objDrawing->setName('COD'.$row['producto']);
									$objDrawing->setDescription('DESC'.$row['codigo']);
									$objDrawing->setPath("./Abarrotes/assets/img/productos/".$row["imagen"]."");
									$objDrawing->setWidth(50);
									$objDrawing->setHeight(50);
									$objDrawing->setCoordinates('F'.$flag1);
									$objDrawing->setOffsetX(5); 
									$objDrawing->setOffsetY(5);
									//$objDrawing->setUrl('http://abarrotesazteca.com/Abarrotes/assets/img/productos/'.$row["imagen"]);
									$objDrawing->setWorksheet($this->excelfile->getActiveSheet());
									$this->excelfile->getActiveSheet()->getRowDimension($flag1)->setRowHeight(60);
									$this->excelfile->getActiveSheet()->getStyleByColumnAndRow(10, $flag1)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_DATE_XLSX14);
									$this->excelfile->getActiveSheet()->getCell('F'.$flag1)->getHyperlink()->setUrl('http://abarrotesazteca.com/Abarrotes/assets/img/productos/'.str_replace("_thumb.",".",$row["imagen"]));
								}

								$hoja1->getStyle("A{$flag1}:P{$flag1}")
						                 ->getAlignment()
						                 ->setHorizontal(\PHPExcel_Style_Alignment::HORIZONTAL_LEFT);
						         
				                 
								//Pedidos
								$this->excelfile->setActiveSheetIndex(1);
								$this->cellStyle("A".$flag.":BX".$flag."", "FFFFFF", "000000", FALSE, 12, "Franklin Gothic Book");
								
								$hoja->setCellValue("A{$flag}", $row['codigo'])->getStyle("A{$flag}")->getNumberFormat()->setFormatCode('# ???/???');//Formato de fraccion
								if($row['color'] == '#92CEE3'){
									$this->cellStyle("A{$flag}", "92CEE3", "000000", FALSE, 12, "Franklin Gothic Book");
								}else{
									$this->cellStyle("A{$flag}", "FFFFFF", "000000", FALSE, 12, "Franklin Gothic Book");
								}
								$hoja->setCellValue("B{$flag}", $row['producto']);
								$hoja->setCellValue("F{$flag}", $row['proveedor_first']);
								$hoja->setCellValue("C{$flag}", $row['reales'])->getStyle("C{$flag}")->getNumberFormat()->setFormatCode('"$"#,##0.00_-');

								if(number_format(($row['precio_first'] - $row['reales']),2) === "0.01" || number_format(($row['precio_first'] - $row['reales']),2) === "-0.01"){
									$this->cellStyle("C{$flag}", "FFFFFF", "000000", FALSE, 12, "Franklin Gothic Book");
								}elseif($row['precio_first'] < $row['reales']){
									$this->cellStyle("C{$flag}", "FFFFFF", "E21600", FALSE, 12, "Franklin Gothic Book");
								}else{
									$this->cellStyle("C{$flag}", "FFFFFF", "249947", FALSE, 12, "Franklin Gothic Book");
								}

								if($row['precio_sistema'] < $row['precio_first']){
									$hoja->setCellValue("E{$flag}", $row['precio_first'])->getStyle("E{$flag}")->getNumberFormat()->setFormatCode('"$"#,##0.00_-');
									$this->cellStyle("E{$flag}", "FDB2B2", "E21111", FALSE, 12, "Franklin Gothic Book");
									$this->cellStyle("B{$flag}", "E21600", "000000", FALSE, 12, "Franklin Gothic Book");
								}else{
									$hoja->setCellValue("E{$flag}", $row['precio_first'])->getStyle("E{$flag}")->getNumberFormat()->setFormatCode('"$"#,##0.00_-');
									$this->cellStyle("E{$flag}", "96EAA8", "0C800C", FALSE, 12, "Franklin Gothic Book");
									$this->cellStyle("B{$flag}", "249947", "000000", FALSE, 12, "Franklin Gothic Book");
								}
								$hoja->setCellValue("H{$flag}", $row['precio_sistema'])->getStyle("H{$flag}")->getNumberFormat()->setFormatCode('"$"#,##0.00_-');//Formto de moneda
								$this->cellStyle("H".$flag, "FFFFFF","000000",  FALSE, 12, "Franklin Gothic Book");
								if($row['colorp'] == 1){
									$this->cellStyle("H{$flag}", "D6DCE4", "000000", FALSE, 12, "Franklin Gothic Book");
								}else{
									$this->cellStyle("H{$flag}", "FFFFFF", "000000", FALSE, 12, "Franklin Gothic Book");
								}
								$hoja->setCellValue("J{$flag}", $row['precio_four'])->getStyle("J{$flag}")->getNumberFormat()->setFormatCode('"$"#,##0.00_-');
								$this->cellStyle("J{$flag}", "FFFFFF", "000000", FALSE, 12, "Franklin Gothic Book");
								if($row['precio_sistema'] < $row['precio_next']){
									$hoja->setCellValue("K{$flag}", $row['precio_next'])->getStyle("K{$flag}")->getNumberFormat()->setFormatCode('"$"#,##0.00_-');
									$this->cellStyle("K{$flag}", "FDB2B2", "E21111", FALSE, 12, "Franklin Gothic Book");
								}else if($row['precio_next'] !== NULL){
									$hoja->setCellValue("K{$flag}", $row['precio_next'])->getStyle("K{$flag}")->getNumberFormat()->setFormatCode('"$"#,##0.00_-');
									$this->cellStyle("K{$flag}", "96EAA8", "0C800C", FALSE, 12, "Franklin Gothic Book");
								}else{
									$hoja->setCellValue("K{$flag}", $row['precio_next'])->getStyle("K{$flag}")->getNumberFormat()->setFormatCode('"$"#,##0.00_-');
									$this->cellStyle("K{$flag}", "FFFFFF", "000000", FALSE, 12, "Franklin Gothic Book");
								}
								$this->cellStyle("M".$flag.":BB".$flag, "FFFFFF", "000000", TRUE, 12, "Franklin Gothic Book");
								$hoja->setCellValue("L{$flag}", $row['proveedor_next']);
								$this->cellStyle("L".$flag, "FFFFFF", "000000", FALSE, 12, "Franklin Gothic Book");

								
								
								$hoja->setCellValue("O{$flag}", $row['cedis']);
								$hoja->setCellValue("AA{$flag}", $row['abarrotes']);
								$hoja->setCellValue("AF{$flag}", $row['pedregal']);
								$hoja->setCellValue("AK{$flag}", $row['tienda']);
								$hoja->setCellValue("AP{$flag}", $row['ultra']);
								$hoja->setCellValue("AU{$flag}", $row['trincheras']);
								$hoja->setCellValue("AZ{$flag}", $row['mercado']);
								$hoja->setCellValue("BE{$flag}", $row['tenencia']);
								$hoja->setCellValue("BJ{$flag}", $row['tijeras']);

								if ($row["lastfecha"] === NULL || $row["lastfecha"] === "") {
									$row["lastfecha"] = "NO PEDIDOS";
								}
								if ($row["unidad"] === NULL || $row["unidad"] === "") {
									$row["unidad"] = 1;
								}
								
								$antis = ((floatval($row[87]) * floatval($row["unidad"])) + ($row["unidad"] * $row["past"]["caja0"]) + $row["past"]["pz0"])/$row["unidad"];
								$hoja->setCellValue("M{$flag}", $row['caja0']);
								$hoja->setCellValue("N{$flag}", $row['pz0']);
								$hoja->setCellValue("P{$flag}", $row["past"]["caja0"]);
								$hoja->setCellValue("Q{$flag}", $row['ped0']);
								$this->cellStyle("Q{$flag}", "D4EAEF", "000000", TRUE, 12, "Franklin Gothic Book");

								$hoja->setCellValue("R{$flag}", $row['caja9']);
								$hoja->setCellValue("S{$flag}", $row['pz9']);
								$hoja->setCellValue("T{$flag}", $row["past"]["caja9"]);
								$hoja->setCellValue("U{$flag}", $row['ped9']);
								$this->cellStyle("U{$flag}", "D4EAEF", "000000", TRUE, 12, "Franklin Gothic Book");

								

								$hoja->setCellValue("V{$flag}", "=M".$flag."+R".$flag);
								$hoja->setCellValue("W{$flag}", "=N".$flag."+S".$flag);
								$hoja->setCellValue("X{$flag}", "=Q".$flag."+U".$flag);
								$this->cellStyle("X{$flag}", "D4EAEF", "000000", TRUE, 12, "Franklin Gothic Book");


								$hoja->setCellValue("Y{$flag}", $row['caja1']);
								$hoja->setCellValue("Z{$flag}", $row['pz1']);
								$hoja->setCellValue("AB{$flag}", $row["past"]["caja1"]);
								$hoja->setCellValue("AC{$flag}", $row['ped1']);
								$this->cellStyle("AC{$flag}", "D4EAEF", "000000", TRUE, 12, "Franklin Gothic Book");

								
								$hoja->setCellValue("AD{$flag}", $row['caja2']);
								$hoja->setCellValue("AE{$flag}", $row['pz2']);
								$hoja->setCellValue("AG{$flag}", $row["past"]["caja2"]);
								$hoja->setCellValue("AH{$flag}", $row['ped2']);
								$this->cellStyle("AH{$flag}", "D4EAEF", "000000", TRUE, 12, "Franklin Gothic Book");

								
								$hoja->setCellValue("AI{$flag}", $row['caja3']);
								$hoja->setCellValue("AJ{$flag}", $row['pz3']);
								$hoja->setCellValue("AL{$flag}", $row["past"]["caja3"]);
								$hoja->setCellValue("AM{$flag}", $row['ped3']);
								$this->cellStyle("AM{$flag}", "D4EAEF", "000000", TRUE, 12, "Franklin Gothic Book");

								
								$hoja->setCellValue("AN{$flag}", $row['caja4']);
								$hoja->setCellValue("AO{$flag}", $row['pz4']);
								$hoja->setCellValue("AQ{$flag}", $row["past"]["caja4"]);
								$hoja->setCellValue("AR{$flag}", $row['ped4']);
								$this->cellStyle("AR{$flag}", "D4EAEF", "000000", TRUE, 12, "Franklin Gothic Book");

								$hoja->setCellValue("AS{$flag}", $row['caja5']);
								$hoja->setCellValue("AT{$flag}", $row['pz5']);
								$hoja->setCellValue("AV{$flag}", $row["past"]["caja5"]);
								$hoja->setCellValue("AW{$flag}", $row['ped5']);
								$this->cellStyle("AW{$flag}", "D4EAEF", "000000", TRUE, 12, "Franklin Gothic Book");

								
								$hoja->setCellValue("AX{$flag}", $row['caja6']);
								$hoja->setCellValue("AY{$flag}", $row['pz6']);
								$hoja->setCellValue("BA{$flag}", $row["past"]["caja6"]);
								$hoja->setCellValue("BB{$flag}", $row['ped6']);
								$this->cellStyle("BB{$flag}", "D4EAEF", "000000", TRUE, 12, "Franklin Gothic Book");

								$hoja->setCellValue("BC{$flag}", $row['caja7']);
								$hoja->setCellValue("BD{$flag}", $row['pz7']);
								$hoja->setCellValue("BF{$flag}", $row["past"]["caja7"]);
								$hoja->setCellValue("BG{$flag}", $row['ped7']);
								$this->cellStyle("BG{$flag}", "D4EAEF", "000000", TRUE, 12, "Franklin Gothic Book");

								
								$hoja->setCellValue("BH{$flag}", $row['caja8']);
								$hoja->setCellValue("BI{$flag}", $row['pz8']);
								$hoja->setCellValue("BK{$flag}", $row["past"]["caja8"]);
								$hoja->setCellValue("BL{$flag}", $row['ped8']);
								$this->cellStyle("BL{$flag}", "D4EAEF", "000000", TRUE, 12, "Franklin Gothic Book");

								

								$this->cellStyle("BM{$flag}", "FFFFFF", "000000", TRUE, 12, "Franklin Gothic Book");
								$hoja->setCellValue("BM{$flag}", $row['promocion_first']);
								$hoja->setCellValue("BN{$flag}", "=E".$flag."*Q".$flag)->getStyle("BN{$flag}")->getNumberFormat()->setFormatCode('"$"#,##0.00_-');
								$hoja->setCellValue("BO{$flag}", "=E".$flag."*AC".$flag)->getStyle("BO{$flag}")->getNumberFormat()->setFormatCode('"$"#,##0.00_-');
								$hoja->setCellValue("BP{$flag}", "=E".$flag."*AH".$flag)->getStyle("BP{$flag}")->getNumberFormat()->setFormatCode('"$"#,##0.00_-');
								$hoja->setCellValue("BQ{$flag}", "=E".$flag."*AM".$flag)->getStyle("BQ{$flag}")->getNumberFormat()->setFormatCode('"$"#,##0.00_-');
								$hoja->setCellValue("BR{$flag}", "=E".$flag."*AR".$flag)->getStyle("BR{$flag}")->getNumberFormat()->setFormatCode('"$"#,##0.00_-');
								$hoja->setCellValue("BS{$flag}", "=E".$flag."*AW".$flag)->getStyle("BS{$flag}")->getNumberFormat()->setFormatCode('"$"#,##0.00_-');
								$hoja->setCellValue("BT{$flag}", "=E".$flag."*BB".$flag)->getStyle("BT{$flag}")->getNumberFormat()->setFormatCode('"$"#,##0.00_-');
								$hoja->setCellValue("BU{$flag}", "=E".$flag."*BG".$flag)->getStyle("BU{$flag}")->getNumberFormat()->setFormatCode('"$"#,##0.00_-');
								$hoja->setCellValue("BV{$flag}", "=E".$flag."*BL".$flag)->getStyle("BV{$flag}")->getNumberFormat()->setFormatCode('"$"#,##0.00_-');
								$this->cellStyle("BW{$flag}", "D4EAEF", "000000", FALSE, 12, "Franklin Gothic Book");
								$hoja->setCellValue("BW{$flag}", "=SUM(BN".$flag.":BV".$flag.")")->getStyle("BW{$flag}")->getNumberFormat()->setFormatCode('"$"#,##0.00_-');
								$this->cellStyle("BX{$flag}", "C2B90A", "000000", FALSE, 12, "Franklin Gothic Book");
								$hoja->setCellValue("BX{$flag}", "=Q".$flag."+AC".$flag."+AH".$flag."+AM".$flag."+AR".$flag."+AW".$flag."+BB".$flag."+BG".$flag."+BL".$flag."");

								//Begin: TOTALES PEDIDOS PENDIENTES
								$hoja->setCellValue("CD{$flag}", "=E".$flag."*O".$flag)->getStyle("CD{$flag}")->getNumberFormat()->setFormatCode('"$"#,##0.00_-');
								$hoja->setCellValue("CE{$flag}", "=E".$flag."*AA".$flag)->getStyle("CE{$flag}")->getNumberFormat()->setFormatCode('"$"#,##0.00_-');
								$hoja->setCellValue("CF{$flag}", "=E".$flag."*AF".$flag)->getStyle("CF{$flag}")->getNumberFormat()->setFormatCode('"$"#,##0.00_-');
								$hoja->setCellValue("CG{$flag}", "=E".$flag."*AK".$flag)->getStyle("CG{$flag}")->getNumberFormat()->setFormatCode('"$"#,##0.00_-');
								$hoja->setCellValue("CH{$flag}", "=E".$flag."*AP".$flag)->getStyle("CH{$flag}")->getNumberFormat()->setFormatCode('"$"#,##0.00_-');
								$hoja->setCellValue("CI{$flag}", "=E".$flag."*AU".$flag)->getStyle("CI{$flag}")->getNumberFormat()->setFormatCode('"$"#,##0.00_-');
								$hoja->setCellValue("CJ{$flag}", "=E".$flag."*AZ".$flag)->getStyle("CJ{$flag}")->getNumberFormat()->setFormatCode('"$"#,##0.00_-');
								$hoja->setCellValue("CK{$flag}", "=E".$flag."*BE".$flag)->getStyle("CK{$flag}")->getNumberFormat()->setFormatCode('"$"#,##0.00_-');
								$hoja->setCellValue("CL{$flag}", "=E".$flag."*BJ".$flag)->getStyle("CL{$flag}")->getNumberFormat()->setFormatCode('"$"#,##0.00_-');
								$this->cellStyle("CM{$flag}", "000000", "FFFFFF", FALSE, 12, "Franklin Gothic Book");
								$hoja->setCellValue("CM{$flag}", "=SUM(CD".$flag.":CL".$flag.")")->getStyle("CM{$flag}")->getNumberFormat()->setFormatCode('"$"#,##0.00_-');
								$this->cellStyle("CN{$flag}", "000000", "FFFFFF", FALSE, 12, "Franklin Gothic Book");
								$hoja->setCellValue("CN{$flag}", "=O".$flag."+AA".$flag."+AF".$flag."+AK".$flag."+AP".$flag."+AU".$flag."+AZ".$flag."+BE".$flag."+BJ".$flag."");
								//End: TOTALES PEDIDOS PENDIENTES
								$border_style= array('borders' => array('right' => array('style' =>
									PHPExcel_Style_Border::BORDER_THIN,'color' => array('argb' => '000000'),)));
								$this->excelfile->setActiveSheetIndex(1);
								
								$this->excelfile->getActiveSheet()->getStyle('A'.$flag.':BX'.$flag)->applyFromArray($styleArray);
								$this->excelfile->getActiveSheet()->getStyle('BW'.$flag.':BX'.$flag)->applyFromArray($styleArray);
								if($row['precio_sistema'] == 0){
									$row['precio_sistema'] = 1;
								}
								if($row['precio_four'] == 0){
									$row['precio_four'] = 1;
								}
									$hoja->setCellValue("G{$flag}",100 - ($row['precio_first'] * 100 / $row['precio_sistema']))->getStyle("G{$flag}")->getNumberFormat()->setFormatCode('"%"#,##0.00_-');;
									$this->cellStyle("G".$flag, "FF9999", "000000", FALSE, 10, "Franklin Gothic Book");

									$hoja->setCellValue("I{$flag}", 100 - ($row['precio_sistema'] * 100 / $row['precio_four']))->getStyle("I{$flag}")->getNumberFormat()->setFormatCode('"%"#,##0.00_-');;
									$this->cellStyle("I".$flag, "FF9999", "000000", FALSE, 10, "Franklin Gothic Book");
								
								$this->excelfile->setActiveSheetIndex(0);
								$this->excelfile->getActiveSheet()->getStyle('A'.$flag1.':P'.$flag1)->applyFromArray($styleArray);
								$hoja->getStyle("A{$flag}:I{$flag}")
						                 ->getAlignment()
						                 ->setHorizontal(\PHPExcel_Style_Alignment::HORIZONTAL_LEFT);

						   
								$flag ++;
								$flag1 ++;
							}
						}
					}
					$flagf = $flag;
					$flagfs = $flag - 1;
					$hoja->setCellValue("BN{$flagf}", "=SUM(BN5:BN".$flagfs.")")->getStyle("BN{$flag}")->getNumberFormat()->setFormatCode('"$"#,##0.00_-');
					$hoja->setCellValue("BO{$flagf}", "=SUM(BO5:BO".$flagfs.")")->getStyle("BO{$flag}")->getNumberFormat()->setFormatCode('"$"#,##0.00_-');
					$hoja->setCellValue("BP{$flagf}", "=SUM(BP5:BP".$flagfs.")")->getStyle("BP{$flag}")->getNumberFormat()->setFormatCode('"$"#,##0.00_-');
					$hoja->setCellValue("BQ{$flagf}", "=SUM(BQ5:BQ".$flagfs.")")->getStyle("BQ{$flag}")->getNumberFormat()->setFormatCode('"$"#,##0.00_-');
					$hoja->setCellValue("BR{$flagf}", "=SUM(BR5:BR".$flagfs.")")->getStyle("BR{$flag}")->getNumberFormat()->setFormatCode('"$"#,##0.00_-');
					$hoja->setCellValue("BS{$flagf}", "=SUM(BS5:BS".$flagfs.")")->getStyle("BS{$flag}")->getNumberFormat()->setFormatCode('"$"#,##0.00_-');
					$hoja->setCellValue("BT{$flagf}", "=SUM(BT5:BT".$flagfs.")")->getStyle("BT{$flag}")->getNumberFormat()->setFormatCode('"$"#,##0.00_-');
					$hoja->setCellValue("BU{$flagf}", "=SUM(BU5:BU".$flagfs.")")->getStyle("BU{$flag}")->getNumberFormat()->setFormatCode('"$"#,##0.00_-');
					$hoja->setCellValue("BV{$flagf}", "=SUM(BV5:BV".$flagfs.")")->getStyle("BV{$flag}")->getNumberFormat()->setFormatCode('"$"#,##0.00_-');
					$this->cellStyle("BW{$flagf}", "000000", "FFFFFF", FALSE, 12, "Franklin Gothic Book");
					$hoja->setCellValue("BW{$flagf}", "=SUM(BW5:BW".$flagfs.")")->getStyle("BW{$flagf}")->getNumberFormat()->setFormatCode('"$"#,##0.00_-');
					$sumall[1] .= "BN".$flagf."+";
					$sumall[2] .= "BO".$flagf."+";
					$sumall[3] .= "BP".$flagf."+";
					$sumall[4] .= "BQ".$flagf."+";
					$sumall[5] .= "BR".$flagf."+";
					$sumall[6] .= "BS".$flagf."+";
					$sumall[7] .= "BT".$flagf."+";
					$sumall[8] .= "BU".$flagf."+";
					$sumall[9] .= "BV".$flagf."+";
					$sumall[10] .= "BW".$flagf."+";
					//Begin: TOTALES PEDIDOS PENDIENTES
					$hoja->setCellValue("CD{$flag}", "=SUM(CD".$flage.":CD".$flagfs.")")->getStyle("CD{$flag}")->getNumberFormat()->setFormatCode('"$"#,##0.00_-');
					$hoja->setCellValue("CE{$flag}", "=SUM(CE".$flage.":CE".$flagfs.")")->getStyle("CE{$flag}")->getNumberFormat()->setFormatCode('"$"#,##0.00_-');
					$hoja->setCellValue("CF{$flag}", "=SUM(CF".$flage.":CF".$flagfs.")")->getStyle("CF{$flag}")->getNumberFormat()->setFormatCode('"$"#,##0.00_-');
					$hoja->setCellValue("CG{$flag}", "=SUM(CG".$flage.":CG".$flagfs.")")->getStyle("CG{$flag}")->getNumberFormat()->setFormatCode('"$"#,##0.00_-');
					$hoja->setCellValue("CH{$flag}", "=SUM(CH".$flage.":CH".$flagfs.")")->getStyle("CH{$flag}")->getNumberFormat()->setFormatCode('"$"#,##0.00_-');
					$hoja->setCellValue("CI{$flag}", "=SUM(CI".$flage.":CI".$flagfs.")")->getStyle("CI{$flag}")->getNumberFormat()->setFormatCode('"$"#,##0.00_-');
					$hoja->setCellValue("CJ{$flag}", "=SUM(CJ".$flage.":CJ".$flagfs.")")->getStyle("CJ{$flag}")->getNumberFormat()->setFormatCode('"$"#,##0.00_-');
					$hoja->setCellValue("CK{$flag}", "=SUM(CK".$flage.":CK".$flagfs.")")->getStyle("CK{$flag}")->getNumberFormat()->setFormatCode('"$"#,##0.00_-');
					$hoja->setCellValue("CL{$flag}", "=SUM(CL".$flage.":CL".$flagfs.")")->getStyle("CL{$flag}")->getNumberFormat()->setFormatCode('"$"#,##0.00_-');
					$hoja->setCellValue("CM{$flag}", "=SUM(CM".$flage.":CM".$flagfs.")")->getStyle("CM{$flag}")->getNumberFormat()->setFormatCode('"$"#,##0.00_-');
					//End: TOTALES PEDIDOS PENDIENTES
					
					$flag++;
					$flag1++;
					$flag++;
					$flag1++;
					$flag1++;
					$flag1++;
					$flag++;
					$flag++;
				}
			}
		}
		$flag++;
		$bandera = $flag;
		
		$hoja->mergeCells('G'.$flag.':J'.$flag);
		$this->cellStyle("G".$flag, "000000", "FFFFFF", TRUE, 12, "Franklin Gothic Book");
		$hoja->setCellValue("G".$flag, "TOTALES PENDIENTES '".$provname."'");
		$flag++;
		$hoja->mergeCells('G'.$flag.':H'.$flag);
		$hoja->mergeCells('I'.$flag.':J'.$flag);
		$this->cellStyle("G".$flag, "000000", "FFFFFF", TRUE, 12, "Franklin Gothic Book");
		$hoja->setCellValue("G".$flag, "CEDIS");
		$hoja->setCellValue("I{$flag}", "=CD{$flagf}")->getStyle("I{$flag}")->getNumberFormat()->setFormatCode('"$"#,##0.00_-');
		$flag++;
		$hoja->mergeCells('G'.$flag.':H'.$flag);
		$hoja->mergeCells('I'.$flag.':J'.$flag);
		$this->cellStyle("G".$flag, "000000", "FFFFFF", TRUE, 12, "Franklin Gothic Book");
		$hoja->setCellValue("G".$flag, "ABARROTES");
		$hoja->setCellValue("I{$flag}", "=CE{$flagf}")->getStyle("I{$flag}")->getNumberFormat()->setFormatCode('"$"#,##0.00_-');
		$flag++;
		$hoja->mergeCells('G'.$flag.':H'.$flag);
		$hoja->mergeCells('I'.$flag.':J'.$flag);
		$this->cellStyle("G".$flag, "000000", "FFFFFF", TRUE, 12, "Franklin Gothic Book");
		$hoja->setCellValue("G".$flag, "VILLAS");
		$hoja->setCellValue("I{$flag}", "=CF{$flagf}")->getStyle("I{$flag}")->getNumberFormat()->setFormatCode('"$"#,##0.00_-');
		$flag++;
		$hoja->mergeCells('G'.$flag.':H'.$flag);
		$hoja->mergeCells('I'.$flag.':J'.$flag);
		$this->cellStyle("G".$flag, "000000", "FFFFFF", TRUE, 12, "Franklin Gothic Book");
		$hoja->setCellValue("G".$flag, "TIENDA");
		$hoja->setCellValue("I{$flag}", "=CG{$flagf}")->getStyle("I{$flag}")->getNumberFormat()->setFormatCode('"$"#,##0.00_-');
		$flag++;
		$hoja->mergeCells('G'.$flag.':H'.$flag);
		$hoja->mergeCells('I'.$flag.':J'.$flag);
		$this->cellStyle("G".$flag, "000000", "FFFFFF", TRUE, 12, "Franklin Gothic Book");
		$hoja->setCellValue("G".$flag, "ULTRAMARINOS");
		$hoja->setCellValue("I{$flag}", "=CH{$flagf}")->getStyle("I{$flag}")->getNumberFormat()->setFormatCode('"$"#,##0.00_-');
		$flag++;
		$hoja->mergeCells('G'.$flag.':H'.$flag);
		$hoja->mergeCells('I'.$flag.':J'.$flag);
		$this->cellStyle("G".$flag, "000000", "FFFFFF", TRUE, 12, "Franklin Gothic Book");
		$hoja->setCellValue("G".$flag, "TRINCHERAS");
		$hoja->setCellValue("I{$flag}", "=CI{$flagf}")->getStyle("I{$flag}")->getNumberFormat()->setFormatCode('"$"#,##0.00_-');
		$flag++;
		$hoja->mergeCells('G'.$flag.':H'.$flag);
		$hoja->mergeCells('I'.$flag.':J'.$flag);
		$this->cellStyle("G".$flag, "000000", "FFFFFF", TRUE, 12, "Franklin Gothic Book");
		$hoja->setCellValue("G".$flag, "AZT MERCADO");
		$hoja->setCellValue("I{$flag}", "=CJ{$flagf}")->getStyle("I{$flag}")->getNumberFormat()->setFormatCode('"$"#,##0.00_-');
		$flag++;
		$hoja->mergeCells('G'.$flag.':H'.$flag);
		$hoja->mergeCells('I'.$flag.':J'.$flag);
		$this->cellStyle("G".$flag, "000000", "FFFFFF", TRUE, 12, "Franklin Gothic Book");
		$hoja->setCellValue("G".$flag, "TENENCIA");
		$hoja->setCellValue("I{$flag}", "=CK{$flagf}")->getStyle("I{$flag}")->getNumberFormat()->setFormatCode('"$"#,##0.00_-');
		$flag++;
		$hoja->mergeCells('G'.$flag.':H'.$flag);
		$hoja->mergeCells('I'.$flag.':J'.$flag);
		$this->cellStyle("G".$flag, "000000", "FFFFFF", TRUE, 12, "Franklin Gothic Book");
		$hoja->setCellValue("G".$flag, "TIJERAS");
		$hoja->setCellValue("I{$flag}", "=CL{$flagf}")->getStyle("I{$flag}")->getNumberFormat()->setFormatCode('"$"#,##0.00_-');
		$flag++;
		$hoja->mergeCells('G'.$flag.':H'.$flag);
		$hoja->mergeCells('I'.$flag.':J'.$flag);
		$this->cellStyle("G".$flag, "000000", "FFFFFF", TRUE, 12, "Franklin Gothic Book");
		$hoja->setCellValue("G".$flag, "TOTAL");
		$hoja->setCellValue("I{$flag}", "=CM{$flagf}")->getStyle("I{$flag}")->getNumberFormat()->setFormatCode('"$"#,##0.00_-');
		$flag = $bandera;

		$hoja->mergeCells('B'.$flag.':C'.$flag);
		$this->cellStyle("B".$flag, "000000", "FFFFFF", TRUE, 12, "Franklin Gothic Book");
		$hoja->setCellValue("B".$flag, "TOTALES EN GENERAL");
		$flag++;
		$this->cellStyle("B".$flag, "C00000", "000000", TRUE, 12, "Franklin Gothic Book");
		$hoja->setCellValue("B".$flag, "CEDIS");
		$hoja->setCellValue("C{$flag}", "=(".substr($sumall[1],0,-1).")")->getStyle("C{$flag}")->getNumberFormat()->setFormatCode('"$"#,##0.00_-');
		$flag++;
		$this->cellStyle("B".$flag, "01B0F0", "000000", TRUE, 12, "Franklin Gothic Book");
		$hoja->setCellValue("B".$flag, "ABARROTES");
		$hoja->setCellValue("C{$flag}", "=(".substr($sumall[2],0,-1).")")->getStyle("C{$flag}")->getNumberFormat()->setFormatCode('"$"#,##0.00_-');
		$flag++;
		$this->cellStyle("B".$flag, "FF0000", "000000", TRUE, 12, "Franklin Gothic Book");
		$hoja->setCellValue("B".$flag, "VILLAS");
		$hoja->setCellValue("C{$flag}", "=(".substr($sumall[3],0,-1).")")->getStyle("C{$flag}")->getNumberFormat()->setFormatCode('"$"#,##0.00_-');
		$flag++;
		$this->cellStyle("B".$flag, "E26C0B", "000000", TRUE, 12, "Franklin Gothic Book");
		$hoja->setCellValue("B".$flag, "TIENDA");
		$hoja->setCellValue("C{$flag}", "=(".substr($sumall[4],0,-1).")")->getStyle("C{$flag}")->getNumberFormat()->setFormatCode('"$"#,##0.00_-');
		$flag++;
		$this->cellStyle("B".$flag, "C5C5C5", "000000", TRUE, 12, "Franklin Gothic Book");
		$hoja->setCellValue("B".$flag, "ULTRAMARINOS");
		$hoja->setCellValue("C{$flag}", "=(".substr($sumall[5],0,-1).")")->getStyle("C{$flag}")->getNumberFormat()->setFormatCode('"$"#,##0.00_-');
		$flag++;
		$this->cellStyle("B".$flag, "92D051", "000000", TRUE, 12, "Franklin Gothic Book");
		$hoja->setCellValue("B".$flag, "TRINCHERAS");
		$hoja->setCellValue("C{$flag}", "=(".substr($sumall[6],0,-1).")")->getStyle("C{$flag}")->getNumberFormat()->setFormatCode('"$"#,##0.00_-');
		$flag++;
		$this->cellStyle("B".$flag, "B1A0C7", "000000", TRUE, 12, "Franklin Gothic Book");
		$hoja->setCellValue("B".$flag, "AZT MERCADO");
		$hoja->setCellValue("C{$flag}", "=(".substr($sumall[7],0,-1).")")->getStyle("C{$flag}")->getNumberFormat()->setFormatCode('"$"#,##0.00_-');
		$flag++;
		$this->cellStyle("B".$flag, "DA9694", "000000", TRUE, 12, "Franklin Gothic Book");
		$hoja->setCellValue("B".$flag, "TENENCIA");
		$hoja->setCellValue("C{$flag}", "=(".substr($sumall[8],0,-1).")")->getStyle("C{$flag}")->getNumberFormat()->setFormatCode('"$"#,##0.00_-');
		$flag++;
		$this->cellStyle("B".$flag, "4CACC6", "000000", TRUE, 12, "Franklin Gothic Book");
		$hoja->setCellValue("B".$flag, "TIJERAS");
		$hoja->setCellValue("C{$flag}", "=(".substr($sumall[9],0,-1).")")->getStyle("C{$flag}")->getNumberFormat()->setFormatCode('"$"#,##0.00_-');
		$flag++;
		$this->cellStyle("B".$flag, "000000", "FFFFFF", TRUE, 12, "Franklin Gothic Book");
		$hoja->setCellValue("B".$flag, "TOTAL");
		$hoja->setCellValue("C{$flag}", "=(".substr($sumall[10],0,-1).")")->getStyle("C{$flag}")->getNumberFormat()->setFormatCode('"$"#,##0.00_-');
		$flag++;

		$dias = array("DOMINGO","LUNES","MARTES","MIÉRCOLES","JUEVES","VIERNES","SÁBADO");
		$meses = array("ENERO","FEBRERO","MARZO","ABRIL","MAYO","JUNIO","JULIO","AGOSTO","SEPTIEMBRE","OCTUBRE","NOVIEMBRE","DICIEMBRE");
		$fecha =  $dias[date('w')]." ".date('d')." DE ".$meses[date('n')-1]. " DEL ".date('Y') ;
		$file_name = "FORMATO CUETARA ".$fecha.".xlsx"; //Nombre del documento con extención
		$excel_Writer = PHPExcel_IOFactory::createWriter($this->excelfile, "Excel2007");
		header("Content-Type: application/vnd.ms-excel; charset=utf-8");
		header("Content-Disposition: attachment;filename=".$file_name);
		header("Cache-Control: max-age=0");
		$excel_Writer->save("php://output");
		/*$excel_Writer = new PHPExcel_Writer_Excel2007($this->excelfile);
		$excel_Writer->setOffice2003Compatibility(true);
		$excel_Writer->save("php://output");*/
	}

	private function fill_mexicano($id_proves,$proves,$prs){
		$flag =1;
		$flag1 = 1;
		$array = "";
		$array2 = "";
		$filenam = "";
		$id_proves = $this->input->post('id_proves4');
		$proves = $this->input->post('id_proves2');
		$prs = substr($id_proves,0,6);
		$filenam = $id_proves;
		$array = (object)['0'=>(object)['nombre' => $id_proves]];
		//$this->jsonResponse($array);
		ini_set("memory_limit", "-1");
		ini_set("max_execution_time", "-1");
		$this->load->library("excelfile");
		$hoja1 = $this->excelfile->setActiveSheetIndex(0);
		$this->excelfile->setActiveSheetIndex(0)->setTitle("EXISTENCIAS");
		$this->excelfile->createSheet();
        $hoja = $this->excelfile->setActiveSheetIndex(1);
        $hoja->setTitle("PEDIDO");
		$styleArray = array(
		  'borders' => array(
		    'allborders' => array(
		      'style' => PHPExcel_Style_Border::BORDER_THIN
		    )
		  )
		);
		$hoja->getColumnDimension('A')->setWidth("20");
		$hoja->getColumnDimension('B')->setWidth("70");
		$hoja->getColumnDimension('C')->setWidth("15");
		$hoja->getColumnDimension('D')->setWidth("8");

		$hoja->getColumnDimension('E')->setWidth("15");
		$hoja->getColumnDimension('G')->setWidth("8");
		$hoja->getColumnDimension('I')->setWidth("8");
		$hoja->getColumnDimension('F')->setWidth("20");
		$hoja->getColumnDimension('H')->setWidth("15");

		$hoja1->getColumnDimension('A')->setWidth("6");
		$hoja1->getColumnDimension('B')->setWidth("6");
		$hoja1->getColumnDimension('C')->setWidth("6");
		$hoja1->getColumnDimension('D')->setWidth("25");
		$hoja1->getColumnDimension('E')->setWidth("47");
		$hoja1->getColumnDimension('G')->setWidth("50");

		$hoja->getColumnDimension('BM' )->setWidth("70");
		$hoja->getColumnDimension('L')->setWidth("20");

		$flagBorder = 0;
		$flagBorder1 = 1;
		$flagBorder2 = 0;
		$flagBorder3 = 1;
		$flage = 5;
		$i = 0;
		$sumall = array(1 => "", 2 => "", 3 => "", 4 => "", 5 => "", 6 => "", 7 => "", 8 => "", 9 => "", 10 => "");
		$provname = "";
		if ($array){
			foreach ($array as $key => $value){
				$fecha = new DateTime(date('Y-m-d H:i:s'));
				$intervalo = new DateInterval('P2D');
				$fecha->add($intervalo);
				$cotizacionesProveedor = $this->ct_mdl->getPedidosAllMexicano(NULL, $fecha->format('Y-m-d H:i:s'), 0);
			
				$difff = 0.01;
				$flag2 = 3;
				$cargo = "";
				if ($cotizacionesProveedor){
					//HOJA EXISTENCIAS
					$this->excelfile->setActiveSheetIndex(0);
					if($i > 0){
						$flagBorder = $flag1 ;
						$this->excelfile->getActiveSheet()->getStyle('A'.$flagBorder1.':G'.$flagBorder)->applyFromArray($styleArray);
						$flagBorder1 = $flag1;
					}
					$hoja1->mergeCells('A'.$flag1.':G'.$flag1);
					$this->cellStyle("A".$flag1, "FFFFFF", "000000", TRUE, 12, "Franklin Gothic Book");
					$hoja1->setCellValue("A".$flag1."", "GRUPO ABARROTES AZTECA");
					$this->excelfile->getActiveSheet()->getStyle('A'.$flag1.':G'.$flag1)->applyFromArray($styleArray);
					$flag1++;
					$hoja1->mergeCells('A'.$flag1.':G'.$flag1);
					$this->cellStyle("A".$flag1, "FFFFFF", "000000", TRUE, 12, "Franklin Gothic Book");
					$hoja1->setCellValue("A".$flag1."", "PEDIDOS A '".$value->nombre."' ".date("d-m-Y"));
					$provname = $value->nombre;
					$this->excelfile->getActiveSheet()->getStyle('A'.$flag1.':G'.$flag1)->applyFromArray($styleArray);
					$flag1++;
					$this->cellStyle("A".$flag1.":D".$flag1, "000000", "FFFFFF", TRUE, 12, "Franklin Gothic Book");
					$hoja1->mergeCells('A'.$flag1.':B'.$flag1);
					$hoja1->setCellValue("A".$flag1, "EXISTENCIAS");
					$hoja1->setCellValue("E".$flag1, "DESCRIPCIÓN");
					$this->cellStyle("E".$flag1, "000000", "FFFFFF", TRUE, 12, "Franklin Gothic Book");

					$this->cellStyle("H".$flag1."", "000000", "FFFFFF", TRUE, 12, "Franklin Gothic Book");
					$hoja1->setCellValue("H".$flag1."", "PENDIENT");
					$this->cellStyle("I".$flag1."", "000000", "FFFFFF", TRUE, 12, "Franklin Gothic Book");
					$hoja1->setCellValue("I".$flag1."", "PENDIENT");
					$this->cellStyle("J".$flag1."", "000000", "FFFFFF", TRUE, 12, "Franklin Gothic Book");
					$hoja1->setCellValue("J".$flag1."", "PENDIENT");
					$this->cellStyle("K".$flag1."", "000000", "FFFFFF", TRUE, 12, "Franklin Gothic Book");
					$hoja1->setCellValue("K".$flag1."", "PENDIENT");
					$this->cellStyle("L".$flag1."", "000000", "FFFFFF", TRUE, 12, "Franklin Gothic Book");
					$hoja1->setCellValue("L".$flag1."", "PENDIENT");
					$this->cellStyle("M".$flag1."", "000000", "FFFFFF", TRUE, 12, "Franklin Gothic Book");
					$hoja1->setCellValue("M".$flag1."", "PENDIENT");
					$this->cellStyle("N".$flag1."", "000000", "FFFFFF", TRUE, 12, "Franklin Gothic Book");
					$hoja1->setCellValue("N".$flag1."", "PENDIENT");
					$this->cellStyle("O".$flag1."", "000000", "FFFFFF", TRUE, 12, "Franklin Gothic Book");
					$hoja1->setCellValue("O".$flag1."", "PENDIENT");
					$this->cellStyle("P".$flag1."", "000000", "FFFFFF", TRUE, 12, "Franklin Gothic Book");
					$hoja1->setCellValue("P".$flag1."", "PENDIENT");
					$this->excelfile->getActiveSheet()->getStyle('A'.$flag1.':P'.$flag1)->applyFromArray($styleArray);
					$flag1++;
					$this->cellStyle("A".$flag1.":G".$flag1, "000000", "FFFFFF", TRUE, 12, "Franklin Gothic Book");
					$hoja1->setCellValue("A".$flag1, "CAJAS");
					$hoja1->setCellValue("B".$flag1, "PZAS");
					$hoja1->setCellValue("C".$flag1, "PEDIDO");
					$hoja1->setCellValue("D".$flag1, "CÓDIGO");
					$hoja1->setCellValue("G".$flag1, "PROMOCIÓN");
					$hoja1->setCellValue("F".$flag1, "IMAGEN")->getColumnDimension('F')->setWidth(18);
					
					$this->cellStyle("H".$flag1, "C00000", "000000", TRUE, 10, "Franklin Gothic Book");
					$this->cellStyle("I".$flag1, "01B0F0", "000000", TRUE, 10, "Franklin Gothic Book");
					$this->cellStyle("J".$flag1, "FF0000", "000000", TRUE, 10, "Franklin Gothic Book");
					$this->cellStyle("K".$flag1, "E26C0B", "000000", TRUE, 10, "Franklin Gothic Book");
					$this->cellStyle("L".$flag1, "C5C5C5", "000000", TRUE, 10, "Franklin Gothic Book");
					$this->cellStyle("M".$flag1, "92D051", "000000", TRUE, 10, "Franklin Gothic Book");
					$this->cellStyle("N".$flag1, "B1A0C7", "000000", TRUE, 10, "Franklin Gothic Book");
					$this->cellStyle("O".$flag1, "DA9694", "000000", TRUE, 10, "Franklin Gothic Book");
					$this->cellStyle("P".$flag1, "4CACC6", "000000", TRUE, 10, "Franklin Gothic Book");
					$hoja1->setCellValue("H".$flag1."", "CEDIS");
					$hoja1->setCellValue("I".$flag1."", "ABARROTES");
					$hoja1->setCellValue("J".$flag1."", "VILLAS");
					$hoja1->setCellValue("K".$flag1."", "TIENDA");
					$hoja1->setCellValue("L".$flag1."", "ULTRA");
					$hoja1->setCellValue("M".$flag1."", "TRINCHERAS");
					$hoja1->setCellValue("N".$flag1."", "MERCADO");
					$hoja1->setCellValue("O".$flag1."", "TENENCIA");
					$hoja1->setCellValue("P".$flag1."", "TIJERAS");
					$this->excelfile->getActiveSheet()->getStyle('A'.$flag1.':P'.$flag1)->applyFromArray($styleArray);
					//$flag1++;
					$this->excelfile->setActiveSheetIndex(1);
					if($i > 0){
						$flagBorder2 = $flag ;
						$this->excelfile->getActiveSheet()->getStyle('A'.$flagBorder3.':AD'.$flagBorder2)->applyFromArray($styleArray);
						$flagBorder3 = $flag;
					}


					//HOJA PEDIDOS
					$this->cellStyle("A".$flag, "FFFFFF", "000000", TRUE, 12, "Franklin Gothic Book");
					$hoja->setCellValue("A".$flag."", "CEDIS, ABARROTES,PEDREGAL, TIENDA, ULTRAMARINOS, TRINCHERAS, MERCADO, TIJERAS, Y TENENCIA AZTECA AUTOSERVICIOS SA. DE CV.");
					$hoja->mergeCells('A'.$flag.':BM'.$flag);
					$this->excelfile->getActiveSheet()->getStyle('A'.$flag.':BM'.$flag)->applyFromArray($styleArray);
					$flag++;
					$hoja->mergeCells('A'.$flag.':L'.$flag);
					$hoja->mergeCells('M'.$flag.':Q'.$flag);
					$hoja->mergeCells('R'.$flag.':U'.$flag);
					$hoja->mergeCells('V'.$flag.':X'.$flag);
					$hoja->mergeCells('Y'.$flag.':AC'.$flag);
					$hoja->mergeCells('AD'.$flag.':AH'.$flag);
					$hoja->mergeCells('AI'.$flag.':AM'.$flag);
					$hoja->mergeCells('AN'.$flag.':AR'.$flag);
					$hoja->mergeCells('AS'.$flag.':AW'.$flag);
					$hoja->mergeCells('AX'.$flag.':BB'.$flag);
					$hoja->mergeCells('BC'.$flag.':BG'.$flag);
					$hoja->mergeCells('BH'.$flag.':BL'.$flag);

					$this->cellStyle("B".$flag, "FFFFFF", "000000", TRUE, 12, "Franklin Gothic Book");
					$hoja->setCellValue("B".$flag, "PEDIDOS A '".$value->nombre."' ".date("d-m-Y"));
					$this->cellStyle("M".$flag, "C00000", "000000", TRUE, 12, "Franklin Gothic Book");
					$hoja->setCellValue("M".$flag, "CEDIS");
					$hoja->setCellValue("R".$flag, "CD INDUSTRIAL");
					$this->cellStyle("R".$flag, "FF0066", "000000", TRUE, 12, "Franklin Gothic Book");
					$this->cellStyle("V".$flag, "C2B90A", "000000", TRUE, 12, "Franklin Gothic Book");
					$hoja->setCellValue("V".$flag, "SUMA CEDIS");
					$this->cellStyle("Y".$flag, "01B0F0", "000000", TRUE, 12, "Franklin Gothic Book");
					$hoja->setCellValue("Y".$flag, "ABARROTES");
					$this->cellStyle("AD".$flag, "FF0000", "000000", TRUE, 12, "Franklin Gothic Book");
					$hoja->setCellValue("AD".$flag, "VILLAS");
					$this->cellStyle("AI".$flag, "E26C0B", "000000", TRUE, 12, "Franklin Gothic Book");
					$hoja->setCellValue("AI".$flag, "TIENDA");
					$this->cellStyle("AN".$flag, "C5C5C5", "000000", TRUE, 12, "Franklin Gothic Book");
					$hoja->setCellValue("AN".$flag, "ULTRAMARINOS");
					$this->cellStyle("AS".$flag, "92D051", "000000", TRUE, 12, "Franklin Gothic Book");
					$hoja->setCellValue("AS".$flag, "TRINCHERAS");
					$this->cellStyle("AX".$flag, "B1A0C7", "000000", TRUE, 12, "Franklin Gothic Book");
					$hoja->setCellValue("AX".$flag, "AZT MERCADO");
					$this->cellStyle("BC".$flag, "DA9694", "000000", TRUE, 12, "Franklin Gothic Book");
					$hoja->setCellValue("BC".$flag, "TENENCIA");
					$this->cellStyle("BH".$flag, "4CACC6", "000000", TRUE, 12, "Franklin Gothic Book");
					$hoja->setCellValue("BH".$flag, "TIJERAS");
					
					$this->cellStyle("A3:BM4", "000000", "FFFFFF", TRUE, 12, "Franklin Gothic Book");
					
					$this->excelfile->getActiveSheet()->getStyle('A'.$flag.':BM'.$flag)->applyFromArray($styleArray);
					$flag++;
					$this->cellStyle("A".$flag.":BM".$flag."", "000000", "FFFFFF", TRUE, 12, "Franklin Gothic Book");
					$hoja->mergeCells('A'.$flag.':L'.$flag);
					$hoja->mergeCells('M'.$flag.':Q'.$flag);
					$hoja->mergeCells('R'.$flag.':U'.$flag);
					$hoja->mergeCells('V'.$flag.':X'.$flag);
					$hoja->mergeCells('Y'.$flag.':AC'.$flag);
					$hoja->mergeCells('AD'.$flag.':AH'.$flag);
					$hoja->mergeCells('AI'.$flag.':AM'.$flag);
					$hoja->mergeCells('AN'.$flag.':AR'.$flag);
					$hoja->mergeCells('AS'.$flag.':AW'.$flag);
					$hoja->mergeCells('AX'.$flag.':BB'.$flag);
					$hoja->mergeCells('BC'.$flag.':BG'.$flag);
					$hoja->mergeCells('BH'.$flag.':BL'.$flag);
					$hoja->setCellValue("A".$flag, "DESCRIPCIÓN");
					$hoja->setCellValue("M".$flag, "EXISTENCIAS");
					$hoja->setCellValue("R".$flag, " SUMA EXISTENCIAS");
					$hoja->setCellValue("V".$flag, "EXISTENCIAS");
					$hoja->setCellValue("Y".$flag, "EXISTENCIAS");
					$hoja->setCellValue("AD".$flag, "EXISTENCIAS");
					$hoja->setCellValue("AI".$flag, "EXISTENCIAS");
					$hoja->setCellValue("AN".$flag, "EXISTENCIAS");
					$hoja->setCellValue("AS".$flag, "EXISTENCIAS");
					$hoja->setCellValue("AX".$flag, "EXISTENCIAS");
					$hoja->setCellValue("BC".$flag, "EXISTENCIAS");
					$hoja->setCellValue("BH".$flag, "EXISTENCIAS");
					$this->excelfile->getActiveSheet()->getStyle('A'.$flag.':BM'.$flag)->applyFromArray($styleArray);
					//Begin: TOTALES PEDIDOS PENDIENTES
					$hoja->mergeCells('CD'.$flag.':CN'.$flag);
					$this->cellStyle("CD".$flag.":CN".$flag."", "000000", "FFFFFF", TRUE, 12, "Franklin Gothic Book");
					$hoja->setCellValue("CD".$flag, "TOTAL POR PEDIDOS PENDIENTES");
					//End: TOTALES PEDIDOS PENDIENTES
					$flag++;
					$this->cellStyle("A".$flag.":BM".$flag."", "000000", "FFFFFF", TRUE, 12, "Franklin Gothic Book");
					$hoja->setCellValue("A".$flag, "CODIGO");
					$hoja->setCellValue("C".$flag, "REAL")
					;
					$hoja->setCellValue("D".$flag, "UM");
					$hoja->setCellValue("E".$flag, "1ER");
					$hoja->setCellValue("F".$flag, "COSTO");
					$hoja->setCellValue("G".$flag, "DIF % 5 Y 1ER");
					$hoja->setCellValue("H".$flag, "SISTEMA");
					$hoja->setCellValue("I".$flag, "DIF % 5 Y 4");
					$hoja->setCellValue("J".$flag, "PRECIO4");
					$hoja->setCellValue("K".$flag, "2DO");
					$hoja->setCellValue("L".$flag, "PROVEEDOR");

					$hoja->setCellValue("M".$flag, "CAJAS");
					$hoja->setCellValue("N".$flag, "PZAS");
					$hoja->setCellValue("O".$flag, "PEND");
					$hoja->setCellValue("P".$flag, "STOCK");
					$hoja->setCellValue("Q".$flag, "PEDIDO");
					$hoja->setCellValue("R".$flag, "CAJAS");
					$hoja->setCellValue("S".$flag, "PZAS");
					$hoja->setCellValue("T".$flag, "STOCK");
					$hoja->setCellValue("U".$flag, "PEDIDO");
					$hoja->setCellValue("V".$flag, "CAJAS");
					$hoja->setCellValue("W".$flag, "PZAS");
					$hoja->setCellValue("X".$flag, "PEDIDO");
					$hoja->setCellValue("Y".$flag, "CAJAS");
					$hoja->setCellValue("Z".$flag, "PZAS");
					$hoja->setCellValue("AA".$flag, "PEND");
					$hoja->setCellValue("AB".$flag, "STOCK");
					$hoja->setCellValue("AC".$flag, "PEDIDO");
					$hoja->setCellValue("AD".$flag, "CAJAS");
					$hoja->setCellValue("AE".$flag, "PZAS");
					$hoja->setCellValue("AF".$flag, "PEND");
					$hoja->setCellValue("AG".$flag, "STOCK");
					$hoja->setCellValue("AH".$flag, "PEDIDO");
					$hoja->setCellValue("AI".$flag, "CAJAS");
					$hoja->setCellValue("AJ".$flag, "PZAS");
					$hoja->setCellValue("AK".$flag, "PEND");
					$hoja->setCellValue("AL".$flag, "STOCK");
					$hoja->setCellValue("AM".$flag, "PEDIDO");
					$hoja->setCellValue("AN".$flag, "CAJAS");
					$hoja->setCellValue("AO".$flag, "PZAS");
					$hoja->setCellValue("AP".$flag, "PEND");
					$hoja->setCellValue("AQ".$flag, "STOCK");
					$hoja->setCellValue("AR".$flag, "PEDIDO");
					$hoja->setCellValue("AS".$flag, "CAJAS");
					$hoja->setCellValue("AT".$flag, "PZAS");
					$hoja->setCellValue("AU".$flag, "PEND");
					$hoja->setCellValue("AV".$flag, "STOCK");
					$hoja->setCellValue("AW".$flag, "PEDIDO");
					$hoja->setCellValue("AX".$flag, "CAJAS");
					$hoja->setCellValue("AY".$flag, "PZAS");
					$hoja->setCellValue("AZ".$flag, "PEND");
					$hoja->setCellValue("BA".$flag, "STOCK");
					$hoja->setCellValue("BB".$flag, "PEDIDO");
					$hoja->setCellValue("BC".$flag, "CAJAS");
					$hoja->setCellValue("BD".$flag, "PZAS");
					$hoja->setCellValue("BE".$flag, "PEND");
					$hoja->setCellValue("BF".$flag, "STOCK");
					$hoja->setCellValue("BG".$flag, "PEDIDO");
					$hoja->setCellValue("BH".$flag, "CAJAS");
					$hoja->setCellValue("BI".$flag, "PZAS");
					$hoja->setCellValue("BJ".$flag, "PEND");
					$hoja->setCellValue("BK".$flag, "STOCK");
					$hoja->setCellValue("BL".$flag, "PEDIDO");
					$hoja->setCellValue("BM".$flag, "PROMOCIÓN");					
					
					
					$this->cellStyle("BN".$flag1, "C00000", "000000", TRUE, 10, "Franklin Gothic Book");
					$this->cellStyle("BO".$flag1, "01B0F0", "000000", TRUE, 10, "Franklin Gothic Book");
					$this->cellStyle("BP".$flag1, "FF0000", "000000", TRUE, 10, "Franklin Gothic Book");
					$this->cellStyle("BQ".$flag1, "E26C0B", "000000", TRUE, 10, "Franklin Gothic Book");
					$this->cellStyle("BR".$flag1, "C5C5C5", "000000", TRUE, 10, "Franklin Gothic Book");
					$this->cellStyle("BS".$flag1, "92D051", "000000", TRUE, 10, "Franklin Gothic Book");
					$this->cellStyle("BT".$flag1, "B1A0C7", "000000", TRUE, 10, "Franklin Gothic Book");
					$this->cellStyle("BU".$flag1, "DA9694", "000000", TRUE, 10, "Franklin Gothic Book");
					$this->cellStyle("BV".$flag1, "4CACC6", "000000", TRUE, 10, "Franklin Gothic Book");

					$hoja->setCellValue("BW".$flag, "TOTAL");
					$hoja->setCellValue("BX".$flag, "PEDIDOS");
					$this->cellStyle("BW".$flag."", "000000", "FFFFFF", TRUE, 12, "Franklin Gothic Book");
					$this->cellStyle("BX".$flag."", "000000", "FFFFFF", TRUE, 12, "Franklin Gothic Book");
					$this->excelfile->getActiveSheet()->getStyle('BW'.$flag)->applyFromArray($styleArray);
					$this->excelfile->getActiveSheet()->getStyle('BX'.$flag)->applyFromArray($styleArray);
					$this->excelfile->getActiveSheet()->getStyle('A'.$flag.':BM'.$flag)->applyFromArray($styleArray);

					//Begin: TOTALES PEDIDOS PENDIENTES
					$this->cellStyle("CD".$flag, "C00000", "000000", TRUE, 12, "Franklin Gothic Book");
					$this->cellStyle("CE".$flag, "01B0F0", "000000", TRUE, 12, "Franklin Gothic Book");
					$this->cellStyle("CF".$flag, "FF0000", "000000", TRUE, 12, "Franklin Gothic Book");
					$this->cellStyle("CG".$flag, "E26C0B", "000000", TRUE, 12, "Franklin Gothic Book");
					$this->cellStyle("CH".$flag, "C5C5C5", "000000", TRUE, 12, "Franklin Gothic Book");
					$this->cellStyle("CI".$flag, "92D051", "000000", TRUE, 12, "Franklin Gothic Book");
					$this->cellStyle("CJ".$flag, "B1A0C7", "000000", TRUE, 12, "Franklin Gothic Book");
					$this->cellStyle("CK".$flag, "DA9694", "000000", TRUE, 12, "Franklin Gothic Book");
					$this->cellStyle("CL".$flag, "4CACC6", "000000", TRUE, 12, "Franklin Gothic Book");
					$this->cellStyle("CM".$flag."", "000000", "FFFFFF", TRUE, 12, "Franklin Gothic Book");
					$this->cellStyle("CN".$flag."", "000000", "FFFFFF", TRUE, 12, "Franklin Gothic Book");
					$hoja->setCellValue("CM".$flag, "TOTAL");
					$hoja->setCellValue("CN".$flag, "PEDIDOS");
					//End: TOTALES PEDIDOS PENDIENTES
					foreach ($cotizacionesProveedor as $key => $value){
						//Existencias
						$this->excelfile->setActiveSheetIndex(0);
						$this->cellStyle("E".$flag1, "000000", "FFFFFF", FALSE, 12, "Franklin Gothic Book");
						$hoja1->setCellValue("E".$flag1, $value['familia']);
						$flag1 +=1;
						//Pedidos
						$this->excelfile->setActiveSheetIndex(1);
						$this->cellStyle("B".$flag, "000000", "FFFFFF", FALSE, 12, "Franklin Gothic Book");
						$hoja->setCellValue("B{$flag}", $value['familia']);
						$flag +=1;
						if ($value['articulos']) {
							foreach ($value['articulos'] as $key => $row){
								//Existencias
								$cargo = $row['cargo'];
								$registrazo = date('Y-m-d',strtotime($row['registrazo']));
								$this->excelfile->setActiveSheetIndex(0);
								$this->cellStyle("A".$flag1.":P".$flag1, "FFFFFF", "000000", FALSE, 12, "Franklin Gothic Book");
								
								$hoja1->setCellValue("D{$flag1}", $row['codigo'])->getStyle("D{$flag1}")->getNumberFormat()->setFormatCode('# ???/???');//Formato de fraccion
								if($row['color'] == '#92CEE3'){
									$this->cellStyle("D{$flag1}", "92CEE3", "000000", FALSE, 12, "Franklin Gothic Book");
								}else{
									$this->cellStyle("D{$flag1}", "FFFFFF", "000000", FALSE, 12, "Franklin Gothic Book");
								}
								$hoja1->setCellValue("E{$flag1}", $row['producto']);
								$hoja1->setCellValue("G{$flag1}", $row['promocion_first']);
								$hoja1->setCellValue("H{$flag1}", $row['cedis']);
								$hoja1->setCellValue("I{$flag1}", $row['abarrotes']);
								$hoja1->setCellValue("J{$flag1}", $row['pedregal']);
								$hoja1->setCellValue("K{$flag1}", $row['tienda']);
								$hoja1->setCellValue("L{$flag1}", $row['ultra']);
								$hoja1->setCellValue("M{$flag1}", $row['trincheras']);
								$hoja1->setCellValue("N{$flag1}", $row['mercado']);
								$hoja1->setCellValue("O{$flag1}", $row['tenencia']);
								$hoja1->setCellValue("P{$flag1}", $row['tijeras']);

								if ($row["imagen"] <> "" && !is_null($row["imagen"]) ) {
									$objDrawing = new PHPExcel_Worksheet_Drawing();
									$objDrawing->setName('COD'.$row['producto']);
									$objDrawing->setDescription('DESC'.$row['codigo']);
									$objDrawing->setPath("./Abarrotes/assets/img/productos/".$row["imagen"]."");
									$objDrawing->setWidth(50);
									$objDrawing->setHeight(50);
									$objDrawing->setCoordinates('F'.$flag1);
									$objDrawing->setOffsetX(5); 
									$objDrawing->setOffsetY(5);
									//$objDrawing->setUrl('http://abarrotesazteca.com/Abarrotes/assets/img/productos/'.$row["imagen"]);
									$objDrawing->setWorksheet($this->excelfile->getActiveSheet());
									$this->excelfile->getActiveSheet()->getRowDimension($flag1)->setRowHeight(60);
									$this->excelfile->getActiveSheet()->getStyleByColumnAndRow(10, $flag1)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_DATE_XLSX14);
									$this->excelfile->getActiveSheet()->getCell('F'.$flag1)->getHyperlink()->setUrl('http://abarrotesazteca.com/Abarrotes/assets/img/productos/'.str_replace("_thumb.",".",$row["imagen"]));
								}

								$hoja1->getStyle("A{$flag1}:P{$flag1}")
						                 ->getAlignment()
						                 ->setHorizontal(\PHPExcel_Style_Alignment::HORIZONTAL_LEFT);
						         
				                 
								//Pedidos
								$this->excelfile->setActiveSheetIndex(1);
								$this->cellStyle("A".$flag.":BX".$flag."", "FFFFFF", "000000", FALSE, 12, "Franklin Gothic Book");
								
								$hoja->setCellValue("A{$flag}", $row['codigo'])->getStyle("A{$flag}")->getNumberFormat()->setFormatCode('# ???/???');//Formato de fraccion
								if($row['color'] == '#92CEE3'){
									$this->cellStyle("A{$flag}", "92CEE3", "000000", FALSE, 12, "Franklin Gothic Book");
								}else{
									$this->cellStyle("A{$flag}", "FFFFFF", "000000", FALSE, 12, "Franklin Gothic Book");
								}
								$hoja->setCellValue("B{$flag}", $row['producto']);
								$hoja->setCellValue("F{$flag}", $row['proveedor_first']);
								$hoja->setCellValue("C{$flag}", $row['reales'])->getStyle("C{$flag}")->getNumberFormat()->setFormatCode('"$"#,##0.00_-');

								if(number_format(($row['precio_first'] - $row['reales']),2) === "0.01" || number_format(($row['precio_first'] - $row['reales']),2) === "-0.01"){
									$this->cellStyle("C{$flag}", "FFFFFF", "000000", FALSE, 12, "Franklin Gothic Book");
								}elseif($row['precio_first'] < $row['reales']){
									$this->cellStyle("C{$flag}", "FFFFFF", "E21600", FALSE, 12, "Franklin Gothic Book");
								}else{
									$this->cellStyle("C{$flag}", "FFFFFF", "249947", FALSE, 12, "Franklin Gothic Book");
								}

								if($row['precio_sistema'] < $row['precio_first']){
									$hoja->setCellValue("E{$flag}", $row['precio_first'])->getStyle("E{$flag}")->getNumberFormat()->setFormatCode('"$"#,##0.00_-');
									$this->cellStyle("E{$flag}", "FDB2B2", "E21111", FALSE, 12, "Franklin Gothic Book");
									$this->cellStyle("B{$flag}", "E21600", "000000", FALSE, 12, "Franklin Gothic Book");
								}else{
									$hoja->setCellValue("E{$flag}", $row['precio_first'])->getStyle("E{$flag}")->getNumberFormat()->setFormatCode('"$"#,##0.00_-');
									$this->cellStyle("E{$flag}", "96EAA8", "0C800C", FALSE, 12, "Franklin Gothic Book");
									$this->cellStyle("B{$flag}", "249947", "000000", FALSE, 12, "Franklin Gothic Book");
								}
								$hoja->setCellValue("H{$flag}", $row['precio_sistema'])->getStyle("H{$flag}")->getNumberFormat()->setFormatCode('"$"#,##0.00_-');//Formto de moneda
								$this->cellStyle("H".$flag, "FFFFFF","000000",  FALSE, 12, "Franklin Gothic Book");
								if($row['colorp'] == 1){
									$this->cellStyle("H{$flag}", "D6DCE4", "000000", FALSE, 12, "Franklin Gothic Book");
								}else{
									$this->cellStyle("H{$flag}", "FFFFFF", "000000", FALSE, 12, "Franklin Gothic Book");
								}
								$hoja->setCellValue("J{$flag}", $row['precio_four'])->getStyle("J{$flag}")->getNumberFormat()->setFormatCode('"$"#,##0.00_-');
								$this->cellStyle("J{$flag}", "FFFFFF", "000000", FALSE, 12, "Franklin Gothic Book");
								if($row['precio_sistema'] < $row['precio_next']){
									$hoja->setCellValue("K{$flag}", $row['precio_next'])->getStyle("K{$flag}")->getNumberFormat()->setFormatCode('"$"#,##0.00_-');
									$this->cellStyle("K{$flag}", "FDB2B2", "E21111", FALSE, 12, "Franklin Gothic Book");
								}else if($row['precio_next'] !== NULL){
									$hoja->setCellValue("K{$flag}", $row['precio_next'])->getStyle("K{$flag}")->getNumberFormat()->setFormatCode('"$"#,##0.00_-');
									$this->cellStyle("K{$flag}", "96EAA8", "0C800C", FALSE, 12, "Franklin Gothic Book");
								}else{
									$hoja->setCellValue("K{$flag}", $row['precio_next'])->getStyle("K{$flag}")->getNumberFormat()->setFormatCode('"$"#,##0.00_-');
									$this->cellStyle("K{$flag}", "FFFFFF", "000000", FALSE, 12, "Franklin Gothic Book");
								}
								$this->cellStyle("M".$flag.":BB".$flag, "FFFFFF", "000000", TRUE, 12, "Franklin Gothic Book");
								$hoja->setCellValue("L{$flag}", $row['proveedor_next']);
								$this->cellStyle("L".$flag, "FFFFFF", "000000", FALSE, 12, "Franklin Gothic Book");

								
								
								$hoja->setCellValue("O{$flag}", $row['cedis']);
								$hoja->setCellValue("AA{$flag}", $row['abarrotes']);
								$hoja->setCellValue("AF{$flag}", $row['pedregal']);
								$hoja->setCellValue("AK{$flag}", $row['tienda']);
								$hoja->setCellValue("AP{$flag}", $row['ultra']);
								$hoja->setCellValue("AU{$flag}", $row['trincheras']);
								$hoja->setCellValue("AZ{$flag}", $row['mercado']);
								$hoja->setCellValue("BE{$flag}", $row['tenencia']);
								$hoja->setCellValue("BJ{$flag}", $row['tijeras']);

								if ($row["lastfecha"] === NULL || $row["lastfecha"] === "") {
									$row["lastfecha"] = "NO PEDIDOS";
								}
								if ($row["unidad"] === NULL || $row["unidad"] === "") {
									$row["unidad"] = 1;
								}
								
								$antis = ((floatval($row[87]) * floatval($row["unidad"])) + ($row["unidad"] * $row["past"]["caja0"]) + $row["past"]["pz0"])/$row["unidad"];
								$hoja->setCellValue("M{$flag}", $row['caja0']);
								$hoja->setCellValue("N{$flag}", $row['pz0']);
								$hoja->setCellValue("P{$flag}", $row["past"]["caja0"]);
								$hoja->setCellValue("Q{$flag}", $row['ped0']);
								$this->cellStyle("Q{$flag}", "D4EAEF", "000000", TRUE, 12, "Franklin Gothic Book");

								$hoja->setCellValue("R{$flag}", $row['caja9']);
								$hoja->setCellValue("S{$flag}", $row['pz9']);
								$hoja->setCellValue("T{$flag}", $row["past"]["caja1"]);
								$hoja->setCellValue("U{$flag}", $row['ped9']);
								$this->cellStyle("U{$flag}", "D4EAEF", "000000", TRUE, 12, "Franklin Gothic Book");

							
								$hoja->setCellValue("V{$flag}", "=M".$flag."+R".$flag);
								$hoja->setCellValue("W{$flag}", "=N".$flag."+S".$flag);
								$hoja->setCellValue("X{$flag}", "=Q".$flag."+U".$flag);
								$this->cellStyle("X{$flag}", "D4EAEF", "000000", TRUE, 12, "Franklin Gothic Book");

								$hoja->setCellValue("Y{$flag}", $row['caja1']);
								$hoja->setCellValue("Z{$flag}", $row['pz1']);
								$hoja->setCellValue("AB{$flag}", $row["past"]["caja1"]);
								$hoja->setCellValue("AC{$flag}", $row['ped1']);
								$this->cellStyle("AC{$flag}", "D4EAEF", "000000", TRUE, 12, "Franklin Gothic Book");

								$hoja->setCellValue("AD{$flag}", $row['caja2']);
								$hoja->setCellValue("AE{$flag}", $row['pz2']);
								$hoja->setCellValue("AG{$flag}", $row["past"]["caja2"]);
								$hoja->setCellValue("AH{$flag}", $row['ped2']);
								$this->cellStyle("AH{$flag}", "D4EAEF", "000000", TRUE, 12, "Franklin Gothic Book");

								$hoja->setCellValue("AI{$flag}", $row['caja3']);
								$hoja->setCellValue("AJ{$flag}", $row['pz3']);
								$hoja->setCellValue("AL{$flag}", $row["past"]["caja3"]);
								$hoja->setCellValue("AM{$flag}", $row['ped3']);
								$this->cellStyle("AM{$flag}", "D4EAEF", "000000", TRUE, 12, "Franklin Gothic Book");

								$hoja->setCellValue("AN{$flag}", $row['caja4']);
								$hoja->setCellValue("AO{$flag}", $row['pz4']);
								$hoja->setCellValue("AQ{$flag}", $row["past"]["caja4"]);
								$hoja->setCellValue("AR{$flag}", $row['ped4']);
								$this->cellStyle("AR{$flag}", "D4EAEF", "000000", TRUE, 12, "Franklin Gothic Book");

								$hoja->setCellValue("AS{$flag}", $row['caja5']);
								$hoja->setCellValue("AT{$flag}", $row['pz5']);
								$hoja->setCellValue("AV{$flag}", $row["past"]["caja5"]);
								$hoja->setCellValue("AW{$flag}", $row['ped5']);
								$this->cellStyle("AW{$flag}", "D4EAEF", "000000", TRUE, 12, "Franklin Gothic Book");

								$hoja->setCellValue("AX{$flag}", $row['caja6']);
								$hoja->setCellValue("AY{$flag}", $row['pz6']);
								$hoja->setCellValue("BA{$flag}", $row["past"]["caja6"]);
								$hoja->setCellValue("BB{$flag}", $row['ped6']);
								$this->cellStyle("BB{$flag}", "D4EAEF", "000000", TRUE, 12, "Franklin Gothic Book");

								$hoja->setCellValue("BC{$flag}", $row['caja7']);
								$hoja->setCellValue("BD{$flag}", $row['pz7']);
								$hoja->setCellValue("BF{$flag}", $row["past"]["caja7"]);
								$hoja->setCellValue("BG{$flag}", $row['ped7']);
								$this->cellStyle("BG{$flag}", "D4EAEF", "000000", TRUE, 12, "Franklin Gothic Book");

								$hoja->setCellValue("BH{$flag}", $row['caja8']);
								$hoja->setCellValue("BI{$flag}", $row['pz8']);
								$hoja->setCellValue("BK{$flag}", $row["past"]["caja8"]);
								$hoja->setCellValue("BL{$flag}", $row['ped8']);
								$this->cellStyle("BL{$flag}", "D4EAEF", "000000", TRUE, 12, "Franklin Gothic Book");

								

								$this->cellStyle("BM{$flag}", "FFFFFF", "000000", TRUE, 12, "Franklin Gothic Book");
								$hoja->setCellValue("BM{$flag}", $row['promocion_first']);
								$hoja->setCellValue("BN{$flag}", "=E".$flag."*Q".$flag)->getStyle("BN{$flag}")->getNumberFormat()->setFormatCode('"$"#,##0.00_-');
								$hoja->setCellValue("BO{$flag}", "=E".$flag."*AC".$flag)->getStyle("BO{$flag}")->getNumberFormat()->setFormatCode('"$"#,##0.00_-');
								$hoja->setCellValue("BP{$flag}", "=E".$flag."*AH".$flag)->getStyle("BP{$flag}")->getNumberFormat()->setFormatCode('"$"#,##0.00_-');
								$hoja->setCellValue("BQ{$flag}", "=E".$flag."*AM".$flag)->getStyle("BQ{$flag}")->getNumberFormat()->setFormatCode('"$"#,##0.00_-');
								$hoja->setCellValue("BR{$flag}", "=E".$flag."*AR".$flag)->getStyle("BR{$flag}")->getNumberFormat()->setFormatCode('"$"#,##0.00_-');
								$hoja->setCellValue("BS{$flag}", "=E".$flag."*AW".$flag)->getStyle("BS{$flag}")->getNumberFormat()->setFormatCode('"$"#,##0.00_-');
								$hoja->setCellValue("BT{$flag}", "=E".$flag."*BB".$flag)->getStyle("BT{$flag}")->getNumberFormat()->setFormatCode('"$"#,##0.00_-');
								$hoja->setCellValue("BU{$flag}", "=E".$flag."*BG".$flag)->getStyle("BU{$flag}")->getNumberFormat()->setFormatCode('"$"#,##0.00_-');
								$hoja->setCellValue("BV{$flag}", "=E".$flag."*BL".$flag)->getStyle("BV{$flag}")->getNumberFormat()->setFormatCode('"$"#,##0.00_-');
								$this->cellStyle("BW{$flag}", "D4EAEF", "000000", FALSE, 12, "Franklin Gothic Book");
								$hoja->setCellValue("BW{$flag}", "=SUM(BN".$flag.":BV".$flag.")")->getStyle("BW{$flag}")->getNumberFormat()->setFormatCode('"$"#,##0.00_-');
								$this->cellStyle("BX{$flag}", "C2B90A", "000000", FALSE, 12, "Franklin Gothic Book");
								$hoja->setCellValue("BX{$flag}", "=Q".$flag."+AC".$flag."+AH".$flag."+AM".$flag."+AR".$flag."+AW".$flag."+BB".$flag."+BG".$flag."+BL".$flag."");

								//Begin: TOTALES PEDIDOS PENDIENTES
								$hoja->setCellValue("CD{$flag}", "=E".$flag."*O".$flag)->getStyle("CD{$flag}")->getNumberFormat()->setFormatCode('"$"#,##0.00_-');
								$hoja->setCellValue("CE{$flag}", "=E".$flag."*AA".$flag)->getStyle("CE{$flag}")->getNumberFormat()->setFormatCode('"$"#,##0.00_-');
								$hoja->setCellValue("CF{$flag}", "=E".$flag."*AF".$flag)->getStyle("CF{$flag}")->getNumberFormat()->setFormatCode('"$"#,##0.00_-');
								$hoja->setCellValue("CG{$flag}", "=E".$flag."*AK".$flag)->getStyle("CG{$flag}")->getNumberFormat()->setFormatCode('"$"#,##0.00_-');
								$hoja->setCellValue("CH{$flag}", "=E".$flag."*AP".$flag)->getStyle("CH{$flag}")->getNumberFormat()->setFormatCode('"$"#,##0.00_-');
								$hoja->setCellValue("CI{$flag}", "=E".$flag."*AU".$flag)->getStyle("CI{$flag}")->getNumberFormat()->setFormatCode('"$"#,##0.00_-');
								$hoja->setCellValue("CJ{$flag}", "=E".$flag."*AZ".$flag)->getStyle("CJ{$flag}")->getNumberFormat()->setFormatCode('"$"#,##0.00_-');
								$hoja->setCellValue("CK{$flag}", "=E".$flag."*BE".$flag)->getStyle("CK{$flag}")->getNumberFormat()->setFormatCode('"$"#,##0.00_-');
								$hoja->setCellValue("CL{$flag}", "=E".$flag."*BJ".$flag)->getStyle("CL{$flag}")->getNumberFormat()->setFormatCode('"$"#,##0.00_-');
								$this->cellStyle("CM{$flag}", "000000", "FFFFFF", FALSE, 12, "Franklin Gothic Book");
								$hoja->setCellValue("CM{$flag}", "=SUM(CD".$flag.":CL".$flag.")")->getStyle("CM{$flag}")->getNumberFormat()->setFormatCode('"$"#,##0.00_-');
								$this->cellStyle("CN{$flag}", "000000", "FFFFFF", FALSE, 12, "Franklin Gothic Book");
								$hoja->setCellValue("CN{$flag}", "=O".$flag."+AA".$flag."+AF".$flag."+AK".$flag."+AP".$flag."+AU".$flag."+AZ".$flag."+BE".$flag."+BJ".$flag."");
								//End: TOTALES PEDIDOS PENDIENTES
								$border_style= array('borders' => array('right' => array('style' =>
									PHPExcel_Style_Border::BORDER_THIN,'color' => array('argb' => '000000'),)));
								$this->excelfile->setActiveSheetIndex(1);
								
								$this->excelfile->getActiveSheet()->getStyle('A'.$flag.':BX'.$flag)->applyFromArray($styleArray);
								$this->excelfile->getActiveSheet()->getStyle('BW'.$flag.':BX'.$flag)->applyFromArray($styleArray);
								if($row['precio_sistema'] == 0){
									$row['precio_sistema'] = 1;
								}
								if($row['precio_four'] == 0){
									$row['precio_four'] = 1;
								}
									$hoja->setCellValue("G{$flag}",100 - ($row['precio_first'] * 100 / $row['precio_sistema']))->getStyle("G{$flag}")->getNumberFormat()->setFormatCode('"%"#,##0.00_-');;
									$this->cellStyle("G".$flag, "FF9999", "000000", FALSE, 10, "Franklin Gothic Book");

									$hoja->setCellValue("I{$flag}", 100 - ($row['precio_sistema'] * 100 / $row['precio_four']))->getStyle("I{$flag}")->getNumberFormat()->setFormatCode('"%"#,##0.00_-');
									$this->cellStyle("I".$flag, "FF9999", "000000", FALSE, 10, "Franklin Gothic Book");
								
								$this->excelfile->setActiveSheetIndex(0);
								$this->excelfile->getActiveSheet()->getStyle('A'.$flag1.':P'.$flag1)->applyFromArray($styleArray);
								$hoja->getStyle("A{$flag}:I{$flag}")
						                 ->getAlignment()
						                 ->setHorizontal(\PHPExcel_Style_Alignment::HORIZONTAL_LEFT);

						   
								$flag ++;
								$flag1 ++;
							}
						}
					}
					$flagf = $flag;
					$flagfs = $flag - 1;
					$hoja->setCellValue("BN{$flagf}", "=SUM(BN5:BN".$flagfs.")")->getStyle("BN{$flag}")->getNumberFormat()->setFormatCode('"$"#,##0.00_-');
					$hoja->setCellValue("BO{$flagf}", "=SUM(BO5:BO".$flagfs.")")->getStyle("BO{$flag}")->getNumberFormat()->setFormatCode('"$"#,##0.00_-');
					$hoja->setCellValue("BP{$flagf}", "=SUM(BP5:BP".$flagfs.")")->getStyle("BP{$flag}")->getNumberFormat()->setFormatCode('"$"#,##0.00_-');
					$hoja->setCellValue("BQ{$flagf}", "=SUM(BQ5:BQ".$flagfs.")")->getStyle("BQ{$flag}")->getNumberFormat()->setFormatCode('"$"#,##0.00_-');
					$hoja->setCellValue("BR{$flagf}", "=SUM(BR5:BR".$flagfs.")")->getStyle("BR{$flag}")->getNumberFormat()->setFormatCode('"$"#,##0.00_-');
					$hoja->setCellValue("BS{$flagf}", "=SUM(BS5:BS".$flagfs.")")->getStyle("BS{$flag}")->getNumberFormat()->setFormatCode('"$"#,##0.00_-');
					$hoja->setCellValue("BT{$flagf}", "=SUM(BT5:BT".$flagfs.")")->getStyle("BT{$flag}")->getNumberFormat()->setFormatCode('"$"#,##0.00_-');
					$hoja->setCellValue("BU{$flagf}", "=SUM(BU5:BU".$flagfs.")")->getStyle("BU{$flag}")->getNumberFormat()->setFormatCode('"$"#,##0.00_-');
					$hoja->setCellValue("BV{$flagf}", "=SUM(BV5:BV".$flagfs.")")->getStyle("BV{$flag}")->getNumberFormat()->setFormatCode('"$"#,##0.00_-');
					$this->cellStyle("BW{$flagf}", "000000", "FFFFFF", FALSE, 12, "Franklin Gothic Book");
					$hoja->setCellValue("BW{$flagf}", "=SUM(BW5:BW".$flagfs.")")->getStyle("BW{$flagf}")->getNumberFormat()->setFormatCode('"$"#,##0.00_-');
					$sumall[1] .= "BN".$flagf."+";
					$sumall[2] .= "BO".$flagf."+";
					$sumall[3] .= "BP".$flagf."+";
					$sumall[4] .= "BQ".$flagf."+";
					$sumall[5] .= "BR".$flagf."+";
					$sumall[6] .= "BS".$flagf."+";
					$sumall[7] .= "BT".$flagf."+";
					$sumall[8] .= "BU".$flagf."+";
					$sumall[9] .= "BV".$flagf."+";
					$sumall[10] .= "BW".$flagf."+";
					//Begin: TOTALES PEDIDOS PENDIENTES
					$hoja->setCellValue("CD{$flag}", "=SUM(CD".$flage.":CD".$flagfs.")")->getStyle("CD{$flag}")->getNumberFormat()->setFormatCode('"$"#,##0.00_-');
					$hoja->setCellValue("CE{$flag}", "=SUM(CE".$flage.":CE".$flagfs.")")->getStyle("CE{$flag}")->getNumberFormat()->setFormatCode('"$"#,##0.00_-');
					$hoja->setCellValue("CF{$flag}", "=SUM(CF".$flage.":CF".$flagfs.")")->getStyle("CF{$flag}")->getNumberFormat()->setFormatCode('"$"#,##0.00_-');
					$hoja->setCellValue("CG{$flag}", "=SUM(CG".$flage.":CG".$flagfs.")")->getStyle("CG{$flag}")->getNumberFormat()->setFormatCode('"$"#,##0.00_-');
					$hoja->setCellValue("CH{$flag}", "=SUM(CH".$flage.":CH".$flagfs.")")->getStyle("CH{$flag}")->getNumberFormat()->setFormatCode('"$"#,##0.00_-');
					$hoja->setCellValue("CI{$flag}", "=SUM(CI".$flage.":CI".$flagfs.")")->getStyle("CI{$flag}")->getNumberFormat()->setFormatCode('"$"#,##0.00_-');
					$hoja->setCellValue("CJ{$flag}", "=SUM(CJ".$flage.":CJ".$flagfs.")")->getStyle("CJ{$flag}")->getNumberFormat()->setFormatCode('"$"#,##0.00_-');
					$hoja->setCellValue("CK{$flag}", "=SUM(CK".$flage.":CK".$flagfs.")")->getStyle("CK{$flag}")->getNumberFormat()->setFormatCode('"$"#,##0.00_-');
					$hoja->setCellValue("CL{$flag}", "=SUM(CL".$flage.":CL".$flagfs.")")->getStyle("CL{$flag}")->getNumberFormat()->setFormatCode('"$"#,##0.00_-');
					$hoja->setCellValue("CM{$flag}", "=SUM(CM".$flage.":CM".$flagfs.")")->getStyle("CM{$flag}")->getNumberFormat()->setFormatCode('"$"#,##0.00_-');
					//End: TOTALES PEDIDOS PENDIENTES
					
					$flag++;
					$flag1++;
					$flag++;
					$flag1++;
					$flag1++;
					$flag1++;
					$flag++;
					$flag++;
				}
			}
		}
		$flag++;
		$bandera = $flag;
		
		$hoja->mergeCells('G'.$flag.':J'.$flag);
		$this->cellStyle("G".$flag, "000000", "FFFFFF", TRUE, 12, "Franklin Gothic Book");
		$hoja->setCellValue("G".$flag, "TOTALES PENDIENTES '".$provname."'");
		$flag++;
		$hoja->mergeCells('G'.$flag.':H'.$flag);
		$hoja->mergeCells('I'.$flag.':J'.$flag);
		$this->cellStyle("G".$flag, "000000", "FFFFFF", TRUE, 12, "Franklin Gothic Book");
		$hoja->setCellValue("G".$flag, "CEDIS");
		$hoja->setCellValue("I{$flag}", "=CD{$flagf}")->getStyle("I{$flag}")->getNumberFormat()->setFormatCode('"$"#,##0.00_-');
		$flag++;
		$hoja->mergeCells('G'.$flag.':H'.$flag);
		$hoja->mergeCells('I'.$flag.':J'.$flag);
		$this->cellStyle("G".$flag, "000000", "FFFFFF", TRUE, 12, "Franklin Gothic Book");
		$hoja->setCellValue("G".$flag, "ABARROTES");
		$hoja->setCellValue("I{$flag}", "=CE{$flagf}")->getStyle("I{$flag}")->getNumberFormat()->setFormatCode('"$"#,##0.00_-');
		$flag++;
		$hoja->mergeCells('G'.$flag.':H'.$flag);
		$hoja->mergeCells('I'.$flag.':J'.$flag);
		$this->cellStyle("G".$flag, "000000", "FFFFFF", TRUE, 12, "Franklin Gothic Book");
		$hoja->setCellValue("G".$flag, "VILLAS");
		$hoja->setCellValue("I{$flag}", "=CF{$flagf}")->getStyle("I{$flag}")->getNumberFormat()->setFormatCode('"$"#,##0.00_-');
		$flag++;
		$hoja->mergeCells('G'.$flag.':H'.$flag);
		$hoja->mergeCells('I'.$flag.':J'.$flag);
		$this->cellStyle("G".$flag, "000000", "FFFFFF", TRUE, 12, "Franklin Gothic Book");
		$hoja->setCellValue("G".$flag, "TIENDA");
		$hoja->setCellValue("I{$flag}", "=CG{$flagf}")->getStyle("I{$flag}")->getNumberFormat()->setFormatCode('"$"#,##0.00_-');
		$flag++;
		$hoja->mergeCells('G'.$flag.':H'.$flag);
		$hoja->mergeCells('I'.$flag.':J'.$flag);
		$this->cellStyle("G".$flag, "000000", "FFFFFF", TRUE, 12, "Franklin Gothic Book");
		$hoja->setCellValue("G".$flag, "ULTRAMARINOS");
		$hoja->setCellValue("I{$flag}", "=CH{$flagf}")->getStyle("I{$flag}")->getNumberFormat()->setFormatCode('"$"#,##0.00_-');
		$flag++;
		$hoja->mergeCells('G'.$flag.':H'.$flag);
		$hoja->mergeCells('I'.$flag.':J'.$flag);
		$this->cellStyle("G".$flag, "000000", "FFFFFF", TRUE, 12, "Franklin Gothic Book");
		$hoja->setCellValue("G".$flag, "TRINCHERAS");
		$hoja->setCellValue("I{$flag}", "=CI{$flagf}")->getStyle("I{$flag}")->getNumberFormat()->setFormatCode('"$"#,##0.00_-');
		$flag++;
		$hoja->mergeCells('G'.$flag.':H'.$flag);
		$hoja->mergeCells('I'.$flag.':J'.$flag);
		$this->cellStyle("G".$flag, "000000", "FFFFFF", TRUE, 12, "Franklin Gothic Book");
		$hoja->setCellValue("G".$flag, "AZT MERCADO");
		$hoja->setCellValue("I{$flag}", "=CJ{$flagf}")->getStyle("I{$flag}")->getNumberFormat()->setFormatCode('"$"#,##0.00_-');
		$flag++;
		$hoja->mergeCells('G'.$flag.':H'.$flag);
		$hoja->mergeCells('I'.$flag.':J'.$flag);
		$this->cellStyle("G".$flag, "000000", "FFFFFF", TRUE, 12, "Franklin Gothic Book");
		$hoja->setCellValue("G".$flag, "TENENCIA");
		$hoja->setCellValue("I{$flag}", "=CK{$flagf}")->getStyle("I{$flag}")->getNumberFormat()->setFormatCode('"$"#,##0.00_-');
		$flag++;
		$hoja->mergeCells('G'.$flag.':H'.$flag);
		$hoja->mergeCells('I'.$flag.':J'.$flag);
		$this->cellStyle("G".$flag, "000000", "FFFFFF", TRUE, 12, "Franklin Gothic Book");
		$hoja->setCellValue("G".$flag, "TIJERAS");
		$hoja->setCellValue("I{$flag}", "=CL{$flagf}")->getStyle("I{$flag}")->getNumberFormat()->setFormatCode('"$"#,##0.00_-');
		$flag++;
		$hoja->mergeCells('G'.$flag.':H'.$flag);
		$hoja->mergeCells('I'.$flag.':J'.$flag);
		$this->cellStyle("G".$flag, "000000", "FFFFFF", TRUE, 12, "Franklin Gothic Book");
		$hoja->setCellValue("G".$flag, "TOTAL");
		$hoja->setCellValue("I{$flag}", "=CM{$flagf}")->getStyle("I{$flag}")->getNumberFormat()->setFormatCode('"$"#,##0.00_-');
		$flag = $bandera;

		$hoja->mergeCells('B'.$flag.':C'.$flag);
		$this->cellStyle("B".$flag, "000000", "FFFFFF", TRUE, 12, "Franklin Gothic Book");
		$hoja->setCellValue("B".$flag, "TOTALES EN GENERAL");
		$flag++;
		$this->cellStyle("B".$flag, "C00000", "000000", TRUE, 12, "Franklin Gothic Book");
		$hoja->setCellValue("B".$flag, "CEDIS");
		$hoja->setCellValue("C{$flag}", "=(".substr($sumall[1],0,-1).")")->getStyle("C{$flag}")->getNumberFormat()->setFormatCode('"$"#,##0.00_-');
		$flag++;
		$this->cellStyle("B".$flag, "01B0F0", "000000", TRUE, 12, "Franklin Gothic Book");
		$hoja->setCellValue("B".$flag, "ABARROTES");
		$hoja->setCellValue("C{$flag}", "=(".substr($sumall[2],0,-1).")")->getStyle("C{$flag}")->getNumberFormat()->setFormatCode('"$"#,##0.00_-');
		$flag++;
		$this->cellStyle("B".$flag, "FF0000", "000000", TRUE, 12, "Franklin Gothic Book");
		$hoja->setCellValue("B".$flag, "VILLAS");
		$hoja->setCellValue("C{$flag}", "=(".substr($sumall[3],0,-1).")")->getStyle("C{$flag}")->getNumberFormat()->setFormatCode('"$"#,##0.00_-');
		$flag++;
		$this->cellStyle("B".$flag, "E26C0B", "000000", TRUE, 12, "Franklin Gothic Book");
		$hoja->setCellValue("B".$flag, "TIENDA");
		$hoja->setCellValue("C{$flag}", "=(".substr($sumall[4],0,-1).")")->getStyle("C{$flag}")->getNumberFormat()->setFormatCode('"$"#,##0.00_-');
		$flag++;
		$this->cellStyle("B".$flag, "C5C5C5", "000000", TRUE, 12, "Franklin Gothic Book");
		$hoja->setCellValue("B".$flag, "ULTRAMARINOS");
		$hoja->setCellValue("C{$flag}", "=(".substr($sumall[5],0,-1).")")->getStyle("C{$flag}")->getNumberFormat()->setFormatCode('"$"#,##0.00_-');
		$flag++;
		$this->cellStyle("B".$flag, "92D051", "000000", TRUE, 12, "Franklin Gothic Book");
		$hoja->setCellValue("B".$flag, "TRINCHERAS");
		$hoja->setCellValue("C{$flag}", "=(".substr($sumall[6],0,-1).")")->getStyle("C{$flag}")->getNumberFormat()->setFormatCode('"$"#,##0.00_-');
		$flag++;
		$this->cellStyle("B".$flag, "B1A0C7", "000000", TRUE, 12, "Franklin Gothic Book");
		$hoja->setCellValue("B".$flag, "AZT MERCADO");
		$hoja->setCellValue("C{$flag}", "=(".substr($sumall[7],0,-1).")")->getStyle("C{$flag}")->getNumberFormat()->setFormatCode('"$"#,##0.00_-');
		$flag++;
		$this->cellStyle("B".$flag, "DA9694", "000000", TRUE, 12, "Franklin Gothic Book");
		$hoja->setCellValue("B".$flag, "TENENCIA");
		$hoja->setCellValue("C{$flag}", "=(".substr($sumall[8],0,-1).")")->getStyle("C{$flag}")->getNumberFormat()->setFormatCode('"$"#,##0.00_-');
		$flag++;
		$this->cellStyle("B".$flag, "4CACC6", "000000", TRUE, 12, "Franklin Gothic Book");
		$hoja->setCellValue("B".$flag, "TIJERAS");
		$hoja->setCellValue("C{$flag}", "=(".substr($sumall[9],0,-1).")")->getStyle("C{$flag}")->getNumberFormat()->setFormatCode('"$"#,##0.00_-');
		$flag++;
		$this->cellStyle("B".$flag, "000000", "FFFFFF", TRUE, 12, "Franklin Gothic Book");
		$hoja->setCellValue("B".$flag, "TOTAL");
		$hoja->setCellValue("C{$flag}", "=(".substr($sumall[10],0,-1).")")->getStyle("C{$flag}")->getNumberFormat()->setFormatCode('"$"#,##0.00_-');
		$flag++;

		$dias = array("DOMINGO","LUNES","MARTES","MIÉRCOLES","JUEVES","VIERNES","SÁBADO");
		$meses = array("ENERO","FEBRERO","MARZO","ABRIL","MAYO","JUNIO","JULIO","AGOSTO","SEPTIEMBRE","OCTUBRE","NOVIEMBRE","DICIEMBRE");
		$fecha =  $dias[date('w')]." ".date('d')." DE ".$meses[date('n')-1]. " DEL ".date('Y') ;
		$file_name = "FORMATO MEXICANO ".$fecha.".xlsx"; //Nombre del documento con extención
		$excel_Writer = PHPExcel_IOFactory::createWriter($this->excelfile, "Excel2007");
		header("Content-Type: application/vnd.ms-excel; charset=utf-8");
		header("Content-Disposition: attachment;filename=".$file_name);
		header("Cache-Control: max-age=0");
		$excel_Writer->save("php://output");
		/*$excel_Writer = new PHPExcel_Writer_Excel2007($this->excelfile);
		$excel_Writer->setOffice2003Compatibility(true);
		$excel_Writer->save("php://output");*/
	}


}
/* End of file Cotizaciones.php */
/* Location: ./application/controllers/Cotizaciones.php */
