<?php if ( ! defined( 'ABSPATH' ) ) exit;

/*
 * Plugin Name: Ninja Forms - Custom Field Validation
 * Plugin URI: http://developer.ninjaforms.com/codex/client-side-field-validation/
 * Description: An example of custom Client Side Validation for a custom field with a custom template in Ninja Forms THREE.
 * Version: 0.0.1
 * Author: Kyle B. Johnson
 * Author URI: http://kylebjohnson.me
 * Text Domain: ninja-forms-custom-field-validation
 *
 * Copyright 2016 Kyle B. Johnson.
 */

if( version_compare( get_option( 'ninja_forms_version', '0.0.0' ), '3.0.0', '>' ) || get_option( 'ninja_forms_load_deprecated', FALSE ) ) {

    include 'deprecated/ninja-forms-custom-field-validation.php';

} else {

    /**
     * Class NF_CustomFieldValidation
     */
    final class NF_CustomFieldValidation
    {
        const VERSION = '0.0.1';
        const SLUG    = 'custom-field-validation';
        const NAME    = 'Custom Field Validation';
        const AUTHOR  = '';
        const PREFIX  = 'NF_CustomFieldValidation';

        /**
         * @var NF_CustomFieldValidation
         * @since 3.0
         */
        private static $instance;

        /**
         * Plugin Directory
         *
         * @since 3.0
         * @var string $dir
         */
        public static $dir = '';

        /**
         * Plugin URL
         *
         * @since 3.0
         * @var string $url
         */
        public static $url = '';

        /**
         * Main Plugin Instance
         *
         * Insures that only one instance of a plugin class exists in memory at any one
         * time. Also prevents needing to define globals all over the place.
         *
         * @since 3.0
         * @static
         * @static var array $instance
         * @return NF_CustomFieldValidation Highlander Instance
         */
        public static function instance()
        {
            if (!isset(self::$instance) && !(self::$instance instanceof NF_CustomFieldValidation)) {
                self::$instance = new NF_CustomFieldValidation();

                self::$dir = plugin_dir_path(__FILE__);

                self::$url = plugin_dir_url(__FILE__);

                /*
                 * Register our autoloader
                 */
                spl_autoload_register(array(self::$instance, 'autoloader'));
            }
        }

        public function __construct()
        {
            /*
             * Required for all Extensions.
             */
            add_action( 'admin_init', array( $this, 'setup_license') );

            /*
             * Optional. If your extension creates a new field interaction or display template...
             */
            add_filter( 'ninja_forms_register_fields', array($this, 'register_fields') );

            add_filter( 'ninja_forms_field_template_file_paths', array( $this, 'register_template_path' ) );

            add_action( 'wp_enqueue_scripts', array( $this, 'enqueue_scripts' ) );
        }

        /**
         * Optional. If your extension creates a new field interaction or display template...
         */
        public function register_fields( $fields )
        {
            $fields[ 'custom' ] = new NF_CustomFieldValidation_Fields_Custom();

            return $fields;
        }

        public function register_template_path( $paths )
        {
            $paths[] = self::$dir . 'includes/Templates/';

            return $paths;
        }

        public function enqueue_scripts()
        {
            wp_enqueue_script( 'custom-field-validation', self::$url . 'assets/js/custom-field-validation.js', array( 'nf-front-end' ) );
        }

        /*
         * Optional methods for convenience.
         */

        public function autoloader($class_name)
        {
            if (class_exists($class_name)) return;

            if ( false === strpos( $class_name, self::PREFIX ) ) return;

            $class_name = str_replace( self::PREFIX, '', $class_name );
            $classes_dir = realpath(plugin_dir_path(__FILE__)) . DIRECTORY_SEPARATOR . 'includes' . DIRECTORY_SEPARATOR;
            $class_file = str_replace('_', DIRECTORY_SEPARATOR, $class_name) . '.php';

            if (file_exists($classes_dir . $class_file)) {
                require_once $classes_dir . $class_file;
            }
        }

        /*
         * Required methods for all extension.
         */

        public function setup_license()
        {
            if ( ! class_exists( 'NF_Extension_Updater' ) ) return;

            new NF_Extension_Updater( self::NAME, self::VERSION, self::AUTHOR, __FILE__, self::SLUG );
        }
    }

    /**
     * The main function responsible for returning The Highlander Plugin
     * Instance to functions everywhere.
     *
     * Use this function like you would a global variable, except without needing
     * to declare the global.
     *
     * @since 3.0
     * @return {class} Highlander Instance
     */
    function NF_CustomFieldValidation()
    {
        return NF_CustomFieldValidation::instance();
    }

    NF_CustomFieldValidation();
}