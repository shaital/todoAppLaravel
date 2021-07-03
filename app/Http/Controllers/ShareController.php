<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Models\User;
use App\Models\Todo;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;


class ShareController extends Controller
{
    public function index()
    {
        $users = User::where('id','!=',Auth::id())->get();
        return response()->json(['errors'=>false,'shares'=>$users]);
    }
       /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $input     = $request->only('share')['share'];
        $input['user_id'] = Auth::id();

        $res = Todo::where('id', $input['id'])->where('user_id', $input['user_id'])->first('id');                    


        $rules = [
            'id' => 'required',
            Rule::exists('todo')->where(function ($query,$input) {
                return $query->where('id', $input['id'])->where('user_id', $input['user_id']);
            }),   
            'users_to_share'    => 'required|array'
        ];
        $validator = Validator::make($input, $rules);

        if ($validator->fails()) {
            return response()->json(['success' => false, 'error' => $validator->messages()],422);
        }
        $row = Todo::where('id',$input['id'])->where('user_id',$input['user_id'])->first();
        foreach($input['users_to_share'] AS $user_id){
            if(!empty(User::where('id',$user_id)->first())){
                $row->replicate()->fill([
                    'user_id' => $user_id,
                    'shared_by_user_id'=>$input['user_id'] 
                ])->save();
            }
        }
        $row->shared = 1;
        $row->save(); 
        return response()->json(['success' => true, 'shares' => []],200);
    }
}