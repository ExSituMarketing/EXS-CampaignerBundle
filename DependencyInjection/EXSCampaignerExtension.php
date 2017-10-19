<?php

namespace EXS\CampaignerBundle\DependencyInjection;

use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\HttpKernel\DependencyInjection\Extension;
use Symfony\Component\DependencyInjection\Loader;

/**
 * This is the class that loads and manages your bundle configuration.
 *
 * @see http://symfony.com/doc/current/cookbook/bundles/extension.html
 */
class EXSCampaignerExtension extends Extension
{
    /**
     * {@inheritdoc}
     */
    public function load(array $configs, ContainerBuilder $container)
    {
        $configuration = new Configuration();
        $config = $this->processConfiguration($configuration, $configs);

        $loader = new Loader\YamlFileLoader($container, new FileLocator(__DIR__.'/../Resources/config'));
        $loader->load('services.yml');

        $container->setParameter('exs_campaigner.username', $config['username']);
        $container->setParameter('exs_campaigner.password', $config['password']);

        $container->setParameter('exs_campaigner.wsdl.campaign_management', $config['wsdl']['campaign_management']);
        $container->setParameter('exs_campaigner.wsdl.contact_management', $config['wsdl']['contact_management']);
        $container->setParameter('exs_campaigner.wsdl.content_management', $config['wsdl']['content_management']);
        $container->setParameter('exs_campaigner.wsdl.list_management', $config['wsdl']['list_management']);
        $container->setParameter('exs_campaigner.wsdl.smtp_management', $config['wsdl']['smtp_management']);
        $container->setParameter('exs_campaigner.wsdl.workflow_management', $config['wsdl']['workflow_management']);

        $container->setParameter('exs_campaigner.xsd.contact_search_criteria', $config['xsd']['contacts_search_criteria']);
    }
}
