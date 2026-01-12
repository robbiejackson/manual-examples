<?php

namespace My\Module\ExampleMaps\Site\Dispatcher;

\defined('_JEXEC') or die;

use Joomla\CMS\Dispatcher\DispatcherInterface;
use Joomla\CMS\Helper\ModuleHelper;
use Joomla\CMS\Language\Text;
use Joomla\CMS\Application\CMSApplicationInterface;
use Joomla\Input\Input;
use Joomla\Registry\Registry;

class Dispatcher implements DispatcherInterface
{
    protected $module;
    
    protected $app;

    public function __construct(\stdClass $module, CMSApplicationInterface $app, Input $input)
    {
        $this->module = $module;
        $this->app = $app;
    }
    
    public function dispatch()
    {
        $language = $this->app->getLanguage();
        $language->load('mod_example_maps', JPATH_BASE . '/modules/mod_example_maps');
        
        $params = new Registry($this->module->params);

        $config = array('id' => 'mod_example_maps_' . $this->module->id,  // id of HTML div element for the map
                        'type' => $params->get('maptype'),
                        'apikey' => $params->get('apikey'),
                        'lat' => (float)$params->get('lat'),
                        'long' => (float)$params->get('long'),
                        'zoom' => (int)$params->get('zoom'),
                        'pins' => (array) $params->get('pins'),
                        );

        require ModuleHelper::getLayoutPath('mod_example_maps');
    }
}
