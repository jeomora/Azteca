<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Cotizaciones extends MY_Controller {

	function __construct(){
		parent::__construct();
		$this->load->model("Usuarios_model", "usua_mdl");
		$this->load->model("Cotizaciones_model", "ct_mdl");
		$this->load->model("Cambios_model", "cambio_md");
		$this->load->model("Faltantes_model", "falt_mdl");
		$this->load->library("form_validation");
	}

	public function getLastCot($id_proveedor){
		$cotizacion = $this->ct_mdl->getLastCot(NULL,$id_proveedor);
		$this->jsonResponse($cotizacion);
	}

	public function anteriores(){
		ini_set("memory_limit", "-1");
		$data['scripts'] = [
			'/scripts/Cotizaciones/anteriores',
		];
		$data["dias"] = array("DOMINGO","LUNES","MARTES","MIÉRCOLES","JUEVES","VIERNES","SÁBADO");
		$data["meses"] = array("ENERO","FEBRERO","MARZO","ABRIL","MAYO","JUNIO","JULIO","AGOSTO","SEPTIEMBRE","OCTUBRE","NOVIEMBRE","DICIEMBRE");
		$data["cotizados"] = $this->usua_mdl->getCotizados();
		$data["usuar"]  = $this->session->userdata();
		$this->estructura("Cotizaciones/anteriores", $data);
		//$this->jsonResponse($data["cotizados"]);
	}

	public function repeat_cotizacion($idprovd){
		$fecha = new DateTime(date('Y-m-d H:i:s'));
		$intervalo = new DateInterval('P2D');
		$fecha->add($intervalo);
		$user = $this->session->userdata();

		$cotizaciones =  $this->ct_mdl->getLastCot(NULL,$idprovd);
		$i = 0;
		$new_cotizacion = null;
		if ($cotizaciones){
			foreach ($cotizaciones as $key => $value){
				$antes =  $this->falt_mdl->get(NULL, ['id_producto' => $value->id_producto, 'fecha_termino > ' => date("Y-m-d H:i:s"), 'id_proveedor' => $idprovd])[0];
				$num_one = $value->num_one == '' ? 0 : $value->num_one;
				$num_two = $value->num_two == '' ? 0 : $value->num_two;
				$descuento = $value->descuento == '' ? 0 : $value->descuento;
				if($antes){
					$new_cotizacion[$i] = [
						"id_proveedor"		=>	$idprovd,
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
						"id_proveedor"		=>	$idprovd,
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
			$data['cotizacion']=$this->ct_mdl->insert_batch($new_cotizacion);
			$aprov = $this->usua_mdl->get(NULL, ['id_usuario'=>$idprovd])[0];
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
}