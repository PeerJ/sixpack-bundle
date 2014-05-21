<?php

namespace Peerj\Bundle\SixPackBundle\DependencyInjection;

use Symfony\Component\HttpKernel\DependencyInjection\Extension;
use Symfony\Component\DependencyInjection\Loader\XmlFileLoader;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\Config\Definition\Processor;
use Symfony\Component\DependencyInjection\Reference;
use Symfony\Component\DependencyInjection\Definition;

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

        $clientIdsByName = $this->loadClients($config['clients'], $container);
        $container->setAlias('sixpack.client', sprintf('sixpack.client.%s', $config['defaultClient']));        
    }
    
    /**
     * Loads the configured clients.
     *
     * @param array $clients An array of clients configurations
     * @param ContainerBuilder $container A ContainerBuilder instance
     * @return array
     */
    protected function loadClients(array $clients, ContainerBuilder $container)
    {
        $clientIds = array();
        foreach ($clients as $name => $clientConfig) {
            $clientId = sprintf('sixpack.client.%s', $name);

            $args = $container->getDefinition('sixpack.client')->getArguments();

            $clientDef = new Definition('%sixpack.client.class%', $args);
            $clientDef->replaceArgument(0, $clientConfig);

            $clientDef->addTag('sixpack.client');
            if ($clientConfig['isUser']) {
                $clientDef->addTag('sixpack.client.user');
            } else {
                $clientDef->addTag('sixpack.client.anon');
            }

            $container->setDefinition($clientId, $clientDef);

            $clientIds[$name] = $clientId;
        }

        return $clientIds;
    }
    
}
