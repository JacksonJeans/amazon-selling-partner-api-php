<?php

require_once('amazon/autoload.php');
use XXXXX\Amazon;

# Namespace for all classes of the resource to be called
use XXXXX\Amazon\Orders;

$AmazonOrders = new Orders\Service($SignatureV4);

$Order = $AmazonOrders->getOrder('408-9433449-0649117');
# Wenn alles gut
if (!is_null($Order->Response)) {
    echo '<pre>' . Amazon\Amazon::var_dump_2_var($Order->Response) . '</pre>';
}