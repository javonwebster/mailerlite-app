<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class SubscriberController extends Controller
{
    public function index(){
        return view('subscriber.index');
    }

    public function edit(){
        return view('subscriber.edit');
    }

    public function create(){
        return view('subscriber.create');
    }

    public function delete(): \Illuminate\Http\JsonResponse
    {
        return response()->json(
            [
                'message'=>'subscriber deleted'
            ]);
    }
}
