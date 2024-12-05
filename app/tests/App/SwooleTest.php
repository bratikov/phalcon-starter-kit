<?php

declare(strict_types=1);

namespace Tests\App;

use PHPUnit\Framework\TestCase;
use Swoole\Coroutine;

/**
 * @internal
 */
final class SwooleTest extends TestCase
{
	public function testSwoole(): void
	{
		$counter = 0;
		Coroutine\run(function () use (&$counter) {
			for ($i = 0; $i < 3; ++$i) {
				Coroutine\go(function () use (&$counter) {
					++$counter;
				});
			}
		});
		$this->assertEquals(3, $counter);
	}
}
