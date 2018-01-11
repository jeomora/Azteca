<div class="wrapper wrapper-content animated fadeInRight">
	<div class="row">
		<div class="col-lg-12">
			<div class="ibox float-e-margins">
				<div class="ibox-title">
					<h5>Importar creditos</h5>
				</div>

				<!-- Insert Pack -->
				<div class="ibox-content">
				<div class="row">
						<div class="col-sm-12">
							<a href="<?php echo base_url('assets/uploads/importacion/importacion_creditos.csv'); ?>" target="_blank" class="targetprint"><i class="fa fa-cloud-download"></i><span class="nav-label" download> Formato : Importacion créditos</span> </a>
						</div>
					</div>
					<br/>
					<br/>
					<div class="row">
						<?php echo form_open_multipart("", array('id' => 'upload_excel')); ?>
						<?php echo form_fieldset(); ?>
							<div class="col-sm-10">
								<input type="hidden" name="producto_id" value=""/>
								<input class="btn btn-info" type="file" id="file_pro" name="file_pro" value=""/>
								<input type="hidden" id="upload" name="upload" value="1"/>
								<input type="hidden" required class="form-control" name="id" >
							</div>
							<div class="col-sm-2">
								<button class="btn btn-info" type="button" id="cargar">
									<i class="fa fa-upload"> </i>
										<span class="bold">Cargar</span>
								</button>
							</div>
							<input type="hidden" id="name_user" value="<?php echo strtoupper($this->session->userdata("username")); ?>"/>
						<?php echo form_fieldset_close(); ?>
						<?php echo form_close(); ?>
					</div>
					<hr>
					<div class="row">
						<div class="col-md-12" id="printer">

						</div>
					</div>
				</div>
				<!-- End pack -->
			</div>
		</div>
	</div>
</div>

<script type="text/javascript">
	$("#cargar").unbind().click(function(event) {
		event.preventDefault();
		blockPage();
		send_datos_formdata("<?php echo site_url('Importacion_control/insert_batch_creditos') ?>", "upload_excel", function(response){
			unblockPage();
			if (response.type == 'error') {
				toastr.error(response.desc, $("#name_user").val());
			}else{
				toastr.success(response.desc, $("#name_user").val());
			}
		});
	});

</script>
