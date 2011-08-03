$(document).ready(function() {
	
	
	//Navigation hover
	$("#full_slider").hover(function(){
		$("#next,#back").stop().animate({opacity:0.6},500);
		},function(){
		$("#next,#back").stop().animate({opacity:0},500);
		});
	
	
	//Navigation hover
	$("#next,#back").hover(function(){
		$(this).stop().animate({opacity:1},500);
		},function(){
		$(this).stop().animate({opacity:0.6},500);
		});
	

	// Backwards navigation
	
	var navigateBack = function() {
	    $("#next").unbind('click',navigateNext);
		$("#back").unbind('click',navigateBack);
	
        stopAnimation();
		navigate("back");
    };
	
	var navigateNext = function() {
	    $("#next").unbind('click',navigateNext);
		$("#back").unbind('click',navigateBack);
		
		stopAnimation();
		navigate("next");
    };
		
	var interval;
	$("#control").toggle(function(){
		stopAnimation();
	}, function() {		
		// Show the next image
		navigate("next");
		
		// Start playing the animation
		interval = setInterval(function() {
			navigate("next");
		}, slideshowSpeed);
	});
	
	var anim_bg=1;
	var activeContainer = 1;	
	var currentImg = 0;
	var animating = false;
	var navigate = function(direction) {
		
		
		// Check which current image we need to show
		if(direction == "next") {
			currentImg++;
			if(currentImg == photos.length + 1) {
				currentImg = 1;
			}
		} else {
			currentImg--;
			if(currentImg == 0) {
				currentImg = photos.length;
			}
		}
		
		// Check which container we need to use
		var currentContainer = activeContainer;
		if(activeContainer == 1) {
			activeContainer = 2;
		} else {
			activeContainer = 1;
		}
		

		
		showImage(photos[currentImg - 1], currentContainer, activeContainer);
		
	};
	
	var currentZindex = -1;
	
	var showImage = function(photoObject, currentContainer, activeContainer) {
		

	    var imageObj = new Image();
        $(imageObj).attr("src",photoObject.image).load(function(){    
		
	
			currentZindex--;
		
			
			// Set the background image of the new active container
			$("#headerimg" + activeContainer).css({
				"background-image" : "url(" + photoObject.image + ")",
				"z-index" : currentZindex,
				"backgroundPosition" : 'center'
			});
			

			
			$("#headerimg" + activeContainer).fadeTo(1000,1, 'linear', function () {

				  $("#next").unbind('click',navigateNext).bind('click',navigateNext);
				  $("#back").unbind('click',navigateBack).bind('click',navigateBack);
				  
                  clearInterval(interval);
				  
				  interval = setInterval(function() {
			navigate("next");
		}, slideshowSpeed);
				  
				  
				  
				  
				 }

			);
					
			
			// Hide the header text
			$("#headertxt").css({"display" : "none"});
			
			// Set the new header text
			$("#firstline").html(photoObject.firstline);
			$("#pictureduri").html(photoObject.secondline);
				
			// Fade out the current container
			// and display the header text when animation is complete
			$("#headerimg" + currentContainer).fadeOut(function() {
				setTimeout(function() {
					$("#headertxt").slideDown(500);
					Cufon.replace('#firstline', {fontFamily:'Nevis'}); 
				}, 1000);
			});
			
           



	 
	    }); 
		
		
	};
	
	var stopAnimation = function() {
		$("#headerimg" + activeContainer).stop();
		// Clear the interval
		clearInterval(interval);
	};
	
	// We should statically set the first image
	navigate("next");
	
	// Start playing the animation
	interval = setInterval(function() {
		navigate("next");
	}, slideshowSpeed);
	
});