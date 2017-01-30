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
 * RSS Feed Thumbnail add.
 *
 * @param  string $content
 * @return string $content
 */
function rss_feed_thumbnail( $content ) {
	global $post;

	if ( has_post_thumbnail( $post->ID ) ) {
		$content = '<figure>' . get_the_post_thumbnail($post->ID) . '</figure>' . $content;
	}

	// Delete unnecessary automatic insertion tag
	$content = str_replace( ' />', '>', $content );
	return $content;
}
add_filter( 'the_excerpt_rss',  'rss_feed_thumbnail' );
add_filter( 'the_content_feed', 'rss_feed_thumbnail' );

/**
 * Delete unnecessary automatic insertion tag
 */
add_action( 'init', function () {
	remove_filter( 'the_excerpt',      'wpautop' );
	remove_filter( 'the_content',      'wpautop' );
	remove_filter( 'the_excerpt_rss',  'wpautop' );
	remove_filter( 'the_content_feed', 'wpautop' );
});


/**
 * WordPress Plug-in Display
 *
 * @param  array  $atts
 * @return string $html
 */
function display_plugin_info( $atts ) {
	$args = array(
		'author'      => '',
		'plugin_slug' => ''
	);
	extract( shortcode_atts( $args, $atts ) );
	require_once( ABSPATH . 'wp-admin/includes/plugin-install.php' );

	$query = 'query_plugins';
	$arg['author'] = $author;
	$arg['fields'] = array(
		'active_installs' => true,
		'compatibility'   => true,
		'downloaded'      => true,
		'icons'           => true
	);
	$plugins = plugins_api( $query, $arg )->plugins;
	$html    = '';

	foreach ( $plugins as $plugin ) {
		if ( $plugin->slug === $plugin_slug || empty( $plugin_slug ) ) {
			$html .= '<section class="display-plugin-info">';
			$html .= '<figure>';
			$html .= '<a href="' . esc_url( $plugin->homepage ) . '"><img src="' . esc_html( $plugin->icons['1x'] ) . '" alt="' . esc_html( $plugin->name ) . ' width="128" height="128"></a>';
			$html .= '</figure>';
			$html .= '<ul>';
			$html .= '<li><a href="' . esc_url( $plugin->homepage ) . '">' . esc_html( $plugin->name ) . '</a></li>';
			$html .= '<li>Author : ' . $plugin->author . '</li>';
			$html .= '<li>Version : ' . esc_html( $plugin->version ) . '</li>';
			$html .= '<li>Downloads : ' . esc_html( $plugin->downloaded ) . ' ( Active Install: ' . esc_html( $plugin->active_installs ) . ' )</li>';
			$html .= '<li>' . esc_html( $plugin->short_description ) . '</li>';
			$html .= '</ul>';
			$html .= '</section>';
		}
	}
	return $html;
}
add_shortcode( 'plugin_info', 'display_plugin_info' );