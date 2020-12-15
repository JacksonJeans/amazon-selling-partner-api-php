<?php
require_once('amazon/autoload.php');
use XXXXX\Amazon;

# Namespace for all classes of the resource to be called
use XXXXX\Amazon\Shippings;

$AmazonShippings = new Shippings\Service($SignatureV4);

$TrackingInformation = $AmazonShippings->getTrackingInformation('AA0046191400');

if (!is_null($TrackingInformation->Response)) {
    echo '<pre>' . Amazon\Amazon::var_dump_2_var($TrackingInformation->Response) . '</pre>';
} else {
    echo '<pre>' . Amazon\Amazon::var_dump_2_var($TrackingInformation->Errors) . '</pre>';
}
