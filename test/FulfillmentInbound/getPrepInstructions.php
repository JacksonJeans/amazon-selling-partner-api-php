<?php
require_once('amazon/autoload.php');

use XXXXX\Amazon;

# Namespace for all classes of the resource to be called
use XXXXX\Amazon\FulfillmentInbound;

$Service = new FulfillmentInbound\Service($SignatureV4);
$PrepInstructions = $Service->getPrepInstructions('DE', array('SKU1','SKU99'), array('ASIN1','ASIN99'));
if (!is_null($PreoderInfo->Response)) {
    echo '<pre>' . Amazon\Amazon::var_dump_2_var($PrepInstructions->Response) . '</pre>';
} else {
    echo '<pre>' . Amazon\Amazon::var_dump_2_var($PrepInstructions->Errors) . '</pre>';
}