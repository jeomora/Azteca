<script type="text/javascript">

	var name_control = "";//Nombre del controlador activo
	var name_function = "";//Nombre de la función cargada
	var window_modal = $("#mainModal");//Ventana modal usada 
	var progress = document.getElementById("myProgress");
	
	$(function() {
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
					progress.style.display = 'block';
					loading();
					$.get(element.attr("href"), function(resp){
						progress.style.display = 'none';
						$("#main_container").html(resp);//Le cargamos la respuesata
					});
					// $("#main_container").load(this.href);//Le cargamos el contenido nuevo
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

	function loading(){
		var barra = document.getElementById("barra");
		var width = 10;
		var id = setInterval(frame, 10);
		function frame(){
			if(width >= 100) {
				clearInterval(id);
			}else{
				width++;
				barra.style.width = width + '%';
				barra.innerHTML = width * 1  + '%';
			}
		}
	}

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
	
	/**
	 * Funciones para construir el dataTable
	 * @param [element 	=> Es el selector de la tabla ]
	 * @param [order 	=> Orden que se mostrará la información (ASC-DESC)]
	 * @param [limit 	=> Cantidad de registros por pagina]
	*/
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
	 * Funciones para formatear cantidades de números
	 * @author Internet
	 * @param [num 	=> Es la cantidad a formatear ]
	 * @param [d 	=> Son la cantidad de decimales a mostrar]
	*/
	function formatNumber(num, d){
		var p = num.toFixed(d).split(".");
		return p[0].split("").reverse().reduce(function(acc, num, i, orig) {
			return  num=="-" ? acc : num + (i && !(i % 3) ? "," : "") + acc;
		}, "") + "." + p[1];
	}

	/**
	 * Funciones para vaciar una ventana modal
	*/
	function emptyModal(){
		window_modal.find(".modal-body").empty();
		window_modal.find(".modal-title").empty();
	}

	/**
	 * Funciones para cargar una ventana modal
	 * @param [url 		=> Es la ruta de la petición (Controlador/función)]
	 * @param [success	=> Es la función que se ejecutará en caso de éxito]
	 * @param [error	=> Es la función que se ejecutará en caso de error]
	*/
	function getModal(url, success, error){
		emptyModal();
		$.ajax({
			url: site_url + url,
			type: "GET",
			dataType: "JSON"
		})
		.done(function(response) {
			window_modal.find(".modal-title").html(response.title);
			window_modal.find(".modal-body").html(response.view);
			window_modal.find(".modal-footer").find("#mybotton").addClass(response.class);
			window_modal.modal("show");
			if(typeof success === "function"){
				success();
			}else{
				$("body").css("cursor", "auto");
			}
		})
		.fail(function(response) {
			console.log("Error en la petición: ",response);
			toastr.error("Se generó un error en el Sistema", "USUARIO");
			if (typeof error === "function"){
				error();
			}
			window_modal.modal("hide");
		});
	}

	/**
	 * [Comprueb si una librería esta cargada]
	 * @param  [js_url 	=> Es la url completa de la libreía]
	 * @param  [type 	=> Es el tipo de archivo de la libreía]
	 * @return [boolean	=> true si esta cargada false si no esta cargada]
	*/
	var isLoaded = function(js_url, type) {
		typeof type !== "undefined" ? type : "script";
		var scripts = document.getElementsByTagName("script");
		if(type === "script"){
			return Array.from(scripts) // transformo a un array
				.map(s => s.src) // Mapeo a un array con solos los src de los JS ya cargados
				.filter(url => url == js_url) // filtro las url que coincidan con el que se intenta cargar
				.length > 0 // si existe más de una, obvio, está cargada
		}else{
			return Array.from(scripts) // transformo a un array
				.map(s => s.src) // Mapeo a un array con solos los src de los JS ya cargados
				.filter(url => url == js_url) // filtro las url que coincidan con el que se intenta cargar
				.length > 0 // si existe más de una, obvio, está cargada
		}
	}

	/**
	 * Funciíon para verificar si una libreria de javascrip existe y si no la carga, posteriormente
	 * @param [url		=> Ruta donde esta cargada la libreria de java script]
	 * @param [callback => Función que se ejecutara despues de cargar el scrip]
	*/
	function loadScript(url, callback){
		if(isLoaded() === false){
			var script = document.createElement("script")
				script.type = "text/javascript";
				if (script.readyState){//IE
					script.onreadystatechange = function(){
						if (script.readyState == "loaded" || script.readyState == "complete"){
							script.onreadystatechange = null;
							callback();
						}
					};
				}else{//Others
					script.onload = function(){
						callback();
					};
				}
				script.src = url;
			document.getElementsByTagName("head")[0].appendChild(script);
		}else{
			if(typeof callback === "function"){
				callback();
			}
		}
	}

	function loadLink(url, callback){
		if(isLoaded() === false){
			var link = document.createElement("link")
			link.rel = "stylesheet";
			if (link.readyState) { //IE
				link.onreadystatechange = function() {
						if (link.readyState == "loaded" || link.readyState == "complete") {
								link.onreadystatechange = null;
								callback();
						}
				};
			}else{//Others
				if(typeof callback === "function") {
					link.onload = function() {
							callback();
					};
				}
			}
		link.src = url;
		document.getElementsByTagName("head")[0].appendChild(link);
		}else{
			if(typeof callback === "function"){
				callback();
			}
		}
	}

	/**
	 * Funciíon para enviar un formulario method POST
	 * @param [url			=> Es la ruta que se le envian los datos]
	 * @param [formData 	=> Es el formulario a enviar]
	 * @param [url_repuesta => Url a cargar despues de recibir los datos]
	*/
	function sendForm(url, formData, url_repuesta){
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
					$("#mainModal").modal("hide");
					$("#main_container").empty();
					toastr.success(response.desc, response.id);
					$("#main_container").load(site_url+url_repuesta);
				break;

				case "info":
					cleanModal();
					$("#mainModal").modal("hide");
					toastr.info(response.desc, response.id);
					$("#main_container").load(site_url+url_repuesta);
				break;

				case "warning":
					toastr.warning(response.desc, response.id);
				break;

				default:
					toastr.error(response.desc, response.id);
			}
			$("#notifications").html(response);

		})
		.fail(function(response) {
			// console.log("Error en la respuesta: ", response);
		});
	}

</script>
