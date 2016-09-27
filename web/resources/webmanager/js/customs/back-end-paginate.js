$(document).ready(function(){
	$('#id_items_x_page').change(function(){
		var selected = $(this).val();
		window.location.href = selected;		
	});
	$('#filter_col').change(function(){ 
		var filter_column = $(this).val();
		var filter_column_val = $('#filter_col_val');
		if(filter_column != "all"){ /*alert(filter_column);*/
			window.location.href = filter_column;
			filter_column_val.removeAttr("disabled");
			
		}
		else{
			filter_column_val.val("all");
		}
	});
	$('#filter_col_val').change(function(){ /*alert($(this).val());*/
		window.location.href = $(this).val().replace("filter_col", $('#filter_col :selected').text());
	});
	
});