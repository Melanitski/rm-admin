<?php

namespace RM\AdminBundle\Mapping;

class ClassMetadata extends ClassMetadataInfo {

    public $reflFields = array();

    private $prototype;

    public function __construct($documentName) {
        $this->name = $documentName;
        $this->reflClass = new \ReflectionClass($documentName);
        $this->namespace = $this->reflClass->getNamespaceName();
    }

    public function mapField(array $mapping) {
        $mapping = parent::mapField($mapping);
        if ( $this->reflClass->hasProperty( $mapping['fieldName'] ) ) {
            $reflProp = $this->reflClass->getProperty( $mapping['fieldName'] );
            $reflProp->setAccessible( true );
            $this->reflFields[$mapping['fieldName']] = $reflProp;
        }
    }


    /**
     * Creates a new instance of the mapped class, without invoking the constructor.
     *
     * @return object
     */
    public function newInstance() {
        if ($this->prototype === null) {
            $this->prototype = unserialize(sprintf('O:%d:"%s":0:{}', strlen($this->name), $this->name));
        }
        return clone $this->prototype;
    }

}
