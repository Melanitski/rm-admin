<?php
namespace RM\AdminBundle\Ext\Menu;

class Collection
    implements
        \JsonSerializable {

    private $_items;

    /**
     * @param Item[] $items
     */
    public function setItems(array $items) {
        foreach ($items as $item) {
            $this->add( $item );
        }
    }

    /**
     * @return Item[]
     */
    public function getItems() {
        return $this->_items;
    }

    public function add(Item $item) {
        $this->_items[] = $item;
    }

    /**
     * @return Collection
     */
    public static function create() {
        return new static();
    }


    public function jsonSerialize() {
        return $this->_items;
    }

}