<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Sucursales extends MY_Controller {

	function __construct(){
		parent::__construct();
		$this->load->model("Sucursales_model", "suc_mdl");
		$this->load->model("Prolunes_model", "prolu_md");
		$this->load->model("Productos_model", "prod_mdl");
		$this->load->model("Exislunes_model", "ex_lun_md");
		$this->load->model("Existencias_model", "ex_mdl");
		$this->load->model("Usuarios_model", "usua_mdl");
		$this->load->model("Cambios_model", "cambio_md");
		$this->load->library("form_validation");
	}


	public function volumen(){
		$flag =1;
		$flag1=4;
		$flag2=1;
		ini_set("memory_limit", "-1");
		ini_set("max_execution_time", "-1");
		$this->load->library("excelfile");
		$user = $this->session->userdata();
		
		$hoja1 = $this->excelfile->setActiveSheetIndex(0);
		$this->excelfile->setActiveSheetIndex(0)->setTitle("EXISTENCIAS");
		$styleArray = array(
		  'borders' => array(
		    'allborders' => array(
		      'style' => PHPExcel_Style_Border::BORDER_THIN
		    )
		  )
		);
	
		$hoja1->getColumnDimension('A')->setWidth("6");
		$hoja1->getColumnDimension('B')->setWidth("6");
		$hoja1->getColumnDimension('C')->setWidth("6");
		$hoja1->getColumnDimension('D')->setWidth("25");
		$hoja1->getColumnDimension('E')->setWidth("60");
		$hoja1->getColumnDimension('F')->setWidth("80");
		$hoja1->getColumnDimension('G')->setWidth("28");

		$this->excelfile->setActiveSheetIndex(0);
		$flag2 = $flag;
		$hoja1->mergeCells('A'.$flag2.':F'.$flag2);
		$this->cellStyle("A".$flag2."", "FFFFFF", "000000", TRUE, 12, "Franklin Gothic Book");
		$hoja1->setCellValue("A".$flag2."", "GRUPO ABARROTES AZTECA");
		$this->excelfile->getActiveSheet()->getStyle('A'.$flag2.':F'.$flag2.'')->applyFromArray($styleArray);
		$flag2++;
		$hoja1->mergeCells('A'.$flag2.':F'.$flag2.'');
		$this->cellStyle("A".$flag2."", "FFFFFF", "000000", TRUE, 12, "Franklin Gothic Book");
		$hoja1->setCellValue("A".$flag2."", "FORMATO DE EXISTENCIAS VOLÚMEN ".date("d-m-Y"));
		$this->excelfile->getActiveSheet()->getStyle('A'.$flag2.':E'.$flag2.'')->applyFromArray($styleArray);
		$flag2++;
		$this->cellStyle("A".$flag2.":G".$flag2."", "000000", "FFFFFF", TRUE, 12, "Franklin Gothic Book");
		$hoja1->mergeCells('A'.$flag2.':C'.$flag2.'');
		$hoja1->setCellValue("A".$flag2."", "EXISTENCIAS");
		$hoja1->setCellValue("E".$flag2."", "DESCRIPCIÓN");
		$this->cellStyle("E".$flag2."", "000000", "FFFFFF", TRUE, 12, "Franklin Gothic Book");
		$hoja1->setCellValue("F".$flag2."", "PROMOCIÓN DE LA SEMANA");
		$this->cellStyle("F".$flag2."", "000000", "FFFFFF", TRUE, 12, "Franklin Gothic Book");
		$this->excelfile->getActiveSheet()->getStyle('A'.$flag2.':F'.$flag2.'')->applyFromArray($styleArray);
		$this->cellStyle("I".$flag2, "000000", "FFFFFF", TRUE, 12, "Franklin Gothic Book");
		$hoja1->setCellValue("I".$flag2."", "PENDIENTE");
		$flag2++;
		$this->cellStyle("A".$flag2.":G".$flag2."", "000000", "FFFFFF", TRUE, 12, "Franklin Gothic Book");
		$hoja1->setCellValue("A".$flag2."", "CAJAS");
		$hoja1->setCellValue("B".$flag2."", "PZAS");
		$hoja1->setCellValue("C".$flag2."", "PEDIDO");
		$hoja1->setCellValue("D".$flag2."", "CÓDIGO");
		$hoja1->setCellValue("G".$flag2."", "IMAGEN");
		$cotizacionesProveedor = $this->suc_mdl->getV(NULL);
		$sucursal = $this->suc_mdl->get(NULL,["id_sucursal"=>$user["id_usuario"]])[0];
		$hoja1->setCellValue("I".$flag2."", $sucursal->nombre);
		$this->cellStyle("I".$flag2."", substr($sucursal->color, 1) , "FFFFFF", TRUE, 12, "Franklin Gothic Book");


		if($cotizacionesProveedor){
			foreach ($cotizacionesProveedor as $key => $value) {
				$this->cellStyle("E".$flag1, "000000", "FFFFFF", FALSE, 12, "Franklin Gothic Book");
				$hoja1->setCellValue("E".$flag1, $value['familia']);
				$flag1 +=1;
				if ($value['articulos']) {
					foreach ($value['articulos'] as $key => $row){
						$this->excelfile->setActiveSheetIndex(0);
						$this->cellStyle("A".$flag1.":I".$flag1, "FFFFFF", "000000", FALSE, 12, "Franklin Gothic Book");
						
						$hoja1->setCellValue("D{$flag1}", $row['codigo'])->getStyle("D{$flag1}")->getNumberFormat()->setFormatCode('# ???/???');//Formato de fraccion
						if($row['color'] == '#92CEE3'){
							$this->cellStyle("D{$flag1}", "92CEE3", "000000", FALSE, 12, "Franklin Gothic Book");
						}else{
							$this->cellStyle("D{$flag1}", "FFFFFF", "000000", FALSE, 12, "Franklin Gothic Book");
						}
						$hoja1->setCellValue("E{$flag1}", $row['producto']);
						$hoja1->setCellValue("F{$flag1}", $row['observaciones']);
						$hoja1->setCellValue("I{$flag1}", $row[$user["id_usuario"]]);
						$this->excelfile->getActiveSheet()->getStyle('A'.$flag1.':I'.$flag1.'')->applyFromArray($styleArray);
						if($row["imagen"] <> "" && !is_null($row["imagen"])){
							$objDrawing = new PHPExcel_Worksheet_Drawing();
							$objDrawing->setName('COD'.$row['producto']);
							$objDrawing->setDescription('DESC'.$row['codigo']);
							$objDrawing->setPath("./assets/img/productos/".str_replace("_thumb.",".",$row["imagen"])."");
							if($this->sizeme($row["imagen"]) === 1 || $this->sizeme($row["imagen"]) === "1"){
								$objDrawing->setWidth(120);
								$objDrawing->setHeight($this->sizem1($row["imagen"]) * 1.80);
								$this->excelfile->getActiveSheet()->getRowDimension($flag1)->setRowHeight(intval( $this->sizem1($row["imagen"]) * 2 ));
								$objDrawing->setOffsetX(5); 
								$objDrawing->setOffsetY( intval((( $this->sizem1($row["imagen"])*2)-($this->sizem1($row["imagen"])*1.80))/0.4) );
							}else{
								$objDrawing->setHeight(120);
								$objDrawing->setWidth($this->sizem2($row["imagen"]) * 1.80);
								$this->excelfile->getActiveSheet()->getRowDimension($flag1)->setRowHeight( 150 );
								$objDrawing->setOffsetX( intval( (100 - $this->sizem2($row["imagen"]))/1 ) ); 
								$objDrawing->setOffsetY(5);
							}
							$objDrawing->setCoordinates('G'.$flag1);
							
							//$objDrawing->setUrl('http://abarrotesazteca.com/Abarrotes/assets/img/productos/'.$row["imagen"]);
							$objDrawing->setWorksheet($this->excelfile->getActiveSheet());
							$this->excelfile->getActiveSheet()->getStyleByColumnAndRow(10, $flag1)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_DATE_XLSX14);
							//$this->excelfile->getActiveSheet()->getCell('G'.$flag1)->getHyperlink()->setUrl('http://abarrotesazteca.com/Abarrotes/assets/img/productos/'.str_replace("_thumb.",".",$row["imagen"]);
						}else{
							$this->excelfile->getActiveSheet()->getRowDimension($flag1)->setRowHeight(120);
						}
						$hoja1->getStyle("A{$flag1}:F{$flag1}")
				                 ->getAlignment()
				                 ->setHorizontal(\PHPExcel_Style_Alignment::HORIZONTAL_LEFT);
				        $hoja1->getStyle("A{$flag1}:F{$flag1}")
				                 ->getAlignment()
				                 ->setHorizontal(\PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
						$flag1 ++;
					}
				}
			}
		}
		
		$dias = array("DOMINGO","LUNES","MARTES","MIÉRCOLES","JUEVES","VIERNES","SÁBADO");
		$meses = array("ENERO","FEBRERO","MARZO","ABRIL","MAYO","JUNIO","JULIO","AGOSTO","SEPTIEMBRE","OCTUBRE","NOVIEMBRE","DICIEMBRE");
		$fecha =  $dias[date('w')]." ".date('d')." DE ".$meses[date('n')-1]. " DEL ".date('Y') ;
		$file_name = "FORMATO EXISTENCIAS VOLÚMEN ".$fecha.".xlsx"; //Nombre del documento con extención
		$excel_Writer = PHPExcel_IOFactory::createWriter($this->excelfile, "Excel2007");
		header("Content-Type: application/vnd.ms-excel; charset=utf-8");
		header("Content-Disposition: attachment;filename=".$file_name);
		header("Cache-Control: max-age=0");
		$excel_Writer->save("php://output");
	}

	public function sizeme($filename){
		$source_path = $_SERVER['DOCUMENT_ROOT'] . '/Nuevo/assets/img/productos/' . $filename;
	    list($width, $height, $type, $attr) = getimagesize($source_path);
	    if ($width > $height) {
	      	return 1;
	      }else{
	      	return 0;
	      } 
   	}

   	public function sizem1($filename){
		$source_path = $_SERVER['DOCUMENT_ROOT'] . '/Nuevo/assets/img/productos/' . $filename;
	    list($width, $height, $type, $attr) = getimagesize($source_path);
	    return $height;	      
   	}

   	public function sizem2($filename){
		$source_path = $_SERVER['DOCUMENT_ROOT'] . '/Nuevo/assets/img/productos/' . $filename;
	    list($width, $height, $type, $attr) = getimagesize($source_path);
	    return $width;	      
   	}

   	public function volumenSin(){
		$flag =1;
		$flag1=4;
		$flag2=1;
		ini_set("memory_limit", "-1");
		ini_set("max_execution_time", "-1");
		$this->load->library("excelfile");
		$user = $this->session->userdata();
		
		$hoja1 = $this->excelfile->setActiveSheetIndex(0);
		$this->excelfile->setActiveSheetIndex(0)->setTitle("EXISTENCIAS");
		$styleArray = array(
		  'borders' => array(
		    'allborders' => array(
		      'style' => PHPExcel_Style_Border::BORDER_THIN
		    )
		  )
		);
	
		$hoja1->getColumnDimension('A')->setWidth("6");
		$hoja1->getColumnDimension('B')->setWidth("6");
		$hoja1->getColumnDimension('C')->setWidth("6");
		$hoja1->getColumnDimension('D')->setWidth("25");
		$hoja1->getColumnDimension('E')->setWidth("60");
		$hoja1->getColumnDimension('F')->setWidth("80");
		$hoja1->getColumnDimension('G')->setWidth("28");

		$this->excelfile->setActiveSheetIndex(0);
		$flag2 = $flag;
		$hoja1->mergeCells('A'.$flag2.':F'.$flag2);
		$this->cellStyle("A".$flag2."", "FFFFFF", "000000", TRUE, 12, "Franklin Gothic Book");
		$hoja1->setCellValue("A".$flag2."", "GRUPO ABARROTES AZTECA");
		$this->excelfile->getActiveSheet()->getStyle('A'.$flag2.':F'.$flag2.'')->applyFromArray($styleArray);
		$flag2++;
		$hoja1->mergeCells('A'.$flag2.':F'.$flag2.'');
		$this->cellStyle("A".$flag2."", "FFFFFF", "000000", TRUE, 12, "Franklin Gothic Book");
		$hoja1->setCellValue("A".$flag2."", "FORMATO DE EXISTENCIAS VOLÚMEN ".date("d-m-Y"));
		$this->excelfile->getActiveSheet()->getStyle('A'.$flag2.':E'.$flag2.'')->applyFromArray($styleArray);
		$flag2++;
		$this->cellStyle("A".$flag2.":G".$flag2."", "000000", "FFFFFF", TRUE, 12, "Franklin Gothic Book");
		$hoja1->mergeCells('A'.$flag2.':C'.$flag2.'');
		$hoja1->setCellValue("A".$flag2."", "EXISTENCIAS");
		$hoja1->setCellValue("E".$flag2."", "DESCRIPCIÓN");
		$this->cellStyle("E".$flag2."", "000000", "FFFFFF", TRUE, 12, "Franklin Gothic Book");
		$hoja1->setCellValue("F".$flag2."", "PROMOCIÓN DE LA SEMANA");
		$this->cellStyle("F".$flag2."", "000000", "FFFFFF", TRUE, 12, "Franklin Gothic Book");
		$this->excelfile->getActiveSheet()->getStyle('A'.$flag2.':F'.$flag2.'')->applyFromArray($styleArray);
		$this->cellStyle("H".$flag2, "000000", "FFFFFF", TRUE, 12, "Franklin Gothic Book");
		$hoja1->setCellValue("H".$flag2."", "PENDIENTE");
		$flag2++;
		$this->cellStyle("A".$flag2.":G".$flag2."", "000000", "FFFFFF", TRUE, 12, "Franklin Gothic Book");
		$hoja1->setCellValue("A".$flag2."", "CAJAS");
		$hoja1->setCellValue("B".$flag2."", "PZAS");
		$hoja1->setCellValue("C".$flag2."", "PEDIDO");
		$hoja1->setCellValue("D".$flag2."", "CÓDIGO");
		$cotizacionesProveedor = $this->suc_mdl->getV(NULL);
		$sucursal = $this->suc_mdl->get(NULL,["id_sucursal"=>$user["id_usuario"]])[0];
		$hoja1->setCellValue("H".$flag2."", $sucursal->nombre);
		$this->cellStyle("H".$flag2."", substr($sucursal->color, 1) , "FFFFFF", TRUE, 12, "Franklin Gothic Book");


		if($cotizacionesProveedor){
			foreach ($cotizacionesProveedor as $key => $value) {
				$this->cellStyle("E".$flag1, "000000", "FFFFFF", FALSE, 12, "Franklin Gothic Book");
				$hoja1->setCellValue("E".$flag1, $value['familia']);
				$flag1 +=1;
				if ($value['articulos']) {
					foreach ($value['articulos'] as $key => $row){
						$this->excelfile->setActiveSheetIndex(0);
						$this->cellStyle("A".$flag1.":H".$flag1, "FFFFFF", "000000", FALSE, 12, "Franklin Gothic Book");
						
						$hoja1->setCellValue("D{$flag1}", $row['codigo'])->getStyle("D{$flag1}")->getNumberFormat()->setFormatCode('# ???/???');//Formato de fraccion
						if($row['color'] == '#92CEE3'){
							$this->cellStyle("D{$flag1}", "92CEE3", "000000", FALSE, 12, "Franklin Gothic Book");
						}else{
							$this->cellStyle("D{$flag1}", "FFFFFF", "000000", FALSE, 12, "Franklin Gothic Book");
						}
						$hoja1->setCellValue("E{$flag1}", $row['producto']);
						$hoja1->setCellValue("F{$flag1}", $row['observaciones']);
						$hoja1->setCellValue("H{$flag1}", $row[$user["id_usuario"]]);
						$this->excelfile->getActiveSheet()->getStyle('A'.$flag1.':F'.$flag1.'')->applyFromArray($styleArray);
						$this->excelfile->getActiveSheet()->getStyle('H'.$flag1)->applyFromArray($styleArray);
						
				        $hoja1->getStyle("A{$flag1}:H{$flag1}")
				                 ->getAlignment()
				                 ->setHorizontal(\PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
						$flag1 ++;
					}
				}
			}
		}
		
		$dias = array("DOMINGO","LUNES","MARTES","MIÉRCOLES","JUEVES","VIERNES","SÁBADO");
		$meses = array("ENERO","FEBRERO","MARZO","ABRIL","MAYO","JUNIO","JULIO","AGOSTO","SEPTIEMBRE","OCTUBRE","NOVIEMBRE","DICIEMBRE");
		$fecha =  $dias[date('w')]." ".date('d')." DE ".$meses[date('n')-1]. " DEL ".date('Y') ;
		$file_name = "FORMATO EXISTENCIAS VOLÚMEN ".$fecha.".xlsx"; //Nombre del documento con extención
		$excel_Writer = PHPExcel_IOFactory::createWriter($this->excelfile, "Excel2007");
		header("Content-Type: application/vnd.ms-excel; charset=utf-8");
		header("Content-Disposition: attachment;filename=".$file_name);
		header("Cache-Control: max-age=0");
		$excel_Writer->save("php://output");
	}


	public function general(){
		$flag =1;
		$flag1=4;
		$flag2=1;
		ini_set("memory_limit", "-1");
		ini_set("max_execution_time", "-1");
		$this->load->library("excelfile");
		$user = $this->session->userdata();
		
		$hoja1 = $this->excelfile->setActiveSheetIndex(0);
		$this->excelfile->setActiveSheetIndex(0)->setTitle("EXISTENCIAS");
		$styleArray = array(
		  'borders' => array(
		    'allborders' => array(
		      'style' => PHPExcel_Style_Border::BORDER_THIN
		    )
		  )
		);
	
		$hoja1->getColumnDimension('A')->setWidth("6");
		$hoja1->getColumnDimension('B')->setWidth("6");
		$hoja1->getColumnDimension('C')->setWidth("6");
		$hoja1->getColumnDimension('D')->setWidth("25");
		$hoja1->getColumnDimension('E')->setWidth("60");
		$hoja1->getColumnDimension('F')->setWidth("80");
		$hoja1->getColumnDimension('G')->setWidth("28");

		$this->excelfile->setActiveSheetIndex(0);
		$flag2 = $flag;
		$hoja1->mergeCells('A'.$flag2.':F'.$flag2);
		$this->cellStyle("A".$flag2."", "FFFFFF", "000000", TRUE, 12, "Franklin Gothic Book");
		$hoja1->setCellValue("A".$flag2."", "GRUPO ABARROTES AZTECA");
		$this->excelfile->getActiveSheet()->getStyle('A'.$flag2.':F'.$flag2.'')->applyFromArray($styleArray);
		$flag2++;
		$hoja1->mergeCells('A'.$flag2.':F'.$flag2.'');
		$this->cellStyle("A".$flag2."", "FFFFFF", "000000", TRUE, 12, "Franklin Gothic Book");
		$hoja1->setCellValue("A".$flag2."", "FORMATO DE EXISTENCIAS GENERAL ".date("d-m-Y"));
		$this->excelfile->getActiveSheet()->getStyle('A'.$flag2.':E'.$flag2.'')->applyFromArray($styleArray);
		$flag2++;
		$this->cellStyle("A".$flag2.":G".$flag2."", "000000", "FFFFFF", TRUE, 12, "Franklin Gothic Book");
		$hoja1->mergeCells('A'.$flag2.':C'.$flag2.'');
		$hoja1->setCellValue("A".$flag2."", "EXISTENCIAS");
		$hoja1->setCellValue("E".$flag2."", "DESCRIPCIÓN");
		$this->cellStyle("E".$flag2."", "000000", "FFFFFF", TRUE, 12, "Franklin Gothic Book");
		$hoja1->setCellValue("F".$flag2."", "PROMOCIÓN DE LA SEMANA");
		$this->cellStyle("F".$flag2."", "000000", "FFFFFF", TRUE, 12, "Franklin Gothic Book");
		$this->excelfile->getActiveSheet()->getStyle('A'.$flag2.':F'.$flag2.'')->applyFromArray($styleArray);
		$this->cellStyle("I".$flag2, "000000", "FFFFFF", TRUE, 12, "Franklin Gothic Book");
		$hoja1->setCellValue("I".$flag2."", "PENDIENTE");
		$flag2++;
		$this->cellStyle("A".$flag2.":G".$flag2."", "000000", "FFFFFF", TRUE, 12, "Franklin Gothic Book");
		$hoja1->setCellValue("A".$flag2."", "CAJAS");
		$hoja1->setCellValue("B".$flag2."", "PZAS");
		$hoja1->setCellValue("C".$flag2."", "PEDIDO");
		$hoja1->setCellValue("D".$flag2."", "CÓDIGO");
		$hoja1->setCellValue("G".$flag2."", "IMAGEN");
		$cotizacionesProveedor = $this->suc_mdl->getG(NULL);
		$sucursal = $this->suc_mdl->get(NULL,["id_sucursal"=>$user["id_usuario"]])[0];
		$hoja1->setCellValue("I".$flag2."", $sucursal->nombre);
		$this->cellStyle("I".$flag2."", substr($sucursal->color, 1) , "FFFFFF", TRUE, 12, "Franklin Gothic Book");


		if($cotizacionesProveedor){
			foreach ($cotizacionesProveedor as $key => $value) {
				$this->cellStyle("E".$flag1, "000000", "FFFFFF", FALSE, 12, "Franklin Gothic Book");
				$hoja1->setCellValue("E".$flag1, $value['familia']);
				$flag1 +=1;
				if ($value['articulos']) {
					foreach ($value['articulos'] as $key => $row){
						$this->excelfile->setActiveSheetIndex(0);
						$this->cellStyle("A".$flag1.":I".$flag1, "FFFFFF", "000000", FALSE, 12, "Franklin Gothic Book");
						
						$hoja1->setCellValue("D{$flag1}", $row['codigo'])->getStyle("D{$flag1}")->getNumberFormat()->setFormatCode('# ???/???');//Formato de fraccion
						if($row['color'] == '#92CEE3'){
							$this->cellStyle("D{$flag1}", "92CEE3", "000000", FALSE, 12, "Franklin Gothic Book");
						}else{
							$this->cellStyle("D{$flag1}", "FFFFFF", "000000", FALSE, 12, "Franklin Gothic Book");
						}
						$hoja1->setCellValue("E{$flag1}", $row['producto']);
						$hoja1->setCellValue("F{$flag1}", $row['observaciones']);
						$hoja1->setCellValue("I{$flag1}", $row[$user["id_usuario"]]);
						$this->excelfile->getActiveSheet()->getStyle('A'.$flag1.':G'.$flag1.'')->applyFromArray($styleArray);
						$this->excelfile->getActiveSheet()->getStyle('I'.$flag1)->applyFromArray($styleArray);
						if($row["imagen"] <> "" && !is_null($row["imagen"])){
							$objDrawing = new PHPExcel_Worksheet_Drawing();
							$objDrawing->setName('COD'.$row['producto']);
							$objDrawing->setDescription('DESC'.$row['codigo']);
							$objDrawing->setPath("./assets/img/productos/".str_replace("_thumb.",".",$row["imagen"])."");
							if($this->sizeme($row["imagen"]) === 1 || $this->sizeme($row["imagen"]) === "1"){
								$objDrawing->setWidth(120);
								$objDrawing->setHeight($this->sizem1($row["imagen"]) * 1.80);
								$this->excelfile->getActiveSheet()->getRowDimension($flag1)->setRowHeight(intval( $this->sizem1($row["imagen"]) * 2 ));
								$objDrawing->setOffsetX(5); 
								$objDrawing->setOffsetY( intval((( $this->sizem1($row["imagen"])*2)-($this->sizem1($row["imagen"])*1.80))/0.4) );
							}else{
								$objDrawing->setHeight(120);
								$objDrawing->setWidth($this->sizem2($row["imagen"]) * 1.80);
								$this->excelfile->getActiveSheet()->getRowDimension($flag1)->setRowHeight( 150 );
								$objDrawing->setOffsetX( intval( (100 - $this->sizem2($row["imagen"]))/1 ) ); 
								$objDrawing->setOffsetY(5);
							}
							$objDrawing->setCoordinates('G'.$flag1);
							
							//$objDrawing->setUrl('http://abarrotesazteca.com/Abarrotes/assets/img/productos/'.$row["imagen"]);
							$objDrawing->setWorksheet($this->excelfile->getActiveSheet());
							$this->excelfile->getActiveSheet()->getStyleByColumnAndRow(10, $flag1)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_DATE_XLSX14);
							//$this->excelfile->getActiveSheet()->getCell('G'.$flag1)->getHyperlink()->setUrl('http://abarrotesazteca.com/Abarrotes/assets/img/productos/'.str_replace("_thumb.",".",$row["imagen"]);
						}else{
							$this->excelfile->getActiveSheet()->getRowDimension($flag1)->setRowHeight(120);
						}
						$hoja1->getStyle("A{$flag1}:F{$flag1}")
				                 ->getAlignment()
				                 ->setHorizontal(\PHPExcel_Style_Alignment::HORIZONTAL_LEFT);
				        $hoja1->getStyle("A{$flag1}:F{$flag1}")
				                 ->getAlignment()
				                 ->setHorizontal(\PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
						$flag1 ++;
					}
				}
			}
		}
		
		$dias = array("DOMINGO","LUNES","MARTES","MIÉRCOLES","JUEVES","VIERNES","SÁBADO");
		$meses = array("ENERO","FEBRERO","MARZO","ABRIL","MAYO","JUNIO","JULIO","AGOSTO","SEPTIEMBRE","OCTUBRE","NOVIEMBRE","DICIEMBRE");
		$fecha =  $dias[date('w')]." ".date('d')." DE ".$meses[date('n')-1]. " DEL ".date('Y') ;
		$file_name = "FORMATO EXISTENCIAS GENERAL ".$fecha.".xlsx"; //Nombre del documento con extención
		$excel_Writer = PHPExcel_IOFactory::createWriter($this->excelfile, "Excel2007");
		header("Content-Type: application/vnd.ms-excel; charset=utf-8");
		header("Content-Disposition: attachment;filename=".$file_name);
		header("Cache-Control: max-age=0");
		$excel_Writer->save("php://output");
	}

	public function generalSin(){
		$flag =1;
		$flag1=4;
		$flag2=1;
		ini_set("memory_limit", "-1");
		ini_set("max_execution_time", "-1");
		$this->load->library("excelfile");
		$user = $this->session->userdata();
		
		$hoja1 = $this->excelfile->setActiveSheetIndex(0);
		$this->excelfile->setActiveSheetIndex(0)->setTitle("EXISTENCIAS");
		$styleArray = array(
		  'borders' => array(
		    'allborders' => array(
		      'style' => PHPExcel_Style_Border::BORDER_THIN
		    )
		  )
		);
	
		$hoja1->getColumnDimension('A')->setWidth("6");
		$hoja1->getColumnDimension('B')->setWidth("6");
		$hoja1->getColumnDimension('C')->setWidth("6");
		$hoja1->getColumnDimension('D')->setWidth("25");
		$hoja1->getColumnDimension('E')->setWidth("60");
		$hoja1->getColumnDimension('F')->setWidth("80");
		$hoja1->getColumnDimension('G')->setWidth("28");

		$this->excelfile->setActiveSheetIndex(0);
		$flag2 = $flag;
		$hoja1->mergeCells('A'.$flag2.':F'.$flag2);
		$this->cellStyle("A".$flag2."", "FFFFFF", "000000", TRUE, 12, "Franklin Gothic Book");
		$hoja1->setCellValue("A".$flag2."", "GRUPO ABARROTES AZTECA");
		$this->excelfile->getActiveSheet()->getStyle('A'.$flag2.':F'.$flag2.'')->applyFromArray($styleArray);
		$flag2++;
		$hoja1->mergeCells('A'.$flag2.':F'.$flag2.'');
		$this->cellStyle("A".$flag2."", "FFFFFF", "000000", TRUE, 12, "Franklin Gothic Book");
		$hoja1->setCellValue("A".$flag2."", "FORMATO DE EXISTENCIAS GENERAL ".date("d-m-Y"));
		$this->excelfile->getActiveSheet()->getStyle('A'.$flag2.':E'.$flag2.'')->applyFromArray($styleArray);
		$flag2++;
		$this->cellStyle("A".$flag2.":G".$flag2."", "000000", "FFFFFF", TRUE, 12, "Franklin Gothic Book");
		$hoja1->mergeCells('A'.$flag2.':C'.$flag2.'');
		$hoja1->setCellValue("A".$flag2."", "EXISTENCIAS");
		$hoja1->setCellValue("E".$flag2."", "DESCRIPCIÓN");
		$this->cellStyle("E".$flag2."", "000000", "FFFFFF", TRUE, 12, "Franklin Gothic Book");
		$hoja1->setCellValue("F".$flag2."", "PROMOCIÓN DE LA SEMANA");
		$this->cellStyle("F".$flag2."", "000000", "FFFFFF", TRUE, 12, "Franklin Gothic Book");
		$this->excelfile->getActiveSheet()->getStyle('A'.$flag2.':F'.$flag2.'')->applyFromArray($styleArray);
		$this->cellStyle("H".$flag2, "000000", "FFFFFF", TRUE, 12, "Franklin Gothic Book");
		$hoja1->setCellValue("H".$flag2."", "PENDIENTE");
		$flag2++;
		$this->cellStyle("A".$flag2.":G".$flag2."", "000000", "FFFFFF", TRUE, 12, "Franklin Gothic Book");
		$hoja1->setCellValue("A".$flag2."", "CAJAS");
		$hoja1->setCellValue("B".$flag2."", "PZAS");
		$hoja1->setCellValue("C".$flag2."", "PEDIDO");
		$hoja1->setCellValue("D".$flag2."", "CÓDIGO");
		$cotizacionesProveedor = $this->suc_mdl->getG(NULL);
		$sucursal = $this->suc_mdl->get(NULL,["id_sucursal"=>$user["id_usuario"]])[0];
		$hoja1->setCellValue("H".$flag2."", $sucursal->nombre);
		$this->cellStyle("H".$flag2."", substr($sucursal->color, 1) , "FFFFFF", TRUE, 12, "Franklin Gothic Book");


		if($cotizacionesProveedor){
			foreach ($cotizacionesProveedor as $key => $value) {
				$this->cellStyle("E".$flag1, "000000", "FFFFFF", FALSE, 12, "Franklin Gothic Book");
				$hoja1->setCellValue("E".$flag1, $value['familia']);
				$flag1 +=1;
				if ($value['articulos']) {
					foreach ($value['articulos'] as $key => $row){
						$this->excelfile->setActiveSheetIndex(0);
						$this->cellStyle("A".$flag1.":H".$flag1, "FFFFFF", "000000", FALSE, 12, "Franklin Gothic Book");
						
						$hoja1->setCellValue("D{$flag1}", $row['codigo'])->getStyle("D{$flag1}")->getNumberFormat()->setFormatCode('# ???/???');//Formato de fraccion
						if($row['color'] == '#92CEE3'){
							$this->cellStyle("D{$flag1}", "92CEE3", "000000", FALSE, 12, "Franklin Gothic Book");
						}else{
							$this->cellStyle("D{$flag1}", "FFFFFF", "000000", FALSE, 12, "Franklin Gothic Book");
						}
						$hoja1->setCellValue("E{$flag1}", $row['producto']);
						$hoja1->setCellValue("F{$flag1}", $row['observaciones']);
						$hoja1->setCellValue("H{$flag1}", $row[$user["id_usuario"]]);
						$this->excelfile->getActiveSheet()->getStyle('A'.$flag1.':F'.$flag1.'')->applyFromArray($styleArray);
						$this->excelfile->getActiveSheet()->getStyle('H'.$flag1)->applyFromArray($styleArray);
						
				        $hoja1->getStyle("A{$flag1}:H{$flag1}")
				                 ->getAlignment()
				                 ->setHorizontal(\PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
						$flag1 ++;
					}
				}
			}
		}
		
		$dias = array("DOMINGO","LUNES","MARTES","MIÉRCOLES","JUEVES","VIERNES","SÁBADO");
		$meses = array("ENERO","FEBRERO","MARZO","ABRIL","MAYO","JUNIO","JULIO","AGOSTO","SEPTIEMBRE","OCTUBRE","NOVIEMBRE","DICIEMBRE");
		$fecha =  $dias[date('w')]." ".date('d')." DE ".$meses[date('n')-1]. " DEL ".date('Y') ;
		$file_name = "FORMATO EXISTENCIAS GENERAL ".$fecha.".xlsx"; //Nombre del documento con extención
		$excel_Writer = PHPExcel_IOFactory::createWriter($this->excelfile, "Excel2007");
		header("Content-Type: application/vnd.ms-excel; charset=utf-8");
		header("Content-Disposition: attachment;filename=".$file_name);
		header("Cache-Control: max-age=0");
		$excel_Writer->save("php://output");
	}

	public function lunesSin(){
		ini_set("memory_limit", "-1");
		ini_set("max_execution_time", "-1");
		$this->load->library("excelfile");
		$hoja = $this->excelfile->setActiveSheetIndex(0);
		$this->excelfile->setActiveSheetIndex(0)->setTitle("EXISTENCIAS");
		$styleArray = array(
		  'borders' => array(
		    'allborders' => array(
		      'style' => PHPExcel_Style_Border::BORDER_THIN
		    )
		  )
		);

		$hoja->getColumnDimension('A')->setWidth("10");
		$hoja->getColumnDimension('B')->setWidth("10");
		$hoja->getColumnDimension('D')->setWidth("22");
		$hoja->getColumnDimension('C')->setWidth("10");
		$hoja->getColumnDimension('E')->setWidth("70");
		$hoja->getColumnDimension('F')->setWidth("50");

		$productos = $this->ex_lun_md->getPlantilla(NULL);
		$alias = "";
		$flag = 1;

		
		if ($productos){
			foreach ($productos as $key => $value){
				if ($alias <> $value->alias) {
					if ($flag <> 1) {
						$flag++;
						$flag++;
					}
					$alias = $value->alias;
					$hoja->mergeCells('A'.$flag.':F'.$flag);
					$this->cellStyle("A".$flag."", "4f81bd", "FFFFFF", TRUE, 12, "Franklin Gothic Book");
					$hoja->setCellValue("A".$flag."", $value->nombre);
					$this->excelfile->getActiveSheet()->getStyle('A'.$flag.':F'.$flag.'')->applyFromArray($styleArray);
					$this->cellStyle("G".$flag."", "000000", "FFFFFF", TRUE, 12, "Franklin Gothic Book");
					$hoja->setCellValue("G".$flag."", "PENDIENT");
					$this->cellStyle("H".$flag."", "000000", "FFFFFF", TRUE, 12, "Franklin Gothic Book");
					$hoja->setCellValue("H".$flag."", "PENDIENT");
					$this->cellStyle("I".$flag."", "000000", "FFFFFF", TRUE, 12, "Franklin Gothic Book");
					$hoja->setCellValue("I".$flag."", "PENDIENT");
					$this->cellStyle("J".$flag."", "000000", "FFFFFF", TRUE, 12, "Franklin Gothic Book");
					$hoja->setCellValue("J".$flag."", "PENDIENT");
					$this->cellStyle("K".$flag."", "000000", "FFFFFF", TRUE, 12, "Franklin Gothic Book");
					$hoja->setCellValue("K".$flag."", "PENDIENT");
					$this->cellStyle("L".$flag."", "000000", "FFFFFF", TRUE, 12, "Franklin Gothic Book");
					$hoja->setCellValue("L".$flag."", "PENDIENT");
					$this->cellStyle("M".$flag."", "000000", "FFFFFF", TRUE, 12, "Franklin Gothic Book");
					$hoja->setCellValue("M".$flag."", "PENDIENT");
					$this->cellStyle("N".$flag."", "000000", "FFFFFF", TRUE, 12, "Franklin Gothic Book");
					$hoja->setCellValue("N".$flag."", "PENDIENT");
					$this->cellStyle("O".$flag."", "000000", "FFFFFF", TRUE, 12, "Franklin Gothic Book");
					$hoja->setCellValue("O".$flag."", "PENDIENT");
					$flag++;
					$this->excelfile->getActiveSheet()->getStyle('A'.$flag.':F'.$flag.'')->applyFromArray($styleArray);
					$this->cellStyle("A".$flag."", "1f497d", "FFFFFF", TRUE, 12, "Franklin Gothic Book");
					$this->cellStyle("B".$flag."", "1f497d", "FFFFFF", TRUE, 12, "Franklin Gothic Book");
					$this->cellStyle("C".$flag."", "1f497d", "FFFFFF", TRUE, 12, "Franklin Gothic Book");
					$this->cellStyle("D".$flag."", "1f497d", "FFFFFF", TRUE, 12, "Franklin Gothic Book");
					$this->cellStyle("E".$flag."", "1f497d", "FFFFFF", TRUE, 12, "Franklin Gothic Book");
					$this->cellStyle("F".$flag."", "1f497d", "FFFFFF", TRUE, 12, "Franklin Gothic Book");
					$hoja->setCellValue("A".$flag."", "CAJA");
					$hoja->setCellValue("B".$flag."", "PZAS");
					$hoja->setCellValue("C".$flag."", "PEDIDO");
					$hoja->setCellValue("D".$flag."", "CÓDIGO");
					$hoja->setCellValue("E".$flag."", "DESCRIPCIÓN");
					$hoja->setCellValue("F".$flag."", "PROMOCIÓN");
					$hoja->setCellValue("A".$flag."", "CAJAS");
					$hoja->setCellValue("B".$flag."", "PZAS");
					$hoja->setCellValue("C".$flag."", "PEDIDO");
					$hoja->setCellValue("D".$flag."", "CÓDIGO");
					$hoja->setCellValue("F".$flag."", "PROMOCIÓN");
					$this->cellStyle("G".$flag, "C00000", "000000", TRUE, 10, "Franklin Gothic Book");
					$this->cellStyle("H".$flag, "01B0F0", "000000", TRUE, 10, "Franklin Gothic Book");
					$this->cellStyle("I".$flag, "FF0000", "000000", TRUE, 10, "Franklin Gothic Book");
					$this->cellStyle("J".$flag, "E26C0B", "000000", TRUE, 10, "Franklin Gothic Book");
					$this->cellStyle("K".$flag, "C5C5C5", "000000", TRUE, 10, "Franklin Gothic Book");
					$this->cellStyle("L".$flag, "92D051", "000000", TRUE, 10, "Franklin Gothic Book");
					$this->cellStyle("M".$flag, "B1A0C7", "000000", TRUE, 10, "Franklin Gothic Book");
					$this->cellStyle("N".$flag, "DA9694", "000000", TRUE, 10, "Franklin Gothic Book");
					$this->cellStyle("O".$flag, "4CACC6", "000000", TRUE, 10, "Franklin Gothic Book");
					$hoja->setCellValue("G".$flag."", "CEDIS");
					$hoja->setCellValue("H".$flag."", "ABARROTES");
					$hoja->setCellValue("I".$flag."", "VILLAS");
					$hoja->setCellValue("J".$flag."", "TIENDA");
					$hoja->setCellValue("K".$flag."", "ULTRA");
					$hoja->setCellValue("L".$flag."", "TRINCHERAS");
					$hoja->setCellValue("M".$flag."", "MERCADO");
					$hoja->setCellValue("N".$flag."", "TENENCIA");
					$hoja->setCellValue("O".$flag."", "TIJERAS");
					$flag++;
					$this->excelfile->getActiveSheet()->getStyle('A'.$flag.':F'.$flag.'')->applyFromArray($styleArray);
					$this->cellStyle("D".$flag."", "FFFFFF", "000000", FALSE, 10, "Franklin Gothic Book");
					$this->cellStyle("E".$flag."", "FFFFFF", "000000", FALSE, 10, "Franklin Gothic Book");
					$hoja->setCellValue("D".$flag."", $value->codigo)->getStyle("D{$flag}")->getNumberFormat()->setFormatCode('# ???/???');
					$hoja->setCellValue("E".$flag."", $value->descripcion);
					$hoja->setCellValue("F".$flag."", $value->observaciones);
					$hoja->setCellValue("G".$flag."", $value->cedis);
					$hoja->setCellValue("H".$flag."", $value->abarrotes);
					$hoja->setCellValue("I".$flag."", $value->pedregal);
					$hoja->setCellValue("J".$flag."", $value->tienda);
					$hoja->setCellValue("K".$flag."", $value->ultra);
					$hoja->setCellValue("L".$flag."", $value->trincheras);
					$hoja->setCellValue("M".$flag."", $value->mercado);
					$hoja->setCellValue("N".$flag."", $value->tenencia);
					$hoja->setCellValue("O".$flag."", $value->tijeras);
					if ($value->promo === 1 || $value->promo === "1") {
						$condRed = new PHPExcel_Style_Conditional();
						$condRed->setConditionType(PHPExcel_Style_Conditional::CONDITION_EXPRESSION)
				                ->addCondition("=MOD(C".$flag.",".$value->cuantos1.")>0")
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
						$conditionalStyles = $this->excelfile->getActiveSheet()->getStyle('C'.$flag)->getConditionalStyles();
						array_push($conditionalStyles,$condRed);
						$this->excelfile->getActiveSheet()->getStyle('C'.$flag)->setConditionalStyles($conditionalStyles);
					}elseif ($value->promo === 3 || $value->promo === "3") {
						$condRed = new PHPExcel_Style_Conditional();
						$condRed->setConditionType(PHPExcel_Style_Conditional::CONDITION_EXPRESSION)
				                ->addCondition("=MOD(C".$flag.",".$value->cuantos1.")>0")
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
						$conditionalStyles = $this->excelfile->getActiveSheet()->getStyle('C'.$flag)->getConditionalStyles();
						array_push($conditionalStyles,$condRed);
						$this->excelfile->getActiveSheet()->getStyle('C'.$flag)->setConditionalStyles($conditionalStyles);
					}
					$flag++;
				}else{
					$this->excelfile->getActiveSheet()->getStyle('A'.$flag.':F'.$flag.'')->applyFromArray($styleArray);
					$this->cellStyle("D".$flag."", "FFFFFF", "000000", FALSE, 10, "Franklin Gothic Book");
					$this->cellStyle("E".$flag."", "FFFFFF", "000000", FALSE, 10, "Franklin Gothic Book");
					$hoja->setCellValue("D".$flag."", $value->codigo)->getStyle("D{$flag}")->getNumberFormat()->setFormatCode('# ???/???');
					$hoja->setCellValue("E".$flag."", $value->descripcion);
					$hoja->setCellValue("F".$flag."", $value->observaciones);
					$hoja->setCellValue("G".$flag."", $value->cedis);
					$hoja->setCellValue("H".$flag."", $value->abarrotes);
					$hoja->setCellValue("I".$flag."", $value->pedregal);
					$hoja->setCellValue("J".$flag."", $value->tienda);
					$hoja->setCellValue("K".$flag."", $value->ultra);
					$hoja->setCellValue("L".$flag."", $value->trincheras);
					$hoja->setCellValue("M".$flag."", $value->mercado);
					$hoja->setCellValue("N".$flag."", $value->tenencia);
					$hoja->setCellValue("O".$flag."", $value->tijeras);
					if ($value->promo === 1 || $value->promo === "1") {
						$condRed = new PHPExcel_Style_Conditional();
						$condRed->setConditionType(PHPExcel_Style_Conditional::CONDITION_EXPRESSION)
				                ->addCondition("=MOD(C".$flag.",".$value->cuantos1.")>0")
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
						$conditionalStyles = $this->excelfile->getActiveSheet()->getStyle('C'.$flag)->getConditionalStyles();
						array_push($conditionalStyles,$condRed);
						$this->excelfile->getActiveSheet()->getStyle('C'.$flag)->setConditionalStyles($conditionalStyles);
					}elseif ($value->promo === 3 || $value->promo === "3") {
						$condRed = new PHPExcel_Style_Conditional();
						$condRed->setConditionType(PHPExcel_Style_Conditional::CONDITION_EXPRESSION)
				                ->addCondition("=MOD(C".$flag.",".$value->cuantos1.")>0")
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
						$conditionalStyles = $this->excelfile->getActiveSheet()->getStyle('C'.$flag)->getConditionalStyles();
						array_push($conditionalStyles,$condRed);
						$this->excelfile->getActiveSheet()->getStyle('C'.$flag)->setConditionalStyles($conditionalStyles);
					}
					$flag++;
				}
			}
		}

		$dias = array("DOMINGO","LUNES","MARTES","MIÉRCOLES","JUEVES","VIERNES","SÁBADO");
		$meses = array("ENERO","FEBRERO","MARZO","ABRIL","MAYO","JUNIO","JULIO","AGOSTO","SEPTIEMBRE","OCTUBRE","NOVIEMBRE","DICIEMBRE");
		$fecha =  $dias[date('w')]." ".date('d')." DE ".$meses[date('n')-1]. " DEL ".date('Y') ;
		$file_name = "Formato Existencias Lunes ".$fecha.".xlsx"; //Nombre del documento con extención
		$excel_Writer = PHPExcel_IOFactory::createWriter($this->excelfile, "Excel2007");
		header("Content-Type: application/vnd.ms-excel; charset=utf-8");
		header("Content-Disposition: attachment;filename=".$file_name);
		header("Cache-Control: max-age=0");
		$excel_Writer->save("php://output");
	}

	public function getOldVal($sheets,$i,$le){
		$cellB = $sheets->getCell($le.$i)->getValue();
		if(strstr($cellB,'=')==true){
		    $cellB = $sheets->getCell($le.$i)->getOldCalculatedValue();
		}
		return $cellB;
	}

	public function upload_existencias(){
		ini_set("memory_limit", -1);

		$fecha = new DateTime(date('Y-m-d H:i:s'));
		$id_tienda = $this->session->userdata('id_usuario');
		$cfile =  $this->usua_mdl->get(NULL, ['id_usuario' => $id_tienda])[0];
		$nams = preg_replace('/\s+/', '_', $cfile->nombre);
		$filen = "Existencias".$nams."".rand();
		$config['upload_path']          = './assets/uploads/existencias/';
        $config['allowed_types']        = 'xlsx|xls';
        $config['max_size']             = 100002400;
        $config['max_width']            = 100002400;
        $config['max_height']           = 100002400;
        $this->load->library('upload', $config);
        $this->upload->initialize($config);
        $this->upload->do_upload('file_existencias',$filen);
		$this->load->library("excelfile");
		$file = $_FILES["file_existencias"]["tmp_name"];
		$filename=$_FILES['file_existencias']['name'];

		$sheet = PHPExcel_IOFactory::load($file);
		$objExcel = PHPExcel_IOFactory::load($file);
		$sheet = $objExcel->getSheet(0);
		$num_rows = $sheet->getHighestDataRow();
		$formato = "Normal";

		$productos = $this->prolu_md->get("codigo",['codigo'=> htmlspecialchars($sheet->getCell('D4')->getValue(), ENT_QUOTES, 'UTF-8')])[0];
		if($productos){
			$formato = "Lunes";
		}else{
			$productos = $this->prolu_md->get("codigo",['codigo'=> htmlspecialchars($sheet->getCell('D5')->getValue(), ENT_QUOTES, 'UTF-8')])[0];
			if ($productos) {
				$formato = "Lunes";
			}
		}

		if ($formato === "Lunes") {
			
			for ($i=1; $i<=$num_rows; $i++) {
				$productos = $this->prolu_md->get("codigo",['codigo'=> htmlspecialchars($sheet->getCell('D'.$i)->getValue(), ENT_QUOTES, 'UTF-8')])[0];
				if (sizeof($productos) > 0) {
					$exis = $this->ex_lun_md->get(NULL,["WEEKOFYEAR(fecha_registro)" => $this->weekNumber($fecha->format('Y-m-d H:i:s')),"id_tienda"=>$id_tienda,"id_producto"=>$productos->codigo])[0];
					$column_one=0; $column_two=0; $column_three=0;
					$column_one = $sheet->getCell('A'.$i)->getValue() == "" ? 0 : $sheet->getCell('A'.$i)->getValue();
					$column_two = $sheet->getCell('B'.$i)->getValue() == "" ? 0 : $sheet->getCell('B'.$i)->getValue();
					$column_three = $sheet->getCell('C'.$i)->getValue() == "" ? 0 : $sheet->getCell('C'.$i)->getValue();
					$new_existencias[$i]=[
						"id_producto"			=>	$productos->codigo,
						"id_tienda"			=>	$id_tienda,
						"cajas"			=>	$column_one,
						"piezas"			=>	$column_two,
						"pedido"	=>	$column_three,
						"fecha_registro"	=>	$fecha->format('Y-m-d H:i:s')
					];
					if(!$exis){
						$data['cotizacion']=$this->ex_lun_md->insert($new_existencias[$i]);
					}
				}
			}

		} else {
			for ($i=1; $i<=$num_rows; $i++) {
				$productos = $this->prod_mdl->get("id_producto",['codigo'=> htmlspecialchars($this->getOldVal($sheet,$i,"D"), ENT_QUOTES, 'UTF-8')])[0];
				if (sizeof($productos) > 0) {
					$exis = $this->ex_mdl->get(NULL,["WEEKOFYEAR(fecha_registro)" => $this->weekNumber($fecha->format('Y-m-d H:i:s')),"id_tienda"=>$id_tienda,"id_producto"=>$productos->id_producto])[0];
					$column_one=0; $column_two=0; $column_three=0;
					$column_one = $this->getOldVal($sheet,$i,"A") == "" ? 0 : $this->getOldVal($sheet,$i,"A");
					$column_two = $this->getOldVal($sheet,$i,"B") == "" ? 0 : $this->getOldVal($sheet,$i,"B");
					$column_three = $this->getOldVal($sheet,$i,"C") == "" ? 0 : $this->getOldVal($sheet,$i,"C");
					$new_existencias[$i]=[
						"id_producto"		=>	$productos->id_producto,
						"id_tienda"			=>	$id_tienda,
						"cajas"				=>	$column_one,
						"piezas"			=>	$column_two,
						"pedido"			=>	$column_three,
						"fecha_registro"	=>	$fecha->format('Y-m-d H:i:s')
					];
					if(!$exis){
						$data['cotizacion']=$this->ex_mdl->insert($new_existencias[$i]);
					}
				}
			}
		}

		if (isset($new_existencias)) {
			$aprov = $this->usua_mdl->get(NULL, ['id_usuario'=>$id_tienda])[0];
			$cambios=[
					"id_usuario"		=>	$this->session->userdata('id_usuario'),
					"fecha_cambio"		=>	date("Y-m-d H:i:s"),
					"antes"				=>	"El usuario sube archivo pedidos de su tienda ".$aprov->nombre,
					"despues"			=>	"assets/uploads/pedidos/".$filen.".xlsx",
					"accion"			=>	"Sube existencias y pedidos ".$filename
				];
			$data['cambios']=$this->cambio_md->insert($cambios);
			$mensaje=[	"id"	=>	'Éxito',
						"desc"	=>	'Existencias y pedidos cargados correctamente en el Sistema',
						"type"	=>	'success'];
		}else{
			$mensaje=[	"id"	=>	'Error',
						"desc"	=>	'Existencias y pedidos no se cargaron al Sistema',
						"type"	=>	'error'];
		}
		$this->jsonResponse($mensaje);
	}

}