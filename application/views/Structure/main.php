<script type="text/javascript">

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

		/*$(document).off("click", "a").on("click", "a", function (event){
			event.preventDefault();
			var href = $(this).href;
			var element = $(this);
			console.log("ruta = ",href);
			console.log("elemento = ",element);
			if(element.hasClass("print")){
				window.open(element);
			}else{
				// window.location=element;
				$("#main_container").html(element);
			}
		});*/
		
	});

	function datePicker() {
		$(".datepicker").datepicker({
			"format" : 'dd-mm-yyyy',
			autoclose : true
		});
	}

	function cleanModal() {
		$("#myModal .modal-header").empty();
		$("#myModal .modal-body").empty();
		$("#myModal .modal-footer").empty();
	}

	function sendDatos(url, formData){
		$.ajax({
			url: urlbase + url,
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
					location.reload();
				break;

				case "info":
					cleanModal();
					$("#myModal").modal("hide");
					toastr.info(response.desc, response.id);
					location.reload();
				break;

				case "warning":
					cleanModal();
					$("#myModal").modal("hide");
					toastr.warning(response.desc, response.id);
					location.reload();
				break;

				default:
					cleanModal();
					$("#myModal").modal("hide");
					toastr.error(response.desc, response.id);
					location.reload();
			}
			$("#notifications").html(response);
		})
		.fail(function(response) {
			console.log("Error en la respuesta");
		})
		.always(function(response) {
			$("#myModal .modal-content").empty();
			$("#myModal .modal-body").empty();
			console.log("Petición completa");
		});
	}

</script>
