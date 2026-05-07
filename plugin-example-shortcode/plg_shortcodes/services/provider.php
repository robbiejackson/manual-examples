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
        }
    };