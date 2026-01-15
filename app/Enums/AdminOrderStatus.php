<?php

namespace App\Enums;

enum AdminOrderStatus: int
{
    case Pending = 1;
    case PendingReview = 2;
    case Assigned = 3;
    case Accepted = 4;
    case Rejected = 5;
    case Preparing = 6;
    case Packed = 7;
    case Shipping = 8;
    case Cancelled = 9;
    case Completed = 10;

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
            self::PendingReview => 'Pending Review',
            self::Assigned => 'Assigned',
            self::Accepted => 'Accepted',
            self::Rejected => 'Rejected',
            self::Preparing => 'Preparing',
            self::Packed => 'Packed',
            self::Shipping => 'Shipping',
            self::Cancelled => 'Cancelled',
            self::Completed => 'Completed',
        };
    }
}
