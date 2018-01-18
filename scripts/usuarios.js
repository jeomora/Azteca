$(function($) {
	$("[data-tooltip='tooltip']").tooltip({
		placement:'top'
	});
	fillDataTable("table_usuarios", 10);
});
