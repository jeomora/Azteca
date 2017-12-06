<?php
	defined('BASEPATH') OR exit('No direct script access allowed');
?>
<div class="wrapper wrapper-content animated fadeInRight">
	<div class="row">
		<div class="col-lg-12">
			<div class="ibox float-e-margins">
				<div class="ibox-title">
					<h5>REGISTRAR GRUPOS</h5>
				</div>
				<div class="ibox-content">
					<div class="row">
						<?php echo form_open("Auth/create_group");?>
						<div class="row">
							<div class="col-sm-4">
								<div class="form-group">
									<label for="group_name">Nombre del grupo</label>
									<input type="text" name="group_name" value="" required="" class="form-control" placeholder="Nombre">
								</div>
							</div>
							<div class="col-sm-8">
								<div class="form-group">
									<label for="description">Descripción del grupo</label>
									<textarea type="text" rows="5" name="description" class="form-control" placeholder="Descripción"></textarea>
								
								</div>
							</div>
						</div>
						<div class="row">
							<div class="col-sm-2 pull-right">
								<button class="btn btn-primary pull-right" type="submit">
									<span class="bold"><i class="fa fa-floppy-o"></i></span>
									&nbsp;Guardar
								</button>
							</div>
						</div>
						<?php echo form_close(); ?>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>

<div id="infoMessage"><?php echo $message;?></div>
