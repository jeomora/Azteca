<?php
if(!$this->session->userdata("username") || $this->session->userdata("id_grupo") == 2){
	redirect("Compras/Login", "");
}
?>
<style>
	.kt-timeline-v2 .kt-timeline-v2__items .kt-timeline-v2__item .kt-timeline-v2__item-time{font-size:1rem;}
</style>
<!-- begin:: Content -->
<div class="kt-content kt-grid__item kt-grid__item--fluid">

	<!--BEGIN:: ACTIVIDAD RECIENTE - EXISTENCIAS TIENDAS PERFUMERÍA Y FARMACIA -->

	<div class="row">
		<div class="col-xl-6">

			<!--Begin::Portlet-->
			<div class="kt-portlet kt-portlet--height-fluid">
				<div class="kt-portlet__head">
					<div class="kt-portlet__head-label">
						<h3 class="kt-portlet__head-title">
							Actividad Reciente
						</h3>
					</div>
					<div class="kt-portlet__head-toolbar">
						<div class="dropdown dropdown-inline">
							<button type="button" class="btn btn-clean btn-sm btn-icon btn-icon-md" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
								<i class="flaticon-more-1"></i>
							</button>
							<div class="dropdown-menu dropdown-menu-right dropdown-menu-fit dropdown-menu-md">

								<!--begin::Nav-->
								<ul class="kt-nav">
									<li class="kt-nav__head">3
										Información
										<i class="flaticon2-information" data-toggle="kt-tooltip" data-placement="right"></i>
									</li>
									<li class="kt-nav__separator"></li>
									<li class="kt-nav__item">
										<a class="kt-nav__link kt-timeline-v2__item-cricle">
											<div class="kt-timeline-v2__item-cricle kt-font-success"><i class="fa fa-genderless"></i></div>
											<span class="kt-nav__link-text"> Proveedores</span>
										</a>
									</li>
									<li class="kt-nav__item">
										<a class="kt-nav__link kt-timeline-v2__item-cricle kt-font-danger">
											<div class="kt-timeline-v2__item-cricle kt-font-danger"><i class="fa fa-genderless"></i></div>
											<span class="kt-nav__link-text"> Personal</span>
										</a>
									</li>
									<li class="kt-nav__item">
										<a class="kt-nav__link kt-timeline-v2__item-cricle">
											<div class="kt-timeline-v2__item-cricle kt-font-brand"><i class="fa fa-genderless"></i></div>
											<span class="kt-nav__link-text"> Sucursales</span>
										</a>
									</li>
									<li class="kt-nav__separator"></li>
									<li class="kt-nav__foot">
										<a class="btn btn-clean btn-bold btn-sm" href="#" data-toggle="kt-tooltip" data-placement="right">Ver más</a>
									</li>
								</ul>

								<!--end::Nav-->
							</div>
						</div>
					</div>
				</div>
				<div class="kt-portlet__body">

					<!--Begin::Timeline 3 -->
					<div class="kt-timeline-v2">
						<div class="kt-timeline-v2__items  kt-padding-top-25 kt-padding-bottom-30">
							
						</div>
					</div>

					<!--End::Timeline 3 -->
				</div>
			</div>

			<!--End::Portlet-->
		</div>
		<div class="col-xl-6">

			<!--begin:: Widgets/Sale Reports-->
			<div class="kt-portlet kt-portlet--tabs kt-portlet--height-fluid">
				<div class="kt-portlet__head">
					<div class="kt-portlet__head-label">
						<h3 class="kt-portlet__head-title">
							Formatos Existencias
						</h3>
					</div>
					<div class="kt-portlet__head-toolbar">
						<ul class="nav nav-tabs nav-tabs-line nav-tabs-bold nav-tabs-line-brand" role="tablist">
							<li class="nav-item">
								<a class="nav-link active" data-toggle="tab" href="#kt_widget11_tab1_content" role="tab">
									Perfumería/Farmacia
								</a>
							</li>
						</ul>
					</div>
				</div>
				<div class="kt-portlet__body">

					<!--Begin::Tab Content-->
					<div class="tab-content">

						<!--begin::tab 1 content-->
						<div class="tab-pane active" id="kt_widget11_tab1_content">

							<!--begin::Widget 11-->
							<div class="kt-widget11">
								<div class="table-responsive">
									<table class="table">
										<thead>
											<tr>
												<td style="width:40%">Sucursal</td>
												<td style="width:14%">Perfumería</td>
												<td style="width:15%">Farmacia</td>
											</tr>
										</thead>
										<tbody>
											<tr style="background: linear-gradient(to right, white 87%, #63FFFB99);">
												<td>
													<a class="kt-widget11__title">Cedis</a>
													<span class="kt-widget11__sub">Azteca Cedis</span>
												</td>
												<td id="gen0" class="kt-font-brand kt-font-bold"></td>
												<td id="far0" class="kt-font-success kt-font-bold"></td>
											</tr>
											<tr style="background: linear-gradient(to right, white 87%, #CC009999);">
												<td>
													<a class="kt-widget11__title">Super</a>
													<span class="kt-widget11__sub">Super Cd. Industrial</span>
												</td>
												<td id="gen1" class="kt-font-brand kt-font-bold"></td>
												<td id="far1" class="kt-font-success kt-font-bold"></td>
											</tr>
											<tr style="background: linear-gradient(to right, white 87%, #00B0F099);">
												<td>
													<a class="kt-widget11__title">Abarrotes</a>
													<span class="kt-widget11__sub">Solidaridad</span>
												</td>
												<td id="gen2" class="kt-font-brand kt-font-bold"></td>
												<td id="far2" class="kt-font-success kt-font-bold"></td>
											</tr>
											<tr style="background: linear-gradient(to right, white 87%, #FF000099);">
												<td>
													<a class="kt-widget11__title">Villas</a>
													<span class="kt-widget11__sub">Villas del Pedregal</span>
												</td>
												<td id="gen3" class="kt-font-brand kt-font-bold"></td>
												<td id="far3" class="kt-font-success kt-font-bold"></td>
											</tr>
											<tr style="background: linear-gradient(to right, white 87%, #E26B0A99);">
												<td>
													<a class="kt-widget11__title">Tienda</a>
													<span class="kt-widget11__sub">San Juanito</span>
												</td>
												<td id="gen4" class="kt-font-brand kt-font-bold"></td>
												<td id="far4" class="kt-font-success kt-font-bold"></td>
											</tr>
											<tr style="background: linear-gradient(to right, white 87%, #C5C5C599);">
												<td>
													<a class="kt-widget11__title">Ultramarinos</a>
													<span class="kt-widget11__sub">Ultramarinos Azteca</span>
												</td>
												<td id="gen5" class="kt-font-brand kt-font-bold"></td>
												<td id="far5" class="kt-font-success kt-font-bold"></td>
											</tr>
											<tr style="background: linear-gradient(to right, white 87%, #92D05099);">
												<td>
													<a class="kt-widget11__title">Trincheras</a>
													<span class="kt-widget11__sub">Trincheras Azteca</span>
												</td>
												<td id="gen6" class="kt-font-brand kt-font-bold"></td>
												<td id="far6" class="kt-font-success kt-font-bold"></td>
											</tr>
											<tr style="background: linear-gradient(to right, white 87%, #B1A0C799);">
												<td>
													<a class="kt-widget11__title">Mercado</a>
													<span class="kt-widget11__sub">Obrera</span>
												</td>
												<td id="gen7" class="kt-font-brand kt-font-bold"></td>
												<td id="far7" class="kt-font-success kt-font-bold"></td>
											</tr>
											<tr style="background: linear-gradient(to right, white 87%, #DA969499);">
												<td>
													<a class="kt-widget11__title">Tenencia</a>
													<span class="kt-widget11__sub">Azteca Tenencia</span>
												</td>
												<td id="gen8" class="kt-font-brand kt-font-bold"></td>
												<td id="far8" class="kt-font-success kt-font-bold"></td>
											</tr>
											<tr style="background: linear-gradient(to right, white 87%, #4BACC699);">
												<td>
													<a class="kt-widget11__title">Tijeras</a>
													<span class="kt-widget11__sub">Azteca Tijeras</span>
												</td>
												<td id="gen9" class="kt-font-brand kt-font-bold"></td>
												<td id="far9" class="kt-font-success kt-font-bold"></td>
											</tr>

										</tbody>
									</table>
								</div>
								<div class="kt-widget11__action kt-align-right">
									<!--<button type="button" class="btn btn-label-brand btn-bold btn-sm">Import Report</button>-->
								</div>
							</div>

							<!--end::Widget 11-->
						</div>

						<!--end::tab 1 content-->

					</div>

					<!--End::Tab Content-->
				</div>
			</div>

			<!--end:: Widgets/Sale Reports-->
		</div>
	</div>

	<!--End::Section-->


	<!--BEGIN:: PERFUMERÍA PRODUCTOS Y PROVEEDORES SIN COTIZAR-->
	<div class="row">
		<div class="col-xl-6">

			<!--begin:: Widgets/Application Sales-->
			<div class="kt-portlet kt-portlet--height-fluid">
				<div class="kt-portlet__head">
					<div class="kt-portlet__head-label">
						<h3 class="kt-portlet__head-title">
							<span style="color:#1dc9b7">PERFUMERÍA: </span> Última Cotización Proveedores
						</h3>
					</div>
					<div class="kt-portlet__head-toolbar">
						
					</div>
				</div>
				<div class="kt-portlet__body">
					<div class="tab-content">
						<div class="tab-pane active" id="kt_widget11_tab1_content">

							<!--begin::Widget 11-->
							<div class="kt-widget11">
								<div class="table-responsive">
									<table class="table">
										<thead>
											<tr>
												<td style=" width: 30%;">Proveedor</td>
												<td style=" width: 40%;">Última Cotización</td>
											</tr>
										</thead>
										<tbody>
											<?php if($cotizados):foreach ($cotizados as $key => $value): ?>
												<?php ?>
												<tr>
													<td>
														<span class="kt-widget11__title"><?php echo $value->nombre ?></span>
														<span class="kt-widget11__sub"></span>
													</td>
													<?php if($value->fecha <> NULL): ?> <td class="kt-align-right">
														<?php echo $dias[date('w',strtotime($value->fecha))]." ".date('d',strtotime($value->fecha))." DE ".$meses[date('n',strtotime($value->fecha))-1]." ".date('H:i:s', strtotime($value->fecha)) ?><br>
														<?php $now=time();$datediff=$now-strtotime($value->fecha);echo "(".number_format((round($datediff / (60 * 60 * 24)) / 7),0,".",",")." semanas)"; ?></td>
													<?php else: ?>
														<td class="kt-align-right">MAS DE 20 SEMANAS</td>
													<?php endif; ?> 
													
												</tr>
											<?php endforeach;endif;?>
											
										</tbody>
									</table>
								</div>
								<div class="kt-widget11__action kt-align-right">
									<button type="button" class="btn btn-label-danger btn-sm btn-bold" data-toggle='modal' data-target='#kt_modal_2'>Ver todos</button>
								</div>
							</div>

							<!--end::Widget 11-->
						</div>
					</div>
				</div>
			</div>

			<!--end:: Widgets/Application Sales-->
		</div>

		<div class="col-xl-6">

			<!--begin:: Widgets/Latest Updates-->
			<div class="kt-portlet kt-portlet--height-fluid ">
				<div class="kt-portlet__head">
					<div class="kt-portlet__head-label">
						<h3 class="kt-portlet__head-title">
							<span style="color:#1dc9b7">PERFUMERÍA: </span> Productos Sin Cotizar
						</h3>
					</div>
				</div>

				<!--full height portlet body-->
				<div class="kt-portlet__body kt-portlet__body--fluid kt-portlet__body--fit">
					<div class="kt-widget4 kt-widget4--sticky">
						<div class="kt-widget4__items kt-portlet__space-x kt-margin-t-15">
							<?php if($cotizad2):foreach ($cotizad2 as $key => $value): ?>
								<div class="kt-widget4__item">
									<span class="kt-widget4__icon">
										<i class="flaticon2-open-box  kt-font-brand"></i>
									</span>
									<a href="#" class="kt-widget4__title">
										<?php echo $value->producto ?> <br>
										<span class="kt-widget11__sub"><?php echo $value->codigo ?></span>
									</a>
									<?php if($value->fecha <> NULL): ?> 
										<span class="kt-align-right"><?php echo $dias[date('w',strtotime($value->fecha))]." ".date('d',strtotime($value->fecha))." DE ".$meses[date('n',strtotime($value->fecha))-1]." ".date('H:i:s', strtotime($value->fecha)) ?><br>
										<?php $now=time();$datediff=$now-strtotime($value->fecha);echo "(".number_format((round($datediff / (60 * 60 * 24)) / 7),0,".",",")." semanas)"; ?></span>
									<?php else: ?>
										<span class="kt-align-right">MAS DE 20 SEMANAS </span>
									<?php endif; ?> 
								</div>
							<?php endforeach;endif ?>
							<!--<div class="kt-widget4__item">
								<span class="kt-widget4__icon">
									<i class="flaticon2-analytics-2  kt-font-success"></i>
								</span>
								<a href="#" class="kt-widget4__title">
									Green Maker Team
								</a>
								<span class="kt-widget4__number kt-font-success">-64</span>
							</div>-->
							
						</div>
						<div class="kt-widget11__action kt-align-right">
							<button type="button" class="btn btn-label-success btn-sm btn-bold"  data-toggle='modal' data-target='#kt_modal_4'>Ver todos</button>
						</div>
					</div>
				</div>
			</div>

			<!--end:: Widgets/Latest Updates-->
		</div>
	</div>
	<!--End::Section-->

	<!--BEGIN:: FARMACIA PRODUCTOS Y PROVEEDORES SIN COTIZAR-->
	<div class="row">
		<div class="col-xl-6">

			<!--begin:: Widgets/Application Sales-->
			<div class="kt-portlet kt-portlet--height-fluid">
				<div class="kt-portlet__head">
					<div class="kt-portlet__head-label">
						<h3 class="kt-portlet__head-title">
							<span style="color:#1dc9b7">FAMRACIA: </span> Última Cotización Proveedores
						</h3>
					</div>
					<div class="kt-portlet__head-toolbar">
						
					</div>
				</div>
				<div class="kt-portlet__body">
					<div class="tab-content">
						<div class="tab-pane active" id="kt_widget11_tab1_content">

							<!--begin::Widget 11-->
							<div class="kt-widget11">
								<div class="table-responsive">
									<table class="table">
										<thead>
											<tr>
												<td style=" width: 30%;">Proveedor</td>
												<td style=" width: 40%;">Última Cotización</td>
											</tr>
										</thead>
										<tbody>
											<?php if($cotizados2):foreach ($cotizados2 as $key => $value): ?>
												<?php ?>
												<tr>
													<td>
														<span class="kt-widget11__title"><?php echo $value->nombre ?></span>
														<span class="kt-widget11__sub"></span>
													</td>
													<?php if($value->fecha <> NULL): ?> <td class="kt-align-right">
														<?php echo $dias[date('w',strtotime($value->fecha))]." ".date('d',strtotime($value->fecha))." DE ".$meses[date('n',strtotime($value->fecha))-1]." ".date('H:i:s', strtotime($value->fecha)) ?><br>
														<?php $now=time();$datediff=$now-strtotime($value->fecha);echo "(".number_format((round($datediff / (60 * 60 * 24)) / 7),0,".",",")." semanas)"; ?></td>
													<?php else: ?>
														<td class="kt-align-right">MAS DE 20 SEMANAS</td>
													<?php endif; ?> 
													
												</tr>
											<?php endforeach;endif;?>
											
										</tbody>
									</table>
								</div>
								<div class="kt-widget11__action kt-align-right">
									<button type="button" class="btn btn-label-warning btn-sm btn-bold" data-toggle='modal' data-target='#kt_modal_3'>Ver todos</button>
								</div>
							</div>

							<!--end::Widget 11-->
						</div>
					</div>
				</div>
			</div>

			<!--end:: Widgets/Application Sales-->
		</div>
		<div class="col-xl-6">

			<!--begin:: Widgets/Latest Updates-->
			<div class="kt-portlet kt-portlet--height-fluid ">
				<div class="kt-portlet__head">
					<div class="kt-portlet__head-label">
						<h3 class="kt-portlet__head-title">
							<span style="color:#1dc9b7">FARMACIA: </span> Productos Sin Cotizar
						</h3>
					</div>
				</div>

				<!--full height portlet body-->
				<div class="kt-portlet__body kt-portlet__body--fluid kt-portlet__body--fit">
					<div class="kt-widget4 kt-widget4--sticky">
						<div class="kt-widget4__items kt-portlet__space-x kt-margin-t-15">
							<?php if($cotizad4):foreach ($cotizad4 as $key => $value): ?>
								<div class="kt-widget4__item">
									<span class="kt-widget4__icon">
										<i class="flaticon2-open-box  kt-font-brand"></i>
									</span>
									<a href="#" class="kt-widget4__title">
										<?php echo $value->producto ?> <br>
										<span class="kt-widget11__sub"><?php echo $value->codigo ?></span>
									</a>
									<?php if($value->fecha <> NULL): ?> 
										<span class="kt-align-right"><?php echo $dias[date('w',strtotime($value->fecha))]." ".date('d',strtotime($value->fecha))." DE ".$meses[date('n',strtotime($value->fecha))-1]." ".date('H:i:s', strtotime($value->fecha)) ?><br>
										<?php $now=time();$datediff=$now-strtotime($value->fecha);echo "(".number_format((round($datediff / (60 * 60 * 24)) / 7),0,".",",")." semanas)"; ?></span>
									<?php else: ?>
										<span class="kt-align-right">MAS DE 20 SEMANAS </span>
									<?php endif; ?> 
								</div>
							<?php endforeach;endif ?>
							
						</div>
						<div class="kt-widget11__action kt-align-right">
							<button type="button" class="btn btn-label-success btn-sm btn-bold"   data-toggle='modal' data-target='#kt_modal_5'>Ver todos</button>
						</div>
					</div>
				</div>
			</div>

			<!--end:: Widgets/Latest Updates-->
		</div>
	</div>

	<!--End::Section-->

	<!--begin::Modal EXISTENCIAS PERFUMERIA JQUERY-->
	<div class="modal fade" id="kt_modal_1" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
		<div class="modal-dialog" role="document">
			<div class="modal-content">
				<div class="modal-header">
					<h5 class="modal-title" id="exampleModalLabel">Productos Sin Existencias</h5>
					<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					</button>
				</div>
				<div class="modal-body">
					<table class="table table-striped table-bordered table-hover" id="exislunnot">
						<thead>
							<tr>
								<th>No</th>
								<th>CÓDIGO</th>
								<th>DESCRIPCIÓN</th>
							</tr>
						</thead>
						<tbody id="existenciasnot">
						</tbody>
					</table>
				</div>
				<div class="modal-footer">
					<button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
				</div>
			</div>
		</div>
	</div>

	<!--end::Modal-->

	<!--begin::Modal PROVEEDORES SIN COTIZAR PERFUMERÍA-->
	<div class="modal fade" id="kt_modal_2" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
		<div class="modal-dialog" role="document">
			<div class="modal-content">
				<div class="modal-header">
					<h5 class="modal-title" id="exampleModalLabel">última Cotización PERFUMERÍA</h5>
					<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					</button>
				</div>
				<div class="modal-body">
					<table class="table table-striped table-bordered table-hover" id="exislunnot">
						<thead>
							<tr>
								<th>Proveedor</th>
								<th>Fecha Cotización</th>
							</tr>
						</thead>
						<tbody id="lastCots">
							<?php if($lastCots):foreach ($lastCots as $key => $value): ?>
								<?php ?>
								<tr>
									<td>
										<span class="kt-widget11__title"><?php echo $value->nombre ?></span>
										<span class="kt-widget11__sub"></span>
									</td>
									<?php if($value->fecha <> NULL): ?> <td class="kt-align-right">
										<?php echo $dias[date('w',strtotime($value->fecha))]." ".date('d',strtotime($value->fecha))." DE ".$meses[date('n',strtotime($value->fecha))-1]." ".date('H:i:s', strtotime($value->fecha)) ?><br>
										<?php $now=time();$datediff=$now-strtotime($value->fecha);echo "(".number_format((round($datediff / (60 * 60 * 24)) / 7),0,".",",")." semanas)"; ?></td>
									<?php else: ?>
										<td class="kt-align-right">MAS DE 20 SEMANAS</td>
									<?php endif; ?> 
									
								</tr>
							<?php endforeach;endif;?>
						</tbody>
					</table>
				</div>
				<div class="modal-footer">
					<button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
				</div>
			</div>
		</div>
	</div>

	<!--end::Modal-->


	<!--begin::Modal PROVEEDORES SIN COTIZAR FARMACIA-->
	<div class="modal fade" id="kt_modal_3" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
		<div class="modal-dialog" role="document">
			<div class="modal-content">
				<div class="modal-header">
					<h5 class="modal-title" id="exampleModalLabel">última Cotización FARMACIA</h5>
					<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					</button>
				</div>
				<div class="modal-body">
					<table class="table table-striped table-bordered table-hover" id="exislunnot">
						<thead>
							<tr>
								<th>Proveedor</th>
								<th>Fecha Cotización</th>
							</tr>
						</thead>
						<tbody id="lastCots">
							<?php if($lastCots2):foreach ($lastCots2 as $key => $value): ?>
								<?php ?>
								<tr>
									<td>
										<span class="kt-widget11__title"><?php echo $value->nombre ?></span>
										<span class="kt-widget11__sub"></span>
									</td>
									<?php if($value->fecha <> NULL): ?> <td class="kt-align-right">
										<?php echo $dias[date('w',strtotime($value->fecha))]." ".date('d',strtotime($value->fecha))." DE ".$meses[date('n',strtotime($value->fecha))-1]." ".date('H:i:s', strtotime($value->fecha)) ?><br>
										<?php $now=time();$datediff=$now-strtotime($value->fecha);echo "(".number_format((round($datediff / (60 * 60 * 24)) / 7),0,".",",")." semanas)"; ?></td>
									<?php else: ?>
										<td class="kt-align-right">MAS DE 20 SEMANAS</td>
									<?php endif; ?> 
									
								</tr>
							<?php endforeach;endif;?>
						</tbody>
					</table>
				</div>
				<div class="modal-footer">
					<button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
				</div>
			</div>
		</div>
	</div>

	<!--end::Modal-->

	<!--begin::Modal Productos no cotizados PERFUMERÍA-->
	<div class="modal fade" id="kt_modal_4" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
		<div class="modal-dialog" role="document">
			<div class="modal-content">
				<div class="modal-header">
					<h5 class="modal-title" id="exampleModalLabel">PRODUCTOS SIN COTIZACIÓN</h5>
					<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					</button>
				</div>
				<div class="modal-body">
					<table class="table table-striped table-bordered table-hover" id="exislunnot">
						<thead>
							<tr>
								<th>CÓDIGO</th>
								<th>DESCRIPCIÓN</th>
								<th>ÚLTIMA FECHA</th>
							</tr>
						</thead>
						<tbody id="lastCots">
							<?php if($cotizad):foreach ($cotizad as $key => $value): ?>
								<?php ?>
								<tr>
									<td>
										<span class="kt-widget11__title"><?php echo $value->codigo ?></span>
									</td>
									<td>
										<span class="kt-widget11__title"><?php echo $value->producto ?></span>
									</td>
									<?php if($value->fecha <> NULL): ?> <td class="kt-align-right">
										<?php echo $dias[date('w',strtotime($value->fecha))]." ".date('d',strtotime($value->fecha))." DE ".$meses[date('n',strtotime($value->fecha))-1]." ".date('H:i:s', strtotime($value->fecha)) ?><br>
										<?php $now=time();$datediff=$now-strtotime($value->fecha);echo "(".number_format((round($datediff / (60 * 60 * 24)) / 7),0,".",",")." semanas)"; ?></td>
									<?php else: ?>
										<td class="kt-align-right">MAS DE 20 SEMANAS</td>
									<?php endif; ?> 
									
								</tr>
							<?php endforeach;endif;?>
						</tbody>
					</table>
				</div>
				<div class="modal-footer">
					<button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
				</div>
			</div>
		</div>
	</div>

	<!--end::Modal-->

	<!--begin::Modal Productos no cotizados FARMACIA-->
	<div class="modal fade" id="kt_modal_5" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
		<div class="modal-dialog" role="document">
			<div class="modal-content">
				<div class="modal-header">
					<h5 class="modal-title" id="exampleModalLabel">PRODUCTOS FARMACIA SIN COTIZAR</h5>
					<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					</button>
				</div>
				<div class="modal-body">
					<table class="table table-striped table-bordered table-hover" id="exislunnot">
						<thead>
							<tr>
								<th>CÓDIGO</th>
								<th>DESCRIPCIÓN</th>
								<th>ÚLTIMA FECHA</th>
							</tr>
						</thead>
						<tbody id="lastCots">
							<?php if($cotizad3):foreach ($cotizad3 as $key => $value): ?>
								<?php ?>
								<tr>
									<td>
										<span class="kt-widget11__title"><?php echo $value->codigo ?></span>
									</td>
									<td>
										<span class="kt-widget11__title"><?php echo $value->producto ?></span>
									</td>
									<?php if($value->fecha <> NULL): ?> <td class="kt-align-right">
										<?php echo $dias[date('w',strtotime($value->fecha))]." ".date('d',strtotime($value->fecha))." DE ".$meses[date('n',strtotime($value->fecha))-1]." ".date('H:i:s', strtotime($value->fecha)) ?><br>
										<?php $now=time();$datediff=$now-strtotime($value->fecha);echo "(".number_format((round($datediff / (60 * 60 * 24)) / 7),0,".",",")." semanas)"; ?></td>
									<?php else: ?>
										<td class="kt-align-right">MAS DE 20 SEMANAS</td>
									<?php endif; ?> 
									
								</tr>
							<?php endforeach;endif;?>
						</tbody>
					</table>
				</div>
				<div class="modal-footer">
					<button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
				</div>
			</div>
		</div>
	</div>

	<!--end::Modal-->

	<!--begin::Modal EXISTENCIAS PERFUMERIA JQUERY-->
	<div class="modal fade" id="kt_modal_6" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
		<div class="modal-dialog" role="document">
			<div class="modal-content">
				<div class="modal-header">
					<h5 class="modal-title" id="exampleModalLabel">Productos Sin Existencias</h5>
					<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					</button>
				</div>
				<div class="modal-body">
					<table class="table table-striped table-bordered table-hover" id="exislunnot">
						<thead>
							<tr>
								<th>No</th>
								<th>CÓDIGO</th>
								<th>DESCRIPCIÓN</th>
							</tr>
						</thead>
						<tbody id="existenciasnot2">
						</tbody>
					</table>
				</div>
				<div class="modal-footer">
					<button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
				</div>
			</div>
		</div>
	</div>

	<!--end::Modal-->

</div>

<!-- end:: Content -->
							