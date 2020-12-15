<?php
require_once('amazon/autoload.php');
use XXXXX\Amazon;

# Namespace for all classes of the resource to be called
use XXXXX\Amazon\Shippings;

$AmazonShippings = new Shippings\Service($SignatureV4);

$AddressShipTo = new Shippings\Address;
$AddressShipTo->name = 'test name 2';
$AddressShipTo->addressLine1 = 'some Test address 2';
$AddressShipTo->stateOrRegion = 'CA';
$AddressShipTo->countryCode = 'US';
$AddressShipTo->city = 'Los Angeles';
$AddressShipTo->postalCode = '90013';

$AddressShipFrom = new Shippings\Address;
$AddressShipFrom->name = 'test name 1';
$AddressShipFrom->addressLine1 = 'some Test address 1';
$AddressShipFrom->stateOrRegion = 'CA';
$AddressShipFrom->countryCode = 'US';
$AddressShipFrom->city = 'Los Angeles';
$AddressShipFrom->postalCode = '90013';

# Paket 1
$ContainerSpecification = new Shippings\ContainerSpecification;
$ContainerSpecification->dimensions = new Shippings\Dimensions;
$ContainerSpecification->dimensions->unit = Shippings\Unit::CM;
$ContainerSpecification->dimensions->width = 15;
$ContainerSpecification->dimensions->height = 12;
$ContainerSpecification->dimensions->length = 36;

$ContainerSpecification->weight = new Shippings\Weight;
$ContainerSpecification->weight->unit = Shippings\Unit::lb;
$ContainerSpecification->weight->value = 0.08164656;

# Paket 2... n
$RatesRequest = new Shippings\GetRatesRequest();
$RatesRequest->shipTo = $AddressShipTo;
$RatesRequest->shipFrom = $AddressShipFrom;
$RatesRequest->serviceTypes = array(Shippings\ServiceType::AmazonShippingStandard);
#$RatesRequest->shipDate = Shippings\Service::dateWithISO8601(strtotime('now'));
$RatesRequest->containerSpecifications = array($ContainerSpecification);

try {

    $GetRatesResponse = $AmazonShippings->getRates($RatesRequest);
    if (!is_null($GetRatesResponse->Response)) {
        echo '<pre>' . Amazon\Amazon::var_dump_2_var($GetRatesResponse->Response) . '</pre>';
    } else {
        echo '<pre>' . Amazon\Amazon::var_dump_2_var($GetRatesResponse->Errors) . '</pre>';
    }
} catch (GuzzleHttp\Exception\InvalidArgumentException $e) {
    $client = $AmazonShippings->clientRequest;

    var_dump(/*RAW Request payload*/$client->getHTTPBody(),/*RAW Response*/ $client->response);
}
