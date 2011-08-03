$(document).ready(function(){
	
	
	
	$("#menuwrap").animate( {"top":"60px"} , 1000 , "easeOutCirc" );
	
	
	//Cufon
	Cufon.replace('#handwritten1, #handwritten2, .handwritten3, #about_the_author .about_heading',{fontFamily:'Pacifico'}); 
	
	Cufon.replace('h1, h2, h3, h4', {hover: true});  
	Cufon.replace('#footer h3, .sidebar h3, .bold_title', {hover: true});  
	Cufon.replace('.kwick_title span');   
	Cufon.replace('.frame .caption'); 
	Cufon.replace('.dropcap1, .dropcap2, .dropcap3'); 
	Cufon.replace('#nav > li > a',{hover: true}); 
	Cufon.replace('.button',{hover: true}); 
	Cufon.replace('.bold_text'); 
	Cufon.replace('.toggle_title'); 
	Cufon.replace('#grid_slider .sub_grid li .caption .caption_title'); 
	Cufon.replace('.reply',{hover: true}); 

	Cufon.now();

	
	$("#nav li").append('<span></span>');

	 	
	$(" #nav ul ").css({display: "none"}); // Opera Fix
    $(" #nav li").hover(function(){
		$(this).find('ul:first').css({visibility: "visible",display: "none"}).slideDown(400);
		},function(){
		$(this).find('ul:first').css({visibility: "hidden"});
		});
		
		
	//menu padding animation
	$('#nav li ul li').hover(function() {
		$(this).children('a').stop().css({color:"white"}).animate({ textIndent:"7px" }, 200);
		$(this).children('span').stop().animate({ width:"190px" }, 350);
	}, function() {
		$(this).children('a').stop().css({color:"#707070"}).animate({ textIndent:"0px" }, 200);
		$(this).children('span').stop().animate({ width:"0px" }, 350);
	});	
	
	$('#nav > li').hover(function() {
		$(this).children('span').stop().animate({ width:"100%" }, 350);
		$(this).children('a').css({color:"white"});
	}, function() {
		$(this).children('span').stop().animate({ width:"0%" }, 350);
		
		$('#nav > li').children('a').css({color:"#707070"});
		
		Cufon.replace($(this).children('a'),{hover: true}); 
	});	
			
			
	//tabs
	$(".tabs_container").each(function(){
		$("ul.tabs",this).tabs("div.panes > div", {tabs:'li',effect: 'fade', fadeOutSpeed: -400});
	});
	
	//accordion
	$.tools.tabs.addEffect("slide", function(i, done) {
		this.getPanes().slideUp();
		this.getPanes().eq(i).slideDown(function()  {
			done.call();
		});
	});
	$(".accordion").tabs("div.pane", {tabs: '.tab', effect: 'slide'});
	
	//toggle
	$(".toggle_title").toggle(
		function(){
			$(this).addClass('toggle_active');
			$(this).siblings('.toggle_content').slideDown("fast");
		},
		function(){
			$(this).removeClass('toggle_active');
			$(this).siblings('.toggle_content').slideUp("fast");
		}
	);

	//image hover
    $(".frame a.icon_zoom,.frame a.icon_play,.frame a.icon_doc").append("<span class='image_overlay'></span>");
	
	var image_hover = function(parent){
		$(".frame a.icon_zoom,.frame a.icon_play,.frame a.icon_doc").hover(function(){ 
			$(this).children('.image_overlay').stop().css("display","block").fadeTo(400, 0.6); 
		},function(){
			$(this).children('.image_overlay').stop().fadeTo(400, 0); 
		});	
	}
	image_hover(document);
	
	//caption hover
	$('.frame, .thumbnail').hover(function() {
		$(this).children('.caption').stop(true,true).fadeIn(250);
		
 	}, function() {
		$(this).children('.caption').stop(true,true).fadeOut(250);
	});	
	
	//thumbnail hover
	if(!$.browser.msie) $('.thumbnail','.recent_posts').hover(function() {
		$(this).children('.thumbnail_shadow').stop(true,true).fadeTo(400, 0.5); 
 	}, function() {
		$(this).children('.thumbnail_shadow').stop(true,true).fadeOut(250);
	});	
	
	//blogcol teaser animation
	$('.blogcol li','#main').hover(function() {
		var x = $(this).find('.teaser').children('a');
		if(!x.hasClass('preloader')) x.children('img').stop().animate({ top: "-22"}, 200);
	
	}, function() {
		var x = $(this).find('.teaser').children('a');
		if(!x.hasClass('preloader')) x.children('img').stop().animate({ top: "0"}, 200);          
	});
	
	$('.blogcol li .more').hover(function() {
       $(this).children('span').stop().animate({'width':'190px'},500);
	  
	}, function() {
       $(this).children('span').stop().animate({'width':'0px'},500);
	});
	
	$('.blogpost').hover(function() {
       $(this).find('.handwritten3').stop(true,true).fadeIn(300);
	  
	}, function() {
        $(this).find('.handwritten3').stop(true,true).fadeOut(300);
	});
	
	
	//metabox hover
	$('.metabox .subheading').hover(function() {
		$(this).children('a').css({color: "#ffffff"});
        $(this).children('span').stop().animate({width:"100%"},400,'easeOutQuint');
	}, function() {
		$(this).children('a').css({color: "#999999"});
        $(this).children('span').stop().animate({width:"0%"},400);  
	});
	
	//social icons hover
	$('.widget_social img').hover(function() {
        $(this).stop().animate({opacity:"0.7",top:"-4px"},200);
	}, function() {
        $(this).stop().animate({opacity:"1",top:"0px"},200);    
	});

	
	//lightbox function
	var lightbox_function = function(parent){
		$("a.lightbox[href*='http://www.vimeo.com/']").each(function() {
			$(this).attr('href',this.href.replace("www.vimeo.com/", "player.vimeo.com/video/"));
		});
		$("a.lightbox[href*='http://vimeo.com/']").each(function() {
			$(this).attr('href',this.href.replace("vimeo.com/", "player.vimeo.com/video/"));
		});
		$("a.lightbox[href*='http://www.youtube.com/watch?']").each(function() {
			$(this).attr('href',this.href.replace(new RegExp("watch\\?v=", "i"), "v/"));
		});
		$("a.lightbox[href*='http://player.vimeo.com/']").each(function() {
			$(this).addClass("fancyVimeo").removeClass('lightbox');
		});
		$("a.lightbox[href*='http://www.youtube.com/v/']").each(function() {
			$(this).addClass("fancyVideo").removeClass('lightbox');
		});
		$("a.lightbox[href$='.swf']").each(function() {
			$(this).addClass("fancyVideo").removeClass('lightbox');
		});
		$(".fancyVimeo").each(function(){
			$(this).colorbox({
				opacity:0.7,
				innerWidth:700,
				innerHeight:450,
				iframe:true,
				scrolling:false,
				current:"{current} of {total}",
				onComplete: function(){
					$("#cboxClose").css("visibility", "hidden");
					$("#colorbox").addClass('withVideo');
					Cufon.replace('#cboxTitle');
					
				}
			});
		});
	
		$(".fancyVideo").each(function(){
			var lightbox_html = $.flash.create({swf:this.href,width:700,height:450,play:true});
			$(this).colorbox({
				opacity:0.7,
				innerWidth:700,
				innerHeight:450,
				html:lightbox_html,
				scrolling:false,
				current:"{current} of {total}",
				onComplete: function(){
					$("#cboxClose").css("visibility", "hidden");
					$("#colorbox").addClass('withVideo');
					Cufon.replace('#cboxTitle');
				}
			});
		});
		
		
		$(".lightbox").colorbox({
			opacity:0.7,
			maxWidth:"90%",
			maxHeight:"90%",
			current:"{current} of {total}",
			onComplete: function(){
				$("#cboxClose").css("visibility", "visible");
				$("#colorbox").removeClass('withVideo');
				Cufon.replace('#cboxTitle');
			}
		});
	
	}
	lightbox_function(document);
		
	$('.image_no_link').click(function(){
		return false;		
	});
	
	
	//list posts width animation
	if(!$.browser.msie) $('.recent_posts li .thumbnail').hover(function() {
		$(this).stop(true,true).animate({ width: "100"}, 200);
 	}, function() {
		$(this).stop(true,true).animate({ width: "85"}, 200);
	});	
	
	
	//quicksand for portfolio
    $(".portfolios.sortable").each(function(){
		var $preferences = {
				name : 'image',
				duration: 500,
				easing: 'linear',
				attribute: 'data-id'
			};
		var $list = $('ul',this);
		
		var $data = $list.clone();
		
		var $column;
		if($list.is('.portfolio_one_column')){
			$column = 1;
		}else if($list.is('.portfolio_two_columns')){
			$column = 2;
		}else if($list.is('.portfolio_three_columns')){
			$column = 3;
		}else if($list.is('.portfolio_four_columns')){
			$column = 4;
		}
		
		$('.sortable_categories a',this).click(function(e){
			$('.sortable_categories a').css({backgroundColor: "white"});
            $(this).css({backgroundColor: "#ededed"});
			if ($.browser.msie && parseInt($.browser.version, 10) < 9) {
				$list.find('.image_shadow').css('visibility','hidden');
				$data.find('.image_shadow').css('visibility','hidden');
			}
			if($(this).attr('data-value') == 'all'){
				$sorted_data = $data.find('li');
			}else{
				$sorted_data = $data.find('li[data-type*='+$(this).attr('data-value')+']');
			}
			$sorted_data.filter('.last').removeClass('last');
			$sorted_data.each(function(i){
				if(((i+1) % $column) == 0 && $column != 1){
					$(this).addClass('last');
				}
			});
			var $callback = function(){
				if ($.browser.msie && parseInt($.browser.version, 10) < 9 && parseInt($.browser.version, 10) > 6) {
					$list.find('.image_shadow').css('visibility','visible');
				}				
				lightbox_function($list);
				image_hover($list);
				if (typeof Cufon !== "undefined"){
					Cufon.replace('.portfolio_title h3');
				}
			};
			$list.quicksand($sorted_data,$preferences,$callback);
			if ($.browser.msie && parseInt($.browser.version, 10) < 7) {
				$callback();
			}
			e.preventDefault();  
		});
	});
	
	
	///contact form
	if(jQuery.tools.validator != undefined){
			jQuery.tools.validator.addEffect("contact_form", function(errors, event) {
				$.each(errors, function(index, error) {
					var input = error.input;
					input.addClass('invalid');
				});
			}, function(inputs)  {
				inputs.removeClass('invalid');
			});

			jQuery('.contact_form').validator({effect:'contact_form'}).submit(function(e) {
				var form = jQuery(this);
				if (!e.isDefaultPrevented()) {
					jQuery.post(this.action,{
						'to':jQuery('input[name="contact_to"]').val(),
						'name':jQuery('input[name="contact_name"]').val(),
						'email':jQuery('input[name="contact_email"]').val(),
						'content':jQuery('textarea[name="contact_content"]').val()
					},function(data){
						form.fadeOut('fast', function() {
							jQuery(this).siblings('.success').show();
						});
					});
					e.preventDefault();
				}
			});
	}
	
	//portfollio animation
	$(".portfolio_list").each(function(){
	      
		var list=this;			
	    $('.portfolio_item',list).hover(function() {
			
			$('.portfolio_item',list).not('.portfolio_item_'+$(this).attr('class').substr(30)).children('.frame').stop().animate({ height: '0px' }, 500, 'easeOutQuart');
			$('.portfolio_item_'+$(this).attr('class').substr(30),list).children('.frame').stop().animate({ height: '152px' }, 500, 'easeOutQuart');
				
 	}, function() {

	});	
	
	});
	
	
	

		
		
		
	
});
	
	
	

		

		
		
		
				

		


	


  



