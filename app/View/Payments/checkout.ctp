<!-- app/View/Payments/embedded.ctp -->
<h2>Embedded Stripe Payment Form</h2>

<!-- Form for collecting payment details -->
<form id="payment-form">
    <div id="card-element">
        <!-- A Stripe Element will be inserted here. -->
    </div>

    <!-- Error message element -->
    <div id="card-errors" role="alert"></div>

    <button id="submit-button" class="btn btn-primary mt-3">Pay $20.00</button>
</form>

<!-- Include Stripe.js -->
<script src="https://js.stripe.com/v3/"></script>
<script>
    // Initialize Stripe with your public key
    var stripe = Stripe('pk_test_fK4FLd857rrVoQ8rWarTDBKW');
    var elements = stripe.elements();

    // Create an instance of the card Element
    var card = elements.create('card');
    card.mount('#card-element');

    // Handle form submission
    var form = document.getElementById('payment-form');

    form.addEventListener('submit', function(event) {
        event.preventDefault();

        stripe.createPaymentMethod({
            type: 'card',
            card: card,
        }).then(function(result) {
            if (result.error) {
                // Display error in #card-errors
                document.getElementById('card-errors').textContent = result.error.message;
            } else {
                // Send the payment method ID to the server
                fetch('<?php echo Router::url(['controller' => 'Payments', 'action' => 'checkout']); ?>', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                    },
                    body: JSON.stringify({ payment_method_id: result.paymentMethod.id }),
                })
                .then(function(response) {
                    return response.json();
                })
                .then(function(paymentResult) {
                    if (paymentResult.error) {
                        // Show error from server in #card-errors
                        document.getElementById('card-errors').textContent = paymentResult.error;
                    } else if (paymentResult.status === 'succeeded') {
                        // Payment was successful
                        alert('Payment succeeded!');
                    }
                })
                .catch(function(error) {
                    console.error('Error:', error);
                    alert('Failed to process payment. Please try again.');
                });
            }
        });
    });
</script>
