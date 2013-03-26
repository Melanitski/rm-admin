<?php
namespace RM\AdminBundle\Ext\Menu;

use RM\AdminBundle\Mapping\ClassMetadataInfo;

class Item
    implements
        \JsonSerializable {

    private $_id;
    private $_name;

    public function __construct($itemId, $itemName) {
        $this->_id = $itemId;
        $this->_name = $itemName;
    }

    /**
     * @param ClassMetadataInfo $classMetaData
     * @return Item
     */
    public static function createFormMetaData(ClassMetadataInfo $classMetaData) {
        return new static(
            $classMetaData->getIdentifier(),
            $classMetaData->getMenuName()
        );
    }

    public function jsonSerialize() {
        return array(
            'cls'   => 'file',
            'id'    => $this->_id,
            'leaf'  => true,
            'text'  => $this->_name
        );
    }

}