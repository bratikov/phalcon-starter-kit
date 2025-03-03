<?php

declare(strict_types=1);

namespace App\Schemas\System;

enum ERole: string
{
	case ROLE_OWNER = 'owner';
	case ROLE_MAINTAINER = 'maintainer';
	case ROLE_USER = 'user';
}
