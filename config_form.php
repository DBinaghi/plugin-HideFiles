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
			<?php echo __('If checked, plugin features will be applied also to most logged-in users (a hidden file will be viewable only by its owner and by Administrators).'); ?>
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

<h2><?php echo __('Admin'); ?></h2>

<div class="field">
	<div class="two columns alpha">
		<?php echo $view->formLabel('hide_files_show_files_list', __('Show Hidden Files List')); ?>
	</div>
	<div class="inputs five columns omega">
		<p class="explanation">
			<?php echo __('If checked, a link will be added to the main Admin navigation menu to show a list of all hidden files.'); ?>
		</p>
		<?php echo $view->formCheckbox('hide_files_show_files_list', get_option('hide_files_show_files_list'), array(), array('1', '0')); ?>
	</div>
</div>

<div class="field">
	<div class="two columns alpha">
		<?php echo $view->formLabel('hide_files_show_original_filename', __('Show Original Filename')); ?>
	</div>
	<div class="inputs five columns omega">
		<p class="explanation">
			<?php echo __('If checked, when browsing hidden files the original filename will be shown.'); ?>
		</p>
		<?php echo $view->formCheckbox('hide_files_show_original_filename', get_option('hide_files_show_original_filename'), array(), array('1', '0')); ?>
	</div>
</div>
