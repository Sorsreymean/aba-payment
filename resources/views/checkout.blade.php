<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>
    <style>
        #checkout_buttom{
            border:none;
            padding:15px 30px;
            font-size:16px;
            cursor: pointer;
            border-radius: 5px;
            transition: background-color 0.3s ease;
        }
        #checkout_button:hover{
            background-color: #0056b3; 
        }
    </style>
</head>
<body>
    <div class="coantiner" style="margin-top: 75px;margin:0 auto;">
        <div style="width:200px;margin:0 auto">
            <h2>TOTAL: ${{$amount}}</h2>
            <form method="POST" action="{{config('payway.api_url')}}" target="aba_webservice" id="aba_merchant_request">
            {{-- <form id="paymentForm"> --}}
                @csrf
                <input type="hidden" name="hash" value="{{$hash}}" id="hash">
                <input type="hidden" name="tran_id" value="{{$transactionId}}" id="tran_id">
                <input type="hidden" name="amount" value="{{$amount}}" id="amount">
                <input type="hidden" name="firstname" value="{{$firstname}}" id="firstname">
                <input type="hidden" name="lastname" value="{{$lastname}}" id="lastname">
                <input type="hidden" name="phone" value="{{$phone}}" id="phone">
                <input type="hidden" name="email" value="{{$email}}" id="email">
                <input type="hidden" name="items" value="{{$items}}" id="items">
                <input type="hidden" name="return_params" value="{{$return_params}}" id="return_params">
                <input type="hidden" name="shipping" value="{{$shipping}}" id="shipping">
                <input type="hidden" name="currency" value="{{$currency}}" id="currency">
                <input type="hidden" name="type" value="{{$type}}" id="type">
                <input type="hidden" name="payment_option" value="{{$payment_option}}" id="payment_option">
                <input type="hidden" name="merchant_id" value="{{$merchant_id}}" id="merchant_id">
                <input type="hidden" name="req_time" value="{{$req_time}}" id="req_time">
                {{-- <label for="amount">Amount:</label>
                <input type="number" id="amount" name="amount" value="10" required><br>

                <label for="currency">Currency:</label>
                <input type="text" id="currency" name="currency" value="USD" required><br>

                <label for="description">Description:</label>
                <textarea id="description" name="description" ></textarea><br>

                <label for="return_url">Return URL:</label>
                <input type="url" id="return_url" name="return_url" value="https://app1.faceazure.net/" required><br>

                <label for="cancel_url">Cancel URL:</label>
                <input type="url" id="cancel_url" name="cancel_url" value="http://127.0.0.1:8000/checkout" required><br>
                <button type="submit">Checkout</button> --}}
            </form>
            <input type="submit" value="Checkout now" id="checkout_button">
        </div>
    </div>
    <script src="https://checkout.payway.com.kh/plugins/checkout2-0.js"></script>
    <script>
         document.getElementById('checkout_button').addEventListener('click', function() {
            AbaPayway.checkout();
            
        });
       
    </script>
</body>
</html>