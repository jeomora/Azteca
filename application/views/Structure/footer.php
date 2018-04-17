<style type="text/css">
	.spinns {
	    position: absolute;
	    top: 25rem;
	    left: 45rem;
	    background-color: rgba(255,255,255,0.5);
	    padding: 10rem;
	    color: #FF6805;
	    border: 2px solid #FF6805;
	    border-radius: 5px;
	    display: none;
	}
	.modal-content.animated.flipInY {
    background-color: rgb(255, 104, 5);
    color: #FFF;
}
.modal-body {
    color: #000;
}
</style>		
				<!-- Page Footer-->
						<div class="footer" style="color: white">
							<a href="http://jeomora.com" style="color:white" target="_blank"><div class="pull-right">
								<strong>AUTOR: Jeovany Mora Vieyra</strong>
							</div></a>
							<div>
								<strong>ABARROTES AZTECA AUTOSERVICIOS S.A DE CV &copy; 2017-2018 </strong> 
							</div>
						</div>

					</div>
				</div>
			</div>
		</div>


		<!-- Mainly scripts -->
		<script type="text/javascript" src="<?php echo base_url('/assets/js/jquery-2.1.1.js') ?>"></script>
		<script type="text/javascript" src="<?php echo base_url('/assets/js/bootstrap.min.js') ?>"></script>
		<script type="text/javascript" src="<?php echo base_url('/assets/js/plugins/metisMenu/jquery.metisMenu.js') ?>"></script>
		<script type="text/javascript" src="<?php echo base_url('/assets/js/plugins/slimscroll/jquery.slimscroll.min.js') ?>"></script>
		<script type="text/javascript" src="<?php echo base_url('assets/js/jquery.blockUI.js'); ?>"></script>
		
		<!-- Custom and plugin javascript -->
		<script type="text/javascript" src="<?php echo base_url('/assets/js/inspinia.js') ?>"></script>
		<script type="text/javascript" src="<?php echo base_url('/assets/js/plugins/pace/pace.min.js') ?>"></script>
		<!-- Chosen-select -->
		<script type="text/javascript" src="<?php echo base_url('/assets/js/plugins/chosen/chosen.jquery.js') ?>"></script>
		<!-- Toastr -->
		<script type="text/javascript" src="<?php echo base_url('/assets/js/plugins/toastr/toastr.min.js') ?>"></script>
		<!-- Formato de numeros -->
		<script type="text/javascript" src="<?php echo base_url('/assets/js/jquery.number.min.js') ?>"></script>
		<!-- Input Mask -->
		<script type="text/javascript" src="<?php echo base_url('/assets/js/jquery.inputmask.bundle.min.js') ?>"></script>
		<?php if (isset($scripts) && $scripts): ?>
			<?php foreach ($scripts as $row): ?>
			<script type="text/javascript" src="<?php echo base_url($row.'.js') ?>"></script>
			<?php endforeach ?>
		<?php endif ?>
		<!-- Data Picker -->
		<script type="text/javascript" src="<?php echo base_url('/assets/js/plugins/datapicker/bootstrap-datepicker.js') ?>"></script>
		<script type="text/javascript" src="<?php echo base_url('/assets/js/plugins/datapicker/bootstrap-datepicker.es.js') ?>"></script>
		<script type="text/javascript" src="<?php echo base_url('/assets/js/plugins/colorpicker/bootstrap-colorpicker.min.js') ?>"></script>
</body>
</html>
<div class="spinns"><i class="fa fa-spinner fa-spin fa-3x fa-fw"></i><span style="font-size:3rem;">Cargando...</span></div>
<div class="modal inmodal" id="mainModal" tabindex="-1" role="dialog" aria-hidden="true">
	<div class="modal-dialog modal-lg">
		<div class="modal-content animated flipInY">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal">
					<i class="fa fa-close"></i>
				</button>
				<h4 class="modal-title">TÍTULO</h4>
			</div>
			<div class="modal-body">
				
			</div>
			<div class="modal-footer">
				
			</div>
		</div>
	</div>
</div>