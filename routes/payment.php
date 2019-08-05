<?php

use Stripe\Customer;

session_start();

require_once('../vendor/autoload.php');

\Stripe\Stripe::setApiKey('sk_test_kM45oaKwNSFt3DxZ88Snwar0');

if (count(\Stripe\Customer::all(['email' => $_POST['email']])->data) > 0) {
    /** @var Customer $customer */
    $customer = \Stripe\Customer::all(['email' => $_POST['email']])->data[0];

    $_SESSION['customer'] = $customer->id;

    header('Location: http://localhost:8080/routes/last_movements.php');
    exit;
}

// Create a Customer:
$customer = \Stripe\Customer::create([
    'source' => $_POST['stripeToken'],
    'email' => $_POST['email'],
]);

$_SESSION['customer'] = $customer->id;

header('Location: http://localhost:8080/routes/last_movements.php');
exit;

// YOUR CODE: Save the customer ID and other info in a database for later.

// When it's time to charge the customer again, retrieve the customer ID.
/*$charge = \Stripe\Charge::create([
    'amount' => 1500, // $15.00 this time
    'currency' => 'usd',
    'customer' => $customer_id, // Previously stored, then retrieved
]);*/