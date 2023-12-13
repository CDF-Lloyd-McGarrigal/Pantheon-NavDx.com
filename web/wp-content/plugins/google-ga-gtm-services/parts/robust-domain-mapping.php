<h4><?php _e('Robust Domain Mapping for Google Tag Manager','ga_gtm');?></h4>
<div class="js-domainGrouping">
	<?php
	$curDomain = $this->getEncodedDomain();
	if( isset( $this->options[ 'gtm_domain_mapping' ] ) && is_array( $this->options[ 'gtm_domain_mapping' ] ) ) :
		foreach( $this->options[ 'gtm_domain_mapping' ] as $index => $values ) :
			$domain = strtolower(base64_decode( $index ));
			$gtm = isset( $values[ 'gtm_robust_gtm' ] ) ? $values[ 'gtm_robust_gtm' ] : '';
			$ua = isset( $values[ 'gtm_robust_ua' ] ) ? $values[ 'gtm_robust_ua' ] : '';

			$highlight = '';
			if( $index == $curDomain ) {
				$highlight = 'active';
			}
	?>
	<div class="robustGroup <?php echo $highlight; ?>" >
		<span class="button js-removeDomain">X</span>
		<span class="activeNotice">This is the current domain</span>
		<div class="fields">
			<div class="row">
				<label for="gtm"><?php _e('Domain:','ga_gtm');?></label>
				<input type="text" id="gtm_domain" name="<?php echo self::$OPTION_NAME;?>[gtm_robust_domain][]"
				placeholder="http(s)://"
				value="<?php echo $domain ?>" /></label>
			</div>
			<div class="row">
				<label for="gtm"><?php _e('GTM Code:','ga_gtm');?></label>
				<input type="text" id="gtm" name="<?php echo self::$OPTION_NAME;?>[gtm_robust_gtm][]"
				placeholder="GTM-XXXXXX"
				value="<?php echo $gtm; ?>" /></label>
			</div>
			<div class="row">
				<label for="gtm_robust_ua"><?php _e('UA Code:','ga_gtm');?></label>
				<input type="text" id="gtm_robust_ua" name="<?php echo self::$OPTION_NAME;?>[gtm_robust_ua][]"
				placeholder="UA-XXXXX-Y"
				value="<?php echo $ua;?>" /><br>
				<span>Create a variable in GTM of type "Data Layer Variable" with name "wordpress_ua_code" to access.</span>
			</div>
		</div>
		<br><hr><br>
	</div>
	<?php
		endforeach;
	endif;
	?>
</div>

<p><span id="js-addDomain" class="button button-secondary">Add Domain</span></p>
<div class="robustGroup js-domainClone" style="display:none">
	<span class="button js-removeDomain">X</span>
	<div class="fields">
		<div class="row">
			<label for="gtm"><?php _e('Domain:','ga_gtm');?></label>
			<input type="text" id="gtm_robust_domain" name="<?php echo self::$OPTION_NAME;?>[gtm_robust_domain][]"
			placeholder="http(s)://"
			value="" /></label>
		</div>
		<div class="row">
			<label for="gtm"><?php _e('GTM Code:','ga_gtm');?></label>
			<input type="text" id="gtm" name="<?php echo self::$OPTION_NAME;?>[gtm_robust_gtm][]"
			placeholder="GTM-XXXXXX"
			value="" /></label>
		</div>
		<div class="row">
			<label for="gtm_robust_ua"><?php _e('UA Code:','ga_gtm');?></label>
			<input type="text" id="gtm_robust_ua" name="<?php echo self::$OPTION_NAME;?>[gtm_robust_ua][]"
			placeholder="UA-XXXXX-Y"
			value="" /><br>
			<span>Create a variable in GTM of type "Data Layer Variable" with name "wordpress_ua_code" to access.</span>
		</div>
	</div>
	<br><hr><br>
</div>

<script>
	$ = jQuery;
	$( '#js-addDomain' ).on( 'click', function( event ){
		event.preventDefault();
		var newGroup = $( '.js-domainClone' )
			.clone()
			.removeClass( 'js-domainClone' )
			.show()
			.eq(0);
		$( '.js-domainGrouping' ).append( newGroup );
	});

	$( '.js-domainGrouping' ).on( 'click', '.js-removeDomain', function( event ){
		event.preventDefault();
		var $this = $( this );
		$this.parents( '.robustGroup' ).remove();
	});
</script>
<style>
	div.robustGroup .js-removeDomain {
		float:left;
		background:red;
		color:white;
		border-radius: 50%;
	}

	div.robustGroup {
		display: inline-block;
		position: relative;
	}

	div.robustGroup .activeNotice {
		position: absolute;
		right: 10px;
		top: 5px;
		display:none;
		font-weight: 700;
	}

	div.robustGroup.active .activeNotice {
		display: block;
	}

	div.robustGroup .fields {
		display: table;
		padding: 5px;
		margin-left: 50px;
	}

	div.robustGroup .fields .row {
		display:table-row;
	}
	
	div.robustGroup .fields .row input[text], div.robustGroup .fields .row label {
		display:table-cell;
	}

	div.robustGroup.active .fields{ 
		border: 3px groove rgb(241, 241, 241); 
	}
</style>