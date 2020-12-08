<?php

# Recommendation Url.: 
# https://github.com/amzn/selling-partner-api-docs/issues/133#issuecomment-732879905

require_once('amazon/autoload.php');
use XXXXX\Amazon;

# Namespace for all classes of the resource to be called
use XXXXX\Amazon\Feeds;

$AmazonFeeds = new Feeds\Service($SignatureV4);

# [1.1] Create a FeedDocument
# Get a feedDocumentId value that you can pass with a subsequent call to the createFeed operation.

$contentType = 'application/xml';
$marketplaceId = 'A1PA6795UKMFR9';

$createFeedDocumentSpecifications = new Feeds\CreateFeedDocumentSpecification();
$createFeedDocumentSpecifications->contentType = $contentType;
$createFeedDocumentSpecifications = $AmazonFeeds->createFeedDocument($createFeedDocumentSpecifications);

# [1.2] Store the response
if (!is_null($createFeedDocumentSpecifications->Response)) {
    $createFeedDocumentSpecifications = $createFeedDocumentSpecifications->Response;

    $file = file_get_contents('test/bestand.xml');
    $fileResourceType = gettype($file);

    if ($fileResourceType == 'resource') {
        $file = stream_get_contents($file);
    } elseif ($fileResourceType == 'string') {
        $file = $file;
    }

    $uploadedResponse = $AmazonFeeds->encryptAndUploadDocument(
        $file,
        $contentType,
        $createFeedDocumentSpecifications->url,
        $createFeedDocumentSpecifications->encryptionDetails->key,
        $createFeedDocumentSpecifications->encryptionDetails->initializationVector
    );

    #  ===
    if ($uploadedResponse === TRUE) {
        # [3.1] Create a feed
        $createFeedSpecifications = new Feeds\CreateFeedSpecification();

        $createFeedSpecifications->feedType = 'POST_INVENTORY_AVAILABILITY_DATA';
        $createFeedSpecifications->marketplaceIds =  array($marketplaceId);
        // feedDocumentId [1.1]
        $createFeedSpecifications->inputFeedDocumentId = $createFeedDocumentSpecifications->feedDocumentId;

        // additional info here as array
        #[JT] feedOptions can be array, or they can be written. you have to test
        $createdFeed = $AmazonFeeds->createFeed($createFeedSpecifications);

        if (!is_null($createdFeed->Response)) {
            $createdFeed = $createdFeed->Response;

            # Confirm the processing by fetching the feed.
            # periodically check if the feed is ready
            # For testing I recommend a sleep of 3 seconds

            $feed = $AmazonFeeds->getFeed($createdFeed->feedId);
            if (!is_null($feed->Response)) {
                $feed = $feed->Response;

                $processingStatuses = $feed->processingStatus;
                $tests = 0;
                # First attempt
                if ($processingStatuses == Amazon\ProcessingStatus::FATAL) {
                    echo '<pre>' . Amazon\Amazon::var_dump_2_var($createFeedDocumentSpecifications) . '</pre>';
                    die('Leider alles kaputt durch einen FATALEN Fehler. createdFeedId:' . $createdFeed->feedId);
                }

                while ($processingStatuses !== Amazon\ProcessingStatus::DONE) {
                    # Wait because of Throttle
                    sleep(3);

                    # Second try
                    # Repeat operation until 
                    $feed = $AmazonFeeds->getFeed($createdFeed->feedId);
                    if (!is_null($feed->Response)) {
                        $feed = $feed->Response;

                        # processingStatuses DONE  
                        $processingStatuses = $feed->processingStatus;

                        if ($processingStatuses == Amazon\ProcessingStatus::FATAL) {
                            echo '<pre>' . Amazon\Amazon::var_dump_2_var($feed) . '</pre>';

                            die('Leider alles kaputt durch einen FATALEN Fehler. createdFeedId:' . $createdFeed->feedId);

                        } elseif ($processingStatuses == Amazon\ProcessingStatus::DONE) {
                            # keep the report for processing, which records were successful, which were not
                            $feedDocument = $AmazonFeeds->getFeedDocument($feed->resultFeedDocumentId);

                            # Download and decompress, deycrpyte the file
                            if (!is_null($feedDocument->Response)) {

                                $feedDocument = $feedDocument->Response;
                                $resultDocument = $AmazonFeeds->decompressFile(file_get_contents($feedDocument->url), $feedDocument->encryptionDetails->key, $feedDocument->encryptionDetails->initializationVector);

                                echo '<pre>' . Amazon\Amazon::var_dump_2_var($resultDocument) . '</pre>';
                                #Error
                            } else {
                                throw new Exception('Verarbeitungsdokument konnte aus folgenden Grund nicht geholt werden: <pre>' . Amazon\Amazon::var_dump_2_var($feedDocument->Errors) . '</pre>');
                            }
                        }
                    } else {
                        // Up to this point the feed could be created. Now Amazon has to process the feed. If an error occurs here, 
                        // then because it is due to the domain logic of this class.
                        $feedId = $createdFeed->feedId;
                        throw new Exception('Die Bestätigung des Feeds ist fehlgeschlagen aus folgenden Grund: <pre>' . Amazon\Amazon::var_dump_2_var($feed->Errors) . '</pre>');
                    }

                    echo "\n Versuch NR: $tests, da vorheriger Status: $processingStatuses ist \n";

                    # If 20 attempts x 3 seconds (60 seconds) did not work, die.
                    if ($tests == 30) {
                        die('Leider alles kaputt. createdFeedId:' . $createdFeed->feedId);
                    }
                    $tests++;
                }
                #Error
            } else {
                // Up to this point the feed could be created. Now Amazon has to process the feed. If an error occurs here, 
                // then because it is due to the domain logic of this class.
                $feedId = $createdFeed->feedId;
                throw new Exception('Die Bestätigung des Feeds ist fehlgeschlagen aus folgenden Grund: <pre>' . Amazon\Amazon::var_dump_2_var($feed->Errors) . '</pre>');
            }
            #Error
        } else {
            // Suggestion - Save the reserved inputFeedDocumentId away 
            // because it is valid for 2 days and Amazon waits 2 days for a document to be uploaded
            // Up to this point the XML document could be uploaded to the feed.
            $inputFeedDocumentId = $createFeedDocumentSpecifications->feedDocumentId;
            throw new Exception('Die Erstellung des Feeds ist fehlgeschlagen aus folgenden Grund: <pre>' . Amazon\Amazon::var_dump_2_var($createdFeed->Errors) . '</pre>');
        }
        #Error
    } else {
        // Suggestion - Save the reserved inputFeedDocumentId away 
        // because it is valid for 2 days and Amazon waits 2 days for a document to be uploaded
        // Save XML file away to check it. Why could the file not be uploaded?
        $inputFeedDocumentId = $createFeedDocumentSpecifications->feedDocumentId;
        $url = $createFeedDocumentSpecifications->url;
        $initializationVector = $createFeedDocumentSpecifications->encryptionDetails->initializationVector;
        $key = $createFeedDocumentSpecifications->encryptionDetails->key;
        throw new Exception('Das Dokument konnte zum Feed aus [2.1] nicht hochgeladen werden aus folgendem Grund: <pre>' . Amazon\Amazon::var_dump_2_var($uploadedResponse) . '</pre>');
    }
    #Error
} else {
    throw new Exception('feedDocumentId-Wert aus [1.1] konnte nicht geholt werden aus folgenden Grund: <pre>' . Amazon\Amazon::var_dump_2_var($createFeedDocumentSpecifications->Errors) . '</pre>');
}