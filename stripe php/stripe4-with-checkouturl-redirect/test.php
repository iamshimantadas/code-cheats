<?php 
require ('vendor/autoload.php');

$secret_key = "sk_test_51Pd3KvJ2";


\Stripe\Stripe::setApiKey($secret_key);

  $checkout_session = \Stripe\Checkout\Session::retrieve('cs_test_a1QN6YVatJAqzQi99LX');

echo json_encode($checkout_session, true);

?>