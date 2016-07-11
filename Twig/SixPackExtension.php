<?php

namespace Peerj\Bundle\SixPackBundle\Twig;

use Peerj\Bundle\SixPackBundle\Service\BasicSixPackClient;

class SixPackExtension extends \Twig_Extension
{
    protected $client;

    public function __construct(BasicSixPackClient $client)
    {
        $this->client = $client;
    }

    public function getFunctions()
    {
        return array(
            'sixpackParticipate' =>  new \Twig_SimpleFunction('sixpackParticipate', [$this, 'participate']),
            'sixpackConvert'     =>  new \Twig_SimpleFunction('sixpackConvert', [$this, 'convert']),
        );
    }

    public function participate($experiment, array $alternatives, $trafficFraction = 1)
    {
        return $this->client->participate($experiment, $alternatives, $trafficFraction)->getAlternative();
    }

    public function convert($experiment, $kpi = null, $convertAll = true)
    {
        $this->client->convert($experiment, $kpi, $convertAll);
    }

    public function getName()
    {
        return 'sixpack_extension';
    }
}
