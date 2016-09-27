(function($){
	var options;
	$.fn.slidingTab = function(opts){
		options = $.extend($.fn.slidingTab.defaults, opts);
		slidingTabInit();
		return this;
	}
	function slidingTabInit(){
		options.tab.click(function(){
			if(options.container.height() != 0)
			{
				fixTop(options.containerClosed, false);			
			}
			else
			{
				fixTop(options.containerOpen, true);
			}
		});
	}
	//------------------------------------------Animate Effect -----------------------------------------------------
	function fixTop(height, isOpening)
	{
		if(isOpening){
			options.tabIcon.removeClass("left-arrow-icon");
			options.tabIcon.addClass("down-arrow-icon");
		}
		else{
			options.tabIcon.addClass("left-arrow-icon");
			options.tabIcon.removeClass("down-arrow-icon");
		}
		options.container.animate({
						'height': height,
					},350);
	}
//--------------------------------------------------------------------------------------------------------------
	$.fn.slidingTab.defaults = {
		container : $('div.quick-quote'),
		tab : $('div.quick-quote-tab'),
		tabIcon : $('div.qq-tab-text'),
		containerOpen : $('div.quick-quote > form').height(),
		containerClosed : '0',
	}
})(jQuery)