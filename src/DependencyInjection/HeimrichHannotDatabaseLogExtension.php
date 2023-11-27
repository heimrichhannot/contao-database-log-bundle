<?php

namespace HeimrichHannot\DatabaseLogBundle\DependencyInjection;

use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Extension\PrependExtensionInterface;
use Symfony\Component\HttpKernel\DependencyInjection\Extension;

class HeimrichHannotDatabaseLogExtension extends Extension implements PrependExtensionInterface
{

    public function load(array $configs, ContainerBuilder $container)
    {
        // TODO: Implement load() method.
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
                    'id' => 'HeimrichHannot\DatabaseLogBundle\Monolog\Handler\DatabaseLogHandler',
                    'channels' => ['doctrine']
                ]
            ]
        ]);
    }
}