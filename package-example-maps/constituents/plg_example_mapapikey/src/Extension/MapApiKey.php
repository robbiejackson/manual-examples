<?php
namespace My\Plugin\System\ExampleMapAPIkey\Extension;

// no direct access
defined('_JEXEC') or die;

use Joomla\CMS\Plugin\CMSPlugin;
use Joomla\CMS\Event\Module\RenderModuleEvent;
use Joomla\Event\SubscriberInterface;
use Joomla\CMS\Language\Text;

class MapApiKey extends CMSPlugin implements SubscriberInterface
{
    public static function getSubscribedEvents(): array
    {
        return [
                'onRenderModule' => 'addAPIkey',  
                ];
    }

    public function addAPIkey(RenderModuleEvent $event): void
    {
        // We want to pass the API key only once
        static $api_key_set = false;
        
        $app = $this->getApplication();
        
        // The line below restricts the functionality to the front-end site 
        if (!$app->isClient('site')) return;
        
        // Ignore if it's not our maps module, or we've already passed the API key
        $module = $event->getModule();
        if ($module->module !== "mod_example_maps" or $api_key_set) return;
        
        // Find the API key from the plugin params, and pass to the JS code
        $vars = array("apikey" => $this->params['apikey']);
        $document = $app->getDocument();
        $document->addScriptOptions('plg_example_maps.apikey', $vars);

        $api_key_set = true;
    }
}