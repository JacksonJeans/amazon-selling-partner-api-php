<?php
require_once('amazon/autoload.php');

use XXXXX\Amazon;

# Namespace for all classes of the resource to be called
use XXXXX\Amazon\FulfillmentInbound;

$Service = new FulfillmentInbound\Service($SignatureV4);
$InboundGuidance = $Service->getInboundGuidance('A1PA6795UKMFR9', array('ASIN1', 'ASIN2'));
if (!is_null($InboundGuidance->Response)) {
    echo '<pre>' . Amazon\Amazon::var_dump_2_var($InboundGuidance->Response) . '</pre>';
} else {
    echo '<pre>' . Amazon\Amazon::var_dump_2_var($InboundGuidance->Errors) . '</pre>';
}