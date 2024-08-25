<!-- <!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>one page payment</title>
</head>

<body>


    <form action="checkout.php" method="post">
        <input type="email" name="email" placeholder="enter customer email">
        <br>
        <input type="text" name="customer_name" placeholder="enter customer full name">
        <br>
        <input type="text" name="product" placeholder="enter product name">
        <br>
        <input type="number" min="1" name="product" placeholder="enter card number">
        <br>
        <input type="date" name="carddate" id="carddate" placeholder="enter expiry date">
        <br>
        <input type="number" min="1" name="cvv" id="cvv" placeholder="enter cvv number">
        <br>
        <input type="number" min="1" name="price" id="price" placeholder="enter product price">
        <button type="submit">pay</button>
    </form>


</body>

</html> -->


<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>One Page Payment</title>
    <script src="https://js.stripe.com/v3/"></script>
</head>

<body>
    <form id="payment-form">
        <input type="email" name="email" id="email" placeholder="Enter customer email">
        <br>
        <input type="text" name="customer_name" id="customer_name" placeholder="Enter customer full name">
        <br>
        <input type="text" name="product" id="product" placeholder="Enter product name">
        <br>
        <input type="number" min="1" name="price" id="price" placeholder="Enter product price">
        <br>
        <input type="text" id="card-number" placeholder="Enter card number">
        <br>
        <input type="text" id="card-exp-month" placeholder="Enter expiry month (MM)">
        <br>
        <input type="text" id="card-exp-year" placeholder="Enter expiry year (YYYY)">
        <br>
        <input type="text" id="card-cvc" placeholder="Enter CVC">
        <br>
        <button type="submit">Pay</button>
    </form>

    <script>
        var stripe = Stripe('pk_test_51Pd3ztWdHQ'); // Replace with your public key

        var form = document.getElementById('payment-form');
        form.addEventListener('submit', function (event) {
            event.preventDefault();

            var cardDetails = {
                number: document.getElementById('card-number').value,
                exp_month: document.getElementById('card-exp-month').value,
                exp_year: document.getElementById('card-exp-year').value,
                cvc: document.getElementById('card-cvc').value
            };

            stripe.createToken('card', cardDetails).then(function (result) {
                if (result.error) {
                    console.error(result.error.message);
                } else {
                    fetch('/create-payment-intent.php', {
                        method: 'POST',
                        headers: { 'Content-Type': 'application/json' },
                        body: JSON.stringify({
                            email: document.getElementById('email').value,
                            customer_name: document.getElementById('customer_name').value,
                            product: document.getElementById('product').value,
                            price: document.getElementById('price').value,
                            token: result.token.id
                        })
                    }).then(function (result) {
                        return result.json();
                    }).then(function (data) {
                        if (data.error) {
                            console.error(data.error);
                        } else {
                            console.log('Payment successful!');
                        }
                    });
                }
            });
        });
    </script>
</body>

</html>
