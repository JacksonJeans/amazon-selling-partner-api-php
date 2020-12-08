<?php

require_once('amazon/autoload.php');
use XXXXX\Amazon;

# Namespace for all classes of the resource to be called
use XXXXX\Amazon\Orders;

$AmazonOrders = new Orders\Service($SignatureV4);

$AmazonAddress = $AmazonOrders->getOrderAddress('304-5251530-1158732');

echo '<pre>' . Amazon\Amazon::var_dump_2_var($AmazonAddress->Response) . '</pre>';
