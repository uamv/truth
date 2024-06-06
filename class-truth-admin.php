<?php
/**
* The admin Truth class
*/
if ( ! class_exists( 'Truth_Admin' ) ) :
    
    class Truth_Admin {
        
        /**
        * Static property to hold our singleton instance
        * @var $instance
        */
        static $instance = false;
        
        /**
        * Static property to hold our singleton instance
        * @var $instance
        */
        public $options = array();
        
        /**
        * this is our constructor.
        * there are many like it, but this one is mine
        */
        private function __construct() {
            
            $this->options = get_option( 'truth_settings' );
            $this->options = ! $this->options ? [] : $this->options;
            $this->options['engine'] = isset( $this->options['engine'] ) ? $this->options['engine'] : 'youversion';
            $this->options['biblesorg_highlighter']['bible_version'] = isset( $this->options['biblesorg_highlighter']['bible_version'] ) ? $this->options['biblesorg_highlighter']['bible_version'] : 'KJV';
            $this->options['youversion']['bible_version'] = isset( $this->options['youversion']['bible_version'] ) ? $this->options['youversion']['bible_version'] : '114';
            $this->options['append_version'] = isset( $this->options['append_version'] ) ? $this->options['append_version'] : 'none';
            $this->options['link_in_new_tab'] = isset( $this->options['link_in_new_tab'] ) ? $this->options['link_in_new_tab'] : 0;
            $this->options['disable_auto_links'] = isset( $this->options['disable_auto_links'] ) ? $this->options['disable_auto_links'] : 0;
            
            require_once( TRUTH_DIR . 'class-truth-notice.php' );
            
            // Load the administrative Stylesheets and JavaScript
            add_action( 'admin_enqueue_scripts', array( $this, 'enqueue_styles' ) );
            add_action( 'admin_enqueue_scripts', array( $this, 'enqueue_scripts' ) );
            
            if ( TRUTH_AUTH_ALL ) {
                
                update_option( 'truth_authorization', true );
                
            } else {
                
                add_action( 'admin_init', array( $this, 'check_authorization' ) );
                
            }
            
            add_action( 'admin_init', array( $this, 'register_settings' ) );
            
        }
        
        /**
        * If an instance exists, this returns it.  If not, it creates one and
        * returns it.
        *
        * @return $instance
        */
        public static function get_instance() {
            if ( ! self::$instance ) {
                self::$instance = new self;
            }
            
            return self::$instance;
        }
        
        /**
        * Register the stylesheets.
        *
        * @since    0.0.1
        */
        public function enqueue_styles() {
            
            wp_enqueue_style( 'truth', plugin_dir_url( __FILE__ ) . 'css/truth-admin.css', array(), TRUTH_VERSION, 'all' );
            
        }
        
        /**
        * Register the JavaScript for the dashboard.
        *
        * @since    0.0.1
        */
        public function enqueue_scripts() {
            
            wp_enqueue_script( 'truth', plugin_dir_url( __FILE__ ) . 'js/truth-admin.js', array( 'jquery' ), TRUTH_VERSION, false );
            wp_localize_script( 'truth', 'TRUTH', array( 'ajaxurl' => admin_url( 'admin-ajax.php' ) ) );
            
        }
        
        public function check_authorization() {
            
            if ( ! get_option( 'truth_authorization' ) ) {
                
                $args = array(
                    'notice'  => 'truth-authorization',
                    'class'   => 'update-nag',
                    'message' => 'The <code>Truth</code> plugin needs authorization to link scripture references to an external site. Click <a href="#" id="authorize-truth" data-security="' . wp_create_nonce( 'authorize-truth' ) . '">here</a> to permit this action.',
                    'echo'    => true,
                );
                
                $notice = new Truth_Notice( $args );
                
                $notice->show();
                
            }
            
        }
        
        public function register_settings() {
            
            register_setting( 'reading', 'truth_settings' );
            add_settings_field( 'truth_settings', 'Biblical References', array( $this, 'display_settings' ), 'reading', 'default' );
            
        }
        
        public function display_settings() {
            
            $sources = Truth::get_sources();
            
            foreach ( $sources as $source => $data ) {

                $versions[ $source ] = $data['versions'];
                
            }
            
            ?>
            <fieldset id="truth-general-settings">
            <label>Bible Service: <select id="truth-engine" name="truth_settings[engine]"></label>
            <option value="biblesorg_highlighter" <?php selected( $this->options['engine'], 'biblesorg_highlighter' ); ?>>Bibles.org Highlighter</option>
            <option value="youversion" <?php selected( $this->options['engine'], 'youversion' ); ?>>YouVersion</option>
            </select></label>
            <span id="description-engine-biblesorg_highlighter" class="description" <?php echo 'biblesorg_highlighter' == $this->options['engine'] ? '' : 'style="display:none;"'; ?>>(allows display of verses via modal when verses are clicked)</span>
            <span id="description-engine-youversion" class="description" <?php echo 'youversion' == $this->options['engine'] ? '' : 'style="display:none;"'; ?>>(directs users to YouVersion.com upon click)</span>
            </p>
            </fieldset>
            
            <fieldset id="truth-biblesorg_highlighter-settings" <?php echo ( ! isset( $this->options['engine'] ) || 'biblesorg_highlighter' == $this->options['engine'] ) ? '' : 'style="display:none;"'; ?>>
                <h2>Bibles.org Highlighter Settings</h2>
                <label>Version: <select id="bible-version" name="truth_settings[biblesorg_highlighter][bible_version]">
                <?php
                    foreach( $versions['biblesorg_highlighter'] as $languageGroup => $languageVersions ): ?>
                        <optgroup label="<?php echo $languageGroup?>">
                            <?php
                                foreach( $languageVersions as $versionID => $versionInfo ): ?>
                                    <option value="<?php echo $versionID; ?>" <?php selected( $this->options['biblesorg_highlighter']['bible_version'], $versionID ); ?>><?php echo $versionInfo['name'] . ' (' . strtoupper( $versionInfo['abbr'] ). ')'; ?></option> <?php
                                endforeach;
                            ?>
                        </optgroup>
                <?php
                    endforeach;
                ?>
                </select></label>
                <label>Overwrite Highlighter Targeting: <input name="truth_settings[biblesorg_highlighter][target_ids]" value="<?php echo ! isset( $this->options['biblesorg_highlighter']['target_ids'] ) ? '' : $this->options['biblesorg_highlighter']['target_ids']; ?>" style="width: 40%"></label><span id="description-biblesorg-target-ids" class="description">(comma-separated list of DOM element ids, overrides default search for verse references)
            </fieldset>
            
            <fieldset id="truth-youversion-settings" <?php echo 'youversion' == $this->options['engine'] ? '' : 'style="display:none;"'; ?>>
                <h2>YouVersion Settings</h2>
                <label>Default Version: <select id="bible-version" name="truth_settings[youversion][bible_version]">
                <?php
                    foreach( $versions['youversion'] as $languageGroup => $languageVersions ):
                ?>
                    <optgroup label="<?php echo $languageGroup?>">
                    <?php
                        foreach( $languageVersions as $versionID => $versionInfo ): ?>
                            <option value="<?php echo $versionID; ?>" <?php selected( $this->options['youversion']['bible_version'], $versionID ); ?>><?php echo $versionInfo['name'] . ' (' . strtoupper( $versionInfo['abbr'] ). ')'; ?></option> <?php
                        endforeach; ?>
                    </optgroup>
                <?php
                    endforeach;
                ?>
                </select></label>
                <p><input type="checkbox" id="link_in_new_tab" name="truth_settings[link_in_new_tab]" value="1" <?php checked( $this->options['link_in_new_tab'], 1 ); ?>> <label for="link_in_new_tab">Open links in new tab.</label></input></p>
                <p><input type="checkbox" id="disable_auto_links" name="truth_settings[disable_auto_links]" value="1" <?php checked( $this->options['disable_auto_links'], 1 ); ?>> <label for="disable_auto_links">Disable auto-generation of links.</label></input> <span class="description">(maintains use of [truth] shortcode)</span></p>
                <p><label> Append Version to Shortcode Text: <select id="append_version" name="truth_settings[append_version]">
                <option value="none" <?php selected( $this->options['append_version'], 'none' ); ?>>No (Disabled)</option>
                <option value="abbr" <?php selected( $this->options['append_version'], 'abbr' ); ?>>Abbreviation</option>
                <option value="full" <?php selected( $this->options['append_version'], 'full' ); ?>>Full Name</option>
                </select></label>
                </p>
            </fieldset>
            
            <?php
            
        }
        
    }
    
endif;