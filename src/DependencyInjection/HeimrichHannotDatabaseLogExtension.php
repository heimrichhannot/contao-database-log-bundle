<?php

namespace HeimrichHannot\DatabaseLogBundle\DependencyInjection;

use HeimrichHannot\DatabaseLogBundle\Monolog\Handler\DatabaseLogHandler;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Extension\PrependExtensionInterface;
use Symfony\Component\DependencyInjection\Loader\YamlFileLoader;
use Symfony\Component\HttpKernel\DependencyInjection\Extension;

class HeimrichHannotDatabaseLogExtension extends Extension implements PrependExtensionInterface
{

    public function load(array $configs, ContainerBuilder $container)
    {
        $loader = new YamlFileLoader(
            $container,
            new FileLocator(__DIR__.'/../../config')
        );
        $loader->load('services.yaml');
    }

    public function prepend(ContainerBuilder $container)
    {
        $container->prependExtensionConfig('monolog', [
            'handlers' => [
                'main' => [
                    'channels' => ['!database_log']
                ],
                'database_log' => [
                    'type' => 'service',
                    'id' => DatabaseLogHandler::class,
                    'channels' => ['doctrine']
                ]
            ]
        ]);
    }
}