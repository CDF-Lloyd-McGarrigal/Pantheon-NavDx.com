<?php
/*
 Plugin Name: Google GA/GTM Services Plugin
Plugin URI: http://arteric.com
Description:
Network: false
Author: Oleg, arteric.com
Version: 3.3.0
Author URI: http://arteric.com
Text Domain: ga_gtm
*/

class GoogleGtmGa {
	private static $_instance;
	public static $VERSION = "3.3.0";
	static $PAGE_TITLE = "Google Analytics and Tag Manager Settings";
	// Google Tag Manager
	static $PLUGIN_DIR;
	static $PLUGIN_URL;
	protected 
		$adminOptions,
		$options;
	
	private static 
		$SETTINGS_ADMIN = 'ga-admin',
		$ADMIN_OPTION_GROUP = "admin-ga-group",
		$ADMIN_OPTION_NAME= 'ga_gtm_admin',
		$OPTION_GROUP= 'ga-group',
		$OPTION_NAME= 'ga_gtm';
	public static 
		$FIELD_GA_ID = 'ga_id',
		$FIELD_GTM_ID = 'gtm_id';
	
	
	public static function getInstance() {
		if (!isset(self::$_instance)) {
			self::$_instance = new self();
		}
		return self::$_instance;
	}
	
	private function __construct() {
		self::$PLUGIN_DIR = plugin_dir_path(__FILE__);
		self::$PLUGIN_URL = plugin_dir_url( __FILE__ );
		add_action('init',  array($this, 'init'));
		add_action('admin_init',  array($this, 'page_init'));
		add_action('admin_menu', array( $this, 'add_plugin_page' ) );		
	}
	
	public function checkDependency() {
		return (class_exists('GtmGA') || class_exists('CelgeneGA'));
	}
	
	public function members_get_capabilities( $caps ) {
		return array_merge($caps, array('gtm-ga-settings','gtm-ga-ua-settings'));
	}

	/**
	 * Get the base64_encoded version of the domain, sanitized for consistency
	 * @param  string $domain The domain in question. Defaults to the SERVER_HOST otherwise
	 * 
	 * @return string         The base64 encoding of the domain
	 */
	private function getEncodedDomain( $domain = '' ){
			
		// If we aren't trying to get a domain...
		if( $domain == '' ) {
			// Get the domain from the request
			$domain = $this->currentDomain;
		}

		// Lowercase the whole domain
		$domain = strtolower($domain);

		// Replace HTTP://, HTTPS://, // on the domain
		$domain = preg_replace( '%^((https?:)?//)?(www.)?%', '', $domain);

		// And remove trailing slashes from the domain
		$domain = rtrim( $domain, '/' );

		return base64_encode( $domain );
	}

	/**
	 * Check whether robust GTM is currently active
	 * @return boolean True if yes, false if no
	 */
	private function isRobustGTMActive() {

		return !empty($this->adminOptions['gtm_robust']);
	}

	/**
	 * Check if GTM IP Anon is active
	 * @return boolean True if yes, false if no
	 */
	private function isGTMIpAnonActive() {

		return !empty($this->adminOptions['gtm_ip_anon']);
	}

	private function isGTMCookieExceptionActive(){

		return !empty($this->adminOptions['gtm_cookie_exception']) && !empty($this->adminOptions['gtm_ce_parameter']);
	}

	/**
	 * Get GTM given the current domain (or the default otherwise)
	 * @param  string $domain The domain to get the UA code for
	 * @return string         The GTM code
	 */
	private function getDomainGTM( $domain = '' ){

		$domainMapping = isset( $this->options['gtm_domain_mapping'] ) ? $this->options['gtm_domain_mapping'] : false;

		if( !$this->isRobustGTMActive() || !$domainMapping || !is_array( $domainMapping ) ){

			return $this->options['gtm_gtm'];
		}

		$domain = $this->getEncodedDomain( $domain );

		if( isset( $domainMapping[ $domain ]) ) {

			return $domainMapping[ $domain ][ 'gtm_robust_gtm' ];
		}

		return $this->options['gtm_gtm'];
	}

	/**
	 * Get the UA code given the current domain (or default otherwise)
	 * @param  string $domain The domain
	 * @return string         The UA code
	 */
	private function getDomainUA( $domain = '' ){

		$domainMapping = isset( $this->options['gtm_domain_mapping'] ) ? $this->options['gtm_domain_mapping'] : false;

		if( !$this->isRobustGTMActive() || !$domainMapping || !is_array( $domainMapping ) ){

			return $this->options['gtm_ua'];
		}

		$domain = $this->getEncodedDomain( $domain );

		if( isset( $domainMapping[ $domain ]) ) {

			return $domainMapping[ $domain ][ 'gtm_robust_ua' ];
		}

		return $this->options['gtm_ua'];
	}
			
	function init() {
		add_filter('members_get_capabilities', array($this, "members_get_capabilities"));
		load_plugin_textdomain( 'ga_gtm', false, dirname( plugin_basename( __FILE__ ) ) . '/languages' );
		
		$this->options = get_option( self::$OPTION_NAME );
		$this->adminOptions = get_option( self::$ADMIN_OPTION_NAME );

		$this->currentDomain = $_SERVER[ 'HTTP_HOST' ];

		if (!is_admin()) {
/*			if ($this->_ga_ID && isset($this->_ga_ID{1})) {
				wp_enqueue_script('ga_gtm', plugins_url('ga_gtm.js', __FILE__), array('jquery'), '1.1', true);		
			}
*/
			if ((!empty($this->adminOptions['ga']) && !empty($this->options['ga_ua']))
					|| (!empty($this->adminOptions['_gaq']) && !empty($this->options['_gaq_ua']))
					|| (!empty($this->adminOptions['gtm']) && !empty($this->getDomainGTM()))
				) {

				add_action('wp_head',array($this, 'wp_head'), 91);
				add_action('wp_footer',array($this, 'wp_footer'), 91);		
			}
		} elseif ($this->checkDependency()) {
			add_action( 'admin_notices', function() {
				// TODO get messages
				echo '<div class="error">'.__('Dependency issue: detected another copy of plugin ', 'ga_gtm').'</div>';
			});
		}
	}
	
	function wp_head() {

		if (!empty($this->adminOptions['gtm']) && !empty($this->getDomainGTM())) {
			$this->wp_head_gtm();
			$optout = true;
		}

		ob_start();
	}
	
	function wp_head_gaq() {
?>
<script type="text/javascript">
	  var _gaq = _gaq || [];
	  _gaq.push(['_setAccount', '<?php echo esc_attr($this->options['_gaq_ua']);?>']);
	  <?php if (!empty($this->adminOptions['_gaq_pageview'])) : ?>
	  _gaq.push(['_trackPageview']);
	  <?php endif; ?>

	  (function() {
		var ga = document.createElement('script'); ga.type = 'text/javascript'; ga.async = true;
		ga.src = ('https:' == document.location.protocol ? 'https://ssl' : 'http://www') + '.google-analytics.com/ga.js';
		var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(ga, s);
	  })();
	</script>
<?php 
	}
	
	function wp_head_ga() { 
?>

	<script>
	  (function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
	  (i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
	  m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
	  })(window,document,'script','//www.google-analytics.com/analytics.js','ga');

		<?php if (!empty($this->adminOptions['ga_pageview'])) : ?>
		  ga('create', '<?php echo esc_attr($this->options['ga_ua']);?>', 'auto');
		  ga('send', 'pageview');
		<?php endif; ?>
	</script>
<?php 	
	}
	
	function wp_footer_ga() {
	}
	

	/**
	 * GTM RELATED FUNCTIONS
	 */

	/**
	 * Queue up any GTM related stuff in the head
	 * @return void this function echoes out
	 */
	function wp_head_gtm() {

		$uaCode = $this->getDomainUA();
		$supportIpAnon = $this->isGTMIpAnonActive();

		?>
		<script>
			if (typeof dataLayer == 'undefined') {
				dataLayer = [];
			}

		<?php if (!empty($uaCode)) : //This script is to set the UA code variable for GTM ?>
			if( typeof dataLayer === 'object' ){
				dataLayer.push({
					'wordpress_ua_code' : '<?php echo esc_attr($uaCode)?>'
				});
			}
		<?php endif; ?>
		</script>
		

		<!-- Google Tag Manager -->
		<?php if( $supportIpAnon ) :
			$this->wp_head_gtm_ipanon();	
		else: ?>
	    	<script type="text/javascript"<?php $this->wp_head_gtm_attributes() ?>>
	    		<?php echo $this->wp_head_javascript(); ?>
	    	</script>
		<?php endif; ?>
		<!-- End Google Tag Manager -->
<?php	
	}

	/**
	 * Extra Head stuff in case IP anon is set up
	 * @return void this function echoes out
	 */
	function wp_head_gtm_ipanon(){

		$gtmCode = $this->getDomainGTM();
		$ajaxURL = self::$PLUGIN_URL . 'ajax/gtm-ajax.php';
?>
		<script type="text/javascript"<?php $this->wp_head_gtm_attributes() ?>>

			var ajaxURL = '<?php echo $ajaxURL; ?>';

			jQuery.ajax({
				url: ajaxURL,
				type: "GET",
				dataType: 'json',
				contentType: "application/json; charset=utf-8",
				success: function( ip ){

					dataLayer.push({
						'ip' : ip
					});
				},
				error: function( xhr, ajaxOptions, thrownError ){

				}
			})
			.always(function(){

				//Instantiate Google Tag Manager
				<?php echo $this->wp_head_javascript(); ?>
				//End Google Tag Manager
			});
		</script>
<?php
	}

	function wp_head_gtm_attributes() {

		$output = '';

		if( $this->isGTMCookieExceptionActive() ){
			$output .= ' ' . $this->adminOptions['gtm_ce_parameter'];
		}

		echo $output;
	}

	/**
	 * Prints out the javascript snippet for GTM, with GTM code inserted
	 * @return void this function echoes out
	 */
	function wp_head_javascript(){

		$gtmCode = $this->getDomainGTM();

?>(function(w,d,s,l,i){w[l]=w[l]||[];w[l].push({'gtm.start':
	    new Date().getTime(),event:'gtm.js'});var f=d.getElementsByTagName(s)[0],
	    j=d.createElement(s),dl=l!='dataLayer'?'&l='+l:'';j.async=true;j.src=
	    '//www.googletagmanager.com/gtm.js?id='+i+dl;f.parentNode.insertBefore(j,f);
	    })(window,document,'script','dataLayer',"<?php echo esc_attr( $gtmCode );?>");<?php
	}

	/**
	 * "Footer", actually outputs right after the opening body tag
	 * @return void This function echoes out
	 */
	function wp_footer_gtm(){

		$gtmCode = $this->getDomainGTM();
?>
		<!-- Google Tag Manager -->
		<noscript><iframe src="//www.googletagmanager.com/ns.html?id=<?php echo esc_attr( $gtmCode );?>" height="0" width="0" style="display:none;visibility:hidden"></iframe></noscript>
		<!-- End Google Tag Manager -->
<?php
	}
	/**
	 * END GTM RELATED FUNCTIONS
	 */
	
	function wp_footer() {
		$content= ob_get_contents();
		ob_clean();
		$contentPos= strpos($content, '<body');
		if ($contentPos !== false) {
			$contentPos = strpos($content, '>', $contentPos) + 1;	
		} else {
			$contentPos = strlen($content);
		}
		
		// find and replace ga();
		// onClick="ga('send', 'event', 'Social', 'Footer', 'Twitter');" 
		
		echo substr($content, 0, $contentPos);
		
		$optout = false;
		
		if  (!empty($this->adminOptions['ga']) && !empty($this->options['ga_ua'])) {
			$this->wp_head_ga();
			$optout = true;
		}
		
		if  (!empty($this->adminOptions['_gaq']) && !empty($this->options['_gaq_ua'])) {
			$this->wp_head_gaq();
			$optout = true;
		}

		if (!empty($this->adminOptions['gtm']) && !empty($this->getDomainGTM())) {
			$this->wp_footer_gtm();
			$optout = true;
		}

	    echo substr($content, $contentPos+1);

	    if  (!empty($this->options['ga_ua'])) {
	    	$this->wp_footer_ga();
	    }
	    
	    if ($optout && !empty($this->adminOptions['optout'])) {
	    	$this->optout_script();
	    }
	    
	}
	
	function optout_script() {
	?>
	<script>
	var analyticsInterval = setInterval(function(){
	    if( typeof ga !== 'undefined' ) {
	        clearInterval(analyticsInterval);
	        if( typeof window.gaOptout === 'function' ) {

	        } else {
	            var trackingIds = ga.getAll();
	            for(var i=0; i<trackingIds.length; i+=1) {
	                var disableStr = 'ga-disable-' + trackingIds[i].get('trackingId');
	                if (document.cookie.indexOf(disableStr + '=true') > -1) {
	                  window[disableStr] = true;
	                }
	            }
	            window.gaOptout = function() {
	                for(var i=0; i<trackingIds.length; i+=1) {
	                    var disableStr = 'ga-disable-' + trackingIds[i].get('trackingId');
	                    document.cookie = disableStr + '=true; expires=Thu, 31 Dec 2099 23:59:59 UTC; path=/';
	                    window[disableStr] = true;
	                }
	            }
	        }
		}
	}, 1000);
	</script> 
	<?php 	
	}
	
	// admin area
	
	private function current_user_can($cap="administrator") {
		if ((!empty($cap) && (in_array($cap, array('gtm-ga-settings','gtm-ga-ua-settings')) || current_user_can($cap))) 
				|| current_user_can("administrator") 
				|| is_super_admin()) {
			return true;
		}
		return false;
	}
		
	public function settings_page() {
		include 'page-settings.php';
	}
	
	public function admin_settings_page() {
		include 'admin-settings.php';
	}
	
	public function add_plugin_page() {
		if ($this->current_user_can()) {
			load_plugin_textdomain( 'ga_gtm', false, dirname( plugin_basename( __FILE__ ) ) . '/languages' );
			
			$userCanAdmin = $this->current_user_can('ga-gtm-settings');
			$userCanSettings = $this->current_user_can('ga-gtm-ua-settings');
			$isAdminSet = (!empty($this->adminOptions['ga']) || !empty($this->adminOptions['_gaq']) || !empty($this->adminOptions['gtm']));

			if ($userCanAdmin || $isAdminSet) {
				add_menu_page(
					__('Analytics Settings', 'ga_gtm'),
					__('Analytics Settings', 'ga_gtm'),
					'manage_options',
					!$isAdminSet ? 'gtm-ga-admin' : 'gtm-ga', // see note under add_submenu_page Admin
					!$isAdminSet ? array($this, 'admin_settings_page') : array( $this, 'settings_page' )
				);
			}
			
			if ($isAdminSet && $userCanSettings) {			
				add_submenu_page(
					'gtm-ga',
					__('Settings', 'ga-gtm'),
					__('Settings', 'ga-gtm'),
					'manage_options', //'ga-gtm-settings',
					'gtm-ga',
					array($this, 'settings_page')
				);
			}
			
			if ($userCanAdmin) {
				add_submenu_page(
					!$isAdminSet ? 'gtm-ga-admin' : 'gtm-ga', // set Analytics Settings to this Admin page if Analytics are not set - no settings page
					__('Admin', 'ga-gtm'),
					__('Admin', 'ga-gtm'),
					'manage_options', //'ga-gtm-settings',
					'gtm-ga-admin',
					array($this, 'admin_settings_page')
				);
			}

		}
	}
	/**
	     * Register and add settings
	     */
	public function page_init() {	
		register_setting(
			self::$OPTION_GROUP, // Option group
			self::$OPTION_NAME, // Option name
			array( $this, 'sanitize' ) // Sanitize
		);
	
		add_settings_section(
			'setting_section_id', // ID
			'',  // Title
			array( $this, 'print_section_info' ), // Callback
			self::$OPTION_GROUP // Page
		);
		
		foreach (array('ga_ua', 'gaq_ua', 'gtm_ua', 'gtm_gtm') as $inputName) {
			add_settings_field(
			$inputName, // ID
			$inputName, //'_gaq', // Title
			'_return_null',// Callback
			self::$OPTION_GROUP, // Page
			'setting_section_id' // Section
			);
		}
		
	
		// admin settings
		register_setting(
			self::$ADMIN_OPTION_GROUP, // Option group
			self::$ADMIN_OPTION_NAME, // Option name
			array( $this, 'sanitize_admin' ) // Sanitize
		);
	
		add_settings_section(
			'admin_setting_section_id', // ID
			'',  // Title
			array( $this, 'print_section_info' ), // Callback
			self::$ADMIN_OPTION_GROUP // Page
		);
		
		foreach (array('ga', 'ga_pageview', '_gaq', '_gaq_pageview', 'gtm', 'optout') as $inputName) {
			add_settings_field(
				$inputName, // ID
				$inputName, //'_gaq', // Title
				'_return_null',// Callback
				self::$ADMIN_OPTION_GROUP, // Page
				'admin_setting_section_id' // Section
			);
		}
		
	}
	
	function print_section_info() {
		
	}
	/**
	 * Sanitize each setting field as needed
	 *
	 * @param array $input Contains all settings fields as array keys
	 */
	public function sanitize_admin($input) {
		$new_input = $input;
		// checkboxes
		if (!empty($input['optout']) && empty($input['ga']) && empty($input['_gaq']) && empty($input['gtm'])) {
			// removing Opt-out if gtm and ga are not selected 
			unset($new_input['optout']);
		}
		return $new_input;
	}
	
	public function sanitize( $input ) {
		$new_input = array();
		$fields = [ 'ga_ua', '_gaq_ua', 'gtm_ua', 'gtm_gtm', 'gtm_domain' ];
		
		// ROBUST ANALYTICS - fields for the extra domain mapping
		$gtmDomainMappingFieldName = 'gtm_domain_mapping';
		$gtmDomainMappingFields = [ 'gtm_robust_ua', 'gtm_robust_gtm' ];
		$gtmDomainMappingKey = 'gtm_robust_domain';

		foreach ( $fields as $inputName) {
			
			if( isset( $input[$inputName] ) ) {

					$new_input[$inputName] = implode("}\n", array_map('sanitize_text_field', explode('}',$input[$inputName])));

			}
		}

		// If the robust key is set up, and has anything
		if( isset( $input[ $gtmDomainMappingKey ] ) && is_array( $input[ $gtmDomainMappingKey ] ) ){

			// For each domain mapped
			foreach( $input[ $gtmDomainMappingKey ] as $index => $theKey ){

				if( $theKey == '' ){
					continue;
				}

				// Lowercase the key
				$theKey = strtolower($theKey);

				// Get an encoded version of the domain
				$robustIndex = $this->getEncodedDomain( $theKey );

				// Now get the UA code and GTM code for each (And any other fields set above)
				foreach( $gtmDomainMappingFields as $innerField ){

					// If that field has content for this index...
					if( isset( $input[ $innerField ][ $index ] ) ){

						// Set it in our output
						$new_input[ $gtmDomainMappingFieldName ][ $robustIndex ][ $innerField ] = $input[ $innerField ][ $index ];
					}
				}
			}
		}

		return $new_input;
	}
		
}

$gtmGA = GoogleGtmGa::getInstance();

/*
 On the super-admin page:
Check boxes of which styles of code to include (gaq, GA, GTM)
Check box to enable the optout script

Then on the settings page:
If gaq, a text field for the gaq code
If GA, a field for the UA code
If GTM, a field for the UA code AND the GTM Code


For the GTM one, a little tooltip or reminder next to the UA code saying how to access the UA code from GTM would be a good thing too


just to clarify that it has to be implemented on that side, i can provide that
 */
