<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\User;

use Tymon\JWTAuth\Exceptions\JWTException;
use JWTAuth;

class UserController extends Controller
{
  public function signIn(Request $request)
  {
      $this->validate($request, [
          'email' => 'required|email',
          'password' => 'required'
      ]);
      $credentials = $request->only('email', 'password');
      try {
          if (!$token = JWTAuth::attempt($credentials)) {
              return response()->json([
                  'error' => 'Invalid Credentials!'
              ], 401);
          }
      } catch (JWTException $e) {
          return response()->json([
              'error' => 'Could not create token!'
          ], 500);
      }
      return response()->json([
          'token' => $token
      ], 200);
  }

    public function signUp(Request $request)
    {
        $this->validate($request, [
            'username' => 'required',
            'email' => 'required|email|unique:users',
            'password' => 'required'
        ]);
        $user = new User([
            'username' => $request->input('username'),
            'email' => $request->input('email'),
            'password' => bcrypt($request->input('password'))
        ]);
        $user->save();
        return response()->json([
            'message' => 'Successfully created user!'
        ], 201);
    }

    public function getUser($token = false){
            if (! $user = JWTAuth::parseToken()->authenticate()) {
                return response()->json(['user_not_found'], 404);
            }
        return response()->json(compact('user'));
    }

}
