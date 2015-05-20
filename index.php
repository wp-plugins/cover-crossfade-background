<?php
/**
 * Plugin Name: Cover CrossFade Background (CCB)
 * Author: Martijn Michel, TocadoVision
 * Description: Insert one or more cover backgrounds on a per page basis. (optional with crossFade)
 * Version: 1.01
 */

// CREATE SQL TABLE
if (is_admin()) {

	
	include 'postmeta.php';

	$sql = "CREATE TABLE IF NOT EXISTS wp_coverbg (
			id mediumint(9) NOT NULL AUTO_INCREMENT,
			page tinytext NOT NULL,
			img tinytext NOT NULL,
			UNIQUE KEY id (id)
		);";
	require_once (ABSPATH . 'wp-admin/includes/upgrade.php');
	dbDelta($sql);

}

// load crossFadeJS and output a DIV.bg per record found in DB.
add_action('wp_head', 'initbg');

function initbg() {
	


	$value = get_post_meta(get_the_ID(), 'coverbg', true);
	

	// if a background is set insert into page.
	if ($value != NULL) {

		$imgs = explode(',', $value);

		foreach ($imgs as $img) {

			echo '<div class="bg ' . $img . '" style="background: url(http://localhost/wp-content/gallery/cover/' . $img . '.jpg) no-repeat center center fixed;"></div>';

		}
	}
	
	
	// if no background is set use default settings (from homepage!)
	if ($value == NULL) {
		$home_id = get_option('page_on_front');
		$value = get_post_meta($home_id, 'coverbg', true);
		$imgs = explode(',', $value);
		foreach ($imgs as $img) {
			echo '<div class="bg ' . $img . '" style="background: url(http://localhost/wp-content/gallery/cover/' . $img . '.jpg) no-repeat center center fixed;"></div>';

		}
	}

}

// load stylesheet
add_action('wp_enqueue_scripts', 'loadscripts');

function loadscripts() {
	$plugins_url = plugins_url();
	wp_enqueue_style('stylecss', plugins_url('style.css', __FILE__));
	wp_enqueue_script('crossfade', plugins_url('crossFade.js', __FILE__));
	
}

// load admin scripts
add_action('admin_enqueue_scripts', 'addj');
function addj() {
	$screen = get_current_screen();
	if($screen->post_type == 'page' || $screen->post_type == 'post') {
		wp_enqueue_style('stylecss', plugins_url('style.css', __FILE__));
	wp_enqueue_script('custom-js', plugins_url('coverbg/script.js', dirname(__FILE__)));
	}
}
?>
