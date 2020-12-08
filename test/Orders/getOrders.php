<?php

require_once('amazon/autoload.php');
use XXXXX\Amazon;

# Namespace for all classes of the resource to be called
use XXXXX\Amazon\Orders;

$AmazonOrders = new Orders\Service($SignatureV4);

#  The index for the filter elements is not case sensitive! Upper and lower case is not case sensitive.
$filter = array(
    'marketplaceids' => 'A1805IZSGTT6HS', #|| array('order-id1', 'order-id2', ... 'order-id50')
    'amazonorderids' => '408-9423449-0649117'
);

# OrderRequest provides the filter properties for the getOrders() method.
# Parameters can be set via Setter [OrderRequest->filterVar = xxxxx;] or via the constructor as an associative array.
# filterVar here represents the corresponding attribute that will be assigned a value.
# with the constructor is meant:
#
# $OrdersRequest = new OrdersRequest(array('createdafter' => xxxx, 'marketplaceids' => xxxx))
#
# The attributes are not case sensitive
$OrderRequest = new Orders\OrdersRequest();

# Beispiel für NL Marketplace
#$OrderRequest->MarketplaceIds = 'A1805IZSGTT6HS'; # array(maxcount 50 elements)
#$OrderRequest->AmazonOrderIds = '408-9433449-0649117';

# Example of exception when setOrderRequest() is filled with invalid attributes (SellerOrderId and LastUpdateAfter do not go together)
#$OrderRequest->SellerOrderId = 'Test';
#$OrderRequest->LastUpdatedAfter = Amazon::dateWithISO8601WithoutTimeZone(strtotime('today - 1 day'));

$OrderRequest->CreatedAfter = Amazon\Amazon::dateWithISO8601WithoutTimeZone(strtotime('today'));

$OrderRequest->MarketplaceIds = array('A1PA6795UKMFR9', 'A1805IZSGTT6HS');
$OrderRequest->MaxResultsPerPage = 100;

$AmazonOrders->setOrdersRequest($OrderRequest);

$i = 1;
while ($AmazonOrders->NextToken !== FALSE) {

    $Orders = $AmazonOrders->getOrders($AmazonOrders->NextToken);
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

    if (!is_null($Orders->Response)) {

        echo '<hr><h2>Orders Liste Seite ' . $i . ': </h2><hr>';

        /**
         * The response object of getOrders offers the following attributes:
         * Every OrdersList has these.
         * 
         *  $NextToken
         *  $LastUpdatedBefore
         *  $CreatedBefore
         *  $Orders 
         */
        echo  Amazon\Amazon::var_dump_2_var(
            $Orders->Response->NextToken,
            $Orders->Response->LastUpdatedBefore,
            $Orders->Response->CreatedBefore
        );


        # The response of getOrders represents OrdersList.
        # Each OrdersList goes through a Foreach,
        # to get to the individual orders
        foreach ($Orders->Response->Orders as $Order) {
            echo '<pre>' . Amazon\Amazon::var_dump_2_var($Order) . '</pre>';
        }

        $i++;

        # If Error
    } else {
        echo '<pre>' . Amazon\Amazon::var_dump_2_var($Orders->Errors) . '</pre>';
    }
}