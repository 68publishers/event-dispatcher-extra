<?php

declare(strict_types=1);

namespace SixtyEightPublishers\EventDispatcherExtra\Tests\Cases\Bridge\Nette\DI;

use Nette\Bootstrap\Configurator;
use Nette\DI\Container;
use Nette\DI\Definitions\Statement;
use SixtyEightPublishers\EventDispatcherExtra\Bridge\Nette\DI\EventDispatcherExtraExtension;
use SixtyEightPublishers\EventDispatcherExtra\Tests\Fixture\DummyService;
use SixtyEightPublishers\EventDispatcherExtra\Tests\Fixture\DummyServiceFactoryInterface;
use Symfony\Component\EventDispatcher\EventDispatcher;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Tester\Assert;
use Tester\TestCase;
use function assert;

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

    public function testFactoriesAndImportedServices(): void
    {
        $configurator = new Configurator();

        $configurator->setTempDirectory(TEMP_PATH);

        $configurator->addConfig([
            'extensions' => [
                '68publishers.event_dispatcher_extra' => new Statement(EventDispatcherExtraExtension::class),
            ],
            'services' => [
                0 => new Statement(EventDispatcher::class),
                'factory' => [
                    'implement' => DummyServiceFactoryInterface::class,
                ],
                # an imported service is skipped, it receives the dispatcher on its own
                'imported' => [
                    'type' => DummyService::class,
                    'imported' => true,
                ],
            ],
        ]);

        $container = $configurator->createContainer();
        $factory = $container->getByType(DummyServiceFactoryInterface::class);
        assert($factory instanceof DummyServiceFactoryInterface);

        Assert::type(EventDispatcherInterface::class, $factory->create()->getEventDispatcher());
        Assert::false($container->isCreated('imported'));
    }
}

(new EventDispatcherExtraExtensionTestCase())->run();
