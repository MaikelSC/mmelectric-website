$(document).ready(function(){
	
	var loadPhotoShow = function(obj, id){
		obj.on( "click", function(){
			var ajax = {
				url: Routing.generate("get_photos_album"),
				data: {"id": id},		
			};
			$(this).photoShow({ajax:ajax}); /*alert("aa");*/
		});
	}
	
	$('.img-container .show-album').each(function(){		
		loadPhotoShow($(this), $(this).attr("id"))
	});
	$('.img-container .album-icon').each(function(){		
		loadPhotoShow($(this), $(this).siblings(".show-album").attr("id"));
	});
	
});