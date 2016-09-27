(function($){
	var options;	
	$.fn.photoShow = function(opts){ /*alert("aa");*/
		options = $.extend($.fn.photoShow.defaults, opts);	
		if(options.ajaxRequest){
			dataRequest();
		}			
		else{
			photoShowInit();
		}			
		return this;
	};
	function photoShowInit(){ 
		$('.img-active').removeClass('img-active');
		var openingPhoto = options.photos.eq(options.active);
		var defaultImg = openingPhoto.parent().addClass('img-active');
		options.screen.children('img').attr({src: openingPhoto.attr('src')});	
		options.photos.each(function(){
			$(this).click(function(){
				if(!$(this).parent().hasClass('img-active')){
					activateTransition($(this));
				}				
			});
		});
		options.btnNextPhoto.unbind('click');
		options.btnNextPhoto.click(function(){ 
			var nextPhotoParent = $('.img-active').next();
			if(nextPhotoParent.length != 0){
				activateTransition(nextPhotoParent.children());
			}			
		});
		options.btnPrevPhoto.unbind('click');
		options.btnPrevPhoto.click(function(){ 
			var prevPhotoParent = $('.img-active').prev();
			if(prevPhotoParent.length != 0){
				activateTransition(prevPhotoParent.children());
			}			
		});
		options.screen.unbind('click');
		options.screen.click(function(e){ 
			e.preventDefault();
			e.stopPropagation();
			if(options.descriptionContainer.height() === 0 && options.btnPrevPhoto.width() <= 3 && options.btnNextPhoto.width() <= 3 ){ /*alert('a');*/
					triggerPhotoFeatures(false, false);
			}
			else{
				triggerPhotoFeatures(true);
			}		
		});
		options.screen.unbind('mouseover mouseout');
		options.screen.hover(
			function(e){
				e.preventDefault();
				e.stopPropagation();
				if(options.descriptionContainer.children().html() != ""){
					triggerPhotoFeatures(false, false);
				}				
			}, 
			function(e){
				e.preventDefault();
				e.stopPropagation();
				triggerPhotoFeatures(true);
			}
		);
//		alert(options.photoStrip.outerWidth() +"  "+ options.photoStrip[0].scrollWidth);
		if (options.photoStrip.outerWidth() < options.photoStrip[0].scrollWidth - 1) { 
		    options.btnBackwrdStrip.fadeIn(500);
			options.btnForwrdStrip.fadeIn(500);
		} 
		scrollPhotoStripEvents();		
		triggerPhotoFeatures(true);
		triggerPhotoFeatures(false, true);
	};
	function activateTransition(element){
		var currentPhoto = options.screen.children('img');
		var clickedImg = element;
		$('.img-active').removeClass('img-active');
		clickedImg.parent().addClass('img-active');				
		currentPhoto.fadeOut(300, function(){
			triggerPhotoFeatures(true);
			$(this).attr({src: clickedImg.attr('src')}).fadeIn(300, function(){
				triggerPhotoFeatures(false);
			});
		// Autoscrolling the photoStrip to the right if the chosen pic is hidden on the left side
			if(options.photoStrip.offset().left > clickedImg.offset().left){ 
			 	// if is in the gap between document left 0 and the photostrip offset().left 
				if(clickedImg.offset().left > 0){  
					scrollPhotoStrip(false, (options.photoStrip.offset().left - clickedImg.offset().left + 5));
				}
				//At this point, If not is in the gap then it is further to the left
				else{ 
					scrollPhotoStrip(false, ( - clickedImg.offset().left + options.photoStrip.offset().left + 5));
				}				
			}
			// Autoscrolling the photoStrip to the left if the chosen pic is hidden on the right side
			if( (clickedImg.offset().left + clickedImg.width()) > (options.photoStrip.offset().left + options.photoStrip.width())){ 
				scrollPhotoStrip(true,(clickedImg.offset().left - options.photoStrip.offset().left - 5));
			}
		});
	};
	function scrollPhotoStripEvents(){
		options.btnForwrdStrip.unbind("mousedown");
		options.btnForwrdStrip.mousedown(function(e){
			e.preventDefault();
			scrollPhotoStrip(true);
		});
		options.btnBackwrdStrip.unbind("mousedown");
		options.btnBackwrdStrip.mousedown(function(e){
			e.preventDefault();
			scrollPhotoStrip(false);
		});
	};
	function scrollPhotoStrip(toLeft, displace){
		displace = displace ? displace : 2*options.photos.width();
		var scrollingTo = options.photoStrip.scrollLeft();	
		if(toLeft){
			scrollingTo += displace;
		}
		else{
			scrollingTo -= displace;
		}
		
		options.photoStrip.animate({
			'scrollLeft': scrollingTo,
		},
		300
		);
	};
	function triggerPhotoFeatures(close, timer){
		var delay;
//		clearTimeout(delay);
		if(close){
			if(options.descriptionContainer.height() > 0 && options.btnPrevPhoto.width() > 3 && options.btnNextPhoto.width() > 3 ){
				showPhotoDescription(true);
				showPhotoBtn(options.btnPrevPhoto, true);
				showPhotoBtn(options.btnNextPhoto, true);
//				clearTimeout(delay);
			}
		}
		if(!close){
			if(options.descriptionContainer.height() === 0 && options.btnPrevPhoto.width() <= 3 && options.btnNextPhoto.width() <= 3 ){
				showPhotoDescription(false);
				showPhotoBtn(options.btnPrevPhoto, false);
				showPhotoBtn(options.btnNextPhoto, false);
//				clearTimeout(delay);
				if(timer != false){
					clearTimeout(delay);
					delay = setTimeout(
								function(){
									if(options.descriptionContainer.height() > 0 && options.btnPrevPhoto.width() > 3 && options.btnNextPhoto.width() > 3 ){ /*alert('delay');*/
										showPhotoDescription(true);
										showPhotoBtn(options.btnPrevPhoto, true);
										showPhotoBtn(options.btnNextPhoto, true);										
									}
								}, 
								3000
					);			
				}				
			}
		}		
	};
	function showPhotoDescription( close  ){
		var descriptionHeight = "0";
		var descriptionMarginBottom = "0";
		
		if(options.descriptionContainer.height() === 0 || !close){
			options.descriptionContainer.children().html($('.img-active').children(options.photoDescription).html());
			descriptionHeight = options.descriptionContainer.children().outerHeight();
		 	descriptionMarginBottom = "1%"; 
		}								
		
		options.descriptionContainer.animate({
			"height": descriptionHeight,
			"marginBottom": descriptionMarginBottom
		},
		150);
	};
	function showPhotoBtn(photoButton, close ){
		var widthSize = "0";
		if(photoButton.width() < 3 || !close){
			widthSize = "8%";
		}
		photoButton.animate({
			"width": widthSize,
		},
		150,
		function(){
			
		});
	};
	function dataRequest(){	
		options.descriptionContainer.children().html("");
		options.photoStrip.children('ul').html("LOADING...");
		options.screen.children('img').attr({src:options.iconsFolder + "loading.gif"});
		options.btnBackwrdStrip.fadeOut(0);
		options.btnForwrdStrip.fadeOut(0);
		
		var onSuccess = function onSuccess(dataObj){ 
						options.photoStrip.children('ul').empty();
						var photoArr = dataObj[0];
						for(var i = 0; i < photoArr.length; i++){
							var newImg = '<li class="imgs"><img id ="'+ photoArr[i]['id'] +'" class = "photo-thumbnails" src="'+ photoArr[i]['url'] +'"/><div class="img-description">'+ photoArr[i]['description'] +'</div></li>';
							options.photoStrip.children('ul').append(newImg);
						}
						options.photos = $('.photo-thumbnails');
						photoShowInit();
					}
		var onFail = function onFail(){
			
		}
		var ajax_request = new ajaxHandler(options.ajax.url, options.ajax.data, options.ajax.method, onSuccess, onFail);
 		ajax_request.excecute();
	};
	
	$.fn.photoShow.defaults = {
		photos: $('.photo-thumbnails'),
		screen: $('.v-screen'),
		active: 0,
		btnBackwrdStrip: $('.backward-btn-strip'),
		btnForwrdStrip: $('.forward-btn-strip'),
		btnPrevPhoto: $('.btn-prev-img'),
		btnNextPhoto: $('.btn-next-img'),
		photoStrip: $('.thumbs-strip'),
		descriptionContainer: $('.description-container'),
		photoDescription: '.img-description',
		iconsFolder: "/mmelectric/web/bundles/mmelectric/icons/photoShow/",
		inModal: $('#myModal'),
		ajaxRequest: true,
		ajax:{
			url:"",
			data:"",
			method:"POST"
		}
		
	};
})(jQuery)
