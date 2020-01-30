@extends('layouts.master')


@section('title')
     Google Pay | @parent
@stop


@section('content')
<div class="icommerce_googlepay icommerce_googlepay_index">
  <div class="container">


    <div class="row my-5">

       <h2 class="text-center mx-auto">Google Pay</h2>

    </div>

    <div class="row my-5 justify-content-center">

      <div id="btn-google"></div>

    </div>


  </div>
</div>
@stop

@section('scripts')
@parent


<script type="text/javascript">

    let paymentsClient;

    /**
      Base variables
    */
    const allowedCardNetworks = ["AMEX", "DISCOVER", "MASTERCARD", "VISA"];
    const allowedCardAuthMethods = ["PAN_ONLY", "CRYPTOGRAM_3DS"];

    const baseCardPaymentMethod = {
      type: 'CARD',
      parameters: {
        allowedAuthMethods: allowedCardAuthMethods,
        allowedCardNetworks: allowedCardNetworks
      }
    };

    const baseRequest = {
      apiVersion: 2,
      apiVersionMinor: 0,
      allowedPaymentMethods: baseCardPaymentMethod
    };


    /**
      Specifications User Google
    */
    const tokenizationSpecification = {
      type: 'PAYMENT_GATEWAY',
      parameters: {
        'gateway': 'example',
        'gatewayMerchantId': 'exampleGatewayMerchantId'
      }
    };

    const cardPaymentMethod = Object.assign(
     {tokenizationSpecification: tokenizationSpecification},
      baseCardPaymentMethod
    );


    /**
      Init Function
    */
    function onGooglePayLoaded() {
        console.warn("INIT GOOGLE PAY")
        
        @if($config->mode==0)
            let enviroment = 'TEST'
        @else
            let enviroment = 'PRODUCTION'
        @endif

        console.warn("ENVIROMENT: "+enviroment)

        paymentsClient = new google.payments.api.PaymentsClient({
            environment: enviroment
        });

        const isReadyToPayRequest = Object.assign({}, baseRequest);

        isReadyToPayRequest.allowedPaymentMethods = [baseCardPaymentMethod];

        paymentsClient.isReadyToPay(isReadyToPayRequest).then(function(response) {
          if (response.result) {
            createAddButton();
          }else{
            alert("Unable to pay using Google Pay");
          }
        }).catch(function(err) {
          // show error in developer console for debugging
          console.error("ERROR - onGooglePayLoaded: determining readiness to use Google Pay: ", err);
        });
       
    }

    /**
      Create add button
    */
    function createAddButton() {

      const button = paymentsClient.createButton({
          // currently defaults to black if default or omitted
          buttonColor: 'default',
          // defaults to long if omitted
          buttonType: 'long',
          onClick: paymentsButtonClicked
        });
      document.getElementById('btn-google').appendChild(button);

    }

    /**
      Button clicked
    */
    function paymentsButtonClicked(){
      
        //console.warn("CLICK BUTTON")

        const paymentDataRequest = Object.assign({}, baseRequest);
        paymentDataRequest.allowedPaymentMethods = [cardPaymentMethod];
        
       
        paymentDataRequest.transactionInfo = {
          totalPriceStatus: 'FINAL',
          totalPrice: '123.45',
          currencyCode: 'USD',
          countryCode: 'US'
        };

        paymentDataRequest.merchantInfo = {
          merchantName: 'Example Merchant',
          merchantId: '0123456789'
        };

        console.warn("Payment Data Request: ", paymentDataRequest)
       
        paymentsClient.loadPaymentData(paymentDataRequest).then(function(paymentData){
          
          console.warn("INIT - LOAD PAYMENT DATA")            
          processPayment(paymentData);
          
        }).catch(function(err){
          // show error in developer console for debugging
          // Log error: { statusCode: CANCELED || DEVELOPER_ERROR }
          console.error("ERROR - PAYMENT BUTTON CLICKED: ",err);
        });

    }
    
    /**
      Procesando el pago
    */
    function processPayment(paymentData){
      
      console.warn("PROCESADO EL PAGO")
      console.log(paymentData);

       // @todo pass payment token to your gateway to process payment
      //paymentToken = paymentData.paymentMethodData.tokenizationData.token;

      /*
      return new Promise(function(resolve, reject) {
        // @todo pass payment token to your gateway to process payment
        const paymentToken = paymentData.paymentMethodData.tokenizationData.token;
        console.log('mock send token ' + paymentToken + ' to payment processor');
        setTimeout(function() {
          console.log('mock response from processor');
          alert('done');
          resolve({});
        }, 800);
      });
      */

    }
 

</script>   

<script async
  src="https://pay.google.com/gp/p/js/pay.js"
  onload="onGooglePayLoaded()">
</script>

@stop
