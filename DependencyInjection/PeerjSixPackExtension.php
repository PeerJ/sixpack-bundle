<?php

namespace Peerj\Bundle\SixPackBundle\DependencyInjection;

use Symfony\Component\HttpKernel\DependencyInjection\Extension;
use Symfony\Component\DependencyInjection\Loader\XmlFileLoader;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\Config\Definition\Processor;
use Symfony\Component\DependencyInjection\Reference;
use Symfony\Component\DependencyInjection\Definition;
use Symfony\Component\DependencyInjection\Extension\PrependExtensionInterface;

class PeerjSixPackExtension extends Extension implements PrependExtensionInterface
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


    /**
     * Allow an extension to prepend the extension configurations.
     *
     * @param ContainerBuilder $container
     */
    public function prepend(ContainerBuilder $container)
    {
        // get all Bundles
        $bundles = $container->getParameter('kernel.bundles');

        if (isset($bundles['DoctrineBundle'])) {
            // Get configuration of our own bundle
            $configs = $container->getExtensionConfig($this->getAlias());
            $config = $this->processConfiguration(new Configuration(), $configs);

            // Prepare for insertion
            $forInsertion = array(
                'orm' => array(
                    'resolve_target_entities' => array(
                        'Peerj\Bundle\SixPackBundle\Model\SixPackUserInterface' => $config['userClass']
                    )
                )
            );

            foreach ($container->getExtensions() as $name => $extension) {
                switch ($name) {
                    case 'doctrine':
                        $container->prependExtensionConfig($name, $forInsertion);
                        break;
                }
            }
        }
    }
}
