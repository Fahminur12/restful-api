<?php

namespace App\Http\Controllers;

use App\Models\User;
use Auth;
use Illuminate\Http\Request;
use Validator;

class AuthController extends Controller
{
    public function register(Request $request){
        $validator = Validator::make($request->all(), [
            'name' => 'required|string',
            'email' => 'required|email',
            'password' => 'required'
        ]);

        if($validator->fails()){
            $response = array(
                'success' => false,
                'message' => 'Failed to register, check your input data',
                'data' => null,
                'error' => $validator->errors()
            );

            return response()->json($response, 400);
        }

        $users = User::create($validator->validate());
        $response = array(
            'success' => true,
            'message' => 'Succesfully register',
            'data' => $users
        );

        return response()->json($response, 201);
    }

    public function login(Request $request){
        $validator = Validator::make($request->all(), [
            'email' => 'required|email',
            'password' => 'required'
        ]);
        if($validator->fails()){
            $response = array(
                'success' => false,
                'message' => "Failed to login, check your input data",
                'data' => null,
                'errors' => $validator->errors()
            );

            return response()->json($response, 400);
        }

        $credentials = $request->only('email', 'password');
        if(!$token = auth()->attempt($credentials)){
            $response = array(
                'success' => false,
                'message' => 'Failed to login, wrong username and password',
                'data' => null
            );
            return response()->json($response, 400);
        }

        $response = array(
            'success' => true,
            'message' => 'Successfuly login',
            'data' => auth()->guard('api')->user(),
            'accesstoken' => $token
        );

        return response()->json($response, 200);
    }

    public function logout(Request $request) {
        auth()->invalidate(true);
        auth()->logout();

        return response()->json([
            'success' => true,
            'message' => 'Successfully logged out',
        ], 200);
    }
}
