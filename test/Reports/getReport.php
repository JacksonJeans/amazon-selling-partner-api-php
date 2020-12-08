<?php

require_once('amazon/autoload.php');
use XXXXX\Amazon;

# Namespace for all classes of the resource to be called
use XXXXX\Amazon\Reports;

$AmazonReports = new Reports\Service($SignatureV4);

$Report = $AmazonReports->getReport('2244760018567');

if (!is_null($Report->Response)) {
    echo '<pre>' . Amazon\Amazon::var_dump_2_var($Report->Response) . '</pre>';
} else {
    echo '<pre>' . Amazon\Amazon::var_dump_2_var($Report->Errors) . '</pre>';
}