<?php

namespace RM\AdminBundle;

use Doctrine\Common\Persistence\Mapping\Driver\MappingDriver;
use Doctrine\Common\Cache\Cache;
use Doctrine\Common\Annotations\AnnotationReader;


class Configuration {

    private $_entitiesNamespaces = array();
    private $_entityNamespace = '';

    private $_proxyDir = '';
    private $_proxyNamespace = '';
    private $_autoGenerateProxy;

    /**
     * @var \RM\AdminBundle\Mapping\ClassMetadataFactory
     */
    private $_metadataFactory;
    private $_metadataFactoryClass = '';

    /**
     * @var MappingDriver
     */
    private $_mappingDriver;

    /**
     * @var Cache
     */
    private $_metadataСacheDriver;


    public function addDocumentNamespace($alias, $namespace) {
        $this->_entitiesNamespaces[ $alias ] = $namespace;
    }

    public function setDocumentNamespaces(array $path) {
        $this->_entitiesNamespaces = $path;
    }

    public function getDocumentNamespace($alias) {
        if ( !isset( $this->_entitiesNamespaces[ $alias ] ) ) {
            //TODO throw Exception
        }
        return trim($this->_entitiesNamespaces[$alias], '\\');
    }

    public function getDocumentNamespaces() {
        return $this->_entityNamespace;
    }

    public function setProxyDir($proxyDir) {
        $this->_proxyDir = $proxyDir;
    }

    public function getProxyDir() {
        return $this->_proxyDir;
    }

    public function setProxyNamespace($proxyNamespace) {
        return $this->_proxyNamespace = $proxyNamespace;
    }

    public function getProxyNamespace() {
        return $this->_proxyNamespace;
    }

    public function setAutoGenerateProxyClasses($state) {
        $this->_autoGenerateProxy = $state;
    }

    public function getAutoGenerateProxyClasses() {
        return $this->_autoGenerateProxy;
    }

    public function setMetadataDriverImpl(MappingDriver $mappingDriver) {
        $this->_mappingDriver = $mappingDriver;
    }

    public function getMetadataDriverImpl() {
        return $this->_mappingDriver;
    }

    public function getMetadataCacheImpl() {
        return $this->_metadataСacheDriver;
    }

    public function setMetadataCacheImpl(Cache $cacheImpl) {
        $this->_metadataСacheDriver = $cacheImpl;
    }

    public function setClassMetadataFactoryName($cmfName) {
        $this->_metadataFactoryClass = $cmfName;
    }

    public function getClassMetadataFactoryName() {
        if ($this->_metadataFactoryClass == '') {
            //TODO move to the DI
            $this->_metadataFactoryClass = 'RM\AdminBundle\Mapping\ClassMetadataFactory';
        }
        return $this->_metadataFactoryClass;
    }

    public function getClassMetadataFactory() {
        if ( is_null($this->_metadataFactory) ) {
            $className = $this->getClassMetadataFactoryName();
            /* @var \RM\AdminBundle\Mapping\ClassMetadataFactory $metadataFactory */
            $metadataFactory = new $className();
            $metadataFactory->setConfiguration( $this );
            if ($cacheDriver = $this->getMetadataCacheImpl()) {
                $metadataFactory->setCacheDriver( $cacheDriver );
            }
            $this->_metadataFactory = $metadataFactory;
        }
        return $this->_metadataFactory;
    }

}