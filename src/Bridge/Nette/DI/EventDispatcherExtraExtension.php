<?php

declare(strict_types=1);

namespace SixtyEightPublishers\EventDispatcherExtra\Bridge\Nette\DI;

use Nette\DI\CompilerExtension;
use Nette\DI\Definitions\Statement;
use Nette\DI\Definitions\Definition;
use Nette\DI\Definitions\FactoryDefinition;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use SixtyEightPublishers\EventDispatcherExtra\EventDispatcherFactory;
use SixtyEightPublishers\EventDispatcherExtra\EventDispatcherAwareInterface;
use SixtyEightPublishers\EventDispatcherExtra\EventDispatcherFactoryInterface;

final class EventDispatcherExtraExtension extends CompilerExtension
{
	/**
	 * {@inheritDoc}
	 */
	public function loadConfiguration(): void
	{
		$builder = $this->getContainerBuilder();

		$builder->addDefinition($this->prefix('event_dispatcher_factory'))
			->setAutowired(FALSE)
			->setType(EventDispatcherFactoryInterface::class)
			->setFactory(EventDispatcherFactory::class);
	}

	/**
	 * {@inheritDoc}
	 */
	public function beforeCompile(): void
	{
		$builder = $this->getContainerBuilder();

		# service existence check
		$builder->getByType(EventDispatcherInterface::class, TRUE);

		$definitions = array_filter($builder->getDefinitions(), static function (Definition $def): bool {
			return is_a($def->getType(), EventDispatcherAwareInterface::class, TRUE) || ($def instanceof FactoryDefinition && is_a($def->getResultType(), EventDispatcherAwareInterface::class, TRUE));
		});

		foreach ($definitions as $definition) {
			if ($definition instanceof FactoryDefinition) {
				$definition = $definition->getResultDefinition();
			}

			$definition->addSetup('setEventDispatcher', [
				new Statement([$this->prefix('@event_dispatcher_factory'), 'create']),
			]);
		}
	}
}
