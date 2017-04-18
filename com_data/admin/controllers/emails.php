<?php
defined('_JEXEC') or die('Restricted access');


/**
 * Emails list controller class.
 */
class DataControllerEmails extends JControllerAdmin
{
    /**
     * Proxy for getModel.
     *
     * @param   string  $name    The model name. Optional.
     * @param   string  $prefix  The class prefix. Optional.
     * @param   array   $config  The array of possible config values. Optional.
     *
     * @return  JModelLegacy
     */
	public function getModel($name = 'Email', $prefix = 'DataModel', $config = array('ignore_request' => true))
	{
		return parent::getModel($name, $prefix, $config);
	}
}
