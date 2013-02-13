<?php
/*
Plugin Name: Contact form as comment by gopsdepth
Plugin URI: http://www.satjapotport.co.nf
Description: Simple contact form plug-in. It'll save contact information as comment of your page. You will get a expereince lie comment is added. You just place my short-code for generate contact form and that's all. 
Version: 0.1.0
Author: gopsdepth
Author URI: http://www.satjapotport.co.nf
*/

function f2c_cac_shortcode($atts)
{
	
	// Get attribute
	$attributes = shortcode_atts( array(
		'title' => '<h1>Leave Message</h1>',
		'label_class' => '',
		'input_class' => '',
		'message_class' => '',
		'submit_class' => '',
		'response_class' => 'success',
		'submit_text' => 'Send',
		'name_text' => 'Name:',
		'email_text' => 'E-mail:',
		'message_text' => 'Message:',
		'thankyou_text' => 'Thank you for your touch.'
	), $atts );
	extract( $attributes );
	
	// prepare data
	$label_class = empty($label_class) ? '' : ' class="' . $label_class . '"';
	$name_class = empty($input_class) ? ' class="required"' : ' class="required ' . $input_class . '"';
	$email_class = empty($input_class) ? ' class="required email"' : ' class="required email ' . $input_class . '"';
	$message_class = empty($message_class) ? '' : ' class="' . $message_class . '"';
	$submit_class = empty($submit_class) ? '' : ' class="' . $submit_class . '"';
	$response_class = empty($response_class) ? '' : ' class="' . $response_class . '"';
	
	ob_start();

	$thx_msg = get_option('f2c_cac_msg');
	if($thx_msg !== false)
	{
		delete_option('f2c_cac_msg');
		$thx_msg = '<div' . $response_class . '>' . $thx_msg . '</div>';
		echo $thx_msg;
	}
?>
<form id="f2c_cac_form" method="post" action="">
	<?php wp_nonce_field('f2c_cac_form'); ?>
	<input type="hidden" name="f2c_cac_action" value="1" />
	<input type="hidden" name="f2c_cac_thx_msg" value="<?php echo $thankyou_text; ?>" />
	
	<?php echo $title; ?>
	<p>
		<label for="f2c_cac_name"<?php echo $label_class; ?>><?php echo $name_text; ?></label>
		<input type="text" name="f2c_cac_name"<?php echo $name_class; ?> />
	<p>
	<p>
		<label for="f2c_cac_email"<?php echo $label_class; ?>><?php echo $email_text; ?></label>
		<input type="text" name="f2c_cac_email"<?php echo $email_class; ?> />
	</p>
	<p>
		<label for="f2c_cac_message"<?php echo $label_class; ?>><?php echo $message_text; ?></label>
		<textarea name="f2c_cac_message"<?php echo $message_class; ?>></textarea>
	</p>
	<input type="submit" name="f2c_cac_submit" value="<?php echo $submit_text; ?>"<?php echo $submit_class; ?> />
</form>
<?php
	$output = ob_get_contents();
	ob_end_clean(); 
	
	// Prepare data for custom theme
	$attributes['label_class'] = $label_class;
	$attributes['name_class'] = $name_class;
	$attributes['email_class'] = $email_class;
	$attributes['message_class'] = $message_class;
	$attributes['submit_class'] = $submit_class;
	$attributes['response_text'] = $thx_msg;
	
	$output = apply_filters('f2c_cac_getform', $output, $attributes);
	return $output;
}
add_shortcode('f2c_cac_form', 'f2c_cac_shortcode');

// Process on init
function f2c_cac_process()
{
	wp_register_script('f2c_cac_validation', plugin_dir_url( __FILE__ ).'jquery.validate.min.js', array('jquery'));
	wp_register_script('f2c_cac_active', plugin_dir_url( __FILE__ ).'contact_as_comment.js', array('f2c_cac_validation'), false, true);
	if( isset($_REQUEST['f2c_cac_action']) )
	{
		// WP Security
		if( !wp_verify_nonce( $_REQUEST['_wpnonce'], 'f2c_cac_form') ) wp_die('Please submit from submission page.');
		
		// input
		$thankyou_msg = $_REQUEST['f2c_cac_thx_msg'];
		
		// Process
		$cpage = & get_page(get_the_ID());
		
		// Add message as comment
		$data = array(
				'comment_post_ID' => $cpage->ID,
				'comment_author' => $_REQUEST['f2c_cac_name'],
				'comment_author_email' => $_REQUEST['f2c_cac_email'],
				'comment_author_url' => 'http://',
				'comment_content' => $_REQUEST['f2c_cac_message'],
				'comment_type' => '',
				'user_id' => 0,
		);

		$data = apply_filters('preprocess_comment', $data);	
		wp_new_comment($data);
		
		// Response
		add_option('f2c_cac_msg', $thankyou_msg);
		wp_redirect($_REQUEST['_wp_http_referer']);
		exit;
	}
}
add_action('template_redirect', 'f2c_cac_process');

// Add script
function f2c_cac_addscript()
{
	wp_enqueue_script('f2c_cac_active');
}
add_action('wp_enqueue_scripts', 'f2c_cac_addscript');