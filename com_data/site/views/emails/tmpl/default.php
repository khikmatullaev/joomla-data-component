<?php
defined('_JEXEC') or die('Restricted access');


$listOrder = str_replace(' ' . $this->state->get('list.direction'), '', $this->state->get('list.fullordering'));
$listDirn  = $this->escape($this->state->get('list.direction'));
$columns   = 10;
?>

<h1 align="center" style="padding: 20px 0px"><?php echo $this->header; ?></h1>

<div id="j-main-container">
    <?php if (empty($this->items)) : ?>
        <div class="alert alert-no-items">
            <?php echo JText::_('JGLOBAL_NO_MATCHING_RESULTS'); ?>
        </div>
    <?php else : ?>
        <table class="table table-striped" id="emailList">
            <thead>
            <tr>
                <th width="1%" class="nowrap hidden-phone">
                    <?php echo JHtml::_('searchtools.sort', 'JGRID_HEADING_ID', 'a.id', $listDirn, $listOrder); ?>
                </th>
                <th style="min-width:100px" class="nowrap">
                    <?php echo JHtml::_('searchtools.sort', 'EMAIL', 'a.email', $listDirn, $listOrder); ?>
                </th>
                <th style="min-width:100px" class="nowrap">
                    <?php echo JHtml::_('searchtools.sort', 'NAME   ', 'a.name', $listDirn, $listOrder); ?>
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
            <?php foreach ($this->items as $i => $item) : ?>
                <tr class="row<?php echo $i % 2; ?>">
                    <td class="center">
                        <span><?php echo $this->escape($item->id); ?></span>
                    </td>
                    <td class="has-context">
                        <div class="pull-left break-word">
                            <span><?php echo $this->escape($item->email); ?></span>
                        </div>
                    </td>
                    <td class="has-context">
                        <div class="pull-left break-word">
                            <span><?php echo $this->escape($item->name); ?></span>
                        </div>
                    </td>
                </tr>
            <?php endforeach; ?>
            </tbody>
        </table>

    <?php endif; ?>

    <?php echo $this->pagination->getListFooter(); ?>
</div>
