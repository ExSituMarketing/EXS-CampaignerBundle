<?php

namespace EXS\CampaignerBundle\DependencyInjection;

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
        $rootNode = $treeBuilder->root('exs_campaigner');

        $rootNode
            ->children()
                ->scalarNode('username')
                    ->isRequired()
                    ->cannotBeEmpty()
                ->end()
                ->scalarNode('password')
                    ->isRequired()
                    ->cannotBeEmpty()
                ->end()
                ->arrayNode('wsdl')
                    ->addDefaultsIfNotSet()
                    ->children()
                        ->scalarNode('contact_management')
                            ->cannotBeEmpty()
                            ->defaultValue('https://ws.campaigner.com/2013/01/contactmanagement.asmx?WSDL')
                        ->end()
                    ->end()
                ->end()
                ->arrayNode('xsd')
                    ->addDefaultsIfNotSet()
                    ->children()
                        ->scalarNode('contacts_search_criteria')
                            ->cannotBeEmpty()
                            ->defaultValue('@EXSCampaignerBundle/Resources/xsd/ContactsSearchCriteria2.xsd')
                        ->end()
                    ->end()
                ->end()
            ->end()
        ;

        return $treeBuilder;
    }
}
