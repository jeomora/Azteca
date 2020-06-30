<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Catalogo extends MY_Controller {

	function __construct(){
		parent::__construct();
		$this->load->model("Veladoras_model", "vela_md");
		$this->load->model("Imagenes_model", "img_md");
		$this->load->library("form_validation");
	}

	public function index(){
		$data['scripts'] = [
			'/scripts/Veladoras/veladoras',
		];
		$this->estructura("Veladoras/veladoras", $data);
	}

	public function getProductos(){
		$count = $this->vela_md->getCount(NULL)[0];
		$data["meta"] = ["page"=>1,"pages"=>1,"perpage"=>-1,"total"=>(int)$count->total,"sort"=>"asc","field"=>"RecordID"];
		$query = $this->vela_md->getProductos(NULL);
		$data["data"]=[];
		foreach ($query as $key => $value) {
			$data["data"][$key] = [
				"id_usuario"	=>	intval($value->id_producto),
				"nombre"		=>	$value->codigo,
				"ShipName"		=>	$value->familia,
				"Imagen"		=>	$value->imagen,
				"Status"		=>	str_replace('	', '', str_replace('"', '', $value->nombre)),
				"Actions"		=>  ""
			];
		}
		
		$this->jsonResponse($data);
	}

	public function admin(){
		$data['scripts'] = [
			'/scripts/Veladoras/admin',
		];
		$this->estructura("Veladoras/admin", $data);
	}

	public function subirImg(){
		ini_set("memory_limit", -1);
		$filen = date("dmyHis");
		$config['upload_path']          = './assets/img/productos/';
        $config['allowed_types']        = 'jpg|jpeg|png|jfif';
        $config['max_size']             = 10000;
        $config['max_width']            = 10024;
        $config['max_height']           = 10024;
        $this->load->library('upload', $config);
        $this->upload->initialize($config);
        $this->upload->do_upload('file_otizaciones',$filen);
        $filen = $filen."_thumb";
		if ($this->upload->do_upload('file_otizaciones',$filen)){
			$uploadedImage = $this->upload->data();
        	$this->resizeImage($uploadedImage['file_name']);
		}
		$path_parts = pathinfo($_FILES["file_otizaciones"]["name"]);
		$extension = $path_parts['extension'];
		$this->jsonResponse($filen.".".$extension);

		/*ini_set("memory_limit", -1);
		$file = $_FILES["file_otizaciones"]["tmp_name"];
		$filename=$_FILES['file_otizaciones']['name'];
		$sheet = PHPExcel_IOFactory::load($file);
		$objExcel = PHPExcel_IOFactory::load($file);
		$sheet = $objExcel->getSheet(0);
		$num_rows = $sheet->getHighestDataRow();

		for ($i=1; $i<=$num_rows; $i++) {
			if($this->getOldVal($sheet,$i,'A') > 0){
				$productos = $this->vela_md->get("id_producto",['codigo'=> htmlspecialchars($this->getOldVal($sheet,$i,'A'), ENT_QUOTES, 'UTF-8')])[0];
				if(sizeof($productos) > 0) {
					$longs = strlen((string)$i);
					if ($longs === 1){
						$ima = "image00".$i.".png";
					}else($longs === 2){
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
		}*/
	}

	public function altaVela(){
		$values = $this->input->post("values");
		$busca = json_decode($values);
		$new_vela=[
					"imagen"	 	=>	$busca->imagen,
					"id_producto"	=>	$busca->id_producto
				];
		$resu = $this->img_md->get(NULL,["id_producto"=>$busca->id_producto])[0];
		if ($resu) {
			$facturas = $this->img_md->update($new_vela,["id_producto"=>$resu->id_image]);
		} else {
			$facturas = $this->img_md->insert($new_vela);
		}
		
		$this->jsonResponse($busca);
	}

	public function getProd($id_prods){
		$imagen = $this->vela_md->getAll(NULL,$id_prods)[0];
		$this->jsonResponse($imagen);
	}

	public function resizeImage($filename){
      $source_path = $_SERVER['DOCUMENT_ROOT'] . '/Abarrotes/assets/img/productos/' . $filename;
      $target_path = $_SERVER['DOCUMENT_ROOT'] . '/Abarrotes/assets/img/productos/';
      list($width, $height, $type, $attr) = getimagesize($source_path);
      if ($width > $height) {
      	$config_manip = array(
	          'image_library' => 'gd2',
	          'source_image' => $source_path,
	          'new_image' => $target_path,
	          'create_thumb' => TRUE,
	          'maintain_ratio' => TRUE,
	          'width' => 250,
	      );
      }else{
      	$config_manip = array(
	          'image_library' => 'gd2',
	          'source_image' => $source_path,
	          'new_image' => $target_path,
	          'create_thumb' => TRUE,
	          'maintain_ratio' => TRUE,
	          'height' => 250,
	      );
      }
      
   
      $this->load->library('image_lib', $config_manip);
      if (!$this->image_lib->resize()) {
          echo $this->image_lib->display_errors();
      }
   
      $this->image_lib->clear();
   }

   public function fill_excel(){
   		ini_set("memory_limit", "-1");
		ini_set("max_execution_time", "-1");
		$this->load->library("excelfile");
		//FECHA EN FORMATO COMPLETO PARA LOS TITULOS Y TABLAS
		$dias = array("DOMINGO","LUNES","MARTES","MIÉRCOLES","JUEVES","VIERNES","SÁBADO");
		$meses = array("ENERO","FEBRERO","MARZO","ABRIL","MAYO","JUNIO","JULIO","AGOSTO","SEPTIEMBRE","OCTUBRE","NOVIEMBRE","DICIEMBRE");
		$fecha =  $dias[date('w')]." ".date('d')." DE ".$meses[date('n')-1]. " DEL ".date('Y') ;
		$day = date('w');

		$hoja = $this->excelfile->setActiveSheetIndex(0);
		$this->excelfile->setActiveSheetIndex(0)->setTitle("Veladoras");
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
		$this->cellStyle("A".$rws.":D".$rws, "000000", "FFFFFF", TRUE, 12, "Franklin Gothic Bold");
		$this->excelfile->getActiveSheet()->getStyle("A".$rws.":D".$rws)->getAlignment()->setWrapText(true);
		$hoja->setCellValue("A".$rws, "CÓDIGO")->getColumnDimension('A')->setWidth(30);
		$hoja->setCellValue("B".$rws, "DESCRIPCIÓN")->getColumnDimension('B')->setWidth(30);
		$hoja->setCellValue("C".$rws, "DESCRIPCIÓN")->getColumnDimension('C')->setWidth(70);
		$hoja->setCellValue("D".$rws, "IMAGEN")->getColumnDimension('D')->setWidth(25);
		$this->excelfile->getActiveSheet()->getStyle('A'.$rws.":D".$rws)->applyFromArray($styleArray2);
		$rws++;

		$query = $this->vela_md->geVelas(NULL);
		foreach ($query as $key => $value) {
			$data["data"][$key] = [
				"id_usuario"	=>	intval($value->id_veladora),
				"nombre"		=>	$value->codpieza,
				"ShipName"		=>	$value->codcaja,
				"Status"		=>	$value->descripcion,
				"Actions"		=>  $value->img
			];
			$hoja->setCellValue('A'.$rws, $value->codpieza)->getStyle("A{$rws}")->getNumberFormat()->setFormatCode('# ???/???');
			$hoja->setCellValue('B'.$rws, $value->codcaja)->getStyle("B{$rws}")->getNumberFormat()->setFormatCode('# ???/???');
			$hoja->setCellValue('C'.$rws, $value->descripcion);
			$this->cellStyle("A".$rws.":D".$rws, "FFFFFF", "000000", FALSE, 12, "Franklin Gothic Book");
			$this->excelfile->getActiveSheet()->getStyle('A'.$rws.":D".$rws)->applyFromArray($styleArray2);

			//$gdImage = imagecreatefromjpeg(base_url("/assets/img/veladoras/".$value->img.""));
			$objDrawing = new PHPExcel_Worksheet_Drawing();
			$objDrawing->setName('COD'.$value->codpieza);
			$objDrawing->setDescription('DESC'.$value->codpieza);
			$objDrawing->setPath("http://abarrotesazteca.com/assets/img/abarrotes.png");
			$objDrawing->setWidth(100);
			$objDrawing->setHeight(100);
			$objDrawing->setCoordinates('D'.$rws);
			$objDrawing->setOffsetX(5); 
			$objDrawing->setOffsetY(5);
			$objDrawing->setWorksheet($this->excelfile->getActiveSheet());
			$this->excelfile->getActiveSheet()->getRowDimension($rws)->setRowHeight(125);
			$rws++;
		}

		$dias = array("DOMINGO","LUNES","MARTES","MIÉRCOLES","JUEVES","VIERNES","SÁBADO");
		$meses = array("ENERO","FEBRERO","MARZO","ABRIL","MAYO","JUNIO","JULIO","AGOSTO","SEPTIEMBRE","OCTUBRE","NOVIEMBRE","DICIEMBRE");
		$fecha =  $dias[date('w')]." ".date('d')." DE ".$meses[date('n')-1]. " DEL ".date('Y') ;
		$file_name = "CATALOGO VELADORAS AL".$fecha.".xlsx"; //Nombre del documento con extención
		header("Content-Type: application/vnd.ms-excel; charset=utf-8");
		header("Content-Disposition: attachment;filename=".$file_name."");
		header("Cache-Control: max-age=0");
		$excel_Writer = PHPExcel_IOFactory::createWriter($this->excelfile, "Excel2007");
		$excel_Writer->save("php://output");
   }

    public function fill_excel2(){
   		ini_set("memory_limit", "-1");
		ini_set("max_execution_time", "-1");
		$this->load->library("excelfile");
		//FECHA EN FORMATO COMPLETO PARA LOS TITULOS Y TABLAS
		$dias = array("DOMINGO","LUNES","MARTES","MIÉRCOLES","JUEVES","VIERNES","SÁBADO");
		$meses = array("ENERO","FEBRERO","MARZO","ABRIL","MAYO","JUNIO","JULIO","AGOSTO","SEPTIEMBRE","OCTUBRE","NOVIEMBRE","DICIEMBRE");
		$fecha =  $dias[date('w')]." ".date('d')." DE ".$meses[date('n')-1]. " DEL ".date('Y') ;
		$day = date('w');

		$hoja = $this->excelfile->setActiveSheetIndex(0);
		$this->excelfile->setActiveSheetIndex(0)->setTitle("Veladoras");
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
		$this->cellStyle("A".$rws.":C".$rws, "000000", "FFFFFF", TRUE, 12, "Franklin Gothic Bold");
		$this->excelfile->getActiveSheet()->getStyle("A".$rws.":C".$rws)->getAlignment()->setWrapText(true);
		$hoja->setCellValue("A".$rws, "CÓDIGO PIEZA")->getColumnDimension('A')->setWidth(30);
		$hoja->setCellValue("B".$rws, "CÓDIGO CAJA")->getColumnDimension('B')->setWidth(30);
		$hoja->setCellValue("C".$rws, "DESCRIPCIÓN")->getColumnDimension('C')->setWidth(45);
		$this->excelfile->getActiveSheet()->getStyle('A'.$rws.":C".$rws)->applyFromArray($styleArray2);
		$rws++;

		$query = $this->vela_md->geVelas(NULL);
		foreach ($query as $key => $value) {
			$data["data"][$key] = [
				"id_usuario"	=>	intval($value->id_veladora),
				"nombre"		=>	$value->codpieza,
				"ShipName"		=>	$value->codcaja,
				"Status"		=>	$value->descripcion,
				"Actions"		=>  $value->img
			];
			$hoja->setCellValue('A'.$rws, $value->codpieza)->getStyle("A{$rws}")->getNumberFormat()->setFormatCode('# ???/???');
			$hoja->setCellValue('B'.$rws, $value->codcaja)->getStyle("B{$rws}")->getNumberFormat()->setFormatCode('# ???/???');
			$hoja->setCellValue('C'.$rws, $value->descripcion);
			$this->cellStyle("A".$rws.":D".$rws, "FFFFFF", "000000", FALSE, 12, "Franklin Gothic Book");
			$this->excelfile->getActiveSheet()->getStyle('A'.$rws.":C".$rws)->applyFromArray($styleArray2);
			$rws++;
		}

		$dias = array("DOMINGO","LUNES","MARTES","MIÉRCOLES","JUEVES","VIERNES","SÁBADO");
		$meses = array("ENERO","FEBRERO","MARZO","ABRIL","MAYO","JUNIO","JULIO","AGOSTO","SEPTIEMBRE","OCTUBRE","NOVIEMBRE","DICIEMBRE");
		$fecha =  $dias[date('w')]." ".date('d')." DE ".$meses[date('n')-1]. " DEL ".date('Y') ;
		$file_name = "CATALOGO VELADORAS AL".$fecha.".xlsx"; //Nombre del documento con extención
		header("Content-Type: application/vnd.ms-excel; charset=utf-8");
		header("Content-Disposition: attachment;filename=".$file_name."");
		header("Cache-Control: max-age=0");
		$excel_Writer = PHPExcel_IOFactory::createWriter($this->excelfile, "Excel2007");
		$excel_Writer->save("php://output");
   }
}