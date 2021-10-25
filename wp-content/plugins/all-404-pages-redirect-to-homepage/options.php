<?php
if(isset($_POST['Save_Options']))
{ 
	$status=sanitize_text_field($_POST['status']);
	$redirect_to=sanitize_text_field($_POST['redirect_to']);
	$nonce=$_POST['_wpnonce'];
	if(wp_verify_nonce( $nonce, 'r404option_nounce' ))
	{
		update_option('status_404r',$status);
		update_option('redirect_to_404r',$redirect_to);
		success_option_msg_404r('Settings Saved!');
		
	}
	else
	{
        failure_option_msg_404r('Unable to save data!');
    }
}

$status= get_status_404r();

$redirect_to=get_redirect_to_404r();


?>

<div class="wrap">
<h2>All 404 Redirect to Homepage</h2>
	<div class='redgk_inner'>

	

	<form method="POST">

		<table class="form-table">



			<tbody>

			<tr valign="top">

				<th scope="row">Status</th>

				<td>

				<select id="satus_404r" name="status">

					<option value="1" <?php if($status==1){ echo "selected"; } ?>>Enabled</option>

					<option value="0" <?php if($status==0){ echo "selected"; } ?>>Disabled</option>

				</select>

				</td>

			</tr>

			<tr valign="top">

				<th scope="row">Redirect all 404 pages to: </th>

				<td>

			<input type="text" name="redirect_to" id="redirect_to" class="regular-text" value="<?php echo $redirect_to; ?>">	

				<p class="description">Links that redirect for all 404 pages.</p>



				</td>

			</tr>

			</tbody>

		</table>
		<input type="hidden" id="_wpnonce" name="_wpnonce" value="<?php echo $nonce = wp_create_nonce('r404option_nounce'); ?>" />
		<input  class="button-primary" type="submit" value="Update" name="Save_Options">

	</form>  

	</div>

</div>

