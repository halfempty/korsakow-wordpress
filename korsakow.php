<?php
/*
Plugin Name: Korsakow for WordPress
Description: Insert Korsakow films into posts and pages.
Author: Marty Spellerberg
Version: 0.1
Author URI: http://martyspellerberg.com

*/

define('MY_PLUGIN_FOLDER',str_replace("\\",'/',dirname(__FILE__)));

function get_kpath() {
	global $post;
		if ( is_single() || is_page() ) {
			$kmeta = get_post_meta($post->ID, '_korsakow', true); 
			$kpath = $kmeta["path"];
		 	if ( $kpath == '' ) { 
				// This is not a korsakow film
			} else {
				return $kpath;
			}

		} 
}



function get_kheight() {
	global $post;
		if ( is_single() || is_page() ) {
			$kmeta = get_post_meta($post->ID, '_korsakow', true); 
			$kheight = $kmeta["height"];
		 	if ( $kheight == '' ) { 
				// no height specified;
				$kheightstring = 'height: 480px';
			} else {
				$kheightstring = 'height: ' . $kheight;
			}

			return $kheightstring;

		} 
}


// Dashboard

function my_scripts_method() {

	$kpath = get_kpath();

 	if ( $kpath ) {

	    $kStyle = plugins_url( 'assets/css/embed.css' , __FILE__ ); // Static
	    wp_register_style('kStyle',$kStyle);
	    wp_enqueue_style( 'kStyle');

	    $kSwfobject = plugins_url( 'assets/js/swfobject.js' , __FILE__ ); // Static
	    wp_register_script('kSwfobject',$kSwfobject);
	    wp_enqueue_script( 'kSwfobject');

	    wp_enqueue_script( 'jquery');

	    $kKorsakowjs = plugins_url( 'assets/js/korsakow.js.php' , __FILE__ ); // Static
	    wp_register_script('kKorsakowjs',$kKorsakowjs);
	    wp_enqueue_script( 'kKorsakowjs');

	}
}

add_action('wp_enqueue_scripts', 'my_scripts_method');
add_action( 'wp_head', 'korsakow_header' );
add_action('admin_init','my_meta_init');

function my_meta_init() {
	foreach (array('post','page') as $type) {
		add_meta_box('my_all_meta', 'Korsakow', 'my_meta_setup', $type, 'normal', 'high');
	}
	
	add_action('save_post','my_meta_save');
}

function my_meta_setup() {
	global $post; 
	$meta = get_post_meta($post->ID,'_korsakow',TRUE); ?> 

	<p><input class="widefat" type="text" name="_korsakow[path]" value="<?php if(!empty($meta['path'])) echo $meta['path']; ?>"/><br />
	<label>Path to the <em>data</em> folder (remember to include "/" at both the beginning and end)</label></p>

	<p><input class="widefat" type="text" name="_korsakow[height]" value="<?php if(!empty($meta['height'])) echo $meta['height']; ?>"/><br />
	<label>Height of Korsakow embed, including unit of measurement (eg. "500px"). Defaults to 480px if blank.</label></p>

	<?php // create a custom nonce for submit verification later
	echo '<input type="hidden" name="my_meta_noncename" value="' . wp_create_nonce(__FILE__) . '" />';
}
 
function my_meta_save($post_id) {
	if (!wp_verify_nonce($_POST['my_meta_noncename'],__FILE__)) return $post_id;

	if ($_POST['post_type'] == 'page') {
		if (!current_user_can('edit_page', $post_id)) return $post_id;
	} else {
		if (!current_user_can('edit_post', $post_id)) return $post_id;
	}

	$current_data = get_post_meta($post_id, '_korsakow', TRUE);	 
	$new_data = $_POST['_korsakow'];

	my_meta_clean($new_data);
	
	if ($current_data) {
		if (is_null($new_data)) delete_post_meta($post_id,'_korsakow');
		else update_post_meta($post_id,'_korsakow',$new_data);
	} elseif (!is_null($new_data)) {
		add_post_meta($post_id,'_korsakow',$new_data,TRUE);
	}

	return $post_id;
}

function my_meta_clean(&$arr) {
	if (is_array($arr)) {
		foreach ($arr as $i => $v) {
			if (is_array($arr[$i])) {
				my_meta_clean($arr[$i]);
				if (!count($arr[$i])) unset($arr[$i]);
			} else {
				if (trim($arr[$i]) == '') unset($arr[$i]);
			}
		}

		if (!count($arr)) $arr = NULL;
	}
}


// Theme

function korsakow_header() {

	$kpath = get_kpath();

 	if ( $kpath ) { 
		echo '<!-- This is a korsakow film. Kpath: ' . $kpath . ' -->';
    	include(MY_PLUGIN_FOLDER . '/headjs.php'); // Requires $kpath
	}

}

function korsakowPlayer() {

	$kpath = get_kpath(); 

	if ( $kpath ) {
		$kheight = get_kheight();
    	include(MY_PLUGIN_FOLDER . '/korsakowPlayer.php'); // Requires $kpath & $kheight
	} else { 
		echo '<h1>Error!</h1><p>This is supposed to be a Korsakow film but no kpath has come through.</p>';
	} 
} // End korsakowPlayer


function korsakow_content($content) {
	global $post;
	$original = $content;
	$content = korsakowPlayer();
	$content .= $original;
	return $content;
}
add_filter( 'the_content', 'korsakow_content' );

?>
