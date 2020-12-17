# amazon-sp-api-php
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

### [Testcase FulFillmentInbound](/test/FulfillmentInbound/)

The FulFillmentInbound API service knows 2 different NextTokens that differ for the corresponding GET-operation:

ShipmentsNextToken for getShipments() and ShipmentItemNextToken for getShipmentItems().

### [Testcase Shippings](/test/Shippings/)

It's relatively easy to use. There is nothing more to say about this. Try it out if u have a valid account.

### [Testcase Uploads](/test/Uploads/)


## Tasks

- [x] Orders - work perfect
- [x] Reports - work perfect
- [x] Finances - work perfect
- [x] Feeds - work perfect
- [x] Uploads - work perfect
- [x] Invoices - work perfect
- [x] Shippings - work perfect
- [ ] Catalogs
- [ ] FBAInboundEligibility
- [ ] FBAInventory
- [ ] FBASmallAndLight
- [x] FulFillmentInbound -  almost finished
- [ ] FulFillmentOutbound - in progress
- [ ] MerchantFulFillment - in progress
- [ ] Messaging
- [ ] Notifications
- [ ] ProductFeeds
- [ ] ProductPricing
- [ ] Sales
- [x] Sellers - work perfect
- [ ] Services
- [ ] Solicitations

## Implementation

The HttpClient was realized by means of Guzzle. All services work autonomously from each other, only the signature object must be passed to the corresponding service.

You need at least PHP 5.3 and should have the addons sodium and openssl installed for decryption and encryption

## Debug

### AWS Signature V4 Debug and Sandbox

You can also use a boolean to control whether a debug log of the signature process is created. This is then executed and can then be viewed to exclude or detect possible errors.

```php
$SignatureV4->AWSAuthDebug = true;
```

**Output:**

Task 1.2 - Add the canonical URI parameter followed by a newline character.

 DOCU: 

```
/documents%2520and%2520settings/
```

Our:

```
/shipping/v1/rates
```

Task 1.3 - Add the canonical query string followed by a newline character.

DOCU:  

```
Action=ListUsers&Version=2010-05-08&X-Amz-Algorithm=AWS4-HMAC-SHA256&X-Amz-Credential=AKIDEXAMPLE%2F20150830%2Fus-east-1%2Fiam%2Faws4_request&X-Amz-Date=20150830T123600Z&X-Amz-SignedHeaders=content-type%3Bhost%3Bx-amz-date
```

Our:

```

```

Task: 1.4 - Add the canonical headers followed by a newline character. The canonical headers consist of a list of all HTTP headers that you include with the signed request.

DOCU: 

```
content-type:application/x-www-form-urlencoded; charset=utf-8
 host:iam.amazonaws.com
 my-header1:a b c
 my-header2:'a b c'
 x-amz-date:20150830T123600Z
```

Our:

```
content-type:application/json;charset=UTF-8
host:sellingpartnerapi-eu.amazon.com
user-agent:GIDUTEX (Language=PHP/7.1; Platform=Windows/10)
x-amz-date:20201208T134301Z
```

 Aufgabe 1.5 - Fügen Sie die signierten Header hinzu, gefolgt von einem Zeilenumbruchzeichen.

DOCU: 

```
content-type;host;x-amz-date
```

Our:

```
content-type;host;user-agent;x-amz-date
```

Task 1.6 - Use a hash (digest) function such as SHA256 to create a hash value from the payload in the text of the HTTP or HTTPS request. 

DOCU with empty string in the payload: 

```
e3b0c44298fc1c149afbf4c8996fb92427ae41e4649b934ca495991b7852b855
```

Our:

```
bd09435c4ae88e3531f04c315b7b6627c27b2a84e4da2afcce29a733bee93527
```

Task 1.7 - To create the finished canonical requirement, combine all components from each step into a single string.

DOCU:  

```
GET 
/Action=ListUsers&Version=2010-05-08
content-type:application/x-www-form-urlencoded; charset=utf-8
host:iam.amazonaws.com
x-amz-date:20150830T123600Z

content-type;host;x-amz-date
e3b0c44298fc1c149afbf4c8996fb92427ae41e4649b934ca495991b7852b855
```

Our:

```
POST
/shipping/v1/rates

content-type:application/json;charset=UTF-8
host:sellingpartnerapi-eu.amazon.com
user-agent:xxxx (Language=PHP/7.1; Platform=Windows/10)
x-amz-date:20201208T134301Z

content-type;host;user-agent;x-amz-date
bd09435c4ae88e3531f04c315b7b6627c27b2a84e4da2afcce29a733bee93527
```

 Task 1.8 - Create a digest (hash) of the canonical request using the same algorithm you used to hash the payload.

DOCU:  

```
f536975d06c0309214f805bb90ccff089219ecd68b2577efef23edd43b7e1a59
```

Our:

```
3001cc6d6c78d3fe0ff64ac0d3bfec0dfc04a8464bb130c6a94786be50a6f165
```



 Task 2 - Create a string to be signed.

DOCU:  

```
AWS4-HMAC-SHA256
20150830T123600Z
20150830/us-east-1/iam/aws4_request
f536975d06c0309214f805bb90ccff089219ecd68b2577efef23edd43b7e1a59
```

Our: 

```
AWS4-HMAC-SHA256
20201208T134301Z
20201208/eu-west-1/execute-api/aws4_request
3001cc6d6c78d3fe0ff64ac0d3bfec0dfc04a8464bb130c6a94786be50a6f165
```

 Task 3.1 - Calculate the signature. Derive your signature key.

DOCU: 

```
c4afb1cc5771d871763a393e44b703571b55cc28424d1a5e86da6ed3c154a4b9
```

Our:

```
dec5bd48a268b46fd53661ded0361b544ccf01491e2845fcc72bd9b9b6e78877
```

 Task 3.2 - Calculate the signature. Calculate the signature. Use the signature key you have derived.

DOCU: 

```
5d672d79c15b13162d9279b0855cfba6789a8edb4c82c400e06b5924a6f2b5d7
```

Our:

```
0ef375ceae59c2fe4e3bb7cb96f29059b4222226b0b77145482480d7dbaa576f
```



To use the endpoint for the Amazon selling partner sandbox, the following line is sufficient:

```php
$SignatureV4->AWSSandbox = true;
```



### Request & Response

All Service Objects have the "clientRequest" attributes with which the client can be viewed more closely. This way you can get the exact HTTP body or the complete request you sent to Amazon last time.

For example:
```php
# RESOURCE is a placeholder for the endpoints to be called.
# Orders, Feeds, Reports, Finances, Shippings, Uploads...
$Service = new RESOURCE\Service($SignatureV4);

# Do calls
# .....
# End calls

$client = $Service->clientRequest;

# request
$rawRequestHTTPBody = $client->getHTTPBody();

# response
$rawResponse = $client->response;
```
