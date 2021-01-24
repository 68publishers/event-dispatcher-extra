<?php

declare(strict_types=1);

namespace SixtyEightPublishers\EventDispatcherExtra;

use Symfony\Component\EventDispatcher\EventDispatcher;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

final class ComposedEventDispatcher implements EventDispatcherInterface
{
	/** @var \Symfony\Component\EventDispatcher\EventDispatcherInterface  */
	private $globalEventDispatcher;

	/** @var \Symfony\Component\EventDispatcher\EventDispatcherInterface  */
	private $localEventDispatcher;

	/**
	 * @param \Symfony\Component\EventDispatcher\EventDispatcherInterface      $globalEventDispatcher
	 * @param \Symfony\Component\EventDispatcher\EventDispatcherInterface|NULL $localEventDispatcher
	 */
	public function __construct(EventDispatcherInterface $globalEventDispatcher, ?EventDispatcherInterface $localEventDispatcher = NULL)
	{
		$this->globalEventDispatcher = $globalEventDispatcher;
		$this->localEventDispatcher = $localEventDispatcher ?? new EventDispatcher();
	}

	/**
	 * {@inheritDoc}
	 */
	public function addListener(string $eventName, $listener, int $priority = 0): void
	{
		$this->localEventDispatcher->addListener($eventName, $listener, $priority);
	}

	/**
	 * {@inheritDoc}
	 */
	public function addSubscriber(EventSubscriberInterface $subscriber): void
	{
		$this->localEventDispatcher->addSubscriber($subscriber);
	}

	/**
	 * {@inheritDoc}
	 */
	public function removeListener(string $eventName, $listener): void
	{
		$this->localEventDispatcher->removeListener($eventName, $listener);
	}

	/**
	 * {@inheritDoc}
	 */
	public function removeSubscriber(EventSubscriberInterface $subscriber): void
	{
		$this->localEventDispatcher->removeSubscriber($subscriber);
	}

	/**
	 * {@inheritDoc}
	 */
	public function getListeners(string $eventName = NULL): array
	{
		$global = $this->globalEventDispatcher->getListeners($eventName);
		$local = $this->localEventDispatcher->getListeners($eventName);

		if (NULL !== $eventName) {
			return array_merge($global, $local);
		}

		foreach ($global as $name => $events) {
			if (isset($local[$name])) {
				$global[$name] = array_merge($events, $local[$name]);
			}
		}

		return $global;
	}

	/**
	 * {@inheritDoc}
	 */
	public function getListenerPriority(string $eventName, $listener): ?int
	{
		return $this->globalEventDispatcher->getListenerPriority($eventName, $listener) ?? $this->localEventDispatcher->getListenerPriority($eventName, $listener);
	}

	/**
	 * {@inheritDoc}
	 */
	public function hasListeners(string $eventName = NULL): bool
	{
		return $this->globalEventDispatcher->hasListeners($eventName) || $this->localEventDispatcher->hasListeners($eventName);
	}

	/**
	 * {@inheritDoc}
	 */
	public function dispatch(object $event, string $eventName = NULL): object
	{
		$this->globalEventDispatcher->dispatch($event, $eventName);
		$this->localEventDispatcher->dispatch($event, $eventName);

		return $event;
	}
}
