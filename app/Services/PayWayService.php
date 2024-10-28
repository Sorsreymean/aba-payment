<?php
namespace App\Services;
class PayWayService{
    public function getApiUrl(){
        return config('payway.api_url');
    }
    public function getHash($str){
        // dd($str);
        $key = config('payway.api_key');
        return base64_encode(hash_hmac('sha512',$str,$key,true));
    }
}

?>