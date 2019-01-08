<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Message; 
use App\Events\MessageEvent;

class ChatController extends Controller
{
    public function index()
    {
    	return view('chats');
    }

    public function message()
    {
    	return Message::with('user')->get();
    }

    public function postMessage(Request $req)
    {
    	$message = auth()->user()->messages()->create([
    		'message' => $req->message
    	]);

    	broadcast(new MessageEvent($message->load('user')))->toOthers();

    	return ['status' => 'Send Message Success'];
    }
}
