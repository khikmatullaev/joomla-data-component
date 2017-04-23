<?php
defined('_JEXEC') or die('Restricted access');


/**
 * Methods supporting a list of email entities.
 */
class DataModelEmails extends JModelList
{
    /**
     * Constructor.
     *
     * @param   array  $config  An optional associative array of configuration settings.
     *
     * @see     JControllerLegacy
     */
    public function __construct($config = array())
    {
        if (empty($config['filter_fields']))
        {
            $config['filter_fields'] = array(
                'id', 'a.id',
                'email', 'a.email',
                'name', 'a.name',
                'access', 'a.access'
            );
        }

        parent::__construct($config);
    }

    /**
     * Build an SQL query to load the list data.
     *
     * @return  JDatabaseQuery
     */
    protected function getListQuery()
    {
        // Create a new query object.
        $db = $this->getDbo();
        $query = $db->getQuery(true);

        // Select the required fields from the table.
        $query->select(
            $this->getState(
                'list.select',
                'a.id, a.email, a.name, a.access')
        );

        $query->from('#__data AS a');

        // Join over the language
        $query->select('l.title AS language_title, l.image AS language_image')
            ->join('LEFT', $db->quoteName('#__languages') . ' AS l ON l.lang_code = a.language');

        // Join over the asset groups.
        $query->select('ag.title AS access_level')
            ->join('LEFT', '#__viewlevels AS ag ON ag.id = a.access');

        // Join over the users for the author.
        $query->select('ua.name AS author_name')
            ->join('LEFT', '#__users AS ua ON ua.id = a.created_by');

        // Filter by access level.
        if ($access = $this->getState('filter.access'))
        {
            $query->where('a.access = ' . (int) $access);
        }

        // Filter by published state
        $published = $this->getState('filter.published');

        if (is_numeric($published))
        {
            $query->where('a.state = ' . (int) $published);
        }
        else
        {
            $query->where('a.state = 1');
        }

        // Filter by author
        $authorId = $this->getState('filter.author_id');

        if (is_numeric($authorId))
        {
            $type = $this->getState('filter.author_id.include', true) ? '= ' : '<>';
            $query->where('a.created_by ' . $type . (int) $authorId);
        }

        // Filter on the language.
        if ($language = $this->getState('filter.language'))
        {
            $query->where('a.language = ' . $db->quote($language));
        }

        // Add the list ordering clause.
        $orderCol  = $this->state->get('list.fullordering', 'a.id');
        $orderDirn = '';

        if (empty($orderCol))
        {
            $orderCol  = $this->state->get('list.ordering', 'a.id');
            $orderDirn = $this->state->get('list.direction', 'DESC');
        }

        $query->order($db->escape($orderCol) . ' ' . $db->escape($orderDirn));

        return $query;
    }

    /**
     * Method to get a list of articles.
     * Overridden to add a check for access levels.
     *
     * @return  mixed  An array of data items on success, false on failure.
     */
    public function getItems()
    {
        $items = parent::getItems();

        if( JFactory::getApplication()->isClient('site') )
        {
            $groups = JFactory::getUser()->getAuthorisedViewLevels();

            for ($x = 0, $count = count($items); $x < $count; $x++)
            {
                // Check the access level. Remove emails the user shouldn't see
                if (!in_array($items[$x]->access, $groups))
                {
                    unset($items[$x]);
                }
            }
        }

        return $items;
    }
}