<?php

namespace App\Enums;

enum CustomerOrderStatus: int
{
    case Pending = 1;
    case Confirmed = 2;
    case Preparing = 3;
    case Packed = 4;
    case Shipping = 5;
    case Cancelled = 6;
    case Completed = 7;

    /**
     * Get all available status values
     */
    public static function values(): array
    {
        return array_map(fn($case) => $case->value, self::cases());
    }

    /**
     * Get status label for display
     */
    public function label(): string
    {
        return match($this) {
            self::Pending => 'Pending',
            self::Confirmed => 'Confirmed',
            self::Preparing => 'Preparing',
            self::Packed => 'Packed',
            self::Shipping => 'Shipping',
            self::Cancelled => 'Cancelled',
            self::Completed => 'Completed',
        };
    }
}
