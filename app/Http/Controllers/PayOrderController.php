<?php

namespace App\Http\Controllers;

use App\User;
use Illuminate\Http\Request;
//use App\Billing\PaymentGateway;
use App\Billing\PaymentGatewayContract;
use App\Orders\OrderDetails;

class PayOrderController extends Controller
{
    public function store(OrderDetails $orderDetails,PaymentGatewayContract $paymentGateway)
    {
        return User::find(1);
        $order = $orderDetails->all();
        //$paymentGateway = new PaymentGateway('usd');
        dd($paymentGateway->charge(2500));
    }
}