$(function($) {
	$("[data-toggle='tooltip']").tooltip({
		placement:'top'
	});
	setTableP();
});
$(document).off("change", "#file_pendientes").on("change", "#file_pendientes", function(event) {
	event.preventDefault();
	blockPage();
	var fdata = new FormData($("#upload_pendientes")[0]);
	uploadPendientes(fdata)
		.done(function (resp) {
			if (resp.type == 'error'){
				toastr.error(resp.desc, user_name);
			}else{
				unblockPage();
				setTimeout("location.reload()", 700, toastr.success(resp.desc, user_name), "");
			}
		});
});
function uploadPendientes(formData) {
	return $.ajax({
		url: site_url+"Lunes/upload_pendientes/",
		type: "POST",
		cache: false,
		contentType: false,
		processData:false,
		dataType:"JSON",
		data: formData,
	});
}


function myFunction() {
  // Declare variables 
  var input, filter, table, tr, td, i;
  input = document.getElementById("myInput");
  filter = input.value.toUpperCase();
  table = document.getElementById("table_prov_cots");
  tr = table.getElementsByTagName("tr");

  // Loop through all table rows, and hide those who don't match the search query
  for (i = 0; i < tr.length; i++) {
    td = tr[i].getElementsByTagName("td")[0];
    if (td) {
      if (td.innerHTML.toUpperCase().indexOf(filter) > -1) {
        tr[i].style.display = "";
      } else {
        tr[i].style.display = "none";
      }
    } 
  }
}
function getPendientes() {
	return $.ajax({
		url: site_url+"/Lunes/getPendientes/",
		type: "POST",
		dataType: "JSON"
	});
}


function setTableP(){
	getPendientes()
	.done(function (resp) {
		console.log(resp.pendientes);
		if(resp.pendientes){
			$.each(resp.pendientes, function(indx, value){
				$(".tablePendv").append('<tr><td>'+value.codigo+'</td><td>'+value.descripcion+'</td><td>'+value.cedis +'</td><td>'+value.abarrotes+
					'</td><td>'+value.pedregal+'</td><td>'+value.tienda +'</td><td>'+value.ultra+'</td><td>'+value.trincheras+
					'</td><td>'+value.mercado+'</td><td>'+value.tenencia+'</td><td>'+value.tijeras+'</td></tr>'
					/*+'<td><button id="update_cotizacion" class="btn btn-info" data-toggle="tooltip" title="Editar" data-id-cotizacion="'+value.id_cotizacion+'">'+
					'<i class="fa fa-pencil"></i></button><button id="delete_cotizacion" class="btn btn-warning" data-toggle="tooltip" title="Eliminar" '+
					'data-id-cotizacion="'+value.id_cotizacion+'"><i class="fa fa-trash"></i></button></td></tr>'*/)
			});
		}
		$(".searchboxs").css("display","block");
		$(".float-e-margins").css("display","block");
		setTimeout("fillDataTable('table_cot_v', 50);", 700, "", "");
				
	});
}