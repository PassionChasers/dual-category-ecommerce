<?php

namespace App\Helpers;

use App\Enums\AdminOrderStatus;

class StatusHelper
{
    /**
     * Map numeric status values to display labels
     */
    public static function getStatusLabel($status): string
    {
        if (is_string($status)) {
            // If already a string, convert it to numeric first
            $status = self::convertStringToNumeric($status);
        }

        try {
            return AdminOrderStatus::from($status)->label();
        } catch (\ValueError $e) {
            return 'Unknown';
        }
    }

    /**
     * Convert string status to numeric value
     */
    public static function convertStringToNumeric($statusString): int
    {
        $mapping = [
            'pending' => AdminOrderStatus::Pending->value,
            'pendingreview' => AdminOrderStatus::PendingReview->value,
            'assigned' => AdminOrderStatus::Assigned->value,
            'accepted' => AdminOrderStatus::Accepted->value,
            'rejected' => AdminOrderStatus::Rejected->value,
            'preparing' => AdminOrderStatus::Preparing->value,
            'packed' => AdminOrderStatus::Packed->value,
            'shipping' => AdminOrderStatus::Shipping->value,
            'dispatched' => AdminOrderStatus::Shipping->value, // Alias
            'delivered' => AdminOrderStatus::Completed->value, // Treat as completed
            'cancelled' => AdminOrderStatus::Cancelled->value,
            'completed' => AdminOrderStatus::Completed->value,
        ];

        return $mapping[strtolower($statusString)] ?? 0;
    }

    /**
     * Get status badge colors based on numeric status
     */
    public static function getStatusColors($status): array
    {
        if (is_string($status)) {
            $status = self::convertStringToNumeric($status);
        }

        $statusMap = [
            AdminOrderStatus::Pending->value => ['bg' => 'bg-yellow-100', 'text' => 'text-yellow-800'],
            AdminOrderStatus::PendingReview->value => ['bg' => 'bg-yellow-100', 'text' => 'text-yellow-800'],
            AdminOrderStatus::Assigned->value => ['bg' => 'bg-blue-100', 'text' => 'text-blue-800'],
            AdminOrderStatus::Accepted->value => ['bg' => 'bg-blue-100', 'text' => 'text-blue-800'],
            AdminOrderStatus::Rejected->value => ['bg' => 'bg-red-100', 'text' => 'text-red-800'],
            AdminOrderStatus::Preparing->value => ['bg' => 'bg-blue-100', 'text' => 'text-blue-800'],
            AdminOrderStatus::Packed->value => ['bg' => 'bg-blue-100', 'text' => 'text-blue-800'],
            AdminOrderStatus::Shipping->value => ['bg' => 'bg-blue-100', 'text' => 'text-blue-800'],
            AdminOrderStatus::Cancelled->value => ['bg' => 'bg-red-100', 'text' => 'text-red-800'],
            AdminOrderStatus::Completed->value => ['bg' => 'bg-green-100', 'text' => 'text-green-800'],
        ];

        return $statusMap[$status] ?? ['bg' => 'bg-gray-100', 'text' => 'text-gray-800'];
    }
}
