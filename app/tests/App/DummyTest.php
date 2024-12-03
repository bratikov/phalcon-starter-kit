<?php

declare(strict_types=1);

namespace Tests\App;

use Phalcon\Di\Di;
use PHPUnit\Framework\TestCase;

/**
 * @internal
 */
final class DummyTest extends TestCase
{
	public function testEnvironment(): void
	{
		/** @var \App\Utils\ConfigStub $config */
		$config = Di::getDefault()?->getShared('config');
		$this->assertArrayHasKey('application', $config->toArray());
		$this->assertArrayHasKey('database', $config->toArray());
	}
}
