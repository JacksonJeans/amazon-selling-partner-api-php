<?php

require_once('amazon/autoload.php');
use XXXXX\Amazon;

# Namespace for all classes of the resource to be called
use XXXXX\Amazon\Reports;

$AmazonReports = new Reports\Service($SignatureV4);

$ReportsRequest = new Reports\ReportsRequest();
$ReportsRequest->reportTypes = 'GET_FBA_FULFILLMENT_INVENTORY_HEALTH_DATA';
$ReportsRequest->processingStatuses = Amazon\ProcessingStatus::DONE;
$ReportsRequest->marketplaceIds = 'A1PA6795UKMFR9';
$ReportsRequest->pageSize = 10;
$ReportsRequest->createdSince = Amazon\Amazon::dateWithISO8601AndTime(strtotime('today - 2 days'));
$ReportsRequest->createdUntil = Amazon\Amazon::dateWithISO8601AndTime(strtotime('today'));

$AmazonReports->setReportRequest(
    $ReportsRequest
);

while ($AmazonReports->NextToken !== FALSE) {

    # REquest
    $Reports = $AmazonReports->getReports($AmazonReports->NextToken);

    # When all is well
    # 
    # Implemented possibilities via the Response object from the return of the getReports() method 
    # Each response object provides the attributes:
    #
    # - HTTP_ResponseCode                                               ONLY IF ERROR !== 200
    # - Response || Content || Payload                                  NOT CASE-SENSITIVE!         
    # - Errors || Error                                                 ONLY IF ERROR !== null
    #
    # if($Orders->HTTP_ResponseCode !== 200)
    # if(is_null($Orders->Errors))
    #
    # Alternatively you can also check the AmazonError attributes via the request object (here AmazonReports):
    # if($AmazonReports->AmazonError !== TRUE)
    #
    if (!is_null($Reports->Response)) {
        foreach ($Reports->Response as $Report) {
            // DO SOMETHING LIKE THIS WITH THE REPORTS
            // IF REPORT ID IS ALREADY KNOWN, THE FOLLOWING METHOD CAN BE USED
            #$Report = $AmazonReports->getReport($Report->reportId);
            echo '<pre>' . Amazon\Amazon::var_dump_2_var($Report) . '</pre>';
        }
        # Fehler
    } else {
        foreach ($Reports->Errors as $Error) {
            // DO SOMETHING LIKE THIS WITH THE REPORTS
            // IF REPORT ID IS ALREADY KNOWN, THE FOLLOWING METHOD CAN BE USED
            #$Report = $AmazonReports->getReport($Report->reportId);
            echo '<pre>' . Amazon\Amazon::var_dump_2_var($Error) . '</pre>';
        }
    }
}