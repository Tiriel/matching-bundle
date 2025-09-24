<?php

namespace Tiriel\MatchingBundle;

use Symfony\Component\Config\Definition\Configurator\DefinitionConfigurator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;
use Symfony\Component\HttpKernel\Bundle\AbstractBundle;
use Tiriel\MatchingBundle\MessageHandler\MatchingMessageHandler;
use function Symfony\Component\DependencyInjection\Loader\Configurator\service;

class TirielMatchingBundle extends AbstractBundle
{
    public function configure(DefinitionConfigurator $definition): void
    {
        $definition->rootNode()
            ->children()
                ->stringNode('user_repository')
                    ->isRequired()
                    ->validate()
                        ->ifFalse(fn ($className) => \class_exists($className))
                        ->thenInvalid('User repository class "%s" does not exists.')
                ->end()
            ->end()
        ;
    }

    public function loadExtension(array $config, ContainerConfigurator $container, ContainerBuilder $builder): void
    {
        $container->import('../config/services.php');
        $container->services()
            ->get(MatchingMessageHandler::class)
            ->call('setUserRepository', [service($config['user_repository'])]);
    }
}
