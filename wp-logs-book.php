<?php
/*
Plugin Name: WP Logs Book
Plugin URI: http://onetarek.com/my-wordpress-plugins/wp-logs-book/
Description: WP Logs Book plugin stores various activity logs for your website. This plugin helps you to know how many times hackers try to login in your website and which usernames and passwords they are using. You are able to know for which URLs and how many times "404 (not found ) errors" are occurred in your website. More activity logs are under development for next  version. 
Version: 1.0.1
Author: oneTarek
Author URI: http://onetarek.com
License: GNU General Public License (GPL)
Min WP Version: 2.5.0
Tags: Analytics , Developer, Logs, Notification, tracking, Hacking Attempt, Error 404

	Copyright 2012  oneTarek  (email : onetarek@gmail.com)

    This program is free software; you can redistribute it and/or modify
    it under the terms of the GNU General Public License, version 2, as 
    published by the Free Software Foundation.

    This program is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

    You should have received a copy of the GNU General Public License
    along with this program; if not, write to the Free Software
    Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
*/

/*
#Declare Some Global Variable and Constants
*/
define('WPLB_PLUGIN_SLUG','wp-logs-book');
define("WPLB_PLUGIN_URL",plugins_url("",__FILE__ )); #without trailing slash (/)
define("WPLB_PLUGIN_PATH",plugin_dir_path(__FILE__)); #with trailing slash (/)
define("WPLB_API_URL",WPLB_PLUGIN_URL."/api/api.php");
define("WPLB_ADMIN_API_URL",WPLB_PLUGIN_URL."/api/admin_api.php");
define("WPLB_LOG_VIEW_CAPABILITY","manage_options");
$wplb_services=array('eror_404_log'=>1, 'login_attack_log'=>1);
$wplb_options=get_option('wplb_options', array('services'=>$wplb_services));
$wplb_services=$wplb_options['services'];
#includes
require_once(WPLB_PLUGIN_PATH."/includes/functions.php"); 
require_once(WPLB_PLUGIN_PATH."/includes/404-log.php");
require_once(WPLB_PLUGIN_PATH."/includes/login-attack-log.php"); 



#admin menus
function wplb_admin_menus()
{
	add_menu_page( "WP Logs Book", "WP Logs Book", WPLB_LOG_VIEW_CAPABILITY, WPLB_PLUGIN_SLUG, 'wp_logs_book');
	add_submenu_page(WPLB_PLUGIN_SLUG, 'Options', 'Options' , WPLB_LOG_VIEW_CAPABILITY, WPLB_PLUGIN_SLUG, 'wp_logs_book');
	add_submenu_page(WPLB_PLUGIN_SLUG, 'Error 404 Log', 'Error 404 Log' , WPLB_LOG_VIEW_CAPABILITY, WPLB_PLUGIN_SLUG.'/404_log', 'wplb_404_log');
	add_submenu_page(WPLB_PLUGIN_SLUG, 'Login Attack Log', 'Login Attack Log' , WPLB_LOG_VIEW_CAPABILITY, WPLB_PLUGIN_SLUG.'/login_attack_log', 'wplb_login_attack_log');

}
add_action('admin_menu', 'wplb_admin_menus');


function wp_logs_book()
{
	global $wplb_options,$wplb_services;
	echo '<div class="wrap" style="width:1100px; padding-left: 10px;">';
	echo '<div id="icon-edit-pages" class="icon32"></div><h2>WP Logs Book</h2>';
	if(isset($_POST['wplb_save']))
	{
	$wplb_services['eror_404_log']=$_POST['wplb_error_404_log'];
	$wplb_services['login_attack_log']=$_POST['wplb_login_attack_log'];
	$wplb_options['services']=$wplb_services;
	update_option('wplb_options', $wplb_options);
	}
	?>
	<div style="height:30px;"></div>
	<form action="" method="post">
	<table class="widefat" style="width:600px;">
		<thead>
			<tr><th><strong>Log Services</strong></th><th>&nbsp;</th></tr>
		</thead>
		<tr><td>Error 404 Log</td><td><input type="radio" name="wplb_error_404_log" value="1" id="wplb_error_404_log_1" <?php if($wplb_services['eror_404_log'])echo 'checked="checked"';?> /><label for="wplb_error_404_log_1"> Enable</label> <input type="radio" name="wplb_error_404_log" value="0" id="wplb_error_404_log_2" <?php if(!$wplb_services['eror_404_log'])echo 'checked="checked"';?> /><label for="wplb_error_404_log_2"> Disable</label></td></tr> 
		<tr><td>Login Attack Log</td><td><input type="radio" name="wplb_login_attack_log" value="1" id="wplb_login_attack_log_1" <?php if($wplb_services['login_attack_log'])echo 'checked="checked"';?>  /><label for="wplb_login_attack_log_1"> Enable</label> <input type="radio" name="wplb_login_attack_log" value="0" id="wplb_login_attack_log_2" <?php if(!$wplb_services['login_attack_log'])echo 'checked="checked"';?> /><label for="wplb_login_attack_log_2"> Disable</label></td></tr> 
		<tr><td colspan="2" style="text-align:center"><input type="submit" name="wplb_save" value="Save" class="button" style="width:100px;" /></td></tr>
	</table>
	</form>
	</div>
<?php
}
?>