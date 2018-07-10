<?php
	defined('BASEPATH') OR exit('No direct script access allowed');
?>

<style>
	.navbar-header{display: none !important}
	nav.navbar.navbar-static-top {
    display: none;
}.footer {
    display: none;
}
.btn-info, .btn-info:hover{
    background-color: #3c763d;
    border-color: #3c763d;
    color: #FFFFFF;
}
</style>

<div class="col-md-12" style="padding: 5rem">
	<div class="col-md-2">
		<div class="col-md-12" style="text-align:center;font-size: 16px;">
			Subir archivo XML
		</div>
		<?php echo form_open("Contadores/subirxml", array("id" => 'reporte_xml', "target" => '_blank')); ?>
			<div class="col-sm-4">
				<input class="btn btn-info" type="file" id="file_xml" name="file_xml" value="" size="20" />
			</div>
		<?php echo form_close(); ?>
	</div>
</div>
<div class="col-md-12 respuesta" style="padding: 5rem"></div>