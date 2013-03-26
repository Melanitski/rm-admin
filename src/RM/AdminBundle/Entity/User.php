<?php
namespace RM\AdminBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use RM\AdminBundle\Mapping\Annotation as RM;

/**
 *
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="RM\AdminBundle\Repository\User")
 * @RM\Entity(key="user", menu="User")
 */
class User {

    /**
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @ORM\Column(name="email", type="string")
     */
    private $email;

    /**
     * @return integer
     */
    public function getId() {
        return $this->id;
    }

}
