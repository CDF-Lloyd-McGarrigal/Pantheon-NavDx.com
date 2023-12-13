<?php
/**
 * The template for displaying 404 pages (not found).
 *
 * @package navdx
 */

get_header(); ?>

	<div id="primary" class="content-area">
		<main id="main" class="site-main" role="main">

			<section class="error-404 not-found">
				<header class="page-header">
					<h1 class="page-title"><?php _e(get_theme_option('404_title'), 'navdx' ); ?></h1>
				</header><!-- .page-header -->

				<div class="page-content">
					<p><?php _e(get_theme_option('404_body'), 'navdx' ); ?></p>

				</div><!-- .page-content -->
			</section><!-- .error-404 -->

		</main><!-- #main -->
	</div><!-- #primary -->

<?php get_footer(); ?>
