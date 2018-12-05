<?php

/**
  ReduxFramework Sample Config File
  For full documentation, please visit: https://docs.reduxframework.com
 * */

if (!class_exists('Complex_Theme_Config')) {

    class Complex_Theme_Config {

        public $args        = array();
        public $sections    = array();
        public $theme;
        public $ReduxFramework;

        public function __construct() {

            if (!class_exists('ReduxFramework')) {
                return;
            }

            // This is needed. Bah WordPress bugs.  ;)
            if (  true == Redux_Helpers::isTheme(__FILE__) ) {
                $this->initSettings();
            } else {
                add_action('plugins_loaded', array($this, 'initSettings'), 10);
            }

        }

        public function initSettings() {

            // Just for demo purposes. Not needed per say.
            $this->theme = wp_get_theme();

            // Set the default arguments
            $this->setArguments();

            // Set a few help tabs so you can see how it's done
            $this->setHelpTabs();

            // Create the sections and fields
            $this->setSections();

            if (!isset($this->args['opt_name'])) { // No errors please
                return;
            }

            // If Redux is running as a plugin, this will remove the demo notice and links
            //add_action( 'redux/loaded', array( $this, 'remove_demo' ) );
            
            // Function to test the compiler hook and demo CSS output.
            // Above 10 is a priority, but 2 in necessary to include the dynamically generated CSS to be sent to the function.
            //add_filter('redux/options/'.$this->args['opt_name'].'/compiler', array( $this, 'compiler_action' ), 10, 3);
            
            // Change the arguments after they've been declared, but before the panel is created
            //add_filter('redux/options/'.$this->args['opt_name'].'/args', array( $this, 'change_arguments' ) );
            
            // Change the default value of a field after it's been set, but before it's been useds
            //add_filter('redux/options/'.$this->args['opt_name'].'/defaults', array( $this,'change_defaults' ) );
            
            // Dynamically add a section. Can be also used to modify sections/fields
            //add_filter('redux/options/' . $this->args['opt_name'] . '/sections', array($this, 'dynamic_section'));

            $this->ReduxFramework = new ReduxFramework($this->sections, $this->args);
        }

        /**

          This is a test function that will let you see when the compiler hook occurs.
          It only runs if a field	set with compiler=>true is changed.

         * */
        function compiler_action($options, $css, $changed_values) {
            echo '<h1>The compiler hook has run!</h1>';
            echo "<pre>";
            print_r($changed_values); // Values that have changed since the last save
            echo "</pre>";
            //print_r($options); //Option values
            //print_r($css); // Compiler selector CSS values  compiler => array( CSS SELECTORS )
        }

        /**

          Custom function for filtering the sections array. Good for child themes to override or add to the sections.
          Simply include this function in the child themes functions.php file.

          NOTE: the defined constants for URLs, and directories will NOT be available at this point in a child theme,
          so you must use get_template_directory_uri() if you want to use any of the built in icons

         * */
        function dynamic_section($sections) {
            //$sections = array();
            $sections[] = array(
                'title' => esc_html__('Section via hook', 'complex'),
                'desc' => esc_html__('<p class="description">This is a section created by adding a filter to the sections array. Can be used by child themes to add/remove sections from the options.</p>', 'complex'),
                'icon' => 'el-icon-paper-clip',
                // Leave this as a blank section, no options just some intro text set above.
                'fields' => array()
            );

            return $sections;
        }

        /**

          Filter hook for filtering the args. Good for child themes to override or add to the args array. Can also be used in other functions.

         * */
        function change_arguments($args) {
            //$args['dev_mode'] = true;

            return $args;
        }

        /**

          Filter hook for filtering the default value of any given field. Very useful in development mode.

         * */
        function change_defaults($defaults) {
            $defaults['str_replace'] = 'Testing filter hook!';

            return $defaults;
        }

        // Remove the demo link and the notice of integrated demo from the redux-framework plugin
        function remove_demo() {

            // Used to hide the demo mode link from the plugin page. Only used when Redux is a plugin.
            if (class_exists('ReduxFrameworkPlugin')) {
                remove_filter('plugin_row_meta', array(ReduxFrameworkPlugin::instance(), 'plugin_metalinks'), null, 2);

                // Used to hide the activation notice informing users of the demo panel. Only used when Redux is a plugin.
                remove_action('admin_notices', array(ReduxFrameworkPlugin::instance(), 'admin_notices'));
            }
        }

        public function setSections() {

            ob_start();

            $ct             = wp_get_theme();
            $this->theme    = $ct;
            $item_name      = $this->theme->get('Name');
            $tags           = $this->theme->Tags;
            $screenshot     = $this->theme->get_screenshot();
            $class          = $screenshot ? 'has-screenshot' : '';

            $customize_title = sprintf(esc_html__('Customize &#8220;%s&#8221;', 'complex'), $this->theme->display('Name'));
            
            ?>
            <div id="current-theme" class="<?php echo esc_attr($class); ?>">
            <?php if ($screenshot) : ?>
                <?php if (current_user_can('edit_theme_options')) : ?>
                        <a href="<?php echo wp_customize_url(); ?>" class="load-customize hide-if-no-customize" title="<?php echo esc_attr($customize_title); ?>">
                            <img src="<?php echo esc_url($screenshot); ?>" alt="<?php esc_attr_e('Current theme preview', 'complex'); ?>" />
                        </a>
                <?php endif; ?>
                    <img class="hide-if-customize" src="<?php echo esc_url($screenshot); ?>" alt="<?php esc_attr_e('Current theme preview', 'complex'); ?>" />
                <?php endif; ?>

                <h4><?php echo esc_html($this->theme->display('Name')); ?></h4>

                <div>
                    <ul class="theme-info">
                        <li><?php printf(esc_html__('By %s', 'complex'), $this->theme->display('Author')); ?></li>
                        <li><?php printf(esc_html__('Version %s', 'complex'), $this->theme->display('Version')); ?></li>
                        <li><?php echo '<strong>' . esc_html__('Tags', 'complex') . ':</strong> '; ?><?php printf($this->theme->display('Tags')); ?></li>
                    </ul>
                    <p class="theme-description"><?php echo esc_html($this->theme->display('Description')); ?></p>
            <?php
            if ($this->theme->parent()) {
                printf(' <p class="howto">' . esc_html__('This <a href="%1$s">child theme</a> requires its parent theme, %2$s.', 'complex') . '</p>', esc_html__('http://codex.wordpress.org/Child_Themes', 'complex'), $this->theme->parent()->display('Name'));
            }
            ?>

                </div>
            </div>

            <?php
            $item_info = ob_get_contents();

            ob_end_clean();

            $sampleHTML = '';
           
			$form_options = array();
			if(class_exists('WYSIJA')){
				$wysija_model_forms = WYSIJA::get('forms', 'model');
				$wysija_forms = $wysija_model_forms->getRows();
				foreach($wysija_forms as $wysija_form){
					$form_options[$wysija_form['form_id']] = esc_html($wysija_form['name']);
				}
			}
		   
            // General
            $this->sections[] = array(
                'title'     => esc_html__('General', 'complex'),
                'desc'      => esc_html__('General theme options', 'complex'),
                'icon'      => 'el-icon-cog',
                'fields'    => array(

                    array(
                        'id'        => 'logo_main',
                        'type'      => 'media',
                        'title'     => esc_html__('Logo', 'complex'),
                        'compiler'  => 'true',
                        'mode'      => false,
                        'desc'      => esc_html__('Upload logo here.', 'complex'),
                    ),
					array(
                        'id'        => 'opt-favicon',
                        'type'      => 'media',
                        'title'     => esc_html__('Favicon', 'complex'),
                        'compiler'  => 'true',
                        'mode'      => false,
                        'desc'      => esc_html__('Upload favicon here.', 'complex'),
                    ),
					array(
                        'id'        => 'background_opt',
                        'type'      => 'background',
                        'output'    => array('body'),
                        'title'     => esc_html__('Body background', 'complex'),
                        'subtitle'  => esc_html__('Upload image or select color. Only work with box layout', 'complex'),
						'default'   => array('background-color' => '#fff'),
                    ),
					array(
                        'id'        => 'back_to_top',
                        'type'      => 'switch',
                        'title'     => esc_html__('Back To Top', 'complex'),
						'desc'      => esc_html__('Show back to top button on all pages', 'complex'),
						'default'   => true,
                    ),
                ),
            );
			
			// Colors
            $this->sections[] = array(
                'title'     => esc_html__('Colors', 'complex'),
                'desc'      => esc_html__('Color options', 'complex'),
                'icon'      => 'el-icon-tint',
                'fields'    => array(
					array(
                        'id'        => 'primary_color',
                        'type'      => 'color',
                        'title'     => esc_html__('Primary Color', 'complex'),
                        'subtitle'  => esc_html__('Pick a color for primary color (default: #b11e22).', 'complex'),
						'transparent' => false,
                        'default'   => '#b11e22',
                        'validate'  => 'color',
                    ),
					
					array(
                        'id'        => 'sale_color',
                        'type'      => 'color',
                        //'output'    => array(),
                        'title'     => esc_html__('Sale Label BG Color', 'complex'),
                        'subtitle'  => esc_html__('Pick a color for bg sale label (default: #e53939).', 'complex'),
						'transparent' => true,
                        'default'   => '#e53939',
                        'validate'  => 'color',
                    ),
					
					array(
                        'id'        => 'saletext_color',
                        'type'      => 'color',
                        //'output'    => array(),
                        'title'     => esc_html__('Sale Label Text Color', 'complex'),
                        'subtitle'  => esc_html__('Pick a color for sale label text (default: #ffffff).', 'complex'),
						'transparent' => false,
                        'default'   => '#ffffff',
                        'validate'  => 'color',
                    ),
					
					array(
                        'id'        => 'rate_color',
                        'type'      => 'color',
                        //'output'    => array(),
                        'title'     => esc_html__('Rating Star Color', 'complex'),
                        'subtitle'  => esc_html__('Pick a color for star of rating (default: #ffc600).', 'complex'),
						'transparent' => false,
                        'default'   => '#ffc600',
                        'validate'  => 'color',
                    ),
                ),
            );
			
			//Fonts
			$this->sections[] = array(
                'title'     => esc_html__('Fonts', 'complex'),
                'desc'      => esc_html__('Fonts options', 'complex'),
                'icon'      => 'el-icon-font',
                'fields'    => array(

                    array(
                        'id'            => 'bodyfont',
                        'type'          => 'typography',
                        'title'         => esc_html__('Body font', 'complex'),
                        //'compiler'      => true,  // Use if you want to hook in your own CSS compiler
                        'google'        => true,    // Disable google fonts. Won't work if you haven't defined your google api key
                        'font-backup'   => false,    // Select a backup non-google font in addition to a google font
                        //'font-style'    => false, // Includes font-style and weight. Can use font-style or font-weight to declare
                        'subsets'       => false, // Only appears if google is true and subsets not set to false
						'text-align'   => false,
                        //'font-size'     => false,
                        //'line-height'   => false,
                        //'word-spacing'  => true,  // Defaults to false
                        //'letter-spacing'=> true,  // Defaults to false
                        //'color'         => false,
                        //'preview'       => false, // Disable the previewer
                        'all_styles'    => true,    // Enable all Google Font style/weight variations to be added to the page
                        'output'        => array('body'), // An array of CSS selectors to apply this font style to dynamically
                        //'compiler'      => array('h2.site-description-compiler'), // An array of CSS selectors to apply this font style to dynamically
                        'units'         => 'px', // Defaults to px
                        'subtitle'      => esc_html__('Main body font.', 'complex'),
                        'default'       => array(
                            'color'         => '#333e48',
                            'font-weight'    => '400',
                            'font-family'   => 'Roboto',
                            'google'        => true,
                            'font-size'     => '14px',
                            'line-height'   => '24px'
						),
                    ),
					array(
                        'id'            => 'headingfont',
                        'type'          => 'typography',
                        'title'         => esc_html__('Heading font', 'complex'),
                        //'compiler'      => true,  // Use if you want to hook in your own CSS compiler
                        'google'        => true,    // Disable google fonts. Won't work if you haven't defined your google api key
                        'font-backup'   => false,    // Select a backup non-google font in addition to a google font
                        //'font-style'    => false, // Includes font-style and weight. Can use font-style or font-weight to declare
                        'subsets'       => false, // Only appears if google is true and subsets not set to false
                        'font-size'     => false,
                        'line-height'   => false,
						'text-align'   => false,
                        //'word-spacing'  => true,  // Defaults to false
                        //'letter-spacing'=> true,  // Defaults to false
                        //'color'         => false,
                        //'preview'       => false, // Disable the previewer
                        'all_styles'    => true,    // Enable all Google Font style/weight variations to be added to the page
                        //'output'        => array('h1, h2, h3, h4, h5, h6'), // An array of CSS selectors to apply this font style to dynamically
                        //'compiler'      => array('h2.site-description-compiler'), // An array of CSS selectors to apply this font style to dynamically
                        'units'         => 'px', // Defaults to px
                        'subtitle'      => esc_html__('Heading font.', 'complex'),
                        'default'       => array(
							'color'         => '#333e48',
                            'font-weight'    => '700',
                            'font-family'   => 'Roboto',
                            'google'        => true,
						),
                    ),
                ),
            );
			
			//Header
			$this->sections[] = array(
                'title'     => esc_html__('Header', 'complex'),
                'desc'      => esc_html__('Header options', 'complex'),
                'icon'      => 'el-icon-tasks',
                'fields'    => array(

					array(
                        'id'        => 'header_layout',
                        'type'      => 'select',
                        'title'     => esc_html__('Header Layout', 'complex'),

                        //Must provide key => value pairs for select options
                        'options'   => array(
                            'default' => 'Default',
                            'second' => 'Second',
                            'dark' => 'Dark',
                        ),
                        'default'   => 'second'
                    ),
					array(
						'id'=>'welcome_message',
						'type' => 'textarea',
						'title' => esc_html__('Welcome Message', 'complex'), 
						'subtitle'         => esc_html__('HTML tags allowed: a, img, br, em, strong, p, ul, li', 'complex'),
						'default' => 'Wellcome to Complex store!',
					),
					array(
                        'id'        => 'header_background',
                        'type'      => 'background',
                        'output'    => array('header .header'),
                        'title'     => esc_html__('Header background', 'complex'),
                        'subtitle'  => esc_html__('Upload image or select color.', 'complex'),
						'default'   => array('background-color' => '#FFFFFF'),
                    ),
					array(
                        'id'        => 'enable_topbar',
                        'type'      => 'switch',
                        'title'     => esc_html__('Enable Topbar', 'complex'),
						'default'   => true,
                    ),
					array(
                        'id'        => 'show_minicart',
                        'type'      => 'switch',
                        'title'     => esc_html__('Show minicart', 'complex'),
						'default'   => true,
                    ),
					array(
                        'id'        => 'topbar_background',
                        'type'      => 'background',
                        'output'    => array('header .top-bar'),
                        'title'     => esc_html__('Topbar background', 'complex'),
                        'subtitle'  => esc_html__('Upload image or select color.', 'complex'),
						'default'   => array('background-color' => '#1b252f'),
                    ),
					array(
                        'id'        => 'topbar_color',
                        'type'      => 'color',
                        'title'     => esc_html__('Topbar text color', 'complex'),
						'default'   => '#fff',
                    ),
					array(
                        'id'        => 'header_icons_color',
                        'type'      => 'color',
                        'title'     => esc_html__('Header icons color', 'complex'),
						'default'   => '#fff',
                    ),
					array(
						'id'        => 'categories_menu_items',
						'type'      => 'slider',
						'title'     => esc_html__('Number of items', 'complex'),
						'desc'      => esc_html__('Number of menu items level 1 to show, default value: 9', 'complex'),
						'default'   => 9,
						'min'       => 0,
						'step'      => 1,
						'max'       => 30,
						'display_value' => 'text'
					),
                ),
            );
			$this->sections[] = array(
				'icon'       => 'el-icon-magic',
				'title'      => esc_html__( 'Main Menu', 'complex' ),
				'subsection' => true,
				'fields'     => array(
					array(
                        'id'        => 'sticky_header',
                        'type'      => 'switch',
                        'title'     => esc_html__('Sticky Menu', 'complex'),
						'default'   => true,
                    ),
					array(
                        'id'        => 'menu_background',
                        'type'      => 'background',
                        'output'    => array('header .nav-menus'),
                        'title'     => esc_html__('Menu background', 'complex'),
                        'subtitle'  => esc_html__('Upload image or select color.', 'complex'),
						'default'   => array('background-color' => '#FFFFFF'),
                    ),
					array(
                        'id'            => 'menufont',
                        'type'          => 'typography',
                        'title'         => esc_html__('Menu font', 'complex'),
                        //'compiler'      => true,  // Use if you want to hook in your own CSS compiler
                        'google'        => true,    // Disable google fonts. Won't work if you haven't defined your google api key
                        'font-backup'   => false,    // Select a backup non-google font in addition to a google font
                        //'font-style'    => false, // Includes font-style and weight. Can use font-style or font-weight to declare
                        'subsets'       => false, // Only appears if google is true and subsets not set to false
                        'font-size'     => true,
                        'line-height'   => false,
						'text-align'   => false,
                        //'word-spacing'  => true,  // Defaults to false
                        //'letter-spacing'=> true,  // Defaults to false
                        //'color'         => false,
                        //'preview'       => false, // Disable the previewer
                        'all_styles'    => true,    // Enable all Google Font style/weight variations to be added to the page
                        //'output'        => array('h1, h2, h3, h4, h5, h6'), // An array of CSS selectors to apply this font style to dynamically
                        //'compiler'      => array('h2.site-description-compiler'), // An array of CSS selectors to apply this font style to dynamically
                        'units'         => 'px', // Defaults to px
                        'subtitle'      => esc_html__('Menu font.', 'complex'),
                        'default'       => array(
							'color'         => '#333e48',
                            'font-weight'    => '700',
                            'font-family'   => 'Roboto',
							'font-size'     => '14px',
                            'google'        => true,
						),
                    ),
					array(
                        'id'        => 'sticky_bg',
                        'type'      => 'color',
                        'title'     => esc_html__('Sticky background color', 'complex'),
						'default'   => '#FFFFFF',
                    ),
					array(
						'id'        => 'sticky_bg_opacity',
						'type'      => 'slider',
						'title'     => esc_html__('Sticky background opacity', 'complex'),
						'default'   => 80,
						'min'       => 0,
						'step'      => 5,
						'max'       => 100,
						'display_value' => 'text'
					),
					array(
                        'id'        => 'sub_menu_bg',
                        'type'      => 'color',
                        //'output'    => array(),
                        'title'     => esc_html__('Submenu background', 'complex'),
                        'subtitle'  => esc_html__('Pick a color for sub menu bg (default: #ffffff).', 'complex'),
						'transparent' => false,
                        'default'   => '#ffffff',
                        'validate'  => 'color',
                    ),
					array(
                        'id'        => 'sub_menu_color',
                        'type'      => 'color',
                        //'output'    => array(),
                        'title'     => esc_html__('Submenu color', 'complex'),
                        'subtitle'  => esc_html__('Pick a color for sub menu color (default: #666666).', 'complex'),
						'transparent' => false,
                        'default'   => '#666666',
                        'validate'  => 'color',
                    ),
				)
			);
			//Footer
			$this->sections[] = array(
                'title'     => esc_html__('Footer', 'complex'),
                'desc'      => esc_html__('Footer options', 'complex'),
                'icon'      => 'el-icon-cog',
                'fields'    => array(
					array(
                        'id'        => 'footer_layout',
                        'type'      => 'select',
                        'title'     => esc_html__('Footer Layout', 'complex'),
                        'options'   => array(
                            'default' => 'Default',
                        ),
                        'default'   => 'default'
                    ),
					array(
                        'id'        => 'footer_background',
                        'type'      => 'background',
                        'output'    => array('footer .footer'),
                        'title'     => esc_html__('Footer background', 'complex'),
                        'subtitle'  => esc_html__('Upload image or select color.', 'complex'),
						'default'   => array('background-color' => '#ffffff'),
                    ),
					array(
                        'id'        => 'footer_text_color',
                        'type'      => 'color',
                        'title'     => esc_html__('Text color', 'complex'),
						'default'   => '#333e48',
                    ),
					array(
                        'id'        => 'footer_heading_color',
                        'type'      => 'color',
                        'title'     => esc_html__('Heading color', 'complex'),
						'default'   => '#333e48',
                    ),
					array(
                        'id'        => 'footer_border_color',
                        'type'      => 'color',
                        'title'     => esc_html__('Border color', 'complex'),
						'default'   => '#ebebeb',
                    ),
					array(
						'id'               => 'copyright',
						'type'             => 'editor',
						'title'    => esc_html__('Copyright information', 'complex'),
						'subtitle'         => esc_html__('HTML tags allowed: a, br, em, strong', 'complex'),
						'default'          => 'Copyright  &copy; Lionthemes88 . All Rights Reserved.',
						'args'   => array(
							'teeny'            => true,
							'textarea_rows'    => 5,
							'media_buttons'	=> false,
						)
					),
					array(
                        'id'        => 'copyright_background',
                        'type'      => 'background',
                        'output'    => array('.footer .footer-bottom .footer-copyright'),
                        'title'     => esc_html__('Copyright background', 'complex'),
                        'subtitle'  => esc_html__('Upload image or select color.', 'complex'),
						'default'   => array('background-color' => '#fff'),
                    ),
					array(
						'id'               => 'payment_icons',
						'type'             => 'editor',
						'title'    => esc_html__('Payment icons', 'complex'),
						'subtitle'         => esc_html__('HTML tags allowed: a, img', 'complex'),
						'default'          => '',
						'args'   => array(
							'teeny'            => true,
							'textarea_rows'    => 5,
							'media_buttons'	=> true,
						)
					),
                ),
            );		
			$this->sections[] = array(
				'icon'       => 'el-icon-website',
				'title'      => esc_html__( 'Social Icons', 'complex' ),
				'subsection' => true,
				'fields'     => array(
					array(
						'id'       => 'social_icons',
						'type'     => 'sortable',
						'title'    => esc_html__('Social Icons', 'complex'),
						'subtitle' => esc_html__('Enter social links', 'complex'),
						'desc'     => esc_html__('Drag/drop to re-arrange', 'complex'),
						'mode'     => 'text',
						'options'  => array(
							'facebook'     => '',
							'twitter'     => '',
							'google-plus'     => '',
							'youtube'     => '',
							'pinterest'     => '',
							'vk'     => '',
							'instagram' => '',
							'tumblr'     => '',
							'linkedin'     => '',
							'behance'     => '',
							'dribbble'     => '',
							'vimeo'     => '',
							'rss'     => '',							
							'QQ' => '',
						),
					),
				)
			);

			//Sticky Social Icons
			$this->sections[] = array(
                'title'     => esc_html__('Sticky Social Icons', 'complex'),
                'desc'      => esc_html__('This setting for social icons always on top of page.', 'complex'),
                'icon'      => 'el-icon-website',
                'fields'    => array(
					array(
						'id'       => 'sticky_icons',
						'type'     => 'select',
						'options'     => array(
							'' => esc_html__( 'No display', 'complex' ),
							'on_left' => esc_html__( 'On left', 'complex' ),
							'on_right' => esc_html__( 'On right', 'complex' ),
						),
						'default'   => 'on_right',
						'title'    => esc_html__( 'Sticky on the side', 'complex' ),
					),
					array(
						'id'       => 'sticky_social_icons',
						'type'     => 'sortable',
						'title'    => esc_html__('Social Icons', 'complex'),
						'subtitle' => esc_html__('Enter social links', 'complex'),
						'desc'     => esc_html__('Drag/drop to re-arrange', 'complex'),
						'mode'     => 'text',
						'options'  => array(
							'facebook'     => '',
							'twitter'     => '',
							'google-plus'     => '',
							'youtube'     => '',
							'pinterest'     => '',
							'mail-to' => '',
							'instagram' => '',
							'tumblr'     => '',
							'linkedin'     => '',
							'behance'     => '',
							'dribbble'     => '',							
							'vimeo'     => '',
							'rss'     => '',
							'vk'     => '',
							
						),
						'default' => array(
						    'facebook'     => '#facebook',
							'twitter'     => '#twitter.com',
							'google-plus'     => '#google-plus',
							'youtube'     => '#youtube',
							'pinterest'     => '#pinterest',
							'mail-to' => 'mailto:lionthemes88@gmail.com',
						),
					),
					array(
						'id'       => 'sticky_social_titles',
						'type'     => 'sortable',
						'title'    => esc_html__('Social Titles', 'complex'),
						'subtitle' => esc_html__('This be merge with social icons select above.', 'complex'),
						'desc'     => esc_html__('This display depend on social icons selected', 'complex'),
						'mode'     => 'text',
						'options'  => array(
							'facebook'     => '',
							'twitter'     => '',
							'google-plus'     => '',
							'youtube'     => '',
							'pinterest'     => '',
							'mail-to' => '',
							'instagram' => '',
							'tumblr'     => '',
							'linkedin'     => '',
							'behance'     => '',
							'dribbble'     => '',							
							'vimeo'     => '',
							'rss'     => '',
							'vk'     => '',							
						),
						'default' => array(
						    'facebook'     => 'Follow via Facebook',
							'twitter'     => 'Follow via Twitter',
							'google-plus'     => 'Follow via Google +',
							'youtube'     => 'Follow via Youtube',
							'pinterest'     => 'Follow via Pinterest',
							'mail-to' => 'Mail To Us',
						),
					),
				)
			);
			
			//Popup
			$this->sections[] = array(
				'icon'       => 'el-icon-website',
				'title'      => esc_html__( 'Newsletter Popup', 'complex' ),
				'desc'      => esc_html__('Content show up on home page loaded', 'complex'),
				'fields'     => array(
					array(
                        'id'        => 'enable_popup',
                        'type'      => 'switch',
                        'title'     => esc_html__('Enable', 'complex'),
						'default'   => false,
                    ),
					array(
                        'id'        => 'background_popup',
                        'type'      => 'background',
                        'output'    => array('#popup-style-apply'),
                        'title'     => esc_html__('Popup background', 'complex'),
                        'subtitle'  => esc_html__('Upload image or select color.', 'complex'),
						'default'   => array('background-color' => '#FFF'),
                    ),
					array(
						'id'=>'popup_onload_content',
						'type' => 'editor',
						'title' => esc_html__('Content', 'complex'), 
						'subtitle'         => esc_html__('HTML tags allowed: a, img, br, em, strong, p, ul, li', 'complex'),
						'default' => '<h3>NEWSLETTER</h3>
							Subscribe to the Complex mailing list to receive updates on new arrivals, special offers and other discount information.',
						'args'   => array(
							'teeny'            => true,
							'textarea_rows'    => 10,
							'media_buttons'	=> true,
						)
					),
					array(
                        'id'        => 'popup_onload_form',
                        'type'      => 'text',
                        'title'     => esc_html__('Newsletter Form', 'complex'),
						'options'   => '',
                    ),
					array(
						'id'        => 'popup_onload_expires',
						'type'      => 'slider',
						'title'     => esc_html__('Time expires', 'complex'),
						'desc'      => esc_html__('Time expires after tick not show again defaut: 1 days', 'complex'),
						'default'   => 1,
						'min'       => 1,
						'step'      => 1,
						'max'       => 7,
						'display_value' => 'text'
					),
				)
			);

			// Layout
            $this->sections[] = array(
                'title'     => esc_html__('Layout', 'complex'),
                'desc'      => esc_html__('Select page layout: Box or Full Width', 'complex'),
                'icon'      => 'el-icon-align-justify',
                'fields'    => array(
					array(
						'id'       => 'page_layout',
						'type'     => 'select',
						'multi'    => false,
						'title'    => esc_html__('Page Layout', 'complex'),
						'options'  => array(
							'full' => 'Full Width',
							'box' => 'Box'
						),
						'default'  => 'full'
					),
					array(
						'id'        => 'box_layout_width',
						'type'      => 'slider',
						'title'     => esc_html__('Box layout width', 'complex'),
						'desc'      => esc_html__('Box layout width in pixels, default value: 1200', 'complex'),
						"default"   => 1200,
						"min"       => 960,
						"step"      => 1,
						"max"       => 1920,
						'display_value' => 'text'
					),
                ),
            );
			
			//Brand logos
			$this->sections[] = array(
                'title'     => esc_html__('Brand Logos', 'complex'),
                'desc'      => esc_html__('Upload brand logos and links', 'complex'),
                'icon'      => 'el-icon-briefcase',
                'fields'    => array(
					array(
						'id'          => 'brand_logos',
						'type'        => 'slides',
						'title'       => esc_html__('Logos', 'complex'),
						'desc'        => esc_html__('Upload logo image and enter logo link.', 'complex'),
						'placeholder' => array(
							'title'           => esc_html__('Title', 'complex'),
							'description'     => esc_html__('Description', 'complex'),
							'url'             => esc_html__('Link', 'complex'),
						),
					),
                ),
            );
			
			// Portfolio
            $this->sections[] = array(
                'title'     => esc_html__('Portfolio', 'complex'),
                'desc'      => esc_html__('Use this section to select options for portfolio', 'complex'),
                'icon'      => 'el-icon-bookmark',
                'fields'    => array(
					array(
						'id'        => 'portfolio_columns',
						'type'      => 'slider',
						'title'     => esc_html__('Portfolio Columns', 'complex'),
						"default"   => 3,
						"min"       => 2,
						"step"      => 1,
						"max"       => 4,
						'display_value' => 'text'
					),
					array(
						'id'        => 'portfolio_per_page',
						'type'      => 'slider',
						'title'     => esc_html__('Projects per page', 'complex'),
						'desc'      => esc_html__('Amount of projects per page on portfolio page', 'complex'),
						"default"   => 12,
						"min"       => 4,
						"step"      => 1,
						"max"       => 48,
						'display_value' => 'text'
					),
					array(
                        'id'        => 'related_project_title',
                        'type'      => 'text',
                        'title'     => esc_html__('Related projects title', 'complex'),
                        'default'   => 'Related Projects'
                    ),
                ),
            );
			
			// Products
            $this->sections[] = array(
                'title'     => esc_html__('Products', 'complex'),
                'desc'      => esc_html__('Use this section to select options for product', 'complex'),
                'icon'      => 'el-icon-tags',
                'fields'    => array(
					array(
						'id'       => 'sidebarshop_pos',
						'type'     => 'radio',
						'title'    => esc_html__('Sidebar', 'complex'),
						'subtitle'      => esc_html__('Sidebar on archive pages', 'complex'),
						'options'  => array(
							'left' => 'Left',
							'right' => 'Right',
							'' => 'None'
						),
						'default'  => 'left'
					),
					array(
                        'id'        => 'default_view',
                        'type'      => 'select',
                        'title'     => esc_html__('Shop default view', 'complex'),
                        'options'   => array(
							'grid-view' => 'Grid View',
                            'list-view' => 'List View',
                        ),
                        'default'   => 'grid-view'
                    ),
					array(
						'id'        => 'product_per_page',
						'type'      => 'slider',
						'title'     => esc_html__('Products per page', 'complex'),
						'subtitle'      => esc_html__('Amount of products per page on category page', 'complex'),
						"default"   => 12,
						"min"       => 4,
						"step"      => 1,
						"max"       => 48,
						'display_value' => 'text'
					),
					array(
						'id'        => 'product_per_row',
						'type'      => 'slider',
						'title'     => esc_html__('Product columns', 'complex'),
						'subtitle'      => esc_html__('Amount of product columns on category page', 'complex'),
						'desc'      => esc_html__('Only works with: 1, 2, 3, 4, 6', 'complex'),
						"default"   => 3,
						"min"       => 1,
						"step"      => 1,
						"max"       => 6,
						'display_value' => 'text'
					),
					array(
						'id'        => 'product_per_row_fw',
						'type'      => 'slider',
						'title'     => esc_html__('Product columns on full width shop', 'complex'),
						'subtitle'      => esc_html__('Amount of product columns on full width category page', 'complex'),
						'desc'      => esc_html__('Only works with: 1, 2, 3, 4, 6', 'complex'),
						"default"   => 4,
						"min"       => 1,
						"step"      => 1,
						"max"       => 6,
						'display_value' => 'text'
					),
					array(
						'id'       => 'enable_loadmore',
						'type'     => 'radio',
						'title'    => esc_html__('Load more ajax', 'complex'),
						'options'  => array(
							'' => esc_html__('Default pagination', 'complex'),
							'scroll-more' => esc_html__('Scroll to load more', 'complex'),
							'button-more' => esc_html__('Button load more', 'complex')
							),
						'default'  => ''
					),
					array(
						'id'       => 'second_image',
						'type'     => 'switch',
						'title'    => esc_html__('Use secondary product image', 'complex'),
						'desc'      => esc_html__('Show the secondary image when hover on product on list', 'complex'),
						'default'  => true,
					),
					array(
						'id'       => 'showlist_cats',
						'type'     => 'switch',
						'title'    => esc_html__('Show categories', 'complex'),
						'default'  => true,
					),
					array(
						'id'       => 'enable_addcart',
						'type'     => 'switch',
						'title'    => esc_html__('Add to cart button', 'complex'),
						'default'  => true,
					),
					array(
						'id'       => 'enable_compare',
						'type'     => 'switch',
						'title'    => esc_html__('Wishlist button', 'complex'),
						'default'  => true,
					),
					array(
						'id'       => 'enable_wishlist',
						'type'     => 'switch',
						'title'    => esc_html__('Compare button', 'complex'),
						'default'  => true,
					),
					array(
                        'id'        => 'upsells_title',
                        'type'      => 'text',
                        'title'     => esc_html__('Up-Sells title', 'complex'),
                        'default'   => 'Up-Sells'
                    ),
					array(
                        'id'        => 'crosssells_title',
                        'type'      => 'text',
                        'title'     => esc_html__('Cross-Sells title', 'complex'),
                        'default'   => 'Cross-Sells'
                    ),
                ),
            );
			$this->sections[] = array(
				'icon'       => 'el-icon-website',
				'title'      => esc_html__( 'Product page', 'complex' ),
				'subsection' => true,
				'fields'     => array(
					array(
						'id'       => 'sidebarpro_pos',
						'type'     => 'radio',
						'title'    => esc_html__('Sidebar', 'complex'),
						'subtitle'      => esc_html__('Sidebar on product detail page', 'complex'),
						'options'  => array(
							'left' => 'Left',
							'right' => 'Right',
							'' => 'None'
						),
						'default'  => 'none'
					),
					array(
                        'id'        => 'related_title',
                        'type'      => 'text',
                        'title'     => esc_html__('Related products title', 'complex'),
                        'default'   => 'Related Products'
                    ),
					array(
						'id'        => 'related_amount',
						'type'      => 'slider',
						'title'     => esc_html__('Number of related products', 'complex'),
						"default"   => 4,
						"min"       => 1,
						"step"      => 1,
						"max"       => 16,
						'display_value' => 'text'
					),
					array(
                        'id'        => 'upsells_title',
                        'type'      => 'text',
                        'title'     => esc_html__('Up-Sells title', 'complex'),
                        'default'   => 'Up-Sells'
                    ),
				)
			);
			$this->sections[] = array(
				'icon'       => 'el-icon-website',
				'title'      => esc_html__( 'Quick View', 'complex' ),
				'subsection' => true,
				'fields'     => array(
					array(
						'id'       => 'enable_quickview',
						'type'     => 'switch',
						'title'    => esc_html__('Enable', 'complex'),
						'default'  => true,
					),
					array(
                        'id'        => 'detail_link_text',
                        'type'      => 'text',
                        'title'     => esc_html__('View details text', 'complex'),
                        'default'   => 'View details'
                    ),
					array(
                        'id'        => 'quickview_link_text',
                        'type'      => 'text',
                        'title'     => esc_html__('View all features text', 'complex'),
						'desc'      => esc_html__('This is the text on quick view box', 'complex'),
                        'default'   => 'See all features'
                    ),
				)
			);
			// Blog options
            $this->sections[] = array(
                'title'     => esc_html__('Blog', 'complex'),
                'desc'      => esc_html__('Use this section to select options for blog', 'complex'),
                'icon'      => 'el-icon-file',
                'fields'    => array(
					array(
                        'id'        => 'blog_header_text',
                        'type'      => 'text',
                        'title'     => esc_html__('Blog header text', 'complex'),
                        'default'   => ''
                    ),
					array(
						'id'       => 'sidebarblog_pos',
						'type'     => 'radio',
						'title'    => esc_html__('Sidebar', 'complex'),
						'subtitle'      => esc_html__('Sidebar on Blog page', 'complex'),
						'options'  => array(
							'left' => 'Left',
							'right' => 'Right',
							'' => 'None'
						),
						'default'  => 'left'
					),
					array(
                        'id'        => 'blog_column',
                        'type'      => 'select',
                        'title'     => esc_html__('Blog Content Column', 'complex'),
                        'options'   => array(
							12 => 'One Column',
							6 => 'Two Column',
							4 => 'Three Column',
							3 => 'Four Column'
                        ),
                        'default'   => 6
                    ),
					array(
                        'id'        => 'readmore_text',
                        'type'      => 'text',
                        'title'     => esc_html__('Read more text', 'complex'),
                        'default'   => 'Read more'
                    ),
					array(
						'id'        => 'excerpt_length',
						'type'      => 'slider',
						'title'     => esc_html__('Excerpt length on blog page', 'complex'),
						"default"   => 22,
						"min"       => 10,
						"step"      => 2,
						"max"       => 120,
						'display_value' => 'text'
					),
                ),
            );
			
			// Error 404 page
            $this->sections[] = array(
                'title'     => esc_html__('Error 404 Page', 'complex'),
                'desc'      => esc_html__('Error 404 page options', 'complex'),
                'icon'      => 'el-icon-cog',
                'fields'    => array(
					array(
                        'id'        => 'background_error',
                        'type'      => 'background',
                        'output'    => array('body.error404'),
                        'title'     => esc_html__('Error 404 background', 'complex'),
                        'subtitle'  => esc_html__('Upload image or select color.', 'complex'),
						'default'   => array('background-color' => '#fff'),
                    ),
					array(
                        'id'        => '404_color',
                        'type'      => 'color',
                        'output'    => array('body.error404 h1, body.error404 h2, body.error404 p, body.error404 h3, body.error404 h4'),
                        'title'     => esc_html__('Error 404 color', 'complex'),
						'default'   => '#000',
						'validate' => 'color'
                    ),
					array(
						'id'=>'404_content',
						'type' => 'editor',
						'title' => esc_html__('Content', 'complex'), 
						'subtitle'         => esc_html__('HTML tags allowed: a, img, br, em, strong, p, ul, li, heading', 'complex'),
						'default' => '<h1>404</h1><h2>Oops! Page Not Found.</h2><p>It looks like nothing was found at this location. Maybe try one of the links below or a search?</p>',
						'args'   => array(
							'teeny'            => true,
							'textarea_rows'    => 10,
							'media_buttons'	=> true,
						)
					),
                ),
            );
			
			// Custom CSS
            $this->sections[] = array(
                'title'     => esc_html__('Custom CSS', 'complex'),
                'desc'      => esc_html__('Add your Custom CSS code', 'complex'),
                'icon'      => 'el-icon-pencil',
                'fields'    => array(
					array(
						'id'       => 'custom_css',
						'type'     => 'ace_editor',
						'title'    => esc_html__('CSS Code', 'complex'),
						'subtitle' => esc_html__('Paste your CSS code here.', 'complex'),
						'mode'     => 'css',
						'theme'    => 'monokai', //chrome
						'default'  => ""
					),
                ),
            );
			
			// Less Compiler
            $this->sections[] = array(
                'title'     => esc_html__('Less Compiler', 'complex'),
                'desc'      => esc_html__('Turn on this option to apply all theme options. Turn of when you have finished changing theme options and your site is ready.', 'complex'),
                'icon'      => 'el-icon-wrench',
                'fields'    => array(
					array(
                        'id'        => 'enable_less',
                        'type'      => 'switch',
                        'title'     => esc_html__('Enable Less Compiler', 'complex'),
						'default'   => true,
                    ),
                ),
            );
			
            $theme_info  = '<div class="redux-framework-section-desc">';
            $theme_info .= '<p class="redux-framework-theme-data description theme-uri">' . esc_html__('<strong>Theme URL:</strong> ', 'complex') . '<a href="' . esc_url($this->theme->get('ThemeURI')) . '" target="_blank">' . $this->theme->get('ThemeURI') . '</a></p>';
            $theme_info .= '<p class="redux-framework-theme-data description theme-author">' . esc_html__('<strong>Author:</strong> ', 'complex') . $this->theme->get('Author') . '</p>';
            $theme_info .= '<p class="redux-framework-theme-data description theme-version">' . esc_html__('<strong>Version:</strong> ', 'complex') . $this->theme->get('Version') . '</p>';
            $theme_info .= '<p class="redux-framework-theme-data description theme-description">' . $this->theme->get('Description') . '</p>';
            $tabs = $this->theme->get('Tags');
            if (!empty($tabs)) {
                $theme_info .= '<p class="redux-framework-theme-data description theme-tags">' . esc_html__('<strong>Tags:</strong> ', 'complex') . implode(', ', $tabs) . '</p>';
            }
            $theme_info .= '</div>';

            $this->sections[] = array(
                'title'     => esc_html__('Import / Export', 'complex'),
                'desc'      => esc_html__('Import and Export your Redux Framework settings from file, text or URL.', 'complex'),
                'icon'      => 'el-icon-refresh',
                'fields'    => array(
                    array(
                        'id'            => 'opt-import-export',
                        'type'          => 'import_export',
                        'title'         => 'Import Export',
                        'subtitle'      => 'Save and restore your Redux options',
                        'full_width'    => false,
                    ),
                ),
            );

            $this->sections[] = array(
                'icon'      => 'el-icon-info-sign',
                'title'     => esc_html__('Theme Information', 'complex'),
                //'desc'      => esc_html__('<p class="description">This is the Description. Again HTML is allowed</p>', 'complex'),
                'fields'    => array(
                    array(
                        'id'        => 'opt-raw-info',
                        'type'      => 'raw',
                        'content'   => $item_info,
                    )
                ),
            );
        }

        public function setHelpTabs() {

            // Custom page help tabs, displayed using the help API. Tabs are shown in order of definition.
            $this->args['help_tabs'][] = array(
                'id'        => 'redux-help-tab-1',
                'title'     => esc_html__('Theme Information 1', 'complex'),
                'content'   => esc_html__('<p>This is the tab content, HTML is allowed.</p>', 'complex')
            );

            $this->args['help_tabs'][] = array(
                'id'        => 'redux-help-tab-2',
                'title'     => esc_html__('Theme Information 2', 'complex'),
                'content'   => esc_html__('<p>This is the tab content, HTML is allowed.</p>', 'complex')
            );

            // Set the help sidebar
            $this->args['help_sidebar'] = esc_html__('<p>This is the sidebar content, HTML is allowed.</p>', 'complex');
        }

        /**

          All the possible arguments for Redux.
          For full documentation on arguments, please refer to: https://github.com/ReduxFramework/ReduxFramework/wiki/Arguments

         * */
        public function setArguments() {

            $theme = wp_get_theme(); // For use with some settings. Not necessary.

            $this->args = array(
                // TYPICAL -> Change these values as you need/desire
                'opt_name'          => 'complex_opt',            // This is where your data is stored in the database and also becomes your global variable name.
                'display_name'      => $theme->get('Name'),     // Name that appears at the top of your panel
                'display_version'   => $theme->get('Version'),  // Version that appears at the top of your panel
                'menu_type'         => 'menu',                  //Specify if the admin menu should appear or not. Options: menu or submenu (Under appearance only)
                'allow_sub_menu'    => true,                    // Show the sections below the admin menu item or not
                'menu_title'        => esc_html__('Theme Options', 'complex'),
                'page_title'        => esc_html__('Theme Options', 'complex'),
                
                // You will need to generate a Google API key to use this feature.
                // Please visit: https://developers.google.com/fonts/docs/developer_api#Auth
                'google_api_key' => '', // Must be defined to add google fonts to the typography module
                
                'async_typography'  => true,                    // Use a asynchronous font on the front end or font string
                //'disable_google_fonts_link' => true,                    // Disable this in case you want to create your own google fonts loader
                'admin_bar'         => true,                    // Show the panel pages on the admin bar
                'global_variable'   => '',                      // Set a different name for your global variable other than the opt_name
                'dev_mode'          => false,                    // Show the time the page took to load, etc
                'customizer'        => true,                    // Enable basic customizer support
                //'open_expanded'     => true,                    // Allow you to start the panel in an expanded way initially.
                //'disable_save_warn' => true,                    // Disable the save warning when a user changes a field

                // OPTIONAL -> Give you extra features
                'page_priority'     => null,                    // Order where the menu appears in the admin area. If there is any conflict, something will not show. Warning.
                'page_parent'       => 'themes.php',            // For a full list of options, visit: http://codex.wordpress.org/Function_Reference/add_submenu_page#Parameters
                'page_permissions'  => 'manage_options',        // Permissions needed to access the options panel.
                'menu_icon'         => '',                      // Specify a custom URL to an icon
                'last_tab'          => '',                      // Force your panel to always open to a specific tab (by id)
                'page_icon'         => 'icon-themes',           // Icon displayed in the admin panel next to your menu_title
                'page_slug'         => '_options',              // Page slug used to denote the panel
                'save_defaults'     => true,                    // On load save the defaults to DB before user clicks save or not
                'default_show'      => false,                   // If true, shows the default value next to each field that is not the default value.
                'default_mark'      => '',                      // What to print by the field's title if the value shown is default. Suggested: *
                'show_import_export' => true,                   // Shows the Import/Export panel when not used as a field.
                
                // CAREFUL -> These options are for advanced use only
                'transient_time'    => 60 * MINUTE_IN_SECONDS,
                'output'            => true,                    // Global shut-off for dynamic CSS output by the framework. Will also disable google fonts output
                'output_tag'        => true,                    // Allows dynamic CSS to be generated for customizer and google fonts, but stops the dynamic CSS from going to the head
                // 'footer_credit'     => '',                   // Disable the footer credit of Redux. Please leave if you can help it.
                
                // FUTURE -> Not in use yet, but reserved or partially implemented. Use at your own risk.
                'database'              => '', // possible: options, theme_mods, theme_mods_expanded, transient. Not fully functional, warning!
                'system_info'           => false, // REMOVE

                // HINTS
                'hints' => array(
                    'icon'          => 'icon-question-sign',
                    'icon_position' => 'right',
                    'icon_color'    => 'lightgray',
                    'icon_size'     => 'normal',
                    'tip_style'     => array(
                        'color'         => 'light',
                        'shadow'        => true,
                        'rounded'       => false,
                        'style'         => '',
                    ),
                    'tip_position'  => array(
                        'my' => 'top left',
                        'at' => 'bottom right',
                    ),
                    'tip_effect'    => array(
                        'show'          => array(
                            'effect'        => 'slide',
                            'duration'      => '500',
                            'event'         => 'mouseover',
                        ),
                        'hide'      => array(
                            'effect'    => 'slide',
                            'duration'  => '500',
                            'event'     => 'click mouseleave',
                        ),
                    ),
                )
            );

            // SOCIAL ICONS -> Setup custom links in the footer for quick links in your panel footer icons.
            $this->args['share_icons'][] = array(
                'url'   => 'https://github.com/ReduxFramework/ReduxFramework',
                'title' => 'Visit us on GitHub',
                'icon'  => 'el-icon-github'
                //'img'   => '', // You can use icon OR img. IMG needs to be a full URL.
            );
            $this->args['share_icons'][] = array(
                'url'   => 'https://www.facebook.com/pages/Redux-Framework/243141545850368',
                'title' => 'Like us on Facebook',
                'icon'  => 'el-icon-facebook'
            );
            $this->args['share_icons'][] = array(
                'url'   => 'http://twitter.com/reduxframework',
                'title' => 'Follow us on Twitter',
                'icon'  => 'el-icon-twitter'
            );
            $this->args['share_icons'][] = array(
                'url'   => 'http://www.linkedin.com/company/redux-framework',
                'title' => 'Find us on LinkedIn',
                'icon'  => 'el-icon-linkedin'
            );

            // Panel Intro text -> before the form
            if (!isset($this->args['global_variable']) || $this->args['global_variable'] !== false) {
                if (!empty($this->args['global_variable'])) {
                    $v = $this->args['global_variable'];
                } else {
                    $v = str_replace('-', '_', $this->args['opt_name']);
                }
                //$this->args['intro_text'] = sprintf(esc_html__('<p>Did you know that Redux sets a global variable for you? To access any of your saved options from within your code you can use your global variable: <strong>$%1$s</strong></p>', 'complex'), $v);
            } else {
                //$this->args['intro_text'] = esc_html__('<p>This text is displayed above the options panel. It isn\'t required, but more info is always better! The intro_text field accepts all HTML.</p>', 'complex');
            }

            // Add content after the form.
            //$this->args['footer_text'] = esc_html__('<p>This text is displayed below the options panel. It isn\'t required, but more info is always better! The footer_text field accepts all HTML.</p>', 'complex');
        }

    }   
    global $reduxConfig;
    $reduxConfig = new Complex_Theme_Config();
}

/**
  Custom function for the callback referenced above
 */
if (!function_exists('redux_my_custom_field')):
    function redux_my_custom_field($field, $value) {
        print_r($field);
        echo '<br/>';
        print_r($value);
    }
endif;

/**
  Custom function for the callback validation referenced above
 * */
if (!function_exists('redux_validate_callback_function')):
    function redux_validate_callback_function($field, $value, $existing_value) {
        $error = false;
        $value = 'just testing';

        /*
          do your validation

          if(something) {
            $value = $value;
          } elseif(something else) {
            $error = true;
            $value = $existing_value;
            $field['msg'] = 'your custom error message';
          }
         */

        $return['value'] = $value;
        if ($error == true) {
            $return['error'] = $field;
        }
        return $return;
    }
endif;
