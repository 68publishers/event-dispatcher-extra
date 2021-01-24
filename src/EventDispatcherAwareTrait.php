<?php

declare(strict_types=1);

namespace SixtyEightPublishers\EventDispatcherExtra;

use RuntimeException;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;

trait EventDispatcherAwareTrait
{
	/** @var \Symfony\Component\EventDispatcher\EventDispatcherInterface|NULL */
	protected $eventDispatcher;

	/**
	 * @param \Symfony\Component\EventDispatcher\EventDispatcherInterface $eventDispatcher
	 *
	 * @return void
	 */
	public function setEventDispatcher(EventDispatcherInterface $eventDispatcher): void
	{
		$this->eventDispatcher = $eventDispatcher;
	}

	/**
	 * @return \Symfony\Component\EventDispatcher\EventDispatcherInterface
	 */
	public function getEventDispatcher(): EventDispatcherInterface
	{
		if (NULL === $this->eventDispatcher) {
			throw new RuntimeException(sprintf(
				'Event dispatcher is not set, please use method %s::setEventDispatcher().',
				static::class
			));
		}

		return $this->eventDispatcher;
	}
}
