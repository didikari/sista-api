<?php

namespace App\Enums;

enum EventStatus: string
{
    case Pending = 'pending';
    case Aapproved = 'approved';
    case Rejected = 'rejected';
    case Scheduled = 'scheduled';
    case Completed = 'completed';

    public static function getValues(): array
    {
        return array_map(fn($enum) => $enum->value, self::cases());
    }
}
