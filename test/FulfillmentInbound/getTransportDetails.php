<?php
require_once('amazon/autoload.php');

use XXXXX\Amazon;

# Namespace for all classes of the resource to be called
use XXXXX\Amazon\FulfillmentInbound;

$Service = new FulfillmentInbound\Service($SignatureV4);

$TransportDetails = $Service->getTransportDetails('FBA15DDQDL4V');

if (!is_null($TransportDetails->Response)) {
    echo '<pre>' . Amazon\Amazon::var_dump_2_var($TransportDetails->Response) . '</pre>';
} else {
    echo '<pre>' . Amazon\Amazon::var_dump_2_var($TransportDetails->Errors) . '</pre>';
}
