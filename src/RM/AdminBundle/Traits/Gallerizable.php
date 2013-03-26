<?php

namespace RM\AdminBundle\Traits;

trait Gallerizable {

    use Ext\Item;

    public function getGallerizableItemId() {
        return $this->getExtItemId();
    }

    public function getGallerizableItemType() {
        return $this->getExtItemType();
    }

    public function getGallery() {
        //TODO GalleryFacade::getGallery( $this ); || ID and TYPE
    }

}