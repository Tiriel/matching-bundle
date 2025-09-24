<?php

namespace Symfony\Component\DependencyInjection\Loader\Configurator;

return function(ContainerConfigurator $container): void {
    $services = $container->services()
        ->defaults()
            ->autowire()
            ->autoconfigure()
    ;

    $services
        ->load('Tiriel\\MatchingBundle\\', '../src/')
            ->exclude(['../src/DependencyInjection/'])
    ;
};
