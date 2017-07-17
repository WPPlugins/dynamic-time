<?php
/*
Plugin Name: Dynamic Time
Plugin URI: http://r1cm.com/
Description: A simple, dynamic calendar-based time solution. 
Author: R1CM
Version: 1.1.5
Text Domain: r1cm
Author URI: http://r1cm.com/
License: GPL2
*/

if(!defined('ABSPATH')) exit;

function dynamicTime_adminMenu() {
  if(current_user_can('manage_options')) {
    add_menu_page('Dynamic Time','Dynamic Time','manage_options','dynamic-time','dynamicTime_admin','dashicons-clock','3');
  }
}
add_action('admin_menu','dynamicTime_adminMenu');


function dynamicTime_admin() {
  global $wpdb;
  include_once('time_admin.php');
  wp_enqueue_style('dynamicTime_style',plugins_url('assets/time_min.css?v=3.4',__FILE__));
}
add_shortcode('dynamicTime_admin','dynamicTime_admin');


function dynamicTime() {
  global $wpdb;
  include_once('time_cal.php');
  wp_enqueue_style('dynamicTime_style',plugins_url('assets/time_min.css?v=3.4',__FILE__));
  wp_enqueue_script('dynamicTime_script',plugins_url('assets/time_min.js?v=3.4',__FILE__));
}
add_shortcode('dynamicTime','dynamicTime');


function dynamicTime_activate() {
  global $wpdb;
  
  $wpdb->query("
    CREATE TABLE IF NOT EXISTS {$wpdb->prefix}time_config
    (ConfigID INT NOT NULL AUTO_INCREMENT
    ,Prompt BIT DEFAULT 0
    ,Period TINYINT DEFAULT 14
    ,WeekBegin TINYINT DEFAULT 0
    ,PRIMARY KEY (ConfigID));
  ");
  
  $wpdb->query("
    CREATE TABLE IF NOT EXISTS {$wpdb->prefix}time_user
    (UserID INT NOT NULL AUTO_INCREMENT
    ,WP_UserID INT
    ,Rate DECIMAL(4,2)
    ,Exempt BIT DEFAULT 0
    ,PRIMARY KEY (UserID));
  ");
  
  $wpdb->query("
    CREATE TABLE IF NOT EXISTS {$wpdb->prefix}time_entry
    (EntryID INT NOT NULL AUTO_INCREMENT
    ,WP_UserID INT
    ,Date INT
    ,Hours DECIMAL(4,2)
    ,HourType VARCHAR(3)
    ,TimeIn VARCHAR(8)
    ,TimeOut VARCHAR(8)
    ,PRIMARY KEY (EntryID));
  ");

  require_once(ABSPATH.'wp-admin/includes/upgrade.php');
  dbDelta($sql);
}
register_activation_hook(__FILE__,'dynamicTime_activate');


function dynamicTime_admin_notice() {
  $settings_url=get_admin_url(null,'admin.php?page=dynamic-time');
  if(current_user_can('manage_options') && strpos($_SERVER['REQUEST_URI'],'dynamic-time')===false):?>
    <div class="notice notice-success is-dismissible" style='margin:0;'>
      <p><? _e("The <em>Dynamic Time</em> plugin is active, but isn't configured to do anything yet. Visit the <a href='$settings_url'>configuration page</a> to complete setup.",'Dynamic Time');?>
    </div><?
  endif;
}

global $wpdb;
$get_config=$wpdb->get_results("SELECT 1 FROM {$wpdb->prefix}time_config LIMIT 1;",OBJECT);
if(!$get_config) add_action('admin_notices','dynamicTime_admin_notice');

function dynamicTime_add_action_links($links) {
  $settings_url=get_admin_url(null,'admin.php?page=dynamic-time');
  $support_url='http://r1cm.com/';
  
  $links[]='<a href="'.$support_url.'">Support</a>';
  array_push($links,'<a href="'.$settings_url.'">Settings</a>');
  return $links;
}
add_filter('plugin_action_links_'.plugin_basename(__FILE__),'dynamicTime_add_action_links');


function dynamicTime_uninstall() {
  global $wpdb;
  
  $wpdb->query("DROP TABLE IF EXISTS {$wpdb->prefix}time_config;");
  $wpdb->query("DROP TABLE IF EXISTS {$wpdb->prefix}time_user;");
  $wpdb->query("DROP TABLE IF EXISTS {$wpdb->prefix}time_entry;");

  require_once(ABSPATH.'wp-admin/includes/upgrade.php');
  dbDelta($sql);
}
register_uninstall_hook(__FILE__,'dynamicTime_uninstall');


?>