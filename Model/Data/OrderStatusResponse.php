<?php
declare(strict_types=1);

namespace Vendor\CustomOrderProcessing\Model\Data;

use Vendor\CustomOrderProcessing\Api\Data\OrderStatusResponseInterface;

/**
 * Implementation of OrderStatusResponseInterface.
 */
class OrderStatusResponse implements OrderStatusResponseInterface
{
    /**
     * @var bool
     */
    private bool $success;

    /**
     * @var string
     */
    private string $message;

    /**
     * Get success flag.
     *
     * @return bool
     */
    public function getSuccess(): bool
    {
        return $this->success;
    }

    /**
     * Set success flag.
     *
     * @param bool $success
     * @return $this
     */
    public function setSuccess(bool $success): self
    {
        $this->success = $success;
        return $this;
    }

    /**
     * Get message.
     *
     * @return string
     */
    public function getMessage(): string
    {
        return $this->message;
    }

    /**
     * Set message.
     *
     * @param string $message
     * @return $this
     */
    public function setMessage(string $message): self
    {
        $this->message = $message;
        return $this;
    }
}
