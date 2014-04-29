<?php
/**
 * QuickLaunch Theme
 * Functions File
 *
 * @package QuickLaunch
 * @version 1.1
 * @since 1.0
 * @author brux <brux.romuar@gmail.com>
 */

if (!function_exists('write_log')) {
    function write_log ( $log )  {
        if ( true === WP_DEBUG ) {
            if ( is_array( $log ) || is_object( $log ) ) {
                error_log( print_r( $log, true ) );
            } else {
                error_log( $log );
            }
        }
    }
}

require_once TEMPLATEPATH . '/includes/QL_JSONResponse.php';
require_once TEMPLATEPATH . '/includes/QL_Email.php';
require_once TEMPLATEPATH . '/includes/admin.php';
require_once TEMPLATEPATH . '/includes/personalization.php';
require_once TEMPLATEPATH . '/includes/campaign.php';
require_once TEMPLATEPATH . '/includes/custom-controls.php';

// include mail chimp
require_once TEMPLATEPATH.'/plugins/mailchimp-widget/mailchimp-widget.php';


// Defaults
define('QL_BACKGROUND_COLOR', '#FFFFFF');
define('QL_TITLE_TAGLINE_FONT', 'Times New Roman');
define('QL_TITLE_TAGLINE_TITLE_COLOR', '#000000');

define('QL_TITLE_TAGLINE_TITLE_COLORINFO', '#000000');



define('QL_LAYOUT_PADDING', '30');
define('QL_LAYOUT_ROUNDNESS', '25');
define('QL_LAYOUT_BOX_SHADOW', '10');
define('QL_LAYOUT_POSITION', 'center');
define('QL_CONTENT_POSTITLEINFO', 'center');
define('QL_TITLE_TAGLINE_FONTINFO', 'Times New Roman');
define('QL_CONTENT_POSITIONEMAIL', 'bottom');
define('QL_CONTENT_EMAIL_TIPTEXT', 'Signup for email newsletters');


define('QL_TITLE_TAGLINE_TEXTALIGN', 'center');
define('QL_CONTENT_POSTITLE', 'center');

define('QL_CONTENT_POSITIONTEXT', 'center');

define('QL_FOOTER_CONTENT', 'Copyright &copy; '.date('Y').' '.get_bloginfo());
define('QL_WIDGETS_EMAIL_SUBMIT_COLOR', 'gray');
define('QL_CONTENT_CONTENT', 'Lorem ipsum dolor sit amet, consetetur sadipscing elitr, sed diam nonumy eirmod tempor invidunt ut labore et dolore magna aliquyam erat, sed diam voluptua. At vero eos et accusam et justo duo dolores et ea rebum. Stet clita kasd gubergren, no sea takimata sanctus est Lorem ipsum dolor sit amet. Lorem ipsum dolor sit amet, consetetur sadipscing elitr, sed diam nonumy eirmod tempor invidunt ut labore et dolore magna aliquyam erat, sed diam voluptua. At vero eos et accusam et justo duo dolores et ea rebum. Stet clita kasd gubergren, no sea takimata sanctus est Lorem ipsum dolor sit amet.');
define('QL_CONTENT_OPACITY', '100');
define('QL_CONTENT_COLOR', '#000000');
define('QL_CONTENT_CONTENTFONT', 'Times New Roman');
define('QL_CONTENT_CONTENTH3FONT', 'Abel');
define('QL_CONTENT_H3_FONTSIZE', 14);
define('QL_CONTENT_H3_COLOR', '#000000');

define('QL_TITLE_BOTTOM_MARGIN', 0);
define('QL_HEADER_BOTTOM_MARGIN', 18);
define('QL_CONTENT_LINE_SPACING', 20);

// Setup the database
$db_setup = <<<DB_SETUP
CREATE TABLE IF NOT EXISTS {$wpdb->prefix}quicklaunch_emails (
  id bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  email varchar(255) CHARACTER SET utf8 NOT NULL,
  ip varchar(15) CHARACTER SET utf8 NOT NULL,
  registered_on datetime NOT NULL,
  PRIMARY KEY (id),
  UNIQUE KEY email (email)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 ;
DB_SETUP;
$wpdb->query($db_setup);

// Default page content
$default_page_content = <<<DEF_PAGE_CONTENT
	<p>Lorem ipsum dolor sit amet, consectetuer adipiscing elit, sed diam nonummy nibh euismod tincidunt ut laoreet dolore magna aliquam erat volutpat. Ut wisi enim ad minim veniam, quis nostrud exerci tation ullamcorper suscipit lobortis nisl ut aliquip ex ea commodo consequat.</p>
DEF_PAGE_CONTENT;
add_option('ql-page-content', $default_page_content);

add_option('ql-content-padding', '20');
add_option('ql-btn-color', 'gray');

// Register our nav menus
//register_nav_menu('bottom', 'Bottom Menu');

/**
 * Prepares the environment for QuickLaunch.
 *
 * @return void
 * @since 1.0
 */
function ql_init()
{
	session_start();
	// If we are Personalizing the theme, set a flag.
	if ( isset($_GET['personalize']) && current_user_can('edit_theme_options') )
	{
		define('QL_PERSONALIZING', true);
	}

}
add_action('init', 'ql_init');

/**
 * Registers and enqueues the Javascript and CSS files needed by the theme.
 *
 * @return void
 * @since 1.0
 */
function ql_add_scripts()
{

	$template_url = get_bloginfo('template_url');
	
	wp_register_style('eye-colorpicker', $template_url .'/js/colorpicker/colorpicker.css');
	wp_register_style('jq-ui', $template_url .'/css/jquery/jquery.ui.css');

	wp_register_script('eye-colorpicker', $template_url .'/js/colorpicker/jquery.colorpicker.js', array('jquery'));
	wp_register_script('nouislider', $template_url .'/js/jquery.nouislider.js', array('jquery'));
	
	wp_register_script('ql-scripts', $template_url . '/js/scripts.js', array('jquery'));
	wp_register_script('ql-admin', $template_url . '/js/admin.js', array('jquery', 'eye-colorpicker', 'plupload-html5', 'plupload-flash', 'nouislider'));
	
	if ( ql_is_personalizing() )
	{
	
		wp_enqueue_style('eye-colorpicker');
		wp_enqueue_style('jq-ui');
	
		$vars = array(
			'ajaxurl' => admin_url('admin-ajax.php'),
			'siteurl' => get_bloginfo('url'),
			'upload_nonce' => wp_create_nonce('quicklaunch-upload-file'),
			'save_nonce' => wp_create_nonce('quicklaunch-save-personalization')
		);
		wp_localize_script('ql-admin', 'QLAdmin', $vars);
		wp_enqueue_script('ql-admin');
		wp_enqueue_script('jquery-ui-dialog');
		
	}
	else
	{
		
		$vars = array(
			'ajaxurl' => admin_url('admin-ajax.php'),
			'reg_email_nonce' => wp_create_nonce('quicklaunch-register-email')
		);
		wp_localize_script('ql-scripts', 'QL', $vars);
		wp_enqueue_script('ql-scripts');
		
	}
	
	if ( is_admin() && basename($_SERVER['PHP_SELF']) == 'admin.php' && $_GET['page'] == 'ql-email-list' )
	{
		wp_enqueue_script('common');
	}

}
add_action('wp_head', 'ql_add_scripts');
add_action('admin_head', 'ql_add_scripts');

/**
 * Fallback function for the bottom menu. Prints an empty Nav container.
 *
 * @return void
 * @since 1.0
 */
function ql_bottom_nav()
{
?>
	<nav id="footer-nav"></nav>
<?php
}

/**
 * Adds the "personalize" button on the admin bar.
 *
 * @return void
 * @since 1.1
 */
function ql_admin_bar()
{
	global $post;
	if ( current_user_can('edit_theme_options') && ! ql_is_personalizing() )
	{
		
		global $wp_admin_bar;
		
		if ( ! is_super_admin() || ! is_admin_bar_showing() )
				return;
		
		$wp_admin_bar->add_menu(array(
			'id' => 'ql-personalize',
			'title' => 'Customize',
			'href' => get_bloginfo('url') . '/wp-admin/customize.php?page_edit_id='.((is_front_page())?0:$post->ID),
		));
		
	}

}
add_action('admin_bar_menu', 'ql_admin_bar', 75);

/**
 * Automatically adds links to valid URLs in our post content.
 *
 * @param string $content the post content
 * @return string
 * @since 1.1
 * @credit http://www.couchcode.com/php/auto-link-function/
 */
function ql_auto_link($content)
{
	if(!ql_is_personalizing()){
		$pattern = "/(((http[s]?:\/\/)|(www\.))(([a-z][-a-z0-9]+\.)?[a-z][-a-z0-9]+\.[a-z]+(\.[a-z]{2,2})?)\/?[a-z0-9._\/~#&=;%+?-]+[a-z0-9\/#=?]{1,1})/is";
		$content = preg_replace($pattern, " <a href='$1'>$1</a>", $content);
		$content = preg_replace("/href='www/", "href='http://www", $content);
		return $content;
	}else
		return $content;

}
//add_filter('the_content', 'ql_auto_link');

/**
 * Sidebar widgets
 */
function ql_widgets_init(){
	register_sidebar( array(
		'name' => 'Top Sidebar',
		'id' => 'sidebar-top',
		'before_widget' => '<aside id="%1$s" class="widget %2$s">',
		'after_widget' => '</aside>',
		'before_title' => '<h3 class="widget-title">',
		'after_title' => '</h3>',
	) );
}
add_action( 'widgets_init', 'ql_widgets_init' );

/**
 * Get Youtube video id from a youtube url
 * @param string Youtube video url
 * @return string video id
 */
function get_youtube_video_id($url){
	$params = parse_str(parse_url ($url, PHP_URL_QUERY));
	// get only v param
	return $v;
}

/**
 * Set Thank you message
 */
function ql_admin_footer(){
	if(!get_option('ql-active')){
		// add option
		add_option('ql-active', 'active');
		
		// set activation message
		echo '<div style="padding:15px;" class="updated">Thanks for installing Quicklaunch. Start <a href="'.get_bloginfo('url') . '?personalize'.'">Editing</a> your site.</div>';
	}
	
	wp_enqueue_script('jQuery');
	
}
add_action('admin_footer', 'ql_admin_footer');




// include jquery ui
function ql_admin_scripts(){
	
    wp_enqueue_script('jquery-ui-core');
	wp_enqueue_script('jquery-ui');
	wp_enqueue_script('jquery-ui-slider');

}
add_action('admin_enqueue_scripts', 'ql_admin_scripts');

// include jquery ui
function ql_ffscripts(){
	wp_enqueue_script('jquery-ui-core');
	wp_enqueue_script('jquery-ui');
	wp_enqueue_script('jquery-ui-slider');
}
add_action('enqueue_scripts', 'ql_ffscripts');




/**
 * Theme cuszomizations
 * 
 * @since 1.7 for wordpress 3.4+
 */

function ql_customize_register( $wp_customize ){
	
	/**
	 * Text area Controller for theme customizer
	 * 
	 * @since 1.8
	 * @author Gihan <gihanshp@gmail.com>
	 */
	class Textarea_Control extends WP_Customize_Control{
		public $type = 'textarea';
		
		public function render_content(){
			?>
			<label>
				<span class="customize-control-title"><?php echo esc_html($this->label); ?></span>
				<textarea rows="25" style="width:100%;" <?php echo $this->link(); ?>><?php echo esc_textarea($this->value()); ?></textarea>
			</label>
			<p><i>Tip: You can use &lt;p&gt; and &lt;/p&gt; to wrap paragraphs precisely</i></p>
			<?php
		}
	}

	
	/**
	 * Slider Controller for theme customizer
	 * 
	 * @since 1.8
	 * @author Gihan <gihanshp@gmail.com>
	 */
	class Slider_Control extends WP_Customize_Control{
		public $type = 'slider';
		
		public function render_content(){
			$rand = md5(time());
			?>
			<label>
				<span class="customize-control-title"><?php esc_html($this->label); ?></span>
				<p><?php echo $this->value; ?></p>
				<input type="hidden" value="<?php echo $this->value; ?>" />
			</label>
			<div id="slider-<?php echo $rand; ?>"></div>
			<script type="text/javascript">
				jQuery(function(){
					//jQuery('#slider-<?php echo $rand; ?>').slider();
				});
			</script>
			<?php
		}
	}
	
	$myglobal_post_id="";
	if(isset($_REQUEST["page_edit_id"])):
		$_SESSION["postid"]=$_REQUEST["page_edit_id"];
	endif;
	if(isset($_SESSION["postid"]))
		$myglobal_post_id=$_SESSION["postid"];

	// social links
	$wp_customize->add_section( 'ql_social', array(
		'title'			=> 'Social Networks',
		'description'	=> 'Social networks links',
		'priority'		=> 38,
	));
	
	// Twitter
	$wp_customize->add_setting( 'ql_social_'.$myglobal_post_id.'[twitter]', array(
		'default'		=> '',
		'type'			=> 'option',
		'capability'	=> 'edit_theme_options',
		'transport'		=> 'postMessage',
	));
	$wp_customize->add_control( 'ql_social_twitter', array(
		'label'			=> 'Twitter',
		'section'		=> 'ql_social',
		'settings'		=> 'ql_social_'.$myglobal_post_id.'[twitter]',
		'type'			=> 'text',
	));
	
	// facebook
	$wp_customize->add_setting( 'ql_social_'.$myglobal_post_id.'[facebook]', array(
		'default'		=> '',
		'type'			=> 'option',
		'capability'	=> 'edit_theme_options',
		'transport'		=> 'postMessage',
	));
	$wp_customize->add_control( 'ql_social_facebook', array(
		'label'			=> 'Facebook',
		'section'		=> 'ql_social',
		'settings'		=> 'ql_social_'.$myglobal_post_id.'[facebook]',
		'type'			=> 'text',
	));
	
	// LinkedIn
	$wp_customize->add_setting( 'ql_social_'.$myglobal_post_id.'[linkedin]', array(
		'default'		=> '',
		'type'			=> 'option',
		'capability'	=> 'edit_theme_options',
		'transport'		=> 'postMessage',
	));
	$wp_customize->add_control( 'ql_social_linkedin', array(
		'label'			=> 'LinkedIn',
		'section'		=> 'ql_social',
		'settings'		=> 'ql_social_'.$myglobal_post_id.'[linkedin]',
		'type'			=> 'text',
	));
	
	// Google plus
	$wp_customize->add_setting( 'ql_social_'.$myglobal_post_id.'[googleplus]', array(
		'default'		=> '',
		'type'			=> 'option',
		'capability'	=> 'edit_theme_options',
		'transport'		=> 'postMessage',
	));
	$wp_customize->add_control( 'ql_social_googleplus', array(
		'label'			=> 'Google +',
		'section'		=> 'ql_social',
		'settings'		=> 'ql_social_'.$myglobal_post_id.'[googleplus]',
		'type'			=> 'text',
	));
	
	// youtube
	$wp_customize->add_setting( 'ql_social_'.$myglobal_post_id.'[youtube]', array(
		'default'		=> '',
		'type'			=> 'option',
		'capability'	=> 'edit_theme_options',
		'transport'		=> 'postMessage',
	));
	$wp_customize->add_control( 'ql_social_youtube', array(
		'label'			=> 'Youtube',
		'section'		=> 'ql_social',
		'settings'		=> 'ql_social_'.$myglobal_post_id.'[youtube]',
		'type'			=> 'text',
	));
	
	
	/*
	 * Layout settings
	 */
	$wp_customize->add_section( 'ql_layout', array(
		'title'			=> 'Layout',
		'description'	=> 'Site layout settings',
		'priority'		=> 37,
	));
	
	// Position
	$wp_customize->add_setting( 'ql_layout_'.$myglobal_post_id.'[position]', array(
		'default'		=> QL_LAYOUT_POSITION,
		'type'			=> 'option',
		'capability'	=> 'edit_theme_options',
		'transport'		=> 'postMessage',
	));
	$wp_customize->add_control( 'ql_position', array(
		'label'			=> 'Position',
		'section'		=> 'ql_layout',
		'settings'		=> 'ql_layout_'.$myglobal_post_id.'[position]',
		'type'			=> 'radio',
		'choices'		=> array(
			'left'=>'Left',
			'center'=>'Center',
			'right'=>'Right',
		),
	));
	
	// Padding
	$wp_customize->add_setting( 'ql_layout_'.$myglobal_post_id.'[padding]', array(
		'default'		=> QL_LAYOUT_PADDING,
		'type'			=> 'option',
		'capability'	=> 'edit_theme_options',
		'transport'		=> 'postMessage',
	));
	$wp_customize->add_control( 'ql_padding', array(
		'label'			=> 'Padding',
		'section'		=> 'ql_layout',
		'settings'		=> 'ql_layout_'.$myglobal_post_id.'[padding]',
	));
	
	// Rounded box background
	$wp_customize->add_setting( 'ql_layout_'.$myglobal_post_id.'[roundness]', array(
		'default'		=> QL_LAYOUT_ROUNDNESS,
		'type'			=> 'option',
		'capability'	=> 'edit_theme_options',
		'transport'		=> 'postMessage',
	));
	$wp_customize->add_control( 'ql_roundness', array(
		'label'			=> 'Corner Roundness',
		'section'		=> 'ql_layout',
		'settings'		=> 'ql_layout_'.$myglobal_post_id.'[roundness]',
		'type'			=> 'text',
	));
	
	// Box shadow
	$wp_customize->add_setting( 'ql_layout_'.$myglobal_post_id.'[boxShadow]', array(
		'default'		=> QL_LAYOUT_BOX_SHADOW,
		'type'			=> 'option',
		'capability'	=> 'edit_theme_options',
		'transport'		=> 'postMessage',
	));
	$wp_customize->add_control( 'ql_boxShadow', array(
		'label'			=> 'Box Shadow',
		'section'		=> 'ql_layout',
		'settings'		=> 'ql_layout_'.$myglobal_post_id.'[boxShadow]',
		'type'			=> 'text',
	));
	
	
	/**
	 * Additional title controls
	 */


	
	// Position
	$wp_customize->add_setting( 'ql_title_tagline_'.$myglobal_post_id.'[postitleinfo]', array(
		'default'		=> QL_CONTENT_POSTITLEINFO,
		'type'			=> 'option',
		'capability'	=> 'edit_theme_options',
		'transport'		=> 'postMessage',
	));
	$wp_customize->add_control( 'ql_title_tagline_'.$myglobal_post_id.'[postitleinfo]', array(
		'label'			=> 'Position Tagline',
		'section'		=> 'title_tagline',
		'settings'		=> 'ql_title_tagline_'.$myglobal_post_id.'[postitleinfo]',
		'type'			=> 'radio',
		'choices'		=> array(
			'left'=>'Left',
			'center'=>'Center',
			'right'=>'Right',
                        'justify'=>'justify',
		),
		'priority'		=> '12'
	));

	 // Position
	$wp_customize->add_setting( 'ql_title_tagline_'.$myglobal_post_id.'[postitle]', array(
		'default'		=> QL_CONTENT_POSTITLE,
		'type'			=> 'option',
		'transport'		=> 'postMessage',
	));
	$wp_customize->add_control( 'ql_title_tagline_'.$myglobal_post_id.'[postitle]', array(
		'label'			=> 'Position Title',
		'section'		=> 'title_tagline',
		'settings'		=> 'ql_title_tagline_'.$myglobal_post_id.'[postitle]',
		'type'			=> 'radio',
		'choices'		=> array(
			'left'=>'Left',
			'center'=>'Center',
			'right'=>'Right',
                        'justify'=>'justify',
		),
		'priority'		=> '11'
	));



		// Heading font family
	$wp_customize->add_setting( 'ql_title_tagline_'.$myglobal_post_id.'[font]', array(
		'default'		=> 'QL_TITLE_TAGLINE_FONT',
		'type'			=> 'option',
		'capability'	=> 'edit_theme_options',
	));
	$wp_customize->add_control( 'ql_title_tagline_'.$myglobal_post_id.'[font]', array(
		'label'			=> 'Title Font',
		'section'		=> 'title_tagline',
		'settings'		=> 'ql_title_tagline_'.$myglobal_post_id.'[font]',
		'type'			=> 'select',
		'choices'		=> array(
			''			=> '-None-',
			'Abel'			=> 'Abel',
			'Abril Fatface'		=> 'Abril Fatface',
			'Actor'			=> 'Actor',
			'Aldrich'		=> 'Aldrich',
			'Alegreya SC'		=> 'Alegreya SC',
			'Alice'			=> 'Alice',
			'Anaheim'		=> 'Anaheim',
			'Asul'			=> 'Asul',
			'BenchNine'		=> 'BenchNine',
			'Bigelow Rules'		=> 'Bigelow Rules',
			'Bilbo Swash Caps'	=> 'Bilbo Swash Caps',
			'Bubbler One'		=> 'Bubbler One',
			'Cabin'			=> 'Cabin',
			'Carrois Gothic'	=> 'Carrois Gothic',
			'Chela One'		=> 'Chela One',
			'Cherry Cream Soda'	=> 'Cherry Cream Soda',
			'Coda'			=> 'Coda',
			'Cousine'		=> 'Cousine',
			'Ceviche One'		=> 'Ceviche One',
			'Chewy'			=> 'Chewy',
			'Creepster'		=> 'Creepster',
			'Crushed'		=> 'Crushed',
			'Droid Sans'		=> 'Droid Sans',
			'Droid Serif'		=> 'Droid Serif',
			'Droid Sans Mono'	=> 'Droid Sans Mono',
			'Eagle Lake'		=> 'Eagle Lake',
			'Electrolize'		=> 'Electrolize',
                        'Faster One'		=> 'Faster One',
			'Fenix'			=> 'Fenix',
			'Flavors'		=> 'Flavors',
			'Francois One'		=> 'Francois One',
			'Finger Paint'		=> 'Finger Paint',
			'Freckle Face'		=> 'Freckle Face',
			'Fredoka One'		=> 'Fredoka One',
			'Geo'			=> 'Geo',
			'Germania One'		=> 'Germania One',
			'Goblin One'		=> 'Goblin One',
			'Gilda Display'		=> 'Gilda Display',
			'Give You Glory'	=> 'Give You Glory',
			'Glass Antiqua'		=> 'Glass Antiqua',
			'Happy Monkey'		=> 'Happy Monkey',
			'Hammersmith One'	=> 'Hammersmith One',
			'Hanalei'		=> 'Hanalei',
			'Holtwood One SC'	=> 'Holtwood One SC',
			'IM Fell Great Primer SC'	=> 'IM Fell Great Primer SC',
			'Inika'			=> 'Inika',
			'Istok Web'		=> 'Istok Web',
			'Josefin Sans'		=> 'Josefin Sans',
			'Josefin Slab'		=> 'Josefin Slab',
			'Judson'		=> 'Judson',
			'Maiden Orange'		=> 'Maiden Orange',
			'Marck Script'		=> 'Marck Script',
			'Medula One'		=> 'Medula One',
			'Merriweather Sans'	=> 'Merriweather Sans',
			'Merienda One'		=> 'Merienda One',
			'Metrophobic'		=> 'Metrophobic',
			'Montserrat Alternates'	=> 'Montserrat Alternates',
			'Montserrat Subrayada'	=> 'Montserrat Subrayada',
                        'Mouse Memoirs'		=> 'Mouse Memoirs',
			'News Cycle'		=> 'News Cycle',
			'New Rocker'		=> 'New Rocker',
			'Nothing You Could Do'	=> 'Nothing You Could Do',
			'Kavoon'		=> 'Kavoon',
			'Kenia'			=> 'Kenia',
			'Knewave'		=> 'Knewave',
                        'Lato'			=> 'Lato',
			'Limelight'		=> 'Limelight',
			'Londrina Sketch'	=> 'Londrina Sketch',
			'Luckiest Guy'		=> 'Luckiest Guy',
			'Oleo Script'		=> 'Oleo Script',
			'Oswald'		=> 'Oswald',
			'Ovo'			=> 'Ovo',
			'Oxygen'		=> 'Oxygen',
			'Pathway Gothic One'	=> 'Pathway Gothic One',
			'Poller One'		=> 'Poller One',
			'Poly'			=> 'Poly',
			'Port Lligat Slab'	=> 'Port Lligat Slab',
			'Port Lligat Sans'	=> 'Port Lligat Sans',
			'Playball'		=> 'Playball',
			'Playfair Display'	=> 'Playfair Display',
			'Quattrocento'		=> 'Quattrocento',
			'Quintessential'	=> 'Quintessential',
			'Qwigley'		=> 'Qwigley',
			'Rancho'		=> 'Rancho',
			'Revalia'		=> 'Revalia',
                        'Roboto'		=> 'Roboto',
			'Ropa Sans'		=> 'Ropa Sans',
			'Rosario'		=> 'Rosario',
			'Rouge Script'		=> 'Rouge Script',
			'Risque'		=> 'Risque',
			'Rufina'		=> 'Rufina',
			'Tangerine'		=> 'Tangerine',
			'Trochut'		=> 'Trochut',
                        'Trykker'		=> 'Trykker',
                        'The Girl Next Door'	=> 'The Girl Next Door',
			'Shadows Into Light'	=> 'Shadows Into Light',
			'Scada'			=> 'Scada',
			'Sunshiney'		=> 'Sunshiney',
			'Ubuntu'		=> 'Ubuntu',
			'Underdog'		=> 'Underdog',
			'UnifrakturMaguntia'	=> 'UnifrakturMaguntia',
			'Unkempt'		=> 'Unkempt',
			'UnifrakturMaguntia'	=> 'UnifrakturMaguntia',
			'Wallpoet'		=> 'Wallpoet',
			'Varela'		=> 'Varela',
			'Vollkorn'		=> 'Vollkorn',
                        'Yeseva One'		=> 'Yeseva One',
			),
		'priority'		=> '13'
	));
	
// Heading font family
	$wp_customize->add_setting( 'ql_title_tagline_'.$myglobal_post_id.'[fontinfo]', array(
		'default'		=> 'QL_TITLE_TAGLINE_FONTINFO',
		'type'			=> 'option',
		'capability'	=> 'edit_theme_options',
	));
	$wp_customize->add_control( 'ql_title_tagline_'.$myglobal_post_id.'[fontinfo]', array(
		'label'			=> 'Tagline Font',
		'section'		=> 'title_tagline',
		'settings'		=> 'ql_title_tagline_'.$myglobal_post_id.'[fontinfo]',
		'type'			=> 'select',
		'choices'		=> array(
			''			=> '-None-',
			'Abel'			=> 'Abel',
			'Abril Fatface'		=> 'Abril Fatface',
			'Actor'			=> 'Actor',
			'Aldrich'		=> 'Aldrich',
			'Alegreya SC'		=> 'Alegreya SC',
			'Alice'			=> 'Alice',
			'Anaheim'		=> 'Anaheim',
			'Asul'			=> 'Asul',
			'BenchNine'		=> 'BenchNine',
			'Bigelow Rules'		=> 'Bigelow Rules',
			'Bilbo Swash Caps'	=> 'Bilbo Swash Caps',
			'Bubbler One'		=> 'Bubbler One',
			'Cabin'			=> 'Cabin',
			'Carrois Gothic'	=> 'Carrois Gothic',
			'Chela One'		=> 'Chela One',
			'Cherry Cream Soda'	=> 'Cherry Cream Soda',
			'Coda'			=> 'Coda',
			'Cousine'		=> 'Cousine',
			'Ceviche One'		=> 'Ceviche One',
			'Chewy'			=> 'Chewy',
			'Creepster'		=> 'Creepster',
			'Crushed'		=> 'Crushed',
			'Droid Sans'		=> 'Droid Sans',
			'Droid Serif'		=> 'Droid Serif',
			'Droid Sans Mono'	=> 'Droid Sans Mono',
			'Eagle Lake'		=> 'Eagle Lake',
			'Electrolize'		=> 'Electrolize',
                        'Faster One'		=> 'Faster One',
			'Fenix'			=> 'Fenix',
			'Flavors'		=> 'Flavors',
			'Francois One'		=> 'Francois One',
			'Finger Paint'		=> 'Finger Paint',
			'Freckle Face'		=> 'Freckle Face',
			'Fredoka One'		=> 'Fredoka One',
			'Geo'			=> 'Geo',
			'Germania One'		=> 'Germania One',
			'Goblin One'		=> 'Goblin One',
			'Gilda Display'		=> 'Gilda Display',
			'Give You Glory'	=> 'Give You Glory',
			'Glass Antiqua'		=> 'Glass Antiqua',
			'Happy Monkey'		=> 'Happy Monkey',
			'Hammersmith One'	=> 'Hammersmith One',
			'Hanalei'		=> 'Hanalei',
			'Holtwood One SC'	=> 'Holtwood One SC',
			'IM Fell Great Primer SC'	=> 'IM Fell Great Primer SC',
			'Inika'			=> 'Inika',
			'Istok Web'		=> 'Istok Web',
			'Josefin Sans'		=> 'Josefin Sans',
			'Josefin Slab'		=> 'Josefin Slab',
			'Judson'		=> 'Judson',
			'Maiden Orange'		=> 'Maiden Orange',
			'Marck Script'		=> 'Marck Script',
			'Medula One'		=> 'Medula One',
			'Merriweather Sans'	=> 'Merriweather Sans',
			'Merienda One'		=> 'Merienda One',
			'Metrophobic'		=> 'Metrophobic',
			'Montserrat Alternates'	=> 'Montserrat Alternates',
			'Montserrat Subrayada'	=> 'Montserrat Subrayada',
                        'Mouse Memoirs'		=> 'Mouse Memoirs',
			'News Cycle'		=> 'News Cycle',
			'New Rocker'		=> 'New Rocker',
			'Nothing You Could Do'	=> 'Nothing You Could Do',
			'Kavoon'		=> 'Kavoon',
			'Kenia'			=> 'Kenia',
			'Knewave'		=> 'Knewave',
                        'Lato'			=> 'Lato',
			'Limelight'		=> 'Limelight',
			'Londrina Sketch'	=> 'Londrina Sketch',
			'Luckiest Guy'		=> 'Luckiest Guy',
			'Oleo Script'		=> 'Oleo Script',
			'Oswald'		=> 'Oswald',
			'Ovo'			=> 'Ovo',
			'Oxygen'		=> 'Oxygen',
			'Pathway Gothic One'	=> 'Pathway Gothic One',
			'Poller One'		=> 'Poller One',
			'Poly'			=> 'Poly',
			'Port Lligat Slab'	=> 'Port Lligat Slab',
			'Port Lligat Sans'	=> 'Port Lligat Sans',
			'Playball'		=> 'Playball',
			'Playfair Display'	=> 'Playfair Display',
			'Quattrocento'		=> 'Quattrocento',
			'Quintessential'	=> 'Quintessential',
			'Qwigley'		=> 'Qwigley',
			'Rancho'		=> 'Rancho',
			'Revalia'		=> 'Revalia',
                        'Roboto'		=> 'Roboto',
			'Ropa Sans'		=> 'Ropa Sans',
			'Rosario'		=> 'Rosario',
			'Rouge Script'		=> 'Rouge Script',
			'Risque'		=> 'Risque',
			'Rufina'		=> 'Rufina',
			'Tangerine'		=> 'Tangerine',
			'Trochut'		=> 'Trochut',
                        'Trykker'		=> 'Trykker',
                        'The Girl Next Door'	=> 'The Girl Next Door',
			'Shadows Into Light'	=> 'Shadows Into Light',
			'Scada'			=> 'Scada',
			'Sunshiney'		=> 'Sunshiney',
			'Ubuntu'		=> 'Ubuntu',
			'Underdog'		=> 'Underdog',
			'UnifrakturMaguntia'	=> 'UnifrakturMaguntia',
			'Unkempt'		=> 'Unkempt',
			'UnifrakturMaguntia'	=> 'UnifrakturMaguntia',
			'Wallpoet'		=> 'Wallpoet',
			'Varela'		=> 'Varela',
			'Vollkorn'		=> 'Vollkorn',
                        'Yeseva One'		=> 'Yeseva One',),
		'priority'		=> '14'
	));

    // Title bottom margin
    $wp_customize->add_setting('ql_title_tagline_'.$myglobal_post_id.'[title_margin]', array(
        'default'       => QL_TITLE_BOTTOM_MARGIN,
        'transport'     => 'postMessage',
        'type'          => 'option',
    ));

    $wp_customize->add_control(new QL_Slider_Control($wp_customize, 'ql_title_bottom_margin', array(
            'label'     => 'Title Bottom Margin',
            'section'   => 'title_tagline',
            'settings'  => 'ql_title_tagline_'.$myglobal_post_id.'[title_margin]',
            'min'       => 0,
            'max'       => 100,
            'step'      => 1,
	'priority'		=> '15'
        ))
    );
    // Tagline bottom margin (Header bottom margin)
    $wp_customize->add_setting('ql_title_tagline_'.$myglobal_post_id.'[header_bottom_margin]', array(
        'default'       => QL_HEADER_BOTTOM_MARGIN,
        'transport'     => 'postMessage',
        'type'          => 'option',
    ));

    $wp_customize->add_control(new QL_Slider_Control($wp_customize, 'ql_header_bottom_margin', array(
            'label'     => 'Tagline Bottom Margin',
            'section'   => 'title_tagline',
            'settings'  => 'ql_title_tagline_'.$myglobal_post_id.'[header_bottom_margin]',
            'min'       => 0,
            'max'       => 100,
            'step'      => 1,
	'priority'		=> '16'
        ))
    );


    // Heading text color
	$wp_customize->add_setting( 'ql_title_tagline_'.$myglobal_post_id.'[title_color]', array(
		'default'		=> QL_TITLE_TAGLINE_TITLE_COLOR,
		'type'			=> 'option',
		'capability'	=> 'edit_theme_options',
		'transport'		=> 'postMessage',
	));
	$wp_customize->add_control( new WP_Customize_Color_Control( 
		$wp_customize, 'ql_title_tagline_title_color', array(
			'label'			=> 'Title Color',
			'section'		=> 'title_tagline',
			'settings'		=> 'ql_title_tagline_'.$myglobal_post_id.'[title_color]',
		'priority'		=> '17'
		))
	);

        //Tagline text color
	$wp_customize->add_setting( 'ql_title_tagline_'.$myglobal_post_id.'[title_colorinfo]', array(
		'default'		=> QL_TITLE_TAGLINE_TITLE_COLORINFO,
		'type'			=> 'option',
		'capability'	=> 'edit_theme_options',
		'transport'		=> 'postMessage',
	));
	$wp_customize->add_control( new WP_Customize_Color_Control( 
		$wp_customize, 'ql_title_tagline_title_colorinfo', array(
			'label'			=> 'Tagline Color',
			'section'		=> 'title_tagline',
			'settings'		=> 'ql_title_tagline_'.$myglobal_post_id.'[title_colorinfo]',
		'priority'		=> '18'
		))
	);

	// Logo
	$wp_customize->add_setting( 'ql_title_tagline_'.$myglobal_post_id.'[logo]', array(
		'default'		=> '',
		'type'			=> 'option',
		'capability'	=> 'edit_theme_options',
		'transport'		=> 'postMessage',
	));
	$wp_customize->add_control( new WP_Customize_Image_Control( 
		$wp_customize, 'ql_title_tagline_logo', array(
			'label'			=> 'Logo',
			'section'		=> 'title_tagline',
			'settings'		=> 'ql_title_tagline_'.$myglobal_post_id.'[logo]',
		'priority'		=> '19'
		))
	);
	
	
	/**
	 * Footer
	 */
	$wp_customize->add_section( 'ql_footer', array(
		'title'			=> 'Footer',
		'description'	=> 'Site footer settings',
		'priority'		=> 40,
	));
	
	// Footer content
	$wp_customize->add_setting( 'ql_footer_'.$myglobal_post_id.'[content]', array(
		'default'		=> QL_FOOTER_CONTENT,
		'type'			=> 'option',
		'capability'	=> 'edit_theme_options',
		'transport'		=> 'postMessage',
	));
	$wp_customize->add_control( 'ql_footer_content', array(
		'label'			=> 'Footer text',
		'section'		=> 'ql_footer',
		'settings'		=> 'ql_footer_'.$myglobal_post_id.'[content]',
		'type'			=> 'text',
	));
	
	
	/**
	 * Widget settings
	 */
	$wp_customize->add_section( 'ql_widgets', array(
		'title'			=> 'Widgets',
		'description'	=> 'Widgets settings',
		'priority'		=> 39,
	));
	
	// email
	$wp_customize->add_setting( 'ql_widgets_'.$myglobal_post_id.'[email]', array(
		'default'		=> 'checked',
		'type'			=> 'option',
		'capability'	=> 'edit_theme_options',
		'transport'		=> 'postMessage',
	));
	$wp_customize->add_control( 'ql_widgets_email', array(
		'label'			=> 'Email Subscription',
		'section'		=> 'ql_widgets',
		'settings'		=> 'ql_widgets_'.$myglobal_post_id.'[email]',
		'type'			=> 'checkbox',
		'priority'		=> 1,
	));
	
	// email
	$wp_customize->add_setting( 'ql_widgets_'.$myglobal_post_id.'[mailchimp]', array(
		'default'		=> '',
		'type'			=> 'option',
		'capability'	=> 'edit_theme_options',
		'transport'		=> 'postMessage',
	));
	$wp_customize->add_control( 'ql_widgets_mailchimp', array(
		'label'			=> 'Use Mailchimp',
		'section'		=> 'ql_widgets',
		'settings'		=> 'ql_widgets_'.$myglobal_post_id.'[mailchimp]',
		'type'			=> 'checkbox',
		'priority'		=> 2,
	));
	
	// email submit color
	$wp_customize->add_setting( 'ql_widgets_'.$myglobal_post_id.'[email_submit_color]', array(
		'default'		=> QL_WIDGETS_EMAIL_SUBMIT_COLOR,
		'type'			=> 'option',
		'capability'	=> 'edit_theme_options',
		'transport'		=> 'postMessage',
	));
	$wp_customize->add_control( 'ql_widgets_email_submit_color', array(
		'label'			=> 'Email Submit Button Color',
		'section'		=> 'ql_widgets',
		'settings'		=> 'ql_widgets_'.$myglobal_post_id.'[email_submit_color]',
		'type'			=> 'select',
		'choices'		=> array(
			'gray'	=> 'Gray',
			'blue'	=> 'Blue',
			'green'	=> 'Green',
			'red'	=> 'Red',
			'orange'	=> 'Orange',
		),
		'priority'		=> 3,
	));
	
	// Center image
	$wp_customize->add_setting( 'ql_widgets_'.$myglobal_post_id.'[image]', array(
		'default'		=> '',
		'type'			=> 'option',
		'capability'	=> 'edit_theme_options',
		'transport'		=> 'postMessage',
	));
	$wp_customize->add_control( new WP_Customize_Image_Control( 
		$wp_customize, 'ql_widgets_image', array(
			'label'			=> 'Center Image',
			'section'		=> 'ql_widgets',
			'settings'		=> 'ql_widgets_'.$myglobal_post_id.'[image]',
			'priority'		=> 4,
		))
	);
	
	// email
	$wp_customize->add_setting( 'ql_widgets_'.$myglobal_post_id.'[slider]', array(
		'default'		=> '',
		'type'			=> 'option',
		'capability'	=> 'edit_theme_options',
		'transport'		=> 'postMessage',
	));
	$wp_customize->add_control( 'ql_widgets_slider', array(
		'label'			=> 'Activate slider',
		'section'		=> 'ql_widgets',
		'settings'		=> 'ql_widgets_'.$myglobal_post_id.'[slider]',
		'type'			=> 'checkbox',
		'priority'		=> 5,
	));
	
	// Center slider image 1
	$wp_customize->add_setting( 'ql_widgets_'.$myglobal_post_id.'[slider_image_1]', array(
		'default'		=> '',
		'type'			=> 'option',
		'capability'	=> 'edit_theme_options',
		'transport'		=> 'postMessage',
	));
	$wp_customize->add_control( new WP_Customize_Image_Control( 
		$wp_customize, 'ql_widgets_slider_image_1', array(
			'label'			=> 'Slider Image 1',
			'section'		=> 'ql_widgets',
			'settings'		=> 'ql_widgets_'.$myglobal_post_id.'[slider_image_1]',
			'priority'		=> 6,
		))
	);
	
	// Center slider image 2
	$wp_customize->add_setting( 'ql_widgets_'.$myglobal_post_id.'[slider_image_2]', array(
		'default'		=> '',
		'type'			=> 'option',
		'capability'	=> 'edit_theme_options',
		'transport'		=> 'postMessage',
	));
	$wp_customize->add_control( new WP_Customize_Image_Control( 
		$wp_customize, 'ql_widgets_slider_image_2', array(
			'label'			=> 'Slider Image 2',
			'section'		=> 'ql_widgets',
			'settings'		=> 'ql_widgets_'.$myglobal_post_id.'[slider_image_2]',
			'priority'		=> 7,
		))
	);
	
	// Center slider image 3
	$wp_customize->add_setting( 'ql_widgets_'.$myglobal_post_id.'[slider_image_3]', array(
		'default'		=> '',
		'type'			=> 'option',
		'capability'	=> 'edit_theme_options',
		'transport'		=> 'postMessage',
	));
	$wp_customize->add_control( new WP_Customize_Image_Control( 
		$wp_customize, 'ql_widgets_slider_image_3', array(
			'label'			=> 'Slider Image 3',
			'section'		=> 'ql_widgets',
			'settings'		=> 'ql_widgets_'.$myglobal_post_id.'[slider_image_3]',
			'priority'		=> 8,
		))
	);
	
	// Center slider image 4
	$wp_customize->add_setting( 'ql_widgets_'.$myglobal_post_id.'[slider_image_4]', array(
		'default'		=> '',
		'type'			=> 'option',
		'capability'	=> 'edit_theme_options',
		'transport'		=> 'postMessage',
	));
	$wp_customize->add_control( new WP_Customize_Image_Control( 
		$wp_customize, 'ql_widgets_slider_image_4', array(
			'label'			=> 'Slider Image 4',
			'section'		=> 'ql_widgets',
			'settings'		=> 'ql_widgets_'.$myglobal_post_id.'[slider_image_4]',
			'priority'		=> 9,
		))
	);
	
	// Center youtube video
	$wp_customize->add_setting( 'ql_widgets_'.$myglobal_post_id.'[video]', array(
		'default'		=> '',
		'type'			=> 'option',
		'capability'	=> 'edit_theme_options',
		'transport'		=> 'postMessage',
	));
	$wp_customize->add_control( 'ql_widgets_video', array(
		'label'			=> 'Center Youtube Video Link',
		'section'		=> 'ql_widgets',
		'settings'		=> 'ql_widgets_'.$myglobal_post_id.'[video]',
		'type'			=> 'text',
		'priority'		=> 10,
	));
	
	// Show wordpress widgets
	$wp_customize->add_setting( 'ql_widgets_'.$myglobal_post_id.'[wordpress]', array(
		'default'		=> '',
		'type'			=> 'option',
		'capability'	=> 'edit_theme_options',
		'transport'		=> 'postMessage',
	));
	$wp_customize->add_control( 'ql_widgets_wordpress', array(
		'label'			=> 'Show Wordpress Widgets',
		'section'		=> 'ql_widgets',
		'settings'		=> 'ql_widgets_'.$myglobal_post_id.'[wordpress]',
		'type'			=> 'checkbox',
		'priority'		=> 11,
	));
	

     
	



	
	/**
	 * Background settings
	 */
	$wp_customize->add_section( 'ql_background', array(
		'title'			=> 'Background',
		'description'	=> 'Background settings',
		'priority'		=> 35,
	));
	
	// Background color
	$wp_customize->add_setting( 'ql_background_'.$myglobal_post_id.'[color]', array(
		'default'		=> QL_BACKGROUND_COLOR,
		'type'			=> 'option',
		'capability'	=> 'edit_theme_options',
		'transport'		=> 'postMessage',
	));
	$wp_customize->add_control( new WP_Customize_Color_Control( 
		$wp_customize, 'ql_background_color', array(
			'label'			=> 'Background color',
			'section'		=> 'ql_background',
			'settings'		=> 'ql_background_'.$myglobal_post_id.'[color]',
		))
	);
	// Background image
	$wp_customize->add_setting( 'ql_background_'.$myglobal_post_id.'[image]', array(
		'default'		=> '',
		'type'			=> 'option',
		'capability'	=> 'edit_theme_options',
		'transport'		=> 'postMessage',
	));
	$wp_customize->add_control( new WP_Customize_Image_Control(
		$wp_customize, 'ql_background_image', array(
			'label'			=> 'Background image',
			'section'		=> 'ql_background',
			'settings'		=> 'ql_background_'.$myglobal_post_id.'[image]',
		))
	);

    // Enable Background Gradient
    $wp_customize->add_setting('ql_background_'.$myglobal_post_id.'[gradient]', array(
        'default'       => false,
        'type'          => 'option',
        'transport'     => 'postMessage'
    ));
    $wp_customize->add_control(new QL_Gradient_BG_Control(
        $wp_customize, 'ql_bg_gradient', array(
            'label'     => 'Gradient Background',
            'section'   => 'ql_background',
            'settings'  => 'ql_background_'.$myglobal_post_id.'[gradient]',
            'choices'    => array('blue', 'green', 'red', 'dark-grey', 'grey')
        ))
    );
	
	
	/**
	 * Content settings
	 */
	$wp_customize->add_section( 'ql_content', array(
		'title'			=> 'Content Settings',
		'description'	=> 'Content settings',
		'priority'		=> 35,
	));
	
	// Content text color
	$wp_customize->add_setting( 'ql_content_'.$myglobal_post_id.'[content]', array(
		'default'		=> QL_CONTENT_CONTENT,
		'type'			=> 'option',
		'capability'	=> 'edit_theme_options',
		'transport'		=> 'postMessage',
	));
	$wp_customize->add_control( new Textarea_Control(
		$wp_customize, 'ql_content_content', array(
			'label'			=> 'Content',
			'section'		=> 'ql_content',
			'settings'		=> 'ql_content_'.$myglobal_post_id.'[content]',
		))
	);
	 
   // CONTENT FONT
	$wp_customize->add_setting( 'ql_content_'.$myglobal_post_id.'[fontinfoa]', array(
		'default'		=> 'QL_CONTENT_CONTENTFONT',
		'type'			=> 'option',
		'capability'	=> 'edit_theme_options',
	));
	$wp_customize->add_control( 'ql_content_'.$myglobal_post_id.'[content]', array(
		'label'			=> 'Content Font',
		'section'		=> 'ql_content',
		'settings'		=> 'ql_content_'.$myglobal_post_id.'[fontinfoa]',
		'type'			=> 'select',
		'choices'		=> array(
			''			=> '-None-',
			'Abel'			=> 'Abel',
			'Abril Fatface'		=> 'Abril Fatface',
			'Actor'			=> 'Actor',
			'Aldrich'		=> 'Aldrich',
			'Alegreya SC'		=> 'Alegreya SC',
			'Alice'			=> 'Alice',
			'Anaheim'		=> 'Anaheim',
			'Asul'			=> 'Asul',
			'BenchNine'		=> 'BenchNine',
			'Bigelow Rules'		=> 'Bigelow Rules',
			'Bilbo Swash Caps'	=> 'Bilbo Swash Caps',
			'Bubbler One'		=> 'Bubbler One',
			'Cabin'			=> 'Cabin',
			'Carrois Gothic'	=> 'Carrois Gothic',
			'Chela One'		=> 'Chela One',
			'Cherry Cream Soda'	=> 'Cherry Cream Soda',
			'Coda'			=> 'Coda',
			'Cousine'		=> 'Cousine',
			'Ceviche One'		=> 'Ceviche One',
			'Chewy'			=> 'Chewy',
			'Creepster'		=> 'Creepster',
			'Crushed'		=> 'Crushed',
			'Droid Sans'		=> 'Droid Sans',
			'Droid Serif'		=> 'Droid Serif',
			'Droid Sans Mono'	=> 'Droid Sans Mono',
			'Eagle Lake'		=> 'Eagle Lake',
			'Electrolize'		=> 'Electrolize',
                        'Faster One'		=> 'Faster One',
			'Fenix'			=> 'Fenix',
			'Flavors'		=> 'Flavors',
			'Francois One'		=> 'Francois One',
			'Finger Paint'		=> 'Finger Paint',
			'Freckle Face'		=> 'Freckle Face',
			'Fredoka One'		=> 'Fredoka One',
			'Geo'			=> 'Geo',
			'Germania One'		=> 'Germania One',
			'Goblin One'		=> 'Goblin One',
			'Gilda Display'		=> 'Gilda Display',
			'Give You Glory'	=> 'Give You Glory',
			'Glass Antiqua'		=> 'Glass Antiqua',
			'Happy Monkey'		=> 'Happy Monkey',
			'Hammersmith One'	=> 'Hammersmith One',
			'Hanalei'		=> 'Hanalei',
			'Holtwood One SC'	=> 'Holtwood One SC',
			'IM Fell Great Primer SC'	=> 'IM Fell Great Primer SC',
			'Inika'			=> 'Inika',
			'Istok Web'		=> 'Istok Web',
			'Josefin Sans'		=> 'Josefin Sans',
			'Josefin Slab'		=> 'Josefin Slab',
			'Judson'		=> 'Judson',
			'Maiden Orange'		=> 'Maiden Orange',
			'Marck Script'		=> 'Marck Script',
			'Medula One'		=> 'Medula One',
			'Merriweather Sans'	=> 'Merriweather Sans',
			'Merienda One'		=> 'Merienda One',
			'Metrophobic'		=> 'Metrophobic',
			'Montserrat Alternates'	=> 'Montserrat Alternates',
			'Montserrat Subrayada'	=> 'Montserrat Subrayada',
                        'Mouse Memoirs'		=> 'Mouse Memoirs',
			'News Cycle'		=> 'News Cycle',
			'New Rocker'		=> 'New Rocker',
			'Nothing You Could Do'	=> 'Nothing You Could Do',
			'Kavoon'		=> 'Kavoon',
			'Kenia'			=> 'Kenia',
			'Knewave'		=> 'Knewave',
                        'Lato'			=> 'Lato',
			'Limelight'		=> 'Limelight',
			'Londrina Sketch'	=> 'Londrina Sketch',
			'Luckiest Guy'		=> 'Luckiest Guy',
			'Oleo Script'		=> 'Oleo Script',
			'Oswald'		=> 'Oswald',
			'Ovo'			=> 'Ovo',
			'Oxygen'		=> 'Oxygen',
			'Pathway Gothic One'	=> 'Pathway Gothic One',
			'Poller One'		=> 'Poller One',
			'Poly'			=> 'Poly',
			'Port Lligat Slab'	=> 'Port Lligat Slab',
			'Port Lligat Sans'	=> 'Port Lligat Sans',
			'Playball'		=> 'Playball',
			'Playfair Display'	=> 'Playfair Display',
			'Quattrocento'		=> 'Quattrocento',
			'Quintessential'	=> 'Quintessential',
			'Qwigley'		=> 'Qwigley',
			'Rancho'		=> 'Rancho',
			'Revalia'		=> 'Revalia',
                        'Roboto'		=> 'Roboto',
			'Ropa Sans'		=> 'Ropa Sans',
			'Rosario'		=> 'Rosario',
			'Rouge Script'		=> 'Rouge Script',
			'Risque'		=> 'Risque',
			'Rufina'		=> 'Rufina',
			'Tangerine'		=> 'Tangerine',
			'Trochut'		=> 'Trochut',
                        'Trykker'		=> 'Trykker',
                        'The Girl Next Door'	=> 'The Girl Next Door',
			'Shadows Into Light'	=> 'Shadows Into Light',
			'Scada'			=> 'Scada',
			'Sunshiney'		=> 'Sunshiney',
			'Ubuntu'		=> 'Ubuntu',
			'Underdog'		=> 'Underdog',
			'UnifrakturMaguntia'	=> 'UnifrakturMaguntia',
			'Unkempt'		=> 'Unkempt',
			'UnifrakturMaguntia'	=> 'UnifrakturMaguntia',
			'Wallpoet'		=> 'Wallpoet',
			'Varela'		=> 'Varela',
			'Vollkorn'		=> 'Vollkorn',
                        'Yeseva One'		=> 'Yeseva One',
		


),
	));



// Viravnivanie
    // Position
	$wp_customize->add_setting( 'ql_content_'.$myglobal_post_id.'[positiontext]', array(
		'default'		=> QL_CONTENT_POSITIONTEXT,
		'type'			=> 'option',
		'capability'	=> 'edit_theme_options',
		'transport'		=> 'postMessage',
	));
	$wp_customize->add_control('ql_content_'.$myglobal_post_id.'[positiontext]', array(
		'label'			=> 'Position',
		'section'		=> 'ql_content',
		'settings'		=> 'ql_content_'.$myglobal_post_id.'[positiontext]',
		'type'			=> 'radio',
		'choices'		=> array(
			'left'=>'Left',
			'center'=>'Center',
			'right'=>'Right',
                        'justify'=>'justify',
		),
	));



	// Content text color
	$wp_customize->add_setting( 'ql_content_'.$myglobal_post_id.'[color]', array(
		'default'		=> QL_CONTENT_COLOR,
		'type'			=> 'option',
		'capability'	=> 'edit_theme_options',
		'transport'		=> 'postMessage',
	));
	$wp_customize->add_control( new WP_Customize_Color_Control( 
		$wp_customize, 'ql_content_color', array(
			'label'			=> 'Content text color',
			'section'		=> 'ql_content',
			'settings'		=> 'ql_content_'.$myglobal_post_id.'[color]',
		))
	);

	$wp_customize->add_setting( 'ql_content_'.$myglobal_post_id.'[opacity]', array(
		'default'		=> QL_CONTENT_OPACITY,
		'type'			=> 'option',
		'capability'	=> 'edit_theme_options',
		'transport'		=> 'postMessage',
	));
	$wp_customize->add_control( 'ql_content_opacity', array(
		'label'			=> 'Content opacity (0-100)',
		'section'		=> 'ql_content',
		'settings'		=> 'ql_content_'.$myglobal_post_id.'[opacity]',
		'type'			=> 'text',
	));

    
	
	// Content text color
	

	$wp_customize->add_setting( 'ql_content_'.$myglobal_post_id.'[headerthreecolor]', array(
		'default'		=> QL_CONTENT_H3_COLOR,
		'type'			=> 'option',
		'capability'	=> 'edit_theme_options',
		'transport'		=> 'postMessage'
	));
	$wp_customize->add_control( new WP_Customize_Color_Control( 
		$wp_customize, 'ql_headerthreecolor', array(
			'label'			=> 'H3 Color',
			'section'		=> 'ql_content',
			'settings'		=> 'ql_content_'.$myglobal_post_id.'[headerthreecolor]'
		))
	);

	$wp_customize->add_setting( 'ql_content_'.$myglobal_post_id.'[headerthreesize]', array(
		'default'		=> QL_CONTENT_H3_FONTSIZE,
		'type'			=> 'option',
		'capability'	=> 'edit_theme_options',
		'transport'		=> 'postMessage'
	));
	$wp_customize->add_control( 'ql_headerthreesize', array(
		'label'			=> 'H3 Font Size',
		'section'		=> 'ql_content',
		'settings'		=> 'ql_content_'.$myglobal_post_id.'[headerthreesize]',
	));

	$wp_customize->add_setting( 'ql_content_'.$myglobal_post_id.'[headerthree]', array(
		'default'		=> 'QL_CONTENT_CONTENTH3FONT',
		'type'			=> 'option',
		'capability'	=> 'edit_theme_options'
	));
	$wp_customize->add_control( 'ql_headerthree', array(
		'label'			=> 'H3 Font',
		'section'		=> 'ql_content',
		'settings'		=> 'ql_content_'.$myglobal_post_id.'[headerthree]',
		'type'			=> 'select',
		'choices'		=> array(
			''			=> '-None-',
			'Abel'			=> 'Abel',
			'Abril Fatface'		=> 'Abril Fatface',
			'Actor'			=> 'Actor',
			'Aldrich'		=> 'Aldrich',
			'Alegreya SC'		=> 'Alegreya SC',
			'Alice'			=> 'Alice',
			'Anaheim'		=> 'Anaheim',
			'Asul'			=> 'Asul',
			'BenchNine'		=> 'BenchNine',
			'Bigelow Rules'		=> 'Bigelow Rules',
			'Bilbo Swash Caps'	=> 'Bilbo Swash Caps',
			'Bubbler One'		=> 'Bubbler One',
			'Cabin'			=> 'Cabin',
			'Carrois Gothic'	=> 'Carrois Gothic',
			'Chela One'		=> 'Chela One',
			'Cherry Cream Soda'	=> 'Cherry Cream Soda',
			'Coda'			=> 'Coda',
			'Cousine'		=> 'Cousine',
			'Ceviche One'		=> 'Ceviche One',
			'Chewy'			=> 'Chewy',
			'Creepster'		=> 'Creepster',
			'Crushed'		=> 'Crushed',
			'Droid Sans'		=> 'Droid Sans',
			'Droid Serif'		=> 'Droid Serif',
			'Droid Sans Mono'	=> 'Droid Sans Mono',
			'Eagle Lake'		=> 'Eagle Lake',
			'Electrolize'		=> 'Electrolize',
                        'Faster One'		=> 'Faster One',
			'Fenix'			=> 'Fenix',
			'Flavors'		=> 'Flavors',
			'Francois One'		=> 'Francois One',
			'Finger Paint'		=> 'Finger Paint',
			'Freckle Face'		=> 'Freckle Face',
			'Fredoka One'		=> 'Fredoka One',
			'Geo'			=> 'Geo',
			'Germania One'		=> 'Germania One',
			'Goblin One'		=> 'Goblin One',
			'Gilda Display'		=> 'Gilda Display',
			'Give You Glory'	=> 'Give You Glory',
			'Glass Antiqua'		=> 'Glass Antiqua',
			'Happy Monkey'		=> 'Happy Monkey',
			'Hammersmith One'	=> 'Hammersmith One',
			'Hanalei'		=> 'Hanalei',
			'Holtwood One SC'	=> 'Holtwood One SC',
			'IM Fell Great Primer SC'	=> 'IM Fell Great Primer SC',
			'Inika'			=> 'Inika',
			'Istok Web'		=> 'Istok Web',
			'Josefin Sans'		=> 'Josefin Sans',
			'Josefin Slab'		=> 'Josefin Slab',
			'Judson'		=> 'Judson',
			'Maiden Orange'		=> 'Maiden Orange',
			'Marck Script'		=> 'Marck Script',
			'Medula One'		=> 'Medula One',
			'Merriweather Sans'	=> 'Merriweather Sans',
			'Merienda One'		=> 'Merienda One',
			'Metrophobic'		=> 'Metrophobic',
			'Montserrat Alternates'	=> 'Montserrat Alternates',
			'Montserrat Subrayada'	=> 'Montserrat Subrayada',
                        'Mouse Memoirs'		=> 'Mouse Memoirs',
			'News Cycle'		=> 'News Cycle',
			'New Rocker'		=> 'New Rocker',
			'Nothing You Could Do'	=> 'Nothing You Could Do',
			'Kavoon'		=> 'Kavoon',
			'Kenia'			=> 'Kenia',
			'Knewave'		=> 'Knewave',
                        'Lato'			=> 'Lato',
			'Limelight'		=> 'Limelight',
			'Londrina Sketch'	=> 'Londrina Sketch',
			'Luckiest Guy'		=> 'Luckiest Guy',
			'Oleo Script'		=> 'Oleo Script',
			'Oswald'		=> 'Oswald',
			'Ovo'			=> 'Ovo',
			'Oxygen'		=> 'Oxygen',
			'Pathway Gothic One'	=> 'Pathway Gothic One',
			'Poller One'		=> 'Poller One',
			'Poly'			=> 'Poly',
			'Port Lligat Slab'	=> 'Port Lligat Slab',
			'Port Lligat Sans'	=> 'Port Lligat Sans',
			'Playball'		=> 'Playball',
			'Playfair Display'	=> 'Playfair Display',
			'Quattrocento'		=> 'Quattrocento',
			'Quintessential'	=> 'Quintessential',
			'Qwigley'		=> 'Qwigley',
			'Rancho'		=> 'Rancho',
			'Revalia'		=> 'Revalia',
                        'Roboto'		=> 'Roboto',
			'Ropa Sans'		=> 'Ropa Sans',
			'Rosario'		=> 'Rosario',
			'Rouge Script'		=> 'Rouge Script',
			'Risque'		=> 'Risque',
			'Rufina'		=> 'Rufina',
			'Tangerine'		=> 'Tangerine',
			'Trochut'		=> 'Trochut',
                        'Trykker'		=> 'Trykker',
                        'The Girl Next Door'	=> 'The Girl Next Door',
			'Shadows Into Light'	=> 'Shadows Into Light',
			'Scada'			=> 'Scada',
			'Sunshiney'		=> 'Sunshiney',
			'Ubuntu'		=> 'Ubuntu',
			'Underdog'		=> 'Underdog',
			'UnifrakturMaguntia'	=> 'UnifrakturMaguntia',
			'Unkempt'		=> 'Unkempt',
			'UnifrakturMaguntia'	=> 'UnifrakturMaguntia',
			'Wallpoet'		=> 'Wallpoet',
			'Varela'		=> 'Varela',
			'Vollkorn'		=> 'Vollkorn',
                        'Yeseva One'		=> 'Yeseva One'
		)));


			// Line spacing
    $wp_customize->add_setting('ql_content_'.$myglobal_post_id.'[line_spacing]', array(
        'default'       => QL_CONTENT_LINE_SPACING,
        'transport'     => 'postMessage',
        'type'          => 'option'
    ));

    $wp_customize->add_control(new QL_Slider_Control($wp_customize, 'ql_content_line_spacing', array(
            'label'     => 'Line Spacing',
            'section'   => 'ql_content',
            'settings'  => 'ql_content_'.$myglobal_post_id.'[line_spacing]',
            'min'       => 0,
            'max'       => 100,
            'step'      => 1
        ))
    );


	$wp_customize->add_section( 'ql_emailsign', array(
		'title'			=> 'Email Signup',
		'description'	=> 'Email Signup Options',
		'priority'		=> 36,
	));

    	$wp_customize->add_setting( 'ql_emailsign_'.$myglobal_post_id.'[emailtiptext]', array(
		'default'		=> QL_CONTENT_EMAIL_TIPTEXT,
		'type'			=> 'option',
		'capability'	=> 'edit_theme_options',
		'transport'		=> 'postMessage'
	));
	$wp_customize->add_control( 'ql_emailtiptext', array(
		'label'			=> 'Email Signup Text',
		'section'		=> 'ql_emailsign',
		'settings'		=> 'ql_emailsign_'.$myglobal_post_id.'[emailtiptext]',
	));

	$wp_customize->add_setting( 'ql_emailsign_'.$myglobal_post_id.'[emailtipposition]', array(
		'default'		=> QL_CONTENT_POSITIONEMAIL,
		'type'			=> 'option',
		'capability'	=> 'edit_theme_options',
		'transport'		=> 'postMessage',
	));
	$wp_customize->add_control('ql_emailsign_'.$myglobal_post_id.'[emailtipposition]', array(
		'label'			=> 'Email Signup Position',
		'section'		=> 'ql_emailsign',
		'settings'		=> 'ql_emailsign_'.$myglobal_post_id.'[emailtipposition]',
		'type'			=> 'radio',
		'choices'		=> array(
			'bottom'=>'Bottom',
			'belowslider'=>'Below Slider',
			'both'=>'Both',
		),
	));

	$wp_customize->add_setting( 'ql_emailsign_'.$myglobal_post_id.'[emailalert]', array(
		'default'		=> 'checked',
		'type'			=> 'option',
		'capability'	=> 'edit_theme_options',
		'transport'		=> 'postMessage',
	));
	$wp_customize->add_control('ql_emailsign_'.$myglobal_post_id.'[emailalert]', array(
		'label'			=> 'Send email alert to Admins',
		'section'		=> 'ql_emailsign',
		'settings'		=> 'ql_emailsign_'.$myglobal_post_id.'[emailalert]',
		'type'			=> 'checkbox',
	));

	// remove static front page section
	$wp_customize->remove_section('static_front_page');
	
	
	/* Live view */
	if ( $wp_customize->is_preview() && ! is_admin() )
		add_action( 'wp_footer', 'ql_customize_preview', 21);
	
}
add_action( 'customize_register', 'ql_customize_register' );


function ql_customize_css()
{
	$postID=$_SESSION["postid"];
	$ql_content = get_option('ql_content_'.$postID);
	$ql_title_tagline = get_option('ql_title_tagline_'.$postID);
	$ql_background = get_option('ql_background_'.$postID);
	$ql_layout = get_option('ql_layout_'.$postID);
    ?>

<?php if ( ! empty($ql_title_tagline['font']) ): ?>
<link href='http://fonts.googleapis.com/css?family=<?php echo urlencode($ql_title_tagline['font']); ?>' rel='stylesheet' type='text/css'>
<?php endif; ?>
<?php if ( ! empty($ql_title_tagline['fontinfo']) ): ?>
<link href='http://fonts.googleapis.com/css?family=<?php echo urlencode($ql_title_tagline['fontinfo']); ?>' rel='stylesheet' type='text/css'>
<?php endif; ?>
<?php if ( ! empty($ql_content['fontinfoa']) ): ?>
<link href='http://fonts.googleapis.com/css?family=<?php echo urlencode($ql_content['fontinfoa']); ?>    ' rel='stylesheet' type='text/css'>
<?php endif; ?>


        <style type="text/css">
			body{
				background-color:<?php echo $ql_background['color']?$ql_background['color']:QL_BACKGROUND_COLOR; ?>; 
				<?php if($ql_background['image']): ?>
				background-image:url('<?php echo $ql_background['image']; ?>');
				background-attachment:fixed;
				background-repeat:no-repeat;
				background-position:center 25%;
				<?php endif; ?>
                <?php if ( $ql_background['gradient'] && $ql_background['gradient'] !== 'false' ): ?>
                background-image: url(<?php echo get_template_directory_uri() . '/images/backgrounds/' . $ql_background['gradient'] . '-gradient.jpg'; ?>);
                background-size: 100% 100%;
                background-attachment: scroll;
                <?php endif; ?>
			}

            header {
                margin-bottom: <?php echo $ql_title_tagline['header_bottom_margin']; ?>px;
            }

            header h1{
				
text-align:<?php echo $ql_title_tagline['postitle']?$ql_title_tagline['postitle']:QL_CONTENT_POSTITLE; ?>;


font-family: '<?php echo $ql_title_tagline['font']?$ql_title_tagline['font']:QL_TITLE_TAGLINE_FONT; ?>';
				color: <?php echo $ql_title_tagline['title_color']?$ql_title_tagline['title_color']:QL_TITLE_TAGLINE_TITLE_COLOR; ?>;

margin-bottom: <?php echo $ql_title_tagline['title_margin']; ?>px;
            }
           

header h2{


text-align:<?php echo $ql_title_tagline['postitleinfo']?$ql_title_tagline['postitleinfo']:QL_CONTENT_POSTITLEINFO; ?>;
				
font-family: '<?php echo $ql_title_tagline['fontinfo']?$ql_title_tagline['fontinfo']:QL_TITLE_TAGLINE_FONTINFO; ?>';

color:<?php echo $ql_title_tagline['title_colorinfo']?$ql_title_tagline['title_colorinfo']:QL_TITLE_TAGLINE_TITLE_COLORINFO; ?>;
			
            }






 #page-content { 




text-align:<?php echo $ql_content['positiontext']?$ql_content['positiontext']:QL_CONTENT_POSITIONTEXT; ?>;


color:<?php echo $ql_content['color']?$ql_content['color']:QL_CONTENT_COLOR; ?>;

 }

#page-content h3{
	color: <?php echo $ql_content['headerthreecolor']?>;
	font-size:<?php echo $ql_content['headerthreesize']?>px;
	font-family:'<?php echo $ql_content['headerthree']?>';
}


#content p {
font-family: '<?php echo $ql_content['fontinfoa']?$ql_content['fontinfoa']:QL_CONTENT_CONTENTFONT; ?>';
line-height: <?php echo $ql_content['line_spacing']; ?>px
}



            #wrap {
				padding: <?php echo $ql_layout['padding']?$ql_layout['padding']:QL_LAYOUT_PADDING; ?>px; 
				border-radius:<?php echo isset($ql_layout['roundness'])?$ql_layout['roundness']:QL_LAYOUT_ROUNDNESS ?>px; 
				-moz-border-radius:<?php echo isset($ql_layout['roundness'])?$ql_layout['roundness']:QL_LAYOUT_ROUNDNESS ?>px; 
				-webkit-border-radius:<?php echo isset($ql_layout['roundness'])?$ql_layout['roundness']:QL_LAYOUT_ROUNDNESS ?>px;
				box-shadow:#333 0 0 <?php echo $ql_layout['boxShadow']?$ql_layout['boxShadow']:QL_LAYOUT_BOX_SHADOW; ?>px;
				-moz-box-shadow:#333 0 0 <?php echo $ql_layout['boxShadow']?$ql_layout['boxShadow']:QL_LAYOUT_BOX_SHADOW; ?>px;
				-webkit-box-shadow:#333 0 0 <?php echo $ql_layout['boxShadow']?$ql_layout['boxShadow']:QL_LAYOUT_BOX_SHADOW; ?>px;
			<?php 	
				$mbrowse=get_user_browser();
				if($mbrowse=="ie"):?>
				background:url("<?php echo get_template_directory_uri().'/js/rgba/rgba.php?r=255&g=255&b=255&a='.($ql_content['opacity']?($ql_content['opacity']):(QL_CONTENT_OPACITY));?>");
			<?php else: ?>
				background-color: rgba(255, 255, 255, <?php echo $ql_content['opacity']?($ql_content['opacity']/100):(QL_CONTENT_OPACITY/100); ?>);
			<?php endif; ?>
				behavior:url("<?php echo get_template_directory_uri().'/js/pie/PIE.htc';?>");
			}
        </style>
    <?php
}
add_action( 'wp_head', 'ql_customize_css');

function get_user_browser()
{
    $u_agent = $_SERVER['HTTP_USER_AGENT'];
    $ub = '';
    if(preg_match('/MSIE/i',$u_agent))
    {
        $ub = "ie";
    }
    elseif(preg_match('/Firefox/i',$u_agent))
    {
        $ub = "firefox";
    }
    elseif(preg_match('/Safari/i',$u_agent))
    {
        $ub = "safari";
    }
    elseif(preg_match('/Chrome/i',$u_agent))
    {
        $ub = "chrome";
    }
    elseif(preg_match('/Flock/i',$u_agent))
    {
        $ub = "flock";
    }
    elseif(preg_match('/Opera/i',$u_agent))
    {
        $ub = "opera";
    }

    return $ub;
}

function ql_enqueue_scripts(){
	wp_enqueue_script(
		'flex-slider',
		get_template_directory_uri(). '/js/jquery.flexslider-min.js',
		array('jquery')
	);
}
add_action('wp_enqueue_scripts', 'ql_enqueue_scripts');


/* Live view cusomize */
function ql_customize_preview() {
	$custom_post_id=$_SESSION["postid"];
	?>
	<script type="text/javascript">
	/**
	 * @credit http://papermashup.com/read-url-get-variables-withjavascript/
	 */
	

	function getUrlVar(url, key) {
		var val;
		var parts = url.replace(/[?&]+([^=&]+)=([^&]*)/gi, function(m,k,value) {
			if(k == key)
				val = value;
		});
		return val;
	}
	( function( $ ){		
		/**
		 * Set social network icon links
		 */
		var mycoin2=$('#coin-slider ul.slides').clone();
		function socialLink(network, lnk){
			if(lnk.length != 0){
				$('#social-'+network+' a').attr('href', lnk);
				$('#social-'+network).show();
			}else{
				$('#social-'+network+' a').attr('href', '#');
				$('#social-'+network).hide();
			}
		}
		
		// content font-family settings
		
		// position
		wp.customize('ql_layout_<?php echo $custom_post_id;?>[position]', function (value){
			value.bind(function(to){
				// first remove all position classes
				$('#wrap').removeClass('pos-center');
				$('#wrap').removeClass('pos-left');
				$('#wrap').removeClass('pos-right');
				
				// add the correct position class
				$('#wrap').addClass('pos-'+to);
			});
		});
		

	    wp.customize('ql_content_<?php echo $custom_post_id;?>[content]', function (value){
		value.bind(function(to){
			$('#page-content').html(parseContent(to));
		    });
	    });

	    wp.customize('ql_content_<?php echo $custom_post_id;?>[positiontext]',function( value ) {
		value.bind(function(to) {
			$('#page-content').css('text-align', to);
    		    });
            });

	    wp.customize('ql_content_<?php echo $custom_post_id;?>[color]',function( value ) {
		value.bind(function(to) {
			$('#page-content').css('color', to);
    		    });
            });

	    wp.customize('ql_content_<?php echo $custom_post_id;?>[headerthreecolor]',function( value ) {
		value.bind(function(to) {
			$('#page-content h3').css('color', to);
    		});
    		});

    	    wp.customize('ql_content_<?php echo $custom_post_id;?>[headerthreesize]',function( value ) {
		value.bind(function(to) {
			$('#page-content h3').css('font-size', to+"px");
    		});
    	    });

	    wp.customize('ql_content_<?php echo $custom_post_id;?>[line_spacing]', function(value) {
        	value.bind(function(newval) {
            		$('#page-content p').css('line-height', newval + 'px');
        	});
    	    });

    	/**
	 * Parse plain text to html
	 */
	function parseContent(content){
		// parse links
		//var exp = /(\b(https?|ftp|file):\/\/[-A-Z0-9+&@#\/%?=~_|!:,.;]*[-A-Z0-9+&@#\/%=~_|])/ig;
		//content = content.replace(exp,"<a href='$1'>$1</a>");
			
		// add html p tags
		/*content = '<p>'+content;
		content = content.replace(/\n/g, '</p><p>');
		content += '</p>';*/
		jQuery.ajaxSetup({async:false});
		jQuery.post("/wp-content/themes/QuickLaunch.me-master/echowp.php","postcontent="+content,function(data){
			content=data;
		});
		jQuery.ajaxSetup({async:true});
		return content;
			
	}


		// footer text
		wp.customize('ql_footer_<?php echo $custom_post_id;?>[content]', function (value){
			value.bind(function(to){
				$('#copyright p').html(to);
			});
		});
		
		// background color
		wp.customize('ql_background_<?php echo $custom_post_id;?>[color]', function (value){
			value.bind(function(to){
				$('body').css('background-color', to);
			});
		});
		
		// padding 
		wp.customize('ql_layout_<?php echo $custom_post_id;?>[padding]', function (value){
			value.bind(function(to){
				$('#wrap').css('padding', to+'px');
			});
		});
		
		// box shadow
		wp.customize('ql_layout_<?php echo $custom_post_id;?>[boxShadow]', function (value){
			value.bind(function(to){
				$('#wrap').css('box-shadow', '0 0 '+to+'px #333');
				$('#wrap').css('-moz-box-shadow', '0 0 '+to+'px #333');
				$('#wrap').css('-webkit-box-shadow', '0 0 '+to+'px #333');
			});
		});
		
		// box Opacity
		wp.customize('ql_content_<?php echo $custom_post_id;?>[opacity]', function (value){
			value.bind(function(to){
				$('#wrap').css('background-color', 'rgba(255, 255, 255, '+(to/100)+')');
			});
		});
		
		// Email
		wp.customize('ql_widgets_<?php echo $custom_post_id;?>[email]', function (value){
			value.bind(function(to){
				if(to)
					$('#email').show();
				else
					$('#email').hide();
			});
		});
	
		
		// Center video
		wp.customize('ql_widgets_<?php echo $custom_post_id;?>[video]', function (value){
			value.bind(function(to){
				if(to.length != 0){
					videoId = getUrlVar(to, 'v');				
					$('#video param[name=movie]').attr('value', 'http://www.youtube.com/v/'+videoId+'&version=3&autohide=1&showinfo=0');
					$('#video embed').attr('src', 'http://www.youtube.com/v/'+videoId+'&version=3&autohide=1&showinfo=0');
					
					// adjust width/height
					var width = $('#wrap').width();
					var height = Math.round(width * (3/4));
					$('#video object, #video embed').attr('width', width);
					$('#video object, #video embed').attr('height', height);
					
					$('#video').show();				
				}else
					$('#video').hide();
			});
		});
		
		// Social network links
		wp.customize('ql_social_<?php echo $custom_post_id;?>[twitter]', function (value){ value.bind(function(to){ socialLink('twitter', to); }); });
		wp.customize('ql_social_<?php echo $custom_post_id;?>[youtube]', function (value){ value.bind(function(to){ socialLink('youtube', to); }); });
		wp.customize('ql_social_<?php echo $custom_post_id;?>[facebook]', function (value){ value.bind(function(to){ socialLink('facebook', to); }); });
		wp.customize('ql_social_<?php echo $custom_post_id;?>[googleplus]', function (value){ value.bind(function(to){ socialLink('googleplus', to); }); });
		wp.customize('ql_social_<?php echo $custom_post_id;?>[linkedin]', function (value){ value.bind(function(to){ socialLink('linkedin', to); }); });
	
		// Backgroud image
		wp.customize('ql_background_<?php echo $custom_post_id;?>[image]', function (value){
			value.bind(function(to){
				if(to.length != 0)
					$('body').css('background', 'url(\''+to+'\') center 25% no-repeat fixed');
				else
					$('body').css('background-image', 'none');
			});
		});
		
		wp.customize('ql_background_<?php echo $custom_post_id;?>[gradient]', function (value){
			value.bind(function(to){
				if(to.length != 0){
					$('body').css('background', 'url(\'<?php echo get_template_directory_uri() ?> /images/backgrounds/'+to+'-gradient.jpg\')');
   				        $('body').css('background-size', '100% 100%');
				        $('body').css('background-attachment', 'scroll');

				}else{
					$('body').css('background-image', 'none');
				}
			});
		});
		
		// wordpress widgets
		wp.customize('ql_widgets_<?php echo $custom_post_id;?>[wordpress]', function (value){
			value.bind(function(to){
				console.log(to);
				if(to)
					$('#widgets').show();
				else
					$('#widgets').hide();
			});
		});
		
		// wordpress widgets
		wp.customize('ql_title_tagline_<?php echo $custom_post_id;?>[logo]', function (value){
			value.bind(function(to){
				if(to.length != 0){
					$('#site-logo').html('<img src="'+to+'" style="max-width:100%;" />')
					$('#site-logo').show();
					
					// hide site title and description
					$('#site-title-and-desc').hide();
					
				}else{
					$('#site-logo').html('');
					$('#site-logo').hide();
					
					// show site title and description
					$('#site-title-and-desc').show();
				}
			});
		});
		
		// title color
		wp.customize('ql_title_tagline_<?php echo $custom_post_id;?>[title_color]', function (value){
			value.bind(function(to){
				$('#site-title').css('color', to);
			});
		});
		
		// title align
		wp.customize('ql_title_tagline_<?php echo $custom_post_id;?>[postitle]', function (value){
			value.bind(function(to){
				$('#site-title').css('text-align', to);
			});
		});

	    	wp.customize('ql_title_tagline_<?php echo $custom_post_id;?>[title_margin]', function(value) {
        		value.bind(function(newval) {
            			$('#site-title').css('margin-bottom', newval + 'px');
        		});
    		});

    		wp.customize('ql_title_tagline_<?php echo $custom_post_id;?>[header_bottom_margin]', function(value) {
        		value.bind(function(newval) {
            			$('header').css('margin-bottom', newval + 'px');
        		});
    		});

		wp.customize('ql_title_tagline_<?php echo $custom_post_id;?>[postitleinfo]', function (value){
			value.bind(function(to){
				$('#site-desc').css('text-align', to);
			});
		});

		wp.customize('ql_title_tagline_<?php echo $custom_post_id;?>[title_colorinfo]', function (value){
			value.bind(function(to){
				$('#site-desc').css('color', to);
			});
		});



		// newsletter submit button color
		wp.customize('ql_widgets_<?php echo $custom_post_id;?>[email_submit_color]', function (value){
			value.bind(function(to){
				$('.newsletter-form .btn').attr('class', 'btn '+to);
			});
		});
		
		// Use mailchimp
		wp.customize('ql_widgets_<?php echo $custom_post_id;?>[mailchimp]', function (value){
			value.bind(function(to){
				if(to){
					$('#email').hide();
					$('#mailchimp').show();
				}else{
					$('#mailchimp').hide();
					$('#email').show();
				}
			});
		});

		wp.customize('ql_emailsign_<?php echo $custom_post_id;?>[emailtiptext]', function (value){
			value.bind(function(to){
				$('input[name=email]').attr("placeholder",to);
   	 		});
    		});
    		wp.customize('ql_emailsign_<?php echo $custom_post_id;?>[emailtipposition]', function (value){
			value.bind(function(to){
				if(to=='belowslider'){
					$('.email-top').removeClass("hidden");
					$('.email-bottom').addClass("hidden");
				}else if(to=='bottom'){
					$('.email-top').addClass("hidden");
					$('.email-bottom').removeClass("hidden");
				}else{
					$('.email-top,.email-bottom').removeClass("hidden");
				}
    			});
    		});

		// corner radius
		wp.customize('ql_layout_<?php echo $custom_post_id;?>[roundness]', function (value){
			value.bind(function(to){
				$('#wrap').css('border-radius', to+'px');
				$('#wrap').css('-moz-border-radius', to+'px');
				$('#wrap').css('-webkit-border-radius', to+'px');
			});
		});

		// Content
		
		// Slider
		wp.customize('ql_widgets_<?php echo $custom_post_id;?>[slider]', function (value){ 
			value.bind(function(to){ 
				if(to) 
					$('#image-slider').show(); 
				else 
					$('#image-slider').hide(); 
			}); 
		});
		wp.customize('ql_widgets_<?php echo $custom_post_id;?>[slider_image_1]', function (value){ value.bind(function(to){ sliderImage(1, to); }); });
		wp.customize('ql_widgets_<?php echo $custom_post_id;?>[slider_image_2]', function (value){ value.bind(function(to){ sliderImage(2, to); }); });
		wp.customize('ql_widgets_<?php echo $custom_post_id;?>[slider_image_3]', function (value){ value.bind(function(to){ sliderImage(3, to); }); });
		wp.customize('ql_widgets_<?php echo $custom_post_id;?>[slider_image_4]', function (value){ value.bind(function(to){ sliderImage(4, to); }); });

		function sliderImage(id, url){
			if(url.length != 0){
				if($('#slider-image-'+id).length > 0){
					$('img[id=slider-image-'+id+']').attr("src",url);
				}else{
					$('<div id="coin-slider" class="mycoinslider"></div>').insertAfter(".mycoinslider");
					$(".mycoinslider:eq(0)").remove();
					mycoin2.find(".clone").remove();
					mycoin2.find("li").removeAttr("class");
					$('#coin-slider').append(mycoin2);
					$('#coin-slider').find("ul.slides").append('<li><img id="slider-image-'+id+'" src="'+url+'" style="max-width:460px"/></li>');
				}
			}else{
				$('#slider-image-'+id).remove();
			}
			
			// restart coinslider
			//$('#coin-slider').coinslider({width:468, links:false});
			$('#coin-slider').flexslider({animation: "slide",});
	}

			
		// Center image
		wp.customize('ql_widgets_<?php echo $custom_post_id;?>[image]', function (value){
			value.bind(function(to){
				if(to.length != 0)
					$('#center-image').html('<img src="'+to+'" style="width:100%;" />').show();
				else
					$('#center-image').html('').hide();
			});
		});


		
	} )( jQuery)
	</script>
	<?php 
} 

add_shortcode("cta","show_cta_button");


function show_cta_button($atts){
	return '<a href="'.$atts["link"].'" style="text-decoration:none"><input type="button" value="'.$atts["name"].'" name="" class="btn '.$atts["color"].'"></a>';
}

?>
