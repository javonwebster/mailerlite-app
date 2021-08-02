<?php

namespace App\Http\Controllers;

use App\Models\Country;
use App\Rules\SubscriberExists;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;
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
        return view('subscriber.create',[
            'countries'=>Country::all()
        ]);
    }

    public function new(Request $request){
        $this->initializeAPI();
        //validate
        $this->validate($request, [
            'email' => ['required','email', new SubscriberExists()]
        ]);

        //save
        $subscriber = [
            'email' => $request->get('email'),
            'name' => $request->get('name',''),
            'fields' => [
                'country' => $request->get('country',''),
            ]
        ];

        $addedSubscriber = $this->subscribersApi->create($subscriber);
        if (isset($addedSubscriber->error)){
            throw ValidationException::withMessages([
                'email'=>$addedSubscriber->error->message
            ]);
        }

        $request->session()->flash('alert-success', 'Subscriber was successful added!');
        return back();
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
