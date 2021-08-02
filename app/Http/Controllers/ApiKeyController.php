<?php

namespace App\Http\Controllers;

use App\Rules\ValidApiKey;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ApiKeyController extends Controller
{
    public function view(){
        $currentKey = DB::table('keys')->whereNotNull('created_at')->first();
        return view('key.view',[
            'currentKey'=>$currentKey
        ]);
    }

    public function store(Request $request){
        //validate
        $this->validate($request, [
            'api-key' => ['required','string', new ValidApiKey()]
        ]);

        //store to db
        $currentKey = DB::table('keys')->whereNotNull('created_at')->first();
        if ($currentKey === null) {
            DB::table('keys')->insert([
                'api_key'=> $request->get('api-key'),
                'created_at' =>  Carbon::now(),
                'updated_at' => Carbon::now(),
            ]);
        } else {
            DB::table('keys')
                ->whereNotNull('created_at')
                ->update(
                    [
                        'api_key'=> $request->get('api-key'),
                        'updated_at' => Carbon::now(),
                    ]
                );
        }

        return back();
    }
}
