<?php
App::uses('Component', 'Controller');
App::import('Vendor', 'Stripe', array('file' => 'stripe-php/init.php'));

class StripeComponent extends Component
{
    public function initialize(Controller $controller) {
        \Stripe\Stripe::setApiKey(Configure::read('Stripe.secret')); // Set the secret key
    }

    // Create a one-time payment
    public function createCharge($amount, $currency, $source, $description) {
        return \Stripe\Charge::create([
            'amount' => $amount,
            'currency' => $currency,
            'source' => $source,
            'description' => $description,
        ]);
    }

    // Create a customer
    public function createCustomer($email, $source) {
        return \Stripe\Customer::create([
            'email' => $email,
            'source' => $source,
        ]);
    }

    // Create a subscription
    public function createSubscription($customerId, $planId) {
        return \Stripe\Subscription::create([
            'customer' => $customerId,
            'items' => [['plan' => $planId]],
        ]);
    }
}
