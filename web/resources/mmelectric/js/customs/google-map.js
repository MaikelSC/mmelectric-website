//-------------------------------------------------------------------------------------------------------------	
//							Google Map Service
//-------------------------------------------------------------------------------------------------------------	
$(document).ready(function(){
	var map;
    var infoWindow;
    var bounds;   
    var markersList = [];
    var getCoordinates = function(){
    	var contact_id = $('p.contact-id');
    	contact_id.each(function(){
    		markersList.push(
    				{
						lat: $(this).attr('lat'),
			    		lng:  $(this).attr('lng'),
			    		description: $(this).html()
					}
    		);
    	});
		 
	}
	getCoordinates();
	var addMarkers = function(){
		bounds = new google.maps.LatLngBounds();
		infoWindow = new google.maps.InfoWindow();
		for(var i = 0; i < markersList.length; i++){
				var position = new google.maps.LatLng(markersList[i].lat, markersList[i].lng);
		        var description = markersList[i].description;					
		        bounds.extend(position);
		        createMarker(position, description);
			}
			//fitting bounds for centering all the markers in the map container
			map.fitBounds(bounds);
			
			google.maps.event.addListener(map, 'click', function() {			    	
	    		infoWindow.close();
	    		activeCurrentContact();
		    });
			
	}
	var activeCurrentContact = function(contact_container){
		$("div.contact-content").each(function(){
		    		$(this).removeClass('contact-content-active');
		    	});
		contact_container ? contact_container.addClass('contact-content-active') : null;
	}
	var createMarker = function(position, description){
		var marker = new google.maps.Marker({
			        position: position,
			        map: map,
		        });
		    var mouseout;
		    google.maps.event.addListener(marker, 'mouseover', function(event) {			    	
	    		openMarkersWindow(marker,  description); 
	    		var contact_container = $("p[lat = "+"'"+ event.latLng.lat()+"'"+"]").parent();
	    		mouseout = google.maps.event.addListener(marker, 'mouseout', function() {			    	
	    			infoWindow.close();
	    			google.maps.event.removeListener(mouseout);
	    			contact_container.removeClass('contact-content-active');
		    	});
		    	activeCurrentContact(contact_container);
		    });
		    google.maps.event.addListener(marker, 'click', function(event) {		    	
		    	var contact_container = $("p[lat = "+"'"+ event.latLng.lat()+"'"+"]").parent();	
		    	activeCurrentContact(contact_container);
	    		openMarkersWindow(marker, description); 	
	    		google.maps.event.removeListener(mouseout);		    
		    });		    
		    
		}
		var openMarkersWindow = function(marker, description){
			
			var iwContent = '<div id="iw_container">' +
					            '<div class="iw_title"> ' + description + '</div>' +
					        '</div>';
				      
			// including content to the Info Window.
		    infoWindow.setContent(iwContent);

		      // opening the Info Window in the current map and at the current marker location.
		    infoWindow.open(map, marker);		
		}
	window.initMap = function() {
		var mapDiv = document.getElementById('map');
			map = new google.maps.Map(mapDiv, {});			    
			addMarkers();
	}
	var loadScript = function() {
	  var script = document.createElement('script');
	  script.src = "https://maps.googleapis.com/maps/api/js?key=AIzaSyDJ0MgWOkP4eHjJGCWoXWTno1dapaW4duM&callback=initMap";
	  document.body.appendChild(script);
	}
	loadScript();
});