<?php

namespace Savch\SendgridBundle\DependencyInjection;

use Symfony\Component\HttpKernel\DependencyInjection\Extension;
use Symfony\Component\DependencyInjection\Loader\YamlFileLoader;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\Config\Definition\Processor;

/**
 * @author Andriy Savchenko andriy.savchenko@gmail.com
 */
class SavchSendgridExtension extends Extension
{
    /**
     * Build the extension services
     *
     * @param array $configs
     * @param ContainerBuilder $container
     */
    public function load(array $configs, ContainerBuilder $container)
    {
        $processor = new Processor();

        $loader = new YamlFileLoader($container, new FileLocator(__DIR__ . '/../Resources/config'));
        $loader->load('services.yml');

        $configuration = new Configuration();
        $config = $processor->processConfiguration($configuration, $configs);

        foreach (array('api_key', 'logging') as $attribute) {
            $container->setParameter('savch_sendgrid.'.$attribute, $config[$attribute]);
        }
    }
}
