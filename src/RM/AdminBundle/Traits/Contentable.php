<?php

namespace RM\AdminBundle\Traits;

trait Contentable {

    use Ext\Item;

    public function getContentableItemId() {
        return $this->getExtItemId();
    }

    public function getContentableItemType() {
        return $this->getExtItemType();
    }

    public function getContentManager() {
        //TODO ContentFacade::getContent( $this ); || ID and TYPE
    }

    public function getContent() {
        return $this->getContentManager()->getCurrentContent();
    }

}


/*

$p = new Page();
$p->getContent()->setName('fafa');

$em->save($p);
$em->save($em->getContent());

*/
