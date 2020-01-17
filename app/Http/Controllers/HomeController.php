<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use GuzzleHttp\Client;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Cache;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        return view('home');
    }

    public function profile(Request $request)
    {
                 
       //if($request->path() == "calls" ) // ; return  $this->getBigJson() ;
        return view('profile');
    }
     
    public function getBigJson()
    {
        
       // $value = Cache::forget('big_json');
        $value = Cache::remember('big_json', 5000 , function () {
            $client = new Client(['base_uri' => 'https://ec.europa.eu/info/funding-tenders/opportunities/data/referenceData/grantsTenders.json?lang=en']);
            $res =   $client->request('GET', '');
            return  head(head(json_decode($res->getBody() , 1)));
              
        });
        return  $value ;
        $response = Cache::remember('big_json', 36000, function () {

            $client = new Client(['base_uri' => 'https://ec.europa.eu/info/funding-tenders/opportunities/data/referenceData/grantsTenders.json?lang=en']);
          // Send a request to https://foo.com/api/test
          return  $client->request('GET', '')->getBody();
        });
        return $response->getBody();
       // file_put_contents(public_path('json\eu1_calls.json'), $response->getBody());
     //  return view('profile' , compact('bigJson' ) );     $response->getBody()
       return view('profile' );




     

 
    }
    public function storageEUCallUrl(Type $var = null)
    {
       
        $url = Storage::url('https://ec.europa.eu/info/funding-tenders/opportunities/data/referenceData/grantsTenders.json?lang=en');
        info($url);
        echo asset('storage/public.eu_calls.json');
      
    }

    public function getTopicDetails(Request $request, $identifier )
    {
        
       // $value = Cache::forget('big_json');
        $value = Cache::remember("t_d_{$identifier}", 5000 , function () use ($identifier) {
            $client = new Client(['base_uri' => "https://ec.europa.eu/info/funding-tenders/opportunities/data/topicDetails/{$identifier}.json"]);
            $res =   $client->request('GET', '');
            return head(json_decode($res->getBody() , 1));
              
        });
        return  $value ;

    }
}
