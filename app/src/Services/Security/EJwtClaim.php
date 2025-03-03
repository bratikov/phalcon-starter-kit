<?php

declare(strict_types=1);

namespace App\Services\Security;

enum EJwtClaim: string
{
	case ROLE = 'role';
}
