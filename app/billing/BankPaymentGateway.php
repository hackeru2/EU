<?php

namespace App\Billing;
use Illuminate\Support\Str;
class BankPaymentGateway implements PaymentGatewayContract//PaymentGateway
{
 
    private $currency , $discount;



    public function __construct($currency)
    {
        $this->currency = $currency;
        $this->dicscount = 0 ;
    }

    public function setDiscount($amount)
    {
        $this->discount = $amount;
    }
    public function charge($amount)
    {
        
        //charge the bank

        return [ 'amount' => $amount - $this->discount,
        'confirmation_number'=> Str::random(),
        'currency'=> $this->currency,
        'discount' =>$this->discount ];
    }

}