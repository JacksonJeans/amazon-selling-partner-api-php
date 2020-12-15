<?php
require_once('amazon/autoload.php');

use XXXXX\Amazon;

# Namespace for all classes of the resource to be called
use XXXXX\Amazon\FulfillmentInbound;

$Service = new FulfillmentInbound\Service($SignatureV4);

$ShipmentItemsByShipmentId = $Service->getShipmentItemsByShipmentId('FBA15DDQDL4V', 'A1PA6795UKMFR9');

if (!is_null($ShipmentItemsByShipmentId->Response)) {
    echo '<pre>' . Amazon\Amazon::var_dump_2_var($ShipmentItemsByShipmentId->Response) . '</pre>';
} else {
    echo '<pre>' . Amazon\Amazon::var_dump_2_var($ShipmentItemsByShipmentId->Errors) . '</pre>';
}
