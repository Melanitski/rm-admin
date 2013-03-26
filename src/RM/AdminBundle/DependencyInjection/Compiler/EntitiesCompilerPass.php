<?php
namespace RM\AdminBundle\DependencyInjection\Compiler;

use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\Reference;
use Symfony\Component\DependencyInjection\ContainerInterface;

class EntitiesCompilerPass implements CompilerPassInterface {

    public function process(ContainerBuilder $container) {
        $definition = $container->getDefinition('rm_admin.collector.transfers');
        $services = array();
        foreach ($container->findTaggedServiceIds('rm_admin.ext_entity') as $id => $attributes) {
            $services[] = new Reference($id);
        }
        $definition->addMethodCall('setEntityTransfers', array( $services ));
    }

}
