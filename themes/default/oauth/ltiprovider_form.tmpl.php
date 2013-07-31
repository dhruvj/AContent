<?php 
/************************************************************************/
/* AContent                                                             */
/************************************************************************/
/* Copyright (c) 2010                                                   */
/* Inclusive Design Institute                                           */
/*                                                                      */
/* This program is free software. You can redistribute it and/or        */
/* modify it under the terms of the GNU General Public License          */
/* as published by the Free Software Foundation.                        */
/************************************************************************/
?>

<form method="post" action="<?php echo $_SERVER['PHP_SELF']; if ($this->isedit == 1) echo "?edit=Edit&id=".$this->tool['tool_id'] ?>" name="form">

<div class="input-form">
<fieldset class="group_form"><legend class="group_form"><?php echo _AT('edit_profile'); ?></legend>

	<table class="form-data" align="center">
		<tr align="center"><td>
		<table>
		<tr>
			<td colspan="2" align="left"><?php echo _AT('required_field_text') ;?></td>
		</tr>

		<tr><td><br /></td></tr>

		<tr>
			<th align="left"><span class="required" title="<?php echo _AT('required_field'); ?>">*</span><label for="shared_secret"><?php echo _AT('shared_secret'); ?></label>:</th>
			<td align="left"><input id="shared_secret" name="shared_secret" type="text" size="60" value="<?php echo $this->tool['shared_secret']; ?>" /><?php echo _AT('LTI_KEY_RANGE'); ?></td>
		</tr>

		<tr>
			<th align="left"><span class="required" title="<?php echo _AT('required_field'); ?>">*</span><label for="consumer_key"><?php echo _AT('consumer_key'); ?></label>:</th>
			<td align="left"><input id="consumer_key" name="consumer_key" type="text" size="60" value="<?php echo $this->tool['consumer_key']; ?>" /><?php echo _AT('LTI_KEY_RANGE'); ?></td>
		</tr>
    
		<tr>
			<th align="left"><span class="required" title="<?php echo _AT('required_field'); ?>">*</span><?php echo _AT('course_selected'); ?>:</th>
			<td>
			<?php
				foreach ($this->my_courses as $mycourse) {
			?>
				<input id="course_<?php echo $mycourse['course_id'];?>" name="course_id" type="radio" <?php if ($mycourse['course_id'] == $this->course_id || $this->tool['course_id'] == $mycourse['course_id']) echo "checked";?> value="<?php echo $mycourse['course_id']; ?>" /><label for="course_<?php echo $mycourse['course_id'];?>"><?php echo $mycourse['title']; ?></label>
				<br>	
			<?php
				}
			?>
            </td>
        </tr>
		<tr>
			<th align="left"><span class="required" title="<?php echo _AT('required_field'); ?>">*</span><label for="max_enrollments"><?php echo _AT('max_enrollments'); ?></label>:</th>
			<td align="left"><input id="max_enrollments" name="max_enrollments" type="text" value="<?php echo $this->tool['max_enrollments'] ?>"/><?php echo _AT('zero_infinite'); ?></td>
		</tr>
		<tr>
			<th align="left"><label for="default_city"><?php echo _AT('default_city'); ?></label>:</th>
			<td align="left"><input id="default_city" name="default_city" type="text" value = "<?php echo $this->tool['default_city'] ?>" /></td>
		</tr>
		<tr>
			<th align="left"><label for="default_country"><?php echo _AT('default_country'); ?></label>:</th>
			<td align="left"><input id="default_country" name="default_country" type="text" value = "<?php echo $this->tool['default_country'] ?>" /></td>
		</tr>
        <tr>
			<th align="left"><label for="enabled"><?php echo _AT('enabled'); ?></label>:</th>
			<td align="left"><input id="enabled" name="enabled" type="checkbox" <?php if ($this->tool['enabled'] == 1) echo "checked";?> /></td>
		</tr>
		</table>
		</td></tr>
		
		<tr align="center"><td>
		
		</td></tr>
		
		<tr align="center"><td>
		<table>
		<tr>
			<td colspan="2">
			<p class="submit_button">
				<input type="submit" name="submit" value="<?php echo _AT('save'); ?>" class="submit" /> 
				<input type="submit" name="cancel" value=" <?php echo _AT('cancel'); ?> "  class="submit" />
			</p>
			</td>
		</tr>
		</table>
		</td></tr>
	</table>
</fieldset>

</div>
</form>