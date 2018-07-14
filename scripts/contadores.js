$(document).off("change", "#file_xml").on("change", "#file_xml", function(event) {
	event.preventDefault();
	var fdata = new FormData($("#reporte_xml")[0]);
	uploadXML(fdata)
		.done(function (resp) {
			$(".respuesta").html(resp);
		});
});

function uploadXML(formData) {
	return $.ajax({
		url: site_url+"Contadores/subirxml",
		type: "POST",
		cache: false,
		contentType: false,
		processData:false,
		dataType:"JSON",
		data: formData,
	});
}
$(document).off("change", "#file_p").on("change", "#file_p", function(event) {
	event.preventDefault();
	var fdata = new FormData($("#upload_xml")[0]);
	uploadXMLs(fdata)
		.done(function (resp) {
			$(".respuesta").html(resp);
		});
});

function uploadXMLs(formData) {
	return $.ajax({
		url: site_url+"Contadores/upload_productos2",
		type: "POST",
		cache: false,
		contentType: false,
		processData:false,
		dataType:"JSON",
		data: formData,
	});
}