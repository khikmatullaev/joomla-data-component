<?php
defined('_JEXEC') or die('Restricted access');


/**
 * The single email entity controller
 */
class DataControllerEmail extends JControllerForm
{
    /**
     * Method override to check if you can edit an existing record.
     *
     * @param   array   $data  An array of input data.
     * @param   string  $key   The name of the key for the primary key.
     *
     * @return  boolean
     */
	protected function allowEdit($data = array(), $key = 'id')
	{
		$recordId = (int) isset($data[$key]) ? $data[$key] : 0;
		$user = JFactory::getUser();

		// Zero record (id:0), return component edit permission by calling parent controller method
		if( !$recordId )
			return parent::allowEdit($data, $key);

		// Check edit on the record asset (explicit or inherited)
		if( $user->authorise('core.edit', 'com_data.email.' . $recordId) )
			return true;

		// Check edit own on the record asset (explicit or inherited)
		if( $user->authorise('core.edit.own', 'com_data.email.' . $recordId) )
		{
			// Existing record already has an owner, get it
			$record = $this->getModel()->getItem($recordId);

			if( empty($record) )
				return false;

			// Grant if current user is owner of the record
			return $user->id == $record->created_by;
		}

		return false;
	}
}
