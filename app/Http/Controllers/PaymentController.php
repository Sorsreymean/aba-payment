<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\PayWayService;
use Illuminate\Support\Facades\Http;
use GuzzleHttp\Client;
use App\Services\ABAService;
use SimpleSoftwareIO\QrCode\Facades\QrCode;
class PaymentController extends Controller
{
    protected $payWayService;
    protected $abaService;

    public function __construct(ABAService $abaService)
    {
        $this->abaService = $abaService;
    }
    // public function _construct(PaywayService $payWayService){
    //     $this->payWayService = $payWayService;
    // }
    public function showCheckOutForm(){
        $item=[
            ['name'=>'test1','quantity'=>'1','price'=>'10.00'],
            ['name'=>'test1','quantity'=>'1','price'=>'10.00']
        ];
        $items = base64_encode(json_encode($item));
        $req_time = time();
        $transactionId = $req_time;
        $amount = '15.00';
        $firstname = 'Tracy';
        $lastname = 'Isabel';
        $phone = '092345455';
        $email = 'admin@gmail.com';
        $return_params = 'Hello world';
        $type = 'purchase';
        $currency = 'USD';
        $shipping = '0.60';
        $merchant_id = config('payway.merchant_id');
        $payment_option = '';
        $payway = new PayWayService();
        // dd($payway->getHash());
        $hash = $payway->getHash(
            $req_time . $merchant_id . $transactionId . $amount . $items . $shipping . $firstname . $lastname . $email . $phone . $type . $payment_option . $currency . $return_params
        );
        return view('checkout',compact('hash','transactionId','amount','firstname','lastname','phone','email','items','return_params','shipping','currency','type','payment_option','merchant_id','req_time'));
    }
    public function createPaymentLink(Request $request)
    {
        // Validate the input data (amount, description, etc.)
        $validatedData = $request->validate([
            'amount' => 'required|numeric',
            // 'description' => 'required|string|max:255',
        ]);

        // Prepare the payload for the ABA PayWay API
        $payload = [
            'amount' => $validatedData['amount'],
            'currency' => 'USD', // Assuming the currency is USD
            'description' => $request->description,
            'return_url' => env('ABA_PAYWAY_RETURN_URL'),
            'cancel_url' => env('ABA_PAYWAY_CANCEL_URL'),
            'tran_id' => time(),
        ];
        dd($payload);

        // Make the API request to create the payment link
        try {
            $response = Http::withHeaders([
                'Authorization' => 'Bearer ' . env('ABA_PAYWAY_API_KEY'),
                'Content-Type' => 'application/json',
            ])->post(env('ABA_PAYWAY_API_URL'), $payload);
                dd($response);
            // Check if the request was successful
            if ($response->successful()) {
                // Extract the payment link from the response
                $paymentLink = $response->json()['payment_link'] ?? null;
                dd($paymentLink);
                if ($paymentLink) {
                    return response()->json([
                        'success' => true,
                        'payment_link' => $paymentLink,
                    ]);
                } else {
                    return response()->json([
                        'success' => false,
                        'message' => 'Failed to create payment link.',
                    ], 500);
                }
            } else {
                return response()->json([
                    'success' => false,
                    'message' => 'Payment link creation failed. API error.',
                    'error_details' => $response->json(),
                ], $response->status());
            }

        } catch (\Exception $e) {
            // Handle exceptions (like network issues or API errors)
            return response()->json([
                'success' => false,
                'message' => 'An error occurred while creating the payment link.',
                'error' => $e->getMessage(),
            ], 500);
        }
    }
    public function createABAPaymentLink(Request $request)
    {
        // dd($request);
        // ABA PayWay credentials
        $merchantId = env('ABA_PAYWAY_MERCHANT_ID');
        $merchantKey = env('ABA_PAYWAY_API_KEY');
        $apiUrl = env('ABA_PAYWAY_API_URL');

        // Transaction data
        $transactionId = uniqid();  // Generate unique transaction ID
        $amount = $request->amount;  // Amount to be paid
        $currency = 'USD';  // Use currency relevant to your business
        $description = 'Payment for Order #'.$transactionId;
        $title = 'Shoes';  // Payment description
        $returnUrl = route('payment.success');  // Success URL (callback)
        $cancelUrl = route('payment.cancel');   // Cancel URL
        $req_time = time();
        // Signature generation (ABA PayWay requires a hashed signature)
        $hashData = $merchantId . $transactionId . $amount . $currency . $returnUrl . $cancelUrl . $merchantKey;
        $signature = hash('sha512', $hashData);
        $merchant = $merchantId . $title . $amount . $returnUrl;
        $merchant_auth = hash('sha512',$merchant);
        // Prepare payment request payload
        $paymentData = [
            'merchant_id' => $merchantId,
            'tran_id' => $transactionId,
            'amount' => $amount,
            'currency' => $currency,
            'description' => $description,
            'return_url' => $returnUrl,
            'cancel_url' => $cancelUrl,
            'hash' => $signature,  // Include the signature
        ];
        // dd($paymentData);
        // $paymentData = [
        //     'req_time' => $req_time,
        //     'merchant_id' =>$merchantId ,
        //     'merchant_auth' => $merchant_auth,
        //     'image' => "IMAGE",
        //     'hash' => $signature,

        // ];
        // dd($paymentData);

        // Use Guzzle to send the request to ABA PayWay
        $client = new Client();
        // dd($client);
        try {
            $response = $client->post($apiUrl, [
                'form_params' => $paymentData,
                'timeout' => 60,
            ]);
            dd($response->getBody());
            
            // Get response from ABA PayWay
            $responseBody = json_decode($response->getBody(), true);
            dd($responseBody);
            // Check if response is successful
            if ($responseBody['status'] == 'success') {
                // Redirect user to the ABA payment URL
                return redirect($responseBody['payment_url']);
            } else {
                return back()->withErrors('Payment initiation failed. Please try again.');
            }
        } catch (\Exception $e) {
            dd($e);
            return back()->withErrors('Payment processing error: ' . $e->getMessage());
        }
    }


    public function createAccount(Request $request)
    {
        $data = [
            'customer_id' => $request->customer_id,
            'name' => $request->name,
            'email' => $request->email,
            'phone' => $request->phone,
        ];
        $abaService = new ABAService();
        $response = $abaService->createAccountOnFile($data);
        dd($response);
        if (isset($response['success']) && $response['success']) {
            return response()->json(['message' => 'Account created successfully!']);
        } else {
            return response()->json(['error' => 'Failed to create account.'], 400);
        }
    }
    public function createCardOnFile(Request $request)
    {
        // Validate incoming data
        $data = [
            'customer_id' => $request->customer_id,
            'name' =>  $request->name,
            'email' => $request->email,
            'card_number' => $request->card_number,
            'card_expiry' => $request->card_expiry, // Format MM/YY
            'card_cvv' => $request->card_cvv,
        ];
        $abaService = new ABAService();
        // Send request to ABAService to create Card on File
        // dd($abaService);
        $resp = $abaService->createCardOnFile($data);
        dd($resp);
        // return view('create-card');
        if (isset($response['success']) && $response['success']) {
            return response()->json(['message' => 'Card stored successfully!']);
        } else {
            return response()->json(['error' => 'Failed to store card.'], 400);
        }
    }
    public function generateKHQRData($merchant_id, $transaction_amount, $currency = 'USD')
    {
        return [
            '00' => '01',               // Payload Format Indicator
            '01' => '11',               // Point of Initiation Method
            '33' => [                   // Merchant Account Information
                '00' => 'KH',           // Country Code
                '01' => $merchant_id,    // Merchant ID
                '02' => 'Some Bank'      // Merchant Bank Information
            ],
            '54' => $currency,          // Transaction Currency
            '55' => number_format($transaction_amount, 2, '.', ''), // Transaction Amount
            '58' => 'KH',               // Country Code
            '59' => 'Merchant Name',    // Merchant Name
            '60' => 'Merchant City'     // Merchant City
        ];
    }
    public function generateKHQRString($data)
    {
        $result = '';
        foreach ($data as $key => $value) {
            if (is_array($value)) {
                $subResult = '';
                foreach ($value as $subKey => $subValue) {
                    $subResult .= $subKey . sprintf('%02d', strlen($subValue)) . $subValue;
                }
                $result .= $key . sprintf('%02d', strlen($subResult)) . $subResult;
            } else {
                $result .= $key . sprintf('%02d', strlen($value)) . $value;
            }
        }
        return $result;
    }
    public function generate(){
        $merchant_id = '123456';
        $transaction_amount = 5.00; // Example amount
        
        // Generate KHQR data array
        $khqrData = $this->generateKHQRData($merchant_id, $transaction_amount);
        
        // Generate KHQR string
        $khqrString = $this->generateKHQRString($khqrData);
        
        // Generate the QR code
        $qrCode = QrCode::size(300)->generate($khqrString);
    
        return view('khqr', ['qrCode' => $qrCode]);
    }
}
