<?php

namespace App\Http\Controllers;

use App\messages;
use App\User;
use Illuminate\Http\Request;
use Auth;
class MessagesController extends Controller
{


    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index($user_id)
    {
        $sent_messages = messages::where([
            'user_id' => Auth::user()->id,
            'sent_to' => $user_id
        ])->get()->toArray();

        $receive_messages = messages::where([
            'sent_to' => Auth::user()->id,
            'user_id' => $user_id
        ])->get()->toArray();

        return array_merge($sent_messages, $receive_messages);
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
        $messages = new messages();
        $messages->user_id = Auth::user()->id;
        $messages->message = $request->message;
        $messages->sent_to = $request->sent_to;
        $messages->save();
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\messages  $messages
     * @return \Illuminate\Http\Response
     */
    public function show(messages $messages)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\messages  $messages
     * @return \Illuminate\Http\Response
     */
    public function edit(messages $messages)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\messages  $messages
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, messages $messages)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\messages  $messages
     * @return \Illuminate\Http\Response
     */
    public function destroy(messages $messages)
    {
        //
    }

    public function getUserId($username){
        return User::where('username',$username)->first();
    }
}
