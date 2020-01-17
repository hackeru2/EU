<?php
//PaymentGateway
namespace App\Billing;
use Illuminate\Support\Str;
interface PaymentGatewayContract 
{

    public function setDiscount($amount);
    public function charge($amount);

}