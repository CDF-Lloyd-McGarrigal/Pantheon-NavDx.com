<?php
$gtmChecked = !empty($this->adminOptions['gtm']) ? 'checked' : '';
$gtmRobustChecked = !empty($this->adminOptions['gtm_robust']) ? 'checked' : '';
$gtmIpAnonChecked = !empty($this->adminOptions['gtm_ip_anon']) ? 'checked' : '';
$gtmCookieExceptionChecked = !empty($this->adminOptions['gtm_cookie_exception']) ? 'checked' : '';

$gtmCEParameter = !empty($this->adminOptions['gtm_ce_parameter']) ? htmlspecialchars($this->adminOptions['gtm_ce_parameter']) : '';
?>
<div class="wrap">
	<?php screen_icon(); ?>
	<h2><?php _e(self::$PAGE_TITLE, 'ga_gtm'); ?></h2> 
	<h3><?php _e('Admin Settings', 'ga_gtm')?></h3>
	<div style="margin-bottom: 1em;"><?php _e('DON’T CHANGE THESE SETTINGS UNLESS YOU FULLY UNDERSTAND WHAT YOU’RE DOING', 'ga_gtm');?></div>
	<div><?php _e('Select Google Services', 'ga_gtm')?></div>
	<form name="admin_settings" method="post" action="options.php">
		<?php
// This prints out all hidden setting fields
		settings_fields( self::$ADMIN_OPTION_GROUP );  
//  do_settings_sections( self::$SETTINGS_ADMIN );
		?>
		<div class="row"><label for="ga">
			<input type="checkbox" id="ga" name="<?php echo self::$ADMIN_OPTION_NAME;?>[ga]" value="1" <?php 
			if (!empty($this->adminOptions['ga'])) echo ' checked';?>/><?php _e('Google Analytics: ga Global Object','ga_gtm');?></label>
			<div class="col2"><label for="ga_pageview">
				<input type="checkbox" id="ga_pageview" name="<?php echo self::$ADMIN_OPTION_NAME;?>[ga_pageview]" value="1" <?php 
				if (!empty($this->adminOptions['ga_pageview'])) echo ' checked';?>/><?php _e('Send <b>pageview</b>','ga_gtm');?></label>			 	
			</div>
		</div>
		<div class="row"><label for="_gaq">
			<input type="checkbox" id="_gaq" name="<?php echo self::$ADMIN_OPTION_NAME;?>[_gaq]" value="1" <?php 
			if (!empty($this->adminOptions['_gaq'])) echo ' checked';?>/><?php _e('Google Analytics: _gaq Global Object','ga_gtm');?></label>
			<div class="col2"><label for="_gaq_pageview">
				<input type="checkbox" id="_gaq_pageview" name="<?php echo self::$ADMIN_OPTION_NAME;?>[_gaq_pageview]" value="1" <?php 
				if (!empty($this->adminOptions['_gaq_pageview'])) echo ' checked';?>/><?php _e('Send <b>_trackPageview</b>','ga_gtm');?></label>			 	
			</div>
		</div>


		<div class="row">
			<label for="gtm">
				<input type="checkbox" id="gtm" name="<?php echo self::$ADMIN_OPTION_NAME;?>[gtm]" value="1" <?php echo $gtmChecked;?> /><?php _e('Google Tag Manager','ga_gtm');?>
			</label>
			<div class="col2">
				<label for="gtm_robust">
					<input type="checkbox" id="gtm_robust" name="<?php echo self::$ADMIN_OPTION_NAME;?>[gtm_robust]" value="1" <?php echo $gtmRobustChecked; ?> />
					<?php _e('Use Robust GTM','ga_gtm');?>
				</label>
			</div>
			<div class="col2">
				<label for="gtm_ip_anon">
					<input type="checkbox" id="gtm_ip_anon" name="<?php echo self::$ADMIN_OPTION_NAME;?>[gtm_ip_anon]" value="1" <?php echo $gtmIpAnonChecked; ?> />
					<?php _e('Support IP Anonymization Filtering (pushes IP Address to the dataLayer)','ga_gtm');?>
				</label>
			</div>
			<div class="col2">
				<label for="gtm_cookie_exception">
					<input type="checkbox" id="gtm_cookie_exception" name="<?php echo self::$ADMIN_OPTION_NAME;?>[gtm_cookie_exception]" value="1" <?php echo $gtmCookieExceptionChecked; ?> />
					<?php _e('Made GTM script an exception for cookie blocker','ga_gtm');?>
				</label>
			</div>
			<div id="gtm_ce_parameter_wrapper" class="col2">
				<label for="gtm_ce_parameter">
					<?php _e('The parameter to set to bypass cookie blocker. Commonly set parameters below:','ga_gtm');?><br>
					<input type="text" id="gtm_ce_parameter" class="regular-text" name="<?php echo self::$ADMIN_OPTION_NAME;?>[gtm_ce_parameter]" value="<?php echo $gtmCEParameter; ?>"/>
				</label>
				<ul style="margin:0">
					<li><strong>Cookiebot: </strong><pre style="display:inline-block">data-cookieconsent="ignore"</pre></li>
				</ul>
			</div>
		</div>


		<div class="row"><label for="optout">
			<input type="checkbox" id="optout" name="<?php echo self::$ADMIN_OPTION_NAME;?>[optout]" value="1" <?php 
			if (!empty($this->adminOptions['optout'])) echo ' checked';?>/><?php _e('Opt Out','ga_gtm');?></label>
		</div>

		<?php  submit_button(); ?>
	</form>
	<style>
	.wrap .row { margin-top:1em; }
	.wrap .row .col2 { margin-left: 4em; }
</style> 	
</div>
<script type="text/javascript">
	<!--
		jQuery(document).ready(function($) {
			$('#ga').change(function() {
				if (!$(this).is(':checked')) {
					$('#ga_pageview').attr('disabled', true);
				} else {
					$('#ga_pageview').removeAttr('disabled');
				}
				optout_rerender();
			}).change();

			$('#_gaq').change(function() {
				if (!$(this).is(':checked')) {
					$('#_gaq_pageview').attr('disabled', true);
				} else {
					$('#_gaq_pageview').removeAttr('disabled');
				}
				optout_rerender();
			}).change();

			$('#gtm').change(function() {

				if (!$(this).is(':checked')) {
					$('#gtm_robust').attr('disabled', true);
					$('#gtm_ip_anon').attr('disabled', true);
					$('#gtm_cookie_exception').attr('disabled', true);
					$('#gtm_ce_parameter').attr('disabled', true);
				} else {
					$('#gtm_robust').removeAttr('disabled');
					$('#gtm_ip_anon').removeAttr('disabled');
					$('#gtm_cookie_exception').removeAttr('disabled');
					$('#gtm_ce_parameter').removeAttr('disabled');
				}

				optout_rerender();
			}).change();

			$('#gtm_cookie_exception').change(function() {

				if (!$(this).is(':checked')) {
					$('#gtm_ce_parameter_wrapper').hide();
					$('#gtm_ce_parameter').attr('disabled', true);
				} else {
					$('#gtm_ce_parameter').removeAttr('disabled');
					$('#gtm_ce_parameter_wrapper').show();					
				}

				optout_rerender();
			}).change();

			function optout_rerender() {
				if (!$('#ga').is(':checked') && !$('#_gaq').is(':checked') && !$('#gtm').is(':checked')) {
					$('#optout').attr('disabled', true);
				} else {
					$('#optout').removeAttr('disabled');
				}
			}

		});
//-->
</script>
