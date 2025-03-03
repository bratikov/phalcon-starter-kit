<?php

declare(strict_types=1);

namespace App\Schemas\System;

class Identity
{
	private ERole $clientRole;

	public function __construct(ERole $clientRole)
	{
		$this->clientRole = $clientRole;
	}

	public function getClientRole(): ERole
	{
		return $this->clientRole;
	}
}
