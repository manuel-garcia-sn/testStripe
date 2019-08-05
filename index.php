<?php

session_start();

require_once('vendor/autoload.php');

\Stripe\Stripe::setApiKey("");

if (isset($_SESSION['customer'])) {
    header('Location: http://localhost:8080/routes/last_movements.php');
    exit;
}
?>

<html>
<head>
    <title>IBAN Element Example</title>
    <link href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" type="text/css" rel="stylesheet">
    <link rel="stylesheet" href="styles.css">

<body>
<div class="wrapper">
    <script src="https://js.stripe.com/v3/"></script>
    <form action="routes/payment.php" method="post" id="payment-form">
        <div class="form-row inline">
            <div class="col">
                <label for="name">
                    Name
                </label>
                <input id="card_name" name="card_name" placeholder="Jenny Rosen" required>
            </div>
            <div class="col">
                <label for="email">
                    Email Address
                </label>
                <input id="email" name="email" type="email" placeholder="jenny.rosen@example.com" required>
            </div>
        </div>

        <div class="form-row">
            <label for="card-element">
                Credit or debit card
            </label>
            <div id="card-element">
                <!-- A Stripe Element will be inserted here. -->
            </div>

            <!-- Used to display form errors. -->
            <div id="card-errors" role="alert"></div>
        </div>

        <button>Add Credit Card</button>
    </form>
</div>

<script>
    // Create a Stripe client.
    var stripe = Stripe('pk_test_SlGtEI2y9tfKHLRbYFN9yufu');
    // Create an instance of Elements.
    var elements = stripe.elements();

    // Custom styling can be passed to options when creating an Element.
    // (Note that this demo uses a wider set of styles than the guide below.)
    var style = {
        base: {
            color: '#32325d',
            fontFamily: '"Helvetica Neue", Helvetica, sans-serif',
            fontSmoothing: 'antialiased',
            fontSize: '16px',
            '::placeholder': {
                color: '#aab7c4'
            }
        },
        invalid: {
            color: '#fa755a',
            iconColor: '#fa755a'
        }
    };

    // Create an instance of the card Element.
    var card = elements.create('card', {style: style});

    // Add an instance of the card Element into the `card-element` <div>.
    card.mount('#card-element');

    // Handle real-time validation errors from the card Element.
    card.addEventListener('change', function(event) {
        var displayError = document.getElementById('card-errors');
        if (event.error) {
            displayError.textContent = event.error.message;
        } else {
            displayError.textContent = '';
        }
    });

    // Handle form submission.
    var form = document.getElementById('payment-form');

    form.addEventListener('submit', function(event) {
        event.preventDefault();

        stripe.createToken(card, {
            name: document.getElementById('card_name').value
        }).then(function(result) {
            if (result.error) {
                // Inform the user if there was an error.
                var errorElement = document.getElementById('card-errors');
                errorElement.textContent = result.error.message;
            } else {
                // Send the token to your server.
                stripeTokenHandler(result.token);
            }
        });
    });

    // Submit the form with the token ID.
    function stripeTokenHandler(token) {
        // Insert the token ID into the form so it gets submitted to the server
        var form = document.getElementById('payment-form');
        var hiddenInput = document.createElement('input');
        hiddenInput.setAttribute('type', 'hidden');
        hiddenInput.setAttribute('name', 'stripeToken');
        hiddenInput.setAttribute('value', token.id);
        form.appendChild(hiddenInput);

        // Submit the form
        form.submit();
    }
</script>
</body>
</html>
