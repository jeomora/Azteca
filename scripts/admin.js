$(document).off("click", "#no_cotizo").on("click", "#no_cotizo", function(event){
	event.preventDefault();
	var ides = $(this).attr("data-id-producto");
	getModal("Main/getCotzUsuario/"+ides, function (){ });
});

