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
<br>
    <?php echo _AT('create_tool_text');?><b><a href="./oauth/ltiprovider_form.php"><?php echo _AT("create_lti_tool"); ?> </b> </a><br>
<br>
<form name="form" method="get" action="<?php echo $_SERVER['PHP_SELF']; ?>">
<table class="data" summary="" rules="cols">
<thead>
<tr>
	<th scope="col">&nbsp;</th>
	<th scope="col"><?php echo _AT('title'); ?></th>
	<th scope="col"><?php echo _AT('consumer_key'); ?></th>
    <th scope="col"><?php echo _AT('shared_secret');	  ?></th>
    <th scope="col"><?php echo _AT('url');	  ?></th>
    <th scope="col"><?php echo _AT('status');	  ?></th>
</tr>
</thead>

<?php  if (!empty($this->mytools)) {?>
	<tfoot>
	<tr>
		<td colspan="3">
			<input type="submit" name="edit" value="<?php echo _AT('edit'); ?>" />
            <input type="submit" name="delete" value="<?php echo _AT('delete'); ?>" />
            <input type="submit" name="edit_users" value="<?php echo _AT('edit_users'); ?>" />
		</td>
	</tr>
	</tfoot>
	<tbody>

	<?php foreach ($this->mytools as $tool) { ?>
		<tr>
			<td><input type="radio" name="id" value="<?php echo $tool['tool_id']; ?>" id="<?php echo $tool['tool_id']; ?>" /></td>
			<td><label for="<?php echo $tool['tool_id']; ?>"><?php echo $tool['course_title']; ?></label></td>
			<td><?php echo $tool['consumer_key']; ?></td>
            <td><?php echo $tool['shared_secret']; ?></td>
            <td><?php echo $tool['url'] ?></td>
            <td><?php echo ($tool['enabled'] == 1) ? "Enabled" : "Disabled"; ?></td>
		</tr>
	<?php }
    } else { ?>
	<tbody>
	<tr>
		<td colspan="7"><?php echo _AT('none_found'); ?></td>
	</tr>
<?php }
// end of else ?>
</tbody>
</table>
</form>
