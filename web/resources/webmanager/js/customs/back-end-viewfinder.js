$(document).ready(function(){
	$("a.viewfinder").click(function(){		
		createViewFinder($(this));
		$("#myModal .modal-body").html("LOADING...");
	});
	$('tr.table-rows').dblclick(function(){
		var elem = $(this).find('a.viewfinder');
		elem.trigger("click");
		createViewFinder(elem);
	});
	var createViewFinder = function(elem){
		if($('#myModal').length === 0){
			var modal = '<div class="modal fade" id="myModal" role="dialog"><div class="modal-dialog modal-lg"><div class="modal-content"><div class="modal-header"><button type="button" class="close" data-dismiss="modal">&times;</button><h4 class="modal-title">Item details</h4></div><div class="modal-body"><p></p></div><div class="modal-footer"><button type="button" class="btn btn-default" data-dismiss="modal">Close</button></div></div></div></div>';
			$('body').append(modal);
		}	
		var url = Routing.generate("get_table_records");
 		var data = {"table":$("#active_table").attr('name'), "id": elem.attr("id")};
 		var onSuccess = function(data){
 			$("#myModal .modal-body").html(data["table_records"]["content"]); 			
		};
		var onFail = function(){
			
		};
 		var ajax_request = new ajaxHandler(url, data, "POST", onSuccess, onFail);
 		ajax_request.excecute();
	}
});