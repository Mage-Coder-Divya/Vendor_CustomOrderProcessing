<?php
declare(strict_types=1);

namespace Vendor\CustomOrderProcessing\Model;

use Magento\Framework\Model\AbstractModel;
use Vendor\CustomOrderProcessing\Model\ResourceModel\OrderStatusLog as OrderStatusLogResource;

/**
 * Model for logging custom order status updates.
 */
class OrderStatusLog extends AbstractModel
{
    /**
     * Initialize OrderStatusLog model.
     */
    protected function _construct(): void
    {
        $this->_init(OrderStatusLogResource::class);
    }
}
