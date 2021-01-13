<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Cotizaciones extends MY_Controller {

	function __construct(){
		parent::__construct();
		$this->load->model("Usuarios_model", "usua_mdl");
		$this->load->model("Cotizaciones_model", "ct_mdl");
		$this->load->model("Cambios_model", "cambio_md");
		$this->load->model("Faltantes_model", "falt_mdl");
		$this->load->model("Familias_model", "fam_mdl");
		$this->load->model("Productos_model", "pro_mdl");
		$this->load->model("Pendientes_model", "pend_mdl");
		$this->load->model("Precio_sistema_model", "pre_mdl");
		$this->load->library("form_validation");
	}

	public function index(){
		$data['scripts'] = [
			'/scripts/Cotizaciones/index',
		];
		$data["dias"] = array("DOMINGO","LUNES","MARTES","MIÉRCOLES","JUEVES","VIERNES","SÁBADO");
		$data["meses"] = array("ENERO","FEBRERO","MARZO","ABRIL","MAYO","JUNIO","JULIO","AGOSTO","SEPTIEMBRE","OCTUBRE","NOVIEMBRE","DICIEMBRE");
		$data["cotizados"] = $this->usua_mdl->getCotizados();
		$data["usuar"]  = $this->session->userdata();
		$data["productos"] = $this->pro_mdl->get(NULL,["estatus<>"=>0]);
		$data["familias"] = $this->fam_mdl->get(NULL,["estatus"=>1]);
		$data["cuantos"] = $this->pro_mdl->getCuantos(NULL)[0];
		$where=["usuarios.id_grupo" => 2, "usuarios.estatus" => 1];
		$data["proveedores"] = $this->usua_mdl->getUsuarios($where);
		$this->estructura("cotizaciones/index", $data);
	}

	public function getProveedorCot($ides){
		$data["cotizaciones"] =  $this->ct_mdl->getfalts(['fal.id_proveedor'=>$ides,'fal.fecha_termino >' => date("Y-m-d H:i:s")]);
		$this->jsonResponse($data);
	}

	public function save_falta($aprovs){
		$user = $this->session->userdata();
		$antes =  $this->falt_mdl->get(NULL, ['id_producto' => $this->input->post('prodFalta'), 'fecha_termino > ' => date("Y-m-d H:i:s"), 'id_proveedor' => $aprovs])[0];
		$fecha = new DateTime(date('Y-m-d H:i:s'));
		$intervalo = new DateInterval('P'.$this->input->post('faltante').'W');
		$fecha->add($intervalo);
		$aprod = $this->pro_mdl->get(NULL, ['id_producto'=>$this->input->post('prodFalta')])[0];
		$aprov = $this->usua_mdl->get(NULL, ['id_usuario'=>$aprovs])[0];
		if($antes){
			$cambios = [
						"id_usuario" => $user["id_usuario"],
						"fecha_cambio" => date('Y-m-d H:i:s'),
						"accion" => "Actualizo faltantes",
						"antes" => "id_faltante: ".$antes->id_faltante." /Proveedor: ".$aprov->nombre." /Producto:".$aprod->nombre."/F Termino: ".$antes->fecha_termino.
								"/Semanas: ".$antes->no_semanas." ",
						"despues" => "El usuario cambio las semanas: \n/Semanas: ".$this->input->post('faltante')."/Fecha Termino:".$fecha->format('Y-m-d H:i:s')];
			$data ['id_faltante'] = $this->falt_mdl->update([
				"no_semanas" => $this->input->post('faltante'),
				"fecha_termino" => $fecha->format('Y-m-d H:i:s')
			], $antes->id_faltante);
			$data['cambios'] = $this->cambio_md->insert($cambios);
		}else{
			$faltante = [
				'id_producto'	=>	$this->input->post('prodFalta'),
				'id_proveedor'	=>	$aprovs,
				'fecha_termino'	=>	$fecha->format('Y-m-d H:i:s'),
				'no_semanas' => $this->input->post('faltante')
			];
				$data ['id_faltante'] = $this->falt_mdl->insert($faltante);
				$cambios = [
				"id_usuario" => $user["id_usuario"],
				"fecha_cambio" => date('Y-m-d H:i:s'),
				"accion" => "Inserto faltantes",
				"antes" => "Proveedor: ".$aprov->nombre." /Producto:".$aprod->nombre."/F Termino: ".$fecha->format('Y-m-d H:i:s').
						"/Semanas: ".$this->input->post('faltante')."",
				"despues" => "El usuario agrego faltantes."];
			$data['cambios'] = $this->cambio_md->insert($cambios);
		}
		$mensaje = [
			"desc"	=> 'Cotización registrada correctamente',
			"type"	=> 'success'
		];
		$this->jsonResponse($mensaje);
	}

	public function delete_falta($val){
		$user = $this->session->userdata();
		$antes =  $this->falt_mdl->get(NULL, ['fecha_termino > ' => date("Y-m-d H:i:s"), 'id_proveedor' => $val]);
		$fecha = new DateTime(date('Y-m-d H:i:s'));
		$intervalo = new DateInterval('P2D');
		$fecha->sub($intervalo);
		$aprov = $this->usua_mdl->get(NULL, ['id_usuario'=> $val])[0];
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
		for ($i=3; $i<=$num_rows; $i++) {
			if($sheet->getCell('A'.$i)->getValue() !=''){
				$productos = $this->pro_mdl->get("id_producto",['codigo'=> htmlspecialchars($this->getOldVal($sheet,$i,'A'), ENT_QUOTES, 'UTF-8')])[0];
				if (sizeof($productos) > 0) {
					$no_semanas = $this->getOldVal($sheet,$i,'C') == '' ? 1 :$this->getOldVal($sheet,$i,'C');
					$fecha = new DateTime(date('Y-m-d H:i:s'));
					$intervalo = new DateInterval('P'.$no_semanas.'W');
					$fecha->add($intervalo);
					$fecha->format('Y-m-d H:i:s');
					$antes =  $this->falt_mdl->get(NULL, ['id_producto' => $productos->id_producto, 'fecha_termino > ' => date("Y-m-d H:i:s"), 'id_proveedor' => $idpro])[0];
					if($antes){
						$aprod = $this->pro_mdl->get(NULL, ['id_producto'=>$antes->id_producto])[0];
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
						$aprod = $this->pro_mdl->get(NULL, ['id_producto'=>$productos->id_producto ] )[0];
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

	public function getPendientes(){
		$fecha = new DateTime(date('Y-m-d H:i:s'));
		$intervalo = new DateInterval('P3D');
		$fecha->add($intervalo);
		$data["pendientes"] =  $this->pend_mdl->getThem(["WEEKOFYEAR(pp.fecha_registro)" => $this->weekNumber($fecha->format('Y-m-d H:i:s'))]);
		$this->jsonResponse($data);
	}

	public function upload_pendientes(){
		$fecha = new DateTime(date('Y-m-d H:i:s'));
		$intervalo = new DateInterval('P3D');
		$fecha->add($intervalo);
		$this->load->library("excelfile");
		ini_set("memory_limit", -1);
		$filen = "PedidosPendientes".rand();
		$config['upload_path']          = './assets/uploads/pedidos/';
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
			$productos = $this->pro_mdl->get("id_producto",['codigo'=> htmlspecialchars($this->getOldVal($sheet,$i,'A'), ENT_QUOTES, 'UTF-8')])[0];

			if (sizeof($productos) > 0) {
				$exis = $this->pend_mdl->get(NULL,["WEEKOFYEAR(fecha_registro)" => $this->weekNumber($fecha->format('Y-m-d H:i:s')),"id_producto"=>$productos->id_producto])[0];
				$cedis = $this->getOldVal($sheet,$i,'C') == "" ? 0 : $this->getOldVal($sheet,$i,'C');
				$abarrotes = $this->getOldVal($sheet,$i,'D') == "" ? 0 : $this->getOldVal($sheet,$i,'D');
				$pedregal = $this->getOldVal($sheet,$i,'E') == "" ? 0 : $this->getOldVal($sheet,$i,'E');
				$tienda = $this->getOldVal($sheet,$i,'F') == "" ? 0 : $this->getOldVal($sheet,$i,'F');
				$ultra = $this->getOldVal($sheet,$i,'G') == "" ? 0 : $this->getOldVal($sheet,$i,'G');
				$trincheras = $this->getOldVal($sheet,$i,'H') == "" ? 0 : $this->getOldVal($sheet,$i,'H');
				$mercado = $this->getOldVal($sheet,$i,'I') == "" ? 0 : $this->getOldVal($sheet,$i,'I');
				$tenencia = $this->getOldVal($sheet,$i,'J') == "" ? 0 : $this->getOldVal($sheet,$i,'J');
				$tijeras = $this->getOldVal($sheet,$i,'K') == "" ? 0 : $this->getOldVal($sheet,$i,'K');
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

	public function archivo_precios(){
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
		$this->cellStyle("A1:D2", "000000", "FFFFFF", TRUE, 12, "Franklin Gothic Book");
		$border_style= array('borders' => array('right' => array('style' =>
			PHPExcel_Style_Border::BORDER_THIN,'color' => array('argb' => '000000'),)));
		$hoja->setCellValue("B1", "DESCRIPCIÓN")->getColumnDimension('B')->setWidth(70);
		$hoja->setCellValue("C1", "SISTEMA")->getColumnDimension('C')->setWidth(15);
		$hoja->setCellValue("D1", "PRECIO 4")->getColumnDimension('D')->setWidth(50);
		$hoja->setCellValue("A2", "CÓDIGO")->getColumnDimension('A')->setWidth(30); //Nombre y ajuste de texto a la columna
		$productos = $this->pro_mdl->getProdFamS(NULL);
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

	public function getOldVal($sheets,$i,$le){
		$cellB = $sheets->getCell($le.$i)->getValue();
		if(strstr($cellB,'=')==true){
		    $cellB = $sheets->getCell($le.$i)->getOldCalculatedValue();
		}
		return $cellB;
	}

	public function upload_precios(){
		$fecha = new DateTime(date('Y-m-d H:i:s'));
		$intervalo = new DateInterval('P2D');
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
			if($this->getOldVal($sheet,$i,'A') !=''){
				$productos = $this->pro_mdl->get("id_producto",['codigo'=> htmlspecialchars($this->getOldVal($sheet,$i,'A'), ENT_QUOTES, 'UTF-8')])[0];
				if (sizeof($productos) > 0) {
					$new_precios=[
						"id_producto"		=>	$productos->id_producto,
						"precio_sistema"	=>	str_replace("$", "", str_replace(",", "replace", $this->getOldVal($sheet,$i,'C'))),
						"precio_four"		=>	str_replace("$", "", str_replace(",", "replace", $this->getOldVal($sheet,$i,'D'))),
						"fecha_registro"		=>	$fecha->format('Y-m-d H:i:s')
					];
					$precios = $this->pre_mdl->get("id_precio",['id_producto'=> $productos->id_producto, 'WEEKOFYEAR(fecha_registro)' => $this->weekNumber($fecha->format('Y-m-d H:i:s'))])[0];
					if(sizeof($precios) > 0 ){
						$data['cotizacion']=$this->pre_mdl->update($new_precios,
						['WEEKOFYEAR(fecha_registro)' => $this->weekNumber($fecha->format('Y-m-d H:i:s')),'id_precio'=>$precios->id_precio]);
					}else{
						$data['cotizacion']=$this->pre_mdl->insert($new_precios);
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

	public function getSistema(){
		$fecha = new DateTime(date('Y-m-d H:i:s'));
		$intervalo = new DateInterval('P3D');
		$fecha->add($intervalo);
		$data["sistema"] =  $this->pre_mdl->getPrecioSistema(["WEEKOFYEAR(precio_sistema.fecha_registro)" => $this->weekNumber($fecha->format('Y-m-d H:i:s'))]);
		$this->jsonResponse($data);
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
}