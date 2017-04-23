<?php
defined('_JEXEC') or die('Restricted access');


/**
 * Component Controller
 */
class DataController extends JControllerLegacy
{
    /**
     * The default view.
     *
     * @var    string
     */
    protected $default_view = 'emails';

    /**
     * Method to display a view.
     *
     * @param   boolean  $cachable   If true, the view output will be cached
     * @param   array    $urlparams  An array of safe URL parameters and their variable types, for valid values see {@link JFilterInput::clean()}.
     *
     * @return  DataController  This object to support chaining.
     */
    public function display($cachable = false, $urlparams = array())
    {
        $view   = $this->input->get('view', 'emails');
        $layout = $this->input->get('layout', 'emails');
        $id     = $this->input->getInt('id');

        // Check for edit form.
        if ($view == 'email' && $layout == 'edit' && !$this->checkEditId('com_data.edit.email', $id))
        {
            // Somehow the person just went to the form - we don't allow that.
            $this->setError(JText::sprintf('JLIB_APPLICATION_ERROR_UNHELD_ID', $id));
            $this->setMessage($this->getError(), 'error');
            $this->setRedirect(JRoute::_('index.php?option=com_data&view=emails', false));

            return false;
        }

        return parent::display();
    }

}