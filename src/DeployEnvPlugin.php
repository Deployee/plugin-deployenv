<?php


namespace Deployee\Plugins\DeployEnv;


use Deployee\Components\Container\ContainerInterface;
use Deployee\Components\DocBlock\DocBlock;
use Deployee\Components\Environment\EnvironmentInterface;
use Deployee\Components\Plugins\PluginInterface;
use Deployee\Plugins\DeployEnv\Subscriber\FindExecutableDefinitionsSubscriber;
use Symfony\Component\EventDispatcher\EventDispatcher;

class DeployEnvPlugin implements PluginInterface
{
    public function boot(ContainerInterface $container)
    {

    }

    public function configure(ContainerInterface $container)
    {
        $docBlock = new DocBlock();

        /* @var EnvironmentInterface $env */
        $env = $container->get(EnvironmentInterface::class);
        /* @var EventDispatcher $eventDispatcher */
        $eventDispatcher = $container->get(EventDispatcher::class);
        $eventDispatcher->addSubscriber(new FindExecutableDefinitionsSubscriber($docBlock, $env));
    }
}