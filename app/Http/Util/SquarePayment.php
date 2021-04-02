<?php

namespace App\Http\Util;

use Square\Environment;
use Square\Models\Money;
use Square\SquareClient;
use Square\Models\CreatePaymentRequest;

class SquarePayment
{
    private $client;
    private $nonce;

    public function __construct($nonce){
      $this->nonce = $nonce;
      $this->client = new SquareClient([
        'accessToken' => config('square.access_token'),
        'environment' => config('square.environment'),//Environment::SANDBOX,
    ]);
    }

    public function proceedPayment($amount){

        $payments_api = $this->client->getPaymentsApi();
        
        $money = new Money();
        $money->setAmount($amount * 100);
        $money->setCurrency('USD');
        $create_payment_request = new CreatePaymentRequest($this->nonce, uniqid(), $money);
        
        try {
            $response = $payments_api->createPayment($create_payment_request);

            $responseCode = $response->getStatusCode();

            if( $responseCode == 400 || $responseCode == 404 || $responseCode == 401){
                $error = $response->getErrors();
                $error = \is_array($error) ? $response->getErrors()[0] : $response->getErrors();
                return ['error' => ['code' => $error->getCode(), 'detail' => $error->getDetail(), 'category' => $error->getCategory()]];
            }
            else if($response->getStatusCode() == 200){
                $body = \json_decode($response->getBody());
                return ['payment_id' => $body->payment->id, 'amount' => $body->payment->amount_money->amount / 100, 'currency' => $body->payment->amount_money->currency, 'status' => $body->payment->status, 'order_id' => $body->payment->order_id];
                
            }

            else if($response->getStatusCode() == 200){
                $body = \json_decode($response->getBody());
                return ['payment_id' => $body->payment->id, 'amount' => $body->payment->amount_money->amount / 100, 'currency' => $body->payment->amount_money->currency, 'status' => $body->payment->status, 'order_id' => $body->payment->order_id];
                
            }
        } catch (Square\Exceptions\ApiException $e) {
            return $e;
        }
    }
   
}
