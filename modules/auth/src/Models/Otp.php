<?php

declare(strict_types=1);

namespace Modules\Auth\Models;

use App\Models\User;
use Illuminate\Database\Eloquent\Attributes\UseFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use Modules\Auth\Database\Factories\OtpFactory;
use Modules\Auth\Enums\NoticeType;

#[UseFactory(OtpFactory::class)]
final class Otp extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $guarded = ['id'];

    protected $hidden = [
        'otp_code',
    ];

    /**
     * @return BelongsTo<User, $this>
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    protected function casts(): array
    {
        return [
            'type' => NoticeType::class,
            'used' => 'boolean',
        ];
    }
}
