$(document).ready(function(){
		var selected_inputs = [];
		$('input.list-selector').prop('checked',false);
		var edit_href = $('#edit_item').attr('href');
		$('#edit_item').removeAttr('href');
	//------------------------------------------FUNCTIONS----------------------------------------------------------
		var getSelected = function(){
			selected_inputs = [];
			$('input:checked:not(#cb_select_all)').each(function(item_index){
					selected_inputs[item_index]= $(this).val();
				});
		}
		var disableLink  = function(disable_link){
			disable_link.click(function(e){
				e.preventDefault();
			});	
			disable_link.unbind("click");		
			disable_link.prop("disabled", true);
 			disable_link.css({"color":"gray", "cursor":"not-allowed"});
 		}
		 var enableLink = function(enable_link, url_to_gen){		 	
		 	enable_link.prop("disabled", false);
		 	enable_link.css( {"color":"black", "cursor":"pointer"} );
		 	enable_link.unbind("click");
		 	enable_link.click(function(){
		 		if(url_to_gen==='edit_item'){
		 			$('#edit_item').attr('href', edit_href.replace('id_value', selected_inputs));
				}
				else{
					if(confirm("Are you sure?"))
					{
						var url = Routing.generate(url_to_gen);
				 		var data = {"table":$("#active_table").attr('name'), "ids": selected_inputs};
				 		var onSuccess = function(){
							window.location.reload();
						};
						var onFail = function(){
							var msg= "Sorry, there was an unexpected error!!!";
							var msg_container = '<div id="msg"><p>'+msg+'</p></div>';
							var html = $('html');
							html.prepend(msg_container);					
							$('#msg').css({
											color:"#5b0000",
											background:"#f9c1bb", 
											border:"1px solid #5b0000",
											zIndex:"1000",
											display:"none",
											position:"absolute",
											padding: '2em'
										 });
							var center = (($('html').width()/2 - $('#msg').width()/2));			 
							$('#msg').css({left:center+'px'});
							$('#msg').fadeIn("slow").delay(8000).fadeOut("slow");
						};
				 		var ajax_request = new ajaxHandler(url, data, "POST", onSuccess, onFail);
				 		ajax_request.excecute();
					}					
				}
		 		
		 	});
		}		
	//-------------------------------------------------------------------------------------------------------
		disableLink($('#del_link'));
		disableLink($('#upd_link'));
	//------------------------------------------EVENTS-------------------------------------------------------
		$('#cb_select_all').click(function(){
//			alert(del_href);
			if($('#cb_select_all:checked').length > 0){
				$('input').prop('checked',true);
				getSelected();
				if($('input:checked').length > 2)
				{
					disableLink($('#upd_link'));
				}
				else
				{					
					enableLink($('#upd_link'), 'edit_item');
				}				
				enableLink($('#del_link'), 'remove_item');
				
			}
			else{
				$('input').prop('checked',false);
				disableLink($('#del_link'));
				disableLink($('#upd_link'));
			}
			
		});
		$('input').click(function(){
			
			if($('input:checked:not(#cb_select_all)').length > 0){
				getSelected();
				enableLink($('#del_link'), 'remove_item');					
			}
			else{
				disableLink($('#del_link'));
			}
			if($('input:checked:not(#cb_select_all)').length === 1){
				getSelected();
				enableLink($('#upd_link'), 'edit_item');					
			}
			else{
				disableLink($('#upd_link'));
			}
			if($('input:checked:not(#cb_select_all)').length < $('input:not(#cb_select_all)').length){
				$('#cb_select_all').prop('checked', false);								
			}
			else{
				$('#cb_select_all').prop('checked', true);
				getSelected();
				enableLink($('#del_link'), 'remove_item');
			}
		});
	//--------------------------------------------------------------------------------------------------------
});

 
