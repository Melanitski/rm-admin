<?php
namespace RM\AdminBundle;

use RM\AdminBundle\Configuration;
use RM\AdminBundle\Mapping\ClassMetadataInfo;
use RM\AdminBundle\Ext\Menu\Collection as MenuCollection;
use RM\AdminBundle\Ext\Menu\Item as MenuItem;

class ExtManager {

    /**
     * @var Configuration
     */
    protected $_config;

    protected $_metadataFactory;

    public function __construct(Configuration $config = null) {
        $this->_config = $config;
        $this->_metadataFactory = $config->getClassMetadataFactory();
    }

    public function getMenuEntitiesCollection() {
        $menuCollection = MenuCollection::create();
        foreach ($this->_metadataFactory->getAllMetadata() as $classMetaData) {
            /* @var ClassMetadataInfo $classMetaData */
            if ($classMetaData->inMenu()) {
                $menuCollection->add( MenuItem::createFormMetaData( $classMetaData ) );
            }
        }
        return $menuCollection;
    }

    public static function create(Configuration $config = null) {
        return new static($config);
    }

}