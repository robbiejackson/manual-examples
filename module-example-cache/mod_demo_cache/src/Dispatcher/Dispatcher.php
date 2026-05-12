<?php

namespace My\Module\CacheDemo\Site\Dispatcher;

\defined('_JEXEC') or die;

use Joomla\CMS\Factory;
use Joomla\CMS\Dispatcher\DispatcherInterface;
use Joomla\CMS\Helper\HelperFactoryAwareInterface;
use Joomla\CMS\Helper\HelperFactoryAwareTrait;
use Joomla\CMS\Cache\CacheControllerFactoryInterface;
use Joomla\CMS\Application\CMSApplicationInterface;
use Joomla\Input\Input;

class Dispatcher implements DispatcherInterface, HelperFactoryAwareInterface
{
    use HelperFactoryAwareTrait;
    
    private $input;
    
    public function __construct(\stdClass $module, CMSApplicationInterface $app, Input $input)
    {
        $this->input = $input;
    }
    
    public function dispatch()
    {
        echo date("H:i:s");
        
        $cacheController = Factory::getContainer()->get(CacheControllerFactoryInterface::class)->createCacheController('callback', array());
        
        // Use the line below if you want to always use callback cache for the calculation,
        // regardless of whether Conservative caching is set on or off in global config
        // lifetime refers to cache expiry time in minutes
        //$cacheController = Factory::getContainer()->get(CacheControllerFactoryInterface::class)->createCacheController('callback', array('caching' => true, 'lifetime' => 1));

        $caching = $cacheController->getCaching();
        if ($caching)
        {
            echo "<br>Global Config set to Caching enabled<br>";
        }
        else
        {
            echo "<br>Global Config set to Caching not enabled<br>";
        }
        
        $helper = $this->getHelperFactory()->getHelper('CacheDemoHelper');

        $start = hrtime(true);
        $queryResults = $helper->getTableCounts($cacheController);
        $delay = hrtime(true) - $start;
        
        echo "<br>$delay ns delay<br>";
        
        echo "<h3>Table Counts</h3>";
        foreach ($queryResults as $key => $value)
        {
            echo "$key: $value<br>";
        }
        
        $cleanCache = $this->input->get('cleancache', 0, "INT"); 
        if ($cleanCache == 1) {
            $cacheController->clean('default', 'group');
        }
    }
}