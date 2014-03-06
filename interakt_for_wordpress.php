<?php
/*
Plugin Name: Interakt for WordPress
Plugin URI: http://interakt.co
Description: Integrate the <a href="http://interakt.co">Interakt</a> CRM and messaging app into your WordPress website.
Author: Peeyush Singla
Author URI: https://twitter.com/peeyush_singla
Version: 0.1
*/


class PS_Interakt{
    /**
     * Holds the values to be used in the fields callbacks
     */

    public $options;

    /**
     * Start up
     */
    public function __construct()
    {
        $this->options = get_option( 'interakt_plugin_options_name' );
        add_action( 'admin_menu', array( $this, 'add_menu_page' ) );
        add_action( 'admin_init', array( $this, 'register_setting_and_fields' ) );
    }

    /**
     * Add options page
     */
    public function add_menu_page()
    {
        // This page will be under "Settings"
        add_options_page(
            'Interakt Options',
            'Interakt Options',
            'manage_options',
            '__FILE__',
            array( $this, 'display_options_page' )
        );
    }

    /**
     * Options page callback
     */
    public function display_options_page()
    {
        // Set class property
        $this->options = get_option( 'interakt_plugin_options_name' );
        ?>
        <div class="wrap">
            <?php screen_icon(); ?>
            <h2>My Settings</h2>
            <form method="post" action="options.php">
            <?php
                // This prints out all hidden setting fields
                settings_fields( 'interakt_plugin_options_group' );
                do_settings_sections( '__FILE__' );
                submit_button();
            ?>
            </form>
        </div>
        <?php
    }

    /**
     * Register and add settings
     */
    public function register_setting_and_fields()
    {
        register_setting(
            'interakt_plugin_options_group', // Option group
            'interakt_plugin_options_name', // Option name
            array( $this, 'sanitize' ) // Sanitize
        );

        add_settings_section(
            'interakt_main_section_id', // ID
            'App Key Setting', // interakt_app_key
            array( $this, 'interakt_main_section_cb' ), // Callback
            '__FILE__' // Page
        );

        add_settings_field(
            'interakt_app_key',
            'Interakt App Key',
            array( $this, 'interakt_app_key_setting' ),
            '__FILE__',
            'interakt_main_section_id'
        );
    }

    /**
     * Sanitize each setting field as needed
     *
     * @param array $input Contains all settings fields as array keys
     */

    public function sanitize( $input )
    {
        return $input;
    }

    /**
     * Print the Section text
     */
    public function interakt_main_section_cb()
    {
        print 'Enter your settings below:';

}

    /**
     * Get the settings option array and print one of its values
     */
    public function interakt_app_key_setting()
    {
        printf(
            '<input type="text" id="interakt_app_key" name="interakt_plugin_options_name[interakt_app_key]" size="30" value="%s" />',
            isset( $this->options['interakt_app_key'] ) ? esc_attr( $this->options['interakt_app_key']) : ''
        );
    }
}

if( is_admin() )
    $my_settings_page = new PS_Interakt();


 add_action('wp_footer', function(){
  $my_settings_page = new PS_Interakt();
  $options = ($my_settings_page->options['interakt_app_key']);
     echo "<script>
    (function() {
    var interakt = document.createElement('script');
    interakt.type = 'text/javascript'; interakt.async = true;
    interakt.src = 'http://localhost:3000/interakt/$options.js'
    var scrpt = document.getElementsByTagName('script')[0];
    scrpt.parentNode.insertBefore(interakt, scrpt);
    })()
  </script>";
} );
