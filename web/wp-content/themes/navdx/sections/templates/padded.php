<?php
/**
 * Template for the padded one column
 */

$customClasses = "";
$jumpLinkName = "";

$customClasses = isset( $section[ 'custom_classes' ] ) ? $section[ 'custom_classes' ] : '';
$jumpLinkName = isset($section['jumpLinkName']) ? $section['jumpLinkName'] : '';
$sectionId = isset( $section[ 'jumpLinkName' ] ) ? $section[ 'jumpLinkName' ].'-section' : '';

?>

<section class="<?php echo $customClasses; ?>" id="<?= $sectionId; ?>" data-post-id="<?= $section['post']->ID ?>" data-jumplink="<?php echo $jumpLinkName; ?>" role="contentinfo"<?= !empty($jumpLinkName) ? ' aria-label="'.$jumpLinkName.'"' : ''; ?>>
        <a style="display: none; position: absolute;" href="/wordpress/wp-admin/post.php?post=<?= $section['post']->ID ?>&action=edit&classic-editor"></a>
	<div class="oneColumn padded">
		<div class="contentWrapper">
			<?php rtrc_build_components( $section[ 'post' ]->ID ); ?>
		</div>
	</div>
</section>
