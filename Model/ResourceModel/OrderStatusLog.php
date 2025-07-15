<?php
declare(strict_types=1);

namespace Vendor\CustomOrderProcessing\Model\ResourceModel;

use Magento\Framework\Model\ResourceModel\Db\AbstractDb;

/**
 * Resource model for custom order status log.
 */
class OrderStatusLog extends AbstractDb
{
    /**
     * Initialize table and primary key.
     */
    protected function _construct(): void
    {
        $this->_init('vendor_order_status_log', 'log_id');
    }
}
