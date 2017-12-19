<div class="ibox-content">
	<div class="row">
		<?php echo form_open("", array("id"=>'form_pedido_new')); ?>
		<div class="row">
			<div class="col-sm-6">
				<div class="form-group">
					<label for="id_proveedor">Proveedores</label>
					<select name="id_proveedor" id="id_proveedor" class="form-control">
						<option value="">Seleccionar...</option>
						<?php if ($proveedores): ?>
							<?php foreach ($proveedores as $key => $value): ?>
								<option value="<?php echo $value->id ?>"><?php echo strtoupper($value->first_name.'	'.$value->last_name) ?></option>
							<?php endforeach ?>
						<?php endif ?>
					</select>
				</div>
			</div>
			<div class="col-sm-6">
				<div class="form-group">
					<label for="id_sucursal">Sucursales</label>
					<select name="id_sucursal" id="id_sucursal" class="form-control">
						<option value="">Seleccionar...</option>
						<?php if ($sucursales): ?>
							<?php foreach ($sucursales as $key => $value): ?>
								<option value="<?php echo $value->id_sucursal ?>"><?php echo strtoupper($value->nombre) ?></option>
							<?php endforeach ?>
						<?php endif ?>
					</select>
				</div>
			</div>
		</div>

		<div class="row">
			<div class="col-sm-12">
				<table class="table table-bordered">
					<thead>
						<tr>
							<th>NO</th>
							<th>NOMBRE</th>
							<th>PRECIO</th>
							<th>CANTIDAD</th>
							<th>IMPORTE</th>
						</tr>
					</thead>
					<tbody id="body_response">
						
					</tbody>
					<tfoot>
						<tr>
							<td colspan="3"></td>
							<th>Subtotal</th>
							<td>
								<div class="input-group m-b">
									<span class="input-group-addon"><i class="fa fa-dollar"></i></span>
									<input type="text" id="subtotal" value="" class="form-control numeric" placeholder="0.00" readonly="">
								</div>
							</td>
						</tr>
						<tr>
							<td colspan="3"></td>
							<th>IVA</th>
							<td>
								<div class="input-group m-b">
									<span class="input-group-addon"><i class="fa fa-dollar"></i></span>
									<input type="text" id="iva" value="" class="form-control numeric" placeholder="0.00" readonly="">
								</div>
							</td>
						</tr>
						<tr>
							<td colspan="3"></td>
							<th>Total</th>
							<td>
								<div class="input-group m-b">
									<span class="input-group-addon"><i class="fa fa-dollar"></i></span>
									<input type="text" id="total" name="total" value="" class="form-control numeric" placeholder="0.00" readonly="">
								</div>
							</td>
						</tr>
					</tfoot>
				</table>
			</div>
		</div>

		<input type="hidden" id="name_user" value="<?php echo strtoupper($usuario->username) ?>"/>

		<?php echo form_close(); ?>
	</div>
</div>

<script type="text/javascript">
	$("#form_pedido_new").validate({
		rules: {
			id_proveedor: {required: true},
			id_sucursal: {required: true}
		}
	});

	jQuery.extend(jQuery.validator.messages, {
		required: "Este campo es requerido",
		min: "Este campo es requerido"
	});

	$(document).off("click", ".save").on("click", ".save", function(event) {
		event.preventDefault();
		if($("#form_pedido_new").valid()){
			if(marcados > 0){
				if(validar() == true){
					savePedido($("#form_pedido_new").serializeArray())
					.done(function (res) {
						if(res.type == 'error') {
							toastr.error(res.desc, $("#name_user").val());
						}else{
							toastr.success(res.desc, $("#name_user").val());
							$("#main_container").load(site_url+"Pedidos/pedidos_view");
							cleanModal();
							$("#myModal").modal("hide");
						}
					})
					.fail(function(res) { });
				}
			}else{
				toastr.warning("Marque 1 producto como mínimo", $("#name_user").val());
			}
		}
	});

	$("#id_proveedor").change(function () {
		var rows = "";
		var proveedor = $("#id_proveedor option:selected").text();
		getProductos($(this).val())
			.done(function (response) {
				var size = response.length;
				$("#body_response").empty();
				if (jQuery.isEmptyObject(response)) {
					toastr.warning("El Proveedor "+proveedor+" no tiene Productos cotizados", $("#name_user").val());
				}else{
					$.each(response, function(index, val) {
						rows += "<tr>"
									+"<td> <input type='checkbox' value="+val.id_producto+" class='id_producto'> </td>"
									+"<td>"+val.producto+"</td>"
									+"<td>"
										+"<div class='input-group m-b'>"
											+"<span class='input-group-addon'><i class='fa fa-dollar'></i></span>"
											+"<input type='text' value="+formatNumber(parseFloat(val.precio), 2)+" class='form-control precio' readonly=''>"
										+"</div>"
									+"</td>"
									+"<td>"
										+"<div class='input-group m-b'>"
											+"<span class='input-group-addon'><i class='fa fa-slack'></i></span>"
											+"<input type='text' value='' class='form-control cantidad numeric'  readonly=''> "
										+"</div>"
									+"</td>"
									+"<td>"
										+"<div class='input-group m-b'>"
											+"<span class='input-group-addon'><i class='fa fa-dollar'></i></span>"
											+"<input type='text' value='' class='form-control importe numeric' readonly=''>"
										+"</div>"
									+"</td>"
								+"</tr>";
					});
					$("#body_response").append(rows);
					$(".numeric").inputmask("currency", {radixPoint: ".", prefix: ""});
					toastr.success("El Proveedor "+proveedor+" tiene "+size+" Productos cotizados", $("#name_user").val());
				}
			})
			.fail(function (response) {
				// body...
			});
	});

	function getProductos(id_prov) {
		return $.ajax({
			url: site_url+"/Pedidos/get_productos",
			type: "POST",
			dataType: "JSON",
			data: {id_proveedor: id_prov},
		});
	}

	var marcados =0;

	$(document).off("change", ".id_producto").on("change", ".id_producto", function() {
		var tr = $(this).closest("tr");
		if($(this).is(":checked")) {
			marcados = marcados + 1;
			tr.find(".cantidad").removeAttr('readonly');
			tr.find(".id_producto ").attr('name', 'id_producto[]');
			tr.find(".precio ").attr('name', 'precio[]');
			tr.find(".cantidad ").attr('name', 'cantidad[]');
			tr.find(".importe ").attr('name', 'importe[]');
		}else{
			$(this).removeAttr("checked");
			marcados = marcados - 1;
			tr.find(".cantidad").attr('readonly', 'readonly');
			tr.find(".cantidad").removeAttr('name').val('');
			tr.find(".precio").removeAttr('name').val('');
			tr.find(".importe").removeAttr('name').val('');
			tr.find(".id_producto ").removeAttr('name');
			$("#subtotal").val(calculaTotales());
			$("#iva").val((calculaTotales() * 0.16));
			$("#total").val((calculaTotales() * 1.16));
		}
	});

	$(document).off("keyup", ".cantidad").on("keyup", ".cantidad", function () {
		var tr = $(this).closest("tr");
		var precio = tr.find(".precio").val().replace(/[^0-9\.]+/g,"");
		var cantidad = tr.find(".cantidad").val().replace(/[^0-9\.]+/g,"");

		if($(this).val().replace(/[^0-9\.]+/g,"") > 0){
			tr.find(".importe").val(precio * cantidad);
			$("#subtotal").val(calculaTotales());
			$("#iva").val((calculaTotales() * 0.16));
			$("#total").val((calculaTotales() * 1.16));
		}
	});

	function calculaTotales() {
		var total =0;
		$.each($(".importe"), function(index, val) {
			if (Number($(this).val().replace(/[^0-9\.]+/g,"")) !== '') {
				total += Number($.trim($(val).val().replace(/[^0-9\.]+/g,"")));
			}
		});
		return total;
	}

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

	function savePedido(argument) {
		return $.ajax({
			url: site_url+'Pedidos/save_pedido',
			type: 'POST',
			async:false,
			data: argument
		});
	}

</script>