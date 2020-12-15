<?php
require_once('amazon/autoload.php');

use XXXXX\Amazon;

# Namespace for all classes of the resource to be called
use XXXXX\Amazon\FulfillmentInbound;

$Service = new FulfillmentInbound\Service($SignatureV4);

$ShipmentItemRequest = new FulfillmentInbound\ShipmentItemRequest;
$ShipmentItemRequest->LastUpdatedBefore =  Amazon\Amazon::dateWithISO8601WithoutTimeZone(strtotime('today'));
$ShipmentItemRequest->LastUpdatedAfter =  Amazon\Amazon::dateWithISO8601WithoutTimeZone(strtotime('yesterday'));
$ShipmentItemRequest->QueryType = FulfillmentInbound\QueryType::DATE_RANGE;
$ShipmentItemRequest->MarketplaceId = 'A1PA6795UKMFR9';

$Service->setShipmentItemRequest(
    $ShipmentItemRequest
);

while ($Service->ShipmentItemNextToken !== FALSE) {

    $ShipmentItems = $Service->getShipmentItems($Service->ShipmentItemNextToken);

    if (!is_null($ShipmentItems->Response)) {
        echo '<pre>' . Amazon\Amazon::var_dump_2_var($ShipmentItems->Response) . '</pre>';
    } else {
        echo '<pre>' . Amazon\Amazon::var_dump_2_var($ShipmentItems->Errors) . '</pre>';
    }
}
