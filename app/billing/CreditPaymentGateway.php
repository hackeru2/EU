<?php

namespace App\Billing;
use Illuminate\Support\Str;
class CreditPaymentGateway implements PaymentGatewayContract//PaymentGateway
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
        $fees = $amount * 0.03  ;
        //charge the bank

        return [ 'amount' => ($amount - $this->discount) - $fees,
        'confirmation_number'=> Str::random(),
        'currency'=> $this->currency,
        'discount' =>$this->discount, 
        'fees' => $fees ];
    }

}