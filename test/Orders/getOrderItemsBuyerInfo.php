<?php

require_once('amazon/autoload.php');
use XXXXX\Amazon;

# Namespace for all classes of the resource to be called
use XXXXX\Amazon\Orders;

$AmazonOrders = new Orders\Service($SignatureV4);

$OrderItemsBuyerInfoRequest = new Orders\OrderItemsBuyerInfoRequest();
$OrderItemsBuyerInfoRequest->orderId = '302-7275622-0198745';

$AmazonOrders->setOrderItemsBuyerInfoRequest(
    $OrderItemsBuyerInfoRequest
);

while ($AmazonOrders->ItemsBuyerInfoNextToken !== FALSE) {
    $ItemsBuyerInfos = $AmazonOrders->getOrderItemsBuyerInfo($AmazonOrders->ItemsBuyerInfoNextToken);

    if (!is_null($ItemsBuyerInfos->Response)) {
        # Dieses Objekt lädt Ergebnisse in ein ListObjet. Ein Iterator wurde implementiert um auf jedes einzelne List Element mittels
        # foreach zugreifen zu können
        $ItemsBuyerInfosList = $ItemsBuyerInfos->Response;
        foreach ($ItemsBuyerInfosList as $ItemsBuyerInfo) {
            echo '<pre>' . Amazon\Amazon::var_dump_2_var($ItemsBuyerInfo) . '</pre>';
        }
    } else {
        echo '<pre>' . Amazon\Amazon::var_dump_2_var($ItemsBuyerInfos->Errors) . '</pre>';
    }
}
