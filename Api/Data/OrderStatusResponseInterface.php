<?php
declare(strict_types=1);

namespace Vendor\CustomOrderProcessing\Api\Data;

/**
 * Interface for order status update response.
 */
interface OrderStatusResponseInterface
{
    /**
     * Get success flag.
     *
     * @return bool
     */
    public function getSuccess(): bool;

    /**
     * Set success flag.
     *
     * @param bool $success
     * @return $this
     */
    public function setSuccess(bool $success);

    /**
     * Get message.
     *
     * @return string
     */
    public function getMessage(): string;

    /**
     * Set message.
     *
     * @param string $message
     * @return $this
     */
    public function setMessage(string $message);
}
