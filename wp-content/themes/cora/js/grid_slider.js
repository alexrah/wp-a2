$(document).ready(function() {
	
	
	//preload
	if(!$.browser.msie) $('#grid_slider img').each(function(){
		            
		var imageObj = new Image();
		var img=$(this);
		$(imageObj).attr('src',$(this).attr('src')).load(function(){  
			
			  img.fadeIn(500);
		});
					
	});
	
	
	
    $("#grid_slider").css({width:$("#grid_slider").children("li").size()*302});


	//captions hover
	$(".sub_grid li").hover(function(){
		$(this).find('.caption').stop(true,true).fadeIn(400);
	}, function() {				
        $(this).find('.caption').stop(true,true).fadeOut(400);
	});
	
	
    $(".sub_grid li").mousemove(function(e){
   
		var offset = $(this).offset();
		var X = (e.pageX - offset.left);
		var Y = (e.pageY - offset.top);
				
		var height=parseInt($(this).css('height'), 10)
		
		var xc=((X-300)/300)*(-30)-30;
		var xy=((Y-height)/height)*(-30)-30;
		
		var img_this=$(this).find('img');
		
		if(img_this.css('opacity')==1)  img_this.stop().animate({left:xc,top:xy},300,'easeOutCirc');
	});
	
	
	
	var cols=$("#grid_slider").children("li").size();
	var grid_width=cols*302;
	
	if(grid_width-$(window).width()<0) $('#grid_slider').css({position:'relative',margin:'0 auto'});
	else{
	
	var xpos=0;
	var ret=false;
	
	var navigate = function() {
		
        xpos-=302;
		
		if(ret==true) { 
			$('#grid_slider').stop().animate({left:0},1500,'easeOutCirc'); ret=false; xpos=0; }
		else {
			if(grid_width-$(window).width()+xpos<0) { 
				$('#grid_slider').stop().animate({left:-(grid_width-$(window).width())},800,'easeOutCirc'); 
				ret=true;
			}
		   else $('#grid_slider').stop().animate({left:xpos},800,'easeOutCirc');
		}
		
	};
	
	var navigateLeft = function() {
			
		if(xpos==0){
			$('#grid_slider').stop().animate({left:-(grid_width-$(window).width())},800,'easeOutCirc'); 
			xpos=-302*(cols-4);
		}
		else{
			if(grid_width-$(window).width()+xpos<0) {
				xpos+=302;
				$('#grid_slider').stop().animate({left:xpos},800,'easeOutCirc'); 
				ret=false;
		    }
		    else{
			xpos+=302;
		    $('#grid_slider').stop().animate({left:xpos},800,'easeOutCirc'); 
		    }
		}
		
	};
	
	
	// Start playing the animation
	interval = setInterval(function() {
		navigate();
	}, 3000);
	
	
	$("#grid_slider").hover(function(){
		clearInterval(interval);
	}, function() {		
	    clearInterval(interval);		
		// Start playing the animation
		interval = setInterval(function() {
			navigate();
		}, 3000);

	});
	
	
	
	$("#grid_container").hover(function(){	
		$('#nav_right,#nav_left').fadeTo(400, 0.8); 
	}, function() {				
		$('#nav_right,#nav_left').fadeTo(0, 0); 
	});
	
	
	$('#nav_right').click(function() {
		clearInterval(interval);
        navigate();
    });


	$('#nav_left').click(function() {
		clearInterval(interval);
        navigateLeft();
    });


	$("#nav_right").hover(function(){		
		$(this).fadeTo(400, 1); 
		clearInterval(interval);
	}, function() {	
	
		$(this).fadeTo(400, 0.8); 
		clearInterval(interval);			
		// Start playing the animation
		interval = setInterval(function() {
				navigate();
		}, 3000);
	});
	
	$("#nav_left").hover(function(){	
	    clearInterval(interval);	
		$(this).fadeTo(400, 1); 
	}, function() {		
		$(this).fadeTo(400, 0.8); 		
		// Start playing the animation
		clearInterval(interval);
		interval = setInterval(function() {
			navigate();
		}, 3000);
	});
	
	}
	
	
	
});