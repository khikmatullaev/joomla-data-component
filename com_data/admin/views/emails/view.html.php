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
     * Display the view
     *
     * @param   string  $tpl  The name of the template file to parse; automatically searches through the template paths.
     *
     * @return  mixed  A string if successful, otherwise an Error object.
     */
    public function display($tpl = null)
    {
        $this->items         = $this->get('Items');
        $this->state         = $this->get('State');
        $this->pagination    = $this->get('Pagination');

        // Check for errors.
        if( count($errors = $this->get('Errors')) )
        {
            JError::raiseError(500, implode("\n", $errors));
            return false;
        }

        $this->addToolbar();
        return parent::display($tpl);
    }

    /**
     * Add the page title and toolbar.
     *
     * @return  void
     */
    protected function addToolbar()
    {
        $canDo = JHelperContent::getActions('com_data', 'component', $this->state->get('filter.category_id'));
        $user  = JFactory::getUser();

        JToolbarHelper::title(JText::_('COM_DATA_EMAILS_TITLE'));

        if ($canDo->get('core.create') || (count($user->getAuthorisedCategories('com_data', 'core.create'))) > 0)
        {
            JToolbarHelper::addNew('email.add');
        }

        if (($canDo->get('core.edit')) || ($canDo->get('core.edit.own')))
        {
            JToolbarHelper::editList('email.edit');
        }

        if ($canDo->get('core.edit.state'))
        {
            JToolbarHelper::publish('emails.publish', 'JTOOLBAR_PUBLISH', true);
            JToolbarHelper::unpublish('emails.unpublish', 'JTOOLBAR_UNPUBLISH', true);
            JToolbarHelper::archiveList('emails.archive');
        }

        if ($this->state->get('filter.published') == -2 && $canDo->get('core.delete'))
        {
            JToolbarHelper::deleteList('JGLOBAL_CONFIRM_DELETE', 'emails.delete', 'JTOOLBAR_EMPTY_TRASH');
        }
        elseif ($canDo->get('core.edit.state'))
        {
            JToolbarHelper::trash('emails.trash');
        }
    }
}