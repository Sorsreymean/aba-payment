<?php
namespace App\Services;

use Illuminate\Support\Facades\Http;
use Carbon\Carbon;
class ABAService
{
    protected $apiUrl;
    protected $merchantId;
    protected $apiKey;
    // protected $secretKey;

    public function __construct()
    {
        $this->apiUrl = env('ABA_PAYWAY_API_URL');
        $this->merchantId = env('ABA_PAYWAY_MERCHANT_ID');
        $this->apiKey = env('ABA_PAYWAY_API_KEY');
        // $this->secretKey = env('ABA_PAYWAY_SECRET_KEY');
    }

    public function createAccountOnFile($customerData)
    {
        // dd($customerData);
        $payload = [
            'merchant_id' => $this->merchantId,
            'customer_id' => $customerData['customer_id'], // Your unique customer ID
            'customer_name' => $customerData['name'],
            'customer_email' => $customerData['email'],
            'customer_phone' => $customerData['phone'],
            'req_time' =>Carbon::now()->format('YmdHis'),
            'hash'=>hash_hmac('sha512', json_encode($this->merchantId . $customerData['customer_id'] . $customerData['name'] . $customerData['email'] . $customerData['phone']),$this->apiKey)
        ];
        // dd($payload);
        // Add security signature if required by the API
        $signature = hash_hmac('sha512', json_encode($payload),$this->apiKey);
        // $signature = base64_encode(hash_hmac('sha512',$payload,$this->apiKey));
        $payload['signature'] = $signature;

        $response = Http::withHeaders([
            'Authorization' => 'Bearer ' . $this->apiKey,
            'Content-Type' => 'application/json',
        ])->post($this->apiUrl, $payload);

        return $response->json();
    }
    public function createCardOnFile($customerData)
    {
        $payload = [
            'merchant_id'   => $this->merchantId,
            'customer_id'   => $customerData['customer_id'],  // Unique customer identifier
            'customer_name' => $customerData['name'],
            'customer_email'=> $customerData['email'],
            'card_number'   => $customerData['card_number'],      // Full card number
            'card_expiry'   => $customerData['card_expiry'],      // MM/YY format
            'card_cvv'      => $customerData['card_cvv'],         // CVV code
            'timestamp'     => Carbon::now()->format('YmdHis'),
        ];
        // Add security signature if required
        $signature = hash_hmac('sha512', json_encode($payload), $this->apiKey);
        $payload['hash'] = $signature;
        // dd($payload);

        // Send request to create Card on File
        $response = Http::withHeaders([
            'Authorization' => 'Bearer ' . $this->apiKey,
            'Content-Type'  => 'application/json',
        ])->post($this->apiUrl, $payload);
        // dd($response);
        return $response;
    }
}


?>