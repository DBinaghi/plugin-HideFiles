# plugin-HideFiles
Plugin for Omeka Classic. Allows to hide specific files (for copyright or any other reason) from visitors and users

# Warning
This is a stub. I'm still adding checks to hide original file information on both Admin and Public side, so it should still be used to experiment, and not on production. Use it at your own risk.

# ToDo
- remove File Edit links from Item Edit page (admin side)
- block/limit access to file edit page (admin side); at least, make it impossible to change the public/private flag to those who are not supposed to do it;
- search for neater coding for hookAdminHead
- amend/improve column check in _columnExists (name space is still missing)
- add hidden files list to admin interface (when required in config)
