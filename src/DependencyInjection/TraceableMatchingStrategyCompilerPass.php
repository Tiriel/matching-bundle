<?php

namespace Tiriel\MatchingBundle\DependencyInjection;

use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Tiriel\MatchingBundle\Matching\Strategy\TraceableMatchingStrategy;

class TraceableMatchingStrategyCompilerPass implements CompilerPassInterface
{
    public function process(ContainerBuilder $container)
    {
        $strategies = $container->findTaggedServiceIds('matching_bundle.matching_strategy');

        foreach ($strategies as $strategy => $attributes) {
            $container->register($strategy.'.cache')
                ->setClass(TraceableMatchingStrategy::class)
                ->setDecoratedService($strategy)
                ->setAutowired(true)
                ->addTag('matching_bundle.matching_strategy', $attributes)
            ;
        }
    }
}
