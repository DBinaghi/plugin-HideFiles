<?php 
	$view = get_view();
?>

<h2><?php echo __('Scope'); ?></h2>

<div class="field">
	<div class="two columns alpha">
		<?php echo $view->formLabel('hide_files_restrict_users_access', __('Restrict Users Access')); ?>
	</div>
	<div class="inputs five columns omega">
		<p class="explanation">
			<?php echo __('If checked, files will be hidden also from most logged-in users (only owner and Administrators will be able to see them).'); ?>
		</p>
		<?php echo $view->formCheckbox('hide_files_restrict_users_access', get_option('hide_files_restrict_users_access'), array(), array('1', '0')); ?>
	</div>
</div>

<div class="field">
	<div class="two columns alpha">
		<?php echo $view->formLabel('hide_files_public_side_hide', __('Hide on Public Side')); ?>
	</div>
	<div class="inputs five columns omega">
		<p class="explanation">
			<?php echo __('If checked, files will always be hidden on Public side, regardless of whether the user is logged-in or not.'); ?>
		</p>
		<?php echo $view->formCheckbox('hide_files_public_side_hide', get_option('hide_files_public_side_hide'), array(), array('1', '0')); ?>
	</div>
</div>

<h2><?php echo __('File List'); ?></h2>

<div class="field">
	<div class="two columns alpha">
		<?php echo $view->formLabel('hide_files_show_file_list', __('Show Hidden Files List')); ?>
	</div>
	<div class="inputs five columns omega">
		<p class="explanation">
			<?php echo __('If checked, a link will be added to the main Admin navigation menu to show a list of all hidden files.'); ?>
		</p>
		<?php echo $view->formCheckbox('hide_files_show_file_list', get_option('hide_files_show_file_list'), array(), array('1', '0')); ?>
	</div>
</div>

<div class="field">
	<div class="two columns alpha">
		<?php echo $view->formLabel('hide_files_expand_access_file_list', __('Expand Access')); ?>
	</div>
	<div class="inputs five columns omega">
		<p class="explanation">
			<?php echo __('If checked, access to the File List will be granted to all logged-in users.'); ?>
		</p>
		<?php echo $view->formCheckbox('hide_files_expand_access_file_list', get_option('hide_files_expand_access_file_list'), array(), array('1', '0')); ?>
	</div>
</div>

<div class="field">
	<div class="two columns alpha">
		<?php echo $view->formLabel('hide_files_show_original_filename', __('Show Original Filename')); ?>
	</div>
	<div class="inputs five columns omega">
		<p class="explanation">
			<?php echo __('If checked, the original filenames will be shown in the File List.'); ?>
		</p>
		<?php echo $view->formCheckbox('hide_files_show_original_filename', get_option('hide_files_show_original_filename'), array(), array('1', '0')); ?>
	</div>
</div>
