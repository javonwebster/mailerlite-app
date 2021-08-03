<?php

namespace App\Http\Controllers;

use App\Models\Country;
use App\Rules\SubscriberExists;
use App\Util\Helpers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;
use MailerLiteApi\Api\Subscribers;
use MailerLiteApi\Common\Collection;
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
            return redirect('/');
        }
        return null;
    }

    public function data(Request $request){
        $this->initializeAPI();
        $recordsFiltered = $recordsTotal = $this->subscribersApi->get()->count();
        $columnsList = [
            'email',
            'name',
            'country',
            'date_subscribe_date',
            'date_subscribe_time',
        ];

        $draw = (int) $request->input('draw');
        $limit = (int) $request->input('length',10);
        $offset = (int) $request->input('start',0);
        $field = $columnsList[(int)$request->input('order.0.column',0)];
        $direction = strtoupper($request->input('order.0.dir','asc'));
        $query = $request->input('search.value', null);

        if (isset($query)){
            $subscribers = $this->subscribersApi->search($query);
            $recordsFiltered = count($subscribers);
        } else {
            $subscribers = $this->subscribersApi
                ->limit($limit)
                ->offset($offset)
                ->orderBy($field, $direction)
                ->get();
        }

        $subscribers = $subscribers instanceof Collection ? $subscribers->toArray() : $subscribers;

        $data = [];
        if ($recordsFiltered){
            foreach ($subscribers as $subscriber){
                $item = [
                    'id'=>(string) $subscriber->id,
                    'email'=>$subscriber->email,
                    'name'=>$subscriber->name,
                    'country'=>Helpers::getSubscriberCountry($subscriber),
                    'date_subscribe_date'=>Helpers::getSubscriberDateSubscribed($subscriber),
                    'date_subscribe_time'=>Helpers::getSubscriberDateSubscribed($subscriber, true),
                ];
                $data[] = $item;
            }
        }

        return response()->json(
            [
                'data'=>$data,
                'limit'=>$limit,
                'offset'=>$offset,
                'field'=>$field,
                'direction'=>$direction,
                'query'=>$query,
                'draw'=>$draw,
                'recordsTotal'=>$recordsTotal,
                'recordsFiltered'=>$recordsFiltered,
            ]
        );
    }

    public function index(){
        return view('subscriber.index',);
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
        try {
            $subscriber = $this->subscribersApi->update($id,$subscriberData);
            $request->session()->flash('alert-success', 'Subscriber was successful updated!');
        } catch (\Exception $exception) {
            $request->session()->flash('alert-error', 'An error occurred. Please try again');
        }
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
        $response = $this->subscribersApi->delete($id);
        if (isset($response)) {
            return response()->json(
                [
                    'message'=>$response->error->message,
                    'code'=>$response->error->code
                ],
                404
            );
        }
        return response()->json(
            [
                'message'=>'subscriber deleted'
            ]);
    }
}
