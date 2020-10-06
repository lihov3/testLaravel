<?php

namespace App\Http\Controllers\Api\Auth;

use App\Http\Controllers\Api\Controller;
use App\Http\Controllers\Api\AccessoryController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use JWTAuth;
use Illuminate\Support\Facades\Validator;

class LoginController extends Controller
{
    public function login(Request $req)
    {
    	$data = $req->only(['email', 'password']);
    	if(!$token = auth()->attempt($data))
    	{
    		return response()->json(['error' => true, 'message' => 'auth is incorrect'], 401);
    	}
    	return AccessoryController::respondWithToken($token, 'Login Success', 201);
    }

    public function registration(Request $req)
    {
    	$data = $req->all();

    	$rules = [
            'email' => 'required|string',
            'name' => 'required|string|max:100',
            'password' => 'required|string|min:8',
        ];
    	$validator = Validator::make($data, $rules);
    	if($validator->fails()){
            return response()->json(['error' => true, 'message' => $validator->errors()->first()], 422);
        }

        $user = User::select('id')->where('email', '=', $data['email']); 
        if($user){
            return response()->json(['error' => true, 'message' => 'User with this email is registered'], 200);
        }

        $user = User::create([
	                "name" => $data['name'],
	                "email" => $data['email'],
	                "password" => Hash::make($data['password']),
	            ]);	

    	if (!$token = JWTAuth::fromUser($user)) {
            return response()->json(['error' => true, 'message' => 'Invalid Token. Try login later.'], 500);
        }
        return AccessoryController::respondWithToken($token, 'Registration Success', 201);
    }
}
