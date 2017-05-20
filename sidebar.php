<?php
/**
 * The template for the sidebar containing the main widget area
 *
 * @package    WordPress
 * @subpackage Maitake
 * @since      1.0.0
 */
?>

<?php if ( is_active_sidebar( 'sidebar-1' )  ) : ?>
	<aside class="layout-sidebar">
		<figure class="wckyoto2017">
			<a href="https://2017.kyoto.wordcamp.org/" target="_blank">
				<img src="https://2017.kyoto.wordcamp.org/files/2017/05/banner-300x250.png" alt="WordCamp Kyoto 2017">
			</a>
		</figure>
		<?php dynamic_sidebar( 'sidebar-1' ); ?>
	</aside>
<?php endif; ?>