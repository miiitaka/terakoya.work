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
	if ( !is_user_logged_in() ) {
		wp_enqueue_script( 'google-tag-manager', get_stylesheet_directory_uri() . '/js/google-tag-manager.js', array(), '1.0.0', false );
	}
}
add_action( 'wp_enqueue_scripts', 'theme_enqueue_script' );

/**
 * Google Tag Manager(AMP).
 */
function tag_manager_script () {
	if ( !is_user_logged_in() ) {
		echo '<script async custom-element="amp-analytics" src="https://cdn.ampproject.org/v0/amp-analytics-0.1.js"></script>';
	}
}
add_action( 'amp_post_template_head', 'tag_manager_script' );

function tag_manager_analytics () {
	if ( !is_user_logged_in() ) {
		echo '<amp-analytics config="https://www.googletagmanager.com/amp.json?id=GTM-5D4Q7KC&gtm.url=SOURCE_URL" data-credentials="include"></amp-analytics>';
	}
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
		$content = '<figure>' . get_the_post_thumbnail( $post->ID ) . '</figure>' . $content;
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
function display_plugin_info( array $atts ) {
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
			$html .= '<a href="' . esc_url( $plugin->homepage ) . '"><img src="' . esc_html( $plugin->icons['1x'] ) . '" alt="' . esc_attr( $plugin->name ) . '" width="128" height="128"></a>';
			$html .= '</figure>';
			$html .= '<ul>';
			$html .= '<li><a href="' . esc_url( $plugin->homepage ) . '">' . esc_html( $plugin->name ) . '</a></li>';
			$html .= '<li>Author : ' . $plugin->author . '</li>';
			$html .= '<li>Version : ' . esc_html( $plugin->version ) . '</li>';
			$html .= '<li>Downloads : ' . esc_html( number_format( $plugin->downloaded ) ) . ' ( Active Install: ' . esc_html( number_format( $plugin->active_installs ) ) . ' )</li>';
			$html .= '<li>Downloads Site: <a href="https://wordpress.org/plugins/' . esc_html( $plugin->slug ) . '/" target="_blank">https://wordpress.org/plugins/' . esc_html( $plugin->slug ) . '</a></li>';
			$html .= '<li>' . esc_html( $plugin->short_description ) . '</li>';
			$html .= '</ul>';
			$html .= '</section>';
		}
	}
	return (string) $html;
}
add_shortcode( 'plugin_info', 'display_plugin_info' );

/**
 * GitHub Display
 *
 * @param  array  $atts
 * @return string $html
 */
function display_github_info( array $atts ) {
	$args = array(
		'repository' => ''
	);
	extract( shortcode_atts( $args, $atts ) );

	$url   = "https://github.com/miiitaka/";

	$html  = '<section class="display-plugin-info">';
	$html .= '<figure>';
	$html .= '<a href="' . $url . esc_attr( $repository ) . '"><img src="https://www.terakoya.work/wp-content/uploads/2017/03/octocat-monalisa.png" alt="GitHub" width="128" height="128"></a>';
	$html .= '</figure>';
	$html .= '<ul>';
	$html .= '<li><a href="' . $url . esc_attr( $repository ) . '">' . esc_html( $repository ) . '</a></li>';
	$html .= '<li>Author : <a href="' . $url . '">Kazuya Takami</a></li>';
	$html .= '<li>Repository URL : <a href="' . $url . esc_attr( $repository ) . '">' . $url . esc_attr( $repository ) . '</a></li>';
	$html .= '</ul>';
	$html .= '</section>';

	return (string) $html;
}
add_shortcode( 'github_info', 'display_github_info' );

/**
 * Amazon affiliate (Sidebar)
 */
function amazon_affiliate_sidebar() {
	if ( !is_user_logged_in() ) {
		$html  = '<aside class="layout-sidebar-top-hook">';
		$html .= '<iframe src="https://rcm-fe.amazon-adsystem.com/e/cm?o=9&p=12&l=ez&f=ifr&linkID=3a17a6b1b93e5208b8ca2f21df5c68b3&t=miiitaka-22&tracking_id=miiitaka-22" width="300" height="250" scrolling="no" border="0" marginwidth="0" style="border:none;" frameborder="0"></iframe>';
		$html .= '</aside>';
		echo $html;
	}
}
add_action( 'layout-sidebar-bottom-hook', 'amazon_affiliate_sidebar' );

/**
 * Adsense (Sidebar Top)
 */
function adsense_affiliate_sidebar_top() {
	if ( !is_user_logged_in() ) {
		$html  = '<aside class="layout-sidebar-top-hook">';
		$html .= '<script async src="//pagead2.googlesyndication.com/pagead/js/adsbygoogle.js"></script>
		<!-- Sidebar top banner -->
		<ins class="adsbygoogle"
		     style="display:inline-block;width:300px;height:250px"
		     data-ad-client="ca-pub-5741984081497449"
		     data-ad-slot="4152791607"></ins>
		<script>
		(adsbygoogle = window.adsbygoogle || []).push({});
		</script>';
		$html .= '</aside>';
		echo $html;
	}
}
add_action( 'layout-sidebar-top-hook', 'adsense_affiliate_sidebar_top', 5 );

/**
 * Adsense affiliate (Post)
 */
function adsense_affiliate_post() {
	if ( !is_user_logged_in() ) {
		$html  = '<aside class="adsense-affiliate-post widget">';
		$html .= '<script async src="//pagead2.googlesyndication.com/pagead/js/adsbygoogle.js"></script>
		<!-- Post header banner -->
		<ins class="adsbygoogle"
		     style="display:block"
		     data-ad-client="ca-pub-5741984081497449"
		     data-ad-slot="2192329214"
		     data-ad-format="auto"></ins>
		<script>
		(adsbygoogle = window.adsbygoogle || []).push({});
		</script>';
		$html .= '</aside>';
		echo $html;
	}
}
add_action( 'layout-post-hook', 'adsense_affiliate_post' );

/**
 * Comment Control
 */
 function comment_form_control( $args ) {
	 $args['fields']['email']      = '';
	 $args['comment_notes_before'] = '';
	 return $args;
 }
 add_filter( 'comment_form_defaults', 'comment_form_control' );

 /**
  * Post list thumbnail
  */
function manage_posts_columns( $columns ) {
	$columns['thumbnail'] = __( 'Feature Image');
	return $columns;
}

function add_column( $column_name, $post_id ) {
	if ( 'thumbnail' == $column_name ) {
		$thumbnail = get_the_post_thumbnail( $post_id, 'thumbnail', array( 'style' => 'width: 100%;' )  );
	}
	if ( isset( $thumbnail ) && $thumbnail ) {
		echo preg_replace( '/(width|height)="\d*"\s/', '', $thumbnail );
	}
}
add_filter( 'manage_posts_columns', 'manage_posts_columns' );
add_action( 'manage_posts_custom_column', 'add_column', 10, 2 );

/**
 * jQuery Migrate remove.
 */
function dequeue_jquery_migrate( $scripts ) {
	if( !is_admin() ) {
		$scripts->remove( 'jquery' );
		$scripts->add( 'jquery', false, array( 'jquery-core' ) );
	}
}
add_filter( 'wp_default_scripts', 'dequeue_jquery_migrate' );

remove_filter('widget_text_content', 'wpautop');

function themes_add_quicktags () {
	if ( wp_script_is( 'quicktags' ) ) {
		$html  = '<script>';
		$html .= 'QTags.addButton( "eg_paragraph", "p", "<p>", "</p>", "p", "Paragraph tag", 1 );';
		$html .= '</script>';

		echo $html;
	}
}
add_action( 'admin_print_footer_scripts', 'themes_add_quicktags' );