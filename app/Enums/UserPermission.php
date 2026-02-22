<?php

declare(strict_types=1);

namespace App\Enums;

use App\Concerns\EnumDataListTrait;
use Spatie\Permission\Middleware\PermissionMiddleware;
use Spatie\Permission\Models\Permission;

enum UserPermission: string
{
    use EnumDataListTrait;

    case SeePanel = 'see_panel';

    case ManageUsers = 'manage_users';

    public function model(): Permission
    {
        return Permission::findByName($this->value);
    }

    public function middleware(): string
    {
        return PermissionMiddleware::using($this);
    }
}
