<?php

namespace Oneup\FlysystemBundle\DependencyInjection\Factory\Adapter;

use Symfony\Component\Config\Definition\Builder\NodeDefinition;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\DefinitionDecorator;
use Oneup\FlysystemBundle\DependencyInjection\Factory\AdapterFactoryInterface;

class LocalWithHostFactory implements AdapterFactoryInterface
{
    public function getKey()
    {
        return 'local_with_host';
    }

    public function create(ContainerBuilder $container, $id, array $config)
    {
        $definition = new DefinitionDecorator('oneup_flysystem.adapter.local_with_host');
        $definition->replaceArgument(0, $config['directory']);
        $definition->replaceArgument(2, $config['webpath']);
        
        if (isset($config['defaults'])) {
            $definition->replaceArgument(3, $config['defaults']);
        }

        $container->setDefinition($id, $definition);
    }

    public function addConfiguration(NodeDefinition $node)
    {
        $node
            ->children()
                ->scalarNode('directory')->isRequired()->end()
                ->scalarNode('webpath')->isRequired()->end()
                ->arrayNode('defaults')
                    ->children()
                        ->scalarNode('scheme')->end()
                        ->scalarNode('httpHost')->end()
                        ->scalarNode('port')->end()
                    ->end()
                ->end()
            ->end()
        ;
    }
}
