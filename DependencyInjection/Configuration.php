<?php
/**
 * @author Grogirii Sokolik <g.sokol99@g-sokol.info>
 */

namespace RonteLtd\PusherBundle\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\ArrayNodeDefinition;
use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

/**
 * This is the class that validates and merges configuration from your app/config files.
 *
 * To learn more see {@link http://symfony.com/doc/current/cookbook/bundles/configuration.html}
 */
class Configuration implements ConfigurationInterface
{
    /**
     * {@inheritdoc}
     */
    public function getConfigTreeBuilder()
    {
        $treeBuilder = new TreeBuilder();
        $rootNode = $treeBuilder->root('ronte_ltd_pusher');

        $rootNode
            ->children()
                ->scalarNode('auth_key')
                    ->isRequired()
                    ->cannotBeEmpty()
                ->end()
                ->scalarNode('secret')
                    ->isRequired()
                    ->cannotBeEmpty()
                ->end()
                ->integerNode('app_id')
                    ->isRequired()
                ->end()
                ->scalarNode('gearman_server')->end()
                ->scalarNode('gearman_port')->end()
            ->end();

        $this->addOptionsSection($rootNode);

        return $treeBuilder;
    }

    private function addOptionsSection(ArrayNodeDefinition $node)
    {
        $node
            ->children()
                ->arrayNode('options')
                    ->children()
                        ->scalarNode('scheme')->end()
                        ->scalarNode('host')->end()
                        ->scalarNode('port')->end()
                        ->integerNode('timeout')->end()
                        ->booleanNode('encrypted')->end()
                        ->scalarNode('cluster')->end()
                        ->arrayNode('curl_options')->end()
                        ->scalarNode('notification_host')->end()
                        ->scalarNode('notification_port')->end()
                    ->end()
                ->end()
            ->end();
    }
}

