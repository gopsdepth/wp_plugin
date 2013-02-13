<?
/*
Plugin Name: Custom class category widget
Plugin URI: http://satjapot.co.nf
Description: All functions like wordpress default categort widget but it can be add class tag for ul
Version: 1.0
Author: Gopsdepth
Author URI: http://satjapot.co.nf
License: GPLv2 or later
 */

/*  Copyright 2013  Custom class category widget  (email : gopsdepth@hotmail.com)

This program is free software; you can redistribute it and/or modify
it under the terms of the GNU General Public License, version 2, as
published by the Free Software Foundation.

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with this program; if not, write to the Free Software
Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
*/
class F2C_Custom_Class_Widget_Categories extends WP_Widget {

	function __construct() {
		$widget_ops = array( 'classname' => 'f2c_custom_class_widget_categories', 'description' => __( "A list or dropdown of categories with custom class" ) );
		parent::__construct('f2c_custom_class_widget_categories', __('Custom Class Categories'), $widget_ops);
	}

	function widget( $args, $instance ) {
		extract( $args );

		$title = apply_filters('widget_title', empty( $instance['title'] ) ? __( 'Categories' ) : $instance['title'], $instance, $this->id_base);
		$c = ! empty( $instance['count'] ) ? '1' : '0';
		$h = ! empty( $instance['hierarchical'] ) ? '1' : '0';
		$d = ! empty( $instance['dropdown'] ) ? '1' : '0';
		
		// Special part
		$cclass= $instance['cclass'];
		$before_title = empty($instance['b_title']) ? $before_title : $instance['b_title'];
		$after_title = empty($instance['a_title']) ? $after_title : $instance['a_title'];
		// end part

		echo $before_widget;
		if ( $title )
			echo $before_title . $title . $after_title;

		$cat_args = array('orderby' => 'name', 'show_count' => $c, 'hierarchical' => $h);

		if ( $d ) {
			$cat_args['show_option_none'] = __('Select Category');
			wp_dropdown_categories(apply_filters('widget_categories_dropdown_args', $cat_args));
?>

<script type='text/javascript'>
/* <![CDATA[ */
	var dropdown = document.getElementById("cat");
	function onCatChange() {
		if ( dropdown.options[dropdown.selectedIndex].value > 0 ) {
			location.href = "<?php echo home_url(); ?>/?cat="+dropdown.options[dropdown.selectedIndex].value;
		}
	}
	dropdown.onchange = onCatChange;
/* ]]> */
</script>

<?php
		} else {
		$ul_class = ''; 
		if(!empty($cclass)) $ul_class = sprintf(' class="%s"', $cclass);
?>
		<ul<?php echo $ul_class; ?>>
<?php
		$cat_args['title_li'] = '';
		wp_list_categories(apply_filters('widget_categories_args', $cat_args));
?>
		</ul>
<?php
		}

		echo $after_widget;
	}

	function update( $new_instance, $old_instance ) {
		$instance = $old_instance;
		$instance['title'] = strip_tags($new_instance['title']);
		$instance['count'] = !empty($new_instance['count']) ? 1 : 0;
		$instance['hierarchical'] = !empty($new_instance['hierarchical']) ? 1 : 0;
		$instance['dropdown'] = !empty($new_instance['dropdown']) ? 1 : 0;
		$instance['cclass'] = trim($new_instance['cclass']);
		$instance['b_title'] = trim($new_instance['b_title']);
		$instance['a_title'] = trim($new_instance['a_title']);

		return $instance;
	}

	function form( $instance ) {
		//Defaults
		$instance = wp_parse_args( (array) $instance, array( 'title' => '') );
		$title = esc_attr( $instance['title'] );
		$count = isset($instance['count']) ? (bool) $instance['count'] :false;
		$hierarchical = isset( $instance['hierarchical'] ) ? (bool) $instance['hierarchical'] : false;
		$dropdown = isset( $instance['dropdown'] ) ? (bool) $instance['dropdown'] : false;
		$cclass = isset( $instance['cclass'] ) ? esc_attr( $instance['cclass'] ) : '';
		$b_title = isset( $instance['b_title'] ) ? esc_attr( $instance['b_title'] ) : '';
		$a_title = isset( $instance['a_title'] ) ? esc_attr( $instance['a_title'] ) : '';
?>
		<p><label for="<?php echo $this->get_field_id('title'); ?>"><?php _e( 'Title:' ); ?></label>
		<input class="widefat" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" type="text" value="<?php echo $title; ?>" /></p>

		<p><label for="<?php echo $this->get_field_id('cclass'); ?>"><?php _e( esc_html('<ul> Class:') ); ?></label>
		<input class="widefat" id="<?php echo $this->get_field_id('cclass'); ?>" name="<?php echo $this->get_field_name('cclass'); ?>" type="text" value="<?php echo $cclass; ?>" /></p>

		<p><label for="<?php echo $this->get_field_id('b_title'); ?>"><?php _e( 'Before title: **Empty equals default value' ); ?></label>
		<input class="widefat" id="<?php echo $this->get_field_id('b_title'); ?>" name="<?php echo $this->get_field_name('b_title'); ?>" type="text" value="<?php echo $b_title; ?>" /></p>

		<p><label for="<?php echo $this->get_field_id('a_title'); ?>"><?php _e( 'After title: **Empty equals default value' ); ?></label>
		<input class="widefat" id="<?php echo $this->get_field_id('a_title'); ?>" name="<?php echo $this->get_field_name('a_title'); ?>" type="text" value="<?php echo $a_title; ?>" /></p>
		
		<p><input type="checkbox" class="checkbox" id="<?php echo $this->get_field_id('dropdown'); ?>" name="<?php echo $this->get_field_name('dropdown'); ?>"<?php checked( $dropdown ); ?> />
		<label for="<?php echo $this->get_field_id('dropdown'); ?>"><?php _e( 'Display as dropdown' ); ?></label><br />

		<input type="checkbox" class="checkbox" id="<?php echo $this->get_field_id('count'); ?>" name="<?php echo $this->get_field_name('count'); ?>"<?php checked( $count ); ?> />
		<label for="<?php echo $this->get_field_id('count'); ?>"><?php _e( 'Show post counts' ); ?></label><br />

		<input type="checkbox" class="checkbox" id="<?php echo $this->get_field_id('hierarchical'); ?>" name="<?php echo $this->get_field_name('hierarchical'); ?>"<?php checked( $hierarchical ); ?> />
		<label for="<?php echo $this->get_field_id('hierarchical'); ?>"><?php _e( 'Show hierarchy' ); ?></label></p>
<?php
	}

}

/**
 * Register
 */
add_action ('widgets_init', 'f2c_reg_category_widget');
function f2c_reg_category_widget()
{
	register_widget ('f2c_custom_class_widget_categories');
}

/**
 * Register before installation
 */
register_activation_hook (__FILE__, 'f2c_activate_category_widget');
function f2c_activate_category_widget ()
{
	return true;
}