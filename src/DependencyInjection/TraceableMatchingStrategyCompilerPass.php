<?php

namespace Tiriel\MatchingBundle\DependencyInjection;

use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Tiriel\MatchingBundle\Matching\Handler\MatchingHandler;
use Tiriel\MatchingBundle\Matching\Strategy\TraceableMatchingStrategy;
use Tiriel\MatchingBundle\MessageHandler\MatchingMessageHandler;

class TraceableMatchingStrategyCompilerPass implements CompilerPassInterface
{
    public function process(ContainerBuilder $container)
    {
        $repository = $container->getExtensionConfig('tiriel_matching')[0]['user_repository'];
        $container->getDefinition(MatchingMessageHandler::class)
            ->addMethodCall('setUserRepository', [$container->getDefinition($repository)]);

        $container->removeDefinition(TraceableMatchingStrategy::class);

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
