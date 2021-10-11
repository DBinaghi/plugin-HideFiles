# plugin-HideFiles
Plugin for Omeka Classic. Allows to hide specific files (for copyright or any other reason) from visitors and users

## Warning
This is a stub. I'm still adding checks to hide original file information on both Admin and Public side, so it should still be used to experiment, and not on production. 

Use it at your own risk.

It’s always recommended to backup your files and your databases and to check your archives regularly so you can roll back if needed.

## Core file changes
~~While developing the plugin, I've found out the files/edit page was not firing the two hooks `admin_files_panel_buttons` and `admin_files_panel_fields`; in order to use this plugin, one has to edit the core file `admin/themes/default/files/edit.php` as follows~~
Core code was fixed with https://github.com/omeka/Omeka/commit/c524e4de14741334586f74d58abd6350d72053c3 fix, so no core file change is needed now.

## ToDo
- Look for more efficient `hookAdminHead` coding (I’m using DOM there, jquery would have been more efficient but if I try to load it it gets commented out in the page’s head);
- Amend/improve column check in `_columnExists` function (name space clause is missing, as I could not find a way to get the db’s name);

## Troubleshooting
See online issues on the <a href="https://github.com/DBinaghi/plugin-HideFiles/issues" target="_blank">plugin issues</a> page on GitHub.

## License
This plugin is published under the <a href="https://www.cecill.info/licences/Licence_CeCILL_V2.1-en.html" target="_blank">CeCILL v2.1</a> licence, compatible with <a href="https://www.gnu.org/licenses/gpl-3.0.html" target="_blank">GNU/GPL</a> and approved by <a href="https://www.fsf.org/" target="_blank">FSF</a> and <a href="http://opensource.org/" target="_blank">OSI</a>.

In consideration of access to the source code and the rights to copy, modify and redistribute granted by the license, users are provided only with a limited warranty and the software’s author, the holder of the economic rights, and the successive licensors only have limited liability.

In this respect, the risks associated with loading, using, modifying and/or developing or reproducing the software by the user are brought to the user’s attention, given its Free Software status, which may make it complicated to use, with the result that its use is reserved for developers and experienced professionals having in-depth computer knowledge. Users are therefore encouraged to load and test the suitability of the software as regards their requirements in conditions enabling the security of their systems and/or data to be ensured and, more generally, to use and operate it in the same conditions of security. This Agreement may be freely reproduced and published, provided it is not altered, and that no provisions are either added or removed herefrom.

## Copyright
Copyright <a href="https://github.com/DBinaghi">Daniele Binaghi</a>, 2021
