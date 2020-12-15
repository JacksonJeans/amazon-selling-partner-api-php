<?php
require_once('amazon/autoload.php');
use XXXXX\Amazon;

# Namespace for all classes of the resource to be called
use XXXXX\Amazon\Shippings;

$AmazonShippings = new Shippings\Service($SignatureV4);

$clientReferenceId = '911-7267646-6348616';

# Adressen
$AddressShipTo = new Shippings\Address;
$AddressShipTo->name = 'test name 2';
$AddressShipTo->addressLine1 = 'some Test address 2';
$AddressShipTo->stateOrRegion = 'CA';
$AddressShipTo->countryCode = 'US';
$AddressShipTo->city = 'Los Angeles';
$AddressShipTo->postalCode = '90013';
$AddressShipTo->email = 'testEmail2@amazon.com';
$AddressShipTo->phoneNumber = '1234567890';

$AddressShipFrom = new Shippings\Address;
$AddressShipFrom->name = 'test name 1';
$AddressShipFrom->addressLine1 = 'some Test address 1';
$AddressShipFrom->stateOrRegion = 'CA';
$AddressShipFrom->countryCode = 'US';
$AddressShipFrom->city = 'Los Angeles';
$AddressShipFrom->postalCode = '90013';
$AddressShipFrom->email = 'testEmail1@amazon.com';
$AddressShipFrom->phoneNumber = '1234567890';

# Container 1 
$Container = new Shippings\Container;
$Container->containerType = Shippings\ContainerType::PACKAGE;
$Container->containerReferenceId = 'ContainerRefId-01';
$Container->dimensions = new Shippings\Dimensions;
$Container->dimensions->unit = Shippings\Unit::CM;
$Container->dimensions->length = 36;
$Container->dimensions->width = 15;
$Container->dimensions->height = 12;

# Items[1,2] in Container 1

# Item 1
$ContainerItems[0] = new Shippings\ContainerItem;
$ContainerItems[0]->quantity = 2;

$ContainerItems[0]->unitPrice = new Shippings\Currency;
$ContainerItems[0]->unitPrice->value = 14.99;
$ContainerItems[0]->unitPrice->unit = 'USD';

$ContainerItems[0]->unitWeight = new Shippings\Weight;
$ContainerItems[0]->unitWeight->value = 0.08164656;
$ContainerItems[0]->unitWeight->unit = Shippings\Unit::lb;

$ContainerItems[0]->title = 'String';

$Container->items = (array) $ContainerItems;

$Container->value = new Shippings\Currency;
$Container->weight = new Shippings\Weight;

foreach ($ContainerItems as $ContainerItem) {
    $Container->value->value += $ContainerItem->unitPrice->value * $ContainerItem->quantity;
    $Container->weight->value += $ContainerItem->unitWeight->value * $ContainerItem->quantity;
}

$Container->value->unit = 'USD';
$Container->weight->unit = Shippings\Unit::lb;

$CreateShipmentRequest = new Shippings\CreateShipmentRequest;
$CreateShipmentRequest->clientReferenceId = $clientReferenceId;
$CreateShipmentRequest->shipTo = $AddressShipTo;
$CreateShipmentRequest->shipFrom = $AddressShipFrom;
$CreateShipmentRequest->containers = array($Container);

$CreateShipmentResponse = $AmazonShippings->createShipment($CreateShipmentRequest);

if (!is_null($CreateShipmentResponse->Response)) {
    echo '<pre>' . Amazon\Amazon::var_dump_2_var($CreateShipmentResponse->Response) . '</pre>';
} else {
    echo '<pre>' . Amazon\Amazon::var_dump_2_var($CreateShipmentResponse->Errors) . '</pre>';
}