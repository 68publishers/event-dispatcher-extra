<?php

declare(strict_types=1);

namespace SixtyEightPublishers\EventDispatcherExtra;

use Symfony\Component\EventDispatcher\EventDispatcher;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;

final class EventDispatcherFactory implements EventDispatcherFactoryInterface
{
	/** @var \Symfony\Component\EventDispatcher\EventDispatcherInterface  */
	private $globalEventDispatcher;

	/**
	 * @param \Symfony\Component\EventDispatcher\EventDispatcherInterface $globalEventDispatcher
	 */
	public function __construct(EventDispatcherInterface $globalEventDispatcher)
	{
		$this->globalEventDispatcher = $globalEventDispatcher;
	}

	/**
	 * @return \Symfony\Component\EventDispatcher\EventDispatcherInterface
	 */
	public function create(): EventDispatcherInterface
	{
		return new ComposedEventDispatcher($this->globalEventDispatcher, new EventDispatcher());
	}
}
