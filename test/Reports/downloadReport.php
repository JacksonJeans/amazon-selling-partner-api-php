<?php

require_once('amazon/autoload.php');
use XXXXX\Amazon;

# Namespace for all classes of the resource to be called
use XXXXX\Amazon\Reports;

$AmazonReports = new Reports\Service($SignatureV4);

$Report = $AmazonReports->getReport('23109080587598');

if (!is_null($Report->Response)) {
    echo '<pre>' . Amazon\Amazon::var_dump_2_var($Report->Response) . '</pre>';
    $Report = $Report->Response;
    $reportDocumentId = $Report->reportDocumentId;
    # Now get the document:
    $ReportDocument = $AmazonReports->getReportDocument($reportDocumentId);

    if (!is_null($ReportDocument->Response)) {
        echo '<pre>' . Amazon\Amazon::var_dump_2_var($ReportDocument->Response) . '</pre>';

        $ReportDocument = $ReportDocument->Response;

        $reportDocument = $AmazonReports->downloadFile(
            file_get_contents($ReportDocument->url),
            $ReportDocument->encryptionDetails->key,
            $ReportDocument->encryptionDetails->initializationVector
        );

        # Here is the content of the decrypted file:
        echo '<pre>' . Amazon\Amazon::var_dump_2_var($reportDocument) . '</pre>';
    } else {
        echo '<pre>' . Amazon\Amazon::var_dump_2_var($ReportDocument->Errors) . '</pre>';
    }
} else {
    echo '<pre>' . Amazon\Amazon::var_dump_2_var($Report->Errors) . '</pre>';
}
