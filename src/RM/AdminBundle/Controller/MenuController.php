<?php
namespace RM\AdminBundle\Controller;

use RM\AdminBundle\ExtManager;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use RM\AdminBundle\ExtJS\Collector\Entities;

class MenuController extends Controller {

    public function listAction() {
        /* @var ExtManager $transfersCollector */
        $transfersCollector = $this->get('rm.admin.ext_manager');
        return new JsonResponse( $transfersCollector->getMenuEntitiesCollection() );
    }

}