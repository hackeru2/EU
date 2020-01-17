<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Channel;

class ChannelController extends Controller
{
    //
    public function index()
    {
        # code...
        //return view('channel.index',compact('channels'));
        return view('channel.index'); 
    }
}
