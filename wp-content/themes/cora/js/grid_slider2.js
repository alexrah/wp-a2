$(document).ready(function() {
	
	
	Cufon.replace("#grid_slider2 .caption_title",{hover:true});
	
	
	//preload
	if(!$.browser.msie) $('#grid_slider2 img').each(function(){
		            
		var imageObj = new Image();
		var img=$(this);
		$(imageObj).attr('src',$(this).attr('src')).load(function(){  
			
			  img.fadeIn(500);
		});
					
	});
	
    
	var cols=Math.floor($("#grid_slider2").children("li").size()/3);
	if(cols*3!=$("#grid_slider2").children("li").size()) cols++;
    $("#grid_slider2").css({width:cols*302});




	//captions hover type 1
	$("#grid_slider2 li.top_grid").hover(function(){
		
		if( !$(this).is(':animated') ) {
		
			this.cx=parseInt($(this).css('left'), 10);
			this.cheight=parseInt($(this).css('height'), 10);

			
			$(this).css({zIndex:'5'}).stop().animate({left:this.cx-30,height:this.cheight+30,width:360},500,
				function(){
					$(this).find('.caption').stop(true,true).fadeIn(400);
			    }			
			);
		}
	}, function() {				
        $(this).find('.caption').stop(true,true).fadeOut(400);
		$(this).css({zIndex:'1'}).stop().animate({left:this.cx,top:this.cy,height:this.cheight,width:300},500);
	});
	
    //captions hover type 2
	$("#grid_slider2 li.middle_grid").hover(function(){
		
		if( !$(this).is(':animated') ) {
		
			
			this.cx=parseInt($(this).css('left'), 10);
			this.cy=parseInt($(this).css('top'), 10);
			this.cheight=parseInt($(this).css('height'), 10);
			
			$(this).css({zIndex:'5'}).stop().animate({left:this.cx-30,top:this.cy-30,height:this.cheight+60,width:360},500,
				function(){
						$(this).find('.caption').stop(true,true).fadeIn(400);
				}
			);
		}
	}, function() {				
        $(this).find('.caption').stop(true,true).fadeOut(400);
		$(this).css({zIndex:'1'}).stop().animate({left:this.cx,top:this.cy,height:this.cheight,width:300},500);
	});
	
	//captions hover type 3
	$("#grid_slider2 li.bottom_grid").hover(function(){
		
		if( !$(this).is(':animated') ) {
	
			this.cx=parseInt($(this).css('left'), 10);
			this.cy=parseInt($(this).css('top'), 10);
			this.cheight=parseInt($(this).css('height'), 10);
			
			$(this).css({zIndex:'5'}).stop().animate({left:this.cx-30,top:this.cy-30,height:this.cheight+30,width:360},500,
			
				function(){
						$(this).find('.caption').stop(true,true).fadeIn(400);
				}
			
			);
		}
	}, function() {				
        $(this).find('.caption').stop(true,true).fadeOut(400);
		$(this).css({zIndex:'1'}).stop().animate({left:this.cx,top:this.cy,height:this.cheight,width:300},500);
	});
	
	
		
	
	var grid_width=cols*302;
	
	if(grid_width-$(window).width()<0) $('#grid_slider2').css({position:'relative',margin:'0 auto'});
	else{
	
	var xpos=0;
	var ret=false;
	
	var navigate = function() {
		
        xpos-=302;
		
		if(ret==true) { 
			$('#grid_slider2').stop().animate({left:0},1500,'easeOutCirc'); ret=false; xpos=0; }
		else {
			if(grid_width-$(window).width()+xpos<0) { 
				$('#grid_slider2').stop().animate({left:-(grid_width-$(window).width())},800,'easeOutCirc'); 
				ret=true;
			}
		   else $('#grid_slider2').stop().animate({left:xpos},800,'easeOutCirc');
		}
		
	};
	
	var navigateLeft = function() {
			
		if(xpos==0){
			$('#grid_slider2').stop().animate({left:-(grid_width-$(window).width())},800,'easeOutCirc'); 
			xpos=-302*(cols-4);
		}
		else{
			if(grid_width-$(window).width()+xpos<0) {
				xpos+=302;
				$('#grid_slider2').stop().animate({left:xpos},800,'easeOutCirc'); 
				ret=false;
		    }
		    else{
			xpos+=302;
		    $('#grid_slider2').stop().animate({left:xpos},800,'easeOutCirc'); 
		    }
		}
		
	};
	
	
	// Start playing the animation
	interval = setInterval(function() {
		navigate();
	}, 3000);
	
	
	$("#grid_slider2").hover(function(){
		clearInterval(interval);
	}, function() {		
	    clearInterval(interval);		
		// Start playing the animation
		interval = setInterval(function() {
			navigate();
		}, 3000);

	});
	
	
	
	$("#grid_container2").hover(function(){	
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