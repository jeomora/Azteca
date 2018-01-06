<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Reportes extends MY_Controller {

	function __construct(){
		parent::__construct();
		$this->load->model("Promociones_model", "prom_mdl");
		$this->load->model("Productos_model", "prod_mdl");
		$this->load->model("Proveedores_model", "pro_mdl");
		$this->load->model("Productos_proveedor_model", "prod_prov_mdl");
	}

	public function index(){
		ini_get("memory_limit");
		ini_set("memory_limit","512M");
		ini_get("memory_limit");
		$data['links'] = [
			'/assets/css/plugins/dataTables/dataTables.bootstrap',
			'/assets/css/plugins/dataTables/dataTables.responsive',
			'/assets/css/plugins/dataTables/dataTables.tableTools.min',
			'/assets/css/plugins/dataTables/buttons.dataTables.min',
		];

		$data['scripts'] = [
			'/scripts/reportes',
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
		$data["preciosBajos"] = $this->prod_prov_mdl->preciosBajosProveedor();
		$this->estructura("Reportes/table_precios_bajos", $data);

	}

	public function precios_iguales(){
		$data['links'] = [
			'/assets/css/plugins/dataTables/dataTables.bootstrap',
			'/assets/css/plugins/dataTables/dataTables.responsive',
			'/assets/css/plugins/dataTables/dataTables.tableTools.min',
			'/assets/css/plugins/dataTables/buttons.dataTables.min',
		];

		$data['scripts'] = [
			'/scripts/reportes',
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
		$data["promociones_igual"] = $this->prom_mdl->getPromociones();
		$this->estructura("Reportes/table_precios_iguales", $data);
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