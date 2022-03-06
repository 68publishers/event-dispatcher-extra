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

	/**
	 * @param string   $eventName
	 * @param callable $listener
	 * @param int      $priority
	 *
	 * @return void
	 */
	public function addEventListener(string $eventName, $listener, int $priority = 0): void
	{
		$this->getEventDispatcher()->addListener($eventName, $listener, $priority);
	}

	/**
	 * @param object      $event
	 * @param string|NULL $eventName
	 *
	 * @return object
	 */
	protected function dispatchEvent(object $event, string $eventName = NULL): object
	{
		return $this->getEventDispatcher()->dispatch($event, $eventName);
	}
}
