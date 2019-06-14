$(function($) {
	$("[data-toggle='tooltip']").tooltip({
		placement:'top'
	});
	setAdminTable();
});

function getAdminTable() {
	return $.ajax({
		url: site_url+"/Cotizaciones/getDirTable/4",
		type: "POST",
		dataType: "JSON"
	});
}

function getTableHead() {
	return $.ajax({
		url: site_url+"/Cotizaciones/getDirProv/4",
		type: "POST",
		dataType: "JSON"
	});
}

function setAdminTable(){
	event.preventDefault();
	var tablehead = "";
	var proveedor = [];
	var tablebody = "";
	
	var flag = 0;
	var flag2 = false;
	getTableHead().done(function (resp){
		if(resp.cotizados){
			$.each(resp.cotizados, function(index, value){
				flag += 1;
				proveedor.push({id:flag, name:value.nombre})
				$(".trdirectos").append("<th colspan='2'>"+value.nombre+"</th>");
			});
		}
	});
	var producto = [];
	var vals = [];
	var gg = "";
	getAdminTable()
		.done(function (resp) {
			if(resp.cotizados){
				$.each(resp.cotizados, function(indx, value){
					for (var i = 0; i < flag; i++) {
						$.each(value.articulos, function(index, val){
							if(i == 0 && tablebody == ""){
								tablebody += "<tr><td>"+val.familia+"</td><td>"+val.codigo+"</td><td>"+val.producto+"</td><td> $"+formatNumber(parseFloat(val.precio_sistema), 2)+"</td><td>"+formatNumber(parseFloat(val.precio_four), 2)+"</td>";
							}
							if(i == proveedor.findIndex(x => x.name === val.proveedor) && flag2 == false){
								flag2 = true;
								vals = val;
							}
						});
						if(flag2 == true){
							tablebody += "<td>$ "+formatNumber(parseFloat(vals.precio_promocion), 2)+"</td><td style='font-size:10px'>"+(vals.observaciones == null ? "Sin observaciones" : vals.observaciones)+"</td>";
							flag2 = false;
							vals = [];
						}else{
							tablebody += "<td></td><td></td>";
							vals = [];
						}
					}
					tablebody += "</tr>";
					gg += tablebody;
					tablebody = "";
				});
			}
			
			setTimeout(function(){ $(".tableAdminv").html(gg); }, 3000);
			setTimeout(function(){ $(".tableAdminv").html(gg); }, 10000);
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



