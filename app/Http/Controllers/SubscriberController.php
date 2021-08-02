<?php

namespace App\Http\Controllers;

use App\Models\Country;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use MailerLiteApi\Api\Subscribers;
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
    }

    private function initializeAPI(){
        try {
            $key = DB::table('keys')->whereNotNull('created_at')->first();
            if ($key != null) {
                if (!isset($this->mailerLiteClient) || !isset($this->subscribersApi)) {
                    $this->mailerLiteClient = new MailerLite($key->api_key);
                    $this->subscribersApi = $this->mailerLiteClient->subscribers();
                }
            }
        } catch (\Exception $e) {
            //TODO
        }
    }

    public function index(){
        $this->initializeAPI();
        $subscribers = $this->subscribersApi->get();
        return view('subscriber.index',[
            'items'=>$subscribers->toArray()
        ]);
    }

    public function edit($id){
        $this->initializeAPI();
        $error = null;
        $subscriber = null;
        $country = null;
        try {
            $subscriber = $this->subscribersApi->find($id);
            foreach ($subscriber->fields as $field){
                if ($field->key == 'country'){
                    $country = $field->value;
                    break;
                }
            }
        } catch (\Exception $e) {
            $error = $e->getMessage();
        }
        return view('subscriber.edit',[
            'error'=>$error,
            'item'=>$subscriber,
            'currentCountry'=>$country,
            'countries'=>Country::all()
        ]);
    }

    public function update(Request $request,$id){
        $this->initializeAPI();
        $subscriberData = [
            'fields'=>[
                'name'=>$request->get('name'),
                'country'=>$request->get('country'),
            ]
        ];
        $subscriber = $this->subscribersApi->update($id,$subscriberData);
        return back();
    }

    public function create(){
        $this->initializeAPI();
        return view('subscriber.create');
    }

    public function delete($id): \Illuminate\Http\JsonResponse
    {
        $this->initializeAPI();
        return response()->json(
            [
                'message'=>'subscriber deleted'
            ]);
    }
}
