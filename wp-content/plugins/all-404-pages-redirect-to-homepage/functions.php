<?php
function current_link_404r()
{
	$prt = $_SERVER['SERVER_PORT'];
	$sname = $_SERVER['SERVER_NAME'];
	
	if (array_key_exists('HTTPS',$_SERVER) && $_SERVER['HTTPS'] != 'off' && $_SERVER['HTTPS'] != '')
	$sname = "https://" . $sname; 
	else
	$sname = "http://" . $sname; 
	
	if($prt !=80)
	{
	$sname = $sname . ":" . $prt;
	} 
	
	$path = $sname . $_SERVER["REQUEST_URI"];
	
	return $path ;

}
function get_status_404r()
{
	return $status= get_option('status_404r');
}
function get_redirect_to_404r()
{
	return $redirect_to=get_option('redirect_to_404r');
}

// Error message
function failure_option_msg_404r($msg)
{	
	echo  '<div class="notice notice-error redgk-error-msg is-dismissible"><p>' . $msg . '</p></div>';	
}

// Success message
function  success_option_msg_404r($msg)
{
	
	echo ' <div class="notice notice-success redgk-success-msg is-dismissible"><p>'. $msg . '</p></div>';		
	
}
?>