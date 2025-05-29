<?php

declare(strict_types=1);

namespace SixtyEightPublishers\EventDispatcherExtra\Bridge\Nette\DI;

use Nette\DI\CompilerExtension;
use Nette\DI\Definitions\Definition;
use Nette\DI\Definitions\FactoryDefinition;
use Nette\DI\Definitions\ServiceDefinition;
use Nette\DI\Definitions\Statement;
use SixtyEightPublishers\EventDispatcherExtra\EventDispatcherAwareInterface;
use SixtyEightPublishers\EventDispatcherExtra\EventDispatcherFactory;
use SixtyEightPublishers\EventDispatcherExtra\EventDispatcherFactoryInterface;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;

final class EventDispatcherExtraExtension extends CompilerExtension
{
    public function loadConfiguration(): void
    {
        $builder = $this->getContainerBuilder();

        $builder->addDefinition($this->prefix('event_dispatcher_factory'))
            ->setType(EventDispatcherFactoryInterface::class)
            ->setFactory(EventDispatcherFactory::class);
    }

    public function beforeCompile(): void
    {
        $builder = $this->getContainerBuilder();

        # service existence check
        $builder->getByType(EventDispatcherInterface::class, true);

        $definitions = array_filter($builder->getDefinitions(), static function (Definition $def): bool {
            return is_a((string) $def->getType(), EventDispatcherAwareInterface::class, true) || ($def instanceof FactoryDefinition && is_a((string) $def->getResultType(), EventDispatcherAwareInterface::class, true));
        });

        foreach ($definitions as $definition) {
            if ($definition instanceof FactoryDefinition) {
                $definition = $definition->getResultDefinition();
            }

            if (!($definition instanceof ServiceDefinition)) {
                continue;
            }

            $definition->addSetup('setEventDispatcher', [
                new Statement([$this->prefix('@event_dispatcher_factory'), 'create']),
            ]);
        }
    }
}
