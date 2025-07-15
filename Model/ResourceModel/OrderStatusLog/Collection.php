<?php
declare(strict_types=1);

namespace Vendor\CustomOrderProcessing\Model\ResourceModel\OrderStatusLog;

use Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection;
use Vendor\CustomOrderProcessing\Model\OrderStatusLog as Model;
use Vendor\CustomOrderProcessing\Model\ResourceModel\OrderStatusLog as ResourceModel;

/**
 * Collection class for OrderStatusLog entries.
 */
class Collection extends AbstractCollection
{
    /**
     * Define model and resource model for the collection.
     */
    protected function _construct(): void
    {
        $this->_init(Model::class, ResourceModel::class);
    }
}
