<?php
require_once('amazon/autoload.php');
use XXXXX\Amazon;

# Namespace for all classes of the resource to be called
use XXXXX\Amazon\Shippings;
$AmazonShippings = new Shippings\Service($SignatureV4);

$AccountResponse = $AmazonShippings->getAccount();

if (!is_null($AccountResponse->Response)) {
    echo '<pre>' . Amazon\Amazon::var_dump_2_var($AccountResponse->Response) . '</pre>';
} else {
    echo '<pre>' . Amazon\Amazon::var_dump_2_var($AccountResponse->Errors) . '</pre>';
}
