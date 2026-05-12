<?php
namespace My\Component\Sendmail\Site\View\Sendmail;
 
defined('_JEXEC') or die;
use Joomla\CMS\MVC\View\HtmlView as BaseHtmlView;

class HtmlView extends BaseHtmlView 
{
    function display($tpl = null)
    {
        $this->form = $this->getModel()->getForm();

        parent::display($tpl);

        $this->setupDocument();
    }
    
    protected function setupDocument() 
    {
        $this->document->setTitle('Sendmail');
    }
}