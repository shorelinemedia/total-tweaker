<?php

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

class SL9_Total_Tweaker {

    const PLUGIN_NICE_NAME = "Total Tweaker";
    
    protected static $instance;

    private static $version;

	/**
	 * Define the core functionality of the plugin.
	 *
	 * Set the plugin name and the plugin version that can be used throughout the plugin.
	 * Load the dependencies, define the locale, and set the hooks for the admin area and
	 * the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function __construct ( $version ) {
        self::$version = $version;

        /**
         * Hooks into Total/WPBakery
         */

        // Add class for vc_row for lazyloading bg images 
        // See https://css-tricks.com/the-complete-guide-to-lazy-loading-images/#chapter-4-lazy-loading-css-background-images
        if ( defined( 'VC_SHORTCODE_CUSTOM_CSS_FILTER_TAG' ) ) {
            // Add filter to add CSS class to elements
            add_filter( VC_SHORTCODE_CUSTOM_CSS_FILTER_TAG, [ $this, 'vc_row_lazyload_bg_class' ], 11, 3 );
            // Add CSS directly to head
            add_action( 'wp_enqueue_scripts', [ $this, 'output_lazyload_styles' ], 50 );
        }
	}

	public static function get_instance ()  {
		if ( !isset(self::$instance) ) {
			self::$instance = new self(self::$version);
		}
		return self::$instance;
	}

    // Get plugin version
    public function get_plugin_version () {
        return self::$version;
    }

    // Get plugin name
    public function get_plugin_name () {
        return self::PLUGIN_NICE_NAME;
    }

    // Change vc_row class for background image lazyloading
    public function vc_row_lazyload_bg_class ( $classes = [], $tag = [], $atts = [] ) {
        // Remove extra spaces in class string 
        $classes = preg_replace( '/\s+/', ' ', $classes );

        // Create array of classes from string
        $classes = explode( ' ', $classes );
        
        // If we have a 'css' key in $atts and it's got 'background-image'
        if ( $this->atts_css_has_bg( $atts ) ) {

            $classes[] = 'sl9-lazyload-bg';
            
        } 

        // Re-convert classes array to string
        $classes = implode( ' ', $classes );

        return $classes;
    }

    public function output_lazyload_styles () { 
        ob_start();?>
<?php include SL9_TOTAL_TWEAKER_PATH . 'assets/css/lazyload-bg.css'; ?>
<?php
        // Inline the styles to hide bg image so they load first
        $styles = ob_get_clean();
        wp_add_inline_style( 'wpex-style', $styles );

        // Enqueue javascript for lazyload intersector
        wp_enqueue_script( 'sl9-total-tweaker-lazyload-bg', SL9_TOTAL_TWEAKER_URL . 'assets/js/lazyload-bg.js', [ 'wpex-core' ], SL9_TOTAL_TWEAKER_VERSION, true );

    }

    // Check if a VC element has a 'bg_image'
    public function vc_element_has_bg ( $tag = '', $atts = [] ) {
        if ( empty( $tag ) ) return false;
        return ( 
            // $tag == 'vc_row' && 
            array_key_exists( 'bg_image', $atts ) && 
            ! empty( $atts['bg_image'] ) );
    }

    // Check an $atts['css'] string for 'background-image'
    public function atts_css_has_bg ( $atts = [] ) {
        // If we have no css array key or $atts is empty, return false
        if ( empty( $atts ) || ! array_key_exists( 'css', $atts ) ) { return false; }

        return ( 
            false !== strpos( $atts['css'], 'background-image' ) || 
            false !== strpos( $atts['css'], 'url(' )
        );
    }

}

