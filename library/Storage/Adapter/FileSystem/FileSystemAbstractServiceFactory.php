<?php
/**
 * Image Manager
 *
 * @link        https://github.com/ripaclub/imgman
 * @copyright   Copyright (c) 2014, RipaClub
 * @license     http://opensource.org/licenses/BSD-2-Clause Simplified BSD License
 */
namespace ImgMan\Storage\Adapter\FileSystem;

use Zend\ServiceManager\AbstractFactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

/**
 * Class FileSystemAbstractServiceFactory
 */
class FileSystemAbstractServiceFactory implements AbstractFactoryInterface
{
    /**
     * @var string
     */
    protected $configKey = 'imgman_adapter_filesystem';

    /**
     * @var array
     */
    protected $config;

    /**
     * Determine if we can create a service with name
     *
     * @param ServiceLocatorInterface $serviceLocator
     * @param $name
     * @param $requestedName
     * @return bool
     */
    public function canCreateServiceWithName(ServiceLocatorInterface $serviceLocator, $name, $requestedName)
    {
        $config = $this->getConfig($serviceLocator);
        if (empty($config)) {
            return false;
        }

        return (
            isset($config[$requestedName])
            && is_array($config[$requestedName])
            && !empty($config[$requestedName])
            && isset($config[$requestedName]['path'])
            && is_string($config[$requestedName]['path'])
            && !empty($config[$requestedName]['path'])
            && realpath($config[$requestedName]['path']) != false
            && isset($config[$requestedName]['resolver'])
            && is_string($config[$requestedName]['resolver'])
            && !empty($config[$requestedName]['resolver'])
            && $serviceLocator->has($config[$requestedName]['resolver'])
        );
    }

    /**
     * Create service with name
     *
     * @param ServiceLocatorInterface $serviceLocator
     * @param $name
     * @param $requestedName
     * @return mixed
     */
    public function createServiceWithName(ServiceLocatorInterface $serviceLocator, $name, $requestedName)
    {
        $config = $this->getConfig($serviceLocator)[$requestedName];

        $storage = new FileSystemAdapter();
        $storage->setPath(realpath($config['path']));
        $storage->setResolver($serviceLocator->get($config['resolver']));
        return $storage;
    }

    /**
     * Get mongo configuration, if any
     *
     * @param  ServiceLocatorInterface $serviceLocator
     * @return array
     */
    protected function getConfig(ServiceLocatorInterface $serviceLocator)
    {
        if ($this->config !== null) {
            return $this->config;
        }

        if (!$serviceLocator->has('Config')) {
            $this->config = [];
            return $this->config;
        }

        $config = $serviceLocator->get('Config');
        if (!isset($config[$this->configKey])
            || !is_array($config[$this->configKey])
        ) {
            $this->config = [];
            return $this->config;
        }
        $this->config = $config[$this->configKey];
        return $this->config;
    }
}
