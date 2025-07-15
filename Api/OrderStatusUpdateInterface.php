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
     * @return \Vendor\CustomOrderProcessing\Api\Data\OrderStatusResponseInterface
     */
    public function updateStatus(string $orderIncrementId, string $newStatus): \Vendor\CustomOrderProcessing\Api\Data\OrderStatusResponseInterface;
}
