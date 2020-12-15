<?php
require_once('amazon/autoload.php');

use XXXXX\Amazon;

# Namespace for all classes of the resource to be called
use XXXXX\Amazon\FulfillmentInbound;

$Service = new FulfillmentInbound\Service($SignatureV4);

$Labels = $Service->getLabels(
    'FBA15DDQDL4V',
    FulfillmentInbound\PageType::PackageLabel_Thermal_No_Carrier_Rotation,
    FulfillmentInbound\LabelType::UNIQUE
);

if (!is_null($Labels->Response)) {
    echo '<pre>' . Amazon\Amazon::var_dump_2_var($Labels->Response) . '</pre>';
} else {
    echo '<pre>' . Amazon\Amazon::var_dump_2_var($Labels->Errors) . '</pre>';
}