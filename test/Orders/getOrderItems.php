<?php

require_once('amazon/autoload.php');
use XXXXX\Amazon;

# Namespace for all classes of the resource to be called
use XXXXX\Amazon\Orders;

$AmazonOrders = new Orders\Service($SignatureV4);

$OrderItemsRequest = new Orders\OrderItemsRequest();
$OrderItemsRequest->orderId = '408-9435949-0449117';

$AmazonOrders->setOrderItemsRequest(
    $OrderItemsRequest
);

while ($AmazonOrders->ItemsNextToken !== FALSE) {
    $OrdersItems = $AmazonOrders->getOrderItems($AmazonOrders->ItemsNextToken);

    if (!is_null($OrdersItems->Response)) {
        # This object loads results into a ListObjet. An iterator was implemented to access each List element using
        $OrdersItemsList = $OrdersItems->Response;
        foreach ($OrdersItemsList as $OrderItem) {
            echo '<pre>' . Amazon\Amazon::var_dump_2_var($OrderItem) . '</pre>';
        }
    } else {
        echo '<pre>' . Amazon\Amazon::var_dump_2_var($OrdersItems->Errors) . '</pre>';
    }
}
