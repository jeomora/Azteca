
				<!-- Page Footer-->
						<div class="footer">
							<div class="pull-right">
								<strong>Autor: </strong>
							</div>
							<div>
								<strong>Abarrotes Azteca &copy; 2017-2018 </strong> 
							</div>
						</div>

					</div>
				</div>
			</div>
		</div>

		<!-- Javascript files-->
		<!-- <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.0/jquery.min.js"></script> -->
		<!-- Mainly scripts -->
		<script src="<?php echo base_url('/assets/js/jquery-2.1.1.js'); ?>"></script>
		<script src="<?php echo base_url('/assets/js/bootstrap.min.js'); ?>"></script>
		<script src="<?php echo base_url('/assets/js/plugins/metisMenu/jquery.metisMenu.js'); ?>"></script>
		<script src="<?php echo base_url('/assets/js/plugins/slimscroll/jquery.slimscroll.min.js'); ?>"></script>
		<!-- Flot -->
		<script src="<?php echo base_url('/assets/js/plugins/flot/jquery.flot.js'); ?>"></script>
		<script src="<?php echo base_url('/assets/js/plugins/flot/jquery.flot.tooltip.min.js'); ?>"></script>
		<script src="<?php echo base_url('/assets/js/plugins/flot/jquery.flot.spline.js'); ?>"></script>
		<script src="<?php echo base_url('/assets/js/plugins/flot/jquery.flot.resize.js'); ?>"></script>
		<script src="<?php echo base_url('/assets/js/plugins/flot/jquery.flot.pie.js'); ?>"></script>
		<!-- Peity -->
		<script src="<?php echo base_url('/assets/js/plugins/peity/jquery.peity.min.js'); ?>"></script>
		<script src="<?php echo base_url('/assets/js/demo/peity-demo.js'); ?>"></script>
		<!-- Custom and plugin javascript -->
		<script src="<?php echo base_url('/assets/js/inspinia.js'); ?>"></script>
		<script src="<?php echo base_url('/assets/js/plugins/pace/pace.min.js'); ?>"></script>
		<!-- jQuery UI -->
		<script src="<?php echo base_url('/assets/js/plugins/jquery-ui/jquery-ui.min.js'); ?>"></script>
		<!-- GITTER -->
		<script src="<?php echo base_url('/assets/js/plugins/gritter/jquery.gritter.min.js'); ?>"></script>
		<!-- Sparkline -->
		<script src="<?php echo base_url('/assets/js/plugins/sparkline/jquery.sparkline.min.js'); ?>"></script>
		<!-- Sparkline demo data  -->
		<script src="<?php echo base_url('/assets/js/demo/sparkline-demo.js'); ?>"></script>
		<!-- ChartJS-->
		<script src="<?php echo base_url('/assets/js/plugins/chartJs/Chart.min.js'); ?>"></script>
		<!-- Toastr -->
		<script src="<?php echo base_url('/assets/js/plugins/toastr/toastr.min.js'); ?>"></script>

		<?php if (isset($scripts) && $scripts): ?><!-- Para cargar scripts -->
			<?php foreach ($scripts as $rows): ?>
				<script type="text/javascript" src="<?php echo base_url($rows.'.js') ?>"></script>
			<?php endforeach ?>
		<?php endif ?>

</body>
</html>
