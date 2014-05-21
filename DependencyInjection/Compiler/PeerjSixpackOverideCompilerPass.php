<?php

namespace Peerj\Bundle\SixPackBundle\DependencyInjection\Compiler;

use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Reference;

/**
 * Class PeerjSixpackOverideCompilerPass
 *
 * @package Peerj\Bundle\SixPackBundle\DependencyInjection\Compiler
 */
class PeerjSixpackOverideCompilerPass implements CompilerPassInterface
{
    /**
     * @param ContainerBuilder $container
     */
    public function process(ContainerBuilder $container)
    {
        $serviceIds = $container->findTaggedServiceIds('sixpack.client');
        foreach ($serviceIds as $serviceId => $tag) {
            $definition = $container->getDefinition($serviceId);
            $definition->addArgument(new Reference('peerj_user.encrypt.id'));
        }
    }
}
