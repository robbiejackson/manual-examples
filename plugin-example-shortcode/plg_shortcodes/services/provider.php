<?php

use Joomla\CMS\Extension\PluginInterface;
use Joomla\CMS\Factory;
use Joomla\CMS\Plugin\PluginHelper;
use Joomla\DI\Container;
use Joomla\DI\ServiceProviderInterface;
use Joomla\Event\DispatcherInterface;
use My\Plugin\Content\Shortcodes\Extension\Shortcode;

    return new class() implements ServiceProviderInterface
    {
        public function register(Container $container)
        {
            if (version_compare(JVERSION, '6.1', '>=')) {
                // Use lazy plugin class for performance 
                // See https://github.com/joomla/joomla-cms/pull/45062
                $container->set(
                    PluginInterface::class,
                    $container->lazy(Shortcode::class, function (Container $container) {
        
                        $config = (array) PluginHelper::getPlugin('content', 'shortcodes');
                        $app = Factory::getApplication();
                        
                        $plugin = new Shortcode($config);
                        $plugin->setApplication($app);
        
                        return $plugin;
                    })
                );
            } else if (version_compare(JVERSION, '5.3', '>=')) {
                // Dispatcher passed to plugin constructor deprecated 
                // See https://github.com/joomla/joomla-cms/pull/43430
                $container->set(
                    PluginInterface::class,
                    function (Container $container) {
        
                        $config = (array) PluginHelper::getPlugin('content', 'shortcodes');
                        $app = Factory::getApplication();
                        
                        $plugin = new Shortcode($config);
                        $plugin->setApplication($app);
        
                        return $plugin;
                    }
                );
            } else {
                $container->set(
                    PluginInterface::class,
                    function (Container $container) {
        
                        $config = (array) PluginHelper::getPlugin('content', 'shortcodes');
                        $dispatcher = $container->get(DispatcherInterface::class);
                        $app = Factory::getApplication();
                        
                        $plugin = new Shortcode($dispatcher, $config);
                        $plugin->setApplication($app);
        
                        return $plugin;
                    }
                );
            }
        }
    };