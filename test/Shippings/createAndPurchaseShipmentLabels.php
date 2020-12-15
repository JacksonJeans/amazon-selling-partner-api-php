<?php
require_once('amazon/autoload.php');
use XXXXX\Amazon;

# Namespace for all classes of the resource to be called
use XXXXX\Amazon\Shippings;

$AmazonShippings = new Shippings\Service($SignatureV4);

$clientReferenceId = 'ReferenzDesClientsMAX40Zeichen';

# Adressen
$AddressShipTo = new Shippings\Address;
$AddressShipTo->name = 'Julian Tietz';
$AddressShipTo->addressLine1 = 'xxxxxx 34';
$AddressShipTo->addressLine2 = 'optional';
$AddressShipTo->addressLine3 = 'optional';
$AddressShipTo->stateOrRegion = 'NRW';
$AddressShipTo->countryCode = 'DE';
$AddressShipTo->city = 'Krefeld';
$AddressShipTo->postalCode = '47799';
$AddressShipTo->email = 'julian.tietz@gidutex.de';
$AddressShipTo->copyEmails = array('email1@address.de');
$AddressShipTo->phoneNumber = '1234567890';

$AddressShipFrom = new Shippings\Address;
$AddressShipFrom->name = 'GIDUTEX International GmbH';
$AddressShipFrom->addressLine1 = 'required';
$AddressShipFrom->addressLine2 = 'optional';
$AddressShipFrom->addressLine3 = 'optional';
$AddressShipFrom->stateOrRegion = 'NRW';
$AddressShipFrom->countryCode = 'DE';
$AddressShipFrom->city = 'Krefeld';
$AddressShipFrom->postalCode = '47804';
$AddressShipFrom->email = 'julian.tietz@gidutex.de';
$AddressShipFrom->copyEmails = array('email1@address.de');
$AddressShipFrom->phoneNumber = '1234567890';

# Container 1 
$Container = new Shippings\Container;
$Container->containerType = Shippings\ContainerType::PACKAGE;
$Container->containerReferenceId = 'ReferenzZumPaket';
$Container->dimensions = new Shippings\Dimensions;
$Container->dimensions->unit = Shippings\Unit::CM;
$Container->dimensions->length = 100;
$Container->dimensions->width = 100;
$Container->dimensions->height = 50;

# Items[1,2] in Container 1

# Item 1
$ContainerItems[0] = new Shippings\ContainerItem;
$ContainerItems[0]->quantity = 72;

$ContainerItems[0]->unitPrice = new Shippings\Currency;
$ContainerItems[0]->unitPrice->value = 12.99;
$ContainerItems[0]->unitPrice->unit = 'EUR';

$ContainerItems[0]->unitWeight = new Shippings\Weight;
$ContainerItems[0]->unitWeight->value = 0.1;
$ContainerItems[0]->unitWeight->unit = Shippings\Unit::kg;

$ContainerItems[0]->title = 'FLEXFIT Cap';

# Item 2
$ContainerItems[1] = new Shippings\ContainerItem;
$ContainerItems[1]->quantity = 10;

$ContainerItems[1]->unitPrice = new Shippings\Currency;
$ContainerItems[1]->unitPrice->value = 18.99;
$ContainerItems[1]->unitPrice->unit = 'EUR';

$ContainerItems[1]->unitWeight = new Shippings\Weight;
$ContainerItems[1]->unitWeight->value = 0.1;
$ContainerItems[1]->unitWeight->unit = Shippings\Unit::kg;

$ContainerItems[1]->title = 'GILDAN Sweatshirt';

$Container->items = (array) $ContainerItems;

$Container->value = new Shippings\Currency;
$Container->weight = new Shippings\Weight;

foreach ($ContainerItems as $ContainerItem) {
    $Container->value->value += $ContainerItem->unitPrice->value * $ContainerItem->quantity;
    $Container->weight->value += $ContainerItem->unitWeight->value * $ContainerItem->quantity;
}

$Container->value->unit = 'EUR';
$Container->weight->unit = Shippings\Unit::kg;

$CreateShipmentRequest = new Shippings\CreateShipmentRequest;
$CreateShipmentRequest->clientReferenceId = $clientReferenceId;
$CreateShipmentRequest->shipTo = $AddressShipTo;
$CreateShipmentRequest->shipFrom = $AddressShipFrom;
$CreateShipmentRequest->containers = array($Container); #$ContainerList;

$CreateShipmentResponse = $AmazonShippings->createShipment($CreateShipmentRequest);

if (!is_null($CreateShipmentResponse->Response)) {
    $CreateShipmentResponse = $CreateShipmentResponse->Response;

    # shipmentId - wichtig, zum kaufen benötigt.
    $shipmentId = $CreateShipmentResponse->shipmentId;

    # berechtigte Rates:
    foreach ($CreateShipmentResponse->eligibleRates as $Rate) {
        echo '<pre>' . Amazon\Amazon::var_dump_2_var($Rate) . '</pre>';
    }

    # bspw. einfach mal den ersten Rate nehmen
    $rateIdToBuy = $CreateShipmentResponse->eligibleRates[0]->rateId;

    $LabelSpecification = new Shippings\LabelSpecification;
    $LabelSpecification->labelFormat = Shippings\LabelFormat::PNG;
    $LabelSpecification->labelStockSize = Shippings\LabelStockSize::LabelStockSize_4x6;

    $PurchaseLabelsRequest = new Shippings\PurchaseLabelsRequest;
    $PurchaseLabelsRequest->rateId = $rateIdToBuy;
    $PurchaseLabelsRequest->labelSpecification = $LabelSpecification;

    $PurchaseLabelsResponse = $AmazonShippings->purchaseLabel($shipmentId, $PurchaseLabelsRequest);

    if (!is_null($PurchaseLabelsResponse->Response)) {
        $PurchaseLabelsResponse = $PurchaseLabelsResponse->Response;

        #ShipmentId
        $shipmentId  = $PurchaseLabelsResponse->shipmentId;

        #client Referenz
        $clientReferenceId = $PurchaseLabelsResponse->clientReferenceId;

        # Akzeptierte Rate
        $acceptedRate = $PurchaseLabelsResponse->acceptedRate;

        echo '<pre>' . Amazon\Amazon::var_dump_2_var($shipmentId, $clientReferenceId, $acceptedRate) . '</pre>';

        # Alle Labels
        foreach ($PurchaseLabelsResponse->labelResults as $label) {
            # containerReferenceId 
            $containerReferenceId  = $label->containerReferenceId;

            # trackingId
            $trackingId = $label->trackingId;

            # Label
            $labelObject = $label->label;
            $labelFile = base64_decode($labelObject->labelStream);
            $labelSpecification = $labelObject->labelSpecification;

            echo '<pre>' . Amazon\Amazon::var_dump_2_var($containerReferenceId, $trackingId, $labelFile, $labelSpecification) . '</pre>';
        }

        #Error - die Labels konnten nicht gekauft werden
    } else {
        echo '<pre>' . Amazon\Amazon::var_dump_2_var($PurchaseLabelsResponse->Errors) . '</pre>';
    }
    #Error - die Sendung konnte nicht erstellt werden.
} else {
    echo '<pre>' . Amazon\Amazon::var_dump_2_var($CreateShipmentResponse->Errors) . '</pre>';
}
