<?php

namespace Peerj\Bundle\SixPackBundle\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

class Configuration implements ConfigurationInterface
{
    public function getConfigTreeBuilder()
    {
        $treeBuilder = new TreeBuilder();
        $rootNode = $treeBuilder->root('peerj_six_pack');

        $rootNode
            ->children()
                ->scalarNode('baseUrl')->defaultNull()->end()
                ->scalarNode('cookiePrefix')->defaultNull()->end()
                ->scalarNode('timeout')->defaultNull()->end()
        ;

        return $treeBuilder;
    }
}
