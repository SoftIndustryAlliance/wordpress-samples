<?php

// PRODUCTION SETTINGS

if (function_exists('env')){
	env(function (){}, function(){

		// PRODUCTION SETTINGS

		// DISABLE AUTO THEME UPDATE
		add_filter('auto_update_theme', '__return_false');

		// DISABLE AUTO PLUGIN UPDATE
		add_filter('auto_update_plugin', '__return_false');

		// DISABLE UPDATE NOTICE
		function disable_uptades_notice(){
			//if (!current_user_can('update_core')) {
			remove_action('admin_notices', 'update_nag', 3);
			//}
		}
		add_action('admin_head', 'disable_uptades_notice', 1);

		// REMOVE CORE UPDATES
		function remove_core_updates(){
			global $wp_version;
			return(object) array('last_checked'=> time(),'version_checked'=> $wp_version,);
		}
		add_filter('pre_site_transient_update_core','remove_core_updates');
		add_filter('pre_site_transient_update_plugins','remove_core_updates');
		add_filter('pre_site_transient_update_themes','remove_core_updates');

	});
}

// REMOVE META GENERATORS

remove_action('wp_head','feed_links_extra', 3); 
remove_action('wp_head','feed_links', 2);
remove_action('wp_head','rsd_link');  
remove_action('wp_head','wlwmanifest_link'); 
remove_action('wp_head','wp_generator'); 


// REMOVE LINKS WHEN SHOW POSTS (PREV, NEXT, ORIGINAL URL...)

remove_action('wp_head','start_post_rel_link',10,0);
remove_action('wp_head','index_rel_link');
remove_action('wp_head','rel_canonical');
remove_action('wp_head','adjacent_posts_rel_link_wp_head', 10, 0 );
remove_action('wp_head','wp_shortlink_wp_head', 10, 0 );


// DISABLE REST API

add_filter('rest_enabled', '__return_false');


// DISABLE FILTERS REST API

remove_action('xmlrpc_rsd_apis', 'rest_output_rsd');
remove_action('wp_head', 'rest_output_link_wp_head', 10, 0);
remove_action('template_redirect', 'rest_output_link_header', 11, 0);
remove_action('auth_cookie_malformed', 'rest_cookie_collect_status');
remove_action('auth_cookie_expired', 'rest_cookie_collect_status');
remove_action('auth_cookie_bad_username', 'rest_cookie_collect_status');
remove_action('auth_cookie_bad_hash', 'rest_cookie_collect_status');
remove_action('auth_cookie_valid', 'rest_cookie_collect_status');
remove_filter('rest_authentication_errors', 'rest_cookie_check_errors', 100);


// DISABLE EVENTS REST API

remove_action('init', 'rest_api_init');
remove_action('rest_api_init', 'rest_api_default_filters', 10, 1);
remove_action('parse_request', 'rest_api_loaded');


// DISABLE EMBEDS LINKED WITH REST API

remove_action('rest_api_init', 'wp_oembed_register_route');
remove_filter('rest_pre_serve_request', '_oembed_rest_pre_serve_request', 10, 4);


// REMOVE EMOJI ICONS

remove_action('wp_head', 'print_emoji_detection_script', 7);
remove_action('wp_print_styles', 'print_emoji_styles');


// DISABLED OEMBED AUTO DICSOVERY
// Don't filter oEmbed results.

remove_filter('oembed_dataparse', 'wp_filter_oembed_result', 10);


// REMOVE OEMBED DISCOVERY LINKS

remove_action('wp_head', 'wp_oembed_add_discovery_links');


// REMOVE OEMBED-SPECIFIC JS FROM THE FRONT-END AND BACK-END

remove_action('wp_head', 'wp_oembed_add_host_js');


// REMOVE <P> IN CATEGORY DESCRIPTION

remove_filter('term_description','wpautop');


// REMOVE DNS PREFETCH

function remove_dns_prefetch($hints, $relation_type){
    if ('dns-prefetch' === $relation_type) {
        return array_diff(wp_dependencies_unique_hosts(), $hints);
    }
    return $hints;
}
add_filter('wp_resource_hints', 'remove_dns_prefetch', 10, 2);


// TO DISABLE SMART QUOTES

add_filter('run_wptexturize', '__return_false');


// SVG ALLOW MEDIA UPLOAD

function cc_mime_types($mimes) {
    $mimes['svg'] = 'image/svg+xml';
    return $mimes;
}
add_filter('upload_mimes', 'cc_mime_types');
function fix_svg_thumb_display() {
    echo '<style type="text/css">
    td.media-icon img[src$=".svg"], img[src$=".svg"].attachment-post-thumbnail { 
      width: 100% !important; 
      height: auto !important; 
    }</style>';
}
add_action('admin_head', 'fix_svg_thumb_display');


// TITLE GENERATOR

function custom_wp_title(){
	$blog_name = get_bloginfo('name');
	function title($text){
		echo '<title>'.$text.'</title>';
	}
	if ( is_home() || is_front_page() ){
		title($blog_name);
	} else if (is_category()) {
		$queried_object = get_queried_object();
		$cat_name = $queried_object->name;
		title($blog_name.' » '.$cat_name);
	} else {
		title($blog_name.' » '.get_the_title());
	}

}


// DESCRIPTION GENERATOR

function custom_wp_meta_description(){
    $meta_descr = '';
    if (is_home() || is_front_page()){
        $meta_descr = get_bloginfo('description');
    } else if (is_category()){
        $queried_object = get_queried_object();
        $taxonomy = $queried_object->taxonomy;
        $term_id = $queried_object->term_id;
        $meta_descr = get_field('meta__description', $taxonomy.'_'.$term_id);
    } else {
        $meta_descr = get_field('meta__description');
    }
    if (!empty($meta_descr)){
        $meta_descr_html = '<meta property="og:description" content="'.$meta_descr.'">';
        $meta_descr_html .= '<meta name="description" content="'.$meta_descr.'">';
        echo $meta_descr_html;
    }
}

// CONNECT LOCALIZATION FILES

function get_textdomain(){
	return basename(__DIR__);
}

function init_localization(){
	load_theme_textdomain(get_textdomain(), get_template_directory().'/languages');
}
add_action('after_setup_theme', 'init_localization');

// ACF INIT OPTIONS PAGE

//if (function_exists('acf_add_options_page')){
//	acf_add_options_page();
//}

function get_options_page_id(){
	return 256;
}

function get_front_page_id(){
	//return 2;
	return get_option('page_on_front');
}

// ADD BUTTONS TO ADMIN MENU

function admin_add_menus() {

	add_menu_page(
		__('Options', get_textdomain()),
		__('Options', get_textdomain()),
		'manage_options',
		'post.php?post='.get_options_page_id().'&action=edit',
		'',
		'dashicons-admin-generic',
		2
	);

	add_menu_page(
		__('Frontpage', get_textdomain()),
		__('Frontpage', get_textdomain()),
		'manage_options',
		'post.php?post='.get_front_page_id().'&action=edit',
		'',
		'dashicons-layout',
		3
	);

}
add_action('admin_menu', 'admin_add_menus');


// HEADER CUSTOM CODE

function custom_wp_header_code(){
	if (!empty($add_code_header = get_field('add-code-header', get_options_page_id()))){
		echo $add_code_header;
	}
}


// FOOTER CUSTOM CODE

function custom_wp_footer_code(){
	if (!empty($add_code_footer = get_field('add-code-footer', get_options_page_id()))){
		echo $add_code_footer;
	}
}


// FAVICONS

function custom_wp_favicon(){
	$fav_icon_dir = get_bloginfo('template_url').'/assets/static/favicon/';
	echo
		'<link rel="icon" type="image/png" sizes="16x16" href="'.$fav_icon_dir.'favicon-16x16.png">'.
		'<link rel="icon" type="image/png" sizes="32x32" href="'.$fav_icon_dir.'favicon-32x32.png">'.
		'<link rel="icon" type="image/png" sizes="96x96" href="'.$fav_icon_dir.'favicon-96x96.png">'.
		'<link rel="icon" type="image/png" sizes="192x192"  href="'.$fav_icon_dir.'android-icon-192x192.png">'.
		'<link rel="apple-touch-icon" sizes="57x57" href="'.$fav_icon_dir.'apple-icon-57x57.png">'.
		'<link rel="apple-touch-icon" sizes="60x60" href="'.$fav_icon_dir.'apple-icon-60x60.png">'.
		'<link rel="apple-touch-icon" sizes="72x72" href="'.$fav_icon_dir.'apple-icon-72x72.png">'.
		'<link rel="apple-touch-icon" sizes="76x76" href="'.$fav_icon_dir.'apple-icon-76x76.png">'.
		'<link rel="apple-touch-icon" sizes="114x114" href="'.$fav_icon_dir.'apple-icon-114x114.png">'.
		'<link rel="apple-touch-icon" sizes="120x120" href="'.$fav_icon_dir.'apple-icon-120x120.png">'.
		'<link rel="apple-touch-icon" sizes="144x144" href="'.$fav_icon_dir.'apple-icon-144x144.png">'.
		'<link rel="apple-touch-icon" sizes="152x152" href="'.$fav_icon_dir.'apple-icon-152x152.png">'.
		'<link rel="apple-touch-icon" sizes="180x180" href="'.$fav_icon_dir.'apple-icon-180x180.png">'.
		'<link rel="manifest" href="'.$fav_icon_dir.'manifest.json">'.
		'<meta name="msapplication-TileColor" content="#00a049">'.
		'<meta name="msapplication-TileImage" content="'.$fav_icon_dir.'ms-icon-144x144.png">'.
		'<meta name="theme-color" content="#00a049">';
}

function custom_wp_head(){
	custom_wp_title();
	custom_wp_meta_description();
	custom_wp_favicon();
	custom_wp_header_code();
}
add_action('wp_head','custom_wp_head');
add_action('wp_footer','custom_wp_footer_code');


// INCLUDE RESOURCES

function include_resources(){
	$bundle_dir = get_template_directory_uri().'/assets/bundle/';
    wp_deregister_script('jquery');
    wp_register_script('common-scripts', $bundle_dir.'index.js');
    wp_enqueue_script('common-scripts');
    wp_register_style('common-styles', $bundle_dir.'index.css');
    wp_enqueue_style('common-styles');
    if (is_admin_bar_showing()){
        wp_register_script('admin-scripts', $bundle_dir.'admin.js');
        wp_enqueue_script('admin-scripts');
        wp_register_style('admin-styles', $bundle_dir.'admin.css');
        wp_enqueue_style('admin-styles');
    }
}
add_action('wp_enqueue_scripts', 'include_resources');


/**
 * Clean up output of stylesheet <link> tags
 */
function clean_style_tag($input) {
	preg_match_all( "!<link rel='stylesheet'\s?(id='[^']+')?\s+href='(.*)' type='text/css' media='(.*)' />!", $input, $matches );
	if ( empty( $matches[2] ) ) {
		return $input;
	}
	// Only display media if it is meaningful
	$media = $matches[3][0] !== '' && $matches[3][0] !== 'all' ? ' media="' . $matches[3][0] . '"' : '';
	return '<link rel="stylesheet" href="' . $matches[2][0] . '"' . $media . '>' . "\n";
}
add_filter('style_loader_tag',  'clean_style_tag');
add_filter('script_loader_tag', 'clean_script_tag');

/**
 * Clean up output of <script> tags
 */
function clean_script_tag( $input ) {
	$input = str_replace( "type='text/javascript' ", '', $input );
	return str_replace( "'", '"', $input );
}


// ADD SOME CODE TO LOGIN PAGE

//apply_filters('login_headertitle', function(){...});


// INCLUDE SVG IN TEMPLATE

function inline_template_svg($file){
    echo file_get_contents(get_template_directory_uri().'/assets/static/images/'.$file.'.svg');
}


// INCLUDE SVG REMOTE URL

function inline_svg($file){
    echo file_get_contents($file);
}


// CLEAR TEL FOR LINKS "tel:"

function clear_tel($tel){
    return str_replace(array(' ','(',')','-'), '', $tel);
    //return str_replace(array('+', ' ', '(' , ')', '-'), '', $tel);
}


// HOME URL OR #HOME ANCHOR

function home_link($action){
    $name = 'home';
    if ($action == 'init') {
        echo $name;
    } else if ($action == 'get'){
        if (is_front_page()){
            echo '#'.$name;
        } else {
            echo esc_url(home_url('/'));
        }
    }
}

// FIELD WRAPPER

function check_field($name, $type){
    if ($type === 'field'){
        return get_field($name);
    } else if ($type === 'subfield'){
        return get_sub_field($name);
    }
}
function acf_check_field($html_start, $field_name, $field_type, $html_end){
    if ( !empty($field = check_field($field_name, $field_type)) ){
        echo $html_start.$field.$html_end;
    }
}


function wp_multilang_str($string){
    foreach($string as $key => $value){
        if (wpm_get_language() == $key) echo $value;
    }
}



