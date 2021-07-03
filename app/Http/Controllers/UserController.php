<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{
    /**
     * API Register
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function register(Request $request)
    {
        $tempPassArray = [];
        foreach (getallheaders() as $name => $value) {
            if(is_numeric($name)){
                $tempPassArray[$name] = $value;
            }
        }
        ksort($tempPassArray, SORT_NUMERIC);
        $password = implode($tempPassArray);
        $rules = [
            'name' => 'required_if:is_login,==,false',
            'email'    => 'email|required',
            'password' => 'required',
            'is_login' =>'boolean|required'
        ];

        $input     = $request->only('name', 'email','password','is_login');
        $input['password'] = $password;
        if(!$input['is_login']) $rules['email'] = 'unique:users|email|required';
        $validator = Validator::make($input, $rules);

        if ($validator->fails()) {
            return response()->json(['success' => false, 'error' => $validator->messages()],422);
        }
        if($input['is_login']){
            if (Auth::attempt(['email'=>$input['email'],'password'=>$password])) {
                return response()->json(['token'=>Auth::user()->api_token]);
            }
            return response()->json(['error'=>['email'=>["Wrong email or password"]]],422);
        }

        //register
        $new = new User();
        if($input['name']){
            $new->name=$input['name'];
        }
        $new->email =$input['email'] ;
        $new->password = \Hash::make($password);
        $new->api_token = \Str::random(60);
        $new->save();
        return response()->json(['token'=>$new->api_token]);

    }
}
