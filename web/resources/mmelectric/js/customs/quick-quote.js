$(document).ready(function(){
//	alert($('.floating-right').height());
	var containerOpen = $('div.quick-quote > form').height();
	$.fn.slidingTab({containerOpen : containerOpen});
	$.fn.slidingTab({tab: $('#btn-close-qq'), containerOpen : containerOpen});
	$.fn.slidingTab({tab: $('#qq-contact-link'), containerOpen : containerOpen});
	
});