var shortcode = {
	
	init:function(){
		jQuery('.shortcode_selector select').val('');
		jQuery('.shortcode_selector select').change(function(){
			jQuery(".shortcode_wrap").hide();
			if(this.value !=''){
				var wrap = jQuery("#shortcode_"+this.value).show();
				if(wrap.children('.sub_shortcode_wrap').size() == 0){
					wrap.find('.toggle-button:checkbox').show();
				}
			}
		});
		
		jQuery('#shortcode_send').click(function(){
			shortcode.sendToEditor();
		});
		jQuery('.shortcode_sub_selector select').val('');
		jQuery('.shortcode_sub_selector select').change(function(){
			jQuery(this).closest('.shortcode_wrap').children('.sub_shortcode_wrap').hide();
			if(this.value !=''){
				jQuery("#sub_shortcode_"+this.value).show().find('.toggle-button:checkbox').show();
			}
		});
		var tabs_number = jQuery('[name="sc_tabs_number"]').val();
		jQuery('#shortcode_tabs table tr').each(function(i){
			if(i>(1+tabs_number*2)){
				jQuery(this).hide();
			}else{
				jQuery(this).show();
			}
		});
		jQuery('[name="sc_tabs_number"]').change(function(){
			var tabs_number = jQuery(this).val();
			jQuery('#shortcode_tabs table tr').each(function(i){
				if(i>(tabs_number*2)){
					jQuery(this).hide();
				}else{
					jQuery(this).show();
				}
			});
		});
		
		jQuery('#shortcode_accordion table tr').each(function(i){
			if(i>(tabs_number*2)){
				jQuery(this).hide();
			}else{
				jQuery(this).show();
			}
		});
		jQuery('[name="sc_accordion_number"]').change(function(){
			var tabs_number = jQuery(this).val();
			jQuery('#shortcode_accordion table tr').each(function(i){
				if(i>(tabs_number*2)){
					jQuery(this).hide();
				}else{
					jQuery(this).show();
				}
			});
		});
	},
	
	generate:function(){
		var type = jQuery('.shortcode_selector select').val();
		switch( type ){
		case 'columns':
			var type = shortcode.getVal('columns', 'type');
			if(type != ''){
				return '\n['+type+']\n'+shortcode.getVal('columns', 'content')+'\n[/'+type+']\n';
			}else{
				return '';
			}
			break;
		case 'layouts':
			var sub_type = shortcode.getVal('layouts','selector');
			switch(sub_type){
			case 'one_half_layout':
				return '\n[one_half]\n'+shortcode.getVal('layouts', 'one_half_layout', '1')+'\n[/one_half]\n\n[one_half_last]\n'+shortcode.getVal('layouts', 'one_half_layout', '2')+'\n[/one_half_last]\n';
				break;
			case 'one_third_layout':
				return '\n[one_third]\n'+shortcode.getVal('layouts', 'one_third_layout', '1')+'\n[/one_third]\n\n[one_third]\n'+shortcode.getVal('layouts', 'one_third_layout', '2')+'\n[/one_third]\n\n[one_third_last]\n'+shortcode.getVal('layouts', 'one_third_layout', '3')+'\n[/one_third_last]\n';
				break;
			case 'one_fourth_layout':
				return '\n[one_fourth]\n'+shortcode.getVal('layouts', 'one_fourth_layout', '1')+'\n[/one_fourth]\n\n[one_fourth]\n'+shortcode.getVal('layouts', 'one_fourth_layout', '2')+'\n[/one_fourth]\n\n[one_fourth]\n'+shortcode.getVal('layouts', 'one_fourth_layout', '3')+'\n[/one_fourth]\n\n[one_fourth_last]\n'+shortcode.getVal('layouts', 'one_fourth_layout', '4')+'\n[/one_fourth_last]\n';
				break;
			case 'one_fifth_layout':
				return '\n[one_fifth]\n'+shortcode.getVal('layouts', 'one_fifth_layout', '1')+'\n[/one_fifth]\n\n[one_fifth]\n'+shortcode.getVal('layouts', 'one_fifth_layout', '2')+'\n[/one_fifth]\n\n[one_fifth]\n'+shortcode.getVal('layouts', 'one_fifth_layout', '3')+'\n[/one_fifth]\n\n[one_fifth]\n'+shortcode.getVal('layouts', 'one_fifth_layout', '4')+'\n[/one_fifth]\n\n[one_fifth_last]\n'+shortcode.getVal('layouts', 'one_fifth_layout', '5')+'\n[/one_fifth_last]\n';
				break;
			case 'one_sixth_layout':
				return '\n[one_sixth]\n'+shortcode.getVal('layouts', 'one_sixth_layout', '1')+'\n[/one_sixth]\n\n[one_sixth]\n'+shortcode.getVal('layouts', 'one_sixth_layout', '2')+'\n[/one_sixth]\n\n[one_sixth]\n'+shortcode.getVal('layouts', 'one_sixth_layout', '3')+'\n[/one_sixth]\n\n[one_sixth]\n'+shortcode.getVal('layouts', 'one_sixth_layout', '4')+'\n[/one_sixth]\n\n[one_sixth]\n'+shortcode.getVal('layouts', 'one_sixth_layout', '5')+'\n[/one_sixth]\n\n[one_sixth_last]\n'+shortcode.getVal('layouts', 'one_sixth_layout', '6')+'\n[/one_sixth_last]\n';
				break;
			case 'one_third_two_third':
				return '\n[one_third]\n'+shortcode.getVal('layouts', 'one_third_two_third', '1')+'\n[/one_third]\n\n[two_third_last]\n'+shortcode.getVal('layouts', 'one_third_two_third', '2')+'\n[/two_third_last]\n';
				break;
			case 'two_third_one_third':
				return '\n[two_third]\n'+shortcode.getVal('layouts', 'two_third_one_third', '1')+'\n[/two_third]\n\n[one_third_last]\n'+shortcode.getVal('layouts', 'two_third_one_third', '2')+'\n[/one_third_last]\n';
				break;
			case 'one_fourth_three_fourth':
				return '\n[one_fourth]\n'+shortcode.getVal('layouts', 'one_fourth_three_fourth', '1')+'\n[/one_fourth]\n\n[three_fourth_last]\n'+shortcode.getVal('layouts', 'one_fourth_three_fourth', '2')+'\n[/three_fourth_last]\n';
				break;
			case 'three_fourth_one_fourth':
				return '\n[three_fourth]\n'+shortcode.getVal('layouts', 'three_fourth_one_fourth', '1')+'\n[/three_fourth]\n\n[one_fourth_last]\n'+shortcode.getVal('layouts', 'three_fourth_one_fourth', '2')+'\n[/one_fourth_last]\n';
				break;
			case 'one_fourth_one_fourth_one_half':
				return '\n[one_fourth]\n'+shortcode.getVal('layouts', 'one_fourth_one_fourth_one_half', '1')+'\n[/one_fourth]\n\n[one_fourth]\n'+shortcode.getVal('layouts', 'one_fourth_one_fourth_one_half', '2')+'\n[/one_fourth]\n\n[one_half_last]\n'+shortcode.getVal('layouts', 'one_fourth_one_fourth_one_half', '3')+'\n[/one_half_last]\n';
				break;
			case 'one_fourth_one_half_one_fourth':
				return '\n[one_fourth]\n'+shortcode.getVal('layouts', 'one_fourth_one_half_one_fourth', '1')+'\n[/one_fourth]\n\n[one_half]\n'+shortcode.getVal('layouts', 'one_fourth_one_half_one_fourth', '2')+'\n[/one_half]\n\n[one_fourth_last]\n'+shortcode.getVal('layouts', 'one_fourth_one_half_one_fourth', '3')+'\n[/one_fourth_last]\n';
				break;
			case 'one_half_one_fourth_one_fourth':
				return '\n[one_half]\n'+shortcode.getVal('layouts', 'one_half_one_fourth_one_fourth', '1')+'\n[/one_half]\n\n[one_fourth]\n'+shortcode.getVal('layouts', 'one_half_one_fourth_one_fourth', '2')+'\n[/one_fourth]\n\n[one_fourth_last]\n'+shortcode.getVal('layouts', 'one_half_one_fourth_one_fourth', '3')+'\n[/one_fourth_last]\n';
				break;
			case 'four_fifth_one_fifth':
				return '\n[four_fifth]\n'+shortcode.getVal('layouts', 'four_fifth_one_fifth', '1')+'\n[/four_fifth]\n\n[one_fifth_last]\n'+shortcode.getVal('layouts', 'four_fifth_one_fifth', '2')+'\n[/one_fifth_last]\n';
				break;
			case 'one_fifth_four_fifth':
				return '\n[one_fifth]\n'+shortcode.getVal('layouts', 'one_fifth_four_fifth', '1')+'\n[/one_fifth]\n\n[four_fifth_last]\n'+shortcode.getVal('layouts', 'one_fifth_four_fifth', '2')+'\n[/four_fifth_last]\n';
				break;
			case 'two_fifth_three_fifth':
				return '\n[two_fifth]\n'+shortcode.getVal('layouts', 'two_fifth_three_fifth', '1')+'\n[/two_fifth]\n\n[three_fifth_last]\n'+shortcode.getVal('layouts', 'two_fifth_three_fifth', '2')+'\n[/three_fifth_last]\n';
				break;
			case 'three_fifth_two_fifth':
				return '\n[three_fifth]\n'+shortcode.getVal('layouts', 'three_fifth_two_fifth', '1')+'\n[/three_fifth]\n\n[two_fifth_last]\n'+shortcode.getVal('layouts', 'three_fifth_two_fifth', '2')+'\n[/two_fifth_last]\n';
				break;
			case 'one_sixth_five_sixth':
				return '\n[one_sixth]\n'+shortcode.getVal('layouts', 'one_sixth_five_sixth', '1')+'\n[/one_sixth]\n\n[five_sixth_last]\n'+shortcode.getVal('layouts', 'one_sixth_five_sixth', '2')+'\n[/five_sixth_last]\n';
				break;
			case 'five_sixth_one_sixth':
				return '\n[five_sixth]\n'+shortcode.getVal('layouts', 'five_sixth_one_sixth', '1')+'\n[/five_sixth]\n\n[one_sixth_last]\n'+shortcode.getVal('layouts', 'five_sixth_one_sixth', '2')+'\n[/one_sixth_last]\n';
				break;
			case 'one_sixth_one_sixth_one_sixth_one_half':
				return '\n[one_sixth]\n'+shortcode.getVal('layouts', 'one_sixth_one_sixth_one_sixth_one_half', '1')+'\n[/one_sixth]\n\n[one_sixth]\n'+shortcode.getVal('layouts', 'one_sixth_one_sixth_one_sixth_one_half', '2')+'\n[/one_sixth]\n\n[one_sixth]\n'+shortcode.getVal('layouts', 'one_sixth_one_sixth_one_sixth_one_half', '3')+'\n[/one_sixth]\n\n[one_half_last]\n'+shortcode.getVal('layouts', 'one_sixth_one_sixth_one_sixth_one_half', '4')+'\n[/one_half_last]\n';
				break;
			}
			break;
		case 'typography':
			var sub_type = shortcode.getVal('typography','selector');
			switch(sub_type){
			case 'dropcap1':
			case 'dropcap2':
			case 'dropcap3':
			case 'dropcap4':
				var color = shortcode.getVal('typography',sub_type,'color');
				if(color !== ''){
					color = ' color="'+color+'"';
				}
				return '['+sub_type+color+']'+shortcode.getVal('typography',sub_type,'text')+'[/'+sub_type+']';
				break;
			case 'blockquote':
				var align = shortcode.getVal('typography','blockquote','align');
				var cite = shortcode.getVal('typography','blockquote','cite');
				if(align !== ''){
					align = ' align="'+align+'"';
				}
				if(cite !== ''){
					cite = ' cite="'+cite+'"';
				}
				return '[blockquote'+align+cite+']'+ shortcode.getVal('typography','blockquote','content') +'[/blockquote]\n';
				break;
			case 'bold_text':
			    return '[bold_text]'+ shortcode.getVal('typography','bold_text','content') +'[/bold_text]\n';
			    break;
			case 'pre_code':
				var s = shortcode.getVal('typography','pre_code','type');
				if(s == ''){
					s='code';
				}
				return '\n['+s+']\n'+shortcode.getVal('typography','pre_code','content')+'\n[/'+s+']\n';
			case 'styledlist':
				var style = shortcode.getVal('typography','styledlist','style');
				var color = shortcode.getVal('typography','styledlist','color');
				if(style !== ''){
					style= ' style="'+style+'"';
				}
				if(color !== ''){
					color = ' color="'+color+'"';
				}
				return '\n[list'+style+color+']\n'+shortcode.getVal('typography','styledlist','content')+'\n[/list]\n';
			case 'highlight':
				var t = shortcode.getVal('typography','highlight','color');
				if(t!==''){
					t = ' color="'+t+'"';
				}
				return '[highlight'+t+']'+shortcode.getVal('typography','highlight','content')+'[/highlight]';
			}
			break;
		case 'styledboxes':
			var sub_type = shortcode.getVal('styledboxes','selector');
			switch(sub_type){
			case 'messageboxes':
				var t = shortcode.getVal('styledboxes','messageboxes','type');
				if(t == ''){
					t='info';
				}
				return '\n['+t+']\n'+shortcode.getVal('styledboxes','messageboxes','content')+'\n[/'+t+']\n';
			case 'framed_box':
				var width = shortcode.getVal('styledboxes','framed_box','width');
				var height = shortcode.getVal('styledboxes','framed_box','height');
				var bgColor = shortcode.getVal('styledboxes','framed_box','bgColor');
				var textColor = shortcode.getVal('styledboxes','framed_box','textColor');
				var rounded = shortcode.getVal('styledboxes','framed_box','rounded');

				if(width!=0){
					width = ' width="'+width+'"';
				}else{
					width ='';
				}

				if(height!=0){
					height = ' height="'+height+'"';
				}else{
					height ='';
				}

				if(bgColor != ''){
					bgColor = ' bgColor="'+ bgColor +'"';
				}
				if(textColor != ''){
					textColor = ' textColor="'+ textColor +'"';
				}

				if(rounded==='yes'){
					rounded = ' rounded="true"';
				}else{
					rounded = '';
				}
				return '\n[framed_box'+width+height+bgColor+textColor+rounded+']\n'+shortcode.getVal('styledboxes','framed_box','content')+'\n[/framed_box]\n';
			case 'note_box':
				var title = shortcode.getVal('styledboxes','note_box','title');
				var align = shortcode.getVal('styledboxes','note_box','align');
				var width = shortcode.getVal('styledboxes','note_box','width');

				if(title != ''){
					title = ' title="'+title+'"';
				}
				if(align !== ''){
					align = ' align="'+align+'"';
				}
				if(width!=0){
					width = ' width="'+width+'"';
				}else{
					width ='';
				}
				return '\n[note'+title+align+width+']\n'+shortcode.getVal('styledboxes','note_box','content')+'\n[/note]\n';
			}
			break;
		case 'tables':
			return '\n[table]\n'+shortcode.getVal('tables','content')+'\n[/table]\n';
			break;
		case 'buttons':
			var size = shortcode.getVal('buttons','size');
			var align = shortcode.getVal('buttons','align');
			var full = shortcode.getVal('buttons','full');
			var link = shortcode.getVal('buttons','link');
			var linkTarget = shortcode.getVal('buttons','linkTarget');
			var text = shortcode.getVal('buttons','text');
			var bgColor = shortcode.getVal('buttons','bgColor');
			var textColor = shortcode.getVal('buttons','textColor');


			if(size!=''){
				size =' size="'+size+'"';
			}
			if(align!='no'){
				align =' align="'+align+'"';
			}
			else align='';
			if(full==='on'){
				full = ' full="true"';
				align='';
			}else{
				full = '';
			}
			if(link!= ''){
				link = ' link="'+link+'"';
			}
			if(linkTarget!= 'no'){
				linkTarget = ' linkTarget="'+linkTarget+'"';
			}
			else linkTarget="";
			if(bgColor != ''){
				bgColor = ' bgColor="'+ bgColor +'"';
			}
			if(textColor != ''){
				textColor = ' textColor="'+ textColor +'"';
			}
			return '[button'+size+align+full+link+linkTarget+bgColor+textColor+']'+text+'[/button]';
			break;
		case 'tabs':
			var number = shortcode.getVal('tabs','number');
			var ret = '\n[tabs]\n';
			for(var i=1;i<=number;i++){
				ret +='[tab title="'+shortcode.getVal('tabs','title_'+i)+'"]\n'+shortcode.getVal('tabs','content_'+i)+'\n[/tab]\n';
			}
			ret +='[/'+type+']\n';
			return ret;
			break;
		case 'accordion':
			var number = shortcode.getVal('accordion','number');
			var ret = '\n[accordions]\n';
			for(var i=1;i<=number;i++){
				ret +='[accordion title="'+shortcode.getVal('accordion','title_'+i)+'"]\n'+shortcode.getVal('accordion','content_'+i)+'\n[/accordion]\n';
			}
			ret +='[/accordions]\n';
			return ret;
			break;
		case 'toggle':
			return '\n[toggle title="'+shortcode.getVal('toggle','title')+'"]\n'+shortcode.getVal('toggle','content')+'\n[/toggle]\n';
			break;
		case 'divider':
			return '\n['+shortcode.getVal('divider','type')+']\n';
			break;
		case 'images':
	

			var src = shortcode.getVal('images','src');
			var title = shortcode.getVal('images','title');
			var size = shortcode.getVal('images','size');
			var align = shortcode.getVal('images','align');
			var icon = shortcode.getVal('images','icon');
			var caption = shortcode.getVal('images','caption');
			var lightbox = shortcode.getVal('images','lightbox');
			var group = shortcode.getVal('images','group');
			var width = shortcode.getVal('images','width');
			var height = shortcode.getVal('images','height');
			var link = shortcode.getVal('images','link');

			if(size!='Choose one'){
				size =' size="'+size+'"';
			}
			else size='';
			if(align!=''){
				align =' align="'+align+'"';
			}
			if(icon!='no'){
				icon =' icon="'+icon+'"';
			}
			else icon="";
			if(caption!=''){
				caption=' caption="'+caption+'"';
			}
			if(lightbox==='yes'){
				lightbox = ' lightbox="true"';
			}else{
				lightbox = '';
			}
			if(link!= ''){
				link = ' link="'+link+'"';
			}
			if(group!=''){
				group = ' group="'+group+'"';
			}
			if(width!=0){
				width = ' width="'+width+'"';
			}else{
				width ='';
			}
			if(height!=0){
				height = ' height="'+height+'"';
			}else{
				height ='';
			}
			if(title!=''){
				title = ' title="'+title+'"';
			}
			return '[image'+title+size+align+icon+caption+lightbox+group+link+width+height+']'+src+'[/image]';
			
			
			break;
		
		case 'widget':
			var sub_type = shortcode.getVal('widget','selector');
			switch(sub_type){
			case 'gmap':
			var width = shortcode.getVal('widget','gmap','width');
			var height = shortcode.getVal('widget','gmap','height');
			var latitude = shortcode.getVal('widget','gmap','latitude');
			var longitude = shortcode.getVal('widget','gmap','longitude');
			var zoom = shortcode.getVal('widget','gmap','zoom');
			var marker = shortcode.getVal('widget','gmap','marker');
			var html = shortcode.getVal('widget','gmap','html');
			var popup = shortcode.getVal('widget','gmap','popup');
			var maptype = shortcode.getVal('widget','gmap','maptype');

			if(width!=0){
				width = ' width="'+width+'"';
			}else{
				width ='';
			}
			if(height!=0){
				height = ' height="'+height+'"';
			}else{
				height ='';
			}
			if(latitude!= ''){
				latitude = ' latitude="'+latitude+'"';
			}
			if(longitude!=''){
				longitude = ' longitude="'+longitude+'"';
			}
			if(zoom!=0){
				zoom = ' zoom="'+zoom+'"';
			}else{
				zoom ='';
			}
			if(marker=='on'){
				marker = '';
			}else{
				marker = ' marker="false"';
			}
			if(popup=='off'){
				popup = '';
			}else{
				popup = ' popup="true"';
			}
			if(html!= ''){
				html = ' html="'+html+'"';
			}
			if(maptype!= 'G_NORMAL_MAP'){
				maptype = ' maptype="'+maptype+'"';
			}
			else{
				maptype='';
			}

			return '\n[gmap'+width+height+latitude+longitude+zoom+marker+popup+html+maptype+']\n';
			break;
			case 'contactform':
				var email = shortcode.getVal('widget','contactform','email');
				if(email !="" ){
					email = ' email="'+email+'"'
				}
				var content = shortcode.getVal('widget','contactform','content');
				if(content != ""){
					return '\n[contactform'+email+']\n'+content+'\n[/contactform]\n';
				}else{
					return '\n[contactform'+email+']\n';
				}
				break;
			case 'twitter':
				var username = shortcode.getVal('widget','twitter','username');
				var count = shortcode.getVal('widget','twitter','count');
				if(username !="" ){
					username = ' username="'+username+'"';
				}
				if(count !="" ){
					count = ' count="'+count+'"';
				}
				return '\n[twitter'+username+count+']\n';
				break;
			case 'flickr':
				var id = shortcode.getVal('widget','flickr','id');
				var count = shortcode.getVal('widget','flickr','count');
				var display = shortcode.getVal('widget','flickr','display');
				if(id !="" ){
					id = ' id="'+id+'"';
				}
				if(count !="" ){
					count = ' count="'+count+'"';
				}
				if(display !="" ){
					display = ' display="'+display+'"';
				}
				return '\n[flickr'+id+count+display+']\n';
				break;
			case 'contact_details':
				var color = shortcode.getVal('widget','contact_details','color');
				var phone = shortcode.getVal('widget','contact_details','phone');
				var cellphone = shortcode.getVal('widget','contact_details','cellphone');
				var email = shortcode.getVal('widget','contact_details','email');
				var address = shortcode.getVal('widget','contact_details','address');
				var city = shortcode.getVal('widget','contact_details','city');
				var state = shortcode.getVal('widget','contact_details','state');
				var zip = shortcode.getVal('widget','contact_details','zip');
				var name = shortcode.getVal('widget','contact_details','name');

				if(color !="" ){
					color = ' color="'+color+'"';
				}
				if(phone !="" ){
					phone = ' phone="'+phone+'"';
				}
				if(cellphone !="" ){
					cellphone = ' cellphone="'+cellphone+'"';
				}
				if(email !="" ){
					email = ' email="'+email+'"';
				}
				if(address !="" ){
					address = ' address="'+address+'"';
				}
				if(city !="" ){
					city = ' city="'+city+'"';
				}
				if(state !="" ){
					state = ' state="'+state+'"';
				}
				if(zip !="" ){
					zip = ' zip="'+zip+'"';
				}
				if(name !="" ){
					name = ' name="'+name+'"';
				}
				return '\n[contact_details'+color+phone+cellphone+email+address+city+state+zip+name+']\n';
				break;
			case 'popular_posts':
				var count = shortcode.getVal('widget','popular_posts','count');
				var cat = shortcode.getVal('widget','popular_posts','cat');

				if(count !="" ){
					count = ' count="'+count+'"';
				}
				if(cat!=undefined){
					cat = ' cat="'+cat+'"';
				}else{
					cat = '';
				}

				return '\n[popular_posts'+count+cat+']\n';
				break;
			case 'recent_posts':
				var count = shortcode.getVal('widget','recent_posts','count');
				var cat = shortcode.getVal('widget','recent_posts','cat');

				if(count !="" ){
					count = ' count="'+count+'"';
				}
				if(cat!=undefined){
					cat = ' cat="'+cat+'"';
				}else{
					cat = '';
				}
				return '\n[recent_posts'+count+cat+']\n';
				break;
			}
			break;
		case 'portfolio':
			var column = shortcode.getVal('portfolio','column');
			var nopaging = shortcode.getVal('portfolio','nopaging');
			var max = shortcode.getVal('portfolio','max');
			var sortable = shortcode.getVal('portfolio','sortable');
			var jquery = shortcode.getVal('portfolio','jquery');
			var cat = shortcode.getVal('portfolio','cat');
			if(column !=""){
				column = ' column="'+column+'"';
			}else{
				column = ' column="4"';
			}
			if(sortable=='on'){
				sortable = ' sortable="true"';
				max = '';
			} else {
				sortable = '';
			}
			if(nopaging=='on'){
				nopaging = ' nopaging="true"';
				max = '';
			}else{
				nopaging = '';
			}
			
			if(jquery=='on'){
				jquery='_jquery'; 
			}
			else jquery=''; 

			if(max!==''){
				max = ' max="'+max+'"';
			}else{
				max = '';
			}

			if(cat!=undefined){
				cat = ' cat="'+cat+'"';
			}else{
				cat = '';
			}

			return '[portfolio'+jquery+column+nopaging+sortable+max+cat+']';
			break;
		
		case 'video':
			var sub_type = shortcode.getVal('video','selector');
			switch(sub_type){
				case 'flash':
					var src = shortcode.getVal('video','flash','src');
					var width = shortcode.getVal('video','flash','width');
					var height = shortcode.getVal('video','flash','height');
					var play = shortcode.getVal('video','flash','play');

					if(src !=""){
						src = ' src="'+src+'"';
					}
					if(width!=0){
						width = ' width="'+width+'"';
					}else{
						width ='';
					}
					if(height!=0){
						height = ' height="'+height+'"';
					}else{
						height ='';
					}
					
					return '[video type="flash"'+src+width+height+']';
					break;
				case 'youtube':
					var clip_id = shortcode.getVal('video','youtube','clip_id');
					var width = shortcode.getVal('video','youtube','width');
					var height = shortcode.getVal('video','youtube','height');

					if(clip_id !=""){
						clip_id = ' clip_id="'+clip_id+'"';
					}
					if(width!=0){
						width = ' width="'+width+'"';
					}else{
						width ='';
					}
					if(height!=0){
						height = ' height="'+height+'"';
					}else{
						height ='';
					}
					
					return '[video type="youtube"'+clip_id+width+height+']';
					break;
				case 'vimeo':
					var clip_id = shortcode.getVal('video','vimeo','clip_id');
					var width = shortcode.getVal('video','vimeo','width');
					var height = shortcode.getVal('video','vimeo','height');

					if(clip_id !=""){
						clip_id = ' clip_id="'+clip_id+'"';
					}
					if(width!=0){
						width = ' width="'+width+'"';
					}else{
						width ='';
					}
					if(height!=0){
						height = ' height="'+height+'"';
					}else{
						height ='';
					}
					
					return '[video type="vimeo"'+clip_id+width+height+']';
					break;
				case 'dailymotion':
					var clip_id = shortcode.getVal('video','dailymotion','clip_id');
					var width = shortcode.getVal('video','dailymotion','width');
					var height = shortcode.getVal('video','dailymotion','height');

					if(clip_id !=""){
						clip_id = ' clip_id="'+clip_id+'"';
					}
					if(width!=0){
						width = ' width="'+width+'"';
					}else{
						width ='';
					}
					if(height!=0){
						height = ' height="'+height+'"';
					}else{
						height ='';
					}
					
					return '[video type="dailymotion"'+clip_id+width+height+']';
					break;
			};
			break;
		case 'blog':
		
			var count = shortcode.getVal('blog','count');
			
			var cat = shortcode.getVal('blog','cat');
			

			if(count!==''){
				count = ' count="'+count+'"';
			}else{
				count = '';
			}
			
			if(cat!=undefined){
				cat = ' cat="'+cat+'"';
			}else{
				cat = '';
			}
			
			return '[blog'+count+cat+']';
			break;
		}
		return '';
	},
	getVal:function(a,b,c){
		if(b == undefined){
			var target = jQuery('[name="sc_'+a+'"]');
			if(target.is('.toggle-button')){
				if(target.is(':checked')){
					return true;
				}else{
					return false;
				}
			}
			if(target.size() == 0){
				return jQuery('[name="sc_'+a+'[]"]').val();
			}else{
				return target.val();
			}
		}else if(c == undefined){
			var target = jQuery('[name="sc_'+a+'_'+b+'"]');
			if(target.is('.toggle-button')){
				if(target.is(':checked')){
					return true;
				}else{
					return false;
				}
			}
			if(target.size() == 0){
				return jQuery('[name="sc_'+a+'_'+b+'[]"]').val();
			}else{
				return target.val();
			}
		}else {
			var target = jQuery('[name="sc_'+a+'_'+b+'_'+c+'"]');
			if(target.is('.toggle-button')){
				if(target.is(':checked')){
					return true;
				}else{
					return false;
				}
			}
			if(target.size() == 0){
				return jQuery('[name="sc_'+a+'_'+b+'_'+c+'[]"]').val();
			}else{
				return target.val();
			}
		}
		
	},
	sendToEditor :function(){
		 send_to_editor(shortcode.generate());
	}
		
}

jQuery(document).ready( function($) {
	shortcode.init();
});
