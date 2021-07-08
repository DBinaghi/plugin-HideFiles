<?php

/**
 * Hide Files plugin for Omeka
 * 
 * @version $Id$
 * @license http://www.gnu.org/licenses/gpl-3.0.txt
 * @copyright Daniele Binaghi, 2021
 * @package HideFiles
 */

define('HIDEFILES_THUMBNAIL', '/plugins/HideFiles/views/shared/images/file_hidden.png');
define('HIDEFILES_REPLACEMENT_STRING', __('Hidden by Hide Files plugin'));

class HideFilesPlugin extends Omeka_Plugin_AbstractPlugin
{
	protected $_hooks = array(
		'install',
		'uninstall',
		'uninstall_message',
		'initialize',
		'config',
		'config_form',
		'admin_head',
		'public_head',
		'admin_files_show_sidebar',
		'admin_files_panel_buttons',
		'after_save_file',
		'files_browse_sql'
	);

	protected $_filters = array(
		'admin_navigation_main',
		'file_markup',
		'file_markup_options',
		'image_tag_attributes'
	);

	public function hookInstall()
	{
		if (!self::_columnExists('public')) {
			$db = get_db();
			$db->query("ALTER TABLE {$db->File} ADD COLUMN `public` TinyInt(4) DEFAULT 1");
			$db->query("UPDATE {$db->File} SET `public` = 1");
		}

		set_option('hide_files_restrict_users_access', 1);
		set_option('hide_files_public_side_hide', 0);
		set_option('hide_files_show_files_list', 1);
	}

	public function hookUninstall()
	{
		if (self::_columnExists('public')) {
			$db = get_db();
			$db->query("ALTER TABLE {$db->File} DROP COLUMN `public`");
		}

		delete_option('hide_files_restrict_users_access');
		delete_option('hide_files_public_side_hide');
		delete_option('hide_files_show_files_list');
	}
	
	/**
	 * Display the uninstall message.
	 */
	public function hookUninstallMessage()
	{
		echo '<p>' . __('%sWarning%s: this will remove all information added by this plugin, 
				thus files will no more be hidden.', '<strong>', '</strong>') . '</p>';
	}

	public function hookInitialize()
	{
		add_translation_source(dirname(__FILE__) . '/languages');
	}
	
	public function hookConfig($args)
	{
		$post = $args['post'];
		set_option('hide_files_restrict_users_access', $post['hide_files_restrict_users_access']);
		set_option('hide_files_public_side_hide', $post['hide_files_public_side_hide']);
		set_option('hide_files_show_files_list', $post['hide_files_show_files_list']);
	}
	
	public function hookConfigForm()
	{
		include 'config_form.php';
	}
	
	public function hookAdminHead($args)
	{
		$request = Zend_Controller_Front::getInstance()->getRequest();
		$controller = $request->getControllerName();
		$action = $request->getActionName();
		if ($controller == 'files') {
			$file = get_current_record('file', false);
			if ($action == 'show') {
				if ($this->_isFileHidden($file)) {
					queue_js_string("
						document.addEventListener('DOMContentLoaded', function() {
							var children = document.getElementById('edit').children;
							for (var i = 0; i < children.length; i++) {
								var child = children[i];
								if (child.href.indexOf('admin/files/edit') > -1) {
									child.remove();
								}
							}
							
							var panel = document.getElementById('file-links');
							if (panel.hasChildNodes()) {
								panel.querySelector('ul').outerHTML = '<p style=\'color:red\'>" . HIDEFILES_REPLACEMENT_STRING . "</p>';
							}
							
							var panel = document.getElementById('format-metadata').childNodes[3];
							if (panel.hasChildNodes()) {
								panel.childNodes[3].innerHTML = '<span style=\'color:red\'>" . HIDEFILES_REPLACEMENT_STRING . "</span>';
							}

							var ulist = document.getElementById('output-format-list');
							ulist.outerHTML = '<p style=\'color:red\'>" . HIDEFILES_REPLACEMENT_STRING . "</p>';
						}, false);
					");
				}	
			} elseif ($action == 'edit') {
				if ($this->_isFileHidden($file)) {
					// blocks access to admin file edit page, showing show page instead
					queue_js_string("window.location.href = '" . url('files/show/' . $file->id) . "'");
				}				
			}
		} elseif ($controller == 'items') {
			if ($action == 'edit') {
				// removes "edit" link from hidden files in items/edit files tab
				queue_js_string("
					document.addEventListener('DOMContentLoaded', function() {
						var link = document.getElementById('file-list').getElementsByClassName('edit');
						while (link.length > 0) {
							link[0].parentNode.remove();
						}
					}, false);
				");	
			}
		}
	}

	public function hookPublicHead($args)
	{
		$request = Zend_Controller_Front::getInstance()->getRequest();
		$controller = $request->getControllerName();
		$action = $request->getActionName();

		if ($controller == 'files' && $action == 'show') {
			$file = get_current_record('file', false);
			if ($this->_isFileHidden($file)) {
				// blocks access to public file show page, showing item page instead
				queue_js_string("window.location.href = '" . url('items/show/' . $file->item_id) . "'");
 			}
		}
	}

	public function filterAdminNavigationMain($nav)
	{
		if ((bool)get_option('hide_files_show_files_list')) {
			$user = current_user();
			$nav[] = array(
				'label' => __('Hidden Files'),
				'uri' => url('hide-files/files/list'),
			);
		}
		
		return $nav;
	}
	
	public function hookAdminFilesShowSidebar($args)
	{
		$args['record'] = $args['file'];
		$this->_adminRecordsShowSidebar($args);
	}
	
	protected function _adminRecordsShowSidebar($args)
	{
		$html = '<div class="public-featured panel">';
		$html .= '<p><span class="label">' . __('Public') . ': </span>' . ($this->_isFilePublic($args['file']) ? __('Yes') : __('No')) . '</p>';
		$html .= '</div>';

 		// moves panel just under edit buttons
		$html .= '<script>jQuery(".public-featured").insertAfter(jQuery("#edit"));</script>';

		echo $html;
	}
	
	public function hookAdminFilesPanelButtons($args)
	{
		$this->_adminRecordsPanelButtons($args);
	}
	
	protected function _adminRecordsPanelButtons($args)
	{
		$view = $args['view'];
		
		$html  = '<div id="public-featured">';
		if (is_allowed('Items', 'makePublic') ):
			$html .= '<div class="public">';
			$html .= '<label for="public">' . __('Public') . ':</label>';
			$html .= $view->formCheckbox('public', $this->_isFilePublic($args['record']), array(), array('1', '0'));
			$html .= '</div>';
		endif;
		$html .= '</div> <!-- end public-featured  div -->';
		
		// moves panel just under edit buttons
		$html .= '<script>jQuery("#public-featured").insertAfter(jQuery("#edit"));</script>';

		echo $html;
	}
		
	protected function _isFilePublic($file)
	{
		$db = get_db();
		$sql = "SELECT public FROM `{$this->_db->File}` WHERE `id` = " . $file->id;
		$results = $db->fetchCol($sql);

		return $results[0];
	}
		
	protected function _isFileHidden($file)
	{
		// checks if file is public and everyone can see it
		if ($this->_isFilePublic($file)) {
			return false;
		} else {
			if (!is_admin_theme() && (bool)get_option('hide_files_public_side_hide')) {
				// if it's public side and files have to be hidden nonetheless
				return true;
			} elseif ((bool)get_option('hide_files_restrict_users_access')) {
				// if access is restricted, checks whether current user is
				// either owner or administrator
				$item_id = metadata($file, 'item_id');
				$item = get_record_by_id('Item', $item_id);
				if ($item->getOwner()->id == current_user()->id || is_allowed('Plugins', 'edit')) {
					return false;
				}
			} elseif (current_user()->id != '') {
				// if access is not restricted, checks whether user is
				// currently logged in
				return false;
			}
		}

		return true;	
	}
	
	public function filterFileMarkup($html, $args)
	{
		$file = $args['file'];
		if ($this->_isFileHidden($file)) {
			if (strpos($html, HIDEFILES_THUMBNAIL) < 0) {
				// replaces thumbnail with plugins' one
				$pattern = "!(?<=src\=['\"]).+(?=['\"](\s|\/\>))!";
				$html = preg_replace($pattern, HIDEFILES_THUMBNAIL, $html);
			} else {
				// removes link to original file
				$pattern = "!href\s*=\s*(['\"])(https?:\/\/.+?)(['\"])!";
				$html = preg_replace($pattern, 'href=$1$1', $html);
			}
		}

		return $html;
	}
	
	public function filterFileMarkupOptions($options, $args)
	{
		$file = $args['file'];
		if ($this->_isFileHidden($file)) {
			$options['linkToFile'] = false;
		}
		
		return $options;
	}
	
	public function filterImageTagAttributes($attrs, $args)
	{
		$file = $args['file'];
		if ($this->_isFileHidden($file)) {
			$attrs['src'] = HIDEFILES_THUMBNAIL;
		}
		
		return $attrs;
	}
	
	public function hookAfterSaveFile($args) 
	{
		$post = $args['post'];
		if (!isset($post['public'])) return;
		$isPublic = $post['public'];
	
		$db = get_db();
		$sql = "UPDATE `{$this->_db->File}` SET `public` = " . $isPublic . " WHERE `id` = " . $args['record']->id;
		$db->query($sql);
		
		$flash = Zend_Controller_Action_HelperBroker::getStaticHelper('FlashMessenger');
		if ($isPublic) {
			$message = __('The file is now Public, so any visitor and user can see it.');
		} else {
			$message = __('Access to the file has now been restricted via the Hide Files plugin.');
		}
		$flash->addMessage($message, 'alert');
	}

	public function hookFilesBrowseSql($args) 
	{
		// Filters out all public files and sort results by added date
		$select = $args['select'];
		$select->where("files.public = ?", 0);
		$select->order("files.added DESC");
	}
	
	protected function _columnExists($columnName)
	{
		$db = get_db();
		$sql = "
			SELECT COLUMN_NAME
			FROM INFORMATION_SCHEMA.COLUMNS
			WHERE TABLE_NAME = '{$this->_db->File}';
		";
		$result = $db->fetchCol($sql);
		return in_array($columnName, $result);
	}
}
