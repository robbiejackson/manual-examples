<?php

defined('_JEXEC') or die;

use Joomla\CMS\Factory;
use Joomla\CMS\Router\Route;
use Joomla\CMS\HTML\HTMLHelper;
use Joomla\CMS\Language\Text;

Factory::getApplication()->getDocument()->getWebAssetManager()->useScript('form.validate');

?>
<h2><?php echo Text::_('COM_SENDMAIL_FORM_TITLE'); ?></h2>
<form action="<?php echo Route::_('index.php?option=com_sendmail&view=sendmail'); ?>"
    method="post" name="adminForm" id="adminForm" class="form-validate" enctype="multipart/form-data">

    <div class="form-horizontal">
        <fieldset class="adminform">
            <div class="row">
                <div class="col-12">
                    <?php echo $this->form->renderFieldset('details');  ?>
                </div>
            </div>
        </fieldset>
    </div>

    <div class="btn-toolbar">
        <div class="btn-group">
            <button type="button" class="btn btn-primary" onclick="Joomla.submitbutton('mailer.send')">
                <span class="icon-ok"></span>&nbsp;<?php echo Text::_('COM_SENDMAIL_FORM_SEND'); ?>
            </button>
        </div>
        <div class="btn-group">
            <button type="button" class="btn" onclick="Joomla.submitbutton('mailer.cancel')">
                <span class="icon-cancel"></span>&nbsp;<?php echo Text::_('COM_SENDMAIL_FORM_CANCEL'); ?>
            </button>
        </div>
    </div>

    <input type="hidden" name="task" />
    <?php echo HTMLHelper::_('form.token'); ?>
</form>