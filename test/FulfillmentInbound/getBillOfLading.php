<?php
require_once('amazon/autoload.php');

use XXXXX\Amazon;

# Namespace for all classes of the resource to be called
use XXXXX\Amazon\FulfillmentInbound;

$Service = new FulfillmentInbound\Service($SignatureV4);

$BillOfLanding = $Service->getBillOfLading('FBA15DDQDL4V');

if (!is_null($BillOfLanding->Response)) {
    echo '<pre>' . Amazon\Amazon::var_dump_2_var($BillOfLanding->Response) . '</pre>';
} else {
    echo '<pre>' . Amazon\Amazon::var_dump_2_var($BillOfLanding->Errors) . '</pre>';
}