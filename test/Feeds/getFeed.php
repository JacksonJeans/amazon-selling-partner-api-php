<?php
require_once('amazon/autoload.php');
use XXXXX\Amazon;

# Namespace for all classes of the resource to be called
use XXXXX\Amazon\Feeds;

$AmazonFeeds = new Feeds\Service($SignatureV4);
$Feed = $AmazonFeeds->getFeed('2252543018572');

if (!is_null($Feed->Response)) {
    echo '<pre>' . Amazon\Amazon::var_dump_2_var($Feed->Response) . '</pre>';
} else {
    foreach ($Feeds->Errors as $Error) {
        echo '<pre>' . Amazon\Amazon::var_dump_2_var($Error) . '</pre>';
    }
}