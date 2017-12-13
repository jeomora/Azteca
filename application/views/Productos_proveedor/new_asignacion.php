<div class="ibox-content">
	<div class="row">
		<?php echo form_open("", array("id"=>'form_asignacion_new')); ?>
		
		<p style="font-size: 15px; margin-left: 10px;"> PROVEEDOR:
			<?php echo strtoupper($usuario->first_name.'	'.$usuario->last_name) ?>
		</p>
		<input type="hidden" name="id_proveedor" value="<?php echo $usuario->id ?>">
		
		<hr>

		<table class="table table-bordered">
			<thead>
				<tr>
					<th>#</th>
					<th>NOMBRE</th>
					<th>PRECIO</th>
				</tr>
			</thead>
			<tbody>
				<?php if ($productos): ?>
					<?php foreach ($productos as $key => $value): ?>
						<tr>
							<td><input type="checkbox" class="id_producto"  value="<?php echo $value->id_producto ?>"></td>
							<td><?php echo $value->nombre ?></td>
							<td>
								<div class="input-group m-b">
									<span class="input-group-addon"><i class="fa fa-dollar"></i></span>
									<input type="text" class="form-control precio" value="" readonly="" placeholder="0.00">
								</div>
							</td>
						</tr>
					<?php endforeach ?>
				<?php endif ?>
			</tbody>
		</table>
		
		<input type="hidden" id="name_user" value="<?php echo strtoupper($usuario->username) ?>"/>
	
		<?php echo form_close(); ?>
	</div>
</div>

<script type="text/javascript">
	$(".precio").inputmask("currency", {radixPoint: ".", prefix: ""});

	$(document).off("change", ".id_producto").on("change", ".id_producto", function() {
		var marcados =0;
		if($(this).is(":checked")) {
			marcados = marcados + 1;
			$(this).closest("tr").find(".precio ").removeAttr('readonly');
			$(this).closest("tr").find(".id_producto ").attr('name', 'id_producto[]');
			$(this).closest("tr").find(".precio ").attr('name', 'precio[]');
		}else{
			$(this).removeAttr("checked");
			marcados = marcados - 1;
			$(this).closest("tr").find(".precio ").attr('readonly', 'readonly');
			$(this).closest("tr").find(".precio .id_producto").removeAttr('name').val('');
			$(this).closest("tr").find(".id_producto ").removeAttr('name');
		}
	});

	$(document).off("click", ".save").on("click", ".save", function (argument) {
		if(marcados > 0){
			if(validar() == true){
				save($("#form_asignacion_new").serializeArray())
					.done(function (res) {
						if(res.type == 'error') {
							toastr.error(res.desc, $("#name_user").val());
						}else{
							toastr.success(res.desc, $("#name_user").val());
							$("#main_container").load(site_url+"Productos_proveedor/productos_proveedor_view");
							cleanModal();
							$("#myModal").modal("hide");
						}
					})
					.fail(function(res) {
						console.log("Error en la respuesta");
					});
				}
			}else{
				toastr.warning("Marque 1 producto como mínimo", $("#name_user").val());
			}
	});

	function validar(){
		var ichecks = $(".id_producto");
		var input = "";
		var errors = 0;
		$.each(ichecks, function(){
			if($(this).is(":checked")){
				input = $(this).closest("tr").find(".precio");
				if(!input.val()){
					errors++;
					return false;
				}
			}
		});
		if(errors > 0 ){
			toastr.warning("Faltan algunos precios por llenar", $("#name_user").val());
			return false;
		}
		return true;
	}

	function save(argument) {
		return $.ajax({
			url: site_url+'Productos_proveedor/save_asignados',
			type: 'POST',
			async:false,
			data: argument
		});
	}

</script>