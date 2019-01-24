<?php

/*
Plugin Name: Session Master
Plugin URI: http://pjeremymalouf.com
Description: For setting get data to pick up and save to a session.
Author: Paul Jeremy Malouf
Version: 1.0
Author URI: http://pjeremymalouf.com
*/

session_start();

function pjm_session_master_install()
{
    add_option('pjm_session_master_find_1');
    add_option('pjm_session_master_replace_1');
    add_option('pjm_session_master_fallback_1');
    add_option('pjm_session_master_find_2');
    add_option('pjm_session_master_replace_2');
    add_option('pjm_session_master_fallback_2');
    add_option('pjm_session_master_find_3');
    add_option('pjm_session_master_replace_3');
    add_option('pjm_session_master_fallback_3');
}

add_action('activate_pjm_session_master/pjm_session_master.php', 'pjm_session_master_install');

function pjm_session_master_filter($content)
{
	for($i = 0; $i < 3; $i++){
	
        $find_option = "pjm_session_master_find_" . $i;

        $find_optionValue = get_option($find_option);
        
        $replace_option = "pjm_session_master_replace_" . $i;

        $replace_optionValue = get_option($replace_option);
        
        if (isset($_POST[$find_optionValue])){
            
            $content = str_replace($replace_optionValue, $_POST[$find_optionValue], $content);
        
        }else if (isset($_GET[$find_optionValue])){
            
            $content = str_replace($replace_optionValue, $_GET[$find_optionValue], $content);
        
        }else if (isset($_SESSION[$find_optionValue])){
            
            $content = str_replace($replace_optionValue, $_SESSION[$find_optionValue], $content);
        }else{
            
            $fallback_option = "pjm_session_master_fallback_" . $i;
            
             $content = str_replace($replace_optionValue, get_option($fallback_option), $content);
        }
    }
    
    return $content;
}

function pjm_session_master_emodalFilter($content){
    
    return pjm_session_master_filter($content);
}

add_filter('emodal_modal_content', 'pjm_session_master_emodalFilter');

function pjm_session_master_contentFilter($content) {
    
    return pjm_session_master_filter($content);
}

add_filter( 'the_content', 'pjm_session_master_contentFilter' );

function pjm_session_lformm_contentFilter($content) {
    
    return pjm_session_master_filter($content);
}

add_filter( 'pjm_lformm_form', 'pjm_session_lformm_contentFilter' );


function pjm_session_master()
{
	for($i = 0; $i < 3; $i++){
	
        $find_option = "find_" . $i;

        $find_optionValue = get_option($find_option);
        
        if (isset($_GET[$find_optionValue])){
            
            if ($_GET[$find_optionValue] != ""){

                $_SESSION[$find_optionValue] = $_GET[$find_optionValue];
            }
        }
    }
    
    return $content;
}

add_action('wp_footer', 'pjm_session_master');
add_action('wp_header', 'pjm_session_master');

function session_master_menu()
{
    global $wpdb;
    include 'pjm_session_master-admin.php';
}
 
function pjm_session_master_admin_actions()
{
    add_options_page("Session Master", "Session Master", 1,
"Session-Master", "session_master_menu");
}
 
add_action('admin_menu', 'pjm_session_master_admin_actions');


?>
