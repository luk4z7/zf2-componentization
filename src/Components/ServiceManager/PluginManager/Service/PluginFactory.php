<?php

namespace Components\ServiceManager\PluginManager\Service;

use Zend\Mvc\Service\AbstractPluginManagerFactory;
use Zend\ServiceManager\ServiceLocatorInterface;

/**
 * Class PluginManager
 * @package Components\ServiceManager\PluginManager
 */
class PluginFactory extends AbstractPluginManagerFactory
{
    /**
     * Constante que recebe o namespace da classe a ser instânciada
     */
    const PLUGIN_MANAGER_CLASS = 'Components\ServiceManager\PluginManager\Plugin';

    /**
     * @param ServiceLocatorInterface $serviceLocator
     * @return \Zend\ServiceManager\AbstractPluginManager
     */
    public function createService(ServiceLocatorInterface $serviceLocator)
    {
        $pluginManagerClass = self::PLUGIN_MANAGER_CLASS;

        /**
         * @var $plugins \Zend\ServiceManager\AbstractPluginManager
         */
        $plugins = new $pluginManagerClass;
        $plugins->setServiceLocator($serviceLocator);

        return $plugins;
    }
}