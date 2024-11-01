<?php
if($wplb_services['login_attack_log'])
{
add_action('wp_login_failed', 'wplb_save_failed_login_info');
}
function wplb_save_failed_login_info($username)
{
	$ip=$_SERVER['REMOTE_ADDR'];
	$ref=$_SERVER['HTTP_REFERER'];
	$req=$_SERVER['REQUEST_URI'];
	$userAgent=$_SERVER['HTTP_USER_AGENT'];
	$failed_list=get_option('wplb_login_attack_log', false);
	if(!is_array($failed_list)){$failed_list=array(); add_option('wplb_login_attack_log', $failed_list, '', 'no');}

	$password=$_REQUEST['pwd'];
	$new_fail=array(
		"time"=>time(),
		"ip"=>$ip,
		"username"=>$username,
		"password"=>$password,
		"reqUrl"=>$req,
		"refUrl"=>$ref,
		"userAgent"=>$userAgent
	);
	
	$failed_list[]=$new_fail;
	
update_option('wplb_login_attack_log',$failed_list);

}

function wplb_show_failed_login_info()
{
$failed_list=get_option('wplb_login_attack_log', array());
$failed_list=array_reverse($failed_list);	
echo "<b>Total Failed Login Occurred : "; echo count($failed_list); echo " Times</b><br>";
echo '<table class="widefat" style="width:1000px">';
	  echo '<thead><tr><th width="130" style="min-width:130px;">Time</th> <th>IP</th> <th>UserName</th> <th>Password</th> <th style="min-width:200px">Reqested URL</th> <th style="min-width:200px">Referer URL</th><th>User Agent</th> </tr></thead>';
	foreach($failed_list as $fail)
	{
	echo "<tr><td>".date('d-m-Y H:i:s',$fail['time'])."</td><td>".$fail['ip']."</td><td>".$fail['username']."</td><td>".$fail['password']."</td><td>".$fail['reqUrl']."</td><td>".$fail['refUrl']."</td>";
	echo '<td><input type="text" size="30" readonly="readonly" value="'.esc_html($fail['userAgent']).'" /></td></tr>';
	}
	echo "</table>";
}

function wplb_clear_login_attack_log()
{
global $wplb_services;
	if(isset($_POST['clear_log']))
	{
	delete_option("wplb_login_attack_log");
	echo '<h3>Log Has Been Cleared</h3>';
	}

	echo "<div>";
	echo '<form action="admin.php?page='.WPLB_PLUGIN_SLUG.'/login_attack_log" method="post">';
	echo '<input name="clear_log" class="button" type="submit" value = "Clear log">';
	echo '</form>';
	echo '</div><br>';

}

function wplb_login_attack_log()
{
	global $wplb_services;
	echo '<div class="wrap">';
	echo '<div id="icon-edit-pages" class="icon32"></div><h2>Login Attack Log</h2>';
	echo '<div style="margin-left:15px;">This log helps you to know how many times hackers try to login in your website and which usernames and passwords they are using.</div>';
	echo '<div style="margin:10px;">';
	if($wplb_services['login_attack_log'])
	{
	echo '&nbsp; Saving new log is enable, you can <a href="admin.php?page='.WPLB_PLUGIN_SLUG.'">Disable</a>'; 
	}
	else
	{
	echo '&nbsp; Saving new log is disable, you can <a href="admin.php?page='.WPLB_PLUGIN_SLUG.'">Enable</a>'; 
	}
	echo '</div>';
	wplb_clear_login_attack_log();
	wplb_show_failed_login_info();
	?>
		<div style="text-align:center; margin-top:40px;">
		<strong>If this plugin is helpful for you please buy me a cup of coffee</strong>
		<form action="https://www.paypal.com/cgi-bin/webscr" method="post">
		<input type="image" src="https://www.paypalobjects.com/en_US/i/btn/btn_donateCC_LG.gif" border="0" name="submit" alt="PayPal - The safer, easier way to pay online!">
		<img alt="" border="0" src="https://www.paypalobjects.com/en_US/i/scr/pixel.gif" width="1" height="1">
		<input type="hidden" name="cmd" value="_donations">
		<input type="hidden" name="business" value="tarek@arcom.com.bd">
		<input type="hidden" name="lc" value="US">
		<input type="hidden" name="item_name" value="WP Logs Book - WordPress Plugin">
		<input type="hidden" name="no_note" value="0">
		<input type="hidden" name="currency_code" value="USD">
		<input type="hidden" name="bn" value="PP-DonationsBF:btn_donateCC_LG.gif:NonHostedGuest">
		</form>
		</div>
	<?php
	echo '</div>';
}

?>