(function($){
	var options;
	$.fn.messagePopup = function(opts){
		options = $.extend($.fn.messagePopup.defaults, opts);
		messagePopupInit();
		return this;
	}
	function messagePopupInit(){
		var popup = '<div class = "popup-positioning center-XY alert alert-danger"></div>';
		options.pFather.prepend(popup);
		popup = $('.popup-positioning');
		popup.append(options.pMessage.show()).fadeIn(500);
		setTimeout(function(){
			popup.fadeOut(500);
			popup.empty();
		},5000)
	}
	$.fn.messagePopup.defaults = {
		pFather:$('html'),
		pMessage: "<p>There was an error</p>",
	}
})(jQuery)