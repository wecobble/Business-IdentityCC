<?php
/**
 * This plugin is intended to be used as a custom colors add-on to the Business Identity WordPress Theme.
 * It will be of no use if you have not purchased the self-hosted version of Business Identity.
 *
 * @wordpress-plugin
 * Plugin Name: Business Identity Custom Colors
 *  Plugin URI: https://creativemarket.com/professionalthemes/39756-Business-Identity-WordPress-Theme
 * Description: This plugin adds custom colors functionality into the Business Identity WordPress Theme.
 *      Author: Professional Themes
 *  Author URI: https://creativemarket.com/professionalthemes
 *     Version: 3.0.0
 * Text Domain: business-identity-custom-colors
 * Domain Path: /languages/
 *     License: GNU General Public License v2 or later
 * License URI: http://www.gnu.org/licenses/gpl-2.0.html
 *
 * @see     function add_action
 * @see     function add_control
 * @see     function add_setting
 * @see     function get_control
 * @see     function get_theme_mod
 * @see     function maybe_hash_hex_color
 * @see     function sanitize_hex_color_no_hash
 * @see     function sanitize_text_field
 * @see     function wp_get_theme
 * @since   2.0.0
 * @package Business_Identity_Custom_Colors
 */

// If this file is called directly, abort.
if ( ! defined( 'ABSPATH' ) ) {
	die;
}

if ( ! function_exists( 'business_identity_custom_colors_hex_to_rgb' ) ) :
	/**
	 * Simple hex to rgb conversion for better custom color handling
	 *
	 * @link http://php.net/manual/en/function.hexdec.php
	 */
	function business_identity_custom_colors_hex_to_rgb( $hex = false ) {
		if ( false == $hex ) {
			return;
		}
		$color        = (int) hexdec( $hex );
		$rgb          = array();
		$rgb['red']   = (int) 0xFF & ( $color >> 0x10 );
		$rgb['green'] = (int) 0xFF & ( $color >> 0x8 );
		$rgb['blue']  = (int) 0xFF & $color;

		return $rgb;
	} // end function business_identity_custom_colors_hex_to_rgb
endif;

if ( ! function_exists( 'business_identity_custom_colors_customize_register' ) ) :
	// Customizer Registration
	function business_identity_custom_colors_customize_register( $wp_customize ) {
		// Point users to Jetpack Custom CSS if they'd like more control over their colors
		$wp_customize->get_section( 'colors' )->description = __( 'If you would like even more fine-grained control over your colors, take advantage of the Jetpack <a href="http://jetpack.me/support/custom-css/">Custom CSS</a> module.', 'business-identity-custom-colors' );
		// Ensure that core controls are shown above Business Identity Custom Colors controls
		$wp_customize->get_control( 'background_color' )->priority = 1;
		$wp_customize->get_control( 'header_textcolor' )->priority = 2;

		/**
		 * Main Accent Color
		 */
		$wp_customize->add_setting(
			'business_identity_primary_color',
			array(
				'default'				=> '352f48',
				'sanitize_callback'		=> 'sanitize_hex_color_no_hash',
				'sanitize_js_callback'	=> 'maybe_hash_hex_color',
			)
		);
		$wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize,
			'business_identity_primary_color',
			array(
				'label'		=> __( 'Main Accent Background', 'business-identity-custom-colors' ),
				'section'	=> 'colors',
				'priority'	=> 100,
			)
		) );
		$wp_customize->add_setting(
			'business_identity_main_accent_txt_color',
			array(
				'default'				=> 'ff0000',
				'sanitize_callback'		=> 'sanitize_hex_color_no_hash',
				'sanitize_js_callback'	=> 'maybe_hash_hex_color',
			)
		);
		$wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize,
			'business_identity_main_accent_txt_color',
			array(
				'label'		=> __( 'Main Accent Text', 'business-identity-custom-colors' ),
				'section'	=> 'colors',
				'priority'	=> 100,
			)
		) );

		/**
		 * Secondary Accent Color
		 */
		$wp_customize->add_setting(
			'business_identity_secondary_color',
			array(
				'default'				=> '5c5379',
				'sanitize_callback'		=> 'sanitize_hex_color_no_hash',
				'sanitize_js_callback'	=> 'maybe_hash_hex_color',
			)
		);
		$wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize,
			'business_identity_secondary_color',
			array(
				'label'		=> __( 'Secondary Accent Background', 'business-identity-custom-colors' ),
				'section'	=> 'colors',
				'priority'	=> 101,
			)
		) );
		$wp_customize->add_setting(
			'business_identity_secondary_accent_txt_color',
			array(
				'default'				=> 'ff0000',
				'sanitize_callback'		=> 'sanitize_hex_color_no_hash',
				'sanitize_js_callback'	=> 'maybe_hash_hex_color',
			)
		);
		$wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize,
			'business_identity_secondary_accent_txt_color',
			array(
				'label'		=> __( 'Secondary Accent Text', 'business-identity-custom-colors' ),
				'section'	=> 'colors',
				'priority'	=> 101,
			)
		) );

		/**
		 * Links
		 */
		$wp_customize->add_setting(
			'business_identity_links_color',
			array(
				'default'				=> '7b65c7',
				'sanitize_callback'		=> 'sanitize_hex_color_no_hash',
				'sanitize_js_callback'	=> 'maybe_hash_hex_color',
			)
		);
		$wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize,
			'business_identity_links_color',
			array(
				'label'		=> __( 'Links', 'business-identity-custom-colors' ),
				'section'	=> 'colors',
				'priority'	=> 102,
			)
		) );
	} // end function business_identity_custom_colors_customize_register
endif;

if ( ! function_exists( 'business_identity_customized_colors' ) ) :
	// Output custom colors
	function business_identity_customized_colors() {
		// Retrieve custom colors settings and also provide their rgb equivalents
		$primary       = get_theme_mod( 'business_identity_primary_color' );
		$primary_txt   = get_theme_mod( 'business_identity_main_accent_txt_color' );
		$secondary     = get_theme_mod( 'business_identity_secondary_color' );
		$secondary_txt = get_theme_mod( 'business_identity_secondary_accent_txt_color' );
		$links         = get_theme_mod( 'business_identity_links_color' );

		/**
		 * Main Accent
		 */
		if ( ! empty( $primary ) && '352f48' != $primary ) :
			$primary_hex = business_identity_custom_colors_hex_to_rgb( $primary ); ?>
			<style type="text/css">
				#masthead,
				#tertiary {
					background-color: #<?php echo sanitize_text_field( get_theme_mod( 'business_identity_primary_color' ) ); ?>;
				}
				.main-navigation li > a:active,
				.main-navigation .current_page_item > a,
				.main-navigation .current-menu-item > a,
				.main-navigation .current_page_ancestor > a {
					color: rgba( 255, 255, 255, .75 );
				}
				#tertiary .widget-title {
					color: rgba( 255, 255, 255, .85 );
				}
				.site-footer {
					background-color: rgba( <?php echo sanitize_text_field( $primary_hex['red'] ); ?>, <?php echo sanitize_text_field( $primary_hex['green'] ); ?>, <?php echo sanitize_text_field( $primary_hex['blue'] ); ?>, .85 );
					color: rgba( 255, 255, 255, .85 );
				}
				@media only screen
				and (min-width : 800px) {
					.main-navigation li > a:hover,
					.main-navigation li > a:active,
					.main-navigation li > a:focus {
						border-bottom-color: rgba( 255, 255, 255, .4 );
					}
					.main-navigation .page_item_has_children > a:first-child:after,
					.main-navigation .menu-item-has-children > a:first-child:after {
						color: rgba( 255, 255, 255, .4 );
					}
				}
			</style><?php
		endif;
		if ( ! empty( $primary_txt ) && 'ff0000' != $primary_txt ) :
			$primary_hex = business_identity_custom_colors_hex_to_rgb( $primary_txt ); ?>
			<style type="text/css">
			</style><?php
		endif;

		/**
		 * Secondary Accent
		 */
		if ( ! empty( $secondary ) && '5c5379' != $secondary ) : ?>
			<style type="text/css">
				#hero,
				.special-offer,
				.infinite-scroll #infinite-handle span {
					background-color: #<?php echo sanitize_text_field( get_theme_mod( 'business_identity_secondary_color' ) ); ?>;
				}
				.main-navigation ul li.page_item_has_children:hover:after,
				.main-navigation ul li.menu-item-has-children:hover:after,
				.infinite-scroll .infinite-load {
					color: #<?php echo sanitize_text_field( get_theme_mod( 'business_identity_secondary_color' ) ); ?>;
				}
				.special-offer .label {
					color: rgba( 255, 255, 255, .85 );
				}
				@media only screen
				and (min-width : 800px) {
					.main-navigation ul ul,
					.main-navigation ul ul ul,
					.main-navigation ul ul ul ul {
						background-color: #<?php echo sanitize_text_field( get_theme_mod( 'business_identity_secondary_color' ) ); ?>;
					}
				}
			</style><?php
		endif;
		if ( ! empty( $secondary_txt ) && 'ff0000' != $secondary_txt ) :
			$primary_hex = business_identity_custom_colors_hex_to_rgb( $secondary_txt ); ?>
			<style type="text/css">
			</style><?php
		endif;

		/**
		 * Links
		 */
		if ( ! empty( $links ) && '7b65c7' != $links ) : ?>
			<style type="text/css">
				a,
				a:visited,
				.infinite-scroll #infinite-footer .blog-info a:hover,
				.infinite-scroll #infinite-footer .blog-credits a:hover {
					color: #<?php echo sanitize_text_field( get_theme_mod( 'business_identity_links_color' ) ); ?>;
				}
				.social a,
				.special-offer a {
					color: inherit;
				}
				input[type="submit"],
				input[type="submit"]:hover,
				input[type="submit"]:active,
				input[type="submit"]:focus,
				#front-page-blog .call-to-action:hover,
				.features .call-to-action:hover {
					background-color: #<?php echo sanitize_text_field( get_theme_mod( 'business_identity_links_color' ) ); ?>;
				}
				input:not([type=submit]):not([type=button]):not([type=reset]):not([type=file]):focus,
				textarea:focus {
					box-shadow: 0 0 3px #<?php echo sanitize_text_field( get_theme_mod( 'business_identity_links_color' ) ); ?>;
					border-color: #<?php echo sanitize_text_field( get_theme_mod( 'business_identity_links_color' ) ); ?>;
				}
			</style><?php
		endif;
	}// end function business_identity_customized_colors
endif;

// Only proceed if Business Identity is in use.
$current_theme          = wp_get_theme();
$current_theme_name     = ! empty( $current_theme ) ? (string) $current_theme->Name : null;
$current_theme_template = ! empty( $current_theme ) ? (string) $current_theme->Template : null;
$current_theme_version  = ! empty( $current_theme ) ? (string) $current_theme->Version : null;
if ( '2.0.0' <= $current_theme_version && ( 'Business Identity' === $current_theme_name || 'business-identity' === $current_theme_template ) ) :
	add_action( 'customize_register', 'business_identity_custom_colors_customize_register', 11 );
	add_action( 'wp_head', 'business_identity_customized_colors' );
endif;