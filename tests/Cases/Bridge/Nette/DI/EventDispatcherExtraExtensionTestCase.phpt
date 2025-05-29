<?php

declare(strict_types=1);

namespace SixtyEightPublishers\EventDispatcherExtra\Tests\Cases\Bridge\Nette\DI;

use Nette\Bootstrap\Configurator;
use Nette\DI\Container;
use Nette\DI\Definitions\Statement;
use SixtyEightPublishers\EventDispatcherExtra\Bridge\Nette\DI\EventDispatcherExtraExtension;
use SixtyEightPublishers\EventDispatcherExtra\Tests\Fixture\DummyService;
use Symfony\Component\EventDispatcher\EventDispatcher;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Tester\Assert;
use Tester\TestCase;

require __DIR__ . '/../../../../bootstrap.php';

final class EventDispatcherExtraExtensionTestCase extends TestCase
{
    public function testIntegrationIntoDependencyInjectionContainer(): void
    {
        $configurator = new Configurator();

        $configurator->setTempDirectory(TEMP_PATH);

        $configurator->addConfig([
            'extensions' => [
                '68publishers.event_dispatcher_extra' => new Statement(EventDispatcherExtraExtension::class),
            ],
            'services' => [
                0 => new Statement(EventDispatcher::class),
                1 => new Statement(DummyService::class),
            ],
        ]);

        /** @var Container|NULL $container */
        $container = null;

        Assert::noError(static function () use (&$container, $configurator) {
            $container = $configurator->createContainer();
        });

        /** @var DummyService $dummyService */
        $dummyService = $container->getByType(DummyService::class);

        Assert::type(EventDispatcherInterface::class, $dummyService->getEventDispatcher());
    }
}

(new EventDispatcherExtraExtensionTestCase())->run();
