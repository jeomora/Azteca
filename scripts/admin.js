$(document).off("click", "#no_cotizados").on("click", "#no_cotizados", function(event){
	event.preventDefault();
	getModal("Main/getNotCotizados/", function (){ });
});

$(document).off("click", "#no_cotizo").on("click", "#no_cotizo", function(event){
	event.preventDefault();
	getModal("Main/getNotCotizo/", function (){ });
});
