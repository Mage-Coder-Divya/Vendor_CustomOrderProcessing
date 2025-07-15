<?php
declare(strict_types=1);

namespace Vendor\CustomOrderProcessing\Api;

/**
 * Interface for updating order status via API.
 */
interface OrderStatusUpdateInterface
{
    /**
     * Update order status by increment ID.
     *
     * @param string $orderIncrementId The order increment ID
     * @param string $newStatus The new status to apply
     * @return array Response indicating success or failure.
     */
    public function updateStatus(string $orderIncrementId, string $newStatus);
}
