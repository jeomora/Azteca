<script type="text/javascript">

	var name_control = "";//Nombre del controlador activo
	var name_function = "";//Nombre de la función cargada

	$(function($) {
		var iniciar =1;

		$("#myModal").modal({
			backdrop: 'static',
			keyboard: false,
			show: false
		});

		$(document).off("click", "btn-modal").on("click", ".btn-modal", function(event){
			event.preventDefault();
			iniciar = 2;
			if(iniciar == 1){
				iniciar = 2;
			}else{
				cleanModal();
				$(".modal-content").load(this.href);
			}
			$( ".btn-modal").unbind( "click" );
		});

		$(document).off("click", "a").on("click", "a", function (event){
			event.preventDefault();
			var element = $(this);
			name_control = element.attr("control");
			name_function = element.attr("funcion");
			if(!element.hasClass("close-session") && !element.hasClass("print")){
				if(element.attr("href") !="#" && element != base_url && element != "" && !element.hasClass("btn-modal")){ 
					$("#welcome_container").remove();//Eliminamos el contenedor de bienvenida
					$("#main_container").html('');//Limpiamos el contenedor
					$("#main_container").load(this.href);//Le cargamos el contenido nuevo
				
				}
			
			}else{
				if(element.hasClass("print")){
					window.open(element);
				}else{
					window.location = element.attr("href");
				}
			}

		});
		
	});


	function datePicker() {
		$(".datepicker").datepicker({
			format : 'dd-mm-yyyy',
			autoclose : true,
			firstDay: 1,
			language: 'es'
		});
	}

	function cleanModal() {
		$("#myModal .modal-header").empty();
		$("#myModal .modal-body").empty();
		$("#myModal .modal-footer").empty();
	}

	function sendDatos(url, formData, url_repuesta){

		url_repuesta = typeof url_repuesta === 'undefined' ? "/#" : url_repuesta;

		$.ajax({
			url: site_url + url,
			type: "POST",
			dataType: "JSON",
			data: (formData).serializeArray()
		})
		.done(function(response) {
			switch(response.type){
				case "success":
					cleanModal();
					$("#myModal").modal("hide");
					toastr.success(response.desc, response.id);
					// location.reload();
					$("#main_container").load(site_url+url_repuesta);
				break;

				case "info":
					cleanModal();
					$("#myModal").modal("hide");
					toastr.info(response.desc, response.id);
					$("#main_container").load(site_url+url_repuesta);
				break;

				case "warning":
					cleanModal();
					$("#myModal").modal("hide");
					toastr.warning(response.desc, response.id);
					$("#main_container").load(site_url+url_repuesta);
				break;

				default:
					cleanModal();
					$("#myModal").modal("hide");
					toastr.error(response.desc, response.id);
					$("#main_container").load(site_url+url_repuesta);
			}
			$("#notifications").html(response);

		})
		.fail(function(response) {
			// console.log("Error en la respuesta: ", response);
		})
		.always(function(response) {
			$("#myModal .modal-content").empty();
			$("#myModal .modal-body").empty();
			// console.log("Petición completa: ", response);
		});
	}

	function fillDataTable(element, order, limit) {
		$("#"+element).dataTable({
			responsive: true,
			pageLength  : limit,
			"order": [[0, order]],
			dom: 'Bfrtip',
			lengthMenu: [
				[ 10, 30, 50, -1 ],
				[ '10 registros', '30 registros', '50 registros', 'Mostrar todos']
			],
			buttons: [{
				extend: 'pageLength'
			}]
		});
	}

	function getChosen(){
		var config = {
			".chosen-select"			: {},
			".chosen-select-deselect"	: {allow_single_deselect:true},
			".chosen-select-no-single"	: {disable_search_threshold:10},
			".chosen-select-no-results"	: {no_results_text:'No existen coincidencias'},
			".chosen-select-width"		: {width:"100%"}
		};
		for (var selector in config) {
			$(selector).chosen(config[selector]);
		}
	}

	/**
	* Funciones para formatear cantidades
	* @author Internet
	* @parm {num=cantidad, d=decimales}
	*/
	function formatNumber(num, d){
		var p = num.toFixed(d).split(".");
		return p[0].split("").reverse().reduce(function(acc, num, i, orig) {
			return  num=="-" ? acc : num + (i && !(i % 3) ? "," : "") + acc;
		}, "") + "." + p[1];
	}


</script>
