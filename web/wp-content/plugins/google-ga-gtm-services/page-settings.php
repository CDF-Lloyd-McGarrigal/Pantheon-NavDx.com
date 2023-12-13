<?php

$gtmHeader = 'Google Tag Manager';
$extraClass = '';

if( $this->isRobustGTMActive() ){
	$gtmHeader = 'Fallback ' . $gtmHeader;
	$extraClass = 'gtmRobust';
}
?>
<div class="wrap <?echo $extraClass; ?>">
	<?php screen_icon(); ?>
	<h2><?php echo self::$PAGE_TITLE; ?></h2> 
	<h3><?php _e('Google Analytics', 'ga_gtm')?></h3>
	<div><?php // _e('Select Google Services', 'ga_gtm')?></div>
		<form method="post" action="options.php">
			<?php
	                // This prints out all hidden setting fields
			settings_fields( self::$OPTION_GROUP );  
	             //  do_settings_sections( self::$SETTINGS_ADMIN );
			?>
			<div class="table">

				<!-- GA OPTIONS -->
				<?php if (!empty($this->adminOptions['ga'])) : ?>
				<h4><?php _e('ga Global Object','ga_gtm');?></h4>
				<div class="row">
					<label for="ga"><?php _e('UA Code:','ga_gtm');?>&nbsp;</label>
					<input type="text" id="ga" name="<?php echo self::$OPTION_NAME;?>[ga_ua]"
					placeholder="UA-XXXXX-Y"
					value="<?php if (!empty($this->options['ga_ua'])) echo esc_attr($this->options['ga_ua']);?>" /></label>
				</div>
				<?php elseif (!empty($this->options['_gaq_ua'])) : ?>	
					<input type="hidden" name="<?php echo self::$OPTION_NAME;?>[ga_ua]" value="<?php echo esc_attr($this->options['ga_ua']);?>" />
				<?php endif; ?>
				<!-- END GA OPTIONS -->

				<!-- GAQ OPTIONS -->
				<?php if (!empty($this->adminOptions['_gaq'])) : ?>
				<h4><?php _e('_gaq Global Object','ga_gtm');?></h4>
				<div class="row">
					<label for="gaq"><?php _e('UA Code:','ga_gtm');?></label>
					<input type="text" id="gaq" name="<?php echo self::$OPTION_NAME;?>[_gaq_ua]"
					placeholder="UA-XXXXX-Y"
					value="<?php if (!empty($this->options['_gaq_ua'])) echo esc_attr($this->options['_gaq_ua']);?>" /></label>
				</div>
				<?php elseif (!empty($this->options['_gaq_ua'])) : ?>	
					<input type="hidden" name="<?php echo self::$OPTION_NAME;?>[_gaq_ua]" value="<?php echo esc_attr($this->options['_gaq_ua']);?>" />
				<?php endif; ?>
				<!-- END GAQ OPTIONS -->

				<!-- GTM SETTINGS -->
				<?php if (!empty($this->adminOptions['gtm'])) : ?>
					<h4><?php _e( $gtmHeader,'ga_gtm');?></h4>
					<p class="fallbackNotice"><strong>This will be used if there is no GTM code for the current domain.</strong></p>

					<div class="group">
						<div class="row">
							<label for="gtm"><?php _e('GTM Code:','ga_gtm');?></label>
							<input type="text" id="gtm" name="<?php echo self::$OPTION_NAME;?>[gtm_gtm]"
							placeholder="GTM-XXXXXX"
							value="<?php if (!empty($this->options['gtm_gtm'])) echo esc_attr($this->options['gtm_gtm']);?>" /></label>
						</div>
						<div class="row">
							<label for="gtm_ua"><?php _e('UA Code:','ga_gtm');?></label>
							<input type="text" id="gtm_ua" name="<?php echo self::$OPTION_NAME;?>[gtm_ua]"
							placeholder="UA-XXXXX-Y"
							value="<?php if (!empty($this->options['gtm_ua'])) echo esc_attr($this->options['gtm_ua']);?>" /><br>
							<span>Create a variable in GTM of type "Data Layer Variable" with name "wordpress_ua_code" to access.</span>
						</div>
					</div><br>
					
				<?php elseif (!empty($this->options['gtm_gtm'])) : ?>	
					<input type="hidden" name="<?php echo self::$OPTION_NAME;?>[gtm_gtm]" value="<?php echo esc_attr($this->options['gtm_gtm']);?>" />
					<input type="hidden" name="<?php echo self::$OPTION_NAME;?>[gtm_ua]" value="<?php if (!empty($this->options['gtm_ua'])) echo esc_attr($this->options['gtm_ua']);?>" />
				<?php endif; ?>
				<!-- END GTM SETTINGS -->
				
				</div>

				<?php 
				if ( $this->isRobustGTMActive() ) {
					include 'parts/robust-domain-mapping.php'; 
				}
				?>

				<?php  submit_button(); ?>
			</form>
		</div>

		<!-- GA OPT OUT LINK -->
		<?php if (!empty($this->adminOptions['optout'])) : ?>
			<h4><?php _e('Google Analytics opt-out link:','ga_gtm');?></h4>
			<div>
				<code>&lt;a href="javascript:gaOptout();"&gt;Opt-out&lt;/a&gt;</code>
			</div>
		<?php endif; ?>
		<!-- END GA OPT OUT LINK -->

		<style type="text/css"> 
			.fallbackNotice {
				display: none;
			}

			.gtmRobust .fallbackNotice {
				display: block;
			}
			
			div.table { display: table; }
			div.table h4,
			div.table .row {display:table-row;}
			div.table .row input[text],
			div.table .row label {display:table-cell;}
		</style>
