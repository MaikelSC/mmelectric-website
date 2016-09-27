$(document).ready(function(){
	
	var selected_table = $('div.panel-heading.active-table').attr("name");
	$('#'+selected_table).toggleClass("itm-selected");
	$('#id_dependencies').appendTo($('#'+selected_table)).slideDown();
	
});