
				<!-- Page Footer-->
						<div class="footer">
							<div class="pull-right">
								<strong>AUTOR: </strong>
							</div>
							<div>
								<strong>ABARROTES AZTECA AUTOSERVICIOS S.A DE CV &copy; 2017-2018 </strong> 
							</div>
						</div>

					</div>
				</div>
			</div>
		</div>


		<!-- Mainly scripts -->
		<script src="<?php echo base_url('/assets/js/jquery-2.1.1.js') ?>"></script>
		<script src="<?php echo base_url('/assets/js/bootstrap.min.js') ?>"></script>
		<script src="<?php echo base_url('/assets/js/plugins/metisMenu/jquery.metisMenu.js') ?>"></script>
		<script src="<?php echo base_url('/assets/js/plugins/slimscroll/jquery.slimscroll.min.js') ?>"></script>
		<!-- Custom and plugin javascript -->
		<script src="<?php echo base_url('/assets/js/inspinia.js') ?>"></script>
		<script src="<?php echo base_url('/assets/js/plugins/pace/pace.min.js') ?>"></script>
		<!-- Chosen-select -->
		<script src="<?php echo base_url('/assets/js/plugins/chosen/chosen.jquery.js') ?>"></script>
		<!-- Toastr -->
		<script src="<?php echo base_url('/assets/js/plugins/toastr/toastr.min.js') ?>"></script>
		<!-- Formato de numeros -->
		<script src="<?php echo base_url('/assets/js/jquery.number.min.js') ?>"></script>
		<!-- Input Mask -->
		<script src="<?php echo base_url('/assets/js/jquery.inputmask.bundle.min.js') ?>"></script>
		<?php if (isset($scripts) && $scripts): ?>
			<?php foreach ($scripts as $row): ?>
			<script type="text/javascript" src="<?php echo base_url($row.'.js') ?>"></script>
			<?php endforeach ?>
		<?php endif ?>
		<!-- Data Picker -->
		<script src="<?php echo base_url('/assets/js/plugins/datapicker/bootstrap-datepicker.js') ?>"></script>
		<script src="<?php echo base_url('/assets/js/plugins/datapicker/bootstrap-datepicker.es.js') ?>"></script>
		<script src="<?php echo base_url('/assets/js/plugins/colorpicker/bootstrap-colorpicker.min.js') ?>"></script>
</body>
</html>

<div class="modal inmodal" id="mainModal" tabindex="-1" role="dialog" aria-hidden="true">
	<div class="modal-dialog modal-lg">
		<div class="modal-content animated flipInY">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal">
					<i class="fa fa-times-circle-o"></i>
				</button>
				<h4 class="modal-title">TÍTULO</h4>
			</div>
			<div class="modal-body">
				
			</div>
			<div class="modal-footer">
				<button id="mybotton" class="btn btn-success" type="button">
					<span class="bold"><i class="fa fa-floppy-o"></i></span>
					&nbsp;Guardar
				</button>
			</div>
		</div>
	</div>
</div>