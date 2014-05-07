<?php

namespace Peerj\Bundle\SixPackBundle\DependencyInjection;

use Symfony\Component\HttpKernel\DependencyInjection\Extension;
use Symfony\Component\DependencyInjection\Loader\XmlFileLoader;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\Config\Definition\Processor;

class PeerjSixPackExtension extends Extension
{
    /**
     * Build the extension services
     *
     * @param array $configs
     * @param ContainerBuilder $container
     */
    public function load(array $configs, ContainerBuilder $container)
    {
        $processor = new Processor();

        $loader = new XmlFileLoader($container, new FileLocator(__DIR__.'/../Resources/config'));
        $loader->load('config.xml');

        $configuration = new Configuration();
        $config = $processor->processConfiguration($configuration, $configs);
        
        $client_definition = $container->getDefinition('sixpack.client');
        $client_definition->addArgument($config['baseUrl']);
        $client_definition->addArgument($config['cookiePrefix']);
        $client_definition->addArgument($config['timeout']);
    }
}
