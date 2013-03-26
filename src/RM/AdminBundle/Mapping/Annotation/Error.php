<?php
namespace RM\AdminBundle\Mapping\Annotation;

class Error extends \Exception {

    public static function unsetIdentifier() {
        return new self('Key attribute of Ext\Entity annotation class wasn\'t set');
    }

}