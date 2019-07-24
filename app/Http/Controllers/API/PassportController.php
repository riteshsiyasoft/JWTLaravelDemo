<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\User;
use Illuminate\Support\Facades\Auth;
use Validator;

class PassportController extends Controller
{
    public $successstatus = 200;

    public function login(Request $request){

    	if(Auth::attempt(['email'=>$request->email,'password'=>$request->password])){
    		$user = Auth::user();
    		$success['tokan'] = $user->createToken('MyApp')->accessToken;
    		return  response()->json(['success'=>$success],$this->successstatus);

    	}else{
			return  response()->json(['error'=>'Unauthorised'],401);
    	}
    }

    public function register(Request $request){

    	$validator = Validator::make($request->all(),[
    		'name' => 'required',
    		'email' => 'required|email',
    		'password' => 'required',
    		'c_password' => 'required|same:password',
    	]);

    	if($validator->fails()){
    		return  response()->json(['error'=>$validator->errors()],401);
    	}

    	$input  = $request->all();
    	$input['password'] = bcrypt($input['password']);
    	$user = User::create($input);
    	$success['tokan'] = $user->createToken('MyApp')->accessToken;
    	$success['name'] = $user->name;
    	return  response()->json(['success'=>$success],$this->successstatus);
    }

    public function getDetails(){
    	$user = Auth::user();
		return response()->json(['success'=>$user],$this->successstatus);
    }
}
