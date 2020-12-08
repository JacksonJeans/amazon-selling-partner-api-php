<?php

require_once('amazon/autoload.php');
use XXXXX\Amazon;

# Namespace for all classes of the resource to be called
use XXXXX\Amazon\Reports;

$AmazonReports = new Reports\Service($SignatureV4);

$ReportDocument = $AmazonReports->getReportDocument('amzn1.tortuga.3.22617e92-ea1d-4dc0-8486-018445478chebbf5.T30MAZTOVEV8PC');
if (!is_null($ReportDocument->Response)) {
    echo '<pre>' . Amazon\Amazon::var_dump_2_var($ReportDocument->Response) . '</pre>';
} else {
    echo '<pre>' . Amazon\Amazon::var_dump_2_var($ReportDocument->Errors) . '</pre>';
}