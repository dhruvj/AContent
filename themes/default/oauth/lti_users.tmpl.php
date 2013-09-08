<?php
/************************************************************************/
/* AContent                                                             */
/************************************************************************/
/* Copyright (c) 2013                                                   */
/* Inclusive Design Institute                                           */
/*                                                                      */
/* This program is free software. You can redistribute it and/or        */
/* modify it under the terms of the GNU General Public License          */
/* as published by the Free Software Foundation.                        */
/************************************************************************/
?>

<form name="form" method="get" action="<?php echo $_SERVER['PHP_SELF']; ?>">
<table class="data" summary="" rules="cols">
<thead>
<tr>
	<th scope="col">&nbsp;</th>
    <th scope="col"><?php echo _AT('user_name');	  ?></th>
    <th scope="col"><?php echo _AT('email');	  ?></th>
    <th scope="col"><?php echo _AT('last_login');	  ?></th>
    <th scope="col"><?php echo _AT('user_status');	  ?></th>
</tr>
</thead>

<?php if (!empty($this->users)) {?>
	<tfoot>
	<tr>
		<td colspan="3">
			<input type="submit" name="disable" value="<?php echo _AT('disable'); ?>" />
            <input type="submit" name="enable" value="<?php echo _AT('enable'); ?>" />
            <input type="submit" name="delete" value="<?php echo _AT('delete'); ?>" />
		</td>
	</tr>
	</tfoot>
	<tbody>
	<?php foreach ($this->users as $user) { ?>
		<tr>
            <input type="hidden" name="tool_id" value="<?php echo $this->tool_id; ?>" id="<?php $this->tool_id; ?>" />
			<td><input type="radio" name="user_id" value="<?php echo $user['user_id']; ?>" id="<?php echo $user['user_id']; ?>" /></td>
			<td><label for="<?php echo $user['user_id']; ?>"><?php echo $user['user_name']==NULL?"LTI User":$user['user_name']; ?></label></td>
			<td><?php echo str_replace("lti:", "", $user['email']); ?></td>
            <td><?php echo $user['last_login']; ?></td>
            <td><?php echo ($user['status'] == 1) ? "Enabled" : "Disabled"; ?></td>
		</tr>
	<?php }} // end of if (is_array($rows)) 
	else { ?>
	<tbody>
	<tr>
		<td colspan="7"><?php echo _AT('none_found'); ?></td>
	</tr>
<?php } // end of else ?>
</tbody>
</table>
</form>
