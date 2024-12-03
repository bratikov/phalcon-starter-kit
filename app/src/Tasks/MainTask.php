<?php

declare(strict_types=1);

namespace App\Tasks;

use Phalcon\Cli\Task;

class MainTask extends Task
{
	public function mainAction(): void
	{
		echo 'This is the default task and the default action'.PHP_EOL;
	}
}
