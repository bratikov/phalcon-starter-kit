<?php

declare(strict_types=1);

namespace App\Tasks;

use App\Models\Client;
use App\Schemas\System\ERole;
use Phalcon\Cli\Task;
use Symfony\Component\Console\Output\ConsoleOutput;

class ClientTask extends Task
{
	public function createAction(): void
	{
		$console = new ConsoleOutput();
		$console->writeln('<info>You are going to create a new application client</info>');

		$console->write('<question>Enter username: </question>');
		$username = trim((string) fgets(STDIN));

		$console->write('<question>Enter password: </question>');
		$password = trim((string) fgets(STDIN));

		$console->writeln('<info>Select role:</info>');
		$roles = ERole::cases();
		for ($i = 0; $i < count($roles); ++$i) {
			$console->writeln('<comment>'.($i + 1).'. '.ucfirst($roles[$i]->value).'</comment>');
		}

		$console->write('<question>Enter the number corresponding to the role: </question>');
		$role = (int) trim((string) fgets(STDIN));
		if (!array_key_exists($role - 1, $roles)) {
			$console->writeln('<error>Invalid option selected</error>');

			return;
		}
		$role = $roles[$role - 1];

		$client = new Client();
		$client->setUsername($username)
			->setPassword($this->security->hash($password))
			->setRole($role->value);

		if ($client->save()) {
			$console->writeln('<info>Client created successfully</info>');
		} else {
			$console->writeln('<error>Failed to create client</error>');
		}
	}
}
