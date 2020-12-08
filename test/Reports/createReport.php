<?php

require_once('amazon/autoload.php');
use XXXXX\Amazon;

# Namespace for all classes of the resource to be called
use XXXXX\Amazon\Reports;

$AmazonReports = new Reports\Service($SignatureV4);

$CreateReportSpecification = new Reports\CreateReportSpecification();
$CreateReportSpecification->reportType = 'GET_MERCHANT_LISTINGS_ALL_DATA';
$CreateReportSpecification->dataStartTime = Amazon\Amazon::dateWithISO8601AndTime(strtotime('today'));
$CreateReportSpecification->dataEndTime = Amazon\Amazon::dateWithISO8601AndTime(strtotime('tomorrow'));
$CreateReportSpecification->marketplaceIds = 'A1PA6795UKMFR9';

$CreatedReport = $AmazonReports->createReport($CreateReportSpecification);

if (!is_null($CreatedReport->Response)) {
    echo '<pre>' . Amazon\Amazon::var_dump_2_var($CreatedReport->Response) . '</pre>';
} else {
    echo '<pre>' . Amazon\Amazon::var_dump_2_var($CreatedReport->Errors) . '</pre>';
}