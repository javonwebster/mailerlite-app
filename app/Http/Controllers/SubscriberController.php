<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use MailerLiteApi\Api\Subscribers;
use MailerLiteApi\Exceptions\MailerLiteSdkException;
use MailerLiteApi\MailerLite;

class SubscriberController extends Controller
{
    /**
     * @var MailerLite
     */
    private $mailerLiteClient;
    /**
     * @var Subscribers
     */
    private $subscribersApi;

    public function __construct(){
        $this->middleware(['has.saved.key']);
        $key = DB::table('keys')->whereNotNull('created_at')->first();
        try {
            $this->mailerLiteClient = new MailerLite($key->api_key);
            $this->subscribersApi = $this->mailerLiteClient->subscribers();
        } catch (MailerLiteSdkException $e) {
            //TODO
        }
    }

    public function index(){
        $subscribers = $this->subscribersApi->get();
        return view('subscriber.index',[
            'items'=>$subscribers->toArray()
        ]);
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
