<?php

require_once('amazon/autoload.php');
use XXXXX\Amazon;

# Namespace for all classes of the resource to be called
use XXXXX\Amazon\Reports;

$AmazonReports = new Reports\Service($SignatureV4);

#[JT] 1st parameter is [String] ,2nd parameter is [String] or [Array]. I recommend [String]. implementation that a list comes back to marketplaces follows.
#$LastReport = $AmazonReports->getLastReport(array('A1PA6795UKMFR9', ... )); max 10 marketplaceIds elements.

# Alle Report Typen: https://github.com/amzn/selling-partner-api-docs/blob/main/references/reports-api/reportType_string_array_values.md

$LastReport = $AmazonReports->getLastReport('GET_FLAT_FILE_OPEN_LISTINGS_DATA', 'A1PA6795UKMFR9');

echo '<pre>' . Amazon\Amazon::var_dump_2_var($LastReport) . '</pre>';
