<?php
namespace My\Component\Sendmail\Site\Controller;

// No direct access to this file
defined('_JEXEC') or die;

use Joomla\CMS\Factory;
use Joomla\CMS\MVC\Controller\FormController;
use Joomla\CMS\Uri\Uri;
use Joomla\CMS\Mail\MailerFactoryAwareTrait;
use Joomla\CMS\Mail\MailerFactoryAwareInterface;
use Joomla\CMS\Mail\MailTemplate;

class MailerController extends FormController implements MailerFactoryAwareInterface
{
    use MailerFactoryAwareTrait;

    public function cancel($key = null)
    {
        $this->app->setUserState('com_sendmail.default.mailform.data', null);
        $this->setRedirect((string)Uri::getInstance(), 'Email cancelled');
    }

    public function send($key = null, $urlVar = null)
    {
        $this->checkToken();
        
        $input = $this->app->input; 
        $model = $this->getModel('sendmail');
        
        // get the data from the HTTP POST request
        $data  = $input->get('jform', array(), 'array');
        
        // Get the current URI to set in redirects. As we're handling a POST, 
        // this URI comes from the <form action="..."> attribute in the layout file above
        $currentUri = (string)Uri::getInstance();
        
        // set up context for saving form data and save it
        $this->app->setUserState('com_sendmail.default.mailform.data', $data);
        
        // Validate the posted data.
        // First we need to set up an instance of the form ...
        $form = $model->getForm($data, false);

        if (!$form)
        {
            $this->app->enqueueMessage($model->getError(), 'error');
            $this->setRedirect($currentUri);
            return false;
        }
        
        // ... and then we validate the data against it
        // The validate function called below results in the running of the validate="..." routines
        // specified against the fields in the form xml file, and also filters the data 
        // according to the filter="..." specified in the same place (removing html tags by default in strings)
        $validData = $model->validate($form, $data);

        // Handle the case where there are validation errors
        if ($validData === false)
        {
            // Get the validation messages.
            $errors = $model->getErrors();

            // Display up to three validation messages to the user.
            for ($i = 0, $n = count($errors); $i < $n && $i < 3; $i++)
            {
                if ($errors[$i] instanceof \Exception)
                {
                    $this->app->enqueueMessage($errors[$i]->getMessage(), 'warning');
                }
                else
                {
                    $this->app->enqueueMessage($errors[$i], 'warning');
                }
            }
            
            $this->setRedirect($currentUri);
            return false;
        }
        
        if ($validData['selector'] == '0') {
            $this->_sendUsingMail($validData);
        } else {
            $this->_sendUsingMailTemplate($validData);
        }
        
        $this->setRedirect($currentUri);
    }
    
    private function _sendUsingMail($validData)
    {
        $mailer = $this->getMailerFactory()->createMailer();
        
        $mailer->addRecipient($validData['recipient']);
        $mailer->setSubject($validData['subject']);
        $mailer->setBody($validData['body']);
        
        try 
        {
            $mailer->send(); 
            // data has been used ok, so clear the fields in the form
            $this->app->setUserState('com_sendmail.default.mailform.data', null);
            $this->app->enqueueMessage("Mail successfully sent", 'info');
        }
        catch (\Exception $e)
        {
            $this->app->enqueueMessage("Failed to send mail, " . $e->getMessage(), 'error');
        }

    }
    
    private function _sendUsingMailTemplate($validData)
    {
        $mailer = $this->getMailerFactory()->createMailer();
        $user = $this->app->getIdentity();
        $mailTemplate = new MailTemplate('com_sendmail.example', $user->getParam('language', $this->app->get('language')), $mailer);
        $mailTemplate->addTemplateData(
            [
                'name' => $validData['name'],
                'p1'   => $validData['p1'],
                'p2'   => $validData['p2']
            ]
        );
        $mailTemplate->addRecipient($validData['recipient']);

        try {
            $mailTemplate->send();
            // data has been used ok, so clear the fields in the form
            $this->app->setUserState('com_sendmail.default.mailform.data', null);
            $this->app->enqueueMessage("Mail successfully sent", 'info');
        } catch (\Exception $e) {
            $this->app->enqueueMessage("Failed to send mail, " . $e->getMessage(), 'error');
        }
    }
}