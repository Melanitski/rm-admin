<?php
namespace RM\AdminBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use RM\AdminBundle\Mapping\Annotation as RM;

/**
 *
 * @ORM\Table()
 * @RM\Entity(key="manager", menu="Managers")
 */
class Manager extends User {

    /**
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @ORM\Column(name="idUser", type="integer")
     */
    private $idUser;

    /**
     * @return integer
     */
    public function getId() {
        return $this->id;
    }

}
