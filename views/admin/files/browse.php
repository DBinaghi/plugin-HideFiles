<?php echo head(array('title' => __('Hidden Files'))); ?>
<?php echo flash(); ?>

<?php if (count($files)): ?>
	<p>
		<?php 
			echo __(plural('This is the file stored in the repository and hidden via the <strong>Hide Files</strong> plugin.', 'These are the %s files stored in the repository and hidden via the <strong>Hide Files</strong> plugin.', $total_results), '<strong>' . $total_results . '</strong>');
			echo '<br>';
			echo __('Click on a filename to access that specific file, or on an Item name to access the associated Item.');
		?>
	</p>
    <?php echo pagination_links(); ?>
	<div class="table-responsive">
		<table id="file-list">
			<thead>
				<tr>
					<?php
						echo browse_sort_links(
							array(
								__('Filename') => 'filename',
								__('Date Added') => 'added',
								__('Item') => 'item_id',
								__('Owner') => 'owner_id'
							),
							array('link_tag' => 'th scope="col"', 'list_tag' => '')
						);
					?>
				</tr>
			</thead>
			<tbody>
				<?php $key = 0; ?>
				<?php $showOriginalFilename = (bool)get_option(hide_files_show_original_filename); ?>
				<?php foreach ($files as $file): ?>
					<?php $item = get_record_by_id('item', $file->item_id); ?>
					<tr class="<?php echo (++$key%2 == 1 ? 'odd' : 'even'); ?>">
						<td>
							<?php
								echo file_image('square_thumbnail', array('class' => 'thumbnail'), $file);
								echo '<a href="' . url('/files/show/' . $file->id) . '" title="' . (!$showOriginalFilenametitle ? $file->original_filename : '') . '">' . ($showOriginalFilename ? $file->original_filename : $file->filename) . '</a>';
							?>
						</td>
						<td>
							<?php
								echo $file->added;
							?>
						</td>
						<td>
							<?php
								echo '<a href="' . url('/items/show/' . $file->item_id) . '">' . metadata($item, 'display_title', array('no_escape' => true, 'snippet' => 50)) . '</a>';
							?>
						</td>
						<td>
							<?php
								echo strip_formatting(metadata($item->getOwner(), 'name'));
							?>
						</td>
					</tr>
				<?php endforeach; ?>
			</tbody>
		</table>
	</div>
<?php else: ?>
	<p>
		<?php echo __('There seems to be no file stored in the repository and hidden via the <strong>Hide Files</strong> plugin.'); ?>
	</p>
<?php endif; ?>

<?php echo foot(); ?>
