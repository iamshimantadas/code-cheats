<?php
require_once 'vendor/autoload.php';
require('config.php');
// $charge_id = "ch_3Pe9VS2MM6opJfa010l1R9Pb";
// $charge_id = "ch_3Pe9qK2MM6opJfa01NJsgtjF";
// $charge_id = "ch_3PeEJo2MM6opJfa01oFx8uSl";
$charge_id = "ch_3PeG2k2MM6opJfa00rfmhoBm";
// 


try {
    $stripe = new \Stripe\StripeClient($stripe_secret_key);
    $response = $stripe->charges->retrieve($charge_id, []);

    print_r($response);
} catch (Exception $e) {
    echo $e->getMessage();
}

?>