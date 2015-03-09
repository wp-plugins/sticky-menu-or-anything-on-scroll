<?php
/*
Plugin Name: Sticky Menu (or Anything!) on Scroll
Plugin URI: http://www.senff.com/plugins/sticky-anything-wp
Description: Pick any element on your page, and it will stick when it reaches the top of the page when you scroll down. Usually handy for navigation menus, but can be used for any (unique) element on your page.
Author: Mark Senff
Author URI: http://www.senff.com
Version: 1.2.2
*/

defined('ABSPATH') or die('INSERT COIN');


/**
 * === FUNCTIONS ========================================================================================
 */

/**
 * --- IF DATABASE VALUES ARE NOT SET AT ALL, ADD DEFAULT OPTIONS TO DATABASE ---------------------------
 */
if (!function_exists('sticky_anthing_default_options')) {
	function sticky_anthing_default_options() {
		$versionNum = '1.2.2';
		if (get_option('sticky_anything_options') === false) {
			$new_options['sa_version'] = $versionNum;
			$new_options['sa_element'] = '';
			$new_options['sa_topspace'] = '';
			$new_options['sa_minscreenwidth'] = '';			
			$new_options['sa_maxscreenwidth'] = '';			
			$new_options['sa_zindex'] = '';
			$new_options['sa_dynamicmode'] = false;		
			$new_options['sa_debugmode'] = false;
			add_option('sticky_anything_options',$new_options);
		} 
	}
}

/**
 * --- IF DATABASE VALUES EXIST, CHECK IF NEWER OPTIONS EXIST ------------------------------------------
 * --- IF NOT, ADD THESE OPTIONS WITH DEFAULT VALUES ---------------------------------------------------
 */
if (!function_exists('sticky_anything_update')) {
	function sticky_anything_update() {
		$versionNum = '1.2.2';
		$existing_options = get_option('sticky_anything_options');

		if(!isset($existing_options['sa_minscreenwidth'])) {
			// Introduced in version 1.1
			$existing_options['sa_minscreenwidth'] = '';
			$existing_options['sa_maxscreenwidth'] = '';
		} 

		if(!isset($existing_options['sa_dynamicmode'])) {
			// Introduced in version 1.2
			$existing_options['sa_dynamicmode'] = false;
		} 

		$existing_options['sa_version'] = $versionNum;
		update_option('sticky_anything_options',$existing_options);
	}
}


/**
 * --- LOAD MAIN .JS FILE AND CALL IT WITH PARAMETERS (BASED ON DATABASE VALUES) -----------------------
 */
if (!function_exists('load_sticky_anything')) {
    function load_sticky_anything() {

		// Main jQuery plugin file 
	    wp_register_script('stickyAnythingLib', plugins_url('/assets/js/jq-sticky-anything.min.js', __FILE__), array( 'jquery' ), $versionNum);
	    wp_enqueue_script('stickyAnythingLib');

		$options = get_option('sticky_anything_options');

		// Set defaults for by-default-empty elements (because '' does not work with the JQ plugin) 
		if (!$options['sa_topspace']) {
			$options['sa_topspace'] = '0';
		}

		if (!$options['sa_minscreenwidth']) {
			$options['sa_minscreenwidth'] = '0';
		}

		if (!$options['sa_maxscreenwidth']) {
			$options['sa_maxscreenwidth'] = '999999';
		}

		// If empty, set to 1 - not to 0. Also, if set to "0", keep it at 0.
		if (strlen($options['sa_zindex']) == "0") {
			$options['sa_zindex'] = '1';
		}

		$script_vars = array(
		      'element' => $options['sa_element'],
		      'topspace' => $options['sa_topspace'],
		      'minscreenwidth' => $options['sa_minscreenwidth'],
		      'maxscreenwidth' => $options['sa_maxscreenwidth'],
		      'zindex' => $options['sa_zindex'],
		      'dynamicmode' => $options['sa_dynamicmode'],
		      'debugmode' => $options['sa_debugmode']
		);

		wp_enqueue_script('stickThis', plugins_url('/assets/js/stickThis.js', __FILE__), array( 'jquery' ), '1.2.2', true);
		wp_localize_script( 'stickThis', 'sticky_anything_engage', $script_vars );

    }
}


/**
 * --- ADD LINK TO SETTINGS PAGE TO SIDEBAR ------------------------------------------------------------
 */
if (!function_exists('sticky_anything_menu')) {
    function sticky_anything_menu() {
		add_options_page( 'Sticky Menu (or Anything!) Configuration', 'Sticky Menu (or Anything!)', 'manage_options', 'stickyanythingmenu', 'sticky_anything_config_page' );
    }
}


/**
 * --- ADD LINK TO SETTINGS PAGE TO PLUGIN ------------------------------------------------------------
 */
if (!function_exists('sticky_anything_settings_link')) {
function sticky_anything_settings_link($links) { 
  $settings_link = '<a href="options-general.php?page=stickyanythingmenu">Settings</a>'; 
  array_unshift($links, $settings_link); 
  return $links; 
}
}


/**
 * --- THE WHOLE ADMIN SETTINGS PAGE -------------------------------------------------------------------
 */
if (!function_exists('sticky_anything_config_page')) {
	function sticky_anything_config_page() {
	// Retrieve plugin configuration options from database
	$sticky_anything_options = get_option( 'sticky_anything_options' );
	?>

	<div id="sticky-anything-settings-general" class="wrap">
		<h2><?php _e('Sticky Menu (or Anything!) Settings','Sticky Anything plugin'); ?></h2>

		<p><?php _e('Pick any element on your page, and it will stick when it reaches the top of the page when you scroll down. Usually handy for navigation menus, but can be used for any (unique) element on your page.','Sticky Anything plugin'); ?></p>

		<div class="main-content">

			<h2 class="nav-tab-wrapper">	
				<a class="nav-tab" href="#main"><?php _e('Settings','Sticky Anything plugin'); ?></a>
				<a class="nav-tab" href="#faq"><?php _e('FAQ/Troubleshooting','Sticky Anything plugin'); ?></a>
			</h2>

			<br>

			<?php 

				$warnings = false;

				if ( isset( $_GET['message'] )) { 
					if ($_GET['message'] == '1') {
						echo '<div id="message" class="fade updated"><p><strong>'.__('Settings Updated.','Sticky Anything plugin').'</strong></p></div>';
					}
				} 
				
				if ( isset( $_GET['message'] )) { 
					if ($sticky_anything_options['sa_element'] == '') {
						$warnings = true;  
					}
				}

				if ( (!is_numeric($sticky_anything_options['sa_topspace'])) && ($sticky_anything_options['sa_topspace'] != '')) {
					// Top space is not empty and has bad value
					$warnings = true;
				}

				if ( (!is_numeric($sticky_anything_options['sa_minscreenwidth'])) && ($sticky_anything_options['sa_minscreenwidth'] != '')) {
					// Minimum width is not empty and has bad value
					$warnings = true;
				}

				if ( (!is_numeric($sticky_anything_options['sa_maxscreenwidth'])) && ($sticky_anything_options['sa_maxscreenwidth'] != '')) {
					// Maximum width is not empty and has bad value
					$warnings = true;
				}

				if ( ($sticky_anything_options['sa_minscreenwidth'] != '') && ($sticky_anything_options['sa_maxscreenwidth'] != '') && ( ($sticky_anything_options['sa_minscreenwidth']) >= ($sticky_anything_options['sa_maxscreenwidth']) ) ) {
					// Minimum width is larger than the maximum width
					$warnings = true;
				}

				if ((!is_numeric($sticky_anything_options['sa_zindex'])) && ($sticky_anything_options['sa_zindex'] != '')) {
					// Z-index is not empty and has bad value
					$warnings = true;
				}

				// IF THERE ARE ERRORS, SHOW THEM
				if ( $warnings == true ) { 
					echo '<div id="message" class="error"><p><strong>'.__('Please review the current settings:','Sticky Anything plugin').'</strong></p>';
					echo '<ul style="list-style-type:disc; margin:0 0 20px 24px;">';

					if ($sticky_anything_options['sa_element'] == '') {
						echo '<li>'.__('ELEMENT is a required field. If you do not want anything sticky, consider disabling the plugin.','Sticky Anything plugin').'</li>';
					} 

					if ( (!is_numeric($sticky_anything_options['sa_topspace'])) && ($sticky_anything_options['sa_topspace'] != '')) {
						echo '<li>'.__('TOP POSITION has to be a number (do not include "px" or "pixels", or any other characters).','Sticky Anything plugin').'</li>';
					}

					if ( (!is_numeric($sticky_anything_options['sa_minscreenwidth'])) && ($sticky_anything_options['sa_minscreenwidth'] != '')) {
						echo '<li>'.__('MINIMUM SCREEN WIDTH has to be a number (do not include "px" or "pixels", or any other characters).','Sticky Anything plugin').'</li>';
					}

					if ( (!is_numeric($sticky_anything_options['sa_maxscreenwidth'])) && ($sticky_anything_options['sa_maxscreenwidth'] != '')) {
						echo '<li>'.__('MAXIMUM SCREEN WIDTH has to be a number (do not include "px" or "pixels", or any other characters).','Sticky Anything plugin').'</li>';
					}

					if ( ($sticky_anything_options['sa_minscreenwidth'] != '') && ($sticky_anything_options['sa_maxscreenwidth'] != '') && ( ($sticky_anything_options['sa_minscreenwidth']) >= ($sticky_anything_options['sa_maxscreenwidth']) ) ) {
						echo '<li>'.__('MAXIMUM screen width has to have a larger value than the MINIMUM screen width.','Sticky Anything plugin').'</li>';
					}

					if ((!is_numeric($sticky_anything_options['sa_zindex'])) && ($sticky_anything_options['sa_zindex'] != '')) {
						echo '<li>'.__('Z-INDEX has to be a number (do not include any other characters).','Sticky Anything plugin').'</li>';
					}

					echo '</ul></div>';
				} 			

			?>
		
			<div class="tabs-content">

				<div id="sticky-main">

					<form method="post" action="admin-post.php">

						<input type="hidden" name="action" value="save_sticky_anything_options" />
						<!-- Adding security through hidden referrer field -->
						<?php wp_nonce_field( 'sticky_anything' ); ?>

						<table class="form-table">

							<tr>
								<th scope="row"><?php _e('Sticky Element:','Sticky Anything plugin'); ?> <span class="required">*</span> <a href="#" title="<?php _e('The element that needs to be sticky once you scroll. This can be your menu, or any other element like a sidebar, ad banner, etc. Make sure this is a unique identifier.','Sticky Anything plugin'); ?>" class="help">?</a></th>
								<td>
									<input type="text" id="sa_element" name="sa_element" value="<?php 
										if ($sticky_anything_options['sa_element'] != '#NO-ELEMENT') {
											echo esc_html( $sticky_anything_options['sa_element'] ); 
										}
									?>"/> <?php _e('(e.g. #main-navigation, .main-menu-1, header nav, etc.)','Sticky Anything plugin'); ?>
								</td>
							</tr>


							<tr>
								<th scope="row"><?php _e('Space between top of page and sticky element: (optional)','Sticky Anything plugin'); ?> <a href="#" title="<?php _e('If you don\'t want the element to be sticky at the very top of the page, but a little lower, add the number of pixels that should be between your element and the \'ceiling\' of the page.','Sticky Anything plugin'); ?>" class="help">?</a></th>
								<td>
									<input type="number" id="sa_topspace" name="sa_topspace" value="<?php echo esc_html( $sticky_anything_options['sa_topspace'] ); ?>" style="width:80px;" /> pixels
								</td>
							</tr>

							<tr>
								<th scope="row"><?php _e('Do not stick element when screen smaller than: (optional)','Sticky Anything plugin'); ?> <a href="#" title="<?php _e('Sometimes you do not want your element to be sticky when your screen is small (responsive menus, etc). If you enter a value here, your menu will not be sticky when your screen width is smaller than his value.','Sticky Anything plugin'); ?>" class="help">?</a></th>
								<td>
									<input type="number" id="sa_minscreenwidth" name="sa_minscreenwidth" value="<?php echo esc_html( $sticky_anything_options['sa_minscreenwidth'] ); ?>" style="width:80px;" /> pixels
								</td>
							</tr>

							<tr>
								<th scope="row"><?php _e('Do not stick element when screen larger than: (optional)','Sticky Anything plugin'); ?> <a href="#" title="<?php _e('Sometimes you do not want your element to be sticky when your screen is large (responsive menus, etc). If you enter a value here, your menu will not be sticky when your screen width is wider than this value.','Sticky Anything plugin'); ?>" class="help">?</a></th>
								<td>
									<input type="number" id="sa_maxscreenwidth" name="sa_maxscreenwidth" value="<?php echo esc_html( $sticky_anything_options['sa_maxscreenwidth'] ); ?>" style="width:80px;" /> pixels
								</td>
							</tr>

							<tr>
								<th scope="row"><?php _e('Z-index: (optional)','Sticky Anything plugin'); ?> <a href="#" title="<?php _e('If there are other elements on the page that obscure/overlap the sticky element, adding a Z-index might help. If you have no idea what that means, try entering 99999.','Sticky Anything plugin'); ?>" class="help">?</a></th>
								<td>
									<input type="number" id="sa_zindex" name="sa_zindex" value="<?php echo esc_html( $sticky_anything_options['sa_zindex'] ); ?>" style="width:80px;" />
								</td>
							</tr>

							<tr>
								<th scope="row"><?php _e('Dynamic mode:','Sticky Anything plugin'); ?> <a href="#" title="<?php _e('When Dynamic Mode is OFF, a cloned element will be created upon page load. If this mode is ON, a cloned element will be created every time your scrolled position hits the \'sticky\' point.','Sticky Anything plugin'); ?>" class="help">?</a></th>
								<td>
									<input type="checkbox" id="sa_dynamicmode" name="sa_dynamicmode" <?php if ($sticky_anything_options['sa_dynamicmode']  ) echo ' checked="checked" ';?> />
									<label for="sa_dynamicmode"><strong><?php _e('If the plugin doesn\'t work in your theme (often the case with responsive themes), try it in Dynamic Mode.','Sticky Anything plugin'); ?></strong></label>
									<p class="description"><?php _e('NOTE: this is not a \'Magic Checkbox\' that fixes all problems. It simply solves some issues that frequently appear with some responsive themes, but doesn\'t necessarily work in ALL situations.','Sticky Anything plugin'); ?></p>
								</td>
							</tr>

							<tr>
								<th scope="row"><?php _e('Debug mode:','Sticky Anything plugin'); ?> <a href="#" title="<?php _e('When Debug Mode is on, error messages will be shown in your browser\'s console when the element you selected either doesn\'t exist, or when there are more elements on the page with your chosen selector.','Sticky Anything plugin'); ?>" class="help">?</a></th>
								<td>
									<input type="checkbox" id="sa_debugmode" name="sa_debugmode" <?php if ($sticky_anything_options['sa_debugmode']  ) echo ' checked="checked" ';?> />
									<label for="sa_debugmode"><strong><?php _e('Log plugin errors in browser console','Sticky Anything plugin'); ?></strong></label>
									<p class="description"><?php _e('Do NOT check this option in production environments.','Sticky Anything plugin'); ?></p>
								</td>
							</tr>

						</table>

						<input type="submit" value="<?php _e('SAVE SETTINGS','Sticky Anything plugin'); ?>" class="button-primary"/>

						<p>&nbsp;</p>
					</form>


				</div>

				<div id="sticky-faq">
					<h2><?php _e('FAQ','Sticky Anything plugin'); ?>/<?php _e('Troubleshooting','Sticky Anything plugin'); ?></h2>

					<p><strong><?php _e('Q: I selected a class/ID in the settings screen, but the element doesn\'t stick when I scroll down. Why not?','Sticky Anything plugin'); ?></strong>
					<?php _e('Make sure that if you select the element by its classname, it is preceded by a dot (e.g. ".main-menu"), and if you select it by its ID, that it\'s preceded by a pound/hash/number sign (e.g. "#main-menu").  Also, make sure there is only ONE element on the page with the selector you\'re using. If there is none, or more than one element that matches your selector, nothing will happen.','Sticky Anything plugin'); ?></p>

					<p><strong><?php _e('Q: I\'m having some issues on mobile (or other responsive themes).','Sticky Anything plugin'); ?></strong>
					<?php _e('A number of people reported problems using a sticky element in a responsive theme - mostly situations involving a different menu (in both design and functionality) between desktop, tablet and mobile views. The newly-introduced \'Dynamic Mode\' solves some of these problems. Try it yourself and chances are that works for you as well (though it\'s not a setting that will magically solves any and all problems that may occur).','Sticky Anything plugin'); ?></p>

					<p><strong><?php _e('Q: My menu sticks, but it doesn\'t open on the <a href="https://wordpress.org/themes/responsive" target="_blank">Responsive</a> theme when it\'s sticky.','Sticky Anything plugin'); ?></strong>
					<?php _e('This is a known bug. I\'m looking into it.','Sticky Anything plugin'); ?></p>

					<p><strong><?php _e('Q: Still doesn\'t work. What could be wrong?','Sticky Anything plugin'); ?></strong>
					<?php _e('Check the "Debug Mode" checkbox in the plugin\'s settings. Reload the page and you may see errors in your browser\'s console window. If you\'ve used a selector that returns zero elements on the page, OR more than one, it will be shown.','Sticky Anything plugin'); ?></p>

					<p><strong><?php _e('Q: Is it possible to have multiple sticky elements?','Sticky Anything plugin'); ?></strong>
					<?php _e('The current version only allows one sticky element. Having more than one may clutter up your page and confuse the user (imagine having a menu stuck at the top, and a banner stuck on the left, and another thing on the right, etc.). Having said that, this functionality may be added to a future version.','Sticky Anything plugin'); ?></p>

					<p><strong><?php _e('Q: What is this Dynamic Mode thing exactly?','Sticky Anything plugin'); ?></strong>
					<?php _e('To properly explain this, we\'ll need to go a little deeper in the plugin\'s functionality. So bear with me...','Sticky Anything plugin'); ?></p>
					<p><?php _e('When an element becomes sticky at the top of the page (and keeps its place regardless of the scrolling), it\'s actually not the element itself you see, but a cloned copy of it (the original element is out of view and invisible).','Sticky Anything plugin'); ?></p>
					<p><?php _e('The original element always stays where it originally is on the page, while the cloned element is always at the top of the browser viewport screen. However, you will never see them both at the same time; depending on your scroll position, it always just shows either one or the other.','Sticky Anything plugin'); ?></p>
					<p><?php _e('In the original plugin version, the clone would be created right when you load the page. Then when you would scroll down, it would become visible (and stick at the top) while the original element would disappear.','Sticky Anything plugin'); ?></p>
					<p><?php _e('However, some themes use some JavaScript to dynamically create elements (menus, mostly) for mobile sites. With this method, a menu doesn\'t exist in the HTML source code when you load the page, but is created on the fly some time after the page is fully loaded -- in many cases, these menus would just be generated ONLY when the screen is more (or less) than a certain specific width. With the original version of the plugin, the problem would be that the original element may not have been fully created upon page load, so the clone would also not be fully functional.','Sticky Anything plugin'); ?></p>
					<p><?php _e('In Dynamic Mode, a clone of the element is not created on page load -- instead, it\'s created when the user scrolls and hits the "sticky" point. This ensures that the cloned element is an actual 1-on-1 copy of what the original element consists of at that specific point (and not at the "page is loaded" point, which may be different if the element was altered since).','Sticky Anything plugin'); ?></p>
					<p><?php _e('Why don\'t we use Dynamic Mode all the time then? This has to do with the fact that other plugins initialize themselves on page load and may need the full markup (including the cloned element) at that point. In Dynamic Mode, there is no clone available yet on page load, so that could cause an issue.','Sticky Anything plugin'); ?></p>
					<p><?php _e('Phew!','Sticky Anything plugin'); ?></p>

					<p><strong><?php _e('Q: I\'ll need more help please!','Sticky Anything plugin'); ?></strong>
					<?php _e('The plugin\'s own page can be found at <a href="http://www.senff.com/plugins/sticky-anything-wp" target="_blank">http://www.senff.com/plugins/sticky-anything-wp</a>. If that still doesn\'t help you solve your issue, please do NOT file a bug through the form on my website, but instead go to the plugin\'s support forum on <a href="https://wordpress.org/support/plugin/sticky-menu-or-anything-on-scroll" target="_blank">WordPress.org</a>.','Sticky Anything plugin'); ?></p>

				</div>

			</div>

		</div>

		<div class="main-sidebar">	
			<?php include 'assets/plugin-info.php'; ?>
		</div>

	</div>

	<?php
	}
}


if (!function_exists('sticky_anything_admin_init')) {
	function sticky_anything_admin_init() {
		add_action( 'admin_post_save_sticky_anything_options', 'process_sticky_anything_options' );
	}
}

/**
 * --- PROCESS THE SETTINGS FORM AFTER SUBMITTING ------------------------------------------------------
 */
if (!function_exists('process_sticky_anything_options')) {
	function process_sticky_anything_options() {

		if ( !current_user_can( 'manage_options' ))
			wp_die( 'Not allowed');

		check_admin_referer('sticky_anything');
		$options = get_option('sticky_anything_options');

		foreach ( array('sa_element') as $option_name ) {
			if ( isset( $_POST[$option_name] ) ) {
				$options[$option_name] = sanitize_text_field( $_POST[$option_name] );
			} 
		}

		foreach ( array('sa_topspace') as $option_name ) {
			if ( isset( $_POST[$option_name] ) ) {
				$options[$option_name] = sanitize_text_field( $_POST[$option_name] );
			}
		}

		foreach ( array('sa_minscreenwidth') as $option_name ) {
			if ( isset( $_POST[$option_name] ) ) {
				$options[$option_name] = sanitize_text_field( $_POST[$option_name] );
			}
		}

		foreach ( array('sa_maxscreenwidth') as $option_name ) {
			if ( isset( $_POST[$option_name] ) ) {
				$options[$option_name] = sanitize_text_field( $_POST[$option_name] );
			}
		}

		foreach ( array('sa_zindex') as $option_name ) {
			if ( isset( $_POST[$option_name] ) ) {
				$options[$option_name] = sanitize_text_field( $_POST[$option_name] );
			}
		}

		foreach ( array('sa_dynamicmode') as $option_name ) {
			if ( isset( $_POST[$option_name] ) ) {
				$options[$option_name] = true;
			} else {
				$options[$option_name] = false;
			}
		}

		foreach ( array('sa_debugmode') as $option_name ) {
			if ( isset( $_POST[$option_name] ) ) {
				$options[$option_name] = true;
			} else {
				$options[$option_name] = false;
			}
		}

		update_option( 'sticky_anything_options', $options );	
 		wp_redirect( add_query_arg(
 			array('page' => 'stickyanythingmenu', 'message' => '1'),
 			admin_url( 'options-general.php' ) 
 			)
 		);	

		exit;
	}
}


/**
 * --- ADD THE .CSS AND .JS TO ADMIN MENU --------------------------------------------------------------
 */
if (!function_exists('sticky_anything_styles')) {
	function sticky_anything_styles($hook) {
		if ($hook != 'settings_page_stickyanythingmenu') {
			return;
		}

		wp_register_script('stickyAnythingAdminScript', plugins_url('/assets/js/sticky-anything-admin.js', __FILE__), array( 'jquery' ), '1.0');
		wp_enqueue_script('stickyAnythingAdminScript');

		wp_register_style('stickyAnythingAdminStyle', plugins_url('/assets/css/sticky-anything-admin.css', __FILE__) );
	    wp_enqueue_style('stickyAnythingAdminStyle');		
	}
}


/**
 * === HOOKS AND ACTIONS AND FILTERS AND SUCH ==========================================================
 */

$plugin = plugin_basename(__FILE__); 

register_activation_hook( __FILE__, 'sticky_anthing_default_options' );
add_action('init','sticky_anything_update',1);
add_action('wp_enqueue_scripts', 'load_sticky_anything');
add_action('admin_menu', 'sticky_anything_menu');
add_action('admin_init', 'sticky_anything_admin_init' );
add_action('admin_enqueue_scripts', 'sticky_anything_styles' );
add_filter("plugin_action_links_$plugin", 'sticky_anything_settings_link' );