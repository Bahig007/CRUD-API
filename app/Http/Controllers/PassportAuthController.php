<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\Models\User;

class PassportAuthController extends Controller
{
    public function register(Request $request)
    {
        $this->validate($request, [
            'name' => 'required',
            'email' => 'required|email',
            'password' => 'required|min:8'
        ]);
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => bcrypt($request->password),
        ]);
        $token =  $user->createToken('ahmedbahig')->accessToken;
        return response()->json(['token' => $token], 200);
    }

    public function login(Request $request)
    {
        if (Auth::attempt(['email' => $request->email, 'password' => $request->password])) {
            $user = Auth::user();
            $success['token'] =  $user->createToken('ahmedbahig')->accessToken;
            $success['name'] =  $user->name;

            return response()->json(['success' => $success], 200);
        } else {
            return response()->json(['error' => 'unAuthorized']);
        }
    }


    public function userInfo()
    {
        $user =  auth()->user();
        return response()->json(['user' => $user], 200);
    }
}
