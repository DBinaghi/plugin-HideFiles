<?php echo head(array('title' => __('Hidden Files'))); ?>
<?php echo flash(); ?>

<?php if (count($files)): ?>
	<p>
		<?php 
			echo __('This is a list of all %s files stored in the repository and hidden via the <b>Hide Files</b> plugin.', '<b>' . $total_results . '</b>'); echo '<br>';
			echo __('Click on a filename to access that specific file, or on an Item name to access the associated Item.');
		?>
	</p>
    <?php echo pagination_links(); ?>
 	<table id="file-list">
		<thead>
			<tr>
                <?php
					$sortLinks = array(
						__('Filename') => 'filename',
						__('Date Added') => 'added',
						__('Item') => 'item_id'
					);
                ?>
                <?php echo browse_sort_links($sortLinks, array('link_tag' => 'th scope="col"', 'list_tag' => '')); ?>
			</tr>
		</thead>
		<tbody>
			<?php $key = 0; ?>
			<?php foreach ($files as $file): ?>
				<tr class="<?php echo (++$key%2 == 1 ? 'odd' : 'even'); ?>">
					<td>
						<?php
							echo file_image('square_thumbnail', array(), $file);
							echo '<a href="' . url('/files/show/' . $file->id) . '">' . $file->original_filename . '</a>';
						?>
					</td>
					<td>
						<?php
							echo $file->added;
						?>
					</td>
					<td>
						<?php
							$item = get_record_by_id('item', $file->item_id);
							echo '<a href="' . url('/items/show/' . $file->item_id) . '">' . metadata($item, 'display_title', array('no_escape' => true)) . '</a>';
						?>
					</td>
				</tr>
			<?php endforeach; ?>
		</tbody>
	</table>
<?php else: ?>
	<p>
		<?php echo __('There seems to be no file stored in the repository and hidden via the <b>Hide Files</b> plugin.'); ?>
	</p>
<?php endif; ?>

<?php echo foot(); ?>
