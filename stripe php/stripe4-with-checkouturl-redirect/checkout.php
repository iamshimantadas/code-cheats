<?php
require ('vendor/autoload.php');

$secret_key = "sk_test_51Pd3zt2MM6KvJ2";

\Stripe\Stripe::setApiKey($secret_key);


if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $amount = $_POST['price'];
    $product = $_POST['product'];
    $customer = $_POST['customer_name'];
    $email = $_POST['email'];


    // creating the new customer
    $new_customer = new \Stripe\StripeClient($secret_key);
    $new_customer = $new_customer->customers->search([
        'query' => 'email:\'' . $email . '\'',
    ]);
    $customerid = $new_customer['data'][0]['id'];

    // check user existence
    if ($customerid) {

        // Create Stripe Checkout Session
        $checkout_session = \Stripe\Checkout\Session::create([
            'payment_method_types' => ['card'],
            'line_items' => [
                [
                    'price_data' => [
                        'currency' => 'usd',
                        'unit_amount' => $amount * 100, // Amount in cents
                        'product_data' => [
                            'name' => $product, // Product name
                        ],
                    ],
                    'quantity' => 1,
                ]
            ],
            'mode' => 'payment',
            'customer' => $customerid,
            'success_url' => 'http://localhost/practice/stripe4/success.php?session_id={CHECKOUT_SESSION_ID}',
            'cancel_url' => 'http://localhost/practice/stripe4/failed.php',
        ]);

        // Redirect user to Stripe Checkout page
        // header("Location: " . $checkout_session->url);
        $arr = $checkout_session;
        echo json_encode($arr, true);

    } else {


        $client = new \Stripe\StripeClient($secret_key);
        $new_customer = $client->customers->create([
            'name' => $customer,
            'email' => $email,
        ]);
        $customerid = $new_customer->id;

        // Create Stripe Checkout Session
        $checkout_session = \Stripe\Checkout\Session::create([
            'payment_method_types' => ['card'],
            'line_items' => [
                [
                    'price_data' => [
                        'currency' => 'usd',
                        'unit_amount' => $amount * 100, // Amount in cents
                        'product_data' => [
                            'name' => $product, // Product name
                        ],
                    ],
                    'quantity' => 1,
                ]
            ],
            'mode' => 'payment',
            'customer' => $customerid,
            'success_url' => 'http://localhost/practice/stripe4/success.php?session_id={CHECKOUT_SESSION_ID}',
            'cancel_url' => 'http://localhost/practice/stripe4/failed.php',
        ]);

        // Redirect user to Stripe Checkout page
        header("Location: " . $checkout_session->url);
    }

    // Connect to MySQL database
    $conn = new mysqli('localhost', 'admin', 'Web@2050', 'db20');
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    $datetime = date('Y-m-d H:i:s');
    $status = "pending";
    $session_id = $checkout_session->id;
  
$sql = "INSERT INTO `payments` (`customer`,`session_id`,`datetime`, `status`)
             VALUES ('$customerid','$session_id','$datetime', '$status')";

    if ($conn->query($sql) === TRUE) {
        echo "<script> console.warn('Record added successfully') </script>";
    } else {
        echo "Error inserting payment details: " . $conn->error;
    }

    $conn->close();

} else {
    echo "Invalid request method";
}

?>