<?php

namespace RM\AdminBundle\Mapping;

use Doctrine\Common\Persistence\Mapping\AbstractClassMetadataFactory;
use Doctrine\Common\Persistence\Mapping\ClassMetadata as ClassMetadataInterface;
use Doctrine\Common\Persistence\Mapping\ReflectionService;

use RM\AdminBundle\Configuration;
use RM\AdminBundle\Mapping\ClassMetadata;
use RM\AdminBundle\Mapping\MappingException;

class ClassMetadataFactory extends AbstractClassMetadataFactory {

    protected $cacheSalt = "\RM_ADMIN_CLASS_METADATA";

    /** @var Configuration The Configuration instance */
    private $config;

    /** @var \Doctrine\Common\Persistence\Mapping\Driver\MappingDriver The used metadata driver. */
    private $driver;

    /**
     * Sets the Configuration instance
     *
     * @param Configuration $config
     */
    public function setConfiguration(Configuration $config) {
        $this->config = $config;
    }

    /**
     * Lazy initialization of this stuff, especially the metadata driver,
     * since these are not needed at all when a metadata cache is active.
     */
    protected function initialize() {
        $this->driver = $this->config->getMetadataDriverImpl();
        $this->initialized = true;
    }

    protected function getFqcnFromAlias($namespaceAlias, $simpleClassName) {
        return $this->config->getDocumentNamespace($namespaceAlias) . '\\' . $simpleClassName;
    }

    protected function getDriver() {
        return $this->driver;
    }

    protected function wakeupReflection(ClassMetadataInterface $class, ReflectionService $reflService) {
        //TODO impl
    }

    protected function initializeReflection(ClassMetadataInterface $class, ReflectionService $reflService) {
        //TODO impl
    }

    protected function isEntity(ClassMetadataInterface $class) {
        //TODO
        return true;
    }

    protected function doLoadMetadata($class, $parent, $rootEntityFound, array $nonSuperclassParents) {
        /* @var $class ClassMetadata */
        /* @var $parent ClassMetadata */

        if ($parent) {
            //TODO add parent load
            $class->setInheritanceType($parent->inheritanceType);
            $class->setIdentifier($parent->identifier);
        }

        $this->driver->loadMetadataForClass($class->getName(), $class);

        $this->wakeupReflection($class, $this->getReflectionService());
    }


    /**
     * Creates a new ClassMetadata instance for the given class name.
     *
     * @param string $className
     * @return \RM\AdminBundle\Mapping\ClassMetadata
     */
    protected function newClassMetadataInstance($className) {
        return new ClassMetadata($className);
    }

}
