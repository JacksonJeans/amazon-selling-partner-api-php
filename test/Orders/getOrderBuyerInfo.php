<?php

require_once('amazon/autoload.php');
use XXXXX\Amazon;

# Namespace for all classes of the resource to be called
use XXXXX\Amazon\Orders;

$AmazonOrders = new Orders\Service($SignatureV4);


$OrderBuyerInfo = $AmazonOrders->getOrderBuyerInfo('408-9578449-0649117');
echo '<pre>' . Amazon\Amazon::var_dump_2_var($OrderBuyerInfo->Response) . '</pre>';
