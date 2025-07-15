<?php
declare(strict_types=1);

namespace Vendor\CustomOrderProcessing\Model;

use Vendor\CustomOrderProcessing\Api\OrderStatusUpdateInterface;
use Vendor\CustomOrderProcessing\Api\Data\OrderStatusResponseInterface;
use Vendor\CustomOrderProcessing\Model\Data\OrderStatusResponse;
use Magento\Sales\Api\OrderRepositoryInterface;
use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\Webapi\Exception as WebapiException;
use Magento\Sales\Model\ResourceModel\Order\CollectionFactory as OrderCollectionFactory;

/**
 * Class to update order status via custom API.
 */
class OrderStatusUpdate implements OrderStatusUpdateInterface
{
    /**
     * @var OrderRepositoryInterface
     */
    private OrderRepositoryInterface $orderRepository;

    /**
     * @var OrderCollectionFactory
     */
    private OrderCollectionFactory $orderCollectionFactory;

    /**
     * @var OrderStatusResponseInterface
     */
    private OrderStatusResponseInterface $response;

    /**
     * Constructor
     *
     * @param OrderRepositoryInterface $orderRepository
     * @param OrderCollectionFactory $orderCollectionFactory
     * @param OrderStatusResponseInterface $response
     */
    public function __construct(
        OrderRepositoryInterface $orderRepository,
        OrderCollectionFactory $orderCollectionFactory,
        OrderStatusResponseInterface $response
    ) {
        $this->orderRepository = $orderRepository;
        $this->orderCollectionFactory = $orderCollectionFactory;
        $this->response = $response;
    }

    /**
     * Update the order status by increment ID.
     *
     * @param string $orderIncrementId
     * @param string $newStatus
     * @return OrderStatusResponseInterface
     * @throws WebapiException
     */
    public function updateStatus(string $orderIncrementId, string $newStatus): OrderStatusResponseInterface
    {
        try {
            $orderCollection = $this->orderCollectionFactory->create()
                ->addFieldToFilter('increment_id', $orderIncrementId)
                ->setPageSize(1);

            $order = $orderCollection->getFirstItem();

            if (!$order || !$order->getEntityId()) {
                throw new WebapiException(__('Order not found'));
            }

            $order = $this->orderRepository->get((int)$order->getEntityId());

            $currentStatus = $order->getStatus();
            $allowedStatuses = $order->getConfig()->getStateStatuses($order->getState());

            echo "Below Is The List Of Allowed Statuses - ";
            print_r($allowedStatuses);
            if (!array_key_exists($newStatus, $allowedStatuses)) {
                throw new LocalizedException(
                    __('Status transition not allowed from %1 to %2', $currentStatus, $newStatus)
                );
            }

            $order->setStatus($newStatus);
            $order->addStatusHistoryComment(__('Order status updated via API to "%1".', $newStatus));
            $this->orderRepository->save($order);

            return $this->response
                ->setSuccess(true)
                ->setMessage('Order status updated successfully');
        } catch (LocalizedException $e) {
            throw new WebapiException(__($e->getMessage()));
        } catch (\Exception $e) {
            throw new WebapiException(__('Something went wrong'));
        }
    }
}
