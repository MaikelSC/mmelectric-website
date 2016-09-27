
$(document).ready(function(){	
	var hoverEffect = function(obj){
		var showAlbum = obj.siblings(".show-album");
		obj.hover(function(e){ /*alert("a");*/
			e.stopPropagation();
			e.preventDefault();			
			if(showAlbum.css("display") === 'none'){
				showAlbumView(showAlbum);
					
				showAlbum.hover(function(e){
					
				},function(e){ 
					e.stopPropagation();			
					if($(this).css("display") != 'none' && e.relatedTarget != null && e.relatedTarget.getAttribute("class") != "album-icon"){
						closeAlbumView($(this));
					}
				});			
			}			
		},function(e){
			if(e.target.getAttribute("class") === "album-icon" && e.relatedTarget.getAttribute("class") != "show-album"){
				closeAlbumView(showAlbum);
			}
		});
	}
	$("img.project-img").each(function(){
		hoverEffect($(this));		
	});
	$(".album-icon").each(function(){
		hoverEffect($(this));		
	});
	
	var showAlbumView = function(showAlbum){
		showAlbum.fadeIn(500, function(){
				showAlbum.children().fadeIn(300);
								
			});
	}
	var closeAlbumView = function(showAlbum){
		showAlbum.children().fadeOut(300, function(){
			showAlbum.fadeOut(500);
			
		});
	}
});