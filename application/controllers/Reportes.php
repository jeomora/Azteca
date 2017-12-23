<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Reportes extends MY_Controller {

	function __construct(){
		parent::__construct();
		$this->load->model("Promociones_model", "prom_mdl");
		$this->load->model("Productos_model", "prod_mdl");
		$this->load->model("Proveedores_model", "pro_mdl");
	}

	public function precios_bajos(){
		ini_get("memory_limit");
		ini_set("memory_limit","512M");
		ini_get("memory_limit");
		$data["preciosBajos"] = $this->prom_mdl->preciosBajos();
		$this->load->view("Reportes/table_precios_bajos", $data, FALSE);
	}

	public function precios_iguales(){
		$data["promociones_igual"] = $this->prom_mdl->getPromociones();
		$this->load->view("Reportes/table_precios_iguales", $data, FALSE);
	}


	public function fillExcel(){
		$promociones = $this->prom_mdl->getPromociones();

		$rows ='';
		echo "<table>
				<thead>
					<tr>
						<th>header</th>
					</tr>
				</thead>
				<tbody>
					<tr>
						<td>data</td>
					</tr>
				</tbody>
			</table>";
			
		$filename = 'Ejemplo_tabla.xlsx';
		header("Content-Type: application/vnd.ms-excel");
		header("Content-Disposition: attachment; filename=".$filename);
		header("Pragma: no-cache");
		header("Expires: 0");
	}


}

/* End of file Reportes.php */
/* Location: ./application/controllers/Reportes.php */