<?php

namespace RM\AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use RM\AdminBundle\Mapping\Annotation as Ext;
use RM\AdminBundle\Traits as Traits;

/**
 * Page
 *
 * @Ext\Entity(key="page", menu="Page")
 */
class Page
{

    use Traits\Contentable;


    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="pageName", type="string", length=255)
     */
    private $pageName;


    /**
     * Get id
     *
     * @return integer 
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set pageName
     *
     * @param string $pageName
     * @return Page
     */
    public function setPageName($pageName)
    {
        $this->pageName = $pageName;
    
        return $this;
    }

    /**
     * Get pageName
     *
     * @return string 
     */
    public function getPageName()
    {
        return $this->pageName;
    }

    public function getExtItemId() {
        return $this->getId();
    }

}
