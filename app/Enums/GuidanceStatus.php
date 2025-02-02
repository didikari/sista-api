<?php

namespace App\Enums;

enum GuidanceStatus: string
{
    case Pending = 'pending';
    case Approved = 'approved';
    case Revision = 'revision';
    case Rejected = 'rejected';

    public static function getValues(): array
    {
        return array_map(fn($enum) => $enum->value, self::cases());
    }
}
