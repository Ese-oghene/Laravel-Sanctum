<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Facades\Hash;
use Symfony\Component\HttpFoundation\Response;


class AuthController extends Controller
{

    public function register(Request $request){
       return $user = User::create([
            'name' => $request->input('name'),
            'email' => $request->input('email'),
            'password' => Hash::make($request->input('password')) 
        ]);

    }

    public function login(Request $request){
        if (!Auth::attempt($request->only('email', 'password'))) {
          return response([
            'message' => 'Invalid credentials'
          ], Response::HTTP_UNAUTHORIZED);
        }

        // 
        $user = Auth::user();
        $token = $user->createToken('token')->plainTextToken;

       $cokies = cookie('jwt', $token, 60 * 24);
       

       return response([
          'message'=> 'success'
       ])->withCookie($cokies);


    }


    public function user(){

        return Auth::user();
    }

    public function logout(){
      $cookie = Cookie::forget('jwt');

      return response([
        'message' => "success"
      ])->withCookie($cookie);
    }
}
