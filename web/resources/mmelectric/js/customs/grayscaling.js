$(document).ready(function(){
	var footerImages = $('.footer-img-leader');	
	grayscale.prepare(footerImages);
	grayscale(footerImages);
	
	footerImages.hover(function(){ 
		grayscale.reset($(this));
		var footerLeadersName = $(this).siblings('.footer-leaders-name');
		var footerLeadersOccup = $(this).siblings('.footer-leaders-occupation');
		footerLeadersName.addClass('footer-leaders-name-hover');
		footerLeadersOccup.addClass('footer-leaders-occupation-hover');
	},function(){
		grayscale.prepare($(this));
		grayscale($(this));
		var footerLeadersName = $(this).siblings('.footer-leaders-name');
		var footerLeadersOccup = $(this).siblings('.footer-leaders-occupation');
		footerLeadersName.removeClass('footer-leaders-name-hover');
		footerLeadersOccup.removeClass('footer-leaders-occupation-hover');
	});
});