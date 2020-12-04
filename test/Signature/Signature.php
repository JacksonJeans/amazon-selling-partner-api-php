<?php

require_once('amazon/autoload.php');

# Standard namespace for all functions of Amazon that are not only related to a resource.
use XXXXX\Amazon;

# Namespace for all classes of the resource to be called
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

# RESOURCE is a placeholder for the endpoints to be called
# Orders, Feeds, Reports, Finances, Shippings, Uploads...
$Service = new RESOURCE\Service($SignatureV4);

#Then you create a request object or an array with the parameters you need for the query.
#
#Request objects with all possible parameters and validation is included in every API.
$ResultFromOperation = $Service->Operation($arg);
