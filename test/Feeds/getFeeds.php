<?php
require_once('amazon/autoload.php');
use XXXXX\Amazon;

# Namespace for all classes of the resource to be called
use XXXXX\Amazon\Feeds;

$AmazonFeeds = new Feeds\Service($SignatureV4);

$FeedsRequest = new Feeds\FeedsRequest();
$FeedsRequest->feedTypes = 'POST_INVENTORY_AVAILABILITY_DATA';
$FeedsRequest->marketplaceIds = 'A1PA6795UKMFR9';
$FeedsRequest->pageSize = 100;
$FeedsRequest->processingStatuses = Amazon\ProcessingStatus::DONE;
$FeedsRequest->createdSince = Amazon\Amazon::dateWithISO8601AndTime(strtotime('today - 3 days'));
$FeedsRequest->createdUntil = Amazon\Amazon::dateWithISO8601AndTime(strtotime('today'));

# call setter for FeedsRequest
$AmazonFeeds->setFeedsRequest(
    $FeedsRequest
);

while ($AmazonFeeds->NextToken !== FALSE) {

    # Anforderung
    $Feeds = $AmazonFeeds->getFeeds($AmazonFeeds->NextToken);

    if (!is_null($Feeds->Response)) {
        foreach ($Feeds->Response as $Feed) {

            echo '<pre>' . Amazon\Amazon::var_dump_2_var($Feed) . '</pre>';
        }

        # Fehler
    } else {
        foreach ($Feeds->Errors as $Error) {

            echo '<pre>' . Amazon\Amazon::var_dump_2_var($Error) . '</pre>';
        }
    }
}