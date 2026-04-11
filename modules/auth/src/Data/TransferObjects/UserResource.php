<?php

namespace Modules\Auth\Data\TransferObjects;

use Spatie\LaravelData\Resource;

class UserResource extends Resource
{
    public function __construct(
        public int $id,
        public string $name,
        public string $mobile,
        public ?string $email,
        public ?string $mobileVerifiedAt,
    ) {}

    public static function fromModel($user): self
    {
        return new self(
            id: $user->id,
            name: $user->name,
            mobile: $user->mobile,
            email: $user->email,
            mobileVerifiedAt: $user->mobile_verified_at?->toISOString(),
        );
    }
}
