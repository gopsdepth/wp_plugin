<?php
/*
Plugin Name: Simple youtube search include by gopsdepth
Plugin URI: http://www.satjapotport.co.nf
Description: Retreive first or value that is indicated in attribute to show as Youtube by text search. It use Wordpress shortcode to show so the youtube video will show with powerful embled. How to use: You just add [f2c_youtube_inc s="search terms"], that's all.
Version: 1.0.0
Author: gopsdepth
Author URI: http://www.satjapotport.co.nf
*/

/**
 * This function is private function. It only execute out shortcode for content. use for save
 */
function _f2c_do_youtube_simple_shortcode( $m ) {
	global $shortcode_tags, $f2c_yt_simple_signal, $f2c_yt_simple_static;
	
	// Snap only our shortcode
	if( $m[2] != 'f2c_youtube_inc') return $m[0]; 

	
	// allow [[foo]] syntax for escaping a tag
	if ( $m[1] == '[' && $m[6] == ']' ) {
		return substr($m[0], 1, -1);
	}

	$tag = $m[2];
	$attr = shortcode_parse_atts( $m[3] );

	$output = $m[1] . call_user_func( $shortcode_tags[$tag], $attr, null,  $tag ) . $m[6];
	if( $f2c_yt_simple_static == 'no' )
	{
		$f2c_yt_simple_static = '1';
		unset($f2c_yt_simple_static);
		return $m[0];
	}
	$f2c_yt_simple_signal++;
	return $output;
}

/**
 * The private funciton, this function will save a content with tranlate our shortcode when saving for performance reason.
 * 
 */
function _f2c_youtube_simple_save($post_id=0, $post=null)
{
	global $f2c_yt_simple_signal;
	if(empty($post)) return;
	
	$f2c_yt_simple_signal = 0;
	$pattern = get_shortcode_regex();
	$post->post_content = preg_replace_callback("/$pattern/s", '_f2c_do_youtube_simple_shortcode', $post->post_content);
	
	if($f2c_yt_simple_signal > 0)
	{
		$f2c_yt_simple_signal = 0;
		remove_action('post_updated', 'f2c_youtube_simple_save');
		wp_update_post( array( 'ID' => $post_id, 'post_content' => $post->post_content ) );
		add_action('post_updated', 'f2c_youtube_simple_save');
	}
}

/**
 * For developer. This function return array of youtube data consist of id, title and link for using in code
 * 
 * @param string $s
 * @param int $no
 * @return array: id=youtube_id, title=title of youtube, url=link of youtube
 */
function f2c_youtube_simple_data($s, $no=0)
{
	// Validation
	if(empty($s)) return '';
	
	// Load from youtube
	require_once 'simple_html_dom.php';
	$url_list = array(
			"http://youtube.com/results?search_type=videos&search_query=".urlencode(str_replace(' ', '+', $s))
	);
	$data = array();
	
	foreach($url_list as $url)
	{
		$html = file_get_html($url);
			
		foreach($html->find('#search-results li') as $element)
		{
			$h3 = $element->find('h3 a', 0);
			if(!is_object($h3)) continue;
	
			$elm = array();
			$elm['href'] = $element->getAttribute('data-context-item-id');
			$elm['text'] =  $h3->innertext;
			$data['links'][] = $elm;
		}
	}
	
	// Validation
	if(count($data['links']) == 0) return '';
	
	// Choose youtube from list
	$cindex = 0;
	if(isset($data['links'][$no]))
	{
		$cindex = $no;
	}
	$curl = 'http://www.youtube.com/watch?v='.$data['links'][$cindex]['href'];
	
	return array('id' => $data['links'][$cindex]['href'], 'title' => $data['links'][$cindex]['text'], 'url' => $curl);
}

/**
 * Original shortcode function
 * 
 * @param unknown_type $atts
 * @return string|multitype:NULL string |unknown
 */
function f2c_youtube_simple_search( $atts ){
	// Get attribute
	extract( shortcode_atts( array(
		's' => '',
		'no' => '1',
		'output' => 'shortcode',
		'static' => '1'
	), $atts ) );
	
	// Validation
	if(empty($s)) return '';
	
	// Load from youtube
	require_once 'simple_html_dom.php';
	$url_list = array(
		"http://youtube.com/results?search_type=videos&search_query=" . (str_replace(' ', '+', $s))
	);
	$data = array();
	
	foreach($url_list as $url)
	{
		$html = file_get_html($url);
			
		foreach($html->find('#search-results li') as $element)
		{
			$h3 = $element->find('h3 a', 0);
			if(!is_object($h3)) continue;

			$elm = array();
			$elm['href'] = $element->getAttribute('data-context-item-id');
       		$elm['text'] =  $h3->innertext;
       		$data['links'][] = $elm;
		}
	}
	
	// Validation
	if(count($data['links']) == 0) return ''; 
	
	// Choose youtube from list
	$cindex = 0;
	if(isset($data['links'][$no]))
	{
		$cindex = $no;
	}
	$curl = 'http://www.youtube.com/watch?v='.$data['links'][$cindex]['href'];
	
	global $f2c_yt_simple_static;
	$f2c_yt_simple_static = $static; 
	
	// Output
	if($output == "url")
	{
		return $curl;
	}
	else
	{
		// Call wordpress embled
		global $wp_embed;
		
		$atts['id'] = $data['links'][$cindex]['href'];
		
		$last_output = $wp_embed->shortcode($atts, $curl);
		
		return $last_output;
	}
}
add_shortcode( 'f2c_youtube_inc', 'f2c_youtube_simple_search' );

function f2c_youtube_thecontent()
{
	$post = get_post(get_the_ID());
	_f2c_youtube_simple_save($post->ID, $post);
	
	return $post->post_content;
}
add_action('the_content', 'f2c_youtube_thecontent', 1);

/*
 * Add button to Editor
 */
function _f2c_youtube_addbutton($buttons)
{
	$buttons[] = 'f2c_youtube_static_button';
	$buttons[] = 'f2c_youtube_button';
	return $buttons;
}
// Add a callback to add our button to the TinyMCE toolbar
add_filter('mce_buttons', '_f2c_youtube_addbutton');

//This callback registers our plug-in
function _f2c_youtube_addmceplugin($plugin_array) {
	$plugin_path = plugin_dir_url(__FILE__);
	$plugin_array['f2c_youtube_inc'] = $plugin_path . 'shortcode.js';
	return $plugin_array;
}
add_filter("mce_external_plugins", "_f2c_youtube_addmceplugin"); 