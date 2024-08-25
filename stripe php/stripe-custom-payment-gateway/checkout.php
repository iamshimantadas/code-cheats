<?php
require_once 'vendor/autoload.php';
require ('config.php');

$cardid = $_POST['card'];
$amount = $_POST['value'];
$email = $_POST['email'];
$name = $_POST['name'];
$product_name = $_POST['product_name'];
$token = $_POST['stripeToken'];
// set it accordingly
$currency = "usd";
$date = date('Y-m-d H:i:s');

try {
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        if ($token) {
            $stripe = new \Stripe\StripeClient($stripe_secret_key);
            $stripe = $stripe->tokens->retrieve($token, []);
            // validate if the user's stripe token is valid or not 
            if ($stripe['created']) {
                // retrive customer
                $stripe = new \Stripe\StripeClient($stripe_secret_key);
                $stripe = $stripe->customers->search([
                    'query' => 'email:\'' . $email . '\'',
                ]);
                $customer = $stripe['data'][0];

                if ($customer->id) {
                    $stripe = new \Stripe\StripeClient($stripe_secret_key);
                    $response = $stripe->charges->create([
                        'amount' => $amount * 100,
                        // set it manually but it shold be changed by woocommerce type of something else
                        'currency' => $currency,
                        'customer' => $customer->id,
                        "metadata" => [
                            "email" => $email,
                            "product_amount" => $amount,
                            "product_name" => $product_name,
                            "customer_name" => $name,
                        ],
                    ]);


                } else {
                    $stripe = new \Stripe\StripeClient($stripe_secret_key);
                    $stripe = $stripe->customers->create([
                        'name' => $customer,
                        'email' => $email,
                    ]);
                    $customer = $stripe['data'][0];

                    $stripe = new \Stripe\StripeClient($stripe_secret_key);
                    $response = $stripe->charges->create([
                        'amount' => $amount * 100,
                        'currency' => $currency,
                        'customer' => $customer->id,
                        "metadata" => [
                            "email" => $email,
                            "product_amount" => $amount,
                            "product_name" => $product_name,
                            "customer_name" => $name,
                        ],
                    ]);
                }

                $sql = "INSERT INTO `payments` (`id`, `customer_id`, `transaction_id`, `product_name`, `currency`, `amount`, `date`) VALUES (NULL, '$customer->id', '$response->id', '$product_name', '$currency', '$amount', '$date')";
                $query = mysqli_query($conn, $sql);
                if ($query) {
                    echo json_encode(array("message" => "payment done successfully!"), true);
                }
            }
        } else {
            echo json_encode(array("message" => "not a valid token request"), true);
        }
    } else {
        echo json_encode(array("message" => "bad method call"), true);
    }


} catch (Exception $e) {
    echo $e->getMessage();
}

?>