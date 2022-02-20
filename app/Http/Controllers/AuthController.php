<?php

namespace App\Http\Controllers;

use App\Http\Requests\LoginFormRequest;
use App\Http\Requests\RegisterFormRequest;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    public function register(RegisterFormRequest $request)
    {
        $request->merge(["password" => bcrypt(111)]);
        $data = $request->input();

        $user = User::create($data);


        $token = $user->createToken("myToken")->plainTextToken;

        return response()->json(["data" => [
            "user" => $user,
            "token" => $token
        ]], 201);
    }

    public function login(LoginFormRequest $request)
    {
        if (!auth()->attempt(($request->validated()))) {
            return response()->json(["message" => "Invalid credentials!"], 401);
        }

        $token = auth()->user()->createToken(auth()->user()->id)->plainTextToken;

        return response()->json(["data" => [
            "user" => auth()->user(),
            "token" => $token
        ]], 202);
    }

    public function logout()
    {
        auth()->user()->tokens()->delete();

        return response()->json(["message" => "Logged Out!"]);
    }
}
