var mp_thickbox = {

	aclass : 'a.thickbox',
	tb     : '',

	init : function() {
		mp_thickbox.tb = mp_thickbox.dims();
		mp_thickbox.tb.click(function() { mp_thickbox.clicked(); } );
		jQuery(window).resize( function() { mp_thickbox.dims(); } );
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

		return jQuery(mp_thickbox.aclass).each( function() 
		{
			var href = jQuery(this).attr('href');
			if ( ! href ) return;
			href = href.replace(/&width=[0-9]+/g, '');
			href = href.replace(/&height=[0-9]+/g, '');
			jQuery(this).attr( 'href', href + '&width=' + ( nW ) + '&height=' + ( nH ) );
		});
	},

	clicked : function() {
		jQuery('#TB_title').css({'background-color':'#222','color':'#cfcfcf'});
		jQuery('#TB_closeAjaxWindow').css({'float':'right'});
		jQuery('#TB_ajaxWindowTitle').css({'float':'left'});
		jQuery('#TB_iframeContent').width('100%');
		mp_thickbox.dims();
		return false;
	}
}
jQuery(document).ready( function() { mp_thickbox.init(); } );