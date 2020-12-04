# amazon-selling-partner-api-php
This repository contains all test cases for the PHP-SDK written by Julian Tietz, the company GIDUTEX. 

Currently the implementation contains feeds/reports/orders/finances API. Within the next weeks all remaining endpoints will be added. If you are interested in the SDK, feel free to contact me.

[TOC]

## Overview

Everything begins with the signature... 🤓

Check out the tests and feel free to see how easy the Selling Partner API can be. 

### [Testcase Signature](/test/Signature/) 

```php
require_once('amazon/autoload.php');

# Standard namespace for all functions of Amazon that are not only related to a resource.
use XXXXX\Amazon;

# Namespace for all classes of the resource to be called.
use XXXXX\Amazon\RESOURCE;

$SignatureV4Credentials = new Amazon\AmazonSignatureV4Configuration();

$SignatureV4Credentials->AmazonRefreshToken = 'AmazonRefreshToken';
$SignatureV4Credentials->AWSHost = 'AWSHost';
$SignatureV4Credentials->AWSAccessKeyId = 'AWSAccessKeyId';
$SignatureV4Credentials->AWSAccessSecretKey = 'AWSAccessSecretKey';
$SignatureV4Credentials->AWSSellingRegion = 'AWSSellingRegion';
$SignatureV4Credentials->AWSSignatureVersion = 'AWSSignatureVersion';
$SignatureV4Credentials->AWSSandboxEndpoint = 'AWSSandboxEndpoint';
$SignatureV4Credentials->AmazonClientID = 'AmazonClientID';
$SignatureV4Credentials->AmazonClientSecretKey = 'AmazonClientSecretKey';
$SignatureV4Credentials->GrantType = 'GrantType';

$SignatureV4 = new Amazon\AmazonSignatureV4($SignatureV4Credentials);

# RESOURCE is a placeholder for the endpoints to be called.
# Orders, Feeds, Reports, Finances, Shippings, Uploads...
$Service = new RESOURCE\Service($SignatureV4);

#Then you create a request object or an array with the parameters you need for the query.
#
#Request objects with all possible parameters and validation is included in every API.
$ResultFromOperation = $Service->Operation($arg);
```

### [Testcase Orders](/test/Orders/)

The OrdersAPI service knows 3 different NextTokens that differ for the corresponding GET-operation:

NextToken for getOrders(), ItemsNextToken for getOrderItems(), ItemsBuyerInfoNextToken for getOrderItemsBuyerInfo().

### [Testcase Feeds](/test/Feeds/)

This service comes with two modified operations:

- With encryptAndUploadDocument() you can encrypt files and upload them as feed.
- With decryptAndDownloadDocument() you can download and decrypt files.

The FeedsAPI service knows only 1 NextToken for the getFeeds()-operation.

The Feeds API no longer supports "UPLOAD_VAT_INVOICE" as opposed to MWS. 

An own implementation I have implemented in the "Testcase Invoices". 

### [Testcase Reports](/test/Reports/)

This service comes with one modified operation:

- With decryptAndDownloadDocument() you can download and decrypt files.

The ReportsAPI service knows only 1 NextToken for the getReports()-operation.

### [Testcase Finances](/test/Finances/)

The FinancesAPI service knows 4 different NextTokens that differ for the corresponding GET-operation:

FinancialEventGroupsNextToken for listFinancialEventGroups(), FinancialEventsByGroupIdNextToken for FinancialEventsByGroupId(), FinancialEventsByOrderIdNextToken for listFinancialEventsByOrderId() and FinancialEventsNextToken for listFinancialEvents().

### [Testcase Inovices](/test/Invoices)

This endpoint is not a Selling Partner API, but still part of the Amazon MWS. Here I implemented an own implementation which allows to switch between the two different signature versions V2 (MWS) and V4 (AWS) if the Invoice-Uploader-API is upgraded to the SP-API.

This endpoint uploads invoices or credit notes as feed.
The [MWS documentation](https://m.media-amazon.com/images/G/03/B2B/invoice-uploader-developer-documentation.pdf) was completely implemented.

WARNING: "Invoices" only works for users who already have an activated MWS account.

### [Testcase Shippings](/test/Shippings/)

- On the way to completion - test phase

### [Testcase Uploads](/test/Uploads/)



## Tasks

- [x] Orders
- [x] Reports
- [x] Finances
- [x] Feeds
- [x] Uploads
- [x] Invoices
- [ ] Shippings - almost finished
- [ ] Catalogs
- [ ] FBAInboundEligibility
- [ ] FBAInventory
- [ ] FBASmallAndLight
- [ ] FulFillmentInbound
- [ ] FulFillmentOutbound
- [ ] MerchantFulFillment
- [ ] Messaging
- [ ] Notifications
- [ ] ProductFeeds
- [ ] ProductPricing
- [ ] Sales
- [ ] Sellers
- [ ] Services
- [ ] Solicitations

## Implementation

The HttpClient was realized by means of Guzzle. All services work autonomously from each other, only the signature object must be passed to the corresponding service.

You need at least PHP 5.3 and should have the addons sodium and openssl installed for decryption and encryption