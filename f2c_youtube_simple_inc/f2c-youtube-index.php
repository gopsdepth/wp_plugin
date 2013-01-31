<?php
/*
Plugin Name: Simple youtube include by Gopsdepth
Plugin URI: http://www.satjapotport.co.nf
Description: Retreive first or value that is indicated in attribute to show as Youtube by text search. It use Wordpress shortcode to show so the youtube video will show with powerful embled. How to use: You just add [f2c_yotube_inc s="your text"], that's all.
Version: 0.1.1
Author: Gopsdepth
Author URI: http://www.satjapotport.co.nf
*/

function f2c_youtube_simple_search( $atts ){
	// Get attribute
	extract( shortcode_atts( array(
		's' => 'No text search!!',
		'no' => '1',
	), $atts ) );
	
	// Load from youtube
	require_once 'simple_html_dom.php';
	$url_list = array(
			"http://youtube.com/results?search_query=".urlencode($s)
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
	
	// Call wordpress embled
	global $wp_embed;
	
	$atts['id'] = $data['links'][$cindex]['href'];
	
	return $wp_embed->shortcode($atts, $curl);
}
add_shortcode( 'f2c_yotube_inc', 'f2c_youtube_simple_search' );
