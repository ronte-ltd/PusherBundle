<?php

/**
 * @author Grigorii Sokolik <g.sokol99@g-sokol.info>
 */

namespace RonteLtd\PusherBundle\DependencyInjection;

use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Loader\XmlFileLoader;
use Symfony\Component\HttpKernel\DependencyInjection\Extension;

/**
 * This is the class that loads and manages your bundle configuration.
 *
 * @link http://symfony.com/doc/current/cookbook/bundles/extension.html
 */
class RonteLtdPusherExtension extends Extension
{
    /**
     * {@inheritdoc}
     */
    public function load(array $configs, ContainerBuilder $container)
    {
        $configuration = new Configuration();
        $config = $this->processConfiguration($configuration, $configs);

        $loader = new XmlFileLoader(
            $container,
            new FileLocator(__DIR__.'/../Resources/config')
        );
        $loader->load('services.xml');

        $pusherDefinition = $container
            ->getDefinition('ronte_ltd_pusher.pusher');

        $pusherDefinition
            ->addArgument($config['auth_key'])
            ->addArgument($config['secret'])
            ->addArgument($config['app_id']);

        if (!empty($config['options'])) {
            $pusherDefinition->addArgument($config['options']);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function getAlias()
    {
        return 'ronte_ltd_pusher';
    }
}
