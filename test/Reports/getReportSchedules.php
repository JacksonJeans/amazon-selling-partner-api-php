<?php

require_once('amazon/autoload.php');
use XXXXX\Amazon;

# Namespace for all classes of the resource to be called
use XXXXX\Amazon\Reports;

$AmazonReports = new Reports\Service($SignatureV4);

$Schedules = $AmazonReports->getReportSchedules('GET_MERCHANT_LISTINGS_ALL_DATA');

if (!is_null($Schedules->Response)) {
    echo '<pre>' . Amazon\Amazon::var_dump_2_var($Schedules->Response) . '</pre>';
} else {
    echo '<pre>' . Amazon\Amazon::var_dump_2_var($Schedules->Errors) . '</pre>';
}