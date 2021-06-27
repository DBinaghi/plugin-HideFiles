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
			<?php echo __('If checked, plugin feature will be applied also to most logged in users (a hidden file will be viewable only by Administrators and its owner).'); ?>
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
			<?php echo __('If checked, files will be always hidden on Public side, regardless of whether the user is logged in or not (useful to make sure that files are actually marked as private).'); ?>
		</p>
		<?php echo $view->formCheckbox('hide_files_public_side_hide', get_option('hide_files_public_side_hide'), array(), array('1', '0')); ?>
	</div>
</div>

<h2><?php echo __('Admin'); ?></h2>

<div class="field">
	<div class="two columns alpha">
		<?php echo $view->formLabel('hide_files_show_files_list', __('Show Files List')); ?>
	</div>
	<div class="inputs five columns omega">
		<p class="explanation">
			<?php echo __('If checked, a new link will be added to the main Admin navigation menu, to show a list of the hidden files. (feature not available yet)'); ?>
		</p>
		<?php echo $view->formCheckbox('hide_files_show_files_list', get_option('hide_files_show_files_list'), array(), array('1', '0')); ?>
	</div>
</div>