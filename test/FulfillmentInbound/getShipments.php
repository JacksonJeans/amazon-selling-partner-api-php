<?php
require_once('amazon/autoload.php');

use XXXXX\Amazon;

# Namespace for all classes of the resource to be called
use XXXXX\Amazon\FulfillmentInbound;

$Service = new FulfillmentInbound\Service($SignatureV4);

$ShipmentRequest = new FulfillmentInbound\ShipmentsRequest;
$ShipmentRequest->ShipmentStatusList = array(FulfillmentInbound\ShipmentStatus::WORKING,FulfillmentInbound\ShipmentStatus::SHIPPED);
$ShipmentRequest->LastUpdatedAfter = Amazon\Amazon::dateWithISO8601WithoutTimeZone(strtotime('yesterday'));
$ShipmentRequest->LastUpdatedBefore = Amazon\Amazon::dateWithISO8601WithoutTimeZone(strtotime('today'));
$ShipmentRequest->QueryType = FulfillmentInbound\QueryType::DATE_RANGE;
$ShipmentRequest->MarketplaceId = 'A1PA6795UKMFR9';

$Service->setShipmentsRequest(
    $ShipmentRequest
);

while ($Service->ShipmentsNextToken !== FALSE) {

    $Shipments = $Service->getShipments($Service->ShipmentsNextToken);

    if (!is_null($Shipments->Response)) {
        echo '<pre>' . Amazon\Amazon::var_dump_2_var($Shipments->Response) . '</pre>';
    } else {
        echo '<pre>' . Amazon\Amazon::var_dump_2_var($Shipments->Errors) . '</pre>';
    }
}
