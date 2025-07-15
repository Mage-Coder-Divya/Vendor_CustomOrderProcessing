# Custom Order Status Update Module

This Magento 2 module allows updating the order status programmatically through a custom API endpoint, with logging and email notification features.

---

## Module Features

- Update Magento order status via API (`POST /V1/custom-order/status`)
- Status change validation based on allowed transitions
- Status change logs saved to a custom table
- Email notification on shipped/complete status
- Follows PSR-4, Magento 2 coding standards

---

## Installation

1. **Copy the Module**
   ```bash
   app/code/Vendor/CustomOrderProcessing

2. **Enable Module**
   bin/magento module:enable Vendor_CustomOrderProcessing
   
   bin/magento setup:upgrade

   bin/magento cache:flush

## Architectural Decisions

1. Order Status Validation
   The order status is only updated if it is valid for the current order state
   
2. Order Repository
   Used Magento's OrderRepositoryInterface to load and save orders to ensure we follow Magento service contracts.

3. OrderStatusLog Model
   A custom model (OrderStatusLog) is used to log changes. This provides auditability and debugging support.

4. Observer-Based Email
   An observer listens for order status changes and triggers email only for 'shipped' and 'complete' statuses. This decouples status change logic from email logic.

