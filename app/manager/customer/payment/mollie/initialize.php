<?php

include_once __DIR__ . '/../../../../controller/config.php';

$mollie = new \Mollie\Api\MollieApiClient();
if (!empty($mollie_api_key)) {
    $mollie->setApiKey($mollie_api_key);
}
