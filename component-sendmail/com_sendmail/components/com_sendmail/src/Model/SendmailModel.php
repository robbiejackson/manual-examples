<?php
namespace My\Component\Sendmail\Site\Model;

// No direct access to this file
defined('_JEXEC') or die('Restricted access');

use Joomla\CMS\MVC\Model\FormModel;
use Joomla\CMS\Factory;

class SendmailModel extends FormModel
{

    public function getForm($data = array(), $loadData = true)
    {
        $form = $this->loadForm(
            'com_sendmail.form',
            'mail',
            array(
                'control' => 'jform',
                'load_data' => $loadData
            )
        );

        if (empty($form))
        {
            $errors = $this->getErrors();
            throw new \Exception(implode("\n", $errors), 500);
        }

        return $form;
    }

    protected function loadFormData()
    {
        // Check the session for previously entered form data.
        $data = Factory::getApplication()->getUserState(
            'com_sendmail.default.mailform.data',
            array()
        );

        return $data;
    }

}