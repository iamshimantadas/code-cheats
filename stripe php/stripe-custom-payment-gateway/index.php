<?php require('config.php'); ?>

<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>stripe pay</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
</head>

<link rel="stylesheet" href="style.css">

<script src="https://code.jquery.com/jquery-3.7.1.js" integrity="sha256-eKhayi8LEQwp4NKxN+CfCh+3qOVUtJn3QNZ0TciWLP4="
    crossorigin="anonymous"></script>
<script src="https://js.stripe.com/v3/"></script>



<body>

    <section class="payment-sec">
        <div class="container">
            <div class="row">
                <div class="col-lg-12">
                    <div class="payment-head">
                        <h1>Payment Form</h1>
                    </div>
                </div>
                <div class="col-2"></div>
                <div class="col-8">

                    <form class="payment-form" method="post">
                        <div class="row">
                            <div class="col-lg-6">
                                <div class="form-group">
                                    <input type="text" name="name" id="name" placeholder="enter customer name">
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="form-group">
                                    <input type="text" name="email" id="email" placeholder=" enter customer email">
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="form-group">
                                    <input type="text" name="value" id="value" placeholder="enter product value">
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="form-group">
                                    <input type="text" name="product_name" id="product_name"
                                        placeholder="enter product name">
                                </div>
                            </div>
                            <div class="col-lg-12">
                                <div class="form-group">
                                    <div id="card-element" class="form-control mb-3"></div>
                                </div>
                            </div>
                        </div>

                        <button type="button" id="pay_btn">pay</button>
                    </form>

                </div>
                <div class="col-2"></div>
            </div>
        </div>
    </section>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz"
        crossorigin="anonymous"></script>

    <script>
        jQuery(document).ready(function () {
            var stripe = Stripe("<?php echo $stripe_publishable_key; ?>");
            var elements = stripe.elements();
            var cardElement = elements.create('card', { hidePostalCode: true });
            cardElement.mount('#card-element');

            jQuery('#pay_btn').click(function () {

                var value = jQuery('#value').val();
                var email = jQuery('#email').val();
                var name = jQuery('#name').val();
                var product_name = jQuery('#product_name').val();


                /**
                 * try to generating tokens frm the given card number, 
                 * if it got the token then it will send the request. to complete request.
                 */
                stripe.createToken(cardElement).then(function (result) {
                    if (result.error) {
                        alert(result.error.message);
                    } else if (result.token) {
                        let stripeToken = result.token.id;
                        // ajax request
                        jQuery.ajax({
                            url: 'checkout.php',
                            method: 'post',
                            data: {
                                 stripeToken: stripeToken,
                                value: value, email: email, name: name, product_name: product_name,
                            },
                            success: function (response) {
                                console.log(response);
                                alert("payment done!");
                            },
                            error: function (response) {
                                console.error(response);
                                alert("Some error occured! try again");
                            },
                        });
                    }

                });

            });
        });
    </script>


</body>

</html>