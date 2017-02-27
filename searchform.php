<?php
/**
 * Template for displaying search forms
 *
 * @package    WordPress
 * @subpackage Maitake
 * @since      1.0.0
 */
?>

<form method="get" action="<?php echo esc_url( home_url( '/' ) ); ?>">
	<label>
		<input type="search" placeholder="Search" value="<?php echo get_search_query(); ?>" name="s">
	</label>
	<button type="submit"><span class="dashicons dashicons-search"></span></button>
</form>

<h3 class="frequently-searched-words-title">よく検索されるキーワード</h3>
<?php
if ( shortcode_exists( 'wp-frequently-searched-words' ) ) {
	echo do_shortcode( '[wp-frequently-searched-words class="frequently-searched-words"]' );
}