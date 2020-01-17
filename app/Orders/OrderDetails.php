<?php

namespace App\Orders;
use Illuminate\Support\Str;
use App\Billing\BankPaymentGateway;
use App\Billing\PaymentGatewayContract;

class OrderDetails
{
 
    private $paymentGateway;

    public function __construct(PaymentGatewayContract $paymentGateway)
    {
        
        $this->paymentGateway = $paymentGateway;
         
    }
    public function all()
    {
         
        $this->paymentGateway->setDiscount(500);
        return ['name'=>'victor',
        'address'=>'123 coder\'street'];
    }

}