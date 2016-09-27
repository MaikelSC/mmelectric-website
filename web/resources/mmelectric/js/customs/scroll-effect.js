$(document).ready(function(){
	var marginFromTop = $('nav.navbar').height();	
	$('a[href^="#"].nav-link').on('click', function(e){
		e.preventDefault();
		if($('button.navbar-toggle').css("display") != "none"){
			$('#navbar').collapse('hide');
		}
		$(document).off('scroll');
		var target = this.hash;
		var targetContainer = $(target);		
		var clickedLink = $(this);		
		$("html, body").animate({
			'scrollTop': targetContainer.offset().top - marginFromTop
			}, 
			800, 
			'swing', 
			function(){
						linkStyle(clickedLink, targetContainer); 
						$(document).on('scroll', function(){onScrollEvent();})
					}
		);
	});
	var onScrollEvent = function(){
		var contScrollPos = $(document).scrollTop();
		$('a[href^="#"].nav-link').each(function(){
			var target = this.hash; 
			var targetContainer = $(target);
			var clickedLink = $(this);
			if((targetContainer.offset().top -3 <= contScrollPos + marginFromTop) && (targetContainer.offset().top + targetContainer.height() > contScrollPos + marginFromTop)){
				linkStyle(clickedLink, targetContainer);
				clickedLink.focus();				
				if(history.replaceState){		//Changing browser url hash val on scroll.
					history.replaceState(null, null, target);
				}
				else{	// This is for old browsers versions. Although works for new ones too.
					var sectionId = targetContainer.attr('id');
					targetContainer.removeAttr('id');
					window.location.hash = sectionId;
					targetContainer.attr('id', sectionId);
				}												
			}
		});
	};
	var linkStyle = function(clickedLink, targetContainer){
		$('.active-link').removeClass('active-link menu-button-selected').addClass('menu-link-font');
		clickedLink.addClass('active-link menu-button-selected').removeClass('menu-link-font');		
	};
	var fixOnLoad = function(){
		var hashValue = window.location.hash;
		var contScrollPos = $(window).scrollTop();
		var containerOnTop = hashValue ? $(window.location.hash).offset().top : 0; /*alert(parseInt(containerOnTop)+" - "+ (parseInt(contScrollPos) + parseInt(marginFromTop)) +" - "+ parseInt(contScrollPos));*/
		if(parseInt(containerOnTop) < (parseInt(contScrollPos) + parseInt(marginFromTop)) && parseInt(containerOnTop) >= parseInt(contScrollPos))
		{
			$('html, body').animate({
			'scrollTop': parseInt(contScrollPos) - parseInt(marginFromTop)
			}, 
			800, 
			'swing');
		}
	};
	setTimeout(function(){
		fixOnLoad();
		onScrollEvent();
		$(document).on('scroll', function(e){onScrollEvent();});
	}, 500);
	
});
