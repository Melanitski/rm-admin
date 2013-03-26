<?php

namespace RM\AdminBundle\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;
use Symfony\Component\Config\Definition\Builder\ArrayNodeDefinition;

class Configuration implements ConfigurationInterface {

    public function getConfigTreeBuilder() {
        $treeBuilder = new TreeBuilder();
        $rootNode = $treeBuilder->root('rm');

        $this->__addExtManagersSection( $rootNode );
        $this->__addExtParamsSections( $rootNode );

        return $treeBuilder;
    }

    protected function __addExtManagersSection(ArrayNodeDefinition $rootNode) {
        $rootNode
            ->fixXmlConfig('ext_manager')
            ->children()
                ->arrayNode('ext_managers')
                    ->addDefaultsIfNotSet()

                    ->children()
                        ->arrayNode('default')
                            ->addDefaultsIfNotSet()

                            ->children()
                                ->scalarNode('auto_mapping')->defaultTrue()->end()
                            ->end()

                            ->append( $this->_getExtCacheDriverNode('metadata_cache_driver') )

                            ->children()
                                ->scalarNode('class_metadata_factory_name')
                                    ->defaultValue('RM\AdminBundle\Mapping\ClassMetadataFactory')
                                ->end()
                                ->scalarNode('auto_mapping')->defaultTrue()->end()
                            ->end()

                            ->children()
                                ->arrayNode('mappings')
                                    ->useAttributeAsKey('name')
                                    ->prototype('array')
                                        ->beforeNormalization()
                                            ->ifString()
                                            ->then(function($v) { return array('type' => $v); })
                                        ->end()
                                        ->treatNullLike(array())
                                        ->treatFalseLike(array('mapping' => false))
                                        ->performNoDeepMerging()
                                        ->children()
                                            ->scalarNode('mapping')->defaultValue(true)->end()
                                            ->scalarNode('type')->end()
                                            ->scalarNode('dir')->end()
                                            ->scalarNode('alias')->end()
                                            ->scalarNode('prefix')->end()
                                            ->booleanNode('is_bundle')->end()
                                        ->end()
                                    ->end()
                                ->end()
                            ->end()

                        ->end()
                    ->end()

                ->end()
            ->end()
        ;
    }

    protected function __addExtParamsSections(ArrayNodeDefinition $node) {
        $node
            ->fixXmlConfig('ext')
            ->addDefaultsIfNotSet()
            ->children()
                ->scalarNode('default_ext_manager')->end()
                ->booleanNode('auto_generate_proxy_classes')
                    ->defaultFalse()
                ->end()
                ->scalarNode('proxy_dir')
                    ->defaultValue('%kernel.cache_dir%/rm/ext/Proxies')
                ->end()
                ->scalarNode('proxy_namespace')
                    ->defaultValue('Proxies')
                ->end()
            ->end()
        ;
    }
    
    private function _getExtCacheDriverNode($name) {
        $treeBuilder = new TreeBuilder();
        $node = $treeBuilder->root($name);
        $node
            ->addDefaultsIfNotSet()
            ->beforeNormalization()
                ->ifString()
                ->then(function($v) { return array('type' => $v); })
            ->end()
            ->children()
                ->scalarNode('type')->defaultValue('array')->end()
                ->scalarNode('host')->end()
                ->scalarNode('port')->end()
                ->scalarNode('instance_class')->end()
                ->scalarNode('class')->end()
                ->scalarNode('id')->end()
            ->end();
        return $node;
    }

}
