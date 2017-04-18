<?php
// No direct access to this file
defined('_JEXEC') or die('Restricted access');


/**
 * Item Model for an Email.
 */
class DataModelEmail extends JModelAdmin
{
    /**
     * The prefix to use with controller messages.
     *
     * @var    string
     */
    protected $text_prefix = 'COM_DATA';

    /**
     * Method to get the record form.
     *
     * @param   array    $data      Data for the form.
     * @param   boolean  $loadData  True if the form is to load its own data (default case), false if not.
     *
     * @return  JForm|boolean  A JForm object on success, false on failure
     */
    public function getForm($data = array(), $loadData = true)
    {
        // Get the form.
        return $this->loadForm('com_data.email', 'email', array('control' => 'jform', 'load_data' => $loadData));
    }

    /**
     * Method to get the data that should be injected in the form.
     *
     * @return  mixed  The data for the form.
     */
    protected function loadFormData()
    {
        // Check the session for previously entered form data.
        $app = JFactory::getApplication();
        $data = $app->getUserState('com_data.edit.email.data', array());

        if (empty($data))
        {
            $data = $this->getItem();

            // Pre-select some filters (Status, Category, Language, Access) in edit form if those have been selected in Article Manager: Articles
            if ($this->getState('email.id') == 0)
            {
                $filters = (array) $app->getUserState('com_data.emails.filter');
                $data->set(
                    'state',
                    $app->input->getInt(
                        'state',
                        ((isset($filters['published']) && $filters['published'] !== '') ? $filters['published'] : null)
                    )
                );

                $data->set('language', $app->input->getString('language', (!empty($filters['language']) ? $filters['language'] : null)));
                $data->set('access',
                    $app->input->getInt('access', (!empty($filters['access']) ? $filters['access'] : JFactory::getConfig()->get('access')))
                );
            }
        }

        // If there are params fieldsets in the form it will fail with a registry object
        if (isset($data->params) && $data->params instanceof Registry)
        {
            $data->params = $data->params->toArray();
        }

        $this->preprocessData('com_data.email', $data);

        return $data;
    }

    /**
     * Method to save the form data.
     *
     * @param   array  $data  The form data.
     *
     * @return  boolean  True on success.
     */
    public function save($data)
    {
        if( empty($data['created']) )
        {
            // Create new email entity with created time and author
            $data['created'] = date('Y-m-d H:i:s');
            $data['created_by'] = JFactory::getUser()->get('id');

        }else{

            // Save the user who changed the email entity
            $data['modified_by'] = JFactory::getUser()->get('id');
        }

        // Save the modify time for existed email entity
        $data['modified'] = date('Y-m-d H:i:s');

        return parent::save($data);
    }

    /**
     * Returns a Table object, always creating it.
     *
     * @param   string  $type    The table type to instantiate
     * @param   string  $prefix  A prefix for the table class name. Optional.
     * @param   array   $config  Configuration array for model. Optional.
     */
    public function getTable($type = 'Email', $prefix = 'DataTable', $config = array())
    {
        return JTable::getInstance($type, $prefix, $config);
    }
}
