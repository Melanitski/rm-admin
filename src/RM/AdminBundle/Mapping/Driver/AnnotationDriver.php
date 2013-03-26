<?php

namespace RM\AdminBundle\Mapping\Driver;

use Doctrine\Common\Annotations\AnnotationReader;
use Doctrine\Common\Annotations\AnnotationRegistry;
use Doctrine\Common\Annotations\Reader;
use Doctrine\Common\Persistence\Mapping\ClassMetadata;
use Doctrine\Common\Persistence\Mapping\Driver\AnnotationDriver as AbstractAnnotationDriver;
use RM\AdminBundle\Mapping\Annotation as ExtMapping;

use RM\AdminBundle\Mapping\ClassMetadataInfo;
use RM\AdminBundle\Mapping\MappingException;


class AnnotationDriver extends AbstractAnnotationDriver {

    protected $entityAnnotationClasses = array(
        'RM\\AdminBundle\\Mapping\\Annotation\\Entity' => 1
    );

    public static function registerAnnotationClasses() {
        AnnotationRegistry::registerFile(__DIR__ . '/../Annotations/DoctrineAnnotations.php');
    }

    public function loadMetadataForClass($className, ClassMetadata $class) {
        /** @var $class ClassMetadataInfo */
        $reflectionClass = $class->getReflectionClass();
        foreach ($this->reader->getClassAnnotations($reflectionClass) as $annotation) {

            //TODO need map fields
            if ($annotation instanceof ExtMapping\Entity) {
                $this->_setExtEntityAttributes($class, $annotation);
            }

        }
    }

    private function _setExtEntityAttributes(ClassMetadataInfo $class, ExtMapping\Entity $annotation) {
        $class->setIdentifier( $annotation->getIdentifier() );
        if ( $annotation->inMenu() ) {
            $class->inMenu(true);
            $class->setMenuName( $annotation->getMenuName() );
        }
    }

    public static function create($paths = array(), Reader $reader = null) {
        if ($reader == null) {
            $reader = new AnnotationReader(); //TODO create default annotation reader via DI container
        }
        return new self($reader, $paths);
    }
}
