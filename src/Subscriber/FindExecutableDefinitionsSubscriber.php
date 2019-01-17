<?php

namespace Deployee\Plugins\DeployEnv\Subscriber;

use Deployee\Components\DocBlock\DocBlock;
use Deployee\Components\Environment\EnvironmentInterface;
use Deployee\Plugins\Deploy\Events\FindExecutableDefinitionFilesEvent;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

class FindExecutableDefinitionsSubscriber implements EventSubscriberInterface
{
    /**
     * @var DocBlock
     */
    private $docBlock;

    /**
     * @var EnvironmentInterface
     */
    private $env;

    /**
     * @return array
     */
    public static function getSubscribedEvents(): array
    {
        return [
            FindExecutableDefinitionFilesEvent::class => 'onFindExecutableDefinitions'
        ];
    }

    /**
     * @param DocBlock $docBlock
     * @param EnvironmentInterface $env
     */
    public function __construct(DocBlock $docBlock, EnvironmentInterface $env)
    {
        $this->docBlock = $docBlock;
        $this->env = $env;
    }

    /**
     * @param FindExecutableDefinitionFilesEvent $event
     * @throws \ReflectionException
     */
    public function onFindExecutableDefinitions(FindExecutableDefinitionFilesEvent $event)
    {
        $collection = $event->getDefinitionFileCollection();

        foreach($collection->getArrayCopy() as $index => $class){
            $tagValues = $this->docBlock->getTagsByName($class, 'env');
            if($this->docBlock->hasTag($class, 'env') && !in_array($this->env->getName(), $tagValues, false)){
                $collection->offsetUnset($index);
            }
        }
    }
}