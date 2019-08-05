<?php

session_start();

require_once('../vendor/autoload.php');

\Stripe\Stripe::setApiKey('sk_test_kM45oaKwNSFt3DxZ88Snwar0');

$charge = \Stripe\Charge::create([
    'amount' => $_GET['amount'],
    'currency' => $_GET['currency'],
    'customer' => $_SESSION['customer'],
    'receipt_email' => 'manuel.garcia@sngular.com',
]);

echo json_encode($charge);