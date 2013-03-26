<?php

namespace RM\AdminBundle\Traits\Ext;

trait Item {

    abstract public function getExtItemId(); //TODO get primary key. Magic

    public function getExtItemType() {
        //TODO Auto increment id of annotation types DB
    }

}