<?php

namespace Peerj\Bundle\SixPackBundle;

use Symfony\Component\HttpKernel\Bundle\Bundle;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Peerj\Bundle\SixPackBundle\DependencyInjection\Compiler\PeerjSixpackOverideCompilerPass;

/**
 *
 */
class PeerjSixPackBundle extends Bundle
{
    /**
     * {@inheritdoc}
     */
    public function build(ContainerBuilder $container)
    {
        parent::build($container);
    }
}
