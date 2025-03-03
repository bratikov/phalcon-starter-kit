<?php

declare(strict_types=1);

namespace App\Attributes;

use App\Schemas\System\ERole;

#[\Attribute(\Attribute::TARGET_METHOD)]
class Accessable
{
	/** @var ERole[] */
	private array $roles;

	public function __construct(ERole ...$roles)
	{
		$this->roles = $roles;
	}

	public function hasRole(ERole $role): bool
	{
		return in_array($role, $this->roles, true);
	}
}
