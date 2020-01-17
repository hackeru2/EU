<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Channel;

class PostController extends Controller
{
public function create()
{
    # code...
//    return view('post.create',compact('channels')); 
    return view('post.create'); 
}    //
}
