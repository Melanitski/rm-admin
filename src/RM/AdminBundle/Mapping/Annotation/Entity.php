<?php
namespace RM\AdminBundle\Mapping\Annotation;

/**
 * @Annotation
 * @Target("CLASS")
 */
final class Entity {

    /**
     * @var string
     */
    public $key;

   /**
    * @var string
    */
    public $menu = false;

    public function inMenu() {
        return is_string($this->menu);
    }

    public function getMenuName() {
        return $this->menu;
    }

    public function getIdentifier() {
        if (!is_string($this->key)) {
            throw Error::unsetIdentifier();
        }
        return $this->key;
    }

}
