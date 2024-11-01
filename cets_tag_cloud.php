<?php
/*
Plugin Name: Custom Tag Cloud Widget
Plugin URI: 
Description: Adds a sidebar widget to display tag cloud from blog of your choosing. Requires WPMU 2.8+
Version: 1.2.1
Author: Deanna Schneider
Copyright:

    Copyright 2009 CETS

    This program is free software; you can redistribute it and/or modify
    it under the terms of the GNU General Public License as published by
    the Free Software Foundation; either version 2 of the License, or
    (at your option) any later version.

    This program is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

    You should have received a copy of the GNU General Public License
    along with this program; if not, write to the Free Software
    Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA  02111_1307  USA

*/



class cets_widget_tag_cloud extends WP_Widget{
	/** constructor **/
	function cets_widget_tag_cloud() {
		parent::WP_Widget(false, $name = 'Selected Blog Tag Cloud');
	}
	
	/** This function displays the output of the widget **/
    function widget($args, $instance) {		
        extract( $args );
        $options = $instance;
		$title = empty($options['title']) ? __('Tags') : apply_filters('widget_title', $options['title']);
		$defaults = array(
			'smallest' => 8, 'largest' => 22, 'unit' => 'pt', 'number' => 40,
			'format' => 'flat', 'orderby' => 'count', 'order' => 'ASC'
		);
		
		
		$args = wp_parse_args( $options, $defaults );
		
		
		if (!is_numeric($args['smallest'])){
			$args['smallest'] = $defaults['smallest'];
			}
		
		if (!is_numeric($args['largest'])){
			$args['largest'] = $defaults['largest'];
			}
		
		
		if ( !in_array( $args['unit'], array( 'pt', 'px', 'em', '%' ) ) ) {
			$args['unit'] = $defaults['unit'];
		}
		
		if (!is_numeric($args['number'])){
			$args['number'] = $defaults['number'];
		}
		
		if ( !in_array( $args['format'], array( 'flat', 'list') ) ) {
			$args['format'] = $defaults['format'];
			}
		
		if ( !in_array( $args['orderby'], array( 'name', 'count') ) ) {
			$args['orderby'] = $defaults['orderby'];
			}
		
		if ( !in_array( $args['order'], array( 'ASC', 'DESC', 'RAND') ) ) {
			$args['order'] = $defaults['order'];
			}
		
		
	
		
		
		// get the blog details and don't show anything if the featured blog has been deleted.
		$details = get_blog_details($args['blogid']);
		if ($details->deleted == 0){
	
			echo $before_widget;
			echo $before_title . $title . $after_title;
			echo ("<div>");
			switch_to_blog($args['blogid']);
			wp_tag_cloud($args);
			restore_current_blog();
			if (isSet($options['linkpage']) && strlen($options['linkpage'])){
				echo('<p class="more"><a href="' . $options['linkpage'] . '">(More)</a></p>');
				
			}
			echo ("</div>");
			echo $after_widget;
		}
    }
	
	/** This function handles the updating **/
	 /** @see WP_Widget::update */
    function update($new_instance, $old_instance) {	
		$instance = $old_instance;
		
		$instance['title'] = strip_tags(stripslashes($new_instance['title']));
		$instance['blogid'] = $new_instance['blogid'];
		if (is_numeric($new_instance['smallest'])){
			$instance['smallest'] = $new_instance['smallest'];
		}
		else {
			$instance['smallest'] = 8;
		}
		if (is_numeric($new_instance['largest'])){
			$instance['largest'] = $new_instance['largest'];
		}
		else {
			$instance['largest'] = 22;
		}
		
		if ( in_array( $new_instance['unit'], array( 'pt', 'px', 'em', '%' ) ) ) {
			$instance['unit'] = $new_instance['unit'];
		}
		else {
			$instance['unit'] = 'pt';
		}
		if (is_numeric($new_instance['number'])){
			$instance['number'] = $new_instance['number'];
		}
		else {
			$instance['number'] = 40;
		}
		if ( in_array( $new_instance['format'], array( 'flat', 'list') ) ) {
			$instance['format'] = $new_instance['format'];
			}
		else {
			$instance['format'] = 'list';
		}	
		if ( in_array( $new_instance['orderby'], array( 'name', 'count') ) ) {
			$instance['orderby'] = $new_instance['orderby'];
			}
		else {
			$instance['orderby'] = 'count';
		}	
		if ( in_array( $new_instance['order'], array( 'ASC', 'DESC', 'RAND') ) ) {
			$instance['order'] = $new_instance['order'];
		}
		else{
			$instance['order'] = 'ASC';
		}
		$instance['exclude'] = strip_tags($new_instance['exclude']);
		$instance['include'] = strip_tags($new_instance['include']);
		$instance['linkpage'] = strip_tags($new_instance['linkpage']);
		
					
        return $new_instance;
    }


	/** This function creates the form **/
	/** @see WP_Widget::form */
    function form($instance) {				
        global $blog_id;
		
		// get the list of blogs
		//$blog_list = get_site_option("blog_list");
		$defaults = array(
			'smallest' => 8, 'largest' => 22, 'unit' => 'pt', 'number' => 40,
			'format' => 'flat', 'orderby' => 'count', 'order' => 'ASC'
		);
		$title = esc_attr($instance['title']);
		$smallest = esc_attr($instance['smallest'] );
		$largest = esc_attr($instance['largest'] );
		$unit = esc_attr($instance['unit'] );
		$number = esc_attr($instance['number'] );
		$format = esc_attr($instance['format'] );
		$orderby = esc_attr($instance['orderby'] );
		$order = esc_attr($instance['order'] );
		$include = esc_attr($instance['include'] );
		$exclude = esc_attr($instance['exclude'] );
		$linkpage = esc_attr($instance['linkpage'] );
		$blogid = $instance['blogid'];
		if ($blogid == NULL) {
			$blogid = $blog_id;
		}
		if (!is_numeric($smallest)) $smallest = $defaults['smallest'];
		if (!is_numeric($largest)) $largest = $defaults['largest'];
		if (!is_numeric($number)) $number = $defaults['number'];
		
		
		
	?>
		<p><label for="<?php echo $this->get_field_id('title'); ?>"><?php _e('Title:'); ?> <input class="widefat" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" type="text" value="<?php echo $title; ?>" /></label></p>
		<p><label for="<?php echo $this->get_field_id('blogid'); ?>"><?php _e('Blog ID to use:'); ?> <input size="5" id="<?php echo $this->get_field_id('blogid'); ?>" name="<?php echo $this->get_field_name('blogid'); ?>" type="text" value="<?php echo $blogid; ?>" /></label></p>
		

		<p><label for="<?php echo $this->get_field_id('smallest'); ?>t"><?php _e('Smallest Font Size:') ?></label>
			<input name="<?php echo $this->get_field_name('smallest'); ?>" id="<?php echo $this->get_field_id('smallest'); ?>" value="<?php echo $smallest?>" size="5" /></label>
			</p>
			<p><label for="<?php echo $this->get_field_id('largest'); ?>"><?php _e('Largest Font Size:') ?></label>
			<input name="<?php echo $this->get_field_name('largest'); ?>" id="<?php echo $this->get_field_id('largest'); ?>" value="<?php echo $largest?>" size="5" /></label>
			</p>
			<p><label for="<?php echo $this->get_field_id('unit'); ?>"><?php _e('Unit:') ?>
			<select name="<?php echo $this->get_field_name('unit'); ?>" id="<?php echo $this->get_field_id('unit'); ?>">
				<option value="pt"<?php if ($unit == 'pt') { echo (" selected");} ?>>pt</option>
				<option value="px"<?php if ($unit == 'px') { echo (" selected");} ?>>px</option>
				<option value="em"<?php if ($unit == 'em') { echo (" selected");} ?>>em</option>
				<option value="%"<?php if ($unit == '%') { echo (" selected");} ?>>%</option>
				</select>
			</label>
			</p>
				<p><label for="<?php echo $this->get_field_id('number'); ?>"><?php _e('Total Tags:') ?></label>
			<input name="<?php echo $this->get_field_name('number'); ?>" id="<?php echo $this->get_field_id('number'); ?>" value="<?php echo $number?>" size="5" /></label>
			</p>
			<p><label for="<?php echo $this->get_field_id('format'); ?>"><?php _e('Format:') ?></label>
			<select name="<?php echo $this->get_field_name('format'); ?>" id="<?php echo $this->get_field_id('format'); ?>">
				<option value="flat"<?php if ($format == 'flat') { echo (" selected");} ?>>flat</option>
				<option value="list"<?php if ($format == 'list') { echo (" selected");} ?>>list</option>
				</select>
			</label>
			</p>
			<p><label for="<?php echo $this->get_field_id('orderby'); ?>"><?php _e('Order By:') ?></label>
			<select name="<?php echo $this->get_field_name('orderby'); ?>" id="<?php echo $this->get_field_id('orderby'); ?>">
				<option value="count"<?php if ($orderby == 'count') { echo (" selected");} ?>>count</option>
				<option value="name"<?php if ($orderby == 'name') { echo (" selected");} ?>>name</option>
				
				</select>
			</label>
			</p>
			<p><label for="<?php echo $this->get_field_id('order'); ?>"><?php _e('Sort Order:') ?></label>
			<select name="<?php echo $this->get_field_name('order'); ?>" id="<?php echo $this->get_field_id('order'); ?>">
				<option value="ASC"<?php if ($order == 'ASC') { echo (" selected");} ?>>ASC</option>
				<option value="DESC"<?php if ($order == 'DESC') { echo (" selected");} ?>>DESC</option>
				<option value="RAND"<?php if ($order == 'RAND') { echo (" selected");} ?>>RAND</option>
				</select>
			
			</p>
			<p><label for="<?php echo $this->get_field_id('exclude'); ?>"></label>
			<?php _e('Exclude:') ?> <input type="text" class="widefat" id="<?php echo $this->get_field_id('exclude'); ?>" name="<?php echo $this->get_field_name('exclude'); ?>" value="<?php echo $exclude ?>" /></label>
			</p>
			<p><label for="<?php echo $this->get_field_id('include'); ?>"></label>
			<?php _e('Include:') ?> <input type="text" class="widefat" id="<?php echo $this->get_field_id('include'); ?>" name="<?php echo $this->get_field_name('include'); ?>" value="<?php echo $include ?>" /></label>
			</p>
			<p><label for="<?php echo $this->get_field_id('linkpage'); ?>"></label>
			<?php _e('Link Tag Cloud to this Page:') ?> <input type="text" class="widefat" id="<?php echo $this->get_field_id('linkpage'); ?>" name="<?php echo $this->get_field_name('linkpage'); ?>" value="<?php echo $linkpage ?>" /></label>
			</p>
		
	<?php
		
    }

	
	
} // End of class


// register cets_tag_cloud widget
add_action('widgets_init', create_function('', 'return register_widget("cets_widget_tag_cloud");'));





/* *************************************************************************************
 * Functions to add a tag cloud shortcode
 * *************************************************************************************
 */

/* *
 * Shortcode function for showing a tag cloud
 * Input values are based on wp_tag_cloud().  Since it has no 'echo'
 * parameter, we must port the function to the plugin to return the
 * the tag cloud for use with the shortcode API.
 * @link http://codex.wordpress.org/Template_Tags/wp_tag_cloud
 *
 * @since 0.1
 * @param array $attr Attributes attributed to the shortcode.
 */
function shortcode_cets_tag_cloud( $attr = '') {
		global $blog_id;
		
		$defaults = array(
		'blogid' => $blog_id, 'smallest' => 8, 'largest' => 22, 'unit' => 'pt', 'number' => 175,
		'format' => 'flat', 'orderby' => 'count', 'order' => 'DESC',
		'exclude' => '', 'include' => '', 'link' => 'view', 'taxonomy' => 'post_tag', 'echo' => true);
		
		$attr = wp_parse_args( $attr, $defaults );
		
		
		if (!is_numeric((int) $attr['blogid'])){
			$attr['blogid'] = $blog_id;
		}
	
		if (!is_numeric($attr['smallest'])){
			$attr['smallest'] = 8;
		}
		
		if (!is_numeric($attr['largest'])){
			$attr['largest'] = 22;
		}
		
		if ( !in_array( $attr['unit'], array( 'pt', 'px', 'em', '%' ) ) ) {
			$attr['unit'] = 'pt';
		}
		if (!is_numeric($attr['number'])){
			$attr['number'] = 5;
		}
		if (!in_array( $attr['format'], array( 'flat', 'list') ) ) {
			$attr['format'] = 'list';
		}	
		if ( !in_array( $attr['orderby'], array( 'name', 'count') ) ) {
			$attr['orderby'] = 'count';
		}	
		if ( !in_array( $attr['order'], array( 'ASC', 'DESC', 'RAND') ) ) {
			$attr['order'] = 'ASC';
		}
		$attr['exclude'] = strip_tags($attr['exclude']);
		$attr['include'] = strip_tags($attr['include']);
		$attr['linkpage'] = strip_tags($attr['linkpage']);
		
		
	
		// if no taxonomy is set up, default to same as normal wordpress default
		switch_to_blog($attr['blogid']);
		
		// This section lifted from category-template.php in the core code //
		$tags = get_terms( $attr['taxonomy'], array_merge( $attr, array( 'orderby' => 'count', 'order' => 'DESC' ) ) ); // Always query top tags
	
		if ( empty( $tags ) )
			return;
	
		foreach ( $tags as $key => $tag ) {
			if ( 'edit' == $attr['link'] )
				$link = get_edit_tag_link( $tag->term_id, $attr['taxonomy'] );
			else
				$link = get_term_link( intval($tag->term_id), $attr['taxonomy'] );
			if ( is_wp_error( $link ) )
				return false;
	
			$tags[ $key ]->link = $link;
			$tags[ $key ]->id = $tag->term_id;
		}
	
		$return = wp_generate_tag_cloud( $tags, $attr ); // Here's where those top tags get sorted according to $attr
	
		$return = apply_filters( 'wp_tag_cloud', $return, $attr );
		// end lifted code	
		restore_current_blog();
		
		return $return;
}

add_shortcode( 'cets_tag_cloud', 'shortcode_cets_tag_cloud' );

?>