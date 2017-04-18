<?php
defined('_JEXEC') or die('Restricted access');


/**
 * View to edit an email entity.
 */
class DataViewEmail extends JViewLegacy
{
    /**
     * The JForm object
     *
     * @var  JForm
     */
    protected $form;

    /**
     * The active item
     *
     * @var  object
     */
    protected $item;

    /**
     * The model state
     *
     * @var  object
     */
    protected $state;

    /**
     * The actions the user is authorised to perform
     *
     * @var  JObject
     */
    protected $canDo;

    /**
     * Execute and display a template script.
     *
     * @param   string  $tpl  The name of the template file to parse; automatically searches through the template paths.
     *
     * @return  mixed  A string if successful, otherwise an Error object.
     */
    public function display($tpl = null)
    {
        if ($this->getLayout() == 'pagebreak')
            return parent::display($tpl);

        $this->form  = $this->get('Form');
        $this->item  = $this->get('Item');
        $this->state = $this->get('State');
        $this->canDo = JHelperContent::getActions('com_data', 'email', $this->item->id);

        // Check for errors.
        if (count($errors = $this->get('Errors')))
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
        JFactory::getApplication()->input->set('hidemainmenu', true);

        $user       = JFactory::getUser();
        $userId     = $user->id;
        $isNew      = ($this->item->id == 0);
        $checkedOut = !($this->item->checked_out == 0 || $this->item->checked_out == $userId);

        // Built the actions for new and existing records.
        $canDo = $this->canDo;

        JToolbarHelper::title(
            JText::_('COM_DATA_PAGE_' . ($checkedOut ? 'VIEW_EMAIL' : ($isNew ? 'ADD_EMAIL' : 'EDIT_EMAIL'))),
            'pencil-2 email-add'
        );

        // For new records, check the create permission.
        if ($isNew && (count($user->getAuthorisedCategories('com_data', 'core.create')) > 0))
        {
            JToolbarHelper::apply('email.apply');
            JToolbarHelper::save('email.save');
            JToolbarHelper::save2new('email.save2new');
            JToolbarHelper::cancel('email.cancel');
        }
        else
        {
            // Since it's an existing record, check the edit permission, or fall back to edit own if the owner.
            $itemEditable = $canDo->get('core.edit') || ($canDo->get('core.edit.own') && $this->item->created_by == $userId);

            // Can't save the record if it's checked out and editable
            if (!$checkedOut && $itemEditable)
            {
                JToolbarHelper::apply('email.apply');
                JToolbarHelper::save('email.save');

                // We can save this record, but check the create permission to see if we can return to make a new one.
                if ($canDo->get('core.create'))
                {
                    JToolbarHelper::save2new('email.save2new');
                }
            }

            // If checked out, we can still save
            if ($canDo->get('core.create'))
            {
                JToolbarHelper::save2copy('email.save2copy');
            }

            JToolbarHelper::cancel('email.cancel', 'JTOOLBAR_CLOSE');
        }

        JToolbarHelper::divider();
        JToolbarHelper::help('JHELP_DATA_EMAIL_MANAGER_EDIT');
    }
}