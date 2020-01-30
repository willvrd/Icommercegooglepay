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

    /**
    * Card networks supported by your site and your gateway
    * https://developers.google.com/pay/api/web/reference/request-objects#CardParameters|CardParameters
    */
    const allowedCardNetworks = ["AMEX", "DISCOVER", "MASTERCARD", "VISA"];
    
    /**
    * Card authentication methods supported by your site and your gateway
    * https://developers.google.com/pay/api/web/reference/request-objects#CardParameters|CardParameters
    */
    const allowedCardAuthMethods = ["PAN_ONLY", "CRYPTOGRAM_3DS"];

    /**
    * Describe your site's support for the CARD payment method and its required
    * fields
    * https://developers.google.com/pay/api/web/reference/request-objects#CardParameters|CardParameters
    */
    const baseCardPaymentMethod = {
      type: 'CARD',
      parameters: {
        allowedAuthMethods: allowedCardAuthMethods,
        allowedCardNetworks: allowedCardNetworks
      }
    };

    /**
    * Define the version of the Google Pay API 
    * https://developers.google.com/pay/api/web/reference/request-objects#PaymentDataRequest|apiVersion in PaymentDataRequest
    */
    const baseRequest = {
      apiVersion: 2,
      apiVersionMinor: 0,
      allowedPaymentMethods: baseCardPaymentMethod
    };

    /**
    * Identify your gateway and your site's gateway merchant identifier
    *
    * The Google Pay API response will return an encrypted payment method capable
    * of being charged by a supported gateway after payer authorization
    *
    * check with your gateway on the parameters to pass
    * https://developers.google.com/pay/api/web/reference/request-objects#gateway|PaymentMethodTokenizationSpecification
    */
    const tokenizationSpecification = {
      type: 'PAYMENT_GATEWAY',
      parameters: {
        'gateway': 'example',
        'gatewayMerchantId': 'exampleGatewayMerchantId'
      }
    };

    /**
    * Describe your site's support for the CARD payment method including optional
    * fields
    *
    * https://developers.google.com/pay/api/web/reference/request-objects#CardParameters|CardParameters
    */
    const cardPaymentMethod = Object.assign(
      {
       tokenizationSpecification: tokenizationSpecification
      },
      baseCardPaymentMethod
    );


    /**
    * initialized google.payments.api.PaymentsClient object or null if not yet set
    */
    let paymentsClient = null;


    /**
    * Return an active PaymentsClient or initialize
    * https://developers.google.com/pay/api/web/reference/client#PaymentsClient|PaymentsClient constructor
    */
    function getGooglePaymentsClient() {

      @if($config->mode==0)
        let enviroment = 'TEST'
      @else
        let enviroment = 'PRODUCTION'
      @endif

      console.warn("ENVIROMENT: "+enviroment)

      if ( paymentsClient === null ) {
        paymentsClient = new google.payments.api.PaymentsClient({
          environment: enviroment
        });
      }
      return paymentsClient;
    }

   
    /**
    *  INIT FUNCTION
    */
    function onGooglePayLoaded() {

        console.warn("INIT GOOGLE PAY")
        
        const paymentsClient = getGooglePaymentsClient();

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
    * Add a Google Pay purchase button alongside an existing checkout button
    * https://developers.google.com/pay/api/web/reference/request-objects#ButtonOptions|Button options
    * https://developers.google.com/pay/api/web/guides/brand-guidelines|Google Pay brand guidelines
    */
    function createAddButton() {

      const paymentsClient = getGooglePaymentsClient();
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
      
        const paymentDataRequest = Object.assign({}, baseRequest);
        paymentDataRequest.allowedPaymentMethods = [cardPaymentMethod];
        
        paymentDataRequest.transactionInfo = {
          totalPriceStatus: 'FINAL',
          totalPrice: '{{$order->total}}',
          currencyCode: '{{$order->currency_code}}',
          countryCode: '{{$order->payment_country}}'
        };

        paymentDataRequest.merchantInfo = {
          merchantName: "{{$config->merchantName}}",
          merchantId: "{{$config->merchantId}}"
        };

        console.warn("Payment Data Request: ", paymentDataRequest)
       
        paymentsClient.loadPaymentData(paymentDataRequest).then(function(paymentData){
          
          console.warn("INIT - LOAD PAYMENT DATA")            
          processPayment(paymentData);
          
        }).catch(function(err){
          // show error in developer console for debugging
          // Log error: { statusCode: CANCELED || DEVELOPER_ERROR }
          console.error("ERROR - PAYMENT BUTTON CLICKED: ",err);
          sendResponse(err,'btn-clicked')
        });

    }
    
    /**
      Process Payment
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

    /**
      Send Response to process Order
    */
    
    function sendResponse(response,type){

      var url = "{{route('icommercegooglepay.api.googlepay.response')}}";
      var orderId = {{$order->id}}
      
      $.ajax({
          
          url:url,
          type:"POST",
          data:{response:response,orderId,type},
          dataType:"JSON",
          beforeSend: function(){
              //console.warn("SEND RESPONSE: BEFORE")
          },
          success: function(result){

            if(result.data.redirectRoute){
              console.warn(result.data.redirectRoute)
              //window.location = result.data.redirectRoute
            }

          },
          error: function(result)
          {
            console.log('ERROR - SEND RESPONSE:', result);
          }

      });
      

    }
    
 

</script>   

<script async
  src="https://pay.google.com/gp/p/js/pay.js"
  onload="onGooglePayLoaded()">
</script>

@stop
