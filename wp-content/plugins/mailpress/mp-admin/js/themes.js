//themes

var mp_themes = {

	aclass : 'a.thickbox',
	tb     : null,

	init : function() {
		mp_themes.tb = mp_themes.dims();
		mp_themes.tb.click(function() { mp_themes.clicked(this); } );
		jQuery(window).resize( function() { mp_themes.dims(); } );
	},

	dims : function() {
		var thickboxW = jQuery('#TB_window');
		var H = jQuery(window).height();
		var W = jQuery(window).width();

		var nW = parseInt(W *.8);
		var nH = parseInt(H *.9);

		if ( thickboxW.size() ) 
		{
			thickboxW.width( nW ).height( nH );
			jQuery('#TB_iframeContent').width('100%').height('100%');
			thickboxW.css({'margin-left': '-' + parseInt( nW / 2 ) + 'px'});
			if ( typeof document.body.style.maxWidth != 'undefined' )
				thickboxW.css({'top':'30px','margin-top':'0'});
		};

		return jQuery(mp_themes.aclass).each( function() 
		{
			var href = jQuery(this).parents('.available-theme').find('.previewlink').attr('href');
			if ( ! href ) return;
			href = href.replace(/&width=[0-9]+/g, '');
			href = href.replace(/&height=[0-9]+/g, '');
			jQuery(this).attr( 'href', href + '&width=' + ( nW ) + '&height=' + ( nH ) );
		});
	},

	clicked : function(_this) {
		var href = jQuery(_this).parents('.available-theme').find('.activatelink');
		var url  = href.attr('href');
		var text = href.html();

		jQuery('#TB_title').css({'background-color':'#222','color':'#cfcfcf'});
		jQuery('#TB_closeAjaxWindow').css({'float':'right'});
		jQuery('#TB_ajaxWindowTitle').css({'float':'left'}).html('&nbsp;<a href="' + url + '" target="_top" class="tb-theme-preview-link">' + text + '</a>');
		jQuery('#TB_iframeContent').width('100%');
		mp_themes.dims();
		return false;
	}
}
jQuery(document).ready( function() { mp_themes.init(); } );