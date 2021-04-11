<?php

namespace App\Http\Controllers;

use App\Messages;
use App\User;
use App\Events\ChatEvent;
use Illuminate\Http\Request;
use Auth;
use App\Events\LogoutEvent;
class MessagesController extends Controller
{


    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index($user_id)
    {
        $sent_messages = Messages::where([
            'user_id' => Auth::user()->id,
            'sent_to' => $user_id
        ])->get()->toArray();

        $receive_messages = Messages::where([
            'sent_to' => Auth::user()->id,
            'user_id' => $user_id
        ])->get()->toArray();

        $sorted_ = collect(array_merge($sent_messages, $receive_messages))->sortBy('created_at')->values()->all();
        return $sorted_;
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
        // $messages = new Messages();
        // $messages->user_id = Auth::user()->id;
        // $messages->message = $request->message;
        // $messages->sent_to = $request->sent_to;
        // $messages->save();

        $messages = Messages::create([
            'user_id' => Auth::user()->id,
            'message' =>  $request->message,
            'sent_to' => $request->sent_to
        ]);
     
        broadcast(new ChatEvent($messages));
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\messages  $messages
     * @return \Illuminate\Http\Response
     */
    public function show(Messages $messages)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\messages  $messages
     * @return \Illuminate\Http\Response
     */
    public function edit(Messages $messages)
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
    public function update(Request $request, Messages $messages)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\messages  $messages
     * @return \Illuminate\Http\Response
     */
    public function destroy(Messages $messages)
    {
        //
    }

    public function getUserId($username){
        return User::where('username',$username)->first();
    }

    public function logout(){

        $user = User::find(Auth::user()->id);
        $user->is_online = 0;
        $user->save();

        broadcast(new LogoutEvent($user));
    }

    public function getAllUsers(){
        return User::where('is_online',0)->get();
    }
}
