(function($) {
	$(function() { 
	    tinymce.create('tinymce.plugins.F2c_youtube_plugin', {
	        init : function(ed, url) { 
	            // Register command for when button is clicked
	            ed.addCommand('f2c_youtube_static_insert_shortcode', function() {
	            	selected = tinyMCE.activeEditor.selection.getContent();

	                if( selected ){
	                    //If text is selected when button is clicked
	                    //Wrap shortcode around it.
	                    content =  '[f2c_youtube_inc s="'+selected+'"]';
	                }else{
	                    content =  '[shortcode s=""]';
	                }
	                
	                tinymce.execCommand('mceInsertContent', false, content);
	            });
	            // Register command for when button is clicked
	            ed.addCommand('f2c_youtube_insert_shortcode', function() {
	            	selected = tinyMCE.activeEditor.selection.getContent();

	                if( selected ){
	                    //If text is selected when button is clicked
	                    //Wrap shortcode around it.
	                    content =  '[f2c_youtube_inc s="'+selected+'" static="no"]';
	                }else{
	                    content =  '[shortcode s="" static="no"]';
	                }
	                
	                tinymce.execCommand('mceInsertContent', false, content);
	            });

	            // Register buttons - trigger above command when clicked
	            ed.addButton('f2c_youtube_button', {title : 'F2C Youtube', cmd : 'f2c_youtube_insert_shortcode', image: url + '/button.gif' });
	            ed.addButton('f2c_youtube_static_button', {title : 'F2C Youtube Static', cmd : 'f2c_youtube_static_insert_shortcode', image: url + '/button_static.gif' });
	        },   
	        createControl : function(n, cm) {
                return null;
	        },
	        getInfo : function() {
	                return {
	                    longname : 'F2C Youtube search include',
	                    author : 'Gopsdepth',
	                    authorurl : 'http://satjapot.co.nf',
	                    infourl : 'http://satjapot.co.nf',
	                    version : "1.0"
	            };
		    }
	    }); 

	    // Register our TinyMCE plugin
	    // first parameter is the button ID1
	    // second parameter must match the first parameter of the tinymce.create() function above
	    tinymce.PluginManager.add('f2c_youtube_inc', tinymce.plugins.F2c_youtube_plugin);
	});
})(jQuery);