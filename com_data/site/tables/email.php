<?php
defined('_JEXEC') or die('Restricted access');


/**
 * Email Table class.
 */
class DataTableEmail extends JTable
{
    /**
     * Constructor
     *
     * @param   JDatabaseDriver  &$db  Database connector object
     */
    public function __construct(&$db)
    {
        parent::__construct('#__data', 'id', $db);
        $this->_columnAlias = array('published' => 'state');
    }
}