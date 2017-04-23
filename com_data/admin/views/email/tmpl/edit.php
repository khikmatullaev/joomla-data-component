<?php
defined('_JEXEC') or die;

JHtml::_('behavior.formvalidator');
JHtml::_('formbehavior.chosen', 'select');

JFactory::getDocument()->addScriptDeclaration('
	Joomla.submitbutton = function(task)
	{
		if (task == "email.cancel" || document.formvalidator.isValid(document.getElementById("item-form")))
		{
			Joomla.submitform(task, document.getElementById("item-form"));
		}
	};
');
?>

<form action="<?php echo JRoute::_('index.php?option=com_data&layout=edit&id=' . (int) $this->item->id); ?>" method="post" name="adminForm" id="item-form" class="form-validate">
    <div class="form-horizontal">
        <div class="row-fluid">
            <div class="span9">
                <fieldset class="adminform">
                    <?php echo $this->form->renderField('email'); ?>
                    <?php echo $this->form->renderField('name'); ?>
                    <?php echo $this->form->renderField('created'); ?>
                    <?php echo $this->form->renderField('created_by'); ?>
                    <?php echo $this->form->renderField('modified'); ?>
                    <?php echo $this->form->renderField('modified_by'); ?>
                </fieldset>
            </div>
            <div class="span3">
                <?php echo JLayoutHelper::render('joomla.edit.global', $this); ?>
            </div>
        </div>

        <input type="hidden" name="task" value="" />
        <?php echo JHtml::_('form.token'); ?>
    </div>
</form>
