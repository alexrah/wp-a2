// write

var mp_write = {

	submit : {	stamp : null,
			button : null
	},

	init : function() {
		// close postboxes that should be closed
		jQuery('.if-js-closed').removeClass('if-js-closed').addClass('closed');

		// postboxes
		postboxes.add_postbox_toggles(MP_AdminPageL10n.screen);

		//uploader
		mp_fileupload.init();

		//autosave
		autosave.init();
		if ( jQuery('#title').val() == '' )
			jQuery('#title').siblings('#title-prompt-text').css('visibility', '');
		jQuery('#title-prompt-text').click(function(){
			jQuery(this).css('visibility', 'hidden').siblings('#title').focus();
		});
		jQuery('#title').blur( function() {
			if (this.value == '') jQuery(this).siblings('#title-prompt-text').css('visibility', '');
			if ( (jQuery("#mail_id").val() > 0) || (jQuery("#title").val().length == 0) ) return; 
			autosave.main(); 
		}).focus(function(){
			jQuery(this).siblings('#title-prompt-text').css('visibility', 'hidden');
		}).keydown(function(e){
			jQuery(this).siblings('#title-prompt-text').css('visibility', 'hidden');
			jQuery(this).unbind(e);
		});

		// submitdiv : save context
		mp_write.submit.stamp  = jQuery('#timestamp').html();
		mp_write.submit.button = jQuery('#publish').val();
		mp_write.submit.name   = jQuery('#publish').attr('name');
		mp_write.submit.save_post = jQuery('#save-post').hasClass('hidden');

		// timestamp
		jQuery('#timestampdiv').siblings('a.edit-timestamp').click(function() {
			if (jQuery('#timestampdiv').is(":hidden")) {
				jQuery('#timestampdiv').slideDown("normal");
				jQuery(this).hide();
			}
			return false;
		});
		jQuery('.cancel-timestamp', '#timestampdiv').click(function() {
			jQuery('#timestampdiv').slideUp("normal");
			jQuery('#mm').val(jQuery('#hidden_mm').val());
			jQuery('#jj').val(jQuery('#hidden_jj').val());
			jQuery('#aa').val(jQuery('#hidden_aa').val());
			jQuery('#hh').val(jQuery('#hidden_hh').val());
			jQuery('#mn').val(jQuery('#hidden_mn').val());
			jQuery('#timestampdiv').siblings('a.edit-timestamp').show();
			mp_write.updateText();
			return false;
		});
		jQuery('.save-timestamp', '#timestampdiv').click(function () {
			if ( mp_write.updateText() ) {
				jQuery('#timestampdiv').slideUp("normal");
				jQuery('#timestampdiv').siblings('a.edit-timestamp').show();
			}
			return false;
		}); 

		// theme
		jQuery('#themediv').siblings('a.edit-theme').click(function() {
			if (jQuery('#themediv').is(":hidden")) {
				jQuery('#themediv').slideDown("normal");
				jQuery(this).hide();
			}
			return false;
		});
		jQuery('.cancel-theme', '#themediv').click(function() {
			jQuery('#themediv').slideUp("normal");
			jQuery('#theme').val(jQuery('#hidden_theme').val());
			var val = jQuery('#theme >option').filter(':selected').text();
			jQuery('#span_theme').html(val);
			jQuery('#themediv').siblings('a.edit-theme').show();
			return false;
		});
		jQuery('.save-theme', '#themediv').click(function () {
			jQuery('#themediv').slideUp("normal");
			var val = jQuery('#theme >option').filter(':selected').text();
			jQuery('#span_theme').html(val);
			jQuery('#themediv').siblings('a.edit-theme').show();
			return false;
		}); 

		// custom fields
		jQuery('#the-list').wpList({	
			addAfter: function( xml, s ) {
				jQuery('table#list-table').show();
			}, 
			addBefore: function( s ) {
				s.data += '&mail_id=' + jQuery('#mail_id').val(); 
				return s;
			}
		});

		// control form
		jQuery('form#writeform').submit( function() {
			return mp_write.control();
		});
	},

	convertDateToString : function(iDate) {
		var oDate   = iDate.getFullYear() +'-';
		oDate  += ((1 + iDate.getMonth()) < 10) ? '0'+ (1 + iDate.getMonth()) +'-' : (1 + iDate.getMonth()) +'-';
		oDate  += (iDate.getDate() < 10)         ? '0'+ iDate.getDate()  +' '       : iDate.getDate() +' ';
		oDate  += (iDate.getHours()< 10)         ? '0'+ iDate.getHours() +':'       : iDate.getHours() +':';
		oDate  += (iDate.getMinutes()<10)        ? '0'+ iDate.getMinutes()          : iDate.getMinutes();
		return oDate;
	},

	updateText : function() {

		var aa = jQuery('#aa').val(),
		    mm = jQuery('#mm').val(), 
		    jj = jQuery('#jj').val(), 
		    hh = jQuery('#hh').val(), 
		    mn = jQuery('#mn').val();
		var originalDate = mp_write.convertDateToString(new Date( jQuery('#hidden_aa').val(), jQuery('#hidden_mm').val() -1, jQuery('#hidden_jj').val(), jQuery('#hidden_hh').val(), jQuery('#hidden_mn').val() ));
		var currentDate  = mp_write.convertDateToString(new Date( jQuery('#cur_aa').val(), jQuery('#cur_mm').val() -1, jQuery('#cur_jj').val(), jQuery('#cur_hh').val(), jQuery('#cur_mn').val() ));
		var attemptedDate = mp_write.convertDateToString(new Date( aa, mm - 1, jj, hh, mn ));

	// attemptedDate is a date ?
		var controlDate   = aa +'-'+  mm +'-'+ jj +' '+ hh +':'+ mn;

		if ( attemptedDate == controlDate )
		{
			jQuery('.timestamp-wrap', '#timestampdiv').removeClass('form-invalid');
		}
		else
		{
			jQuery('.timestamp-wrap', '#timestampdiv').addClass('form-invalid');
			return false;
		}

		if ( attemptedDate == originalDate )
		{
			jQuery('#timestamp').html(mp_write.submit.stamp);
			jQuery('#publish').val(mp_write.submit.button);
			jQuery('#publish').attr('name', mp_write.submit.name);
			if (mp_write.submit.save_post) jQuery('#save-post').addClass('hidden');
			else 					 jQuery('#save-post').removeClass('hidden');

			return true;
		} 

		if ( attemptedDate <= currentDate )
		{
			attemptedDate = currentDate;

			jQuery('#aa').val(jQuery('#cur_aa').val());
			jQuery('#mm').val(jQuery('#cur_mm').val()); 
			jQuery('#jj').val(jQuery('#cur_jj').val()); 
			jQuery('#hh').val(jQuery('#cur_hh').val()); 
			jQuery('#mn').val(jQuery('#cur_mn').val());

			jQuery('#timestamp').html(MP_AdminPageL10n.sendImmediately);
			jQuery('#publish').val(MP_AdminPageL10n.send);
			jQuery('#publish').attr('name', MP_AdminPageL10n.name_send);
			jQuery('#save-post').removeClass('hidden');

			return true;
		}

		jQuery('#timestamp').html(
			MP_AdminPageL10n.sendOnFuture + ' <b>' +
			jQuery('option[value=' + jQuery('#mm').val() + ']', '#mm').text() + ' ' +
			jj + ', ' +
			aa + ' @ ' +
			hh + ':' +
			mn + '</b> '
		);
		jQuery('#publish').val( MP_AdminPageL10n.schedule );
		jQuery('#publish').attr('name', MP_AdminPageL10n.name_send);
		jQuery('#save-post').addClass('hidden');

		return true;
	},

	control : function() {
		var err = 0;

		// email or list
		document.writeform.toemail.style.border='1px solid #C6D9E9';
		document.writeform.to_list.style.border='1px solid #C6D9E9';

		if (mp_write.is_empty(document.writeform.toemail.value) && (mp_write.is_empty(document.writeform.to_list.value)))
		{
			document.writeform.toemail.style.border='1px solid #f00';
			document.writeform.to_list.style.border='1px solid #f00';
			err++;
		}
		else
		{
			if (!mp_write.is_empty(document.writeform.toemail.value))
			{
				if (!mp_write.is_email(document.writeform.toemail.value))
				{
					document.writeform.toemail.style.border='1px solid #f00';
					err++;
				}
			}
		}
		if ( err == 0 )	return true;

		alert(MP_AdminPageL10n.errmess);
		return false;
	},

	is_empty : function(t) { return (t.length == 0); },
	is_email : function(m) { var pattern = /^[_a-zA-Z0-9-]+(\.[_a-zA-Z0-9-]+)*@[a-z0-9-]+(\.[a-z0-9-]+)*(\.[a-z]{2,4})$/; return pattern.test(m); },
	is_numeric : function(n) { var pattern = /^[0-9]$/; return pattern.test(n); }
}
jQuery(document).ready( function() { mp_write.init(); });