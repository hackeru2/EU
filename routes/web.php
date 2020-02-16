<?php
use Illuminate\Support\Collection;
use Illuminate\Support\LazyCollection;
use GuzzleHttp\Client;


/*Illuminate\Support\LazyCollection
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

Auth::routes();
 
Route::get('/pay' , 'PayOrderController@store');
Route::get('/channels' , 'ChannelController@index');
Route::get('posts/create' , 'PostController@create');
Route::get('lazy' , function(){
    $collection = Collection::times(1500000)->map(function($n){
        return pow(2, $n);
    })->all();
//  $collection = LazyCollection::times(1500000)->map(function($n){
//         return pow(2, $n);
//     })->all();
    
    return 'done';
});
Route::get('gen' , function(){
     
    function happyFunction($s){
        yield $s;
     }
    //  return get_class_methods(  //get_class
    //     happyFunction('Happy!'));
//    return happyFunction('Happy!')->current();
  $return =  happyFunction('Happy!');
     
});
Route::get('json' ,function ( )
{
    
    $client = new Client(['base_uri' => 'https://ec.europa.eu/info/funding-tenders/opportunities/data/referenceData/grantsTenders.json?lang=en']);
    // Send a request to https://foo.com/api/test
    $response = $client->request('GET', '');
     

    echo $response->getBody() ;



});


Route::get('/home', 'HomeController@index')->name('home');
Route::get('/try', 'AuthController@show') ;

 

Route::get('/home', 'HomeController@index')->name('home');
Route::get('/big-json', 'HomeController@getBigJson') ;
 
Route::get('/get-topic-details/{identifier}', 'HomeController@getTopicDetails') ;
Route::get('/me', 'AuthController@show') ;
Route::resource('flag', 'FlagController');
Route::resource('keywords', 'KeywordController');
Route::get('/{any?}', 'HomeController@profile')->name('profile');
Route::resource('profile', 'ProfileController');
Route::resource('user', 'UserController');
Route::get('/{any?}', 'HomeController@profile')->name('profile');
Route::get('calls/{any?}', 'HomeController@profile')->name('profile');
//Route::get('/profile', 'HomeController@profile')->name('profile');



