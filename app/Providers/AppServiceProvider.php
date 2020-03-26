<?php

namespace App\Providers;

use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\View;
use App\Http\View\Composers\ChannelsComposer;
use Illuminate\Support\ServiceProvider;
use App\Billing\BankPaymentGateway;
use App\Billing\CreditPaymentGateway;
use App\Billing\PaymentGatewayContract;
use App\Channel;
class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        require_once __DIR__ . '/../Http/helpers.php';

        //bind
        $this->app->singleton(PaymentGatewayContract::class,
        function($app){
            if(request()->has('credit')) {
                return new CreditPaymentGateway('usd');
            }
           return new BankPaymentGateway('usd');
           
        });
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */

    public function boot()
    {
    Schema::defaultStringLength(191);
        //
        #1.Share
        // View::share('channels', Channel::orderBy('name')->get());
        #2.Select specific
        //  View::composer(['post.*','channel.index'], function($view) {
        //     return $view->with('channels', Channel::orderBy('name')->get() );
        //  });
        #3.
          View::composer('partials.channels.*',ChannelsComposer::class );
    }
    
}
