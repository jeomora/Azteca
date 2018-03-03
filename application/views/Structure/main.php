<script type="text/javascript">

	var window_modal = $("#mainModal");//Ventana modal usada

	$(function($) {
		$("#mainModal").modal({
			backdrop: 'static',
			keyboard: false,
			show: false
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

	/**
	 * Función para construir el dataTable
	 * @param [element 	=> Es el selector de la tabla ]
	 * @param [order 	=> Orden que se mostrará la información (ASC-DESC)]
	 * @param [limit 	=> Cantidad de registros por pagina]
	*/
	function fillDataTable(element, limit) {
		$("#"+element).dataTable({
			responsive: true,
			pageLength  : limit,
			dom: 'Bfrtip',
			lengthMenu: [
				[ 10, 30, 50, -1 ],
				[ '10 registros', '30 registros', '50 registros', 'Mostrar todos']
			],
			bSort:false,
			language: {
            processing: '<div class="spinns"><i class="fa fa-spinner fa-spin fa-3x fa-fw"></i><span style="font-size:3rem;">Cargando...</span></div> '},
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
			".chosen-select-width"		: {width:"100%"}
		};
		for (var selector in config) {
			$(selector).chosen(config[selector]);
		}
	}

	/**
	 * Función para formatear cantidades de números
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
	 * Función para vaciar una ventana modal
	*/
	function emptyModal(){
		window_modal.find(".modal-body").empty();
		window_modal.find(".modal-title").empty();
		window_modal.find(".modal-footer").empty();
	}

	/**
	 * Funcióne para cargar una ventana modal
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
			window_modal.find(".modal-footer").html(response.button);
			window_modal.modal("show");
			if(typeof success === "function"){
				success();
			}else{
				$("body").css("cursor", "auto");
			}
		})
		.fail(function(response) {
			console.log("Error en la petición: ",response);
			toastr.error("Se generó un error en el Sistema", user_name);
			if (typeof error === "function"){
				error();
			}
			window_modal.modal("hide");
		});
	}

	/**
	 * [Comprueba si una librería esta cargada]
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
	 * Función para verificar si una libreria de javascrip existe y si no la carga, posteriormente
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
	 * Función para enviar un formulario method POST
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
					emptyModal();
					$("#mainModal").modal("hide");
					setTimeout("location.reload()", 1300, toastr.success(response.desc, user_name), "");
				break;

				case "info":
					emptyModal();
					$("#mainModal").modal("hide");
					setTimeout("location.reload()", 1300, toastr.info(response.desc, user_name), "");
				break;

				case "warning":
					toastr.warning(response.desc, user_name);
				break;

				default:
					toastr.error(response.desc, user_name);
			}
			$("#notifications").html(response);
		})
		.fail(function(response) {
			// console.log("Error en la respuesta: ", response);
		});
	}

	function blockPage(){
		$("html").block({
			centerY: 0,
			message: "<h2>"+user_name+"</h2> <img src='./assets/img/busy.gif'/> <h3>Espere que se cargue la información ...</h3>",
			css: { top: '50px', left: '', right: '10px' },
			overlayCSS: { backgroundColor: '#DDFF33' }
		});
	}
	
	function unblockPage() {
		$("html").unblock();
	}

</script>
