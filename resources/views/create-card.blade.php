
<!doctype html>
<html lang="en">
   <head> 
      <meta charset="utf-8"> 
      <meta http-equiv="X-UA-Compatible" content="IE=edge">
      <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
      <meta name="description" content="">
      <meta name="author" content="PayWay">
      <title>PayWay Add Card Sample</title>
      {{-- <link href="http://demo.payway.com.kh/css/bootstrap.min.css" rel="stylesheet"> --}}
      {{-- <link rel="stylesheet" href="https://payway-staging.ababank.com/checkout-popup.html?file=css"/> --}}
      <style type="text/css">
         /* Your css style*/
      </style>
      
    </head> 
    <body> 
        <div class="container">
        </div>
        <!-- The Modal -->
        <div id="aba_main_modal" class="aba-modal">
            <!-- Modal content --> 
            <div class="aba-modal-content add-card"> 
                <form method="POST" target="aba_webservice" action="https://checkout-sandbox.payway.com.kh/api/payment-gateway/v1/cof/initial" id="aba_merchant_add_card">
                    <input type="hidden" name="firstname" value="Samnang"/>
                    <input type="hidden" name="lastname" value="Sok"/>
                    <input type="hidden" name="phone" value="0123456789"/>
                    <input type="hidden" name="email" value="sok.samnang@gmail.com"/>
                    <input type="hidden" name="ctid" value="239acf04eace99ea1590857c7066acf260e"/>
                    <input type="hidden" name="merchant_id" value="###"/>
                    <input type="hidden" name="return_param" value="rp-1582083583"/>
                    <input type="hidden" name="hash" value="###"/>
                    <button id="add_card_button" class="btn btn-primary add-to-card">Add New Card</button>
                </form>
            </div>
        </div>
        <script src="https://payway-staging.ababank.com/checkout-popup.html?file=js"></script>
      <script>
        document.getElementById('add_card_button').addEventListener('click', function() {
            AbaPayway.addCard(); 
        }); 
      </script>
   </body>
</html>