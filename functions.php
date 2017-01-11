<?php
/**
 * Parent theme style read.
 */
function theme_enqueue_styles () {
	wp_enqueue_style( 'parent-style', get_template_directory_uri() . '/style.css' );
}
add_action( 'wp_enqueue_scripts', 'theme_enqueue_styles' );

/**
 * Breadcrumb List short code add.
 */
function plugin_breadcrumb_list () {
	if ( shortcode_exists( 'wp-structuring-markup-breadcrumb' ) ) {
		echo do_shortcode( '[wp-structuring-markup-breadcrumb class="breadcrumb-list"]' );
	}
}
add_action( 'layout-wrapper-hook', 'plugin_breadcrumb_list' );

/**
 * Google Tag Manager.
 */
function theme_enqueue_script () {
	wp_enqueue_script( 'google-tag-manager', get_stylesheet_directory_uri() . '/js/google-tag-manager.js', array(), '1.0.0', false );
}
add_action( 'wp_enqueue_scripts', 'theme_enqueue_script' );

function tag_manager_script () {
	echo '<script async custom-element="amp-analytics" src="https://cdn.ampproject.org/v0/amp-analytics-0.1.js"></script>';
}
add_action( 'amp_post_template_head', 'tag_manager_script' );

function tag_manager_analytics () {
	echo '<amp-analytics config="https://www.googletagmanager.com/amp.json?id=GTM-5D4Q7KC&gtm.url=SOURCE_URL" data-credentials="include"></amp-analytics>';
}
add_action( 'amp_post_template_footer', 'tag_manager_analytics' );

/**
 * RSS Feed bug avoid.
 *
 * @version WordPress4.7
 */
add_filter( 'get_lastpostmodified', '__return_false' );

function do_not_cache_feeds( $feed ) {
	$feed->enable_cache( false );
}
add_action( 'wp_feed_options', 'do_not_cache_feeds' );