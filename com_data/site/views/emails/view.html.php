<?php
defined('_JEXEC') or die('Restricted access');


/**
 * View class for a list of email entities.
 */
class DataViewEmails extends JViewLegacy
{
    /**
     * An array of items
     *
     * @var  array
     */
    protected $items;

    /**
     * The model state
     *
     * @var  object
     */
    protected $state;

    /**
     * The pagination object
     *
     * @var  JPagination
     */
    protected $pagination;

    /**
     * Header of view
     *
     * @var  array
     */
    protected $header;

    /**
     * Display the view
     *
     * @param   string  $tpl  The name of the template file to parse; automatically searches through the template paths.
     *
     * @return  mixed  A string if successful, otherwise an Error object.
     */
    public function display($tpl = null)
    {
        $this->items      = $this->get('Items');
        $this->pagination = $this->get('Pagination');
        $this->state      = $this->get('State');
        $this->header     = "List of emails which acceptable to see for current user";

        return parent::display($tpl);
    }
}
