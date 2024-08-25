<?php
require ('vendor/autoload.php');
$secret_key = "sk_test_51Pd3KvJ2";
\Stripe\Stripe::setApiKey($secret_key);

try {
    $session_id = $_GET['session_id'];

    // retrive payment id from session id of stripe
    $checkout_session = \Stripe\Checkout\Session::retrieve($session_id);
    $payment_intent_id = $checkout_session->payment_intent;

    // retriving the payment status
    $stripe = new \Stripe\StripeClient($secret_key);
    $stripe = $stripe->paymentIntents->retrieve($payment_intent_id, []);
    $status = $stripe['status'];

    // retrive customer id
    $customer_id = $checkout_session['customer'];

    // print_r($checkout_session);
    // echo json_encode($stripe, true);
    // echo $stripe['amount_received']." ".$stripe['currency']." ".$stripe['payment_method_types'][0];
    $currency = $stripe['currency'];
    $amount = $stripe['amount_received'];
    $payment_mode = $stripe['payment_method_types'][0];

} catch (\Stripe\Exception\ApiErrorException $e) {
    // Handle error
    echo "Error: " . $e->getMessage();
    die("error occured!");
}

try {
    // Connect to MySQL database
    $conn = new mysqli('localhost', 'admin', 'Web@2050', 'db20');
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }


    $sql = "UPDATE payments SET payment_intent_id='$payment_intent_id',status='$status',currency='$currency',amount='$amount',payment_mode='$payment_mode' WHERE customer='$customer_id' AND session_id='$session_id'";
    if ($conn->query($sql) === TRUE) {
        echo "<script> console.warn('Record updated successfully') </script>";
    } else {
        echo "Error inserting payment details: " . $conn->error;
    }

    $conn->close();
} catch (Exception $e) {
    echo $e->getMessage();
}

?>

<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>success</title>
</head>

<body>
    <h2 style="color: green;">success</h2>
</body>

</html>