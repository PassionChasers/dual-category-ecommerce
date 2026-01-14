<?php

namespace App\Enums;

enum UserRole: int
{
    case Customer = 1;           // Regular user who orders
    case Supplier = 2;           // Medical store owner
    case Restaurant = 3;         // Food restaurant owner
    case Admin = 4;              // System administrator
    case DeliveryMan = 5;        // Delivery personnel

    /**
     * Get all available role values
     */
    public static function values(): array
    {
        return array_map(fn($case) => $case->value, self::cases());
    }

    /**
     * Get role label for display
     */
    public function label(): string
    {
        return match($this) {
            self::Customer => 'Customer',
            self::Supplier => 'Supplier',
            self::Restaurant => 'Restaurant',
            self::Admin => 'Admin',
            self::DeliveryMan => 'Delivery Man',
        };
    }
}
