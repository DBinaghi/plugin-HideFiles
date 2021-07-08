<?php

class HideFiles_FilesController extends Omeka_Controller_AbstractActionController
{
    const RECORDS_PER_PAGE_SETTING = 'records_per_page_setting';

    /**
     * The number of records to browse per page.
     * 
     * If this is left null, then results will not paginate. This is partially 
     * because not every controller will want to paginate records and also to 
     * avoid BC breaks for plugins.
     *
     * Setting this to self::RECORDS_PER_PAGE_SETTING will cause the
     * admin-configured page limits to be used (which is often what you want).
     *
     * @var string
     */
    protected $_browseRecordsPerPage = self::RECORDS_PER_PAGE_SETTING;

    /**
     * Controller-wide initialization. Sets the underlying model to use.
     */
    public function init()
    {
        // Sets the default table.
        $this->_helper->db->setDefaultModelName('File');
    }

    public function listAction()
    {
        // Respect only GET parameters when browsing.
        $this->getRequest()->setParamSources(array('_GET'));
		
        // Apply controller-provided default sort parameters
        if (!$this->_getParam('sort_field')) {
            $defaultSort = apply_filters("files_browse_default_sort",
                $this->_getBrowseDefaultSort(),
                array('params' => $this->getAllParams())
            );
            if (is_array($defaultSort) && isset($defaultSort[0])) {
                $this->setParam('sort_field', $defaultSort[0]);

                if (isset($defaultSort[1])) {
                    $this->setParam('sort_dir', $defaultSort[1]);
                }
            }
        }
		
		$params = $this->getAllParams();
        $recordsPerPage = $this->_getBrowseRecordsPerPage('files');
        $currentPage = $this->getParam('page', 1);

        // Get the records filtered to Omeka_Db_Table::applySearchFilters().
		$records = $this->_helper->db->findBy($params, $recordsPerPage, $currentPage);
        $totalRecords = $this->_helper->db->count($params);

        // Add pagination data to the registry. Used by pagination_links().
        if ($recordsPerPage) {
            Zend_Registry::set('pagination', array(
                'page' => $currentPage,
                'per_page' => $recordsPerPage,
                'total_results' => $totalRecords,
            ));
        }

        $this->view->assign(array('files' => $records, 'total_results' => $totalRecords));
    }
	
	/**
     * Return the number of records to display per page.
     *
     * By default this will read from the _browseRecordsPerPage property, which
     * in turn defaults to null, disabling pagination. This can be 
     * overridden in subclasses by redefining the property or this method.
     *
     * Setting the property to self::RECORDS_PER_PAGE_SETTING will enable
     * pagination using the admin-configued page limits.
     *
     * @param string|null $pluralName
     * @return int|null
     */
    protected function _getBrowseRecordsPerPage($pluralName = null)
    {
        $perPage = $this->_browseRecordsPerPage;

        // Use the user-configured page
        if ($perPage === self::RECORDS_PER_PAGE_SETTING) {
            $options = $this->getFrontController()->getParam('bootstrap')
                ->getResource('Options');

            if (is_admin_theme()) {
                $perPage = (int) $options['per_page_admin'];
            } else {
                $perPage = (int) $options['per_page_public'];
            }
        }

        // If users are allowed to modify the # of items displayed per page,
        // then they can pass the 'per_page' query parameter to change that.
        if ($this->_helper->acl->isAllowed('modifyPerPage')
            && ($queryPerPage = $this->getRequest()->get('per_page'))
        ) {
            $perPage = (int) $queryPerPage;
        }

        // Any integer zero or below disables pagination.
        if ($perPage < 1) {
            $perPage = null;
        }

        if ($pluralName) {
            $perPage = apply_filters("{$pluralName}_browse_per_page", $perPage,
                array('controller' => $this));
        }
        return $perPage;
    }

    /**
     * Return the default sorting parameters to use when none are specified.
     *
     * @return array|null Array of parameters, with the first element being the
     *  sort_field parameter, and the second (optionally) the sort_dir.
     */
    protected function _getBrowseDefaultSort()
    {
        return null;
    }
}
