<?php
declare(strict_types=1);

namespace Vendor\CustomOrderProcessing\Observer;

use Magento\Framework\App\Area;
use Magento\Framework\Event\Observer;
use Magento\Framework\Event\ObserverInterface;
use Magento\Framework\Mail\Template\TransportBuilder;
use Magento\Sales\Model\Order;
use Magento\Store\Model\StoreManagerInterface;
use Magento\Email\Model\TemplateFactory;
use Psr\Log\LoggerInterface;
use Vendor\CustomOrderProcessing\Model\OrderStatusLogFactory;

/**
 * Observer to handle order status changes.
 */
class OrderStatusObserver implements ObserverInterface
{
    /**
     * @var LoggerInterface
     */
    private LoggerInterface $logger;

    /**
     * @var TransportBuilder
     */
    private TransportBuilder $transportBuilder;

    /**
     * @var StoreManagerInterface
     */
    private StoreManagerInterface $storeManager;

    /**
     * @var TemplateFactory
     */
    private TemplateFactory $templateFactory;

    /**
     * @var OrderStatusLogFactory
     */
    private OrderStatusLogFactory $orderStatusLogFactory;

    /**
     * Constructor
     *
     * @param LoggerInterface $logger
     * @param TransportBuilder $transportBuilder
     * @param StoreManagerInterface $storeManager
     * @param TemplateFactory $templateFactory
     * @param OrderStatusLogFactory $orderStatusLogFactory
     */
    public function __construct(
        LoggerInterface $logger,
        TransportBuilder $transportBuilder,
        StoreManagerInterface $storeManager,
        TemplateFactory $templateFactory,
        OrderStatusLogFactory $orderStatusLogFactory
    ) {
        $this->logger = $logger;
        $this->transportBuilder = $transportBuilder;
        $this->storeManager = $storeManager;
        $this->templateFactory = $templateFactory;
        $this->orderStatusLogFactory = $orderStatusLogFactory;
    }

    /**
     * Execute observer
     *
     * @param Observer $observer
     * @return void
     */
    public function execute(Observer $observer): void
    {
        /** @var Order $order */
        $order = $observer->getEvent()->getOrder();

        $newStatus = $order->getStatus();
        $oldStatus = $order->getOrigData('status');

        if (!empty($oldStatus) && $oldStatus !== $newStatus) {
            $this->logOrderStatusChange($order, $oldStatus, $newStatus);
            $this->sendStatusEmailIfApplicable($order, $newStatus);
        }
    }

    /**
     * Log status change to custom table.
     *
     * @param Order $order
     * @param string $oldStatus
     * @param string $newStatus
     * @return void
     */
    private function logOrderStatusChange(Order $order, string $oldStatus, string $newStatus): void
    {
        try {
            $log = $this->orderStatusLogFactory->create();
            $log->setData([
                'order_id' => $order->getEntityId(),
                'old_status' => $oldStatus,
                'new_status' => $newStatus
            ]);
            $log->save();
        } catch (\Exception $e) {
            $this->logger->error('Failed to log order status change: ' . $e->getMessage());
        }
    }

    /**
     * Send order shipped/completed email if applicable.
     *
     * @param Order $order
     * @param string $newStatus
     * @return void
     */
    private function sendStatusEmailIfApplicable(Order $order, string $newStatus): void
    {
        if ($newStatus === Order::STATE_COMPLETE || $newStatus === 'shipped') {
            try {
                $transport = $this->transportBuilder
                    ->setSubject('Order Status Updated')
                    ->setTemplateIdentifier('custom_order_shipped_email')
                    ->setTemplateOptions([
                        'area' => Area::AREA_FRONTEND,
                        'store' => $order->getStoreId()
                    ])
                    ->setTemplateVars(['order' => $order])
                    ->setFrom('general')
                    ->addTo($order->getCustomerEmail(), $order->getCustomerName())
                    ->getTransport();

                $transport->sendMessage();
            } catch (\Exception $e) {
                $this->logger->error('Order shipped email failed: ' . $e->getMessage());
            }
        }
    }
}
