<?php
function wplb_save_header_404_info()
{

#header is wp object. no need in this function
	if(is_404())
	{
		$ip=$_SERVER['REMOTE_ADDR'];

			$wplb_404_log=get_option('wplb_404_log', false);
			if(!is_array($wplb_404_log)){$wplb_404_log=array(); add_option('wplb_404_log', $wplb_404_log, '', 'no');}#optoin auto loading is off
			$info=array(
				"time"=>time(),
				"ip"=>$ip,
				"reqUrl"=>$_SERVER['REQUEST_URI'],
				"refUrl"=>$_SERVER['HTTP_REFERER'],
				"userAgent"=>$_SERVER['HTTP_USER_AGENT']
			);

			$wplb_404_log[]=$info;
			update_option('wplb_404_log',$wplb_404_log);

	}#end if(is_404())

}


function wplb_show_404_log()
{
$wplb_404_log=get_option('wplb_404_log', array());
$wplb_404_log=array_reverse($wplb_404_log);	
echo "<b>Total 404 Error Occurred : "; echo count($wplb_404_log); echo " Times</b><br>";

echo '<table class="widefat" style="width:1000px">';
	echo '<thead><tr><th width="130" style="min-width:130px;">Time</th> <th>IP</th> <th style="min-width:200px; max-width:450px;">Reqested URL</th> <th>Referer URL</th><th>User Agent</th> </tr></thead>';
	foreach($wplb_404_log as $item)
	{
	echo "<tr><td>".date('d-m-Y H:i:s',$item['time'])."</td>";
		echo '<td>'.$item['ip'].'</td><td width="400">'; if(strlen($item['reqUrl'])>80){ echo '<input type="text" size="80"  readonly="readonly" value="'.esc_html($item['reqUrl']).'" />';}else{echo $item['reqUrl'];} echo "</td>";
		echo '<td><input type="text" size="30" readonly="readonly" value="'.esc_html($item['refUrl']).'" /></td>';
		echo '<td><input type="text" size="30" readonly="readonly" value="'.esc_html($item['userAgent']).'" /></td></tr>';
	}
	echo "</table>";
}


function wplb_clear_404_log()
{
	if(isset($_POST['clear_log']))
	{
	delete_option("wplb_404_log");
	echo '<h3>Log Has Been Cleared</h3>';
	}
	echo "<div>";
	echo '<form action="admin.php?page='.WPLB_PLUGIN_SLUG.'/404_log" method="post">';
	echo '<input name="clear_log" class="button" type="submit" value = "Clear log">';
	echo '</form>';
	echo '</div><br>';
}



function wplb_404_log()

{
	global $wplb_services;
	echo '<div class="wrap"  style="width:1100px; max-width:1100px;">';
	echo '<div id="icon-edit-pages" class="icon32"></div><h2>404 Error Log</h2>';
	echo '<div style="margin-left:15px;">This log helps you to findout for which urls and how many times "404 (not found ) error" occurred in your website.</div>';
	echo '<div style="margin:10px;">';
	if($wplb_services['eror_404_log'])
	{
	echo '&nbsp; Saving new log is enable, you can <a href="admin.php?page='.WPLB_PLUGIN_SLUG.'">Disable</a>'; 
	}
	else
	{
	echo '&nbsp; Saving new log is disable, you can <a href="admin.php?page='.WPLB_PLUGIN_SLUG.'">Enable</a>'; 
	}
	echo '</div>';
	wplb_clear_404_log();
	wplb_show_404_log();
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

if($wplb_services['eror_404_log'])
{
	add_action('wp_head', 'wplb_save_header_404_info');
}

?>