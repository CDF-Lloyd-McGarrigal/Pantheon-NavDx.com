<?php
/**
 * The template for displaying the footer.
 *
 * Contains the closing of the #content div and all content after
 *
 * @package navdx
 */

$patent = empty(this_post_meta('change_patent_code')) ? get_theme_option('patent') : this_post_meta('change_patent_code');
$social = get_theme_option('sites');
?>

</main><!-- #content -->

	<footer id="colophon" class="site-footer" role="contentinfo">

		<div class="top-content">
			<div class="contentWrapper">
				<div class="footer-navDX-logo">
					<a href="<?= get_theme_option('product_logo_link'); ?>"><?php img_from_id(get_theme_option('product_logo'), 'logo navdx'); ?></a>
				</div>

				<div class="social-links">
					<?php foreach($social as $link): ?>
						<a href="<?= $link['url']; ?>"><?php img_from_id($link['social_icon'], 'logo social'); ?></a>
					<?php endforeach; ?>
				</div>

				<div class="footer-naveris-logo">
					<a target="_blank" href="<?= get_theme_option('manufacturer_logo_link'); ?>"><?php img_from_id(get_theme_option('manufacturer_logo'), 'logo naveris'); ?></a>
				</div>
			</div>
		</div>

		<div class="bottom">
			<div class="contentWrapper">
				<?php
				wp_nav_menu([
					'container' => 'nav',
					'container_id' => 'footer-menu',
					'theme_location' => 'footer',
				]);
				?>
				<div class="company-information">
					<p class="address"><?= get_theme_option('address'); ?></p>
					<?php
					wp_nav_menu([
						'container' => 'nav',
						'container_id' => 'direct-links',
						'theme_location' => 'contact',
					]);
					?>
					<p>
						<span class="copyright"><?= get_theme_option('copyright'); ?></span>
						<span class="patent"><?= $patent; ?></span>
					</p>
				</div>
			</div>
		</div>
	</footer><!-- #colophon -->
</div><!-- #page -->

<?php wp_footer(); ?>
<section class="modal-popup" data-remodal-id="registration-modal" data-remodal-options='{ "hashTracking": false, "closeOnEscape": false, "closeOnOutsideClick": false }'>
    <i class="close-button" data-remodal-action="cancel"></i>
    <?= wysiwyg_format(get_theme_option('modal_form')) ?>
</section>
</body>
</html>
