<?php
/**
 * The header for our theme.
 *
 * Displays all of the <head> section and everything up till <div id="content">
 *
 * @package navdx
 */
?><!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
	<?php add_x_ua_compatability_meta(); ?>
	<meta charset="<?php bloginfo( 'charset' ); ?>">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<link rel="profile" href="http://gmpg.org/xfn/11">
	<link rel="pingback" href="<?php bloginfo( 'pingback_url' ); ?>">

	<link rel="preconnect" href="https://fonts.googleapis.com">
	<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
	<link href="https://fonts.googleapis.com/css2?family=Orbitron:wght@700&display=swap" rel="stylesheet">
	<link rel="icon" type="image/png" href="<?php echo img_url_from_id(get_theme_option('header_favicon')); ?>"/>

	<!-- Google tag (gtag.js) -->
	<script async src="https://www.googletagmanager.com/gtag/js?id=G-DJ9MZQWTPL"></script>
	<script>
	  window.dataLayer = window.dataLayer || [];
	  function gtag(){dataLayer.push(arguments);}
	  gtag('js', new Date());

	  gtag('config', 'G-DJ9MZQWTPL');
	</script>

	<?php wp_head(); ?>
</head>

<body <?php body_class(); ?>>
<div id="page" class="hfeed site">

	<header id="masthead" class="site-header" role="banner">
		<div class="content">
			<a href="<?= get_theme_option('header_image_link'); ?>"><?php img_from_id(get_theme_option('header_image'), 'logo'); ?></a>
			<div class="menus">
				<?php
				wp_nav_menu([
					'container' => 'nav',
					'container_id' => 'site-menu',
					'theme_location' => 'primary',
					'walker' => new Custom_Nav_Walker(),
				]);
				wp_nav_menu([
					'container' => 'nav',
					'container_id' => 'action-menu',
					'theme_location' => 'eyebrow',
				]);
				?>
			</div>
			<div class="hamburger">
				<img class="open-menu" src="<?php echo get_stylesheet_directory_uri() ?>/images/hamburger.svg" />
				<img class="close-menu" src="<?php echo get_stylesheet_directory_uri() ?>/images/close-menu.svg" />
			</div>
		</div>
	</header><!-- #masthead -->

	<main id="content" class="site-content">
