$(function($) {
	$("[data-tooltip='tooltip']").tooltip({
		placement:'top'
	});
	fillDataTable("table_usuarios", 30);
});


$(document).off("click", "#new_usuario").on("click", "#new_usuario", function(event) {
	event.preventDefault();
	getModal("Auth/create_user", function (){ });
});

$(document).off("click", "#update_usuario").on("click", "#update_usuario", function(event) {
	event.preventDefault();
	var id_user = $(this).closest("tr").find("#update_usuario").data("idUser");
	getModal("Auth/edit_user/"+id_user, function (){ });
});

$(document).off("click", "#desactivar_usuario").on("click", "#desactivar_usuario", function(event) {
	event.preventDefault();
	var id_user = $(this).closest("tr").find("#desactivar_usuario").data("idUser");
	getModal("Auth/deactivate/"+id_user, function (){ });
});

$(document).off("click", "#activar_usuario").on("click", "#activar_usuario", function(event) {
	event.preventDefault();
	var id_user = $(this).closest("tr").find("#activar_usuario").data("idUser");
	$.get("Auth/activate/"+id_user, function (){
		location.reload();
	});
});

$(document).off("click", "#change_password").on("click", "#change_password", function(event) {
	event.preventDefault();
	var id_user = $(this).closest("tr").find("#change_password").data("idUser");
	getModal("Auth/change_password/"+id_user, function (){ });
});