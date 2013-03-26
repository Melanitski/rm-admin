<?php

namespace RM\AdminBundle\DependencyInjection;

use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\Alias;
use Symfony\Bridge\Doctrine\DependencyInjection\AbstractDoctrineExtension;
use Symfony\Component\DependencyInjection\Loader;
use Symfony\Component\DependencyInjection\Definition;
use Symfony\Component\DependencyInjection\DefinitionDecorator;
use Symfony\Component\DependencyInjection\Reference;

/**
 * TODO extract ext manager config sub-class. For abstraction. To hard code
 *
 * Class RMAdminExtension
 * @package RM\AdminBundle\DependencyInjection
 */
class RMAdminExtension extends AbstractDoctrineExtension {

    public function load(array $configs, ContainerBuilder $container) {
        $loader = new Loader\XmlFileLoader($container, new FileLocator(__DIR__.'/../Resources/config'));
        $loader->load('services.xml');

        $configuration = new Configuration( );
        $config = $this->processConfiguration($configuration, $configs);

        $this->__setDefaultExtManager($config, $container);
        $this->__overrideDefaultParameters($config, $container);

        $this->__loadExtManagers($config['ext_managers'], $config['default_ext_manager'], $container);
    }

    /**
     * Create container parameter with default ext manager
     *
     * @param array $config
     * @param ContainerBuilder $container
     */
    protected function __setDefaultExtManager(array &$config, ContainerBuilder $container) {
        if (empty($config['default_ext_manager'])) {
            $keys = array_keys($config['ext_managers']);
            $config['default_ext_manager'] = reset($keys);
        }
        $container->setParameter('rm.admin.default_ext_manager', $config['default_ext_manager']);
    }


    /**
     * Override default parameters in config
     *
     * @param array $options
     * @param ContainerBuilder $container
     * @return array
     */
    protected function __overrideDefaultParameters(array $options, ContainerBuilder $container) {
        $overrides = array(
            'proxy_namespace',
            'proxy_dir',
            'auto_generate_proxy_classes'
        );
        foreach ($overrides as $key) {
            if (isset($options[$key])) {
                $container->setParameter('rm.ext.'.$key, $options[$key]);
                unset($options[$key]);
            }
        }
        return $options;
    }

    /**
     * Load ext managers array
     *
     * @param array $extManagersConfigs
     * @param $defaultDocumentManagerName
     * @param ContainerBuilder $container
     */
    protected function __loadExtManagers(array $extManagersConfigs, $defaultDocumentManagerName, ContainerBuilder $container) {
        $extManagers = array();
        foreach ($extManagersConfigs as $name => $extManager) {
            $extManager['name'] = $name;
            $this->__loadExtManager($extManager, $name, $defaultDocumentManagerName, $container);
            $extManagers[$name] = sprintf('rm.admin.%s_ext_manager', $name);
        }
        $container->setParameter('rm.admin.ext_managers', $extManagers);
    }

    /**
     * Load current ext manager
     *
     * @param array $extManagerConfig
     * @param $extManagerName
     * @param $defaultDocumentManagerName
     * @param ContainerBuilder $container
     */
    protected function __loadExtManager(array $extManagerConfig, $extManagerName, $defaultDocumentManagerName, ContainerBuilder $container) {
        $configDef = $this->_getConfigDefinition( $extManagerConfig, $container );
        $this->__loadExtManagerBundlesMappingInformation($extManagerConfig, $configDef, $container);
        $this->loadObjectManagerCacheDriver($extManagerConfig, $container, 'metadata_cache');
        $this->__setExtManagerParameters($defaultDocumentManagerName, $configDef);

        $extManagerDefinition = new Definition(
            '%rm.admin.ext_manager.class%',
            array( new Reference( sprintf( 'rm.admin.%s_configuration', $extManagerName ) ) )
        );
        $extManagerDefinition->setFactoryClass('%rm.admin.ext_manager.class%');
        $extManagerDefinition->setFactoryMethod('create');
        $extManagerDefinition->addTag('rm.admin.ext_manager');
        $container->setDefinition( sprintf( 'rm.admin.%s_ext_manager', $extManagerName ), $extManagerDefinition );

        if ($extManagerName == $defaultDocumentManagerName) {
            $alias = new Alias(sprintf('rm.admin.%s_ext_manager', $extManagerName));
            $container->setAlias('rm.admin.ext_manager', $alias);
        }
    }

    /**
     * Set parameters to ext manager definition
     *
     * @param $documentManagerName
     * @param Definition $configDef
     */
    private function __setExtManagerParameters($documentManagerName, Definition $configDef) {
        $methods = array(
            'setMetadataCacheImpl' => new Reference(sprintf('rm.admin.%s_metadata_cache', $documentManagerName)),
            'setMetadataDriverImpl' => new Reference(sprintf('rm.admin.%s_metadata_driver', $documentManagerName)),
            'setProxyDir' => '%rm.ext.proxy_dir%',
            'setProxyNamespace' => '%rm.ext.proxy_namespace%',
            'setAutoGenerateProxyClasses' => '%rm.ext.auto_generate_proxy_classes%'
        );

        foreach ($methods as $method => $arg) {
            if ($configDef->hasMethodCall($method)) {
                $configDef->removeMethodCall($method);
            }
            $configDef->addMethodCall($method, array($arg));
        }
    }

    /**
     * Load alias map and set to ext manager definition
     *
     * @param array $documentManager
     * @param Definition $odmConfigDef
     * @param ContainerBuilder $container
     */
    protected function __loadExtManagerBundlesMappingInformation(array $documentManager, Definition $odmConfigDef, ContainerBuilder $container) {
        // reset state of drivers and alias map. They are only used by this methods and children.
        $this->drivers = array();
        $this->aliasMap = array();

        $this->loadMappingInformation($documentManager, $container);
        $this->registerMappingDrivers($documentManager, $container);

        if ($odmConfigDef->hasMethodCall('setDocumentNamespaces')) {
            // TODO: Can we make a method out of it on Definition? replaceMethodArguments() or something.
            $calls = $odmConfigDef->getMethodCalls();
            foreach ($calls as $call) {
                if ($call[0] == 'setDocumentNamespaces') {
                    $this->aliasMap = array_merge($call[1][0], $this->aliasMap);
                }
            }
            $method = $odmConfigDef->removeMethodCall('setDocumentNamespaces');
        }
        $odmConfigDef->addMethodCall('setDocumentNamespaces', array($this->aliasMap));
    }

    protected function getObjectManagerElementName($name) {
        return 'rm.admin.' . $name;
    }

    /**
     * TODO needs return array
     * @return string
     */
    protected function getMappingObjectDefaultName() {
        return 'Entity';
    }

    protected function getMappingResourceConfigDirectory() {
        return 'Resources/config/';
    }

    protected function getMappingResourceExtension() {
        return 'rm_admin';
    }

    /**
     * @param array $documentManager
     * @param ContainerBuilder $container
     * @return Definition
     */
    private function _getConfigDefinition(array $documentManager, ContainerBuilder $container) {
        $serviceName = sprintf('rm.admin.%s_configuration', $documentManager['name']);
        if ( !$container->hasDefinition($serviceName) ) {
            $configDef = new Definition('%rm.admin.configuration.class%');
            $container->setDefinition($serviceName, $configDef);
        }
        return $container->getDefinition($serviceName);
    }

}
