<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\BaseController;
use App\Models\User;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class AuthController extends BaseController
{
    public function login(Request $request)
    {
        $validateUser = Validator::make($request->all(),[
            'email' => 'required|string|email',
            'password' => 'required|string'
        ]);

        if($validateUser->fails()){
            return $this->sendError(false, $validateUser->errors(), 404);
        }

        $user = User::where('email', $request->email)->first();

        if (!$user || !Hash::check($request->password, $user->password)) {
            return $this->sendError(false, 'Invalid credentials');
        }

        $token = $user->createToken('authToken')->plainTextToken;

        return $this->sendResponse(true, 'Login successful!', array_merge($user->toArray(), ['token' => $token]));
    }

    public function logout(Request $request)
    {
        try{
            $request->user()->tokens()->delete();
            return $this->sendResponse(true, 'Logged out successfully!', null);
        } catch(Exception $e){
            return $this->sendError(false, $e->getMessage());
        }

    }
}
