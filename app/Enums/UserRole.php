<?php

declare(strict_types=1);

namespace App\Enums;

use App\Concerns\EnumDataListTrait;
use App\Enums\UserPermission as P;
use Spatie\Permission\Models\Role;

enum UserRole: string
{
    use EnumDataListTrait;

    case Admin = 'admin';

    case Premium = 'premium';

    public function model(): Role
    {
        return Role::findByName($this->value);
    }

    public function permissions(): array
    {
        return match ($this) {
            self::Admin => P::cases(),
            self::Premium => [
                P::Upload,
            ],
        };
    }

    public function getPermissionNames(): array
    {
        return collect($this->permissions())->map->value->toArray();
    }

    public function rateLimit(): int
    {
        return match ($this) {
            self::Admin => 100,
            self::Premium => 1000,
        };
    }
}
