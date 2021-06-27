# plugin-HideFiles
Plugin for Omeka Classic. Allows to hide specific files (for copyright or any other reason) from visitors and users

# Warning
This is a stub. I'm still adding checks to hide original file information on both Admin and Public side, so it should still be used to experiment, and not on production. Use it at your own risk.

# ToDo
- remove File Edit links for hidden files from items/edit page, when needed (admin side);
- block/limit access to files/edit page (admin side); at least, make it impossible to change the public/private flag to those who are not supposed to do it;
- look for more efficient hookAdminHead coding (I’m using DOM there, jquery would have been more efficient but if I try to load it it gets commented out in the page’s head);
- amend/improve column check in _columnExists function (name space clause is missing, as I could not find a way to get the db’s name);
- add hidden files list to admin interface (when required in config)

# Core file changes
While developing the plugin, I've found out the files/edit page was not firing the two hooks `admin_files_panel_buttons` and `admin_files_panel_fields`, so one has to edit the core file (`admin/themes/default/files/edit.php`) as follows
  
```
  <section class="three columns omega">
        <div id="save" class="panel">
            <input type="submit" name="submit" class="submit big green button" value="<?php echo __('Save Changes'); ?>" id="file_edit" /> 
            <?php if (is_allowed('Files', 'delete')): ?>
                <?php echo link_to($file, 'delete-confirm', __('Delete'), array('class' => 'big red button delete-confirm')); ?>
            <?php endif; ?>
            <?php fire_plugin_hook("admin_files_panel_buttons", array('view'=>$this, 'record'=>$file)); ?>
            <?php fire_plugin_hook("admin_files_panel_fields", array('view'=>$this, 'record'=>$file)); ?>
        </div>
    </section>
```
