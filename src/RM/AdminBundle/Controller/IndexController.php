<?php
namespace RM\AdminBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

class IndexController extends Controller {

    /**
     * @Template()
     */
    public function indexAction() {
        return array();
    }

    /**
     * @Template
     * @return array
     */
    public function loginAction() {
        return array();
    }
}
