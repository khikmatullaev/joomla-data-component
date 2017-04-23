<?php
defined('_JEXEC') or die;


$user      = JFactory::getUser();
$userId    = $user->get('id');
$columns   = 10;

$orderingColumn = 'created';
?>

<form action="<?php echo JRoute::_('index.php?option=com_data&view=emails'); ?>" method="post" name="adminForm" id="adminForm">
    <div id="j-main-container">
        <?php if (empty($this->items)) : ?>
            <div class="alert alert-no-items">
                <?php echo JText::_('JGLOBAL_NO_MATCHING_RESULTS'); ?>
            </div>
        <?php else : ?>
            <table class="table table-striped" id="emailList">
                <thead>
                <tr>
                    <th width="1%" class="center">
                        <?php echo JHtml::_('grid.checkall'); ?>
                    </th>
                    <th width="1%" class="nowrap center">
                        <?php echo JText::_('JSTATUS');?>
                    </th>
                    <th style="min-width:100px" class="nowrap">
                        <?php echo JText::_('COM_DATA_FIELD_EMAILTEXT_LABEL');?>
                    </th>
                    <th style="min-width:100px" class="nowrap">
                        <?php echo JText::_('COM_DATA_FIELD_EMAILNAMETEXT_LABEL');?>
                    </th>
                    <th width="10%" class="nowrap hidden-phone">
                        <?php echo JText::_('JAUTHOR');?>
                    </th>
                    <th width="10%" class="nowrap hidden-phone">
                        <?php echo JText::_('COM_DATA_HEADING_DATE');?>
                    </th>
                    <th width="10%" class="nowrap hidden-phone">
                        <?php echo JText::_('JGRID_HEADING_ACCESS');?>
                    </th>
                    <th width="10%" class="nowrap hidden-phone">
                        <?php echo JText::_('JGRID_HEADING_LANGUAGE');?>
                    </th>
                    <th width="1%" class="nowrap hidden-phone">
                        <?php echo JText::_('JGRID_HEADING_ID');?>
                    </th>
                </tr>
                </thead>
                <tfoot>
                <tr>
                    <td colspan="<?php echo $columns; ?>">
                    </td>
                </tr>
                </tfoot>
                <tbody>
                <?php foreach ($this->items as $i => $item) :
                    $canEdit    = $user->authorise('core.edit',       'com_data.email.' . $item->id);
                    $canEditOwn = $user->authorise('core.edit.own',   'com_data.email.' . $item->id) && $item->created_by == $userId;
                    $canChange  = $user->authorise('core.edit.state', 'com_data.email.' . $item->id);
                    ?>
                    <tr class="row<?php echo $i % 2; ?>">
                        <td class="center">
                            <?php echo JHtml::_('grid.id', $i, $item->id); ?>
                        </td>
                        <td class="center">
                            <div class="btn-group">
                                <?php echo JHtml::_('jgrid.published', $item->state, $i, 'emails.', $canChange, 'cb', $item->publish_up, $item->publish_down); ?>
                                <?php // Create dropdown items and render the dropdown list.
                                if ($canChange)
                                {
                                    JHtml::_('actionsdropdown.' . ((int) $item->state === 2 ? 'un' : '') . 'archive', 'cb' . $i, 'emails');
                                    JHtml::_('actionsdropdown.' . ((int) $item->state === -2 ? 'un' : '') . 'trash', 'cb' . $i, 'emails');
                                    echo JHtml::_('actionsdropdown.render', $this->escape($item->title));
                                }
                                ?>
                            </div>
                        </td>
                        <td class="has-context">
                            <div class="pull-left break-word">
                                <?php if ($canEdit || $canEditOwn) : ?>
                                    <a class="hasTooltip" href="<?php echo JRoute::_('index.php?option=com_data&task=email.edit&id=' . $item->id); ?>" title="<?php echo JText::_('JACTION_EDIT'); ?>">
                                        <?php echo $this->escape($item->email); ?></a>
                                <?php else : ?>
                                    <span><?php echo $this->escape($item->email); ?></span>
                                <?php endif; ?>
                            </div>
                        </td>
                        <td class="has-context">
                            <div class="pull-left break-word">
                                <?php if ($canEdit || $canEditOwn) : ?>
                                    <a class="hasTooltip" href="<?php echo JRoute::_('index.php?option=com_data&task=email.edit&id=' . $item->id); ?>" title="<?php echo JText::_('JACTION_EDIT'); ?>">
                                        <?php echo $this->escape($item->name); ?></a>
                                <?php else : ?>
                                    <span><?php echo $this->escape($item->name); ?></span>
                                <?php endif; ?>
                            </div>
                        </td>
                        <td class="small hidden-phone">
                                <a class="hasTooltip" href="<?php echo JRoute::_('index.php?option=com_users&task=user.edit&id=' . (int) $item->created_by); ?>" title="<?php echo JText::_('JAUTHOR'); ?>">
                                    <?php echo $this->escape($item->author_name); ?></a>
                        </td>
                        <td class="nowrap small hidden-phone">
                            <?php
                            $date = $item->{$orderingColumn};
                            echo $date > 0 ? JHtml::_('date', $date, JText::_('DATE_FORMAT_LC4')) : '-';
                            ?>
                        </td>
                        <td class="small hidden-phone">
                            <?php echo $this->escape($item->access_level); ?>
                        </td>
                        <td class="small hidden-phone">
                            <?php echo JLayoutHelper::render('joomla.content.language', $item); ?>
                        </td>
                        <td class="hidden-phone">
                            <?php echo (int) $item->id; ?>
                        </td>
                    </tr>
                <?php endforeach; ?>
                </tbody>
            </table>

        <?php endif; ?>

        <?php echo $this->pagination->getListFooter(); ?>

        <input type="hidden" name="task" value="" />
        <input type="hidden" name="boxchecked" value="0" />
        <?php echo JHtml::_('form.token'); ?>
    </div>
</form>
