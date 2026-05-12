<?php

namespace My\Component\Sendmail\Site\Controller;

defined('_JEXEC') or die;

use Joomla\CMS\MVC\Controller\BaseController;
use Joomla\CMS\Session\Session;

class DisplayController extends BaseController {

    public function display($cachable = false, $urlparams = array())
    {        
        $model = $this->getModel('sendmail');
        $view = $this->getView('sendmail', 'html');
        $view->setModel($model, true);
        $view->setDocument($this->app->getDocument());
        $view->display();
    }
}