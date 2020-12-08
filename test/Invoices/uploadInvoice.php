<?php


# Konfiguration Signature V2 (MWS)
$AmazonAuthV2Config = new Amazon\AmazonSignatureV2Configuration();
$AmazonAuthV2Config->Merchant = 'MERCHANTID';
$AmazonAuthV2Config->AWSAccessKeyId = 'AWSAccessKeyId';
$AmazonAuthV2Config->AWSSecretAccessKey = 'AWSSecretAccessKey';

# Auth
$AmazonAuthV2 = new Amazon\AmazonSignatureV2($AmazonAuthV2Config);

# Handling
$AmazonInvoices = new Invoices\Service($AmazonAuthV2);

# create Invoice
$Invoice = new Invoices\Invoice();
$Invoice->DocumentType = Invoices\Invoice::DOCUMENT_TYPE_INVOICE;    # || 'Invoice'; || DOCUMENT_TYPE_CREDIT_NOTE || 'CreditNote';                                      
$Invoice->ContentType = 'application/pdf';                           # [optional] default: application/pdf 
$Invoice->TotalAmount = 22.92;
$Invoice->VATAmount = 4.35;
$Invoice->OrderId = '028-17347-12384';
$Invoice->MarketplaceId = 'A3GFEf3FEASf';
$Invoice->InvoiceNumber = 'R_231465354';
$Invoice->FeedContent = file_get_contents('test/dummy.pdf');

$response = $AmazonInvoices->uploadInvoice($Invoice);

# u get xml!
$response = simplexml_load_string($response);
