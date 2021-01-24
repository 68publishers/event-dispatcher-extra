# Event Manager Extra

:sparkles: Extension that adds a combination of global and local event dispatchers with bridge into [Nette Framework](https://nette.org).
The global event dispatcher is the one that is registered as a service in DI Container.
The local event dispatcher is a unique instance for each created object.

## Installation

The best way to install 68publishers/event-dispatcher-extra is using Composer:

```bash
composer require 68publishers/event-dispatcher-extra
```

## Integration into Nette Framework

All you need is register a compiler extension:

```neon
extensions:
    68publishers.event_dispatcher_extra: SixtyEightPublishers\EventDispatcherExtra\Bridge\Nette\DI\EventDispatcherExtraExtension
```

The extension expects a service of type `Symfony\Component\EventDispatcher\EventDispatcherInterface` in the DI Container
so you can use any integration of `symfony/event-dispatcher` into the Nette Framework or you can simply register the service:

```neon
services:
    -
        type: Symfony\Component\EventDispatcher\EventDispatcher
        factory: Symfony\Component\EventDispatcher\EventDispatcher
```

## Example usage

Service and factory:

```php
<?php

use Symfony\Contracts\EventDispatcher\Event;
use SixtyEightPublishers\EventDispatcherExtra\EventDispatcherAwareTrait;
use SixtyEightPublishers\EventDispatcherExtra\EventDispatcherAwareInterface;

final class MyService implements EventDispatcherAwareInterface
{
    use EventDispatcherAwareTrait;

    public function doSomething() : void
    {
        $this->getEventDispatcher()->dispatch(new Event(), 'something');
    }
}

interface MyServiceFactoryInterface
{
    public function create() : MyService;
}
```

```php
<?php

/** @var MyServiceFactoryInterface $factory */

$service1 = $factory->create();
$service2 = $factory->create();

# attach a local event listener on specific instance:
$service1->getEventDispatcher()->addListener('something', static function () {
    # do some stuff here
});

# the "global" listeners and subscribers will be called as first and then will be called "local" listener defined above:
$service1->doSomething();

# only the "global" listeners and subscribers will be called:
$service2->doSomething();
```

## Contributing

Before committing any changes, don't forget to run

```bash
$ vendor/bin/php-cs-fixer fix --config=.php_cs.dist -v --dry-run
```

and

```bash
$ composer run tests
```
