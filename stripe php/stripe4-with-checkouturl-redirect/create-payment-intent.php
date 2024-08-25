<?php
require ('vendor/autoload.php');

$secret_key = "sk_test_51Pd3zt2Mql7KvJ2";

\Stripe\Stripe::setApiKey($secret_key);

header('Content-Type: application/json');

$input = file_get_contents('php://input');
$data = json_decode($input);

$amount = $data->price * 100; // Amount in cents
$product = $data->product;
$customer_name = $data->customer_name;
$email = $data->email;
$token = $data->token;

try {
    $charge = \Stripe\Charge::create([
        'amount' => $amount,
        'currency' => 'usd',
        'description' => $product,
        'source' => $token,
        'receipt_email' => $email,
        'metadata' => [
            'customer_name' => $customer_name
        ]
    ]);

    echo json_encode(['status' => 'success', 'charge' => $charge]);
} catch (Error $e) {
    http_response_code(500);
    echo json_encode(['error' => $e->getMessage()]);
}
?>
