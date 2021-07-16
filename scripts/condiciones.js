$(document).off("click", ".btnAddCond").on("click", ".btnAddCond", function(event){
	event.preventDefault();
	var cual = $(this).data("idUser");
	var cual2 = $(this).data("idCotiz");
	getModal("Lunes/addCond/"+cual+"/"+cual2, function (){ });
});


$(document).off("click", ".btnDelCond").on("click", ".btnDelCond", function(event){
	event.preventDefault();
	var cual = $(this).data("idUser");
	setNoCondicion(cual).done(function(resp){
		location.reload();
	})
});


function setNoCondicion(codigo) {
	return $.ajax({
		url: site_url+"Lunes/delCond/"+codigo,
		type: "POST",
		cache: false,
		contentType: false,
		processData:false,
		dataType:"JSON",
	});
}

$(document).off("click", ".new_producto").on("click", ".new_producto", function(event) {
	event.preventDefault();
	var val = $(".new_producto").data("idUser");
	sendForm("Lunes/saveAddCond/"+val, $("#form_cond_new"), "");
});