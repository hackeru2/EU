<?php

namespace App\Http\Controllers;

use App\User;
use Illuminate\Http\Request;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Profile  $profile
     * @return \Illuminate\Http\Response
     */
    public function show() //User $user
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\User  $profile
     * @return \Illuminate\Http\Response
     */
    public function edit(User $user)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\User  $User
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request  ) //User $user
    {

            $user =  auth()->user();
        
        
            $user->flags()->sync($request->flags);
            $user->profile->keywords_ccm2_Ids =  $request->keywords;
            $user->profile->topics =  $request->topics;
            $user->profile->save();
        return auth()->user()->with('flags','profile','tags')
        ->where('id' , auth()->user()->id)
        ->get()->last();
        
        return $request->all();
        //
        // $profile  = auth()->user()->profile()->first();
        // $experties =  collect($request->experties)->filter();
        // $profile->experties = $experties;
        // $profile->save();
        // return $profile;
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\User  $user
     * @return \Illuminate\Http\Response
     */
    public function destroy(User $user)
    {
        //
    }

    public function insertUserTags(Request $request) {

        $user_id = auth()->user()->id; 
        // $tag_ids = collect($request)->mapWithKeys(function($a ,) use($user_id){
        //     return [ $user_id => ['tag_id' => $a['id']]];
        // });
        $attributes = collect($request->all())->map->id->toArray();
        

        auth()->user()->tags()->sync($attributes);
         
        //   foreach($tag_ids as $tag_id) {
             
        //   }

         return auth()->user()->tags()->get();
    }
}
