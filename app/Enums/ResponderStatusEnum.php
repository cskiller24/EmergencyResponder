<?php

declare(strict_types=1);

namespace App\Enums;

use App\Enums\Contracts\EnumStatus;

enum ResponderStatusEnum: int implements EnumStatus
{
    case ACTIVE = 1;
    case INACTIVE = 2;
    case ARCHIVED = 3;

    public function getMessage(): string
    {
        return match ($this) {
            static::ACTIVE => 'Responder is on draft',
            static::INACTIVE => 'Responder is submitted',
            static::ARCHIVED => 'Responder is approved',
        };
    }

    public function titleCase(): string
    {
        return match ($this) {
            static::ACTIVE => 'Active',
            static::INACTIVE => 'Inactive',
            static::ARCHIVED => 'Archived'
        };
    }

    public static function randomValue(): int
    {
        $arr = array_column(self::cases(), 'value');

        return $arr[array_rand($arr)];
    }

    public static function randomName(): string
    {
        $arr = array_column(self::cases(), 'name');

        return $arr[array_rand($arr)];
    }
}
