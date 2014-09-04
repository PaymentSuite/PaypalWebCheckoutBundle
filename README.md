PaypalWebCheckoutBundle
=======================

PayPal web checkout for PaymentSuite

Configuration
-----

Configure the PaypalWebCheckout configuration in your `config.yml`

``` yml
paypal_web_checkout:

    # Paypal > Account > Pesonal Data > Merchant account ID
    business: XXXXXXXXXX
    debug: true|false
    
    # By default, controller route is /payment/paypal_web_checkout/execute
    controller_route: /my/custom/route

    # Configuration for payment success redirection
    #
    # Route defines which route will redirect if payment successes
    # If order_append is true, Bundle will append order identifier into route
    #    taking order_append_field value as parameter name and
    #    PaymentOrderWrapper->getOrderId() value
    payment_success:
        route: payment_success
        order_append: true
        order_append_field: order_id

    # Configuration for payment fail redirection
    #
    # Route defines which route will redirect if payment fails

    payment_fail:
        route: payment_failed
        order_append: false
        order_append_field: order_id
        
    # Configuration for payment success redirection
    #
    # Route defines which route paypal will execute to process the payment
    payment_process:
        route: payment_process
        path: /payment/paypal_web_checkout/process
```
